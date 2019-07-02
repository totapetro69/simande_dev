<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Setup extends REST_Controller {

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
     * [jeniscustomer_get description]
     * @return [type] [description]
     */
    public function jeniscustomer_get(){
        $param = array();$search='';
        if($this->get("kd_jeniscustomer")){
            $param["KD_JENISCUSTOMER"]      = $this->get("kd_jeniscustomer");
        }
        if($this->get("nama_jeniscustomer")){
            $param["NAMA_JENISCUSTOMER"]     = $this->get("nama_jeniscustomer");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_JENISCUSTOMER"     => $this->get("keyword"),
                "NAMA_JENISCUSTOMER"   => $this->get("keyword")
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
        $this->resultdata("SETUP_JENISCUSTOMER",$param);
    }

    /**
     * [jeniscustomer_post description]
     * @return [type] [description]
     */
    public function jeniscustomer_post(){
        $param = array();
        $param["KD_JENISCUSTOMER"] = $this->post('kd_jeniscustomer');
        $this->Main_model->data_sudahada($param,"SETUP_JENISCUSTOMER");
        $param["NAMA_JENISCUSTOMER"]   = $this->post('nama_jeniscustomer');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_SETUP_JENISCUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [jeniscustomer_put description]
     * @return [type] [description]
     */
    public function jeniscustomer_put(){
        $param = array();
        $param["KD_JENISCUSTOMER"]   = $this->put('kd_jeniscustomer');
        $param["NAMA_JENISCUSTOMER"] = $this->put('nama_jeniscustomer');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_JENISCUSTOMER_UPDATE",$param,'put',TRUE);
    }

    /**
     * [jeniscustomer_delete description]
     * @return [type] [description]
     */
    public function jeniscustomer_delete(){
        $param = array();
        $param["KD_JENISCUSTOMER"]     = $this->delete('kd_jeniscustomer');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_JENISCUSTOMER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [typecustomer_get description]
     * @return [type] [description]
     */
    public function typecustomer_get(){
        $param = array();$search='';
        if($this->get("kd_typecustomer")){
            $param["KD_TYPECUSTOMER"]      = $this->get("kd_typecustomer");
        }
        if($this->get("nama_typecustomer")){
            $param["NAMA_TYPECUSTOMER"]     = $this->get("nama_typecustomer");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_TYPECUSTOMER"     => $this->get("keyword"),
                "NAMA_TYPECUSTOMER"   => $this->get("keyword")
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
        $this->resultdata("SETUP_TYPECUSTOMER",$param);
    }

    /**
     * [typecustomer_post description]
     * @return [type] [description]
     */
    public function typecustomer_post(){
        $param = array();
        $param["KD_TYPECUSTOMER"] = $this->post('kd_typecustomer');
        $this->Main_model->data_sudahada($param,"SETUP_TYPECUSTOMER");
        $param["NAMA_TYPECUSTOMER"]   = $this->post('nama_typecustomer');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_SETUP_TYPECUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [typecustomer_put description]
     * @return [type] [description]
     */
    public function typecustomer_put(){
        $param = array();
        $param["KD_TYPECUSTOMER"]   = $this->put('kd_typecustomer');
        $param["NAMA_TYPECUSTOMER"] = $this->put('nama_typecustomer');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_TYPECUSTOMER_UPDATE",$param,'put',TRUE);
    }

    /**
     * [typecustomer_delete description]
     * @return [type] [description]
     */
    public function typecustomer_delete(){
        $param = array();
        $param["KD_TYPECUSTOMER"]     = $this->delete('kd_typecustomer');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_TYPECUSTOMER_DELETE",$param,'delete',TRUE);
    }
    
    /**
     * [jenispembayaran_get description]
     * @return [type] [description]
     */
    public function jenispembayaran_get(){
        $param = array();$search='';
        if($this->get("kd_jenispembayaran")){
            $param["KD_JENISPEMBAYARAN"]      = $this->get("kd_jenispembayaran");
        }
        if($this->get("nama_jenispembayaran")){
            $param["NAMA_JENISPEMBAYARAN"]     = $this->get("nama_jenispembayaran");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_JENISPEMBAYARAN"     => $this->get("keyword"),
                "NAMA_JENISPEMBAYARAN"   => $this->get("keyword")
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
        $this->resultdata("SETUP_JENISPEMBAYARAN",$param);
    }
    /**
     * [jenispembayaran_post description]
     * @return [type] [description]
     */
    public function jenispembayaran_post(){
        $param = array();
        $param["KD_JENISPEMBAYARAN"] = $this->post('kd_jenispembayaran');
        $this->Main_model->data_sudahada($param,"SETUP_JENISPEMBAYARAN");
        $param["NAMA_JENISPEMBAYARAN"]   = $this->post('nama_jenispembayaran');
        $param["KD_PEMBAYAR"]   = $this->post('kd_pembayar');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_SETUP_JENISPEMBAYARAN_INSERT",$param,'post',TRUE);
    }

    /**
     * [jenispembayaran_put description]
     * @return [type] [description]
     */
    public function jenispembayaran_put(){
        $param = array();
        $param["KD_JENISPEMBAYARAN"]   = $this->put('kd_jenispembayaran');
        $param["NAMA_JENISPEMBAYARAN"] = $this->put('nama_jenispembayaran');
        $param["KD_PEMBAYAR"]   = $this->put('kd_pembayar');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put('lastmodified_by');

        $this->resultdata("SP_SETUP_JENISPEMBAYARAN_UPDATE",$param,'put',TRUE);
    }

    /**
     * [jenispembayaran_delete description]
     * @return [type] [description]
     */
    public function jenispembayaran_delete(){
        $param = array();
        $param["KD_JENISPEMBAYARAN"]     = $this->delete('kd_jenispembayaran');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_JENISPEMBAYARAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [jenispergerakan_get description]
     * @return [type] [description]
     */
    public function jenispergerakan_get(){
        $param = array();$search='';
        if($this->get("kd_jenispergerakan")){
            $param["KD_JENISPERGERAKAN"]      = $this->get("kd_jenispergerakan");
        }
        if($this->get("nama_jenispergerakan")){
            $param["NAMA_JENISPERGERAKAN"]     = $this->get("nama_jenispergerakan");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_JENISPERGERAKAN"     => $this->get("keyword"),
                "NAMA_JENISPERGERAKAN"   => $this->get("keyword")
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
        $this->resultdata("SETUP_JENISPERGERAKAN",$param);
    }
    /**
     * [jenispergerakan_post description]
     * @return [type] [description]
     */
    public function jenispergerakan_post(){
        $param = array();
        $param["KD_JENISPERGERAKAN"] = $this->post('kd_jenispergerakan');
        $this->Main_model->data_sudahada($param,"SETUP_JENISPERGERAKAN");
        $param["NAMA_JENISPERGERAKAN"]   = $this->post('nama_jenispergerakan');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_SETUP_JENISPERGERAKAN_INSERT",$param,'post',TRUE);
    }

    /**
     * [jenispergerakan_put description]
     * @return [type] [description]
     */
    public function jenispergerakan_put(){
        $param = array();
        $param["KD_JENISPERGERAKAN"]   = $this->put('kd_jenispergerakan');
        $param["NAMA_JENISPERGERAKAN"] = $this->put('nama_jenispergerakan');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_JENISPERGERAKAN_UPDATE",$param,'put',TRUE);
    }

    /**
     * [jenispergerakan_delete description]
     * @return [type] [description]
     */
    public function jenispergerakan_delete(){
        $param = array();
        $param["KD_JENISPERGERAKAN"]     = $this->delete('kd_jenispergerakan');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_JENISPERGERAKAN_DELETE",$param,'delete',TRUE);
    }


    /**
     * [jenisreceiving_get description]
     * @return [type] [description]
     */
    public function jenisreceiving_get(){
        $param = array();$search='';
        if($this->get("kd_jenisreceiving")){
            $param["KD_JENISRECEIVING"]      = $this->get("kd_jenisreceiving");
        }
        if($this->get("nama_jenisreceiving")){
            $param["NAMA_JENISRECEIVING"]     = $this->get("nama_jenisreceiving");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_JENISRECEIVING"     => $this->get("keyword"),
                "NAMA_JENISRECEIVING"   => $this->get("keyword")
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
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_JENISRECEIVING",$param);
    }
    /**
     * [jenisreceiving_post description]
     * @return [type] [description]
     */
    public function jenisreceiving_post(){
        $param = array();
        $param["KD_JENISRECEIVING"] = $this->post('kd_jenisreceiving');
        $this->Main_model->data_sudahada($param,"SETUP_JENISRECEIVING");
        $param["NAMA_JENISRECEIVING"]   = $this->post('nama_jenisreceiving');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_SETUP_JENISRECEIVING_INSERT",$param,'post',TRUE);
    }

    /**
     * [jenisreceiving_put description]
     * @return [type] [description]
     */
    public function jenisreceiving_put(){
        $param = array();
        $param["KD_JENISRECEIVING"]   = $this->put('kd_jenisreceiving');
        $param["NAMA_JENISRECEIVING"] = $this->put('nama_jenisreceiving');
		$param["ROW_STATUS"]     = $this->put('row_status');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_JENISRECEIVING_UPDATE",$param,'put',TRUE);
    }

    /**
     * [jenisreciving_delete description]
     * @return [type] [description]
     */
    public function jenisreceiving_delete(){
        $param = array();
        $param["KD_JENISRECEIVING"]     = $this->delete('kd_jenisreceiving');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_JENISRECEIVING_DELETE",$param,'delete',TRUE);
    }

    /**
     * [modul_get description]
     * @return [type] [description]
     */
    public function modul_get(){
        $param = array();$search='';
        if($this->get("kd_modul")){
            $param["KD_MODUL"]      = $this->get("kd_modul");
        }
        if($this->get("urutan_modul")){
            $param["URUTAN_MODUL"]      = $this->get("urutan_modul");
        }
        if($this->get("nama_modul")){
            $param["NAMA_MODUL"]     = $this->get("nama_modul");
        }
        if($this->get("icon_modul")){
            $param["ICON_MODUL"]      = $this->get("icon_modul");
        }
        if($this->get("parent_modul")){
            $param["PARENT_MODUL"]      = $this->get("parent_modul");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_MODUL"     => $this->get("keyword"),
                "URUTAN_MODUL"  => $this->get("keyword"),
                "NAMA_MODUL"   => $this->get("keyword"),
                "ICON_MODUL"    => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if($this->get("orderby")){
            $this->Main_model->set_orderby($this->get("orderby"));    
        }else{
            $this->Main_model->set_orderby("PARENT_MODUL,URUTAN_MODUL,NAMA_MODUL");
        }
        
        
        $this->Main_model->set_statusdata($this->get('row_status'));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        
        $this->resultdata("MASTER_MODUL",$param);
    }
    /**
     * [modul_post description]
     * @return [type] [description]
     */
    public function modul_post(){
        $param = array();
        $param["KD_MODUL"] = $this->post('kd_modul');
        $this->Main_model->data_sudahada($param,"MASTER_MODUL");
        $param["URUTAN_MODUL"]  = $this->post('urutan_modul');
        $param["NAMA_MODUL"]   = $this->post('nama_modul');
        $param["ICON_MODUL"]    = $this->post('icon_modul');
        $param["PARENT_MODUL"]  = $this->post('parent_modul');
        $param["LINK_MODUL"]    = $this->post('link_modul');
        $param["PARENT_STATUS"]    = $this->post('parent_status');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_MASTER_MODUL_INSERT",$param,'post',TRUE);
    }
    /**
     * [modul_put description]
     * @return [type] [description]
     */
    public function modul_put(){
        $param = array();
        $param["KD_MODUL"]   = $this->put('kd_modul');
        $param["URUTAN_MODUL"]  = $this->put('urutan_modul');
        $param["NAMA_MODUL"] = $this->put('nama_modul');
        $param["ICON_MODUL"]    = $this->put('icon_modul');
        $param["PARENT_MODUL"]  = $this->put('parent_modul');
        $param["LINK_MODUL"]    = $this->put('link_modul');
        $param["PARENT_STATUS"]    = $this->put('parent_status');
        $param["ROW_STATUS"]    = $this->put('row_status');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MODUL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [modul_delete description]
     * @return [type] [description]
     */
    public function modul_delete(){
        $param = array();
        $param["KD_MODUL"]     = $this->delete('kd_modul');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_MODUL_DELETE",$param,'delete',TRUE);

    }


    /**
     * [setup_docno description]
     * @return [type] [description]
     */
    public function setup_docno_get(){
        $param = array();$search='';
        if($this->get("kd_docno")){
            $param["KD_DOCNO"]      = $this->get("kd_docno");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_docno")){
            $param["NAMA_DOCNO"]     = $this->get("nama_docno");
        }
        if($this->get("tahun_docno")){
            $param["TAHUN_DOCNO"]   = $this->get("tahun_docno");
        }
        if ($this->get("bulan_docno")) {
            $param["BULAN_DOCNO"]   = $this->get("bulan_docno");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_DOCNO"     => $this->get("keyword"),
                "NAMA_DOCNO"   => $this->get("keyword"),
                "TAHUN_DOCNO"   => $this->get("keyword"),
                "BULAN_DOCNO"   => $this->get("keyword")
                );
        }
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_DOCNO",$param);
    }
    /**
     * [setup_docno_post description]
     * @return [type] [description]
     */
    public function setup_docno_post(){
        $param = array();
        $param["KD_DOCNO"] = $this->post('kd_docno');
		$param["NAMA_DOCNO"]   = $this->post('nama_docno');
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $param["TAHUN_DOCNO"]    = $this->post('tahun_docno');
		$this->Main_model->data_sudahada($param,"SETUP_DOCNO");
        $param["BULAN_DOCNO"]   = $this->post('bulan_docno');
        $param["URUTAN_DOCNO"]  = $this->post('urutan_docno');
        $param["RESET_DOCNO"]   = $this->post('reset_docno');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->resultdata("SP_SETUP_DOCNO_INSERT",$param,'post',TRUE);
    }
    /**
     * [setup_docno_put description]
     * @return [type] [description]
     */
    public function setup_docno_put(){
        $param = array();
        $param["KD_DOCNO"] = $this->put('kd_docno');
        $param["NAMA_DOCNO"]   = $this->put('nama_docno');
        $param["KD_DEALER"]  = $this->put('kd_dealer');
        $param["TAHUN_DOCNO"]    = $this->put('tahun_docno');
        $param["BULAN_DOCNO"]   = $this->put('bulan_docno');
        $param["URUTAN_DOCNO"]  = $this->put('urutan_docno');
        $param["RESET_DOCNO"]   = $this->put('reset_docno');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_DOCNO_UPDATE",$param,'put',TRUE);
    }

    public function docno_get(){
        $param=null;
        if($this->get("kd_docno")){
            $param["KD_DOCNO"]      = $this->get("kd_docno");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("tahun_docno")){
            $param["TAHUN_DOCNO"]   = $this->get("tahun_docno");
        }
        if($this->get("bulan_docno")){
            $param["BULAN_DOCNO"]   = $this->get("bulan_docno");
        }
        if($this->get("reset")){
            $param["RESET_DOCNO"] =$this->get("reset");
        }
        $this->Main_model->set_parameter($param);
        $this->Main_model->tabel_name("SETUP_DOCNO");
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));

        $data=($this->Main_model->show_list_array());
        $return=0;
        if($data){
            //$result=(count($data)>0)?$data->result_array():array(array('URUTAN_DOCNO'=>0));
            $return=$data[0]["URUTAN_DOCNO"];
        }
        $this->response($return);
    }

    public function docno_put(){
        $param = array();
        $param["KD_DOCNO"]      = $this->put('kd_docno');
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["TAHUN_DOCNO"]   = $this->put('tahun_docno');
        $param["URUTAN_DOCNO"]  = $this->put('urutan_docno');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_DOCNO_UPDATE_URUTAN",$param,'put',TRUE);
    }

    //
    /**
    *MASTER JENISORDER
    */
    public function jenisorder_get(){
        $param = array();$search='';
        if($this->get("kd_jenisorder")){
            $param["KD_JENISORDER"]     = $this->get("kd_jenisorder");
        }
        if($this->get("nama_jenisorder")){
            $param["NAMA_JENISORDER"]     = $this->get("nama_jenisorder");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_JENISORDER" => $this->get("keyword"),
                "NAMA_JENISORDER" => $this->get("keyword")
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
        $this->resultdata("MASTER_JENISORDER",$param);
    }
    /**
     * [jenisorder_post description]
     * @return [type] [description]
     */
    public function jenisorder_post(){
        $param = array();
        $param["KD_JENISORDER"] = $this->post('kd_jenisorder');
        $this->Main_model->data_sudahada($param,"MASTER_JENISORDER");
        $param["NAMA_JENISORDER"]   = $this->post('nama_jenisorder');
        $param["TYPE_DOCUMENT"] = $this->post('type_document');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_JENISORDER_INSERT",$param,'post',TRUE);
    }
    /**
     * [jenisorder_put description]
     * @return [type] [description]
     */
    public function jenisorder_put(){
        $param = array();
        $param["KD_JENISORDER"] = $this->put('kd_jenisorder');
        $param["NAMA_JENISORDER"]   = $this->put('nama_jenisorder');
        $param["TYPE_DOCUMENT"] = $this->put('type_document');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_JENISORDER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jenisorder_delete description]
     * @return [type] [description]
     */
    public function jenisorder_delete(){
        $param = array();
        $param["KD_JENISORDER"]     = $this->delete('kd_jenisorder');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_JENISORDER_DELETE",$param,'delete',TRUE);
    }
    
    //
    /**
    *SETUP DISKON
    */
    public function diskon_get(){
        $param = array();$search='';
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get('type_customer')){
            $param["KD_JENISCUSTOMER"]     = $this->get("type_customer");
        }
        if($this->get("kd_diskon")){
            $param["KD_DISKON"]     = $this->get("kd_diskon");
        }
        if($this->get("nama_diskon")){
            $param["NAMA_DISKON"]     = $this->get("nama_diskon");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_DISKON" => $this->get("keyword"),
                "NAMA_DISKON" => $this->get("keyword")
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
        $this->resultdata("SETUP_DISKON",$param);
    }
    /**
     * [diskon_post description]
     * @return [type] [description]
     */
    public function diskon_post(){
        $param = array();
        $param["KD_DISKON"] = $this->post('kd_diskon');
        $param["KD_DEALER"] =  $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"SETUP_DISKON");
        $param["NAMA_DISKON"]   = $this->post('nama_diskon');
        $param["TIPE_DISKON"] = $this->post('tipe_diskon');
        $param["START_DATE"] =  tglToSql($this->post('start_date'));
        $param["END_DATE"] =  tglToSql($this->post('end_date'));
        $param["NIK"] =  $this->post('nik');
        $param["AMOUNT"] =  $this->post('amount');
        $param["KD_JENISCUSTOMER"] =  $this->post('kd_jeniscustomer');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_DISKON_INSERT",$param,'post',TRUE);
    }
    /**
     * [diskon_put description]
     * @return [type] [description]
     */
    public function diskon_put(){
        $param = array();
        $param["KD_DISKON"] = $this->put('kd_diskon');
        $param["NAMA_DISKON"]   = $this->put('nama_diskon');
        $param["TIPE_DISKON"] = $this->put('tipe_diskon');
        $param["START_DATE"] =  tglToSql($this->put('start_date'));
        $param["END_DATE"] =  tglToSql($this->put('end_date'));
        $param["NIK"] =  $this->put('nik');
        $param["AMOUNT"] =  $this->put('amount');
        $param["KD_JENISCUSTOMER"] =  $this->put('kd_jeniscustomer');
        $param["KD_DEALER"] =  $this->put('kd_dealer');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_DISKON_UPDATE",$param,'put',TRUE);
    }
    /**
     * [diskon_delete description]
     * @return [type] [description]
     */
    public function diskon_delete(){
        $param = array();
        $param["KD_DISKON"]     = $this->delete('kd_diskon');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_DISKON_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *SETUP_DISKON_PART
    */
    public function diskon_part_get(){
        $param = array();$search='';
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get('type_customer')){
            $param["KD_JENISCUSTOMER"]     = $this->get("type_customer");
        }
        if($this->get("part_number")){
            $param["PART_NUMBER"]     = $this->get("part_number");
        }
        if($this->get("nama_diskon")){
            $param["NAMA_DISKON"]     = $this->get("nama_diskon");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "PART_NUMBER" => $this->get("keyword")
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
        $this->resultdata("SETUP_DISKON_PART",$param);
    }
    /**
     * [diskon_part_post description]
     * @return [type] [description]
     */
    public function diskon_part_post(){
        $param = array();
        $param["KD_DISKON"] = $this->post('kd_diskon');
        $param["KD_DEALER"] =  $this->post('kd_dealer');
        $param["KD_MAINDEALER"] =  $this->post('kd_maindealer');
        $param["NAMA_DISKON"]   = $this->post('nama_diskon');
        $param["TIPE_DISKON"] = $this->post('tipe_diskon');
        $param["START_DATE"] =  tglToSql($this->post('start_date'));
        $param["END_DATE"] =  tglToSql($this->post('end_date'));
        $param["NIK"] =  $this->post('nik');
        $param["AMOUNT"] =  $this->post('amount');
        $param["KD_JENISCUSTOMER"] =  $this->post('kd_jeniscustomer');
        $param["PART_NUMBER"] =  $this->post('part_number');
        $this->Main_model->data_sudahada($param,"SETUP_DISKON_PART");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_DISKON_PART_INSERT",$param,'post',TRUE);
    }
    /**
     * [diskon_part_put description]
     * @return [type] [description]
     */
    public function diskon_part_put(){
        $param = array();
        $param["ID"] = $this->put('id');
        $param["KD_DISKON"] = $this->put('kd_diskon');
        $param["NAMA_DISKON"]   = $this->put('nama_diskon');
        $param["TIPE_DISKON"] = $this->put('tipe_diskon');
        $param["START_DATE"] =  tglToSql($this->put('start_date'));
        $param["END_DATE"] =  tglToSql($this->put('end_date'));
        $param["NIK"] =  $this->put('nik');
        $param["AMOUNT"] =  $this->put('amount');
        $param["KD_JENISCUSTOMER"] =  $this->put('kd_jeniscustomer');
        $param["KD_DEALER"] =  $this->put('kd_dealer');
        $param["KD_MAINDEALER"] =  $this->put('kd_maindealer');
        $param["PART_NUMBER"] =  $this->put('part_number');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_DISKON_PART_UPDATE",$param,'put',TRUE);
    }
    /**
     * [diskon_part_delete description]
     * @return [type] [description]
     */
    public function diskon_part_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_DISKON_PART_DELETE",$param,'delete',TRUE);
    }

    function saleskuponbatch_post($insert=null){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
         // $folderJson = "\\\\".getConfig("UPJSON")."\\tmp\\desa.json";
        $folderJson = getConfig("UPJSON_C")."\saleskupon.json";
        //$folderJson = "\\\\192.168.0.114\\tmp\\desa.json";
        $handle=fopen($folderJson,'wb');
        fwrite($handle,$param);
        fclose($handle);
        if($insert==true){
            $datax = $this->Custom_model->simpan_kupon();
            unlink($folderJson);
        }
        
        //unlink($folderJson);
        if(!isset($datax)){
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
        if($insert==true){
            $this->response($datax);
        }
    }
    //
    /**
    *SETUP SALES KUPON
    */
    public function kupon_get(){
        $param = array();$search='';
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]  = $this->get("kd_typemotor");
        }
        /**/
        if($this->get("kd_saleskupon")){
            $param["KD_SALESKUPON"]     = $this->get("kd_saleskupon");
        }
        if($this->get("end_date")){
            $param["END_DATE"]     = $this->get("end_date");
        }
        if($this->get("end_claim")){
            $param["END_CLAIM"]     = $this->get("end_claim");
        }
        if($this->get("no_perkiraan")){
            $param["NO_PERKIRAAN"]     = $this->get("no_perkiraan");
        }
        if($this->get("no_subperkiraan")){
            $param["NO_SUBPERKIRAAN"]     = $this->get("no_subperkiraan");
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
        $this->resultdata("SETUP_SALESKUPON",$param);
    }
    /**
     * [kupon_post description]
     * @return [type] [description]
     */
    public function kupon_post(){
        $param = array();
        $param["KD_SALESKUPON"] = $this->post('kd_saleskupon');
        $param["KD_TYPEMOTOR"] =  $this->post('kd_typemotor');
        $param["START_DATE"] =  $this->post('start_date');
        $param["END_DATE"] =  $this->post('end_date');
        $param["END_CLAIM"] =  $this->post('end_claim');
        $param["NO_PERKIRAAN"] =  $this->post('no_perkiraan');
        $param["NO_SUBPERKIRAAN"] =  $this->post('no_subperkiraan');
        $param["NILAI"] =  $this->post('nilai');
        $param["TOP1"] =  $this->post('top1');
        $param["TOP2"] =  $this->post('top2');
        $this->Main_model->data_sudahada($param,"SETUP_SALESKUPON");
        $param["NAMA_SALESKUPON"]   = $this->post('nama_saleskupon');
        $param["CREATED_BY"]    = $this->post('created_by');
		
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESKUPON_INSERT",$param,'post',TRUE);
    }

    public function kuponnew_post(){
        $param=array();
        $this->Main_model->set_jsons($this->post('query'));
        $this->resultdata("",$param,'json',TRUE);
    }
    /**
     * [kupon_put description]
     * @return [type] [description]
     */
    public function kupon_put(){
        $param = array();
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

        $this->resultdata("SP_SETUP_SALESKUPON_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kupon_delete description]
     * @return [type] [description]
     */
    public function kupon_delete(){
        $param = array();
        $param["KD_SALESKUPON"]     = $this->delete('kd_saleskupon');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_SALESKUPON_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *SETUP SALESKUPONKOTA
    */
    public function saleskuponkota_get(){
        $param = array();$search='';
        if($this->get("kd_saleskupon")){
            $param["KD_SALESKUPON"]     = $this->get("kd_saleskupon");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_saleskupon")){
            $param["NAMA_SALESKUPON"]     = $this->get("nama_saleskupon");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_SALESKUPON" => $this->get("keyword"),
                "KD_DEALER" => $this->get("keyword"),
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
        $this->resultdata("SETUP_SALESKUPONKOTA",$param);
    }
    /**
     * [saleskuponkota_post description]
     * @return [type] [description]
     */
    public function saleskuponkota_post(){
        $param = array();
        $param["KD_SALESKUPON"] = $this->post('kd_saleskupon');
        $param["KD_DEALER"] = $this->post('kd_dealer');
        $param["NAMA_SALESKUPON"] = $this->post('nama_saleskupon');
        $param["CREATED_BY"]    = $this->post('created_by');
		$this->Main_model->data_sudahada($param,"SETUP_SALESKUPONKOTA");
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESKUPONKOTA_INSERT",$param,'post',TRUE);
    }

    /**
     * [saleskuponkota_put description]
     * @return [type] [description]
     */
    public function saleskuponkota_put(){
        $param = array();
        $param["KD_SALESKUPON"]     = $this->put('kd_saleskupon');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NAMA_SALESKUPON"]   = $this->put('nama_saleskupon');
        $param["LASTMODIFIED_BY"]   = $this->put('lastmodified_by');
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESKUPONKOTA_UPDATE",$param,'put',TRUE);
    }

    //
    /**
    *SETUP SALESKUPONLEASING
    */
    public function saleskuponleasing_get(){
        $param = array();$search='';
        if($this->get("kd_saleskupon")){
            $param["KD_SALESKUPON"]     = $this->get("kd_saleskupon");
        }
        if($this->get("kd_leasing")){
            $param["KD_LEASING"]     = $this->get("kd_leasing");
        }
        if($this->get("nama_saleskupon")){
            $param["NAMA_SALESKUPON"]     = $this->get("nama_saleskupon");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_SALESKUPON" => $this->get("keyword"),
                "KD_LEASING" => $this->get("keyword"),
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
        $this->resultdata("SETUP_SALESKUPONLEASING",$param);
    }
    /**
     * [saleskuponleasing_post description]
     * @return [type] [description]
     */
    public function saleskuponleasing_post(){
        $param = array();
        $param["KD_SALESKUPON"] = $this->post('kd_saleskupon');
        $param["KD_LEASING"] = $this->post('kd_leasing');
        
        $param["NAMA_SALESKUPON"] = $this->post('nama_saleskupon');
        $param["CREATED_BY"]    = $this->post('created_by');
		$this->Main_model->data_sudahada($param,"SETUP_SALESKUPONLEASING");
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESKUPONLEASING_INSERT",$param,'post',TRUE);
    }

    /**
     * [saleskuponleasing_put description]
     * @return [type] [description]
     */
    public function saleskuponleasing_put(){
        $param = array();
        $param["KD_SALESKUPON"]     = $this->put('kd_saleskupon');
        $param["KD_LEASING"]        = $this->put('kd_leasing');
        $param["NAMA_SALESKUPON"]   = $this->put('nama_saleskupon');
        $param["LASTMODIFIED_BY"]   = $this->put('lastmodified_by');
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESKUPONLEASING_UPDATE",$param,'put',TRUE);
    }
    
    //
    /**
    *SETUP SALES PROGRAM
    */
    public function salesprogram_get(){
        $param = array();$search='';
        if($this->get("kd_salesprogram")){
            $param["KD_SALESPROGRAM"]     = $this->get("kd_salesprogram");
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
        $this->resultdata("SETUP_SALESPROGRAM",$param);
    }
    function salesprogram_v_get(){
        $kd_dealer = $this->get("kd_dealer");
        $kd_leasing = ($this->get("kd_leasing"));
        $kd_typemotor = ($this->get("kd_typemotor"));
        $this->Main_model->set_custom_query($this->Custom_model->salesprogram($kd_dealer,$kd_leasing,$kd_typemotor));
        $this->resultdata("SETUP_SALESPROGRAM",$param=array());
    }
    /**
     * [salesprogram_post description]
     * @return [type] [description]
     */
    public function salesprogram_post(){
        $param = array();
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
		$this->Main_model->data_sudahada($param,"SETUP_SALESPROGRAM");
        $param["CREATED_BY"]    = $this->post('created_by');
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESPROGRAM_INSERT",$param,'post',TRUE);
    }
    public function salesprogramnew_post(){
        $param=array();
        $this->Main_model->set_jsons($this->post('query'));
        $this->resultdata("",$param,'json',TRUE);
    }
    /**
     * [salesprogram_put description]
     * @return [type] [description]
     */
    public function salesprogram_put(){
        $param = array();
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

        $this->resultdata("SP_SETUP_SALESPROGRAM_UPDATE",$param,'put',TRUE);
    }
    /**
     * [salesprogram_delete description]
     * @return [type] [description]
     */
    public function salesprogram_delete(){
        $param = array();
        $param["KD_SALESPROGRAM"] = $this->delete('kd_salesprogram');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_SALESPROGRAM_DELETE",$param,'delete',TRUE);
    }


    //
    /**
    *SETUP SALESPROGRAMKOTA
    */
    public function salesprogramkota_get(){
        $param = array();$search='';
        if($this->get("kd_salesprogram")){
            $param["KD_SALESPROGRAM"]     = $this->get("kd_salesprogram");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("nama_salesprogram")){
            $param["NAMA_SALESPROGRAM"]     = $this->get("nama_salesprogram");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_SALESPROGRAM" => $this->get("keyword"),
                "KD_KABUPATEN" => $this->get("keyword"),
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
        $this->resultdata("SETUP_SALESPROGRAMKOTA",$param);
    }
    /**
     * [salesprogramkota_post description]
     * @return [type] [description]
     */
    public function salesprogramkota_post(){
        $param = array();
        $param["KD_SALESPROGRAM"] = $this->post('kd_salesprogram');      
        $param["KD_KABUPATEN"] =  $this->post('kd_kabupaten');
        $param["NAMA_SALESPROGRAM"]   = $this->post('nama_salesprogram');    
		$this->Main_model->data_sudahada($param,"SETUP_SALESPROGRAMKOTA");
        $param["CREATED_BY"]    = $this->post('created_by');
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESPROGRAMKOTA_INSERT",$param,'post',TRUE);
    }

    /**
     * [salesprogramkota_put description]
     * @return [type] [description]
     */
    public function salesprogramkota_put(){
        $param = array();
        $param["KD_SALESPROGRAM"]   = $this->put('kd_salesprogram');
        $param["KD_KABUPATEN"]      = $this->put('kd_kabupaten');
        $param["NAMA_SALESPROGRAM"] = $this->put('nama_salesprogram');
        $param["LASTMODIFIED_BY"]   = $this->put('lastmodified_by');
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESPROGRAMKOTA_UPDATE",$param,'put',TRUE);
    }


    //
    /**
    *SETUP SALESPROGRAMLEASING
    */
    public function salesprogramleasing_get(){
        $param = array();$search='';
        if($this->get("kd_salesprogram")){
            $param["KD_SALESPROGRAM"]     = $this->get("kd_salesprogram");
        }
        if($this->get("nama_salesprogram")){
            $param["NAMA_SALESPROGRAM"]     = $this->get("nama_salesprogram");
        }
        if($this->get("kd_leasing")){
            $param["KD_LEASING"]     = $this->get("kd_leasing");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_SALESPROGRAM" => $this->get("keyword"),
                "NAMA_SALESPROGRAM" => $this->get("keyword"),
                "KD_LEASING" => $this->get("keyword")

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
        $this->resultdata("SETUP_SALESPROGRAMLEASING",$param);
    }
    /**
     * [salesprogramleasing_post description]
     * @return [type] [description]
     */
    public function salesprogramleasing_post(){
        $param = array();
        $param["KD_SALESPROGRAM"] = $this->post('kd_salesprogram');
        
        $param["NAMA_SALESPROGRAM"]   = $this->post('nama_salesprogram');
        $param["KD_LEASING"] =  $this->post('kd_leasing');
        $param["NILAI_LEASING"] =  $this->post('nilai_leasing');
        $param["KLASIFIKASI_LEASING"] =  $this->post('klasifikasi_leasing');
        $param["CREATED_BY"]    = $this->post('created_by');
		$this->Main_model->data_sudahada($param,"SETUP_SALESPROGRAMLEASING");
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESPROGRAMLEASING_INSERT",$param,'post',TRUE);
    }

    /**
     * [salesprogramleasing_put description]
     * @return [type] [description]
     */
    public function salesprogramleasing_put(){
        $param = array();
        $param["KD_SALESPROGRAM"]       = $this->put('kd_salesprogram');
        $param["NAMA_SALESPROGRAM"]     = $this->put('nama_salesprogram');
        $param["KD_LEASING"]            = $this->put('kd_leasing');
        $param["NILAI_LEASING"]         = $this->put('nilai_leasing');
        $param["KLASIFIKASI_LEASING"]   = $this->put('klasifikasi_leasing');
        $param["LASTMODIFIED_BY"]       = $this->put('lastmodified_by');
        // print_r($param);
        $this->resultdata("SP_SETUP_SALESPROGRAMLEASING_UPDATE",$param,'put',TRUE);
    }

    //
    /**
    *MASTER MAKELAR
    */
    public function makelar_get(){
        $param = array();$search='';
        if($this->get('kd_makelar')){
            $param["KD_MAKELAR"] = $this->get("kd_makelar");
        }
        if($this->get("nama_makelar")){
            $param["NAMA_MAKELAR"]     = $this->get("nama_makelar");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_MAKELAR"  => $this->get("keyword"),
                "NAMA_MAKELAR"    => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_MAKELAR",$param);
    }
    /**
     * [makelar_post description]
     * @return [type] [description]
     */
    public function makelar_post(){
        $param = array();
        $param["KD_MAKELAR"] = $this->post('kd_makelar');
        $this->Main_model->data_sudahada($param,"MASTER_MAKELAR");
        $param["NAMA_MAKELAR"]   = $this->post('nama_makelar');
        $param["NO_HP"] = $this->post('no_hp');
        $param["ALAMAT"] =  $this->post('alamat');
        $param["KD_DEALER"] =  $this->post('kd_dealer');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_MAKELAR_INSERT",$param,'post',TRUE);
    }
    /**
     * [makelar_put description]
     * @return [type] [description]
     */
    public function makelar_put(){
        $param = array();
        $param["KD_MAKELAR"] = $this->put('kd_makelar');
        $param["NAMA_MAKELAR"]   = $this->put('nama_makelar');
        $param["NO_HP"] = $this->put('no_hp');
        $param["ALAMAT"] =  $this->put('alamat');
        $param["KD_DEALER"] =  $this->put('kd_dealer');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MAKELAR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [makelar_delete description]
     * @return [type] [description]
     */
    public function makelar_delete(){
        $param = array();
        $param["KD_MAKELAR"]     = $this->delete('kd_makelar');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_MAKELAR_DELETE",$param,'delete',TRUE);
    }
    /**
     * get penomoran yng document nya sudah ada nomorator dan di bagi
     * dengan benerapa user contoh nomor kwitansi
     * dan fungsi ini bisa di gunakan untuk document lain yang juga seperti itu
     * @return [type] [description]
     */
    function docno_users_get($ntrans=null){
        $param=array();$search="";
        if($this->get('kd_docno')){
            $param["KD_DOCNO"] = $this->get('kd_docno');
        }
        if($this->get('kd_dealer')){
            $param['KD_DEALER'] = $this->get('kd_dealer');
        }
        if($this->get('kd_maindealer')){
            $param['KD_MAINDEALER'] = $this->get('kd_maindealer');
        }
        if($this->get('kd_users')){
            $param["KD_USERS"] = $this->get('kd_users');
        }
        if($this->get('tahun_docno')){
            $param["TAHUN_DOCNO"]= $this->get('tahun_docno');
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
        $tabel_name=($ntrans=='1')?"SETUP_DOCNO_USERS":"SETUP_DOCNO_KWT";
        $this->resultdata($tabel_name,$param);
    }

    /**
     * [docno_users_post description]
     * @return [type] [description]
     */
    public function docno_users_post(){
        $param = array();
        $param["KD_DOCNO"] = $this->post('kd_docno');
        $param["KD_DEALER"] =  $this->post('kd_dealer');
        $param["KD_MAINDEALER"] =  $this->post('kd_maindealer');
        $param["KD_USERS"] =  $this->post('kd_users');
        $param["LAST_DOCNO"] =  $this->post('last_docno');
        $param["TO_DOCNO"] =  $this->post('to_docno');
        $this->Main_model->data_sudahada($param,"SETUP_DOCNO_KWT",TRUE);
        $param["NAMA_DOCNO"]   = $this->post('nama_docno');
        $param["TAHUN_DOCNO"] = $this->post('tahun_docno');
        $param["BULAN_DOCNO"] =  $this->post('bulan_docno');
        $param["FROM_DOCNO"] =  $this->post('from_docno');
        $param["NO_TRANS"] =  $this->post('no_trans');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_DOCNO_KWT_INSERT",$param,'post',TRUE);
    }
    /**
     * [docno_users_put description]
     * @return [type] [description]
     */
    public function docno_users_put(){
        $param = array();
        // $param["ID"]     = $this->put('id');
        $param["KD_DOCNO"] = $this->put('kd_docno');
        $param["NAMA_DOCNO"]   = $this->put('nama_docno');
        $param["TAHUN_DOCNO"] = $this->put('tahun_docno');
        $param["BULAN_DOCNO"] =  $this->put('bulan_docno');
        $param["FROM_DOCNO"] =  $this->put('from_docno');
        $param["LAST_DOCNO"]     = $this->put("last_docno");
        $param["TO_DOCNO"]     = $this->put("to_docno");
        $param["KD_USERS"]     = $this->put("kd_users");
        $param["KD_DEALER"]     = $this->put("kd_dealer");
        $param["KD_MAINDEALER"]     = $this->put("kd_maindealer");
        $param["NO_TRANS"]     = $this->put("no_trans");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_DOCNO_KWT_UPDATE",$param,'put',TRUE);
    }
    /**
     * [docno_users_delete description]
     * @return [type] [description]
     */
    public function docno_users_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_DOCNO_USERS_DELETE",$param,'delete',TRUE);
    }

    function hasil_fu_get(){
        $param=array();$search="";
        if($this->get('kategori')){
            $param["KATEGORI"] = $this->get('kategori');
        }
        if($this->get('status')){
            $param["STATUS"]= $this->get('status');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KATEGORI"  => $this->get("keyword"),
                "STATUS"    => $this->get("keyword")
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
        $this->resultdata("SETUP_HASIL_FU",$param);
    }

    /**
     * [hasil_fu_post description]
     * @return [type] [description]
     */
    public function hasil_fu_post(){
        $param = array();
        $param["KATEGORI"] = $this->post('kategori');
        $param["STATUS"]   = $this->post('status');
        $param["KLASIFIKASI"] = $this->post('klasifikasi');
        $this->Main_model->data_sudahada($param,"SETUP_HASIL_FU");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_HASIL_FU_INSERT",$param,'post',TRUE);
    }
    /**
     * [hasil_fu_put description]
     * @return [type] [description]
     */
    public function hasil_fu_put(){
        $param = array();
        $param["ID"]     = $this->put('id');
        $param["KATEGORI"] = $this->put('kategori');
        $param["STATUS"]   = $this->put('status');
        $param["KLASIFIKASI"] = $this->put('klasifikasi');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_HASIL_FU_UPDATE",$param,'put',TRUE);
    }
    /**
     * [hasil_fu_delete description]
     * @return [type] [description]
     */
    public function hasil_fu_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_HASIL_FU_DELETE",$param,'delete',TRUE);
    }


    function ketnotdeal_get(){
        $param=array();$search="";
        if($this->get('kd_knd')){
            $param["KD_KND"]= $this->get('kd_knd');
        }
        if($this->get('kd_customer')){
            $param["KD_CUSTOMER"] = $this->get('kd_customer');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_KND"  => $this->get("keyword"),
                "KD_CUSTOMER"    => $this->get("keyword")
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
        $this->resultdata("SETUP_KETNOTDEAL",$param);
    }

    /**
     * [ketnotdeal_post description]
     * @return [type] [description]
     */
    public function ketnotdeal_post(){
        $param = array();
        $param["KD_KND"] = $this->post('kd_knd');
        $param["KD_CUSTOMER"]   = $this->post('kd_customer');
        $param["ALASAN"] = $this->post('alasan');
        $this->Main_model->data_sudahada($param,"SETUP_KETNOTDEAL");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_KETNOTDEAL_INSERT",$param,'post',TRUE);
    }
    /**
     * [ketnotdeal_put description]
     * @return [type] [description]
     */
    public function ketnotdeal_put(){
        $param = array();
        $param["ID"]     = $this->put('id');
        $param["KD_KND"] = $this->put('kd_knd');
        $param["KD_CUSTOMER"]   = $this->put('kd_customer');
        $param["ALASAN"] = $this->put('alasan');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_KETNOTDEAL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [kepalasales_get description]
     * @return [type] [description]
     */
    function kepalasales_get(){
        $param=array();$search="";
        if($this->get('id')){
            $param["ID"]= $this->get('id');
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"]= $this->get('kd_maindealer');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]= $this->get('kd_dealer');
        }
        if($this->get('nik')){
            $param["NIK"]= $this->get('nik');
        }
        if($this->get('nama_karyawan')){
            $param["NAMA_KARYAWAN"]= $this->get('nama_karyawan');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_DEALER"  => $this->get("keyword"),
                "NIK"  => $this->get("keyword"),
                "NAMA_KARYAWAN"    => $this->get("keyword")
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
        $this->resultdata("SETUP_KEPALASALES",$param);
    }

    /**
     * [kepalasales_post description]
     * @return [type] [description]
     */
    public function kepalasales_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["NIK"]               = $this->post('nik');
        $param["TGL_AWAL"]          = tglToSql($this->post('tgl_awal'));
        $this->Main_model->data_sudahada($param,"SETUP_KEPALASALES");
        $param["NAMA_KARYAWAN"]     = $this->post('nama_karyawan');
        $param["KD_LOKASI"]         = $this->post('kd_lokasi');
        $param["NIK_BAWAHAN"]       = $this->post('nik_bawahan');
        $param["KD_JABATAN"]        = $this->post('kd_jabatan');
        $param["TGL_AKHIR"]         = tglToSql($this->post('tgl_akhir'));
        $param["STATUS_AKTIF"]      = $this->post('status_aktif');
        //$param["ROW_STATUS"]        = $this->post('row_status');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_SETUP_KEPALASALES_INSERT",$param,'post',TRUE);
    }
    /**
     * [kepalasales_put description]
     * @return [type] [description]
     */
    public function kepalasales_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NIK"]               = $this->put('nik');
        $param["NAMA_KARYAWAN"]     = $this->put('nama_karyawan');
        $param["KD_JABATAN"]        = $this->put('kd_jabatan');
        $param["TGL_AWAL"]          = tglToSql($this->put('tgl_awal'));
        $param["TGL_AKHIR"]         = tglToSql($this->put('tgl_akhir'));
        //($this->put('tgl_akhir'))?tglToSql($this->put('tgl_akhir')):'NULL';
        $param["STATUS_AKTIF"]      = $this->put('status_aktif');
        $param["KD_LOKASI"]         = $this->put('kd_lokasi');
        $param["NIK_BAWAHAN"]       = $this->put('nik_bawahan');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_KEPALASALES_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kepalasales_delete description]
     * @return [type] [description]
     */
    public function kepalasales_delete(){
        $param = array();
        $param["ID"]               = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_KEPALASALES_DELETE",$param,'delete',TRUE);
    }

    //
    /**
    *SETUP_SA_UNIT
    */
    public function sa_unit_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_mesin")){
            $param["NO_MESIN"]     = $this->get("no_mesin");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
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
        $this->resultdata("SETUP_SA_UNIT",$param);
    }
    /**
     * [sa_unit_post description]
     * @return [type] [description]
     */
    public function sa_unit_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["NO_MESIN"]          = $this->post('no_mesin');
        $this->Main_model->data_sudahada($param,"SETUP_SA_UNIT");
        $param["NO_RANGKA"]         = $this->post('no_rangka');
        $param["THN_PROD"]          = $this->post('thn_prod');
        $param["JUMLAH"]            = $this->post('jumlah');
        $param["KD_GUDANG"]         = $this->post('kd_gudang');
        $param["STATUS_UNIT"]       = $this->post('status_unit');
        $param["KD_ITEM"]           = $this->post('kd_item');
        $param["TGL_TERIMA"]        = tglToSql($this->post('tgl_terima'));
        $param["CREATED_BY"]        = $this->post('created_by');
        
        // print_r($param);
        $this->resultdata("SP_SETUP_SA_UNIT_INSERT",$param,'post',TRUE);
    }

    /**
     * [sa_unit_put description]
     * @return [type] [description]
     */
    public function sa_unit_put(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NO_MESIN"]          = $this->put('no_mesin');
        $param["NO_RANGKA"]         = $this->put('no_rangka');
        $param["THN_PROD"]          = $this->put('thn_prod');
        $param["JUMLAH"]            = $this->put('jumlah');
        $param["KD_GUDANG"]         = $this->put('kd_gudang');
        $param["STATUS_UNIT"]       = $this->put('status_unit');
        $param["KD_ITEM"]           = $this->put('kd_item');
        $param["TGL_TERIMA"]        = tglToSql($this->put('tgl_terima'));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_SA_UNIT_UPDATE",$param,'put',TRUE);
    }
    /**
     * [sa_unit_delete description]
     * @return [type] [description]
     */
    public function sa_unit_delete(){
        $param = array();
        
        $param["KD_MAINDEALER"]     = $this->delete('kd_maindealer');
        $param["KD_DEALER"]         = $this->delete('kd_dealer');
        $param["NO_MESIN"]          = $this->delete('no_mesin');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_SA_UNIT_DELETE",$param,'delete',TRUE);
    }

    /**
     * [modul_apv_get description]
     * @return [type] [description]
     */
    public function modul_apv_get(){
        $param = array();$search='';
        if($this->get("kd_modul")){
            $param["KD_MODUL"]      = $this->get("kd_modul");
        }
        if($this->get("nama_modul")){
            $param["NAMA_MODUL"]     = $this->get("nama_modul");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]      = $this->get("kd_dealer");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_MODUL"     => $this->get("keyword"),
                "NAMA_MODUL"   => $this->get("keyword")
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
        
        $this->resultdata("MASTER_MODUL_APV",$param);
    }
    /**
     * [modul_apv_post description]
     * @return [type] [description]
     */
    public function modul_apv_post(){
        $param = array();
        $param["KD_MODUL"]          = $this->post('kd_modul');
        $param["NAMA_MODUL"]        = $this->post('nama_modul');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_MODUL_APV");
        $param["LEVEL_APV"]         = $this->post('level_apv');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_MASTER_MODUL_APV_INSERT",$param,'post',TRUE);
    }
    /**
     * [modul_apv_put description]
     * @return [type] [description]
     */
    public function modul_apv_put(){
        $param = array();
        $param["KD_MODUL"]          = $this->put('kd_modul');
        $param["NAMA_MODUL"]        = $this->put('nama_modul');
        $param["LEVEL_APV"]         = $this->put('level_apv');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MODUL_APV_UPDATE",$param,'put',TRUE);
    }

    /**
     * [modul_apv_delete description]
     * @return [type] [description]
     */
    public function modul_apv_delete(){
        $param = array();
        $param["KD_MODUL"]          = $this->delete('kd_modul');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_MODUL_APV_DELETE",$param,'delete',TRUE);

    }

    public function minimal_get(){
        $param = array();$search='';
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]      = $this->get("kd_maindealer");
        }
        if($this->get("kd_trans")){
            $param["KD_TRANS"]     = $this->get("kd_trans");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]      = $this->get("kd_dealer");
        }
       
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_TRANS"     => $this->get("keyword"),
                "KD_DEALER"   => $this->get("keyword")
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
        
        $this->resultdata("SETUP_MINIMAL_VALUE",$param);
    }
    public function minimal_post(){
        $param = array();
        $param["KD_MAINDEALER"]     = $this->post('kd_maindealer');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["KD_TRANS"]          = $this->post('kd_trans');
        $this->Main_model->data_sudahada($param,"SETUP_MINIMAL_VALUE");
        $param["KETERANGAN"]        = $this->post('keterangan');
        $param["MIN_VALUE"]         = $this->post('min_value');
        $param["MAX_VALUE"]         = $this->post('max_value');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_SETUP_MINIMAL_VALUE_INSERT",$param,'post',TRUE);
    }

    public function minimal_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_MAINDEALER"]     = $this->put('kd_maindealer');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_TRANS"]          = $this->put('kd_trans');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["MIN_VALUE"]         = $this->put('min_value');
        $param["MAX_VALUE"]         = $this->put('max_value');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_MINIMAL_VALUE_UPDATE",$param,'put',TRUE);
    }
    public function minimal_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_MINIMAL_VALUE_DELETE",$param,'delete',TRUE);

    }
    
    /**
     * [kepalasales_bawahan_get description]
     * @return [type] [description]
     */
    public function kepalasales_bawahan_get(){
        $param = array();$search='';
        if($this->get("nik")){
            $param["NIK"]      = $this->get("nik");
        }
        if($this->get("nik_ks")){
            $param["NIK_KS"]     = $this->get("nik_ks");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]      = $this->get("kd_dealer");
        }
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"]      = $this->get("kd_lokasi");
        }
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "NIK"     => $this->get("keyword"),
                "NIK_KS"   => $this->get("keyword")
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
        
        $this->resultdata("SETUP_KEPALASALES_BAWAHAN",$param);
    }
    /**
     * [kepalasales_bawahan_post description]
     * @return [type] [description]
     */
    public function kepalasales_bawahan_post(){
        $param = array();
        $param["NIK"]               = $this->post('nik');
        $param["KS_ID"]             = $this->post('ks_id');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $param["TGL_AWAL"]          = tglToSql($this->post('tgl_awal'));
        $this->Main_model->data_sudahada($param,"SETUP_KEPALASALES_BAWAHAN");
        $param["NIK_KS"]            = $this->post('nik_ks');
        $param["KD_LOKASI"]         = $this->post('kd_lokasi');
        $param["TGL_AKHIR"]         = tglToSql($this->post('tgl_akhir'));
        $param["STATUS"]            = $this->post('status');
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_SETUP_KEPALASALES_BAWAHAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [kepalasales_bawahan_put description]
     * @return [type] [description]
     */
    public function kepalasales_bawahan_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NIK"]               = $this->put('nik');
        $param["NIK_KS"]            = $this->put('nik_ks');
        $param["KS_ID"]             = $this->put('ks_id');
        $param["TGL_AWAL"]          = tglToSql($this->put('tgl_awal'));
        $param["TGL_AKHIR"]         = tglToSql($this->put('tgl_akhir'));
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_LOKASI"]         = $this->put('kd_lokasi');
        $param["STATUS"]            = $this->put('status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_KEPALASALES_BAWAHAN_UPDATE",$param,'put',TRUE);
    }

    /**
     * [kepalasales_bawahan_delete description]
     * @return [type] [description]
     */
    public function kepalasales_bawahan_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_KEPALASALES_BAWAHAN_DELETE",$param,'delete',TRUE);

    }

    public function skema_kredit_get($view=null){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]      = $this->get("no_trans");
        }
        if($this->get("kd_typemotor")){
            $param["KD_TYPEMOTOR"]      = $this->get("kd_typemotor");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]      = $this->get("kd_dealer");
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
        if($view){
            $this->Main_model->set_custom_query($this->Custom_model->skemaleasing($param["KD_DEALER"],$param["KD_TYPEMOTOR"],$param['NO_TRANS']));
        }
        $this->resultdata("SETUP_LEASING_SKEMA",$param);
    }
    public function skema_kredit_post(){
        $param=array();
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_DEALER"]     = $this->post("kd_dealer");
        $param["NO_TRANS"]      = $this->post("no_trans");
        $param["KD_LEASING"]    = $this->post("kd_leasing");
        $param["KD_TYPEMOTOR"]  = $this->post("kd_typemotor");
        $this->Main_model->data_sudahada($param,"SETUP_LEASING_SKEMA");
        $param["TGL_TRANS"]     = tglToSql($this->post("tgl_trans"));
        $param["HARGA_OTR"]     = $this->post("harga_otr");
        $param["START_DATE"]    = tglToSql($this->post("start_date"));
        $param["END_DATE"]      = tglToSql($this->post("end_date"));
        $param["KETERANGAN"]    = $this->post("keterangan");
//        $param["ROW_STATUS"]    = "0";
        $param["CREATED_BY"]    = $this->post("created_by");
        $this->resultdata("SP_SETUP_LEASING_SKEMA_INSERT",$param,'post',TRUE);
    }
    public function skema_kredit_put(){
//        $param["ID"]            = $this->put("id");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_DEALER"]     = $this->put("kd_dealer");
        $param["NO_TRANS"]      = $this->put("no_trans");
        $param["KD_LEASING"]    = $this->put("kd_leasing");
        $param["KD_TYPEMOTOR"]  = $this->put("kd_typemotor");
        $param["TGL_TRANS"]     = tglToSql($this->put("tgl_trans"));
        $param["HARGA_OTR"]     = $this->put("harga_otr");
        $param["START_DATE"]    = tglToSql($this->put("start_date"));
        $param["END_DATE"]      = tglToSql($this->put("end_date"));
        $param["KETERANGAN"]    = $this->put("keterangan");
        $param["LASTMODIFIED_BY"]    = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_LEASING_SKEMA_UPDATE",$param,'put',TRUE);
    }
    public function skema_kredit_delete(){
        $param = array();
        $param["NO_TRANS"]                = $this->delete('no_trans');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_LEASING_SKEMA_DELETE",$param,'delete',TRUE);
    }
    public function skema_kredit_detail_get(){
        $param = array();$search='';
        if($this->get("no_trans")){
            $param["NO_TRANS"]      = $this->get("no_trans");
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
        
        $this->resultdata("SETUP_LEASING_SKEMA_DETAIL",$param);
    }
    public function skema_kredit_detail_post(){
        $param=array();
        $param["NO_TRANS"]  = $this->post("no_trans");
        $param["UANG_MUKA"] = $this->post("uang_muka");
        $param["TENOR"]     = $this->post("tenor");
        $this->Main_model->data_sudahada($param,"SETUP_LEASING_SKEMA_DETAIL");
        $param["JML_ANGSURAN"]    = $this->post("jml_angsuran");
        $param["CREATED_BY"]  = $this->post("created_by");
        $this->resultdata("SP_SETUP_LEASING_SKEMA_DETAIL_INSERT",$param,'post',TRUE);
    }
    public function skema_kredit_detail_put(){
        $param=array();
        $param["ID"]        = $this->put("id");
        $param["NO_TRANS"]  = $this->put("no_trans");
        $param["UANG_MUKA"] = $this->put("uang_muka");
        $param["TENOR"]     = $this->put("tenor");
        $param["JML_ANGSURAN"]    = $this->put("jml_angsuran");
        $param["LASTMODIFIED_BY"]  = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_LEASING_SKEMA_DETAIL_UPDATE",$param,'put',TRUE);
    }
    public function skema_kredit_detail_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_LEASING_SKEMA_DETAIL_DELETE",$param,'delete',TRUE);
    }
    /**
     * [notifikasi_get description]
     * @return [type] [description]
     */
    public function notifikasi_get(){
       $param = array();$search='';
        if($this->get("kd_group")){
            $param["KD_GROUP"]     = $this->get("kd_group");
        }
        if($this->get("kd_modul")){
            $param["KD_MODUL"]     = $this->get("kd_modul");
        }
        if($this->get("kd_relasi")){
            $param["KD_RELASI"]     = $this->get("kd_relasi");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_GROUP"     => $this->get("keyword"),
                "KD_MODUL"     => $this->get("keyword"),
                "KD_RELASI"     => $this->get("keyword")
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
        $this->resultdata("SETUP_NOTIFIKASI",$param);
    }

    /**
     * [notifikasi_post description]
     * @return [type] [description]
     */
    public function notifikasi_post(){	
        $param = array();
        $param["KD_GROUP"]          = $this->post('kd_group');
        $param["KD_MODUL"]          = $this->post('kd_modul');
        $param["KD_RELASI"]         = $this->post("kd_relasi");
        $param["TABLE_VIEW"]        = $this->post("table_view");
        $this->Main_model->data_sudahada($param,"SETUP_NOTIFIKASI",TRUE);
        $param["DASHBOARD_NOTIF"]   = $this->post("dashboard_notif");
        $param["HEADER_NOTIF"]      = $this->post("header_notif");
        $param["CREATED_BY"]        = $this->post('created_by');
        $this->resultdata("SP_SETUP_NOTIFIKASI_INSERT",$param,'post',TRUE);
    }

    
    /**
     * [notifikasi_put description]
     * @return [type] [description]
     */
    public function notifikasi_put(){
        $param = array();
        $param["ID"]                = $this->put("id");
        $param["KD_GROUP"]          = $this->put('kd_group');
        $param["KD_MODUL"]          = $this->put('kd_modul');
        $param["KD_RELASI"]         = $this->put("kd_relasi");
        $param["TABLE_VIEW"]        = $this->put("table_view");
        $param["DASHBOARD_NOTIF"]   = $this->put("dashboard_notif");
        $param["HEADER_NOTIF"]      = $this->put("header_notif");

        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_SETUP_NOTIFIKASI_UPDATE",$param,'put',TRUE);
    }

    public function notifikasi_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        
        $this->resultdata("SP_SETUP_NOTIFIKASI_DELETE",$param,'delete',TRUE);
    }
	
	public function qtypo_get(){
		$param = array();
		$search='';
        if($this->get("kd_dealer") != ''){
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if($this->get("tahun")){
            $param["TAHUN"]     = $this->get("tahun");
        }
		if($this->get("jenis_po")){
            $param["JENIS_pO"]     = $this->get("jenis_po");
        }
        if($this->get("kd_typemotor") != '' AND $this->get("kd_typemotor") != 'all'){
            $param["KD_TYPEMOTOR"]     = $this->get("kd_typemotor");
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
        $this->resultdata("SETUP_QTYPO",$param);
	}
	
	public function qtypo_delete(){					
		$param["ID"]				= $this->delete("id");
		$param["LASTMODIFIED_BY"] 	= $this->delete("lastmodified_by");	
		$this->resultdata("SP_SETUP_QTYPO_DELETE",$param,'delete',TRUE);
	}
	
	public function qtypo_put(){					
		$param["ID"]				= $this->put("id");
		$param["MIN"]				= $this->put("min");
		$param["MAX"]				= $this->put("max");
		$param["LASTMODIFIED_BY"] 	= $this->put("lastmodified_by");	
		$this->resultdata("SP_SETUP_QTYPO_UPDATE",$param,'put',TRUE);
	}
	
	public function qtypo_post(){
		$param["KD_DEALER"]			= $this->post("kd_dealer");
		$param["TAHUN"]				= $this->post("tahun");
		$param["KD_TYPEMOTOR"]		= $this->post("kd_typemotor");
		$param["JENIS_PO"]			= $this->post("jenis_po");
		$param["MIN"]				= $this->post("min");
		$param["MAX"]				= $this->post("max");
		$param["CREATED_BY"] 	= $this->post("created_by");	
		$this->resultdata("SP_SETUP_QTYPO_INSERT",$param,'post',TRUE);
	}
	
}
?>