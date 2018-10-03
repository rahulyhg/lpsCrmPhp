<?php

/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 11:24:04 AM
 */
class User extends BRT_Controller {

    public $viewPath = "user/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    public function index() {
        $this->profile();
    }

    function profile() {
        $this->meta["pageJs"] = array(
            property_url("app-assets/js/scripts/forms/form-login-register.js")
        );
        $this->meta["pageCss"] = array(
            property_url("app-assets/css/pages/login-register.css")
        );
        $this->navMeta = ["active" => "profile", "pageTitle" => "Edit Profile", "bc" => array(
                ["url" => user_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];

        $this->form_validation->set_rules("firstName", "FirstName Can not be blank", "required|trim");
        $this->form_validation->set_rules("email", "Email Can not be blank", "required|trim");
        $this->form_validation->set_rules("lastName", "LastName Can not be blank", "required|trim");

        if ($this->form_validation->run() == true) {
            $cols = ["firstName", "lastName", "email", "phoneNumber"];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["birthDate"] = changeDateFormat($this->input->post("birthDate"));
            $password = $this->input->post("password");
            if ($password) {
                $saveData["password"] = $this->encryption->encrypt($password);
            }
            $this->mdb->updateData(TAB_USERS, $saveData, ["id" => $this->getUserID()]);
            $this->setAlertMsg("Profile Chnaged!", SUCCESS);
        } else {
            $this->setAlertMsg(validation_errors(), DANGER);
        }

        $this->view($this->viewPath . "profile");
    }

    function addUser() {
        $this->ifNotAdmin();
        $this->meta["pageJs"] = array(
                // property_url("app-assets/js/scripts/forms/form-login-register.js")
        );
        $this->meta["pageCss"] = array(
                //property_url("app-assets/css/pages/login-register.css")
        );
        $this->navMeta = ["active" => "addUser", "pageTitle" => "Add User", "bc" => array(
                ["url" => settings_url("users"), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];

        $this->form_validation->set_rules("firstName", "FirstName Can not be blank", "required|trim");
        $this->form_validation->set_rules("email", "Email Can not be blank", "required|trim");
        $this->form_validation->set_rules("password", "Password Can not be blank", "required|trim");
        $this->form_validation->set_rules("lastName", "LastName Can not be blank", "required|trim");
        $this->form_validation->set_rules("hourlyRate", "Hourly Rate Can not be blank", "required|trim");
        $this->form_validation->set_rules("deduct", "Deduct Can not be blank", "required|trim");
        $this->form_validation->set_rules("pin", "Pin Can not be blank", "required|trim");

        if ($this->form_validation->run() == true) {
            $cols = ["firstName", "lastName", "email", "phoneNumber", "position", "designation", "hourlyRate", "deduct", "pin"];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["birthDate"] = changeDateFormat($this->input->post("birthDate"));
            $password = $this->input->post("password");
            if ($password) {
                $saveData["password"] = $this->encryption->encrypt($password);
            }
            $this->mdb->insertData(TAB_USERS, $saveData);
            $this->setAlertMsg("User Added!", SUCCESS);
        } else {
            $this->setAlertMsg(validation_errors(), DANGER);
        }
        $this->data["usersType"] = $this->mdb->getData(TAB_userPermissions);
        $this->view($this->viewPath . "addUser");
    }

    function editUser($id) {
        $this->ifNotAdmin();
        $this->meta["pageJs"] = array(
                // property_url("app-assets/js/scripts/forms/form-login-register.js")
        );
        $this->meta["pageCss"] = array(
                //property_url("app-assets/css/pages/login-register.css")
        );
        $this->navMeta = ["active" => "users", "pageTitle" => "Edit User", "bc" => array(
                ["url" => settings_url("users"), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];


        $this->form_validation->set_rules("email", "Email Can not be blank", "required|trim");
        $this->form_validation->set_rules("position", "position Can not be blank", "required|trim");
        $this->form_validation->set_rules("hourlyRate", "Hourly Rate Can not be blank", "required|trim");
        $this->form_validation->set_rules("deduct", "Deduct Can not be blank", "required|trim");
        $this->form_validation->set_rules("pin", "Pin Can not be blank", "required|trim");
        if ($this->form_validation->run() == true) {
            $cols = ["firstName", "lastName", "email", "phoneNumber", "position", "designation", "hourlyRate", "deduct", "pin"];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $this->mdb->updateData(TAB_USERS, $saveData, ["id" => $this->input->post("id")]);
            $this->setAlertMsg("User Edited!", SUCCESS);
            $this->redirectToUrl(settings_url("users"));
        } else {
            $this->setAlertMsg(validation_errors(), DANGER);
        }
        $this->data["user"] = $this->mdb->getSingleData(TAB_USERS, ["id" => $id]);
        if (!$this->data["user"]) {
            $this->someThingWrong();
        }
        $this->data["usersType"] = $this->mdb->getData(TAB_userPermissions);
        $this->view($this->viewPath . "editUser");
    }

    function emailDuplicate() {
        $flag = 1;
        $users = $this->mdb->countData(TAB_USERS, ["email" => $_REQUEST["value"]]);
        if ($users) {
            $flag = 0;
        }
        echo json_encode(
                [
                    "value" => $_REQUEST["value"],
                    "valid" => $flag,
                    "message" => "Duplicate Email!"
                ]
        );
    }
    function dump(){
        dnp($this->encryption);
    }
}
