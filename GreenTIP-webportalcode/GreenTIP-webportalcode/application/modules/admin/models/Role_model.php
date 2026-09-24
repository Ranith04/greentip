<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends CI_Model
{
    public $table = 'b_permissions';

    /**
     * Plan detail with project information
     * */

    public function insert_data($table, $data)
    {
        $this->db->trans_begin();
        foreach ($data AS $row) {
            $this->db->insert($table, $row);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return = false;
        } else {
            $this->db->trans_commit();
            $return = true;
        }
        return $return;
    }

    public function pre_selected_perm($condition)
    {
        $assigned = array();
        $return = array();
        $this->db->where($condition);
        $resSQL = $this->db->get($this->table);
        if ($resSQL->num_rows() > 0) {
            $raw = $resSQL->result_array();
            foreach ($raw as $key => $value) {
                $moduleId = $value['b_module_id'];
                foreach ($value AS $key1 => $value1) {
                    if (in_array($key1, array('has_read_permission', 'has_create_permission', 'has_update_permission', 'has_delete_permission'))) {
                        $keyArray = explode('_', $key1);
                        $data = array();
                        $assigned[$moduleId][$keyArray[1]] = $value[$key1];
                    }
                }
            }
            $return = $assigned;
        }
        return $return;
    }


    public function get_perm_id($param)
    {
        $this->db->where('module_name', $param);
        $query = $this->db->get('b_modules');
        if ($query->num_rows() == 0) {
            return FALSE;
        }
        $row = $query->row();
        return $row->id;
    }


    /**
     * Is Group allowed
     * Check if group is allowed to do specified action, admin always allowed
     * @param int $perm_par Permission id or name to check
     * @param int|string|bool $group_par Group id or name to check, or if FALSE checks all user groups
     * @return bool
     */
    public function is_group_allowed($perm_par, $type = 'read', $group_par = FALSE)
    {
        $perm_id = $this->get_perm_id($perm_par);
        // if group par is given
        if ($group_par != FALSE) {
            $this->db->where(array('b_module_id' => $perm_id, 'b_admin_users_type_id' => $group_par));
            $query = $this->db->get('b_permissions');
            if ($query->num_rows() > 0) {
                $raw = $query->result_array();
                if ($raw[0]['has_' . $type . '_permission'] == '0') {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        }
        // if group par is not given
        // checks current user's groups
        else {
            // if public is allowed or he is admin
            $userData = getSessionUserData('auth_admin_data');
            if ($this->is_group_allowed($perm_par, $type, $userData['b_admin_users_type_id'])) {
                return TRUE;
            }
            return FALSE;
        }
    }


}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */