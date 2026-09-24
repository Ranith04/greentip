<?php
/**
 * @name		CodeIgniter Secure Authentication Library
 * @author		Jens Segers
 * @link		http://www.jenssegers.be
 * @license		MIT License Copyright (c) 2012 Jens Segers
 *
 */
if (!defined("BASEPATH"))
    exit("No direct script access allowed");
class Auth {

    // default values
    private $cookie_name = 'autologin';
    private $cookie_encrypt = TRUE;
    private $autologin_expire = 5184000;
    private $hash_algorithm = 'sha256';

    private $ci;

    /**
     * Constructor, loads dependencies, initializes the library
     * and detects the autologin cookie
     */
    public function __construct($config = array()) {
        $this->ci = &get_instance();

        // load session library
        $this->ci->load->library('session');

        // initialize from config
        if (!empty($config)) {
            $this->initialize($config);
        }

        log_message('debug', 'Authentication library initialized');

        // detect autologin
        if (!$this->ci->session->userdata('auth_user_logged_in')) {
            $this->autologin('user');
        }
        // detect autologin
        if (!$this->ci->session->userdata('auth_admin_logged_in')) {
            $this->autologin('admin');
        }
    }

    /**
     * Initialize with configuration array
     *
     * @param array $config
     */
    public function initialize($config = array()) {
        foreach ($config as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Mark a user as logged in and create autologin cookie if wanted
     *
     * @param string $id
     * @param boolean $remember
     * @return boolean
     */
    public function login($id, $user_type = 'user', $remember = FALSE) {
        if(!$this->loggedin($user_type)) {
            // mark user as logged in
            $this->ci->session->set_userdata(array('auth_'.$user_type => $id, 'auth_'.$user_type.'_logged_in' => TRUE));
            if ($remember) {
                $this->create_autologin($id, $user_type);
            }
        }
    }

    /**
     * Logout the current user, destroys the current session and autologin key
     */
    public function logout($type) {
        // mark user as logged out
        $this->ci->session->set_userdata(array('auth_'.$type => FALSE, 'auth_'.$type.'_logged_in' => FALSE));
        // remove cookie and active key
        $this->delete_autologin($type);
    }

    /**
     * Check if the current user is logged in or not
     *
     * @return boolean
     */
    public function loggedin($type) {
        return $this->ci->session->userdata('auth_'.$type.'_logged_in');
    }

    /**
     * Returns the user id of the current user when logged in
     *
     * @return int
     */
    public function userid($type) {
        return $this->loggedin($type) ? $this->ci->session->userdata('auth_'.$type) : FALSE;
    }

    /**
     * Generate a new key pair and create the autologin cookie
     *
     * @param int $id
     * @param string $series
     */
    private function create_autologin($id, $type, $series = FALSE) {
        // generate keys
        list($public, $private) = $this->generate_keys();

        $this->ci->load->model('auth_model');

        // create new series or expand current series
        if (!$series) {
            list($series) = $this->generate_keys();
            $this->ci->auth_model->insert($id, $series, $private, $type);
        } else {
            $this->ci->auth_model->update($id, $series, $private, $type);
        }

        // write public key to cookie
        $cookie = array('id' => $id, 'series' => $series, 'key' => $public, 'user_type' => $type);
        $this->write_cookie($cookie);
    }

    /**
     * Disable the current autologin key and remove the cookie
     */
    private function delete_autologin($type) {
        if ($cookie = $this->read_cookie($type)) {
            // remove current series
            $this->ci->load->model('auth_model');
            $this->ci->auth_model->delete($cookie['id'], $cookie['series'], $type);

            // delete cookie
            $this->ci->input->set_cookie(array('name' => $this->cookie_name, 'value' => '', 'expire' => ''));
        }
    }

    /**
     * Detects the autologin cookie and check public/private key pair
     *
     * @return boolean
     */
    private function autologin($type) {
        if ($cookie = $this->read_cookie($type)) {
            // remove expired keys
            $this->ci->load->model('auth_model');
            $this->ci->auth_model->purge();

            // get private key
            $private = $this->ci->auth_model->get($cookie['id'], $cookie['series'], $cookie['user_type']);

            if ($this->validate_keys($cookie['key'], $private)) {
                // mark user as logged in
                $this->ci->session->set_userdata(array('auth_'.$type => $cookie['id'], 'auth_'.$type.'_logged_in' => TRUE));

                // user has a valid key, extend current series with new key
                $this->create_autologin($cookie['id'], $type, $cookie['series']);
                return TRUE;
            } else {
                // the key was not valid, strange stuff going on
                // remove the active session to prevent theft!
                $this->delete_autologin($type);
            }
        }

        return FALSE;
    }

    /**
     * Write data to autologin cookie
     *
     * @param array $data
     */
    private function write_cookie($data = array()) {
        $data = serialize($data);

        // encrypt cookie
        if ($this->cookie_encrypt) {
            $this->ci->load->library('encrypt');
            $data = $this->ci->encrypt->encode($data);
        }

        return $this->ci->input->set_cookie(array('name' => $this->cookie_name, 'value' => $data, 'expire' => $this->autologin_expire));
    }

    /**
     * Read data from autologin cookie
     *
     * @return boolean
     */
    private function read_cookie($type) {
        $cookie = $this->ci->input->cookie($this->cookie_name, TRUE);

        if (!$cookie) {
            return FALSE;
        }

        // decrypt cookie
        if ($this->cookie_encrypt) {
            $this->ci->load->library('encrypt');
            $data = $this->ci->encrypt->decode($cookie);
        }

        $data = @unserialize($data);

        if (isset($data['id']) && isset($data['series']) && isset($data['key']) && isset($data['user_type']) && $data['user_type'] == $type) {
            return $data;
        }

        return FALSE;
    }

    /**
     * Generate public/private key pair
     *
     * @return array
     */
    private function generate_keys() {
        $public = hash($this->hash_algorithm, uniqid(rand()));
        $private = hash_hmac($this->hash_algorithm, $public, $this->ci->config->item('encryption_key'));

        return array($public, $private);
    }

    /**
     * Validate public/private key pair
     *
     * @param string $public
     * @param string $private
     * @return boolean
     */
    private function validate_keys($public, $private) {
        $check = hash_hmac($this->hash_algorithm, $public, $this->ci->config->item('encryption_key'));
        return $check == $private;
    }
}