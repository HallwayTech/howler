<div id='saved-playlists'>
	<ul class='list'>
	{section loop=$playlists name=i}
		{cycle values="<li class='item'>, <li class='item light-bg'>"}
			<div class='desc'>
				<span class='name'>{$playlists[i]}</span>
			</div>
			<div class='actions'>
				<span class='load'>[<a href='#' onclick='Playlist.load("{$playlists[i]}");return false' title='Load' alt='Load'>L</a>]</span>
				<span class='save'>[<a href='#' onclick='Playlist.save("{$playlists[i]}");return false' title='Save' alt='Save'>S</a>]</span>
				<span class='delete'>[<a href='#' onclick='Playlist.remove("{$playlists[i]}");return false' title='Delete' alt='Delete'>D</a>]</span>
			</div>
			<div class='clear'></div>
		</li>
	{/section}
	</ul>
</div>
