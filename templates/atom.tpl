<feed xmlns='http://www.w3.org/2005/Atom' xmlns:media='http://search.yahoo.com/mrss/'>
	<title>$title</title>
	{foreach from=$data item=$track}
	foreach ($data as $track) {
		<entry>
			<title>{$track.title}</title>
			<media:credit role='author'>{$track.artist}</media:credit>
			<media:content url='{$track.file}' type='audio/mp3' />
		</entry>
    {/foreach}
</feed>

