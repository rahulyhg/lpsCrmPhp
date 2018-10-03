<?PHP
//include("../db.php");
include("../functions.php");
//$_PG = $_REQUEST;

switch($_GET["action"])
{
	case "save_bankinfo":
		$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp'); // valid extensions
		$path = "../images/"; // upload directory
		$final_image1="";
		$final_image2="";
		if(isset($_FILES['signature_file']['tmp_name']) && $_FILES['signature_file']['tmp_name']!="")
		{
			$signature_image = $_FILES['signature_file']['name'];
			$tmp = $_FILES['signature_file']['tmp_name'];
			// get uploaded file's extension
			$ext = strtolower(pathinfo($signature_image, PATHINFO_EXTENSION));
			
			// can upload same image using rand function
			$final_image1 = "signature.".$ext;
			
			// check's valid format
			if(in_array($ext, $valid_extensions)) 
			{					
				$path = $path.strtolower($final_image1);	
				if(move_uploaded_file($tmp,$path)) 
				{
					$signature_image_uploaded=1;
				}
			}
			else
				$signature_image_uploaded=0;
		}
		$path = "../images/"; // upload directory
		if(isset($_FILES['logo_file']['tmp_name']) && $_FILES['logo_file']['tmp_name']!="")
		{
			$logo_image = $_FILES['logo_file']['name'];
			$tmp = $_FILES['logo_file']['tmp_name'];
			
			// get uploaded file's extension
			$ext = strtolower(pathinfo($logo_image, PATHINFO_EXTENSION));
			
			// can upload same image using rand function
			$final_image2 = "logo_image.".$ext;
			
			//die($final_image2);
			// check's valid format
			if(in_array($ext, $valid_extensions)) 
			{					
				$path = $path.strtolower($final_image2);	
				if(move_uploaded_file($tmp,$path)) 
				{
					$logo_image_uploaded=1;
				}
			}
			else
				$logo_image_uploaded=0;
		}
		$path = "../../accounts/".$_SESSION["subdomain"]."/images/"; // upload directory
		if(isset($_FILES['logo_bank']['tmp_name']) && $_FILES['logo_bank']['tmp_name']!="")
		{
			$logo_bank = $_FILES['logo_bank']['name'];
			$tmp = $_FILES['logo_bank']['tmp_name'];
			
			// get uploaded file's extension
			$ext = strtolower(pathinfo($logo_bank, PATHINFO_EXTENSION));
			
			// can upload same image using rand function
			$final_image3 = "logo_bank.".$ext;
			
			// check's valid format
			if(in_array($ext, $valid_extensions)) 
			{					
				$path = $path.strtolower($final_image3);	
				if(move_uploaded_file($tmp,$path)) 
				{
					$logo_bank_uploaded=1;
				}
			}
			else
				$logo_bank_uploaded=0;
		}
		
		$company_name = $_PG['company_name'];
		$company_address = $_PG['company_address'];
		$company_city = $_PG['company_city'];
		$company_state = $_PG['company_state'];
		$company_zipcode = $_PG['company_zipcode'];
		$company_phone = $_PG['company_phone'];
		
		$id_bank_info = $_PG['id_bank_info'];
		$bank_name = $_PG['bank_name'];
		$transit_code = $_PG['transit_code'];
		$routing_number = $_PG['routing_number'];
		$account_number = $_PG['account_number'];
		$information1 = $_PG['information1'];
		$address_array=explode(",",$information1);
		$information1=$address_array[0];
		$information2=$address_array[1];
		$information3=$address_array[2];
		$check_number_start = $_PG['check_number_start'];
		$signature_image_show = $_PG['signature_image_show']=="on"?1:0;
		$logo_image_show = $_PG['logo_image_show']=="on"?1:0;
		$default_account = $_PG['default_account']=="on"?1:0;
		$print_routing_symbol = $_PG['print_routing_symbol']=="on"?1:0;
				
		//password_verify($pass, $passHash)		
		if($id_bank_info=="")
		{
			$sql="INSERT INTO accounting_bank_info (company_name, company_address, company_city, company_state, company_zipcode, company_phone, bank_name, information1, information2, information3, transit_code, routing_number, account_number, check_number_start, signature_image, signature_image_show, logo_image, logo_image_show, date_created, id_account, default_account, print_routing_symbol) VALUES ('$company_name', '$company_address', '$company_city', '$company_state', '$company_zipcode', '$company_phone', '$bank_name', '$information1', '$information2', '$information3', '$transit_code', '$routing_number', '$account_number', '$check_number_start', '$final_image1', '$signature_image_show', '$final_image2', '$logo_image_show', now(), '".$_SESSION["id_user"]."', '$default_account', '$print_routing_symbol')";
			@$conn->query($sql);
			$id_bank_info = $conn->insert_id;
			
			$new_value="'$company_name', '$company_address', '$company_city', '$company_state', '$company_zipcode', '$company_phone', '$bank_name', '$information1', '$information2', '$information3', '$transit_code', '$routing_number', '$account_number', '$check_number_start', '$final_image1', '$signature_image_show', '$final_image2', '$logo_image_show', now(), '".$_SESSION["id_user"]."', '$default_account', '$print_routing_symbol'";			
			log_register($_SESSION["id_user"], "id_bank_info", $id_bank_info, "accounting_bank_info", "*", "INSERT", "", $new_value, "5");
			if($default_account)
			{
				$sql="UPDATE accounting_bank_info SET default_account=0 WHERE id_bank_info<>$id_bank_info";
				$conn->query($sql);
			}
				
			$_data["msg_bankinfo"]="<div class='alert alert-success'>Bank Account Info registered</div>";
		}
		else
		{
			$field_affected=array('company_name', 'company_address', 'company_city', 'company_state', 'company_zipcode', 'company_phone','bank_name','information1','information2','information3', 'transit_code', 'routing_number', 'account_number', 'default_account', 'signature_image_show', 'logo_image_show', 'print_routing_symbol');
			$new_value=array($bank_name,$information1,$information2,$information3,$transit_code,$routing_number,$account_number,$default_account,$signature_image_show,$logo_image_show, $print_routing_symbol);
			log_register($_SESSION["id_user"], "id_bank_info", $id_bank_info, "accounting_bank_info", $field_affected, "UPDATE", "", $new_value, "5");
			
			$sql="UPDATE accounting_bank_info SET company_name='$company_name', company_address='$company_address', company_city='$company_city', company_state='$company_state', company_zipcode='$company_zipcode', company_phone='$company_phone', bank_name='$bank_name', information1='$information1', information2='$information2', information3='$information3', transit_code='$transit_code', routing_number='$routing_number', account_number='$account_number', default_account='$default_account', signature_image_show='$signature_image_show', logo_image_show='$logo_image_show', print_routing_symbol='$print_routing_symbol' WHERE id_bank_info=$id_bank_info";
			//die($sql);
			$conn->query($sql);
			if($signature_image_uploaded)
			{				
				$sql="UPDATE accounting_bank_info SET signature_image='$final_image1' WHERE id_bank_info=$id_bank_info";
				$conn->query($sql);
			}
			if($logo_image_uploaded)
			{
				$sql="UPDATE accounting_bank_info SET logo_image='$final_image2' WHERE id_bank_info=$id_bank_info";
				$conn->query($sql);
			}
			if($default_account)
			{
				$sql="UPDATE accounting_bank_info SET default_account=0 WHERE id_bank_info<>$id_bank_info";
				$conn->query($sql);
			}
			$_data["msg_bankinfo"]="<div class='alert alert-success'>Bank Account Info Updated</div>";
		}

		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "list_bank_account":
		$path = "images/"; // upload directory
		$bank_grid_tr="";		
		$sql="SELECT * FROM accounting_bank_info ORDER BY bank_name";
		$result = $conn->query($sql);
        $bank_grid='<table id="table_bankinfo" class="table table-hover">
        <thead>
          <tr style="font-size:12px;">
		  	<th>Company Name</th>
		  	<th>Bank Name</th>
            <th>Transit Code</th>
            <th>Routing #</th>			
            <th>Account #</th>
            <th>Current Check #</th>
			<th>Signature</th>
			<th>Logo</th>
			<th>Default Account</th>
            <th></th>
          </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
			$default_account=$row['default_account']==1?"Yes":"No";
			$signature_image=$row['signature_image']!=""? $path.$row['signature_image']:$path."noimage.png";
			$signature_image2=$row['signature_image']!=""? "<img src='".$path.$row['signature_image']."' width='250' height='300'/>":"<img src='".$path."noimage.png' width='250' height='300'/>";
			$logo_image=$row['logo_image']!=""? $path.$row['logo_image']:$path."noimage.png";
			$logo_image2=$row['logo_image']!=""? "<img src='".$path.$row['logo_image']."' width='250' height='300'/>":"<img src='".$path."noimage.png' width='250' height='300'/>";

            $bank_grid_tr.='
            <tr>
			<td>'.$row['company_name'].'</td>
			<td>'.$row['bank_name'].'</td>
            <td>'.$row['transit_code'].'</td>
            <td>'.$row['routing_number'].'</td>
            <td>'.$row['account_number'].'</td>
			<td>'.$row['check_number_start'].'</td>
			<td><a id="signature_image" data-fancybox-type="iframe" href="image.php?w=270px&h=290px&src_pu='.$signature_image.'"><img src="'.$signature_image.'" width="35" height="25" /></a></td>
			<td><a id="logo_image" data-fancybox-type="iframe" href="image.php?w=270px&h=290px&src_pu='.$logo_image.'"><img src="'.$logo_image.'" width="35" height="25" /></a></td>
			<td>'.$default_account.'</td>			
            <td><button type="button" href="#head_info" onclick="edit_bankinfo('.$row['id_bank_info'].');" class="btn-xs btn-link" title="Edit Bank Info"><span class="glyphicon glyphicon-edit"></span></button>&nbsp;&nbsp;<button type="button" onclick="delete_bankinfo('.$row['id_bank_info'].','.$row['account_number'].');" class="btn-xs btn-link" title="Delete Bank Info"><span class="glyphicon glyphicon-remove"></span></button></td>
          	</tr>';
        }		
		$_data["bank_grid"]=$bank_grid.$bank_grid_tr.'</tbody></table>';
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "delete_bankinfo":
		$id_bank_info=$_PG["id_bank_info"];
		log_register($_SESSION["id_user"], "id_bank_info", $id_bank_info, "accounting_bank_info", "*", "DELETE", "", "", "5");
		$conn->query("DELETE FROM accounting_bank_info WHERE id_bank_info='$id_bank_info'");
		$_data["msg"]="<div class='alert alert-success'>Bank Info Deleted</div>";
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "edit_bankinfo":
		$id_bank_info=$_PG["id_bank_info"];
		$sql="SELECT * FROM accounting_bank_info WHERE id_bank_info='".$id_bank_info."'";
		$result=$conn->query($sql);
		$row = $result->fetch_assoc();
		
		$_data["company_name"]=$row["company_name"];
		$_data["company_address"]=$row["company_address"];
		$_data["company_city"]=$row["company_city"];
		$_data["company_state"]=$row["company_state"];
		$_data["company_zipcode"]=$row["company_zipcode"];
		$_data["company_phone"]=$row["company_phone"];
		
		$_data["id_bank_info"]=$row["id_bank_info"];
		$_data["bank_name"]=$row["bank_name"];
		$_data["information1"]=$row["information1"].", ".$row["information2"].", ".$row["information3"];
		$_data["transit_code"]=$row["transit_code"];
		$_data["routing_number"]=$row["routing_number"];
		$_data["account_number"]=$row["account_number"];
		$_data["check_number_start"]=$row["check_number_start"];
		$_data["signature_image_show"]=$row["signature_image_show"];
		$_data["logo_image_show"]=$row["logo_image_show"];
		$_data["default_account"]=$row["default_account"];
		$_data["print_routing_symbol"]=$row["print_routing_symbol"];
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
	case "validate_bankinfo":
		$id_bank_info=$_PG["id_bank_info"];
		$account_number=$_PG["account_number"];
		$routing_number=$_PG["routing_number"];
		$_data["msg"]="";
		$_data["valid_account"]=1;
		$sql="SELECT * FROM accounting_bank_info WHERE account_number='$account_number' AND routing_number='$routing_number'";	
		$result = $conn->query($sql);
		$row=$result->fetch_assoc();
		if($result->num_rows>0 && $row["id_bank_info"]!=$id_bank_info)	
		{
			$_data["valid_account"]=0;
		}
		$_output = array("success" => true, "data" => $_data);
		echo json_encode($_output);
		break;
}
?>