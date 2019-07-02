<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Accounting extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
        $this->load->helper("zetro");
        $this->load->model('Custom_model');
    }
    /**
    * Generate result data from select query
    * @param string $tabel_name
    * @param array $param
    * @param string $method default 'get'
    * @param boolean $status default FALSE = not authorizes
    */
    function resultdata($tabel_name,$param='',$method='get',$status=FALSE){
        $this->Main_model->tabel_name($tabel_name);
        $this->Main_model->set_parameter($param);
        $result = $this->Main_model->response_result($method,$status);
        $this->response($result);
    }
    /**
     * [uangmasuk_get description]
     * @return [type] [description]
     */
    public function uangmasuk_get(){
        $param = array();$search='';
        
        if($this->get("kd_dealer")){ $param["KD_DEALER"] = $this->get("kd_dealer");}
        if($this->get("no_trans")){ $param["NO_TRANS"]   = $this->get("no_trans");}
        if($this->get("nomor")){ $param["NOMOR"]  = $this->get("nomor");}
        if($this->get("tgl_trans")){ $param["TGL_TRANS"] = $this->get("tgl_trans");}
        if($this->get("jenis_trans")) {
            $param["JENIS_TRANS"]  = $this->get("jenis_trans");
        }   
        if($this->get("type_trans")) {
            $param["TYPE_TRANS"]  = $this->get("type_trans");
        }
        if($this->get("pstatus")) {
            $param["POSTING_STATUS"]  = $this->get("pstatus");
        }
        if($this->get("voucher_no")) {
            $param["VOUCHER_NO"]  = $this->get("voucher_no");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "TGL_TRANS"     => $this->get("keyword"),
                "JENIS_TRANS"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_UANGMASUK",$param);
    }
    /**
     * [uangmasuk_post description]
     * @return [type] [description]
     */
    public function uangmasuk_post(){
        $param = array();
        $param["NO_TRANS"]      = $this->post("no_trans");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $this->Main_model->data_sudahada($param,"TRANS_UANGMASUK");
        $param["TGL_TRANS"]     = tglToSql($this->post("tgl_trans"));
        $param["TYPE_TRANS"]    = $this->post("type_trans");
        $param["JENIS_TRANS"]   = $this->post("jenis_trans");
        $param["NO_REFF"]       = $this->post("no_reff");
        $param["SOURCE_REFF"]   = $this->post("reff_source");
        $param["KET_REFF"]      = ($this->post("ket_reff"));
        $param["POSTING_STATUS"]= 0;//$this->post("kd_customer");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_TRANS_UANGMASUK_INSERT",$param,'post',TRUE);
    }
    /**
     * [uangmasuk_put description]
     * @return [type] [description]
     */
    public function uangmasuk_put(){
        $param = array();
        $param["NO_TRANS"]      = $this->put("no_trans");
        $param["KD_DEALER"]     = $this->put("kd_dealer");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["TGL_TRANS"]     = tglToSql($this->put("tgl_trans"));
        $param["TYPE_TRANS"]    = $this->put("type_trans");
        $param["JENIS_TRANS"]   = $this->put("jenis_trans");
        $param["NO_REFF"]       = $this->put("no_reff");
        $param["SOURCE_REFF"]   = $this->put("reff_source");
        $param["KET_REFF"]      = ($this->put("ket_reff"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_UPDATE",$param,'put',TRUE);
    }
    public function uangmasuk_app_put(){
        $param["NO_TRANS"]      = $this->put("no_trans");
        $param["VOUCHER_NO"]       = $this->put("voucher_no");
        $param["VOUCHER_DATE"]   = $this->put("voucher_date");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_APV",$param,'put',TRUE);
    }
    /**
     * [uangmasuk_delete description]
     * @return [type] [description]
     */
    public function uangmasuk_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [uangmasuk_detail_get description]
     * @return [type] [description]
     */
    public function uangmasuk_detail_get(){
        $param = array();$search='';
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]        = $this->get("no_trans");
        }
        if($this->get("no_urut")){
            $param["NO_URUT"]     = $this->get("no_urut");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "NO_URUT"    => $this->get("keyword")
            );
        }
        //only if join with uangmasuk
        if($this->get("voucher_no")) {
            $param["TU.VOUCHER_NO"]  = trim($this->get("voucher_no"));
        }

        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_UANGMASUK_DETAIL",$param);
    }
    /**
     * [uangmasuk_detail_post description]
     * @return [type] [description]
     */
    public function uangmasuk_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["NO_URUT"]           = $this->post("no_urut");
        $this->Main_model->data_sudahada($param,"TRANS_UANGMASUK_DETAIL");
        $param["URAIAN_TRANSAKSI"]  = $this->post("uraian_transaksi");
        $param["JUMLAH"]            = $this->post("jumlah");
        $param["HARGA"]             = $this->post("harga");
        $param["SALDO_AWAL"]        = $this->post("saldo_awal");
        $param["KD_ACCOUNT"]        = $this->post("kd_account");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");
        $param["SALDO_AKHIR"]       = ($this->post("saldo_akhir"))?$this->post("saldo_akhir"):"0";
        $this->resultdata("SP_TRANS_UANGMASUK_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [uangmasuk_detail_put description]
     * @return [type] [description]
     */
    public function uangmasuk_detail_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NO_URUT"]           = $this->put("no_urut");
        $param["URAIAN_TRANSAKSI"]  = $this->put("uraian_transaksi");
        $param["JUMLAH"]            = $this->put("jumlah");
        $param["HARGA"]             = $this->put("harga");
        $param["SALDO_AWAL"]        = $this->put("saldo_awal");
        $param["KD_ACCOUNT"]        = $this->put("kd_account");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["SALDO_AKHIR"]       = ($this->put("saldo_akhir"))?$this->put("saldo_akhir"):"0";
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_DETAIL_UPDATE",$param,'put',TRUE);
    }
    public function um_detail_put(){
        $param["ID"]          = $this->put("id");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["LKH"]               = 1;//$this->put("lkh");
        $param["POS_AKUN"]          = $this->put("pos_akun");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_DETAIL_POSTING",$param,'put',TRUE);
    }
    /**
     * [uangmasuk_detail_delete description]
     * @return [type] [description]
     */
    public function uangmasuk_detail_delete(){
        $param = array();
        $param["ID"]          = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_DETAIL_DELETE",$param,'delete',TRUE);
    }
    
    public function kasir_openclose_get(){
        $param = array();$search='';
        
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]        = $this->get("kd_dealer");
        }
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"]     = $this->get("kd_lokasi");
        }
        if($this->get("open_date")){
            $param["OPEN_DATE"]     = $this->get("open_date");
        }
        if($this->get("reopen")){
            $param["REOPEN"]     = $this->get("reopen");
        }
        if($this->get("close_date")){
            $param["CLOSE_DATE"]     = $this->get("close_date");
        }
        if($this->get("kd_trans")){
            $param["KD_TRANS"]     = $this->get("kd_trans");
        }
        /*if($this->get("keyword")){
            $param=array();
            $search=array(
                "TRANS_ID"      => $this->get("keyword"),
                "NO_URUT"    => $this->get("keyword")
            );
        }*/
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_KASIR",$param);
    }
    public function kasir_openclose_post(){
        $param=array();
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["KD_LOKASI"]     = $this->post('kd_lokasi');
        $param["KD_TRANS"]      = $this->post('kd_trans');
        $param["OPEN_DATE"]     = $this->post('open_date');
        $this->Main_model->data_sudahada($param,"TRANS_KASIR");
        $param["SALDO_AWAL"]    = $this->post('saldo_awal');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_KASIR_INSERT",$param,'post',TRUE);
    }
    public function kasir_openclose_put(){
        $param=array();
        $param["ID"] = $this->put('idtrans');
        $param["CLOSE_DATE"]     = $this->put('close_date');
        $param["SALDO_AKHIR"]    = $this->put('saldo_akhir');
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]    = $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_KASIR_UPDATE",$param,'put',TRUE);
    }
    public function kasir_reopen_put(){
        $param["ID"] = $this->put('id');
        $param["REOPEN"]     = $this->put('openstatus');
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]    = $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_KASIR_REOPEN",$param,'put',TRUE);
    }
    public function saldo_akhir_get(){

    }

    /**
     * Kode akun
     */
    public function kdakun_get(){
        $param=array();$search='';
        if($this->get("kd_akun")){ $param["KD_AKUN"] = $this->get("kd_akun");}
        if($this->get("tipe")){ $param["TIPE"] = $this->get("tipe");}
        if($this->get("subtipe")){ $param["SUBTIPE"] = $this->get("subtipe");}
        if($this->get("jenis")){ $param["JENIS"] = $this->get("jenis");}
        if($this->get("subjenis")){ $param["SUBJENIS"] = $this->get("subjenis");}
        if($this->get("subsubjenis")){ $param["SUBSUBJENIS"] = $this->get("subsubjenis");}

        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_AKUN"      => $this->get("keyword"),
                "NAMA_AKUN"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_ACC_KODEAKUN_V",$param);
    }

    /**
     * [kdakun_post description]
     * @return [type] [description]
     */
    public function kdakun_post(){
        $param = array();
        $param["KD_AKUN"]       = $this->post("kd_akun");
        $this->Main_model->data_sudahada($param,"MASTER_ACC_KODEAKUN");
        $param["NAMA_AKUN"]     = $this->post("nama_akun");
        $param["SALDO_AWAL"]    = $this->post("saldo_awal");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_MASTER_ACC_KODEAKUN_INSERT",$param,'post',TRUE);
    }
    /**
     * [kdakun_put description]
     * @return [type] [description]
     */
    public function kdakun_put(){
        $param = array();
        $param["KD_AKUN"]          = $this->put("kd_akun");
        $param["NAMA_AKUN"]        = $this->put("nama_akun");
        $param["SALDO_AWAL"]       = $this->put("saldo_awal");
        $param["ROW_STATUS"]       = $this->put("row_status");
        $param["LASTMODIFIED_BY"]  = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_ACC_KODEAKUN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kdakun_delete description]
     * @return [type] [description]
     */
    public function kdakun_delete(){
        $param = array();
        $param["KD_AKUN"]          = $this->delete("kd_akun");
        $param["LASTMODIFIED_BY"]  = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_ACC_KODEAKUN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [uangmasuk_cbayar_get description]
     * @return [type] [description]
     */
    public function uangmasuk_cbayar_get(){
        $param = array();$search='';
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]        = $this->get("no_trans");
        }
        if($this->get("cara_bayar")){
            $param["CARA_BAYAR"]        = $this->get("cara_bayar");
        }
        if ($this->get("no_rekening")) {
            $param["NO_REKENING"]  = $this->get("no_rekening");
        }
        if($this->get("no_cheque")){
            $param["NO_CHEQUE"]     = $this->get("no_cheque");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "CARA_BAYAR"     => $this->get("keyword"),
                "NO_REKENING"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_UANGMASUK_CBAYAR",$param);
    }
    /**
     * [uangmasuk_cbayar_post description]
     * @return [type] [description]
     */
    public function uangmasuk_cbayar_post(){
        $param = array();
        $param["NO_TRANS"]      = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_UANGMASUK_CBAYAR");
        $param["NO_REKENING"]   = $this->post("no_rekening");
        $param["CARA_BAYAR"]    = $this->post("cara_bayar");
        $param["NO_CHEQUE"]     = $this->post("no_cheque");
        $param["NAMA_BANK"]     = $this->post("nama_bank");
        $param["JTH_TEMPO"]     = tglToSql($this->post("jth_tempo"));
        $param["NO_KWITANSI"]   = $this->post("no_kwitansi");
        $param["STATUS_PRINT"]  = $this->post("status_print");
        $param["PRINTED_BY"]    = $this->post("printed_by");
        $param["PRINTED_TIME"]  = ($this->put("printed_time"))?tglToSql($this->put("printed_time")):date('Ymd');
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_TRANS_UANGMASUK_CBAYAR_INSERT",$param,'post',TRUE);
    }
    /**
     * [uangmasuk_cbayar_put description]
     * @return [type] [description]
     */
    public function uangmasuk_cbayar_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["CARA_BAYAR"]        = $this->put("cara_bayar");
        $param["NO_REKENING"]       = $this->put("no_rekening");
        $param["NO_CHEQUE"]         = $this->put("no_cheque");
        $param["NAMA_BANK"]         = $this->put("nama_bank");
        $param["JTH_TEMPO"]         = tglToSql($this->put("jth_tempo"));
        $param["NO_KWITANSI"]       = $this->put("no_kwitansi");
        $param["STATUS_PRINT"]      = $this->put("status_print");
        $param["PRINTED_BY"]        = $this->put("printed_by");
        $param["PRINTED_TIME"]      =($this->put("printed_time"))?tglToSql($this->put("printed_time")):date('Ymd');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_CBAYAR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [uangmasuk_cbayar_delete description]
     * @return [type] [description]
     */
    public function uangmasuk_cbayar_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_UANGMASUK_CBAYAR_DELETE",$param,'delete',TRUE);
    }
     public function uangmasuk_cbayar_upd_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NO_KWITANSI"]       = $this->put("no_kwitansi");
        $param["LASTMODIFIED_BY"]   = substr($this->put("lastmodified_by"),0,22);
        $param["JENIS_PRINT"]       = $this->put("jenis_print");
        $this->resultdata("SP_TRANS_UANGMASUK_CBAYAR_PRINT",$param,'delete',TRUE);
    }

    public function auto_jurnal_get(){
        $param = array();$search='';
        
        if($this->get("kd_trans")){
            $param["KD_TRANS"]        = $this->get("kd_trans");
        }
        if($this->get("tp_trans")){
            $param["TYPE_TRANS"]        = $this->get("tp_trans");
        }
        if($this->get("js_trans")){
            $param["JENIS_TRANS"]        = $this->get("js_trans");
        }
        
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_UANGMASUK_CBAYAR",$param);
    }
    function lkh_get($list=null){
         $param=array();
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]  = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("tgl_trans")){
            $param["TGL_TRANS"]     = tglToSql($this->get("tgl_trans"));
        }

        $this->Main_model->set_custom_query($this->Custom_model->laporankasharian($param,$list));
       //echo $this->Main_model->get_custom_query();
        $this->resultdata("TRANS_UANGMASUK",$param=array());
    }
    function laporanlkh_get(){
        $kd_dealer =$this->get("kd_dealer");
        $startdate =($this->get("tgl_trans"));
        $endate =($this->get("tgl_end"))?($this->get("tgl_end")):($this->get("tgl_trans"));
        $saldoawal=($this->get("saldoawal"))?$this->get("saldoawal"):0;
        $this->Main_model->set_custom_query($this->Custom_model->lkh($kd_dealer,$startdate,$endate,$saldoawal));
        $this->resultdata("TRANS_UANGMASUK",$param=array());
    }
    function lkhcustom_get(){
        $param=array();
        $kd_dealer =$this->get("kd_dealer");
        $tgl_trans =($this->get("tgl_trans"));
        $output = $this->get('output');
        $this->Main_model->set_custom_query($this->Custom_model->report_lkh($kd_dealer,$output,$tgl_trans));
        $this->resultdata("TRANS_UANGMASUK",$param=array());
    }
    function biayabpkb_get(){
        $data_id = $this->get("stnk_id");
        $status = $this->get("status");
        $this->Main_model->set_custom_query($this->Custom_model->paybill_bpkb($data_id,$status));
        $this->resultdata("TRANS_UANGMASUK",$param=array());
    }
    /**
     * [setup_trans_vs_acc_get description]
     * @return [type] [description]
     */
    public function setup_trans_vs_acc_get(){
        $param = array();$search='';
        
        if($this->get("kd_trans")){
            $param["KD_TRANS"]        = $this->get("kd_trans");
        }
        if($this->get("nama_trans")){
            $param["NAMA_TRANS"]        = $this->get("nama_trans");
        }
        if ($this->get("kd_group")) {
            $param["KD_GROUP"]  = $this->get("kd_group");
        }
        if($this->get("kd_akun")){
            $param["KD_AKUN"]     = $this->get("kd_akun");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_TRANS"      => $this->get("keyword"),
                "NAMA_TRANS"     => $this->get("keyword"),
                "KD_GROUP"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_TRANS_VS_ACC",$param);
    }
    /**
     * [setup_trans_vs_acc_post description]
     * @return [type] [description]
     */
    public function setup_trans_vs_acc_post(){
        $param = array();
        $param["KD_TRANS"]      = $this->post("kd_trans");
        $param["NAMA_TRANS"]   = $this->post("nama_trans");
        $param["KD_GROUP"]    = $this->post("kd_group");
        $param["KD_SUBGROUP"]     = $this->post("kd_subgroup");
        $param["KD_AKUN"]     = $this->post("kd_akun");
        $this->Main_model->data_sudahada($param,"SETUP_TRANS_VS_ACC");
        $param["POSISI_AKUN"]     = $this->post("posisi_akun");
        $param["KD_AKUN_BALANCE"]   = $this->post("kd_akun_balance");
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_SETUP_TRANS_VS_ACC_INSERT",$param,'post',TRUE);
    }
    /**
     * [setup_trans_vs_acc_put description]
     * @return [type] [description]
     */
    public function setup_trans_vs_acc_put(){
        $param = array();
        $param["ID"]          = $this->put("id");
        $param["KD_TRANS"]          = $this->put("kd_trans");
        $param["NAMA_TRANS"]        = $this->put("nama_trans");
        $param["KD_GROUP"]       = $this->put("kd_group");
        $param["KD_SUBGROUP"]         = $this->put("kd_subgroup");
        $param["KD_AKUN"]         = $this->put("kd_akun");
        $param["POSISI_AKUN"]         = $this->put("posisi_akun");
        $param["KD_AKUN_BALANCE"]       = $this->put("kd_akun_balance");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_TRANS_VS_ACC_UPDATE",$param,'put',TRUE);
    }
    /**
     * [setup_trans_vs_acc_delete description]
     * @return [type] [description]
     */
    public function setup_trans_vs_acc_delete(){
        $param = array();
        $param["ID"]          = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_TRANS_VS_ACC_DELETE",$param,'delete',TRUE);
    }



    /**
     * [kasir_register_get description]
     * @return [type] [description]
     */
    public function kasir_register_get(){
        $param = array();$search='';
        
        
        if($this->get("no_register")){
            $param["NO_REGISTER"]     = $this->get("no_register");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]        = $this->get("no_trans");
        }

        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_REGISTER"    => $this->get("keyword"),
                "NO_TRANS"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_KASIR_REGISTER",$param);
    }
    /**
     * [kasir_register_post description]
     * @return [type] [description]
     */
    public function kasir_register_post(){
        $param = array();
        $param["NO_REGISTER"]          = $this->post("no_register");
        $param["NO_TRANS"]             = $this->post("no_trans");
        $param["KD_MAINDEALER"]        = $this->post("kd_maindealer");
        $param["KD_DEALER"]            = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"TRANS_KASIR_REGISTER");
        $param["TGL_REGISTER"]          = tglToSql($this->post("tgl_register"));
        $param["NO_KWT"]                = $this->post("no_kwt");
        $param["URAIAN_REGISTER"]       = $this->post("uraian_register");
        $param["STATUS_PRINT"]        = $this->post("status_print");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_KASIR_REGISTER_INSERT",$param,'post',TRUE);
    }
    /**
     * [kasir_register_put description]
     * @return [type] [description]
     */
    public function kasir_register_put(){
        $param = array();
        $param["NO_REGISTER"]          = $this->put("no_register");
        $param["TGL_REGISTER"]           = tglToSql($this->put("tgl_register"));
        $param["KD_MAINDEALER"]  = $this->put("kd_maindealer");
        $param["KD_DEALER"]            = $this->put("kd_dealer");
        $param["NO_TRANS"]             = $this->put("no_trans");
        $param["NO_KWT"]        = $this->put("no_kwt");
        $param["URAIAN_REGISTER"]        = $this->put("uraian_register");
        $param["STATUS_PRINT"]        = $this->put("status_print");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_KASIR_REGISTER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kasir_register_delete description]
     * @return [type] [description]
     */
    public function kasir_register_delete(){
        $param = array();
        $param["ID"]          = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_KASIR_REGISTER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [retur_jualbeli_get description]
     * @return [type] [description]
     */
    public function retur_jualbeli_get(){
        $param = array();$search='';
        
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }

        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("jenis_retur")){
            $param["JENIS_RETUR"]     = $this->get("jenis_retur");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "JENIS_RETUR"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_RETUR_JUALBELI",$param);
    }
    /**
     * [retur_jualbeli_post description]
     * @return [type] [description]
     */
    public function retur_jualbeli_post(){
        $param = array();
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["NO_TRANS"]      = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_RETUR_JUALBELI");
        $param["TGL_TRANS"]     = tglToSql($this->post("tgl_trans"));
        $param["JENIS_RETUR"]   = $this->post("jenis_retur");
        $param["STATUS_RETUR"]  = "0";//$this->post("status_retur");
        $param["NO_REFF"]       = $this->post("no_reff");
        $param["TGL_REFF"]      = tglToSql($this->post("tgl_reff"));
        $param["KETERANGAN"]    = '';//$this->post("keterangan");
        $param["KD_LOKASIDEALER"]= $this->post("kd_lokasidealer");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_TRANS_RETUR_JUALBELI_INSERT",$param,'post',TRUE);
    }
    /**
     * [retur_jualbeli_put description]
     * @return [type] [description]
     */
    public function retur_jualbeli_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["JENIS_RETUR"]       = $this->put("jenis_retur");
        $param["STATUS_RETUR"]      = "0";//$this->put("status_retur");
        $param["NO_REFF"]           = $this->put("no_reff");
        $param["TGL_REFF"]          = tglToSql($this->put("tgl_reff"));
        $param["KETERANGAN"]        = '';//$this->put("keterangan");
        $param["KD_LOKASIDEALER"]   = $this->put("kd_lokasidealer");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_RETUR_JUALBELI_UPDATE",$param,'put',TRUE);
    }
    /**
     * [retur_jualbeli_delete description]
     * @return [type] [description]
     */
    public function retur_jualbeli_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_RETUR_JUALBELI_DELETE",$param,'delete',TRUE);
    }

    /**
     * [retur_jualbeli_detail_get description]
     * @return [type] [description]
     */
    public function retur_jualbeli_detail_get(){
        $param = array();$search='';
        
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "PART_NUMBER"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_RETUR_JUALBELI_DETAIL",$param);
    }
    /**
     * [retur_jualbeli_detail_post description]
     * @return [type] [description]
     */
    public function retur_jualbeli_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["PART_NUMBER"]       = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"TRANS_RETUR_JUALBELI_DETAIL");
        $param["KD_GUDANG"]         = $this->post("kd_gudang");
        $param["KD_RAKBIN"]         = $this->post("kd_rakbin");
        $param["JUMLAH"]            = $this->post("jumlah");
        $param["HARGA"]             = $this->post("harga");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_RETUR_JUALBELI_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [retur_jualbeli_detail_put description]
     * @return [type] [description]
     */
    public function retur_jualbeli_detail_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["PART_NUMBER"]       = $this->put("part_number");
        $param["KD_GUDANG"]         = $this->put("kd_gudang");
        $param["KD_RAKBIN"]         = $this->put("kd_rakbin");
        $param["JUMLAH"]            = $this->put("jumlah");
        $param["HARGA"]             = $this->put("harga");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_RETUR_JUALBELI_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [retur_jualbeli_detail_delete description]
     * @return [type] [description]
     */
    public function retur_jualbeli_detail_delete(){
        $param = array();
        $param["ID"]          = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_RETUR_JUALBELI_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [retur_jualbeli_detail_put description]
     * @return [type] [description]
     */
    public function retur_picking_detail_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["PICKING_STATUS"]    = $this->put("picking_status");
        $param["PICKING_REFF"]      = $this->put("picking_reff");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_RETUR_PICKING_DETAIL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [bank_get description]
     * @return [type] [description]
     */
    public function bank_get(){
        $param = array();$search='';
        
        
        if($this->get("kd_bank")){
            $param["KD_BANK"]     = $this->get("kd_bank");
        }
        if($this->get("nama_bank")){
            $param["NAMA_BANK"]     = $this->get("nama_bank");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_BANK"      => $this->get("keyword"),
                "NAMA_BANK"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_BANK",$param);
    }
    /**
     * [bank_post description]
     * @return [type] [description]
     */
    public function bank_post(){
        $param = array();
        $param["KD_BANK"]           = $this->post("kd_bank");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"MASTER_BANK");
        $param["NAMA_BANK"]         = $this->post("nama_bank");
        $param["ALAMAT_BANK"]       = $this->post("alamat_bank");
        $param["KD_PROPINSI"]       = $this->post("kd_propinsi");
        $param["KD_KOTA"]           = $this->post("kd_kota");
        $param["NO_REKENING"]       = $this->post("no_rekening");
        $param["KD_AKUN"]           = $this->post("kd_akun");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_MASTER_BANK_INSERT",$param,'post',TRUE);
    }
    /**
     * [bank_put description]
     * @return [type] [description]
     */
    public function bank_put(){
        $param = array();
        $param["KD_BANK"]           = $this->put("kd_bank");
        $param["NAMA_BANK"]         = $this->put("nama_bank");
        $param["ALAMAT_BANK"]       = $this->put("alamat_bank");
        $param["KD_PROPINSI"]       = $this->put("kd_propinsi");
        $param["KD_KOTA"]           = $this->put("kd_kota");
        $param["NO_REKENING"]       = $this->put("no_rekening");
        $param["KD_AKUN"]           = $this->put("kd_akun");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_BANK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [bank_delete description]
     * @return [type] [description]
     */
    public function bank_delete(){
        $param = array();
        $param["KD_BANK"]           = $this->delete("kd_bank");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_BANK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [range_nopajak_get description]
     * @return [type] [description]
     */
    public function range_nopajak_get(){
        $param = array();$search='';
        
        
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("range1")){
            $param["RANGE1"]     = $this->get("range1");
        }
        if($this->get("range2")){
            $param["RANGE2"]     = $this->get("range2");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "RANGE1"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_RANGE_NOPAJAK",$param);
    }
    /**
     * [range_nopajak_post description]
     * @return [type] [description]
     */
    public function range_nopajak_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["RANGE1"]        = $this->post("range1");
        $param["RANGE2"]        = $this->post("range2");
        $this->Main_model->data_sudahada($param,"MASTER_RANGE_NOPAJAK");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_MASTER_RANGE_NOPAJAK_INSERT",$param,'post',TRUE);
    }
    /**
     * [range_nopajak_put description]
     * @return [type] [description]
     */
    public function range_nopajak_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["RANGE1"]            = $this->put("range1");
        $param["RANGE2"]            = $this->put("range2");
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_RANGE_NOPAJAK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [range_nopajak_delete description]
     * @return [type] [description]
     */
    public function range_nopajak_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_RANGE_NOPAJAK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [terimabank_get description]
     * @return [type] [description]
     */
    function generate_terimabank_get(){
        $param=array();$query="";
        $this->Custom_model->set_logines($this->get("user_login"));
        $kd_dealer=$this->get("kd_dealer");
        $query=$this->Custom_model->terima_bank($kd_dealer);
        //echo $query;
        $this->Main_model->set_custom_query($this->Custom_model->terima_bank($kd_dealer));
        $this->resultdata("TRANS_TERIMABANK",$param,"get",TRUE);
    }
    public function terimabank_get($view=null,$blm_close=null){
        $param = array();$search='';
        
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("tgl_trans")){
            $param["TGL_TRANS"]     = $this->get("tgl_trans");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "TGL_TRANS"    => $this->get("keyword")
            );
        }
        if($view){
            $frd = $this->get("from_date");
            $tod = $this->get("to_date");
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($view){
            if($blm_close){
                $this->resultdata("TRANS_TAGIHAN_BAYAR",$param);
            }else{
                $this->resultdata("TRANS_TERIMABANK_VIEW",$param);
            }
            
        }else{
            $this->resultdata("TRANS_TERIMABANK",$param);
        }
    }
    /**
     * [terimabank_post description]
     * @return [type] [description]
     */
    public function terimabank_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["KD_BANK"]           = $this->post("kd_bank");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_TERIMABANK");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["KD_TRANS"]          = $this->post("kd_trans");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["TIPE_TRANS"]        = $this->post("tipe_trans");
        $param["AWAL"]              = "0";//$this->post("awal");
        $param["DEBET"]             = $this->post("debet");
        $param["KREDIT"]            = $this->post("kredit");
        $param["AKHIR"]             = "0";//$this->post("akhir");
        $param["TUTUP"]             = $this->post("tutup");
        $param["RINCI"]             = $this->post("rinci");
        $param["KD_AKUN"]           = $this->post("kd_akun");
        $param["JENIS_TRANS"]       = $this->post("jenis_trans");
        $param["JUMLAH"]            = $this->post("jumlah");
        $param["STATUS_TRANS"]      = $this->post("status_trans");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_TERIMABANK_INSERT",$param,'post',TRUE);
    }
    /**
     * [terimabank_put description]
     * @return [type] [description]
     */
    public function terimabank_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["KD_BANK"]           = $this->put("kd_bank");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["KD_TRANS"]          = $this->put("kd_trans");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["TIPE_TRANS"]        = $this->put("tipe_trans");
        $param["AWAL"]              = "0";//$this->put("awal");
        $param["DEBET"]             = $this->put("debet");
        $param["KREDIT"]            = $this->put("kredit");
        $param["AKHIR"]             = "0";//$this->put("akhir");
        $param["TUTUP"]             = $this->put("tutup");
        $param["RINCI"]             = $this->put("rinci");
        $param["KD_AKUN"]           = $this->put("kd_akun");
        $param["JENIS_TRANS"]       = $this->put("jenis_trans");
        $param["JUMLAH"]            = $this->put("jumlah");
        $param["STATUS_TRANS"]      = $this->put("status_trans");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_TERIMABANK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [terimabank_delete description]
     * @return [type] [description]
     */
    public function terimabank_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_TERIMABANK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [trans_get description]
     * @return [type] [description]
     */
    public function trans_get(){
        $param = array();$search='';
        
        
        if($this->get("kd_trans")){
            $param["KD_TRANS"]     = $this->get("kd_trans");
        }
        if($this->get("nama_trans")){
            $param["NAMA_TRANS"]     = $this->get("nama_trans");
        }
        if($this->get("kasir")){
            $param["kasir_trans"] = $this->get("kasir");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_TRANS"      => $this->get("keyword"),
                "NAMA_TRANS"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_TRANS",$param);
    }
    /**
     * [trans_post description]
     * @return [type] [description]
     */
    public function trans_post(){
        $param = array();
        $param["KD_TRANS"]      = $this->post("kd_trans");
        $param["TIPE_TRANS"]          = $this->post("tipe_trans");
        $param["TIPE_AR"]            = $this->post("tipe_ar");
        $this->Main_model->data_sudahada($param,"MASTER_TRANS");
        $param["NAMA_TRANS"]    = $this->post("nama_trans");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_MASTER_TRANS_INSERT",$param,'post',TRUE);
    }
    /**
     * [trans_put description]
     * @return [type] [description]
     */
    public function trans_put(){
        $param = array();
        $param["KD_TRANS"]          = $this->put("kd_trans");
        $param["NAMA_TRANS"]        = $this->put("nama_trans");
        $param["TIPE_TRANS"]        = $this->put("tipe_trans");
        $param["TIPE_AR"]           = $this->put("tipe_ar");
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_TRANS_UPDATE",$param,'put',TRUE);
    }
    /**
     * [trans_delete description]
     * @return [type] [description]
     */
    public function trans_delete(){
        $param = array();
        $param["KD_TRANS"]          = $this->delete("kd_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_TRANS_DELETE",$param,'delete',TRUE);
    }

    /**
     * [acc_mapping_get description]
     * @return [type] [description]
     */
    public function acc_mapping_get(){
        $param = array();$search='';
        if($this->get("kd_akun_lama")){
            $param["KD_AKUN_LAMA"]     = $this->get("kd_akun_lama");
        }
        if($this->get("kd_akun_baru")){
            $param["KD_AKUN_BARU"]     = $this->get("kd_akun_baru");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_AKUN_LAMA"      => $this->get("keyword"),
                "KD_AKUN_BARU"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_ACC_MAPPING",$param);
    }
    /**
     * [acc_mapping_post description]
     * @return [type] [description]
     */
    public function acc_mapping_post(){
        $param = array();
        $param["KD_AKUN_LAMA"]  = $this->post("kd_akun_lama");
        $param["KD_AKUN_BARU"]  = $this->post("kd_akun_baru");
        $this->Main_model->data_sudahada($param,"SETUP_ACC_MAPPING");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_SETUP_ACC_MAPPING_INSERT",$param,'post',TRUE);
    }
    /**
     * [acc_mapping_put description]
     * @return [type] [description]
     */
    public function acc_mapping_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_AKUN_LAMA"]      = $this->put("kd_akun_lama");
        $param["KD_AKUN_BARU"]      = $this->put("kd_akun_baru");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_ACC_MAPPING_UPDATE",$param,'put',TRUE);
    }
    /**
     * [acc_mapping_delete description]
     * @return [type] [description]
     */
    public function acc_mapping_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_ACC_MAPPING_DELETE",$param,'delete',TRUE);
    }

    /**
     * [penerimaan_get description]
     * @return [type] [description]
     */
    public function penerimaan_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_barang")){
            $param["KD_BARANG"]     = $this->get("kd_barang");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "NAMA_BARANG"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("PENERIMAAN_VIEW",$param);
    }

    /**
     * [pengeluaran_get description]
     * @return [type] [description]
     */
    public function pengeluaran_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_barang")){
            $param["KD_BARANG"]     = $this->get("kd_barang");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "NAMA_BARANG"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("PENGELUARAN_VIEW",$param);
    }

    /**
     * [acc_saldoawal_get description]
     * @return [type] [description]
     */
    public function acc_saldoawal_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_akun")){
            $param["KD_AKUN"]     = $this->get("kd_akun");
        }
        if($this->get("nama_akun")){
            $param["NAMA_AKUN"]     = $this->get("nama_akun");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_AKUN"      => $this->get("keyword"),
                "NAMA_AKUN"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_ACC_SALDOAWAL",$param);
    }
    /**
     * [acc_saldoawal_post description]
     * @return [type] [description]
     */
    public function acc_saldoawal_post(){
        $param = array();
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["KD_AKUN"]       = $this->post("kd_akun");
        $param["NAMA_AKUN"]     = $this->post("nama_akun");
        $this->Main_model->data_sudahada($param,"TRANS_ACC_SALDOAWAL");
        $param["SALDO_AWAL"]    = $this->post("saldo_awal");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_TRANS_ACC_SALDOAWAL_INSERT",$param,'post',TRUE);
    }
    /**
     * [acc_saldoawal_put description]
     * @return [type] [description]
     */
    public function acc_saldoawal_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["KD_AKUN"]           = $this->put("kd_akun");
        $param["NAMA_AKUN"]         = $this->put("nama_akun");
        $param["SALDO_AWAL"]        = $this->put("saldo_awal");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_ACC_SALDOAWAL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [acc_saldoawal_delete description]
     * @return [type] [description]
     */
    public function acc_saldoawal_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_ACC_SALDOAWAL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [fakturpajak_get description]
     * @return [type] [description]
     */
    public function fakturpajak_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_FAKTURPAJAK",$param);
    }
    /**
     * [fakturpajak_post description]
     * @return [type] [description]
     */
    public function fakturpajak_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["NO_PAJAK"]          = $this->post("no_pajak");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["TGL_PAJAK"]         = tglToSql($this->post("tgl_pajak"));
        $param["TGL_SURATJALAN"]    = tglToSql($this->post("tgl_suratjalan"));
        $param["KD_CUSTOMER"]       = $this->post("kd_customer");
        $param["NAMA_CUSTOMER"]     = $this->post("nama_customer");
        $param["ALAMAT_CUSTOMER"]   = $this->post("alamat_customer");
        $param["NPWP_CUSTOMER"]     = $this->post("npwp_customer");
        $param["STATUS_FAKTUR"]     = $this->post("status_faktur");
        $param["JENIS_FAKTUR"]      = $this->post("jenis_faktur");
        $this->Main_model->data_sudahada($param,"TRANS_FAKTURPAJAK");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_FAKTURPAJAK_INSERT",$param,'post',TRUE);
    }
    /**
     * [fakturpajak_put description]
     * @return [type] [description]
     */
    public function fakturpajak_put(){
        $param = array();
        //$param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NO_PAJAK"]          = $this->put("no_pajak");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["TGL_PAJAK"]         = tglToSql($this->put("tgl_pajak"));
        $param["TGL_SURATJALAN"]    = tglToSql($this->put("tgl_suratjalan"));
        $param["KD_CUSTOMER"]       = $this->put("kd_customer");
        $param["NAMA_CUSTOMER"]     = $this->put("nama_customer");
        $param["ALAMAT_CUSTOMER"]   = $this->put("alamat_customer");
        $param["NPWP_CUSTOMER"]     = $this->put("npwp_customer");
        $param["STATUS_FAKTUR"]     = $this->put("status_faktur");
        $param["JENIS_FAKTUR"]      = $this->put("jenis_faktur");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FAKTURPAJAK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [fakturpajak_delete description]
     * @return [type] [description]
     */
    public function fakturpajak_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FAKTURPAJAK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [fakturpajak_detail_get description]
     * @return [type] [description]
     */
    public function fakturpajak_detail_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_itemfaktur")){
            $param["KD_ITEMFAKTUR"]     = $this->get("kd_itemfaktur");
        }
        if($this->get("nama_itemfaktur")){
            $param["NAMA_ITEMFAKTUR"]     = $this->get("nama_itemfaktur");
        }
        if($this->get("no_faktur")){
            $param["NO_FAKTUR"]     = $this->get("no_faktur");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "NAMA_ITEMFAKTUR"    => $this->get("keyword"),
                "NO_FAKTUR"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_FAKTURPAJAK_DETAIL",$param);
    }
    /**
     * [fakturpajak_detail_post description]
     * @return [type] [description]
     */
    public function fakturpajak_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["NO_PAJAK"]          = $this->post("no_pajak");
        $param["KD_ITEMFAKTUR"]     = $this->post("kd_itemfaktur");
        $param["NAMA_ITEMFAKTUR"]   = $this->post("nama_itemfaktur");
        $param["NO_FAKTUR"]         = $this->post("no_faktur");
        $param["TGL_FAKTUR"]        = tglToSql($this->post("tgl_faktur"));
        $param["QTY_ITEM"]          = $this->post("qty_item");
        $param["HARGA_ITEM"]        = $this->post("harga_item");
        $param["DISC_ITEM"]         = $this->post("disc_item");
        $param["DPP_FAKTUR"]        = $this->post("dpp_faktur");
        $param["PPN_FAKTUR"]        = $this->post("ppn_faktur");
        $param["BIAYA_STNK"]        = $this->post("biaya_stnk");
        $param["NO_RANGKA"]         = $this->post("no_rangka");
        $param["NO_MESIN"]          = $this->post("no_mesin");
        $param["CREATED_BY"]        = $this->post("created_by");
        // $this->Main_model->data_sudahada($param,"TRANS_FAKTURPAJAK_DETAIL");
        $this->resultdata("SP_TRANS_FAKTURPAJAK_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [fakturpajak_detail_put description]
     * @return [type] [description]
     */
    public function fakturpajak_detail_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NO_PAJAK"]          = $this->put("no_pajak");
        $param["KD_ITEMFAKTUR"]     = $this->put("kd_itemfaktur");
        $param["NAMA_ITEMFAKTUR"]   = $this->put("nama_itemfaktur");
        $param["QTY_ITEM"]          = $this->put("qty_item");
        $param["HARGA_ITEM"]        = $this->put("harga_item");
        $param["DISC_ITEM"]         = $this->put("disc_item");
        $param["DPP_FAKTUR"]        = $this->put("dpp_faktur");
        $param["PPN_FAKTUR"]        = $this->put("ppn_faktur");
        $param["BIAYA_STNK"]        = $this->put("biaya_stnk");
        $param["NO_RANGKA"]         = $this->put("no_rangka");
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["NO_FAKTUR"]         = $this->put("no_faktur");
        $param["TGL_FAKTUR"]        = tglToSql($this->put("tgl_faktur"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FAKTURPAJAK_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [fakturpajak_detail_delete description]
     * @return [type] [description]
     */
    public function fakturpajak_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FAKTURPAJAK_DETAIL_DELETE",$param,'delete',TRUE);
    }


    /**
     * [weekly_gb_get description]
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    public function range_seripajak_get(){
        $kd_dealer  = $this->get("kd_dealer");
        $range_seri   = $this->get("range_seri");

        // var_dump($_GET);exit;

        $this->Main_model->set_custom_query($this->Custom_model->range_seri($kd_dealer,$range_seri));
        
        $this->resultdata("TRANS_FAKTURPAJAK",$param=array());
    }

    /**
     * [piutang_get description]
     * @return [type] [description]
     */
    public function piutang_get($view=null,$jenis=0){
        $param = array();$search='';
        if($this->get("kd_maindealer")){$param["KD_MAINDEALER"] = $this->get("kd_maindealer");}
        if($this->get("kd_dealer")){ $param["KD_DEALER"]        = $this->get("kd_dealer");}
        if($this->get("no_trans")){ $param["NO_TRANS"]          = $this->get("no_trans");}
        if($this->get("no_reff")){ $param["NO_REFF"]          = $this->get("no_reff");}
        if($this->get("kd_piutang")){ $param["KD_PIUTANG"]      = $this->get("kd_piutang");}
        if($this->get("kd_kupon")){ $param["KD_SALESKUPON"]     = $this->get("kd_kupon");}
        if($this->get("tagihanke")){ $param["TAGIHANKE"]        = $this->get("tagihanke");}
        if($this->get("kd_fincoy")){ $param["KD_FINCOY"]        = $this->get("kd_fincoy");}
        if(strlen($this->get("status_piutang"))>0){ $param["STATUS_PIUTANG"]     = $this->get("status_piutang");}
        if(strlen($this->get("apv_piutang"))>0){ $param["APV_PIUTANG"]     = $this->get("apv_piutang");}
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "KD_PIUTANG"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($view){
            switch ((int)$jenis) {
                case 1:
                    $this->resultdata("TRANS_PIUTANG_NONTUNAI",$param);
                    break;
                case 2:
                    $this->resultdata("TRANS_PIUTANG_LEASING",$param);
                    break;
                case 3:
                    $this->resultdata("TRANS_PIUTANG_KUPON",$param);
                    break;
                case 4:
                    $this->resultdata("TRANS_PIUTANG_PROGRAM",$param);
                    break;
                case 5:
                    $this->resultdata("TRANS_PIUTANG_JOINPROMO",$param);
                    break;
                default:
                    $this->resultdata("TRANS_PIUTANG_VIEW",$param);
                    break;
            }
            
        }else{
            $this->resultdata("TRANS_PIUTANG",$param);
        }
    }
    /**
     * [piutang_post description]
     * @return [type] [description]
     */
    public function piutang_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["KD_PIUTANG"]        = $this->post("kd_piutang");
        $this->Main_model->data_sudahada($param,"TRANS_PIUTANG");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["TGL_PIUTANG"]       = tglToSql($this->post("tgl_piutang"));
        $param["REFF_PIUTANG"]      = $this->post("reff_piutang");
        $param["URAIAN_PIUTANG"]    = $this->post("uraian_piutang");
        $param["JUMLAH_PIUTANG"]    = $this->post("jumlah_piutang");
        $param["CARA_BAYAR"]        = $this->post("cara_bayar");
        $param["TGL_TEMPO"]         = tglToSql($this->post("tgl_tempo"));
        $param["STATUS_PIUTANG"]    = $this->post("status_piutang");
        // $param["APV_PIUTANG"]       = $this->post("apv_piutang");
        // $param["APV_DATE"]          = tglToSql($this->post("apv_date"));
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_PIUTANG_INSERT",$param,'post',TRUE);
    }
    /**
     * [piutang_put description]
     * @return [type] [description]
     */
    public function piutang_put(){
        $param = array();
        //$param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["KD_PIUTANG"]        = $this->put("kd_piutang");
        $param["TGL_PIUTANG"]       = tglToSql($this->put("tgl_piutang"));
        $param["REFF_PIUTANG"]      = $this->put("reff_piutang");
        $param["URAIAN_PIUTANG"]    = $this->put("uraian_piutang");
        $param["JUMLAH_PIUTANG"]    = $this->put("jumlah_piutang");
        $param["CARA_BAYAR"]        = $this->put("cara_bayar");
        $param["TGL_TEMPO"]         = tglToSql($this->put("tgl_tempo"));
        $param["STATUS_PIUTANG"]    = $this->put("status_piutang");
        // $param["APV_PIUTANG"]       = $this->put("apv_piutang");
        // $param["APV_DATE"]          = tglToSql($this->put("apv_date"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_UPDATE",$param,'put',TRUE);
    }
    /**
     * [piutang_delete description]
     * @return [type] [description]
     */
    public function piutang_delete(){
        $param = array();
        $param["NO_TRANS"]                = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_DELETE",$param,'delete',TRUE);
    }
    function piutang_apv_put(){
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["STATUS_PIUTANG"]    = $this->put("status_piutang");
        $param["APV_PIUTANG"]       = $this->put("apv_piutang");
        $param["APV_DATE"]          = tglToSql($this->put("apv_date"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_APPROVAL",$param,'put',TRUE);
    }
    function piutang_lunas_put(){
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NO_REFFBAYAR"]      = $this->put("no_reff");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_LUNAS",$param,'put',TRUE);
    }
    public function piutang_bayar_get(){

    }
    public function piutang_bayar_post(){
        $param=array();
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["NO_TRANS"]      = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_PIUTANG");
        $param["TGL_BAYAR"]     = tglToSql($this->post("tgl_bayar"));
        $param["JUMLAH_BAYAR"]  = (double)$this->post("jumlah_bayar");
        $param["REFF_BAYAR"]    = $this->post("reff_bayar");
        $param["KETERANGAN"]    = $this->post("keterangan");
        $param["SISA_BAYAR"]    = (double)$this->post("sisa_bayar");
        $param["RENCANA_BAYAR"] = tglToSql($this->post("rencana_bayar"));
        $param["NO_KWITANSI"]   = $this->post("no_kwitansi");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_TRANS_PIUTANG_BAYAR_INSERT",$param,'post',TRUE);
    }
    public function piutang_bayar_put(){
        $param=array();
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_DEALER"]     = $this->put("kd_dealer");
        $param["NO_TRANS"]      = $this->put("no_trans");
        $param["TGL_BAYAR"]     = tglToSql($this->put("tgl_bayar"));
        $param["JUMLAH_BAYAR"]  = (double)$this->put("jumlah_bayar");
        $param["REFF_BAYAR"]    = $this->put("reff_bayar");
        $param["KETERANGAN"]    = $this->put("keterangan");
        $param["SISA_BAYAR"]    = (double)$this->put("sisa_bayar");
        $param["RENCANA_BAYAR"] = tglToSql($this->put("rencana_bayar"));
        $param["NO_KWITANSI"]   = $this->put("no_kwitansi");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_BAYAR_UPDATE",$param,'put',TRUE);
    }
    public function piutang_bayar_delete(){
        $param = array();
        $param["NO_TRANS"]                = $this->delete("no_trans");
        $param["ROW_STATUS"]              = ($this->delete("row_status"))?$this->delete("row_status"):"-1";
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_BAYAR_DELETE",$param,'delete',TRUE);
    }
    public function piutang_reprint_put(){
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["KD_PIUTANG"]    = $this->put("kd_piutang");
        $param["REPRINT"]       = $this->put("reprint");
        $param["ALASAN_REPRINT"] = $this->put("alasan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PIUTANG_REPRINT",$param,'put',TRUE);
    }
    /**
     * [audit_kasir_get description]
     * @return [type] [description]
     */
    public function audit_kasir_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_lokasidealer")){
            $param["KD_LOKASIDEALER"]     = $this->get("kd_lokasidealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("tgl_trans")){
            $param["TGL_TRANS"]     = $this->get("tgl_trans");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_KASIR_AUDIT",$param);
    }
    /**
     * [audit_kasir_post description]
     * @return [type] [description]
     */
    public function audit_kasir_post(){
        $param = array();
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_KASIR_AUDIT");
        $param["KD_LOKASIDEALER"]   = $this->post("kd_lokasidealer");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["JUMLAH_TOTAL"]      = $this->post("jumlah_total");
        $param["JUMLAH_KAS"]      = $this->post("jumlah_kas");
        $param["JUMLAH_K100"]       = $this->post("jumlah_k100");
        $param["JUMLAH_K50"]        = $this->post("jumlah_k50");
        $param["JUMLAH_K20"]        = $this->post("jumlah_k20");
        $param["JUMLAH_K10"]        = $this->post("jumlah_k10");
        $param["JUMLAH_K5"]         = $this->post("jumlah_k5");
        $param["JUMLAH_K2"]         = $this->post("jumlah_k2");
        $param["JUMLAH_K1"]         = $this->post("jumlah_k1");
        $param["JUMLAH_L1000"]      = $this->post("jumlah_l1000");
        $param["JUMLAH_L500"]       = $this->post("jumlah_l500");
        $param["JUMLAH_L200"]       = $this->post("jumlah_l200");
        $param["JUMLAH_L100"]       = $this->post("jumlah_l100");
        $param["JUMLAH_L50"]        = $this->post("jumlah_l50");
        $param["SELISIH"]           = $this->post("selisih");
        $param["STATUS_AUDIT"]      = "0";//$this->post("status_audit");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_KASIR_AUDIT_INSERT",$param,'post',TRUE);
    }
    /**
     * [audit_kasir_put description]
     * @return [type] [description]
     */
    public function audit_kasir_put($upd_status=null){
        $param = array();
        // $param["ID"]                = $this->put("id");
        $param["KD_LOKASIDEALER"]   = $this->put("kd_lokasidealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["JUMLAH_TOTAL"]      = $this->put("jumlah_total");
        $param["JUMLAH_KAS"]      = $this->put("jumlah_kas");
        $param["JUMLAH_K100"]       = $this->put("jumlah_k100");
        $param["JUMLAH_K50"]        = $this->put("jumlah_k50");
        $param["JUMLAH_K20"]        = $this->put("jumlah_k20");
        $param["JUMLAH_K10"]        = $this->put("jumlah_k10");
        $param["JUMLAH_K5"]         = $this->put("jumlah_k5");
        $param["JUMLAH_K2"]         = $this->put("jumlah_k2");
        $param["JUMLAH_K1"]         = $this->put("jumlah_k1");
        $param["JUMLAH_L1000"]      = $this->put("jumlah_l1000");
        $param["JUMLAH_L500"]       = $this->put("jumlah_l500");
        $param["JUMLAH_L200"]       = $this->put("jumlah_l200");
        $param["JUMLAH_L100"]       = $this->put("jumlah_l100");
        $param["JUMLAH_L50"]        = $this->put("jumlah_l50");
        $param["SELISIH"]           = $this->put("selisih");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        if($upd_status){
            $param=array();
            $param["NO_TRANS"]          = $this->put("no_trans");
            $param["STATUS_AUDIT"]      = $this->put("status_audit");
            $param["KETERANGAN"]        = $this->put("keterangan");
            $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by")."|apv";
            $this->resultdata("SP_TRANS_KASIR_AUDIT_STATUS",$param,'put',TRUE);
        }else{
            $this->resultdata("SP_TRANS_KASIR_AUDIT_UPDATE",$param,'put',TRUE);
        }
    }
    /**
     * [audit_kasir_delete description]
     * @return [type] [description]
     */
    public function audit_kasir_delete(){
        $param = array();
        $param["NO_TRANS"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_KASIR_AUDIT_DELETE",$param,'delete',TRUE);
    }

    /**
     * [labarugi_get description]
     * @return [type] [description]
     */
    public function labarugi_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama")){
            $param["NAMA"]     = $this->get("nama");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NAMA"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_LABA_RUGI",$param);
    }
    /**
     * [labarugi_post description]
     * @return [type] [description]
     */
    public function labarugi_post(){
        $param = array();
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NAMA"]              = $this->post("nama");
        $this->Main_model->data_sudahada($param,"TRANS_LABA_RUGI");
        $param["UNIT"]              = $this->post("unit");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_LABA_RUGI_INSERT",$param,'post',TRUE);
    }
    /**
     * [labarugi_put description]
     * @return [type] [description]
     */
    public function labarugi_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NAMA"]              = $this->put("nama");
        $param["UNIT"]              = $this->put("unit");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_LABA_RUGI_UPDATE",$param,'put',TRUE);
    }
    /**
     * [labarugi_delete description]
     * @return [type] [description]
     */
    public function labarugi_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_LABA_RUGI_DELETE",$param,'delete',TRUE);
    }

    /**
     * [titipan_uang_get description]
     * @return [type] [description]
     */
    public function titipan_uang_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("no_reff")){
            $param["NO_REFF"]     = $this->get("no_reff");
        }
        if($this->get("status_titipan")){
            $param["STATUS_TITIPAN"]     = $this->get("status_titipan");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "NO_REFF"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_TITIPAN_UANG",$param);
    }
    /**
     * [titipan_uang_post description]
     * @return [type] [description]
     */
    public function titipan_uang_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_TITIPAN_UANG");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_KWITANSI"]       = $this->post("no_kwitansi");
        $param["NO_REFF"]           = $this->post("no_reff");
        $param["JUMLAH_TITIPAN"]    = $this->post("jumlah_titipan");
        $param["URAIAN_TITIPAN"]    = $this->post("uraian_titipan");
        $param["STATUS_TITIPAN"]    = $this->post("status_titipan");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_TITIPAN_UANG_INSERT",$param,'post',TRUE);
    }
    /**
     * [titipan_uang_put description]
     * @return [type] [description]
     */
    public function titipan_uang_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_REFF"]           = $this->put("no_reff");
        $param["JUMLAH_TITIPAN"]    = $this->put("jumlah_titipan");
        $param["NO_KWITANSI"]       = $this->put("no_kwitansi");
        $param["URAIAN_TITIPAN"]    = $this->put("uraian_titipan");
        $param["STATUS_TITIPAN"]    = $this->put("status_titipan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_TITIPAN_UANG_UPDATE",$param,'put',TRUE);
    }
    /**
     * [titipan_uang_delete description]
     * @return [type] [description]
     */
    public function titipan_uang_delete($fromspk=null){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_TITIPAN_UANG_DELETE",$param,'delete',TRUE);
    }
    public function biaya_ss_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_pengurus")){
            $param["NAMA_PENGURUS"]     = $this->get("nama_pengurus");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("status_stnk")){
            $param["STATUS_STNK"]     = $this->get("status_stnk");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NAMA_PENGURUS"      => $this->get("keyword"),
                "NO_RANGKA"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_STNK_SS",$param);
    }
}
?>