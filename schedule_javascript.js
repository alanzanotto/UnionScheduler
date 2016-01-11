
function update_shift(user_id, updated_shift_value)
{
$.ajax({
		url: "update_shift.php",
		method: "POST",
		data:
		{
			new_user_id: user_id,
			new_shift_value: updated_shift_value
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax

}

function update_available(user_id, updated_available_value)
{
$.ajax({
		url: "update_available.php",
		method: "POST",
		data:
		{
			new_user_id: user_id,
			new_available_value: updated_available_value
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax

}

function update_posted_position(user_id, updated_posted_position_value)
{
$.ajax({
		url: "update_posted_position.php",
		method: "POST",
		data:
		{
			new_user_id: user_id,
			new_posted_position_value: updated_posted_position_value
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax

}


function update_non_rotational(user_id, updated_non_rotational_value)
{
$.ajax({
		url: "update_non_rotational.php",
		method: "POST",
		data:
		{
			new_user_id: user_id,
			new_non_rotational_value: updated_non_rotational_value
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax

};

function update_displayed_schedule(updated_schedule_value)
{


$.ajax({
	type: "POST",
	url: "update_displayed_schedule_V2.php",
	data:
	{
		new_schedule_value: updated_schedule_value
	},
	dataType: "html",
	success: function(result){
	$("#schedule_page_content_4").html(result),//outputs the html data retrieved from load_data_page.php.  (no css is applied)
	$('#schedule_page_content_4').trigger('create')}//reconstructs the css on the specified container  (css will be applied to the new html)
	
	})

	var station_select = $('#select-choice-station_ID');
	station_select[0].selectedIndex = 0;
	station_select.selectmenu("refresh");
	
	var shift_select = $('#select-choice-shift_ID');
	shift_select[0].selectedIndex = 0;
	shift_select.selectmenu("refresh");
	
	//update the hidden Title Fields for printing.
	var schedule_date = $("#select-choice-schedule_ID :selected").text();
	schedule_date.trim()
	//$("#schedule_page_station").html("");
	$("#schedule_page_date").html(schedule_date);
	$("#schedule_page_station").html("Station");
	$("#schedule_page_shift").html("Shift");
	$("#schedule_page_time").html("Time");
	//$("#schedule_page_shift").html("");
}

function update_displayed_schedule_station()
{

var updated_schedule_value = $('#select-choice-schedule_ID').val();
var updated_station_value = $('#select-choice-station_ID').val();
var updated_shift_value = $('#select-choice-shift_ID').val();

$.ajax({
	type: "POST",
	url: "update_displayed_schedule_station.php",
	data:
	{
		new_schedule_value: updated_schedule_value,
		new_station_value: updated_station_value
	},
	dataType: "html",
	success: function(result){
	$("#schedule_page_content_4").html(result),//outputs the html data retrieved from load_data_page.php.  (no css is applied)
	$('#schedule_page_content_4').trigger('create')}//reconstructs the css on the specified container  (css will be applied to the new html)
	
	})

	var shift_select = $('#select-choice-shift_ID');
	shift_select[0].selectedIndex = 0;
	shift_select.selectmenu("refresh");
	
	
	//update the hidden Title Fields for printing.
	var station_text = $("#select-choice-station_ID :selected").text();
	$("#schedule_page_station").html(station_text);
	//$("#schedule_page_shift").html("");
	
}

function update_displayed_schedule_shift()
{

var updated_schedule_value = $('#select-choice-schedule_ID').val();
var updated_station_value = $('#select-choice-station_ID').val();
var updated_shift_value = $('#select-choice-shift_ID').val();

$.ajax({
	type: "POST",
	url: "update_displayed_schedule_shift.php",
	data:
	{
		new_schedule_value: updated_schedule_value,
		new_station_value: updated_station_value,
		new_shift_value: updated_shift_value
	},
	dataType: "html",
	success: function(result){
	$("#schedule_page_content_4").html(result),//outputs the html data retrieved from load_data_page.php.  (no css is applied)
	$('#schedule_page_content_4').trigger('create')}//reconstructs the css on the specified container  (css will be applied to the new html)
	
	})
	
	//update the hidden Title Fields for printing
	
	var schedule_date = $("#select-choice-schedule_ID :selected").text();
	schedule_date.trim()
	var d = new Date(schedule_date);
	var weekday = new Array(7);
	weekday[0]=  "Monday";
	weekday[1] = "Tuesday";
	weekday[2] = "Wednesday";
	weekday[3] = "Thursday";
	weekday[4] = "Friday";
	weekday[5] = "Saturday";
	weekday[6] = "Sunday";

	var schedule_weekday = weekday[d.getDay()];
	
	//if day set time to | "7:00 AM - 3:30PM"
	var schedule_time;
	if (updated_shift_value == 0)
	{
	schedule_time = " | 7:00 AM - 3:30 PM";
	}
	else
	{
	schedule_time = " | 4:00 PM - 12:30 AM";
	}
	$("#schedule_page_date").html(schedule_weekday);
	$("#schedule_page_time").html(schedule_date + schedule_time);
	
	var station_text = $("#select-choice-station_ID :selected").text();
	var shift_text = $("#select-choice-shift_ID :selected").text();
	$("#schedule_page_station").html(station_text);
	$("#schedule_page_shift").html(shift_text);
	
}



function update_schedule_page_shuffle()
{
var schedule_ID = $('#select-choice-schedule_ID').val();

$.ajax({
		url: "update_schedule_page_shuffle.php",
		method: "POST",
		data:
		{
			schedule_ID: schedule_ID
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){ load_schedule_page() }, 500);
}




function update_settings_edit_position_new()
{
var position_name = $('#newPopupSchedulePositionName').val();
var pp_requirement = $('#newPopupSchedulePositionPPRequirement').val();
var facility = $('#newPopupSchedulePositionFacility').val();
var station = $('#newPopupSchedulePositionStation').val();
var non_rotational = $('#newPopupSchedulePositionNonRotational').val();

var text = 
" position_name: " + position_name +
" pp_requirement: " + pp_requirement +
" facility: " + facility + 
" station: " + station + 
" non_rotational: " + non_rotational;

//alert(text);

$.ajax({
		url: "update_settings_edit_position_new.php",
		method: "POST",
		data:
		{
			new_position_name: position_name,
			new_pp_requirement: pp_requirement,
			new_facility: facility,
			new_station: station,
			new_non_rotational: non_rotational
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_position_page()}, 500);
}


function update_settings_edit_position_save()
{
var position_ID = $('#editPopupSchedulePositionHidden').val();
var position_name = $('#editPopupSchedulePositionName').val();
var pp_requirement = $('#editPopupSchedulePositionPPRequirement').val();
var facility = $('#editPopupSchedulePositionFacility').val();
var station = $('#editPopupSchedulePositionStation').val();
var non_rotational = $('#editPopupSchedulePositionNonRotational').val();

var text = 
" position_id: " + position_ID +
" position_name: " + position_name +
" pp_requirement: " + pp_requirement +
" facility: " + facility + 
" station: " + station + 
" non_rotational: " + non_rotational;

//alert(text);

$.ajax({
		url: "update_settings_edit_position_save.php",
		method: "POST",
		data:
		{
			position_id: position_ID,
			new_position_name: position_name,
			new_pp_requirement: pp_requirement,
			new_facility: facility,
			new_station: station,
			new_non_rotational: non_rotational
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_position_page()}, 500);
}

function update_settings_edit_position_delete()
{
var position_ID = $('#editPopupSchedulePositionHidden').val();

var text = 
" position_id: " + position_ID;

//alert(text);

$.ajax({
		url: "update_settings_edit_position_delete.php",
		method: "POST",
		data:
		{
			position_id: position_ID
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_position_page()}, 500);
}





function update_settings_edit_template_position_new()
{
var template_ID = $('#select-choice-template').val();
var position_ID = $('#select-choice-newPosition').val();
var position_quantity = $('#slider-fill_newPosition').val();
//var position_shift = $('input[name=radio-choice_newPosition]:checked').val();
var position_facility = $('#select-choice-newFacility').val();
var position_station = $('#select-choice-newStation').val();

$.ajax({
		url: "update_settings_edit_template_position_new.php",
		method: "POST",
		data:
		{
			template_ID: template_ID,
			new_position_ID: position_ID,
			new_position_quantity: position_quantity,
			new_position_facility: position_facility,
			new_position_station: position_station
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_create_schedule_template_position_list(template_ID)}, 500);
}

function update_settings_edit_template_position_save()
{
var template_ID = $('#select-choice-template').val();
var position_ID = $('#hiddenPositionID').val();
var position_quantity = $('#slider-fill_editPosition').val();
//var position_shift = $('input[name=radio-choice_editPosition]:checked').val();
var position_facility = $('#select-choice-editFacility').val();
var position_station = $('#select-choice-editStation').val();

$.ajax({
		url: "update_settings_edit_template_position_save.php",
		method: "POST",
		data:
		{
			template_ID: template_ID,
			position_ID: position_ID,
			new_position_quantity: position_quantity,
			new_position_facility: position_facility,
			new_position_station: position_station
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_create_schedule_template_position_list(template_ID)}, 500);
}


function update_settings_edit_template_position_delete()
{
var template_ID = $('#select-choice-template').val();
var position_ID = $('#hiddenPositionID').val();
var position_quantity = $('#slider-fill_editPosition').val();
var position_shift = $('input[name=radio-choice_editPosition]:checked').val();

$.ajax({
		url: "update_settings_edit_template_position_delete.php",
		method: "POST",
		data:
		{
			template_ID: template_ID,
			position_ID: position_ID,
			position_shift: position_shift,
			position_quantity: position_quantity
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_create_schedule_template_position_list(template_ID)}, 500);
}


function update_settings_posted_position_new()
{
var new_name = $('#newPostedPosition').val();

$.ajax({
		url: "update_settings_posted_position_new.php",
		method: "POST",
		data:
		{
			new_name: new_name
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_settings_posted_positions_page()}, 500);
}


//Popup Save Button (Create a new template)
function update_settings_schedule_template_new()
{
var new_template_name = $('#newTemplateName').val();
var new_template_replication = $('#select-choice_newTemplateReplication').val();

$.ajax({
		url: "update_settings_schedule_template_new.php",
		method: "POST",
		data:
		{
			new_template_name: new_template_name,
			new_template_replication: new_template_replication
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){ load_settings_schedule_templates_page() }, 500);
}

function update_settings_schedule_template_delete()
{
var template_ID = $('#hiddenTemplateID').val()

$.ajax({
		url: "update_settings_schedule_template_delete.php",
		method: "POST",
		data:
		{
			template_ID: template_ID
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax

setTimeout(function(){ load_settings_schedule_templates_page() }, 500);
}


function update_data_page_new()
{
var emp_first_name = $('#newEmployee_first_name').val();
var emp_last_name = $('#newEmployee_last_name').val();


$.ajax({
		url: "update_data_page_new_employee.php",
		method: "POST",
		data:
		{
			new_first_name: emp_first_name,
			new_last_name: emp_last_name
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){ load_data_page() }, 500);
}





function load_data_page()
{

//loading gif for data page.
$( document ).on( "click", ".show-page-loading-msg", function() {
    var $this = $( this ),
        theme = $this.jqmData( "theme" ) || $.mobile.loader.prototype.options.theme,
        msgText = $this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
        textVisible = $this.jqmData( "textvisible" ) || $.mobile.loader.prototype.options.textVisible,
        textonly = !!$this.jqmData( "textonly" );
        html = $this.jqmData( "html" ) || "";
    $.mobile.loading( "show", {
            text: msgText,
            textVisible: textVisible,
            theme: theme,
            textonly: textonly,
            html: html
    });
})

	
$.ajax({
	type: "POST",
	url: "load_data_page_V2.php",
	dataType: "html",
	success: function(result){
	$("#data_page_content").html(result),//outputs the html data retrieved from load_data_page.php.  (no css is applied)
	$('#data_page_content').trigger('create')}//reconstructs the css on the specified container  (css will be applied to the new html)
	})
setTimeout(function(){$.mobile.loading( "hide" )}, 4500);

}
	


function load_schedule_page()
{
$.ajax({
	type: "POST",
	url: "load_schedule_page_V2.php",
	dataType: "html",
	success: function(result){
	$("#schedule_page_content").html(result),
	$("#schedule_page_content").trigger('create')}
	
	})
}



function load_schedule_setup_page()
{
$.ajax({
	type: "POST",
	url: "load_schedule_setup_page.php",
	dataType: "html",
	success: function(result){
	$("#schedule_setup_page_content").html(result),
	$("#schedule_setup_page_content").trigger('create')}
	})
}

function load_settings_position_page()
{
$.ajax({
	type: "POST",
	url: "load_settings_positions_page_V2.php",
	dataType: "html",
	success: function(result){
	$("#settings_positions_page_content").html(result),
	$("#settings_positions_page_content").trigger('create')}
	})
}

function load_settings_posted_positions_page()
{
$.ajax({
	type: "POST",
	url: "load_settings_posted_positions_page.php",
	dataType: "html",
	success: function(result){
	$("#setting_posted_positions_content").html(result),
	$("#setting_posted_positions_content").trigger('create')}
	})
}

function load_settings_schedule_templates_page()
{
$.ajax({
	type: "POST",
	url: "load_settings_schedule_templates_page.php",
	dataType: "html",
	success: function(result){
	$("#settings_schedule_templates_page_content").html(result),
	$("#settings_schedule_templates_page_content").trigger('create')}
	})
}

function load_settings_create_schedule_template_page()
{

$.ajax({
	type: "POST",
	url: "load_settings_create_schedule_templates.php",
	dataType: "html",
	success: function(result){
	$("#settings_create_schedule_templates_page_content_1").html(result),
	$('#settings_create_schedule_templates_page_content_1').trigger('create'),
	$("#settings_create_schedule_templates_page_content_2").html(""),
	$('#settings_create_schedule_templates_page_content_2').trigger('create')}
	})
}

function load_settings_create_schedule_template_position_list(new_template_ID)
{
$.ajax({
	type: "POST",
	url: "load_settings_create_schedule_templates_position_list.php",
	data:
	{
		requested_template_ID: new_template_ID
	},
	dataType: "html",
	success: function(result){
	$("#settings_create_schedule_templates_page_content_2").html(result),
	$('#settings_create_schedule_templates_page_content_2').trigger('create')}
	})
	
}




function create_schedule(requested_date)
{
/*
requested_date = document.getElementById('text-date').value
requested_template = document.getElementById('select-schedule_template').value
*/
//checkbox-dayX
//Calculate the day templates
var x = 0;
var loop = true;
var day_templates = "";
while (loop == true)
{
	var templateElement = "#checkbox-day" + x;
	//if element does not exist, then set x to null and eject from loop.  no more templates.
	if ($(templateElement).val() == null){
		break;//no more template to collect
	}
	else{
		//only if the template is checked will the template be added to the schedule
		if ($(templateElement).prop("checked") == true)
			day_templates = day_templates + "_" + $(templateElement).val();
		x++;
	}
}//while

//Calculate the night templates
var x = 0;
var loop = true;
var night_templates = "";
while (loop == true)
{
	var templateElement = "#checkbox-night" + x;
	//if element does not exist, then set x to null and eject from loop.  no more templates.
	if ($(templateElement).val() == null){
		break;//no more template to collect
	}
	else{
		//only if the template is checked will the template be added to the schedule
		if ($(templateElement).prop("checked") == true)
			night_templates = night_templates + "_" + $(templateElement).val();
		x++;
	}
}//while


//Calculate the night templates


$.ajax({
	type: "POST",
	url: "create_schedule.php",
	dataType: "html",
	data:
		{
			schedule_date: requested_date,
			templates_day: day_templates,
			templates_night: night_templates
		},//data
	success: function(result){
	$("#schedule_setup_page_content2").html(result),
	$("#anchor-button").button('disable'),
	$('#schedule_setup_page_content2').trigger('create')}
	
	})


}





function submit_new_position(position_name, position_PP_ID, position_nr)
{
$.ajax({
	type: "POST",
	url: "settings_add_new_position.php",
	dataType: "html",
	data:
		{
			new_position_name: position_name,
			new_position_PP_ID: position_PP_ID,
			new_position_NR: position_nr
		},//data
	success: function(result){
	document.getElementById("settings_positions_page_form").reset();
	alert("Added!");
	}

	})
}


function update_popup_edit_position(position_id, position_name, quantity, facility, station)
{
//input values: position_id, quantity, 
var newTitle = "Edit Position: " + position_name;
$('#editPositionHeader').html(newTitle);//update header label with position name
$('#hiddenPositionID').val(position_id);//update hidden field with position ID.
$('#slider-fill_editPosition').val(quantity).slider( "refresh" );//update slider

//Shift is not needed any more  but keeping this code for another location.
/*if shift is day then set day to true
if (shift == 0)
{
$('#radio-choice-day_editPosition').prop("checked", true).checkboxradio("refresh");
$('#radio-choice-night_editPosition').prop("checked", false).checkboxradio("refresh");
}
else//set shift to night
{
$('#radio-choice-day_editPosition').prop("checked", false).checkboxradio("refresh");
$('#radio-choice-night_editPosition').prop("checked", true).checkboxradio("refresh");
}
*/

var facility_menu = $( "#select-choice-editFacility" );
facility_menu[0].selectedIndex = facility;
facility_menu.selectmenu( "refresh" );

var station_menu = $( "#select-choice-editStation" );
station_menu[0].selectedIndex = station;
station_menu.selectmenu( "refresh" );

}//function update_popup_edit_position(position_id, postion_name, quantity, shift)


function update_edit_popup_schedule_position(position_ID, position_name, PP_requirement)
{

var newTitle = "Edit Position: " + position_name;
$('#editPopupSchedulePositionHeader').html(newTitle);

$('#editPopupSchedulePositionName').val(position_name);

$('#editPopupSchedulePositionHidden').val(position_ID);

var myselect = $( "#editPopupSchedulePositionPPRequirement" );
myselect[0].selectedIndex = PP_requirement;
myselect.selectmenu( "refresh" );

}


function update_edit_popup_template(template_ID, template_name)
{
$('#hiddenTemplateID').val(template_ID);
var head = "Edit Template: " + template_name;
$('#editTemplateHeader').html(head);
}


//found in load_data_page()
function update_delete_employee_popup(emp_ID, first_name, last_name)
{
//updates header to include name of employee.
var newTitle = "Delete Employee: " + first_name + " " + last_name + "?";
$('#popupDeleteEmployeeHeader').html(newTitle);
$('#deleteEmployeeHidden').val(emp_ID);

}

function update_data_page_deleteEmployee()
{
var employee_ID = $('#deleteEmployeeHidden').val();

$.ajax({
		url: "update_data_page_deleteEmployee.php",
		method: "POST",
		data:
		{
			employee_ID: employee_ID
		},//data
		complete: function(xhr, status)
		{
		//alert("Update complete!");
		}
	}//ajax
	)//$.ajax
	
setTimeout(function(){load_data_page()}, 500);
}



