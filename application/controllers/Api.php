<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 14, 2018 , 9:45:39 AM
 */
class Api extends BRT_Controller {

    public function __construct() {
        parent::__construct();
    }

    function sync() {
        $this->updateLaborCRM();
        echo '<br><br>';
        $this->updateProDocCRM();
        /*
          UPDATE labor_home.orders
          JOIN labor_crm.prospects
          ON labor_home.orders.bcontact_id = labor_crm.prospects.contactID
          SET labor_home.orders.stateID = labor_crm.prospects.stateID
          WHERE labor_home.orders.stateID=''
         */
    }

    function updateProDocCRM() {
        echo 'proDoc:<br>';
        $dbPds = $this->load->database("pds", true);
        $data = $dbPds->query("select COUNT(*) AS countNum from orders limit 1");
        $results = $data->result();
        $countNum = $results[0]->countNum;
        $oldData = $this->mdb->getSingleData("sync", ["tableName" => 'pds.orders']);
        $added = 0;
        if ($oldData) {
            if ($oldData->countNum != $countNum) {
                $homeOrders = $dbPds->query("select * from orders where stateID!=''")->result();
                if ($homeOrders) {

                    foreach ($homeOrders as $homeOrder) {
                        $ifExist = $this->mdb->countData(TAB_proDocOrders, ["stateID" => $homeOrder->stateID, "contactID" => $homeOrder->bcontact_id]);
                        if (!$ifExist) {
                            $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["contactID" => $homeOrder->bcontact_id, "stateID" => $homeOrder->stateID]);
                            $saveData = [
                                "company" => $prospect ? $prospect->name : "",
                                "siteAddress" => $prospect ? $prospect->siteAddress : "",
                                "prospectsID" => $prospect ? $prospect->id : "",
                                "contactID" => $homeOrder->bcontact_id,
                                "stateID" => $homeOrder->stateID,
                                "orderDate" => changeDateFormat($homeOrder->cdate),
                                "price" => $homeOrder->amount,
                                "email" => $homeOrder->bemail,
                                "phone" => $homeOrder->bphone_number,
                                "paymentType" => "Online",
                                "addedBy" => "1"
                            ];
                            echo "contactID: " . $homeOrder->bcontact_id . '  state: ' . $homeOrder->stateID . '<br>';
                            if ($this->mdb->insertData(TAB_proDocOrders, $saveData)) {
                                $added++;
                            }
                        }
                    }
                    echo $added . ' row updated!<br>';
                }
                $this->mdb->updateData("sync", ["countNum" => $countNum], ["tableName" => 'pds.orders']);
                echo 'sync table - update success!<br>';
            } else {
                echo 'sync table - no update Data found!<br>';
            }
        } else {
            $homeOrders = $dbPds->query("select * from orders where stateID!=''")->result();
            if ($homeOrders) {
                foreach ($homeOrders as $homeOrder) {
                    $ifExist = $this->mdb->countData(TAB_proDocOrders, ["stateID" => $homeOrder->stateID, "contactID" => $homeOrder->bcontact_id]);
                    if (!$ifExist) {
                        $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["contactID" => $homeOrder->bcontact_id, "stateID" => $homeOrder->stateID]);
                        $saveData = [
                            "company" => $prospect ? $prospect->name : "",
                            "siteAddress" => $prospect ? $prospect->siteAddress : "",
                            "prospectsID" => $prospect ? $prospect->id : "",
                            "contactID" => $homeOrder->bcontact_id,
                            "stateID" => $homeOrder->stateID,
                            "orderDate" => changeDateFormat($homeOrder->cdate),
                            "price" => $homeOrder->amount,
                            "email" => $homeOrder->bemail,
                            "phone" => $homeOrder->bphone_number,
                            "paymentType" => "Online",
                            "addedBy" => "1"
                        ];
                        echo "contactID: " . $homeOrder->bcontact_id . '  state: ' . $homeOrder->stateID . '<br>';
                        if ($this->mdb->insertData(TAB_proDocOrders, $saveData)) {
                            $added++;
                        }
                    }
                }
                echo $added . ' row updated!<br>';
            }
            $this->mdb->insertData("sync", ["tableName" => 'pds.orders', "countNum" => $countNum]);
            echo 'sync table - insert success!';
        }
    }

    function updateLaborCRM() {
        echo 'labor:<br>';
        $added = 0;
        $dbHome = $this->load->database("home", true);
        $data = $dbHome->query("select COUNT(*) AS countNum from orders limit 1");
        $results = $data->result();
        $countNum = $results[0]->countNum;
        $oldData = $this->mdb->getSingleData("sync", ["tableName" => 'home.orders']);
        if ($oldData) {
            if ($oldData->countNum != $countNum) {
                $homeOrders = $dbHome->query("select * from orders where stateID!=''")->result();
                if ($homeOrders) {

                    foreach ($homeOrders as $homeOrder) {
                        $ifExist = $this->mdb->countData(TAB_orders, ["stateID" => $homeOrder->stateID, "contactID" => $homeOrder->bcontact_id]);
                        if (!$ifExist) {
                            $prospect = $this->mdb->getSingleData(TAB_prospects, ["contactID" => $homeOrder->bcontact_id, "stateID" => $homeOrder->stateID]);
                            $saveData = [
                                "company" => $prospect ? $prospect->company : "",
                                "prospectsID" => $prospect ? $prospect->id : "",
                                "contactID" => $homeOrder->bcontact_id,
                                "stateID" => $homeOrder->stateID,
                                "orderDate" => changeDateFormat($homeOrder->cdate),
                                "price" => $homeOrder->amount,
                                "email" => $homeOrder->bemail,
                                "phone" => $homeOrder->bphone_number,
                                "paymentType" => "Online",
                                "addedBy" => "1"
                            ];
                            echo "contactID: " . $homeOrder->bcontact_id . '  state: ' . $homeOrder->stateID . '<br>';
                            if ($this->mdb->insertData(TAB_orders, $saveData)) {
                                $added++;
                            }
                        }
                    }
                    echo $added . ' row updated!<br>';
                }
                $this->mdb->updateData("sync", ["countNum" => $countNum], ["tableName" => 'home.orders']);
                echo 'sync table - update success!<br>';
            } else {
                echo 'sync table - no update Data found!<br>';
            }
        } else {
            $homeOrders = $dbHome->query("select * from orders where stateID!=''")->result();
            if ($homeOrders) {
                foreach ($homeOrders as $homeOrder) {
                    $ifExist = $this->mdb->countData(TAB_orders, ["stateID" => $homeOrder->stateID, "contactID" => $homeOrder->bcontact_id]);
                    if (!$ifExist) {
                        $prospect = $this->mdb->getSingleData(TAB_prospects, ["contactID" => $homeOrder->bcontact_id, "stateID" => $homeOrder->stateID]);
                        $saveData = [
                            "company" => $prospect ? $prospect->company : "",
                            "contactID" => $homeOrder->bcontact_id,
                            "stateID" => $homeOrder->stateID,
                            "orderDate" => changeDateFormat($homeOrder->cdate),
                            "price" => $homeOrder->amount,
                            "email" => $homeOrder->bemail,
                            "phone" => $homeOrder->bphone_number,
                            "paymentType" => "Online",
                            "addedBy" => "1"
                        ];
                        echo "contactID: " . $homeOrder->bcontact_id . '  state: ' . $homeOrder->stateID . '<br>';
                        if ($this->mdb->insertData(TAB_orders, $saveData)) {
                            $added++;
                        }
                    }
                }
                echo $added . ' row updated!<br>';
            }
            $this->mdb->insertData("sync", ["tableName" => 'home.orders', "countNum" => $countNum]);
            echo 'sync table - insert success!';
        }
    }

    function getRecord() {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        $contactID = $this->input->get("contactID");
        $stateID = $this->input->get("stateID");
        $prospect = $this->mdb->getSingleData(TAB_prospects, ['contactID' => $contactID, 'stateID' => $stateID]);
        $data = ["status" => $prospect ? true : false, "prospect" => $prospect];
        echo json_encode($data);
    }

    function getProDocRecord() {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        $contactID = $this->input->get("contactID");
        $stateID = $this->input->get("stateID");
        //dnp($contactID);
        $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ['contactID' => $contactID, 'stateID' => $stateID]);
        $data = ["status" => $prospect ? true : false, "prospect" => $prospect];
        echo json_encode($data);
    }

    function getContactID() {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        $term = $this->input->get("term");
        $prospects = $this->mdb->executeCustomArray("SELECT * FROM `prospects`"
                . " WHERE LENGTH(`stateID`)=2 AND `contactID` LIKE '$term%' LIMIT 2");
        $data = ["status" => $prospects ? true : false, "prospects" => $prospects];

        echo json_encode($data);
    }

}
