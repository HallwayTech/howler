<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<li class="entry">
			<a href="#" onclick="${remoteFunction(controller:'entry', action:'findBy', update:'collection', method:'get', params:['type':type, (type):entry[0]])}return false"
					class="fileAdd" title="${message(code:'entry.add.queue', args:[entry[0]])}">${entry[0]} (${entry[1]})</a>
			<div class="add-entry-button">
				<a href="#" onclick="${remoteFunction(controller:'entry', action:'findBy', update:'collection', method:'get', params:['type':type, (type):entry[0]])};return false"
						class="fileAdd" title="${message(code:'entry.add.queue', args:[entry[0]])}">
					<img src="${createLinkTo(dir: "images", file: "bullet_arrow_down.png")}" alt="${message(code:"entry.add.queue", args:[entry[0]])}" />
				</a>
			</div>
		</li>
	</g:each>
	</ul>
</g:if>
