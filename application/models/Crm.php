<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 9, 2018 , 9:35:21 AM
 */
class Crm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getCountProDocProspects($contactID, $State) {
        $query = "SELECT COUNT(*) AS count FROM `" . TAB_proDocProspects . "` WHERE `contactID`='$contactID' AND `stateID`='$State'";
        $result = $this->db->query($query)->result();
        return $result[0]->count;
    }
function getProDocCountOrders($contactID, $State) {
        $query = "SELECT COUNT(*) AS count FROM `" . TAB_orders . "` WHERE `contactID`='$contactID' AND `stateID`='$State'";
        $result = $this->db->query($query)->result();
        return $result[0]->count;
    }
    function getCountProspects($contactID, $State) {
        $query = "SELECT COUNT(*) AS count FROM `" . TAB_prospects . "` WHERE `contactID`='$contactID' AND `stateID`='$State'";
        $result = $this->db->query($query)->result();
        return $result[0]->count;
    }

    function getCountOrders($contactID, $State) {
        $query = "SELECT COUNT(*) AS count FROM `" . TAB_orders . "` WHERE `contactID`='$contactID' AND `stateID`='$State'";
        $result = $this->db->query($query)->result();
        return $result[0]->count;
    }

    function getAcCountProspects($contactID, $State) {
        $query = "SELECT COUNT(*) AS count FROM `" . TAB_acProspects . "` WHERE `contactID`='$contactID' AND `stateID`='$State'";
        $result = $this->db->query($query)->result();
        return $result[0]->count;
    }

    function getAcCountOrders($contactID, $State) {
        $query = "SELECT COUNT(*) AS count FROM `" . TAB_ACorders . "` WHERE `contactID`='$contactID' AND `stateID`='$State'";
        $result = $this->db->query($query)->result();
        return $result[0]->count;
    }

}
