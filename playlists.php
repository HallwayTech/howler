<?php
require_once 'classes/RestBase.class.php';
require_once 'http_response.php';
require_once 'config.php';

class PlaylistsRest extends RestBase
{
    /**
     * Delete a specific playlist in the logged in user's space.
     *
     * @param name The name of the playlist to be deleted.  The name is resolved to
     *             the user's space.
     */
    function delete($name)
    {
        $output = '';
        $response_code = 0;

        $filename = $this->build_filename($name);
        error_log("Deleting $filename");

        if (is_writeable($filename)) {
            if (unlink($filename)) {
                $response_code = 204;
            } else {
                $output = "Unable to unlink '$filename': $unlinked";
                $response_code = 422;
            }
        } else {
            $output = "File '$filename' is not writeable.";
            $response_code = 422;
        }

        error_log("Delete: $response_code - $output");
        send_response_code($response_code);
        echo $output;
    }

    /**
     * Get all playlists available to the current logged in user.
     */
    function index()
    {
        $output = '';
        $response_code = 0;

        $playlists = array ();
        $dir = scandir(PLAYLISTS_DIR . $_SERVER['REMOTE_USER']);
        if ($dir) {
            foreach ($dir as $file) {
                if ($file != '.' && $file != '..') {
                    $playlists[] = $file;
                }
            }

            $output = "{'playlists':['" . implode("','", $playlists) . "']}";
            $response_code = 200;
        } else {
            $output = 'Unable to open playlists folder for user.';
            $response_code = 500;
        }
        send_response_code($response_code);
        echo $output;
    }

    /**
     * Get all playlists available to the current logged in user.
     */
    function doIndexPublic()
    {
        $output = '';
        $response_code = 0;

        $playlists = array ();
        $dir = scandir(PLAYLISTS_DIR);
        if ($dir) {
            foreach ($dir as $file) {
                if ($file != '.' && $file != '..') {
                    $playlists[] = $file;
                }
            }

            $output = "{'playlists':['" . implode("','", $playlists) . "']}";
            $response_code = 200;
        } else {
            $output = 'Unable to open playlists folder for user.';
            $response_code = 500;
        }
        send_response_code($response_code);
        echo $output;
    }

    /**
     * Get a specific playlist from a user's space.
     *
     * @param name The name of the playlist to retrieve.
     */
    function read($name, $format)
    {
        $output = '';
        $response_code = 0;

        $filename = $this->build_filename($name);
        $data = '[]';
        if (is_readable($filename)) {
            $file = fopen($filename, 'r');
            $json = fread($file, filesize($filename));
            fclose($file);
            $output = parent::transform($name, $json, $format);
            $response_code = 200;
        } else {
            $output = "File is not readable: ${filename}";
            $response_code = 404;
        }
        send_response_code($response_code);
        echo $output;
    }

    /**
     * Create a playlist.  Same as calling update($name, $playlist) as that
     * function also handles creating.
     */
    function create($name, $playlist) {
        update($name, $playlist);
    }

    /**
     * Save a playlist.  If the playlist doesn't exist, it is created.  Otherwise,
     * it is updated.
     *
     * @param name The name of the playlist to save.
     */
    function update($name, $playlist)
    {
        $message = '';
        $response_code = 0;
        // create the filename by prepending the user name to the playlist name

        if (is_writeable($this->playlist_dir())) {
        	$filename = $this->build_filename($name);
            // persist the playlist to file
            $file = fopen($filename, 'w');
            fwrite($file, stripslashes($playlist));
            fclose($file);
            $response_code = 201;
        } else {
            $message = 'Unable to write playlist.';
            $response_code = 401;
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
    protected function build_filename($name)
    {
        $filename = $this->playlist_dir() . "/${name}";
        return $filename;
    }

    protected function playlist_dir()
    {
        $dir = PLAYLISTS_DIR . "/${_SERVER['REMOTE_USER']}";
    	return $dir;
    }
}

$rest = new PlaylistsRest;
$rest->process();
?>
