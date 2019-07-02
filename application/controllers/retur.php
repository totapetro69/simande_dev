<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Retur extends CI_Controller {
 
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
 
 
    //service advisor 
    /**
     * list data guest book
     * @return [type] [description]
     */
   public function jualbeli() {
        $data = array();$result=null;

        $bln = ($this->input->get("bulan"))?$this->input->get("bulan"):date("m");
        $thn = ($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'TRANS_RETUR_JUALBELI.ID desc',
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );

        if ($this->input->get("jenis_retur")) {
            $param["jenis_retur"] = $this->input->get("jenis_retur");
        }
 
        $param["custom"] = "MONTH(TGL_TRANS)='".$bln."' AND YEAR(TGL_TRANS)='".$thn."'";

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli", $param));  

        $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang/true")); 
        
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        if($this->session->userdata("nama_group")!='Root'){
            $param=array("kd_dealer" => $this->session->userdata("kd_dealer"));
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $pagination = $this->pagination($config);
        
        $this->pagination->initialize($pagination);

        $data['pagination'] = $this->pagination->create_links();
 
        $this->template->site('master_service/returjualbeli/view', $data);
    }
    
 
    public function add_jualbeli(){
        $this->auth->validate_authen('retur/add_jualbeli');
        $data = array();
        $param = array();

        if ($this->session->userdata("nama_group") != 'Root' ) {
           $param['kd_dealer'] = $this->session->userdata("kd_dealer");
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $param['field'] = "KD_LOKASI, NAMA_LOKASI";
        $param['groupby']= TRUE;
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang/true", $param));
        if($this->input->get("n")){
            $param=array(
                'no_trans' => $this->input->get("n"),
            );
            $data["hdr"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli",$param));
            $param["field"] ="TRANS_RETUR_JUALBELI_DETAIL.*,(SELECT PART_DESKRIPSI FROM MASTER_PART M WHERE M.PART_NUMBER=TRANS_RETUR_JUALBELI_DETAIL.PART_NUMBER) AS PART_DESKRIPSI";
            $data["dtl"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli_detail",$param));
        }
        
        $this->template->site('master_service/returjualbeli/add', $data);
    }
 
    public function simpan_jualbeli() {
        
            $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_trans('RJB'));
            $param = array(
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_lokasidealer' => $this->input->post("kd_lokasidealer"),
                'no_trans' => $ntrans,
                'tgl_trans' => $this->input->post("tgl_trans"),
                'jenis_retur' => $this->input->post("jenis_retur"),
                'no_reff' => $this->input->post("no_reff"),
                'tgl_reff' => $this->input->post("tgl_ref"),
                'created_by' => $this->session->userdata('user_id')
            );
 
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/retur_jualbeli", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data=json_decode($hasil);
            if($data){
                if($data->recordexists==true){
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                }
                //print_r($param);exit();
                $hasil = $this->curl->simple_put(API_URL . "/api/accounting/retur_jualbeli", $param, array(CURLOPT_BUFFERSIZE => 10));

                $this->simpan_jualbeli_dtl($ntrans);
            }
 
            $this->data_output($hasil, 'post',base_url("retur/add_jualbeli?n=").$ntrans); 
    }

    function simpan_jualbeli_dtl($no_trans){
        $param=array(
            'no_trans' => $no_trans,
            'part_number' => $this->input->post('part_number'),
            'jumlah'    => $this->input->post('qty'),
            'harga'     => (double)$this->input->post('harga'),
            'kd_gudang' => PartDefaultBin($this->input->post('part_number'),'KD_GUDANG'),
            'kd_rakbin'    => PartDefaultBin($this->input->post('part_number'),'KD_LOKASI'),
            'created_by'=> $this->session->userdata("user_id")."|".$this->input->post('diskon'),
            'keterangan'=> $this->input->post('Keterangan')
        );
        $hasil = $this->curl->simple_post(API_URL . "/api/accounting/retur_jualbeli_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
        $data=json_decode($hasil);
        if($data){
            if($data->recordexists==true){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
            }
            $hasil = $this->curl->simple_put(API_URL . "/api/accounting/retur_jualbeli_detail", $param, array(CURLOPT_BUFFERSIZE => 10));

        }
        return $hasil;
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
     

    public function delete_jualbeli($no_trans) {    
        $param = array(
            'no_trans' => $no_trans,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
       $data=json_decode($this->curl->simple_delete(API_URL."/api/accounting/retur_jualbeli",$param));
 
        $this->data_output($data, 'delete', base_url('retur/jualbeli'));
    }
    public function delete_jualbeli_dtl($id,$notrans=null) {    
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data=json_decode($this->curl->simple_delete(API_URL."/api/accounting/retur_jualbeli_detail",$param));
        //$this->data_output($data, 'delete');
       redirect(base_url("retur/add_jualbeli?n=").$ntrans);
    }
    
    public function jualbeli_typeahead() {
        $param = array(
            "custom" => "TRANS_RETUR_JUALBELI.KD_DEALER= '".$this->session->userdata('kd_dealer')."'"
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli", $param));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_TRANS;
            $data_message[1][$key] = $message->JENIS_RETUR;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }
    
    public function retur_typeahead($jenis) {

        if ($jenis == 'penjualan') {
            $param = array(
                'jointable' => array(
                    array("TRANS_PKB_DETAIL PD", "PD.NO_PKB=TRANS_PKB.NO_PKB AND PD.ROW_STATUS>=0", "LEFT")
                ),
                'custom' => "((PD.PICKING_STATUS IS NULL OR PD.PICKING_STATUS = 0) AND (TRANS_PKB.STATUS_PKB <= 5)) AND PD.KATEGORI = 'Part' AND TRANS_PKB.KD_DEALER = '" . $this->session->userdata("kd_dealer") . "'",
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'field' => 'TRANS_PKB.NO_PKB',                  
                'groupby' => true
            );

            $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));

            // var_dump($data); exit();
            $result['keyword'] = [];

            if (is_array($data->message)) {

                foreach ($data->message as $key => $message) {
                    $data_message[0][$key] = $message->NO_PKB;
                }
                $result['keyword'] = array_merge($data_message[0]);
            }
            $this->output->set_output(json_encode($result));

        }elseif($jenis == 'pembelian') {
            $param = array(
                'jointable' => array(
                    array("TRANS_PART_TERIMADETAIL PD", "PD.NO_TRANS=TRANS_PART_TERIMA.NO_TRANS AND PD.ROW_STATUS>=0", "LEFT")
                ),
                'custom' => "((PD.ROW_STATUS IS NULL OR PD.ROW_STATUS = 0) AND TRANS_PART_TERIMA.ROW_STATUS =0)",
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'field' => 'TRANS_PART_TERIMA.NO_TRANS',
                'groupby' => TRUE
            );
 
            $data = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima", $param));
 
            $result['keyword'] = [];
 
            if (is_array($data->message)) {
 
                foreach ($data->message as $key => $message) {
                    $data_message[0][$key] = $message->NO_TRANS;
                }
                $result['keyword'] = array_merge($data_message[0]);
            }
 
            $this->output->set_output(json_encode($result));
        // var_dump($data);
        }
    }
   
    public function get_retur($jenis) {
        if ($jenis == 'penjualan') {
            $param = array(
                'no_pkb' => $this->input->get('no_trans'),
                'jointable' => array(
                    array("TRANS_PKB TP", "TP.NO_PKB=TRANS_PKB_DETAIL.NO_PKB AND TP.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_PART MP", "MP.PART_NUMBER=TRANS_PKB_DETAIL.KD_PEKERJAAN AND MP.ROW_STATUS>=0", "LEFT"),
                ),
                "field" => "TRANS_PKB_DETAIL.KD_PEKERJAAN AS PART_NUMBER, TRANS_PKB_DETAIL.ID, TRANS_PKB_DETAIL.QTY AS JUMLAH,  MP.PART_DESKRIPSI, TS.TANGGAL_PKB",
                'custom' => "(TRANS_PKB_DETAIL.PICKING_STATUS > 0 ) AND TRANS_PKB_DETAIL.KATEGORI = 'Part'"
            );
 
            $data = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/pkb_detail", $param));
        } elseif ($jenis == 'pembelian') {
            $param = array(
                'no_trans' => $this->input->get('no_trans'),
                'jointable' => array(
                    array("TRANS_PART_TERIMA TTP", "TTP.NO_TRANS=TRANS_PART_TERIMADETAIL.NO_TRANS AND TTP.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_PART MP", "MP.PART_NUMBER=TRANS_PART_TERIMADETAIL.PART_NUMBER AND MP.ROW_STATUS>=0", "LEFT") 
                ),
                "field" => "TRANS_PARTS_TERIMADETAIL.PART_NUMBER, TRANS_PART_TERIMADETAIL.ID, TRANS_PART_TERIMADETAIL.QTY AS JUMLAH, MP.PART_DESKRIPSI",
                'custom' => "(TRANS_PART_TERIMADETAIL.ROW_STATUS >= 0)"
            );
 
            $data = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima", $param));

        } 
        if ($data) {
            if ($data ->totaldata > 0) {
                echo json_encode($data->message);
            }
        } else {
            echo "[]";
        }
    }

    function penjualan($debug=null){
        $param=array(
            'no_trans'=> $this->input->get("n"),
            'custom'  => "ISNULL(PICKING_STATUS,0) >1"
        );
        if(substr($this->input->get("n"), 0,2)=='WO') { 
            $param["custom"] .=" AND KATEGORI='Part'";
            $param["field"] ="NO_PKB AS NO_TRANS,KD_PEKERJAAN AS PART_NUMBER,(SELECT PART_DESKRIPSI FROM MASTER_PART WHERE PART_NUMBER=KD_PEKERJAAN)PART_DESKRIPSI,QTY AS JUMLAH,HARGA_SATUAN AS HARGA,ISNULL(DISKON,0)DISKON,(SELECT CONVERT(CHAR,TANGGAL_PKB,103)TGL_TRANS FROM TRANS_PKB P WHERE P.NO_PKB=TRANS_PKB_DETAIL.NO_PKB)TGL_TRANS";
        }else{
            $param["field"] ="NO_TRANS,PART_NUMBER,(SELECT PART_DESKRIPSI FROM MASTER_PART M WHERE M.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER)PART_DESKRIPSI,JUMLAH_ORDER AS JUMLAH,HARGA_JUAL AS HARGA,ISNULL(DISKON,0)DISKON,(SELECT CONVERT(CHAR,TGL_TRANS,103)TGL_TRANS FROM TRANS_PARTSO P WHERE P.NO_TRANS=TRANS_PARTSO_DETAIL.NO_TRANS)TGL_TRANS";
        }
        $data =(substr($this->input->get("n"), 0,2)=='SP')? 
            json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso_detail", $param)):
            json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail", $param));
        //print_r($param);
        if($data){
            if($data->totaldata >0){
                echo json_encode($data->message);
            }else{
                echo "[]";
            }
        }else{
            echo "[]";
        }
    }  
    function pembelian(){
        $param=array(
            'no_trans' => $this->input->get('n'),
            'field'    => "NO_TRANS,PART_NUMBER,(SELECT PART_DESKRIPSI FROM MASTER_PART M WHERE M.PART_NUMBER=TRANS_PART_TERIMADETAIL.PART_NUMBER)PART_DESKRIPSI,JUMLAH,NETPRICE AS HARGA,ISNULL(DISKON,0) AS DISKON,(SELECT CONVERT(CHAR,TGL_TRANS,103)TGL_TRANS FROM TRANS_PART_TERIMA P WHERE P.NO_TRANS=TRANS_PART_TERIMADETAIL.NO_TRANS)TGL_TRANS"
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terimadetail", $param));
        if($data){
            if($data->totaldata >0){
                echo json_encode($data->message);
            }else{
                echo "[]";
            }
        }else{
            echo "[]";
        }
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

