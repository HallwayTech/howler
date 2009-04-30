<html>
	<head>
		<meta http-equiv=”Content-Type” content=”text/html; charset=utf-8″ />
		<!-- javascript -->
		<script type="text/javascript" src="scripts.php"></script>
		<!-- stylesheets -->
		<link rel="stylesheet" type="text/css" href="stylesheets.php" />

		<title>&#164;&#164; Home Media Player &#164;&#164;</title>
	</head>
	<body>
		<div id="player">
			<!-- menus -->
			<div id="menubar">
				<!-- actions menu -->
				<div class="player-menu"><input type="button" id="restart-player" onclick="Actions.restartPlayer()" value="Restart player" />
				</div>
				<!-- random menu -->
				<div class="player-menu"><input type="checkbox" id="random" onclick="Player.random(this.checked)"/><label for="random"> Random?</label></div>
				<!-- repeat menu -->
				<div class="player-menu"><select id="repeat-menu">
						<option onclick="Player.repeat('NONE')">Repeat None</option>
						<option onclick="Player.repeat('SONG')">Repeat Song</option>
						<option onclick="Player.repeat('LIST')">Repeat List</option>
					</select>
				</div>
			</div>
			<!-- marquee -->
			<div id='marquee'>
				<span class="artist"></span> - <span class="title"></span>
				<span class="album"></span>
				<div class='clear'></div>
			</div>
			<!-- player -->
			<div id="playerWrapper">
				<div id="playerSpot">This text will be replaced by the media player.</div>
			</div>
			<!-- saved playlists -->
			<div id='saved-playlists-container'>
				<div id='saved-playlists-actions'>
					<div class='left'>
						<input type='button' value='New' onclick='Playlist.clear()'/>
						<input type='button' value='Save as..' onclick='Playlist.save()'/>
					</div>
					<div class='right'>
						<a href='#' onclick='$("#saved-playlists").toggle("normal");return false'>^</a>
					</div>
				</div>
				<div class='clear'></div>
				{include file='$template_dir/playlists.tpl'}
			</div>
			<!-- playlist -->
			<div id='playlist'></div>
		</div>

		<!-- collection -->
		<div id='collection'>
			<div id='alphaNav'>
				<ul>
				{section loop=$alphaNav name=i}
					<li onclick="Collection.search('{$alphaNav[i]}');return false">{$alphaNav[i]}</li>
				{/section}
				</ul>
			</div>
			<div id='output'></div>
		</div>

<!-- TEMPLATES -->
<!-- playlist template -->
<textarea id="playlistTemplate" class="template">
<ul class='items'>
{for i in items}
<li id='playlist-item-${i_index}' class='playlist-item'>
	<div onclick="Player.controls.play('${i_index}')" class='content'>
		<span class="artist">${i.artist}</span> - <span class="title">${i.title}</span>
		<span class="album">${i.album}</span>
	</div>
	<div class='remove'>
		<a href='#' onclick="Playlist.removeItem('${i_index}')">X</a>
	</div>
	<div class='clear'></div>
</li>
{forelse}
<div>Click through the collection and add some songs!</div>
{/for}
</ul>
</textarea>

<!-- collection template -->
<textarea id="collectionTemplate" class="template">
{if defined('cp') && cp != ''}
<div>
	<span id="listHeader">
	Listing for ${cp}
	</span>
	<div id="collectionNav">
		<ul>
			<li id="refreshLink"><a href='#' onclick='Collection.refresh();return false'>Refresh</a></li>
			<li id="backLink"><a href="#" onclick='Collection.back();return false'>Go back</a></li>
			<li id="addAllLink"><a href='#' onclick='Collection.addAll();return false'>Add All Songs</a></li>
		</ul>
	</div>

<ul class='dirs'>
{for _d in d}
<li class='dir'><a href="#" onclick="Collection.view('${_d_index}');return false">${_d.l}</a></li>
{/for}
</ul>

<ul class='files'>
{for _f in f}
<li class='file'><a href="#" onclick="Collection.addSong(${_f_index});return false" class="fileAdd">[add]</a>&nbsp;{if _f.a && _f.t}${_f.a} - ${_f.t}{else}${_f.f}{/if}</li>
{/for}
</ul>
{else}
Select from list.
{/if}
</textarea>

	</body>
</html>
