var old_ids = [];
var first = true;

function informAboutNewEvents(new_ids, new_descriptions, new_times) {
	
	popup = $("#popup_top_tpl").clone();
	popup.removeAttr('id');
	popup.empty();
	event_tpl = $("#popup_top_tpl .event");
	
	var needToShow = false;
	
	for (i = 0; i < new_ids.length; ++i) {

		if($.inArray(new_ids[i], old_ids) == -1) {
			old_ids.push(new_ids[i]);
			
			var event_cur = event_tpl.clone();
			$(event_cur).find(".time").html(new_times[i]);
			$(event_cur).find(".description").html(new_descriptions[i]);
			popup.append(event_cur);
			
			needToShow = true;
		}
	}
	
	if (first) {
		first = false;
		//return;
	}
	
	if(needToShow) {
		$("#container").append(popup);
		popup.show("slow");
		
		function(popup) {
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
			var times = [];
			
			xmlEv.find("event").each(
					function (idx, value) {
						ids.push($(value).find("id").text());
						descriptions.push($(value).find("description").text());
						times.push($(value).find("from").text());
					});
			
			informAboutNewEvents(ids, descriptions, times);
			
		}
	});
}

function updater($timeout) {
	getLatestEvents();
	setTimeout(function () {
		updater($timeout);
	}, $timeout);
}
