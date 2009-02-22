<?php
require_once('config.php');
?>
<html>
	<head>
<?php
if (MODE == 'dev') {
	require_once('inc/head_dev.inc');
} else {
	require_once('inc/head.inc');
}
?>
	</head>
	<body>
		<div id="player">
			<!-- menus -->
			<div id="menubar">
				<!-- actions menu -->
				<div class="player_menu"><input type="button" id="restart_player" onclick="Actions.restartPlayer()" value="Restart player" />
				</div>
				<!-- random menu -->
				<div class="player_menu"><input type="checkbox" id="random" onclick="Playlist.random()"/><label for="random"> Random?</label></div>
				<!-- repeat menu -->
				<div class="player_menu"><select id="repeat_menu">
						<option onclick="Playlist.repeat('NONE')">Repeat None</option>
						<option onclick="Playlist.repeat('SONG')">Repeat Song</option>
						<option onclick="Playlist.repeat('LIST')">Repeat List</option>
					</select>
				</div>
			</div>
			<!-- marquee -->
			<div id='marquee'></div>
			<!-- player -->
			<div id="playerWrapper">
				<div id="playerSpot">This text will be replaced by the media player.</div>
			</div>
			<!-- saved playlists -->
			<div id='savedPlaylists'></div>
			<!-- playlist -->
			<div id='playlist'></div>
		</div>

		<!-- collection -->
		<div id='collection'>
			<div id="alphaNav"></div>
			<div id='output'></div>
		</div>

<!-- TEMPLATES -->
<!-- marquee template -->
<textarea id='marqueeTemplate' class='template'>
<div>
	<span class="artist">${artist}</span> - <span class="title">${title}</span>
	<span class="album">${album}</span>
</div>
<div class='clear'/>
</textarea>

<!-- saved playlists -->
<textarea id="savedPlaylistsTemplate" class="template">
<select id='savedPlaylistsItems'>
	<option value="_new">New playlist</option>
	{for p in playlists}
	<option value='${p}'>${p}</option>
	{/for}
</select>
<!--
<div style='height: 3em;scroll-y:true'>
<ul id='saved' style='margin: 0; indent: 0'>
	<li>New playlist</li>
	{for p in playlists}
	<li>${p}</li>
	{/for}
</ul>
</div>
-->
<input type='button' value='New' onclick='Playlist.clear()'/>
<input type='button' value='Load' onclick='Playlist.load()'/>
<input type='button' value='Save' onclick='Playlist.save()'/>
<input type='button' value='Delete' onclick='Playlist.remove()'/>
</textarea>

<!-- playlist template -->
<textarea id="playlistTemplate" class="template">
<ul id='playlistItems'>
{for i in items}
<li id='playlistItem_${i_index}' class='playlistItem'>
	<div onclick="Player.controls.play('${i_index}')" class='content'>
		<span class="artist">${i.artist}</span> - <span class="title">${i.title}</span>
		<span class="album">${i.album}</span>
	</div>
	<div class='remove'>
		<a href='#' onclick="Playlist.removeItem('${i_index}')">X</a>
	</div>
	<div class='clear'/>
</li>
{forelse}
<div>Click through the collection and add some songs!</div>
{/for}
</ul>
</textarea>

<!-- alpha navigation -->
<textarea id="alphaNavTemplate" class="template">
<ul>
{for nav in items}
	<li onclick="Collection.view({search:'${nav}'});return false">${nav}</li>
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
			<li id="backLink"><a href="#" onclick='Collection.goBack();return false'>Go back</a></li>
			<li id="addAllLink"><a href='#' onclick='Collection.addAll();return false'>Add All Songs</a></li>
		</ul>
	</div>

<ul class='dirs'>
{for _d in d}
<li class='dir'><a href="#" onclick='Collection.view({dir:"${_d_index}"});return false'>${_d.l}</a></li>
{/for}
</ul>

<ul class='files'>
{for _f in f}
<li class='file'><a href="#" onclick="Collection.addSong(${_f_index});return false" class="fileAdd">[add]</a> ${_f.a} - ${_f.t}</li>
{/for}
</ul>
{else}
Select from list.
{/if}
</textarea>

	</body>
</html>
