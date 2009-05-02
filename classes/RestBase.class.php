<?php
require_once 'http_response.php';

/**
 * Base class for handling REST based calls.
 *
 * URLs should be handled in the convention specified in the REST microformat.
 * http://microformats.org/wiki/rest/urls
 */
abstract class RestBase {
    protected $rest_uri = null;
    protected $method = '';
    protected $id = null;
    protected $format = null;

    function __construct() {
        // get everything after the name of this script
        $this->rest_uri = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));

        // separate the uri into elements split on /
        $elements = explode('/', $this->rest_uri);
        if (sizeof($elements) > 1) {
            $name = urldecode($elements[1]);
            $this->id = $this->get_id($name);
            $this->format = $this->get_format($name);

            //if ($this->format) {
            //    $this->name = substr($this->name, 0, strlen($this->name) - strlen($this->format) - 1);
            //}
        }

        $this->method = isset ($_GET['_method']) ? $_GET['method'] : $_SERVER['REQUEST_METHOD'];
    }

    function process()
    {
        $results = null;
        $playlist = $_POST['playlist'];
        switch ($this->method) {
            // read
            case 'GET' :
                if (isset ($this->id)) {
                    $results = $this->read($this->id, $this->format);
                } else {
                    // get a list of the current user's playlists
                    $results = $this->index();
                }
                break;

            // update & create
            case 'POST' :
                $results = $this->create($name, $playlist);
                break;

            case 'PUT' :
                $results = $this->update($name, $playlist);
                break;

                // delete
            case 'DELETE' :
                $results = $this->delete($name);
                break;
        }

        if ($results) {
            $output = $this->transform($playlist, $results, $this->format);
            echo $output;
            /*
            switch(strtolower($this->format)) {
                case 'json':
                    break;

                case 'xml':
                    break;

                default:
                    break;
            }
            */
        }
    }

    /**
     * Default implementation for GET with no provided ID.
     */
    function index() {
        send_response_code(405);
    }

    /**
     * Default implementation for GET with provided ID.
     */
    function read($name) {
        send_response_code(405);
    }

    /**
     * Default implementation for POST.
     */
    function create($name, $playlist) {
        send_response_code(405);
    }

    /**
     * Default implementation for DELETE or _method=delete.
     */
    function delete() {
        send_response_code(405);
    }

    /**
     * Default implementation for PUT or _method=put.
     */
    function update() {
        send_response_code(405);
    }

    /**
     * Get the requested response format based on the name of the requested
     * playlist.
     */
    protected function get_format($name) {
        // set the default format
        $format = 'html';

        $last_slash = strrpos($name, '/');
        $last_dot = strrpos($name, '.');

        if ($last_slash === false) {
            $last_slash = -1;
        }

        if ($last_dot !== false && $last_dot < strlen($name) - 1
                && $last_dot > $last_slash) {
            $format = substr($name, $last_dot + 1);
        }

        return $format;
    }

    protected function get_id($name) {
        $id = '';
        $last_slash = strrpos($name, '/');
        $last_dot = strrpos($name, '.');

        if ($last_slash === false) {
            $last_slash = -1;
        }
 
        // neither dot nor slash was found
        if ($last_dot === false && $last_slash === false) {
            $id = $name;
        // a dot was found after a slash
        } elseif ($last_dot !== false && $last_dot > $last_slash) {
            $id = substr($name, $last_slash + 1, $last_dot);
        } else {
            $id = substr($name, $last_slash + 1);
        }

        return $id;
    }

    /**
     * Factory method to transform json into a different format.
     */
    protected function transform($title, $results, $format)
    {
        //require_once('lib/transformers.php');
        require '/usr/share/php/smarty/Smarty.class.php';
        // initialize templating
        $smarty = new Smarty;
        // line ending
        $data = $results['output'];
        $playlists = $data['playlists'];

        $output = '';
        "$template_dir/$format.tpl";
        
        if ($format == 'atom') {
            $output = marshall_atom($title, $playlists);
        } elseif ($format == 'xspf') {
            $output = marshall_xspf($title, $playlists);
//        } elseif ($format == 'json') {
//        } elseif ($format == 'html') {
        } else {
            $output = json_encode($data);
        }

        return $output;
    }
}
?>
