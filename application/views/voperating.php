<?php require_once (APPPATH.'views/includes/header.php');?>
<div id="page-wrapper" >
    <div id="page-inner">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>Operating Expenses</h2>   
            </div>
        </div>
        
        <hr />
        
        <div class="text-center">
            <?php
            if(isset($flag)):                
            ?>
            <div class="success <?=$flagst?>"><?=$flag?></div>
            <?php
            endif;
            ?>
            <div class="row">
                
                <div class="col-sm-2 col-sm-offset-3">
                    <input class="form-control btn btn-default" value="New Expense"  data-target="#addexpense" data-toggle="modal" type="button">
                </div>
                <div class="col-sm-2">
                    <a href="<?= base_url("dashboard/vnec")?>"><input class="form-control btn btn-primary" value="Expense Category"  type="button"></a>
                </div>
                <div class="col-sm-2">
                    <a href="<?= base_url("dashboard/employee")?>"><input class="form-control btn btn-default" value="Employee List"  type="button"></a>
                </div>
            </div><hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed table-hover" id="table">
                            <thead>            
                                <tr>
                                    <th>Serial No</th>          
                                    <th>Date</th>
                                    <th>Shift</th>
                                    <th>Reference</th>
                                    <th>Category</th>
                                    <th>Amount</th>                                    
                                    <th>Note</th>
                                    <th>Created By</th>
                                    <th>Attachment</th>                                    
                                    <th>Payed by</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($operatings){
                                    $n=1;
                                    $total=0;
                                    foreach ($operatings as $operating){
                                        $total+=$operating->am;
                                ?>
                                <tr>
                                    <td><?=$n?></td>          
                                    <td><?=$operating->date?></td>
                                    <td><?=$operating->shift?($operating->shift==1?"Morning":($operating->shift==2?"Evening":($operating->shift==3?"OverNight":"N/A"))):"N/A"?></td>
                                    <td><?=$operating->ref?></td>
                                    <td><?=$operating->name.($operating->employee?'<br><b title="'.$operating->details.'">('.$operating->emname.')</b>':'')?></td>
                                    <td><?=$operating->am?></td>                                    
                                    <td><?=$operating->notes?></td>
                                    <td><?=$operating->username?></td>
                                    <td>
                                    <?php
                                    if($operating->att){
                                    ?>
                                        <a onclick="showattach('<?=$operating->att?>')">Attachment</a>
                                    <?php
                                    }else{
                                    echo "N/A";
                                    }
                                    ?>
                                    </td>
                                    <td><?php
                                    if($operating->payedby){
                                    echo "Account";
                                    }else{
                                    echo "Cash";
                                    }
                                    ?>
                                    </td>
                                    <td>
                                        <select id='act<?=$operating->sl?>' data-value='<?=$operating->sl?>' class="form-control expense_action">
                                            <option value="0">---</option>
                                            <option value="1">Edit</option>
                                            <option value="2">Delete</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                                $n++;
                                }
                                ?>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <th colspan="5"><?=number_format($total,2)?></th>
                                </tr>
                            </thead>                                    
                                    <?php
                                }else{
                                ?>
                                <tr><th colspan="10"><h4 class="h4 text-center text-danger">No Data found!</h4></th></tr></tbody>
                                <?php
                                }
                                ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<div class="modal fade text-center" id="attmodal" style="z-index:999999999" role="dialog">
    <div class="modal-dialog text-center" id="attach">        
    </div>
</div>
<div class="modal fade" id="addexpense" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Expense</h4>
            </div>
            <div class="modal-body">
                <div class='panel panel-body'>
            <div class="ex-error panel panel-success text-center"></div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Date</label>
                    <input type="date" value="<?=date("Y-m-d")?>"  id="ex-date" class="form-control" >
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Shift</label>
                    <select class="form-control" name="shift" id="shift">
                        <option value="0">Select Shift</option>
                        <option value="1">Morning</option>
                        <option value="2">Evening</option>
                        <option value="3">OverNight</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="dj">Reference</label>
                    <input type="text" id="ex-ref" class="form-control">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Expense Category</label>                    
                    <select id="ex-ca" onchange="getem()" class="form-control">
                        <?php
                        if($categories){
                        foreach ($categories as $category){
                        ?>
                        <option value="<?=$category->no?>"><?=$category->name?></option>
                        <?php
                        }}
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Amount</label>
                    <input type="text" min="0" id="am" class="form-control" >
                </div>
            </div>
            <div class="col-sm-12 col-sm-offset-0 employee">
                <div class="form-group">
                    <label for="dj">Select Employee</label>
                    <select id="empl" class="form-control">
                        <option value="0">Select an Employee</option>
                        <?php
                        if($employees){
                        foreach ($employees as $employee){
                        ?>
                        <option title="<?=$employee->details?>" value="<?=$employee->id?>"><?=$employee->emname?></option>
                        <?php
                        }}
                        ?>
                    </select>
                </div>
            </div>            
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Attachment</label>
                    <input type="file" id="att" class="form-control">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Payed By</label>
                    <select id="payedby" class="form-control">
                        <option min="0">Cash</option>
                        <option value="1">Account</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="dj">Notes</label>
                    <textarea id="notes" class="form-control"></textarea>
                </div>
            </div>
            
                                    
            
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="text-center ">
                        <button class="btn btn-success" id="new-ex" value="Save">Save</button>
                    </div>
                </div>
            </div>
                </div>          
            </div>           
        </div>
    </div>
</div>

<div class="modal fade" id="edit-expense" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Purchase</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('dashboard/editexpense',"class='panel panel-body'"); ?>
            <div class="error"><?php echo validation_errors(); ?></div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Date</label>
                    <input type="hidden" name="eid" id="eid" class="form-control">
                    <input type="date" name="date"  id="eex-date" class="form-control" >
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Shift</label>
                    <select class="form-control" name="shift" id="eshift">
                        <option value="0">Select Shift</option>
                        <option value="1">Morning</option>
                        <option value="2">Evening</option>
                        <option value="3">OverNight</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="dj">Reference</label>
                    <input type="text" id="eex-ref" name="ref" class="form-control">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Expense Category</label>                    
                    <select  id="eex-ca"  onchange="egetem()" name="ex-ca"  class="form-control">
                        <?php
                        if($categories){
                        foreach ($categories as $category){
                        ?>
                        <option value="<?=$category->no?>"><?=$category->name?></option>
                        <?php
                        }}
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Amount</label>
                    <input type="text" min="0" id="eam" name="amm" class="form-control" >
                </div>
            </div>
            <div class="col-sm-12 col-sm-offset-0 eemployee">
                <div class="form-group">
                    <label for="dj">Select Employee</label>
                    <select id="eempl" name="employee" class="form-control">
                        <option value="0">Select an Employee</option>
                        <?php
                        if($employees){
                        foreach ($employees as $employee){
                        ?>
                        <option title="<?=$employee->details?>" value="<?=$employee->id?>"><?=$employee->emname?></option>
                        <?php
                        }}
                        ?>
                    </select>
                </div>
            </div> 
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Attachment</label>
                    <a id="slfile"></a>
                    <input type="file" id="eatt" name="upm" class="form-control">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dj">Payed By</label>
                    <select id="epayedby" name="payedby" class="form-control">
                        <option min="0">Cash</option>
                        <option value="1">Account</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="dj">Notes</label>
                    <textarea id="enotes" name="note" class="form-control"></textarea>
                </div>
            </div>
            
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="text-center ">
                        <button class="btn btn-success" type="submit" value="Save">Save</button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
            </div>           
        </div>
    </div>
</div>
<script>   
    function showattach(atname){
        $("#attach").html("<img src='"+base_url+"uploads/"+atname+"' class='img-responsive'>");
        $("#attmodal").modal("toggle");
    }
    function getem(){
        if($("#ex-ca").val()==1){            
            $(".employee").show();
        }else{
            $("#empl").val(0);
            $(".employee").hide();
        }
    }
    function egetem(){
        if($("#eex-ca").val()==1){            
            $(".eemployee").show();
        }else{
            $("#eempl").val(0);
            $(".eemployee").hide();
        }
    }
    $("#new-ex").click(function() {
        var data=new FormData();
        data.append('ref', $("#ex-ref").val());
        data.append('employee', $("#empl").val());
        data.append('note', $("#notes").val());
        data.append('amm', $("#am").val());
        data.append('ex-ca', $("#ex-ca").val());
        data.append('date', $("#ex-date").val());
        data.append('payby', $("#payedby").val());
        data.append('shift', $("#shift").val());
        if( $("#att").val()!==""){
            data.append('att', $("#att")[0].files[0]);
        }else{
            data.append('att', "");
        }       
        $(".ex-error").html("Data Saved!");
        $.ajax({
            //data:{att:"",ref:$("#ex-ref").val(),note:$("#notes").html(),amm:$("#am").val(),"ex-ca":$("#ex-ca").val(),"date":$("#ex-date").val(),payby:$("#payedby").val()},
            dataType: 'json',
            type: 'POST',
            url: base_url + "dashboard/saveexpense",
            data: data,
            enctype:'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            //dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                //console.log($("#ex-date").val());
               // console.log(data.status);
                if(data.status==true){
                    open(base_url + "operating/1","_self");
                }
            }
        });            
        });
    
    $(".expense_action").on("change",function () {
        var $ven=$(this);
        var val=$ven.val();
        var id=$ven.attr("data-value");
        
        if(val==1){
            $.ajax({
                url: base_url+"dashboard/get_expense/",
                type: 'POST',
                data: {id:id},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {                   
                    var expense=data.expense[0];     
                  // console.log(id);
                    $("#eex-date").val(expense.date);                    
                    $("#eex-ca option[value='"+expense.ec+"']").prop('selected',true);
                    $("#eempl").val(expense.employee);
                    $("#eex-ref").val(expense.ref);
                    $("#eam").val(expense.am);
                    $("#eshift").val(expense.shift);
                    $("#epayedby option[value='"+expense.payedby+"']").prop('selected',true);
                    $("#enotes").html(expense.notes);
                    $("#eid").val(expense.sl);
                    if(expense.att!=0){
                        $("#slfile").html("Stored File");
                        $("#slfile").click(function (){
                            showattach(expense.att);
                        });                         
                    }
                    $("#edit-expense").modal("toggle");                   
                }
            });
            
        }else if(val==2){
            if(confirm("do you really want to delete ?")){
                open(base_url+"dashboard/delex/"+id,"_self");
            }
        }
    });
</script>


<?php require_once (APPPATH.'views/includes/footer.php');?>