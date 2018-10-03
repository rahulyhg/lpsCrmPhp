<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 3:38:48 PM
 */
?>
</div>

</div>
</div>
<footer class="footer footer-static footer-light navbar-border">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block">Copyright &copy; <?= date("Y") ?> 
            <a class="text-bold-800 grey darken-2" href="<?= base_url() ?>" target="_SELF"><?= SYSTEM_NAME ?> </a>, All rights reserved. </span>
        <span class="float-md-right d-block d-md-inline-block d-none d-lg-block">Developed By <a href="#">S. Brinta</a></span>
    </p>
</footer>
<div class="loader" style='    background: url(<?= property_url("images/loading.GIF") ?>) no-repeat scroll 50% 50% rgba(200,220,255,.7);
     height: 100%;    left: 0;    position: fixed;    top: 0;    width: 100%;    z-index: 999999999;'></div> 
<div class="modal fade" id="remoteModal1"  role="dialog" aria-hidden="true"></div>
<div class="modal fade" id="remoteModal2" role="dialog" aria-hidden="true"
     style="z-index: 99999; background: rgba(1,1,1,.5);" ></div>
<link href = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css" rel = "stylesheet">
<!-- BEGIN VENDOR JS-->
<script src="<?= property_url() ?>app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="<?= property_url() ?>app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/extensions/unslider-min.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/timeline/horizontal-timeline.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/forms/validation/jqBootstrapValidation.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"  type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"  type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/pickadate/picker.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/pickadate/picker.date.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/pickadate/picker.time.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/pickadate/legacy.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/pickers/daterange/daterangepicker.js"type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/extensions/toastr.min.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN STACK JS-->
<script src="<?= property_url() ?>app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="<?= property_url() ?>app-assets/js/core/app.js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="<?= property_url("app-assets/vendors/js/tables/datatable/datatables.min.js") ?>"></script>
<script src="<?= property_url("app-assets/js/scripts/forms/form-login-register.js") ?>"></script>
<script src=" <?= property_url("app-assets/vendors/js/forms/select/select2.full.min.js") ?>"></script>
<script src="<?= property_url() ?>app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
<script src="<?= property_url("app-assets/vendors/js/extensions/dropzone.js") ?>"></script>
<!--<script src="<?= property_url() ?>app-assets/js/scripts/forms/checkbox-radio.min.js" type="text/javascript"></script>-->
<!-- END STACK JS-->
<script src="<?= property_url() ?>js/scripts.js"  type="text/javascript"></script>
<script src="<?= property_url() ?>js/custom.js"  type="text/javascript"></script>
<script src="<?= property_url() ?>js/jquery.fancybox.js"  type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL JS-->
<?php
if (isset($pageJs)) {
    foreach ($pageJs as $js) {
        ?>
        <script src = "<?= $js ?>" type = "text/javascript"></script>
        <?php
    }
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.1/jquery.twbsPagination.min.js"></script>
<!-- END PAGE LEVEL JS-->
<script>
    if (document.getElementById("content-nav-right")) {
        $("#nav-right-container").html($("#content-nav-right").html());
        $("#content-nav-right").remove();
    }

    setTimeout(
            function () {
                $(".brt-alert").fadeOut("slow", "swing");
            }, 10000);
    if ($("#accounting")) {
        $("#accounting").fancybox({
            //maxWidth	: 800,
            //maxHeight	: 600,
            fitToView: false,
            width: '95%',
            height: '95%',
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none'
        });
    }
</script>
</body>
</html>