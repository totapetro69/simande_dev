<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	 var $API="";
	public function __construct()
    {
            parent::__construct();
			//API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
			$this->load->library('form_validation');
        	$this->load->library('session');
        	$this->load->library('curl');
			$this->load->library('pagination');
        	$this->load->helper('form');
        	$this->load->helper('url'); 
    }


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function upPass($user_id=null)
	{
        // var_dump($this->zetro_auth->encryptNET3DES($this->get('user_id').$this->get('password')));exit;
		$param = array(
			//111
			// 'custom' => "USER_ID NOT IN('SUPERUSER') AND ID BETWEEN 500 AND 550",  
			//'custom' => "PASSWORD = 'B6YnoyqHGnY='",  //111
			// 'custom' => "PASSWORD = 'VaXvT479JiU='",  //123
			'user_id' =>$user_id
		);
		if(!$user_id){ echo 'user id harus di isi';exit();}
		$data=json_decode($this->curl->simple_get(API_URL."/api/login/user", $param));

		$user = array();
		if($data){
			if($data->totaldata >0){
				foreach ($data->message as $key => $value) {
					$param_update = array(
						'user_id' => $value->USER_ID, 
						'password' => 123, 
					);

					 $this->curl->simple_put(API_URL."/api/login/changepwd",$param_update, array(CURLOPT_BUFFERSIZE => 10));

					array_push($user, $param_update);
				}
				
			}
		}

		$this->output->set_output(json_encode($user));

	}
	
	public function checkEncrypt($text){
		var_dump($this->encryptNET3DES($text));
	}


	public function encryptNET3DES($text) {
            //Secret Key
            $key1="Je ne vous oublie pas";
            $key2="!@#$%!@#$%";
            $key = substr($key1.$key2,0,24);

            //Initialization Vector
            $iv="";
            $array_iv = array(12,241,10,21,90,74,11,39);
            foreach ($array_iv as $value_iv) {
                    $iv .= chr($value_iv);
            }
	
            $td = mcrypt_module_open (MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
            $block = mcrypt_get_block_size('tripledes', 'cbc');
            $len = strlen($text);           
            $padding= $block-($len%$block);                  
            $text .= str_repeat(chr($padding),$padding);

            mcrypt_generic_init ($td, $key, $iv);
            $encrypt_text = mcrypt_generic ($td, $text);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return base64_encode($encrypt_text);    
	}    

	public function user_list()
	{
        $kd_dealer = $this->input->get('kd_dealer')?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

		$param = array(
			'keyword' 	=> $this->input->get('keyword'), 
			'kd_status' => $this->input->get('kd_status'), 
            //'kd_dealer' => $kd_dealer,
			'offset' 	=> ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
			'limit' 	=> 15,
			'jointable' => array(
				array("USERS_GROUP","USERS_GROUP.KD_GROUP=USERS.KD_GROUP","LEFT"),
				array("USERS_LEVEL","USERS_LEVEL.KD_LEVEL=USERS.KD_LEVEL","LEFT"),
				array("MASTER_DEALER","MASTER_DEALER.KD_DEALER=USERS.KD_DEALER","LEFT"),
				array("MASTER_DIVISI_V","MASTER_DIVISI_V.KD_DIVISI=USERS.KD_DIV","LEFT")
			),
			'field'	=> '*,USERS.ID as ID',
			'custom'=>"USERS.KD_GROUP NOT IN('Root','root')",
			// 'custom'=>"USERS_GROUP.ROW_STATUS>-1 OR USERS.KD_DEALER = '' ",
			'orderby' => 'USERS.USER_NAME'
		);

		/*if($kd_dealer!='MD'){
			$param["kd_dealer"] = $kd_dealer;
		}else{
			$param["type_users"] = $kd_dealer;
		}*/


	    if($this->input->get("kd_dealer")){
	      $param['kd_dealer'] = $this->input->get("kd_dealer");
	    }else{
	      $param['where_in']  = isDealerAkses();
	      $param['where_in_field'] = 'USERS.KD_DEALER';
	    }
		/*if($this->session->userdata("type_users")=='TM'){
			unset($param["kd_dealer"]);
			$param["custom"] .=" AND KD_DEALER IN(".str_replace("]","",str_replace("[","", isDealerAkses())).")";
		}*/
		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user", $param));
		//var_dump($data);exit();
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
        $param=array(
        	'custom'	=>"NAMA_GROUP NOT IN('Root')",
        	'orderby'	=>"NAMA_GROUP"
        );
		$data["groups"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup",$param));

		$pagination = $this->template->pagination($config);

		$this->pagination->initialize($pagination);
		$data['pagination'] = $this->pagination->create_links();
		$this->template->site('user/index',$data);
	}

	public function get_detail()
	{

	}


	/** z
	* Load form add new user
	*/
	public function add_user($data_only=null)
	{
		$this->auth->validate_authen("user/user_list");
        $kd_maindealer = $this->input->get('kd_maindealer') ?$this->input->get('kd_maindealer'):$this->session->userdata("kd_maindealer");
        $kd_dealer = $this->input->get('kd_dealer') ?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

		$param_area = array(
			'kd_maindealer' => $kd_maindealer
		);
		$data["user_area"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer", $param_area));

		$param_lokasi = array(
			'kd_maindealer' => $kd_maindealer,
		);

		if($this->input->get('type_users') == 'D' OR $this->input->get('kd_maindealer') == ''){
			$param_lokasi['kd_dealer'] = $kd_dealer;
		}

		$data["lokasidealer"]=json_decode($this->curl->simple_get(API_URL."/api/master_general/lokasidealer", $param_lokasi));

		$param=array(
        	'custom'	=>"NAMA_GROUP NOT IN('Root')",
        	'orderby'	=>"NAMA_GROUP"
        );
		$data["groups"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup",$param));
		$data["levels"]=json_decode($this->curl->simple_get(API_URL."/api/login/users_level"));
		$data["divisions"]=json_decode($this->curl->simple_get(API_URL."/api/master/divisi"));
		// $data["dealers"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer"));
		$data["maindealers"]=json_decode($this->curl->simple_get(API_URL."/api/master/maindealer"));
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));

		if($data_only == 'true'){
			$this->output->set_output(json_encode($data));
		}
		else{
			if($this->input->get('n')){
				$param=array(
					"user_id" => $this->input->get('n')
				);
				$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user",$param));
			}
	        $this->load->view('user/add_user',$data);
	        $html = $this->output->get_output();
	        
			$this->output->set_output(json_encode($html));
		}

 	}
	 
	/* simpan data dari form users to database */
	public function store_user(){

	    $this->form_validation->set_rules('user_id', 'NIK', 'required|min_length[5]|trim');
	    $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
	    //$this->form_validation->set_rules('password', 'Password', 'required|trim');
	    $this->form_validation->set_rules('kd_group', 'Grup User', 'required|trim');
	    // $this->form_validation->set_rules('kd_level', 'Level User', 'required|trim');
	    // $this->form_validation->set_rules('kd_div', 'Divisi', 'required|trim');
	    //$this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');

	    if ($this->form_validation->run() === FALSE)
	    {
			$data = array(
				'status' => false,
				'message' => validation_errors()
			);

			$this->output->set_output(json_encode($data));
	    }
	    else
	    {
			$param = array(
				'type_users'=> $this->input->post("type_users"),
				'user_id'=> str_replace(' ','',$this->input->post("user_id")),
				// 'user_id'=> html_escape(trim($this->input->post("user_id"), " ")),
				'user_name'=> html_escape($this->input->post("user_name")),
				'type_password'=> $this->input->post("type_password"),
				'password'=> $this->input->post("password"),
		        'kd_div'=> $this->input->post("kd_div"),
		        'kd_dealer'=>  $this->input->post("type_users") == 'D'?$this->input->post("kd_dealer"):'',
		        'kd_maindealer'=>  $this->input->post("kd_maindealer"),
		        'kd_lokasi'=> $this->input->post("kd_lokasi"),
		        'kd_group'=> $this->input->post("kd_group"),
		        'kd_level'=> $this->input->post("kd_level"),
		        'apv_doc' => $this->input->post("apv_doc"),
		        'kd_status'=> 0,
		        'created_by'=> $this->session->userdata('user_id')
			);

			// var_dump($param);exit;

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_post(API_URL."/api/login/user",$param, array(CURLOPT_BUFFERSIZE => 10));

			$data = json_decode($hasil);
			if($data->recordexists==true){
				$param["lastmodified_by"] = $this->session->userdata('user_id');
				$hasil=$this->curl->simple_put(API_URL."/api/login/user",$param, array(CURLOPT_BUFFERSIZE => 10));
				$data = json_decode($hasil);
			}

			$detail = false;


	        if($data AND $param['type_users'] == 'MD')
	        {
	            if($data->message>0){
	                $detail = $this->store_detail();
	            }
	        }else{
				$detail = true;
	        }  


	        if($detail == true){
				$this->session->set_flashdata('tr-active', $data->message);
            	$this->data_output($hasil, 'post', base_url('user/user_list'));
	        }



		}
	}

	public function store_detail()
	{
        $detail = json_decode($this->input->post("detail"),true);

        for ($i=0; $i < count($detail); $i++) { 

            $param = array(
                'users_id' => $detail[$i]['user_id'],
                'kd_dealer' => $detail[$i]['kd_dealer'],
                'auth_status' => $detail[$i]['auth_status'],
                'created_by' => $this->session->userdata("user_id")
            );
    
            $hasil = $this->curl->simple_post(API_URL . "/api/menu/users_area", $param, array(CURLOPT_BUFFERSIZE => 10));
        }

        return true;
	}
	function apv_docs($user_id=null){
		$this->auth->validate_authen('user/user_list');
		$data=array();
		$param = array(
			'user_id' => $user_id
		);
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user",$param));
		$param=array(
			'field'=> "KD_MODUL,NAMA_MODUL,LEVEL_APV,ISNULL((SELECT S.APP_LEVEL FROM USERS_APPROVAL S WHERE S.KD_DOC=KD_MODUL AND USER_ID='".$user_id."'),0)APV_LEVEL",
			'orderby'=>"NAMA_MODUL"
		);
		$data["lstdoc"] = json_decode($this->curl->simple_get(API_URL."/api/setup/modul_apv",$param));
		$this->load->view('user/user_apvdoc', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));
	}
	function apv_docs_simpan(){
		$data=array();
		$detail = json_decode($this->input->post("detail"),true);
		for($i=0;$i< count($detail);$i++){
			$param=array(
				'user_id'	=> $this->input->post('user_id'),
				'kd_doc'	=> $detail[$i]['kd_doc'],
				'app_level'	=> $detail[$i]["lvl"],
				'created_by'=> $this->session->userdata("user_id")
			);
			$hasil = $this->curl->simple_post(API_URL . "/api/login/users_app", $param, array(CURLOPT_BUFFERSIZE => 10));
			$data=json_decode($hasil);
			if($data){
				if($data->recordexists==true){
					$param["lastmodified_by"] = $this->session->userdata("user_id");
					$hasil=$this->curl->simple_put(API_URL . "/api/login/users_app", $param, array(CURLOPT_BUFFERSIZE => 10));
				}
			}
		}
		$this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('user/user_list'));

	}
	public function edit_user($user_id)
	{
		$this->auth->validate_authen('user/user_list');

		$param = array(
			'user_id' => $user_id,
			'kd_status' => $this->input->get('status')
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user",$param));
		$data["groups"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup"));
		$data["levels"]=json_decode($this->curl->simple_get(API_URL."/api/login/users_level"));
		$data["divisions"]=json_decode($this->curl->simple_get(API_URL."/api/master/divisi"));
		$data["dealers"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));

		/*var_dump($data);
		exit;*/

        $this->load->view('user/edit_user', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));
 	}
 
	/* update data dari form users to database */
	public function update_user($id)
	{
	    $this->form_validation->set_rules('kd_group', 'Grup User', 'required|trim');
	    $this->form_validation->set_rules('kd_level', 'Level User', 'required|trim');
	    $this->form_validation->set_rules('kd_div', 'Divisi', 'required|trim');
	    //$this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
	    $this->form_validation->set_rules('kd_status', 'Status', 'required|trim');

	    if ($this->form_validation->run() === FALSE)

	    {
			$data = array(
				'status' => false,
				'message' => validation_errors()
			);

			$this->output->set_output(json_encode($data));

	    }

	    else

	    {

			$param = array(
				'user_id'=> html_escape($this->input->post("user_id")),
				'user_name'=> html_escape($this->input->post("user_name")),
		        'kd_div'=> $this->input->post("kd_div"),
		        'kd_dealer'=>  $this->input->post("kd_dealer"),
		        'kd_group'=> $this->input->post("kd_group"),
		        'kd_level'=> $this->input->post("kd_level"),
		        'kd_status'=> $this->input->post("kd_status"),
		        'lastmodified_by'=> $this->session->userdata('user_id')
			);

			/*print_r($hasil);
			exit;*/
			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_put(API_URL."/api/login/user",$param, array(CURLOPT_BUFFERSIZE => 10));

			$this->session->set_flashdata('tr-active', $id);

	        $this->data_output($hasil, 'put');

		}
		
	}

	public function delete_user($user_id)
	{
		$param = array(
			'user_id' => $user_id,
	        'lastmodified_by'=> $this->session->userdata('user_id')
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/login/user",$param));

        $this->data_output($data, 'delete', base_url('user/user_list'));

	}

	public function edit_password($user_id)
	{
		$param = array(
			'user_id' => $user_id
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user",$param));

        $this->load->view('user/edit_password', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));

	}

	public function upddate_password($id)
	{
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');


	    if ($this->form_validation->run() === FALSE)
	    {
			$data = array(
				'status' => false,
				'message' => validation_errors()
			);

			$this->output->set_output(json_encode($data));
	    }
	    else
	    {
			$param = array(
				'user_id'=> $this->input->post("user_id"),
				'password'=> $this->input->post("password")
			);

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_put(API_URL."/api/login/changepwd",$param, array(CURLOPT_BUFFERSIZE => 10));

			$this->session->set_flashdata('tr-active', $id);

    		$this->data_output($hasil, 'put');
	

	    }
	}

	public function list_dealer($user_id)
	{
		$this->auth->validate_authen('user/user_list');

		$param = array(
			'user_id' => $user_id
		);

		$data = array();
		
		$data["user_id"]=$user_id;
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/menu/users_area",$param));
        $data["user_area"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer"));

		// $this->output->set_output(json_encode($data));

        $this->load->view('user/list_dealer', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));
	}

	public function user_list_update()
	{
		$param = array(
			'kd_dealer'=> $this->input->post("kd_dealer"),
			'users_id'=> $this->input->post("user_id"),
			'auth_status'=> $this->input->post("status"),
			'row_status'=> $this->input->post("status") == 1?0:-1,
	        'created_by'=> $this->session->userdata('user_id'),
	        'lastmodified_by'=> $this->session->userdata('user_id')
		);

		// var_dump($param);exit;

		/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
		$hasil=$this->curl->simple_post(API_URL."/api/menu/users_area",$param, array(CURLOPT_BUFFERSIZE => 10));
		$method = 'post';

        $data = json_decode($hasil);
        if($data->recordexists==true){
			$hasil=$this->curl->simple_put(API_URL."/api/menu/users_area_status",$param, array(CURLOPT_BUFFERSIZE => 10));
			$method = 'put';
        }


        $this->data_output($hasil, $method);

	}

	public function user_typeahead()
	{

	    if($this->input->get("kd_dealer")){
	      $param['kd_dealer'] = $this->input->get("kd_dealer");
	    }else{
	      $param['where_in']  = isDealerAkses();
	      $param['where_in_field'] = 'USERS.KD_DEALER';
	    }

		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user", $param));

		foreach ($data["list"]->message as $key => $message) {
			$data_message[0][$key] = $message->USER_ID;
			$data_message[1][$key] = $message->USER_NAME;
		}

		$result['keyword'] = array_merge($data_message[0], $data_message[1]);

		$this->output->set_output(json_encode($result));
		
	}

	public function user_group_list()
	{

		$param = array(
			'kd_group' 		=> $this->input->get('kd_group'), 
			'row_status' 	=> $this->input->get('row_status'), 
			'keyword' 		=> $this->input->get('keyword'), 
			'offset' 		=> ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
			'limit' 		=> 15,
			'orderby' 		=> 'ID desc'

		);


		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup", $param));
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
		

		$pagination = $this->template->pagination($config);

		$this->pagination->initialize($pagination);
		$data['pagination'] = $this->pagination->create_links();

        
		$this->template->site('user/user_group',$data);
		
	}

	public function user_group_typeahead()
	{

	    if($this->input->get("kd_dealer")){
	      $param['kd_dealer'] = $this->input->get("kd_dealer");
	    }else{
	      $param['where_in']  = isDealerAkses();
	      $param['where_in_field'] = 'USERS.KD_DEALER';
	    }

		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup", $param));

		foreach ($data["list"]->message as $key => $message) {
			$data_message[0][$key] = $message->KD_GROUP;
			$data_message[1][$key] = $message->NAMA_GROUP;
		}

		$result['keyword'] = array_merge($data_message[0], $data_message[1]);

		$this->output->set_output(json_encode($result));

	}

	public function add_user_group()
	{
        $this->load->view('user/add_user_group');
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));

	}

	public function store_user_group()
	{
	    $this->form_validation->set_rules('kd_group', 'Kode Group', 'required|trim|max_length[5]');
	    $this->form_validation->set_rules('nama_group', 'Nama Group', 'required|trim');

	    if ($this->form_validation->run() === FALSE)
	    {
			$data = array(
				'status' => false,
				'message' => validation_errors()
			);

			$this->output->set_output(json_encode($data));
	    }
	    else
	    {
			$param = array(
				'kd_group'=> html_escape($this->input->post("kd_group")),
				'nama_group'=> html_escape($this->input->post("nama_group")),
		        'created_by'=> $this->session->userdata('user_id')
			);

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_post(API_URL."/api/login/usergroup",$param, array(CURLOPT_BUFFERSIZE => 10));

			$data = json_decode($hasil);
			$this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('user/user_group_list'));
			
		}

	}

	public function edit_user_group($kd_group, $row_status)
	{
		$this->auth->validate_authen('user/user_group_list');
		
		$param = array(
			'kd_group' => $kd_group,
			'row_status' => $row_status
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup",$param));
/*
		print_r($data);
		exit;*/

        $this->load->view('user/edit_user_group', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));

	}

	public function update_user_group($id)
	{
	    $this->form_validation->set_rules('nama_group', 'Nama Group', 'required|trim');

	    if ($this->form_validation->run() === FALSE)
	    {
			$data = array(
				'status' => false,
				'message' => validation_errors()
			);

			$this->output->set_output(json_encode($data));
	    }
	    else
	    {
			$param = array(
				'kd_group'=> html_escape($this->input->post("kd_group")),
				'nama_group'=> html_escape($this->input->post("nama_group")),
				'row_status' => html_escape($this->input->post("row_status")),
		        'lastmodified_by'=> $this->session->userdata('user_id')
			);

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_put(API_URL."/api/login/usergroup",$param, array(CURLOPT_BUFFERSIZE => 10));
/*
			print_r($hasil);
			exit;*/
			$this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
			
		}

	}


	public function delete_user_group($kd_group)
	{
		$param = array(
			'kd_group' => $kd_group
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/login/usergroup",$param));

        $this->data_output($data, 'delete', base_url('user/user_group_list'));


	}

	public function user_otorisasi()
	{

		$data = array();
		$kd_group = $this->input->get('kd_group')?$this->input->get('kd_group'):$this->session->userdata('kd_group');
		$param_child = array(
			'orderby' 	=> 'MASTER_MODUL.NAMA_MODUL asc',
			'jointable' => array(
				array("USERS_GROUP_PRIVILAGES UGP","UGP.KD_MODUL=MASTER_MODUL.KD_MODUL AND AND UGP.ROW_STATUS >= 0 AND UGP.KD_GROUP='".$kd_group."'","LEFT"),
				array("USERS_GROUP UG"," UG.ROW_STATUS >= 0 AND UG.KD_GROUP=UGP.KD_GROUP","LEFT")
			),
			'field'		=> 'MASTER_MODUL.*,
							(SELECT UGR.NAMA_GROUP FROM USERS_GROUP UGR WHERE UGR.ID=UG.ID) AS NAMA_GROUP,
							(SELECT UGR.KD_GROUP FROM USERS_GROUP UGR WHERE UGR.ID=UG.ID) AS KD_GROUP,
							(SELECT UGPR.C FROM USERS_GROUP_PRIVILAGES UGPR WHERE UGPR.ID=UGP.ID) AS C,
							(SELECT UGPR.E FROM USERS_GROUP_PRIVILAGES UGPR WHERE UGPR.ID=UGP.ID) AS E,
							(SELECT UGPR.V FROM USERS_GROUP_PRIVILAGES UGPR WHERE UGPR.ID=UGP.ID) AS V,
							(SELECT UGPR.P FROM USERS_GROUP_PRIVILAGES UGPR WHERE UGPR.ID=UGP.ID) AS P,
							(SELECT UGPR.ID FROM USERS_GROUP_PRIVILAGES UGPR WHERE UGPR.ID=UGP.ID) AS ID,
							',
			// 'kd_modul' => $this->input->get('kd_modul'), 
			'custom' => ""

		);
		$tree= json_decode($this->curl->simple_get(API_URL."/api/setup/modul",$param_child));


		$message = $this->parseTree($tree->message);

		$data['tree'] = $this->printTree($message);

		$data["groups"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup"));
		$data["moduls"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul", array('custom' => "PARENT_MODUL = '' ") ));

        // $this->output->set_output(json_encode($tree));
		$this->template->site('user/user_otorisasi',$data);

	}

	public function parseTree($tree, $root = null) {
	    $return = array();
	    # Traverse the tree and search for direct children of the root

	    foreach($tree as $child => $parent) {
	        # A direct child is found
	        if($parent->PARENT_MODUL == $root) {
	        // if($parent->PARENT_MODUL == $root && ($group != null ?  $parent->KD_GROUP == $group : '')) {
	            # Remove item from tree (we don't need to traverse this again)
	            unset($tree[$child]);
	            # Append the child into result array and parse its children
	            $return[] = array(
	                'ID' 			=> $parent->ID,
	                'KD_MODUL' 		=> $parent->KD_MODUL,
	                'KD_GROUP' 		=> $parent->KD_GROUP,
	                'NAMA_GROUP' 	=> $parent->NAMA_GROUP,
	                'ROW_STATUS' 	=> $parent->ROW_STATUS,
	                'NAMA_MODUL' 	=> $parent->NAMA_MODUL,
	                'ICON_MODUL' 	=> $parent->ICON_MODUL,
	                'PARENT_MODUL' 	=> $parent->PARENT_MODUL,
	                'C' 			=> $parent->C,
	                'E' 			=> $parent->E,
	                'V' 			=> $parent->V,	
	                'P' 			=> $parent->P,
	                'CHILDREN' 		=> $this->parseTree($tree, $parent->KD_MODUL)
	            );
	        }
	    }

	    return empty($return) ? null : $return;    
	}

	public function printTree($tree, $parent = false) {
		
		$html="";
	    if(!is_null($tree) && count($tree) > 0):
	    	$html .= '<div class="accordion">';
	        foreach($tree as $node):

	        	$tot_child = count($node['CHILDREN']);

	        	$html .= '<div class="accordion-group">';
	        	$html .= '<div class="accordion-heading area">';
	        	$html .= '<span id="HEAD-'.$node['KD_MODUL'].'" class="accordion-toggle">

	        				<input type="hidden" name="id" value="'.$node['ID'].'" >
	        				<input type="hidden" name="kd_group" value="'.$node['KD_GROUP'].'" >
	        				<input type="hidden" name="kd_modul" value="'.$node['KD_MODUL'].'" >

	        				<a data-toggle="collapse" href="#'.$node['KD_MODUL'].'">
	        				<span class="badge">
		        				'.$tot_child.' sub menu
		        				</span>
	        				'.$node['NAMA_MODUL'].'
	        				</a>


	        				<div class="pull-right">
								<span class="badge bg-info"><input id="c-'.$node['KD_MODUL'].'" name="c" value="c" type="checkbox" class="checked" '.($node['C'] == 1 ? "checked" : "").'></span>
								<span class="badge bg-success"><input id="e-'.$node['KD_MODUL'].'" name="e" value="e" type="checkbox" class="checked" '.($node['E'] == 1 ? "checked" : "").'></span>
								<span class="badge bg-warning"><input id="v-'.$node['KD_MODUL'].'" name="v" value="v" type="checkbox" class="checked" '.($node['V'] == 1 ? "checked" : "").'></span>
								<span class="badge bg-danger"><input id="p-'.$node['KD_MODUL'].'" name="p" value="p" type="checkbox" class="checked" '.($node['P'] == 1 ? "checked" : "").'></span>
		        				
	        				</div>

        					</span>
	        				

        					';
	        	$html .= '</div>';

	        	if($tot_child != 0):
				$html .= '<div class="accordion-body collapse" id="'.$node['KD_MODUL'].'">';
				$html .= '<div class="accordion-inner">';
				$html .= '<div class="accordion" id="equipamento1">';

	            $html .= $this->printTree($node['CHILDREN'],true);

	        	$html .= '</div>';
	        	$html .= '</div>';
	        	$html .= '</div>';
	        	endif;
	        	$html .= '</div>';
	        	

	        endforeach;
	        $html .= '</div>';
	    
	    endif;
		return $html;
	}

	public function add_user_otorisasi()
	{
		$arrayName = array('orderby' => 'NAMA_GROUP'  );
		$data["groups"]=json_decode($this->curl->simple_get(API_URL."/api/login/usergroup",$arrayName));
		$param=array(
			'orderby' => 'NAMA_MODUL'
		);
		$data["moduls"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul",$param));

        $this->load->view('user/add_user_otorisasi', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));

	}

	
	public function update_user_otorisasi()
	{
		$param = array(
			'kd_modul'			=>	$this->input->post("kd_modul"),
			'kd_group'			=>	$this->input->post("kd_group"),
			'c'					=>	$this->input->post("c"),
			'e'					=>	$this->input->post("e"),
			'v'					=>	$this->input->post("v"),
			'p'					=>	$this->input->post("p"),
			'created_by'	=>	$this->session->userdata('user_id'),
			'lastmodified_by'	=>	$this->session->userdata('user_id')
		);


        $hasil = $this->curl->simple_post(API_URL . "/api/login/auths", $param, array(CURLOPT_BUFFERSIZE => 10));
        $method = "post";

        if(json_decode($hasil)->recordexists==TRUE){

			$hasil=$this->curl->simple_put(API_URL."/api/login/auths",$param, array(CURLOPT_BUFFERSIZE => 10));

            $method = "put";

        }


		// $this->session->set_flashdata('tr-active', $id);
		
        $this->data_output($hasil, $method);
		
	}



	public function delete_user_otorisasi()
	{
		$param = array(
			'kd_modul' => $this->input->post("kd_modul"),
			'kd_group' => $this->input->post("kd_group"),
	        'lastmodified_by'=> $this->session->userdata('user_id')
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/login/auths",$param));

        $this->data_output($data, 'delete');

	}

	public function get_nik($kd_dealer = null)
	{
		if($kd_dealer == 'MD'){
	       	$param["link"] 	= 'list9a';
	   		$param["param"]	= 'MD';

	   		$list = json_decode($this->curl->simple_get(API_URL."/api/login/webservice",$param), true);
	   		$list = json_decode($list, true);

	   		$data = [];

	        for ($i = 0; $i < count($list); $i++) {
	            $new_data = array(
	                'NIK' => $list[$i]['nik'],
	                'NAMA' => str_replace("'", "", rtrim($list[$i]['nama'])),
	                'PASSWORD' => $list[$i]['password']
	            );

	            array_push($data, $new_data);
			}


	   		$param["param"]	= 'SP';

	   		$list = json_decode($this->curl->simple_get(API_URL."/api/login/webservice",$param), true);
	   		$list = json_decode($list, true);

	        for ($j = 0; $j < count($list); $j++) {
	            $new_data = array(
	                'NIK' => $list[$j]['nik'],
	                'NAMA' => str_replace("'", "", rtrim($list[$j]['nama'])),
	                'PASSWORD' => $list[$j]['password']
	            );

	            array_push($data, $new_data);
			}

			// $data = (object) $data;
		}
		else{
	        $param = array(
	        	'field' => "NIK, NAMA, PASSWORD",
	            'custom' => "(KD_STATUS = 'STS-1' OR KD_STATUS = 'STS-2' OR KD_STATUS = 'STS-3') AND ROW_STATUS >= 0 AND NIK NOT IN(SELECT USER_ID FROM USERS WHERE ROW_STATUS >= 0)"
	        );

	        if($kd_dealer != null){
	        	$param['kd_dealer'] = $kd_dealer;
	        }

	        $nik = json_decode($this->curl->simple_get(API_URL."/api/master_general/karyawan",$param));

	        if($nik && (is_array($nik->message) || is_object($nik->message))){
	        	$data = $nik->message;
	        }
	        else{
	        	$data = array(['NIK'=>'','NAMA'=>'','PASSWORD'=>'']);
	        }
		}


        $this->output->set_output(json_encode($data));
	}

	public function get_detail_karyawan()
	{


        // $param["param"] = $this->session->userdata("kd_dealer");
        // $param["link"] = "list9a";
        // $data = json_decode($this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true)), true);


       	$param["link"] 	= 'list10';
   		$param["param"]	= $this->input->get('user_id');

   		$list = json_decode($this->curl->simple_get(API_URL."/api/login/webservice",$param), true);
   		$list = json_decode($list, true);

   		if(count($list) > 0):
	        for ($i = 0; $i < count($list); $i++) {
	            $data = array(
	                'nik' => $list[$i]['nik'],
	                'nama' => str_replace("'", "", rtrim($list[$i]['nama'])),
	                'kd_status' => $list[$i]['kdstatus'],
	                'kd_perusahaan' => ($list[$i]['kdperusahaan']=='TM')?'T10':$list[$i]['kdperusahaan'],
	                'kd_cabang' => $list[$i]['kdcabang'],
	                'kd_divisi' => $list[$i]['kddivisi'],
	                'kd_jabatan' => $list[$i]['kdjabatan'],
	                'personal_jabatan' => trim($list[$i]['personaljabatan']),
	                'personal_level' => $list[$i]['personallevel'],
	                'atasan_langsung' => $list[$i]['atasanlangsung'],
	                'password' => $list[$i]['password'],
	                'tgl_lahir' => $list[$i]['tgllahir'],
	                'tgl_masuk' => $list[$i]['tglmasuk'],
	                'pendidikan' => $list[$i]['kdstudi']
	            );
			}
		else:

            $data = array(
                'nik' => '',
                'nama' => '',
                'kd_status' => '',
                'kd_perusahaan' => '',
                'kd_cabang' => '',
                'kd_divisi' => '',
                'kd_jabatan' => '',
                'personal_jabatan' => '',
                'personal_level' => '',
                'atasan_langsung' => '',
                'password' => '',
                'tgl_lahir' => '',
                'tgl_masuk' => '',
                'pendidikan' => ''
            );
		endif;
		$data = (object) $data;

    	$this->output->set_output(json_encode($data));
	}

    /**
     * [data_output description]
     * @param  [type] $hasil [description]
     * @return [type]        [description]
     */
    function data_output($hasil = NULL, $method = '', $location = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil disimpan",
						'location' => $location
                    );
                } else {
                    $result = $hasil;
                }
                $this->output->set_output(json_encode($result));
                break;

            case 'put':
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil diupdate",
						'location' => $location
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal diupdate',
                        'status' => false
                    );

                    $this->output->set_output(json_encode($result));
                }
                break;

            case 'delete':
                if ($hasil) {
                    $result = array(
                        'status' => true,
						'message' => 'data berhasil dihapus',
						'location' => $location
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal dihapus',
                        'status' => false
                    );

                    $this->output->set_output(json_encode($result));
                }
                break;
        }
    }

}
