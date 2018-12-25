<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function get_all_profile_info()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$this->load->model('user_model');
			$check_auth_client =$this->user_model->check_auth_client();
			 
			if($check_auth_client == true){
				$user_id  = $this->input->get_request_header('user_id', TRUE); 
				$response = $this->user_model->get_all_profile_info($user_id); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}
	public function update_user_info()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			$this->load->model('user_model');
			$check_auth_client =$this->user_model->check_auth_client();
			 
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);
				$user_id  = $this->input->get_request_header('user_id', TRUE); 
				$response = $this->user_model->update_user_info($user_id,$params); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}

	public function set_players_current_location()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			$this->load->model('user_model');
			$check_auth_client =$this->user_model->check_auth_client();
			 
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);	
				$user_id  = $this->input->get_request_header('user_id', TRUE); 			  
				$response = $this->user_model->set_players_current_location($user_id,$params); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}


	public function get_players_info()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			$this->load->model('user_model');
			$check_auth_client =$this->user_model->check_auth_client();
			 
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);	
				$user_id  = $this->input->get_request_header('user_id', TRUE); 			  
				$response = $this->user_model->get_players_info($user_id, $params); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}
	
	public function get_my_dashboard()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			$this->load->model('user_model');
			$check_auth_client =$this->user_model->check_auth_client();
			 
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);	
				$user_id  = $this->input->get_request_header('user_id', TRUE); 			  
				$response = $this->user_model->get_my_dashboard($user_id,$params); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}



	 


 
	
}
