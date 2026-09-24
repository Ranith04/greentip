<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("basic_helper");
    }

    public function all_categories_get()
    {
        try
            {
                $categories = $this->common_model->_select('b_categories', 'id,cat_name as name', ['status' => '1'], 'id', 'ASC');
                $this->set_response([
                    'status' => true,
                    'data' => $categories
                ], REST_Controller::HTTP_OK);
            }//try
        catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 
    }
      public function industrial_categories_get()
    {
        try
            {
                $categories = $this->common_model->_select('b_industrial_categories', 'id,cat_name as name', ['status' => '1'], 'id', 'ASC');
                $this->set_response([
                    'status' => true,
                    'data' => $categories
                ], REST_Controller::HTTP_OK);
            }//try
        catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 
    }

    public function all_faqs_get()
    {
        try
        {
             tryLogPrinter("all_faqs_get", $params);
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
        }//try
    catch(Exception $e) 
        {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

        } // catch 

    }

    public function get_page_by_id_post()
    {
        try
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
       }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 

    }
public function get_page_by_id_mobile_post()
    {
        try
        {
            $params = $this->post();
            tryLogPrinter("get_page_by_id_mobile", $params);
            $page_name = isset($params['page_name']) ? $params['page_name'] : '';
            if ($page_name == '') {
                $status = false;
                $msg = 'Page name is empty';
            } else {
                $pageData = $this->common_model->_selectById('b_pages', 'title,content', array('alias' => $page_name));

                if($page_name == "knowledge-center")
                {
                    $doc = $this->common_model->_select('knowledge_center_links_doc', 'id,type,value,name,created_on', array('type' => 1));
                    $link = $this->common_model->_select('knowledge_center_links_doc', 'id,type,value,name,created_on', array('type' => 0));
                }

        
                $status = !empty($pageData) ? true : false;
                $msg = empty($pageData) ? 'Page name is wrong' : 'Success';
            }

            if($page_name == "knowledge-center")
                {
                      $this->set_response([
                        'status' => $status,
                        'message' => $msg,
                        'url' => base_url('page/pageDetailForMobile/' . $page_name),
                        'content' => nl2br($pageData["content"]),
                        'documents' => $doc,
                        'links' => $link,
                    ], REST_Controller::HTTP_OK);
                }
                else
                {
                      $this->set_response([
                        'status' => $status,
                        'message' => $msg,
                        'url' => base_url('page/pageDetailForMobile/' . $page_name),
                        'content' => $pageData["content"]
                    ], REST_Controller::HTTP_OK);
                }
          
       }
    catch(Exception $e) 
        {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

        } // catch 
    }



    public function contact_us_post()
    {
        try
         {
            $params = $this->post();
            tryLogPrinter("contact_us", $params);
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
                        'message' => 'Message Sent Successfully',
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
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 
    }


/*
function tryLogPrinter($functionName, $param)
    {
       if(count($param)>0)
       {
             $parameters = implode(",", $param);
             $infomessage = trim("Function  is: ".$functionName." and parameters is :".$parameters);
       }
       else
       {
             $infomessage = trim("Function  is: ".$functionName);
       }

             log_message('info', $infomessage);
             return true;;
    }
*/
}