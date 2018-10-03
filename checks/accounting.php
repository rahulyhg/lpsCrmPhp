<? 
//session_start();
include ("db.php"); loginCheck();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accounting</title>
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
<script type="text/javascript" src="js/bootbox.min.js"></script>
<script type="text/javascript" src="js/accounting.js"></script>
<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="js/jqueryeditor/jquery-te-1.4.0.css">
<style>
/*.dataTables_filter { display: none; }*/
</style>
<body>
<input type="hidden" id="id_bank_info" name="id_bank_info" value=""/>
    <div class="panel panel-default centre" style="width:99%;background-color: #F7F7F7; padding:5px; margin-left:4px; margin-top:5px" id="adv_search">
        <div class="row">
            <div class="col-md-8">
                <a id="newcheck" href="check.php?action=new" data-fancybox-type="iframe"><button type="button" class="btn btn-primary" id="btn_newcheck" name="btn_newcheck" title="New Check"><span class="glyphicon glyphicon-file"></span> New</button></a>&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" id="deletecheck" name="deletecheck" onClick="delete_check();" title="Delete Check"><span class="glyphicon glyphicon glyphicon-remove"></span> Delete</button>&nbsp;&nbsp;
                <a id="editcheck" onClick="show_edit();" data-fancybox-type="iframe"><button type="button" class="btn btn-primary" id="btn_editcheck" name="btn_editcheck" title="Edit Check"><span class="glyphicon glyphicon-pencil"></span> Edit</button></a>&nbsp;&nbsp;
                <a id="duplicatecheck" onClick="show_duplicate();" data-fancybox-type="iframe"><button type="button" class="btn btn-primary" id="btn_duplicatecheck" name="btn_duplicatecheck" title="Duplicate Check"><span class="glyphicon glyphicon-duplicate"></span> Duplicate</button></a>&nbsp;&nbsp;
                <a id="printcheck" onClick="print_check(0);" data-fancybox-type="iframe">
                <button type="button" class="btn btn-primary" id="btn_printcheck" name="btn_printcheck" title="Print Check"><span class="glyphicon glyphicon-print"></span> Print</button></a>&nbsp;&nbsp;
				<a id="printcheck_blank" onClick="print_check(1);" data-fancybox-type="iframe">
                <button type="button" class="btn btn-primary" id="btn_printcheck" name="btn_printcheck" title="Print Blank Check"><span class="glyphicon glyphicon-print"></span> Blank check</button></a>&nbsp;&nbsp;
                <a href="index.php">
                <button type="button" class="btn btn-success" id="btn_back" name="btn_back" title="Back to Menu"><span class="glyphicon glyphicon-chevron-left"></span> Back</button></a>
				<a id="printcheck_blank2" data-fancybox-type="iframe"></a>
            </div>
            <div class="col-md-4" style="text-align:right">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">
                    <div style="width:120px;"> Bank Account:</div></span>
                    <select class="form-control" id="id_bank_info" name="id_bank_info" title="Select Bank Account" onChange="getcheck_list(this.value);">
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
        </div>
    </div>
    <div id="msg" style="display:none"></div>
    <div class="centre" style="width:99%; height:490px;overflow:auto; overflow-style:auto; margin-left:4px" id="main_div">
        <div id="accounting_grid" class="centre" style="width:97%;margin-left:4px"></div>
    </div>
</body>
</html>