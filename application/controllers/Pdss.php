<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pdss extends CI_Controller {

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

    public function pdss_list() {
        $row_status = $this->input->get('row_status');
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) = '" . tglToSql($this->input->get("tanggal")) . "'",
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ,
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MD.KD_DEALER = TRANS_PDSS.KD_DEALER","LEFT")
            ),
            'tanggal' => $this->input->get('tanggal'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'TRANS_PDSS.*,MD.KD_DEALERAHM',
            'orderby' => 'TRANS_PDSS.ID Desc'
        );

        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdss", $param));

        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");


        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));

        $data['pdss'] = false;

        if ($row_status == 0) {
            $data['pdss'] = true;
        }
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('inventori/pdss/view', $data);
    }

    public function download_list() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PDSS.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_PDSS.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $param = array(
            'keyword' => $this->input->get('keyword'),
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'custom' => "ROW_STATUS >= 0 AND " . $kd_dealer,
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MD.KD_DEALER = TRANS_PDSS.KD_DEALER","LEFT")
            ),
            'field' => "TRANS_PDSS.*, MD.KD_DEALERAHM"
        );

        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }

        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdss", $param));
//        var_dump($data);
        return $data;
    }

    /**
     * [createfile_udpo description]
     * @return [type] [description]
     */
    public function createfile_pdss() {
        $data = array();
        $data = $this->download_list();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALER . "-" . date('dmY') . "-" . date('ymdHi') . ".PDSS"; //
            $isifile .= $row->KD_DEALERAHM . ";";
            $isifile .= str_replace("/", "", tglFromSql($row->TANGGAL)) . ";";
            $isifile .= $row->PART_NUMBER . ";";
            $isifile .= $row->QTY_ON_HAND . ";";
            $isifile .= $row->QTY_SALES . ";";
            $isifile .= $row->QTY_SIM_PARTS . ";" . PHP_EOL;
        }
//        var_dump($data->message);
//        exit();
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }

    public function pdss_typeahead() {
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdss",$param)); //

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
        }

        $result['keyword'] = array_merge($data_message[0]);

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
