var player = null;
// id of player after it is embedded
var playerId = 'dood';
// id of element to replace with embedded player
var playerAreaId = 'playerSpot';

/**
 * Called after the player has been created and is ready for interaction.
 * Performed by the player swf.
 */
function playerReady(thePlayer) {
	player = swfobject.getObjectById(playerId);

	if (!player) {
		player = document.getElementById(playerId);
	}

	Player.init();
}

/**
 * The player object that contains all controls, trackers and playlist
 * functionality.
 */
var Player = function() {
	var _repeat = 'NONE' // SONG, LIST;
	var _random = false;
	var _state = null;

	return {
		create: function() {
			var swfUrl = 'lib/player-4.2.90.swf';
			var width = '100%';
			var height = 20;
			var flashVersion = '7.0.0';
			var expressInstallSwfUrl = false;
			var flashVars = {
				'file': '',
				'bufferlength': '5'
			};

			var params = {
				'allowscriptaccess': 'always',
				'allowfullscreen': 'false'
			};

			var attributes = {
				'id': playerId,
				'name': playerId
			};

			swfobject.embedSWF(swfUrl, playerAreaId, width, height, flashVersion, expressInstallSwfUrl, flashVars, params, attributes);
		},
 
		init: function() {
			if (player) {
				player.addModelListener('STATE', 'Player.trackers.stateTracker');
				Playlist.refresh(true);
				var output = Template.processTemplate('marqueeTemplate', {artist:'',title:'',album:''});
				Player.setMarquee(output);
			} else {
				alert('Unable to find player.');
			}
		},

		/**
		 * random() -- tells where the play order should be random
		 *
		 * @returns true if play should be random
		 *          false otherwise
		 */
		random: function(checked) {
			if (typeof(checked) != 'undefined') {
				_random = checked;
				if (_state != 'PLAYING') {
					Player.controls.next();
				}
			} else {
				return _random;
			}
		},

		/**
		 * repeat() -- gets the repeat state
		 *
		 * repeat(val) -- sets the repeat state.
		 *   accepted values: NONE, SONG, LIST
		 */
		repeat: function(state) {
			if (state) {
				_repeat = state.toUpperCase();
			} else {
				return _repeat;
			}
		},

		/**
		 * Restart the player by destroying the embedded flash element and recreating it.
		 * This is needed when to have a working player when the browser destroys the
		 * element for unknown reasons.  Refreshing the page also works but the playlist
		 * is lost.  This allows the playlist to be kept.
		 */
		restart: function() {
			swfobject.removeSWF(playerId);
			$('#playerWrapper').append('<div id="' + playerAreaId + '">player</div>');
			Player.create();
		},

		setMarquee: function(text) {
			$('#marquee').html(text);
		},

		controls: {
			next: function() {
				var next = Playlist._playingIdx + 1;
				var stopAtEnd = _repeat == 'NONE' && next >= Playlist._playlist.length;
				if (!stopAtEnd) {
					if (_random) {
						do {
							// get random playlist position
							// then play it
							next = Math.round(Math.random() * (Playlist._playlist.length - 1));
						} while (next == Playlist._playingIdx);
					}
					Player.controls.play(next);
				}
			},

			play: function(idx) {
				// set Playlist._playingIdx if an index is requested
				if (!isNaN(idx)) {
					if (idx < 0) {
						Playlist._playingIdx = Playlist._playlist.length - (Math.abs(idx) % Playlist._playlist.length);
					} else if(idx > 0) {
						Playlist._playingIdx = idx % Playlist._playlist.length;
					} else {
						Playlist._playingIdx = idx;
					}
				}

				// make sure Playlist._playingIdx is within legal bounds
				Playlist._playingIdx = Math.max(0, Playlist._playingIdx);
				Playlist._playingIdx = Math.min(Playlist._playingIdx, Playlist._playlist.length - 1);

				// pick the correct item
				var next = Playlist._playlist[Playlist._playingIdx];

//				alert('play:' + next.file);

				// load, play and highlight the item
				player.sendEvent('LOAD', [next]);
				player.sendEvent('PLAY', true);
				Playlist.highlightPlaying();
				var output = Template.processTemplate('marqueeTemplate', next);
				Player.setMarquee(output);
			},

			prev: function() {
				Player.controls.play(Playlist._playingIdx - 1);
			}
		},

		trackers: {
			/**
			 * @param info {id, client, version}
			 */
			nextTracker: function(info) {
				Player.controls.next();
			},

			/**
			 * @param info {id, client, version}
			 */
			prevTracker: function(info) {
				Player.controls.prev();
			},

			/**
			 * @param info {newstate,oldstate,id,client,version}
			 *
			 * state in (IDLE, BUFFERING, PLAYING, PAUSED, COMPLETED)
			 */
			stateTracker: function(info) {
				_state = info['newstate'];
				// if playing is complete, progress the playlist forward by 1, load next
				// song into the player, and change the highlighted playlist item.
				if (_state == 'COMPLETED') {
					if (_repeat == 'SONG') {
						Player.controls.play();
					} else {
						Player.controls.next();
					}
				}
			}
		}
	}
}();

$(document).ready(function() {
	Player.create();
});
