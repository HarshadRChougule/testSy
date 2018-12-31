<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//Modified by Harshad

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//$this->load->library('stripegateway');
		//$this->load->helper('json_output');
	}

	public function login()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			 
				//$params = $_REQUEST; ... get call
		        $params = json_decode(file_get_contents('php://input'), TRUE);//post call

		        $username = $params['user_email'];
		        $password = $params['user_password'];

		        $response = $this->auth_model->login($username,$password);
 
				json_output($response['status'],$response);
			 
		}
	}

	public function signup()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			 	
				//$params = $_REQUEST; ... get call
		        $params = json_decode(file_get_contents('php://input'), TRUE);//post call

		        $response = $this->auth_model->signup($params); 
		        json_output($response['status'], $response);
			 
		}
	}

	public function verify_email()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
		        $params = json_decode(file_get_contents('php://input'), TRUE);//post call
		        $response = $this->auth_model->verify_email($params); 
		        json_output($response['status'],$response);		 
		}
	}

	public function reset_password()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_model->check_auth_client();
			if($check_auth_client == true){
			$user_id = $this->input->get_request_header('user_id', TRUE);
			$auth_key =$this->input->get_request_header('auth_key', TRUE);

				$params = json_decode(file_get_contents('php://input'), TRUE);//post call
		        $response = $this->auth_model->reset_password($params,$user_id,$auth_key);
				json_output($response['status'],$response);
			}
			
		}
	}

public function google_or_facebook_login()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			 
				//$params = $_REQUEST; ... get call
		        $params = json_decode(file_get_contents('php://input'), TRUE);//post call
		        //echo '<pre>',print_r($params);exit;
		        $response = $this->auth_model->google_or_facebook_login($params);
 
				json_output($response['status'], $response);
			 
		}
	}

	public function forgot_password()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			//$check_auth_client = $this->auth_model->check_auth_client();
				$params = json_decode(file_get_contents('php://input'), TRUE);//post call
		        $response = $this->auth_model->forgot_password($params);
		        //print_r($response);exit();
				json_output($response['status'],$response);
			
		}
	}

	function forgotpassword_link()
    {
        $user_id=$this->uri->segment(3);
        $password_token=$this->uri->segment(4);
        $result=$this->auth_model->check_link_time($user_id, $password_token);
        //echo '<pre>',print_r($result);exit;
        if(COUNT($result)>0)
        {
            $this->load->view('reset_password', $user_id);
        }
        else{
            $this->load->view('link_expired');
        }
    }

    function set_new_password(){
        $result=$this->auth_model->set_new_password();
        $this->session->set_flashdata('success', '<strong>Password Changed Successfully!</strong>');
        $this->load->view('link_expired');
    }


	public function logout()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_model->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->auth_model->logout();
				json_output($response['status'],$response);
			}
		}
	}

	public function terms_condition()
   {
        $method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {                         
			$this->load->view('terms_condition');	
		}

   } 
}
                              