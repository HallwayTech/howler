jQuery.extend({
	put: function( url, data, callback, type ) {
		if ( jQuery.isFunction( data ) ) {
			callback = data;
			data = {};
		}

		return jQuery.ajax({
			type: "PUT",
			url: url,
			data: data,
			success: callback,
			dataType: type
		});
	},

	delete_: function( url, data, callback, type ) {
		if ( jQuery.isFunction( data ) ) {
			callback = data;
			data = {};
		}

		return jQuery.ajax({
			type: "DELETE",
			url: url,
			data: data,
			success: callback,
			dataType: type
		});
	}
});
