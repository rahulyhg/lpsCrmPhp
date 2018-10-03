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
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" 
                    data-toggle="dropdown" aria-haspopup="true">
                TimeStation</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">                
                <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1"
                   data-remote="<?= timeStation_url("manualEntry") ?>">
                    Manual Entry
                </a>
            </div>
        </div>
        <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1"
           data-remote="<?= timeStation_url("employeeList") ?>">
            Employee List
        </a>
    </div>
</div>
<div class="">
    <div class="row text-center">
        <div class="col-md-6 col-md-offset-3 form-inline">
            <select  id="employeeOthID" onchange="showEmTm()" data-live-search="true" value="<?= $employeeID ?>" required class="show-tick" data-showTick="true" style="width: 900%" data-showSubtext="true" title="Select an employee first">
                <?php
                if (isset($employees)) {
                    foreach ($employees as $employee) {
                        ?>
                        <option data-subtext="<?= $employee->title ?>" value="<?= $employee->id ?>"><?= $employee->emname ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <button onclick="showEmTm()" class="btn btn-success"><i class="fa fa-arrow-circle-right"></i></button>
        </div>
    </div>
    <div class="text-center">
        <div class="row">
            <div class="col-sm-12">
                <?php
                if ($employeesStatus) {
                    $ts = $employeesStatus;
                    ?>
                    <h6 class="label-info h3"><?= $currentEmployee ? $currentEmployee->emname . " is " : "" ?> currently Clocked in for <strong><?= intval(abs(strtotime($ts->timeOut ? $ts->timeOut : date("Y-m-d H:i")) - strtotime($ts->timeIn)) / (3600)); ?></strong> hours from <strong><?= $ts->timeIn ?></strong>.
                        <br><button class="btn btn-primary" data-target="#clockOut" data-toggle="modal" type="button">Clock Out here!</button></h6>
                    <?php
                } else {
                    ?>
                    <h6 class="label-info h3" data-target="#clockIn" data-toggle="modal" type="button"><?= $currentEmployee ? $currentEmployee->emname . " is " : "" ?> currently Clocked out.
                        <br><button class="btn btn-primary">Clock in here!</button></h6>
                    <?php
                }
                ?>
            </div>
        </div>
        <hr>            
        <div class="row">
            <div class="col-sm-12 form-inline">         
                <button class="btn btn-warning" onclick="showCurWeekTS()"><i class="fa fa-arrow-left">Current Week Data</i></button>
                <input class="form-control date" <?= isset($_GET["tsFrom"]) ? ' value="' . $_GET["tsFrom"] . '"' : "" ?> style="width: 150px;" id="tsFrom">
                to 
                <input class="form-control date" <?= isset($_GET["tsTo"]) ? ' value="' . $_GET["tsTo"] . '"' : "" ?>  style="width: 150px;" id="tsTo">
                <button class="btn btn-danger" onclick="showDateSelectorTS()"><i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
        <hr>
        <div class="row">                
            <div class="col-sm-12">
                <?php
                if (isset($_GET["tsFrom"]) || isset($_GET["tsTo"])) {
                    ?>
                    <h4><?= $currentEmployee ? $currentEmployee->emname . "'s" : "" ?> Details ( <?= $_GET["tsFrom"] ?> to <?= $_GET["tsTo"] ?> )</h4>
                    <?php
                } else {
                    ?>
                    <h4><?= $currentEmployee ? $currentEmployee->emname . "'s" : "" ?> Details (Current Week)</h4>
                    <?php
                }
                ?> 
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover"  id="tableem">
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
                                        <td><?= $ts->timeIn ?></td>
                                        <td <?= !$ts->timeOut ? "class='text-danger bg-success'" : ""; ?>><?= $ts->timeOut ? $ts->timeOut : date("Y-m-d H:i") ?></td>
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
<script>
</script>