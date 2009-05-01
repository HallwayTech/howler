<?php
require_once 'config.php';
require '/usr/share/php/smarty/Smarty.class.php';

// initialize templating
$smarty = new Smarty;
$smarty->caching = TEMPLATE_CACHING;

if (!$smarty->is_cached('index.tpl')) {
	// repeat menu list
	$smarty->assign('repeat_values', array('NONE', 'SONG', 'LIST'));
	$smarty->assign('repeat_output', array('Repeat None', 'Repeat Song', 'Repeat List'));
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
	$smarty->assign('playlists', array('ONE', 'TWO'));
}

$smarty->display('index.tpl');
?>
