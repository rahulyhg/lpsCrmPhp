<?php
/*
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 29, 2018 , 7:50:22 PM
 */
$states = [];
foreach (getState() as $key => $value) {
    if (strlen($key) == 2) {
        $states[$key] = $value;
    }
}
//$states["FIC"] = "Fictitious";
$rows = [
    "brmReceived" => "BRM Received",
    "brmCharged" => "BRM Charged",
    "paymentsAdded" => "Payments Added",
    "brmCost" => "BRM Cost",    
    "creditUSDAmount" => "Credit Card Tot $",
    "checkUSDAmount" => "Check $",
    "onlineUSDAmount" => "Online $",
    "totalAmount" => "Total Charged $",
    "mailing" => "Mailings",
    "mailCost" => "Mailing Cost",
    "posters" => "Posters",
    "refunds" => "Refunds",
    "chargeBacks" => "ChargeBacks",
    "stopPayments" => "Stop Payment",
    "nonBrm"=>"Non BRM"
];
$day = date('w') - 1;
$dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
$dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">     
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
                <?= isset($navMeta["pageTitle"]) ? $navMeta["pageTitle"] : "Report" ?></button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
                <?php
                if (hasPermission("reports/general")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url() ?>">General Reports</a>
                    <?php
                }
                if (hasPermission("reports/state PnL")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("plReports") ?>"> State P&L Reports</a>
                    <?php
                }
                if (hasPermission("reports/fictitious PnL")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("plFictitious") ?>"> Fictitious P&L Reports</a>
                    <?php
                }
                if (hasPermission("reports/pmtQtyReport")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("pmtQtyReport") ?>"> Pmt Qty Report</a>
                    <?php
                }
                if (hasPermission("reports/acReports")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("acReports") ?>">Annual Compliance Report</a>
                    <?php
                }
                if (hasPermission("reports/ordersReport")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("ordersReport") ?>">Orders Report</a>
                    <?php
                }if (hasPermission("reports/proDocReport")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("proDocReport") ?>">Property Doc Report</a>
                    <?php
                }if (hasPermission("reports/conversionRateReport")) {
                    ?>
                    <a class="dropdown-item" href="<?= reports_url("conversionRateReport") ?>">Conversion Rate Report</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<style>
</style>
<form class="row">            
    <div class="form-group col-md col-12">
        <input type="text" class="form-control selectedDate" id="searchText1" name="dateForm" required placeholder="From Date"
               value="<?= isset($_GET["dateForm"]) ? $_GET["dateForm"] : $dateForm ?>">
    </div>
    <div class="form-group col-md col-12">
        <input type="text" class="form-control selectedDate" id="searchText2" name="dateTo" required placeholder="To Date"
               value="<?= isset($_GET["dateTo"]) ? $_GET["dateTo"] : $dateTo ?>">
    </div>
    <div class="form-group col-md-auto col-12">
        <button type="submit" class="btn btn-primary mb-2 w-100"><i class="ft-arrow-right"></i> Go</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered serverSide-table dtr-inline w-100 table-hover">
        <thead class="w-100">
            <tr>
                <th></th>
                <?php
                foreach ($states as $short => $state) {
                    ?><th><?= $short ?></th><?php
                    }
                    ?>
                <th>Total</th>
            </tr>        
        </thead>
        <tbody class="w-100">
            <?php
            foreach ($rows as $key => $row) {
                $total = 0;
                if ($key === "mailCost") {
                    $report = $reports["mailing"];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $val = str_replace(",", "", $report[$short]) * .40;
                            $total += (float) $val;
                            ?>
                            <td><?= number_format($val, 2) ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                } else if ($key === "brmCost") {
                    $report = $reports["brmReceived"];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $val = str_replace(",", "", $report[$short]) * .60;
                            $total += (float) $val;
                            ?>
                            <td><?= number_format($val, 2) ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                } else if ($key === "posters") {
                    $report = $reports["brmCharged"];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $val = str_replace(",", "", $report[$short]) * 1.35;
                            $total += (float) $val;
                            ?>
                            <td><?= number_format($val, 2) ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                } else if ($key === "refunds") {
                    $report = $reports["refund"];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $val = (str_replace(",", "", $report[$short]) * 2) + $reports["refundAmount"][$short];
                            $total += (float) $val;
                            ?>
                            <td><?= number_format($val, 2) ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                } else if ($key === "chargeBacks") {
                    $report = $reports["chargeBack"];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $val = (str_replace(",", "", $report[$short]) * 25) + $reports["chargeBackAmount"][$short];
                            $total += (float) $val;
                            ?>
                            <td><?= number_format($val, 2) ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                } else if ($key === "stopPayments") {
                    $report = $reports["stopPayment"];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $val = (str_replace(",", "", $report[$short]) * 12) + $reports["stopPaymentAmount"][$short];
                            $total += (float) $val;
                            ?>
                            <td><?= number_format($val, 2) ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                } else {
                    $report = $reports[$key];
                    ?>
                    <tr>
                        <th><?= $row ?></th>
                        <?php
                        foreach ($states as $short => $state) {
                            $total += (float) str_replace(",", "", $report[$short]);
                            ?>
                            <td><?= $report[$short] ?></td><?php
                        }
                        ?>
                        <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6">
                    <input class="form-control" onkeyup="calculateTotalValue()" id="fixedExp" placeholder="Fixed Exp" value="3800" type="number">
                </th>
                <th colspan="7" class="bg-success h2 text-bold-800" id="totalValue">

                </th>

            </tr>
        </tfoot>
    </table>
</div>
<script>
    var Table;
    window.onload = function () {
        calculateTotalValue();
        geTableData();
        $(".selectedDate").datetimepicker({
            format: 'DD MMM, YYYY'
        });
    };
    function calculateTotalValue() {
        var totalValue = 0;
        var charged = parseFloat($("#tot_totalAmount").html().toString().replaceAll(",", ""));
        var cost = parseFloat($("#tot_brmCost").html().toString().replaceAll(",", ""));
        var mailingCost = parseFloat($("#tot_mailCost").html().toString().replaceAll(",", ""));
        var posters = parseFloat($("#tot_posters").html().toString().replaceAll(",", ""));
        var refunds = parseFloat($("#tot_refunds").html().toString().replaceAll(",", ""));
        var stopPayments = parseFloat($("#tot_stopPayments").html().toString().replaceAll(",", ""));
        var chargeBacks = parseFloat($("#tot_chargeBacks").html().toString().replaceAll(",", ""));
        var fixedExp = parseFloat($("#fixedExp").val());
        //console.log(charged, mailingCost, posters, refunds, stopPayments, chargeBacks, fixedExp);
        totalValue = (charged - cost - mailingCost - posters - refunds - stopPayments - chargeBacks - fixedExp) * .7;
        $("#totalValue").html(parseFloat(totalValue).toFixed(2));
    }
    function geTableData() {
        Table = $('.serverSide-table').DataTable({
            searchDelay: 1000,
            order: [],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return data;
                    },
                    "targets": 0
                }
            ],
            dom: '<"top"B>rt',
            buttons: ['colvis',
                {
                    extend: 'collection',
                    text: 'Export Selected',
                    buttons: [
                        {
                            extend: 'csv',
                            text: 'Export as Csv [All]',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csv',
                            text: 'Export as Csv [Selected]',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    selected: true
                                }
                            },
                        },
                        {
                            extend: 'excel',
                            text: 'Export as Excel [All]',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Export as Excel [Selected]',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    selected: true
                                }
                            },
                        },
                        {
                            extend: 'pdf',
                            text: 'Export as PDF [All]',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'Export as PDF [Selected]',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    selected: true
                                }
                            },
                        },
                        {
                            extend: 'print',
                            text: 'Print [All]',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Print [Selected]',
                            exportOptions: {
                                columns: ':visible',
                                modifier: {
                                    selected: true
                                }
                            },
                        }
                    ]
                },
                {
                    text: 'All',
                    action: function () {
                        Table.rows().select();
                    }
                },
                {
                    text: 'None',
                    action: function () {
                        Table.rows().deselect();
                    }
                }
            ],
            "aLengthMenu": [[25, 50, 100, 200, 500, 1000, 2000, 5000, -1], [25, 50, 100, 200, 500, 1000, 2000, 5000, "all"]],
            "iDisplayLength": -1,
            'bProcessing': true,
            "language": {
                buttons: {
                    excel: "Excel",
                    pdf: "pdf",
                    selectAll: "all",
                    selectNone: "none"
                },
                processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                // console.log(nRow);
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }

        });

    }
</script>