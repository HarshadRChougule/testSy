<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sport_model extends CI_Model {

     
    public function check_auth_client(){ 
        $auth_key  = $this->input->get_request_header('auth_key', TRUE); 
        $DB_auth_key  = $this->db->select('auth_key')->from('user_mst')->where('auth_key',$auth_key)->get()->row();
        
        if($auth_key == $DB_auth_key->auth_key){            
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function get_all_sports($user_id)
    {

        $this->db->trans_start();

        $q = $this->db->query('SELECT sport_id, sport_name, sport_icon FROM sports_mst')->result_array();

             
       // echo '<pre>',print_r($q),exit;
        if($q == ""){ 
            return array('status' => 204,'message' => 'Username not found.');
        } else{              
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'data' => $q);
               }
            }  
        }

    public function get_all_level_of_sport($user_id)
    {

        $this->db->trans_start();

        $q = $this->db->query('SELECT level_id, level_name FROM sport_level_mst')->result_array();

             
       // echo '<pre>',print_r($q),exit;
        if($q == ""){ 
            return array('status' => 204,'message' => 'Username not found.');
        } else{              
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'data' => $q);
               }
            }  
        }
}

?>