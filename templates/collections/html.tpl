<ul class='dirs'>
{section loop=$data.dirs name=i}
	<li class='dir'><a href='#' onclick="Collection.view('{$smarty.section.i.index}');return false">{$data.dirs[i]}</a></li>
{/section}
</ul>

<ul class='files'>
{section loop=$data.files name=i}
    {strip}
    {$title = $data.files[i].t}
    {$artist = $data.files[i].a}
    {$file = $data.files[i].f}
    <li class='file'>
            <a href="#" onclick="Collection.addSong({$smarty.section.i.index});return false" class="fileAdd">[add]</a>
            {if $data.files[i].a && $data.files[i].t}
                    {$data.files[i].a} - {$data.files[i].t}
            {else}
                    {$data.files[i].f}
            {/if}
    </li>
    {/strip}
{/section}
</ul>