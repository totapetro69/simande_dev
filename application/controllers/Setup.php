<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

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
        $this->load->model("Custom_model");
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

    public function setup_docno() {
        $data = array();
        $config['per_page'] = '12';
        $data['per_page'] = $config['per_page'];
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page'],
            'field' => '*'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/setup_docno", $param));
        $config['base_url'] = base_url() . 'setup/setup_docno?keyword=' . $param['keyword'];
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
        $this->template->site('general/list_penomoran', $data);
    }

    public function add_data() {
        $this->load->view('general/add_data');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    function add_data_simpan() {
        $this->form_validation->set_rules('kd_docno', 'Kode Nomor Dokumen', 'required|trim');
        $this->form_validation->set_rules('nama_docno', 'Nama Nomor Dokumen', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Nama Dealer', 'required|trim');
        $this->form_validation->set_rules('tahun_docno', 'Tahun Nomor Dokumen', 'required|trim');
        $this->form_validation->set_rules('bulan_docno', 'Bulan Nomor Dokumen', 'required|trim');
        $this->form_validation->set_rules('urutan_docno', 'Urutan Nomor Dokumen', 'required|trim');
        $this->form_validation->set_rules('reset_docno', 'Reset Nomor Dokumen', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_docno' => $this->input->post("kd_docno"),
                'nama_docno' => $this->input->post("nama_docno"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'tahun_docno' => $this->input->post("tahun_docno"),
                'bulan_docno' => $this->input->post("bulan_docno"),
                'urutan_docno' => $this->input->post("urutan_docno"),
                'reset_docno' => $this->input->post("reset_docno"),
                'created_by' => $this->session->userdata("userid")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/setup_docno", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->data_output($hasil, 'post');
        }
    }

    public function edit_data($kd_docno) {
        $param = array(
            'kd_docno' => $kd_docno
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/setup_docno", $param));
        $this->load->view('general/edit_data', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_data() {
        $hasil = "";
        $param = array(
            'kd_docno' => $this->input->post('kd_docno'),
            'nama_docno' => $this->input->post("nama_docno"),
            'kd_dealer' => $this->input->post("kd_dealer"),
            'tahun_docno' => $this->input->post("tahun_docno"),
            'bulan_docno' => $this->input->post("bulan_docno"),
            'urutan_docno' => $this->input->post("urutan_docno"),
            'reset_docno' => $this->input->post("reset_docno"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        //print_r($param);
        $hasil = $this->curl->simple_put(API_URL . "/api/setup/setup_docno", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->data_output($hasil);
    }

    public function setup_docno_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/setup_docno"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DOCNO;
            $data_message[1][$key] = $message->NAMA_DOCNO;
            $data_message[2][$key] = $message->KD_DEALER;
            $data_message[3][$key] = $message->TAHUN_DOCNO;
            $data_message[4][$key] = $message->BULAN_DOCNO;
            $data_message[5][$key] = $message->URUTAN_DOCNO;
            $data_message[6][$key] = $message->RESET_DOCNO;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
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
                        'message' => "Data berhasil simpan",
                        'location' => $location
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
                        'message' => "Update berhasil",
                        'location' => $location
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

    /**
     * [groupcustomer description]
     * @return [type] [description]
     */
    public function groupcustomer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            //'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=MASTER_GROUPCUSTOMER.KD_DEALER", "LEFT"),
                array("MASTER_PROPINSI MC", "MC.KD_PROPINSI=MASTER_GROUPCUSTOMER.KD_PROPINSI", "LEFT"),
                array("MASTER_KABUPATEN K", "K.KD_KABUPATEN=MASTER_GROUPCUSTOMER.KD_KABUPATEN", "LEFT")
            ),
            'field' => 'MASTER_GROUPCUSTOMER.*, MD.NAMA_DEALER, MC.NAMA_PROPINSI, K.NAMA_KABUPATEN',
            'orderby' => 'MASTER_GROUPCUSTOMER.ID desc',
            "custom" => "MASTER_GROUPCUSTOMER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        //var_dump($data);
        $this->template->site('master_service/groupcustomer/view', $data);
    }

    public function add_groupcustomer() {
        $this->auth->validate_authen('setup/groupcustomer');
        //$paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $this->load->view('master_service/groupcustomer/add', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function kabupaten() {
        $param = array(
            'kd_propinsi' => $this->input->post('kd')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
        echo "<option value='0'>--Pilih Kabupaten--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_KABUPATEN . "'>" . $value->NAMA_KABUPATEN . "</option>";
                }
            }
        }
    }

    public function add_groupcustomer_simpan() {
        $this->form_validation->set_rules('nama', 'Nama Group Customer', 'required|trim');
        //$this->form_validation->set_rules('nama_perusahaan', 'Kode Perusahaan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_groupcustomer' => $this->getKDGC(),
                'nama_groupcustomer' => $this->input->post("nama"),
                'alamat_lengkap' => $this->input->post("alamat"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'no_telp' => $this->input->post("no_telp"),
                'no_npwp' => $this->input->post("npwp"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/groupcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/groupcustomer'));
        }
    }

    public function getKDGC() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kd_maindealer='" . $this->session->userdata('kd_maindealer') . "'"
        );
        $bulan = date('m');
        $tahun = date('Y');
        $data = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer", $param));
        $number = str_pad(($data->totaldata) + 1, 6, '0', STR_PAD_LEFT);
        return "GC" . str_pad($this->session->userdata("kd_maindealer"), 3, 0, STR_PAD_LEFT) . $tahun . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . $number;
    }

    public function edit_groupcustomer($id, $row_status) {
        $this->auth->validate_authen('setup/groupcustomer');
        $param = array(
            'custom' => "id = '" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $this->load->view('master_service/groupcustomer/edit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_groupcustomer($id) {
        $this->form_validation->set_rules('nama', 'Nama Grup Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_groupcustomer' => $this->input->post("kd_groupcustomer"),
                'nama_groupcustomer' => $this->input->post("nama"),
                'alamat_lengkap' => $this->input->post("alamat"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'no_telp' => $this->input->post("no_telp"),
                'no_npwp' => $this->input->post("npwp"),
                'row_status' => $this->input->post("row_status"),
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/groupcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_groupcustomer($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/groupcustomer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('Setup/groupcustomer')
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

    public function groupcustomer_typeahead() {
        $data = [];
        $keywords = $this->input->get('keyword');
        $param = array(
            'keyword' => $keywords
        );
        $data_message = [];
        $data = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer", $param));
        // var_dump($data); exit;
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->KD_GROUPCUSTOMER . " - " . $message->NAMA_GROUPCUSTOMER . " - " . $message->NO_TELP;
                }
            } else {
                $data_message['keyword'][] = ""; //$data->message;
            }
        } else {
            $data_message['keyword'][0] = "<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,
        $this->output->set_output(json_encode($data_message));
    }

    public function customer_typeahead() {
        $data = [];
        $keywords = $this->input->get('keyword');
        $param = array(
            'keyword' => $keywords
        );
        $data_message = [];
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $param));
        // var_dump($data); exit;
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->KD_CUSTOMER . " - " . $message->NAMA_CUSTOMER . " - " . tglfromsql($message->TGL_LAHIR);
                }
            } else {
                $data_message['keyword'][] = ""; //$data->message;
            }
        } else {
            $data_message['keyword'][0] = "<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,
        $this->output->set_output(json_encode($data_message));
    }

    /**
     * [groupcustomer_mapping description]
     * @return [type] [description]
     */
    public function gcmapping() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            //'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=MASTER_GROUPCUSTOMER_MAPPING.KD_DEALER", "LEFT"),
                array("SETUP_TYPECUSTOMER MC", "MC.KD_TYPECUSTOMER=MASTER_GROUPCUSTOMER_MAPPING.KD_TYPECUSTOMER", "LEFT"),
                array("MASTER_GROUPCUSTOMER K", "K.KD_GROUPCUSTOMER=MASTER_GROUPCUSTOMER_MAPPING.KD_GROUPCUSTOMER", "LEFT")
            ),
            'field' => 'MASTER_GROUPCUSTOMER_MAPPING.*, MD.NAMA_DEALER, MC.NAMA_TYPECUSTOMER, K.NAMA_GROUPCUSTOMER',
            'orderby' => 'MASTER_GROUPCUSTOMER_MAPPING.ID desc',
            "custom" => "MASTER_GROUPCUSTOMER_MAPPING.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer_mapping", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        //var_dump($data);
        $this->template->site('master_service/gcmapping/view', $data);
    }

    public function add_gcmapping() {
        $this->auth->validate_authen('setup/gcmapping');
        //$paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        $data["groupcustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer"));
        $this->load->view('master_service/gcmapping/add', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_gcmapping_simpan() {
        $this->form_validation->set_rules('kd_groupcustomer', 'Group Customer', 'required|trim');
        $this->form_validation->set_rules('kd_typecustomer', 'Tipe Customer', 'required|trim');
        //$this->form_validation->set_rules('nama_perusahaan', 'Kode Perusahaan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_groupcustomer' => substr($this->input->post("kd_groupcustomer"), 0, 18),
                'kd_typecustomer' => $this->input->post("kd_typecustomer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/groupcustomer_mapping", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/gcmapping'));
        }
    }

    public function edit_gcmapping($id, $row_status) {
        $this->auth->validate_authen('part/groupcustomerpart');
        $param = array(
            'custom' => "id = '" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer_mapping", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        $data["groupcustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer"));
        $this->load->view('master_service/gcmapping/edit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_gcmapping($id) {
        $this->form_validation->set_rules('kd_groupcustomer', 'Grup Customer', 'required|trim');
        $this->form_validation->set_rules('kd_typecustomer', 'Tipe Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_groupcustomer' => substr($this->input->post("kd_groupcustomer"), 0, 18),
                'kd_typecustomer' => $this->input->post("kd_typecustomer"),
                'row_status' => $this->input->post("row_status"),
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/groupcustomer_mapping", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_gcmapping($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/groupcustomer_mapping", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('Setup/gcmapping')
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

    public function gcmapping_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer_mapping"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_GROUPCUSTOMER;
            $data_message[1][$key] = $message->KD_TYPECUSTOMER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    public function proposal_gc() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_PRO_GC.KD_DEALER", "LEFT"),
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=TRANS_PRO_GC.KD_KABUPATEN", "LEFT")
            ),
            'field' => "TRANS_PRO_GC.*, MK.NAMA_KABUPATEN, MD.NAMA_DEALER",
            'orderby' => 'TRANS_PRO_GC.ID desc',
            "custom" => "TRANS_PRO_GC.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param));
        //var_dump($data["list"]);exit;
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/proposal_gc/view', $data);
    }

    public function detail_proposal_gc($id) {
        $this->auth->validate_authen('setup/proposal_gc');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'TRANS_PRO_GC_DETAIL.ID DESC',
            "custom" => "TRANS_PRO_GC_DETAIL.NO_TRANS='" . $id . "'"
        );
        $param_cek = array(
            "custom" => "TRANS_PRO_GC.ID='" . $id . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param));
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param_cek));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/proposal_gc/view_detail', $data);
    }

    public function proposal_gc_print($id) {
        $data = array();
        $param = array(
            'orderby' => 'TRANS_PRO_GC_DETAIL.ID DESC',
            "custom" => "TRANS_PRO_GC_DETAIL.NO_TRANS='" . $id . "'"
        );
        $param_cek = array(
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_PRO_GC.KD_DEALER", "LEFT"),
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=TRANS_PRO_GC.KD_KABUPATEN", "LEFT"),
                array("MASTER_KARYAWAN MY", "MY.NIK=TRANS_PRO_GC.CREATED_BY", "LEFT")
            ),
            'field' => "TRANS_PRO_GC.*, MK.NAMA_KABUPATEN, MD.NAMA_DEALER, MY.NAMA",
            "custom" => "TRANS_PRO_GC.ID='" . $id . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param));
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param_cek));
        $this->load->view('master_service/proposal_gc/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_proposal_gc() {
        $this->auth->validate_authen('setup/proposal_gc');
        $data = array();
        $param = array(
            'field' => "MASTER_GC.KD_GC, MASTER_GC.NAMA_PROGRAM, MASTER_GC.START_DATE, MASTER_GC.END_DATE",
            "custom" => "'" . date("Y-m-d") . "' BETWEEN MASTER_GC.START_DATE AND MASTER_GC.END_DATE",
            'groupby' => TRUE
        );
        //var_dump($param);exit;
        $param_kab = array(
            'jointable' => array(
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=MASTER_DEALER.KD_KABUPATEN", "LEFT")
            ),
            'field' => "MASTER_DEALER.KD_KABUPATEN AS KD_KABUPATEN, MK.NAMA_KABUPATEN AS NAMA_KABUPATEN",
            "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["groupcustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer"));
        $data["gc"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc", $param));
        $data["leasing"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        $this->load->view('master_service/proposal_gc/add', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function detail_add_proposal_gc($id, $kd_gc) {
        $data = array();
        $param = array(
            "custom" => "TRANS_PRO_GC.ID='" . $id . "'"
        );
        $param_gc = array(
            "custom" => "MASTER_GC.KD_GC='" . $kd_gc . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param));
        $data["gc"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc", $param_gc));
        $this->load->view('master_service/proposal_gc/add_detail', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function getKDProGC() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kd_dealer='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param));
        $number = str_pad(($data->totaldata) + 1, 4, '0', STR_PAD_LEFT);
        return "GC" . str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT) . '-' . date('Y') . '-' . $number;
    }

    public function add_proposal_gc_simpan() {
        $this->form_validation->set_rules('desc_program', 'Deskripsi', 'required|trim');
        $this->form_validation->set_rules('type', 'Tipe', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Tanggal Selesai', 'required|trim');
        $this->form_validation->set_rules('jenis_trans', 'Jenis Transaksi', 'required|trim');
        $this->form_validation->set_rules('kd_gc', 'Kode Master GC', 'required|trim');
        //$this->form_validation->set_rules('kd_leasing', 'Leasing', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            /* if ($this->input->post("kd_leasing") == 'ALL') {
              $kd_leasing = $this->input->post("kd_leasing");
              } else {
              $kd_leasing = implode(", ", $this->input->post("leasing"));;
              } */
            $param = array(
                'no_trans' => $this->getKDProGC(),
                'desc_program' => $this->input->post("desc_program"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'type' => $this->input->post("type"),
                'kd_gc' => $this->input->post("kd_gc"),
                'jenis_trans' => $this->input->post("jenis_trans"),
                'no_po_perusahaan' => $this->input->post("no_po_perusahaan"),
                //'kd_leasing' => $kd_leasing,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/pro_gc", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/proposal_gc'));
        }
    }

    public function detail_add_proposal_gc_simpan() {
        $this->form_validation->set_rules('kd_typemotor', 'Kode Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('qty', 'Qty', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'no_trans' => $this->input->post("id"),
                'qty' => $this->input->post("qty"),
                'kd_typemotor' => $this->input->post("kd_typemotor"),
                's_ahm' => $this->input->post("s_ahm"),
                's_md' => $this->input->post("s_md"),
                's_sd' => $this->input->post("s_sd"),
                'sk_finance' => $this->input->post("sk_finance"),
                'sc_ahm' => $this->input->post("sc_ahm"),
                'sc_md' => $this->input->post("sc_md"),
                'sc_sd' => $this->input->post("sc_sd"),
                'harga_kontrak' => $this->input->post("harga_kontrak"),
                'fee' => $this->input->post("fee"),
                'pengurusan_stnk' => $this->input->post("pengurusan_stnk"),
                'pengurusan_bpkb' => $this->input->post("pengurusan_bpkb"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/pro_gc_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/proposal_gc'));
        }
    }

    public function edit_proposal_gc($id, $row_status) {
        $this->auth->validate_authen('setup/proposal_gc');
        $data = array();
        $param_list = array(
            "custom" => "TRANS_PRO_GC.ID='" . $id . "'",
            'row_status' => $row_status
        );

        $param_detail = array(
            "custom" => "TRANS_PRO_GC_DETAIL.NO_TRANS='" . $id . "'",
            'row_status' => $row_status
        );

        $param = array(
            'field' => "MASTER_GC.KD_GC, MASTER_GC.NAMA_PROGRAM, MASTER_GC.START_DATE, MASTER_GC.END_DATE",
            "custom" => "'" . date("Y-m-d") . "' BETWEEN MASTER_GC.START_DATE AND MASTER_GC.END_DATE",
            'groupby' => TRUE
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["groupcustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer"));
        $data["gc"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc", $param));
        $data["leasing"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param_list));
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param_detail));
        $this->load->view('master_service/proposal_gc/edit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function leasing_proposal_gc($id, $row_status) {
        $this->auth->validate_authen('setup/proposal_gc');
        $data = array();
        $param = array(
            "custom" => "TRANS_PRO_GC_LEASING.NO_TRANS='" . $id . "'"
        );
        $data["leasing"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_leasing", $param));
        $data["id"] = $id;
        //var_dump($data["list"]);exit;
        if ($data["list"]->totaldata != 0) {
            $this->load->view('master_service/proposal_gc/leasing_edit', $data);
        } else {
            $this->load->view('master_service/proposal_gc/leasing', $data);
        }
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_leasing_proposal_gc_simpan() {
        $this->form_validation->set_rules('kd_leasing', 'Deskripsi', 'required|trim');
        $data = array();
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            //$hasil="";
            if ($this->input->post("kd_leasing") == 'ALL') {
                $data = array();
                $param = array(
                    'no_trans' => $this->input->post("id"),
                    'kd_leasing' => $this->input->post("kd_leasing"),
                    'no_pro' => '',
                    'created_by' => $this->session->userdata('user_id')
                );
                $param_cek = array(
                    "custom" => "TRANS_PRO_GC_LEASING.NO_TRANS='" . $this->input->post("id") . "'"
                );
                //var_dump($param_cek);exit;
                $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_leasing", $param_cek));
                if ($data["list"]->totaldata != 0) {
                    $param_delete = array(
                        'no_trans' => $this->input->post("id")
                    );
                    json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/pro_gc_leasing", $param_delete));
                }
                $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/pro_gc_leasing", $param, array(CURLOPT_BUFFERSIZE => 10));
            } else {
                $data = array();
                $records = $this->input->post("leasing");
                $param_cek = array(
                    "custom" => "TRANS_PRO_GC_LEASING.NO_TRANS='" . $this->input->post("id") . "'"
                );
                $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_leasing", $param_cek));
                if ($data["list"]->totaldata != 0) {
                    $param_delete = array(
                        'no_trans' => $this->input->post("id")
                    );
                    json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/pro_gc_leasing", $param_delete));
                }
                if (is_array($records)) {
                    foreach ($records as $key => $value) {
                        $param = array(
                            'no_trans' => $this->input->post("id"),
                            'kd_leasing' => $records[$key],
                            'no_pro' => '',
                            'created_by' => $this->session->userdata('user_id')
                        );
                        //var_dump($param);exit;
                        $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/pro_gc_leasing", $param, array(CURLOPT_BUFFERSIZE => 10));
                    }
                }
            }
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/proposal_gc'));
        }
    }

    public function detail_edit_proposal_gc($id, $row_status) {
        $param = array(
            'jointable' => array(
                array("TRANS_PRO_GC MK", "MK.ID=TRANS_PRO_GC_DETAIL.NO_TRANS", "LEFT")
            ),
            'field' => "TRANS_PRO_GC_DETAIL.*, MK.NO_TRANS AS NO_PRO, MK.KD_GC, MK.JENIS_TRANS",
            "custom" => "TRANS_PRO_GC_DETAIL.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param));
        $this->load->view('master_service/proposal_gc/edit_detail', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_proposal_gc($id) {
        $this->form_validation->set_rules('desc_program', 'Deskripsi', 'required|trim');
        $this->form_validation->set_rules('type', 'Tipe', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Tanggal Selesai', 'required|trim');
        $this->form_validation->set_rules('jenis_trans', 'Jenis Transaksi', 'required|trim');
        $this->form_validation->set_rules('kd_gc', 'Kode Master GC', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'no_trans' => $this->input->post("no_trans"),
                'desc_program' => $this->input->post("desc_program"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'type' => $this->input->post("type"),
                'kd_gc' => $this->input->post("kd_gc"),
                'jenis_trans' => $this->input->post("jenis_trans"),
                'no_po_perusahaan' => $this->input->post("no_po_perusahaan"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/pro_gc", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function detail_update_proposal_gc($id) {
        $this->form_validation->set_rules('kd_typemotor', 'Kode Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('qty', 'Qty', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'no_trans' => $this->input->post("no_trans"),
                'qty' => $this->input->post("qty"),
                'kd_typemotor' => $this->input->post("kd_typemotor"),
                's_ahm' => $this->input->post("s_ahm"),
                's_md' => $this->input->post("s_md"),
                's_sd' => $this->input->post("s_sd"),
                'sk_finance' => $this->input->post("sk_finance"),
                'sc_ahm' => $this->input->post("sc_ahm"),
                'sc_md' => $this->input->post("sc_md"),
                'sc_sd' => $this->input->post("sc_sd"),
                'harga_kontrak' => $this->input->post("harga_kontrak"),
                'fee' => $this->input->post("fee"),
                'pengurusan_stnk' => $this->input->post("pengurusan_stnk"),
                'pengurusan_bpkb' => $this->input->post("pengurusan_bpkb"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/pro_gc_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_proposal_gc($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/pro_gc", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/proposal_gc')
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

    public function detail_delete_proposal_gc($id, $no_trans) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/pro_gc_detail", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/detail_proposal_gc/' . $no_trans)
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

    public function proposal_gc_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/transaksi/pro_gc"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_TRANS;
            $data_message[1][$key] = $message->DESC_PROGRAM;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    public function detail_proposal_gc_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/transaksi/pro_gc_detail"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TYPEMOTOR;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    public function download_udpgc($id) {
        $param = array(
            'jointable' => array(
                array("TRANS_PRO_GC TP", "TP.ID=TRANS_PRO_GC_DETAIL.NO_TRANS", "LEFT"),
                array("MASTER_KABUPATEN K", "K.KD_KABUPATEN=TP.KD_KABUPATEN", "LEFT"),
                array("TRANS_PRO_GC_LEASING TL", "TL.NO_TRANS=TP.ID", "LEFT")
            ),
            'field' => 'TRANS_PRO_GC_DETAIL.NO_TRANS, TRANS_PRO_GC_DETAIL.ROW_STATUS, TRANS_PRO_GC_DETAIL.KD_TYPEMOTOR, TRANS_PRO_GC_DETAIL.QTY, TRANS_PRO_GC_DETAIL.S_AHM, TRANS_PRO_GC_DETAIL.S_MD, TRANS_PRO_GC_DETAIL.S_SD, TRANS_PRO_GC_DETAIL.SK_FINANCE, TRANS_PRO_GC_DETAIL
            .SC_AHM, TRANS_PRO_GC_DETAIL.SC_MD, TRANS_PRO_GC_DETAIL.SC_SD, TRANS_PRO_GC_DETAIL.HARGA_KONTRAK, TRANS_PRO_GC_DETAIL.FEE, TRANS_PRO_GC_DETAIL.PENGURUSAN_STNK, TRANS_PRO_GC_DETAIL.PENGURUSAN_BPKB, TP.KD_DEALER, TP.NO_TRANS, TP.DESC_PROGRAM, TP.NO_PO_PERUSAHAAN, TP.START_DATE,TP.END_DATE,TP.TYPE,TL.KD_LEASING, K.NAMA_KABUPATEN',
            "custom" => "TRANS_PRO_GC_DETAIL.NO_TRANS='" . $id . "' AND TRANS_PRO_GC_DETAIL.ROW_STATUS >= 0"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param));
        return $data;
    }

    public function createfile_udpgc($id) {
        $this->auth->validate_authen('setup/proposal_gc');
        $data = array();
        $param_leasing = array(
            "custom" => "TRANS_PRO_GC_LEASING.NO_TRANS='" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_leasing", $param_leasing));
        $param_tipe = array(
            "custom" => "TRANS_PRO_GC_DETAIL.NO_TRANS='" . $id . "'",
        );
        $data["tipe"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param_tipe));
        if ($data["list"]->totaldata == 0 || $data["tipe"]->totaldata == 0) {
            echo "<script>alert('Data Leasing atau Tipe Motor masih kosong!');history.go(-1);</script>";
        } else {
            $data = $this->download_udpgc($id);
            $namafile = "";
            $isifile = "";
            foreach ($data->message as $key => $row) {
                $namafile = $this->session->userdata("kd_maindealer") . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".UDPGC"; //
                $isifile .= $row->NO_TRANS . ";";
                $isifile .= $row->DESC_PROGRAM . ";";
                $isifile .= $row->NO_PO_PERUSAHAAN . ";";
                $isifile .= str_replace("/", "-", tglFromSql($row->START_DATE)) . ";";
                $isifile .= str_replace("/", "-", tglFromSql($row->END_DATE)) . ";";
                $isifile .= substr($row->TYPE, 0, 1) . ";";
                $isifile .= $row->KD_TYPEMOTOR . ";";
                $isifile .= round($row->QTY) . ";";
                $isifile .= round($row->S_AHM) . ";";
                $isifile .= round($row->S_MD) . ";";
                $isifile .= round($row->S_SD) . ";";
                $isifile .= round($row->SK_FINANCE) . ";";
                $isifile .= round($row->SC_AHM) . ";";
                $isifile .= round($row->SC_MD) . ";";
                $isifile .= round($row->SC_SD) . ";";
                $isifile .= round($row->HARGA_KONTRAK) . ";";
                $isifile .= round($row->FEE) . ";";
                $isifile .= round($row->PENGURUSAN_STNK) . ";";
                $isifile .= round($row->PENGURUSAN_BPKB) . ";";
                $isifile .= $row->NAMA_KABUPATEN . ";";
                $isifile .= $row->KD_LEASING . ";";
                $isifile .= ";" . PHP_EOL;
            }
            $param = array(
                'id' => $id,
                'status_download' => 1
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/pro_gc", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->load->helper("download");
            force_download($namafile, $isifile);
            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
        }
    }

    public function proposal_gc_old() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_PRO_GC_DETAIL.KD_DEALER", "LEFT"),
                array("MASTER_GC MG", "MG.KD_TYPEMOTOR=TRANS_PRO_GC_DETAIL.KD_TYPEMOTOR", "LEFT")
            ),
            'field' => "TRANS_PRO_GC_DETAIL.*, MG.KD_TYPEMOTOR,MG.KD_GC",
            'orderby' => 'TRANS_PRO_GC_DETAIL.ID desc',
            "custom" => "TRANS_PRO_GC_DETAIL.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["groupcustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer"));
        $data["gc"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc"));
        $data["company_finance"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["list"] = json_decode($this->curl->simple_get($this->API . "/api/transaksi/pro_gc", $param));
        $param["groupby_text"] = "NO_TRANS";
        if ($this->input->get("n")) {
            $param = array("no_trans" => $this->input->get("n"));
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param));
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param));
        }
        $this->template->site('master_service/proposal_gc/view', $data); //, $data
    }

    public function get_gc_new() {
        $param = array(
            'kd_typemotor' => $this->input->get('kd_typemotor'),
            'kd_gc' => $this->input->get('kd_gc')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/gc", $param));
        //var_dump($data);exit;
        $this->output->set_output(json_encode($data));
    }

    public function get_gc() {
        $start_dates = ($this->input->get('start_date')) ? tglToSql($this->input->get('start_date')) : date('Y-m-d');
        $end_dates = ($this->input->get('end_date')) ? tglToSql($this->input->get('end_date')) : date('Y-m-d');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));
        $param = array(
            'start_date' => tglToSql($this->input->get('start_date')),
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/gc", $param));
        $this->output->set_output(json_encode($data));
    }

    /**
     * [simpan_header description]
     * @param  [type] $pohotline [description]
     * @return [type]            [description]
     */
    function simpan_header() {
        $param = array();
        $notrans = ($this->input->post("no_trans")) ? $this->input->post("no_trans") : $this->notrans_progc();
        $param = array(
            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
            'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
            'no_trans' => $notrans,
            'kd_gc' => $this->input->post("kd_gc"),
            'desc_program' => $this->input->post('desc_program'),
            'kd_typemotor' => $this->input->post("kd_typemotor"),
            'start_date' => $this->input->post("start_date"),
            'end_date' => $this->input->post("end_date"),
            'no_po_perusahaan' => $this->input->post('no_po_perusahaan'),
            'kd_kabupaten' => $this->input->post('kd_kabupaten'),
            'type' => $this->input->post('type'),
            'kd_leasing' => $this->input->post('kd_leasing'),
            'created_by' => $this->session->userdata("user_id"),
        );
        $hasil = ($this->curl->simple_post(API_URL . "/api/transaksi/pro_gc", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if ($hasil) {
            $hasile = json_decode($hasil);
            if ($hasile->recordexists) {
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL . "/api/transaksi/pro_gc", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            $hasil = $this->simpan_detail($notrans);
        }
        // echo json_encode(json_decode($hasil));
        $this->data_output($hasil, 'post', base_url('setup/proposal_gc?n=' . $notrans));
    }

    /**
     * [simpan_detail description]
     * @param  [type] $pohotline [description]
     * @return [type]            [description]
     */
    function simpan_detail($notrans) {
        $details = json_decode($this->input->post('detail'), true);
        for ($i = 0; $i < count($details); $i++) {
            $param = array(
                'no_trans' => $notrans,
                //'no_pro'            => $details[$i]["no_pro"],
                'kd_typemotor' => $details[$i]["kd_typemotor"],
                'qty' => ($details[$i]["qty"]) ? $details[$i]["qty"] : "0",
                's_ahm' => ($details[$i]["s_ahm"]) ? $details[$i]["s_ahm"] : "0",
                's_md' => ($details[$i]["s_md"]) ? $details[$i]["s_md"] : "0",
                's_sd' => ($details[$i]["s_sd"]) ? $details[$i]["s_sd"] : "0",
                'sk_finance' => ($details[$i]["sk_finance"]) ? $details[$i]["sk_finance"] : "0",
                'sc_ahm' => ($details[$i]["sc_ahm"]) ? $details[$i]["sc_ahm"] : "0",
                'sc_md' => ($details[$i]["sc_md"]) ? $details[$i]["sc_md"] : "0",
                'sc_sd' => ($details[$i]["sc_sd"]) ? $details[$i]["sc_sd"] : "0",
                'harga_kontrak' => ($details[$i]["harga_kontrak"]) ? $details[$i]["harga_kontrak"] : "0",
                'fee' => ($details[$i]["fee"]) ? $details[$i]["fee"] : "0",
                'pengurusan_stnk' => ($details[$i]["pengurusan_stnk"]) ? $details[$i]["pengurusan_stnk"] : "0",
                'pengurusan_bpkb' => ($details[$i]["pengurusan_bpkb"]) ? $details[$i]["pengurusan_bpkb"] : "0",
                'created_by' => $this->session->userdata("user_id"),
                'groupby' => TRUE
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/transaksi/pro_gc_detail
                ", $param, array(CURLOPT_BUFFERSIZE => 10)));
            //var_dump($hasil);print_r($param);exit();
            if ($hasil) {
                $hasile = json_decode($hasil);
                if ($hasile->recordexists == true) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = ($this->curl->simple_put(API_URL . "/api/transaksi/pro_gc_detail
                        ", $param, array(CURLOPT_BUFFERSIZE => 10)));
                }
            }
        }
        return $hasil;
    }

    /**
     * [notrans_progc description]
     * @param  [type] $debug [description]
     * @return [type]        [description]
     */
    function notrans_progc($debug = null, $kode = 'GC') {
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => $kode,
            'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'), //  substr($this->input->post('tgl_trans'),6,4)
            'limit' => 1,
            'offset' => 0
        );
        $bulan_kirim = date('m'); // $this->input->post('bulan_kirim');
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomorpo == 0) {
            $nopo = $kode . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = $kode . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
        }
        if ($debug) {
            echo $nopo;
        } else {
            return $nopo;
        }
    }

    public function delete_gc_detail($id = null) {
        $param = array(
            'id' => ($id) ? $id : 0, // $this->input->get("id"),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/pro_gc_detail", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/proposal_gc')
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

    public function cetak_pgc() {
        $this->load->library('dompdf_gen');
        $data = array();
        $paramcustomer = array();
        $param = array(
            //'custom' => $this>input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            //'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE)
                //'orderby' => 'ID DESC',
                //'limit' => 15
        );
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc_detail", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        // var_dump($paramcustomer);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        $html = $this->load->view('master_service/proposal_gc/cetak_proposal', $data, true);
        $filename = 'report_' . time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'landscape');
    }

    public function createfile_proposalgc() {
        $data = array();
        $data = $this->proposal_gc();
        $namafile = "";
        $isifile = "";
        /* $n = 0; */
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $row) {
                    $n++;
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALER . "-" . date('ymdHis') . "-" . str_replace("-", "", $row->START_DATE) . ".UDPGC";
                    $isifile .= $row->KD_DEALER . ";";
                    $isifile .= $row->KD_MASTERGC . ";";
                    $isifile .= $row->NO_PRO . ";";
                    $isifile .= $row->DESC_PROGRAM . ";";
                    $isifile .= $row->TYPE . ";"; //detail
                    $isifile .= str_replace("/", "", $row->START_DATE) . ";";
                    $isifile .= str_replace("/", "", $row->END_DATE) . ";";
                    $isifile .= $row->NO_PO_PERUSAHAAN . ";"; //detail
                    $isifile .= $row->KD_KABUPATEN . ";"; //detail
                }
            }
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }

    public function cetak_proposalgc() {
        $param_gc = array(
            "field" => "TRANS_PRO_GC.*"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param_gc));
        var_dump($param_gc);
        exit();
        $this->load->view('master_service/proposal_gc/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function proposalgc_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_PRO;
            $data_message[1][$key] = $message->KD_MASTERGC;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    public function getNOPRO() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kd_maindealer='" . $this->session->userdata('kd_maindealer') . "' "
        );
        $bulan = date('m');
        $tahun = date('Y');
        $data = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/pro_gc", $param));
        $number = str_pad(($data->totaldata) + 1, 6, '0', STR_PAD_LEFT);
        return "NP" . str_pad($this->session->userdata("kd_maindealer"), 3, 0, STR_PAD_LEFT) . $tahun . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . $number;
    }

    /**
     * [jenisreceiving description]
     * @return [type] [description]
     */
    public function jenisreceiving() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'SETUP_JENISRECEIVING.*',
            'orderby' => 'SETUP_JENISRECEIVING.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisreceiving", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/jenisreceiving', $data);
    }

    public function add_jenisreceiving() {
        $this->auth->validate_authen('setup/jenisreceiving');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisreceiving"));
        $this->load->view('form_tambah/add_jenisreceiving');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_jenisreceiving_simpan() {
        $this->form_validation->set_rules('kd_jenisreceiving', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jenisreceiving', 'Nama Jenis Penerimaan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenisreceiving' => $this->input->post("kd_jenisreceiving"),
                'nama_jenisreceiving' => $this->input->post("nama_jenisreceiving"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/jenisreceiving", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/jenisreceiving'));
        }
    }

    public function edit_jenisreceiving($kd_jenisreceiving, $row_status) {
        $this->auth->validate_authen('setup/jenisreceiving');
        $param = array(
            'kd_jenisreceiving' => $kd_jenisreceiving,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisreceiving", $param));
        $this->load->view('form_edit/edit_jenisreceiving', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_jenisreceiving($id) {
        $this->form_validation->set_rules('kd_jenisreceiving', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_jenisreceiving', 'Nama Jenis Receiving', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenisreceiving' => html_escape($this->input->post("kd_jenisreceiving")),
                'nama_jenisreceiving' => html_escape($this->input->post("nama_jenisreceiving")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/jenisreceiving", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_jenisreceiving($kd_jenisreceiving) {
        $param = array(
            'kd_jenisreceiving' => $kd_jenisreceiving,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/jenisreceiving", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/jenisreceiving')
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

    public function jenisreceiving_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisreceiving"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISRECEIVING;
            $data_message[1][$key] = $message->NAMA_JENISRECEIVING;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [jenispergerakan description]
     * @return [type] [description]
     */
    public function jenispergerakan() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'SETUP_JENISPERGERAKAN.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispergerakan", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/jenispergerakan', $data);
    }

    public function add_jenispergerakan() {
        $this->auth->validate_authen('setup/jenispergerakan');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispergerakan"));
        $this->load->view('form_tambah/add_jenispergerakan');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_jenispergerakan_simpan() {
        $this->form_validation->set_rules('kd_jenispergerakan', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jenispergerakan', 'Nama Jenis Pergerakan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenispergerakan' => $this->input->post("kd_jenispergerakan"),
                'nama_jenispergerakan' => $this->input->post("nama_jenispergerakan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/jenispergerakan", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('Setup/jenispergerakan'));
        }
    }

    public function edit_jenispergerakan($kd_jenispergerakan, $row_status) {
        $this->auth->validate_authen('setup/jenispergerakan');
        $param = array(
            'kd_jenispergerakan' => $kd_jenispergerakan,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispergerakan", $param));
        $this->load->view('form_edit/edit_jenispergerakan', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_jenispergerakan($id) {
        $this->form_validation->set_rules('kd_jenispergerakan', 'Koder', 'required|trim');
        $this->form_validation->set_rules('nama_jenispergerakan', 'Nama Jenis Pergerakan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenispergerakan' => html_escape($this->input->post("kd_jenispergerakan")),
                'nama_jenispergerakan' => html_escape($this->input->post("nama_jenispergerakan")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/jenispergerakan", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_jenispergerakan($kd_jenispergerakan) {
        $param = array(
            'kd_jenispergerakan' => $kd_jenispergerakan,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/jenispergerakan", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/jenispergerakan')
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

    public function jenispergerakan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispergerakan"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISPERGERAKAN;
            $data_message[1][$key] = $message->NAMA_JENISPERGERAKAN;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [jeniscustomer description]
     * @return [type] [description]
     */
    public function jeniscustomer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'SETUP_JENISCUSTOMER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/jeniscustomer', $data);
    }

    public function add_jeniscustomer() {
        $this->auth->validate_authen('setup/jeniscustomer');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer"));
        $this->load->view('form_tambah/add_jeniscustomer');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_jeniscustomer_simpan() {
        $this->form_validation->set_rules('kd_jeniscustomer', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jeniscustomer', 'Nama Jenis Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jeniscustomer' => $this->input->post("kd_jeniscustomer"),
                'nama_jeniscustomer' => $this->input->post("nama_jeniscustomer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/jeniscustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/jeniscustomer'));
        }
    }

    public function edit_jeniscustomer($kd_jeniscustomer, $row_status) {
        $this->auth->validate_authen('setup/jeniscustomer');
        $param = array(
            'kd_jeniscustomer' => $kd_jeniscustomer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer", $param));
        $this->load->view('form_edit/edit_jeniscustomer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_jeniscustomer($id) {
        $this->form_validation->set_rules('kd_jeniscustomer', 'Koder', 'required|trim');
        $this->form_validation->set_rules('nama_jeniscustomer', 'Nama Jenis Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jeniscustomer' => html_escape($this->input->post("kd_jeniscustomer")),
                'nama_jeniscustomer' => html_escape($this->input->post("nama_jeniscustomer")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/jeniscustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_jeniscustomer($kd_jeniscustomer) {
        $param = array(
            'kd_jeniscustomer' => $kd_jeniscustomer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/jeniscustomer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/jeniscustomer')
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

    public function jeniscustomer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISCUSTOMER;
            $data_message[1][$key] = $message->NAMA_JENISCUSTOMER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [typecustomer description]
     * @return [type] [description]
     */
    public function typecustomer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'SETUP_TYPECUSTOMER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/typecustomer', $data);
    }

    public function add_typecustomer() {
        $this->auth->validate_authen('setup/typecustomer');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        $this->load->view('form_tambah/add_typecustomer');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_typecustomer_simpan() {
        $this->form_validation->set_rules('kd_typecustomer', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_typecustomer', 'Nama Tipe Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_typecustomer' => $this->input->post("kd_typecustomer"),
                'nama_typecustomer' => $this->input->post("nama_typecustomer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/typecustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('Setup/typecustomer'));
        }
    }

    public function edit_typecustomer($kd_typecustomer, $row_status) {
        $this->auth->validate_authen('setup/typecustomer');
        $param = array(
            'kd_typecustomer' => $kd_typecustomer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer", $param));
        $this->load->view('form_edit/edit_typecustomer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_typecustomer($id) {
        $this->form_validation->set_rules('kd_typecustomer', 'Koder', 'required|trim');
        $this->form_validation->set_rules('nama_typecustomer', 'Nama Tipe Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_typecustomer' => html_escape($this->input->post("kd_typecustomer")),
                'nama_typecustomer' => html_escape($this->input->post("nama_typecustomer")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/typecustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_typecustomer($kd_typecustomer) {
        $param = array(
            'kd_typecustomer' => $kd_typecustomer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/typecustomer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/typecustomer')
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

    public function typecustomer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TYPECUSTOMER;
            $data_message[1][$key] = $message->NAMA_TYPECUSTOMER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [jenispembayaran description]
     * @return [type] [description]
     */
    public function jenispembayaran() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'SETUP_JENISPEMBAYARAN.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispembayaran", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/jenispembayaran', $data);
    }

    public function add_jenispembayaran() {
        $this->auth->validate_authen('setup/jenispembayaran');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispembayaran"));
        $this->load->view('form_tambah/add_jenispembayaran');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_jenispembayaran_simpan() {
        $this->form_validation->set_rules('kd_jenispembayaran', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jenispembayaran', 'Nama Jenis Pembayaran', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenispembayaran' => $this->input->post("kd_jenispembayaran"),
                'nama_jenispembayaran' => $this->input->post("nama_jenispembayaran"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/jenispembayaran", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('Setup/jenispembayaran'));
        }
    }

    public function edit_jenispembayaran($kd_jenispembayaran, $row_status) {
        $this->auth->validate_authen('setup/jenispembayaran');
        $param = array(
            'kd_jenispembayaran' => $kd_jenispembayaran,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispembayaran", $param));
        $this->load->view('form_edit/edit_jenispembayaran', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_jenispembayaran($id) {
        $this->form_validation->set_rules('kd_jenispembayaran', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_jenispembayaran', 'Nama Jenis Pembayaran', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenispembayaran' => html_escape($this->input->post("kd_jenispembayaran")),
                'nama_jenispembayaran' => html_escape($this->input->post("nama_jenispembayaran")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/jenispembayaran", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_jenispembayaran($kd_jenispembayaran) {
        $param = array(
            'kd_jenispembayaran' => $kd_jenispembayaran,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/jenispembayaran", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/jenispembayaran')
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

    public function jenispembayaran_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenispembayaran"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISPEMBAYARAN;
            $data_message[1][$key] = $message->NAMA_JENISPEMBAYARAN;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [jenisorder description]
     * @return [type] [description]
     */
    public function jenisorder() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_JENISORDER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisorder", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/jenisorder', $data);
    }

    public function add_jenisorder() {
        $this->auth->validate_authen('setup/jenisorder');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisorder"));
        $this->load->view('form_tambah/add_jenisorder');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_jenisorder_simpan() {
        $this->form_validation->set_rules('kd_jenisorder', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jenisorder', 'Nama Jenis Pembayaran', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenisorder' => $this->input->post("kd_jenisorder"),
                'nama_jenisorder' => $this->input->post("nama_jenisorder"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/jenisorder", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('Setup/jenisorder'));
        }
    }

    public function edit_jenisorder($kd_jenisorder, $row_status) {
        $this->auth->validate_authen('setup/jenisorder');
        $param = array(
            'kd_jenisorder' => $kd_jenisorder,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisorder", $param));
        $this->load->view('form_edit/edit_jenisorder', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_jenisorder($id) {
        $this->form_validation->set_rules('kd_jenisorder', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_jenisorder', 'Nama Jenis Order', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenisorder' => html_escape($this->input->post("kd_jenisorder")),
                'nama_jenisorder' => html_escape($this->input->post("nama_jenisorder")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/jenisorder", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_jenisorder($kd_jenisorder) {
        $param = array(
            'kd_jenisorder' => $kd_jenisorder,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/jenisorder", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/jenisorder')
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

    public function jenisorder_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jenisorder"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISORDER;
            $data_message[1][$key] = $message->NAMA_JENISORDER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [gender description]
     * @return [type] [description]
     */
    public function gender() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/Master_General/jeniskelamin", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/gender', $data);
    }

    public function add_gender() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $this->load->view('form_tambah/add_gender');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_gender_simpan() {
        $this->form_validation->set_rules('kd_gender', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_gender', 'Nama Jenis Pembayaran', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_gender' => $this->input->post("kd_gender"),
                'nama_gender' => $this->input->post("nama_gender"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/jeniskelamin", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('Setup/gender'));
        }
    }

    public function edit_gender($kd_gender) {
        $param = array(
            'kd_gender' => $kd_gender
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelaminr", $param));
        $this->load->view('form_edit/edit_gender', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_gender($id) {
        $this->form_validation->set_rules('kd_gender', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_gender', 'Nama Jenis Kelamin', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_gender' => html_escape($this->input->post("kd_gender")),
                'nama_gender' => html_escape($this->input->post("nama_gender")),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/jeniskelamin", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_gender($kd_gender) {
        $param = array(
            'kd_gender' => $kd_gender,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/jeniskelamin", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/gender')
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

    public function gender_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_GENDER;
            $data_message[1][$key] = $message->NAMA_GENDER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [diskon description]
     * @return [type] [description]
     */
    public function diskon() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'kd_jeniscustomer' => $this->input->get('kd_jeniscustomer'),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("SETUP_JENISCUSTOMER", "SETUP_JENISCUSTOMER.KD_JENISCUSTOMER=SETUP_DISKON.KD_JENISCUSTOMER", "LEFT"),
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=SETUP_DISKON.KD_DEALER", "LEFT"),
            ),
            'orderby' => 'SETUP_DISKON.ID desc',
            "custom" => "SETUP_DISKON.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/diskon", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/diskon', $data);
    }

    public function add_diskon() {
        $this->auth->validate_authen('setup/diskon');
        $data["jeniscustomers"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer"));
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('form_tambah/add_diskon', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_diskon_simpan() {
        $this->form_validation->set_rules('kd_diskon', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_diskon', 'Nama Diskon', 'required|trim');
        $this->form_validation->set_rules('kd_jeniscustomer', 'Jenis Customer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_diskon' => html_escape($this->input->post("kd_diskon")),
                'nama_diskon' => html_escape($this->input->post("nama_diskon")),
                'tipe_diskon' => $this->input->post("tipe_diskon"),
                'amount' => $this->input->post("amount"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_jeniscustomer' => $this->input->post("kd_jeniscustomer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/diskon", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/diskon'));
        }
    }

    public function edit_diskon($kd_diskon, $row_status) {
        $this->auth->validate_authen('setup/diskon');
        $param = array(
            'kd_diskon' => $kd_diskon,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/diskon", $param));
        $data["jeniscustomers"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer"));
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('form_edit/edit_diskon', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_diskon($id) {
        $this->form_validation->set_rules('kd_diskon', 'Kode Diskon', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_diskon', 'Nama Diskon', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_diskon' => html_escape($this->input->post("kd_diskon")),
                'nama_diskon' => html_escape($this->input->post("nama_diskon")),
                'tipe_diskon' => html_escape($this->input->post("tipe_diskon")),
                'amount' => html_escape($this->input->post("amount")),
                'start_date' => html_escape($this->input->post("start_date")),
                'end_date' => html_escape($this->input->post("end_date")),
                'kd_jeniscustomer' => html_escape($this->input->post("kd_jeniscustomer")),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/diskon", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_diskon($kd_diskon) {
        $param = array(
            'kd_diskon' => $kd_diskon,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/diskon", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('Setup/diskon')
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

    public function diskon_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/diskon"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DISKON;
            $data_message[1][$key] = $message->NAMA_DISKON;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [diskonpart description]
     * @return [type] [description]
     */
    public function diskonpart($onlyData = null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("SETUP_JENISCUSTOMER", "SETUP_JENISCUSTOMER.KD_JENISCUSTOMER=SETUP_DISKON_PART.KD_JENISCUSTOMER", "LEFT"),
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=SETUP_DISKON_PART.KD_DEALER", "LEFT"),
                array("MASTER_PART", "MASTER_PART.PART_NUMBER=SETUP_DISKON_PART.PART_NUMBER", "LEFT")
            ),
            'field' => 'SETUP_DISKON_PART.*, MASTER_DEALER.NAMA_DEALER, MASTER_PART.PART_DESKRIPSI, SETUP_JENISCUSTOMER.NAMA_JENISCUSTOMER',
            'orderby' => 'SETUP_DISKON_PART.ID desc',
            "custom" => "SETUP_DISKON_PART.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        if ($onlyData == "1") {
            $param = array(
                'jointable' => array(
                    array("SETUP_JENISCUSTOMER", "SETUP_JENISCUSTOMER.KD_JENISCUSTOMER=SETUP_DISKON_PART.KD_JENISCUSTOMER", "LEFT"),
                    array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=SETUP_DISKON_PART.KD_DEALER", "LEFT"),
                    array("MASTER_PART", "MASTER_PART.PART_NUMBER=SETUP_DISKON_PART.PART_NUMBER", "LEFT")
                ),
                'field' => 'SETUP_DISKON_PART.*, MASTER_DEALER.NAMA_DEALER, MASTER_PART.PART_DESKRIPSI, SETUP_JENISCUSTOMER.NAMA_JENISCUSTOMER',
                'orderby' => 'SETUP_DISKON_PART.ID desc',
                "custom" => "SETUP_DISKON_PART.KD_DEALER='" . $this->session->userdata('kd_dealer') . "' AND START_DATE <= GETDATE() AND END_DATE >= GETDATE()"
            );
        }
        if ($this->input->get("part_number")) {
            $param["part_number"] = $this->input->get("part_number");
        }
        if ($this->input->get("tp_cus")) {
            $param["type_customer"] = $this->input->get("tp_cus");
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/diskon_part", $param));
        if ($onlyData == "1") {
            if ($data["list"]) {
                if ($data["list"]->totaldata > 0) {
                    echo json_encode($data["list"]->message);
                }
            }
        } else {
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('setup/diskon_part', $data);
        }
    }

    public function add_diskonpart() {
        $this->auth->validate_authen('setup/diskon');
        $data["jeniscustomers"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $this->load->view('form_tambah/add_diskon_part', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_diskonpart_simpan() {
        $this->form_validation->set_rules('kd_jeniscustomer', 'Jenis Customer', 'required|trim');
        $this->form_validation->set_rules('part_number', 'Nomor Part', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $part_number = substr($this->input->post("part_number"), 0, strpos($this->input->post("part_number"), ' '));
            $param = array(
                'part_number' => $part_number,
                'tipe_diskon' => $this->input->post("tipe_diskon"),
                'amount' => $this->input->post("amount"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_jeniscustomer' => $this->input->post("kd_jeniscustomer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/diskon_part", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/diskonpart'));
        }
    }

    public function edit_diskonpart($id, $row_status) {
        $this->auth->validate_authen('setup/diskon');
        $param = array(
            "custom" => "id='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/diskon_part", $param));
        $data["jeniscustomers"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/jeniscustomer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $this->load->view('form_edit/edit_diskon_part', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_diskonpart($id) {
        $this->form_validation->set_rules('kd_jeniscustomer', 'Jenis Customer', 'required|trim');
        $this->form_validation->set_rules('part_number', 'Nomor Part', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if (strpos($this->input->post("part_number"), ' ') == false) {
                $part_number = $this->input->post("part_number");
            } else {
                $part_number = substr($this->input->post("part_number"), 0, strpos($this->input->post("part_number"), ' '));
            }
            //var_dump($part_number);exit;
            $param = array(
                'id' => $this->input->post("id"),
                'part_number' => $part_number,
                'tipe_diskon' => $this->input->post("tipe_diskon"),
                'amount' => $this->input->post("amount"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_jeniscustomer' => $this->input->post("kd_jeniscustomer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/diskon_part", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_diskonpart($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/diskon_part", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('Setup/diskonpart')
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

    public function diskonpart_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/diskon_part"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->KD_JENISCUSTOMER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [salesprogram description]
     * @return [type] [description]
     */
    public function salesprogram() {
        $data = array();
        if ($this->input->get('pilih') == 2) {
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => $this->input->get('row_status'),
                'kd_dealer' => $this->input->get('kd_dealer'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15
            );
            $data['pilih'] = 2;
            $param['orderby'] = 'KD_SALESPROGRAM DESC';
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogramleasing", $param));
        } elseif ($this->input->get('pilih') == 1) {
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => $this->input->get('row_status'),
                'kd_dealer' => $this->input->get('kd_dealer'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'orderby' => 'KD_SALESPROGRAM DESC'
            );
            $data['pilih'] = 1;
            $param['orderby'] = 'KD_SALESPROGRAM DESC';
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogramkota", $param));
        } else {
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => $this->input->get('row_status'),
                'kd_dealer' => $this->input->get('kd_dealer'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'orderby' => 'END_DATE DESC,ID,KD_SALESPROGRAM DESC'
            );
            $data['pilih'] = 0;
            $param['orderby'] = 'END_DATE DESC,ID,KD_SALESPROGRAM DESC';
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogram", $param));
        }
        //var_dump($data["list"]);exit();
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/salesprogram', $data);
    }

    public function detail_salesprogram($id = null, $kd_salesprogram = null) {
        $data = array();
        $param = array();
        if ($id) {
            $param = array(
                "custom" => "ID='" . $id . "'"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogram", $param));
        }
        if ($kd_salesprogram) {
            $param = array(
                'kd_salesprogram' => $kd_salesprogram,
                'kd_typemotor' => $id
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogram", $param));
            echo json_encode($data["list"]);
            exit();
        }
        $this->load->view('form_detail/detail_salesprogram', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_salesprogram() {
        ob_end_flush();
        ob_start();
        $this->auth->validate_authen('setup/salesprogram');
        ini_set('max_execution_time', 500);
        ini_set('memory_limit', '1024M');
        $mulai = microtime(true);
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["link"] = "list19a";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogram"), true);
        $options = array(
            CURLOPT_URL => 'http://36.66.232.220:8686/t10maiNDealer01/list19a',
            CURLOPT_RETURNTRANSFER => true, // return web page 
            CURLOPT_HEADER => false, // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING => "", // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER => true, // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect 
            CURLOPT_TIMEOUT => 120, // timeout on response 
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects 
            CURLOPT_POST => false
        );
        $cUrl = curl_init();
        curl_setopt_array($cUrl, $options);
        $datax = curl_exec($cUrl);
        $js = json_decode(json_decode(json_encode($datax)), true);
        $datasp = array();
        $dataws = array();
        $datane = array();
        //var_dump($datax);exit();
        //echo count($js);exit();
        if ($js) {
            foreach ($js as $row) {
                $sama = 0;
                $sd = date("Ym", strtotime($row["startdate"]));
                $th = date("Ym", strtotime($row["enddate"]));
                //echo $sd;exit();
                if (/* $sd <=date('Ym') && */$th >= date("Ym")) { //
                    $dataws[str_replace("-", "", $row["programid"]) . $row["kdtipe"]] = $row;
                    $hasil = array();
                    $param = array(
                        'kd_salesprogram' => $row["programid"],
                        'kd_typemotor' => $row["kdtipe"],
                        'end_date' => $row["enddate"]
                    );
                    $param['custom'] = "SK_AHM=" . $row["skahm"];
                    $param['custom'] .= " AND SK_MD=" . $row["skmd"];
                    $param['custom'] .= " AND SK_SD=" . $row["sksd"];
                    $param['custom'] .= " AND SK_FINANCE=" . $row["skfinance"];
                    $param['custom'] .= " AND SC_AHM=" . $row["scahm"];
                    $param['custom'] .= " AND SC_MD=" . $row["scmd"];
                    $param['custom'] .= " AND CB_AHM=" . $row["cbahm"];
                    $param['custom'] .= " AND CB_MD=" . $row["cbmd"];
                    $param['custom'] .= " AND CB_SD=" . $row["cbsd"];
                    $hasil = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogram", $param));
                    if ($hasil) {
                        if ($hasil->totaldata > 0) {
                            $datasp[] = ($hasil->message);
                        } else {
                            $datane[] = $row;
                        }
                    } else {
                        
                    }
                }
            }
        }
        $sama = 0;
        ob_flush();
        flush();
        $data["listmd"] = $datane; //$dataupdate;
        $data["jumlahdata"] = count($datasp);
        $data["selesai"] = microtime(true);
        $this->load->view('form_tambah/add_salesprogram', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_salesprogram_kota($debug = null) {
        // ob_end_flush();
        // ob_start();
        $this->auth->validate_authen('setup/salesprogram');
        ini_set('max_execution_time', 500);
        ini_set('memory_limit', '1024M');
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list20";
        //$listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $options = array(
            CURLOPT_URL => 'http://36.66.232.220:8686/t10maiNDealer01/list20',
            CURLOPT_RETURNTRANSFER => true, // return web page 
            CURLOPT_HEADER => false, // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING => "", // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER => true, // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect 
            CURLOPT_TIMEOUT => 120, // timeout on response 
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects 
            CURLOPT_POST => false
        );
        $cUrl = curl_init();
        curl_setopt_array($cUrl, $options);
        $datax = curl_exec($cUrl);
        $js = json_decode(json_decode(json_encode($datax)), true);
        $datasp = array();
        $dataws = array();
        $datane = array();
        if ($js) {
            foreach ($js as $row) {
                $dataws[str_replace("-", "", $row["programid"]) . $row["kdtipe"]] = $row;
                $hasil = array();
                $param = array(
                    'kd_salesprogram' => $row["programid"],
                    'kd_kabupaten' => $row["kota"]
                        // ,
                        // 'nama_salesprogram' => $row["programdesc"]
                );
                $hasil = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogramkota", $param));
                if ($hasil) {
                    if ($hasil->totaldata > 0) {
                        $datasp[] = $hasil->message;
                    } else {
                        $datane[] = $row;
                    }
                }
            }
        }
        //var_dump($datane);exit();
        ob_flush();
        flush();
        $data["listmd"] = $datane;
        $data["jumlahdata"] = count($datasp);
        $data["selesai"] = microtime(true);
        if ($debug) {
            var_dump($datane);
            exit();
        }
        $this->load->view('form_tambah/add_salesprogram_kota', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_salesprogram_leasing($debug = null) {
        ob_end_flush();
        ob_start();
        $this->auth->validate_authen('setup/salesprogram');
        ini_set('max_execution_time', 500);
        ini_set('memory_limit', '1024M');
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list21";
        $options = array(
            CURLOPT_URL => WS_URL . $param["link"],
            CURLOPT_RETURNTRANSFER => true, // return web page 
            CURLOPT_HEADER => false, // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING => "", // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER => true, // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect 
            CURLOPT_TIMEOUT => 120, // timeout on response 
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects 
            CURLOPT_POST => false
        );
        $cUrl = curl_init();
        curl_setopt_array($cUrl, $options);
        $datax = curl_exec($cUrl);
        $js = json_decode($datax, true); //json_decode(json_decode(json_encode($datax)), true);
        $datasp = array();
        $dataws = array();
        $datane = array();
        $timer_start = 0;
        $timer_end = 0;
        if ($js) {
            $timer_start = microtime(true);
            foreach ($js as $row) {
                $dataws[str_replace("-", "", $row["programid"]) . $row["kdtipe"]] = $row;
                $hasil = array();
                $param = array(
                    'kd_salesprogram' => $row["programid"],
                    'kd_leasing' => $row["kdlsng"],
                    //'nama_salesprogram' => $row["programdesc"],
                    'nilai_leasing' => $row["nilail"],
                    'klasifikasi_leasing' => $row["klasifikasi"]
                );
                $hasil = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogramleasing", $param));
                if ($hasil) {
                    if ($hasil->totaldata > 0) {
                        $datasp[] = $hasil->message;
                    } else {
                        $datane[] = $row;
                    }
                }
                $timer_end = microtime(true);
                if ((($timer_end - $timer_start) + 20) == 120) {
                    break;
                }
                /* if((count($datasp)+count($datane))==50){
                  break;
                  } */
            }
        }
        //var_dump($datane);exit();
        ob_flush();
        flush();
        $data["listmd"] = $datane;
        $data["jumlahdata"] = count($datasp);
        $data["selesai"] = microtime(true);
        if ($debug) {
            echo ($timer_end - $timer_start);
            var_dump($datane);
            exit();
        }
        $this->load->view('form_tambah/add_salesprogram_leasing', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_salesprogram() {
        ob_end_flush();
        ob_start();
        //$this->auth->validate_authen('setup/salesprogram');
        ini_set('max_execution_time', 500);
        ini_set('memory_limit', '1024M');
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_salesprogram' => $data[0][$i]["programid"],
                'nama_salesprogram' => trim(str_replace("'", "", $data[0][$i]["programdesc"])),
                'tipe_salesprogram' => $data[0][$i]["type"],
                'kd_salesprogramahm' => $data[0][$i]["spid"],
                'no_suratsp' => $data[0][$i]["localspid"],
                'salesprogram_khusus' => $data[0][$i]["khusus"],
                'salesprogram_gift' => $data[0][$i]["gift"],
                'salesprogram_cabang' => $data[0][$i]["cabang"],
                'start_date' => $data[0][$i]["startdate"],
                'pot_start' => $data[0][$i]["potstart"],
                'pot_end' => $data[0][$i]["potend"],
                'ssu_start' => $data[0][$i]["ssustart"],
                'ssu_end' => $data[0][$i]["ssuend"],
                'end_date' => $data[0][$i]["enddate"],
                'end_claim' => $data[0][$i]["endclaim"],
                'kd_typemotor' => $data[0][$i]["kdtipe"],
                'qty' => ($data[0][$i]["qty"]) ? $data[0][$i]["qty"] : "0",
                'sk_ahm' => ($data[0][$i]["skahm"]) ? $data[0][$i]["skahm"] : "0",
                'sk_md' => ($data[0][$i]["skmd"]) ? $data[0][$i]["skmd"] : "0",
                'sk_sd' => ($data[0][$i]["sksd"]) ? $data[0][$i]["sksd"] : "0",
                'sk_finance' => ($data[0][$i]["skfinance"]) ? $data[0][$i]["skfinance"] : "0",
                'sc_ahm' => ($data[0][$i]["scahm"]) ? $data[0][$i]["scahm"] : "0",
                'sc_md' => ($data[0][$i]["scmd"]) ? $data[0][$i]["scmd"] : "0",
                'sc_sd' => ($data[0][$i]["scsd"]) ? $data[0][$i]["scsd"] : "0",
                'cb_ahm' => ($data[0][$i]["cbahm"]) ? $data[0][$i]["cbahm"] : "0",
                'cb_md' => ($data[0][$i]["cbmd"]) ? $data[0][$i]["cbmd"] : "0",
                'cb_sd' => ($data[0][$i]["cbsd"]) ? $data[0][$i]["cbsd"] : "0",
                'pot_faktur' => ($data[0][$i]["potfaktur"]) ? $data[0][$i]["potfaktur"] : "0",
                'cash_tempo' => $data[0][$i]["cashtempo"],
                'split_otr' => $data[0][$i]["splitotr"],
                'split_otr2' => $data[0][$i]["splitotr2"],
                'hadiah_langsung' => $data[0][$i]["hadiahlangsung"],
                'harga_kontrak' => ($data[0][$i]["hkontrak"]) ? $data[0][$i]["hkontrak"] : "0",
                'fee' => ($data[0][$i]["fee"]) ? $data[0][$i]["fee"] : "0",
                'pengurusan_stnk' => ($data[0][$i]["pstnk"]) ? $data[0][$i]["pstnk"] : "0",
                'pengurusan_bpkb' => ($data[0][$i]["pbpkb"]) ? $data[0][$i]["pbpkb"] : "0",
                'no_po' => $data[0][$i]["nopo"],
                'min_sk_sd' => ($data[0][$i]["minsksd"]) ? $data[0][$i]["minsksd"] : "0",
                'min_sc_sd' => ($data[0][$i]["minscsd"]) ? $data[0][$i]["minscsd"] : "0",
                'dp_otr' => ($data[0][$i]["dpotr"]) ? $data[0][$i]["dpotr"] : "0",
                'tambahan_finance' => ($data[0][$i]["tambfinance"]) ? $data[0][$i]["tambfinance"] : "0",
                'tambahan_md' => ($data[0][$i]["tambmd"]) ? $data[0][$i]["tambmd"] : "0",
                'tambahan_sd' => ($data[0][$i]["tambsd"]) ? $data[0][$i]["tambsd"] : "0",
                'tunda_faktur' => $data[0][$i]["tundafaktur"],
                'hadiah_langsung2' => $data[0][$i]["hadiahlangsung2"],
                'keterangan_hadiah' => $data[0][$i]["kethadiah"],
                'tambahan_ahm' => ($data[0][$i]["tambahm"]) ? $data[0][$i]["tambahm"] : "0",
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/setup/salesprogram", $param, array(CURLOPT_BUFFERSIZE => 100)));
            if (($i % 100) == 0) {
                sleep(1);
                continue;
            }
        }
        ob_flush();
        flush();
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function update_salesprogramkota() {
        ini_set('max_execution_time', 120);
        $param = array();
        $data = array();
        $result = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        /* print_r($data[0]);
          exit(); */
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_salesprogram' => $data[0][$i]["programid"],
                'nama_salesprogram' => str_replace("'", "", $data[0][$i]["programdesc"]),
                'kd_kabupaten' => str_replace("'", "", $data[0][$i]["kota"]),
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/setup/salesprogramkota", $param, array(CURLOPT_BUFFERSIZE => 10)));
            /* $result=json_decode($hasil);
              if($result->recordexists==true){
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil = ($this->curl->simple_putt(API_URL . "/api/setup/salesprogramkota", $param, array(CURLOPT_BUFFERSIZE => 10)));
              } */
            /* var_dump($hasil);
              exit(); */
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function update_salesprogramleasing() {
        ini_set('max_execution_time', 120);
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        /* print_r($data[0]);
          exit(); */
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_salesprogram' => $data[0][$i]["programid"],
                'nama_salesprogram' => rtrim(str_replace("'", "", $data[0][$i]["programdesc"])),
                'kd_leasing' => $data[0][$i]["kdlsng"],
                'nilai_leasing' => $data[0][$i]["nilail"],
                'klasifikasi_leasing' => $data[0][$i]["klasifikasi"],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/setup/salesprogramleasing", $param, array(CURLOPT_BUFFERSIZE => 10)));
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function salesprogram_typeahead() {
        if ($this->input->get('pilih') == 2) {
            $data['pilih'] = 2;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogramleasing", $param));
        } elseif ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogramkota", $param));
        } else {
            $data['pilih'] = 0;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/salesprogram", $param));
        }
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_SALESPROGRAM;
            $data_message[1][$key] = $message->NAMA_SALESPROGRAM;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [kupon description]
     * @return [type] [description]
     */
    public function saleskupon() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'oderby' => 'ID desc'
        );
        $data = array();
        if ($this->input->get('pilih') == 2) {
            $data['pilih'] = 2;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/saleskuponleasing", $param));
        } elseif ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/saleskuponkota", $param));
        } else {
            $data['pilih'] = 0;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kupon", $param));
        }
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->add_saleskupon_d();
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/saleskupon', $data);
    }

    public function add_saleskupon_d() {
        ini_set('max_execution_time', 120);
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        //$param["param"] =($this->input->get('kd_dealer'))? $this->input->get('kd_dealer'): $this->session->userdata('kd_dealer');
        $param["link"] = "list16";
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) : json_encode(array("status" => FALSE, "message" => "Bad request"));
        //echo count(json_decode($js,true))."--".count($js[0]);
        $js = (json_decode($js, true));
        //echo count($js);exit();
        $dealer = array();
        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $dealer[] = array(
                "programid" => $js[$i]["programid"],
                "programdesc" => $js[$i]["programdesc"],
                "startdate" => $js[$i]["startdate"],
                "enddate" => $js[$i]["enddate"],
                "endclaim" => $js[$i]["endclaim"],
                "noperk" => $js[$i]["noperk"],
                "nosub" => $js[$i]["nosub"],
                "kdtipe" => $js[$i]["kdtipe"],
                "nilai" => $js[$i]["nilai"],
                "kota" => $js[$i]["kota"],
                "kdlsng" => $js[$i]["kdlsng"],
                "top1" => $js[$i]["top1"],
                "top2" => $js[$i]["top2"]
            );
        }
        $url = API_URL . "/api/setup/saleskuponbatch";
        $ch = curl_init($url);
        $jsonDataEncoded = (base64_encode(json_encode($dealer)));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_exec($ch);
    }

    function add_saleskupon() {
        $path = PATH_DATA;
        ini_set('max_execution_time', 0);
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $dataupdate = json_decode(file_get_contents($path . '\saleskupon.json'), true);
        $totaldatas = 0;
        $js = $dataupdate;
        $dataupdatex = array();
        for ($x = 0; $x < count($js); $x++) {
            $dealerx = array(
                'kd_saleskupon' => trim($js[$x]['programid']),
                'kd_typemotor' => trim($js[$x]['kdtipe']),
                'end_date' => str_replace("-", "", $js[$x]['enddate']),
                'end_claim' => str_replace("-", "", $js[$x]['endclaim']),
                'nilai' => (double) ($js[$x]['nilai'])/* ,
                      'no_perkiraan' => $js[$x]['noperk'],
                      'no_subperkiraan' => $js[$x]['nosub'] */
            );
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/setup/kupon", $dealerx));
            if (isset($databaru)) {
                if ($databaru->totaldata == 0) {
                    $dataupdatex[$totaldatas] = $js[$x];
                    $totaldatas++;
                    /* echo $x;
                      print_r($dataupdatex);
                      print_r($databaru);exit(); */
                }
            } else {
                $dataupdatex[$totaldatas] = $js[$x];
                $totaldatas++;
            }
            //echo $x;
            if ($totaldatas == 20) {
                break;
            }
        }
        $data["listmd"] = $dataupdatex;
        //var_dump($data);exit();
        $this->load->view('form_tambah/add_saleskupon', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_saleskupon_kota() {
        $this->auth->validate_authen('setup/saleskupon');
        //ob_clean();
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list17";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/saleskuponkota"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $data["listmd"] = $js;
        //var_dump($data);exit();
        $this->load->view('form_tambah/add_saleskupon_kota', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_saleskupon_leasing() {
        $this->auth->validate_authen('setup/saleskupon');
        //ob_clean();
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list18";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/saleskuponleasing"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $data["listmd"] = $js;
        /* var_dump($js);
          exit(); */
        $this->load->view('form_tambah/add_saleskupon_leasing', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_saleskupon() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        $param = array();
        for ($i = 0; $i < count($data[0]); $i++) {
            $param[$i]['KD_SALESKUPON'] = $data[0][$i]["programid"];
            $param[$i]['NAMA_SALESKUPON'] = strtoupper(str_replace("'", "", (rtrim($data[0][$i]["programdesc"]))));
            $param[$i]['START_DATE'] = rtrim($data[0][$i]["startdate"]);
            $param[$i]['END_DATE'] = rtrim($data[0][$i]["enddate"]);
            $param[$i]['END_CLAIM'] = trim($data[0][$i]["endclaim"]);
            $param[$i]['NO_PERKIRAAN'] = rtrim($data[0][$i]["noperk"]);
            $param[$i]['NO_SUBPERKIRAAN'] = rtrim($data[0][$i]["nosub"]);
            $param[$i]['KD_TYPEMOTOR'] = rtrim($data[0][$i]["kdtipe"]);
            $param[$i]['TOP1'] = rtrim($data[0][$i]["top1"]);
            $param[$i]['TOP2'] = rtrim($data[0][$i]["top2"]);
            $param[$i]['NILAI'] = rtrim($data[0][$i]["nilai"]);
            $param[$i]['CREATED_BY'] = $this->session->userdata("user_id");
        }
        $paramsales = array(
            'query' => $this->Custom_model->simpan_saleskupon(json_encode($param))
        );
        $hasil = $this->curl->simple_post(API_URL . "/api/setup/kuponnew", $paramsales, array(CURLOPT_BUFFERSIZE => 100));
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => number_format($i, 0) . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function update_saleskuponkota() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_saleskupon' => $data[0][$i]["programid"],
                'nama_saleskupon' => $data[0][$i]["programdesc"],
                'kd_dealer' => $data[0][$i]["kota"],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/setup/saleskuponkota", $param, array(CURLOPT_BUFFERSIZE => 10)));
            $datar = json_decode($hasil);
            //if($hasil){
            if ($datar->recordexists == TRUE) {
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL . "/api/setup/saleskuponkota", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            //}
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => ' Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function update_saleskuponleasing() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        /* print_r($data[0]);
          exit(); */
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_saleskupon' => $data[0][$i]["programid"],
                'nama_saleskupon' => $data[0][$i]["programdesc"],
                'kd_leasing' => $data[0][$i]["kdlsng"],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/setup/saleskuponleasing", $param, array(CURLOPT_BUFFERSIZE => 10)));
            $datar = json_decode($hasil);
            //if($hasil){
            if ($datar->recordexists == TRUE) {
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL . "/api/setup/saleskuponleasing", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            //}
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => ' Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function saleskupon_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kupon"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_SALESKUPON;
            $data_message[1][$key] = $message->NAMA_SALESKUPON;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [makelar description]
     * @return [type] [description]
     */
    public function makelar() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=MASTER_MAKELAR.KD_DEALER", "LEFT")
            ),
            'field' => '*,MASTER_MAKELAR.ID as ID, MASTER_MAKELAR.ALAMAT as ALAMAT_MAKELAR, MASTER_MAKELAR.ROW_STATUS',
            'orderby' => 'MASTER_MAKELAR.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/makelar", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/makelar', $data);
    }

    public function add_makelar() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/makelar"));
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('form_tambah/add_makelar');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_makelar_simpan() {
        $this->form_validation->set_rules('nama_makelar', 'Nama Makelar', 'required|trim');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_makelar' => $this->getKDMakelar(),
                'nama_makelar' => $this->input->post("nama_makelar"),
                'no_hp' => $this->input->post("no_hp"),
                'alamat' => $this->input->post("alamat"),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/makelar", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/makelar'));
        }
    }

    public function getKDMakelar() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kd_dealer='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/setup/makelar", $param));
        $number = str_pad(($data->totaldata) + 1, 4, '0', STR_PAD_LEFT);
        return str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT) . "MK" . '-' . $number;
    }

    public function edit_makelar($kd_makelar) {
        $param = array(
            'kd_makelar' => $kd_makelar
        );
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/makelar", $param));
        $this->load->view('form_edit/edit_makelar', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_makelar($id) {
        $this->form_validation->set_rules('kd_makelar', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_makelar', 'Nama Jenis Order', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_makelar' => html_escape($this->input->post("kd_makelar")),
                'nama_makelar' => html_escape($this->input->post("nama_makelar")),
                'no_hp' => html_escape($this->input->post("no_hp")),
                'alamat' => html_escape($this->input->post("alamat")),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/makelar", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_makelar($kd_makelar) {
        $param = array(
            'kd_makelar' => $kd_makelar,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/makelar", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('Setup/makelar')
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

    public function makelar_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/makelar"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_MAKELAR;
            $data_message[1][$key] = $message->NAMA_MAKELAR;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [targetsf description]
     * @return [type] [description]
     */
    public function targetsf() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_SALESMAN as MS", "MS.KD_SALES=MASTER_TARGETSF.KD_SALES", "LEFT")
            ),
            'field' => 'MASTER_TARGETSF.*, MS.NAMA_SALES, MS.KD_DEALER',
            'orderby' => 'MASTER_TARGETSF.ID desc',
//            'groupby' => TRUE,
            "custom" => "MS.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targetsf", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_targetsf', $data);
    }

    public function add_targetsf() {
        //$this->auth->validate_authen('setup/targetsf');
        $data = array();
        $param = array(
            "custom" => "MASTER_SALESMAN.KD_DEALER='" . $this->session->userdata('kd_dealer') . "' AND MASTER_SALESMAN.STATUS_SALES='A'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targetsf"));
        $data["saless"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
        $this->load->view('form_tambah/add_targetsf', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_targetsf_simpan() {
        $this->form_validation->set_rules('kd_sales', 'Kode Salesman', 'required|trim');
        $this->form_validation->set_rules('target', 'Target', 'required|trim');
        //$this->form_validation->set_rules('start_date', 'Periode Mulai', 'required|trim');
        //$this->form_validation->set_rules('end_date', 'Periode Selesai', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_sales' => $this->input->post("kd_sales"),
                'start_date' => ('01/' . sprintf("%'.02d", $this->input->post("bulan")) . '/' . $this->input->post("tahun")),
                'end_date' => (date('t', strtotime(sprintf("%'.02d", $this->input->post("bulan")) . '/01/' . $this->input->post("tahun"))) . '/' . sprintf("%'.02d", $this->input->post("bulan")) . '/' . $this->input->post("tahun")),
                'target' => $this->input->post("target"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
//            var_dump($param);            exit();
            $hasil = $this->curl->simple_post(API_URL . "/api/master/targetsf", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            if ($this->input->post("target2") != '') {
                $param2 = array(
                    'kd_sales' => $this->input->post("kd_sales"),
                    'start_date' => ('01/' . sprintf("%'.02d", $this->input->post("bulan2")) . '/' . $this->input->post("tahun2")),
                    'end_date' => (date('t', strtotime(sprintf("%'.02d", $this->input->post("bulan2")) . '/01/' . $this->input->post("tahun2"))) . '/' . sprintf("%'.02d", $this->input->post("bulan2")) . '/' . $this->input->post("tahun2")),
                    'target' => $this->input->post("target2"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );
                $hasil2 = $this->curl->simple_post(API_URL . "/api/master/targetsf", $param2, array(CURLOPT_BUFFERSIZE => 10));
                $data = json_decode($hasil2);
            }
            if ($this->input->post("target3") != '') {
                $param3 = array(
                    'kd_sales' => $this->input->post("kd_sales"),
                    'start_date' => ('01/' . sprintf("%'.02d", $this->input->post("bulan3")) . '/' . $this->input->post("tahun3")),
                    'end_date' => (date('t', strtotime(sprintf("%'.02d", $this->input->post("bulan3")) . '/01/' . $this->input->post("tahun3"))) . '/' . sprintf("%'.02d", $this->input->post("bulan3")) . '/' . $this->input->post("tahun3")),
                    'target' => $this->input->post("target3"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );
                $hasil3 = $this->curl->simple_post(API_URL . "/api/master/targetsf", $param3, array(CURLOPT_BUFFERSIZE => 10));
                $data = json_decode($hasil3);
            }
//            var_dump($data);
//            exit();
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setuptargetsf'));
            if ($this->input->post("target2") != '') {
                $this->data_output($hasil2, 'post');
            }
            if ($this->input->post("target3") != '') {
                $this->data_output($hasil3, 'post');
            }
        }
    }

    public function edit_targetsf($id, $row_status) {
        $this->auth->validate_authen('setup/targetsf');
        $param = array(
            "custom" => "MASTER_TARGETSF.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targetsf", $param));
        $data["saless"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman"));
        $this->load->view('form_edit/edit_targetsf', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_targetsf($id) {
        $this->form_validation->set_rules('kd_sales', 'Kode Salesman', 'required|trim');
        $this->form_validation->set_rules('target', 'Target', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Periode Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Periode Selesai', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_sales' => $this->input->post("kd_sales"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'target' => $this->input->post("target"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/targetsf", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_targetsf($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/targetsf", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('setup/targetsf')
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

    public function targetsf_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targetsf"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_SALES;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [insentif description]
     * @return [type] [description]
     */
    public function insentif() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_P_CATEGORY_V as MC", "MC.KD_CATEGORY=MASTER_INSENTIF.KD_CATEGORY", "LEFT")
            ),
            'field' => 'MASTER_INSENTIF.*,MC.NAMA_CATEGORY',
            'orderby' => 'MASTER_INSENTIF.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/insentif", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_insentif', $data);
    }

    public function add_insentif() {
        $this->auth->validate_authen('setup/insentif');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/insentif"));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $data["category"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor"));
        $this->load->view('form_tambah/add_insentif', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_insentif_simpan() {
        //$this->form_validation->set_rules('kd_jenisreceiving', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        //$this->form_validation->set_rules('kd_motor', 'Kode Motor', 'required|trim');
        if ($this->input->post("cash") == null) {
            $cash = 0;
        } else {
            $cash = $this->input->post("cash");
        }
        if ($this->input->post("kredit") == null) {
            $kredit = 0;
        } else {
            $kredit = $this->input->post("kredit");
        }
        if ($this->input->post("khusus") == null) {
            $khusus = 0;
        } else {
            $khusus = $this->input->post("khusus");
        }
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $kategori = $this->input->post("kategori");
            if ($kategori == 'SWAT' || $kategori == 'WING' || $kategori == 'SC WING') {
                $kd_motor = $this->input->post("kd_motor");
                $kd_category = "";
            } elseif ($kategori == 'Reguler' || $kategori == 'Kepala Sales' || $kategori == 'Kepala Counter' || $kategori == 'SC Reguler') {
                $kd_motor = "";
                $kd_category = $this->input->post("kd_category");
            }
            $param = array(
                'kategori' => $this->input->post("kategori"),
                'kd_motor' => $kd_motor,
                'kd_category' => $kd_category,
                'cash' => $cash,
                'kredit' => $kredit,
                'khusus' => $khusus,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/master/insentif", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setuptargetsf'));
        }
    }

    public function edit_insentif($id, $row_status) {
        $this->auth->validate_authen('setup/insentif');
        $param = array(
            "custom" => "MASTER_INSENTIF.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/insentif", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $data["category"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor"));
        //var_dump($data["list"]);exit;
        $this->load->view('form_edit/edit_insentif', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_insentif($id) {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        if ($this->input->post("cash") == null) {
            $cash = 0;
        } else {
            $cash = $this->input->post("cash");
        }
        if ($this->input->post("kredit") == null) {
            $kredit = 0;
        } else {
            $kredit = $this->input->post("kredit");
        }
        if ($this->input->post("khusus") == null) {
            $khusus = 0;
        } else {
            $khusus = $this->input->post("khusus");
        }
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
                'kd_motor' => $this->input->post("kd_motor"),
                'kd_category' => $this->input->post("kd_category"),
                'cash' => $cash,
                'kredit' => $kredit,
                'khusus' => $khusus,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/insentif", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_insentif($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/insentif", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/insentif')
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

    public function insentif_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/insentif"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
            $data_message[1][$key] = $message->KD_MOTOR;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [gc description]
     * @return [type] [description]
     */
    public function gc() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'MASTER_GC.KD_GC desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_gc', $data);
    }

    public function add_gc() {
        $this->auth->validate_authen('setup/gc');
        $data = array();
        $param = array();
        $js = array();
        $param["param"] = date("Y");
        $param["link"] = "list40";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc"), true);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $data["listmd"] = $js;
        $this->load->view('form_tambah/add_gc', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_gc() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_gc' => $data[0][$i]["programid"],
                'nama_program' => str_replace("'", "", rtrim($data[0][$i]["programdc"])),
                'start_date' => $data[0][$i]["startdate"],
                'end_date' => $data[0][$i]["enddate"],
                'kd_typemotor' => $data[0][$i]["kdtipe"],
                's_ahm' => $data[0][$i]["sahm"],
                's_md' => $data[0][$i]["smd"],
                's_sd' => $data[0][$i]["ssd"],
                'lkpp_kalses' => $data[0][$i]["lkppkalsel"],
                'lkpp_kalteng' => $data[0][$i]["lkppkalteng"],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/master/gc", $param, array(CURLOPT_BUFFERSIZE => 10)));
            //var_dump($hasil);exit;
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . "Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    public function gc_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gc"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
            $data_message[1][$key] = $message->KD_MOTOR;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    function leasing_komposisi() {
        $data = array();
        $param = array();
        if ($this->session->userdata("nama_group") == 'Root') {
            $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        } else {
            $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $param = array('custom' => "KD_LEASING NOT IN ('CSH')");
        $data["fincom"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance", $param));
        $paramsales = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
            'jointable' => array(array("MASTER_COM_FINANCE MF", "MF.KD_LEASING=TRANS_SPK_LEASING_KOMPOSISI.KD_LEASING", "LEFT")),
            'field' => "TRANS_SPK_LEASING_KOMPOSISI.ID,TRANS_SPK_LEASING_KOMPOSISI.KD_LEASING,ISNULL(MF.NAMA_LEASING,'OTHERS LEASING')NAMA_LEASING,TARGET_LEASING,RANGKING_LEASING"
        );
        $where = "WHERE SL.KD_DEALER='" . $this->session->userdata("kd_dealer") . "' AND SL.TAHUN='" . date('Y') . "'";
        $paramsales = array(
            'query' => $this->Custom_model->Leasing_Achieve($where)
        );
        $data["prosensales"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/leasing_komposisi", $paramsales));
        $this->load->view('sales/survey_leasing', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    function simpan_komposisi() {
        $data = 0;
        $hasil = "";
        for ($i = 1; $i <= 4; $i++) {
            $param = array(
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
                'kd_leasing' => $this->input->post('kd_leasing_' . $i),
                'target_leasing' => $this->input->post('target_leasing_' . $i),
                'rangking' => $this->input->post('rangking_' . $i),
                'tahun' => ($this->input->post('tahun')) ? $this->input->post('tahun') : date('Y'),
                'created_by' => $this->session->userdata("user_id")
            );
            //print_r($param);exit();
            $hasil = json_decode($this->curl->simple_post(API_URL . "/api/spk/leasing_komposisi", $param, array(CURLOPT_BUFFERSIZE => 10)));
            if ($hasil) {
                $data++;
                if ($hasil->recordexists == TRUE) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = json_decode($this->curl->simple_put(API_URL . "/api/spk/leasing_komposisi", $param, array(CURLOPT_BUFFERSIZE => 10)));
                }
            }
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => $data . " Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );
            $this->output->set_output(json_encode($data));
        }
    }

    function list_komposisi() {
        $tahun = date("Y");
        $paramd = array();
        if ($this->session->userdata("nama_group") == 'Root') {
            // $paramd["kd_dealer"]=$this->session->userdata("kd_dealer");
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            /* 'offset'     => ($this->input->get('page')== null)?0:$this->input->get('page', TRUE),
              'limit'      => 10, */
            "jointable" => array(array("MASTER_DEALER M", "M.KD_DEALER=TRANS_SPK_LEASING_KOMPOSISI.KD_DEALER", "LEFT")),
            "field" => "M.KD_DEALER,M.NAMA_DEALER",
            "groupby" => TRUE,
            'kd_maindealer' => $this->session->userdata("kd_maindealer")
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get("kd_dealer");
        } else {
            $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        }
        if ($this->input->get("tahun")) {
            $tahun = $this->input->get("tahun");
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/leasing_komposisi", $param));
        $paramTahun = array('field' => "TAHUN", 'groupby' => TRUE, 'orderby' => " TAHUN DESC");
        $data["listtahun"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/leasing_komposisi", $paramTahun));
        if ($data["list"]) {
            if ($data["list"]->totaldata > 0) {
                foreach ($data["list"]->message as $key => $value) {
                    $where = "WHERE SL.KD_DEALER='" . $value->KD_DEALER . "' AND SL.TAHUN='" . $tahun . "'";
                    $paramsales = array(
                        'query' => $this->Custom_model->Leasing_Achieve($where)
                    );
                    $data["listd"][$value->KD_DEALER] = json_decode($this->curl->simple_get(API_URL . "/api/spk/leasing_komposisi", $paramsales));
                }
            }
        }
        $this->template->site('sales/leasing_komposisi', $data);
    }

    /**
     * created by Dimas Rido
     * [targeth3dealer description]
     * @return [type] [description]
     */
    public function targeth3dealer() {
        $data = array();
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER as MD", "MD.KD_DEALER=MASTER_TARGET_H3_DEALER.KD_DEALER", "LEFT")
            ),
            'field' => 'MASTER_TARGET_H3_DEALER.*,MD.NAMA_DEALER',
            'orderby' => 'MASTER_TARGET_H3_DEALER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targeth3dealer", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["dealerpilih"] = $this->input->get('kd_dealer');
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_target_h3_dealer', $data);
    }

    public function add_targeth3dealer() {
        $this->auth->validate_authen('setup/targeth3dealer');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targeth3dealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('form_tambah/add_targeth3dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_targeth3dealer_simpan() {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("target") == null) {
                $target = 0;
            } else {
                $target = $this->input->post("target");
            }
            $kategori = $this->input->post("kategori");
            $kd_dealer = $this->input->post("kd_dealer");
            $start_date = ('01/' . sprintf("%'.02d", $this->input->post("bulan")) . '/' . $this->input->post("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", $this->input->post("bulan")) . '/01/' . $this->input->post("tahun"))) . '/' . sprintf("%'.02d", $this->input->post("bulan")) . '/' . $this->input->post("tahun"));
            //$end_date = (date('t', strtotime(sprintf("%'.02d", $this->input->post("bulan2")) . '/01/' . $this->input->post("tahun2"))) . '/' . sprintf("%'.02d", $this->input->post("bulan2")) . '/' . $this->input->post("tahun2"));
            $param = array(
                'kategori' => $kategori,
                'kd_dealer' => $kd_dealer,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'target' => $target,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/targeth3dealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/targeth3dealer'));
        }
    }

    public function edit_targeth3dealer($id, $row_status) {
        $this->auth->validate_authen('setup/targeth3dealer');
        $param = array(
            "custom" => "MASTER_TARGET_H3_DEALER.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targeth3dealer", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        //var_dump($data["list"]);exit;
        $this->load->view('form_edit/edit_targeth3dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_targeth3dealer($id) {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("target") == null) {
                $target = 0;
            } else {
                $target = $this->input->post("target");
            }
            $kategori = $this->input->post("kategori");
            $kd_dealer = $this->input->post("kd_dealer");
            $start_date = ('01/' . sprintf("%'.02d", $this->input->post("bulan")) . '/' . $this->input->post("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", $this->input->post("bulan")) . '/01/' . $this->input->post("tahun"))) . '/' . sprintf("%'.02d", $this->input->post("bulan")) . '/' . $this->input->post("tahun"));
            $param = array(
                'id' => $this->input->post("id"),
                'kategori' => $kategori,
                'kd_dealer' => $kd_dealer,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'target' => $target,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/targeth3dealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function approve_targeth3dealer($kd_dealer) {
        $param = array(
            'kd_dealer' => $kd_dealer,
            'status_approve' => 1,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/master/targeth3dealerapprove", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $kd_dealer);
        $this->data_output($hasil, 'put');
    }

    public function delete_targeth3dealer($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/targeth3dealer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/targeth3dealer')
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

    public function targeth3dealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targeth3dealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
            $data_message[1][$key] = $message->KD_DEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    /**
     * [masterinsentifh3 description]
     * @return [type] [description]
     */
    public function masterinsentifh3() {
        $data = array();
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER as MD", "MD.KD_DEALER=MASTER_INSENTIF_H3.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN as MK", "MK.NIK=MASTER_INSENTIF_H3.NIK", "LEFT"),
            ),
            'field' => 'MASTER_INSENTIF_H3.*,MD.NAMA_DEALER,MK.NAMA',
            'orderby' => 'MASTER_INSENTIF_H3.ID desc',
            "custom" => "MK.KD_CABANG like '%" . $this->input->get('kd_dealer') . "%'",
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/masterinsentifh3", $param));
		//var_dump($param); exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
		//var_dump($data["list"]); exit();
        $data["dealerpilih"] = $this->input->get('kd_dealer');
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_insentif_h3', $data);
    }
    public function masterinsentifh2(){
        $data = array();
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER as MD", "MD.KD_DEALER=MASTER_INSENTIF_H3.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN as MK", "MK.NIK=MASTER_INSENTIF_H3.NIK", "LEFT"),
            ),
            'field' => 'MASTER_INSENTIF_H3.*,MD.NAMA_DEALER,MK.NAMA',
            'orderby' => 'MASTER_INSENTIF_H3.ID desc',
            "custom" => "MK.KD_CABANG like '%" . $this->input->get('kd_dealer') . "%'",
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/masterinsentifh3", $param));
        //var_dump($param); exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        //var_dump($data["list"]); exit();
        $data["dealerpilih"] = $this->input->get('kd_dealer');
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_insentif_h3', $data);
    }
    public function add_masterinsentifh3() {
        $this->auth->validate_authen('setup/targeth3dealer');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/masterinsentifh3"));
        /* $paramdealer = array(
          "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
          ); */
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramkaryawan = array(
            "custom" => "MASTER_KARYAWAN.KD_CABANG='" . $this->session->userdata('kd_dealer') . "' AND MASTER_KARYAWAN.KD_STATUS in ('STS-1','STS-2')"
        );
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramkaryawan));
        //var_dump($data["karyawan"]); exit();
        $this->load->view('form_tambah/add_masterinsentifh3', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_masterinsentifh3_simpan() {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("persentase") == null) {
                $persentase = 0;
            } else {
                $persentase = $this->input->post("persentase");
            }
            $kd_dealer = $this->input->post("kd_dealer");
            $nik = $this->input->post("nik");
            $param = array(
                'kd_dealer' => $kd_dealer,
                'nik' => $nik,
                'persentase' => $persentase,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/masterinsentifh3", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/masterinsentifh3'));
        }
    }

    public function edit_masterinsentifh3($id, $row_status) {
        $this->auth->validate_authen('setup/masterinsentifh3');
        $param = array(
            "custom" => "MASTER_INSENTIF_H3.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/masterinsentifh3", $param));
        $paramdealer = array(
            "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        //$data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",$paramdealer));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramkaryawan = array(
            "custom" => "MASTER_KARYAWAN.KD_CABANG='" . $this->session->userdata('kd_dealer') . "' AND MASTER_KARYAWAN.KD_STATUS in ('STS-1','STS-2')"
        );
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramkaryawan));
        //var_dump($data["list"]);exit;
        $this->load->view('form_edit/edit_masterinsentifh3', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_masterinsentifh3($id) {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("persentase") == null) {
                $persentase = 0;
            } else {
                $persentase = $this->input->post("persentase");
            }
            //var_dump('Persentase : '.$persentase); exit();
            $kd_dealer = $this->input->post("kd_dealer");
            $nik = $this->input->post("nik");
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $kd_dealer,
                'nik' => $nik,
                'persentase' => $persentase,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/masterinsentifh3", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_masterinsentifh3($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/masterinsentifh3", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/masterinsentifh3')
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

    public function approve_masterinsentifh3($kd_dealer) {
        $param = array(
            'kd_dealer' => $kd_dealer,
            'status_approve' => 1,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/master/masterinsentifh3approve", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $kd_dealer);
        $this->data_output($hasil, 'put');
    }

    public function masterinsentifh3_typeahead() {
        /* $param = array(
          'jointable' => array(
          array("MASTER_DEALER as MD", "MD.KD_DEALER=MASTER_TARGET_H3_DEALER.KD_DEALER", "LEFT")
          ),
          'field' => 'MASTER_TARGET_H3_DEALER.*,MD.NAMA_DEALER',
          );
          $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targeth3dealer", $param));
         */
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/targeth3dealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
            $data_message[1][$key] = $message->KD_DEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    /**
     * [jenisreceiving description]
     * @return [type] [description]
     */
    public function minimal() {
        $data = array();

        if ($this->input->get("kd_dealer") != null) {
            $kd_dealer = $this->input->get("kd_dealer");
        } else {
            $kd_dealer = $this->session->userdata('kd_dealer');
        }

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER as MD", "MD.KD_DEALER=SETUP_MINIMAL_VALUE.KD_DEALER", "LEFT"),
                array("MASTER_TRANS as MT", "MT.KD_TRANS=SETUP_MINIMAL_VALUE.KD_TRANS", "LEFT")
            ),
            'field' => 'SETUP_MINIMAL_VALUE.*, MD.NAMA_DEALER, MT.NAMA_TRANS',
            'orderby' => 'SETUP_MINIMAL_VALUE.ID desc',
            "custom" => "SETUP_MINIMAL_VALUE.KD_DEALER='" . $kd_dealer . "'"
        );



        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/minimal", $param));
        //var_dump($data["list"]);exit;
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/minimal', $data);
    }

    public function add_minimal() {
        $this->auth->validate_authen('setup/minimal');
        $data = array();
        $data["transaksi"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/minimal"));

        $this->load->view('form_tambah/add_minimal', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_minimal_simpan() {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_trans', 'Kode', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_trans' => $this->input->post("kd_trans"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'min_value' => $this->input->post("min_value") == "" ? 0 : $this->input->post("min_value"),
                'max_value' => $this->input->post("max_value") == "" ? 0 : $this->input->post("max_value"),
                'keterangan' => $this->input->post("keterangan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/minimal", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/minimal'));
        }
    }

    public function edit_minimal($id, $row_status) {
        $this->auth->validate_authen('setup/minimal');
        $param = array(
            'jointable' => array(
                array("MASTER_DEALER AS MD ", "MD.KD_DEALER=SETUP_MINIMAL_VALUE.KD_DEALER", "LEFT")
            ),
            'field' => 'SETUP_MINIMAL_VALUE.*, MD.NAMA_DEALER',
            "custom" => "SETUP_MINIMAL_VALUE.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["transaksi"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/minimal", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('form_edit/edit_minimal', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_minimal($id) {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_trans', 'Kode', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $id,
                'kd_trans' => $this->input->post("kd_trans"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'min_value' => $this->input->post("min_value") == "" ? 0 : $this->input->post("min_value"),
                'max_value' => $this->input->post("max_value") == "" ? 0 : $this->input->post("max_value"),
                'keterangan' => $this->input->post("keterangan"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/minimal", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_minimal($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/minimal", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('Setup/minimal')
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

    public function minimal_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/minimal"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TRANS;
            $data_message[1][$key] = $message->KD_DEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    public function insentif_stnk() {
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/insentif_stnk"));
        $string = link_pagination();
        $config = array(
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_insentif_stnk', $data);
    }

    public function edit_insentif_stnk($id) {
        $this->auth->validate_authen('setup/insentif_stnk');
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/insentif_stnk"));
        $this->load->view('form_edit/edit_insentif_stnk', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_insentif_stnk($id) {
        if ($this->input->post("value_config") == null) {
            $value = 0;
        } else {
            $value = $this->input->post("value_config");
        }
        $param = array(
            'id' => $this->input->post("id"),
            'value_config' => $value,
            'created_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/master/insentif_stnk", $param, array(CURLOPT_BUFFERSIZE => 10));

        $this->session->set_flashdata('tr-active', $id);

        $this->data_output($hasil, 'put');
    }

    /**
     * [setuphadiah description]
     * @return [type] [description]
     */
    public function hadiah() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'ID DESC'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/so_programhadiah", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_hadiah', $data);
    }

    public function add_hadiah() {
        $this->auth->validate_authen('setup/hadiah');
        $data = array();
        $param = array(
            'field' => 'KD_TYPEMOTOR',
            'orderby' => 'KD_TYPEMOTOR ASC',
            'groupby' => true
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/so_programhadiah"));
        $data["tipe"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));
        $this->load->view('form_tambah/add_hadiah', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function getKDHadiah() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kd_dealer='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/so_programhadiah"));
        $number = str_pad(($data->totaldata) + 1, 4, '0', STR_PAD_LEFT);
        return date('Y') . '-' . $number;
    }

    public function add_hadiah_simpan() {
        $this->form_validation->set_rules('nama_program', 'Kode Salesman', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Target', 'required|trim');
        //$this->form_validation->set_rules('start_date', 'Periode Mulai', 'required|trim');
        //$this->form_validation->set_rules('end_date', 'Periode Selesai', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("kd_typemotor") != null) {
                $kd_typemotor = implode(",", $this->input->post("kd_typemotor"));
            } else {
                $kd_typemotor = "";
            }
            $param = array(
                'kd_program' => $this->getKDHadiah(),
                'nama_program' => $this->input->post("nama_program"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'end_print' => $this->input->post("end_print"),
                'nama_hadiah' => $this->input->post("nama_hadiah"),
                'kd_typemotor' => $kd_typemotor,
                'jumlah_hadiah' => $this->input->post("jumlah"),
                'share_d' => $this->input->post("share_d"),
                'share_md' => $this->input->post("share_md"),
                'share_ahm' => $this->input->post("share_ahm"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit();
            $hasil = $this->curl->simple_post(API_URL . "/api/sales/so_programhadiah", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);

//            var_dump($data);
//            exit();
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('setup/hadiah'));
        }
    }

    public function edit_hadiah($id, $row_status) {
        $this->auth->validate_authen('setup/hadiah');
        $param = array(
            "custom" => "SETUP_SO_PROGRAMHADIAH.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/so_programhadiah", $param));
        $paramX = array(
            'field' => 'KD_TYPEMOTOR',
            'orderby' => 'KD_TYPEMOTOR ASC',
            'groupby' => true
        );
        $data["tipe"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $paramX));
        $this->load->view('form_edit/edit_hadiah', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_hadiah($id) {
        $this->form_validation->set_rules('nama_program', 'Kode Salesman', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Target', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("kd_typemotor") != null) {
                $kd_typemotor = implode(",", $this->input->post("kd_typemotor"));
            } else {
                $kd_typemotor = "";
            }

            $param = array(
                'id' => $this->input->post("id"),
                'kd_program' => $this->input->post("kd_program"),
                'nama_program' => $this->input->post("nama_program"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'end_print' => $this->input->post("end_print"),
                'nama_hadiah' => $this->input->post("nama_hadiah"),
                'kd_typemotor' => $kd_typemotor,
                'jumlah_hadiah' => $this->input->post("jumlah"),
                'share_d' => $this->input->post("share_d"),
                'share_md' => $this->input->post("share_md"),
                'share_ahm' => $this->input->post("share_ahm"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales/so_programhadiah", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_hadiah($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sales/so_programhadiah", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/hadiah')
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

    public function hadiah_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/so_programhadiah"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_PROGRAM;
            $data_message[1][$key] = $message->NAMA_PROGRAM;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }

    public function listpengajuan_insentifpicpart() {
        $data = array();
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER as MD", "MD.KD_DEALER=TRANS_INSENTIF_PICPART_HEADER.KD_DEALER", "LEFT"),
            ),
            'field' => 'TRANS_INSENTIF_PICPART_HEADER.*,MD.NAMA_DEALER',
            'orderby' => 'TRANS_INSENTIF_PICPART_HEADER.ID desc',
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentifpicpartheader", $param));
        //var_dump($data["list"]); exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["dealerpilih"] = $this->input->get('kd_dealer');
        //var_dump($data["list"]); exit();
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/insentif_picpart/insentifpicpart_listpengajuan', $data);
    }

    public function deletepengajuan_insentifpicpart($no_proses) {
        $param = array(
            'no_proses' => $no_proses,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        // $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentifpicpartheader", $param));   
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/laporan/insentifpicpart", $param));
        if ($data) {
            // echo '<script>console.log("'.$data.'")</script>';
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/listpengajuan_insentifpicpart')
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

    public function approvepengajuan_insentifpicpart($no_proses) {
        $this->auth->validate_authen('setup/listpengajuan_insentifpicpart');
        $param = array(
            "custom" => "TRANS_INSENTIF_PICPART_DETAIL.NO_PROSES='" . $no_proses . "'"
        );
        $data = array();
        $data["no_proses"] = $no_proses;
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentifpicpartdetail", $param));

        //var_dump($data["list"]);exit;
        $this->load->view('report/insentif_picpart/insentifpicpart_approve', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function prosesapprove_insentifpicpart($no_proses) {
        $param = array(
            'no_proses' => $no_proses,
            'status_approve' => 1,
            'approved_by' => "AAA",
            'lastmodified_by' => "AAA"
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/laporan/insentifpicpartapprove", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $no_proses);
        $this->data_output($hasil, 'put');
        //var_dump($hasil);
    }

    public function quantity_po() {
        $data = array();
        $param = array(
            'kd_dealer' => ($this->input->post('kd_dealer') == null) ? $this->session->userdata('kd_dealer') : $this->input->post('kd_dealer'),
            'row_status' => ($this->input->post('row_status') == null) ? 0 : $this->input->post('row_status'),
            'tahun' => ($this->input->post('tahun') == null) ? date('Y') : $this->input->post('tahun'),
            'kd_typemotor' => ($this->input->post('kd_typemotor') == null) ? '' : $this->input->post('kd_typemotor'),
            'orderby' => 'KD_TYPEMOTOR ASC',
            'jenis_po' => ($this->input->post('jenis_po') == null) ? '' : $this->input->post('jenis_po'),
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/qtypo", $param));
		//var_dump($data["list"]);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["dealerpilih"] = ($this->input->post('kd_dealer') == null) ? $this->session->userdata('kd_dealer') : $this->input->post('kd_dealer');
        $data["tahunpilih"] = ($this->input->post('tahun') == null) ? date('Y') : $this->input->post('tahun');
        $data["motorpilih"] = ($this->input->post('kd_typemotor') == null) ? '' : $this->input->post('kd_typemotor');
        $paramkd_motor = array(
            'row_status' => 0,
            'orderby' => 'NAMA_ITEM ASC'
        );
        $data["kodetipemotor"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor/tipe_motor", $paramkd_motor));
        $string = link_pagination();
        $config = array(
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/quantity_po', $data);
    }

    public function quantity_po_delete() {
        $param["id"] = ($this->input->get("id"));
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $id_pods = ($this->curl->simple_delete(API_URL . "/api/setup/qtypo", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->data_output($id_pods, 'delete');
    }

    public function quantity_po_update() {
        $param = array(
            'id' => $this->input->get("id"),
            'min' => $this->input->get("min"),
            'max' => $this->input->get("max"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $id_pods = ($this->curl->simple_put(API_URL . "/api/setup/qtypo", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->data_output($id_pods, 'update');
    }

    public function add_lsc() {
        $data = array();
        $param = array();

        if ($this->input->get("n")) {
            $param = array(
                'no_trans' => base64_decode(urldecode($this->input->get("n"))),
                'jointable' => array(
                    array("SETUP_LEASING_SKEMA_DETAIL SKD", "SKD.NO_TRANS=SETUP_LEASING_SKEMA.NO_TRANS AND SKD.ROW_STATUS>=0", "LEFT"),
                ),
                "field" => "SETUP_LEASING_SKEMA.*, SKD.*",
                "orderby" => "SKD.UANG_MUKA"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit", $param));
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["leasing"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));

        // $this->output->set_output(json_encode($data));
        $this->template->site('setup/lsc', $data);
    }

    function autogenerate_sl($kode_dealer) {
        $no_sl = "";
        $param = array(
            'kd_docno' => 'SL',
            'kd_dealer' => $kode_dealer,
            'tahun_docno' => date('Y'), // substr($this->input->post('tanggal_sa'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tgl_spk'), 3, 2),
            'limit' => 1,
            'offset' => 0
        );
        $bulan_kirim = date('m'); // substr($this->input->post('tanggal_sa'), 3, 2);
        $nomorsl = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($kode_dealer, 3, 0, STR_PAD_LEFT);

        if (strlen($kode_dealer) == 2) {
            $kd_dealer = str_pad($kode_dealer, 2, 0, STR_PAD_LEFT);
        }

        if ($nomorsl == 0) {
            $no_sl = "SL" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-000001";
        } else {
            $nomorsl = $nomorsl + 1;
            $no_sl = "SL" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorsl, 6, '0', STR_PAD_LEFT);
        }
        return $no_sl;
    }

    public function list_motor($dataonly = null) {

        $offset = ($this->input->get('p') - 1) * $this->input->get('per_page');

        $param = array(
            'kd_type' => $this->input->get('kd_typemotor'),
            'jointable' => array(
                array("SETUP_HARGAMOTOR SHM", "SHM.KD_ITEM=MASTER_P_TYPEMOTOR.KD_ITEM", "RIGHT")
            ),
            'field' => "MASTER_P_TYPEMOTOR.KD_TYPEMOTOR,MASTER_P_TYPEMOTOR.NAMA_PASAR, SHM.HARGA_OTR,SHM.KD_WILAYAH",
            'custom' => "SHM.KD_WILAYAH = '".$this->input->get('kd_wilayah')."'",
            'groupby' => TRUE,
            'order_by' => 'MASTER_P_TYPEMOTOR.KD_TYPEMOTOR,MASTER_P_TYPEMOTOR.NAMA_PASAR'
        );
        

        if ($this->input->get('q')) {
            $param['keyword'] = $this->input->get('q');
        } else {
            $param['offset'] = $offset;
            $param['limit'] = $this->input->get('per_page');
        }

        $list = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));

        $data = array();
        if ($list) {
            if ($list->totaldata > 0) {
                $data = array(
                    'p' => $this->input->get('q'),
                    'count' => $list->totaldata,
                    'per_page' => $this->input->get('per_page'),
                    'data' => $list->message
                );
            } else {
                $data = array(
                    'p' => $this->input->get('p'),
                    'count' => $list->totaldata,
                    'per_page' => $this->input->get('per_page'),
                    'data' => array()
                );
            }
        } else {
            $data = array(
                'p' => $this->input->get('p'),
                'count' => 0, //$list->totaldata, 
                'per_page' => $this->input->get('per_page'),
                'data' => array()
            );
        }

        if ($dataonly) {
            $this->output->set_output(json_encode($list));
        } else {
            $this->output->set_output(json_encode($data));
        }
    }

    public function simpan_lsc() {
        ini_set('max_execution_time', 0);
        $ntrans = ($this->input->post('no_trans')) ? $this->input->post('no_trans') : $this->autogenerate_sl($this->input->post('kd_dealer'));
        $param = array(
            'no_trans' => $ntrans,
            'kd_maindealer' => "T10",
            'kd_dealer' => $this->input->post('kd_dealer'),
            'kd_leasing' => $this->input->post('kd_leasing'),
            'kd_typemotor' => $this->input->post('kd_typemotor'),
            'tgl_trans' => $this->input->post("tgl_trans"),
            'harga_otr' => $this->input->post("harga_otr"),
            'start_date' => $this->input->post("start_date"),
            'end_date' => $this->input->post("end_date"),
            'keterangan' => $this->input->post("keterangan"),
            'created_by' => $this->session->userdata('user_id')
        );

        $hasil = $this->curl->simple_post(API_URL . "/api/setup/skema_kredit", $param, array(CURLOPT_BUFFERSIZE => 100));
        $method = "post";

        $data = json_decode($hasil);
        if ($data) {
            if ($data->recordexists == true) {
                $param["lastmodified_by"] = $this->session->userdata('user_id');
                $hasil = $this->curl->simple_put(API_URL . "/api/setup/skema_kredit", $param, array(CURLOPT_BUFFERSIZE => 100));
                $method = "put";
            }
        }

        if ($hasil) {
            if (json_decode($hasil)->message > 0) {
                $post_detail = $this->lsc_detail($ntrans);
            }
        }

        $this->data_output($hasil, $method, base_url('setup/add_lsc?n=' . urlencode(base64_encode($ntrans))));
    }

    public function lsc_detail($ntrans) {
        $detail = json_decode($this->input->post("detail"), true);
        for ($i = 0; $i < count($detail); $i++) {
            $param = array(
                'no_trans' => $ntrans,
                'uang_muka' => $detail[$i]['uang_muka'],
                'tenor' => $detail[$i]['tenor'],
                'jml_angsuran' => $detail[$i]['jml_angsuran'],
                'created_by' => $this->session->userdata("user_id")
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/setup/skema_kredit_detail", $param, array(CURLOPT_BUFFERSIZE => 10));

            if (json_decode($hasil)->recordexists == TRUE) {
                $param_lsc_detail = array(
                    'no_trans' => $ntrans,
                    'field' => '*'
                );

                $lsc_detail = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit_detail", $param_lsc_detail));

                if ($lsc_detail->status == false) {
                    $param_lsc_detail = array(
                        'no_trans' => $ntrans,
                        'row_status' => -1,
                        'field' => '*'
                    );
                    $param_lsc_detail['custom'] = "(UANG_MUKA = '" . $detail[$i]['uang_muka'] . "' AND TENOR = '" . $detail[$i]['tenor'] . "')";

                    $lsc_detail = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit_detail", $param_lsc_detail));
                }

                $param_update = array(
                    'id' => $lsc_detail->message[0]->ID,
                    'no_trans' => $ntrans,
                    'uang_muka' => $detail[$i]['uang_muka'],
                    'tenor' => $detail[$i]['tenor'],
                    'jml_angsuran' => $detail[$i]['jml_angsuran'],
                    'lastmodified_by' => $this->session->userdata("user_id")
                );
                $hasil = $this->curl->simple_put(API_URL . "/api/setup/skema_kredit_detail", $param_update, array(CURLOPT_BUFFERSIZE => 10));
            }
        }
    }

    public function lsc_list() {
        $data = array();
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'kd_typemotor' => $this->input->get('kd_typemotor'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page'),
            'jointable' => array(
                array("MASTER_COM_FINANCE MCF", "MCF.KD_LEASING=SETUP_LEASING_SKEMA.KD_LEASING", "LEFT"),
                array("MASTER_P_TYPEMOTOR MTP", "MTP.KD_TYPEMOTOR=SETUP_LEASING_SKEMA.KD_TYPEMOTOR", "LEFT")
            ),
            'field' => "distinct(SETUP_LEASING_SKEMA.KD_TYPEMOTOR),SETUP_LEASING_SKEMA.*, MCF.NAMA_LEASING, MTP.NAMA_PASAR",
            'orderby' => 'SETUP_LEASING_SKEMA.TGL_TRANS ASC',
            'limit' => 15
        );

        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }

        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["leasing"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('setup/lsc_list', $data); //, $data
    }

    public function delete_lsc($notrans) {
        $param = array(
            'no_trans' => $notrans,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/skema_kredit", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('setup/lsc_list')
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

    public function delete_lsc_detail() {
        $param = array(
            'id' => $this->input->get('id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/skema_kredit_detail", $param));
        $this->data_output($data, 'delete');
    }

    public function cetak_lsc() {
        ini_set('max_execution_time', 0);
        $this->load->library('dompdf_gen');

        $kd_dealer = $this->input->get("kd_dealer");
        $kd_typemotor = $this->input->get("kd_typemotor");
        $no_trans = base64_decode(urldecode($this->input->get("n")));

        $param = array(
            'jointable' => array(
                array("MASTER_COM_FINANCE MCF", "MCF.KD_LEASING=SETUP_LEASING_SKEMA.KD_LEASING", "LEFT"),
                array("MASTER_P_TYPEMOTOR MTP", "MTP.KD_TYPEMOTOR=SETUP_LEASING_SKEMA.KD_TYPEMOTOR", "LEFT")
            ),
            'field' => "SETUP_LEASING_SKEMA.*, MCF.NAMA_LEASING, MTP.NAMA_PASAR",
            'no_trans' => $no_trans
        );

        $paramDetail = array(
            'kd_dealer' => $kd_dealer,
            'kd_typemotor' => $kd_typemotor,
            'no_trans' => $no_trans
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit", $param));
        $data["listDetail"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit/view/true", $paramDetail));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["leasing"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        
        $data["jumlahTenor"] = 0;

        foreach ($data["listDetail"]->message as $key => $value) {
            foreach ($value as $item => $list1) {
                if (!strcspn($item, '0123456789')) {
                    $data["jumlahTenor"] = $data["jumlahTenor"] + 1;
                }
            }
            break;
        }

        $html = $this->load->view('setup/cetak_lsc', $data, true);
        $filename = 'report_' . time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'portrait');
    }

    public function importExcelLSC() {
        include APPPATH . 'third_party/PHPExcel.php';

        $config['upload_path'] = 'assets/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['file_name'] = "IMPORT_EXCEL_LSC";

        $this->load->library('upload', $config);

        $this->upload->do_upload('file');

        $this->upload->data('file_name');

        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $file = FCPATH . 'assets/IMPORT_EXCEL_LSC.' . $ext;

        $inputFileType = PHPExcel_IOFactory::identify($file);

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load(FCPATH . 'assets/IMPORT_EXCEL_LSC.' . $ext);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $error = array();
        $dataHeader = array();
        $dataUangMuka = array();
        $dataTenor = array();
        $dataJmlAngsuran = array();
        $statusUpload = false;

        $highestRow = $objWorksheet->getHighestRow();

        for ($a = 1; $a <= 8; $a++) {
            $headerValue = $objWorksheet->getCellByColumnAndRow(1, $a)->getValue();

            if (empty($headerValue)) {
                $error[] = "Data dari B1 sampai B8 tidak boleh kosong !";
                break;
            } else {
                $param = array();
                if ($a == 1) {
                    $param["kd_dealer"] = $headerValue;
                    $dealer = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
                    if ($dealer->status == false) {
                        $error[] = "Dealer " . $headerValue . " tidak terdaftar di sistem !<br/>";
                    }
                }
                if ($a == 2 || $a == 6 || $a == 7) {
                    $headerValue = $objWorksheet->getCellByColumnAndRow(1, $a)->getFormattedValue();

                    $statusDate = (bool) strtotime($headerValue);

                    if ($statusDate) {
                        $convertDate = strtotime($headerValue);

                        $headerValue = date('d-m-Y', $convertDate);
                        $statusDate = TRUE;
                    }

                    if (strlen($headerValue) != 10 || $statusDate == FALSE) {
                        $error[] = '"' . $headerValue . '" error pada baris B' . $a . ' , '
                                . 'pastikan format tanggal : tanggal-bulan-tahun (Contoh : 01-01-2019)<br/>';
                    }
                }
                if ($a == 3) {
                    $param["kd_leasing"] = $headerValue;
                    $leasing = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance", $param));

                    if ($leasing->status == false) {
                        $error[] = "Leasing '" . $headerValue . "' tidak terdaftar di sistem !<br/>";
                    }
                }

                if ($a == 4) {
                    $param["kd_type"] = $headerValue;
                    $kd_typemotor = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));

                    if ($kd_typemotor->status == false) {
                        $error[] = "Tipe Motor '" . $headerValue . "' tidak terdaftar di sistem !<br/>";
                    }
                }

                if ($a == 5) {
                    if (!is_numeric($headerValue)) {
                        $error[] = "Harga otr harus angka tanpa titik koma<br/>";
                    }
                }
            }

            $dataHeader[] = $headerValue;
        }

        if ($highestRow == 10) {
            $error[] = "Data lsc detail kosong !";
        }

        for ($i = 11; $i <= $highestRow; $i++) {
            $uang_muka_value = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
            $tenor_value = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
            $jml_angsuran_value = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();

            if (empty($uang_muka_value)) {
                $error[] = "Data uang muka pada baris A" . $i . " tidak boleh kosong<br/>";
            } else if (!is_numeric($uang_muka_value)) {
                $error[] = "Data uang muka pada baris A" . $i . " harus angka tanpa titik koma<br/>";
            }

            if (empty($tenor_value)) {
                $error[] = "Data tenor pada baris A" . $i . " tidak boleh kosong<br/>";
            } else if (!is_numeric($tenor_value)) {
                $error[] = "Data tenor pada baris A" . $i . " harus angka tanpa titik koma<br/>";
            }

            if (empty($jml_angsuran_value)) {
                $error[] = "Data jumlah angsuran pada baris A" . $i . " tidak boleh kosong<br/>";
            } else if (!is_numeric($jml_angsuran_value)) {
                $error[] = "Data jumlah angsuran pada baris A" . $i . " harus angka tanpa titik koma<br/>";
            }

            $dataUangMuka[] = $uang_muka_value;
            $dataTenor[] = $tenor_value;
            $dataJmlAngsuran[] = $jml_angsuran_value;
        }

        if (count($error) == 0) {
            ini_set('max_execution_time', 0);
            $ntrans = $this->autogenerate_sl($dataHeader[0]);
            $param = array(
                'no_trans' => $ntrans,
                'kd_maindealer' => "T10",
                'kd_dealer' => $dataHeader[0],
                'kd_leasing' => $dataHeader[2],
                'kd_typemotor' => $dataHeader[3],
                'tgl_trans' => $dataHeader[1],
                'harga_otr' => $dataHeader[4],
                'start_date' => $dataHeader[5],
                'end_date' => $dataHeader[6],
                'keterangan' => $dataHeader[7],
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/setup/skema_kredit", $param, array(CURLOPT_BUFFERSIZE => 100));
            $method = "post";

            $dataInput = json_decode($hasil);
            if ($dataInput) {
                if ($dataInput->recordexists == true) {
                    $param["lastmodified_by"] = $this->session->userdata('user_id');
                    $hasil = $this->curl->simple_put(API_URL . "/api/setup/skema_kredit", $param, array(CURLOPT_BUFFERSIZE => 100));
                    $method = "put";
                }
            }

            if ($hasil) {
                if (json_decode($hasil)->message > 0) {
                    for ($b = 0; $b < count($dataUangMuka); $b++) {
                        $paramDetail = array(
                            'no_trans' => $ntrans,
                            'uang_muka' => $dataUangMuka[$b],
                            'tenor' => $dataTenor[$b],
                            'jml_angsuran' => $dataJmlAngsuran[$b],
                            'created_by' => $this->session->userdata("user_id")
                        );

                        $hasilDetail = $this->curl->simple_post(API_URL . "/api/setup/skema_kredit_detail", $paramDetail, array(CURLOPT_BUFFERSIZE => 10));

                        if (json_decode($hasilDetail)->recordexists == TRUE) {
                            $param_lsc_detail = array(
                                'no_trans' => $ntrans,
                                'field' => '*'
                            );

                            $lsc_detail = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit_detail", $param_lsc_detail));

                            if ($lsc_detail->status == false) {
                                $param_lsc_detail = array(
                                    'no_trans' => $ntrans,
                                    'row_status' => -1,
                                    'field' => '*'
                                );
                                $param_lsc_detail['custom'] = "(UANG_MUKA = '" . $dataUangMuka[$b] . "' AND TENOR = '" . $dataTenor[$b] . "')";

                                $lsc_detail = json_decode($this->curl->simple_get(API_URL . "/api/setup/skema_kredit_detail", $param_lsc_detail));
                            }

                            $param_update = array(
                                'id' => $lsc_detail->message[0]->ID,
                                'no_trans' => $ntrans,
                                'uang_muka' => $dataUangMuka[$b],
                                'tenor' => $dataTenor[$b],
                                'jml_angsuran' => $dataJmlAngsuran[$b],
                                'lastmodified_by' => $this->session->userdata("user_id")
                            );
                            $hasilUpdate = $this->curl->simple_put(API_URL . "/api/setup/skema_kredit_detail", $param_update, array(CURLOPT_BUFFERSIZE => 10));
                        }
                    }
                }
            }

            $statusUpload = true;
        }

        $data["status"] = $statusUpload;
        $data["error"] = $error;
        unlink($file);
        echo json_encode($data);
    }

    public function downloadTemplateLSC() {
        $this->load->helper('download');
        force_download(FCPATH.'assets/TEMPLATE_EXCEL_LSC.xlsx', NULL);
    }

    public function quantity_po_insert() {
        $param = array(
            'kd_dealer' => $this->input->post("kd_dealer"),
            'tahun' => $this->input->post("tahun"),
            'kd_typemotor' => $this->input->post("kd_typemotor"),
            'jenis_po' => $this->input->post("jenis_po"),
            'min' => $this->input->post("min"),
            'max' => $this->input->post("max"),
            'created_by' => $this->session->userdata("user_id"),
        );
        $id_pods = $this->curl->simple_post(API_URL . "/api/setup/qtypo", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->data_output(true);
    }

}

?>