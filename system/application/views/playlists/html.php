<ul class='items'>
<?php foreach($playlist as $index => $track): ?>
	<?php $id = $track['id'] ?>
	<?php $artist = array_key_exists('artist', $track) ? $track['artist'] : "" ?>
	<?php $album = array_key_exists('album', $track) ? $track['album'] : "" ?>
	<?php $title = array_key_exists('title', $track) ? $track['title'] : "" ?>
	<li id='playlist-item-<?= $id ?>' class='playlist-item'>
	    <div onclick="Player.controls.play('<?= $id ?>')" class='content'>
	        <span class="artist"><?= $artist ?></span> - <span class="title"><?= $title ?></span>
	        <span class="album"><?= $album ?></span>
	    </div>
	    <div class='remove'>
	        <a href='#' onclick="Playlist.removeItem('<?= $id ?>')">X</a>
	    </div>
	    <div class='clear'></div>
	</li>
<?php endforeach ?>
</ul>
<?php

/* End of file html.php */
/* Location: ./system/application/controllers/html.php */