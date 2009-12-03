<ul class='items'>
<?php foreach($playlist as $index => $entry): ?>
	<?php $id = $entry['file'] ?>
	<?php $artist = isset($entry['artist']) ? $entry['artist'] : "" ?>
	<?php $album = isset($entry['album']) ? $entry['album'] : "" ?>
	<?php $title = isset($entry['title']) ? $entry['title'] : "" ?>
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