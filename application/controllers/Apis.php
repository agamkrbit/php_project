<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apis extends Note_controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('user_model');
		$this->load->model('note_table_model');
	}

	public function index(){
		$this->login();
	}

	public function login($user = NULL, $pwd = NULL){

		$user = isset($user) && trim($user) != "" ? $user : (isset($_REQUEST['user']) && trim($_REQUEST['user']) != "" ? $_REQUEST['user'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);
		print_r(json_encode($this->user_model->user_authentication($user, $pwd)));
		//print_r(json_encode($this->user_model->get_userdata($user, $pwd)));

	}

	public function user(){
		//var_dump($_SERVER['REQUEST_METHOD']);
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				$this->get_user_data();
				break;
			case 'DELETE' :
				$this->delete_user();
				break;
			case 'POST':
				$this->reg_new_user();
			default:
				# code...
				break;
		}
	}

	public function note(){
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST':
				$this->insert_table();
				break;
			case 'DELETE':
				$this->delete_note();
				break;
			case 'GET' :
				$this->get_note_all();
				break;
			default:
				# code...
				break;
		}
	}

	private function get_user_data($user = NULL, $pwd = NULL){
		$user = isset($user) && trim($user) != "" ? $user : (isset($_REQUEST['user']) && trim($_REQUEST['user']) != "" ? $_REQUEST['user'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);
		print_r(json_encode($this->user_model->get_user_data($user, $pwd)));
	}

	private function reg_new_user($first_name = NULL, $last_name = NULL, $email = NULL, $pwd = NULL, $pwd2 = NULL){
		$first_name = isset($first_name) && trim($first_name) != "" ? $first_name : (isset($_REQUEST['first_name']) && trim($_REQUEST['first_name']) != "" ? $_REQUEST['first_name'] : NULL);
		$last_name = isset($last_name) && trim($last_name) != "" ? $last_name : (isset($_REQUEST['last_name']) && trim($_REQUEST['last_name']) != "" ? $_REQUEST['last_name'] : NULL);
		$email = isset($email) && trim($email) != "" ? $email : (isset($_REQUEST['email']) && trim($_REQUEST['email']) != "" ? $_REQUEST['email'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);
		$pwd2 = isset($pwd2) && trim($pwd2) != "" ? $pwd2 : (isset($_REQUEST['pwd2']) && trim($_REQUEST['pwd2']) != "" ? $_REQUEST['pwd2'] : NULL);

		if($first_name != NULL && $email != NULL && $pwd != NULL && $pwd2 != NULL && $pwd == $pwd2){
			$new_user = array(
				DB_COL_FIRSTNAME_USERTABLE => $first_name,
				DB_COL_LASTNAME_USERTABLE => $last_name,
				DB_COL_EMAIL_USERTABLE => $email,
				DB_COL_PASSWORDHASH_USERTABLE => md5($pwd)
				);

			$result = $this->user_model->insert(DB_USERTABLE_TABLE, $new_user);
			$response = array();
			$response['code'] = RESPONSE_CODE_NEW_USER;
			$response['message'] = RESPONSE_MESSAGE_NEW_USER;
			print_r(json_encode($response));
		}else{
			$response = array();
			$response['code'] = RESPONSE_CODE_ERROR;
			$response['message'] = RESPONSE_MESSAGE_ERROR;
			print_r(json_encode($response));
		}
	}

	private function delete_user($user = NULL, $pwd = NULL){
		$user = isset($user) && trim($user) != "" ? $user : (isset($_REQUEST['user']) && trim($_REQUEST['user']) != "" ? $_REQUEST['user'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);
		print_r(json_encode($this->user_model->delete_user($user, $pwd)));
	}

	private function insert_table($user = NULL, $pwd = NULL, $time = NULL, $note = NULL, $priority_status = NULL){
		$user = isset($user) && trim($user) != "" ? $user : (isset($_REQUEST['user']) && trim($_REQUEST['user']) != "" ? $_REQUEST['user'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);
		$time = isset($time) && trim($time) != "" ? $time : (isset($_REQUEST['time']) && trim($_REQUEST['time']) != "" ? $_REQUEST['time'] : NULL);
		$note_status = 'active';
		$note = isset($note) && trim($note) != "" ? $note : (isset($_REQUEST['note']) && trim($_REQUEST['note']) != "" ? $_REQUEST['note'] : NULL);
		$priority_status = isset($priority_status) && trim($priority_status) != "" ? $priority_status : (isset($_REQUEST['priority_status']) && trim($_REQUEST['priority_status']) != "" ? $_REQUEST['priority_status'] : NULL);
		if($user != NULL && $pwd != NULL && $time != NULL && $note_status != NULL && $note != NULL && $priority_status != NULL){
			print_r(json_encode($this->note_table_model->insert_note($user, $pwd , $time , $note_status , $note , $priority_status)));
		}else{
			$response = array();
			$response['code'] = RESPONSE_CODE_ERROR;
			$response['message'] = RESPONSE_MESSAGE_ERROR;
			print_r(json_encode($response));
		}
	}

	private function delete_note($user = NULL, $pwd = NULL, $note_id = NULL){
		$user = isset($user) && trim($user) != "" ? $user : (isset($_REQUEST['user']) && trim($_REQUEST['user']) != "" ? $_REQUEST['user'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);
		$note_id = isset($note_id) && trim($note_id) != "" ? $note_id : (isset($_REQUEST['note_id']) && trim($_REQUEST['note_id']) != "" ? $_REQUEST['note_id'] : NULL);
		print_r(json_encode($this->note_table_model->delete_note($user, $pwd, $note_id)));
	}

	private function get_note_all($user = NULL, $pwd= NULL){
		$user = isset($user) && trim($user) != "" ? $user : (isset($_REQUEST['user']) && trim($_REQUEST['user']) != "" ? $_REQUEST['user'] : NULL);
		$pwd = isset($pwd) && trim($pwd) != "" ? $pwd : (isset($_REQUEST['pwd']) && trim($_REQUEST['pwd']) != "" ? $_REQUEST['pwd'] : NULL);

		$response = $this->user_model->get_user_data($user, $pwd);
		if($response['code'] == 3 && $response['message'] == 'success'){
			$this->note_table_model->query_get_note['tables'] = array(DB_NOTETABLE_TABLE);
			$this->note_table_model->query_get_note['select'] = array(
					DB_NOTETABLE_TABLE => array(DB_COL_NOTEID_NOTETABLE, DB_COL_TIME_NOTETABLE, DB_COL_NOTESTATUS_NOTETABLE, DB_COL_NOTE_NOTETABLE, DB_COL_PRIORITYSTATUS_NOTETABLE)
				);
			//print_r(json_encode($this->note_table_model->query_get_note));
			$this->note_table_model->query_get_note['where'] = DB_COL_USERID_NOTETABLE . '=' . "".$response['user_data']['user_id']."";
			$json = array();
			echo "{";
			foreach($this->note_table_model->query_builder($this->note_table_model->query_get_note) as $key => $value){
				print_r(json_encode($value));
				echo ",";
			}
			echo "}";
		}
	}
}
