<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Jurnal extends REST_Controller {

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
     * [acc_jurnal_oto_get description]
     * @return [type] [description]
     */
    public function acc_jurnal_oto_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_transaksi")){
            $param["KD_TRANSAKSI"]     = $this->get("kd_transaksi");
        }
        if($this->get("type_transaksi")){
            $param["TYPE_TRANSAKSI"]     = $this->get("type_transaksi");
        }
        if($this->get("cara_bayar")){
            $param["CARA_BAYAR"]     = $this->get("cara_bayar");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_TRANSAKSI"      => $this->get("keyword"),
                "TYPE_TRANSAKSI"    => $this->get("keyword")
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
        $this->resultdata("SETUP_JURNAL_OTO_V",$param);
    }
    /**
     * [acc_jurnal_oto_post description]
     * @return [type] [description]
     */
    public function acc_jurnal_oto_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_TRANSAKSI"]      = $this->post("kd_transaksi");
        $param["TYPE_TRANSAKSI"]    = $this->post("type_transaksi");
        $param["CARA_BAYAR"]        = $this->post("cara_bayar");
        $param["KD_AKUN"]           = $this->post("kd_akun");
        $param["SUB_AKUN"]          = $this->post("sub_akun");
        $param["TYPE_AKUN"]         = $this->post("type_akun");
        $this->Main_model->data_sudahada($param,"SETUP_ACC_JURNAL_OTO",TRUE);
        $param["PERIODE_TAHUN"]     = $this->post("periode_tahun");
        $param["PERIODE_BULAN"]     = $this->post("periode_bulan");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_SETUP_ACC_JURNAL_OTO_INSERT",$param,'post',TRUE);
    }
    /**
     * [acc_jurnal_oto_put description]
     * @return [type] [description]
     */
    public function acc_jurnal_oto_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_TRANSAKSI"]      = $this->put("kd_transaksi");
        $param["TYPE_TRANSAKSI"]    = $this->put("type_transaksi");
        $param["CARA_BAYAR"]        = $this->put("cara_bayar");
        $param["PERIODE_TAHUN"]     = $this->put("periode_tahun");
        $param["PERIODE_BULAN"]     = $this->put("periode_bulan");
        $param["KD_AKUN"]           = $this->put("kd_akun");
        $param["SUB_AKUN"]          = $this->put("sub_akun");
        $param["TYPE_AKUN"]         = $this->put("type_akun");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_ACC_JURNAL_OTO_UPDATE",$param,'put',TRUE);
    }
    /**
     * [acc_jurnal_oto_delete description]
     * @return [type] [description]
     */
    public function acc_jurnal_oto_delete($allItem=null){
        $param = array();
        $param["ID"]    = $this->delete("id");
        $param["ALL"]   =($allItem)?"Y":"N";       
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_ACC_JURNAL_OTO_DELETE",$param,'delete',TRUE);
    }

    /**
     * [jurnal_get description]
     * @return [type] [description]
     */
    public function jurnal_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }

        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_posdealer")){
            $param["KD_POSDEALER"]     = $this->get("kd_posdealer");
        }
        if($this->get("no_jurnal")){
            $param["NO_JURNAL"]     = $this->get("no_jurnal");
        }
        if($this->get("type_jurnal")){
            $param["TYPE_JURNAL"]     = $this->get("type_jurnal");
        }
        if($this->get("source_jurnal")){
            $param["SOURCE_JURNAL"] = $this->get("source_jurnal");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_JURNAL"      => $this->get("keyword"),
                "TYPE_JURNAL"    => $this->get("keyword")
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
        $this->resultdata("TRANS_JURNAL",$param);
    }
    public function jurnalunit_get(){
        $param=array();
        $no_spk=$this->get("no_spk");
        $this->Main_model->set_custom_query($this->Custom_model->jurnal_unit($no_spk));
        $this->resultdata("TRANS_JURNAL",$param=array());
    }
    /**
     * [jurnalbpkb_get description]
     * @return [type] [description]
     */
    public function jurnalbpkb_get(){
        $param=array();
        $no_trans=$this->get("no_trans");
        $kd_dealer=$this->get("kd_dealer");
        $kd_trans=$this->get("kd_trans");
        $this->Main_model->set_custom_query($this->Custom_model->jurnal_bpkb($kd_dealer,$kd_trans,$no_trans));
        $this->resultdata("TRANS_JURNAL",$param=array());
    }
    /**
     * [jurnal_post description]
     * @return [type] [description]
     */
    public function jurnal_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_JURNAL"]         = $this->post("no_jurnal");
        $this->Main_model->data_sudahada($param,"TRANS_JURNAL");
        $param["KD_POSDEALER"]      = $this->post("kd_posdealer");
        $param["TYPE_JURNAL"]       = $this->post("type_jurnal");
        $param["TGL_JURNAL"]        = tglToSql($this->post("tgl_jurnal"));
        $param["DESKRIPSI_JURNAL"]  = $this->post("deskripsi_jurnal");
        $param["TOTAL_JURNAL"]      = $this->post("total_jurnal");
        $param["CLOSING_STATUS"]    = $this->post("closing_status");
        $param["SOURCE_JURNAL"]     = $this->post("source_jurnal");
        $param["KD_TRANS"]          = $this->post("kd_trans");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_JURNAL_INSERT",$param,'post',TRUE);
    }
    /**
     * [jurnal_put description]
     * @return [type] [description]
     */
    public function jurnal_put(){
        $param = array();
        // $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["KD_POSDEALER"]      = $this->put("kd_posdealer");
        $param["NO_JURNAL"]         = $this->put("no_jurnal");
        $param["TYPE_JURNAL"]       = $this->put("type_jurnal");
        $param["TGL_JURNAL"]        = tglToSql($this->put("tgl_jurnal"));
        $param["DESKRIPSI_JURNAL"]  = $this->put("deskripsi_jurnal");
        $param["TOTAL_JURNAL"]      = $this->put("total_jurnal");
        $param["KD_TRANS"]          = $this->put("kd_trans");
        $param["SOURCE_JURNAL"]     = $this->put("source_jurnal");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JURNAL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [jurnal_closing_put description]
     * @return [type] [description]
     */
    public function jurnal_closing_put(){
        $param = array();
        $param["NO_JURNAL"]         = $this->put("no_jurnal");
        $param["CLOSING_STATUS"]    = $this->put("closing_status");
        $param["CLOSING_DATE"]      = tglToSql($this->put("closing_date"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JURNAL_UPDATE_CLOSING",$param,'put',TRUE);
    }
    /**
     * [jurnal_delete description]
     * @return [type] [description]
     */
    public function jurnal_delete(){
        $param = array();
        $param["NO_JURNAL"]                = $this->delete("no_jurnal");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JURNAL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [jurnal_detail_get description]
     * @return [type] [description]
     */
    public function jurnal_detail_get($view=null){
        $param = array();$search='';
        if($this->get("no_jurnal")){
            $param["NO_JURNAL"]     = $this->get("no_jurnal");
        }
        if($this->get("urutan_jurnal")){
            $param["URUTAN_JURNAL"]     = $this->get("urutan_jurnal");
        }
        if($this->get("kd_akun")){
            $param["KD_AKUN"]     = $this->get("kd_akun");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_JURNAL"      => $this->get("keyword"),
                "KD_AKUN"    => $this->get("keyword")
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
        if($view==true){
            $this->resultdata("TRANS_JURNAL_DETAIL_VIEW",$param);
        }else{
            $this->resultdata("TRANS_JURNAL_DETAIL",$param);
        }
    }
    /**
     * [jurnal_detail_post description]
     * @return [type] [description]
     */
    public function jurnal_detail_post(){
        $param = array();
        $param["NO_JURNAL"]         = $this->post("no_jurnal");
        $param["URUTAN_JURNAL"]     = $this->post("urutan_jurnal");
        $param["KD_AKUN"]           = $this->post("kd_akun");
        $param["TYPE_AKUN"]         = $this->post("type_akun");
        $this->Main_model->data_sudahada($param,"TRANS_JURNAL_DETAIL",TRUE);
        $param["KETERANGAN_JURNAL"] = $this->post("keterangan_jurnal");
        $param["JUMLAH"]            = $this->post("jumlah");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_JURNAL_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [jurnal_detail_put description]
     * @return [type] [description]
     */
    public function jurnal_detail_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NO_JURNAL"]         = $this->put("no_jurnal");
        $param["URUTAN_JURNAL"]     = $this->put("urutan_jurnal");
        $param["KD_AKUN"]           = $this->put("kd_akun");
        $param["KETERANGAN_JURNAL"] = $this->put("keterangan_jurnal");
        $param["TYPE_AKUN"]         = $this->put("type_akun");
        $param["JUMLAH"]            = $this->put("jumlah");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JURNAL_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jurnal_detail_delete description]
     * @return [type] [description]
     */
    public function jurnal_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JURNAL_DETAIL_DELETE",$param,'delete',TRUE);
    }
    public function jurnal_detail_all_delete(){
        $param = array();
        $param["NO_JURNAL"]         = $this->delete("no_jurnal");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JURNAL_DETAIL_ALL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [ joinpromo_get description]
     * @return [type] [description]
     */
    public function  joinpromo_get($views=null){
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
        if($this->get("area_joinpromo")){
            $param["AREA_JOINPROMO"]     = $this->get("area_joinpromo");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "AREA_JOINPROMO"      => $this->get("keyword")
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
        if($views){
            $this->resultdata("TRANS_JOINPROMO_VIEWS",$param);
        }else{
            $this->resultdata("TRANS_JOINPROMO",$param);
        }
        
    }
    /**
     * [ joinpromo_post description]
     * @return [type] [description]
     */
    public function  joinpromo_post(){
        $param = array();
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]             = $this->post("kd_dealer");
        $param["NO_TRANS"]              = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_JOINPROMO");
        $param["TGL_TRANS"]             = tglToSql($this->post("tgl_trans"));
        $param["AREA_JOINPROMO"]        = $this->post("area_joinpromo");
        $param["KEGIATAN_JOINPROMO"]    = $this->post("kegiatan_joinpromo");
        $param["TGL_JOINPROMO"]         = tglToSql($this->post("tgl_joinpromo"));
        $param["TUJUAN_JOINPROMO"]      = $this->post("tujuan_joinpromo");
        $param["LOKASI_JOINPROMO"]      = $this->post("lokasi_joinpromo");
        $param["TARGET_AUDIENS"]        = $this->post("target_audiens");
        $param["TARGET_SALES"]          = $this->post("target_sales");
        $param["TARGET_DATABASE"]       = $this->post("target_database");
        $param["RINGKASAN_JOINPROMO"]   = $this->post("ringkasan_joinpromo");
        $param["STATUS_JOINPROMO"]      = $this->post("status_joinpromo");
        $param["CREATED_BY"]            = $this->post("created_by");
        $this->resultdata("SP_TRANS_JOINPROMO_INSERT",$param,'post',TRUE);
    }
    /**
     * [ joinpromo_put description]
     * @return [type] [description]
     */
    public function  joinpromo_put(){
        $param = array();
        $param["KD_MAINDEALER"]         = $this->put("kd_maindealer");
        $param["KD_DEALER"]             = $this->put("kd_dealer");
        $param["NO_TRANS"]              = $this->put("no_trans");
        $param["TGL_TRANS"]             = tglToSql($this->put("tgl_trans"));
        $param["AREA_JOINPROMO"]        = $this->put("area_joinpromo");
        $param["KEGIATAN_JOINPROMO"]    = $this->put("kegiatan_joinpromo");
        $param["TGL_JOINPROMO"]         = tglToSql($this->put("tgl_joinpromo"));
        $param["TUJUAN_JOINPROMO"]      = $this->put("tujuan_joinpromo");
        $param["LOKASI_JOINPROMO"]      = $this->put("lokasi_joinpromo");
        $param["TARGET_AUDIENS"]        = $this->put("target_audiens");
        $param["TARGET_SALES"]          = $this->put("target_sales");
        $param["TARGET_DATABASE"]       = $this->put("target_database");
        $param["RINGKASAN_JOINPROMO"]   = $this->put("ringkasan_joinpromo");
        $param["STATUS_JOINPROMO"]      = $this->put("status_joinpromo");
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_UPDATE",$param,'put',TRUE);
    }
    /**
     * [ joinpromo_delete description]
     * @return [type] [description]
     */
    public function  joinpromo_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_DELETE",$param,'delete',TRUE);
    }
    /**
     * [ joinpromo_detail_get description]
     * @return [type] [description]
     */
    public function  joinpromo_detail_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("uraian_joinpromo")){
            $param["URAIAN_JOINPROMO"]     = $this->get("uraian_joinpromo");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "URAIAN_JOINPROMO"      => $this->get("keyword")
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
        $this->resultdata("TRANS_JOINPROMO_DETAIL",$param);
    }
    /**
     * [ joinpromo_detail_post description]
     * @return [type] [description]
     */
    public function  joinpromo_detail_post(){
        $param = array();
        $param["NO_TRANS"]              = $this->post("no_trans");
        $param["URAIAN_JOINPROMO"]      = $this->post("uraian_joinpromo");
        $this->Main_model->data_sudahada($param,"TRANS_JOINPROMO_DETAIL");
        $param["VOLUME_JOINPROMO"]      = $this->post("volume_joinpromo");
        $param["SATUAN_JOINPROMO"]      = $this->post("satuan_joinpromo");
        $param["HARGA_JOINPROMO"]       = $this->post("harga_joinpromo");
        $param["JUMLAH_JOINPROMO"]      = $this->post("jumlah_joinpromo");
        $param["KETERANGAN_JOINPROMO"]  = $this->post("keterangan_joinpromo");
        $param["CREATED_BY"]            = $this->post("created_by");
        $this->resultdata("SP_TRANS_JOINPROMO_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [ joinpromo_detail_put description]
     * @return [type] [description]
     */
    public function  joinpromo_detail_put(){
        $param = array();
        
        $param["NO_TRANS"]              = $this->put("no_trans");
        $param["URAIAN_JOINPROMO"]      = $this->put("uraian_joinpromo");
        $param["VOLUME_JOINPROMO"]      = $this->put("volume_joinpromo");
        $param["SATUAN_JOINPROMO"]      = $this->put("satuan_joinpromo");
        $param["HARGA_JOINPROMO"]       = $this->put("harga_joinpromo");
        $param["JUMLAH_JOINPROMO"]      = $this->put("jumlah_joinpromo");
        $param["KETERANGAN_JOINPROMO"]  = $this->put("keterangan_joinpromo");
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [ joinpromo_detail_delete description]
     * @return [type] [description]
     */
    public function  joinpromo_detail_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [ joinpromo_sharing_get description]
     * @return [type] [description]
     */
    public function  joinpromo_sharing_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_leasing")){
            $param["KD_LEASING"]     = $this->get("kd_leasing");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "KD_LEASING"      => $this->get("keyword")
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
        $this->resultdata("TRANS_JOINPROMO_SHARING",$param);
    }
    /**
     * [ joinpromo_sharing_post description]
     * @return [type] [description]
     */
    public function  joinpromo_sharing_post(){
        $param = array();
        $param["NO_TRANS"]              = $this->post("no_trans");
        $param["KD_LEASING"]            = $this->post("kd_leasing");
        $this->Main_model->data_sudahada($param,"TRANS_JOINPROMO_SHARING");
        $param["JUMLAH_SHARING"]        = $this->post("jumlah_sharing");
        $param["CREATED_BY"]            = $this->post("created_by");
        $this->resultdata("SP_TRANS_JOINPROMO_SHARING_INSERT",$param,'post',TRUE);
    }
    /**
     * [ joinpromo_sharing_put description]
     * @return [type] [description]
     */
    public function  joinpromo_sharing_put(){
        $param = array();
        $param["NO_TRANS"]              = $this->put("no_trans");
        $param["KD_LEASING"]            = $this->put("kd_leasing");
        $param["JUMLAH_SHARING"]        = $this->put("jumlah_sharing");
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_SHARING_UPDATE",$param,'put',TRUE);
    }
    /**
     * [ joinpromo_sharing_delete description]
     * @return [type] [description]
     */
    public function  joinpromo_sharing_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_SHARING_DELETE",$param,'delete',TRUE);
    }
    /**
     * [ joinpromo_approval_get description]
     * @return [type] [description]
     */
    public function  joinpromo_approval_get(){
        $param = array();$search='';
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
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_JOINPROMO_APPROVAL",$param);
    }
    /**
     * [ joinpromo_approval_post description]
     * @return [type] [description]
     */
    public function  joinpromo_approval_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["APPROVAL_LEVEL"]    = $this->post("approval_level");
        $this->Main_model->data_sudahada($param,"TRANS_JOINPROMO_APPROVAL");
        $param["APPROVAL_BY"]       = $this->post("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->post("approval_date"));
        $param["APPROVAL_STATUS"]   = $this->post("approval_status");
        $param["APPROVAL_REMARKS"]  = $this->post("approval_remarks");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_JOINPROMO_APPROVAL_INSERT",$param,'post',TRUE);
    }
    /**
     * [ joinpromo_approval_put description]
     * @return [type] [description]
     */
    public function  joinpromo_approval_put(){
        $param = array();
        
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["APPROVAL_LEVEL"]    = $this->put("approval_level");
        $param["APPROVAL_BY"]       = $this->put("approval_by");
        $param["APPROVAL_STATUS"]   = $this->put("approval_status");
        $param["APPROVAL_REMARKS"]  = $this->put("approval_remarks");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_APPROVAL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [ joinpromo_approval_delete description]
     * @return [type] [description]
     */
    public function  joinpromo_approval_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_APPROVAL_DELETE",$param,'delete',TRUE);
    }
    /**
     * Update status join promo 5, dibayar, 6 di kembalikan
     * @return [type] [description]
     */
    public function joinpromo_status_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["STATUS_JOINPROMO"]  = $this->put("status_jp");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_JOINPROMO_STATUS_UPDATE",$param,'put',TRUE);
    }
}
?>