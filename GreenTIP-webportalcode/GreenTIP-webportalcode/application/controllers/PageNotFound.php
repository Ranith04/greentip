<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PageNotFound extends CI_Controller
{
    var $data = array();
    public $colPrefix = '';
    public $viewData = array();
    /*
     *  Home Construct Function
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {

        set_status_header(404);
        if ($this->input->is_ajax_request()) {
            die;
        }
        $this->viewData['title'] = 'Whoops! Page Not Exist';
        $this->load->view('PageNotFound', $this->viewData);
    }

    function error_404()
    {
        $this->output->set_status_header('404');
        echo "404 - not found";
    }

}

/* End of file index.php */
/* Location: ./application/controllers/index.php */