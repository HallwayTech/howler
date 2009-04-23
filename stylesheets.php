<?php
/**
 * Stream stylesheets out as one file to cut down the number of connections on
 * initial page load of the site.
 *
 * Note: not using include or require because they both evaluate the code.
 */
$stylesheets = array('css/playlist.min.css', 'css/collection.min.css', 'css/player.min.css');

foreach ($stylesheets as $stylesheet) {
    if (is_file($stylesheet)) {
        readfile($stylesheet);
    } else {
        echo '// unable to read $script';
    }
}
?>
