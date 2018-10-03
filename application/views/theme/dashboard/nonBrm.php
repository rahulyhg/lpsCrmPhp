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
        <?php
        if (hasPermission("nonBrm/add")) {
            ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" 
               data-remote="<?= dashboard_url("newNonBrm") ?>">New Non BRM</a>
               <?php
           }
           ?>
    </div>
</div>
<!----------------------------------------------------------------------------------------------------------------------->
<style>
    .less-padding td,.less-padding th{
        padding-left: 5px; padding-right: 5px;
    }
</style>
<div class="table-responsive">
    <table class="table table-striped table-bordered serverSide-table dtr-inline w-100" style="width: 100%">
        <thead class="w-100">
            <tr>                  
                <?php
                $stateMData = [];
                $columns = ["date", "stateID", "email", "regus", "ownStamps", "dayTotal"];
                foreach ($columns as $column) {
                    array_push($stateMData, ["mData" => $column, "bSortable" => false]);
                    ?>
                    <th><?= ucfirst($column) ?></th>
                    <?php
                }
                array_push($stateMData, ["mData" => "actions", "bSortable" => false]);
                $stateMData = json_encode($stateMData)
                ?>            
                <th class="px-1">Action</th>
            </tr>
        </thead>
        <tbody class="w-100 less-padding">        
        </tbody>  
        <tfoot>
            <tr>
                <?php
                $columns = ["date", "email", "regus", "ownStamps", "dayTotal"];
                foreach ($columns as $column) {
                    ?><th></th><?php
                }
                ?>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<script>
    window.onload = function () {
        geTableData();
    };
    var sums = [];
    var Table;
    function geTableData() {
        Table = $('.serverSide-table').DataTable({
            "initComplete": function (settings, json) {

            },
            "columnDefs": [
                {"render": function (data, type, row) {
                        // console.log(data);
                        return moment(data, "YYYY-MM-DD").format("dddd DD MMM, YYYY");
                    },
                    "targets": 0
                }, {"render": function (data, type, row) {
                        if (!sums[2]) {
                            sums[2] = parseInt(0);
                        }
                        sums[2] += parseInt(data ? data : 0);
                        return data;
                    }, "targets": 2
                }, {"render": function (data, type, row) {
                        if (!sums[3]) {
                            sums[3] = parseInt(data ? data : 0);
                        }
                        sums[3] += parseInt(data);
                        return data;
                    }, "targets": 3
                }, {"render": function (data, type, row) {
                        if (!sums[4]) {
                            sums[4] = parseInt(0);
                        }
                        sums[4] += parseInt(data ? data : 0);
                        return data;
                    }, "targets": 4
                }, {"render": function (data, type, row) {
                        if (!sums[5]) {
                            sums[5] = parseInt(0);
                        }
                        sums[5] += parseInt(data ? data : 0);
                        return data;
                    }, "targets": 5
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
            'aoColumns': <?= $stateMData ?>,
            "aLengthMenu": [[25, 50, 100, 200, 500, 1000, -1], [25, 50, 100, 200, 500, 1000, "all"]],
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
            'bServerSide': true,
            'sAjaxSource': '<?= dashboard_url("getNonBrm") ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData = setDate(aoData);
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': function (d, e, f) {
                        //console.log(aoData);
                        sums = [];
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
                var api = this.api();
                api.columns().every(function (i) {
                    //  console.log(sums);
                    if (sums[i]) {
                        $(this.footer()).html(sums[i]);
                    }
                    if (i === 0) {
                        $(this.footer()).html("Total");
                    }
                });
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
                filter_reset_button_text: "<i class='fa fa-close'></i>",
                filter_plugin_options: {
                    changeMonth: true,
                    changeYear: true
                }
            },
            {column_number: 1, filter_default_label: "select", data: <?= json_encode(getBRMSortState()) ?>, filter_type: "select", filter_reset_button_text: "<i class='fa fa-close'></i>"},
        ], "header");
        yadcf.exFilterColumn(Table, [
            [0, {
                    from: moment().startOf('isoweek').format("DD MMM, YYYY"),
                    to: moment().endOf('isoweek').format("DD MMM, YYYY")
                }]
        ]);
    }
    function setDate(aoData) {
        var newaoData = [];
        aoData.forEach(function (data) {
            if (data.name === "sSearch_0" && data.value === "") {
                data.value = moment().startOf('isoweek').format("DD MMM, YYYY") + "-yadcf_delim-" + moment().endOf('isoweek').format("DD MMM, YYYY");
            }
            newaoData.push(data);
        });
        //console.log(newaoData);
        return  newaoData;
    }

</script>