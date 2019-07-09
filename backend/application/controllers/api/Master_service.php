<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Master_service extends REST_Controller {

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
    * Get data typecomingcustomer
    * @access public
    */
    public function typecomingcustomer_get(){
        $param=array(); $result=array();
        if($this->get('kd_typecomingcustomer')){
            $param["KD_TYPECOMINGCUSTOMER"]  = $this->get('kd_typecomingcustomer');
        }
        if($this->get('nama_typecomingcustomer')){
            $param["NAMA_TYPECOMINGCUSTOMER"]  = $this->get('nama_typecomingcustomer');
        }
        
        $search="";
       if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_TYPECOMINGCUSTOMER" =>$this->get("keyword"),
                "NAMA_TYPECOMINGCUSTOMER"     =>$this->get("keyword")
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
        $this->resultdata("SETUP_TYPECOMINGCUSTOMER",$param);
        
    }
    /**
     * [typecomingcustomer_post description]
     * @return [type] [description]
     */
    public function typecomingcustomer_post(){
        
        $param = array();
        $param["KD_TYPECOMINGCUSTOMER"]      = $this->post('kd_typecomingcustomer');
        $this->Main_model->data_sudahada($param,"SETUP_TYPECOMINGCUSTOMER");
        $param["NAMA_TYPECOMINGCUSTOMER"]    = $this->post('nama_typecomingcustomer');
		
        
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_TYPECOMINGCUSTOMER_INSERT",$param,'post',TRUE);

    }
    /**
     * 
     * @return [type] [description]
     */
    public function typecomingcustomer_put(){
        /*
        * Applikasi ini tidak di ijinkan edit data motor
        */
        $param=array();
        $param["KD_TYPECOMINGCUSTOMER"]     = $this->put('kd_typecomingcustomer');
        $param["NAMA_TYPECOMINGCUSTOMER"]   = $this->put('nama_typecomingcustomer');
        $param["ROW_STATUS"]            = $this->put("row_status");
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");
		
        $this->resultdata("SP_SETUP_TYPECOMINGCUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * Delete type motor tidak dilakukan di applikasi ini
     * @return [type] [description]
     */
    public function typecomingcustomer_delete(){
       
        $param=array();
        $param["KD_TYPECOMINGCUSTOMER"] = $this->delete("kd_typecomingcustomer");
        $param["LASTMODIFIED_BY"]       = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_TYPECOMINGCUSTOMER_DELETE",$param,'delete',TRUE);
    }
   
    
    /**
     * [master_comingcustomer_get description]
     * @return [type] [description]
     */
    public function master_comingcustomer_get(){
        $param = array();$search='';
        
        if($this->get("nama_comingcustomer")){
            $param["NAMA_COMINGCUSTOMER"]     = $this->get("nama_comingcustomer");
        }
        if($this->get("no_ktp")){
            $param["NO_KTP"]     = $this->get("no_ktp");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_COMINGCUSTOMER" => $this->get("keyword"),
                "NO_KTP" => $this->get("keyword")
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
        $this->resultdata("MASTER_COMINGCUSTOMER",$param);
    }
    /**
     * [master_comingcustomer_post description]
     * @return [type] [description]
     */
    public function master_comingcustomer_post(){
        $param = array();
		
		$param["NO_KTP"]                    = $this->post('no_ktp');
		 $this->Main_model->data_sudahada($param,"MASTER_COMINGCUSTOMER");
        $param["KD_GENDER"]                 = $this->post('kd_gender');
        $param["KD_TYPECOMINGCUSTOMER"]     = $this->post('kd_typecomingcustomer');
        $param["NAMA_COMINGCUSTOMER"]       = $this->post('nama_comingcustomer');
        $param["NO_TELEPON"]                = $this->post('no_telepon');
        $param["ALAMAT_KTP"]                = $this->post('alamat_ktp');
        $param["ALAMAT_TERAKHIR"]           = $this->post('alamat_terakhir');
        $param["EMAIL"]                     = $this->post('email');
        $param["KD_PROPINSI"]               = $this->post('kd_propinsi');
        $param["KD_KABUPATEN"]              = $this->post('kd_kabupaten');
        $param["KD_KECAMATAN"]              = $this->post('kd_kecamatan');
        $param["KD_DESA"]                   = $this->post('kd_desa');
        $param["KODE_POS"]                  = $this->post('kode_pos');
        $param["CREATED_BY"]                = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_COMINGCUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [master_comingcustomer_put description]
     * @return [type] [description]
     */
    public function master_comingcustomer_put(){
        $param = array();
        $param["ID"]                    = $this->put('id');
        $param["KD_GENDER"]             = $this->put('kd_gender');
        $param["KD_TYPECOMINGCUSTOMER"] = $this->put('kd_typecomingcustomer');
        $param["NAMA_COMINGCUSTOMER"]   = $this->put('nama_comingcustomer');
        $param["NO_KTP"]                = $this->put('no_ktp');
        $param["NO_TELEPON"]            = $this->put('no_telepon');
        $param["ALAMAT_KTP"]            = $this->put('alamat_ktp');
        $param["ALAMAT_TERAKHIR"]       = $this->put('alamat_terakhir');
        $param["EMAIL"]                 = $this->put('email');
        $param["KD_PROPINSI"]           = $this->put('kd_propinsi');
        $param["KD_KABUPATEN"]          = $this->put('kd_kabupaten');
        $param["KD_KECAMATAN"]          = $this->put('kd_kecamatan');
        $param["KD_DESA"]               = $this->put('kd_desa');
        $param["KODE_POS"]              = $this->put('kode_pos');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]       = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_COMINGCUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [master_comingcustomer_delete description]
     * @return [type] [description]
     */
    public function master_comingcustomer_delete(){
        $param = array();
        $param["ID"]   = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_COMINGCUSTOMER_DELETE",$param,'delete',TRUE);
    }

    


    /**
     * [tipepkb_get description]
     * @return [type] [description]
     */
    public function tipepkb_get(){
        $param = array();$search='';
        if($this->get("kd_tipepkb")){
            $param["KD_TIPEPKB"]     = $this->get("kd_tipepkb");
        }
        if($this->get("nama_tipepkb")){
            $param["NAMA_TIPEPKB"]     = $this->get("nama_tipepkb");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_TIPEPKB" => $this->get("keyword"),
                "NAMA_TIPEPKB" => $this->get("keyword")
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
        $this->resultdata("SETUP_TIPEPKB",$param);
    }
    /**
     * [tipepkb_post description]
     * @return [type] [description]
     */
    public function tipepkb_post(){
        $param = array();

        $param["KD_TIPEPKB"]    = $this->post('kd_tipepkb');
		$this->Main_model->data_sudahada($param,"SETUP_TIPEPKB");
        $param["NAMA_TIPEPKB"]  = $this->post('nama_tipepkb');
        
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_TIPEPKB_INSERT",$param,'post',TRUE);
    }
    /**
     * [tipepkb_put description]
     * @return [type] [description]
     */
    public function tipepkb_put(){
        $param = array();
        $param["KD_TIPEPKB"]        = $this->put('kd_tipepkb');
        $param["NAMA_TIPEPKB"]      = $this->put('nama_tipepkb');
        $param["ROW_STATUS"]    = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_TIPEPKB_UPDATE",$param,'put',TRUE);
    }
    /**
     * [tipepkb_delete description]
     * @return [type] [description]
     */
    public function tipepkb_delete(){
        $param = array();
        $param["KD_TIPEPKB"]        = $this->delete('kd_tipepkb');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_TIPEPKB_DELETE",$param,'delete',TRUE);
    }


    /**
     * [statusservicecustomer_get description]
     * @return [type] [description]
     */
    public function statusservicecustomer_get(){
        $param = array();$search='';
        if($this->get("kd_statusservicecustomer")){
            $param["KD_STATUSSERVICECUSTOMER"]     = $this->get("kd_statusservicecustomer");
        }
        if($this->get("nama_statusservicecustomer")){
            $param["NAMA_STATUSSERVICECUSTOMER"]     = $this->get("nama_statusservicecustomer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_STATUSSERVICECUSTOMER" => $this->get("keyword"),
                "NAMA_STATUSSERVICECUSTOMER" => $this->get("keyword")
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
        $this->resultdata("SETUP_STATUSSERVICECUSTOMER",$param);
    }
    /**
     * [statusservicecustomer_post description]
     * @return [type] [description]
     */
    public function statusservicecustomer_post(){
        $param = array();

        $param["KD_STATUSSERVICECUSTOMER"]  = $this->post('kd_statusservicecustomer');
		$this->Main_model->data_sudahada($param,"SETUP_STATUSSERVICECUSTOMER");
        $param["NAMA_STATUSSERVICECUSTOMER"]= $this->post('nama_statusservicecustomer');
        
        $param["CREATED_BY"]                = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_STATUSSERVICECUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [statusservicecustomer_put description]
     * @return [type] [description]
     */
    public function statusservicecustomer_put(){
        $param = array();
        $param["KD_STATUSSERVICECUSTOMER"]      = $this->put('kd_statusservicecustomer');
        $param["NAMA_STATUSSERVICECUSTOMER"]    = $this->put('nama_statusservicecustomer');
        $param["ROW_STATUS"]                = $this->put("row_status");
        $param["LASTMODIFIED_BY"]               = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_STATUSSERVICECUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [statusservicecustomer_delete description]
     * @return [type] [description]
     */
    public function statusservicecustomer_delete(){
        $param = array();
        $param["KD_STATUSSERVICECUSTOMER"]  = $this->delete('kd_statusservicecustomer');
        $param["LASTMODIFIED_BY"]           = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_STATUSSERVICECUSTOMER_DELETE",$param,'delete',TRUE);
    }


    /**
     * [jenispit_get description]
     * @return [type] [description]
     */
    public function jenispit_get(){
        $param = array();$search='';
        if($this->get("kd_jenispit")){
            $param["KD_JENISPIT"]     = $this->get("kd_jenispit");
        }
        if($this->get("nama_jenispit")){
            $param["NAMA_JENISPIT"]     = $this->get("nama_jenispit");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_JENISPIT" => $this->get("keyword"),
                "NAMA_JENISPIT" => $this->get("keyword")
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
        $this->resultdata("SETUP_JENISPIT",$param);
    }
    /**
     * [jenispit_post description]
     * @return [type] [description]
     */
    public function jenispit_post(){
        $param = array();

        $param["KD_JENISPIT"]   = $this->post('kd_jenispit');
		$this->Main_model->data_sudahada($param,"SETUP_JENISPIT");
        $param["NAMA_JENISPIT"] = $this->post('nama_jenispit');
        
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_JENISPIT_INSERT",$param,'post',TRUE);
    }
    /**
     * [jenispit_put description]
     * @return [type] [description]
     */
    public function jenispit_put(){
        $param = array();
        $param["KD_JENISPIT"]       = $this->put('kd_jenispit');
        $param["NAMA_JENISPIT"]     = $this->put('nama_jenispit');
        $param["ROW_STATUS"]    = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_JENISPIT_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jenispit_delete description]
     * @return [type] [description]
     */
    public function jenispit_delete(){
        $param = array();
        $param["KD_JENISPIT"]       = $this->delete('kd_jenispit');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_JENISPIT_DELETE",$param,'delete',TRUE);
    }

    /**
     * [tipeservicemekanik_get description]
     * @return [type] [description]
     */
    public function tipeservicemekanik_get(){
        $param = array();$search='';
        if($this->get("kd_tipeservicemekanik")){
            $param["KD_TIPESERVICEMEKANIK"]     = $this->get("kd_tipeservicemekanik");
        }
        if($this->get("nama_tipeservicemekanik")){
            $param["NAMA_TIPESERVICEMEKANIK"]     = $this->get("nama_tipeservicemekanik");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_TIPESERVICEMEKANIK" => $this->get("keyword"),
                "NAMA_TIPESERVICEMEKANIK" => $this->get("keyword")
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
        $this->resultdata("SETUP_TIPESERVICEMEKANIK",$param);
    }
    /**
     * [tipeservicemekanik_post description]
     * @return [type] [description]
     */
    public function tipeservicemekanik_post(){
        $param = array();

        $param["KD_TIPESERVICEMEKANIK"]  = $this->post('kd_tipeservicemekanik');
        $this->Main_model->data_sudahada($param,"SETUP_TIPESERVICEMEKANIK");
        $param["NAMA_TIPESERVICEMEKANIK"]= $this->post('nama_tipeservicemekanik');
        
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_TIPESERVICEMEKANIK_INSERT",$param,'post',TRUE);
    }
    /**
     * [tipeservicemekanik_put description]
     * @return [type] [description]
     */
    public function tipeservicemekanik_put(){
        $param = array();
        $param["KD_TIPESERVICEMEKANIK"]     = $this->put('kd_tipeservicemekanik');
        $param["NAMA_TIPESERVICEMEKANIK"]   = $this->put('nama_tipeservicemekanik');
        $param["ROW_STATUS"]            = $this->put("row_status");
        $param["LASTMODIFIED_BY"]           = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_TIPESERVICEMEKANIK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [tipeservicemekanik_delete description]
     * @return [type] [description]
     */
    public function tipeservicemekanik_delete(){
        $param = array();
        $param["KD_TIPESERVICEMEKANIK"] = $this->delete('kd_tipeservicemekanik');
        $param["LASTMODIFIED_BY"]       = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_TIPESERVICEMEKANIK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [jasa_get description]
     * @return [type] [description]
     */
    public function jasa_get(){
        $param = array();$search='';
        if($this->get("kd_jasa")){
            $param["KD_JASA"]     = $this->get("kd_jasa");
        }
        if($this->get("kd_motor")){
            $param["KD_MOTOR"]     = $this->get("kd_motor");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_JASA" => $this->get("keyword"),
                "KD_MOTOR" => $this->get("keyword"),
                "KETERANGAN" => $this->get("keyword")
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
        $this->resultdata("MASTER_JASA",$param);
    }
    /**
     * [jasa_post description]
     * @return [type] [description]
     */
    public function jasa_post(){
        $param = array();
        $param["KD_JASA"]       = $this->post('kd_jasa');
        $param["KD_MOTOR"]      = $this->post('kd_motor');
        $this->Main_model->data_sudahada($param,"MASTER_JASA");
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["FRT"]           = $this->post('frt');
        $param["HARGA"]         = $this->post('harga');
        $param["KATEGORI"]      = $this->post('kategori');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_JASA_INSERT",$param,'post',TRUE);
    }

    public function sdmjbatch_post(){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));
        
        $folderJson = getConfig("UPJSON")."\sdmj.json";
        $handle=fopen($folderJson,'wb');
        fwrite($handle,$param);
        fclose($handle);
        $datax=$this->Custom_model->querysdmj();
        unlink($folder);
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
            $result["param"]    = "";//$this->db->last_query();
            $result["recordexists"]=FALSE;
        }
        unlink($folderJson);
        $this->response($result);
    }
    /**
     * [jasa_put description]
     * @return [type] [description]
     */
    public function jasa_put(){
        $param = array();

        $param["ID"]                = $this->put('id');
        $param["KD_JASA"]           = $this->put('kd_jasa');
        $param["KD_MOTOR"]          = $this->put('kd_motor');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["FRT"]               = $this->put('frt');
        $param["HARGA"]             = $this->put('harga');
        $param["KATEGORI"]          = $this->put('kategori');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_JASA_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jasa_delete description]
     * @return [type] [description]
     */
    public function jasa_delete(){
        $param = array();
        $param["ID"]           = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_JASA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pajakperusahaan_get description]
     * @return [type] [description]
     */
    public function pajakperusahaan_get(){
        $param = array();$search='';
        if($this->get("nama_perusahaan")){
            $param["NAMA_PERUSAHAAN"]     = $this->get("nama_perusahaan");
        }
        if($this->get("no_npwp")){
            $param["NO_NPWP"]     = $this->get("no_npwp");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NAMA_PERUSAHAAN" => $this->get("keyword"),
                "NO_NPWP" => $this->get("keyword")
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
        $this->resultdata("MASTER_PAJAKPERUSAHAAN",$param);
    }
    /**
     * [pajakperusahaan_post description]
     * @return [type] [description]
     */
    public function pajakperusahaan_post(){
        $param = array();

        $param["NAMA_PERUSAHAAN"]   = $this->post('nama_perusahaan');
        $param["NO_NPWP"]           = $this->post('no_npwp');
        $this->Main_model->data_sudahada($param,"MASTER_PAJAKPERUSAHAAN");
        $param["ALAMAT"]            = $this->post('alamat');
        $param["KD_PROPINSI"]           = $this->post('kd_propinsi');
        $param["NO_TELPPERUSAHAAN"] = $this->post('no_telpperusahaan');
        $param["KD_KABUPATEN"]           = $this->post('kd_kabupaten');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PAJAKPERUSAHAAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [pajakperusahaan_put description]
     * @return [type] [description]
     */
    public function pajakperusahaan_put(){
        $param = array();
        $param["ID"]   = $this->put('id');
        $param["NAMA_PERUSAHAAN"]   = $this->put('nama_perusahaan');
        $param["NO_NPWP"]           = $this->put('no_npwp');
        $param["ALAMAT"]            = $this->put('alamat');
        $param["KD_PROPINSI"]           = $this->put('kd_propinsi');
        $param["NO_TELPPERUSAHAAN"] = $this->put('no_telpperusahaan');
        $param["KD_KABUPATEN"]           = $this->put('kd_kabupaten');
        $param["ROW_STATUS"]    = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PAJAKPERUSAHAAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pajakperusahaan_delete description]
     * @return [type] [description]
     */
    public function pajakperusahaan_delete(){
        $param = array();
        $param["ID"]   = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PAJAKPERUSAHAAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [promoprogram_get description]
     * @return [type] [description]
     */
    public function promoprogram_get(){
        $param = array();$search='';
        if($this->get("kd_promo")){
            $param["KD_PROMO"]     = $this->get("kd_promo");
        }
        if($this->get("nama_program")){
            $param["NAMA_PROGRAM"]     = $this->get("nama_program");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_PROMO" => $this->get("keyword"),
                "NAMA_PROGRAM" => $this->get("keyword")
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
        $this->resultdata("MASTER_PROMOPROGRAM",$param);
    }
    /**
     * [promoprogram_post description]
     * @return [type] [description]
     */
    public function promoprogram_post(){
        $param = array();

        $param["KD_PROMO"]      = $this->post('kd_promo');
        $this->Main_model->data_sudahada($param,"MASTER_PROMOPROGRAM");
        $param["NAMA_PROGRAM"]  = $this->post('nama_program');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["START_DATE"]    = tglToSql($this->post('start_date'));
        $param["END_DATE"]      = tglToSql($this->post('end_date'));
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PROMOPROGRAM_INSERT",$param,'post',TRUE);
    }
    /**
     * [promoprogram_put description]
     * @return [type] [description]
     */
    public function promoprogram_put(){
        $param = array();
        $param["KD_PROMO"]          = $this->put('kd_promo');
        $param["NAMA_PROGRAM"]      = $this->put('nama_program');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["START_DATE"]        = tglToSql($this->put('start_date'));
        $param["END_DATE"]          = tglToSql($this->put('end_date'));
        $param["KD_DEALER"]        = $this->put('kd_dealer');
        $param["ROW_STATUS"]    = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PROMOPROGRAM_UPDATE",$param,'put',TRUE);
    }
    /**
     * [promoprogram_delete description]
     * @return [type] [description]
     */
    public function promoprogram_delete(){
        $param = array();
        $param["KD_PROMO"]          = $this->delete('kd_promo');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PROMOPROGRAM_DELETE",$param,'delete',TRUE);
    }


    /**
     * [kpb_get description]
     * @return [type] [description]
     */
    public function kpb_get(){
        $param = array();$search='';
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("tipe_motor")){
            $param["TIPE_MOTOR"]     = $this->get("tipe_motor");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_MESIN" => $this->get("keyword"),
                "TIPE_MOTOR" => $this->get("keyword")
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
        $this->resultdata("MASTER_KPB_BY_TIPEMOTOR",$param);
    }
    /**
     * [kpb_post description]
     * @return [type] [description]
     */
    public function kpb_post(){
        $param = array();

        $param["NO_MESIN"]      = $this->post('no_mesin');
        $param["TIPE_MOTOR"]  = $this->post('tipe_motor');
        $this->Main_model->data_sudahada($param,"MASTER_KPB_BY_TIPEMOTOR");
        $param["PREMIUM"]    = $this->post('premium');
        $param["BKM1"]  = $this->post('bkm1');
        $param["BKM2"]  = $this->post('bkm2');
        $param["BKM3"]  = $this->post('bkm3');
        $param["BKM4"]  = $this->post('bkm4');
        $param["BSE1"]  = $this->post('bse1');
        $param["BSE2"]  = $this->post('bse2');
        $param["BSE3"]  = $this->post('bse3');
        $param["BSE4"]  = $this->post('bse4');
        $param["BCL1"]  = $this->post('bcl1');
        $param["BCL2"]  = $this->post('bcl2');
        $param["BCL3"]  = $this->post('bcl3');
        $param["BCL4"]  = $this->post('bcl4');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_KPB_BY_TIPEMOTOR_INSERT",$param,'post',TRUE);
    }
    /**
     * [kpb_put description]
     * @return [type] [description]
     */
    public function kpb_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["TIPE_MOTOR"]      = $this->put('tipe_motor');
        $param["PREMIUM"]        = $this->put('premium');
        $param["BKM1"]        = $this->put('bkm1');
        $param["BKM2"]        = $this->put('bkm2');
        $param["BKM3"]        = $this->put('bkm3');
        $param["BKM4"]        = $this->put('bkm4');
        $param["BSE1"]        = $this->put('bse1');
        $param["BSE2"]        = $this->put('bse2');
        $param["BSE3"]        = $this->put('bse3');
        $param["BSE4"]        = $this->put('bse4');
        $param["BCL1"]        = $this->put('bcl1');
        $param["BCL2"]        = $this->put('bcl2');
        $param["BCL3"]        = $this->put('bcl3');
        $param["BCL4"]        = $this->put('bcl4');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KPB_BY_TIPEMOTOR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kpb_delete description]
     * @return [type] [description]
     */
    public function kpb_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_KPB_BY_TIPEMOTOR_DELETE",$param,'delete',TRUE);
    }

    /**
     * [promo_program_detail_get description]
     * @return [type] [description]
     */
    public function promo_program_detail_get(){
        $param = array();$search='';
        if($this->get("kd_detailpromo")){
            $param["KD_DETAILPROMO"]     = $this->get("kd_detailpromo");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DETAILPROMO" => $this->get("keyword")
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
        $this->resultdata("MASTER_PROMO_PROGRAM_DETAIL",$param);
    }
    /**
     * [promo_program_detail_post description]
     * @return [type] [description]
     */
    public function promo_program_detail_post(){
        $param = array();

        $param["KD_DETAILPROMO"]      = $this->post('kd_detailpromo');
        $param["KD_PEKERJAAN"]  = $this->post('kd_pekerjaan');
        $this->Main_model->data_sudahada($param,"MASTER_PROMO_PROGRAM_DETAIL");
        $param["HARGA"]    = $this->post('harga');
        $param["NO_PART"]    = $this->post('no_part');
        $param["HARGA_NO_PART"]  = $this->post('harga_no_part');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PROMO_PROGRAM_DETAIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [promo_program_detail_put description]
     * @return [type] [description]
     */
    public function promo_program_detail_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_DETAILPROMO"]          = $this->put('kd_detailpromo');
        $param["KD_PEKERJAAN"]      = $this->put('kd_pekerjaan');
        $param["HARGA"]        = $this->put('harga');
        $param["NO_PART"]        = $this->put('no_part');
        $param["HARGA_NO_PART"]        = $this->put('harga_no_part');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PROMO_PROGRAM_DETAIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [promo_program_detail_delete description]
     * @return [type] [description]
     */
    public function promo_program_detail_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PROMO_PROGRAM_DETAIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [mekanik_get description]
     * @return [type] [description]
     */
    public function mekanik_get(){
        $param = array();$search='';
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NIK" => $this->get("keyword"),
                "NAMA_MEKANIK" =>$this->get("keyword"),
                );
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer");
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
        $this->resultdata("MASTER_MEKANIK_VIEW",$param);
    }
    /**
     * [mekanik_post description]
     * @return [type] [description]
     */
    public function mekanik_post(){
        $param = array();
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $param["NIK"]      = $this->post('nik');
        $this->Main_model->data_sudahada($param,"MASTER_MEKANIK");
        $param["HONDA_ID"]  = $this->post('honda_id');
        $param["TIPE_PKB"]    = $this->post('tipe_pkb');
        
        $param["NAMA_MEKANIK"]    = $this->post('nama_mekanik');
        $param["CREATED_BY"]    = $this->post('created_by');

       //mekanik harus di tambahkan dari master karyawan
        $this->resultdata("SP_MASTER_MEKANIK_INSERT",$param,'post',TRUE);
    }
    /**
     * [mekanik_put description]
     * @return [type] [description]
     */
    public function mekanik_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["NIK"]          = $this->put('nik');
        $param["HONDA_ID"]      = $this->put('honda_id');
        $param["TIPE_PKB"]        = $this->put('tipe_pkb');
        $param["KD_DEALER"]        = $this->put('kd_dealer');
        $param["KD_MAINDEALER"]        = $this->put('kd_maindealer');
        $param["NAMA_MEKANIK"]        = $this->put('nama_mekanik');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MEKANIK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [mekanik_delete description]
     * @return [type] [description]
     */
    public function mekanik_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_MEKANIK_DELETE",$param,'delete',TRUE);
    }

    /**
     * [status_absensi_get description]
     * @return [type] [description]
     */
    public function status_absensi_get(){
        $param = array();
        
        if($this->get("kd_statusabsensi")){
            $param["KD_STATUSABSENSI"]     = $this->get("kd_statusabsensi");
        }

        if($this->get("nama_statusabsensi")){
            $param["NAMA_STATUSABSENSI"]     = $this->get("nama_statusabsensi");
        }

        $search="";
        if($this->get("keyword")){
            $param=array();
            $search=array(
                "KD_STATUSABSENSI"      => $this->get("keyword"),
                "NAMA_STATUSABSENSI"  => $this->get("keyword")
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
        $this->resultdata("MASTER_STATUS_ABSENSI",$param);
    }

    /**
     * [jasa_vs_typemotor_get description]
     * @return [type] [description]
     */
    public function jasa_vs_typemotor_get(){
        $param = array();$search='';
        if($this->get("kd_jasa")){
            $param["KD_JASA"]     = $this->get("kd_jasa");
        }
        if($this->get("kd_motor")){
            $param["KD_MOTOR"]     = $this->get("kd_motor");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
        }
        if($this->get("keyword")){
            //$param=array();
            $search= array(
                "KD_JASA" => $this->get("keyword"),
                "KETERANGAN" => $this->get("keyword"),
                "KD_TYPEMOTOR" => $this->get("keyword")
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
        $this->resultdata("JASA_VS_TYPEMOTOR",$param);
    }


    /**
     * [kpb motor]
     * @return [type] [description]
     */
    public function kpb_motor_get(){
        $param = array();$search='';
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("jenis_kpb")){
            $param["JENIS_KPB"]     = $this->get("jenis_kpb");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_RANGKA" => $this->get("keyword"),
                "JENIS_KPB" => $this->get("keyword"),
                "BULAN_SERVICE" => $this->get("keyword"),
                "STATUS_SERVICE" => $this->get("keyword"),
                "STATUS_DEALER" => $this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword"),
                "BULAN_SERVICE" =>$this->get("keyword"),
                );
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer");
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
        $this->resultdata("TRANS_KPB_MOTOR_VIEW",$param);    
    }

    public function kpb_motor_reminder_get(){
        $param = array();$search='';
        if($this->get("no_rangka")){
            $param["NO_RANGKA"]     = $this->get("no_rangka");
        }
        if($this->get("jenis_kpb")){
            $param["JENIS_KPB"]     = $this->get("jenis_kpb");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_RANGKA" => $this->get("keyword"),
                "JENIS_KPB" => $this->get("keyword"),
                "BULAN_SERVICE" => $this->get("keyword"),
                "STATUS_SERVICE" => $this->get("keyword"),
                "STATUS_DEALER" => $this->get("keyword"),
                "NAMA_CUSTOMER" =>$this->get("keyword"),
                "BULAN_SERVICE" =>$this->get("keyword"),
                );
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"] = $this->get("kd_dealer");
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
        $this->resultdata("TRANS_DATA_REMINDER_VIEW",$param);
    }

    /**
     * [history_unit]
     * @return [type] [description]
     */
    public function history_unit_get(){
        $param = array();$search='';
        if($this->get("no_so")){
            $param["NO_SO"]     = $this->get("no_so");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_SO" => $this->get("keyword"),
                "NAMA_CUSTOMER" => $this->get("keyword")
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
        $this->resultdata("TRANS_HISTORY_UNIT",$param);
    }

}
?>