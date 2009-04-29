<?php
require_once 'config.php';

/**
 * Stream scripts out as one file to cut down the number of connections on
 * initial page load of the site.
 *
 * Note: not using include or require because they both evaluate the code.
 */
$libraries = array('lib/jquery-1.3.2.min.js', 'lib/jquery-ui-1.7.1.custom.min.js', 'lib/swfobject-2.1.min.js', 'lib/trimpath-template-1.0.38.min.js', 'lib/querystring-1.3.min.js', 'lib/json2.min.js');
$script_names = array('js/template', 'js/playlist', 'js/player', 'js/collection', 'js/actions');

// echo libraries untransformed
foreach ($libraries as $script) {
	echo_script($script, false);
}

// echo local script conditionally transformed
foreach ($script_names as $script) {
	echo_script($script);
}

function echo_script($script, $transform=true) {
	$name = $script;
	if ($transform) {
		if (MODE == 'dev') {
			$name = "$script.js";
		} else {
			$name = "$script.min.js";
		}
	}

    if (is_file($name)) {
        readfile($name);
    } else {
        echo "/* unable to read $name */";
    }
}
?>
