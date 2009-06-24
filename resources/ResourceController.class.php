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
    function display()
    {
        echo $this->dispatch();
    }

    function dispatch($url)
    {
        $entity = null;
        $id = null;

        if (empty($url)) {
            // get everything after the name of this script
            $url = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
        }

        // separate the uri into elements split on /
        $elements = explode('/', $url);

        // separate the uri into elements split on /
        $elements = explode('/', $url);
        $num_elements = count($elements);
        $resource = null;
        if ($num_elements >= 1) {
            $entity = urldecode($elements[0]);
            if (file_exists(RESOURCES_DIR.$entity)) {
                include RESOURCES_DIR.$entity;
            } else {
                die("Unable to load requested resource [$entity]");
            }
        }

        if ($num_elements >= 2) {
            $id = $this->getId(urldecode($elements[1]));
        }

        // get the output format
        $format = $this->getFormat($url);

        // get the request method
        $method = $this->getMethod();

        $results = null;

        switch ($method) {
        // read
        case 'GET':
            if (!empty($id)) {
                if ($id == 'new') {
                    call_user_func("{$entity}_createForm");
                } else {
                    call_user_func("{$entity}_read", $id);
                }
            } else {
                // get a list of the current user's data
                call_user_func("{$entity}_index");
            }
            break;

        // update
        case 'POST':
            $data = $_POST['data'];
            call_user_func("{$entity}_create", $data);
            break;

        // create
        case 'PUT':
            $data = $_POST['data'];
            call_user_func("{$entity}_update", $data);
            break;

        // delete
        case 'DELETE':
            call_user_func("{$entity}_delete", $id);
            break;
        }

        $results = null;
        if (!empty($resource->output)) {
            $results = $this->transform($resource->output, $format);
        }
        send_response_code($resource->response_code, $results);

        /*
        if ($results === true) {
            send_response_code(204);
        } elseif ($results === false) {
            send_response_code(400);
        } elseif (is_numeric($results)) {
            send_response_code($results);
        } elseif ($results != null) {
            $output = $this->transform($results, $format);
            return $output;
        }
        */
    }

    /**
     * Get the requested response format based on the name of the requested
     * playlist.
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
     */
    function transform($results, $format)
    {
        // initialize templating
        include_once SMARTY_DIR . 'Smarty.class.php';
        $smarty = new Smarty;
        $smarty->assign('title', $results['title']);

        $data = $results['output'];

        $output = '';
        // send back the array if no format or 'raw'
        if (empty($format) or $format == 'raw') {
            $output = $data;
        } elseif ($format == 'json') {
            $output = json_encode($data);
        } else {
            $template = "{$format}.tpl";
            if (is_readable("{$smarty->template_dir}/$template")) {
                $smarty->assign('data', $data);
                $output = $smarty->fetch($template);
            } else {
                $output = "Unable to read template [$template]";
            }
        }
        return $output;
    }
}
?>
