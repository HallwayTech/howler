<?php
$codes = array(
    200 => 'OK',
    201 => 'Created',
    204 => 'No Content',
    303 => 'See Other',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    422 => 'Unprocessable Entity');

/**
 * Sets the response header based on the given code.
 *
 * @param code The status code to set in the header.
 */
function send_response_code($code) {
    $status = $code[$code];
    header("HTTP/1.1 $code $status", true, $code);
}
?>
