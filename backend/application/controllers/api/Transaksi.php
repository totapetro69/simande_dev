<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Transaksi extends REST_Controller {

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
     * [absensi_get description]
     * @return [type] [description]
     */
    public function absensi_get(){
        $param = array();
        
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }

        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }

        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NIK"      => $this->get("keyword"),
                "TANGGAL"  => $this->get("keyword")
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
        $this->resultdata("TRANS_ABSENSI_MEKANIK",$param);
    }
    function absensi_view_get(){
        $param=array();
        $kd_dealer = $this->get("kd_dealer");
        $start_date = $this->get("start_date");
        $end_date= $this->get("end_date");
        $this->Main_model->set_custom_query($this->Custom_model->att_mekanik($kd_dealer,$start_date,$end_date));
        $this->resultdata("TRANS_ABSENSI_MEKANIK",$param=array());
    }

    function mekanik_attendance_get(){
        $param=array();
        $kd_dealer = ($this->get("where_in"))?implode("','", $this->get("where_in")):$this->get("kd_dealer");
        $start_date = $this->get("start_date");
        $end_date= $this->get("end_date");
        //var_dump($start_date, $end_date, $kd_dealer); exit;
        $this->Main_model->set_custom_query($this->Custom_model->mek_att($kd_dealer,$start_date,$end_date));
        $this->resultdata("TRANS_ABSENSI_MEKANIK",$param=array());
    }
    /**
     * [absensi_post description]
     * @return [type] [description]
     */
    public function absensi_post(){
        $param = array();
        $param["NIK"]     = $this->post("nik");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["TANGGAL"]         = tglToSql($this->post("tanggal"));
        $this->Main_model->data_sudahada($param,"TRANS_ABSENSI_MEKANIK");
        $param["JAM_MASUK"]         = $this->post("jam_masuk");
        $param["JAM_PULANG"]         = $this->post("jam_pulang");
        $param["STATUS_KARYAWAN"]    = $this->post("status_karyawan");
        $param["KETERANGAN"]         = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");    
        $this->resultdata("SP_TRANS_ABSENSI_MEKANIK_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [absensi_put description]
     * @return [type] [description]
     */
    public function absensi_put(){
        $param = array();
        $param["ID"]     = $this->put("id");
        $param["NIK"]     = $this->put("nik");
        $param["TANGGAL"]   = tglToSql($this->put("tanggal"));
        $param["JAM_MASUK"]   = $this->put("jam_masuk"); 
        $param["JAM_PULANG"]   = $this->put("jam_pulang");
        $param["STATUS_KARYAWAN"]    = $this->put("status_karyawan"); 
        $param["KETERANGAN"]   = $this->put("keterangan");
        $param["KD_DEALER"]   = $this->put("kd_dealer");  
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_ABSENSI_MEKANIK_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [absensi_delete description]
     * @return [type] [description]
     */
    public function absensi_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_ABSENSI_MEKANIK_DELETE",$param,'delete',TRUE);
    }


    /**
     * [csa_get description]
     * @return [type] [description]
     */
    public function csa_get(){
        $param = array();
        
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("kd_sa")){
            $param["KD_SA"]     = $this->get("kd_sa");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get('no_polisi')){
            $param["NO_POLISI"]     = $this->get("no_polisi");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_SA"      => $this->get("keyword"),
                "KD_CUSTOMER"      => $this->get("keyword"),
                "NO_POLISI"      => $this->get("keyword"),
                "NO_MESIN"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_CSA",$param);
    }
    
    /**
     * [csa_post description]
     * @return [type] [description]
     */
    public function csa_post(){
        $param = array();
        $param["KD_SA"]                         = $this->post("kd_sa");
        $param["KD_MAINDEALER"]                 = $this->post("kd_maindealer");
        $param["KD_DEALER"]                     = $this->post("kd_dealer");
        $param["NO_POLISI"]                     = strtoupper($this->post("no_polisi"));
        $this->Main_model->data_sudahada($param,"TRANS_CSA");
        $param["KD_LOKASIDEALER"]               = $this->post("kd_lokasidealer");
        $param["KD_CUSTOMER"]                   = $this->post("kd_customer");
        $param["TANGGAL_SA"]                    = tglToSql($this->post("tanggal_sa"));
        $param["NO_MESIN"]                      = $this->post("no_mesin");
        $param["NO_RANGKA"]                     = $this->post("no_rangka");
        $param["KD_PEMBAWAMOTOR"]               = $this->post("kd_pembawamotor");
        $param["KD_PEMAKAIMOTOR"]               = $this->post("kd_pemakaimotor");
        $param["KD_TYPECOMINGCUSTOMER"]         = $this->post("kd_typecomingcustomer");
        $param["KD_HONDA"]                      = $this->post("kd_honda");
        $param["KD_TIPEPKB"]                    = $this->post("kd_tipepkb");
        $param["KD_JENISPIT"]                   = $this->post("kd_jenispit");
        $param["FOTO_KONSUMEN"]                 = $this->post("foto_konsumen");
        $param["DOKUMEN"]                       = $this->post("dokumen");
        $param["ESTIMASI_PENDAFTARAN"]          = tglToSql($this->post("estimasi_pendaftaran"));
        $param["ESTIMASI_PENGERJAAN"]           = tglToSql($this->post("estimasi_pengerjaan"));
        $param["ESTIMASI_SELESAI"]              = tglToSql($this->post("estimasi_selesai"));
        $param["HASIL_ANALISA_SA"]              = $this->post("hasil_analisa_sa");
        $param["KEBUTUHAN_KONSUMEN"]            = $this->post("kebutuhan_konsumen");
        $param["SARAN_MEKANIK"]                 = $this->post("saran_mekanik");
        $param["KM_SAATINI"]                    = $this->post("km_saatini");
        $param["KD_PEKERJAAN"]                  = $this->post("kd_pekerjaan");
        $param["PART_NUMBER"]                   = $this->post("part_number");
        $param["TOTAL_FRT"]                     = $this->post("total_frt");
        $param["AMOUNT"]                        = $this->post("amount");
        $param["NO_PIT"]                        = $this->post("no_pit");
        $param["KD_TYPESERVICE"]                = $this->post("kd_typeservice");
        $param["KD_SETUPPEMBAYARAN"]            = $this->post("kd_setuppembayaran");
        $param["ALAMAT_COMINGCUSTOMER"]         = $this->post("alamat_comingcustomer");
        $param["BENSIN_SAATINI"]                = $this->post("bensin_saatini");
        $param["HP_COMINGCUSTOMER"]             = $this->post("hp_comingcustomer");
        $param["NO_BUKU"]                       = $this->post("no_buku");
        $param["STATUS_SA"]                     = $this->post("status_sa");
        $param["NO_STNK"]                       = $this->post("no_stnk");
        $param["NAMA_PEMILIK"]                  = $this->post("nama_pemilik");
        $param["NO_HP"]                         = $this->post("no_hp");
        $param["ALAMAT"]                        = $this->post("alamat");
        $param["NAMA_COMINGCUSTOMER"]           = $this->post("nama_comingcustomer");
        $param["KD_TYPEMOTOR"]                  = $this->post("kd_typemotor");
        $param["CATATAN_TAMBAHAN"]              = $this->post("catatan_tambahan");
        $param["KONFIRMASI_PEKERJAANTAMBAHAN"]  = $this->post("konfirmasi_pekerjaantambahan");
        $param["KD_PROPINSI"]                   = $this->post("kd_propinsi");
        $param["KD_KABUPATEN"]                  = $this->post("kd_kabupaten");
        $param["KD_KECAMATAN"]                  = $this->post("kd_kecamatan");
        $param["KD_KELURAHAN"]                  = $this->post("kd_kelurahan");
        $param["KD_PROPINSI_COMINGCUSTOMER"]    = $this->post("kd_propinsi_comingcustomer");
        $param["KD_KABUPATEN_COMINGCUSTOMER"]   = $this->post("kd_kabupaten_comingcustomer");
        $param["KD_KECAMATAN_COMINGCUSTOMER"]   = $this->post("kd_kecamatan_comingcustomer");
        $param["KD_KELURAHAN_COMINGCUSTOMER"]   = $this->post("kd_kelurahan_comingcustomer");
        $param["TAHUN"]                         = $this->post("tahun");
        $param["TGL_BELI"]                      = tglToSql($this->post("tgl_beli"));
        $param["CREATED_BY"]                    = $this->post("created_by");

        $this->resultdata("SP_TRANS_CSA_INSERT",$param,'post',TRUE);
    }

    /**
     * [csa_put description]
     * @return [type] [description]
     */
    public function csa_put(){
        $param = array();
        // $param["ID"]                            = $this->put("id");
        $param["KD_SA"]                         = $this->put("kd_sa");
        $param["KD_CUSTOMER"]                   = $this->put("kd_customer");
        $param["TANGGAL_SA"]                    = tglToSql($this->put("tanggal_sa"));
        $param["NO_POLISI"]                     = strtoupper($this->put("no_polisi")); 
        $param["NO_MESIN"]                      = $this->put("no_mesin"); 
        $param["NO_RANGKA"]                     = $this->put("no_rangka");
        $param["KD_PEMBAWAMOTOR"]               = $this->put("kd_pembawamotor");
        $param["KD_PEMAKAIMOTOR"]               = $this->put("kd_pemakaimotor");
        $param["KD_TYPECOMINGCUSTOMER"]         = $this->put("kd_typecomingcustomer");
        $param["KD_HONDA"]                      = $this->put("kd_honda");
        $param["KD_TIPEPKB"]                    = $this->put("kd_tipepkb");
        $param["KD_JENISPIT"]                   = $this->put("kd_jenispit");
        $param["FOTO_KONSUMEN"]                 = $this->put("foto_konsumen");
        $param["DOKUMEN"]                       = $this->put("dokumen");
        $param["ESTIMASI_PENDAFTARAN"]          = tglToSql($this->put("estimasi_pendaftaran"));
        $param["ESTIMASI_PENGERJAAN"]           = tglToSql($this->put("estimasi_pengerjaan"));
        $param["ESTIMASI_SELESAI"]              = tglToSql($this->put("estimasi_selesai"));
        $param["HASIL_ANALISA_SA"]              = $this->put("hasil_analisa_sa");
        $param["KEBUTUHAN_KONSUMEN"]            = $this->put("kebutuhan_konsumen");
        $param["SARAN_MEKANIK"]                 = $this->put("saran_mekanik");
        $param["KM_SAATINI"]                    = $this->put("km_saatini");
        $param["KD_PEKERJAAN"]                  = $this->put("kd_pekerjaan");
        $param["PART_NUMBER"]                   = $this->put("part_number");
        $param["TOTAL_FRT"]                     = $this->put("total_frt");
        $param["AMOUNT"]                        = $this->put("amount");
        $param["NO_PIT"]                        = $this->put("no_pit");
        $param["KD_TYPESERVICE"]                = $this->put("kd_typeservice");
        $param["KD_SETUPPEMBAYARAN"]            = $this->put("kd_setuppembayaran");
        $param["ALAMAT_COMINGCUSTOMER"]         = $this->put("alamat_comingcustomer");
        $param["BENSIN_SAATINI"]                = $this->put("bensin_saatini");
        $param["HP_COMINGCUSTOMER"]             = $this->put("hp_comingcustomer");
        $param["NO_BUKU"]                       = $this->put("no_buku"); 
        $param["STATUS_SA"]                     = $this->put("status_sa");
        $param["NO_STNK"]                       = $this->put("no_stnk");
        $param["NAMA_PEMILIK"]                  = $this->put("nama_pemilik");
        $param["NO_HP"]                         = $this->put("no_hp");
        $param["ALAMAT"]                        = $this->put("alamat");
        $param["NAMA_COMINGCUSTOMER"]           = $this->put("nama_comingcustomer");
        $param["KD_MAINDEALER"]                 = $this->put("kd_maindealer");
        $param["KD_DEALER"]                     = $this->put("kd_dealer");  
        $param["KD_LOKASIDEALER"]               = $this->put("kd_lokasidealer");
        $param["KD_TYPEMOTOR"]                  = $this->put("kd_typemotor");
        $param["CATATAN_TAMBAHAN"]              = $this->put("catatan_tambahan");
        $param["KONFIRMASI_PEKERJAANTAMBAHAN"]  = $this->put("konfirmasi_pekerjaantambahan");
        $param["KD_PROPINSI"]                   = $this->put("kd_propinsi");
        $param["KD_KABUPATEN"]                  = $this->put("kd_kabupaten");
        $param["KD_KECAMATAN"]                  = $this->put("kd_kecamatan");
        $param["KD_KELURAHAN"]                  = $this->put("kd_kelurahan");
        $param["KD_PROPINSI_COMINGCUSTOMER"]    = $this->put("kd_propinsi_comingcustomer");
        $param["KD_KABUPATEN_COMINGCUSTOMER"]   = $this->put("kd_kabupaten_comingcustomer");
        $param["KD_KECAMATAN_COMINGCUSTOMER"]   = $this->put("kd_kecamatan_comingcustomer");
        $param["KD_KELURAHAN_COMINGCUSTOMER"]   = $this->put("kd_kelurahan_comingcustomer");
        $param["TAHUN"]                         = $this->put("tahun");
        $param["TGL_BELI"]                      = tglToSql($this->put("tgl_beli"));
        $param["LASTMODIFIED_BY"]               = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_CSA_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [csa_delete description]
     * @return [type] [description]
     */
    public function csa_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_CSA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pro_gc_get description]
     * @return [type] [description]
     */
    public function pro_gc_get(){
        $param = array();
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_gc")){
            $param["KD_GC"]     = $this->get("kd_gc");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"      => $this->get("keyword"),
                "KD_GC"      => $this->get("keyword"),
                "DESC_PROGRAM"      => $this->get("keyword")
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
        $this->resultdata("TRANS_PRO_GC",$param);
    }
    
    /**
     * [pro_gc_post description]
     * @return [type] [description]
     */
    public function pro_gc_post(){
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["KD_GC"]             = $this->post("kd_gc");
        $param["NO_TRANS"]          = $this->post("no_trans");
        //$param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["DESC_PROGRAM"]      = $this->post("desc_program");
        $param["KD_TYPEMOTOR"]      = $this->post("kd_typemotor");
        $param["START_DATE"]        = tglToSql($this->post("start_date"));
        $param["END_DATE"]          = tglToSql($this->post("end_date"));
        $param["NO_PO_PERUSAHAAN"]  = $this->post("no_po_perusahaan");
        $param["KD_KABUPATEN"]      = $this->post("kd_kabupaten");
        $param["TYPE"]              = $this->post("type");
        $param["KD_LEASING"]        = $this->post("kd_leasing");
        $param["JENIS_TRANS"]       = $this->post("jenis_trans");
        $this->Main_model->data_sudahada($param,"TRANS_PRO_GC");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_TRANS_PRO_GC_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [pro_gc_put description]
     * @return [type] [description]
     */
    public function pro_gc_put(){
        $param = array();
        /*
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));*/
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["KD_GC"]             = $this->put("kd_gc");
        //$param["NO_TRANS"]          = $this->put("no_trans");
        $param["DESC_PROGRAM"]      = $this->put("desc_program");
        $param["KD_TYPEMOTOR"]      = $this->put("kd_typemotor");
        $param["START_DATE"]        = tglToSql($this->put("start_date"));
        $param["END_DATE"]          = tglToSql($this->put("end_date"));
        $param["NO_PO_PERUSAHAAN"]  = $this->put("no_po_perusahaan");
        $param["KD_KABUPATEN"]      = $this->put("kd_kabupaten");
        $param["TYPE"]              = $this->put("type");
        //$param["KD_LEASING"]        = $this->put("kd_leasing");
        $param["JENIS_TRANS"]       = $this->put("jenis_trans");
        //$param["STATUS_DOWNLOAD"]   = $this->put("status_download");
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PRO_GC_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pro_gc_status_put description]
     * @return [type] [description]
     */
    public function pro_gc_status_put(){
        $param = array();
       
        $param["ID"]                = $this->put("id");
        $param["STATUS_DOWNLOAD"]   = $this->put("status_download");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PRO_GC_STATUS_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [pro_gc_delete description]
     * @return [type] [description]
     */
    public function pro_gc_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PRO_GC_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pro_gc_detail_get description]
     * @return [type] [description]
     */
    public function pro_gc_detail_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
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
        $this->resultdata("TRANS_PRO_GC_DETAIL",$param);
    }
    
    /**
     * [pro_gc_detail_post description]
     * @return [type] [description]
     */
    public function pro_gc_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["KD_TYPEMOTOR"]      = $this->post("kd_typemotor");
        $this->Main_model->data_sudahada($param,"TRANS_PRO_GC_DETAIL");
        $param["QTY"]               = $this->post('qty');
        $param["S_AHM"]             = $this->post('s_ahm');
        $param["S_MD"]              = $this->post('s_md');
        $param["S_SD"]              = $this->post('s_sd');
        $param["SK_FINANCE"]        = $this->post('sk_finance');
        $param["SC_AHM"]            = $this->post('sc_ahm');
        $param["SC_MD"]             = $this->post('sc_md');
        $param["SC_SD"]             = $this->post('sc_sd');
        $param["HARGA_KONTRAK"]     = $this->post('harga_kontrak');
        $param["FEE"]               = $this->post('fee');
        $param["PENGURUSAN_STNK"]   = $this->post('pengurusan_stnk');
        $param["PENGURUSAN_BPKB"]   = $this->post('pengurusan_bpkb');
        
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_TRANS_PRO_GC_DETAIL_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [pro_gc_detail_put description]
     * @return [type] [description]
     */
    public function pro_gc_detail_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["KD_TYPEMOTOR"]      = $this->put("kd_typemotor");
        $param["QTY"]               = $this->put('qty');
        $param["S_AHM"]             = $this->put('s_ahm');
        $param["S_MD"]              = $this->put('s_md');
        $param["S_SD"]              = $this->put('s_sd');
        $param["SK_FINANCE"]        = $this->put('sk_finance');
        $param["SC_AHM"]            = $this->put('sc_ahm');
        $param["SC_MD"]             = $this->put('sc_md');
        $param["SC_SD"]             = $this->put('sc_sd');
        $param["HARGA_KONTRAK"]     = $this->put('harga_kontrak');
        $param["FEE"]               = $this->put('fee');
        $param["PENGURUSAN_STNK"]   = $this->put('pengurusan_stnk');
        $param["PENGURUSAN_BPKB"]   = $this->put('pengurusan_bpkb');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PRO_GC_DETAIL_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [pro_gc_detail_delete description]
     * @return [type] [description]
     */
    public function pro_gc_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PRO_GC_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pro_gc_leasing_get description]
     * @return [type] [description]
     */
    public function pro_gc_leasing_get(){
        $param = array();
        if($this->get("no_pro")){
            $param["NO_PRO"]     = $this->get("no_pro");
        }
        if($this->get("kd_leasing")){
            $param["KD_LEASING"]     = $this->get("kd_leasing");
        }

        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_PRO"      => $this->get("keyword")
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
        $this->resultdata("TRANS_PRO_GC_LEASING",$param);
    }
    
    /**
     * [pro_gc_leasing_post description]
     * @return [type] [description]
     */
    public function pro_gc_leasing_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["NO_PRO"]            = $this->post("no_pro");
        $param["KD_LEASING"]        = $this->post("kd_leasing");
        $this->Main_model->data_sudahada($param,"TRANS_PRO_GC_LEASING");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_TRANS_PRO_GC_LEASING_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [pro_gc_leasing_put description]
     * @return [type] [description]
     */
    public function pro_gc_leasing_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NO_PRO"]            = $this->put("no_pro");
        $param["KD_LEASING"]        = $this->put("kd_leasing");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PRO_GC_LEASING_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [pro_gc_leasing_delete description]
     * @return [type] [description]
     */
    public function pro_gc_leasing_delete(){
        $param = array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $this->resultdata("SP_TRANS_PRO_GC_LEASING_DELETE",$param,'delete',TRUE);
    }

    /**
     * [srut_get description]
     * @return [type] [description]
     */
    public function srut_get(){
        $param=array();$search="";
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]= $this->get('kd_dealer');
        }
        if($this->get('no_terima_dealer')){
            $param["NO_TERIMA_DEALER"]= $this->get('no_terima_dealer');
        }
        if($this->get('no_mesin')){
            $param["NO_MESIN"]= $this->get('no_mesin');
        }
        if($this->get('no_rangka')){
            $param["NO_RANGKA"]= $this->get('no_rangka');
        }
        if($this->get('no_penyerahan')){
            $param["NO_PENYERAHAN"]= $this->get('no_penyerahan');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_TERIMA_DEALER"  => $this->get("keyword"),
                "NO_MESIN"    => $this->get("keyword"),
                "NO_RANGKA"    => $this->get("keyword")
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
        $this->resultdata("TRANS_SRUT",$param);
    }
    
    /**
     * [srut_post description]
     * @return [type] [description]
     */
    public function srut_post(){
        $param = array();
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_TERIMA_DEALER"]  = $this->post("no_terima_dealer");
        $param["TGL_TERIMA"]        = $this->post("tgl_terima");
        $param["NO_MESIN"]          = $this->post("no_mesin");
        $param["NO_RANGKA"]         = $this->post("no_rangka");
        $this->Main_model->data_sudahada($param,"TRANS_SRUT");
        $param["NO_SUT"]            = $this->post("no_sut");
        $param["NO_SRUT"]           = $this->post("no_srut");
        $param["CREATED_BY"]        = $this->post("created_by");    
        $this->resultdata("SP_TRANS_SRUT_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [srut_put description]
     * @return [type] [description]
     */
    public function srut_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_TERIMA_DEALER"]  = $this->put("no_terima_dealer");
        $param["TGL_TERIMA"]        = tglToSql($this->put("tgl_terima"));
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["NO_RANGKA"]         = $this->put("no_rangka");
        $param["NO_SUT"]            = $this->put("no_sut");
        $param["NO_SRUT"]           = $this->put("no_srut");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SRUT_UPDATE",$param,'put',TRUE);
    }

    /**
     * [srut_norangka_put description]
     * @return [type] [description]
     */
    public function srut_norangka_put(){
        $param = array();
        $param["STATUS_SRUT"]       = $this->put("status_srut");
        $param["NO_PENYERAHAN"]     = $this->put("no_penyerahan");
        $param["NO_RANGKA"]         = $this->put("no_rangka");
        $param["NAMA_PENERIMA"]     = $this->put("nama_penerima");
        $param["TGL_PENYERAHAN"]    = tglToSql($this->put("tgl_penyerahan"));
        $param["ALAMAT"]            = $this->put("alamat");
        $param["NO_HP"]             = $this->put("no_hp");
        $param["STATUS_PENERIMA"]   = $this->put("status_penerima");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SRUT_UPDATE_NORANGKA",$param,'put',TRUE);
    }
    /**
     * [srut_file_put description]
     * @return [type] [description]
     */
    public function srut_penyerahan_file_put(){
        $param = array();
        $param["NO_RANGKA"]                 = $this->put('no_rangka');
        $param["DIRECTORY_BUKTIPENYERAHAN"] = $this->put('directory_buktipenyerahan');
        $param["STATUS_PENERIMA"]           = $this->put('status_penerima');
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SRUT_PENYERAHAN_FILE_UPDATE",$param,'put',TRUE);
    }

    /**
     * [srut_penyerahan_file_put description]
     * @return [type] [description]
     */
    public function srut_penyerahan_group_file_put(){
        $param = array();
        $param["NO_PENYERAHAN"]             = $this->put('no_penyerahan');
        $param["DIRECTORY_BUKTIPENYERAHAN"] = $this->put('directory_buktipenyerahan');
        $param["STATUS_PENERIMA"]           = $this->put('status_penerima');
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_SRUT_PENYERAHAN_GROUP_FILE_UPDATE",$param,'put',TRUE);
    } 
    
    /**
     * [srut_delete description]
     * @return [type] [description]
     */
    public function srut_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SRUT_DELETE",$param,'delete',TRUE);
    }
    public function datassu_get($tipe_ssu=null){
        $param=array();$search="";
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]= $this->get('kd_dealer');
        }
        if($this->get('no_mesin')){
            $param["NO_MESIN"]= $this->get('no_mesin');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_MESIN"    => $this->get("keyword"),
                "KD_DEALER"    => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        /*$this->Main_model->set_where_in(array('JBK1E1577435','JBK1E1578258','JBK1E1578258'));
        $this->Main_model->set_whereinfield('NO_MESIN');*/
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        switch($tipe_ssu){
            case 'udstk': 
            case 'UDSTK': $this->Main_model->set_custom_query($this->Custom_model->getdatassu($this->get('kd_dealer'),'UDSTK')); $this->resultdata("TRANS_SSU_UDSTK",$param);break; //generate new data udstk
            case 'cddb' : 
            case 'CDDB' : $this->Main_model->set_custom_query($this->Custom_model->getdatassu($this->get('kd_dealer'),'CDDB')); $this->resultdata("TRANS_SSU_CDDB",$param);break;//generate new data udstk
            case 'udprg': 
            case 'UDPRG': $this->Main_model->set_custom_query($this->Custom_model->getdatassu($this->get('kd_dealer'),'UDPRG')); $this->resultdata("TRANS_SSU_UDPRG",$param);break;//generate new data udstk
            case 'udstk_d' :$this->resultdata("TRANS_SSU_DETAIL_UDSTK",$param);break;//edit proposed
            case 'cddb_d' :$this->resultdata("TRANS_SSU_DETAIL_CDDB",$param);break;
            case 'udprg_d' :$this->resultdata("TRANS_SSU_DETAIL_UDPRG",$param);break;
            case 'txt': $this->resultdata("TRANS_SSU_TXT",$param);break;
            case 'SDPKB': $this->resultdata("TRANS_SSU_SDPKB",$param);break;//generate new data sdpkb

        }
        
    }
    /**
     * [ssu_get description]
     * @return [type] [description]
     */
    public function ssu_get($view=null){
        $param=array();$search="";
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]= $this->get('kd_dealer');
        }
        if($this->get('no_mesin')){
            $param["NO_MESIN"]= $this->get('no_mesin');
        }
        if($this->get('nama_file')){
            $param["NAMA_FILE"]= $this->get('nama_file');
        }
        if($this->get('no_trans')){
            $param["NO_TRANS"]= $this->get('no_trans');
        }
        if($this->get('type_file')){
            $param["TIPE_FILE"]= $this->get('type_file');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_MESIN"    => $this->get("keyword"),
                "NAMA_FILE"    => $this->get("keyword")
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
        if($view){
            $this->resultdata("TRANS_SSU_VIEW",$param);
        }else{
            $this->resultdata("TRANS_SSU",$param);
        }
    }
    
    /**
     * [ssu_post description]
     * @return [type] [description]
     */
    public function ssu_post(){
        $param = array();
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["NO_MESIN"]          = $this->post("no_mesin");
        $param["NAMA_FILE"]         = $this->post("nama_file");
        $param["TYPE_FILE"]         = $this->post("type_file");
        $this->Main_model->data_sudahada($param,"TRANS_SSU");
        $param["STATUS_DOWNLOAD"]   = $this->post("status_download");
        $param["TGL_DOWNLOAD"]      = tglToSql($this->post("tgl_download"));
        $param["CREATED_BY"]        = $this->post("created_by");    
        $this->resultdata("SP_TRANS_SSU_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [ssu_put description]
     * @return [type] [description]
     */
    public function ssu_put(){
        $param = array();
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["NAMA_FILE"]         = $this->put("nama_file");
        $param["TYPE_FILE"]         = $this->put("type_file");
        $param["STATUS_DOWNLOAD"]   = $this->put("status_download");
        $param["TGL_DOWNLOAD"]      = tglToSql($this->put("tgl_download"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_UPDATE",$param,'put',TRUE);
    }
    public function ssu_download_put(){
        $param = array();
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_UPDATE_DOWNLOAD",$param,'put',TRUE);
    }
    /**
     * [ssu_delete description]
     * @return [type] [description]
     */
    public function ssu_delete($deleteall="0"){
        $param = array();
        $param["NO_MESIN"]          = $this->delete("no_mesin");
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $param["MODEL"] = $deleteall;
        $this->resultdata("SP_TRANS_SSU_DELETE",$param,'delete',TRUE);
    }
    public function ssu_udstk_post(){
        $param=array();
        $param["NO_MESIN"]= $this->post("no_mesin");
        $this->Main_model->data_sudahada($param,"TRANS_SSU_DETAIL_UDSTK");
        $param["KD_DEALER"]= $this->post("kd_dealer");
        $param["SPK_ID"]= $this->post("spk_id");
        $param["STATUS_SPK"]= $this->post("status_spk");
        $param["NO_RANGKA"]= $this->post("no_rangka");
        $param["NO_MESIN1"]= $this->post("no_mesin1");
        $param["NO_MESIN2"]= $this->post("no_mesin2");
        $param["NAMA_BPKB"]= $this->post("nama_bpkb");
        $param["ALAMAT_BPKB"]= $this->post("alamat_bpkb");
        $param["NAMA_KELURAHAN"]= $this->post("nama_kelurahan");
        $param["NAMA_KECAMATAN"]= $this->post("nama_kecamatan");
        $param["KD_KABUPATEN"]= $this->post("kd_kabupaten");
        $param["KODE_POS"]= $this->post("kode_pos");
        $param["KD_PROPINSI"]= $this->post("kd_propinsi");
        $param["JENIS_PEMBELIAN"]= $this->post("jenis_pembelian");
        $param["KD_LEASING"]= $this->post("kd_leasing");
        $param["UANG_MUKA"]= (double)$this->post("uang_muka");
        $param["JANGKA_WAKTU"]= $this->post("jangka_waktu");
        $param["JUMLAH_ANGSURAN"]= (double)$this->post("jml_angsuran");
        $param["HONDA_ID"]= $this->post("honda_id");
        $param["KD_POSAHM"]= $this->post("kd_posahm");
        $param["KTP_BPKB"]= $this->post("ktp_bpkb");
        $param["EMAIL_BPKB"]= $this->post("email_bpkb");
        $param["CREATED_BY"] =$this->post("created_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_UDSTK_INSERT",$param,'post',TRUE);
    }
    public function ssu_udstk_put(){
        $param=array();
        $param["SPK_ID"]= $this->put("spk_id");
        $param["STATUS_SPK"]= $this->put("status_spk");
        $param["KD_DEALER"]= $this->put("kd_dealer");
        $param["NO_RANGKA"]= $this->put("no_rangka");
        $param["NO_MESIN1"]= $this->put("no_mesin1");
        $param["NO_MESIN2"]= $this->put("no_mesin2");
        $param["NO_MESIN"]= $this->put("no_mesin");
        $param["NAMA_BPKB"]= $this->put("nama_bpkb");
        $param["ALAMAT_BPKB"]= $this->put("alamat_bpkb");
        $param["NAMA_KELURAHAN"]= $this->put("nama_kelurahan");
        $param["NAMA_KECAMATAN"]= $this->put("nama_kecamatan");
        $param["KD_KABUPATEN"]= $this->put("kd_kabupaten");
        $param["KODE_POS"]= $this->put("kode_pos");
        $param["KD_PROPINSI"]= $this->put("kd_propinsi");
        $param["JENIS_PEMBELIAN"]= $this->put("jenis_pembelian");
        $param["KD_LEASING"]= $this->put("kd_leasing");
        $param["UANG_MUKA"]= (double)$this->put("uang_muka");
        $param["JANGKA_WAKTU"]= $this->put("jangka_waktu");
        $param["JUMLAH_ANGSURAN"]= (double)$this->put("jml_angsuran");
        $param["HONDA_ID"]= $this->put("honda_id");
        $param["KD_POSAHM"]= $this->put("kd_posahm");
        $param["KTP_BPKB"]= $this->put("ktp_bpkb");
        $param["EMAIL_BPKB"]= $this->put("email_bpkb");
        $param["ROW_STATUS"]= $this->put("row_status");
        $param["LASTMODIFIED_BY"] =$this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_UDSTK_UPDATE",$param,'put',TRUE);
    }
    public function ssu_udstk_delete(){
        $param=array();
        $param["KD_DEALER"]= $this->put("kd_dealer");
        $param["NO_MESIN"]= $this->put("no_mesin");
        $param["LASTMODIFIED_BY"] =$this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_UDSTK_DELETE",$param,'delete',TRUE);
    }
    public function ssu_cddb_post(){
        $param=array();
        $param["NO_MESIN"] = ($this->post("no_mesin"));
        ($this->Main_model->data_sudahada($param,"TRANS_SSU_DETAIL_CDDB"));
        //print_r($param);exit();
        $param["KD_DEALER"]= $this->post("kd_dealer");
        $param["SPK_ID"]= $this->post("spk_id");
        $param["STATUS_SPK"]= $this->post("status_spk");
        $param["NO_RANGKA"]= $this->post("no_rangka");
        $param["NO_MESIN1"]= $this->post("no_mesin1");
        $param["NO_MESIN2"]= $this->post("no_mesin2");
        $param["NO_KTP"]= $this->post("no_ktp");
        $param["KD_CUSTOMER"]= $this->post("kd_customer");
        $param["JENIS_KELAMIN"]= $this->post("jenis_kelamin");
        $param["TGL_LAHIR"]= $this->post("tgl_lahir");
        $param["ALAMAT_SURAT"]= $this->post("alamat_surat");
        $param["NAMA_DESA"]= $this->post("nama_desa");
        $param["NAMA_KECAMATAN"]= $this->post("nama_kecamatan");
        $param["KD_KOTA"]= $this->post("kd_kota");
        $param["KODE_POS"]= $this->post("kode_pos");
        $param["KD_PROPINSI"]= $this->post("kd_propinsi");
        $param["KD_AGAMA"]= $this->post("kd_agama");
        $param["EMAIL"]= $this->post("email");
        $param["STATUS_RUMAH"]= $this->post("status_rumah");
        $param["STATUS_HP"]= $this->post("status_hp");
        $param["STATUS_DIHUBUNGI"]= $this->post("status_dihubungi");
        $param["AKUN_FB"]= $this->post("akun_fb");
        $param["TWITTER"]= $this->post("twitter");
        $param["INSTAGRAM"]= $this->post("instagram");
        $param["YOUTUBE"]= $this->post("youtube");
        $param["HOBI"]= $this->post("hobi");
        $param["KETERANGAN"]= $this->post("keterangan");
        $param["KARTU_KELUARGA"]= $this->post("kartu_keluarga");
        $param["WNI"]= $this->post("wni");
        $param["REFF_ID"]= $this->post("reff_id");
        $param["ROBB_ID"]= $this->post("robb_id");
        $param["PEKERJAAN"]= $this->post("pekerjaan");
        $param["PENGELUARAN"]= $this->post("pengeluaran");
        $param["PENDIDIKAN"]= $this->post("pendidikan");
        $param["PIC_PERUSAHAAN"]= $this->post("pic_perusahaan");
        $param["NO_HP"]= $this->post("no_hp");
        $param["NO_TELP"]= $this->post("no_telp");
        $param["INFORMASI_BARU"]= $this->post("informasi_baru");
        $param["MERK_MOTOR"]= $this->post("merk_motor");
        $param["JENIS_MOTOR"]= $this->post("jenis_motor");
        $param["DIGUNAKAN_UNTUK"]= $this->post("digunakan_untuk");
        $param["YANG_MENGGUNAKAN"]= $this->post("yang_menggunakan");
        $param["KD_SALES"]= $this->post("kd_sales");
        $param["CREATED_BY"] =$this->post("created_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_CDDB_INSERT",$param,'post',TRUE);
    }
    public function ssu_cddb_put(){
        $param=array();
        $param["SPK_ID"]= $this->put("spk_id");
        $param["STATUS_SPK"]= $this->put("status_spk");
        $param["KD_DEALER"]= $this->put("kd_dealer");
        $param["NO_RANGKA"]= $this->put("no_rangka");
        $param["NO_MESIN1"]= $this->put("no_mesin1");
        $param["NO_MESIN2"]= $this->put("no_mesin2");
        $param["NO_MESIN"]= $this->put("no_mesin");
        $param["NO_KTP"]= $this->put("no_ktp");
        $param["KD_CUSTOMER"]= $this->put("kd_customer");
        $param["JENIS_KELAMIN"]= $this->put("jenis_kelamin");
        $param["TGL_LAHIR"]= $this->put("tgl_lahir");
        $param["ALAMAT_SURAT"]= $this->put("alamat_surat");
        $param["NAMA_DESA"]= $this->put("nama_desa");
        $param["NAMA_KECAMATAN"]= $this->put("nama_kecamatan");
        $param["KD_KOTA"]= $this->put("kd_kota");
        $param["KODE_POS"]= $this->put("kode_pos");
        $param["KD_PROPINSI"]= $this->put("kd_propinsi");
        $param["KD_AGAMA"]= $this->put("kd_agama");
        $param["EMAIL"]= $this->put("email");
        $param["STATUS_RUMAH"]= $this->put("status_rumah");
        $param["STATUS_HP"]= $this->put("status_hp");
        $param["STATUS_DIHUBUNGI"]= $this->put("status_dihubungi");
        $param["AKUN_FB"]= $this->put("akun_fb");
        $param["TWITTER"]= $this->put("twitter");
        $param["INSTAGRAM"]= $this->put("instagram");
        $param["YOUTUBE"]= $this->put("youtube");
        $param["HOBI"]= $this->put("hobi");
        $param["KETERANGAN"]= $this->put("keterangan");
        $param["KARTU_KELUARGA"]= $this->put("kartu_keluarga");
        $param["WNI"]= $this->put("wni");
        $param["REFF_ID"]= $this->put("reff_id");
        $param["ROBB_ID"]= $this->put("robb_id");
        $param["PEKERJAAN"]= $this->put("pekerjaan");
        $param["PENGELUARAN"]= $this->put("pengeluaran");
        $param["PENDIDIKAN"]= $this->put("pendidikan");
        $param["PIC_PERUSAHAAN"]= $this->put("pic_perusahaan");
        $param["NO_HP"]= $this->put("no_hp");
        $param["NO_TELP"]= $this->put("no_telp");
        $param["INFORMASI_BARU"]= $this->put("informasi_baru");
        $param["MERK_MOTOR"]= $this->put("merk_motor");
        $param["JENIS_MOTOR"]= $this->put("jenis_motor");
        $param["DIGUNAKAN_UNTUK"]= $this->put("digunakan_untuk");
        $param["YANG_MENGGUNAKAN"]= $this->put("yang_menggunakan");
        $param["KD_SALES"]= $this->put("kd_sales");
        $param["ROW_STATUS"]= $this->put("row_status");
        $param["LASTMODIFIED_BY"] =$this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_CDDB_UPDATE",$param,'put',TRUE);
    }
    public function ssu_cddb_delete(){
        $param=array();
        $param["KD_DEALER"]= $this->put("kd_dealer");
        $param["NO_MESIN"]= $this->put("no_mesin");
        $param["LASTMODIFIED_BY"] =$this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_CDDB_DELETE",$param,'delete',TRUE);
    }
    public function ssu_udprg_post(){
        $param["NO_MESIN"]= $this->post("no_mesin");
        $this->Main_model->data_sudahada($param,"TRANS_SSU_DETAIL_UDPRG");
        $param["KD_DEALER"]= $this->post("kd_dealer");
        $param["SPK_ID"]= $this->post("spk_id");
        $param["STATUS_SPK"]= $this->post("status_spk");
        $param["NO_RANGKA"]= $this->post("no_rangka");
        $param["KD_LEASING"]= $this->post("kd_leasing");
        $param["KD_SALESPROGRAM"]= $this->post("kd_salesprogram");
        $param["TELP_KOSONG"]= $this->post("telp_kosong");
        $param["HP_KOSONG"]= $this->post("hp_kosong");
        $param["TGL_BELI"]= $this->post("tgl_beli");
        $param["KD_SALESPROGRAMAHM"]= $this->post("kd_salesprogramahm");
        $param["LOKAL_SP"]= $this->post("lokal_sp");
        $param["UANG_MUKA"]= (double)$this->post("uang_muka");
        $param["JENIS_BELI"]= $this->post("jenis_beli");
        $param["ASAL_JUAL"]= $this->post("asal_jual");
        $param["KD_LOKASI"]= $this->post("kd_lokasi");
        $param["SALES_FORCE"]= $this->post("sales_force");
        $param["KD_DESA"]= $this->post("kd_desa");
        $param["KD_KECAMATAN"]= $this->post("kd_kecamatan");
        $param["DP_SETOR"]= (double)$this->post("dp_setor");
        $param["SUSB_AHM"]= (double)$this->post("susb_ahm");
        $param["SUB_MD"]= (double)$this->post("sub_md");
        $param["SUB_DLR"]= (double)$this->post("sub_dlr");
        $param["SUB_FIN"]= (double)$this->post("sub_fin");
        $param["SPLIT_OTR"]= $this->post("split_otr");
        $param["RO"]= $this->post("ro");
        $param["RO_MESIN"]= $this->post("ro_mesin");
        $param["JENIS_CUSTOMER"]= $this->post("jenis_customer");
        $param["OF_TR"]= $this->post("of_tr");
        $param["KELURAHAN_SURAT"]= $this->post("kelurahan_surat");
        $param["KECAMATAN_SURAT"]= $this->post("kecamatan_surat");
        $param["JML_ANGSURAN"]= (double)$this->post("jml_angsuran");
        $param["CREATED_BY"] = $this->post("created_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_UDPRG_INSERT",$param,'post',TRUE);
    }
    function ssu_udprg_put(){
        $param=array();
        $param["SPK_ID"]= $this->put("spk_id");
        $param["STATUS_SPK"]= $this->put("status_spk");
        $param["KD_DEALER"]= $this->put("kd_dealer");
        $param["NO_RANGKA"]= $this->put("no_rangka");
        $param["NO_MESIN"]= $this->put("no_mesin");
        $param["KD_LEASING"]= $this->put("kd_leasing");
        $param["KD_SALESPROGRAM"]= $this->put("kd_salesprogram");
        $param["TELP_KOSONG"]= $this->put("telp_kosong");
        $param["HP_KOSONG"]= $this->put("hp_kosong");
        $param["TGL_BELI"]= $this->put("tgl_beli");
        $param["KD_SALESPROGRAMAHM"]= $this->put("kd_salesprogramahm");
        $param["LOKAL_SP"]= $this->put("lokal_sp");
        $param["UANG_MUKA"]= (double)$this->put("uang_muka");
        $param["JENIS_BELI"]= $this->put("jenis_beli");
        $param["ASAL_JUAL"]= $this->put("asal_jual");
        $param["KD_LOKASI"]= $this->put("kd_lokasi");
        $param["SALES_FORCE"]= $this->put("sales_force");
        $param["KD_DESA"]= $this->put("kd_desa");
        $param["KD_KECAMATAN"]= $this->put("kd_kecamatan");
        $param["DP_SETOR"]= (double)$this->put("dp_setor");
        $param["SUSB_AHM"]= (double)$this->put("susb_ahm");
        $param["SUB_MD"]= (double)$this->put("sub_md");
        $param["SUB_DLR"]= (double)$this->put("sub_dlr");
        $param["SUB_FIN"]= (double)$this->put("sub_fin");
        $param["SPLIT_OTR"]= $this->put("split_otr");
        $param["RO"]= $this->put("ro");
        $param["RO_MESIN"]= $this->put("ro_mesin");
        $param["JENIS_CUSTOMER"]= $this->put("jenis_customer");
        $param["OF_TR"]= $this->put("of_tr");
        $param["KELURAHAN_SURAT"]= $this->put("kelurahan_surat");
        $param["KECAMATAN_SURAT"]= $this->put("kecamatan_surat");
        $param["JML_ANGSURAN"]= (double)$this->put("jml_angsuran");
        $param["ROW_STATUS"]= $this->put("row_status");
        $param["LASTMODIFIED_BY"] =$this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_UDPRG_UPDATE",$param,'put',TRUE);
    }
    function ssu_udprg_delete(){
        $param=array();
        $param["KD_DEALER"]= $this->put("kd_dealer");
        $param["NO_MESIN"]= $this->put("no_mesin");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SSU_DETAIL_UDPRG_DELETE",$param,'delete',TRUE);
    }


//trans_csa_detail
    public function csa_detail_get(){
        $param = array();
        if($this->get("kd_sa")){
            $param["KD_SA"]     = $this->get("kd_sa");
        }
        if($this->get("kd_pekerjaan")){
            $param["KD_PEKERJAAN"]     = $this->get("kd_pekerjaan");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_SA"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_CSA_DETAIL",$param);
    }
    /**
     * [csa_detail_post description]
     * @return [type] [description]
     */
    public function csa_detail_post(){
        $param = array();
        
        $param["KD_SA"]             = $this->post('kd_sa');
        $param["KD_PEKERJAAN"]      = $this->post('kd_pekerjaan');
        $this->Main_model->data_sudahada($param,"TRANS_CSA_DETAIL",TRUE);
        $param["QTY"]               = $this->post('qty');
        $param["HARGA_SATUAN"]      = $this->post('harga_satuan');
        $param["TOTAL_HARGA"]       = $this->post('total_harga');
        $param["KATEGORI"]          = $this->post('kategori');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_CSA_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [csa_detail_put description]
     * @return [type] [description]
     */
    public function csa_detail_put(){
        $param = array();
        // $param["ID"]                = $this->put('id');
        $param["KD_SA"]             = $this->put('kd_sa');
        $param["KD_PEKERJAAN"]      = $this->put('kd_pekerjaan');
        $param["QTY"]               = $this->put('qty');
        $param["HARGA_SATUAN"]      = $this->put('harga_satuan');
        $param["TOTAL_HARGA"]       = $this->put('total_harga');
        $param["KATEGORI"]          = $this->put('kategori');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_CSA_DETAIL_UPDATE",$param,'put',TRUE);
        
    }
    /**
     * [csa_detail_delete description]
     * @return [type] [description]
     */
    public function csa_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_CSA_DETAIL_DELETE",$param,'delete',TRUE);
    }

    //trans_monitoring
    public function monitoring_get(){
        $param = array();
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_dealer")){
            $param["NAMA_DEALER"]     = $this->get("nama_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_DEALER"      => $this->get("keyword"),
                "NAMA_DEALER"      => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_MONITORING",$param);
    }
}
?>