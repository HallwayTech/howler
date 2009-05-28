var Actions = function() {
	return {
		next: function() {
			Playlist.controls.next();
		},

		restartPlayer: function() {
			$('#actions-menu').attr('selectedIndex', 0);
			Player.restart();
		}
	}
}();
