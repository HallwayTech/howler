var Collection = function() {
	var history = [];
	var path = '';
	var cache = {};

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
		 * Search for entries
		 *
		 * @param search the search term to use.  entries starting with this term are returned.
		 */
		search: function(search) {
			Collection._wait();
			$('#output').html('Loading...');

			path = search;
			url = 'index.php/collections/find/' + encodeURIComponent(search);
			$('#listingContainer').load(url);
			Collection._done();
			history = [url];
		},

		/**
		 * View a directory 
		 *
		 * @param dirIdx the index to show relative to the current url.
		 */
		view: function(dirIdx) {
			Collection._wait();
			$('#listingContainer').load('index.php/collections/read/' + encodeURI(dirIdx));
			Collection._done();
		},

		/**
		 * Hide the wait icon.
		 */
		_done: function() {
			$('body').css('cursor', 'auto');
		},

		/**
		 * Show the wait icon.
		 */
		_wait: function() {
			$('body').css('cursor', 'wait');
		}
	}
}();
