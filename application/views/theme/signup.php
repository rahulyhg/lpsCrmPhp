<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 3, 2018, 7:06:14 AM
 */
?>

<div class="content-header row">
</div>
<div class="content-body">
    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-6 col-lg-4 col-10 box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                    <div class="card-header border-0">
                        <div class="card-title text-center">
                            <a href="<?= base_url() ?>"><img src="<?= logoSrc() ?>" height="120" width="170" alt="branding logo">
                                <h4 class="h3"><strong><?= SYSTEM_NAME ?></strong></h4></a>
                        </div>
                        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2 my-0">                                    
                            <span><strong><?= ucfirst($userType) ?> SignUp</strong></span>
                        </h6>
                        <?php
                        if (isset($_SESSION["altMsg"])) {
                            ?>
                            <div class="alert brt-alert alert-<?= isset($_SESSION["altMsgType"]) ? ($_SESSION["altMsgType"] ? $_SESSION["altMsgType"] : "info") : "info" ?>">
                                <?= $_SESSION["altMsg"] ?>
                            </div>
                            <?php
                            unset($_SESSION["altMsg"]);
                        }
                        ?>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form-horizontal form-simple" action="<?= signup_url() . "/" . $userType ?>" method="post" novalidate>
                                <?php
                                if ($userType == "driver") {
                                    ?>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="firstName" placeholder="First Name" name="firstName" required>
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="lastName" placeholder="Last Name" name="lastName" required>
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg birthDate" id="birthDate" placeholder="Birth Date" name="birthDate" required>
                                        <div class="form-control-position">
                                            <i class="ft-calendar"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="ssn" placeholder="SSN (AAA-GG-SSSS)"
                                               pattern="\d{3}[\-]\d{2}[\-]\d{4}"  required name="ssn"
                                               data-validation-pattern-message="SSN format AAA-GG-SSSS">
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <?php
                                } else {
                                    ?>                                        
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="user-name" placeholder="Business Name" name="businessName" required>
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="mc" placeholder="MC #" name="mc">
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="usdot" placeholder="USDOT #" name="usDot">
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-1">
                                        <input type="text" class="form-control form-control-lg" id="taxID" placeholder="TAX ID #" name="taxID">
                                        <div class="form-control-position">
                                            <i class="ft-user"></i>
                                        </div>
                                        <p class="help-block m-0 danger"></p>
                                    </fieldset>
                                    <?php
                                }
                                ?>
                                <fieldset class="form-group position-relative has-icon-left mb-1">
                                    <input type="email" class="form-control form-control-lg" id="user-email" placeholder="Email Address" name="email"
                                           required>
                                    <div class="form-control-position">
                                        <i class="ft-mail"></i>
                                    </div>
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="password" class="form-control form-control-lg" id="user-password" placeholder="Enter Password" name="password"
                                           required>
                                    <div class="form-control-position">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="ft-unlock"></i> Register</button>
                            </form>
                        </div>
                        <p class="text-center">Already have an account ? <a href="<?= login_url($userType) ?>" class="card-link">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>