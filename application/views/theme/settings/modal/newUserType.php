<div class="modal-dialog modal-lg">
    <div class="modal-content square">
        <div class="modal-header">            
            <h4 class="modal-title pull-left">New User Type</h4>
            <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class='panel panel-body'>
                <form method="post" enctype="multipart/form-data" id="newExpense" novalidate action="<?= settings_url("newUserTypeSave") ?>"
                      class="container-fluid" >                               
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>User Type</label>
                            <input type="text" name="userType" class="form-control" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>            
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>White Listed IP [Put * for allow all ip or <b>8.8.8.8;8.8.4.4;</b>]</label>                            
                            <input type="text" name="whiteListedIP" class="form-control" value="*"
                                   data-validation-regex-regex="^\*$|([0-9]{1,3}(\.[0-9]{1,3}){3};)+"
                                   data-validation-regex-message="Put * for allow all ip OR 8.8.8.8;8.8.4.4;"
                                   required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12 pb-2">                 
                        <label>Permission</label>
                        <table class="table table-striped">                           
                            <tbody>
                                <?php
                                foreach (getPermissionDetails() as $pc => $fields) {
                                    ?>
                                    <tr>
                                        <th><?= strtoupper($pc) ?></th>
                                        <td>
                                            <?php
                                            foreach ($fields as $pf) {
                                                ?>
                                                <label class="px-1-">
                                                    <input type="checkbox" value="<?= $pf ?>" name="<?= $pc ?>[]"
                                                           class="permisionCheckBox" data-pType="<?= $pc ?>">
                                                           <?= ucfirst($pf) ?>
                                                </label>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>                                
                            </tbody>
                        </table>                       
                    </div>


                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <div class="text-center ">
                                <button class="btn btn-success" value="Save" type="submit">Save</button>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>          
        </div>           
    </div>
</div>
<script type="text/javascript">
    $("input[type=checkbox]").iCheck({
        checkboxClass: 'icheckbox_polaris',
        radioClass: 'iradio_polaris',
        increaseArea: '20%'
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation({
        preventSubmit: true
    });
    $("input.permisionCheckBox").on("ifClicked", function (e) {
        var $this = $(this);
        var pType = $this.attr("data-pType");
        var val = $this.val();
        if (val === "all") {
            $this.on("ifChecked", function (ec) {
                $("input.permisionCheckBox[data-pType=" + pType + "]").iCheck("check");
            });
            $this.on("ifUnchecked", function (ec) {
                $("input.permisionCheckBox[data-pType=" + pType + "]").iCheck("uncheck");
            });
        } else {
            $("input.permisionCheckBox[data-pType=" + pType + "][value=all]").iCheck("uncheck");
        }
    });
</script>