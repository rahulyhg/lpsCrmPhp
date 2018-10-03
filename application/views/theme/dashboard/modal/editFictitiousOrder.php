<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Mar 30, 2018, 7:30:04 PM
 */
?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="newEventMoalLebel">New Fictitious Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= dashboard_url("editFictitiousOrder/" . $fictitiousOrder->id) ?>" class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="orderDate" class="form-control selectedDate " 
                                   value="<?= changeDateFormatToLong($fictitiousOrder->orderDate) ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>                        
                        <fieldset class="form-group">                            
                            <label class="h6">Fictitious ContactID</label>                            
                            <input class="form-control" style="width: 100%;" required name="contactID"
                                    placeholder="Prospect ContactID"
                                    value="<?= $fictitiousOrder->contactID ?>" readonly
                                    data-validation-required-message="Prospect ContactID can not be blank">                                     
                               
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Payment Type</label>
                            <select class="form-control" onchange="checkPayment()" name="paymentType" id="paymentType" required>
                                <option><?= $fictitiousOrder->paymentType ?></option>
                                <option><?= $fictitiousOrder->paymentType == "Credit" ? "Check" : "Credit" ?></option>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>         
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Price</label>                            
                            <input class="form-control" name="price" id="price" type="text" required value="<?= $fictitiousOrder->price ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div> 
                    <div class="col-12" id="checkDiv">
                        <fieldset class="form-group">
                            <label class="h6">Check Number</label>                            
                            <input class="form-control" name="checkNumber" type="text" id="checkNumber" value="<?= $fictitiousOrder->checkNumber ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Email</label>                            
                            <input class="form-control" name="email" type="email" value="<?= $fictitiousOrder->email ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  

                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Phone</label>                            
                            <input class="form-control" name="phone" type="text" value="<?= $fictitiousOrder->phone ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div> 
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Note</label>                            
                            <textarea class="form-control" name="note" ><?= $fictitiousOrder->note ?></textarea>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12 text-center">                        
                        <button type="submit" class="btn bg-blue-grey square" value="SaveNewPayment">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">
    function checkPayment() {
        if ($("#paymentType").val() === "Check") {
            $("#checkDiv").show();
            $("#checkNumber").attr("required", true);
        } else {
            $("#checkDiv").hide();
            $("#checkNumber").attr("required", false);
            $("#checkNumber").val("");
        }
    }
    checkPayment();   
    /* $("#customer").on("select2:select", function (e) {
     console.log($("#customer").select2('data')[0].extra);
     $("#prospectID").val($("#customer").select2('data')[0].extra);
     });*/
    $("#newPaymentForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".selectedDate").datetimepicker({
        format: 'DD MMM, YYYY'
    });

</script>