<?php
require_once 'config.php';

/**
 * Stream stylesheets out as one file to cut down the number of connections on
 * initial page load of the site.
 *
 * Note: not using include or require because they both evaluate the code.
 */
$stylesheet_names = array('css/playlist', 'css/collection', 'css/player', 'css/playlist');

foreach ($stylesheet_names as $stylesheet) {
	if (MODE == 'dev') {
		$sheet = "$stylesheet.css";
	} else {
		$sheet = "$stylesheet.min.css";
	}

    if (is_file($sheet)) {
        readfile($sheet);
    } else {
        echo "/* unable to read $sheet */";
    }
}
?>
