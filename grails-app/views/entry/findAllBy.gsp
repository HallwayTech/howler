<g:if test="${entries}">
	<table id="entries">
		<thead>
			<tr>
				<th></th>
				<th>Artist</th>
				<th>Album</th>
				<th>Title</th>
			</tr>
		</thead>
		<tbody>
		<g:each var="entry" in="${entries}" status="i">
			<%
			// Note: all of this is done here to localize the processing per loop

			// encode items for the entry
			def id = entry.id.encodeAsJavaScript()
			def artist = entry.artist.encodeAsHTML()
			def album = entry.album.encodeAsHTML()
			def title = entry.title.encodeAsHTML()

			// construct JavaScript calls
			def artistFunc = remoteFunction(controller: 'entry', action: 'findAllBy',
					update: 'collection', method: 'get',
					params: ['type': 'artist', 'artist': entry.artist]) + "return false"
			def albumFunc = remoteFunction(controller: 'entry', action: 'findAllBy',
				update: 'collection', method: 'get',
				params: ['type': 'album', 'album': entry.album]) + "return false"

			// get image sources
			def addImgSrc = resource(dir:'images', file:'add.png')
			def playImgSrc = resource(dir:'images', file:'control_play_blue.png')

			// build messages
			def addTitle = message(code:'entry.add.queue', args:[title], encodeAs:'HTML')
			def playTitle = message(code:'entry.play', args:[title], encodeAs:'HTML')
			def artistTitle = message(code:'artist.findby', args:[artist], encodeAs:'HTML')
			def albumTitle = message(code:'album.findby', args:[album], encodeAs:'HTML')
			%>
			<tr id="${id}" class="entry">
				<td class="actions">
					<!--
					<a href='#' onclick='player.add("${id}")' class='add'
						title='${addTitle}'><img src='${addImgSrc}' alt='${addTitle}'/></a>
					-->
					<a href='#' onclick='player.play("${id}")' class='play'
						title='${playTitle}'><img src='${playImgSrc}' alt='${playTitle}'/></a>
				</td>

				<td class="artist"><a href="#" onclick="${artistFunc}" title="${artistTitle}">${artist}</a></td>
				<td class="album"><a href="#" onclick="${albumFunc}" title="${albumTitle}">${album}</a></td>
				<td class="title"><a href='#' onclick='player.play("${id}")' class='play' title='${playTitle}'>${title}</a></td>
			</tr>
		</g:each>
		</tbody>
	</table>
</g:if>
