<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname extends CI_Controller {

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

    public function header1() {
        
        $data = array();
        $paramcustomer = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("MASTER_LOKASIDEALER AS MLD","MLD.KD_LOKASI=TRANS_STOCKOPNAME.KD_LOKASIDEALER","LEFT"),
                array("TRANS_STOCKOPNAME_DETAIL AS TSOD","TSOD.NO_TRANS=TRANS_STOCKOPNAME.NO_TRANS","LEFT")
            ),
            'field' => "TRANS_STOCKOPNAME.*,TSOD.KETERANGAN,TSOD.QTY_STOCK,MLD.NAMA_LOKASI",
            'groupby_text'=>"NO_TRANS"
        );

        //$data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$param));
        $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
        $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
        $param["custom"] ="CONVERT(CHAR,TRANS_STOCKOPNAME.TGL_TRANS,112) BETWEEN '".tglToSql($dari_tanggal)."' AND '".tglToSql($sampai_tanggal)."'";
        if($this->input->get('kd_dealer')){
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }else{
             $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname", $param));
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('stock_opname/header1', $data); //, $data
    }
    public function detail1(){
        $data = array();
        $paramcustomer = array();
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $kd_lokasi = $this->input->get('kd_lokasi') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_lokasi");
        $param=array(
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE)
        );
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$param));
        $data["gudang"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname_view",$param));         
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer())); 
        $param["field"] ="KD_GUDANG,NAMA_GUDANG,KD_DEALER";
        $param["orderby"] = "KD_GUDANG,KD_DEALER";
        $param["groupby_text"] = "NO_TRANS";
        //var_dump($data["gudang"]);print_r($param);exit();
        if($this->input->get("n")){
            $param=array("no_trans" => $this->input->get("n"));
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname", $param));
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname_detail", $param));
        }

        $this->template->site('stock_opname/detail1', $data); //, $data
    }

    public function get_item()
    {
        $param=array(
            'kd_dealer' => $this->input->get("kd_dealer"),
            'kd_gudang' => $this->input->get("kd_gudang"),
            'field'     => "NO_MESIN,NO_RANGKA,STOCK_AKHIR,KD_ITEM,NAMA_ITEM",
            'groupby'   => TRUE
        );
        $param["custom"] = "STOCK_AKHIR >0";
        $data= json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname_view",$param));
        $this->output->set_output(json_encode($data));      

    }
    /**
   * [simpan_header description]
   * @param  [type] $pohotline [description]
   * @return [type]            [description]
   */
    function simpan_header(){
        $param=array();
        $notrans=($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->notrans_st();
        $param=array(
          'kd_maindealer'   => $this->session->userdata("kd_maindealer"),
          'kd_dealer'       => ($this->input->post('kd_dealer'))?$this->input->post('kd_dealer'):$this->session->userdata("kd_dealer"),
          'kd_lokasidealer' => $this->input->post("kd_lokasi"),
          'no_trans'        => $notrans,
          'tgl_trans'       => ($this->input->post("tgl_trans")),
          'created_by'      => $this->session->userdata("user_id"),
          'jenis_opname'    => $this->input->post('jenis_opname'),
          'user_opname'     => $this->session->userdata("user_id"),
          'tgl_opname'      => ($this->input->post("tgl_trans"))
          
        );
        $hasil = ($this->curl->simple_post(API_URL."/api/inventori/stockopname", $param, array(CURLOPT_BUFFERSIZE => 10)));
        //var_dump($hasil);print_r($param);exit();
        if($hasil){
            $hasile=json_decode($hasil);
            if($hasile->recordexists){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL."/api/inventori/stockopname", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            $hasil=$this->simpan_detail($notrans);  
        }
        // echo json_encode(json_decode($hasil));
        $this->data_output($hasil,'post',base_url('stock_opname/detail1?n='.$notrans));
    }

    /**
    * [simpan_detail description]
    * @param  [type] $pohotline [description]
    * @return [type]            [description]
    */
    function simpan_detail($notrans){
        $details=json_decode($this->input->post('detail'),true);
        for($i=0;$i < count($details);$i++){
            $param=array(
                'no_trans'    => $notrans,
                'kd_gudang'   => $details[$i]["kd_gudang"],
                'kd_item'     => $details[$i]["kd_item"],
                'qty_stock'   => ($details[$i]["qty_stock"])?$details[$i]["qty_stock"]:"0",
                'qty_aktual'  => ($details[$i]["qty_aktual"])?$details[$i]["qty_aktual"]:"0",
                'selisih'     => ($details[$i]["selisih"])?$details[$i]["selisih"]:"0",
                'harga_jual'  => 0,
                'created_by'  => $this->session->userdata("user_id"),
                'keterangan'  => $details[$i]["keterangan"],
                'jenis_item'  => $details[$i]["jenis_item"],
                'groupby' => TRUE
            );
            $hasil = ($this->curl->simple_post(API_URL."/api/inventori/stockopname_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
            //var_dump($hasil);print_r($param);exit();
            if($hasil){
                $hasile=json_decode($hasil);
                if($hasile->recordexists==true){
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = ($this->curl->simple_put(API_URL."/api/inventori/stockopname_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
                } 
            }
            
        }
        return $hasil;
    }

    /**
   * [notrans_so description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function notrans_st($debug=null,$kode='OP'){
    $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => $kode,
            'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),//  substr($this->input->post('tgl_trans'),6,4),
            'limit' => 1,
            'offset' => 0
        );
        $bulan_kirim = date('m');// $this->input->post('bulan_kirim');
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomorpo == 0) {
            $nopo = $kode . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) .$param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = $kode . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) .$param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
        }
        if($debug){echo $nopo;}else{ return $nopo;}
  }

    public function delete_stock_opname_detail($id = null) {
        $param = array(
            'id' =>($id)?$id:0,// $this->input->get("id"),
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/stockopname_detail", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('stock_opname/detail1')
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

    public function sa_print() {
        
        $data = array();
        $paramcustomer = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("TRANS_STOCKOPNAME_DETAIL AS TSOD","TSOD.NO_TRANS=TRANS_STOCKOPNAME.NO_TRANS","LEFT")
            ),
            'field' => "TRANS_STOCKOPNAME.*"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname", $param));

         $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('sales/sa_print', $data); //, $data
    }
    function proposal_jplist($dataonly=null,$no_transonly=null){
        $data = array();
        $bulan=($this->input->get("bln"))?$this->input->get("bln"):date("m");
        $thn=($this->input->get("thn"))?$this->input->get("thn"):date("Y");
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page'],
            'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata('kd_dealer'),
            
        );
        if(!$this->input->get("a")){
            $param["custom"]= "MONTH(TGL_TRANS)=".$bulan." AND YEAR(TGL_TRANS)=".$thn;  
        }
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_detail", $param));
        $data["sharing"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_sharing", $param));
        $data["apv"] =json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_approval", $param));
        $forview="";
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
        );
        if($no_transonly){
            $param=array(
                'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata('kd_dealer')
            );
            $param["field"] ="NO_TRANS,TGL_JOINPROMO,KEGIATAN_JOINPROMO,TUJUAN_JOINPROMO,SUM(JUMLAH_JOINPROMO)TOTAL_HARGA,STATUS_JOINPROMO";
            $param["custom"] = "STATUS_JOINPROMO >= 4";
            $param["groupby_text"] = "NO_TRANS,TGL_JOINPROMO,KEGIATAN_JOINPROMO,TUJUAN_JOINPROMO,STATUS_JOINPROMO";
            $param["orderby"] = "NO_TRANS";
            $forview=TRUE;
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo/".$forview, $param));
        //var_dump($data["list"]);print_r($param);exit();
        if($dataonly){
            echo json_encode($data["list"]);
        }else{
            $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
            $param=array(
                "field"=>"YEAR(TGL_JOINPROMO) AS TAHUN",
                "groupby_text" => "YEAR(TGL_JOINPROMO)",
                "orderby" => "YEAR(TGL_JOINPROMO)"
            );
            $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo", $param));
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('accounting/proposal_jp_list',$data);
        }
    }
    /**
     * [proposal_jp description]
     * @param  [type] $dataonly [description]
     * @return [type]           [description]
     */
    public function proposal_jp($dataonly=null,$notrans=null) {
        $data = array();
        $param = array();
        
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $paramx=array("orderby"=>"NAMA_LEASING");
        $data["fincom"]     = json_decode($this->curl->simple_get(API_URL."/api/master/company_finance",$paramx));
        if($this->input->get("n")){
            $param["no_trans"] = base64_decode(urldecode($this->input->get("n")));
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo", $param));
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_detail", $param));
            $data["sharing"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_sharing", $param));
            $data["apv"] =json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_approval", $param));
        }
        if($dataonly==true){
            return $data["list"];
        }else{               
            $this->template->site('accounting/proposal_jp', $data);
        }
    }
    function simpan_proposal(){
        $details=json_decode($this->input->post('d'),true);
        $joinan=json_decode($this->input->post('s'),true);
        $defaultDealer=($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer");
        $param=array();
        $no_trans =($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->notrans_st(null,"JP");
        $param["kd_maindealer"] = $this->session->userdata("kd_maindealer");
        $param["kd_dealer"]     = $defaultDealer;
        $param["no_trans"]      = $no_trans;
        $param["tgl_trans"]     = ($this->input->post("tgl_trans"));
        $param["area_joinpromo"]= $this->input->post("area");
        $param["kegiatan_joinpromo"]    = $this->input->post("kegiatan");
        $param["tgl_joinpromo"] = ($this->input->post("tgl_joinpromo"));
        $param["tujuan_joinpromo"]      = $this->input->post("tujuan_joinpromo");
        $param["lokasi_joinpromo"]      = $this->input->post("lokasi_joinpromo");
        $param["target_audiens"]= $this->input->post("target_audiens");
        $param["target_sales"]  = $this->input->post("target_sales");
        $param["target_database"]       = $this->input->post("target_database");
        $param["ringkasan_joinpromo"]   = $this->input->post("ringkasan_joinpromo");
        $param["status_joinpromo"]      = "0";
        $param["created_by"]    = $this->session->userdata("user_id");
        $hasil = ($this->curl->simple_post(API_URL."/api/jurnal/joinpromo", $param, array(CURLOPT_BUFFERSIZE => 10)));
        //print_r($param);
        if($hasil){
            $hasile=json_decode($hasil);
            if($hasile->recordexists==true){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL."/api/jurnal/joinpromo", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            if($hasile->status){
                $this->simpan_detail_jp($details,$no_trans);
                $this->simpan_sharing_jp($joinan,$no_trans);
            }
        }
        $this->data_output($hasil,'post',base_url("stock_opname/proposal_jp?n=".urlencode(base64_encode($no_trans))));
    }
    function simpan_detail_jp($data,$no_trans){
        $hasil="";
        if($data){
            for($i=0; $i < count($data); $i++){
                $param = array();
                $param["no_trans"]      = $no_trans;
                $param["uraian_joinpromo"]      = $data[$i]["uraian"];
                $param["volume_joinpromo"]      = $data[$i]["volumne"];
                $param["satuan_joinpromo"]      = $data[$i]["satuan"];
                $param["harga_joinpromo"]       = str_replace(",", "",$data[$i]["harga"]);
                $param["jumlah_joinpromo"]      = str_replace(",", "",$data[$i]["jumlah"]);
                $param["keterangan_joinpromo"]  = $data[$i]["keterangan"];
                $param["created_by"]    = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_post(API_URL."/api/jurnal/joinpromo_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
                if($hasil){
                    $hasile=json_decode($hasil);
                    if($hasile->recordexists==true){
                        $param["lastmodified_by"] = $this->session->userdata("user_id");
                        $hasil = ($this->curl->simple_put(API_URL."/api/jurnal/joinpromo_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    }
                }
            }
        }
        return $hasil;
    }
    function simpan_sharing_jp($data,$no_trans){
        $hasil="";
        if($data){
            for($i=0; $i < count($data); $i++){
                $param = array();
                $param["no_trans"]      = $no_trans;
                $param["kd_leasing"]    = $data[$i]["kd_leasing"];
                $param["jumlah_sharing"]= $data[$i]["sharing"];
                $param["created_by"]    = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_post(API_URL."/api/jurnal/joinpromo_sharing", $param, array(CURLOPT_BUFFERSIZE => 10)));
                if($hasil){
                    $hasile=json_decode($hasil);
                    if($hasile->recordexists==true){
                        $param["lastmodified_by"] = $this->session->userdata("user_id");
                        $hasil = ($this->curl->simple_put(API_URL."/api/jurnal/joinpromo_sharing", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    }
                }
            }
        }
        return $hasil;
    }
    function approval_jp(){
        $data=array();
        $param["no_trans"]          = $this->input->post("no_trans");
        $param["approval_level"]    = $this->input->post("approval_level");
        $param["approval_by"]       = $this->input->post("approval_by");
        $param["approval_date"]     = $this->input->post("approval_date");
        $param["approval_status"]   = $this->input->post("approval_status");
        $param["approval_remarks"]  = $this->input->post("approval_remarks");
        $param["created_by"]        = $this->session->userdata("user_id");
        $hasil = ($this->curl->simple_post(API_URL."/api/jurnal/joinpromo_approval", $param, array(CURLOPT_BUFFERSIZE => 10)));
        //print_r($param);
        if($hasil){
            $hasile=json_decode($hasil);
            if($hasile->recordexists==true){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL."/api/jurnal/joinpromo_approval", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        $this->data_output($hasil,'post');
    }
    function proposal_del(){
        $param=array(
            'no_trans'  => $this->input->get("n")
        );
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $hasil = ($this->curl->simple_delete(API_URL."/api/jurnal/joinpromo", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->data_output($hasil,'delete');
    }
    function proposal_jp_print(){
        $data = array();
        $param = array();
        if($this->input->get("n")){
            $param["no_trans"] = base64_decode(urldecode($this->input->get("n")));
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo", $param));
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_detail", $param));
            $data["sharing"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_sharing", $param));
            $data["apv"] =json_decode($this->curl->simple_get(API_URL . "/api/jurnal/joinpromo_approval", $param));
        }
        $this->load->view('accounting/proposal_jp_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function cetak_spk(){
        $this->load->library('dompdf_gen');
        $data = array();
        $paramcustomer = array();
        
        $param = array(
            'keyword'    => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset'     => ($this->input->get('page')== null)?0:$this->input->get('page', TRUE),
            'limit'      => 15,
            'jointable'  => array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS =0","LEFT")),
            'field'      => "TRANS_SPK.*,MC.NAMA_CUSTOMER,SK.ID AS LEASINGID,SK.KD_FINCOY,SK.HASIL,SK.KETERANGAN KET",
            'orderby'    => 'TRANS_SPK.NO_SPK DESC,ID DESC'
        );
        $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
        $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
        $param["custom"] ="CONVERT(CHAR,TRANS_SPK.TGL_SPK,112) BETWEEN '".tglToSql($dari_tanggal)."' AND '".tglToSql($sampai_tanggal)."'";
        if($this->input->get('kd_dealer')){
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }else{
             $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        }
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $param));
        //var_dump(($data["list"]));exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));


        $html = $this->load->view('stock_opname/cetak_spk', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');

    }

    public function list_part($dataonly=null)
    {
        $data = array();
        //var_dump($data["gudang"]);print_r($param);exit();
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        if($this->input->get("no_trans")){
            switch ($this->input->get("keterangan")) {
                case 'B':
                    $keterangan = "QTY_AKTUAL = 0 AND QTY_STOCK > 0 AND JENIS_ITEM = 'PART'";
                    break;
                case 'C':
                    $keterangan = "QTY_AKTUAL = QTY_STOCK AND JENIS_ITEM = 'PART'";
                    break;
                case 'D':
                    $keterangan = "QTY_AKTUAL > 0 AND QTY_STOCK > 0 AND (QTY_AKTUAL - QTY_STOCK) > 0 AND JENIS_ITEM = 'PART'";
                    break;
                case 'E':
                    $keterangan = "QTY_AKTUAL > 0 AND QTY_STOCK = 0 AND JENIS_ITEM = 'PART'";
                    break;
                case 'F':
                    $keterangan = "QTY_AKTUAL > 0 AND QTY_STOCK > 0 AND (QTY_AKTUAL - QTY_STOCK) < 0 AND JENIS_ITEM = 'OLI'";
                    break;
                case 'G':
                    $keterangan = "QTY_AKTUAL = QTY_STOCK AND JENIS_ITEM = 'OLI'";
                    break;
                case 'H':
                    $keterangan = "QTY_AKTUAL > 0 AND QTY_STOCK > 0 AND (QTY_AKTUAL - QTY_STOCK) > 0 AND JENIS_ITEM = 'OLI'";
                    break;
                default:
                    $keterangan = "QTY_AKTUAL > 0 AND QTY_STOCK > 0 AND (QTY_AKTUAL - QTY_STOCK) < 0 AND JENIS_ITEM = 'PART'";
                    break;
            }

            $param = array(
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'no_trans' => $this->input->get("no_trans"),
                'custom' => $keterangan
            );

            switch ($this->input->get("jenis")) {
                case 'Detail':
                    $param['field'] = "KD_ITEM, KETERANGAN, HARGA_JUAL, KD_GUDANG, KD_RAKBIN, QTY_STOCK, QTY_AKTUAL";
                    break;
                
                default:
                    $param['field'] = "KD_ITEM, KETERANGAN, HARGA_JUAL, MAX(KD_GUDANG) KD_GUDANG, MAX(KD_RAKBIN) KD_RAKBIN, SUM(QTY_STOCK) QTY_STOCK, SUM(QTY_AKTUAL) QTY_AKTUAL";
                    $param['groupby'] = true;
                    break;
            }

            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname_detail", $param));

            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();

        }

        // $this->output->set_output(json_encode($data));
        $this->template->site('stock_opname/list_part', $data); 
    
    }

    public function add_stock()
    {
        $this->parts4gen();

        $data = array();
        //var_dump($data["gudang"]);print_r($param);exit();
        if($this->input->get("n")){
            $param=array(
                "no_trans" => $this->input->get("n")
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname", $param));
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname_detail", $param));
        }

        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        $this->template->site('stock_opname/add_part', $data); 
        // $this->output->set_output(json_encode($data));

    }

    public function delete_detail() {
        $param = array(
            'id' => $this->input->get('id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/inventori/stockopname_detail", $param));
        $this->data_output($data, 'delete');
    }

    public function get_partheder()
    {
        $data = array();

        $param=array(
            "kd_dealer" => ($this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")),
            "jenis_opname" => 'PART'
        );
        $list = json_decode($this->curl->simple_get(API_URL . "/api/inventori/stockopname", $param));

        // $list=array();
        if($list){
            if($list->totaldata >0) {
                $data = array(
                    'p' => $this->input->get('q'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => $list->message
                );
            }else{
                $data=array(
                    'p' => $this->input->get('p'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => array()
                );
            }
        }else{
            $data=array(
                'p' => $this->input->get('p'), 
                'count' => 0,//$list->totaldata, 
                'per_page' => $this->input->get('per_page'), 
                'data' => array()
            );
        }
        
        // $this->output->set_output(json_encode($list));
        $this->output->set_output(json_encode($data));
    }

    public function get_stock($dataonly=null)
    {
        $offset     = ($this->input->get('p')-1)*$this->input->get('per_page');
        $kd_dealer  = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $kd_lokasi  = $this->input->get('kd_lokasi') ? $this->input->get('kd_lokasi') : $this->session->userdata("kd_lokasi");
        $user_login = str_replace("-","",$this->session->userdata("user_id"));

        $rand=strtoupper($kd_dealer."_".substr($user_login,0,10));

        if($this->input->get('q')){
            $param['keyword']   = $this->input->get('q');
            // $param['custom']   = "(PART_NUMBER LIKE '%".$this->input->get('q')."%' OR PART_DESKRIPSI LIKE '%".$this->input->get('q')."%')";
        }else{
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');
        }

        $param['id']            = '40';
        $param['kd_lokasi']     = $kd_lokasi;
        $param["part_number"]   = $this->input->get("part_number");
        $param["kd_rakbin"]     = $this->input->get("kd_rakbin");
        $param["kd_gudang"]     = $this->input->get("kd_gudang");
        $param['kd_dealer']     = $kd_dealer;
        $param['user_login']    = $user_login;
        $param['field']         = "TRANS_PARTSTOCK_VIEW.*, CONCAT(TRANS_PARTSTOCK_VIEW.PART_NUMBER,'|',TRANS_PARTSTOCK_VIEW.KD_GUDANG,'|',TRANS_PARTSTOCK_VIEW.KD_RAKBIN,'|',TRANS_PARTSTOCK_VIEW.KD_LOKASI,'|',TRANS_PARTSTOCK_VIEW.JUMLAH,'|',TRANS_PARTSTOCK_VIEW.HARGA_JUAL,'|',TRANS_PARTSTOCK_VIEW.PART_DESKRIPSI,'|',TRANS_PARTSTOCK_VIEW.KD_GROUPSALES) AS VALUE_ALL";
        $param['custom']        = "TRANS_PARTSTOCK_VIEW.KD_RAKBIN IS NOT NULL";

        if($this->input->get('no_trans')){
            $param['custom']    = "TRANS_PARTSTOCK_VIEW.KD_RAKBIN IS NOT NULL AND TRANS_PARTSTOCK_VIEW.PART_NUMBER NOT IN ( SELECT SOD.KD_ITEM FROM TRANS_STOCKOPNAME_DETAIL SOD WHERE SOD.NO_TRANS='".$this->input->get('no_trans')."' AND SOD.KD_ITEM = TRANS_PARTSTOCK_VIEW.PART_NUMBER AND SOD.KD_GUDANG = TRANS_PARTSTOCK_VIEW.KD_GUDANG AND SOD.KD_RAKBIN = TRANS_PARTSTOCK_VIEW.KD_RAKBIN AND SOD.ROW_STATUS >= 0)";
        }

        $list = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));

        $data=array();
        if($list){
            if($list->totaldata >0) {
                $data = array(
                    'p' => $this->input->get('q'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => $list->message
                );
            }else{
                $data=array(
                    'p' => $this->input->get('p'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => array()
                );
            }
        }else{
            $data=array(
                'p' => $this->input->get('p'), 
                'count' => 0,//$list->totaldata, 
                'per_page' => $this->input->get('per_page'), 
                'data' => array()
            );
        }
        if($dataonly){
            $this->output->set_output(json_encode($list));
        }
        else{
            $this->output->set_output(json_encode($data));
        }
    }

    public function store_sopart()
    {
        $param=array();
        $notrans=($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->notrans_st();
        $param=array(
          'kd_maindealer'   => $this->session->userdata("kd_maindealer"),
          'kd_dealer'       => ($this->input->post('kd_dealer'))?$this->input->post('kd_dealer'):$this->session->userdata("kd_dealer"),
          'kd_lokasidealer' => $this->input->post("kd_lokasidealer"),
          'no_trans'        => $notrans,
          'tgl_trans'       => $this->input->post("tgl_trans"),
          'jenis_opname'    => $this->input->post('jenis_opname'),
          'user_opname'     => $this->session->userdata("user_id"),
          'tgl_opname'      => $this->input->post("tgl_trans"),
          'created_by'      => $this->session->userdata("user_id"),
          'lastmodified_by' => $this->session->userdata("user_id"),
        );
        $hasil = ($this->curl->simple_post(API_URL."/api/inventori/stockopname", $param, array(CURLOPT_BUFFERSIZE => 10)));
        //var_dump($hasil);print_r($param);exit();
        if($hasil){
            $hasile=json_decode($hasil);
            if($hasile->recordexists){
                $hasil = ($this->curl->simple_put(API_URL."/api/inventori/stockopname", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }

            $hasil=$this->simpan_detailpart($notrans); 

            $id =  json_decode($hasil)->message;
        }
        // echo json_encode(json_decode($id));
        $this->data_output($hasil,'post',$notrans,$id);

    }

    public function simpan_detailpart($notrans){
        $param=array(
            'no_trans'          => $notrans,
            'kd_item'           => $this->input->post("kd_item"),
            'kd_gudang'         => $this->input->post("kd_gudang"),
            'kd_rakbin'         => $this->input->post("kd_rakbin"),
            'qty_stock'         => ($this->input->post("qty_stock"))?$this->input->post("qty_stock"):"0",
            'qty_aktual'        => ($this->input->post("qty_aktual"))?$this->input->post("qty_aktual"):"0",
            'keterangan'        => $this->input->post("keterangan"),
            'harga_jual'        => $this->input->post("harga_jual"),
            'jenis_item'        => $this->input->post("jenis_item"),
            'created_by'        => $this->session->userdata("user_id"),
            'lastmodified_by'   => $this->session->userdata("user_id"),
        );
        $hasil = ($this->curl->simple_post(API_URL."/api/inventori/stockopname_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
        //var_dump($hasil);print_r($param);exit();
        if($hasil){
            $hasile=json_decode($hasil);
            if($hasile->recordexists==true){
                $hasil = ($this->curl->simple_put(API_URL."/api/inventori/stockopname_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
            } 
        }
            
        return $hasil;
    }

    function parts4gen($debug=null){
        $param = array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->inpu->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'kd_lokasi' =>($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi"),
            'user_login' => str_replace("-","",$this->session->userdata("user_id"))
        );
        $direct=$this->input->get("d");
        $gndata=$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param);
        if($debug){echo json_encode($gndata);}
    }

    function data_output($hasil = NULL, $method = null,$location=null,$dataid=null) {
        $result = "";
        switch ($method) {  
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan",
                        'location' => $location,
                        'dataid' => $dataid
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
