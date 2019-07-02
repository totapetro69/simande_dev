<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Rekap extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
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
     * [spk_get description]
     * @return [type] [description]
     */
public function rekap_insentif_get(){
       $param = array();$search='';
     
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }   

        if($this->get("personal_jabatan")){
            $param["PERSONAL_JABATAN"]     = $this->get("personal_jabatan");
        }        
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        
        $this->resultdata("TRANS_REKAP_V",$param);
        
    }

    public function rekap_insentif_kops_get(){
       $param = array();$search='';
     
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }   

        if($this->get("personal_jabatan")){
            $param["PERSONAL_JABATAN"]     = $this->get("personal_jabatan");
        }        
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        
        $this->resultdata("TRANS_REKAP_OPS_V",$param);
        
    }

    public function rekap_k_insentif_get(){
       $param = array();$search='';
     
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }   

        if($this->get("personal_jabatan")){
            $param["PERSONAL_JABATAN"]     = $this->get("personal_jabatan");
        }        
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        
        $this->resultdata("TRANS_REKAP_KS_V",$param);
        
    }

     public function rekap_ins_k_sales_get($unit=null){
        $param=array();$search='';

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
        $this->Main_model->set_custom_query($this->Custom_model->rekap_ins_k_sales($param));
       
        $this->resultdata("SP_LAPORAN_REKAP_INS_KS",$param,'get',TRUE);
    }
    
    public function rekap_ins_k_counter_get($unit=null){
        $param=array();$search='';

        if($this->input->get("kd_dealer")){
            $param["kd_dealer"]     = $this->input->get("kd_dealer");
        }else{
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }       

        if ($this->input->get("tgl_awal")){
            $param["start_date"] = $this->input->get("tgl_awal");
        } else {
            $param["start_date"] = date('Y-m-d', strtotime('first day of this month'));
        } 
        if ($this->input->get("tgl_akhir")){
            $param["end_date"] = $this->input->get("tgl_akhir");
        } else {
            $param["end_date"] = date('Y-m-d');
        } 
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
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
        $this->Main_model->set_custom_query($this->Custom_model->rekap_ins_k_counter($param["kd_dealer"],$param["start_date"],$param["end_date"]));
       
        $this->resultdata("TRANS_REKAP_INSCOUNTER_V",$param,'get',TRUE);
    }

     public function rekap_ins_ksp_get($unit=null){
        $param=array();$search='';

        if($this->input->get("kd_dealer")){
            $param["kd_dealer"]     = $this->input->get("kd_dealer");
        }else{
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }       

        if ($this->input->get("start_date")){
            $param["start_date"] = $this->input->get("start_date");
        } else {
            $param["start_date"] = date('Y-m-d', strtotime('first day of this month'));
        } 
        if ($this->input->get("end_date")){
            $param["end_date"] = $this->input->get("end_date");
        } else {
            $param["end_date"] = date('Y-m-d');
        } 
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
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
        $this->Main_model->set_custom_query($this->Custom_model->rekap_ins_ksp($param["kd_dealer"],$param["start_date"],$param["end_date"]));
       
        $this->resultdata("TRANS_REKAP_V",$param,'get',TRUE);
    }

    public function rekap_penalty_get(){
       $param = array();$search='';
     
        if($this->input->get("kd_dealer")){
            $param["kd_dealer"]     = $this->input->get("kd_dealer");
        }else{
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }       

        if ($this->input->get("tgl_awal")){
            $param["start_date"] = $this->input->get("tgl_awal");
        } else {
            $param["start_date"] = date('Y-m-d', strtotime('first day of this month'));
        } 
        if ($this->input->get("tgl_akhir")){
            $param["end_date"] = $this->input->get("tgl_akhir");
        } else {
            $param["end_date"] = date('Y-m-d');
        } 
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        $this->Main_model->set_custom_query($this->Custom_model->rekap_penalty($param["kd_dealer"],$param["start_date"],$param["end_date"]));
        $this->resultdata("TRANS_REKAP_INSCOUNTER_V",$param,'get',true);
        
    }

     public function penalty_ksp_get(){
       $param = array();$search='';
     
        if($this->input->get("kd_dealer")){
            $param["kd_dealer"]     = $this->input->get("kd_dealer");
        }else{
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }       

        if ($this->input->get("tgl_awal")){
            $param["start_date"] = $this->input->get("tgl_awal");
        } else {
            $param["start_date"] = date('Y-m-d', strtotime('first day of this month'));
        } 
        if ($this->input->get("tgl_akhir")){
            $param["end_date"] = $this->input->get("tgl_akhir");
        } else {
            $param["end_date"] = date('Y-m-d');
        } 
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        $this->Main_model->set_custom_query($this->Custom_model->penalty_ksp($param["kd_dealer"],$param["start_date"],$param["end_date"]));
        $this->resultdata("TRANS_REKAP_INSCOUNTER_V",$param,'get',true);
        
    }

    public function rekap_insentif_counter_get(){
       $param = array();$search='';
     
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }        
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        
        $this->resultdata("TRANS_REKAP_INSCOUNTER_V",$param);
        
    }

     public function rekap_insentif_ksp_get(){
       $param = array();$search='';
     
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }        
        
        if($this->get("keyword")){
            $param=array();
            $search= array(
               
                "KD_DEALER" =>$this->get("keyword"),
              
            );
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
        
        $this->resultdata("TRANS_REKAP_INSCOUNTER_V",$param);
        
    }
}