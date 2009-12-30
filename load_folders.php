<?php
require_once 'lib/id3.php';

error_reporting(E_ALL);

ini_set('memory_limit', '64M');

define('TEST', true);
define('LOAD_LIMIT', 25);
define('MUSIC_DIR', '/home/chall/Music');

function walkdir($dir, $top_level_count)
{
    $parent_id = sha1($dir);

    $nodes = @scandir(MUSIC_DIR."/$dir");
    if ($nodes !== false) {
        foreach ($nodes as $node) {
            if (substr($node, 0, 1) == '.') {
                continue;
            }

            $path = "$dir/$node";
            while (substr($path, 0, 1) == '/') {
                $path = substr($path, 1);
            }
            $full_path = MUSIC_DIR."/$path";
            $entry_id = sha1($path);

            // collect common fields
            $entry = array(
                '_id' => $entry_id, 'added' => date(filemtime($full_path))
            );
            if (!empty($dir)) {
                $entry['parent'] = $parent_id; 
            }

            if (is_dir($full_path)) {
                // add directory specific fields
                $entry['type'] = 'd';
                $entry['label'] = $node;

                if (!empty($dir)) {
                    $top_level_count++;
                }
                store($entry);
                walkdir($path, $top_level_count);
            } elseif (is_file($full_path)) {
                $entry['type'] = 'f';
                $entry['file'] = $path;

                // get any artist, title, album information found
                list($artist, $title, $album) = id3_info($full_path);
                $entry['artist'] = $artist;
                $entry['title'] = $title;
                $entry['album'] = $album;
                store($entry);
            }
            if (TEST === true && $top_level_count == LOAD_LIMIT) {
              exit;
            }
        }
    }
}

function store($doc)
{
    // convert doc to json for storage
    $doc_json = json_encode($doc);

    // store doc in tempfile for put call
    $tmp = tmpfile();
    fwrite($tmp, $doc_json);
    fseek($tmp, 0);

    // create curl resource
    $ch = curl_init('http://localhost:5984/howler-test/'.$doc['_id']);

    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // set the method to PUT
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_INFILE, $tmp);
    curl_setopt($ch, CURLOPT_INFILESIZE, strlen($doc_json));

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    // close tmp file handle
    fclose($tmp);

    $response = json_decode($output, true);
    if (!isset($response['ok'])) {
        if (isset($doc['file'])) {
            $response['file'] = $doc['file'];
        } elseif (isset($doc['label'])) {
            $response['label'] = $doc['label'];
        }
        print_r($response);
    }
}

$top_level_count = 0;
walkdir('', $top_level_count);
