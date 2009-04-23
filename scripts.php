<?php
/**
 * Stream scripts out as one file to cut down the number of connections on
 * initial page load of the site.
 *
 * Note: not using include or require because they both evaluate the code.
 */
$stylesheets = array('css/playlist.min.css', 'css/collection.min.css', 'css/player.min.css');
$scripts = array('lib/jquery-1.3.2.min.js', 'lib/jquery-ui-1.7.custom.min.js', 'lib/swfobject-2.1.min.js', 'lib/trimpath-template-1.0.38.min.js', 'lib/querystring-1.3.min.js', 'lib/json2.min.js', 'js/template.js', 'js/playlist.js', 'js/player.js', 'js/collection.js', 'js/actions.js');

foreach ($scripts as $script) {
    if (is_file($script)) {
        readfile($script);
    } else {
        echo '// unable to read $script';
    }
}
?>
