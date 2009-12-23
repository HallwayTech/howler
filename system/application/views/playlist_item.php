<li id='playlist-item-<?= $_id ?>' class='playlist-item'>
    <div class='content' onclick='Player.controls.play("<?= $_id ?>")'>
        <a href='#' onclick='Player.controls.play("<?= $_id ?>");return false'>&#187;</a>
<?php if ($artist or $title or $album): ?>
        <span class='artist'><?= $artist ?></span> - <span class='title'><?= $title ?></span>
        <span class='album'><?= $album ?></span>
<?php else: ?>
        <span class='label'><?= $file ?></span>
<?php endif ?>
    </div>
    <div class='remove'>
        <a href='#' onclick='Playlist.removeItem('<?= $_id ?>');return false'>X</a>
    </div>
    <div class='clear'></div>
</li>
<?php

/* End of file playlist_entry.php */
/* Location: ./system/application/views/playlist_entry.php */