/* global $ */
var Playlist = function() {
	return {
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

		/**
		 * Add all items associated to a parent ID.
		 * 
		 * @param parentId The parent ID to load all children of.
		 */
		addParent: function(parentId) {
			var url = 'index.php/playlists/addParent/' + parentId;
			$.get(url, function(data) {
				$('#playlist .items').append(data);
			});
		},

		/**
		 * Clear all items from the current playlist. Does not affect the state of
		 * any saved playlists.
		 */
		clear: function() {
			$('#playlist .items').empty();
		},

		/**
		 * Delete a saved playlist.
		 */
		deletePlaylist: function(id, rev) {
			if (id && id != '_new') {
				if (confirm('Are you sure you want to delete this playlist?')) {
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
			}
		},

		/**
		 * Highlight an item in the current playlist using the ID of the item.
		 */
		highlight: function(id) {
			// clear current highlighted item
			$('.now-playing .controls .play').removeClass('now-playing').attr('src', 'images/control_play_blue.png');

			if (id) {
				// highlight current song
				nowPlaying = $('#playlist-item-' + id).addClass('now-playing');

				// calculate top position and scroll to it
				var playlistTop = $('#playlist').position()['top'];
				var nowPlayingTop = nowPlaying.position()['top'];
				var topDiff = nowPlayingTop - playlistTop;
				var scrollTop = $('#playlist').scrollTop();
				$('.controls .play', nowPlaying).attr('src', 'images/control_pause_blue.png');

				$('#playlist').scrollTop(scrollTop + topDiff);
			}
		},

		highlightBlur: function() {
			$('.now-playing .controls .play').attr('src', 'images/control_play_blue.png');
		},

		highlightFocus: function() {
			$('.now-playing .controls .play').attr('src', 'images/control_pause_blue.png');
		},

		/**
		 * Initialization of the playlist
		 */
		init: function() {
			Playlist.loadPlaylists();
		},

		/**
		 * Load a saved playlist.
		 * 
		 * @param id The ID of the playlist to load.
		 */
		loadPlaylist: function(id) {
			if (id) {
				var url = 'index.php/playlists/read/' + id;
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

		/**
		 * Load the list of all playlists available to the user.
		 */
		loadPlaylists: function() {
			$('#saved-playlists .list').load('index.php/playlists');
//			$('#saved-playlists .list').resizable();
		},

		/**
		 * Get the ID of the next item in the playlist that should be played. Will
		 * select a random ID, if the random checkbox is ticked.
		 * 
		 * @param overrideRepeat(optional) Whether to override a repeat state of
		 *        'SONG'.
		 */
		nextId: function(overrideRepeat) {
			var currentPlayingId = Player.currentPlayingId();
			var nextId = false;
			if (currentPlayingId
					&& ((!overrideRepeat && Playlist.repeat() == 'SONG')
					|| !Playlist.random())) {
				nextId = currentPlayingId;
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

		/**
		 * Get the ID of the previous item in the playlist that should be played.
		 * Will not select a random ID, if the random checkbox is ticked.
		 * 
		 * @param overrideRepeat(optional) Whether to override a repeat state of
		 *        'SONG'.
		 */
		prevId: function(overrideRepeat) {
			var currentPlayingId = Player.currentPlayingId();
			var prevId = false;
			if (currentPlayingId
					&& (!overrideRepeat && Playlist.repeat() == 'SONG')) {
				prevId = currentPlayingId;
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
		 * Tells if the play order should be random, if state is not provided.
		 * Sets whether the play order should be random, if state is provided.
		 *
		 * @returns true if play should be random
		 *          false otherwise
		 */
		random: function(state) {
			var rand = $('#random');
			if (state != null) {
				rand.attr('checked', state);
			} else {
				return rand.attr('checked');
			}
		},

		/**
		 * Get the ID of a random item in the current playlist.
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
		 * Remove an item from the current playlist. The item is selected by matching
		 * to the provided ID.
		 * 
		 * @param id The ID of the item to remove from the playlist.
		 */
		removeItem: function(id) {
			$('#playlist-item-' + id).remove();
		},

		/**
		 * Gets the repeat state, if state is not provided.
		 * 
		 * Sets the repeat state, if state is provided.
		 * Expected values: NONE, SONG, LIST
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

				var playlist = '[' + ids.join(',') + ']';

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
		 * Show/hide the saved playlists area based on the current view state of the
		 * area.
		 */
		toggleSavedView: function() {
			var anchor = $('#saved-playlists-actions a');
			var img = $('img', anchor);
			var savedPlaylists = $('#saved-playlists');
			if (anchor.hasClass('hide-button')) {
				img.attr('src', 'images/bullet_arrow_down.png');
				savedPlaylists.slideUp('fast');
				anchor.removeClass('hide-button').addClass('show-button');
			} else if (anchor.hasClass('show-button')) {
				img.attr('src', 'images/bullet_arrow_up.png');
				savedPlaylists.slideDown('fast');
				anchor.removeClass('show-button').addClass('hide-button');
			}
		},
	};
}();

$(document).ready(function() {
	Playlist.init();
});
