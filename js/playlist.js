/* global $ */
var Playlist = function() {
	var performRefresh = true;
	var PROC_PLAYLISTS = 'index.php/playlists';
	var PROC_LOAD_FILE = 'index.php/files/read/';

	return {
		_playlist: [],
		_playingIdx: -1,

		/**
		 * Initialization of the playlist
		 */
		init: function() {
			Playlist.loadPlaylists();
			$('#saved-playlists').resizable();
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
			Playlist._playlist.push(item);
//			Playlist.refresh(true);
		},

		allowRefresh: function(refreshNow) {
			performRefresh = true;
			if (refreshNow) {
//				Playlist.refresh(true);
			}
		},

		clear: function() {
			$('#saved-playlists .items').attr('selectedIndex', '0');
			Playlist._playlist = [];
			Playlist._playingIdx = -1;
//			Playlist.refresh();
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

		holdRefresh: function() {
			performRefresh = false;
		},

		loadPlaylist: function(name) {
			if (!name) {
				name = $('#saved-playlists .items').val();
			}
			if (name == '_new') {
				Playlist.clear();
			} else {
				var url = PROC_PLAYLISTS + '/read/' + encodeURIComponent(name);
				$('#playlist').load(url);
			}
		},

		refresh: function(highlightPlaying) {
			if (performRefresh) {
//				var output = Template.processTemplate('playlistTemplate', {'items':Playlist._playlist});
				$('#playlist').html(output);
				$('#playlist .items').sortable({
					axis: 'y',
					opacity: .75,
					update: Playlist.updateSortable
				});
				if (highlightPlaying) {
					Playlist.highlightPlaying();
				}
			}
		},

		loadPlaylists: function() {
			$('#saved-playlists').load(PROC_PLAYLISTS);
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
			if (idx >= 0 && idx < Playlist._playlist.length) {
				var highlight = true;
				// don't highlight on refresh if removing current playing
				// item.
				if (idx == Playlist._playingIdx) {
					highlight = false;
				}

				// adjust Playlist._playingIdx if the removed index <= to it.
				if (idx <= Playlist._playingIdx) {
					Playlist._playingIdx--;
				}

				Playlist._playlist.splice(idx, 1);
				Playlist.refresh(highlight);
			}
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
		},

		/**
		 * Processing function when the playlist is rearranged.
		 */
		updateSortable: function(ev, ui) {
			var newPlaylist = [];
			var visList = $('#playlist .items').sortable('toArray');
			for (var _i_ = 0; _i_ < visList.length; _i_++) {
				var visItem = visList[_i_];
				if ($('#' + visItem).hasClass('now-playing')) {
					Playlist._playingIdx = _i_;
				}
				var visId = visItem.substring(visItem.lastIndexOf('-') + 1);
				newPlaylist[_i_] = Playlist._playlist[visId];
			}
			Playlist._playlist = newPlaylist;
			Playlist.refresh(true);
		}
	}
}();

$(document).ready(function() {
	Playlist.init();
});
