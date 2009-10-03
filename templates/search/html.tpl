<ul class='dirs'>
{section loop = $data.d name = i}
    <li class='dir'><a href='#' onclick="Collection.view('{$i}');return false">{$data.dirs[i].album}</a></li>
{/section}
</ul>

<ul class='files'>
{section loop = $data.files name = i}
    {$title = $data.files[i].title;}
    {$artist = $data.files[i].artist;}
    {$file = $data.files[i].file;}
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
