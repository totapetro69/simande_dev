<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_dmd extends CI_Controller {

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

    public function service_dmd() {
        $data = array();

        $paramDC = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m"),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array("TRANS_PARTSO_DETAIL AS PSD", "PSD.NO_TRANS=TRANS_PARTSO.NO_TRANS", "LEFT"),
                array("MASTER_PART AS MP", "MP.PART_NUMBER=PSD.PART_NUMBER", "LEFT"),
                array("TRANS_PART_PICKING_DETAIL AS PPD", "PPD.NO_TRANS=PSD.PICKING_REFF AND PPD.PART_NUMBER=PSD.PART_NUMBER", "LEFT"),
                array("TRANS_PART_PICKING AS PP", "PP.NO_TRANS=PPD.NO_TRANS", "LEFT")
            ),
            'field' => 'PSD.*,TRANS_PARTSO.TGL_TRANS,PPD.JUMLAH, PSD.HARGA_JUAL, MP.PART_DESKRIPSI'
        );

        $paramMDD = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m"),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array("TRANS_POSP_DETAIL AS TPD", "TPD.NO_PO      = TRANS_POSP.NO_PO", "LEFT"),
                array("MASTER_PART AS MP", "MP.PART_NUMBER = TPD.PART_NUMBER", "LEFT"),
                array("TRANS_PART_TERIMA AS PT", "PT.NO_PO       = TRANS_POSP.NO_PO", "LEFT"),
                array("TRANS_PART_TERIMADETAIL AS PTD", "PTD.NO_TRANS   = PT.NO_TRANS AND PTD.PART_NUMBER=TPD.PART_NUMBER", "LEFT")
            ),
            'field' => 'TRANS_POSP.ID, TRANS_POSP.TGL_PO, TRANS_POSP.NO_PO,TPD.PART_NUMBER, MP.PART_DESKRIPSI ,TPD.JUMLAH AS JUMLAH1, PTD.JUMLAH AS JUMLAH2,TPD.HARGA,PTD.HARGA_BELI '
        );

        //tahun
        if ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $paramDC["custom"]= "MONTH(TRANS_PARTSO.TGL_TRANS)='".$this->input->get('bulan')."' AND YEAR(TRANS_PARTSO.TGL_TRANS)='".$this->input->get("tahun")."'";
            $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso", $paramDC));
//            var_dump($data);            exit();
        } else {
            $data['pilih'] = 0;
            $paramMDD["custom"]= "MONTH(TGL_PO)='".$this->input->get('bulan')."' AND YEAR(TGL_PO)='".$this->input->get("tahun")."'";
            $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $paramMDD));
        }

        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        if ($this->input->get('pilih') == 1) {        
            $string = link_pagination();
            $config = array(
                'per_page' => $paramDC['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
        } else {        
            $string = link_pagination();
            $config = array(
                'per_page' => $paramMDD['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
        }
        $paramDC = array(
            'field' => "YEAR(TGL_TRANS) AS TAHUNS,",
            'groupby' => "YEAR(TGL_TRANS)",
            'orderby' => "YEAR(TGL_TRANS) DESC"
        );

        $paramMDD = array(
            'field' => "YEAR(TGL_PO) AS TAHUNS",
            'groupby' => "YEAR(TGL_PO)",
            'orderby' => "YEAR(TGL_PO) DESC"
        );

        if ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso", $paramDC));
        } else {
            $data['pilih'] = 0;
            $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $paramMDD));
        }

        $param_area = array( 
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=USERS_AREA.KD_DEALER AND USERS_AREA.AUTH_STATUS > 0", "LEFT")
            ),
            "custom" => "USERS_AREA.USER_ID='".$this->session->userdata('user_id')."'"
        );

        $data["dealer_area"] = json_decode($this->curl->simple_get(API_URL . "/api/menu/users_area", $param_area));

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('report/service_dmd/view_dmd', $data); //, $data
    }

    public function report_dmd_print() {
        $data = array();

        $paramDC = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m"),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array("TRANS_PARTSO_DETAIL AS PSD", "PSD.NO_TRANS=TRANS_PARTSO.NO_TRANS", "LEFT"),
                array("MASTER_PART AS MP", "MP.PART_NUMBER=PSD.PART_NUMBER", "LEFT"),
                array("TRANS_PART_PICKING_DETAIL AS PPD", "PPD.NO_TRANS=PSD.PICKING_REFF AND PPD.PART_NUMBER=PSD.PART_NUMBER", "LEFT"),
                array("TRANS_PART_PICKING AS PP", "PP.NO_TRANS=PPD.NO_TRANS", "LEFT")
            ),
            'field' => 'PSD.*,TRANS_PARTSO.TGL_TRANS,PPD.JUMLAH, PPD.HARGA_JUAL, MP.PART_DESKRIPSI'
        );

        $paramMDD = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m"),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array("TRANS_POSP_DETAIL AS TPD", "TPD.NO_PO      = TRANS_POSP.NO_PO", "LEFT"),
                array("MASTER_PART AS MP", "MP.PART_NUMBER = TPD.PART_NUMBER", "LEFT"),
                array("TRANS_PART_TERIMA AS PT", "PT.NO_PO       = TRANS_POSP.NO_PO", "LEFT"),
                array("TRANS_PART_TERIMADETAIL AS PTD", "PTD.NO_TRANS   = PT.NO_TRANS AND PTD.PART_NUMBER=TPD.PART_NUMBER", "LEFT")
            ),
            'field' => 'TRANS_POSP.ID, TRANS_POSP.TGL_PO, TRANS_POSP.NO_PO,TPD.PART_NUMBER, MP.PART_DESKRIPSI ,TPD.JUMLAH AS JUMLAH1, PTD.JUMLAH AS JUMLAH2,TPD.HARGA,PTD.HARGA_BELI '
        );

        //tahun
        if ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $paramDC["custom"]= "MONTH(TRANS_PARTSO.TGL_TRANS)='".$this->input->get('bulan')."' AND YEAR(TRANS_PARTSO.TGL_TRANS)='".$this->input->get("tahun")."'";
            $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso", $paramDC));
//            var_dump($data);            exit();
        } else {
            $data['pilih'] = 0;
            $paramMDD["custom"]= "MONTH(TGL_PO)='".$this->input->get('bulan')."' AND YEAR(TGL_PO)='".$this->input->get("tahun")."'";
            $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $paramMDD));
        }

        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        $paramDC = array(
            'field' => "YEAR(TGL_TRANS) AS TAHUNS,",
            'groupby' => "YEAR(TGL_TRANS)",
            'orderby' => "YEAR(TGL_TRANS) DESC"
        );

        $paramMDD = array(
            'field' => "YEAR(TGL_PO) AS TAHUNS",
            'groupby' => "YEAR(TGL_PO)",
            'orderby' => "YEAR(TGL_PO) DESC"
        );

        if ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso", $paramDC));
        } else {
            $data['pilih'] = 0;
            $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $paramMDD));
        }
        
        $this->load->view('report/service_dmd/print_dmd', $data);

        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
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

}
    