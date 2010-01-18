<?php foreach($query->result() as $index => $row): ?>
    <?php $id = $row->playlist_id ?>
    <?php $title = $row->name ?>
    <?php if ($index % 2 != 0): ?>
    <li class='item-even'>
    <?php else: ?>
    <li class='item-odd'>
    <?php endif ?>
        <div class='desc'>
            <span class='name'><?= $title ?></span>
        </div>
        <div class='actions'>
            <span class='load'><a href='#' onclick='Playlist.loadPlaylist("<?= $id ?>");return false' title='Load playlist: "<?= $title ?>"'><img src='images/page_white_go.png' alt='Load playlist: "<?= $title ?>"' /></a></span>
            <span class='save'><a href='#' onclick='Playlist.savePlaylist("<?= $title ?>");return false' title='Save playlist: "<?= $title ?>"'><img src='images/page_white_edit.png' alt='Save playlist: "<?= $title ?>"' /></a></span>
            <span class='delete'><a href='#' onclick='Playlist.deletePlaylist("<?= $id ?>");return false' title='Delete playlist: "<?= $title ?>"'><img src='images/page_white_delete.png' alt='Delete "<?= $title ?>"' /></a></span>
        </div>
    </li>
<?php endforeach;

/* End of file playlists.php */
/* Location: ./system/application/controllers/playlists.php */
