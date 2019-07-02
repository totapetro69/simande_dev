<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

	 	var $API=""; 
	 	var $_ci="";
		var	$html = '';

		function __construct() { 
			$this->_ci =& get_instance();
			$this->_ci->load->library('curl');
        	$this->_ci->load->helper('url'); 
			$this->_ci->load->library('session');
			//API_URL=str_replace('frontend/','backend/',base_url())."index.php";

			$this->validate_authen();
		}

		
	    public function validate_authen($url = null){

			if( $this->_ci->session->userdata('kd_group') == null ){
			    redirect(base_url().'auth');
			}
				
			else
			{
				$user = array();
				
				$param_user = array(
					'user_id' => $this->_ci->session->userdata('user_id'),
					'field' 	=>"USERS.KD_GROUP as kd_group"
				);

				$user=json_decode($this->_ci->curl->simple_get(API_URL."/api/login/user", $param_user), TRUE);
				$user_session = $user['message'];
				$this->_ci->session->set_userdata($user_session[0]);
				
				if($this->_ci->uri->uri_string() != null){

					$access = array();

					$param = array(
						'kd_group' => $this->_ci->session->userdata('kd_group'),
						'custom' => "MM.LINK_MODUL = '".($url != null ? $url : $this->_ci->uri->uri_string())."'",
						'jointable' => array(
							array("MASTER_MODUL AS MM","MM.KD_MODUL=USERS_GROUP_PRIVILAGES.KD_MODUL AND MM.ROW_STATUS>=0","LEFT")
						),
						'field' 	=>"C AS c, E as e, V as v, P as p"
					);

					$access=json_decode($this->_ci->curl->simple_get(API_URL."/api/login/auths", $param), TRUE);
					$access_session = $access['message'];

					if($access["status"] == false)
					{
						$array_items = array('c', 'e','v','p');

						$this->_ci->session->unset_userdata($array_items);
					}
					$this->_ci->session->set_userdata($access_session[0]);
				}
				else
				{
					//redirect(base_url());
				}
				
				/*var_dump($access);
				exit;*/
			}
	    }
	 public function set_kd_dealer($kd_dealer=null){
	 	$this->kd_dealer=$kd_dealer;
	 }
	 public function get_kd_dealer(){
	 	return $this->kd_dealer;
	 }
}