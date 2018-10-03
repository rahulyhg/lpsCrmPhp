<?php

/**
 * Author S Brinta
 * Email: <brrinta@gmail.com>
 * Web: http://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta 
 * Created on : Apr 07, 2017, 11:14:10 AM
 */
class Mdb extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @return int
     */
    function countData($table, $where = 0) {
        $this->db->select('COUNT(*) as count');
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->first_row()->count;
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $limit
     * @param type $select
     * @return boolean
     */
    function getDataArray($table, $where = 0, $order = 0, $limit = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        if ($limit) {
            if ($query->num_rows()) {
                return $query->result_array();
            }
            return [];
        }

        if ($query->num_rows()) {
            return $query->result_array();
        }
        return [];
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $limit
     * @param type $select
     * @return boolean
     */
    function getDataLikeArray($table, $where = 0, $order = 0, $limit = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            $this->db->like($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        if ($limit) {
            if ($query->num_rows()) {
                return $query->result_array();
            }
            return [];
        }

        if ($query->num_rows()) {
            return $query->result_array();
        }
        return [];
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $limit
     * @param type $select
     * @return boolean
     */
    function getData($table, $where = 0, $order = 0, $limit = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        if ($limit) {
            if ($query->num_rows()) {
                return $query->result();
            }
            return [];
        }
        if ($query->num_rows()) {
            return $query->result();
        }
        return [];
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $limit
     * @param type $select
     * @return boolean
     */
    function getDataLike($table, $where = 0, $order = 0, $limit = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            //foreach ($where as $key => $sort) {
            $this->db->like($where);
            //}
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        if ($limit) {
            if ($query->num_rows()) {
                return $query->result();
            }
            return [];
        }
        if ($query->num_rows()) {
            return $query->result();
        }
        return [];
    }

    /**
     * 
     * @param type $table
     * @param type $like
     * @param type $where
     * @param type $order
     * @param type $limit
     * @param type $select
     * @return boolean
     */
    function getDataLikeWhere($table, $like = 0, $where = 0, $order = 0, $limit = 0, $select = 0, $offSet = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($like) {
            $this->db->like($like);
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }
        if ($offSet) {
            $this->db->limit($limit, $offSet);
        } else {
            if ($limit) {
                $this->db->limit($limit);
            }
        }
        $query = $this->db->get();
        if ($limit) {
            if ($query->num_rows()) {
                return $query->result();
            }
            return [];
        }
        if ($query->num_rows()) {
            return $query->result();
        }
        return [];
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $select
     * @return boolean
     */
    function getSingleData($table, $where = 0, $order = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows()) {
            return $query->row();
        }
        return false;
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $select
     * @return boolean
     */
    function getSingleDataArray($table, $where = 0, $order = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows()) {
            return $query->row_array();
        }
        return false;
    }

    /**
     * 
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $select
     * @return boolean
     */
    function getSingleDataLikeArray($table, $where = 0, $order = 0, $select = 0) {
        if ($select) {
            $this->db->select($select);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);
        if ($where) {
            $this->db->like($where);
        }
        if ($order) {
            foreach ($order as $key => $sort) {
                $this->db->order_by($key, $sort);
            }
        }

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows()) {
            return $query->row_array();
        }
        return false;
    }

    /**
     * 
     * @param type $query
     * @return array
     */
    function executeCustomArray($query) {
        $quy = $this->db->query($query);
        return $quy->result_array();
    }

    /**
     * 
     * @param type $query
     * @return std class
     */
    function executeCustom($query) {
        $quy = $this->db->query($query);
        return $quy->result();
    }

    /**
     * 
     * @param type $table
     * @param type $saveData
     * @return boolean
     */
    function insertData($table, $saveData) {
        if ($this->db->insert($table, $saveData)) {
            $returnID = $this->db->insert_id();
            if ($this->reportToLog) {
                $activity = "ID: " . $returnID . " saved in " . $table;
                $this->insertIntoUserLog($activity);
            }
            return $returnID;
        }
        return 0;
    }

    /**
     * 
     * @param type $table
     * @param type $saveArray
     * @return type
     */
    function insertBatchData($table, $saveArray) {
        $return = $this->db->insert_batch($table, $saveArray);
        if ($return && $this->reportToLog) {
            $activity = $return . " Data saved in " . $table;
            $this->insertIntoUserLog($activity);
        }
        return $return;
    }

    /**
     * 
     * @param type $table
     * @param type $data
     * @param type $where
     * @return boolean
     */
    function updateData($table, $data, $where) {
        $return = $this->db->update($table, $data, $where);
        if ($return && $this->reportToLog) {
            $activity = "Data Edited in " . $table . "<br>Where:<br>";
            foreach ($where as $k => $val) {
                $activity .= $k . " = " . $val . "<br>";
            }
            $this->insertIntoUserLog($activity);
        }
        return $return;
    }

    /**
     * @param type $table
     * @param type $where
     * @return boolean
     */
    function removeData($table, $where) {
        $return = $this->db->delete($table, $where);
        if ($return && $this->reportToLog) {
            $activity = "Data removed from " . $table . "<br>Where:<br>";
            foreach ($where as $k => $val) {
                $activity .= $k . " = " . $val . "<br>";
            }
            $this->insertIntoUserLog($activity);
        }
        return $return;
    }

    private $reportToLog = true;
    private $userLog = 'userLog';

    private function insertIntoUserLog($activity) {
        $saveData = ["activity" => $activity, "user" => isset($_SESSION["user"]) ? $_SESSION["user"]->id : "N/A"];
        $this->db->insert($this->userLog, $saveData);
    }

}
