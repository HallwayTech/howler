/* global $ */
var Playlist = function() {
	var PROC_PLAYLISTS = 'index.php/playlists';
	var PROC_LOAD_FILE = 'index.php/files/read/';

	return {
		_playingIdx: -1,

		/**
		 * Initialization of the playlist
		 */
		init: function() {
			Playlist.loadPlaylists();
//			$('#saved-playlists').resizable();
		},

		/**
		 * add(item) -- add an item to the current loaded playlist
		 *
		 * @param item The item to add to the playlist.  Should be compatible
		 *             with any item params expected by your player.
		 */
		addItem: function(item) {
			item.type = 'sound';
			item.start = '0';
			item.file = PROC_LOAD_FILE + encodeURIComponent(item.id);
			// TODO add entry to html list
		},

		clear: function() {
			$('#playlist').empty();
		},

		highlightPlaying: function() {
			if (Playlist._playingIdx >= 0) {
				// clear last played song
				$('.now-playing').removeClass('now-playing');

				// highlight current song
				$('#playlist-item-' + Playlist._playingIdx).addClass('now-playing');

				// calculate top position and scroll to it
				var playlistTop = $('#playlist').position()['top'];
				var nowPlayingTop = $('.now-playing').position()['top'];
				var topDiff = nowPlayingTop - playlistTop;
				var scrollTop = $('#playlist').scrollTop();

				$('#playlist').scrollTop(scrollTop + topDiff);
			}
		},

		loadPlaylist: function(name) {
			if (name) {
				var url = PROC_PLAYLISTS + '/read/' + encodeURIComponent(name);
				$('#playlist').load(url, function() {
					$('.items', this).sortable({
						axis: 'y',
						opacity: .75
					});
				});
			} else {
				alert('No playlist to load.');
			}
		},

		loadPlaylists: function() {
			$('#saved-playlists').load(PROC_PLAYLISTS).resizable();
		},

		removePlaylist: function(name) {
			if (!name) {
				name = $('#saved-playlists .items').val();
			}
			if (name != '_new') {
				var url = PROC_PLAYLISTS + '/' + name;
				$.ajax({
					type: 'DELETE',
					url: url,
					success: function() {
						Playlist.loadPlaylists();
					},
					error: function () {
						alert('Unable to delete playlist [' + name + ']');
					}
				});
			}
		},

		removeItem: function(idx) {
			$('[id="playlist-item-' + idx + '"]').remove();
		},

		/**
		 * Saves the current playlist.
		 */
		save: function(name) {
			if (!name) {
				name = $('#saved-playlists .items :selected').val();
			}
			if (name == '_new') {
				name = '';
				while (name == '') {
					name = prompt("Please provide a name for this playlist.");
				}
			}
			if (name != null) {
				var playlist = JSON.stringify(Playlist._playlist);
				$.ajax({
					type: 'POST',
					url: PROC_PLAYLISTS + '/' + name,
					data: {'playlist': playlist},
					success: function() {
						Playlist.loadPlaylists();
					},
					error: function() {
					}
				});
			}
		}
	};
}();

$(document).ready(function() {
	Playlist.init();
});
