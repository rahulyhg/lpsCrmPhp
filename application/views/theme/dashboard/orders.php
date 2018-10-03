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
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
                Orders </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
                <?php
                if (hasPermission("prospects")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("prospects/" . $_currentState) ?>">Prospects</a>
                    <?php
                }
                if (hasPermission("customers")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("customers/" . $_currentState) ?>">Customers</a>
                    <?php
                } if (hasPermission("refunds")) {
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
<div class="table-responsive">
    <table class="table table-striped table-bordered serverSide-table dtr-inline w-100" style="width: 100%">
        <thead class="w-100">
            <tr>  
                <th>Date<br></th>
                <th>PaymentType<br></th>
                <th>Contact ID<br></th>            
                <th>Price<br></th>
                <th>Email<br></th>
                <th>Phone<br></th>
                <th>Shipped<br>
                    <input type="checkbox" name="shiped[]" id="checkAllOrder">    
                </th>
                <th>Action</th>
            </tr>        
        </thead>
        <tbody class="w-100">        
        </tbody>        
    </table>
</div>
<script>
    window.onload = function () {
    geTableData();
    $('.serverSide-table tbody').on('click', 'td:nth-child(3)', function () {
    var id = Table.row(this).data().prospectsID;
    $('.loader').show();
    var $_modalID = "#remoteModal1";
    $($_modalID).load("<?= dashboard_url("prospectDetails/" . $_currentState . "?prospectID=") ?>" + id, function (e) {
    setTimeout(function (e) {
    $($_modalID).modal("show");
    $('.loader').hide();
    }, 1500);
    });
    });
    $('#checkAllOrder').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red',
            increaseArea: '20%'
    });
    $("#checkAllOrder").on("ifChecked", function (e) {
    $('.selectCheckBox').iCheck("check");
    });
    $("#checkAllOrder").on("ifUnchecked", function (e) {
    $('.selectCheckBox').iCheck("uncheck");
    });
    Table.on('draw', function (e) {
    $('.selectCheckBox').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red',
            increaseArea: '20%'
    });
    });
    };
    var Table;
    function geTableData() {
    Table = $('.serverSide-table').DataTable({
    order: [[0, "DESC"]],
            "initComplete": function (settings, json) {

            },
            "columnDefs": [{
            render:function (data, type, row){
            if (data === "Check"){
            return row.checkNumber?data + "<br>" + row.checkNumber:data;
            }
            return data;
            }, targets:1
            }, {
            "render": function (data, type, row) {
            return "$" + data;
            }, "targets": 3
            }, {
            "render": function (data, type, row) {
            // console.log(data);
            return moment(data, "YYYY-MM-DD").format("DD MMM, YYYY");
            },
                    "targets": 0
            }, {
            "render": function (data, type, row) {
            return '<input type="checkbox" name="shiped[]" value="' + data + '" class="selectCheckBox">';
            },
                    "targets": 6
            }
            ],
            dom: '<"top"B<"pull-right"l>>rtip',
            buttons: ['colvis',
<?php if (hasPermission(TAB_orders . "/bulk")) { ?>
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
            {
            text: "Apply & Exported",
                    action: function (evt) {
                    if ($('.selectCheckBox:checked').length > 0) {
                    swal({
                    title: "Are you sure?",
                            text: "You want to move to orders?!",
                            icon: "warning",
                            buttons: {
                            cancel: {
                            text: "No, don't move!",
                                    value: null,
                                    visible: true,
                                    className: "",
                                    closeModal: true,
                            },
                                    confirm: {
                                    text: "Yes, move it!",
                                            value: true,
                                            visible: true,
                                            className: "",
                                            closeModal: true
                                    }
                            }
                    }).then((isConfirm) => {
                    if (isConfirm) {
                    var shippeds = [];
                    $('.selectCheckBox:checked').each(function (fn) {
                    shippeds.push($(this).val());
                    });
                    open("<?= dashboard_url("makeShipped/" . $_currentState) ?>?orders=" + shippeds, "_self");
                    setTimeout(function(){
                    location.reload();
                    },5000);
                    }
                    });
                    } else {
                    swal("Empty!", "No Row is selected!", "error");
                    }
                    }
            }

            ],
            'aoColumns': [{mData: "orderDate"}, {mData: "paymentType"},
            {mData: "contactID"}, {mData: "price"}, {mData: "email"},
            {mData: "phone"}, {mData: "id", bSortable: false},
            {mData: "actions", bSortable: false}],
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
            'sAjaxSource': '<?= dashboard_url("getOrdersData/" . $_currentState) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
            $.ajax({
            'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': function (d, e, f) {
                    console.log(f);
                    fnCallback(d, e, f);
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
    {column_number: 1, filter_default_label: "select", data: ["Check", "Credit", "Online"], filter_type: "select", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 5, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 2, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 3, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 4, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"}
    ], "header");
    }

</script>