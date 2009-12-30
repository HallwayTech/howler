var Collection = function() {
	var history = [];
	var path = '';
	var cache = {};

	/**
	 * Hide the wait icon.
	 */
	function _done() {
		$('body').css('cursor', 'auto');
	}

	/**
	 * Show the wait icon.
	 */
	function _wait() {
		$('body').css('cursor', 'wait');
	}

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
			_wait();
			$('#output').html('Loading...');

			path = search;
			url = 'index.php/collections/find/' + encodeURIComponent(search);
			$('#collection-container').load(url);
			_done();
		},

		/**
		 * View a directory 
		 *
		 * @param dirIdx the index to show relative to the current url.
		 */
		view: function(parentId) {
			_wait();
			url = 'index.php/collections/byParent/' + parentId;
			$('#collection-container').load(url);
			_done();
		}
	}
}();
