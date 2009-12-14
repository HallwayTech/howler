<?php
require_once 'lib/id3.php';

error_reporting(E_ALL);

ini_set('memory_limit', '64M');

define('MUSIC_DIR', '/home/chall/Music');

function walkdir($dir)
{
    $dir_key = sha1($dir);

    $nodes = @scandir(MUSIC_DIR."/$dir");
    if ($nodes !== false) {
        foreach ($nodes as $node) {
            if (substr($node, 0, 1) == '.') {
                continue;
            }

            $entry = "$dir/$node";
            while (substr($entry, 0, 1) == '/') {
                $entry = substr($entry, 1);
            }
            $full_entry = MUSIC_DIR."/$entry";
            $entry_key = sha1($entry);

            if (is_dir($full_entry)) {
                $dir_entry = array(
                    '_id' => $entry_key, 'type' => 'folder', 'label' => $entry,
                    'added' => date('Y-m-d')
                );
                if (!empty($dir)) {
                    $dir_entry['parent'] = $dir_key; 
                }
                store($dir_entry);
                walkdir($entry);
            } elseif (is_file($full_entry)) {
                $file_entry = array(
                    '_id' => $entry_key, 'type' => 'file', 'file' => $entry,
                    'added' => date('Y-m-d')
                );
                if (!empty($dir)) {
                    $file_entry['parent'] = $dir_key; 
                }

                // get any artist, title, album information found
                list($artist, $title, $album) = id3Info($full_entry);
                $file_entry['artist'] = $artist;
                $file_entry['title'] = $title;
                $file_entry['album'] = $album;
//                $id3 = @id3_get_tag($full_entry);
//                $file_entry = array_merge($file_entry, $id3);
                store($file_entry);
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
      print_r(array_merge($response, $doc));
    }
}

walkdir('');
