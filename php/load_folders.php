<?php
require_once 'id3.php';

error_reporting(E_ALL);

ini_set('memory_limit', '64M');

define('MUSIC_DIR', '/home/chall/music');

/**
 * Walk a directory and insert all entries found (folders, files) into a mysql
 * database.  Filters file that don't end in .mp3.
 */
function walk_dir($dir, $insert_current_dir = false)
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
                '_id' => $entry_id, 'added' => date('Y-m-d', filemtime($full_path)),
                'url' => $path
            );
            if (!empty($dir)) {
                $entry['parent'] = $parent_id; 
            }

            if (is_dir($full_path)) {
                // add directory specific fields
                $entry['type'] = 'd';
                $entry['label'] = $node;

                store_mysql($entry);
                walk_dir($path);
            } elseif (is_file($full_path) && substr($node, -4) == '.mp3') {
                $entry['type'] = 'f';

                // get any artist, title, album information found
                list($artist, $title, $album) = id3_info($full_path);
                $entry['artist'] = $artist;
                $entry['title'] = $title;
                $entry['album'] = $album;
                if ($title) {
                    $entry['label'] = $title;
                } else {
                    $entry['label'] = $node;
                }
                store_mysql($entry);
            }
        }
    }
}

function store_mysql($doc)
{
    @mysql_connect('localhost', 'root', 'mtrpls12') or exit(mysql_error());
    @mysql_select_db('howler') or exit(mysql_error());

    preg_match('/[a-zA-Z0-9]/', $doc['label'], $matches);
    if (!$matches) {
        if ($doc['type'] == 'd') {
            echo "Can't determine prefix of '{$doc['label']}'\n";
            return;
        } else {
            $prefix = '';
        }
    } else {
        $prefix = strtolower($matches[0]);
    }
    $fields = "entry_id, label, url, type, date_added, prefix";
    $values = sprintf("'%s', '%s', '%s', '%s', '%s', '%s'",
        mysql_real_escape_string($doc['_id']),
        mysql_real_escape_string($doc['label']),
        mysql_real_escape_string($doc['url']),
        mysql_real_escape_string($doc['type']),
        mysql_real_escape_string($doc['added']), $prefix);
    if (!empty($doc['parent'])) {
        $fields .= ", parent_entry_id";
        $values .= ", '".mysql_real_escape_string($doc['parent'])."'";
    }
    $sql = "insert into entries ($fields) values ($values)";

    if (!TEST) {
        $result = @mysql_query($sql);
        if (!$result) {
            echo "Unable to insert {$doc['label']}";
        }
    } else {
        echo "$sql\n";
    }

    if ($doc['type'] == 'f') {
        $fields = 'entry_id, artist, album, title';
        $artist = 'null';
        $album = 'null';
        $title = 'null';
        if (!empty($doc['artist'])) {
            $artist = "'".mysql_real_escape_string($doc['artist'])."'";
        }
        if (!empty($doc['album'])) {
            $album = "'".mysql_real_escape_string($doc['album'])."'";
        }
        if (!empty($doc['title'])) {
            $title = "'".mysql_real_escape_string($doc['title'])."'";
        }
        $values = sprintf("'%s', %s, %s, %s", $doc['_id'], $artist, $album, $title);
        $sql = "insert into id3 ($fields) values ($values)";
        if (!TEST) {
            $result = @mysql_query("insert into id3 ($fields) values ($values)");
            if (!$result) {
                echo "Unable to insert info for {$doc['label']}";
            }
        } else {
            echo "$sql\n";
        }
    }
    mysql_close();
}

function store_couch($doc)
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

walk_dir('');
