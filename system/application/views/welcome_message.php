<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <!-- stylesheets -->
        <link rel='stylesheet' type='text/css' href='index.php/stylesheets' />

        <title>&#164;&#164; Home Media Player &#164;&#164;</title>
    </head>
    <?php flush() ?>
    <body>
        <div id='main-left'>
            <!-- menus -->
            <div id='menubar'>
                <!-- actions menu -->
                <div class='player-menu'><input type='button' id='restart-player' onclick='Actions.restartPlayer()' value='Restart player' /></div>
                <!-- random menu -->
                <div class='player-menu'><input type='checkbox' id='random'<?= ($random == 'true') ? ' checked="checked"' : '' ?>/><label for='random'> Random?</label></div>
                <!-- repeat menu -->
                <div class='player-menu'><select id='repeat-menu'><?php foreach($repeats as $value => $output): ?>
                	<option value='<?= $value ?>'<?= ($repeat == $value) ? ' selected="selected"' : '' ?>><?= $output ?></option>
                <?php endforeach ?></select></div>
                <div class='clear'></div>
            </div>
            <!-- marquee -->
            <div id='marquee'>
                <span class='label'></span>
                <span class='artist'></span> - <span class='title'></span>
                <span class='album'></span>
                <div class='clear'></div>
            </div>
            <!-- player -->
            <div id='player-wrapper'>
                <div id='player-actions'>
                    <a href='#' onclick='Player.controls.prev(true);return false' title='&#60 Previous'><img src='images/control_start.png' class='player-button' alt='&#60 Previous' /></a><a href='#' onclick='Player.controls.next(true);return false' title='Next &#62'><img src='images/control_end.png' class='player-button' alt='Next &#62' /></a>
                </div>
                <div id='swfplayer'>This text will be replaced by the media player.</div>
                <div class='clear'></div>
            </div>
            <!-- saved playlists -->
            <div id='saved-playlists-container'>
                <div id='saved-playlists-actions'>
                    <div class='left'>
                        <input type='button' value='New' onclick='Playlist.clear()'/>
                        <input type='button' value='Save as...' onclick='Playlist.savePlaylist()'/>
                    </div>
                    <div class='right'>
                        <a href='#' onclick='Playlist.toggleSavedView();return false' title='Hide saved playlists' class='hide-button'><img src='images/bullet_arrow_up.png' alt='Hide saved playlists' /></a>
                    </div>
                    <div class='clear'></div>
                </div>
                <div id='saved-playlists'>
                    <ul class='list'></ul>
                </div>
                <div class='clear'></div>
            </div>
            <!-- playlist -->
            <div id='playlist'>
                <ul class='items'></ul>
                <div class='clear'></div>
            </div>
        </div>

        <!-- collection -->
        <div id='main-right'>
            <div id='alphaNav'>
                <ul>
				<?php foreach($alpha_nav as $alpha): ?>
					<?php if (is_array($alpha)): ?>
					<li onclick='Collection.search("<?= $alpha[1] ?>")'><a href='#' onclick='Collection.search("<?= $alpha[1] ?>");return false' title='Search for "<?= $alpha[1] ?>"'><?= $alpha[0] ?></a></li>
					<?php else: ?>
                    <li onclick='Collection.search("<?= $alpha ?>")'><a href='#' onclick='Collection.search("<?= $alpha ?>");return false' title='Search for "<?= $alpha ?>"'><?= $alpha ?></a></li>
                    <?php endif ?>
				<?php endforeach ?>
                </ul>
            </div>
            <div id='listingContainer'></div>
        </div>
        <!-- javascript -->
        <script type='text/javascript' src='index.php/scripts'></script>
    </body>
</html>

<?php 
/* End of file welcome_message.php */
/* Location: ./system/application/views/welcome_message.php */
?>