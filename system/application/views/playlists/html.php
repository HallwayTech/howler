<ul class='items'>
<?php foreach($playlist as $index => $track): ?>
	<?php $id = $track['id'] ?>
	<?php $artist = isset($track['artist']) ? $track['artist'] : "" ?>
	<?php $album = isset($track['album']) ? $track['album'] : "" ?>
	<?php $title = isset($track['title']) ? $track['title'] : "" ?>
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