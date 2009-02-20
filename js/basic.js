var Wii = function() {
	return {
		init: function() {
			$.getJSON('playlists.php', function(data) {
				var output = Template.processTemplate('playlistsTemplate', data);
				$('#playlistsArea').html(output);
			});
		}
	}
}();

$(document).ready(function() {
	Wii.init();
});
