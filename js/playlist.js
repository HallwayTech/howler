var Playlist = function() {
	var _performRefresh = true;
	var PROC_PLAYLISTS = 'playlists.php';
	var PROC_LOAD_FILE = 'loadfile.php';

	return {
		_playlist: [],
		_playingIdx: -1,

		/**
		 * Initialization of the playlist
		 */
		init: function() {
			Playlist.reload();
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
//			alert('addItem: ' + item.file);
			item.file = PROC_LOAD_FILE + '?d=' + encodeURIComponent(item.file);
			Playlist._playlist.push(item);
			Playlist.refresh(true);
		},

		allowRefresh: function(refreshNow) {
			_performRefresh = true;
			if (refreshNow) {
				Playlist.refresh(true);
			}
		},

		clear: function() {
			$('#savedPlaylistsItems').attr('selectedIndex', '0');
			Playlist._playlist = [];
			Playlist._playingIdx = -1;
			Playlist.refresh();
		},

		highlightPlaying: function() {
			$('.nowPlaying').removeClass('nowPlaying');
			$('#playlistItem_' + Playlist._playingIdx).addClass('nowPlaying');
		},

		holdRefresh: function() {
			_performRefresh = false;
		},

		load: function() {
			var name = $('#savedPlaylistsItems').val();
			if (name == '_new') {
				Playlist.clear();
			} else {
				var url = PROC_PLAYLISTS + '/' + encodeURIComponent(name);
				$.get(url, function(data, textStatus) {
					Playlist._playlist = JSON.parse(data);
					Playlist.refresh();
				});
			}
		},

		refresh: function(highlightPlaying) {
			if (_performRefresh) {
				var output = Template.processTemplate('playlistTemplate', {'items':Playlist._playlist});
				$('#playlist').html(output);
				$('#playlistItems').sortable({
					axis: 'y',
					opacity: .75,
					update: function(ev, ui) {
						var newPlaylist = [];
						var visList = $('#playlistItems').sortable('toArray');
						for (var _i_ = 0; _i_ < visList.length; _i_++) {
							var visItem = visList[_i_];
							if ($('#' + visItem).hasClass('nowPlaying')) {
								Playlist._playingIdx = _i_;
							}
							var visId = visItem.substring(visItem.indexOf('_') + 1);
							newPlaylist[_i_] = Playlist._playlist[visId];
						}
						Playlist._playlist = newPlaylist;
						Playlist.refresh(true);
					}
				});
				if (highlightPlaying) {
					Playlist.highlightPlaying();
				}
			}
		},

		reload: function() {
			$.getJSON(PROC_PLAYLISTS, function(data) {
				var output = Template.processTemplate('savedPlaylistsTemplate', data);
				$('#savedPlaylists').html(output);
			});
		},

		remove: function() {
			var name = $('#savedPlaylistsItems').val();
			if (name != '_new') {
				var url = PROC_PLAYLISTS + '/' + name;
				$.delete_(url, function() {
					Playlist.reload();
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
		save: function() {
			var name = $('#savedPlaylistsItems :selected').val();
			if (name == '_new') {
				name = '';
				while (name == '') {
					name = prompt("Please provide a name for this playlist");
				}
			}
			if (name != null) {
				var playlist = JSON.stringify(Playlist._playlist);
				$.post(PROC_PLAYLISTS + '/' + name, {'playlist': playlist}, function() {
					Playlist.reload();
				});
			}
		}
	}
}();

$(document).ready(function() {
	Playlist.init();
});
