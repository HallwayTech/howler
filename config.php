<?php
//
// Configuration settings for Howler.
//
// URLs can be absolute or relative, should be accessible to the users of
// the system via HTTP and end with a slash.
//
// DIRs should be absolute, do not have to be accessible outside of the server
// and end with a slash.
//

// File system location of the root of music.  Does not end with a slash.
define('MUSIC_DIR', '/var/media/music');

// URL of root to where mp3s can be found.
define('MUSIC_URL', 'music/');

// URL of root to where playlists are stored.
define('PLAYLISTS_URL', 'playlists/');
?>
