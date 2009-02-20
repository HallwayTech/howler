<?php
$playlist = $_POST['playlist'];
$file = '';
if (isset($playlist)) {
	$file = "playlists.php/$playlist.xspf";
}
$swf_file = "lib/xspf_player-0.2.3.swf?autoplay=true&autoload=true&repeat_playlist=true&playlist_url=$file";
$swf_height = 250;
$swf_width = '100%';
?>
<html>
<head>
<?php
if ($_GET['mode'] == 'dev') {
	require_once('inc/head_basic_dev.inc');
} else {
	require_once('inc/head_basic.inc');
}
?>
</head>

<body>
	<!-- playlists -->
	<div id='playlistsArea'></div>

<?php
if (isset($playlist)) {
?>
	<!-- player -->
	<object width="<?= $swf_width ?>" height="<?= $swf_height?>" id="xspf_player">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="<?= $swf_file ?>"/>
		<param name="quality" value="high" />
		<param name="wmode" value="transparent" />
		<embed src="<?= $swf_file ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?= $swf_width ?>" height="<?= $swf_height ?>"></embed>
	</object>
<?php
}
?>

	<!--
	<div id="playerWrapper">
		<div id="playerSpot">This text will be replaced by the media player.</div>
	</div>
	-->
	<!--
	<object type='application/x-shockwave-player'
		data='lib/xspf_player-0.2.3.swf?playlist_url=<?= $file ?>&repeat_playlist=true&autoplay=true'
		width='600' height='400' />
	-->
	<!--
		FlashVars='displayheight=0|autostart=true|largecontrols=true|playlist=bottom|playlistsize=200|location=<?= $file; ?>'
	-->


	<script type="text/javascript">
	/*
	// id of player after it is embedded
	var playerId = 'dood';
	// id of element to replace with embedded player
	var playerAreaId = 'playerSpot';

	var playerHeight = 20;
	var playlistsize = 200;
	var swfUrl = 'lib/player-3.16.swf';
	//var swfUrl = 'lib/player-4.2.90.swf';
	var width = '600';
	var height = playlistsize + playerHeight;
	var flashVersion = '7.0.0';
	var expressInstallSwfUrl = false;
	var flashVars = {
		'location': '<?= $file; ?>',
		'file': '<?= $file; ?>',
		'bufferlength': '5',
		'playlist': 'bottom',
		'playlistsize': playlistsize,
		'autostart': true,
		'displayheight': 0,
		'largecontrols': true,
		'repeat': 'always'
	};

	var params = {
		'allowscriptaccess': 'false',
		'allowfullscreen': 'false'
	};

	var attributes = {
		'id': playerId,
		'name': playerId
	};

	swfobject.embedSWF(swfUrl, playerAreaId, width, height, flashVersion, expressInstallSwfUrl, flashVars, params, attributes);
	*/
	</script>

<!-- templates -->
<textarea id="playlistsTemplate" class="template">
<form action="basic.php" method="post">
	<select id='playlist' name='playlist' size='1'>
{for pl in playlists}
	<option value='${pl}'>${pl}</option>
{/for}
	</select> <input type='submit' value='Play!'/>
<?php
if (isset($playlist)) {
	echo "<p>Now playing '$playlist'</p>";
}
?>
</form>
</textarea>
</body>
</html>
