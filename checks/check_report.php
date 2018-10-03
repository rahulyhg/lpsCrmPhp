<? 
//session_start();
include ("db.php"); loginCheck();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Customers</title>
</head>
<script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/mask/jquery.maskedinput.js" ></script>
<script type="text/javascript" src="js/mask/jquery_maskmoney/jquery.maskMoney.js" ></script>
<script type="text/javascript" src="js/json/json.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="js/jqueryeditor/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<script language="javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

<script type="text/javascript" src="js/bootbox.min.js"></script>
<script language="javascript" src="js/check_report.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="js/jqueryeditor/jquery-te-1.4.0.css">

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" type="text/css" media="screen" />

<style>
.dataTables_filter { display: none; }
</style>
<body>
<div class="panel panel-primary centre" id="adv_search" style="margin:15px;">
    <div class="panel-heading">Check Report</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">
                        <div style="width:120px;"> Bank Account:</div></span>
                        <select class="form-control" id="id_bank_info" name="id_bank_info" title="Select Bank Account">
                        <?
                            $sql="SELECT * FROM accounting_bank_info ORDER BY bank_name";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc())
                            {
                                if($row["default_account"]==1)
                                {
                                    echo "<option value='".$row["id_bank_info"]."' selected>".$row["bank_name"]." - ".$row["account_number"]."</option>";
                                    $id_bank_info=$row["id_bank_info"];
                                }
                                else
                                    echo "<option value='".$row["id_bank_info"]."'>".$row["bank_name"]." - ".$row["account_number"]."</option>";
                            }
                        ?>
                        </select><input type="hidden" id="id_bank_infoH" value="<?=$id_bank_info?>" />
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="input-group">
                      <input class="form-control" type="text" id="text_date_from" placeholder="Start Date" style="position: relative; z-index: 100000;" value="<?=date("m/d/Y")?>" /><div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                    </div>
                </div>                                                  
                <div class="col-xs-2">
                    <div class="input-group">
                      <input class="form-control" type="text" id="text_date_to" placeholder="End Date" style="position: relative; z-index: 100000;"  value="<?=date("m/d/Y")?>"/><div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                    </div>
                </div>
                <div class="col-xs-1">
                    <button type="button" class="btn btn-primary" onClick="getcheck_report();"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Get report</button>
                </div>
                <div class="col-xs-1">
                     <a href="index.php"><button type="button" class="btn btn-success" id="btn_back" name="btn_back" title="Back to Menu"><span class="glyphicon glyphicon-chevron-left"></span> Back</button></a>
                </div>
            </div>
            <hr class="centre" style="width:100%; height:1px">
            <div class="centre" style=" width:100%;height:379px;overflow:auto; overflow-style:auto" id="main_div">
                <div id="checkreport_grid"></div>
            </div>
		</div>
	</div>
</div>
</body>
</html>