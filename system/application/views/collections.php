<span id="listHeader">
	Listing for <?= $label ?>
</span>
<div id="collectionNav">
	<ul>
		<li id="refreshLink"><a href='#' onclick='Collection.refresh();return false'>Refresh</a></li>
		<li id="backLink"><a href="#" onclick='Collection.back();return false'>Go back</a></li>
		<li id="addAllLink"><a href='#' onclick='Collection.addAll();return false'>Add All Songs</a></li>
	</ul>
</div>

<?php if (isset($dirs)):
	$dirs_length = count($dirs); ?>
<ul class='dirs'>
<?php 	for ($i = 0; $i < $dirs_length; $i++):
			$dir = $dirs[$i]; ?>
	<li class='dir'><a href='#' onclick="Collection.view('<?= $i ?>');return false"><?= $dir ?></a></li>
<?php 	endfor ?>
</ul>
<?php endif ?>

<?php if (isset($files)):
	$files_length = count($files); ?>
<ul class='files'>
<?php 	for ($i = 0; $i < $files_length; $i++):
			$f = $files[$i];
			$title = $f['t'];
			$artist = $f['a'];
			$file = $f['f']; ?>
    <li class='file'>
		<a href="#" onclick="Collection.addSong(<?= $i ?>);return false" class="fileAdd">[add]</a>
<?php		if ($artist && $title): ?>
		<?= $artist - $title ?>
<?php		else: ?>
		<?= $file ?>
<?php		endif ?>
    </li>
<?php	endfor ?>
</ul>
<?php endif ?>

<!-- End of file collections.php -->
<!-- Location: ./system/application/views/collections.php -->