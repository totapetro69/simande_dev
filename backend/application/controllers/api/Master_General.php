<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Master_general extends REST_Controller {

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
     * [agama_get description]
     * @return [type] [description]
     */
    public function agama_get(){
       $param = array();$search='';
       if($this->get("kd_agama")){
            $param["KD_AGAMA"]     = $this->get("kd_agama");
        }
        if($this->get("nama_agama")){
            $param["NAMA_AGAMA"]     = $this->get("nama_agama");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_AGAMA" =>$this->get("keyword"),
                "NAMA_AGAMA" =>$this->get("keyword")
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
        $this->resultdata("MASTER_AGAMA",$param);
    }
    /**
     * [agama_post description]
     * @return [type] [description]
     */
    public function agama_post(){
        $param = array();
        $param["KD_AGAMA"]      = $this->post('kd_agama');
        $param["NAMA_AGAMA"]    = $this->post('nama_agama');
        $this->Main_model->data_sudahada($param,"MASTER_AGAMA");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_AGAMA_INSERT",$param,'post',TRUE);
    }

    /**
     * [jeniskelamin_get description]
     * @return [type] [description]
     */
    public function jeniskelamin_get(){
        $param = array(); $search='';
        if ($this->get("kd_gender")) {
            $param["KD_GENDER"] = $this->get("kd_gender");
        }
        if($this->get("nama_gender")) {
            $param["NAMA_GENDER"]   = $this->get("nama_gender");
        }
        if ($this->get("keyword")) {
            $param = array();
            $search=array(
                "KD_GENDER" => $this->get("keyword") ,
                "NAMA_GENDER"   => $this->get("keyword")
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
        $this->resultdata("MASTER_GENDER",$param);
    }

    /**
     * [jeniskelamin_post description]
     * @return [type] [description]
     */
    public function jeniskelamin_post(){
        $param = array();
        $param["KD_GENDER"]     = $this->post('kd_gender');
        $param["NAMA_GENDER"]   = $this->post('nama_gender');
        $this->Main_model->data_sudahada($param,"MASTER_GENDER");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_GENDER_INSERT",$param,'post',TRUE);
    }

    /**
     * [propinsi_get description]
     * @return [type] [description]
     */
    public function propinsi_get(){
        $param = array();$search='';
        if($this->get("kd_propinsi")){
            $param["KD_PROPINSI"]     = $this->get("kd_propinsi");
        }
        if($this->get("nama_propinsi")){
            $param["NAMA_PROPINSI"]   = $this->get("nama_propinsi");
        }
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_PROPINSI"   => $this->get("keyword"),
                "NAMA_PROPINSI" => $this->get("keyword")
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
        $this->resultdata("MASTER_PROPINSI",$param);
    }
    /**
     * [propinsi_post description]
     * @return [type] [description]
     */
    public function propinsi_post(){
        $param = array();
        $param["KD_NEGARA"]     = $this->post('kd_negara');
        $param["KD_PROPINSI"]   = $this->post('kd_propinsi');
        $this->Main_model->data_sudahada($param,"MASTER_PROPINSI");
        $param["NAMA_PROPINSI"] = $this->post('nama_propinsi');
        $param["CREATED_BY"]    = $this->post('created_by');

        $this->resultdata("SP_MASTER_PROPINSI_INSERT",$param,'post',TRUE);
    }


    /**
     * [propinsi_put description]
     * @return [type] [description]
     */
    public function propinsi_put(){
        $param = array();
        $param["KD_PROPINSI"]       = $this->put('kd_propinsi');
        $param["KD_NEGARA"]         = $this->put('kd_negara');
        $param["NAMA_PROPINSI"]     = $this->put('nama_propinsi');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PROPINSI_UPDATE",$param,'put',TRUE);
    }

    /**
     * [propinsi_delete description]
     * @return [type] [description]
     */
    public function propinsi_delete(){
        $param = array();
        $param["KD_PROPINSI"]       = $this->delete('kd_propinsi');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PROPINSI_DELETE",$param,'delete',TRUE);
    }
    /**
     * [kabupaten_get description]
     * @return [type] [description]
     */
    public function kabupaten_get(){
        $param = array();$search='';
        if($this->get("kd_propinsi")){
            $param["KD_PROPINSI"]     = $this->get("kd_propinsi");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("nama_kabupaten")){
            $param["NAMA_KABUPATEN"]     = $this->get("nama_kabupaten");
        }
        /*if($this->get("kd_propinsi")){
            $param["KD_PROPINSI"]     = $this->get("kd_propinsi");
        }*/
        if ($this->get("keyword")) {
            $param= array();
            $search= array(
                "KD_KABUPATEN" => $this->get("keyword"),
                "NAMA_KABUPATEN" => $this->get("keyword")
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
        $this->resultdata("MASTER_KABUPATEN",$param);
    }
    /**
     * [kabupaten_post description]
     * @return [type] [description]
     */
    public function kabupaten_post(){
        $param = array();
        $param["KD_PROPINSI"]   = $this->post('kd_propinsi');
        $param["KD_KABUPATEN"]  = $this->post('kd_kabupaten');
        $this->Main_model->data_sudahada($param,"MASTER_KABUPATEN");
        $param["NAMA_KABUPATEN"]= $this->post('nama_kabupaten');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_KABUPATEN_INSERT",$param,'post',TRUE);
    }
    /**
     * [kabupaten_put description]
     * @return [type] [description]
     */
    public function kabupaten_put(){
        $param = array();
        $param["KD_PROPINSI"]       = $this->put('kd_propinsi');
        $param["KD_KABUPATEN"]      = $this->put('kd_kabupaten');
        $param["NAMA_KABUPATEN"]    = $this->put('nama_kabupaten');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KABUPATEN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kabupaten_delete description]
     * @return [type] [description]
     */
    public function kabupaten_delete(){
        $param = array();
        $param["KD_KABUPATEN"]      = $this->delete('kd_kabupaten');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_KABUPATEN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [kecamatan_get description]
     * @return [type] [description]
     */
    public function kecamatan_get(){
        $param = array();$search='';
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("kd_kecamatan")){
            $param["KD_KECAMATAN"]     = $this->get("kd_kecamatan");
        }
        if($this->get("nama_kecamatan")){
            $param["NAMA_KECAMATAN"]     = $this->get("nama_kecamatan");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_KABUPATEN" => $this->get("keyword"),
                "KD_KECAMATAN" => $this->get("keyword"),
                "NAMA_KECAMATAN" => $this->get("keyword")
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
        $this->resultdata("MASTER_KECAMATAN",$param);
    }
    /**
     * [kecamatan_post description]
     * @return [type] [description]
     */
    public function kecamatan_post(){
        $param = array();
        $param["KD_KECAMATAN"]  = $this->post('kd_kecamatan');
        $param["KD_KABUPATEN"]  = $this->post('kd_kabupaten');
        $this->Main_model->data_sudahada($param,"MASTER_KECAMATAN");
        $param["NAMA_KECAMATAN"]= $this->post('nama_kecamatan');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_KECAMATAN_INSERT",$param,'post',TRUE);
    }

    public function kecamatannew_post(){
        $param=array();
        $this->Main_model->set_jsons($this->post('query'));
        $this->resultdata("",$param,'json',TRUE);
    }
    /**
     * [kecamatan_put description]
     * @return [type] [description]
     */
    public function kecamatan_put(){
        $param = array();
        $param["KD_KABUPATEN"]   = $this->put('kd_kabupaten');
        $param["KD_KECAMATAN"]   = $this->put('kd_kecamatan');
        $param["NAMA_KECAMATAN"] = $this->put('nama_kecamatan');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KECAMATAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kecamatan_delete description]
     * @return [type] [description]
     */
    public function kecamatan_delete(){
        $param = array();
        $param["KD_KECAMATAN"]      = $this->delete('kd_kecamatan');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_KECAMATAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [desa_get description]
     * @return [type] [description]
     */
    public function desa_get(){
        $param = array();$search='';
        if($this->get("kd_kota")){
            $param["KD_KOTA"]     = $this->get("kd_kota");
        }
        if($this->get("kd_kecamatan")){
            $param["KD_KECAMATAN"]     = $this->get("kd_kecamatan");
        }
        if($this->get("kd_desa")){
            $param["KD_DESA"]     = $this->get("kd_desa");
        }
        if($this->get("nama_desa")){
            $param["NAMA_DESA"] = $this->get("nama_desa");
        }
        if($this->get("kd_desaahm")){
            $param["KD_DESAAHM"] = $this->get("kd_desaahm");
        }
        if($this->get("nama_desaahm")){
            $param["DESAAHM"] = $this->get("nama_desaahm");
        }
        if($this->post('kode_pos')){
            $param["KODE_POS"] = $this->post('kode_pos');
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NAMA_DESA" => $this->get("keyword"),
                "KD_DESA"   => $this->get("keyword"),
                "NAMA_DESA" => $this->get("keyword")
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
        $this->resultdata("MASTER_DESA",$param);
    }
    /**
     * [desa_post description]
     * @return [type] [description]
     */
    public function desa_post(){
        $param = array();
        $param["KD_DESA"]   = $this->post('kd_desa');
        $param["KD_KECAMATAN"] = $this->post('kd_kecamatan');
        $param["KD_KOTA"] = $this->post('kd_kota');
        $this->Main_model->data_sudahada($param,"MASTER_DESA");
        $param["NAMA_PROPINSI"]   = $this->post('nama_propinsi');
        $param["NAMA_KOTA"]   = $this->post('nama_kota');
        $param["NAMA_KECAMATAN"]   = $this->post('nama_kecamatan');
        $param["NAMA_DESA"] = $this->post('nama_desa');
        $param["KODE_POS"] = $this->post('kode_pos');
        $param["STATUS"] = $this->post('status');
        $param["KD_DESAAHM"]    = $this->post('kd_desaahm');
        $param["DESAAHM"]   = $this->post('desaahm');
        $param["CREATED_BY"]    = $this->post('created_by');
 
        // print_r($param);
        $this->resultdata("SP_MASTER_DESA_INSERT",$param,'post',TRUE);
    }   

    public function desanew_post(){
        $param=array();
        $this->Main_model->set_jsons($this->post('query'));
        $this->resultdata("",$param,'json',TRUE);
    }
    public function desabatch_post($insert=null){
        ini_set('max_execution_time',120);
        ini_set('post_max_size',0);
        $param = file_get_contents("php://input");
        $param=(base64_decode($param));;
         // $folderJson = "\\\\".getConfig("UPJSON")."\\tmp\\desa.json";
        $folderJson = getConfig("UPJSON_C")."\desa.json";
        //$folderJson = "\\\\192.168.0.114\\tmp\\desa.json";
        $handle=fopen("$folderJson",'wb') or die("Unable to open file!");
        fwrite($handle,$param);
        fclose($handle);
        if($insert==true){
            $datax = $this->Custom_model->simpan_desa();
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

    /**
     * [desa_put description]
     * @return [type] [description]
     */
    public function desa_put(){
        $param = array();
        $param["KD_KOTA"] = $this->put('kd_kota');
        $param["KD_KECAMATAN"] = $this->put('kd_kecamatan');
        $param["KD_DESA"]   = $this->put('kd_desa');
        $param["NAMA_PROPINSI"]   = $this->put('nama_propinsi');
        $param["NAMA_KOTA"]   = $this->put('nama_kota');
        $param["NAMA_KECAMATAN"]   = $this->put('nama_kecamatan');
        $param["NAMA_DESA"] = $this->put('nama_desa');
        $param["KODE_POS"] = $this->put('kode_pos');
        $param["STATUS"] = $this->put('status');
        $param["KD_DESAAHM"]    = $this->put('kd_desaahm');
        $param["DESAAHM"]   = $this->put('desaahm');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
 
        $this->resultdata("SP_MASTER_DESA_UPDATE",$param,'put',TRUE);
    }
 
    /**
     * [desa_delete description]
     * @return [type] [description]
     */
    public function desa_delete(){
        $param = array();
        $param["KD_DESA"]     = $this->delete('kd_desa');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_DESA_DELETE",$param,'delete',TRUE);
    }

    

    /**
     * [pekerjaan_get description]
     * @return [type] [description]
     */
    public function pekerjaan_get(){
        $param = array();$search='';
        if($this->get("kd_pekerjaan")){
            $param["KD_PEKERJAAN"]     = $this->get("kd_pekerjaan");
        }
        if($this->get("nama_pekerjaan")){
            $param["NAMA_PEKERJAAN"]     = $this->get("nama_pekerjaan");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_PEKERJAAN"  => $this->get("keyword"),
                "NAMA_PEKERJAAN"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PEKERJAAN",$param);
    }
    /**
     * [pekerjaan_post description]
     * @return [type] [description]
     */
    public function pekerjaan_post(){
        $param = array();
        $param["KD_PEKERJAAN"]  = $this->post('kd_pekerjaan');
        $this->Main_model->data_sudahada($param,"MASTER_PEKERJAAN");
        $param["NAMA_PEKERJAAN"]= $this->post('nama_pekerjaan');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PEKERJAAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [pekerjaan_put description]
     * @return [type] [description]
     */
    public function pekerjaan_put(){
        $param = array();
        $param["KD_PEKERJAAN"]      = $this->put('kd_pekerjaan');
        $param["NAMA_PEKERJAAN"]    = $this->put('nama_pekerjaan');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PEKERJAAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pekerjaan_delete description]
     * @return [type] [description]
     */
    public function pekerjaan_delete(){
        $param = array();
        $param["KD_PEKERJAAN"]     = $this->delete('kd_pekerjaan');
        $param["LASTMODIFIED_BY"]  = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PEKERJAAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [karyawan_get description]
     * @return [type] [description]
     */
    public function karyawan_get(){
        $param = array();$search='';
        if($this->get("nik")){
            $param["NIK"]     = $this->get("nik");
        }
        if($this->get("nama")){
            $param["NAMA"]     = $this->get("nama");
        }

        if($this->get("kd_status")){
            $param["KD_STATUS"]     = $this->get("kd_status");
        }

        if($this->get("kd_dealer")){
            $param["KD_CABANG"]     = $this->get("kd_dealer");
        }

        /*if($this->get("kd_cabang")){
            $param["KD_CABANG"]     = $this->get("kd_cabang");
        }*/


        if($this->get("kd_divisi")){
            $param["KD_DIVISI"]     = $this->get("kd_divisi");
        }

        if($this->get("kd_jabatan")){
            $param["KD_JABATAN"]     = $this->get("kd_jabatan");
        }


        if($this->get("personal_jabatan")){
            $param["PERSONAL_JABATAN"]     = $this->get("personal_jabatan");
        }

        if($this->get("personal_level")){
            $param["PERSONAL_LEVEL"]     = $this->get("personal_level");
        }

         if($this->get("atasan_langsung")){
            $param["ATASAN_LANGSUNG"]     = $this->get("atasan_langsung");
        }

        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NIK"   => $this->get("keyword"),
                "NAMA"  => $this->get("keyword")
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
        $this->resultdata("MASTER_KARYAWAN",$param);
    }
    /**
     * [karyawan_post description]
     * @return [type] [description]
     */
    public function karyawan_post(){
        $param = array();
        $param["NIK"]               = $this->post('nik');
        $this->Main_model->data_sudahada($param,"MASTER_KARYAWAN");
        $param["NAMA"]              = ($this->post('nama'));
        $param["KD_STATUS"]         = $this->post('kd_status');
        $param["KD_PERUSAHAAN"]     = $this->post('kd_perusahaan');
        $param["KD_CABANG"]         = $this->post('kd_cabang');
        $param["KD_DIVISI"]         = $this->post('kd_divisi');
        $param["KD_JABATAN"]        = $this->post('kd_jabatan');
        $param["PERSONAL_JABATAN"]  = $this->post('personal_jabatan');
        $param["PERSONAL_LEVEL"]    = $this->post('personal_level');
        $param["ATASAN_LANGSUNG"]   = $this->post('atasan_langsung');
        $param["PASSWORD"]          = $this->post('password');
        $param["KD_SALES"]          = $this->post('kd_sales');
        $param["KD_HSALES"]         = $this->post('kd_hsales');
        $param["HONDA_ID"]          = $this->post('honda_id');
        $param["TGL_LAHIR"]         = $this->post('tgl_lahir');
        $param["PENDIDIKAN"]        = $this->post('pendidikan');
        $param["TGL_MASUK"]         = $this->post('tgl_masuk');
        $param["CREATED_BY"]        = $this->post('created_by');

        $this->resultdata("SP_MASTER_KARYAWAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [karyawan_put description]
     * @return [type] [description]
     */
    public function karyawan_put(){
        $param = array();
        $param["NIK"]               = $this->put('nik');
        $param["NAMA"]              = ($this->put('nama'));
        $param["KD_STATUS"]         = $this->put('kd_status');
        $param["KD_PERUSAHAAN"]     = $this->put('kd_perusahaan');
        $param["KD_CABANG"]         = $this->put('kd_cabang');
        $param["KD_DIVISI"]         = $this->put('kd_divisi');
        $param["KD_JABATAN"]        = $this->put('kd_jabatan');
        $param["PERSONAL_JABATAN"]  = $this->put('personal_jabatan');
        $param["PERSONAL_LEVEL"]    = $this->put('personal_level');
        $param["ATASAN_LANGSUNG"]   = $this->put('atasan_langsung');
        $param["PASSWORD"]          = $this->put('password');
        $param["KD_SALES"]          = $this->post('kd_sales');
        $param["KD_HSALES"]         = $this->post('kd_hsales');
        $param["HONDA_ID"]          = $this->put('honda_id');
        $param["TGL_LAHIR"]         = $this->put('tgl_lahir');
        $param["PENDIDIKAN"]        = $this->put('pendidikan');
        $param["TGL_MASUK"]         = $this->put('tgl_masuk');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KARYAWAN_UPDATE",$param,'put', TRUE);
    }

    /**
     * [karyawan_dealer_post description]
     * @return [type] [description]
     */
    public function karyawan_dealer_post(){
        $param = array();
        $param["NIK"]               = $this->post('nik');
        $this->Main_model->data_sudahada($param,"MASTER_KARYAWAN");
        $param["NAMA"]              = ($this->put('nama'));
        $param["KD_STATUS"]         = $this->post('kd_status');
        $param["KD_PERUSAHAAN"]     = $this->post('kd_perusahaan');
        $param["KD_CABANG"]         = $this->post('kd_cabang');
        $param["KD_DIVISI"]         = $this->post('kd_divisi');
        $param["KD_JABATAN"]        = $this->post('kd_jabatan');
        $param["PERSONAL_JABATAN"]  = $this->post('personal_jabatan');
        $param["PERSONAL_LEVEL"]    = $this->post('personal_level');
        $param["ATASAN_LANGSUNG"]   = $this->post('atasan_langsung');
        $param["PASSWORD"]          = $this->post('password');
        $param["KD_SALES"]          = $this->post('kd_sales');
        $param["KD_HSALES"]         = $this->post('kd_hsales');
        $param["HONDA_ID"]          = $this->post('honda_id');
        $param["TGL_LAHIR"]         = tglToSql($this->post('tgl_lahir'));
        $param["PENDIDIKAN"]        = $this->post('pendidikan');
        $param["TGL_MASUK"]         = tglToSql($this->post('tgl_masuk'));
        $param["CREATED_BY"]        = $this->post('created_by');

        $this->resultdata("SP_MASTER_KARYAWAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [karyawan_dealer_put description]
     * @return [type] [description]
     */
    public function karyawan_dealer_put(){
        $param = array();
        $param["NIK"]               = $this->put('nik');
        $param["NAMA"]              = ($this->put('nama'));
        $param["KD_STATUS"]         = $this->put('kd_status');
        $param["KD_PERUSAHAAN"]     = $this->put('kd_perusahaan');
        $param["KD_CABANG"]         = $this->put('kd_cabang');
        $param["KD_DIVISI"]         = $this->put('kd_divisi');
        $param["KD_JABATAN"]        = $this->put('kd_jabatan');
        $param["PERSONAL_JABATAN"]  = $this->put('personal_jabatan');
        $param["PERSONAL_LEVEL"]    = $this->put('personal_level');
        $param["ATASAN_LANGSUNG"]   = $this->put('atasan_langsung');
        $param["PASSWORD"]          = $this->put('password');
        $param["KD_SALES"]          = $this->post('kd_sales');
        $param["KD_HSALES"]         = $this->post('kd_hsales');
        $param["HONDA_ID"]          = $this->put('honda_id');
        $param["TGL_LAHIR"]         = tglToSql($this->put('tgl_lahir'));
        $param["PENDIDIKAN"]        = $this->put('pendidikan');
        $param["TGL_MASUK"]         = tglToSql($this->put('tgl_masuk'));
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KARYAWAN_UPDATE",$param,'put', TRUE);
    }


    /**
     * [karyawan_sales_put description]
     * @return [type] [description]
     */
    public function karyawan_sales_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_SALES"]          = $this->post('kd_sales');
        $param["KD_HSALES"]         = $this->post('kd_hsales');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KARYAWAN_SALES_UPDATE",$param,'put', TRUE);
    }



    /**
     * [karyawan_delete description]
     * @return [type] [description]
     */
    public function karyawan_delete(){
        $param = array();
        $param["NIK"]               = $this->delete('nik');
        //$param["NAMA"]              = $this->delete('nama');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_KARYAWAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [customer_get description]
     * @return [type] [description]
     */
    public function customer_get($dlr=null){
        $param = array();$search='';
        if($this->get('kd_dealer')){
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if($this->get('guest_no')){
            $param["GUEST_NO"] = $this->get("guest_no");
        }
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("no_ktp")){
            $param["NO_KTP"]     = $this->get("no_ktp");
        }
        if($this->get("no_hp")){
            $param["NO_HP"]     = $this->get("no_hp");
        }
        if($this->get("tgl_lahir")){
            $param["TGL_LAHIR"]     = tglToSql($this->get("tgl_lahir"));
        }
        if($this->get("keyword")){
            if(!$dlr){$param = array();}
            $search = array(
                "KD_CUSTOMER"  => $this->get("keyword"),
                "NAMA_CUSTOMER"    => $this->get("keyword"),
                "NO_HP"     => $this->get("keyword"),
                "NO_KTP"    => $this->get("keyword")
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
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        if($dlr){
            $this->resultdata("MASTER_CUSTOMER_DLR",$param);
        }else{
            $this->resultdata("MASTER_CUSTOMER",$param);           
        }
    }
    /**
     * [customer_post description]
     * @return [type] [description]
     * Jika nama nya sudah pernah ada maka akan terblok
     * kode customer di generate otomatis tidak bisa sebagai key data
     */
    public function customer_post(){
        $param = array();
        $param["KD_CUSTOMER"]       = $this->post('kd_customer');
        $param["NAMA_CUSTOMER"]     = ($this->post('nama_customer'));
        $param["NO_HP"]             = $this->post('no_hp');
        $this->Main_model->data_sudahada($param,"MASTER_CUSTOMER");    
        $param["NO_KTP"]            = $this->post('no_ktp');
        $param["JENIS_KELAMIN"]     = $this->post('jenis_kelamin');
        $param["TGL_LAHIR"]         = tglToSql($this->post('tgl_lahir'));
        $param["TGL_PEMBUATAN_NPWP"]= ($this->post('tgl_pembuatan_npwp'));
        $param["NO_NPWP"]           = $this->post('no_npwp');
        $param["ALAMAT_SURAT"]      = ($this->post('alamat_surat'));
        $param["KELURAHAN"]         = $this->post('kelurahan');
        $param["KD_KECAMATAN"]      = $this->post('kd_kecamatan');
        $param["KD_KOTA"]           = $this->post('kd_kota');
        $param["KODE_POS"]          = $this->post('kode_possurat');
        $param["KD_PROPINSI"]       = $this->post('kd_propinsi');
        $param["KD_AGAMA"]          = $this->post('kd_agama');
        $param["PENGELUARAN"]       = $this->post('pengeluaran');
        $param["KD_PEKERJAAN"]      = $this->post('kd_pekerjaan');
        $param["KD_PENDIDIKAN"]     = $this->post('kd_pendidikan');
        $param["NAMA_PENANGGUNGJAWAB"]  = $this->post('nama_penanggungjawab');
        $param["NO_TELEPON"]        = $this->post('no_telepon');
        $param["STATUS_DIHUBUNGI"]  = $this->post('status_dihubungi');
        $param["EMAIL"]             = $this->post('email');
        $param["STATUS_RUMAH"]      = $this->post('status_rumah');
        $param["STATUS_NOHP"]       = $this->post('status_nohp');
        $param["AKUN_FB"]           = $this->post('akun_fb');
        $param["AKUN_TWITTER "]     = $this->post('akun_twitter');
        $param["AKUN_INSTAGRAM "]   = $this->post('akun_instagram');
        $param["AKUN_YOUTUBE"]      = $this->post('akun_youtube');
        $param["HOBI"]              = $this->post('hobi');
        $param["KARAKTERISTIK_KONSUMEN"] = $this->post('karakteristik_konsumen');
        $param["ID_REFFERAL"]       = $this->post('id_refferal');
        $param["UPLINE"]            = $this->post('upline');
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_CUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [customer_put description]
     * @return [type] [description]
     */
    public function customer_put(){
        $param = array();
        $param["KD_CUSTOMER"]       = $this->put('kd_customer');
        $param["NAMA_CUSTOMER"]     = ($this->put('nama_customer'));
        $param["JENIS_KELAMIN"]     = $this->put('jenis_kelamin');
        $param["TGL_LAHIR"]         = tglToSql($this->put('tgl_lahir'));
        $param["TGL_PEMBUATAN_NPWP"]= tglToSql($this->put('tgl_pembuatan_npwp'));
        $param["NO_KTP"]            = $this->put('no_ktp');
        $param["NO_NPWP"]           = $this->put('no_npwp');
        $param["ALAMAT_SURAT"]      = ($this->put('alamat_surat'));
        $param["KELURAHAN"]         = $this->put('kelurahan');
        $param["KD_KECAMATAN"]      = $this->put('kd_kecamatan');
        $param["KD_KOTA"]           = $this->put('kd_kota');
        $param["KODE_POS"]          = $this->put('kode_possurat');
        $param["KD_PROPINSI"]       = $this->put('kd_propinsi');
        $param["KD_AGAMA"]          = $this->put('kd_agama');
        $param["PENGELUARAN"]       = $this->put('pengeluaran');
        $param["KD_PEKERJAAN"]      = $this->put('kd_pekerjaan');
        $param["KD_PENDIDIKAN"]     = $this->put('kd_pendidikan');
        $param["NAMA_PENANGGUNGJAWAB"]  = $this->put('nama_penanggungjawab');
        $param["NO_HP"]             = $this->put('no_hp');
        $param["NO_TELEPON"]        = $this->put('no_telepon');
        $param["STATUS_DIHUBUNGI"]  = $this->put('status_dihubungi');
        $param["EMAIL"]             = $this->put('email');
        $param["STATUS_RUMAH"]      = $this->put('status_rumah');
        $param["STATUS_NOHP"]       = $this->put('status_nohp');
        $param["AKUN_FB"]           = $this->put('akun_fb');
        $param["AKUN_TWITTER "]     = $this->put('akun_twitter');
        $param["AKUN_INSTAGRAM "]   = $this->put('akun_instagram');
        $param["AKUN_YOUTUBE"]      = $this->put('akun_youtube');
        $param["HOBI"]              = $this->put('hobi');
        $param["KARAKTERISTIK_KONSUMEN"] = $this->put('karakteristik_konsumen');
        $param["ID_REFFERAL"]       = $this->put('id_refferal');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $param["UPLINE"]            = $this->put('upline');
        $param["NAMA_CUSTOMERLAMA"] = $this->put("nama_customerlama");
        $param["NO_HPLAMA"]         = $this->put("no_hplama");
        $this->resultdata("SP_MASTER_CUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * Update data customer from guestbook atau SPK
     * @return [type] [description]
     */
    public function customergb_put(){
        $param = array();
        $param["KD_CUSTOMER"] = $this->put('kd_customer');
        $param["NAMA_CUSTOMER"]     = ($this->put('nama_customer'));
        $param["NO_HP"] = $this->put('no_hp');
        $param["JENIS_KELAMIN"] = $this->put('jenis_kelamin');
        $param["TGL_LAHIR"]    = tglToSql($this->put('tgl_lahir'));
        $param["NO_KTP"]    = $this->put('no_ktp');
        $param["NO_NPWP"]   = $this->put('no_npwp');
        $param["ALAMAT_SURAT"] = ($this->put('alamat_surat'));
        $param["KELURAHAN"]  = $this->put('kelurahan');
        $param["KD_KECAMATAN"] = $this->put('kd_kecamatan');
        $param["KD_KOTA"]   = $this->put('kd_kota');
        $param["KD_PROPINSI"]   = $this->put('kd_propinsi');
        $param["KODE_POS"]    = $this->put('kode_possurat');
        $param["KD_AGAMA"]  = $this->put('kd_agama');
        $param["EMAIL"] = $this->put('email');
        $param["AKUN_FB"] = $this->put('akun_fb');
        $param["AKUN_TWITTER "] = $this->put('akun_twitter');
        $param["AKUN_INSTAGRAM "] = $this->put('akun_instagram');
        $param["AKUN_YOUTUBE"] = $this->put('akun_youtube');
        $param["HOBI"] = $this->put('hobi');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $param["UPLINE"]    = $this->put("upline");
        $this->resultdata("SP_MASTER_CUSTOMER_UPDATE_GB",$param,'put',TRUE);
    }
    /**
     * [customerview_get description]
     * @return [type] [description]
     */
    public function customerview_get(){
        $param = array();$search='';
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("no_ktp")){
            $param["NO_KTP"]     = $this->get("no_ktp");
        }
        if($this->get("tgl_lahir")){
            $param["TGL_LAHIR"]     = tglToSql($this->get("tgl_lahir"));
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_CUSTOMER"  => $this->get("keyword"),
                "NAMA_CUSTOMER"    => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_CUSTOMER_VIEW",$param);
    }
    /**
     * Update Customer dari guestbook
     * @return [type] [description]
     */
    public function customerdb_put(){
        $param = array();
        $param["KD_CUSTOMER"] = $this->put('kd_customer');
        $param["JENIS_KELAMIN"] = $this->put('jenis_kelamin');
        $param["TGL_LAHIR"]    = tglToSql($this->put('tgl_lahir'));
        $param["NO_KTP"]    = $this->put('no_ktp');
        $param["ALAMAT_SURAT"] = ($this->put('alamat_surat'));
        $param["KELURAHAN"]  = $this->put('kelurahan');
        $param["KD_KECAMATAN"] = $this->put('kd_kecamatan');
        $param["KD_KOTA"]   = $this->put('kd_kota');
        $param["KD_PROPINSI"]   = $this->put('kd_propinsi');
        $param["NO_HP"] = $this->put('no_hp');
        $param["EMAIL"] = $this->put('email');
        $param["KD_PEKERJAAN"] = $this->put('kd_pekerjaan');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_CUSTOMER_UPDATE_GBUP",$param,'put',TRUE);
    }

   /**
    * update customer dari appointment
    * @return [type] [description]
    */
    public function customerap_put(){
        $param = array();
        $param["KD_CUSTOMER"] = $this->put('kd_customer');
        //$param["NAMA_CUSTOMER"] = $this->post("nama_customer");
        $param["ALAMAT_SURAT"] = ($this->put('alamat_surat'));
        $param["KELURAHAN"]  = $this->put('kelurahan');
        $param["KD_KECAMATAN"] = $this->put('kd_kecamatan');
        $param["KD_KOTA"]   = $this->put('kd_kota');
        $param["KD_PROPINSI"]   = $this->put('kd_propinsi');
        $param["NO_HP"] = $this->put('no_hp');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $param["KODE_POS"] = $this->post("kode_pos");

        $this->resultdata("SP_MASTER_CUSTOMER_UPDATE_AP",$param,'put',TRUE);
    }
    /**
     * [customerso_post description]
     * Insert data customer dari proses so sparepart
     * @return [type] [description]
     */
    public function customerso_post(){
        $param = array();
        $param["KD_CUSTOMER"] = $this->post('kd_customer');
        $param["NAMA_CUSTOMER"] = ($this->post('nama_customer'));
        $param["NO_HP"] = $this->post('no_hp');
        $this->Main_model->data_sudahada($param,"MASTER_CUSTOMER"); 
        $param["ALAMAT_SURAT"] = ($this->post('alamat_surat'));

        $param["KELURAHAN"]  = $this->post('kelurahan');
        $param["KD_KECAMATAN"] = $this->post('kd_kecamatan');
        $param["KD_KOTA"]   = $this->post('kd_kota');
        $param["KD_PROPINSI"]   = $this->post('kd_propinsi');
        $param["CREATED_BY"]= $this->post("created_by")."| So SP";
        $param["KODE_POS"] = $this->post("kode_pos");

        $this->resultdata("SP_MASTER_CUSTOMER_INSERT_SO",$param,'post',TRUE);
    }

    /**
     * [customer_delete description]
     * @return [type] [description]
     */
    public function customer_delete(){
        $param = array();
        $param["KD_CUSTOMER"]     = $this->delete('kd_customer');
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_CUSTOMER_DELETE",$param,'delete',TRUE);
    }
    
    /**
     * [lokasidealer_get description]
     * @return [type] [description]
     */
    public function lokasidealer_get(){
        $param = array();$search='';
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"]     = $this->get("kd_lokasi");
        }
        if($this->get("nama_lokasi")){
            $param["NAMA_LOKASI"]     = $this->get("nama_lokasi");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_LOKASI"  => $this->get("keyword"),
                "NAMA_LOKASI"    => $this->get("keyword")
                );
        }
        if($this->get('kd_maindealer')){
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"] = $this->get("kd_dealer");
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
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->resultdata("MASTER_LOKASIDEALER",$param);
    }
    /**
     * [lokasidealer_post description]
     * @return [type] [description]
     */
    public function lokasidealer_post(){
        $param = array();
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_LOKASI"]     = $this->post('kd_lokasi');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_LOKASIDEALER");
        $param["NAMA_LOKASI"]   = $this->post('nama_lokasi');
        $param["ALAMAT"]        = $this->post('alamat');
        $param["CHANEL"]        = $this->post('chanel');
        $param["DEFAULTS"]      = $this->post('defaults');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_LOKASIDEALER_INSERT",$param,'post',TRUE);
    }
    /**
     * [lokasidealer_put description]
     * @return [type] [description]
     */
    public function lokasidealer_put(){
        $param = array();
        $param["KD_LOKASI"]     = $this->put('kd_lokasi');
        $param["NAMA_LOKASI"]   = ($this->put('nama_lokasi'));
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["KD_MAINDEALER"] = $this->put('kd_maindealer');
        $param["ALAMAT"]        = $this->put('alamat');
        $param["CHANEL"]        = $this->put('chanel');
        $param["DEFAULTS"]      = $this->put('defaults');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_LOKASIDEALER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [lokasidealer_delete description]
     * @return [type] [description]
     */
    public function lokasidealer_delete(){
        $param = array();
        $param["KD_LOKASI"]         = $this->delete('kd_lokasi');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_LOKASIDEALER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [activity_get description]
     * @return [type] [description]
     */
    public function activity_get(){
        $param = array();$search='';
        if($this->get("kd_activity")){
            $param["KD_ACTIVITY"]     = $this->get("kd_activity");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("nama_activity")){
            $param["NAMA_ACTIVITY"]     = $this->get("nama_activity");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_ACTIVITY"  => $this->get("keyword"),
                "KD_DEALER"    => $this->get("keyword"),
                "NAMA_ACTIVITY"    => $this->get("keyword")
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
        $this->resultdata("MASTER_ACTIVITY",$param);
    }
    /**
     * [activity_post description]
     * @return [type] [description]
     */
    public function activity_post(){
        $param = array();
        $param["KD_ACTIVITY"]       = $this->post('kd_activity');
        $param["KD_DEALER"]         = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_ACTIVITY");
        $param["NAMA_ACTIVITY"]     = ($this->post('nama_activity'));
        $param["JENIS_ACTIVITY"]    = $this->post('jenis_activity');
        $param["START_DATE"]    = tglToSql($this->post('start_date'));
        $param["END_DATE"]    = tglToSql($this->post('end_date'));
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_ACTIVITY_INSERT",$param,'post',TRUE);
    }
    /**
     * [activity_put description]
     * @return [type] [description]
     */
    public function activity_put(){
        $param = array();
        $param["KD_ACTIVITY"]       = $this->put('kd_activity');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["NAMA_ACTIVITY"]     = ($this->put('nama_activity'));
        $param["JENIS_ACTIVITY"]    = $this->put('jenis_activity');
        $param["START_DATE"]    = tglToSql($this->put('start_date'));
        $param["END_DATE"]    = tglToSql($this->put('end_date'));
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_ACTIVITY_UPDATE",$param,'put',TRUE);
    }
    /**
     * [activity_delete description]
     * @return [type] [description]
     */
    public function activity_delete(){
        $param = array();
        $param["KD_ACTIVITY"]     = $this->delete('kd_activity');
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_ACTIVITY_DELETE",$param,'delete',TRUE);
    }

    /**
     * [jabatan_get description]
     * @return [type] [description]
     */
    public function jabatan_get(){
        $param = array();$search='';
        if($this->get("kd_jabatan")){
            $param["KD_JABATAN"]     = $this->get("kd_jabatan");
        }
        if($this->get("nama_jabatan")){
            $param["NAMA_JABATAN"]     = $this->get("nama_jabatan");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_JABATAN"  => $this->get("keyword"),
                "NAMA_JABATAN"    => $this->get("keyword")
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
        //$this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_JABATAN",$param);
    }
    /**
     * [jabatan_post description]
     * @return [type] [description]
     */
    public function jabatan_post(){
        $param = array();
        $param["KD_JABATAN"]    = $this->post('kd_jabatan');
        $this->Main_model->data_sudahada($param,"MASTER_JABATAN");
        $param["NAMA_JABATAN"]  = $this->post('nama_jabatan');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_JABATAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [jabatan_put description]
     * @return [type] [description]
     */
    public function jabatan_put(){
        $param = array();
        $param["KD_JABATAN"]    = $this->put('kd_jabatan');
        $param["NAMA_JABATAN"]  = $this->put('nama_jabatan');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_JABATAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jabatan_delete description]
     * @return [type] [description]
     */
    public function jabatan_delete(){
        $param = array();
        $param["KD_JABATAN"]     = $this->delete('kd_jabatan');
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_JABATAN_DELETE",$param,'delete',TRUE);
    }
    /**
     * [pendidikan_get description]
     * @return [type] [description]
     */
    public function pendidikan_get(){
        $param = array();$search='';
        if($this->get("kd_pendidikan")){
            $param["KD_PENDIDIKAN"]     = $this->get("kd_pendidikan");
        }
        if($this->get("nama_institusi")){
            $param["NAMA_INSTITUSI"]     = $this->get("nama_institusi");
        }
        if($this->get("jurusan")){
            $param["JURUSAN"]     = $this->get("jurusan");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_PENDIDIKAN"  => $this->get("keyword"),
                "NAMA_INSTITUSI"    => $this->get("keyword"),
                "JURUSAN"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PENDIDIKAN",$param);
    }
    /**
     * [pendidikan_post description]
     * @return [type] [description]
     */
    public function pendidikan_post(){
        $param = array();
        $param["KD_PENDIDIKAN"] = $this->post('kd_pendidikan');
        $this->Main_model->data_sudahada($param,"MASTER_PENDIDIKAN");
        $param["NAMA_INSTITUSI"]   = $this->post('nama_institusi');
        $param["JURUSAN"]   = $this->post('jurusan');
        $param["GELAR"] = $this->post('gelar');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PENDIDIKAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [pendidikan_put description]
     * @return [type] [description]
     */
    public function pendidikan_put(){
        $param = array();
        $param["KD_PENDIDIKAN"]     = $this->put('kd_pendidikan');
        $param["NAMA_INSTITUSI"]    = $this->put('nama_institusi');
        $param["JURUSAN"]           = $this->put('jurusan');
        $param["GELAR"]             = $this->put('gelar');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PENDIDIKAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pendidikan_delete description]
     * @return [type] [description]
     */
    public function pendidikan_delete(){
        $param = array();
        $param["KD_PENDIDIKAN"]     = $this->delete('kd_pendidikan');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PENDIDIKAN_DELETE",$param,'delete',TRUE);
    }

    function pendidikanlevel_get(){
        $param = array();$search='';
        if($this->get("kd_pendidikan")){
            $param["KD_PENDIDIKAN"]     = $this->get("kd_pendidikan");
        }
        if($this->get("nama_pendidikan")){
            $param["NAMA_PENDIDIKAN"]     = $this->get("nama_pendidikan");
        }
        
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_PENDIDIKAN"  => $this->get("keyword"),
                "NAMA_PENDIDIKAN"    => $this->get("keyword")
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
        $this->resultdata("MASTER_PENDIDIKAN_LEVEL",$param);
    }

    /**
     * [pendidikanlevel_post description]
     * @return [type] [description]
     */
    public function pendidikanlevel_post(){
        $param = array();
        $param["KD_PENDIDIKAN"] = $this->post('kd_pendidikan');
        $param["NAMA_PENDIDIKAN"]   = $this->post('nama_pendidikan');
        $this->Main_model->data_sudahada($param,"MASTER_PENDIDIKAN");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PENDIDIKAN_LEVEL_INSERT",$param,'post',TRUE);
    }
    /**
     * [pendidikanlevel_put description]
     * @return [type] [description]
     */
    public function pendidikanlevel_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_PENDIDIKAN"]     = $this->put('kd_pendidikan');
        $param["NAMA_PENDIDIKAN"]   = $this->put('nama_pendidikan');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PENDIDIKAN_LEVEL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pendidikanlevel_delete description]
     * @return [type] [description]
     */
    public function pendidikanlevel_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PENDIDIKAN_LEVEL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [hobby_get description]
     * @return [type] [description]
     */
    public function hobby_get(){
        $param = array();$search='';
        if($this->get("kd_hobby")){
            $param["KD_HOBBY"]     = $this->get("kd_hobby");
        }
        if($this->get("nama_hobby")){
            $param["NAMA_HOBBY"]     = $this->get("nama_hobby");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_HOBBY"  => $this->get("keyword"),
                "NAMA_HOBBY"    => $this->get("keyword")
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
        $this->resultdata("MASTER_HOBBY",$param);
    }
    /**
     * [hobby_post description]
     * @return [type] [description]
     */
    public function hobby_post(){
        $param = array();
        $param["KD_HOBBY"] = $this->post('kd_hobby');
        $this->Main_model->data_sudahada($param,"MASTER_HOBBY");
        $param["NAMA_HOBBY"]   = $this->post('nama_hobby');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_HOBBY_INSERT",$param,'post',TRUE);
    }
    /**
     * [hobby_put description]
     * @return [type] [description]
     */
    public function hobby_put(){
        $param = array();
        $param["ID"]     = $this->put('id');
        $param["KD_HOBBY"]     = $this->put('kd_hobby');
        $param["NAMA_HOBBY"]    = $this->put('nama_hobby');
        $param["ROW_STATUS"]     = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_HOBBY_UPDATE",$param,'put',TRUE);
    }
    /**
     * [hobby_delete description]
     * @return [type] [description]
     */
    public function hobby_delete(){
        $param = array();
        $param["ID"]     = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_HOBBY_DELETE",$param,'delete',TRUE);
    }
    /**
     * [mobil_get description]
     * @return [type] [description]
     */
    public function mobil_get(){
        $param = array();$search='';
        if($this->get("no_polisi")){
            $param["NO_POLISI"]     = $this->get("no_polisi");
        }
        if($this->get("merek")){
            $param["MEREK"]     = $this->get("merek");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "NO_POLISI"  => $this->get("keyword"),
                "MEREK"    => $this->get("keyword")
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
        $this->resultdata("MASTER_MOBIL",$param);
    }
    /**
     * [mobil_post description]
     * @return [type] [description]
     */
    public function mobil_post(){
        $param = array();
        $param["NO_POLISI"]     = $this->post('no_polisi');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $this->Main_model->data_sudahada($param,"MASTER_MOBIL");
        $param["MEREK"]         = $this->post('merek');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_MOBIL_INSERT",$param,'post',TRUE);
    }
    /**
     * [mobil_put description]
     * @return [type] [description]
     */
    public function mobil_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["NO_POLISI"]         = $this->put('no_polisi');
        $param["MEREK"]             = $this->put('merek');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MOBIL_UPDATE",$param,'put',TRUE);
    }
    /**
     * [mobil_delete description]
     * @return [type] [description]
     */
    public function mobil_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_MOBIL_DELETE",$param,'delete',TRUE);
    }

    /**
     * [supir_get description]
     * @return [type] [description]
     */
    public function supir_get(){
        $param = array();$search='';
        if($this->get("kd_supir")){
            $param["KD_SUPIR"]     = $this->get("kd_supir");
        }
        if($this->get("nama_supir")){
            $param["NAMA_SUPIR"]     = $this->get("nama_supir");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_SUPIR"  => $this->get("keyword"),
                "NAMA_SUPIR"    => $this->get("keyword")
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
        $this->resultdata("MASTER_SUPIR",$param);
    }
    /**
     * [msupir_post description]
     * @return [type] [description]
     */
    public function supir_post(){
        $param = array();
        $param["KD_SUPIR"]      = $this->post('kd_supir');
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["NAMA_SUPIR"]    = ($this->post('nama_supir'));
        $param["NO_HP"]         = $this->post('no_hp');
        $this->Main_model->data_sudahada($param,"MASTER_SUPIR");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_SUPIR_INSERT",$param,'post',TRUE);
    }
    /**
     * [supir_put description]
     * @return [type] [description]
     */
    public function supir_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_SUPIR"]          = $this->put('kd_supir');
        $param["NAMA_SUPIR"]        = ($this->put('nama_supir'));
        $param["NO_HP"]             = $this->put('no_hp');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_SUPIR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [supir_delete description]
     * @return [type] [description]
     */
    public function supir_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_SUPIR_DELETE",$param,'delete',TRUE);
    }
    

    /**
     * [jeniscustomer_get description]
     * @return [type] [description]
     */
    public function jeniscustomer_get(){
        $param = array();$search='';
        if($this->get("jenis_customer")){
            $param["JENIS_CUSTOMER"]     = $this->get("jenis_customer");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "JENIS_CUSTOMER"  => $this->get("keyword")
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
        $this->resultdata("MASTER_JENISCUSTOMER",$param);
    }
    /**
     * [jeniscustomer_post description]
     * @return [type] [description]
     */
    public function jeniscustomer_post(){
        $param = array();
        $param["JENIS_CUSTOMER"]    = $this->post('jenis_customer');
        $this->Main_model->data_sudahada($param,"MASTER_JENISCUSTOMER");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_JENISCUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [jeniscustomer_put description]
     * @return [type] [description]
     */
    public function jeniscustomer_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["JENIS_CUSTOMER"]    = $this->put('jenis_customer');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_JENISCUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jeniscustomer_delete description]
     * @return [type] [description]
     */
    public function jeniscustomer_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_JENISCUSTOMER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [jenismotor_get description]
     * @return [type] [description]
     */
    public function jenismotor_get(){
        $param = array();$search='';
        if($this->get("jenis_motor")){
            $param["JENIS_MOTOR"]     = $this->get("jenis_motor");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "JENIS_MOTOR"  => $this->get("keyword")
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
        $this->resultdata("MASTER_JENISMOTOR",$param);
    }
    /**
     * [jenismotor_post description]
     * @return [type] [description]
     */
    public function jenismotor_post(){
        $param = array();
        $param["JENIS_MOTOR"]    = $this->post('jenis_motor');
        $this->Main_model->data_sudahada($param,"MASTER_JENISMOTOR");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_JENISMOTOR_INSERT",$param,'post',TRUE);
    }
    /**
     * [jeniscustomer_put description]
     * @return [type] [description]
     */
    public function jenismotor_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["JENIS_MOTOR"]    = $this->put('jenis_motor');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_JENISMOTOR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [jenismotor_delete description]
     * @return [type] [description]
     */
    public function jenismotor_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_JENISMOTOR_DELETE",$param,'delete',TRUE);
    }

    /**
     * [kegunaan_get description]
     * @return [type] [description]
     */
    public function kegunaan_get(){
        $param = array();$search='';
        if($this->get("kegunaan")){
            $param["KEGUNAAN"]     = $this->get("kegunaan");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KEGUNAAN"  => $this->get("keyword")
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
        $this->resultdata("MASTER_KEGUNAAN",$param);
    }
    /**
     * [kegunaan_post description]
     * @return [type] [description]
     */
    public function kegunaan_post(){
        $param = array();
        $param["KEGUNAAN"]          = $this->post('kegunaan');
        $this->Main_model->data_sudahada($param,"MASTER_KEGUNAAN");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_KEGUNAAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [kegunaan_put description]
     * @return [type] [description]
     */
    public function kegunaan_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KEGUNAAN"]          = $this->put('kegunaan');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_KEGUNAAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [kegunaan_delete description]
     * @return [type] [description]
     */
    public function kegunaan_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_KEGUNAAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [merkmotor_get description]
     * @return [type] [description]
     */
    public function merkmotor_get(){
        $param = array();$search='';
        if($this->get("merk_motor")){
            $param["MERK_MOTOR"]     = $this->get("merk_motor");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "MERK_MOTOR"  => $this->get("keyword")
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
        $this->resultdata("MASTER_MERKMOTOR",$param);
    }
    /**
     * [merkmotor_post description]
     * @return [type] [description]
     */
    public function merkmotor_post(){
        $param = array();
        $param["MERK_MOTOR"]          = $this->post('merk_motor');
        $this->Main_model->data_sudahada($param,"MASTER_MERKMOTOR");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_MERKMOTOR_INSERT",$param,'post',TRUE);
    }
    /**
     * [merkmotor_put description]
     * @return [type] [description]
     */
    public function merkmotor_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["MERK_MOTOR"]          = $this->put('merk_motor');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_MERKMOTOR_UPDATE",$param,'put',TRUE);
    }
    /**
     * [merkmotor_delete description]
     * @return [type] [description]
     */
    public function merkmotor_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_MERKMOTOR_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pengeluaran_get description]
     * @return [type] [description]
     */
    public function pengeluaran_get(){
        $param = array();$search='';
        if($this->get("range_pengeluaran")){
            $param["RANGE_PENGELUARAN"]     = $this->get("range_pengeluaran");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "RANGE_PENGELUARAN"  => $this->get("keyword")
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
        $this->resultdata("MASTER_PENGELUARAN",$param);
    }
    /**
     * [pengeluaran_post description]
     * @return [type] [description]
     */
    public function pengeluaran_post(){
        $param = array();
        $param["RANGE_PENGELUARAN"] = $this->post('range_pengeluaran');
        $this->Main_model->data_sudahada($param,"MASTER_PENGELUARAN");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PENGELUARAN_INSERT",$param,'post',TRUE);
    }
    /**
     * [pengeluaran_put description]
     * @return [type] [description]
     */
    public function pengeluaran_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["RANGE_PENGELUARAN"] = $this->put('range_pengeluaran');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PENGELUARAN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pengeluaran_delete description]
     * @return [type] [description]
     */
    public function pengeluaran_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PENGELUARAN_DELETE",$param,'delete',TRUE);
    }

    /**
     * [pengguna_get description]
     * @return [type] [description]
     */
    public function pengguna_get(){
        $param = array();$search='';
        if($this->get("pengguna")){
            $param["PENGGUNA"]     = $this->get("pengguna");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "PENGGUNA"  => $this->get("keyword")
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
        $this->resultdata("MASTER_PENGGUNA",$param);
    }
    /**
     * [pengguna_post description]
     * @return [type] [description]
     */
    public function pengguna_post(){
        $param = array();
        $param["PENGGUNA"]          = $this->post('pengguna');
        $this->Main_model->data_sudahada($param,"MASTER_PENGGUNA");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_PENGGUNA_INSERT",$param,'post',TRUE);
    }
    /**
     * [pengguna_put description]
     * @return [type] [description]
     */
    public function pengguna_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["PENGGUNA"]          = $this->put('pengguna');
        $param["ROW_STATUS"]        = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_PENGGUNA_UPDATE",$param,'put',TRUE);
    }
    /**
     * [pengguna_delete description]
     * @return [type] [description]
     */
    public function pengguna_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_PENGGUNA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [status_rumah_get description]
     * @return [type] [description]
     */
    public function status_rumah_get(){
        $param = array();$search='';
        if($this->get("status")){
            $param["STATUS"]     = $this->get("status");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "STATUS"  => $this->get("keyword")
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
        $this->resultdata("MASTER_STATUS_RUMAH",$param);
    }
    /**
     * [status_rumah_post description]
     * @return [type] [description]
     */
    public function status_rumah_post(){
        $param = array();
        $param["STATUS"]          = $this->post('status');
        $this->Main_model->data_sudahada($param,"MASTER_STATUS_RUMAH");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_STATUS_RUMAH_INSERT",$param,'post',TRUE);
    }

    /**
     * [status_hubungi_get description]
     * @return [type] [description]
     */
    public function status_hubungi_get(){
        $param = array();$search='';
        if($this->get("status")){
            $param["STATUS"]     = $this->get("status");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "STATUS"  => $this->get("keyword")
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
        $this->resultdata("MASTER_STATUS_HUBUNGI",$param);
    }
    /**
     * [status_hubungi_post description]
     * @return [type] [description]
     */
    public function status_hubungi_post(){
        $param = array();
        $param["STATUS"]          = $this->post('status');
        $this->Main_model->data_sudahada($param,"MASTER_STATUS_HUBUNGI");
        $param["CREATED_BY"]        = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_STATUS_HUBUNGI_INSERT",$param,'post',TRUE);
    }

    /**
     * [ring_area_get description]
     * @return [type] [description]
     */
    public function ring_area_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_kabupaten")){
            $param["KD_KABUPATEN"]     = $this->get("kd_kabupaten");
        }
        if($this->get("lokasi")){
            $param["LOKASI"]     = $this->get("lokasi");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_DEALER" => $this->get("keyword"),
                "KD_KABUPATEN" => $this->get("keyword"),
                "LOKASI" => $this->get("keyword")
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
        $this->resultdata("MASTER_RING_AREA",$param);
    }
    /**
     * [ring_area_post description]
     * @return [type] [description]
     */
    public function ring_area_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["KD_KABUPATEN"]  = $this->post('kd_kabupaten');
        $this->Main_model->data_sudahada($param,"MASTER_RING_AREA");
        $param["LOKASI"]        = $this->post('lokasi');
        $param["RING1"]         = $this->post('ring1');
        $param["RING2"]         = $this->post('ring2');
        $param["RING3"]         = $this->post('ring3');
        $param["OTHERS"]        = $this->post('others');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_RING_AREA_INSERT",$param,'post',TRUE);
    }
    /**
     * [ring_area_put description]
     * @return [type] [description]
     */
    public function ring_area_put(){
        $param = array();
        $param["ID"]            = $this->put('id');
        $param["KD_DEALER"]     = $this->put('kd_dealer');
        $param["KD_KABUPATEN"]  = $this->put('kd_kabupaten');
        $param["LOKASI"]        = $this->put('lokasi');
        $param["RING1"]         = $this->put('ring1');
        $param["RING2"]         = $this->put('ring2');
        $param["RING3"]         = $this->put('ring3');
        $param["OTHERS"]        = $this->put('others');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_RING_AREA_UPDATE",$param,'put',TRUE);
    }
    /**
     * [ring_area_delete description]
     * @return [type] [description]
     */
    public function ring_area_delete(){
        $param = array();
        $param["ID"]                = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_RING_AREA_DELETE",$param,'delete',TRUE);
    }
    
}