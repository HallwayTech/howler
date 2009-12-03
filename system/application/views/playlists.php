<ul class='list'>
<?php foreach($rows as $index => $row): ?>
	<?php if ($index % 2 != 0): ?>
	<li class='item'>
	<?php else: ?>
	<li class='item light-bg'>
	<?php endif ?>
		<div class='desc'>
			<span class='name'><?= $row['value'] ?></span>
		</div>
		<div class='actions'>
			<span class='load'>[<a href='#' onclick='Playlist.loadPlaylist("<?= $row['id'] ?>");return false' title='Load' alt='Load'>L</a>]</span>
			<span class='save'>[<a href='#' onclick='Playlist.save("<?= $row['id'] ?>");return false' title='Save' alt='Save'>S</a>]</span>
			<span class='delete'>[<a href='#' onclick='Playlist.removePlaylist("<?= $row['id'] ?>");return false' title='Delete' alt='Delete'>D</a>]</span>
		</div>
		<div class='clear'></div>
	</li>
<?php endforeach ?>
</ul>
<?php

/* End of file playlists.php */
/* Location: ./system/application/controllers/playlists.php */
?>