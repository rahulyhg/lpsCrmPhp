<? 
//session_start();
include ("db.php"); loginCheck();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>CRM check module</title>
    </head>
    <script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/mask/jquery.maskedinput.js" ></script>
    <script type="text/javascript" src="js/mask/jquery_maskmoney/jquery.maskMoney.js" ></script>
    <script type="text/javascript" src="js/json/json.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jqueryeditor/jquery-te-1.4.0.min.js" charset="utf-8"></script>

    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
            <link type="text/css" rel="stylesheet" href="js/jqueryeditor/jquery-te-1.4.0.css">
                <link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
                <style>
                    /*.dataTables_filter { display: none; }*/
                </style>
                <body>
                    <div class="centre" style=" width:98%;overflow:auto; overflow-style:auto" id="main_div">
                        <div class="panel panel-default centre" style="width:96%;background-color: #F7F7F7; margin:20px; padding: 5px;" id="adv_search">
                            <div class="row">
                                <div class="col-md-12" style="text-align:center">
                                    <h4>CRM check module</h4>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default centre" style="width:96%;background-color: #FFFFFF; margin:20px; padding: 5px;" id="adv_search">
                            <div class="row">
                                <div class="col-md-12" style="text-align:center">
                                    <a href="accounting.php">
                                        <button type="button" class="btn btn-success" id="btn_checks" name="btn_checks" title="Checks Manage"><span class="glyphicon glyphicon-usd"></span> ChecksManage</button></a>
                                    &nbsp;&nbsp;
                                    <a href="check_report.php">
                                        <button type="button" class="btn btn-success" id="btn_checkreport" name="btn_checkreport" title="Checks Rerport"><span class="glyphicon glyphicon-list-alt"></span> Checks Report</button></a>
                                    &nbsp;&nbsp;
                                    <a href="bank_account_setup.php">
                                        <button type="button" class="btn btn-success" id="btn_bankaccount" name="btn_bankaccount" title="Bank Account Info"><span class="glyphicon glyphicon-list-alt"></span> Bank Account Info</button></a>
                                </div>

                            </div>
                        </div>
                        <div id="msg" style="display:none"></div>
                    </div>
                </body>
                </html>