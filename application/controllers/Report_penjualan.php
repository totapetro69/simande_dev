<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_penjualan extends CI_Controller {
     var $API="";
    public function __construct()
    {
            parent::__construct();
            //API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
            $this->load->library('form_validation');
            $this->load->library('session');
            $this->load->library('curl');
            $this->load->library('pagination');
            $this->load->helper('form');
            $this->load->helper('url'); 
            $this->load->helper('zetro'); 
            $this->load->helper('file');
    }

    public function penjualan_partoli(){
        $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,PKB.TANGGAL_PKB,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,PKB.TANGGAL_PKB,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $jenis = ($this->input->get('jenis_item') == 'OLI' ? 'OLI' : 'PART');

        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jenis_item' => $jenis,
            'jointable' =>array(
                array("TRANS_PKB PKB" , "PKB.NO_PKB=TRANS_PKB_DETAIL.NO_PKB AND PKB.ROW_STATUS>=0", "LEFT"),
                array("MASTER_PART MP" , "MP.PART_NUMBER=TRANS_PKB_DETAIL.KD_PEKERJAAN AND MP.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'TRANS_PKB_DETAIL.*, MP.PART_DESKRIPSI, PKB.TANGGAL_PKB',
            'custom' => $tgl." AND PKB.KD_DEALER = '".$kd_dealer."'"
        );

        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/service/pkb_detail",$param));

        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        
        // $this->output->set_output(json_encode($data['list']));
        $this->template->site('report/part_oli/laporan_partoli', $data);
    }

    public function cetak_partoli()
    {
        $this->load->library('dompdf_gen');

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,PKB.TANGGAL_PKB,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,PKB.TANGGAL_PKB,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $jenis = ($this->input->get('jenis_item') == 'OLI' ? 'OLI' : 'PART');

        $param = array(
            // 'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            // 'limit' => 50,
            'jenis_item' => $jenis,
            'jointable' =>array(
                array("TRANS_PKB PKB" , "PKB.NO_PKB=TRANS_PKB_DETAIL.NO_PKB AND PKB.ROW_STATUS>=0", "LEFT"),
                array("MASTER_PART MP" , "MP.PART_NUMBER=TRANS_PKB_DETAIL.KD_PEKERJAAN AND MP.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'TRANS_PKB_DETAIL.*, MP.PART_DESKRIPSI, PKB.TANGGAL_PKB',
            'custom' => $tgl." AND PKB.KD_DEALER = '".$kd_dealer."'"
        );

        $data['list']=json_decode($this->curl->simple_get(API_URL."/api/service/pkb_detail",$param));

        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer/true",array('kd_dealer' => $kd_dealer)));

        // $html = $this->load->view('report/part_oli/cetak_partoli', $data);

        $html = $this->load->view('report/part_oli/cetak_partoli', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'portrait');

        // $this->output->set_output(json_encode($data));

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
                        'message' => 'data berhasil dihapus',
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
	function penjualan_oliservis() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => $this->input->get('tahun') ? $this->input->get('tahun'):date("Y"),
            'bulan' => $this->input->get('bulan') ? $this->input->get('bulan'):date("m")
        );
		$param_list = array(
			'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => ($this->input->get('tahun')) ? $this->input->get('tahun'):date("Y"),
            'bulan' => ($this->input->get('bulan')) ? $this->input->get('bulan'):date("m"),
			'field' => "distinct(KD_PEKERJAAN) as KD_PEKERJAAN",
			'groupby' => "KD_PEKERJAAN"
		);
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["rekapoli"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporanpenjualan_oliservis", $param));
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/service/tahunpkb"));
		$data["list_pekerjaan"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporanpenjualan_oliservis", $param_list));
        $this->template->site('report/report_penjualan_oliservis', $data);
    }


}
