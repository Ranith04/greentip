<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PageNotFound extends MX_Controller
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
        $this->viewData['title'] = 'Whoops! Page Not Exist';
        $this->viewData['data'] = array();
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