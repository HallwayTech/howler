<?php
#!/usr/bin/php
$root = '/home/chall/Music';

function walkdir($dir) {
	$root = '/home/chall/Music';
	$dirs = array();
	$files = array();

	$nodes = scandir("$root/$dir");
	foreach ($nodes as $node) {
		if (substr($node, 0, 1) == '.') {
			continue;
		}

		$key = sha1("$dir/$node");
		if (is_dir("$root/$dir/$node")) {
			$dirs[$key] = walkdir("$dir/$node");
		} elseif (is_file($node)) {
			$files[$key] = $node;
		}
	}

	$retval['dirs'] = $dirs;
	$retval['files'] = $files;
	return $retval;
}

$cats = array();
$dirs = array();
$files = array();

// loop over the filesystem
$nodes = scandir($root);
foreach ($nodes as $node) {
	if (substr($node, 0, 1) == '.') {
		continue;
	}

	preg_match('/[a-zA-Z0-9]/', $node, $matches);
	$alphanum = strtoupper($matches[0]);

	$tcats = ($cats[$alphanum]) ? $cats[$alphanum] : array();
	$tcats[] = sha1($node);
	$cats[$alphanum] = $tcats;

	$key = sha1($node);
	if (is_dir("$root/$node")) {
		$dirs[$key] = walkdir($node);
	} elseif (is_file("$root/$node")) {
		$files[$key] = $node;
	}
}
echo json_encode($dirs);
