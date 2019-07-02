<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_kpb extends CI_Controller {

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



    public function laporan_reminder_kpb() {

        $data = array();

        $data['date'] = $this->get_date_perweek();
        $data['kpb_jatuhtempo'] = $this->get_rangka_bykpb();
        $data['phone_jatuhtempo'] = $this->get_rangka_bykpb('no_hp');

        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tgl_awal' => tglToSql($data['date']['start_date']),
            'tgl_akhir' => tglToSql($data['date']['end_date']),
            'jenis_kpb' => $this->input->get('jenis_kpb')?$this->input->get('jenis_kpb'):'KPB1'
        );
        $data["metode1"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/laporan_lmkpb/metode1", $param));

        $data["metode2"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/laporan_lmkpb/metode2", $param));
        
        
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));


        // $this->output->set_output(json_encode($data));
        $this->template->site('report/report_lmkpb', $data);
    }

    public function get_date_perweek()
    {
        $data = array();

        $date_now = $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y');
        // $date_now = '31/07/2018';
        // $date_now = '02/08/2018';


        $thisday = date("m", strtotime(tglToSql($date_now)));
        $firstday = date("m", strtotime('monday this week', strtotime(tglToSql($date_now))));
        $month = $thisday - $firstday;

        $tgl_periode_awal = date("d/m/Y", strtotime('monday this week', strtotime(tglToSql($date_now))));
        $tgl_periode_akhir = tglfromSql(getNextDays(tglToSql($tgl_periode_awal), 7));

        $month_interval =  date("m",strtotime(tglToSql($tgl_periode_akhir))) - date("m",strtotime(tglToSql($tgl_periode_awal)));

        $month_end = date("d/m/Y", strtotime('last day of this month', strtotime(tglToSql($tgl_periode_awal))));
        $month_start = date("d/m/Y", strtotime('first day of this month', strtotime(tglToSql($tgl_periode_akhir))));

        $month_1 = date("F", strtotime('monday this week', strtotime(tglToSql($date_now))));
        $month_2 = date("F", strtotime('first day of this month', strtotime(tglToSql($tgl_periode_akhir))));

        if($month_interval == 1)
        {
            $data['start_date'] = $month == 0? $tgl_periode_awal : $month_start;
            $data['end_date'] = $month == 0? $month_end : $tgl_periode_akhir;
            $data['month'] = $month == 0?$month_1 : $month_2;
        }
        else
        {
            $data['start_date'] = $tgl_periode_awal;
            $data['end_date'] = $tgl_periode_akhir;
            $data['month'] = $month_1;
        }

        // $this->output->set_output(json_encode($data));
        return $data;
    }


    public function get_rangka_bykpb($filter=null)
    {
        $data_fu = array();
        $kpbInput = $this->input->get('jenis_kpb')?$this->input->get('jenis_kpb'):'KPB1';

        $param = array(
            'jointable' => array(
                array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_SJKELUAR.KD_CUSTOMER", "LEFT"),
                array("MASTER_AGAMA MA", "MA.KD_AGAMA=U.KD_AGAMA", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.KET_UNIT NOT IN('KSU','HADIAH','BARANG')", "LEFT"),
                array("TRANS_STNK_BUKTI STB" , "STB.NO_RANGKA=SJD.NO_RANGKA AND STB.KETERANGAN = 'PLAT' AND STB.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_KPB_MOTOR_VIEW KPB" , "KPB.NO_RANGKA=SJD.NO_RANGKA AND KPB.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'SJD.NO_RANGKA, STB.DATA_NOMOR, SJD.NO_MESIN, SJD.KET_UNIT, U.*, MA.NAMA_AGAMA, TRANS_SJKELUAR.NO_SURATJALAN, TRANS_SJKELUAR.TGL_TERIMA, TRANS_SJKELUAR.STATUS_SJ,SP.TGL_SO, convert(char,SP.TGL_SO,112) AS TGL_PEMBELIAN, KPB.JENIS_KPB',
            'orderby' => 'TRANS_SJKELUAR.TGL_TERIMA asc'
        );

        if ($filter == 'no_hp') {
            $param['custom'] = " (U.NO_HP != '' OR U.NO_HP IS NOT NULL) AND TRANS_SJKELUAR.KD_DEALER ='".$this->session->userdata("kd_dealer")."' AND KPB.JENIS_KPB = '".$kpbInput."'";
        }
        else{
            $param['custom'] = "TRANS_SJKELUAR.KD_DEALER ='".$this->session->userdata("kd_dealer")."' AND KPB.JENIS_KPB = '".$kpbInput."'";
        }

            

        $sj = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));

        return $sj->totaldata;


    }


    function autogenerate_fu($kd_docno) {
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

    /**
     * [add_customer description]
     */


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
