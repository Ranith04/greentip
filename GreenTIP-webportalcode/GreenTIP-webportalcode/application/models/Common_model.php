<?php

class Common_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function _update($table, $data, $condition = array())
    {
      //  print_r($data);die;

        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        if ($this->db->update($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function _select($table, $column = '*', $condition = array(), $order_by = '', $sort = 'ASC', $start = '0', $perPage = '')
    {
        if (count($condition) > 0 && $condition != '') {
            $this->db->where($condition);
        }
        if ($order_by != '') {
            $this->db->order_by($order_by, $sort);
        }
        if ($perPage != '') {
            $this->db->limit($perPage, $start);
        }
        $this->db->select($column, false);
        $resSQL = $this->db->get($table);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function _selectWhereIn($table, $column = '*', $onColumn, $value = array(), $condition = array(), $order_by = '', $sort = 'ASC', $start = '0', $perPage = '')
    {
        if (!empty($value)) {
            $this->db->group_start();
            $sale_ids_chunk = array_chunk($value, 25);
            foreach ($sale_ids_chunk as $sale_ids) {
                $this->db->or_where_in($onColumn, $sale_ids);
            }
            $this->db->group_end();

        }
        if (count($condition) > 0 && $condition != '') {
            $this->db->where($condition);
        }
        if ($order_by != '') {
            $this->db->order_by($order_by, $sort);
        }
        if ($perPage != '') {
            $this->db->limit($perPage, $start);
        }
        $this->db->select($column, false);
        $resSQL = $this->db->get($table);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function _selectById($table, $column = '*', $condition = array())
    {
        if (count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->select($column, false);
        $resSQL = $this->db->get($table);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
            return $result;
        } else {
            return false;
        }
    }

    function _insert($table, $data)
    {
        if ($this->db->insert($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function insert_batch($table, $data)
    {
        if ($this->db->insert_batch($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function update_batch($table, $data, $key)
    {
        if ($this->db->update_batch($table, $data, $key)) {
            return true;
        } else {
            return false;
        }
    }

    function _delete($table, $condition = '')
    {
        if ($condition != '') {
            $this->db->where($condition);
        }
        if ($this->db->delete($table)) {
            return true;
        } else {
            return false;
        }
    }

    function getTotal($table, $column, $condition = array())
    {
        if (is_array($condition) && count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->select($column);
        $resSQL = $this->db->get($table);
        if ($resSQL->num_rows() > 0) {
            $total = '';
            foreach ($resSQL->result_array() as $row) {
                $total = $row['total'];
            }
            return $total;
        } else {
            return false;
        }
    }

    function deleteWhereIn($table, $onColumn, $value = array())
    {
        $this->db->where_in($onColumn, $value);
        if ($this->db->delete($table)) {
            return true;
        } else {
            return false;
        }
    }

    function updateWhereIn($table, $data, $onColumn, $id_array)
    {
        $this->db->where_in($onColumn, $id_array);
        if ($this->db->update($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function _insertReturnId($table, $data)
    {
        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function _CheckExistence($col, $val, $tbl = 'b_users', $where = array())
    {
        $this->db->where(array($col => $val));
        $this->db->from($tbl);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    function _select_for_dropdown($table, $columnId, $columnVal, $condition = array(), $order_by = '', $sort = 'ASC')
    {
        if (count($condition) > 0 && $condition != '') {
            $this->db->where($condition);
        }
        if ($order_by != '') {
            $this->db->order_by($order_by, $sort);
        }
        $this->db->select($columnId . ', ' . $columnVal, false);
        $resSQL = $this->db->get($table);
        if ($resSQL->num_rows() > 0) {
            $result = array();
            $i = 0;
            $result[''] = 'Please Select';
            foreach ($resSQL->result_array() as $row) {
                $result[$row[$columnId]] = $row[$columnVal];
            }
            return $result;
        } else {
            return false;
        }
    }

    function refineSearchString($strForRefine)
    {
        $srcName = '';
        $refinedString = '';
        $disAllowChars = array(';', ':', '/', ',', '>', '<', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '\'', '_', '=', '"');
        $refinedString = str_replace($disAllowChars, ' ', preg_replace('/[^A-Za-z0-9\-\.\']/', ' ', $strForRefine));
        //$refinedString = str_replace('-',' ',preg_replace('/[^A-Za-z0-9\-\']/',' ', $strForRefine));
        if (strlen(trim($refinedString)) > 0) {
            $srcNam_old = explode(' ', $refinedString);
            if ($srcNam_old) {
                foreach ($srcNam_old as $s) {
                    if (strlen($s) >= 1) {
                        $srcName[] = $s;
                    }
                }
            }
        }
        return $srcName;
    }

    function MakeSearchqry($field_name = array(), $value)
    {
        $BeforeRefinedString = $value;
        $if_condition = '';
        $refinedStringArr = $this->refineSearchString($value);
        if ($field_name && $refinedStringArr) {
            $refinedStringArr = $this->refineSearchString($value);
            $value = implode(' ', $refinedStringArr);
            $f = 0;
            $count = 1;
            $finalElseClause = "0";
            $if_condition = '';
            $brackitclosecount = '';
            foreach ($field_name as $field) {
                if (count($refinedStringArr)) {
                    $if_condition .= "if( " . $field . " = " . $this->db->escape($BeforeRefinedString) . " ," . $count . ",if( " . $field . " LIKE " . $this->db->escape($BeforeRefinedString . '%') . "," . ($count + 1) . ",if( " . $field . " LIKE " . $this->db->escape('%' . $BeforeRefinedString . '%') . "," . ($count + 2) . ",";
                    $brackitclosecount = $brackitclosecount + 3;
                    $count = $count + 2;
                    $count++;

                    if (count($refinedStringArr) > 0) {
                        $array_and = array();
                        foreach ($refinedStringArr as $StringArr) {
                            $array_and[] = ' ' . $field . ' LIKE "%' . $StringArr . '%" ';
                        }
                        if (!empty($array_and)) {
                            $if_condition .= " if(" . implode(' AND ', $array_and) . ", " . $count . ", ";
                            $brackitclosecount++;
                            $count++;
                        }
                    }

                    if (count($refinedStringArr) > 1) {
                        $innerCount = $count;
                        for ($i = 0; $i < count($refinedStringArr); $i++) {
                            $if_condition .= " if(" . $field . " LIKE '%" . $refinedStringArr[$i] . "%', " . $innerCount . ", ";
                            $brackitclosecount++;
                            $innerCount++;
                        }
                        $count++;
                    }

                }

                if (count($field_name) == ($f + 1)) {
                    $if_condition .= $finalElseClause . str_repeat(" ) ", $brackitclosecount);
                }

                $f++;
            }
        }
        return $if_condition;

    }

    public function get_all_record($col = '*', $table, $where = array())
    {
        if ($table == 'b_categories') {
            $i = 0;;
            $result1[$i]['id'] = '0';
            $result1[$i]['cat_name'] = 'All Categories';
            $result1[$i]['status'] = '1';
            $result1[$i]['added_on'] = '2016-10-27 13:42:32';
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->select($col);
        $result = $this->db->get($table)->result_array();
        if ($table == 'b_categories') {
            $result = array_merge($result1, $result);
        }

        return $result;
    }

    function total_count($table, $column, $condition = array())
    {
        if (is_array($condition) && count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->select($column);
        $this->db->from($table);
        $resSQL = $this->db->count_all_results();
        return $resSQL;
    }

    function getEarns($table, $column, $condition = array())
    {
        if (is_array($condition) && count($condition) > 0) {
            $this->db->where($condition);
        }
        $this->db->select('SUM(' . $column . ') as earn');
        $this->db->from($table);
        $query = $this->db->get();
        $data = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    function getSingleRecord($table, $condition = '', $col = '*')
    {
        if ($condition != '') {
            $condition = ' WHERE ' . $condition;
        }
        $strSQL = "SELECT " . $col . " FROM " . $table . " " . $condition;
        $resSQL = $this->db->query($strSQL);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
            return $result;
        } else {
            return false;
        }
    }

    function getMultipleRecord($table, $condition = '', $col = '*')
    {
        if ($condition != '') {
            $condition = ' WHERE ' . $condition;
        }
        $strSQL = "SELECT " . $col . " FROM " . $table . " " . $condition. " ORDER BY id DESC" ;
        $resSQL = $this->db->query($strSQL);
        $data = false;
        if ($resSQL->num_rows() > 0) {
            foreach ($resSQL->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getList($table, $start, $limit, $columns = '*', $condition = [])
    {
        if (empty($columns)) {
            $columns = '*';
        }
        $this->db->select("SQL_CALC_FOUND_ROWS null as `total_rows`", false);
        if ($columns == '*') {
            $this->db->select("{$table}.*");
        } else {
            $this->db->select($columns);
        }

        SetCondition($condition);
        $this->db->limit($limit, $start);
        $query = $this->db->get($table);
        /* echo $this->db->last_query();die;*/
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        return [
            'total_rows' => $total_rows,
            'results' => $query->result_array(),
        ];
    }

    public function getNewList($table, $start, $limit, $columns = '*', $condition = [], $order_by = '', $sort = 'ASC')
    {
        if (empty($columns)) {
            $columns = '*';
        }
        $this->db->select("SQL_CALC_FOUND_ROWS null as `total_rows`", false);
        if ($columns == '*') {
            $this->db->select("{$table}.*");
        } else {
            $this->db->select($columns);
        }
        if (count($condition) > 0 && $condition != '') {
            $this->db->where($condition);
        }
        if($order_by!=''){
            $this->db->order_by($order_by,$sort);
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get($table);
        /* echo $this->db->last_query();die;*/
        $total_rows = $this->db->select('FOUND_ROWS() count ')->get()->row_array()['count'];
        return [
            'total_rows' => $total_rows,
            'results' => $query->result_array(),
        ];
    }

    function slug($title, $separator = '-')
    {
        // convert String to Utf-8 Ascii
        $title = iconv(mb_detect_encoding($title, mb_detect_order(), true), "UTF-8", $title);

        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';

        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        // $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);


        $str = str_replace("'", "", trim($title, $separator));
        $str = str_replace("?", "", $str);
        $str = str_replace("(", "", $str);
        $str = str_replace(")", "", $str);
        $str = str_replace("&", "", $str);
        $str = str_replace("\\", "", $str);
        $str = str_replace("/", "", $str);
        $str = str_replace('"', "", $str);
        $str = str_replace('~', "", $str);
        $str = str_replace('#', "", $str);
        $str = str_replace('%', "", $str);
        return $str;
    }

    function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL)
    {

        $this->load->helper('text');
        $t =& get_instance();
        /* $slug = $this->slug($string, '-', TRUE);*/
        $slug = strtolower(url_title(convert_accented_characters($string), '-'));
        /*    $slug = mb_strtolower($slug, 'UTF-8');*/
        $i = 0;
        $params = array();
        $params[$field] = $slug;

        if ($key) $params["$key !="] = $value;


        while ($t->db->where($params)->get($table)->num_rows()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug))
                $slug .= '-' . ++$i;
            else
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);

            $params [$field] = $slug;
        }

        return $slug;
    }

}

?>