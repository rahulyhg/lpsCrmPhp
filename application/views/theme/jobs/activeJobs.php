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
        <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
            <?= $navMeta["pageTitle"] ?></button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
            <a class="dropdown-item" href="<?= jobs_url("finishedJobs") ?>">Finished Jobs</a>
        </div>        
        <?php if (hasPermission(TAB_jobs . "/add")) { ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" 
               data-remote="<?= jobs_url("newJob") ?>">New Job</a>
           <?php } ?>
    </div>
</div>
<style>
</style>
<div class="table-responsive">
    <table class="table table-striped table-bordered serverSide-table dtr-inline w-100 table-hover">
        <thead class="w-100">
            <tr>
                <th>Date</th>
                <th>Job</th>
                <th>Count</th>
                <th>Tray  start</th>
                <th>Employee</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>        
        </thead>
        <tbody class="w-100">        
        </tbody>        
    </table>
</div>

<script>
    var Table;
    window.onload = function () {
        getTableData();
    };
    function getTableData() {
        var rt = 1;
        Table = $('.serverSide-table').DataTable({
            order: [0, "DESC"],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return moment(data, "YYYY-MM-DD HH:mm:ss").format("DD MMM, YYYY hh:mm A");
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return row.employeeName;
                    },
                    "targets": 4
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
            ], //  Date,  Job,  count,  tray  start,  employee,  notes.  
            'aoColumns': [{mData: "date"}, {mData: "job"}, {mData: "count"},
                {mData: "trayStart"}, {mData: "employeeID"}, {mData: "note"}, {mData: "actions", bSortable: false}],
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
            'sAjaxSource': '<?= jobs_url("getActiveJobs") ?>',
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
    }
</script>