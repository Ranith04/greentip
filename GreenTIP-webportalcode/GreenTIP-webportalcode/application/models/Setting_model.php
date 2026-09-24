<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends MY_Model
{
    public $table = 'b_settings';
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct()
    {
        $this->has_many['address'] = array('Address_model', 'b_user_id', 'id');
        parent::__construct();
    }
    public function record_count($condition='') {
        if(!empty($condition)){
            SetCondition($condition);
        }

        return $this->db->count_all_results($this->table);
    }
    public function get_Settings($start, $limit, $condition = array()) {
        if(!empty($condition)){
            SetCondition($condition);
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data=$query->result_array();
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
            $result=$resSQL->row_array();
            return $result;
        } else {
            return false;
        }
    }

}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */