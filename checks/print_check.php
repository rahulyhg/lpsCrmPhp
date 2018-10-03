<?php

//ini_set('error_reporting', E_ALL);
include ("db.php");
loginCheck();
require_once("functions.php");
ini_set("allow_url_fopen", 1);
require_once("dompdf/autoload.inc.php");

use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Dompdf\Options;
use FontLib\Font;

$options = new Options();
$options->set('defaultFont', 'Courier');
$options->set("isPhpEnabled", true);

$pdf_file = new DOMPDF($options);
ob_start();

$html_check = '';
$id_check = "";
$action = "";
$check_number = "blank";
$id_bank_info = isset($_GET["id_bank_info"]) ? $_GET["id_bank_info"] : "";
$check_date = date("m/d/Y");
$payee = "";
$check_amount = "";
$check_amount_letter = "";
$payee_name2 = "";
$payee_address1 = "";
$payee_address2 = "";
$payee_address3 = "";
$memo = "";
$note1 = "";
$note2 = "";
$file_name = "";
$signature_image_div = "";
$logo_image_div = "";
if (isset($_PG["action"]))
    $action = $_PG["action"];

$id_check = $_PG["id_check"];
$path = "images/"; // upload directory
$sql = "SELECT accounting_bank_info.* FROM accounting_bank_info WHERE accounting_bank_info.id_bank_info=$id_bank_info";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$print_routing_symbol = $row["print_routing_symbol"];
$routing_number = $row['routing_number'];
$account_number = $row['account_number'];

$company_bank_account_div = $row['bank_name'] . "<br>" . $row['information1'] . "<br>" . $row['information2'] . ", " . $row['information3'];
$company_div = $row['company_name'] . "<br>" . $row['company_address'] . "<br>" . $row['company_city'] . ", " . $row['company_state'] . ", " . $row['company_zipcode'] . "<br>" . $row['company_phone'];

$transit_code_div = $row['transit_code'];

$signature_image_div = "";
if ($row['signature_image_show'] == 1)
    $signature_image_div = "<img src='" . $path . $row['signature_image'] . "' width='170' height='40'/>";
$logo_image_div = "";
if ($row['logo_image'] != "") {
    $logo_image = $path . $row['logo_image'];
    list($width, $height) = getimagesize("$logo_image");
    $ratio = $width / $height;
    if ($ratio > 2) {
        $width = 100;
        $height = round(100 / $ratio);
    } else {
        $width = round(30 * $ratio, 0);
        $height = 30;
    }
}
if ($row['logo_image_show'] && $logo_image != "") {
    $logo_image_div = "<img src='" . $path . $row['logo_image'] . "' width='$width' height='$height'/>";
    $company_bank_account_div = "";
} else
    $logo_image_div = $company_bank_account_div;

$page = array();
$count_page = 0;
//echo $id_check."<br>";
$check_array = explode(",", $_PG["id_check"]);
//print_r($id_check);
$html_header = '<html><head>
		  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		  <link rel="stylesheet" href="css/bootstrap.css">
		  <link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
		  <title>Check</title>
		  <style type="text/css">
		  @page { margin: 0px; }
		  body {
			  font-family: "Helvetica";
			  margin: 0px;
			  font-size: 12px;
			  /*font-family:Georgia, "Times New Roman", Times, serif;*/
		  }
		  #main_check {
			position:fixed;
			left:9px;
			top:14px;
			width:800px;
			height:304px;
			z-index:1;
			border-color:#FFFFFF;
			background-color: #FFFFFF;
			font-size: 11px;
			  }
			  #dealer_div {
			position:fixed;
			left: 46px;
			top: 25px;
			width: 150px;
			height: 58px;
			z-index: 2;
			font-size: 11px;
			  }
			  /*#logo_image_div {
			position:fixed;
			left: 272px;
			top: 25px;
			width: 170px;
			height: 58px;
			z-index: 3;
			  }*/
			  #logo_image_div {
			  position:fixed;
			left: 443px;
			top: 25px;
			width: 143px;
			height: 58px;
			z-index: 4;
			font-size:11px
			}
			  #dealer_bank_account_div {
			position:fixed;
			left: 513px;
			top: 25px;
			width: 143px;
			height: 58px;
			z-index: 4;
			font-size:11px
			  }
			  #check_number_div {
			position:fixed;
			left: 702px;
			top: 23px;
			width: 101px;
			height: 18px;
			z-index: 5;
			font-size:11px
			  }
			  #transit_code_div {
			position:fixed;
			left: 751px;
			top: 44px;
			width: 72px;
			height: 18px;
			z-index: 6;
			font-size: 8px
			  }
			  #check_date_div {
			position:fixed;
			left: 703px;
			top: 65px;
			width: 101px;
			height: 18px;
			z-index: 7;
			font-size:11px
			  }
			  #payee_div {
				  position:fixed;
				  left:24px;
				  top:118px;
				  width:133px;
				  height:26px;
				  z-index:8;
				  font-size:12px
			  }
			  #payee_div {
			position:fixed;
			left: 117px;
			top: 103px;
			width: 562px;
			height: 18px;
			z-index: 9;
			font-size:11px
			  }
			  #payee_address1_div {
			position:fixed;
			left: 123px;
			top: 184px;
			width: 574px;
			height: 18px;
			z-index: 12;
			font-size:11px
			  }
			  #payee_address2_div {
			position:fixed;
			left: 123px;
			top: 204px;
			width: 574px;
			height: 18px;
			z-index: 13;
			font-size:11px
			  }
			  #payee_address3_div {
			position:fixed;
			left: 123px;
			top: 224px;
			width: 574px;
			height: 18px;
			z-index: 14;
			font-size:11px
			  }
			  #check_amount_div {
			position:fixed;
			left: 717px;
			top: 100px;
			width: 101px;
			height: 18px;
			z-index: 9;
			font-size:12px
			  }
			  #check_amount_letter_div {
			position:fixed;
			left: 27px;
			top: 135px;
			width: 665px;
			height: 18px;
			z-index: 10;
			font-size:12px
			  }
			  #dollars_div {
			position:fixed;
			left: 703px;
			top: 135px;
			width: 50px;
			height: 15px;
			z-index: 12;
			font-size:11px
			  }
			  #payee_name_address_div {
				  position:fixed;
				  left:62px;
				  top:197px;
				  width:143px;
				  height:64px;
				  z-index:11;
				  font-size:11px
			  }
			  #memo_title_div {
			position:fixed;
			left: 27px;
			top: 249px;
			width: 41px;
			height: 15px;
			z-index: 14;
			font-size:11px
			  }
			  #memo_div {
			position:fixed;
			left: 90px;
			top: 248px;
			width: 300px;
			height: 18px;
			z-index: 15;
			font-size:11px
			  }
			  #signature_image_div {
			position:fixed;
			left: 499px;
			top: 226px;
			width: 229px;
			height: 33px;
			z-index: 16;
			text-align: center;
			  }
			#account_number_div {
			position:fixed;
			left: 171px;
			top: 292px;
			width: 604px;
			height: 20px;
			z-index: 17;
			font-size: 24px;
			padding-top: 5px;
			  }
			  #payee_name_title_div {
			position:fixed;
			left: 27px;
			top: 162px;
			width: 92px;
			height: 15px;
			z-index: 18;
			font-size:11px
			  }
			  #payee_address_title_div {
			position:fixed;
			left: 27px;
			top: 184px;
			width: 92px;
			height: 13px;
			z-index: 18;
			font-size:11px
			  }
			  #separator {
				  position:fixed;
				  left:9px;
				  top:382px;
				  width:870px;
				  height:2px;
				  z-index:19;
			  }
			  #payee_name_div {
			position:fixed;
			left: 123px;
			top: 162px;
			width: 574px;
			height: 18px;
			z-index: 9;
			font-size:11px
			  }
			  #payee_title_div {
			position:fixed;
			left: 27px;
			top: 95px;
			width: 74px;
			height: 15px;
			z-index: 20;
			font-size:11px
			  }
			  #apDiv2 {
			position:fixed;
			left:13px;
			top:403px;
			width:850px;
			height:80px;
			z-index:21;
			font-size:11px
			  }
			  #apDiv1 {
			position:fixed;
			left: 18px;
			top: 370px;
			width: 60px;
			height: 18px;
			z-index: 22;
			font-size:11px
			  }
			  #apDiv3 {
			position:fixed;
			left: 59px;
			top: 11px;
			width: 782px;
			height: 23px;
			z-index: 16;
			font-size:11px
			  }
			  #apDiv4 {
			position:fixed;
			left: 18px;
			top: 395px;
			width: 53px;
			height: 18px;
			z-index: 24;
			font-size:11px
			  }
			  #note1_title {
			position:fixed;
			left: 18px;
			top: 423px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note1 {
			position:fixed;
			left: 82px;
			top: 423px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note2_title {
			position:fixed;
			left: 18px;
			top: 451px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note2 {
			position:fixed;
			left: 83px;
			top: 451px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note12_title {
			position:fixed;
			left: 18px;
			top: 736px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note12 {
			position:fixed;
			left: 82px;
			top: 736px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note22_title {
			position:fixed;
			left: 18px;
			top: 764px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #note22 {
			position:fixed;
			left: 83px;
			top: 764px;
			width: 53px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #apDiv5 {
			position:fixed;
			left: 73px;
			top: 396px;
			width: 250px;
			height: 18px;
			z-index: 17;
			font-size:11px
			  }
			  #apDiv6 {
			position:fixed;
			left: 698px;
			top: 100px;
			width: 12px;
			height: 16px;
			z-index: 26;
			font-size:11px
			  }
			  #apDiv7 {
			position:fixed;
			left: 447px;
			top: 263px;
			width: 330px;
			height: 1px;
			z-index: 27;
			background-color: #000000;
			font-size:11px
			  }
			  #apDiv8 {
			position:fixed;
			left: 75px;
			top: 264px;
			width: 330px;
			height: 1px;
			z-index: 27;
			background-color: #000000;
			font-size:11px
			  }
			  hr {
			position:fixed;
			left:11px;
			top:339px;
			border:none;
			border-top:1px dotted #000;
			color:#fff;
			background-color:#fff;
			height:1px;
			width:850px;
			  }
			  #save_div {
				  position:fixed;
				  left: 9px;
				  top: 491px;
				  width: 870px;
				  height: 25px;
				  text-align:right;
				  z-index: 21;
			  }
			  #apDiv9 {
			position:fixed;
			left:93px;
			top:119px;
			width:591px;
			height:1px;
			z-index:28;
			background-color: #000000;
			font-size:11px
		}
			  #apDiv10 {
			position:fixed;
			left:27px;
			top:151px;
			width:663px;
			height:1px;
			z-index:29;
			background-color: #000000;
			font-size:11px
		}
			  #apDiv11 {
			position:fixed;
			left:346px;
			top:370px;
			width:53px;
			height:18px;
			z-index:30;
			font-size:11px
		}
			  #apDiv12 {
			position:fixed;
			left:408px;
			top:370px;
			width:87px;
			height:18px;
			z-index:31;
			font-size:11px
		}
			  #apDiv13 {
			position:fixed;
			left:650px;
			top:370px;
			width:39px;
			height:18px;
			z-index:32;
			font-size:11px
		}
			  #apDiv14 {
			position:fixed;
			left:694px;
			top:370px;
			width:61px;
			height:18px;
			z-index:33;
			font-size:11px
		}
			  #apDiv15 {
			position:fixed;
			left:18px;
			top:683px;
			width:60px;
			height:18px;
			z-index:34;
			font-size:11px
		}
			  #apDiv16 {
			position:fixed;
			left:18px;
			top:708px;
			width:53px;
			height:18px;
			z-index:35;
			font-size:11px
		}
			  #apDiv17 {
			position:fixed;
			left:73px;
			top:709px;
			width:250px;
			height:18px;
			z-index:36;
			font-size:11px
		}
			  #apDiv18 {
			position:fixed;
			left:346px;
			top:687px;
			width:53px;
			height:18px;
			z-index:37;
			font-size:11px
		}
			  #apDiv19 {
			position:fixed;
			left:408px;
			top:687px;
			width:87px;
			height:18px;
			z-index:38;
			font-size:11px
		}
			  #apDiv20 {
			position:fixed;
			left:650px;
			top:680px;
			width:39px;
			height:18px;
			z-index:39;
			font-size:11px
		}
			  #apDiv21 {
			position:fixed;
			left:694px;
			top:680px;
			width:61px;
			height:18px;
			z-index:40;
			font-size:11px
		}
		@page check {
		  size: letter portrait;
		}		
		.check_page {
			page: check;
			page-break-after: always;
		}
		</style>
		</head><body>';
$html_footer = '</body></html>';
$html_check = "";
$count_page = count($check_array);
$y = 0;
foreach ($check_array as $checks) {
    $det_check = explode("_", $checks);
    $id_check = $det_check[2];
    $sql = "SELECT * FROM accounting_checks WHERE id_check=" . $id_check;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $id_bank_info = $row["id_bank_info"];
    if ($action != "duplicate")
        $check_date = BD2PHP($row["check_date"]);
    $payee = $row["payee"];
    $check_amount = $row["check_amount"] != "" ? number_format($row["check_amount"], 2, ".", "") : "";
    $check_amount_letter = $row["check_amount_letter"];
    $payee_name2 = $row["payee_name"];
    $payee_address1 = $row["payee_address1"];
    $payee_address2 = $row["payee_address2"];
    $payee_address3 = $row["payee_address3"];
    $memo = $row["memo"];
    $note1 = $row["note1"];
    $note2 = $row["note2"];
    $check_number = $row["check_number"];
    $check_number_div = "<strong>No.</strong> " . $row["check_number"];
    $payee_file = str_replace(" ", "", $payee);

    $file_name = $check_number . "-" . $id_check . "_" . $payee_file . ".pdf";

    $check_number_array = array_map('intval', str_split($check_number));
    $count = 6 - count($check_number_array);
    $zeros = array();
    for ($i = 0; $i < $count; $i++) {
        $zeros[$i] = "0";
    }
    $check_number_array = array_merge($zeros, $check_number_array);
    $check_number_print = "";
    for ($i = 0; $i < count($check_number_array); $i++) {
        $check_number_print .= $check_number_array[$i];
    }
    $check_number_div = "<strong>No.</strong> " . $check_number;


    if ($print_routing_symbol == 1)
        $account_number_div = "A" . $routing_number . "A  " . $account_number . "C  " . $check_number_print;
    else
        $account_number_div = " " . $routing_number . "A  " . $account_number . "C  " . $check_number_print;

    $url_image = $path . str_replace(" ", "", $account_number_div) . ".png";
    //die($url_image);
    $account_image = "<img src='" . $url_image . "'/>";
    get_account_img(str_replace(" ", "", $account_number_div));
    //die($url_image);
    $class_page = '';
    if ($y < $count_page - 1)
        $class_page = 'class="check_page"';
    $html_check .= '<div ' . $class_page . '>
			    <div id="main_check"></div>
			    <div id="apDiv8"></div>
				<div id="dealer_div">' . $company_div . '</div>
				<div id="logo_image_div">' . $logo_image_div . '</div>
				<!--<div id="dealer_bank_account_div">' . $company_bank_account_div . '</div>-->
				<div id="check_number_div">' . $check_number_div . '</div>
				<div id="transit_code_div">' . $transit_code_div . '</div>
				<div id="check_date_div">' . $check_date . '</div>
				<div id="payee_title_div"><strong>Pay To The Order of</strong></div>
			    <div id="payee_div">' . $payee . '</div>
			    <div id="check_amount_div">' . $check_amount . '</div>
			    <div id="check_amount_letter_div">' . $check_amount_letter . '</div>
				<div id="dollars_div"><strong>Dollars</strong></div>
				<div id="payee_name_title_div"><strong>Payee Name</strong></div>
			    <div id="payee_name_div">' . $payee_name2 . '</div>
			    <div id="payee_address_title_div"><strong>Payee Address</strong></div>
			    <div id="payee_address1_div">' . $payee_address1 . '</div>
				<div id="apDiv6"><strong>$</strong></div>
				<div id="apDiv7"></div>
				<div id="payee_address2_div">' . $payee_address2 . '</div>
			    <div id="payee_address3_div">' . $payee_address3 . '</div>
			    <div id="memo_title_div"><strong>Memo:</strong></div>
			    <div id="memo_div">' . $memo . '</div>
			    <div id="signature_image_div">' . $signature_image_div . '</div>
			    <div id="account_number_div">' . $account_image . '</div>
			    <hr>
			    <div id="apDiv9"></div>
				<div id="apDiv10"></div>
			    <div id="apDiv11"><strong>Amount:</strong></div>
			    <div id="apDiv12">' . $check_amount . '</div>
			    <div id="apDiv13"><strong>Date:</strong></div>
			    <div id="apDiv14">' . $check_date . '</div>
				<div id="apDiv1"><strong>' . $check_number . '</strong></div>
				<div id="apDiv4"><strong>Pay to:</strong></div>
				<div id="note1_title"><strong>Note1:</strong></div>
				<div id="note1"><strong>' . $note1 . '</strong></div>
				<div id="note2_title"><strong>Note2:</strong></div>
				<div id="note2"><strong>' . $note2 . '</strong></div>
				<div id="apDiv5">' . $payee . '</div>
			    <div id="apDiv15"><strong>' . $check_number . '</strong></div>
			    <div id="apDiv16"><strong>Pay to:</strong></div>
				<div id="apDiv17">' . $payee . '</div>
				<div id="apDiv18"><strong>Amount:</strong></div>
				<div id="apDiv19">' . $check_amount . '</div>
				<div id="apDiv20"><strong>Date:</strong></div>
				<div id="apDiv21">' . $check_date . '</div>
				<div id="note12_title"><strong>Note1:</strong></div>
				<div id="note12"><strong>' . $note1 . '</strong></div>
				<div id="note22_title"><strong>Note2:</strong></div>
				<div id="note22"><strong>' . $note2 . '</strong></div>
				</div>';
    $y++;
}
//die(print_r($page));
$html_check = utf8_decode($html_check);
ob_end_clean();
/* foreach($page as $page_actual)
  {
  if($page_actual!="")
  {
  $page_actual=$html_header.$page_actual.$html_footer;
  $pdf_file->loadHtml($page_actual);
  //echo $page_actual;
  }
  //echo $page_actual;
  } */
$page_actual = $html_header . $html_check . $html_footer;
//$page_actual = preg_replace('/>\s+</', "><", $page_actual);
//$page_actual=$html_check;
//die($page_actual);
$pdf_file->loadHtml($page_actual);
$pdf_file->setPaper('letter', 'portrait');
$pdf_file->render();
$pdf_file->stream($file_name, array("Attachment" => false));
?>