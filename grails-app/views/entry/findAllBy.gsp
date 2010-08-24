<g:if test="${entries}">
	<ul class="entries">
	<g:each var="entry" in="${entries}" status="i">
		<li class="entry"><a href='#'
				onclick='$("#player").jPlayer("setFile", encodeURI("${entry.path}".substring(1))).jPlayer("play");alert(encodeURI("${entry.path}".substring(1)))'><img src='${resource(dir:'images', file:'control_play_blue.png')}' alt='${message(code:'player.play')}'/></a>
			${entry.artist} - ${entry.album} - ${entry.title}</li>
	</g:each>
	</ul>
</g:if>
