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
            <h5 class="modal-title" id="newEventMoalLebel">Edit Non BRM</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newPaymentForm" novalidate action="<?= dashboard_url("editNonBrm/" . $nonBrm->id) ?>" class="container-fluid" 
                  enctype="multipart/form-data" >
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="date" class="form-control todayDate " required="" 
                                   value="<?= changeDateFormatToLong($nonBrm->date) ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <?php
                    $columns = ["email", "regus", "ownStamps"];
                    foreach ($columns as $column) {
                        ?>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label><?= ucfirst($column) ?></label>
                                <input placeholder="<?= ucfirst($column) ?>" class="form-control square charged"
                                       pattern="^\$?[\d]*" data-validation-pattern-message="Only Positive integer"
                                       name="<?= $column ?>" value="<?=$nonBrm->$column?>"/>                                        

                                <p class="help-block m-0 px-1 danger"></p>
                            </fieldset>
                        </div>
                        <?php
                    }
                    ?>                 

                    <div class="col-12  bg-lighten-5 bg-blue-grey" style="padding: 5px 0px;">
                        <fieldset class="form-group">
                            <label>Total</label>                                
                            <input value="0" placeholder="Total" class="form-control square" id="charged" readonly/>
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
    calCh();
    function calCh() {
        var e = parseInt(0);
        $(".charged").each(function (a) {
            $(this).val() && (e += parseInt($(this).val()));
        }), $("#charged").val(e);
    }
    $(".charged").on("keyup", function (e) {
        calCh();
    }), $("#newPaymentForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY'
    });
</script>