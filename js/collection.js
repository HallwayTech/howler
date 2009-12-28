var Collection = function() {
	var history = [];
	var path = '';
	var cache = {};

	return {
		download: function(id) {
			window.open('index.php/files/read/' + id, 'Download');
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
		},

		/**
		 * View a directory 
		 *
		 * @param dirIdx the index to show relative to the current url.
		 */
		view: function(parentId) {
			Collection._wait();
			url = 'index.php/collections/byParent/' + parentId;
			$('#listingContainer').load(url);
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
