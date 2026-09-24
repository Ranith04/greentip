<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Api_Controller
{

    private $uploadPath;
    // print_r($uploadPath);die;
    public function __construct()
    {
        parent::__construct();
		
        $this->checkLogin();
          $this->load->helpers('basic_helper');
        
        /* Upload Profile Pic Path */
        $this->uploadPath = UPLOAD_PATH;
    }

    public function index_post()
    {
        
        $this->set_response([
            'status' => false,
            'message' => '',
            'data' => $this->loginUserData,
        ], REST_Controller::HTTP_OK);
    }

    public function getUserProfile_post()
    {

        try
         {
            tryLogPrinter("[info]: getUserProfile()", $params);
		 //log_message('info', "\n Exception Caught", $e->getMessage());
            $userDetail = $this->common_model->_selectById('b_users', '*', array('id' => $this->loginUserData['id'], 'status' => '1'));
            $userDetail['image'] = (!empty($userDetail['image'])) ? $this->uploadPath . "/users/" . $userDetail['image'] : '';
            $userDetail['address'] = (!empty($userDetail['address'])) ? $userDetail['address'] : '';
            $userDetail['company_location'] = (!empty($userDetail['company_location'])) ? $userDetail['company_location'] : '';
            $userDetail['state'] = (!empty($userDetail['state'])) ? $userDetail['state'] : '';
            $userDetail['district'] = (!empty($userDetail['district'])) ? $userDetail['district'] : '';
            $userDetail['pin'] = (!empty($userDetail['pin'])) ? $userDetail['pin'] : '';
            $response = [
                'status' => true,
                'message' => 'Success',
                'data' => $userDetail,
            ];
        }
        catch(Exception $e) 
        {
            log_message('[error]: getUserProfile()', "\n Exception Caught", $e->getMessage());
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => array(),
            ];

        }
        $this->apiSetOutput($response);
    }


    

    public function edit_profile_post()
    {

        try 
            {
                $params = $this->post();
                tryLogPrinter("[info]: edit_profile()", $params);
                $this->set_rules('EditProfile');
                $userFile = $_FILES;

                // if ($_FILES) {
                //   print_r($_FILES);die;
                // }
                if ($this->form_validation->run() !== FALSE) {

                    $userFile = isset($_FILES['userFile']) ? $_FILES['userFile'] : NULL;

                    $name = $params['name'] != '' ? $params['name'] : '';
                    $phone = $params['phone'] != '' ? $params['phone'] : NULL;
                    $company_name = $params['company_name'] != '' ? $params['company_name'] : NULL;
                    $occupation = $params['occupation'] != '' ? $params['occupation'] : '';
                    $education_qualification = $params['education_qualification'] != '' ? $params['education_qualification'] : NULL;
                    $expertise_field = $params['expertise_field'] != '' ? $params['expertise_field'] : NULL;
                    $address = $params['address'] != '' ? $params['address'] : NULL;

                    $dataArray['company_location'] =  (!empty($params['company_location']))? $params['company_location'] : NULL;
                    
                     $dataArray['district'] = (!empty($params['district']))? $params['district'] : NULL;
                     $dataArray['state'] = (!empty($params['state']))? $params['state'] : NULL;
                     $dataArray['pin'] =  (!empty($params['pin']))?$params['pin'] : NULL;

                    $dataArray['name'] = $name;
                    $dataArray['contact_no'] = $phone;
                    $dataArray['company_name'] = $company_name;
                    $dataArray['occupation'] = $occupation;
                    $dataArray['education_qualification'] = $education_qualification;
                    $dataArray['expertise_field'] = $expertise_field;
                      $dataArray['address'] = $address;
                     // print_r($dataArray);die;
                    $filename = $this->loginUserData['image'];
                    if (!empty($userFile)) {

                        $filename = md5($rand_val) . ".jpg";
                        $filename = $this->bulk_user_upload($filename);

                    }
                    
                    $dataArray['image'] = $filename;

                    //echo $dataArray['image']; die;
                    if ($this->common_model->_update('b_users', $dataArray, array('id' => $this->loginUserData['id']))) {
                        $flag = true;
                        $message = ' You have successfully updated your profile information.';
						
                    } else {
                        $flag = false;
                        $message = 'We are facing some technical issue, Please try later.';
                    }
                } else {
                    $flag = false;
                    $message = filter_validation_errors();
                }
            }
    catch(Exception $e) 
            { 
                log_message('[error]: edit_profile()', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

            }

        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
        ], REST_Controller::HTTP_OK);
    }

    public function changePassword_post()
    {

     try
        {
            $params = $this->post();
            tryLogPrinter("[info]: changePassword()", $params);
            $this->set_rules('changePassword');
            if ($this->form_validation->run() !== FALSE) {
                $old_password = $params['old_password'];
                $password = $this->user_model->hash($params['new_password']);
                if ($this->user_model->check_password($old_password, $this->loginUserData['password']) == false) {
                    $this->apiSetOutput(['status' => false, 'message' => 'Invalid old password']);
                }
                if ($this->db->update('b_users', ['password' => $password], ['id' => $this->loginUserData['id']])) {
                    $this->apiSetOutput([
                        'status' => true,
                        'message' => 'Password changed successfully.'
                    ]);
                } else {
                    $this->apiSetOutput([
                        'status' => false,
                        'message' => 'Something went wrong ,Please try again.'
                    ]);
                }
            } else {
                $this->apiSetOutput(['status' => false, 'message' => filter_validation_errors()]);
            }
       }  // try
catch(Exception $e) 
      { 
                log_message('[error]: changePassword()', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();
                $this->apiSetOutput(['status' => $flag, 'message' => $message]);
      }


    }

    public function dashboard_post()
    {
        try
        {
        $params = $this->post();
        tryLogPrinter("[info]:dashboard()", $params);
        $response_type = $params['response_type'] != '' ? $params['response_type'] : 'all';
        //print_r($this->loginUserData);die;
        /*--By End User----*/
        if ($this->loginUserData['role_type'] == '2') {
            // $condition = array('q.user_id' => $this->loginUserData['id']);
           
            // $result = $this->user_model->getQueryEnduser('b_queries', $this->start, $this->perPage, '*', $condition);
            // $listings = [];
            // if (isset($result['total_rows']) && $result['total_rows'] > 0) {
            //     $listings = $result['results'];
            // }
            // $flag = true;
            // $message = 'Success';
            // $params = array('queries' => $listings, 'total' => $result['total_rows'], 'imagepath' =>$this->uploadPath."/users/");


             if ($response_type == '' || $response_type == 'expert') {

                    $condition = array('q.user_id' => $this->loginUserData['id'], 'q.status' => '0');
                    $result = $this->user_model->getQueryEnduser('b_queries', $this->start, $this->perPage, '*', $condition);

                    $listings = [];
                    $pagination = '';
                    if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                        $listings = $result['results'];
                        
                    }
                    $flag = true;
            $message = 'Success';
            $params = array('queries' => $listings, 'total' => $result['total_rows'], 'imagepath' =>$this->uploadPath."/users/");

                }

                if ($response_type == '' || $response_type == 'answered_expert') {
                    $condition1 = array('q.user_id' => $this->loginUserData['id'], 'q.status' => '1');
                    $result1 = $this->user_model->getQueryEnduser('b_queries',  $this->start, $this->perPage, '*', $condition1);

                    //print_r($result1);die;

                    $listings1 = [];
                    $pagination1 = '';
                    if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                        $listings1 = $result1['results'];
                       
                    }
                    $flag = true;
            $message = 'Success';
            $params = array('queries' => $listings1, 'total' => $result1['total_rows'], 'imagepath' =>$this->uploadPath."/users/");

                }


                if ($response_type == '' || $response_type == 'unanswered_expert') {
                    $condition2 = array('q.user_id' => $this->loginUserData['id'], 'q.status ' => '2');
                    $result2 = $this->user_model->getQueryEnduser('b_queries',  $this->start, $this->perPage, '*', $condition2);
                    $listings2 = [];
                    $pagination2 = '';
                    if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                        $listings2 = $result2['results'];
                        
                    }
                    $flag = true;
            $message = 'Success';
            $params = array('queries' => $listings2, 'total' => $result2['total_rows'], 'imagepath' =>$this->uploadPath."/users/");

                }
        }

        /*--By Expert----*/
        if ($this->loginUserData['role_type'] == '1') {

            if ($response_type == '' || $response_type == 'all') {
                $condition = array('qa.user_id' => $this->loginUserData['id']);
                $result = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition);
                $listings = [];
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];
                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings, 'total' => $result['total_rows'], 'imagepath' =>$this->uploadPath."/users/");
            }

            if ($response_type == '' || $response_type == 'answered_query') {
                $condition1 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status!=' => '0');
                $result1 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition1);
                $listings1 = [];

                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];
                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings1, 'total' => $result1['total_rows'], 'imagepath' =>$this->uploadPath."/users/");
            }

            if ($response_type == '' || $response_type == 'unanswered_query') {
                $condition2 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status' => '0');
                $result2 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition2);
                $listings2 = [];
                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $result2['results'];
                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings2, 'total' => $result2['total_rows'], 'imagepath' =>$this->uploadPath."/users/");
            }

        }

        /*--By Super Admin--*/
        if ($this->loginUserData['role_type'] == '0') {

            if ($response_type == '' || $response_type == 'all') {
                $condition = array('q.status!=' => '1');
                $result = $this->user_model->getNewQuery('b_queries', $this->start, $this->perPage, '*', $condition);
                $listings = [];
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];
                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings, 'total' => $result['total_rows'], 'imagepath' =>$this->uploadPath."/users/");
            }

            if ($response_type == '' || $response_type == 'in_progress_query') {
                /*Respond Process Data Get*/
                $condition = array('q.status!=' => '1');
                $result1 = $this->user_model->getRespondQuery('b_query_assign', $this->start, $this->perPage, '*', $condition);
                $listings1 = [];
                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];

                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings1, 'total' => $result1['total_rows'], 'imagepath' =>$this->uploadPath."/users/");
                /*--End New Query Data*/
            }

            if ($response_type == '' || $response_type == 'responded_query') {
                $condition2 = array('qa.status' => '1', 'review_status' => '1');
                $result2 = $this->user_model->getRespondedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition2);
                //print_r($result2);die;
                $listings2 = [];
                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $this->managerArray($result2['results']);
                }
                //print_r($listings2);die;
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings2, 'total' => $result2['total_rows'], 'imagepath' =>$this->uploadPath."/users/");

            }


        }
         /*--By MD Dasboard--*/
        if (isset($this->loginUserData['role_type']) && $this->loginUserData['role_type'] == '3') {
            //print_r($this->loginUserData);die;
            if ($response_type == '' || $response_type == 'expert') {
                $condition = array('qa.user_id' => $this->loginUserData['id']);

                $result = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition);

                $listings = [];
                $pagination = '';
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];

                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings, 'total' => $result['total_rows'], 'imagepath' =>$this->uploadPath."/users/");
            }

            if ($response_type == '' || $response_type == 'answered_expert') {
                $condition1 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status!=' => '0');
                $result1 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition1);
                $listings1 = [];
                $pagination1 = '';
                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];
                    
                }
                 $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings1, 'imagepath' =>$this->uploadPath."/users/");
            }


            if ($response_type == '' || $response_type == 'unanswered_expert') {
                $condition2 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status' => '0');
                $result2 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition2);
                $listings2 = [];
                $pagination2 = '';
                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $result2['results'];
                  
                }
                $flag = true;
                $message = 'Success';
                $params = array('queries' => $listings2, 'total' => $result['total_rows'], 'imagepath' =>$this->uploadPath."/users/");

            }

            
        }
    } //try
    catch(Exception $e) 
            { 
                log_message('[error]: dashboard()', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

            } // catch 

        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
        ], REST_Controller::HTTP_OK);
    }





function managerArray($data)
{  
             $listings =array();
                foreach ($data as $value) 
                    {
                        $answer = array("answer"=>nl2br(str_replace("&nbsp;", " ", htmlspecialchars_decode(htmlentities($value["answerData"]["answer"])))), "answer_date"=>$value["answerData"]["answer_date"], "user_id"=>$value["answerData"]["user_id"],    "upload_image"=>$value["answerData"]["upload_image"], 
                        "upload_full_image"=>$value["answerData"]["upload_full_image"],   "ext"=>$value["answerData"]["ext"],       "expert_name"=>$value["answerData"]["expert_name"], "expert_image"=>$value["answerData"]["expert_image"]);

                        $listings[] = array(
                            "total_rows"=>$value["total_rows"],"id"=>$value["id"],"name"=>$value["name"],"query_id"=>$value["query_id"],"user_id"=>$value["user_id"],"expert_answer"=>nl2br(str_replace("&nbsp;", " ", htmlspecialchars_decode(htmlentities(strip_tags($value["expert_answer"]))))),"answer"=>nl2br(str_replace("&nbsp;", " ", htmlspecialchars_decode(htmlentities(strip_tags($value["answer"]))))),"added_on"=>$value["added_on"],"respond_date"=>$value["respond_date"],"admin_review_date"=>$value["admin_review_date"],"status"=>$value["status"],"review_status"=>$value["review_status"], "asked_date"=>$value["asked_date"], "query"=>$value["query"],"user_image"=>$value["user_image"],"category_name"=>$value["category_name"],"answerData"=>$answer);

                    }
                    return $listings;
}

    public function dashboard_end_user_post(){
         $params = $this->post();
         tryLogPrinter("dashboard_end_user", $params);
        $response_type = $params['response_type'] != '' ? $params['response_type'] : 'all';

        /*--By End User----*/
        if ($this->loginUserData['role_type'] == '2') {
            $condition = array('q.user_id' => $this->loginUserData['id']);
            $result = $this->user_model->getQueryEnduser('b_queries', $this->start, $this->perPage, '*', $condition);
            $listings = [];
            if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                $listings = $result['results'];
            }
            $flag = true;
            $message = 'Success';
            $params = array('queries' => $listings, 'total' => $result['total_rows']);
        }

    }

    public function count_dashboard_post()
    {
		
        try {
			
        $params = $this->post();
         tryLogPrinter("count_dashboard", $params);
        /*--By Expert----*/
        if ($this->loginUserData['role_type'] == '1') {


            $condition1 = array('qa.user_id' => $this->loginUserData['id']);
            $result1 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition1);
            $all = $result1['total_rows'];

            $condition2 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status!=' => '0');
            $result2 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition2);
            $answered_query = $result2['total_rows'];

            $condition3 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status' => '0');
            $result3 = $this->user_model->getAssignedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition3);
            $unanswered_query = $result3['total_rows'];
            $total = $all + $answered_query + $unanswered_query;
            $params = array('total_inbox' => strval($total), 'all' => $all, 'answered_query' => $answered_query, 'unanswered_query' => $unanswered_query);


        }

        /*--By Super Admin--*/
        if ($this->loginUserData['role_type'] == '0') {

            $condition1 = array('q.status!=' => '1');
            $result1 = $this->user_model->getNewQuery('b_queries', $this->start, $this->perPage, '*', $condition1);
            $new_query = $result1['total_rows'];

            /*Respond Process Data Get*/
            $condition2 = array('q.status!=' => '1');
            $result2 = $this->user_model->getRespondQuery('b_query_assign', $this->start, $this->perPage, '*', $condition2);
            $respond_query = $result2['total_rows'];

            $condition3 = array('qa.status' => '1', 'review_status' => '1');
            $result3 = $this->user_model->getRespondedQuery('b_query_assign', $this->start, $this->perPage, '*', $condition3);
            $responded_query = $result3['total_rows'];
            $total = $new_query + $respond_query + $responded_query;
            $params = array('total_inbox' => strval($total), 'new_query' => $new_query, 'respond_query' => $respond_query, 'responded_query' => $responded_query);
        }
         if ($this->loginUserData['role_type'] == '3') {


             $condition = array('qa.user_id' => $this->loginUserData['id']);
                $result = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition);
                

                $condition1 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status!=' => '0');
                $result1 = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition1);
                
                 $condition2 = array('qa.user_id' => $this->loginUserData['id'], 'qa.status' => '0');
                $result2 = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition2);
              $total = $result['total_rows'] + $result1['total_rows'] + $result2['total_rows'];
            $params = array('total_inbox' => strval($total), 'all' => $result['total_rows'], 'answered_query' => $result1['total_rows'], 'unanswered_query' => $result2['total_rows']);
   
          // $total = $all + $answered_query + $unanswered_query;
               
          //      $params = array('all_query' => $result['total_rows'], 'answered_query' => $result1['total_rows'], 'unanswered_query' => $result2['total_rows']);
           
        }
         if ($this->loginUserData['role_type'] == '2') {
          

              $condition = array('q.user_id' => $this->loginUserData['id'], 'q.status' => '0');
                    $result = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition);
                

                $condition1 = array('q.user_id' => $this->loginUserData['id'], 'q.status' => '1');
                    $result1 = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition1);
                
                 $condition2 = array('q.user_id' => $this->loginUserData['id'], 'q.status ' => '2');
                    $result2 = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition2);
              $total = $result['total_rows'] + $result1['total_rows'] + $result2['total_rows'];
            $params = array('total_inbox' => strval($total), 'new_query' => $result['total_rows'], 'answered_query' => $result1['total_rows'], 'rejected' => $result2['total_rows']);
   
          // $total = $all + $answered_query + $unanswered_query;
               
          //      $params = array('all_query' => $result['total_rows'], 'answered_query' => $result1['total_rows'], 'unanswered_query' => $result2['total_rows']);
           
        }


        $flag = true;
        $message = 'Success';

        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    /*--End User Query Submit and mail goes to super admin------*/
    public function submit_query_post()
    {
        try 
          {
                $params = $this->post();
                tryLogPrinter("submit_query", $params);
                /*--By End User----*/
                if ($this->loginUserData['role_type'] == '2') {
                    $this->set_rules('submit_query');
              if ($_FILES) {
                //$filename       =            $_FILES['attachment_file']['name'] ; 
                $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $rand_val = date('YMDHIS') . rand(11111, 99999);
                 $filename = md5($rand_val) . "." . $imgInfo;
                $_FILES['attachment_file']['name'] = $filename;
            
                $config['upload_path']          = "assets/uploads/attachment_file";
                $config['allowed_types']        = 'gif|jpg|png|jpeg|mp4|doc|docx|pdf|txt';
                $config['max_size']             = 100000;
                $config['max_width']            = 100024;
                $config['max_height']           = 10768;
                $attachment =  "assets/uploads/attachment_file/".$filename;
                $this->load->library('upload', $config);
                $this->upload->do_upload('attachment_file'); 
            }
                    if ($this->form_validation->run() !== FALSE) {
                        $category_id = $params['category_id'];
                        $query = $params['query'];
                        $user_id = $this->loginUserData['id'];
                        $dataArray['category_id'] = $category_id;
                        $dataArray['query'] = $query;
                        $dataArray['user_id'] = $user_id;
                        if ( $attachment) {
                         $dataArray['query_image'] =$attachment;
                    }
                        $dataArray['added_on'] = set_local_to_gmt();
                        $dataArray['modified_on'] = set_local_to_gmt();
                        $userID = $this->common_model->_insertReturnId('b_queries', $dataArray);
                        if ($userID) {
                            /*----mail send to super admin----*/
                            $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                            $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->loginUserData['name'], 'SENDER_EMAIL' => $this->loginUserData['email'], 'QUERY' => $query);
                            SendEmailByTemplate(8, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->loginUserData['name']);
                            $flag = true;
                            $message = ' Your query has been submitted';
                        } else {
                            $flag = false;
                            $message = 'Whoops! Something went wrong. Please try again later.';
                        }
                    } else {
                        $flag = false;
                        $message = filter_validation_errors();
                    }
                } else {
                    $flag = false;
                    $message = 'Sorry! You don\'t have a permission to add the query.Please login another account.';
                }
                $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => $params
                ], REST_Controller::HTTP_OK);
        }// try

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

    /*--Discard query by super admin and mail goes to end users------*/
    /*--Discard query by expert and mail goes to admin------*/
    public function discard_query_post()
    {
        try
        {
        $params = $this->post();
          tryLogPrinter("discard_query", $params);
        /*--By Super Admin----*/
        if ($this->loginUserData['role_type'] == '0' || $this->loginUserData['role_type'] == '1'|| $this->loginUserData['role_type'] == '3') {
            $this->set_rules('discard_query');
            if ($this->form_validation->run() !== FALSE) {
                $query_id = $params['query_id'];
                $user_id = $this->loginUserData['id'];

                $queryData = $this->common_model->_selectById('b_queries', 'user_id,query', ['id' => $query_id]);
                
                if (empty($queryData)) {
                    $flag = false;
                    $message = 'Query not found.';
                } else {
                    $dataArray['status'] = '2';

                    /*---Action by Super admin--*/
                    if ($this->loginUserData['role_type'] == '0') {
                        $this->common_model->_update('b_queries', $dataArray, ['id' => $query_id, 'status' => '0']);
                        $afftectedRows = $this->db->affected_rows();
                        if ($afftectedRows > 0) {
                            /*----mail send to end user----*/
                            $endUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $queryData['user_id']));
                            $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                            $keyword = array('RECEIVER_NAME' => $endUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'QUERY' => $queryData['query']);
                            SendEmailByTemplate(9, $keyword, $endUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);

                            $flag = true;
                            $message = 'Query discarded successfully.';
                        } else {
                            $flag = false;
                            $message = 'Whoops! Something went wrong. Please try again later.';
                        }
                    }

                    /*---Action by Expert--*/
                    if ($this->loginUserData['role_type'] == '1'||$this->loginUserData['role_type'] == '3') {
                        $queryAssignData = $this->common_model->_selectById('b_query_assign', 'id', ['query_id' => $query_id, 'user_id' => $user_id]);
                        if (empty($queryAssignData)) {
                            $flag = false;
                            $message = ' Assigned Query id is wrong.';
                        } else {
                            $this->common_model->_update('b_query_assign', $dataArray, ['query_id' => $query_id, 'status' => '0', 'user_id' => $user_id]);
                            $afftectedRows = $this->db->affected_rows();
                            if ($afftectedRows > 0) {
                                /*----mail send to admin----*/
                                $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                                $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->UserDetail['name'], 'SENDER_EMAIL' => $this->UserDetail['email'], 'QUERY' => $queryData['query']);
                                SendEmailByTemplate(11, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->UserDetail['name']);
                                $flag = true;
                                $message = 'Query discarded successfully.';
                            } else {
                                $flag = false;
                                $message = 'Whoops! Something went wrong. Please try again later.';
                            }
                        }
                    }

                }
            } else {
                $flag = false;
                $message = filter_validation_errors();
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have a permission to discard query.Please login another account.';
        }
        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    /*--Super admin assign query to expert------*/
    public function query_assign_to_expert_post()
    {

        try
            {
                     $params = $this->post();
                      tryLogPrinter("query_assign_to_expert", $params);
                    // print_r($params);die;
                    /*--By Super Admin----*/
                    if ($this->loginUserData['role_type'] == '0') {
                        $this->set_rules('query_assign_to_expert');
                        if ($this->form_validation->run() !== FALSE) {
                            $queryData = $this->common_model->_selectById('b_queries', 'user_id,query,status', ['id' => $params['query_id']]);
                            if (empty($queryData)) {
                                $flag = false;
                                $message = 'Query not found.';
                            } else {
                                if ($queryData['status'] == '2') {
                                    $flag = false;
                                    $message = 'Whoops! You can not assign the query to expert because query has been discarded.';
                                } else {
                                    $AssignQueryData = $this->common_model->_selectById('b_query_assign', '*', ['query_id' => $params['query_id'], 'user_id' => $params['expert_id']]);
                                    if (!empty($AssignQueryData)) {
                                        $flag = false;
                                        $message = 'Query already assigned to expert.';
                                    } else {
                                        $dataArray['user_id'] = $params['expert_id'];
                                        $dataArray['query_id'] = $params['query_id'];
                                        $dataArray['added_on'] = set_local_to_gmt();
                                        $userID = $this->common_model->_insertReturnId('b_query_assign', $dataArray);
                                        $params['id'] = $userID;
                                        if ($userID) {
                                            /*----mail send to expert user----*/
                                            $expertUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $params['expert_id']));
                                            $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                                            $keyword = array('RECEIVER_NAME' => $expertUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'QUERY' => $queryData['query']);
                                            SendEmailByTemplate(10, $keyword, $expertUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);

                                            $flag = true;
                                            $message = ' Your query has been assigned to Expert.';
                                        } else {
                                            $flag = false;
                                            $message = 'Whoops! Something went wrong. Please try again later.';
                                        }
                                    }

                                }
                            }
                        } else {
                            $flag = false;
                            $message = filter_validation_errors();
                        }
                    } else {
                        $flag = false;
                        $message = 'Sorry! You don\'t have a permission to assign the query to any expert .Please login another account.';
                    }
                    $this->set_response([
                        'status' => $flag,
                        'message' => $message,
                        'data' => $params
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

    /*--Expert respond query------*/
    public function respond_by_expert_post()
    {
        try
            {
      
		 $params = $this->post();
                tryLogPrinter("respond_by_expert", $params);
                /*--By Super Admin----*/
                if ($this->loginUserData['role_type'] == '1') {


              if ($_FILES) {
                //$filename       =            $_FILES['attachment_file']['name'] ; 
                $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $rand_val = date('YMDHIS') . rand(11111, 99999);
                 $filename = md5($rand_val) . "." . $imgInfo;
                $_FILES['attachment_file']['name'] = $filename;
            
                $config['upload_path']          = "assets/uploads/attachment_file";
                $config['allowed_types']        = 'gif|jpg|png|jpeg|mp4|doc|docx|pdf';
                $config['max_size']             = 100000;
                $config['max_width']            = 100024;
                $config['max_height']           = 10768;
                $attachment =  "assets/uploads/attachment_file/".$filename;
                $this->load->library('upload', $config);
                $this->upload->do_upload('attachment_file'); 
            }




                    $this->set_rules('respond_by_expert');
                    if ($this->form_validation->run() !== FALSE) {
                        $user_id = $this->loginUserData['id'];
                        $assign_query_id = $params['assign_query_id'];
                        $queryAssignData = $this->common_model->_selectById('b_query_assign', 'query_id,respond_Date,status', ['id' => $assign_query_id, 'user_id' => $user_id]);
                        if (empty($queryAssignData)) {
                            $flag = false;
                            $message = 'Assign Query not found.';
                        } else {
                            if ($queryAssignData['status'] == '2') {
                                $flag = false;
                                $message = 'Whoops! You can not respond because query has been discarded';
                            } elseif ($queryAssignData['respond_Date'] != NULL) {
                                $flag = false;
                                $message = 'Whoops! Already Respoded.';
                            } else {
                                $dataArray['expert_answer'] = $params['answer'];
                                $dataArray['status'] = '1';
                                if ( $attachment) {
                                $dataArray['upload_image'] =$attachment;
                                  }
                                $dataArray['respond_Date'] = set_local_to_gmt();
                                if ($this->common_model->_update('b_query_assign', $dataArray, ['id' => $assign_query_id, 'user_id' => $user_id])) {
                                    /*----mail send to admin----*/
                                    $queryData = $this->common_model->_selectById('b_queries', 'query', ['id' => $queryAssignData['query_id']]);
                                    $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                                    $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->UserDetail['name'], 'SENDER_EMAIL' => $this->UserDetail['email'], 'QUERY' => $queryData['query'], 'RESPOND_MESSAGE' => $answer);
                                    SendEmailByTemplate(12, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->UserDetail['name']);

                                    $flag = true;
                                    $message = ' Your respond has been submitted.';
                                } else {
                                    $flag = false;
                                    $message = 'Whoops! Something went wrong. Please try again later.';
                                }
                            }

                        }
                    } else {
                        $flag = false;
                        $message = filter_validation_errors();
                    }
                } else {
                    $flag = false;
                    $message = 'Sorry! You don\'t have a permission to respond the query .Please login another account.';
                }
                $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => $params
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

    /*--admin review expert respond------*/
    public function review_post()
    {
        try

        {


        $params = $this->post();
        tryLogPrinter("review", $params);
        /*--By Super Admin----*/
        if ($this->loginUserData['role_type'] == '0') {
            $this->set_rules('review');
            if ($this->form_validation->run() !== FALSE) {
                $user_id = $this->loginUserData['id'];
                $assign_query_id = $params['assign_query_id'];
                $answer = $params['answer'];
                $queryAssignData = $this->common_model->_selectById('b_query_assign', 'query_id,respond_Date,status', ['id' => $assign_query_id]);
                if (empty($queryAssignData)) {
                    $flag = false;
                    $message = 'Assign Query not found.';
                } else {
                    if ($queryAssignData['status'] == '2') {
                        $flag = false;
                        $message = 'Whoops! You can not review because query has been discarded';
                    } elseif ($queryAssignData['status'] == '0') {
                        $flag = false;
                        $message = 'Whoops! Expert not responded';
                    } else {
                        $query_id = $queryAssignData['query_id'];
                        $queryData = $this->common_model->_selectById('b_queries', 'query,user_id,status', ['id' => $query_id]);
                        if ($queryData['status'] == '2') {
                            $flag = false;
                            $message = 'Query Discarded.';
                        } elseif ($queryData['status'] == '1') {
                            $flag = false;
                            $message = 'Query already responded.';
                        } else {
                            $dataArray['answer'] = $answer;
                            $dataArray['admin_review_date'] = set_local_to_gmt();
                            $dataArray['review_status'] = '1';

                            $this->common_model->_update('b_query_assign', $dataArray, ['id' => $assign_query_id]);
                            $this->common_model->_update('b_queries', ['status' => '1'], ['id' => $query_id]);

                            /*----mail send to end user----*/

                            $endUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $queryData['user_id'], 'status !=' => '3'));
                            $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                            $keyword = array('RECEIVER_NAME' => $endUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'SENDER_EMAIL' => $superUserData['email'], 'QUERY' => $queryData['query']);
                            SendEmailByTemplate(13, $keyword, $endUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);

                            $flag = true;
                            $message = ' Your review has been submitted.';
                        }


                    }

                }
            } else {
                $flag = false;
                $message = filter_validation_errors();
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have a permission to review the query .Please login another account.';
        }
        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    public function get_answer_by_expert_post()
    {
     try
        {
            $params = $this->post();
             tryLogPrinter("get_answer_by_expert", $params);
            $listings = [];
            /*--By Super Admin----*/
            if ($this->loginUserData['role_type'] == '0') {
                $this->set_rules('get_answer_by_expert');
                if ($this->form_validation->run() !== FALSE) {
                    $assign_query_id = $params['assign_query_id'];
                    $condition = array('qa.id' => $assign_query_id);
                    $result = $this->user_model->getAssignedQuery('b_query_assign', '0', '0', '*', $condition);

                    $pagination = '';
                    if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                        $listings = $result['results'][0];
                    }
                    $flag = true;
                    $message = 'Success';
                } else {
                    $flag = false;
                    $message = filter_validation_errors();
                }
            } else {
                $flag = false;
                $message = 'Sorry! You don\'t have a permission to get answer by expert .Please login another account.';
            }
            $this->set_response([
                'status' => $flag,
                'message' => $message,
                'data' => $listings
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

    public function get_expert_detail_post()
    {
        try
            {   
                $listings = '';
                $params = $this->post();
                 tryLogPrinter("get_expert_detail", $params);
                $this->set_rules('get_expert_detail');
                if ($this->form_validation->run() !== FALSE) {
                    $expert_id = $params['expert_id'];
                    $condition = array('id' => $expert_id);
                    $expertDetail = $this->common_model->_selectById('b_users', '*', $condition);
                    if (!empty($expertDetail)) {
                        $listings = $expertDetail;
                        $flag = true;
                        $message = 'Success';
                    } else {
                        $flag = false;
                        $message = 'Wrong expert Id';
                    }
                } else {
                    $flag = false;
                    $message = filter_validation_errors();
                }

                $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => $listings
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

    /*--------------Admin Expert/End User List/Add/Edit/Delete/View----------------------------------*/

    public function admin_users_post()
    { 

        try
        {
        $params = $this->post();
         tryLogPrinter("admin_users", $params);	
        if ($this->loginUserData['role_type'] == '0' ||$this->loginUserData['role_type'] == '3') {

            if (isset($params['list_by']) && ($params['list_by'] == 'end_user' || $params['list_by'] == 'expert_user'|| $params['list_by'] == 'md_user')) {
                $userType = $params['list_by'];
                $conditionArray['equal']['u.status!='] = '3';
                if ($params['list_by'] == 'md_user') {
                 $conditionArray['equal']['u.role_type'] = $userType == 'end_user' ? '2' : '3';	
                }else{
                $conditionArray['equal']['u.role_type'] = $userType == 'end_user' ? '2' : '1';
                   }
               // print_r($conditionArray['equal']['u.role_type']);die;
                $userDataCount = $this->user_model->expert_users_record_count($conditionArray);
                $endUsersList = $this->user_model->get_expert_users($this->start, $this->perPage, $conditionArray);
                $params = array('end_user_list' => !empty($endUsersList) ? $endUsersList : [], 'total' => $userDataCount);
                $flag = true;
                $message = 'Success';
            } else {
                $flag = false;
                $message = 'Whoops! Something went wrong.';
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have any permissoin to access this.';
        }

        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    public function admin_insert_user_post()
    {

        try
        {
        $userFile = $_FILES;

        $params = $this->post();
         tryLogPrinter("admin_insert_user", $params); 
        if ($this->loginUserData['role_type'] == '0'||$this->loginUserData['role_type'] == '3') {

            $this->set_rules('insert_user');
           
            if ($this->form_validation->run($this) !== FALSE) {
                
                $userFile = isset($_POST['userFile']) ? $_POST['userFile'] : '';
                $first_name = $params['first_name'] != '' ? $params['first_name'] : '';
                $last_name = $params['last_name'] != '' ? $params['last_name'] : '';

                $dataArray['name'] = $first_name . ' ' . $last_name;
                $dataArray['email'] = $params['email'];
                if ($params['contact_no']) {
                  $dataArray['contact_no'] = $params['contact_no'] != '' ? $params['contact_no'] : '';  
                }
                 $dataArray['company_location'] = $this->input->post('company_location');
                 $dataArray['address'] = $this->input->post('address');
                 $dataArray['district'] = $this->input->post('district');
                 $dataArray['state'] = $this->input->post('state');
                 $dataArray['pin'] = $this->input->post('pin');
                // $dataArray['company_name'] = $params['company_name'] != '' ? $params['company_name'] : '';
                // $dataArray['occupation'] = $params['occupation'] != '' ? $params['occupation'] : '';
                // $dataArray['education_qualification'] = $params['education_qualification'] != '' ? $params['education_qualification'] : '';
                // $dataArray['expertise_field'] = $params['expertise_field'] != '' ? $params['expertise_field'] : '';
                 $dataArray['company_name'] = $params['company_name'];
                $dataArray['occupation'] = $params['occupation'];
                $dataArray['education_qualification'] = $params['education_qualification'];
                $dataArray['expertise_field'] = $params['expertise_field'];
                if ($this->input->post('password')) {
                  $password = $this->input->post('password'); 
                }
                //$password = $this->input->post('password');
                $password = $password != '' ? $password : randomGenerateString();
                $dataArray['password'] = $this->user_model->hash($password);
                if (isset($userFile)) {
                   
                    $rand_val = date('YMDHIS') . rand(11111, 99999);
                    $filename = md5($rand_val) . ".jpg";
                    $upload_path = "./assets/uploads/users/";
                    $img = str_replace('data:image/jpeg;base64,', '', $userFile);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    file_put_contents($upload_path . $filename, $data);
                    $filename = md5($rand_val) . ".jpg";
                    $filename = $this->bulk_user_upload($filename);
                    
                }
                $dataArray['image'] = $filename;
                
                $dataArray['status'] = '1';
                $template_id = 14;
                if ($params['list_by'] != 'end_user') {
                    $dataArray['description'] = $params['description'] != '' ? $params['description'] : '';
                    $dataArray['linkdin'] = $params['linkdin'] != '' ? $params['linkdin'] : '';
                    $dataArray['facebook'] = $params['facebook'] != '' ? $params['facebook'] : '';
                    $template_id = 6;
                }
                $dataArray['role_type'] = $params['list_by'] == 'end_user' ? '2' : '1';
                $userID = $this->common_model->_insertReturnId('b_users', $dataArray);
                if ($userID > 0) {
                    $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                    SendEmailByTemplate($template_id, $keywords, $dataArray['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    $flag = true;
                    $message = ' You have successfully added new user.';
                } else {
                    $flag = false;
                    $message = 'We are facing some technical issue, Please try later.';
                }
            } else {
                $flag = false;
                $message = filter_validation_errors();
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have any permissoin to access this.';
        }
        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    public function admin_update_user_post()
    {
        try
        {
        $params = $this->post();
          tryLogPrinter("admin_update_user", $params); 
        /*--By End User----*/
        if ($this->loginUserData['role_type'] == '0'||$this->loginUserData['role_type'] == '3') {
            $this->set_rules('update_user');
            if ($this->form_validation->run() !== FALSE) {
                $roleUserID = $params['roleUserID'];
                $profile = $this->user_model->profile($roleUserID);
                $dbdata = $profile;
                $userFile = isset($_FILES['userFile']) ? $_FILES['userFile'] : '';
                $first_name = $params['first_name'] != '' ? $params['first_name'] : '';
                $last_name = $params['last_name'] != '' ? $params['last_name'] : '';
                $dataArray['name'] = $first_name . ' ' . $last_name;
                $dataArray['email'] = $email = $params['email'];
                if ( $params['contact_no']) {
                  $dataArray['contact_no'] = $params['contact_no'] != '' ? $params['contact_no'] : '';
                }
               
                $dataArray['company_name'] = $params['company_name'];
                $dataArray['occupation'] = $params['occupation'];
                $dataArray['education_qualification'] = $params['education_qualification'];
                $dataArray['expertise_field'] = $params['expertise_field'];
                 $dataArray['company_location'] = $this->input->post('company_location');
                 $dataArray['address'] = $this->input->post('address');
                 $dataArray['district'] = $this->input->post('district');
                 $dataArray['state'] = $this->input->post('state');
                 $dataArray['pin'] = $this->input->post('pin');
                $template_id = 14;
                if ($params['list_by'] != 'end_user') {
                    $dataArray['description'] = $params['description'] != '' ? $params['description'] : '';
                    $dataArray['linkdin'] = $params['linkdin'] != '' ? $params['linkdin'] : '';
                    $dataArray['facebook'] = $params['facebook'] != '' ? $params['facebook'] : '';
                    $template_id = 6;
                }
                if (!empty($userFile)) {
                    $rand_val = date('YMDHIS') . rand(11111, 99999);
                    $filename = md5($rand_val) . ".jpg";
                    $upload_path = "./assets/uploads/users/";
                    $img = str_replace('data:image/jpeg;base64,', '', $userFile);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    file_put_contents($upload_path . $filename, $data);
                      $filename = $this->bulk_user_upload($filename);
                    $dataArray['image'] = $filename;
                }
                $password = $this->input->post('password');
                $keywords = '';
                if ($password != '') {
                    $dataArray['password'] = $this->user_model->hash($password);
                    $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                }
                if ($email != $dbdata['email']) {
                    $password = isset($dataArray['password']) ? $dataArray['password'] : randomGenerateString();
                    $dataArray['password'] = $this->user_model->hash($password);
                    $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                }
                $this->common_model->_update('b_users', $dataArray, array('id' => $roleUserID));
                $afftectedRows = $this->db->affected_rows();
                if ($afftectedRows > 0) {
                    if ($keywords != '') {
                        SendEmailByTemplate($template_id, $keywords, $dataArray['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    }
                    $flag = true;
                    $message = ' You have successfully updated profile information.';
                } else {
                    $flag = false;
                    $message = 'Whoops! Something went wrong. Please try again later.';
                }
            } else {
                $flag = false;
                $message = filter_validation_errors();
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have any permissoin to access this.';
        }
        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    public function admin_delete_user_post()
    {
        try
        {
        $params = $this->post();
        tryLogPrinter("admin_delete_user", $params); 
        /*--By End User----*/
        if ($this->loginUserData['role_type'] == '0'||$this->loginUserData['role_type'] == '3') {
            $this->set_rules('delete_user');
            if ($this->form_validation->run() !== FALSE) {
                $roleUserID = $params['roleUserID'];
                $profile = $this->user_model->profile($roleUserID);
                $dbdata = $profile;
                if (!empty($dbdata)) {
                    $this->common_model->_update('b_users', array('status' => '3'), array('id' => $roleUserID));
                    $params = $dbdata;
                    $flag = true;
                    $message = 'User deleted successfully';
                } else {
                    $flag = false;
                    $message = 'No User found.';
                }
            } else {
                $flag = false;
                $message = filter_validation_errors();
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have any permissoin to access this.';
        }
        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    public function admin_view_profile_user_post()
    {
        try
        {
        $params = $this->post();
         tryLogPrinter("admin_view_profile_user", $params); 
        /*--By End User----*/
        if ($this->loginUserData['role_type'] == '0') {
            $this->set_rules('view_profile_user');
            if ($this->form_validation->run() !== FALSE) {
                $roleUserID = $params['roleUserID'];
                $profile = $this->user_model->profile($roleUserID);
                $dbdata = $profile;
                if (!empty($dbdata)) {
                    $dbdata['image'] = (!empty($dbdata['image'])) ? $this->uploadPath . "/users/" . $dbdata['image'] : DEFAULT_NO_IMAGE;
                    $params = $dbdata;
                    $flag = true;
                    $message = 'Success';
                } else {
                    $flag = false;
                    $message = 'No User found.';
                }
            } else {
                $flag = false;
                $message = filter_validation_errors();
            }
        } else {
            $flag = false;
            $message = 'Sorry! You don\'t have any permissoin to access this.';
        }
        $this->set_response([
            'status' => $flag,
            'message' => $message,
            'data' => $params
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

    /*--------------End User Here---------------------------------------------*/

    function set_rules($option)
    {
        $this->load->library('form_validation');
        if ($option == 'EditProfile') {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('phone', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]'); //{10} for 10 digits number
        }
        if ($option == 'changePassword') {
            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required');
        }
        if ($option == 'submit_query') {
            $this->form_validation->set_rules('category_id', 'Category', 'required|trim|is_natural_no_zero', array('required' => 'Please Enter Category.'));
            $this->form_validation->set_rules('query', 'Query', 'required|trim', array('required' => 'Please Enter Query.'));
        }
        /*----Super admin and expert----*/
        if ($option == 'discard_query') {
            $this->form_validation->set_rules('query_id', 'Query Id', 'required|trim', array('required' => 'Query id not found.'));
        }
        /*----Super assign query to expert expert----*/
        if ($option == 'query_assign_to_expert') {
            $this->form_validation->set_rules('query_id', 'Query Id', 'required|trim', array('required' => 'Query id not found.'));
            $this->form_validation->set_rules('expert_id', 'Assign to expert', 'required|trim', array('required' => 'Please select expert.'));
        }
        /*----Expert resond answer----*/
        if ($option == 'respond_by_expert') {
            $this->form_validation->set_rules('assign_query_id', 'Assign Query Id', 'required|trim', array('required' => 'Assign Query id not found.'));
            $this->form_validation->set_rules('answer', 'Answer', 'required|trim', array('required' => 'Please enter Answer.'));
        }

        if ($option == 'review') {
            $this->form_validation->set_rules('assign_query_id', 'Assign Query Id', 'required|trim', array('required' => 'Assign Query id not found.'));
            $this->form_validation->set_rules('answer', 'Answer', 'required|trim', array('required' => 'Please enter Answer.'));
        }

        if ($option == 'get_answer_by_expert') {
            $this->form_validation->set_rules('assign_query_id', 'Assign Query Id', 'required|trim', array('required' => 'Assign Query id not found.'));
        }

        if ($option == 'get_expert_detail') {
            $this->form_validation->set_rules('expert_id', 'Assign to expert', 'required|trim', array('required' => 'Please select expert.'));
        }

        if ($option == 'insert_user') {

            $this->form_validation->set_rules('list_by', 'List By User', 'required|trim|callback_list_by');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check');
            // $this->form_validation->set_rules('password', 'Password', 'required|trim');
        }

        if ($option == 'update_user') {
            $this->form_validation->set_rules('list_by', 'List By User', 'required|trim|callback_list_by');
            $this->form_validation->set_rules('roleUserID', 'Role User Id', 'required|trim|integer');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check');
        }

        if ($option == 'view_profile_user' || $option == 'delete_user') {
            $this->form_validation->set_rules('roleUserID', 'Role User Id', 'required|trim|integer');
        }
    }

    public function useremail_check($str)
    {   
       
        $id = isset($_POST['roleUserID']) && $_POST['roleUserID'] > 0 ? $_POST['roleUserID'] : '';
        $condition = array('status!=' => '3');
        
        if (!empty($id) && is_numeric($id)) {
            $condition = array('id !=' => $id, 'status!=' => '3');
        }
        if ($this->common_model->_CheckExistence('email', $str, 'b_users', $condition)) {
           
            return true;
            
        } else {
            $this->form_validation->set_message('useremail_check', 'Whoops! User Email already exists. Please try with another User Email.');
           
            return false;
        }
    }

    public function list_by($str)
    {
        if ($str == 'end_user' || $str == 'expert_user') {
            return true;
        } else {
            $this->form_validation->set_message('list_by', 'Whoops! List by should be (end_user or expert_user).');
            return false;
        }
    }

      

    /*-- Bulk email Background mail send */
    public function sendemail()
    {
       // echo "hiiii"; die;
        if (!empty($_REQUEST)) {
            $sent_to = explode(',', $_REQUEST['1']);
            $categoryId = $_REQUEST['2'] != '0' ? explode(',', $_REQUEST['2']) : '0';
            $emailIDs = $_REQUEST['6'] != '' ? explode(',', $_REQUEST['6']) : '';
            $attachment_file = isset($_REQUEST['7']) && $_REQUEST['7'] != '' ? $_REQUEST['7'] : '';
            $subject = $_REQUEST['3'];
            $message = $_REQUEST['5'];
            $expertList = $endUserlist = $industryUserslist = $emailList = [];
            $fp = fopen('sendemailRequest.txt', 'w+');
            fwrite($fp, print_r($_REQUEST, 1));
            if (in_array('experts', $sent_to)) {
                $expertList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '1'));
                if (!empty($expertList)) {
                    $expertList = array_column($expertList, 'email');
                } else {
                    $expertList = array();
                }
            }
            if (in_array('users', $sent_to)) {
                $endUserlist = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '2'));
                if (!empty($endUserlist)) {
                    $endUserlist = array_column($endUserlist, 'email');
                } else {
                    $endUserlist = array();
                }
            }
            if (in_array('industrial_category', $sent_to)) {
                $this->db->select('email');
                $this->db->from('b_industrial_users');
                $this->db->where(array('status' => '1'));
                $this->db->where_in('category_id', $categoryId);
                $resSQL = $this->db->get();
                if ($resSQL->num_rows() > 0) {
                    $industryUserslist = $resSQL->result_array();
                }
                if (!empty($industryUserslist)) {
                    $industryUserslist = array_column($industryUserslist, 'email');
                } else {
                    $industryUserslist = array();
                }
            }
            if (in_array('emailid', $sent_to)) {
                $emailList = $emailIDs != '' ? $emailIDs : [];
            }
            $list = array_unique(array_merge($expertList, $endUserlist, $industryUserslist, $emailList));
            $fp = fopen('list.txt', 'w+');
            fwrite($fp, print_r($list, 1));
            if (!empty($list)) {
                foreach ($list as $email) {
                    $userEmail = $email;
                    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                        /*$mailContent = '<table><tr><td align="left" valign="top" style="padding:20px;"><h3>Hello,' . $email . '</h3>' . $message . '<br/><br/>Regards,<br/<br/>' . ADMIN_NOTIFICATION_TITLE . '</td></tr></table>';*/
                        $mailContent = $message;
                        sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $attachment_file);
                    }
                }
            }
        }
    }


 /*-- work By Saurabh ----- */

    public function manage_users_post()
    {  
        try
        {
         $params = $this->post();
         tryLogPrinter("manage_users", $params); 
         // print_r($params);die;
         if (!empty($params) && $params['purpose_hidden'] != "" && $params['csv_ids_hidden'] != "") {

            $userId = $params['csv_ids_hidden'];

            $resultData = explode(',', $userId);
            //print_r($resultData);die;
            $status_id = $params['purpose_hidden'];
            
            if ($status_id == '1' || $status_id == '2' || $status_id == '3') {
                if ($status_id == "1") {
                    $status = 'Activate';
                } else if ($status_id == "2") {
                    $status = 'Blocked';
                } else if ($status_id == "3") {
                    $status = 'Deleted';
                }

                if ($this->common_model->updateWhereIn('b_users', array('status' => $status_id), 'id', $resultData)) {
                    $message = 'Expert ' . $status . ' successfully.';
                }
            } elseif ($status_id == '4') {
                foreach ($resultData as $user_id) {
                    $user = $this->common_model->_selectById("b_users", 'name,email', array("id" => $user_id));
                    $keywords = array('NAME' => $user['name']);
                    SendEmailByTemplate(15, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                }
                $message = 'User Bulk Email sent successfully';
            }
              $response = [
            'status' => true,
            'message' =>  $message,
            'data' => $params,
        ];
        $this->apiSetOutput($response);
        }else{
        	$response = [
            'status' => false,
            'message' =>  "Something is Missing!",
            'data' => $params,
        ];
        $this->apiSetOutput($response);
        }
   }//try

catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 

    }

    public function manage_end_users_post(){
        try
        {
        $params = $this->post();
        tryLogPrinter("manage_end_users", $params); 
        if (!empty($params) && $params['purpose_hidden'] != "" && $params['csv_ids_hidden'] != "") {
            $userId = $params['csv_ids_hidden'];
            $resultData = explode(',', $userId);
            $status_id = $params['purpose_hidden'];
            if ($status_id == '1' || $status_id == '2' || $status_id == '3' || $status_id == '0') {
                if ($status_id == "1") {
                    $status = 'Activate';
                } else if ($status_id == "2") {
                    $status = 'Blocked';
                } else if ($status_id == "3") {
                    $status = 'Deleted';
                } else if ($status_id == "0") {
                    $status = 'Pending';
                }
                if ($this->common_model->updateWhereIn('b_users', array('status' => $status_id), 'id', $resultData)) {
                    $message = 'End User ' . $status . ' successfully.';
                }
            } elseif ($status_id == '4') {
                foreach ($resultData as $user_id) {
                    $user = $this->common_model->_selectById("b_users", 'name,email', array("id" => $user_id));
                    $keywords = array('NAME' => $user['name']);
                    SendEmailByTemplate(15, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                }
                $message = 'User Bulk Email sent successfully';
            }
            $response = [
            'status' => true,
            'message' =>  $message,
            'data' => $params,
        ];
        $this->apiSetOutput($response);
        }else{
        	 $response = [
            'status' => false,
            'message' =>  "Something is Missing!",
            'data' => $params,
        ];
        $this->apiSetOutput($response);
        }
    }//try

    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 

    }





   public function bulkEmailNotification_post()
    {

         $attachment_file = $_FILES;
           $params = $this->post();
            tryLogPrinter("bulkEmailNotification", $params); 
         //print_r($params);
         //print_r($file);
         //die();
          
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

          if (isset($attachment_file)) {
             $this->bulk_handle_upload();
          }
              // using this function attachement will be gone on user email id.
           
              $isValidated = true;
              $this->set_rules('respond_by_expert');
                
                $sent_to = $params['sent_to'];
                //print_r($sent_to);die;
                if (in_array('industrial_category', $sent_to)) {
                    $category_id = $params['category_id'];
                    if (empty($category_id)) {
                        $isValidated = false;
                        setSessionFlashData('error', 'Industrial Category Field is required');
                    }
                }
                if (in_array('emailid', $sent_to)) {
                    $email_id = $params['email'];
                    if (empty($email_id)) {
                        $isValidated = false;
                        setSessionFlashData('error', 'Email Field is required');
                    }
                }
                if ($isValidated) {
                    $message = closetags(trim($params['message']));
                    $full_path = '""';
                    if (isset($this->upload_data) && $this->upload_data['file_name']) {
                        $full_path = $this->upload_data['full_path'];
                    }
                    $subject = trim($params['subject']);

                    $expertList = $endUserlist = $industryUserslist = $emailList = $Md = $Admin =  [];

                    if (in_array('experts', $sent_to)) {

                        $expertList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '1'));
                        if (!empty($expertList)) {
                            $expertList = array_column($expertList, 'email');
                        } else {
                            $expertList = array();
                        }
                    }
                    if (in_array('users', $sent_to)) {
                        $endUserlist = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '2'));
                        if (!empty($endUserlist)) {
                            $endUserlist = array_column($endUserlist, 'email');
                        } else {
                            $endUserlist = array();
                        }
                    }
                    if (in_array('industrial_category', $sent_to)) {
                        $this->db->select('email');
                        $this->db->from('b_industrial_users');
                        $this->db->where(array('status' => '1'));
                        $this->db->where_in('category_id', $category_id);
                        $resSQL = $this->db->get();
                        if ($resSQL->num_rows() > 0) {
                            $industryUserslist = $resSQL->result_array();
                        }
                        if (!empty($industryUserslist)) {
                            $industryUserslist = array_column($industryUserslist, 'email');
                        } else {
                            $industryUserslist = array();
                        }
                    }
            
          /*  ------------------------------------------------------------*/

                      if (in_array('Md', $sent_to)) {
                        $Md = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '3'));
                        if (!empty($Md)) {
                            $Md = array_column($Md, 'email');
                        } else {
                            $Md = array();
                        }
                    }


                    if (in_array('Admin', $sent_to)) {
                        $Admin = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '0'));
                        if (!empty($Admin)) {
                            $Admin = array_column($Admin, 'email');
                        } else {
                            $Admin = array();
                        }
                    }

          /* ------------------------------------------------------------*/


                    if (in_array('emailid', $sent_to)) {
                        $emailList = explode(',', trim($params['email']));
                    }

                    $list = array_unique(array_merge($expertList, $endUserlist, $industryUserslist, $Md, $Admin, $emailList));
                     $emailLog = array();

                        $sent_to_1 = implode(',', $sent_to);
                        $emailid = implode(',', $emailList);

                        //foreach($list as $row) {

                        $emailLog[] = array(

                            "sender_user_id" => $this->UserDetail['id'],
                            "email" => $row,
                            "sender_user_type" => $sent_to_1,
                            "email" => $emailid,
                            "subject" => $subject,
                            "message" => nl2br($message),
                            "attachements" => $full_path,
                            "date" => date("Y-m-d")
                        );
						
						/* $myfile = fopen("emailbulk.txt", "w") or die("Unable to open file!");

fwrite($myfile, print_r($emailLog,1));
fclose($myfile);die; */
                        //}
                        //     print_r($emailid);
                        // print_r($emailLog);die;

                        $this->db->insert_batch("b_bulk_email_log", $emailLog);

                        // For Bulk email Log end


                        /* $sender_email_id = $this->UserDetail['id'];*/
                        if (!empty($list)) {

                            $url = base_url('sendemail');
                            $sent_to = implode(',', $sent_to);

                            $emails = trim(_inputPost('email'));
                            $emailIDs = $emails != '' ? $emails : '""';
                            $category_id = !empty($category_id) ? implode(',', $category_id) : '0';
                            $message = escapeshellarg(($message));
                            $subject = escapeshellarg(($subject));

                            $cmd = FCPATH . 'send_request.php';
                            //print_r($cmd);die;
                            //$command = "start /B php $cmd $sent_to $category_id $subject $url $message $emailIDs $full_path  > NUL";
                            // print_r($sent_to );die;
                            $command = "/usr/bin/php $cmd $sent_to $category_id $subject $url $message $emailIDs $full_path > /dev/null 2>&1 &";
                   //print_r($full_path); die;

                    // if (!empty($list)) {

                    //     $url = base_url('sendemail');
                    //     $sent_to = implode(',', $sent_to);
                    //     $emails = trim($params['email']);
                    //     $emailIDs = $emails != '' ? $emails : '""';
                    //     $category_id = !empty($category_id) ? implode(',', $category_id) : '0';
                    //     $message = escapeshellarg(($message));
                    //     $subject = escapeshellarg(($subject));
                       
                    //     $cmd = FCPATH . 'send_request.php';
                    //     // print_r($cmd); die;
                    //     //$command = "start /B php $cmd $sent_to $category_id $subject $url $message $emailIDs $full_path  > NUL";
                    //     $command = "/usr/bin/php $cmd $sent_to $category_id $subject $url $message  $emailIDs $full_path > /dev/null 2>&1 &";
                        
                      exec($command);
                        $smsg = 'Bulk Email has been sent successfully.';
                        setSessionFlashData('success', $smsg);
                       $response = [
                        'status' => true,
                        'message' => $smsg,
                        'data' => $params,
                         'attachment' => $attachment_file,
                            ];
                        $this->apiSetOutput($response);
                    } else {
                        setSessionFlashData('error', 'No active users found');
                    }
                
            }
        }
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $userDetail,
        ];
        $this->apiSetOutput($response);
    }




  function bulk_handle_upload()
    {
        if (isset($_FILES['attachment_file']) && !empty($_FILES['attachment_file']['name'])) {
            if (!file_exists("assets/uploads/attachment_file")) {
                mkdir("assets/uploads/attachment_file", 0777, true);
            }
            $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
            $rand_val = date('YMDHIS') . rand(11111, 99999);
            $filename = md5($rand_val) . "." . $imgInfo;
            $_FILES['attachment_file']['name'] = $filename;
            $config2['upload_path'] = "assets/uploads/attachment_file";
            $config2['allowed_types'] = "*";
            $config2['max_size'] = "2048";
            $config2['remove_spaces'] = TRUE;
            $this->load->library('upload', $config2);
            $this->upload->set_upload_path($config2['upload_path']);
            $this->upload->initialize($config2);
            if ($this->upload->do_upload('attachment_file')) {
                // set a $_POST value for 'image' that we can use later
                $this->upload_data = $this->upload->data();
                return true;
            } else {
                // possibly do some clean up ... then throw an error
                $this->form_validation->set_message('handle_upload', $this->upload->display_errors());
                return false;
            }
        }
    }




  function bulk_user_upload($filename)
    {
       
        if (isset($_FILES['userFile']) && !empty($_FILES['userFile']['name'])) {
            if (!file_exists("assets/uploads/users/")) {
                mkdir("assets/uploads/users/", 0777, true);
            }
            $imgInfo = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
            $rand_val = date('YMDHIS') . rand(11111, 99999);
            $filename = md5($rand_val) . "." . $imgInfo;
            $_FILES['userFile']['name'] = $filename;
            $config2['upload_path'] = "assets/uploads/users/";
            $config2['allowed_types'] = "*";
            $config2['max_size'] = "2048";
            $config2['remove_spaces'] = TRUE;
            $this->load->library('upload', $config2);
            $this->upload->set_upload_path($config2['upload_path']);
            $this->upload->initialize($config2);
            if ($this->upload->do_upload('userFile')) {
                // set a $_POST value for 'image' that we can use later
                $this->upload_data = $this->upload->data();
                return $filename;
            } 
        }
    }







/* ------- Md Dashboard-----*/
function reports_post()
    {  
        try
          {
            /*-----------------------------------------------------------------------*/

                $condition = array('q.status!=' => '1');
                $result = $this->user_model->getNewQuery('b_queries',  $this->start, $this->perPage, '*', $condition);
                $this->viewData['new'] = $result['total_rows'];
            /*-----------------------------------------------------------------------*/

                $condition = array('q.status!=' => '1');
                $result1 = $this->user_model->getRespondQuery('b_query_assign',  $this->start, $this->perPage, '*', $condition);
                $this->viewData['pending'] = $result1['total_rows'];
            /*-----------------------------------------------------------------------*/  
            
                $condition2 = array('qa.status' => '1', 'review_status' => '1');
                $result2 = $this->user_model->getRespondedQuery('b_query_assign',  $this->start, $this->perPage, '*', $condition2);
                $this->viewData['closed'] = $result2['total_rows'];
            /*-----------------------------------------------------------------------*/
             
                $this->viewData['total'] = (string)$this->db->count_all('b_queries');
                
                //print_r($this->viewData);die;
                $response = [
            'status' => true,
            'message' =>  "All Count",
            'data' => $this->viewData,
        ];
        $this->apiSetOutput($response);
            /*-----------------------------------------------------------------------*/
        }//try
        catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 
    } 
     function mdTotalQueriesReports_post()
    {
        try
        {
             tryLogPrinter("mdTotalQueriesReports", $params); 
        customPagination();  // for generating the pagination HTML
   
        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdManager');
        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
           
            $start = validateURI(3) != '' ? validateURI(3) : '0';

            $getData['page'] = $start;
            //echo  $getData['page']; die;
        } else {

            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdTotalQueriesReports_count($conditionArray);

        $userData = $this->user_model->get_mdTotalQueriesReports($this->start, $this->perPage, $conditionArray);
       // print_r($userData);die;
        $message = "Md Total Queries Reports";
       // if ($userData) { 

            $response = [
            'status' => true,
            'message' =>  $message,
            'data' => $userData,
        ];
        $this->apiSetOutput($response);
        //}
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 

       
    }


  function mdNewQueriesReports_post()
    {
        
        try
        {
             tryLogPrinter("mdNewQueriesReports", $params); 
        customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters

        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdNewManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdNewManager');

        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdNewQueriesReports_count($conditionArray);

        $userData = $this->user_model->get_mdNewQueriesReports($this->start, $this->perPage, $conditionArray);
        // print_r($userData);die;
        $message = "Md New Queries Reports";
       

            $response = [
            'status' => true,
            'message' =>  $message,
            'data' => $userData,
        ];
        $this->apiSetOutput($response);
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 
    }


function mdClosedQueriesReports_post()
    {
        
       try
       {
         tryLogPrinter("mdClosedQueriesReports", $params); 
       customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters

        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdClosedManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdClosedManager');

        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdClosedQueriesReports_count($conditionArray);

        $userData = $this->user_model->get_mdClosedQueriesReports($this->start, $this->perPage, $conditionArray);
        //print_r($userData);die;
        $message = "Md clossed Queries Reports";
       // if ($userData) { 

            $response = [
            'status' => true,
            'message' =>  $message,
            'data' => $userData,
        ];
        $this->apiSetOutput($response);
        //}
    }//try

    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 

       
    }


function mdPendingQueriesReports_post()
    {
        try
        {
             tryLogPrinter("mdPendingQueriesReports", $params); 
        customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters

        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdPendingManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdPendingManager');

        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdPendingQueriesReports_count($conditionArray);


        $userData = $this->user_model->get_mdPendingQueriesReports($this->start, $this->perPage, $conditionArray);
        //print_r($userData);die;
        $message = "Md pending Queries Reports";
       // if ($userData) { 

            $response = [
            'status' => true,
            'message' =>  $message,
            'data' => $userData,
        ];
        $this->apiSetOutput($response);
        //}
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 

       
    }


public function respond_by_md_post()
    { 
        try
        {
            tryLogPrinter("respond_by_md", $params); 
             if ($_FILES) {
                //$filename       =            $_FILES['attachment_file']['name'] ; 
                $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $rand_val = date('YMDHIS') . rand(11111, 99999);
                 $filename = md5($rand_val) . "." . $imgInfo;
                $_FILES['attachment_file']['name'] = $filename;
            
                $config['upload_path']          = "assets/uploads/attachment_file";
                $config['allowed_types']        = 'gif|jpg|png|jpeg|mp4|doc|docx|pdf';
                $config['max_size']             = 100000;
                $config['max_width']            = 100024;
                $config['max_height']           = 10768;
                $attachment =  "assets/uploads/attachment_file/".$filename;
                $this->load->library('upload', $config);
                $this->upload->do_upload('attachment_file'); 
            }


            if (!empty($_POST)) {
                $failure = false;
              
                $answer = $this->input->post('answer');
                
                 $id= $this->input->post('id');
                
               
                    $dataArray['expert_answer'] = $answer;
                    $dataArray['status'] = '1';
                     if ( $attachment) {
                                $dataArray['upload_image'] =$attachment;
                                  }
                    $dataArray['respond_Date'] = set_local_to_gmt();
                    //print_r($dataArray);die;
                    $this->common_model->_update('b_query_assign', $dataArray, ['id' => $id]);
                    if ($id) {
                        /*----mail send to admin----*/
                        $queryAssignData = $this->common_model->_selectById('b_query_assign', 'query_id', ['id' => $id]);
                        $queryData = $this->common_model->_selectById('b_queries', 'query', ['id' => $queryAssignData['query_id']]);
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->loginUserData['name'], 'SENDER_EMAIL' => $this->loginUserData['email'], 'QUERY' => $queryData['query'], 'RESPOND_MESSAGE' => $answer);
                         SendEmailByTemplate(12, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->loginUserData['name']);
                        $success_message = ' Your respond has been submitted.';
                        setSessionFlashData('success', $success_message);
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                   
                    $data['message'] = $success_message;
                }
                $response = [
            'status' => true,
            'message' =>  $success_message,
            'data' => $this->loginUserData,
        ];
          $this->apiSetOutput($response);
            }
        }//try
        catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 

      
        }

public function assign_to_md_post()
                {

              try
                {
                 tryLogPrinter("assign_to_md", $params); 
                    if (!empty($_POST)) {
                        $failure = false;
                     
                        $md_id = $this->input->post('md_id');
                         $id = $this->input->post('query_id');
                       
                        
                            $dataArray['user_id'] = $md_id;
                            $dataArray['query_id'] = $id;
                            $dataArray['added_on'] = set_local_to_gmt();
                            $userID = $this->common_model->_insertReturnId('b_query_assign', $dataArray);
                            if ($userID) {
                                $success_message = ' Your query has been assigned to MD.';
                                /*----mail send to expert user----*/
                               $queryData = $this->common_model->_selectById('b_queries', 'user_id,query', ['id' => $id]);
                               $expertUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $expert_id));
                                 $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                                $keyword = array('RECEIVER_NAME' => $expertUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'QUERY' => $queryData['query']);
                                SendEmailByTemplate(10, $keyword, $expertUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);
                            } else {
                                $error_message = 'Whoops! Something went wrong. Please try again later.';
                                $failure = true;
                            }
                        }
                        if ($failure) {
                            $data['success'] = false;
                            $data['message'] = $error_message;
                        } else {
                            $data['message'] = $success_message;
                            $data['success'] = true;
                            $data['resetform'] = true;
                            $data['selfReload'] = true;
                            $data['hideModel'] = true;
                        }
                        $response = [
                    'status' => true,
                    'message' =>  $success_message,
                    'data' => $this->loginUserData,
                ];
                  $this->apiSetOutput($response);
            }//try
      catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                 $response = [
                    'status' => $flag,
                    'message' =>  $message,
                    'data' => array(),
                ];
                $this->apiSetOutput($response);

            } // catch 
        }



            // $this->viewData['queryData'] = $this->user_model->getQueryById($id);
            // $assignExperts = $this->common_model->_select('b_query_assign', 'user_id', ['query_id' => $id]);
            // $expertIds = '';
            // if (!empty($assignExperts)) {
            //     $expertIds = ' AND id NOT IN (' . implode(',', array_column($assignExperts, 'user_id')) . ')';
            // }
            

            // $this->viewData['type'] = $type;
            // if($type == 1)
            // {
            //   $condition = " status = '1' AND role_type = '1'  " . $expertIds;
            //    $this->viewData['experts'] = $this->common_model->getMultipleRecord('b_users', $condition, 'id,name,email');
            //   $this->viewData['title'] = ADMIN_COMPANY . " |  Assign to Expert";
            // }
            // else
            // {
            //   // for md users   -- done by monu from 23-04-2020
            // $condition1 = " status = '1' AND role_type = '3'  " . $expertIds;
            //     $this->viewData['experts'] = $this->common_model->getMultipleRecord('b_users', $condition1, 'id,name,email');
            //   $this->viewData['title'] = ADMIN_COMPANY . " |  Assign to MD";
            // }

            
            // $this->load->view('Modals/Assign_to_Expert', $this->viewData);

         public function assign_to_expert_list_post(){


         $query_id = $this->input->post('query_id');
  

         $this->viewData['queryData'] = $this->user_model->getQueryById($query_id);
            $assignExperts = $this->common_model->_select('b_query_assign', 'user_id', ['query_id' => $query_id]);

            $expertIds = '';
            if (!empty($assignExperts)) {
                $expertIds = ' AND id NOT IN (' . implode(',', array_column($assignExperts, 'user_id')) . ')';
            }
            

         
              $condition = " status = '1' AND role_type = '1'  " . $expertIds;
               $this->viewData['experts'] = $this->common_model->getMultipleRecord('b_users', $condition, 'id,name,email,image');
                $response = [
            'status' => true,
            'data' => $this->viewData['experts'],
            
        ];
             $this->apiSetOutput($response);
            

        }

        public function assign_to_md_list_post(){


         $query_id = $this->input->post('query_id');
  

         $this->viewData['queryData'] = $this->user_model->getQueryById($query_id);
            $assignExperts = $this->common_model->_select('b_query_assign', 'user_id', ['query_id' => $query_id]);

            $expertIds = '';
            if (!empty($assignExperts)) {
                $expertIds = ' AND id NOT IN (' . implode(',', array_column($assignExperts, 'user_id')) . ')';
            }
            

         
              $condition = " status = '1' AND role_type = '3'  " . $expertIds;
               $this->viewData['experts'] = $this->common_model->getMultipleRecord('b_users', $condition, 'id,name,email,image');
                $response = [
            'status' => true,
            'data' => $this->viewData['experts'],
            
        ];
             $this->apiSetOutput($response);
            

        }
        public function  getTheUserType_post(){
            
             $user_id = $this->input->post('ans_user_id');
            //echo $user_id; die;
        $query = $this->db->select("role_type")->get_where('b_users', array('id' => $user_id));
        $res = $query->row_array();

           $response = [
            'status' => true,
            'data' => $res,
            
        ];
             $this->apiSetOutput($response);
            
       // print_r($res);die;
        // $typeUser = $res["role_type"];
        // echo $typeUser;
        }

        public function  deleteAttachment_post(){
            
             $user_id = $this->input->post('assign_query_id');

             // $this->db->select('upload_image');
             // $this->db->from('b_query_assign');
             // $this->db->where('id', $user_id);   
             // $path =  $this->db->get()->result()->row('upload_image');

             // $this->load->helper("file");
             // delete_files($path);


            $data = array('upload_image' => '');
            $this->db->where('id', $user_id);        
            $this->db->update('b_query_assign', $data);
              $msg = 'The attachment has been deleted.';
           $response = [ 
            'status' => true,
            'message' => $msg,
            
        ];
             $this->apiSetOutput($response);
            
       // print_r($res);die;
        // $typeUser = $res["role_type"];
        // echo $typeUser;
        }
    

}

/* End of file Users.php */
/* Location: ./application/controllers/api/Users.php */
