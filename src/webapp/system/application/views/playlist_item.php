<li id='playlist-item-<?= $entry_id ?>' class='playlist-item'>
    <ul>
    <li class='controls'>
        <a href='#' onclick='Player.controls.play("<?= $entry_id ?>");return false'><img src='images/control_play_blue.png' alt='Play "<?= $label ?>"' class='play' /></a><br/>
        <a href='#' onclick='Collection.view("<?= $parent_entry_id ?>");return false'><img src='images/folder_go.png' alt='View collection containing "<?= $label ?>"' /></a>
    </li>
    <li class='content' onclick='Player.controls.play("<?= $entry_id ?>")'>
<?php if (!empty($artist) and !empty($title) and !empty($album)): ?>
        <span class='artist'><?= $artist ?></span> - <span class='title'><?= $title ?></span>
        <span class='album'><?= $album ?></span>
<?php else: ?>
        <span class='label'><?= $url ?></span>
<?php endif ?>
    </li>
    <li class='remove'>
        <a href='#' onclick='Playlist.removeItem("<?= $entry_id ?>");return false' title='Remove "<?= $label ?>"'><img src='images/cross.png' alt='Remove "<?= $label ?>"' /></a>
    </li>
    </ul>
</li>
<?php

/* End of file playlist_entry.php */
/* Location: ./system/application/views/playlist_entry.php */
