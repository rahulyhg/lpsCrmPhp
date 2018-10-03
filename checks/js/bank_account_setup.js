$(document).ready(function(){
	$("#table_bankinfo").DataTable();
	$("#routing_number").mask("999999?999999");
	$("#account_number").mask("999999?999999");
	$("#check_number_start").mask("9?9999");
	$("#transit_code").mask("99-99999");
	$("#company_phone").mask("(999)-9999999");
	list_bank_account();
	$("#form").on('submit',(function(e)
	{
		if(validate_bankinfo())
		{		
			e.preventDefault();
			
			$.ajax({
				url: "libs/bank_account_setup.php?action=save_bankinfo",
				type: "POST",
				data:  new FormData(this),
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
				$("#autocomplete2").val(rs.data.company_address);
				$("#locality2").val(rs.data.company_city);
				$("#administrative_area_level_12").val(rs.data.company_state);
				$("#postal_code2").val(rs.data.company_zipcode);
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
	document.getElementById("mandatory").style.display = 'none';
	document.getElementById("mandatory").innerHTML="";
}
function validate_bankaccount()
{
	clean();
	id_bank_info=$("#id_bank_info").val();
	transit_code=$("#transit_code").val();
	routing_number=$("#routing_number").val();
	account_number=$("#account_number").val();
	if(routing_number!="" && account_number!="")
	{
		var params = {
			id_bank_info: id_bank_info,
			routing_number: routing_number,
			account_number: account_number,
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
				if(rs.data.valid_account==0)
				{
					document.getElementById("account_number_group").className = "input-group has-error";
					document.getElementById("mandatory").innerHTML="Bank account number is already registered in the database, please verify.";
					document.getElementById("mandatory").style.display = '';
					return false;
				}
			}
		});
	}
}
function validate_bankinfo()
{
	company_name=$("#company_name").val();
	company_address=$("#autocomplete2").val();
	company_city=$("#locality2").val();
	company_state=$("#administrative_area_level_12").val();
	company_zipcode=$("#postal_code2").val();
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
	
	if(company_name=="")
	{
		document.getElementById("company_name_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Company Name field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(company_phone=="")
	{
		document.getElementById("company_phone_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Company Phone field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(company_address=="")
	{
		document.getElementById("company_address_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Company Address field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(company_city=="")
	{
		document.getElementById("company_city_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Company City field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(company_state=="")
	{
		document.getElementById("company_state_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Company State field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(company_zipcode=="")
	{
		document.getElementById("company_zipcode_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Company ZIP Code field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(bank_name=="")
	{
		document.getElementById("bank_name_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Bank Name field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
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
		return false;
	}
	if(account_number=="")
	{
		document.getElementById("account_number_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Account Number field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(check_number_start=="")
	{
		document.getElementById("check_number_start_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Current Check Number field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	if(information1=="")
	{
		document.getElementById("information1_group").className = "input-group has-error";
		document.getElementById("mandatory").innerHTML="Address field is empty, please verify.";
		document.getElementById("mandatory").style.display = '';
		return false;
	}
	return true;
}
$(function()
{
	var placeSearch, autocomplete, autocomplete2;
	var componentForm = {
	  street_number: 'short_name',
	  route: 'long_name',
	  locality: 'long_name',
	  administrative_area_level_1: 'short_name',
	  country: 'long_name',
	  postal_code: 'short_name'
	};
	
	function initAutocomplete() {
	  // Create the autocomplete object, restricting the search to geographical
	  // location types.
	  autocomplete = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */
		(document.getElementById('information1')), {
		  types: ['geocode'],
		  componentRestrictions: { country: "us" }
		});
	
	  // When the user selects an address from the dropdown, populate the address
	  // fields in the form.
	  autocomplete.addListener('place_changed', function() {
		fillInAddress(autocomplete, "");
	  });
	
	  autocomplete2 = new google.maps.places.Autocomplete(
		/** @type {!HTMLInputElement} */
		(document.getElementById('autocomplete2')), {
		  types: ['geocode'],
		  componentRestrictions: { country: "us" }
		});
	  autocomplete2.addListener('place_changed', function() {
		fillInAddress(autocomplete2, "2");
	  });
	}
	
	function fillInAddress(autocomplete, unique) {
	  // Get the place details from the autocomplete object.
	  var place = autocomplete.getPlace();
	
	  for (var component in componentForm) {
		if (!!document.getElementById(component + unique)) {
		  document.getElementById(component + unique).value = '';
		  document.getElementById(component + unique).disabled = false;
		}
	  }
	
	  // Get each component of the address from the place details
	  // and fill the corresponding field on the form.
	  for (var i = 0; i < place.address_components.length; i++) {
		var addressType = place.address_components[i].types[0];
		if (componentForm[addressType] && document.getElementById(addressType + unique)) {
		  var val = place.address_components[i][componentForm[addressType]];
		  document.getElementById(addressType + unique).value = val;
		}
	  }
	  fix_address();
	}
	google.maps.event.addDomListener(window, "load", initAutocomplete);
	
	function geolocate() {
	  if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
		  var geolocation = {
			lat: position.coords.latitude,
			lng: position.coords.longitude
		  };
		  var circle = new google.maps.Circle({
			center: geolocation,
			radius: position.coords.accuracy
		  });
		  autocomplete.setBounds(circle.getBounds());
		});
	  }
	}
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
function fix_address()
{
	clean();
	var address = $("#autocomplete2").val();
	var arr = address.split(',');
	$("#autocomplete2").val(arr[0]);
}