<div class="modal-dialog modal-lg">
    <div class="modal-content square">
        <div class="modal-header">            
            <h4 class="modal-title pull-left">New Template</h4>
            <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class='panel panel-body'>
                <form method="post" enctype="multipart/form-data" id="newExpense" novalidate action="<?= settings_url("addTemplate") ?>"
                      class="container-fluid" >                               
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Purpose</label>
                            <select type="text" name="purpose" class="form-control" required>
                                <?php
                                foreach (getPurposes() as $key => $val) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $val ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>   
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Purpose</label>
                            <input type="email" name="senderEmail" class="form-control" value="info@laborposterservices.com" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>   
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Active</label><br>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" name="active" checked value="1" class="form-check-input" required>
                                    Yes
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" name="active" value="0" class="form-check-input">
                                    No
                                </label>
                            </div>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Template</label>
                            <textarea type="text" name="template" class="form-control" rows="20" required></textarea>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
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