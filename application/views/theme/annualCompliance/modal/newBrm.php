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
            <h5 class="modal-title" id="newEventMoalLebel">New BRM</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= annualCompliance_url("newBrm") ?>" class="container-fluid" 
                  enctype="multipart/form-data" >
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="date" class="form-control todayDate " required="">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <?php
                    foreach (getACBrmState() as $st => $state) {
                        ?>
                        <div class="col-12 border-bottom " style="padding: 5px 0px;">
                            <fieldset class="form-group  m-0 p-0">
                                <div class="d-flex">
                                    <strong class=" mx-1 w-25"><?= $st ?></strong>
                                    <input placeholder="Received" class="form-control square form-control-sm mx-1 received"
                                           pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                           name="<?= $st ?>received"/>
                                    <input placeholder="Charged" class="form-control square form-control-sm mx-1 charged"
                                           pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                           name="<?= $st ?>charged"/>                                        
                                </div>
                                <p class="help-block m-0 px-1 danger"></p>
                            </fieldset>
                        </div>
                        <?php
                    }
                    ?>                    
                    <div class="col-12  bg-lighten-5 bg-blue-grey" style="padding: 5px 0px;">
                        <fieldset class="form-group  m-0 p-0">
                            <div class="d-flex">
                                <strong class=" mx-1 w-25">Total</strong>

                                <input value="0" placeholder="Received" class="form-control square form-control-sm mx-1" id="received" readonly/>
                                <input value="0" placeholder="Charged" class="form-control square form-control-sm mx-1" id="charged" readonly/>
<!--                                <input value="0" placeholder="sent" class="form-control square form-control-sm mx-1 " id="sent" readonly/>-->
                            </div>
                            <p class="help-block m-0 danger px-1"></p>
                        </fieldset>
                    </div>
                    <div class="col-12" style="padding: 5px 0px;">
                        <fieldset class="form-group  m-0 p-0">
                            <label>Attachment</label>
                            <input type="file" class="form-control-file" name="attachment"/>
                            <p class="help-block m-0 danger px-1"></p>
                        </fieldset>
                    </div>
                    <div class="col-12 text-center py-1">
                        <button type="submit" class="btn bg-blue-grey square" value="newBrm">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">
    function calCh() {
        var e = parseInt(0);
        $(".charged").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#charged").val(e);
    }
    function calRe() {
        var e = parseInt(0);
        $(".received").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#received").val(e);
    }
    $(".charged").on("keyup", function (e) {
        calCh();
    }), $(".received").on("keyup", function (e) {
        calRe();
    }), $("#newPaymentForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY',
        defaultDate: new Date()
    });
</script>