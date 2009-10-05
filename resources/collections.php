<?php
require_once 'lib/id3.php';
require_once 'http_utils.php';

/**
 * Handler method for the base page
 */
function collections_read($id)
{
    // get whatever is after the name of the script
    $uri = http_script_uri();

    // build the lists of files and dirs
    $output = array();
    $dirs = array();
    $files = array();

    if (!empty($uri)) {
        // get a list of dirs and show them
        $path = MUSIC_DIR."/$id";
        $dir_list = scandir($path);
        foreach ($dir_list as $f) {
            // continue if $f is current directory '.' or parent directory '..'
            if ($f == '.' || $f == '..') {
                continue;
            }

            // create directory reference by concatenating path and the current
            // directory listing item
            $abs_dir = "$path/$f";
            // create a short name. be sure it doesn't start with a slash
//            $rel_dir = "$uri/$f";
            $rel_dir = $id;
            // determine the file extension
            $path_info = pathinfo($f);
            $dirname = $path_info['dirname']; // /var/www
            $basename = $path_info['basename']; //  index.html
            $filename = $path_info['filename']; //  index
            $extension = strtolower($path_info['extension']); //  html

            if (is_dir($abs_dir)) {
                // build the output for a dir
                /*
                $output['d'][] = array (
                    'dr' => $rel_dir,
                    'l' => $f
                    'l' => $f
                );
                */
                $dirs[] = $f;
            } elseif ($extension == 'mp3') {
                // build the output for a file
                $f = utf8_encode($f);
                $rel_dir = utf8_encode($rel_dir);
                // get any artist, title, album information found
                list ($artist, $title, $album) = id3Info(MUSIC_DIR . "/$rel_dir");
                $info = array ('p' => MUSIC_URL . $rel_dir, 'f' => $f);
                if (!empty($artist)) {
                    $info['a'] = $artist;
                }
                if (!empty($title)) {
                    $info['t'] = $title;
                }
                if (!empty($album)) {
                    $info['l'] = $album;
                }
                $files[] = $info;
            }
        }
    }
    if (sizeof($dirs) > 0) {
        $output['dirs'] = $dirs;
    }
    if (sizeof($files) > 0) {
        $output['files'] = $files;
    }
    return $output;
}
?>
