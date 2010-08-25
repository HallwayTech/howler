/**
 * The player object that contains all controls, trackers and playlist
 * functionality.
 */
var Player = function() {
	var ready = false;

	// constants
	var MAX_HIST = .75;

	var _currentId = null;
	var _state = null;

	return {
		currentPlayingId: function() {
			return _currentId;
		},

		init: function() {
			ready = true
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
				$("#player")
					.jPlayer("setFile", "entry/show/" + id)
					.jPlayer("play")
				$("#jplayer_playlist ul li").text(id)
				/*
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
				*/
			},

			prev: function() {
				var prev = Playlist.prevId();
				if (prev) {
					Player.controls.play(prev);
				}
			}
		}
	}
}();
