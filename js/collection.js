var Collection = function() {
	var _history = [];
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
			_curPath = [s];
			_curUrl = 'search.php/' + encodeURIComponent(s);
			_history = [_curUrl];

			// if the output isn't available in cache, retrieve it from the server
			if (_dataCache[_curUrl]) {
				_renderCurrentCollection(output_area);
			} else {
				Collection.refresh();
			}
		},

		view: function(dirIdx) {
			if (dirIdx) {
				$('body').css('cursor', 'wait');
				var output_area = $('#output');
				output_area.html('Loading...');

				// determine directory to look up
				// track the last directory looked up
				if (dirIdx == -1) {
					_history.pop();
					_curUrl = _history[_history.length - 1];
					if (_curUrl) {
						_curPath = _curUrl.substring(_curUrl.indexOf('=') + 1);
					}
				} else {
					_curPath = _dataCache[_curUrl].d[dirIdx].d;
					_curUrl = 'collection.php/' + encodeURIComponent(_curPath);
					_history.push(_curUrl);
				}
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

		addAll: function() {
			Playlist.holdRefresh();
			$('.fileAdd').each(function(i) {
				this.onclick();
			})
			Playlist.allowRefresh(true);
		},

		refresh: function() {
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
					error: function(request, textStatus, errorThrown) {
						alert('Unable to retrieve collection [' + textStatus + ': ' + errorThrown + ']');
					}
				});
			}
		}
	}
}();

$(document).ready(function() {
	Collection.init();
});
