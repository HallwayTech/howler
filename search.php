<?php
require_once('config.php');
require_once('lib/id3.php');

// build the lists of files and dirs
$output['d'] = array();
$output['f'] = array();
if ($_GET['s']) {
	$search = strtolower($_GET['s']);

	// get a list of dirs and show them
	$dir_list = scandir(MUSIC_DIR);
	foreach ($dir_list as $f) {
		if (matches($search, $f)) {
			// create directory reference by concatenating path and the current
			// directory listing item
			$abs_dir = MUSIC_DIR."/$f";
			$rel_dir = $f;

			// build the output for a dir
			if (is_dir($abs_dir)) {
				$output['d'][] = array(
					'd' => $rel_dir,
					'l' => $f);
			}
		}
	}
}
echo json_encode($output);

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
    } elseif (substr($f, 0, 1) == $search) {
        $retval = true;
    } elseif ($search == '#' && is_numeric(substr($f, 0, 1))) {
        $retval = true;
    }
    return $retval;
}
?>
