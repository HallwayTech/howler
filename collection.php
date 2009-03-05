<?php
// import external id3 library
require_once('lib/id3.php');

// import configuration settings
require_once('config.php');

/**
 * Handler method for the base page
 */
$output = array();
$base = '';
if ($_GET['d']) {
	$base = $_GET['d'];
	$base = str_replace("\\'", "'", $base);
}

// build the lists of files and dirs
$output['d'] = array();
$output['f'] = array();
if ($base) {
	// get a list of dirs and show them
	$path = MUSIC_DIR."/$base";

	$dir_list = scandir($path);
	foreach ($dir_list as $f) {
		// continue if $f is current directory ['.'],
		// parent directory ['..'], or fails a matching test.
		if ($f == '.' || $f == '..') {
			continue;
		}

		// create directory reference by concatenating path and the current
		// directory listing item
		$abs_dir = "$path/$f";

		// create a short name but be sure it doesn't start with a slash
		$rel_dir = "$base/$f";

		// determine the file extension
		$path_info = pathinfo($rel_dir);
		$dirname = $path_info['dirname']; // /var/www
		$basename = $path_info['basename']; //  index.html
		$filename = $path_info['filename']; //  index
		$extension = strtolower($path_info['extension']); //  html

		// build the output for a dir
		if (is_dir($abs_dir)) {
			$output['d'][] = array(
				'd' => $rel_dir,
				'l' => $f);
		}
		// build the output for a file
		elseif ($extension == 'mp3') {
			// TODO encode in utf8
//                $f = utf8_encode($f);
//                $rel_dir = utf8_encode($rel_dir);

			// initialize ID3

			// get any artist, title, album information found
			list($artist, $title, $album) = id3Info(MUSIC_DIR."/$rel_dir");
			
			$output['f'][] = array(
				'p' => MUSIC_URL.$rel_dir,
				'f' => MUSIC_URL.$f,
				'a' => $artist,
				't' => $title,
				'l' => $album);
		}
	}
}
echo json_encode($output);
?>
