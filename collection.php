<?php
require_once('/usr/share/php-getid3/getid3.php');

//"""Beginning point for processing of this script."""
$output = home();
return $output;

/**
 * Handler method for the base page
 */
function home()
{
	$root = '/var/media/music/';
	$output = array();
	$base = '';
	$search = '';
	if ($_GET['d']) {
		$base = $_GET['d'];
		$base = str_replace("\\'", "'", $base);
	} elseif ($_GET['s']) {
		$search = strtolower($_GET['s']);
	}

	// get a list of dirs and show them
	$path = $root . $base;
	$output['cp'] = ($search ? $search : $base);

	// build the lists of files and dirs
	$output['d'] = array();
	$output['f'] = array();
	if ($base || (!$base && $search)) {
		// initialize getID3
		$dir_list = scandir($path);
		$getID3 = new getID3;
		foreach ($dir_list as $f) {
			if ($f == '.' || $f == '..' || !matches($search, $f)) {
				continue;
			}

			$dir = "$path/$f";
			$shortname = ($base) ? "$base/$f" : $f;
			$file_end = strtolower(substr($f, strlen($f) - 4));

			// build the output for a dir
			if (is_dir($dir)) {
				$output['d'][] = array('d' => escapeOutput($shortname), 'l' => escapeOutput($f));
			}
			// build the output for a file
			elseif ($file_end == '.mp3') {
				// encode in utf8
//				$f = utf8_encode($f);
//				$shortname = utf8_encode($shortname);

				$artist = '';
				$title = '';
				$album = '';

				$id3info = $getID3->analyze("/var/media/music/$shortname");
				getid3_lib::CopyTagsToComments($id3info);

				if (!empty($id3info['comments_html']['artist'])) {
					$artist = utf8_encode($id3info['comments_html']['artist'][0]);
				} else {
					$artist = utf8_encode($shortname);
				}
				if (!empty($id3info['comments_html']['title'])) {
					$title = utf8_encode($id3info['comments_html']['title'][0]);
				} else {
					$title = utf8_encode($f);
				}
				if (!empty($id3info['comments_html']['album'])) {
					$album = utf8_encode($id3info['comments_html']['album'][0]);
					//$artist .= ' - ' . $album;
				}

				$output['f'][] = array('p' => escapeOutput("music/$shortname"), 'f' => escapeOutput($f), 'a' => escapeOutput($artist), 't' => escapeOutput($title), 'l' => escapeOutput($album));
			}
		}
	}
	echo json_encode($output);
}

/**
 * Convert apostrophe and quote with html safe characters
 */
function escapeOutput($output)
{
	$fixup = $output;
	if ($output) {
//		$fixup = utf8_decode($output);
//		$fixup = utf8_encode($fixup);
//		$fixup = str_replace("'", "&#39;", $fixup);
//		$fixup = str_replace('"', '&#34;', $fixup);
//		$fixup = str_replace('?', '&#63;', $fixup);
	}
	return $fixup;
}

/**
 * Matches the search term to the beginning of the provided filename.  This is done instead of regex for performance (this is faster).
 */ 
function matches($search, $f)
{
	$f = str_replace('_', '', $f);
	$f = str_replace('(', '', $f);
	$f = trim($f);
	$f = strtolower($f);
	$retval = false;
	if (!$search) {
		$retval = true;
	} elseif ($search == '#' && is_numeric(substr($f, 0, 1))) {
		$retval = true;
	} elseif (substr($f, 0, 1) == $search) {
		$retval = true;
	}
	return $retval;
}
?>
