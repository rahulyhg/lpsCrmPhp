<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Expense Category</h4>
        </div>
        <div class="modal-body">
            <div class='panel panel-body text-center'>
                <button class="btn btn-primary square" modal-toggler="true" data-target="#remoteModal2" data-remote="<?= dashboard_url("newCategory") ?>">
                    <i class="fa fa-plus"></i> New Category
                </button>
                <table class="table table-bordered table-condensed table-hover" id="table">
                    <thead>            
                        <tr>
                            <th>Serial No</th>
                            <th>Category Name</th>                                                                       
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>                                
                        <?php
                        if ($categories) {
                            $n = 1;
                            foreach ($categories as $category) {
                                ?>
                                <tr>
                                    <td><?= $n ?></td>                                    
                                    <td><?= $category->name ?></td>
                                    <td>

                                    </td>
                                </tr>
                                <?php
                                $n++;
                            }
                        }
                        ?>                                    

                    </tbody>
                </table>

            </div>
        </div>           
    </div>
</div>
<script type="text/javascript">
    $("#table").dataTable();
</script>