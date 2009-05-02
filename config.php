<?php
//
// Configuration settings for Howler.
//
// URLs can be absolute or relative, should be accessible to the users of the
// system via HTTP and end with a slash.
//
// DIRs should be absolute or relative, do not have to be accessible outside of
// the server and do not end with a slash.
//

// set the running mode of the system.
// Known settings: dev,prod
define('MODE', 'dev');

// File system location of the root of music.
define('MUSIC_DIR', '/var/media/music');

// URL of root to where mp3s can be found.
define('MUSIC_URL', 'music/');

// File system location to where playlists are stored.  Reference can be
// relative to installation directory.
define('PLAYLISTS_DIR', 'playlists/');

// Whether templates should be cached.
define('TEMPLATE_CACHING', false);

// The default setting for the repeat list.
define('DEFAULT_REPEAT', 'LIST');

// The default setting for 'random'
define('DEFAULT_RANDOM', 'false');
?>
