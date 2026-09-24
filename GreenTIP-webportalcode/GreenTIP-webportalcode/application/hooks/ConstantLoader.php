<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
class ConstantLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->load->model('Common_model','CommonModel');
        $rawData = $ci->CommonModel->_select('b_settings');
        if(!empty($rawData)){
            foreach($rawData AS $row){
               define( (string)'ADMIN_'.strtoupper($row['option_name']), $row['option_value']);
            }
        }
    }
}

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */