<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MX_Controller
{
    var $perPage = '10';
    var $segment = '4';
    public $viewData = array();
    public $loggedInAdmin = array();

    public function __construct()
    {
        parent::__construct();
        isLoggedIn($type = 'admin');
        $this->loggedInAdmin = getSessionUserData('auth_admin_data');
        $this->load->model('admin_model');
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->load->model('pages_model');

        customPagination();
    }


    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $getData['page']=$isAll;
        $getData=$this->input->get();
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('PagesManager');
        }
        $prevSessData = getSessionUserData('PagesManager');
        $conditionArray=$prevSessData;
        if($isAll!='all')
        {
            $start =validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page']=$start;
        }
        else{
            $start='0';
            $getData['page']='';
        }
        $this->viewData['title'] = 'Admin Panel | Pages Manager';
        $this->viewData['data'] = array();
        $getField= $this->input->get();
        $sortField= isset($prevSessData['sort']['field'])?$prevSessData['sort']['field']:'id';
        $order= isset($prevSessData['sort']['order'])?$prevSessData['sort']['order']:'desc';
        $page_num = (int)$this->uri->segment(4);
        if($page_num==0) $page_num=1;
        if($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $pagesDataCount = $this->pages_model->record_count($conditionArray);
        $pagesData = $this->pages_model->get_pages($start, $this->perPage,$conditionArray);
        $pagesPagination = createPagination('admin/pages/index',$pagesDataCount,$this->perPage,$this->segment,$getField);
        $this->viewData['pagination'] = $pagesPagination;
        $this->viewData['dbdata'] = $pagesData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->viewData['FormData'] =$prevSessData;
        $this->load->view('Pages/List',$this->viewData);
    }



    public function edit()
    {
        $pagesId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($pagesId != '') {
            $dbdata = $this->pages_model->_selectById(array('id'=>$pagesId));
            if (!empty($dbdata)) {
                $this->set_rules('editPages');
                            if ($this->form_validation->run($this) == true) {

                                if($pagesId == 2)
                                {
                         if(isset($_FILES['userfile']) && !empty($_FILES['userfile'] && $_POST['name']))
                            {
                                    
                                       $this->load->library('upload');
                                       $dataInfo = array();
                                       $files = $_FILES;
                                       $cpt = count($_FILES['userfile']['name']);
                                       for ($i=0; $i < $cpt; $i++) { 
                                      
                                         $_FILES['userfile']['name']= $files['userfile']['name'][$i];
                                        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
                                        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
                                        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
                                        $_FILES['userfile']['size']= $files['userfile']['size'][$i]; 
                                       
                                        $this->upload->initialize($this->set_upload_options());
                                        $this->upload->do_upload();
                                        $dataInfo[] = $this->upload->data();
                                        
                                        $data = array(
                                            'value' => base_url()."assets/uploads/knowledgedoc/".$dataInfo[$i]['file_name'],
                                            'name' => $_POST['name'][$i],
                                            'type' => 1,
                                        );
                                        //$this->db->insert("knowledge_center_links_doc", $data);
                                       
                        if(!empty($_FILES['userfile']['name']))  // if file is pdf then it will upload the file inside the folder
                        { 
                           $this->db->insert("knowledge_center_links_doc", $data);
                       }

                                           // if ($_FILES['userfile']['name'][$i]) {
                                            //   $this->uploaddocument($_POST['name'][$i]);
                                            // }

                    }
               }
               
               if(count($_POST['link_name'])>0)
               {
                 
                 //print_r($_POST['namee'][1]);die;
                  for ($i=0; $i < count($_POST['link_name']); $i++) { 
                   $data = array(
                    'value' => trim($_POST['link_name'][$i]),
                    'name' => $_POST['namee'][$i],
                    'type' => 0,
                );

                   if ($_POST['link_name'][$i]) {
                    $this->db->insert("knowledge_center_links_doc", $data);
                }

            }

            }

            }
            $dataArray['content'] = $this->input->post('description');                   

            $this->common_model->_update('b_pages', $dataArray,array('id'=>$pagesId));
            setSessionFlashData('success', 'You have successfully updated detail');
            redirect(base_url('admin/pages'));
            }
            }

            $this->viewData['dbdata'] = $dbdata;
            $this->viewData['pagesid'] = $pagesId;
            $this->viewData['title'] = "Admin | Manage pages";

            if($pagesId == 2)
            {   
                $this->viewData['link'] = $this->db->get_where("knowledge_center_links_doc", array("type"=>0))->result_array();
                $this->viewData['doc'] = $this->db->get_where("knowledge_center_links_doc", array("type"=>1))->result_array();
                $this->load->view('Pages/knowledgeedit', $this->viewData);
            }
            else
            {
                $this->load->view('Pages/Edit', $this->viewData);
            }
            
        }
        else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('admin/pages'));
        }
    }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error">', '</span>');
        if ($option == 'editPages') {
            $this->form_validation->set_rules('description', 'Content Field', 'required');
        }

    }


    public function uploaddocument($name)
    {       
       
        $this->load->library('upload');
        $dataInfo = array();
        $files = $_FILES;
        $cpt = count($_FILES['userfile']['name']);
        $uploadcount = 0;
        for($i=0; $i<$cpt; $i++)
        {           
            $_FILES['userfile']['name']= $files['userfile']['name'][$i];
            $_FILES['userfile']['type']= $files['userfile']['type'][$i];
            $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
            $_FILES['userfile']['error']= $files['userfile']['error'][$i];
            $_FILES['userfile']['size']= $files['userfile']['size'][$i]; 
            $this->upload->initialize($this->set_upload_options());
            $this->upload->do_upload();
            $dataInfo[] = $this->upload->data();

            $data = array(
            'value' => base_url()."assets/uploads/knowledgedoc/".$dataInfo[$i]['file_name'],
            'name' => $name,
            'type' => 1,
         );
            if(!empty($_FILES['userfile']['name']))  // if file is pdf then it will upload the file inside the folder
               { 
                 $this->db->insert("knowledge_center_links_doc", $data);
               }
        }

    } // uploaddocument


    private function set_upload_options()
        {   
            //upload an image options
            $config = array();
            $config['upload_path'] = 'assets/uploads/knowledgedoc';
            $config['allowed_types'] = '*';
            /*$config['max_size']      = '0';*/
            $config['overwrite']     = FALSE;
            return $config;
        }  //set_upload_options



function deletelinkdoc($id, $editid)
    {
      $this->db->where("id", $id);
      if($this->db->delete("knowledge_center_links_doc"))
      {
        setSessionFlashData('success', 'Link removed successfully.');
            
      }
      else
      {
        setSessionFlashData('error', 'Something went wrong....');
      }
       redirect(base_url('admin/pages/edit/'.$editid));
    }
}
