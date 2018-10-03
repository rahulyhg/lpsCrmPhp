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
            <h5 class="modal-title" id="newEventMoalLebel">New Non BRM</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= dashboard_url("newNonBrm") ?>" class="container-fluid" 
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
                    foreach (getBrmState() as $st => $state) {
                        ?>
                        <div class="col-12 border-bottom " style="padding: 5px 0px;">
                            <fieldset class="form-group  m-0 p-0">
                                <div class="d-flex">
                                    <strong class=" mx-1 w-25"><?= $st ?></strong>
                                    <input placeholder="Email" class="form-control square form-control-sm mx-1 email"
                                           pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                           name="email[<?= $st ?>]"/>
                                    <input placeholder="Regus" class="form-control square form-control-sm mx-1 regus"
                                           pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                           name="regus[<?= $st ?>]"/>         
                                    <input placeholder="Own Stamps" class="form-control square form-control-sm mx-1 ownStamp"
                                           pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                           name="ownStamps[<?= $st ?>]"/>
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
                                <input placeholder="Email" readonly class="form-control square form-control-sm mx-1" id="email">
                                <input placeholder="Regus" readonly class="form-control square form-control-sm mx-1" id="regus">
                                <input placeholder="Own Stamps" readonly class="form-control square form-control-sm mx-1"id="ownStamp">
                            </div>
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
    function calEm() {
        var e = parseInt(0);
        $(".email").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#email").val(e);
    }
    function calOS() {
        var e = parseInt(0);
        $(".ownStamp").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#ownStamp").val(e);
    }
    function calRe() {
        var e = parseInt(0);
        $(".regus").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#regus").val(e);
    }
    $(".email").on("keyup", function (e) {
        calEm();
    }),$(".regus").on("keyup", function (e) {
        calRe();
    }),$(".ownStamp").on("keyup", function (e) {
        calOS();
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