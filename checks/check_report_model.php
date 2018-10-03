<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
 body {
		  font-family:Georgia, "Times New Roman", Times, serif;
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
</head>

<body>

<table width="800px" border="0" align="center" cellpadding="0" cellspacing="0">
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
    <td width="10%" height="25" class="text">Date:</td>
    <td width="90%" class="text">'.BD2PHP($text_date_from).' - '.BD2PHP($text_date_to).'</td>
  </tr>
  <tr>
    <td height="10" colspan="2" class="text">&nbsp;</td>
  </tr>
</table>
<table width="800px" border="0" align="center" cellpadding="0" cellspacing="0">
		<thead>
		  <tr style="font-size:12px; font-family:Georgia, Times New Roman, Times, serif;">
		  	<th height="25">Check #</th>
			<th>Check Date</th>
			<th>Payee Name</th>
			<th>Memo</th>
            <th>Amount</th>
		  </tr>
		</thead>
		<tbody>
        <tr>
          <td height="2" colspan="5" bgcolor="#000000">&nbsp;</td>
          </tr>
        <tr>
			<td height="25" class="text">'.$row['check_number'].'</td>
			<td class="text">'.BD2PHP($row['check_date']).'</td>
			<td class="text">'.$row['payee_name'].'</td>
            <td class="text">'.$row['memo'].'</td>
			<td class="text">'.number_format($row['check_amount'],2).'</td>
		  </tr>
        <tr>
          <td height="1" colspan="5" bgcolor="#000000">&nbsp;</td>
          </tr>
        <tr>
			  <td height="25"></td>
			  <td></td>
		  <td></td>
			  <td class="text"><strong>Grand Total</strong></td>
			  <td class="text"><strong>$'.number_format($total_check_amount,2).'</strong></td>
		  </tr>
        </tbody>
</table>
</body>
</html>