<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribers extends MX_Controller
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
        $this->load->model('subscriber_model');
        customPagination();
    }

    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $isExport =isset($FormData['is_export']) && $FormData['is_export']=='1' ? 1 : 0;
        $getData = $this->input->get();
        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $newsletterId = $FormData['csv_ids_hidden'];
            $result = explode(',', $newsletterId);
            $status_id = (int)$FormData['purpose_hidden'];
            if (in_array($status_id, array(0, 1, 2))) {
                $dbArray = ['status' => $status_id];
                if ($status_id == 1) {
                    $status = 'Activate';
                } else if ($status_id == 0) {
                    $status = 'Deactivated';
                } else if ($status_id == 2) {
                    $status = 'Deleted';
                    unset($dbArray['status']);
                    $dbArray['is_deleted'] = 1;
                }
                $isExport=0;
                if ($this->common_model->updateWhereIn('b_subscriber', $dbArray, 'id', $result)) {
                    $message = 'Subscriber ' . $status . ' successfully.';
                }
                setSessionFlashData('success', $message);
            } else {
                setSessionFlashData('error', 'Invalid action');
                redirect(base_url('admin/subscribers'));
            }
        }
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('SubscriberManager');
        }
        $prevSessData = getSessionUserData('SubscriberManager');
        $conditionArray = $prevSessData;
        $conditionArray['equal']['is_deleted'] = 0;
        if ($isAll != 'all') {
            $start = validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }

        $this->viewData['data'] = array();
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['order'] : 'desc';
        $page_num = (int)$this->uri->segment(4);
        if ($page_num == 0) $page_num = 1;
        $contactDataCount = $this->subscriber_model->record_count('b_subscriber', $conditionArray);
        $contactData = $this->subscriber_model->get_records('b_subscriber', $start, $this->perPage, $conditionArray);
        if ($isExport) {
            $result = $this->subscriber_model->get_records('b_subscriber', '', '', $conditionArray);
            $this->createExcel($result);
            exit();
        }
        $pagination = createPagination('admin/subscribers/index', $contactDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $pagination;
        $this->viewData['dbdata'] = $contactData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['start'] = $start;
        //sorting
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order;
        $this->viewData['sorting_class'] = 'sorting_' . $order;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['title'] = 'Admin Panel | Manage Newsletter Subscribers';
        $this->load->view('Subscriber/newsletter_subscribers', $this->viewData);
    }

    public function createExcel($result)
    {
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        if (!empty($result)) {
            $i = 1;
            $j = 1;
            //////////header//////////////////
            $objPHPExcel->getSheet(0)
                ->setCellValue('A' . $i, 'Id')
                ->setCellValue('B' . $i, 'Email')
                ->setCellValue('C' . $i, 'Status');

            $objPHPExcel->getSheet(0)->getStyle('A' . $i . ':F' . $i)->getFont()->setBold(true);
            $objPHPExcel->getSheet(0)->getColumnDimension('A')->setWidth(10);
            $objPHPExcel->getSheet(0)->getColumnDimension('B')->setWidth(20);
            $i++;
            foreach ($result as $row) {
                $objPHPExcel->getSheet(0)
                    ->setCellValue('A' . $i, $j)
                    ->setCellValue('B' . $i, $row['email'])
                    ->setCellValue('C' . $i, $row['status']=='1' ? 'Active' : 'Deactivated');
                $i++;
                $j++;
            }
            // Rename worksheet
            $objPHPExcel->getSheet()->setTitle('Subscribers');
            $objPHPExcel->setActiveSheetIndex(0);
            $file = url_title('Export-' . date('d-m-Y H-i-s a')) . '.xlsx';
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $file);
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Pragma: public'); // HTTP/1.0
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save("assets/uploads/file_subscriber" . "/" . $file);
            $objWriter->save('php://output');
            exit;
        }
    }
}