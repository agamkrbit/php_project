<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
	* Note Table
	*/
	class Note_table_model extends Note_model
	{
		
		function __construct()
		{
			parent::__construct();
			$this->table = DB_NOTETABLE_TABLE;
			$this->load->model('user_model');
		}

		function insert_note($user, $pwd, $time, $note_status, $note, $priority_status){
			$response = $this->user_model->get_user_data($user, $pwd);
			if($response['code'] == 3 && $response['message'] == 'success'){
				$note_data = array(
					DB_COL_USERID_NOTETABLE => $response['user_data'][DB_COL_USERID_USERTABLE],
					DB_COL_TIME_NOTETABLE => $time,
					DB_COL_NOTESTATUS_NOTETABLE => $note_status,
					DB_COL_PRIORITYSTATUS_NOTETABLE => $priority_status,
					DB_COL_NOTE_NOTETABLE => $note
					);
				$this->db->insert($this->table, $note_data);
				return $response;
			}else{
				return $response;
			}
		}

		public function delete_note($user , $pwd , $note_id){
			$response = $this->user_model->user_authentication($user, $pwd);
			if($response['code'] = 3 && $response['message'] == 'success'){
				$this->db->where(DB_COL_NOTEID_NOTETABLE, $note_id);
				$this->db->delete($this->table);
				$response['code'] = RESPONSE_CODE_SUCCESS;
				$response['message'] = RESPONSE_MESSAGE_SUCCESS;
				return $response;
			}else{
				$response['code'] = RESPONSE_CODE_ERROR;
				$response['message'] = RESPONSE_MESSAGE_ERROR;
				return $response;
			}
		}

	}

?>