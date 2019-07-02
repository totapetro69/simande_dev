<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_inspen extends CI_Controller {

    var $API;

    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('dompdf_gen');
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

    public function laporan_kops() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
        );
        $data["list"]=array();
        //$data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('report_inspen/laporan_kops/view_kops', $data);
    }

    public function laporan_ksp() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "MK.NIK = TRANS_INSENTIF_KSP.NIK", "LEFT")
            ),
            'limit' => 15
        );

        
        //print_r($param);
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentif_ksp", $param));
        //var_dump($data["list"]);exit();
        //$data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('report_inspen/laporan_ksp/view_ksp', $data);
    }

    public function edit_ksp() {
        $this->auth->validate_authen('report_inspen/laporan_ksp');
        $param = array(
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentif_ksp", $param));

        $this->load->view('report_inspen/laporan_ksp/edit_ksp', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_ksp($id) {
        $this->form_validation->set_rules('no_trans', 'No Trans', 'required|trim');
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
        } else {
            $param = array(
                'no_trans' => $this->input->post("no_trans"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'nik' => $this->input->post("nik"),
                'periode_awal' => $this->input->post("periode_awal"),
                'periode_akhir' => $this->input->post("periode_akhir"),
                'tgl_pengajuan' => $this->input->post("tgl_pengajuan"),
                'total_sales' => $this->input->post("total_sales"),
                'sales_tambah' => $this->input->post("sales_tambah"),
                'sales_kurang' => $this->input->post("sales_kurang"),
                'rpk' => $this->input->post("rpk"),
                'margin_unit' => $this->input->post('margin_unit'),
                'insentif_unit' => $this->input->post('insentif_unit'),
                'total_insentif' => $this->input->post('total_insentif'),
                'penalty' => $this->input->post('penalty'),
                'pph21' => $this->input->post('pph21'),
                'insentif_terima' => $this->input->post('insentif_terima')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/laporan/insentif_ksp", $param, array(CURLOPT_BUFFERSIZE => 10));
            
            $this->session->set_flashdata('tr-active', $id);
            
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_ksp($no_trans) {
        $param = array(
            'no_trans' => $no_trans,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/laporan/insentif_ksp", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('report_inspen/laporan_ksp')
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

    public function cetak_insentif_ksp() {
        $data = array();
        $this->auth->validate_authen('report_inspen/laporan_ksp');
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentif_ksp", $param));
        
        $this->load->view('report_inspen/laporan_ksp/print_ksp_rekap', $data);

        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function cetak_penalty_ksp() {
        $data = array();
        $this->auth->validate_authen('report_inspen/laporan_ksp');
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentif_ksp", $param));

        $this->load->view('report_inspen/laporan_ksp/print_ksp_penalty', $data);

        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function exclude_ksp() {
        
    }

    public function laporan_kepalacounter() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
        );
        $data["list"]=array();
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('report_inspen/laporan_kepalacounter/view_kepalacounter', $data);
    }

    public function laporan_salescounter() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
        );

        $data["list"]=array();
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report_inspen/laporan_salescounter/view_salescounter', $data);
    }

    public function laporan_salesman() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("TRANS_SPK_KENDARAAN_VIEW SK", "SK.SPK_ID=TRANS_SPK_VIEW.SPKID", "LEFT"),
            ),
        );

        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TGL,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"]=array();
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );
         $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["totaldata"] = 0;//$totaldata;
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('report_inspen/laporan_salesman/view_salesman', $data);
    }

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

    public function laporaninsentif_picstnk() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 100,
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );

        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " JUMLAH > 0 AND CONVERT(CHAR,TGLSELESAI_PENGURUSAN,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " JUMLAH > 0 AND CONVERT(CHAR,TGLSELESAI_PENGURUSAN,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentif_picstnk", $param));
        //$data["list"]=array();
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );

        $data["totaldata"] = 0;//$totaldata;
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        
        $this->template->site('report_inspen/laporaninsentif_picstnk/proses_insentif_picstnk', $data);
    }
    
    public function modal_proses_insentif_picstnk() {
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/config_insentifpic_stnk"));
        $this->load->view('report_inspen/laporaninsentif_picstnk/modal_proses_insentif_picstnk', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    
    public function proses_insentifpic_stnk($id) {
        $total = 0;
        
        $detail = array();
        $detail = $_SESSION["array_detail"];
        foreach ($detail as $row) {
            $total = $total + ($row->JUMLAH * $row->INSENTIF_PERUNIT);
            $newparam = array(
                'no_proses' => $this->input->post("no_proses"),
                'nik' => $row->KD_BIROJASA,
                'nama_pengurus' => $row->NAMA_PENGURUS,
                'no_transaksi' => $row->NO_TRANS,
                'jumlah' => $row->JUMLAH,
                'insentif_perunit' => $row->INSENTIF_PERUNIT,
                'tgl_selesaipengurusan' => $row->TGLSELESAI_PENGURUSAN,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil_detail = $this->curl->simple_post(API_URL . "/api/stnkbpkb/detail_insentifpic_stnk", $newparam, array(CURLOPT_BUFFERSIZE => 10));
        }
        $param = array(
            'id' => $this->input->post("id"),
            'kd_config' => $this->input->post("kd_config"),
            'nama_config' => $this->input->post("nama_config"),
            'value_config' => $this->input->post("value_config"),
            'created_by' => $this->session->userdata("user_id"),
            'no_proses' => $this->input->post("no_proses"),
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'total' => $total,
            'periode' => $this->input->post("periode")
        );
        
        $hasil_header = $this->curl->simple_post(API_URL . "/api/stnkbpkb/header_insentifpic_stnk", $param, array(CURLOPT_BUFFERSIZE => 10));
        
        
        $hasil = $this->curl->simple_put(API_URL . "/api/master/config_proses_insentifpic_stnk", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $id);

        $this->data_output($hasil_header, 'put');
    }

}
