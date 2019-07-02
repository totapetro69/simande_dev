u<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_jalan extends CI_Controller {

    var $API;

    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper("zetro_helper");
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    
   //surat jalan
   public function suratjalan() {
       $data = array();
       $config['per_page'] = '12';
       $data['per_page'] = $config['per_page'];
       $param = array(
           'keyword' => $this->input->get('keyword'),
           'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
           'limit' => $config['per_page'],
           'field' => '*'
       );

       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));

       $config['base_url'] = base_url() . 'surat_jalan/suratjalan?keyword=' . $param['keyword'];
       $config['total_rows'] = $data["list"]->totaldata;
       $config['page_query_string'] = TRUE;
       $config['query_string_segment'] = 'page';
       $config['full_tag_open'] = "<ul class='pagination pagination-sm m-t-none m-b-none'>";
       $config['full_tag_close'] = "</ul>";
       $config['num_tag_open'] = '<li>';
       $config['num_tag_close'] = '</li>';
       $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
       $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
       $config['next_tag_open'] = "<li>";
       $config['next_tagl_close'] = "</li>";
       $config['prev_tag_open'] = "<li>";
       $config['prev_tagl_close'] = "</li>";
       $config['first_tag_open'] = "<li>";
       $config['first_tagl_close'] = "</li>";
       $config['last_tag_open'] = "<li>";
       $config['last_tagl_close'] = "</li>";

       $this->pagination->initialize($config);
       $data['pagination'] = $this->pagination->create_links();

       $this->template->site('purchasing/list_suratjalan', $data);
   }

   public function add_sj() {
        
       $this->load->view('purchasing/add_sj');
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
   }

   function add_sj_simpan() {
       $this->form_validation->set_rules('no_surat_jalan', 'NO SURAT JALAN', 'required|trim');
       $this->form_validation->set_rules('tgl_shipping', 'Tanggal Shipping', 'required|trim');
       $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
       $this->form_validation->set_rules('kd_maindealer', 'Kode Main Dealer', 'required|trim');
       $this->form_validation->set_rules('kd_cabang_dealer', 'Kode Cabang Dealer', 'required|trim');
       $this->form_validation->set_rules('kd_type_motor', 'Kode Type Motor', 'required|trim');
       $this->form_validation->set_rules('kd_warna', 'KD_WARNA', 'required|trim');
       $this->form_validation->set_rules('no_rangka', 'No Rangka', 'required|trim');
       $this->form_validation->set_rules('no_mesin', 'No Mesin', 'required|trim');
       $this->form_validation->set_rules('thn_perakitan', 'Thn Perakitan', 'required|trim');
       $this->form_validation->set_rules('no_ref', 'No Ref', 'required|trim');
       $this->form_validation->set_rules('expedisi', 'Expedisi', 'required|trim');
       $this->form_validation->set_rules('no_pol_truk', 'No Pol', 'required|trim');
       $this->form_validation->set_rules('no_faktur', 'No Faktur', 'required|trim');


       if ($this->form_validation->run() === FALSE) {
           $data = array(
               'status' => false,
               'message' => validation_errors()
           );

           $this->output->set_output(json_encode($data));
       } else {
           $param = array(
               'no_surat_jalan' => $this->input->post("no_surat_jalan"),
               'tgl_shipping' => $this->input->post("tgl_shipping"),
               'kd_dealer' => $this->input->post("kd_dealer"),
               'kd_maindealer'  => $this->input->post("kd_maindealer"),
               'kd_cabang_dealer' => $this->input->post("kd_cabang_dealer"),
               'kd_type_motor'   => $this->input->post("kd_type_motor"),
               'kd_warna'  => $this->input->post("kd_warna"),
               'no_rangka'   => $this->input->post("no_rangka"),
               'no_mesin'   => $this->input->post("no_mesin"),
               'thn_perakitan'   => $this->input->post("thn_perakitan"),
               'no_ref'   => $this->input->post("no_ref"),
               'expedisi'   => $this->input->post("expedisi"),
               'no_pol_truk'   => $this->input->post("no_pol_truk"),
               'no_faktur'   => $this->input->post("no_faktur"),

               'created_by' => $this->session->userdata("userid")
           );
           $hasil = $this->curl->simple_post(API_URL . "/api/purchasing/suratjalan", $param, array(CURLOPT_BUFFERSIZE => 10));
           /* var_dump($hasil);
             exit(); */
           $this->data_output($hasil, 'post');
       }
   }

   public function edit_sj($no_surat_jalan) {
       $param = array(
           'no_surat_jalan' => $no_surat_jalan
       );
       $data = array();
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));
/*
       var_dump($data);
       exit;*/
       $this->load->view('purchasing/edit_sj', $data);
       $html = $this->output->get_output();

       $this->output->set_output(json_encode($html));
   }

   public function update_sj() {
       $hasil = "";
       $param = array(
           'no_surat_jalan' => $this->input->put('no_surat_jalan'),
           'tgl_shipping' => $this->input->put("tgl_shipping"),
           'kd_dealer' => $this->input->put("kd_dealer"),
           'kd_maindealer'  => $this->input->put("kd_maindealer"),
           'kd_cabang_dealer'   => $this->input->put("kd_cabang_dealer"),
           'kd_type_motor'   => $this->input->put("kd_type_motor"),
           'kd_warna'  => $this->input->put("kd_warna"),
           'no_rangka'   => $this->input->put("no_rangka"),
           'no_mesin'   => $this->input->put("no_mesin"),
           'thn_perakitan'   => $this->input->put("thn_perakitan"),
           'no_ref'   => $this->input->put("no_ref"),
           'expedisi'   => $this->input->put("expedisi"),
           'no_pol_truk'   => $this->input->put("no_pol_truk"),
           'no_faktur'   => $this->input->put("no_faktur"),
           'lastmodified_by' => $this->session->userdata("user_id")
       );
       //print_r($param);
       $hasil = $this->curl->simple_put(API_URL ."/api/purchasing/suratjalan", $param, array(CURLOPT_BUFFERSIZE => 10));
       $this->data_output($hasil);
   }


   public function delete_sj($no_surat_jalan) {
       $param = array(
           'no_surat_jalan' => $no_surat_jalan,
           'lastmodified_by' => $this->session->userdata('user_name')
       );

       $data = array();
       $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/purchasing/suratjalan", $param));

       if ($data) {
           $data_status = array(
               'status' => true,
               'message' => 'data berhasil dihapus',
               'location' => base_url('surat_jalan/suratjalan')
           );

           $this->output->set_output(json_encode($data_status));
       } else {
           $data_status = array(
               'status' => false,
               'message' => 'data gagal dihapus',
           );

           $this->output->set_output(json_encode($data_status));
       }

   }

   

   public function suratjalan_typeahead() {
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan"));
// var_dump($data);exit;
    $data_message="";
    if(is_array($data["list"]->message)){

        foreach ($data["list"]->message as $key => $message) {
           $data_message[0][$key] = $message->NO_SURAT_JALAN;
           $data_message[1][$key] = $message->NO_RANGKA;
           $data_message[2][$key] = $message->NO_MESIN;
           $data_message[3][$key] = $message->EXPEDISI;
           
            }
       

        $data_message = array_merge($data_message[0], $data_message[1], $data_message[2], $data_message[3]);
    }
        $result['keyword']=$data_message;
       $this->output->set_output(json_encode($result));
   
}

    public function contoh(){
      $this->load->view('purchasing/contoh');
      $html = $this->output->get_output();

      $this->output->set_output(json_encode($html));

    }
    
    /**
     * [data_output description]
     * @param  [type] $hasil [description]
     * @return [type]        [description]
     */
    function data_output($hasil = NULL, $method = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan"
                    );
                } else {
                    $result = $hasil;
                }
                $this->output->set_output(json_encode($result));
                break;

            default:
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Update berhasil"
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal di simpan',
                        'status' => false
                    );

                    $this->output->set_output(json_encode($result));
                }
                break;
        }
    }
}