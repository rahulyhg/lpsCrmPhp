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
        if (hasPermission("brm/add")) {
            ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newBrm") ?>">New BRM</a>
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
    <table class="table table-striped table-bordered table-responsive serverSide-table dtr-inline w-100" style="width: 100%">
        <thead class="w-100">
            <tr>  
                <th style="padding-left: 5px;">Date<br></th>
                <?php
                $stateMData = [];
                array_push($stateMData, ["mData" => "date"]);
                foreach (getBrmState() as $st => $state) {
                    array_push($stateMData, ["mData" => $st . "charged", "bSortable" => false]);
                    ?>
                    <th><?= $st ?></th>
                    <?php
                }
                array_push($stateMData, ["mData" => "chargedTotal", "bSortable" => false]);
                array_push($stateMData, ["mData" => "receivedTotal", "bSortable" => false]);
                array_push($stateMData, ["mData" => "loss", "bSortable" => false]);
                array_push($stateMData, ["mData" => "lossPercent", "bSortable" => false]);
                array_push($stateMData, ["mData" => "actions", "bSortable" => false]);
                $stateMData = json_encode($stateMData)
                ?>                
                <th class="px-1">Charged</th>
                <th class="px-1">Received</th>
                <th class="px-1">Loss</th>
                <th class="px-1">Loss %</th>
                <th class="px-1">Action</th>
            </tr>
        </thead>
        <tbody class="w-100 less-padding">        
        </tbody>
        <tfoot>
            <tr>  
                <th style="padding-left: 5px;"></th>
                <?php
                foreach (getBrmState() as $st => $state) {
                    ?>
                    <th></th>
                <?php }
                ?>

                <th class="px-1"></th>
                <th class="px-1"></th>
                <th class="px-1"></th>
                <th class="px-1"></th>
                <th class="px-1"></th>
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
            if (!sums[1]){            sums[1] = [];
            sums[1]["c"] = parseInt(0); sums[1]["r"] = parseInt(0);
            }
            sums[1]["c"] += parseInt(data);
            sums[1]["r"] += parseInt(row.FLreceived);
            return "C: " + data + "<br>R: " + row.FLreceived;
            }, "targets": 1
            }, {"render": function (data, type, row) {
            if (!sums[2]){            sums[2] = [];
            sums[2]["c"] = parseInt(0); sums[2]["r"] = parseInt(0);
            }
            sums[2]["c"] += parseInt(data);
            sums[2]["r"] += parseInt(row.GAreceived);
            return "C: " + data + "<br>R: " + row.GAreceived;
            }, "targets": 2
            }, {"render": function (data, type, row) {
            if (!sums[3]){            sums[3] = [];
            sums[3]["c"] = parseInt(0); sums[3]["r"] = parseInt(0);
            }
            sums[3]["c"] += parseInt(data);
            sums[3]["r"] += parseInt(row.LAreceived);
            return "C: " + data + "<br>R: " + row.LAreceived;
            }, "targets": 3
            }, {"render": function (data, type, row) {
            if (!sums[4]){            sums[4] = [];
            sums[4]["c"] = parseInt(0); sums[4]["r"] = parseInt(0);
            }
            sums[4]["c"] += parseInt(data);
            sums[4]["r"] += parseInt(row.OHreceived);
            return "C: " + data + "<br>R: " + row.OHreceived;
            }, "targets": 4
            }, {"render": function (data, type, row) {
            if (!sums[5]){            sums[5] = [];
            sums[5]["c"] = parseInt(0); sums[5]["r"] = parseInt(0);
            }
            sums[5]["c"] += parseInt(data);
            sums[5]["r"] += parseInt(row.MAreceived);
            return "C: " + data + "<br>R: " + row.MAreceived;
            }, "targets": 5
            }, {"render": function (data, type, row) {
            if (!sums[6]){            sums[6] = [];
            sums[6]["c"] = parseInt(0); sums[6]["r"] = parseInt(0);
            }
            sums[6]["c"] += parseInt(data);
            sums[6]["r"] += parseInt(row.TXreceived);
            return "C: " + data + "<br>R: " + row.TXreceived;
            }, "targets": 6
            }, {"render": function (data, type, row) {
            if (!sums[7]){            sums[7] = [];
            sums[7]["c"] = parseInt(0); sums[7]["r"] = parseInt(0);
            }
            sums[7]["c"] += parseInt(data);
            sums[7]["r"] += parseInt(row.NCreceived);
            return "C: " + data + "<br>R: " + row.NCreceived;
            }, "targets": 7
            }, {"render": function (data, type, row) {
            if (!sums[8]){            sums[8] = [];
            sums[8]["c"] = parseInt(0); sums[8]["r"] = parseInt(0);
            }
            sums[8]["c"] += parseInt(data);
            sums[8]["r"] += parseInt(row.NJreceived);
            return "C: " + data + "<br>R: " + row.NJreceived;
            }, "targets": 8
            }, {"render": function (data, type, row) {
            if (!sums[9]){            sums[9] = [];
            sums[9]["c"] = parseInt(0); sums[9]["r"] = parseInt(0);
            }
            sums[9]["c"] += parseInt(data);
            sums[9]["r"] += parseInt(row.COreceived);
            return "C: " + data + "<br>R: " + row.COreceived;
            }, "targets": 9
            }, {"render": function (data, type, row) {
            if (!sums[10]){            sums[10] = [];
            sums[10]["c"] = parseInt(0); sums[10]["r"] = parseInt(0);
            }
            sums[10]["c"] += parseInt(data);
            sums[10]["r"] += parseInt(row.PAreceived);
            return "C: " + data + "<br>R: " + row.PAreceived;
            }, "targets": 10
            }, {"render": function (data, type, row) {
            if (!sums[11]){            sums[11] = [];
            sums[11]["c"] = parseInt(0); sums[11]["r"] = parseInt(0);
            }
            sums[11]["c"] += parseInt(data);
            sums[11]["r"] += parseInt(row.INreceived);
            return "C: " + data + "<br>R: " + row.INreceived;
            }, "targets": 11
            },
            {"render": function (data, type, row) {
            if (!sums[12]){            sums[12] = [];
            sums[12]["c"] = parseInt(0); sums[12]["r"] = parseInt(0);
            }
            sums[12]["c"] += parseInt(data);
            sums[12]["r"] += parseInt(row.MIAreceived);
            return "C: " + data + "<br>R: " + row.MIAreceived;
            }, "targets": 12
            },
            {"render": function (data, type, row) {
            if (!sums[13]){            sums[13] = [];
            sums[13]["c"] = parseInt(0); sums[13]["r"] = parseInt(0);
            }
            sums[13]["c"] += parseInt(data);
            sums[13]["r"] += parseInt(row.BROreceived);
            return "C: " + data + "<br>R: " + row.BROreceived;
            }, "targets": 13
            },
            {"render": function (data, type, row) {
            if (!sums[14]){            sums[14] = [];
            sums[14]["c"] = parseInt(0); sums[14]["r"] = parseInt(0);
            }
            sums[14]["c"] += parseInt(data);
            sums[14]["r"] += parseInt(row.HILreceived);
            return "C: " + data + "<br>R: " + row.HILreceived;
            }, "targets": 14
            },
            {"render": function (data, type, row) {
            if (!sums[15]){            sums[15] = [];
            sums[15]["c"] = parseInt(0); sums[15]["r"] = parseInt(0);
            }
            sums[15]["c"] += parseInt(data);
            sums[15]["r"] += parseInt(row.acFLreceived);
            return "C: " + data + "<br>R: " + row.acFLreceived;
            }, "targets": 15
            },
            {"render": function (data, type, row) {
            if (!sums[16]){            sums[16] = [];
            sums[16]["c"] = parseInt(0); sums[16]["r"] = parseInt(0);
            }
            sums[16]["c"] += parseInt(data);
            sums[16]["r"] += parseInt(row.acNCreceived);
            return "C: " + data + "<br>R: " + row.acNCreceived;
            }, "targets": 16
            },
            {"render": function (data, type, row) {
            if (!sums[17]){            sums[17] = [];
            sums[17]["c"] = parseInt(0); sums[17]["r"] = parseInt(0);
            }
            sums[17]["c"] += parseInt(data);
            sums[17]["r"] += parseInt(row.acNJreceived);
            return "C: " + data + "<br>R: " + row.acNJreceived;
            }, "targets": 17
            },
            {"render": function (data, type, row) {
            if (!sums[18]){            sums[18] = [];
            sums[18]["c"] = parseInt(0); sums[18]["r"] = parseInt(0);
            }
            sums[18]["c"] += parseInt(data);
            sums[18]["r"] += parseInt(row.acGAreceived);
            return "C: " + data + "<br>R: " + row.acGAreceived;
            }, "targets": 18
            },
            {"render": function (data, type, row) {
            if (!sums[19]){            sums[19] = [];
            sums[19]["c"] = parseInt(0); sums[19]["r"] = parseInt(0);
            }
            sums[19]["c"] += parseInt(data);
            sums[19]["r"] += parseInt(row.FICreceived);
            return "C: " + data + "<br>R: " + row.FICreceived;
            }, "targets": 19
            }, /////////////////////
            {"render": function (data, type, row) {
            if (!sums[20]){
            sums[20] = 0;
            }
            sums[20] += parseInt(data);
            return data;
            }, "targets": 20
            },
            {"render": function (data, type, row) {
            if (!sums[21]){
            sums[21] = 0;
            }
            sums[21] += parseInt(data);
            return data;
            }, "targets": 21
            },
            {"render": function (data, type, row) {
            if (!sums[22]){
            sums[22] = 1;
            }
            return row.receivedTotal - row.chargedTotal;
            }, "targets": 22
            }, {"render": function (data, type, row) {
            if (!sums[23]){
            sums[23] = 1;
            }
            return parseFloat(((row.receivedTotal - row.chargedTotal) / row.receivedTotal) * 100).toFixed(2) + "%";
            }, "targets": 23
            }
            ],
            dom: '<"top"B<"pull-right"l>>rtip',
            buttons: ['colvis',
<?php if (hasPermission("brm/bulk")): ?>
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
<?php endif; ?>

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
            "aLengthMenu": [[25, 50, 100, 200, 500, 1000, - 1], [25, 50, 100, 200, 500, 1000, "all"]],
            "iDisplayLength": - 1,
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
            'sAjaxSource': '<?= dashboard_url("getBrm") ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
            aoData = setDate(aoData);
            $.ajax({
            'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': function (d, e, f) {
                    console.log(f);
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
            if (sums[i]){
            if (i === 21 || i === 20){
            $(this.footer()).html(sums[i]);
            } else if (i === 22) {
            $(this.footer()).html(sums[21] - sums[20]);
            } else if (i === 23) {
             $(this.footer()).html(parseFloat(((sums[21] - sums[20]) / sums[21]) * 100).toFixed(2) + "%");
            } else{
            $(this.footer()).html("C:" + sums[i]["c"] + "<br>R: " + sums[i]["r"]);
            }
            }
            if (i === 0){
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
    }
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