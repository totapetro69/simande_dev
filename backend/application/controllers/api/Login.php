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
class Login extends REST_Controller {

    /**
     * [__construct description]
     * @param string $config [description]
     */
    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Admin_model');
        $this->load->library('zetro_auth');
        $this->load->model('Login_model');
        $this->load->model('Main_model');
        $this->load->library("Curl");
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
     * Check User login 
     * @return [type] [description]
     */
    function check_get(){
        $users=array(); $data=array();$param=array();

        $username=$this->get('user_id');
        $password=($this->get('password'));
        $this->zetro_auth->set_password($username.$password);
        if($username=='' || $password==''){
            $this->response(['status' => FALSE,'message' => 'NIK / Password tidak sesuai'], REST_Controller::HTTP_NOT_FOUND);
        }else{

            /*if($this->zetro_auth->validatePassword($password)==FALSE){
                $this->response(['status' => FALSE,'message' => 'NIK / Password tidak sesuai'], REST_Controller::HTTP_NOT_FOUND);
            }*/
            $where=" where user_id='".$username."' 
                     and password='".$this->zetro_auth->get_password()."' and kd_status >-1";
                     //and password='".$this->zetro_auth->setPassword($password)."'";
            //$users=$this->Admin_model->cek_user_login($username,$password);
            $users=$this->Login_model->listusers($where);
           /**
            * Bandingkan data user dengan status users di webservice md
            * jika nik masih terdaftar di md -> boleh login else tidak
            */
           $param["link"] = 'list10';
           $param["param"]= $username;

           /*$data = json_decode($this->curl->simple_get(base_url()."index.php/api/login/webservice",$param));
           if(!$data){
                $this->response(['status'=> FALSE,'message'=> strtoupper($username)." sudah tidak aktif"],REST_Controller::HTTP_NOT_FOUND);
           }*/
           
        }
        if($users){
            $this->response($users,REST_Controller::HTTP_OK);   
        }else{
            //echo $users;
            $this->response(['status'=> FALSE,'message'=> 'NIK / Password tidak sesuai'],REST_Controller::HTTP_NOT_FOUND);
        }        
    }
    function checks_get(){
        $this->zetro_auth->set_password($this->get("password"));
        $param=array(
            'USER_ID'   => $this->get("user_id"),
            'PASSWORD'  => $this->zetro_auth->get_password()
        );

        $this->Main_model->set_statusdata($this->get('kd_status'));
        $this->Main_model->set_customcriteria($this->get("custom"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->resultdata("USERS",$param);
    }

    /**
    *   Tampilkan users base on nik atau userid
    *   jika variable nik/userid tidak kosong maka pencarian berdasarkan nik / userid
    *   jika variable id tidak kosong maka pencarian berdasarkan id user
    */
    function user_get(){
        $data=array();$param=array();
        if($this->get('user_id')){
            $param['USER_ID']=$this->get('user_id');
        }
        if($this->get('user_name')){
            $param['USER_NAME']=$this->get('user_name');
        }
        
        if($this->get('kd_dealer')){
            $param['KD_DEALER']=$this->get('kd_dealer');
        }
        if($this->get('type_users')){
            $param['TYPE_USERS']=$this->get('type_users');
        }
        //jika data dari field search yang di kirim
        $search='';
        if($this->get("keyword")){
            $param=array();
            $search=array(
                'USER_ID'=> $this->get("keyword"),
                'USER_NAME'=> $this->get("keyword")
            );
        }
        $this->Main_model->set_statusdata($this->get('kd_status'));
        
        
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_where_in($this->get('where_in'));
        $this->Main_model->set_whereinfield($this->get('where_in_field'));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("USERS",$param);
    }
    /**
    * tampilkann semua user yang aktif
    * jika parameter kd_status kosong akan menampilkan user yang aktif saja
    * kd_status= '' hanya user yang aktif
    * kd_status= < 1 hanya untuk yang tidak aktify
    */
    function userlist_get(){
     
     $param = array();$search='';
        if($this->get("kd_status")){
            $param["KD_STATUS"]     = $this->get("kd_status");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_STATUS" => $this->get("keyword")
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
        $this->resultdata("USERS",$param);

    }
    /**
     * input data user login baru
     * @return [type] [description]
     */
    function user_post(){
        //$insert = 0;
        $data   = array(); $check=array();
        $nik    = ($this->post('user_id'));
        //check ke webservice MD status keaktifan karyawan berdasarkan nik / user id user yang akan dibuak
        $data["link"] = 'list10';
        $data["param"]= $nik;
        $this->zetro_auth->set_password($this->post('user_id').$this->post("password"));
        
            
            //jika user belum ada maka simpan data
            $param['USER_ID']       = $this->post('user_id');
            $this->Main_model->data_sudahada($param,"USERS");
            $param['KD_GROUP']      = (($this->post('kd_group')));
            $param['KD_DEALER']     = $this->post('kd_dealer');
            $param['USER_NAME']     = $this->post('user_name');
            $param['PASSWORD' ]     = ($this->post('type_password') == 'old'? $this->post("password") : $this->zetro_auth->get_password());
            $param['KD_DIV']        = $this->post('kd_div');
            $param['KD_LEVEL']      = (($this->post('kd_level')));
            $param['KD_STATUS']     = (($this->post('kd_status')));
            $param['TYPE_USERS']    = $this->post('type_users');
            $param['KD_MAINDEALER'] = $this->post('kd_maindealer');
            $param['KD_LOKASI']     = $this->post('kd_lokasi');
            $param['APV_DOC']     = $this->post('apv_doc');
            $param['CREATED_BY']    = ($this->post('created_by'));
            $this->resultdata("SP_USERS_INSERT",$param,'post',TRUE);
        
    }
    
    /**
     * change password login
     * @return [type] [description]
     */
    public function changepwd_put(){
        if($this->put("password")){
            $param["USER_ID"] = $this->put("user_id");
            $this->zetro_auth->set_password($this->put("user_id").$this->put('password'));
            $param["PASSWORD"]= $this->zetro_auth->get_password();
            $param['LASTMODIFIED_BY']= $this->put('lastmodified_by');
            $this->resultdata("SP_USERS_CHANGEPWD",$param,'put',TRUE);
        }

    }
    /**
     * user update data
     * @return [type] [description]
     */
    function user_put(){
        $data=array(); $result="";

            $param["USER_ID"]        = $this->put('user_id');
            $param["KD_GROUP"]       = $this->put('kd_group');
            $param["USER_Name"]      = $this->put('user_name');
            $param["KD_DIV"]         = $this->put('kd_div');
            $param["KD_DEALER"]      = $this->put('kd_dealer');
            $param['KD_LEVEL']       = $this->put('kd_level');
            $param['KD_STATUS']      = $this->put('kd_status');
            $param['TYPE_USERS']     = $this->put('type_users');
            $param['KD_MAINDEALER']  = $this->put('kd_maindealer');
            $param['KD_LOKASI']      = $this->put('kd_lokasi');
            $param['APV_DOC']        = $this->put('apv_doc');
            $param['LASTMODIFIED_BY']= $this->put('lastmodified_by');
            $this->resultdata("SP_USERS_UPDATE",$param,'put',TRUE);
        
    }
    /**
     * [user_delete description]
     * @return [type] [description]
     */
    function user_delete(){
        $data=array(); $result="";

            $param["USER_ID"]        = $this->delete('user_id');
            $param['LASTMODIFIED_BY']= $this->delete('lastmodified_by');
            $this->resultdata("SP_USERS_DELETE",$param,'delete',TRUE);
        
    }
    /**
    * Lock unlock user
    * status=0 locked, status=1 unlocked
    */
    
    /**
     * [usergroup_get description]
     * @return [type] [description]
     */
    function usergroup_get(){
        $param = array();
        if($this->get('kd_group')){
            $param["KD_GROUP"] = $this->get('kd_group');
        }
        $search="";
        if($this->get("keyword")){
            $param=array();
            $search = array(
                "KD_GROUP" => $this->get("keyword"),
                "NAMA_GROUP" => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        if($this->get("field")){
            $this->Main_model->set_selectfield($this->get("field"));
        }else{
            $this->Main_model->set_selectfield("*");
        }
        
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));   
        $jointable=$this->get("jointable");
        $this->Main_model->set_jointable($jointable);
         $this->Main_model->set_orderby($this->get("orderby"));
        $this->resultdata("USERS_GROUP",$param);
    }
    /**
     * [usergroup_post description]
     * @return [type] [description]
     */
    function usergroup_post(){
        $data=array();$param=array();
        
        $param['KD_GROUP']   =$this->post('kd_group');
        //check jika data sudah ada
        $this->Main_model->data_sudahada($param,"USERS_GROUP");
        
        $param['NAMA_GROUP'] =$this->post('nama_group');
        $param['CREATED_BY'] =$this->post('created_by');
        $this->resultdata("SP_USERS_GROUP_INSERT",$param,'post',TRUE);
    }
    /**
     * [usergroup_put description]
     * @return [type] [description]
     */
    function usergroup_put(){
        $param=array();
        $param['KD_GROUP']       = $this->put('kd_group');
        $param['NAMA_GROUP']     = $this->put('nama_group');
        $param['ROW_STATUS']     = $this->put('row_status');
        $param['LASTMODIFIED_BY']= $this->put('lastmodified_by');
        $this->resultdata("SP_USERS_GROUP_UPDATE",$param,'put',TRUE);
    }
    /**
     * [usergroup_delete description]
     * @return [type] [description]
     */
    function usergroup_delete(){
        $param=array(); $result="";
        $param["KD_GROUP"]  = $this->delete('kd_group');
        $param['LASTMODIFIED_BY']= $this->delete('lastmodified_by');
        $this->resultdata("SP_USERS_GROUP_DELETE",$param,'delete',TRUE);
    }

    /**
     * [gender_get description]
     * @return [type] [description]
     */
    function gender_get(){
        $data   =array();
        $id     =$this->get('KD_GENDER');
        $where  =($id=='')?'':" Where KD_GENDER=".$id;
        $data   =$this->Admin_model->show_list("MASTER_GENDER",$where);
        $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    /**
     * [agama_get description]
     * @return [type] [description]
     */
    function agama_get(){
        $data     = array();
        $kd_agama = $this->get('KD_AGAMA');
        $where    = ($kd_agama == '')?'' : ' WHERE KD_AGAMA='.$kd_agama;
        $data     = $this->Admin_model->show_list("MASTER_AGAMA",$where);
        $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);
    }
    
    /**
     * [auths_get description]
     * @return [type] [description]
     */
    function auths_get(){
        $param=array();
        if($this->get("kd_modul")){
           $param["KD_MODUL"]  = $this->get("kd_modul"); 
        }
        if($this->get("kd_group")){
            $param["KD_GROUP"]  = $this->get("kd_group");        
        }
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        
        $this->Main_model->set_orderby($this->get('orderby'));
        $this->Main_model->set_selectfield($this->get('field'));
        // $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_groupby($this->get("groupby"));
         $this->Main_model->set_orderby($this->get("orderby"));
        $this->resultdata("USERS_GROUP_PRIVILAGES",$param);
    }
    /**
     * [auths_post description]
     * @return [type] [description]
     */
    function auths_post(){
        $parram=array();
        $param["KD_MODUL"] = $this->post("kd_modul");
        $param["KD_GROUP"] = $this->post("kd_group");
        $this->Main_model->data_sudahada($param,"USERS_GROUP_PRIVILAGES",TRUE);
        $param["C"] = $this->post("c");
        $param["E"] = $this->post("e");
        $param["V"] = $this->post("v");
        $param["P"] = $this->post("p");
        $param["CREATED_BY"] = $this->post("created_by");
        $this->resultdata("SP_USERS_GROUP_PRIVILAGES_INSERT",$param,'post',TRUE);
    }

    /**
     * [auths_put description]
     * @return [type] [description]
     */
    function auths_put(){
        $parram=array();
        $param["KD_MODUL"] = $this->put("kd_modul");
        $param["KD_GROUP"] = $this->put("kd_group");
        $param["C"] = $this->put("c");
        $param["E"] = $this->put("e");
        $param["V"] = $this->put("v");
        $param["P"] = $this->put("p");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $this->resultdata("SP_USERS_GROUP_PRIVILAGES_UPDATE",$param,'put',TRUE);
    }
    /**
     * [usergroup_delete description]
     * @return [type] [description]
     */
    function auths_delete(){
        $param=array(); $result="";
        $param["KD_MODUL"] = $this->delete("kd_modul");
        $param["KD_GROUP"]  = $this->delete('kd_group');
        $param['LASTMODIFIED_BY']= $this->delete('lastmodified_by');
        $this->resultdata("SP_USERS_GROUP_PRIVILAGES_DELETE",$param,'delete',TRUE);
    }

    /**
     * webservice proses ambil data dari main dealer
     * @return [type] [description]
     */
    function webservice_get($with_status=null){
        ini_set('max_execution_time', 0);
        if($this->get("link")){
            $link_ws=$this->get("link");
        }else{
            $this->response(array('status'=>FALSE,'message'=>'Bad Request'),REST_Controller::HTTP_BAD_REQUEST);
        }
        
        if($this->get("param")){
            $param_ws=$this->get("param");
        }else{
            $param_ws='';
        }
        
        $data=array();
        //$data=$this->curl->simple_get($this->Main_model->webservice($link_ws,$param_ws));
        $options = array(
            CURLOPT_URL =>($param_ws)? WS_URL.$param_ws.'/'.$link_ws: WS_URL.$link_ws,
            CURLOPT_RETURNTRANSFER => 1,     // return web page 
            CURLOPT_HEADER         => 0,    // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING       => "",       // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
            CURLOPT_TIMEOUT        => 120,      // timeout on response 
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
            CURLOPT_POST           => false,
            CURLOPT_FAILONERROR    => 1
        );
        $cUrl = curl_init();
        curl_setopt_array( $cUrl, $options );
        $data = curl_exec( $cUrl );
        //$header=curl_getinfo( $cUrl );
        $curError = curl_errno($cUrl);
        $curMSG   = curl_error($cUrl);
        $curURL = $cUrl;
        curl_close( $cUrl );
        if($with_status){
            $result=array(
                'status'=>($curError==0)?TRUE: FALSE,
                'message' => ($curError>0)?$curMSG: $data,
                'error'=>$curError,
                'errmsg'=>$curMSG,
            );
            $this->response($result,REST_Controller::HTTP_OK); 
        }else{
            if(!$data){ $data=FALSE;}
           $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);            
        }
    }
    function webservicesms_get($with_status=null){
        ini_set('max_execution_time', 0);
        $param["nohp"] = $this->get("nohp");
        $param["pesan"] = $this->get("pesan");
        $param["creaby"] = $this->get("created_by");
        
        $data=array();
        $options = array(
            CURLOPT_URL => WS_URL."kirimsms",
            CURLOPT_RETURNTRANSFER => 1,     // return web page 
            CURLOPT_HEADER         => 0,    // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING       => "",       // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
            CURLOPT_TIMEOUT        => 120,      // timeout on response 
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
            CURLOPT_POST           => false,
            CURLOPT_POSTFIELDS     => $param,
            CURLOPT_FAILONERROR    => 1
        );
        $cUrl = curl_init();
        curl_setopt_array( $cUrl, $options );
        $data = curl_exec( $cUrl );
        //$header=curl_getinfo( $cUrl );
        $curError = curl_errno($cUrl);
        $curMSG   = curl_error($cUrl);
        $curURL = $cUrl;
        curl_close( $cUrl );
        if($with_status){
            $result=array(
                'status'=>($curError==0)?TRUE: FALSE,
                'message' => ($curError>0)?$curMSG: $data,
                'error'=>$curError,
                'errmsg'=>$curMSG,
            );
            $this->response($result,REST_Controller::HTTP_OK); 
        }else{
            if(!$data){ $data=FALSE;}
           $this->response($data,($data)?REST_Controller::HTTP_OK:REST_Controller::HTTP_NOT_FOUND);            
        }
    }
    /**
     * [users_level_get description]
     * @return [type] [description]
     */
    public function users_level_get(){
        $param = array();$search='';
        if($this->get("kd_level")){
            $param["KD_LEVEL"]     = $this->get("kd_level");
        }
        if($this->get("nama_level")){
            $param["NAMA_LEVEL"]     = $this->get("nama_level");
        }
        if($this->get("keyword")){
            $param = array();
            $search = array(
                "KD_LEVEL"  => $this->get("keyword"),
                "NAMA_LEVEL"    => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->resultdata("USERS_LEVEL",$param);
    }

    /**
     * [users_level_post description]
     * @return [type] [description]
     */
    public function users_level_post(){
        $param = array();
        $param["KD_LEVEL"] = $this->post('kd_level');
        $this->Main_model->data_sudahada($param,"USERS_LEVEL");
        $param["NAMA_LEVEL"]   = $this->post('nama_level');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_USERS_LEVEL_INSERT",$param,'post',TRUE);
    }

    /**
     * [users_level_put description]
     * @return [type] [description]
     */
    public function users_level_put(){
        $param = array();
        $param["KD_LEVEL"] = $this->put('kd_level');
        $param["NAMA_LEVEL"]   = $this->put('nama_level');
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");

        $this->resultdata("SP_USERS_LEVEL_UPDATE",$param,'put',TRUE);
    }

    /**
     * [users_level_delete description]
     * @return [type] [description]
     */
    public function users_level_delete(){
        $param = array();
        $param["KD_LEVEL"]     = $this->delete('kd_level');
        $param["LASTMODIFIED_BY"]    = $this->delete("lastmodified_by");
        $this->resultdata("SP_USERS_LEVEL_DELETE",$param,'delete',TRUE);
    }

    public function users_app_get(){
        $param=array();
        $search="";
        if($this->get("user_id")){
            $param["USER_ID"]  = $this->get("user_id");
        }
        if($this->get("kd_doc")){
            $param["KD_DOC"]    = $this->get("kd_doc");
        }
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("USERS_APPROVAL",$param);
    }
    public function users_app_post(){
        $param=array();
        $param["USER_ID"]   = $this->post("user_id");
        $param["KD_DOC"]    = $this->post("kd_doc");
        $this->Main_model->data_sudahada($param,"USERS_APPROVAL");
        $param["APP_LEVEL"] = $this->post("app_level");
        $param["CREATED_BY"]= $this->post("created_by");
        $this->resultdata("SP_USERS_APPROVAL_INSERT",$param,'post',TRUE);
    }
    public function users_app_put(){
        $param=array();
        $param["USER_ID"]   = $this->put("user_id");
        $param["KD_DOC"]    = $this->put("kd_doc");
        $param["APP_LEVEL"] = $this->put("app_level");
        $param["LASTMODIFIED_BY"]= $this->put("lastmodified_by");
        $this->resultdata("SP_USERS_APPROVAL_UPDATE",$param,'put',TRUE);
    }
    public function users_app_delete(){
        $param=array();
        $param["USER_ID"]   = $this->delete("user_id");
        $param["KD_DOC"]    = $this->delete("kd_doc");
        $param["LASTMODIFIED_BY"]= $this->delete("lastmodified_by");
        $this->resultdata("SP_USERS_APPROVAL_DELETE",$param,'delete',TRUE);
    }

    function encrip_get(){
      echo "1 encript:".$this->zetro_auth->encrypt('1')."<br>";
      echo "2 decrypt:".$this->zetro_auth->actionDecrypt('akPwMKzWhsDnwsg4q9W6ig==')."<br>";
      echo $this->zetro_auth->decrypt($this->zetro_auth->encrypt('1'));
    }
    function config_app_get(){
        $param=array();
        if($this->get("kd_config")){
            $param["KD_CONFIG"] = $this->get("kd_config");
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("CONFIG_APP",$param);
    }

    /**
     * [users_guest_get description]
     * @return [type] [description]
     */
    public function users_guest_get(){
        $param=array();
        $search="";
        if($this->get("users_id")){
            $param["USERS_ID"]  = $this->get("users_id");
        }
        if($this->get("users_password")){
            $param["USERS_PASSWORD"]    = $this->get("users_password");
        }
        if($this->get("users_name")){
            $param["USERS_NAME"]    = $this->get("users_name");
        }
        if($this->get("users_email")){
            $param["USERS_EMAIL"]    = $this->get("users_email");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "USERS_ID" => $this->get("keyword"),
                "USERS_NAME" => $this->get("keyword")
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
        $this->resultdata("USERS_GUEST",$param);
    }

    /**
     * [users_guest_post description]
     * @return [type] [description]
     */
    public function users_guest_post(){
        $param=array();
        $param["USERS_ID"]          = $this->post("users_id");
        $param["USERS_NAME"]        = $this->post("users_name");
        $param["USERS_EMAIL"]       = $this->post("users_email");
        $this->Main_model->data_sudahada($param,"USERS_GUEST");
        $param["USERS_PASSWORD"]    = $this->post("users_password");
        $param["USERS_ALAMAT"]      = $this->post("users_alamat");
        $param["USERS_HP"]          = $this->post("users_hp");
        $param["USERS_GEO"]         = $this->post("users_geo");
        $param["CREATED_BY"]        = $this->post("created_by");
        $this->resultdata("SP_USERS_GUEST_INSERT",$param,'post',TRUE);
    }

    /**
     * [users_guest_put description]
     * @return [type] [description]
     */
    public function users_guest_put($change_pwd=null){
        $param=array();
        $param["USERS_ID"]          = $this->put("users_id");
        if(!$change_pwd){
            $param["USERS_PASSWORD"]    = $this->put("users_password");
            $param["USERS_NAME"]        = $this->put("users_name");
            $param["USERS_ALAMAT"]      = $this->put("users_alamat");
            $param["USERS_EMAIL"]       = $this->put("users_email");
            $param["USERS_HP"]          = $this->put("users_hp");
            $param["USERS_GEO"]         = $this->put("users_geo");
        }else{
            $param["USERS_PASSWORD"]    = $this->put("users_password");
        }
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");
        if($change_pwd){
            $this->resultdata("SP_USERS_GUEST_UPDATE_PWD",$param,'put',TRUE);
        }else{
            $this->resultdata("SP_USERS_GUEST_UPDATE",$param,'put',TRUE);
        }
        
    }

    /**
     * [users_guest_delete description]
     * @return [type] [description]
     */
    public function users_guest_delete(){
        $param=array();
        $param["USERS_ID"]          = $this->delete("users_id");
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_USERS_GUEST_DELETE",$param,'delete',TRUE);
    }
    /**
     * Change kd dealer of users
     */
    public function chd_get($field='KD_DEALER'){
        $param=array(
            'user_id' =>$this->get('uid'),
            'kd_dealer' => $this->get("d")
        );
        return;
    }
}
?>