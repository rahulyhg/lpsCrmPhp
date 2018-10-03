<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">            
            <h4 class="modal-title">New Attachment</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class='panel panel-body'>
                <div id="actions" class="row">

                    <div class="col-12 justify-content-center text-center">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="fa fa-plus"></i>
                            <span>Add files...</span>
                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="fa fa-upload"></i>
                            <span>Start upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="fa fa-ban"></i>
                            <span>Cancel upload</span>
                        </button>
                    </div>

                    <div class="col-12">
                        <!-- The global file processing state -->
                        <span class="fileupload-process">
                            <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                            </div>
                        </span>
                    </div>

                </div>


                <div class="table table-striped files" id="previews">

                    <div id="template" class="file-row">
                        <!-- This is used as the file preview template -->
                        <div>
                            <span class="preview"><img data-dz-thumbnail /></span>
                        </div>
                        <div>
                            <p class="name" data-dz-name></p>
                            <strong class="error text-danger" data-dz-errormessage></strong>
                        </div>
                        <div>
                            <p class="size" data-dz-size></p>
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                            </div>
                        </div>
                        <div>                            
                            <button data-dz-remove class="btn btn-danger delete">
                                <i class="fa fa-trash"></i>                                
                            </button>
                        </div>
                    </div>

                </div>






            </div>          
        </div>           
    </div>
</div>
<script type="text/javascript">
    // Get the template HTML and remove it from the doument
    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var myDropzone = new Dropzone("#previews", {// Make the whole body a dropzone
        paramName: "attachment",
        url: "<?= dashboard_url("uploadFiles/" . $_extra) ?>", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false,
        previewsContainer: "#previews",
        clickable: ".fileinput-button",
        init: function () {
            this.on("error", function (file, response) {
                console.log("error");
                console.log(response);
            }),
                    this.on("success", function (file, response) {
                        console.log(response);
                    })
        }
    });
    myDropzone.on("totaluploadprogress", function (progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
    });


    myDropzone.on("sending", function (file, xhr, formData) {
        document.querySelector("#total-progress").style.opacity = "1";
        //formData.append('userName', 'bob');
    });
    myDropzone.on("queuecomplete", function (progress) {
        document.querySelector("#total-progress").style.opacity = "0";
    });

    document.querySelector("#actions .start").onclick = function () {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };
    document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true);
    };





</script>