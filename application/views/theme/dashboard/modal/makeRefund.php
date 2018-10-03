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
            <h5 class="modal-title" id="newEventMoalLebel"><?= $_type ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate   enctype="multipart/form-data"
                  action="<?= dashboard_url("makeRefund/" . $_type . "/" . $_id . "/" . $_cus) ?>" class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Refund Date</label>
                            <input type="text" name="refundDate" required="" class="form-control todayDate ">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>

                        <fieldset class="form-group">                            
                            <label class="h6">Notes</label>                            
                            <textarea class="form-control" name="note" rows="5"></textarea>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <?php
                    if ($_type === "Refund") {
                        ?>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="h6">Chose <strong>Attachment</strong> File </label>
                                <input type="file" name="attachment" required class="form-control-file">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn bg-blue-grey square" value="save">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">

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