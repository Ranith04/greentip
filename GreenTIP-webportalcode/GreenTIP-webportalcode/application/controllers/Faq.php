<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller
{
    var $perPage = '10';
    var $segment = '2';
    public $viewData = array();
    public $loggedInUser = array();
    public $UserDetail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    public function index()
    {   try
        {
            tryLogPrinter("[info]: Faq()", $params);
        $segment = '2';
        $start = validateURI($segment) != '' ? validateURI($segment) : '0';
        $result = $this->common_model->getNewList('b_faqs', $start, $this->perPage, '*', ['status' => '1']);
        $listings = [];
        $pagination = '';
        if (isset($result['total_rows']) && $result['total_rows'] > 0) {
            $listings = $result['results'];
            $pagination = createPagination('faq/', $result['total_rows'], $this->perPage, $this->segment);
        }
        $this->viewData['pagination'] = $pagination;
        $this->viewData['dbdata'] = $listings;
        $this->viewData['title'] = 'GreenTip | Faq';
        $this->load->view('FAQ', $this->viewData);

        }//try
        catch(Exception $e) 
        {  
            log_message('[error]: Faq()', "\n Exception Caught", $e->getMessage());
        }//catch
    }
}