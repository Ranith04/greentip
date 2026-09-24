<?php
/**
 * Created by PhpStorm.
 * User: abhishek
 * Date: 11/2/15
 * Time: 5:47 PM
 */

class Default_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->db->query("SET time_zone='+00:00'");
    }
}

