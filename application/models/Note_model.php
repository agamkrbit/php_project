<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* User Model class
	*/
	class Note_model extends CI_Model
	{	
		public $query_get_note = array(
			'table' => array(),
			'select' => array(),
			'where' => 'true',
			'order by' => '',
			'limit' => '',
			'offset' => '',
		);
		
		function __construct()
		{
			parent::__construct();
		}

		public function get($table, $where_col, $where_data){
			$this->db->where($where_col, $where_data);
			$this->db->select('*');
			$result = $this->db->get($table);
			return $result->result();
		}

		public function insert($table,$data){
			$this->db->insert($table, $data);
		}

		public function query_builder($query_array){
			$tables = implode(',', $query_array['tables']);
			$tables = '' . $tables . '';
			$select = array();
			foreach ($query_array['select'] as $table => $cols) {
				foreach ($cols as $col) {
					$select[] .= '' . $table . '.' . $col . ''; 
				}
			}
			$where = "";
			if(isset($query_array['where']) && trim($query_array['where']) != ""){
				$where = 'WHERE  ' . $query_array['where'];
			}
			$limit = "";
			if(isset($query_array['limit']) && trim($query_array['limit']) != ""){
				$limit = 'LIMIT = ' . $query_array['limit'];
			}
			$offset = "";
			if(isset($query_array['offset']) && trim($query_array['offset']) != ""){
				$offset = 'OFFSET = ' . $query_array['offset'];
			}
			$group_by = "";
			if(isset($query_array['order by']) && trim($query_array['order by']) != ""){
				$group_by = 'ORDER BY  ' . $query_array['order by'];
			}
			$select = implode(',', $select);
			$query = ' SELECT ' . $select . ' FROM ' . $tables . ' ' . $where . ' ' . $group_by . ' ' . $limit . ' ' . $offset;

			$result = $this->db->query($query);
			return $result->result();
		}
	}

?>