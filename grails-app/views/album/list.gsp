${params.album}=${entriesCount}
<div id='collection'>
<g:if test='${entries}'>
    <ul class='dirs'>
    <g:each var='entry' in='${entries}' status='i'>
        <li class='dir'>
        	<a href='#' onclick='Player.play("${entry.id}");return false' title=''>${entry.album}</a>
        	<div class='add-entry-button'>
	            <a href='#' onclick='Queue.add("${entry.album}");return false' class='fileAdd' title='<g:message code="entry.add.queue" args="${[entry.album]}"/>'>
	                <img src='${createLinkTo(dir: 'images', file: 'folder_add.png')}' alt='Add "${entry.album}"' />
	            </a>
            </div>
        </li>
	</g:each>
    </ul>
</g:if>
</div>