<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Service extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
        $this->load->helper("zetro");
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
     * [pkb_get description]
     * @return [type] [description]
     */
    public function pkb_get(){
        $param = array();$search='';
       /* if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }*/
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_pkb")){
            $param["NO_PKB"]     = $this->get("no_pkb");
        }
        if($this->get("status_pkb")){
            $param["STATUS_PKB"]     = $this->get("status_pkb");
        }
        if($this->get("no_polisi")){
            $param["NO_POLISI"]     = $this->get("no_polisi");
        }
        if($this->get("final_confirmation")){
            $param["FINAL_CONFIRMATION"]     = $this->get("final_confirmation");
        }
        if($this->get("keyword")){
            //$param=array();
            $search= array(
                "NO_PKB" => $this->get("keyword"),
                "NO_POLISI" => $this->get("keyword")

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
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PKB",$param);
    }
    /**
     * [pkb_post description]
     * @return [type] [description]
     */
    public function pkb_post(){
        $param = array();

        $param["NO_PKB"]            = $this->post('no_pkb');
        $param["NO_POLISI"]         = $this->post('no_polisi');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_SA"]               = $this->post('kd_sa');
        $this->Main_model->data_sudahada($param,"TRANS_PKB",TRUE);
        $param["NO_MESIN"]          = $this->post('no_mesin');
        $param["NO_RANGKA"]         = $this->post('no_rangka');
        $param["KM_MOTOR"]          = $this->post('km_motor');
        $param["NAMA_TYPEMOTOR"]    = $this->post('nama_typemotor');
        $param["TAHUN"]             = $this->post('tahun');
        $param["NAMA_MEKANIK"]      = $this->post('nama_mekanik');
        $param["NO_ANTRIAN"]        = $this->post('no_antrian');
        $param["JENIS_KPB"]         = $this->post('jenis_kpb');
        $param["JENIS_PIT"]         = $this->post('jenis_pit');
        $param["ESTIMASI_MULAI"]    = $this->post('estimasi_mulai');
        $param["ESTIMASI_SELESAI"]  = $this->post('estimasi_selesai');
        $param["SARAN_MEKANIK"]     = $this->post('saran_mekanik');
        $param["PEMBELIAN_MOTOR"]   = $this->post('pembelian_motor');
        $param["ALASAN_KE_AHASS"]   = $this->post('alasan_ke_ahass');
        $param["HUBUNGAN_DENGAN_PEMBAWA"]   = $this->post('hubungan_dengan_pembawa');
        $param["SERVICE_SEBELUMNYA"]= tglToSql($this->post('service_sebelumnya'));
        $param["BBM"]               = $this->post('bbm');
        $param["STATUS_PKB"]         = $this->post('status_pkb');
        $param["STATUS_APPROVAL"]         = $this->post('status_approval');
        $param["KETERANGAN"]               = $this->post('keterangan');
        $param["FINAL_CONFIRMATION"]         = $this->post('final_confirmation');
        $param["TANGGAL_PKB"]       = tglToSql($this->post('tanggal_pkb'));
        $param["KD_ITEM"]           = $this->post('kd_item');
        $param["KD_LOKASI"]         = $this->post('kd_lokasi');
        $param["TGL_BELI"]          = tglToSql($this->post('tgl_beli'));
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_PKB_INSERT",$param,'post',TRUE);
    }
    /**
     * [pkb_put description]
     * @return [type] [description]
     */
    public function pkb_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NO_PKB"]            = $this->put('no_pkb');
        $param["NO_POLISI"]         = $this->put('no_polisi');
        $param["KM_MOTOR"]          = $this->put('km_motor');
        $param["NO_RANGKA"]         = $this->put('no_rangka');
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["NAMA_TYPEMOTOR"]    = $this->put('nama_typemotor');
        $param["TAHUN"]             = $this->put('tahun');
        $param["NAMA_MEKANIK"]      = $this->put('nama_mekanik');
        $param["NO_ANTRIAN"]        = $this->put('no_antrian');
        $param["JENIS_KPB"]         = $this->put('jenis_kpb');
        $param["JENIS_PIT"]         = $this->put('jenis_pit');
        $param["ESTIMASI_MULAI"]    = $this->put('estimasi_mulai');
        $param["ESTIMASI_SELESAI"]  = $this->put('estimasi_selesai');
        $param["SARAN_MEKANIK"]     = $this->put('saran_mekanik');
        $param["PEMBELIAN_MOTOR"]   = $this->put('pembelian_motor');
        $param["ALASAN_KE_AHASS"]   = $this->put('alasan_ke_ahass');
        $param["HUBUNGAN_DENGAN_PEMBAWA"]   = $this->put('hubungan_dengan_pembawa');
        $param["SERVICE_SEBELUMNYA"]= tglToSql($this->put('service_sebelumnya'));
        $param["BBM"]               = $this->put('bbm');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_SA"]               = $this->put('kd_sa');
        $param["STATUS_PKB"]         = $this->put('status_pkb');
        $param["STATUS_APPROVAL"]         = $this->put('status_approval');
        $param["KETERANGAN"]               = $this->put('keterangan');
        $param["FINAL_CONFIRMATION"]         = $this->put('final_confirmation');
        $param["TANGGAL_PKB"]         = tglToSql($this->put('tanggal_pkb'));
        $param["KD_ITEM"]           = $this->put('kd_item');
        $param["KD_LOKASI"]         = $this->put('kd_lokasi');
        $param["TGL_BELI"]          = tglToSql($this->put('tgl_beli'));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pkb_delete description]
     * @return [type] [description]
     */
    public function pkb_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PKB_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pkb_detail_get description]
     * @return [type] [description]
     */
    public function pkb_detail_get($view=null){
        $param = array();$search='';
        if($this->get("no_pkb")){
            $param["NO_PKB"]     = $this->get("no_pkb");
        }
        if($this->get("jenis_item")){
            $param["JENIS_ITEM"]     = $this->get("jenis_item");
        }
        if($this->get("kd_pekerjaan")){
            $param["KD_PEKERJAAN"]     = $this->get("kd_pekerjaan");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_PEKERJAAN" => $this->get("keyword")

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
            $this->resultdata("TRANS_PKB_DETAIL_V",$param);
        }else{
            $this->resultdata("TRANS_PKB_DETAIL",$param);
        }
    }
    /**
     * [pkb_detail_post description]
     * @return [type] [description]
     */
    public function pkb_detail_post(){
        $param = array();
        $param["NO_PKB"]            = $this->post('no_pkb');
        $param["KD_PEKERJAAN"]      = $this->post('kd_pekerjaan');
        $param["KATEGORI"]          = $this->post('kategori');
        $param["JENIS_PKB"]         = $this->post('jenis_pkb');
        $this->Main_model->data_sudahada($param,"TRANS_PKB_DETAIL",TRUE);
        $param["QTY"]               = $this->post('qty');
        $param["HARGA_SATUAN"]      = $this->post('harga_satuan');
        $param["TOTAL_HARGA"]       = $this->post('total_harga');
        $param["JENIS_ITEM"]        = $this->post('jenis_item');
        $param["DISKON"]            = $this->post('diskon');
        $param["APPROVAL_ITEM"]     = $this->post('approval_item');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_PKB_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [pkb_detail_put description]
     * @return [type] [description]
     */
    public function pkb_detail_put(){
        $param = array();
        $param["KD_PEKERJAAN"]      = $this->put('kd_pekerjaan');
        $param["QTY"]               = $this->put('qty');
        $param["HARGA_SATUAN"]      = $this->put('harga_satuan');
        $param["TOTAL_HARGA"]       = $this->put('total_harga');
        $param["NO_PKB"]            = $this->put('no_pkb');
        $param["KATEGORI"]          = $this->put('kategori');
        $param["JENIS_ITEM"]        = $this->put('jenis_item');
        $param["DISKON"]            = $this->put('diskon');
        $param["JENIS_PKB"]         = $this->put('jenis_pkb');
        $param["APPROVAL_ITEM"]     = $this->put('approval_item');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_DETAIL_UPDATE",$param,'put',TRUE);
        
    }
    /**
     * [pkb_detail_delete description]
     * @return [type] [description]
     */
    public function pkb_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PKB_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pkb_status_put description]
     * @return [type] [description]
     */
    public function pkb_pending_put(){
        $param = array();
        
        $param["ID"]         = $this->put('id');
        $param["ALASAN_PENDING"]         = $this->put('alasan_pending');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_PENDING_UPDATE",$param,'put',TRUE);
    }

    /**
     * [pkb_status_put description]
     * @return [type] [description]
     */
    public function pkb_status_put(){
        $param = array();
        
        $param["NO_PKB"]         = $this->put('no_pkb');
        $param["STATUS_PKB"]         = $this->put('status_pkb');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_STATUS_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pkb_paybill_put description]
     * @return [type] [description]
     */
    public function pkb_paybill_put(){
        $param = array();
        
        $param["NO_PKB"]         = $this->put('no_pkb');
        $param["STATUS_PKB"]     = $this->put('status_pkb');
        $param["PICKING_STATUS"] = $this->put('picking_status');
        $param["BILL_REFF"]   = $this->put('bill_reff');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_PAYBILL",$param,'put',TRUE);
    }
    /**
     * [pkb_picking_detail_put description]
     * @return [type] [description]
     */
    public function pkb_picking_detail_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["PICKING_STATUS"]         = $this->put('picking_status');
        $param["PICKING_REFF"]         = $this->put('picking_reff');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_PICKING_DETAIL_UPDATE",$param,'put',TRUE);
    }


    public function pkb_approval_detail_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["APPROVAL_ITEM"]     = $this->put('approval_item');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_APPROVAL_DETAIL_UPDATE",$param,'put',TRUE);
    }



    /**
     * [kpb_claim_get description]
     * @return [type] [description]
     */
    public function kpb_claim_get(){
        $param = array();$search='';
        if($this->get("no_claim")){
            $param["NO_CLAIM"]     = $this->get("no_claim");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_CLAIM" => $this->get("keyword"),
                "KD_MAINDEALER" => $this->get("keyword"),
                "KD_DEALER" => $this->get("keyword")

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
        $this->resultdata("TRANS_KPB_CLAIM",$param);
    }
    /**
     * [kpb_claim_post description]
     * @return [type] [description]
     */
    public function kpb_claim_post(){
        $param = array();

        $param["NO_CLAIM"]   = $this->post('no_claim');
        $this->Main_model->data_sudahada($param,"TRANS_KPB_CLAIM",TRUE);
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $param["KD_DEALER"]      = $this->post('kd_dealer');
        $param["NO_PKB"]      = $this->post('no_pkb');
        $param["STATUS_FILE"]  = $this->post('status_file');
        $param["STATUS_SURAT_PENGANTAR"]  = $this->post('status_surat_pengantar');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_KPB_CLAIM_INSERT",$param,'post',TRUE);
    }
    /**
     * [kpb_claim_put description]
     * @return [type] [description]
     */
    public function kpb_claim_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NO_CLAIM"]          = $this->put('no_claim');
        $param["KD_MAINDEALER"]  = $this->put('kd_maindealer');
        $param["KD_DEALER"]    = $this->put('kd_dealer');
        $param["NO_PKB"]    = $this->put('no_pkb');
        $param["STATUS_FILE"]    = $this->put('status_file');
        $param["STATUS_SURAT_PENGANTAR"]    = $this->put('status_surat_pengantar');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KPB_CLAIM_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kpb_claim_delete description]
     * @return [type] [description]
     */
    public function kpb_claim_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_KPB_CLAIM_DELETE",$param,'delete',TRUE);
    }

    /**
     * [kpb_claim_file_put description]
     * @return [type] [description]
     */
    public function kpb_claim_file_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STATUS_FILE"]    = $this->put('status_file');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KPB_CLAIM_FILE_UPDATE",$param,'put',TRUE);
    }

    /**
     * [kpb_claim_surat_put description]
     * @return [type] [description]
     */
    public function kpb_claim_surat_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STATUS_SURAT_PENGANTAR"]    = $this->put('status_surat_pengantar');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KPB_CLAIM_SURAT_UPDATE",$param,'put',TRUE);
    }

    /**
     * [kpb_validasi_get description]
     * @return [type] [description]
     */
    public function kpb_validasi_get(){
        $param = array();$search='';
        if($this->get("no_pkb")){
            $param["NO_PKB"]     = $this->get("no_pkb");
        }
        if($this->get("no_kpb")){
            $param["NO_KPB"]     = $this->get("no_kpb");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            //$param=array();
            $search= array(
                "NO_PKB" => $this->get("keyword"),
                "NO_KPB" => $this->get("keyword"),
                "NO_MESIN" => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword")

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
        $this->resultdata("TRANS_KPB_VALIDASI",$param);
    }
    /**
     * [kpb_validasi_post description]
     * @return [type] [description]
     */
    public function kpb_validasi_post(){
        $param = array();
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $param["KD_DEALER"]      = $this->post('kd_dealer');
        $param["NO_PKB"]      = $this->post('no_pkb');
        $param["NO_KPB"]  = $this->post('no_kpb');
        $this->Main_model->data_sudahada($param,"TRANS_KPB_VALIDASI",TRUE);
        $param["KD_MESIN"]  = $this->post('kd_mesin');
        $param["NO_MESIN"]  = $this->post('no_mesin');
        $param["NO_RANGKA"]  = $this->post('no_rangka');
        $param["TGL_BELI"]  = tglToSql($this->post('tgl_beli'));
        $param["SEQUENCE"]  = $this->post('sequence');
        $param["KM_SERVICE"]  = $this->post('km_service');
        $param["TGL_SERVICE"]  = tglToSql($this->post('tgl_service'));
        $param["MOTOR_LUAR"]  = $this->post('motor_luar');
        $param["BUKU_BARU"]  = $this->post('buku_baru');
        $param["STATUS_KPB"]  = $this->post('status_kpb');
        $param["KD_DEALERAHM"]  = $this->post('kd_dealerahm');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_KPB_VALIDASI_INSERT",$param,'post',TRUE);
    }
    /**
     * [kpb_validasi_put description]
     * @return [type] [description]
     */
    public function kpb_validasi_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_MAINDEALER"]  = $this->put('kd_maindealer');
        $param["KD_DEALER"]    = $this->put('kd_dealer');
        $param["NO_PKB"]    = $this->put('no_pkb');
        $param["KD_MESIN"]  = $this->put('kd_mesin');
        $param["NO_MESIN"]    = $this->put('no_mesin');
        $param["NO_RANGKA"]  = $this->put('no_rangka');
        $param["NO_KPB"]  = $this->put('no_kpb');
        $param["TGL_BELI"]  = tglToSql($this->put('tgl_beli'));
        $param["SEQUENCE"]  = $this->put('sequence');
        $param["KM_SERVICE"]  = $this->put('km_service');
        $param["TGL_SERVICE"]  = tglToSql($this->put('tgl_service'));
        $param["MOTOR_LUAR"]  = $this->put('motor_luar');
        $param["BUKU_BARU"]  = $this->put('buku_baru');
        $param["STATUS_KPB"]  = $this->put('status_kpb');
        $param["KD_DEALERAHM"]  = $this->put('kd_dealerahm');
        $param["REVISI"]  = $this->put('revisi');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KPB_VALIDASI_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kpb_validasi_delete description]
     * @return [type] [description]
     */
    public function kpb_validasi_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_KPB_VALIDASI_DELETE",$param,'delete',TRUE);
    }

    /**
     * [kpb_validasi_status_put description]
     * @return [type] [description]
     */
    public function kpb_validasi_status_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STATUS_KPB"]  = $this->put('status_kpb');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KPB_VALIDASI_STATUS_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [kpb_surat_pengantar_get description]
     * @return [type] [description]
     */
    public function kpb_surat_pengantar_get(){
       $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->input->get("no_kpb")){
            $param["NO_KPB"] = $this->input->get("no_kpb");
        }
       
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_KPB"  => $this->get("keyword"),
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SURAT_PENGANTAR_KPB_VIEWS",$param);

    }

    /**
     * [claim_promo_get description]
     * @return [type] [description]
     */
    public function claim_promo_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("jenis")){
            $param["JENIS"]     = $this->get("jenis");
        }
        if($this->get("kd_fincoy")){
            $param["KD_FINCOY"]     = $this->get("kd_fincoy");
        }
        if($this->get("no_claim")){
            $param["NO_CLAIM"]     = $this->get("no_claim");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_CLAIM" => $this->get("keyword"),
                "KD_MAINDEALER" => $this->get("keyword"),
                "KD_DEALER" => $this->get("keyword")

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
        $this->resultdata("TRANS_CLAIM_PROMO",$param);
    }
    /**
     * [claim_promo_post description]
     * @return [type] [description]
     */
    public function claim_promo_post(){
        $param = array();
        $param["NO_CLAIM"]          = $this->post('no_claim');
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_DEALERAHM"]      = $this->post('kd_dealerahm');
        $param["NO_MESIN"]          = $this->post('no_mesin');
        $this->Main_model->data_sudahada($param,"TRANS_CLAIM_PROMO",TRUE);
        $param["SPK_ID"]            = $this->post('spk_id');
        $param["KD_SALESPROGRAM"]   = $this->post('kd_salesprogram');
        $param["CLAIM_STATUS"]      = $this->post('claim_status');
        $param["NAMA_DEALER"]       = $this->post('nama_dealer');
        $param["NO_RANGKA"]         = $this->post('no_rangka');
        $param["KD_TIPE"]           = $this->post('kd_tipe');
        $param["NAMA_TIPE"]         = $this->post('nama_tipe');
        $param["KD_WARNA"]          = $this->post('kd_warna');
        $param["DESKRIPSI_WARNA"]   = $this->post('deskripsi_warna');
        $param["NAMA_SALESPROGRAM"] = $this->post('nama_salesprogram');
        $param["NO_FAKTUR_JUAL"]    = $this->post('no_faktur_jual');
        $param["TGL_FAKTUR_JUAL"]   = tglToSql($this->post('tgl_faktur_jual'));
        $param["TGL_BAST"]          = tglToSql($this->post('tgl_bast'));
        $param["TIPE_PENJUALAN"]    = $this->post('tipe_penjualan');
        $param["KD_FINCOY"]         = $this->post('kd_fincoy');
        $param["NAMA_FINCOY"]       = $this->post('nama_fincoy');
        $param["NO_PO_FINCOY"]      = $this->post('no_po_fincoy');
        // $param["TGL_PO_FINCOY"]     = tglToSql($this->post('tgl_po_fincoy'));
        $param["TGL_FAKTUR_STNK"]   = tglToSql($this->post('tgl_faktur_stnk'));
        $param["ALAMAT"]            = $this->post('alamat');
        $param["KD_KOTA"]           = $this->post('kd_kota');
        $param["NAMA_CUSTOMER"]     = $this->post('nama_customer');
        $param["NAMA_KOTA"]         = $this->post('nama_kota');
        $param["TGL_CLAIM"]         = tglToSql($this->post('tgl_claim'));
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_CLAIM_PROMO_INSERT",$param,'post',TRUE);
    }
    /**
     * [claim_promo_put description]
     * @return [type] [description]
     */
    public function claim_promo_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NO_CLAIM"]          = $this->put('no_claim');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_DEALERAHM"]      = $this->put('kd_dealerahm');
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["SPK_ID"]            = $this->put('spk_id');
        $param["KD_SALESPROGRAM"]   = $this->put('kd_salesprogram'); 
        $param["CLAIM_STATUS"]      = $this->put('claim_status');
        $param["NAMA_DEALER"]       = $this->put('nama_dealer');
        $param["NO_RANGKA"]         = $this->put('no_rangka'); 
        $param["KD_TIPE"]           = $this->put('kd_tipe');
        $param["NAMA_TIPE"]         = $this->put('nama_tipe'); 
        $param["KD_WARNA"]          = $this->put('kd_warna');
        $param["DESKRIPSI_WARNA"]   = $this->put('deskripsi_warna');
        $param["NAMA_SALESPROGRAM"] = $this->put('nama_salesprogram'); 
        $param["NO_FAKTUR_JUAL"]    = $this->put('no_faktur_jual'); 
        $param["TGL_FAKTUR_JUAL"]   = tglToSql($this->put('tgl_faktur_jual'));
        $param["TGL_BAST"]          = tglToSql($this->put('tgl_bast'));
        $param["TIPE_PENJUALAN"]    = $this->put('tipe_penjualan'); 
        $param["KD_FINCOY"]         = $this->put('kd_fincoy');
        $param["NAMA_FINCOY"]       = $this->put('nama_fincoy'); 
        $param["NO_PO_FINCOY"]      = $this->put('no_po_fincoy'); 
        $param["TGL_PO_FINCOY"]     = tglToSql($this->put('tgl_po_fincoy'));
        $param["TGL_FAKTUR_STNK"]   = tglToSql($this->put('tgl_faktur_stnk'));
        $param["ALAMAT"]            = $this->put('alamat'); 
        $param["KD_KOTA"]           = $this->put('kd_kota');
        $param["NAMA_CUSTOMER"]     = $this->put('nama_customer'); 
        $param["NAMA_KOTA"]         = $this->put('nama_kota');
        $param["TGL_CLAIM"]         = tglToSql($this->put('tgl_claim'));              
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_CLAIM_PROMO_UPDATE",$param,'put',TRUE);
    }
    /**
     * [claim_promo_delete description]
     * @return [type] [description]
     */
    public function claim_promo_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_CLAIM_PROMO_DELETE",$param,'delete',TRUE);
    }


    /**
     * [kpb_claim_listharga_get description]
     * @return [type] [description]
     */
    public function kpb_claim_listharga_get(){
        $param = array();$search='';
         if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("motor_kpb")){
            $param["MOTOR_KPB"]     = $this->get("motor_kpb");
        }
        if($this->get("inisial")){
            $param["INISIAL"]     = $this->get("inisial");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_MESIN" => $this->get("keyword"),
                "MOTOR_KPB" => $this->get("keyword")

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
        $this->resultdata("TRANS_KPB_CLAIM_LISTHARGA",$param);
    }
    /**
     * [kpb_claim_listharga_post description]
     * @return [type] [description]
     */
    public function kpb_claim_listharga_post(){
        $param = array();
        $param["NO_MESIN"]   = $this->post('no_mesin');
        $param["MOTOR_KPB"]    = $this->post('motor_kpb');
        $param["INISIAL"]      = $this->post('inisial');
        $param["KD_KPB"]      = $this->post('kd_kpb');
        $this->Main_model->data_sudahada($param,"TRANS_KPB_CLAIM_LISTHARGA",TRUE);
        $param["SERVICE"]  = $this->post('service');
        $param["NOMINAL_JASA"]  = $this->post('nominal_jasa');
        $param["ISI_OLI"]  = $this->post('isi_oli');
        $param["HARGA_OLI"]  = $this->post('harga_oli');
        $param["NO_PART_OLI"]  = $this->post('no_part_oli');
        $param["NO_PART_OLI2"]  = $this->post('no_part_oli2');
        $param["ISI_OLI_2"]  = $this->post('isi_oli_2');
        $param["HARGA_OLI_2"]  = $this->post('harga_oli_2');
        $param["NO_PART_OLI_1"]  = $this->post('no_part_oli_1');
        $param["NO_PART_OLI_2"]  = $this->post('no_part_oli_2');
        $param["KD_MAINDEALER"]  = $this->post('kd_maindealer');
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_KPB_CLAIM_LISTHARGA_INSERT",$param,'post',TRUE);
    }
    /**
     * [kpb_claim_listharga_put description]
     * @return [type] [description]
     */
    public function kpb_claim_listharga_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["MOTOR_KPB"]  = $this->put('motor_kpb');
        $param["INISIAL"]    = $this->put('inisial');
        $param["KD_KPB"]    = $this->put('kd_kpb');
        $param["SERVICE"]    = $this->put('service');
        $param["NOMINAL_JASA"]    = $this->put('nominal_jasa');
        $param["ISI_OLI"]  = $this->put('isi_oli');
        $param["HARGA_OLI"]  = $this->put('harga_oli');
        $param["NO_PART_OLI"]  = $this->put('no_part_oli');
        $param["NO_PART_OLI2"]  = $this->put('no_part_oli2');
        $param["ISI_OLI_2"]  = $this->put('isi_oli_2');
        $param["HARGA_OLI_2"]  = $this->put('harga_oli_2');
        $param["NO_PART_OLI_1"]  = $this->put('no_part_oli_1');
        $param["NO_PART_OLI_2"]  = $this->put('no_part_oli_2');
        $param["KD_MAINDEALER"]  = $this->put('kd_maindealer');
        $param["KD_DEALER"]  = $this->put('kd_dealer');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KPB_CLAIM_LISTHARGA_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kpb_claim_listharga_delete description]
     * @return [type] [description]
     */
    public function kpb_claim_listharga_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_KPB_CLAIM_LISTHARGA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [historyservice_new_get description]
     * @return [type] [description]
     */
    public function historyservice_new_get(){
        $param = array();$search='';
        if($this->get("kd_reminder")){
            $param["KD_REMINDER"]     = $this->get("kd_reminder");
        }
        if($this->get("kd_booking")){
            $param["KD_BOOKING"]     = $this->get("kd_booking");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_REMINDER" => $this->get("keyword"),
                "KD_BOOKING" => $this->get("keyword"),
                "KD_CUSTOMER" => $this->get("keyword")

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
        $this->resultdata("TRANS_HISTORYSERVICE_NEW",$param);
    }
    /**
     * [historyservice_new_post description]
     * @return [type] [description]
     */
    public function historyservice_new_post(){
        $param = array();
        $param["NO_TRANS"]   = $this->post('no_trans');
        $param["TGL_TRANS"]      = tglToSql($this->post('tgl_trans'));
        $param["KD_REMINDER"]   = $this->post('kd_reminder');
        $param["KD_BOOKING"]      = $this->post('kd_booking');
        $param["KD_CUSTOMER"]  = $this->post('kd_customer');
        $param["NO_MESIN"]  = $this->post('no_mesin');
        $param["KD_MAINDEALER"]  = $this->post('kd_maindealer');
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"TRANS_HISTORYSERVICE_NEW",TRUE);
        $param["WAKTU_JDWREMINDER"]    = tglToSql($this->post('waktu_jdwreminder'));
        $param["WAKTU_REMINDERKPB"]      = tglToSql($this->post('waktu_reminderkpb'));
        $param["WAKTU_BOOKING"]  = tglToSql($this->post('waktu_booking'));
        $param["WAKTU_ESTSERVICE"]  = tglToSql($this->post('waktu_estservice'));
        $param["NO_POLISI"]  = $this->post('no_polisi');
        $param["NO_RANGKA"]  = $this->post('no_rangka');
        $param["KD_PEMBAWAMOTOR"]  = $this->post('kd_pembawamotor');
        $param["KD_PEMAKAIMOTOR"]  = $this->post('kd_pemakaimotor');
        $param["KD_FORMSA"]  = $this->post('kd_formsa');
        $param["WAKTU_FORMSA"]  = tglToSql($this->post('waktu_formsa'));
        $param["KD_PKB"]  = $this->post('kd_pkb');
        $param["WAKTU_PKB"]  = tglToSql($this->post('waktu_pkb'));
        $param["WAKTU_FINALCHECK"]  = tglToSql($this->post('waktu_finalcheck'));
        $param["KD_NJB"]  = $this->post('kd_njb');
        $param["WAKTU_NJB"]  = tglToSql($this->post('waktu_njb'));
        $param["KD_NSC"]  = $this->post('kd_nsc');
        $param["WAKTU_NSC"]  = tglToSql($this->post('waktu_nsc'));
        $param["KD_FOLLUP"]  = $this->post('kd_follup');
        $param["WAKTU_REMINDER_FOLLUP"]  = tglToSql($this->post('waktu_reminder_follup'));
        $param["STATUS_CUSTOMER"]  = $this->post('status_customer');
        $param["KD_STATUSSERVICE"]  = $this->post('kd_statusservice');
        $param["STATUS_PKB"]  = $this->post('status_pkb');
        $param["STATUS_FOLLUP_TERAKHIR"]  = $this->post('status_follup_terakhir');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_HISTORYSERVICE_NEW_INSERT",$param,'post',TRUE);
    }
    /**
     * [historyservice_new_put description]
     * @return [type] [description]
     */
    public function historyservice_new_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NO_TRANS"]   = $this->put('no_trans');
        $param["TGL_TRANS"]      = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]  = $this->put('kd_maindealer');
        $param["KD_DEALER"]  = $this->put('kd_dealer');
        $param["KD_REMINDER"]   = $this->put('kd_reminder');
        $param["WAKTU_JDWREMINDER"]    = tglToSql($this->put('waktu_jdwreminder'));
        $param["WAKTU_REMINDERKPB"]      = tglToSql($this->put('waktu_reminderkpb'));
        $param["KD_BOOKING"]      = $this->put('kd_booking');
        $param["WAKTU_BOOKING"]  = tglToSql($this->put('waktu_booking'));
        $param["WAKTU_ESTSERVICE"]  = tglToSql($this->put('waktu_estservice'));
        $param["KD_CUSTOMER"]  = $this->put('kd_customer');
        $param["NO_POLISI"]  = $this->put('no_polisi');
        $param["NO_MESIN"]  = $this->put('no_mesin');
        $param["NO_RANGKA"]  = $this->put('no_rangka');
        $param["KD_PEMBAWAMOTOR"]  = $this->put('kd_pembawamotor');
        $param["KD_PEMAKAIMOTOR"]  = $this->put('kd_pemakaimotor');
        $param["KD_FORMSA"]  = $this->put('kd_formsa');
        $param["WAKTU_FORMSA"]  = tglToSql($this->put('waktu_formsa'));
        $param["KD_PKB"]  = $this->put('kd_pkb');
        $param["WAKTU_PKB"]  = tglToSql($this->put('waktu_pkb'));
        $param["WAKTU_FINALCHECK"]  = tglToSql($this->put('waktu_finalcheck'));
        $param["KD_NJB"]  = $this->put('kd_njb');
        $param["WAKTU_NJB"]  = tglToSql($this->put('waktu_njb'));
        $param["KD_NSC"]  = $this->put('kd_nsc');
        $param["WAKTU_NSC"]  = tglToSql($this->put('waktu_nsc'));
        $param["KD_FOLLUP"]  = $this->put('kd_follup');
        $param["WAKTU_REMINDER_FOLLUP"]  = tglToSql($this->put('waktu_reminder_follup'));
        $param["STATUS_CUSTOMER"]  = $this->put('status_customer');
        $param["KD_STATUSSERVICE"]  = $this->put('kd_statusservice');
        $param["STATUS_PKB"]  = $this->put('status_pkb');
        $param["STATUS_FOLLUP_TERAKHIR"]  = $this->put('status_follup_terakhir');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_HISTORYSERVICE_NEW_UPDATE",$param,'put',TRUE);
    }

    /**
     * [historyservice_new_delete description]
     * @return [type] [description]
     */
    public function historyservice_new_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_HISTORYSERVICE_NEW_DELETE",$param,'delete',TRUE);
    }

     /**
     * [pkb_finalconfirmation_put description]
     * @return [type] [description]
     */
    public function pkb_finalconfirmation_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["SARAN_MEKANIK"]     = $this->put('saran_mekanik');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_FINALCONFIRMATION_UPDATE",$param,'put',TRUE);
    }

    /**
     * [cust_reminder_get description]
     * @return [type] [description]
     */
    public function cust_reminder_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_CUSTOMER" => $this->get("keyword"),
                "NO_TRANS" => $this->get("keyword"),
                "KD_CUSTOMER" => $this->get("keyword")

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
        $this->resultdata("TRANS_CUST_REMINDER",$param);
    }
    /**
     * [cust_reminder_post description]
     * @return [type] [description]
     */
    public function cust_reminder_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->post('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_CUSTOMER"]       = $this->post('kd_customer');
        $param["NAMA_CUSTOMER"]     = $this->post('nama_customer');
        $this->Main_model->data_sudahada($param,"TRANS_CUST_REMINDER",TRUE);
        $param["WAKTU_JDWREMINDER"] = tglToSql($this->post('waktu_jdwreminder'));
        $param["WAKTU_REMINDERKPB"] = tglToSql($this->post('waktu_reminderkpb'));
        $param["JENIS_KPB"]         = $this->post('jenis_kpb');
        $param["JENIS_REMINDER"]    = $this->post('jenis_reminder');
        $param["STATUS_REMINDER"]   = $this->post('status_reminder');
        $param["NO_HP"]             = $this->post('no_hp');
        $param["KD_TYPEMOTOR"]      = $this->post('kd_typemotor');
        $param["NO_POLISI"]        = $this->post('no_polisi');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_CUST_REMINDER_INSERT",$param,'post',TRUE);
    }
    /**
     * [cust_reminder_put description]
     * @return [type] [description]
     */
    public function cust_reminder_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["NAMA_CUSTOMER"]     = $this->put('nama_customer');
        $param["WAKTU_JDWREMINDER"] = tglToSql($this->put('waktu_jdwreminder'));
        $param["WAKTU_REMINDERKPB"] = tglToSql($this->put('waktu_reminderkpb'));
        $param["JENIS_KPB"]         = $this->put('jenis_kpb');
        $param["JENIS_REMINDER"]    = $this->put('jenis_reminder');
        $param["STATUS_REMINDER"]   = $this->put('status_reminder');
        $param["NO_HP"]             = $this->put('no_hp');
        $param["KD_TYPEMOTOR"]      = $this->put('kd_typemotor');
        $param["NO_POLISI"]        = $this->put('no_polisi');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_CUST_REMINDER_UPDATE",$param,'put',TRUE);
    }

    /**
     * [cust_reminder_delete description]
     * @return [type] [description]
     */
    public function cust_reminder_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_CUST_REMINDER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [cust_booking_get description]
     * @return [type] [description]
     */
    public function cust_booking_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("no_polisi")){
            $param["NO_POLISI"]     = $this->get("no_polisi");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_POLISI" => $this->get("keyword"),
                "NO_TRANS" => $this->get("keyword"),
                "KD_CUSTOMER" => $this->get("keyword")

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
        $this->resultdata("TRANS_CUST_BOOKING",$param);
    }
    /**
     * [cust_booking_post description]
     * @return [type] [description]
     */
    public function cust_booking_post(){
        $param = array();
        $param["NO_TRANS"]      = $this->post('no_trans');
        $param["TGL_TRANS"]     = $this->post('tgl_trans');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"TRANS_CUST_BOOKING",TRUE);
        $param["NAMA_CUSTOMER"] = $this->post('nama_customer');
        $param["NO_POLISI"]     = $this->post('no_polisi');
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $param["NO_TELEPON"]    = $this->post('no_telepon');
        $param["WAKTU_SERVIS"]  = $this->post('waktu_servis');
        $param["TIPE_MOTOR"]    = $this->post('tipe_motor');
        $param["KELUHAN_CUST"]  = $this->post('keluhan_cust');
        $param["KD_TIPEPKB"]    = $this->post('kd_tipepkb');
        $param["TIPE_MANUAL"]   = $this->post('tipe_manual');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["ALASAN"]        = $this->post('alasan');
        $param["NO_RANGKA"]     = $this->post('no_rangka');
        $param["NO_MESIN"]      = $this->post('no_mesin');
        $param["NAMA_PEMILIK"]  = $this->post('nama_pemilik');
        $param["ALAMAT"]        = $this->post('alamat');
        $param["TAHUN_KENDARAAN"] = $this->post('tahun_kendaraan');
        $param["EMAIL"]         = $this->post('email');
        $param["CREATED_BY"]    = $this->post('created_by');

        // var_dump($param);exit();
        $this->resultdata("SP_TRANS_CUST_BOOKING_INSERT",$param,'post',TRUE);
    }
    /**
     * [cust_booking_put description]
     * @return [type] [description]
     */
    public function cust_booking_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_TRANS"]         = $this->put('tgl_trans');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["WAKTU_SERVIS"]      = $this->put('waktu_servis');
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["NAMA_CUSTOMER"]     = $this->put('nama_customer');
        $param["NO_TELEPON"]        = $this->put('no_telepon');
        $param["NO_POLISI"]         = $this->put('no_polisi');
        $param["TIPE_MOTOR"]        = $this->put('tipe_motor');
        $param["KELUHAN_CUST"]      = $this->put('keluhan_cust');
        $param["KD_TIPEPKB"]        = $this->put('kd_tipepkb');
        $param["TIPE_MANUAL"]       = $this->put('tipe_manual');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["ALASAN"]            = $this->put('alasan');
        $param["NO_RANGKA"]     = $this->put('no_rangka');
        $param["NO_MESIN"]      = $this->put('no_mesin');
        $param["NAMA_PEMILIK"]  = $this->put('nama_pemilik');
        $param["ALAMAT"]        = $this->put('alamat');
        $param["TAHUN_KENDARAAN"]    = $this->put('tahun_kendaraan');
        $param["EMAIL"]         = $this->put('email');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_CUST_BOOKING_UPDATE",$param,'put',TRUE);
    }

    /**
     * [cust_booking_delete description]
     * @return [type] [description]
     */
    public function cust_booking_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_CUST_BOOKING_DELETE",$param,'delete',TRUE);
    }

    public function cust_booking_cancel_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["STATUS_BOOKING"]    = $this->put('status_booking');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_CUST_BOOKING_CANCEL",$param,'put',TRUE);
    }


    /**
     * [training_record_get description]
     * @return [type] [description]
     */
    public function training_record_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("nama_training")){
            $param["NAMA_TRAINING"]     = $this->get("nama_training");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NIK" => $this->get("keyword"),
                "NAMA_TRAINING" => $this->get("keyword")

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
        $this->resultdata("TRANS_TRAINING_RECORD",$param);
    }
    /**
     * [training_record_post description]
     * @return [type] [description]
     */
    public function training_record_post(){
        $param = array();
        $param["TGL_TRAINING"]      = tglToSql($this->post('tgl_training'));
        $param["NIK"]               = $this->post('nik');
        $param["STATUS_TRAINING"]   = $this->post('status_training');
        $param["NAMA_TRAINING"]     = $this->post('nama_training');
        $this->Main_model->data_sudahada($param,"TRANS_TRAINING_RECORD",TRUE);
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["DURASI"]            = $this->post('durasi');
        $param["LOKASI"]            = $this->post('lokasi');
        $param["PEMBICARA"]         = $this->post('pembicara');
        $param["MATERI"]            = $this->post('materi');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_TRAINING_RECORD_INSERT",$param,'post',TRUE);
    }
    /**
     * [training_record_put description]
     * @return [type] [description]
     */
    public function training_record_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NIK"]               = $this->put('nik');
        $param["NAMA_TRAINING"]     = $this->put('nama_training');
        //$this->Main_model->data_sudahada($param,"TRANS_TRAINING_RECORD",TRUE);
        $param["STATUS_TRAINING"]   = $this->put('status_training');
        $param["TGL_TRAINING"]      = tglToSql($this->put('tgl_training'));
        $param["DURASI"]            = $this->put('durasi');
        $param["LOKASI"]            = $this->put('lokasi');
        $param["PEMBICARA"]         = $this->put('pembicara');
        $param["MATERI"]            = $this->put('materi');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_TRAINING_RECORD_UPDATE",$param,'put',TRUE);
    }

    /**
     * [training_record_delete description]
     * @return [type] [description]
     */
    public function training_record_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_TRAINING_RECORD_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pkb_pickingstatus_put description]
     * @return [type] [description]
     */
    public function pkb_pickingstatus_put(){
        $param = array();
        $param["NO_PKB"]            = $this->put('no_pkb');
        $param["STATUS_APPROVAL"]   = $this->put('status_approval');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_PICKINGSTATUS_UPDATE",$param,'put',TRUE);
    }

    /**
     * [pkb_finalconfirm_put description]
     * @return [type] [description]
     */
    public function pkb_finalconfirm_put(){
        $param = array();
        $param["NO_PKB"]                = $this->put('no_pkb');
        $param["FINAL_CONFIRMATION"]    = $this->put('final_confirmation');
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PKB_FINALCONFIRM_UPDATE",$param,'put',TRUE);
    }
	public function tahunpkb_get(){
		$param = array();
		$this->Main_model->set_selectfield("DISTINCT(YEAR(TANGGAL_PKB)) AS TAHUN");
		$this->Main_model->set_orderby("TAHUN DESC");
		$this->resultdata("TRANS_PKB",$param);
	}

}
?>