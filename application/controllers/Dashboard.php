<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
    {
            parent::__construct();
			//API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
			$this->load->library('form_validation');
        	$this->load->library('session');
        	$this->load->library('curl');
        	$this->load->helper('form');
        	$this->load->helper('url');
            $this->load->helper("zetro_helper");
            $this->load->model("Custom_model");
   
    }
    
	public function index()
	{
		$param = array(
			'where_in' => isDealerAkses(),
			'where_in_field' => "KD_DEALER" 
		);
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true", $param));
        // $this->output->set_output(json_encode($data));       

		$this->template->site('dashboard/index', $data);
	}

	public function monitoring()
	{
		$data = array();
        $param = array(
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/monitoring", $param));
        //var_dump($data["list"]);exit;
        $this->template->site('dashboard/monitoring', $data);
	}
}
