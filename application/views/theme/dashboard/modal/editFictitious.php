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
            <h5 class="modal-title" id="newEventMoalLebel">Edit Fictitious</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="newForm" novalidate action="<?= dashboard_url("editFictitious/" . $fictitious->id) ?>" class="container" 
                  enctype="multipart/form-data" >
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label>Pre Sort Date</label>
                            <input type="text" name="preSortDate" class="form-control slelctedDate " required="" value="<?= changeDateFormatToLong($fictitious->preSortdate) ?>">
                            <input type="hidden" name="id" value="<?= $fictitious->id ?>">
                            <p class="help-block m-0 danger"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <strong><?= "Reg Date" ?></strong>
                            <input placeholder="<?= "Reg Date" ?>" class="form-control square slelctedDate"
                                   value="<?= changeDateFormatToLong($fictitious->regDate) ?>"
                                   name="<?= "regDate" ?>"/>     
                            <p class="help-block m-0 px-1 danger"></p>
                        </fieldset>
                    </div>
                    <?php
                    $dbColumns = ["", "county", "owner",
                        "contactID", "company", "address", "preSortdate",
                        "city", "state", "zip"];
                    foreach ($dbColumns as $showName) {
                        if ($showName !== "preSortdate" || $showName !== "regDate") {
                            ?>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <strong><?= $showName ?></strong>
                                    <input placeholder="<?= $showName ?>" class="form-control square"
                                           value="<?= $fictitious->$showName ?>"
                                           name="<?= $showName ?>"/>     
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