/* global $ */
var Playlist = function() {
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
		 * Add an item to the current loaded playlist
		 *
		 * @param item The ID of the item to add to the playlist.
		 */
		addItem: function(id) {
			var url = 'index.php/playlists/addEntry/' + id;
			$.get(url, function(data) {
				$('#playlist .items').append(data);
			});
		},

		addParent: function(parentId) {
			var url = 'index.php/playlists/addParent/' + parentId;
			$.get(url, function(data) {
				$('#playlist .items').append(data);
			});
		},

		clear: function() {
			$('#playlist .items').empty();
		},

		currentPlayingId: function() {
			return $('.now-playing').attr('id');
			/*
			var id = false;
			var length = $('.now-playing').length;
			if (length <= 0) {
				id = $('.now-playing').attr('id');
			}
			return id;
			*/
		},

		highlight: function(id) {
			// clear current highlighted item
			$('.now-playing').removeClass('now-playing');

			if (id) {
				// highlight current song
				$('#playlist-item-' + id).addClass('now-playing');

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
				var url = 'index.php/playlists/read/' + encodeURIComponent(name);
				$('#playlist .items').load(url, function() {
					$(this).sortable({
						axis: 'y',
						opacity: .75
					});
				});
			} else {
				alert('No playlist to load.');
			}
		},

		loadPlaylists: function() {
			$('#saved-playlists').load('index.php/playlists').resizable();
		},

		deletePlaylist: function(id, rev) {
			if (id && id != '_new') {
				var url = 'index.php/playlists/delete/' + id + '/' + rev;
				$.ajax({
					type: 'DELETE',
					url: url,
					success: function(data, textStatus) {
						Playlist.loadPlaylists();
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert('Unable to delete playlist [' + textStatus + ']');
					}
				});
			}
		},

		removeItem: function(id) {
			$('#playlist-item-' + id).remove();
		},

		/**
		 * Saves the current playlist.
		 */
		save: function(name) {
			if (!name || name == '_new') {
				name = '';
				while (name == '') {
					name = prompt("Please provide a name for this playlist.");
				}
			}
			if (name) {
				ids = [];
				$('.playlist-item').each(function (idx) {
						var id = this.id.substring(this.id.lastIndexOf('-') + 1);
						ids.push(id);
					}
				);
				
				var playlist = JSON.stringify(ids);
				$.ajax({
					type: 'POST',
					url: 'index.php/playlists/save/' + name,
					data: {'playlist': playlist},
					success: function() {
						Playlist.loadPlaylists();
					},
					error: function() {
						alert("An error occurred saving the playlist.  Please try again later.");
					}
				});
			}
		},

		toggleSavedView: function() {
			$("#saved-playlists").toggle("normal");
			$("#saved-playlists-actions .left").toggle("normal");
		}
	};
}();

$(document).ready(function() {
	Playlist.init();
});
