<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all_categories_get()
    {
        $categories = $this->common_model->_select('b_categories', 'id,cat_name as name', ['status' => '1'], 'id', 'ASC');
        $this->set_response([
            'status' => true,
            'data' => $categories
        ], REST_Controller::HTTP_OK);
    }

    public function all_faqs_get()
    {
        $faqs = $this->common_model->_select('b_faqs', 'question,answer', ['status' => '1'], 'id', 'ASC');
        if (!empty($faqs)) {
            foreach ($faqs as $key => $value) {
                $faqs[$key]['answer'] = html_entity_decode(htmlentities(strip_tags($value['answer'])));
            }
        }
        $this->set_response([
            'status' => true,
            'data' => $faqs
        ], REST_Controller::HTTP_OK);
    }

    public function get_page_by_id_post()
    {
        $params = $this->post();
        $page_name = isset($params['page_name']) ? $params['page_name'] : '';
        if ($page_name == '') {
            $status = false;
            $msg = 'Page name is empty';
        } else {
            $pageData = $this->common_model->_selectById('b_pages', 'title,content', array('alias' => $page_name));
            $status = !empty($pageData) ? true : false;
            $msg = empty($pageData) ? 'Page name is wrong' : 'Success';
        }
        $this->set_response([
            'status' => $status,
            'message' => $msg,
            'url' => base_url('page/pageDetail/' . $page_name)
        ], REST_Controller::HTTP_OK);
    }


public function get_page_by_id_mobile_post()
    {
        $params = $this->post();
        $page_name = isset($params['page_name']) ? $params['page_name'] : '';
        if ($page_name == '') {
            $status = false;
            $msg = 'Page name is empty';
        } else {
            $pageData = $this->common_model->_selectById('b_pages', 'title,content', array('alias' => $page_name));
            $status = !empty($pageData) ? true : false;
            $msg = empty($pageData) ? 'Page name is wrong' : 'Success';
        }
        $this->set_response([
            'status' => $status,
            'message' => $msg,
            'url' => base_url('page/pageDetailForMobile/' . $page_name),
            'content' => $pageData["content"]
        ], REST_Controller::HTTP_OK);
    }




    public function contact_us_post()
    {
        $params = $this->post();

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error-server">', '</span>');
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|min_length[10]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[10]|max_length[10]');

        if ($this->form_validation->run() !== FALSE) {

            $dataArray['name'] = isset($params['name']) ? $params['name'] : '';
            $dataArray['email'] = isset($params['email']) ? $params['email'] : '';
            $dataArray['contact_no'] = isset($params['contact_no']) ? $params['contact_no'] : '';
            $dataArray['message'] = isset($params['message']) ? $params['message'] : '';
            $dataArray['added_on'] = set_local_to_gmt();
            $enquiryID = $this->common_model->_insertReturnId('b_contact_forms', $dataArray);
            if ($enquiryID) {
                $keywords = array('MESSAGE' => $dataArray['message'], 'EMAIL' => $dataArray['email'], 'NAME' => $dataArray['name'], 'PHONE' => $dataArray['contact_no']);
                SendEmailByTemplate(5, $keywords, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                $this->set_response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $params
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->set_response([
                'status' => false,
                'message' => filter_validation_errors(),
                'data' => $params
            ], REST_Controller::HTTP_OK);
        }
    }

}