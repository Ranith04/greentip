<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller
{

    public $viewData = array();
    public $loggedInAdmin = array();

    public function __construct()
    {
        parent::__construct();
        isLoggedIn($type = 'admin');
        $this->loggedInAdmin = getSessionUserData('auth_admin_data');
        $this->load->model('common_model');

    }

    public function index()
    {
        $TotalUsers = $this->common_model->total_count('b_users', 'id', array('status!=' => '3'));
        $TotalCategories = $this->common_model->total_count('b_categories', 'id', array('status!=' => '3'));
        $TotalIndustrialCategories = $this->common_model->total_count('b_industrial_categories', 'id', array('status!=' => '3'));
        $TotalIndustrialUsers = $this->common_model->total_count('b_industrial_users', 'id', array('status!=' => '3'));
        $TotalSliders = $this->common_model->total_count('b_sliders', 'id', array('status!=' => '3'));
        $TotalFaq = $this->common_model->total_count('b_faqs', 'id', array('status!=' => '3'));
        $TotalContacts = $this->common_model->total_count('b_contact_forms', 'id', array('status!=' => '3'));

        $users = $this->common_model->_select('b_users', $column = '*', array('status !=' => '3'), 'id', $sort = 'DESC', 0, 5);

        $this->viewData['title'] = 'Admin Panel | Dashboard';

        $this->viewData['users'] = $users;
        $this->viewData['TotalUsers'] = $TotalUsers;
        $this->viewData['TotalCategories'] = $TotalCategories;
        $this->viewData['TotalIndustrialCategories'] = $TotalIndustrialCategories;
        $this->viewData['TotalIndustrialUsers'] = $TotalIndustrialUsers;
        $this->viewData['TotalFaq'] = $TotalFaq;
        $this->viewData['TotalSliders'] = $TotalSliders;

        $this->viewData['TotalContacts'] = $TotalContacts;
        $this->viewData['data'] = array();
        $this->load->view('Dashboard', $this->viewData);
    }
}
