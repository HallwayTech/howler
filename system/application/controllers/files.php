<?php

class Files extends Contoller
{
	function Files()
	{
		parent::Controller();
	}

	function read($id)
	{
		// since the player seems to turn %26 into &, the query string has to be parsed
		// manually so that & doesn't split the requested item
		//$file = stripslashes($_GET['d']);
		$qs = urldecode($id);
		$file = stripslashes($qs);

		if (!is_readable($file)) {
		    header("Status: 404 Not Found");
		    print "Unable to find the requested file [$file]";
		} else {
		    $filename = basename($file);
		    $filesize = filesize($file);

		    header("Content-Disposition: attachment; filename=".urlencode($filename));
		    header("Content-Type: audio/mpeg");
		    header("Content-Length: ".$filesize);
		    header("Pragma: no-cache");
		    header("Expires: 0");

		    $fp=fopen("$file","rb");
		    print fread($fp, $filesize);
		    fclose($fp);
		}
	}
}