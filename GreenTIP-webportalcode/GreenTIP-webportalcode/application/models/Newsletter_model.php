<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter_model extends MY_Model
{
    public $table = 'b_newsletters';
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct()
    {
        parent::__construct();
    }
    public function record_count($condition)
    {
        SetCondition($condition,false);
        return $this->db->count_all_results($this->table);
    }
    public function get_newsletters($start, $limit, $condition = array())
    {
        SetCondition($condition);
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
    }
}