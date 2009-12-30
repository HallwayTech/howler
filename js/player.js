var swfplayer = null;
// id of player after it is embedded
var playerId = 'swfplayer';
// id of element to replace with embedded player
var playerAreaId = 'swfplayer';

/**
 * Called after the player has been created and is ready for interaction.
 * Performed by the player swf.
 */
function playerReady(thePlayer) {
	swfplayer = swfobject.getObjectById(playerId);

	if (!swfplayer) {
		swfplayer = document.getElementById(playerId);
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

	var _currentId = null;
	var _state = null;

	return {
		create: function() {
			var swfUrl = 'lib/player-4.2.swf';
			var width = '90%';
			var height = 20;
			var flashVersion = '7.0.0';
			var expressInstallSwfUrl = false;
			var flashVars = {
				file: '',
				bufferlength: 5,
				volume: 100
			};

			var params = {
				allowscriptaccess: 'always',
				allowfullscreen: false,
				wmode: 'opaque'
			};

			var attributes = {
				id: playerId,
				name: playerId
			};

			swfobject.embedSWF(swfUrl, playerAreaId, width, height, flashVersion,
					expressInstallSwfUrl, flashVars, params, attributes);
		},
 
		currentPlayingId: function() {
			return _currentId;
		},

		init: function() {
			if (swfplayer) {
				swfplayer.addModelListener('STATE', 'Player.trackers.stateTracker');
				Player.setMarquee('0');
			} else {
				alert('Unable to find player.');
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
			$('#player-wrapper').append('<div id="' + playerAreaId + '">player</div>');
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

			pause: function() {
				swfplayer.sendEvent('PLAY', false);
			},

			play: function(id) {
				if (id && id != _currentId) {
					// load, play and highlight the item
					var url = 'index.php/files/read/' + id;
					var item = {file: url, type: 'sound', start: '0'};
					swfplayer.sendEvent('LOAD', [item]);
					_currentId = id;
					swfplayer.sendEvent('PLAY', true);
					Player.setMarquee(id);
					Playlist.highlight(id);
				} else if (_state == 'PLAYING') {
					swfplayer.sendEvent('PLAY', false);
				} else {
					swfplayer.sendEvent('PLAY', true);
				}
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
					Player.controls.next();
				} else if (_state == 'PLAYING' && info['oldstate'] == 'PAUSED') {
					Playlist.highlightFocus();
				} else if (_state == 'PAUSED') {
					Playlist.highlightBlur();
				}
			}
		}
	}
}();

$(document).ready(function() {
	Player.create();
});
