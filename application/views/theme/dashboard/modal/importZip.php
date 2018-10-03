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
            <h5 class="modal-title" id="newEventMoalLebel">Import Prospects from Zip</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">                                      
            <form method="post" enctype="multipart/form-data" novalidate action="<?= dashboard_url("importZip") ?>" class="container green lighten-3 rounded-left p-1">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Chose a State</label>
                            <select name="state" class="form-control" required="">
                                <option></option>
                                <?php
                                foreach (getState() as $ab => $state) {
                                    ?>
                                    <option value="<?= $ab ?>"><?= $state ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Chose a zip</label>
                            <input type="file" name="file" class="form-control-file"  accept=".zip" required="">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12 text-left">
                        <div class="text-left">
                            <button type="submit" class="btn orange"><i class="ft-save"></i>Start Import</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>            
</div>

<script type="text/javascript">
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
</script>