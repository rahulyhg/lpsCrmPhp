<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 11:32:33 AM
 */
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <a class="btn btn-outline-blue-grey" href="<?= settings_url("users") ?>">Users List</a>
        </div>
    </div>
</div>
<form class="form" novalidate action="<?= user_url("addUser") ?>" method="post" autocomplete>
    <div class="form-body">
        <h4 class="form-section"><i class="fa fa-user"></i>  Personal Info</h4>
        <div class="row">
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput1">Fist Name</label>
                    <input type="text" id="userinput1" class="form-control border-primary" required placeholder="First Name" name="firstName">
                    <p class="help-block m-0 danger"></p>
                </fieldset>

            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput2">Last Name</label>
                    <input type="text" id="userinput2" class="form-control border-primary" required placeholder="Last Name" name="lastName">

                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput3">Birth Date</label>
                    <input type="text" id="userinput3" required class="form-control border-primary birthDate" placeholder="BirthDate" name="birthDate">

                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput4">Position</label>
                    <select type="text" id="userinput4" class="form-control border-primary" name="position">
                        <?php
                        foreach ($usersType as $userType) {
                            ?>
                            <option value="<?= $userType->id ?>"><?= $userType->userType ?></option><?php
                        }
                        ?>
                    </select>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput9">Pin</label>
                    <input type="text" id="userinput9" required class="form-control border-primary" placeholder="Pin" name="pin">

                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput7">Designation</label>
                    <input type="text" id="userinput7" class="form-control border-primary" placeholder="Designation" name="designation">

                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput6">Hourly Rate</label>
                    <input type="number" id="userinput6" required class="form-control border-primary" placeholder="HourlyRate" name="hourlyRate">

                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput8">Deduct</label>
                    <input type="number" id="userinput8" required class="form-control border-primary" placeholder="Deduct" name="deduct">

                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
        </div>

        <h4 class="form-section"><i class="ft-mail"></i> Contact Info &amp; Notes</h4>


        <fieldset class="form-group">
            <label>Phone Number</label>
            <input class="form-control border-primary" id="userinput7" type="tel" placeholder="Phone Number" name="phoneNumber">
            <p class="help-block m-0 danger"></p>
        </fieldset>       
        <fieldset class="form-group">
            <label for="userinput5">Email</label>
            <input class="form-control border-primary" type="email" placeholder="email" id="userinput5" required name="email"
                   data-validation-ajax-ajax="<?= user_url("emailDuplicate") ?>" >
            <p class="help-block m-0 danger"></p>
        </fieldset>
        <h4 class="form-section"><i class="ft-lock"></i>Password</h4>

        <fieldset class="form-group">
            <label for="userinput5">Password</label>
            <input class="form-control border-primary" type="password" placeholder="password" id="userinput6" name="password" required autocomplete="off">
            <p class="help-block m-0 danger"></p>
        </fieldset>        
    </div>

    <div class="form-actions right">
        <button type="button" class="btn btn-warning mr-1">
            <i class="ft-x"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check-square-o"></i> Save
        </button>
    </div>
</form>