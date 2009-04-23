<?php
require_once ('http_response.php');
require_once ('config.php');

/**
 * Delete a specific playlist in the logged in user's space.
 *
 * @param name The name of the playlist to be deleted.  The name is resolved to
 *             the user's space.
 */
function delete($name)
{
    $response_code = 0;
    $output = '';

    $filename = build_filename($name);
    error_log("Deleting $filename");

    if (is_writeable($filename)) {
        if (unlink($filename)) {
            $response_code = 204;
        } else {
            $response_code = 422;
            $output = "Unable to unlink '$filename': $unlinked";
        }
    } else {
        $response_code = 422;
        $output = "File '$filename' is not writeable.";
    }

    error_log("Delete: $response_code - $output");
    send_response_code($response_code);
    echo $output;
}

/**
 * Get all playlists available to the current logged in user.
 */
function get_private_list()
{
    $response_code = 0;
    $output = '';

    $playlists = array ();
    $dir = scandir(PLAYLISTS_DIR . "/${_SERVER['REMOTE_USER']}");
    if ($dir) {
        foreach ($dir as $file) {
            if ($file != '.' && $file != '..') {
                $playlists[] = $file;
            }
        }

        $response_code = 200;
        $output = "{'playlists':['" . implode("','", $playlists) . "']}";
    } else {
        $response_code = 500;
        $output = 'Unable to open playlists folder for user.';
    }
    send_response_code($response_code);
    echo $output;
}

/**
 * Get all playlists available to the current logged in user.
 */
function get_all()
{
    $response_code = 0;
    $output = '';

    $playlists = array ();
    $dir = scandir(PLAYLISTS_DIR);
    if ($dir) {
        foreach ($dir as $file) {
            if ($file != '.' && $file != '..') {
                $playlists[] = $file;
            }
        }

        $response_code = 200;
        $output = "{'playlists':['" . implode("','", $playlists) . "']}";
    } else {
        $response_code = 500;
        $output = 'Unable to open playlists folder for user.';
    }
    send_response_code($response_code);
    echo $output;
}

/**
 * Get a specific playlist from a user's space.
 *
 * @param name The name of the playlist to retrieve.
 */
function get($name, $format)
{
    $response_code = 0;
    $output = '';

    $filename = build_filename($name);
    $data = '[]';
    if (is_readable($filename)) {
        $file = fopen($filename, 'r');
        $json = fread($file, filesize($filename));
        fclose($file);
        $output = transform($name, $json, $format);
        $response_code = 200;
    } else {
        $response_code = 404;
        $output = "File is not readable: ${filename}";
    }
    send_response_code($response_code);
    echo $output;
}

/**
 * Save a playlist.  If the playlist doesn't exist, it is created.  Otherwise,
 * it is updated.
 *
 * @param name The name of the playlist to save.
 */
function save($name, $playlist)
{
    $response_code = 0;
    $message = '';
    // create the filename by prepending the user name to the playlist name

    if (is_writeable(playlist_dir())) {
    	$filename = build_filename($name);
        // persist the playlist to file
        $file = fopen($filename, 'w');
        fwrite($file, stripslashes($playlist));
        fclose($file);
        $response_code = 201;
    } else {
        $response_code = 401;
        $message = 'Unable to write playlist.';
    }
    send_response_code($response_code);
    echo $message;
}

//===================================================================
//   utility functions
//===================================================================
/**
 * Build the filename to a specific playlist.  The filename is built in relation
 * to the user's space.  The filename is not verified to exist.
 *
 * @param name The name of the playlist to work with.
 */
function build_filename($name)
{
    $filename = playlist_dir(). "/${name}";
    return $filename;
}

function playlist_dir()
{
    $dir = PLAYLISTS_DIR . "/${_SERVER['REMOTE_USER']}";
	return $dir;
}

/**
 * Get the requested response format based on the name of the requested playlist.
 *
 * Understood formats: json, xspf, atom
 */
function get_format($name)
{
    $format = null;

    if (preg_match('/\.xspf$/', $name)) {
        $format = 'xspf';
    } else
        if (preg_match('/\.atom$/', $name)) {
            $format = 'atom';
        } else
            if (preg_match('/\.json$/', $name)) {
                $format = 'json';
            }

    return $format;
}

/**
 * Factory method to transform json into a different format.
 */
function transform($title, $json, $format)
{
    require_once('lib/transformers.php');
    // line ending
    $data = json_decode($json, true);

    $output = '';
    if ($format == 'atom') {
		$output = marshallAtom($title, $data);
    } elseif ($format == 'xspf') {
		$output = marshallXspf($title, $data);
    } else {
        $output = $json;
    }

    return $output;
}

//===================================================================
//   main processing
//===================================================================

//
// URLs should be handled in the convention specified in the REST microformat.
// http://microformats.org/wiki/rest/urls
//

// get everything after the name of this script
$rest_uri = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));

// separate the uri into elements split on /
$elements = explode('/', $rest_uri);
if (sizeof($elements) > 1) {
    $name = urldecode($elements[1]);
    $format = get_format($name);

    if ($format) {
        $name = substr($name, 0, strlen($name) - strlen($format) - 1);
    }
}

$method = isset ($_GET['_method']) ? $_GET['method'] : $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // read
    case 'GET' :
        if (isset ($name)) {
            get($name, $format);
        } else {
            // get a list of the current user's playlists
            get_private_list();
            // get a list of all other user's playlists
            //            get_all();
        }
        break;

        // update & create
    case 'POST' :
    case 'PUT' :
        $playlist = $_POST['playlist'];
        save($name, $playlist);
        break;

        // delete
    case 'DELETE' :
        delete($name);
        break;
}
?>
