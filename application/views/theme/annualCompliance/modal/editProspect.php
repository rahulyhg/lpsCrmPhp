<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Mar 30, 2018, 7:30:04 PM
 */
?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="newEventMoalLebel">Edit Prospect</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= annualCompliance_url("editProspect/" . $prospect->id) ?>" class="container" 
                  enctype="multipart/form-data" >
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <strong>Pre Sort Date</strong>
                            <input type="text" name="preSortDate" class="form-control slelctedDate " required="" value="<?= changeDateFormat($prospect->preSortDate, "d M, Y") ?>">
                            <input type="hidden" name="id" value="<?= $prospect->id ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    
                    <?php
                    $columns = getACStateColumns($prospect->stateID);
                    foreach ($columns as $column => $showName) {
                        if ($column !== "preSortDate") {
                            ?>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <strong><?= $showName ?></strong>
                                    <input placeholder="<?= $showName ?>" class="form-control square"
                                           value="<?= $prospect->$column ?>"
                                           name="<?= $column ?>"/>     
                                    <p class="help-block m-0 px-1 danger"></p>
                                </fieldset>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <div class="col-12 text-center py-1">
                        <button type="submit" class="btn bg-blue-grey square" value="editProspect">Edit</button>
                    </div>
                </div>
            </form>            
        </div>
    </div>            
</div>

<script type="text/javascript">
    $("#newForm").on("change", function (e) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $(".slelctedDate").datetimepicker({
        format: 'DD MMM, YYYY'
    });
</script>