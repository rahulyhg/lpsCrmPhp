$(document).ready(function() {	
	$("#check_amount").maskMoney({thousands:'', decimal:'.'});
	$("#check_date").datepicker();
	if($("#check_amount").val()!="" && $("#check_amount").val()>0)
	{
		convert_number_letter($("#check_amount").val());
	}
});
function convert_number_letter(check_amount)
{
	var params = {
		check_amount:check_amount
	}
	$.ajax
	({
		type: "POST",
		url: "libs/check.php?action=convert_number_letter",
		//timeout: 3000,
		data: params,
		success: function(result){
			var rs = JSON.decode(result);
			if(rs.success)
			{				
				document.getElementById("check_amount_letter").value=rs.data.check_amount_letter;
			}
		}
	});
}
function get_info_bankaccount(id_bank_info)
{
	var params = {
		id_bank_info:id_bank_info,
		id_check:$("#id_check").val(),
		check_number:$("#check_number").val()
	}
	$.ajax
	({
		type: "POST",
		url: "libs/check.php?action=get_info_bankaccount",
		//timeout: 3000,
		data: params,
		success: function(result){
			var rs = JSON.decode(result);
			if(rs.success)
			{
				document.getElementById("company_div").innerHTML=rs.data.company_div;
				document.getElementById("company_bank_account_div").innerHTML=rs.data.company_bank_account_div;
				document.getElementById("check_number_div").innerHTML=rs.data.check_number_div;
				document.getElementById("transit_code_div").innerHTML=rs.data.transit_code_div;
				document.getElementById("account_number_div").innerHTML=rs.data.account_number_div;
				document.getElementById("signature_image_div").innerHTML=rs.data.signature_image_div;
				document.getElementById("logo_image_div").innerHTML=rs.data.logo_image_div;
				document.getElementById("check_number").value=rs.data.check_number;
			}
		}
	});
}
function validate_check()
{
	check_date=$("#check_date").val();
	payee=$("#payee").val();
	check_amount=$("#check_amount").val();
	check_amount_letter=$("#check_amount_letter").val();
	payee_name2=$("#payee_name2").val();
	note1=$("#note1").val();
	note2=$("#note2").val();
	payee_address1=$("#payee_address1").val();
	payee_address2=$("#payee_address2").val();
	payee_address3=$("#payee_address3").val();
	memo=$("#memo").val();
	if(check_date=="")
	{
		document.getElementById("check_date").className = "form-control has-error";
		//document.getElementById("msg_check").innerHTML="Check Date field is empty, please verify.";
		//alert("Check Date field is empty, please verify.");
		bootbox.alert("Check Date field is empty, please verify.");
		document.getElementById("msg_check").style.display = '';
		return false;
	}
	if(payee=="")
	{
		document.getElementById("payee").className = "form-control has-error";
		//document.getElementById("msg_check").innerHTML="Pay of the order field is empty, please verify.";
		//alert("Pay of the order field is empty, please verify.");
		bootbox.alert("Pay of the order field is empty, please verify.");
		document.getElementById("payee").focus();
		document.getElementById("msg_check").style.display = '';
		return false;
	}
	if(check_amount=="" || check_amount==0)
	{
		document.getElementById("check_amount").className = "form-control has-error";
		//document.getElementById("msg_check").innerHTML="Check Amount field is empty o less than zero, please verify.";
		//alert("Check Amount field is empty o less than zero, please verify.");
		bootbox.alert("Check Amount field is empty o less than zero, please verify.");
		document.getElementById("check_amount").focus();
		document.getElementById("msg_check").style.display = '';
		return false;
	}
	/*if(payee_name2=="")
	{
		document.getElementById("payee_name2").className = "form-control has-error";
		//document.getElementById("msg_check").innerHTML="Payee Name field is empty, please verify.";
		//alert("Payee Name field is empty, please verify.");
		bootbox.alert("Payee Name field is empty, please verify.");
		document.getElementById("payee_name2").focus();
		document.getElementById("msg_check").style.display = '';
		return false;
	}
	if(payee_address1=="" || payee_address2=="" || payee_address3=="")
	{
		document.getElementById("payee_address1").className = "form-control has-error";
		//document.getElementById("msg_check").innerHTML="Payee Address field is empty, please verify.";
		//alert("Payee Address field is empty, please verify.");
		bootbox.alert("Payee Address field is empty, please verify.");
		document.getElementById("payee_address1").focus();
		document.getElementById("msg_check").style.display = '';
		return false;
	}*/
	return true;
}
function save_check()
{
	if($("#action").val()=="newcheck_exp")
		id_bank_info=document.getElementById("id_bank_info").value;
	else
		id_bank_info=window.parent.document.getElementById("id_bank_infoH").value;
	valid=validate_check();
	if(valid==true)
	{
		var params = {
			id_check: $("#id_check").val(),
			action: $("#action").val(),
			id_bank_info: id_bank_info,
			check_number: $("#check_number").val(),
			check_date: $("#check_date").val(),
			payee: $("#payee").val(),
			check_amount: $("#check_amount").val(),
			check_amount_letter: $("#check_amount_letter").val(),
			payee_name2: $("#payee_name2").val(),
			note1: $("#note1").val(),
			note2: $("#note2").val(),
			payee_address1: $("#payee_address1").val(),
			payee_address2: $("#payee_address2").val(),
			payee_address3: $("#payee_address3").val(),
			memo: $("#memo").val()
		}
		$.ajax
		({
			type: "POST",
			url: "libs/check.php?action=save_check",
			data: params,
			success: function(result)
			{
				var rs = JSON.decode(result);
				if(rs.success)
				{
					bootbox.alert(rs.data.msg_check);
					getcheck_list();
					url="print_check.php?id_check="+rs.data.id_check+"&id_bank_info="+id_bank_info;
					//alert(url);
					//window.open(url,'_blank');
					setTimeout(function(){location.href=url;}, 1000);
					//setTimeout(function(){parent.$.fancybox.close();}, 2000);
				}
			}
		});		
	}
}
function getcheck_list()
{
	//alert(window.parent.document.getElementById("id_bank_infoH").value);
	var params = {
		id_bank_info: window.parent.document.getElementById("id_bank_infoH").value
	}
	$.ajax
	({
		type: "POST",
		url: "libs/accounting.php?action=getcheck_list",
		//timeout: 3000,
		data: params,
		success: function(result){
			var rs = JSON.decode(result);
			if(rs.success)
			{				
				parent.document.getElementById("accounting_grid").innerHTML=rs.data.accounting_grid;
				parent.$("#checks_grid").DataTable({
					"order": [[ 1, "desc" ]]
				});
			}
		}
	});
}
function fill_payee_address()
{
	var address = $("#payee_address1").val();
	var arr = address.split(',');
	payee_address2=arr[1]+", "+arr[2];
	$("#payee_address1").val(arr[0]);
	$("#payee_address2").val(payee_address2);
	$("#payee_address3").val(arr[3]);
}