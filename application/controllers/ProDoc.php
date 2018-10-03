<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Aug 31, 2018 , 6:55:00 PM
 * @property Pdf $pdf Description
 */
class ProDoc extends BRT_Controller {

    public $viewPath = "proDoc/";
    public $modalPath = "proDoc/modal/";
    private $duplicate = 0;
    private $missing = 0;

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
    }

    function index() {
        $this->navMeta = ["active" => "proDoc", "pageTitle" => "Property Document", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];
        $this->meta["pageJs"] = array(
        );
        $this->meta["pageCss"] = array(
        );
        $this->view($this->viewPath . "index");
    }

    function newProspects($state) {
        //  $this->ifNotValidState($state, "proDocState");
        $this->ifNotPermited("proDoc/add");
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
                $flag = $this->importFromExcel($full_path, $state, $_POST["filteredColumns"], $_POST["dbColumns"]); //, $_POST["preSortDateFormat"], $_POST["saleDateFormat"]);

                $this->setAlertMsg($data['upload_data']['orig_name'] . " [size:" . $data["upload_data"]["file_size"] .
                        "KB] Uploaded!<br>"
                        . $flag . " row inserted successfully! [" . $this->duplicate . " duplicate found!!] "
                        . "<br>--{ " . $this->missing . " row missing contact ID}--", SUCCESS);
            }

            $this->redirectToReference();
        } else {
            $this->modal($this->modalPath . "newProspects");
        }
    }

    private function importFromExcel($inputFileName, $state, $filteredColumns, $dbColumns) {
        //dnp($inputFileName);
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
        // dnd($highestColumn);
        foreach (createColumnsArray($highestColumn) as $cell) {
            $val = $sheet->getCell($cell . '1')->getValue();
            // dnp($val);
            //dnp($cell);
            if ($val) {
                if (array_search(strtolower($val), array_map('strtolower', $filteredColumns)) !== FALSE) {
                    $columns[$dbColumns[array_search(strtolower($val), array_map('strtolower', $filteredColumns))]] = $cell;
                }
            }
        }

        // dnp($columns);
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($columns as $column => $cell) {
                if (strtolower($column) === strtolower("presortdate") || strtolower($column) === strtolower("saledate")) {
                    $v = $sheet->getCell($cell . $row)->getFormattedValue();
                    //   dnp($v);
                    $rowData[$column] = changeDateFormat($v);
                } elseif (strtolower($column) === strtolower("contactID")) {
                    $rowData[$column] = preg_replace('/[^a-zA-Z0-9]/', '', $sheet->getCell($cell . $row)->getValue());
                } else {
                    $rowData[$column] = $sheet->getCell($cell . $row)->getValue();
                }
            }
            // dnp($rowData);
            // dnp($columns);
            $rowData["stateID"] = $state;
            $rowData["addedBy"] = $this->getUserID();
            $rowData["addedTime"] = date("Y-m-d H:i:s");

            if (isset($rowData["contactID"])) {
                if (!$this->crm->getCountProDocProspects($rowData["contactID"], $state)) {
                    //dnp($rowData);
                    $this->mdb->insertData(TAB_proDocProspects, $rowData);
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

    function prospects($state) {
        $this->ifNotPermited("proDoc");
        $count = $this->mdb->countData(TAB_proDocProspects, ["stateID" => $state]);
        $this->navMeta = ["active" => "pd_prospects", "pageTitle" => getProDocState($state) . " Prospects [ " . $count . " ]", "bc" => array(
                ["url" => proDoc_url(), "page" => __CLASS__],
                ["url" => "", "page" => __FUNCTION__]
        )];
        $this->data["_currentState"] = $state;
        $this->view($this->viewPath . "prospects");
    }

    function editProspect($id) {
        $this->ifNotPermited("proDoc/edit");
        $this->form_validation->set_rules("preSortDate", "Pre Sort Date is required", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["id" => $id]);
            $columns = getProDocStateColumns($prospect->stateID);
            $saveData = [];
            foreach ($columns as $col => $column) {
                $saveData[$col] = $this->input->post($col);
            }
            $saveData["preSortDate"] = changeDateFormat($saveData["preSortDate"]);
            $saveData["saleDate"] = changeDateFormat($saveData["saleDate"]);
            $this->mdb->updateData(TAB_proDocProspects, $saveData, ["id" => $id]);
            $this->setAlertMsg("Prospect Edited", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["preSortDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["prospect"] = $this->mdb->getSingleData(TAB_proDocProspects, ["id" => $id]);
        $this->modal($this->modalPath . "editProspect");
    }

    function getProspectsData($state) {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_proDocProspects . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission("proDoc/edit")) {
            $extra .= "<button data-remote='" . proDoc_url('editProspect/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission('proDoc/delete')) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }

        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";

        $columns = ",";

        foreach (getProDocStateColumns($state) as $col => $va) {
            $columns .= $col . ",";
        }
        $columns = rtrim($columns, ",");
        $this->datatables
                ->select("id,stateID$columns")
                ->from(TAB_proDocProspects)
                ->where(["stateID" => $state])
                ->addColumn("actions", $action, "id,stateID,contactID");

        echo $this->datatables->generate();
    }

    function getCustomerList() {
        $result = [];
        $state = $this->input->post("state");
        $term = isset($this->input->post("term")["term"]) ? $this->input->post("term")["term"] : "";
        $prospects = [];
        if ($term) {
            $prospects = $this->mdb->getDataLikeWhere(TAB_proDocProspects, ["contactID" => $term], ["stateID" => $state, "ordered" => "0"], 0, 20);
        }
        if ($prospects) {
            foreach ($prospects as $prospect) {
                array_push($result, ["id" => $prospect->id, "text" => $prospect->contactID]);
            }
        }
        $result["extra"] = ["state" => $state, "term" => $term];

        echo json_encode($result);
    }

    function newPayment($state) {
        $this->data["_currentState"] = $state;
        if (isset($_GET["prospectID"])) {
            $this->data["_currentProspect"] = $this->input->get("prospectID");
            $this->data["_currentProspectContactID"] = $this->mdb->getSingleData(TAB_proDocProspects, ["id" => $_GET["prospectID"]])->contactID;
        }
        $this->form_validation->set_rules("stateID", "State is required", "required");
        $this->form_validation->set_rules("price", "Price is required", "required");
        $this->form_validation->set_rules("paymentType", "Payment Type is required", "required");
        $this->form_validation->set_rules("prospectsID", "Prospect ID can not be blank", "required");
        if ($this->form_validation->run()) {
            $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["id" => $_POST["prospectsID"]]);
            if ($prospect) {
                $cols = ["stateID", "price", "prospectsID", "email", "phone", "paymentType", "contactID", "checkNumber"];
                $saveData = [];
                foreach ($cols as $col) {
                    $saveData[$col] = $this->input->post($col);
                }
                $saveData["company"] = $prospect ? $prospect->name : "";
                $saveData["siteAddress"] = $prospect ? $prospect->siteAddress : "";
                $saveData["orderDate"] = changeDateFormat($this->input->post("orderDate"));
                $saveData["addedBy"] = $this->getUserID();
                if (!$this->crm->getProDocCountOrders($saveData["contactID"], $state)) {
                    $this->mdb->insertData(TAB_proDocOrders, $saveData);
                    $this->mdb->updateData(TAB_proDocProspects, ["ordered" => '1'], ["id" => $_POST["prospectsID"]]);
                    $msg = "Order Added!";
                    if (valid_email($saveData["email"])) {
                        $template = $this->mdb->getSingleData(TAB_emailTemplates, ["purpose" => "proDoc"]);
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
            $this->mdb->updateData(TAB_proDocOrders, $saveData, ["id" => $id]);
            $this->setAlertMsg("Order Edited!", SUCCESS);
            //  dnp($saveData);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["orderDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["order"] = $this->mdb->getSingleData(TAB_proDocOrders, ["id" => $id]);
        $this->modal($this->modalPath . "editOrder");
    }

    function orders($state) {
        $this->ifNotPermited("proDoc/orders");
        $this->navMeta = ["active" => $state, "pageTitle" => getProDocState($state) . " Orders", "bc" => array(
                ["url" => proDoc_url(), "page" => __FUNCTION__],
                ["url" => "", "page" => getProDocState($state)]
        )];
        $this->data["_currentState"] = $state;

        $this->view($this->viewPath . "orders");
    }

    function getOrdersData($state) {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_proDocOrders . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission("proDoc/orderEdit")) {
            $extra .= "<button data-remote='" . proDoc_url('editOrder/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission("proDoc/refunds")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . proDoc_url('makeRefund/StopPayment/$1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . proDoc_url('makeRefund/ChargeBack/$1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . proDoc_url('makeRefund/Refund/$1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        if (hasPermission("proDoc/orderDelete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $proDocCover = "<a target='_blank' class='btn btn-link btn-sm rounded-0' href='" . proDoc_url('getProDocCover/$3/$2') . "'><i class='fa fa-list'></i></a>";

        if (true) {
            $extra .= '<button type="button" class="btn btn-success salesHistory p-0 px-1" data-loadLink="' . curl_url("getSalesHistory/$3/$2") . '" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Sales History" '
                    . 'data-placement="left" data-html="true" >'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }

        $action = "<div class=\"text-center\">"
                . $extra . $proDocCover
                . "</div>";
        $this->datatables
                ->select("id,paymentType,price,phone,email,contactID,prospectsID,orderDate,checkNumber,siteAddress,stateID")
                ->from(TAB_proDocOrders)
                ->where(["stateID" => $state, "shipped" => "0", "refund" => "0"])
                ->addColumn("actions", $action, "id,contactID,stateID");
        echo $this->datatables->generate();
    }

    function prospectDetails($state) {
        $prospectID = $this->input->get("prospectID");
        $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["id" => $prospectID, "stateID" => $state]);
        $this->data["prospect"] = $prospect;
        $this->data["_currentState"] = $state;
        $this->modal($this->modalPath . "prospectDetails");
    }

    function makeShipped($state) {
        $orders = $this->input->get("orders");
        $orders = explode(",", $orders);
        foreach ($orders as $id) {
            $order = $this->mdb->getSingleData(TAB_proDocOrders, ["id" => $id]);
            if ($order) {
                // dnp($order);
                $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["contactID" => $order->contactID, "stateID" => $state]);
                $this->mdb->updateData(TAB_proDocOrders, ["shipped" => "1"], ["id" => $id]);
                $saveData = ["contactID" => $order->contactID, "paymentType" => $order->paymentType, "price" => $order->price,
                    "email" => $order->email, "phone" => $order->phone, "stateID" => $order->stateID, "addedBy" => $this->getUserID(),
                    "addedTime" => date("Y-m-d"), "shippedDate" => date("Y-m-d"), "orderDate" => $order->orderDate,
                    "siteAddress" => $prospect ? $prospect->siteAddress : "", "company" => $prospect ? $prospect->name : ""];
                if (!$this->mdb->getSingleData(TAB_proDocCustomers, $saveData)) {
                    $this->mdb->insertData(TAB_proDocCustomers, $saveData);
                }
            }
        }
        $this->exportWhenShipped($orders, $state);
        $this->setAlertMsg(sizeof($orders) . " Order Marked as shipped successfully!", SUCCESS);
        $this->redirectToUrl(proDoc_url("orders/" . $state));
    }

    function makeOrderFromShiped($state) {
        $customers = $this->input->get("customers");
        $customers = explode(",", $customers);
        foreach ($customers as $id) {
            $customer = $this->mdb->getSingleData(TAB_proDocCustomers, ["id" => $id]);
            //dnp($customer);
            if ($customer) {
                $this->mdb->removeData(TAB_proDocCustomers, ["id" => $id]);
                $this->mdb->updateData(TAB_proDocOrders, ["shipped" => "0"], ["contactID" => $customer->contactID, "stateID" => $state]);
            }
        }
        $this->setAlertMsg(sizeof($customers) . " Shipped Order moved to order successfully!", SUCCESS);
        $this->redirectToUrl(proDoc_url("customers/" . $state));
    }

    private function exportWhenShipped($orders, $state) {

        $allData = [];
        if (sizeof($orders)) {
            foreach ($orders as $id) {
                $query = "SELECT * FROM " . TAB_proDocOrders . " JOIN " . TAB_proDocProspects . " "
                        . "ON proDocOrders.contactID=proDocProspects.contactID AND proDocOrders.stateID=proDocProspects.stateID "
                        . " WHERE proDocOrders.id='$id' AND proDocOrders.stateID='$state'";
                $data = $this->mdb->executeCustomArray($query);
                if (isset($data[0])) {
                    array_push($allData, $data[0]);
                }
            }
        }
        $fileName = isset($_POST["fileName"]) ? ($this->input->post("fileName") . ".xls") : $state . "-" . date("y-m-d") . "-Shipped.xls";

        $columnNames = ["contactID", "company", "siteAddress", "city", "state", "zip", "email", "phone"];
        $realColumnNames = ["Contact ID", "Company", "SiteAddress", "City", "State", "Zip", "Email", "Phone"];
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
        $this->ifNotPermited("proDoc/customers");
        $this->navMeta = ["active" => $state, "pageTitle" => getProDocState($state) . " Customers", "bc" => array(
                ["url" => proDoc_url(), "page" => __CLASS__],
                ["url" => proDoc_url("prospects/" . $state), "page" => __FUNCTION__],
                ["url" => "", "page" => getProDocState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->view($this->viewPath . "customers");
    }

    function getCustomersData($state) {
        $extra = "";
        $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_proDocCustomers . '/$1') . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
        if (hasPermission("proDoc/customerEdit")) {
            $extra .= "<button data-remote='" . proDoc_url('editCustomer/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
        }
        if (hasPermission("proDoc/refunds")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" '
                    . 'title="<button class=\'pop-close btn btn-sm btn-defult\'><i class=\'fa fa-close text-danger pop-close\'></i></button> Refund options" '
                    . 'data-toggle="popover" data-placement="left" data-html="true" '
                    . 'data-content="'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . proDoc_url('makeRefund/StopPayment/$1/1') . '\'>StopPayment</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . proDoc_url('makeRefund/ChargeBack/$1/1') . '\'>ChargeBack</button><br>'
                    . '<button class=\'btn btn-link w-100 btn-sm h6 rounded-0 pop-close\' '
                    . 'modal-toggler=\'true\' data-target=\'#remoteModal1\' '
                    . 'data-remote=\'' . proDoc_url('makeRefund/Refund/$1/1') . '\'>Refund</button>'
                    . '">'
                    . '<i class="fa fa-external-link"></i>'
                    . '</button>';
        }
        if (hasPermission("proDoc/customerDelete")) {
            $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
        }
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id AS cusID,id,paymentType,price,"
                        . "phone,email,contactID,"
                        . "shippedDate,company,orderDate,siteAddress")
                ->from(TAB_proDocCustomers)
                ->where(["stateID" => $state, "refund" => "0"])
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
            $this->mdb->updateData(TAB_proDocCustomers, $saveData, ["id" => $id]);
            $this->setAlertMsg("Customer Edited!", SUCCESS);
            return $this->redirectToReference();
        } else {
            if (isset($_POST["shippedDate"])) {
                $this->setAlertMsg(validation_errors(), DANGER);
                return $this->redirectToReference();
            }
        }
        $this->data["customer"] = $this->mdb->getSingleData(TAB_proDocCustomers, ["id" => $id]);
        $this->modal($this->modalPath . "editCustomer");
    }

    function makeRefund($type, $id, $cus = 0) {
        $this->form_validation->set_rules("refundDate", "Refund Date can not be blank", "required");
        if ($this->form_validation->run()) {
            if ($cus) {
                $dis = $this->mdb->getSingleData(TAB_proDocCustomers, ["id" => $id]);
                $this->mdb->updateData(TAB_proDocCustomers, ["refund" => "1"], ["id" => $id]);
            } else {
                $dis = $this->mdb->getSingleData(TAB_proDocOrders, ["id" => $id]);
                $this->mdb->updateData(TAB_proDocOrders, ["refund" => "1"], ["id" => $id]);
            }
            if ($dis) {
                $saveData["refundDate"] = changeDateFormat($this->input->post("refundDate"));
                $saveData["note"] = $this->input->post("note");
                $saveData["contactID"] = $dis->contactID;
                $saveData["orderDate"] = $dis->orderDate;
                $saveData["company"] = $dis->company;
                $saveData["siteAddress"] = $dis->company;
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
                $this->mdb->insertData(TAB_proDocRefunds, $saveData);
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
        $this->ifNotPermited("proDoc/refunds");
        $this->navMeta = ["active" => $state, "pageTitle" => getProDocState($state) . " Refunds", "bc" => array(
                ["url" => proDoc_url(), "page" => __FUNCTION__],
                ["url" => proDoc_url("redunds/" . $state), "page" => __FUNCTION__],
                ["url" => "", "page" => getProDocState($state)]
        )];
        $this->data["_currentState"] = $state;
        $this->view($this->viewPath . "refunds");
    }

    function getRefundsData($state) {

        $extra = "";
        $extra .= "<button data-remote='" . dashboard_url('showUpload/' . TAB_proDocRefunds . '/$1') . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-link\"></i></button>";
        $action = "<div class=\"text-center\">"
                . $extra
                . "</div>";
        $this->datatables
                ->select("id,refundType,price,phone,email,contactID,orderDate,refundDate,note,company,siteAddress,attachment")
                ->from(TAB_proDocRefunds)
                ->where(["stateID" => $state])
                ->addColumn("actions", $action, "id");
        echo $this->datatables->generate();
    }

    function php() {
        phpinfo();
    }

    function getProDocCover($stateID, $contactID) {
        $prospect = $this->mdb->getSingleData(TAB_proDocProspects, ["contactID" => $contactID, "stateID" => $stateID]);
        $coverPageData = $prospect->name .
                "<br>" .
                $prospect->siteAddress;
        $data = ["coverPageData" => $coverPageData, "contactID" => $prospect->contactID];
        $html = $this->load->view("theme/proDoc/coverPage", $data, TRUE);

        $this->load->library("pdf");
        $mpdf = $this->pdf->loadMpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetMargins(10, 10, 25);
        $mpdf->WriteHTML($html);
        $mpdf->Output("op.pdf", "I");
    }

    function dump() {
        echo date("d M, Y", strtotime("9/8/2018"));
        /* $users = $this->mdb->getData(TAB_USERS);
          foreach ($users as $user) {
          dnp($user);
          dnp($this->encryption->decrypt($user->password));
          } */
    }

}
