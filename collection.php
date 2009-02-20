<?php
// import external id3 library
require_once('lib/id3.php');

// import configuration settings
require_once('config.php');

$output = home();
echo $output;

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
	$path = MUSIC_DIR."/$base";

	// build the lists of files and dirs
	$output['d'] = array();
	$output['f'] = array();
	if ($base || (!$base && $search)) {
		$dir_list = scandir($path);
		foreach ($dir_list as $f) {
			// continue if $f is current directory ['.'],
			// parent directory ['..'], or fails a matching test.
			if ($f == '.' || $f == '..' || !matches($search, $f)) {
				continue;
			}

			// create directory reference by concatenating path and the current
			// directory listing item
			$abs_dir = "$path/$f";

			// create a short name but be sure it doesn't start with a slash
			$rel_dir = ($base) ? "$base/$f" : $f;

			// determine the file extension
			$path_info = pathinfo($rel_dir);
			$dirname = $path_info['dirname']; // /var/www
			$basename = $path_info['basename']; //  index.html
			$filename = $path_info['filename']; //  index
			$extension = strtolower($path_info['extension']); //  html

			// build the output for a dir
			if (is_dir($abs_dir)) {
				$output['d'][] = array('d' => escapeOutput($rel_dir), 'l' => escapeOutput($f));
			}
			// build the output for a file
			elseif ($extension == 'mp3') {
				// TODO encode in utf8
//				$f = utf8_encode($f);
//				$rel_dir = utf8_encode($rel_dir);

				// initialize ID3
				$id3 = new id3(MUSIC_DIR."/$rel_dir");

				// get any artist, title, album information found
				$artist = $id3->artist();
				if (empty($artist)) {
					$artist = $dirname;
				}
				$title = $id3->title();
				if (empty($title)) {
					$title = $filename;
				}
				$album = $id3->album();
				if (empty($album)) {
					$album = '';
				}

				$output['f'][] = array('p' => escapeOutput(MUSIC_URL ."$rel_dir"), 'f' => MUSIC_URL.escapeOutput($f), 'a' => escapeOutput($artist), 't' => escapeOutput($title), 'l' => escapeOutput($album));
			}
		}
	}
	return json_encode($output);
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
