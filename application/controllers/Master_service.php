<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Master_service extends CI_Controller {
 
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
 
    /*
     * [typecomingcustomer description]
     * @return [type] [description]
     */
 
    public function absensi_mekanik() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            //'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'jointable' => array(
                array("MASTER_DEALER AS MD ", "TRANS_ABSENSI_MEKANIK.KD_DEALER=MD.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN AS MK", "TRANS_ABSENSI_MEKANIK.NIK = MK.NIK", "LEFT")
            ),
            'field' => '*,TRANS_ABSENSI_MEKANIK.ID as ID, TRANS_ABSENSI_MEKANIK.ROW_STATUS, MK.NIK',
            'orderby' => 'TRANS_ABSENSI_MEKANIK.ID desc',
            "custom" => "TRANS_ABSENSI_MEKANIK.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/absensi", $param));
       
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
        $this->template->site('master_service/absensimekanik/view', $data);
    }
 
    public function add_absensi_mekanik() {
        $this->auth->validate_authen('master_service/absensi_mekanik');
 
        $data = array();
        $param = array(
            'kd_dealer' =>($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer")
        );
 
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $param));
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/status_absensi"));

        //var_dump($data["karyawan"]);exit;
 
        $this->load->view('master_service/absensimekanik/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
    
    public function add_absensi_mekanik_simpan() {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'nik' => $this->input->post("nik"),
                'tanggal' => $this->input->post('tanggal'),
                'jam_masuk' => $this->input->post('jam_masuk'),
                'jam_pulang' => $this->input->post('jam_pulang'),
                'status_karyawan' => $this->input->post('status_karyawan'),
                'keterangan' => $this->input->post('keterangan'),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/absensi", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/absensi_mekanik'));
        }
    }
 
    public function edit_absensi_mekanik($id, $row_status) {
        $this->auth->validate_authen('master_service/absensi_mekanik');
        $param = array(
            "custom" => "ID='".$id."'",
            'row_status' => $row_status
        );
 
        $data = array();
        $param_kar = array(
            'kd_dealer' =>($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer")
        );
 
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $param_kar));
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/status_absensi"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/absensi", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_service/absensimekanik/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_absensi_mekanik($id) {
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'nik' => $this->input->post("nik"),
                'kd_dealer' => $this->input->post('kd_dealer'),
                'tanggal' => $this->input->post('tanggal'),
                'jam_masuk' => $this->input->post('jam_masuk'),
                'jam_pulang' => $this->input->post('jam_pulang'),
                'status_karyawan' => $this->input->post('status_karyawan'),
                'keterangan' => $this->input->post('keterangan'),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
 
            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/absensi", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_absensi_mekanik($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/absensi", $param));
 
        //var_dump($data); exit;
    
        $this->data_output($data, 'delete', base_url('master_service/absensi_mekanik'));
    }
 
    public function absensimekanik_typeahead() {
        $param = array(
            "custom" => "TRANS_ABSENSI_MEKANIK.KD_DEALER='".$this->session->userdata('kd_dealer')."'"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/absensi", $param));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NIK;
            $data_message[1][$key] = $message->TANGGAL;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    public function typecomingcustomer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer", $param));
 
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
 
 
        $this->template->site('master_service/typecomingcustomer/view', $data);
    }
 
    public function add_typecomingcustomer() {
        $this->auth->validate_authen('master_service/typecomingcustomer');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer"));
        $this->load->view('master_service/typecomingcustomer/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_typecomingcustomer_simpan() {
        $this->form_validation->set_rules('kd_typecomingcustomer', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_typecomingcustomer', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_typecomingcustomer' => $this->input->post("kd_typecomingcustomer"),
                'nama_typecomingcustomer' => $this->input->post("nama_typecomingcustomer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/typecomingcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));                                                
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/typecomingcustomer'));
        }
    }
 
    public function edit_typecomingcustomer($kd_typecomingcustomer, $row_status) {
        $this->auth->validate_authen('master_service/typecomingcustomer');
        $param = array(
            'kd_typecomingcustomer' => $kd_typecomingcustomer,
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer", $param));
 
        $this->load->view('master_service/typecomingcustomer/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_typecomingcustomer($id) {
        $this->form_validation->set_rules('kd_typecomingcustomer', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_typecomingcustomer', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_typecomingcustomer' => html_escape($this->input->post("kd_typecomingcustomer")),
                'nama_typecomingcustomer' => html_escape($this->input->post("nama_typecomingcustomer")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/typecomingcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_typecomingcustomer($kd_typecomingcustomer) {
        $param = array(
            'kd_typecomingcustomer' => $kd_typecomingcustomer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/typecomingcustomer", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/typecomingcustomer')
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
 
    public function typecomingcustomer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/typecomingcustomer"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TYPECOMINGCUSTOMER;
            $data_message[1][$key] = $message->NAMA_TYPECOMINGCUSTOMER;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /**
     * [tipepkb description]
     * @return [type] [description]
     */
    public function tipepkb() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb", $param));
 
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
 
 
        $this->template->site('master_service/tipepkb/view', $data);
    }
 
    public function add_tipepkb() {
        $this->auth->validate_authen('master_service/tipepkb');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb"));
        $this->load->view('master_service/tipepkb/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_tipepkb_simpan() {
        $this->form_validation->set_rules('kd_tipepkb', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_tipepkb', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_tipepkb' => $this->input->post("kd_tipepkb"),
                'nama_tipepkb' => $this->input->post("nama_tipepkb"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/tipepkb", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/tipepkb'));
        }
    }
 
    public function edit_tipepkb($kd_tipepkb, $row_status) {
        $this->auth->validate_authen('master_service/tipepkb');
        $param = array(
            'kd_tipepkb' => $kd_tipepkb,
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb", $param));
 
        $this->load->view('master_service/tipepkb/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_tipepkb($id) {
        $this->form_validation->set_rules('kd_tipepkb', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_tipepkb', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_tipepkb' => html_escape($this->input->post("kd_tipepkb")),
                'nama_tipepkb' => html_escape($this->input->post("nama_tipepkb")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/tipepkb", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_tipepkb($kd_tipepkb) {
        $param = array(
            'kd_tipepkb' => $kd_tipepkb,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/tipepkb", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/tipepkb')
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
 
    public function tipepkb_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/tipepkb"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TIPEPKB;
            $data_message[1][$key] = $message->NAMA_TIPEPKB;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /**
     * [statusservicecustomer description]
     * @return [type] [description]
     */
    public function statusservicecustomer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/statusservicecustomer", $param));
 
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
 
 
        $this->template->site('master_service/statusservicecustomer/view', $data);
    }
 
    public function add_statusservicecustomer() {
        $this->auth->validate_authen('master_service/statusservicecustomer');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/statusservicecustomer"));
        $this->load->view('master_service/statusservicecustomer/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_statusservicecustomer_simpan() {
        $this->form_validation->set_rules('kd_statusservicecustomer', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_statusservicecustomer', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_statusservicecustomer' => $this->input->post("kd_statusservicecustomer"),
                'nama_statusservicecustomer' => $this->input->post("nama_statusservicecustomer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/statusservicecustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/statusservicecustomer'));
        }
    }
 
    public function edit_statusservicecustomer($kd_statusservicecustomer, $row_status) {
        $this->auth->validate_authen('master_service/statusservicecustomer');
        $param = array(
            'kd_statusservicecustomer' => $kd_statusservicecustomer,
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/statusservicecustomer", $param));
 
        $this->load->view('master_service/statusservicecustomer/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_statusservicecustomer($id) {
        $this->form_validation->set_rules('kd_statusservicecustomer', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_statusservicecustomer', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_statusservicecustomer' => html_escape($this->input->post("kd_statusservicecustomer")),
                'nama_statusservicecustomer' => html_escape($this->input->post("nama_statusservicecustomer")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/statusservicecustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_statusservicecustomer($kd_statusservicecustomer) {
        $param = array(
            'kd_statusservicecustomer' => $kd_statusservicecustomer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/statusservicecustomer", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/statusservicecustomer')
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
 
    public function statusservicecustomer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/statusservicecustomer"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_STATUSSERVICECUSTOMER;
            $data_message[1][$key] = $message->NAMA_STATUSSERVICECUSTOMER;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * jenispit description]
     * @return [type] [description]
     */
 
    public function jenispit() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jenispit", $param));
 
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
 
 
        $this->template->site('master_service/jenispit/view', $data);
    }
 
    public function add_jenispit() {
        $this->auth->validate_authen('master_service/jenispit');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jenispit"));
        $this->load->view('master_service/jenispit/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_jenispit_simpan() {
        $this->form_validation->set_rules('kd_jenispit', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jenispit', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenispit' => $this->input->post("kd_jenispit"),
                'nama_jenispit' => $this->input->post("nama_jenispit"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/jenispit", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/jenispit'));
        }
    }
 
    public function edit_jenispit($kd_jenispit, $row_status) {
        $this->auth->validate_authen('master_service/jenispit');
        $param = array(
            'kd_jenispit' => $kd_jenispit,
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jenispit", $param));
 
        $this->load->view('master_service/jenispit/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_jenispit($id) {
        $this->form_validation->set_rules('kd_jenispit', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_jenispit', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenispit' => html_escape($this->input->post("kd_jenispit")),
                'nama_jenispit' => html_escape($this->input->post("nama_jenispit")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/jenispit", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_jenispit($kd_jenispit) {
        $param = array(
            'kd_jenispit' => $kd_jenispit,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/jenispit", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/jenispit')
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
 
    public function jenispit_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/jenispit"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISPIT;
            $data_message[1][$key] = $message->NAMA_JENISPIT;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * tipeservicemekanik description]
     * @return [type] [description]
     */
 
    public function tipeservicemekanik() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipeservicemekanik", $param));
 
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
 
 
        $this->template->site('master_service/tipeservicemekanik/view', $data);
    }
 
    public function add_tipeservicemekanik() {
        $this->auth->validate_authen('master_service/tipeservicemekanik');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipeservicemekanik"));
        $this->load->view('master_service/tipeservicemekanik/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_tipeservicemekanik_simpan() {
        $this->form_validation->set_rules('kd_tipeservicemekanik', 'Kode', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_tipeservicemekanik', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_tipeservicemekanik' => $this->input->post("kd_tipeservicemekanik"),
                'nama_tipeservicemekanik' => $this->input->post("nama_tipeservicemekanik"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/tipeservicemekanik", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/tipeservicemekanik'));
        }
    }
 
    public function edit_tipeservicemekanik($kd_tipeservicemekanik, $row_status) {
        $this->auth->validate_authen('master_service/tipeservicemekanik');
        $param = array(
            'kd_tipeservicemekanik' => $kd_tipeservicemekanik,
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipeservicemekanik", $param));
 
        $this->load->view('master_service/tipeservicemekanik/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_tipeservicemekanik($id) {
        $this->form_validation->set_rules('kd_tipeservicemekanik', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_tipeservicemekanik', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_tipeservicemekanik' => html_escape($this->input->post("kd_tipeservicemekanik")),
                'nama_tipeservicemekanik' => html_escape($this->input->post("nama_tipeservicemekanik")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/tipeservicemekanik", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_tipeservicemekanik($kd_tipeservicemekanik) {
        $param = array(
            'kd_tipeservicemekanik' => $kd_tipeservicemekanik,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/tipeservicemekanik", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/tipeservicemekanik')
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
 
    public function tipeservicemekanik_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/tipeservicemekanik"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TIPESERVICEMEKANIK;
            $data_message[1][$key] = $message->NAMA_TIPESERVICEMEKANIK;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * comingcustomer description]
     * @return [type] [description]
     */
 
    public function comingcustomer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("SETUP_TYPECOMINGCUSTOMER", "SETUP_TYPECOMINGCUSTOMER.KD_TYPECOMINGCUSTOMER=MASTER_COMINGCUSTOMER.KD_TYPECOMINGCUSTOMER", "LEFT"),
                array("MASTER_KABUPATEN", "MASTER_KABUPATEN.KD_KABUPATEN=MASTER_COMINGCUSTOMER.KD_KABUPATEN", "LEFT"),
                array("MASTER_PROPINSI", "MASTER_PROPINSI.KD_PROPINSI=MASTER_COMINGCUSTOMER.KD_PROPINSI", "LEFT"),
                array("MASTER_KECAMATAN", "MASTER_KECAMATAN.KD_KECAMATAN=MASTER_COMINGCUSTOMER.KD_KECAMATAN", "LEFT"),
                array("MASTER_DESA", "MASTER_DESA.KD_DESA=MASTER_COMINGCUSTOMER.KD_DESA", "LEFT"),
                array("MASTER_GENDER", "MASTER_GENDER.KD_GENDER=MASTER_COMINGCUSTOMER.KD_GENDER", "LEFT")
            ),
            'field' => 'MASTER_COMINGCUSTOMER.*, SETUP_TYPECOMINGCUSTOMER.NAMA_TYPECOMINGCUSTOMER, MASTER_KABUPATEN.NAMA_KABUPATEN, MASTER_PROPINSI.NAMA_PROPINSI, MASTER_KECAMATAN.NAMA_KECAMATAN, MASTER_DESA.NAMA_DESA, MASTER_GENDER.NAMA_GENDER',
            'orderby' => 'MASTER_COMINGCUSTOMER.ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/master_comingcustomer", $param));
 
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master_service/comingcustomer/view', $data);
    }
 
    public function add_comingcustomer() {
        $this->auth->validate_authen('master_service/comingcustomer');
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/master_comingcustomer"));
        $data["typecomingcustomers"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer"));
        $data["genders"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
 
        $this->load->view('master_service/comingcustomer/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    /**
     * [kabupaten description]
     * @return [type] [description]
     */
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
 
    /**
     * [kecamatan description]
     * @return [type] [description]
     */
    public function kecamatan() {
        $param = array(
            'kd_kabupaten' => $this->input->post('kd')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan", $param));
        echo "<option value='0'>--Pilih Kecamatan--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_KECAMATAN . "'>" . $value->NAMA_KECAMATAN . "</option>";
                }
            }
        }
    }
 
    /**
     * [desa description]
     * @return [type] [description]
     */
    public function desa() {
        $param = array(
            'kd_kecamatan' => $this->input->post('kd')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $param));
        echo "<option value='0'>--Pilih Desa/Kelurahan--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_DESA . "'>" . $value->NAMA_DESA . "</option>";
                }
            }
        }
    }
 
    /**
     * [comingcustomerdetail description]
     * @return [type] [description]
     */
    public function comingcustomerdetail() {
        $param = array(
            "nama_comingcustomer" => $this->input->post("nama_comingcustomer")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/master_comingcustomer", $param));
        $result = array();
        if ($data) {
            if (is_array($data->message)) {
                $result = json_encode($data->message);
            } else {
                $result = json_encode($data);
            }
        }
        echo $result;
    }
 
    public function add_comingcustomer_simpan() {
        $this->form_validation->set_rules('nama_comingcustomer', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_ktp', 'Nomor KTP', 'required|trim');
        $this->form_validation->set_rules('kd_typecomingcustomer', 'Tipe', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'nama_comingcustomer' => $this->input->post("nama_comingcustomer"),
                'kd_gender' => $this->input->post("kd_gender"),
                'no_ktp' => $this->input->post("no_ktp"),
                'no_telepon' => $this->input->post("no_telepon"),
                'alamat_ktp' => $this->input->post("alamat_ktp"),
                'alamat_terakhir' => $this->input->post("alamat_terakhir"),
                'email' => $this->input->post("email"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kd_desa' => $this->input->post("kd_desa"),
                'kode_pos' => $this->input->post("kode_pos"),
                'kd_typecomingcustomer' => $this->input->post("kd_typecomingcustomer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/master_comingcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/comingcustomer'));
        }
    }
 
    public function edit_comingcustomer($id, $row_status) {
        $this->auth->validate_authen('master_service/comingcustomer');
        $param = array(
            "custom" => "MASTER_COMINGCUSTOMER.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["typecomingcustomers"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer"));
        $data["genders"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/master_comingcustomer", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_service/comingcustomer/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_comingcustomer($id) {
        $this->form_validation->set_rules('nama_comingcustomer', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_ktp', 'Nomor KTP', 'required|trim');
        $this->form_validation->set_rules('kd_typecomingcustomer', 'Tipe', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'nama_comingcustomer' => $this->input->post("nama_comingcustomer"),
                'kd_gender' => $this->input->post("kd_gender"),
                'no_ktp' => $this->input->post("no_ktp"),
                'no_telepon' => $this->input->post("no_telepon"),
                'alamat_ktp' => $this->input->post("alamat_ktp"),
                'alamat_terakhir' => $this->input->post("alamat_terakhir"),
                'email' => $this->input->post("email"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kd_desa' => $this->input->post("kd_desa"),
                'kode_pos' => $this->input->post("kode_pos"),
                'kd_typecomingcustomer' => $this->input->post("kd_typecomingcustomer"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
 
            //var_dump($param);exit;
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/master_comingcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_comingcustomer($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/master_comingcustomer", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/comingcustomer')
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
 
    public function comingcustomer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/master_comingcustomer"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_COMINGCUSTOMER;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * pajakperusahaan description]
     * @return [type] [description]
     */
 
    public function pajakperusahaan() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_PAJAKPERUSAHAAN.KD_KABUPATEN", "LEFT")
            ),
            'field' => 'MASTER_PAJAKPERUSAHAAN.*, MK.NAMA_KABUPATEN',
            'orderby' => 'MASTER_PAJAKPERUSAHAAN.ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/pajakperusahaan", $param));
 
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
 
 
        $this->template->site('master_service/pajakperusahaan/view', $data);
    }
 
    public function add_pajakperusahaan() {
        $this->auth->validate_authen('master_service/pajakperusahaan');
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/pajakperusahaan"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $this->load->view('master_service/pajakperusahaan/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_pajakperusahaan_simpan() {
        $this->form_validation->set_rules('nama_perusahaan', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'nama_perusahaan' => $this->input->post("nama_perusahaan"),
                'no_npwp' => $this->input->post("no_npwp"),
                'alamat' => $this->input->post("alamat"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'no_telpperusahaan' => $this->input->post("no_telpperusahaan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/pajakperusahaan", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/pajakperusahaan'));
        }
    }
 
    public function edit_pajakperusahaan($id, $row_status) {
        $this->auth->validate_authen('master_service/pajakperusahaan');
        $param = array(
            //'id' => $id,
            "custom" => "MASTER_PAJAKPERUSAHAAN.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/pajakperusahaan", $param));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
 
        $this->load->view('master_service/pajakperusahaan/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_pajakperusahaan($id) {
        $this->form_validation->set_rules('nama_perusahaan', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'nama_perusahaan' => $this->input->post("nama_perusahaan"),
                'no_npwp' => $this->input->post("no_npwp"),
                'alamat' => $this->input->post("alamat"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'no_telpperusahaan' => $this->input->post("no_telpperusahaan"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/pajakperusahaan", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_pajakperusahaan($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/pajakperusahaan", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/pajakperusahaan')
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
 
    public function pajakperusahaan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/pajakperusahaan"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_COMPANY;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /**
     * [jasa description]
     * @return [type] [description]
     */
    public function jasa() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'CREATED_TIME DESC'
        );
 
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/jasa/view', $data);
    }
 
    public function add_jasa() {
        $this->auth->validate_authen('master_service/jasa');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $this->load->view('master_service/jasa/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_jasa() {
        ini_set('max_execution_time', 120);
        ini_set('post_max_size', 0);
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
        $hasilx = "";
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
 
                $filename = $_FILES["file"]["tmp_name"];
 
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    $n = 0;
                    $x = 0;
                    $param[$x]["param"] = array();
                    $arr = array();
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        $arr[] = array(
                            'kategori' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'kd_jasa' => !empty($importdata[1]) ? rtrim($importdata[1]) : '',
                            'kd_motor' => !empty($importdata[2]) ? rtrim($importdata[2]) : '',
                            'keterangan' => !empty($importdata[3]) ? rtrim($importdata[3]) : '',
                            'frt' => !empty($importdata[4]) ? rtrim($importdata[4]) : '',
                            'harga' => !empty($importdata[5]) ? rtrim($importdata[5]) : '',
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $n++;
                    }
 
                    //API Url
                    $url = API_URL . "/api/master_service/sdmjbatch";
 
                    //Initiate cURL.
                    $ch = curl_init($url);
                    $jsonDataEncoded = (base64_encode(json_encode($arr)));
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    $hasilx = curl_exec($ch);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data = json_encode($hasilx);
        //var_dump($hasilx);
        //exit;
        $this->data_output($data, 'post', base_url('master_service/jasa'));
    }
 
    public function add_jasa_simpan() {
        $this->form_validation->set_rules('kd_jasa', 'Kode Jasa', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kd_motor', 'Tipe Motor', 'required|trim');
 
        if ($this->input->post("harga") == null) {
            $harga = 0;
        } else {
            $harga = $this->input->post("harga");
        }
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jasa' => $this->input->post("kd_jasa"),
                'kd_motor' => $this->input->post("kd_motor"),
                'keterangan' => $this->input->post("keterangan"),
                'frt' => $this->input->post("frt"),
                'harga' => $harga,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/jasa", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/jasa'));
        }
    }
 
    public function edit_jasa($id, $row_status) {
        $this->auth->validate_authen('master_service/jasa');
        $param = array(
            "custom" => "MASTER_JASA.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
 
        $this->load->view('master_service/jasa/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_jasa($id) {
        $this->form_validation->set_rules('kd_jasa', 'Kode Jasa', 'required|trim');
        $this->form_validation->set_rules('kd_motor', 'Tipe Motor', 'required|trim');
 
        if ($this->input->post("harga") == null) {
            $harga = 0;
        } else {
            $harga = $this->input->post("harga");
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
                'kd_jasa' => $this->input->post("kd_jasa"),
                'kd_motor' => $this->input->post("kd_motor"),
                'keterangan' => $this->input->post("keterangan"),
                'frt' => $this->input->post("frt"),
                'harga' => $harga,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/jasa", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_jasa($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/jasa", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/jasa')
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
 
    public function jasa_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JASA;
            $data_message[1][$key] = $message->KD_MOTOR;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }

    public function jasa_promo($keywords=null,$price=null) {
        $data=[];
        $keywords = $this->input->get('keyword');

        $param = array(
            'keyword' => $keywords 
        );
        $data_message=[];
        $data=json_decode($this->curl->simple_get(API_URL."/api/master_service/jasa",$param));

        // var_dump($data); exit;

        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->KD_JASA ." - ". $message->KETERANGAN ." - ". $message->HARGA;
                }
            }else{
                $data_message['keyword'][]="";//$data->message;
            }
        }else{

            $data_message['keyword'][0]="<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,

        $this->output->set_output(json_encode($data_message));
    }
 
    /*     * promoprogram description]
     * @return [type] [description]
     */
 
    public function promoprogram() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=MASTER_PROMOPROGRAM.KD_DEALER", "LEFT")
            ),
            'orderby' => 'MASTER_PROMOPROGRAM.ID DESC',
            "custom" => "MASTER_PROMOPROGRAM.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promoprogram", $param));
 
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
 
 
        $this->template->site('master_service/promoprogram/view', $data);
    }
 
    public function detail_promoprogram($kd_promo) {
        $this->auth->validate_authen('master_service/promoprogram');
        $param = array(
            //'kd_detailpromo' => $kd_promo,
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_PROMO_PROGRAM_DETAIL.ID DESC',
            "custom" => "MASTER_PROMO_PROGRAM_DETAIL.KD_DETAILPROMO='" . $kd_promo . "'"
        );
 
        $param_cek = array(
            "custom" => "MASTER_PROMOPROGRAM.KD_PROMO='" . $kd_promo . "'"
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promo_program_detail", $param));
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promoprogram", $param_cek));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/promoprogram/view_detail', $data);
    }
 
    public function add_promoprogram() {
        $this->auth->validate_authen('master_service/promoprogram');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promoprogram"));
        $this->load->view('master_service/promoprogram/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function detail_add_promoprogram($kd_promo) {
        $data = array();
        $param = array(
            "custom" => "MASTER_PROMOPROGRAM.KD_PROMO='" . $kd_promo . "'"
        );
 
        $param_user = array(
            "custom" => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promoprogram", $param));
 
        $this->load->view('master_service/promoprogram/add_detail', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_promoprogram_simpan() {
        $this->form_validation->set_rules('kd_promo', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_program', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_promo' => $this->input->post("kd_promo"),
                'nama_program' => $this->input->post("nama_program"),
                'keterangan' => $this->input->post("keterangan"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/promoprogram", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/promoprogram'));
        }
    }
 
    public function detail_add_promoprogram_simpan() {
        $this->form_validation->set_rules('kd_pekerjaan', 'Kode Pekerjaan', 'required|trim');
        $this->form_validation->set_rules('part_no', 'Part Number', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga', 'required|trim');
        $this->form_validation->set_rules('harga_no_part', 'Harga', 'required|trim');
 
        if ($this->input->post("harga") == null) {
            $harga = 0;
        } else {
            $harga = $this->input->post("harga");
        }
 
        if ($this->input->post("harga_no_part") == null) {
            $harga_no_part = 0;
        } else {
            $harga_no_part = $this->input->post("harga_no_part");
        }
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $data = array();
            $part_number = substr($this->input->post("part_no"), 0, strpos($this->input->post("part_no"), ' '));
            $jasa = substr($this->input->post("kd_pekerjaan"), 0, strpos($this->input->post("kd_pekerjaan"), ' '));

            $param_jasa = array(
                "custom" => "KD_JASA IN('".$jasa."')"
            );

            $param_part = array(
                "custom" => "PART_NUMBER IN('".$part_number."')"
            );

            $data['jasa'] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa",$param_jasa));
            $data['part']= json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param_part));

            //var_dump($data['jasa'], $data['part']);exit;
            //var_dump($data['jasa']->totaldata, $data['part']->totaldata );exit;

            if($data['jasa']->totaldata <= 0 || $data['part']->totaldata <= 0){
                $data = array(
                    'status' => false,
                    'message' => 'Kode Pekerjaan atau Part Number harus sesuai dengan pilihan yang disediakan'
                );

                $this->output->set_output(json_encode($data));
            }else{
                $param = array(
                    'kd_detailpromo' => $this->input->post("kd_detailpromo"),
                    'kd_pekerjaan' => $jasa,
                    'harga' => $harga,
                    'no_part' => $part_number,
                    'harga_no_part' => $harga_no_part,
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );

                //var_dump($param);exit;

                $hasil = $this->curl->simple_post(API_URL . "/api/master_service/promo_program_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
 
                $data = json_decode($hasil);
                $this->session->set_flashdata('tr-active', $data->message);
     
                $this->data_output($hasil, 'post', base_url('master_service/detail_promoprogram/' . $this->input->post("kd_detailpromo")));
                }
            
            
            
        }
    }
 
    public function edit_promoprogram($kd_promo, $row_status) {
        $param = array(
            "custom" => "MASTER_PROMOPROGRAM.KD_PROMO='" . $kd_promo . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promoprogram", $param));
 
        $this->load->view('master_service/promoprogram/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function detail_edit_promoprogram($id, $row_status) {
        $param = array(
            "custom" => "MASTER_PROMO_PROGRAM_DETAIL.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promo_program_detail", $param));
        //var_dump($data["list"]);exit;
 
        $this->load->view('master_service/promoprogram/edit_detail', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
 
    public function update_promoprogram($id) {
        $this->form_validation->set_rules('kd_promo', 'Kode', 'required|trim');
        $this->form_validation->set_rules('nama_program', 'Nama', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_promo' => html_escape($this->input->post("kd_promo")),
                'nama_program' => html_escape($this->input->post("nama_program")),
                'keterangan' => $this->input->post("keterangan"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/promoprogram", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function detail_update_promoprogram($id) {
        $this->form_validation->set_rules('kd_pekerjaan', 'Kode Pekerjaan', 'required|trim');
        $this->form_validation->set_rules('part_no', 'Part Number', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga', 'required|trim');
        $this->form_validation->set_rules('harga_no_part', 'Harga', 'required|trim');
 
        if ($this->input->post("harga") == null) {
            $harga = 0;
        } else {
            $harga = $this->input->post("harga");
        }
 
        if ($this->input->post("harga_no_part") == null) {
            $harga_no_part = 0;
        } else {
            $harga_no_part = $this->input->post("harga_no_part");
        }
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => html_escape($this->input->post("id")),
                'kd_detailpromo' => $this->input->post("kd_detailpromo"),
                'kd_pekerjaan' => $this->input->post("kd_pekerjaan"),
                'harga' => $harga,
                'no_part' => $this->input->post("part_no"),
                'harga_no_part' => $harga_no_part,
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/promo_program_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_promoprogram($kd_promo) {
        $param = array(
            'kd_promo' => $kd_promo,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/promoprogram", $param));
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/promoprogram')
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
 
    public function detail_delete_promoprogram($id, $kd_detailpromo) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/promo_program_detail", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/detail_promoprogram/' . $kd_detailpromo)
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
 
 
    public function promoprogram_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/promoprogram"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_PROMO;
            $data_message[1][$key] = $message->NAMA_PROGRAM;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    public function detail_promoprogram_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master_service/detail_promo_program"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DETAILPROMO;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /**
     * [kpb description]
     * @return [type] [description]
     */
    public function kpb() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_KPB_BY_TIPEMOTOR.ID desc'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master_service/kpb/view', $data);
    }
 
    public function add_kpb() {
        $this->auth->validate_authen('master_service/KPB');
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list23";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb"), true);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
 
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $data["listmd"] = $js;
        $this->load->view('master_service/kpb/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_kpb() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
 
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'no_mesin' => $data[0][$i]["nomesin"],
                'tipe_motor' => htmlspecialchars_decode($data[0][$i]["tipemotor"]),
                'premium' => $data[0][$i]["premium"],
                'bkm1' => $data[0][$i]["bkm1"],
                'bkm2' => $data[0][$i]["bkm2"],
                'bkm3' => $data[0][$i]["bkm3"],
                'bkm4' => $data[0][$i]["bkm4"],
                'bse1' => $data[0][$i]["bse1"],
                'bse2' => $data[0][$i]["bse2"],
                'bse3' => $data[0][$i]["bse3"],
                'bse4' => $data[0][$i]["bse4"],
                'bcl1' => $data[0][$i]["bcl1"],
                'bcl2' => $data[0][$i]["bcl2"],
                'bcl3' => $data[0][$i]["bcl3"],
                'bcl4' => $data[0][$i]["bcl4"],
                'created_by' => $this->session->userdata("user_id")
            );
 
            $hasil = ($this->curl->simple_post(API_URL . "/api/master_service/kpb", $param, array(CURLOPT_BUFFERSIZE => 10)));
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
 
    public function kpb_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KATEGORI;
            $data_message[1][$key] = $message->KD_MOTOR;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /**
     * [partvstipemotor description]
     * @return [type] [description]
     */
    public function partvstipemotor() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'MASTER_PVTM.NO_PART_TIPEMOTOR, MASTER_PVTM.TYPE_MARKETING'
        );
 
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/pvtm", $param));
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/partvstipemotor/view', $data);
    }
 
    public function add_partvstipemotor() {
        $this->auth->validate_authen('master_service/partvstipemotor');
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
        //$data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $this->load->view('master_service/partvstipemotor/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_partvstipemotor() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
 
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
 
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    while (($importdata = fgetcsv($file, 1000000, ";")) !== FALSE) {
                        $param = array(
                            'no_part_tipemotor' => !empty($importdata[0]) ? $importdata[0] : '',
                            'type_marketing' => !empty($importdata[1]) ? $importdata[1] : '',
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/pvtm", $param, array(CURLOPT_BUFFERSIZE => 10));
                    }
                    if ($hasil <= 0) {
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
 
//                        break;
                    } else {
                        $response['status'] = 'success';
                        $response['message'] = 'Successfully added new record.';
                    }
                    fclose($file);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('motor/grup_motor'));
    }
 
    public function notEmpty() {
        if (!empty($_FILES['file']['name'])) {
            return TRUE;
        } else {
            $this->form_validation->set_message('notEmpty', 'The {field} field can not be empty.');
            return FALSE;
        }
    }
 
    public function partvstipemotor_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JASA;
            $data_message[1][$key] = $message->KD_MOTOR;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /**
     * [ptm description]
     * @return [type] [description]
     */
    public function ptm() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_PTM.ID DESC'
        );
 
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/ptm", $param));
 
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
 
        $pagination = $this->template->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/ptm/view', $data);
    }
 
    public function add_ptm() {
        $this->auth->validate_authen('master_service/ptm');
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
        //$data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $this->load->view('master_service/ptm/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_ptm() {
        ini_set('max_execution_time', 600);
        ini_set('post_max_size', 0);
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
        $hasilx = "";
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
 
                $filename = $_FILES["file"]["tmp_name"];
 
                if ($_FILES['file']['size'] > 0) {
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    $n = 0;
                    $x = 0;
                    $param[$x]["param"] = array();
                    $arr = array();
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        if (empty($importdata[3])) {
                            $date = '';
                        } elseif ($importdata[3] == " ") {
                            $date = '';
                        } else {
                            $yeara = DateTime::createFromFormat('y', substr(!empty($importdata[3]) ? rtrim($importdata[3]) : '', 0, 2));
                            $year = $yeara->format('Y');
                            //var_dump($year);exit;
                            $month = substr(!empty($importdata[3]) ? rtrim($importdata[3]) : '', 2, 2);
                            $day = substr(!empty($importdata[3]) ? rtrim($importdata[3]) : '', 4, 2);
                            $date = $year . $month . $day;
                        }
 
                        $arr[] = array(
                            'tipe_produksi' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'type_marketing' => !empty($importdata[1]) ? rtrim($importdata[1]) : '',
                            'deskripsi' => !empty($importdata[2]) ? rtrim($importdata[2]) : '',
                            'last_effective' => $date,
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $n++;
                    }
 
                    // var_dump($arr);exit;
                    //API Url
                    $url = API_URL . "/api/sparepart/ptmbatch";
 
                    //Initiate cURL.
                    $ch = curl_init($url);
                    //Encode the array into JSON.
                    $jsonDataEncoded = (base64_encode(json_encode($arr))); //json_encode($arr);
                    //Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1);
                    //curl_setopt($ch, , 1);
                    //Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
                    //Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    //Execute the request
                    $hasilx = curl_exec($ch);
                    // var_dump($hasilx);exit();
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data =$hasilx;
        //var_dump($data);exit;
        $this->data_output($data, 'post', base_url('master_service/ptm'));
    }
 
    public function ptm_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/ptm"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->TIPE_PRODUKSI;
            $data_message[1][$key] = $message->TYPE_MARKETING;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    //PETD
    public function petd() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'CREATED_TIME desc'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/petd", $param));
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('report/petd/view', $data);
    }
 
    public function add_petd() {
        $this->auth->validate_authen('master_service/petd');
        $this->load->view('report/petd/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_petd() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
 
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
 
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    while (($importdata = fgetcsv($file, 1000000, ";")) !== FALSE) {
                        $tgl = $importdata[2];
                        $tglpomd_keahm = substr($tgl, 0, 2) . "/" . substr($tgl, 2, 2) . "/" . substr($tgl, 4, 4);
 
                        $tgl_2 = $importdata[8];
                        $etdahm_awal = substr($tgl_2, 0, 2) . "/" . substr($tgl_2, 2, 2) . "/" . substr($tgl_2, 4, 4);
 
                        $tgl_3 = $importdata[9];
                        $etdahm_revised = substr($tgl_3, 0, 2) . "/" . substr($tgl_3, 2, 2) . "/" . substr($tgl_3, 4, 4);
 
                        $param = array(
                            'kd_maindealer' => !empty($importdata[0]) ? $importdata[0] : '',
                            'nopomd_ke_ahm' => !empty($importdata[1]) ? $importdata[1] : '',
                            'tglpomd_keahm' => $tglpomd_keahm,
                            'kd_dealer' => !empty($importdata[3]) ? $importdata[3] : '',
                            'part_number' => !empty($importdata[4]) ? $importdata[4] : '',
                            'part_deskripsi' => !empty($importdata[5]) ? $importdata[5] : '',
                            'quantitypo_awal' => !empty($importdata[6]) ? $importdata[6] : '',
                            'quantitybo_ahm' => !empty($importdata[7]) ? $importdata[7] : '',
                            'etdahm_awal' => $etdahm_awal,
                            'etdahm_revised' => $etdahm_revised,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        //$this->db->trans_begin();
                        //$this->students_tbl_model->add($row);
                        if ($importdata[0] != null || $importdata[1] != null || $importdata[2] != null || $importdata[3] != null || $importdata[4] != null || $importdata[5] != null || $importdata[6] != null || $importdata[7] != null || $importdata[8] != null || $importdata[9] != null) {
                            $hasil = $this->curl->simple_post(API_URL . "/api/laporan/petd", $param, array(CURLOPT_BUFFERSIZE => 10));
                        }
                        //$data = json_decode($hasil);strtoupper(
                        //$this->session->set_flashdata('tr-active', $data->message);
                        //$this->data_output($hasil, 'post', base_url('motor/grup_motor'));
                    }
                    if ($hasil <= 0) {
                        //$this->db->trans_rollback();
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
 
//                        break;
                    } else {
                        //$this->db->trans_commit();
 
                        $response['status'] = 'success';
                        $response['message'] = 'Successfully added new record.';
                    }
                    fclose($file);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('master_service/petd'));
    }
 
    public function petd_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/petd"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_MAINDEALER;
            $data_message[1][$key] = $message->NOPMD_KE_AHM;
            $data_message[2][$key] = $message->KD_DEALER;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2]);
 
        $this->output->set_output(json_encode($result));
    }
 
    public function pdetd() {
        $data = array();
        $kd_ahm = KodeDealerAHM($this->session->userdata("kd_dealer"));
        //var_dump($kd_ahm);exit;
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'TRANS_PDETD.ID desc',
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdetd", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('report/pdetd/view', $data);
    }
 
    public function add_pdetd() {
        $this->auth->validate_authen('master_service/pdetd');
        $this->load->view('report/pdetd/add');
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_pdetd(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
 
        $kd_ahm = KodeDealerAHM($this->session->userdata("kd_dealer"));
 
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        }else{
            try {
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    while (($importdata = fgetcsv($file, 1000000, ";")) !== FALSE) {
                        $tgl = $importdata[2];
                        $tglpodealer_ke_md = substr($tgl, 0, 2) . "/" . substr($tgl, 2, 2) . "/" . substr($tgl, 4, 4);
                        //var_dump($tglpodealer_ke_md);exit;
 
                        $tgl_2 = $importdata[4];
                        $tglpomd_ke_ahm = substr($tgl_2, 0, 2) . "/" . substr($tgl_2, 2, 2) . "/" . substr($tgl_2, 4, 4);
 
                        $tgl_3 = $importdata[9];
                        $etdahm_awal = substr($tgl_3, 0, 2) . "/" . substr($tgl_3, 2, 2) . "/" . substr($tgl_3, 4, 4);
 
 
                        $tgl_4 = $importdata[10];
                        if($tgl_4 != '') {
                            $etdahm_revised = substr($tgl_4, 0, 2) . "/" . substr($tgl_4, 2, 2) . "/" . substr($tgl_4, 4, 4);
                        }else{
                            $etdahm_revised = $importdata[10];
                        }
 
                        $tgl_5 = $importdata[12];
                        $tglpesanan_konsumen = substr($tgl_5, 0, 2) . "/" . substr($tgl_5, 2, 2) . "/" . substr($tgl_5, 4, 4);
 
                        $param = array(
                            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                            'kd_dealer'     => $this->session->userdata("kd_dealer"),
                            'kd_dealerahm' => !empty($importdata[0]) ? $importdata[0] : '',
                            'no_podealer_ke_md' => !empty($importdata[1]) ? $importdata[1] : '',
                            'tglpodealer_ke_md' => $tglpodealer_ke_md,
                            'nopomd_ke_ahm' => !empty($importdata[3]) ? $importdata[3] : '',
                            'tglpomd_ke_ahm' => $tglpomd_ke_ahm,
                            'part_number' => !empty($importdata[5]) ? $importdata[5] : '',
                            'part_deskripsi' => !empty($importdata[6]) ? $importdata[6] : '',
                            'quantitypo_awal' => !empty($importdata[7]) ? $importdata[7] : '',
                            'quantitybo_ahm' => !empty($importdata[8]) ? $importdata[8] : '0',
                            'etdahm_awal' => $etdahm_awal,
                            'etdahm_revised' => $etdahm_revised,
                            'nopesanan_konsumen' => !empty($importdata[11]) ? $importdata[11] : '',
                            'tglpesanan_konsumen' => $tglpesanan_konsumen,
                            'nama_konsumen' => !empty($importdata[13]) ? $importdata[13] : '',
                            'notel_konsumen' => !empty($importdata[14]) ? $importdata[14] : '',
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $hasil = $this->curl->simple_post(API_URL . "/api/laporan/pdetd", $param, array(CURLOPT_BUFFERSIZE => 10));
                        $datax = json_decode($hasil);
                        if($datax->recordexists == TRUE){
                            $param["lastmodified_by"] = $this->session->userdata("user_id");
                            //var_dump($param);exit;
                            $hasil = ($this->curl->simple_put(API_URL . "/api/laporan/pdetd", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        }
                    }
 
 
                    if ($hasil <= 0) {
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
 
                    } else {
 
                        $response['status'] = 'success';
                        $response['message'] = 'Successfully added new record.';
                    }
                    fclose($file);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        //var_dump($param);exit;
        $data = json_decode($hasil);
        //var_dump($hasil);exit;
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('master_service/pdetd'));
    }
 
    public function pdetd_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdetd"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * setup_leadtime description]
     * @return [type] [description]
     */
 
    public function setup_leadtime() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=SETUP_LEADTIME.KD_DEALER", "LEFT"),
                array("MASTER_MAINDEALER MM", "MM.KD_MAINDEALER=SETUP_LEADTIME.KD_MAINDEALER", "LEFT"),
            ),
            'field' => 'SETUP_LEADTIME.*, MM.NAMA_MAINDEALER, MD.NAMA_DEALER',
            'orderby' => 'SETUP_LEADTIME.ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/leadtime", $param));
 
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
 
 
        $this->template->site('master_service/setup_leadtime/view', $data);
    }
 
    public function add_setup_leadtime() {
        $this->auth->validate_authen('master_service/setup_leadtime');
 
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
 
        $this->load->view('master_service/setup_leadtime/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_setup_leadtime_simpan() {
        $this->form_validation->set_rules('ahm_to_md', 'AHM ke MD', 'required|trim');
        $this->form_validation->set_rules('process_md', 'Proses di MD', 'required|trim');
        $this->form_validation->set_rules('md_to_dealer', 'MD ke Dealer', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'ahm_to_md' => $this->input->post("ahm_to_md"),
                'process_md' => $this->input->post("process_md"),
                'md_to_dealer' => $this->input->post("md_to_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/leadtime", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/setup_leadtime'));
        }
    }
 
    public function edit_setup_leadtime($id, $row_status) {
        $this->auth->validate_authen('master_service/setup_leadtime');
        $param = array(
            "custom" => "SETUP_LEADTIME.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/leadtime", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_service/setup_leadtime/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_setup_leadtime($id) {
        $this->form_validation->set_rules('ahm_to_md', 'AHM ke MD', 'required|trim');
        $this->form_validation->set_rules('process_md', 'Proses di MD', 'required|trim');
        $this->form_validation->set_rules('md_to_dealer', 'MD ke Dealer', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'ahm_to_md' => $this->input->post("ahm_to_md"),
                'process_md' => $this->input->post("process_md"),
                'md_to_dealer' => $this->input->post("md_to_dealer"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sparepart/leadtime", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_setup_leadtime($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sparepart/leadtime", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/setup_leadtime')
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
 
    public function setup_leadtime_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/sparepart/leadtime"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DEALER;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * setup_etd description]
     * @return [type] [description]
     */
 
    public function setup_etd() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_MAINDEALER MM", "MM.KD_MAINDEALER=SETUP_ETDAHM.KD_MAINDEALER", "LEFT"),
            ),
            'field' => 'SETUP_ETDAHM.*, MM.NAMA_MAINDEALER', 
            'orderby' => 'SETUP_ETDAHM.ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/etdahm", $param));
 
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master_service/setup_etd/view', $data);
    }
 
    public function add_setup_etd() {
        $this->auth->validate_authen('master_service/setup_etd');
 
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
 
        $this->load->view('master_service/setup_etd/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_setup_etd_simpan() {
        $this->form_validation->set_rules('kategori_part', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('sifat_part', 'Sifat', 'required|trim');
        $this->form_validation->set_rules('etd', 'ETD', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'sifat_part' => $this->input->post("sifat_part"),
                'kategori_part' => $this->input->post("kategori_part"),
                'etd' => $this->input->post("etd"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/etdahm", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/setup_etd'));
        }
    }
 
    public function edit_setup_etd($id, $row_status) {
        $this->auth->validate_authen('master_service/setup_etd');
        $param = array(
            "custom" => "SETUP_ETDAHM.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/etdahm", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('master_service/setup_etd/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_setup_etd($id) {
        $this->form_validation->set_rules('kategori_part', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('sifat_part', 'Sifat', 'required|trim');
        $this->form_validation->set_rules('etd', 'ETD', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'sifat_part' => $this->input->post("sifat_part"),
                'kategori_part' => $this->input->post("kategori_part"),
                'etd' => $this->input->post("etd"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
 
            $hasil = $this->curl->simple_put(API_URL . "/api/sparepart/etdahm", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_setup_etd($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sparepart/etdahm", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/setup_etd')
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
 
    public function setup_etd_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/sparepart/etdahm"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->SIFAT_PART;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * setup_etd description]
     * @return [type] [description]
     */
 
    public function part_eta() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("SETUP_ETDAHM AS SE", "SE.SIFAT_PART=MASTER_PART.PART_SOURCE AND SE.KATEGORI_PART=MASTER_PART.PART_CURRENT", "LEFT"),
                array("SETUP_LEADTIME AS SL", "SL.KD_DEALER='" . $this->session->userdata("kd_dealer") . "'", "LEFT")
            ),
            'field' => 'MASTER_PART.*, SE.*, SL.*',
            'orderby' => 'MASTER_PART.ID DESC',
            "custom" => "SE.ROW_STATUS=0 AND SL.ROW_STATUS=0"
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/parteta", $param));
 
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master_service/part_eta/view', $data);
    }
 
    public function part_eta_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/sparepart/part"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->PART_DESKRIPSI;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    /*     * mekanik description]
     * @return [type] [description]
     */
 
    public function mekanik() {
        $data = array();
 
        $cek = array(
            'field' => 'MASTER_DEALER.*',
            "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
 
        $data['user'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true/true", $cek));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' =>($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer")
        );
 
        /**
         * edited by iswan putera on 25-05-2018
         * 
         * @var [type]
         */
        //var_dump($data['user']);exit;
        $jenisdealer = $data['user']->message[0]->KD_JENISDEALER;
 
        if ($jenisdealer == 'Y') {
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $param));
        } else {
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $param));
        }
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master_service/mekanik/view', $data);
    }

    public function training_mekanik($nik) {
        $this->auth->validate_authen('master_service/mekanik');
        $param = array(
            //'kd_detailpromo' => $kd_promo,
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'TRANS_TRAINING_RECORD.ID DESC',
            "custom" => "TRANS_TRAINING_RECORD.NIK='" . $nik . "'"
        );
 
        $param_cek = array(
            "custom" => "MASTER_KARYAWAN.NIK='" . $nik . "'"
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/training_record", $param));
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param_cek));  
        //var_dump($data["cek"]);exit;    
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/mekanik/view_training', $data);
    }

    public function add_training($nik) {
        $this->auth->validate_authen('master_service/mekanik');
        $data = array();
        $param = array(
            "custom" => "MASTER_KARYAWAN.NIK='" . $nik . "'"
        );
 
        $param_user = array(
            "custom" => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master/training"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        //var_dump($data["status"]);exit;  
 
        $this->load->view('master_service/mekanik/add_training', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }

    public function add_training_simpan() {

        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('status_training', 'Status', 'required|trim');
        $this->form_validation->set_rules('tgl_training', 'Tanggal', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            //$akun = substr($this->input->post("kd_akun"), 0, strpos($this->input->post("kd_akun"), ' '));
            $param = array(
                'nik' => $this->input->post("nik"),
                'nama_training' => $this->input->post("nama_training"),
                'status_training' => $this->input->post("status_training"),
                'tgl_training' => $this->input->post("tgl_training"),
                'durasi' => $this->input->post("durasi"),
                'lokasi' => $this->input->post("lokasi"),
                'pembicara' => $this->input->post("pembicara"),
                'materi' => $this->input->post("materi"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $param_cek = array(
                "custom" => "MASTER_MEKANIK_VIEW.NIK='" . $this->input->post("nik") . "'"
            );
            $data = array();

            $data["tot"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $param_cek));

            //var_dump($data["tot"]->totaldata);exit;

            if($data["tot"]->totaldata <= 0){
                $param_mek = array(
                    'kd_maindealer' => $this->input->post("kd_maindealer"),
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'nik' => $this->input->post("nik"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );
 
            $hasil_mek = $this->curl->simple_post(API_URL . "/api/master_service/mekanik", $param_mek, array(CURLOPT_BUFFERSIZE => 10));

            }

            //var_dump($param);exit;

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/service/training_record", $param, array(CURLOPT_BUFFERSIZE => 10));

            //var_dump($hasil);exit;

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('master_service/training_mekanik/'.$this->input->post("nik")));
            //redirect(base_url('finance/perkiraan'));
        }
    }

    public function update_training($id) {
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('status_training', 'Status', 'required|trim');
        $this->form_validation->set_rules('tgl_training', 'Tanggal', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'id' => $this->input->post("id"),
                'nik' => $this->input->post("nik"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'nama_training' => $this->input->post("nama_training"),
                'status_training' => $this->input->post("status_training"),
                'tgl_training' => $this->input->post("tgl_training"),
                'durasi' => $this->input->post("durasi"),
                'lokasi' => $this->input->post("lokasi"),
                'pembicara' => $this->input->post("pembicara"),
                'materi' => $this->input->post("materi"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            //var_dump($param);exit;
            
            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_put(API_URL . "/api/service/training_record", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function edit_training($id, $row_status) {
        $this->auth->validate_authen('master_service/mekanik');
        $param = array(
            "custom" => "TRANS_TRAINING_RECORD.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/training_record", $param));
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master/training"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        //var_dump($data["list"]);exit;
 
        $this->load->view('master_service/mekanik/edit_training', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function delete_training($id,$nik) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/service/training_record", $param));

        $this->data_output($data, 'delete', base_url('master_service/training_mekanik/'.$nik));
    }

    public function training_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/service/training_record"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NIK;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }

 
    public function add_mekanik() {
        $this->auth->validate_authen('master_service/mekanik');
 
        $data = array();
 
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $kary_dealer = array(
            "custom" => "MASTER_KARYAWAN.KD_CABANG='" . $this->session->userdata('kd_dealer') . "' AND MASTER_KARYAWAN.PERSONAL_JABATAN='Mekanik' AND MASTER_KARYAWAN.KD_STATUS IN('STS-1','STS-2','STS-3')"
        );
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $kary_dealer));
        $data["tipe_pkb"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb"));
        $cek = array(
            'field' => 'MASTER_DEALER.*',
            "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
 
 
        $data['user'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $cek));
 
        $this->load->view('master_service/mekanik/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function add_mekanik_simpan() {
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("tipe_pkb") != null) {
                $tipe_pkb = implode(", ", $this->input->post("tipe_pkb"));
            } else {
                $tipe_pkb = "";
            }
 
 
            $param = array(
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'nik' => $this->input->post("nik"),
                'nama_mekanik' => $this->input->post("nama_mekanik"),
                'honda_id' => $this->input->post("honda_id"),
                'tipe_pkb' => $tipe_pkb,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
 
            $hasil = $this->curl->simple_post(API_URL . "/api/master_service/mekanik", $param, array(CURLOPT_BUFFERSIZE => 10));
 
 
            $data = json_decode($hasil);
            //var_dump($hasil, $data);exit;
            /* if($data->recordexists == false){
              $this->session->set_flashdata('tr-active', $data->message);
 
              $this->data_output($hasil, 'post', base_url('master_service/mekanik'));
              }else{
              $this->session->set_flashdata('tr-active', $data->message);
              $hasilx="";
 
              $this->data_output($hasilx, 'post', base_url('master_service/mekanik'));
              } */
 
            if ($hasil) {
                if ($data->recordexists == false) {
                    $data_status = array(
                        'status' => true,
                        'message' => 'Data berhasil ditambahkan',
                        'location' => base_url('master_service/mekanik')
                    );
                } else {
                    $data_status = array(
                        'status' => true,
                        'message' => 'Data sudah ada',
                        'location' => base_url('master_service/mekanik')
                    );
                }
 
 
                $this->output->set_output(json_encode($data_status));
            } else {
                $data_status = array(
                    'status' => false,
                    'message' => 'Data gagal ditambahkan',
                );
 
                $this->output->set_output(json_encode($data_status));
            }
        }
    }
 
    public function edit_mekanik($id, $row_status) {
        $this->auth->validate_authen('master_service/mekanik');
        $param = array(
            "nik" =>  $id,
            'row_status' => $row_status
        );
        $kd_dlr=$this->session->userdata("kd_dealer");
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $param));
        if($data["list"]){
            if($data["list"]->totaldata>0){
                foreach ($data["list"]->message as $key => $value) {
                    $kd_dealer=$value->KD_DEALER;
                }
            }
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $kary_dealer = array(
            "field" => "NIK,NAMA_MEKANIK",
            "kd_dealer" => $kd_dlr
        );
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $kary_dealer));
        $data["tipe_pkb"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb"));
        $cek = array(
            'field' => 'MASTER_DEALER.*',
            "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
 
 
        $data['user'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $cek));
 
        
        //var_dump($data["list"]);exit;
        $this->load->view('master_service/mekanik/edit', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
    
    /**
     * [update_mekanik description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     * updated on 25-05-2018
     */
    public function update_mekanik($id) {
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
             $this->output->set_output(json_encode($data));
        } else {
            $tipe_pkb = implode(', ', $this->input->post("tipe_pkb"));
             $param = array(
                'id' =>'0',// $this->input->post("id"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'nik' => $this->input->post("nik"),
                'nama_mekanik' => $this->input->post("nama_mekanik"),
                'honda_id' => $this->input->post("honda_id"),
                'tipe_pkb' => $tipe_pkb,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_service/mekanik", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data=json_decode($hasil);
            if($data){
                if($data->recordexists==false){
                    $hasil = $this->curl->simple_post(API_URL . "/api/master_service/mekanik", $param, array(CURLOPT_BUFFERSIZE => 10));
                }
            }
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_mekanik($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_service/mekanik", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/mekanik')
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
 
    public function mekanik_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/master_service/mekanik"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NIK;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
 
    public function barang_summary() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_BARANG AS MB", "MB.KD_BARANG=TRANS_PART_HARGAJUAL.PART_NUMBER", "LEFT"),
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=TRANS_PART_HARGAJUAL.KD_DEALER", "LEFT"),
                array("SETUP_TYPECUSTOMER AS MT", "MT.KD_TYPECUSTOMER=TRANS_PART_HARGAJUAL.KD_TYPECUSTOMER", "LEFT"),
            ),
            'field' => 'TRANS_PART_HARGAJUAL.*, MB.NAMA_BARANG, MB.KATEGORI as KATEGORI_BARANG, MD.NAMA_DEALER, MT.NAMA_TYPECUSTOMER',
            'orderby' => 'TRANS_PART_HARGAJUAL.END_DATE desc',
            "custom" => "TRANS_PART_HARGAJUAL.KD_DEALER='" . $this->session->userdata('kd_dealer') . "' AND TRANS_PART_HARGAJUAL.KATEGORI='Barang'"
        );
 
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/barangsummary/view', $data);
    }
 
    public function add_barang_summary() {
        $this->auth->validate_authen('master_service/barang_summary');
 
        $param = array('kd_dealer' => $this->session->userdata("kd_dealer"));
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang_summary", $param));
        // $data["barangs"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["hargaparts"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual"));
 
        $this->load->view('master_service/barangsummary/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function simpan_barang_summary() {
        //$this->form_validation->set_rules('id', 'ID Part', 'required|trim');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|trim');
        //$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|trim');
        //$this->form_validation->set_rules('diskon', 'Diskon', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id_part' => $this->input->post("id_part"),
                'harga_beli' => $this->input->post("harga_beli"),
                'harga_jual' => $this->input->post("harga_jual"),
                'diskon' => 0,
                'stock' => NULL,
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/inventori/barang_summary", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            //var_dump($hasil);exit;
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('master_service/barang_summary'));
        }
    }
 
    public function edit_barang_summary($id, $row_status) {
        $this->auth->validate_authen('master_service/barang_summary');
        $param = array(
            "custom" => "MASTER_BARANG_SUMMARY.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang_summary", $param));
        //$data["barangs"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));
        $data["maindealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        //$data["hargaparts"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual"));
        $this->load->view('master_service/barangsummary/edit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
 
    public function update_barang_summary($id) {
 
        //$this->form_validation->set_rules('id', 'ID Part', 'required|trim');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'id_part' => $this->input->post("id_part"),
                'harga_beli' => $this->input->post("harga_beli"),
                'harga_jual' => $this->input->post("harga_jual"),
                'diskon' => null,
                'stock' => null,
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
 
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/barang_summary", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_barang_summary($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/barang_summary", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('master_service/barang_summary')
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
 
    public function barang_summary_typeahead() {
        $param = array(
            'jointable' => array(
                array("MASTER_BARANG AS MB", "MB.KD_BARANG=TRANS_PART_HARGAJUAL.PART_NUMBER", "LEFT"),
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=TRANS_PART_HARGAJUAL.KD_DEALER", "LEFT"),
                array("SETUP_TYPECUSTOMER AS MT", "MT.KD_TYPECUSTOMER=TRANS_PART_HARGAJUAL.KD_TYPECUSTOMER", "LEFT"),
            ),
            'field' => 'TRANS_PART_HARGAJUAL.*, MB.NAMA_BARANG, MB.KATEGORI as KATEGORI_BARANG, MD.NAMA_DEALER, MT.NAMA_TYPECUSTOMER',
            'orderby' => 'TRANS_PART_HARGAJUAL.END_DATE desc',
            "custom" => "TRANS_PART_HARGAJUAL.KD_DEALER='" . $this->session->userdata('kd_dealer') . "' AND TRANS_PART_HARGAJUAL.KATEGORI='Barang'"
        );
 
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual", $param));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->NAMA_BARANG;
        }
 
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }
 
    public function uminv() {
        $data = array();
        $kd_ahm = KodeDealerAHM($this->session->userdata("kd_dealer"));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => $this->input->get('kd_dealer'),
            'orderby' => 'TRANS_UMINV.ID desc',
            "custom" => "TRANS_UMINV.KD_DEALER='".$kd_ahm."'"
 
        );
 
        /*$data = array();*/
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/uminv", $param));
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/uminv/view', $data);
    }
 
    public function add_uminv() {
        $this->auth->validate_authen('master_service/uminv');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/uminv"));
        $this->load->view('master_service/uminv/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_uminv() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');

        $kd_ahm = KodeDealerAHM($this->session->userdata("kd_dealer"));
 
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
 
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        $tgl = $importdata[1];
                        $tgl_inv = substr($tgl, 0, 2) . "/" . substr($tgl, 2, 2) . "/" . substr($tgl, 4, 4);
                        //var_dump($tglpodealer_ke_md);exit;
                        $param = array(
                            'no_inv' => !empty($importdata[0]) ? $importdata[0] : '',
                            'tgl_inv' => $tgl_inv,
                            'kd_tipe' => !empty($importdata[2]) ? $importdata[2] : '',
                            'kd_warna' => !empty($importdata[3]) ? $importdata[3] : '',
                            'kd_dealer' => !empty($importdata[4]) ? $importdata[4] : '',
                            'kd_cabangdealer' => !empty($importdata[5]) ? $importdata[5] : '',
                            'qty' => !empty($importdata[6]) ? $importdata[6] : '0',
                            'amount' => !empty($importdata[7]) ? $importdata[7] : '0',
                            'mppn' => !empty($importdata[8]) ? $importdata[8] : '0',
                            'mprice' => !empty($importdata[9]) ? $importdata[9] : '0',
                            'mdiscount' => !empty($importdata[10]) ? $importdata[10] : '0',
                            'no_reff' => !empty($importdata[11]) ? $importdata[11] : '',
                            'kd_maindealer' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        //var_dump($param); exit();
                        if($importdata[0] != null) {
                            if($importdata[5] == $this->session->userdata("kd_dealer")){
                                $hasil = $this->curl->simple_post(API_URL . "/api/laporan/uminv", $param, array(CURLOPT_BUFFERSIZE => 10));
                                //var_dump($hasil); exit();
                                $datax = json_decode($hasil);
                                if($datax->recordexists == TRUE){
                                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                                    /*var_dump($param);exit;*/
                                    $hasil = $this->curl->simple_put(API_URL . "/api/laporan/uminv", $param, array(CURLOPT_BUFFERSIZE => 10));
                                }
                            }
                        }
                    }
 
 
                    if ($hasil <= 0) {
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
 
                    } else {
 
                        $response['status'] = 'success';
                        $response['message'] = 'Successfully added new record.';
                    }
                    fclose($file);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('master_service/uminv'));
    }
 
    public function uminv_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/uminv"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_INV;
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }
 
    public function historyservice() {     
        $data = array();
        /*$kd_ahm = KodeDealerAHM($this->session->userdata("kd_dealer"));*/

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => $this->input->get('kd_dealer'),  
            'orderby' => 'TRANS_HISTORYSERVICE.ID desc',
             "custom" => "TRANS_HISTORYSERVICE.KD_DEALER='".$this->session->userdata("kd_dealer")."'",
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/historyservice", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
       /* var_dump($data);*/
        $this->template->site('master_service/historyservice/view', $data);
    }
 
    public function add_historyservice() {
        $this->auth->validate_authen('master_service/historyservice');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/historyservice"));
        $this->load->view('master_service/historyservice/add', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function import_historiservice() {
        
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');

        /*$kd_ahm = KodeDealerAHM($this->session->userdata("kd_dealer"));*/
 
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        }else{
            try {
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES['file']['size'] > 0) {
                   /* $hasil="Berhasil";*/
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        $tgl = $importdata[0];
                        $tgl_trans = substr($tgl, 0, 2) . "/" . substr($tgl, 2, 2) . "/" . substr($tgl, 4, 4);
                        $param = array(
                            'tgl_trans' => $tgl_trans,
                            'flag_jp' => !empty($importdata[1]) ? rtrim($importdata[1]) : '',
                            'nama_customer' => !empty($importdata[2]) ? rtrim($importdata[2]) : '',
                            'no_mesin' => !empty($importdata[3]) ? rtrim($importdata[3]) : '',
                            'no_rangka' => !empty($importdata[4]) ? rtrim($importdata[4]) : '',
                            'no_polisi' => !empty($importdata[5]) ? rtrim($importdata[5]) : '',
                            'tipe_pkb' => !empty($importdata[6]) ? rtrim($importdata[6]) : '0',
                            'problem' => !empty($importdata[7]) ? rtrim($importdata[7]) : '0',
                            'id_job' => !empty($importdata[8]) ? rtrim($importdata[8]) : '0',
                            'keterangan_job' => !empty($importdata[9]) ? rtrim($importdata[9]) : '0',
                            'part_number' => !empty($importdata[10]) ? rtrim($importdata[10]) : '0',
                            'qty' => !empty($importdata[11]) ? rtrim($importdata[11]) : '',
                            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                            'kd_dealer' => $this->session->userdata('kd_dealer'),
                            'created_by' => $this->session->userdata('user_id')
                        );
 
                        if ($importdata[2] != null || $importdata[3] != null || $importdata[4] != null || $importdata[5] != null) {
                                $hasil = $this->curl->simple_post(API_URL . "/api/laporan/historyservice", $param, array(CURLOPT_BUFFERSIZE => 10));
                                //var_dump($hasil); exit();
                                $datax = json_decode($hasil);
                                if($datax->recordexists == TRUE){
                                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                                    /*var_dump($param);exit;*/
                                    $hasil = $this->curl->simple_put(API_URL . "/api/laporan/historyservice", $param, array(CURLOPT_BUFFERSIZE => 10));
                                }
                            }
                        }
                    
                    //var_dump($hasil); exit;
                    if ($hasil <= 0) {
                        //$this->db->trans_rollback();
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
 
//                        break;
                    } else {
                        //$this->db->trans_commit();
                        $response['status'] = 'success';
                        $response['message'] = 'Successfully added new record.';
                    }
                    fclose($file);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('master_service/historyservice'));
    }
 
    public function historiservice_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/historyservice"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_CUSTOMER;
            $data_message[1][$key] = $message->NO_MESIN;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
                                                     
    public function harga_claim() {     
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            //'kd_dealer' => $this->input->get('kd_dealer'),
            'jointable' => array(
                array("MASTER_MAINDEALER AS MM", "TRANS_KPB_CLAIM_LISTHARGA.KD_MAINDEALER=MM.KD_MAINDEALER", "LEFT"),
                array("MASTER_DEALER AS MD ", "TRANS_KPB_CLAIM_LISTHARGA.KD_DEALER=MD.KD_DEALER", "LEFT")
            ),
            'field' => 'TRANS_KPB_CLAIM_LISTHARGA.*, MD.NAMA_DEALER, MM.NAMA_MAINDEALER',
            'orderby' => 'TRANS_KPB_CLAIM_LISTHARGA.ID desc'
            //"custom" => "TRANS_KPB_CLAIM_LISTHARGA.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_claim_listharga", $param));       
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
        $this->template->site('master_service/harga_claim/view', $data);
    }

    public function add_harga_claim() {
        $data = array();

        $param = array(
            'row_status' => 0
        );

        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL. "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));

        $this->load->view('master_service/harga_claim/add', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    function add_harga_claim_simpan() {
        $this->form_validation->set_rules('no_mesin', 'Nomor Mesin', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

             $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'no_mesin' => $this->input->post("no_mesin"),
                'motor_kpb' => $this->input->post('motor_kpb'),
                'inisial' => $this->input->post('inisial'),
                'kd_kpb' => $this->input->post('kd_kpb'),
                'service' => $this->input->post('service'),
                'nominal_jasa' => $this->input->post('nominal_jasa'),
                'isi_oli' => $this->input->post("isi_oli"),
                'harga_oli' => $this->input->post('harga_oli'),
                'no_part_oli' => $this->input->post('no_part_oli'),
                'no_part_oli2' => $this->input->post('no_part_oli2'),
                'isi_oli_2' => $this->input->post("isi_oli_2"),
                'harga_oli_2' => $this->input->post('harga_oli_2'),
                'no_part_oli_1' => $this->input->post('no_part_oli_1'),
                'no_part_oli_2' => $this->input->post('no_part_oli2'),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/service/kpb_claim_listharga", $param, array(CURLOPT_BUFFERSIZE => 10));
           
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('master_service/harga_claim'));
        }
    }

    public function edit_harga_claim($id, $row_status) {
        $this->auth->validate_authen('master_service/harga_claim');
        $param = array(
            "custom" => "ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_claim_listharga", $param));

        $this->load->view('master_service/harga_claim/edit', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_harga_claim($id) {
        $this->form_validation->set_rules('no_mesin', 'Nomor Mesin', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validate_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'no_mesin' => $this->input->post("no_mesin"),
                'motor_kpb' => $this->input->post('motor_kpb'),
                'inisial' => $this->input->post('inisial'),
                'kd_kpb' => $this->input->post('kd_kpb'),
                'service' => $this->input->post('service'),
                'nominal_jasa' => $this->input->post('nominal_jasa'),
                'isi_oli' => $this->input->post("isi_oli"),
                'harga_oli' => $this->input->post('harga_oli'),
                'no_part_oli' => $this->input->post('no_part_oli'),
                'no_part_oli2' => $this->input->post('no_part_oli2'),
                'isi_oli_2' => $this->input->post("isi_oli_2"),
                'harga_oli_2' => $this->input->post('harga_oli_2'),
                'no_part_oli_1' => $this->input->post('no_part_oli_1'),
                'no_part_oli_2' => $this->input->post('no_part_oli2'),
                'row_status' => $this->input->post('row_status'),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/service/kpb_claim_listharga", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_harga_claim($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/service/kpb_claim_listharga", $param));

        $this->data_output($data, 'delete', base_url('master_service/harga_claim'));
    }

    public function harga_claim_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_claim_listharga"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_MESIN;
            $data_message[1][$key] = $message->MOTOR_KPB;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }



    public function histori_service_nopol()
    {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PKB.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PKB.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'no_polisi' => $this->input->get('no_polisi'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "TRANS_PKB.NAMA_MEKANIK=MK.NIK", "LEFT"),
                array("TRANS_CSA AS CSA", "CSA.NO_MESIN=TRANS_PKB.NO_MESIN", "LEFT"),
            ),
            'custom' => $kd_dealer,
            'field' => 'TRANS_PKB.*, MK.NAMA, CSA.NAMA_PEMILIK, CSA.KEBUTUHAN_KONSUMEN',
            'orderby' => 'TRANS_PKB.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $param_detail = array(
            'no_polisi' => $this->input->get('no_polisi'),
            'jointable' => array(
                array("TRANS_PKB_DETAIL AS TPD", "TRANS_PKB.NO_PKB=TPD.NO_PKB", "LEFT"),
            ),
            'custom' => $kd_dealer,
            'field' => "TPD.*,
                    CASE WHEN TPD.KATEGORI='Part' 
                    THEN (SELECT S.PART_DESKRIPSI FROM MASTER_PART as S WHERE S.PART_NUMBER=TPD.KD_PEKERJAAN)
                    ELSE (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END PART_DESKRIPSI",
            'orderby' => 'TPD.KATEGORI asc'
        );
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param_detail));
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
        $this->template->site('master_service/historiservicenopol/view', $data);
    }
    public function histori_service_group()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PKB.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PKB.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'custom' => $kd_dealer,
            'field' => 'NO_POLISI, NO_MESIN',
            'orderby' => 'NO_POLISI desc',
            'groupby' => true
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->output->set_output(json_encode($data["list"]->message));
    }
    public function jasa_vsmotor($ajax=true,$onlyTypeMotor=null){
        $param=array();$result=array();

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $param=array(
            'custom' => "MD.KD_DEALER = '".$kd_dealer."'",
            'jointable' =>array(
                array("MASTER_DEALER MD" , "MD.KATEGORI_DEALER=JASA_VS_TYPEMOTOR.KATEGORI AND MD.ROW_STATUS>=0", "LEFT"),
            ),
            'field' =>'JASA_VS_TYPEMOTOR.*, MD.KD_DEALER',
        );

        if($this->input->get("kd_typemotor")){
            $param["kd_typemotor"]=$this->input->get("kd_typemotor");
        }
        if($this->input->get("kd_jasa")){
            $param["kd_jasa"] = $this->input->get("kd_jasa");
        }
        if($onlyTypeMotor==true){
            $param=array(
                'field' =>'KD_TYPEMOTOR,NAMA_GROUPMOTOR',
                'groupby' => TRUE
            );
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa_vs_typemotor",$param));
        //var_dump($data);exit();
        if($data){
            if($data->totaldata>0){
                $result = $data->message;
            }
        }
        if($ajax==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }

    public function returjualbeli() {
        $data = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            'keyword' => $this->input->get('keyword'),
            'tgl_awal' => $this->input->get("tgl_awal"),
            'tgl_akhir' => $this->input->get("tgl_akhir"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID desc',
            'field' => 'TRANS_RETUR_JUALBELI.*',
            'orderby' => 'TRANS_RETUR_JUALBELI.ID DESC',
            'limit' => 15
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }

        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli", $param));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang/true"));
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


        $this->template->site('master_service/returjualbeli/view', $data); //, $data
    }

    /**$
     * load form inputan guestbook
     */
    public function add_retur() {
        $data = array();
        $param = array();

        if ($this->session->userdata("nama_group") != 'Root') {
            $param['kd_dealer'] = $this->session->userdata("kd_dealer");
        }

        $data["tipepkb"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb"));
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $paramdealer = array(
            'field' => "KD_LOKASI, NAMA_LOKASI",
            'groupby' => TRUE
        );
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang/true",$paramdealer));
        $this->template->site('sales/service_advisor', $data);
    }

    public function simpan_service_advisor() {
        $this->form_validation->set_rules('jenis_retur', 'No STNK', 'required|trim');
        
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $hasil = "";
            $data = array();
            $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_trans('RJB'));
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_lokasidealer' => $this->input->post("kd_lokasidealer"),
                'no_trans' => $ntrans,
                'tgl_trans' => $this->input->post("tgl_trans"),
                'jenis_retur' => $this->input->post("jenis_retur"),
                'status_retur' => $this->input->post("status_retur"),
                'no_reff' => $this->input->post("no_reff"),
                'tgl_reff' => $this->input->post("tgl_reff"),
                'keterangan' => $this->input->post("keterangan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
 
//            var_dump($param);
//            exit();
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/retur_jualbeli", $param, array(CURLOPT_BUFFERSIZE => 100));

            $this->data_output($hasil, 'post', base_url('master_service/returjualbeli')); // 
        }
    }   

     function autogenerate_trans($kd_docno) {
        $no_trans = "";
        $nomortrans = 0;
        $param = array(
            'kd_docno' => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),// substr($this->input->post('tgl_trans'), 6, 4),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = date('m');// substr($this->input->post('tgl_trans'), 3, 2);
        $nomortrans = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomortrans == 0) {
            $no_trans = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomortrans = $nomortrans + 1;
            $no_trans = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomortrans, 5, '0', STR_PAD_LEFT);
        }
        return $no_trans;
    }

    public function rangenopajak() {
        $data = array();

        $kd_dealer = $this->input->get('kd_dealer')?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'kd_dealer' => $kd_dealer,
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MASTER_RANGE_NOPAJAK.KD_DEALER=MD.KD_DEALER", "LEFT")
            ),
            'field' => 'MASTER_RANGE_NOPAJAK.*, MD.NAMA_DEALER',
            'orderby' => 'MASTER_RANGE_NOPAJAK.ID desc',
            "custom" => "MASTER_RANGE_NOPAJAK.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );  
    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/range_nopajak", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master_service/rangenopajak/view', $data);
    }

    public function add_rangenopajak() {
        $this->auth->validate_authen("master_service/rangenopajak");
        
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL. "/api/master/dealer",listDealer()));

        $this->load->view('master_service/rangenopajak/add', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    function get_serial_nopajak(){
        $result="[]";
        $param=array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'range_seri'=> $this->input->get('prefix')
        );
        $data = json_decode($this->curl->simple_get(API_URL."/api/accounting/range_seripajak",$param));
        if($data){
            if($data->totaldata > 0){
                $result = json_encode($data->message);
            }
        }
        echo $result;
    }
    public function range_check1($range1) { 
        $subRange1 = substr($range1,0,6);
        $subRange2 = substr($this->input->post('range2'),0,6);

        if($subRange1 == $subRange2) {
            return TRUE;
        } else {
            $this->form_validation->set_message('range_check1', 'Range tidak sesuai');
            return FALSE;
        }
    }

    function check_equal_less($range2,$range1) { 
        if ($range2 <= $range1) { 
            $this->form_validation->set_message('check_equal_less', 'Range 1 harus lebih kecil dari range 2.'); 
            return false; 
        } else { return true; } 
    }
    
    public function rangenopajak_simpan() {
        $this->form_validation->set_rules('range1', 'Range 1', 'required|callback_range_check1');
        $this->form_validation->set_rules('range2', 'Range 2', 'required|callback_check_equal_less['.$this->input->post('range1').']');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->input->post('kd_dealer'),
                'range1' => $this->input->post('range1'),
                'range2' => $this->input->post('range2'),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/range_nopajak", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('master_service/rangenopajak'));
        }
    }

    public function edit_rangenopajak($id, $row_status) {
        $this->auth->validate_authen('master_service/rangenopajak');
        $param = array(
            "custom" => "ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL. "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/range_nopajak", $param));

        $this->load->view("master_service/rangenopajak/edit", $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    
    public function range_check2($range1) {
        $subRange1 = substr($range1,0,6);
        $subRange2 = substr($this->input->post('range2'),0,6);

        if($subRange1 == $subRange2) {
            return TRUE;
        } else {
            $this->form_validation->set_message('range_check2', 'Range tidak sesuai');
            return FALSE;
        }
    }

    public function update_rangenopajak($id) {
        $this->form_validation->set_rules('range1', 'Range 1', 'required|callback_range_check2');
        $this->form_validation->set_rules('range2', 'Range 2', 'required|callback_check_equal_less['.$this->input->post('range1').']');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()    
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post('id'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'range1' => $this->input->post('range1'),
                'range2' => $this->input->post('range2'),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')                
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/accounting/range_nopajak", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_rangenopajak($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/accounting/range_nopajak", $param));

        $this->data_output($data, 'delete', base_url('master_service/rangenopajak'));
    }


    public function history_cust()
    {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_HISTORY_UNIT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_HISTORY_UNIT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MP", "TRANS_HISTORY_UNIT.KD_TYPEMOTOR=MP.KD_TYPEMOTOR AND TRANS_HISTORY_UNIT.KD_WARNA = MP.KD_WARNA", "LEFT"),
                array("MASTER_SALESMAN AS MS", "MS.KD_SALES=TRANS_HISTORY_UNIT.KD_SALES", "LEFT"),
            ),
            'custom' => $kd_dealer,
            'field' => 'TRANS_HISTORY_UNIT.*, MP.NAMA_TYPEMOTOR, MP.KET_WARNA, MS.NAMA_SALES',
            //'orderby' => 'TRANS_HISTORY_UNIT.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/history_unit", $param));

        //var_dump($data);
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
        $this->template->site('master_service/history_cust', $data);
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
 
}