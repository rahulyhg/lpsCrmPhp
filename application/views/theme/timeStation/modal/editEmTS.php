<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Mar 30, 2018, 7:30:04 PM
 */
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title h5">Edit TimeStation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?php echo form_open('timeStation/editEmpToTS', "class='panel panel-body'"); ?>
            <div class="error"><?php echo validation_errors(); ?></div>      
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Time In</label>
                    <input type="hidden" name="id" value="<?= $employeeTS->id ?>" >
                    <input type="text" name="timeIn" required class="form-control selectedTimeDate" 
                           value="<?= date("d M, Y h:i a", strtotime($employeeTS->timeIn)) ?>" placeholder="Pick In Time">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Time Out</label>
                    <input type="text" name="timeOut" class="form-control selectedTimeDate" 
                           value="<?= date("d M, Y h:i a", strtotime($employeeTS->timeOut)) ?>" placeholder="Pick Out Time">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="note" class="form-control" rows="2"><?= $employeeTS->note ?></textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="text-center ">
                        <button class="btn btn-success" type="submit" value="Save">Edit</button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    $(".selectedTimeDate").datetimepicker({ format: 'DD MMM, YYYY hh:mm a'});
    $("body").delegate(".selectedTimeDate", "focusin", function () {
        $(".selectedTimeDate").datetimepicker({ format: 'DD MMM, YYYY hh:mm a'});
    });
</script>