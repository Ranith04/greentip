<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contact_model extends MY_Model
{
    public $table = 'b_contact_forms';
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct()
    {
        parent::__construct();
    }

    public function record_count($condition)
    {
        SetCondition($condition);
        return $this->db->count_all_results($this->table);
    }

    public function get_contacts($start, $limit, $condition = array())
    {
        SetCondition($condition);
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data=$query->result_array();
        }
        return $data;
    }

    function getReply($contact_id)
    {
        $query = $this->db->get_where('b_contact_replys', array('contact_id' => $contact_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $replies[] = $row;
            }
            return $replies;
        } else {
            return false;
        }
    }

    function insertReply($data)
    {
        $this->db->insert('b_contact_replys', $data);
        return true;
    }

    function updateContact($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('b_contact_forms', $this->db->escape_str($data));
        return true;
    }

}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */