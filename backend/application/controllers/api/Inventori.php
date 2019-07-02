<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Inventori extends REST_Controller {
 
    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
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
    * Surat Jalan Keluar
    */
    public function sjkeluar_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_suratjalan")){
            $param["NO_SURATJALAN"]     = $this->get("no_suratjalan");
        }
        if($this->get("no_reff")){
            $param["NO_REFF"]     = $this->get("no_reff");
        }
        if($this->get("nama_pengirim")){
            $param["NAMA_PENGIRIM"]     = $this->get("nama_pengirim");
        }
        if($this->get("no_mobil")){
            $param["NO_MOBIL"]     = $this->get("no_mobil");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_SURATJALAN"  => $this->get("keyword"),
                "NO_REFF"   => $this->get("keyword"),
                "NAMA_PENGIRIM"    => $this->get("keyword"),
                "KD_CUSTOMER"    => $this->get("keyword"),
                "NAMA_PENERIMA"    => $this->get("keyword"),
                "NO_MOBIL"    => $this->get("keyword")
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
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SJKELUAR",$param);
    }

    public function sjkeluar_post(){
        $param = array();
        $param["NO_SURATJALAN"] = $this->post('no_suratjalan');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"] = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"TRANS_SJKELUAR");
        $param["TGL_SURATJALAN"]   = tglToSql($this->post('tgl_suratjalan'));
        $param["KD_GUDANG"] = $this->post('kd_gudang');
        $param["NO_REFF"]   = $this->post('no_reff');
        $param["KD_CUSTOMER"]  = $this->post('kd_customer');
        $param["ALAMAT_KIRIM"]  = $this->post('alamat_kirim');
        $param["TGL_KIRIM"] = tglToSql($this->post('tgl_kirim'));
        $param["NAMA_PENGIRIM"] = $this->post('nama_pengirim');
        $param["NAMA_EKSPEDISI"]  = $this->post('nama_ekspedisi');
        $param["NO_MOBIL"]  = $this->post('no_mobil');
        $param["NAMA_SOPIR"]    = $this->post('nama_sopir');
        $param["NAMA_PENERIMA"] = $this->post('nama_penerima');
        $param["TGL_TERIMA"]    = tglToSql($this->post('tgl_terima'));
        $param["STATUS_SJ"] = $this->post('status_sj');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["TGL_ESTIMASIKIRIM"] = tglToSql($this->post('tgl_estimasikirim'));
        $param["WAKTU_ESTIMASIKIRIM"]    = $this->post('waktu_estimasikirim');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_SJKELUAR_INSERT",$param,'post',TRUE);
    }

    public function sjkeluar_put(){
        $param = array();
        $param["NO_SURATJALAN"] = $this->put('no_suratjalan');
        $param["TGL_SURATJALAN"]   = tglToSql($this->put('tgl_suratjalan'));
        $param["KD_MAINDEALER"] = $this->put('kd_maindealer');
        $param["KD_DEALER"] = $this->put('kd_dealer');
        $param["KD_GUDANG"] = $this->put('kd_gudang');
        $param["NO_REFF"]   = $this->put('no_reff');
        $param["KD_CUSTOMER"]  = $this->put('kd_customer');
        $param["ALAMAT_KIRIM"]  = $this->put('alamat_kirim');
        $param["TGL_KIRIM"] = tglToSql($this->put('tgl_kirim'));
        $param["NAMA_PENGIRIM"] = $this->put('nama_pengirim');
        $param["NAMA_EKSPEDISI"]  = $this->put('nama_ekspedisi');
        $param["NO_MOBIL"]  = $this->put('no_mobil');
        $param["NAMA_SOPIR"]    = $this->put('nama_sopir');
        $param["NAMA_PENERIMA"] = $this->put('nama_penerima');
        $param["TGL_TERIMA"]    = tglToSql($this->put('tgl_terima'));
        $param["STATUS_SJ"] = $this->put('status_sj');
        $param["KETERANGAN"]    = $this->put('keterangan');$param["TGL_ESTIMASIKIRIM"] = tglToSql($this->put('tgl_estimasikirim'));
        $param["WAKTU_ESTIMASIKIRIM"]    = $this->put('waktu_estimasikirim');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SJKELUAR_UPDATE",$param,'put',TRUE);
    }

    /**
     * [trans_sjkeluar_delete description]
     * @return [type] [description]
     */
    public function sjkeluar_delete(){
        $param = array();
        $param["NO_SURATJALAN"] = $this->delete('no_suratjalan');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SJKELUAR_DELETE",$param,'delete',TRUE);
    }


    /**
    * Surat Jalan Keluar Detail
    */
    public function sjkeluar_detail_get(){
        $param = array();$search='';
        if($this->get("id_suratjalan")){
            $param["ID_SURATJALAN"]      = $this->get("id_suratjalan");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]   = $this->get("no_rangka");
        }
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "NAMA_ITEM"     => $this->get("keyword"),
                "ID_SURATJALAN"     => $this->get("keyword"),
                "NO_MESIN"   => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SJKELUAR_DETAIL",$param);
    }

    public function sjkeluar_detail_post(){
        $param = array();
        $param["ID_SURATJALAN"] = $this->post('id_suratjalan');
        $param["NO_MESIN"] = $this->post('no_mesin');
        $param["NO_RANGKA"]  = $this->post('no_rangka');
        $param["NAMA_ITEM"]   = $this->post('nama_item');
        $this->Main_model->data_sudahada($param,"TRANS_SJKELUAR_DETAIL");
        $param["KD_WARNA"] = $this->post('kd_warna');
        $param["JUMLAH"]    = $this->post('jumlah');
        $param["KET_UNIT"] = $this->post('ket_unit');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_SJKELUAR_DETAIL_INSERT",$param,'post',TRUE);
    }

    public function sjkeluar_detail_put(){
        $param = array();
        $param["ID_SURATJALAN"] = $this->put('id_suratjalan');
        $param["NAMA_ITEM"]   = $this->put('nama_item');
        $param["KD_WARNA"] = $this->put('kd_warna');
        $param["NO_MESIN"] = $this->put('no_mesin');
        $param["NO_RANGKA"]  = $this->put('no_rangka');
        $param["JUMLAH"]    = $this->put('jumlah');
        $param["KET_UNIT"] = $this->put('ket_unit');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SJKELUAR_UPDATE",$param,'put',TRUE);
    }

    /**
     * [trans_sjkeluar_detail_delete description]
     * @return [type] [description]
     */
    public function sjkeluar_detail_delete(){
        $param = array();
        $param["ID_SURATJALAN"] = $this->delete('id_suratjalan');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SJKELUAR_DETAIL_DELETE",$param,'delete',TRUE);
    }

    public function sjkeluar_status_put()
    {
        $param = array();
        $param["NO_SURATJALAN"] = $this->put('no_suratjalan');
        $param["TGL_TERIMA"]    = tglToSql($this->put('tgl_terima'));
        $param["STATUS_SJ"] = $this->put('status_sj');
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["BUKTI_TERIMA"]    = $this->put('bukti_terima');
        $param["WAKTU_TERIMA"]    = $this->put('waktu_terima');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SJKELUAR_STATUS_UPDATE",$param,'put',TRUE);

    }

    /**
     * [hargamotor_get description]
     * @return [type] [description]
     */
    public function hargamotor_get(){
        $param = array();$search='';
        if($this->get("kd_item")){
            $param["KD_ITEM"] = $this->get("kd_item");
        }
        if($this->get("nama_item")){
            $param["NAMA_ITEM"] = $this->get("nama_item");
        }
        if($this->get("kd_wilayah")){
            $param["KD_WILAYAH"] = $this->get("kd_wilayah");
        }
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "NAMA_ITEM"     => $this->get("keyword"),
                "KD_ITEM"     => $this->get("keyword"),
                "KD_WILAYAH"  => $this->get("keyword"),
                "HARGA" => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_HARGAMOTOR",$param);
    }
    /**
     * [hargamotor_post description]
     * @return [type] [description]
     */
    public function hargamotor_post(){
        set_time_limit(0);
        $param=array();
        $param["KD_WILAYAH"]    = $this->post('kd_wilayah');
        $param["KD_ITEM"]       = $this->post('kd_item');
        $param["KD_CATEGORY"]   = $this->post('kd_category');
        $this->Main_model->data_sudahada($param,"SETUP_HARGAMOTOR");
        $param["NAMA_ITEM"]     = $this->post('nama_item');
        $param["HARGA"]         = $this->post('harga');
        $param["HARGA_OTR"]     = $this->post('harga_otr');
        $param["BBN"]           = $this->post('bbn');
        $param["HARGA_DEALER"]  = $this->post('harga_dealer');
        $param["HARGA_DEALERD"] = $this->post('harga_dealerd');
        $param["PPH_RK"]        = $this->post('pph_dlrk');
        $param["PPH_RK2"]       = $this->post('pph_dlrk2');
        $param["BIAYA_ADM"]     = $this->post('biaya_adm');
        $param["BIAYA_LAIN"]    = $this->post('biaya_lain');
        $param["BARANG"]     = $this->post('aksesoris');
        $param["TGL_UPDATE"]    = tglToSql($this->post('tgl_update'));
        $param["CREATED_BY"]    = $this->post('created_by');
        /*var_dump($param);
        exit();*/
        $this->resultdata("SP_SETUP_HARGAMOTOR_INSERT",$param,'post',TRUE);
    }
    /**
     * [hargamotor_put description]
     * @return [type] [description]
     */
    public function hargamotor_put(){
        $param=array();
        //$param["ID"]            = $this->put('id');
        $param["KD_WILAYAH"]    = $this->put('kd_wilayah');
        $param["KD_ITEM"]       = $this->put('kd_item');
        $param["KD_CATEGORY"]   = $this->put('kd_category');
        $param["NAMA_ITEM"]     = $this->put('nama_item');
        $param["HARGA"]         = $this->put('harga');
        $param["HARGA_OTR"]     = $this->put('harga_otr');
        $param["BBN"]           = $this->put('bbn');
        $param["HARGA_DEALER"]  = $this->put('harga_dealer');
        $param["HARGA_DEALERD"] = $this->put('harga_dealerd');
        $param["PPH_RK"]        = $this->put('pph_dlrk');
        $param["PPH_RK2"]       = $this->put('pph_dlrk2');
        $param["BIAYA_ADM"]     = $this->put('biaya_adm');
        $param["BIAYA_LAIN"]    = $this->put('biaya_lain');
        $param["BARANG"]        = $this->put('aksesoris');
        $param["TGL_UPDATE"]    = date('Ymd');
        $param["LASTMODIFIED_BY"]= $this->put('lastmodified_by');
        $this->resultdata("SP_SETUP_HARGAMOTOR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [hargamotor_delete description]
     * @return [type] [description]
     */
    public function hargamotor_delete(){
        $param=array();
        $param["ID"]             = $this->delete('id');
        $param["LASTMODIFIED_BY"]= $this->delete('kd_item');
        $this->resultdata("SP_SETUP_HARGAMOTOR_DELETE",$param,'delete',TRUE);
    }



    /**
     * Penerimaan motor
     */
    public function terimamotor_get(){
        $param = array();$search='';
        if($this->get('kd_dealer')){
            $param["KD_DEALER"] = $this->get('kd_dealer');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"] = $this->get('kd_maindealer');
        }
        if($this->get("no_terimasjm")){
            $param["NO_TERIMASJM"] = $this->get("no_terimasjm");
        }
        if($this->get("no_sjmasuk")){
            $param["NO_SJMASUK"] = $this->get("no_sjmasuk");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"] = $this->get("no_mesin");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"] = $this->get("no_rangka");
        }
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "NO_TERIMASJM"     => $this->get("keyword"),
                "NO_SJMASUK"  => $this->get("keyword"),
                "NO_MESIN"  => $this->get("keyword"),
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
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_TERIMASJMOTOR",$param);
    }
    /**
     * [terimamotor_post description]
     * @return [type] [description]
     */
    public function terimamotor_post(){
        set_time_limit(0);
        $param=array();
        $param["NO_TERIMASJM"]    = $this->post('no_terimasjm');
        $param["NO_SJMASUK"]       = $this->post('no_sjmasuk');
        $param["KD_MAINDEALER"]   = $this->post('kd_maindealer');
        $param["NO_RANGKA"]   = $this->post('no_rangka');
        $param["NO_MESIN"]   = $this->post('no_mesin');
        $this->Main_model->data_sudahada($param,"TRANS_TERIMASJMOTOR");
        $param["KD_DEALER"]   = $this->post('kd_dealer');
        $param["KD_ITEM"]   = $this->post('kd_item');
        $param["JUMLAH"]   = $this->post('jumlah');
        $param["KSU"]   = $this->post('ksu');
        $param["EXPEDISI"]   = $this->post('expedisi');
        $param["NOPOL"]   = $this->post('nopol');
        $param["STOCK_STATUS"]   = $this->post('stock_status');
        $param["KD_GUDANG"]   = $this->post('kd_gudang');
        $param["KETERANGAN_NRFS"]   = $this->post('keterangan_nrfs');
        $param["CREATED_BY"]    = $this->post('created_by');
        /*var_dump($param);
        exit();*/
        $this->resultdata("SP_TRANS_TERIMASJMOTOR_INSERT",$param,'post',TRUE);
    }
    /**
     * [terimamotor_put description]
     * @return [type] [description]
     */
    public function terimamotor_put(){
        $param=array();
        $param["ID"]    = $this->put('id');
        $param["NO_TERIMASJM"]    = $this->put('no_terimasjm');
        $param["NO_SJMASUK"]       = $this->put('no_sjmasuk');
        $param["KD_MAINDEALER"]   = $this->put('kd_maindealer');
        $param["KD_DEALER"]   = $this->put('kd_dealer');
        $param["KD_ITEM"]   = $this->put('kd_item');
        $param["NO_RANGKA"]   = $this->put('no_rangka');
        $param["NO_MESIN"]   = $this->put('no_mesin');
        $param["JUMLAH"]   = $this->put('jumlah');
        $param["KSU"]   = $this->put('ksu');
        $param["EXPEDISI"]   = $this->put('expedisi');
        $param["NOPOL"]   = $this->put('nopol');
        $param["STOCK_STATUS"]   = $this->put('stock_status');
        $param["KD_GUDANG"]   = $this->put('kd_gudang');
        $param["KETERANGAN_NRFS"]   = $this->put('keterangan_nrfs');
        $param["LASTMODIFIED_BY"]= $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_TERIMASJMOTOR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [terimamotor_delete description]
     * @return [type] [description]
     */
    public function terimamotor_delete(){
        $param=array();
        $param["ID"]    = $this->delete('id');
        $param["LASTMODIFIED_BY"]= $this->delete('lastmodified_by');
        $this->resultdata("SP_TRANS_TERIMASJMOTOR_DELETE",$param,'delete',TRUE);
    }

    
    /**
     * [sj_terimamotor_put description]
     * @return [type] [description]
     */
    public function sj_terimamotor_put(){
        $param=array();
        $param["ID"]                = $this->put('id');
        //$param["NO_RANGKA"]    = $this->put('no_rangka');
        //$param["NO_SJMASUK"]    = $this->put('no_sjmasuk');
        $param["STATUS_SJ"]         = $this->put('status_sj');
        $param["LASTMODIFIED_BY"]   = $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_SJ_TERIMASJMOTOR_UPDATE",$param,'put',TRUE);
 
    }
    /**
     * [mutasi_terimamotor_put description]
     * @return [type] [description]
     */
    public function mutasi_terimamotor_put(){
        $param=array();
        $param["ID"]                = $this->put('id');
        //$param["NO_RANGKA"]    = $this->put('no_rangka');
        //$param["NO_SJMASUK"]    = $this->put('no_sjmasuk');
        $param["STATUS_MUTASI"]     = $this->put('status_mutasi');
        $param["LASTMODIFIED_BY"]   = $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_INV_MUTASI_STATUS_UPDATE",$param,'put',TRUE);
 
    }

    /**
     * [ksu_get description]
     * @return [type] [description]
     */
    public function ksu_get(){
        $param = array();$search='';
        if($this->get("kd_ksu")){
            $param["KD_KSU"] = $this->get("kd_ksu");
        }
        if($this->get("nama_ksu")){
            $param["NAMA_KSU"] = $this->get("nama_ksu");
        }
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_KSU"     => $this->get("keyword"),
                "NAMA_KSU"  => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_KSU",$param);
    }
    /**
     * [ksu_post description]
     * @return [type] [description]
     */
    public function ksu_post(){
        set_time_limit(0);
        $param=array();
        $param["KD_KSU"]    = $this->post('kd_ksu');
        $this->Main_model->data_sudahada($param,"MASTER_KSU");
        $param["NAMA_KSU"]       = $this->post('nama_ksu');
        $param["JUMLAH"]   = $this->post('jumlah');
        
        $param["CREATED_BY"]    = $this->post('created_by');
        /*var_dump($param);
        exit();*/
        $this->resultdata("SP_MASTER_KSU_INSERT",$param,'post',TRUE);
    }
    /**
     * [ksu_put description]
     * @return [type] [description]
     */
    public function ksu_put(){
        $param=array();
        $param["KD_KSU"]    = $this->put('kd_ksu');
        $param["NAMA_KSU"]       = $this->put('nama_ksu');
        $param["JUMLAH"]   = $this->put('jumlah');
        $param["ROW_STATUS"]   = $this->put('row_status');
        $param["LASTMODIFIED_BY"]= $this->put('lastmodified_by');
        $this->resultdata("SP_MASTER_KSU_UPDATE",$param,'put',TRUE);
    }
    /**
     * [ksu_delete description]
     * @return [type] [description]
     */
    public function ksu_delete(){
        $param=array();
        $param["KD_KSU"]    = $this->delete('kd_ksu');
        $param["LASTMODIFIED_BY"]= $this->delete('lastmodified_by');
        $this->resultdata("SP_MASTER_KSU_DELETE",$param,'delete',TRUE);
    }


    /**
     * [trans_ksu_get description]
     * @return [type] [description]
     */
    public function trans_ksu_get(){
        $param = array();$search='';
        if($this->get("kd_ksu")){
            $param["KD_KSU"] = $this->get("kd_ksu");
        }
        
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_KSU"     => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_KSU",$param);
    }
    /**
     * [trans_ksu_post description]
     * @return [type] [description]
     */
    public function trans_ksu_post(){
        set_time_limit(0);
        $param=array();
        $param["KD_KSU"]    = $this->post('kd_ksu');
        $this->Main_model->data_sudahada($param,"TRANS_KSU");
        $param["ID_TERIMASJMASUK"]       = $this->post('id_terimasjmasuk');
        $param["JUMLAH"]   = $this->post('jumlah');
        
        $param["CREATED_BY"]    = $this->post('created_by');
        /*var_dump($param);
        exit();*/
        $this->resultdata("SP_TRANS_KSU_INSERT",$param,'post',TRUE);
    }
    /**
     * [ksu_put description]
     * @return [type] [description]
     */
    public function trans_ksu_put(){
        $param=array();
        $param["KD_KSU"]            = $this->put('kd_ksu');
        $param["ID_TERIMASJMASUK"]  = $this->put('id_terimasjmasuk');
        $param["JUMLAH"]            = $this->put('jumlah');
        $param["LASTMODIFIED_BY"]   = $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_KSU_UPDATE",$param,'put',TRUE);
    }
    /**
     * [trans_ksu_delete description]
     * @return [type] [description]
     */
    public function trans_ksu_delete(){
        $param=array();
        $param["KD_KSU"]    = $this->delete('kd_ksu');
        $param["LASTMODIFIED_BY"]= $this->delete('lastmodified_by');
        $this->resultdata("SP_TRANS_KSU_DELETE",$param,'delete',TRUE);
    }

    /**
     * [trans_sum_get description]
     * @return [type] [description]
     */
    public function trans_sum_get(){
        $param = array();$search='';
        if($this->get("kd_sum")){
            $param["KD_SUM"] = $this->get("kd_sum");
        }
        if($this->get("jenis_stok")){
            $param["JENIS_STOK"] = $this->get("jenis_stok");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"] = $this->get("kd_gudang");
        }

        
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_SUM"     => $this->get("keyword"),
                "JENIS_STOK"     => $this->get("keyword"),
                "KD_GUDANG"     => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SUM",$param);
    }
    /**
     * [trans_sum_post description]
     * @return [type] [description]
     */
    public function trans_sum_post(){
        $param=array();
        $param["KD_SUM"]    = $this->post('kd_sum');
        $param["NO_RANGKA"]   = $this->post('no_rangka');
        $param["NO_MESIN"]       = $this->post('no_mesin');
        $this->Main_model->data_sudahada($param,"TRANS_SUM");
        $param["JENIS_STOK"]    = $this->post('jenis_stok');
        $param["KD_GUDANG"]       = $this->post('kd_gudang');
        $param["KD_TYPEMOTOR"]   = $this->post('kd_typemotor');
        $param["KD_WARNA"]       = $this->post('kd_warna');
        $param["TGL_KELUAR"]   = tglToSql($this->post('tgl_keluar'));
        $param["TGL_RECEIVE"]   = tglToSql($this->post('tgl_receive'));
        $param["KETERANGAN"]       = $this->post('keterangan');
        $param["CREATED_BY"]    = $this->post('created_by');
        /*var_dump($param);
        exit();*/
        $this->resultdata("SP_TRANS_SUM_INSERT",$param,'post',TRUE);
    }
    /**
     * [trans_sum_put description]
     * @return [type] [description]
     */
    public function trans_sum_put(){
        $param=array();
        $param["KD_SUM"]    = $this->put('kd_sum');
        $param["JENIS_STOK"]    = $this->put('jenis_stok');
        $param["KD_GUDANG"]       = $this->put('kd_gudang');
        $param["KD_TYPEMOTOR"]   = $this->put('kd_typemotor');
        $param["KD_WARNA"]       = $this->put('kd_warna');
        $param["NO_RANGKA"]   = $this->put('no_rangka');
        $param["NO_MESIN"]       = $this->put('no_mesin');
        $param["TGL_KELUAR"]   = tglToSql($this->put('tgl_keluar'));
        $param["TGL_RECEIVE"]   = tglToSql($this->put('tgl_receive'));
        $param["KETERANGAN"]       = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]= $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_SUM_UPDATE",$param,'put',TRUE);
    }
    /**
     * [trans_sum_delete description]
     * @return [type] [description]
     */
    public function trans_sum_delete(){
        $param=array();
        $param["KD_SUM"]    = $this->delete('kd_sum');
        $param["LASTMODIFIED_BY"]= $this->delete('lastmodified_by');
        $this->resultdata("SP_TRANS_SUM_DELETE",$param,'delete',TRUE);
    }

    /**
     * [trans_history_hargamotor_get description]
     * @return [type] [description]
     */
    public function trans_history_hargamotor_get(){
        $param = array();$search='';
        if($this->get("nama_item")){
            $param["NAMA_ITEM"] = $this->get("nama_item");
        }
        
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "NAMA_ITEM"     => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_HISTORY_HARGAMOTOR",$param);
    }
    /**
     * [barang_get description]
     * @return [type] [description]
     */
    public function barang_get(){
        $param = array();$search='';
        if($this->get("kd_barang")){
            $param["KD_BARANG"]   = $this->get("kd_barang");
        }
        if($this->get("nama_barang")){
            $param["NAMA_BARANG"] = $this->get("nama_barang");
        }
        if($this->get("kategori")){
            $param["KATEGORI"] = $this->get("kategori");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_BARANG"       => $this->get("keyword"),
                "NAMA_BARANG"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if($this->get("orderby")){
            $this->Main_model->set_orderby($this->get("orderby"));
        }else{
            $this->Main_model->set_orderby("NAMA_BARANG");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_BARANG",$param);
    }
    
    /**
     * [barang_post description]
     * @return [type] [description]
     */
    public function barang_post(){
        $param = array();
        $param["NAMA_BARANG"] = $this->post("nama_barang");
        $param["KATEGORI"] = $this->post("kategori");
        $this->Main_model->data_sudahada($param,"MASTER_BARANG");
        $param["KD_BARANG"]   = $this->post("kd_barang");
        $param["DEFAULT_QTY"] = $this->post("default_qty");
        $param["MASUK_SJ"] = $this->post("masuk_sj");
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_MASTER_BARANG_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [barang_put description]
     * @return [type] [description]
     */
    public function barang_put(){
        $param = array();
        $param["ID"]   = $this->put("id");
        $param["KD_BARANG"]   = $this->put("kd_barang");
        $param["NAMA_BARANG"] = $this->put("nama_barang");
        $param["KATEGORI"] = $this->put("kategori");
        $param["DEFAULT_QTY"] = $this->put("default_qty");
        $param["MASUK_SJ"]     = $this->put("masuk_sj");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_MASTER_BARANG_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [barang_delete description]
     * @return [type] [description]
     */
    public function barang_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_BARANG_DELETE",$param,'delete',TRUE);
    }
 
    /**
     * [apparel_get description]
     * @return [type] [description]
     */
    public function apparel_get(){
        $param = array();$search='';
        if($this->get("kd_apparel")){
            $param["KD_APPAREL"]   = $this->get("kd_apparel");
        }
        if($this->get("nama_apparel")){
            $param["NAMA_APPAREL"] = $this->get("nama_apparel");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_APPAREL"       => $this->get("keyword"),
                "NAMA_APPAREL"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if($this->get("orderby")){
            $this->Main_model->set_orderby($this->get("orderby"));
        }else{
            $this->Main_model->set_orderby("NAMA_APPAREL");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_APPAREL",$param);
    }
    
    /**
     * [apparel_post description]
     * @return [type] [description]
     */
    public function apparel_post(){
        $param = array();
        $param["KD_APPAREL"]   = $this->post("kd_apparel");
        $this->Main_model->data_sudahada($param,"MASTER_APPAREL");
        $param["NAMA_APPAREL"] = $this->post("nama_apparel");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_MASTER_APPAREL_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [apparel_put description]
     * @return [type] [description]
     */
    public function apparel_put(){
        $param = array();
        $param["KD_APPAREL"]   = $this->put("kd_apparel");
        $param["NAMA_APPAREL"] = $this->put("nama_apparel");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_MASTER_APPAREL_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [apparel_delete description]
     * @return [type] [description]
     */
    public function apparel_delete(){
        $param = array();
        $param["KD_APPAREL"]     = $this->delete('kd_apparel');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_APPAREL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [barang_summary_get description]
     * @return [type] [description]
     */
    public function barang_summary_get(){
        $param = array();$search='';
        if($this->get("id_part")){
            $param["ID_PART"]   = $this->get("id_part");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "ID_PART"       => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_BARANG_SUMMARY",$param);
    }
    
    /**
     * [barang_summary_post description]
     * @return [type] [description]
     */
    public function barang_summary_post(){
        $param = array();
        $param["ID_PART"]   = $this->post("id_part");
        $this->Main_model->data_sudahada($param,"MASTER_BARANG_SUMMARY");
        $param["HARGA_BELI"] = $this->post("harga_beli");
        $param["HARGA_JUAL"] = $this->post("harga_jual");
        $param["DISKON"] = $this->post("diskon");
        $param["STOCK"] = $this->post("stock");
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_MASTER_BARANG_SUMMARY_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [barang_summary_put description]
     * @return [type] [description]
     */
    public function barang_summary_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["ID_PART"]   = $this->put("id_part");
        $param["HARGA_BELI"] = $this->put("harga_beli");
        $param["HARGA_JUAL"] = $this->put("harga_jual");
        $param["DISKON"] = $this->put("diskon");
        $param["STOCK"] = $this->put("stock");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_MASTER_BARANG_SUMMARY_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [barang_summary_delete description]
     * @return [type] [description]
     */
    public function barang_summary_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_BARANG_SUMMARY_DELETE",$param,'delete',TRUE);
    }
    
    /**
     * [part_saldo_get description]
     * @return [type] [description]
     */
    public function part_saldo_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]   = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]   = $this->get("part_number");
        }
        if($this->get("tahun")){
            $param["TAHUN"]   = $this->get("tahun");
        }
        if($this->get("bulan")){
            $param["BULAN"]   = $this->get("bulan");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_MAINDEALER"       => $this->get("keyword"),
                "KD_DEALER"       => $this->get("keyword"),
                "PART_NUMBER"       => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PART_SALDO",$param);
    }
    
    /**
     * [part_saldo_post description]
     * @return [type] [description]
     */
    public function part_saldo_post(){
        $param = array();
        $param["KD_MAINDEALER"]   = $this->post("kd_maindealer");
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["PART_NUMBER"] = $this->post("part_number");
        $param["TAHUN"] = $this->post("tahun");
        $param["BULAN"] = $this->post("bulan");
        $this->Main_model->data_sudahada($param,"TRANS_PART_SALDO");
        $param["JUMLAH_SAK"] = $this->post("jumlah_sak");
        $param["HARGA_SAK"] = $this->post("harga_sak");
        $param["POSTING_STATUS"] = $this->post("posting_status");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_TRANS_PART_SALDO_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [part_saldo_put description]
     * @return [type] [description]
     */
    public function part_saldo_put(){
        $param = array();
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["PART_NUMBER"] = $this->put("part_number");
        $param["TAHUN"] = $this->put("tahun");
        $param["BULAN"] = $this->put("bulan");
        $param["JUMLAH_SAK"] = $this->put("jumlah_sak");
        $param["HARGA_SAK"] = $this->put("harga_sak");
        $param["POSTING_STATUS"]     = $this->put("posting_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_PART_SALDO_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [part_saldo_delete description]
     * @return [type] [description]
     */
    public function part_saldo_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_SALDO_DELETE",$param,'delete',TRUE);
    }
    /**
     * [part_stock description]
     * @return [type] [description]
     */
    public function part_stock_get(){
        $param=array();$search='';$paramsa=array();$paramx=array();
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
            $paramsa["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
            $paramsa["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");

        }
        if($this->get("customs")){
            $paramx["customs"]     = $this->get("customs");
        }
        if($this->get("tahun")){
            $param["TAHUN"]     = $this->get("tahun");

        }
        if($this->get("bulan")){
            $param["BULAN"]     = $this->get("bulan");
            $paramsa["TAHUN"]   =((int)$this->get("bulan")<12)?$this->get('tahun'):((int)$this->get("tahun"))-1;
            $paramsa["BULAN"]   =((int)$this->get("bulan")<12)?((int)$this->get("bulan"))-1:"01";
        }
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_MAINDEALER" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "PART_NUMBER" =>$this->get("keyword")
            );
        }
        //menggunakan custom query
        // $this->Custom_model->set_where_custom($paramx);
        // $query = $this->Custom_model->part_stock($param);
        //echo $query;exit();
        if($query){
            $this->Main_model->set_custom_query($query);
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
        $this->resultdata("TRANS_PARTSTOCK_VIEW",$param);
    }
    function parts_generate_get(){
        $param=array();$query="";
        $this->Custom_model->set_logines($this->get("user_login"));
        $kd_dealer=$this->get("kd_dealer");
        $jn=($this->get("jenis_data"))?$this->get("jenis_data"):"ALL";
        $tgl=($this->get("tgl_trans"))?tglToSql($this->get("tgl_trans")):date('Ymd');
        //$query=$this->Custom_model->part_stocked($kd_dealer,$jn,$tgl);
        //echo $query;exit();
        //$this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_PART_SALDO",$param,false);
    }
    function part_deltmp_get(){
        $param=array();$query="";
        $this->Custom_model->set_logines($this->get("user_login"));
        $query=$this->Custom_model->deltmp_table($this->get("kd_dealer"));
        $this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_PART_SALDO",$param);
    }
    function part_dellead_get(){
        $param=array();$query="";
        $this->Custom_model->set_logines($this->get("user_login"));
        $query=$this->Custom_model->dellead_table();
        $this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_PART_SALDO",$param);
    }
    function part_leadtime_get($generate=null){
        $param=array();$query="";$so=null;
        $rand=substr($this->get("user_login"),0,10);
        $this->Custom_model->set_logines($this->get("user_login"));

        if($this->get('urutan')){
            $param["URUTAN"] = $this->get('urutan');
        }
        if($this->get("no_po")){
            $param["NO_PO"]     = $this->get("no_po");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("so")){
            $so="true";
        }
        if($generate==true){
            $kd_dealer  =$this->get("kd_dealer");
            $bulan      =$this->get("bulan");
            $tahun      =$this->get("tahun");
            $query=$this->Custom_model->part_leadtime($kd_dealer,$tahun,$bulan,$so);
            $this->Main_model->set_custom_query($query);
            $this->resultdata("TRANS_PARTSO",$param);
        }else{
            $custom="";
            if($this->get("custom")){
                $this->Main_model->set_customcriteria($this->get("custom"));
            }
            $this->Main_model->set_groupby($this->get("groupby"));
            $this->Main_model->set_orderby($this->get("orderby"));
            $this->Main_model->set_selectfield($this->get('field'));
            $this->resultdata("PART_LEADTIME_$rand",$param);
        }
    }
    function part_ots_get($generate=null){
        $param=array();$query="";$so=null;
        $rand=substr($this->get("user_login"),0,10);
        $this->Custom_model->set_logines($this->get("user_login"));

        if($this->get('tipe_rpt')){
            $param["TIPE_RPT"] = $this->get('tipe_rpt');
        }
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        
        if($generate==true){
            $kd_dealer  =$this->get("kd_dealer");
            $bulan      =$this->get("bulan");
            $tahun      =$this->get("tahun");
            $query=$this->Custom_model->part_outstanding($kd_dealer,$tahun,$bulan);
            $this->Main_model->set_custom_query($query);
            $this->resultdata("TRANS_PARTSO",$param);
        }else{
            $custom="";
            if($this->get("custom")){
                $this->Main_model->set_customcriteria($this->get("custom"));
            }
            $this->Main_model->set_groupby($this->get("groupby"));
            $this->Main_model->set_orderby($this->get("orderby"));
            $this->Main_model->set_selectfield($this->get('field'));
            $this->resultdata("PART_OTS_$rand",$param);
        }
    }
    function part_delots_get(){
        $param=array();$query="";
        $this->Custom_model->set_logines($this->get("user_login"));
        $query=$this->Custom_model->dellots_table();
        $this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_PART_SALDO",$param);
    }
    function parts_view_get($tipe_data=null){
        $param=array();$search='';
        $rand=strtoupper($this->get("kd_dealer")."_".substr($this->get("user_login"),0,10));
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kd_rakbin")){
            $param["KD_RAKBIN"]     = $this->get("kd_rakbin");
        }
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"]     = $this->get("kd_lokasi");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }
        if($this->get("kd_groupsales")){
            $param["KD_GROUPSALES"]     = $this->get("kd_groupsales");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");

        }
        if($this->get("keyword")){
            // $param=array();
            $search=array(
                "PART_NUMBER"       => $this->get("keyword"),
                "PART_DESKRIPSI"       => $this->get("keyword")
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
        // $this->resultdata("STOCK_VIEWS_$rand",$param);
        $this->resultdata("TRANS_PARTSTOCK_VIEW",$param);
    }
    /**
     * [barang_stock_get description]
     * @return [type] [description]
     */
    public function barang_stock_get($trans=null){
        $param=array();$search='';$paramsa=array();
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
            $paramsa["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
            $paramsa["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("tahun")){
            $param["TAHUN"]     = $this->get("tahun");

        }
        if($this->get("bulan")){
            $param["BULAN"]     = $this->get("bulan");
            $paramsa["TAHUN"]   =((int)$this->get("bulan")<12)?$this->get('tahun'):((int)$this->get("tahun"))-1;
            $paramsa["BULAN"]   =((int)$this->get("bulan")<12)?((int)$this->get("bulan"))-1:"01";
        }
        if(strlen(trim($this->get("keyword")))>0){
            $param=array();
            $search= array(
                "KD_MAINDEALER" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "PART_NUMBER" =>$this->get("keyword")
            );
        }
        if($this->get("d_tgl")){
            $param["DARI_TANGGAL"] = tglToSql($this->get("d_tgl"));
            $param["SAMPAI_TANGGAL"] =($this->get("s_tgl"))?tglToSql($this->get("s_tgl")):date('Ymd');
        }
        if($this->get("j_trans")){
            $param["JENIS_TRANS"] = $this->get("j_trans");
        }
        if($this->get("detail")){
            $param["DETAIL"] = $this->get("detail");
        }
        //menggunakan custom query
        $query = $this->Custom_model->stock_barang($param,$trans);
        if($query){
            $this->Main_model->set_custom_query($query);
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
        $this->resultdata("TRANS_PART_SALDO",$param);
    }
    public function hargabeli_barang_get($list=null){
        $param=array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }

        $this->Main_model->set_custom_query($this->Custom_model->barang_hargabeli($param,$list));
       //echo $this->Main_model->get_custom_query();
        
        $this->resultdata("TRANS_PART_SALDO",$param=array());
    }
    public function barang_autopicking_get(){
        $param=array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"]     = $this->get("kd_lokasi");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("mode")){
            $param["DETAIL"]     = $this->get("mode");
        }
        $query =$this->Custom_model->barang_in_dounit($param);
        $this->Main_model->set_custom_query($query);

        $this->resultdata("PART_ETA",$param);
    }
    public function part_eta_get($list=null){
        $param=array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
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
        $this->resultdata("PART_ETA",$param);
    }

    /**
     * [partso_get description]
     * @return [type] [description]
     */
    public function partso_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]   = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("jenis_part")){
            $param["JENIS_PART"]   = $this->get("jenis_part");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]   = $this->get("no_trans");
        }
        if($this->get("so_status")!=''){
            $param["SO_STATUS"]   = $this->get("so_status");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_MAINDEALER"       => $this->get("keyword"),
                "KD_DEALER"       => $this->get("keyword"),
                "NO_TRANS"       => $this->get("keyword")
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
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PARTSO",$param);
    }
    
    /**
     * [partso_post description]
     * @return [type] [description]
     */
    public function partso_post(){
        $param = array();
        $param["KD_MAINDEALER"]   = $this->post("kd_maindealer");
        $param["KD_DEALER"]   = $this->post("kd_dealer");
        $param["NO_TRANS"]   = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_PARTSO");
        $param["TGL_TRANS"] = tglToSql($this->post("tgl_trans"));
        $param["TYPE_CUSTOMER"] = $this->post("type_customer");
        $param["JENIS_PART"] = $this->post("jenis_part");
        $param["KD_CUSTOMER"] = $this->post("kd_customer");
        $param["KD_TYPEMOTOR"] = $this->post("kd_typemotor");
        $param["TAHUN_MOTOR"] = $this->post("tahun_motor");
        $param["VOR"] = $this->post("vor");
        $param["JR"] = $this->post("jr");
        $param["BOOKING_ORDER"] = $this->post("booking_order");
        $param["ORDER_TO"] = $this->post("order_to");
        $param["SO_STATUS"] = $this->post("so_status");
        $param["KD_LOKASI"] = $this->post("kd_lokasi");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_TRANS_PARTSO_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [partso_put description]
     * @return [type] [description]
     */
    public function partso_put(){
        $param = array();
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["NO_TRANS"]   = $this->put("no_trans");
        $param["TGL_TRANS"] = tglToSql($this->put("tgl_trans"));
        $param["TYPE_CUSTOMER"] = $this->put("type_customer");
        $param["JENIS_PART"] = $this->put("jenis_part");
        $param["KD_CUSTOMER"] = $this->put("kd_customer");
        $param["KD_TYPEMOTOR"] = $this->put("kd_typemotor");
        $param["TAHUN_MOTOR"] = $this->put("tahun_motor");
        $param["VOR"] = $this->put("vor");
        $param["JR"] = $this->put("jr");
        $param["BOOKING_ORDER"] = $this->put("booking_order");
        $param["ORDER_TO"] = $this->put("order_to");
        $param["SO_STATUS"] = $this->put("so_status");
        $param["KD_LOKASI"] = $this->put("kd_lokasi");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_PARTSO_UPDATE",$param,'put',TRUE);
    }
    function partso_updpo_put(){
        $param=array();
        $query="UPDATE TRANS_PARTSO SET REFF_DOC ='".$this->put('nopo')."', LASTMODIFIED_BY='".$this->put("lastmodified_by")."',
                LASTMODIFIED_TIME=GETDATE() WHERE NO_TRANS='".$this->put("noso")."'";
        $this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_PARTSO",$param);
    }
    /**
     * [partso_delete description]
     * @return [type] [description]
     */
    public function partso_delete(){
        $param = array();
        $param["NO_TRANS"]     = $this->delete('no_trans');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PARTSO_DELETE",$param,'delete',TRUE);
    }

    /**
     * [partso_detail_get description]
     * @return [type] [description]
     */
    public function partso_detail_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]   = $this->get("no_trans");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]   = $this->get("part_number");
        }
        if($this->get("picking_status")){
            $param["PICKING_STATUS"]   = $this->get("picking_status");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"       => $this->get("keyword"),
                "PART_NUMBER"       => $this->get("keyword")
                
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PARTSO_DETAIL",$param);
    }
    
    /**
     * [partso_detail_post description]
     * @return [type] [description]
     */
    public function partso_detail_post(){
        $param = array();
        $param["NO_TRANS"]   = $this->post("no_trans");
        $param["PART_NUMBER"]   = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"TRANS_PARTSO_DETAIL");
        // $param["PART_BATCH"]   = $this->post("part_batch");
        $param["JUMLAH_ORDER"] = tglToSql($this->post("jumlah_order"));
        $param["HARGA_JUAL"] = $this->post("harga_jual");
        $param["ETA"] = $this->post("eta");
        $param["DISKON"] = $this->post("diskon");
        $param["STOCK_AWAL"] = $this->post("stock_awal");
        // $param["PICKING_STATUS"] = $this->post("picking_status");
        // $param["PICKING_REFF"] = $this->post("picking_reff");
        // $param["BILL_STATUS"] = $this->post("bill_status");
        // $param["BILL_REFF"] = $this->post("bill_reff");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_TRANS_PARTSO_DETAIL_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [partso_detail_put description]
     * @return [type] [description]
     */
    public function partso_detail_put(){
        $param = array();
        $param["NO_TRANS"]   = $this->put("no_trans");
        $param["PART_NUMBER"]   = $this->put("part_number");
        //$param["PART_BATCH"]   = $this->put("part_batch");
        $param["JUMLAH_ORDER"] = tglToSql($this->put("jumlah_order"));
        $param["HARGA_JUAL"] = $this->put("harga_jual");
        $param["ETA"] = $this->put("eta");
        //$param["HARGA_AVG"] = $this->put("harga_avg");
        $param["DISKON"] = $this->put("diskon");
        $param["STOCK_AWAL"] = $this->put("stock_awal");
        // $param["PICKING_STATUS"] = $this->put("picking_status");
        // $param["PICKING_REFF"] = $this->put("picking_reff");
        // $param["BILL_STATUS"] = $this->put("bill_status");
        // $param["BILL_REFF"] = $this->put("bill_reff");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_PARTSO_DETAIL_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [partso_detail_delete description]
     * @return [type] [description]
     */
    public function partso_detail_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PARTSO_DETAIL_DELETE",$param,'delete',TRUE);
    }
    /**
     * [so_status_put description]
     * @return [type] [description]
     */
    public function so_status_put(){
        $param = array();
        $param["NO_TRANS"]         = $this->put('no_trans');
        $param["SO_STATUS"]         = $this->put('so_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PARTSO_STATUS_UPDATE",$param,'put',TRUE);
    }

    /**
     * [so_picking_detail_put description]
     * @return [type] [description]
     */
    public function so_picking_detail_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["PICKING_STATUS"]         = $this->put('picking_status');
        $param["PICKING_REFF"]         = $this->put('picking_reff');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PARTSO_PICKING_DETAIL_UPDATE",$param,'put',TRUE);
    }
    public function so_paybill_put(){
        $param = array();
        $param["NO_TRANS"]        = $this->put('no_trans');
        $param["PICKING_STATUS"]  = $this->put('picking_status');
        $param["BILL_REFF"]       = $this->put('bill_reff');
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $param["JENIS_TRANS"]     = $this->put("jenis");
        $this->resultdata("SP_TRANS_PARTSO_PAYBILL_UPDATE",$param,'put',TRUE);
    }
    public function do_paybill_put(){
        $param = array();
        $param["NO_TRANS"]        = $this->put('no_trans');
        // $param["PICKING_STATUS"]  = $this->put('picking_status');
        $param["BILL_REFF"]       = $this->put('bill_reff');
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SJKELUAR_PAYBILL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [inv_mutasi_get description]
     * @return [type] [description]
     */
    public function inv_mutasi_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]   = $this->get("no_trans");
        }
        if($this->get("tipe_trans")){
            $param["TIPE_TRANS"]   = $this->get("tipe_trans");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]   = $this->get("part_number");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]   = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("jenis_trans")){
            $param["JENIS_TRANS"]   = $this->get("jenis_trans");
        }
        if($this->get("tipe_trans")){
            $param["TIPE_TRANS"]   = $this->get("tipe_trans");
        }
        if($this->get("kd_dealer_tujuan")){
            $param["KD_DEALER_TUJUAN"] = $this->get("kd_dealer_tujuan");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"       => $this->get("keyword"),
                "PART_NUMBER"       => $this->get("keyword")                
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_INV_MUTASI",$param);
    }

    public function mutasi_history_get(){
        $param = array();$search='';
        $kd_dealer =$this->get("kd_dealer");
        $no_rangka =($this->get("no_rangka"));
        
        $this->Main_model->set_custom_query($this->Custom_model->mutasi_history($no_rangka,$kd_dealer));
        $this->resultdata("TRANS_INV_MUTASI",$param=array());
    }
    
    /**
     * [inv_mutasi_post description]
     * @return [type] [description]
     */
    public function inv_mutasi_post(){
        $param = array();
        // $this->Main_model->data_sudahada($param,"TRANS_INV_MUTASI");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["TIPE_TRANS"]        = $this->post("tipe_trans");
        $param["JENIS_TRANS"]       = $this->post("jenis_trans");
        $param["KD_GUDANG_ASAL"]    = $this->post("kd_gudang_asal");
        $param["PART_NUMBER"]       = $this->post("part_number");
        $param["RAKBIN_ASAL"]       = $this->post("rakbin_asal");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["KD_DEALER_TUJUAN"]  = $this->post("kd_dealer_tujuan");
        $param["KD_GUDANG_TUJUAN"]  = $this->post("kd_gudang_tujuan");
        $param["RAKBIN_TUJUAN"]     = $this->post("rakbin_tujuan");
        $param["JUMLAH"]            = $this->post("jumlah");
        $param["HARGA_BELI"]        = ($this->post("harga_beli"))?$this->post("harga_beli"):"0";
        $param["HET"]               = ($this->post("het"))?$this->post("het"):"0";
        /*$param["APPROVAL_STATUS"]   = $this->post("approval_status");
        $param["APPROVAL_BY"]       = $this->post("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->post("approval_date"));*/
        $param["STATUS_MUTASI"]     = $this->post("status_mutasi");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->Main_model->set_totalrecord('0');
       
 
        $this->resultdata("SP_TRANS_INV_MUTASI_INSERT",$param,'post',TRUE);
        
    }

    function update_status_nrfs_get(){
        $param = array();
        
        $no_mesin = $this->get("part_number");        
        $kd_status_tujuan = $this->get("kd_status_tujuan");        
        $modify_by = $this->get("lastmodified_by");        
        $this->Main_model->set_custom_query($this->Custom_model->update_NRFS($no_mesin,$kd_status_tujuan,$modify_by));
        
        $this->resultdata("TRANS_INV_MUTASI",$param,'get',TRUE);

      
    }

    function update_mutasi_nrfs_get(){
        $param = array();        
        $no_trans = $this->get("no_trans");             
        $modify_by = $this->get("lastmodified_by");        
        $this->Main_model->set_custom_query($this->Custom_model->update_mutasi_NRFS($no_trans,$modify_by));
        
        $this->resultdata("TRANS_INV_MUTASI",$param,'get',TRUE);

      
    }
 
    /**
     * [inv_mutasi_delete description]
     * @return [type] [description]
     */
    public function inv_mutasi_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["TIPE_TRANS"]        = $this->put("tipe_trans");
        $param["JENIS_TRANS"]       = $this->put("jenis_trans");
        $param["PART_NUMBER"]       = $this->put("part_number");
        $param["KD_GUDANG_ASAL"]    = $this->put("kd_gudang_asal");
        $param["KD_DEALER_TUJUAN"]  = $this->put("kd_dealer_tujuan");
        $param["KD_GUDANG_TUJUAN"]  = $this->put("kd_gudang_tujuan");
        $param["RAKBIN_ASAL"]       = $this->put("rakbin_asal");
        $param["RAKBIN_TUJUAN"]     = $this->put("rakbin_tujuan");
        $param["JUMLAH"]            = $this->put("jumlah");
        $param["HARGA_BELI"]        = ($this->put("harga_beli"))?$this->put("harga_beli"):"0";
        $param["HET"]               = ($this->put("het"))?$this->put("het"):"0";
        /*$param["APPROVAL_STATUS"]   = $this->put("approval_status");
        $param["APPROVAL_BY"]       = $this->put("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->put("approval_date"));
        $param["STATUS_MUTASI"]     = $this->put("status_mutasi");*/
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_INV_MUTASI_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [inv_mutasi_delete description]
     * @return [type] [description]
     */
    public function inv_mutasi_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_INV_MUTASI_DELETE",$param,'delete',TRUE);
    }

    /**
     * [inv_mutasi_apv_post description]
     * @return [type] [description]
     */
    public function inv_mutasi_apv_post(){
        $param = array();
        $param["APPROVAL_STATUS"]   = $this->post("approval_status");
        $param["APPROVAL_BY"]       = $this->post("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->post("approval_date"));
        $param["STATUS_MUTASI"]     = $this->post("status_mutasi");
        $param["CREATED_BY"]        = $this->post("created_by");
 
        $this->resultdata("SP_TRANS_INV_MUTASI_APV_INSERT",$param,'post',TRUE);
    }

    /**
     * [inv_mutasi_apv_put description]
     * @return [type] [description]
     */
    public function inv_mutasi_apv_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["APPROVAL_STATUS"]   = $this->put("approval_status");
        $param["APPROVAL_BY"]       = $this->put("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->put("approval_date"));
        $param["STATUS_MUTASI"]     = $this->put("status_mutasi");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_INV_MUTASI_APV_UPDATE",$param,'put',TRUE);
    }

    /**
     * [moving_part_get description]
     * @return [type] [description]
     */
    public function moving_part_get($history=null){
        $param = array();$search='';
        if($this->get("part_number")){
            $param["PART_NUMBER"]   = $this->get("part_number");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]   = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }
        if($this->get("tgl_trans")){
            $param["TGL_TRANS"]     = $this->get("tgl_trans");
        }
        if($this->get("kd_rakbin")){
            $param["KD_RAKBIN"]     = $this->get("kd_rakbin");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "PART_NUMBER" =>$this->get("keyword"),
                "KD_GUDANG" =>$this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_havings($this->get("having"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_havings($this->get("having"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($history==true){
            $this->resultdata("TRANS_PART_MOVEMENT",$param);
        }else{
            $this->resultdata("TRANS_MOVING_PART_VIEW",$param);
        }
    }
    /**
     * [unitmovement_get description]
     * @return [type] [description]
     */
    public function unitmovement_get(){
        $param = array();$search='';
        if($this->get('no_rangka')){
             $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }

        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "PART_NUMBER" =>$this->get("keyword"),
                "KD_GUDANG" =>$this->get("keyword")
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
        $this->resultdata("TRANS_STOCKMOVEMENT",$param);
    }
    function rankparts_get(){
        $kd_dealer = $this->get("kd_dealer");
        $startdate = ($this->get("tgl_trans"));
        $kd_maindealer = ($this->get("kd_maindealer"));
        $typerpt= $this->get("rpt");
        $this->Main_model->set_custom_query($this->Custom_model->rankparts($kd_dealer,$typerpt,$startdate,$kd_maindealer));
        $this->resultdata("TRANS_PARTSO",$param=array());
    }

    /**
     * [adj_sgsorder_get description]
     * @return [type] [description]
     */
    public function adj_sgsorder_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]   = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]   = $this->get("no_trans");
        }
        if($this->get("tgl_trans")){
            $param["TGL_TRANS"]   = $this->get("tgl_trans");
        }
        if($this->get("PART_NUMBER")!=''){
            $param["part_number"]   = $this->get("part_number");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"       => $this->get("keyword"),
                "NO_TRANS"       => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_ADJ_SGSORDER",$param);
    }
    
    /**
     * [adj_sgsorder_post description]
     * @return [type] [description]
     */
    public function adj_sgsorder_post(){
        $param = array();
        $param["KD_MAINDEALER"]   = $this->post("kd_maindealer");
        $param["KD_DEALER"]   = $this->post("kd_dealer");
        $param["NO_TRANS"]   = $this->post("no_trans");
        $param["PART_NUMBER"] = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"TRANS_ADJ_SGSORDER");
        $param["TGL_TRANS"] = tglToSql($this->post("tgl_trans"));
        $param["AVG_SALES"] = $this->post("avg_sales");
        $param["AK_QTY"] = $this->post("ak_qty");
        $param["TOTAL_SALES"] = $this->post("total_sales");
        $param["PROSEN"] = $this->post("prosen");
        $param["RANK_PARTS"] = $this->post("rank_parts");
        $param["USK"] = $this->post("usk");
        $param["QTY_SIMPARTS"] = $this->post("qty_simparts");
        $param["USKD"] = $this->post("uskd");
        $param["WK1"] = $this->post("wk1");
        $param["WK2"] = $this->post("wk2");
        $param["WK3"] = $this->post("wk3");
        $param["WK4"] = $this->post("wk4");
        $param["WK5"] = $this->post("wk5");
        $param["WK6"] = $this->post("wk6");
        $param["STOCKED"] = $this->post("stocked");
        $param["QTY_PO"] = $this->post("qty_po");
        $param["PART_DESKRIPSI"] = $this->post("part_deskripsi");
        $param["SGS_ORDER"] = $this->post("sgs_order");
        $param["ADJ_SGSORDER"] = $this->post("adj_sgsorder");
        $param["CREATED_BY"]    = $this->post("created_by");
        //$this->resultdata("TRANS_ADJ_SGSORDER",$param,"post_batch",TRUE);
        $this->resultdata("SP_TRANS_ADJ_SGSORDER_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [adj_sgsorder_put description]
     * @return [type] [description]
     */
    public function adj_sgsorder_put(){
        $param = array();
        $param["NO_TRANS"]   = $this->put("no_trans");
        $param["TGL_TRANS"] = tglToSql($this->put("tgl_trans"));
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["PART_NUMBER"] = $this->put("part_number");
        $param["AVG_SALES"] = $this->put("avg_sales");
        $param["AK_QTY"] = $this->put("ak_qty");
        $param["TOTAL_SALES"] = $this->put("total_sales");
        $param["PROSEN"] = $this->put("prosen");
        $param["RANK_PARTS"] = $this->put("rank_parts");
        $param["USK"] = $this->put("usk");
        $param["QTY_SIMPARTS"] = $this->put("qty_simparts");
        $param["USKD"] = $this->put("uskd");
        $param["WK1"] = $this->put("wk1");
        $param["WK2"] = $this->put("wk2");
        $param["WK3"] = $this->put("wk3");
        $param["WK4"] = $this->put("wk4");
        $param["WK5"] = $this->put("wk5");
        $param["WK6"] = $this->put("wk6");
        $param["STOCKED"] = $this->put("stocked");
        $param["QTY_PO"] = $this->put("qty_po");
        $param["PART_DESKRIPSI"] = $this->put("part_deskripsi");
        $param["SGS_ORDER"] = $this->put("sgs_order");
        $param["ADJ_SGSORDER"] = $this->put("adj_sgsorder");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_ADJ_SGSORDER_UPDATE",$param,'put',TRUE);
    }

    /**
     * [adj_sgsorder_delete description]
     * @return [type] [description]
     */
    public function adj_sgsorder_delete(){
        $param = array();
        $param["NO_TRANS"]     = $this->delete('no_trans');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_ADJ_SGSORDER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stockopname_get description]
     * @return [type] [description]
     */
    public function stockopname_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]      = $this->get("no_trans");
        }
        if($this->get("jenis_opname")){
            $param["JENIS_OPNAME"]  = $this->get("jenis_opname");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "JENIS_OPNAME"  => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_STOCKOPNAME",$param);
    }
    
    /**
     * [stockopname_post description]
     * @return [type] [description]
     */
    public function stockopname_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["KD_LOKASIDEALER"]   = $this->post("kd_lokasidealer");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_STOCKOPNAME");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["JENIS_OPNAME"]      = $this->post("jenis_opname");
        $param["TGL_OPNAME"]        = tglToSql($this->post("tgl_opname"));
        $param["USER_OPNAME"]       = $this->post("user_opname");
        $param["STATUS_OPNAME"]     = "0";//$this->post("status_opname");
        $param["URAIAN_OPNAME"]     = "-";//$this->post("uraian_opname");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_STOCKOPNAME_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [stockopname_put description]
     * @return [type] [description]
     */
    public function stockopname_put(){
        $param = array();
        //$param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["JENIS_OPNAME"]      = $this->put("jenis_opname");
        $param["TGL_OPNAME"]        = tglToSql($this->put("tgl_opname"));
        $param["USER_OPNAME"]       = $this->put("user_opname");
        $param["STATUS_OPNAME"]     = $this->put("status_opname");
        $param["URAIAN_OPNAME"]     = "-";//$this->put("uraian_opname");
        $param["KD_LOKASIDEALER"]   = $this->put("kd_lokasidealer");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_STOCKOPNAME_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stockopname_delete description]
     * @return [type] [description]
     */
    public function stockopname_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STOCKOPNAME_DELETE",$param,'delete',TRUE);
    }
    /**
     * [stockopname_detail_get description]
     * @return [type] [description]
     */
    public function stockopname_detail_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]  = $this->get("no_trans");
        }
        if($this->get("kd_item")){
            $param["KD_ITEM"]   = $this->get("kd_item");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"] = $this->get("kd_gudang");
        }
        if($this->get("kd_rakbin")){
            $param["KD_RAKBIN"] = $this->get("kd_rakbin");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "KD_ITEM"       => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_STOCKOPNAME_DETAIL",$param);
    }
    
    /**
     * [stockopname_detail_post description]
     * @return [type] [description]
     */
    public function stockopname_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["KD_ITEM"]           = $this->post("kd_item");
        $param["KD_GUDANG"]         = $this->post("kd_gudang");
        $param["KD_RAKBIN"]         = $this->post("kd_rakbin");
        $this->Main_model->data_sudahada($param,"TRANS_STOCKOPNAME_DETAIL",TRUE);
        $param["QTY_STOCK"]         = $this->post("qty_stock");
        $param["QTY_AKTUAL"]        = $this->post("qty_aktual");
        //$param["SELISIH"]           = $this->post("selisih");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["HARGA_JUAL"]        = $this->post("harga_jual");
        $param["JENIS_ITEM"]        = $this->post("jenis_item");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_STOCKOPNAME_DETAIL_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [stockopname_detail_put description]
     * @return [type] [description]
     */
    public function stockopname_detail_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["KD_ITEM"]           = $this->put("kd_item");
        $param["KD_GUDANG"]         = $this->put("kd_gudang");
        $param["KD_RAKBIN"]         = $this->put("kd_rakbin");
        $param["QTY_STOCK"]         = $this->put("qty_stock");
        $param["QTY_AKTUAL"]        = $this->put("qty_aktual");
        //$param["SELISIH"]           = $this->put("selisih");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["HARGA_JUAL"]        = $this->put("harga_jual");
        $param["JENIS_ITEM"]        = $this->put("jenis_item");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
 
        $this->resultdata("SP_TRANS_STOCKOPNAME_DETAIL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [stockopname_detail_delete description]
     * @return [type] [description]
     */
    public function stockopname_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_STOCKOPNAME_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [sopart_customer_get description]
     * @return [type] [description]
     */
    public function sopart_customer_get($cs=null,$for_guest=null){
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
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_CUSTOMER"      => $this->get("keyword"),
                "NAMA_CUSTOMER"    => $this->get("keyword")
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
        if($cs){
            if($for_guest){
                $query = $this->Custom_model->customer_search($this->get("keyword"),'AP',$this->get("limit"));
                //var_dump($query);exit();
                $this->Main_model->set_custom_query($query);
                $this->resultdata("TRANS_PARTSO_CUSTOMER_V",$param);
            }else{
                $this->resultdata("TRANS_PARTSO_CUSTOMER_V",$param);
            }
        }else{
            $this->resultdata("TRANS_PARTSO_CUSTOMER",$param);
        }
    }
    /**
     * [sopart_customer_post description]
     * @return [type] [description]
     */
    public function sopart_customer_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $this->Main_model->data_sudahada($param,"TRANS_PARTSO_CUSTOMER");
        $param["NO_POLISI"]         = $this->post("no_polisi");
        $param["KD_CUSTOMER"]       = $this->post("kd_customer");
        $param["KD_TYPEMOTOR"]      = $this->post("kd_typemotor");
        $param["TAHUN_MOTOR"]       = $this->post("tahun_motor");
        $param["NAMA_CUSTOMER"]     = $this->post("nama_customer");
        $param["ALAMAT"]            = $this->post("alamat");
        $param["KD_DESA"]           = $this->post("kd_desa");
        $param["KD_KECAMATAN"]      = $this->post("kd_kecamatan");
        $param["KD_KABUPATEN"]      = $this->post("kd_kabupaten");
        $param["KD_PROPINSI"]       = $this->post("kd_propinsi");
        $param["NO_HP"]             = $this->post("no_hp");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_PARTSO_CUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [sopart_customer_put description]
     * @return [type] [description]
     */
    public function sopart_customer_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["KD_CUSTOMER"]       = $this->put("kd_customer");
        $param["NO_POLISI"]         = $this->put("no_polisi");
        $param["KD_TYPEMOTOR"]      = $this->put("kd_typemotor");
        $param["TAHUN_MOTOR"]       = $this->put("tahun_motor");
        $param["NAMA_CUSTOMER"]     = $this->put("nama_customer");
        $param["ALAMAT"]            = $this->put("alamat");
        $param["KD_DESA"]           = $this->put("kd_desa");
        $param["KD_KECAMATAN"]      = $this->put("kd_kecamatan");
        $param["KD_KABUPATEN"]      = $this->put("kd_kabupaten");
        $param["KD_PROPINSI"]       = $this->put("kd_propinsi");
        $param["NO_HP"]             = $this->put("no_hp");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PARTSO_CUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [sopart_customer_delete description]
     * @return [type] [description]
     */
    public function sopart_customer_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PARTSO_CUSTOMER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [stockopname_view_get description]
     * @return [type] [description]
     */
    public function stockopname_view_get(){
        $param = array();$search='';
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]   = $this->get("no_rangka");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]   = $this->get("kd_gudang");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_RANGKA"       => $this->get("keyword"),
                "KD_GUDANG"       => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_STOCKOPNAME_VIEW",$param);
    }

    /**
     * [sjkeluar_bynosj_put description]
     * @return [type] [description]
     */
    public function sjkeluar_bynosj_put(){
        $param = array();
        $param["NO_SURATJALAN"]     = $this->put("no_suratjalan");
        $param["NO_MOBIL"]          = $this->put("no_mobil");
        $param["NAMA_SOPIR"]        = $this->put("nama_sopir");
        $param["NAMA_EKSPEDISI"]    = $this->put("nama_ekspedisi");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SJKELUAR_UPDATE_BY_NOSJ",$param,'put',TRUE);
    }

    /**
     * [setup_barang_get description]
     * @return [type] [description]
     */
    public function setup_barang_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("kd_barang")){
            $param["KD_BARANG"]   = $this->get("kd_barang");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_RANGKA"       => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_BARANG",$param);
    }
    
    /**
     * [setup_barang_post description]
     * @return [type] [description]
     */
    public function setup_barang_post(){
        $param = array();
        $param["KD_BARANG"]   = $this->post("kd_barang");
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"SETUP_BARANG");
        $param["QTY_DEFAULT"] = $this->post("qty_default");
        $param["DOC_REFERER"] = $this->post("doc_referer");
        $param["CREATED_BY"]    = $this->post("created_by");
 
        $this->resultdata("SP_SETUP_BARANG_INSERT",$param,'post',TRUE);
    }
 
    /**
     * [setup_barang_put description]
     * @return [type] [description]
     */
    public function setup_barang_put(){
        $param = array();
        $param["ID"]   = $this->put("id");
        $param["KD_BARANG"]   = $this->put("kd_barang");
        $param["QTY_DEFAULT"] = $this->put("qty_default");
        $param["DOC_REFERER"] = $this->put("doc_referer");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["ROW_STATUS"] = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
 
        $this->resultdata("SP_SETUP_BARANG_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [setup_barang_delete description]
     * @return [type] [description]
     */
    public function setup_barang_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_BARANG_DELETE",$param,'delete',TRUE);
    }

}
?>