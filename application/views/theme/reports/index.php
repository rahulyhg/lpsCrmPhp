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
$states["FIC"] = "Fictitious";
$rows = [
    "paymentsAdded" => "Payments Added",
    "check" => "Check Amt",
    "checkUSDAmount" => "Check $ Amt",
    "credit" => "Credit Card Amt",
    "creditUSDAmount" => "Credit Card Tot $",
    "online" => "Online Amt",
    "onlineUSDAmount" => "Online $",
    "totalAmount" => "Total $ amt",
    "posterSent" => "Posters Sent",
    "mailing" => "Mailings",
    "brmReceived" => "BRM Received",
    "brmCharged" => "BRM Charged",
    "brmPercent" => "BRM %",
    "chargeBack" => "ChargeBacks",
    "stopPayment" => "Stop Payment",
    "refund" => "Refunds",
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
            <tr class="header">
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
                $report = $reports[$key];
                $total = 0;
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
                    <th><?= $total ?></th>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    var Table;
    window.onload = function () {
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
                // console.log(nRow);
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }

        });

    }
</script>