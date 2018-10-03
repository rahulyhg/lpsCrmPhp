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
            <h5 class="modal-title" id="newEventMoalLebel">Edit Bank</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= deposits_url("editBank/" . $bank->id) ?>"
                  class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Company</label>
                            <input type="text" name="company" class="form-control" required value="<?= $bank->company ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">                            
                            <label class="h6">DBA</label>                            
                            <input type="text" name="dba" class="form-control" required value="<?= $bank->dba ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Bank Name</label>
                            <input type="text" name="bankName" class="form-control" required value="<?= $bank->bankName ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>         
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Account#</label>                            
                            <input class="form-control" name="account" type="text" required value="<?= $bank->account ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">State</label>                            
                            <input class="form-control" name="state" type="text" value="<?= $bank->state ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12 text-center">
                        <button type="submit" class="btn bg-blue-grey square" value="save">Save</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">

    $("#newForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

</script>