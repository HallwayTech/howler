<ul class='items'>
<?php foreach($playlist as $index => $track): ?>
	<li id='playlist-item-<?= $index ?>' class='playlist-item'>
	    <div onclick="Player.controls.play('<?= $index ?>')" class='content'>
	        <span class="artist"><?= $track['artist'] ?></span> - <span class="title"><?= $track['title'] ?></span>
	        <span class="album"><?= $track['album'] ?></span>
	    </div>
	    <div class='remove'>
	        <a href='#' onclick="Playlist.removeItem('<?= $index ?>')">X</a>
	    </div>
	    <div class='clear'></div>
	</li>
<?php endforeach ?>
</ul>
<?php

/* End of file html.php */
/* Location: ./system/application/controllers/html.php */