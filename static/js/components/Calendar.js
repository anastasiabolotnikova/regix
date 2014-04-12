// Event

function Event(id, from, to, user, description) {
	this.id = id;
	this.from = from;
	this.to = to;
	this.user = user;
	this.description = description;
}

Event.parseEvent = function(ev_data) {
	return new Event(
			$(ev_data).find("id").text(),
			$(ev_data).find("from").text(),
			$(ev_data).find("to").text(),
			"TEST",
			$(ev_data).find("description").text()
			);
}


// Update manager

function UpdateManager(url) {
	this.url = url;
	
	this.events = {};
	
	this.callbacks = $.Callbacks();
}

UpdateManager.prototype.addCallback = function(callbacks) {
	this.callbacks.add(callbacks);
}

UpdateManager.prototype.removeCallback = function(callbacks) {
	this.callbacks.remove(callbacks);
}

UpdateManager.prototype.parseData = function(data, update_manager) {
	console.log(update_manager);
	
	xmlData = $($.parseXML(data));
	
	xmlData.find("event").each(
			function (idx, value) {
				var event = Event.parseEvent(value);
				if (!update_manager.events[event.id]) {
					update_manager.events[event.id] = event;
					update_manager.callbacks.fire(event);
				}
			});
}

UpdateManager.prototype.update = function(url) {
	var self = this;
	$.ajax({
		url: this.url,
		success: function(data) {
			self.parseData(data, self);
		}
	});
}


// UI

function SlottedUI(from, to, slot_count) {
	this.from = from;
	this.to = to;
	this.slot_count = slot_count;
	this.slot_size = (to - from) / slot_count;
	
	this.timeline = $("#timeline");
	this.timeline_slot_tpl = $("#timeline_slot_tpl").clone().removeAttr("id");
	this.current_time_line = $("#current_time_line");
	
	this.slots = this.generate_slots(slot_count);
}

SlottedUI.generate_slots(slot_count) {
	for (var int = 0; int < slot_count; int++) {
		
	}
}

SlottedUI.prototype.eventAdder = function(event, ui) {
	console.log(this);
	var cur_timeslot = this.timeline_slot_tpl.clone();
	cur_timeslot.find(".timeslot_from").text(event.from);
	cur_timeslot.find(".timeslot_to").text(event.to);
	
	this.timeline.append(cur_timeslot);
}

var url = '/latest/events';

sui = new SlottedUI(480, 1080, 10)
um = new UpdateManager(url);
um.addCallback(function(event) {
	sui.eventAdder(event, sui);
});