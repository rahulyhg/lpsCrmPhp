<?php
/*
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 18, 2018 , 9:23:46 PM
 */
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
                CC Settllements</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
                <?php
                if (hasPermission("deposits/add")) {
                    ?>
                    <a class="dropdown-item"  modal-toggler="true" data-target="#remoteModal1" data-remote="<?= deposits_url("newSettllement") ?>">New Deposit</a>
                    <a class="dropdown-item" href="<?= deposits_url("terminals") ?>">Terminals</a>
                    <?php
                }
                ?>
            </div>
        </div>
        <a class="btn btn-outline-blue-grey" href="<?= deposits_url("banks") ?>">Bank</a>
    </div>
</div>
<style>
</style>
<form class="row">            
    <div class="form-group col-md col-12">
        <input type="text" class="form-control selectedDate" id="searchText1" name="dateForm" required placeholder="From Date"
               value="<?= isset($_GET["dateForm"]) ? $_GET["dateForm"] : changeDateFormatToLong(date("Y-m-01")) ?>">
    </div>
    <div class="form-group col-md col-12">
        <input type="text" class="form-control selectedDate" id="searchText2" name="dateTo" required placeholder="To Date"
               value="<?= isset($_GET["dateTo"]) ? $_GET["dateTo"] : changeDateFormatToLong(date("Y-m-t")) ?>">
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
                <?php
                if ($terminals) {
                    foreach ($terminals as $terminal) {
                        ?>
                        <th>
                            <?= $terminal->terminalName ?> 
                        </th>
                        <?php
                    }
                }
                ?>
                <th>Total</th>
                <th>Actions</th>
            </tr>        
        </thead>
        <tbody class="w-100">
            <?php
            $total = [];
            if ($settllements) {
                foreach ($settllements as $settllement) {
                    $extra = "";
                    $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' "
                            . "href='" . dashboard_url('delete/' . TAB_settllements . '/' . $settllement->settllementID . "/settllementID") . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
                    if (hasPermission(TAB_deposits . "/edit")) {
                        $extra .= "<button data-remote='" . deposits_url('editSettllement/' . $settllement->settllementID) . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
                    }
                    if (hasPermission(TAB_deposits . "/delete")) {
                        $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
                    }
                    $actions = "<div class=\"text-center\">"
                            . $extra
                            . "</div>";
                    $dateTotal = 0;
                    ?>
                    <tr>
                        <td><?= changeDateFormatToLong($settllement->date) ?></td>
                        <?php
                        $account = json_decode($settllement->account);
                        $accounts = [];
                        foreach ($account as $acc) {
                            $accounts[$acc->id] = $acc;
                        }

                        if ($terminals) {
                            foreach ($terminals as $terminal) {
                                $ammount = isset($accounts[$terminal->id]) ? $accounts[$terminal->id] : false;
                                $dateTotal += $ammount ? $ammount->value : 0;
                                if ($ammount) {
                                    if (!isset($total[$ammount->id])) {
                                        $total[$ammount->id] = 0;
                                    }
                                    if (!isset($total["dateTotal"])) {
                                        $total["dateTotal"] = 0;
                                    }
                                    $total[$ammount->id] += $ammount->value;
                                    $total["dateTotal"] += $dateTotal;
                                }
                                ?>
                                <td>
                                    <?= $ammount ? $ammount->value : 0 ?>
                                </td>
                                <?php
                            }
                        }
                        ?>
                        <td><?= $dateTotal ?></td>
                        <td><?= $actions ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>   
        <tfoot>
        <th>Count: <?= sizeof($settllements) ?></th>
        <?php
        if ($terminals) {
            foreach ($terminals as $terminal) {
                ?>
                <th>
                    <?= isset($total[$terminal->id]) ? $total[$terminal->id] : 0 ?>
                </th>
                <?php
            }
        }
        ?>
        <th>
            <?= isset($total["dateTotal"]) ? $total["dateTotal"] : 0 ?>
        </th>
        <th></th>
        </tfoot>
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
            order: [0, "DESC"],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return data;
                    },
                    "targets": 0
                }
            ],
            dom: '<"top"B<"pull-right"l>>rtip',
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
            "iDisplayLength": 25,
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