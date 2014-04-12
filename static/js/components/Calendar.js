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
			new Date($(ev_data).find("from").text()),
			new Date($(ev_data).find("to").text()),
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

function Slot(el) {
	this.el = el;
	this.events = [];
}

Slot.prototype.addEvent = function(event) {
	this.events.push(event);
}


function SlottedUI(from, to, slot_count) {
	this.from = from;
	this.to = to;
	this.slot_count = slot_count;
	this.slot_time = (to - from) / slot_count;
	this.slot_height = 100 / slot_count;
	
	this.timeline = $("#timeline");
	this.timeline_slot_tpl = $("#timeline_slot_tpl").clone().removeAttr("id");
	this.current_time_line = $("#current_time_line");
	
	this.slots = [];
	this.generate_slots();
}


/**
 * Return formatted date as string for printing.
 */
SlottedUI.format_date = function(date) {
	var m = date.getMinutes();
	var h = date.getHours();
	var ms = m < 10 ? "0"+m : m.toString();
	var hs = h < 10 ? "0"+h : h.toString();
	return hs + ":" + ms;
}


/**
 * Create empty slots.
 */
SlottedUI.prototype.generate_slots = function() {
	var cur_slot;
	var cur_date = new Date(this.from.valueOf());
	var self = this;
	
	for (var i = 0; i < this.slot_count; i++) {
		
		cur_slot = this.timeline_slot_tpl.clone();
		cur_slot.find(".time_slot_from").text(
				SlottedUI.format_date(new Date(cur_date)));
		cur_date.setTime(cur_date.valueOf() + this.slot_time);
		cur_slot.find(".time_slot_to").text(
				SlottedUI.format_date(new Date(cur_date)));
		cur_slot.attr("id", "time_slot_"+i);
		cur_slot.css("top", (i * this.slot_height) + "%");
		cur_slot.css("height", this.slot_height + "%");
		(function(i, ui) {
			cur_slot.click(function() {
				self.showSlot(i);
			});
		})(i);
		
		this.timeline.append(cur_slot);
		this.slots.push(new Slot(cur_slot));
	}
}


/**
 * Add event to an appropriate slot.
 */
SlottedUI.prototype.eventAdder = function(event, ui) {
	if(event.to < this.from || event.from > this.to) {
		return;
	}
	
	var slot_from = Math.floor(
			Math.max(
					(event.from - this.from) / this.slot_time,
					0
			)
	);
	
	var slot_to = Math.ceil(
			Math.min(
					(event.to - this.from) / this.slot_time,
					this.slot_count
			)
	) - 1;
	
	for (var i = slot_from; i <= slot_to; i++) {
		this.slots[i].addEvent(event);
		if(!this.slots[i].el.hasClass("timeline_slot_taken")) {
			this.slots[i].el.addClass("timeline_slot_taken");
		}
	}
}


/**
 * Display slot events in main area.
 */
SlottedUI.prototype.showSlot = function(idx) {
	console.log(this.slots[idx].events);
}

var url = '/latest/events';
var from = new Date("2014-04-19 08:00:00");
var to = new Date("2014-04-19 18:00:00");

sui = new SlottedUI(from, to, 10);
um = new UpdateManager(url);
um.addCallback(function(event) {
	sui.eventAdder(event, sui);
});

um.update();