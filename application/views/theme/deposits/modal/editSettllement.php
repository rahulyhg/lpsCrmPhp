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
            <h5 class="modal-title" id="newEventMoalLebel">Edit Settllement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= deposits_url("editSettllement/" . $settllement->settllementID) ?>"
                  class="container-fluid">
                <div class="row">
                    <div class="col-12 py-1">
                        <fieldset class="form-group m-0">
                            <label>Date</label>
                            <input type="text" name="date" class="form-control todayDate" required
                                   value="<?= changeDateFormatToLong($settllement->date) ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <?php
                    $accs = json_decode($settllement->account);
                    $accounts = [];
                    foreach ($accs as $acc) {
                        $accounts[$acc->id] = $acc;
                    }
                    $fill = 1;
                    if ($terminals) {

                        foreach ($terminals as $terminal) {
                            ?>
                            <div class="col-12 py-1 <?= $fill ? "bg-light" : "" ?>">
                                <fieldset class="form-group p-0 m-0">                            
                                    <label class="h6">
                                        Terminal Name <strong>
                                            <?=
                                            isset($accounts[$terminal->id]) ?
                                                    ($accounts[$terminal->id]->terminalName !== $terminal->terminalName ?
                                                            ($terminal->terminalName). " [ Old: " . ($accounts[$terminal->id]->terminalName ). "] " :
                                                            $terminal->terminalName ) :
                                                    $terminal->terminalName
                                            ?>
                                        </strong>                                        
                                    </label>                            
                                    <input type="text" name="terminal_<?= $terminal->id ?>" class="form-control termi"
                                           value="<?= isset($accounts[$terminal->id]) ? $accounts[$terminal->id]->value : "0" ?>"
                                           >
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                            </div>
                            <?php
                            $fill = !$fill;
                        }
                    }
                    ?>
                    <div class="col-12 py-1 <?= $fill ? "bg-light" : "" ?>">
                        <fieldset class="form-group p-0 m-0">                            
                            <label class="h6">
                                <strong>Total</strong>                                        
                            </label>                            
                            <input type="text" readonly class="form-control" id="total">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12 text-center mt-1">
                        <button type="submit" class="btn bg-blue-grey square" value="save">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">
    $(".termi").on("keyup", function () {
        tot();
    });
    tot();
    function tot() {
        var total = 0;
        $(".termi").each(function () {
            var val = $(this).val() ? $(this).val() : 0;
            total += parseInt(val);
        });
        $("#total").val(total);
    }
    $("#newForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY',
        defaultDate: new Date()
    });

</script>