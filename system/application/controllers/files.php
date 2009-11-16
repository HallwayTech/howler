<?php

class Files extends Controller
{
	function Files()
	{
		parent::Controller();
	}

	function read($id, $id0 = null, $id1 = null, $id2 = null, $id3 = null)
	{
		// since the player seems to turn %26 into &, the query string has to be parsed
		// manually so that & doesn't split the requested item
		//$file = stripslashes($_GET['d']);
		if ($id0 != null) {
			$id .= "/$id0";
		}
		if ($id1 != null) {
			$id .= "/$id1";
		}
		if ($id2 != null) {
			$id .= "/$id2";
		}
		if ($id3 != null) {
			$id .= "/$id3";
		}

		$qs = urldecode($this->config->item('music_dir') . "/$id");
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