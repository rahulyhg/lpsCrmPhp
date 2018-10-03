<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 18, 2018 , 8:32:07 PM
 */
class Jobs extends BRT_Controller {

    public $viewPath = "jobs/";
    public $modalPath = "jobs/modal/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    function index() {
        $this->activeJobs();
    }

    function activeJobs() {
        $this->navMeta = ["active" => "jobs", "pageTitle" => 'Active Jobs', "bc" => array(
                ["url" => jobs_url(), "page" => __CLASS__],
                ["url" => "", "page" => "Active Jobs"]
        )];

        $this->view($this->viewPath . "activeJobs");
    }

    function getActiveJobs() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_jobs . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_jobs . "/edit")) {
            $extra .= "<button data-remote='" . jobs_url('editJob/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_jobs . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }

        if (hasPermission(TAB_jobs . "/markAsComplete")) {
            $extra .= "<button data-remote='" . jobs_url('markAsComplted/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-check\"></i></button>";
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,date,job,count,trayStart,employeeID,note,addedTime,addedBy,completed,completionDate,"
                        . "(SELECT CONCAT(firstName,' ',lastName) FROM users WHERE users.id=jobs.employeeID)   AS employeeName")
                ->from(TAB_jobs)
                ->where(["completed" => "0"])
                ->addColumn("actions", $action, "id");

        echo $this->datatables->generate();
    }

    function finishedJobs() {
        $this->navMeta = ["active" => "jobs", "pageTitle" => 'Finished Jobs', "bc" => array(
                ["url" => jobs_url(), "page" => __CLASS__],
                ["url" => "", "page" => "Finished Jobs"]
        )];

        $this->view($this->viewPath . "finishedJobs");
    }

    function getfinishedJobs() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_jobs . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";

        if (hasPermission(TAB_jobs . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }

        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,date,job,count,trayStart,employeeID,note,addedTime,addedBy,completed,completionDate,"
                        . "(SELECT CONCAT(firstName,' ',lastName) FROM users WHERE users.id=jobs.employeeID)   AS employeeName")
                ->from(TAB_jobs)
                ->where(["completed" => "1"])
                ->addColumn("actions", $action, "id");

        echo $this->datatables->generate();
    }

    function newJob() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        $this->form_validation->set_rules("job", "Job is required", "required");
        if ($this->form_validation->run()) {
            $cols = ["date", "job", "count", "trayStart", "employeeID", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = convertDate($saveData["date"], "Y-m-d H:i:s", "d M, Y h:i A");
            $saveData["addedBy"] = $this->getUserID();
            $this->mdb->insertData(TAB_jobs, $saveData);
            $this->setAlertMsg("New Job Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["employees"] = $this->mdb->getData(TAB_USERS);
        $this->modal($this->modalPath . "newJob");
    }
    function markAsComplted($id) {
        $this->form_validation->set_rules("completionDate", "Date is required", "required");

        if ($this->form_validation->run()) {
            $saveData["completionDate"] = changeDateFormat($this->input->post("completionDate"));
            $saveData["completed"] = "1";
            $this->mdb->updateData(TAB_jobs, $saveData, ["id" => $id]);
            $this->setAlertMsg("New Job Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["completionDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["job"] = $this->mdb->getSingleData(TAB_jobs, ["id" => $id]);

        $this->modal($this->modalPath . "markAsComplted");
    }

    function editJob($id) {
        $this->form_validation->set_rules("date", "Date is required", "required");
        $this->form_validation->set_rules("job", "Job is required", "required");
        if ($this->form_validation->run()) {
            $cols = ["date", "job", "count", "trayStart", "employeeID", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = convertDate($saveData["date"], "Y-m-d H:i:s", "d M, Y h:i A");
            $saveData["addedBy"] = $this->getUserID();
            $this->mdb->updateData(TAB_jobs, $saveData, ["id" => $id]);
            $this->setAlertMsg("Edit Job Added", SUCCESS);
            //dnp($saveData);
             return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["job"] = $this->mdb->getSingleData(TAB_jobs, ["id" => $id]);
        $this->data["employees"] = $this->mdb->getData(TAB_USERS);
        $this->modal($this->modalPath . "editJob");
    }

}
