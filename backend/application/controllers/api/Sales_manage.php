<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * @date_modify()[24-11-2017]
 */
class Sales_manage extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
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
     * [master_jenis_event_get description]
     * @return [type] [description]
     */
    public function master_jenis_event_get(){
       $param = array();$search='';
        if($this->get("kd_jenis_event")){
            $param["KD_JENIS_EVENT"]     = $this->get("kd_jenis_event");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("nama_jenis_event")){
            $param["NAMA_JENIS_EVENT"]     = $this->get("nama_jenis_event");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_JENIS_EVENT"     => $this->get("keyword")
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
        $this->resultdata("MASTER_JENIS_EVENT",$param);
    }

    /**
     * [master_jenis_event_post description]
     * @return [type] [description]
     */
    public function master_jenis_event_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_JENIS_EVENT"]    = $this->post('kd_jenis_event');
        $param["NAMA_JENIS_EVENT"]  = $this->post("nama_jenis_event");
        $this->Main_model->data_sudahada($param,"MASTER_JENIS_EVENT",TRUE);
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_MASTER_JENIS_EVENT_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [master_jenis_event_put description]
     * @return [type] [description]
     */
    public function master_jenis_event_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_JENIS_EVENT"]    = $this->put('kd_jenis_event');
        $param["NAMA_JENIS_EVENT"]  = $this->put("nama_jenis_event");
        $param["NEED_APPROVAL"]     = $this->put("need_approval");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_JENIS_EVENT_UPDATE",$param,'put',TRUE);
    }

    public function master_jenis_event_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_MASTER_JENIS_EVENT_DELETE",$param,'delete',TRUE);
    }

    /**
     * [event_approval_get description]
     * @return [type] [description]
     */
    public function event_approval_get(){
       $param = array();$search='';
        if($this->get("kd_event")){
            $param["KD_EVENT"]     = $this->get("kd_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_EVENT"     => $this->get("keyword")
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
        $this->resultdata("TRANS_EVENT_APPROVAL",$param);
    }

    /**
     * [event_approval_post description]
     * @return [type] [description]
     */
    public function event_approval_post(){
        $param = array();
        $param["KD_EVENT"]          = $this->post('kd_event');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["APPROVAL_LEVEL"]    = $this->post("approval_level");
        $param["APPROVAL_BY"]       = $this->post("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->post("approval_date"));
        $param["KETERANGAN"]        = $this->post("keterangan");
        $this->Main_model->data_sudahada($param,"TRANS_EVENT_APPROVAL",TRUE);
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_EVENT_APPROVAL_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [event_approval_put description]
     * @return [type] [description]
     */
    public function event_approval_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_EVENT"]          = $this->put('kd_event');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["APPROVAL_LEVEL"]    = $this->put("approval_level");
        $param["APPROVAL_BY"]       = $this->put("approval_by");
        $param["APPROVAL_DATE"]     = tglToSql($this->put("approval_date"));
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_APPROVAL_UPDATE",$param,'put',TRUE);
    }

    public function event_approval_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_EVENT_APPROVAL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [event_budget_get description]
     * @return [type] [description]
     */
    public function event_budget_get(){
       $param = array();$search='';
        if($this->get("kd_event")){
            $param["KD_EVENT"]     = $this->get("kd_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_budget")){
            $param["KD_BUDGET"]     = $this->get("kd_budget");
        }
        if($this->get("nama_budget")){
            $param["NAMA_BUDGET"]     = $this->get("nama_budget");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_EVENT"     => $this->get("keyword"),
                "NAMA_BUDGET"     => $this->get("keyword"),
                "KD_BUDGET"     => $this->get("keyword"),
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
        $this->resultdata("TRANS_EVENT_BUDGET",$param);
    }

    /**
     * [event_budget_post description]
     * @return [type] [description]
     */
    public function event_budget_post(){
        $param = array();
        $param["KD_EVENT"]          = $this->post('kd_event');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_BUDGET"]         = $this->post("kd_budget");
        $param["NAMA_BUDGET"]       = $this->post("nama_budget");
        $param["JUMLAH_BUDGET"]     = $this->post("jumlah_budget");
        $param["AKTUAL_BUDGET"]     = $this->post("aktual_budget");
        $param["KETERANGAN_BUDGET"] = $this->post("keterangan_budget");
        $this->Main_model->data_sudahada($param,"TRANS_EVENT_BUDGET",TRUE);
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_EVENT_BUDGET_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [event_budget_put description]
     * @return [type] [description]
     */
    public function event_budget_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_EVENT"]          = $this->put('kd_event');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_BUDGET"]         = $this->put("kd_budget");
        $param["NAMA_BUDGET"]       = $this->put("nama_budget");
        $param["JUMLAH_BUDGET"]     = $this->put("jumlah_budget");
        $param["AKTUAL_BUDGET"]     = $this->put("aktual_budget");
        $param["KETERANGAN_BUDGET"] = $this->put("keterangan_budget");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_BUDGET_UPDATE",$param,'put',TRUE);
    }

    /**
     * [event_budget_aktual_put description]
     * @return [type] [description]
     */
    public function event_budget_aktual_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_BUDGET"]         = $this->put("kd_budget");
        $param["NAMA_BUDGET"]       = $this->put("nama_budget");
        $param["AKTUAL_BUDGET"]     = $this->put("aktual_budget");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_BUDGET_UPD_AKTUAL",$param,'put',TRUE);
    }

    public function event_budget_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_EVENT_BUDGET_DELETE",$param,'delete',TRUE);
    }

    /**
     * [event_create_get description]
     * @return [type] [description]
     */
    public function event_create_get(){
       $param = array();$search='';
        if($this->get("kd_event")){
            $param["KD_EVENT"]     = $this->get("kd_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_jenis_event")){
            $param["KD_JENIS_EVENT"]     = $this->get("kd_jenis_event");
        }
        if($this->get("nama_event")){
            $param["NAMA_EVENT"]     = $this->get("nama_event");
        }
        if($this->get("inisiasi_event")){
            $param["INISIASI_EVENT"]     = $this->get("inisiasi_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_EVENT"     => $this->get("keyword"),
                "KD_JENIS_EVENT"     => $this->get("keyword"),
                "NAMA_EVENT"     => $this->get("keyword"),
                "KD_DEALER"     => $this->get("keyword"),
                "INISIASI_EVENT"     => $this->get("keyword")
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
        $this->resultdata("TRANS_EVENT_CREATE",$param);
    }

    /**
     * [event_create_post description]
     * @return [type] [description]
     */
    public function event_create_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_JENIS_EVENT"]    = $this->post("kd_jenis_event");
        $param["INISIASI_EVENT"]    = $this->post("inisiasi_event");
        $param["KD_EVENT"]          = $this->post('kd_event');
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["NAMA_EVENT"]        = $this->post("nama_event");
        $this->Main_model->data_sudahada($param,"TRANS_EVENT_CREATE",TRUE);
        $param["KETERANGAN_EVENT"]  = $this->post("keterangan_event");
        $param["ASSIGN_EVENT"]      = $this->post('assign_event');
        $param["START_DATE"]        = tglToSql($this->post("start_date"));
        $param["END_DATE"]          = tglToSql($this->post("end_date"));
        $param["TARGET_UNIT"]       = $this->post("target_unit");
        $param["TARGET_REVENUE"]    = $this->post("target_revenue");
        $param["STATUS_EVENT"]      = $this->post("status_event");
        $param["ALAMAT_EVENT"]      = $this->post("alamat_event");
        $param["KD_DESA"]           = $this->post("kd_desa");
        $param["KD_KECAMATAN"]      = $this->post("kd_kecamatan");
        $param["KD_KABUPATEN"]      = $this->post("kd_kabupaten");
        $param["KD_PROPINSI"]       = $this->post("kd_propinsi");
        $param["APPROVAL_MD"]       = $this->post("approval_md");
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_EVENT_CREATE_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [event_create_put description]
     * @return [type] [description]
     */
    public function event_create_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_JENIS_EVENT"]    = $this->put("kd_jenis_event");
        $param["INISIASI_EVENT"]    = $this->put("inisiasi_event");
        $param["KD_EVENT"]          = $this->put('kd_event');
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["NAMA_EVENT"]        = $this->put("nama_event");
        $param["KETERANGAN_EVENT"]  = $this->put("keterangan_event");
        $param["ASSIGN_EVENT"]      = $this->put('assign_event');
        $param["START_DATE"]        = tglToSql($this->put("start_date"));
        $param["END_DATE"]          = tglToSql($this->put("end_date"));
        $param["TARGET_UNIT"]       = $this->put("target_unit");
        $param["TARGET_REVENUE"]    = $this->put("target_revenue");
        //$param["STATUS_EVENT"]      = $this->put("status_event");
        $param["ALAMAT_EVENT"]      = $this->put("alamat_event");
        $param["KD_DESA"]           = $this->put("kd_desa");
        $param["KD_KECAMATAN"]      = $this->put("kd_kecamatan");
        $param["KD_KABUPATEN"]      = $this->put("kd_kabupaten");
        $param["KD_PROPINSI"]       = $this->put("kd_propinsi");
        $param["APPROVAL_MD"]       = $this->put("approval_md");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_CREATE_UPDATE",$param,'put',TRUE);
    }

    /**
     * [event_app_put description]
     * @return [type] [description]
     */
    public function event_app_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["APPROVAL_MD"]       = $this->put("approval_md");
        $param["ALASAN_REJECT"]     = $this->put("alasan_reject");
        $param["APPROVAL_TIME"]     = tglToSql($this->put("approval_time"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_CREATE_UPDATE_APP",$param,'put',TRUE);
    }

    public function event_status_put(){
        $param = array();
     
        $param["KD_EVENT"]          = $this->put("kd_event");
        $param["STATUS_EVENT"]      = $this->put("status_event");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_CREATE_UPDATE_STATUS",$param,'put',TRUE);
    }

    public function event_create_delete(){
        $param = array();
        $param["KD_EVENT"]          = $this->delete("kd_event");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_EVENT_CREATE_DELETE",$param,'delete',TRUE);
    }

    /**
     * [event_people_get description]
     * @return [type] [description]
     */
    public function event_people_get(){
       $param = array();$search='';
        if($this->get("kd_event")){
            $param["KD_EVENT"]     = $this->get("kd_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("nama_sales")){
            $param["NAMA_SALES"]     = $this->get("nama_sales");
        }
        if($this->get("jabatan_sales")){
            $param["JABATAN_SALES"]     = $this->get("jabatan_sales");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_EVENT"     => $this->get("keyword"),
                "KD_SALES"     => $this->get("keyword"),
                "NAMA_SALES"     => $this->get("keyword"),
                "JABATAN_SALES"     => $this->get("keyword")
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
        $this->resultdata("TRANS_EVENT_PEOPLE",$param);
    }

    /**
     * [event_people_post description]
     * @return [type] [description]
     */
    public function event_people_post(){
        $param = array();
        $param["KD_EVENT"]          = $this->post('kd_event');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_SALES"]          = $this->post("kd_sales");
        $param["NAMA_SALES"]        = $this->post("nama_sales");
        $this->Main_model->data_sudahada($param,"TRANS_EVENT_PEOPLE",TRUE);
        $param["JABATAN_SALES"]     = $this->post("jabatan_sales");
        $param["KEHADIRAN_SALES"]   = $this->post("kehadiran_sales");
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_EVENT_PEOPLE_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [event_people_put description]
     * @return [type] [description]
     */
    public function event_people_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_EVENT"]          = $this->put('kd_event');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_SALES"]          = $this->put("kd_sales");
        $param["NAMA_SALES"]        = $this->put("nama_sales");
        $param["JABATAN_SALES"]     = $this->put("jabatan_sales");
        $param["KEHADIRAN_SALES"]   = $this->put("kehadiran_sales");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_PEOPLE_UPDATE",$param,'put',TRUE);
    }

    public function event_people_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_EVENT_PEOPLE_DELETE",$param,'delete',TRUE);
    }

    /**
     * [event_unit2display_get description]
     * @return [type] [description]
     */
    public function event_unit2display_get(){
       $param = array();$search='';
        if($this->get("kd_event")){
            $param["KD_EVENT"]     = $this->get("kd_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("nama_sales")){
            $param["NAMA_SALES"]     = $this->get("nama_sales");
        }
        if($this->get("jabatan_sales")){
            $param["JABATAN_SALES"]     = $this->get("jabatan_sales");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_EVENT"     => $this->get("keyword"),
                "KD_SALES"     => $this->get("keyword"),
                "NAMA_SALES"     => $this->get("keyword"),
                "JABATAN_SALES"     => $this->get("keyword")
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
        $this->resultdata("TRANS_EVENT_UNT2DISPLAY",$param);
    }

    /**
     * [event_unit2display_post description]
     * @return [type] [description]
     */
    public function event_unit2display_post(){
        $param = array();
        $param["KD_EVENT"]          = $this->post('kd_event');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_ITEM"]           = $this->post("kd_item");
        $param["NAMA_ITEM"]         = $this->post("nama_item");
        $this->Main_model->data_sudahada($param,"TRANS_EVENT_UNT2DISPLAY",TRUE);
        $param["NO_RANGKA"]         = $this->post("no_rangka");
        $param["NO_MESIN"]          = $this->post("no_mesin");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["NO_MUTASI_IN"]      = $this->post("no_mutasi_in");
        $param["TGL_TERIMA"]        = tglToSql($this->post("tgl_terima"));
        $param["NO_MUTASI_OUT"]     = $this->post("no_mutasi_out");
        $param["TGL_KEMBALI"]       = tglToSql($this->post("tgl_kembali"));
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_EVENT_UNT2DISPLAY_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [event_unit2display_put description]
     * @return [type] [description]
     */
    public function event_unit2display_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_EVENT"]          = $this->put('kd_event');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_ITEM"]           = $this->put("kd_item");
        $param["NAMA_ITEM"]         = $this->put("nama_item");
        $param["NO_RANGKA"]         = $this->put("no_rangka");
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["NO_MUTASI_IN"]      = $this->put("no_mutasi_in");
        $param["TGL_TERIMA"]        = tglToSql($this->put("tgl_terima"));
        $param["NO_MUTASI_OUT"]     = $this->put("no_mutasi_out");
        $param["TGL_KEMBALI"]       = tglToSql($this->put("tgl_kembali"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_UNT2DISPLAY_UPDATE",$param,'put',TRUE);
    }

    public function event_unit2display_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_EVENT_UNT2DISPLAY_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *TRANS_EVENT_SALESPROGRAM
    */
    public function event_salesprogram_get(){
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
        $this->resultdata("TRANS_EVENT_SALESPROGRAM",$param);
    }
    /**
     * [event_salesprogram_post description]
     * @return [type] [description]
     */
    public function event_salesprogram_post(){
        $param = array();
        $param["KD_EVENT"]              = $this->post('kd_event');
        $param["KD_DEALER"]             = $this->post('kd_dealer');
        $param["KD_SALESPROGRAM"]       = $this->post('kd_salesprogram');
        $this->Main_model->data_sudahada($param,"TRANS_EVENT_SALESPROGRAM");
        $param["KD_TYPEMOTOR"]          = $this->post('kd_typemotor');
        $param["START_DATE"]            = tglToSql($this->post('start_date'));
        $param["END_DATE"]              = tglToSql($this->post('end_date'));
        $param["NAMA_SALESPROGRAM"]     = $this->post('nama_salesprogram');
        $param["TIPE_SALESPROGRAM"]     = $this->post('tipe_salesprogram');
        $param["KD_SALESPROGRAMAHM"]    = $this->post('kd_salesprogramahm');
        $param["NO_SURATSP"]            = $this->post('no_suratsp');
        $param["SALESPROGRAM_KHUSUS"]   = $this->post('salesprogram_khusus');
        $param["SALESPROGRAM_GIFT"]     = $this->post('salesprogram_gift');
        $param["SALESPROGRAM_CABANG"]   = $this->post('salesprogram_cabang');
        $param["POT_START"]             = tglToSql($this->post('pot_start'));
        $param["POT_END"]               = tglToSql($this->post('pot_end'));
        $param["SSU_START"]             = tglToSql($this->post('ssu_start'));
        $param["SSU_END"]               = tglToSql($this->post('ssu_end'));
        $param["END_CLAIM"]             = tglToSql($this->post('end_claim'));
        $param["QTY"]                   = $this->post('qty');
        $param["SK_AHM"]                = $this->post('sk_ahm');
        $param["SK_MD"]                 = $this->post('sk_md');
        $param["SK_SD"]                 = $this->post('sk_sd');
        $param["SK_FINANCE"]            = $this->post('sk_finance');
        $param["SC_AHM"]                = $this->post('sc_ahm');
        $param["SC_MD"]                 = $this->post('sc_md');
        $param["SC_SD"]                 = $this->post('sc_sd');
        $param["CB_AHM"]                = $this->post('cb_ahm');
        $param["CB_MD"]                 = $this->post('cb_md');
        $param["CB_SD"]                 = $this->post('cb_sd');
        $param["POT_FAKTUR"]            = $this->post('pot_faktur');
        $param["CASH_TEMPO"]            = $this->post('cash_tempo');
        $param["SPLIT_OTR"]             = $this->post('split_otr');
        $param["SPLIT_OTR2"]            = $this->post('split_otr2');
        $param["HADIAH_LANGSUNG"]       = $this->post('hadiah_langsung');
        $param["HARGA_KONTRAK"]         = $this->post('harga_kontrak');
        $param["FEE"]                   = $this->post('fee');
        $param["PENGURUSAN_STNK"]       = $this->post('pengurusan_stnk');
        $param["PENGURUSAN_BPKB"]       = $this->post('pengurusan_bpkb');
        $param["NO_PO"]                 = $this->post('no_po');
        $param["MIN_SK_SD"]             = $this->post('min_sk_sd');
        $param["MIN_SC_SD"]             = $this->post('min_sc_sd');
        $param["DP_OTR"]                = $this->post('dp_otr');
        $param["TAMBAHAN_FINANCE"]      = $this->post('tambahan_finance');
        $param["TAMBAHAN_MD"]           = $this->post('tambahan_md');
        $param["TAMBAHAN_SD"]           = $this->post('tambahan_sd');
        $param["TUNDA_FAKTUR"]          = $this->post('tunda_faktur');
        $param["HADIAH_LANGSUNG2"]      = $this->post('hadiah_langsung2');
        $param["KETERANGAN_HADIAH"]     = $this->post('keterangan_hadiah');
        $param["TAMBAHAN_AHM"]          = $this->post('tambahan_ahm');
        $param["CREATED_BY"]            = $this->post('created_by');
        // print_r($param);
        $this->resultdata("SP_TRANS_EVENT_SALESPROGRAM_INSERT",$param,'post',TRUE);
    }
    /**
     * [event_salesprogram_put description]
     * @return [type] [description]
     */
    public function event_salesprogram_put(){
        $param = array();
        $param["ID"]                    = $this->put('id');
        $param["KD_EVENT"]              = $this->put('kd_event');
        $param["KD_DEALER"]             = $this->put('kd_dealer');
        $param["KD_SALESPROGRAM"]       = $this->put('kd_salesprogram');
        $param["KD_TYPEMOTOR"]          = $this->put('kd_typemotor');
        $param["START_DATE"]            = tglToSql($this->put('start_date'));
        $param["END_DATE"]              = tglToSql($this->put('end_date'));
        $param["NAMA_SALESPROGRAM"]     = $this->put('nama_salesprogram');
        $param["TIPE_SALESPROGRAM"]     = $this->put('tipe_salesprogram');
        $param["KD_SALESPROGRAMAHM"]    = $this->put('kd_salesprogramahm');
        $param["NO_SURATSP"]            = $this->put('no_suratsp');
        $param["SALESPROGRAM_KHUSUS"]   = $this->put('salesprogram_khusus');
        $param["SALESPROGRAM_GIFT"]     = $this->put('salesprogram_gift');
        $param["SALESPROGRAM_CABANG"]   = $this->put('salesprogram_cabang');
        $param["POT_START"]             = tglToSql($this->put('pot_start'));
        $param["POT_END"]               = tglToSql($this->put('pot_end'));
        $param["SSU_START"]             = tglToSql($this->put('ssu_start'));
        $param["SSU_END"]               = tglToSql($this->put('ssu_end'));
        $param["END_CLAIM"]             = tglToSql($this->put('end_claim'));
        $param["QTY"]                   = $this->put('qty');
        $param["SK_AHM"]                = $this->put('sk_ahm');
        $param["SK_MD"]                 = $this->put('sk_md');
        $param["SK_SD"]                 = $this->put('sk_sd');
        $param["SK_FINANCE"]            = $this->put('sk_finance');
        $param["SC_AHM"]                = $this->put('sc_ahm');
        $param["SC_MD"]                 = $this->put('sc_md');
        $param["SC_SD"]                 = $this->put('sc_sd');
        $param["CB_AHM"]                = $this->put('cb_ahm');
        $param["CB_MD"]                 = $this->put('cb_md');
        $param["CB_SD"]                 = $this->put('cb_sd');
        $param["POT_FAKTUR"]            = $this->put('pot_faktur');
        $param["CASH_TEMPO"]            = $this->put('cash_tempo');
        $param["SPLIT_OTR"]             = $this->put('split_otr');
        $param["SPLIT_OTR2"]            = $this->put('split_otr2');
        $param["HADIAH_LANGSUNG"]       = $this->put('hadiah_langsung');
        $param["HARGA_KONTRAK"]         = $this->put('harga_kontrak');
        $param["FEE"]                   = $this->put('fee');
        $param["PENGURUSAN_STNK"]       = $this->put('pengurusan_stnk');
        $param["PENGURUSAN_BPKB"]       = $this->put('pengurusan_bpkb');
        $param["NO_PO"]                 = $this->put('no_po');
        $param["MIN_SK_SD"]             = $this->put('min_sk_sd');
        $param["MIN_SC_SD"]             = $this->put('min_sc_sd');
        $param["DP_OTR"]                = $this->put('dp_otr');
        $param["TAMBAHAN_FINANCE"]      = $this->put('tambahan_finance');
        $param["TAMBAHAN_MD"]           = $this->put('tambahan_md');
        $param["TAMBAHAN_SD"]           = $this->put('tambahan_sd');
        $param["TUNDA_FAKTUR"]          = $this->put('tunda_faktur');
        $param["HADIAH_LANGSUNG2"]      = $this->put('hadiah_langsung2');
        $param["KETERANGAN_HADIAH"]     = $this->put('keterangan_hadiah');
        $param["TAMBAHAN_AHM"]          = $this->put('tambahan_ahm');
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_SALESPROGRAM_UPDATE",$param,'put',TRUE);
    }
    /**
     * [event_salesprogram_delete description]
     * @return [type] [description]
     */
    public function event_salesprogram_delete(){
        $param = array();
        $param["ID"]                    = $this->delete('id');
        $param["LASTMODIFIED_BY"]       = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_EVENT_SALESPROGRAM_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *TRANS_SALES_EVENT_VIEW
    */
    public function sales_event_view_get(){
        $param = array();$search='';
        if($this->get("kd_event")){
            $param["KD_EVENT"]     = $this->get("kd_event");
        }
        if($this->get("nama_event")){
            $param["NAMA_EVENT"]     = $this->get("nama_event");
        }
        if($this->get("nama_sales")){
            $param["NAMA_SALES"]     = $this->get("nama_sales");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_EVENT" => $this->get("keyword"),
                "NAMA_EVENT" => $this->get("keyword"),
                "NAMA_SALES" => $this->get("keyword"),
                "KD_DEALER" => $this->get("keyword")
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
        $this->resultdata("TRANS_SALES_EVENT_VIEW",$param);
    }
}