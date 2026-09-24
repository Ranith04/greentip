<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public $table = 'b_users';

    /**
     * Update a user, password will be hashed
     *
     * @param int id
     * @param array user
     * @return int id
     */
    public function update($id, $user)
    {
        // prevent overwriting with a blank password
        if (isset($user['password']) && $user['password']) {
            $user['password'] = $this->hash($user['password']);
        } else {
            unset($user['password']);
        }

        $this->db->where('id', $id)->update($this->table, $user);
        return $id;
    }

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

    /**
     * Password hashing function
     *
     * @param string $password
     */
    public function hash($password)
    {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));

        // hash password
        return $this->passwordhash->HashPassword($password);
    }

    /**
     * Compare user input password to stored hash
     *
     * @param string $password
     * @param string $stored_hash
     */
    public function check_password($password, $stored_hash)
    {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
        // check password
        return $this->passwordhash->CheckPassword($password, $stored_hash);
    }


}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */