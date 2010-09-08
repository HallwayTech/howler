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
		add: function(id) {
			link$ = $('#' + id + ' .play')
			text = $('#' + id).text()
			$('#jplayer_playlist ul').append('<li>').append($('#' + id + ' .play')).append(text).append('</li>')
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

		play: function(id, title) {
			//now-playing
			$('#' + id).addClass('jplayer_playlist_current')
			$("#player")
				.jPlayer("setFile", "entry/stream/" + id)
				.jPlayer("play")
			$("#marquee").text(title)
		},

		previous: function() {
			var prev = Playlist.prevId();
			if (prev) {
				Player.play(prev);
			}
		}
	}
}();
