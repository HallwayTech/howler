<div id='collection'>
<g:if test='${entries}'>
	<table>
		<tr>
			<th><g:message code="entry.title" /></th>
			<th><g:message code="entry.artist" /></th>
			<th><g:message code="entry.album" /></th>
		</tr>
    	<g:each var='entry' in='${entries}' status='i'>
	        <tr class='dir'>
	        	<td></td><a href='#' onclick='Player.play("${entry.id}");return false' title='<g:message code="entry.play" args="${entry.title}" />'>${entry.title}</a></td>
	        	<td>${entry.artist}</td>
	        	<td>${entry.album}</td>
	        	<div class='add-entry-button'>
		            <a href='#' onclick='Queue.add("${entry.artist}");return false' class='fileAdd' title='<g:message code="entry.add.queue" args="${entry.artist}" />'>
		                <img src='${createLinkTo(dir: 'images', file: 'folder_add.png')}' alt='<g:message code="entry.add.queue" args="${entry.artist}" />' />
		            </a>
	            </div>
	        </tr>
		</g:each>
    </table>
</g:if>
</div>