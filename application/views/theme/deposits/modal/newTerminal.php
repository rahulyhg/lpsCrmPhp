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
            <h5 class="modal-title" id="newEventMoalLebel">New Terminal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= deposits_url("newTerminal") ?>"
                  class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Terminal Name</label>
                            <input type="text" name="terminalName" class="form-control" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">                            
                            <label class="h6">Company</label>                            
                            <input type="text" name="company" class="form-control" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Login ID</label>
                            <input type="text" name="loginID" class="form-control" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>         
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Account#</label>                            
                            <input class="form-control" name="account" type="text" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Note</label>                            
                            <textarea class="form-control" name="note" type="text"></textarea>
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