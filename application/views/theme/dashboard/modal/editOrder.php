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
            <h5 class="modal-title" id="newEventMoalLebel">Edit Order</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= dashboard_url("editOrder/" . $order->id) ?>" class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="orderDate" class="form-control selectedDate" required 
                                   value="<?= date("d M, Y", strtotime($order->orderDate)) ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Payment Type</label>
                            <select class="form-control" name="paymentType" id="paymentType" required value="<?= $order->paymentType ?>">
                                <option><?= $order->paymentType ?></option>
                                <option><?= $order->paymentType == "Credit" ? "Check" : "Credit" ?></option>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>         
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Price</label>                            
                            <input class="form-control" name="price" id="price" value="<?= $order->price ?>" type="text" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12" id="checkDiv">
                        <fieldset class="form-group">
                            <label class="h6">Check Number</label>                            
                            <input class="form-control" name="checkNumber" type="text" id="checkNumber" value="<?= $order->checkNumber ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Email</label>                            
                            <input class="form-control" name="email" type="email" value="<?= $order->email ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Phone</label>                            
                            <input class="form-control" name="phone" type="text" value="<?= $order->phone ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12 text-center">
                        <button type="submit" class="btn bg-blue-grey square" value="SaveNewPayment">Edit</button>
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
            $("#checkNumber").val(" ");
        }
    }
    checkPayment();
    $("#paymentType").on("change", function (e) {
        checkPayment();
<?php
if (strlen($order->stateID) === 2) {
    ?>
            if ($(this).val() === "Check") {
                $("#price").val(<?= $_currentState === "CA" ? "125" : "94" ?>);
            } else {
                $("#price").val(<?= $_currentState === "CA" ? "129.95" : "98.95" ?>);
            }
    <?php
} else {
    ?>
            if ($(this).val() === "Check") {
                $("#price").val(84);
            } else {
                $("#price").val(88.95);
            }
    <?php
}
?>
    });
    $("#newPaymentForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".selectedDate").datetimepicker({
        format: 'DD MMM, YYYY'
    });</script>