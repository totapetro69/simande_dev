<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_hc3 extends CI_Controller {

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
    

    /**metodefu description]
     * @return [type] [description]
     */
    public function metodefu() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'MASTER_METODEFU.*',
            'orderby' => 'MASTER_METODEFU.ID DESC'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu", $param));
        
        //var_dump($data["list"]);
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('master_hc3/metodefu/view', $data);
    }

    public function add_metodefu() {
        $this->auth->validate_authen('master_hc3/metodefu');
        
        $this->load->view('master_hc3/metodefu/add');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_metodefu_simpan() {
        $this->form_validation->set_rules('nama_metode', 'Nama Metode', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {          

            $param = array(
                'nama_metode' => $this->input->post("nama_metode"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master_hc3/metodefu", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('master_hc3/metodefu'));
        }
    }

    public function edit_metodefu($id, $row_status) {
        $this->auth->validate_authen('master_hc3/metodefu');
        $param = array(
            "custom" => "MASTER_METODEFU.ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_hc3/metodefu/edit', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_metodefu($id) {
        $this->form_validation->set_rules('nama_metode', 'Nama Metode', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'id' => $this->input->post("id"),
                'nama_metode' => $this->input->post("nama_metode"),
                'row_status' => $this->input->post("row_status"),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $hasil = $this->curl->simple_put(API_URL . "/api/master_hc3/metodefu", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_metodefu($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_hc3/metodefu", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_hc3/metodefu')
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

    public function metodefu_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/master_hc3/metodefu"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_METODE;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    /**callvisit description]
     * @return [type] [description]
     */
    public function callvisit() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'SETUP_CALLVISIT.*',
            'orderby' => 'SETUP_CALLVISIT.ID DESC'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/callvisit", $param));
        
        //var_dump($data["list"]);
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('master_hc3/callvisit/view', $data);
    }

    public function add_callvisit() {
        $this->auth->validate_authen('master_hc3/callvisit');
        
        $this->load->view('master_hc3/callvisit/add');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_callvisit_simpan() {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {          

            $param = array(
                'kategori' => $this->input->post("kategori"),
                'status' => $this->input->post("status"),
                'keterangan' => $this->input->post("keterangan"),
                'klasifikasi' => $this->input->post("klasifikasi"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/callvisit", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('master_hc3/callvisit'));
        }
    }

    public function edit_callvisit($id, $row_status) {
        $this->auth->validate_authen('master_hc3/callvisit');
        $param = array(
            "custom" => "SETUP_CALLVISIT.ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/callvisit", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_hc3/callvisit/edit', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_callvisit($id) {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');  
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            
            $param = array(
                'id' => $this->input->post("id"),
                'kategori' => $this->input->post("kategori"),
                'status' => $this->input->post("status"),
                'keterangan' => $this->input->post("keterangan"),
                'klasifikasi' => $this->input->post("klasifikasi"),
                'row_status' => $this->input->post("row_status"),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/callvisit", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_callvisit($id) {
        $data = array();        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/callvisit", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_hc3/callvisit')
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

    public function callvisit_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/marketing/callvisit"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }
    
    public function hasilfu() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'SETUP_HASIL_FU.*',
            'orderby' => 'SETUP_HASIL_FU.ID DESC'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/hasil_fu", $param));
        
        //var_dump($data["list"]);
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('master_hc3/hasilfu/view', $data);
    }

    public function add_hasilfu() {
        $this->auth->validate_authen('master_hc3/hasilfu');
        
        $this->load->view('master_hc3/hasilfu/add');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_hasilfu_simpan() {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {          

            $param = array(
                'kategori' => $this->input->post("kategori"),
                'status' => $this->input->post("status"),
                'klasifikasi' => $this->input->post("klasifikasi"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/setup/hasil_fu", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
//            var_dump($hasil);
//            exit();
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('master_hc3/hasilfu'));
        }
    }

    public function edit_hasilfu($id, $row_status) {
        $this->auth->validate_authen('master_hc3/hasilfu');
        $param = array(
            "custom" => "SETUP_HASIL_FU.ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/hasil_fu", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_hc3/hasilfu/edit', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_hasilfu($id) {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            
            $param = array(
                'id' => $this->input->post("id"),
                'kategori' => $this->input->post("kategori"),
                'status' => $this->input->post("status"),
                'klasifikasi' => $this->input->post("klasifikasi"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/hasil_fu", $param, array(CURLOPT_BUFFERSIZE => 10));
//            var_dump($hasil);            exit();
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_hasilfu($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/hasil_fu", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_hc3/hasilfu')
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

    public function hasilfu_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/hasil_fu"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
            $data_message[1][$key] = $message->STATUS;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
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
