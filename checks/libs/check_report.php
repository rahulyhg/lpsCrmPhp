<?
include("../functions.php");
switch($_GET["action"])
{	
	case "getcheck_report":
		$id_bank_info=$_PG["id_bank_info"];
		$text_date_from=PHP2BD($_PG["text_date_from"]);
		$text_date_to=PHP2BD($_PG["text_date_to"]);
		$sql="SELECT check_number, check_date, payee, payee_name, check_amount, memo, bank_name, account_number FROM accounting_checks INNER JOIN accounting_bank_info ON accounting_checks.id_bank_info=accounting_bank_info.id_bank_info WHERE accounting_checks.id_bank_info='$id_bank_info' AND (check_date>='$text_date_from' AND check_date<='$text_date_to') ORDER BY check_date DESC";
		$result = $conn->query($sql);
		if($result->num_rows>0)
		{
			$checkreport_grid= '<table id="report_grid" class="table table-hover">
			<thead>
			  <tr style="font-size:12px;">			    
				<th>Check #</th>
				<th>Check Date</th>
				<th>Pay of the Order of</th>
				<th>Payee Name</th>
				<th>Memo</th>
				<th>Amount</th>
			  </tr>
			</thead>
			<tbody>';
			$total_check_amount=0;
			while ($row=$result->fetch_assoc()) {
				$list_check.= '
				<tr>
				<td>'.$row['check_number'].'</td>
				<td>'.BD2PHP($row['check_date']).'</td>
				<td>'.$row['payee'].'</td>
				<td>'.$row['payee_name'].'</td>			
				<td>'.$row['memo'].'</td>
				<td>'.number_format($row['check_amount'],2).'</td>
			  </tr>';
				$total_check_amount+=$row['check_amount'];
			}
			$checkreport_grid_total='
			  <tr>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td><strong>Grand Total</strong></td>
			  <td><strong>$'.number_format($total_check_amount,2).'</strong></td>
			  </tr>';
			$checkreport_grid=$checkreport_grid.$list_check.$checkreport_grid_total.'</tbody></table>';
			$_data["btn_export"]="";
		}
		else
		{
			$checkreport_grid="No records found";
			$_data["btn_export"]="none";
		}
		$_data["checkreport_grid"]=$checkreport_grid;
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
}
?>