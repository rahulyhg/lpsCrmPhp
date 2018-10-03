<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">            
            <h4 class="modal-title">New Expense</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class='panel panel-body'>
                <form method="post" enctype="multipart/form-data" id="newExpense" novalidate action="<?= dashboard_url("newExpense") ?>" class="container-fluid" >
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label>Date</label>
                                <input type="text" name="date" class="form-control todayDate" required >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>                
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label>Reference</label>
                                <input type="text" name="reference" class="form-control">
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label>Expense Category</label>                    
                                <select name="ecID" id="expenseCategory" class="form-control">
                                    <?php
                                    if ($categories) {
                                        foreach ($categories as $category) {
                                            ?>
                                            <option value="<?= $category->no ?>"><?= $category->name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label>Amount</label>
                                <input type="text" min="0" name="amount" required class="form-control" >
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col-12 employee">
                            <fieldset class="form-group">
                                <label>Select Employee</label>
                                <select id="employee" name="employee" class="form-control" style="width: 100%;">
                                    <option></option>                            
                                </select>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>            
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label>Attachment</label>
                                <input type="file" name="attachment" class="form-control">
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label>Paid By</label>
                                <select name="paidBy" required class="form-control">
                                    <option>Cash</option>
                                    <option>Account</option>
                                </select>
                                <p class="help-block m-0 danger"></p>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control"></textarea>
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
                    </div>
                </form>
            </div>          
        </div>           
    </div>
</div>
<script type="text/javascript">

    $("#employee").select2({
        minimumInputLength: 2,
        tags: [],
        ajax: {
            url: '<?= dashboard_url("getEmployeeList") ?>',
            dataType: 'json',
            type: "POST",
            quietMillis: 00,
            data: function (term) {
                return {term: term};
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {id: item.id, text: item.text};
                    })
                };
            }, success(e, f, d) {
                // console.log(e, f, d);
            },
            error(e, f, d) {
                console.log(e, f, d);
            }
        }
    });

    $("#newExpense").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY',
        defaultDate: new Date()
    });
    $("#expenseCategory").on("change", function (e) {
        console.log(this);
        if ($(this).val() === "1") {
            $(".employee").show();
        } else {
            $(".employee").hide();
        }
    });
</script>