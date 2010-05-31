<html>
    <head>
        <title><g:message code="application.title"/></title>
		<meta name="layout" content="main" />
    </head>
    <body>
    	<div id='header'>
    		<div id='header-controls'>
    			<img src='${resource(dir:'images', file:'control_play_blue.png')}' alt='${message(code:'player.play')}'/>
    			<img src='${resource(dir:'images', file:'control_end_blue.png')}' alt='${message(code:'player.prev')}'/>
    			<img src='${resource(dir:'images', file:'control_start_blue.png')}' alt='${message(code:'player.next')}'/>
    			<img src='${resource(dir:'images', file:'control_repeat_blue.png')}' alt='${message(code:'player.repeat')}'/>
    		</div>
    		<div id='header-marquee'><g:message code='player.notPlaying' /></div>
    		<!--
                <span class='label'></span>
                <span class='artist'></span>
                -
                <span class='title'></span>
                <span class='album'></span>
            </div>
            -->
    		<div id='header-player'><hr/></div>
    		<div id='header-search'></div>
    		<div id='header-artist'></div>
    		<div id='header-album'></div>
    	</div>
    	<div id='body'>
    		<div id='collection'></div>
    	</div>
    	<jq:jquery>
    	<g:remoteFunction controller='artist' action='list' update='header-artist' method='get'/>
    	<g:remoteFunction controller='album' action='list' update='header-album' method='get'/>
    	</jq:jquery>
    </body>
</html>