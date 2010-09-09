/**
 * The player object that contains all controls, trackers and playlist functionality.
 */
var Player = function() {
	var ready = false;

	// constants
	var MAX_HIST = .75;

	var _currentId = null;
	var _state = null;

	return {
		add: function(id, title) {
			var imgLink$ = $('.' + id + ' .play').clone()
			var li = $('<li>').addClass(id).append(imgLink$).append(title)
			$('#jplayer_playlist ul').append(li)
		},

		currentPlayingId: function() {
			return _currentId;
		},

		init: function() {
			ready = true
			$('#jplayer_previous').click(Player.previous);
			$('#jplayer_next').click(Player.next);
		},

		next: function() {
			var next = Playlist.nextId();
			if (next) {
				Player.controls.play(next);
			}
		},

		play: function(id, title, entry) {
			var player$ = $("#player")
			
			var file = "entry/stream" + id
			var currentFile = player$.jPlayer("getData", "diag.src")

			if (file == currentFile) {
				var isPlaying = player$.jPlayer("getData", "diag.isPlaying")
				if (isPlaying) {
					player$.jPlayer("pause")
				} else {
					player$.jPlayer("play")
				}
			} else {
				$("#marquee").text(title)
				$('.now-playing').removeClass('now-playing')
				$('.' + id).addClass('now-playing')
				player$.jPlayer("setFile", file).jPlayer("play")
			}
		},

		previous: function() {
			var prev = Playlist.prevId();
			if (prev) {
				Player.play(prev);
			}
		}
	}
}();
