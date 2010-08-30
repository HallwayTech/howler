<html>
		<head>
			<title><g:message code='application.title'/></title>
			<meta name='layout' content='main' />
			<script type="text/javascript" src="js/player.js"></script>
			<link rel='stylesheet' href='${resource(dir:'css', file:'jplayer.blue.monday.css')}' type='text/css'/>
		</head>
		<body>
			<div id='top'>
				<div id='player'></div>
				<div class="jp-playlist-player">
					<div class="jp-interface">
						<ul class="jp-controls">
							<li><a href="#" id="jplayer_play" class="jp-play" tabindex="1">play</a></li>
							<li><a href="#" id="jplayer_pause" class="jp-pause" tabindex="1">pause</a></li>
							<li><a href="#" id="jplayer_stop" class="jp-stop" tabindex="1">stop</a></li>
							<li><a href="#" id="jplayer_volume_min" class="jp-volume-min" tabindex="1">min volume</a></li>
							<li><a href="#" id="jplayer_volume_max" class="jp-volume-max" tabindex="1">max volume</a></li>
							<li><a href="#" id="jplayer_previous" class="jp-previous" tabindex="1">previous</a></li>
							<li><a href="#" id="jplayer_next" class="jp-next" tabindex="1">next</a></li>
						</ul>
						<div class="jp-progress">
							<div id="jplayer_load_bar" class="jp-load-bar">
								<div id="jplayer_play_bar" class="jp-play-bar"></div>
							</div>
						</div>
						<div id="jplayer_volume_bar" class="jp-volume-bar">
							<div id="jplayer_volume_bar_value" class="jp-volume-bar-value"></div>
						</div>
						<div id="jplayer_play_time" class="jp-play-time"></div>
						<div id="jplayer_total_time" class="jp-total-time"></div>
					</div>
					<div id="jplayer_playlist" class="jp-playlist">
						<ul>
							<li><g:message code='application.title'/></li>
						</ul>
					</div>
				</div>
				<div id='search'></div>
			</div>
			<div id='middle'>
				<div id='selectors'>
					<div id='artist-selector'>
						<div class='header'><g:message code='entries.artists'/></div>
						<div id='artists-list'><div class='wait'><img src='${resource(dir:'images', file:'wait30trans.gif')}'/></div></div>
					</div>
					<div id='album-selector'>
						<div class='header'><g:message code='entries.albums'/></div>
						<div id='albums-list'><div class='wait'><img src='${resource(dir:'images', file:'wait30trans.gif')}'/></div></div>
					</div>
				</div>
			</div>
			<div id='bottom'>
				<div id='collection'></div>
			</div>
			<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
			<jq:jquery>
			$("#player").jPlayer({
				nativeSupport: true,
				ready: Player.init
			});
			${remoteFunction(controller: 'entry', action:'listBy', params:[type:'artist'], update:'artists-list', method:'get')}
			${remoteFunction(controller: 'entry', action:'listBy', params:[type:'album'], update:'albums-list', method:'get')}
			</jq:jquery>
		</body>
</html>
