<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Master extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
        $this->load->model('Custom_model');
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
    * Get data Master motor
    * @access public
    */
    public function motor_get(){
        $param=array(); $result=array();
        if($this->get('kd_type')){
            $param["KD_TYPEMOTOR"]  = $this->get('kd_type');
        }
        if($this->get('kd_item')){
            $param["KD_ITEM"]  = $this->get('kd_item');
        }
        if($this->get('kd_warna')){
            $param["KD_WARNA"] = $this->get('kd_warna');
        }
        if($this->get("nama_pasar")){
            $param["NAMA_PASAR"] = $this->get("nama_pasar");
        }
        if($this->get("cc_motor")){
            $param["CC_MOTOR"] = $this->get("cc_motor");
        }
        
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_TYPEMOTOR" =>$this->get("keyword"),
                "KD_WARNA"     =>$this->get("keyword"),
                "KET_WARNA"    =>$this->get("keyword"),
                "CC_MOTOR"     =>$this->get("keyword"),
                "NAMA_TYPEMOTOR" =>$this->get("keyword"),
                "NAMA_PASAR"   =>$this->get("keyword"),
                "NAMA_ITEM"    =>$this->get("keyword"),
                "KD_ITEM"      =>$this->get("keyword")
            );
        }
        if($this->get("tgl_akhireff")){
            $param["TGL_AKHIREFF"] = $this->get("tgl_akhireff");
        }
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if($this->get('having')){
            $this->Main_model->set_havings($this->get('having'));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get('groupby'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->resultdata("MASTER_P_TYPEMOTOR",$param);
        
    }
    /**
     * [motor_post description]
     * @return [type] [description]
     */
    public function motor_post(){
        /* 
        * Penambahan data type motor dilakukan dari data webservice list5
        */
        $param=array();
        $param["KD_ITEM"]       = $this->post("kd_item");
        $this->Main_model->data_sudahada($param,"MASTER_P_TYPEMOTOR");
        $param["KD_TYPEMOTOR"]  = $this->post('kd_type');
        $param["NAMA_TYPEMOTOR"]= $this->post("nama_typemotor");
        $param["KD_WARNA"]      = $this->post('kd_warna');
        $param["KET_WARNA"]     = $this->post('ket_warna');
        $param["NAMA_PASAR"]    = $this->post("nama_pasar");
        $param["CC_MOTOR"]      = (double)($this->post("cc_motor"));
        $param["NAMA_ITEM"]     = $this->post("nama_item");
        $param["JENIS_MOTOR"]   = $this->post("jenis_motor");
        $param["TGL_AWALEFF"]   = tglToSql($this->post("tgl_awaleff"));
        $param["TGL_AKHIREFF"]  = tglToSql($this->post("tgl_akhireff"));
        $param["CBU"]           = $this->post("cbu");
        $param["SEGMENT"]       = $this->post("segment");
        $param["TRIO_STATUS"]   = $this->post("trio_status");
        $param["SUB_KATEGORI"]  = $this->post("sub_kategori");
        $param["MODIF_DATE"]    = tglToSql($this->post("modif_date"));
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_MASTER_P_TYPEMOTOR_INSERT",$param,'post',TRUE);

    }
    /**
     * Update type motor tidak dilakukan di applikasi ini
     * @return [type] [description]
     */
    public function motor_put(){
        /*
        * Applikasi ini tidak di ijinkan edit data motor
        */
        $param=array();
        $param["KD_ITEM"]           = $this->put("kd_item");
        $param["KD_TYPEMOTOR"]      = $this->put('kd_type');
        $param["NAMA_TYPEMOTOR"]    = $this->put("nama_typemotor");
        $param["KD_WARNA"]          = $this->put('kd_warna');
        $param["KET_WARNA"]         = $this->put('ket_warna');
        $param["NAMA_PASAR"]        = $this->put("nama_pasar");
        $param["CC_MOTOR"]          = (double)($this->put("cc_motor"));
        $param["NAMA_ITEM"]         = $this->put("nama_item");
        $param["JENIS_MOTOR"]       = $this->put("jenis_motor");
        $param["TGL_AWALEFF"]       = tglToSql($this->put("tgl_awaleff"));
        $param["TGL_AKHIREFF"]      = tglToSql($this->put("tgl_akhireff"));
        $param["CBU"]               = $this->put("cbu");
        $param["SEGMENT"]           = $this->put("segment");
        $param["TRIO_STATUS"]       = $this->put("trio_status");
        $param["SUB_KATEGORI"]      = $this->put("sub_kategori");
        $param["MODIF_DATE"]        = tglToSql($this->put("modif_date"));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_P_TYPEMOTOR_UPDATE",$param,'put',TRUE);
        
    }
    /**
     * Delete type motor tidak dilakukan di applikasi ini
     * @return [type] [description]
     */
    public function motor_delete(){
       
        $param=array();
        $param["KD_ITEM"]       = $this->delete("kd_item");
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_TYPEMOTOR_DELETE",$param,'delete',TRUE);
    }
    /**
     * [groupmotor_get description]
     * @return [type] [description]
     */
    public function groupmotor_get(){
        $param = array();$search='';
        if($this->get("kd_groupmotor")){
            $param["KD_GROUPMOTOR"]     = $this->get("kd_groupmotor");
        }
        if($this->get("nama_groupmotor")){
            $param["NAMA_GROUPMOTOR"]     = $this->get("nama_groupmotor");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
        }
      
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_GROUPMOTOR" =>$this->get("keyword"),
                "NAMA_GROUPMOTOR" =>$this->get("keyword"),
                "KD_TYPEMOTOR" =>$this->get("keyword"),
                "CATEGORY_MOTOR" =>$this->get("keyword"),
                "SEMBILAN_SEGMEN" =>$this->get("keyword"),
                "SERIES" =>$this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_GROUPMOTOR");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->resultdata("MASTER_P_GROUPMOTOR",$param);
    }
    /**
     * [groupmotor_post description]
     * @return [type] [description]
     */
    public function groupmotor_post(){
        $param=array();
        $param["KD_GROUPMOTOR"] = $this->post("kd_groupmotor");
        //$this->Main_model->data_sudahada($param,"MASTER_P_GROUPMOTOR");
        $param["KD_TYPEMOTOR"]  = $this->post("kd_typemotor");
        $param["NAMA_GROUPMOTOR"]  = $this->post("nama_groupmotor");
        $param["CATEGORY_MOTOR"]   = $this->post("category_motor");
        $param["SEMBILAN_SEGMEN"]  = $this->post("sembilan_segmen");
        $param["SERIES"]           = $this->post("series");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_MASTER_P_GROUPMOTOR_INSERT",$param,'post',TRUE);       
    }

    public function ugmbatch_post(){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
         // $folderJson = "\\\\".getConfig("UPJSON")."\\tmp\\desa.json";
        $folderJson = getConfig("UPJSON")."\ugm.json";
        //$folderJson = "c:\\tmp\pvtm.json";
        $handle=fopen($folderJson,'wb');
        fwrite($handle,$param);
        fclose($handle);
        $datax=$this->Custom_model->queryugm();
        //var_dump($datax);exit;
        unlink($folderJson);
        if($datax){
            $result["status"]   = FALSE;
            $result["message"]  = "Data gagal di simpan";
            $result["debug"]    = "";
            $result["param"]    = $this->db->last_query();
            $result["recordexists"]=FALSE;

        }else{
            $result["status"]   = "Success";
            $result["message"]  = "Data berhasil di upload";
            $result["location"] =(isset($this->location))?$this->get_location():"";
            $result["param"]    = "";//$this->db->last_query();
            $result["recordexists"]=FALSE;
        }
        $this->response($result);
    }
    
    /**
     * [groupmotor_put description]
     * @return [type] [description]
     */
    public function groupmotor_put(){
        $param=array();
        $param["KD_GROUPMOTOR"] = $this->put("kd_groupmotor");
        $param["KD_TYPEMOTOR"]  = $this->put("kd_typemotor");
        $param["NAMA_GROUPMOTOR"]  = $this->put("nama_groupmotor");
        $param["CATEGORY_MOTOR"]   = $this->put("category_motor");
        $param["SEMBILAN_SEGMEN"]  = $this->put("sembilan_segmen");
        $param["SERIES"]           = $this->put("series");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_P_GROUPMOTOR_UPDATE",$param,'put',TRUE);       
    }
    
    /**
     * [groupmotor_delete description]
     * @return [type] [description]
     */
    public function groupmotor_delete(){
        
        $param=array();
        $param["KD_GROUPMOTOR"] = $this->delete("kd_groupmotor");
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_GROUPMOTOR_DELETE",$param,'delete',TRUE);       
    }
    /**
    * [categorymotor_get description]
    * @return [type] [description]
    */
    public function categorymotor_get(){
        $param  = array(); $search="";
        if($this->get('kd_category')){
            $param["KD_CATEGORY"]   = $this->get("kd_category");
        }
        if($this->get("nama_category")){
            $param["NAMA_CATEGORY"] = $this->get("nama_category");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_CATEGORY" =>$this->get("keyword"),
                "NAMA_CATEGORY" =>$this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_CATEGORY");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_P_CATEGORY_V",$param);
    }
    /**
     * [categorymotor_post description]
     * @return [type] [description]
     */
    public function categorymotor_post(){       
        $param=array();
        $param["KD_CATEGORY"] = $this->post("kd_category");
        $this->Main_model->data_sudahada($param,"MASTER_P_CATEGORY");
        $param["NAMA_CATEGORY"] = $this->post("nama_category");
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_MASTER_P_CATEGORY_INSERT",$param,'post');       
    }
    /**
     * [categorymotor_put description]
     * @return [type] [description]
     */
    public function categorymotor_put(){      
        $param=array();
        $param["KD_CATEGORY"]   = $this->put("kd_category");
        $param["NAMA_CATEGORY"] = $this->put("nama_category");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        
        $this->resultdata("SP_MASTER_P_CATEGORY_UPDATE",$param,'put');       
    }
    /**
     * [categorymotor_delete description]
     * @return [type] [description]
     */
    public function categorymotor_delete(){       
        $param=array();
        $param["KD_CATEGORY"] = $this->delete("kd_category");
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_CATEGORY_DELETE",$param,'delete');       
    }

    /**
     * [seriesmotor_get description]
     * @return [type] [description]
     */
    public function seriesmotor_get(){
        $param  = array(); $search='';
        if($this->get('kd_series')){
            $param["KD_SERIES"] = $this->get("kd_series");
        }
        if($this->get("nama_series")){
            $param["NAMA_SERIES"] = $this->get("nama_series");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_SERIES"   =>$this->get("keyword"),
                "NAMA_SERIES" =>$this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_SERIES");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_P_SERIES_V",$param);

    }

    /**
     * [seriesmotor_post description]
     * @return [type] [description]
     */
    public function seriesmotor_post(){       
        $param=array();
        $param["KD_SERIES"] = $this->post("kd_series");
        $this->Main_model->data_sudahada($param,"MASTER_P_SERIES");
        $param["NAMA_SERIES"] = $this->post("nama_series");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_MASTER_P_SERIES_INSERT",$param,'post');       
    }
    /**
     * [seriesmotor_put description]
     * @return [type] [description]
     */
    public function seriesmotor_put() {      
        $param=array();
        $param["KD_SERIES"] = $this->put("kd_series");
        $param["NAMA_SERIES"] = $this->put("nama_series");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_P_SERIES_UPDATE",$param,'put');       
    }
    /**
     * [seriesmotor_del description]
     * @return [type] [description]
     */
    public function seriesmotor_delete(){       
        $param=array();
        $param["KD_SERIES"] = $this->delete("kd_series");
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_SERIES_DELETE",$param,'delete');       
    }
    
    /**
     * [segmen_get description]
     * @return [type] [description]
     */
    public function segmen_get(){
        $param  = array(); $search='';
        if($this->get('kd_segmen')){
            $param["KD_SEGMEN"]   = $this->get("kd_segmen");
        }
        if($this->get("nama_segmen")){
            $param["NAMA_SEGMEN"] = $this->get("nama_segmen");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_SEGMEN" =>$this->get("keyword"),
                "NAMA_SEGMEN" =>$this->get("keyword")
            );
        }

       
        $this->Main_model->set_statusdata($this->get('row_status'));
        
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if($this->get("orderby")){
            $this->Main_model->set_orderby($this->get("orderby"));
        }else{
            $this->Main_model->set_orderby("NAMA_SEGMEN");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_P_9SEGMEN_V",$param);
    }
    /**
     * [segmen_post description]
     * @return [type] [description]
     */
    public function segmen_post(){       
        $param=array();
        $param["KD_SEGMEN"]   = $this->post("kd_segmen");
        $this->Main_model->data_sudahada($param,"MASTER_P_9SEGMEN");
        $param["NAMA_SEGMEN"] = $this->post("nama_segmen");
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_MASTER_P_9SEGMEN_INSERT",$param,'post');       
    }
    /**
     * [segmen_put description]
     * @return [type] [description]
     */
    public function segmen_put(){      
        $param=array();
        $param["KD_SEGMEN"]   = $this->put("kd_segmen");
        $param["NAMA_SEGMEN"] = $this->put("nama_segmen");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
        
        $this->resultdata("SP_MASTER_P_9SEGMEN_UPDATE",$param,'put');       
    }
    /**
     * [segmen_delete description]
     * @return [type] [description]
     */
    public function segmen_delete(){       
        $param=array();
        $param["KD_SEGMEN"]   = $this->delete("kd_segmen");
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_9SEGMEN_DELETE",$param,'delete');       
    }

    /**
     * [bundling_get description]
     * @return [type] [description]
     */
    public function bundling_get(){
        $param = array();$search='';
        if($this->get("kd_bundling")){
            $param["KD_BUNDLING"]   = $this->get("kd_bundling");
        }
        if($this->get("nama_bundling")){
            $param["NAMA_BUNDLING"] = $this->get("nama_bundling");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]   = $this->get("kd_typemotor");
        }
        if($this->get("kd_warna")){
            $param["KD_WARNA"]   = $this->get("kd_warna");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_BUNDLING"       => $this->get("keyword"),
                "NAMA_BUNDLING"    => $this->get("keyword"),
                "KD_DEALER"        => $this->get("keyword")
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
        $this->resultdata("MASTER_P_BUNDLING",$param);
    }
    
    /**
     * [bundling_post description]
     * @return [type] [description]
     */
    public function bundling_post(){
        $param = array();
        $param["KD_BUNDLING"]   = $this->post("kd_bundling");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $this->Main_model->data_sudahada($param,"MASTER_P_BUNDLING");
        $param["NAMA_BUNDLING"] = $this->post("nama_bundling");
        $param["KD_TYPEMOTOR"]  = $this->post("kd_typemotor");
        $param["KD_WARNA"]      = $this->post("kd_warna");
        $param["NO_URUT"]       = $this->post("no_urut");
        $param["START_DATE"]    = tglToSql($this->post('start_date'));
        $param["END_DATE"]      = tglToSql($this->post('end_date'));
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_MASTER_P_BUNDLING_INSERT",$param,'post',TRUE);
    }

    /**
     * [bundling_put description]
     * @return [type] [description]
     */
    public function bundling_put(){
        $param = array();
        $param["KD_BUNDLING"]   = $this->put("kd_bundling");
        $param["NAMA_BUNDLING"] = $this->put("nama_bundling");
        $param["KD_DEALER"]     = $this->put("kd_dealer");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_TYPEMOTOR"]  = $this->put("kd_typemotor");
        $param["KD_WARNA"]      = $this->put("kd_warna");
        $param["NO_URUT"]       = $this->put("no_urut");
        $param["START_DATE"]    = tglToSql($this->put('start_date'));
        $param["END_DATE"]      = tglToSql($this->put('end_date'));
        $param["ROW_STATUS"]    = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_P_BUNDLING_UPDATE",$param,'put',TRUE);
    }

    /**
     * [bundling_delete description]
     * @return [type] [description]
     */
    public function bundling_delete(){
        $param = array();
        $param["KD_BUNDLING"]     = $this->delete('kd_bundling');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_BUNDLING_DELETE",$param,'delete',TRUE);
    }

    /**
     * [bundling_detail_get description]
     * @return [type] [description]
     */
    public function bundling_detail_get(){
        $param = array();$search='';
        if($this->get("kd_bundling")){
            $param["KD_BUNDLING"]   = $this->get("kd_bundling");
        }
        if($this->get("type_bundling")){
            $param["TYPE_BUNDLING"] = $this->get("type_bundling");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_BUNDLING"       => $this->get("keyword"),
                "TYPE_BUNDLING"    => $this->get("keyword")
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
        $this->resultdata("MASTER_P_BUNDLING_DETAIL_V",$param);
    }
    
    /**
     * [bundling_detail_post description]
     * @return [type] [description]
     */
    public function bundling_detail_post(){
        $param = array();
        $param["KD_BUNDLING"]   = $this->post("kd_bundling");
        $param["KD_ITEM"] = $this->post("kd_item");
        $param["GROUP_BUNDLING"]= $this->post("group_bundling");
        $this->Main_model->data_sudahada($param,"MASTER_P_BUNDLING_DETAIL");
        $param["NAMA_ITEM"]     = $this->post("nama_item");
        $param["JUMLAH"]     = $this->post('jumlah');
        $param["KETERANGAN"]     = $this->post('keterangan');
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_MASTER_P_BUNDLING_DETAIL_INSERT",$param,'post',TRUE);
    }

    /**
     * [bundling_detail_put description]
     * @return [type] [description]
     */
    public function bundling_detail_put(){
        $param = array();
        $param["KD_BUNDLING"]   = $this->put("kd_bundling");
        $param["KD_ITEM"] = $this->put("kd_item");
        $param["GROUP_BUNDLING"]= $this->put("group_bundling");
        $param["NAMA_ITEM"]     = $this->put("nama_item");
        $param["JUMLAH"]     = $this->put('jumlah');
        $param["KETERANGAN"]     = $this->put('keterangan');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_P_BUNDLING_DETAIL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [bundling_detail_delete description]
     * @return [type] [description]
     */
    public function bundling_detail_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_P_BUNDLING_DETAIL_DELETE",$param,'delete',TRUE);
    }


    /**
     * [dealer_get description]
     * @return [type] [description]
     */
    public function dealer_get($complete=null, $asli=null){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if($this->get("kd_dealerahm")){
            $param["KD_DEALERAHM"] = $this->get("kd_dealerahm");
        }
        if($this->get("nama_dealer")){
            $param["NAMA_DEALER"] = $this->get("nama_dealer");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_DEALER"      => $this->get("keyword"),
                "KD_DEALERAHM"  => $this->get("keyword"),
                "NAMA_DEALER"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if(!$this->get("orderby")){
            $this->Main_model->set_orderby("KD_JENISDEALER DESC,NAMA_DEALER");
        }else{
            $this->Main_model->set_orderby($this->get("orderby"));
        }
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($complete==true){
            if($asli==true){
                $this->resultdata("MASTER_DEALER",$param);
            }else{
                $this->resultdata("MASTER_DEALER_V",$param);
            }
            
        }else{
            $this->resultdata("MASTER_DEALER_VIEW",$param);
        }
        
    }
    /**
     * [dealer_post description]
     * @return [type] [description]
     */
    public function dealer_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["KD_DEALERAHM"]  = $this->post('kd_dealerahm');
        $this->Main_model->data_sudahada($param,"MASTER_DEALER");
        $param["NAMA_DEALER"]   = $this->post('nama_dealer');
        $param["TLP"]   = $this->post('tlp');
        $param["TLP2"]  = $this->post('tlp2');
        $param["TLP3"]  = $this->post('tlp3');
        $param["ALAMAT"]    = $this->post('alamat');
        $param["KD_JENISDEALER"]= $this->post('kd_jenisdealer');
        $param["KD_STATUSDEALER"]= $this->post('kd_statusdealer');
        $param["KD_KABUPATEN"]    = $this->post('kd_kabupaten');
        $param["KD_PROPINSI"]   = $this->post('kd_propinsi');
        $param["RULE_DEALER"]   = $this->post('rule_dealer');
        $param["KD_MAINDEALER"] = 'T10';// $this->post('kd_maindealer');
        $param["JUMLAH_PIT"]    = $this->post("jumlah_pit");
        $param["KATEGORI_DEALER"]    = $this->post("kategori_dealer");
        $param["NO_NPWP"]    = $this->post("no_npwp");
        $param["PKP"]    = $this->post("pkp");
        $param["GROUP_DEALER"]    = $this->post("group_dealer");
        $param["LAT"]    = $this->post("lat");
        $param["LNG"]    = $this->post("lng");
        $param["CREATED_BY"]    = $this->post("created_by");
       // print_r($param);
        $this->resultdata("SP_MASTER_DEALER_INSERT",$param,'post',TRUE);
    }
    /**
     * [dealer_put description]
     * @return [type] [description]
     */
    public function dealer_put(){
        $param = array();
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["KD_DEALERAHM"]  = $this->put('kd_dealerahm');
        $param["NAMA_DEALER"]   = $this->put('nama_dealer');
        $param["TLP"]   = $this->put('tlp');
        $param["TLP2"]  = $this->put('tlp2');
        $param["TLP3"]  = $this->put('tlp3');
        $param["ALAMAT"]    = $this->put('alamat');
        $param["KD_JENISDEALER"]= $this->put('kd_jenisdealer');
        $param["KD_STATUSDEALER"]= $this->put('kd_statusdealer');
        $param["KD_KABUPATEN"]    = $this->put('kd_kabupaten');
        $param["KD_PROPINSI"]   = $this->put('kd_propinsi');
        $param["RULE_DEALER"]   = $this->put('rule_dealer');
        $param["KD_MAINDEALER"] = 'T10';// $this->put('kd_maindealer');
        $param["JUMLAH_PIT"]    = $this->put("jumlah_pit");
        $param["KATEGORI_DEALER"]    = $this->put("kategori_dealer");
        $param["NO_NPWP"]    = $this->put("no_npwp");
        $param["PKP"]    = $this->put("pkp");
        $param["GROUP_DEALER"]    = $this->put("group_dealer");
        $param["LAT"]    = $this->put("lat");
        $param["LNG"]    = $this->put("lng");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_DEALER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [dealer_delete description]
     * @return [type] [description]
     */
    public function dealer_delete(){
        $param = array();
        $param["KD_DEALER"]     = $this->delete('kd_dealer');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_DEALER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [maindealer_get description]
     * @return [type] [description]
     */
    public function maindealer_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("nama_maindealer")){
            $param["NAMA_MAINDEALER"]   = $this->get("nama_maindealer");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_MAINDEALER"      => $this->get("keyword"),
                "NAMA_MAINDEALER"    => $this->get("keyword")
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
        $this->resultdata("MASTER_MAINDEALER",$param);
    }

    
    /**
     * [maindealer_post description]
     * @return [type] [description]
     */
    public function maindealer_post() {
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $this->Main_model->data_sudahada($param,"MASTER_MAINDEALER");
        $param["NAMA_MAINDEALER"]   = $this->post("nama_maindealer");
        $param["ALAMAT"]            = $this->post("alamat");
        $param["KD_KABUPATEN"]            = $this->post("kd_kabupaten");
        $param["KD_PROPINSI"]            = $this->post("kd_propinsi");
        $param["TELEPON"]            = $this->post("telepon");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_MASTER_MAINDEALER_INSERT",$param,'post',TRUE);
    }
    /**
     * [maindealer_put description]
     * @return [type] [description]
     */
    public function maindealer_put(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["NAMA_MAINDEALER"]   = $this->put("nama_maindealer");
        $param["ALAMAT"]            = $this->put("alamat");
        $param["KD_KABUPATEN"]            = $this->put("kd_kabupaten");
        $param["KD_PROPINSI"]   = $this->put("kd_propinsi");
        $param["TELEPON"]            = $this->put("telepon");
        $param["ROW_STATUS"]            = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MAINDEALER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [maindealer_delete description]
     * @return [type] [description]
     */
    public function maindealer_delete() {
        $param = array();
        $param["KD_MAINDEALER"]     = $this->delete("kd_maindealer");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_MASTER_MAINDEALER_DELETE",$param,'delete',TRUE);
    }
    /**
     * [wilayahdealer_get description]
     * @return [type] [description]
     */
    public function wilayahdealer_get(){
        $param = array();$search='';
        
        if($this->get("kd_wilayah")){
            $param["KD_WILAYAH"]     = $this->get("kd_wilayah");
        }
        if($this->get("nama_wilayah")){
            $param["NAMA_WILAYAH"]   = $this->get("nama_wilayah");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_WILAYAH"      => $this->get("keyword"),
                "NAMA_WILAYAH"    => $this->get("keyword")
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
        $this->resultdata("MASTER_WILAYAH",$param);
    }
    /**
     * [wilayahdealer_post description]
     * @return [type] [description]
     */
    public function wilayahdealer_post(){
        $param = array();
        $param["KD_WILAYAH"]     = $this->post("kd_wilayah");
        $this->Main_model->data_sudahada($param,"MASTER_WILAYAH");
        $param["NAMA_WILAYAH"]   = $this->post("nama_wilayah");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_WILAYAH_INSERT",$param,'post',TRUE);
    }
    /**
     * [wilayahdealer_put description]x
     * @return [type] [description]
     */
    public function wilayahdealer_put(){
        $param = array();
        $param["KD_WILAYAH"]     = $this->put("kd_wilayah");
        $param["NAMA_WILAYAH"]   = $this->put("nama_wilayah");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_WILAYAH_UPDATE",$param,'put',TRUE);
    }
    /**
     * [wilayahdealer_delete description]
     * @return [type] [description]
     */
    public function wilayahdealer_delete(){
        $param = array();
        $param["KD_WILAYAH"]     = $this->delete("kd_wilayah");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");

        $this->resultdata("SP_MASTER_WILAYAH_DELETE",$param,'delete',TRUE);
    }
    
    /**
     * [areadealer_get description]
     * @return [type] [description]
     */
    public function areadealer_get(){
        $param = array();
        
        if($this->get("kd_areadealer")){
            $param["KD_AREADEALER"]     = $this->get("kd_areadealer");
        }
        if($this->get("nama_areadealer")){
            $param["NAMA_AREADEALER"]   = $this->get("nama_areadealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]    = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_AREADEALER"      => $this->get("keyword"),
                "NAMA_AREADEALER"    => $this->get("keyword"),
                "KD_DEALER"          => $this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_AREADEALER");
        }
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->resultdata("MASTER_AREADEALER",$param);
    }
    
    /**
     * [areadealer_post description]
     * @return [type] [description]
     */
    public function areadealer_post(){
        $param = array();
        $param["KD_AREADEALER"]     = $this->post("kd_areadealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"MASTER_AREADEALER");
        $param["NAMA_AREADEALER"]   = $this->post("nama_areadealer");
        $param["RING_AREA"]         = $this->post('ring_area');
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_AREADEALER_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [areadealer_put description]
     * @return [type] [description]
     */
    public function areadealer_put(){
        $param = array();
        $param["KD_AREADEALER"]     = $this->put("kd_areadealer");
        $param["NAMA_AREADEALER"]   = $this->put("nama_areadealer");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["RING_AREA"]         = $this->put('ring_area');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_AREADEALER_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [areadealer_delete description]
     * @return [type] [description]
     */
    public function areadealer_delete(){
        $param = array();
        $param["KD_AREADEALER"]     = $this->delete("kd_areadealer");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_AREADEALER_DELETE",$param,'delete',TRUE);
    }
    /**
     * [statusdealer_get description]
     * @return [type] [description]
     */
    public function statusdealer_get(){
        $param = array();$search='';

        if($this->get("kd_statusdealer")){
            $param["KD_STATUSDEALER"]   = $this->get("kd_statusdealer");
        }
        if($this->get("nama_statusdealer")){
            $param["NAMA_STATUSDEALER"] = $this->get("nama_statusdealer");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_STATUSDEALER"      => $this->get("keyword"),
                "NAMA_STATUSDEALER"    => $this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_STATUSDEALER");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_STATUSDEALER",$param);
    }
    /**
     * [statusdealer_post description]
     * @return [type] [description]
     */
    public function statusdealer_post(){
        $param = array();
        $param["KD_STATUSDEALER"]   = $this->post("kd_statusdealer");
        $this->Main_model->data_sudahada($param,"MASTER_STATUSDEALER");
        $param["NAMA_STATUSDEALER"] = $this->post("nama_statusdealer");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_MASTER_STATUSDEALER_INSERT",$param,'post',TRUE);
    }
    /**
     * [statusdealer_put description]
     * @return [type] [description]
     */
    public function statusdealer_put(){
        $param = array();
        $param["KD_STATUSDEALER"]   = $this->put("kd_statusdealer");
        $param["NAMA_STATUSDEALER"] = $this->put("nama_statusdealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_STATUSDEALER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [statusdealer_delete description]
     * @return [type] [description]
     */
    public function statusdealer_delete(){
        $param = array();
        $param["KD_STATUSDEALER"]     = $this->delete("kd_statusdealer");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_STATUSDEALER_DELETE",$param,'delete',TRUE);
    }
    /**
     * [jenisdealer_get description]
     * @return [type] [description]
     */
    public function jenisdealer_get(){
        $param = array(); $search='';
        if($this->get("kd_jenisdealer")){
            $param["KD_JENISDEALER"]   = $this->get("kd_jenisdealer");
        }
        if($this->get("nama_jenisdealer")){
            $param["NAMA_JENISDEALER"] = $this->get("nama_jenisdealer");
        }
       
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_JENISDEALER"      => $this->get("keyword"),
                "NAMA_JENISDEALER"    => $this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_JENISDEALER");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_JENISDEALER",$param);
    }
    /**
     * [jenisdealer_post description]
     * @return [type] [description]
     */
    public function jenisdealer_post(){
        $param = array();
        $param["KD_JENISDEALER"]   = $this->post("kd_jenisdealer");
        $this->Main_model->data_sudahada($param,"MASTER_JENISDEALER");
        $param["NAMA_JENISDEALER"] = $this->post("nama_jenisdealer");
        $param["CREATED_BY"]       = $this->post("created_by");

        $this->resultdata("SP_MASTER_JENISDEALER_INSERT",$param,'post',TRUE);
    }
    /**
     * [jenisdealer_put description]
     * @return [type] [description]
     */
    public function jenisdealer_put(){
        $param = array();
        $param["KD_JENISDEALER"]   = $this->put("kd_jenisdealer");
        $param["NAMA_JENISDEALER"] = $this->put("nama_jenisdealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_JENISDEALER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jenisdealer_delete description]
     * @return [type] [description]
     */
    public function jenisdealer_delete(){
        $param = array();
        $param["KD_JENISDEALER"]   = $this->delete("kd_jenisdealer");
        $param["LASTMODIFIED_BY"]  = $this->delete("lastmodified_by");

        $this->resultdata("SP_MASTER_JENISDEALER_DELETE",$param,'delete',TRUE);
    }
    /**
     * [pitdealer_get description]
     * @return [type] [description]
     */
    public function pitdealer_get(){
        $param = array();$search='';
        
        if($this->get("kd_pit")){
            $param["KD_PIT"]        = $this->get("kd_pit");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if ($this->get("nama_pit")) {
            $param["NAMA_PIT"]  = $this->get("nama_pit");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_PIT"      => $this->get("keyword"),
                "KD_DEALER"     => $this->get("keyword"),
                "NAMA_PIT"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PITDEALER",$param);
    }
    /**
     * [pitdealer_post description]
     * @return [type] [description]
     */
    public function pitdealer_post(){
        $param = array();
        $param["KD_PIT"]        = $this->post("kd_pit");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"MASTER_PITDEALER");
        $param["NAMA_PIT"]      = $this->post("nama_pit");
        $param["JENIS_PIT"]     = $this->post("jenis_pit");
        $param["URUTAN"]        = $this->post("urutan");
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_MASTER_PITDEALER_INSERT",$param,'post',TRUE);
    }
    /**
     * [pitdealer_put description]
     * @return [type] [description]
     */
    public function pitdealer_put(){
        $param = array();
        $param["KD_PIT"]        = $this->put("kd_pit");
        $param["KD_DEALER"]     = $this->put("kd_dealer");
        $param["NAMA_PIT"]      = $this->put("nama_pit");
        $param["JENIS_PIT"]     = $this->put("jenis_pit");
        $param["URUTAN"]        = $this->put("urutan");
        $param["ROW_STATUS"]    = $this->put("row_status");
        $param["LASTMODIFIED_BY"]  = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_PITDEALER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pitdealer_delete description]
     * @return [type] [description]
     */
    public function pitdealer_delete(){
        $param = array();
        $param["KD_PIT"]        = $this->delete("kd_pit");
        $param["KD_DEALER"]     = $this->delete("kd_dealer");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PITDEALER_DELETE",$param,'delete',TRUE);
    }
    /**
     * [gudang_get description]
     * @return [type] [description]
     */
    public function gudang_get($view=null){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]       = $this->get("kd_dealer");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]       = $this->get("kd_gudang");
        }

        if($this->get("nama_gudang")){
            $param["NAMA_GUDANG"]     = $this->get("nama_gudang");
        }
        if($this->get("jenis_gudang")){
            $param["JENIS_GUDANG"]     = $this->get("jenis_gudang");
        }
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_GUDANG"      => $this->get("keyword"),
                "NAMA_GUDANG"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if(!$this->get("orderby")){

        }
        if($this->get("orderby")){
            $this->Main_model->set_orderby($this->get("orderby"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($view){
            $this->resultdata("MASTER_GUDANG_PART",$param);
        }else{
            $this->resultdata("MASTER_GUDANG",$param);
        }
    }
    /**
     * [gudang_post description]
     * @return [type] [description]
     */
    public function gudang_post(){
        $param = array();
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["KD_LOKASIDEALER"]  = $this->post("kd_lokasidealer");
        $param["JENIS_GUDANG"]  = $this->post("jenis_gudang");
        $param["KD_GUDANG"] = $this->post("kd_gudang");
        $this->Main_model->data_sudahada($param,"MASTER_GUDANG");
        $param["NAMA_GUDANG"]   = $this->post("nama_gudang");
        $param["ALAMAT"]  = $this->post("alamat");
        $param["DEFAULTS"]  = $this->post("defaults");
        $param["CREATED_BY"]  = $this->post("created_by");
        $this->resultdata("SP_MASTER_GUDANG_INSERT",$param,'post',TRUE);
    }
    /**
     * [gudang_put description]
     * @return [type] [description]
     */
    public function gudang_put(){
        $param = array();
        $param["ID"] = $this->put("id");
        $param["KD_GUDANG"] = $this->put("kd_gudang");
        $param["NAMA_GUDANG"]   = $this->put("nama_gudang");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["ALAMAT"]  = $this->put("alamat");
        $param["DEFAULTS"] = $this->put("defaults");
        $param["JENIS_GUDANG"]  = $this->put("jenis_gudang");
        $param["KD_LOKASIDEALER"]  = $this->put("kd_lokasidealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]  = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_GUDANG_UPDATE",$param,'put',TRUE);
    }

     /**
     * [gudang_status_put description]
     * @return [type] [description]
     */
    public function gudang_status_put(){
        $param = array();
        $param["ID"] = $this->put("id");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $this->resultdata("SP_MASTER_GUDANG_STATUS_UPDATE",$param,'put',TRUE);
    }


    /**
     * [gudang_delete description]
     * @return [type] [description]
     */
    public function gudang_delete(){
        $param = array();
        $param["ID"] = $this->delete("id");
        $param["LASTMODIFIED_BY"]  = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_GUDANG_DELETE",$param,'delete',TRUE);
    }
    
   /**
    * [company_get description]
    * @return [type] [description]
    */
    public function company_get(){
        $param = array();$search='';
        if($this->get("kd_company")){
            $param["KD_COMPANY"] = $this->get("kd_company");
        }
        if($this->get("nama_company")){
            $param["NAMA_COMPANY"]     = $this->get("nama_company");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_COMPANY"   => $this->get("keyword"),
                "NAMA_COMPANY" => $this->get("keyword")
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
        $this->resultdata("MASTER_COMPANY",$param);
    }

    /**
     * [company_post description]
     * @return [type] [description]
     */
    public function company_post(){
        $param = array();
        $param["KD_DEALER"]   = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_COMPANY");
        $param["KD_COMPANY"]    = $this->post('kd_company');
        $param["PIMPINAN_DEALER"]   = $this->post('pimpinan_dealer');
        $param["NAMA_COMPANY"]   = $this->post('nama_company');
        $param["KEPALA_GUDANG"]   = $this->post('kepala_gudang');
        $param["CREATED_BY"]    = $this->post("created_by");
       // print_r($param);
        $this->resultdata("SP_MASTER_COMPANY_INSERT",$param,'post',TRUE);
    }
    /**
     * [company_put description]
     * @return [type] [description] 
     */
    public function company_put(){
        $param = array();
        $param["KD_COMPANY"]    = $this->put('kd_company');
        $param["NAMA_COMPANY"]   = $this->put('nama_company');
        $param["PIMPINAN_DEALER"]   = $this->put('pimpinan_dealer');
        $param["KEPALA_GUDANG"]   = $this->put('kepala_gudang');
        $param["KD_DEALER"]   = $this->put('kd_dealer');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_COMPANY_UPDATE",$param,'put',TRUE);
    }
    /**
     * [company_delete description]
     * @return [type] [description]
     */
    public function company_delete(){
        $param = array();
        $param["KD_COMPANY"]     = $this->delete('kd_company');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_COMPANY_DELETE",$param,'delete',TRUE);
    }

    /**
     * [company_finance_get description]
     * @return [type] [description]
     */
    public function company_finance_get(){
        $param = array();$search='';
        
        if($this->get("kd_leasing")){
            $param["KD_LEASING"]        = $this->get("kd_leasing");
        }
        if($this->get("nama_leasing")){
            $param["NAMA_LEASING"]     = $this->get("nama_leasing");
        }
        if($this->get("kd_leasingahm")){
            $param["KD_LEASINGAHM"]        = $this->get("kd_leasingahm");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_LEASING"      => $this->get("keyword"),
                "NAMA_LEASING"    => $this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_LEASING");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_COM_FINANCE",$param);
    }

    /**
     * [company_finance_post description]
     * @return [type] [description]
     */
    public function company_finance_post(){
        $param = array();
        $param["KD_LEASING"]      = $this->post('kd_leasing');
        $this->Main_model->data_sudahada($param,"MASTER_COM_FINANCE");
        $param["KD_LEASINGAHM"]        = $this->post('kd_leasingahm');
        $param["NAMA_LEASING"]    = $this->post('nama_leasing');
        $param["CREATED_BY"]    = $this->post("created_by");
       // print_r($param);
        $this->resultdata("SP_MASTER_COM_FINANCE_INSERT",$param,'post',TRUE);
    }

    /**
     * [company_finance_put description]
     * @return [type] [description]
     */
    public function company_finance_put(){
        $param = array();
        $param["KD_LEASING"]      = $this->put('kd_leasing');
        $param["NAMA_LEASING"]    = $this->put('nama_leasing');
        $param["KD_LEASINGAHM"]        = $this->put('kd_leasingahm');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_COM_FINANCE_UPDATE",$param,'put',TRUE);

    }

    public function company_finance_delete(){       
        $param=array();
        $param["KD_LEASING"] = $this->delete("kd_leasing");
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_COM_FINANCE_DELETE",$param,'delete',TRUE);       
    }

    /** 
     * [divisi_get description]
     * @return [type] [description]
     */
    public function divisi_get(){
        $param = array(); $search='';
        if($this->get("kd_div")){
            $param["KD_DIV"]        = $this->get("kd_div");
        }
        if($this->get("nama_div")){
            $param["NAMA_DIV"]     = $this->get("nama_div");
        }
       
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_DIV"   => $this->get("keyword"),
                "NAMA_DIV" => $this->get("keyword")
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
            $this->Main_model->set_orderby("NAMA_DIV");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_DIVISI_V",$param);
    }

    /**
     * [divisi_post description]
     * @return [type] [description]
     */

    public function divisi_post(){
        $param = array();
        $param["KD_DIV"]     = $this->post('kd_div');
        $this->Main_model->data_sudahada($param,"MASTER_DIVISI");
        $param["NAMA_DIV"]   = $this->post('nama_div');
        $param["CREATED_BY"] = $this->post("created_by");
       
        $this->resultdata("SP_MASTER_DIVISI_INSERT",$param,'post',TRUE);
    }
    /**
     * [divisi_put description]
     * @return [type] [description]
     */

   public function divisi_put(){      
        $param=array();
        $param["KD_DIV"]   = $this->put("kd_div");
        $param["NAMA_DIV"] = $this->put("nama_div");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
        
        $this->resultdata("SP_MASTER_DIVISI_UPDATE",$param,'put',TRUE);       

    }

    /**
     * [divisi_delete description]
     * @return [type] [description]
     */

    public function divisi_delete(){       
        $param=array();
        $param["KD_DIV"] = $this->delete("kd_div");
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_DIVISI_DELETE",$param,'delete',TRUE);       
    }

    
    /**
     * [insentif_get description]
     * @return [type] [description]
     */
    public function insentif_get(){
        $param = array();
        
        if($this->get("kategori")){
            $param["KATEGORI"]     = $this->get("kategori");
        }
        if($this->get("kd_motor")){
            $param["KD_MOTOR"]   = $this->get("kd_motor");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KATEGORI"      => $this->get("keyword"),
                "KD_MOTOR"    => $this->get("keyword")
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
        $this->resultdata("MASTER_INSENTIF",$param);
    }
    
    /**
     * [insentif_post description]
     * @return [type] [description]
     */
    public function insentif_post(){
        $param = array();
        $param["KATEGORI"]     = $this->post("kategori");
        $param["KD_MOTOR"]         = $this->post("kd_motor");
        $param["KD_CATEGORY"]   = $this->post("kd_category"); 
        $param["KD_DEALER"]   = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"MASTER_INSENTIF");
        $param["CASH"]         = $this->post("cash");
        $param["KREDIT"]         = $this->post("kredit");
        $param["KHUSUS"]         = $this->post("khusus");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_INSENTIF_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [insentif_put description]
     * @return [type] [description]
     */
    public function insentif_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["KATEGORI"]     = $this->put("kategori");
        $param["KD_MOTOR"]   = $this->put("kd_motor"); 
        $param["CASH"]   = $this->put("cash"); 
        $param["KREDIT"]   = $this->put("kredit"); 
        $param["KHUSUS"]   = $this->put("khusus");
        $param["KD_CATEGORY"]   = $this->put("kd_category"); 
        $param["KD_DEALER"]   = $this->put("kd_dealer"); 
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_INSENTIF_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [insentif_delete description]
     * @return [type] [description]
     */
    public function insentif_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_INSENTIF_DELETE",$param,'delete',TRUE);
    }

    /**
     * [gc_get description]
     * @return [type] [description]
     */
    public function gc_get(){
        $param = array();
        
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
        }
        if($this->get("nama_program")){
            $param["NAMA_PROGRAM"]   = $this->get("nama_program");
        }
        if($this->get("kd_gc")){
            $param["KD_GC"]     = $this->get("kd_gc");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_GC"      => $this->get("keyword"),
                "NAMA_PROGRAM"    => $this->get("keyword")
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
            $this->Main_model->set_orderby("KD_GC");
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_GC",$param);
    }
    
    /**
     * [gc_post description]
     * @return [type] [description]
     */
    public function gc_post(){
        $param = array();
        $param["KD_GC"]         = $this->post("kd_gc");
        
        $param["NAMA_PROGRAM"]  = ($this->post("nama_program"));
        $param["START_DATE"]    = $this->post("start_date");
        $param["END_DATE"]      = $this->post("end_date");
        $param["S_AHM"]         = $this->post("s_ahm");
        $param["S_MD"]          = $this->post("s_md");
        $param["S_SD"]          = $this->post("s_sd");
        $param["LKPP_KALSES"]   = $this->post("lkpp_kalses");
        $param["LKPP_KALTENG"]  = $this->post("lkpp_kalteng");
        $param["KD_TYPEMOTOR"]  = $this->post("kd_typemotor");
        $this->Main_model->data_sudahada($param,"MASTER_GC");
        $param["CREATED_BY"]    = $this->post("created_by");

        $this->resultdata("SP_MASTER_GC_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [gc_put description]
     * @return [type] [description]
     */
    public function gc_put(){
        $param = array();
        $param["KD_GC"]             = $this->put("kd_gc");
        $param["NAMA_PROGRAM"]      = $this->put("nama_program"); 
        $param["START_DATE"]        = $this->put("start_date"); 
        $param["END_DATE"]          = $this->put("end_date"); 
        $param["S_AHM"]             = $this->put("s_ahm");
        $param["S_MD"]              = $this->put("s_md"); 
        $param["S_SD"]              = $this->put("s_sd"); 
        $param["LKPP_KALSES"]       = $this->put("lkpp_kalses");  
        $param["LKPP_KALTENG"]      = $this->put("lkpp_kalteng");  
        $param["KD_TYPEMOTOR"]      = $this->put("kd_typemotor");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_GC_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [gc_delete description]
     * @return [type] [description]
     */
    public function gc_delete(){
        $param = array();
        $param["KD_GC"]     = $this->delete("kd_gc");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_GC_DELETE",$param,'delete',TRUE);
    }

    /**
     * [targetsf_get description]
     * @return [type] [description]
     */
    public function targetsf_get(){
        $param = array();
        
        if($this->get("kd_sales")){
            $param["KD_SALES"]     = $this->get("kd_sales");
        }
        if($this->get("target")){
            $param["TARGET"]   = $this->get("target");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_SALES"      => $this->get("keyword"),
                "TARGET"    => $this->get("keyword")
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
        $this->resultdata("MASTER_TARGETSF",$param);
    }
    
    /**
     * [targetsf_post description]
     * @return [type] [description]
     */
    public function targetsf_post(){
        $param = array();
        $param["KD_SALES"]         = $this->post("kd_sales");
        $param["START_DATE"]         = tglToSql($this->post("start_date"));
        $param["END_DATE"]         = tglToSql($this->post("end_date"));
        $this->Main_model->data_sudahada($param,"MASTER_TARGETSF");
        $param["TARGET"]     = $this->post("target");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_TARGETSF_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [targetsf_put description]
     * @return [type] [description]
     */
    public function targetsf_put(){
        $param = array();
        $param["ID"]     = $this->put("id");
        $param["START_DATE"]   = tglToSql($this->put("start_date")); 
        $param["END_DATE"]   = tglToSql($this->put("end_date")); 
        $param["KD_SALES"]   = $this->put("kd_sales");
        $param["TARGET"]   = $this->put("target");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_TARGETSF_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [targetsf_delete description]
     * @return [type] [description]
     */
    public function targetsf_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_TARGETSF_DELETE",$param,'delete',TRUE);
    }
    /**
     * Upload file to server for proses to database
     * @return [type] [description]
     */
    public function upload_post(){
      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = '*';

      $this->load->library('upload');
      $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('file'))
        {
            $error = array('error' => $this->upload->display_errors());
            $this->response($error);
        }else{
            $data = array('upload_data' => $this->upload->data());
            $this->response($data);
        }
    }

    /**
     * [training_get description]
     * @return [type] [description]
     */
    public function training_get(){
        $param = array();$search='';
        if($this->get("kd_training")){
            $param["KD_TRAINING"]     = $this->get("kd_training");
        }
        if($this->get("nama_training")){
            $param["NAMA_TRAINING"]     = $this->get("nama_training");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
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
        $this->resultdata("MASTER_TRAINING",$param);
    }
    /**
     * [training_post description]
     * @return [type] [description]
     */
    public function training_post(){
        $param = array();
        $param["KD_TRAINING"]       = $this->post('kd_training');
        $param["NAMA_TRAINING"]     = $this->post('nama_training');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_TRAINING",TRUE);
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_TRAINING_INSERT",$param,'post',TRUE);
    }
    /**
     * [training_put description]
     * @return [type] [description]
     */
    public function training_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_TRAINING"]       = $this->put('kd_training');
        $param["NAMA_TRAINING"]     = $this->put('nama_training');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_TRAINING_UPDATE",$param,'put',TRUE);
    }

    /**
     * [training_record_delete description]
     * @return [type] [description]
     */
    public function training_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_TRAINING_DELETE",$param,'delete',TRUE);
    } 

    /**
     * [toleransi_get description]
     * @return [type] [description]
     */
    public function toleransi_get(){
        $param = array();$search='';
        if($this->get("kd_propinsi")){
            $param["KD_PROPINSI"]     = $this->get("kd_propinsi");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
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
                "KD_KABUPATEN" => $this->get("keyword"),
                "TOLERANSI" => $this->get("keyword")
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
        $this->resultdata("MASTER_TOLERANSI",$param);
    }
    /**
     * [toleransi_post description]
     * @return [type] [description]
     */
    public function toleransi_post(){
        $param = array();
        $param["KD_PROPINSI"]       = $this->post('kd_propinsi');
        $param["KD_KABUPATEN"]      = $this->post('kd_kabupaten');
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_TOLERANSI",TRUE);
        $param["TOLERANSI"]         = $this->post('toleransi');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_TOLERANSI_INSERT",$param,'post',TRUE);
    }
    /**
     * [toleransi_put description]
     * @return [type] [description]
     */
    public function toleransi_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_PROPINSI"]       = $this->put('kd_propinsi');
        $param["KD_KABUPATEN"]      = $this->put('kd_kabupaten');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["TOLERANSI"]         = $this->put('toleransi');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_TOLERANSI_UPDATE",$param,'put',TRUE);
    }

    /**
     * [toleransi_delete description]
     * @return [type] [description]
     */
    public function toleransi_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_TOLERANSI_DELETE",$param,'delete',TRUE);
    }
    /**
     * [master_event_get description]
     * @return [type] [description]
     */
    public function master_event_get(){
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
        if($this->get("id_event")){
            $param["ID_EVENT"]     = $this->get("id_event");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_EVENT"     => $this->get("keyword"),
                "DESC_EVENT"     => $this->get("keyword"),
                "JENIS_EVENT"    => $this->get("keyword"),
                "ID_EVENT"    => $this->get("keyword")
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
        $this->resultdata("MASTER_EVENT",$param);
    }
    /**
     * [master_event_post description]
     * @return [type] [description]
     */
    public function master_event_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["ID_EVENT"]          = $this->post('id_event');
        $this->Main_model->data_sudahada($param,"MASTER_EVENT",TRUE);
        //$param["KD_EVENT"]          = $this->post('kd_event');
        $param["NAMA_EVENT"]        = $this->post("nama_event");
        $param["JENIS_EVENT"]       = $this->post('jenis_event');
        $param["DESC_EVENT"]        = $this->post('desc_event');
        $param["UNIT_TARGET"]       = $this->post("unit_target");
        $param["REVENUE_TARGET"]    = $this->post("revenue_target");
        $param["START_DATE"]        = tglToSql($this->post("start_date"));
        $param["END_DATE"]          = tglToSql($this->post('end_date'));
        $param["BUDGET_EVENT"]      = $this->post("budget_event");
        $param["LOC_EVENT"]         = $this->post('loc_event');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_MASTER_EVENT_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [master_event_put description]
     * @return [type] [description]
     */
    public function master_event_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["ID_EVENT"]          = $this->put('id_event');
        //$param["KD_EVENT"]          = $this->put('kd_event');
        $param["NAMA_EVENT"]        = $this->put("nama_event");
        $param["JENIS_EVENT"]       = $this->put('jenis_event');
        $param["DESC_EVENT"]        = $this->put('desc_event');
        $param["UNIT_TARGET"]       = $this->put("unit_target");
        $param["REVENUE_TARGET"]    = $this->put("revenue_target");
        $param["START_DATE"]        = tglToSql($this->put("start_date"));
        $param["END_DATE"]          = tglToSql($this->put('end_date'));
        $param["BUDGET_EVENT"]      = $this->put("budget_event");
        $param["LOC_EVENT"]         = $this->put('loc_event');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_EVENT_UPDATE",$param,'put',TRUE);
    }

    public function master_event_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_MASTER_EVENT_DELETE",$param,'delete',TRUE);
    }

    /**
     * [master_event_app_put description]
     * @return [type] [description]
     */
    public function master_event_app_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["STATUS"]            = $this->put('status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_EVENT_UPDATE_APPROVE",$param,'put',TRUE);
    }

    /**
     * [master_event_rej_put description]
     * @return [type] [description]
     */
    public function master_event_rej_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["STATUS"]            = $this->put('status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_EVENT_UPDATE_REJECT",$param,'put',TRUE);
    }

    /**
     * [targeth3dealer_get description]
     * @return [type] [description]
     */
    public function targeth3dealer_get(){
        $param = array();
        
        if($this->get("kategori")){
            $param["KATEGORI"]     = $this->get("kategori");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KATEGORI"      => $this->get("keyword"),
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
        $this->resultdata("MASTER_TARGET_H3_DEALER",$param);
    }
    
    /**
     * [targeth3dealer_post description]
     * @return [type] [description]
     */
    public function targeth3dealer_post(){
        //echo '<script>console.log("targeth3dealer_post")</script>';
        $param = array();
        $param["KATEGORI"]     = $this->post("kategori");
        $param["KD_DEALER"]    = $this->post("kd_dealer");
        $param["START_DATE"]   = tglToSql($this->post("start_date"));
        $param["END_DATE"]     = tglToSql($this->post("end_date"));
        
        $this->Main_model->data_sudahada($param,"MASTER_TARGET_H3_DEALER");
        $param["TARGET"]   = $this->post("target"); 
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_TARGET_H3_DEALER_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [targeth3dealer_put description]
     * @return [type] [description]
     */
    public function targeth3dealer_put(){
        $param = array();
        $param["ID"]           = $this->put("id");
        $param["KATEGORI"]     = $this->put("kategori");
        $param["KD_DEALER"]    = $this->put("kd_dealer");
        $param["START_DATE"]   = tglToSql($this->put("start_date"));
        $param["END_DATE"]     = tglToSql($this->put("end_date"));
        $param["TARGET"]       = $this->put("target"); 
        $param["ROW_STATUS"]   = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_TARGET_H3_DEALER_UPDATE",$param,'put',TRUE);
    }
    
     public function targeth3dealerapprove_put(){
        $param = array();
     
        $param["KD_DEALER"]    = $this->put("kd_dealer");
        $param["STATUS_APPROVE"]           = $this->put("status_approve");
       
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_TARGET_H3_DEALER_APPROVE_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [insentif_delete description]
     * @return [type] [description]
     */
    public function targeth3dealer_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_TARGET_H3_DEALER_DELETE",$param,'delete',TRUE);
    }
    
    /**
     * [masterinsentifh3_get description]
     * @return [type] [description]
     */
    public function masterinsentifh3_get(){
        $param = array();
        
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]   = $this->get("kd_dealer");
        }
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_DEALER"    => $this->get("kd_dealer"),
                "NIK"      => $this->get("nik"),
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
        $this->resultdata("MASTER_INSENTIF_H3",$param);
    }
    
    /**
     * [targeth3dealer_post description]
     * @return [type] [description]
     */
    public function masterinsentifh3_post(){
        //echo '<script>console.log("targeth3dealer_post")</script>';
        $param = array();
       
        $param["KD_DEALER"]    = $this->post("kd_dealer");
        $param["NIK"]     = $this->post("nik");
        
        
        $this->Main_model->data_sudahada($param,"MASTER_INSENTIF_H3");
        $param["PERSENTASE"]   = $this->post("persentase");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_INSENTIF_H3_INSERT",$param,'post',TRUE);
    }
    
    /**
     * created by Dimas Rido 
     * merge on 25/03/2019
     * [targeth3dealer_put description]
     * @return [type] [description]
     */
    public function masterinsentifh3_put(){
        $param = array();
        $param["ID"]           = $this->put("id");    
        $param["KD_DEALER"]    = $this->put("kd_dealer");
        $param["NIK"]           = $this->put("nik");
        $param["PERSENTASE"]       = $this->put("persentase");
        $param["ROW_STATUS"]   = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_INSENTIF_H3_UPDATE",$param,'put',TRUE);
    }
    
    public function masterinsentifh3approve_put(){
        $param = array();
     
        $param["KD_DEALER"]    = $this->put("kd_dealer");
        $param["STATUS_APPROVE"]           = $this->put("status_approve");
       
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_INSENTIF_H3APPROVE_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [insentif_delete description]
     * @return [type] [description]
     */
    public function masterinsentifh3_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_INSENTIF_H3_DELETE",$param,'delete',TRUE);
    }

    public function insentif_stnk_get(){
        $param = array();
        $param["KD_CONFIG"]     = 'MIPS';
        $this->resultdata("CONFIG_APP",$param);
    }
  
    public function insentif_stnk_put(){
        $param = array();
        $param["ID"] = $this->put("id");
        $param["VALUE_CONFIG"]   = $this->put("value_config");
        $param["CREATED_BY"]   = $this->put("created_by");
        $this->resultdata("SP_MASTER_INSENTIF_PIC_STNK_UPDATE",$param,'put',TRUE);
    }
    
    public function config_proses_insentifpic_stnk_put(){
        $param = array();
        $param["ID"] = $this->put("id");
        $param["KD_CONFIG"] = $this->put("kd_config");
        $param["NAMA_CONFIG"] = $this->put("nama_config");
        $param["VALUE_CONFIG"]   = $this->put("value_config");
        $param["CREATED_BY"]   = $this->put("created_by");
        $this->resultdata("SP_CONFIG_PROSES_INSENTIF_PICSTNK_UPDATE",$param,'put',TRUE);
    }

     /**
     * [dealerbooking_get description]
     * @return [type] [description]
     */
    public function dealerbooking_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if($this->get("kd_dealerahm")){
            $param["KD_DEALERAHM"] = $this->get("kd_dealerahm");
        }
        if($this->get("nama_dealer")){
            $param["NAMA_DEALER"] = $this->get("nama_dealer");
        }
        if($this->get("tanggal_pkb")){
            $param["TANGGAL_PKB"] = $this->get("tanggal_pkb");
        }
        
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_DEALER"      => $this->get("keyword"),
                "KD_DEALERAHM"  => $this->get("keyword"),
                "NAMA_DEALER"    => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if(!$this->get("orderby")){
            $this->Main_model->set_orderby("KD_DEALER,TANGGAL_PKB");
        }else{
            $this->Main_model->set_orderby($this->get("orderby"));
        }
        $this->Main_model->set_where_in($this->get("where_in"));
        $this->Main_model->set_whereinfield($this->get("where_in_field"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
       
         $this->resultdata("MASTER_DEALER_BOOKING_VIEW",$param);
        
    }
}
?>