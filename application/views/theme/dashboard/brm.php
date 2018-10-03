<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 2:54:35 PM
 */
//dnd(getBrmState());
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
    var colCon = 0;
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
            },
<?php
$colCon = 1;
foreach (getBrmState() as $st => $state) {
    ?>
                {"render": function (data, type, row) {
                if (!sums[<?= $colCon ?>]){            sums[<?= $colCon ?>] = [];
                sums[<?= $colCon ?>]["c"] = parseInt(0); sums[<?= $colCon ?>]["r"] = parseInt(0);
                }
                sums[<?= $colCon ?>]["c"] += parseInt(data);
                sums[<?= $colCon ?>]["r"] += parseInt(row.<?= $st ?>received);
                return "C: " + data + "<br>R: " + row.<?= $st ?>received;
                }, "targets": <?= $colCon ?>
                },
    <?php
    $colCon++;
}
?>/////////////////////
            {"render": function (data, type, row) {
            if (!sums[<?= $colCon ?>]){
            sums[<?= $colCon ?>] = 0;
            }
            sums[<?= $colCon ?>] += parseInt(data);
            return data;
            }, "targets": <?= $colCon++ ?>
            },
            {"render": function (data, type, row) {
            if (!sums[<?= $colCon ?>]){
            sums[<?= $colCon ?>] = 0;
            }
            sums[<?= $colCon ?>] += parseInt(data);
            return data;
            }, "targets": <?= $colCon++ ?>
            },
            {"render": function (data, type, row) {
            if (!sums[<?= $colCon ?>]){
            sums[<?= $colCon ?>] = 1;
            }
            return row.receivedTotal - row.chargedTotal;
            }, "targets": <?= $colCon++ ?>
            }, {"render": function (data, type, row) {
            if (!sums[<?= $colCon ?>]){
            sums[<?= $colCon ?>] = 1;
            }
            return parseFloat(((row.receivedTotal - row.chargedTotal) / row.receivedTotal) * 100).toFixed(2) + "%";
            }, "targets": <?= $colCon++ ?>
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
            if (i === <?= sizeof(getBrmState())+2 ?> || i === <?= sizeof(getBrmState())+1 ?>){
            $(this.footer()).html(sums[i]);
            } else if (i === <?= sizeof(getBrmState())+3 ?>) {
            $(this.footer()).html(sums[<?= sizeof(getBrmState())+2 ?>] - sums[<?= sizeof(getBrmState())+1 ?>]);
            } else if (i === <?= sizeof(getBrmState())+4 ?>) {
            $(this.footer()).html(parseFloat(((sums[<?= sizeof(getBrmState())+2 ?>] - sums[<?= sizeof(getBrmState())+1 ?>]) / sums[<?= sizeof(getBrmState())+2 ?>]) * 100).toFixed(2) + "%");
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