function gen_times_slotted(time_start, time_end, events) {
	
	var time_length = time_end - time_start;
	
	var time_bar = $("#time_bar");
	var time_slot = $("#time_slot_tpl");
	
	var time_slot_cur;
	var time_slot_cur_height;
	var time_slot_cur_top;
	
	for (var i = 0, l = events.length; i < l; i++) {
		
		time_slot_cur_height = (events[i]['to'] - events[i]['from']) / time_length * 100;
		time_slot_cur_top = (events[i]['from'] - time_start) / time_length * 100;
		
		time_slot_cur = time_slot.clone().removeAttr("id");
		
		time_slot_cur.find(".time_from").html(events[i]['from']);
		time_slot_cur.find(".time_to").html(events[i]['to']);
		time_slot_cur.css("height", time_slot_cur_height + "%");
		time_slot_cur.css("top", time_slot_cur_top + "%");
		
		time_bar.append(time_slot_cur);
	}
}

function get_time() {
	var d = new Date();
	var t = d.getHours() * 60 + d.getMinutes();
	return 690;
}

function set_time_line(time_start, time_end, time) {
	var time_line = $("#time_line");
	var pos_top = (time - time_start) / (time_end - time_start) * 100
	time_line.css("top", pos_top + "%");
}

function init() {
	var time_start = 480;
	var time_stop = 960;
	
	events = [];
	
	events.push({'from'	:	600,
				'to'	:	660,
				'text'	:	'Text'});
	
	events.push({'from'	:	680,
				'to'	:	700,
				'text'	:	'Text'});
				
	gen_times_slotted(time_start, time_stop, events);
	set_time_line(time_start, time_stop, get_time());
	
	$("#time_bar").click(function (e) {
		var el_offset = $(this).offset();
		var el_height = $(this).height();
		var ev_y = e.pageY - el_offset.top;
		
		var ev_y_rel = ev_y / el_height;
		
		var time_length = time_stop - time_start;
		
		var sel_time = time_start + ev_y_rel * time_length;
		
		set_time_line(time_start, time_stop, sel_time);
	});
}

$("document").ready(init);