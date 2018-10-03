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
            <h5 class="modal-title" id="newEventMoalLebel">New Mailing</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= dashboard_url("newMailing") ?>" class="container-fluid" 
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
                                        <input placeholder="Sent" class="form-control square form-control-sm mx-1 sent"
                                               pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                               name="<?= $st ?>sent"/>                                   
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
                                <input value="0" placeholder="Sent" class="form-control square form-control-sm mx-1" id="sent" readonly/>                              
                            </div>
                            <p class="help-block m-0 danger px-1"></p>
                        </fieldset>
                    </div>                    
                    <div class="col-12 text-center py-1">
                        <button type="submit" class="btn bg-blue-grey square" value="newMailing">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">
    function calSe() {
        var e = parseInt(0);
        $(".sent").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#sent").val(e);
    }

    $(".sent").on("keyup", function (e) {
        calSe();
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