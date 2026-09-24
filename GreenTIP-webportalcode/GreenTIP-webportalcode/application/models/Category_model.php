<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends MY_Model
{
    public $table = 'b_categories';
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct()
    {
        $this->has_many['address'] = array('Address_model', 'tbl_user_id', 'id');
        parent::__construct();
    }
    public function record_count($condition) {
        SetCondition($condition);
        return $this->db->count_all_results($this->table);
    }
    public function get_categories($start, $limit, $condition = array()) {
        SetCondition($condition);
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }
    function _selectById( $condition = array())
    {
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->select('*', false);
        $resSQL = $this->db->get($this->table);
        if ($resSQL->num_rows() > 0) {
            $result = array();
            foreach ($resSQL->result_array() as $row) {
                $result = $row;
            }
            return $result;
        } else {
            return false;
        }
    }

}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */