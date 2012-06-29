<?php
class LDAP{
	private $ldap_dn;
	private $ldap_filter;
	private $link_identifier;
	private $result_identifier;
	private $result_entry_identifier;
	private $bind_rdn;
	private $username;
	private $member;
	
	private $connection = false;
	private $login = false;
	
	function __construct(){
		$ldap_host = Config::get_param("ldap_host");
		$ldap_port = Config::get_param("ldap_port");
		$this->ldap_filter = Config::get_param("ldap_filter");
		$this->ldap_dn = Config::get_param("ldap_dn");
		$this->link_identifier=@ldap_connect("ldap://".$ldap_host.":".$ldap_port);
		if(!$this->link_identifier) trigger_error("LDAP Connection failure", E_USER_ERROR);
		
		ldap_set_option($this->link_identifier, LDAP_OPT_PROTOCOL_VERSION, 3);
		$this->connection = true;
	}
	function __destruct(){
		if($this->connection) ldap_unbind($this->link_identifier);
	}
	function login($username,$password){

		if(!$this->login||$this->username != $username){
			$this->username = $username;
			$this->result_identifier = ldap_search($this->link_identifier,$this->ldap_dn,"(&(uid=".$this->username.")".$this->ldap_filter.")",array("uid","givenName","sn"));
			if(!$this->result_identifier) return false;	
			if (ldap_count_entries($this->link_identifier, $this->result_identifier) != 1) return false;

			$this->result_entry_identifier = ldap_first_entry($this->link_identifier, $this->result_identifier);
			if(!$this->result_entry_identifier) return false;
			
			$this->bind_rdn = ldap_get_dn($this->link_identifier, $this->result_entry_identifier);
			
			if (!@ldap_bind($this->link_identifier, $this->bind_rdn, $password))	return false;
		}
		$this->login = true;
		return true;	
	}
	function login_status(){
		return $this->login;
	}
	function userdetails(){
		if(!$this->login) trigger_error("Not logged into RaW LDAP", E_USER_ERROR);
		
		if(!$this->member){
			$member_data = ldap_get_attributes($this->link_identifier,$this->result_entry_identifier);
			$this->member = array();
			$this->member['username']		= $member_data['uid'][0];
			$this->member['first_name']		= ucwords(strtolower($member_data['givenName'][0]));
			$this->member['surname'] 			= ucwords(strtolower($member_data['sn'][0]));
		}
		
		return $this->member;
	}
}
?>
