// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults
var howler = howler || {}

howler.update = function(type, update) {
    $.get('/entry/list_by', {'type': type}, function(data) {
        $(update).html(data)
    })
}
