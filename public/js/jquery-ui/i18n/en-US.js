jQuery(function($) {
	$.datepicker.regional["en"] = {
        "altFormat": "M d, yy",
        "dateFormat": "M d, yy",
    };

	$.datepicker.setDefaults($.datepicker.regional["en"]);
});