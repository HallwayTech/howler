/* global $ */
var Playlist = function() {
	return {
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

		nextId: function(overrideRepeat) {
			var currentPlayingId = Player.currentPlayingId();
			var nextId = false;
			if (currentPlayingId) {
				if (!overrideRepeat && Playlist.repeat() == 'SONG') {
					nextId = currentPlayingId.substring(currentPlayingId.lastIndexOf('-') + 1);
				} else if (!Playlist.random()) {
					var id = $('#' + currentPlayingId).next().attr('id');
					if (id) {
						nextId = id.substring(id.lastIndexOf('-') + 1);
					}
				}
			}
			if (!nextId) {
				if (Playlist.random()) {
					var randomId = Playlist.randomId();
					nextId = randomId;
				} else {
					var id = $('#playlist .items li:first').attr('id');
					if (id) {
						nextId = id.substring(id.lastIndexOf('-') + 1);
					}
				}
			}
			return nextId;
		},

		prevId: function(overrideRepeat) {
			var currentPlayingId = Player.currentPlayingId();
			var prevId = false;
			if (currentPlayingId) {
				if (!overrideRepeat && Playlist.repeat() == 'SONG') {
					prevId = currentPlayingId;
				} else {
					var id = $('#' + currentPlayingId).prev().attr('id');
					if (id) {
						prevId = id.substring(id.lastIndexOf('-') + 1);
					}
				}
			}
			// TODO if history is available, go back through it
			if (!prevId) {
				var id = $('#playlist .items li:last').attr('id');
				if (id) {
					prevId = id.substring(id.lastIndexOf('-') + 1);
				}
			}
			return prevId;
		},

		/**
		 * random() -- tells if the play order should be random
		 * 
		 * random(state) -- sets whether the play order should be random.
		 *
		 * @returns true if play should be random
		 *          false otherwise
		 */
		random: function(state) {
			var rand = $('#random');
			if (state != null) {
				rand.val(state);
			} else {
				return rand.val();
			}
		},

		/**
		 * randomId() -- Get a random item ID from the playlist.
		 * 
		 * @return A randomly selected playlist item's ID.
		 */
		randomId: function() {
			var playlist = $('#playlist .items li');
			var size = playlist.size();
			var pos = Math.floor(Math.random() * size);
			var id = $('#playlist .items li:eq(' + pos + ')').attr('id');
			var nextId = id.substring(id.lastIndexOf('-') + 1);
			return nextId;
		},

		/**
		 * removeItem(id) -- Remove an item from the playlist. The item is selected
		 *   by matching to the provided ID.
		 */
		removeItem: function(id) {
			$('#playlist-item-' + id).remove();
		},

		/**
		 * repeat() -- gets the repeat state
		 *
		 * repeat(val) -- sets the repeat state.
		 *   accepted values: NONE, SONG, LIST
		 */
		repeat: function(state) {
			var menu = $('#repeat-menu');
			if (state) {
				menu.val(state.toUpperCase());
			} else {
				return menu.val();
			}
		},

		/**
		 * Saves the current playlist.
		 */
		savePlaylist: function(name, rev) {
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

				var url = 'index.php/playlists/save/' + name;
				if (rev) {
					url += '/' + rev;
				} else {
					url += '/_new';
				}

				var playlist = JSON.stringify(ids);

				$.ajax({
					type: 'POST',
					url: url,
					data: {'playlist': playlist},
					success: function() {
						if (!rev) {
							Playlist.loadPlaylists();
						}
					},
					error: function() {
						alert('An error occurred saving the playlist.  Please try again later.');
					}
				});
			}
		},

		/**
		 * toggleSavedView() -- Show/hide the saved playlists area based on the
		 *   current view state of the area.
		 */
		toggleSavedView: function() {
			var anchor = $('#saved-playlists-actions a');
			var img = $('img', anchor);
			var savedPlaylists = $('#saved-playlists');
			if (anchor.hasClass('hide-button')) {
				img.attr('src', 'images/bullet_arrow_down.png');
				savedPlaylists.slideUp();
				anchor.removeClass('hide-button').addClass('show-button');
			} else if (anchor.hasClass('show-button')) {
				img.attr('src', 'images/bullet_arrow_up.png');
				savedPlaylists.slideDown();
				anchor.removeClass('show-button').addClass('hide-button');
			}
		}
	};
}();

$(document).ready(function() {
	Playlist.init();
});
