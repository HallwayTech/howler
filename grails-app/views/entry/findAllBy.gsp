<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<%
		// all of this is done here to localize the processing per loop
		def id = entry.id.encodeAsJavaScript()
		def title = "${entry.artist} - ${entry.album} - ${entry.title}"

		def addImgSrc = resource(dir:'images', file:'add.png')
		def playImgSrc = resource(dir:'images', file:'control_play_blue.png')

		def addTitle = message(code:'entry.add.queue', args:[title])
		def playTitle = message(code:'entry.play', args:[title])
		%>
		<li class="${id} entry">
			<a href='#' onclick='Player.add("${id}", "${title.encodeAsJavaScript()}")' class='add'
				title='${addTitle.encodeAsHTML()}'><img src='${addImgSrc}' alt='${addTitle.encodeAsHTML()}'/></a>
			<a href='#' onclick='Player.play("${id}", "${title.encodeAsJavaScript()}")' class='play'
				title='${playTitle.encodeAsHTML()}'><img src='${playImgSrc}' alt='${playTitle.encodeAsHTML()}'/></a>
			${title.encodeAsHTML()}</li>
	</g:each>
	</ul>
</g:if>
