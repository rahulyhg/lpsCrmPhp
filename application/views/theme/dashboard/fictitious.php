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
            Fictitious</button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
            <?php
            if (hasPermission("fictitious/order")) {
                ?>
                <a class="dropdown-item" href="<?= dashboard_url("fictitiousOrders") ?>">Fictitious Orders</a>
                <?php
            }
            if (hasPermission("fictitious/customers")) {
                ?>
                <a class="dropdown-item" href="<?= dashboard_url("fictitiousCustomers") ?>">Fictitious Customers</a>
                <?php
            }
            if (hasPermission(TAB_fictitious . "/refund")) {
                ?>
                <a class="dropdown-item" href="<?= dashboard_url("fictRefunds") ?>">Fictitious Refunds</a>
                <?php
            }
            ?>
        </div>
        <?php if (hasPermission("fictitious/add")) { ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newFictitious") ?>">New fictitious</a>
        <?php } ?>        
        <?php if (hasPermission("fictitious/order")) { ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newFictitiousPayment") ?>">New Payment</a>
        <?php } ?>

    </div>
</div>
<!----------------------------------------------------------------------------------------------------------------------->
<style>
</style>
<table class="table table-striped table-bordered table-responsive serverSide-table dtr-inline w-100 table-hover">
    <thead class="w-100">
        <tr>            
            <th>Reg.Date<br></th>
            <th>PreSortdate<br></th>
            <th>County<br></th>
            <th>Owner<br></th>
            <th>ContactID<br></th>
            <th>Company<br></th>
            <th>Address<br></th>
            <th>City<br></th>
            <th>Zip<br></th>
            <th>State<br></th>            
            <th>Action<br></th>
        </tr>        
    </thead>
    <tbody class="w-100">        
    </tbody>        
</table>

<script>
    var Table;
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
            },
            {
            "render": function (data, type, row) {
            return moment(data, "YYYY-MM-DD").format("DD MMM, YYYY");
            },
                    "targets": 1
            }
            ],
            dom: '<"top"B<"pull-right"l>>rtip',
            buttons: ['colvis',
<?php if (hasPermission("fictitious/bulk")) { ?>
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
            }
            ],
            'aoColumns': [{mData: "regDate"}, {mData: "preSortdate"}, {mData: "county"}, {mData: "owner"},
            {mData: "contactID"}, {mData: "company"}, {mData: "address"}, {mData: "city"}, {mData: "zip"},
            {mData: "state"}, {mData: "actions", bSortable: false}],
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
            'sAjaxSource': '<?= dashboard_url("getFictitiousData") ?>',
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
    {column_number: 1, filter_default_label: ["From Date", "End Date"],
            filter_type: "range_date",
            date_format: 'dd M, yyyy',
            filter_delay: 500,
            filter_reset_button_text: "<i class='fa fa-close'></i>"
    },
    {column_number: 2, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 3, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 4, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 5, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 6, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 7, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 8, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"},
    {column_number: 9, filter_default_label: "Type here..", filter_type: "text", filter_reset_button_text: "<i class='fa fa-close'></i>"}
    ], "header");
    }

</script>