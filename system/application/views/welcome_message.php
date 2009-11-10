<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- javascript -->
        <script type="text/javascript" src="lib/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="lib/jquery-ui-1.7.1.custom.min.js"></script>
        <script type="text/javascript" src="lib/swfobject-2.1.min.js"></script>
        <script type="text/javascript" src="lib/trimpath-template-1.0.38.min.js"></script>
        <script type="text/javascript" src="lib/querystring-1.3.min.js"></script>
        <script type="text/javascript" src="lib/json2.min.js"></script>
        <script type="text/javascript" src="js/template.js"></script>
        <script type="text/javascript" src="js/playlist.js"></script>
        <script type="text/javascript" src="js/player.js"></script>
        <script type="text/javascript" src="js/collection.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- stylesheets -->
        <link rel="stylesheet" type="text/css" href="css/collection.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.1.custom.css" />
        <link rel="stylesheet" type="text/css" href="css/player.css" />
        <link rel="stylesheet" type="text/css" href="css/playlist.css" />

        <title>&#164;&#164; Home Media Player &#164;&#164;</title>
    </head>
    <body>
        <div id="player">
            <!-- menus -->
            <div id="menubar">
                <!-- actions menu -->
                <div class="player-menu"><input type="button" id="restart-player" onclick="Actions.restartPlayer()" value="Restart player" /></div>
                <!-- random menu -->
                <div class="player-menu"><input type="checkbox" id="random"<?= ($random == 'true') ? ' checked="checked"' : '' ?> onclick="Player.random(this.checked)"/><label for="random"> Random?</label></div>
                <!-- repeat menu -->
                <div class="player-menu"><select id="repeat-menu"><?php foreach($repeats as $value => $output): ?>
                	<option value="<?= $value ?>"<?= ($repeat == $value) ? ' selected="selected"' : '' ?>><?= $output ?></option>
                <?php endforeach ?></select></div>
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
                        <input type='button' value='Save as...' onclick='Playlist.save()'/>
                    </div>
                    <div class='right'>
                        <a href='#' onclick='$("#saved-playlists").toggle("normal");return false'>--</a>
                    </div>
                </div>
                <div class='clear'></div>
                <div id='saved-playlists'></div>
            </div>
            <!-- playlist -->
            <div id='playlist'></div>
        </div>

        <!-- collection -->
        <div id='collection'>
            <div id='alphaNav'>
                <ul>
<?php foreach($alpha_nav as $alpha): ?>
                    <li onclick="Collection.search('<?= $alpha ?>');return false"><?= $alpha ?></li>
<?php endforeach ?>
                </ul>
            </div>
            <div id='listingContainer'></div>
        </div>
    </body>
</html>

<?php 
/* End of file welcome_message.php */
/* Location: ./system/application/views/welcome_message.php */
?>