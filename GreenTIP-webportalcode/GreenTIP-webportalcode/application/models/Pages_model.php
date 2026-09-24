<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: abhishek
 * Date: 6/5/15
 * Time: 3:28 PM
 */
class Pages_model extends CI_Model
{
    public $table = 'b_pages';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'b_pages';
    }

    public function record_count($condition = '')
    {
        if (!empty($condition)) {
            SetCondition($condition);
        }

        return $this->db->count_all_results($this->table);
    }

    public function get_pages($start, $limit, $condition = array())
    {
        if (!empty($condition)) {
            SetCondition($condition);
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
        }
        return $data;
    }

    function _selectById($condition = array())
    {
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->select('*', false);
        $resSQL = $this->db->get($this->table);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
            return $result;
        } else {
            return false;
        }
    }
}