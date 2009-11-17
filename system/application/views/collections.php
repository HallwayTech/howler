<span id="listHeader">Listing for <?= $label ?></span>
<!--
<div id="collectionNav">
	<ul>
		<li id="refreshLink"><a href='#' onclick='Collection.refresh();return false'>Refresh</a></li>
		<li id="backLink"><a href="#" onclick='Collection.back();return false'>Go back</a></li>
		<li id="addAllLink"><a href='#' onclick='Collection.addAll();return false'>Add All Songs</a></li>
	</ul>
</div>
-->

<div id='listing'>
<?php if (isset($dirs)): ?>
	<ul class='dirs'>
	<?php foreach ($dirs as $dir): ?>
		<li class='dir'><a href='#' onclick="Collection.view('<?= $dir['id'] ?>');return false"><?= $dir['label'] ?></a></li>
	<?php endforeach ?>
	</ul>
<?php endif ?>

<?php if (isset($files)):?>
	<ul class='files'>
	<?php foreach ($files as $file): ?>
		<?php $title = $file['title']; ?>
		<?php $artist = $file['artist']; ?>
		<?php $id = $file['id']; ?>
		<li class='file'>
			<a href="#" onclick="Collection.addSong('<?= $id ?>');return false" class="fileAdd">[add]</a>
		<?php if ($artist && $title): ?>
			<?= $artist ?> - <?= $title ?>
		<?php else: ?>
			<?= $id ?>
		<?php endif ?>
		</li>
	<?php endforeach ?>
	</ul>
<?php endif ?>
</div>

<?php

/* End of file collections.php */
/* Location: ./system/application/views/collections.php */
?>