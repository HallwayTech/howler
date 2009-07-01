<?php
/**
 * Perform bootstrap checks to make sure everything needed is in place.
 */
// check for playlists directory
if (!is_dir(PLAYLISTS_DIR)) {
    echo "Playlists directory is missing [".PLAYLISTS_DIR."]";
}
?>
