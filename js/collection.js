var Collection = function() {
	var _curDir = 0;
	var _lastDir = 0;
	var _curPath = '';
	var _curUrl = '';
	var _dataCache = {};
	var _alphaMenu = {items: ['#', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']};

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

		back: function() {
			Collection.view(-1);
		},

		search: function(search) {
			$('body').css('cursor', 'wait');
			var output_area = $('#output');
			output_area.html('Loading...');

			// if the current 'search' == the previous search, clear the current search
			var s = encodeURIComponent(search);
			_curPath = s;
			_curDir = s;
			_curUrl = 'search.php?s=' + unescape(encodeURIComponent(s));

			// if the output isn't available in cache, retrieve it from the server
			if (_dataCache[_curUrl]) {
				_renderCurrentCollection(output_area);
			} else {
				Collection.refresh();
			}
		},

		view: function(dir) {
			$('body').css('cursor', 'wait');
			var output_area = $('#output');
			output_area.html('Loading...');

			var d = '';
			// check for a directory first.  search should override any previous
			// set dirs but only if a directory is not currently being requested
			if (dir) {
				if (dir == -1) {
					d = _lastDir;
				} else {
					d = _dataCache[_curUrl].d[dir].d;
				}
				_lastDir = _curDir;
				_curDir = (d != '') ? d.substring(0, d.lastIndexOf('/')) : '';
				_curPath = d;
				_curUrl = 'collection.php?d=' + unescape(encodeURIComponent(d));
				// if the output isn't available in cache, retrieve it from the server
				if (_dataCache[_curUrl]) {
					_renderCurrentCollection(output_area);
				} else {
					Collection.refresh();
				}
			}
		},

		addSong: function(idx) {
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
	}
}();

$(document).ready(function() {
	Collection.init();
});
