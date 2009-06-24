<?php
/**
 * Delete a specific playlist in the logged in user's space.
 *
 * @param name The name of the playlist to be deleted.  The name is resolved
 *             to the user's space.
 * @return associative array with 'code' and 'output' keys. 'code' is the
 *             http response code.  'output' is any results or message to
 *             be sent back to the user.
 */
function playlist_delete($name)
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
    return array('code' => $response_code, 'output' => $output);
}

/**
 * Get all playlists available to the current logged in user.
 *
 * @return associative array with 'code' and 'output' keys. 'code' is the
 *             http response code.  'output' is any results or message to
 *             be sent back to the user.
 */
function playlist_index()
{
    $output = '';
    $response_code = 0;

    $dir = scandir(PLAYLISTS_DIR . "/${_SERVER['REMOTE_USER']}");
    if ($dir) {
        $playlists = array();
        foreach ($dir as $file) {
            if ($file != '.' && $file != '..') {
                $playlists[] = $file;
            }
        }

        $output = array('data' => $playlists);
        $response_code = 200;
    } else {
        $output = 'Unable to open playlists folder for user.';
        $response_code = 500;
    }
    return array('code' => $response_code, 'output' => $output);
}

/**
 * Get a specific playlist from a user's space.
 *
 * @param string $name The name of the playlist to retrieve.
 *
 * @return associative array with 'code' and 'output' keys. 'code' is the
 *             http response code.  'output' is any results or message to
 *             be sent back to the user.
 */
function playlist_read($name)
{
    $output = '';
    $response_code = 0;

    $filename = $this->build_filename($name);
    if (is_readable($filename)) {
        $file = fopen($filename, 'r');
        $json = fread($file, filesize($filename));
        $output = json_decode($json);
        fclose($file);
        $response_code = 200;
    } else {
        $output = "File is not readable: ${filename}";
        $response_code = 404;
    }
    return array('code' => $response_code, 'title' => $name, 'output' => $output);
}

/**
 * Create a playlist.  Same as calling update($name, $playlist) as that
 * function also handles creating.
 *
 * @param string $name     The name of the playlist to save.
 * @param string $playlist The playlist to save.
 *
 * @see update($name, $playlist)
 *
 * @return associative array with 'code' and 'output' keys. 'code' is the
 *             http response code.  'output' is any results or message to
 *             be sent back to the user.
 */
function playlist_create($name, $playlist)
{
    return update($name, $playlist);
}

/**
 * Save a playlist.  If the playlist doesn't exist, it is created.  Otherwise,
 * it is updated.
 *
 * @param string $name     The name of the playlist to save.
 * @param string $playlist The playlist to save.
 *
 * @return associative array with 'code' and 'output' keys. 'code' is the
 *             http response code.  'output' is any results or message to
 *             be sent back to the user.
 */
function playlist_update($name, $playlist)
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
    return array('code' => $response_code, 'output' => $message);
}

//===================================================================
//   utility functions
//===================================================================
/**
 * Build the filename to a specific playlist.  The filename is built in relation
 * to the user's space.  The filename is not verified to exist.
 *
 * @param string $name The name of the playlist to work with.
 *
 * @return filename with the playlist dir prepended.
 */
function build_filename($name)
{
    $filename = "{playlist_dir()}/$name";
    return $filename;
}

function playlist_dir()
{
    $dir = PLAYLISTS_DIR."/{$_SERVER['REMOTE_USER']}";
    return $dir;
}
?>
