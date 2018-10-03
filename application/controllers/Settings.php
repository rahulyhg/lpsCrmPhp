<?php

/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 2:10:17 PM
 * @property Datatables $datatables Description
 * @property Mdl $mdl Description
 */
class Settings extends BRT_Controller {

    public $viewPath = "settings/";
    public $modalPath = "settings/modal/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
        $this->ifNotAdmin();
    }

    function index() {
        $this->users();
    }

    function users() {
        $this->navMeta = ["active" => "users", "pageTitle" => "User List", "bc" => array(
                ["url" => settings_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->data["usersType"] = $this->mdb->getData(TAB_userPermissions);
        $this->view($this->viewPath . __FUNCTION__);
    }

    function getUsers() {
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . settings_url('delete/users/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        $extra = "<a href='" . user_url('edituser/$1') . "' class='btn btn-link p-0 px-1' ><i class=\"fa fa-edit\"></i></a>";
        $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        $action = "<div class=\"text-center\">"
                //  . "<button data-remote='" . dashboard_url('viewData/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-eye\"></i></button>"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,firstName,designation,hourlyRate,deduct,lastName,phoneNumber,email,position,(SELECT userType FROM userPermissions WHERE userPermissions.id=users.position)AS userType")
                ->from(TAB_USERS)
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function delete($table, $id) {
        if (!$table || !$id) {
            $this->someThingWrong();
        }
        $this->setAlertMsg("Something Wrong!", DANGER);
        switch ($table) {
            case "users":
                if ($id == $this->getUserID()) {
                    $this->someThingWrong();
                }
                if ($this->mdb->removeData(TAB_USERS, ["id" => $id])) {
                    $this->setAlertMsg("User Deleted Successfully!", INFO);
                }
                break;
        }
        $this->redirectToReference();
    }

    function deleteSelected($table) {
        $ids = $this->input->get("ids");
        $ids = explode(',', $ids);

        if (!$table || !$ids) {
            $this->someThingWrong();
        }
        $count = 0;
        $this->setAlertMsg("Something Wrong!", DANGER);
        foreach ($ids as $id) {
            if ($this->mdb->getSingleData($table, ["id" => $id])) {
                $count += $this->mdb->removeData($table, ["id" => $id]);
            }
        }
        $this->setAlertMsg($count . " Deleted Successfully!", INFO);
        $this->redirectToReference();
    }

    function usersPermission() {
        $this->navMeta = ["active" => "userPermission", "pageTitle" => "Users Permission", "bc" => array(
                ["url" => settings_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->data["userPermissions"] = $this->mdb->getData(TAB_userPermissions);
        $this->view($this->viewPath . __FUNCTION__);
    }

    function newUserType() {
        $this->modal($this->modalPath . "newUserType");
    }

    function newUserTypeSave() {
        $saveData = [];
        $saveData["userType"] = $this->input->post("userType");
        $permissions = [];
        foreach (getPermissionDetails() as $pc => $fileds) {
            $values = $this->input->post($pc);
            $permissionFields = removeFromArray($fileds, "all");
            if ($values) {
                if (in_array("all", $values)) {
                    $permissions[$pc] = [(object) ["all" => true]];
                } else {
                    $colVal = [];
                    foreach ($permissionFields as $permissionField) {
                        if (in_array($permissionField, $values)) {
                            $colVal[] = [$permissionField => true];
                        } else {
                            $colVal[] = [$permissionField => false];
                        }
                    }
                    $permissions[$pc] = $colVal;
                }
            } else {
                $permissions[$pc] = [(object) ["all" => false]];
            }
        }
        $saveData["permissions"] = json_encode($permissions);
        $saveData["whiteListedIP"] = strlen($this->input->post("whiteListedIP")) < 2 ? "*" : $this->input->post("whiteListedIP");
        //dnp($saveData);
        $this->mdb->insertData(TAB_userPermissions, $saveData);
        $this->redirectToReference("New User Type Added!", SUCCESS);
    }

    function editUserType($id) {
        $this->data["userPermission"] = $this->mdb->getSingleData(TAB_userPermissions, ["id" => $id]);
        $this->modal($this->modalPath . "editUserType");
    }

    function editUserTypeSave($id) {
        $saveData = [];
        $saveData["userType"] = $this->input->post("userType");
        $permissions = [];
        foreach (getPermissionDetails() as $col => $fields) {
            $permissionFields = removeFromArray($fields, "all");
            $values = $this->input->post($col);
            if ($values) {
                if (in_array("all", $values)) {
                    $permissions[$col] = [(object) ["all" => true]];
                } else {
                    $colVal = [];
                    if ($col !== "dashboard") {
                        foreach ($permissionFields as $permissionField) {
                            if (in_array($permissionField, $values)) {
                                $colVal[] = [$permissionField => true];
                            } else {
                                $colVal[] = [$permissionField => false];
                            }
                        }
                    } else {
                        foreach ($values as $value) {
                            $colVal[] = [$value => true];
                        }
                    }
                    $permissions[$col] = $colVal;
                }
            } else {
                $permissions[$col] = [(object) ["all" => false]];
            }
        }
        $saveData["permissions"] = json_encode($permissions);
        $saveData["whiteListedIP"] = strlen($this->input->post("whiteListedIP")) < 2 ? "*" : $this->input->post("whiteListedIP");
        // dnp($saveData);
        $this->mdb->updateData(TAB_userPermissions, $saveData, ["id" => $id]);
        $this->redirectToReference("User Type Edited!", SUCCESS);
    }

    /*     * ****************************************************************************************** */

    function userLog() {
        $this->navMeta = ["active" => "userLog", "pageTitle" => "Users Log", "bc" => array(
                ["url" => settings_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];

        $this->view($this->viewPath . __FUNCTION__);
    }

    function getUserLog() {
        $this->datatables
                ->select("(SELECT users.email FROM users WHERE users.id=userLog.user) AS user,userLog.activity,userLog.activityTime")
                ->from(TAB_userLog);

        echo $this->datatables->generate();
    }

    function emailTemplate() {
        $this->navMeta = ["active" => "emailTemplate", "pageTitle" => "Email Template", "bc" => array(
                ["url" => settings_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $this->data["templates"] = $this->mdb->getData(TAB_emailTemplates);
        $this->view($this->viewPath . __FUNCTION__);
    }

    function addTemplate() {
        $this->form_validation->set_rules("purpose", "Purpose is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["purpose", "active", "template", "senderEmail"];
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["addedBy"] = $this->getUserID();
            $saveData["template"] = str_replace("\n", "<br>", $saveData["template"]);
            $this->mdb->insertData(TAB_emailTemplates, $saveData);
            $this->setAlertMsg("New Email Template Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["purpose"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "addTemplate");
    }

    function editTemplate($id) {
        $this->form_validation->set_rules("purpose", "Purpose is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["purpose", "active", "template", "senderEmail"];
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }

            $saveData["template"] = str_replace("\n", "<br>", $saveData["template"]);
            $this->mdb->updateData(TAB_emailTemplates, $saveData, ["id" => $id]);
            $this->setAlertMsg("New Email Template Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["purpose"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["template"] = $this->mdb->getSingleData(TAB_emailTemplates, ["id" => $id]);
        $this->modal($this->modalPath . "editTemplate");
    }

    function send() {
        echo valid_email("") ? "t" : "f";
    }

}
