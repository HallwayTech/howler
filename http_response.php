<?php
define('HTTP_100', 'Continue');
define('HTTP_101', 'Switching Protocols');
define('HTTP_200', 'OK');
define('HTTP_201', 'Created');
define('HTTP_202', 'Accepted');
define('HTTP_203', 'Non-Authoritative Information');
define('HTTP_204', 'No Content');
define('HTTP_205', 'Reset Content');
define('HTTP_206', 'Partial Content');
define('HTTP_300', 'Multiple Choices');
define('HTTP_301', 'Moved Permanently');
define('HTTP_302', 'Found');
define('HTTP_303', 'See Other');
define('HTTP_304', 'Not Modified');
define('HTTP_305', 'Use Proxy');
define('HTTP_307', 'Temporary Redirect');
define('HTTP_400', 'Bad Request');
define('HTTP_401', 'Unauthorized');
define('HTTP_402', 'Payment Required');
define('HTTP_403', 'Forbidden');
define('HTTP_404', 'Not Found');
define('HTTP_405', 'Method Not Allowed');
define('HTTP_406', 'Not Acceptible');
define('HTTP_407', 'Proxy Authentication Required');
define('HTTP_408', 'Request Timeout');
define('HTTP_409', 'Conflict');
define('HTTP_410', 'Gone');
define('HTTP_411', 'Length Required');
define('HTTP_412', 'Precondition Failed');
define('HTTP_413', 'Request Entity Too Large');
define('HTTP_414', 'Request-URI Too Long');
define('HTTP_415', 'Unsupported Media Type');
define('HTTP_416', 'Requested Range Not Satisfiable');
define('HTTP_417', 'Expectation Failed');
define('HTTP_422', 'Unprocessable Entity');
define('HTTP_500', 'Internal Server Error');
define('HTTP_501', 'Not Implemented');
define('HTTP_502', 'Bad Gateway');
define('HTTP_503', 'Service Unavailable');
define('HTTP_504', 'Gateway Timeout');
define('HTTP_505', 'HTTP Version Not Supported');

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
