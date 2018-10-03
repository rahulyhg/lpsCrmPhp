<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 3:38:41 PM
 */
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Developer:Brinta.me,brrinta,brrinta@gmail.com">
        <meta name="keywords" content="brrinta,s brinta,brinta,brinta developer,brinta web developer">
        <meta name="author" content="brinta">
        <title><?= isset($navMeta["pageTitle"]) ? $navMeta["pageTitle"] . " - " . $title : $title ?></title>
        <link rel="apple-touch-icon" href="<?= property_url("images/tet-logo-120.png") ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>favicon.ico">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
              rel="stylesheet">
        <!-- BEGIN VENDOR CSS-->
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/vendors.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/extensions/unslider.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/extensions/sweetalert.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/extensions/toastr.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/weather-icons/climacons.min.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/fonts/meteocons/style.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/pickers/daterange/daterangepicker.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/pickers/pickadate/pickadate.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/charts/morris.css">         
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/plugins/animate/animate.css">         
        <!-- END VENDOR CSS-->
        <!-- BEGIN STACK CSS-->
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/app.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/core/menu/menu-types/vertical-menu.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/core/colors/palette-gradient.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/fonts/simple-line-icons/style.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/pages/timeline.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url("app-assets/vendors/css/tables/datatable/datatables.min.css") ?>"/>
        <link rel="stylesheet" type="text/css" href="<?= property_url("app-assets/css/pages/login-register.css") ?>"/>
        <link rel="stylesheet" type="text/css" href="<?= property_url("app-assets/vendors/css/forms/selects/select2.min.css") ?>"/>
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/forms/icheck/icheck.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/vendors/css/forms/icheck/custom.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
        <link rel="stylesheet" type="text/css" href="<?= property_url()."css/jquery.fancybox.css";?>"/>
        <link rel="stylesheet" type="text/css" href="<?= property_url()."app-assets/vendors/css/file-uploaders/dropzone.min.css";?>"/>
        <!-- END STACK CSS-->
        <!-- BEGIN Page Level CSS-->
        <?php
        if (isset($pageCss)) {
            foreach ($pageCss as $css) {
                ?>
                <link rel="stylesheet" type="text/css" href="<?= $css ?>">
                <?php
            }
        }
        ?>
        <!-- END Page Level CSS-->
        <!-- BEGIN Custom CSS-->
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>css/style.css">
        <!-- END Custom CSS-->
<!--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
    </head>
    <body class="vertical-layout pace-done<?=
          !$showNavBar ? "" : "vertical-menu 2-columns menu-collapsed fixed-navbar ";
          echo $showNavBar
          ?>" data-open="hover" data-menu="vertical-menu" data-col="2-columns">