<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * @date_modify()[24-11-2017]
 */
class Sales extends REST_Controller {

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
     * [guestbook_get description]
     * @return [type] [description]
     */
    public function guestbook_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
        }
        if($this->get("guest_no")){
            $param["GUEST_NO"]     = $this->get("guest_no");
        }
        if($this->get("spk_no")){
            $param["SPK_NO"]     = $this->get("spk_no");
        }
        if ($this->get("category")) {
            $param["MP.CATEGORY_MOTOR"] = $this->get("category");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"     => $this->get("keyword"),
                "KD_CUSTOMER"   => $this->get("keyword"),
                "KD_SALES"      => $this->get("keyword"),
                "KD_TYPEMOTOR"  => $this->get("keyword"),
                "STATUS"   => $this->get("keyword"),
                "NAMA_CUSTOMER" => $this->get("keyword"),
                "NAMA_SALES"    => $this->get("keyword"),
                "KET_WARNA"     => $this->get("keyword"),
                "NAMA_TYPEMOTOR"=> $this->get("keyword"),
                "NAMA_PASAR"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_where_in($this->get('where_in'));
        $this->Main_model->set_whereinfield($this->get('where_in_field'));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_GUESTBOOK_VIEW",$param);
    }
    /**
     * [guestbook_post description]
     * @return [type] [description]
     */
    public function guestbook_post(){
        $param = array();
        $param["GUEST_NO"]      = $this->post("guest_no");
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $this->Main_model->data_sudahada($param,"TRANS_GUESTBOOK");
        $param["TANGGAL"]       = tglToSql($this->post('tgl_visit'));
        $param["KD_TYPEMOTOR"]  = $this->post('kd_typemotor');
        $param["KD_WARNA"]      = $this->post('kd_warna');
        $param["STATUS"]        = $this->post('status_deal');
        $param["KETERANGAN"]    = $this->post('alasan_nodeal');
        $param["TEST_DRIVE"]    = $this->post('test_drive');
        $param["TGL_TEST"]      = tglToSql($this->post('tgl_test'));
        $param["KET_TESTDRIVE"] = $this->post('kesan_test');
        $param["KD_SALES"]      = $this->post('kd_sales');
        $param["KD_TYPECUSTOMER"] = $this->post('kd_typecustomer');
        $param["CREATED_BY"]    = $this->post('created_by');
        $param["CARA_BAYAR"]    = $this->post('carabayar');
        $param["SPK_NO"]        = $this->post('spk_no');
        $param["RENCANA_FU"]    = tglToSql($this->post('rencana_fu'));
        $param["GB_SOURCE"]     = $this->post('gb_source');
        $param["CUST_STATUS"]   = $this->post('cust_status');
        $param["KD_EVENT"]      = $this->post('kd_event');
        $this->resultdata("SP_TRANS_GUESTBOOK_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [guestbook_put description]
     * @return [type] [description]
     */
    public function guestbook_put(){
        $param = array();
        $param["GUEST_NO"]      = $this->put("guest_no");
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["KD_CUSTOMER"]   = $this->put('kd_customer');
        $param["TANGGAL"]       = tglToSql($this->put('tgl_visit'));
        $param["KD_TYPEMOTOR"]  = $this->put('kd_typemotor');
        $param["KD_WARNA"]      = $this->put('kd_warna');
        $param["STATUS"]        = $this->put('status_deal');
        $param["KETERANGAN"]    = $this->put('alasan_nodeal');
        $param["TEST_DRIVE"]    = $this->put('test_drive');
        $param["TGL_TEST"]      = tglToSql($this->put('tgl_test'));
        $param["KET_TESTDRIVE"] = $this->put('kesan_test');
        $param["KD_SALES"]      = $this->put('kd_sales');
        $param["KD_TYPECUSTOMER"]= $this->put('kd_typecustomer');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $param["CARA_BAYAR"]    = $this->put('carabayar');
        $param["SPK_NO"]        = $this->put('spk_no');
        $param["RENCANA_FU"]    = tglToSql($this->put('rencana_fu'));
        $param["GB_SOURCE"]     = $this->put('gb_source');
        $param["CUST_STATUS"]   = $this->put('cust_status');
        $param["KD_EVENT"]      = $this->put('kd_event');
        $this->resultdata("SP_TRANS_GUESTBOOK_UPDATE",$param,'put',TRUE);
    }
    public function guestbooksts_put(){
        $param["GUEST_NO"]      = $this->put("guest_no");
        $param["SPK_NO"]        = $this->put("spk_no");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_GUESTBOOK_UPDATE_STATUS",$param,'put',TRUE);
    }
    public function guestbook_delete(){
        $param = array();
        $param["GUEST_NO"]      = $this->delete("guest_no");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_GUESTBOOK_DELETE",$param,'delete',TRUE);
    }
    public function guestbookhargamotor_get(){
       $param = array();$search='';
       if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("guestid")){
            $param["ID"]     = $this->get("guestid");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
        }
        if($this->get("guest_no")){
            $param["GUEST_NO"]     = $this->get("guest_no");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER"     => $this->get("keyword"),
                "KD_CUSTOMER"   => $this->get("keyword"),
                "KD_SALES"      => $this->get("keyword"),
                "KD_TYPEMOTOR"  => $this->get("keyword"),
                "STATUS_DEAL"   => $this->get("keyword"),
                "KET_WARNA"     => $this->get("keyword"),
                "NAMA_TYPEMOTOR"=> $this->get("keyword"),
                "NAMA_PASAR"    => $this->get("keyword")
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
        $this->resultdata("TRANS_GBHARGAMOTOR_VIEW",$param);
    }
    public function salesman_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_sales")){
            $param["NAMA_SALES"]     = $this->get("nama_sales");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("status_sales")){
            $param["STATUS_SALES"]     = $this->get("status_sales");
        }
        if($this->get("group_sales")){
            $param["GROUP_SALES"]     = $this->get("group_sales");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER" =>$this->get("keyword"),
                "NAMA_SALES" =>$this->get("keyword"),
                "KD_SALES" =>$this->get("keyword"),
                "KD_HSALES" =>$this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_SALES");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_SALESMAN",$param);
    }
     public function salesman_post(){
        $param = array();
        $param["KD_SALES"]      = $this->post("kd_sales");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"MASTER_SALESMAN");
        $param["STATUS_SALES"]  = $this->post("status_sales");      
        $param["KD_HSALES"]     = $this->post("kd_hsales");
        $param["NAMA_SALES"]    = ($this->post("nama_sales"));
        $param["GROUP_SALES"]   = $this->post("group_sales");
        $param["NIK"]           = $this->post("nik");
        $param["CREATED_BY"]    = $this->post("createdby");
        //additional june 2018
        $param["KD_JABATAN"]    = $this->post("kd_jabatan");
        $param["PERSONAL_JABATAN"] = $this->post("ps_jabatan");
        $this->resultdata("SP_MASTER_SALESMAN_INSERT",$param,'post',TRUE);
    }
    public function salesmannew_post(){
        $param=array();
        $this->Main_model->set_jsons($this->post('query'));
        $this->resultdata("",$param,'json',TRUE);
    }
    public function salesman_put(){
        $param = array();
        $param["KD_SALES"]      = $this->put('kd_sales');
        $param["NAMA_SALES"]    = ($this->put('nama_sales'));
        $param["KD_HSALES"]     = $this->put('kd_hsales');
        $param["GROUP_SALES"]   = $this->put('group_sales');
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["STATUS_SALES"]  = $this->put('status_sales');
        $param["NIK"]           = $this->put('nik');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        //additional june 2018
        $param["KD_JABATAN"]    = $this->put("kd_jabatan");
        $param["PERSONAL_JABATAN"] = $this->put("ps_jabatan");
        $this->resultdata("SP_MASTER_SALESMAN_UPDATE",$param,'put',TRUE);
    }

    public function salesman_delete(){
        $param = array();
        $param["KD_SALES"]     = $this->delete('kd_sales');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_SALESMAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [guestbook_detail_get description]
     * @return [type] [description]
     */
    public function guestbook_detail_get(){
       $param = array();$search='';
       
        if($this->get("guest_id")){
            $param["GUEST_ID"]     = $this->get("guest_id");
        }
        if($this->get("guest_source")){
            $param["GUEST_SOURCE"]     = $this->get("guest_source");
        }
        if($this->get("guest_no")){
            $param["GUEST_NO"]     = $this->get("guest_no");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "GUEST_NO"     => $this->get("keyword"),
                "GUEST_SOURCE"   => $this->get("keyword")
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
        $this->resultdata("TRANS_GUESTBOOK_DETAIL",$param);
    }
    /**
     * [guestbook_detail_post description]
     * @return [type] [description]
     */
    public function guestbook_detail_post(){
        $param = array();
        $param["GUEST_ID"]          = $this->post("guest_id");
        $param["GUEST_NO"]          = $this->post("guest_no");
        $param["GUEST_SOURCE"]      = $this->post('guest_source');
        $this->Main_model->data_sudahada($param,"TRANS_GUESTBOOK_DETAIL");
        $param["RENCANA_BAYAR"]     = $this->post('rencana_bayar');
        $param["FOLLOW_UP"]         = $this->post('follow_up');
        $param["RENCANA_FU"]        = tglToSql($this->post('rencana_fu'));
        $param["METODE_FU"]         = $this->post('metode_fu');
        $param["TGL_FU"]            = tglToSql($this->post('tgl_fu'));
        $param["STATUS_FU"]         = $this->post('status_fu');
        $param["HASIL_METODE"]      = $this->post('hasil_metode');
        $param["KET_STATUSFU"]      = $this->post('ket_statusfu');
        $param["KLAS_STATUSFU"]     = $this->post('klas_statusfu');
        $param["STATUS_CUSTOMER"]   = $this->post('status_customer');
        $param["TGL_NEXTFU"]        = tglToSql($this->post('tgl_nextfu'));
        $param["KET_NODEAL"]        = $this->post('ket_nodeal');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_GUESTBOOK_DETAIL_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [guestbook_detail_put description]
     * @return [type] [description]
     */
    public function guestbook_detail_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["GUEST_ID"]          = $this->put("guest_id");
        $param["GUEST_NO"]          = $this->put("guest_no");
        $param["GUEST_SOURCE"]      = $this->put('guest_source');
        $param["RENCANA_BAYAR"]     = $this->put('rencana_bayar');
        $param["FOLLOW_UP"]         = $this->put('follow_up');
        $param["RENCANA_FU"]        = tglToSql($this->put('rencana_fu'));
        $param["METODE_FU"]         = $this->put('metode_fu');
        $param["TGL_FU"]            = tglToSql($this->put('tgl_fu'));
        $param["STATUS_FU"]         = $this->put('status_fu');
        $param["HASIL_METODE"]      = $this->put('hasil_metode');
        $param["KET_STATUSFU"]      = $this->put('ket_statusfu');
        $param["KLAS_STATUSFU"]     = $this->put('klas_statusfu');
        $param["STATUS_CUSTOMER"]   = $this->put('status_customer');
        $param["TGL_NEXTFU"]        = tglToSql($this->put('tgl_nextfu'));
        $param["KET_NODEAL"]        = $this->put('ket_nodeal');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_GUESTBOOK_DETAIL_UPDATE",$param,'put',TRUE);
    }

    public function guestbook_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_GUESTBOOK_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [approval_ds_get description]
     * @return [type] [description]
     */
    public function approval_ds_get($detail=null){
       $param = array();$search='';
       
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("jenis_doc")){
            $param["JENIS_DOC"]     = $this->get("jenis_doc");
        }
        if($this->get("no_doc")){
            $param["NO_DOC"]     = $this->get("no_doc");
        }
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "JENIS_DOC"     => $this->get("keyword"),
                "NO_DOC"   => $this->get("keyword")
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
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($detail){
            $this->resultdata("TRANS_APPROVAL_DS",$param);
        }else{
            $this->resultdata("TRANS_APPROVAL_DSV",$param);
        }
    }
    /**
     * [approval_ds_post description]
     * @return [type] [description]
     */
    public function approval_ds_post(){
        $param = array();
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["JENIS_DOC"]         = $this->post('jenis_doc');
        $param["NO_DOC"]            = $this->post('no_doc');
        $this->Main_model->data_sudahada($param,"TRANS_APPROVAL_DS",TRUE);
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["TGL_DOC"]           = tglToSql($this->post('tgl_doc'));
        $param["KETERANGAN"]        = $this->post('keterangan');
        $param["RELATED_DOC"]       = $this->post('related_doc');
        $param["APV_STATUS"]        = $this->post('apv_status');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_TRANS_APPROVAL_DS_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [approval_ds_put description]
     * @return [type] [description]
     */
    public function approval_ds_put(){
        $param = array();
        //$param["ID"]                = $this->put("id");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["JENIS_DOC"]         = $this->put('jenis_doc');
        $param["NO_DOC"]            = $this->put('no_doc');
        $param["TGL_DOC"]           = tglToSql($this->put('tgl_doc'));
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["RELATED_DOC"]       = $this->put('related_doc');
        $param["APV_STATUS"]        = $this->put('apv_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_APPROVAL_DS_UPDATE",$param,'put',TRUE);
    }

    public function approval_ds_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_APPROVAL_DS_DELETE",$param,'delete',TRUE);
    }

    /**
     * [so_programhadiah_get description]
     * @return [type] [description]
     */
    public function so_programhadiah_get($detail=null){
       $param = array();$search='';
       
        if($this->get("kd_item")){
            $param["KD_ITEM"]     = $this->get("kd_item");
        }
        if($this->get("kd_program")){
            $param["KD_PROGRAM"]     = $this->get("kd_program");
        }
        if($this->get("nama_program")){
            $param["NAMA_PROGRAM"]     = $this->get("nama_program");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_PROGRAM"     => $this->get("keyword")
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

        if($detail){
            $this->resultdata("SETUP_SO_PROGRAMHADIAH_VIEW",$param);
        }
        else{
            $this->resultdata("SETUP_SO_PROGRAMHADIAH",$param);
        }
    }
    /**
     * [so_programhadiah_post description]
     * @return [type] [description]
     */
    public function so_programhadiah_post(){
        $param = array();
        $param["KD_PROGRAM"]        = $this->post('kd_program');
        $param["NAMA_PROGRAM"]      = $this->post('nama_program');
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"SETUP_SO_PROGRAMHADIAH",TRUE);
        $param["START_DATE"]        = tglToSql($this->post("start_date"));
        $param["END_DATE"]          = tglToSql($this->post('end_date'));
        $param["KD_TYPEMOTOR"]      = $this->post('kd_typemotor');
        $param["NAMA_HADIAH"]       = $this->post("nama_hadiah");
        $param["JUMLAH_HADIAH"]     = $this->post("jumlah_hadiah");
        $param["END_PRINT"]         = $this->post("end_print");
        $param["SHARE_D"]           = $this->post("share_d");
        $param["SHARE_MD"]          = $this->post("share_md");
        $param["SHARE_AHM"]         = $this->post("share_ahm");
        $param["HARI"]              = $this->post("hari");
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_SETUP_SO_PROGRAMHADIAH_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [so_programhadiah_put description]
     * @return [type] [description]
     */
    public function so_programhadiah_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_PROGRAM"]        = $this->put('kd_program');
        $param["NAMA_PROGRAM"]      = ($this->put('nama_program'));
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["START_DATE"]        = tglToSql($this->put("start_date"));
        $param["END_DATE"]          = tglToSql($this->put('end_date'));
        $param["KD_TYPEMOTOR"]      = $this->put('kd_typemotor');
        $param["NAMA_HADIAH"]       = $this->put("nama_hadiah");
        $param["JUMLAH_HADIAH"]     = $this->put("jumlah_hadiah");
        $param["END_PRINT"]         = $this->put("end_print");
        $param["SHARE_D"]           = $this->put("share_d");
        $param["SHARE_MD"]          = $this->put("share_md");
        $param["SHARE_AHM"]         = $this->put("share_ahm");
        $param["HARI"]              = $this->put("hari");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_SO_PROGRAMHADIAH_UPDATE",$param,'put',TRUE);
    }

    public function so_programhadiah_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_SETUP_SO_PROGRAMHADIAH_DELETE",$param,'delete',TRUE);
    }

    /**
     * [trans_sales_event_get description]
     * @return [type] [description]
     */
    public function trans_sales_event_get(){
       $param = array();$search='';
        if($this->get("nama_event")){
            $param["NAMA_EVENT"]     = $this->get("nama_event");
        }
        if($this->get("desc_event")){
            $param["DESC_EVENT"]     = $this->get("desc_event");
        }
        if($this->get("jenis_event")){
            $param["JENIS_EVENT"]     = $this->get("jenis_event");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_EVENT"     => $this->get("keyword"),
                "DESC_EVENT"     => $this->get("keyword"),
                "JENIS_EVENT"    => $this->get("keyword")
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
        $this->resultdata("TRANS_SALES_EVENT",$param);
    }
    /**
     * [trans_sales_event_post description]
     * @return [type] [description]
     */
    public function trans_sales_event_post(){
        $param = array();
        $param["ID_EVENT"]          = $this->post("id_event");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"TRANS_SALES_EVENT",TRUE);
        $param["NAMA_EVENT"]        = $this->post("nama_event");
        $param["JENIS_EVENT"]       = $this->post("jenis_event");
        $param["START_DATE"]        = tglToSql($this->post("start_date"));
        $param["END_DATE"]          = tglToSql($this->post("end_date"));
        $param["DESC_EVENT"]        = $this->post("desc_event");
        $param["BUDGET_EVENT"]      = $this->post("budget_event");
        $param["LOC_EVENT"]         = $this->post("loc_event");
        $param["UNIT_TARGET"]       = $this->post("unit_target");
        $param["REVENUE_TARGET"]    = $this->post("revenue_target");
        //$param["UNIT_TO_DISPLAY"]   = $this->post("unit_to_display");
        $param["TANGGAL"]           = tglToSql($this->post("tanggal"));
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_SALES_EVENT_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [trans_sales_event_put description]
     * @return [type] [description]
     */
    public function trans_sales_event_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["ID_EVENT"]          = $this->put('id_event');
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["NAMA_EVENT"]        = $this->put("nama_event");
        $param["DESC_EVENT"]        = $this->put('desc_event');
        $param["JENIS_EVENT"]       = $this->put('jenis_event');
        $param["START_DATE"]        = tglToSql($this->put("start_date"));
        $param["END_DATE"]          = tglToSql($this->put('end_date'));
        $param["BUDGET_EVENT"]      = $this->put("budget_event");
        $param["LOC_EVENT"]         = $this->put('loc_event');
        //$param["UNIT_TO_DISPLAY"]   = $this->put('unit_to_display');
        $param["UNIT_TARGET"]       = $this->put("unit_target");
        $param["REVENUE_TARGET"]    = $this->put("revenue_target");
        $param["TANGGAL"]           = tglToSql($this->put('tanggal'));
        //$param["HONDAID_SP"]        = $this->put('hondaid_sp');
        //$param["AVAILABLE_PROMOTION"]= $this->put('available_promotion');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SALES_EVENT_UPDATE",$param,'put',TRUE);
    }

    public function trans_sales_event_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_SALES_EVENT_DELETE",$param,'delete',TRUE);
    }

    /**
     * [salesevent_prepare_put description]
     * @return [type] [description]
     */
    public function salesevent_prepare_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["PIC_SALESEVENT"]        = $this->put('pic_salesevent');
        $param["SP_SALESEVENT"]        = $this->put('sp_salesevent');
        $param["AVAILABLE_PROMOTION"]= $this->put('available_promotion');
        $param["UNIT_TO_DISPLAY"]   = $this->put('unit_to_display');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SALES_EVENT_UPDATEPREPARE",$param,'put',TRUE);
    }


    /**
     * [after_dealing_get description]
     * @return [type] [description]
     */
    public function after_dealing_get(){
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
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"      => $this->get("keyword"),
                "NAMA_CUSTOMER" => $this->get("keyword"),
                "NO_SPK"        => $this->get("keyword")
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
        $this->resultdata("TRANS_AFTER_DEALING",$param);
    }
    /**
     * [after_dealing_post description]
     * @return [type] [description]
     */
    public function after_dealing_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["KD_CUSTOMER"]       = $this->post("kd_customer");
        $param["NO_SPK"]            = $this->post("no_spk");
        $this->Main_model->data_sudahada($param,"TRANS_AFTER_DEALING");
        $param["NO_RANGKA"]         = $this->post("no_rangka");
        $param["NO_MESIN"]          = $this->post("no_mesin");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NAMA_CUSTOMER"]     = $this->post("nama_customer");
        $param["NO_HP"]             = $this->post("no_hp");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [after_dealing_put description]
     * @return [type] [description]
     */
    public function after_dealing_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["TGL_TRANS"]         = tglToSql($this->put("tgl_trans"));
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["KD_CUSTOMER"]       = $this->put("kd_customer");
        $param["NAMA_CUSTOMER"]     = $this->put("nama_customer");
        $param["NO_SPK"]            = $this->put("no_spk");
        $param["NO_RANGKA"]         = $this->put("no_rangka");
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["NO_HP"]             = $this->put("no_hp");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_UPDATE",$param,'put',TRUE);
    }

    public function after_dealing_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_AFTER_DEALING_DELETE",$param,'delete',TRUE);
    }

    /**
     * [after_dealing_detail_get description]
     * @return [type] [description]
     */
    public function after_dealing_detail_get(){
       $param = array();$search='';
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("nama_aktivitas")){
            $param["NAMA_AKTIVITAS"]     = $this->get("nama_aktivitas");
        }
        if($this->get("tipe_aktivitas")){
            $param["TIPE_AKTIVITAS"]     = $this->get("tipe_aktivitas");
        }
        if($this->get("status_aktivitas")){
            $param["STATUS_AKTIVITAS"]     = $this->get("status_aktivitas");
        }
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS"      => $this->get("keyword"),
                "NAMA_AKTIVITAS" => $this->get("keyword"),
                "TIPE_AKTIVITAS"        => $this->get("keyword")
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
        $this->resultdata("TRANS_AFTER_DEALING_DETAIL",$param);
    }
    /**
     * [after_dealing_detail_post description]
     * @return [type] [description]
     */
    public function after_dealing_detail_post(){
        $param = array();
        $param["NO_TRANS"]          = $this->post("no_trans");
        $param["KD_SALES"]          = $this->post("kd_sales");
        $param["TIPE_AKTIVITAS"]    = $this->post("tipe_aktivitas");
        $param["NAMA_AKTIVITAS"]    = $this->post("nama_aktivitas");
        $param["STATUS_AKTIVITAS"]  = $this->post("status_aktivitas");
        $this->Main_model->data_sudahada($param,"TRANS_AFTER_DEALING_DETAIL", TRUE);
        $param["WAKTU_MULAI"]       = tglToSql($this->post("waktu_mulai"));
        $param["WAKTU_SELESAI"]     = tglToSql($this->post("waktu_selesai"));
        $param["DESKRIPSI"]         = $this->post("deskripsi");
        $param["KETERANGAN"]        = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_DETAIL_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [after_dealing_detail_put description]
     * @return [type] [description]
     */
    public function after_dealing_detail_put(){
        $param = array();
        // $param["ID"]                = $this->put("id");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["KD_SALES"]          = $this->put("kd_sales");
        $param["NAMA_AKTIVITAS"]    = $this->put("nama_aktivitas");
        $param["TIPE_AKTIVITAS"]    = $this->put("tipe_aktivitas");
        $param["STATUS_AKTIVITAS"]  = $this->put("status_aktivitas");
        $param["WAKTU_MULAI"]       = tglToSql($this->put("waktu_mulai"));
        $param["WAKTU_SELESAI"]     = tglToSql($this->put("waktu_selesai"));
        $param["DESKRIPSI"]         = $this->put("deskripsi");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_DETAIL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [after_dealing_detail_put description]
     * @return [type] [description]
     */
    public function after_dealing_detailid_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["NO_TRANS"]          = $this->put("no_trans");
        $param["KD_SALES"]          = $this->put("kd_sales");
        $param["NAMA_AKTIVITAS"]    = $this->put("nama_aktivitas");
        $param["TIPE_AKTIVITAS"]    = $this->put("tipe_aktivitas");
        $param["STATUS_AKTIVITAS"]  = $this->put("status_aktivitas");
        $param["WAKTU_MULAI"]       = tglToSql($this->put("waktu_mulai"));
        $param["WAKTU_SELESAI"]     = tglToSql($this->put("waktu_selesai"));
        $param["DESKRIPSI"]         = $this->put("deskripsi");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_DETAIL_UPDATE_ID",$param,'put',TRUE);
    }

    /**
     * [after_dealing_detail_status_aktivitas_put description]
     * @return [type] [description]
     */
    public function after_dealing_detail_status_aktivitas_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["STATUS_AKTIVITAS"]  = $this->put("status_aktivitas");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_DETAIL_UPDATE_STATUS_AKTIVITAS",$param,'put',TRUE);
    }

    /**
     * [after_dealing_detail_status_aktivitas_put description]
     * @return [type] [description]
     */
    public function after_dealing_detail_keterangan_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KETERANGAN"]        = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_AFTER_DEALING_DETAIL_UPDATE_KETERANGAN",$param,'put',TRUE);
    }

    public function after_dealing_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_TRANS_AFTER_DEALING_DETAIL_DELETE",$param,'delete',TRUE);
    }


    public function list_after_dealing_get(){
       $param = array();$search='';
        if($this->get("no_spk")){
            $param["NO_SPK"]     = $this->get("no_spk");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("tipe")){
            $param["TIPE"]     = $this->get("tipe");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_SPK"      => $this->get("keyword"),
                "NAMA_CUSTOMER" => $this->get("keyword"),
                "KD_CUSTOMER"        => $this->get("keyword")
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
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_LIST_AFTER_DEALING_VIEW",$param);
    }

    /**
     * [gb_status_put description]
     * @return [type] [description]
     */
    public function gb_status_put(){
        $param = array();
        $param["SPK_NO"]            = $this->put("spk_no");
        $param["STATUS"]            = $this->put("status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_GUESTBOOK_UPDATESTATUS",$param,'put',TRUE);
    }

}