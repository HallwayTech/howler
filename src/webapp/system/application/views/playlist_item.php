<li id='playlist-item-<?= $entry_id ?>' class='playlist-item'>
    <div class='controls'>
        <a href='#' onclick='Player.controls.play("<?= $entry_id ?>");return false'><img src='images/control_play_blue.png' alt='Play "<?= $label ?>"' class='play' /></a>
    </div>
    <div class='content' onclick='Player.controls.play("<?= $entry_id ?>")'>
<?php if (!empty($artist) and !empty($title) and !empty($album)): ?>
        <span class='artist'><?= $artist ?></span> - <span class='title'><?= $title ?></span>
        <span class='album'><?= $album ?></span>
<?php else: ?>
        <span class='label'><?= $url ?></span>
<?php endif ?>
    </div>
    <div class='remove'>
        <a href='#' onclick='Playlist.removeItem("<?= $entry_id ?>");return false' title='Remove "<?= $label ?>"'><img src='images/cross.png' alt='Remove "<?= $label ?>"' /></a>
    </div>
    <div class='clear'></div>
</li>
<?php

/* End of file playlist_entry.php */
/* Location: ./system/application/views/playlist_entry.php */