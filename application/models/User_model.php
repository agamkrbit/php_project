<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
	/**
	* User Model class
	*/
	class User_model extends Note_model
	{
		
		function __construct()
		{
			parent::__construct();
			$this->table = DB_USERTABLE_TABLE;
		}

		public function user_authentication($user = NULL, $pwd = NULL){
			$result =  $this->get($this->table, DB_COL_EMAIL_USERTABLE, $user);
			$pwd = md5($pwd);
			$respone = array();
			//var_dump($result);
			$pwd_col = DB_COL_PASSWORDHASH_USERTABLE;
			if(isset($result[0]) && count($result[0]) > 0){
				if($result[0]->$pwd_col == $pwd){
					$response['code'] = RESPONSE_CODE_SUCCESS;
					$response['message'] = RESPONSE_MESSAGE_SUCCESS;
					//$response['user_data'] = $result[0];
					return $response;
				}else{
					$response['code'] = RESPONSE_CODE_AUTH_STATUS_PASSWORD_NOT_MATCHED;
					$response['message'] = RESPONSE_MESSAGE_AUTH_STATUS_PASSWORD_NOT_MATCHED;
					return $response;
				}
			}else{
				$response['code'] = RESPONSE_CODE_AUTH_STATUS_NO_USER;
				$response['message'] = RESPONSE_MESSAGE_AUTH_STATUS_NO_USER;
				return $response;
			}
		}

		public function get_user_data($user = NULL, $pwd = NULL){
			$result =  $this->get($this->table, DB_COL_EMAIL_USERTABLE, $user);
			$pwd = md5($pwd);
			$respone = array();
			//var_dump($result);
			$pwd_col = DB_COL_PASSWORDHASH_USERTABLE;
			$first_name_col = DB_COL_FIRSTNAME_USERTABLE;
			$email_col = DB_COL_EMAIL_USERTABLE;
			$last_name_col = DB_COL_LASTNAME_USERTABLE ;
			$user_id_col = DB_COL_USERID_USERTABLE;
			if(isset($result[0]) && count($result[0]) > 0){
				if($result[0]->$pwd_col == $pwd){
					$response['code'] = RESPONSE_CODE_SUCCESS;
					$response['message'] = RESPONSE_MESSAGE_SUCCESS;
					$response['user_data'][DB_COL_FIRSTNAME_USERTABLE] = $result[0]->$first_name_col;
					$response['user_data'][DB_COL_LASTNAME_USERTABLE] = $result[0]->$last_name_col;
					$response['user_data'][DB_COL_EMAIL_USERTABLE] = $result[0]->$email_col;
					$response['user_data'][DB_COL_USERID_USERTABLE] = $result[0]->$user_id_col;
					return $response;
				}else{
					$response['code'] = RESPONSE_CODE_AUTH_STATUS_PASSWORD_NOT_MATCHED;
					$response['message'] = RESPONSE_MESSAGE_AUTH_STATUS_PASSWORD_NOT_MATCHED;
					return $response;
				}
			}else{
				$response['code'] = RESPONSE_CODE_AUTH_STATUS_NO_USER;
				$response['message'] = RESPONSE_MESSAGE_AUTH_STATUS_NO_USER;
				return $response;
			}
		}

		public function delete_user($user = NULL, $pwd = NULL){
			$response = $this->user_authentication($user, $pwd);
			if($response['code'] = 3 && $response['message'] == 'success'){
				$this->db->where(DB_COL_EMAIL_USERTABLE, $user);
				$this->db->delete($this->table);
				$this->db->where(DB_COL_USERID_NOTETABLE, $response['user_data'][DB_COL_USERID_USERTABLE]);
				$this->db->delete(DB_NOTETABLE_TABLE);
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