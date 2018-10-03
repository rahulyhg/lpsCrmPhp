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
$userList = [];
$createdByList = [];
if ($users) {
    foreach ($users as $us) {
        $userList[$us->id] = $us->firstName . " " . $us->lastName;
        array_push($createdByList, ["value" => $us->id, "label" => $us->firstName . " " . $us->lastName]);
    }
}

$categoryList = [];
if ($categories) {
    foreach ($categories as $category) {
        array_push($categoryList, ["value" => $category->no, "label" => $category->name]);
    }
}
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">        
        <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newExpense") ?>">New Expense</a>
        <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("expenseCategory") ?>">Expense Category</a>
    </div>
</div>

<!----------------------------------------------------------------------------------------------------------------------->
<style>
    .less-padding td,.less-padding th{
        padding-left: 5px; padding-right: 5px;
    }
</style>

<table class="table table-striped table-bordered table-responsive serverSide-table dtr-inline w-100" style="width: 100%">
    <thead class="w-100">
        <tr>  
            <th style="padding-left: 5px;">Date<br></th>            
            <th class="">Reference</th>
            <th class="">Category<br></th>
            <th class="">Amount</th>
            <th class="">PayedBy<br></th>
            <th class="">Created By<br></th>
            <th class="">Note</th>
            <th class="">Action</th>
        </tr>
    </thead>
    <tbody class="w-100 less-padding">
    </tbody>
</table>

<script>
    window.onload = function () {
        geTableData();
    };
    var users =<?= json_encode($userList) ?>;
    var Table;
    function geTableData() {
        Table = $('.serverSide-table').DataTable({
            "initComplete": function (settings, json) {

            },
            "columnDefs": [
                {"render": function (data, type, row) {
                        // console.log(data);
                        return moment(data, "YYYY-MM-DD").format("DD MMM, YYYY");
                    },
                    "targets": 0
                },
                {
                    render: function (data, type, row) {
                        if (data == 1) {
                            return row.category + "<br><strong>[" + (users[row.employee] ? users[row.employee] : "N/A") + "]</strong>";
                        } else {
                            return row.category;
                        }
                    },
                    targets: 2
                },
                {
                    render: function (data, type, row) {
                        return users[row.createdBy];
                    },
                    targets: 5
                },
                {
                    render: function (data, type, row) {
                        return "$" + data;
                    },
                    targets: 3
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
            'aoColumns': [{mData: "date"}, {mData: "reference"}, {mData: "ecID"}, {mData: "amount"}, {mData: "paidBy"},
                {mData: "createdBy"}, {mData: "notes"}, {mData: "actions"}],
            "aLengthMenu": [[25, 50, 100, 200, 500, 1000, -1], [25, 50, 100, 200, 500, 1000, "all"]],
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
            'sAjaxSource': '<?= dashboard_url("getExpense") ?>',
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
            "fnFooterCallback": function (nRow, data, iStart, iEnd, aiDisplay) {
                /* console.log(nRow);
                 var api = this.api();
                 
                 api.columns().every(function () {
                 console.log(this);
                 var sum = this
                 .data()
                 .reduce(function (a, b) {
                 var x = parseFloat(a) || 0;
                 var y = parseFloat(b) || 0;
                 return x + y;
                 }, 0);
                 console.log(sum); //alert(sum);
                 $(this.footer()).html(sum);
                 });*/
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }

        });
        yadcf.init(Table, [
            {column_number: 0, filter_default_label: ["From Date", "End Date"],
                filter_type: "range_date",
                datepicker_type: "bootstrap-datepicker",
                date_format: 'dd M, yyyy',
                filter_delay: 500,
                filter_reset_button_text: "<i class='fa fa-close'></i>",
                filter_plugin_options: {
                    changeMonth: true,
                    changeYear: true
                }
            }, {column_number: 2, filter_default_label: "select",
                data: <?= json_encode($categoryList) ?>,
                filter_type: "select", filter_reset_button_text: "<i class='fa fa-close'></i>"
            }, {column_number: 5, filter_default_label: "select",
                data: <?= json_encode($createdByList) ?>,
                filter_type: "select", filter_reset_button_text: "<i class='fa fa-close'></i>"
            }, {column_number: 4, filter_default_label: "select",
                data: ["Cash", "Account"],
                filter_type: "select", filter_reset_button_text: "<i class='fa fa-close'></i>"}
        ], "header");
        yadcf.exFilterColumn(Table, [
            [0, {
                    from: moment().startOf('month').format("DD MMM, YYYY"),
                    to: moment().endOf('month').format("DD MMM, YYYY")
                }]
        ]);
    }

</script>