<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 2:54:35 PM
 */
$userTypeOption = [];
foreach ($usersType as $userType) {
    $userTypeOption[] = ["value" => $userType->id, "label" => $userType->userType];
}

$userTypeOption = json_encode($userTypeOption);
?>
<style>
    .gty{

    }
</style>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <a class="btn btn-outline-blue-grey" href="<?= user_url("addUser") ?>">Add User</a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered serverSide-table dtr-inline">
        <thead>
            <tr>
                <th>First Name<br></th>
                <th>Last Name<br></th>
                <th>Position<br></th>
                <th>Designation<br></th>
                <th>HourlyRate<br></th>
                <th>Deduct<br></th>
                <th>Email<br></th>
                <th>Phone<br></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    window.onload = function () {
        geTableData();
    };
    var Table;
    function geTableData() {
        Table = $('.serverSide-table').DataTable({
            "columnDefs": [
                {"render": function (data, type, row) {
                        // console.log(data);
                        return row.userType;
                    },
                    "targets": 2
                }
            ],
            'aoColumns': [{mData: "firstName"}, {mData: "lastName"}, {mData: "position"},
                {mData: "designation"}, {mData: "hourlyRate"}, {mData: "deduct"},
                {mData: "email"}, {mData: "phoneNumber"}, {mData: "actions"}],
            "aLengthMenu": [[25, 50, 100, 200,500,1000, - 1], [25, 50, 100, 200,500,1000, "all"]],
            "iDisplayLength": 25,
            'bProcessing': true,
            "language": {
                buttons: {
                    selectAll: "Select all",
                    selectNone: "Select none"
                },
                processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
            },
            'bServerSide': true,
            'sAjaxSource': '<?= settings_url("getUsers") ?>',
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
            },
            dom: '<"top"B<"pull-right"l>>rtip',
            //dom: 'Blfrtip',
            buttons: [
                {
                    text: 'Select all',
                    action: function () {
                        Table.rows().select();
                    }
                },
                {
                    text: 'Select none',
                    action: function () {
                        Table.rows().deselect();
                    }
                }
            ]
        });
        yadcf.init(Table, [

            {column_number: 0, filter_default_label: "Type First Name", filter_type: "text", data: []},

            {column_number: 1, filter_default_label: "Type Last Name", filter_type: "text", data: []},

            {column_number: 2, filter_default_label: "Position", filter_type: "select", data: <?= $userTypeOption ?>},

            {column_number: 3, filter_default_label: "Type...", filter_type: "text", data: []},

            {column_number: 4, filter_default_label: "Type...", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "Type...", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "Type Email", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "Type Phone", filter_type: "text", data: []}
        ], "header");

    }
</script>