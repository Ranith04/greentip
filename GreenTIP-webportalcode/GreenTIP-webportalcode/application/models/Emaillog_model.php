<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Emaillog_model extends CI_Model
{
    public $table = 'b_bulk_email_log';  // cutomer table for login credentials
    public $primary_key = 'id'; // you MUST mention the primary key
    /**
     * Update a user, password will be hashed
     *
     * @param int id
     * @param array user
     * @return int id
     */
  

    /**
     * Retrieve a user
     *
     * @param string where
     * @param int value
     * @param string identification field
     */
    public function get($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }

        $user = $this->db->where($where, $value)->get($this->table)->row_array();
        return $user;
    }

    /**
     * Check if a user exists
     *
     * @param string where
     * @param int value
     * @param string identification field
     */

    public function exists($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }

        return $this->db->where($where, $value)->count_all_results($this->table);
    }



  
    function _update($table, $data, $condition = array())
    {
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        if ($this->db->update($table, $data)) {
            return true;
        } else {
            return false;
        }
    }


   

     // public function record_count($tableName,$condition)
     //    {
     //        SetCondition($condition,false);
     //        if($tableName!='' || !empty( $tableName )){
     //            $this->table = $tableName;
     //        }
     //        return $this->db->count_all_results( $this->table );
     //    }
     //    public function get_records( $tableName, $start, $limit, $condition = array())
     //    {
     //        SetCondition($condition);
     //        if($tableName!='' || !empty( $tableName )){
     //           $this->table = $tableName;
     //        }
     //        $this->db->limit($limit, $start);
     //        $query = $this->db->get($this->table);
     //       // echo $this->db->last_query();exit;
     //        return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
     //    }
    public function record_count($tableName,$condition)
        {
           // echo "hi";//
            //print_r($condition);die;
            //SetCondition($condition,false);
           // $this->db->count("*");
            if (count($condition) > 0 AND $condition != "") {
            $this->db->where('date<=', $condition['end_date']);
            $this->db->where('date>=', $condition['start_date']);
        }
            if($tableName!='' || !empty( $tableName )){
                $this->table = $tableName;
            }

            return $this->db->count_all_results( $this->table );
           
        }
        public function get_records( $tableName, $start, $limit, $condition = array())
        {   
           // print_r($condition);die;
           // SetCondition($condition);
            if($tableName!='' || !empty( $tableName )){
               $this->table = $tableName;
            }
            if (count($condition) > 0 AND $condition != "") {
               $this->db->where('date<=', $condition['end_date']);
            $this->db->where('date>=', $condition['start_date']);
            }
            
            $this->db->limit($limit, $start);
           $this->db->order_by('id', 'DESC');
            $query = $this->db->get($this->table);
           // echo $this->db->last_query();exit;
            return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
        }

    
    

}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */