<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber_model extends MY_Model
{
    public $table = 'b_subscriber';
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct()
    {
        parent::__construct();
    }
    public function record_count($tableName,$condition)
    {
        SetCondition($condition,false);
        if($tableName!='' || !empty( $tableName )){
            $this->table = $tableName;
        }
        return $this->db->count_all_results( $this->table );
    }
    public function get_records( $tableName, $start, $limit, $condition = array())
    {
        SetCondition($condition);
        if($tableName!='' || !empty( $tableName )){
           $this->table = $tableName;
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
       // echo $this->db->last_query();exit;
        return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
    }
}