<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 2:54:35 PM
 */
//dnp(getStateColumns($_currentState));
$mData = [];
//dnp(implode(",", array_keys(getStateColumns($_currentState))));
foreach (array_keys(getStateColumns($_currentState)) as $col) {
    array_push($mData, ["mData" => $col]);
}
array_push($mData, ["mData" => "actions", "bSortable" => false, "button-export" => false]);
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
                Pospects</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
                <?php
                if (hasPermission("orders")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("orders/" . $_currentState) ?>">Orders</a>
                    <?php
                }
                if (hasPermission("customers")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("customers/" . $_currentState) ?>">Customers</a>
                    <?php
                }if (hasPermission("refunds")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("refunds/" . $_currentState) ?>">Refunds</a>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        if (hasPermission("prospects/add")) {
            ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newProspects/" . $_currentState) ?>">New Prospects</a>
            <?php
        }
        if (hasPermission("orders/add")) {
            ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newPayment/" . $_currentState) ?>">New Payment</a>
            <?php
        }
        ?>
    </div>
</div>
<!----------------------------------------------------------------------------------------------------------------------->
<style>
</style>

<table class="table table-striped table-bordered table-responsive serverSide-table dtr-inline w-100 table-hover">
    <thead class="w-100">
        <tr>            
            <?php
            foreach (getStateColumns($_currentState) as $column) {
                ?>
                <th class="text-center"><?= $column ?><br></th>
                <?php
            }
            ?>
            <th>Action</th>
        </tr>        
    </thead>
    <tbody class="w-100">        
    </tbody>        
</table>

<script>
    var Table;
    window.onload = function () {
    geTableData();
    $('.serverSide-table tbody').on('click', 'td:not(:first-child, :last-child)', function () {
    var id = Table.row(this).data().id;
    $('.loader').show();
    var $_modalID = "#remoteModal1";
    $($_modalID).load("<?= dashboard_url("newPayment/" . $_currentState . "?prospectID=") ?>" + id, function (e) {
    setTimeout(function (e) {
    $($_modalID).modal("show");
    $('.loader').hide();
    }, 1500);
    });
    });
    };
    function geTableData() {
    Table = $('.serverSide-table').DataTable({
    searchDelay:1000,
            order: [0, "DESC"],
            "columnDefs": [
            {
            "render": function (data, type, row) {
            return moment(data, "YYYY-MM-DD").format("DD MMM, YYYY");
            },
                    "targets": 0
            }
            ],
            dom: '<"top"B<"pull-right"l>>rtip',
            buttons: ['colvis',
<?php if (hasPermission(TAB_prospects . "/bulk")) { ?>{
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
                },<?php
}
if (currentUserIsAdmin()) {
    ?>

                {
                text: 'Delete Selected',
                        action: function (e) {
                        var data = Table.rows({selected: true}).data();
                        var deleteArray = [];
                        for (var i = 0; i < data.length; i++) {
                        var d = data[i];
                        deleteArray.push(d.id);
                        }
                        if (deleteArray.length > 0){
                        if (confirm("really like to delete selected rows [" + deleteArray.length + "]?")) {
                        open('<?= settings_url() ?>deleteSelected/prospects?ids=' + deleteArray, '_self');
                        }
                        } else{
                        alert("no row selected! select row by click on first column.");
                        }
                        }
                },
    <?php
}
?>
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
            'aoColumns': <?= json_encode($mData) ?>,
            "aLengthMenu": [[25, 50, 100, 200, 500, 1000, 2000, 5000, - 1], [25, 50, 100, 200, 500, 1000, 2000, 5000, "all"]],
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
            'bServerSide': true,
            'sAjaxSource': '<?= dashboard_url("getProspectsData/" . $_currentState) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
            $.ajax({
            'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': function (d, e, f) {
                    //console.log(f);
                    fnCallback(d, e, f);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR);
                    if (jqXHR.jqXHRstatusText)
                            alert(jqXHR.jqXHRstatusText);
                    }
            });
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
            // console.log(nRow);
            },
            select: {
            style: 'multi',
                    selector: 'td:first-child'
            }

    });
    yadcf.init(Table, [
    {column_number: 0, filter_default_label: ["From Date", "End Date"],
            filter_type: "range_date",
            date_format: 'dd M, yyyy',
            filter_delay: 500,
            filter_reset_button_text: "<i class='fa fa-close'></i>"
    },
<?php for ($n = 1; $n < intval(sizeof(getStateColumns($_currentState))); $n++) { ?>
        {column_number: <?= $n ?>, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
<?php } ?>
    ], "header");
    }

</script>
<?php
?>