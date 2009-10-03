<ul class='dirs'>
{section loop =$dirs name=i}
	<li class='dir'><a href='#' onclick="Collection.view('{$i}');return false">{$dirs[$i]['album']}</a></li>
{/section}
</ul>

<ul class='files'>
{section loop=$files name=i}
	{$title = $files[i].title;}
	{$artist = $files[i].artist;}
	{$file = $files[i].file;}
	{strip}
	<li class='file'>
		<a href="#" onclick="Collection.addSong({$i});return false" class="fileAdd">[add]</a>
		{if $title && $artist}
			{$artist} - {$title}
		{else}
			{$file}
		{/if}
	</li>
	{/strip}
{/section}
</ul>
