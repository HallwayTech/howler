/* global $ */

/**
 * The player object that contains all controls, trackers and playlist functionality.
 */
var howler = howler || {}
howler.player = howler.player || {}
howler.player.currentId = null
howler.player.state = 'unloaded'

/**
 * Initialization function for the player
 */
howler.player.init = function() {
    $('#jplayer_previous').click(howler.player.previous);
    $('#jplayer_next').click(howler.player.next);

    $('#player').jPlayer('onSoundComplete', howler.player.next)

    howler.player.state = 'ready'
}

/**
 * Add an entry to the playlist.
 */
howler.player.add = function(id, title) {
    var imgLink$ = $('.' + id + ' .play').clone()
    var li = $('<li>', {
        'class': id
    }).append(imgLink$).append(title)
    $('#jplayer_playlist ul').append(li)
}

/**
 * Play the next entry.
 */
howler.player.next = function() {
    var nextId = $('#' + howler.player.currentId).next().attr('id')

    if (!nextId) {
        nextId = $('#entries .entry:first-child').attr('id')
    }

    howler.player.play(nextId)
}

/**
 * Play a specific entry.
 */
howler.player.play = function(id) {
    var player$ = $('#player')

    if (id == howler.player.currentId) {
        // if selected entry is playing, just toggle play/pause
        var isPlaying = player$.jPlayer('getData', 'diag.isPlaying')
        if (isPlaying) {
            player$.jPlayer('pause')
        } else {
            player$.jPlayer('play')
        }
    } else {
        // unhighlight current playing entry
        $('.now-playing').removeClass('now-playing')

        // set the marquee title to the selected entry title
        var entry$ = $('#' + id)
        $('#marquee').text($(entry$).text())

        // highlight the selected entry
        entry$.addClass('now-playing')

        // play the music!
        player$.jPlayer('setFile', 'entry/stream/' + id).jPlayer('play')

        // track the current ID
        howler.player.currentId = id
    }
}

/**
 * Play the previous entry.
 */
howler.player.previous = function() {
    var prevId = $('#' + howler.player.currentId).prev().attr('id')

    if (!prevId) {
        prevId = $('#entries .entry:last-child').attr('id')
    }

    howler.player.play(prevId)
}
