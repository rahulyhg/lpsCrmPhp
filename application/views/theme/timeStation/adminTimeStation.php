<?php
$totalTSUSDs = [];
$titles = [];
$totalTSTimes = [];
if ($employeeTS) {
    foreach ($employeeTS as $ts) {
        if (!isset($totalTSUSDs[$ts->employeeID])) {
            $totalTSUSDs[$ts->employeeID] = 0;
        }
        if (!isset($totalTSTimes[$ts->employeeID])) {
            $totalTSTimes[$ts->employeeID] = 0;
        }
        if (!isset($titles[$ts->employeeID])) {
            $titles[$ts->employeeID] = "";
        }
        $dayHourlyRate = $ts->dayHourlyRate;
        $mtimesp = floatval(abs(strtotime($ts->timeOut ? $ts->timeOut : date("Y-m-d H:i")) - strtotime($ts->timeIn)) / (3600));
        $wholetimesp = floor($mtimesp);
        $decimalTimesp = number_format($mtimesp - $wholetimesp, 2);
        $timesp = $decimalTimesp >= .25 ? ($wholetimesp + $decimalTimesp) : $wholetimesp;
        $timespPrice = $decimalTimesp >= .25 ? ($decimalTimesp >= .50 ? ($decimalTimesp >= .75 ? ($wholetimesp + 1) : ($wholetimesp + .75)) : ($wholetimesp + .5) ) : $wholetimesp;

        $titles[$ts->employeeID] .= "Timein:" . $ts->timeIn . "<br>Timeout:" . $ts->timeOut .
                "<br>TIme: " . convertTime($timespPrice) . "(Spent:" . convertTime($timesp) . ")<br>Pay: $" . ($timespPrice * $dayHourlyRate) . "<br><br>";
        $totalTSTimes[$ts->employeeID] += $timespPrice;
        $totalTSUSDs[$ts->employeeID] += $timespPrice * $dayHourlyRate;
    }
}

$employeedetails = [];
foreach ($employees as $emp) {
    $employeedetails[$emp->id] = $emp;
}
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" 
                    data-toggle="dropdown" aria-haspopup="true">
                TimeStation</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">                
                <a class="dropdown-item"  data-target="#addNew" data-toggle="modal">
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

<div class="row justify-content-center">
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
            <h4>Employee Details ( <?= $_GET["tsFrom"] ?> to <?= $_GET["tsTo"] ?> )</h4>
            <?php
        } else {
            ?>
            <h4>Employee Details (Current Week)</h4>
            <?php
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-hover"  id="table">
                <thead>      
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Designation</th>
                        <th class="text-center">Total Hours</th>
                        <th class="text-center">Total Pay</th>
                        <th>Deductions</th>
                        <th>Action</th>
                    </tr>                              
                </thead>
                <tbody>
                    <?php
                    if (isset($timeStationData)) {
                        $totalTSUSD = 0;
                        $totalTSTime = 0;
                        $toalDeduct = 0;
                        foreach ($timeStationData as $tsd) {
                            $totalTSUSD += $totalTSUSDs[$tsd->employeeID];
                            $totalTSTime += $totalTSTimes[$tsd->employeeID];
                            $toalDeduct += $employeedetails[$tsd->employeeID]->deduct;
                            ?>

                            <tr>
                                <th><?= $employeedetails[$tsd->employeeID]->firstName . " " . $employeedetails[$tsd->employeeID]->lastName ?></th>
                                <td><?= $employeedetails[$tsd->employeeID]->designation ?></td>
                                <td data-content="<?= $titles[$tsd->employeeID] ?>"  data-toggle="popover" title="<button class='pop-close btn btn-sm btn-defult'><i class='fa fa-close text-danger'></i></button> Details" ><?= $totalTSTimes[$tsd->employeeID] ?></td>
                                <td data-content="<?= $titles[$tsd->employeeID] ?>"  data-toggle="popover" title="<button class='pop-close btn btn-sm btn-defult'><i class='fa fa-close text-danger'></i></button> Details" ><?= money_format('%.2n', $totalTSUSDs[$tsd->employeeID]) ?></td>
                                <td><?= $employeedetails[$tsd->employeeID]->deduct ?></td>
                                <td><button modal-toggler='true' data-target='#remoteModal1' class="btn btn-link" data-remote="<?= timeStation_url("employeTimeDetails/" . $tsd->employeeID) ?>"><i class="fa fa-list"></i></button></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
                <?php
                if ($timeStationData) {
                    ?>
                    <tfoot>
                        <tr><th colspan="2">Total</th><th colspan="1"><?= $totalTSTime ?></th><th><?= money_format('%.2n', $totalTSUSD) ?></th><th colspan="2"><?= $toalDeduct ?></th></tr>
                    </tfoot> 
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addNew" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                
                <h4 class="modal-title">Add New</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php echo form_open('timeStation/addNewEmpToTS', "class='panel panel-body'"); ?>
                <div class="error"><?php echo validation_errors(); ?></div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Employee Name</label>
                            <select  name="employeeID" data-live-search="true" required class="form-control slelectTwo show-tick" data-showTick="true" style="width: 100%" data-showSubtext="true" title="Select an employee first">

                                <?php
                                if (isset($employees)) {
                                    foreach ($employees as $employee) {
                                        ?>
                                        <option data-subtext="<?= $employee->designation ?>" value="<?= $employee->id ?>"><?= $employee->firstName . " " . $employee->lastName ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>                
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Time In</label>
                            <input type="text" name="timeIn" required class="form-control dateTimePicker" placeholder="Pick In Time">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Time Out</label>
                            <input type="text" name="timeOut" class="form-control dateTimePicker" placeholder="Pick Out Time">
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
                                <button class="btn btn-success" id="emsave" type="submit" value="Save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>           
        </div>
    </div>
</div>

<script>
    function showCurWeekTS() {
        open("<?= timeStation_url() ?>", "_self");
    }
    function showDateSelectorTS() {
        open("<?= timeStation_url() ?>?tsFrom=" + $("#tsFrom").val() + "&tsTo=" + $("#tsTo").val(), "_self");
    }
    window.onload = function () {
        $(".select-2").select2();
        $("#table2").DataTable();
        $("#table").DataTable();
    };
</script>     
