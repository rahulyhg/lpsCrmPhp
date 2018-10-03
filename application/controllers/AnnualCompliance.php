<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jul 16, 2018 , 7:18:06 PM
 * @property Prospects $prospects
 */
class AnnualCompliance extends BRT_Controller {

    public $viewPath = "annualCompliance/";
    public $modalPath = "annualCompliance/modal/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    function index() {
        $this->navMeta = ["active" => "arc", "pageTitle" => __CLASS__, "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->view($this->viewPath . "index");
    }

    private $duplicate = 0, $missing = 0;

    function newProspects($__currentState) {
        if (isset($_FILES['file'])) {
            $upconfig['upload_path'] = 'temp/';
            $upconfig["encrypt_name"] = TRUE;
            $upconfig['allowed_types'] = '*';
            $this->upload->initialize($upconfig);

            if (!$this->upload->do_upload('file')) {
                $this->setAlertMsg($this->upload->display_errors(), DANGER);
            } else {
                $data = array('upload_data' => $this->upload->data());
                $full_path = $data['upload_data']['full_path'];
                $flag = $this->importFromExcelFictitious($full_path, $_POST["filteredColumns"], $_POST["dbColumns"], $__currentState);
                //dnp();
                unlink($full_path);
                $this->setAlertMsg($flag . " row inserted successfully! [" . $this->duplicate . " duplicate found!!]", SUCCESS);
            }
            $this->redirectToUrl(annualCompliance_url());
        } else {
            $this->data["__currentState"] = $__currentState;
            $this->modal($this->modalPath . "newProspects");
        }
    }

    private function importFromExcelFictitious($inputFileName, $filteredColumns, $dbColumns, $_currentState) {
        $completed = 0;
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn(1);
        //dnp($filteredColumns);
        $columns = [];
        foreach (range("A", $highestColumn) as $cell) {
            $val = $sheet->getCell($cell . '1')->getValue();
            //dnp($val);
            if (array_search(strtolower($val), array_map('strtolower', $filteredColumns)) !== FALSE) {
                $columns[$dbColumns[array_search(strtolower($val), array_map('strtolower', $filteredColumns))]] = $cell;
            }
        }
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($columns as $column => $cell) {
                if ($column === "preSortdate" || $column === "regDate") {
                    $rowData[$column] = changeDateFormat($sheet->getCell($cell . $row)->getValue());
                } else {
                    $rowData[$column] = $sheet->getCell($cell . $row)->getValue();
                }
            }
            $rowData["addedBy"] = $this->getUserID();
            $rowData["addedTime"] = date("Y-m-d H:i:s");
            $rowData["stateID"] = $_currentState;
            if (!$this->mdb->getSingleData(TAB_acProspects, ["contactID" => $rowData["contactID"]])) {
                $this->mdb->insertData(TAB_acProspects, $rowData);
                $completed++;
            } else {
                $this->duplicate++;
            }
        }
        return $completed;
    }

    function prospects($state) {
        $this->ifNotPermited("annualCompliance");
        $count = $this->mdb->countData(TAB_acProspects, ["stateID" => $state]);
        $this->navMeta = ["active" => "ac_prospects", "pageTitle" => getACState($state) . " Prospects [ " . $count . " ]", "bc" => array(
                ["url" => annualCompliance_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];
        $this->data["_currentState"] = $state;
        $this->view($this->viewPath . "prospects");
    }

    function editProspect($id) {
        $this->form_validation->set_rules("preSortDate", "Pre Sort Date is required", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_acProspects, ["id" => $id]);
            $columns = getACStateColumns($prospect->stateID);
            $saveData = [];
            foreach ($columns as $col => $column) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["preSortDate"] = changeDateFormat($saveData["preSortDate"]);
            $saveData["saleDate"] = changeDateFormat($saveData["saleDate"]);
            $this->mdb->updateData(TAB_acProspects, $saveData, ["id" => $id]);
            $this->setAlertMsg("Prospect Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["preSortDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["prospect"] = $this->mdb->getSingleData(TAB_acProspects, ["id" => $id]);
        $this->modal($this->modalPath . "editProspect");
    }

    function getProspectsData($state) {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_acProspects . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission("annualCompliance/edit")) {
            $extra .= "<button data-remote='" . annualCompliance_url('editProspect/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission('annualCompliance/delete')) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }

        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";

        $columns = ",";

        foreach (getACStateColumns($state) as $col => $va) {
            $columns .= $col . ",";
        }
        $columns = rtrim($columns, ",");
        $this->datatables
                ->select("id,stateID$columns")
                ->from(TAB_acProspects)
                ->where(["stateID" => $state])
                ->addColumn("actions", $action, "id,stateID,contactID");

        echo $this->datatables->generate();
    }

    function brm() {
        $this->navMeta = ["active" => "brm", "pageTitle" => "BRM ", "bc" => array(
                ["url" => annualCompliance_url(), "page" => __CLASS__],
                ["url" => annualCompliance_url("brm"), "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "brm");
    }

    function getBrm() {
        $states = array_keys(getACBrmState());
        $chCol = [];
        $reCol = [];
        foreach ($states as $state) {
            array_push($reCol, $state . "received");
            array_push($chCol, $state . "charged");
        }
        /* array_push($reCol, "fictitiousreceived");
          array_push($chCol, "fictitiouscharged"); */
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_ACBRM . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        //$extra .= "<a href='" . dashboard_url('showUpload/' . TAB_BRM . '/$1') . "' class='btn btn-link p-0 px-1' ><i class=\"fa fa-link\"></i></a>";
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_ACBRM . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission("brm/edit")) {
            $extra .= "<button data-remote='" . annualCompliance_url('editBrm/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        } if (hasPermission("brm/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        /* $weekStartDate = date('Y-m-d', strtotime("last Monday", time()));
          $weekEndDate = date('Y-m-d', strtotime("next Sunday", time())); */

        $da = $this->datatables
                ->select("id,date,attachment," . implode(",", $chCol) . "," . implode(",", $reCol) . ",( " . implode(" + ", $chCol) . " ) AS chargedTotal, ( " . implode(" + ", $reCol) . " ) AS receivedTotal,(@receivedTotal-@chargedTotal) AS loss,CONCAT('','') AS lossPercent")
                ->from(TAB_ACBRM)
                //->where("YEARWEEK(`date`, 1) = YEARWEEK(CURDATE(), 1)")
                ->addColumn("actions", $action, "id");

        echo $this->datatables->generate();
    }

    function newBrm() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["date"];
            $states = array_keys(getACBrmState());
            foreach ($states as $state) {
                array_push($columns, $state . "received");
                array_push($columns, $state . "charged");
            }
            /* array_push($columns, "fictitiousreceived");
              array_push($columns, "fictitiouscharged"); */
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = changeDateFormat($saveData["date"]);
            $saveData["addedBy"] = $this->getUserID();
            $config['allowed_types'] = '*';
            $this->load->initialize('upload', $config);
            $oldAttachment = [];
            if (!$this->upload->do_upload('attachment')) {
                $_SESSION["altMSg"] = $this->upload->display_errors();
                $_SESSION["altMsgType"] = "danger";
            } else {
                $filedata = $this->upload->data();
                $attachment["fileName"] = $filedata["file_name"];
                $attachment["orgFileName"] = $filedata["orig_name"];
                $attachment["uploadTime"] = date("Y-m-d H:i");
                $attachment["uploadBy"] = $this->getUserID();
                array_push($oldAttachment, (object) $attachment);
            }

            $saveData["attachment"] = json_encode($oldAttachment);
            $this->mdb->insertData(TAB_ACBRM, $saveData);
            $this->setAlertMsg("BRM Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newBrm");
    }

    function editBrm($id) {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["date"];
            $states = array_keys(getACBrmState());
            foreach ($states as $state) {
                array_push($columns, $state . "received");
                array_push($columns, $state . "charged");
            }
            /* array_push($columns, "fictitiousreceived");
              array_push($columns, "fictitiouscharged"); */
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = changeDateFormat($saveData["date"]);
            $this->mdb->updateData(TAB_ACBRM, $saveData, ["id" => $id]);
            $this->setAlertMsg("BRM Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["brm"] = $this->mdb->getSingleDataArray(TAB_ACBRM, ["id" => $id]);
        $this->modal($this->modalPath . "editBrm");
    }

    function getCustomerList() {
        $result = [];
        $state = $this->input->post("state");
        $term = isset($this->input->post("term")["term"]) ? $this->input->post("term")["term"] : "";
        $prospects = [];
        if ($term) {
            $prospects = $this->mdb->getDataLikeWhere(TAB_acProspects, ["contactID" => $term], ["stateID" => $state, "ordered" => "0"], 0, 20);
        }
        if ($prospects) {
            foreach ($prospects as $prospect) {
                array_push($result, ["id" => $prospect->id, "text" => $prospect->contactID, "amount" => $prospect->amount]);
            }
        }
        $result["extra"] = ["state" => $state, "term" => $term];

        echo json_encode($result);
    }

    function newPayment($state) {
        $this->data["_currentState"] = $state;
        if (isset($_GET["prospectID"])) {
            //dnp($_GET);
            $this->data["_currentProspect"] = $this->input->get("prospectID");
            $this->data["_currentProspectContactID"] = $this->mdb->getSingleData(TAB_acProspects, ["id" => $_GET["prospectID"]])->contactID;
        }
        $this->form_validation->set_rules("stateID", "State is required", "required");
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("prospectsID", "Prospect ID can not be blank", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_acProspects, ["id" => $_POST["prospectsID"]]);
            if ($prospect) {
                $cols = ["stateID", "price", "prospectsID", "email", "phone", "paymentType", "contactID", "checkNumber", "ein", "notes", "changed"];
                $saveData = [];
                foreach ($cols as $col) {
                    $saveData[$col] = $this->input->post($col);
                }
                $saveData["changed"] = $saveData["changed"] ? "Yes" : "No";
                $saveData["company"] = $prospect ? $prospect->company : "";
                $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
                $saveData["addedBy"] = $this->getUserID();
                $oldAttachment = [];
                if (!$this->upload->do_upload('paymentAttachment')) {
                    $_SESSION["altMSg"] = $this->upload->display_errors();
                    $_SESSION["altMsgType"] = "danger";
                } else {
                    $filedata = $this->upload->data();
                    $attachment["fileName"] = $filedata["file_name"];
                    $attachment["orgFileName"] = $filedata["orig_name"];
                    $attachment["uploadTime"] = date("Y-m-d H:i");
                    $attachment["uploadBy"] = $this->getUserID();
                    array_push($oldAttachment, (object) $attachment);
                }

                $saveData["paymentAttachment"] = json_encode($oldAttachment);

                if (!$this->crm->getAcCountOrders($saveData["contactID"], $state)) {
                    $this->mdb->insertData(TAB_ACorders, $saveData);
                    $this->mdb->updateData(TAB_acProspects, ["ordered" => '1'], ["id" => $_POST["prospectsID"]]);
                    $msg = "Order Added!";
                    if (valid_email($saveData["email"])) {
                        $template = $this->mdb->getSingleData(TAB_emailTemplates, ["purpose" => "annual"]);
                        if ($template) {
                            if ($template->active) {
                                $subject = "Order Confirmation";
                                $message = $template->template;
                                $msg .= sendMail($saveData["email"], $subject, $message, $template->senderEmail) ? "<br>Order Confirmation Email Sent!" : "";
                            }
                        }
                    }
                    $this->setAlertMsg($msg, SUCCESS);
                } else {
                    $this->setAlertMsg("Order Already Exist!", WARNING);
                }
                return $this->redirectToReference();
            }
        } else {
            if (isset($_POST["stateID"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newPayment");
    }

    function editOrder($id) {
        $order = $this->mdb->getSingleData(TAB_ACorders, ["id" => $id]);
        $this->data["_currentState"] = $order->stateID;
        $this->form_validation->set_rules("stateID", "State is required", "required");
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("contactID", "ContactID can not be blank", "required");
        if ($this->form_validation->run()) {
            $cols = ["stateID", "price", "email", "phone", "paymentType", "checkNumber", "ein", "notes", "changed"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["changed"] = $saveData["changed"] ? "Yes" : "No";
            $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
            $saveData["addedBy"] = $this->getUserID();
            $oldAttachment = json_decode($order->paymentAttachment);
            if (!$this->upload->do_upload('paymentAttachment')) {
                $_SESSION["altMSg"] = $this->upload->display_errors();
                $_SESSION["altMsgType"] = "danger";
            } else {
                $filedata = $this->upload->data();
                $attachment["fileName"] = $filedata["file_name"];
                $attachment["orgFileName"] = $filedata["orig_name"];
                $attachment["uploadTime"] = date("Y-m-d H:i");
                $attachment["uploadBy"] = $this->getUserID();
                array_push($oldAttachment, (object) $attachment);
            }

            $saveData["paymentAttachment"] = json_encode($oldAttachment);
            $this->mdb->updateData(TAB_ACorders, $saveData, ["id" => $id]);
            $this->setAlertMsg("Order Updated!", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["stateID"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["order"] = $order;
        $this->data["prospect"] = $this->mdb->getSingleData(TAB_acProspects, ["id" => $order->prospectsID]);

        $this->modal($this->modalPath . "editOrder");
    }

    function saveProspectExtra() {
        $columns = ['principleAddress', 'principleCity', 'principleState', 'principleZip',
            'mailingAddress', 'mailingCity', 'mailingState', 'mailingZip',
            'registeredName', 'registeredAddress', 'registeredCity', 'registeredState', 'registeredZip',
            'authorized1Title', 'authorized1Name', 'authorized1Address', 'authorized1City', 'authorized1State', 'authorized1Zip',
            'authorized2Title', 'authorized2Name', 'authorized2Address', 'authorized2City', 'authorized2State', 'authorized2Zip',
            'authorized3Title', 'authorized3Name', 'authorized3Address', 'authorized3City', 'authorized3State', 'authorized3Zip'
        ];
        $saveData = [];
        foreach ($columns as $column) {
            $saveData[$column] = $this->input->post($column);
        }
        $data = $this->mdb->updateData(TAB_acProspects, $saveData, [
            'contactID' => $this->input->post("contactID"),
            'stateID' => $this->input->post("stateID")
        ]);
        echo json_encode(["status" => $data]);
    }

    function editProspectExtra() {
        $columns = ['principleAddress', 'principleCity', 'principleState', 'principleZip',
            'mailingAddress', 'mailingCity', 'mailingState', 'mailingZip',
            'registeredName', 'registeredAddress', 'registeredCity', 'registeredState', 'registeredZip',
            'authorized1Title', 'authorized1Name', 'authorized1Address', 'authorized1City', 'authorized1State', 'authorized1Zip',
            'authorized2Title', 'authorized2Name', 'authorized2Address', 'authorized2City', 'authorized2State', 'authorized2Zip',
            'authorized3Title', 'authorized3Name', 'authorized3Address', 'authorized3City', 'authorized3State', 'authorized3Zip'
        ];
        $saveData = [];
        foreach ($columns as $column) {
            $saveData[$column] = $this->input->post($column);
        }
        $data = $this->mdb->updateData(TAB_acProspects, $saveData, [
            'contactID' => $this->input->post("contactID"),
            'stateID' => $this->input->post("stateID")
        ]);
        echo json_encode(["status" => $data]);
    }

    function orders($state) {
        $this->navMeta = ["active" => $state, "pageTitle" => getACState($state) . " Orders", "bc" => array(
                ["url" => annualCompliance_url(), "page" => __CLASS__],
                ["url" => annualCompliance_url("orders/" . $state), "page" => __FUNCTION__],
                ["url" => "", "page" => getACState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "orders");
    }

    function getOrdersData($state) {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_ACorders . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_orders . "/edit")) {
            $extra .= "<button data-remote='" . annualCompliance_url('editOrder/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_refunds)) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . annualCompliance_url('makeRefund/StopPayment/$1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . annualCompliance_url('makeRefund/ChargeBack/$1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . annualCompliance_url('makeRefund/Refund/$1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        $extra .= "<button data-remote='" . annualCompliance_url('showUpload/' . TAB_ACorders . '/$1/paymentAttachment') .
                "' class='btn btn-success btn-sm mx-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission(TAB_orders . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }

        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,paymentType,price,phone,email,contactID,prospectsID,orderDate,checkNumber,changed")
                ->from(TAB_ACorders)
                ->where(["stateID" => $state, "completed" => "0", "refund" => "0"])
                ->addColumn("actions", $action, "id,contactID");
        echo $this->datatables->generate();
    }

    function prospectDetails($state) {
        $prospectID = $this->input->get("prospectID");
        $prospect = $this->mdb->getSingleData(TAB_acProspects, ["id" => $prospectID, "stateID" => $state]);
        $this->data["prospect"] = $prospect;
        $this->data["_currentState"] = $state;
        $this->modal($this->modalPath . "prospectDetails");
    }

    function makeOrderFromShiped() {
        $customers = $this->input->get("customers");
        $customers = explode(",", $customers);
        foreach ($customers as $id) {
            $this->mdb->updateData(TAB_ACorders, ["completed" => 0], ["id" => $id]);
        }
        $this->setAlertMsg(sizeof($customers) . " Completed Order moved to order successfully!", SUCCESS);
        $this->redirectToReference();
    }

    function orderMarkCompleted() {
        $ids = explode(",", $this->input->post("ids"));
        $oldAttachment = [];
        if ($this->upload->do_upload('attachment')) {
            $filedata = $this->upload->data();
            $attachment["fileName"] = $filedata["file_name"];
            $attachment["orgFileName"] = $filedata["orig_name"];
            $attachment["uploadTime"] = date("Y-m-d H:i");
            $attachment["uploadBy"] = $this->getUserID();
            array_push($oldAttachment, (object) $attachment);
        }
        foreach ($ids as $id) {
            $saveData = [
                "date" => changeDateFormat($this->input->post("date")),
                "note" => $this->input->post("note"),
                "publishingCost" => $this->input->post("publishingCost"),
                "completed" => "1",
                "completedDate" => date("Y-m-d H:i")
            ];
            $saveData["attachment"] = json_encode($oldAttachment);
            // dnp($saveData);

            $this->mdb->updateData(TAB_ACorders, $saveData, ["id" => $id]);
        }
        return $this->redirectToReference(sizeof($ids) . " Orders mark as completed!", SUCCESS);
    }

    function makeRefund($type, $id, $cus = 0) {
        $this->form_validation->set_rules("refundDate", "Refund Date can not be blank", "required");
        if ($this->form_validation->run()) {
            if ($cus) {
                $dis = $this->mdb->getSingleData(TAB_ACorders, ["id" => $id]);
                $this->mdb->updateData(TAB_ACorders, ["refund" => "1"], ["id" => $id]);
            } else {
                $dis = $this->mdb->getSingleData(TAB_ACorders, ["id" => $id]);
                $this->mdb->updateData(TAB_ACorders, ["refund" => "1"], ["id" => $id]);
            }
            if ($dis) {
                $saveData["refundDate"] = changeDateFormat($this->input->post("refundDate"));
                $saveData["note"] = $this->input->post("note");
                $saveData["contactID"] = $dis->contactID;
                $saveData["orderDate"] = $dis->orderDate;
                $saveData["company"] = $dis->company;
                $saveData["price"] = $dis->price;
                $saveData["phone"] = $dis->phone;
                $saveData["email"] = $dis->email;
                $saveData["addedBy"] = $this->getUserID();
                $saveData["stateID"] = $dis->stateID;
                $saveData["paymentType"] = $dis->paymentType;
                $saveData["refundType"] = $type;
                if ($type === "Refund") {
                    $oldAttachment = [];
                    if ($this->upload->do_upload('attachment')) {
                        $filedata = $this->upload->data();
                        $attachment["fileName"] = $filedata["file_name"];
                        $attachment["orgFileName"] = $filedata["orig_name"];
                        $attachment["uploadTime"] = date("Y-m-d H:i");
                        $attachment["uploadBy"] = $this->getUserID();
                        array_push($oldAttachment, (object) $attachment);
                    }
                    $saveData["attachment"] = json_encode($oldAttachment);
                }
                $this->mdb->insertData(TAB_ACrefunds, $saveData);
                $this->setAlertMsg("Added to refunds successfully", SUCCESS);
                return $this->redirectToReference();
            }
        } else {
            if (isset($_POST["refundDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["_cus"] = $cus;
        $this->data["_type"] = $type;
        $this->data["_id"] = $id;
        $this->modal($this->modalPath . "makeRefund");
    }

    function refunds($state) {
        $this->navMeta = ["active" => $state, "pageTitle" => getACState($state) . " Refunds", "bc" => array(
                ["url" => annualCompliance_url(), "page" => __CLASS__],
                ["url" => annualCompliance_url("refunds/" . $state), "page" => __FUNCTION__],
                ["url" => "", "page" => getACState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "refunds");
    }

    function getRefundsData($state) {
        $extra = "";
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_ACrefunds . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,refundType,price,phone,email,contactID,orderDate,refundDate,note")
                ->from(TAB_ACrefunds)
                ->where(["stateID" => $state])
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function customers($state) {
        $this->navMeta = ["active" => $state, "pageTitle" => getACState($state) . " Customers", "bc" => array(
                ["url" => annualCompliance_url(), "page" => __CLASS__],
                ["url" => prospects_url("customrts/" . $state), "page" => __FUNCTION__],
                ["url" => "", "page" => getACState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "customers");
    }

    function getCustomersData($state) {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_ACorders . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_customers . "/edit")) {
            $extra .= "<button data-remote='" . annualCompliance_url('editCustomer/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        $extra .= "<button data-remote='" . annualCompliance_url('showUpload/' . TAB_ACorders . '/$1/attachment') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission(TAB_refunds)) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . annualCompliance_url('makeRefund/StopPayment/$1/1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . annualCompliance_url('makeRefund/ChargeBack/$1/1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . annualCompliance_url('makeRefund/Refund/$1/1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        $extra .= "<button data-remote='" . annualCompliance_url('showUpload/' . TAB_ACorders . '/$1/paymentAttachment') .
                "' class='btn btn-success btn-sm mx-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission(TAB_customers . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("acOrders.id,acOrders.id AS fisID,acOrders.paymentType,acOrders.price,"
                        . "acOrders.phone,acOrders.email,acOrders.contactID,"
                        . "acOrders.prospectsID,acOrders.orderDate,acOrders.stateID,"
                        . "acOrders.date,acOrders.changed,acOrders.notes,acOrders.note,"
                        . "acOrders.publishingCost,acProspects.company")
                ->from(TAB_ACorders)
                ->join(TAB_acProspects, TAB_acProspects . ".contactID=" . TAB_ACorders . ".contactID")
                ->where(["acOrders.stateID" => $state, "completed" => "1", "refund" => "0"])
                ->addColumn("actions", $action, "fisID");
        echo $this->datatables->generate();
    }

    function editCustomer($id) {
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("shippedDate", "Shipped Date is required", "required");
        if ($this->form_validation->run()) {
            $cols = ["price", "email", "phone", "paymentType"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["shippedDate"] = changeDateFormat($this->input->post("shippedDate"));
            $this->mdb->updateData(TAB_ACorders, $saveData, ["id" => $id]);
            $this->setAlertMsg("Customer Edited!", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["shippedDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["customer"] = $this->mdb->getSingleData(TAB_ACorders, ["id" => $id]);
        $this->modal($this->modalPath . "editCustomer");
    }

//////////////////////////////////////////////////////////////
    function showUpload($type, $id, $column = "attchment") {
        $this->data["fileData"] = $this->mdb->getSingleData($type, ["id" => $id])->$column;
        $this->modal($this->modalPath . "showUpload");
    }

    function downloadFile() {
        if ($_GET["file"]) {
            $file = basename($_GET['file']);
            $file = base_url("uploads/" . $file);
            header("Location:" . $file);
        } else {
            $this->setAlertMsg("No file found!", DANGER);
            $this->redirectToReference();
        }
    }

}
