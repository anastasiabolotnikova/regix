var old_ids = [];
var first = true;

function informAboutNewEvents(new_ids, new_descriptions, times_f, times_t) {
	
	popup = $("#popup_top_tpl").clone();
	popup.removeAttr('id');
	popup.empty();
	event_tpl = $("#popup_top_tpl .event");
	
	var needToShow = false;
	
	for (i = 0; i < new_ids.length; ++i) {

		if($.inArray(new_ids[i], old_ids) == -1) {
			old_ids.push(new_ids[i]);
			
			var event_cur = event_tpl.clone();
			$(event_cur).find(".time").html(times_f[i]+" — "+times_t[i]);
			$(event_cur).find(".description").html(new_descriptions[i]);
			popup.append(event_cur);
			
			needToShow = true;
		}
	}
	
	if (first) {
		first = false;
		return;
	}
	
	if(needToShow) {
		$("#header").append(popup);
		popup.show("slow");
		
		new function(popup) {
			setTimeout(function () {
				popup.hide("slow");
			}, 5000);
		}(popup);
		
	}
}

function getLatestEvents() {
	$.ajax({
		url: '/latest/events',
		success: function(data) {
			xmlEvDoc = $.parseXML(data);
			xmlEv = $(xmlEvDoc);
			
			var ids = [];
			var descriptions = [];
			var times_f = [];
			var times_t = [];
			
			xmlEv.find("event").each(
					function (idx, value) {
						ids.push($(value).find("id").text());
						descriptions.push($(value).find("description").text());
						times_f.push($(value).find("from").text());
						times_t.push($(value).find("to").text());
					});
			
			informAboutNewEvents(ids, descriptions, times_f, times_t);
			
		}
	});
}

var updater = function ($timeout) {
	getLatestEvents();
	setTimeout(function () {
		updater($timeout);
	}, $timeout);
};
