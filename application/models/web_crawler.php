<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
////////////////////////
//      pEAS v3.0     //
// by Kristaps Ledins //
// ------------------ //
// twitter.com/krysits//
// github.com/krysits //
// krristaps@inbox.lv //
// ------------------ //
// (c) 2014 @krysits  //
////////////////////////
class Web_crawler extends CI_Model {	
	// arguments
	private $mails = array();
	private $links = array();
	public 	$url = "";
	private $uid = 1;
    private $wid = 0;
    private $buffer = '';
    public 	$only_mails = false;
    private $no_ssl = true;
	private $max_times = 90;
	private $times = 0;
	public  $_messages = array();	
	// constructor
	public function __construct()
	{
		parent::__construct();
	}
	// methods	
	private function findMails(){
		$this->mails=array();
		$re = "/[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]{1,})+)*/";
		if(preg_match_all($re, $this->buffer, $res, PREG_SET_ORDER)){
			for ($i = 0; $i<count($res); $i++){
				if ($tmp = trim($res[$i][0])){
					$this->mails[$tmp] = $tmp;
				}
			}
			return true;
		}
		return false;
	}
	
	private function findLinks(){
		$this->links=array();
		if(preg_match_all("/(href)[[:space:]]*=[[:space:]]*[\"']{0,1}([^\"'[:space:]>]*)/i",$this->buffer,$res,PREG_SET_ORDER)){
			for ($i = 0; $i<count($res); $i++) if (trim($res[$i][2])){
				// eliminate anchor
				$tmp = trim(preg_replace("/\#(.*)/","",$res[$i][2]));
				// don't crawl other than http
				if(preg_match("/(http):/",$tmp)){
					$this->links[$tmp]=$tmp;
				}
			}
			return true;
		}
		return false;
	}
	
	private function saveMails(){
		$this->mails=array_unique($this->mails);
		if(count($this->mails)){
			foreach($this->mails as $value){
				$this->saveMail($value, $this->wid);
			}
			$this->mails=array();
			return true;
		}
		else return false;
	}
	
	public function getDomainFromEmail($email = ""){
		if( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			// split on @ and return last value of array (the domain)
			$domain = array_pop(explode('@', $email));
			return $domain;
		}
		return "";
	}
	
	private function saveLinks(){
		$this->links=array_unique($this->links);
		if(count($this->links)){
			foreach($this->links as $value) if(!$this->existLink($value) && $value!=$this->url && $this->isNormal($value)) $this->saveLink($value,0);
			$this->links=array();
			return true;
		}
		else return false;
	}
	
	public function isNormal($url=""){
		$not_good_extensions=array(
			"pdf","doc","xml","js","wiki","gif","png","jpg","jpeg","xls","pps","exe","txt","db","vbs","css","ico"
		);
		foreach($not_good_extensions as $ext){
			if(preg_match("/\.(".$ext.")/",$url)){
				return false;
			}
		}
		return true;
	}
	
	private function maxNestingLevel(){
		// max function nesting level
		++$this->times;
		if($this->times > $this->max_times){
			return true;
		}
		return false;
	}
	
	public function startCrawl($url='',$wid=0){
		// max function nesting level
		if($this->maxNestingLevel()) return false;
		$this->url = $url;
		if($this->url == ''){
			$sql = "
				select
					url,
					wid
				from
					peas3_page
				where
					status='1'
					and uid='1'
				order by
					rand() asc
				limit 0,1
			";
			$rs = $this->db->query($sql);
			if($row = $rs->row()){
				$this->url = $row->url;
				$this->wid = $row->wid;
				$this->msg($this->url.' ::wil go to ');
				return $this->startCrawl($this->url, $this->wid);
			}
			else{
				$this->msg('There is no more to go.');
				return false;
			}
		}
		else{
			if($this->no_ssl==true && !preg_match('/^https/',$this->url)){
				if(!$this->isVisited($this->url)){
					$this->wid=$this->saveLink($this->url,0);
					$this->buffer='';
					$this->links=$this->mails=array();
					// curl part
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $this->url);
					curl_setopt($ch, CURLOPT_HEADER, 1);
					if(isset($_SERVER['HTTP_USER_AGENT'])) curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					if($this->buffer=curl_exec($ch)){
						if(!$this->only_mails){
							if($this->findLinks()){
								$this->saveLinks();
							}
						}
						if($this->findMails()){
							$this->saveMails();
						}
					}
					curl_close($ch);
					//
					$this->saveLink($this->url, 1);
					$this->msg($this->url . ' ::crawled');
					return $this->startCrawl();
				}
				else{
					$this->msg($this->url.' ::we have been here.');
					return $this->startCrawl();
				}
			}
			else{
					$this->msg($this->url.' ::we dont suport SSL connections.');
					return $this->startCrawl('');
			}
		}
	}
	
	private function saveLink($url,$visited=0){
		if($wid=$this->existLink($url)){
			if(!$this->isVisited($url) && $visited==1){
				$sql= "
				update
					peas3_page
				set
					status='2',
					date_crawled=now()
				where
					uid='1'
					and url='$url'
				";
				$this->db->query($sql);
				return ($this->db->affected_rows())?$wid:false;
			}
			else return false;
		}
		else{
			$sql = "
			insert IGNORE into
				peas3_page
			set
				uid='1',
				url='$url',
				status='".(($visited)?2:1)."',
				date_created=now()
				".(($visited)?",date_crawled=now()":"")."
			";
			$this->db->query($sql);
			return ($this->db->affected_rows())?($this->wid = $this->db->insert_id()):false;
		}
	}
    
	private function saveMail($mail,$wid=0)
	{
		$sess_array = $this->session->userdata('logged_in');
		$user_id = $sess_array['id'];
			
		$result = $this->email_manager->addEmail( $mail , $user_id , $this->input->post('list_id'));
		if( $result )
		{
			$this->msg($mail . ":: Email saved succesfully.");
			return true;
		}
		return false;
    }
	
	private function existLink($url){
		// max function nesting level
		if($this->maxNestingLevel()) return false;
		$sql = "
			select
				wid
			from
				peas3_page
			where
				uid='1'
				and url='$url'
			limit 0,1
		";
		$rs = $this->db->query($sql);
		if($row = $rs->row()){
			if($row->wid){
				return $row->wid;
			}
		}
		return false;
	}
	
	public function isVisited($url){
		$sql = "
			select
				count(*)
			from
				peas3_page
			where
				status='2'
				and uid='1'
				and url='$url'
		";
		$rs = $this->db->query($sql);
		if($row = $rs->result_array()){
			if(!empty( $row["count"] )){
				return true;
			}
		}
		return false;
	}
	
	// helper methods
	private function msg($str = ""){
		$this->_messages[] = $str;
		return $this->_messages;
	}
};