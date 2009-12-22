<?php
function load($url, $filename)
{
    if (!file_exists($filename)) {
       die("Could not find docs file [$filename]\n");
    }

    $file = file_get_contents($filename);
    $docs = json_decode($file, true);

    foreach ($docs as $doc) {
        echo "Processing {$doc['_id']}\n";

        // see if a version already exists in the db
        $ch = curl_init("$url/{$doc['_id']}");

        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $output contains the output string
        $output = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // if the document exists, use the _rev returned
        if ($response_code == 200 && !empty($output)) {
            echo "--Updating existing doc\n";
            $response = json_decode($output);
            $doc['_rev'] = $response->_rev;
        }

        // convert doc to json for storage
        $doc_json = json_encode($doc);

        // store doc in tempfile for put call
        $tmp = tmpfile();
        fwrite($tmp, $doc_json);
        fseek($tmp, 0);

        // create curl resource
        $ch = curl_init("$url/{$doc['_id']}");
        
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
            $response['id'] = $doc['_id'];
            print_r($response);
        }
    }
}

function dump($url, $filename)
{
    // create curl resource
    $ch = curl_init($url);

    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    $designs = json_decode($output, true);
    $docs = array();
    foreach ($designs['rows'] as $row) {
        unset($row['doc']['_rev']);
        $docs[] = $row['doc'];
    }
    $file = fopen($filename, 'w');
    fwrite($file, json_encode($docs));
}

function view_name($type, $func)
{
    switch ($type) {
        case "design":
            return '_all_docs?startkey="_design/"&endkey="_design0"&include_docs=true';
        case "playlist":
            return '_design/playlists/_view/by_user?include_docs=true';
    }
}

function usage($argv)
{
    return <<<USAGE

Manages special documents in a database accessed over REST.

Usage: {$argv[0]} [<url>] <db> (design|playlist) (load|dump)


USAGE;
}

if ($argc < 4) {
    die(usage($argv));
}

if ($argc == 4) {
    // assume url is not provided
    $db = $argv[1];
    $url = "http://localhost:5984/$db";
    $type = $argv[2];
    $func = $argv[3];
} elseif ($argc == 5) {
    // use the url given
    $db = $argv[2];
    $url = "{$argv[1]}/$db";
    $type = $argv[3];
    $func = $argv[4];
}

if (($type != 'design' && $type != 'playlist')
    || ($func != 'dump' && $func != 'load')
) {
    die(usage($argv));
}

$filename = "{$db}_{$type}_docs.json";

switch ($func) {
    case 'load':
        load($url, $filename);
        break;
    case 'dump':
        $url .= '/'.view_name($type, $func);
        dump($url, $filename);
        break;
}
