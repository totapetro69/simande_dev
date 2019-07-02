<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Sparepart extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
        $this->load->model('Custom_model');
        $this->load->helper("zetro");
        $this->load->helper("url");
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
     * [part_get description]
     * @return [type] [description]
     */
    public function part_get(){
        $param = array();
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("part_deskripsi")){
            $param["PART_DESKRIPSI"]   = $this->get("part_deskripsi");
        }
        if($this->get("part_rank")){
            $param["PART_RANK"]   = $this->get("part_rank");
        }
        if($this->get("part_moving")){
            $param["PART_MOVING"]   = $this->get("part_moving");
        }
        if($this->get("part_group")){
            $param["PART_GROUP"]   = $this->get("part_group");
        }
        if($this->get("part_source")){
            $param["PART_SOURCE"]   = $this->get("part_source");
        }
        if($this->get("part_current")){
            $param["PART_CURRENT"]   = $this->get("part_current");
        }
        if($this->get("het")){
            $param["HET"]   = $this->get("het");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"      => $this->get("keyword"),
                "PART_DESKRIPSI"    => $this->get("keyword"),
                "HET"    => $this->get("keyword")
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

        $this->resultdata("MASTER_PART_V",$param);
    }
    
    /**
     * [part_post description]
     * @return [type] [description]
     */
    public function part_post(){
        $param = array();
        $param["PART_NUMBER"]     = $this->post("part_number");
        $param["PART_DESKRIPSI"]         = $this->post("part_deskripsi");
        $param["PART_MOVING"]   = $this->post("part_moving");
        $param["PART_SOURCE"]   = $this->post("part_source");
        $param["PART_RANK"]   = $this->post("part_rank");
        $param["PART_GROUP"]   = $this->post("part_group");
        $param["PART_CURRENT"]   = $this->post("part_current");
        $this->Main_model->data_sudahada($param,"MASTER_PART");
        $param["HET"]   = $this->post("het");
        $param["HARGA_BELI"]   = $this->post("harga_beli");
        $param["KD_SUPPLIER"]   = $this->post("kd_supplier");
        $param["KD_GROUPSALES"]   = $this->post("kd_groupsales");
        $param["PART_REFERENCE"]   = $this->post("part_reference");
        $param["PART_STATUS"]   = $this->post("part_status");
        $param["PART_SUPERSEED"]   = $this->post("part_superseed");
        $param["MOQ_DK"]   = $this->post("moq_dk");
        $param["MOQ_DM"]   = $this->post("moq_dm");
        $param["MOQ_DB"]   = $this->post("moq_db");
        $param["PART_NUMBERTYPE"]   = $this->post("part_numbertype");
        $param["PART_TYPE"]   = $this->post("part_type");
        $param["PART_LIFETIME"]   = $this->post("part_lifetime");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_PART_INSERT",$param,'post',TRUE);
    }
    public function partbatch_post(){
        ini_set('max_execution_time',200);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
        $folderJson = getConfig("UPJSON")."\part.json";
        $handle=fopen($folderJson,'wb');
        fwrite($handle,$param);
        fclose($handle);
        $datax=$this->Custom_model->querypdmp();
        unlink($folderJson);
        if($datax){
            $result["status"]   = FALSE;
            $result["message"]  = "Data gagal di simpan";
            $result["debug"]    = "";
            $result["param"]    = $this->db->last_query();
            $result["recordexists"]=FALSE;

        }else{
            $result["status"]   = "success";
            $result["message"]  = "Data berhasil di upload";
            $result["location"] =(isset($this->location))?$this->get_location():"";
            $result["param"]    = "";//$this->db->last_query();
            $result["recordexists"]=FALSE;
        }
        $this->response($result);
    }

    public function pdsimbatch_post(){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
        // $folderJson = "\\\\".getConfig("UPJSON")."\\tmp\pdsim.json";
        $folderJson = getConfig("UPJSON")."\pdsim.json";
        //chmod($folderJson, 0777);
        $handle=fopen($folderJson,'wb');
        fwrite($handle,$param);
        fclose($handle);
        $datax=$this->Custom_model->querypdsim();
        unlink($folderJson);
        if($datax){
            $result["status"]   = FALSE;
            $result["message"]  = "Data gagal di simpan";
            $result["debug"]    = "";
            $result["param"]    = $this->db->last_query();
            $result["recordexists"]=FALSE;

        }else{
            $result["status"]   = "success";
            $result["message"]  = "Data berhasil di upload";
            $result["location"] =(isset($this->location))?$this->get_location():"";
            $result["param"]    = "";//$this->db->last_query();
            $result["recordexists"]=FALSE;
        }
        $this->response($result);
    }
    /**
     * [part_put description]
     * @return [type] [description]
     */
    public function part_put(){
        $param = array();
        $param["PART_NUMBER"]     = $this->put("part_number");
        $param["PART_DESKRIPSI"]   = $this->put("part_deskripsi");
        $param["HET"]         = $this->put("het");
        $param["HARGA_BELI"]   = $this->put("harga_beli");
        $param["KD_SUPPLIER"]   = $this->put("kd_supplier");
        $param["KD_GROUPSALES"]   = $this->put("kd_groupsales");
        $param["PART_REFERENCE"]   = $this->put("part_reference");
        $param["PART_STATUS"]   = $this->put("part_status");
        $param["PART_SUPERSEED"]   = $this->put("part_superseed");
        $param["MOQ_DK"]   = $this->put("moq_dk");
        $param["MOQ_DM"]   = $this->put("moq_dm");
        $param["MOQ_DB"]   = $this->put("moq_db");
        $param["PART_NUMBERTYPE"]   = $this->put("part_numbertype");
        $param["PART_MOVING"]   = $this->put("part_moving");
        $param["PART_SOURCE"]   = $this->put("part_source");
        $param["PART_RANK"]   = $this->put("part_rank");
        $param["PART_CURRENT"]   = $this->put("part_current");
        $param["PART_TYPE"]   = $this->put("part_type");
        $param["PART_LIFETIME"]   = $this->put("part_lifetime");
        $param["PART_GROUP"]   = $this->put("part_group");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_PART_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_delete description]
     * @return [type] [description]
     */
    public function part_delete(){
        $param = array();
        $param["PART_NUMBER"]     = $this->delete("part_number");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PART_DELETE",$param,'delete',TRUE);
    }


    /**
     * [sim_parts_get description]
     * @return [type] [description]
     */
    public function sim_parts_get(){
        $param = array();
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kategori_ahass")){
            $param["KATEGORI_AHASS"]   = $this->get("kategori_ahass");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"      => $this->get("keyword")
                /*"KATEGORI_AHASS"    => $this->get("keyword")*/
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
        $this->resultdata("MASTER_SIM_PARTS",$param);   
    }
    
    /**
     * [sim_parts_post description]
     * @return [type] [description]
     */
    public function sim_parts_post(){
        $param = array();
        $param["PART_NUMBER"]     = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"MASTER_SIM_PARTS");
        $param["KATEGORI_AHASS"]         = $this->post("kategori_ahass");
        $param["JUMLAH_STANDARITEM_MIN"]   = $this->post("jumlah_standaritem_min");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_SIM_PARTS_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [sim_parts_put description]
     * @return [type] [description]
     */
    public function sim_parts_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["PART_NUMBER"]     = $this->put("part_number");
        $param["KATEGORI_AHASS"]   = $this->put("kategori_ahass"); 
        $param["JUMLAH_STANDARITEM_MIN"]   = $this->put("jumlah_standaritem_min");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_SIM_PARTS_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [sim_parts_delete description]
     * @return [type] [description]
     */
    public function sim_parts_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_SIM_PARTS_DELETE",$param,'delete',TRUE);
    }

    
    /**
     * [pvtm_get description]
     * @return [type] [description]
     */
    public function pvtm_get(){
        $param = array();
        
        if($this->get("no_part_tipemotor")){
            $param["NO_PART_TIPEMOTOR"]     = $this->get("no_part_tipemotor");
        }
        if($this->get("type_marketing")){
            $param["TYPE_MARKETING"]   = $this->get("type_marketing");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_PART_TIPEMOTOR"      => $this->get("keyword"),
                "TYPE_MARKETING"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PVTM",$param);
    }
    
    /**
     * [pvtm_post description]
     * @return [type] [description]
     */
    public function pvtm_post(){
        $param = array();
        $param["NO_PART_TIPEMOTOR"]     = $this->post("no_part_tipemotor");
        $param["TYPE_MARKETING"]         = $this->post("type_marketing");
        $this->Main_model->data_sudahada($param,"MASTER_PVTM");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_PVTM_INSERT",$param,'post',TRUE);
    }

    public function pvtmbatch_post(){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
        // $folderJson = "\\\\".getConfig("UPJSON")."\\tmp\pvtm.json";
        $folderJson = getConfig("UPJSON")."\pvtm.json";
        //echo $folderJson;
        //chmod($folderJson, 0777);
        $handle=fopen($folderJson,'wb');
        //var_dump($handle);
        fwrite($handle,$param);
        fclose($handle);
        $datax=$this->Custom_model->querypvtm();
        unlink($folderJson);
        //var_dump($datax);exit();
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
     * [pvtm_put description]
     * @return [type] [description]
     */
    public function pvtm_put(){
        $param = array();
        $param["NO_PART_TIPEMOTOR"]     = $this->put("no_part_tipemotor");
        $param["TYPE_MARKETING"]   = $this->put("type_marketing"); 
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_PVTM_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [pvtm_delete description]
     * @return [type] [description]
     */
    public function pvtm_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PVTM_DELETE",$param,'delete',TRUE);
    }


     /**
     * [hargabeli_md_get description]
     * @return [type] [description]
     */
    public function hargabeli_md_get(){
        $param = array();
        
        if($this->get("nama_unit")){
            $param["NAMA_UNIT"]     = $this->get("nama_unit");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NAMA_UNIT"      => $this->get("keyword")
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
        $this->resultdata("MASTER_HARGABELI_KE_MD",$param);
    }
    
    /**
     * [hargabeli_md_post description]
     * @return [type] [description]
     */
    public function hargabeli_md_post(){
        $param = array();
        $param["NAMA_UNIT"]     = $this->post("nama_unit");
        $this->Main_model->data_sudahada($param,"MASTER_HARGABELI_KE_MD");
        $param["HARGA_BELI"]         = $this->post("harga_beli");
        $param["KETERANGAN"]         = $this->post("keterangan");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_HARGABELI_KE_MD_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [hargabeli_md_put description]
     * @return [type] [description]
     */
    public function hargabeli_md_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["NAMA_UNIT"]     = $this->put("nama_unit");
        $param["HARGA_BELI"]   = $this->put("harga_beli");
        $param["KETERANGAN"]   = $this->put("keterangan"); 
        $param["ROW_STATUS"]   = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_HARGABELI_KE_MD_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [hargabeli_md_delete description]
     * @return [type] [description]
     */
    public function hargabeli_md_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_HARGABELI_KE_MD_DELETE",$param,'delete',TRUE);
    }




     /**
     * [part_inventory_get description]
     * @return [type] [description]
     */
    public function part_inventory_get(){
        $param = array();
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"      => $this->get("keyword"),
                "KD_DEALER"      => $this->get("keyword")
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
        $this->resultdata("TRANS_PART_INVENTORY",$param);
    }
    
    /**
     * [part_inventory_post description]
     * @return [type] [description]
     */
    public function part_inventory_post(){
        $param = array();
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["PART_NUMBER"]     = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"TRANS_PART_INVENTORY");
        $param["HARGA_BELI"]         = $this->post("harga_beli");
        $param["HET"]         = $this->post("het");
        $param["HARGA_JUAL1"]         = $this->post("harga_jual1");
        $param["HARGA_JUAL2"]         = $this->post("harga_jual2");
        $param["HARGA_JUAL3"]         = $this->post("harga_jual3");
        $param["DISKON"]         = $this->post("diskon");
        $param["STOK"]         = $this->post("stok");
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_TRANS_PART_INVENTORY_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_inventory_put description]
     * @return [type] [description]
     */
    public function part_inventory_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["PART_NUMBER"]     = $this->put("part_number");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["HARGA_BELI"]   = $this->put("harga_beli");
        $param["HET"]   = $this->put("het");
        $param["HARGA_JUAL1"]   = $this->put("harga_jual1");
        $param["HARGA_JUAL2"]   = $this->put("harga_jual2");
        $param["HARGA_JUAL3"]   = $this->put("harga_jual3");
        $param["DISKON"]   = $this->put("diskon");
        $param["STOK"]   = $this->put("stok");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_INVENTORY_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_inventory_delete description]
     * @return [type] [description]
     */
    public function part_inventory_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_INVENTORY_DELETE",$param,'delete',TRUE);
    }


    /**
     * [part_stock_get description]
     * @return [type] [description]
     */
    public function part_stock_get(){
        $param = array();
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"    => $this->get("keyword"),
                "KD_GUDANG"      => $this->get("keyword")
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
        $this->resultdata("TRANS_PART_STOCK",$param);
    }
    
    /**
     * [part_stock_post description]
     * @return [type] [description]
     */
    public function part_stock_post(){
        $param = array();
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["PART_NUMBER"]     = $this->post("part_number");
        $param["KD_GUDANG"]         = $this->post("kd_gudang");
        $param["KD_RAK"]         = $this->post("kd_rak");
        $param["KD_BINBOX"]         = $this->post("kd_binbox");
        $this->Main_model->data_sudahada($param,"TRANS_PART_STOCK");
        $param["STOK"]         = $this->post("stok");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_TRANS_PART_STOCK_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_stock_put description]
     * @return [type] [description]
     */
    public function part_stock_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["PART_NUMBER"]     = $this->put("part_number");
        $param["KD_GUDANG"] = $this->put("kd_gudang");
        $param["KD_RAK"]   = $this->put("kd_rak");
        $param["KD_BINBOX"]   = $this->put("kd_binbox");
        $param["STOK"]   = $this->put("stok");
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_STOCK_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_stock_delete description]
     * @return [type] [description]
     */
    public function part_stock_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_STOCK_DELETE",$param,'delete',TRUE);
    }


    /**
     * [etdahm_get description]
     * @return [type] [description]
     */
    public function etdahm_get(){
        $param = array();
        
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_MAINDEALER"    => $this->get("keyword"),
                "SIFAT_PART"    => $this->get("keyword")
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
        $this->resultdata("SETUP_ETDAHM",$param);
    }
    
    /**
     * [etdahm_post description]
     * @return [type] [description]
     */
    public function etdahm_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["SIFAT_PART"]         = $this->post("sifat_part");
        $param["KATEGORI_PART"]         = $this->post("kategori_part");
         $this->Main_model->data_sudahada($param,"SETUP_ETDAHM");
        $param["ETD"]         = $this->post("etd");
       
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_SETUP_ETDAHM_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [etdahm_put description]
     * @return [type] [description]
     */
    public function etdahm_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["SIFAT_PART"] = $this->put("sifat_part");
        $param["KATEGORI_PART"]   = $this->put("kategori_part");
        $param["ETD"]   = $this->put("etd");
        $param["ROW_STATUS"]   = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_ETDAHM_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [etdahm_delete description]
     * @return [type] [description]
     */
    public function etdahm_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_ETDAHM_DELETE",$param,'delete',TRUE);
    }


    /**
     * [leadtime_get description]
     * @return [type] [description]
     */
    public function leadtime_get(){
        $param = array();
        
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
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
        $this->resultdata("SETUP_LEADTIME",$param);
    }
    
    /**
     * [leadtime_post description]
     * @return [type] [description]
     */
    public function leadtime_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"SETUP_LEADTIME");
        $param["AHM_TO_MD"]         = $this->post("ahm_to_md");
        $param["PROCESS_MD"]         = $this->post("process_md");
        $param["MD_TO_DEALER"]         = $this->post("md_to_dealer");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_SETUP_LEADTIME_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [leadtime_put description]
     * @return [type] [description]
     */
    public function leadtime_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["AHM_TO_MD"] = $this->put("ahm_to_md");
        $param["PROCESS_MD"]   = $this->put("process_md");
        $param["MD_TO_DEALER"]   = $this->put("md_to_dealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["ROW_STATUS"]   = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_LEADTIME_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [leadtime_delete description]
     * @return [type] [description]
     */
    public function leadtime_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_LEADTIME_DELETE",$param,'delete',TRUE);
    }


    /**
     * [part_terima_get description]
     * @return [type] [description]
     */
    public function part_terima_get(){
        $param = array();
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
         if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"    => $this->get("keyword")
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
        $this->resultdata("TRANS_PART_TERIMA",$param);
    }
    
    /**
     * [part_terima_post description]
     * @return [type] [description]
     */
    public function part_terima_post(){
        $param = array();
        $param["NO_TRANS"]         = $this->post("no_trans");
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_SURATJALAN"]         = $this->post("no_suratjalan");
        $this->Main_model->data_sudahada($param,"TRANS_PART_TERIMA");
        $param["TGL_TRANS"]     = tglToSql($this->post("tgl_trans"));
        $param["NO_PO"]         = $this->post("no_po");
        $param["NAMA_EXPEDISI"]         = strtoupper($this->post("nama_expedisi"));
        $param["NO_POLISI"]         = strtoupper($this->post("no_polisi"));
        $param["NAMA_SOPIR"]         = $this->post("nama_sopir");
        $param["STATUS_RCV"]         = $this->post("status_rcv");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_TRANS_PART_TERIMA_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_terima_put description]
     * @return [type] [description]
     */
    public function part_terima_put(){
        $param = array();
        $param["TGL_TRANS"]     = tglToSql($this->put("tgl_trans"));
        $param["NO_TRANS"] = $this->put("no_trans");
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["NO_SURATJALAN"]   = $this->put("no_suratjalan");
        $param["NO_PO"]   = $this->put("no_po");
        $param["NAMA_EXPEDISI"]   = strtoupper($this->put("nama_expedisi"));
        $param["NO_POLISI"]   = strtoupper($this->put("no_polisi"));
        $param["NAMA_SOPIR"]   = $this->put("nama_sopir");
        $param["STATUS_RCV"]   = $this->put("status_rcv");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_TERIMA_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_terima_delete description]
     * @return [type] [description]
     */
    public function part_terima_delete(){
        $param = array();
        $param["NO_TRANS"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_TERIMA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [part_terimadetail_get description]
     * @return [type] [description]
     */
    public function part_terimadetail_get(){
        $param = array();
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("kd_part")){
            $param["PART_NUMBER"]     = $this->get("kd_part");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NO_TRANS"    => $this->get("keyword")
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
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PART_TERIMADETAIL",$param);
    }
    
    /**
     * [part_terimadetail_post description]
     * @return [type] [description]
     */
    public function part_terimadetail_post(){
        $param = array();
        $param["NO_TRANS"]         = $this->post("no_trans");
        $param["PART_NUMBER"]         = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"TRANS_PART_TERIMADETAIL");
        $param["JUMLAH"]         = $this->post("jumlah");
        $param["JUMLAH_RFS"]   = $this->post("jumlah_rfs");
        $param["JUMLAH_NRFS"]   = $this->post("jumlah_nrfs");
        $param["STATUS_PART"]   = $this->post("status_part");
        $param["PART_SERIAL"]         = $this->post("part_serial");
        $param["PART_BARCODE"]         = $this->post("part_barcode");
        $param["PART_BATCH"]         = $this->post("part_batch");
        $param["HARGA_BELI"]         = $this->post("harga_beli");
        $param["PPN"]         = $this->post("ppn");
        $param["NETPRICE"]         = $this->post("netprice");
        $param["DISKON"]         = $this->post("diskon");
        $param["KD_TRANS"]         = $this->post("kd_trans");
        $param["KD_GUDANG"]         = $this->post("kd_gudang");
        $param["KD_RAKBIN"]         = $this->post("kd_rakbin");
        $param["KD_RAKBIN_NRFS"]         = $this->post("kd_rakbinNRFS");
        $param["CREATED_BY"]      = $this->post("created_by");
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["NO_SJMASUK"]    = $this->post('no_sjmasuk');
        $this->resultdata("SP_TRANS_PART_TERIMADETAIL_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_terimadetail_put description]
     * @return [type] [description]
     */
    public function part_terimadetail_put(){
        $param = array();
        //$param["ID"]         = $this->put("id");
        $param["NO_TRANS"] = $this->put("no_trans");
        $param["PART_NUMBER"]   = $this->put("part_number");
        $param["JUMLAH"]   = $this->put("jumlah");
        $param["JUMLAH_RFS"]   = $this->put("jumlah_rfs");
        $param["STATUS_PART"]   = $this->put("status_part");
        $param["PART_SERIAL"]   = $this->put("part_serial");
        $param["PART_BARCODE"]   = $this->put("part_barcode");
        $param["PART_BATCH"]   = $this->put("part_batch");
        $param["HARGA_BELI"]   = $this->put("harga_beli");
        $param["PPN"]         = $this->put("ppn");
        $param["NETPRICE"]         = $this->put("netprice");
        $param["DISKON"]         = $this->put("diskon");
        $param["KD_TRANS"]         = $this->put("kd_trans");
        $param["KD_GUDANG"]         = $this->put("kd_gudang");
        $param["KD_RAKBIN"]         = $this->put("kd_rakbin");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_TERIMADETAIL_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_terimadetail_delete description]
     * @return [type] [description]
     */
    public function part_terimadetail_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_TERIMADETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [part_sjmasuk_get description]
     * @return [type] [description]
     */
    public function part_sjmasuk_get(){
        $param = array();
        
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_sj")){
            $param["NO_SJ"]     = $this->get("no_sj");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"] = $this->get("part_number");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_MAINDEALER"    => $this->get("keyword"),
                "KD_DEALER"    => $this->get("keyword"),
                "NO_SJ"    => $this->get("keyword"),
                "PART_NUMBER"    => $this->get("keyword")
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
        $this->resultdata("TRANS_PART_SJMASUK",$param);
    }
    
    /**
     * [part_sjmasuk_post description]
     * @return [type] [description]
     */
    public function part_sjmasuk_post(){
        $param = array();
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["NO_SJ"]         = $this->post("no_sj");
        $this->Main_model->data_sudahada($param,"TRANS_PART_SJMASUK");
        $param["TGL_SJ"]         = tglToSql($this->post("tgl_sj"));
        $param["NO_PO"]         = $this->post("no_po");
        $param["JATUH_TEMPO"]         = tglToSql($this->post("jatuh_tempo"));
        $param["PART_NUMBER"]         = $this->post("part_number");
        $param["QTY"]         = $this->post("qty");
        $param["QTY_RCV"]         = $this->post("qty_rcv");
        $param["PRICE"]         = $this->post("price");
        $param["DISKON"]         = $this->post("diskon");
        $param["PPN"]         = $this->post("ppn");
        $param["NETPRICE"]         = $this->post("netprice");
        $param["KD_TRANS"]         = $this->post("kd_trans");
        $param["NO_REFF"]         = $this->post("no_reff");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_TRANS_PART_SJMASUK_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_sjmasuk_put description]
     * @return [type] [description]
     */
    public function part_sjmasuk_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["NO_SJ"]   = $this->put("no_sj");
        $param["TGL_SJ"]   = tglToSql($this->put("tgl_sj"));
        $param["NO_PO"]   = $this->put("no_po");
        $param["JATUH_TEMPO"]   = tglToSql($this->put("jatuh_tempo"));
        $param["PART_NUMBER"]   = $this->put("part_number");
        $param["QTY"]   = $this->put("qty");
        $param["QTY_RCV"]   = $this->put("qty_rcv");
        $param["PRICE"]   = $this->put("price");
        $param["DISKON"]   = $this->put("diskon");
        $param["PPN"]   = $this->put("ppn");
        $param["NETPRICE"]   = $this->put("netprice");
        $param["KD_TRANS"]   = $this->put("kd_trans");
        $param["NO_REFF"]   = $this->put("no_reff");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_SJMASUK_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_sjmasuk_delete description]
     * @return [type] [description]
     */
    public function part_sjmasuk_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_SJMASUK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [part_backup_get description]
     * @return [type] [description]
     */
    public function part_backup_get(){
        $param = array();
        if($this->get("id_part")){
            $param["ID_PART"]     = $this->get("id_part");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("part_deskripsi")){
            $param["PART_DESKRIPSI"]   = $this->get("part_deskripsi");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]    = $this->get("kd_dealer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(

                "PART_NUMBER"      => $this->get("keyword"),
                "PART_DESKRIPSI"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PART_BACKUP",$param);
    }



     /**
     * [hargabeli_md_backup_get description]
     * @return [type] [description]
     */
    public function hargabeli_md_backup_get(){
        $param = array();
        
        if($this->get("nama_unit")){
            $param["NAMA_UNIT"]     = $this->get("nama_unit");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "NAMA_UNIT"      => $this->get("keyword")
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
        $this->resultdata("MASTER_HARGABELI_KE_MD_BACKUP",$param);
    }

    /**
     * [part_hargajual_get description]
     * @return [type] [description]
     */
    public function part_hargajual_get(){
        $param = array();
        
        if($this->get("kategori")){
            $param["KATEGORI"]     = $this->get("kategori");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_typecustomer")){
            $param["KD_TYPECUSTOMER"]     = $this->get("kd_typecustomer");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                
                "KATEGORI"    => $this->get("keyword"),
                "PART_NUMBER"    => $this->get("keyword"),
                "KD_MAINDEALER"    => $this->get("keyword"),
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
        $this->resultdata("TRANS_PART_HARGAJUAL",$param);
    }
    
    /**
     * [part_hargajual_post description]
     * @return [type] [description]
     */
    public function part_hargajual_post(){
        $param = array();
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["KATEGORI"]         = $this->post("kategori");
        $param["PART_NUMBER"]         = $this->post("part_number");
        $param["KD_TYPECUSTOMER"]         = $this->post("kd_typecustomer");
        $param["START_DATE"]         = tglToSql($this->post("start_date"));
        $param["END_DATE"]         = tglToSql($this->post("end_date"));
        $this->Main_model->data_sudahada($param,"TRANS_PART_HARGAJUAL");
        $param["HARGA_BELI"]         = $this->post("harga_beli");
        $param["HARGA_JUAL"]         = $this->post("harga_jual");
        $param["DISKON"]         = $this->post("diskon");
        $param["DISKON_TYPE"]         = $this->post("diskon_type");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_TRANS_PART_HARGAJUAL_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_hargajual_put description]
     * @return [type] [description]
     */
    public function part_hargajual_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["KATEGORI"] = $this->put("kategori");
        $param["PART_NUMBER"]   = $this->put("part_number");
        $param["KD_TYPECUSTOMER"]   = $this->put("kd_typecustomer");
        $param["HARGA_BELI"]   = $this->put("harga_beli");
        $param["HARGA_JUAL"]   = $this->put("harga_jual");
        $param["DISKON"]   = $this->put("diskon");
        $param["START_DATE"]   = tglToSql($this->put("start_date"));
        $param["END_DATE"]   = tglToSql($this->put("end_date"));
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["DISKON_TYPE"]   = $this->put("diskon_type");
        $param["ROW_STATUS"]   = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_HARGAJUAL_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_hargajual_delete description]
     * @return [type] [description]
     */
    public function part_hargajual_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_HARGAJUAL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [part_hargajual_backup_get description]
     * @return [type] [description]
     */
    public function part_hargajual_backup_get(){
        $param = array();
        
        if($this->get("kategori")){
            $param["KATEGORI"]     = $this->get("kategori");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
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
                
                "KATEGORI"    => $this->get("keyword"),
                "PART_NUMBER"    => $this->get("keyword"),
                "KD_MAINDEALER"    => $this->get("keyword"),
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
        $this->resultdata("TRANS_PART_HARGAJUAL_BACKUP",$param);
    }

    /**
     * [part_picking_get description]
     * @return [type] [description]
     */
    public function part_picking_get(){
        $param = array();
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("nama_konsumen")){
            $param["NAMA_KONSUMEN"]     = $this->get("nama_konsumen");
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
                "NO_TRANS"    => $this->get("keyword"),
                "NAMA_KONSUMEN"    => $this->get("keyword"),
                "KD_MAINDEALER"    => $this->get("keyword"),
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
        $this->resultdata("TRANS_PART_PICKING",$param);
    }
    
    /**
     * [part_picking_post description]
     * @return [type] [description]
     */
    public function part_picking_post(){
        $param = array();
        $param["NO_TRANS"]         = $this->post("no_trans");
        $param["NO_REFF"]         = $this->post("no_reff");
        $this->Main_model->data_sudahada($param,"TRANS_PART_PICKING");
        $param["TGL_TRANS"]         = tglToSql($this->post("tgl_trans"));
        $param["NAMA_KONSUMEN"]         = $this->post("nama_konsumen");
        $param["KD_MAINDEALER"]         = $this->post("kd_maindealer");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $param["KD_LOKASIDEALER"]         = $this->post("kd_lokasidealer");
        $param["CREATED_BY"]      = $this->post("created_by");
 
        $this->resultdata("SP_TRANS_PART_PICKING_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_picking_put description]
     * @return [type] [description]
     */
    public function part_picking_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["NO_TRANS"] = $this->put("no_trans");
        $param["TGL_TRANS"]   = tglToSql($this->put("tgl_trans"));
        $param["NO_REFF"]   = $this->put("no_reff");
        $param["NAMA_KONSUMEN"]   = $this->put("nama_konsumen");
        $param["KD_MAINDEALER"]   = $this->put("kd_maindealer");
        $param["KD_DEALER"]   = $this->put("kd_dealer");
        $param["KD_LOKASIDEALER"]         = $this->put("kd_lokasidealer");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_PICKING_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_picking_delete description]
     * @return [type] [description]
     */
    public function part_picking_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_PICKING_DELETE",$param,'delete',TRUE);
    }

    /**
     * [part_picking_detail_get description]
     * @return [type] [description]
     */
    public function part_picking_detail_get(){
        $param = array();
        
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                
                "NO_TRANS"    => $this->get("keyword"),
                "PART_NUMBER"    => $this->get("keyword")
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
        $this->resultdata("TRANS_PART_PICKING_DETAIL",$param);
    }
    
    /**
     * [part_picking_detail_post description]
     * @return [type] [description]
     */
    public function part_picking_detail_post(){
        $param = array();
        $param["NO_TRANS"]         = $this->post("no_trans");
        $param["KD_RAKBIN"]         = $this->post("kd_rakbin");
        $param["PART_NUMBER"]         = $this->post("part_number");
        $this->Main_model->data_sudahada($param,"TRANS_PART_PICKING_DETAIL");
        $param["JUMLAH"]         = $this->post("jumlah");
        $param["PRICE"]         = $this->post("price");
        $param["HARGA_BELI"]   = $this->post("harga_beli");
        $param["HARGA_JUAL"]   = $this->post("harga_jual");
        $param["PART_BATCH"]   = $this->post("part_batch");
        $param["KD_GUDANG"]   = $this->post("kd_gudang");
        $param["CREATED_BY"]      = $this->post("created_by");

        $this->resultdata("SP_TRANS_PART_PICKING_DETAIL_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [part_picking_detail_put description]
     * @return [type] [description]
     */
    public function part_picking_detail_put(){
        $param = array();
        $param["ID"]         = $this->put("id");
        $param["NO_TRANS"] = $this->put("no_trans");
        $param["KD_RAKBIN"]   = $this->put("kd_rakbin");
        $param["PART_NUMBER"]   = $this->put("part_number");
        $param["JUMLAH"]         = $this->put("jumlah");
        $param["PRICE"]         = $this->put("price");
        $param["HARGA_BELI"]   = $this->put("harga_beli");
        $param["HARGA_JUAL"]   = $this->put("harga_jual");
        $param["PART_BATCH"]   = $this->put("part_batch");
        $param["KD_GUDANG"]   = $this->put("kd_gudang");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_PICKING_DETAIL_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [part_picking_detail_delete description]
     * @return [type] [description]
     */
    public function part_picking_detail_delete(){
        $param = array();
        $param["ID"]     = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_PICKING_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [ptm_get description]
     * @return [type] [description]
     */
    public function ptm_get(){
        $param = array();
        
        if($this->get("tipe_produksi")){
            $param["TIPE_PRODUKSI"]     = $this->get("tipe_produksi");
        }
        if($this->get("type_marketing")){
            $param["TYPE_MARKETING"]   = $this->get("type_marketing");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "TIPE_PRODUKSI"      => $this->get("keyword"),
                "TYPE_MARKETING"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PTM",$param);
    }

    public function ptmbatch_post(){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
        // $folderJson = "\\\\".getConfig("UPJSON")."\\tmp\ptm.json";
        $folderJson = getConfig("UPJSON")."\ptm.json";
        //$folderJson = "\\\\DBSERVER\\tmp\ptm.json";
        //chmod($folderJson, 0777);
        $handle=fopen($folderJson,'wb');
        fwrite($handle,$param);
        fclose($handle);
        $datax=$this->Custom_model->queryptm();
        unlink($folderJson);
        if($datax){
            $result["status"]   = FALSE;
            $result["message"]  = "Data gagal di simpan";
            $result["debug"]    = "";
            $result["param"]    = $this->db->last_query();
            $result["recordexists"]=FALSE;

        }else{
            $result["status"]   = TRUE;
            $result["message"]  = "Data berhasil di upload";
            $result["location"] =(isset($this->location))?$this->get_location():"";
            $result["param"]    = $datax;//$this->db->last_query();
            $result["recordexists"]=FALSE;
        }
        $this->response($result);
    }

    /**
     * Partnumber versus type motor
     * @return [type] [description]
     */
    public function partvsmotor_get(){
        $param = array();
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]    = $this->get("kd_typemotor");
        }
        if($this->get("part_deskripsi")){
            $param["PART_DESKRIPSI"]  = $this->get("part_deskripsi");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"      => $this->get("keyword"),
                "PART_DESKRIPSI"   => $this->get("keyword")
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

        $this->resultdata("PART_VS_TYPEMOTOR",$param);
    }

    /**
     * parteta_get
     * @return [type] [description]
     */
    public function parteta_get(){
        $param = array();
        
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("part_deskripsi")){
            $param["PART_DESKRIPSI"]   = $this->get("part_deskripsi");
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "PART_NUMBER"      => $this->get("keyword"),
                "PART_DESKRIPSI"    => $this->get("keyword")
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

        $this->resultdata("MASTER_PART",$param);
    }

    /**
     * [kpb_part_get description]
     * @return [type] [description]
     */
    public function kpb_part_get(){
        $param=array();$search="";
        if($this->get("no_mesin")){  $param["NO_MESIN"] = $this->get("no_mesin");}
        if($this->get("motor_kpb")){ $param["MOTOR_KPB"] = $this->get("motor_kpb");}
        if($this->get("no_part_oli_1a")){ $param["NO_PART_OLI_1A"] = $this->get("no_part_oli_1a");}
        if($this->get("no_part_oli_1b")){ $param["NO_PART_OLI_1B"] = $this->get("no_part_oli_1b");}
        if($this->get("isi_oli_1")){ $param["ISI_OLI_1"]  = $this->get("isi_oli_1");}
        if($this->get("harga_oli_1")){ $param["HARGA_OLI_1"] = $this->get("harga_oli_1");}
        if($this->get("no_part_oli_2a")){ $param["NO_PART_OLI_2A"]  = $this->get("no_part_oli_2a");}
        if($this->get("no_part_oli_2b")){ $param["NO_PART_OLI_2B"] = $this->get("no_part_oli_2b");}
        if($this->get("isi_oli_2")){ $param["ISI_OLI_2"]         = $this->get("isi_oli_2");}
        if($this->get("harga_oli_2")){ $param["HARGA_OLI_2"]       = $this->get("harga_oli_2");}
        if($this->get("nominal_jasa")){ $param["NOMINAL_JASA"]      = $this->get("nominal_jasa");}
        if($this->get("kpb_ke")){ $param["KPB_KE"]      = $this->get("kpb_ke");}
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_MESIN"  => $this->get("keyword"),
                "MOTOR_KPB"    => $this->get("keyword")
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
        $this->resultdata("MASTER_KPB_PART",$param);
    }
    
    /**
     * [kpb_part_post description]
     * @return [type] [description]
     */
    public function kpb_part_post(){
        $param = array();
        $param["NO_MESIN"]          = $this->post("no_mesin");
        $param["KD_KPB"]            = $this->post("kd_kpb");
        $this->Main_model->data_sudahada($param,"MASTER_KPB_PART");
        $param["KPB_KE"]            = $this->post("kpb_ke");
        $param["MOTOR_KPB"]         = $this->post("motor_kpb");
        $param["NO_PART_OLI_1A"]    = $this->post("no_partoli");
        $param["NO_PART_OLI_1B"]    = $this->post("no_partoli2");
        $param["ISI_OLI_1"]         = $this->post("isi_oli");
        $param["HARGA_OLI_1"]       = $this->post("harga_oli");
        $param["NO_PART_OLI_2A"]    = $this->post("nopart_oli1");
        $param["NO_PART_OLI_2B"]    = $this->post("nopart_oli2");
        $param["ISI_OLI_2"]         = $this->post("isi_oli2");
        $param["HARGA_OLI_2"]       = $this->post("harga_oli2");
        $param["NOMINAL_JASA"]      = $this->post("nominal_jasa");
        $param["KD_SEGMENT"]        = $this->post("kd_segment");
        $param["CREATED_BY"]        = $this->post("created_by");

        $this->resultdata("SP_MASTER_KPB_PART_INSERT",$param,'post',TRUE);
    }
    /**
     * [kpb_part_put description]
     * @return [type] [description]
     */
    public function kpb_part_put(){
        $param = array();
        $param["NO_MESIN"]          = $this->put("no_mesin");
        $param["KD_KPB"]            = $this->put("kd_kpb");
        $param["MOTOR_KPB"]         = $this->put("motor_kpb");
        $param["NO_PART_OLI_1A"]    = $this->put("no_partoli");
        $param["NO_PART_OLI_1B"]    = $this->put("no_partoli2");
        $param["ISI_OLI_1"]         = $this->put("isi_oli");
        $param["HARGA_OLI_1"]       = $this->put("harga_oli");
        $param["NO_PART_OLI_2A"]    = $this->put("nopart_oli1");
        $param["NO_PART_OLI_2B"]    = $this->put("nopart_oli2");
        $param["ISI_OLI_2"]         = $this->put("isi_oli2");
        $param["HARGA_OLI_2"]       = $this->put("harga_oli2");
        $param["NOMINAL_JASA"]      = $this->put("nominal_jasa");
        $param["KPB_KE"]            = $this->put("kpb_ke");
        $param["KD_SEGMENT"]        = $this->put("kd_segment");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_MASTER_KPB_PART_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [kpb_part_delete description]
     * @return [type] [description]
     */
    public function kpb_part_delete(){
        $param = array();
        $param["NO_MESIN"]          = $this->delete("no_mesin");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_KPB_PART_DELETE",$param,'delete',TRUE);
    }

    function kpb_part_oli_get($mode=0){
        $kd_dealer = $this->get("kd_dealer");
        $part_number = $this->get("part_number");
        $kd_typemotor = $this->get("kd_typemotor");
        $this->Main_model->set_custom_query($this->Custom_model->trans_pkb_oli($kd_dealer,$mode,$part_number,$kd_typemotor));
        $this->resultdata("TRANS_PKB",$param=array());
    }

}
?>