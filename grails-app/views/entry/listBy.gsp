<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<%
		def name = entry[0]
		def count = entry[1]
		def onclick = remoteFunction(controller: 'entry', action: 'findAllBy', update: 'collection',
				method: 'get', params: ['type': type, (type): name]) + "return false"
		def title = message(code: 'entry.add.queue', args: [name], encodeAs:'HTML')
		def imgSrc = createLinkTo(dir: "images", file: "bullet_arrow_down.png")
		def imgAlt = message(code: "entry.add.queue", args:[name], encodeAs:'HTML')
		%>
		<li class="entry">
			<a href="#" onclick="${onclick}" title="${title}">${name} (${count})</a>
		</li>
	</g:each>
	</ul>
</g:if>
