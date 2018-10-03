<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 22, 2018 , 6:46:29 PM
 */
class Prospects extends CI_Model {

    private $table;
    private $columns = [];
    private $limit = 25;
    private $where = [];
    public $query = "";

    public function __construct() {
        parent::__construct();
    }

    public function select($columns = '*') {
        if ($columns !== '*') {
            foreach ($this->explode(',', $columns) as $val) {
                $column = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
                $this->columns[] = $column;
                $this->select[$column] = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
            }
        }
        $this->db->select($columns);
        return $this;
    }

    function limit($limit = 25, $offset = 0) {
        $this->limit = [
            $limit,
            $offset
        ];
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this;
    }

    function join($table, $fk, $type = NULL) {
        $this->joins[] = array($table, $fk, $type);
        $this->db->join($table, $fk, $type);
        return $this;
    }

    function where($where) {
        $this->where = $where;
        $this->db->where($where);
        return $this;
    }

    function like($like) {
        $this->db->like($like);
        return $this;
    }

    function from($table) {
        $this->table = $table;
        $this->db->from($table);
        return $this;
    }

    function generate() {
        $results = $this->db->get();
        $this->query = $this->db->last_query();
        return $results->result();
    }

    function displayRecordsCount() {

        $query = $this->query;
        $qry = "";
        if ($this->limit) {
            $length = $this->limit[0];
            $offset = $this->limit[1];
            if ($offset) {
                $qry = preg_replace("/LIMIT\s$offset,\s$length/", "", $query);
                return $this->db->query($qry)->num_rows();
            } else {
                $qry = preg_replace("/LIMIT\s$length/", "", $query);
                return $this->db->query($qry)->num_rows();
            }
        } else {
            return $this->db->query($query)->num_rows();
        }
    }

    private function explode($delimiter, $str, $open = '(', $close = ')') {
        $retval = array();
        $hold = array();
        $balance = 0;
        $parts = explode($delimiter, $str);

        foreach ($parts as $part) {
            $hold[] = $part;
            $balance += $this->balanceChars($part, $open, $close);

            if ($balance < 1) {
                $retval[] = implode($delimiter, $hold);
                $hold = array();
                $balance = 0;
            }
        }

        if (count($hold) > 0)
            $retval[] = implode($delimiter, $hold);

        return $retval;
    }

    private function balanceChars($str, $open, $close) {
        $openCount = substr_count($str, $open);
        $closeCount = substr_count($str, $close);
        $retval = $openCount - $closeCount;
        return $retval;
    }

}
