$(document).ready(function() {
	$('#text_date_from').datepicker({
    startDate: '-2m',
    endDate: '+2d'
	});
	$('#text_date_to').datepicker({
    startDate: '-2m',
    endDate: '+2d'
	});
	getcheck_report();	
});
function getcheck_report()
{
	var params = {
		id_bank_info:$("#id_bank_info").val(),
		text_date_from:$("#text_date_from").val(),
		text_date_to:$("#text_date_to").val()
	}
	$.ajax
	({
		type: "POST",
		url: "libs/check_report.php?action=getcheck_report",
		//timeout: 3000,
		data: params,
		success: function(result){
			var rs = JSON.decode(result);
			if(rs.success)
			{				
				document.getElementById("checkreport_grid").innerHTML=rs.data.checkreport_grid;		
				//document.getElementById("btn_export").style.display=rs.data.btn_export;
				$("#report_grid").dataTable({
					"order": [[ 1, 'desc' ], [ 0, 'desc' ]],
					"iDisplayLength": 10,
					fixedHeader: {
						header: true,
						footer: true
					},
					dom: 'Bfrtip',
					buttons: [
						{
							extend: 'print',
							footer: true,
							title: 'Check Report',
							text: '<i class="fa fa-print"></i>',
							titleAttr: 'Print Check Report',
							customize: function ( win ) {
								$(win.document.body)
									.css( 'font-size', '8pt' )
			 
								$(win.document.body).find( 'table' )
									.addClass( 'compact' )
									.css( 'font-size', 'inherit' );
							}
						},
						{
							extend:    'excelHtml5',
							footer: true,
							title: 'Check Report',
							text:      '<i class="fa fa-file-excel-o"></i>',
							titleAttr: 'Excel Check Report'
						}
						/*{
							extend: 'pdfHtml5',
							footer: false,
							title: 'Check Report',
							text: '<i class="fa fa-file-pdf-o"></i>',
							titleAttr: 'PDF Check Report',
							orientation: 'landscape'
						}*/
					]
				});		
			}
		}
	});
}
function check_export()
{
	id_bank_info=$("#id_bank_info").val();
	text_date_from=$("#text_date_from").val();
	text_date_to=$("#text_date_to").val();
	window.open('print_check_report.php?idb='+id_bank_info+'&df='+text_date_from+'&dt='+text_date_to,'_blank');
}