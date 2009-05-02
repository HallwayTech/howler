<?php
require_once 'http_response.php';

/**
 * Base class for handling REST based calls.
 *
 * URLs should be handled in the convention specified in the REST microformat.
 * http://microformats.org/wiki/rest/urls
 */
abstract class RestBase {
    protected $name = null;
    protected $rest_uri = null;
    protected $method = '';
    protected $id = null;
    protected $format = null;

    /**
     * Constructor for RESTful objects.
     */
    function __construct($name) {
        $this->name = $name;
        // get everything after the name of this script
        $this->rest_uri = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));

        // separate the uri into elements split on /
        $elements = explode('/', $this->rest_uri);
        if (sizeof($elements) > 1) {
            $name = urldecode($elements[1]);
            $this->id = $this->get_id($name);
            $this->format = $this->get_format($name);
        }

        $this->method = isset($_GET['_method']) ? $_GET['method'] : $_SERVER['REQUEST_METHOD'];
    }

    function display()
    {
        echo $this->fetch();
    }

    function fetch()
    {
        $results = null;
        $playlist = $_POST['playlist'];
        switch ($this->method) {
            // read
            case 'GET' :
                if (isset($this->id)) {
                    $results = $this->read($this->id);
                } else {
                    // get a list of the current user's playlists
                    $results = $this->index();
                }
                break;

            // update
            case 'POST' :
                $results = $this->create($name, $playlist);
                break;

            // create
            case 'PUT' :
                $results = $this->update($name, $playlist);
                break;

                // delete
            case 'DELETE' :
                $results = $this->delete($name);
                break;
        }

        if ($results) {
            $output = $this->transform($results, $this->format);
            return $output;
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
        //$format = 'html';
        $format = 'json';

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
    function transform($results, $format)
    {
        // initialize templating
        require_once '/usr/share/php/smarty/Smarty.class.php';
        $smarty = new Smarty;
        $smarty->assign('title', $results['title']);

        $playlists = $results['output'];

        $output = '';
		// send back the array if no format or 'raw'
        if (!isset($format) or $format == 'raw') {
            $output = $playlists;
        } elseif ($format == 'json') {
            $output = json_encode($playlists);
        } else {
            $template = "{$this->name}.{$format}.tpl";
            if (is_readable("{$smarty->template_dir}/$template")) {
                $smarty->assign('playlists', $playlists);
                $output = $smarty->fetch($template);
            } else {
                $output = "Unable to read template [$template]";
            }
        }
        return $output;
    }
}
?>
