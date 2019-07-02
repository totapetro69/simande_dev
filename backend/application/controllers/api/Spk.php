<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Spk extends REST_Controller {

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
     * [spk_get description]
     * @return [type] [description]
     */
    public function spk_get($batal_view=null){
       $param = array();$search='';
       if($this->get('spkid')){
         $param["TRANS_SPK.ID"]     = $this->get("spkid");
       }
       if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("no_so")){
            $param["NO_SO"]     = $this->get("no_so");
        }
        if($this->get("faktur_penjualan")){
            $param["FAKTUR_PENJUALAN"]     = $this->get("faktur_penjualan");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("penjualan_via")){
            $param["PENJUALAN_VIA"]     = $this->get("penjualan_via");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_SO" =>$this->get("keyword"),
                "NO_SPK" =>$this->get("keyword"),
                "KD_SALES" =>$this->get("keyword"),
                "KD_CUSTOMER" =>$this->get("keyword"),

            );
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($batal_view){
            $this->resultdata("TRANS_SPK_VIEW_BATAL",$param);
        }else{
            $this->resultdata("TRANS_SPK",$param);
        }
    }
    function spkview_get(){
        $param = array();$search='';
       if($this->get('spkid')){
         $param["SPKID"]     = $this->get("spkid");
       }
       $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SPK_VIEW",$param);
    }
    /**
     * [spk_post description]
     * @return [type] [description]
     */
    public function spk_post(){
        $param = array();
        $param["NO_SPK"]        = $this->post('no_spk'); //1
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');//2
        $param["KD_DEALER"]     = $this->post('kd_dealer');//3
        $this->Main_model->data_sudahada($param,"TRANS_SPK",TRUE);
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');//5
        $param["JENIS_PENJUALAN"]   = $this->post('jenis_penjualan');//6
        $param["TYPE_PENJUALAN"]    = $this->post('type_penjualan');//7
        $param["JENIS_P_ANTARDEALER"]   = $this->post('jenis_p_antardealer');//8
        $param["CHANEL"]        = $this->post('chanel');//4
        $param["KD_TYPECUSTOMER"]   = $this->post('kd_typecustomer');//9
        $param["PENJUALAN_VIA"] = $this->post('penjualan_via');//10
        $param["JENIS_HARGA"]   = $this->post('jenis_harga');//11
        $param["KD_SALES"]      = $this->post('kd_sales');//12
        $param["KD_SALESHONDA"] = $this->post('kd_saleshonda');//13
        $param["NO_SO"]         = $this->post('no_so');//14
        $param["GUEST_NO"]      = $this->post('guest_no');//15
        $param["CREATED_BY"]    = $this->post('created_by');//16
        /*print_r($param);
        exit();*/
        $this->resultdata("SP_TRANS_SPK_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [spk_put description]
     * @return [type] [description]
     */
    public function spk_put(){
        $param = array();
        $param["NO_SPK"]            = $this->put('no_spk');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["JENIS_PENJUALAN"]   = $this->put('jenis_penjualan');
        $param["TYPE_PENJUALAN"]    = $this->put('type_penjualan');
        $param["JENIS_P_ANTARDEALER"]   = $this->put('jenis_p_antardealer');
        $param["CHANEL"]            = $this->put('chanel');
        $param["KD_TYPECUSTOMER"]   = $this->put('kd_typecustomer');
        $param["PENJUALAN_VIA"]     = $this->put('penjualan_via');
        $param["JENIS_HARGA"]       = $this->put('jenis_harga');
        $param["KD_SALES"]          = $this->put('kd_sales');
        $param["KD_SALESHONDA"]     = $this->put('kd_saleshonda');
        $param["NO_SO"]             = $this->put('no_so');
        $param["GUEST_NO"]          = $this->put('guest_no');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_UPDATE",$param,'put',TRUE);
    }

    /**
     * [spk_delete description]
     * @return [type] [description]
     */
    public function spk_delete(){
        $param=array();
        $param["NO_SPK"] = $this->delete('no_spk');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SPK_DELETE",$param,'delete',TRUE);
    }
    

    /**
     * [spk_detailcustomer_get description]
     * @return [type] [description]
     */
    public function spk_detailcustomer_get(){
       $param = array();$search='';
       if($this->get("spk_id")){
            $param["SPK_ID"]     = $this->get("spk_id");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("nama_bpkb")){
            $param["NAMA_BPKB"]     = $this->get("nama_bpkb");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "SPK_ID" =>$this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword"),
                "NAMA_BPKB" =>$this->get("keyword")
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
        $this->resultdata("TRANS_SPK_DETAILCUSTOMER",$param);
    }
    /**
     * [spk_detailcustomer_post description]
     * @return [type] [description]
     */
    public function spk_detailcustomer_post(){
        $param = array();
        $param["SPK_ID"]            = $this->post('spk_id');
        $this->Main_model->data_sudahada($param,"TRANS_SPK_DETAILCUSTOMER");
        $param["KD_CUSTOMER"]       = $this->post('kd_customer');
        $param["NAMA_BPKB"]         = ($this->post('nama_bpkb'));
        $param["ALAMAT_BPKB"]       = ($this->post('alamat_bpkb'));
        $param["KTP_BPKB"]          = $this->post('ktp_bpkb');
        $param["EMAIL_BPKB"]        = $this->post('email_bpkb');
        $param["TGL_LAHIR_BPKB"]    = $this->post('tgl_lahir_bpkb');
        $param["KETERANGAN"]        = ($this->post('keterangan'));
        $param["KD_PROPINSI"]       = $this->post('kd_propinsi');
        $param["KD_KABUPATEN"]      = $this->post('kd_kabupaten');
        $param["KD_KECAMATAN"]      = $this->post('kd_kecamatan');
        $param["NAMA_KECAMATAN"]    = $this->post('nama_kecamatan');
        $param["KD_KELURAHAN"]      = $this->post('kd_kelurahan');
        $param["NAMA_KELURAHAN"]    = $this->post('nama_kelurahan');
        $param["KODE_POS"]          = $this->post('kode_pos');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_SPK_DETAILCUSTOMER_INSERT",$param,'post',TRUE);
    }
    

    /**
     * [spk_detailcustomer_put description]
     * @return [type] [description]
     */
    public function spk_detailcustomer_put(){
        $param = array();
        $param["SPK_ID"]            = $this->put('spk_id');
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["NAMA_BPKB"]         = ($this->put('nama_bpkb'));
        $param["ALAMAT_BPKB"]       = ($this->put('alamat_bpkb'));
        $param["KTP_BPKB"]          = $this->put('ktp_bpkb');
        $param["EMAIL_BPKB"]        = $this->put('email_bpkb');
        $param["TGL_LAHIR_BPKB"]    = $this->put('tgl_lahir_bpkb');
        $param["KETERANGAN"]        = ($this->put('keterangan'));
        $param["KD_PROPINSI"]       = $this->put('kd_propinsi');
        $param["KD_KABUPATEN"]      = $this->put('kd_kabupaten');
        $param["KD_KECAMATAN"]      = $this->put('kd_kecamatan');
        $param["NAMA_KECAMATAN"]    = $this->put('nama_kecamatan');
        $param["KD_KELURAHAN"]      = $this->put('kd_kelurahan');
        $param["NAMA_KELURAHAN"]    = $this->put('nama_kelurahan');
        $param["KODE_POS"]          = $this->put('kode_pos');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_DETAILCUSTOMER_UPDATE",$param,'put',TRUE);
    }

    /**
     * [spk_detailcustomer_delete description]
     * @return [type] [description]
     */
    public function spk_detailcustomer_delete(){
        $param=array();
        $param["SPK_ID"]             = $this->delete("spk_id");
        $param["LASTMODIFIED_BY"]    = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_SPK_DETAILCUSTOMER_DELETE",$param,'delete',TRUE);
    }


    /**
     * [spk_detailkendaraan_get description]
     * @return [type] [description]
     */
    public function spk_detailkendaraan_get(){
       $param = array();$search='';
       if($this->get("spk_id")){
            $param["SPK_ID"]     = $this->get("spk_id");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
        }
        if($this->get("kd_warna")){
            $param["KD_WARNA"]     = $this->get("kd_warna");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "SPK_ID" =>$this->get("keyword"),
                "KD_TYPEMOTOR" =>$this->get("keyword"),
                "KD_WARNA" =>$this->get("keyword")
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
        $this->resultdata("TRANS_SPK_DETAILKENDARAAN",$param);
    }
    /**
     * [spk_detailkendaraan_post description]
     * @return [type] [description]
     */
    public function spk_detailkendaraan_post(){
        $param = array();
        $param["SPK_ID"]        = $this->post('spk_id');//1
        $param["KD_TYPEMOTOR"]  = $this->post('kd_typemotor');//2
        $param["KD_WARNA"]      = $this->post('kd_warna');//3
        $this->Main_model->data_sudahada($param,"TRANS_SPK_DETAILKENDARAAN");
        $param["NO_MESIN"]      = $this->post('no_mesin');//4
        $param["NO_RANGKA"]     = $this->post('no_rangka');//5
        $param["KD_PAKET"]      = $this->post('kd_paket');//6
        $param["HARGA"]         = $this->post('harga');//7
        $param["HARGA_OTR"]     = $this->post('harga_otr');//8
        $param["HARGA_DEALER"]  = $this->post('harga_dealer');//9
        $param["HARGA_DEALERD"] = $this->post('harga_dealerd');//10
        $param["JUMLAH"]        = $this->post('jumlah');//11
        $param["BBN"]           = $this->post('bbn');//12
        $param["DISKON"]        = $this->post('diskon');
        $param["KD_SALESPROGRAM"]   = $this->post('kd_salesprogram');//13
        $param["KD_SALESKUPON"] = $this->post('kd_saleskupon');//14
        $param["KD_BUNDLING"]   = $this->post('kd_bundling');//15
        $param["ESTIMASI_STNK"] = tglToSql($this->post('estimasi_stnk'));//15
        $param["ESTIMASI_BPKB"] = tglToSql($this->post('estimasi_bpkb'));//15
        $param["CRM"]           = $this->post('crm');//16
        $param["BARANG"]        = $this->post('barang');//17
        $param["HADIAH"]        = $this->post('hadiah');//18
        $param["CREATED_BY"]    = $this->post('created_by');//19

            // print_r($param)  ;
        $this->resultdata("SP_TRANS_SPK_DETAILKENDARAAN_INSERT",$param,'post',TRUE);
    }


    /**
     * [spk_detailkendaraan_put description]
     * @return [type] [description]
     */
    public function spk_detailkendaraan_put(){
        $param = array();
        $param["SPK_ID"]        = $this->put('spk_id');
        $param["KD_TYPEMOTOR"]  = $this->put('kd_typemotor');
        $param["KD_WARNA"]      = $this->put('kd_warna');
        $param["NO_MESIN"]      = $this->put('no_mesin');
        $param["NO_RANGKA"]     = $this->put('no_rangka');
        $param["KD_PAKET"]      = $this->put('kd_paket');
        $param["HARGA"]         = $this->put('harga');
        $param["HARGA_OTR"]     = $this->put('harga_otr');
        $param["HARGA_DEALER"]  = $this->put('harga_dealer');
        $param["HARGA_DEALERD"] = $this->put('harga_dealerd');
        $param["JUMLAH"]        = $this->put('jumlah');
        $param["BBN"]           = $this->put('bbn');
        $param["DISKON"]        = $this->put('diskon');
        $param["KD_SALESPROGRAM"]   = $this->put('kd_salesprogram');
        $param["KD_SALESKUPON"] = $this->put('kd_saleskupon');
        $param["KD_BUNDLING"]   = $this->put('kd_bundling');//15
        $param["ESTIMASI_STNK"] = tglToSql($this->put('estimasi_stnk'));//15
        $param["ESTIMASI_BPKB"] = tglToSql($this->put('estimasi_bpkb'));//15
        $param["CRM"]           = $this->put('crm');
        $param["BARANG"]        = $this->put('barang');
        $param["HADIAH"]        = $this->put('hadiah');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_DETAILKENDARAAN_UPDATE",$param,'put',TRUE);
    }

    /**
     * [spk_detailkendaraan_delete description]
     * @return [type] [description]
     */
    public function spk_detailkendaraan_delete(){
        $param=array();
        $param["ID"]             = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_SPK_DETAILKENDARAAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [spk_leasing_get description]
     * @return [type] [description]
     */
    public function spk_leasing_get(){
       $param = array();$search='';
       if($this->get("spk_id")){
            $param["SPK_ID"]     = $this->get("spk_id");
        }
        if($this->get("kd_fincoy")){
            $param["KD_FINCOY"]     = $this->get("kd_fincoy");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "SPK_ID" =>$this->get("keyword"),
                "KD_FINCOY" =>$this->get("keyword"),
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
        $this->resultdata("TRANS_SPK_LEASING",$param);
    }
    /**
     * [spk_leasing_post description]
     * @return [type] [description]
     */
    public function spk_leasing_post(){
        $param = array();
        $param["SPK_ID"]    = $this->post('spk_id');
        $param["KD_FINCOY"] = $this->post('kd_fincoy');
        $this->Main_model->data_sudahada($param,"TRANS_SPK_LEASING");
        $param["UANG_MUKA"] = $this->post('uang_muka');
        $param["BUNGA"]     = $this->post('bunga');
        $param["ADM"]       = $this->post('adm');
        $param["JANGKA_WAKTU"]   = $this->post('jangka_waktu');
        $param["JUMLAH_ANGSURAN"]   = $this->post('jumlah_angsuran');
        $param["JATUH_TEMPO"]   = tglToSql($this->post('jatuh_tempo'));
        $param["HASIL"]     =NULL;// $this->post('hasil');
        $param["TANGGAL"]   =NULL;// ($this->post('tanggal'));
        $param["KETERANGAN"]= $this->post('keterangan');
        $param["CREATED_BY"]= $this->post('created_by');
        $param["ALASAN_PAKSA"]= $this->post('alasan_paksa');
        $param["TYPE_CREDIT"] = $this->post('type_credit');
        // print_r($param);
        $this->resultdata("SP_TRANS_SPK_LEASING_INSERT",$param,'post',TRUE);
    }


    /**
     * [spk_leasing_put description]
     * @return [type] [description]
     */
    public function spk_leasing_put(){
        $param = array();
        $param["SPK_ID"]      = $this->put('spk_id');
        $param["KD_FINCOY"]   = $this->put('kd_fincoy');
        $param["UANG_MUKA"]   = $this->put('uang_muka');
        $param["BUNGA"]   = $this->put('bunga');
        $param["ADM"]   = $this->put('adm');
        $param["JANGKA_WAKTU"]   = $this->put('jangka_waktu');
        $param["JUMLAH_ANGSURAN"]   = $this->put('jumlah_angsuran');
        $param["JATUH_TEMPO"]   = tglToSql($this->put('jatuh_tempo'));
        $param["HASIL"]   = $this->put('hasil');
        $param["TANGGAL"]   = tglToSql($this->put('tanggal'));
        $param["KETERANGAN"]   = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $param["ALASAN_PAKSA"]= $this->put('alasan_paksa');
        $param["TYPE_CREDIT"] = $this->put('type_credit');
        
        $this->resultdata("SP_TRANS_SPK_LEASING_UPDATE",$param,'put',TRUE);
    }

    /**
     * [spk_leasing_delete description]
     * @return [type] [description]
     */
    public function spk_leasing_delete(){
        $param=array();
        $param["SPK_ID"]             = $this->delete("spk_id");
        $param["LASTMODIFIED_BY"]   = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_SPK_LEASING_DELETE",$param,'delete',TRUE);
    }
    public function spk_appvleasing_put(){
        $param=array();
        $param["SPK_ID"]             = $this->delete("spk_id");
        $param["KD_FINCOY"]   = $this->put('kd_fincoy');
        $param["LASTMODIFIED_BY"]   = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_SPK_LEASING_APROVE",$param,'delete',TRUE);
    }

    /**
     * [so_put description]
     * @return [type] [description]
     */
    public function so_put(){
        $param=array();
        $param["SPK_ID"]   = $this->put('spk_id');
        $param["NO_SO"]    = $this->put('no_so');
        $param["STATUS_SPK"]    = $this->put('status_spk');
        $param["FAKTUR_PENJUALAN"]    = $this->put('faktur_penjualan');
        //$param["LOK_PENJUALAN"]    = $this->put('lok_penjualan');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SO_UPDATE",$param,'put',TRUE);
    }

    /**
     * [so_put description]
     * @return [type] [description]
     */
    public function so_kendaraan_put(){
        $param=array();
        $param["SDK_ID"]        = $this->put('sdk_id');
        $param["NO_MESIN"]      = $this->put('no_mesin');
        $param["NO_RANGKA"]     = $this->put('no_rangka');
        $param["BARANG"]        = $this->put('barang');
        $param["HADIAH"]        = $this->put('hadiah');
        $param["KD_PAKET"]      = $this->put('kd_paket');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SO_KENDARAAN_UPDATE",$param,'put',TRUE);
    }



    /**
     * [so_put description]
     * @return [type] [description]
     */
    public function status_rm_put(){
        $param=array();
        $param["NO_MESIN"]       = $this->put('no_mesin');
        $param["STOCK_STATUS"]   = $this->put('stock_status');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SO_TERIMA_KENDARAAN_UPDATE",$param,'put',TRUE);
    }

    /**
     * [trans_kuisoner_get description]
     * @return [type] [description]
     */
    public function trans_kuisoner_get(){
       $param = array();$search='';
       if($this->get("spk_id")){
            $param["SPK_ID"]     = $this->get("spk_id");
        }
       if($this->get("no_urut")){
            $param["NO_URUT"]     = $this->get("no_urut");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_URUT" =>$this->get("keyword")
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
        $this->resultdata("TRANS_KUISONER",$param);
    }
    /**
     * [trans_kuisoner_post description]
     * @return [type] [description]
     */
    public function trans_kuisoner_post(){
        $param = array();
        $param["NO_URUT"]       = $this->post('no_urut');
        $param["SPK_ID"]        = $this->post('spk_id');
        $this->Main_model->data_sudahada($param,"TRANS_KUISONER");
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["JAWABAN"]       = $this->post('jawaban');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_KUISONER_INSERT",$param,'post',TRUE);
    }


    /**
     * [trans_kuisoner_put description]
     * @return [type] [description]
     */
    public function trans_kuisoner_put(){
        $param = array();
        //$param["ID"] = $this->put('id');
        $param["SPK_ID"]        = $this->put('spk_id');
        $param["NO_URUT"]       = $this->put('no_urut');
        $param["KD_CUSTOMER"]   = $this->put('kd_customer');
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["JAWABAN"]       = $this->put('jawaban');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_KUISONER_UPDATE",$param,'put',TRUE);
    }

    /**
     * [trans_kuisoner_delete description]
     * @return [type] [description]
     */
    public function trans_kuisoner_delete(){
        $param=array();
        $param["ID"]             = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_KUISONER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [trans_spk_detailalamat_get description]
     * @return [type] [description]
     */
    public function spk_detailalamat_get(){
       $param = array();$search='';
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("spk_id")){
            $param["SPK_ID"]     = $this->get("spk_id");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_CUSTOMER" =>$this->get("keyword"),
                "ALAMAT_SURAT" =>$this->get("keyword")
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
        $this->resultdata("TRANS_SPK_DETAILALAMAT",$param);
    }
    /**
     * [trans_spk_detailalamat_post description]
     * @return [type] [description]
     */
    public function spk_detailalamat_post(){
        $param = array();
        $param["SPK_ID"]        = $this->post('spk_id');
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $this->Main_model->data_sudahada($param,"TRANS_SPK_DETAILALAMAT");
        $param["ALAMAT_SURAT"]  = ($this->post('alamat_surat'));
        $param["KD_PROPINSI"]   = $this->post('kd_propinsi');
        $param["KD_KOTA"]       =   $this->post('kd_kota');
        $param["KD_KECAMATAN"]  =   $this->post('kd_kecamatan');
        $param["KD_DESA"]       =   $this->post('kd_desa');
        $param["KODE_POS"]      =   $this->post('kode_pos');

        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_SPK_DETAILALAMAT_INSERT",$param,'post',TRUE);
    }


    /**
     * [trans_spk_detailalamat_put description]
     * @return [type] [description]
     */
    public function spk_detailalamat_put(){
        $param = array();
        $param["SPK_ID"]        = $this->put('spk_id');
        $param["KD_CUSTOMER"]   = $this->put('kd_customer');
        $param["ALAMAT_SURAT"]  = ($this->put('alamat_surat'));
        $param["KD_PROPINSI"]   = $this->put('kd_propinsi');
        $param["KD_KOTA"]       = $this->put('kd_kota');
        $param["KD_KECAMATAN"]  = $this->put('kd_kecamatan');
        $param["KD_DESA"]       = $this->put('kd_desa');
        $param["KODE_POS"]      = $this->put('kode_pos');
        /*$param["NO_HP"]    =   $this->put('no_hp');
        $param["ALAMAT_KIRIM"]    =   $this->put('alamat_kirim');
        $param["NAMA_PENERIMA"]    =   $this->put('nama_penerima');
        $param["KETERANGAN"]    =   $this->put('keterangan');*/
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_DETAILALAMAT_UPDATE",$param,'put',TRUE);
    }

    /**
     * [trans_spk_detailalamat_delete description]
     * @return [type] [description]
     */
    public function spk_detailalamat_delete(){
        $param=array();
        $param["SPK_ID"]             = $this->delete("spk_id");
        $param["LASTMODIFIED_BY"]   = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_SPK_DETAILALAMAT_DELETE",$param,'delete',TRUE);
    }
    public function spk_alamatkirim_put(){
        $param=array();
        $param["SPK_ID"]        = $this->put('spk_id');
        $param["NO_HP"]         = $this->put('no_hp');
        $param["ALAMAT_KIRIM"]  = ($this->put('alamat_kirim'));
        $param["TGL_KIRIM"]     = tglToSql($this->put('tgl_kirim'));
        $param["JAM_KIRIM"]     = $this->put('jam_kirim');
        $param["LOKASI_KIRIM"]     = $this->put('lokasi_kirim');
        $param["NAMA_PENERIMA"] = ($this->put('nama_penerima'));
        $param["KETERANGAN"]    = $this->put('keterangan');
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_ALAMATKIRIM_UPDATE",$param,'put',TRUE);
    }

    /**
     * [spk_status_put description]
     * @return [type] [description]
     */
    public function spk_status_put($paybill=null){
        $param=array();
        $param["STATUS_SPK"]    = $this->put('status_spk');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        if($paybill==true){
            $param["NO_SPK"]   = $this->put('no_spk');
            $param["PAYBILL_REFF"]  = $this->put('no_reff');
            $param["KURANG_BAYAR"]  = $this->put("kurang_bayar");
            $param["RENCANA_BAYAR"] = $this->put("rencana_bayar");
            $this->resultdata("SP_TRANS_SPK_PAYBILL",$param,'put',TRUE);
        }else{
             $param["ID"]   = $this->put('id');
             $this->resultdata("SP_SPK_STATUS_UPDATE",$param,'put',TRUE);
        }
        
    }

    /**
     * [spk_leasing_komposisi_get description]
     * @return [type] [description]
     */
    public function leasing_komposisi_get(){
       $param = array();$search='';
       
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_leasing")){
            $param["KD_LEASING"]     = $this->get("kd_leasing");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_MAINDEALER" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "KD_LEASING" =>$this->get("keyword")
            );
        }
        if($this->get("query")){
            $this->Main_model->set_custom_query($this->get("query"));
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
        $this->resultdata("TRANS_SPK_LEASING_KOMPOSISI",$param);
    }

    /**
     * [spk_leasing_komposisi_post description]
     * @return [type] [description]
     */
    public function leasing_komposisi_post(){
        $param = array();
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["TAHUN"]    = $this->post('tahun');
        $param["RANGKING_LEASING"] = $this->post('rangking');
        $this->Main_model->data_sudahada($param,"TRANS_SPK_LEASING_KOMPOSISI");
        $param["KD_LEASING"]   = $this->post('kd_leasing');
        $param["TARGET_LEASING"]   =((int)$this->post('target_leasing'))? ($this->post('target_leasing')/100):0;
        $param["CREATED_BY"]    = $this->post('created_by');
        /*print_r($param);
        exit();*/
        $this->resultdata("SP_TRANS_SPK_LEASING_KOMPOSISI_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [spk_leasing_komposisi_put description]
     * @return [type] [description]
     */
    public function leasing_komposisi_put(){
        $param = array();
        $param["ID"] = $this->put('id');
        $param["KD_MAINDEALER"]   = $this->put('kd_maindealer');
        $param["KD_DEALER"]   = $this->put('kd_dealer');
        $param["KD_LEASING"]   = $this->put('kd_leasing');
        $param["TARGET_LEASING"]   = ((int)$this->put('target_leasing'))? ($this->put('target_leasing')/100):0;
        $param["RANGKING_LEASING"] = $this->put('rangking');
        $param["TAHUN"]   = $this->put('tahun');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_LEASING_KOMPOSISI_UPDATE",$param,'put',TRUE);
    }

    /**
     * [spk_leasing_komposisi_delete description]
     * @return [type] [description]
     */
    public function leasing_komposisi_delete(){
        $param=array();
        $param["ID"] = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_SPK_LEASING_KOMPOSISI_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *TRANS SPK SALES KUPON
    */
    public function spk_saleskupon_get(){
        $param = array();$search='';
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]  = $this->get("kd_typemotor");
        }
        /**/
        if($this->get("kd_saleskupon")){
            $param["KD_SALESKUPON"]     = $this->get("kd_saleskupon");
        }
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("end_date")){
            $param["END_DATE"]     = $this->get("end_date");
        }
        if($this->get("end_claim")){
            $param["END_CLAIM"]     = $this->get("end_claim");
        }
        if($this->get("nilai")){
            $param["NILAI"]     = $this->get("nilai");
        }
        if($this->get("nama_saleskupon")){
            $param["NAMA_SALESKUPON"]     = $this->get("nama_saleskupon");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_SPK" => $this->get("keyword"),
                "KD_SALESKUPON" => $this->get("keyword"),
                "NAMA_SALESKUPON" => $this->get("keyword")
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
        $this->resultdata("TRANS_SPK_SALESKUPON",$param);
    }
    /**
     * [spk_saleskupon_post description]
     * @return [type] [description]
     */
    public function spk_saleskupon_post(){
        $param = array();
        $param["NO_SPK"] = $this->post('no_spk');
        $param["KD_SALESKUPON"] = $this->post('kd_saleskupon');
        $param["KD_TYPEMOTOR"] =  $this->post('kd_typemotor');
        $this->Main_model->data_sudahada($param,"TRANS_SPK_SALESKUPON");
        $param["NAMA_SALESKUPON"]   = $this->post('nama_saleskupon');
        $param["END_DATE"] =  $this->post('end_date');
        $param["END_CLAIM"] =  $this->post('end_claim');
        $param["START_DATE"] =  $this->post('start_date');
        $param["NO_PERKIRAAN"] =  $this->post('no_perkiraan');
        $param["NO_SUBPERKIRAAN"] =  $this->post('no_subperkiraan');
        $param["TOP1"] =  $this->post('top1');
        $param["TOP2"] =  $this->post('top2');
        $param["NILAI"] =  $this->post('nilai');
        $param["CREATED_BY"]    = $this->post('created_by');
        
        // print_r($param);
        $this->resultdata("SP_TRANS_SPK_SALESKUPON_INSERT",$param,'post',TRUE);
    }

    /**
     * [spk_saleskupon_put description]
     * @return [type] [description]
     */
    public function spk_saleskupon_put(){
        $param = array();
        $param["NO_SPK"] = $this->put('no_spk');
        $param["KD_SALESKUPON"] = $this->put('kd_saleskupon');
        $param["NAMA_SALESKUPON"]   = $this->put('nama_saleskupon');
        $param["START_DATE"] =  $this->put('start_date');
        $param["END_DATE"] =  $this->put('end_date');
        $param["END_CLAIM"] =  $this->put('end_claim');
        $param["NO_PERKIRAAN"] =  $this->put('no_perkiraan');
        $param["NO_SUBPERKIRAAN"] =  $this->put('no_subperkiraan');
        $param["KD_TYPEMOTOR"] =  $this->put('kd_typemotor');
        $param["NILAI"] =  $this->put('nilai');
        $param["TOP1"] =  $this->put('top1');
        $param["TOP2"] =  $this->put('top2');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_SALESKUPON_UPDATE",$param,'put',TRUE);
    }
    /**
     * [spk_saleskupon_delete description]
     * @return [type] [description]
     */
    public function spk_saleskupon_delete(){
        $param = array();
        $param["KD_SALESKUPON"]     = $this->delete('kd_saleskupon');
        $param["NO_SPK"]     = $this->delete('no_spk');
        $param["KD_TYPEMOTOR"]     = $this->delete('kd_typemotor');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SPK_SALESKUPON_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *TRANS SPK SALES PROGRAM
    */
    public function spk_salesprogram_get(){
        $param = array();$search='';
        if($this->get("kd_salesprogram")){
            $param["KD_SALESPROGRAM"]     = $this->get("kd_salesprogram");
        }
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("nama_salesprogram")){
            $param["NAMA_SALESPROGRAM"]     = $this->get("nama_salesprogram");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"] = $this->get("kd_typemotor");
        }
        if($this->get("end_date")){
            $param["END_DATE"] = $this->get("end_date");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_SALESPROGRAM" => $this->get("keyword"),
                "NAMA_SALESPROGRAM" => $this->get("keyword")
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
        $this->resultdata("TRANS_SPK_SALESPROGRAM",$param);
    }
    /**
     * [spk_salesprogram_post description]
     * @return [type] [description]
     */
    public function spk_salesprogram_post(){
        $param = array();
        $param["NO_SPK"] = $this->post('no_spk');
        $this->Main_model->data_sudahada($param,"TRANS_SPK_SALESPROGRAM");      
        $param["KD_SALESPROGRAM"] = $this->post('kd_salesprogram');
        $param["KD_TYPEMOTOR"] =  $this->post('kd_typemotor');
        $param["START_DATE"] =  $this->post('start_date');
        $param["END_DATE"] =  $this->post('end_date');
        $param["NAMA_SALESPROGRAM"]   = $this->post('nama_salesprogram');
        $param["TIPE_SALESPROGRAM"]   = $this->post('tipe_salesprogram');
        $param["KD_SALESPROGRAMAHM"]   = $this->post('kd_salesprogramahm');
        $param["NO_SURATSP"]   = $this->post('no_suratsp');
        $param["SALESPROGRAM_KHUSUS"]   = $this->post('salesprogram_khusus');
        $param["SALESPROGRAM_GIFT"]   = $this->post('salesprogram_gift');
        $param["SALESPROGRAM_CABANG"]   = $this->post('salesprogram_cabang');
        $param["POT_START"] =  $this->post('pot_start');
        $param["POT_END"] =  $this->post('pot_end');
        $param["SSU_START"] =  $this->post('ssu_start');
        $param["SSU_END"] =  $this->post('ssu_end');
        $param["END_CLAIM"] =  $this->post('end_claim');
        $param["QTY"] =  $this->post('qty');
        $param["SK_AHM"] =  $this->post('sk_ahm');
        $param["SK_MD"] =  $this->post('sk_md');
        $param["SK_SD"] =  $this->post('sk_sd');
        $param["SK_FINANCE"] =  $this->post('sk_finance');
        $param["SC_AHM"] =  $this->post('sc_ahm');
        $param["SC_MD"] =  $this->post('sc_md');
        $param["SC_SD"] =  $this->post('sc_sd');
        $param["CB_AHM"] =  $this->post('cb_ahm');
        $param["CB_MD"] =  $this->post('cb_md');
        $param["CB_SD"] =  $this->post('cb_sd');
        $param["POT_FAKTUR"] =  $this->post('pot_faktur');
        $param["CASH_TEMPO"] =  $this->post('cash_tempo');
        $param["SPLIT_OTR"] =  $this->post('split_otr');
        $param["SPLIT_OTR2"] =  $this->post('split_otr2');
        $param["HADIAH_LANGSUNG"] =  $this->post('hadiah_langsung');
        $param["HARGA_KONTRAK"] =  $this->post('harga_kontrak');
        $param["FEE"] =  $this->post('fee');
        $param["PENGURUSAN_STNK"] =  $this->post('pengurusan_stnk');
        $param["PENGURUSAN_BPKB"] =  $this->post('pengurusan_bpkb');
        $param["NO_PO"] =  $this->post('no_po');
        $param["MIN_SK_SD"] =  $this->post('min_sk_sd');
        $param["MIN_SC_SD"] =  $this->post('min_sc_sd');
        $param["DP_OTR"] =  $this->post('dp_otr');
        $param["TAMBAHAN_FINANCE"] =  $this->post('tambahan_finance');
        $param["TAMBAHAN_MD"] =  $this->post('tambahan_md');
        $param["TAMBAHAN_SD"] =  $this->post('tambahan_sd');
        $param["TUNDA_FAKTUR"] =  $this->post('tunda_faktur');
        $param["HADIAH_LANGSUNG2"] =  $this->post('hadiah_langsung2');
        $param["KETERANGAN_HADIAH"] =  $this->post('keterangan_hadiah');
        $param["TAMBAHAN_AHM"] =  $this->post('tambahan_ahm');
        $param["CREATED_BY"]    = $this->post('created_by');
        // print_r($param);
        $this->resultdata("SP_TRANS_SPK_SALESPROGRAM_INSERT",$param,'post',TRUE);
    }
    /**
     * [spk_salesprogram_put description]
     * @return [type] [description]
     */
    public function spk_salesprogram_put(){
        $param = array();
        $param["NO_SPK"] = $this->put('no_spk');
        $param["KD_SALESPROGRAM"] = $this->put('kd_salesprogram');
        $param["NAMA_SALESPROGRAM"]   = $this->put('nama_salesprogram');
        $param["TIPE_SALESPROGRAM"]   = $this->put('tipe_salesprogram');
        $param["KD_SALESPROGRAMAHM"]   = $this->put('kd_salesprogramahm');
        $param["NO_SURATSP"]   = $this->put('no_suratsp');
        $param["SALESPROGRAM_KHUSUS"]   = $this->put('salesprogram_khusus');
        $param["SALESPROGRAM_GIFT"]   = $this->put('salesprogram_gift');
        $param["SALESPROGRAM_CABANG"]   = $this->put('salesprogram_cabang');
        $param["START_DATE"] =  $this->put('start_date');
        $param["POT_START"] =  $this->put('pot_start');
        $param["POT_END"] =  $this->put('pot_end');
        $param["SSU_START"] =  $this->put('ssu_start');
        $param["SSU_END"] =  $this->put('ssu_end');
        $param["END_DATE"] =  $this->put('end_date');
        $param["END_CLAIM"] =  $this->put('end_claim');
        $param["KD_TYPEMOTOR"] =  $this->put('kd_typemotor');
        $param["QTY"] =  $this->put('qty');
        $param["SK_AHM"] =  $this->put('sk_ahm');
        $param["SK_MD"] =  $this->put('sk_md');
        $param["SK_SD"] =  $this->put('sk_sd');
        $param["SK_FINANCE"] =  $this->put('sk_finance');
        $param["SC_AHM"] =  $this->put('sc_ahm');
        $param["SC_MD"] =  $this->put('sc_md');
        $param["SC_SD"] =  $this->put('sc_sd');
        $param["CB_AHM"] =  $this->put('cb_ahm');
        $param["CB_MD"] =  $this->put('cb_md');
        $param["CB_SD"] =  $this->put('cb_sd');
        $param["POT_FAKTUR"] =  $this->put('pot_faktur');
        $param["CASH_TEMPO"] =  $this->put('cash_tempo');
        $param["SPLIT_OTR"] =  $this->put('split_otr');
        $param["SPLIT_OTR2"] =  $this->put('split_otr2');
        $param["HADIAH_LANGSUNG"] =  $this->put('hadiah_langsung');
        $param["HARGA_KONTRAK"] =  $this->put('harga_kontrak');
        $param["FEE"] =  $this->put('fee');
        $param["PENGURUSAN_STNK"] =  $this->put('pengurusan_stnk');
        $param["PENGURUSAN_BPKB"] =  $this->put('pengurusan_bpkb');
        $param["NO_PO"] =  $this->put('no_po');
        $param["MIN_SK_SD"] =  $this->put('min_sk_sd');
        $param["MIN_SC_SD"] =  $this->put('min_sc_sd');
        $param["DP_OTR"] =  $this->put('dp_otr');
        $param["TAMBAHAN_FINANCE"] =  $this->put('tambahan_finance');
        $param["TAMBAHAN_MD"] =  $this->put('tambahan_md');
        $param["TAMBAHAN_SD"] =  $this->put('tambahan_sd');
        $param["TUNDA_FAKTUR"] =  $this->put('tunda_faktur');
        $param["HADIAH_LANGSUNG2"] =  $this->put('hadiah_langsung2');
        $param["KETERANGAN_HADIAH"] =  $this->put('keterangan_hadiah');
        $param["TAMBAHAN_AHM"] =  $this->put('tambahan_ahm');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPK_SALESPROGRAM_UPDATE",$param,'put',TRUE);
    }
    /**
     * [spk_salesprogram_delete description]
     * @return [type] [description]
     */
    public function spk_salesprogram_delete(){
        $param = array();
        $param["NO_SPK"] = $this->delete('no_spk');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        // $this->resultdata("SP_TRANS_SPK_SALESPROGRAM_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *TRANS SPK BUNDLING
    */
    public function spkbundling_get(){
        $param = array();$search='';
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("kd_bundling")){
            $param["KD_BUNDLING"]     = $this->get("kd_bundling");
        }
        if($this->get("kd_item")){
            $param["KD_ITEM"]     = $this->get("kd_item");
        }
        if($this->get("nama_item")){
            $param["NAMA_ITEM"]     = $this->get("nama_item");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_SPK" => $this->get("keyword"),
                "KD_BUNDLING" => $this->get("keyword"),
                "NAMA_ITEM" => $this->get("keyword")
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
        $this->resultdata("TRANS_SPKBUNDLING",$param);
    }
    /**
     * [spkbundling_post description]
     * @return [type] [description]
     */
    public function spkbundling_post(){
        $param = array();
        $param["NO_SPK"]        = $this->post('no_spk');
        $param["KD_BUNDLING"]   = $this->post('kd_bundling');
        $param["KD_ITEM"]       = $this->post('kd_item');
        $this->Main_model->data_sudahada($param,"TRANS_SPKBUNDLING");
        $param["NAMA_ITEM"]     = $this->post('nama_item');
        $param["JML_ITEM"]      = $this->post('jml_item');
        $param["HET_ITEM"]      = $this->post('het_item');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["CREATED_BY"]    = $this->post('created_by');
        
        // print_r($param);
        $this->resultdata("SP_TRANS_SPKBUNDLING_INSERT",$param,'post',TRUE);
    }

    /**
     * [spkbundling_put description]
     * @return [type] [description]
     */
    public function spkbundling_put(){
        $param = array();
        $param["NO_SPK"]            = $this->put('no_spk');
        $param["KD_BUNDLING"]       = $this->put('kd_bundling');
        $param["KD_ITEM"]           = $this->put('kd_item');
        $param["NAMA_ITEM"]         = $this->put('nama_item');
        $param["JML_ITEM"]          = $this->put('jml_item');
        $param["HET_ITEM"]          = $this->put('het_item');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SPKBUNDLING_UPDATE",$param,'put',TRUE);
    }
    /**
     * [spkbundling_delete description]
     * @return [type] [description]
     */
    public function spkbundling_delete(){
        $param = array();
        $param["NO_SPK"]            = $this->delete('no_spk');
        $param["KD_BUNDLING"]       = $this->delete('kd_bundling');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SPKBUNDLING_DELETE",$param,'delete',TRUE);
    }
    function approvalds_get(){
        $param=array();$query="";
        $id=($this->get("tp")=='SPK')?$this->get("id"):$this->get("no_trans");
        $tp=$this->get("tp");
        $apvby= $this->get('created_by');
        $apv_lvl= $this->get('apv_level');
        $query=$this->Custom_model->approvale_ds($id,$tp,$apvby,$apv_lvl);
        $this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_SPK",$param=array());
    }
    function spkbatal_get(){
        $no_spk= $this->get('no_spk');
        $kd_dealer = $this->get("kd_dealer");
        $this->Main_model->set_custom_query($this->Custom_model->batal_spk($no_spk,$kd_dealer));
        $this->resultdata("TRANS_SPK",$param=array());
    }
    /**
     * [spkbatal_put description]
     * @return [type] [description]
     */
    public function spkbatal_put($approval=null,$kembali=null){
        $param=array();
        $param["NO_SPK"] = $this->put("no_spk");
        $param["STATUS_SPK"] = ($this->put("status_spk"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        if($approval){
            if($kembali){
                $param["PAYBILL_REFF2"] = $this->put("paybill_reff2");
                $this->resultdata("SP_TRANS_SPK_KEMBALI",$param,'put',TRUE);
            }else{
                $this->resultdata("SP_TRANS_SPK_APPROVAL",$param,'put',TRUE);
                
            }
        }else{
            $this->resultdata("SP_TRANS_SPK_BATAL",$param,'put',TRUE);
        }
    }
    /**
     * data fee penjuaan unit
     */
    public function fee_jual_get(){
        $param=array();
        $kd_dealer= $this->get("kd_dealer");
        $groups = $this->get("groups");
        $query=$this->Custom_model->fee_penjualan($kd_dealer,$groups);
        $this->Main_model->set_custom_query($query);
        $this->resultdata("TRANS_SPK",$param=array());
    }
    /**
     * get data kartu keluarga
     */
    public function datakk_get(){
        $param = array();$search='';
        if($this->get("no_kk")){
            $param["NO_KK"]      = $this->get("no_kk");
        }
        if ($this->get("keyword")) {
            //$param= array();
            $search= array();
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
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        
        $this->resultdata("MASTER_CUSTOMER_KK",$param);
    }
    public function datakk_post(){
        $param=array();
        $param["NO_KK"]=$this->post("no_kk");
        $this->Main_model->data_sudahada($param,"MASTER_CUSTOMER_KK");
        $param["ALAMAT_KK"]=$this->post("alamat_kk");
        $param["RTRW_KK"]=$this->post("rtrw_kk");
        $param["KD_PROPINSI"]=$this->post("kd_propinsi");
        $param["KD_KABUPATEN"]=$this->post("kd_kabupaten");
        $param["KD_KECAMATAN"]=$this->post("kd_kecamatan");
        $param["KD_DESA"]=$this->post("kd_desa");
        $param["KETERANGAN"]=$this->post("keterangan");
        $param["CREATED_BY"]=$this->post("created_by");
        $this->resultdata("SP_MASTER_CUSTOMER_KK_INSERT",$param,'post',TRUE);
    }
    public function datakk_put(){
        $param=array();
        $param["NO_KK"]=$this->put("no_kk");
        $param["ALAMAT_KK"]=$this->put("alamat_kk");
        $param["RTRW_KK"]=$this->put("rtrw_kk");
        $param["KD_PROPINSI"]=$this->put("kd_propinsi");
        $param["KD_KABUPATEN"]=$this->put("kd_kabupaten");
        $param["KD_KECAMATAN"]=$this->put("kd_kecamatan");
        $param["KD_DESA"]=$this->put("kd_desa");
        $param["KETERANGAN"]=$this->put("keterangan");
        $param["LASTMODIFIED_BY"]=$this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_CUSTOMER_KK_UPDATE",$param,'put',TRUE);
    }
    public function datakk_delete(){
        $param = array();
        $param["NO_KK"]             = $this->delete('no_kk');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_CUSTOMER_KK_DELETE",$param,'delete',TRUE);
    }

    public function datakk_detail_get(){
        $param = array();$search='';
        if($this->get("no_kk")){
            $param["NO_KK"]      = $this->get("no_kk");
        }
        if($this->get("nama_anggota")){
            $param["NAMA_ANGGOTA"]      = $this->get("nama_anggota");
        }
        if($this->get("nik_anggota")){
            $param["NIK_ANGGOTA"]      = $this->get("nik_anggota");
        }
        if ($this->get("keyword")) {
            //$param= array();
            $search= array();
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
        
        $this->resultdata("MASTER_CUSTOMER_KK_DETAIL",$param);
    }
    public function datakk_detail_post(){
        $param=array();
        $param["NO_KK"]=$this->post("no_kk");
        $param["NAMA_ANGGOTA"]=$this->post("nama_anggota");
        $this->Main_model->data_sudahada($param,"MASTER_CUSTOMER_KK_DETAIL");
        $param["NIK_ANGGOTA"]=$this->post("nik_anggota");
        $param["JENIS_KELAMIN"]=$this->post("jenis_kelamin");
        $param["TGL_LAHIR"]=$this->post("tgl_lahir");
        $param["STATUS_DIKK"]=$this->post("status_dikk");
        $param["PENDIDIKAN_TERAKHIR"]=$this->post("pendidikan_terakhir");
        $param["PEKERJAAN"]=$this->post("pekerjaan");
        $param["NAMA_IBUKANDUNG"]=$this->post("nama_ibukandung");
        $param["CREATED_BY"]=$this->post("created_by");
        $this->resultdata("SP_MASTER_CUSTOMER_KK_DETAIL_INSERT",$param,'post',TRUE);
    }
    public function datakk_detail_put(){
        $param=array();
        $param["NO_KK"]=$this->put("no_kk");
        $param["NAMA_ANGGOTA"]=$this->put("nama_anggota");
        $param["NIK_ANGGOTA"]=$this->put("nik_anggota");
        $param["JENIS_KELAMIN"]=$this->put("jenis_kelamin");
        $param["TGL_LAHIR"]=$this->put("tgl_lahir");
        $param["STATUS_DIKK"]=$this->put("status_dikk");
        $param["PENDIDIKAN_TERAKHIR"]=$this->put("pendidikan_terakhir");
        $param["PEKERJAAN"]=$this->put("pekerjaan");
        $param["NAMA_IBUKANDUNG"]=$this->put("nama_ibukandung");
        $param["LASTMODIFIED_BY"]=$this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_CUSTOMER_KK_DETAIL_UPDATE",$param,'put',TRUE);
    }
    public function datakk_detail_delete(){
        $param = array();
        $param["ID"]             = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_CUSTOMER_KK_DETAIL_DELETE",$param,'delete',TRUE);
    }
    
    public function spk_detail_hadiah_get(){
        $param = array();$search='';
        if($this->get("spk_detail_id")){
            $param["SPK_DETAIL_ID"]     = $this->get("spk_detail_id");
        }
        if($this->get("kd_item")){
            $param["KD_ITEM"]     = $this->get("kd_item");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "SPK_DETAIL_ID" => $this->get("keyword"),
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
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SPK_DETAIL_HADIAH_VIEW",$param);
    }
    public function spkindent_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_item")){
            $param["KD_ITEM"]     = $this->get("kd_item");
        }
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("keyword")){
            //$param = array();
            $search = array(
                "NO_SPK" => $this->get("keyword"),
                "KD_CUSTOMER" => $this->get("keyword"),
                "KD_ITEM" => $this->get("keyword")
                );
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
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
        $this->resultdata("TRANS_SPK_INDENT",$param);
    }
    public function spkindent_post(){
        $param=array();
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["NO_TRANS"] = $this->post("no_trans");
        $param["NO_SPK"] = $this->post("no_spk");
        $this->Main_model->data_sudahada($param,"TRANS_SPK_INDENT");
        $param["TGL_TRANS"] = tglToSql($this->post("tgl_trans"));
        $param["KD_ITEM"] = $this->post("kd_item");
        $param["JUMLAH_UNIT"] = $this->post("jumlah_unit");
        $param["KD_CUSTOMER"] = $this->post("kd_customer");
        $param["KETERANGAN"] = $this->post("keterangan");
        $param["ETA_INDENT"] = tglToSql($this->post("eta_indent"));
        $param["CREATED_BY"]=$this->post("created_by");
        $this->resultdata("SP_TRANS_SPK_INDENT_INSERT",$param,'post',TRUE);
    }
    public function spkindent_put($upd_status=null){
        $param=array();
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["NO_TRANS"] = $this->put("no_trans");
        $param["NO_SPK"] = $this->put("no_spk");
        $param["KD_ITEM"] = $this->put("kd_item");
        $param["JUMLAH_UNIT"] = $this->put("jumlah_unit");
        $param["KETERANGAN"]    = $this->put("keterangan");
        $param["ETA_INDENT"]    = tglToSql($this->put("eta_indent"));
        $param["LASTMODIFIED_BY"]=$this->put("lastmodified_by");
        if($upd_status){
            $param["STATUS_INDENT"] = $this->put('status_indent');
            unset($param["KD_DEALER"]);
            unset($param["NO_SPK"]);
            unset($param["KD_ITEM"]);
            unset($param["JUMLAH_UNIT"]);
            unset($param["KETERANGAN"]);
            unset($param["ETA_INDENT"]);
            $this->resultdata("SP_TRANS_SPK_INDENT_STATUS_UPD",$param,'put',TRUE);
        }else{
            $this->resultdata("SP_TRANS_SPK_INDENT_UPDATE",$param,'put',TRUE);
        }
    }
    public function spkindent_delete(){
        $param=array();
        $param["NO_TRANS"] = $this->put("no_trans");
        $param["KETERANGAN"] = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SPK_INDENT_DELETE",$param,'delete',TRUE);
    }
}
?>