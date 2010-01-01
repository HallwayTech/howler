<?= "<?xml version='1.0' encoding='UTF-8'?>" ?>
<playlist version='1' xmlns='http://xspf.org/ns/0/'>
	<title><?= $title ?></title>
	<trackList>
    <?php foreach ($playlist as $track): ?>
		<track>
			<title><?= $track['title'] ?></title>
			<album><?= $track['album'] ?></album>
			<creator><?= $track['artist'] ?></creator>
			<location><?= $track['file'] ?></location>
		</track>
	<?php endforeach?>
	</trackList>
</playlist>
