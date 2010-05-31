<g:setProvider library="jquery"/>
${params.artist}=${entriesCount}
<div id='collection'>
<g:if test='${entries}'>
    <ul class='entries'>
    <g:each var='entry' in='${entries}' status='i'>
        <li class='entry'>
        	<a href='#' onclick='Player.play("${entry.id}");return false' title=''>${entry.artist}</a>
        	<div class='add-entry-button'>
	            <a href='#' onclick='Queue.add("${entry.artist}");return false' class='fileAdd' title='<g:message code="entry.add.queue" args="${[entry.artist]}"/>'>
	                <img src='<g:createLinkTo dir='images' file='bullet_arrow_down.png'/> alt='Add "${entry.artist}"' />
	            </a>
            </div>
        </li>
	</g:each>
    </ul>
</g:if>
</div>