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
            <h5 class="modal-title" id="newEventMoalLebel">New Settllement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= deposits_url("newSettllement") ?>"
                  class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="date" class="form-control todayDate" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <?php
                    if ($terminals) {
                        $fill = 1;
                        foreach ($terminals as $terminal) {
                            ?>
                            <div class="col-12 py-1 <?= $fill ? "bg-light" : "" ?>">
                                <fieldset class="form-group p-0">                            
                                    <label class="h6">
                                        TerminalName#<strong><?= $terminal->terminalName ?></strong>                                        
                                    </label>                            
                                    <input type="text" name="terminal_<?= $terminal->id ?>" class="form-control termi">
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                            </div>
                            <?php
                            $fill = !$fill;
                        }
                    }
                    ?>
                    <div class="col-12 py-1 <?= $fill ? "bg-light" : "" ?>">
                        <fieldset class="form-group p-0">                            
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
        var total = 0;
        $(".termi").each(function () {
            var val = $(this).val() ? $(this).val() : 0;
            total += parseInt(val);
        });
        $("#total").val(total);
    });
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