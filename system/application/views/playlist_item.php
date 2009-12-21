<li id='playlist-item-<?= $_id ?>' class='playlist-item'>
    <div class='content'>
        <a href="#" onclick="Player.controls.play('<?= $_id ?>');return false">&#187;</a>
        <span class="artist"><?= $artist ?></span> - <span class="title"><?= $title ?></span>
        <span class="album"><?= $album ?></span>
    </div>
    <div class='remove'>
        <a href='#' onclick="Playlist.removeItem('<?= $_id ?>');return false">X</a>
    </div>
    <div class='clear'></div>
</li>
<?php

/* End of file playlist_entry.php */
/* Location: ./system/application/views/playlist_entry.php */