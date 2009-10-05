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
    protected $methodFunctionMap = array(
        'GET' => 'read',
        'POST' => 'create',
        'PUT' => 'update',
        'DELETE' => 'delete',
        'INDEX' => 'index',
        'NEW' => 'createForm'
    );

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

        $url = $this->_cleanUrl($url);

        // check for a / which denotes something after the entity
        $slash_pos = strpos($url, '/');
        if ($slash_pos === false) {
            // if no slash then only an entity is named
            $entity = urldecode($url);
        } else {
            // slash was found so split into entity and other
            $entity = urldecode(substr($url, 0, $slash_pos));
            $id = $this->getId(urldecode($url));
        }

        // load the resource
        $resource = RESOURCES_DIR.$entity.".php";
        if (file_exists($resource)) {
            include $resource;
        } else {
            die("Unable to load requested resource [$resource]");
        }

        // get the output format
        $format = $this->getFormat($url);

        // get the request method if not provided
        if (empty($method)) {
            $method = $this->getMethod();
        }

//        echo "$entity, $id, $format, $method<br/>";

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

//        var_dump($results);
//        echo '<br/><br/>';
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
        } elseif (is_array($results) && count($results) == 3) {
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
     * @param string $rest_url The rest URL. Should start with the entity being
     *                         requested.
     *
     * @return The ID found in the element.  The ID is considered to be the last
     *         element after the first forward slash (/) but before the last dot (.).
     */
    protected function getId($rest_url)
    {
        $id = '';
        $first_slash = strpos($rest_url, '/');

        if ($first_slash !== false) {
            $other = substr($rest_url, $first_slash + 1);
            $last_slash = strrpos($rest_url, '/');
            $last_dot = strrpos($other, '.');

            // set to -1 if no last slash found
            if ($last_slash === false) {
                $last_slash = -1;
            }

            if ($last_dot === false) {
                // no dot found, slash was found
                $id = $other;
            } elseif ($last_dot !== false && $last_dot > $last_slash) {
                // a dot was found after a slash
                $id = substr($other, 0, $last_dot);
            } else {
                $id = substr($other, 0);
            }
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
            if (!$output) {
                $output = "Unable to read template [$template]";
            }
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
    private function _callUserFunc($entity, $method, $data = null)
    {
        $functionName = $entity . '_' . $this->_functionName($method);
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

    /**
     * Cleans a URL for consumption in REST calls.  If no URL is provided, the
     * REQUEST_URI and SCRIPT_NAME are used to construct the URL.
     *
     * @param <string> $url
     * @return <string> The URL without beginning or trailing slashes.
     */
    private function _cleanUrl($url)
    {
        if (empty($url)) {
            // get everything after the name of this script
            $url = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
        }

        // remove any starting /'s
        while (substr($url, 0, 1) == '/') {
            $url = substr($url, 1);
        }

        // remove any trailing /'s
        while (substr($url, strlen($url) - 1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }
}
?>