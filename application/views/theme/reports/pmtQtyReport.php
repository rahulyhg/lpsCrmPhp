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
foreach (getBrmState() as $key => $value) {
   // if (strlen($key) == 2) {
        $states[$key] = $value;
    //}
}
/*$states["FIC"] = "Fictitious";

foreach (getACState() as $key => $state) {
    $states["ac" . $key] = "Annual " . $key;
}*/

$rows = [
    "paymentsAdded" => "Payments Added",
    "online" => "Online",
    "nonBrm" => "Non BRM",
    "brmCharged" => "BRM Charged",
    "brmReceived" => "BRM Received",
    "brmPercent" => "Loss %",
    "check" => "Check",
    "credit" => "Credit",
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
                $report = $reports[$key];
                ?>
                <tr>
                    <th><?= $row ?></th>
                    <?php
                    foreach ($states as $short => $state) {
                        $total += (float) str_replace(",", "", isset($report[$short]) ? $report[$short] : 0);
                        ?>
                        <td><?= isset($report[$short]) ? ($report[$short]?$report[$short]:0) : 0 ?></td><?php
                    }
                    ?>
                    <th id="tot_<?= $key ?>"><?= number_format($total, 2) ?></th>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Balance</th>
                <?php
                foreach ($states as $short => $state) {
                    ?>
                    <th></th>
                    <?php
                }
                ?>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<script>
    var Table;
    window.onload = function () {
        //calculateTotalValue();
        geTableData();
        $(".selectedDate").datetimepicker({
            format: 'DD MMM, YYYY'
        });
    };
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
                console.log(aaData);

                var api = this.api();

                api.columns().every(function (i) {
                    if (i > 0) {
                        var balance = aaData[0][i] - aaData[1][i] - aaData[2][i] - aaData[3][i];
                        $(this.footer()).html(balance);
                    }
                });
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }

        });

    }
</script>