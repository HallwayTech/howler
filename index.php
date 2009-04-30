<?php
require_once 'config.php';
require '/usr/share/php/smarty/Smarty.class.php';

// initialize templating
$smarty = new Smarty;
$smarty->caching = TEMPLATE_CACHING;

$template = TEMPLATE_DIR.'index.tpl';

if (!$smarty->is_cached('index.tpl')) {
	//
	// gather data to present intial page
	//

	// repeat menu list
	$smarty->assign('repeats', array('NONE' => 'Repeat None',
		'SONG' => 'Repeat Song','LIST' => 'Repeat List'));

	// build alpha numeric navigation
	$alphaNav= array('#');
	for ($i = ord('a'), $size = ord('z'); $i <= $size; $i++) {
		$alphaNav[] = chr($i);
	}
	$smarty->assign('alphaNav', $alphaNav);

	// saved playlists
	$smarty->assign('playlists', array());
}

$smarty->display(TEMPLATE_DIR.'/index.tpl');
?>
