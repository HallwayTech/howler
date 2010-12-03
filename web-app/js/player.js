/* global $ */

/**
 * The player object that contains all controls, trackers and playlist functionality.
 */
var player = {}
player.currentId = null
player.state = "unloaded"

/**
 * Initialization function for the player
 */
player.init = function() {
	$('#jplayer_previous').click(player.previous);
	$('#jplayer_next').click(player.next);

	$("#player").jPlayer("onSoundComplete", player.next)

	state = "ready"
}

/**
 * Add an entry to the playlist.
 */
player.add = function(id, title) {
	var imgLink$ = $('.' + id + ' .play').clone()
	var li = $("<li>", {"class": id}).append(imgLink$).append(title)
	$('#jplayer_playlist ul').append(li)
}

/**
 * Play the next entry.
 */
player.next = function() {
	var nextId = $("#" + player.currentId).next().attr("id")

	if (!nextId) {
		nextId = $("#entries .entry:first-child").attr("id")
	}

	player.play(nextId)
}

/**
 * Play a specific entry.
 */
player.play = function(id) {
	var player$ = $("#player")

	if (id == player.currentId) {
		// if selected entry is playing, just toggle play/pause
		var isPlaying = player$.jPlayer("getData", "diag.isPlaying")
		if (isPlaying) {
			player$.jPlayer("pause")
		} else {
			player$.jPlayer("play")
		}
	} else {
		// de-highlight current playing entry
		$('.now-playing').removeClass('now-playing')

		// set the marquee title to the selected entry title
		var entry$ = $("#" + id)
		$("#marquee").text($(entry$).text())

		// highlight the selected entry
		entry$.addClass('now-playing')

		// play the music!
		player$.jPlayer("setFile", "entry/stream/" + id).jPlayer("play")

		// track the current ID
		player.currentId = id
	}
}

/**
 * Play the previous entry.
 */
player.previous = function() {
	var prevId = $("#" + player.currentId).prev().attr("id")

	if (!prevId) {
		prevId = $("#entries .entry:last-child").attr("id")
	}

	player.play(prevId)
}
