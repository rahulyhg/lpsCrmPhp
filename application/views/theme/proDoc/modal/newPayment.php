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
            <h5 class="modal-title" id="newEventMoalLebel"><strong><?= getProDocState($_currentState) ?></strong> New Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= proDoc_url("newPayment/" . $_currentState) ?>" class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="orderDate" class="form-control todayDate ">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>

                        <fieldset class="form-group">

                            <input id="prospectID" type="hidden" value="<?= isset($_currentProspect) ? "$_currentProspect" : "" ?>" 
                                   name="prospectsID" required
                                   data-validation-required-message="Prospect ID can not be blank">
                            <label class="h6">Prospect ContactID</label>                            
                            <select class="form-control" id="customer" style="width: 100%;" required name="contactID"
                                    placeholder="Prospect ContactID"
                                    data-validation-required-message="Prospect ContactID can not be blank">
                                        <?php
                                        if (isset($_currentProspect)) {
                                            ?>
                                    <option><?= $_currentProspectContactID ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value=""></option>
                                    <?php
                                }
                                ?>                                
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Payment Type</label>
                            <select class="form-control" name="paymentType" id="paymentType" required>
                                <option>Check</option>
                                <option>Credit</option>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>         
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Price</label>                            
                            <input class="form-control" name="price" id="price" value="<?= "91" ?>" type="text" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12" id="checkDiv">
                        <fieldset class="form-group">
                            <label class="h6">Check Number</label>                            
                            <input class="form-control" name="checkNumber" type="text" id="checkNumber">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Email</label>                            
                            <input class="form-control" name="email" type="email">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Phone</label>                            
                            <input class="form-control" name="phone" type="text">
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

    $("#paymentType").on("change", function (e) {

        if ($(this).val() === "Check") {
            $("#price").val(91);
            $("#checkDiv").show();
            $("#checkNumber").attr("required", true);
            $("#checkNumber").val("");
        } else {
            $("#price").val(95.95);
            $("#checkDiv").hide()();
            $("#checkNumber").attr("required", false);
            $("#checkNumber").val("");
        }

    });
    $("#customer").select2({
        createTag: function () {
            // Disable tagging
            return null;
        },
        //minimumInputLength: 3,
        tags: [],
        ajax: {
            url: '<?= proDoc_url("getCustomerList") ?>',
            dataType: 'json',
            type: "POST",
            quietMillis: 00,
            data: function (term) {
                return {term: term, state: $("#stateID").val()};
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {id: item.text, text: item.text, extra: item.id};
                    })
                };
            }, success(e, f, d) {
                console.log(e, f, d);
            },
            error(e, f, d) {
                console.log(e, f, d);
            }

        }

    });
    $("#customer").on("select2:select", function (e) {
        //console.log($("#customer").select2('data')[0].extra);
        $("#prospectID").val($("#customer").select2('data')[0].extra);
    });
    $("#newPaymentForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY',
        defaultDate: new Date()
    });
    

</script>