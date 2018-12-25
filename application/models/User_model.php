<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_model extends CI_Model {

     
    public function check_auth_client(){ 
        $auth_key  = $this->input->get_request_header('auth_key', TRUE); 
        $DB_auth_key  = $this->db->select('auth_key')->from('user_mst')->where('auth_key',$auth_key)->get()->row();
        
        if($auth_key == $DB_auth_key->auth_key){            
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function get_all_profile_info($user_id)
    {

        $this->db->trans_start();

        $q = $this->db->query('SELECT DISTINCT u.user_id, u.user_name, u.user_email, u.user_phone, u.user_address, u.user_avatar, u.user_os_type, u.user_gmail_key, u.user_facebook_key, u.latitude, u.longitude, u.changed_location_datetime, u.user_gender, u.user_DOB, u.user_description, "sports_info" as sports_info
                              FROM user_mst as u 
                              JOIN user_sports_details as us ON us.user_id=u.user_id
                              WHERE u.user_id=?', array($user_id))->result_array();

        $q[0]['sports_info']=$this->db->query('SELECT s.sport_name, sl.level_name 
                                FROM user_sports_details as us
                                JOIN sports_mst as s ON s.sport_id=us.sport_id
                                JOIN sport_level_mst as sl ON sl.level_id=us.level_id
                                WHERE us.user_id=?', array($user_id))->result_array();      
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

     public function update_user_info($user_id,$params)
    {
         $this->db->trans_start();
         $params['updatedBy'] =$user_id;
         $params['updatedDtm'] =date('Y-m-d H:i:s');
         
        $q = $this->db->where('user_id',$user_id)->update('user_mst',$params);      
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
    
    public function set_players_current_location($user_id,$params)
      {
          $this->db->trans_start();
          //$params['updatedBy'] =$user_id;
          $params['changed_location_datetime'] =date('Y-m-d H:i:s');
         
          //$q = $this->db->where('user_id',$user_id)->update('user_mst',$params); 

          $q =$this->db->query('UPDATE user_mst SET latitude=?, longitude=?, changed_location_datetime=? WHERE user_id=?',array($params['latitude'], $params['longitude'], $params['changed_location_datetime'], $user_id));

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


      public function get_players_info($user_id,$params)
      {

          $this->db->trans_start();
          $latitude=$params['latitude'];
          $longitude=$params['longitude'];
          //$distance = 10; // Kilometers

          $distance=$this->db->query('SELECT vicinity_distance FROM user_mst WHERE user_id=?', array($user_id))->row()->vicinity_distance;
          //echo $distance;exit;
          $q=$this->db->query('SELECT user_id, user_name, user_email, user_phone, user_address, user_avatar, latitude, longitude, changed_location_datetime, user_DOB, user_gender, user_description,
            6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(user_mst.latitude))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(user_mst.latitude))) * POWER(SIN(RADIANS(? - user_mst.longitude)), 2))) AS distance
            FROM user_mst
            WHERE user_id!=?
            HAVING distance < ?
            ', array($latitude, $latitude, $longitude, $user_id, $distance))->result_array();
          
          //echo '<pre>',print_r(COUNT($q)), exit;
        //  echo '<pre>',print_r($this->db->last_query()),exit;
            

          if($q == ""){ 
              return array('status' => 204,'message' => 'Username not found.');
          } else{              
                 if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    return array('status' => 500,'message' => 'Internal server error.');
                 } else {
                    $this->db->trans_commit();

                    $numrows = COUNT($q);            
                    $rowsperpage = 10;
                    $response['total_records'] = $numrows;             
                    // find out total pages
                    $totalpages = ceil($numrows / $rowsperpage);
                    $response['total_pages'] = $totalpages;
                     
                    // get the current page or set a default            
                    if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
                    $currentpage = (int) $_GET['currentpage'];
                    } else {
                    $currentpage = 1;  // default page number
                    }
                     
                    // if current page is greater than total pages
                    if ($currentpage > $totalpages) {
                    // set current page to last page
                    $currentpage = $totalpages;
                    }
                    // if current page is less than first page
                    if ($currentpage < 1) {
                    // set current page to first page
                    $currentpage = 1;
                    }
                     
                    // the offset of the list, based on current page
                    $offset = ($currentpage - 1) * $rowsperpage;
                    $response['current_page'] = $currentpage;
                    $response['data'] = $q; 
                    return array('status' => 200,'data' => $response);
                 }
              } 
      }
      
    public function get_my_dashboard($user_id,$params)
    {
         $this->db->trans_start();
          
 
        $q = $this->db->where('end_user_id',$user_id);      
        if($q == ""){ 
            return array('status' => 204,'message' => 'Username not found.');
        } else{              
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                $city = strtolower($params['city']);
                $currentDate =date('Y-m-d');
 
              //myLeagueCount
                $sql = "SELECT DISTINCT lt.league_id as myLeagueCount
                FROM `end_user_mst`as e
                JOIN league_transaction as lt ON lt.end_user_id=e.end_user_id
                WHERE e.end_user_id=".$user_id;

                $result= $this->db->query($sql)->result_array();
          //     echo '<pre>', print_r(COUNT($result));exit;
                $data['myLeagueCount'] =COUNT($result);//$COUNT->myLeagueCount;


                //myTournamentCount
                $sql = "SELECT COUNT(tt.tournament_id) AS myTournamentCount
                FROM `end_user_mst`as e
                JOIN tournament_transaction as tt ON tt.end_user_id=e.end_user_id
                WHERE e.end_user_id=".$user_id;

                $COUNT= $this->db->query($sql)->row();
                $data['myTournamentCount'] =$COUNT->myTournamentCount;


                //activeLeagueCount
                $sql = "SELECT COUNT(league_id) AS activeLeagueCount
                  FROM league_mst                                                        
                  WHERE LOWER(league_city) LIKE LOWER('%".$city."%')
                  AND league_isActive= 1
                  AND league_end_date > '".$currentDate."'";

                $COUNT= $this->db->query($sql)->row();
                $data['activeLeagueCount'] =$COUNT->activeLeagueCount;


                //activeTournamentCount  
                $sql = "SELECT COUNT(tournament_id) AS activeTournamentCount 
                FROM tournament_mst 
                WHERE LOWER(tournament_city) LIKE LOWER('%".$city."%')
                AND tournament_isActive= 1 
                AND tournament_end_date > '".$currentDate."'";

                $COUNT= $this->db->query($sql)->row();
                $data['activeTournamentCount'] =$COUNT->activeTournamentCount ; 

                $data['admin_stripe_id']=$this->db->query('SELECT stripe_client_id FROM admin_stripe_details')->row()->stripe_client_id;
                
                  $this->db->trans_commit();
                  return array('status' => 200,'data' => $data);
               }
            }  
        }
    
    
    public function book_all_data()
    {
        return $this->db->select('id,title,author')->from('books')->order_by('id','desc')->get()->result();
    }

    public function book_detail_data($id)
    {
        return $this->db->select('id,title,author')->from('books')->where('id',$id)->order_by('id','desc')->get()->row();
    }

    public function book_create_data($data)
    {
        $this->db->insert('books',$data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function book_update_data($id,$data)
    {
        $this->db->where('id',$id)->update('books',$data);
        return array('status' => 200,'message' => 'Data has been updated.');
    }

    public function book_delete_data($id)
    {
        $this->db->where('id',$id)->delete('books');
        return array('status' => 200,'message' => 'Data has been deleted.');
    }

}
