<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Stnkbpkb extends REST_Controller {

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
     * [stnk_get description]
     * @return [type] [description]
     */
    public function stnk_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS" => $this->get("keyword")
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
        $this->resultdata("TRANS_STNK",$param);
    }
    /**
     * [stnk_post description]
     * @return [type] [description]
     */
    public function stnk_post(){
        $param = array();

        $param["NO_TRANS"]      = $this->post('no_trans');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $this->Main_model->data_sudahada($param,"TRANS_STNK");
        $param["TGL_STNK"]    = tglToSql($this->post('tgl_stnk'));
        $param["NAMA_PENGURUS"]    = $this->post('nama_pengurus');
        $param["TGLMULAI_PENGURUSAN"]    = tglToSql($this->post('tglmulai_pengurusan'));
        $param["TGLSELESAI_PENGURUSAN"]    = tglToSql($this->post('tglselesai_pengurusan'));
        $param["STATUS_MANUAL "]    = $this->post('status_manual');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_put description]
     * @return [type] [description]
     */
    public function stnk_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_STNK"]        = tglToSql($this->put('tgl_stnk'));
        $param["NAMA_PENGURUS"]        = $this->put('nama_pengurus');
        $param["KD_DEALER"]        = $this->put('kd_dealer');
        $param["KD_MAINDEALER"]        = $this->put('kd_maindealer');
        $param["TGLMULAI_PENGURUSAN"]        = tglToSql($this->put('tglmulai_pengurusan'));
        $param["TGLSELESAI_PENGURUSAN"]        = tglToSql($this->put('tglselesai_pengurusan'));
        $param["STATUS_MANUAL "]    = $this->put('status_manual');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [stnk_delete description]
     * @return [type] [description]
     */
    public function stnk_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete('no_trans');
        $param["ROW_STATUS"]        = $this->delete('row_status');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stnk_detail_get description]
     * @return [type] [description]
     */
    public function stnk_detail_get(){
        $param = array();$search='';
        if($this->get("stnk_id")){
            $param["STNK_ID"]     = $this->get("stnk_id");
        }
        if($this->get("status_stnk")){
            $param["STNK_ID"]     = $this->get("status_stnk");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("kd_mesin")){
            $param["KD_MESIN"]     = $this->get("kd_mesin");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("reff_source")){
            $param["REFF_SOURCE"]     = $this->get("reff_source");
        }
        if($this->get("kd_kota")){
            $param["KD_KOTA"]     = $this->get("kd_kota");
        }
        if($this->get("req_bpkb")){
            $param["REQ_BPKB"]     = $this->get("req_bpkb");
        }
        if($this->get("req_stck")){
            $param["REQ_STCK"]     = $this->get("req_stck");
        }
        if($this->get("req_admin_samsat")){
            $param["REQ_ADMIN_SAMSAT"]     = $this->get("req_admin_samsat");
        }
        if($this->get("req_plat_asli")){
            $param["REQ_PLAT_ASLI"]     = $this->get("req_plat_asli");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "STNK_ID" => $this->get("keyword"),
                "NAMA_PEMILIK" => $this->get("keyword"),
                "KD_ITEM" => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if($this->get("query")){
            $this->Main_model->set_custom_query($this->get("query"));
        }
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_STNK_DETAIL",$param);
    }
    /**
     * [stnk_detail_post description]
     * @return [type] [description]
     */
    public function stnk_detail_post(){
        $param = array();

        $param["STNK_ID"]      = $this->post('stnk_id');
        $param["NO_RANGKA"]  = $this->post('no_rangka');
        $param["KD_MESIN"]    = $this->post('kd_mesin');
        $param["NO_MESIN"]    = $this->post('no_mesin');
        $param["REFF_SOURCE"]    = $this->post('reff_source');
        $this->Main_model->data_sudahada($param,"TRANS_STNK_DETAIL");
        $param["NO_STNK"]    = $this->post('no_stnk');
        $param["NO_PENGAJUAN"]    = $this->post('no_pengajuan');
        $param["NAMA_PEMILIK"]    = $this->post('nama_pemilik');
        $param["ALAMAT_PEMILIK"]    = $this->post('alamat_pemilik');
        $param["KD_KELURAHAN"]    = $this->post('kd_kelurahan');
        $param["KD_KECAMATAN"]    = $this->post('kd_kecamatan');
        $param["KD_KOTA"]    = $this->post('kd_kota');
        $param["KODE_POS"]    = $this->post('kode_pos');
        $param["KD_PROPINSI"]    = $this->post('kd_propinsi');
        $param["JENIS_PEMBAYARAN"]    = $this->post('jenis_pembayaran');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_FINCOY"]    = $this->post('kd_fincoy');
        $param["DP"]    = $this->post('dp');
        $param["TENOR"]    = $this->post('tenor');
        $param["BESAR_CICILAN"]    = $this->post('besar_cicilan');
        $param["KD_CUSTOMER"]      = $this->post('kd_customer');
        $param["STATUS_PEMBAYARAN"]      = $this->post('status_pembayaran');
        $param["MATERAI"]      = $this->post('materai');
        $param["STATUS_STNK"]      = $this->post('status_stnk');
        $param["KD_ITEM"]      = $this->post('kd_item');
        $param["NO_SURATJALAN"]      = $this->post('no_suratjalan');
        $param["BIAYA_STNK"]      = $this->post('biaya_stnk');
        $param["STCK"]      = $this->post('stck');
        $param["PLAT_ASLI"]      = $this->post('plat_asli');
        $param["ADMIN_SAMSAT"]      = $this->post('admin_samsat');
        $param["BPKB"]      = $this->post('bpkb');
        $param["BIAYA_BBN"]      = $this->post('biaya_bbn');
        $param["BIAYA_BPKB"]      = $this->post('biaya_bpkb');
        $param["BBNKB"]      = $this->post('bbnkb');
        $param["PKB"]      = $this->post('pkb');
        $param["SWDKLLJ"]      = $this->post('swdkllj');
        $param["SS"]      = $this->post('ss');
        $param["BANPEN"]      = $this->post('banpen');
        $param["STATUS_PENGURUSAN"] = $this->post('status_pengurusan');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_detail_put description]
     * @return [type] [description]
     */
    public function stnk_detail_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STNK_ID"]          = $this->put('stnk_id');
        $param["NO_RANGKA"]      = $this->put('no_rangka');
        $param["KD_MESIN"]        = $this->put('kd_mesin');
        $param["NO_MESIN"]        = $this->put('no_mesin');
        $param["NO_STNK"]        = $this->put('no_stnk');
        $param["NO_PENGAJUAN"]        = $this->put('no_pengajuan');
        $param["NAMA_PEMILIK"]        = $this->put('nama_pemilik');
        $param["ALAMAT_PEMILIK"]        = $this->put('alamat_pemilik');
        $param["KD_KELURAHAN"]        = $this->put('kd_kelurahan');
        $param["KD_KECAMATAN"]        = $this->put('kd_kecamatan');
        $param["KD_KOTA"]        = $this->put('kd_kota');
        $param["KODE_POS"]        = $this->put('kode_pos');
        $param["KD_PROPINSI"]        = $this->put('kd_propinsi');
        $param["JENIS_PEMBAYARAN"]        = $this->put('jenis_pembayaran');
        $param["KD_DEALER"]        = $this->put('kd_dealer');
        $param["KD_FINCOY"]        = $this->put('kd_fincoy');
        $param["DP"]        = $this->put('dp');
        $param["TENOR"]        = $this->put('tenor');
        $param["BESAR_CICILAN"]        = $this->put('besar_cicilan');
        $param["KD_CUSTOMER"]      = $this->put('kd_customer');
        $param["STATUS_PEMBAYARAN"]      = $this->put('status_pembayaran');
        $param["MATERAI"]      = $this->put('materai');
        $param["STATUS_STNK"]      = $this->put('status_stnk');
        $param["KD_ITEM"]      = $this->put('kd_item');
        $param["NO_SURATJALAN"]      = $this->put('no_suratjalan');
        $param["BIAYA_STNK"]      = $this->put('biaya_stnk');
        $param["REFF_SOURCE"]      = $this->put('reff_source');
        $param["STCK"]      = $this->put('stck');
        $param["PLAT_ASLI"]      = $this->put('plat_asli');
        $param["ADMIN_SAMSAT"]      = $this->put('admin_samsat');
        $param["BPKB"]      = $this->put('bpkb');
        $param["BIAYA_BBN"]      = $this->put('biaya_bbn');
        $param["BIAYA_BPKB"]      = $this->put('biaya_bpkb');
        $param["BBNKB"]      = $this->put('bbnkb');
        $param["PKB"]      = $this->put('pkb');
        $param["SWDKLLJ"]      = $this->put('swdkllj');
        $param["SS"]      = $this->put('ss');
        $param["BANPEN"]      = $this->put('banpen');
        $param["STATUS_PENGURUSAN"]      = $this->put('status_pengurusan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [stnk_detail_delete description]
     * @return [type] [description]
     */
    public function stnk_detail_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stnk_detail_rw_delete description]
     * @return [type] [description]
     */
    public function stnk_detail_rw_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["ROW_STATUS"]        = $this->delete('row_status');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_DETAIL_RW_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stnkid_detail_delete description]
     * @return [type] [description]
     */
    public function stnkid_detail_delete(){
        $param = array();
        $param["STNK_ID"]          = $this->delete('stnk_id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_DETAIL_DELETE_BY_STNKID",$param,'delete',TRUE);
    }


    
    /**
     * [stnk_bukti_get description]
     * @return [type] [description]
     */
    public function stnk_bukti_get($view=null){
        $param = array();$search='';
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("keterangan")){
            $param["KETERANGAN"]     = $this->get("keterangan");
        }
        if($this->get("no_polisi")){
            if($view){
                $param["NO_POLISI"] = $this->get("no_polisi");
            }else{
                $param["DATA_NOMOR"] = $this->get("no_polisi");
            }
        }
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_RANGKA" => $this->get("keyword")
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
            $this->resultdata("TRANS_STNK_BUKTI_V",$param);
        }else{
            $this->resultdata("TRANS_STNK_BUKTI",$param);
        }
    }
    /**
     * [stnk_bukti_post description]
     * @return [type] [description]
     */
    public function stnk_bukti_post(){
        $param = array();

        $param["NO_RANGKA"]      = $this->post('no_rangka');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $this->Main_model->data_sudahada($param,"TRANS_STNK_BUKTI");
        $param["DIRECTORY_BUKTITERIMA"]  = $this->post('directory_buktiterima');
        $param["NAMA_PENERIMA"]  = $this->post('nama_penerima');
        $param["TGL_PENERIMA"]    = tglToSql($this->post('tgl_penerima'));
        $param["ALAMAT"]  = $this->post('alamat');
        $param["NOHP"]    = $this->post('nohp');
        $param["STATUS_PENERIMA"]  = $this->post('status_penerima');
        $param["DATA_NOMOR"]  = $this->post('data_nomor');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_BUKTI_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_bukti_put description]
     * @return [type] [description]
     */
    public function stnk_bukti_put(){
        $param = array();
        $param["NO_RANGKA"]          = $this->put('no_rangka');
        $param["DIRECTORY_BUKTITERIMA"]  = $this->put('directory_buktiterima');
        $param["NAMA_PENERIMA"]  = $this->put('nama_penerima');
        $param["TGL_PENERIMA"]    = tglToSql($this->put('tgl_penerima'));
        $param["ALAMAT"]  = $this->put('alamat');
        $param["NOHP"]    = $this->put('nohp');
        $param["STATUS_PENERIMA"]  = $this->put('status_penerima');
        $param["DATA_NOMOR"]  = $this->put('data_nomor');
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BUKTI_UPDATE",$param,'put',TRUE);
    }


    /**
     * [stnk_bukti_post description]
     * @return [type] [description]
     */
    public function stnk_bukti_terima_post(){
        $param = array();

        $param["NO_RANGKA"]      = $this->post('no_rangka');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $this->Main_model->data_sudahada($param,"TRANS_STNK_BUKTI");
        $param["TGL_PENERIMA"]    = tglToSql($this->post('tgl_penerima'));
        $param["STATUS_PENERIMA"]  = $this->post('status_penerima');
        $param["DATA_NOMOR"]  = $this->post('data_nomor');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_BUKTI_TERIMA_INSERT",$param,'post',TRUE);
    }


    /**
     * [stnk_bukti_put description]
     * @return [type] [description]
     */
    public function stnk_bukti_penyerahan_put(){
        $param = array();
        $param["NO_RANGKA"]          = $this->put('no_rangka');
        $param["NAMA_PENERIMA"]  = $this->put('nama_penerima');
        $param["TGL_PENYERAHAN"]    = tglToSql($this->put('tgl_penyerahan'));
        $param["ALAMAT"]  = $this->put('alamat');
        $param["NOHP"]    = $this->put('nohp');
        $param["STATUS_PENERIMA"]  = $this->put('status_penerima');
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["JENIS_PENYERAHAN"]  = $this->put('jenis_penyerahan');
        $param["NO_PENYERAHAN"]  = $this->put('no_penyerahan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BUKTI_PENYERAHAN_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stnk_bukti_delete description]
     * @return [type] [description]
     */
    public function stnk_bukti_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_BUKTI_DELETE",$param,'delete',TRUE);
    }


    /**
     * [stnk_buktipenyerahan_post description]
     * @return [type] [description]
     */
    public function stnk_buktipenyerahan_post(){
        $param = array();

        $param["NO_RANGKA"]      = $this->post('no_rangka');
        $this->Main_model->data_sudahada($param,"TRANS_STNK_BUKTI");
        $param["DIRECTORY_BUKTIPENYERAHAN"]  = $this->post('directory_buktipenyerahan');
        $param["STATUS_PENERIMA"]  = $this->post('status_penerima');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_BUKTIPENYERAHAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_bukti_penyerahan_file_put description]
     * @return [type] [description]
     */
    public function stnk_bukti_penyerahan_file_put(){
        $param = array();
        $param["NO_RANGKA"]          = $this->put('no_rangka');
        $param["DIRECTORY_BUKTIPENYERAHAN"]  = $this->put('directory_buktipenyerahan');
        $param["STATUS_PENERIMA"]  = $this->put('status_penerima');
        $param["KETERANGAN"]  = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BUKTI_PENYERAHAN_FILE_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stnk_bukti_penyerahan_file_put description]
     * @return [type] [description]
     */
    public function stnk_bukti_penyerahan_group_file_put(){
        $param = array();
        $param["NO_PENYERAHAN"]          = $this->put('no_penyerahan');
        $param["DIRECTORY_BUKTIPENYERAHAN"]  = $this->put('directory_buktipenyerahan');
        $param["STATUS_PENERIMA"]  = $this->put('status_penerima');
        $param["KETERANGAN"]  = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BUKTI_PENYERAHAN_GROUP_FILE_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stnk_bpkb_get description]
     * @return [type] [description]
     */
    public function stnk_bpkb_get($view=null){
        $param = array();$search='';
        if($this->get("kd_tipemotor")){
            $param["KD_TIPEMOTOR"]     = $this->get("kd_tipemotor");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("tahun")){
            $param["TAHUN"]     = $this->get("tahun");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_TIPEMOTOR" => $this->get("keyword"),
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
        
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));

        $this->Main_model->set_where_or($this->get("where_or"));
        $this->Main_model->set_where_or_field($this->get("where_or_field"));
        $this->Main_model->set_wheregroup($this->get("grouping"));
        $this->Main_model->set_wheregroup_type($this->get("where_group"));

        $this->Main_model->set_where_orout($this->get("where_orout"));
        $this->Main_model->set_where_or_fieldout($this->get("where_or_fieldout"));
        $this->Main_model->set_wheregroupout($this->get("groupingout"));
        $this->Main_model->set_wheregroup_typeout($this->get("where_groupout"));
        if($view){
            $this->Main_model->set_custom_query($this->Custom_model->getBiayaBPKB($this->get("kd_dealer"),$this->get('row_status')));
            $this->resultdata("MASTER_BIAYA_STNKBPKB_V",$param);
        }else{
            $this->resultdata("MASTER_STNK_BPKB",$param);
        }
        
    }
    /**
     * [stnk_bpkb_post description]$this->get('row_status')
     * @return [type] [description]
     */
    public function stnk_bpkb_post($updateAll=null){
        $param = array();
        $param["KD_TIPEMOTOR"]      = $this->post('kd_tipemotor');
        $param["KD_KABUPATEN"]  = $this->post('kd_kabupaten');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["TAHUN"]    = $this->post('tahun');
        $this->Main_model->set_reuseddata($this->post("re_insert"));
        $this->Main_model->data_sudahada($param,"MASTER_STNK_BPKB");
        if(!$updateAll){
            $param["BBNKB"]    = $this->post('bbnkb');
            $param["PKB"]    = $this->post('pkb');
            $param["SWDKLLJ"]    = $this->post('swdkllj');
            $param["TOTAL_STNK"]    = $this->post('total_stnk');
        }
        $param["WILAYAH_SAMSAT"] = $this->post('wilayah_samsat');
        $param["STCK"]    = $this->post('stck');
        $param["PLAT_ASLI"]    = $this->post('plat_asli');
        $param["ADMIN_SAMSAT"]    = $this->post('admin_samsat');
        $param["BPKB"]    = $this->post('bpkb');
        $param["PENGURUSAN_TAMBAHAN"]    = $this->post('pengurusan_tambahan');
        $param["TOTAL_BPKB"]    = $this->post('total_bpkb');
        $param["SS"]    = $this->post('ss');
        $param["BANPEN"]    = $this->post('banpen');
        $param["KD_PROPINSI"]    = $this->post('kd_propinsi');
        $param["ROW_STATUS"]    = $this->post('row_status');
        $param["TIPE_CUSTOMER"] = $this->post("tipe_customer");
        $param["CREATED_BY"]    = $this->post('created_by');
        if($updateAll){
            $this->resultdata("SP_MASTER_STNK_BPKB_INSERT_ALL",$param,'post',TRUE);
        }else{
            $this->resultdata("SP_MASTER_STNK_BPKB_INSERT",$param,'post',TRUE);
        }
    }
    /**
     * [stnk_bpkb_put description]
     * @return [type] [description]
     */
    public function stnk_bpkb_put($updateAll=null){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_TIPEMOTOR"]          = $this->put('kd_tipemotor');
        $param["KD_KABUPATEN"]  = $this->put('kd_kabupaten');
        $param["KD_DEALER"]    = $this->put('kd_dealer');
        $param["TAHUN"]    = $this->put('tahun');
        if(!$updateAll){
            $param["BBNKB"]    = $this->put('bbnkb');
            $param["PKB"]    = $this->put('pkb');
            $param["SWDKLLJ"]    = $this->put('swdkllj');
            $param["TOTAL_STNK"]    = $this->put('total_stnk');
        }
        $param["STCK"]    = $this->put('stck');
        $param["PLAT_ASLI"]    = $this->put('plat_asli');
        $param["ADMIN_SAMSAT"]    = $this->put('admin_samsat');
        $param["BPKB"]    = $this->put('bpkb');
        $param["PENGURUSAN_TAMBAHAN"]    = $this->put('pengurusan_tambahan');
        $param["TOTAL_BPKB"]    = $this->put('total_bpkb');
        $param["SS"]    = $this->put('ss');
        $param["BANPEN"]    = $this->put('banpen');
        $param["KD_PROPINSI"]    = $this->put('kd_propinsi');
        $param["WILAYAH_SAMSAT"] = $this->put('wilayah_samsat');
        $param["TIPE_CUSTOMER"] = $this->put("tipe_customer");
        $param["ROW_STATUS"]    = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        if($updateAll){
            $this->resultdata("SP_MASTER_STNK_BPKB_UPDATE_ALL",$param,'put',TRUE);
        }else{
            $this->resultdata("SP_MASTER_STNK_BPKB_UPDATE",$param,'put',TRUE);
        }
    }
    /**
     * [stnk_bpkb_delete description]
     * @return [type] [description]
     */
    public function stnk_bpkb_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_STNK_BPKB_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stnk_bpkb_backup_get description]
     * @return [type] [description]
     */
    public function stnk_bpkb_backup_get(){
        $param = array();$search='';
        if($this->get("kd_tipemotor")){
            $param["KD_TIPEMOTOR"]     = $this->get("kd_tipemotor");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_TIPEMOTOR" => $this->get("keyword"),
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
        $this->resultdata("MASTER_STNK_BPKB_BACKUP",$param);
    }
    /**
     * [stnk_bpkb_backup_post description]
     * @return [type] [description]
     */
    public function stnk_bpkb_backup_post(){
        $param = array();

        $param["KD_TIPEMOTOR"]      = $this->post('kd_tipemotor');
        $param["KD_KABUPATEN"]  = $this->post('kd_kabupaten');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["TAHUN"]    = $this->post('tahun');
        $param["BBNKB"]    = $this->post('bbnkb');
        $param["PKB"]    = $this->post('pkb');
        $param["SWDKLLJ"]    = $this->post('swdkllj');
        $param["TOTAL_STNK"]    = $this->post('total_stnk');
        $param["STCK"]    = $this->post('stck');
        $param["PLAT_ASLI"]    = $this->post('plat_asli');
        $param["ADMIN_SAMSAT"]    = $this->post('admin_samsat');
        $param["BPKB"]    = $this->post('bpkb');
        $param["PENGURUSAN_TAMBAHAN"]    = $this->post('pengurusan_tambahan');
        $param["TOTAL_BPKB"]    = $this->post('total_bpkb');
        $param["SS"]    = $this->post('ss');
        $param["BANPEN"]    = $this->post('banpen');
        $param["NIK"]    = $this->post('nik');
        $param["STATUS_APPROVE"]    = $this->post('status_approve');
        $param["KD_PROPINSI"]    = $this->post('kd_propinsi');
        $param["ID_STNKBPKB"]    = $this->post('id_stnkbpkb');
        $this->Main_model->data_sudahada($param,"MASTER_STNK_BPKB_BACKUP");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_STNK_BPKB_BACKUP_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_bpkb_backup_put description]
     * @return [type] [description]
     */
    public function stnk_bpkb_backup_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_TIPEMOTOR"]          = $this->put('kd_tipemotor');
        $param["KD_KABUPATEN"]  = $this->put('kd_kabupaten');
        $param["KD_DEALER"]    = $this->put('kd_dealer');
        $param["TAHUN"]    = $this->put('tahun');
        $param["BBNKB"]    = $this->put('bbnkb');
        $param["PKB"]    = $this->put('pkb');
        $param["SWDKLLJ"]    = $this->put('swdkllj');
        $param["TOTAL_STNK"]    = $this->put('total_stnk');
        $param["STCK"]    = $this->put('stck');
        $param["PLAT_ASLI"]    = $this->put('plat_asli');
        $param["ADMIN_SAMSAT"]    = $this->put('admin_samsat');
        $param["BPKB"]    = $this->put('bpkb');
        $param["PENGURUSAN_TAMBAHAN"]    = $this->put('pengurusan_tambahan');
        $param["TOTAL_BPKB"]    = $this->put('total_bpkb');
        $param["SS"]    = $this->put('ss');
        $param["BANPEN"]    = $this->put('banpen');
        $param["NIK"]    = $this->put('nik');
        $param["STATUS_APPROVE"]    = $this->put('status_approve');
        $param["KD_PROPINSI"]    = $this->put('kd_propinsi');
        $param["ID_STNKBPKB"]    = $this->put('id_stnkbpkb');
        $param["ROW_STATUS"]    = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_STNK_BPKB_BACKUP_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stnk_biaya_get description]
     * @return [type] [description]
     */
    public function stnk_biaya_get($pengurusan=null){
        $param = array();$search='';
        if($this->get("no_pengajuan")){
            $param["NO_PENGAJUAN"]     = $this->get("no_pengajuan");
        }
        if($this->get("approve")){
            $param["BIAYA_APPROVE"]     = $this->get("approve");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_PENGAJUAN" => $this->get("keyword")
                );
        }
        if($pengurusan==true){
            if($this->get("kd_dealer")){
                $param["KD_DEALER"] = $this->get("kd_dealer");
            }
            if($this->get("kd_birojasa")){
                $param["KD_BIROJASA"] = $this->get("kd_birojasa");
            }
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
        if($pengurusan==true){
            $this->resultdata("TRANS_STNK_PENGURUS_V3",$param);
        }else{
            $this->resultdata("TRANS_STNK_BIAYA",$param);
        }
        
    }
    /**
     * [stnk_biaya_post description]
     * @return [type] [description]
     */
    public function stnk_biaya_post(){
        $param = array();

        $param["NO_PENGAJUAN"]      = $this->post('no_pengajuan');
        $this->Main_model->data_sudahada($param,"TRANS_STNK_BIAYA");
        $param["TOTAL_BIAYAPENGAJUAN"]  = $this->post('total_biayapengajuan');
        $param["TOTAL_BIAYAAPPROVE"]    = $this->post('total_biayaapprove');
        $param["TGL_APPROVE"]    = tglToSql($this->post('tgl_approve'));
        $param["APPROVE_BY"]    = $this->post('approve_by');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_BIAYA_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_biaya_put description]
     * @return [type] [description]
     */
    public function stnk_biaya_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NO_PENGAJUAN"]          = $this->put('no_pengajuan');
        $param["TOTAL_BIAYAPENGAJUAN"]  = $this->put('total_biayapengajuan');
        $param["TOTAL_BIAYAAPPROVE"]    = $this->put('total_biayaapprove');
        $param["TGL_APPROVE"]    = tglToSql($this->put('tgl_approve'));
        $param["APPROVE_BY"]    = $this->put('approve_by');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BIAYA_UPDATE",$param,'put',TRUE);
    }
    /**
     * [stnk_biaya_delete description]
     * @return [type] [description]
     */
    public function stnk_biaya_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_BIAYA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stnk_biayaapprove_put description]
     * @return [type] [description]
     */
    public function stnk_biayaapprove_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STATUS_STNK"]          = $this->put('status_stnk');
        $param["BIAYA_STNK"]  = $this->put('biaya_stnk');
        $param["BIAYA_BBN"]  = $this->put('biaya_bbn');

        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BIAYAAPPROVE_UPDATE",$param,'put',TRUE);
    }


    /**
     * [stnk_approve_biayadetail_put description]
     * @return [type] [description]
     */
    public function stnk_status_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STATUS_STNK"]          = $this->put('status_stnk');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_STATUS_UPDATE",$param,'put',TRUE);
    }


     /**
     * [trans_stnk_bpkb_get description]
     * @return [type] [description]
     */
    public function trans_stnk_bpkb_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
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
                "NO_TRANS" => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword")
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
        $this->resultdata("TRANS_STNK_BPKB",$param);
    }
    /**
     * [trans_stnk_bpkb_post description]
     * @return [type] [description]
     */
    public function trans_stnk_bpkb_post(){
        $param = array();

        $param["NO_TRANS"]      = $this->post('no_trans');
        $param["TGL_TRANS"]  = tglToSql($this->post('tgl_trans'));
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["NO_RANGKA"]    = $this->post('no_rangka');
        $param["NO_MESIN"]    = $this->post('no_mesin');
        $this->Main_model->data_sudahada($param,"TRANS_STNK_BPKB");
        $param["BBNKB"]    = ($this->post('bbnkb'))?$this->post('bbnkb'):0;
        $param["PKB"]    = ($this->post('pkb'))?$this->post('pkb'):0;
        $param["SWDKLLJ"]    = ($this->post('swdkllj'))?$this->post('swdkllj'):0;
        $param["STCK"]    = ($this->post('stck'))?$this->post('stck'):0;
        $param["PLAT_ASLI"]    = ($this->post('plat_asli'))?$this->post('plat_asli'):0;
        $param["ADMIN_SAMSAT"]    = ($this->post('admin_samsat'))?$this->post('admin_samsat'):0;
        $param["BPKB"]    = ($this->post('bpkb'))?$this->post('bpkb'):0;
        $param["SS"]    = ($this->post('ss'))?$this->post('ss'):0;
        $param["BANPEN"]    = ($this->post('banpen'))?$this->post('banpen'):0;
        $param["NO_STNK"]    = $this->post('no_stnk');
        $param["NO_PLAT"]    = $this->post('no_plat');
        $param["NO_BPKB"]    = $this->post('no_bpkb');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_STNK_BPKB_INSERT",$param,'post',TRUE);
    }
    /**
     * [trans_stnk_bpkb_put description]
     * @return [type] [description]
     */
    public function trans_stnk_bpkb_put(){
        $param = array();
        //$param["ID"]          = $this->put('id');
        $param["NO_TRANS"]      = $this->put('no_trans');
        $param["TGL_TRANS"]  = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]    = $this->put('kd_maindealer');
        $param["KD_DEALER"]    = $this->put('kd_dealer');
        $param["NO_RANGKA"]    = $this->put('no_rangka');
        $param["NO_MESIN"]    = $this->put('no_mesin');
        $param["BBNKB"]    = $this->put('bbnkb');
        $param["PKB"]    = $this->put('pkb');
        $param["SWDKLLJ"]    = $this->put('swdkllj');
        $param["STCK"]    = $this->put('stck');
        $param["PLAT_ASLI"]    = $this->put('plat_asli');
        $param["ADMIN_SAMSAT"]    = $this->put('admin_samsat');
        $param["BPKB"]    = $this->put('bpkb');
        $param["SS"]    = $this->put('ss');
        $param["BANPEN"]    = $this->put('banpen');
        $param["NO_STNK"]    = $this->put('no_stnk');
        $param["NO_PLAT"]    = $this->put('no_plat');
        $param["NO_BPKB"]    = $this->put('no_bpkb');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_BPKB_UPDATE",$param,'put',TRUE);
    }
    /**
     * [trans_stnk_bpkb_delete description]
     * @return [type] [description]
     */
    public function trans_stnk_bpkb_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STNK_BPKB_DELETE",$param,'delete',TRUE);
    }

    /**
     * [bpkb_biayaapprove_put description]
     * @return [type] [description]
     */
    public function bpkb_biayaapprove_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["STATUS_STNK"]  = $this->put('status_stnk');
        $param["BIAYA_BPKB"]  = $this->put('biaya_bpkb');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_BPKB_BIAYAAPPROVE_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stnk_detail_file_put description]
     * @return [type] [description]
     */
    public function stnk_detail_file_put(){
        $param = array();
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["STATUS_FILE"]  = "1";//$this->put('status_file');
        $param["NO_PENGAJUAN"] = $this->put("no_pengajuan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_FILE_UPDATE",$param,'put',TRUE);
    }


    /**
     * [birojasa_get description]
     * @return [type] [description]
     */
    public function birojasa_get(){
        $param = array();$search='';
        if($this->get("kd_birojasa")){
            $param["KD_BIROJASA"]     = $this->get("kd_birojasa");
        }
        if($this->get("nama_birojasa")){
            $param["NAMA_BIROJASA"]     = $this->get("nama_birojasa");
        }
        if($this->get("nama_pengurus")){
            $param["NAMA_PENGURUS"]     = $this->get("nama_pengurus");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_BIROJASA" => $this->get("keyword"),
                "NAMA_BIROJASA" => $this->get("keyword"),
                "NAMA_PENGURUS" => $this->get("keyword")

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
        $this->resultdata("MASTER_BIROJASA",$param);
    }
    /**
     * [birojasa_post description]
     * @return [type] [description]
     */
    public function birojasa_post(){
        $param = array();
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $param["KD_BIROJASA"]   = $this->post('kd_birojasa');
        $this->Main_model->data_sudahada($param,"MASTER_BIROJASA",TRUE);
        $param["NAMA_BIROJASA"]    = $this->post('nama_birojasa');
        $param["NAMA_PENGURUS"]      = $this->post('nama_pengurus');
        $param["KD_MAINDEALER"]      = $this->post('kd_maindealer');
        
        $param["ALAMAT"]  = $this->post('alamat');
        $param["STATUS_BIROJASA"]    = $this->post('status_birojasa');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_BIROJASA_INSERT",$param,'post',TRUE);
    }
    /**
     * [birojasa_put description]
     * @return [type] [description]
     */
    public function birojasa_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_BIROJASA"]          = $this->put('kd_birojasa');
        $param["KD_MAINDEALER"]    = $this->put('kd_maindealer');
        $param["KD_DEALER"]    = $this->put('kd_dealer');
        $param["NAMA_BIROJASA"]  = $this->put('nama_birojasa');
        $param["NAMA_PENGURUS"]    = $this->put('nama_pengurus');
        $param["ALAMAT"]    = $this->put('alamat');
        $param["STATUS_BIROJASA"]    = $this->put('status_birojasa');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_BIROJASA_UPDATE",$param,'put',TRUE);
    }
    /**
     * [birojasa_delete description]
     * @return [type] [description]
     */
    public function birojasa_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_BIROJASA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [udh_get description]
     * @return [type] [description]
     */
    public function udh_get(){
        $param = array();$search='';
        if($this->get("status_stnk")){
            $param["STATUS_STNK"]     = $this->get("status_stnk");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "DATA_NOMOR_STNK" => $this->get("keyword"),
                "KD_ITEM" => $this->get("keyword")

                );
        }
       $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom=null;
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_STNK_UDH",$param);
    }

    /**
     * [stnk_detail_status_put description]
     * @return [type] [description]
     */
    public function stnk_detail_status_put(){
        $param = array();
        $param["STATUS_STNK"]          = $this->put('status_stnk');
        $param["NO_RANGKA"]          = $this->put('no_rangka');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_STATUS_UPDATE",$param,'put',TRUE);
    }
    /**
     * [stnk_paybill_put description]
     * @return [type] [description]
     */
    public function stnk_paybill_put(){
        $param = array();
        $param["ID"]         = $this->put('stnk_id');
        $param["STATUS_STNK"]= $this->put('status_stnk');
        $param["NO_RANGKA"]  = $this->put('no_rangka');
        $param["NO_REFF"]    = $this->put("no_reff");
        $param["PROCESS"]    = $this->put("proses");
        $param["JENIS_BAYAR"]= $this->put("jenis_bayar");
        $param["TGL_PINJAM"] = $this->put("tgl_pinjam");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_PAYBILL",$param,'put',TRUE);
    }
    /**
     * [Update pengurusan stnkbpkb setelah di bayar]
     * @return [type] [description]
     */
    public function stnkpaybill_put(){
        $param = array();
        $param["STNK_ID"]   = $this->put('stnk_id');
        $param["STATUS"]    = $this->put('status_stnk');
        $param["NO_RANGKA"]  = $this->put('no_rangka');
        $param["NO_REFF"]    = $this->put("no_reff");
        $param["FIELD"]    = $this->put("field");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_PAYBILL_N",$param,'put',TRUE);
    }
    /**
     * [stnk_detail_reqstck_put description]
     * @return [type] [description]
     */
    public function stnk_detail_reqstck_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["REQ_STCK"]          = $this->put('req_stck');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_UPDATE_REQSTCK",$param,'put',TRUE);
    }

    /**
     * [stnk_detail_reqbpkb_put description]
     * @return [type] [description]
     */
    public function stnk_detail_reqbpkb_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["REQ_BPKB"]          = $this->put('req_bpkb');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_UPDATE_REQBPKB",$param,'put',TRUE);
    }

    /**
     * [stnk_detail_reqadminsamsat_put description]
     * @return [type] [description]
     */
    public function stnk_detail_reqadminsamsat_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["REQ_ADMIN_SAMSAT"]          = $this->put('req_admin_samsat');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_UPDATE_REQADMINSAMSAT",$param,'put',TRUE);
    }

    /**
     * [stnk_detail_reqplatasli_put description]
     * @return [type] [description]
     */
    public function stnk_detail_reqplatasli_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["REQ_PLAT_ASLI"]          = $this->put('req_plat_asli');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_DETAIL_UPDATE_REQPLATASLI",$param,'put',TRUE);
    }

    /**
     * [stnk_pengurusan_get description]
     * @return [type] [description]
     */
    public function stnk_pengurusan_get($new_view=null){
        $param = array();$search='';
        if($this->get("kabupaten_samsat")){
            $param["KABUPATEN_SAMSAT"]     = $this->get("kabupaten_samsat");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("kd_jenisdealer")){
            $param["KD_JENISDEALER"]     = $this->get("kd_jenisdealer");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("id_suratjalan")){
            $param["ID_SURATJALAN"]     = $this->get("id_suratjalan");
        }
        if($this->get("tahun")){
            $param["TAHUN"]     = $this->get("tahun");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_RANGKA" => $this->get("keyword"),
                "NO_MESIN" => $this->get("keyword"),
                "TAHUN" => $this->get("keyword")

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
        if($new_view){
            $this->resultdata("TRANS_STNK_PENGURUSAN_V",$param);
        }else{
            $this->resultdata("TRANS_STNK_PENGURUSAN_VIEW",$param);
        }
    }


    /**
     * [stnk_pengurusan_get description]
     * @return [type] [description]
     */
    public function stnk_batastoleransi_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("kd_jenisdealer")){
            $param["KD_JENISDEALER"]     = $this->get("kd_jenisdealer");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("status_pengurusan")){
            $param["STATUS_PENGURUSAN"]     = $this->get("status_pengurusan");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_RANGKA" => $this->get("keyword"),
                "NO_MESIN" => $this->get("keyword")

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
        $this->resultdata("TRANS_STNK_BATASTOLERANSI_VIEW",$param);
    }
    /**
     * [stnk_bukti_view_get description]
     * @return [type] [description]
     */
    public function stnk_bukti_view_get(){
        $param = array();$search='';
        if($this->get("no_penyerahan_bpkb")){
            $param["NO_PENYERAHAN_BPKB"]     = $this->get("no_penyerahan_bpkb");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("nama_penerima_stnk")){
            $param["NAMA_PENERIMA_STNK"]     = $this->get("nama_penerima_stnk");
        }
        if($this->get("data_nomor_stnk")){
            $param["DATA_NOMOR_STNK"]     = $this->get("data_nomor_stnk");
        }
        if($this->get("tahun")){
            $param["TAHUN"]     = $this->get("tahun");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_RANGKA" => $this->get("keyword"),
                "NAMA_PENERIMA_STNK" => $this->get("keyword")

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
        $this->resultdata("TRANS_STNK_BUKTI_VIEW",$param);
    }



     /**
     * [stnk_bpkb_wilayah_get description]
     * @return [type] [description]
     */
    public function stnk_bpkb_wilayah_get($view=null){
        $param = array();$search='';
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("kd_kecamatan")){
            $param["KD_KECAMATAN"]     = $this->get("kd_kecamatan");
        }
        if($this->get("samsat")){
            $param["SAMSAT"]     = $this->get("samsat");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_KABUPATEN" => $this->get("keyword"),
                "KD_KECAMATAN" => $this->get("keyword"),
                "SAMSAT" => $this->get("keyword")

                );
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]=$this->get("kd_dealer");
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
            $this->resultdata("MASTER_AREADEALER_VIEW",$param);
        }else{
            $this->resultdata("MASTER_STNK_BPKB_WILAYAH",$param);
        }
    }
    /**
     * [stnk_bpkb_wilayah_post description]
     * @return [type] [description]
     */
    public function stnk_bpkb_wilayah_post(){
        $param = array();

        $param["KD_KABUPATEN"]   = $this->post('kd_kabupaten');
        $param["KD_KECAMATAN"]    = $this->post('kd_kecamatan');
        $param["SAMSAT"]      = $this->post('samsat');
        $this->Main_model->data_sudahada($param,"MASTER_STNK_BPKB_WILAYAH",TRUE);
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_STNK_BPKB_WILAYAH_INSERT",$param,'post',TRUE);
    }
    /**
     * [stnk_bpkb_wilayah_put description]
     * @return [type] [description]
     */
    public function stnk_bpkb_wilayah_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_KABUPATEN"]          = $this->put('kd_kabupaten');
        $param["KD_KECAMATAN"]    = $this->put('kd_kecamatan');
        $param["SAMSAT"]    = $this->put('samsat');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_STNK_BPKB_WILAYAH_UPDATE",$param,'put',TRUE);
    }
    /**
     * [stnk_bpkb_wilayah_delete description]
     * @return [type] [description]
     */
    public function stnk_bpkb_wilayah_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_STNK_BPKB_WILAYAH_DELETE",$param,'delete',TRUE);
    }

    /**
     * [birojasa_outstanding_get description]
     * @return [type] [description]
     */
    public function birojasa_outstanding_get(){
        $param = array();$search='';
        if($this->get("kd_birojasa")){
            $param["KD_BIROJASA"]     = $this->get("kd_birojasa");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                
                "KD_BIROJASA" => $this->get("keyword")

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
        $this->resultdata("TRANS_BIROJASA_OUTSTANDING_VIEW",$param);
    }

    /**
     * [kabupaten_samsat_get description]
     * @return [type] [description]
     */
    public function kabupaten_samsat_get(){
        $param = array();$search='';
        if($this->get("kabupaten_samsat")){
            $param["KABUPATEN_SAMSAT"]     = $this->get("kabupaten_samsat");
        }
        if($this->get("nama_kabupaten")){
            $param["NAMA_KABUPATEN"]     = $this->get("nama_kabupaten");
        }
        if($this->get("samsat")){
            $param["SAMSAT"]     = $this->get("samsat");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KABUPATEN_SAMSAT" => $this->get("keyword"),
                "NAMA_KABUPATEN" => $this->get("keyword"),
                "SAMSAT" => $this->get("keyword")
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
        $this->resultdata("MASTER_KABUPATEN_SAMSAT_VIEW",$param);
    }

     /**
     * [stnk_statuscetak_put description]
     * @return [type] [description]
     */
    public function stnk_statuscetak_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["STATUS_CETAK"]      = $this->put('status_cetak');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_STNK_UPDATE_STATUSCETAK",$param,'put',TRUE);
    }

    public function header_insentifpic_stnk_post(){
        $param=array();
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["NO_PROSES"]  = $this->post("no_proses");
        $param["ROW_STATUS"]  = 0;
        $param["CREATED_BY"]   = $this->post("created_by");
        $param["PERIODE"]   = $this->post("periode");
        $param["TOTAL"] = $this->post("total");
        $this->resultdata("SP_TRANS_INSENTIF_PICSTNK_HEADER_INSERT",$param,'post',TRUE);       
    }
    
    public function detail_insentifpic_stnk_post(){
        $param=array();
        $param["NO_PROSES"] = $this->post("no_proses");
        $param["NIK"]  = $this->post("nik");
        $param["NAMA_PENGURUS"]  = $this->post("nama_pengurus");
        $param["NO_TRANSAKSI"] = $this->post("no_transaksi");
        $param["JUMLAH"] = $this->post("jumlah");
        $param["INSENTIF_PERUNIT"] = $this->post("insentif_perunit");
        $param["TGL_SELESAIPENGURUSAN"] = $this->post("tgl_selesaipengurusan");
        $param["ROW_STATUS"] = 0;
        $param["CREATED_BY"]   = $this->post("created_by");
        $this->resultdata("SP_TRANS_INSENTIF_PICSTNK_DETAIL_INSERT",$param,'post',TRUE);       
    }
    
    public function header_insentif_picstnk_get(){
        $param = array();
        if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer"); 
        }
        if($this->get("no_proses")){
            $param["NO_PROSES"] = $this->get("no_proses");
        }
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
        $this->resultdata("TRANS_INSENTIF_PICSTNK_HEADER",$param);
    }
    
    public function detail_insentif_picstnk_get(){
        $param = array();
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
        $this->resultdata("TRANS_INSENTIF_PICSTNK_DETAIL",$param);
    }
    
    public function header_insentif_picstnk_put(){
        $param = array();
        $param["NO_PROSES"] = $this->put("no_proses");
        $param["APPROVAL_STATUS"] = $this->put("approval_status");
        $param["APPROVAL_BY"]   = $this->put("approval_by");

        $this->resultdata("SP_TRANS_INSENTIF_PICSTNK_HEADER_UPDATE",$param,'put',TRUE);
        
    }
	
	public function detail_insentif_picstnk_put(){
        $param = array();
        $param["NO_PROSES"] = $this->put("no_proses");
		$param["APPROVAL_STATUS"] = $this->put("approval_status");
        $param["LASTMODIFIED_BY"] = $this->put("approval_by");

        $this->resultdata("SP_TRANS_INSENTIF_PICSTNK_DETAIL_UPDATE",$param,'put',TRUE);
    }
	
	public function dealer_area_ds_get(){
		$param = array();
        if($this->get("user_id")){
            $param["USER_ID"] = $this->get("user_id");
        }
        $this->Main_model->set_orderby("KD_DEALER");
        $this->Main_model->set_selectfield($this->get('field'));
		$this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("USERS_AREA",$param);
	}
    function fakturstnkws_get($with_status=null){
        ini_set('max_execution_time', 0);
        $param["nomormesin"] = $this->get("no_mesin");
        
        
        $data=array();
        $options = array(
            CURLOPT_URL => WS_URL."list49",
            CURLOPT_RETURNTRANSFER => 1,     // return web page 
            CURLOPT_HEADER         => 0,    // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING       => "",       // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
            CURLOPT_TIMEOUT        => 120,      // timeout on response 
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $param,
            CURLOPT_FAILONERROR    => 1
        );
        $cUrl = curl_init();
        curl_setopt_array( $cUrl, $options );
        $data = curl_exec( $cUrl );
        //$header=curl_getinfo( $cUrl );
        $curError = curl_errno($cUrl);
        $curMSG   = curl_error($cUrl);
        $curURL = $cUrl;
        curl_close( $cUrl );
        if($with_status){
            $result=array(
                'status'=>($curError==0)?TRUE: FALSE,
                'message' => ($curError>0)?$curMSG: $data,
                'error'=>$curError,
                'errmsg'=>$curMSG,
            );
            $this->response($result,REST_Controller::HTTP_OK); 
        }else{
            if(!$data){ $data=FALSE;}
           $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);            
        }
    }
}
?>