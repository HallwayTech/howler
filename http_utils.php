<?php
function http_script_uri() {
    //$value = preg_replace('/%([0-9a-f]{2})/ie', 'chr(hexdec($1))', (string) $value);
    // get whatever is after the name of the script
    // add 1 to skip the first slash
    $script_name = $_SERVER['SCRIPT_NAME'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $len_script_name = strlen($script_name);
    $len_request_uri = strlen($request_uri);

    $uri = '';

    if ($len_request_uri != $len_script_name) {
        $uri = substr($request_uri, $len_script_name);
        $uri = str_replace("\\'", "'", $uri);
        $uri = urldecode($uri);
        if (substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }
    }

    return $uri;
}
?>
