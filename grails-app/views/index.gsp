<html>
    <head>
        <title><g:message code='application.title'/></title>
		<meta name='layout' content='main' />
    </head>
    <body>
    	<div id='header'>
    		<div id='controls'>
    			<img src='${resource(dir:'images', file:'control_play_blue.png')}' alt='${message(code:'player.play')}'/>
    			<img src='${resource(dir:'images', file:'control_end_blue.png')}' alt='${message(code:'player.prev')}'/>
    			<img src='${resource(dir:'images', file:'control_start_blue.png')}' alt='${message(code:'player.next')}'/>
    			<img src='${resource(dir:'images', file:'control_repeat_blue.png')}' alt='${message(code:'player.repeat')}'/>
    		</div>
    		<div id='marquee'><g:message code='player.notPlaying' /></div>
    		<!--
                <span class='label'></span>
                <span class='artist'></span>
                -
                <span class='title'></span>
                <span class='album'></span>
            -->
    		<div id='player'><hr/></div>
    		<div id='search'></div>
    		<div id='selectors'>
	    		<div id='artist-selector'>
	    			<div class='header'><g:message code='entries.artists'/></div>
	    			<div id='artists-list'><div class='wait'><img src='${resource(dir:'images', file:'wait30trans.gif')}'/></div></div>
	    		</div>
	    		<div id='album-selector'>
		    		<div class='header'><g:message code='entries.albums'/></div>
		    		<div id='albums-list'><div class='wait'><img src='${resource(dir:'images', file:'wait30trans.gif')}'/></div></div>
	    		</div>
    		</div>
    	</div>
    	<div id='body'>
    		<div id='collection'></div>
    	</div>
    	<jq:jquery>
    		${remoteFunction(controller: 'entry', action:'listBy', params:[type:'artist'], update:'artists-list', method:'get')}
			${remoteFunction(controller: 'entry', action:'listBy', params:[type:'album'], update:'albums-list', method:'get')}
    	</jq:jquery>
    </body>
</html>
