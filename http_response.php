<?php
define('HTTP_200', 'OK');
define('HTTP_201', 'Created');
define('HTTP_204', 'No Content');
define('HTTP_303', 'See Other');
define('HTTP_400', 'Bad Request');
define('HTTP_401', 'Unauthorized');
define('HTTP_404', 'Not Found');
define('HTTP_405', 'Method Not Allowed');
define('HTTP_422', 'Unprocessable Entity');

/**
 * Sets the response header based on the given code.
 *
 * @param code The status code to set in the header.
 */
function send_response_code($code) {
    $status = constant("HTTP_$code");
    header("HTTP/1.1 $code $status", true, $code);
}
?>
