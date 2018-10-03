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
                Terminals</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
                <?php
                if (hasPermission("deposits/add")) {
                    ?>
                    <a class="dropdown-item"  modal-toggler="true" data-target="#remoteModal1" data-remote="<?= deposits_url("newSettllement") ?>">New Settllement</a>
                    <a class="dropdown-item" href="<?= deposits_url("ccSettllement") ?>">CC Settllement</a>
                    <?php
                }
                ?>
            </div>
        </div>
        <a class="btn btn-outline-blue-grey" href="<?= deposits_url("banks") ?>">Banks Dashboard</a>
        <a class="btn btn-outline-blue-grey"  modal-toggler="true" data-target="#remoteModal1" data-remote="<?= deposits_url("newTerminal") ?>">New Terminal</a>
    </div>
</div>
<style>
</style>
<div class="table-responsive">
    <table class="table table-striped table-bordered serverSide-table dtr-inline w-100 table-hover">
        <thead class="w-100">
            <tr>         
                <th>Teminal Name<br></th>               
                <th>Company<br></th>               
                <th>Login ID<br></th>               
                <th>Account<br></th>               
                <th>Note<br></th>               
                <th>Actions</th>
            </tr>        
        </thead>
        <tbody class="w-100">        
        </tbody>        
    </table>
</div>
<script>
    var Table;
    window.onload = function () {
        geTableData();
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
            'aoColumns': [{mData: "terminalName"}, {mData: "company"}, {mData: "loginID"},
                {mData: "account"}, {mData: "note"}, {mData: "actions"}],
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
            'bServerSide': true,
            'sAjaxSource': '<?= deposits_url("getTerminals") ?>',
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
            {column_number: 0, filter_default_label: "Type...",
                filter_type: "text",
                filter_delay: 500,
                filter_reset_button_text: "<i class='fa fa-close'></i>"
            },
            {column_number: 1, filter_default_label: "Type...",
                filter_type: "text",
                filter_delay: 500,
                filter_reset_button_text: "<i class='fa fa-close'></i>"
            },
            {column_number: 2, filter_default_label: "Type...",
                filter_type: "text",
                filter_delay: 500,
                filter_reset_button_text: "<i class='fa fa-close'></i>"
            },
            {column_number: 3, filter_default_label: "Type...",
                filter_type: "text",
                filter_delay: 500,
                filter_reset_button_text: "<i class='fa fa-close'></i>"
            },
            {column_number: 4, filter_default_label: "Type...",
                filter_type: "text",
                filter_delay: 500,
                filter_reset_button_text: "<i class='fa fa-close'></i>"
            }
        ], "header");
    }

</script>