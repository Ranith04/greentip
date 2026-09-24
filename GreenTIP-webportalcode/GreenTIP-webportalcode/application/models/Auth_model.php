<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * This is the autologin model used by the Authentication library
 * It handles interaction with the database to store autologin keys
 */
class Auth_model extends CI_Model {

    // database table name
    var $table = 'b_auto_login';
    var $expire = 5184000;

    /**
     * Get the settings from config
     */
    public function __construct() {
        $this->config->load('auth');
        $this->table = $this->config->item('autologin_table');
        $this->expire = $this->config->item('autologin_expire');
    }

    /**
     * Get the private key for a specific user and series
     */
    public function get($user, $series, $type) {
        $this->db->where('user', $user);
        $this->db->where('series', $series);
        $this->db->where('type', $type);
        $row = $this->db->get($this->table)->row();
        return $row ? $row->key : FALSE;
    }

    /**
     * Extend a user's current series with a new key
     */
    public function update($user, $series, $private, $type) {
        $this->db->where('user', $user);
        $this->db->where('series', $series);
        $this->db->where('type', $type);
        return $this->db->update($this->table, array('key' => $private, 'created_on' => set_local_to_gmt()));
    }

    /**
     * Start a new serie for a user
     */
    public function insert($user, $series, $private, $type) {
        $time = set_local_to_gmt();
        return $this->db->insert($this->table, array('user' => $user, 'series' => $series, 'key' => $private, 'type' => $type, 'created_on' => $time));
    }

    /**
     * Dlete a user's series
     */
    public function delete($user, $series, $type) {
        $this->db->where('user', $user);
        $this->db->where('series', $series);
        $this->db->where('series', $type);

        return $this->db->delete($this->table);
    }

    /**
     * Remove all expired keys
     */

    public function purge() {
        $this->db->where('UNIX_TIMESTAMP(created_on) <', strtotime(set_local_to_gmt()) - $this->expire)->delete($this->table);
    }

    function confirmIPAddress($value, $type = '2')
    {
        $q = "SELECT attempts, (CASE when last_login is not NULL and DATE_ADD(last_login, INTERVAL 30 MINUTE)> '".set_local_to_gmt()."' then 1 else 0 end) as Denied FROM b_login_attempts WHERE ip_address = '" . $value . "' AND user_type = '" . $type . "'";
        $resSQL = $this->db->query($q);
        $result = false;
        if ($resSQL->num_rows() > 0) {
            $dataRaw = $resSQL->result_array();
            $data = $dataRaw[0];
            if ($data["attempts"] >= '10') {
                if ($data["Denied"] == 1) {
                    $result = true;
                } else {
                    $this->clearLoginAttempts($value);
                    $result = false;
                }
            }
        }
        return $result;
    }

    function addLoginAttempt($value, $type = '2')
    {
        $q = "SELECT * FROM b_login_attempts WHERE ip_address = '".$value."' AND user_type = '".$type."'";
        $resSQL = $this->db->query($q);
        /*printArray($this->db->last_query(),1);*/
        if ($resSQL->num_rows() > 0) {
            $dataRaw = $resSQL->result_array();
            $data = $dataRaw[0];
            $attempts = $data["attempts"] + 1;
            if ($attempts == '10') {
                $this->db->set('last_login', "'".set_local_to_gmt()."'", FALSE);
                $this->db->where(array('ip_address' => $value, 'user_type' => $type));
                $this->db->update('b_login_attempts', array('attempts' => $attempts));
            } else {
                $this->db->where(array('ip_address' => $value, 'user_type' => $type));
                $this->db->update('b_login_attempts', array('attempts' => $attempts));
            }
        } else {
            $this->db->set('last_login', '\''.set_local_to_gmt().'\'', FALSE);
            $this->db->insert('b_login_attempts', array('attempts' => 1, 'ip_address' => $value, 'user_type' => $type));
        }
    }

    function clearLoginAttempts($value, $type = '2')
    {
        $this->db->where(array('ip_address' => $value, 'user_type' => $type));
        $this->db->update('b_login_attempts', array('attempts' => 0));
        return true;
    }
}