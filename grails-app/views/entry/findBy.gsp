<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<li class="entry">${entry.artist} - ${entry.album} - ${entry.title}</li>
	</g:each>
	</ul>
</g:if>
