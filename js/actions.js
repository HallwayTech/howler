var Actions = function() {
	return {
		restartPlayer: function() {
			$('#actions_menu').attr('selectedIndex', 0);
			Player.restart();
		}
	}
}();
