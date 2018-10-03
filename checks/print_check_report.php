<?php
	require_once("functions.php");
	require_once('../html2fpdf/html2pdf.class.php');
	if(!validate_privileges($_SESSION["id_user"],5,1,1,0,0)){?>
<script language="javascript">location.href="login.php?logout";</script><?php }
	ini_set("allow_url_fopen", 1);
	$pdf_file=new HTML2PDF('P', 'letter', 'en');
	//$pdf_file=new HTML2FPDF('P','mm','letter');

	$html_check='';
	$id_bank_info=$_PG["idb"];
	$text_date_from=PHP2BD($_PG["df"]);
	$text_date_to=PHP2BD($_PG["dt"]);
	
	$sql="SELECT accounting_bank_info.bank_name, accounting_bank_info.account_number, dealership.name, dealership.subdomain FROM accounting_bank_info INNER JOIN dealership ON accounting_bank_info.dealership_id = dealership.id WHERE dealership_id='".$_SESSION["dealership_id"]."' AND accounting_bank_info.id_bank_info='$id_bank_info'";
	$result = $conn->query($sql);
	$row=$result->fetch_assoc();
	
	$name=$row["name"];
	$account_number=$row["account_number"];
	$bank_name=$row["bank_name"];
	$subdomain=$row["subdomain"];
	
	$sql="SELECT check_number, check_date, payee_name, check_amount, memo, bank_name, account_number FROM accounting_checks INNER JOIN accounting_bank_info ON accounting_checks.id_bank_info=accounting_bank_info.id_bank_info WHERE accounting_checks.dealership_id='".$_SESSION["dealership_id"]."' AND accounting_checks.id_bank_info='$id_bank_info' AND (check_date>='$text_date_from' AND check_date<='$text_date_to') ORDER BY check_date DESC";
	$result = $conn->query($sql);
	$checkreport_grid= '<table width="900px" border="0" align="center" cellpadding="2" cellspacing="2">
		  <tr style="font-size:12px;" bgcolor="#EEEEEE">
			<td>Check #</td>
			<td>Check Date</td>
			<td>Payee Name</td>
			<td>Memo</td>
			<td>Amount</td>
		  </tr>
		';
	$total_check_amount=0;
	$list_check="";
	while ($row=$result->fetch_assoc()) {
		$list_check.= '
		<tr class="text">
		<td align="center">'.$row['check_number'].'</td>
		<td align="center">'.BD2PHP($row['check_date']).'</td>
		<td>'.$row['payee_name'].'</td>			
		<td>'.$row['memo'].'</td>
		<td align="right">'.number_format($row['check_amount'],2).'</td>
	  </tr>';
		$total_check_amount+=$row['check_amount'];
	}
	$checkreport_grid_total='
	  <tr class="text" bgcolor="#EEEEEE">
	  <td></td>
	  <td></td>
	  <td></td>
	  <td><strong>Grand Total</strong></td>
	  <td align="right"><strong>$'.number_format($total_check_amount,2).'</strong></td>
	  </tr>';
	$checkreport_grid=$checkreport_grid.$list_check.$checkreport_grid_total.'</table>';
	$file_name = $subdomain.$id_bank_info."_".$text_date_from.".pdf";
	
	ob_start();
	$page = array();
	
	$html_check='<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
 body {
		  font-family: Verdana, Geneva, sans-serif;
	  }
.title1 {
	font-size: 18px;
	font-weight: bold;
	text-align: center;
}
.title2 {
	font-weight: bold;
	font-size: 14px;
}
.text {
	font-size: 12px;
}
</style>
</head><body>
<table width="900px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" colspan="2" align="center" bgcolor="#EEEEEE"><strong class="title1">Check  Summary Report</strong></td>
  </tr>
  <tr>
    <td height="25" colspan="2" class="title2">'.$row['name'].'</td>
  </tr>
  <tr>
    <td height="25" class="text">Bank:</td>
    <td class="text">'.$bank_name.'</td>
  </tr>
  <tr>
    <td height="25" class="text">Account Number:</td>
    <td class="text">'.$account_number.'</td>
  </tr>
  <tr>
    <td width="150" height="25" class="text">Date:</td>
    <td width="650" class="text">'.$_PG["df"].' - '.$_PG["dt"].'</td>
  </tr>
  <tr>
    <td height="10" colspan="2" class="text">&nbsp;</td>
  </tr>
</table>'.$checkreport_grid.'
</body></html>';
	$page[0]=utf8_decode($html_check);
	ob_end_clean();
	
	foreach($page as $page_actual)
	{
		if($page_actual!="")
		{
			die($page_actual);
			//$pdf_file->AddPage();
			$pdf_file->WriteHTML($page_actual);
			//$pdf_file->writeHTML($page_actual);
		}
	}
	$pdf_file->Output($file_name,"I");//I:browser,D:download,F:save
?>