var Collection = function() {
	var history = [];
	var path = '';
	var cache = {};
	var alphaMenu = {items: ['#', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']};

	return {
		/**
		 * Add a song to the playlist.
		 *
		 * @param idx the index of the song to add.
		 */
		addSong: function(idx) {
			url = history[history.length - 1];
			var meta = cache[url].f[idx];
			var file = meta.p;

			var item = {'file':file};
			item.album = meta.l;
			if (meta.a) {
				item.artist = meta.a;
			}
			if (meta.t) {
				item.title = meta.t;
			} else {
				var lastSlash = file.lastIndexOf('/');
				var lastDot = file.lastIndexOf('.');
				var songTitle = file.substring(lastSlash + 1, lastDot);
				item.title = songTitle;
			}
			Playlist.addItem(item);
		},

		/**
		 * Add all songs shown in the collection view to the playlist.  Does not recurse subdirectories.
		 */
		addAll: function() {
			Playlist.holdRefresh();
			$('.fileAdd').each(function(i) {
				this.onclick();
			});
			Playlist.allowRefresh(true);
		},

		/**
		 * Show the previous collection view.
		 */
		back: function() {
			Collection.view(-1);
		},

		/**
		 * Initialization
		 */
		init: function() {
			// add the alphabetical navigation
			var menu = Template.processTemplate('alphaNavTemplate', alphaMenu);
			$('#alphaNav').html(menu);
		},

		/**
		 * Render the data found at a url.  Cached data is used if found.
		 *
		 * @param url the url to check for data.
		 */
		renderUrl: function(url) {
			// if the output isn't available in cache, retrieve it from the server
			if (!url || cache[url]) {
				var data = cache[url];
				var markup = Template.processTemplate('collectionTemplate', data);
				$('#output').html(markup);
				Collection.wait(true);
			} else if (url) {
				Collection.update(url);
			}
		},

		refresh: function() {
<<<<<<< HEAD:js/collection.js
			if (_curUrl) {
				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: _curUrl,
					success: function(json, textStatus) {
						json['cp'] = _curPath;
						_dataCache[_curUrl] = json;
						// get template and merge with data
						_renderCurrentCollection($('#output'));
					},
					error: function() {
						alert('Unable to retrieve collection.');
					}
				});
			}
=======
			var url = history[history.length - 1];
			Collection.update(url);
		},

		/**
		 * Search for entries
		 *
		 * @param search the search term to use.  entries starting with this term are returned.
		 */
		search: function(search) {
			Collection.wait();
			$('#output').html('Loading...');

			path = search;
			url = 'search.php/' + encodeURIComponent(search);
			history = [url];

			Collection.renderUrl(url);
		},

		/**
		 * Update the data found at a URL
		 *
		 * @param url the url to get information from.
		 */
		update: function(url) {
			if (url) {
				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: url,
					success: function(json, textStatus) {
						json['cp'] = path;
						cache[url] = json;
						Collection.renderUrl(url);
					},
					error: function(request, textStatus, errorThrown) {
						alert('Unable to retrieve collection [' + textStatus + ': ' + errorThrown + ']');
					}
				});
			}
		},

		/**
		 * View a directory 
		 *
		 * @param dirIdx the index to show relative to the current url.
		 */
		view: function(dirIdx) {
			var url = '';

			// only work if there is a provided index
			if (dirIdx) {
				// put out the wait sign
				Collection.wait();

				// determine directory to look up
				// track the last directory looked up
				if (dirIdx == -1) {
					// pop off the current url
					history.pop();

					// use the previous url as the current url
					url = history[history.length - 1];
				} else {
					// get the current url
					url = history[history.length - 1];

					// get the requested dir that's nested at the current url
					path = cache[url].d[dirIdx].d;

					// build the new url
					url = 'collection.php/' + encodeURIComponent(path);

					// add the new url to the history
					history.push(url);
				}
			}

			// render the url
			Collection.renderUrl(url);
		},

		/**
		 * Show/hide the wait icon.
		 *
		 * @param hide true to hide the wait icon, false to show
		 */
		wait: function(hide) {
			if (hide) {
				$('body').css('cursor', 'auto');
			} else {
				$('body').css('cursor', 'wait');
			}
>>>>>>> local_trunk:js/collection.js
		}
	}
}();

$(document).ready(function() {
	Collection.init();
});
