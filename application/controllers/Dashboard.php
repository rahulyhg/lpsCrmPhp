<?php

/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 5:00:56 PM
 * @property Prospects $prospects
 */
class Dashboard extends BRT_Controller {

    public $viewPath = "dashboard/";
    public $modalPath = "dashboard/modal/";
    public $missing = 0;

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    function test() {
        $dbHome = $this->load->database("home", true);
        $data = $dbHome->query("select COUNT(*) AS countNum from orders limit 1");
        $results = $data->result();
        $countNum = $results[0]->countNum;
    }

    function index() {

        $this->navMeta = ["active" => "dashboard", "pageTitle" => __CLASS__, "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $qry = "SELECT "
                . "(FLcharged+GAcharged+LAcharged+OHcharged+MAcharged+TXcharged+NCcharged+NJcharged+COcharged+PAcharged+INcharged) AS totalCharged "
                . " FROM `brm` WHERE YEARWEEK(`date`,1)= YEARWEEK(CURDATE(), 1)";
        $result = $this->mdb->executeCustom($qry);
        $totalCharged = 0;
        if ($result) {
            foreach ($result as $rs) {
                $totalCharged += $rs->totalCharged;
            }
        }
        $this->data["stats"]["totalCharged"] = $result ? $totalCharged : 0;

        $qry = "SELECT SUM(price) AS totalCreditCard "
                . "FROM `orders` WHERE YEARWEEK(`orderDate`,1)= YEARWEEK(CURDATE(), 1) AND paymentType='Credit'";
        $result = $this->mdb->executeCustom($qry);
        $this->data["stats"]["totalCreditCard"] = $result ? $result[0]->totalCreditCard : 0;

        $qry = "SELECT SUM(price) AS totalCheck FROM `orders` WHERE YEARWEEK(`orderDate`,1)= YEARWEEK(CURDATE(), 1) AND paymentType='Check'";
        $result = $this->mdb->executeCustom($qry);
        $this->data["stats"]["totalCheck"] = $result ? $result[0]->totalCheck : 0;

        $qry = "SELECT "
                . "(FLreceived+GAreceived+LAreceived+OHreceived+MAreceived+TXreceived+NCreceived+NJreceived+COreceived+PAreceived+INreceived) AS totalReceived"
                . " FROM `brm` WHERE YEARWEEK(`date`,1)= YEARWEEK(CURDATE(), 1)";
        $result = $this->mdb->executeCustom($qry);

        $totalReceived = 0;
        if ($result) {
            foreach ($result as $rs) {
                $totalReceived += $rs->totalReceived;
            }
        }
        $this->data["stats"]["totalReceived"] = $result ? $totalReceived : 0;

        $qry = "SELECT "
                . "( FLsent + GAsent + LAsent + OHsent + MAsent + TXsent + NCsent + NJsent + COsent + PAsent +INsent ) AS totalSent "
                . "FROM `mailing` WHERE YEARWEEK(`date`,1)= YEARWEEK(CURDATE(), 1)";
        $result = $this->mdb->executeCustom($qry);
        $totalSent = 0;
        if ($result) {
            foreach ($result as $rs) {
                $totalSent += $rs->totalSent;
            }
        }
        $this->data["stats"]["totalSent"] = $result ? $totalSent : 0;


        $this->data["stats"]["lossPercent"] = $this->data["stats"]["totalReceived"] ? number_format((($this->data["stats"]["totalReceived"] - $this->data["stats"]["totalCharged"]) / $this->data["stats"]["totalReceived"]) * 100, 2) . " %" : "Received is Zero";

        $qry = "SELECT SUM(amount) AS totalExpense FROM `expenses` WHERE YEARWEEK(`date`,1)= YEARWEEK(CURDATE(), 1)";
        $result = $this->mdb->executeCustom($qry);
        $this->data["stats"]["totalExpense"] = $result ? $result[0]->totalExpense : 0;





        $query = "SELECT stateID,COUNT(*) AS numData FROM prospects GROUP BY stateID";
        $tempProspectCount = $this->mdb->executeCustom($query);
        $prospectsCount = [];
        foreach ($tempProspectCount as $data) {
            $prospectsCount[$data->stateID] = $data->numData;
        }
        $this->data["prospectsCount"] = $prospectsCount;

        $query = "SELECT stateID,COUNT(*) AS numData FROM prospects WHERE MONTH(preSortDate) = MONTH(CURRENT_DATE()) AND YEAR(preSortDate) = YEAR(CURRENT_DATE()) GROUP BY stateID ";
        $tempProspectMonthCount = $this->mdb->executeCustom($query);
        $prospectsMonthCount = [];
        foreach ($tempProspectMonthCount as $data) {
            $prospectsMonthCount[$data->stateID] = $data->numData;
        }
        $this->data["prospectsMonthCount"] = $prospectsMonthCount;


        $query = "SELECT stateID,COUNT(*) AS numData FROM orders GROUP BY stateID";
        $tempOrderCount = $this->mdb->executeCustom($query);
        $orderCount = [];
        foreach ($tempOrderCount as $data) {
            $orderCount[$data->stateID] = $data->numData;
        }
        $this->data["orderCount"] = $orderCount;
        $query = "SELECT stateID,COUNT(*) AS numData FROM orders WHERE MONTH(orderDate) = MONTH(CURRENT_DATE()) AND YEAR(orderDate) = YEAR(CURRENT_DATE()) GROUP BY stateID ";
        $tempOrderMonthCount = $this->mdb->executeCustom($query);
        $orderMonthCount = [];
        foreach ($tempOrderMonthCount as $data) {
            $orderMonthCount[$data->stateID] = $data->numData;
        }
        $this->data["orderMonthCount"] = $orderMonthCount;



        $query = "SELECT stateID,COUNT(*) AS numData FROM customers GROUP BY stateID";
        $tempCustomerCount = $this->mdb->executeCustom($query);
        $customerCount = [];
        foreach ($tempCustomerCount as $data) {
            $customerCount[$data->stateID] = $data->numData;
        }
        $this->data["customerCount"] = $customerCount;
        $query = "SELECT stateID,COUNT(*) AS numData FROM customers WHERE MONTH(shippedDate) = MONTH(CURRENT_DATE()) AND YEAR(shippedDate) = YEAR(CURRENT_DATE()) GROUP BY stateID ";
        $tempCustomerMonthCount = $this->mdb->executeCustom($query);
        $customerMonthCount = [];
        foreach ($tempCustomerMonthCount as $data) {
            $customerMonthCount[$data->stateID] = $data->numData;
        }
        $this->data["customerMonthCount"] = $customerMonthCount;

        $this->view($this->viewPath . "index");
    }

    function states() {
        $this->navMeta = ["active" => "stateDash", "pageTitle" => 'State Dashboard', "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__], ["url" => '', "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->view($this->viewPath . "stateDashboard");
    }

    function rStates() {
        $this->navMeta = ["active" => "state", "pageTitle" => 'R State Dashboard', "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__], ["url" => '', "page" => 'R States']
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->view($this->viewPath . "rStateDashboard");
    }

    function prospects($state) {
        if (!isset($state)) {
            return $this->redirectToUrl(dashboard_url(), "State Not valid!", DANGER);
        }
        $this->ifNotValidState($state);
        $count = $this->mdb->countData(TAB_prospects, ["stateID" => $state]);
        $this->navMeta = ["active" => $state, "pageTitle" => getState($state) . " Prospects -[ " . $count . " ]", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => prospects_url("/" . $state), "page" => __FUNCTION__],
                ["url" => "", "page" => getState($state)]
        )];
        $this->data["_currentState"] = $state;



        if (isset($_GET["searchIn"]) && isset($_GET["searchText"]) && isset($_GET["length"])) {
            $searchText = $_GET["searchText"];
            $searchIn = $_GET["searchIn"];
            $limit = $_GET["length"];
            $currentPage = isset($_GET["currentPage"]) ? $_GET["currentPage"] : 1;
            $offset = $currentPage > 1 ? ($limit * ($currentPage - 1) + 1) : 0;
            $this->load->model("prospects");
            $prospects = $this->prospects->select()
                    ->from(TAB_prospects)
                    ->where(["stateID" => $state])
                    ->limit($limit, $offset);

            if ($searchIn === "preSortDate") {
                $prospects = $prospects->where("`preSortDate`>='" . changeDateFormat($_GET["searchText"]) . "'");
                if (isset($_GET["searchText1"])) {
                    $prospects = $prospects->where("`preSortDate`<='" . changeDateFormat($_GET["searchText1"]) . "'");
                }
            } else {
                $prospects = $prospects->like([$searchIn => $searchText]);
            }
            $resultSet = $prospects->generate();
            $this->data["results"] = [
                "resultSetCount" => $prospects->displayRecordsCount(),
                "query" => $prospects->query,
                "resultSet" => $resultSet
            ];
        }
        $this->view($this->viewPath . "prospects");
    }

    /* function getProspectsData($state) {

      $this->ifNotValidState($state);
      $extra = "";
      $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_prospects . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
      if (hasPermission(TAB_prospects . "/edit")) {
      $extra .= "<button data-remote='" . dashboard_url('editProspect/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
      }
      if (hasPermission(TAB_prospects . "/delete")) {
      $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
      }
      $action = "<div class=\"text-center\">"
      . $extra
      . "</div>";
      $this->datatables
      ->select("id," . implode(",", array_keys(getStateColumns($state))))
      ->from(TAB_prospects)
      ->where(["stateID" => $state])
      ->addColumn("actions", $action, "id");
      echo $this->datatables->generate();
      } */

    function editProspect($id) {
        $this->form_validation->set_rules("preSortDate", "Pre Sort Date is required", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_prospects, ["id" => $id]);
            $columns = getStateColumns($prospect->stateID);
            $saveData = [];
            foreach ($columns as $col => $column) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["preSortDate"] = changeDateFormat($saveData["preSortDate"]);
            $this->mdb->updateData(TAB_prospects, $saveData, ["id" => $id]);
            $this->setAlertMsg("Prospect Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["preSortDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["prospect"] = $this->mdb->getSingleData(TAB_prospects, ["id" => $id]);
        $this->modal($this->modalPath . "editProspect");
    }

    function newProspects($state) {
        $this->ifNotValidState($state);
        $this->data["_currentState"] = $state;

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
                $flag = $this->importFromExcel($full_path, $state, $_POST["filteredColumns"], $_POST["dbColumns"]);
                unlink($full_path);
                $this->setAlertMsg($data['upload_data']['orig_name'] . " [size:" . $data["upload_data"]["file_size"] . "KB] Uploaded!<br>"
                        . $flag . " row inserted successfully! [" . $this->duplicate . " duplicate found!!] "
                        . "<br>--{ " . $this->missing . " row missing contact ID}--", SUCCESS);
            }
            $this->redirectToUrl(dashboard_url("prospects/" . $state));
        } else {
            $this->modal($this->modalPath . "newProspects");
        }
    }

    private function importFromExcel($inputFileName, $state, $filteredColumns, $dbColumns) {
        /* $existings= $this->mdb->getData(TAB_prospects,["rstate"=>"t"]);
          dnd($existings); */
        $this->missing = 0;
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

        $columns = [];
        foreach (range("A", $highestColumn) as $cell) {
            $val = $sheet->getCell($cell . '1')->getValue();
            if ($val) {
                if (array_search(strtolower($val), array_map('strtolower', $filteredColumns)) !== FALSE) {
                    $columns[$dbColumns[array_search(strtolower($val), array_map('strtolower', $filteredColumns))]] = $cell;
                }
            }
        }
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($columns as $column => $cell) {
                if (strtolower($column) === "presortdate" || strtolower($column) === "regdate") {
                    // echo "--pre---";
                    $rowData[$column] = changeDateFormat($sheet->getCell($cell . $row)->getFormattedValue());
                } elseif (strtolower($column) === strtolower("contactID")) {
                    $rowData[$column] = preg_replace('/[^a-zA-Z0-9]/', '', $sheet->getCell($cell . $row)->getValue());
                } else {
                    $rowData[$column] = $sheet->getCell($cell . $row)->getValue();
                }
            }
            $rowData["stateID"] = $state;
            $rowData["addedBy"] = $this->getUserID();
            $rowData["addedTime"] = date("Y-m-d H:i:s");
            $rowData["rstate"] = strlen($state) > 2 ? "t" : "f";


            if (isset($rowData["contactID"])) {
                //  echo time() . '   --' . $this->crm->getCountProspects($rowData["contactID"], $rowData["rstate"]) . '--   ';
                if (!$this->crm->getCountProspects($rowData["contactID"], $state)) {
                    //  echo '   ----   ' . time() . br(2);
                    //  dnp($rowData);
                    $this->mdb->insertData(TAB_prospects, $rowData);
                    $completed++;
                } else {
                    $this->duplicate++;
                }
            } else {
                // echo '--missiing---';
                $this->missing++;
            }
        }
        return $completed;
    }

    function newPayment($state) {
        $this->ifNotValidState($state);
        $this->data["_currentState"] = $state;
        if (isset($_GET["prospectID"])) {
            //dnp($_GET);
            $this->data["_currentProspect"] = $this->input->get("prospectID");
            $this->data["_currentProspectContactID"] = $this->mdb->getSingleData(TAB_prospects, ["id" => $_GET["prospectID"]])->contactID;
        }
        $this->form_validation->set_rules("stateID", "State is required", "required");
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("prospectsID", "Prospect ID can not be blank", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_prospects, ["id" => $_POST["prospectsID"]]);
            if ($prospect) {
                $cols = ["stateID", "price", "prospectsID", "email", "phone", "paymentType", "contactID", "checkNumber"];
                $saveData = [];
                foreach ($cols as $col) {
                    $saveData[$col] = $this->input->post($col);
                }
                $saveData["company"] = $prospect ? $prospect->company : "";
                $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
                $saveData["addedBy"] = $this->getUserID();
                if (!$this->crm->getCountOrders($saveData["contactID"], $state)) {
                    $this->mdb->insertData(TAB_orders, $saveData);
                    $this->mdb->updateData(TAB_prospects, ["ordered" => '1'], ["id" => $_POST["prospectsID"]]);
                    $msg = "Order Added!";
                    if (valid_email($saveData["email"])) {
                        $template = $this->mdb->getSingleData(TAB_emailTemplates, ["purpose" => strlen($state) === 2 ? "state" : "rState"]);
                        if ($template) {
                            if ($template->active) {
                                $subject = "Order Confirmation";
                                $message = $template->template;
                                $msg .= sendMail($saveData["email"], $subject, $message,$template->senderEmail) ? "<br>Order Confirmation Email Sent!" : "";
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
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("orderDate", "Order Date is required", "required");
        if ($this->form_validation->run()) {
            $cols = ["price", "email", "phone", "paymentType", "checkNumber"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
            $this->mdb->updateData(TAB_orders, $saveData, ["id" => $id]);
            $this->setAlertMsg("Order Edited!", SUCCESS);
            //  dnp($saveData);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["orderDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["order"] = $this->mdb->getSingleData(TAB_orders, ["id" => $id]);
        $this->modal($this->modalPath . "editOrder");
    }

    function orders($state) {
        $this->ifNotValidState($state);
        $this->navMeta = ["active" => $state, "pageTitle" => getState($state) . " Orders", "bc" => array(
                ["url" => dashboard_url(), "page" => __FUNCTION__],
                ["url" => "", "page" => getState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "orders");
    }

    function getOrdersData($state) {
        $this->ifNotValidState($state);
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_orders . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_orders . "/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editOrder/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_refunds)) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeRefund/StopPayment/$1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeRefund/ChargeBack/$1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeRefund/Refund/$1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        if (hasPermission(TAB_orders . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }

        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,paymentType,price,phone,email,contactID,prospectsID,orderDate,checkNumber")
                ->from(TAB_orders)
                ->where(["stateID" => $state, "shipped" => "0", "refund" => "0"])
                ->addColumn("actions", $action, "id,contactID");
        echo $this->datatables->generate();
    }

    function prospectDetails($state) {
        $prospectID = $this->input->get("prospectID");
        $prospect = $this->mdb->getSingleData(TAB_prospects, ["id" => $prospectID, "stateID" => $state]);
        $this->data["prospect"] = $prospect;
        $this->data["_currentState"] = $state;
        $this->modal($this->modalPath . "prospectDetails");
    }

    function getCustomerList() {
        $result = [];
        $state = $this->input->post("state");
        $term = isset($this->input->post("term")["term"]) ? $this->input->post("term")["term"] : "";
        $prospects = [];
        if ($term) {
            $prospects = $this->mdb->getDataLikeWhere(TAB_prospects, ["contactID" => $term], ["stateID" => $state, "ordered" => "0"], 0, 20);
        }
        if ($prospects) {
            foreach ($prospects as $prospect) {
                array_push($result, ["id" => $prospect->id, "text" => $prospect->contactID]);
            }
        }
        $result["extra"] = ["state" => $state, "term" => $term];

        echo json_encode($result);
    }

    function makeShipped($state) {
        $this->ifNotValidState($state);
        $orders = $this->input->get("orders");
        $orders = explode(",", $orders);
        foreach ($orders as $id) {
            $order = $this->mdb->getSingleData(TAB_orders, ["id" => $id]);
            if ($order) {
                // dnp($order);
                $prospect = $this->mdb->getSingleData(TAB_prospects, ["contactID" => $order->contactID, "stateID" => $state]);
                $this->mdb->updateData(TAB_orders, ["shipped" => "1"], ["id" => $id]);
                $saveData = ["contactID" => $order->contactID, "paymentType" => $order->paymentType, "price" => $order->price,
                    "email" => $order->email, "phone" => $order->phone, "stateID" => $order->stateID, "addedBy" => $this->getUserID(),
                    "addedTime" => date("Y-m-d"), "shippedDate" => date("Y-m-d"), "orderDate" => $order->orderDate,
                    "company" => $prospect ? $prospect->company : ""];
                if (!$this->mdb->getSingleData(TAB_customers, $saveData)) {
                    $this->mdb->insertData(TAB_customers, $saveData);
                }
            }
        }
        $this->exportWhenShipped($orders, $state);
        $this->setAlertMsg(sizeof($orders) . " Order Marked as shipped successfully!", SUCCESS);
        $this->redirectToUrl(dashboard_url("orders/" . $state));
    }

    function makeOrderFromShiped($state) {
        $this->ifNotValidState($state);
        $customers = $this->input->get("customers");
        $customers = explode(",", $customers);
        foreach ($customers as $id) {
            $customer = $this->mdb->getSingleData(TAB_customers, ["id" => $id]);
            //dnp($customer);
            if ($customer) {
                $this->mdb->removeData(TAB_customers, ["id" => $id]);
                $this->mdb->updateData(TAB_orders, ["shipped" => "0"], ["contactID" => $customer->contactID, "stateID" => $state]);
            }
        }
        $this->setAlertMsg(sizeof($customers) . " Shipped Order moved to order successfully!", SUCCESS);
        $this->redirectToUrl(dashboard_url("customers/" . $state));
    }

    private function exportWhenShipped($orders, $state) {

        $allData = [];
        if (sizeof($orders)) {
            foreach ($orders as $id) {
                $query = "SELECT * FROM " . TAB_orders . " JOIN " . TAB_prospects . " "
                        . "ON orders.contactID=prospects.contactID AND orders.stateID=prospects.stateID "
                        . " WHERE orders.id='$id' AND orders.stateID='$state'";
                $data = $this->mdb->executeCustomArray($query);
                if (isset($data[0])) {
                    array_push($allData, $data[0]);
                }
            }
        }
        $fileName = isset($_POST["fileName"]) ? ($this->input->post("fileName") . ".xls") : $state . "-" . date("y-m-d") . "-Shipped.xls";

        // echo $fileName;
        $columnNames = ["contactID", "company", "address", "city", "state", "zip", "email", "phone"];
        $realColumnNames = ["Contact ID", "Company", "Address", "City", "State", "Zip", "Email", "Phone"];
        $sheetColumnName = [
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
            "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ"
        ];
        $col = 0;
        foreach ($realColumnNames as $columnName) {
            $this->excel->getActiveSheet()->SetCellValue($sheetColumnName[$col++] . '1', $columnName);
        }
        $this->excel->setActiveSheetIndex(0);
        $row = 2;
        foreach ($allData as $data) {
            $col = 0;
            foreach ($columnNames as $columnName) {
                //  printf($data[$columnName]."   --  ");
                $this->excel->getActiveSheet()->SetCellValue($sheetColumnName[$col++] . $row, $data[$columnName]);
            }
            $row++;
        }
        $this->excel->stream($fileName);
        // dnp($allData);
    }

    function customers($state) {
        $this->ifNotValidState($state);
        $this->navMeta = ["active" => $state, "pageTitle" => getState($state) . " Customers", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => prospects_url(), "page" => __FUNCTION__],
                ["url" => "", "page" => getState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "customers");
    }

    function getCustomersData($state) {
        $this->ifNotValidState($state);
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_customers . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_customers . "/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editCustomer/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_refunds)) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeRefund/StopPayment/$1/1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeRefund/ChargeBack/$1/1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeRefund/Refund/$1/1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        if (hasPermission(TAB_customers . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id AS cusID,id,paymentType,price,"
                        . "phone,email,contactID,"
                        . "shippedDate,company,orderDate")
                ->from(TAB_customers)
                ->where(["customers.stateID" => $state, "refund" => "0"])
                ->addColumn("actions", $action, "cusID");
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
            $this->mdb->updateData(TAB_customers, $saveData, ["id" => $id]);
            $this->setAlertMsg("Customer Edited!", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["shippedDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["customer"] = $this->mdb->getSingleData(TAB_customers, ["id" => $id]);
        $this->modal($this->modalPath . "editCustomer");
    }

    function importZipModal() {
        $this->modal($this->modalPath . "importZip");
    }

    function importZip() {
        $state = $this->input->post("state");
        $config['allowed_types'] = 'zip';
        $this->load->initialize('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $_SESSION["altMSg"] = $this->upload->display_errors();
            $_SESSION["altMsgType"] = "danger";
        } else {
            $data = array('upload_data' => $this->upload->data());
            $full_path = $data['upload_data']['full_path'];
            $excel_dir = $data["upload_data"]["file_path"] . $data["upload_data"]["raw_name"];
            $zip = new ZipArchive;
            dnp($data);
            if ($zip->open($full_path) === TRUE) {
                $zip->extractTo($excel_dir);
                $zip->close();
                unlink($full_path);
                $allExcels = scandir($excel_dir);
                foreach ($allExcels as $excel) {
                    $currentExcelFile = $excel_dir . "/" . $excel;
                    $ext = pathinfo($currentExcelFile, PATHINFO_EXTENSION);
                    if ($ext == "xls" || $ext == "csv" || $ext == "xlsx") {
                        $this->countInsert += $this->importFromExcelZip($currentExcelFile, $state);
                    }
                }
                delete_files($excel_dir);
                unlink($excel_dir);
            }
            $_SESSION["altMsg"] = $this->countInsert . " Data Import Successfully![" . $this->duplicate . " duplicate data found!]";
            $_SESSION["altMsgType"] = "success";
            $this->redirectToUrl(dashboard_url("prospects/" . $state));
        }
        $this->redirectToReference();
    }

    private function importFromExcelZip($inputFileName, $state) {
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
        $columns = getstateColumnNumbers($state);
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($columns as $column => $cell) {

                if ($column === "preSortDate") {
                    $rowData[$column] = changeDateFormat($sheet->getCell($cell . $row)->getFormattedValue());
                } elseif (strtolower($column) === strtolower("contactID")) {
                    $rowData[$column] = preg_replace('/[^a-zA-Z0-9]/', '', $sheet->getCell($cell . $row)->getValue());
                } else {
                    $rowData[$column] = $sheet->getCell($cell . $row)->getValue();
                }
            }
            $rowData["stateID"] = $state;
            $rowData["addedBy"] = $this->getUserID();
            $rowData["addedTime"] = date("Y-m-d");
            if (!$this->mdb->getSingleData(TAB_prospects, ["contactID" => $rowData["contactID"], "stateID" => $state])) {
                $this->mdb->insertData(TAB_prospects, $rowData);
                $completed++;
            } else {
                $this->duplicate++;
            }
        }
        return $completed;
    }

    private $countInsert = 0;
    private $duplicate = 0;

    function brm() {
        $this->navMeta = ["active" => "brm", "pageTitle" => "BRM ", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => dashboard_url("brm"), "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "brm");
    }

    function getBrm() {
        $states = array_keys(getBrmState());
        $chCol = [];
        $reCol = [];
        foreach ($states as $state) {
            array_push($reCol, $state . "received");
            array_push($chCol, $state . "charged");
        }
        /* array_push($reCol, "fictitiousreceived");
          array_push($chCol, "fictitiouscharged"); */
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_BRM . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        //$extra .= "<a href='" . dashboard_url('showUpload/' . TAB_BRM . '/$1') . "' class='btn btn-link p-0 px-1' ><i class=\"fa fa-link\"></i></a>";
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_BRM . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission("brm/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editBrm/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
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
                ->from(TAB_BRM)
                //->where("YEARWEEK(`date`, 1) = YEARWEEK(CURDATE(), 1)")
                ->addColumn("actions", $action, "id");

        echo $this->datatables->generate();
    }

    function newBrm() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["date"];
            $states = array_keys(getBrmState());
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
            $this->mdb->insertData(TAB_BRM, $saveData);
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
            $states = array_keys(getBrmState());
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
            $this->mdb->updateData(TAB_BRM, $saveData, ["id" => $id]);
            $this->setAlertMsg("BRM Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["brm"] = $this->mdb->getSingleDataArray(TAB_BRM, ["id" => $id]);
        $this->modal($this->modalPath . "editBrm");
    }

    function mailing() {
        $this->navMeta = ["active" => "mailing", "pageTitle" => "Mailing ", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "mailing");
    }

    function getMailing() {
        $states = array_keys(getBrmState());
        $seCol = [];
        foreach ($states as $state) {
            array_push($seCol, $state . "sent");
        }
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_MAILING . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission("mailing/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editMailing/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission("mailing/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,date," . implode(",", $seCol) . ",( " . implode(" + ", $seCol) . " ) AS sentTotal")
                ->from(TAB_MAILING)
                //  ->where("YEARWEEK(`date`, 1) = YEARWEEK(CURDATE(), 1)")
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function newMailing() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["date"];
            $states = array_keys(getBrmState());
            foreach ($states as $state) {
                array_push($columns, $state . "sent");
            }
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = changeDateFormat($saveData["date"]);
            $saveData["addedBy"] = $this->getUserID();
            $this->mdb->insertData(TAB_MAILING, $saveData);
            $this->setAlertMsg("Mail Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newMailing");
    }

    function editMailing($id) {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["date"];
            $states = array_keys(getBrmState());
            foreach ($states as $state) {
                array_push($columns, $state . "sent");
            }
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = changeDateFormat($saveData["date"]);
            $this->mdb->updateData(TAB_MAILING, $saveData, ["id" => $id]);
            $this->setAlertMsg("Mail Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["mailing"] = $this->mdb->getSingleDataArray(TAB_MAILING, ["id" => $id]);
        $this->modal($this->modalPath . "editMailing");
    }

    function getEmployeeList() {
        $result = [];
        $term = $this->input->post("term")["term"];
        if ($term) {
            $query = "SELECT * FROM users WHERE firstName LIKE '%$term%' OR lastName LIKE '%$term%'";
            $users = $this->mdb->executeCustom($query);
        }
        if ($users) {
            foreach ($users as $user) {
                array_push($result, ["id" => $user->id, "text" => $user->firstName . " " . $user->lastName]);
            }
        }
        echo json_encode($result);
    }

    function showUpload($type, $id) {
        $this->data["fileData"] = $this->mdb->getSingleData($type, ["id" => $id])->attachment;
        $this->modal($this->modalPath . "showUpload");
        /* if ($_GET["file"]) {
          $file = basename($_GET['file']);
          $file = base_url("uploads/" . $file);
          header("Location:" . $file);
          } else {
          $this->setAlertMsg("No file found!", DANGER);
          $this->redirectToReference();
          } */
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

////////////////////////////////////////////////////////////////////////////////////
    function expense() {
        $this->navMeta = ["active" => "expense", "pageTitle" => "Expenses ", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->data["categories"] = $this->mdb->getData(TAB_exCategory);
        $this->data["users"] = $this->mdb->getData(TAB_USERS);
        $this->view($this->viewPath . "expense");
    }

    function uploadFiles($type, $id) {
        $attachment = [];
        if ($this->upload->do_upload('attachment')) {
            $filedata = $this->upload->data();
            $attachment["fileName"] = $filedata["file_name"];
            $attachment["orgFileName"] = $filedata["orig_name"];
            $attachment["uploadTime"] = date("Y-m-d H:i");
            $attachment["uploadBy"] = $this->getUserID();
            $po = $this->mdb->getSingleData($type, ["id" => $id])->attachment;
            $oldAttachment = json_decode($po ? $po : "[]");
            array_push($oldAttachment, (object) $attachment);
            $saveData["attachment"] = json_encode($oldAttachment);
            $this->mdb->updateData($type, $saveData, ["id" => $id]);
        }
    }

    function addMoreAttachment($type, $id) {
        $this->data["_extra"] = $type . "/" . $id;
        $this->modal($this->modalPath . "addMoreAttachment");
    }

    function getExpense() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_expenses . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_expenses . '/add')) {
            $extra .= "<button data-remote='" . dashboard_url('addMoreAttachment/' . TAB_expenses . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal2'><i class=\"fa fa-plus-square\"></i></button>";
        }
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_expenses . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission(TAB_expenses . '/delete')) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                //. "<button data-remote='" . dashboard_url('viewTrade/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-eye\"></i></button>"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,date,reference,ecID,amount,attachment,notes,paidBy,createdBy,employee,"
                        . "(SELECT name FROM exCategory WHERE exCategory.no=ecID) AS category")
                ->from(TAB_expenses)
                //  ->where("YEARWEEK(`date`, 1) = YEARWEEK(CURDATE(), 1)")
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function newExpense() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        $this->form_validation->set_rules("amount", "Amount is required", "required");
        $this->form_validation->set_rules("paidBy", "Payed By is required", "required");
        if ($this->form_validation->run()) {

            $cols = ["reference", "paidBy", "notes", "ecID", "amount", "employee"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = changeDateFormat($this->input->post("date"));
            $saveData["createdby"] = $this->getUserID();
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
            $this->mdb->insertData(TAB_expenses, $saveData);
            $this->setAlertMsg("Order Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"]) && isset($_POST["amount"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }

        $this->data["categories"] = $this->mdb->getData(TAB_exCategory);
        $this->modal($this->modalPath . "newExpense");
    }

    function expenseCategory() {
        $this->data["categories"] = $this->mdb->getData(TAB_exCategory);
        $this->modal($this->modalPath . "expenseCategory");
    }

    function newCategory() {
        $this->form_validation->set_rules("name", "Category Name is required", "required");

        if ($this->form_validation->run()) {
            $cols = ["name"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $this->mdb->insertData(TAB_exCategory, $saveData);
            $this->setAlertMsg("new Category Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"]) && isset($_POST["amount"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }

        $this->data["categories"] = $this->mdb->getData(TAB_exCategory);
        $this->modal($this->modalPath . "newCategory");
    }

    /*     * ******************************************************************************************** */

    function makeFictitiousOrderFromShiped() {
        $customers = $this->input->get("customers");
        $customers = explode(",", $customers);
        foreach ($customers as $id) {
            $this->mdb->updateData(TAB_fictitiousOrders, ["completed" => 0], ["id" => $id]);
        }
        $this->setAlertMsg(sizeof($customers) . " Shipped Order moved to order successfully!", SUCCESS);
        $this->redirectToReference();
    }

    function getFictitiousCustomersData() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_fictitiousOrders . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_fictitious . '/add')) {
            $extra .= "<button data-remote='" . dashboard_url('addMoreAttachment/' . TAB_fictitiousOrders . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal2'><i class=\"fa fa-plus-square\"></i></button>";
        }
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_fictitiousOrders . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        if (hasPermission(TAB_fictitious . "/refund")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeFictRefund/StopPayment/$1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeFictRefund/ChargeBack/$1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeFictRefund/Refund/$1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        /* if (hasPermission(TAB_fictitious . "/edit")) {
          $extra .= "<button data-remote='" . dashboard_url('editFictitousCustomer/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
          } */
        if (hasPermission(TAB_fictitious . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("fictitiousOrders.id,fictitiousOrders.id AS fisID,fictitiousOrders.paymentType,fictitiousOrders.price,"
                        . "fictitiousOrders.phone,fictitiousOrders.email,fictitiousOrders.contactID,"
                        . "fictitiousOrders.prospectsID,fictitiousOrders.orderDate,fictitiousOrders.stateID,"
                        . "fictitiousOrders.date,fictitiousOrders.publishingDate,fictitiousOrders.publishingCompany,"
                        . "fictitiousOrders.publishingCost,fictitious.company,fictitious.county")
                ->from(TAB_fictitiousOrders)
                ->join(TAB_fictitious, TAB_fictitious . ".contactID=" . TAB_fictitiousOrders . ".contactID")
                ->where(["completed" => "1", "refund" => "0"])
                ->addColumn("actions", $action, "fisID");
        echo $this->datatables->generate();
    }

    function fictitiousCustomers() {
        $this->navMeta = ["active" => "fictitious", "pageTitle" => "Fictitious Customers", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->view($this->viewPath . "fictitiousCustomers");
    }

    function fictitiousOrderMarkCompleted() {
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
                "publishingDate" => changeDateFormat($this->input->post("publishingDate")),
                "date" => changeDateFormat($this->input->post("date")),
                "publishingCompany" => $this->input->post("publishingCompany"),
                "publishingCost" => $this->input->post("publishingCost"),
                "completed" => 1,
                "completedDate" => date("Y-m-d H:i")
            ];
            $saveData["attachment"] = json_encode($oldAttachment);
            // dnp($saveData);
            $this->mdb->updateData(TAB_fictitiousOrders, $saveData, ["id" => $id]);
        }
        return $this->redirectToReference(sizeof($ids) . " Orders mark as completed!", SUCCESS);
    }

    function fictitiousOrders() {
        $this->navMeta = ["active" => 'fictitiousOrders', "pageTitle" => 'Fictitious Orders', "bc" => array(
                ["url" => dashboard_url(), "page" => __FUNCTION__],
                ["url" => "", "page" => 'Fictitious Orders']
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "fictitiousOrders");
    }

    function getFictitiousOrdersData() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_fictitiousOrders . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_fictitious . "/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editFictitiousOrder/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_fictitious . "/refund")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeFictRefund/StopPayment/$1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeFictRefund/ChargeBack/$1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . dashboard_url('makeFictRefund/Refund/$1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        if (hasPermission(TAB_fictitious . "/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("fictitiousOrders.id,fictitiousOrders.id AS fisID,fictitiousOrders.paymentType,fictitiousOrders.price,"
                        . "fictitiousOrders.phone,fictitiousOrders.email,fictitiousOrders.contactID,"
                        . "fictitiousOrders.prospectsID,fictitiousOrders.orderDate,fictitiousOrders.stateID,"
                        . "fictitious.company,note,checkNumber,fictitious.county")
                ->from(TAB_fictitiousOrders)
                ->join(TAB_fictitious, TAB_fictitious . ".contactID=" . TAB_fictitiousOrders . ".contactID")
                ->where(["completed" => "0", "refund" => "0"])
                ->addColumn("actions", $action, "fisID");
        echo $this->datatables->generate();
    }

    function fictitious() {
        $this->navMeta = ["active" => "fictitious", "pageTitle" => "Fictitious ", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->view($this->viewPath . "fictitious");
    }

    function getFictitiousData() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_fictitious . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission(TAB_fictitious . "/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editFictitious/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission(TAB_fictitious . '/delete')) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                //. "<button data-remote='" . dashboard_url('viewTrade/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-eye\"></i></button>"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,regDate, county, owner, contactID, company, address, city, zip, state, preSortdate")
                ->from(TAB_fictitious)

                //  ->where("YEARWEEK(`date`, 1) = YEARWEEK(CURDATE(), 1)")
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function editFictitious($id) {
        $this->form_validation->set_rules("preSortDate", "Pre Sort Date is required", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_prospects, ["id" => $id]);
            $columns = ["regDate", "county", "owner",
                "contactID", "company", "address", "preSortdate",
                "city", "state", "zip"];
            $saveData = [];
            foreach ($columns as $column) {
                $saveData[$column] = $this->input->post($column);
            }
            $saveData["preSortdate"] = changeDateFormat($saveData["preSortdate"]);
            $this->mdb->updateData(TAB_fictitious, $saveData, ["id" => $id]);
            $this->setAlertMsg("Prospect Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["preSortDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["fictitious"] = $this->mdb->getSingleData(TAB_fictitious, ["id" => $id]);
        $this->modal($this->modalPath . "editFictitious");
    }

    function getFicitiousCustomerList() {
        $result = [];
        $term = $this->input->post("term")["term"];
        if ($term) {
            $prospects = $this->mdb->getDataLikeWhere(TAB_fictitious, ["contactID" => $term], ["ordered" => '0'], 0, 20);
        }
        if ($prospects) {
            foreach ($prospects as $prospect) {
                array_push($result, ["id" => $prospect->id, "text" => $prospect->contactID]);
            }
        }
        echo json_encode($result);
    }

    function editFictitiousOrder($id) {
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("contactID", "Contact ID can not be blank", "required");
        if ($this->form_validation->run()) {

            $cols = ["price", "email", "phone", "paymentType", "contactID", "note", "checkNumber"];
            $saveData = [];
            foreach ($cols as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
            $this->mdb->updateData(TAB_fictitiousOrders, $saveData, ["id" => $id]);
            $this->setAlertMsg("Fictitious Order Updated!", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["contactID"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["fictitiousOrder"] = $this->mdb->getSingleData(TAB_fictitiousOrders, ["id" => $id]);
        // dnp($this->data);
        $this->modal($this->modalPath . "editFictitiousOrder");
    }

    function newFictitiousPayment() {
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("contactID", "Contact ID can not be blank", "required");
        if ($this->form_validation->run()) {
            $fis = $this->mdb->getSingleData(TAB_fictitious, ["contactID" => $_POST["contactID"]]);
            if ($fis) {
                $cols = ["price", "email", "phone", "paymentType", "contactID", "note", "checkNumber"];
                $saveData = [];
                foreach ($cols as $col) {
                    $saveData[$col] = $this->input->post($col);
                }
                $saveData["prospectsID"] = $fis->id;
                $saveData["stateID"] = $fis->state;
                $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
                $saveData["addedBy"] = $this->getUserID();
                $this->mdb->insertData(TAB_fictitiousOrders, $saveData);
                $this->mdb->updateData(TAB_fictitious, ["ordered" => '1'], ["id" => $fis->id]);
                $msg = "Order Added!";
                if (valid_email($saveData["email"])) {
                    $template = $this->mdb->getSingleData(TAB_emailTemplates, ["purpose" => "fictitious"]);
                    if ($template) {
                        if ($template->active) {
                            $subject = "Order Confirmation";
                            $message = $template->template;
                            $msg .= sendMail($saveData["email"], $subject, $message,$template->senderEmail) ? "<br>Order Confirmation Email Sent!" : "";
                        }
                    }
                }
                $this->setAlertMsg($msg, SUCCESS);
                return $this->redirectToReference();
            } else {
                $this->someThingWrong();
            }
        } else {
            if (isset($_POST["contactID"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newFictitiousPayment");
    }

    function newFictitious() {
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
                $flag = $this->importFromExcelFictitious($full_path, $_POST["filteredColumns"], $_POST["dbColumns"]);
                //dnp();
                unlink($full_path);
                $this->setAlertMsg($flag . " row inserted successfully! [" . $this->duplicate . " duplicate found!!]", SUCCESS);
            }
            $this->redirectToUrl(dashboard_url("fictitious"));
        } else {
            $this->modal($this->modalPath . "newFictitious");
        }
    }

    private function importFromExcelFictitious($inputFileName, $filteredColumns, $dbColumns) {
        $completed = 0;
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        //$filteredColumns = ['fname', 'mname', 'lname', 'contactid', 'company', 'address', 'presortdate', 'city', 'state', 'zip'];
        //$dbColumns = ["regDate", "county", "owner", "contactID", "company", "address", "preSortdate", "city", "state", "zip"];
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn(1);

        $columns = [];
        foreach (range("A", $highestColumn) as $cell) {
            $val = $sheet->getCell($cell . '1')->getValue();
            if (array_search(strtolower($val), array_map('strtolower', $filteredColumns)) !== FALSE) {
                $columns[$dbColumns[array_search(strtolower($val), array_map('strtolower', $filteredColumns))]] = $cell;
            }
        }
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($columns as $column => $cell) {
                if ($column === "preSortdate" || $column === "regDate") {
                    $rowData[$column] = changeDateFormat($sheet->getCell($cell . $row)->getFormattedValue());
                } elseif (strtolower($column) === strtolower("contactID")) {
                    $rowData[$column] = preg_replace('/[^a-zA-Z0-9]/', '', $sheet->getCell($cell . $row)->getValue());
                } else {
                    $rowData[$column] = $sheet->getCell($cell . $row)->getValue();
                }
            }
            $rowData["addedBy"] = $this->getUserID();
            $rowData["addedTime"] = date("Y-m-d H:i:s");
            if (!$this->mdb->getSingleData(TAB_fictitious, ["contactID" => $rowData["contactID"]])) {
                $this->mdb->insertData(TAB_fictitious, $rowData);
                $completed++;
            } else {
                $this->duplicate++;
            }
        }
        return $completed;
    }

    /*     * ********************************************************** */

    function makeRefund($type, $id, $cus = 0) {
        $this->form_validation->set_rules("refundDate", "Refund Date can not be blank", "required");
        if ($this->form_validation->run()) {
            if ($cus) {
                $dis = $this->mdb->getSingleData(TAB_customers, ["id" => $id]);
                $this->mdb->updateData(TAB_customers, ["refund" => "1"], ["id" => $id]);
            } else {
                $dis = $this->mdb->getSingleData(TAB_orders, ["id" => $id]);
                $this->mdb->updateData(TAB_orders, ["refund" => "1"], ["id" => $id]);
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
                }//L18000152354
                $this->mdb->insertData(TAB_refunds, $saveData);
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
        $this->ifNotValidState($state);

        $this->navMeta = ["active" => $state, "pageTitle" => getState($state) . " Refunds", "bc" => array(
                ["url" => dashboard_url(), "page" => __FUNCTION__],
                ["url" => "", "page" => getState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "refunds");
    }

    function getRefundsData($state) {
        $this->ifNotValidState($state);
        $extra = "";
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_refunds . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,refundType,price,phone,email,contactID,orderDate,refundDate,note,company,attachment")
                ->from(TAB_refunds)
                ->where(["stateID" => $state])
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function makeFictRefund($type, $id, $cus = 0) {
        $this->form_validation->set_rules("refundDate", "Refund Date can not be blank", "required");
        if ($this->form_validation->run()) {
            $dis = $this->mdb->getSingleData(TAB_fictitiousOrders, ["id" => $id]);
            $this->mdb->updateData(TAB_fictitiousOrders, ["refund" => "1"], ["id" => $id]);
            if ($dis) {
                $saveData["refundDate"] = changeDateFormat($this->input->post("refundDate"));
                $saveData["note"] = $this->input->post("note");
                $saveData["contactID"] = $dis->contactID;
                $saveData["orderDate"] = $dis->orderDate;
                $saveData["company"] = isset($dis->company) ? $dis->company : "";
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
                $this->mdb->insertData(TAB_fictitiousRefunds, $saveData);
                $this->setAlertMsg("Added to refunds successfully", SUCCESS);
                return $this->redirectToReference();
            }
        } else {
            if (isset($_POST["refundDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["_type"] = $type;
        $this->data["_id"] = $id;
        $this->modal($this->modalPath . "makeFictRefund");
    }

    function fictRefunds() {
        $this->navMeta = ["active" => "fictitous", "pageTitle" => "Fictitous Refunds", "bc" => array(
                ["url" => dashboard_url(), "page" => __FUNCTION__],
                ["url" => "", "page" => "Fictitous Refunds"]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "fictRefunds");
    }

    function getFictRefundsData() {
        $extra = "";
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_fictitiousRefunds . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,refundType,price,phone,email,contactID,orderDate,refundDate,note,company,stateID")
                ->from(TAB_fictitiousRefunds)
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function delete($table, $id, $column = "id") {
        if (!$table || !$id) {
            $this->someThingWrong();
        }
        if (hasPermissionField($table)) {
            if (!hasPermission($table . '/delete')) {
                $this->redirectToUrl(user_url("logout"), "You are trying to enter where you donot have permission!!", DANGER);
            }
        }
        $this->setAlertMsg("Something Wrong!", DANGER);
        switch ($table) {
            case $table:
                if ($this->mdb->removeData($table, [$column => $id])) {

                    $this->setAlertMsg("Deleted Successfully!", INFO);
                }
                break;
        }
        $this->redirectToReference();
    }

    function removeBulk($table) {
        if (!$table) {
            $this->someThingWrong();
        }
        if (!hasPermission('bulkDelete/all')) {
            $this->redirectToReference("You are trying to enter where you donot have permission!!", DANGER);
        }
        $this->setAlertMsg("Something Wrong!", DANGER);
        $ids = explode(",", $this->input->get("ids"));
        foreach ($ids as $id) {
            $this->mdb->removeData($table, ["id" => $id]);
        }
        $this->setAlertMsg(sizeof($ids) . " Deleted Successfully!", INFO);
        $this->redirectToReference();
    }

//////////////////////////////////////////////////////////\
    function nonBrm() {
        $this->navMeta = ["active" => "nonBrm", "pageTitle" => "NonBRM ", "bc" => array(
                ["url" => dashboard_url(), "page" => __CLASS__],
                ["url" => dashboard_url("brm"), "page" => __FUNCTION__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );

        $this->view($this->viewPath . "nonBrm");
    }

    function getNonBrm() {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_NONBRM . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission("brm/edit")) {
            $extra .= "<button data-remote='" . dashboard_url('editNonBrm/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        } if (hasPermission("brm/delete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        /* $weekStartDate = date('Y-m-d', strtotime("last Monday", time()));
          $weekEndDate = date('Y-m-d', strtotime("next Sunday", time())); */

        $dataTab = $this->datatables
                ->select("id,date,email,regus,ownStamps,(email+regus+ownStamps) AS dayTotal,stateID")
                ->from(TAB_NONBRM)
                //->where("YEARWEEK(`date`, 1) = YEARWEEK(CURDATE(), 1)")
                ->addColumn("actions", $action, "id");

        echo $this->datatables->generate();
    }

    function newNonBrm() {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $saveData = [];
            $n = 0;
            foreach (getBrmState() as $st => $state) {
                if ($this->input->post("email[" . $st . "]") ||
                        $this->input->post("regus[" . $st . "]") ||
                        $this->input->post("ownStamps[" . $st . "]")) {
                    $columns = ["email", "regus", "ownStamps"];
                    $saveDataTemp = [];
                    $saveDataTemp["stateID"] = $st;
                    foreach ($columns as $col) {
                        $saveDataTemp[$col] = $this->input->post($col . "[" . $st . "]") ?
                                $this->input->post($col . "[" . $st . "]") :
                                0;
                    }
                    $saveDataTemp["date"] = changeDateFormat($this->input->post("date"));
                    $saveDataTemp["addedBy"] = $this->getUserID();
                    array_push($saveData, $saveDataTemp);
                }
            }
            //dnp($saveData);
            $this->mdb->insertBatchData(TAB_NONBRM, $saveData);
            $this->setAlertMsg("NON BRM Added", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->modal($this->modalPath . "newNonBrm");
    }

    function editNonBrm($id) {
        $this->form_validation->set_rules("date", "Date is required", "required");
        if ($this->form_validation->run()) {
            $columns = ["date", "email", "regus", "ownStamps", "stateID"];
            $saveData = [];
            foreach ($columns as $col) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["date"] = changeDateFormat($saveData["date"]);
            $this->mdb->updateData(TAB_NONBRM, $saveData, ["id" => $id]);
            $this->setAlertMsg("NON BRM Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["date"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["nonBrm"] = $this->mdb->getSingleData(TAB_NONBRM, ["id" => $id]);
        $this->modal($this->modalPath . "editNonBrm");
    }

}
