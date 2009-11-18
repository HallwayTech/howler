<?php
#!/usr/bin/php

$cats = array();

// loop over the filesystem
$nodes = scandir('/home/chall/Music');
foreach ($nodes as $node) {
	if (substr($node, 0, 1) == '.') {
		continue;
	}

	preg_match('/[a-zA-Z0-9]/', $node, $matches);
	$alphanum = strtoupper($matches[0]);

	$tcats = ($cats[$alphanum]) ? $cats[$alphanum] : array();
	$tcats[] = sha1($node);
	$cats[$alphanum] = $tcats;
}
echo json_encode($cats);
