<div id='collection-nav'>
<?php if (!empty($parent)): ?>
    <a href='#' onclick='Collection.view("<?= $parent ?>");return false'><img src='images/folder_back.png' /></a>
<?php elseif (!empty($search)): ?>
    <a href='#' onclick='Collection.search("<?= $search ?>");return false'><img src='images/folder_back.png' /></a>
<?php else: ?>
    <img class='dimmed' src='images/folder_back.png' />
<?php endif ?>
<?php if (!empty($id)): ?>
    <a href='#' onclick='Playlist.addParent("<?= $id ?>");return false'><img src='images/folder_add.png' /></a>
<?php else: ?>
    <img class='dimmed' src='images/folder_add.png' />
<?php endif ?>
</div>
<div id='collection-header'>Listing for <span id='collection-label'><?= $label ?></span></div>

<div id='collection'>
<?php if (!empty($dirs)): ?>
    <ul class='dirs'>
    <?php foreach ($dirs as $dir): ?>
        <li class='dir'>
            <a href='#' onclick='Playlist.addParent("<?= $dir['id'] ?>");return false' class='fileAdd' title='Add "<?= $dir['label'] ?>"'>
                <img src='images/folder_add.png' alt='Add "<?= $dir['label'] ?>"' />
            </a>
            <a href='#' onclick='Collection.view("<?= $dir['id'] ?>");return false' title='View "<?= $dir['label'] ?>"'><?= $dir['label'] ?></a>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>

<?php if (!empty($files)):?>
    <ul class='files'>
    <?php foreach ($files as $file): ?>
        <li class='file' id='<?= $file['id'] ?>'>
            <a href='#' onclick='Collection.download("<?= $file['id'] ?>");return false' class='fileDownload' title='Download "<?= $file['label'] ?>"'><img src='images/arrow_down.png' alt='Download "<?= $file['label'] ?>"' /></a>
            <a href='#' onclick='Playlist.addItem("<?= $file['id'] ?>");return false' class='fileAdd' title='Add "<?= $file['label'] ?>"'><img src='images/add.png' alt='Add "<?= $file['label'] ?>"' /></a>
            <?= $file['label'] ?>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>
</div>
<div class='clear'></div>

<?php

/* End of file collections.php */
/* Location: ./system/application/views/collections.php */
?>