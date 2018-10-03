<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Category</h4>
        </div>
        <div class="modal-body">
            <div class='panel panel-body'>
                <form method="post" enctype="multipart/form-data" id="newExpense" novalidate action="<?= dashboard_url("newCategory") ?>" class="container-fluid" >
                          
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="name" required class="form-control">
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

    $("#newExpense").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
  
</script>