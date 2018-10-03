<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">            
            <h4 class="modal-title">Attachment</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body px-2">            

            <?php
            $fill = 1;
            $filesData = json_decode($fileData);
            if ($fileData) {
                foreach ($filesData as $fData) {
                    ?>
                    <div class="row my-1 <?= $fill ? "bg-light" : "bg-white" ?>">
                        <div class="col-12">
                            Original file name: <strong><?= $fData->orgFileName ?></strong>
                        </div>
                        <div class="col">
                            Upload By: <strong><?= $fData->uploadBy ?></strong>
                        </div>
                        <div class="col">
                            Upload Time: <strong><?= changeDateFormatToLong($fData->uploadTime, "d M, Y h:i a") ?></strong>
                        </div>
                        <div class="col-12">
                            <a href="<?= dashboard_url("downloadFile?file=" . $fData->fileName) ?>"><h3>download here</h3></a>
                        </div>
                    </div>
                    <?php
                    $fill = !$fill;
                }
            } else {
                ?>
                <div class="row my-1">
                    <div class="col-12 text-center justify-content-center">
                        <h1>No attachment found!</h1>
                    </div>
                </div>
                <?php
            }
            ?>


        </div>           
    </div>
</div>
<script type="text/javascript">

</script>