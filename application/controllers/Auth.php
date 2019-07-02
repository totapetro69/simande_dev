<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	 var $API="";
	public function __construct()
    {
		    parent::__construct();
			//API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
			$this->load->library('form_validation');
        	$this->load->library('session');
        	$this->load->library('curl');
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


	public function index()
	{
		$this->load->view('auth/index');
	}
	public function login(){
		/*
		Check nik dan password ke database via web service
		*/
		$data = array();
		$datax="";
		$param = array(
			'user_id'=>$this->input->post('user_id'),
			'password'=>$this->input->post('password')
			);

		if($this->input->post('user_id')==''||$this->input->post('password')=='' ){
			$data = array(
				'message' => 'NIK / Password harus di isi',
				'status' => false
			);

			$this->output->set_output(json_encode($data));

		}else{
			$data=json_decode($this->curl->simple_get(API_URL."/api/Login/check",$param),TRUE);

			if($data){
				$param2 = array(
					'user_id' => $data[0]['USER_ID'],
					'jointable' => array(
						array("MASTER_DEALER AS MD","MD.KD_DEALER=USERS.KD_DEALER","LEFT"),
						array("MASTER_WILAYAH AS MW","MW.KD_PROPINSI=MD.KD_PROPINSI","LEFT"),
						array("USERS_GROUP AS UG","UG.KD_GROUP=USERS.KD_GROUP","LEFT")
					),
					'field' 	=>"MD.KD_KABUPATEN,MD.KD_JENISDEALER AS STATUS_CABANG,MD.KD_MAINDEALER,MD.KD_DEALER,USER_ID,USER_NAME,KD_DIV,USERS.KD_GROUP,UG.NAMA_GROUP,MW.KD_WILAYAH,KD_LEVEL,KD_STATUS,MD.NAMA_DEALER, MD.KD_DEALERAHM, USERS.TYPE_USERS, USERS.KD_LOKASI"
				);

				$user=json_decode($this->curl->simple_get(API_URL."/api/login/user", $param2), true);
				$user_session = $user['message'];
				
				$this->session->set_userdata(array_change_key_case($user_session[0],CASE_LOWER));
				// print_r($this->session->userdata());
				// exit;
				$data = array(
					'message' => 'Selamat datang '.$this->session->userdata("user_name").($this->session->userdata("nama_dealer")?' - '.$this->session->userdata("nama_dealer"): ''),
					'status' => true,
					'location' => base_url()
				);

				$this->output->set_output(json_encode($data));
				// redirect(base_url());
			}else{
				$data = array(
					'message' => 'NIK / Password salah, silahkan coba lagi',
					'status' => false
				);

				$this->output->set_output(json_encode($data));
			}
		}
	}
	public function logout(){
		$this->session->sess_destroy();
        redirect(base_url()."auth");
	}

	public function profile()
	{

		if(!empty($this->session->userdata('user_id')))
		{
			$param = array(
				'user_id' 	=> $this->session->userdata('user_id'), 
				'offset' 	=> ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
				'limit' 	=> 15,
				'jointable' => array(
					array("USERS_GROUP","USERS_GROUP.KD_GROUP=USERS.KD_GROUP","LEFT"),
					array("USERS_LEVEL","USERS_LEVEL.KD_LEVEL=USERS.KD_LEVEL","LEFT"),
					array("MASTER_DEALER","MASTER_DEALER.KD_DEALER=USERS.KD_DEALER","LEFT"),
					array("MASTER_DIVISI","MASTER_DIVISI.KD_DIV=USERS.KD_DIV","LEFT")
				),
				'field'	=> 'USERS.*,USERS_GROUP.NAMA_GROUP,MASTER_DIVISI.NAMA_DIV,USERS_LEVEL.NAMA_LEVEL,MASTER_DEALER.NAMA_DEALER, MASTER_DIVISI.CREATED_BY as MCre'
			);


			$data = array();
			$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/login/user", $param));

	        $this->load->view('auth/profile', $data);
		}
		else
		{
	        $this->load->view('errors/modal/not_found');
		}
		// $this->output->set_output(json_encode($data["list"]));
        
        $html = $this->output->get_output();
		$this->output->set_output(json_encode($html));

	}

	public function ganti_password()
	{

		if(!empty($this->session->userdata('user_id')))
		{
	        $this->load->view('auth/password');
	    }
		else
		{
	        $this->load->view('errors/modal/not_found');
		}
		
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));
	}

	public function update_password()
	{
		$this->form_validation->set_rules('passold', 'Password Lama', 'trim|required');
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
			$param_check = array(
				'user_id'=> $this->session->userdata("user_id"),
				'password'=> $this->input->post("passold")
			);

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$cek=json_decode($this->curl->simple_get(API_URL."/api/Login/check",$param_check),TRUE);
/*
			var_dump(count($cek));
			exit;*/

			if(count($cek) > 0)
			{
				$param = array(
					'user_id'=> $this->session->userdata("user_id"),
					'password'=> $this->input->post("password")
				);
	
				/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
				$hasil=$this->curl->simple_put(API_URL."/api/login/changepwd",$param, array(CURLOPT_BUFFERSIZE => 10));
	

        		$this->data_output($hasil, 'put');
			}
			else
			{
				$data = array(
					'message' => 'Password lama yg anda masukan salah',
					'status' => false				
				);

				$this->output->set_output(json_encode($data));
			}
	    }
	}

	
	public function error_auth($dialog=false)
	{
		$data['heading'] = 'Permission Error';
		$data['message'] = "<p>Anda tidak memilik akses pada halaman ini, silakan kembali ke <a href='".base_url('/')."' >menu utama</a></p>";
		if($dialog==false){
			$this->load->view('errors/html/error_404', $data);
		}else{
			$this->load->view('errors/modal/not_found', $data);
	        $html = $this->output->get_output();
	        $this->output->set_output(json_encode($html));
		}
		
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
