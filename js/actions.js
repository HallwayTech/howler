var Actions = function() {
	return {
		next: function() {
			alert(Player.nextIndex());
		},

		restartPlayer: function() {
			$('#actions-menu').attr('selectedIndex', 0);
			Player.restart();
		}
	}
}();
