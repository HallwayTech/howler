<ul class='items'>
<?php foreach($playlists as $index => $playlist): ?>
<li id='playlist-item-<?= $index ?>' class='playlist-item'>
    <div onclick="Player.controls.play('<?= $index ?>')" class='content'>
        <span class="artist"><?= $playlist['artist'] ?></span> - <span class="title"><?= $playlist['title'] ?></span>
        <span class="album"><?= $playlist['album'] ?></span>
    </div>
    <div class='remove'>
        <a href='#' onclick="Playlist.removeItem('<?= $index ?>')">X</a>
    </div>
    <div class='clear'></div>
</li>
<?php endforeach ?>
</ul>
<?php

/* End of file playlists.php */
/* Location: ./system/application/controllers/playlists.php */
?>