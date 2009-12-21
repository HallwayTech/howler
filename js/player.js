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
		 * Get the next index to play.  Considers if the next index should be
		 * chosen randomly.
		 *
		 * @param options Expected options include:
		 *  -current
		 *      The current index [int].
		 *  -history
		 *      The history of played indices [int array].
		 *  -random
		 *      Whether to choose randomly or not [boolean].
		 *  -playlistLength
		 *      Length of the current playlist [int].
		 * @return The next index to play.
		 */
		nextId: function(options) {
			var currentPlayingId = Playlist.currentPlayingId();
			var nextId = false;
			if (currentPlayingId) {
				var id = $('#' + currentPlayingId + ' + li').attr('id');
				if (id) {
					nextId = id.substring(id.lastIndexOf('-') + 1);
				}
			} else {
				var id = $('#playlist .items li:first').attr('id');
				if (id) {
					nextId = id.substring(id.lastIndexOf('-') + 1);
				}
			}
			return nextId;
			
			/*
			options = options || {};
			var current = options.current || Playlist._playingIdx;
			var history = options.history || _history || [];
			var random = options.random || _random;
			var playlistLength = options.playlistLength || ;
			var next = 0;

			if (playlistLength > 0) {
				if (random) {
					do {
						// get random playlist position until no collision
						next = Math.round(Math.abs(Math.random() * (playlistLength - 1)));
					} while (next == current || history.indexOf(next) > -1);

					history.push(next);
					while (history.length > history.length * MAX_HIST) {
						// push the last one off the list
						history.shift();
					}
				} else {
					next = current + 1;
				}
			}

			return next;
			*/
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
			var playlistItem = $('#playlist-item-' + id + ' .content');
			var artist = $('.artist', playlistItem).text();
			var title = $('.title', playlistItem).text();
			var album = $('.album', playlistItem).text();
			$('#marquee .artist').html(artist);
			$('#marquee .title').html(title);
			$('#marquee .album').html(album);
		},

		controls: {
			next: function() {
				var stopPlaying = _repeat == 'NONE';
				if (!stopPlaying) {
					var next = Player.nextId();
					Player.controls.play(next);
				}
			},

			play: function(id) {
				// load, play and highlight the item
				var url = 'index.php/files/read/' + id;
				var item = {file: url, type: 'sound', start: '0'};
				player.sendEvent('LOAD', [item]);
				player.sendEvent('PLAY', true);
				Playlist.highlight(id);
				Player.setMarquee(id);
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
