<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 2:54:35 PM
 */
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
            Prospects</button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
            <?php
            if (hasPermission("annualCompliance/orders")) {
                ?>
                <a class="dropdown-item" href="<?= annualCompliance_url("orders/" . $_currentState) ?>">Orders</a>
                <?php
            }
            if (hasPermission("annualCompliance/customers")) {
                ?>
                <a class="dropdown-item" href="<?= annualCompliance_url("customers/" . $_currentState) ?>">Customers</a>
                <?php
            }
            if (hasPermission("annualCompliance/refunds")) {
                ?>
                <a class="dropdown-item" href="<?= annualCompliance_url("refunds/" . $_currentState) ?>">Refunds</a>
                <?php
            }
            ?>
        </div>
        <?php if (hasPermission("annualCompliance/add")) { ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= annualCompliance_url("newProspects/" . $_currentState) ?>">New Prospects</a>
        <?php } ?>        
        <?php if (hasPermission("annualCompliance/orderAdd")) { ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= annualCompliance_url("newPayment/" . $_currentState) ?>">New Payment</a>
        <?php } ?>

    </div>
</div>
<!----------------------------------------------------------------------------------------------------------------------->
<style>
</style>
<table class="table table-striped table-bordered table-responsive serverSide-table dtr-inline w-100 table-hover">
    <thead class="w-100">
        <tr>            
            <?php
            $loadColumns = [];
            foreach (getACStateColumns($_currentState) as $column => $show) {
                array_push($loadColumns, ["mData" => $column]);
                ?>
                <th><?= $show ?><br></th>
                <?php
            }
            array_push($loadColumns, ["mData" => "actions", "bSortable" => false]);
            $mData = json_encode($loadColumns);
            ?>
            <th>Action<br></th>
        </tr>        
    </thead>
    <tbody class="w-100">        
    </tbody>        
</table>


<script>
    var Table;
    function lo() {

    $('.salesHistory').click(function () {
    var btn = $(this);
    //e.off('click');
    //console.log(btn.attr("data-loadLink"));
    $.get(btn.attr("data-loadLink"), function (d, t, j) {
    //console.log(d, "bro");
    btn.popover({content: d}).popover('show');
    });
    });
    }
    window.onload = function () {
    geTableData();
    };
    function geTableData() {
    Table = $('.serverSide-table').DataTable({
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
<?php if (hasPermission("annualCompliance/bulk")) { ?>
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
<?php } ?>
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
            },
<?php if (hasPermission("bulkDelete")) { ?>
                {
                text: 'bulkDelete',
                        action: function () {
                        var apprReq = [];
                        var rowData = Table.rows({selected: true}).data();
                        $.each($(rowData), function (key, value) {
                        apprReq.push(value.id);
                        });
                        if (apprReq.length > 0) {
                        swal({
                        title: "Are you sure?",
                                text: "You want to delete to selected row?\n[" + apprReq.length + " row]",
                                icon: "warning",
                                buttons: {
                                cancel: {
                                text: "No, don't remove!",
                                        value: null,
                                        visible: true,
                                        className: "",
                                        closeModal: true,
                                },
                                        confirm: {
                                        text: "Yes, remove them!",
                                                value: true,
                                                visible: true,
                                                className: "",
                                                closeModal: true
                                        }
                                }
                        }).then((isConfirm) => {
                        if (isConfirm) {
                        open("<?= dashboard_url("removeBulk/" . TAB_acProspects . "/") ?>?ids=" + apprReq, "_self");
                        }
                        });
                        }
                        }
                }
<?php } ?>
            ],
            'aoColumns': <?= $mData ?>,
            "aLengthMenu": [[25, 50, 100, 200, 500, 1000, - 1], [25, 50, 100, 200, 500, 1000, "all"]],
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
            'sAjaxSource': '<?= annualCompliance_url("getProspectsData/" . $_currentState) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
            $.ajax({
            'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': function (d, e, f) {
                    lo();
                    // console.log(f);
                    fnCallback(d, e, f);
                    lo();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
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
   
<?php for ($n = 1; $n < sizeof(getACStateColumns($_currentState)); $n++) { ?>
        {column_number: <?= $n ?>, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
<?php } ?>
    ], "header");
    }


</script>