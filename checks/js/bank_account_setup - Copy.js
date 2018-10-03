$(document).ready(function(){
	$("#table_bankinfo").DataTable();
	//$("#routing_number").mask("999999999");
	//$("#account_number").mask("9999999999");
	$("#transit_code").mask("99-99999");
	$("#company_phone").mask("(999)-9999999");
	list_bank_account();
	$("#form").on('submit',(function(e)
	{
		validate_bankinfo(function(val)
		{
			e.preventDefault();
			if(val==true)
			{
				//e.preventDefault();
				var formData = $('form').get(0); 
				formData.append('file', $('input[type=file]')[0].files[0]);
				formData.append('file', $('input[type=file]')[1].files[1]);
				var fd = new FormData(formData);
				$.ajax({
					url: "libs/bank_account_setup.php?action=save_bankinfo",
					type: "POST",
					data:  fd,
					contentType: false,
					cache: false,
					processData:false,
					success: function(result)
					{
						var rs = JSON.decode(result);
						if(rs.success)
						{
							document.getElementById("msg_bankinfo").innerHTML=rs.data.msg_bankinfo;
							document.getElementById("msg_bankinfo").style.display="";
							setTimeout(function(){document.getElementById("msg_bankinfo").style.display="none";}, 3000);
							$("#form")[0].reset();
							list_bank_account();
						}
					}
				});
			}
		});
	}));
	$("#signature_image").fancybox({
		//maxWidth	: 800,
		//maxHeight	: 600,
		fitToView	: true,
		width		: '290px',
		height		: '310px',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	$("#logo_image").fancybox({
		//maxWidth	: 800,
		//maxHeight	: 600,
		fitToView	: true,
		width		: '290px',
		height		: '310px',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	$("head_info").each(function(){
		$(this).click(function(){ 
			$("html,body").animate({ scrollTop: 0 }, 'slow');
			return false; 
		});
	});
	$("#account_number").keypress(function (e)
	{
		//if the letter is not digit then display error and don't type anything
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
		{
			//display error message
			$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
		}
	});
});
function list_bank_account()
{
	document.getElementById("div_newaccount").style.display="";
	document.getElementById("form_bank").style.display="none";
	$.ajax
	({
		type: "POST",
		url: "libs/bank_account_setup.php?action=list_bank_account",
		//data: params,
		success: function(result)
		{
			var rs = JSON.decode(result);
			if(rs.success)
			{
				document.getElementById("msg_bankinfo").innerHTML="";
				document.getElementById("msg_bankinfo").style.display="none";
				document.getElementById("bank_grid").innerHTML=rs.data.bank_grid;
				document.getElementById("bank_grid").style.display="";
				$("#table_bankinfo").DataTable();
			}
		}
	});
}

function edit_bankinfo(id_bank_info)
{
	document.getElementById("bank_grid").style.display="none";
	document.getElementById("div_newaccount").style.display="none";
	document.getElementById("form_bank").style.display="";
	var params = {
		id_bank_info: id_bank_info,
	}
	$.ajax
	({
		type: "POST",
		url: "libs/bank_account_setup.php?action=edit_bankinfo",
		data: params,
		success: function(result)
		{
			var rs = JSON.decode(result);
			if(rs.success)
			{
				//$("head_info").animate({ scrollTop: 0 }, "fast");	
				$("#company_name").val(rs.data.company_name);
				$("#company_address").val(rs.data.company_address);
				$("#company_city").val(rs.data.company_city);
				$("#company_state").val(rs.data.company_state);
				$("#company_zipcode").val(rs.data.company_zipcode);
				$("#company_phone").val(rs.data.company_phone);
							
				$("#id_bank_info").val(rs.data.id_bank_info);
				$("#bank_name").val(rs.data.bank_name);
				$("#information1").val(rs.data.information1);
				$("#transit_code").val(rs.data.transit_code);
				$("#routing_number").val(rs.data.routing_number);
				$("#account_number").val(rs.data.account_number);
				$("#check_number_start").val(rs.data.check_number_start);
				
				if(rs.data.signature_image_show==1)
					$('#signature_image_show').prop('checked', true);
				else
					$('#signature_image_show').prop('checked', false);
				if(rs.data.logo_image_show==1)
					$('#logo_image_show').prop('checked', true);
				else
					$('#logo_image_show').prop('checked', false);
				if(rs.data.default_account==1)
					$('#default_account').prop('checked', true);
				else
					$('#default_account').prop('checked', false);
					
				if(rs.data.print_routing_symbol==1)
					$('#print_routing_symbol').prop('checked', true);
				else
					$('#print_routing_symbol').prop('checked', false);
					
				document.getElementById("check_number_start").readOnly=true;
			}
		}
	});
}

function delete_bankinfo(id_bank_info,account_number)
{
	bootbox.confirm({
		message: "Are you sure you want to delete the selected Bank Account #: "+account_number+" ?",
		buttons: {
			confirm: {
				label: 'Yes',
				className: 'btn-success'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result)
			{
				var params = {
					id_bank_info: id_bank_info,
				}
				$.ajax
				({
					type: "POST",
					url: "libs/bank_account_setup.php?action=delete_bankinfo",
					data: params,
					success: function(result)
					{
						var rs = JSON.decode(result);
						if(rs.success)
						{
							document.getElementById("msg_bankinfo").innerHTML=rs.data.msg;
							document.getElementById("msg_bankinfo").style.display="";
							setTimeout(function(){document.getElementById("msg_bankinfo").style.display="none";}, 2000);
							list_bank_account();
						}
					}
				});
			}
		}
	});
}
function clean()
{
	document.getElementById("bank_name_group").className = "input-group";
	document.getElementById("transit_code_group").className = "input-group";
	document.getElementById("routing_number_group").className = "input-group";
	document.getElementById("account_number_group").className = "input-group";
	document.getElementById("information1_group").className = "input-group";
	document.getElementById("check_number_start_group").className = "input-group";
	document.getElementById("mandatory").style.display = 'none';
	document.getElementById("mandatory").innerHTML="";
}
function validate_bankinfo(callback)
{
	company_name=$("#company_name").val();
	company_address=$("#company_address").val();
	company_city=$("#company_city").val();
	company_state=$("#company_state").val();
	company_zipcode=$("#company_zipcode").val();
	company_phone=$("#company_phone").val();
		
	id_bank_info=$("#id_bank_info").val();
	bank_name=$("#bank_name").val();
	transit_code=$("#transit_code").val();
	routing_number=$("#routing_number").val();
	account_number=$("#account_number").val();
	information1=$("#information1").val();
	check_number_start=$("#check_number_start").val();
	
	document.getElementById("company_name_group").className = "input-group";
	document.getElementById("company_address_group").className = "input-group";
	document.getElementById("company_city_group").className = "input-group";
	document.getElementById("company_state_group").className = "input-group";
	document.getElementById("company_zipcode_group").className = "input-group";
	
	document.getElementById("bank_name_group").className = "input-group";
	document.getElementById("transit_code_group").className = "input-group";
	document.getElementById("routing_number_group").className = "input-group";
	document.getElementById("account_number_group").className = "input-group";
	document.getElementById("information1_group").className = "input-group";
	document.getElementById("check_number_start_group").className = "input-group";
	
	document.getElementById("mandatory").innerHTML="";
	document.getElementById("mandatory").style.display = "none";
	var params = {
			company_name: company_name,
			company_address: company_address,
			company_city: company_city,
			company_state: company_state,
			company_zipcode: company_zipcode,
			company_phone: company_phone,
			id_bank_info: id_bank_info,
			bank_name: bank_name,
			transit_code: transit_code,
			routing_number: routing_number,
			account_number: account_number,
			information1: information1,
			check_number_start: check_number_start
		}
		$.ajax
		({
			type: "POST",
			url: "libs/bank_account_setup.php?action=validate_bankinfo",
			data: params,
			success: function(result)
			{
				val=true;
				var rs = JSON.decode(result);
				if(rs.data.valid_account==0 && id_bank_info=="")
				{
					document.getElementById("account_number_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Bank account number is already registered in the database, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(company_name=="")
				{
					document.getElementById("company_name_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Company Name field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(company_phone=="")
				{
					document.getElementById("company_phone_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Company Phone field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(company_address=="")
				{
					document.getElementById("company_address_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Company Address field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(company_city=="")
				{
					document.getElementById("company_city_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Company City field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(company_state=="")
				{
					document.getElementById("company_state_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Company State field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(company_zipcode=="")
				{
					document.getElementById("company_zipcode_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Company ZIP Code field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(bank_name=="")
				{
					document.getElementById("bank_name_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Bank Name field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				/*if(transit_code=="")
				{
					document.getElementById("transit_code_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Transit Code field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					return false;
				}*/
				if(routing_number=="")
				{
					document.getElementById("routing_number_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Routing Number field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(account_number=="")
				{
					document.getElementById("account_number_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Account Number field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(check_number_start=="")
				{
					document.getElementById("check_number_start_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Current Check Number field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				if(information1=="")
				{
					document.getElementById("information1_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Address field is empty, please verify.";
					document.getElementById("mandatory").style.display = '';
					//return false;
					val=false;
					callback(val);
					return;
				}
				//return true;
				callback(val);
			}
		});
}

$(function()
{
	$("#information1").on('focus', function () {
		geolocate();
	});
	
	$("#information1").on('blur', function () {
		geolocate();
	});
	
	var placeSearch, autocomplete;
	var componentForm = {
		street_number: 'short_name',
		route: 'long_name',
		locality: 'long_name',
		administrative_area_level_1: 'short_name',
		administrative_area_level_2: 'long_name',
		postal_code: 'short_name'
	};
	
	function initialize() {
		// Create the autocomplete object, restricting the search
		// to geographical location types.
		autocomplete = new google.maps.places.Autocomplete(
		/** @type {HTMLInputElement} */ (document.getElementById('information1')), {
			types: ['geocode']
		});
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			fillInAddress();
		});
	}
	
	// [START region_fillform]
	function fillInAddress() {
		// Get the place details from the autocomplete object.
		var place = autocomplete.getPlace();
	
		document.getElementById("latitude").value = place.geometry.location.lat();
		document.getElementById("longitude").value = place.geometry.location.lng();
	
		for (var component in componentForm) {
			document.getElementById(component).value = '';
			document.getElementById(component).disabled = false;
		}
	
		// Get each component of the address from the place details
		// and fill the corresponding field on the form.
		for (var i = 0; i < place.address_components.length; i++) {
			var addressType = place.address_components[i].types[0];
			if (componentForm[addressType]) {
				var val = place.address_components[i][componentForm[addressType]];
				document.getElementById(addressType).value = val;
			}
		}
	}
	// [END region_fillform]
	
	// [START region_geolocation]
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function geolocate() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				var geolocation = new google.maps.LatLng(
				position.coords.latitude, position.coords.longitude);
	
				var latitude = position.coords.latitude;
				var longitude = position.coords.longitude;
				document.getElementById("latitude").value = latitude;
				document.getElementById("longitude").value = longitude;
	
				autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
			});
		}
	
	}
	initialize();
});
function new_account()
{
	document.getElementById("check_number_start").readOnly=false;
	$("#id_bank_info").val("");
	$("#company_name").val("");
	$("#company_address").val("");
	$("#company_city").val("");
	$("#company_state").val("");
	$("#company_zipcode").val("");
	$("#company_phone").val("");
		
	$("#id_bank_info").val("");
	$("#bank_name").val("");
	$("#transit_code").val("");
	$("#routing_number").val("");
	$("#account_number").val("");
	$("#information1").val("");
	$("#check_number_start").val("")
	$('#signature_image_show').attr('checked', false);
	$('#logo_image_show').attr('checked', false);
	$('#print_routing_symbol').attr('checked', false);
	$('#default_account').attr('checked', false);
	
	document.getElementById("bank_grid").style.display="none";
	document.getElementById("div_newaccount").style.display="none";
	document.getElementById("form_bank").style.display="";
}