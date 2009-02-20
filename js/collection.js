var Collection = function() {
	var _curOpts = {dir:'', search:''};
	var _lastOpts = {dir:'', search:''};
	var _curUrl = '';
	var _dataCache = {};
	var _alphaMenu = {items: ['#', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']};

	function _buildUrl(d, s) {
		var url = 'collection.php?';

		url += 'd=' + unescape(encodeURIComponent(d)) + '&';
		url += 's=' + unescape(encodeURIComponent(s));

		return url;
	}

	function _renderCurrentCollection(output) {
		var markup = Template.processTemplate('collectionTemplate', _dataCache[_curUrl]);
		output.html(markup);
		$('body').css('cursor', 'auto');
	}

	return {
		init: function() {
			// add the alphabetical navigation
			var menu = Template.processTemplate('alphaNavTemplate', _alphaMenu);
			$('#alphaNav').html(menu);
		},

		goBack: function() {
			Collection.view({'dir':-1});
		},

		view: function(options) {
			$('body').css('cursor', 'wait');
			var output = $('#output');
			output.html('Loading...');

			var d = '';
			var s = '';
			// check for a directory first.  search should override any previous
			// set dirs but only if a directory is not currently being requested
			if (options.dir) {
				if (options.dir == -1) {
					d = _lastOpts['dir'];
				} else {
					d = _dataCache[_curUrl].d[options.dir].d;
				}
				s = _lastOpts['search'];
			} else if (options.search) {
				// if the current 'search' == the previous search, clear the current search
				s = options.search;
			}
			_lastOpts = _curOpts;
			if (_curOpts['search'] == _lastOpts['search'] && _curOpts['dir'] && _lastOpts['dir']) {
				_lastOpts['search'] = '';
			}

			_curOpts['dir'] = (d != '') ? d.substring(0, d.lastIndexOf("/")) : '';
			_curOpts['search'] = s;

			_curUrl = _buildUrl(d, s);
			// if the output isn't available in cache, retrieve it from the server
			if (_dataCache[_curUrl]) {
				_renderCurrentCollection(output);
			} else {
				Collection.refresh();
			}
		},

		addSong: function(idx) {
			// escape problem characters
			// the file name is stored in the href field which will automatically
			// escape some characters so we have to be selective here and not use
			// escape(..)
			var meta = _dataCache[_curUrl].f[idx];
			var file = meta.p;

//			alert('addSong: ' + file);

			var item = {'file':decodeURIComponent(escape(file))};
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

		addAll: function() {
			Playlist.holdRefresh();
			$('.fileAdd').each(function(i) {
				this.onclick();
			})
			Playlist.allowRefresh(true);
		},

		refresh: function() {
			$.getJSON(_curUrl, function(data, textStatus) {
				_dataCache[_curUrl] = data;
				// get template and merge with data
				_renderCurrentCollection($('#output'));
			});
		}
	}
}();

$(document).ready(function() {
	Collection.init();
});
