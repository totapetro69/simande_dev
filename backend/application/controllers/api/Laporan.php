<?php defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Laporan extends REST_Controller {
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
    function customer_get(){
        $param=array();$search="";
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"] = $this->get("kd_customer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_PROPINSI" =>$this->get("keyword"),
                "NAMA_KABUPATEN" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword"),
                "NAMA_SALES"    => $this->get("keyword")
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
        $this->resultdata("MASTER_CUSTOMER_VIEW",$param);
    }
    /**
     * [historyhargamotor_get description]
     * @return [type] [description]
     */
    public function historyhargamotor_get(){
        $param = array();$search='';
        if($this->get('kd_item')){
             $param["KD_ITEM"]     = $this->get("kd_item");
        }
        if($this->get("kd_kategory")){
            $param["KD_KATEGORY"]     = $this->get("kd_kategory");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("kd_wilayah")){
            $param["KD_WILAYAH"]     = $this->get("kd_wilayah");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_ITEM" =>$this->get("keyword"),
                "NAMA_ITEM" =>$this->get("keyword"),
                "KD_CATEGORY" =>$this->get("keyword"),
                "KD_WILAYAH" =>$this->get("keyword")
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
        $this->resultdata("HISTORY_HARGAMOTOR_VIEW",$param);
    }
    /**
     * [spkview_get description]
     * dalm spk view sudah termasuk
     *  - detail customer
     *  - hargamotors
     *  - diskon yng sedang aktif
     * @return [type] [description]
     */
    public function spkview_get(){
        $param = array();$search='';
        if($this->get('spkid')){
             $param["SPKID"]     = $this->get("spkid");
        }
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("type_penjualan")){
            $param["TYPE_PENJUALAN"] = $this->get("type_penjualan");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]  = $this->get("kd_sales");
        }
        /**
         * harus di join dengan Master group motor
         * LEFT JOIN MASTER_P_GROUPMOTOR AS MP
         */
        if($this->get("9_segmen")){
            $param["MP.SEMBILAN_SEGMEN"]   = $this->get("9_segmen");
        }
        if($this->get("kd_groupmotor")){
            $param["MP.KD_GROUPMOTOR"]   = $this->get("kd_groupmotor");
        }
        if($this->get("category")){
            $param["MP.CATEGORY_MOTOR"]   = $this->get("category");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_SO" =>$this->get("keyword"),
                "NO_SPK" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword"),
                "NAMA_SALES"    => $this->get("keyword")
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
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SPK_VIEW",$param);
    }
    function spkview_kendaraan_get(){
        $param = array();$search='';
        if($this->get('spk_id')){
             $param["SPK_ID"]     = $this->get("spk_id");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_SO" =>$this->get("keyword"),
                "NO_SPK" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword"),
                "NAMA_SALES"    => $this->get("keyword")
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
        $this->resultdata("TRANS_SPK_KENDARAAN_VIEW",$param);
    }
    /**
     * [saleskupon_get description]
     * @return [array] [daftar sales kupon yng di dapat customer]
     * berdasarkan criteria sales kupon yng aktif, sales kota dan sales kupon leasing
     * jika ketiga kriteria tersebut tidak mencakup dan berdasarkan dealer user login kupon akfif tidak muncul
     */
    public function saleskupon_get(){
        $param = array();
        $search='';
        if($this->get('kd_typemotor')){
             $param["KD_TYPEMOTOR"] = $this->get("kd_typemotor");
        }
        if($this->get('kd_item')){
             $param["KD_ITEM"] = $this->get("kd_item");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]  = $this->get("kd_dealer");
        }
        if($this->get("kd_leasing")){
            $param["KD_LEASING"] = $this->get("kd_leasing");
        }
        if($this->get("kd_saleskupon")){
            $param["KD_SALESKUPON"] =$this->get("kd_saleskupon");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_ITEM" =>$this->get("keyword"),
                "NAMA_SALESKUPON" =>$this->get("keyword"),
                "KD_DEALER" =>$this->get("keyword"),
                "KD_LEASING" =>$this->get("keyword")
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
        $this->resultdata("TRANS_KUPONMOTOR_VIEW",$param);
    }
    /**
     * [cddb_get description]
     * @return [array] [daftar customer db yng di dapat customer]
     */
    public function cddb_get(){
        $param = array();$search='';
        if($this->get("no_mesin")){
            $param["NO_MESIN"]  = $this->get("no_mesin");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]  = $this->get("no_rangka");
        }
        if($this->get("nama_item")){
            $param["NAMA_ITEM"]  = $this->get("nama_item");
        }
        if($this->get('kd_customer')){
             $param["KD_CUSTOMER"] = $this->get("kd_customer");
        }
        /*if($this->get("guest_no")){
            $param["TG.GUEST_NO"]   = $this->get("guest_no");
        }
        if($this->get("test_drive")){
            $param["TG.TEST_DRIVE"]   = $this->get("test_drive");
        }*/
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_MESIN" =>$this->get("keyword"),
                "NO_RANGKA" =>$this->get("keyword"),
                "NAMA_ITEM" =>$this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword")
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
        $this->resultdata("TRANS_CUSTOMER_DATABASE",$param);
    }
    /**
     * [cddb_postput description]
     * @return [type] [description]
     */
    public function cddb_post(){
        $param=array();
        $param["NO_MESIN"]    = $this->post('no_mesin');
        $param["NO_RANGKA"]       = $this->post('no_rangka');
        $param["KD_ITEM"]   = $this->post('kd_item');
        $param["KD_TYPEMOTOR"]   = $this->post('kd_typemotor');
        $param["NAMA_ITEM"]   = $this->post('nama_item');
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $param["NAMA_CUSTOMER"]   = $this->post('nama_customer');
        $param["JENIS_KELAMIN"]   = $this->post('jenis_kelamin');
        $param["TGL_LAHIR"]   = tglToSql($this->post('tgl_lahir'));
        $param["TGL_PEMBUATAN_NPWP"]   = tglToSql($this->post('expedisi'));
        $param["NO_KTP"]   = $this->post('no_ktp');
        $param["NO_NPWP"]    = $this->post('no_npwp');
        $param["ALAMAT_SURAT"]       = $this->post('alamat_surat');
        $param["KELURAHAN"]   = $this->post('kelurahan');
        $param["KD_KECAMATAN"]   = $this->post('kd_kecamatan');
        $param["KD_KOTA"]   = $this->post('kd_kota');
        $param["KODE_POS"]   = $this->post('kode_pos');
        $param["KD_PROPINSI"]   = $this->post('kd_propinsi');
        $param["KD_AGAMA"]   = $this->post('kd_agama');
        $param["KD_PEKERJAAN"]   = $this->post('kd_pekerjaan');
        $param["PENGELUARAN"]    = $this->post('pengeluaran');
        $param["KD_PENDIDIKAN"]       = $this->post('kd_pendidikan');
        $param["NAMA_PENANGGUNGJAWAB"]   = $this->post('nama_penanggungjawab');
        $param["NO_HP"]   = $this->post('no_hp');
        $param["NO_TELEPON"]   = $this->post('no_telepon');
        $param["STATUS_DIHUBUNGI"]   = $this->post('status_dihubungi');
        $param["EMAIL"]   = $this->post('email');
        $param["STATUS_RUMAH"]   = $this->post('status_rumah');
        $param["STATUS_NOHP"]   = $this->post('status_nohp');
        $param["AKUN_FB"]   = $this->post('akun_fb');
        $param["AKUN_TWITTER"]    = $this->post('akun_twitter');
        $param["AKUN_INSTAGRAM"]       = $this->post('akun_instagram');
        $param["AKUN_YOUTUBE"]   = $this->post('akun_youtube');
        $param["HOBI"]   = $this->post('hobi');
        $param["KARAKTERISTIK_KONSUMEN"]   = $this->post('karakteristik_konsumen');
        $param["ID_REFFERAL"]   = $this->post('id_refferal');
        $param["NAMA_PROPINSI"]   = $this->post('nama_propinsi');
        $param["KD_KABUPATEN"]   = $this->post('kd_kabupaten');
        $param["NAMA_KABUPATEN"]   = $this->post('nama_kabupaten');
        $param["NAMA_KECAMATAN"]    = $this->post('nama_kecamatan');
        $param["KD_DESA"]       = $this->post('kd_desa');
        $param["NAMA_DESA"]   = $this->post('nama_desa');
        $param["ALAMAT"]   = $this->post('alamat');
        $param["NAMA_SALES"]   = $this->post('nama_sales');
        $param["KD_DEALER"]   = $this->post('kd_dealer');
        $param["KD_MAINDEALER"]   = $this->post('kd_maindealer');
        $param["SPK_ID"]   = $this->post('spk_id');
        $param["STATUS_DOWNLOAD"]   = $this->post('status_download');
        $param["CREATED_BY"]= $this->post('created_by');
        $this->resultdata("SP_TRANS_CDDB_INSERT",$param,'put',TRUE);
    }
    /**
     * [cddb_put description]
     * @return [type] [description]
     */
    public function cddb_put(){
        $param=array();
        $param["ID"]    = $this->put('id');
        $param["NO_MESIN"]    = $this->put('no_mesin');
        $param["NO_RANGKA"]       = $this->put('no_rangka');
        $param["KD_ITEM"]   = $this->put('kd_item');
        $param["KD_TYPEMOTOR"]   = $this->put('kd_typemotor');
        $param["NAMA_ITEM"]   = $this->put('nama_item');
        $param["KD_CUSTOMER"]   = $this->put('kd_customer');
        $param["NAMA_CUSTOMER"]   = $this->put('nama_customer');
        $param["JENIS_KELAMIN"]   = $this->put('jenis_kelamin');
        $param["TGL_LAHIR"]   = tglToSql($this->put('tgl_lahir'));
        $param["TGL_PEMBUATAN_NPWP"]   = tglToSql($this->put('expedisi'));
        $param["NO_KTP"]   = $this->put('no_ktp');
        $param["NO_NPWP"]    = $this->put('no_npwp');
        $param["ALAMAT_SURAT"]       = $this->put('alamat_surat');
        $param["KELURAHAN"]   = $this->put('kelurahan');
        $param["KD_KECAMATAN"]   = $this->put('kd_kecamatan');
        $param["KD_KOTA"]   = $this->put('kd_kota');
        $param["KODE_POS"]   = $this->put('kode_pos');
        $param["KD_PROPINSI"]   = $this->put('kd_propinsi');
        $param["KD_AGAMA"]   = $this->put('kd_agama');
        $param["KD_PEKERJAAN"]   = $this->put('kd_pekerjaan');
        $param["PENGELUARAN"]    = $this->put('pengeluaran');
        $param["KD_PENDIDIKAN"]       = $this->put('kd_pendidikan');
        $param["NAMA_PENANGGUNGJAWAB"]   = $this->put('nama_penanggungjawab');
        $param["NO_HP"]   = $this->put('no_hp');
        $param["NO_TELEPON"]   = $this->put('no_telepon');
        $param["STATUS_DIHUBUNGI"]   = $this->put('status_dihubungi');
        $param["EMAIL"]   = $this->put('email');
        $param["STATUS_RUMAH"]   = $this->put('status_rumah');
        $param["STATUS_NOHP"]   = $this->put('status_nohp');
        $param["AKUN_FB"]   = $this->put('akun_fb');
        $param["AKUN_TWITTER"]    = $this->put('akun_twitter');
        $param["AKUN_INSTAGRAM"]       = $this->put('akun_instagram');
        $param["AKUN_YOUTUBE"]   = $this->put('akun_youtube');
        $param["HOBI"]   = $this->put('hobi');
        $param["KARAKTERISTIK_KONSUMEN"]   = $this->put('karakteristik_konsumen');
        $param["ID_REFFERAL"]   = $this->put('id_refferal');
        $param["NAMA_PROPINSI"]   = $this->put('nama_propinsi');
        $param["KD_KABUPATEN"]   = $this->put('kd_kabupaten');
        $param["NAMA_KABUPATEN"]   = $this->put('nama_kabupaten');
        $param["NAMA_KECAMATAN"]    = $this->put('nama_kecamatan');
        $param["KD_DESA"]       = $this->put('kd_desa');
        $param["NAMA_DESA"]   = $this->put('nama_desa');
        $param["ALAMAT"]   = $this->put('alamat');
        $param["NAMA_SALES"]   = $this->put('nama_sales');
        $param["KD_DEALER"]   = $this->put('kd_dealer');
        $param["KD_MAINDEALER"]   = $this->put('kd_maindealer');
        $param["SPK_ID"]   = $this->put('spk_id');
        $param["STATUS_DOWNLOAD"]   = $this->put('status_download');
        $param["LASTMODIFIED_BY"]= $this->put('lastmodified_by');
        $this->resultdata("SP_TRANS_CDDB_UPDATE",$param,'put',TRUE);
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
     public function stockmotor_get(){
        $param = array();$search='';
        if($this->get('kd_item')){
             $param["KD_ITEM"]     = $this->get("kd_item");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("nor_rangka");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_MESIN" => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword"),
                "KD_ITEM" => $this->get("keyword"),
            );
        }
        if($this->get("having")){
            $this->Main_model->set_havings($this->get("having"));
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
        $this->resultdata("TRANS_STOCKMOTOR",$param);
    }
     /**
     * [plhlo_get description]
     * @return [type] [description]
     */
    public function plhlo_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_pesanancustomer")){
            $param["NO_PESANANCUSTOMER"]     = $this->get("no_pesanancustomer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"     => $this->get("keyword"),
                "NO_PESANANCUSTOMER"   => $this->get("keyword")
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
        $this->resultdata("TRANS_PLHLO",$param);
    }
    /**
     * [plhlo_post description]
     * @return [type] [description]
     */
    public function plhlo_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"TRANS_PLHLO");
        $param["NO_PESANANANCUSTOMER"]   = $this->post('no_pesanancustomer');
        $param["TGL_PESANANANCUSTOMER"]       = tglToSql($this->post('tgl_pesananancustomer'));
        $param["KD_MAINDEALER"]  = $this->post('kd_maindealer');
        $param["NO_PODEALER_KEMD"]      = $this->post('no_podealer_kemd');
        $param["TGL_PODEALER_KEMD"]      = tglToSql($this->post('tgl_podealer_kemd'));
        $param["PART_NUMBER"]        = $this->post('part_number');
        $param["TGL_SUPPLY_DARIMD"]    = tglToSql($this->post('tgl_supply_darimd'));
        $param["QUANTITY_SUPPLY_DARIMD"]    = $this->post('quantity_supply_darimd');
        $param["QUANTITY_PESANANANCUSTOMER"] = $this->post('quantity_pesanancustomer');
        $param["NO_SO"]      = $this->post('no_so');
        $param["TGL_SO_KEKONSUMEN"] = tglToSql($this->post('tgl_so_kekonsumen'));
        $param["QUANTITY_SO_KEKONSUMEN"]    = $this->post('quantity_so_kekonsumen');
        $param["NAMA_KONSUMEN"]    = $this->post('nama_konsumen');
        $param["NOTEL_KONSUMEN"]      = $this->post('notel_konsumen');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_PLHLO_INSERT",$param,'post',TRUE);
    }
    /**
     * [plhlo_put description]
     * @return [type] [description]
     */
    public function plhlo_put(){
        $param = array();
        $param["ID"]      = $this->put("id");
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["NO_PESANANANCUSTOMER"]   = $this->put('no_pesanancustomer');
        $param["TGL_PESANANANCUSTOMER"]       = tglToSql($this->put('tgl_pesananancustomer'));
        $param["KD_MAINDEALER"]  = $this->put('kd_maindealer');
        $param["NO_PODEALER_KEMD"]      = $this->put('no_podealer_kemd');
        $param["TGL_PODEALER_KEMD"]      = tglToSql($this->put('tgl_podealer_kemd'));
        $param["PART_NUMBER"]        = $this->put('part_number');
        $param["TGL_SUPPLY_DARIMD"]    = tglToSql($this->put('tgl_supply_darimd'));
        $param["QUANTITY_SUPPLY_DARIMD"]    = $this->put('quantity_supply_darimd');
        $param["QUANTITY_PESANANANCUSTOMER"] = $this->put('quantity_pesanancustomer');
        $param["NO_SO"]      = $this->put('no_so');
        $param["TGL_SO_KEKONSUMEN"]   = tglToSql($this->put('tgl_so_kekonsumen'));
        $param["QUANTITY_SO_KEKONSUMEN"]    = $this->put('quantity_so_kekonsumen');
        $param["NAMA_KONSUMEN"]    = $this->put('nama_konsumen');
        $param["NOTEL_KONSUMEN"]      = $this->put('notel_konsumen');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PLHLO_UPDATE",$param,'put',TRUE);
    }
    public function plhlo_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PLHLO_DELETE",$param,'delete',TRUE);
    } 
    /**
     * [petd_get description]
     * @return [type] [description]
     */
    public function petd_get(){
       $param = array();$search='';
       if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("nopomd_ke_ahm")){
            $param["NOPOMD_KE_AHM"]     = $this->get("nopomd_ke_ahm");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_MAINDEALER"     => $this->get("keyword"),
                "NOPOMD_KE_AHM"   => $this->get("keyword"),
                "KD_DEALER"   => $this->get("keyword")
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
        $this->resultdata("TRANS_PETD",$param);
    }
    /**
     * [petd_post description]
     * @return [type] [description]
     */
    public function petd_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["NOPOMD_KE_AHM"]   = $this->post('nopomd_ke_ahm');
        $param["TGLPOMD_KE_AHM"]       = tglToSql($this->post('tglpomd_keahm'));
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $param["PART_NUMBER"]      = $this->post('part_number');
        $param["PART_DESKRIPSI"]      = $this->post('part_deskripsi');
        $param["QUANTITYPO_AWAL"]        = $this->post('quantitypo_awal');
        $param["QUANTITYBO_AHM"]        = $this->post('quantitybo_ahm');
        $param["ETDAHM_AWAL"]    = tglToSql($this->post('etdahm_awal'));
        $param["ETDAHM_REVISED"] = tglToSql($this->post('etdahm_revised'));
        $this->Main_model->data_sudahada($param,"TRANS_PETD");
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_PETD_INSERT",$param,'post',TRUE);
    }
    /**
     * [petd_put description]
     * @return [type] [description]
     */
    public function petd_put(){
        $param = array();
        $param["ID"]      = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["NOPOMD_KE_AHM"]   = $this->put('nopomd_ke_ahm');
        $param["TGLPOMD_KE_AHM"]       = tglToSql($this->put('tglpomd_keahm'));
        $param["KD_DEALER"]  = $this->put('kd_dealer');
        $param["PART_NUMBER"]      = $this->put('part_number');
        $param["PART_DESKRIPSI"]      = $this->put('part_deskripsi');
        $param["QUANTITYPO_AWAL"]        = $this->put('quantitypo_awal');
        $param["QUANTITYBO_AHM"]        = $this->put('quantitybo_ahm');
        $param["ETDAHM_AWAL"]    = tglToSql($this->put('etdahm_awal'));
        $param["ETDAHM_REVISED"]   = tglToSql($this->put('etdahm_revised'));
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PETD_UPDATE",$param,'put',TRUE);
    }
    public function petd_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PETD_DELETE",$param,'delete',TRUE);
    } 
    /**
     * [pdetd_get description]
     * @return [type] [description]
     */
    public function pdetd_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "PART_NUMBER"     => $this->get("keyword")
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
        $this->resultdata("TRANS_PDETD",$param);
    }
    /**
     * [pdetd_post description]
     * @return [type] [description]
     */
    public function pdetd_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALERAHM"]     = $this->post('kd_dealerahm');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["NOPODEALER_KE_MD"]   = $this->post('no_podealer_ke_md');
        $param["PART_NUMBER"]        = $this->post('part_number');
        $this->Main_model->data_sudahada($param,"TRANS_PDETD");
        $param["TGLPODEALER_KE_MD"]       = tglToSql($this->post('tglpodealer_ke_md'));
        $param["NOPOMD_KE_AHM"]  = $this->post('nopomd_ke_ahm');
        $param["TGLPOMD_KE_AHM"]      = tglToSql($this->post('tglpomd_ke_ahm'));
        $param["PART_DESKRIPSI"]      = $this->post('part_deskripsi');
        $param["QUANTITYPO_AWAL"]        = $this->post('quantitypo_awal');
        $param["QUANTITYBO_AHM"]        = $this->post('quantitybo_ahm');
        $param["ETDAHM_AWAL"]    = tglToSql($this->post('etdahm_awal'));
        $param["ETDAHM_REVISED"] = tglToSql($this->post('etdahm_revised'));
        $param["NOPESANAN_KONSUMEN"]    = $this->post('nopesanan_konsumen');
        $param["TGLPESANAN_KONSUMEN"] = tglToSql($this->post('tglpesanan_konsumen'));
        $param["NAMA_KONSUMEN"]    = $this->post('nama_konsumen');
        $param["NOTEL_KONSUMEN"]      = $this->post('notel_konsumen');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_PDETD_INSERT",$param,'post',TRUE);
    }
    /**
     * [pdetd_put description]
     * @return [type] [description]
     */
    public function pdetd_put(){
        $param = array();
        $param["ID"]      = $this->put("id");
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["NOPODEALER_KE_MD"]   = $this->put('no_podealer_ke_md');
        $param["TGLPODEALER_KE_MD"]       = tglToSql($this->put('tglpodealer_ke_md'));
        $param["NOPOMD_KE_AHM"]  = $this->put('nopomd_ke_ahm');
        $param["TGLPOMD_KE_AHM"]      = tglToSql($this->put('tglpomd_ke_ahm'));
        $param["PART_NUMBER"]        = $this->put('part_number');
        $param["PART_DESKRIPSI"]      = $this->put('part_deskripsi');
        $param["QUANTITYPO_AWAL"]        = $this->put('quantitypo_awal');
        $param["QUANTITYBO_AHM"]        = $this->put('quantitybo_ahm');
        $param["ETDAHM_AWAL"]    = tglToSql($this->put('etdahm_awal'));
        $param["ETDAHM_REVISED"]   = tglToSql($this->put('etdahm_revised'));
        $param["NOPESANAN_KONSUMEN"]    = $this->put('nopesanan_konsumen');
        $param["TGLPESANAN_KONSUMEN"]   = tglToSql($this->put('tglpesanan_konsumen'));
        $param["NAMA_KONSUMEN"]    = $this->put('nama_konsumen');
        $param["NOTEL_KONSUMEN"]      = $this->put('notel_konsumen');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALERAHM"]     = $this->put('kd_dealerahm');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PDETD_UPDATE",$param,'put',TRUE);
    }
    public function pdetd_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PDETD_DELETE",$param,'delete',TRUE);
    }
    /**
     * [uminv_get description]
     * @return [type] [description]
     */
    public function uminv_get(){
       $param = array();$search='';
       /*if ($this->get("kd_dealer")) {
           $param["KD_DEALER"] = $this->get("kd_dealer");
       }*/
       if($this->get("no_inv")){
            $param["NO_INV"]     = $this->get("no_inv");
        }
        if($this->get("kd_tipe")){
            $param["KD_TIPE"]     = $this->get("kd_tipe");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_INV"     => $this->get("keyword"),
                "KD_TIPE"   => $this->get("keyword")
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
        $this->resultdata("TRANS_UMINV",$param);
    }
    /**
     * [uminv_post description]
     * @return [type] [description]
     */
    public function uminv_post(){
        $param = array();
        $param["NO_INV"]     = $this->post('no_inv');
        $param["KD_TIPE"]  = $this->post('kd_tipe');
        $param["KD_CABANGDEALER"] = $this->post('kd_cabangdealer');
        $param["KD_WARNA"]  = $this->post('kd_warna');
        $this->Main_model->data_sudahada($param,"TRANS_UMINV");
        $param["TGL_INV"]   = tglToSql($this->post('tgl_inv'));
        $param["KD_DEALER"] = $this->post('kd_dealer');
        $param["QTY"]        = $this->post('qty');
        $param["AMOUNT"]    = $this->post('amount');
        $param["MPPN"] = $this->post('mppn');
        $param["MPRICE"]    = $this->post('mprice');
        $param["MDISCOUNT"]    = $this->post('mdiscount');
        $param["NO_REFF"]      = $this->post('no_reff');
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_UMINV_INSERT",$param,'post',TRUE);
    }
    /**
     * [uminv_put description]
     * @return [type] [description]
     */
    public function uminv_put(){
        $param = array();
        //$param["ID"]      = $this->put("id");
        $param["NO_INV"]     = $this->put('no_inv');
        $param["TGL_INV"]   = tglToSql($this->put('tgl_inv'));
        $param["KD_TIPE"]  = $this->put('kd_tipe');
        $param["KD_WARNA"]        = $this->put('kd_warna');
        $param["KD_DEALER"]      = $this->put('kd_dealer');
        $param["KD_CABANGDEALER"]        = $this->put('kd_cabangdealer');
        $param["QTY"]        = $this->put('qty');
        $param["AMOUNT"]    = $this->put('amount');
        $param["MPPN"]   = $this->put('mppn');
        $param["MPRICE"]    = $this->put('mprice');
        $param["MDISCOUNT"]    = $this->put('mdiscount');
        $param["NO_REFF"]      = $this->put('no_reff');
        $param["KD_MAINDEALER"]         = $this->put("kd_maindealer"); 
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UMINV_UPDATE",$param,'put',TRUE);
    }
    public function uminv_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_UMINV_DELETE",$param,'delete',TRUE);
    }
    /**
     * [udcp_get description]
     * @return [type] [description]
     */
    public function udcp_get(){
       $param = array();$search='';
       if($this->get("kd_guestbook")){
            $param["KD_GUESTBOOK"]     = $this->get("kd_guestbook");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_GUESTBOOK"     => $this->get("keyword"),
                "KD_CUSTOMER"   => $this->get("keyword")
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
        $this->resultdata("TRANS_UDCP",$param);
    }
    /**
     * [udcp_post description]
     * @return [type] [description]
     */
    public function udcp_post(){
        $param = array();
        $param["KD_GUESTBOOK"]     = $this->post('kd_guestbook');
        $this->Main_model->data_sudahada($param,"TRANS_UDCP");
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $param["HONDA_ID"]  = $this->post('honda_id');
        $param["KD_WARNA"]        = $this->post('kd_warna');
        $param["KD_TIPE"]      = $this->post('kd_tipe');
        $param["TANGGAL"]        = tglToSql($this->post('tanggal'));
        $param["NAMA_CUSTOMER"]        = $this->post('nama_customer');
        $param["ALAMAT"]    = $this->post('alamat');
        $param["NO_TELP"] = $this->post('no_telp');
        $param["RENCANA_PEMBAYARAN"]    = $this->post('rencana_pembayaran');
        $param["JENIS_CUSTOMER"]    = $this->post('jenis_customer');
        $param["STATUS"]      = $this->post('status');
        $param["KETERANGAN"]         = $this->post("keterangan");
        $param["NAMA_SALESFORCE"]         = $this->post("nama_salesforce");
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");  
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_UDCP_INSERT",$param,'post',TRUE);
    }
    /**
     * [udcp_put description]
     * @return [type] [description]
     */
    public function udcp_put(){
        $param = array();
        $param["ID"]      = $this->put("id");
        $param["KD_GUESTBOOK"]     = $this->put('kd_guestbook');
        $param["KD_CUSTOMER"]   = $this->put('kd_customer');
        $param["HONDA_ID"]  = $this->put('honda_id');
        $param["KD_WARNA"]        = $this->put('kd_warna');
        $param["KD_TIPE"]      = $this->post('kd_tipe');
        $param["TANGGAL"]        = tglToSql($this->put('tanggal'));
        $param["NAMA_CUSTOMER"]        = $this->put('nama_customer');
        $param["ALAMAT"]    = $this->put('alamat');
        $param["NO_TELP"]   = $this->put('no_telp');
        $param["RENCANA_PEMBAYARAN"]    = $this->put('rencana_pembayaran');
        $param["JENIS_CUSTOMER"]    = $this->put('jenis_customer');
        $param["STATUS"]      = $this->put('status');
        $param["KETERANGAN"]         = $this->put("keterangan"); 
        $param["NAMA_SALESFORCE"]         = $this->put("nama_salesforce"); 
        $param["KD_MAINDEALER"]         = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");  
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UDCP_UPDATE",$param,'put',TRUE);
    }
    public function udcp_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_UDCP_DELETE",$param,'delete',TRUE);
    }
    /**
     * [pdpo_get description]
     * @return [type] [description]
     */
    public function pdpo_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_po")){
            $param["NO_PO"]     = $this->get("no_po");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"     => $this->get("keyword"),
                "NO_PO"   => $this->get("keyword")
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
        $this->resultdata("TRANS_PDPO",$param);
    }
    /**
     * [pdpo_post description]
     * @return [type] [description]
     */
    public function pdpo_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["NO_PO"]   = $this->post('no_po');
        $this->Main_model->data_sudahada($param,"TRANS_PDPO");
        $param["TGL_PO"]  = tglToSql($this->post('tgl_po'));
        $param["JENIS_ORDER"]        = $this->post('jenis_order');
        $param["SEQUENCE"]      = $this->post('sequence');
        $param["NO_PART"]        = $this->post('no_part');
        $param["QTY"]        = $this->post('qty');
        $param["HARGA_PCS"]    = $this->post('harga_pcs');
        $param["NAMA_KONSUMEN"] = $this->post('nama_konsumen');
        $param["ALAMAT_KONSUMEN"]    = $this->post('alamat_konsumen');
        $param["KOTA"]    = $this->post('kota');
        $param["KODE_POS"]      = $this->post('kode_pos');
        $param["TYPE_MOTOR"]         = $this->post("type_motor");
        $param["TAHUN"]         = $this->post("tahun");
        $param["NO_TELP"]         = $this->post("no_telp");
        $param["VOR"]         = $this->post("vor");
        $param["JR"]         = $this->post("jr");    
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_PDPO_INSERT",$param,'post',TRUE);
    }
    /**
     * [pdss_get description]
     * @return [type] [description]
     */
    public function pdss_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"     => $this->get("keyword"),
                "PART_NUMBER"   => $this->get("keyword")
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
        $this->resultdata("TRANS_PDSS",$param);
    }
    /**
     * [pdss_post description]
     * @return [type] [description]
     */
    public function pdss_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["PART_NUMBER"]   = $this->post('no_po');
        $this->Main_model->data_sudahada($param,"TRANS_PDSS");
        $param["TANGGAL"]  = tglToSql($this->post('tanggal'));
        $param["QTY_ON_HAND"]        = $this->post('qty_on_hand');
        $param["QTY_SALES"]      = $this->post('qty_sales');
        $param["QTY_SIM_PARTS"]        = $this->post('qty_sim_parts');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_PDSS_INSERT",$param,'post',TRUE);
    }
    /**
     * [pdss_put description]
     * @return [type] [description]
     */
    public function pdss_put(){
        $param = array();
        $param["ID"]      = $this->put("id");
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["PART_NUMBER"]   = $this->put('no_po');
        $param["TANGGAL"]  = tglToSql($this->put('tanggal'));
        $param["QTY_ON_HAND"]        = $this->put('qty_on_hand');
        $param["QTY_SALES"]      = $this->put('qty_sales');
        $param["QTY_SIM_PARTS"]        = $this->put('qty_sim_parts'); 
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PDSS_UPDATE",$param,'put',TRUE);
    }
    public function pdss_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PDSS_DELETE",$param,'delete',TRUE);
    }
    /**
     * [spk_detailkendaraan_sd_put description]
     * @return [type] [description]
     */
    public function spk_detailkendaraan_sd_put(){
        $param = array();
        $param["NO_MESIN"]       = $this->put('no_mesin');
        $param["NO_RANGKA"]      = $this->put('no_rangka');
        $param["STATUS_DOWNLOAD"]= $this->put('status_download');
        $param["NAMA_FILE"]      = $this->put('nama_file');  
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SPK_DETAILKENDARAAN_SD_UPDATE",$param,'put',TRUE);
    }
    /**
     * [historyservice_get description]
     * @return [type] [description]
     */
    public function historyservice_get(){
       $param = array();$search='';
       if($this->get("flag_jp")){
            $param["FLAG_JP"]     = $this->get("flag_jp");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_POLISI"  => $this->get("keyword"),
                "NO_MESIN"   => $this->get("keyword")
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
        $this->resultdata("TRANS_HISTORYSERVICE",$param);
    }
    /**
     * [historyservice_post description]
     * @return [type] [description]
     */
    public function historyservice_post(){
        $param = array();
        $param["NAMA_CUSTOMER"]     = $this->post('nama_customer');
        $param["NO_MESIN"]   = $this->post('no_mesin');
        $param["NO_RANGKA"]        = $this->post('no_rangka');
        $param["NO_POLISI"]      = $this->post('no_polisi');
        $this->Main_model->data_sudahada($param,"TRANS_HISTORYSERVICE");
        $param["FLAG_JP"]     = $this->post('flag_jp');
        $param["TGL_TRANS"]     = tglToSql($this->post('tgl_trans'));
        $param["TIPE_PKB"]        = $this->post('tipe_pkb');
        $param["PROBLEM"]        = $this->post('problem');
        $param["ID_JOB"]        = $this->post('id_job');
        $param["KETERANGAN_JOB"]        = $this->post('keterangan_job');
        $param["PART_NUMBER"]        = $this->post('part_number');
        $param["QTY"]        = $this->post('qty');
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");  
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_HISTORYSERVICE_INSERT",$param,'post',TRUE);
    }
    /**
     * [historiservice_put description]
     * @return [type] [description]
     */
    public function historyservice_put(){
        $param = array();
        $param["ID"]      = $this->put("id");
        $param["TGL_TRANS"]     = tglToSql($this->put('tgl_trans'));
        $param["FLAG_JP"]     = $this->put('flag_jp');
        $param["NAMA_CUSTOMER"]     = $this->put('nama_customer');
        $param["NO_MESIN"]   = $this->put('no_mesin');
        $param["NO_RANGKA"]        = $this->put('no_rangka');
        $param["NO_POLISI"]      = $this->put('no_polisi');
        $param["TIPE_PKB"]        = $this->put('tipe_pkb');
        $param["PROBLEM"]        = $this->put('problem');
        $param["ID_JOB"]        = $this->put('id_job');
        $param["KETERANGAN_JOB"]        = $this->put('keterangan_job');
        $param["PART_NUMBER"]        = $this->put('part_number');
        $param["QTY"]        = $this->put('qty'); 
        $param["KD_MAINDEALER"]         = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");  
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_HISTORYSERVICE_UPDATE",$param,'put',TRUE);
    }
    public function historyservice_delete(){
        $param = array();
        $param["ID"]      = $this->delete("id");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_HISTORYSERVICE_DELETE",$param,'delete',TRUE);
    }
    /**
     * [exc_ksp_get description]
     * @return [type] [description]
     */
    public function exc_ksp_get(){
       $param = array();$search='';
       if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NIK"  => $this->get("keyword"),
                "NO_MESIN"   => $this->get("keyword")
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
        $this->resultdata("TRANS_EXC_KSP",$param);
    }
    /**
     * [exc_ksp_post description]
     * @return [type] [description]
     */
    public function exc_ksp_post(){
        $param = array();
        $param["NIK"]           = $this->post('nik');
        $param["NO_MESIN"]      = $this->post('no_mesin');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"TRANS_EXC_KSP");
        $param["PERIODE_AWAL"]  = tglToSql($this->post('periode_awal'));
        $param["PERIODE_AKHIR"] = tglToSql($this->post('periode_akhir'));
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_EXC_KSP_INSERT",$param,'post',TRUE);
    }
    /**
     * [exc_ksp_put description]
     * @return [type] [description]
     */
    public function exc_ksp_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NIK"]               = $this->put('nik');
        $param["PERIODE_AWAL"]      = tglToSql($this->put('periode_awal'));
        $param["PERIODE_AKHIR"]     = tglToSql($this->put('periode_akhir')); 
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EXC_KSP_UPDATE",$param,'put',TRUE);
    }
    public function exc_ksp_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_EXC_KSP_DELETE",$param,'delete',TRUE);
    }
    /**put
     * [exc_penalty_get description]
     * @return [type] [description]
     */
    public function exc_penalty_get(){
       $param = array();$search='';
       if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NIK"  => $this->get("keyword"),
                "NO_MESIN"   => $this->get("keyword")
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
        $this->resultdata("TRANS_EXC_PENALTY",$param);
    }
    /**
     * [exc_penalty_post description]
     * @return [type] [description]
     */
    public function exc_penalty_post(){
        $param = array();
        $param["NIK"]           = $this->post('nik');
        $param["NO_MESIN"]      = $this->post('no_mesin');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"TRANS_EXC_PENALTY");
        $param["TGL"]           = tglToSql($this->post('tgl'));
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_TRANS_EXC_PENALTY_INSERT",$param,'post',TRUE);
    }
    /**
     * [exc_penalty_put description]
     * @return [type] [description]
     */
    public function exc_penalty_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NIK"]               = $this->put('nik');
        $param["TGL"]               = tglToSql($this->put('tgl'));
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EXC_PENALTY_UPDATE",$param,'put',TRUE);
    }
    /**
     * [exc_penalty_delete description]
     * @return [type] [description]
     */
    public function exc_penalty_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_EXC_PENALTY_DELETE",$param,'delete',TRUE);
    }
    /**
     * [laporan_lbb_get description]
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    function laporan_lbb_get($service=null){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        if($service==true){
            $this->Main_model->set_custom_query($this->Custom_model->wp_unitentry($kd_dealer,$tahun,$bulan));
        }else{
            $this->Main_model->set_custom_query($this->Custom_model->wp_revenue($kd_dealer,$tahun,$bulan));
        }
        $this->resultdata("TRANS_PKB",$param=array());
    }
    /**
     * [laporan_lbb_cum_get description]
     * @return [type] [description]
     */
    function laporan_lbb_cum_get(){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        $this->Main_model->set_custom_query($this->Custom_model->wp_recaptahun($kd_dealer,$tahun,$bulan));
        $this->resultdata("TRANS_PKB",$param=array());
    }
    /**
     * [laporan_sdlbb_get description]
     * @return [type] [description]
     */
    function laporan_sdlbb_get(){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        $hariKerja=count(working_days($tahun,$bulan));
        $this->Main_model->set_custom_query($this->Custom_model->wp_sdlbbfiles($kd_dealer,$tahun,$bulan,$hariKerja));
        $this->resultdata("TRANS_PKB",$param=array());
    }
    /**
     * [laporan_lbb2_get description]
     * @return [type] [description]
     */
    function laporan_lbb2_get(){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        $hariKerja=count(working_days($tahun,$bulan));
        $this->Main_model->set_custom_query($this->Custom_model->wp_rekapmekanik($kd_dealer,$tahun,$bulan,$hariKerja));
        $this->resultdata("TRANS_PKB",$param=array());
    }
    /**
     * [laporan_lbb2_get description]
     * @return [type] [description]
     */
    function biayabengkel_get(){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        $this->Main_model->set_custom_query($this->Custom_model->wp_biayaopr($kd_dealer,$tahun,$bulan));
        $this->resultdata("TRANS_PKB",$param=array());
    }
    function customerservice_get(){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        $this->Main_model->set_custom_query($this->Custom_model->wp_customer($kd_dealer,$tahun,$bulan));
        $this->resultdata("TRANS_PKB",$param=array());
    }
    /**
     * [weekly_gb_get description]
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    public function weekly_crm_get(){
        $kd_dealer  = $this->get("kd_dealer");
        $tgl_awal   = $this->get("tgl_awal");
        $tgl_akhir  = $this->get("tgl_akhir");
        // var_dump($_GET);exit;
        $this->Main_model->set_custom_query($this->Custom_model->wk_crm($kd_dealer,$tgl_awal,$tgl_akhir));
        $this->resultdata("TRANS_GUESTBOOK",$param=array());
    }
    /**
     * [rpk_get description]
     * @return [type] [description]
     */
    public function rpk_get(){
       $param = array();$search='';
       if($this->get("kd_rpk")){
            $param["KD_RPK"]     = $this->get("kd_rpk");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_RPK"  => $this->get("keyword"),
                "KD_MAINDEALER"   => $this->get("keyword")
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
        $this->resultdata("MASTER_RPK",$param);
    }
    /**
     * [rpk_post description]
     * @return [type] [description]
     */
    public function rpk_post(){
        $param = array();
        $param["KD_RPK"]        = $this->post('kd_rpk');
        $param["PROSENTASE"]    = $this->post('prosentase');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $this->Main_model->data_sudahada($param,"MASTER_RPK");
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_MASTER_RPK_INSERT",$param,'post',TRUE);
    }
    /**
     * [rpk_put description]
     * @return [type] [description]
     */
    public function rpk_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_RPK"]            = $this->put('kd_rpk');
        $param["PROSENTASE"]        = $this->put('prosentase');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_RPK_UPDATE",$param,'put',TRUE);
    }
    public function rpk_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_RPK_DELETE",$param,'delete',TRUE);
    }
    /**
     * [insentif_kops_get description]
     * @return [type] [description]
     */
    public function insentif_kops_get(){
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
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"  => $this->get("keyword"),
                "NIK"   => $this->get("keyword")
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
        $this->resultdata("TRANS_INSENTIF_KOPS",$param);
    }
    /**
     * [insentif_kops_post description]
     * @return [type] [description]
     */
    public function insentif_kops_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["NIK"]               = $this->post('nik');
        $this->Main_model->data_sudahada($param,"TRANS_INSENTIF_KOPS");
        $param["TGL_TRANS"]         = tglToSql($this->post('tgl_trans'));
        $param["PERIODE_AWAL"]      = tglToSql($this->post('periode_awal'));
        $param["PERIODE_AKHIR"]     = tglToSql($this->post('periode_akhir'));
        $param["TGL_PENGAJUAN"]     = tglToSql($this->post('tgl_pengajuan'));
        $param["MARGIN_UNIT"]       = $this->post('margin_unit');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_INSENTIF_KOPS_INSERT",$param,'post',TRUE);
    }
    /**
     * [insentif_kops_put description]
     * @return [type] [description]
     */
    public function insentif_kops_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NIK"]               = $this->put('nik');
        $param["PERIODE_AWAL"]      = tglToSql($this->put('periode_awal'));
        $param["PERIODE_AKHIR"]     = tglToSql($this->put('periode_akhir'));
        $param["TGL_PENGAJUAN"]     = tglToSql($this->put('tgl_pengajuan'));
        $param["MARGIN_UNIT"]       = $this->put('margin_unit');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_KOPS_UPDATE",$param,'put',TRUE);
    }
    public function insentif_kops_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_KOPS_DELETE",$param,'delete',TRUE);
    }
    /**
     * [insentif_kops_detail_get description]
     * @return [type] [description]
     */
    public function insentif_kops_detail_get(){
       $param = array();$search='';
       if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("insentif_kops_id")){
            $param["INSENTIF_KOPS_ID"]     = $this->get("insentif_kops_id");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"  => $this->get("keyword"),
                "INSENTIF_KOPS_ID"   => $this->get("keyword")
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
        $this->resultdata("TRANS_INSENTIF_KOPS_DETAIL",$param);
    }
    /**
     * [insentif_kops_detail_post description]
     * @return [type] [description]
     */
    public function insentif_kops_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["INSENTIF_KOPS_ID"]  = $this->post('insentif_kops_id');       
        $param["PERIODE"]           = $this->post('periode');
        $this->Main_model->data_sudahada($param,"TRANS_INSENTIF_KOPS_DETAIL");
        $param["P1"]                = $this->post('p1');
        $param["P1_TOTAL"]          = $this->post('p1_total');
        $param["P2"]                = $this->post('p2');
        $param["P2_TOTAL"]          = $this->post('p2_total');
        $param["P3"]                = $this->post('p3');
        $param["P3_TOTAL"]          = $this->post('p3_total');
        $param["P4"]                = $this->post('p4');
        $param["P4_TOTAL"]          = $this->post('p4_total');
        $param["P5"]                = $this->post('p5');
        $param["P6"]                = $this->post('p6');
        $param["P7"]                = $this->post('p7');
        $param["P8"]                = $this->post('p8');
        $param["P9"]                = $this->post('p9');
        $param["P10"]               = $this->post('p10');
        $param["P11"]               = $this->post('p11');
        $param["TOTAL_PENJUALAN"]   = $this->post('total_penjualan');
        $param["CREATED_BY"]        = $this->post('created_by');
        //print_r($param);die();
        
        $this->resultdata("SP_TRANS_INSENTIF_KOPS_DETAIL_INSERT",$param,'post',TRUE);

    }
    /**
     * [insentif_kops_detail_put description]
     * @return [type] [description]
     */
    public function insentif_kops_detail_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["INSENTIF_KOPS_ID"]  = $this->put('insentif_korps_id');
        $param["PERIODE"]           = tglToSql($this->put('periode'));
        $param["P1"]                = $this->put('p1');
        $param["P1_TOTAL"]          = $this->put('p1_total');
        $param["P2"]                = $this->put('p2');
        $param["P2_TOTAL"]          = $this->put('p2_total');
        $param["P3"]                = $this->put('p3');
        $param["P3_TOTAL"]          = $this->put('p3_total');
        $param["P4"]                = $this->put('p4');
        $param["P4_TOTAL"]          = $this->put('p4_total');
        $param["P5"]                = $this->put('p5');
        $param["P6"]                = $this->put('p6');
        $param["P7"]                = $this->put('p7');
        $param["P8"]                = $this->put('p8');
        $param["P9"]                = $this->put('p9');
        $param["P10"]               = $this->put('p10');
        $param["P11"]               = $this->put('p11');
        $param["TOTAL_PENJUALAN"]   = $this->put('total_penjualan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_KOPS_DETAIL_UPDATE",$param,'put',TRUE);
    }
    public function insentif_kops_detail_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_KOPS_DETAIL_DELETE",$param,'delete',TRUE);
    }
    /**
     * [insentif_ksp_get description]
     * @return [type] [description]
     */
    public function insentif_ksp_get(){
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
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"  => $this->get("keyword"),
                "NIK"   => $this->get("keyword")
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
        $this->resultdata("TRANS_INSENTIF_KSP",$param);
    }
    /**
     * [insentif_ksp_post description]
     * @return [type] [description]
     */
    public function insentif_ksp_post(){
      
       
        $dt   = new DateTime();
       
        $param = array();
       
        $param["NO_TRANS"]          = 1;
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["NIK"]               = $this->post('nik');        
        $param["PERIODE_AWAL"]      = tglToSql($this->post('periode_awal'));
        $param["PERIODE_AKHIR"]     = tglToSql($this->post('periode_akhir'));
        $param["TGL_PENGAJUAN"]     = tglToSql($this->post('tgl_pengajuan'));
         
        /*$param["PERIODE_AWAL"]      = $dt->createFromFormat('d/m/Y', $this->post('periode_awal'))->format('Y-m-d');      
        $param["PERIODE_AKHIR"]     = $dt->createFromFormat('d/m/Y', $this->post('periode_akhir'))->format('Y-m-d');
        $param["TGL_PENGAJUAN"]     = $dt->createFromFormat('d/m/Y', $this->post('tgl_pengajuan'))->format('Y-m-d');*/
       
        $this->Main_model->data_sudahada($param,"TRANS_INSENTIF_KSP");
        $param["TOTAL_SALES"]       = $this->post('total_sales');
        $param["SALES_TAMBAH"]      = 0;
        $param["SALES_KURANG"]      = 0;
        $param["RPK"]               = $this->post('rpk');
        $param["MARGIN_UNIT"]       = $this->post('margin_unit');
        $param["INSENTIF_UNIT"]     = $this->post('insentif_unit');
        $param["TOTAL_INSENTIF"]    = $this->post('total_insentif');
        $param["PENALTY"]           = $this->post('penalty');
        $param["PPH21"]             = $this->post('pph21');
        $param["INSENTIF_TERIMA"]   = $this->post('insentif_terima');
        $param["CREATED_BY"]        = $this->post('created_by');       
      
        $this->resultdata("SP_TRANS_INSENTIF_KSP_INSERT",$param,'post',TRUE);
    }
    /**
     * [insentif_ksp_put description]
     * @return [type] [description]
     */
    public function insentif_ksp_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NIK"]               = $this->put('nik');
        $param["PERIODE_AWAL"]      = tglToSql($this->put('periode_awal'));
        $param["PERIODE_AKHIR"]     = tglToSql($this->put('periode_akhir'));
        $param["TGL_PENGAJUAN"]     = tglToSql($this->put('tgl_pengajuan'));
        $param["TOTAL_SALES"]       = $this->put('total_sales');
        $param["SALES_TAMBAH"]      = $this->put('sales_tambah');
        $param["SALES_KURANG"]      = $this->put('sales_kurang');
        $param["RPK"]               = $this->put('rpk');
        $param["MARGIN_UNIT"]       = $this->put('margin_unit');
        $param["INSENTIF_UNIT"]     = $this->put('insentif_unit');
        $param["TOTAL_INSENTIF"]    = $this->put('total_insentif');
        $param["PENALTY"]           = $this->put('penalty');
        $param["PPH21"]             = $this->put('pph21');
        $param["INSENTIF_TERIMA"]   = $this->put('insentif_terima');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_KSP_UPDATE",$param,'put',TRUE);
    }
    public function insentif_ksp_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_KSP_DELETE",$param,'delete',TRUE);
    }
    /**
     * [file_udprg_get description]
     * @return [type] [description]
     */
    public function file_udprg_get(){
       $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("no_rangka")){
            $param["[NO_RANGKA]"]     = $this->get("no_rangka");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_MESIN"  => $this->get("keyword"),
                "NAMA_CUSTOMER"   => $this->get("keyword")
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
        $this->resultdata("TRANS_FILE_UDPRG_VIEW",$param);
    }
    /**
     * [file_get description]
     * @return [type] [description]
     */
    public function file_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
       if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"  => $this->get("keyword"),
                "NO_RANGKA"   => $this->get("keyword")
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
        $this->resultdata("TRANS_FILE",$param);
    }
    /**
     * [file_post description]
     * @return [type] [description]
     */
    public function file_post(){
        $param = array();
        $param["NO_TRANS"]                  = $this->post('no_trans');
        $param["TGL_TRANS"]                 = tglToSql($this->post('tgl_trans'));
        $param["NO_RANGKA"]                 = $this->post('no_rangka');
        $param["DIRECTORY_FILE"]            = $this->post('directory_file');
        $param["KD_MAINDEALER"]             = $this->post('kd_maindealer');
        $param["KD_DEALER"]                 = $this->post('kd_dealer');
        $param["SPK_ID"]                    = $this->post('spk_id');
        $param["KD_DEALERAHM"]              = $this->post('kd_dealerahm');
        $param["NO_MESIN"]                  = $this->post('no_mesin');
        $param["JENIS_PENJUALAN"]           = $this->post('jenis_penjualan');
        $param["TYPE_PENJUALAN"]            = $this->post('type_penjualan');
        $param["KD_TYPEMOTOR"]              = $this->post('kd_typemotor');
        $param["KD_ITEM"]                   = $this->post('kd_item');
        $param["NAMA_ITEM"]                 = $this->post('nama_item');
        $param["KD_FINCOY"]                 = $this->post('kd_fincoy');
        $param["UANG_MUKA"]                 = $this->post('uang_muka');
        $param["JANGKA_WAKTU"]              = $this->post('jangka_waktu');
        $param["JUMLAH_ANGSURAN"]           = $this->post('jumlah_angsuran');
        $param["KD_CUSTOMER"]               = $this->post('kd_customer');
        $param["NAMA_CUSTOMER"]             = $this->post('nama_customer');
        $param["JENIS_KELAMIN"]             = $this->post('jenis_kelamin');
        $param["TGL_LAHIR"]                 = tglToSql($this->post('tgl_lahir'));
        $param["TGL_PEMBUATAN_NPWP"]        = tglToSql($this->post('tgl_pembuatan_npwp'));
        $param["NO_KTP"]                    = $this->post('no_ktp');
        $param["NO_NPWP"]                   = $this->post('no_npwp');
        $param["ALAMAT_SURAT"]              = $this->post('alamat_surat');
        //$param["KELURAHAN"]                 = $this->post('kelurahan');
        $param["KD_KECAMATAN"]              = $this->post('kd_kecamatan');
        //$param["KD_KOTA"]                   = $this->post('kd_kota');
        $param["KODE_POS"]                  = $this->post('kode_pos');
        $param["KD_PROPINSI"]               = $this->post('kd_propinsi');
        $param["KD_AGAMA"]                  = $this->post('kd_agama');
        $param["KD_PEKERJAAN"]              = $this->post('kd_pekerjaan');
        $param["PENGELUARAN"]               = $this->post('pengeluaran');
        $param["KD_PENDIDIKAN"]             = $this->post('kd_pendidikan');
        $param["NAMA_PENANGGUNGJAWAB"]      = $this->post('nama_penanggungjawab');
        $param["NO_HP"]                     = $this->post('no_hp');
        $param["NO_TELEPON"]                = $this->post('no_telepon');
        $param["STATUS_DIHUBUNGI"]          = $this->post('status_dihubungi');
        $param["EMAIL"]                     = $this->post('email');
        $param["STATUS_RUMAH"]              = $this->post('status_rumah');
        $param["STATUS_NOHP"]               = $this->post('status_nohp');
        $param["AKUN_FB"]                   = $this->post('akun_fb');
        $param["AKUN_TWITTER"]              = $this->post('akun_twitter');
        $param["AKUN_INSTAGRAM"]            = $this->post('akun_instagram');
        $param["AKUN_YOUTUBE"]              = $this->post('akun_youtube');
        $param["HOBI"]                      = $this->post('hobi');
        $param["KARAKTERISTIK_KONSUMEN"]    = $this->post('karakteristik_konsumen');
        $param["ID_REFFERAL"]               = $this->post('id_refferal');
        $param["NAMA_PROPINSI"]             = $this->post('nama_propinsi');
        $param["KD_KABUPATEN"]              = $this->post('kd_kabupaten');
        $param["NAMA_KABUPATEN"]            = $this->post('nama_kabupaten');
        $param["NAMA_KECAMATAN"]            = $this->post('nama_kecamatan');
        $param["KD_DESA"]                   = $this->post('kd_desa');
        $param["NAMA_DESA"]                 = $this->post('nama_desa');
        $param["ALAMAT"]                    = $this->post('alamat');
        $param["JENIS_KELAMIN"]             = $this->post('jenis_kelamin');
        $param["NAMA_SALES"]                = $this->post('nama_sales');
        $param["TGL_TARIKDATA"]             = tglToSql($this->post('tgl_tarikdata'));
        //$param["TGL_DOWNLOAD"]              = tglToSql($this->post('tgl_download'));
        //$param["TGL_BATAL_DOWNLOAD"]        = tglToSql($this->post('tgl_batal_download'));
        //$param["STATUS_DOWNLOAD"]           = $this->post('status_download');
        $param["FINANCE_COMPANY"]       = $this->post('finance_company');
        $param["PROGRAM_ID"]            = $this->post('program_id');
        $param["REASON_TEL"]            = $this->post('reason_tel');
        $param["REASON_HP"]             = $this->post('reason_hp');
        $param["TGL_JUAL"]              = tglToSql($this->post('tgl_jual'));
        $param["SP_ID"]                 = $this->post('sp_id');
        $param["LCLSP_ID"]              = $this->post('lclsp_id');
        $param["JENIS_BAYAR"]           = $this->post('jenis_bayar');
        $param["ASAL_JUAL"]             = $this->post('asal_jual');
        $param["KD_DLRPOS"]             = $this->post('kd_dlrpos');
        $param["JENIS_SLSFORCE"]        = $this->post('jenis_slsforce');
        $param["DP_SETOR"]              = $this->post('dp_setor');
        $param["S_AHM"]                 = $this->post('s_ahm');
        $param["S_MD"]                  = $this->post('s_md');
        $param["S_SD"]                  = $this->post('s_sd');
        $param["S_FINANCE"]             = $this->post('s_finance');
        $param["SPLIT_OTR"]             = $this->post('split_otr');
        $param["RO"]                    = $this->post('ro');
        $param["J_CUST"]                = $this->post('j_cust');
        $param["INFORMASI"]             = $this->post('informasi');
        $param["MERK_MOTOR"]            = $this->post('merk_motor');
        $param["JENIS_MOTOR"]           = $this->post('jenis_motor');
        $param["DIGUNAKAN_UNTUK"]       = $this->post('digunakan_untuk');
        $param["YANG_MENGGUNAKAN"]      = $this->post('yang_menggunakan');
        $param["HONDAID_SALES"]         = $this->post('hondaid_sales');
        $param["GROUP_CUSTOMER"]        = $this->post('group_customer');
        $param["NO_MESINRO"]            = $this->post('no_mesinro');
        $param["POS_PENGIRIMAN"]        = $this->post('pos_pengiriman');
        $param["JENIS_HARGA"]           = $this->post('jenis_harga');
        $this->Main_model->data_sudahada($param,"TRANS_FILE");
        $param["CREATED_BY"]                = $this->post('created_by');
        $this->resultdata("SP_TRANS_FILE_INSERT",$param,'post',TRUE);
    }
    /**
     * [file_put description]
     * @return [type] [description]
     */
    public function file_put(){
        $param = array();
        $param["ID"]                        = $this->put('id');
        // $param["NO_TRANS"]                  = $this->put('no_trans');
        // $param["TGL_TRANS"]                 = tglToSql($this->put('tgl_trans'));
        // $param["NO_RANGKA"]                 = $this->put('no_rangka');
        // $param["DIRECTORY_FILE"]            = $this->put('directory_file');
        // $param["KD_MAINDEALER"]             = $this->put('kd_maindealer');
        // $param["KD_DEALER"]                 = $this->put('kd_dealer');
        // $param["SPK_ID"]                    = $this->put('spk_id');
        // $param["KD_DEALERAHM"]              = $this->put('kd_dealerahm');
        // $param["NO_MESIN"]                  = $this->put('no_mesin');
        // $param["JENIS_PENJUALAN"]           = $this->put('jenis_penjualan');
        // $param["TYPE_PENJUALAN"]            = $this->put('type_penjualan');
        // $param["KD_TYPEMOTOR"]              = $this->put('kd_typemotor');
        // $param["KD_ITEM"]                   = $this->put('kd_item');
        // $param["NAMA_ITEM"]                 = $this->put('nama_item');
        // $param["KD_FINCOY"]                 = $this->put('kd_fincoy');
        // $param["UANG_MUKA"]                 = $this->put('uang_muka');
        // $param["JANGKA_WAKTU"]              = $this->put('jangka_waktu');
        // $param["JUMLAH_ANGSURAN"]           = $this->put('jumlah_angsuran');
        // $param["KD_CUSTOMER"]               = $this->put('kd_customer');
        $param["NAMA_CUSTOMER"]             = $this->put('nama_customer');
        $param["JENIS_KELAMIN"]             = $this->put('jenis_kelamin');
        $param["TGL_LAHIR"]                 = tglToSql($this->put('tgl_lahir'));
        $param["TGL_PEMBUATAN_NPWP"]        = tglToSql($this->put('tgl_pembuatan_npwp'));
        $param["NO_KTP"]                    = $this->put('no_ktp');
        $param["NO_NPWP"]                   = $this->put('no_npwp');
        $param["ALAMAT_SURAT"]              = $this->put('alamat_surat');
        //$param["KELURAHAN"]                 = $this->put('kelurahan');
        $param["KD_KECAMATAN"]              = $this->put('kd_kecamatan');
        //$param["KD_KOTA"]                   = $this->put('kd_kota');
        $param["KODE_POS"]                  = $this->put('kode_pos');
        $param["KD_PROPINSI"]               = $this->put('kd_propinsi');
        $param["KD_AGAMA"]                  = $this->put('kd_agama');
        $param["KD_PEKERJAAN"]              = $this->put('kd_pekerjaan');
        $param["PENGELUARAN"]               = $this->put('pengeluaran');
        $param["KD_PENDIDIKAN"]             = $this->put('kd_pendidikan');
        $param["NAMA_PENANGGUNGJAWAB"]      = $this->put('nama_penanggungjawab');
        $param["NO_HP"]                     = $this->put('no_hp');
        $param["NO_TELEPON"]                = $this->put('no_telepon');
        $param["STATUS_DIHUBUNGI"]          = $this->put('status_dihubungi');
        $param["EMAIL"]                     = $this->put('email');
        $param["STATUS_RUMAH"]              = $this->put('status_rumah');
        $param["STATUS_NOHP"]               = $this->put('status_nohp');
        $param["AKUN_FB"]                   = $this->put('akun_fb');
        $param["AKUN_TWITTER"]              = $this->put('akun_twitter');
        $param["AKUN_INSTAGRAM"]            = $this->put('akun_instagram');
        $param["AKUN_YOUTUBE"]              = $this->put('akun_youtube');
        $param["HOBI"]                      = $this->put('hobi');
        $param["KARAKTERISTIK_KONSUMEN"]    = $this->put('karakteristik_konsumen');
        // $param["ID_REFFERAL"]               = $this->put('id_refferal');
        $param["NAMA_PROPINSI"]             = $this->put('nama_propinsi');
        $param["KD_KABUPATEN"]              = $this->put('kd_kabupaten');
        $param["NAMA_KABUPATEN"]            = $this->put('nama_kabupaten');
        $param["NAMA_KECAMATAN"]            = $this->put('nama_kecamatan');
        $param["KD_DESA"]                   = $this->put('kd_desa');
        $param["NAMA_DESA"]                 = $this->put('nama_desa');
        $param["ALAMAT"]                    = $this->put('alamat');
        // $param["NAMA_SALES"]                = $this->put('nama_sales');
        // $param["TGL_TARIKDATA"]             = tglToSql($this->put('tgl_tarikdata'));
        // $param["TGL_DOWNLOAD"]              = tglToSql($this->put('tgl_download'));
        // $param["TGL_BATAL_DOWNLOAD"]        = tglToSql($this->put('tgl_batal_download'));
        // $param["STATUS_DOWNLOAD"]           = $this->put('status_download');
        $param["HONDAID_SALES"]             = $this->put('hondaid_sales');
        $param["GROUP_CUSTOMER"]            = $this->put('group_customer');
        $param["NO_MESINRO"]                = $this->put('no_mesinro');
        $param["POS_PENGIRIMAN"]            = $this->put('pos_pengiriman');
        $param["JENIS_HARGA"]               = $this->put('jenis_harga');
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FILE_UPDATE",$param,'put',TRUE);
    }
    public function file_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FILE_DELETE",$param,'delete',TRUE);
    }
    /**
     * [monitoring_etdeta_get description]
     * @return [type] [description]
     */
    public function monitoring_etdeta_get(){
       $param = array();$search='';
       if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"  => $this->get("keyword"),
                "PART_NUMBER"   => $this->get("keyword")
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
        $this->resultdata("TRANS_ETDETA_SO",$param);
    }
    /**
     * [reporting_data_get description]
     * @return [type] [description]
     */
    public function reporting_data_get(){
       $param = array();$search='';
       if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_SPK"  => $this->get("keyword"),
                "NO_RANGKA"   => $this->get("keyword")
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
        $this->resultdata("TRANS_LEADTIME_UNIT",$param);
    }
    /**
     * [crm_ufu_get description]
     * @return [type] [description]
     */
    public function crm_ufu_get(){
        $param = array();
        if($this->get("tipe")){
            $param["TIPE"]     = $this->get("tipe");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "TIPE" =>$this->get("keyword"),
                "KD_CUSTOMER" =>$this->get("keyword")
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
        $this->resultdata("TRANS_CRM_UFU_VIEW",$param);
    }
    /**
     * [udbyb_get description]
     * @return [type] [description]
     */
    public function udbyb_get(){
       $param = array();$search='';
        if($this->get("no_mhn")){
            $param["NO_MHN"]     = $this->get("no_mhn");
        }
        if($this->get("nama_wilayah")){
            $param["NAMA_WILAYAH"]     = $this->get("nama_wilayah");
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
                "NO_MHN"  => $this->get("keyword"),
                "NAMA_WILAYAH"   => $this->get("keyword")
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
        $this->resultdata("TRANS_UDBYB",$param);
    }
    /**
     * [udbyb_post description]
     * @return [type] [description]
     */
    public function udbyb_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["NO_MHN"]            = $this->post('no_mhn');
        $param["NAMA_WILAYAH"]      = $this->post('nama_wilayah');
        $param["TGL_MHN"]           = tglToSql($this->post('tgl_mhn'));
        $param["TGL_FAKTUR"]        = tglToSql($this->post('tgl_faktur'));
        $param["NO_FAKTUR"]         = $this->post('no_faktur');
        $param["NO_MESIN"]          = $this->post('no_mesin');
        $param["NO_RANGKA"]         = $this->post('no_rangka');
        $param["KD_ITEM"]           = $this->post('kd_item');
        $param["NAMA_KONSUMEN"]     = $this->post('nama_konsumen');
        $param["BPKB"]              = $this->post('bpkb');
        $param["STCK"]              = $this->post('stck');
        $param["FORMULIR"]          = $this->post('formulir');
        $param["SP3"]               = $this->post('sp3');
        $param["STNK"]              = $this->post('stnk');
        $param["PLAT_ASLI"]         = $this->post('plat_asli');
        $param["ADMIN"]             = $this->post('admin');
        $param["ASURANSI"]          = $this->post('asuransi');
        $param["ADMIN_SAMSAT"]      = $this->post('admin_samsat');
        $param["LEGES_FAK"]         = $this->post('leges_fak');
        $param["SPKB"]              = $this->post('spkb');
        $param["BIAYA"]             = $this->post('biaya');
        $param["KD_DEALER2"]        = $this->post('kd_dealer2');
        $param["HASH"]              = $this->post('hash');
        $param["KETERANGAN_RANGKA"] = $this->post('keterangan_rangka');
        $this->Main_model->data_sudahada($param,"TRANS_UDBYB");
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_UDBYB_INSERT",$param,'post',TRUE);
    }
    /**
     * [udbyb_put description]
     * @return [type] [description]
     */
    public function udbyb_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NO_MHN"]            = $this->put('no_mhn');
        $param["NAMA_WILAYAH"]      = $this->put('nama_wilayah');
        $param["TGL_MHN"]           = tglToSql($this->put('tgl_mhn'));
        $param["TGL_FAKTUR"]        = tglToSql($this->put('tgl_faktur'));
        $param["NO_FAKTUR"]         = $this->put('no_faktur');
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["NO_RANGKA"]         = $this->put('no_rangka');
        $param["KD_ITEM"]           = $this->put('kd_item');
        $param["NAMA_KONSUMEN"]     = $this->put('nama_konsumen');
        $param["BPKB"]              = $this->put('bpkb');
        $param["STCK"]              = $this->put('stck');
        $param["FORMULIR"]          = $this->put('formulir');
        $param["SP3"]               = $this->put('sp3');
        $param["STNK"]              = $this->put('stnk');
        $param["PLAT_ASLI"]         = $this->put('plat_asli');
        $param["ADMIN"]             = $this->put('admin');
        $param["ASURANSI"]          = $this->put('asuransi');
        $param["ADMIN_SAMSAT"]      = $this->put('admin_samsat');
        $param["LEGES_FAK"]         = $this->put('leges_fak');
        $param["SPKB"]              = $this->put('spkb');
        $param["BIAYA"]             = $this->put('biaya');
        $param["KD_DEALER2"]        = $this->put('kd_dealer2');
        $param["HASH"]              = $this->put('hash');
        $param["KETERANGAN_RANGKA"] = $this->put('keterangan_rangka');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_UDBYB_UPDATE",$param,'put',TRUE);
    }
    /**
     * [udbyb_deletet description]
     * @return [type] [description]
     */ 
    public function udbyb_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_UDBYB_DELETE",$param,'delete',TRUE);
    }
    /**
     * [file_download_put description]
     * @return [type] [description]
     */
    public function file_download_put(){
        $param = array();
        $param["ID"]                        = $this->put('id');
        $param["TGL_DOWNLOAD"]              = tglToSql($this->put('tgl_download'));
        $param["STATUS_DOWNLOAD"]           = $this->put('status_download');
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FILE_DOWNLOAD_UPDATE",$param,'put',TRUE);
    }
    public function sales_harian_get($unit=null){
        $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->input->get("part_number")){
            $param["PART_NUMBER"] = $this->input->get("part_number");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "PART_NUMBER"  => $this->get("keyword"),
                "PART_DESKRIPSI"   => $this->get("keyword")
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
        $this->resultdata("TRANS_PART_SALES",$param);
    }
    public function lhb_get($unit=null){
        $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->input->get("no_pkb")){
            $param["NO_PKB"] = $this->input->get("no_pkb");
        }
        if($this->input->get("nama_mekanik")){
            $param["NAMA_MEKANIK"] = $this->input->get("nama_mekanik");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_PKB"  => $this->get("keyword"),
                "NAMA_MEKANIK"   => $this->get("keyword")
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
        $this->resultdata("TRANS_LHB_VIEWS",$param);
    }
//mekanik attendance
    public function mekanik_attendance_get($unit=null){
        $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->input->get("nik")){
            $param["NIK"] = $this->input->get("nik");
        }
        if($this->input->get("nama")){
            $param["NAMA"] = $this->input->get("nama");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NIK"  => $this->get("keyword"),
                "NAMA"   => $this->get("keyword")
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
        $this->resultdata("MEKANIK_ATTENDANCE_V",$param);
    }
    /**
     * [insentif_salesman_get description]
     * @return [type] [description]
     */
    public function insentif_salesman_get(){
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
        if($this->get("kd_salesman")){
            $param["KD_SALESMAN"]     = $this->get("kd_salesman");
        }
        if($this->get("nama_salesman")){
            $param["NAMA_SALESMAN"]     = $this->get("nama_salesman");
        }
        if($this->get("no_faktur")){
            $param["NO_FAKTUR"]     = $this->get("no_faktur");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"      => $this->get("keyword"),
                "NAMA_SALESMAN" => $this->get("keyword")
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
        $this->resultdata("TRANS_INSENTIF_SALESMAN",$param);
    }
    /**
     * [insentif_salesman_post description]
     * @return [type] [description]
     */
    public function insentif_salesman_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->post('tgl_trans'));
        $param["NAMA_SALESMAN"]     = $this->post('nama_salesman');
        $param["TGL_FAKTUR"]        = tglToSql($this->post('tgl_faktur'));
        $param["NO_FAKTUR"]         = $this->post('no_faktur');
        $this->Main_model->data_sudahada($param,"TRANS_INSENTIF_SALESMAN");
        $param["TGL_PENGAJUAN"]     = tglToSql($this->post('tgl_pengajuan'));
        $param["KATEGORI"]          = $this->post('kategori');
        $param["KD_SALESMAN"]       = $this->post('kd_salesman');
        $param["NAMA_KONSUMEN"]     = $this->post('nama_konsumen');
        $param["KD_TYPEMOTOR"]      = $this->post('kd_typemotor');
        $param["NAMA_TYPEMOTOR"]    = $this->post('nama_typemotor');
        $param["KET_TYPE"]          = $this->post('ket_type');
        $param["VIA"]               = $this->post('via');
        $param["DP_OTR"]            = $this->post('dp_otr');
        $param["SUB_DLR_DISC"]      = $this->post('sub_dlr_disc');
        $param["PROGRAM"]           = $this->post('program');
        $param["INSENTIF"]          = $this->post('insentif');
        $param["PENALTY"]           = $this->post('penalty');
        $param["INSENTIF_DASAR"]    = $this->post('insentif_dasar');
        $param["PENCAPAIAN"]        = $this->post('pencapaian');
        $param["PENGALI"]           = $this->post('pengali');
        $param["TOTAL_INSENTIF"]    = $this->post('total_insentif');
        $param["STATUS"]            = $this->post('status');
        $param["TARGET"]            = $this->post('target');
        $param["PENJUALAN"]         = $this->post('penjualan');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_INSENTIF_SALESMAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [insentif_salesman_put description]
     * @return [type] [description]
     */
    public function insentif_salesman_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->put('tgl_trans'));
        $param["TGL_PENGAJUAN"]     = tglToSql($this->put('tgl_pengajuan'));
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KATEGORI"]          = $this->put('kategori');
        $param["KD_SALESMAN"]       = $this->put('kd_salesman');
        $param["NAMA_SALESMAN"]     = $this->put('nama_salesman');
        $param["TGL_FAKTUR"]        = tglToSql($this->put('tgl_faktur'));
        $param["NO_FAKTUR"]         = $this->put('no_faktur');
        $param["NAMA_KONSUMEN"]     = $this->put('nama_konsumen');
        $param["KD_TYPEMOTOR"]      = $this->put('kd_typemotor');
        $param["NAMA_TYPEMOTOR"]    = $this->put('nama_typemotor');
        $param["KET_TYPE"]          = $this->put('ket_type');
        $param["VIA"]               = $this->put('via');
        $param["DP_OTR"]            = $this->put('dp_otr');
        $param["SUB_DLR_DISC"]      = $this->put('sub_dlr_disc');
        $param["PROGRAM"]           = $this->put('program');
        $param["INSENTIF"]          = $this->put('insentif');
        $param["PENALTY"]           = $this->put('penalty');
        $param["INSENTIF_DASAR"]    = $this->put('insentif_dasar');
        $param["PENCAPAIAN"]        = $this->put('pencapaian');
        $param["PENGALI"]           = $this->put('pengali');
        $param["TOTAL_INSENTIF"]    = $this->put('total_insentif');
        $param["STATUS"]            = $this->put('status');
        $param["TARGET"]            = $this->put('target');
        $param["PENJUALAN"]         = $this->put('penjualan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_SALESMAN_UPDATE",$param,'put',TRUE);
    }
    public function insentif_salesman_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_SALESMAN_DELETE",$param,'delete',TRUE);
    }
    /**
     * [mpp description]
     * @return [type] [description]
     */
    function laporan_mpp_get(){
        $kd_dealer = $this->get("kd_dealer");
        $tahun = ($this->get("tahun"));
        $bulan = ($this->get("bulan"));
        $this->Main_model->set_custom_query($this->Custom_model->get_mekanikperformance($kd_dealer,$tahun,$bulan));
        $this->resultdata("TRANS_PKB",$param=array());
    }
    /**
     * Created by Dimas Rido
     * [crmharian_get description]
     * @param  [type] $unit [description]
     * @return [type]       [description]
     */
    public function crmharian_get($unit=null){
        $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->input->get("no_pkb")){
            $param["NO_PKB"] = $this->input->get("no_pkb");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_PKB"  => $this->get("keyword"),
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
        $this->resultdata("TRANS_CRM_HARIAN_VIEW",$param);
    }

    public function insentifpicpart_get($unit=null){
        $param=array();$search='';$query='';
         if ($this->input->get("kd_dealer")){
            $param["PARAMETER"] = "'".$this->input->get("kd_dealer")."','".$this->input->get("start_date")."','".$this->input->get("end_date")."'";
        } else {
            $param["PARAMETER"] = "'".$this->session->userdata('kd_dealer')."','".date('Y-m-d', strtotime('first day of this month'))."','".date('Y-m-d')."'";
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"  => $this->get("keyword"),
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
        //$query = $this->Custom_model->insentif_picpart($param);
        //echo $query;exit();
        $this->Main_model->set_custom_query($this->Custom_model->insentif_picpart($param));
        $this->resultdata("MASTER_INSENTIF",$param,'get',TRUE);       

    }
    
    public function insentifpicpartheader_get($unit=null){
        $param = array();
        
       
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_DEALER"    => $this->get("keyword")
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
        $this->resultdata("TRANS_INSENTIF_PICPART_HEADER",$param);

    }
    
    public function insentifpicpartheader_post(){
        $param=array();
        $param["NO_PROSES"]    = $this->post('no_proses');
        $param["KD_DEALER"]   = $this->post('kd_dealer');
        $param["START_DATE"]   = $this->post('start_date');
        $param["END_DATE"]   = $this->post('end_date');
       
        $param["CREATED_BY"]= $this->post('created_by');
        $this->resultdata("SP_TRANS_INSENTIF_PICPART_HEADER_INSERT",$param,'put',TRUE);
    }
    
    public function insentifpicpartdetail_get($unit=null){
        $param = array();
        
       
        if($this->get("no_proses")){
            $param["NO_PROSES"]   = $this->get("no_proses");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_PROSS"    => $this->get("keyword")
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
        $this->resultdata("TRANS_INSENTIF_PICPART_DETAIL",$param);

    }
    
     public function insentifpicpartdetail_post(){
        $param=array();
        $param["NO_PROSES"]    = $this->post('no_proses');
        $param["NIK"]   = $this->post('nik');
        $param["INSENTIF"]   = $this->post('insentif');
       
        $param["CREATED_BY"]= $this->post('created_by');
        $this->resultdata("SP_TRANS_INSENTIF_PICPART_DETAIL_INSERT",$param,'put',TRUE);
    }
    
     public function insentifpicpartapprove_put(){
        $param = array();
     
        $param["NO_PROSES"]    = $this->put("no_proses");
        $param["APPROVED_BY"]    = $this->put("approved_by");
        $param["STATUS_APPROVE"]           = $this->put("status_approve");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_PICPART_HEADER_APPROVE_UPDATE",$param,'put',TRUE);
    }

    public function insentifpicpart_delete(){
        $param = array();
        $param["NO_PROSES"]      = $this->delete("no_proses");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_INSENTIF_PICPART_HEADER_DELETE",$param,'delete',TRUE);
        $this->resultdata("SP_TRANS_INSENTIF_PICPART_DETAIL_DELETE",$param,'delete',TRUE);
    } 

     public function insentifpicpart2_get($unit=null){
        $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"  => $this->get("keyword"),
            );
        }
        $tanggalawal = '2019-02-01';
        $tanggalakhir = '2019-02-28';
        if ($this->get("custom")) {
            $custom = $this->get("custom");
        } else {
            $custom = "'T13','2019-02-01','2019-02-28";
        }
        //$procedure="EXEC SP_LAPORAN_INSENTIF_PICPART_VIEW '".$tanggalawal."','".$tanggalakhir."'"; 
        $procedure="EXEC SP_LAPORAN_INSENTIF_PICPART_VIEW '".$custom."'"; 
        //var_dump($procedure); exit();
        $query = $this->db->query($procedure);
        $data = $query->result();
        $result["status"]   = TRUE;
        $result["message"]  = $data;
        $result["param"]    = $this->db->last_query();
        $ttr=$query->num_rows();
        $result["totaldata"]= $ttr;
        $this->response($result); // return  autoincrement
        $this->resultdata($this->response($result),$param);
    }

    public function insentif_picstnk_get(){
        $param=array();
        $search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
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
        $this->resultdata("TRANS_INSENTIFPICSTNK_VIEWS",$param);
    }
    
    public function config_insentifpic_stnk_get(){
        $param = array();
        $param["KD_CONFIG"]     = 'NO_PIPS';
        $this->resultdata("CONFIG_APP",$param);
    }

	public function laporanpenjualan_oliservis_get(){
        $param = array();
	    if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
		if($this->get("bulan")){
            $param["BULAN"] = $this->get("bulan");
        }
		if($this->get("tahun")){
            $param["TAHUN"] = $this->get("tahun");
        }
		$this->Main_model->set_selectfield($this->get('field'));
        $this->resultdata("TRANS_LAPORANPENJUALAN_OLISERVIS_VIEW",$param);

		
	}

    
    public function lab_get($unit=null){
        $param=array();$search='';
        if($this->input->get("kd_dealer")){
            $param["KD_DEALER"] = $this->input->get("kd_dealer");
        }
        if($this->input->get("tanggal_pkb")){
            $param["TANGGAL_PKB"] = $this->input->get("tanggal_pkb");
        }
       
        /*if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_PKB"  => $this->get("keyword"),
            );
        }*/
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
        $this->resultdata("TRANS_LAB_VIEWS",$param);

    }

    public function stock_fulfillment_get(){
        $kd_item = $this->get("kd_item");
        $kd_dealer = $this->get("kd_dealer");
        $no_mesin = $this->get("no_mesin");
        $status_indent = $this->get("status_indent");

        $this->Main_model->set_custom_query($this->Custom_model->stock_fulfillment($kd_item,$kd_dealer,$status_indent,$no_mesin));
        $this->resultdata("TRANS_STOCKMOTOR",$param=array());
    }

}
?>