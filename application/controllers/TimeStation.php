<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 8, 2018 , 12:10:08 PM
 */
class TimeStation extends BRT_Controller {

    public $viewPath = "timeStation/";
    public $modalPath = "timeStation/modal/";

    public function __construct() {
        parent::__construct();
        // $this->ifNotAdmin();
    }

    function index($employeeID = 0) {
        $employeeID = $employeeID ? $employeeID : $this->getUserID();
        if (currentUserIsAdmin()) {
            $this->adminTimeStation($employeeID);
        } else {
            $this->othersTimeStation($employeeID);
        }
    }

    function employeeList() {

        $this->modal($this->modalPath . "employeeList");
    }

    function adminTimeStation($employeeID = 0) {
        $this->ifNotAdmin();
        $this->navMeta = ["active" => "timeStation", "pageTitle" => __CLASS__, "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];
        $f = isset($_GET["tsFrom"]) ? changeDateFormat($_GET["tsFrom"]) : false;
        $t = isset($_GET["tsTo"]) ? changeDateFormat($_GET["tsTo"]) : false;
        $this->data["employees"] = $this->mdb->getData(TAB_USERS, "id!='0'");

        if ($f && $t) {
            $query = "SELECT 
                    employeeID                   
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) >= DATE(STR_TO_DATE('$f','%Y-%m-%d'))
                    AND DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) <= DATE(STR_TO_DATE('$t','%Y-%m-%d'))
                    GROUP BY employeeID
                    ORDER BY `timeIn`";
            $query1 = "SELECT 
                    *                   
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) >= DATE(STR_TO_DATE('$f','%Y-%m-%d'))
                    AND DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) <= DATE(STR_TO_DATE('$t','%Y-%m-%d'))
                    ";
        } elseif ($f) {
            $query = "SELECT 
                    employeeID                   
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) >= DATE(STR_TO_DATE('$f','%Y-%m-%d'))
                    GROUP BY employeeID
                    ORDER BY `timeIn`";
            $query1 = "SELECT 
                    *     
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) >= DATE(STR_TO_DATE('$f','%Y-%m-%d'))";
        } elseif ($t) {
            $query = "SELECT 
                    employeeID
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) <= DATE(STR_TO_DATE('$t','%Y-%m-%d'))
                    GROUP BY employeeID
                    ORDER BY `timeIn`";
            $query1 = "SELECT 
                    *
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) <= DATE(STR_TO_DATE('$t','%Y-%m-%d'))
                   ";
        } else {
            $query = "SELECT 
                    employeeID
                    FROM `timeStation` 
                    WHERE YEARWEEK(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i'), 1) = YEARWEEK(CURDATE(), 1) 
                    GROUP BY employeeID
                    ORDER BY `timeIn`";
            $query1 = "SELECT 
                    *
                    FROM `timeStation` 
                    WHERE YEARWEEK(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i'), 1) = YEARWEEK(CURDATE(), 1) 
                    ";
        }
        $this->data["employeeTS"] = $this->mdb->executeCustom($query1);
        $this->data["timeStationData"] = $this->mdb->executeCustom($query);
        $this->view($this->viewPath . 'adminTimeStation');
    }

    function othersTimeStation($employeeID = 0) {

        $this->navMeta = ["active" => "timeStation", "pageTitle" => __CLASS__, "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];
        $f = isset($_GET["tsFrom"]) ? changeDateFormat($_GET["tsFrom"]) : false;
        $t = isset($_GET["tsTo"]) ? changeDateFormat($_GET["tsTo"]) : false;
        $this->data["employees"] = $this->mdb->getData(TAB_USERS, "id!='0'");
        if (!$employeeID) {
            $employeeID = $this->data["employees"][0]->id;
        }
        $this->data["currentEmployee"] = $this->mdb->getSingleData(TAB_USERS, ["id" => $employeeID]);
        $this->data["employeesStatus"] = $this->mdb->getSingleData(TAB_TIMESTATION, ["employeeID" => $employeeID, "timeOut" => "0"]);
        $this->data["employeeID"] = $employeeID;
        //dnp($this->data);
        if ($f && $t) {
            $query = "SELECT 
                   *
                   FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) >= DATE(STR_TO_DATE('$f','%Y-%m-%d'))
                    AND DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) <= DATE(STR_TO_DATE('$t','%Y-%m-%d'))
                    AND `employeeID`='$employeeID'
                    ORDER BY `timeIn` DESC";
        } elseif ($f) {
            $query = "SELECT 
                    *
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) >= DATE(STR_TO_DATE('$f','%Y-%m-%d'))
                    AND `employeeID`='$employeeID'
                    ORDER BY `timeIn` DESC";
        } elseif ($t) {
            $query = "SELECT 
                   *
                    FROM `timeStation` 
                    WHERE DATE(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i')) <= DATE(STR_TO_DATE('$t','%Y-%m-%d'))
                    AND `employeeID`='$employeeID'
                    ORDER BY `timeIn` DESC";
        } else {
            $query = "SELECT 
                   *
                    FROM `timeStation` 
                    WHERE YEARWEEK(STR_TO_DATE(`timeIn`,'%Y-%m-%d %H:%i'), 1) = YEARWEEK(CURDATE(), 1) 
                    AND `employeeID`='$employeeID'
                    ORDER BY `timeIn` DESC";
        }
        $this->data["employeeTS"] = $this->mdb->executeCustom($query);
        $this->view($this->viewPath . 'othersTimeStation');
    }

    function outEM() {
        $employeeData = $this->mdb->getSingleData(TAB_USERS, ["id" => $this->input->post("employeeID")]);
        if ($_POST["pin"] === $employeeData->pin) {
            $cols = ["employeeID", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $_POST[$col];
            }
            $saveData["timeOut"] = date("Y-m-d H:i");
            $this->mdb->updateData(TAB_TIMESTATION, $saveData, ["id" => $_POST["tsID"]]);
            $this->setAlertMsg("Thank you for clocking out at " . $saveData["timeOut"], SUCCESS);
        } else {
            $this->setAlertMsg("Your Pin is incorrect!", DANGER);
        }
        return $this->redirectToReference();
    }

    function inEM() {
        $employeeData = $this->mdb->getSingleData(TAB_USERS, ["id" => $this->input->post("employeeID")]);
        if ($_POST["pin"] === $employeeData->pin) {
            $cols = ["employeeID", "note"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $_POST[$col];
            }
            $saveData["dayHourlyRate"] = $employeeData->hourlyRate;
            $saveData["dayDeduct"] = $employeeData->deduct;
            $saveData["addedBy"] = $this->getUserID();
            $saveData["addedTime"] = date("Y-m-d H:i");
            $saveData["timeIn"] = date("Y-m-d H:i");
            $this->mdb->insertData(TAB_TIMESTATION, $saveData);
            $this->setAlertMsg("thank you for clocking in at " . $saveData["timeIn"], SUCCESS);
        } else {
            $this->setAlertMsg("Your Pin is incorrect!", DANGER);
        }
        return $this->redirectToReference();
    }

    function deleteTS($id) {
        $this->ifNotAdmin();
        $this->mdb->removeData(TAB_TIMESTATION, ["id" => $id]);
        $this->setAlertMsg("Data removed!", DANGER);
        return redirect($_SERVER["HTTP_REFERER"]);
    }

    function addNewEmpToTS() {
        $cols = ["employeeID", "timeIn", "timeOut", "note"];
        $employeeData = $this->mdb->getSingleData(TAB_USERS, ["id" => $this->input->post("employeeID")]);
        $saveData = [];
        foreach ($cols as $col) {
            $saveData[$col] = $_POST[$col];
        }
        $saveData["timeIn"] = DateTime::createFromFormat("d M, Y h:i a", $saveData["timeIn"])->format("Y-m-d H:i");
        $saveData["timeOut"] = DateTime::createFromFormat("d M, Y h:i a", $saveData["timeOut"])->format("Y-m-d H:i");
        $saveData["dayHourlyRate"] = $employeeData->hourlyRate;
        $saveData["dayDeduct"] = $employeeData->deduct;
        $saveData["addedBy"] = $this->getUserID();
        $saveData["addedTime"] = time();
        $this->mdb->insertData(TAB_TIMESTATION, $saveData);
        //dnp($saveData);

        $this->setAlertMsg(" Data Added!", SUCCESS);
        return $this->redirectToReference();
    }

    function employeTimeDetails($id) {
        $this->data["employee"] = $this->mdb->getSingleData(TAB_USERS, ["id" => $id]);
        $this->data["employeeTS"] = $this->mdb->getData("timeStation", ["employeeID" => $id]);
        $this->modal($this->modalPath . "tsEmployeeDetails");
    }

    function editEmTS($id) {
        $this->data["employeeTS"] = $this->mdb->getSingleData("timeStation", ["id" => $id]);
        $this->modal($this->modalPath . 'editEmTS');
    }

    function editEmpToTS() {
        $cols = ["timeIn", "timeOut", "note"];
        $saveData = [];
        foreach ($cols as $col) {
            $saveData[$col] = $_POST[$col];
        }
        $saveData["timeIn"] = DateTime::createFromFormat("d M, Y h:i a", $saveData["timeIn"])->format("Y-m-d H:i");
        $saveData["timeOut"] = DateTime::createFromFormat("d M, Y h:i a", $saveData["timeOut"])->format("Y-m-d H:i");
        $this->mdb->updateData(TAB_TIMESTATION, $saveData, ["id" => $_POST["id"]]);
        $_SESSION["altMsg"] = "Data Edited!";
        $_SESSION["altMsgType"] = "success";
        return redirect($_SERVER["HTTP_REFERER"]);
    }

}
