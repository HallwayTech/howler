<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<%
		def title = "${entry.artist} - ${entry.album} - ${entry.title}"
		def titleJs = title.encodeAsJavaScript()
		def titleHtml = title.encodeAsHTML()
		def id = entry.id.encodeAsJavaScript()
		def imgSrc = resource(dir:'images', file:'control_play_blue.png')
		def imgAlt = message(code:'entry.play', args:[title], encodeAs: '')
		%>
		<li id="${id}" class="entry"><a onclick='Player.play("${id}", "${titleJs}")' href='#'
				title='${titleHtml}'><img src='${imgSrc}' alt='${imgAlt}'/>
			${title}</a></li>
	</g:each>
	</ul>
</g:if>
