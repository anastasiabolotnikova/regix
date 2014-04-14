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

UpdateManager.prototype.update = function(update_manager) {
	$.ajax({
		url: update_manager.url,
		success: function(data) {
			update_manager.parseData(data, update_manager);
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


function SlottedUI(from, to, slot_count, timeout) {
	this.from = from;
	this.to = to;
	this.slot_count = slot_count;
	this.slot_time = (to - from) / slot_count;
	this.slot_height = 100 / slot_count;
	
	this.event_bar = $("#event_bar");
	this.event_tpl = $("#event_tpl").clone().removeAttr("id");
	this.event_placeholder_tpl = $("#event_placeholder_tpl").clone().removeAttr("id");
	
	this.timeline = $("#timeline");
	this.timeline_slot_tpl = $("#timeline_slot_tpl").clone().removeAttr("id");
	
	this.current_time_line = $("#current_time_line");
	
	this.slots = [];
	this.generate_slots();
	
	this.timeout = timeout;
	this.started = false;
	this.intervalID = 0;
	
	this.update_manager = new UpdateManager(url, 2000);
	ui = this;
	this.update_manager.addCallback(function(event) {
		ui.eventAdder(event, sui);
	});
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
	
	this.event_bar.empty();
	
	if(idx < 0 || idx >= this.slots.length) {
		// Invalid argument
		this.event_bar.append(this.event_placeholder_tpl);
		return;
	}
	
	var width = 100 / this.slots[idx].events.length;
	var cur_event;
	
	for (var i = 0; i < this.slots[idx].events.length; i++) {
		cur_event = this.event_tpl.clone();
		cur_event.find(".event_time").text(
				SlottedUI.format_date(this.slots[idx].events[i].from) +
				" — " +
				SlottedUI.format_date(this.slots[idx].events[i].to));
		
		cur_event.find(".event_client").text(this.slots[idx].events[i].user);
		cur_event.find(".event_description").text(
				this.slots[idx].events[i].description);
		
		cur_event.css("width", width + "%");
		this.event_bar.append(cur_event);
	}
	
	if (!this.slots[idx].events.length) {
		this.event_bar.append(this.event_placeholder_tpl);
	}
	
	$(".timeline_slot").removeClass("selected");
	this.slots[idx].el.addClass("selected");
}


SlottedUI.prototype.setCurrentTime = function(date) {
	if (date < this.from || date > this.to) {
		this.current_time_line.css("display", "none");
	} else {
		current_time_line_pos = (date.valueOf() - this.from.valueOf()) / 
				(this.to.valueOf() - this.from.valueOf()) * 100;
		this.current_time_line.css("top", current_time_line_pos + "%");
		this.current_time_line.css("display", "block");
	}
}

SlottedUI.prototype.getSystemTime = function() {
	return new Date();
}

SlottedUI.prototype.timeToSlot = function(time) {
	if (time < this.from || time > this.to) {
		// Not in range
		return -1;
	}
	
	return Math.floor((time - this.from) / this.slot_time);
}

//Autoupdater methods

SlottedUI.prototype.update = function() {
	this.setCurrentTime(this.getSystemTime());
	this.update_manager.update(this.update_manager);
}

SlottedUI.prototype.setTiemout = function(timeout) {
	this.timeout = timeout;
	if(this.started) {
		this.stop();
		this.start();
	}
}

SlottedUI.prototype.start = function() {
	if (!this.started) {
		this.started = true;
		
		ui = this;
		ui.update();
		
		ui.intervalID = setInterval(
				function() {
					ui.update()
				}, 
				ui.timeout);
	}
}

SlottedUI.prototype.stop = function() {
	if (this.started) {
		this.started = false;
		clearInterval(this.intervalID);
	}
}



var url = '/latest/events';
var from = new Date("Fri Apr 14 2014 08:00:00 GMT+0300");
var to = new Date("Fri Apr 14 2014 20:00:00 GMT+0300");

sui = new SlottedUI(from, to, 9, 2000);

sui.start();
sui.showSlot(sui.timeToSlot(sui.getSystemTime()));