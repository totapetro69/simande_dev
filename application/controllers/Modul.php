<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {
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

			if($this->session->userdata('kd_group') == null)
			{
				redirect("auth");

			}
        	/*print_r($this->session->userdata());
        	exit;*/

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
	public function modul_list()
	{

		$param = array(
			// 'custom'	=> "ROW_STATUS =".$this->input->get('row_status')."  AND NAMA_MODUL LIKE '%".$this->input->get('keyword')."%'",
			'row_status' 	=> $this->input->get('row_status'), 
			'keyword' 	=> $this->input->get('keyword'), 
			'custom'	=> ($this->input->get('row_status') == -2) ? "ROW_STATUS >=".$this->input->get('row_status') : '',
			'offset' 	=> ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
			'limit' 	=> 100,
			'orderby' => 'PARENT_MODUL, URUTAN_MODUL,NAMA_MODUL,ID asc'
		);


		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul", $param));


		       
        $string = link_pagination(); 
        // var_dump(count($string));exit;
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );


		$pagination = $this->template->pagination($config);

		$this->pagination->initialize($pagination);
		$data['pagination'] = $this->pagination->create_links();

		$this->template->site('modul/index',$data);
	}

	public function modul_typeahead()
	{
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul"));

		foreach ($data["list"]->message as $key => $message) {
			$result['keyword'][$key] = $message->NAMA_MODUL;
		}

		$this->output->set_output(json_encode($result));

	}


	public function add_modul()
	{
		$param=array(
			'orderby'	=> 'NAMA_MODUL'
		);
		$data["moduls"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul",$param));
/*
		var_dump($data);
		exit;*/

        $this->load->view('modul/add_modul', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));

	}

	public function store_modul(){

	    $this->form_validation->set_rules('kd_modul', 'Kode modul', 'required|trim|max_length[5]');
	    $this->form_validation->set_rules('urutan_modul', 'Urutan', 'required|is_numeric|is_natural_no_zero');
	    $this->form_validation->set_rules('nama_modul', 'Nama Modul', 'required|trim');

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
				'kd_modul'=> strtoupper(html_escape($this->input->post("kd_modul"))),
				'nama_modul'=> html_escape($this->input->post("nama_modul")),
				'urutan_modul'=> $this->input->post("urutan_modul"),
		        'icon_modul'=> $this->input->post("icon_modul"),
		        'parent_modul'=>  $this->input->post("parent_modul"),
		        'link_modul'=> ($this->input->post("link_modul") == null)? "null": $this->input->post("link_modul"),
		        'parent_status'=>  $this->input->post("parent_status") ? 1 : 0,
		        'created_by'=> $this->session->userdata('user_id')
			);

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_post(API_URL."/api/setup/modul",$param, array(CURLOPT_BUFFERSIZE => 10));

			$data = json_decode($hasil);
			$this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post');//base_url('modul/modul_list')

		}
	}

	public function edit_modul($kd_modul, $row_status)
	{
		$this->auth->validate_authen('modul/modul_list');
		
		$param = array(
			'kd_modul' => $kd_modul,
			'row_status' => $row_status
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul",$param));
		$param=array(
			'orderby'	=> 'NAMA_MODUL'
		);
		$data["moduls"]=json_decode($this->curl->simple_get(API_URL."/api/setup/modul",$param));
/*
		print_r($data);
		exit;*/

        $this->load->view('modul/edit_modul', $data);
        $html = $this->output->get_output();
        
		$this->output->set_output(json_encode($html));

	}

	public function update_modul($id)
	{
	    $this->form_validation->set_rules('kd_modul', 'Kode modul', 'required|trim');
	    $this->form_validation->set_rules('urutan_modul', 'Urutan', 'required|is_numeric|is_natural_no_zero');
	    $this->form_validation->set_rules('nama_modul', 'Nama Modul', 'required|trim');

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
				'kd_modul'=> html_escape($this->input->post("kd_modul")),
				'nama_modul'=> html_escape($this->input->post("nama_modul")),
				'urutan_modul'=> $this->input->post("urutan_modul"),
		        'icon_modul'=> $this->input->post("icon_modul"),
		        'parent_modul'=>  $this->input->post("parent_modul"),
		        'link_modul'=> ($this->input->post("link_modul") == null)? "null": $this->input->post("link_modul"),
		        'parent_status'=>  $this->input->post("parent_status") ? 1 : 0,
				'row_status' => $this->input->post("row_status"),
		        'lastmodified_by'=> $this->session->userdata('user_id')
			);

			/* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
			$hasil=$this->curl->simple_put(API_URL."/api/setup/modul",$param, array(CURLOPT_BUFFERSIZE => 10));
			/*print_r($param);
			print_r($hasil);
			exit;*/
			$this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
			
		}

	}

	public function delete_modul($kd_modul)
	{
		$param = array(
			'kd_modul' => $kd_modul
		);

		$data = array();
		$data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/setup/modul",$param));

        $this->data_output($data, 'delete', base_url('modul/modul_list'));


	}

	function icon()
	{
		
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
