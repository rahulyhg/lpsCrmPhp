<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 30, 2018 , 9:02:18 AM
 */
$states = [];
foreach (getState() as $key => $value) {
    if (strlen($key) == 2) {
        $states[$key] = $value;
    }
}
$states["FIC"] = "Fictitious";
$GLOBALS["states"] = $states;

class Report extends CI_Model {

    private $dateFrom = "";
    private $dateTo = "";
    private $states = [];

    public function __construct() {
        parent::__construct();
        $this->states = $GLOBALS["states"];
    }

    function setDate($dateFrom = 0, $dateTo = 0) {
        if ($dateFrom) {
            $this->dateFrom = changeDateFormat($dateFrom);
        }
        if ($dateTo) {
            $this->dateTo = changeDateFormat($dateTo);
        }
    }

    function getAcStateList() {
        $return = [];
        foreach (getACState() as $key => $state) {
            $return[$key] = "ac" . $key;
        }
        return $return;
    }

    function getStateList() {
        return $this->states;
    }

    function paymentsAdded($fict = false) {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_orders);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACorders);
        if ($this->dateFrom) {
            $acstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["paymentsAdded"][$acState])) {
                $return["paymentsAdded"][$acState] = [];
                $return["totalAmount"][$acState] = [];
            }
            $return["paymentsAdded"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["totalAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocOrders);
        if ($this->dateFrom) {
            $pdstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return["paymentsAdded"][$state])) {
                $return["paymentsAdded"][$state] = [];
                $return["totalAmount"][$state] = [];
            }
            $return["paymentsAdded"][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
            $return["totalAmount"][$state] = number_format(isset($pdTemp[$state]) ? (isset($pdTemp[$state]->amountUSD) ? $pdTemp[$state]->amountUSD : 0) : 0, 2);
        }

        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousOrders);
        if ($fict) {
            $fictitiousQuery->where(["completed" => "0"]);
        }
        if ($this->dateFrom) {
            $fictitiousQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }

        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["paymentsAdded"][$id])) {
                $return["paymentsAdded"][$id] = [];
                $return["totalAmount"][$id] = [];
            }
            $return["paymentsAdded"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["totalAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function online() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_orders);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Online"]);
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACorders);
        if ($this->dateFrom) {
            $acstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Online"]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["online"][$acState])) {
                $return["online"][$acState] = [];
                $return["onlineUSDAmount"][$acState] = [];
            }
            $return["online"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["onlineUSDAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** ProDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocOrders);
        if ($this->dateFrom) {
            $pdstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Online"]);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $st) {
            if (!isset($return["online"][$state])) {
                $return["online"][$state] = [];
                $return["onlineUSDAmount"][$state] = [];
            }
            $return["online"][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
            $return["onlineUSDAmount"][$state] = number_format(isset($pdTemp[$state]) ? (isset($pdTemp[$state]->amountUSD) ? $pdTemp[$state]->amountUSD : 0) : 0, 2);
        }
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["paymentType" => "Online"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["check"][$id])) {
                $return["online"][$id] = [];
                $return["onlineUSDAmount"][$id] = [];
            }
            $return["online"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["onlineUSDAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function check() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_orders);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Check"]);
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();


        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACorders);
        if ($this->dateFrom) {
            $acstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Check"]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["check"][$acState])) {
                $return["check"][$acState] = [];
                $return["checkUSDAmount"][$acState] = [];
            }
            $return["check"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["checkUSDAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocOrders);
        if ($this->dateFrom) {
            $pdstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Check"]);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $State => $pdState) {
            if (!isset($return["check"][$State])) {
                $return["check"][$State] = [];
                $return["checkUSDAmount"][$State] = [];
            }
            $return["check"][$State] = isset($pdTemp[$State]) ? (isset($pdTemp[$State]->num) ? $pdTemp[$State]->num : 0) : 0;
            $return["checkUSDAmount"][$State] = number_format(isset($pdTemp[$State]) ? (isset($pdTemp[$State]->amountUSD) ? $pdTemp[$State]->amountUSD : 0) : 0, 2);
        }

        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["paymentType" => "Check"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["check"][$id])) {
                $return["check"][$id] = [];
                $return["checkUSDAmount"][$id] = [];
            }
            $return["check"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["checkUSDAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function credit() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_orders);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->where(["paymentType" => "Credit"]);
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["paymentType" => "Credit"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();


        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACorders);
        if ($this->dateFrom) {
            $acstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["paymentType" => "Credit"]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["credit"][$acState])) {
                $return["credit"][$acState] = [];
                $return["creditUSDAmount"][$acState] = [];
            }
            $return["credit"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["creditUSDAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** AC ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocOrders);
        if ($this->dateFrom) {
            $pdstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["paymentType" => "Credit"]);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $State => $st) {
            $pdState = $State;
            if (!isset($return["credit"][$pdState])) {
                $return["credit"][$pdState] = [];
                $return["creditUSDAmount"][$pdState] = [];
            }
            $return["credit"][$pdState] = isset($pdTemp[$State]) ? (isset($pdTemp[$State]->num) ? $pdTemp[$State]->num : 0) : 0;
            $return["creditUSDAmount"][$pdState] = number_format(isset($pdTemp[$State]) ? (isset($pdTemp[$State]->amountUSD) ? $pdTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["credit"][$id])) {
                $return["credit"][$id] = [];
                $return["creditUSDAmount"][$id] = [];
            }
            $return["credit"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["creditUSDAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function postersSent() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID");
        $stateQuery->from(TAB_customers);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num");
        $fictitiousQuery->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["completed" => "1"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (isset($temp[$id])) {
                $return[$id] = $temp[$id]->num;
            } else {
                $return[$id] = 0;
            }
        }
        return ["posterSent" => $return];
    }

    function mailngs($byDate = false) {
        $return = [];
        foreach (getBrmState() as $st => $state) {
            $stateQuery = $this->db->select("SUM(`" . $st . "sent`) AS '$st'");
        }
        $stateQuery = $this->db->select("date");
        $stateQuery->from(TAB_MAILING);
        if ($this->dateFrom) {
            $stateQuery->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`date`<='" . $this->dateTo . "'");
        }
        if ($byDate) {
            $stateQuery->group_by("date");
        }
        $results = $stateQuery->get();
        if ($byDate) {
            $results = $results->result();
            foreach ($results as $result) {
                $return[$result->date] = $result;
            }
        } else {
            $return = $results->first_row("array");
        }
        return ["mailing" => $return];
    }

    function nonBrm($byDate = false) {
        $return = [];
        $stateQuery = $this->db->select("stateID,SUM(email)+SUM(regus)+SUM(ownStamps) AS num,date");
        $stateQuery->from(TAB_NONBRM);
        if ($this->dateFrom) {
            $stateQuery->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`date`<='" . $this->dateTo . "'");
        }
        if ($byDate) {
            $stateQuery->group_by("date,stateID");
        } else {
            $stateQuery->group_by("stateID");
        }
        $results = $stateQuery->get()->result();
        if ($byDate) {
            $temp = [];
            foreach ($results as $result) {
                foreach (getBrmState() as $id => $state) {
                    if (!isset($return[$result->date][$id])) {
                        $return[$result->date][$id] = 0;
                    }
                    if ($id === $result->stateID) {
                        $return[$result->date][$id] += $result->num;
                    } else {
                        $return[$result->date][$id] += 0;
                    }
                }
            }
        } else {
            $temp = [];
            foreach ($results as $result) {
                $temp[$result->stateID] = $result;
            }
            $temp["FIC"] = ["stateID" => "FIC", "num" => isset($temp["FIC"]) ? $temp["FIC"]->num : 0];
            foreach (getBrmState() as $id => $state) {
                if (isset($temp[$id])) {
                    $dl = (object) $temp[$id];
                    $return[$id] = $dl->num;
                } else {
                    $return[$id] = 0;
                }
            }
        }
        return ["nonBrm" => $return];
    }

    function brmReceived($byDate = false) {
        $return = [];
        /* $stateQuery = $this->db->select("SUM(`FLreceived`) AS FL,"
          . " SUM(`GAreceived`) AS GA,"
          . " SUM(`LAreceived`) AS LA,"
          . " SUM(`OHreceived`) AS OH,"
          . " SUM(`MAreceived`) AS MA,"
          . " SUM(`TXreceived`) AS TX,"
          . " SUM(`NCreceived`) AS NC,"
          . " SUM(`NJreceived`) AS NJ,"
          . " SUM(`COreceived`) AS CO,"
          . " SUM(`PAreceived`) AS PA,"
          . " SUM(`INreceived`) AS 'IN',"
          . " SUM(`MIAreceived`) AS MIA,"
          . " SUM(`BROreceived`) AS BRO,"
          . " SUM(`HILreceived`) AS 'HIL',"
          . " SUM(`LACreceived`) AS 'LAC',"
          . " SUM(`acFLreceived`) AS 'acFL',"
          . " SUM(`acNCreceived`) AS 'acNC',"
          . " SUM(`acNJreceived`) AS 'acNJ',"
          . " SUM(`FICreceived`) AS FIC,"
          . " date"); */
        foreach (getBrmState() as $st => $state) {
            $stateQuery = $this->db->select("SUM(`" . $st . "received`) AS '$st'");
        }
        $stateQuery = $this->db->select("date");
        $stateQuery->from(TAB_BRM);
        if ($this->dateFrom) {
            $stateQuery->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`date`<='" . $this->dateTo . "'");
        }
        if ($byDate) {
            $stateQuery->group_by("date");
        }
        $results = $stateQuery->get();
        if ($byDate) {
            $results = $results->result();
            foreach ($results as $result) {
                $return[$result->date] = $result;
            }
        } else {
            $return = $results->first_row("array");
        }
        return $return;
    }

    function brmCharged($byDate = false) {
        $return = [];
        foreach (getBrmState() as $st => $state) {
            $stateQuery = $this->db->select("SUM(`" . $st . "charged`) AS '$st'");
        }
        $stateQuery = $this->db->select("date");
        $stateQuery->from(TAB_BRM);
        if ($this->dateFrom) {
            $stateQuery->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`date`<='" . $this->dateTo . "'");
        }
        if ($byDate) {
            $stateQuery->group_by("date");
        }
        $results = $stateQuery->get();
        if ($byDate) {
            $results = $results->result();
            foreach ($results as $result) {
                $return[$result->date] = $result;
            }
        } else {
            $return = $results->first_row("array");
        }

        return $return;
    }

    function brm() {
        $received = $this->brmReceived();
        $charged = $this->brmCharged();
        $brmPercent = [];
        foreach ($received as $state => $rec) {
            if ($rec) {
                $brmPercent[$state] = number_format(((($rec - $charged[$state]) / $rec) * 100), 2) . "%";
            } else {
                $brmPercent[$state] = "";
            }
        }
        return ["brmReceived" => $received, "brmCharged" => $charged, "brmPercent" => $brmPercent];
    }

    function refund() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_refunds);
        $stateQuery->where(["refundType" => "Refund"]);
        if ($this->dateFrom) {
            $stateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACrefunds);
        if ($this->dateFrom) {
            $acstateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $acstateQuery->where(["refundType" => "Refund"]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["refund"][$acState])) {
                $return["refund"][$acState] = [];
                $return["refundAmount"][$acState] = [];
            }
            $return["refund"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["refundAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocRefunds);
        if ($this->dateFrom) {
            $pdstateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $pdstateQuery->where(["refundType" => "Refund"]);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return["refund"][$state])) {
                $return["refund"][$state] = [];
                $return["refundAmount"][$state] = [];
            }
            $return["refund"][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
            $return["refundAmount"][$state] = number_format(isset($pdTemp[$state]) ? (isset($pdTemp[$state]->amountUSD) ? $pdTemp[$state]->amountUSD : 0) : 0, 2);
        }
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousRefunds);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["refundType" => "Refund"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["refund"][$id])) {
                $return["refund"][$id] = [];
                $return["refundAmount"][$id] = [];
            }
            $return["refund"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["refundAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function chargeBack() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_refunds);
        $stateQuery->where(["refundType" => "ChargeBack"]);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACrefunds);
        if ($this->dateFrom) {
            $acstateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $acstateQuery->where(["refundType" => "ChargeBack"]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["chargeBack"][$acState])) {
                $return["chargeBack"][$acState] = [];
                $return["chargeBackAmount"][$acState] = [];
            }
            $return["chargeBack"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["chargeBackAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocRefunds);
        if ($this->dateFrom) {
            $pdstateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $pdstateQuery->where(["refundType" => "ChargeBack"]);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return["chargeBack"][$state])) {
                $return["chargeBack"][$state] = [];
                $return["chargeBackAmount"][$state] = [];
            }
            $return["chargeBack"][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
            $return["chargeBackAmount"][$state] = number_format(isset($pdTemp[$state]) ? (isset($pdTemp[$state]->amountUSD) ? $pdTemp[$state]->amountUSD : 0) : 0, 2);
        }
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousRefunds);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["refundType" => "ChargeBack"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["chargeBack"][$id])) {
                $return["chargeBack"][$id] = [];
                $return["chargeBackAmount"][$id] = [];
            }
            $return["chargeBack"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["chargeBackAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function stopPayment() {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_refunds);
        $stateQuery->where(["refundType" => "StopPayment"]);
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACrefunds);
        if ($this->dateFrom) {
            $acstateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $acstateQuery->where(["refundType" => "StopPayment"]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["stopPayment"][$acState])) {
                $return["stopPayment"][$acState] = [];
                $return["stopPaymentAmount"][$acState] = [];
            }
            $return["stopPayment"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["stopPaymentAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocRefunds);
        if ($this->dateFrom) {
            $pdstateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $pdstateQuery->where(["refundType" => "StopPayment"]);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return["stopPayment"][$state])) {
                $return["stopPayment"][$state] = [];
                $return["stopPaymentAmount"][$state] = [];
            }
            $return["stopPayment"][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
            $return["stopPaymentAmount"][$state] = number_format(isset($pdTemp[$state]) ? (isset($pdTemp[$state]->amountUSD) ? $pdTemp[$state]->amountUSD : 0) : 0, 2);
        }
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousRefunds);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->where(["refundType" => "StopPayment"]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["stopPayment"][$id])) {
                $return["stopPayment"][$id] = [];
                $return["stopPaymentAmount"][$id] = [];
            }
            $return["stopPayment"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["stopPaymentAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function getGeneralReport() {
        $reports = [];
        $reports += $this->paymentsAdded();
        $reports += $this->check();
        $reports += $this->credit();
        $reports += $this->postersSent();
        $reports += $this->mailngs();
        $reports += $this->brm();
        $reports += $this->chargeBack();
        $reports += $this->stopPayment();
        $reports += $this->refund();
        $reports += $this->nonBrm();
        $reports += $this->online();
        return $reports;
    }

    /*     * ******************************************************************************************* */

    function fictious() {
        $return = [];
        $fict = $this->db->select("COUNT(*) AS paymentQty,SUM(price) AS dollerTotal,orderDate");
        $fict->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fict->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fict->where(["completed" => '0']);
        $fict->group_by("orderDate");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->orderDate] = $res;
        }
        $return["fictOrder"] = $array;
        ///
        $fict = null;
        $fict = $this->db->select("COUNT(*) AS customers,SUM(price) AS custTotal,date as orderDate,SUM(publishingCost) AS pubCost");
        $fict->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fict->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`date`<='" . $this->dateTo . "'");
        }
        $fict->where(["completed" => '1']);
        $fict->group_by("date");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->orderDate] = $res;
        }
        $return["fictCustomer"] = $array;
        ///
        $fict = null;
        $fict = $this->db->select("COUNT(*) AS creditNum,SUM(price) AS creditTotal,orderDate");
        $fict->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fict->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fict->where(["paymentType" => "Credit"]);
        $fict->group_by("orderDate");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->orderDate] = $res;
        }
        $return["credit"] = $array;
        ///
        $fict = null;
        $fict = $this->db->select("COUNT(*) AS checkNum,SUM(price) AS checkTotal,orderDate");
        $fict->from(TAB_fictitiousOrders);
        if ($this->dateFrom) {
            $fict->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fict->where(["paymentType" => "Check"]);
        $fict->group_by("orderDate");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->orderDate] = $res;
        }
        $return["check"] = $array;
        ///deposits


        $return["deposits"] = $this->getBankDepositDetails(4);
        $return["settllements"] = $this->getCcSettllementsDetails(4);
        $return["Refund"] = $this->fictRefunds("Refund");
        $return["ChargeBack"] = $this->fictRefunds("ChargeBack");
        $return["StopPayment"] = $this->fictRefunds("StopPayment");
        $return["brmCharged"] = $this->brmCharged(true);
        $return += $this->nonBrm(true);
        $return += $this->mailngs(true);
        return $return;
    }

    function fictRefunds($refundType = "Refund") {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amount,refundDate");
        $stateQuery->from(TAB_fictitiousRefunds);
        $stateQuery->where(["refundType" => $refundType]);
        if ($this->dateFrom) {
            $stateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("refundDate");
        $results = $stateQuery->get()->result();
        $array = [];
        foreach ($results as $res) {
            $return[$res->refundDate] = $res;
        }
        //dnd($results);
        return $return;
    }

    function getBankDepositDetails($id, $ac = false) {
        $return = [];
        $fict = $this->db->select("account,date");
        $fict->from(TAB_deposits);
        if ($this->dateFrom) {
            $fict->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`date`<='" . $this->dateTo . "'");
        }
        if (!$ac) {
            $fict->group_by("date");
        }
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $data = json_decode($res->account);
            $returnData = 0;
            foreach ($data as $dat) {
                if ($dat->id == $id) {
                    $returnData = $dat;
                }
            }
            $array[$res->date] = $returnData;
        }
        if ($ac) {
            $acReturn = ["value" => 0, "qty" => 0];
            foreach ($array as $ar) {
                $acReturn["value"] += $ar->value ? $ar->value : 0;
                $acReturn["qty"] += $ar->qty ? $ar->qty : 0;
            }
            return (object) $acReturn;
        }
        return $array;
    }

    function getCcSettllementsDetails($id, $ac = false) {
        $return = [];
        $fict = $this->db->select("account,date");
        $fict->from(TAB_settllements);
        if ($this->dateFrom) {
            $fict->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`date`<='" . $this->dateTo . "'");
        }
        if (!$ac) {
            $fict->group_by("date");
        }
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $data = json_decode($res->account);
            $returnData = 0;
            foreach ($data as $dat) {
                if ($dat->id == $id) {
                    $returnData = $dat;
                }
            }
            $array[$res->date] = $returnData;
        }
        if ($ac) {
            $acReturn = ["value" => 0,];
            foreach ($array as $ar) {
                $acReturn["value"] += $ar->value ? $ar->value : 0;
            }
            return (object) $acReturn;
        }
        return $array;
    }

    function pmtQty() {
        $reports = [];
        $reports += $this->paymentsAdded(true);
        $reports += $this->online();
        $reports += $this->nonBrm();
        $reports += $this->brm();
        $reports += $this->check();
        $reports += $this->credit();



        return $reports;
    }

    function acReports() {
        $return = [];
        $fict = $this->db->select("COUNT(*) AS paymentQty,SUM(price) AS dollerTotal,orderDate,stateID");
        $fict->from(TAB_ACorders);
        if ($this->dateFrom) {
            $fict->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`orderDate`<='" . $this->dateTo . "'");
        }
        // $fict->where(["completed" => '0']);
        $fict->group_by("stateID");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->stateID] = $res;
        }
        $return["acOrder"] = $array;
        ///        
        $acCustomers = $this->db->select("COUNT(*) AS customers,SUM(price) AS custTotal,date as orderDate,SUM(publishingCost) AS pubCost,stateID");
        $acCustomers->from(TAB_ACorders);
        if ($this->dateFrom) {
            $acCustomers->where("`date`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acCustomers->where("`date`<='" . $this->dateTo . "'");
        }
        $acCustomers->where(["completed" => '1']);
        $fict->group_by("stateID");
        $acCresults = $acCustomers->get()->result();
        $acCarray = [];
        foreach ($acCresults as $res) {
            $acCarray[$res->stateID] = $res;
        }
        $return["acCustomer"] = $acCarray;
        ///
        $fict = null;
        $fict = $this->db->select("COUNT(*) AS creditNum,SUM(price) AS creditTotal,orderDate,stateID");
        $fict->from(TAB_ACorders);
        if ($this->dateFrom) {
            $fict->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fict->where(["paymentType" => "Credit", "completed" => '0']);
        $fict->group_by("stateID");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->stateID] = $res;
        }
        $return["credit"] = $array;
        ///
        $fict = null;
        $fict = $this->db->select("COUNT(*) AS checkNum,SUM(price) AS checkTotal,orderDate,stateID");
        $fict->from(TAB_ACorders);
        if ($this->dateFrom) {
            $fict->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fict->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $fict->where(["paymentType" => "Check", "completed" => '0']);
        $fict->group_by("stateID");
        $results = $fict->get()->result();
        $array = [];
        foreach ($results as $res) {
            $array[$res->stateID] = $res;
        }
        $return["check"] = $array;
        ///deposits


        $return["deposits"] = $this->getBankDepositDetails(3, true);
        $return["settllements"] = $this->getCcSettllementsDetails(7, true);
        $return["Refund"] = $this->acRefunds("Refund");
        $return["ChargeBack"] = $this->acRefunds("ChargeBack");
        $return["StopPayment"] = $this->acRefunds("StopPayment");
        $return["brmCharged"] = $this->brmCharged();
        $return += $this->nonBrm();
        $return += $this->mailngs();
        return $return;
    }

    function acRefunds($refundType = "Refund") {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_ACrefunds);
        $stateQuery->where(["refundType" => $refundType]);
        if ($this->dateFrom) {
            $stateQuery->where("`refundDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`refundDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        $temp = [];

        foreach ($results as $result) {
            $temp[$result->stateID]["num"] = $result->num;
            $temp[$result->stateID]["amount"] = $result->amountUSD;
        }


        $tempValue = ["num" => 0, "amount" => 0];
        foreach (getACState() as $id => $state) {
            $return[$id] = (object) (
                    isset($temp[$id]) ? $temp[$id] :
                    $tempValue);
        }
        return $return;
    }

    function orderCount($customer = 0) {
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $stateQuery->from(TAB_orders);
        $stateQuery->where("shipped", $customer);
        $stateQuery->where("refund", '0');
        if ($this->dateFrom) {
            $stateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $acstateQuery->from(TAB_ACorders);
        if ($this->dateFrom) {
            $acstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $acstateQuery->where("refund", '0');
        $acstateQuery->where(["completed" => $customer]);
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return["current"][$acState])) {
                $return["current"][$acState] = [];
                $return["currentAmount"][$acState] = [];
            }
            $return["current"][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
            $return["currentAmount"][$acState] = number_format(isset($acTemp[$State]) ? (isset($acTemp[$State]->amountUSD) ? $acTemp[$State]->amountUSD : 0) : 0, 2);
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID,SUM(price) AS amountUSD");
        $pdstateQuery->from(TAB_proDocOrders);
        if ($this->dateFrom) {
            $pdstateQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }
        $pdstateQuery->where("refund", '0');
        $pdstateQuery->where("shipped", $customer);
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return["current"][$state])) {
                $return["current"][$state] = [];
                $return["currentAmount"][$state] = [];
            }
            $return["current"][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
            $return["currentAmount"][$state] = number_format(isset($pdTemp[$state]) ? (isset($pdTemp[$state]->amountUSD) ? $pdTemp[$state]->amountUSD : 0) : 0, 2);
        }

        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num,SUM(price) AS amountUSD");
        $fictitiousQuery->from(TAB_fictitiousOrders);

        $fictitiousQuery->where(["completed" => $customer]);
        $fictitiousQuery->where("refund", '0');
        if ($this->dateFrom) {
            $fictitiousQuery->where("`orderDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`orderDate`<='" . $this->dateTo . "'");
        }

        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * * * * * * * * *  fictious * * * * * * * * * */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return["current"][$id])) {
                $return["current"][$id] = [];
                $return["currentAmount"][$id] = [];
            }
            $return["current"][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
            $return["currentAmount"][$id] = number_format(isset($temp[$id]) ? (isset($temp[$id]->amountUSD) ? $temp[$id]->amountUSD : 0) : 0, 2);
        }
        return $return;
    }

    function ordersReports() {
        $return["orders"] = $this->orderCount(0);
        $return["customers"] = $this->orderCount(1);
        return $return;
    }

    function proDocReports() {
        $return = [];
        $return = $this->paymentsAdded();
        $return["brmCharged"] = $this->brmCharged();
        $return += $this->nonBrm();
        $return += $this->online();
        $return += $this->check();
        $return += $this->credit();
        $return["customers"] = $this->orderCount(1);
        $return += $this->mailngs();
        $return += $this->refund();
        $return += $this->chargeBack();
        $return += $this->stopPayment();
        $return["deposits"] = $this->getBankDepositDetails(10);
        $return["settllements"] = $this->getCcSettllementsDetails(1);
        return $return;
    }

    function prospectByPresort($order = 0) {
        $rtn = "sent";
        if ($order) {
            $rtn = "paymentAdded";
        }
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num,stateID");
        $stateQuery->from(TAB_prospects);

        if ($this->dateFrom) {
            $stateQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        if ($order) {
            $stateQuery->where(["ordered" => $order]);
        }
        $stateQuery->group_by("stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num,stateID");
        $acstateQuery->from(TAB_acProspects);
        if ($this->dateFrom) {
            $acstateQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        if ($order) {
            $acstateQuery->where(["ordered" => $order]);
        }
        $acstateQuery->group_by("stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return[$rtn][$acState])) {
                $return[$rtn][$acState] = [];
            }
            $return[$rtn][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num,stateID");
        $pdstateQuery->from(TAB_proDocProspects);
        if ($this->dateFrom) {
            $pdstateQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }

        if ($order) {
            $pdstateQuery->where(["ordered" => $order]);
        }
        $pdstateQuery->group_by("stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return[$rtn][$state])) {
                $return[$rtn][$state] = [];
            }
            $return[$rtn][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
        }
        /*         * **********fict*************** */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num");
        $fictitiousQuery->from(TAB_fictitious);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        if ($order) {
            $fictitiousQuery->where(["ordered" => $order]);
        }
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * ************************* */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return[$rtn][$id])) {
                $return[$rtn][$id] = [];
            }
            $return[$rtn][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
        }

        return $return;
    }

    /**
     * Refund StopPayment  ChargeBack
     * @param type $ref
     * @return type
     */
    function prospectByPresortRefunds($ref = "Refund") {
        $rtn = $ref;
        $return = [];
        $stateQuery = $this->db->select("COUNT(*) AS num," . TAB_prospects . ".stateID");
        $stateQuery->from(TAB_prospects);

        if ($this->dateFrom) {
            $stateQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $stateQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        $stateQuery->join(TAB_refunds, TAB_prospects . ".contactID =" . TAB_refunds . ".contactID", "INNER");
        $stateQuery->where(["refundType" => $ref]);
        $stateQuery->group_by(TAB_prospects . ".stateID");
        $results = $stateQuery->get()->result();
        /*         * ********************** AC ************************* */
        $acstateQuery = $this->db->select("COUNT(*) AS num," . TAB_acProspects . ".stateID");
        $acstateQuery->from(TAB_acProspects);
        if ($this->dateFrom) {
            $acstateQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $acstateQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        $acstateQuery->join(TAB_ACrefunds, TAB_acProspects . ".contactID =" . TAB_ACrefunds . ".contactID", "INNER");
        $acstateQuery->where(["refundType" => $ref]);
        $acstateQuery->group_by(TAB_acProspects . ".stateID");
        $acresults = $acstateQuery->get()->result();
        $acTemp = [];
        foreach ($acresults as $acresult) {
            $acTemp[$acresult->stateID] = $acresult;
        }
        foreach ($this->getAcStateList() as $State => $acState) {
            if (!isset($return[$rtn][$acState])) {
                $return[$rtn][$acState] = [];
            }
            $return[$rtn][$acState] = isset($acTemp[$State]) ? (isset($acTemp[$State]->num) ? $acTemp[$State]->num : 0) : 0;
        }
        /*         * ********************** proDoc ************************* */
        $pdstateQuery = $this->db->select("COUNT(*) AS num," . TAB_proDocProspects . ".stateID");
        $pdstateQuery->from(TAB_proDocProspects);
        if ($this->dateFrom) {
            $pdstateQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $pdstateQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        $pdstateQuery->join(TAB_proDocRefunds, TAB_proDocProspects . ".contactID =" . TAB_proDocRefunds . ".contactID", "INNER");
        $pdstateQuery->where(["refundType" => $ref]);
        $pdstateQuery->group_by(TAB_proDocProspects . ".stateID");
        $pdresults = $pdstateQuery->get()->result();
        $pdTemp = [];
        foreach ($pdresults as $pdresult) {
            $pdTemp[$pdresult->stateID] = $pdresult;
        }
        foreach (getProDocState() as $state => $St) {
            if (!isset($return[$rtn][$state])) {
                $return[$rtn][$state] = [];
            }
            $return[$rtn][$state] = isset($pdTemp[$state]) ? (isset($pdTemp[$state]->num) ? $pdTemp[$state]->num : 0) : 0;
        }
        /*         * **********fict*************** */
        $fictitiousQuery = $this->db->select("COUNT(*) AS num");
        $fictitiousQuery->from(TAB_fictitious);
        if ($this->dateFrom) {
            $fictitiousQuery->where("`preSortDate`>='" . $this->dateFrom . "'");
        }
        if ($this->dateTo) {
            $fictitiousQuery->where("`preSortDate`<='" . $this->dateTo . "'");
        }
        $fictitiousQuery->join(TAB_fictitiousRefunds, TAB_fictitious . ".contactID =" . TAB_fictitiousRefunds . ".contactID", "INNER");
        $fictitiousQuery->where(["refundType" => $ref]);
        $fictitiousResults = $fictitiousQuery->get()->first_row();
        /*         * ************************* */
        $temp = [];
        foreach ($results as $result) {
            $temp[$result->stateID] = $result;
        }
        $temp["FIC"] = $fictitiousResults;
        foreach ($this->states as $id => $state) {
            if (!isset($return[$rtn][$id])) {
                $return[$rtn][$id] = [];
            }
            $return[$rtn][$id] = isset($temp[$id]) ? (isset($temp[$id]->num) ? $temp[$id]->num : 0) : 0;
        }

        return $return;
    }

    function conversionRateReports() {
        $return = [];
        $return = $this->prospectByPresort();
        $return += $this->prospectByPresort(1);
        $return += $this->prospectByPresortRefunds("Refund");
        $return += $this->prospectByPresortRefunds("StopPayment");
        $return += $this->prospectByPresortRefunds("ChargeBack");

        return $return;
    }

}
