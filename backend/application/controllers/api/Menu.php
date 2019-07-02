<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Menu extends REST_Controller {

    function __construct($config='rest') {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Admin_model');
        $this->load->model('Login_model');
        $this->load->library('zetro_auth');
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
    
    //main menu
    function mainmenu_get(){
		$data=array();$datax=array();
		$datax['d']			='t';
		if($this->get('namamodul')){
			$datax['namamodul']  =$this->get('namamodul'); 
		}	
    	$data=$this->Admin_model->show_list_array("refmodul",$datax);
    	$this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    function mainmenu_post(){
    	$data=array();
    	$data['namamodul']	=$this->post('namamodul');
    	//check if exists
    	if($this->Admin_model->record_exists('refmodul',$data)){
    		$this->response(['status'=>FALSE,'message' => $this->post('namamodul').' sudah ada'],REST_Controller::HTTP_FOUND);
    	}else{
    		$data['ordered']	=$this->post('ordered');
    		$data['hakakses']	=$this->post('hakakses');
    		$result=$this->Admin_model->replace_data('refmodul',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_OK);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    	}
    }
    function mainmenu_put(){
    	$data=array();
		$data['id']		=$this->post('id');
		$data['namamodul']	=$this->post('namamodul');
    	$data['ordered']	=$this->post('ordered');
    	$data['hakakses']	=$this->post('hakakses');
    	$result=$this->Admin_model->replace_data('refmodul',$data);
    		if($result){
    			$this->response($daresultta,REST_Controller::HTTP_ACCEPTED);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    }
    function mainmenu_delete(){
    	$data=array(); $result="";
        $id=$this->put('id');
        $data['d']=$this->put('aktif');
        $result=$this->Admin_model->update_table('refmodul','id',$id,$data);
        if($result){
    		$this->response($result,REST_Controller::HTTP_ACCEPTED);
    	}else{
    		$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    	}
    }
    //submenu / menu level 1
    function submenu_get(){
		$data=array();$datax=array();
		$datax['d']			='t';
		if($this->get('idmodul')!=''){
			$datax['idmodul']  =$this->get('idmodul'); 
		}	
    	$data=$this->Admin_model->show_list_array("refsubmodul",$datax);
    	$this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    function submenu_post(){
    	$data=array();
    	$data['idmodul']	 =$this->post('idmodul');
    	$data['namasubmodul']=$this->post('namasubmodul');
    	$data['parentid']	 ="0";
    	//check if exists
    	if($this->Admin_model->record_exists('refsubmodul',$data)){
    		$this->response(['status'=>FALSE,'message' => $this->post('namasubmodul').' sudah ada'],REST_Controller::HTTP_FOUND);
    	}else{
    		$data['kodemodul']	=$this->post('kodemodul');
    		$data['keterangan']	=$this->post('keterangan');
    		$result=$this->Admin_model->replace_data('refsubmodul',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_OK);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    	}
    }
    function submenu_put(){
    	$data=array();
		$data['id']		=$this->post('id');
		$data['idmodul']	 =$this->post('idmodul');
    	$data['namasubmodul']=$this->post('namasubmodul');
    	$data['parentid']	 ="0";
    	$data['kodemodul']	=$this->post('kodemodul');
    	$data['keterangan']	=$this->post('keterangan');

    	$result=$this->Admin_model->replace_data('refsubmodul',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_ACCEPTED);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    }
    function submenu_delete(){
    	$data=array(); $result="";
        $id=$this->put('id');
        $data['d']=$this->put('aktif');
        $result=$this->Admin_model->update_table('refsubmodul','id',$id,$data);
        if($result){
    		$this->response($data,REST_Controller::HTTP_ACCEPTED);
    	}else{
    		$this->response($data,REST_Controller::HTTP_BAD_REQUEST);
    	}
    }
    //subsubmenu / menu level 2
    function subsubmenu_get(){
		$data=array();$datax=array();
		$datax['d']			='t';
    	$datax['idmodul']	=$this->get('idmodul');
    	$datax['parentid']	=$this->get('parentid');
    	$data=$this->Admin_model->show_list_array("refsubmodul",$datax);
    	$this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    function subsubmenu_post(){
    	$data=array();
    	$data['idmodul']	 =$this->post('idmodul');
    	$data['namasubmodul']=$this->post('namasubmodul');
    	$data['parentid']	 =$this->post('parentid');
    	//check if exists
    	if($this->Admin_model->record_exists('refsubmodul',$data)){
    		$this->response(['status'=>FALSE,'message' => $this->post('namasubmodul').' sudah ada'],REST_Controller::HTTP_FOUND);
    	}else{
    		$data['kodemodul']	=$this->post('kodemodul');
    		$data['keterangan']	=$this->post('keterangan');
    		$result=$this->Admin_model->replace_data('refsubmodul',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_OK);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    	}
    }
    function subsubmenu_put(){
    	$data=array();
		$data['id']			 =$this->post('id');
		$data['idmodul']	 =$this->post('idmodul');
    	$data['namasubmodul']=$this->post('namasubmodul');
    	$data['parentid']	 =$this->post('parentid');
    	$data['kodemodul']	 =$this->post('kodemodul');
    	$data['keterangan']	 =$this->post('keterangan');
    	
    	$result=$this->Admin_model->replace_data('refsubmodul',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_ACCEPTED);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    }
    function subsubmenu_delete(){
    	$data  =array(); 
        $result="";
        $id=$this->put('id');
        $data['d']=$this->put('aktif');
        $result=$this->Admin_model->update_table('refsubmodul','id',$id,$data);
        if($result){
    		$this->response($result,REST_Controller::HTTP_ACCEPTED);
    	}else{
    		$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    	}
    }
    //otorisasi menu
    function sidemenu_get(){
        $data   =array();
        $where  =' AND parentid=0 ';
        $where .=str_replace('idmodul','m.id',$this->get('where'));
        $orderby=$this->get('orderby');
        $data   =$this->Login_model->usersmenu($where,$orderby);
        $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    function hidemenu_get(){
        $data   =array();
        $where  =' AND parentid > 0';
        $where .=' AND idsubmodul= '.$this->get('idsubmodul');
        $orderby=$this->get('orderby');
        $data   =$this->Login_model->usersmenu($where,$orderby);
        $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    function auth_get(){
    	$data=array();$datax=array();
    	$datax['hakakses']		='1';
    	$datax['idkelompok']	=$this->get('idkelompok');
    	$data=$this->Admin_model->show_list_array('refotorisasi',$datax);
    	$this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    function auth_post(){
    	$data=array();
    	$data['idkelompok']	=$this->post('idkelompok');
    	$data['idsubmodul']	=$this->post('idsubmodul');
    	$data['hakakses']	="1";
    	$data['keterangan']	=$this->post('keterangan');
    		$result=$this->Admin_model->replace_data('refotorisasi',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_OK);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    }
    function auth_put(){
    	$data=array();
    	$data['id']			=$this->post('id');
    	$data['idkelompok']	=$this->post('idkelompok');
    	$data['idsubmodul']	=$this->post('idsubmodul');
    	$data['hakakses']	=$this->post('hakakses');;
    	$data['keterangan']	=$this->post('keterangan');
    		$result=$this->Admin_model->replace_data('refotorisasi',$data);
    		if($result){
    			$this->response($result,REST_Controller::HTTP_OK);
    		}else{
    			$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    		}
    }
    function auth_delete(){
    	$data=array(); $result="";
        $id=$this->put('id');
        $data['d']=$this->put('aktif');
        $result=$this->Admin_model->update_table('refotorisasi','id',$id,$data);
        if($result){
    		$this->response($result,REST_Controller::HTTP_ACCEPTED);
    	}else{
    		$this->response($result,REST_Controller::HTTP_BAD_REQUEST);
    	}
    }

    /**
     * [users_area_get description]
     * @return [type] [description]
     */
    public function users_area_get(){
        $param=array();$search="";
        if($this->get('user_id')){
            $param["USER_ID"]= $this->get('user_id');
        }
        if($this->get('kd_dealer')){
            $param["KD_DEALER"]= $this->get('kd_dealer');
        }
        if($this->get('auth_status')){
            $param["AUTH_STATUS"]= $this->get('auth_status');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "USER_ID"  => $this->get("keyword"),
                "AUTH_STATUS"    => $this->get("keyword")
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
        $this->resultdata("USERS_AREA",$param);
    }
    
    /**
     * [users_area_post description]
     * @return [type] [description]
     */
    public function users_area_post(){
        $param = array();
        $param["USER_ID"]           = $this->post("users_id");
        $param["KD_DEALER"]         = $this->post("kd_dealer");
        $this->Main_model->data_sudahada($param,"USERS_AREA", TRUE);
        $param["AUTH_STATUS"]       = $this->post("auth_status");
        $param["CREATED_BY"]        = $this->post("created_by");    
        $this->resultdata("SP_USERS_AREA_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [users_area_put description]
     * @return [type] [description]
     */
    public function users_area_put(){
        $param = array();
        $param["USER_ID"]           = $this->put("users_id");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["AUTH_STATUS"]       = $this->put("auth_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_USERS_AREA_UPDATE",$param,'put',TRUE);
    }


    /**
     * [users_area_put description]
     * @return [type] [description]
     */
    public function users_area_status_put(){
        $param = array();
        $param["USER_ID"]           = $this->put("users_id");
        $param["KD_DEALER"]         = $this->put("kd_dealer");
        $param["AUTH_STATUS"]       = $this->put("auth_status");
        $param["ROW_STATUS"]       = $this->put("row_status");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_USERS_AREA_STATUS_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [users_area_delete description]
     * @return [type] [description]
     */
    public function users_area_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_USERS_AREA_DELETE",$param,'delete',TRUE);
    }

    /**
     * [users_approval_get description]
     * @return [type] [description]
     */
    public function users_approval_get(){
        $param=array();$search="";
        if($this->get('users_id')){
            $param["USER_ID"]= $this->get('users_id');
        }
        if($this->get('kd_doc')){
            $param["KD_DOC"]= $this->get('kd_doc');
        }
        if($this->get('app_level')){
            $param["APP_LEVEL"]= $this->get('app_level');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "USER_ID"  => $this->get("keyword"),
                "APP_LEVEL"    => $this->get("keyword")
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
        $this->resultdata("USERS_APPROVAL",$param);
    }
    
    /**
     * [users_approval_post description]
     * @return [type] [description]
     */
    public function users_approval_post(){
        $param = array();
        $param["USER_ID"]           = $this->post("users_id");
        $param["KD_DOC"]            = $this->post("kd_doc");
        $this->Main_model->data_sudahada($param,"USERS_APPROVAL");
        $param["APP_LEVEL"]         = $this->post("app_level");
        $param["CREATED_BY"]        = $this->post("created_by");    
        $this->resultdata("SP_USERS_APPROVAL_INSERT",$param,'post',TRUE);
    }
    
    /**
     * [users_approval_put description]
     * @return [type] [description]
     */
    public function users_approval_put(){
        $param = array();
        $param["USER_ID"]           = $this->put("users_id");
        $param["KD_DOC"]            = $this->put("kd_doc");
        $param["APP_LEVEL"]         = $this->put("app_level");
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        $this->resultdata("SP_USERS_APPROVAL_UPDATE",$param,'put',TRUE);
    }
    
    /**
     * [users_approval_delete description]
     * @return [type] [description]
     */
    public function users_approval_delete(){
        $param = array();
        $param["ID"]                = $this->delete("id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_USERS_APPROVAL_DELETE",$param,'delete',TRUE);
    }
    public function modul_approval_get(){
        $param=array();$search="";
        if($this->get('kd_modul')){
            $param["KD_MODUL"]= $this->get('kd_modul');
        }
        if($this->get('level_apv')){
            $param["LEVEL_APV"]= $this->get('level_apv');
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "USER_ID"  => $this->get("keyword"),
                "APP_LEVEL"    => $this->get("keyword")
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
}
?>