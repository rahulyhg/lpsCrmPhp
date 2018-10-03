<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 3, 2018, 7:06:45 AM
 */
?>
<div class="content-header row">
</div>
<div class="content-body">
    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-4 col-10 box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title text-center">
                            <a href="<?= base_url() ?>"><img src="<?= logoSrc() ?>" height="120" width="170" alt="branding logo">
                                <h4 class="h3"><strong><?= SYSTEM_NAME ?></strong></h4></a>
                        </div>
                        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                            <span>Set your new password here</span>
                        </h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?= site_url("ressetPassword") ?>" novalidate method="post">
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="email" class="form-control form-control-lg" id="user-email" placeholder="Your Email Address" 
                                           name="email" value="<?= $user->email ?>" required readonly>
                                    <div class="form-control-position">
                                        <i class="ft-mail"></i>
                                    </div>
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="hidden" value="<?= $user->password ?>" name="oldPassword">
                                    <input type="password" class="form-control form-control-lg" id="user-email" placeholder="Your new password" 
                                           name="password" required>
                                    <div class="form-control-position">
                                        <i class="ft-lock"></i>
                                    </div>
                                    <p class="help-block m-0 danger"></p>
                                </fieldset>
                                <button type="submit" class="btn btn-outline-primary btn-lg btn-block"><i class="ft-unlock"></i> Recover Password</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <p class="float-sm-left text-center"><a href="<?= login_url() ?>" class="card-link">Login</a></p>
<!--                                <p class="float-sm-right text-center">New here ? <a href="<?= signup_url() ?>" class="card-link">Create Account</a></p>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>