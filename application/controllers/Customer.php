<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    var $API;

    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->helper("zetro_helper");
    }

    //Customer
    /**
     * [customer description]
     * @return [type] [description]
     */
    public function customer($modal=null,$dataOnly=null) {
        $data = array();
        $defaultDealer=($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
        $param =array(
            'keyword'=> $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'custom'    => "LEN(NAMA_CUSTOMER)>0"
        );
        
        if(!$modal){
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 15;       
            $param['orderby']= 'ID DESC';
        }else{
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 100;
            $param['orderby']= 'NAMA_CUSTOMER';
        }
        $param["kd_dealer"] = $defaultDealer;
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer/true", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        // print_r($data['list']->message);exit(); 
        if($dataOnly){
           $param= array(
                "kd_dealer" => $defaultDealer,
                "orderby"   =>"NAMA_CUSTOMER"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer/true", $param));
            if($data["list"]){
                echo json_encode($data["list"]);
                exit();
            }
        }      
        if($modal){
            $this->load->view('sales/guest_book_cetak', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }else{
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);

            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();

            $this->template->site('master/master_customer', $data);
        }
    }

    /**
     * [add_customer description]
     */
    public function add_customer() {
        $this->auth->validate_authen('customer/customer');
        $paramcustomer["sales_status"] = "A";
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["genders"]    = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $data["propinsi"]   = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["agamas"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/agama"));
        $data["pekerjaans"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));
        $data["status"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu"));
        /*$this->load->view('form_tambah/add_customer', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));*/
        $this->template->site('form_tambah/add_customer_new', $data);
    }

    /**
     * [add_customer_simpan description]
     */
    public function add_customer_simpan() {
        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'required|trim');
        //$this->form_validation->set_rules('kd_gender', 'Jenis Kelamin', 'required|trim');
        //$this->form_validation->set_rules('alamat_surat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('no_ktp', 'Nomor KTP', 'required|trim');
        //$this->form_validation->set_rules('nama_penanggungjawab', 'Nama Penanggung Jawab', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->template->site('form_tambah/add_customer_new', $data);
        } else {
            $param = array(
                'kd_customer' => $this->getKDCustomer(),
                'nama_customer' => ($this->input->post("nama_customer"))?$this->input->post("nama_customer"):$this->input->post("nama_customer_a"),
                'jenis_kelamin' => $this->input->post("kd_gender"),
                'tgl_lahir' => $this->input->post("tgl_lahir"),
                'tgl_pembuatan_npwp' => $this->input->post("tgl_pembuatan_npwp"),
                'no_ktp' => $this->input->post("no_ktp"),
                'no_npwp' => $this->input->post("no_npwp"),
                'alamat_surat' => $this->input->post("alamat_surat"),
                'kelurahan' => $this->input->post("kd_desa"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kd_kota' => $this->input->post("kd_kabupaten"),
                'kode_possurat' => $this->input->post("kode_possurat"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_agama' => $this->input->post("kd_agama"),
                'kd_pekerjaan' => $this->input->post("kd_pekerjaan"),
                'kecamatan' => $this->input->post("kecamatan"),
                'pengeluaran' => $this->input->post("pengeluaran"),
                'kd_pendidikan' => $this->input->post("kd_pendidikan"),
                'nama_penanggungjawab' => $this->input->post("nama_penanggungjawab"),
                'no_hp' => $this->input->post("no_hp"),
                'no_telepon' => $this->input->post("no_telepon"),
                'status_dihubungi' => $this->input->post("nama_metode"),
                'email' => $this->input->post("email"),
                'status_rumah' => $this->input->post("status_rumah"),
                'status_nohp' => $this->input->post("status_nohp"),
                'akun_fb' => $this->input->post("akun_fb"),
                'akun_twitter' => $this->input->post("akun_twitter"),
                'akun_instagram' => $this->input->post("akun_instagram"),
                'akun_youtube' => $this->input->post("akun_youtube"),
                'hobi' => $this->input->post("hobi"),
                'karakteristik_konsumen' => $this->input->post("karakteristik_konsumen"),
                'id_refferal' => $this->input->post("kd_sales"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/customer", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('customer/customer'));
        }
    }

    /**
     * [edit_customer description]
     * @param  [type] $kd_customer [description]
     * @return [type]              [description]
     */
    public function edit_customer($kd_customer) {
        $this->auth->validate_authen('customer/customer');
        $param = array(
            'kd_customer' => $kd_customer
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $param));
        $paramcustomer["sales_status"] = "A";
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["genders"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["agamas"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/agama"));
        $data["pekerjaans"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu"));
        /*$this->load->view('form_edit/edit_customer', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));*/
        $this->template->site('form_edit/edit_customer_new', $data);
    }

    /**
     * [update_customer description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update_customer($id) {
        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'required|trim');
        //$this->form_validation->set_rules('kd_gender', 'Jenis Kelamin', 'required|trim');
        //$this->form_validation->set_rules('alamat_surat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('no_ktp', 'Nomor KTP', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_customer' =>$id,// $this->input->post("kd_customer"),
                'nama_customer' => ($this->input->post("nama_customer"))?$this->input->post("nama_customer"):$this->input->post("nama_customer_a"),
                'jenis_kelamin' => $this->input->post("kd_gender"),
                'tgl_lahir' => $this->input->post("tgl_lahir"),
                'tgl_pembuatan_npwp' => $this->input->post("tgl_pembuatan_npwp"),
                'no_ktp' => $this->input->post("no_ktp"),
                'no_npwp' => $this->input->post("no_npwp"),
                'alamat_surat' => $this->input->post("alamat_surat"),
                'kelurahan' => $this->input->post("kd_desa"),
                'kd_kota' => $this->input->post("kd_kabupaten"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kode_possurat' => $this->input->post("kode_possurat"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_agama' => $this->input->post("kd_agama"),
                'kd_pekerjaan' => $this->input->post("kd_pekerjaan"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'pengeluaran' => $this->input->post("pengeluaran"),
                'kd_pendidikan' => $this->input->post("kd_pendidikan"),
                'nama_penanggungjawab' => $this->input->post("nama_penanggungjawab"),
                'no_hp' => $this->input->post("no_hp"),
                'no_telepon' => $this->input->post("no_telepon"),
                'status_dihubungi' => $this->input->post("nama_metode"),
                'email' => $this->input->post("email"),
                'status_rumah' => $this->input->post("status_rumah"),
                'status_nohp' => $this->input->post("status_nohp"),
                'akun_fb' => $this->input->post("akun_fb"),
                'akun_twitter' => $this->input->post("akun_twitter"),
                'akun_instagram' => $this->input->post("akun_instagram"),
                'akun_youtube' => $this->input->post("akun_youtube"),
                'hobi' => $this->input->post("hobi"),
                'karakteristik_konsumen' => $this->input->post("karakteristik_konsumen"),
                'upline' => $this->input->post("upline"),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id'),
                'nama_customerlama' => $this->input->post("nama_customerlama"),
                'no_hplama'     => $this->input->post("no_hplama")
            );
            //print_r($param);
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/customer", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    /**
     * [customerdetail description]
     * @return [type] [description]
     */
    public function customerdetail($fromPart=null,$detailpasti=null) {
        $result = "";
        if($fromPart=="1"){
            $param=array(
                'kd_customer'   => $this->input->post("kd_customer"),
                'jointable'     => array(array("MASTER_DESA D","D.KD_DESA=MASTER_CUSTOMER.KELURAHAN","LEFT")),
                'field'         => "MASTER_CUSTOMER.*,D.KODE_POS"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $param));
            if ($data) {
                if($data->totaldata>0){
                    $result = json_encode($data);
                }
            }
        }elseif ($fromPart=="2") {
            $param=array(
                'nama_customer'   => $this->input->post("nama_customer"),
                'no_hp'         => $this->input->post("no_hp"),
                'jointable'     => array(array("MASTER_DESA D","D.KD_DESA=MASTER_CUSTOMER.KELURAHAN","LEFT")),
                'field'         => "MASTER_CUSTOMER.*,D.KODE_POS"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $param));
            if ($data) {
                if($data->totaldata>0){
                    $result = json_encode($data);
                }
            }
        }else{
            $param = array(
                'jointable'     => array(array("MASTER_PEKERJAAN PK", "PK.KD_PEKERJAAN=MASTER_CUSTOMER.KD_PEKERJAAN", "LEFT")),
                'field'         => "MASTER_CUSTOMER.*,PK.NAMA_PEKERJAAN",
                "nama_customer" => $this->input->post("nama_customer")
            );
            if(!$detailpasti){
                if(!$this->input->post("no_hp") ){
                    //&& !$this->input->post("tgl_lahir") && !$this->input->post("nama_customer")){
                    $data = array(
                        'status' => false,
                        'message' => validation_errors()
                    );
                    //$result=json_encode($data);

                }else{
                    if($this->input->post("no_hp")){
                        $param["no_hp"]=$this->input->post("no_hp");
                    }

                    if($this->input->post("nama_customer")){
                        $param["nama_customer"]=$this->input->post("nama_customer");
                    }
                    
                }
            }else{
                //unset($param['nama_customer']);
                $param['kd_customer'] = $this->input->post("kd_customer");
                if($this->input->post("no_hp")){
                    $param["no_hp"]=$this->input->post("no_hp");
                }
            }
            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $param));
            if ($data) {
                $result = json_encode($data);
            }
                    
        }
        
        echo $result;
    }

    /**
     * [delete_customer description]
     * @param  [type] $kd_customer [description]
     * @return [type]              [description]
     */
    public function delete_customer($kd_customer) {
        $param = array(
            'kd_customer' => $kd_customer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/customer", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('customer/customer')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    /**
     * [customer_typeahead description]
     * @return [type] [description]
     */
    public function customer_typeahead($kd_dealer='',$forInputPicker='') {
        $param=array(
            "jointable" => array(
                array("MASTER_PROPINSI P", "P.KD_PROPINSI=MASTER_CUSTOMER.KD_PROPINSI", "LEFT"),
                array("MASTER_KABUPATEN K", "K.KD_KABUPATEN=MASTER_CUSTOMER.KD_KOTA", "LEFT"),
                array("MASTER_KECAMATAN C", "C.KD_KECAMATAN=MASTER_CUSTOMER.KD_KECAMATAN", "LEFT"),
                array("MASTER_DESA D", "D.KD_DESA=MASTER_CUSTOMER.KELURAHAN", "LEFT")),
            "field" => "KD_CUSTOMER,NAMA_CUSTOMER,NO_KTP,ALAMAT_SURAT,D.NAMA_DESA,C.NAMA_KECAMATAN,K.NAMA_KABUPATEN,P.NAMA_PROPINSI,NO_HP",
            "groupby" =>TRUE,
            "orderby" =>"NAMA_CUSTOMER,KD_CUSTOMER",
            "custom"  =>"MASTER_CUSTOMER.KD_KOTA IN(SELECT DISTINCT KD_KABUPATEN FROM MASTER_DEALER WHERE KD_DEALER='".$this->session->userdata("kd_dealer")."')"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer",$param));
        //var_dump($data);
        if ($data) {
            if(!$forInputPicker){
                if ($data->totaldata>0) {

                    foreach ($data["list"]->message as $key => $message) {
                        $data_message[0][$key] = $message->KD_CUSTOMER;
                        $data_message[1][$key] = $message->NAMA_CUSTOMER;
                    }
                    $result['keyword'] = array_merge($data_message[0], $data_message[1]);

                    $this->output->set_output(json_encode($result));
                }
            }else{
                echo json_encode($data->message);
            }
        }
    }

    function json_customer($debug=null){
        ini_set('max_execution_time', 120);
        $result=array();
        $param=array(
            'field' => "MAX(KD_CUSTOMER) AS KD_CUSTOMER,UPPER(NAMA_CUSTOMER) AS NAMA_CUSTOMER,NO_KTP,NO_HP",
            'orderby'=> "ID DESC,NAMA_CUSTOMER",
            'custom' => "(LEN(NAMA_CUSTOMER) >0 AND LEN(KD_CUSTOMER)>0) ",
            'groupby_text' => "NAMA_CUSTOMER,NO_KTP,NO_HP,ID",
            'limit' => 200,
            'offset' =>0
        );
        ///*AND LTRIM((SELECT dbo.fnSplitColumn(CREATED_BY,2,'| ')))='".$this->session->userdata("kd_dealer")."'*/ 
        $data= json_decode($this->curl->simple_get(API_URL . "/api/master_general/customerview",$param));
        if($debug){var_dump($data);print_r($param);exit();}
        if($data){
            $result = $data;
        }
        //print_r($data->param);
       (write_file("assets/uploads/customer_".$this->session->userdata("user_id").".json",json_encode($result),'wb'));
    }
    function json_read(){
        $sleep = isset($_GET['sleep']) ? intval($_GET['sleep']) : 0;
        if($sleep > 3 || $sleep < 0)    $sleep = 0;
        if($sleep)
        sleep($sleep);
        $msg = '';
        $data = [];
        try{
            $count = 0;
            $prev_page = 1;
            $next_page = 1;
            $q = isset($_GET['q']) ? $_GET['q'] : '';
            $p = isset($_GET['p']) ? $_GET['p'] : '';   // Page
            if($p){
                $list = json_decode(file_get_contents('assets/uploads/customer_'.$this->session->userdata("user_id").'.json'), true)['message'];
                $count = count($list);
                $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;   // Per Page
                if($per_page < 1)   $per_page = 10;
                $first_page = 1;
                $last_page = ceil($count / $per_page);
                if($p < $first_page) $p = $first_page;
                if($p > $last_page) $p = $last_page;
                $prev_page = $p > 1 ? ($p - 1) : 1;
                $next_page = $p < $last_page ? ($p + 1) : $last_page;
                $i = 0;
                foreach($list as $v){
                    if ($i >= (($p - 1) * $per_page) && $i < ( $p * $per_page) ){
                        $data[] = $v;
                    }
                    $i++;
                }
            }
            else{
                foreach(json_decode(file_get_contents('assets/uploads/customer_'.$this->session->userdata("user_id").'.json'), true)['message'] as $v){
                    $is = true;
                    if($q){
                        $is = false;
                        foreach($v as $vv){
                            if ($q && stripos($vv, $q) !== false){
                                $is = true;
                                break;
                            }
                        }
                    }
                    if ($is)    $data[] = $v;
                }
            }
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
        }
        finally{
            if($p){
                // , 'prev_page' => $prev_page, 'next_page' => $next_page
                echo json_encode(['msg' => $msg, 'p' => $p, 'count' => $count, 'per_page' => $per_page
                    , 'data' => $data]);
            }
            else{
                echo json_encode(['msg' => $msg, 'data' => $data]);
            }
        }
        //var_dump($data);exit();
    }
    /**
     * [customer_autocomple description]
     * @return [type] [description]
     */
    public function customer_autocomplete($kd_dealer=null) {
        $param=array("keyword"=> $this->input->get("k"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customerview",$param));
        if ($data) {
            if(!$kd_dealer){
                if (is_array($data["list"]->message)) {
     
                    foreach ($data["list"]->message as $key => $message) {
                        $data_message[0][$key] = $message->KD_CUSTOMER;
                        $data_message[1][$key] = $message->NAMA_CUSTOMER;
                    }
                    $result['keyword'] = array_merge($data_message[0], $data_message[1]);
     
                    $this->output->set_output(json_encode($result));
                }
            }else{
                /*if($data["list"]->totaldata >0){
                    echo json_encode($data["list"]->message);
                }*/
                echo json_encode($data["list"]);
            }
        }
    }

    //Guest Book
    /**
     * list data guest book
     * @return [type] [description]
     */
    public function guest_book($debug=null) {
        $data = array();$paramcustomer=array();
        $param = array(
            
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'limit' => 12,
            'field' => "TRANS_GUESTBOOK_VIEW.*,ISNULL((SELECT TOP 1 NO_SPK FROM TRANS_SPK SP WHERE ROW_STATUS >=0 AND SP.GUEST_NO=TRANS_GUESTBOOK_VIEW.GUEST_NO ORDER BY ID DESC),'')HAS_SPK"
        );
        if($this->input->get("kd_dealer")){
            $param["kd_dealer"] = $this->input->get("kd_dealer");
        }else{
            unset($param["kd_dealer"]);
            $param["where_in"]= isDealerAkses();
            $param["where_in_field"] = "KD_DEALER";
        }
        if($this->input->get('keyword')){
            $param['keyword'] = $this->input->get('keyword');
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        // print_r($param);
        // exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        if($debug){
            print_r($param);
            var_dump($data["list"]->param);exit();
        }               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('sales/guest_book', $data); //, $data
    }

    /**
     * load form inputan guestbook
     */
    public function add_guest_book($event=null) {
        //$this->auth->validate_authen('customer/guest_book');
        $data = array(); $paramcustomer=array();
        $param= array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer",$param));
        $data["pekerjaan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom' => " STATUS_SALES ='A'"
        );
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        if($this->session->userdata("nama_group")=="Root"){
            unset($paramcustomer);
            $paramcustomer=array();
        }
        unset($paramcustomer["custom"]);
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        //var_dump($paramcustomer);exit();
        $data["gender"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        $this->json_customer();
        if($event){
            $param=array(
               'kd_dealer' => $this->session->userdata("kd_dealer"),
               'custom'    => "START_DATE <= GETDATE() AND END_DATE >= GETDATE()",
               'field'  => "ID_EVENT,NAMA_EVENT,JENIS_EVENT,LOC_EVENT",
               'groupby' => TRUE
            );
            $data["event"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/trans_sales_event",$param));
            echo json_encode($data["event"]);
            exit();
        }
        $this->template->site('sales/guest_book_tambah_new', $data);
    }

    /**
     * simpan transaksi guestbook ke table trans_guestbook
     * @return [type] [description]
     */
    public function simpan_guest() {
        $kdcus = $this->input->post("kd_customer");
        $kdcus = ($kdcus == "") ? $this->getKDCustomer() : $kdcus; //generate kd_customer
        $param = array(
            'guest_no'      =>($this->input->post("guest_no"))?$this->input->post("guest_no"): $this->getguestno(), //generate nomor urut guestbook
            'kd_customer'   => $kdcus,
            'nama_customer' => ucwords($this->input->post("nama_customer")),
            'kd_dealer'     => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
            'tgl_visit'     => $this->input->post("tgl_kunjungan"),
            'kd_typemotor'  => $this->input->post("kd_item"),
            'kd_warna'      => $this->input->post("kd_item_wm"),
            'kd_sales'      => $this->input->post("kd_sales"),
            'status_deal'   => $this->input->post("kd_status"),
            'alasan_nodeal' =>($this->input->post("ket_notdeal")=="5")?$this->input->post("ket_notdeal_5"): $this->input->post("ket_notdeal"),
            'test_drive'    => $this->input->post("test_drive"),
            'tgl_test'      => $this->input->post("tgl_test"),
            'kesan_test'    => ($this->input->post("test_drive")=='Tidak')?'':htmlentities($this->input->post("kesan_test")),
            'kd_typecustomer' => $this->input->post("kd_typecustomer"),
            'created_by'    => substr($this->input->post('no_appointment')."|".($this->session->userdata("user_id")."|GB"),0,50),
            'carabayar'     => $this->input->post("carabayar"),
            'gb_source'     => $this->input->post("gb_source"),
            'rencana_fu'   => $this->input->post("rencana_fu1"),
            'cust_status'   => $this->input->post("statuse"),
            'kd_event'      => $this->input->post("kd_event")
        );
        $hasilx = ($this->curl->simple_post(API_URL . "/api/sales/guestbook", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $hasil = $this->simpan_custgb($kdcus); //simpan customer baru
        if($hasilx){
            $hasilxy=json_decode($hasilx);
            if($hasilxy->recordexists==TRUE){
                $param ['lastmodified_by']=$this->session->userdata("user_id");
                $hasilx = ($this->curl->simple_put(API_URL . "/api/sales/guestbook", $param, array(CURLOPT_BUFFERSIZE => 10)));
                
                $hasilxx=json_decode($hasilx);
                $id = ($hasilx) ? $hasilxx->message : 0;
                //update transaksi appointment
                if($this->input->post("no_appointment")){
                    $paramapp=array(
                        'no_trans'  => $this->input->post("no_appointment"),
                        'guest_no'  => $param["guest_no"],
                        'kd_sales'  =>$this->input->post("kd_sales")
                    );
                    $hasil= $this->curl->simple_put(API_URL . "/api/marketing/appointcoming", $paramapp, array(CURLOPT_BUFFERSIZE => 10));
                }
                //generate output
                $this->session->set_flashdata('tr-active', $this->input->post("guest_no"));
                $this->data_output($hasilx, 'put',base_url('customer/guest_book'));
            }else{
                //update transaksi appointment
                if($this->input->post("no_appointment")){
                    $paramapp=array(
                        'no_trans'  => $this->input->post("no_appointment"),
                        'guest_no'  => $param["guest_no"],
                        'kd_sales'  =>$this->input->post("kd_sales")
                    );
                    $hasil= $this->curl->simple_put(API_URL . "/api/marketing/appointcoming", $paramapp, array(CURLOPT_BUFFERSIZE => 10));
                    
                }
                //$hasil = $this->simpan_custgb($kdcus);
                $id = ($hasilxy) ? $hasilxy->message : 0;
                $this->session->set_flashdata('tr-active', $this->input->post("guest_no"));
                $this->data_output($hasilx, 'post',base_url('customer/guest_book'));
                
            }
        
        }
    }
    /**
     * Simpan data customer ke table master_customer
     * @param  [type] $kdcust [description]
     * @return [type]         [description]
     */
    public function simpan_custgb($kdcust) {
        $param = array(
            'kd_customer'   => $kdcust,
            'nama_customer' => ($this->input->post("nama_customer"))?ucwords($this->input->post("nama_customer")):$this->input->post("nama_customer_a"),
            'jenis_kelamin' => $this->input->post("kd_gender"),
            'no_ktp'        => $this->input->post("no_ktp"),
            'tgl_lahir'     => $this->input->post('tgl_lahir'),
            'alamat_surat'  => $this->input->post("alamat"),
            'kd_propinsi'   => $this->input->post("kd_propinsi"),
            'kd_kota'       => $this->input->post("kd_kabupaten"),
            'kd_kecamatan'  => $this->input->post("kd_kecamatan"),
            'kelurahan'     => $this->input->post("kd_desa"),
            'no_hp'         => $this->input->post("no_hp"),
            'email'         => $this->input->post("email_customer"),
            'kd_pekerjaan'  => $this->input->post("kd_pekerjaan"),
            'id_refferal'   => $this->input->post("kd_sales"),
            'created_by'    => $this->session->userdata("user_id")." | guestbook",
            'upline'        => $this->input->post("upline")
        );
       
       //proses insert atau update di table master_customer
        //jika kd_customer sudah ada maka lakukan update
        //jika kd_customer belum ada maka lakukan insert 
        $hasil=array();
        $hasil = json_decode($this->curl->simple_post(API_URL . "/api/master_general/customer", $param, array(CURLOPT_BUFFERSIZE => 10)));
        // var_dump($hasil); print_r($param);exit();
        if($hasil){
            if($hasil->recordexists==TRUE){
                $param["lastmodified_by"]=$this->session->userdata("user_id")." | guestbook";
                //$hasil = json_decode($this->curl->simple_put(API_URL . "/api/master_general/customerdb", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        //      var_dump($id_pods);
        return $hasil;
    }
    /**
     * [guestbook_edit description]
     * @return [type] [description]
     */
    public function guestbook_edit() {
        $this->auth->validate_authen('customer/add_guest_book');
        $param = array(
            'guest_no' => base64_decode(urldecode($this->input->get("n"))),
            'field' => "*,ISNULL((SELECT TOP 1 NO_SPK FROM TRANS_SPK SP WHERE ROW_STATUS >=0 AND SP.GUEST_NO=TRANS_GUESTBOOK_VIEW.GUEST_NO ORDER BY ID DESC),'')HAS_SPK"
        );
        $data["guestbook"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $paramcustomer));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $paramcustomer["custom"] = " STATUS_SALES ='A'";
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["pekerjaan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));
        $data["gender"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if($this->session->userdata("nama_group")=="Root"){
            $paramcustomer=array();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        
        $this->template->site('sales/guest_book_tambah_new', $data);
    }

    public function guestbook_update() {
        $param = array(
            'guest_no' => $this->input->post("guest_no"),
            'kd_customer' => $this->input->post("kd_customer"),
            'nama_customer' => ucwords($this->input->post("nama_customer")),
            'kd_dealer' =>($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
            'tgl_visit' => $this->input->post("tgl_kunjungan"),
            'kd_typemotor' => $this->input->post("kd_item"),
            'kd_warna' => $this->input->post("kd_item_wm"),
            'kd_sales' => $this->input->post("kd_sales"),
            'status_deal' => $this->input->post("kd_status"),
            'alasan_nodeal' => $this->input->post("alasan_nodeal"),
            'test_drive' => $this->input->post("test_drive"),
            'tgl_test'      => $this->input->post("tgl_test"),
            'kesan_test'         => $this->input->post("kesan_test"),
            'kd_typecustomer' => $this->input->post("kd_typecustomer"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        //update ke table trans_guestbook
        $hasilx = json_decode($this->curl->simple_put(API_URL . "/api/sales/guestbook", $param, array(CURLOPT_BUFFERSIZE => 10)));
        /*var_dump($hasilx);
        print_r($param);exit();*/
        $id = ($hasilx) ? $hasilx->message : 0;
        //update ke table master_customer
        //$hasil = $this->simpan_custgb($this->input->post("kd_customer"));
        //generate output
        $this->session->set_flashdata('tr-active', $id);
        $this->data_output($hasilx, 'put');
    }

    public function guestbook_delete($n) {
        $param = array(
            'guest_no' => $n,
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $id_pods = json_decode($this->curl->simple_delete(API_URL . "/api/sales/guestbook", $param, array(CURLOPT_BUFFERSIZE => 10)));
        /* var_dump($id_pods);
          exit(); */
        redirect("customer/guest_book");
    }

    public function guest_book_download() {
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => "convert(char,TGL_TRANS,112) = '" .tglToSql($this->input->get("tanggal")) . "'",
            'tanggal' => $this->input->get('tanggal'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array(
                array("SETUP_TYPECUSTOMER AS ST", "ST.KD_TYPECUSTOMER = TRANS_GUESTBOOK_VIEW.KD_TYPECUSTOMER", "LEFT"),
                
            ),
            'field' => "TRANS_GUESTBOOK_VIEW.*, ST.NAMA_TYPECUSTOMER,
                (SELECT COUNT (ID)FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS IN ('Deal','Deal Indent') ) AS TYPECUSTOMER,
                (SELECT COUNT (ID) FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS NOT IN ('Deal','Deal Indent') ) AS JENIS",
            'limit' => 15
        );
        
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';

        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('sales/guest_book_download', $data); //, $data
    }

    /**
     * list data guestbook_detail
     * @return [type] [description]
     */
    public function guestbook_detail($guest_no) {
        $this->auth->validate_authen('customer/guest_book');
        
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'TRANS_GUESTBOOK_DETAIL.ID DESC',
            'custom' => "TRANS_GUESTBOOK_DETAIL.GUEST_NO='" . $guest_no ."'"

        );
        $data = array();
        if($this->input->get("kd_dealer")){
            $param["kd_dealer"] =($this->input->get("kd_dealer"));
        }else{
            $param["where_in"] = isDealerAkses();
            $param["where_in_field"] = "KD_DEALER";
        }
        $param_cek = array(
            "guest_no" => $guest_no
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook_detail", $param));        
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param_cek));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('sales/guestbook_detail', $data); //, $data
    }

    public function download_listdaily() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_GUESTBOOK.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_GUESTBOOK.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $param = array(
            'keyword' => $this->input->get('keyword'),
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'field' => "TRANS_GUESTBOOK_VIEW.*,(SELECT COUNT (ID)FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS IN ('Deal','Deal Indent') ) AS TYPECUSTOMER,
                (SELECT COUNT (ID) FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS NOT IN ('Deal','Deal Indent') ) AS JENIS"
        );  
          
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }
        

        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        return $data;
    }
    
    public function createfile_daily_udcp() {
        $data = array();
        $data = $this->download_listdaily();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $this->session->userdata("kd_maindealer"). "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".UDCP"; //
            $isifile .= $row->ID . ";";
            $isifile .= $row->KD_CUSTOMER . ";";
            $isifile .= $row->KD_DEALER . ";";
            $isifile .= $row->KD_ITEM . ";";
            $isifile .= $row->KD_WARNA . ";";
            $isifile .= $row->KD_TYPEMOTOR . ";";
            $isifile .= str_replace("/", "", tglFromSql($row->TANGGAL)) . ";";
            $isifile .= $row->NAMA_CUSTOMER . ";"; //JADI NAMA
            $isifile .= $row->ALAMAT . ";";
            $isifile .= $row->NO_TELEPON . ";";
            $isifile .= $row->CARA_BAYAR . ";";
            if ($row->TYPECUSTOMER > 0) {$isifile .= "1" . ";"; }else{$isifile .= "0" . ";";}
            if ($row->JENIS > 0) {$isifile .= "1" . ";"; }else{$isifile .= "0" . ";";}
            $isifile .= $row->KETERANGAN . ";";
            $isifile .= $row->NAMA_SALES . ";" . PHP_EOL;
        }

        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    
    public function download_list() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_GUESTBOOK.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_GUESTBOOK.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";


        $param = array(
            'keyword' => $this->input->get('keyword'),
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("TRANS_GUESTBOOK_DETAIL AS TGD", "TGD.GUEST_ID = TRANS_GUESTBOOK_VIEW.ID", "LEFT"),
                array("MASTER_DEALER AS MD", "MD.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER", "LEFT"),
                array("MASTER_METODEFU AS MM", "MM.ID = TGD.METODE_FU", "LEFT"),
                array("SETUP_TYPECUSTOMER AS ST", "ST.KD_TYPECUSTOMER = TRANS_GUESTBOOK_VIEW.KD_TYPECUSTOMER", "LEFT"),
                array("SETUP_KETNOTDEAL AS KND", "KND.KD_KND = TRANS_GUESTBOOK_VIEW.KETERANGAN", "LEFT")
            ),
            'field' => "TRANS_GUESTBOOK_VIEW.*,
                STATUS, KND.ALASAN, METODE_FU, KET_NODEAL,MD.KD_DEALERAHM,
                CASE WHEN TRANS_GUESTBOOK_VIEW.STATUS='Pending' THEN TRANS_GUESTBOOK_VIEW.RENCANA_FU 
                ELSE 
                (SELECT RENCANA_FU FROM TRANS_GUESTBOOK_DETAIL WHERE TRANS_GUESTBOOK_DETAIL.GUEST_ID = TRANS_GUESTBOOK_VIEW.ID) 
                END RENCANA_FU,
                
                (SELECT COUNT (ID) FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS IN ('Deal','Deal Indent') ) AS TYPECUSTOMER,
                (SELECT COUNT (ID) FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS NOT IN ('Deal','Deal Indent') ) AS JENIS",
            'limit' => 15
            );  
          
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }
        

        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        return $data;
    }

    public function createfile_udcp() {
        $data = array();
        $data = $this->download_list();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $this->session->userdata("kd_maindealer") . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".UDCP"; //
            $isifile .= $row->GUEST_NO . ";"; //iconv(in_charset, out_charset, str)d guest
            $isifile .= $row->KD_CUSTOMER . ";"; //id customer
            $isifile .= $row->KD_DEALERAHM . ";"; // id dealer
            $isifile .= $row->KD_HSALES . ";"; //fk honda id
            $isifile .= $row->METODE_FU . ";"; // fk merode fu
            
            if ($row->KD_TYPECUSTOMER == "RO") {
                $isifile .= "2" . ";";
            } else if ($row->KD_TYPECUSTOMER != "RO"){
                $isifile .= "1" . ";";
            } else {
                $isifile .= "1" . ";";
            } // kfid jenis Customer
            if ($row->CARA_BAYAR = "CASH") {
                $isifile .= "1;";
            } elseif ($row->CARA_BAYAR = "CREDIT") {
                $isifile .= "2;";
            } elseif ($row->CARA_BAYAR = "BILYET") {
                $isifile .= "3;";
            } else {
                $isifile .= ";";
            }// kfid setup pembayaran
            $isifile .= $row->STATUS . ";"; // kfid hasil
            $isifile .= $row->KETERANGAN . ";"; // kfid ket not deal
            $isifile .= str_replace("/", "", tglFromSql($row->TANGGAL)) . ";"; // hari
            
            $isifile .= $row->NAMA_CUSTOMER . ";"; // NAMA
            $isifile .= $row->ALAMAT . ";"; //alamat
            $isifile .= $row->NO_HP . ";"; // nohp
            $isifile .= $row->KD_WARNA . ";"; //warna motor
            $isifile .= $row->KD_TYPEMOTOR . ";"; //tipe motor
            
            $isifile .= $row->ALASAN . ";"; //hasil
            $isifile .= $row->RENCANA_FU . ";"; //rencana next fu
            if ($row->JENIS > 0) {$isifile .= "2" . ";"; }else{$isifile .= "1" . ";";} //Status
            $isifile .= $row->KET_NODEAL . ";";
            $isifile .= $row->NAMA_SALES . ";" . PHP_EOL;
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    
    //Appointment
    public function list_appointment($debug=null,$onlydata=null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("MASTER_SALESMAN MS", "MS.KD_SALES=TRANS_APPOINTMENT.KD_SALES", "LEFT")
            ),
            'field' => 'TRANS_APPOINTMENT.*, MS.NAMA_SALES',    
            'orderby' => 'TRANS_APPOINTMENT.TANGGAL_JANJI DESC, ID DESC',
            'limit' => 15

        );
        $tgl_awal =($this->input->get("tgl_awal"))?tglToSql($this->input->get("tgl_awal")):date('Ymd', strtotime('0 day'));
        switch ($this->input->get("pm")) {
            case '3':
                $tgl_akhir = $tgl_awal;//date("Ymd",strtotime('+3 day',$tgal));
                $param["custom"] = "TANGGAL_JANJI BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";
                break;
            case '5':
                //$tgl_akhir = date(create_date($tgl_awal),strtotime("+5 days"));
                $tgl_akhir = date("Ymd",strtotime('+5 day',$tgl_awal)) ;
                $param["custom"] = "TANGGAL_JANJI BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";
                break;
            case '3P':
                $tgl_akhir = date($tgl_awal,strtotime("-3 days"));
                $param["custom"] = "TANGGAL_JANJI BETWEEN '".$tgl_akhir."' AND '".$tgl_awal."'";
                break;
            default:
                $param["custom"] = "TANGGAL_JANJI >='".$tgl_awal."'";
                break;
        }
        switch ($this->input->get("f")) {
            case 'bd':
                # code...
                break;
             case 'sd':
                # code...
                break;
             case 'td':
                # code...
                break;
            
            default:
                # code...
                break;
        }
        if($onlydata==true){
            $param=array(
                'custom' => "DATEDIFF(DAY,TANGGAL_JANJI,GETDATE()) between -1 and 3 AND LEN(ISNULL(GUEST_NO,''))=0",
                'orderby' => "TANGGAL_JANJI DESC, ID DESC"
            );
        }
        $param["kd_dealer"] =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/appointment", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        
        if($debug==true){
            // var_dump($data["list"]);
            if($data["list"]){
                if($data["list"]->totaldata>0){
                    echo json_encode($data["list"]->message);
                }else{
                    echo "[]";
                }
            }else{
                echo "[]";
            }
        }else{
            $string = explode('&page=', $_SERVER["REQUEST_URI"]);
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],//base_url() . 'customer/list_appointment?keyword=' . $param['keyword'],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);

            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('sales/list_appointment', $data); //, $data
            
        }

    }
    /** 
     * load form inputan appointment
     */
    public function add_list_appointment() {
        //$this->auth->validate_authen('customer/guest_book');
        $data = array();
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            "custom" => "MASTER_SALESMAN.STATUS_SALES='A'"
        );
        // $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $paramcustomer));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["metodefus"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        if($this->input->get("n")){
            $param = array(
                'no_trans' => base64_decode(urldecode($this->input->get("n")))
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/appointment", $param));
        }
        $this->template->site('sales/add_list_appointment', $data);
    }

    /**
     * simpan list appointment
     * @return [type] [description]
     */
    public function simpan_list_appointment() {
        //validasi inputan
        if($this->input->post("inputpicker-1")==''){
            $this->form_validation->set_rules('nama_customer', 'nama_customer', 'required|trim');
        }

        if ($this->form_validation->run() === FALSE && $this->input->post("inputpicker-1")=='') {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $kdcus = $this->input->post("kd_customer");
            $nm_cust = ($this->input->post("nama_customer"));
            $kdcus = (strlen(trim($kdcus)) == 0) ? $this->getKDCustomer('app',$nm_cust) : $kdcus; //generate kd_customer
            $param = array(
                'no_trans'      =>($this->input->post("no_trans"))?$this->input->post("no_trans"): $this->getnotrans(), //generate nomor transaksi
                'kd_customer'   => $kdcus,
                'nama_customer' => strtoupper($nm_cust),
                'alamat'        => $this->input->post("alamat"),
                'kd_propinsi'   => $this->input->post("kd_propinsi"),
                'nama_propinsi'  => $this->input->post("nama_propinsi"),
                'kd_kabupaten'   => $this->input->post("kd_kabupaten"),
                'nama_kabupaten'   => $this->input->post("nama_kabupaten"),
                'kd_kecamatan'   => $this->input->post("kd_kecamatan"),
                'nama_kecamatan' => $this->input->post("nama_kecamatan"),
                'kd_desa' => $this->input->post("kd_desa"),
                'nama_desa' => $this->input->post("nama_desa"),
                'no_hp' => $this->input->post("hp_customer"),
                'kd_dealer'     => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
                'tanggal'       =>  $this->input->post("tanggal"),
                'jenis_appointment'  => $this->input->post("jenis_appointment"),
                'hubungi_via'   => $this->input->post("nama_metode"),
                'kd_sales'      => $this->input->post("kd_sales"),
                'nama_sales'    => $this->input->post("nama_sales"),
                'tanggal_janji'   => $this->input->post("tanggal_janji"),
                'jam_janji'    => $this->input->post("jam_janji"),
                'keterangan'      => $this->input->post("keterangan"),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $hasilx = ($this->curl->simple_post(API_URL . "/api/marketing/appointment", $param, array(CURLOPT_BUFFERSIZE => 10)));
            //var_dump($hasilx);exit();
            if($hasilx){
                $hasilxy=json_decode($hasilx);
                if($hasilxy->recordexists==TRUE){
                    $param = array(
                        'no_trans' => $this->input->post("no_trans"),
                        'kd_customer' => $this->input->post("kd_customer"),
                        'nama_customer' => strtoupper($this->input->post("nama_customer")),
                        'alamat'        => $this->input->post("alamat"),
                        'kd_propinsi'   => $this->input->post("kd_propinsi"),
                        'nama_propinsi'  => $this->input->post("nama_propinsi"),
                        'kd_kabupaten'   => $this->input->post("kd_kabupaten"),
                        'nama_kabupaten'   => $this->input->post("nama_kabupaten"),
                        'kd_kecamatan'   => $this->input->post("kd_kecamatan"),
                        'nama_kecamatan' => $this->input->post("nama_kecamatan"),
                        'kd_desa' => $this->input->post("kd_desa"),
                        'nama_desa' => $this->input->post("nama_desa"),
                        'no_hp' => $this->input->post("hp_customer"),
                        'kd_dealer'     => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
                        'tanggal'       =>  $this->input->post("tanggal"),
                        'jenis_appointment'  => $this->input->post("jenis_appointment"),
                        'hubungi_via'   => $this->input->post("nama_metode"),
                        'kd_sales'      => $this->input->post("kd_sales"),
                        'nama_sales'    => $this->input->post("nama_sales"),
                        'tanggal_janji'   => $this->input->post("tanggal_janji"),
                        'jam_janji'    => $this->input->post("jam_janji"),
                        'keterangan'      => $this->input->post("keterangan"),
                        'lastmodified_by' => $this->session->userdata("user_id"),
                    );
                        $hasilx = ($this->curl->simple_put(API_URL . "/api/marketing/appointment", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        $hasilxx=json_decode($hasilx);
                        $id = ($hasilx) ? $hasilxx->message : 0;
                        //update ke table master_customer
                        $hasil = $this->simpan_custap($kdcus,strtoupper($nm_cust));
                        //generate output
                        $this->session->set_flashdata('tr-active', $id);
                        $this->data_output($hasilx, 'put',base_url('customer/list_appointment'));
                }else{
                    //$hasil = $this->simpan_custap($kdcus,$param["nama_customer"]);
                    $id = ($hasilxy) ? $hasilxy->message : 0;
                    $this->session->set_flashdata('tr-active', $id);
                    $this->data_output($hasilx, 'post',base_url('customer/list_appointment'));
                }
            }else{
                $this->data_output($hasilx, 'post',base_url('customer/add_list_appointment'));
            }
        }
    }
    public function edit_list_appointment() {
        $this->auth->validate_authen('customer/list_appointment');
        $param = array(
            'no_trans' => base64_decode(urldecode($this->input->get("n")))
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/appointment", $param));
        
        //dibuat pak iswan
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            "custom" => "MASTER_SALESMAN.STATUS_SALES='A'"
        );
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $paramcustomer));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["metodefus"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu"));
        $data["edit"] = "y";
        
        $this->template->site('sales/edit_list_appointment', $data);
    }
    public function update_list_appointment($id) {       
        $param = array(
            'no_trans'      =>  $this->input->post("no_trans"), //generate nomor transaksi
            'kd_customer'   =>  $this->input->post("kd_customer"),
            'nama_customer' => strtoupper($this->input->post("nama_customer")),
            'alamat'        => $this->input->post("alamat"),
            'kd_propinsi'   => $this->input->post("kd_propinsi"),
            'nama_propinsi'  => $this->input->post("nama_propinsi"),
            'kd_kabupaten'   => $this->input->post("kd_kabupaten"),
            'nama_kabupaten'   => $this->input->post("nama_kabupaten"),
            'kd_kecamatan'   => $this->input->post("kd_kecamatan"),
            'nama_kecamatan' => $this->input->post("nama_kecamatan"),
            'kd_desa' => $this->input->post("kd_desa"),
            'nama_desa' => $this->input->post("nama_desa"),
            'no_hp' => $this->input->post("hp_customer"),
            'kd_dealer'     => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
            'tanggal'       =>  $this->input->post("tanggal"),
            'jenis_appointment'  => $this->input->post("jenis_appointment"),
            'hubungi_via'   => $this->input->post("nama_metode"),
            'kd_sales'      => $this->input->post("kd_sales"),
            'nama_sales'    => $this->input->post("nama_sales"),
            'tanggal_janji'   => $this->input->post("tanggal_janji"),
            'jam_janji'    => $this->input->post("jam_janji"),
            'keterangan'      => $this->input->post("keterangan"),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = json_decode($this->curl->simple_put(API_URL . "/api/marketing/appointment", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if ($hasil) {
            $hasil = $this->simpan_custap($this->input->post("kd_customer"));
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'location' => base_url('customer/list_appointment')
            );
            $this->output->set_output(json_encode($data_status));
        } else {
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }

    } 

    public function delete_list_appointment($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/appointment", $param));

        $this->data_output($data, 'delete', base_url('customer/list_appointment'));
    }
    function update_app_datang(){
        $param = array(
            'no_trans'      =>  $this->input->post("no_trans"),
            'guest_no'      => $this->input->post("guest_no"),
            'kd_sales'      => $this->input->post('kd_sales')
        );
        $hasil = json_decode($this->curl->simple_put(API_URL . "/api/marketing/appointcoming", $param, array(CURLOPT_BUFFERSIZE => 10)));

        if ($hasil) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'location' => base_url('customer/list_appointment')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }
    public function list_appointment_typeahead() {
        $data=[];
        $keywords = $this->input->get('keyword');

        $param = array(
            'keyword' => $keywords,
            "custom" => "TRANS_APPOINTMENT.KD_DEALER='".$this->session->userdata("kd_dealer")."'"
        );
        $data_message=[];
        $data=json_decode($this->curl->simple_get(API_URL."/api/marketing/appointment",$param));

        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->NAMA_CUSTOMER;
                }
            }else{
                $data_message['keyword'][]="";//$data->message;
            }
        }else{

            $data_message['keyword'][0]="<i class='fa fa-info'></i> Data tidak di temukan";
        }
        $this->output->set_output(json_encode($data_message));
    }
    
    public function simpan_custap($kdcust,$nama_customer=null) {
        $param = array(
            'kd_customer'   => $kdcust,
            'nama_customer' => $nama_customer,
            'jenis_kelamin' => $this->input->post("jenis_kelamin"),
            'alamat_surat'  => $this->input->post("alamat"),
            'kd_propinsi'   => $this->input->post("kd_propinsi"),
            'kd_kota'       => $this->input->post("kd_kabupaten"),
            'kd_kecamatan'  => $this->input->post("kd_kecamatan"),
            'kelurahan'     => $this->input->post("kd_desa"),
            'no_hp'         => $this->input->post("hp_customer"),
            'id_refferal'   => $this->input->post("kd_sales"),
            'created_by'    => $this->session->userdata("user_id")." | appointment"
        );
        //proses insert atau update di table master_customer
        //jika kd_customer sudah ada maka lakukan update
        //jika kd_customer belum ada maka lakukan insert 
        $hasil=array();
        if($kdcust){
            $hasil = json_decode($this->curl->simple_post(API_URL . "/api/master_general/customer", $param, array(CURLOPT_BUFFERSIZE => 10)));
        }else{
            if($hasil->recordexists==TRUE){
                $param["lastmodified_by"]=$this->session->userdata("user_id")." | appointment";
                $hasil = json_decode($this->curl->simple_put(API_URL . "/api/master_general/customerap", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        //      var_dump($id_pods);
        return $hasil;
    }
    /**
     * generate otomatis kode customer
     * format kd_customer =CS.KD_DEALER.TahunBulan saat ini - nomor urut
     * akan reset ke awal setiap awal tahun
     * @return [type] [description]
     */
    public function getAppointment() {
        $nomor=0;
        $param = array(
            "custom" => "YEAR(CREATED_TIME)=YEAR(GETDATE()) AND ROW_STATUS >-2",
            "field"  => "TOP 1 (KD_CUSTOMER) ",
            "orderby"=> "REPLACE(RIGHT(KD_CUSTOMER,13),'-','') DESC"
            );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/marketing/list_appointment", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $nomor = substr($value->KD_CUSTOMER,-6);
                }
            }
        }
        //echo (int)$nomor; exit();
        $number =str_pad(((int)$nomor) + 1, 6, '0', STR_PAD_LEFT);
        return "CS" . str_pad($this->session->userdata("kd_dealer"), 3, '0', STR_PAD_LEFT) . date('Ym') . '-' . $number;
    }

    public function getKDCustomer($asal=null,$nama_customer=null) {
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => 'CB',
            'kd_dealer' => $this->session->userdata("kd_maindealer"),
            'tahun_docno' => date('Y'),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = date('m');//substr($this->input->post('tgl_kunjungan'), 3, 2);
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_maindealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpo == 0) {
            $nopo = "CB" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-000001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = "CB" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 6, '0', STR_PAD_LEFT);
        }
        if($asal=='app'){
            $this->simpan_custap($nopo,strtoupper($nama_customer));
        }
        return $nopo;
    }

    /**
     * generate nomor transaksi guestbook
     * format GB.KD_DEALER.TahunBulan transaksi-nomor urut
     * reset nomor urut setiap awal tahun otomatis sesuai dengan tgl transaksi
     * @return [type] [description]
     */
    function getguestno() {
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => 'GB',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tgl_kunjungan'), 6, 4),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = substr($this->input->post('tgl_kunjungan'), 3, 2);
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpo == 0) {
            $nopo = "GB" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-000001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = "GB" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 6, '0', STR_PAD_LEFT);
        }
        //var_dump($nopo);exit();
        return $nopo;
    }

    function getnotrans() {
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => 'AP',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),//substr($this->input->post('tgl_kunjungan'), 6, 4),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim =date('m');// substr($this->input->post('tgl_kunjungan'), 3, 2);
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpo == 0) {
            $nopo = "AP" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-000001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = "AP" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 6, '0', STR_PAD_LEFT);
        }
        //var_dump($nopo);exit();
        return $nopo;
    }

    /**
     * [print_guest_book description]
     * @return [type] [description]
     */
    public function print_guest_book() {

        $this->load->view('sales/guest_book_cetak');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    /**
     * [kabupaten description]
     * @return [type] [description]
     */
    public function kabupaten() {
        $param = array(
            'kd_propinsi' => $this->input->get('kd'),
            'custom'  => "LEFT(KD_KABUPATEN,2) ='".substr($this->input->get('kd'),0,2)."'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
        echo "<option value=''>--Pilih Kabupaten--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_KABUPATEN . "'>" . $value->NAMA_KABUPATEN . "</option>";
                }
            }
        }
    }

    /**
     * [kecamatan description]
     * @return [type] [description]
     */
    public function kecamatan() {
        $param = array(
            'custom'  => "LEFT(KD_KECAMATAN,2) ='".substr($this->input->get('kd'),0,2)."'",
            'kd_kabupaten' => $this->input->get('kd')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan", $param));
        echo "<option value=''>--Pilih Kecamatan--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_KECAMATAN . "'>" . $value->NAMA_KECAMATAN . "</option>";
                }
            }
        }
    }

    /**
     * [desa description]
     * @return [type] [description]
     */
    public function desa($kdpos=null,$kd_desa=null) {
        $param = array(
            'kd_kecamatan' => $this->input->get('kd'),
            'custom'  => "LEFT(KD_DESA,2) ='".substr($this->input->get('kd'),0,2)."'"
        );
        if($kd_desa){
            $param = array('kd_desa' => $kd_desa) ;
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $param));
        if ($data) {
            if ($data->totaldata >0) {
                if($kdpos==true){
                    foreach ($data->message as $key => $value) {
                        echo $value->KODE_POS;
                    }
                }else{
                    echo "<option value=''>--Pilih Desa/Kelurahan--</option>";
                    foreach ($data->message as $key => $value) {
                        echo "<option value='" . $value->KD_DESA . "'>" . $value->NAMA_DESA . "</option>";
                    }
                }
            }
        }
    }

    public function desa_ddl($kd_desa=null) {
        $param = array(
            'kd_kecamatan' => $this->input->get('kd'),
            'custom'  => "LEFT(KD_DESA,2) ='".substr($this->input->get('kd'),0,2)."'"
        );
        if($kd_desa){
            $param = array('kd_desa' => $kd_desa) ;
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $param));
        if ($data) {
            if ($data->totaldata >0) {
                echo "<option value=''>--Pilih Desa/Kelurahan--</option>";
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_DESA . "'>" . $value->NAMA_DESA . "</option>";
                }
            }
        }
    }
    function cs_h2($data_only=null,$debug=null){
        $for_apo="";
        $param=array(
            'keyword' => $this->input->get("keyword"),
            'orderby' => "NAMA_CUSTOMER",
            'limit'   => '150',
            'offset'  => '0'
        );
        if($data_only){
            $param=array(
                'no_mesin'  => $this->input->post("kd_customer")
            );
        }
        if($this->input->get("f")){
            $for_apo="/true";
        }
        $data["list"]=json_decode($this->curl->simple_get(API_URL . "/api/inventori/sopart_customer/true".$for_apo, $param));
        
        if($data_only){
            if($data["list"]){
                if($data["list"]->totaldata >0){
                    echo json_encode($data["list"]);
                }
            }
        }else{
            $this->load->view('sales/customer_search', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }
    }
    /**
     * [data_output description]
     * @param  [type] $hasil [description]
     * @return [type]        [description]
     */
    function data_output($hasil = NULL, $method = '', $location = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil disimpan",
                        'location' => $location
                    );
                } else {
                    $result = $hasil;
                }
                $this->output->set_output(json_encode($result));
                break;

            case 'put':
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil diupdate",
                        'location' => $location
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal diupdate',
                        'status' => false
                    );

                    $this->output->set_output(json_encode($result));
                }
                break;

            case 'delete':
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => 'Data berhasil dihapus',
                        'location' => $location
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal dihapus',
                        'status' => false
                    );

                    $this->output->set_output(json_encode($result));
                }
                break;
        }
    }

}
