<g:if test="${entries}">
    <ul class="entries">
    <g:each var="entry" in="${entries}" status="i">
        <li class="entry">
        	<a href="#" onclick="${remoteFunction(controller:'entry', action:'findByArtist', update:'collection', method:'get', params:[album:entry[0]])}return false" class="fileAdd" title="${message(code:'entry.add.queue', args:[entry[0]])}">${entry[0]} (${entry[1]})</a>
        	<!--
        	<a href="#" onclick="Player.play('${entry[0]}');return false" title="${message(code:'entry.play', args:[entry[0]])}">${entry[0]} (${entry[1]})</a>
        	-->
        	<div class="add-entry-button">
                <img src="${createLinkTo(dir: "images", file: "bullet_arrow_down.png")}" alt="${message(code:"entry.add.queue", args:[entry[0]])}" />
            </div>
        </li>
	</g:each>
    </ul>
</g:if>
