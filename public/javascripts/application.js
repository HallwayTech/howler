// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults
$(document).ready(function() {
    $("#player").jPlayer({
        swfPath: 'javascripts',
        nativeSupport: true,
        customCssIds: false,
        ready: player.init
    })
    //<%= url_for(:controller => 'entry', :action => 'list_by') %>
    $.get('entry/list_by', {'type': 'artist'}, function(data) {
        $('#artists-list').html(data)
    })
    $.get('entry/list_by', {'type': 'album'}, function(data) {
        $('#albums-list').html(data)
    })
})
