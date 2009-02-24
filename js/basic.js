var Basic = function() {
	return {
		init: function() {
			$.getJSON('playlists.php', function(json) {
				var output = Template.processTemplate('playlistsTemplate', json);
				$('#playlistsArea').html(output);
			});
		}
	}
}();

$(document).ready(function() {
	Basic.init();
});
