<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Master_hc3 extends REST_Controller {

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
    * Get data master_metodefu
    * @access public
    */
    public function metodefu_get(){
        $param=array(); $result=array();
        if($this->get('nama_metode')){
            $param["NAMA_METODE"]  = $this->get('nama_metode');
        }
        
        if($this->get('id')){
            $param["ID"]  = $this->get('id');
        }

        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_METODE" =>$this->get("keyword")
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
        $this->resultdata("MASTER_METODEFU",$param);
        
    }
    /**
     * [metodefu_post description]
     * @return [type] [description]
     */
    public function metodefu_post(){
        
        $param = array();
        $param["NAMA_METODE"]      = $this->post('nama_metode');
        $this->Main_model->data_sudahada($param,"MASTER_METODEFU");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_METODEFU_INSERT",$param,'post',TRUE);

    }
    /**
     * 
     * @return [type] [description]
     */
    public function metodefu_put(){
        $param=array();
        $param["ID"]     = $this->put('id');
        $param["NAMA_METODE"]   = $this->put('nama_metode');
        $param["ROW_STATUS"]            = $this->put("row_status");
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");
        
        $this->resultdata("SP_MASTER_METODEFU_UPDATE",$param,'put',TRUE);
    }
    /**
     * Delete metodefu tidak dilakukan di applikasi ini
     * @return [type] [description]
     */
    public function metodefu_delete(){
       
        $param=array();
        $param["ID"] = $this->delete("id");
        $param["LASTMODIFIED_BY"]       = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_METODEFU_DELETE",$param,'delete',TRUE);
    }
   
    /**
    * Get data trans_fu
    * @access public
    */
    public function fu_get(){
        $param=array(); $result=array();
        if($this->get('no_trans')){
            $param["NO_TRANS"]  = $this->get('no_trans');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"]  = $this->get('kd_maindealer');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]  = $this->get('kd_dealer');
        }
        if($this->get('kd_fu')){
            $param["KD_FU"]  = $this->get('kd_fu');
        }
        if($this->get('kd_customer')){
            $param["KD_CUSTOMER"]  = $this->get('kd_customer');
        }
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS" =>$this->get("keyword"),
                "KD_FU" =>$this->get("keyword"),
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
        $this->resultdata("TRANS_FU",$param);
        
    }
    /**
     * [fu_post description]
     * @return [type] [description]
     */
    public function fu_post(){
        
        $param = array();
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->post('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_FU"]             = $this->post('kd_fu');
        $param["KD_CUSTOMER"]       = $this->post('kd_customer');
        $this->Main_model->data_sudahada($param,"TRANS_FU");
        $param["WAKTU_KEAHASS"]     = tglToSql($this->post('waktu_keahass'));
        $param["WAKTU_REMINDERFU"]  = tglToSql($this->post('waktu_reminderfu'));
        $param["TIPE_MOTOR"]        = $this->post('tipe_motor');
        $param["KD_TIPEPKB"]        = $this->post('kd_tipepkb');
        $param["KETERANGAN"]        = $this->post('keterangan');
        $param["HONDA_ID"]          = $this->post('honda_id');
        $param["STATUS_FUAKHIR"]    = $this->post('status_fuakhir');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_FU_INSERT",$param,'post',TRUE);

    }
    /**
     * 
     * @return [type] [description]
     */
    public function fu_put(){
        $param=array();
        $param["ID"]                = $this->put('id');
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_FU"]             = $this->put('kd_fu');
        $param["WAKTU_KEAHASS"]     = tglToSql($this->put('waktu_keahass'));
        $param["WAKTU_REMINDERFU"]  = tglToSql($this->put('waktu_reminderfu'));
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["TIPE_MOTOR"]        = $this->put('tipe_motor');
        $param["KD_TIPEPKB"]        = $this->put('kd_tipepkb');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["HONDA_ID"]          = $this->put('honda_id');
        $param["STATUS_FUAKHIR"]    = $this->put('status_fuakhir');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        
        $this->resultdata("SP_TRANS_FU_UPDATE",$param,'put',TRUE);
    }
    /**
     * Delete fu 
     * @return [type] [description]
     */
    public function fu_delete(){
       
        $param=array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_DELETE",$param,'delete',TRUE);
    }


    /**
    * Get data trans_fu_thanks
    * @access public
    */
    public function fu_thanks_get(){
        $param=array(); $result=array();
        if($this->get('no_trans')){
            $param["NO_TRANS"]  = $this->get('no_trans');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"]  = $this->get('kd_maindealer');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]  = $this->get('kd_dealer');
        }
        if($this->get('kd_fu_thanks')){
            $param["KD_FU_THANKS"]  = $this->get('kd_fu_thanks');
        }
        if($this->get('kd_customer')){
            $param["KD_CUSTOMER"]  = $this->get('kd_customer');
        }
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS" =>$this->get("keyword"),
                "KD_FU_THANKS" =>$this->get("keyword"),
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
        $this->resultdata("TRANS_FU_THANKS",$param);
        
    }
    /**
     * [fu_thanks_post description]
     * @return [type] [description]
     */
    public function fu_thanks_post(){
        
        $param = array();
        $param["NO_TRANS"]          = $this->post('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->post('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_FU_THANKS"]      = $this->post('kd_fu_thanks');
        $param["KD_CUSTOMER"]       = $this->post('kd_customer');
        $this->Main_model->data_sudahada($param,"TRANS_FU_THANKS");
        $param["HONDA_ID"]          = $this->post('honda_id');
        $param["KD_METODEFU"]       = $this->post('kd_metodefu');
        $param["KD_UNITDELIVERY"]   = $this->post('kd_unitdelivery');
        $param["NO_FRAME"]          = $this->post('no_frame');
        $param["TGL_PEMBELIAN"]     = tglToSql($this->post('tgl_pembelian'));
        $param["NAMA_CUSTOMER"]     = $this->post('nama_customer');
        $param["TGL_LAHIR"]         = tglToSql($this->post('tgl_lahir'));
        $param["NO_HP"]             = $this->post('no_hp');
        $param["ALAMAT_SURAT"]      = $this->post('alamat_surat');
        $param["KELURAHAN"]         = $this->post('kelurahan');
        $param["KECAMATAN"]         = $this->post('kecamatan');
        $param["KOTA"]              = $this->post('kota');
        $param["KODE_POS"]          = $this->post('kode_pos');
        $param["PROPINSI"]          = $this->post('propinsi');
        $param["AGAMA"]             = $this->post('agama');
        $param["EMAIL"]             = $this->post('email');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_FU_THANKS_INSERT",$param,'post',TRUE);

    }
    /**
     * 
     * @return [type] [description]
     */
    public function fu_thanks_put(){
        $param=array();
        $param["ID"]                = $this->put('id');
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["TGL_TRANS"]         = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_FU_THANKS"]      = $this->put('kd_fu_thanks');
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["HONDA_ID"]          = $this->put('honda_id');
        $param["KD_METODEFU"]       = $this->put('kd_metodefu');
        $param["KD_UNITDELIVERY"]   = $this->put('kd_unitdelivery');
        $param["NO_FRAME"]          = $this->put('no_frame');
        $param["TGL_PEMBELIAN"]     = tglToSql($this->put('tgl_pembelian'));
        $param["NAMA_CUSTOMER"]     = $this->put('nama_customer');
        $param["TGL_LAHIR"]         = tglToSql($this->put('tgl_lahir'));
        $param["NO_HP"]             = $this->put('no_hp');
        $param["ALAMAT_SURAT"]      = $this->put('alamat_surat');
        $param["KELURAHAN"]         = $this->put('kelurahan');
        $param["KECAMATAN"]         = $this->put('kecamatan');
        $param["KOTA"]              = $this->put('kota');
        $param["KODE_POS"]          = $this->put('kode_pos');
        $param["PROPINSI"]          = $this->put('propinsi');
        $param["AGAMA"]             = $this->put('agama');
        $param["EMAIL"]             = $this->put('email');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        
        $this->resultdata("SP_TRANS_FU_THANKS_UPDATE",$param,'put',TRUE);
    }
    /**
     * Delete fu_thanks 
     * @return [type] [description]
     */
    public function fu_thanks_delete(){
       
        $param=array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_THANKS_DELETE",$param,'delete',TRUE);
    }

    /**
    * Get data trans_fu_thanks_detail
    * @access public
    */
    public function fu_thanks_detail_get(){
        $param=array(); $result=array();
        if($this->get('no_trans')){
            $param["NO_TRANS"]  = $this->get('no_trans');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"]  = $this->get('kd_maindealer');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]  = $this->get('kd_dealer');
        }
        if($this->get('kd_fu_thanks')){
            $param["KD_FU_THANKS"]  = $this->get('kd_fu_thanks');
        }
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS" =>$this->get("keyword"),
                "KD_FU_THANKS" =>$this->get("keyword")
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
        $this->resultdata("TRANS_FU_THANKS_DETAIL",$param);
        
    }
    /**
     * [fu_thanks_detail_post description]
     * @return [type] [description]
     */
    public function fu_thanks_detail_post(){
        
        $param = array();
        $param["NO_TRANS"]              = $this->post('no_trans');
        $param["KD_MAINDEALER"]         = $this->post('kd_maindealer');
        $param["KD_DEALER"]             = $this->post('kd_dealer');
        $param["KD_FU_THANKS"]          = $this->post('kd_fu_thanks');
        $this->Main_model->data_sudahada($param,"TRANS_FU_THANKS_DETAIL");
        $param["KD_METODEFU"]           = $this->post('kd_metodefu');
        $param["KD_SETUP_STATUSCALL"]   = $this->post('kd_setup_statuscall');
        $param["NAMA_METODEFU"]         = $this->post('nama_metodefu');
        $param["TGL_FU"]                = tglToSql($this->post('tgl_fu'));
        $param["STATUS_METODE"]         = $this->post('status_metode');
        $param["KET_THANKS"]            = $this->post('ket_thanks');
        $param["REMINDER_KPB1"]         = $this->post('reminder_kpb1');
        $param["INFORMASI_DEALER"]      = $this->post('informasi_dealer');
        $param["CREATED_BY"]            = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_FU_THANKS_DETAIL_INSERT",$param,'post',TRUE);

    }
    /**
     * 
     * @return [type] [description]
     */
    public function fu_thanks_detail_put(){
        $param=array();
        $param["ID"]                    = $this->put('id');
        $param["NO_TRANS"]              = $this->put('no_trans');
        $param["KD_MAINDEALER"]         = $this->put('kd_maindealer');
        $param["KD_DEALER"]             = $this->put('kd_dealer');
        $param["KD_FU_THANKS"]          = $this->put('kd_fu_thanks');
        $param["KD_METODEFU"]           = $this->put('kd_metodefu');
        $param["KD_SETUP_STATUSCALL"]   = $this->put('kd_setup_statuscall');
        $param["NAMA_METODEFU"]         = $this->put('nama_metodefu');
        $param["TGL_FU"]                = tglToSql($this->put('tgl_fu'));
        $param["STATUS_METODE"]         = $this->put('status_metode');
        $param["KET_THANKS"]            = $this->put('ket_thanks');
        $param["REMINDER_KPB1"]         = $this->put('reminder_kpb1');
        $param["INFORMASI_DEALER"]      = $this->put('informasi_dealer');
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_THANKS_DETAIL_UPDATE",$param,'put',TRUE);
    }

    /**
     * Delete fu_thanks_detail 
     * @return [type] [description]
     */
    public function fu_thanks_detail_delete(){
       
        $param=array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_THANKS_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
    * Get data trans_fu_service
    * @access public
    */
    public function fu_service_get(){
        $param=array(); $result=array();
        if($this->get('no_trans')){
            $param["NO_TRANS"]  = $this->get('no_trans');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"]  = $this->get('kd_maindealer');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]  = $this->get('kd_dealer');
        }
        if($this->get('kd_fu_service_reminderh2')){
            $param["KD_FU_SERVICE_REMINDERH2"]  = $this->get('kd_fu_service_reminderh2');
        }
        if($this->get('kd_metodefu')){
            $param["KD_METODEFU"]  = $this->get('kd_metodefu');
        }
        if($this->get('jenis_kpb')){
            $param["JENIS_KPB"]  = $this->get('jenis_kpb');
        }
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_CUSTOMER" =>$this->get("keyword"),
                "NO_TRANS" =>$this->get("keyword"),
                "KD_FU_SERVICE_REMINDERH2" =>$this->get("keyword"),
                "KD_METODEFU" =>$this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_FU_SERVICE",$param);
        
    }
    /**
     * [fu_service_post description]
     * @return [type] [description]
     */
    public function fu_service_post(){
        
        $param = array();
        $param["NO_TRANS"]                  = $this->post('no_trans');
        $param["TGL_TRANS"]                 = tglToSql($this->post('tgl_trans'));
        $param["KD_MAINDEALER"]             = $this->post('kd_maindealer');
        $param["KD_DEALER"]                 = $this->post('kd_dealer');
        $param["KD_FU_SERVICE_REMINDERH2"]  = $this->post('kd_fu_service_reminderh2');
        $this->Main_model->data_sudahada($param,"TRANS_FU_SERVICE");
        $param["KD_METODEFU"]               = $this->post('kd_metodefu');
        $param["KD_CUSTOMER"]               = $this->post('kd_customer');
        $param["KD_MOTOR"]                  = $this->post('kd_motor');
        $param["NO_RANGKA"]                 = $this->post('no_rangka');
        $param["NO_MESIN"]                  = $this->post('no_mesin');
        $param["NO_POLISI"]                 = $this->post('no_polisi');
        $param["TGL_BELI"]                  = tglToSql($this->post('tgl_beli'));
        $param["NAMA_STNK"]                 = $this->post('nama_stnk');
        $param["NO_HP"]                     = $this->post('no_hp');
        $param["ALAMAT_SURAT"]              = $this->post('alamat_surat');
        $param["KELURAHAN_SURAT"]           = $this->post('kelurahan_surat');
        $param["KECAMATAN_SURAT"]           = $this->post('kecamatan_surat');
        $param["KOTA_SURAT"]                = $this->post('kota_surat');
        $param["KODE_POS"]                  = $this->post('kode_pos');
        $param["PROPINSI_SURAT"]            = $this->post('propinsi_surat');
        $param["JENIS_KPB"]                  = $this->post('jenis_kpb');
        $param["CREATED_BY"]                = $this->post('created_by');
        // print_r($param);
        $this->resultdata("SP_TRANS_FU_SERVICE_INSERT",$param,'post',TRUE);

    }
    /**
     * 
     * @return [type] [description]
     */
    public function fu_service_put(){
        $param=array();
        $param["NO_TRANS"]                  = $this->put('no_trans');
        $param["TGL_TRANS"]                 = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]             = $this->put('kd_maindealer');
        $param["KD_DEALER"]                 = $this->put('kd_dealer');
        $param["KD_FU_SERVICE_REMINDERH2"]  = $this->put('kd_fu_service_reminderh2');
        $param["KD_METODEFU"]               = $this->put('kd_metodefu');
        $param["KD_CUSTOMER"]               = $this->put('kd_customer');
        $param["KD_MOTOR"]                  = $this->put('kd_motor');
        $param["NO_RANGKA"]                 = $this->put('no_rangka');
        $param["NO_MESIN"]                  = $this->put('no_mesin');
        $param["NO_POLISI"]                 = $this->put('no_polisi');
        $param["TGL_BELI"]                  = tglToSql($this->put('tgl_beli'));
        $param["NAMA_STNK"]                 = $this->put('nama_stnk');
        $param["NO_HP"]                     = $this->put('no_hp');
        $param["ALAMAT_SURAT"]              = $this->put('alamat_surat');
        $param["KELURAHAN_SURAT"]           = $this->put('kelurahan_surat');
        $param["KECAMATAN_SURAT"]           = $this->put('kecamatan_surat');
        $param["KOTA_SURAT"]                = $this->put('kota_surat');
        $param["KODE_POS"]                  = $this->put('kode_pos');
        $param["PROPINSI_SURAT"]            = $this->put('propinsi_surat');
        $param["JENIS_KPB"]                  = $this->put('jenis_kpb');
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_SERVICE_UPDATE",$param,'put',TRUE);
    }
    /**
     * Delete fu_service 
     * @return [type] [description]
     */
    public function fu_service_delete(){
       
        $param=array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_SERVICE_DELETE",$param,'delete',TRUE);
    }

    /**
    * Get data trans_fu_service_detail
    * @access public
    */
    public function fu_service_detail_get(){
        $param=array(); $result=array();
        if($this->get('no_trans')){
            $param["NO_TRANS"]  = $this->get('no_trans');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"]  = $this->get('kd_maindealer');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]  = $this->get('kd_dealer');
        }
        if($this->get('kd_fu_service_reminderh2')){
            $param["KD_FU_SERVICE_REMINDERH2"]  = $this->get('kd_fu_service_reminderh2');
        }
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS" =>$this->get("keyword"),
                "KD_FU_SERVICE_REMINDERH2" =>$this->get("keyword")
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
        $this->resultdata("TRANS_FU_SERVICE_DETAIL",$param);
        
    }
    /**
     * [fu_service_detail_post description]
     * @return [type] [description]
     */
    public function fu_service_detail_post(){
        
        $param = array();
        $param["NO_TRANS"]                      = $this->post('no_trans');
        $param["TGL_TRANS"]                     = tglToSql($this->post('tgl_trans'));
        $param["KD_MAINDEALER"]                 = $this->post('kd_maindealer');
        $param["KD_DEALER"]                     = $this->post('kd_dealer');
        $param["KD_FU_SERVICE_REMINDERH2"]      = $this->post('kd_fu_service_reminderh2');
        $this->Main_model->data_sudahada($param,"TRANS_FU_SERVICE_DETAIL");
        $param["KD_METODEFU"]                   = $this->post('kd_metodefu');
        $param["KD_SETUP_STATUSSMS"]            = $this->post('kd_setup_statussms');
        $param["KD_SETUP_STATUSCALL"]           = $this->post('kd_setup_statuscall');
        $param["KD_SETUP_HASILFU_SERVICEH2"]    = $this->post('kd_setup_hasilfu_serviceh2');
        $param["NAMA_METODEFU"]                 = $this->post('nama_metodefu');
        $param["TGL_METODEFU"]                  = tglToSql($this->post('tgl_metodefu'));
        $param["KD_STATUS_METODEFU"]            = $this->post('kd_status_metodefu');
        $param["STATUS_METODEFU"]               = $this->post('status_metodefu');
        $param["HASIL_METODEFU"]                = $this->post('hasil_metodefu');
        $param["NAMA_METODEFU2"]                = $this->post('nama_metodefu2');
        // $param["TGL_METODEFU2"]                 = tglToSql($this->post('tgl_metodefu2'));
        $param["KD_STATUS_METODEFU2"]           = $this->post('kd_status_metodefu2');
        $param["STATUS_METODEFU2"]              = $this->post('status_metodefu2');
        $param["HASIL_METODEFU2"]               = $this->post('hasil_metodefu2');
        $param["KD_METODEFU2"]                  = $this->post('kd_metodefu2');
        $param["KD_SETUP_HASILFU2_SERVICEH2"]   = $this->post('kd_setup_hasilfu2_serviceh2');
        $param["STATUS_BOOKING"]                = $this->post('status_booking');
        $param["ALASAN_BOOKING"]                = $this->post('alasan_booking');
        $param["STATUS_FU"]                     = $this->post('status_fu');
        $param["CREATED_BY"]                    = $this->post('created_by');
        // print_r($param);
        $this->resultdata("SP_TRANS_FU_SERVICE_DETAIL_INSERT",$param,'post',TRUE);

    }
    /**
     * [fu_service_detail_put description]
     * @return [type] [description]
     */
    public function fu_service_detail_put(){
        $param=array();
        $param["NO_TRANS"]                      = $this->put('no_trans');
        $param["TGL_TRANS"]                     = tglToSql($this->put('tgl_trans'));
        $param["KD_MAINDEALER"]                 = $this->put('kd_maindealer');
        $param["KD_DEALER"]                     = $this->put('kd_dealer');
        $param["KD_FU_SERVICE_REMINDERH2"]      = $this->put('kd_fu_service_reminderh2');
        $param["KD_METODEFU"]                   = $this->put('kd_metodefu');
        $param["KD_SETUP_STATUSSMS"]            = $this->put('kd_setup_statussms');
        $param["KD_SETUP_STATUSCALL"]           = $this->put('kd_setup_statuscall');
        $param["KD_SETUP_HASILFU_SERVICEH2"]    = $this->put('kd_setup_hasilfu_serviceh2');
        $param["NAMA_METODEFU"]                 = $this->put('nama_metodefu');
        $param["TGL_METODEFU"]                  = tglToSql($this->put('tgl_metodefu'));
        $param["KD_STATUS_METODEFU"]            = $this->put('kd_status_metodefu');
        $param["STATUS_METODEFU"]               = $this->put('status_metodefu');
        $param["HASIL_METODEFU"]                = $this->put('hasil_metodefu');
        $param["NAMA_METODEFU2"]                = $this->put('nama_metodefu2');
        $param["TGL_METODEFU2"]                 = tglToSql($this->put('tgl_metodefu2'));
        $param["KD_STATUS_METODEFU2"]           = $this->put('kd_status_metodefu2');
        $param["STATUS_METODEFU2"]              = $this->put('status_metodefu2');
        $param["HASIL_METODEFU2"]               = $this->put('hasil_metodefu2');
        $param["KD_METODEFU2"]                  = $this->put('kd_metodefu2');
        $param["KD_SETUP_HASILFU2_SERVICEH2"]   = $this->put('kd_setup_hasilfu2_serviceh2');
        $param["STATUS_BOOKING"]                = $this->put('status_booking');
        $param["ALASAN_BOOKING"]                = $this->put('alasan_booking');
        $param["STATUS_FU"]                     = $this->put('status_fu');
        $param["LASTMODIFIED_BY"]               = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_SERVICE_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * Delete fu_service_detail
     * @return [type] [description]
     */
    public function fu_service_detail_delete(){
       
        $param=array();
        $param["NO_TRANS"]          = $this->delete("no_trans");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_FU_SERVICE_DETAIL_DELETE",$param,'delete',TRUE);
     }


    /**
     * [laporan_lmkpb_get description]
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    public function laporan_lmkpb_get($metode){
        $kd_dealer = $this->get("kd_dealer");
        $tgl_awal = $this->get("tgl_awal");
        $tgl_akhir = $this->get("tgl_akhir");
        $jenis_kpb = $this->get("jenis_kpb");

        // var_dump($_GET);exit;

        $this->Main_model->set_custom_query($this->Custom_model->mt_metodefu($kd_dealer,$tgl_awal,$tgl_akhir,$jenis_kpb, $metode));
        
        $this->resultdata("TRANS_FU_SERVICE",$param=array());
    }

    /**
     * [weekly_gb_get description]
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    public function weekly_gb_get(){
        $param=array();
        $kd_dealer = ($this->get("where_in"))?implode("','", $this->get("where_in")):$this->get("kd_dealer");
        $tgl_awal = $this->get("tgl_awal");
        $tgl_akhir = $this->get("tgl_akhir");
        $gb_source = $this->get("gb_source");

        // var_dump($_GET);exit;

        $this->Main_model->set_custom_query($this->Custom_model->mt_metode($kd_dealer,$tgl_awal,$tgl_akhir,$gb_source));
        
        $this->resultdata("TRANS_GUESTBOOK",$param=array());
    }

    /**
    * Get data trans_fu_call_view
    * @access public
    */
    public function fu_call_view_get(){
        $param=array(); $result=array();
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]  = $this->get('kd_dealer');
        }
        if($this->get('no_pkb')){
            $param["NO_PKB"]  = $this->get('no_pkb');
        }
        if($this->get('no_rangka')){
            $param["NO_RANGKA"]  = $this->get('no_rangka');
        }
        if($this->get('kd_item')){
            $param["KD_ITEM"]  = $this->get('kd_item');
        }
        if($this->get('jenis_kpb')){
            $param["JENIS_KPB"]  = $this->get('jenis_kpb');
        }
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_PKB" =>$this->get("keyword"),
                "NO_RANGKA" =>$this->get("keyword")
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
        $this->resultdata("TRANS_FOLLOWUP_CALL_VIEW",$param);
        
    }

}
?>