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
	// constants
	var MAX_HIST = .75;

	// default setup states
	var _repeat = 'LIST'; // NONE, SONG, LIST
	var _random = false;
	var _state = null;
	var _history = [];

	return {
		create: function() {
			var swfUrl = 'lib/player-4.2.90.swf';
			var width = '100%';
			var height = 20;
			var flashVersion = '7.0.0';
			var expressInstallSwfUrl = false;
			var flashVars = {
				'file': '',
				'bufferlength': '5',
				'volume': 100
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
				Player.setMarquee('0');
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
				if (_random && _state != 'PLAYING') {
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

		setMarquee: function(id) {
			// collect available information
			var playlistItem = $('#playlist-item-' + id + ' .content');
			var artist = $('.artist', playlistItem).text();
			var title = $('.title', playlistItem).text();
			var album = $('.album', playlistItem).text();
			var label = $('.label', playlistItem).text();

			// set the values in the marquee
			var marquee = $('#marquee');
			if (label) {
				// set and show the label
				$('.label', marquee).html(label).show();

				// hide the unused fields
				$('.artist,.title,.album', marquee).hide();
			} else {
				// hide the unused label
				$('.label', marquee).hide();

				// set the artist, title and album then show them
				$('.artist', marquee).html(artist);
				$('.title', marquee).html(title);
				$('.album', marquee).html(album);
				$('.artist,.title,.album', marquee).show();
			}
		},

		controls: {
			next: function() {
				var next = Playlist.nextId();
				if (next) {
					Player.controls.play(next);
				}
			},

			play: function(id) {
				if (id) {
					// load, play and highlight the item
					var url = 'index.php/files/read/' + id;
					var item = {file: url, type: 'sound', start: '0'};
					player.sendEvent('LOAD', [item]);
					Playlist.highlight(id);
					Player.setMarquee(id);
				}
				player.sendEvent('PLAY', true);
			},

			prev: function() {
				var prev = Playlist.prevId();
				if (prev) {
					Player.controls.play(prev);
				}
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
