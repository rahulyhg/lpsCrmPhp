<!--
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 8, 2018 , 12:28:10 PM
-->
<style>

</style>
<div class="">
    <div class="row text-center">
        <div class="col text-center">
            <div class="input-group justify-content-center">
                <div class="input-group-prepend w-75">
                    <select  id="employeeOthID" data-live-search="true" value="<?= $employeeID ?>" required class="show-tick select" data-showTick="true" style="width: 100%" data-showSubtext="true" title="Select an employee first">
                        <?php
                        if (isset($employees)) {
                            foreach ($employees as $employee) {
                                ?>
                                <option data-subtext="<?= $employee->designation ?>" value="<?= $employee->id ?>"><?= $employee->firstName . ' ' . $employee->lastName ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <button onclick="showEmTm()" class="btn btn-success input-group-btn"><i class="fa fa-arrow-circle-right"></i></button>
            </div>
        </div>
    </div>
    <div class="text-center">
        <div class="row">
            <div class="col-sm-12">
                <?php
                if ($employeesStatus) {
                    $ts = $employeesStatus;
                    ?>
                    <h6 class="label-info h3"><?= $currentEmployee ? $currentEmployee->firstName . ' ' . $currentEmployee->lastName . " is " : "" ?> currently Clocked in for <strong><?= intval(abs(strtotime($ts->timeOut ? $ts->timeOut : date("Y-m-d H:i")) - strtotime($ts->timeIn)) / (3600)); ?></strong> hours from <strong><?= date("d M,Y h:i a", strtotime( $ts->timeIn)) ?></strong>.
                        <br><button class="btn btn-primary" data-target="#clockOut" data-toggle="modal" type="button">Clock Out here!</button></h6>
                    <?php
                } else {
                    ?>
                    <h6 class="label-info h3"><?= $currentEmployee ? $currentEmployee->firstName . ' ' . $currentEmployee->lastName . " is " : "" ?> currently Clocked out.
                        <br><button data-target="#clockIn" data-toggle="modal" class="btn btn-primary">Clock in here!</button></h6>
                    <?php
                }
                ?>
            </div>
        </div>
        <hr>            
        <div class="row">
            <div class="col-sm-12 form-inline justify-content-center">         
                <button class="btn btn-warning" onclick="showCurWeekTS()"><i class="fa fa-arrow-left">Current Week Data</i></button>
                <input class="form-control datePicker" <?= isset($_GET["tsFrom"]) ? ' value="' . $_GET["tsFrom"] . '"' : "" ?> style="width: 150px;" id="tsFrom">
                to 
                <input class="form-control datePicker" <?= isset($_GET["tsTo"]) ? ' value="' . $_GET["tsTo"] . '"' : "" ?>  style="width: 150px;" id="tsTo">
                <button class="btn btn-danger" onclick="showDateSelectorTS()"><i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
        <hr>
        <div class="row">                
            <div class="col-sm-12">
                <?php
                if (isset($_GET["tsFrom"]) || isset($_GET["tsTo"])) {
                    ?>
                    <h4><?= $currentEmployee ? $currentEmployee->firstName . ' ' . $currentEmployee->lastName . "'s" : "" ?> Details ( <?= $_GET["tsFrom"] ?> to <?= $_GET["tsTo"] ?> )</h4>
                    <?php
                } else {
                    ?>
                    <h4><?= $currentEmployee ? $currentEmployee->firstName . ' ' . $currentEmployee->lastName . "'s" : "" ?> Details (Current Week)</h4>
                    <?php
                }
                ?> 
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover"  id="tableem">
                        <thead>
                            <tr><th>Time In</th><th>Time Out</th><th>Time Spent</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($employeeTS) {
                                $totalTSUSD = 0;
                                $totalTSTime = 0;
                                foreach ($employeeTS as $ts) {
                                    $timesp = intval(abs(strtotime($ts->timeOut ? $ts->timeOut : date("Y-m-d H:i")) - strtotime($ts->timeIn)) / (3600));
                                    $totalTSTime += $timesp;
                                    $totalTSUSD += ( $timesp * $ts->dayHourlyRate);
                                    ?>
                                    <tr>
                                        <td><?= date("d M,Y h:i a", strtotime($ts->timeIn)) ?></td>
                                        <td <?= !$ts->timeOut ? "class='text-danger bg-success'" : ""; ?>><?= date("d M,Y h:i a", strtotime( $ts->timeOut ? $ts->timeOut : date("Y-m-d H:i")))?></td>
                                        <td><?= $timesp ?></td>

                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <?php
                        if ($employeeTS) {
                            ?>
                            <tfoot>
                                <tr><th colspan="2">Total</th><th><?= $totalTSTime ?></th></tr>
                            </tfoot> 
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="clockIn" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                
                <h4 class="modal-title"><?= $currentEmployee ? $currentEmployee->firstName . " " . $currentEmployee->lastName : "" ?> Clock In</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php echo form_open('timeStation/inEM', "class='panel panel-body'"); ?>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><?= $currentEmployee ? $currentEmployee->firstName . " " . $currentEmployee->lastName . "'s" : "" ?> TimeStation Pin</label>
                        <input name="employeeID" value="<?= $employeeID ?>" type="hidden">
                        <input type="password" name="pin" required class="form-control" placeholder="Pin">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="note" class="form-control" rows="2"></textarea>
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
<div class="modal fade" id="clockOut" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                
                <h4 class="modal-title"><?= $currentEmployee ? $currentEmployee->firstName . " " . $currentEmployee->lastName : "" ?> Clock Out</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php echo form_open('timeStation/outEM', "class='panel panel-body'"); ?>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><?= $currentEmployee ? $currentEmployee->firstName . " " . $currentEmployee->lastName . "'s" : "" ?> TimeStation Pin</label>
                        <input name="tsID" value="<?= $employeesStatus ? $employeesStatus->id : "" ?>" type="hidden">
                        <input name="employeeID" value="<?= $employeeID ?>" type="hidden">
                        <input type="password" name="pin" required class="form-control" placeholder="Pin">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="note" class="form-control" rows="2"><?= $employeesStatus ? $employeesStatus->note : "" ?></textarea>
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
    window.onload = function () {
        $("#employeeOthID").select2();
        //$("#employeeOthID").select2("val", '<?= $employeeID ?>');
        $('#tableem').dataTable({
            order: [[0, "desc"]],
            "pageLength": 25
        });

        $("#employeeOthID").on("change", function () {
            open("<?= timeStation_url() ?>othersTimeStation/" + $("#employeeOthID").val(), "_self");
        });
    };
    function showEmTm() {
        open("<?= timeStation_url() ?>othersTimeStation/" + $("#employeeOthID").val(), "_self");
    }

    function showCurWeekTS() {
        open("<?= timeStation_url() ?>othersTimeStation/<?= $employeeID ?>", "_self");
            }
            function showDateSelectorTS() {
                open("<?= timeStation_url() ?>othersTimeStation/<?= $employeeID ?>?tsFrom=" + $("#tsFrom").val() + "&tsTo=" + $("#tsTo").val(), "_self");
            }

</script>