<?php
interface RestResource
{
	protected $response_code = 204;
	protected $headers = array();
    protected $output = null;

    function _new();
    function _list();
    function create();
    function read();
    function update();
    function delete();
}
?>