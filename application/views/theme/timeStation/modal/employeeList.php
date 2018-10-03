<div class="modal-dialog modal-lg">
    <div class="modal-content square">
        <div class="modal-header">            
            <h4 class="modal-title pull-left">Employee List</h4>
            <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered dtr-inline" id="employeeList">
                    <thead>
                        <tr>
                            <th>First Name<br></th>
                            <th>Last Name<br></th>
                            <th>Designation<br></th>
                            <th>HourlyRate<br></th>
                            <th>Deduct<br></th>
                            <th>Email<br></th>
                            <th>Phone<br></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>  
        </div>           
    </div>
</div>
<script type="text/javascript">
    var Table;

    Table = $('#employeeList').DataTable({
        "columnDefs": [
            {"render": function (data, type, row) {
                    // console.log(data);
                    return row.userType;
                },
                "targets": 2
            }
        ],
        'aoColumns': [{mData: "firstName"}, {mData: "lastName"},
            {mData: "designation"}, {mData: "hourlyRate"}, {mData: "deduct"},
            {mData: "email"}, {mData: "phoneNumber"}],
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
                    // console.log(f);
                    fnCallback(d, e, f);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //   console.log(jqXHR);
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

        {column_number: 2, filter_default_label: "Type...", filter_type: "text", data: []},

        {column_number: 3, filter_default_label: "Type...", filter_type: "text", data: []},
        {column_number: 4, filter_default_label: "Type...", filter_type: "text", data: []},
        {column_number: 5, filter_default_label: "Type Email", filter_type: "text", data: []},
        {column_number: 6, filter_default_label: "Type Phone", filter_type: "text", data: []}
    ], "header");


</script>