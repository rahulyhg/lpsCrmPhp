<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 18, 2018 , 8:32:07 PM
 */
class Vendors extends BRT_Controller {

    public $viewPath = "vendors/";
    public $modalPath = "vendors/modal/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    function index() {
        $this->navMeta = ["active" => "vendors", "pageTitle" => 'Vendors List', "bc" => array(
                ["url" => vendors_url(), "page" => __CLASS__]
        )];
        $this->view($this->viewPath . "index");
    }

    function getVendorsList() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_vendors . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_vendors . "/edit")) {
            $extra .= "<button data-remote='" . vendors_url('editVendor/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_vendors . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,company,name,email,phone,note")
                ->from(TAB_vendors)
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function newVendor() {
        $this->form_validation->set_rules("name", "Name is required", "required");
        $this->form_validation->set_rules("company", "Company is required", "required");
        $this->form_validation->set_rules("phone", "Phone is required", "required");
        $this->form_validation->set_rules("email", "Email can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["name", "company", "email", "phone", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["addedBy"] = $this->getUserID();
            $this->mdb->insertData(TAB_vendors, $saveData);
            $this->setAlertMsg("New Vendor Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["name"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newVendor");
    }

    function editVendor($id) {
        $this->form_validation->set_rules("name", "Name is required", "required");
        $this->form_validation->set_rules("company", "Company is required", "required");
        $this->form_validation->set_rules("phone", "Phone is required", "required");
        $this->form_validation->set_rules("email", "Email can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["name", "company", "email", "phone", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $this->mdb->updateData(TAB_vendors, $saveData, ["id" => $id]);
            $this->setAlertMsg("Vendor Edited!", SUCCESS);
            return $this->redirectToReference();
        }
        $this->data["vendor"] = $this->mdb->getSingleData(TAB_vendors, ["id" => $id]);
        $this->modal($this->modalPath . "editVendor");
    }

}
