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
		$ldap_host = pg_fetch_result(DigiplayDB::query("SELECT val FROM configuration WHERE parameter = 'ldap_host'"),NULL,0);
		$ldap_port = pg_fetch_result(DigiplayDB::query("SELECT val FROM configuration WHERE parameter = 'ldap_port'"),NULL,0);
		$this->ldap_filter = pg_fetch_result(DigiplayDB::query("SELECT val FROM configuration WHERE parameter = 'ldap_filter'"),NULL,0);
		$this->ldap_dn = pg_fetch_result(DigiplayDB::query("SELECT val FROM configuration WHERE parameter = 'ldap_dn'"),NULL,0);
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
	function changepassword($password){
		if(!$this->login) trigger_error("Not logged into RaW LDAP", E_USER_ERROR);
		//return ldap_mod_replace ($this->link_identifier,$this->bind_rdn,array('userpassword' => $password));
	}
	
	function resetpassword($username,$universityid){
	
	}
	function get_members(){
		$result = ldap_search($this->link_identifier,"ou=People,dc=radio,dc=warwick,dc=ac,dc=uk","(|(rawMemberStatus=Member)(rawMemberStatus=Other))",array("rawUniversityNum","givenName","cn","uid","uidNumber","mail"));
		$array = array();
		for ($i = ldap_first_entry($this->link_identifier,$result);	$i!=false; $i = ldap_next_entry($this->link_identifier,$i)){
			$j = ldap_get_attributes($this->link_identifier,$i);
			
			$k = array();
			$k['username']		= $j['uid'][0];
			$k['id']			= $j['rawUniversityNum'][0];
			$k['name'] 			= ucwords(strtolower($j['cn'][0]));
			$k['nick'] 			= ucwords(strtolower($j['givenName'][0]));
			$k['email'] 		= $j['mail'];
			$array[] = $k;
		}
		return $array;
	}

	function get_member($rawUniversityNum){
		$result = ldap_search($this->link_identifier,"ou=People,dc=radio,dc=warwick,dc=ac,dc=uk","(&(|(rawMemberStatus=Member)(rawMemberStatus=Other))(rawUniversityNum=".$rawUniversityNum."))",array("rawUniversityNum","givenName","uid"));
		$array = array();
		for ($i = ldap_first_entry($this->link_identifier,$result);	$i!=false; $i = ldap_next_entry($this->link_identifier,$i)){
			$j = ldap_get_attributes($this->link_identifier,$i);
			
			$k = array();
			$k['username']		= $j['uid'][0];
			$k['id']			= $j['rawUniversityNum'][0];
			$k['nick'] 			= ucwords(strtolower($j['givenName'][0]));
		}
		if(isset($k))
			return $k;
		else
			return FALSE;
	}
}
?>
