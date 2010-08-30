<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<%
		def onclick = remoteFunction(controller: 'entry', action: 'findAllBy', update: 'collection',
				method: 'get', params: ['type': type, (type): entry[0]]) + "return false"
		def title = message(code: 'entry.add.queue', args: [entry[0]], encodeAs:'HTML')
		def imgSrc = createLinkTo(dir: "images", file: "bullet_arrow_down.png")
		def imgAlt = message(code: "entry.add.queue", args:[entry[0]], encodeAs:'HTML')
		%>
		<li class="entry">
			<a href="#" onclick="${onclick}" title="${title}">${entry[0]} (${entry[1]})</a>
		</li>
	</g:each>
	</ul>
</g:if>
