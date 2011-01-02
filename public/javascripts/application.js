// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults
var howler = howler || {}

howler.init = function() {
    $(window).hashchange(howler.hashchange)
    
    // trigger a hashchange event to get things started as suggested by the BBQ
    // documentation
    $(window).hashchange()
}

howler.hashchange = function() {
    var params = $.deparam.fragment()
    if (location.hash.indexOf('#findBy') === 0) {
        howler.findBy(params['findBy'], params['update'])
    } else if (location.hash.indexOf('#play') === 0) {
        howler.player.play(params['play'])
//    } else if (location.hash.indexOf('#listBy') === 0) {
//        howler.listBy(params['listBy'], params['update'])
    }
}

howler.findBy = function(params, update) {
    if (params && update) {
        $.get('entry/find_by', params, function(data) {
            $(update).html(data)
        })
    }
}

howler.listBy = function(type, update) {
    if (type && update) {
        $.get('entry/list_by', {'type': type}, function(data) {
            $(update).html(data)
        })
    }
}
