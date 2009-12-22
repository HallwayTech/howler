<?php
if (!defined('HEAD')) {
    define('HEAD', 'HEAD');
}
if (!defined('GET')) {
    define('GET', 'GET');
}
if (!defined('POST')) {
    define('POST', 'POST');
}
if (!defined('PUT')) {
    define('PUT', 'PUT');
}
if (!defined('DELETE')) {
    define('DELETE', 'DELETE');
}
if (!defined('TRACE')) {
    define('TRACE', 'TRACE');
}
if (!defined('OPTIONS')) {
    define('OPTIONS', 'OPTIONS');
}
if (!defined('CONNECT')) {
    define('CONNECT', 'CONNECT');
}

/**
 * Verifies that the current request method is included in a set of allowed methods.
 *
 * @parm $methods
 */
if ( ! function_exists('require_method'))
{
    function is_method_allowed($allowed_method)
    {
        $allowed = false;

        $method = $this->input->server('REQUEST_METHOD');

        if (is_array($allowed_method)) {
            $allowed = in_array($method, $allowed_method);
        } else {
            $allowed = $method == $allowed_method;
        }

        if (!$allowed) {
            show_error('Request method not allowed. Expected '
                . join(',', $allowed_method));
        }

        return $allowed;
    }
}



/* End of file request_helper.php */
/* Location: ./system/application/helpers/request_method_helper.php */