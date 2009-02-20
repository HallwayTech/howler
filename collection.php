<?php
// import external id3 library
require_once('getid3.php');

// import configuration settings
require_once('config.php');

$output = home();
return $output;

/**
 * Handler method for the base page
 */
function home()
{
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
	$path = MUSIC_DIR . $base;
	$output['cp'] = ($search ? $search : $base);

	// build the lists of files and dirs
	$output['d'] = array();
	$output['f'] = array();
	if ($base || (!$base && $search)) {
		// initialize getID3
		$dir_list = scandir($path);
		$getID3 = new getID3;
		foreach ($dir_list as $f) {
			// continue if $f is current directory ['.'],
			// parent directory ['..'], or fails a matching test.
			if ($f == '.' || $f == '..' || !matches($search, $f)) {
				continue;
			}

			// create directory reference by concatenating path and the current
			// directory listing item
			$dir = "$path/$f";

			// create a short name but be sure it doesn't start with a slash
			$shortname = ($base) ? "$base/$f" : $f;

			// determine the file extension
			$path_info = pathinfo($f);
			$file_end = strtolower($path_info['extension']);

			// build the output for a dir
			if (is_dir($dir)) {
				$output['d'][] = array('d' => escapeOutput($shortname), 'l' => escapeOutput($f));
			}
			// build the output for a file
			elseif ($file_end == '.mp3') {
				// TODO encode in utf8
//				$f = utf8_encode($f);
//				$shortname = utf8_encode($shortname);

				// set defaults for artist, title, album
				$artist = '';
				$title = '';
				$album = '';

				// get the ID3 information
				$id3info = $getID3->analyze(MUSIC_DIR . "/$shortname");
				getid3_lib::CopyTagsToComments($id3info);

				// get any artist, title, album information found
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

				$output['f'][] = array('p' => escapeOutput(MUSIC_URL ."$shortname"), 'f' => escapeOutput($f), 'a' => escapeOutput($artist), 't' => escapeOutput($title), 'l' => escapeOutput($album));
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
