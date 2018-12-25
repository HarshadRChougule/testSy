<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class auth_model extends CI_Model {

    var $client_service = "frontend-client";
    var $auth_key       = "simplerestapi";

    public function check_auth_client(){
        $auth_key  = $this->input->get_request_header('auth_key', TRUE); 
        $DB_auth_key  = $this->db->select('auth_key')->from('user_mst')->where('auth_key',$auth_key)->get()->row();
        
        if($auth_key == $DB_auth_key->auth_key){            
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function login($username,$password)
    {

        $q  = $this->db->select('user_password, user_id')->from('user_mst')->where('user_email',$username)->get()->row();       
      //  echo '<pre>',print_r($this->db->last_query());exit;
        if($q == ""){ 
            return array('status' => 404,'message' => 'Username not found.');
        } else {
            $hashed_password = $q->user_password;
            $id              = $q->user_id;
              
            if (verifyHashedPassword($password,$hashed_password)) {
               $params['last_login']= date('Y-m-d H:i:s');
               $params['auth_key'] = base64_encode(mt_rand());
               $this->db->trans_start();

               $this->db->where('user_id',$id)->update('user_mst',$params);
              
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','user_id' => $id, 'auth_key' =>  $params['auth_key']);
               }
            } else { 
               return array('status' => 401,'message' => 'Wrong password.');
            }
        }
    }

public function google_or_facebook_login($params)
{
    //echo '<pre>',print_r($params);exit;
    $params['auth_key'] = base64_encode(mt_rand());
    $params['createdDtm'] =date('Y-m-d H:i:s'); 
    //echo '<pre>',print_r($params);exit;
       
    $this->db->trans_start();
            if($params['token_type']=='facebook')
            {   
              $exit_user_id=$this->db->query('SELECT user_id FROM user_mst WHERE user_email=? AND EXISTS (SELECT user_facebook_key FROM user_mst WHERE user_email=?)', array($params['user_email'], $params['user_email']))->row()->user_id;
                  //echo '<pre>',print_r($this->db->last_query());exit;
                  if($exit_user_id){
                    $this->db->query('UPDATE `user_mst` SET `user_facebook_key`=? WHERE `user_id`=?', array($params['token'], $exit_user_id));
                     $insert_id=$exit_user_id;
                }
                else{
                $this->db->query('INSERT INTO `user_mst`(`user_name`, `user_email`, `user_os_type`, `auth_key`, `user_facebook_key`,`isDeleted`,`createdDtm`) VALUES(?,?,?,?,?,?,?)', array($params['user_name'], $params['user_email'], $params['user_os_type'], $params['auth_key'], $params['token'], 0, $params['createdDtm']));
                $insert_id=$this->db->insert_id();
              }
            }
            if($params['token_type']=='google')
            {
                //echo '<pre>',print_r($params);exit;
                  $exit_user_id=$this->db->query('SELECT user_id FROM user_mst WHERE user_email=? AND EXISTS (SELECT user_gmail_key FROM user_mst WHERE user_email=?)', array($params['user_email'], $params['user_email']))->row()->user_id;
                  //echo '<pre>',print_r($this->db->last_query());exit;
                  if($exit_user_id){
                    $this->db->query('UPDATE `user_mst` SET `user_gmail_key`=?, `auth_key`=? WHERE `user_id`=?', array($params['token'], $params['auth_key'], $exit_user_id));
                     $insert_id=$exit_user_id;
                }
                else{
                  $user_info=$this->db->query('SELECT user_id FROM user_mst WHERE user_email=?', array($params['user_email']))->row()->user_id;

                   if(!$user_info){
                      $this->db->query('INSERT INTO `user_mst`(`user_name`, `user_email`, `user_os_type`, `auth_key`,`user_gmail_key`,`isDeleted`, `createdDtm`) VALUES(?,?,?,?,?,?,?)', array($params['user_name'], $params['user_email'], $params['user_os_type'], $params['auth_key'], $params['token'], 0, $params['createdDtm']));
                     // echo '<pre>',print_r($this->db->last_query());exit;
                       $insert_id=$this->db->insert_id();
                  }else{
                     return array('status' => 409,'message' => 'All-ready register user');
                  }

                  
                }
            }
            

            if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                          return array('status' => 500,'message' => 'Internal server error.');
            } else {  
             $this->db->trans_commit();
                          return array('status' => 200,'message' => 'Successfully login.','id' => $insert_id, 'auth_key' =>  $params['auth_key']);            
            }
}

    public function signup($params)
    {        

        $user_name = $params['user_name'];
        $user_email = $params['user_email'];
        $params['user_password'] = getHashedPassword($params['user_password']);
        $user_phone = $params['user_phone'];
        $user_address = $params['user_address'];
        $params['auth_key'] = base64_encode(mt_rand());
        $params['createdDtm'] =date('Y-m-d H:i:s'); 
        
        $user_info  = $this->db->select('user_password, user_id')->from('user_mst')->where('user_email',$user_email)->get()->row();
                   
         if($user_info !="" ){
            return array('status' => 409,'message' => 'All-ready register user ');           
               }
          else{
            $this->db->trans_start();
            $a=$this->db->insert('user_mst',$params);
          //echo '<pre>',print_r($this->db->last_query());exit;
                if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
                 } else {
                    $last_insert_id = $this->db->insert_id();
                  $this->db->trans_commit();
                 
                   return array('status' => 200,'message' => 'Successfully Signup .','user_id' => $last_insert_id, 'auth_key' => $params['auth_key']);                                               
            }      
       }
    }

    public function reset_password($params,$user_id,$auth_key)
    {        
       
        //ToDo Email code 

         $old_password = $params['user_old_password'];        
         $new_password = $params['user_password']; 
               
         
         $user_info  = $this->db->select('user_password, auth_key')->from('user_mst')->where('user_id',$user_id)->get()->row();
        

         //check valid user
         if($user_info =="" || $auth_key != $user_info->auth_key ){
             return array('status' => 404,'message' => 'Invalid User   ');                        
               }                          
          else{
                    //check old password
                    $hashed_password = $user_info->user_password;
                if (verifyHashedPassword($old_password,$hashed_password)) {
                    //update new password
                    
                    $hashed_password_new = getHashedPassword($new_password);
                    $params = array('user_password' => $hashed_password_new, 'updatedBy'=>$user_id,'updatedDtm'=>date('Y-m-d H:i:s')); 
                    $this->db->trans_start();
                    $this->db->where('user_id',$user_id);
                    $this->db->update('user_mst',$params);
                  
                   if ($this->db->trans_status() === FALSE){
                      $this->db->trans_rollback();
                      return array('status' => 500,'message' => 'Internal server error.');
                   } else {
                      $this->db->trans_commit();
                       return array('status' => 200,'message' => 'Password updated Successfully');
                   }
                }else{
                     return array('status' => 401,'message' => 'Old password is not currect');
                }
            }      
       }

   public function forgot_password($params)
    {        
       
        //ToDo Email code 

         $username = $params['user_email'];        
         $user_info  = $this->db->select('user_password, user_id')->from('user_mst')->where('user_email',$username)->get()->row();
        // echo '<pre>',print_r($username);exit;
         if($user_info =="" ){
             return array('status' => 404,'message' => 'User Not found ');                        
               }
          else{
                $slug = md5($user_info->user_id . $username . date('Ymd'));
                $link = site_url('auth/forgotpassword_link/'.$user_info->user_id.'/'. $slug);
                $user_name=$this->db->query('SELECT `user_name` FROM `user_mst` WHERE `user_id`=?', array($user_info->user_id))->row()->user_name;
                $to = $username;
                $subject = "Bowling Express Pay";

            $emailImgUrl = EMAIL_IMG_URL;
            $htmlContent='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>  <title></title>  <!--[if !mso]><!-- -->  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!--<![endif]--><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><style type="text/css">  #outlook a { padding: 0; }  .ReadMsgBody { width: 100%; }  .ExternalClass { width: 100%; }  .ExternalClass * { line-height:100%; }  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }  p { display: block; margin: 13px 0; }</style><!--[if !mso]><!--><style type="text/css">  @media only screen and (max-width:480px) {    @-ms-viewport { width:320px; }    @viewport { width:320px; }  }</style><!--<![endif]--><!--[if mso]><xml>  <o:OfficeDocumentSettings>    <o:AllowPNG/>    <o:PixelsPerInch>96</o:PixelsPerInch>  </o:OfficeDocumentSettings></xml><![endif]--><!--[if lte mso 11]><style type="text/css">  .outlook-group-fix {    width:100% !important;  }</style><![endif]--><!--[if !mso]><!-->    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">    <style type="text/css">        @import url(https://fonts.googleapis.com/css?family=Lato);  @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);    </style>  <!--<![endif]--><style type="text/css">  @media only screen and (min-width:480px) {    .mj-column-per-100 { width:100%!important; }.mj-column-per-50 { width:50%!important; }.mj-column-per-25 { width:25%!important; }  }</style></head><body>    <div class="mj-container"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><div style="margin:0px auto;max-width:600px;background:#3a7d46;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#3a7d46;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;"><div style="font-size:1px;line-height:25px;white-space:nowrap;">&#xA0;</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:90px;"><img alt="" title="" height="auto" src="'.$emailImgUrl.'emailImg/logo.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="90"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="center"><div style="cursor:auto;color:#FFFFFF;font-family:Lato, Tahoma, sans-serif;font-size:14px;line-height:22px;text-align:center;"><h1 style="font-family: &apos;Cabin&apos;, sans-serif; color: #FFFFFF; font-size: 32px; line-height: 100%;">Oops forgot password....!!</h1></div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="center"><div style="cursor:auto;color:#FFFFFF;font-family:Lato, Tahoma, sans-serif;font-size:14px;line-height:22px;text-align:center;"><h1 style="font-family: &apos;Cabin&apos;, sans-serif; color: #FFFFFF; font-size: 32px; line-height: 100%;"><span style="font-size:22px;">Bowling Express Pay is always here to help you.</span></h1></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:13px 0px 13px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:300px;">      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:300px;"><img alt="" title="" height="auto" src="'.$emailImgUrl.'emailImg/support.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="300"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:300px;">      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 28px 0px 28px;" align="left"><div style="cursor:auto;color:#000000;font-family:Lato, Tahoma, sans-serif;font-size:14px;line-height:22px;text-align:left;"><p><span style="font-size:16px;"><strong>Hi '.$user_name.',</strong></span></p><p><span style="font-size:16px;"><strong>You recently requested to reset your password for your Bowling Express Pay account, Click the button below to reset it.</strong>&#xA0;</span></p></div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:12px 0px 12px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:24px;color:#fff;cursor:auto;padding:10px 25px;" align="center" valign="middle" bgcolor="#d6343f"><a href="'.$link.'" style="text-decoration:none;background:#d6343f;color:#fff;font-family:Ubuntu, Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;font-size:15px;font-weight:normal;line-height:120%;text-transform:none;margin:0px;" target="_blank">Reset Password Now</a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><div style="margin:0px auto;max-width:600px;background:#3a7d46;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#3a7d46;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:300px;">      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="left"><div style="cursor:auto;color:#FFFFFF;font-family:Lato, Tahoma, sans-serif;font-size:14px;line-height:22px;text-align:left;"><h2 style="color: #757575; line-height: 100%;"></h2><p><span style="font-size:16px;">If you did not requeste the password reset, please ignore this email. This password reset is only valid till next 15 minutes.</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:300px;">      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><!--<tr><td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:300px;"><img alt="" title="" height="auto" src="'.$emailImgUrl.'emailImg/pins.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="300"></td></tr></tbody></table></td></tr>--></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:54px;padding-left:54px;"><p style="font-size:1px;margin:0px auto;border-top:1px dotted #868686;width:100%;"></p><!--[if mso | IE]><table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0px auto;border-top:1px dotted #868686;width:100%;" width="600"><tr><td style="height:0;line-height:0;"> </td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:150px;">      <![endif]--><div class="mj-column-per-25 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:150px;">      <![endif]--><div class="mj-column-per-25 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:150px;">      <![endif]--><div class="mj-column-per-25 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:8px 8px 8px 8px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:134px;"><img alt="" title="" height="auto" src="'.$emailImgUrl.'emailImg/apple.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="134"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:150px;">      <![endif]--><div class="mj-column-per-25 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:8px 8px 8px 8px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:134px;"><img alt="" title="" height="auto" src="'.$emailImgUrl.'emailImg/googlePlay.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="134"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:300px;">      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 26px 0px 26px;" align="left"><div style="cursor:auto;color:#949494;font-family:Lato, Tahoma, sans-serif;font-size:14px;line-height:22px;text-align:left;"><p><span style="font-size:12px;">Copyright &#xA9; 2018BXP, All rights reserved.&#xA0;<br>&#xA0;<br>&#xA0;</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:300px;">      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="right"><div><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="undefined"><tr><td>      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="float:none;display:inline-table;" align="right" border="0"><tbody><tr><td style="padding:4px;vertical-align:middle;"><table role="presentation" cellpadding="0" cellspacing="0" style="background:none;border-radius:3px;width:35px;" border="0"><tbody><tr><td style="vertical-align:middle;width:35px;height:35px;"><a href="https://www.facebook.com/PROFILE"><img alt="facebook" height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/outlined/facebook.png" style="display:block;border-radius:3px;" width="35"></a></td></tr></tbody></table></td><td style="padding:4px 4px 4px 0;vertical-align:middle;"><a href="https://www.facebook.com/PROFILE" style="text-decoration:none;text-align:left;display:block;color:#333333;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;border-radius:3px;"></a></td></tr></tbody></table><!--[if mso | IE]>      </td><td>      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="float:none;display:inline-table;" align="right" border="0"><tbody><tr><td style="padding:4px;vertical-align:middle;"><table role="presentation" cellpadding="0" cellspacing="0" style="background:none;border-radius:3px;width:35px;" border="0"><tbody><tr><td style="vertical-align:middle;width:35px;height:35px;"><a href="https://www.twitter.com/PROFILE"><img alt="twitter" height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/outlined/twitter.png" style="display:block;border-radius:3px;" width="35"></a></td></tr></tbody></table></td><td style="padding:4px 4px 4px 0;vertical-align:middle;"><a href="https://www.twitter.com/PROFILE" style="text-decoration:none;text-align:left;display:block;color:#333333;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;border-radius:3px;"></a></td></tr></tbody></table><!--[if mso | IE]>      </td><td>      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="float:none;display:inline-table;" align="right" border="0"><tbody><tr><td style="padding:4px;vertical-align:middle;"><table role="presentation" cellpadding="0" cellspacing="0" style="background:none;border-radius:3px;width:35px;" border="0"><tbody><tr><td style="vertical-align:middle;width:35px;height:35px;"><a href="https://plus.google.com/PROFILE"><img alt="google" height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/outlined/google-plus.png" style="display:block;border-radius:3px;" width="35"></a></td></tr></tbody></table></td><td style="padding:4px 4px 4px 0;vertical-align:middle;"><a href="https://plus.google.com/PROFILE" style="text-decoration:none;text-align:left;display:block;color:#333333;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;border-radius:3px;"></a></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]--></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]--></div></body></html>';

            // Set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // Additional headers
            $headers .= 'From: '.INFO_EMAIL . "\r\n";

            //sending email
            //if(mail($to,$subject,$htmlContent,$headers)){
                $startTime = date("Y-m-d H:i:s");
                $cenvertedTime = date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($startTime)));
              //  $this->db->query('UPDATE `end_user_mst` SET `password_token`=?, `password_token_Dtm`=? WHERE `end_user_id`=?', array($slug, $cenvertedTime, $user_info->end_user_id));

               return array('status' => 200,'message' => 'Please check email, reset password link is send to the email id ');
            //   }
                           
                 
            }      
       }
     
    function check_link_time($user_id, $password_token)
    {
       // echo date("Y-m-d H:i:s");exit;
        $now_date=date('Y-m-d H:i:s');
        $result=$this->db->query("SELECT end_user_id FROM end_user_mst WHERE password_token=? AND end_user_id=? AND password_token_Dtm >?",array($password_token, $user_id, $now_date))->result_array();
        return $this->db->last_query();
       // return $result;
    }

    function set_new_password()
    {
        $this->db->query('UPDATE `end_user_mst` SET `end_user_password`=?, `password_token_Dtm`=? WHERE `end_user_id`=?', array(getHashedPassword($_POST['newPassword']), date('Y-m-d H:i:s'), $_POST['user_id']));

        return $this->db->affected_rows();
    }
     
    


    public function logout()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $this->db->where('users_id',$users_id)->where('token',$token)->delete('users_authentication');
        return array('status' => 200,'message' => 'Successfully logout.');
    }

    public function auth()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $q  = $this->db->select('expired_at')->from('users_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
        if($q == ""){
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } else {
            if($q->expired_at < date('Y-m-d H:i:s')){
                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } else {
                $updated_at = date('Y-m-d H:i:s');
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                $this->db->where('users_id',$users_id)->where('token',$token)->update('users_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
                return array('status' => 200,'message' => 'Authorized.');
            }
        }
    }


   public function verify_email($params)
    {         
        $user_email = $params['user_email'];         
         $user_info  = $this->db->select('user_id')->from('user_mst')->where('user_email', $user_email)->get()->row();
     
         if($user_info !="" ){
            return array('status' => 409,'message' => 'All-ready register user ');           
               }
          else{
            return array('status' => 200,'message' => 'Username avalible ');           
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
