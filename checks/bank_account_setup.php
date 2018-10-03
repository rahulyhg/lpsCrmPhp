<?PHP
include ("db.php");
loginCheck();
?>
<script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyD4tsJ4QAoKkvKXeBwmBhZIhB3NIShMM8E"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/mask/jquery.maskedinput.js" ></script>
<script type="text/javascript" src="js/mask/jquery_maskmoney/jquery.maskMoney.js" ></script>
<script type="text/javascript" src="js/json/json.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="js/jqueryeditor/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/bootbox.min.js"></script>
<!--<script language="javascript" src="js/address.js"></script>-->
<script language="javascript" src="js/bank_account_setup.js"></script>
<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="js/jqueryeditor/jquery-te-1.4.0.css">

<style>
    .photo {
        width: 300px;
        text-align: center;
    }
    #preview{
        position:absolute;
        border:1px solid #ccc;
        background:#333;
        padding:5px;
        display:none;
        color:#fff;
    }
</style>
<div style="float:left;width:96%; margin-top:10px; margin-left:10px; margin-right:10px;" class="centre">
    <form id="form" method="post" enctype="multipart/form-data" onsubmit="return validate_bankinfo();">
        <input type="hidden" id="id_bank_info" name="id_bank_info" />
        <a name="head_info" id="head_info"></a>
        <div class="panel panel-primary" id="panel_bank_info">
            <div class="panel-heading">Bank Account Setup</div>
            <div class="panel-body">
                <div class="alert alert-danger" id="mandatory" style="display:none; width:99%"></div>
                <div id="msg_bankinfo" style="display:none"></div>
                <!----------------------------------------->
                <div id="form_bank" style="display:">
                    <div class="row">
                        <h4>&nbsp;&nbsp;&nbsp;Company Info</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="company_name_group">
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Company Name:</div></span>
                                <input type="text" class="form-control" placeholder="" value="" id="company_name" name="company_name" aria-describedby="basic-addon1" onblur="clean();"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="company_phone_group">
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Company Phone:</div></span>
                                <input type="text" class="form-control" placeholder="" value="" id="company_phone" name="company_phone" aria-describedby="basic-addon1" onblur="clean();"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="company_address_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Company Address:</div></span>
                                <input type="text" class="form-control" id="autocomplete2" name="company_address" aria-describedby="basic-addon1" onchange="fix_address();" onblur="fix_address();" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="company_city_group">
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Company City:</div></span>
                                <input type="text" class="form-control" placeholder="" value="" id="locality2" name="company_city" aria-describedby="basic-addon1" onblur="clean();"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="company_state_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Company State:</div></span>
                                <input type="text" class="form-control" id="administrative_area_level_12" name="company_state" aria-describedby="basic-addon1" onblur="clean();" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="company_zipcode_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Company ZIP Code:</div></span>
                                <input type="text" class="form-control" id="postal_code2" name="company_zipcode" aria-describedby="basic-addon1" onblur="clean();" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h4>&nbsp;&nbsp;&nbsp;Bank Info</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="bank_name_group">
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Bank Name:</div></span>
                                <input type="text" class="form-control" placeholder="" value="" id="bank_name" name="bank_name" aria-describedby="basic-addon1" onblur="clean();"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="transit_code_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Transit Code:</div></span>
                                <input type="text" class="form-control" id="transit_code" name="transit_code" aria-describedby="basic-addon1" onblur="clean();" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="routing_number_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Routing Number:</div></span>
                                <input type="text" class="form-control" placeholder="" id="routing_number" name="routing_number" aria-describedby="basic-addon1" onblur="validate_bankaccount();" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="account_number_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Bank Account</div></span>
                                <input name="account_number" type="text" class="form-control" id="account_number" placeholder="" onblur="validate_bankaccount();" maxlength="20" aria-describedby="basic-addon1" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="check_number_start_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Current Check No.</div></span>
                                <input type="text" class="form-control" placeholder="" id="check_number_start" name="check_number_start" aria-describedby="basic-addon1" onblur="clean();" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="information1_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Address:</div></span>
                                <input type="text" class="form-control" placeholder="" id="information1" name="information1" aria-describedby="basic-addon1" />
                            </div>
                        </div>                
                    </div>                         
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="signature_image_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Signature Image:</div></span>
                                <input class="form-control" style=" padding:0px; text-align:right;" id="signature_file" type="file" name="signature_file"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" style="margin-right:10px;margin-bottom:10px;" id="logo_image_group" >
                                <span class="input-group-addon" id="basic-addon1"><div style="width:120px;">Logo Bank:</div></span>
                                <input class="form-control" style=" padding:0px; text-align:right;" id="logo_file" type="file" name="logo_file"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label><input type="checkbox" name="signature_image_show" id="signature_image_show" />&nbsp;&nbsp;<strong>Display Signature Check</strong></label>
                        </div>
                        <div class="col-md-6">
                            <label><input type="checkbox" name="logo_image_show" id="logo_image_show" />&nbsp;&nbsp;<strong>Display Logo Check</strong></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class = "col-md-6">  
                            <label><input type="checkbox" name="print_routing_symbol" id="print_routing_symbol" />&nbsp;&nbsp;<strong>Print Routing Symbol</strong></label>
                        </div>
                        <div class="col-md-6">
                            <label><input type="checkbox" name="default_account" id="default_account" />&nbsp;&nbsp;<strong>Default Account</strong></label>
                        </div>                
                    </div>
                    <div class="row" style="padding:3px">
                        <div class="col-md-12">
                            <div style="margin-right:10px;margin-bottom:10px; text-align:right" id="user_buttons">
                                <button type="button" class="btn btn-primary custom-button-width" id="listaccounts" name="listaccounts" onclick="list_bank_account();"><span class="	glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Bank Accounts</button>&nbsp;&nbsp;<button type="submit" class="btn btn-primary custom-button-width" id="addbankaccount" name="addbankaccount"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;&nbsp;Save</button>
                            </div>
                        </div>
                    </div>
                    <input id="street_number" name="street_number" style="display:none;"></input>
                    <input id="route" name="route" style="display:none;"></input>
                    <input id="locality" name="locality" style="display:none;"></input>
                    <input id="administrative_area_level_1" name="administrative_area_level_1" style="display:none;"></input>
                    <input id="postal_code" name="postal_code" style="display:none;"></input>
                    <input type="text" id="latitude" style="display:none;"></input>
                    <input type="text" id="longitude" style="display:none;"></input>
                    <input type="text" id="country" name="country" style="display:none;" />

                    <input id="street_number2" name="street_number2" style="display:none;"></input>
                    <input id="route2" name="route2" style="display:none;"></input>
                    <input id="locality2" name="locality2" style="display:none;"></input>
                    <input type="text" id="latitude2" style="display:none;"></input>
                    <input type="text" id="longitude2" style="display:none;"></input>
                    <input type="text" id="country2" name="country2" style="display:none;" />
                </div>
                <!----------------------------------------->
                <div class="row" id="div_newaccount">
                    <div style="margin-left:10px;margin-right:10px; margin-bottom:10px; margin-top:10px; text-align:left">
                        <button type="button" class="btn btn-primary custom-button-width" id="newaccount" name="newaccount" onclick="new_account();"><span class="glyphicon glyphicon-usd"></span>&nbsp;&nbsp;New Bank Account</button>&nbsp;&nbsp;<a href="index.php"><button type="button" class="btn btn-success" id="btn_back" name="btn_back" title="Back to Menu"><span class="glyphicon glyphicon-chevron-left"></span> Back</button></a>
                    </div>
                </div>
                <!--<hr class="centre" style="width:100%; height:1px">-->
                <div id="msg_bankinfo_grid" style="display:none"></div>
                <div id="bank_grid" style="display:"></div>
            </div>
        </div>
    </form>   
</div>