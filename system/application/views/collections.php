<span id='listHeader'>Listing for <?= $label ?></span>
<div id='collectionNav'>
    <ul>
        <li id='refreshLink'><a href='#' onclick='Collection.refresh();return false'>Refresh</a></li>
<?php if (!empty($parent)): ?>
        <li id='backLink'><a href='#' onclick='Collection.view("<?= $parent ?>");return false'>Go back</a></li>
<?php elseif (false and !empty($search)): ?>
        <li id='backLink'><a href='#' onclick='Collection.search("<?= $search ?>");return false'>Go back</a></li>
<?php else: ?>
        <li id='backLink'>Go back</li>
<?php endif ?>
        <li id='addAllLink'><a href='#' onclick='Playlist.addParent("<?= $id ?>");return false'>Add All Songs</a></li>
    </ul>
</div>

<div id='listing'>
<?php if (isset($dirs)): ?>
    <ul class='dirs'>
    <?php foreach ($dirs as $dir): ?>
        <li class='dir'>
            <a href='#' onclick='Playlist.addParent("<?= $dir['id'] ?>");return false' class='fileAdd' title='Add "<?= $dir['label'] ?>"'><img src='images/add.png' alt='Add "<?= $dir['label'] ?>"' /></a>
            <a href='#' onclick='Collection.view("<?= $dir['id'] ?>");return false' title='View "<?= $dir['label'] ?>"'><?= $dir['label'] ?></a>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>

<?php if (isset($files)):?>
    <ul class='files'>
    <?php foreach ($files as $file): ?>
        <li class='file' id='<?= $file['id'] ?>'>
            <a href='#' onclick='Playlist.addItem("<?= $file['id'] ?>");return false' class='fileAdd' title='Add "<?= $file['label'] ?>"'><img src='images/add.png' alt='Add "<?= $file['label'] ?>"' /></a><?= $file['label'] ?>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>
</div>

<?php

/* End of file collections.php */
/* Location: ./system/application/views/collections.php */
?>