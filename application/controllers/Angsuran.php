<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Angsuran extends CI_Controller {
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
    public function terimabank_list($debug=null){
        $data=array();
        $fromDate=($this->input->get("frd"))?TglToSql($this->input->get("frd")):date('Ymd',strtotime("-5 days"));
        $toDate=($this->input->get("tod"))?TglToSql($this->input->get("tod")):date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");
        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'ID,NO_TRANS desc',
            'kd_dealer' => $kd_dealer,
            'custom'    =>"CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".$fromDate."' AND '".$toDate."'",
            'user_login' => $this->session->userdata("user_id"),
        );

        $data['terimabank'] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/terimabank/true", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $string = link_pagination();
        if($debug){
            $param=array(
                'kd_dealer'=>$kd_dealer,
                'custom'   =>"SISA_SALDO >0",
            );
            $data['terimabank'] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/terimabank/1/1", $param));
            echo (json_encode($data['terimabank']));
            exit();
        }
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('accounting/terima_bank_list', $data);
    }
    public function terima_bank($dataonly = null,$debug=null){
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");
        if(!$this->input->get("n")){
            $param=array(
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'user_login' => $this->session->userdata("user_id")
            );
            $hasil=$this->curl->simple_get(API_URL."/api/accounting/generate_terimabank",$param);
            if($debug){
                var_dump($hasil);
            }
        }
        $param= array(
            'kd_dealer' => $kd_dealer,
            'field' => "KD_BANK",
            'groupby' => true
        );
        $data["kd_bank"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/bank", $param));
        $param_bank= array(
            'kd_bank' => $this->input->get('kd_bank'),
            'kd_dealer' => $kd_dealer
        );
        $data["nama_bank"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/bank", $param_bank));
        $param_trans= array(
            'kd_trans' => $this->input->get('kd_trans')
        );
        $data["transaksi"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans", $param_trans));
        $param_terimabank = array(
            'orderby' => 'ID,NO_TRANS desc',
            'kd_dealer' => $kd_dealer,
            'custom'    =>"CONVERT(CHAR,TGL_TRANS,112)='".date('Ymd')."'",
            'user_login' => $this->session->userdata("user_id")
        );
        $paramtm=array(
            'kd_dealer'=>$kd_dealer,
            'user_login' => str_replace("-","",$this->session->userdata("user_id"))
        );
        $datax=$this->curl->simple_get(API_URL . "/api/accounting/generate_terimabank",$paramtm);
        $data['terimabank'] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/terimabank/true", $param_terimabank));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        if($debug){
            print_r($param_terimabank);
            var_dump($data["terimabank"]);
            exit();
        }
        if($dataonly=='true'){
            //print_r($param_terimabank);
            $this->output->set_output(json_encode($data));
        }else{
            $this->template->site('accounting/terima_bank',$data);
        }
    }
    public function terimabank_hps(){
        $param=array(
            'id' => $this->input->get("id"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil=$this->curl->simple_delete(API_URL."/api/accounting/terimabank",$param, array(CURLOPT_BUFFERSIZE => 10));
        //var_dump($hasil);
        $this->data_output($hasil,'delete');
    }
    public function get_terimabank_table($list){
        $html = '';
        $no = $this->input->get('page');
        foreach ($list as $key => $list_terima) {
            $no++;
            $html .= '<tr>';
            $html .= '<td>'.$no.'</td>';
            $html .= '<td>'.$list_terima->TGL_TRANS.'</td>';
            $html .= '<td>'.$list_terima->KD_TRANS.'</td>';
            $html .= '<td>'.$list_terima->KETERANGAN.'</td>';
            $html .= '<td>'.$list_terima->TIPE_TRANS.'</td>';
            $html .= '<td>'.$list_terima->AWAL.'</td>';
            $html .= '<td>'.$list_terima->DEBET.'</td>';
            $html .= '<td>'.$list_terima->KREDIT.'</td>';
            $html .= '<td>'.$list_terima->AKHIR.'</td>';
            $html .= '<td>'.$list_terima->NO_TRANS.'</td>';
            $html .= '</tr>';
        }
        return $html;
    }
    public function terimabank_simpan() {
        $debet = $this->input->post("tipe_trans") == 'D'?$this->input->post("jumlah"):0;
        $kredit = $this->input->post("tipe_trans") == 'K'?$this->input->post("jumlah"):0;
        $akhir = $this->input->post("tipe_trans") == 'D'?$this->input->post("akhir") + $this->input->post("jumlah"):$this->input->post("akhir") - $this->input->post("jumlah");
        $param = array(
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_bank' => $this->input->post("kd_bank"),
            'no_trans' => $this->getnopo('TB'),
            'tgl_trans' => $this->input->post("tgl_trans"),
            'kd_trans' => $this->input->post("kd_trans"),
            'keterangan' => $this->input->post("keterangan"),
            'tipe_trans' => $this->input->post("tipe_trans"),
            'awal' => str_replace(",","",$this->input->post("akhir")),
            'debet' => str_replace(",","",$debet),
            'kredit' => str_replace(",","",$kredit),
            // 'awal' => $this->input->post("awal"),
            'akhir' => str_replace(",","",$akhir),
            'tutup' => $this->input->post("tutup"),
            'rinci' => $this->input->post("rinci"),
            'kd_akun' => $this->input->post("kd_akun"),
            'jenis_trans' => $this->input->post("jenis_trans"),
            'jumlah' => str_replace(",","",$this->input->post("jumlah")),
            'status_trans' => $this->input->post("status_trans"),
            'created_by' => $this->session->userdata('user_id')
        );
        $hasil=$this->curl->simple_post(API_URL."/api/accounting/terimabank",$param,array(CURLOPT_BUFFERSIZE => 10));  
        // print_r($param);var_dump($hasil);exit();
        $method = "post";
        if($hasil){
            if(json_decode($hasil)->recordexists==TRUE){
                $hasil=$this->curl->simple_put(API_URL."/api/accounting/terimabank",$param, array(CURLOPT_BUFFERSIZE => 10));  
                $method = "put";

                //$this->output->set_output(json_encode($hasil));
            }else{
                //$this->output->set_output(json_encode($hasil));
            }
        }
        /*$param=array(
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'user_login' => $this->session->userdata("user_id")
        );
        $this->curl->simple_get(API_URL."/api/accounting/generate_terimabank",$param);*/
        $this->data_output($hasil, $method,base_url("angsuran/terima_bank?n=".$param["kd_bank"]));
    }
    // generate code number
    // ===========================================================================
    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo($kd_docno){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'TERIMA BANK',
            'reset_docno' => 1,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'limit'     => 1,
            'offset'    => 0
        );
        $bulan_kirim = date('m');
        $nomor_po=$this->curl->simple_get(API_URL."/api/setup/docno",$param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomor_po == 0) {
            $nopo = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
            $param['urutan_docno'] = $nomor_po+1;
            $this->curl->simple_post(API_URL."/api/setup/setup_docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        } else {
            $nomorpo = $nomor_po+1;
            $nopo = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
            $param['urutan_docno'] = $nomor_po;
            $this->curl->simple_put(API_URL."/api/setup/docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        }
        //var_dump($nopo);exit();
        return $nopo;
    }
    function pelunasan($debug=null){

    }
    function reprinttagihan($approve=null){
        $param=array(
            'no_trans' => $this->input->get("no_trans"),
            'kd_piutang' => $this->input->get("kd_piutang"),
            'alasan'    => $this->input->get("alasan"),
            'reprint' =>($approve)?$approve:'1',
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil=$this->curl->simple_put(API_URL."/api/accounting/piutang_reprint",$param, array(CURLOPT_BUFFERSIZE => 10));
        
        $this->data_output($hasil,'put');
    }
    function data_output($hasil = NULL, $method = '', $location = '', $no_claim='') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil disimpan",
                        'location' => $location,
                        'noclaim' => $no_claim
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
}