var old_ids = [];
var first = true;

function informAboutNewEvents(new_ids, new_descriptions) {
	
	var msg = "";
	
	for (i = 0; i < new_ids.length; ++i) {

		if($.inArray(new_ids[i], old_ids) == -1) {
			old_ids.push(new_ids[i]);
			msg += "<div>"+new_descriptions[i]+"</div>"
		}
	}
	
	if (first) {
		first = false;
		return;
	}
	
	if(msg.length > 0) {
		popup = $("#popup_top");
		popup.empty();
		popup.append(msg);
		popup.show("slow");
	} else {
		popup = $("#popup_top");
		popup.hide("slow");
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
			
			xmlEv.find("event").each(
					function (idx, value) {
						ids.push($(value).find("id").text());
						descriptions.push($(value).find("description").text());
					});
			
			informAboutNewEvents(ids, descriptions);
			
		}
	});
}

function updater($timeout) {
	getLatestEvents();
	setTimeout(function () {
		updater($timeout);
	}, $timeout);
}
