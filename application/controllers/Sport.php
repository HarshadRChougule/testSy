<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sport extends CI_Controller {

	public function get_all_sports()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			$this->load->model('sport_model');
			$check_auth_client =$this->sport_model->check_auth_client();
			// echo $check_auth_client;exit;	
			if($check_auth_client == true){
				$user_id  = $this->input->get_request_header('user_id', TRUE); 
				$response = $this->sport_model->get_all_sports($user_id); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}

	public function get_all_level_of_sport()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			$this->load->model('sport_model');
			$check_auth_client =$this->sport_model->check_auth_client();
			// echo $check_auth_client;exit;	
			if($check_auth_client == true){
				$user_id  = $this->input->get_request_header('user_id', TRUE); 
				$response = $this->sport_model->get_all_level_of_sport($user_id); 
				//print_r($response);exit();
				json_output($response['status'],$response);
			}
		}
	}

}
?>