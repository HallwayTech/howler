var Playlist = function() {
	var performRefresh = true;
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
			$('#saved-playlists-container').resizable();
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
			item.file = PROC_LOAD_FILE + '?d=' + encodeURIComponent(item.file);
			Playlist._playlist.push(item);
			Playlist.refresh(true);
		},

		allowRefresh: function(refreshNow) {
			performRefresh = true;
			if (refreshNow) {
				Playlist.refresh(true);
			}
		},

		clear: function() {
			$('#saved-playlists .items').attr('selectedIndex', '0');
			Playlist._playlist = [];
			Playlist._playingIdx = -1;
			Playlist.refresh();
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

		load: function(name) {
			if (!name) {
				name = $('#saved-playlists .items').val();
			}
			if (name == '_new') {
				Playlist.clear();
			} else {
				var url = PROC_PLAYLISTS + '/' + encodeURIComponent(name);
				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: url,
					success: function(json, textStatus) {
						Playlist._playlist = json;
						Playlist.refresh();
					},
					error: function(data, textStatus) {
						alert('Unable to load playlist [' + name + ']');
					}
				});
			}
		},

		refresh: function(highlightPlaying) {
			if (performRefresh) {
				var output = Template.processTemplate('playlistTemplate', {'items':Playlist._playlist});
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

		reload: function() {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: PROC_PLAYLISTS,
				success: function(json, textStatus) {
					var output = Template.processTemplate('savedPlaylistsTemplate', json);
					$('#saved-playlists').html(output);
				},
				error: function(data, textStatus) {
					alert('Unable to get playlists.');
				}
			});
		},

		remove: function(name) {
			if (!name) {
				name = $('#saved-playlists .items').val();
			}
			if (name != '_new') {
				var url = PROC_PLAYLISTS + '/' + name;
				$.ajax({
					type: 'DELETE',
					url: url,
					success: function() {
						Playlist.reload();
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
						Playlist.reload();
					},
					error: function() {
					}
				});
			}
		},

		updateSortable: function(ev, ui) {
			var newPlaylist = [];
			var visList = $('#playlist .items').sortable('toArray');
			for (var _i_ = 0; _i_ < visList.length; _i_++) {
				var visItem = visList[_i_];
				if ($('#' + visItem).hasClass('now-playing')) {
					Playlist._playingIdx = _i_;
				}
				var visId = visItem.substring(visItem.indexOf('_') + 1);
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
