<?php
require 'config.php';
require 'bootstrap.php';
require 'resources/ResourceController.class.php';

$query = $_GET['r'];

$resources = new ResourceController;

if (!empty($query)) {
    $resources->display($query);
} else {
    index();
}

/**
 * Produces the index of the site.  Gathers all needed information and builds the
 * first view of the site without subsequent client calls to the server.
 *
 * @return void
 */
function index()
{
    global $resources;
    include SMARTY_DIR . 'Smarty.class.php';

    // initialize templating
    $smarty = new Smarty;
    $smarty->caching = CACHE_TEMPLATES;

    if (!$smarty->is_cached('index.tpl')) {
        // repeat menu list
        $smarty->assign('repeat_values', array('NONE', 'SONG', 'LIST'));
        $smarty->assign(
            'repeat_output', array('Repeat None', 'Repeat Song', 'Repeat List')
        );
        $smarty->assign('repeat', DEFAULT_REPEAT);

        // 'random' checkbox
        $smarty->assign('random', DEFAULT_RANDOM);

        // build alpha numeric navigation
        $alphaNav= array('#');
        for ($i = ord('a'), $size = ord('z'); $i <= $size; $i++) {
            $alphaNav[] = chr($i);
        }
        $smarty->assign('alphaNav', $alphaNav);

        // saved playlists
        $playlists = array(); //$resources->dispatch('playlists');

        $smarty->assign('saved_playlists', $playlists);
    }

    $smarty->display('index.tpl');
}
?>
