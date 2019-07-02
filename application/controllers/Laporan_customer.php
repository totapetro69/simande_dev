<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_customer extends CI_Controller {

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

    public function customer() {

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_GUESTBOOK_VIEW.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_GUESTBOOK_VIEW.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";

        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TANGGAL,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TANGGAL,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $kd_dealer2 = $this->input->get('kd_dealer') ? "TRANS_APPOINTMENT.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_APPOINTMENT.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $tgl2 = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TANGGAL_JANJI,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TANGGAL_JANJI,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $kd_dealer3 = $this->input->get('kd_dealer') ? "TRANS_CUSTOMER_DATABASE.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_CUSTOMER_DATABASE.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $tgl3 = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_SPK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SPK,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'custom' => $tgl,/*
            'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",*/
            'limit' => 15,
            'field' => '*'
        );

        $params = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            /*'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",*/
            'custom' => $tgl2,
            'limit' => 15,
            'field' => '*'
        );

        $paramcustomer = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            //'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
            'custom' => $tgl3,
            'limit' => 15,
            'field' => '*'
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
            $paramcustomer['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $params['where_in'] = isDealerAkses();
            $paramcustomer['where_in'] = isDealerAkses();
        }

        $data = array();
        if ($this->input->get('pilih') == 3) {
            $data['pilih'] = 3;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        } elseif ($this->input->get('pilih') == 2) {
            $data['pilih'] = 2;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        } elseif ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/cddb", $paramcustomer));
        } else {
            $data['pilih'] = 0;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/appointment", $params));
        }


        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        $string = link_pagination();
        $config = array(
            //'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('report/customer/view', $data);
    }

    public function customer_print() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_GUESTBOOK_VIEW.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_GUESTBOOK_VIEW.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";

        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TANGGAL,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TANGGAL,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $kd_dealer2 = $this->input->get('kd_dealer') ? "TRANS_APPOINTMENT.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_APPOINTMENT.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $tgl2 = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TANGGAL_JANJI,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TANGGAL_JANJI,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $kd_dealer3 = $this->input->get('kd_dealer') ? "TRANS_CUSTOMER_DATABASE.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_CUSTOMER_DATABASE.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $tgl3 = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_SPK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SPK,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'custom' => $kd_dealer . " AND " . $tgl,/*
            'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",*/
            'limit' => '*',
            'field' => '*'
        );

        $params = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            /*'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",*/
            'custom' => $kd_dealer2 . " AND " . $tgl2,
            'limit' => '*',
            'field' => '*'
        );

        $paramcustomer = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
            'custom' => $kd_dealer3 . " AND " . $tgl3,
            'limit' => '*',
            'field' => '*'
        );

        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
/*
        $param = array(
            'field' => "YEAR(TANGGAL) AS TAHUN",
            'groupby' => TRUE,
            'orderby' => "YEAR(TANGGAL) DESC"
        );*/

      /*  $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));*/

        $data = array();
        if ($this->input->get('pilih') == 3) {
            $data['pilih'] = 3;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        } elseif ($this->input->get('pilih') == 2) {
            $data['pilih'] = 2;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        } elseif ($this->input->get('pilih') == 1) {
            $data['pilih'] = 1;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/cddb", $paramcustomer));
        } else {
            $data['pilih'] = 0;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/appointment", $params));
        }


        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

//        var_dump($data);
//        exit();
        $this->load->view('report/customer/print', $data);
        $html = $this->output->get_output();
//
        $this->output->set_output(json_encode($html));
    }

    public function customer_database() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_CUSTOMER_DATABASE.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_CUSTOMER_DATABASE.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";
        $row_status = $this->input->get('row_status');
        $status_download = $this->input->get('status_download');

        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_SPK,112) = '" . tglToSql($this->input->get("tanggal")) . "'",
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'tanggal' => $this->input->get('tanggal'),
            'field' => 'TRANS_CUSTOMER_DATABASE.NAMA_FILE, TRANS_CUSTOMER_DATABASE.STATUS_DOWNLOAD',
            'custom' => "TRANS_CUSTOMER_DATABASE.ROW_STATUS = 0 AND TRANS_CUSTOMER_DATABASE.STATUS_DOWNLOAD = '" . $this->input->get('status_download') . "' AND " . $kd_dealer,
            'orderby' => 'TRANS_CUSTOMER_DATABASE.NAMA_FILE DESC',
            'groupby' => TRUE
        );
        
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TGL_SPK,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_SPK,112) = '" . date('Ymd') . "'";
        }
        
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/cddb", $param));


        $param_detail = array(
        'keyword' => $this->input->get('keyword'),
        'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
        'limit' => 15,
        'field' => '*',
//            'field' => 'TRANS_CUSTOMER_DATABASE.ID, TRANS_CUSTOMER_DATABASE.NO_MESIN, TRANS_CUSTOMER_DATABASE.NO_RANGKA, TRANS_CUSTOMER_DATABASE.KD_ITEM, TRANS_CUSTOMER_DATABASE.NAMA_ITEM, TRANS_CUSTOMER_DATABASE.KD_CUSTOMER, TRANS_CUSTOMER_DATABASE.N AMA_CUSTOMER, TRANS_CUSTOMER_DATABASE.JENIS_KELAMIN, TRANS_CUSTOMER_DATABASE.NO_TELEPON, TRANS_CUSTOMER_DATABASE.ALAMAT, TRANS_CUSTOMER_DATABASE.STATUS_DOWNLOAD, TRANS_CUSTOMER_DATABASE.NAMA_FILE',
        'custom' => "TRANS_CUSTOMER_DATABASE.ROW_STATUS = 0 AND TRANS_CUSTOMER_DATABASE.STATUS_DOWNLOAD = '" . $this->input->get('status_download') . "' AND " . $kd_dealer,
        'orderby' => 'TRANS_CUSTOMER_DATABASE.SPK_ID DESC, TRANS_CUSTOMER_DATABASE.STATUS_DOWNLOAD asc',
        );
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/cddb", $param_detail));

//        var_dump($data);exit;

        $data['cddb'] = false;

        if ($row_status == 0 && $status_download == 0) {
            $data['cddb'] = true;
        }

        $configs = array(
            'per_page' => $param['limit'],
            'base_url' => base_url() . 'laporan_customer/customer_database?keyword=' . $param['keyword'] . '&tanggal=' . $param['tanggal'],
            'total_rows' => $data["list"]->totaldata ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($configs);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('report/customer_database/view', $data);
    }

    public function customer_database_detail_list() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_CUSTOMER_DATABASE.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_CUSTOMER_DATABASE.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $params = array(
            'custom' => 'TRANS_CUSTOMER_DATABASE.ROW_STATUS >= 0 AND TRANS_CUSTOMER_DATABASE.STATUS_DOWNLOAD = 0 AND ' . $kd_dealer,
            'field' => "TRANS_CUSTOMER_DATABASE.*,"
            . "\"(SELECT JAWABAN FROM TRANS_KUISONER AS M WHERE M.KETERANGAN = '7_digunakan_untuk' AND M.KD_CUSTOMER = TRANS_CUSTOMER_DATABASE.KD_CUSTOMER AND M.ROW_STATUS>=0 AND M.SPK_ID = TRANS_CUSTOMER_DATABASE.SPK_ID) AS JAWABAN \","
            . "\"(SELECT JAWABAN FROM TRANS_KUISONER AS C WHERE C.KETERANGAN = '8_yang_menggunakan' AND C.KD_CUSTOMER = TRANS_CUSTOMER_DATABASE.KD_CUSTOMER AND C.ROW_STATUS >=0 AND C.SPK_ID = TRANS_CUSTOMER_DATABASE.SPK_ID) AS JAWABAN1\""
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/cddb", $params));
//        var_dump($data);
//        exit();
        return $data;
    }

    public function createfile_cddb() {
        $data = array();
        $data = $this->customer_database_detail_list();
        $namafile = "";
        $isifile = "";
        if ($data && is_array($data->message) || is_object($data->message)):
            foreach ($data->message as $key => $row) {
//                if ($row->NAMA_FILE) {
//                    $this->download_file($row->NAMA_FILE);
//                }
                $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".CDDB"; //
                $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                $isifile .= substr($row->NO_MESIN, 5, 7) . ";";
                $isifile .= $row->NO_KTP . ";";
                $isifile .= $row->KD_TYPEMOTOR . ";";
                $isifile .= $row->KD_CUSTOMER . ";";
                $isifile .= $row->JENIS_KELAMIN . ";";
                $isifile .= str_replace("/", "", tglFromSql($row->TGL_LAHIR)) . ";";
                $isifile .= $row->ALAMAT_SURAT . ";";
                $isifile .= $row->NAMA_DESA . ";";
                $isifile .= $row->NAMA_KECAMATAN . ";";
                $isifile .= $row->NAMA_KABUPATEN . ";";
                $isifile .= $row->KODE_POS . ";";
                $isifile .= $row->KD_PROPINSI . ";";
                $isifile .= $row->KD_AGAMA . ";";
                $isifile .= $row->KD_PEKERJAAN . ";";
                $isifile .= $row->PENGELUARAN . ";";
                $isifile .= $row->KD_PENDIDIKAN . ";";
                $isifile .= $row->NAMA_PENANGGUNGJAWAB . ";";
                $isifile .= $row->NO_HP . ";";
                $isifile .= $row->NO_TELEPON . ";";
                $isifile .= $row->STATUS_DIHUBUNGI . ";";
                $isifile .= $row->NAMA_ITEM . ";";
                $isifile .= $row->KD_TYPEMOTOR . ";";
                $isifile .= $row->JAWABAN . ";";
                $isifile .= $row->JAWABAN1 . ";";
                $isifile .= $row->KD_ITEM . ";";
                $isifile .= $row->NAMA_SALES . ";";
                $isifile .= $row->EMAIL . ";";
                $isifile .= $row->STATUS_RUMAH . ";";
                $isifile .= $row->STATUS_NOHP . ";";
                $isifile .= $row->AKUN_FB . ";";
                $isifile .= $row->AKUN_TWITTER . ";";
                $isifile .= $row->AKUN_INSTAGRAM . ";";
                $isifile .= $row->AKUN_YOUTUBE . ";";
                $isifile .= $row->HOBI . ";";
                $isifile .= $row->KARAKTERISTIK_KONSUMEN . ";";
                $isifile .= $row->ID_REFFERAL . ";" . PHP_EOL;
            }
        endif;

        $this->load->helper('file');

        if (write_file(FCPATH . 'assets/uploads/CDDB/' . $namafile, $isifile) == TRUE) {
            foreach ($data->message as $key => $rows) {

                $param = array(
                    'no_mesin' => $rows->NO_MESIN,
                    'no_rangka' => $rows->NO_RANGKA,
                    'status_download' => 1,
                    'nama_file' => $namafile,
                    'lastmodified_by' => $this->session->userdata("user_id")
                );

                $data = json_decode($this->curl->simple_put(API_URL . "/api/laporan/spk_detailkendaraan_sd", $param));
            }
            //untuk download langsung
//            $data_return = array(
//                'status' => true,
//                'message' => 'data berahasil didownload',
//                'file' => base_url() . 'laporan_customer/download_cddb?namafile=' . $namafile
//            );
        }
        //untuk download langsung
//        else {
//
//            $data_return = array(
//                'status' => false,
//                'message' => 'data gagal didownload'
//            );
//        }

        redirect(base_url() . 'laporan_customer/customer_database?status_download=1');
//        $this->load->helper("download");
//        force_download($namafile, $isifile);
    }
                             
    public function download($file) {

        $this->load->helper('download');
        $name = $file;
        $data = file_get_contents('./assets/uploads/CDDB/' . $file);
        force_download($name, $data);

//        redirect('laporan_customer/customer_database?status_download=1', 'refresh');
    }

    public function cetak_customer_database() {
        $data = array();
        if (base64_decode(urldecode($this->input->get("n")))) {
            $param = array(
                'custom' => "TRANS_CUSTOMER_DATABASE.ROW_STATUS >-1",
                'jointable' => array(array("MASTER_P_TYPEMOTOR", "MASTER_P_TYPEMOTOR.KD_ITEM=TRANS_CUSTOMER_DATABASE.KD_ITEM", "LEFT")),
                'field' => "TRANS_CUSTOMER_DATABASE.*,MASTER_P_TYPEMOTOR.NAMA_ITEM"
            );
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/cddb", $param));
            //untuk keperluan create file udpo
            $data["filecddb"] = $this->customer_database_list();
        }
        /* var_dump($data);
          exit(); */
        $this->load->view('report/customer_database/cetak', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_customer_database() {
        $this->auth->validate_authen('laporan_customer/customer_database');
//$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
//$data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $this->load->view('report/customer_database/add');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function import_customer_database() {
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
                            'no_mesin' => !empty($importdata[0]) ? $importdata[0] : '',
                            'no_rangka' => !empty($importdata[1]) ? $importdata[1] : '',
                            'kd_typemotor' => !empty($importdata[2]) ? $importdata[2] : '',
                            'kd_item' => !empty($importdata[3]) ? $importdata[3] : '',
                            'nama_item' => !empty($importdata[0]) ? $importdata[0] : '',
                            'kd_customer' => !empty($importdata[1]) ? $importdata[1] : '',
                            'nama_customer' => !empty($importdata[2]) ? $importdata[2] : '',
                            'kd_item' => !empty($importdata[3]) ? $importdata[3] : '',
                            'created_by' => $this->session->userdata('user_id')
                        );
//                        var_dump($param);
//                        exit();
//$this->db->trans_begin();
//$this->students_tbl_model->add($row);
                        $hasil = $this->curl->simple_post(API_URL . "/api/laporan/cddb", $param, array(CURLOPT_BUFFERSIZE => 10));
//$data = json_decode($hasil);strtoupper(
//$this->session->set_flashdata('tr-active', $data->message);
//$this->data_output($hasil, 'post', base_url('motor/grup_motor'));
                    }
                    if ($hasil <= 0) {
//$this->db->trans_rollback();
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
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
        $this->data_output($hasil, 'post', base_url('laporan_customer/customer_database'));
    }

    public function notEmpty() {
        if (!empty($_FILES['file']['name'])) {
            return TRUE;
        } else {
            $this->form_validation->set_message('notEmpty', 'The {field} field can not be empty.');
            return FALSE;
        }
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
