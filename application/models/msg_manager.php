<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
////////////////////////
//      pEAS v3.0     //
// by Kristaps Ledins //
// ------------------ //
// twitter.com/krysits//
// github.com/krysits //
// krysits@gmail.com  //
// ------------------ //
//(c)2014 krysits.COM //
////////////////////////
class Msg_manager extends CI_Model 
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('email_manager');
        $this->load->library('My_PHPMailer');
	}
	
	private function _getEmails($lists = null)
	{
		if(empty($lists)) return false;
		$ems = array();
		foreach($lists as $list)
		{
			$newEms = $this->email_manager->_getEmailsList($list);
			if(!empty($newEms))
			{
				foreach($newEms as $em)
				{
					$ems[ $em['email'] ] = $em['email'];
				}
			}
		}
		return $ems;		
	}
	
	public function sendMsg($subject = "", $htmlBody = "", $lists = null) 
	{
		$sess_array = $this->session->userdata('logged_in');
		$user_name = $sess_array['username'];
		$user_email = $sess_array['email'];
		// mailer part
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = false; // enabled SMTP authentication
        //$mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = "mx2.bkc.lv";      // setting GMail as our SMTP server
        $mail->Port       = 25;                   // SMTP port to connect to GMail
        //$mail->Username   = "krysits@gmail.com";  // user email address
        //$mail->Password   = "password";            // password in GMail
        $mail->SetFrom($user_email, $user_name);  //Who is sending the email
        $mail->AddReplyTo($user_email, $user_name);  //email address that receives the response
		// msg body
		$mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody    = "pEAS3::PlainTextMessage";
        // get recipients
		$ems = $this->_getEmails($lists);
		if(!empty($ems))
		{
			foreach($ems as $email)
			{
				$mail->AddAddress($email);
			}
		}
		else
		{
			return false;
		}
        //$mail->AddAddress("krysits@gmail.com", "peas3admin"); // hack

        //$mail->AddAttachment("images/phpmailer.gif");      // some attached files
        //$mail->AddAttachment("images/phpmailer_mini.gif"); // as many as you want
        if(!$mail->Send()) {
            $data["message"] = "Error: " . $mail->ErrorInfo;
        } else {
            $data["message"] = "Message sent correctly!";
        }
        
		return $data["message"];
	}
	
};