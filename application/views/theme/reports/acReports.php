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
        <input type="text" class="form-control selectedDate" id="searchText1" name="dateForm"  required placeholder="From Date"
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
                <th>State</th>
                <th>Payments Qty</th>
                <th>Customers Qty</th>
                <th>Total CC</th>
                <th>Total Checks</th>			
                <th>Pmt Total $</th>
                <th>Dep Total $</th>
                <th>Dep Checks</th>
                <th>Dep CC</th>
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
            // dnp($reports);
            $total["paymentQty"] = 0;
            $total["customers"] = 0;
            $total["creditTotal"] = 0;
            $total["checkTotal"] = 0;
            $total["dollerTotal"] = 0;

            $total["depositsTotal"] = 0;
            $total["depositsValue"] = 0;
            $total["settllementsValue"] = 0;

            $total["custTotal"] = 0;
            $total["pubCost"] = 0;

            $total["RefundAmount"] = 0;
            $total["RefundNum"] = 0;
            $total["StopPaymentAmount"] = 0;
            $total["StopPaymentNum"] = 0;
            $total["ChargeBackAmount"] = 0;
            $total["ChargeBackNum"] = 0;

            $total["brmCharged"] = 0;
            $total["nonBrm"] = 0;
            $total["mailing"] = 0;

            $exist = ceil(sizeof(getACState()) / 2);
            $i = 0;
            foreach (getACState() as $id => $state) {
                $i++;
                $total["paymentQty"] += (isset($reports["acOrder"][$id]) ? $reports["acOrder"][$id]->paymentQty : 0);
                $total["customers"] += (isset($reports["acCustomer"][$id]) ? $reports["acCustomer"][$id]->customers : 0);
                $total["creditTotal"] += (isset($reports["credit"][$id]) ? $reports["credit"][$id]->creditTotal : 0 );
                $total["checkTotal"] += (isset($reports["check"][$id]) ? $reports["check"][$id]->checkTotal : 0 );
                $total["dollerTotal"] += ( isset($reports["acOrder"][$id]) ? $reports["acOrder"][$id]->dollerTotal : 0 );

                /**
                 * 
                 */
                $depositsValue = (isset($reports["deposits"]) ? $reports["deposits"]->value : 0 );
                $settllementsValue = ( isset($reports["settllements"]) ? $reports["settllements"]->value : 0 );
                ;
                /**
                 * 
                 */
                $total["depositsValue"] = $depositsValue;
                $total["depositsTotal"] = $depositsValue + $settllementsValue;
                $total["settllementsValue"] = $settllementsValue;

                $total["custTotal"] += (isset($reports["acCustomer"][$id]) ? $reports["acCustomer"][$id]->custTotal : 0 );
                $total["pubCost"] += ( isset($reports["acCustomer"][$id]) ? $reports["acCustomer"][$id]->pubCost : 0 );

                $total["RefundAmount"] += ( isset($reports["Refund"][$id]) ? $reports["Refund"][$id]->amount : 0);
                $total["RefundNum"] += ( isset($reports["Refund"][$id]) ? $reports["Refund"][$id]->num : 0);
                $total["StopPaymentAmount"] += (isset($reports["StopPayment"][$id]) ? $reports["StopPayment"][$id]->amount : 0 );
                $total["StopPaymentNum"] += (isset($reports["StopPayment"][$id]) ? $reports["StopPayment"][$id]->num : 0 );
                $total["ChargeBackAmount"] += ( isset($reports["ChargeBack"][$id]) ? $reports["ChargeBack"][$id]->amount : 0 );
                $total["ChargeBackNum"] += ( isset($reports["ChargeBack"][$id]) ? $reports["ChargeBack"][$id]->num : 0);

                $total["brmCharged"] += ( isset($reports["brmCharged"]["ac" . $id]) ? $reports["brmCharged"]["ac" . $id] : 0);
                $total["nonBrm"] += ( isset($reports["nonBrm"]["ac" . $id]) ? $reports["nonBrm"]["ac" . $id] : 0);
                $total["mailing"] += ( isset($reports["mailing"]["ac" . $id]) ? $reports["mailing"]["ac" . $id] : 0);
                ?>
                <tr>
                    <th><?= $id ?></th>
                    <td><?= number_format(isset($reports["acOrder"][$id]) ? $reports["acOrder"][$id]->paymentQty : 0, 0) ?></td>
                    <td><?= number_format(isset($reports["acCustomer"][$id]) ? $reports["acCustomer"][$id]->customers : 0, 0) ?></td>
                    <td><?= number_format(isset($reports["credit"][$id]) ? $reports["credit"][$id]->creditTotal : 0, 2) ?></td>
                    <td><?= number_format(isset($reports["check"][$id]) ? $reports["check"][$id]->checkTotal : 0, 2) ?></td>
                    <td><?= number_format(isset($reports["acOrder"][$id]) ? $reports["acOrder"][$id]->dollerTotal : 0, 2) ?></td>
                    <?php
                    if ($exist == $i) {
                        ?>
                        <td class="bg-primary bg-lighten-5 border-bottom-0"><?= $depositsValue + $settllementsValue ?></td>
                        <td class="bg-primary bg-lighten-4 border-top-0 border-bottom-0"><?= $depositsValue ?></td>
                        <td class="bg-primary bg-lighten-5 border-top-0"><?= $settllementsValue ?></td>
                        <?php
                    } else {
                        ?>
                        <td class="bg-primary bg-lighten-5 border-bottom-0"><?= "" ?></td>
                        <td class="bg-primary bg-lighten-4 border-top-0 border-bottom-0"><?= "" ?></td>
                        <td class="bg-primary bg-lighten-5 border-top-0"><?= "" ?></td>
                        <?php
                    }
                    ?>
                    <td><?= number_format(isset($reports["acCustomer"][$id]) ? $reports["acCustomer"][$id]->custTotal : 0, 2) ?></td>
                    <td><?= number_format(isset($reports["acCustomer"][$id]) ? $reports["acCustomer"][$id]->pubCost : 0, 2) ?></td>

                    <td><?= number_format(isset($reports["Refund"][$id]) ? $reports["Refund"][$id]->amount : 0, 2) ?></td>
                    <td><?= number_format(isset($reports["Refund"][$id]) ? $reports["Refund"][$id]->num : 0, 0) ?></td>
                    <td><?= number_format(isset($reports["StopPayment"][$id]) ? $reports["StopPayment"][$id]->amount : 0, 2) ?></td>
                    <td><?= number_format(isset($reports["StopPayment"][$id]) ? $reports["StopPayment"][$id]->num : 0, 0) ?></td>
                    <td><?= number_format(isset($reports["ChargeBack"][$id]) ? $reports["ChargeBack"][$id]->amount : 0, 2) ?></td>
                    <td><?= number_format(isset($reports["ChargeBack"][$id]) ? $reports["ChargeBack"][$id]->num : 0, 0) ?></td>

                    <td><?= number_format(isset($reports["brmCharged"]["ac" . $id]) ? $reports["brmCharged"]["ac" . $id] : 0, 0) ?></td>
                    <td><?= number_format(isset($reports["nonBrm"]["ac" . $id]) ? $reports["nonBrm"]["ac" . $id] : 0, 0) ?></td>
                    <td><?= number_format(isset($reports["mailing"]["ac" . $id]) ? $reports["mailing"]["ac" . $id] : 0, 0) ?></td>

                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <?php
                $n = 0;
                $arr = [0, 1, 11, 13, 15, 16, 17, 18];
                foreach ($total as $to) {
                    if (in_array($n, $arr, true)) {
                        ?>
                        <th>
                            <?= number_format($to, 0) ?>
                        </th>
                        <?php
                    } else {
                        ?>
                        <th>
                            <?= number_format($to, 2) ?>
                        </th>
                        <?php
                    }
                    $n++;
                }
                ?>
            </tr>
            <?php
            $s = "(" . $total["custTotal"] . "- " . $total["pubCost"] . "- (" . $total["RefundNum"] . "* (" . $total["RefundAmount"]
                    . "+ 2)) - (" . $total["StopPaymentNum"] . " * (" . $total["StopPaymentAmount"] . " + 12)) - (" .
                    $total["ChargeBackNum"] . "* (" . $total["ChargeBackAmount"] . "+ 25)) - (" . $total["mailing"] . "* .41) - (" . $total["brmCharged"] . " * 1.5) ) * .7";

            $net = ($total["custTotal"] - $total["pubCost"] - ($total["RefundNum"] * ($total["RefundAmount"] + 2)) - ($total["StopPaymentNum"] * ($total["StopPaymentAmount"] + 12)) - ($total["ChargeBackNum"] * ($total["ChargeBackAmount"] + 25)) - ($total["mailing"] * .41) - ($total["brmCharged"] * 1.5)) * .7;
            ?>
            <tr class="success text-bold-800 bg-secondary">
                <th colspan="2">Balance</th>
                <th colspan="4"><h2 class="text-bold"><?= number_format(($total["dollerTotal"] - $total["depositsTotal"]), 2) ?></h2></th>
                <th colspan="2">Net Balance</th>
                <th colspan="12"><h2 class="text-bold"><?= number_format($net, 2) ?></h2></th>
            </tr>
            <tr><th colspan="20"><?= $s ?></th></tr>
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