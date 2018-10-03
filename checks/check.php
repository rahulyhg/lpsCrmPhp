<?php
	include ("db.php"); loginCheck();
	include("functions.php");	
	
	if($_PG["action"]=="new" || $_PG["action"]=="newcheck_exp")
	{
		$button_title="Print Check";
	}
	elseif($_PG["action"]=="edit_check")
	{
		$button_title="Update";
	}
	elseif($_PG["action"]=="duplicate")
	{
		$button_title="Duplicate";
	}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="css/jquery.fancybox.css" type="textcss" media="screen" />
<script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyD4tsJ4QAoKkvKXeBwmBhZIhB3NIShMM8E"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/jscolor.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="js/json/json.js"></script>
<script type="text/javascript" src="js/mask/jquery.maskedinput.js" ></script>
<script type="text/javascript" src="js/mask/jquery_maskmoney/jquery.maskMoney.js" ></script>
<script language="javascript" src="js/bootbox.min.js"></script>
<script language="javascript" src="js/check.js"></script>
<script language="javascript" type="text/javascript">
$(function(){//<![CDATA[
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

$("#payee_address1").on('focus', function () {
    geolocate();
});

$("#payee_address1").on('blur', function () {
    geolocate();
});
var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    payee_address2: 'long_name',
    //administrative_area_level_1: 'short_name',
    payee_address3: 'long_name',
    //postal_code: 'short_name'
};

function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
    /** @type {HTMLInputElement} */ (document.getElementById('payee_address1')), {
        types: ['geocode']
    });
    // When the user selects an address from the dropdown,
    // populate the address fields in the form.
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        fillInAddress();
    });
}

// [START region_fillform]
function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    document.getElementById("latitude").value = place.geometry.location.lat();
    document.getElementById("longitude").value = place.geometry.location.lng();

    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
	fill_payee_address();
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = new google.maps.LatLng(
            position.coords.latitude, position.coords.longitude);

            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;

            autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
        });
    }
}

initialize();
// [END region_geolocation]
});//]]> 
//<![CDATA[
<?php
$payee = $conn->query("SELECT DISTINCT payee FROM accounting_checks ORDER BY payee");
while($datpayee = $payee->fetch_assoc())
{
	$payee_list.="\"".str_replace("\"","",$datpayee["payee"])."\",";
}
$payee_list=trim($payee_list, ',');
?>
$(function() {
    var availablepayee = [
		<?=$payee_list;?>
    ];
    $("#payee").autocomplete({
      source: availablepayee
    });
});
<?php
$payee = $conn->query("SELECT DISTINCT payee_name FROM accounting_checks ORDER BY payee_name");
while($datpayee = $payee->fetch_assoc())
{
	$payee_name_list.="\"".str_replace("\"","",$datpayee["payee_name"])."\",";
}
$payee_name_list=trim($payee_name_list, ',');
?>
$(function() {
    var availablepayee_name = [
		<?=$payee_name_list;?>
    ];
    $("#payee_name2").autocomplete({
      source: availablepayee_name
    });
});
</script>

<title>Check</title>
<style type="text/css">
body {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
}
#main_check {
	position:absolute;
	left:9px;
	top:10px;
	width:870px;
	height:360px;
	z-index:1;
	border-color:#3e566a;
	border-style: ridge;
	background-color: #F0F0F0;
}
#company_div {
	position: absolute;
	left: 62px;
	top: 26px;
	width: 150px;
	height: 64px;
	z-index: 2;
}
#logo_image_div {
	position: absolute;
	left: 288px;
	top: 26px;
	width: 170px;
	height: 64px;
	z-index: 3;
}
#company_bank_account_div {
	position: absolute;
	left: 529px;
	top: 26px;
	width: 143px;
	height: 64px;
	z-index: 4;
}
#check_number_div {
	position: absolute;
	left: 718px;
	top: 24px;
	width: 101px;
	height: 20px;
	z-index: 5;
}
#transit_code_div {
	position: absolute;
	left: 767px;
	top: 46px;
	width: 72px;
	height: 20px;
	z-index: 6;
	font-size: 10px
}
#check_date_div {
	position: absolute;
	left: 719px;
	top: 71px;
	width: 101px;
	height: 20px;
	z-index: 7;
}
#payee_div {
	position:absolute;
	left:24px;
	top:118px;
	width:133px;
	height:26px;
	z-index:8;
}
#payee_div {
	position: absolute;
	left: 171px;
	top: 105px;
	width: 519px;
	height: 22px;
	z-index: 9;
}
#payee_address1_div {
	position: absolute;
	left: 120px;
	top: 205px;
	width: 574px;
	height: 22px;
	z-index: 12;
}
#payee_address2_div {
	position: absolute;
	left: 120px;
	top: 235px;
	width: 574px;
	height: 19px;
	z-index: 13;
}
#payee_address3_div {
	position: absolute;
	left: 120px;
	top: 266px;
	width: 574px;
	height: 18px;
	z-index: 14;
}
#check_amount_div {
	position: absolute;
	left: 719px;
	top: 105px;
	width: 101px;
	height: 22px;
	z-index: 9;
}
#check_amount_letter_div {
	position: absolute;
	left: 24px;
	top: 139px;
	width: 665px;
	height: 25px;
	z-index: 10;
}
#dollars_div {
	position: absolute;
	left: 700px;
	top: 145px;
	width: 50px;
	height: 15px;
	z-index: 12;
}
#payee_name_address_div {
	position:absolute;
	left:62px;
	top:197px;
	width:143px;
	height:64px;
	z-index:11;
}
#memo_title_div {
	position: absolute;
	left: 24px;
	top: 306px;
	width: 41px;
	height: 15px;
	z-index: 14;
}
#memo_div {
	position: absolute;
	left: 87px;
	top: 304px;
	width: 321px;
	height: 18px;
	z-index: 15;
}
#signature_image_div {
	position: absolute;
	left: 572px;
	top: 303px;
	width: 229px;
	height: 25px;
	z-index: 16;
	text-align: center;
}
@font-face {
    font-family: 'MICR Encoding';
	src: url('fonts/micrenc.ttf');
}

#account_number_div {
	position: absolute;
	left: 118px;
	top: 336px;
	width: 604px;
	height: 20px;
	z-index: 17;
	font-size: 24px;
	font-family: 'MICR Encoding';
	padding-top: 5px;
}
#payee_name_title_div {
	position: absolute;
	left: 24px;
	top: 179px;
	width: 92px;
	height: 15px;
	z-index: 18;
}
#payee_address_title_div {
	position: absolute;
	left: 24px;
	top: 208px;
	width: 92px;
	height: 13px;
	z-index: 18;
}
#separator {
	position:absolute;
	left:9px;
	top:382px;
	width:870px;
	height:2px;
	z-index:19;
}
#payee_name_div {
	position: absolute;
	left: 120px;
	top: 175px;
	width: 574px;
	height: 22px;
	z-index: 9;
}
#payee_title_div {
	position: absolute;
	left: 24px;
	top: 109px;
	width: 136px;
	height: 15px;
	z-index: 20;
}
#apDiv2 {
	position:absolute;
	left:9px;
	top:396px;
	width:870px;
	height:80px;
	z-index:21;
}
#apDiv1 {
	position: absolute;
	left: 9px;
	top: 14px;
	width: 43px;
	height: 18px;
	z-index: 22;
}
#apDiv3 {
	position: absolute;
	left: 59px;
	top: 11px;
	width: 800px;
	height: 23px;
	z-index: 16;
}
#apDiv4 {
	position: absolute;
	left: 9px;
	top: 48px;
	width: 43px;
	height: 18px;
	z-index: 24;
}
#apDiv5 {
	position: absolute;
	left: 59px;
	top: 45px;
	width: 800px;
	height: 23px;
	z-index: 17;
}
#apDiv6 {
	position: absolute;
	left: 700px;
	top: 109px;
	width: 12px;
	height: 16px;
	z-index: 26;
}
#apDiv7 {
	position: absolute;
	left: 512px;
	top: 330px;
	width: 350px;
	height: 1px;
	z-index: 27;
	background-color: #000000;
}
#apDiv8 {
	position: absolute;
	left: 72px;
	top: 330px;
	width: 350px;
	height: 1px;
	z-index: 27;
	background-color: #000000;
}
hr {
position:absolute;
	left:9px;
	top:382px;
  border:none;
  border-top:1px dotted #000;
  color:#fff;
  background-color:#fff;
  height:1px;
  width:870px;
}
#save_div {
	position: absolute;
	left: 9px;
	top: 478px;
	width: 870px;
	height: 25px;
	text-align:right;
	z-index: 21;
}

</style>
<?
$id_check="";
$action="";
$check_number="";
$id_bank_info="";
$check_date=date("m/d/Y");
$payee="";
$check_amount="";
$check_amount_letter="";
$payee_name2="";
$payee_address1="";
$payee_address2="";
$payee_address3="";
$memo="";
$note1="";
$note2="";
if(isset($_PG["action"]))
	$action=$_PG["action"];
if(isset($_PG["id_check"]))
{	
	$id_check=$_PG["id_check"];
	$sql="SELECT * FROM accounting_checks WHERE id_check=".$id_check;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$id_bank_info = $row["id_bank_info"];
	if($action!="duplicate")
		$check_date = BD2PHP($row["check_date"]);
	$payee = $row["payee"];
	$check_amount = number_format($row["check_amount"],2,".","");
	$check_amount_letter = $row["check_amount_letter"];
	$payee_name2 = $row["payee_name"];
	$payee_address1 = $row["payee_address1"];
	$payee_address2 = $row["payee_address2"];
	$payee_address3 = $row["payee_address3"];
	$memo = $row["memo"];
	$note1 = $row["note1"];
	$note2 = $row["note2"];
	if($action=="edit_check")
	{
		$check_number = $row["check_number"];
		$check_number_div = "<strong>No.</strong> ".$row["check_number"];
	}
	elseif($action=="duplicate")
	{
		$sql="SELECT MAX(check_number) AS check_number FROM accounting_checks INNER JOIN accounting_bank_info ON accounting_checks.id_bank_info = accounting_bank_info.id_bank_info WHERE accounting_bank_info.default_account=1";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$check_number = $row["check_number"]+1;
		$check_number_div = "<strong>No.</strong> ".$row["check_number"]+1;
	}
}
/*
if(isset($_PG["id_expense"]))
{
	//newcheck_exp
	$sql="SELECT * FROM accounting_bank_info WHERE default_account=1";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$id_bank_info = $row["id_bank_info"];
	
	$sql="SELECT inventory_expenses.vin, inventory_expenses.expense_type, inventory_expenses.description, inventory_expenses.cost, supplier.name, supplier.address FROM inventory_expenses LEFT JOIN supplier ON inventory_expenses.id_supplier=supplier.id_supplier WHERE inventory_expenses.id_expense=".$_PG["id_expense"];	
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	$payee = $row["name"];
	$check_amount = number_format($row["cost"],2,".","");
	$payee_name2 = $row["name"];
	$address=explode(",",$row["address"]);
	$payee_address1 = $address[0];
	$payee_address2 = $address[1].", ".$address[2];
	$payee_address3 =  $address[3];
	$memo = $row["expense_type"];
	$note1 = $row["description"];
	$vin = $row["vin"];	
	?>
    <script language="javascript" type="text/javascript">
		convert_number_letter(<?=$row["cost"]?>);
	</script>
    <?
}*/
?>
<input type="hidden" id="action" name="action" value="<?=$action?>"/>
<input type="hidden" id="id_check" name="id_check" value="<?=$id_check?>"/>
<input type="hidden" id="check_number" name="check_number" value="<?=$check_number?>"/>
<input type="hidden" id="id_bank_info" name="id_bank_info" value="<?=$id_bank_info?>"/>
<script language="javascript" type="text/javascript">
$("#check_amount").maskMoney({thousands:'', decimal:'.'});
$("#check_date").datepicker();
<? if($action=="new"){?>
	get_info_bankaccount(window.parent.document.getElementById("id_bank_infoH").value);
<? }else{?>
	get_info_bankaccount(<?=$id_bank_info?>);
<? }?>
</script>
<div id="msg_check" style="display:none"></div>
<div id="main_check"></div>
<div id="apDiv8"></div>
<div id="company_div"></div>
<div id="logo_image_div"></div>
<div id="company_bank_account_div"></div>
<div id="check_number_div"><?=$check_number_div;?></div>
<div id="transit_code_div"></div>
<div id="check_date_div"><input type='text' class="form-control" id="check_date" name="check_date" value="<?=$check_date;?>" placeholder="MM/DD/YYYY" style="width:95px; height:26px;position: relative; z-index: 100000;" /></div>
<div id="payee_title_div"><strong>Pay of the Order of</strong></div>
<div id="payee_div"><input type="text" class="form-control" id="payee" name="payee" aria-describedby="basic-addon1" style="width:520px; height:26px" value="<?=$payee;?>"/></div>
<div id="check_amount_div"><input type="text" class="form-control" placeholder="" id="check_amount" name="check_amount" aria-describedby="basic-addon1" style="width:95px; height:26px" onKeyPress="convert_number_letter(this.value);" onChange="convert_number_letter(this.value);" onBlur="convert_number_letter(this.value);" value="<?=$check_amount;?>"/></div>
<div id="check_amount_letter_div"><input type="text" class="form-control" placeholder="" id="check_amount_letter" name="check_amount_letter" aria-describedby="basic-addon1" style="width:667px; height:26px" value="<?=$check_amount_letter;?>" /></div>
<div id="dollars_div"><strong>Dollars</strong></div>
<div id="payee_name_title_div"><strong>Payee Name</strong></div>
<div id="payee_name_div"><input type="text" class="form-control" placeholder="" id="payee_name2" name="payee_name2" aria-describedby="basic-addon1" style="width:400px; height:26px" value="<?=$payee_name2;?>" /></div>
<div id="payee_address_title_div"><strong>Payee Address</strong></div>
<div id="payee_address1_div"><input type="text" class="form-control" placeholder="" id="payee_address1" name="payee_address1" aria-describedby="basic-addon1" style="width:400px; height:26px" onBlur="fill_payee_address();" onChange="fill_payee_address();" value="<?=$payee_address1;?>" /></div>
<div id="apDiv6"><strong>$</strong></div>
<div id="apDiv7"></div>
<input id="street_number" style="display:none;">
<input id="route" style="display:none;">
<input id="locality" style="display:none;">
<input type="text" id="latitude" style="display:none;">
<input type="text" id="longitude" style="display:none;">   
<div id="payee_address2_div"><input type="text" class="form-control" placeholder="" id="payee_address2" name="payee_address2" aria-describedby="basic-addon1" style="width:400px; height:26px" value="<?=$payee_address2;?>" /></div>
<div id="payee_address3_div"><input type="text" class="form-control" placeholder="" id="payee_address3" name="payee_address3" aria-describedby="basic-addon1" style="width:400px; height:26px" value="<?=$payee_address3;?>" /></div>
<div id="memo_title_div"><strong>Memo:</strong></div>
<div id="memo_div"><input type="text" class="form-control" placeholder="" id="memo" name="memo" aria-describedby="basic-addon1" style="width:320px; height:26px" value="<?=$memo;?>" /></div>
<div id="signature_image_div"></div>
<div id="account_number_div"></div>
<hr></hr>
<div id="apDiv2">
<div id="apDiv1"><strong>Note 1:</strong></div>
<div id="apDiv3"><input type="text" class="form-control" placeholder="" id="note1" name="note1" aria-describedby="basic-addon1" style="width:798px; height:26px" value="<?=$note1;?>" /></div>
<div id="apDiv4"><strong>Note 2:</strong></div>
<div id="apDiv5"><input type="text" class="form-control" placeholder="" id="note2" name="note2" aria-describedby="basic-addon1" style="width:798px; height:26px" value="<?=$note2;?>" /></div>
</div>
<div id="save_div"><button type="button" class="btn btn-primary custom-button-width" id="addcheck" name="addcheck" onClick="save_check()"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;<?=$button_title?></button></div>
<div/>
