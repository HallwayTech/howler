<html>
    <head>
        <meta http-equiv=”Content-Type” content=”text/html; charset=utf-8″ />
        <!-- javascript -->
        <script type="text/javascript" src="scripts.php"></script>
        <!-- stylesheets -->
        <link rel="stylesheet" type="text/css" href="stylesheets.php" />

        <title>&#164;&#164; Home Media Player &#164;&#164;</title>
    </head>
    <body>
        <div id="player">
            <!-- menus -->
            <div id="menubar">
                <!-- actions menu -->
                <div class="player-menu"><input type="button" id="restart-player" onclick="Actions.restartPlayer()" value="Restart player" />
                </div>
                <!-- random menu -->
                <div class="player-menu"><input type="checkbox" id="random" {if $random == 'true'}checked='checked'{/if} onclick="Player.random(this.checked)"/><label for="random"> Random?</label></div>
                <!-- repeat menu -->
                <div class="player-menu"><select id="repeat-menu">{html_options values=$repeat_values output=$repeat_output selected=$repeat}</select></div>
            </div>
            <!-- marquee -->
            <div id='marquee'>
                <span class="artist"></span> - <span class="title"></span>
                <span class="album"></span>
                <div class='clear'></div>
            </div>
            <!-- player -->
            <div id="playerWrapper">
                <div id="playerSpot">This text will be replaced by the media player.</div>
            </div>
            <!-- saved playlists -->
            <div id='saved-playlists-container'>
                <div id='saved-playlists-actions'>
                    <div class='left'>
                        <input type='button' value='New' onclick='Playlist.clear()'/>
                        <input type='button' value='Save as..' onclick='Playlist.save()'/>
                    </div>
                    <div class='right'>
                        <a href='#' onclick='$("#saved-playlists").toggle("normal");return false'>--</a>
                    </div>
                </div>
                <div class='clear'></div>
            </div>
            <!-- playlist -->
            <div id='playlist'></div>
        </div>

        <!-- collection -->
        <div id='collection'>
            <div id='alphaNav'>
                <ul>
                {section loop=$alphaNav name=i}
                    <li onclick="Collection.search('{$alphaNav[i]}');return false">{$alphaNav[i]}</li>
                {/section}
                </ul>
            </div>
            <div id='listingContainer'>
                <span id='listingHeader'>Listing for <span id='listingName'></span></span>
                <div id='collectionNav'>
                    <ul>
                        <li id='refreshLink'><a href='#' onclick='Collection.refresh();return false'>Refresh</a></li>
                        <li id='backLink'><a href='#' onclick='Collection.back();return false'>Go back</a></li>
                        <li id='addAllLink'><a href='#' onclick='Collection.addAll();return false'>Add All Songs</a></li>
                    </ul>
                </div>
                <div id='listing'></div>
            </div>
        </div>
    </body>
</html>
