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
                <th>Date</th>
                <th>Payments Qty</th>
                <th>customers</th>
                <th>Fictitious total $</th>
                <th>Fict Checks</th>
                <th>Fict  CC</th>
                <th>Fict Deposits</th>
                <th>Fictitious Check</th>
                <th>Fictitious CC</th>
                <th>Customers $</th>
                <th>Publishing Cost</th>

                <th>Refunds</th>
                <th>Refund Qty</th>
                <th>Stop pmt</th>
                <th>Stop pmt qty</th>
                <th>ChargeBack</th>
                <th>Chbk Qty</th>

                <th>BRM Charged</th>
                <th>NonBRM</th>
                <th>Mailing</th>
            </tr>        
        </thead>
        <tbody class="w-100">
            <?php
            $total["paymentQty"] = 0;
            $total["customers"] = 0;
            $total["dollerTotal"] = 0;
            $total["checkTotal"] = 0;
            $total["creditTotal"] = 0;
            $total["custTotal"] = 0;
            $total["pubCost"] = 0;

            $total["fictDeposit"] = 0;
            $total["fictCheck"] = 0;
            $total["fictCc"] = 0;

            $total["RefundAmount"] = 0;
            $total["RefundNum"] = 0;
            $total["StopPaymentAmount"] = 0;
            $total["StopPaymentNum"] = 0;
            $total["ChargeBackAmount"] = 0;
            $total["ChargeBackNum"] = 0;

            $total["brmCharged"] = 0;
            $total["nonBrm"] = 0;
            $total["mailing"] = 0;
            foreach ($reports["fictOrder"] as $key => $fictOrder) {
                $fictCustomer = isset($reports["fictCustomer"][$key]) ? $reports["fictCustomer"][$key] : 0;
                $credit = isset($reports["credit"][$key]) ? $reports["credit"][$key] : 0;
                $check = isset($reports["check"][$key]) ? $reports["check"][$key] : 0;

                $deposits = isset($reports["deposits"][$key]) ? $reports["deposits"][$key] : 0;
                $settllements = isset($reports["settllements"][$key]) ? $reports["settllements"][$key] : 0;

                $total["paymentQty"] += $fictOrder->paymentQty;
                $total["customers"] += $fictCustomer ? $fictCustomer->customers : 0;
                $total["dollerTotal"] += $fictOrder->dollerTotal;
                $total["checkTotal"] += $check ? $check->checkTotal : 0;
                $total["creditTotal"] += $credit ? $credit->creditTotal : 0;
                $total["custTotal"] += $fictCustomer ? $fictCustomer->custTotal : 0;
                $total["pubCost"] += $fictCustomer ? $fictCustomer->pubCost : 0;

                $total["fictDeposit"] += $deposits ? $deposits->value : 0;
                $total["fictDeposit"] += $settllements ? $settllements->value : 0;
                $total["fictCheck"] += $deposits ? $deposits->value : 0;
                $total["fictCc"] += $settllements ? $settllements->value : 0;

                $total["RefundAmount"] += ( isset($reports["Refund"][$key]) ? $reports["Refund"][$key]->amount : 0);
                $total["RefundNum"] += ( isset($reports["Refund"][$key]) ? $reports["Refund"][$key]->num : 0);
                $total["StopPaymentAmount"] += (isset($reports["StopPayment"][$key]) ? $reports["StopPayment"][$key]->amount : 0 );
                $total["StopPaymentNum"] += (isset($reports["StopPayment"][$key]) ? $reports["StopPayment"][$key]->num : 0 );
                $total["ChargeBackAmount"] += ( isset($reports["ChargeBack"][$key]) ? $reports["ChargeBack"][$key]->amount : 0 );
                $total["ChargeBackNum"] += ( isset($reports["ChargeBack"][$key]) ? $reports["ChargeBack"][$key]->num : 0);


                $total["brmCharged"] += (isset($reports["brmCharged"][$key]->FIC) ? $reports["brmCharged"][$key]->FIC : 0);
                $total["nonBrm"] += (isset($reports["nonBrm"][$key]->FIC) ? $reports["nonBrm"][$key]->FIC : 0);
                $total["mailing"] += (isset($reports["mailing"][$key]->FIC) ? $reports["mailing"][$key]->FIC : 0);
                ?>
                <tr>
                    <td><?= changeDateFormatToLong($key) ?></td>
                    <td><?= number_format($fictOrder->paymentQty, 0) ?></td>
                    <td><?= number_format($fictCustomer ? $fictCustomer->customers : 0, 0) ?></td>
                    <td><?= "$" . number_format($fictOrder->dollerTotal, 2) ?></td>
                    <td><?= "$" . number_format($check ? $check->checkTotal : 0, 2) ?></td>            
                    <td><?= "$" . number_format($credit ? $credit->creditTotal : 0, 2) ?></td>

                    <td><?= "$" . number_format(($deposits ? $deposits->value : 0) + ($settllements ? $settllements->value : 0), 2) ?></td>
                    <td><?= "$" . number_format(($deposits ? floatval($deposits->value) : 0), 2) ?></td>
                    <td><?= "$" . number_format(($settllements ? floatval($settllements->value) : 0), 2) ?></td>

                    <td><?= "$" . number_format($fictCustomer ? $fictCustomer->custTotal : 0, 2) ?></td>            
                    <td><?= "$" . number_format($fictCustomer ? $fictCustomer->pubCost : 0, 2) ?></td>

                    <td><?= (isset($reports["Refund"][$key]) ? $reports["Refund"][$key]->amount : 0 ) ?></td>
                    <td><?= (isset($reports["Refund"][$key]) ? $reports["Refund"][$key]->num : 0) ?></td>
                    <td><?= isset($reports["StopPayment"][$key]) ? $reports["StopPayment"][$key]->amount : 0 ?></td>
                    <td><?= isset($reports["StopPayment"][$key]) ? $reports["StopPayment"][$key]->num : 0 ?></td>
                    <td><?= isset($reports["ChargeBack"][$key]) ? $reports["ChargeBack"][$key]->amount : 0 ?></td>
                    <td><?= isset($reports["ChargeBack"][$key]) ? $reports["ChargeBack"][$key]->num : 0 ?></td>

                    <td><?= isset($reports["brmCharged"][$key]->FIC) ? $reports["brmCharged"][$key]->FIC : 0 ?></td>
                    <td><?= isset($reports["nonBrm"][$key]->FIC) ? $reports["nonBrm"][$key]->FIC : 0 ?></td>
                    <td><?= isset($reports["mailing"][$key]->FIC) ? $reports["mailing"][$key]->FIC : 0 ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <td>Total</td>
                <td><?= number_format($total["paymentQty"], 0) ?></td>
                <td><?= number_format($total["customers"], 0) ?></td>
                <td><?= "$" . number_format($total["dollerTotal"], 2) ?></td>
                <td><?= "$" . number_format($total["checkTotal"], 2) ?></td>            
                <td><?= "$" . number_format($total["creditTotal"], 2) ?></td>

                <td><?= "$" . number_format($total["fictDeposit"], 2) ?></td>
                <td><?= "$" . number_format($total["fictCheck"], 2) ?></td>
                <td><?= "$" . number_format($total["fictCc"], 2) ?></td>

                <td><?= "$" . number_format($total["custTotal"], 2) ?></td>            
                <td><?= "$" . number_format($total["pubCost"], 2) ?></td>

                <td><?= "$" . number_format($total["RefundAmount"], 2) ?></td>            
                <td><?= $total["RefundNum"] ?></td>
                <td><?= "$" . number_format($total["StopPaymentAmount"], 2) ?></td>            
                <td><?= $total["StopPaymentNum"] ?></td>
                <td><?= "$" . number_format($total["ChargeBackAmount"], 2) ?></td>            
                <td><?= $total["ChargeBackNum"] ?></td>

                <td><?= "$" . number_format($total["brmCharged"], 2) ?></td>
                <td><?= "$" . number_format($total["nonBrm"], 2) ?></td>
                <td><?= "$" . number_format($total["mailing"], 2) ?></td>
            </tr>
            <tr class="success text-bold-800 bg-secondary">
                <td colspan="2"><strong>Balance</strong></td>
                <th colspan="3"><h2 class="text-bold"><?= number_format(($total["dollerTotal"] - $total["fictDeposit"]), 2) ?></h2></th>
                <td colspan="3"><strong>Net Total</strong></td>
                <?php
                $net = ($total["custTotal"] - $total["pubCost"] - ($total["RefundNum"] * ($total["RefundAmount"] + 2)) - ($total["StopPaymentNum"] * ($total["StopPaymentAmount"] + 12)) - ($total["ChargeBackNum"] * ($total["ChargeBackAmount"] + 25))) * .7;
                ?>
                <th colspan="12"><h2 class="text-bold"><?= number_format($net, 2) ?></h2></th>
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
                // console.log(nRow);
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }

        });

    }
</script>