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
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title h5"><?= $employee->firstName . " " . $employee->lastName ?> TimeStation Details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-3">Name</div><div class="col-xs-9 bold"><?= $employee->firstName . " " . $employee->lastName ?></div>
                <div class="col-3">HourlyRate</div><div class="col-xs-9 bold"><?= $employee->hourlyRate ?></div>
                <div class="col-3">Deduction</div><div class="col-xs-9 bold"><?= $employee->deduct ?></div>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-hover table-striped table-bordered" id="build-data-table">
                    <thead>
                        <tr><th>Time In</th><th>Time Out</th><th>Time Spent</th><th>Balance</th><th>#</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($employeeTS) {
                            $totalTSUSD = 0;
                            $totalTSTime = 0;
                            foreach ($employeeTS as $ts) {
                                $dayHourlyRate = $ts->dayHourlyRate;
                                $mtimesp = floatval(abs(strtotime($ts->timeOut ? $ts->timeOut : date("Y-m-d H:i")) - strtotime($ts->timeIn)) / (3600));
                                $wholetimesp = floor($mtimesp);
                                $decimalTimesp = number_format($mtimesp - $wholetimesp, 2);
                                $timesp = $decimalTimesp >= .25 ? ($wholetimesp + $decimalTimesp) : $wholetimesp;
                                $timespPrice = $decimalTimesp >= .25 ? ($decimalTimesp >= .50 ? ($decimalTimesp >= .75 ? ($wholetimesp + 1) : ($wholetimesp + .75)) : ($wholetimesp + .5) ) : $wholetimesp;

                                $totalTSTime += $timesp;
                                $totalTSUSD += $timespPrice * $dayHourlyRate;
                                ?>
                                <tr>
                                    <td><?= $ts->timeIn; ?></td>
                                    <td <?= !$ts->timeOut ? "class='text-danger bg-success'" : ""; ?>><?= $ts->timeOut ? $ts->timeOut : date("Y-m-d H:i"); ?></td>
                                    <td title="to be calculated: <?= convertTime($timespPrice) . ' ~ ' . $timespPrice; ?>"><?= convertTime($timesp); ?></td>
                                    <td title="<?= "Hourly Rate: $" . $dayHourlyRate; ?>"><?= money_format('%.2n', $timespPrice * $dayHourlyRate); ?></td>
                                    <td><button class="btn btn-warning" data-remote="<?= timeStation_url("editEmTS/" . $ts->id) ?>" modal-toggler="true" data-target="#remoteModal2"><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='<?= timeStation_url('deleteTS/' . $ts->id) ?>'>Sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>"><i class="fa fa-trash"></i></button>
                                    </td>
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
                            <tr><th colspan="2">Total</th><th><?= $totalTSTime ?></th><th colspan="2"><?= money_format('%.2n', $totalTSUSD) ?></th></tr>
                        </tfoot> 
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $("#build-data-table").dataTable({order: [[0, 'desc']], pageLength: 20});
</script>