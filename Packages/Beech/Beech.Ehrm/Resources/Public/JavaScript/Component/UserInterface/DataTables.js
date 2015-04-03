(function () {
	'use strict';

	$.extend($.fn.dataTableExt.oSort, {
		"date-nl-pre": function (a) {
			var dateTime = a.split(' ');
			var date = dateTime[0].split('-');
			var time = [0,0];
			if (dateTime[1] != undefined) {
				time = dateTime[1].split(':');
			}
			return (date[2] + date[1] + date[0] + time[0] + time[1]) * 1;
		},

		"date-nl-asc": function (a, b) {
			return ((a < b) ? -1 : ((a > b) ? 1 : 0));
		},

		"date-nl-desc": function (a, b) {
			return ((a < b) ? 1 : ((a > b) ? -1 : 0));
		}
	});
}).call(this);
