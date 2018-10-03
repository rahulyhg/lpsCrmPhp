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
            <h5 class="modal-title" id="newEventMoalLebel"><strong><?= getState($_currentState) ?></strong> Import Prospects</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">                                      
            <form method="post" enctype="multipart/form-data" action="<?= dashboard_url("newProspects/" . $_currentState) ?>" class="container green lighten-3 rounded-left p-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="h6">Chose <strong><?= getState($_currentState) ?></strong> data Excel File </label>
                            <input type="file" required name="file" class="form-control-file"  accept=".csv,.xls,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                    </div>
                    <?php
                    $dbColumns = getStateColumns($_currentState);
                    foreach ($dbColumns as $cell => $col) {
                        ?>
                        <div class="col-6">
                            <div class="d-flex my-1 bg-light">      
                                <label class="w-50 text-right h6"><?= $col ?></label>
                                <input type="hidden" name="dbColumns[]" value="<?= $cell ?>" required class="form-control w-50 form-control-sm square">
                                <input type="text" name="filteredColumns[]" value="<?= $cell ?>" required class="form-control w-50 form-control-sm square">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-12 text-left">
                        <div class="text-left">
                            <button type="submit" class="btn orange"><i class="ft-save"></i>Import</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>            
</div>

<script type="text/javascript">

</script>