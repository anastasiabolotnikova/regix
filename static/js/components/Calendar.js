/**
 * @brief Class that represents a client (Regix user).
 * 
 * @constructor
 * @this {Client}
 * @param {integer} id Id of the client (from the DB).
 * @param {string} name User name.
 * @param {string} email Client email.
 * @param {string} phone Client telephone number.
 */
function Client(id, name, email, phone) {
	this.id = id;
	this.name = name;
	this.email = email;
	this.phone = phone;
}


/**
 * @brief Class that represents a single booked event.
 * 
 * @constructor
 * @this {Event}
 * @param {integer} id Id of the event (from the DB).
 * @param {Date} from Date (with time) when the event begins.
 * @param {Date} to Date (with time) when the event ends.
 * @param {Client} to Client that registered the event.
 * @param {string} description Description of the event.
 * 
 * @todo Replace stub client.
 */
function Event(id, from, to, client, description) {
	this.id = id;
	this.from = from;
	this.to = to;
	this.client = client;
	this.description = description;
}


/**
 * @brief Parse an XML event into a new Event object.
 * 
 * @param {jQuery object} ev_data XML event.
 * 
 * @todo Replace stub client with real data.
 */
Event.parseEvent = function(ev_data) {
	return new Event(
			$(ev_data).find("id").text(),
			new Date($(ev_data).find("from").text()),
			new Date($(ev_data).find("to").text()),
			new Client(
					0,
					"Bogus Client Name",
					"bogus@examle.com",
					"+555 5555"
					),
			$(ev_data).find("description").text()
			);
}




/**
 * @brief Object that receives new Events from the server.
 * 
 * @param {string} url URL to request using AJAX.
 */
function UpdateManager(url) {
	this.url = url;
	this.events = {};
	this.callbacks = $.Callbacks();
}


/**
 * @brief Add a callback that will be called for every Event received.
 * 
 * @param {Function() | Array} callbacks A function, or array of functions,
 * that are to be added to the callback list. Note that callbacks will be passed
 * an event that triggered them.
 */
UpdateManager.prototype.addCallback = function(callbacks) {
	this.callbacks.add(callbacks);
}


/**
 * @brief Remove a callback that will should be called for every Event received.
 * 
 * @param {Function() | Array} callbacks A function, or array of functions,
 * that are to be removed from the callback list.
 */
UpdateManager.prototype.removeCallback = function(callbacks) {
	this.callbacks.remove(callbacks);
}


/**
 * @brief Parse an XML reply from the server.
 * 
 * This method parses an XML document passed as @a data and tries to create an
 * Event object from every `<event>` entry in the document using
 * Event.parseEvent method. For every new event (an event with an id that is not
 * registered) callbacks of the UpdateManager @a update_manager will be
 * triggered. Created Event will be passed as the only argument.
 * 
 * @param {string} data XML reply from the server containing a message with
 * events.
 * 
 * @param {UpdateManager} update_manager An UpdateManager that will be used to
 * trigger callbacks for new events.
 * 
 * @todo Implement error handling.
 */
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


/**
 * @brief Send a new request to server and parse response.
 * 
 * This method requests url configured when creating given UpdateManager object
 * and parses received response using UpdateManager.prototype.parseData method.
 * 
 * @param {UpdateManager} update_manager Update manager that will be used to
 * parse received response.
 */
UpdateManager.prototype.update = function(update_manager) {
	$.ajax({
		url: update_manager.url,
		success: function(data) {
			update_manager.parseData(data, update_manager);
		}
	});
}




/**
 * Timeslot, a collection of events registered for given time.
 */
function Slot(el) {
	this.el = el;
	this.events = [];
}

Slot.prototype.addEvent = function(event) {
	this.events.push(event);
}




/**
 * Main UI
 */
function SlottedUI(from, to, slot_count, timeout, prev_date, next_date) {
	
	// Closure
	ui = this;
	
	// Save arguments and calculate parameters
	this.from = from;
	this.to = to;
	this.slot_count = slot_count;
	this.slot_time = (to - from) / slot_count;
	this.slot_height = 100 / slot_count;
	
	this.prev_date = prev_date;
	this.next_date = next_date;
	
	// Set up navigation
	this.navigation_prev = $("#navigation_prev");
	this.navigation_now = $("#navigation_now");
	this.navigation_next = $("#navigation_next");
	
	this.navigation_now.click(function() {
		ui.showSlot(ui.timeToSlot(ui.getSystemTime()));
	});
	
	// Prepare event bar objects
	this.event_bar = $("#event_bar");
	this.event_tpl = $("#event_tpl").clone().removeAttr("id");
	this.event_placeholder_tpl = $("#event_placeholder_tpl").clone().removeAttr("id");
	
	// Prepare timeline objects
	this.timeline = $("#timeline");
	this.timeline_slot_tpl = $("#timeline_slot_tpl").clone().removeAttr("id");
	this.current_time_line = $("#current_time_line");
	
	// Generate slots with given parameters
	this.slots = [];
	this.generateSlots();
	
	// Set up autoupdater
	this.timeout = timeout;
	this.started = false;
	this.intervalID = 0;
	
	this.update_manager = new UpdateManager(url, 2000);
	this.update_manager.addCallback(function(event) {
		ui.eventAdder(event, sui);
	});
	
	this.shown_slot = -1;
}


/**
 * Return formatted date as string for printing.
 */
SlottedUI.formatDateTimeOnly = function(date) {
	var m = date.getMinutes();
	var h = date.getHours();
	var ms = m < 10 ? "0"+m : m.toString();
	var hs = h < 10 ? "0"+h : h.toString();
	return hs + ":" + ms;
}

/**
 * Return formatted date as string for printing (date only).
 */
SlottedUI.formatDateDayOnly = function(date) {
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	var ds = d < 10 ? "0"+d : d.toString();
	var ms = m < 10 ? "0"+m : m.toString();
	return ds + "." + ms + "." + y;
}

/**
 * Return formatted date as string for printing (with day).
 */
SlottedUI.formatDate = function(date) {
	var t = SlottedUI.formatDateTimeOnly(date);
	var d = SlottedUI.formatDateDayOnly(date);
	return d + ", " + t;
}


/**
 * Display navigation
 */
SlottedUI.prototype.updateNavigation = function(prev, now, next) {
	var self = this;
	this.navigation_prev.text("<< " + SlottedUI.formatDateDayOnly(prev));
	
	this.navigation_now.text(SlottedUI.formatDate(now));
	
	this.navigation_next.text(SlottedUI.formatDateDayOnly(next) + " >>");
}


/**
 * Create empty slots.
 */
SlottedUI.prototype.generateSlots = function() {
	var cur_slot;
	var cur_date = new Date(this.from.valueOf());
	var self = this;
	
	for (var i = 0; i < this.slot_count; i++) {
		
		cur_slot = this.timeline_slot_tpl.clone();
		cur_slot.find(".time_slot_from").text(
				SlottedUI.formatDateTimeOnly(new Date(cur_date)));
		cur_date.setTime(cur_date.valueOf() + this.slot_time);
		cur_slot.find(".time_slot_to").text(
				SlottedUI.formatDateTimeOnly(new Date(cur_date)));
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
		if (i == ui.shown_slot) {
			// Refresh current view. May cause data loss.
			ui.showSlot(i);
		}
	}
}


/**
 * Display slot events in main area.
 */
SlottedUI.prototype.showSlot = function(idx) {
	
	this.event_bar.empty();
	$(".timeline_slot").removeClass("selected");
	
	if(idx < 0 || idx >= this.slots.length) {
		// Invalid argument
		idx = 0;
	}
	
	// Display all events overlapping selected time slot.
	var width = 100 / this.slots[idx].events.length; // width of a single card
	var cur_event;
	
	for (var i = 0; i < this.slots[idx].events.length; i++) {
		cur_event = this.event_tpl.clone();	// Copy template
		
		// Show time
		cur_event.find(".event_time").text(
				SlottedUI.formatDateTimeOnly(this.slots[idx].events[i].from) +
				" — " +
				SlottedUI.formatDateTimeOnly(this.slots[idx].events[i].to));
		
		// Show client information
		cur_event.find(".event_client_name").text(
				this.slots[idx].events[i].client.name);
		cur_event.find(".event_client_email").text(
				this.slots[idx].events[i].client.email);
		cur_event.find(".event_client_phone").text(
				this.slots[idx].events[i].client.phone);
		
		// Show description
		cur_event.find(".event_description").text(
				this.slots[idx].events[i].description);
		
		if (i == this.slots[idx].events.length - 1) {
			cur_event.addClass("last");
		}
		/*cur_event.css("width", width + "%");*/
		this.event_bar.append(cur_event);
	}
	
	if (!this.slots[idx].events.length) {
		this.event_bar.append(this.event_placeholder_tpl);
	}
	this.slots[idx].el.addClass("selected");
	this.shown_slot = idx;
}


SlottedUI.prototype.setCurrentTime = function(date) {
	
	this.updateNavigation(this.prev_date, date, this.next_date);
	
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
var from = new Date("Fri Apr 15 2014 08:00:00 GMT+0300");
var to = new Date("Fri Apr 15 2014 18:00:00 GMT+0300");
var prev = new Date("Fri Apr 14 2014 08:00:00 GMT+0300");
var next = new Date("Fri Apr 16 2014 08:00:00 GMT+0300");

sui = new SlottedUI(from, to, 10, 2000, prev, next);

sui.start();
sui.showSlot(sui.timeToSlot(sui.getSystemTime()));