<?php
require_once 'http_response.php';

/**
 * Base class for handling REST based calls.
 *
 * URLs should be handled in the convention specified in the REST microformat.
 * http://microformats.org/wiki/rest/urls
 */
class ResourceController
{
    protected $methodFunctionMap = array('POST' => 'create', 'GET' => 'read',
        'PUT' => 'update', 'DELETE' => 'delete', 'INDEX' => 'index',
        'NEW' => 'createForm');

    /**
     * Echos the output of the dispatch.
     *
     * @param string $url    The URL to process for resource handling.
     * @param string $method The method on which to base processing. Allows
     *                       overriding of actual request method for embedded use.
     *                       If null, the actual request method is used.
     *
     * @return void
     */
    function display($url = null, $method = null)
    {
        echo $this->dispatch($url, $method);
    }

    /**
     * Dispatch the given URL to the proper resource handler.
     *
     * @param string $url    The URL to process for resource handling.
     * @param string $method The method on which to base processing. Allows
     *                       overriding of actual request method for embedded use.
     *
     * @return The output of resource processing.
     */
    function dispatch($url = null, $method = null)
    {
        $entity = null;
        $id = null;

        if (empty($url)) {
            // get everything after the name of this script
            $url = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
        }

        // separate the uri into elements split on /
        if (substr($url, 0, 1) == '/') {
            $url = substr($url, 1);
        }

        // separate the uri into elements split on /
        $elements = explode('/', $url);
        $num_elements = count($elements);
        if ($num_elements >= 1) {
            $entity = urldecode($elements[0]);
            $resource = RESOURCES_DIR.$entity.".php";
            if (file_exists($resource)) {
                include $resource;
            } else {
                die("Unable to load requested resource [$resource]");
            }
        }

        if ($num_elements >= 2) {
            $id = $this->getId(urldecode($elements[1]));
        }

        // get the output format
        $format = $this->getFormat($url);

        // get the request method if not provided
        if (empty($method)) {
            $method = $this->getMethod();
        }

        $results = null;
        $actualMethod = $method;

        switch ($method) {
        // read
        case 'GET':
            if (!empty($id)) {
                if ($id == 'new') {
                    $actualMethod = 'NEW';
                    $results = $this->_callUserFunc($entity, $actualMethod);
                } else {
                    $results = $this->_callUserFunc($entity, $method, $id);
                }
            } else {
                // get a list of the current user's data
                $actualMethod = 'INDEX';
                $results = $this->_callUserFunc($entity, $actualMethod);
            }
            break;

        case 'POST': // update
        case 'PUT': // create
            $data = $_POST['data'];
            $results = $this->_callUserFunc($entity, $method, $data);
            break;

        // delete
        case 'DELETE':
            $results = $this->_callUserFunc($entity, $method, $id);
            break;
        }

        //var_dump($results);
        //echo "<br/><br/>";
        //if (!empty($results)) {
        //    $results[1] = $this->transform($results[1], $format, $entity, $method);
        //}
        //var_dump($results);
        //sendResponseCode($results[0], $results[1]);

        $functionName = $this->_functionName($actualMethod);
        if ($results === true) {
            sendResponseCode(204);
        } elseif ($results === false) {
            sendResponseCode(400);
        } elseif (is_numeric($results)) {
            sendResponseCode($results);
        } elseif (is_array($results)) {
            $response_code = $results[0];
            $content = $results[1];
            $headers = $results[2];
            $output = $this->transform($content, $format, $entity, $functionName);
            sendResponseCode($response_code, $output, $headers);
        } elseif ($results != null) {
            $output = $this->transform($results, $format, $entity, $functionName);
            sendResponsecode($response_code, $output);
        }
    }

    /**
     * Get the requested response format based on the name of the requested
     * playlist.
     *
     * @param string $name The entity name to search for a specified format.
     *
     * @return The format found on the entity.  'json' if none found.
     */
    protected function getFormat($name)
    {
        // set the default format
        //$format = 'html';
        $format = 'json';

        $last_slash = strrpos($name, '/');
        $last_dot = strrpos($name, '.');

        if ($last_slash === false) {
            $last_slash = -1;
        }

        if ($last_dot !== false && $last_dot < strlen($name) - 1
            && $last_dot > $last_slash
        ) {
            $format = substr($name, $last_dot + 1);
        }

        return $format;
    }

    /**
     * Get the method used on the current request.
     *
     * @return string The method used to perform the current request.  If GET or
     *                POST, a request parameter of '_method' is checked to override
     *                the method.
     */
    protected function getMethod()
    {
        // only check for _method when REQUEST_METHOD = (GET|POST)
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'GET' or $method == 'POST') {
            if (! empty($_GET['_method'])) {
                $method = strtoupper($_GET['_method']);
            }
        }
        return $method;
    }

    /**
     * Get the ID from a given element.
     *
     * @param string $name The element to search for an ID.
     *
     * @return The ID found in the element.  The ID is considered to be the last
     *         element after the last forward slash (/) but before the last dot (.).
     */
    protected function getId($name)
    {
        $id = '';
        $last_slash = strrpos($name, '/');
        $last_dot = strrpos($name, '.');

        if ($last_slash === false) {
            $last_slash = -1;
        }

        if ($last_dot === false && $last_slash === false) {
            // neither dot nor slash was found
            $id = $name;
        } elseif ($last_dot !== false && $last_dot > $last_slash) {
            // a dot was found after a slash
            $id = substr($name, $last_slash + 1, $last_dot);
        } else {
            $id = substr($name, $last_slash + 1);
        }

        return $id;
    }

    /**
     * Factory method to transform json into a different format.
     *
     * @param array  $results The output from a resource to be transformed.
     * @param string $format  The format to transform to.
     *
     * @return The results transformed to the requested format.
     */
    protected function transform($data, $format, $entity = null, $function = null)
    {
        $output = false;

        // send back the array if no format or 'raw'
        if (empty($format) or $format == 'raw') {
            $output = $data;
        } elseif ($format == 'json') {
            $output = json_encode($data);
        } else {
            include_once SMARTY_DIR . 'Smarty.class.php';
            $smarty = new Smarty;
            $smarty->assign('title', $data['title']);

            $templates = array();
            $templates[] = "$entity/$function.$format.tpl";
            $templates[] = "$entity/$format.tpl";
            $templates[] = "$format.tpl";
            foreach ($templates as $template) {
                if ($smarty->template_exists($template)) {
                    $smarty->assign('data', $data);
                    $output = $smarty->fetch($template);
                    break;
                }
            }
            //if (!$output) {
            //    $output = "Unable to read template [$template]";
            //}
        }
        return $output;
    }

    /**
     * Calls a user function based on the entity and key provided.  If data is not
     * null, it is passed to the function.
     *
     * @param string $entity The entity to limit call interest to.
     * @param string $key    The key to filter the function name down to.
     * @param mixed  $data   The data to pass to the function.
     *
     * @return void
     */
    private function _callUserFunc($entity, $key, $data = null)
    {
        $functionName = $entity . '_' . $this->_functionName($key);
        if (function_exists($functionName)) {
            return call_user_func($functionName, $data);
        } else {
            die("The requested function doesn't exist [$functionName]");
        }
    }

    /**
     * Returns the funcation name associated to a given key.
     *
     * @param $key The key to use for looking up the function name.
     *
     * @return The function name mapped to the key.  false, otherwise.
     */
    private function _functionName($key)
    {
        $functionName = false;
        if (array_key_exists($key, $this->methodFunctionMap)) {
            $functionName = $this->methodFunctionMap[$key];
        }
        return $functionName;
    }
}
?>
