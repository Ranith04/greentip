<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
    public $table = 'b_users';
    public $primary_key = 'id'; // you MUST mention the primary key

    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Update a user, password will be hashed
     *
     * @param int id
     * @param array user
     * @return int id
     */
    public function getDetails($user_id)
    {
        $strSQL = "SELECT * FROM b_users WHERE id=$user_id ORDER BY id DESC ";
        //echo $strSQL;exit;
        $resSQL = $this->db->query($strSQL);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->result_array();
            return $result;
        } else {
            return false;
        }
    }

    public function get_all_new($col = '*', $where = array(), $order_by = 'full_name', $sort = 'DESC')
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->select($col);
        $this->db->where(array('status' => '1'));
        $this->db->order_by($order_by, $sort);
        $cities = $this->db->get($this->table)->result_array();
        $result = array();
        foreach ($cities as $row) {
            $crop_url = base_url() . 'assets/uploads/users/';
            $row['image'] = $crop_url . $row['image'];
            $result[] = $row;

        }
        return $result;
    }

    public function get_new($where = array())
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where(array('status' => '1'));
        $cities = $this->db->get($this->table)->row_array();
        return $cities;
    }


    /**
     * Check if a user exists
     *
     * @param string where
     * @param int value
     * @param string identification field
     */

    public function exists($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }

        return $this->db->where($where, $value)->count_all_results($this->table);
    }

    /**
     * Password hashing function
     *
     * @param string $password
     */
    public function hash($password)
    {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));

        // hash password
        return $this->passwordhash->HashPassword($password);
    }

    /**
     * Compare user input password to stored hash
     *
     * @param string $password
     * @param string $stored_hash
     */
    public function check_password($password, $stored_hash)
    {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
        // check password
        return $this->passwordhash->CheckPassword($password, $stored_hash);
    }

    public function record_count($condition)
    {
        $this->db->select($this->table . '.*');
        SetCondition($condition);

        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data = $query->num_rows();
        }
        return $data;
    }

    public function get_users($start, $limit, $condition = array())
    {
        $this->db->select($this->table . '.*');
        SetCondition($condition);

        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
        }
        return $data;
    }

    public function profile($id = '', $email = '', $condition = array())
    {
        $this->db->select('b_users.*');
        $this->db->from('b_users');
        if ($id != '') {
            $this->db->where('b_users.id', $id);
        } else if ($email != '') {
            $this->db->where(array('email' => $email));
        } else if (count($condition) > 0) {
            $this->db->where($condition);
        }


        $this->db->where(array('b_users.status!=' => '3'));
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $data = $query->row_array();
            return $data;
        } else {
            return false;
        }
    }

    public function get_by_col($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }

        $user = $this->db->where($where, $value)->get($this->table)->row_array();
        return $user;
    }

    public function getQueryEnduser($tbl = 'b_queries', $current_page = '', $per_page = '', $col = '*', $condition = array())
    {
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,qa.id as assign_query_id,c.cat_name as category_name,qa.answer,qa.admin_review_date,qa.added_on as answer_date,qa.user_id as expert_id,u.image as enduser_image', false);
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
         if ($this->input->get('category_idd')=='1') {
            
           $this->db->where('q.category_id =', 1);
        }
        if ($this->input->get('category_idd')=='2') {
            
           $this->db->where('q.category_id =', 2);
        }
        if ($this->input->get('category_idd')=='3') {
            
           $this->db->where('q.category_id =', 3);
        }
        if ($this->input->get('category_idd')=='4') {
            
           $this->db->where('q.category_id =', 4);
        }
        if ($this->input->get('category_idd')=='5') {
            
           $this->db->where('q.category_id =', 5);
        }
        if ($this->input->get('category_idd')=='6') {
            
           $this->db->where('q.category_id =', 6);
        }
        if ($this->input->get('category_idd')=='7') {
            
           $this->db->where('q.category_id =', 7);
        }
        if ($this->input->get('category_idd')=='8') {
            
           $this->db->where('q.category_id =', 8);
        }
        if ($this->input->get('category_idd')=='9') {
            
           $this->db->where('q.category_id =', 9);
        }
        /* $this->db->where('SELECT count(qa.*)  FROM  b_query_assign as qa WHERE  qa.query_id = q.id) < 1');
         $this->db->where('NOT EXISTS (SELECT qa.* FROM   b_query_assign as qa WHERE  qa.query_id = q.id)');*/
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        $this->db->join('b_query_assign as qa', 'q.id = qa.query_id and qa.status = "1" and qa.review_status = "1"  ', 'LEFT');
         if ($this->input->get('short_by')=='old') {
            
            $this->db->order_by('q.id', 'ASC');
        }
        if ($this->input->get('short_by')=='new') {
           
           $this->db->order_by('q.id', 'DESC');
        }

          
         
        if ($per_page > 0) {
            $this->db->limit($per_page, $current_page);
        }

        $resSQL = $this->db->get($tbl . ' as q');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        $result = array();
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = array();
                if ($row['expert_id'] > 0) {
                    $answerData['answer'] = $row['answer'] != '' ? $row['answer'] : '';
                    $answerData['answer_date'] = $row['admin_review_date'] != '' ? $row['admin_review_date'] : '';
                    $answerData['expert_id'] = $row['expert_id'] != '' ? $row['expert_id'] : '';
                    $answerData['expert_name'] = '';
                    if (!empty($row['expert_id'])) {
                        $answerData['expert_name'] = getUserInfo($row['expert_id'])['name'];
                        $answerData['expert_image'] = getUserInfo($row['expert_id'])['image'];
                    }
                    $row['answerData'] = $answerData;
                }
                $row['total_assign_to_expert'] = $this->common_model->total_count('b_query_assign', 'id', ['query_id' => $row['id']]);
                $result[] = $row;
            }
        }
        return array('total_rows' => $total_rows, 'results' => $result);
    }

    public function getNewQuery($tbl = 'b_queries', $current_page = '', $per_page = '', $col = '*', $condition = array())
    {
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,c.cat_name as category_name,u.image as enduser_image', false);
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        if ($this->input->get('category_idd')=='1') {
            
           $this->db->where('q.category_id =', 1);
        }
        if ($this->input->get('category_idd')=='2') {
            
           $this->db->where('q.category_id =', 2);
        }
        if ($this->input->get('category_idd')=='3') {
            
           $this->db->where('q.category_id =', 3);
        }
        if ($this->input->get('category_idd')=='4') {
            
           $this->db->where('q.category_id =', 4);
        }
        if ($this->input->get('category_idd')=='5') {
            
           $this->db->where('q.category_id =', 5);
        }
        if ($this->input->get('category_idd')=='6') {
            
           $this->db->where('q.category_id =', 6);
        }
        if ($this->input->get('category_idd')=='7') {
            
           $this->db->where('q.category_id =', 7);
        }
        if ($this->input->get('category_idd')=='8') {
            
           $this->db->where('q.category_id =', 8);
        }
        if ($this->input->get('category_idd')=='9') {
            
           $this->db->where('q.category_id =', 9);
        }
        $this->db->where('NOT EXISTS (SELECT qa.* FROM  b_query_assign as qa WHERE  qa.query_id = q.id)', '', FALSE);
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        if ($this->input->get('short_by')=='old') {
            
            $this->db->order_by('q.id', 'ASC');
        }
        if ($this->input->get('short_by')=='new') {
           
           $this->db->order_by('q.id', 'DESC');
        }

        if ($per_page > 0) {
            $this->db->limit($per_page, $current_page);
        }
        $resSQL = $this->db->get($tbl . ' as q');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        $result = array();
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = array();
                $row['total_assign_to_expert'] = $this->common_model->total_count('b_query_assign', 'id', ['query_id' => $row['id']]);
                $result[] = $row;
            }
        }
        return array('total_rows' => $total_rows, 'results' => $result);
    }

     public function getAssignedQuery($tbl = 'b_query_assign', $current_page = '', $per_page = '', $col = '*', $condition = array())
    {
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,qa.*,q.query as question,u.image as user_image,c.cat_name as category_name', false);
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
        $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->order_by('qa.id', 'DESC');
        if ($per_page > 0) {
            $this->db->limit($per_page, $current_page);
        }
        $resSQL = $this->db->get($tbl . ' as qa');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        $result = array();
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = array();
                if ($row['status'] == '1') {
                    $answerData['expert_answer'] = $row['expert_answer'] != '' ? $row['expert_answer'] : '';
                    $answerData['answer'] = $row['answer'] != '' ? $row['answer'] : '';
                    $answerData['answer_date'] = $row['respond_date'] != '' ? $row['respond_date'] : '';
                    $answerData['user_id'] = $row['user_id'] != '' ? $row['user_id'] : '';
                    $answerData['expert_name'] = '';
                    if (!empty($row['user_id'])) {
                        $expertData = getUserInfo($row['user_id']);
                        $answerData['expert_name'] = $expertData['name'];
                        $answerData['expert_image'] = $expertData['image'];
                    }
                    $row['answerData'] = $answerData;
                }
                $result[] = $row;
            }
        }
        return array('total_rows' => $total_rows, 'results' => $result);
    }

    public function expert_users_record_count($condition)
    {
        $this->db->select('u.*');
        SetCondition($condition);
        $query = $this->db->get('b_users as u');
        $data = 0;
        if ($query->num_rows() > 0) {
            $data = $query->num_rows();
        }
        return $data;
    }

    public function get_expert_users($start, $limit, $condition = array())
    {
        $this->db->select('u.*');
        SetCondition($condition);
        $this->db->limit($limit, $start);
        $query = $this->db->get('b_users as u');
        $data = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getRespondQuery($tbl = 'b_query_assign', $current_page = '', $per_page = '', $col = '*', $condition = array())
    {
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.id,q.status,qa.query_id,q.added_on as asked_date,q.query as question,u.image as user_image,c.cat_name as category_name', false);
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        if ($this->input->get('category_idd')=='1') {
            
           $this->db->where('q.category_id =', 1);
        }
        if ($this->input->get('category_idd')=='2') {
            
           $this->db->where('q.category_id =', 2);
        }
        if ($this->input->get('category_idd')=='3') {
            
           $this->db->where('q.category_id =', 3);
        }
        if ($this->input->get('category_idd')=='4') {
            
           $this->db->where('q.category_id =', 4);
        }
        if ($this->input->get('category_idd')=='5') {
            
           $this->db->where('q.category_id =', 5);
        }
        if ($this->input->get('category_idd')=='6') {
            
           $this->db->where('q.category_id =', 6);
        }
        if ($this->input->get('category_idd')=='7') {
            
           $this->db->where('q.category_id =', 7);
        }
        if ($this->input->get('category_idd')=='8') {
            
           $this->db->where('q.category_id =', 8);
        }
        if ($this->input->get('category_idd')=='9') {
            
           $this->db->where('q.category_id =', 9);
        }
        $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
        $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->group_by('qa.query_id');
         if ($this->input->get('short_by')=='old') {
            
            $this->db->order_by('qa.query_id', 'ASC');
        }
        if ($this->input->get('short_by')=='new') {
           $this->db->order_by('qa.query_id', 'DESC');
        }
        //$this->db->order_by('qa.query_id', 'DESC');
        if ($per_page > 0) {
            $this->db->limit($per_page, $current_page);
        }
        $resSQL = $this->db->get($tbl . ' as qa');

        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        $result = array();
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = $this->getAssignedQueryByQueryId('b_query_assign', $row['query_id']);
                $result[] = $row;
            }
        }
        return array('total_rows' => $total_rows, 'results' => $result);
    }

    public function getAssignedQueryByQueryId($tbl = 'b_query_assign', $query_id)
    {
        $this->db->select('qa.*');
        $this->db->where('qa.query_id', $query_id);
        $this->db->order_by('qa.id', 'DESC');
        $resSQL = $this->db->get($tbl . ' as qa');
        $result = array();
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $answerData = array();
                $answerData['assign_query_id'] = $row['id'];
                $answerData['status'] = $row['status'];
                $answerData['added_on'] = $row['added_on'] != '' ? $row['added_on'] : '';
                $answerData['answer'] = $row['expert_answer'] != '' ? $row['expert_answer'] : '';
                $answerData['answer_date'] = $row['respond_date'] != '' ? $row['respond_date'] : '';
                $answerData['user_id'] = $row['user_id'] != '' ? $row['user_id'] : '';
                $answerData['expert_name'] = '';
                if (!empty($row['user_id'])) {
                    $expertData = getUserInfo($row['user_id']);
                    $answerData['expert_name'] = $expertData['name'];
                    $answerData['expert_image'] = $expertData['image'];
                }
                $result[] = $answerData;
            }
        }
        return $result;
    }

    public function getRespondedQuery($tbl = 'b_query_assign', $current_page = '', $per_page = '', $col = '*', $condition = array())
    {
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,qa.*,q.added_on as asked_date,q.query,u.image as user_image,c.cat_name as category_name', false);
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        //print_r($this->input->get('category_idd'));die;
        if ($this->input->get('category_idd')=='1') {
            
           $this->db->where('q.category_id =', 1);
        }
        if ($this->input->get('category_idd')=='2') {
            
           $this->db->where('q.category_id =', 2);
        }
        if ($this->input->get('category_idd') == 3 ) {
           // echo "hi saurbh";die;
           $this->db->where('q.category_id =', 3);
        }
        if ($this->input->get('category_idd')=='4') {
            
           $this->db->where('q.category_id =', 4);
        }
        if ($this->input->get('category_idd')=='5') {
            
           $this->db->where('q.category_id =', 5);
        }
        if ($this->input->get('category_idd')=='6') {
            
           $this->db->where('q.category_id =', 6);
        }
        if ($this->input->get('category_idd')=='7') {
            
           $this->db->where('q.category_id =', 7);
        }
        if ($this->input->get('category_idd')=='8') {
            
           $this->db->where('q.category_id =', 8);
        }
        if ($this->input->get('category_idd')=='9') {
            
           $this->db->where('q.category_id =', 9);
        }
        $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
        $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
         if ($this->input->get('short_by')=='old') {
            
            $this->db->order_by('qa.query_id', 'ASC');
        }
        if ($this->input->get('short_by')=='new') {
           $this->db->order_by('qa.query_id', 'DESC');
        }
        //$this->db->order_by('qa.query_id', 'DESC');
        if ($per_page > 0) {
            $this->db->limit($per_page, $current_page);
        }
        $resSQL = $this->db->get($tbl . ' as qa');
        //print_r($this->db->last_query());die;
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];

        $result = array();
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = array();
                $answerData['answer'] = $row['answer'] != '' ? $row['answer'] : '';
                $answerData['answer_date'] = $row['admin_review_date'] != '' ? $row['admin_review_date'] : '';
                $answerData['user_id'] = $row['user_id'] != '' ? $row['user_id'] : '';
                $answerData['expert_name'] = '';
                if (!empty($row['user_id'])) {
                    $expertData = getUserInfo($row['user_id']);
                    $answerData['expert_name'] = $expertData['name'];
                    $answerData['expert_image'] = $expertData['image'];
                }
                $row['answerData'] = $answerData;
                $result[] = $row;
            }
        }
        //pr($result,1);
        return array('total_rows' => $total_rows, 'results' => $result);
    }

    public function getQueryById($id)
    {
        $this->db->select('q.*,c.cat_name as category_name,u.image as enduser_image', false);
        $this->db->where('q.id',$id);
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        $this->db->order_by('q.id', 'DESC');
        $resSQL = $this->db->get('b_queries as q');
        $result = array();
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
        }
        return $result;
    }

    public function getAssignedQueryById($id)
    {
        $this->db->select('qa.*,q.query,c.cat_name as category_name,u.image as enduser_image', false);
        $this->db->where('qa.id',$id);
        $this->db->join('b_queries as q', 'q.id = qa.query_id ', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        $this->db->order_by('q.id', 'DESC');
        $resSQL = $this->db->get('b_query_assign as qa');
        $result = array();
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
        }
        return $result;
    }

    public function industrial_record_count($condition)
    {
        $this->table = 'b_industrial_users';
        $this->db->select($this->table . '.*');
        SetCondition($condition);

        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data = $query->num_rows();
        }
        return $data;
    }

    public function get_industrial_users($start, $limit, $condition = array())
    {
        $this->table = 'b_industrial_users';
        $this->db->select($this->table . '.*,b_industrial_categories.cat_name as category_name');
        SetCondition($condition);
        $this->db->join('b_industrial_categories','b_industrial_categories.id=b_industrial_users.category_id','LEFT');
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        $data = false;
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
        }
        return $data;
    }

    public function industrial_profile($id = '', $email = '', $condition = array())
    {

        $this->db->select('b_industrial_users.*,b_industrial_categories.cat_name as category_name');
        $this->db->from('b_industrial_users');
        if ($id != '') {
            $this->db->where('b_industrial_users.id', $id);
        } else if ($email != '') {
            $this->db->where(array('email' => $email));
        } else if (count($condition) > 0) {
            $this->db->where($condition);
        }

        $this->db->join('b_industrial_categories','b_industrial_categories.id=b_industrial_users.category_id','LEFT');
        $this->db->where(array('b_industrial_users.status!=' => '3'));
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $data = $query->row_array();
            return $data;
        } else {
            return false;
        }
    }


/*-------------------------------------------------------------------------------*/


public function mdTotalQueriesReports_count($condition)
    {
       $from = $condition['range']['q-from'];
       $to = $condition['range']['q-to'];
        $this->db->select('q.*');
    
        if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
         {
            $this->db->where('DATE(q.added_on) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
         }
         else if(isset($from) && $from != '' && $from != null && empty($to))
         { 
            $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-from"] . '"');
         }
         else if(empty($from)  && isset($to) && $to != '' && $to != null)
         {   
            $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-to"] . '"');
         }

            $query = $this->db->get("b_queries q");
            $data = false;
            if ($query->num_rows() > 0) {
                $data = $query->num_rows();
            }
            return $data;
        }

    public function get_mdTotalQueriesReports($start, $limit, $condition = array())
    {
       $from = $condition['range']['q-from'];
       $to = $condition['range']['q-to'];

       $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,qa.id as assign_query_id,c.cat_name as category_name,qa.answer,qa.admin_review_date,qa.added_on as answer_date,qa.user_id as expert_id,u.image as enduser_image', false);

      
        if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
        {
            $this->db->where('DATE(q.added_on) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
        }
     else if(isset($from) && $from != '' && $from != null && empty($to))
        { 
            $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-from"] . '"');
        }
     else if(empty($from)  && isset($to) && $to != '' && $to != null)
        {   
            $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-to"] . '"');
        }


        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        $this->db->join('b_query_assign as qa', 'q.id = qa.query_id', 'LEFT');
        $this->db->order_by('q.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get('b_queries as q');
        $data = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }


function mdTotalQueriesExcel($condition)
    {
       $from = $condition['q-from'];
       $to = $condition['q-to'];
            $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,qa.id as assign_query_id,c.cat_name as category_name,qa.answer,qa.admin_review_date,qa.added_on as answer_date,qa.user_id as expert_id,u.image as enduser_image', false);
            if(!empty($from) && !empty($to))
                 { 
                    $this->db->where('DATE(q.added_on) BETWEEN "' . $from . '" and "' . $to . '"');
                 }
                 else if(isset($from) && $from != '' && $from != null && empty($to))
                 { 
                   
                    $this->db->where('DATE(q.added_on) = "' . $from . '"');
                 }
                 else if(empty($from)  && isset($to) && $to != '' && $to != null)
                 {  
                    $this->db->where('DATE(q.added_on) = "' . $to . '"');
                 }
             
                $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
                $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
                $this->db->join('b_query_assign as qa', 'q.id = qa.query_id', 'LEFT');
                $this->db->order_by('q.id', 'DESC');
                $query = $this->db->get('b_queries as q');
                $data = false;
                if ($query->num_rows() > 0) {
                    foreach ($query->result_array() as $row) {
                        $data[] = $row;
                    }
                }
                return $data;
            }
   /* ------------------------------------------------------------------------------*/

public function mdNewQueriesReports_count($condition)
    {
        $from = $condition['range']['q-from'];
        $to = $condition['range']['q-to'];


        $conditionWhere = array('q.status!=' => '1');
            
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,c.cat_name as category_name,u.image as enduser_image', false);

        if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(q.added_on) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-from"] . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-to"] . '"');
             }
       $this->db->where($conditionWhere);
        $this->db->where('NOT EXISTS (SELECT qa.* FROM  b_query_assign as qa WHERE  qa.query_id = q.id)', '', FALSE);
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        $this->db->order_by('q.id', 'DESC');
        $resSQL = $this->db->get('b_queries as q');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        return  $total_rows;
    }



  public function get_mdNewQueriesReports($start, $limit, $condition = array())
    {
       $from = $condition['range']['q-from'];
       $to = $condition['range']['q-to'];

       $conditionWhere = array('q.status!=' => '1');

       $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,c.cat_name as category_name,u.image as enduser_image', false);
       
       if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(q.added_on) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-from"] . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(q.added_on) = "' . $condition["range"]["q-to"] . '"');
             }
       $this->db->where($conditionWhere);
        $this->db->where('NOT EXISTS (SELECT qa.* FROM  b_query_assign as qa WHERE  qa.query_id = q.id)', '', FALSE);
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
        $this->db->order_by('q.id', 'DESC'); 

        $this->db->limit($limit, $start);

        $resSQL = $this->db->get('b_queries as q');
        $data = false;
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;

    }


function mdNewQueriesExcel($condition)
    {
        $from = $condition['q-from'];
        $to = $condition['q-to'];

        $conditionWhere = array('q.status!=' => '1');

        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.*,c.cat_name as category_name,u.image as enduser_image', false);

         if(!empty($from) && !empty($to))
                 { 
                    $this->db->where('DATE(q.added_on) BETWEEN "' . $from . '" and "' . $to . '"');
                 }
                 else if(isset($from) && $from != '' && $from != null && empty($to))
                 { 
                   
                    $this->db->where('DATE(q.added_on) = "' . $from . '"');
                 }
                 else if(empty($from)  && isset($to) && $to != '' && $to != null)
                 {  
                    $this->db->where('DATE(q.added_on) = "' . $to . '"');
                 }
         $this->db->where($conditionWhere);
         $this->db->where('NOT EXISTS (SELECT qa.* FROM  b_query_assign as qa WHERE  qa.query_id = q.id)', '', FALSE);
         $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
         $this->db->join('b_users as u', 'u.id = q.user_id ', 'LEFT');
         $this->db->order_by('q.id', 'DESC'); 
         $resSQL = $this->db->get('b_queries as q');
         $data = false;
         if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;

    }


/* ------------------------------------------------------------------------------*/

public function mdClosedQueriesReports_count($condition)
    {  
        $from = $condition['range']['q-from'];
        $to = $condition['range']['q-to'];

        $conditionWhere = array('qa.status' => '1', 'review_status' => '1');


        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,qa.*,q.added_on as asked_date,q.query,u.image as user_image,c.cat_name as category_name', false);
        if (count($conditionWhere) > 0) {
            $this->db->where($conditionWhere);
        }

            if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(qa.admin_review_date) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(qa.admin_review_date) = "' . $condition["range"]["q-from"] . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(qa.admin_review_date) = "' . $condition["range"]["q-to"] . '"');
             }

        $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
        $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->order_by('qa.query_id', 'DESC');
        $resSQL = $this->db->get('b_query_assign as qa');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];

        $result = array();
        return $total_rows;
    }



  public function get_mdClosedQueriesReports($start, $limit, $condition = array())
    {
        $from = $condition['range']['q-from'];
        $to = $condition['range']['q-to'];

        $conditionWhere = array('qa.status' => '1', 'review_status' => '1');


        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,qa.*,q.added_on as asked_date,q.query,u.image as user_image,c.cat_name as category_name', false);
        if (count($conditionWhere) > 0) {
            $this->db->where($conditionWhere);
        }

            if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(qa.admin_review_date) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(qa.admin_review_date) = "' . $condition["range"]["q-from"] . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(qa.admin_review_date) = "' . $condition["range"]["q-to"] . '"');
             }

        $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
        $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->order_by('qa.query_id', 'DESC');
          $this->db->limit($limit, $start);
        $resSQL = $this->db->get('b_query_assign as qa');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];

        $result = array();

         if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = array();
                $answerData['answer'] = $row['answer'] != '' ? $row['answer'] : '';
                $answerData['answer_date'] = $row['admin_review_date'] != '' ? $row['admin_review_date'] : '';
                $answerData['user_id'] = $row['user_id'] != '' ? $row['user_id'] : '';
                $answerData['expert_name'] = '';
                if (!empty($row['user_id'])) {
                    $expertData = getUserInfo($row['user_id']);
                    $answerData['expert_name'] = $expertData['name'];
                    $answerData['expert_image'] = $expertData['image'];
                }
                $row['answerData'] = $answerData;
                $result[] = $row;
            }
        }

        return $result;

    }


function mdClosedQueriesExcel($condition)
    {
        $from = $condition['q-from'];
        $to = $condition['q-to'];

        $conditionWhere = array('qa.status' => '1', 'review_status' => '1');
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,qa.*,q.added_on as asked_date,q.query,u.image as user_image,c.cat_name as category_name', false);
        if (count($conditionWhere) > 0) {
            $this->db->where($conditionWhere);
        }

            if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(qa.admin_review_date) BETWEEN "' . $from . '" and "' . $to . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(qa.admin_review_date) = "' . $from . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(qa.admin_review_date) = "' . $to . '"');
             }

        $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
        $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
        $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
        $this->db->order_by('qa.query_id', 'DESC');
        $resSQL = $this->db->get('b_query_assign as qa');
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];

        $result = array();

         if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $row['answerData'] = array();
                $answerData['answer'] = $row['answer'] != '' ? $row['answer'] : '';
                $answerData['answer_date'] = $row['admin_review_date'] != '' ? $row['admin_review_date'] : '';
                $answerData['user_id'] = $row['user_id'] != '' ? $row['user_id'] : '';
                $answerData['expert_name'] = '';
                if (!empty($row['user_id'])) {
                    $expertData = getUserInfo($row['user_id']);
                    $answerData['expert_name'] = $expertData['name'];
                    $answerData['expert_image'] = $expertData['image'];
                }
                $row['answerData'] = $answerData;
                $result[] = $row;
            }
        }

        return $result;

    }

/* ------------------------------------------------------------------------------*/

public function mdPendingQueriesReports_count($condition)
    {  
        $from = $condition['range']['q-from'];
        $to = $condition['range']['q-to'];

        $conditionWhere = array('q.status!=' => '1');

        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.id,q.status,qa.query_id,q.added_on as asked_date,q.query as question,u.image as user_image,c.cat_name as category_name', false);
            if (count($conditionWhere) > 0) {
                $this->db->where($conditionWhere);
            }


             if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(qa.added_on) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(qa.added_on) = "' . $condition["range"]["q-from"] . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(qa.added_on) = "' . $condition["range"]["q-to"] . '"');
             }



            $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
            $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
            $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
            $this->db->group_by('qa.query_id');
            $this->db->order_by('qa.query_id', 'DESC');
            $resSQL = $this->db->get('b_query_assign as qa');

            $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
           
            return $total_rows;
    }



  public function get_mdPendingQueriesReports($start, $limit, $condition = array())
    {
        $from = $condition['range']['q-from'];
        $to = $condition['range']['q-to'];

        $conditionWhere = array('q.status!=' => '1');


         $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows,q.id,q.status,qa.query_id,q.added_on as asked_date,q.query as question,u.image as user_image,c.cat_name as category_name', false);
            if (count($conditionWhere) > 0) {
                $this->db->where($conditionWhere);
            }

             if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(qa.added_on) BETWEEN "' . $condition["range"]["q-from"] . '" and "' . $condition["range"]["q-to"] . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(qa.added_on) = "' . $condition["range"]["q-from"] . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(qa.added_on) = "' . $condition["range"]["q-to"] . '"');
             }

            $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
            $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
            $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
            $this->db->group_by('qa.query_id');
            $this->db->order_by('qa.query_id', 'DESC');
            $this->db->limit($limit, $start);
            $resSQL = $this->db->get('b_query_assign as qa');

            $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
            $result = array();
            if ($resSQL->num_rows() > 0) {
                foreach ($resSQL->result_array() as $row) {
                    $row['answerData'] = $this->getAssignedQueryByQueryId('b_query_assign', $row['query_id']);
                    $result[] = $row;
                }
            }

            return $result;

    }



    function mdPendingQueriesExcel($condition)
    {
        $from = $condition['q-from'];
        $to = $condition['q-to'];

        $conditionWhere = array('q.status!=' => '1');

        
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_rows, q.id,q.status,qa.query_id,q.added_on as asked_date,q.query as question,u.image as user_image,c.cat_name as category_name', false);
            if (count($conditionWhere) > 0) {
                $this->db->where($conditionWhere);
            }

                if(isset($from) && $from != '' && $from != null && isset($to) && $to != '' && $to != null)
             {
                $this->db->where('DATE(qa.added_on) BETWEEN "' . $from . '" and "' . $to . '"');
             }
             else if(isset($from) && $from != '' && $from != null && empty($to))
             { 
                $this->db->where('DATE(qa.added_on) = "' . $from . '"');
             }
             else if(empty($from)  && isset($to) && $to != '' && $to != null)
             {   
                $this->db->where('DATE(qa.added_on) = "' . $to . '"');
             }


            $this->db->join('b_queries as q', 'q.id=qa.query_id', 'LEFT');
            $this->db->join('b_users as u', 'u.id=q.user_id', 'LEFT');
            $this->db->join('b_categories as c', 'c.id = q.category_id ', 'LEFT');
            $this->db->group_by('qa.query_id');
            $this->db->order_by('qa.query_id', 'DESC');
            $this->db->limit($limit, $start);
            $resSQL = $this->db->get('b_query_assign as qa');

            $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
            $result = array();
            if ($resSQL->num_rows() > 0) {
                foreach ($resSQL->result_array() as $row) {
                    $row['answerData'] = $this->getAssignedQueryByQueryId('b_query_assign', $row['query_id']);
                    $result[] = $row;
                }
            }

            return $result;

    }



}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */