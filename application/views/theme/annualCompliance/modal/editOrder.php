<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Mar 30, 2018, 7:30:04 PM
 */
?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="newEventMoalLebel"><strong><?= getACState($_currentState) ?></strong> Edit Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate enctype="multipart/form-data" 
                  action="<?= annualCompliance_url("editOrder/" . $order->id) ?>" class="container-fluid">
                <div class="row">
                    <div class="alert alert-info w-100 text-center" id="alertInfo"></div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="orderDate" class="form-control todayDate" value="<?= changeDateFormatToLong($order->orderDate) ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>

                        <fieldset class="form-group">                           
                            <label class="h6">Prospect ContactID</label>                            
                            <input class="form-control" id="customer"  value="<?= $order->contactID ?>" required name="contactID"
                                   placeholder="Prospect ContactID" readonly
                                   data-validation-required-message="Prospect ContactID can not be blank">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <fieldset class="form-group">
                            <label class="h6">Payment Type</label>
                            <select class="form-control" name="paymentType" id="paymentType" required>
                                <option selected><?= $order->paymentType ?></option>
                                <option><?= $order->paymentType === "Check" ? "Credit" : "Check" ?></option>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>         
                    <div class="col">
                        <fieldset class="form-group">
                            <label class="h6">Price</label>                            
                            <input class="form-control" name="price" id="price" type="text" required value="<?= $order->price ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col" id="checkDiv">
                        <fieldset class="form-group">
                            <label class="h6">Check Number</label>                            
                            <input class="form-control" name="checkNumber" type="text" id="checkNumber" value="<?= $order->checkNumber ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <fieldset class="form-group">
                            <label class="h6">Email</label>                            
                            <input class="form-control" name="email" type="email" value="<?= $order->email ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col">
                        <fieldset class="form-group">
                            <label class="h6">Phone</label>                            
                            <input class="form-control" name="phone" type="text" value="<?= $order->phone ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">EIN</label>                            
                            <input class="form-control" name="ein" type="text" value="<?= $order->ein ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Notes</label>                            
                            <textarea class="form-control" name="notes"><?= $order->notes ?></textarea>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12" style="padding: 5px 0px;">
                        <fieldset class="form-group  m-0 p-0">
                            <label>Attachment[New attachment will be added with older]</label>
                            <input type="file" class="form-control-file" name="paymentAttachment"/>
                            <p class="help-block m-0 danger px-1"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>
                                <input onclick="toggleModal(this)" class="icheck_line-icon" name="changed" value="yes" 
                                       <?= $order->changed === "yes" ? " checked " : "" ?> type="checkbox">
                                Check  if  there  are  changes  to  the  Annual  Report  </label>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12 text-center">
                        <input id="stateID" type="hidden" value="<?= $_currentState ?>" name="stateID">
                        <button type="submit" class="btn bg-blue-grey square" value="SaveNewPayment">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">
    function toggleModal(th) {
        if ($(th).is(":checked")) {
            $("#contactID_val").html($("#customer").val());
            $("#contactID_value").val($("#customer").val());
            $('#inModal').modal({backdrop: 'static', keyboard: false});
            $("#inModal").modal("toggle");
        }
    }
    $(".closeinModal").on("click", function () {
        $("#inModal").modal("toggle");
    });
    $("#paymentType").on("change", function (e) {
        if ($(this).val() === "Check") {
            $("#checkDiv").show();
            $("#checkNumber").attr("required", true);
            $("#checkNumber").val("");
        } else {
            $("#checkDiv").hide()();
            $("#checkNumber").attr("required", false);
            $("#checkNumber").val("");
        }
    });
    $("#alertInfo").hide();
    if ($("#paymentType").val() === "Check") {
        $("#checkDiv").show();
        $("#checkNumber").attr("required", true);
        $("#checkNumber").val("");
    } else {
        $("#checkDiv").hide()();
        $("#checkNumber").attr("required", false);
        $("#checkNumber").val("");
    }
    
    $("#newPaymentForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY',
        defaultDate: new Date()
    });
    $('#prospectExtra').submit(function () {
        submitForm();
        return false;
    });
    function submitForm() {
        $(".loader").show();
        $.ajax({
            url: "<?= annualCompliance_url("editProspectExtra") ?>",
            type: 'POST',
            data: $("#prospectExtra").serialize(),
            dataType: "json",
            success: function (data, textStatus, jqXHR) {                
                if (parseInt(data.status) > 0) {
                    $("#alertInfo").html("Prospect Extra Information Update Successfull!");
                    $("#alertInfo").show();
                } else {
                    $("#alertInfo").html("Prospect Extra Information Updated Wrong!");
                    $("#alertInfo").show();
                }
                $("#inModal").modal("toggle");
                $(".loader").hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
                $(".loader").hide();
            }
        });
    }
</script>

<div class="modal fade" id="inModal" role="dialog" aria-hidden="true"
     style="z-index: 999; background: rgba(100,1,1,.5);" >
    <div class="modal-dialog " style="width: 100%!important;max-width: 90%!important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= getACState($_currentState) ?> Prospect -- <strong id="contactID_val"></strong>Edit Extra Info</h5>
                <button type="button" class="close closeinModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="prospectExtra" novalidate class="container-fluid">
                    <div class="row">
                        <div class="col-12"><label>Principle</label></div>
                        <div class="col">
                            <fieldset class="form-group">    
                                <input id="contactID_value" name="contactID" type="hidden" value="<?= $prospect->contactID ?>">
                                <input class="form-control" placeholder="Address" name='principleAddress' value="<?= $prospect->principleAddress ?>" type="text" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>         
                        <div class="col">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="City" name="principleCity" type="text" value="<?= $prospect->principleCity ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="State" name="principleState" type="text" value="<?= $prospect->principleState ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Zip" name="principleZip" type="text" value="<?= $prospect->principleZip ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12"><label>Mailing</label></div>
                        <div class="col">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Address" name="mailingAddress" type="text" value="<?= $prospect->mailingAddress ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>         
                        <div class="col">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="City" name="mailingCity" type="text" value="<?= $prospect->mailingCity ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="State" name="mailingState" type="text" value="<?= $prospect->mailingState ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Zip" name="mailingZip" type="text" value="<?= $prospect->mailingZip ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12"><label>Registered</label></div>
                        <div class="col-6">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Name" name="registeredName" type="text" value="<?= $prospect->registeredName ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>         
                        <div class="col-6">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Address" name="registeredAddress" type="text" value="<?= $prospect->registeredAddress ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  



                        <div class="col">
                            <fieldset class="form-group">
                                <input class="form-control" placeholder="City" name="registeredCity" type="text" value="<?= $prospect->registeredCity ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">
                                <input class="form-control" placeholder="State" name="registeredState" type="text" value="<?= $prospect->registeredState ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Zip" name="registeredZip" type="text" value="<?= $prospect->registeredZip ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12"><label> Authorized  to  Manage  Limited  Liability  Company: (1)</label></div>
                        <div class="col-4">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Title" name="authorized1Title" type="text" value="<?= $prospect->authorized1Title ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col-4">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Name" name="authorized1Name" type="text" value="<?= $prospect->authorized1Name ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>         
                        <div class="col-4">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Address" name="authorized1Address" type="text" value="<?= $prospect->authorized1Address ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  



                        <div class="col">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="City" name="authorized1City" type="text" value="<?= $prospect->authorized1City ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="State" name="authorized1State" type="text"  value="<?= $prospect->authorized1State ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Zip" name="authorized1Zip" type="text" value="<?= $prospect->authorized1Zip ?>" required>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12"><label> Authorized  to  Manage  Limited  Liability  Company: (2)</label></div>
                        <div class="col-4">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Title" name="authorized2Title" value="<?= $prospect->authorized2Title ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col-4">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Name" name="authorized2Name" value="<?= $prospect->authorized2Name ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>         
                        <div class="col-4">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Address" name="authorized2Address" value="<?= $prospect->authorized2Address ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  



                        <div class="col">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="City" name="authorized2City" value="<?= $prospect->authorized2City ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="State" name="authorized2State" value="<?= $prospect->authorized2State ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Zip" name="authorized2Zip" value="<?= $prospect->authorized2Zip ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12"><label> Authorized  to  Manage  Limited  Liability  Company: (3)</label></div>
                        <div class="col-4">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Title" name="authorized3Title" value="<?= $prospect->authorized3Title ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col-4">
                            <fieldset class="form-group">                                
                                <input class="form-control" placeholder="Name" name="authorized3Name" value="<?= $prospect->authorized3Name ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>         
                        <div class="col-4">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Address" name="authorized3Address" value="<?= $prospect->authorized3Address ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  



                        <div class="col">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="City" name="authorized3City" value="<?= $prospect->authorized3City ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>  
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="State" name="authorized3State" value="<?= $prospect->authorized3State ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col" id="checkDiv">
                            <fieldset class="form-group">                                                          
                                <input class="form-control" placeholder="Zip" name="authorized3Zip" value="<?= $prospect->authorized3Zip ?>" type="text" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <input type="hidden" value="<?= $_currentState ?>" name="stateID" value="<?= $prospect->stateID ?>">
                            <button type="submit" class="btn bg-blue-grey square">Save</button>
                        </div>
                    </div>
                </form>            
            </div>
        </div>            
    </div>
</div>