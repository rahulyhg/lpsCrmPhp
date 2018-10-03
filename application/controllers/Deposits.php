<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 18, 2018 , 8:32:07 PM
 */
class Deposits extends BRT_Controller {

    public $viewPath = "deposits/";
    public $modalPath = "deposits/modal/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    function index() {
        /* $this->navMeta = ["active" => "deposits", "pageTitle" => 'Deposits', "bc" => array(
          ["url" => deposits_url(), "page" => __CLASS__]
          )];
          $this->view($this->viewPath . "index"); */
        $this->redirectToUrl(deposits_url("banks"));
    }

    function ccSettllements() {
        $this->navMeta = ["active" => "ccSettllements", "pageTitle" => 'CC Settllement Dashboard', "bc" => array(
                ["url" => deposits_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $where = "MONTH(`date`) = MONTH(CURRENT_DATE()) 
            AND YEAR(`date`) = YEAR(CURRENT_DATE())";
        if (isset($_GET["dateForm"]) && isset($_GET["dateTo"])) {
            $dateForm = $_GET["dateForm"];
            $dateTo = $_GET["dateTo"];
            $where = "`date`>='" . changeDateFormat($dateForm) . "' AND `date`<='" . changeDateFormat($dateTo) . "'";
        }
        $this->data["settllements"] = $this->mdb->getData(TAB_settllements, $where, ["date" => "DESC"]);
        $this->data["terminals"] = $this->mdb->getData(TAB_terminals);
        $this->view($this->viewPath . "ccSettllements");
    }

    function terminals() {
        $this->navMeta = ["active" => "deposit", "pageTitle" => 'Terminals', "bc" => array(
                ["url" => deposits_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $this->view($this->viewPath . "terminals");
    }

    function getTerminals() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_banks . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_deposits . "/edit")) {
            $extra .= "<button data-remote='" . deposits_url('editTerminal/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        /* if (hasPermission(TAB_deposits . "/delete")) {
          $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
          } */
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,company,terminalName,account,loginID,note")
                ->from(TAB_terminals)
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function newTerminal() {
        $this->form_validation->set_rules("loginID", "Login ID is required", "required");
        $this->form_validation->set_rules("company", "Company is required", "required");
        $this->form_validation->set_rules("terminalName", "Terminal Name is required", "required");
        $this->form_validation->set_rules("account", "Account can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["company", "terminalName", "loginID", "account", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["addedBy"] = $this->getUserID();
            $saveData["addedTime"] = date("Y-m-d H:i");
            $this->mdb->insertData(TAB_terminals, $saveData);
            $this->setAlertMsg("New Terminal Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["terminal"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newTerminal");
    }

    function editTerminal($id) {
        $this->form_validation->set_rules("loginID", "Login ID is required", "required");
        $this->form_validation->set_rules("company", "Company is required", "required");
        $this->form_validation->set_rules("terminalName", "Terminal Name is required", "required");
        $this->form_validation->set_rules("account", "Account can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["company", "terminalName", "loginID", "account", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }

            $this->mdb->updateData(TAB_terminals, $saveData, ["id" => $id]);
            $this->setAlertMsg("New Terminal Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["terminal"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["terminal"] = $this->mdb->getSingleData(TAB_terminals, ["id" => $id]);
        ;
        $this->modal($this->modalPath . "editTerminal");
    }

    function newSettllement() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $saveData = [];
            $saveData["date"] = changeDateFormat($this->input->post("date"));
            $saveData["addedBy"] = $this->getUserID();
            $saveData["addedTime"] = date("Y-m-d H:i");
            $posts = $_POST;
            unset($posts["date"]);
            $accounts = [];
            foreach ($posts as $post => $val) {
                $id = str_replace("terminal_", "", $post);
                $account = $this->mdb->getSingleDataArray(TAB_terminals, ["id" => $id]);
                if ($account) {
                    $account["value"] = $val;
                } else {
                    $account = [];
                }
                array_push($accounts, (object) $account);
            }
            $saveData["account"] = json_encode($accounts);
            $this->mdb->insertData(TAB_settllements, $saveData);
            $this->setAlertMsg("New Settllement Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["terminals"] = $this->mdb->getData(TAB_terminals);
        $this->modal($this->modalPath . "newSettllement");
    }

    function editSettllement($id) {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $saveData = [];
            $saveData["date"] = changeDateFormat($this->input->post("date"));
            $posts = $_POST;
            unset($posts["date"]);
            $accounts = [];
            foreach ($posts as $post => $val) {
                $id = str_replace("terminal_", "", $post);
                $account = $this->mdb->getSingleDataArray(TAB_terminals, ["id" => $id]);
                if ($account) {
                    $account["value"] = $val;
                } else {
                    $account = [];
                }
                array_push($accounts, (object) $account);
            }
            $saveData["account"] = json_encode($accounts);
            $this->mdb->updateData(TAB_settllements, $saveData, ["settllementID" => $id]);
            $this->setAlertMsg("Settllement updated", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["terminals"] = $this->mdb->getData(TAB_terminals);
        $this->data["settllement"] = $this->mdb->getSingleData(TAB_settllements, ["settllementID" => $id]);
        $this->modal($this->modalPath . "editSettllement");
    }

    /*     * ***************************************************************************************** */

    function banks() {
        $this->navMeta = ["active" => "banks", "pageTitle" => 'Bank Dashboard', "bc" => array(
                ["url" => deposits_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $where = "MONTH(`date`) = MONTH(CURRENT_DATE()) 
            AND YEAR(`date`) = YEAR(CURRENT_DATE())";
        if (isset($_GET["dateForm"]) && isset($_GET["dateTo"])) {
            $dateForm = $_GET["dateForm"];
            $dateTo = $_GET["dateTo"];
            $where = "`date`>='" . changeDateFormat($dateForm) . "' AND `date`<='" . changeDateFormat($dateTo) . "'";
        }
        $this->data["deposits"] = $this->mdb->getData(TAB_deposits, $where, ["date" => "DESC"]);
        $this->data["banks"] = $this->mdb->getData(TAB_banks);
        //$this->output->enable_profiler();
        $this->view($this->viewPath . "banks");
    }

    function banksList() {
        $this->navMeta = ["active" => "deposit", "pageTitle" => 'Banks List', "bc" => array(
                ["url" => deposits_url(), "page" => __CLASS__], ["url" => "", "page" => __FUNCTION__]
        )];
        $this->view($this->viewPath . "banksList");
    }

    function getBankList() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_banks . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_deposits . "/edit")) {
            $extra .= "<button data-remote='" . deposits_url('editBank/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        /* if (hasPermission(TAB_deposits . "/delete")) {
          $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
          } */
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,company,bankName,account,dba,state")
                ->from(TAB_banks)
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function newBank() {
        $this->form_validation->set_rules("dba", "DBA is required", "required");
        $this->form_validation->set_rules("company", "Company is required", "required");
        $this->form_validation->set_rules("bankName", "Bank Name is required", "required");
        $this->form_validation->set_rules("account", "Account can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["company", "bankName", "dba", "account", "state"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["addedBy"] = $this->getUserID();
            $saveData["addedTime"] = date("Y-m-d H:i");
            $this->mdb->insertData(TAB_banks, $saveData);
            $this->setAlertMsg("New Bank Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["bankName"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newBank");
    }

    function editBank($id) {
        $this->form_validation->set_rules("dba", "DBA is required", "required");
        $this->form_validation->set_rules("company", "Company is required", "required");
        $this->form_validation->set_rules("bankName", "Bank Name is required", "required");
        $this->form_validation->set_rules("account", "Account can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["company", "bankName", "dba", "account", "state"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $this->mdb->updateData(TAB_banks, $saveData, ["id" => $id]);
            $this->setAlertMsg("Bank Edited!", SUCCESS);
            return $this->redirectToReference();
        }
        $this->data["bank"] = $this->mdb->getSingleData(TAB_banks, ["id" => $id]);
        $this->modal($this->modalPath . "editBank");
    }

    function newDeposit() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $saveData = [];
            $saveData["date"] = changeDateFormat($this->input->post("date"));
            $saveData["addedBy"] = $this->getUserID();
            $saveData["addedTime"] = date("Y-m-d H:i");
            $accounts = [];
            foreach ($_POST["value"] as $id => $val) {
                //$id = str_replace("bank_", "", $post);
                $account = $this->mdb->getSingleDataArray(TAB_banks, ["id" => $id]);
                if ($account) {
                    $account["value"] = $val;
                    $account["qty"] = $_POST["qty"][$id];
                } else {
                    $account = [];
                }
                array_push($accounts, (object) $account);
            }
            $saveData["account"] = json_encode($accounts);

            $this->mdb->insertData(TAB_deposits, $saveData);
            $this->setAlertMsg("New Deposit Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["banks"] = $this->mdb->getData(TAB_banks);
        $this->modal($this->modalPath . "newDeposit");
    }

    function editDeposit($id) {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $saveData = [];
            $saveData["date"] = changeDateFormat($this->input->post("date"));
            $accounts = [];
            foreach ($_POST["value"] as $i => $val) {
                //$id = str_replace("bank_", "", $post);
                $account = $this->mdb->getSingleDataArray(TAB_banks, ["id" => $i]);
                if ($account) {
                    $account["value"] = $val;
                    $account["qty"] = $_POST["qty"][$i];
                } else {
                    $account = [];
                }
                array_push($accounts, (object) $account);
            }
            $saveData["account"] = json_encode($accounts);
           // dnp($saveData);
            $this->mdb->updateData(TAB_deposits, $saveData, ["depositID" => $id]);
            $this->setAlertMsg("Deposit updated", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["banks"] = $this->mdb->getData(TAB_banks);
        $this->data["deposit"] = $this->mdb->getSingleData(TAB_deposits, ["depositID" => $id]);
        $this->modal($this->modalPath . "editDeposit");
    }

}
