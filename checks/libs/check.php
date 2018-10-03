<?PHP
//include("../db.php");
include("../functions.php");
//$_PG = $_REQUEST;
define("MAJOR", 'pounds');
define("MINOR", 'p');
class toWords  {
		   var $pounds;
		   var $pence;
		   var $major;
		   var $minor;
		   var $words = '';
		   var $number;
		   var $magind;
		   var $units = array('','one','two','three','four','five','six','seven','eight','nine');
		   var $teens = array('ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen');
		   var $tens = array('','ten','twenty','thirty','forty','fifty','sixty','seventy','eighty','ninety');
		   var $mag = array('','thousand','million','billion','trillion');
	function toWords($amount, $major=MAJOR, $minor=MINOR) {
			 $this->major = $major;
			 $this->minor = $minor;
			 $this->number = number_format($amount,2);
			 list($this->pounds,$this->pence) = explode('.',$this->number);
			 $this->words = "$this->major$this->pence$this->minor";
			 if ($this->pounds==0)
				 $this->words = "Zero $this->words";
			 else {
				 $groups = explode(',',$this->pounds);
				 $groups = array_reverse($groups);
				 for ($this->magind=0; $this->magind<count($groups); $this->magind++) {
					  if (($this->magind==1)&&(strpos($this->words,'hundred') === false)&&($groups[0]!='000'))
						   $this->words = ' ' . $this->words;
					  $this->words = $this->_build($groups[$this->magind]).$this->words;
				 }
			 }
	}
	function _build($n) {			
			 $res = '';
			 $na = str_pad("$n",3,"0",STR_PAD_LEFT);
			 if ($na == '000') return ' ';
			 if ($na{0} != 0)
				 $res = ' '.$this->units[$na{0}] . ' hundred';
			 if (($na{1}=='0')&&($na{2}=='0'))
				  return $res . ' ' . $this->mag[$this->magind];
			 $res .= $res==''? ' ' : ' and';//$res .= $res==''? '' : ' and';
			 $t = (int)$na{1}; $u = (int)$na{2};
			 switch ($t) {
					 case 0: $res .= ' ' . $this->units[$u]; break;
					 case 1: $res .= ' ' . $this->teens[$u]; break;
					 default:$res .= ' ' . $this->tens[$t] . ' ' . $this->units[$u] ; break;
			 }
			 $res .= ' ' . $this->mag[$this->magind];
			 return $res;
	}
}

switch($_GET["action"])
{
	case "convert_number_letter":
		//$check_amount_letter=convert_number_to_words($_PG["check_amount"]);
		$obj = new toWords( $_PG["check_amount"] , '', '/100');
		$check_amount_letter = $obj->words;      // gives Twelve thousand three hundred forty five dollars 67c
		//echo  $obj->number;     // gives 12,345.67
		$_data["check_amount_letter"]=$check_amount_letter;
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "get_info_bankaccount":
		$id_check=$_PG["id_check"];
		$check_number=$_PG["check_number"];
		
		$sql="SELECT accounting_bank_info.* FROM accounting_bank_info WHERE accounting_bank_info.id_bank_info=".$_PG["id_bank_info"];
		//die($sql);
		$result = $conn->query($sql);		
		$row = $result->fetch_assoc();
		if($check_number=="")
		{
			$check_number=$row['check_number_start'];			
			$result2 = $conn->query("SELECT check_number AS check_number FROM accounting_checks WHERE id_bank_info=".$_PG["id_bank_info"]." ORDER BY check_number DESC");
			$row2 = $result2->fetch_assoc();
			if($result2->num_rows>0)
				$check_number=$row2['check_number']+1;
		}
		$check_number_array = array_map('intval', str_split($check_number));
		$count = 6-count($check_number_array);
		$zeros=array();
		for($i=0;$i<$count;$i++)
		{
			$zeros[$i]="0";
		}
		$check_number_array = array_merge($zeros,$check_number_array);
		$check_number_print="";
		for($i=0;$i<count($check_number_array);$i++)
		{
			$check_number_print.=$check_number_array[$i];
		}
		$path = "images/"; // upload directory	
		$_data["company_bank_account_div"]=$row['bank_name']."<br>".$row['information1']."<br>".$row['information2'].", ".$row['information3'];
		$_data["company_div"]=$row['company_name']."<br>".$row['company_address']."<br>".$row['company_city'].", ".$row['company_state'].", ".$row['company_zipcode']."<br>".$row['company_phone'];
		$_data["check_number_div"]="<strong>No.</strong> ".$check_number;
		$_data["check_number"]=$check_number;
		$_data["transit_code_div"]=$row['transit_code'];
		$_data["signature_image_div"]="";
		if($row['signature_image_show']==1)
			$_data["signature_image_div"]="<img src='".$path.$row['signature_image']."' width='130' height='25'/>";
		$_data["logo_image_div"]="";
		if($row['logo_image_show']==1)
			$_data["logo_image_div"]="<img src='".$path.$row['logo_image']."' width='60'/>";
		//$_data["account_number_div"]="C".$check_number_print."CA".$row['routing_number']."A".$row['account_number']."C";
		//$_data["account_number_div"]=$row['routing_number']."A".$row['account_number']."C  ".$check_number_print."";
		$_data["account_number_div"]="A".$row['routing_number']."A  ".$row['account_number']."C  ".$check_number_print;
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "validate_check":		
		break;
	case "save_check":
		$id_check=$_PG["id_check"];
		$id_bank_info=$_PG["id_bank_info"];
		$check_number=$_PG["check_number"];
		$check_date=PHP2BD($_PG["check_date"]);
		$payee=$_PG["payee"];
		$check_amount=$_PG["check_amount"];
		$check_amount_letter=$_PG["check_amount_letter"];
		$payee_name2=$_PG["payee_name2"];
		$note1=$_PG["note1"];
		$note2=$_PG["note2"];
		$payee_address1=$_PG["payee_address1"];
		$payee_address2=$_PG["payee_address2"];
		$payee_address3=$_PG["payee_address3"];
		$memo=$_PG["memo"];
		if($_PG["id_check"]=="")
		{
			$query="INSERT INTO accounting_checks (id_bank_info, check_number, check_date, payee, check_amount, check_amount_letter, payee_name, payee_address1, payee_address2, payee_address3, memo, note1, note2, date_created, id_account) VALUES ('".$id_bank_info."', '".$check_number."', '".$check_date."', '".$payee."', '".$check_amount."', '".$check_amount_letter."', '".$payee_name2."', '".$payee_address1."', '".$payee_address2."', '".$payee_address3."', '".$memo."', '".$note1."', '".$note2."', NOW(), '".$_SESSION["id_user"]."')";
			$result=$conn->query($query);
			$id_check=$conn->insert_id;
			
			$new_value="'".$id_bank_info."', '".$check_number."', '".$check_date."', '".$payee."', '".$check_amount."', '".$check_amount_letter."', '".$payee_name2."', '".$payee_address1."', '".$payee_address2."', '".$payee_address3."', '".$memo."', '".$note1."', '".$note2."', NOW(), '".$_SESSION["id_user"]."'";
			log_register($_SESSION["id_user"], "id_check", $id_check, "accounting_checks", "*", "INSERT", "", $new_value, "5");

			$msg="Check Saved";
		}
		elseif($_PG["id_check"]!="" && $_PG["action"]=="edit_check")
		{
			$field_affected=array('check_date','payee','check_amount','check_amount_letter', 'payee_name', 'payee_address1', 'payee_address2', 'payee_address3', 'memo', 'note1', 'note2');
			$new_value=array($check_date,$payee,$check_amount,$check_amount_letter,$payee_name2,$payee_address1,$payee_address2,$payee_address3,$memo,$note1,$note2);
			log_register($_SESSION["id_user"], "id_check", $id_check, "accounting_checks", $field_affected, "UPDATE", "", $new_value, "5");
			$query="UPDATE accounting_checks SET check_date='$check_date', payee='$payee', check_amount='$check_amount', check_amount_letter='$check_amount_letter', payee_name='$payee_name2', payee_address1='$payee_address1', payee_address2='$payee_address2', payee_address3='$payee_address3', memo='$memo', note1='$note1', note2='$note2' WHERE id_check='$id_check'";
			//die($query);
			$conn->query($query);
			//$msg="<div class='alert alert-success'>Check Info Updated</div>";
			$id_check=$_PG["id_check"];
			$msg="Check Info Updated";
		}
		elseif($_PG["id_check"]!="" && $_PG["action"]=="duplicate")
		{
			$query="INSERT INTO accounting_checks (id_bank_info, check_number, check_date, payee, check_amount, check_amount_letter, payee_name, payee_address1, payee_address2, payee_address3, memo, note1, note2, date_created, id_account) VALUES ('".$id_bank_info."', '".$check_number."', '".$check_date."', '".$payee."', '".$check_amount."', '".$check_amount_letter."', '".$payee_name2."', '".$payee_address1."', '".$payee_address2."', '".$payee_address3."', '".$memo."', '".$note1."', '".$note2."', NOW(), '".$_SESSION["id_user"]."')";
			//die($query);
			$conn->query($query);
			$id_check=$conn->insert_id;
			
			$new_value="'".$id_bank_info."', '".$check_number."', '".$check_date."', '".$payee."', '".$check_amount."', '".$check_amount_letter."', '".$payee_name2."', '".$payee_address1."', '".$payee_address2."', '".$payee_address3."', '".$memo."', '".$note1."', '".$note2."', NOW(), '".$_SESSION["id_user"]."'";
			log_register($_SESSION["id_user"], "id_check", $id_check, "accounting_checks", "*", "DUPLICATE", "", $new_value, "5");
			//$msg="<div class='alert alert-success'>Check Info Duplicated</div>";
			$msg="Check Info Duplicated";
		}	
		$_data["id_check"]="check_selected_".$id_check;
		$_data["msg_check"]=$msg;		
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "edit_check":
		$_checks_id = $_PG["checks_id"];
		foreach($_checks_id as $checks)
		{			
			$det_check=explode("_",$checks);
			$id_check=$det_check[2];				
		}
		$_data["msg"]="Selected Checks was deleted";
		$_output = array("success" => true, "data" => $_data);		
		echo json_encode($_output);
		break;
}
?>