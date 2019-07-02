<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Service_reminder extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
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
    

    public function setupreminder_get(){
        $param = array();$search='';
        if($this->get("type_srv_next")){
            $param["TYPE_SRV_NEXT"]     = $this->get("type_srv_next");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "TYPE_SRV NEXT" => $this->get("type_srv_next")
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
        $this->resultdata("SETUP_SERVICE_REMINDER",$param);
    }

    public function setupreminder_post(){
        $param = array();
        $param["TYPE_SRV_NEXT"]    = $this->post('type_srv_next');
        $param["TGL_SRV_REMINDER"] = $this->post('tgl_srv_reminder');
        $param["TGL_SRV_NEXT"]  = ($this->post('tgl_srv_next'));
        $this->Main_model->data_sudahada($param,"SETUP_SERVICE_REMINDER");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_SERVICE_REMINDER_INSERT",$param,'post',TRUE);
    }

    public function setupreminder_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["TYPE_SRV_NEXT"]        = $this->put('type_srv_next');
        $param["TGL_SRV_REMINDER"]      = $this->put('tgl_srv_reminder');
        $param["TGL_SRV_NEXT"]        = ($this->put('tgl_srv_next'));
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_SERVICE_REMINDER_UPDATE",$param,'put',TRUE);
    }

    public function setupreminder_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_SERVICE_REMINDER_DELETE",$param,'delete',TRUE);
    }

}
?>