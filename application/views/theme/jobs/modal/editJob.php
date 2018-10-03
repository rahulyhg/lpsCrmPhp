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
            <h5 class="modal-title" id="newEventMoalLebel">Edit Job</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= jobs_url("editJob/" . $job->id) ?>"
                  class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Date</label>
                            <input type="text" name="date" class="form-control todayDate" autocomplete="off" value="<?= convertDate($job->date, "d M, Y h:i A", "Y-m-d H:i:s") ?>" required>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">                            
                            <label class="h6">Job</label>                            
                            <select name="job" data-live-search="true" required class="form-control selectTwo" data-showTick="true" style="width: 100%" data-showSubtext="true" title="Select a Job">

                                <?php
                                foreach (getBrmState() as $st => $state) {
                                    ?>
                                    <option <?= $job->job == $state ? "selected" : "" ?> value="<?= $state ?>"><?= $state ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Count</label>
                            <input type="text" name="count" class="form-control" value="<?= $job->count ?>" >
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div> 
                    <div class="col-6">
                        <fieldset class="form-group">
                            <label class="h6">Tray Start</label>
                            <input type="text" name="trayStart" class="form-control" value="<?= $job->trayStart ?>" >
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div> 
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Employee Name</label>
                            <select  name="employeeID" data-live-search="true" required class="form-control selectTwo show-tick" data-showTick="true" style="width: 100%" data-showSubtext="true" title="Select an employee">
                                <?php
                                if (isset($employees)) {
                                    foreach ($employees as $employee) {
                                        ?>
                                        <option <?= $job->employeeID == $employee->id ? "selected" : "" ?> data-subtext="<?= $employee->designation ?>" value="<?= $employee->id ?>"><?= $employee->firstName . " " . $employee->lastName ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label class="h6">Note</label>                            
                            <textarea class="form-control" name="note"><?= $job->note ?></textarea>
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>  
                    <div class="col-12 text-center">
                        <button type="submit" class="btn bg-blue-grey square" value="save">Edit</button>
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
    $(".todayDate").datetimepicker({
        format: 'DD MMM, YYYY hh:mm A',
        defaultDate: new Date()
    });
    $(".selectTwo").select2();
</script>