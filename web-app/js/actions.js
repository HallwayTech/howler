var Actions = function() {
	return {
		restartPlayer: function() {
			$('#actions-menu').attr('selectedIndex', 0);
			Player.restart();
		}
	}
}();
