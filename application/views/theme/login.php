<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 3:38:30 PM
 */
?>

<div class="content-header row">
</div>
<div class="content-body">
    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-4 col-10 box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 m-0">
                    <div class="card-header border-0">
                        <div class="card-title text-center">
                            <div class="p-1">
                                <a href="<?= base_url() ?>"><img src="<?= logoSrc() ?>" height="120" width="170" alt="branding logo">
                                    <h4 class="h3"><strong><?= SYSTEM_NAME ?></strong></h4></a>
                            </div>                                    
                        </div>
                        <?php
                        if (isset($_SESSION["altMsg"])) {
                            ?>
                            <div class="alert brt-alert text-center h2 font-weight-bold alert-<?= isset($_SESSION["altMsgType"]) ? ($_SESSION["altMsgType"] ? $_SESSION["altMsgType"] : "info") : "info" ?>">
                                <?= $_SESSION["altMsg"] ?>
                            </div>
                            <?php
                            unset($_SESSION["altMsg"]);
                        }
                        ?>
                        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                            <span><strong> Log in</strong></span>
                        </h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <form class="form-horizontal form-simple" 
                                  action="<?= login_url() . (isset($_GET["redirect"]) ? "?redirect=" . $_GET["redirect"] : "") ?>"
                                  novalidate method="post">
                                <fieldset class="form-group position-relative has-icon-left mb-0">
                                    <input type="text" class="form-control form-control-lg" id="user-name" name="username"
                                           placeholder="Your Username" data-validation-required-message="Must fill out username" 
                                           required>
                                    <div class="form-control-position">
                                        <i class="ft-user"></i>
                                    </div>
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="password" class="form-control form-control-lg" id="user-password" name="password" 
                                           placeholder="Enter Password" data-validation-required-message="Must fill out username" 
                                           required>
                                    <input type="hidden" name="location" id="location">
                                    <div class="form-control-position">
                                        <i class="fa fa-key"></i>                                                
                                    </div>
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>                                        
                                <button type="submit" class="btn btn-dark btn-lg btn-block"><i class="ft-unlock"></i> Login</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="">
                            <p class="float-sm-left text-center m-0"><a href="<?= recover_url() ?>" class="card-link">Recover password</a></p>
<!--                                    <p class="float-sm-right text-center m-0">New to here? <a href="<?= signup_url() ?>" class="card-link">Sign Up</a></p>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

</script>