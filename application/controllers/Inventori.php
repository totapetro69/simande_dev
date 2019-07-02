
<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Inventori extends CI_Controller {

    var $API;
    /**
     * [__construct description]
     */
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
        $this->load->model("Custom_model"); 
    }
   /**
    * [pagination description]
    * @param  [type] $config [description]
    * @return [type]         [description]
    */
   public function pagination($config) {

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

        return $config;
   }

  /**
   * [sjkeluar description]
   * @return [type] [description]
   */
   public function sjkeluar() {
     $data = array();
     $config['per_page'] = '12';
     $data['per_page'] = $config['per_page'];
       $param = array(
           'keyword' => $this->input->get('keyword'),
           'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
           'limit' => $config['per_page'],
           'field' => '*'
       );
       $data = array();
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
      $pagination = $this->template->pagination($config);
      $this->pagination->initialize($pagination);
      $data['pagination'] = $this->pagination->create_links();
      $this->template->site('inventori/list_sjkeluar',$data);
   }
   /**
    * [add_sjkeluar description]
    */
   public function add_sjkeluar() {
        
       $this->load->view('inventori/add_sjkeluar');
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
   }
   /**
    * [add_sjkeluar_simpan description]
    */
   function add_sjkeluar_simpan() {
       $this->form_validation->set_rules('no_suratjalan', 'NO SURAT JALAN', 'required|trim');
       $this->form_validation->set_rules('tgl_suratjalan', 'Tanggal Surat Jalan', 'required|trim');
       $this->form_validation->set_rules('kd_maindealer', 'Kode Main Dealer', 'required|trim');
       $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
       $this->form_validation->set_rules('kd_gudang', 'Kode Gudang', 'required|trim');
       $this->form_validation->set_rules('no_reff', 'No Reff', 'required|trim');
       $this->form_validation->set_rules('kd_customer', 'Kode Customer', 'required|trim');
       $this->form_validation->set_rules('alamat_kirim', 'Alamat Kirim', 'required|trim');
       $this->form_validation->set_rules('tgl_kirim', 'Tgl Kirim', 'required|trim');
       $this->form_validation->set_rules('nama_pengirim', 'Nama Pengirim', 'required|trim');
       $this->form_validation->set_rules('no_mobil', 'No Mobil', 'required|trim');
       $this->form_validation->set_rules('nama_sopir', 'Nama Sopir', 'required|trim');
       $this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'required|trim');
       $this->form_validation->set_rules('status_sj', 'Status', 'required|trim');
       $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');

       if ($this->form_validation->run() === FALSE) {
           $data = array(
               'status' => false,
               'message' => validation_errors()
           );

           $this->output->set_output(json_encode($data));
       } else {
           $param = array(
               'no_suratjalan' => $this->input->post("no_suratjalan"),
               'tgl_suratjalan' => $this->input->post("tgl_suratjalan"),
               'kd_maindealer'  => $this->input->post("kd_maindealer"),
               'kd_dealer' => $this->input->post("kd_dealer"),
               'kd_gudang' => $this->input->post("kd_gudang"),
               'no_reff'   => $this->input->post("no_reff"),
               'kd_customer'  => $this->input->post("kd_customer"),
               'alamat_kirim'   => $this->input->post("alamat_kirim"),
               'nama_pengirim'   => $this->input->post("nama_pengirim"),
               'no_mobil'   => $this->input->post("no_mobil"),
               'nama_sopir'   => $this->input->post("nama_sopir"),
               'nama_penerima'   => $this->input->post("nama_penerima"),
               'status_sj'   => $this->input->post("status_sj"),
               'keterangan'   => $this->input->post("keterangan"),

               'created_by' => $this->session->userdata("userid")
           );
           $hasil = $this->curl->simple_post(API_URL . "/api/inventori/sjkeluar", $param, array(CURLOPT_BUFFERSIZE => 10));
           /* var_dump($hasil);
             exit(); */
           $this->data_output($hasil, 'post');
       }
   }

   public function edit_sjkeluar($no_suratjalan) {
       $param = array(
           'no_suratjalan' => $no_suratjalan
       );
       $data = array();
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));
/*
       var_dump($data);
       exit;*/
       $this->load->view('inventori/edit_sjkeluar', $data);
       $html = $this->output->get_output();

       $this->output->set_output(json_encode($html));
   }

   public function update_sjkeluar() {
       $hasil = "";
       $param = array(
            'no_suratjalan' => $this->input->put("no_suratjalan"),
            'tgl_suratjalan' => $this->input->put("tgl_suratjalan"),
            'kd_maindealer'  => $this->input->put("kd_maindealer"),
            'kd_dealer' => $this->input->put("kd_dealer"),
            'kd_gudang' => $this->input->put("kd_gudang"),
            'no_reff'   => $this->input->put("no_reff"),
            'kd_customer'  => $this->input->put("kd_customer"),
            'alamat_kirim'   => $this->input->put("alamat_kirim"),
            'nama_pengirim'   => $this->input->put("nama_pengirim"),
            'no_mobil'   => $this->input->put("no_mobil"),
            'nama_sopir'   => $this->input->put("nama_sopir"),
            'nama_penerima'   => $this->input->put("nama_penerima"),
            'tgl_terima'   => $this->input->put("tgl_terima"),
            'status_sj'   => $this->input->put("status_sj"),
            'keterangan'   => $this->input->put("keterangan"),
           'lastmodified_by' => $this->session->userdata("user_id")
       );
       //print_r($param);
       $hasil = $this->curl->simple_put(API_URL ."/api/inventori/sjkeluar", $param, array(CURLOPT_BUFFERSIZE => 10));
       $this->data_output($hasil);
   }


   public function delete_sjkeluar($no_suratjalan) {
       $param = array(
           'no_suratjalan' => $no_suratjalan,
           'lastmodified_by' => $this->session->userdata('user_name')
       );

       $data = array();
       $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/sjkeluar", $param));

       if ($data) {
           $data_status = array(
               'status' => true,
               'message' => 'data berhasil dihapus',
               'location' => base_url('inventori/sjkeluar')
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

   public function du() {
        
       $this->load->view('inventori/du');
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
   }
   public function co() {
        
       $this->load->view('inventori/co');
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
   }

   

  public function sjkeluar_typeahead() {
    $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar"));
    // var_dump($data);exit;
    $data_message="";
    if(is_array($data["list"]->message)){

        foreach ($data["list"]->message as $key => $message) {
           $data_message[0][$key] = $message->NO_SURATJALAN;
           $data_message[1][$key] = $message->NAMA_PENGIRIM;
           $data_message[2][$key] = $message->NO_MOBIL;
           $data_message[3][$key] = $message->NAMA_SOPIR;
           $data_message[4][$key] = $message->NAMA_PENERIMA;
           
            }
       

        $data_message = array_merge($data_message[0], $data_message[1], $data_message[2], $data_message[3], $data_message[4]);
    }
        $result['keyword']=$data_message;
       $this->output->set_output(json_encode($result));
   
  }

  //surat jalan keluar Detail
   public function sjkeluar_detail() {
       $data = array();
       $config['per_page'] = '12';
       $data['per_page'] = $config['per_page'];
       $param = array(
           'keyword' => $this->input->get('keyword'),
           'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
           'limit' => $config['per_page'],
           'field' => '*'
       );
       $data = array();
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar_detail", $param));
       
      $string = link_pagination();
      $config = array(
          'per_page' => $param['limit'],
          'base_url' => $string[0],
          'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
      );

    $pagination = $this->template->pagination($config);

    $this->pagination->initialize($pagination);
    $data['pagination'] = $this->pagination->create_links();
    $this->template->site('inventori/list_sjkeluar_detail',$data);
   
   }

   public function add_sjkeluar_detail() {
        
       $this->load->view('inventori/add_sjkeluar_detail');
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
   }

   function add_sjkeluar_detail_simpan() {
       $this->form_validation->set_rules('id_suratjalan', 'Id SURAT JALAN', 'required|trim');
       $this->form_validation->set_rules('kd_typemotor', 'Kode Tipe Motor', 'required|trim');
       $this->form_validation->set_rules('kd_warna', 'Kode Warna', 'required|trim');
       $this->form_validation->set_rules('no_mesin', 'No Mesin', 'required|trim');
       $this->form_validation->set_rules('no_rangka', 'No Rangka', 'required|trim');
       $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
       $this->form_validation->set_rules('ket_unit', 'Ket Unit', 'required|trim');
      

       if ($this->form_validation->run() === FALSE) {
           $data = array(
               'status' => false,
               'message' => validation_errors()
           );

           $this->output->set_output(json_encode($data));
       } else {
           $param = array(
               'id_suratjalan' => $this->input->post("id_suratjalan"),
               'kd_typemotor' => $this->input->post("kd_typemotor"),
               'kd_warna'  => $this->input->post("kd_warna"),
               'no_mesin' => $this->input->post("no_mesin"),
               'no_rangka' => $this->input->post("no_rangka"),
               'jumlah'   => $this->input->post("jumlah"),
               'ket_unit'  => $this->input->post("ket_unit"),

               'created_by' => $this->session->userdata("userid")
           );
           $hasil = $this->curl->simple_post(API_URL . "/api/inventori/sjkeluar_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
           /* var_dump($hasil);
             exit(); */
           $this->data_output($hasil, 'post');
       }
   }

   public function edit_sjkeluar_detail($id_suratjalan) {
       $param = array(
           'id_suratjalan' => $id_suratjalan
       );
       $data = array();
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar_detail", $param));
/*
       var_dump($data);
       exit;*/
       $this->load->view('inventori/edit_sjkeluar_detail', $data);
       $html = $this->output->get_output();

       $this->output->set_output(json_encode($html));
   }

   public function update_sjkeluar_detail() {
       $hasil = "";
       $param = array(
            'id_suratjalan' => $this->input->put("id_suratjalan"),
            'kd_typemotor' => $this->input->put("kd_typemotor"),
            'kd_warna'  => $this->input->put("kd_warna"),
            'no_mesin' => $this->input->put("no_mesin"),
            'no_rangka' => $this->input->put("no_rangka"),
            'jumlah'   => $this->input->put("jumlah"),
            'ket_unit'  => $this->input->put("ket_unit"),
           'lastmodified_by' => $this->session->userdata("user_id")
       );
       //print_r($param);
       $hasil = $this->curl->simple_put(API_URL ."/api/inventori/sjkeluar_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
       $this->data_output($hasil);
   }


   public function delete_sjkeluar_detail($id_suratjalan) {
       $param = array(
           'id_suratjalan' => $id_suratjalan,
           'lastmodified_by' => $this->session->userdata('user_name')
       );

       $data = array();
       $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/sjkeluar_detail", $param));

       if ($data) {
           $data_status = array(
               'status' => true,
               'message' => 'data berhasil dihapus',
               'location' => base_url('inventori/sjkeluar_detail')
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

   

    public function sjkeluar_detail_typeahead() {
      $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar_detail"));
       $data_message="";
        if(is_array($data["list"]->message)){

            foreach ($data["list"]->message as $key => $message) {
               $data_message[0][$key] = $message->ID_SURATJALAN;
               $data_message[1][$key] = $message->NO_MESIN;
               $data_message[2][$key] = $message->NO_RANGKA;
               
                }
           

            $data_message = array_merge($data_message[0], $data_message[1], $data_message[2]);
        }
            $result['keyword']=$data_message;
           $this->output->set_output(json_encode($result));
       
    }
  
    function mutasiunit(){
      $param=array(
        'kd_dealer' => $this->sessio->userdata("kd_dealer")
      );
      $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
      $data["gudang"] = json_decode($this->curl->simple_get(API_URL."/api/master/gudang",$param));

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
                        'message' => 'Data berhasil dihapus',
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
  
  /**
     * [ksu description]
     * @return [type] [description]
     */
    public function ksu() {
      $data = array();
      $param = array(
          'keyword' => $this->input->get('keyword'),
          'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
          'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
          'limit' => 15,
          'orderby' => 'MASTER_KSU.ID desc'
      );

      $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param));       
      $string = link_pagination();
      $config = array(
          'per_page' => $param['limit'],
          'base_url' => $string[0],
          'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
      );
      $pagination = $this->pagination($config);

      $this->pagination->initialize($pagination);
      $data['pagination'] = $this->pagination->create_links();


      $this->template->site('inventori/ksu', $data);
    }

    /**
     * [add_ksu description]
     */
    public function add_ksu() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu"));
        $this->load->view('form_tambah/add_ksu');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    /**
     * [add_ksu_simpan description]
     */
    public function add_ksu_simpan() {
        $this->form_validation->set_rules('kd_ksu', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_ksu', 'Nama Jenis KSU', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_ksu' => $this->input->post("kd_ksu"),
                'nama_ksu' => $this->input->post("nama_ksu"),
        'jumlah' => $this->input->post("jumlah"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/inventori/ksu", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('inventori/ksu'));
        }
    }

    public function edit_ksu($kd_ksu, $row_status) {
        $param = array(
            'kd_ksu' => $kd_ksu,
      'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param));

        $this->load->view('form_edit/edit_ksu', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_ksu($id) {
        $this->form_validation->set_rules('kd_ksu', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_ksu', 'Nama KSU', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_ksu' => html_escape($this->input->post("kd_ksu")),
                'nama_ksu' => html_escape($this->input->post("nama_ksu")),
        'jumlah' => html_escape($this->input->post("jumlah")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/ksu", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_ksu($kd_ksu) {
        $param = array(
            'kd_ksu' => $kd_ksu,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/ksu", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('inventori/ksu')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    public function ksu_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_KSU;
            $data_message[1][$key] = $message->NAMA_KSU;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    /**
     * [aksesoris description]
     * @return [type] [description]
     */
    public function barang() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'MASTER_BARANG.*',
            'orderby' => 'MASTER_BARANG.ID desc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('inventori/barang', $data);
    }

    public function add_barang() {
      $this->auth->validate_authen('inventori/barang');
      $data=array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));
        $this->load->view('form_tambah/add_barang', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_barang_simpan() {
        $this->form_validation->set_rules('nama_barang', 'Nama', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_barang' => $this->getKDBarang($this->input->post("kategori")),
                'nama_barang' => $this->input->post("nama_barang"),
                'kategori' => $this->input->post("kategori"),
                'default_qty' => null,
                'masuk_sj' => null,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            //var_dump($param);exit;

            $hasil = $this->curl->simple_post(API_URL . "/api/inventori/barang", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('inventori/barang'));
        }
    }

    public function getKDBarang($kategori) {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kategori='" . $kategori . "'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));
        $number = str_pad(($data->totaldata) + 1, 4, '0', STR_PAD_LEFT);
        return strtoupper(substr($kategori, 0, 3)) . $number;
    }

    public function edit_barang($id, $row_status) {
      $this->auth->validate_authen('inventori/barang');
        $param = array(
            'custom' => "id = '" . $id . "'",
            'row_status' => $row_status
        );

        $data = array();
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));

        $this->load->view('form_edit/edit_barang', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_barang($id) {
        $this->form_validation->set_rules('kd_barang', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_barang' => $this->input->post("kd_barang"),
                'nama_barang' => $this->input->post("nama_barang"),
                'kategori' => $this->input->post("kategori"),
                'default_qty' => null,
                'masuk_sj' => null,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );

            //var_dump($param);exit;
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/barang", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_barang($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/barang", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('inventori/barang')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    public function barang_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_BARANG;
            $data_message[1][$key] = $message->NAMA_BARANG;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    public function barang_setup() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable'  => array(
                    array("MASTER_BARANG as MB","MB.KD_BARANG=SETUP_BARANG.KD_BARANG","LEFT"),
                    array("MASTER_DEALER as MD","MD.KD_DEALER=SETUP_BARANG.KD_DEALER","LEFT")            ),
            'field' => 'SETUP_BARANG.*, MB.NAMA_BARANG, MD.NAMA_DEALER',
            'orderby' => 'SETUP_BARANG.ID desc',
            "custom" => "SETUP_BARANG.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/setup_barang", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('inventori/barang_setup', $data);
    }

    public function add_barang_setup() {
      $this->auth->validate_authen('inventori/barang_setup');
      $data=array();
      $param = array(
            'custom' => "kd_dealer = '" .  $this->session->userdata('kd_dealer') . "'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["barang"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));
        $this->load->view('form_tambah/add_barang_setup', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_barang_setup_simpan() {
        $this->form_validation->set_rules('kd_barang', 'Kode', 'required|trim|max_length[10]');
        $this->form_validation->set_rules('qty_default', 'Qty Default', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_barang' => $this->input->post("kd_barang"),
                'qty_default' => $this->input->post("qty_default"),
                'doc_referer' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/inventori/setup_barang", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('inventori/barang_setup'));
        }
    }

    public function edit_barang_setup($id, $row_status) {
      $this->auth->validate_authen('inventori/barang_setup');
        $param = array(
            'custom' => "SETUP_BARANG.ID = '" . $id . "'",
            'row_status' => $row_status
        );
        $paramD = array(
            'custom' => "MASTER_DEALER.KD_DEALER = '" .  $this->session->userdata('kd_dealer') . "'"
        );
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true/true",$paramD));
        $data["barang"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/setup_barang", $param));
        //var_dump($data["list"]);exit;

        $this->load->view('form_edit/edit_barang_setup', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_barang_setup($id) {
        $this->form_validation->set_rules('kd_barang', 'Kode', 'required|trim');
        $this->form_validation->set_rules('qty_default', 'Qty Default', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_barang' => $this->input->post("kd_barang"),
                'qty_default' => $this->input->post("qty_default"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );

            //var_dump($param);exit;
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/setup_barang", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_barang_setup($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/setup_barang", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('inventori/barang_setup')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    public function barang_setup_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/setup_barang"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_BARANG;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    /**
     * [apparel description]
     * @return [type] [description]
     */
    public function apparel() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'MASTER_APPAREL.*',
            'orderby' => 'MASTER_APPAREL.ID desc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/apparel", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('inventori/apparel', $data);
    }

    public function add_apparel() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/apparel"));
        $this->load->view('form_tambah/add_apparel');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_apparel_simpan() {
        $this->form_validation->set_rules('kd_apparel', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_apparel', 'Nama', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_apparel' => $this->input->post("kd_apparel"),
                'nama_apparel' => $this->input->post("nama_apparel"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/inventori/apparel", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('inventori/apparel'));
        }
    }

    public function edit_apparel($kd_apparel, $row_status) {
        $param = array(
            'kd_apparel' => $kd_apparel,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/apparel", $param));

        $this->load->view('form_edit/edit_apparel', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_apparel($id) {
        $this->form_validation->set_rules('kd_apparel', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_apparel', 'Nama', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_apparel' => $this->input->post("kd_apparel"),
                'nama_apparel' => $this->input->post("nama_apparel"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/apparel", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_apparel($kd_apparel) {
        $param = array(
            'kd_apparel' => $kd_apparel,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/apparel", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('inventori/apparel')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    public function apparel_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/apparel"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_APPAREL;
            $data_message[1][$key] = $message->NAMA_APPAREL;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }
  
  /**
     * lst surat jalan masuk yng sudah di download dari webservice
     * @return [type] [description]
     */
    public function history_harga() {
        $data = array();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];
        //parameter 
        $param = array(
            'kd_wilayah' =>($this->input->post("kd_wilayah"))?$this->input->post("kd_wilayah"):$this->session->userdata("kd_wilayah"),
            'keyword'   => ($this->input->get('keyword')=="all")?"":$this->input->get('keyword'),
            'offset'   => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'custom'    => "STATUS_HARGA=1",
            'orderby'   => "KD_WILAYAH,KD_ITEM"
        );

        $data["listh"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/historyhargamotor", $param));
        $datas = array();
        if($data["listh"]){
          if (is_array($data["listh"]->message)) {
              $i = 0;
              foreach ($data["listh"]->message as $key => $value) {
                  $paramd = array(
                      'kd_wilayah'=>$value->KD_WILAYAH,
                      'kd_item' => $value->KD_ITEM,
                      // 'custom'  => 'STATUS_HARGA=2',
                      'orderby' => "STATUS_HARGA ASC,CREATED_TIME DESC"
                  );
                  $listd = json_decode($this->curl->simple_get(API_URL . "/api/laporan/historyhargamotor", $paramd), true);
                  $datas[$i][$value->KD_ITEM] = $listd["message"];
                  $i++;
              }
          }
        }
        /*$param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page'],
            'custom'    => "STATUS_HARGA=1",
        );*/

        $data["listd"] = $datas;       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["listh"]) ? $data["listh"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
    
        $this->template->site('inventori/history_harga', $data);
    }
  
   public function detail_history_harga($id) {
        $param = array(
            'id' => $id
        );

        $data = array();
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/laporan/historyhargamotor",$param));

        $this->load->view('inventori/detail_history_harga', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
   }
  
  /**
     * stock_unit
     * @return [type] [description]
     */
    
   public function stock_unit3($debug=null) {
      $data = array();$totaldata=0;$data['totaldata']='';$datax=array();
      $data['html']='';
      $html=""; $dealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
      $newParam=array();
      $param = array (
          'keyword'    => $this->input->get('keyword'), 
          'row_status' => $this->input->get('row_status'),
          'offset'     => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
          'limit'      => 15,
          'orderby' =>"TRANS_STOCKMOTOR.KD_ITEM",
          'custom' => "TRANS_STOCKMOTOR.KD_DEALER IN('".$dealer."')"
      );
      $paramdetail=array(
        'jointable'  => array(
            array("TRANS_SJMASUK AS TM","TM.NO_MESIN=TRANS_STOCKMOTOR.NO_MESIN","LEFT"),
            array("TRANS_TERIMASJMOTOR AS TJ","TJ.NO_MESIN=TRANS_STOCKMOTOR.NO_MESIN AND TJ.NO_SJMASUK=TM.NO_SJMASUK AND TJ.ROW_STATUS>=0","LEFT","NO_MESIN,NO_SJMASUK,KD_GUDANG,TGL_TRANS,ROW_STATUS")    
          ),
          'field' => "TRANS_STOCKMOTOR.KD_MAINDEALER,TRANS_STOCKMOTOR.KD_ITEM,TRANS_STOCKMOTOR.NAMA_ITEM,TRANS_STOCKMOTOR.NO_MESIN,
            TRANS_STOCKMOTOR.NO_RANGKA,STOCK_AWAL,STOCK_AKHIR,SUM(BELI) BELI,SUM(NRFS)NRFS,SUM(RETUR)RETUR,SUM(BOOK)BOOK,SUM(BOOK_FULL)BOOK_FULL,SUM(DEL_PARSIAL)DEL_PAR,SUM(DEL_COMP)SALES,SUM(RETUR_MD)RETUR_MD, TM.TGL_SJMASUK,TJ.TGL_TRANS,TRANS_STOCKMOTOR.ROW_STATUS,TJ.KD_GUDANG,TRANS_STOCKMOTOR.KD_DEALER",
          'groupby'=> TRUE/*,
          'having'  => "((SUM(BELI)+SUM(RETUR)) - SUM(DEL_PARSIAL)-SUM(DEL_COMP)-SUM(RETUR_MD)) > 0 "*/
         );
      if($this->input->get("onlystock")){
      //$paramdetail["having"] = "((SUM(BELI)+SUM(RETUR)) - SUM(DEL_PARSIAL)-SUM(DEL_COMP)-SUM(RETUR_MD)) >0";
      }
      if($this->input->get("kd_dealer")){
        $param["kd_dealer"] = $this->input->get('kd_dealer');
      }else{
        $param["kd_dealer"] = $this->session->userdata('kd_dealer');
      }
      $grouping ='0';
      $grouping = ($this->input->get("pilih"))?$this->input->get("pilih"):"0";
      $nom=0;
      switch ($grouping) {
         case '1':
        # Segment motor
        $html="";
        $params=array(
          'field' =>"ISNULL(SEMBILAN_SEGMEN,'OTHERS') AS SEMBILAN_SEGMEN, SUM(STOCK_AKHIR)STOCK_AKHIR",
          'groupby_text' =>'SEMBILAN_SEGMEN',
          'orderby' =>"SEMBILAN_SEGMEN",
          'custom' => "TRANS_STOCKMOTOR.KD_DEALER IN('".$dealer."')"
        );
        if($this->input->get("kd_gudang")!="0"){
          $params["custom"] .= " AND KD_GUDANG='".$this->input->get("kd_gudang")."' AND STOCK_AKHIR > 0";
          $params["groupby_text"]='SEMBILAN_SEGMEN,KD_GUDANG';
        }else{
          $params["custom"] .= " AND STOCK_AKHIR >0";
        }
        unset($param["offset"]);unset($param["limit"]);
        $newParam = array_merge($param,$params);
        $list=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor",$newParam));
        
        if($list){
          if($list->totaldata>0){
            foreach ($list->message as $key => $value) {
              $nom++;$n=0;$datax=array();
              $param["custom"] =($value->SEMBILAN_SEGMEN=='OTHERS')? "SEMBILAN_SEGMEN IS NULL AND STOCK_AKHIR >0":" SEMBILAN_SEGMEN='".$value->SEMBILAN_SEGMEN."' AND STOCK_AKHIR >0";
              if($this->input->get("kd_gudang")!="0"){
                $param["custom"] .= "AND TJ.KD_GUDANG ='".$this->input->get("kd_gudang")."'";
              }
              //print_r($param);exit();
              $datax=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor", array_merge($param,$paramdetail)));
              //print_r($datax);exit();
              $html .="<tr id='l_".$nom."' class='info'><td class='text-center'>$nom <span  class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
              $html .="<td colspan='2'>".strtoupper($value->SEMBILAN_SEGMEN)."</td>";
              $html .="<td class='text-center'>".number_format($value->STOCK_AKHIR,0)."</td>";
              $html .="<td colspan='6'>&nbsp;</td>";
              $html .="</tr>";
              if($datax){
                  if($datax->totaldata>0){
                      foreach ($datax->message as $key => $value) {
                        $status="";$jumlah=0;$title="";
                        if($value->BOOK_FULL >0 || $value->BOOK >0){
                          $status="BOOKED";
                          $jumlah=($value->BOOK_FULL>0)?$value->BOOK_FULL:$value->BOOK;
                          $title = "Sudah di booking untuk di anter ke customer";
                        }else if($value->RETUR >0 || $value->RETUR_MD>0 || $value->NRFS){
                          $status="NRFS";
                          $jumlah=($value->RETUR >0)?$value->RETUR:$value->RETUR_MD;
                          $title = "Not Ready For Sales - Unit tidak layak jual";
                        }else{
                          $status="RFS";
                          $jumlah = $value->STOCK_AKHIR;
                          $title = "Ready For Sales";
                        }
                         $n++;
                         $html .="<tr class='l_".$nom." hidden'><td class='text-right'>$n</td>";
                         $html .="<td class='text-center table-nowarp'>".$value->KD_ITEM."</td>";
                         $html .="<td class='text-left table-nowarp'>".$value->NAMA_ITEM."</td>";
                         $html .="<td class='text-center'>".$jumlah."</td>";
                         $html .="<td class='text-center'>".$value->NO_RANGKA."</td>";
                         $html .="<td class='text-center'>".$value->NO_MESIN."</td>";
                         $html .="<td class='text-center'>".$value->KD_GUDANG."</td>";
                         $html .="<td class='text-center'><abbr title='".$title."'>".$status."</abbr></td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_SJMASUK)."</td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>";
                         $html .="</tr>";
                      }
                      $totaldata +=$datax->totaldata;
                  }
              }
              //$html .=$datax->param;
            }
          }
        }
        break;
      case '2':
      case '4':
        # Tipe Motor
        $html="";
        $params=array(
          'field' =>"ISNULL(KD_GROUPMOTOR,'OTHERS') AS KD_GROUPMOTOR,ISNULL(NAMA_GROUPMOTOR,'OTHERS')NAMA_GROUPMOTOR,SUM(STOCK_AKHIR) STOCK_AKHIR",
          'groupby_text' =>'KD_GROUPMOTOR,NAMA_GROUPMOTOR',
          'orderby' =>"KD_GROUPMOTOR",
          'custom' => "TRANS_STOCKMOTOR.KD_DEALER IN('".$dealer."')"
        );
        if($this->input->get("kd_gudang")!="0"){
          $params["custom"] .= " AND KD_GUDANG='".$this->input->get("kd_gudang")."' AND STOCK_AKHIR > 0";
          $params["groupby_text"]='KD_GROUPMOTOR,NAMA_GROUPMOTOR,KD_GUDANG';
        }else{
          $params["custom"] .= " AND STOCK_AKHIR >0";
        }
        unset($param["offset"]);unset($param["limit"]);
        $newParam = array_merge($param,$params);
        $list=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor",$newParam));
        //print_r($newParam);exit();
        if($list){
          if($list->totaldata>0){
            foreach ($list->message as $key => $value) {
              $nom++;$n=0;
              $paramx=array();
              $groupe=($value->KD_GROUPMOTOR=='OTHERS')?"AND KD_GROUPMOTOR IS NULL":"AND KD_GROUPMOTOR='".$value->KD_GROUPMOTOR."'";
              if($this->input->get("kd_gudang")!="0"){
                $param["custom"] = "TJ.KD_GUDANG ='".$this->input->get("kd_gudang")."' AND STOCK_AKHIR >0 ".$groupe;
              }else{
                $param["custom"] ="STOCK_AKHIR >0 ".$groupe;
              }
              $datax=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor",array_merge($param,$paramdetail)));
              //print_r(array_merge($param,$paramx));var_dump($dataxs);exit();
              $html .="<tr id='l_".$nom."' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
              $html .="<td colspan='0'>".strtoupper($value->KD_GROUPMOTOR)."</td>";
              $html .="<td>".$value->NAMA_GROUPMOTOR."</td>";
              $html .="<td class='text-center'>".number_format($value->STOCK_AKHIR,0)."</td>";
              $html .="<td colspan='6'>&nbsp;</td>";
              $html .="</tr>";
              if($datax){
                  if($datax->totaldata>0){
                      foreach ($datax->message as $key => $value) {
                        $status="";$jumlah=0;$title="";
                        if($value->BOOK_FULL >0 || $value->BOOK >0){
                          $status="BOOKED";
                          $jumlah=($value->BOOK_FULL>0)?$value->BOOK_FULL:$value->BOOK;
                          $title = "Sudah di booking untuk di anter ke customer";
                        }else if($value->RETUR >0 || $value->RETUR_MD>0 || $value->NRFS){
                          $status="NRFS";
                          $jumlah=($value->RETUR >0)?$value->RETUR:$value->RETUR_MD;
                          $title = "Not Ready For Sales - Unit tidak layak jual";
                        }else{
                          $status="RFS";
                          $jumlah = $value->STOCK_AKHIR;
                          $title = "Ready For Sales";
                        }
                         $n++;
                         $html .="<tr class='l_".$nom." hidden'><td class='text-right'>$n</td>";
                         $html .="<td class='text-center table-nowarp'>".$value->KD_ITEM."</td>";
                         $html .="<td class='text-left table-nowarp'>".$value->NAMA_ITEM."</td>";
                         $html .="<td class='text-center'>".$jumlah."</td>";
                         $html .="<td class='text-center'>".$value->NO_RANGKA."</td>";
                         $html .="<td class='text-center'>".$value->NO_MESIN."</td>";
                         $html .="<td class='text-center'>".$value->KD_GUDANG."</td>";
                         $html .="<td class='text-center'><abbr title='".$title."'>".$status."</abbr></td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_SJMASUK)."</td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>";
                         $html .="</tr>";
                      }
                      $totaldata +=$datax->totaldata;
                  }
              }
              
            }
          }
        }
        break;
      case '3':
        # Series
        $html="";
        $params=array(
          'field' =>"ISNULL(SERIES,'OTHERS')SERIES,SUM(STOCK_AKHIR)STOCK_AKHIR",
          'groupby_text' =>'SERIES',
          'orderby' => "SERIES",
          'custom' => "STOCK_AKHIR > 0 AND TRANS_STOCKMOTOR.KD_DEALER IN('".$dealer."')",
        );
        if($this->input->get("kd_gudang")!="0"){
          $params["custom"] .= " AND KD_GUDANG='".$this->input->get("kd_gudang")."'";
        }
        unset($param["offset"]);unset($param["limit"]);
        $newParam = array_merge($param,$params);
        $list=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor",$newParam));
        if($list){
          if($list->totaldata>0){
            foreach ($list->message as $key => $value) {
              $nom++;$n=0;
              $param["custom"] =($value->SERIES=='OTHERS')?"SERIES IS NULL AND STOCK_AKHIR >0":"SERIES='".$value->SERIES."' AND STOCK_AKHIR >0";
              if($this->input->get("kd_gudang")!="0"){
                $params["custom"] .= " AND TJ.KD_GUDANG='".$this->input->get("kd_gudang")."'";
              }
              $datax=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor", array_merge($param,$paramdetail)));
              $html .="<tr id='l_".$nom."' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
              $html .="<td colspan='2'>".strtoupper($value->SERIES)."</td>";
              $html .="<td class='text-center'>".number_format($value->STOCK_AKHIR,0)."</td>";
              $html .="<td colspan='6'>&nbsp;</td>";
              $html .="</tr>";
              if($datax){
                  if($datax->totaldata>0){
                      foreach ($datax->message as $key => $value) {
                        $status="";$jumlah=0;$title="";
                        if($value->BOOK_FULL >0 || $value->BOOK >0){
                          $status="BOOKED";
                          $jumlah=($value->BOOK_FULL>0)?$value->BOOK_FULL:$value->BOOK;
                          $title = "Sudah di booking untuk di anter ke customer";
                        }else if($value->RETUR >0 || $value->RETUR_MD>0 || $value->NRFS){
                          $status="NRFS";
                          $jumlah=($value->RETUR >0)?$value->RETUR:$value->RETUR_MD;
                          $title = "Not Ready For Sales - Unit tidak layak jual";
                        }else{
                          $status="RFS";
                          $jumlah = $value->STOCK_AKHIR;
                          $title = "Ready For Sales";
                        }
                         $n++;
                         $html .="<tr class='hidden l_".$nom."'><td class='text-right'>$n</td>";
                         $html .="<td class='text-center table-nowarp'>".$value->KD_ITEM."</td>";
                         $html .="<td class='text-left table-nowarp'>".$value->NAMA_ITEM."</td>";
                         $html .="<td class='text-center'>".$jumlah."</td>";
                         $html .="<td class='text-center'>".$value->NO_RANGKA."</td>";
                         $html .="<td class='text-center'>".$value->NO_MESIN."</td>";
                         $html .="<td class='text-center'>".$value->KD_GUDANG."</td>";
                         $html .="<td class='text-center'><abbr title='".$title."'>".$status."</abbr></td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_SJMASUK)."</td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>";
                         $html .="</tr>";
                      }
                      $totaldata +=$datax->totaldata;
                  }
              }
              
            }
          }
        }
        break;
      case '6':
        # Group Motor
        $html="";
        $params=array(
          'field' =>"ISNULL(STOCK_STATUS,'OTHERS') STOCK_STATUS, SUM(STOCK_AKHIR) STOCK_AKHIR",
          'groupby_text' =>"STOCK_STATUS",
          'orderby' => "STOCK_STATUS",
          'custom' => "STOCK_AKHIR > 0 AND TRANS_STOCKMOTOR.KD_DEALER IN('".$dealer."')"
        );
        if($this->input->get("kd_gudang")!="0"){
          $params["custom"] .= " AND KD_GUDANG='".$this->input->get("kd_gudang")."'";
        }
        unset($param["offset"]);unset($param["limit"]);
        $newParam = array_merge($param,$params);
        $list=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor",$newParam));
        //var_dump($newParam);exit();
        if($list){
          if($list->totaldata>0){
            foreach ($list->message as $key => $value) {
              $nom++;$n=0;
              $param["custom"] .=($value->STOCK_STATUS=='OTHERS')?"AND STOCK_STATUS IS NULL AND STOCK_AKHIR>0":"STOCK_STATUS='".$value->STOCK_STATUS."' AND STOCK_AKHIR>0";
              if($this->input->get("kd_gudang")!="0"){
                $params["custom"] .= " AND TJ.KD_GUDANG='".$this->input->get("kd_gudang")."'";
              }
              $datax=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor", array_merge($param,$paramdetail)));
              $html .="<tr id='l_".$nom."' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
              $html .="<td colspan='2'>".strtoupper($value->STOCK_STATUS)."</td>";
              $html .="<td class='text-center'>".number_format($value->STOCK_AKHIR,0)."</td>";
              $html .="<td colspan='6'>&nbsp;</td>";
              $html .="</tr>";
              if($datax){
                  if($datax->totaldata>0){
                      foreach ($datax->message as $key => $value) {
                        $status="";$jumlah=0;$title="";
                        if($value->BOOK_FULL >0 || $value->BOOK >0){
                          $status="BOOKED";
                          $jumlah=($value->BOOK_FULL>0)?$value->BOOK_FULL:$value->BOOK;
                          $title = "Sudah di booking untuk di anter ke customer";
                        }else if($value->RETUR >0 || $value->RETUR_MD>0 || $value->NRFS){
                          $status="NRFS";
                          $jumlah=($value->RETUR >0)?$value->RETUR:$value->RETUR_MD;
                          $title = "Not Ready For Sales - Unit tidak layak jual";
                        }else{
                          $status="RFS";
                          $jumlah = $value->STOCK_AKHIR;
                          $title = "Ready For Sales";
                        }
                         $n++;
                         $html .="<tr class='hidden l_".$nom."'><td class='text-right'>$n</td>";
                         $html .="<td class='text-center table-nowarp'>".$value->KD_ITEM."</td>";
                         $html .="<td class='text-left table-nowarp'>".$value->NAMA_ITEM."</td>";
                         $html .="<td class='text-center'>".$jumlah."</td>";
                         $html .="<td class='text-center'>".$value->NO_RANGKA."</td>";
                         $html .="<td class='text-center'>".$value->NO_MESIN."</td>";
                         $html .="<td class='text-center'>".$value->KD_GUDANG."</td>";
                         $html .="<td class='text-center'><abbr title='".$title."'>".$status."</abbr></td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_SJMASUK)."</td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>";
                         $html .="</tr>";
                      }
                      $totaldata +=$datax->totaldata;
                  }
              }
              
            }
          }
        }
        break;
      case '5':
        # Kategori Motor
        $html="";
        $params=array(
          'field' =>"ISNULL(CATEGORY_MOTOR,'OTHERS')CATEGORY_MOTOR,SUM(STOCK_AKHIR)STOCK_AKHIR",
          'groupby_text' =>'CATEGORY_MOTOR',
          'orderby' => "CATEGORY_MOTOR",
          'custom' =>"STOCK_AKHIR > 0 AND TRANS_STOCKMOTOR.KD_DEALER IN('".$dealer."')"
        );
        if($this->input->get("kd_gudang")!="0"){
          $params["custom"] .= " AND KD_GUDANG='".$this->input->get("kd_gudang")."'";
        }
        unset($param["offset"]);unset($param["limit"]);
        $newParam = array_merge($param,$params);
        $list=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor",$newParam));
        
        if($list){
          if($list->totaldata>0){
            foreach ($list->message as $key => $value) {
              $nom++;$n=0;
              $param["custom"] .=($value->CATEGORY_MOTOR=='OTHERS')?" AND CATEGORY_MOTOR IS NULL AND STOCK_AKHIR >0":"CATEGORY_MOTOR='".$value->CATEGORY_MOTOR."' AND STOCK_AKHIR >0";
              if($this->input->get("kd_gudang")!="0"){
                $params["custom"] .= " AND TJ.KD_GUDANG='".$this->input->get("kd_gudang")."'";
              }
              $datax=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor", array_merge($param,$paramdetail)));
              $html .="<tr id='l_".$nom."' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
              $html .="<td colspan='2'>".strtoupper($value->CATEGORY_MOTOR)."</td>";
              $html .="<td class='text-center'>".number_format($value->STOCK_AKHIR,0)."</td>";
              $html .="<td colspan='6'>&nbsp;</td>";
              $html .="</tr>";
              //print_r($datax);exit();
              if($datax){
                  if($datax->totaldata>0){
                      foreach ($datax->message as $key => $value) {
                        $status="";$jumlah=0;$title="";
                        if($value->BOOK_FULL >0 || $value->BOOK >0){
                          $status="BOOKED";
                          $jumlah=($value->BOOK_FULL>0)?$value->BOOK_FULL:$value->BOOK;
                          $title = "Sudah di booking untuk di anter ke customer";
                        }else if($value->RETUR >0 || $value->RETUR_MD>0 || $value->NRFS){
                          $status="NRFS";
                          $jumlah=($value->RETUR >0)?$value->RETUR:$value->RETUR_MD;
                          $title = "Not Ready For Sales - Unit tidak layak jual";
                        }else{
                          $status="RFS";
                          $jumlah = $value->STOCK_AKHIR;
                          $title = "Ready For Sales";
                        }
                         $n++;
                         $html .="<tr class='hidden l_".$nom."'><td class='text-right'>$n</td>";
                         $html .="<td class='text-center table-nowarp'>".$value->KD_ITEM."</td>";
                         $html .="<td class='text-left table-nowarp'>".$value->NAMA_ITEM."</td>";
                         $html .="<td class='text-center'>".$jumlah."</td>";
                         $html .="<td class='text-center'>".$value->NO_RANGKA."</td>";
                         $html .="<td class='text-center'>".$value->NO_MESIN."</td>";
                         $html .="<td class='text-center'>".$value->KD_GUDANG."</td>";
                         $html .="<td class='text-center'><abbr title='".$title."'>".$status."</abbr></td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_SJMASUK)."</td>";
                         $html .="<td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>";
                         $html .="</tr>";
                      }
                      $totaldata +=$datax->totaldata;
                  }
              }
              
            }
          }
        }
        break;
      default:
        $html="";
        $n=$this->input->get('page');

        if($this->input->get("kd_gudang")){
          $param["custom"] .= " AND TRANS_STOCKMOTOR.KD_GUDANG='".$this->input->get("kd_gudang")."' AND STOCK_AKHIR > 0";
        }else{
          $param["custom"] .= " AND STOCK_AKHIR > 0";
        }
        
        $params=array_merge($param,$paramdetail);
        $datax=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor", $params));
        // print_r($params);
        //var_dump($datax);exit();
        if($datax){
          $kdtm="";$jml=0;
            if($datax->totaldata>0){
                foreach ($datax->message as $key => $value) {
                  $status="";$jumlah=0;$title="";
                  if($value->BOOK_FULL >0 || $value->BOOK >0){
                    $status="BOOKED";
                    $jumlah=($value->BOOK_FULL>0)?$value->BOOK_FULL:$value->BOOK;
                    $title = "Sudah di booking untuk di antar ke customer";
                  }
                  if($value->RETUR >0 || $value->RETUR_MD>0 ){
                    $status=($value->RETUR >0)?"RETUR":"RETUR TO MD";
                    $jumlah=($value->RETUR >0)?$value->RETUR:$value->RETUR_MD;
                    $title = "Not Ready For Sales - Unit tidak layak jual";
                  }
                  if((int)$value->NRFS > 0){
                    $status ="NRFS";
                    $jumlah=$value->NRFS;
                    $title = "Not Ready For Sales";
                  }else{
                    $status="RFS";
                    $jumlah = $value->STOCK_AKHIR;
                    $title = "Ready For Sales";
                  }
                   
                   if($kdtm!=$value->KD_ITEM){
                    $jml =$jumlah;
                    $n++;
                    $html .="<tr class='info'><td class='text-right'>$n</td>";
                     $html .="<td class='text-center table-nowarp'>".$value->KD_ITEM."</td>";
                     $html .="<td class='text-left table-nowarp'>".$value->NAMA_ITEM."</td>";
                     $html .="<td class='text-center table-nowarp'></td>";
                     $html .="<td colspan='6'></td></tr>";
                     $jml=0;
                   }
                    $jml +=$jumlah;
                   
                   $html .="<tr><td class='text-right'></td>";
                   $html .="<td class='text-center table-nowarp'></td>";
                   $html .="<td class='text-left table-nowarp'></td>";
                   $html .="<td class='text-center'>".$jumlah."</td>";
                   $html .="<td class='text-center'>".$value->NO_RANGKA."</td>";
                   $html .="<td class='text-center'>".$value->NO_MESIN."</td>";
                   $html .="<td class='text-center'>".$value->KD_GUDANG."</td>";
                   $html .="<td class='text-center'><abbr title='".$title."'>".$status."</abbr></td>";
                   $html .="<td class='text-center'>".tglFromSql($value->TGL_SJMASUK)."</td>";
                   $html .="<td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>";
                   $html .="</tr>";
                 //}
                   $kdtm=$value->KD_ITEM;

                }
                //$html .=$datax->totaldata;
                $totaldata +=$datax->totaldata;
            }
        }
        break;
      }
      if($debug==true){
       print_r($params);
       var_dump($datax);
       echo $html;
       exit();
      }
      $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer"));
      $data["html"] = $html;
      $string = link_pagination();
         if(isset($param['limit'])){
            $config = array(
                'per_page' => $param['limit'], 
                'base_url'  => $string[0],//base_url().'inventori/stock_unit?keyword='.$param['keyword'].'&row_status='.$param['row_status'],
                'total_rows' => $totaldata
            );
            $pagination = $this->template->pagination($config);

            $this->pagination->initialize($pagination);
         }
      $data["totaldata"]=$totaldata;
      $data['pagination'] = $this->pagination->create_links();
      $data["gudang"] = $this->gudang(false);

      $this->template->site('inventori/stock_unit',$data);
   }

   public function stock_unit($debug=null){
      $param=array(
            'keyword' => $this->input->keyword,
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'custom' => "STOCK_AKHIR >0"
      );
      if($this->input->get("kd_gudang")){
         $param["kd_gudang"] = $this->input->get("kd_gudang");
      }
      $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $param));
      if($debug){
        var_dump($data["list"]);
      }
      $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
      $string = link_pagination();
      if(isset($param['limit'])){
         $config = array(
             'per_page' => $param['limit'], 
             'base_url'  => $string[0],
             'total_rows' => (isset($data["list"]))?$data["list"]->totaldata:"0"
         );
         $pagination = $this->template->pagination($config);

         $this->pagination->initialize($pagination);
      }
      $data["totaldata"] = (isset($data["list"]))?$data["list"]->totaldata:"0";
      $data['pagination'] = $this->pagination->create_links();
      $data["gudang"] = $this->gudang(false);

      $this->template->site('inventori/stock_unit',$data);
   }
   /**
   * [gudang description]
   * @param  boolean $jquery [description]
   * @return [type]          [description]
   */
   public function gudang($jquery=true) {
      $data=array();
      if($this->input->get('kd_dealer')){
         $param['kd_dealer']= $this->input->get('kd_dealer');
      }else{
         $param['kd_dealer']= $this->session->userdata("kd_dealer");
      }
    
    $data = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
    //var_dump($data);exit();
    if($jquery==true){
      echo "<option value='0'>--Pilih Gudang--</option>";
      if ($data) {
          if (is_array($data->message)) {
              foreach ($data->message as $key => $value) {
                $select=($value->DEFAULTS==1)?"selected":"";
                  echo "<option value='" . $value->KD_GUDANG . "' ".$select.">" . $value->NAMA_GUDANG . "</option>";
              }
          }
      }
    }else{
      return $data;
    }
    
  }
  public function gudang_part($jquery=true) {
    $data=array();
     if($this->input->get('kd_dealer')){
        $param['kd_dealer']= $this->input->get('kd_dealer');
     }else{
        $param['kd_dealer']= $this->session->userdata("kd_dealer");
     }
    
    $data= json_decode($this->curl->simple_get(API_URL."/api/marketing/lokasi_rak_bin",$param));
    //var_dump($data);exit();
    if($jquery==true){
      echo "<option value='0'>--Pilih Gudang--</option>";
      if ($data) {
          if (is_array($data->message)) {
              foreach ($data->message as $key => $value) {
                $select=($value->RAK_DEFAULT==1)?"selected":"";
                  echo "<option value='" . $value->KD_LOKASI.":".$value->KD_GUDANG . "' ".$select.">".strtoupper($value->KD_LOKASI)."[".$value->KD_GUDANG ."]</option>";
              }
          }
      }
    }else{
      return $data;
    }
    
  }
  /**
   * [stockoverview description]
   * @return [type] [description]
   */
  public function stockoverview(){
    $data=array();$arrData=null;$data["receive"]=null;$data["sales"]=null;
    if($this->input->get("keyword")){
      // data receiving unit motor
      $param=array(
        'kd_maindealer' => $this->session->userdata("kd_maindealer"),
        'kd_dealer'     => ($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
        'custom'        => $this->input->get('keyword'),
        'no_rangka'     => $this->input->get("keyword")
      );
      $data["receive"] = json_decode($this->curl->simple_get(API_URL."/api/inventori/mutasi_history", $param));
       
    }
    
    $param=array(
      'custom'       => "(TRANS_SJMASUK.NO_MESIN ='".$this->input->get('keyword')."' OR TRANS_SJMASUK.NO_RANGKA ='".$this->input->get('keyword')."')",
      'jointable' => array(array('MASTER_P_TYPEMOTOR MT','MT.KD_TYPEMOTOR=TRANS_SJMASUK.KD_TYPEMOTOR AND MT.KD_WARNA=TRANS_SJMASUK.KD_WARNA','LEFT')),
      'field'   =>"MT.KD_ITEM,MT.NAMA_ITEM,TRANS_SJMASUK.THN_PERAKITAN,TRANS_SJMASUK.NO_MESIN,TRANS_SJMASUK.NO_RANGKA"
    );
    $data["motor"]=json_decode($this->curl->simple_get(API_URL."/api/purchasing/suratjalan", $param));
    $data["list"]=$arrData;
    //var_dump($data);exit();
    $paramcustomer=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jointable' => array(array("MASTER_WILAYAH MW","MW.KD_PROPINSI=MASTER_DEALER.KD_PROPINSI","LEFT")),
            'field' => 'MASTER_DEALER.*,MW.KD_WILAYAH'
        );
    if($this->session->userdata("nama_group")=='Root'){
      unset($paramcustomer["kd_dealer"]);
    }
    $data["dealer"]      =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",$paramcustomer));
    $this->template->site('inventori/stock_overview', $data);
  }
   /**
   * [listpenerimaan_sp description]
   * @return [type] [description]
   */
   public function listpenerimaan_sp($debug=null){
      $data=array();$totaldata=0;$config=array();$datax=array();
      $tgl_awal=($this->input->get('dari_tanggal'))?($this->input->get('dari_tanggal')):date("d/m/Y" ,strtotime('first day of this month'));
      $tgl_akir=($this->input->get('sampai_tanggal'))?($this->input->get('sampai_tanggal')):date("d/m/Y");
      $result=array();
      $result["no_data"]= 1;
      $paramh=array(
         'offset'     => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
         'limit'      => 15,
         'keyword' => $this->input->get('keyword'),
         'kd_dealer'=> ($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
         'custom' => "CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".TglToSql($tgl_awal)."' AND '".TglToSql($tgl_akir)."'",
         'orderby' => "ID DESC"
      );
      $hasil=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part_terima",$paramh));
      //var_dump($hasil);exit();
      if($hasil){
         if($hasil->totaldata >0){
            $nn=0;
            $result["header"]=$hasil->totaldata; 
            foreach ($hasil->message as $key => $value) {
               $param=array(
                  'jointable'=> array(
                     array("TRANS_PART_SJMASUK AS SJM","SJM.NO_SJ='".$value->NO_SURATJALAN."'","LEFT","NO_SJ,TGL_SJ,NO_PO,JATUH_TEMPO")
                  ),
                  'field' => "\"(ROW_NUMBER() OVER(PARTITION BY NO_TRANS ORDER BY NO_TRANS,TRANS_PART_TERIMADETAIL.ID)min1)\" AS XN,TRANS_PART_TERIMADETAIL.ID AS TDID,PART_NUMBER,JUMLAH,JUMLAH_RFS,JUMLAH_NRFS,STATUS_PART,HARGA_BELI,PPN,NETPRICE,DISKON,KD_RAKBIN,KD_RAKBIN_NRFS,KD_TRANS,SJM.TGL_SJ,SJM.JATUH_TEMPO",
                  'orderby' => "ID",
                  'no_trans'=>$value->NO_TRANS
               );
                  
               $datax=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part_terimadetail",$param));
               $x=0;
               if($debug){
                  /*echo '<pre>jumlah data: '.count($hasil)."<br>";
                  print_r($param);
                  print_r($datax->param);
                  echo '</pre>';
                  exit();*/
               }
               if($datax){
                  if($datax->totaldata >0){
                     $xn=0;
                     $result["no_data"]=false;
                     foreach ($datax->message as $key => $val) {
                        $xn=($nn);$x++;
                        $n=($val->XN);
                        
                        $result["list"][($xn)]["no_trans"] = $value->NO_TRANS;
                        $result["list"][($xn)]["kd_maindealer"]= $value->KD_MAINDEALER;
                        $result["list"][($xn)]["kd_dealer"]    = $value->KD_DEALER;
                        $result["list"][($xn)]["kd_dealerahm"]    = $this->__DealerAHMtoTM($value->KD_DEALER,true);
                        $result["list"][($xn)]["no_po"]    = $value->NO_PO;
                        $result["list"][($xn)]["no_sj"]    = $value->NO_SURATJALAN;
                        $result["list"][($xn)]["jth_tmp"]  = $val->JATUH_TEMPO;
                        $result["list"][($xn)]["tgl_sj"]   = $val->TGL_SJ;
                        $result["list"][($xn)]["tgl_trans"] = $value->TGL_TRANS;
                        $result["list"][($xn)]["nama_expedisi"] = $value->NAMA_EXPEDISI;
                        $result["list"][($xn)]["no_polisi"]  = $value->NO_POLISI;
                        $result["list"][($xn)]["nama_driver"]= $value->NAMA_SOPIR;
                        $result["list"][($xn)]["status_rcv"] = $value->STATUS_RCV;
                        $result["list"][($xn)]["detailid"]= $val->XN;
                        $result["list"][($xn)]["detail"][($n)]['partno'] = $val->PART_NUMBER;
                        $result["list"][($xn)]["detail"][($n)]['partdes'] = $this->__getDeskripsiPart($val->PART_NUMBER);
                        $result["list"][($xn)]["detail"][($n)]['qty']    = $val->JUMLAH;
                        $result["list"][($xn)]["detail"][($n)]['qty_rfs']    = $val->JUMLAH_RFS;
                        $result["list"][($xn)]["detail"][($n)]['qty_nrfs']    = $val->JUMLAH_NRFS;
                        $result["list"][($xn)]["detail"][($n)]['status_part']    = $val->STATUS_PART;
                        $result["list"][($xn)]["detail"][($n)]['price']  = $val->HARGA_BELI;
                        $result["list"][($xn)]["detail"][($n)]['diskon'] = $val->DISKON;
                        $result["list"][($xn)]["detail"][($n)]['netprice']= $val->NETPRICE;
                        $result["list"][($xn)]["detail"][($n)]['kdtrans'] = $val->KD_TRANS;
                        $result["list"][($xn)]["detail"][($n)]['ppn']    = $val->PPN;
                        $result["list"][($xn)]["detail"][($n)]['rakbin'] = $val->KD_RAKBIN;
                        $result["list"][($xn)]["detail"][($n)]['rakbin_nrfs'] = $val->KD_RAKBIN_NRFS;
                        $result["list"][($xn)]["detail"][($n)]['dtlid'] = $val->TDID;
                     }
                     $totaldata +=$datax->totaldata;
               $nn++;
                  }
               }
            }
         }
      }
      $data=(($result));
      $data["hasil"]=$hasil;
      if($debug){
         print_r($data);
         exit();
      }
      $data["dealer"]      = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
      $string = link_pagination();
      $config = array(
        'per_page' => $paramh['limit'], 
        'base_url'  => $string[0],// base_url().'report/sales?keyword='.$param['keyword'].'&row_status='.$param['row_status'],
        'total_rows' => isset($hasil)?$hasil->totaldata:0
      );
      $pagination = $this->template->pagination($config);
      $this->pagination->initialize($pagination);
      $data['pagination'] = $this->pagination->create_links();
      $this->template->site('inventori/penerimaan_part_list',$data);
  }



    public function listpenerimaan_sp1($debug=null) {
        $data = array();  

        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(            
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => "NO_TRANS asc",
            'field' => "NO_TRANS,NO_SURATJALAN,NO_PO, replace(convert(char(11),TGL_TRANS,113),' ','-') as TGL_TRANS2",
            'custom' => $tgl,
          
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
            $params['where_in'] = isDealerAkses();
            $params['where_in_field'] = 'KD_DEALER';
        }        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima", $param));
         //print_r($data['list']);die();
       
       $tgl2 = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TRANS_PART_TERIMA.TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_PART_TERIMA.TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PART_TERIMA.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PART_TERIMA.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
      
        $params = array(
           
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
             
                array("TRANS_PART_TERIMADETAIL AS TT", "TT.NO_TRANS = TRANS_PART_TERIMA.NO_TRANS", ""),
                array("MASTER_PART AS MP", "MP.PART_NUMBER = TT.PART_NUMBER AND MP.ROW_STATUS >=0", "LEFT")
            ),

            'field' => 'TRANS_PART_TERIMA.*, TT.PART_NUMBER, TT.JUMLAH, TT.KD_RAKBIN, TT.HARGA_BELI, TT.KD_TRANS, MP.PART_DESKRIPSI,MP.PART_REFERENCE',
            'custom' =>$tgl2,
             
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima", $params));
        //print_r($data['list_group']);die();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('inventori/penerimaan_part_list1', $data);
    }



  /**
   * [penerimaanpart description]
   * @return [type] [description]
   */
  public function penerimaanpart($debug=null){
    $data=array();
    $data["sjm"]  = null;
    $data["dealer"]  = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
    if($this->input->get('n')){
      $nsj=strtoupper(base64_decode($this->input->get("n")));
      $nsj = str_replace("/", ".", $nsj);
      $data["sjm"]  =$this->sjmasuk_spws($nsj);
      
    }

    if($this->input->get('t')){
      $notrans=base64_decode($this->input->get('t'));
      $kd_dealer = $this->input->get('d');
      $data["sjm"]= $this->trans_penerimaan($notrans,false,$kd_dealer);

    }
    //print_r($data['sjm']);die();
    $paramgudang=array(
      'kd_dealer' =>($this->input->get('d'))?$this->input->get('d'):$this->session->userdata("kd_dealer")
    );
    $data["gudang"] = json_decode($this->curl->simple_get(API_URL."/api/marketing/lokasi_rak_bin",$paramgudang));

    if($debug==true){
      echo json_encode($data["sjm"]);
      exit;
    }else{
      $this->template->site('inventori/penerimaan_part', $data);
    }
  }
  /**
   * [trans_penerimaan description]
   * @param  boolean $notrans   [description]
   * @param  boolean $debug     [description]
   * @param  [type]  $kd_dealer [description]
   * @return [type]             [description]
   */
  function trans_penerimaan($notrans=false,$debug=false,$kd_dealer=null){
    $result=array();
    $result["no_data"]=true;
    $datax=array();

    $param=array(
      'kd_dealer'=> ($kd_dealer)?$kd_dealer:$this->session->userdata("kd_dealer"),
      'no_trans' => $notrans,
      'jointable'=> array(
        array("TRANS_PART_TERIMADETAIL TD","TD.NO_TRANS=TRANS_PART_TERIMA.NO_TRANS AND TD.ROW_STATUS >=0","LEFT"),
        array("TRANS_PART_SJMASUK AS SJM","SJM.NO_SJ=TRANS_PART_TERIMA.NO_SURATJALAN","LEFT","NO_SJ,TGL_SJ,NO_PO,JATUH_TEMPO")
      ),
      'field' => "TRANS_PART_TERIMA.*,TD.ID AS TDID,TD.PART_NUMBER,TD.JUMLAH, TD.JUMLAH_RFS,TD.JUMLAH_NRFS,TD.STATUS_PART,TD.HARGA_BELI,TD.PPN,TD.NETPRICE,TD.DISKON,TD.KD_RAKBIN,TD.KD_RAKBIN_NRFS,TD.KD_TRANS,
                  SJM.TGL_SJ,SJM.JATUH_TEMPO"
    );

    if(!$notrans){
      unset($param["no_trans"]);
    }
    $data=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part_terima",$param));
     //print_r($data);die();
    /*print_r($param);
    exit();*/
    if($data){
      if($data->totaldata>0){
        $n=0;
        $result["no_data"]=false;
        foreach ($data->message as $key => $value) {
            $result["kd_maindealer"]= $value->KD_MAINDEALER;
            $result["kd_dealer"]    = $value->KD_DEALER;
            $result["nama_dealer"]    = NamaDealer($value->KD_DEALER);
            $result["kd_dealerahm"]    = $this->__DealerAHMtoTM($value->KD_DEALER,true);
            $result["no_po"]    = $value->NO_PO;
            $result["no_sj"]    = $value->NO_SURATJALAN;
            $result["jth_tmp"]  = $value->JATUH_TEMPO;
            $result["tgl_sj"]   = $value->TGL_SJ;
            $result["tgl_trans"] = $value->TGL_TRANS;
            $result["nama_expedisi"] = $value->NAMA_EXPEDISI;
            $result["no_polisi"]  = $value->NO_POLISI;
            $result["nama_driver"]= $value->NAMA_SOPIR;
            $result["status_rcv"] = $value->STATUS_RCV;
            $result["detail"][$n]['partno'] = $value->PART_NUMBER;
            $result["detail"][$n]['partdes'] = $this->__getDeskripsiPart($value->PART_NUMBER);
            $result["detail"][$n]['status_part']    = $value->STATUS_PART;
            $result["detail"][$n]['qty']    = $value->JUMLAH;
            $result["detail"][$n]['qty_rfs']    = $value->JUMLAH_RFS;
            $result["detail"][$n]['qty_nrfs']    = $value->JUMLAH_NRFS;
            $result["detail"][$n]['price']  = $value->HARGA_BELI;
            $result["detail"][$n]['diskon'] = $value->DISKON;
            $result["detail"][$n]['netprice']= $value->NETPRICE;
            $result["detail"][$n]['kdtrans'] = $value->KD_TRANS;
            $result["detail"][$n]['ppn']    = $value->PPN;
            $result["detail"][$n]['rakbin'] = $value->KD_RAKBIN;
            $result["detail"][$n]['rakbin_nrfs'] = $value->KD_RAKBIN_NRFS;
            $result["detail"][$n]['detailid'] = $value->TDID;
            $n++;
        }
      }
    }
    if($debug){
      print_r($data);
    }else{
      return $result;
    }
  }
  /**
   * [defaultbin description]
   * @param  [type] $part_number [description]
   * @param  [type] $debug       [description]
   * @return [type]              [description]
   */
  function defaultbin($part_number=null,$debug=null){
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer")
    );
    if($part_number){
      $param["part_number"] = $part_number; 
    }
    $data=json_decode($this->curl->simple_get(API_URL . "/api/marketing/parts_vs_defaultrak", $param));
    if($data){
      if($data->totaldata >0){
        
      }
    }
  }
  /**
   * Check SJ from webservice
   */
  public function sjmasuk_spws($nosj=null,$debug=false){
        $result=array();
        $result["no_data"]=true;
        $datax=array();
        $nosj=str_replace(".", "/", $nosj);
        if(!$nosj){
            $result["no_data"]=true;
            $result["info"]="Nomor Surat Jalan tidak ditemukan";
        }else{
          //check apakah sj tersebut sudah pernah di download atau belum
          //kalau belum lanjut ke proses webservice dan simpan
          //kalau sudah tampilkan data part yng blm terkirim semua (qty > qty_rcv)
          //check apakah no sj yang di masukan sudah ada di table sjmasuk
          //jika data sudah pernah di download maka langsung tampikan data
          //jika data belum pernah di download maka inset ke tabke trans_part_sjmasuk
          $param=array(
            'no_sj' => $nosj,
            'field' =>'NO_SJ',
            'kd_dealer'=> $this->session->userdata("kd_dealer"),
            'groupby'=>TRUE
          );
          $checksj= json_decode($this->curl->simple_get(API_URL."/api/sparepart/part_sjmasuk",$param));
          if($checksj){
            if($checksj->totaldata>0){
              //tampilkan data 
              /*$result["no_data"]=false;
              $result["info"]=false;*/
              $result=$this->tampilkan_datasj($nosj);
            }else{
              //insert data download dari webservise
              $nosjm=explode("/",$nosj);
              $tahun=(count($nosjm)>1)?$nosjm[count($nosjm)-1]:0;
              $tahun=($tahun <= date('Y'))?$tahun:0;
              if($tahun >0){
                $param = array(
                    'link' => 'list29',
                    'param' =>$nosjm[0]."/".$tahun
                );
                $data= $this->curl->simple_get(API_URL."/api/login/webservice/true",$param);
                $datax=(json_decode($data));
                if($datax){
                  if(isset($datax)){
                    if($datax->status==false){
                      //jika tidak di temukan di webservice
                      $result["no_data"]=true;
                      $result["info"]=$datax->status.", ".$datax->message;
                    }else{
                      $n=0;
                      $result["no_data"]=false;
                      //check milik siapa
                      $xx =(json_decode($datax->message,true));
                      $dealer=KodeDealer(null,($xx[0]["kddlr"]));
                      //check apakah sj tersebut milik dealer yang login
                      if($dealer==$this->session->userdata("kd_dealer")){
                        //simpan dulu ke database
                        $hasil=json_decode($this->simpan_sjmasuk(json_decode($datax->message)));
                        if($hasil){
                          if($hasil->status==true){
                            /*$result["no_data"]=false;
                            $result["info"]=false;  */
                            $result=$this->tampilkan_datasj($nosj);                     
                          }
                        }
                      }else{
                        //jika bukan dealer milik yang login
                        $result["no_data"]=true;
                        $result["info"]="Surat jalan ini milik dealer ". NamaDealer($dealer); 
                        $result["dealerpunya"] = $dealer; 
                      }
                    }
                  }
                }
              }else{
                $result["no_data"]=true;
                $result["info"]="Nomor surat jalan tidak sesuai"; 
              }
            }
          }
        }
        
        if($debug==true){
          print_r($result);
        }else{
          return $result;
        }
    }
    function tampilkan_datasj($nosj,$debug=null){
      $result=array();
      $param=array(
        'no_sj' =>str_replace(".", "/", $nosj),
        'kd_dealer' => $this->session->userdata("kd_dealer"),
        'custom' => "QTY > ISNULL(QTY_RCV,0)",
        'field' => "MAX(ID) AS ID,KD_MAINDEALER,KD_DEALER,NO_SJ,TGL_SJ,NO_PO,JATUH_TEMPO,PART_NUMBER,QTY,QTY_RCV,PRICE,
        DISKON,PPN,NETPRICE,KD_TRANS,NO_REFF,KD_DEALERAHM,REFF_ID,\"(QTY min ISNULL(QTY_RCV,0))\" AS QTY_SISA",
        'groupby_text' =>"KD_MAINDEALER,KD_DEALER,NO_SJ,TGL_SJ,NO_PO,JATUH_TEMPO,PART_NUMBER,QTY,QTY_RCV,PRICE,
        DISKON,PPN,NETPRICE,KD_TRANS,NO_REFF,KD_DEALERAHM,REFF_ID"
      );
      $data=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part_sjmasuk",$param));
      if($data){
        if($data->totaldata >0){
          $n=0;
          $result["no_data"]=false;
          $result["info"]=false;
          foreach ($data->message as $key => $value) {
              $result["kd_maindealer"]= $value->KD_MAINDEALER;
              $result["kd_dealer"]    = $value->KD_DEALER;
              $result["nama_dealer"]    = NamaDealer($value->KD_DEALER);
              $result["kd_dealerahm"]    = $value->KD_DEALERAHM;
              $result["no_po"]    = $value->NO_PO;
              $result["no_sj"]    = $value->NO_SJ;
              $result["jth_tmp"]  = $value->JATUH_TEMPO;
              $result["tgl_sj"]   = $value->TGL_SJ;
              $result["detail"][$n]['partno'] = $value->PART_NUMBER;
              $result["detail"][$n]['partdes'] = $this->__getDeskripsiPart($value->PART_NUMBER);
              $result["detail"][$n]['qty']    = $value->QTY_SISA;
              $result["detail"][$n]['price']  = $value->PRICE;
              $result["detail"][$n]['diskon'] = $value->DISKON;
              $result["detail"][$n]['netprice']= $value->NETPRICE;
              $result["detail"][$n]['kdtrans'] = $value->KD_TRANS;
              $result["detail"][$n]['ppn']    = $value->PPN;
              $result["detail"][$n]['detailid']    = $value->ID;
              $n++;
          }
        }else{
          $result["no_data"]=true;
          $result["info"]="Item Surat Jalan ".strtoupper($param["no_sj"])." sudah diterima semua";
        }
      }else{
        $result["no_data"]=true; 
        $result["info"]="Nomor Surat Jalan ".strtoupper($param["no_sj"])." tidak ditemukan";     
      }

      if($debug){
        echo json_encode($result);
        exit();
      }
      return $result;
    }
    function __getDeskripsiPart($partno=null){
      if($partno==''){
        return false;
        //exit();
      }
      $param=array(
        'part_number'=>$partno,
        'field' =>'PART_DESKRIPSI,PART_REFERENCE'
      );
      $data=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part",$param));
      if($data){
        if($data->totaldata>0){
          foreach ($data->message as $key => $value) {
            return $value->PART_DESKRIPSI ." - ". $value->PART_REFERENCE;
          }
        }
      }
    }
    function __DealerAHMtoTM($kdDealerAhm=null,$TMtoAHM=false){
      $param=array(
        'kd_dealerahm'=>$kdDealerAhm,
        'field' =>'KD_DEALER,NAMA_DEALER,KD_DEALERAHM'
      );
      if($TMtoAHM==true){
        unset($param["kd_dealerahm"]);
        $param["kd_dealer"] = $kdDealerAhm;
      }
      $data=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",$param));
      if($data){
        if($data->totaldata>0){
          foreach ($data->message as $key => $value) {
            return ($TMtoAHM==true)?$value->KD_DEALERAHM:$value->KD_DEALER;
          }
        }
      }
    }
    function simpan_sjmasuk($json){
      $paramsales=array(
            'query'  => $this->Custom_model->simpan_sjmasuk(json_encode($json))
        );
      //var_dump($paramsales);exit();
      $hasil = $this->curl->simple_post(API_URL."/api/sales/salesmannew", $paramsales, array(CURLOPT_BUFFERSIZE => 100));
      return $hasil;
    }
    function simpanpenerimaan(){
      $hasil="";
        $data = array();
        $pdata = json_decode($this->input->post("detail"),true);
        $ntrans=($this->input->post('no_trans'))?$this->input->post('no_trans'):$this->nomor_trans();
        $param=array(
          'no_trans'  => $ntrans,
          'kd_maindealer' => $this->session->userdata("kd_maindealer"),
          'kd_dealer'     => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata('kd_dealer'),
          'no_suratjalan' => $this->input->post('no_sjmasuk'),
          'tgl_trans'     => $this->input->post('tgl_sj'),
          'no_po'         => $this->input->post('no_po'),
          'nama_expedisi' => $this->input->post('nama_expedisi'),
          'no_polisi'     => $this->input->post('no_polisi'),
          'nama_sopir'   => $this->input->post('nama_driver'),
          'status_rcv'    => 0,
          'created_by'    => $this->session->userdata("user_id")
        );
        //print_r($pdata);die();
        $hasil = json_decode($this->curl->simple_post(API_URL."/api/sparepart/part_terima", $param, array(CURLOPT_BUFFERSIZE => 100)));
        if($hasil->recordexists==TRUE){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL."/api/sparepart/part_terima", $param, array(CURLOPT_BUFFERSIZE => 100)));
        }
        //if($hasil){
         $jml_tagihan = $this->simpandetail_tsp($pdata,$ntrans);
      
         if(((double)$jml_tagihan)>0){
            //TODO : Process hutang persediaan
            $this->proses_hutang($param,$jml_tagihan);
         }
        echo base64_encode($ntrans).":0:1";
    }
    function simpandetail_tsp($data=null,$notrans=null){
      $jml_tagihan =0;
        for ($i=0;$i< count($data);$i++){
          $rakbin=explode(":",$data[$i]["kd_gudang"]);
          $rakbinNRFS=explode(":",$data[$i]["kd_gudangNRFS"]);
          //$rakbin=$rakbin[0];
         
          $param=array(
            'no_trans'    => $notrans,
            'part_number' => $data[$i]["part_number"],
            'harga_beli'  => ($data[$i]["price"])?$data[$i]["price"]:"0",
            'ppn'         => ($data[$i]["ppn"])?$data[$i]["ppn"]:"0",
            'diskon'      => ($data[$i]["diskon"])?$data[$i]["diskon"]:"0",
            'netprice'    => ($data[$i]["netprice"])?$data[$i]["netprice"]:"0",
            'jumlah'      => $data[$i]["qty"],
            'jumlah_rfs'  => $data[$i]["qty_rfs"],
            'jumlah_nrfs' => $data[$i]["qty_nrfs"],
            'status_part' => ($data[$i]["qty_nrfs"] == 0 )?1:0,
            'kd_trans'    => $data[$i]["kdtrans"],
            'kd_gudang'   => (count($rakbin)>1)?$rakbin[1]:"",
            'kd_rakbin'   => $rakbin[0],
            'kd_rakbinNRFS'   => $rakbinNRFS[0],
            'part_batch'  => date('Ymd').str_replace('.00','',$data[$i]["price"]),
            'kd_dealer'   => $data[$i]["kd_dealer"],
            'no_sjmasuk'  => $data[$i]["no_sjmasuk"]
          );

         // print_r($param);die();
          $hasil = json_decode($this->curl->simple_post(API_URL."/api/sparepart/part_terimadetail", $param, array(CURLOPT_BUFFERSIZE => 100)));
          //var_dump($hasil);print_r($param);exit(); tested on 11/10
          if($hasil){
            if($hasil->recordexists>0){
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil = json_decode($this->curl->simple_put(API_URL."/api/sparepart/part_terimadetail", $param, array(CURLOPT_BUFFERSIZE => 100)));
            }
          }
          if($hasil){
            $jml_tagihan +=($param["jumlah"]* $param["netprice"]);
          }
        }
        return $jml_tagihan;
    }
    function proses_hutang($param,$jml_tagihan=null){
      return true;
    }
    function deletedetail_tsp($id=0){
      $data=array();
      $param=array('id' => $id, 'lastmodified_by' => $this->session->userdata("user_id"));
      $hasil = json_decode($this->curl->simple_delete(API_URL."/api/sparepart/part_terimadetail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      if($hasil){
        if($hasil->status==true){
          $data=array(
            'status'  => true,
            'message' => 'Data berhasil di hapus',
            'param'   => $hasil
          );
        }else{
          $data=array(
            'status'  => false,
            'message' => 'Data gagal di hapus',
            'param'   => $hasil
          );
        }
      }
      $this->output->set_output(json_encode($hasil));
    }
    function deletepenerimaan($id=0){
     $data=array(
            'status'  =>false,
            'message' => 'Data gagal di hapus',
            'param'   => $id
          );
      $param=array('id' => $id, 'lastmodified_by' => $this->session->userdata("user_id"));
      $hasil = json_decode($this->curl->simple_delete(API_URL."/api/sparepart/part_terima", $param, array(CURLOPT_BUFFERSIZE => 10)));
      if($hasil){
        if($hasil->status==true){
          $data=array(
            'status'  =>true,
            'message' => 'Data berhasil di hapus',
            'param'   => $hasil
          );
        }
      }
      $this->output->set_output(json_encode($data));
    }
    function nomor_trans(){
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => 'RC',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tgl_sj'), 6, 4),
           // 'bulan_docno' => (int)substr($this->input->post('tgl_spk'), 3, 2),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = substr($this->input->post('tgl_sj'), 3, 2);
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpo == 0) {
            $nopo = "RC" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = "RC" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
        }
        //var_dump($nopo);exit();
        return $nopo;
    }

   function list_barang($ajax=true,$onlyBarang=null){
      $data=array(); $result="[]";
      $param=array(
        'field' =>"KD_BARANG,NAMA_BARANG,KATEGORI",
        'orderby' => "KATEGORI,NAMA_BARANG"
      );
      if($onlyBarang==true){
        $param["kategori"] = "Barang";
      }
      $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));
      if($data){
        if($data->totaldata>0){
          $result = $data->message;
        }
      }
      if($ajax){ echo json_encode($result);}else{ return $result;}
   }
   function list_sp_w_stock($ajax=true,$selainDealer=null){
      $data=array(); $result=array();$kategori="";
      switch ($this->input->get('jt')) {
         case 'Penjualan Aksesoris':
         case 'Penjualan Apparel':
         case 'Pengeluaran Barang':
         case 'barang':
         case 'Barang':
            $param=array(
               'kd_maindealer' => $this->session->userdata("kd_maindealer"),
               'kd_dealer'     => $this->session->userdata("kd_dealer")/*,
               'tahun'         => date ('Y'),
               'bulan'         => date('m')*/
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang_stock", $param));
         break;
         case 'Jasa':
            $kategori="Jasa";
            $param=array(
               "field" => "KD_JASA AS PART_NUMBER, KETERANGAN AS PART_DESKRIPSI, 1 AS JUMLAH_SAK, HARGA AS HARGA_JUAL"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa", $param));
         break;
         default:
            $kategori="Part";
            $param=array(
               'kd_dealer' => $this->session->userdata("kd_dealer"),
               'kd_maindealer' => $this->session->userdata("kd_maindealer"),
               'tahun' => date('Y'),
               'bulan' => date('m'),
               'stoked_only'=>$this->input->get("os")
            );
            if($selainDealer!=''){
               unset($param["kd_dealer"]);
               $param["customs"] = " AND T.KD_DEALER NOT IN('".$selainDealer."')";
            }
            if($this->input->get('part_number')){
               $param["part_number"] = trim(str_replace("+","",$this->input->get("part_number")));
            }
            if($this->input->get('keyword')){
               $param["keyword"] = $this->input->get("keyword");
            }
            $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/part_stock", $param));
         break;
      }
      if($data){
         if($data->totaldata>0){
            $result = $data;//->message;
         }
      }
      if($ajax==TRUE){ echo json_encode($result);}else{ return $data;}
   }
}