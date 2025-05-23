<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Common_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    //get record count by sql
    function get_count_by_sql($SQL)
    {
        return $this->db->query($SQL)->num_rows();
    }
    //get records by sql
    function get($SQL)
    {
        $rs = $this->db->query($SQL);
        if ($rs->num_rows() > 0) {
            return $rs->result_array();
        } else {
            return false;
        }
    }
    function get_max($table_name = '', $field_name, $where = array())
    {
        $this->db->select_max($field_name);
        $query = $this->db->get_where($table_name, $where);
        return $query->result_array();
    }
    function get_where($table_name = '', $where = array(), $data = '*')
    {
        $this->db->select($data);
        $this->db->order_by('id', 'asc');
        $rs = $this->db->get_where($table_name, $where);
        return $rs->result();
    }
    function get_where_in($table_name = '', $field_name, $where = array())
    {
        $this->db->where_in($field_name, $where);
        $query = $this->db->get($table_name);
        return $query->result_array();
    }
    function get_in($table_name = "", $where = array(), $field_name = "", $select_field_name = "", $where_data = array())
    {
        $this->db->select($select_field_name);
        $this->db->from($table_name);
        $this->db->where_in($field_name, $where);
        $this->db->where($where_data);
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            $rs = $rs->result_array();
            if ($select_field_name != "") {
                foreach ($rs as $record) {
                    $init_list[] = $record[$select_field_name];
                }
                return $init_list;
            } else {
                return $rs;
            }
        } else {
            return false;
        }
    }
        public function count_all($table, $where = [])
{
    if (!empty($where)) {
        $this->db->where($where);
    }
    return $this->db->count_all_results($table);
}
public function count_filtered($table, $where = [], $search = [])
{
    if (!empty($where)) {
        $this->db->where($where);
    }
    if (!empty($search)) {
        $this->db->group_start();
        foreach ($search as $key => $value) {
            $this->db->or_like($key, $value);
        }
        $this->db->group_end();
    }
    return $this->db->count_all_results($table);
}
    // select records count (for pagination)
    function get_count($table_name = '', $where = array(), $distinct_value = NULL)
    {
        if ($distinct_value != NULL) {
            $this->db->select($distinct_value);
            $this->db->distinct($distinct_value);
        }
        $rs = $this->db->get_where($table_name, $where);
        return $rs->num_rows();
    }
    function getWhereJoin($select, $table1, $where1, $table2 = "", $where2 = "", $order = '', $table3 = "", $where3 = "")
    {
        $this->db->select($select);
        $this->db->from($table1);
        if ($table2 != '') {
            $this->db->join($table2, $where2, 'left');
        }
        if ($table3 != '') {
            $this->db->join($table3, $where3, 'left');
        }
        if ($where1 != '') {
            $this->db->where($where1);
        }
        if ($order != '') {
            $this->db->order_by($order, 'DESC');
        }
        $query = $this->db->get();
        return $query->result();
    }
    //get count like 
    function get_count_like($table_name = '', $where = array())
    {
        $this->db->like($where);
        $this->db->from($table_name);
        $rs = $this->db->get();
        return $rs->num_rows();
    }
    function get_select_records($table_name = '', $field_name = '', $where = array())
    {
        $this->db->select($field_name);
        $this->db->from($table_name);
        $this->db->where($where);
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            return $rs->result_array();
        } else {
            return false;
        }
    }
    // select records
    function get_records($table_name = '', $where = array(), $limit = null, $offset = null, $order_by_field = null, $order_by_order = null)
    {
        if (!is_null($offset) && !is_null($limit)) {
            $this->db->limit($limit, $offset);
        }
        if ($order_by_field != null && $order_by_order != null) {
            $this->db->order_by($order_by_field, $order_by_order);
        }
        $rs = $this->db->get_where($table_name, $where);
        if ($rs->num_rows() > 0) {
            return $rs->result_array();
        } else {
            return false;
        }
    }
    function get_where_row($table_name = '', $where = array(), $data = '*')
    {
        $this->db->select($data);
        $rs = $this->db->get_where($table_name, $where);
        return $rs->row();
    }
    ///get like
    function get_like($table_name = '', $where = array(), $limit = null, $offset = null, $order_by_field = null, $order_by_order = null)
    {
        if (!is_null($offset) and !is_null($limit)) {
            $this->db->limit($limit, $offset);
        }
        if ($order_by_field != null and $order_by_order != null) {
            $this->db->order_by($order_by_field, $order_by_order);
        }
        $this->db->like($where, '', 'both');
        $this->db->from($table_name);
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            return $rs->result_array();
        } else {
            return false;
        }
    }
    // get list of records for drop down
    function get_list($id_field_name, $value_field_name, $table_name, $where = array(), $init_list = array(), $order_by_field = null, $order_by_order = null, $concatname = null)
    {
        $this->db->select($id_field_name);
        if ($concatname == null) {
            $this->db->select($value_field_name);
        } else {
            $this->db->select('CONCAT(' . $value_field_name . '," ",' . $concatname . ',"") AS ' . $value_field_name . '');
        }
        $this->db->where($where);
        if ($order_by_field != null and $order_by_order != null) {
            $this->db->order_by($order_by_field, $order_by_order);
        }
        $rs = $this->db->get($table_name);
        if ($rs->num_rows() > 0) {
            $records = $rs->result_array();
            foreach ($records as $record) {
                $init_list[$record[$id_field_name]] = $record[$value_field_name];
            }
        }
        return $init_list;
    }
    // get one row
    function get_row($table_name, $where = array())
    {
        $records = $this->get_records($table_name, $where);
        if ($records != false) {
            return $records[0];
        } else {
            return false;
        }
    }
    function get_all($table, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        return $query->result(); // Returns multiple rows as an array of objects
    }
    // get one field
    function get_one($field_name, $table_name, $where = array())
    {
        $records = $this->get_records($table_name, $where);
        if ($records != false) {
            return $records[0][$field_name];
        } else {
            return false;
        }
    }
    // create new record
    function insert($table_name = '', $data = array())
    {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
    function insert_data($table_name = '', $data = array())
    {
        $result = $this->db->insert($table_name, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function insert_batch($table, $data)
    {
        if (!empty($data) && is_array($data)) {
            return $this->db->insert_batch($table, $data);
        }
        return false;
    }
    // update existing record
    function update($table_name, $data, $where)
    {
        $this->db->update($table_name, $data, $where);
        $result = $this->db->get_where($table_name, $where);
        return $result->row();
    }
    // delete existing record
    function delete($table_name = '', $where = array())
    {
        $this->db->delete($table_name, $where);
    }
    // Update or Insert
    function update_or_insert($table_name = '', $data = array(), $where = array())
    {
        $n = $this->get_count($table_name, $where);
        if ($n > 0) {
            $this->update($table_name, $data, $where);
            return true;
        } else {
            $this->db->insert($table_name, $data);
            return $this->db->insert_id();
        }
    }
    function show_data($table_name = "")
    {
        if ($table_name != "") {
            $result = $this->get("SHOW TABLE STATUS FROM " . $this->user_database . " WHERE name LIKE '" . $table_name . "'");
            return $result[0]['Auto_increment'];
        }
        return false;
    }
    public function InsertData($table, $values)
    {
        $this->db->insert($table, $values);
        return $this->db->insert_id();
    }
    public function InsertBatchData($table, $values)
    {
        $res = $this->db->insert_batch($table, $values);
        return ($res) ? 1 : 0;
    }
    public function GetDatas($table, $select, $where = NULL, $orderby = NULL, $groupby = NULL, $search = NULL, $limit = NULL, $start = NULL)
    {
        $query = $this->db->select($select)->from($table);
        if (!empty($search)) {
            $query->group_start();
            foreach ($search as $key => $value) {
                $query->or_like($key, $value);
            }
            $query->group_end();
        }
        if (!empty($where)) $query->where($where);
        if (!empty($groupby)) $query->group_by($groupby);
        if (!empty($orderby)) $query->order_by($orderby);
        if ($limit !== NULL && $start !== NULL) $query->limit($limit, $start);
        return $query->get()->result_array();
    }
    public function PaginationData($table, $select, $where = NULL, $orderby, $limit = 10, $start = 0, $search = NULL)
    {
        $query = $this->db->select($select)->from($table);
        if (!empty($search)) {
            $query->group_start();
            $query->or_like($search);
            $query->group_end();
        }
        (!empty($where)) ? $query->where($where) : $query;
        return $query->limit($limit, $start)->order_by($orderby)->get()->result_array();
    }
    public function UpdateData($table, $values, $where)
    {
        $this->db->set($values)->where($where)->update($table);
        return ($this->db->affected_rows() > 0) ? 1 : 0;
    }
    public function UpdateBatchData($table, $values, $where)
    {
        $this->db->update_batch($table, $values, $where);
        return ($this->db->affected_rows() > 0) ? 1 : 0;
    }
    public function GetJoinDatas($table1, $table2, $joincond, $select, $where = NULL, $wherein = NULL, $groupby = NULL, $search = null)
    {
        $query = $this->db->select($select)->from($table1);
        $query->join($table2, $joincond);
        if (!empty($search)) {
            $query->group_start();
            $query->or_like($search);
            $query->group_end();
        }
        (!empty($where)) ? $query->where($where) : $query;
        (!empty($groupby)) ? $query->group_by($groupby) : $query;
        (!empty($wherein)) ? $query->where_in($wherein) : $query;
        return $query->get()->result_array();
    }
    public function GetJoinDatasCustom($table1, $table2, $joincond, $select, $where = NULL, $order_by = NULL, $groupby = NULL, $search = null, $limit = null, $offset = null)
    {
        $query = $this->db->select($select)->from($table1);
        $query->join($table2, $joincond);

        if (!empty($search)) {
            $query->group_start();
            foreach ($search as $key => $value) {
                $query->or_like($key, $value);
            }
            $query->group_end();
        }

        if (!empty($where)) {
            $query->where($where);
        }

        if (!empty($groupby)) {
            $query->group_by($groupby);
        }

        if (!empty($order_by)) {
            $query->order_by($order_by);
        }

        if ($limit !== null && $offset !== null) {
            $query->limit($limit, $offset);
        }

        return $query->get()->result_array();
    }
    public function getCount($table, $where = NULL, $search = NULL)
    {
        $query = $this->db->from($table);
        if (!empty($search)) {
            $query->group_start();
            $query->or_like($search);
            $query->group_end();
        }
        (!empty($where)) ? $query->where($where) : $query;
        return $query->count_all_results();
    }
    public function DeleteData($table, $where)
    {
        $this->db->where($where)->delete($table);
        return ($this->db->affected_rows() > 0) ? 1 : 0;
    }
    public function getLikeDatas($table, $select, $where = NULL, $like)
    {
        $query = $this->db->select($select)->from($table);
        if (is_array($like)) {
            $query->group_start();
            foreach ($like as $columname => $values) {
                if (is_array($values)) {
                    foreach ($values as $val) {
                        $query->or_like($columname, $val);
                    }
                } else {
                    $query->or_like($columname, $values);
                }
            }
            $query->group_end();
        } else {
            $query->group_start();
            $query->or_like($like);
            $query->group_end();
        }
        (!empty($where)) ? $query->where($where) : $query;
        return $query->get()->result_array();
    }
    public function RawSQL($query)
    {
        return $this->db->query($query)->result_array();
    }
}