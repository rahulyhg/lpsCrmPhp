$(document).ready(function() {
	//$("#text_date").datepicker();
	/*$('#text_date_from').datepicker({
    startDate: '-2m',
    endDate: '+2d'
	});
	$('#text_date_to').datepicker({
    startDate: '-2m',
    endDate: '+2d'
	});
	//$("#checks_grid").DataTable();*/
	$("#newcheck").fancybox({
		fitToView	: false,
		width		: '900px',
		height		: '520px',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		closeBtn: false
	});
	getcheck_list($("#id_bank_infoH").val());	
});

function show_edit()
{
	var checks_id = new Array();
	 $("input:checkbox").each(function() {
		if ($(this).is(":checked"))
		{
			checks_id.push($(this).attr("id"));
		}
	});
	
	if(checks_id.length==0)
	{		
		//alert("Please select a check.");
		bootbox.alert("Please select a check.");
	}
	else if(checks_id.length>1)
	{		
		//alert("Please select just one check.");
		bootbox.alert("Please select just one check.");
		parent.$.fancybox.close();
	}
	else
	{
		var arr = checks_id[0].split('_');
		checks_id=arr[2];
		//url=this.href;
		url="check.php?id_check="+checks_id+"&action=edit_check";
		$("#editcheck").fancybox({
			fitToView	: false,
			width		: '900px',
			height		: '520px',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
			overlayShow	: true,
			href		: url,
			hideOnContentClick: false,
			closeBtn: false,
			iframe:{
				preload: false // fixes issue with iframe and IE
			}
		});
	}
}
function show_duplicate()
{
	var checks_id = new Array();
	 $("input:checkbox").each(function() {
		if ($(this).is(":checked"))
		{
			checks_id.push($(this).attr("id"));
		}
	});
	
	if(checks_id.length==0)
	{		
		//alert("Please select a check.");
		bootbox.alert("Please select a check.");
	}
	else if(checks_id.length>1)
	{		
		//alert("Please select just one check.");
		bootbox.alert("Please select just one check.");
		parent.$.fancybox.close();
	}
	else
	{
		var arr = checks_id[0].split('_');
		checks_id=arr[2];
		//url=this.href;
		url="check.php?id_check="+checks_id+"&action=duplicate";
		$("#duplicatecheck").fancybox({
			fitToView	: false,
			width		: '900px',
			height		: '540px',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
			overlayShow	: true,
			href		: url,
			hideOnContentClick: false,
			closeBtn: false,
			iframe:{
				preload: false // fixes issue with iframe and IE
			}
		});
	}
}
function print_check(blank)
{
	if(!blank)
	{		
		var checks_id = new Array();
		$("input:checkbox").each(function() {
			if ($(this).is(":checked"))
			{
				checks_id.push($(this).attr("id"));
			}
		});
		
		if(checks_id.length==0)
		{		
			bootbox.alert("Please select at least one check.");
		}
		/*else if(checks_id.length>1)
		{		
			bootbox.alert("Please select just one check.");
			parent.$.fancybox.close();
		}*/
		else
		{
			/*var arr = checks_id[0].split('_');
			checks_id=arr[2];*/
			//alert(checks_id);
			url="print_check.php?id_check="+checks_id+"&id_bank_info="+$("#id_bank_infoH").val();
			
			$("#printcheck").fancybox({
				fitToView	: false,
				width		: '900px',
				height		: '540px',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none',
				overlayShow	: true,
				href		: url,
				hideOnContentClick: false,
				closeBtn: false,
				iframe:{
					preload: false // fixes issue with iframe and IE
				}
			});
		}
	}
	else
	{
		bootbox.confirm({
			message: "Are you sure you want to Print a Blank Check?",
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
					url="print_check_blank.php?id_check=0"+"&id_bank_info="+$("#id_bank_infoH").val();
					$("#printcheck_blank2").fancybox({
						fitToView	: false,
						width		: '900px',
						height		: '540px',
						autoSize	: false,
						closeClick	: false,
						openEffect	: 'none',
						closeEffect	: 'none',
						overlayShow	: true,
						href		: url,
						hideOnContentClick: false,
						closeBtn: false,
						iframe:{
							preload: false // fixes issue with iframe and IE
						}
					});
					$('#printcheck_blank2').trigger('click');
				}
			}
		});
	}
}
function getcheck_list(id_bank_info)
{
	/*type_search=$("input[name=type_search]:checked").val();
	fields=$("#fields").val();
	text_search=$("#text_search").val();
	text_date_from=$("#text_date_from").val();
	text_date_to=$("#text_date_to").val();
	text_search_default=$("#text_search_default").val();*/
	document.getElementById('id_bank_infoH').value=id_bank_info;
	var params = {
		id_bank_info:id_bank_info
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
				document.getElementById("accounting_grid").innerHTML=rs.data.accounting_grid;
				$("#checks_grid").DataTable({
					"order": [[ 1, "desc" ]]
				});
			}
		}
	});
}
function delete_check()
{
	var checks_id = new Array();
	 $("input:checkbox").each(function() {
		if ($(this).is(":checked"))
		{
            checks_id.push($(this).attr("id"));
        }
	});
	if(checks_id!="")
	{
		bootbox.confirm({
			message: "Are you sure you want to delete Selected Checks?",
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
						checks_id: checks_id
					};
					//loading_show();                    
					$.ajax
					({
						type: "POST",
						url: "libs/accounting.php?action=delete_check",
						//timeout: 3000,
						data: params,
						success: function(result){
							var rs = JSON.decode(result);
							if(rs.success)
							{
								/*document.getElementById("msg").innerHTML=rs.data.msg;
								document.getElementById("msg").style.display="";
								$(document).scrollTop( $("#msg").offset().top );
								setTimeout(function(){document.getElementById("msg").style.display="none";}, 3000);*/
								bootbox.alert(rs.data.msg);
								getcheck_list($("#id_bank_infoH").val());
							}
						}
					});
				}
			}
		});
	}
	else
	{
		bootbox.alert("Please select at least one check.");
	}
}
