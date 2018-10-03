<?
include("../functions.php");
switch($_GET["action"])
{	
	case "getcheck_list":	
		$id_bank_info=$_PG["id_bank_info"];
		$sql="SELECT * FROM accounting_checks WHERE id_bank_info='".$id_bank_info."' ORDER BY check_date DESC";
		//die($sql);
		//$sql="SELECT * FROM accounting_checks ORDER BY check_date DESC";
		$result = $conn->query($sql);
		$accounting_grid= '<table id="checks_grid" class="table table-hover">
		<thead>
		  <tr style="font-size:12px;">
		  	<th>Selected</th>
		  	<th>Check #</th>
			<th>Payee</th>
			<th>Check Amount</th>
			<th>Check Date</th>
			<th>Payee Name</th>
			<th>Payee Address</th>
			<th>Payee Address 2</th>
			<th>Payee Country</th>
		  </tr>
		</thead>
		<tbody>';
		while ($row=$result->fetch_assoc()) {
			$payee=$row['payee']!=""?$row['payee']:"<strong>Blank Check</strong>";
			$list_check.= '
			<tr>
			<td align="center"><input type="checkbox" id="check_selected_'.$row['id_check'].'" name="id_check_selected" /></td>
			<td>'.$row['check_number'].'</td>
			<td>'.$payee.'</td>
			<td>'.number_format($row['check_amount'],2).'</td>
			<td>'.BD2PHP($row['check_date']).'</td>
			<td>'.$row['payee_name'].'</td>
			<td>'.$row['payee_address1'].'</td>
			<td>'.$row['payee_address2'].'</td>
			<td>'.$row['payee_address3'].'</td>
		  </tr>';
		}
		$accounting_grid=$accounting_grid.$list_check.'</tbody></table>';
		$_data["accounting_grid"]=$accounting_grid;
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "validate_check":
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "delete_check":
		$_checks_id = $_PG["checks_id"];
		$i=0;
		foreach($_checks_id as $checks)
		{			
			//if(substr_count($checks,'_'))
			//{
				$det_check=explode("_",$checks);
				$id_check=$det_check[2];
				log_register($_SESSION["id_user"], "id_check", $id_check, "accounting_checks", "*", "DELETE", "", "", "5");
				$sql="DELETE FROM accounting_checks WHERE id_check=".$id_check;
				$conn->query($sql);
			//}
		}
		$_data["msg"]="Selected Checks was deleted";
		$_output = array("success" => true, "data" => $_data);		
		echo json_encode($_output);
		break;
}
?>