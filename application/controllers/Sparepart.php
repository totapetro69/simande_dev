<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart extends CI_Controller {
     var $API="";
     public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper("zetro_helper");
        $this->load->model("Custom_model");
    }

     public function pagination($config) {

        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open'] = "<ul class='pagination pagination-sm m-t-none m-b-none'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";

        return $config;
    }



    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

   
    public function sparepart_typeahead($result=false,$part_number=null) {
        $param=array();
        $param=array(
            "jointable"   => array(array("MASTER_PART P","P.PART_NUMBER=TRANS_PART_SJMASUK.PART_NUMBER","LEFT")),
            "field"       => "TRANS_PART_SJMASUK.PART_NUMBER,P.PART_DESKRIPSI",
            "groupby"     => TRUE,
            "custom"      => "TRANS_PART_SJMASUK.PART_NUMBER IS NOT NULL"
        );
        if($this->input->post("part_number")){
            $param["part_number"]= $this->input->post("part_number");
        }
        if($this->input->get("part_number")){
            $param["part_number"]= $this->input->get("part_number");
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part",$param));
        //var_dump($data);
        if($result==false){
            foreach ($data["list"]->message as $key => $message) {
                $data_message[$key] = $message->PART_NUMBER ." - ".$message->PART_DESKRIPSI;
                //$data_message[1][$key] = $message->NAMA_BUNDLING;
            }
        
            $result['keyword'] =$data_message;// array_merge($data_message[0], $data_message[1]);
            $this->output->set_output(json_encode($result));
        }else{
            if($data["list"]){
                if($data["list"]->totaldata>0){
                     echo json_encode(($data["list"]->message));
                }else{
                    echo "[]";
                }
            }else{
                echo "[]";
            }
        }
    }
    
    function sparepart_vsmotor($ajax=true,$onlyTypeMotor=null){
        $param=array();$result=array();
        if($this->input->get("kd_typemotor")){
            $param["kd_typemotor"]=$this->input->get("kd_typemotor");
        }
        if($this->input->get("part_number")){
            $param["part_number"] = $this->input->get("part_number");
        }
        if($onlyTypeMotor==true){
            $param=array(
                'field' =>'KD_TYPEMOTOR,NAMA_GROUPMOTOR,(SELECT TOP 1 NAMA_TYPEMOTOR FROM MASTER_P_TYPEMOTOR P WHERE KD_TYPEMOTOR=PART_VS_TYPEMOTOR.KD_TYPEMOTOR) AS NAMA_TYPEMOTOR',           
                'groupby_text' => 'KD_TYPEMOTOR,NAMA_GROUPMOTOR'
            );
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/partvsmotor",$param));
        //var_dump($data);print_r($param);
        if($data){
            if($data->totaldata>0){
                $result = $data->message;
            }
        }
        if($ajax==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }

    public function expedisi_typeahead($result=false) {
        $param=array(
            'field' => 'NAMA_EXPEDISI,NO_POLISI',
            'groupby' => TRUE,
            'orderby' => 'NAMA_EXPEDISI'
        );
        if($this->input->post("nama_expedisi")){
            $param=array("nama_expedisi" => $this->input->post("nama_expedisi"));
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima",$param));
        if($result==false){
            foreach ($data["list"]->message as $key => $message) {
                $data_message[$key] = $message->NAMA_EXPEDISI ." - ". $message->NO_POLISI;
            }
        
            $result['keyword'] =$data_message;
            $this->output->set_output(json_encode($result));
        }else{
            if($data["list"]){
                if($data["list"]->totaldata>0){
                     echo json_encode(($data["list"]->message));
                }else{
                    echo "[]";
                }
            }else{
                echo "[]";
            }
        }
    }


    /**
     * [hargapart hanya untuk harga jual ada]
     * @return [type] [description]
     * last modifed by Iswan Putera 28-03-2018
     */
    public function hargapart($onlydata=null,$kategori=null) {
        // function ini sudah fix jika mau menambah gunaka if dengan variable baru
        $this->auth->validate_authen('sparepart/hargapart/'.$onlydata.'/'.$kategori);
        $data = array();$result=array();
        if($this->session->userdata("nama_group") == "Root"){
            $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable'  => array(
                    array("SETUP_TYPECUSTOMER as MT","MT.KD_TYPECUSTOMER=TRANS_PART_HARGAJUAL.KD_TYPECUSTOMER","LEFT"),
                    array("MASTER_DEALER as MD","MD.KD_DEALER=TRANS_PART_HARGAJUAL.KD_DEALER","LEFT"),
                    array("MASTER_PART as MP","MP.PART_NUMBER=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT"),
                    array("MASTER_BARANG as MB","MB.KD_BARANG=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT"),
                    array("MASTER_JASA as MJ","MJ.KD_JASA=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT")
            ),
            'field' => 'TRANS_PART_HARGAJUAL.*,MT.NAMA_TYPECUSTOMER, MD.NAMA_DEALER, MP.PART_DESKRIPSI, MB.NAMA_BARANG, MJ.KD_MOTOR, MJ.KETERANGAN',
            'orderby' => 'TRANS_PART_HARGAJUAL.ID desc'
        );
        }else{
            $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable'  => array(
                    array("SETUP_TYPECUSTOMER as MT","MT.KD_TYPECUSTOMER=TRANS_PART_HARGAJUAL.KD_TYPECUSTOMER","LEFT"),
                    array("MASTER_DEALER as MD","MD.KD_DEALER=TRANS_PART_HARGAJUAL.KD_DEALER","LEFT"),
                    array("MASTER_PART as MP","MP.PART_NUMBER=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT"),
                    array("MASTER_BARANG as MB","MB.KD_BARANG=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT"),
                    array("MASTER_JASA as MJ","MJ.KD_JASA=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT")
            ),
            'field' => 'TRANS_PART_HARGAJUAL.*,MT.NAMA_TYPECUSTOMER, MD.NAMA_DEALER, MP.PART_DESKRIPSI, MB.NAMA_BARANG, MJ.KD_MOTOR, MJ.KETERANGAN',
            'orderby' => 'TRANS_PART_HARGAJUAL.ID desc',
            "custom" => "TRANS_PART_HARGAJUAL.KD_DEALER='".$this->session->userdata("kd_dealer")."'"
        );
        }
        
        if($onlydata=='true'){
            if($this->input->get('part_number')){
                $param["part_number"] = $this->input->get("part_number");
            }
            if($this->input->get("customer")){
                $param["kd_typecustomer"] = $this->input->get("customer");
            }else{
                $param["kd_typecustomer"] = 'R';
            }
            $param["custom"] = "TRANS_PART_HARGAJUAL.START_DATE <= GETDATE() AND TRANS_PART_HARGAJUAL.END_DATE >= GETDATE()";
        }
        if(strtolower($kategori)=='part' || $kategori==''){
            if($onlydata){
                if($this->input->get('part_number')){
                    $param=array('part_number'=> $this->input->get("part_number"));
                }
            }
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        }else{
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual", $param));
            
        }
        // echo $kategori;
        // var_dump($data["list"]);exit();
        if($onlydata=='true'){
            if($data["list"]){
                if($data["list"]->totaldata>0){
                    $result=json_encode($data["list"]->message);
                }else{
                    goto xx;
                }
            }else{
                xx:
                $result =($kategori=='')? $this->sparepart_typeahead(TRUE,(isset($param["part_number"]))?$param["part_number"]:''):json_encode($this->harga_belibarang());
                
            }
            
            echo ($result);
        }else{        
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );            
            $pagination = $this->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('master/master_hargapart', $data);
        }
        //echo $onlydata;
    }

    function harga_belibarang($debug=false){
        $list=null; $data=array();
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
            'jointable'=> array(array("MASTER_BARANG M","M.KD_BARANG=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT")),
            'field' =>"TRANS_PART_HARGAJUAL.*,M.NAMA_BARANG AS PART_DESKRIPSI"
        );
        if($this->input->get("part_number")){
            $param["part_number"] = $this->input->get("part_number");
        }else{
            $list = true;
        }
        $data=json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual/", $param));
        //var_dump($data);
        if($data){
            if($data->totaldata >0){
                if($debug == true){
                    echo json_encode($data->message);
                }else{
                   return $data->message; 
                }
                
            }else{
                if($debug == true){ echo "[]";}else{return false;}
            }                  
        }
    }

    /**
     * [history_hargapart description]
     * @param  [type] $part_number     [description]
     * @param  [type] $kd_typecustomer [description]
     * @return [type]                  [description]
     */
    public function history_hargapart($part_number, $kd_typecustomer) {
        $this->auth->validate_authen('sparepart/hargapart');
        $data = array();
        $param_detail = array(
            'jointable'  => array(
                array("SETUP_TYPECUSTOMER as MT","MT.KD_TYPECUSTOMER=TRANS_PART_HARGAJUAL.KD_TYPECUSTOMER","LEFT"),
                array("MASTER_DEALER as MD","MD.KD_DEALER=TRANS_PART_HARGAJUAL.KD_DEALER","LEFT"),
                array("MASTER_PART as MP","MP.PART_NUMBER=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT"),
                array("MASTER_BARANG as MB","MB.KD_BARANG=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT"),
                array("MASTER_JASA as MJ","MJ.KD_JASA=TRANS_PART_HARGAJUAL.PART_NUMBER","LEFT")
            ),
            'field' => 'TRANS_PART_HARGAJUAL.*,MT.NAMA_TYPECUSTOMER, MD.NAMA_DEALER, MP.PART_DESKRIPSI, MB.NAMA_BARANG, MJ.KD_MOTOR, MJ.KETERANGAN',
            "custom" => "TRANS_PART_HARGAJUAL.PART_NUMBER='".$part_number."' AND TRANS_PART_HARGAJUAL.KD_TYPECUSTOMER='".$kd_typecustomer."'"
        );

        $data["list_detail"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual", $param_detail));
        //var_dump($data["list_detail"]);exit;

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable'  => array(
                    array("SETUP_TYPECUSTOMER as MT","MT.KD_TYPECUSTOMER=TRANS_PART_HARGAJUAL_BACKUP.KD_TYPECUSTOMER","LEFT"),
                    array("MASTER_DEALER as MD","MD.KD_DEALER=TRANS_PART_HARGAJUAL_BACKUP.KD_DEALER","LEFT"),
                    array("MASTER_PART as MP","MP.PART_NUMBER=TRANS_PART_HARGAJUAL_BACKUP.PART_NUMBER","LEFT"),
                    array("MASTER_BARANG as MB","MB.KD_BARANG=TRANS_PART_HARGAJUAL_BACKUP.PART_NUMBER","LEFT"),
                    array("MASTER_JASA as MJ","MJ.KD_JASA=TRANS_PART_HARGAJUAL_BACKUP.PART_NUMBER","LEFT")
            ),
            'field' => 'TRANS_PART_HARGAJUAL_BACKUP.*,MT.NAMA_TYPECUSTOMER, MD.NAMA_DEALER, MP.PART_DESKRIPSI, MB.NAMA_BARANG, MJ.KD_MOTOR, MJ.KETERANGAN',
            'orderby' => 'TRANS_PART_HARGAJUAL_BACKUP.ID desc',
            "custom" => "TRANS_PART_HARGAJUAL_BACKUP.PART_NUMBER='".$part_number."' AND TRANS_PART_HARGAJUAL_BACKUP.KD_TYPECUSTOMER='".$kd_typecustomer."'"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual_backup", $param));        
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );            
            $pagination = $this->pagination($config);

            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('master/master_hargapart_history', $data);

    }

    public function add_hargapart() {
        $this->auth->validate_authen('sparepart/hargapart/false/barang');
        $data = array();
         $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual"));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        //$data["part"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part"));
        $barang = array(
            "custom" => "MASTER_BARANG.KATEGORI='Barang'"
        );
        $data["barang"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $barang));
        $aksesoris = array(
            "custom" => "MASTER_BARANG.KATEGORI='Aksesoris'"
        );
        $data["aksesoris"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $aksesoris));
        $apparel = array(
            "custom" => "MASTER_BARANG.KATEGORI='Apparel'"
        );
        $data["apparel"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $apparel));
        $param_js = array(
            'orderby' => 'CREATED_TIME DESC'
        );
        $data["jasa"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa", $param_js));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        //$data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        //var_dump($data["part"]);exit;

        $this->load->view('form_tambah/add_hargapart',$data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_hargapart_simpan() {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Periode Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Periode Selesai', 'required|trim');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|trim');
        if($this->input->post("kategori") == "Aksesoris"){
            $this->form_validation->set_rules('aksesoris', 'Aksesoris', 'required|trim');
        }else if($this->input->post("kategori") == "Barang"){
            $this->form_validation->set_rules('kd_barang', 'Barang', 'required|trim');
        }else{
            $this->form_validation->set_rules('apparel', 'Apparel', 'required|trim');
        }

        if($this->input->post("harga_beli") == null){
            $harga_beli = 0;
        }else{
            $harga_beli = $this->input->post("harga_beli");
        }

        if($this->input->post("harga_jual") == null){
            $harga_jual = 0;
        }else{
            $harga_jual = $this->input->post("harga_jual");
        }

        if($this->input->post("diskon") == null){
            $diskon = 0;
        }else{
            $diskon = $this->input->post("diskon");
        }

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            if($this->input->post("kategori") == "Aksesoris"){
                //$part_number = substr($this->input->post("part_no"), 0, strpos($this->input->post("part_no"), ' '));
                $kd_barang = $this->input->post("aksesoris");
            }else if($this->input->post("kategori") == "Barang"){
                $kd_barang = $this->input->post("kd_barang");
            }else{
                $kd_barang = $this->input->post("apparel");
            }

            $param = array(
                    'kategori' => $this->input->post("kategori"),
                    'part_number' => $kd_barang,
                    'kd_typecustomer' => $this->input->post("kd_typecustomer"),
                    'harga_beli' => $harga_beli,
                    'harga_jual' => $harga_jual,
                    'diskon_type' => $this->input->post("diskon_type"),
                    'diskon' => $diskon,
                    'start_date' => $this->input->post("start_date"),
                    'end_date' => $this->input->post("end_date"),
                    'kd_maindealer' => $this->input->post("kd_maindealer"),
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );

            //var_dump($param);exit;
            
            $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/part_hargajual", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            //var_dump($hasil);exit;
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('sparepart/hargapart/false/barang?jt=barang'));
        }
    }

    public function edit_hargapart($id, $row_status) {
        $this->auth->validate_authen('sparepart/hargapart/false/barang');
        $param = array(
            "custom" => "TRANS_PART_HARGAJUAL.ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual", $param));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        $data["part"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part"));
        $barang = array(
            "custom" => "MASTER_BARANG.KATEGORI='Barang'"
        );
        $data["barang"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $barang));
        $aksesoris = array(
            "custom" => "MASTER_BARANG.KATEGORI='Aksesoris'"
        );
        $data["aksesoris"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $aksesoris));
        $apparel = array(
            "custom" => "MASTER_BARANG.KATEGORI='Apparel'"
        );
        $data["apparel"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $apparel));
        $param_js = array(
            'orderby' => 'CREATED_TIME DESC'
        );
        $data["jasa"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa", $param_js));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));

        //var_dump($data["list"]);exit;

        $this->load->view('form_edit/edit_hargapart', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_hargapart($id) {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Periode Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Periode Selesai', 'required|trim');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|trim');
        if($this->input->post("kategori") == "Aksesoris"){
            $this->form_validation->set_rules('aksesoris', 'Aksesoris', 'required|trim');
        }else if($this->input->post("kategori") == "Barang"){
            $this->form_validation->set_rules('kd_barang', 'Barang', 'required|trim');
        }else{
            $this->form_validation->set_rules('apparel', 'Apparel', 'required|trim');
        }

        if($this->input->post("harga_beli") == null){
            $harga_beli = 0;
        }else{
            $harga_beli = $this->input->post("harga_beli");
        }

        if($this->input->post("harga_jual") == null){
            $harga_jual = 0;
        }else{
            $harga_jual = $this->input->post("harga_jual");
        }

        if($this->input->post("diskon") == null){
            $diskon = 0;
        }else{
            $diskon = $this->input->post("diskon");
        }

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            if($this->input->post("kategori") == "Aksesoris"){
                //$part_number = substr($this->input->post("part_no"), 0, strpos($this->input->post("part_no"), ' '));
                $kd_barang = $this->input->post("aksesoris");
            }else if($this->input->post("kategori") == "Barang"){
                $kd_barang = $this->input->post("kd_barang");
            }else{
                $kd_barang = $this->input->post("apparel");
            }

            $param = array(
                    'id' => $this->input->post("id"),
                    'kategori' => $this->input->post("kategori"),
                    'part_number' => $kd_barang,
                    'kd_typecustomer' => $this->input->post("kd_typecustomer"),
                    'harga_beli' => $harga_beli,
                    'harga_jual' => $harga_jual,
                    'diskon_type' => $this->input->post("diskon_type"),
                    'diskon' => $diskon,
                    'start_date' => $this->input->post("start_date"),
                    'end_date' => $this->input->post("end_date"),
                    'kd_maindealer' => $this->input->post("kd_maindealer"),
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );

            $hasil = $this->curl->simple_put(API_URL . "/api/sparepart/part_hargajual", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_hargapart($id) {
        $this->auth->validate_authen('sparepart/hargapart/false/barang');
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sparepart/part_hargajual", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('sparepart/hargapart/false/barang')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    public function hargapart_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_hargajual"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->KATEGORI;
            $data_message[2][$key] = $message->KD_TYPECUSTOMER;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    
     public function part_typeahead($keywords=null,$price=null)
    {
        $data=[];
        $keywords = $this->input->get('keyword');

        $param = array(
            'keyword' => $keywords 
        );
        $data_message=[];
        $data=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part",$param));

        // var_dump($data); exit;

        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $message) {
                    $harga=($price==true)?" - ".number_format($message->HET):"";
                    $data_message['keyword'][$key] = $message->PART_NUMBER ." - ". $message->PART_DESKRIPSI .$harga;
                }
            }else{
                $data_message['keyword'][]="";//$data->message;
            }
        }else{

            $data_message['keyword'][0]="<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,

        $this->output->set_output(json_encode($data_message));
    }

    public function part_jasa($keywords=null, $price=null)
    {
        $jasa = $this->input->get('para');
        //var_dump($jasa);exit;
        $data=[];
        $keywords = $this->input->get('keyword');
        $param = array(
            'keyword' => $keywords,
            'jointable' => array(
                array("MASTER_PART MM", "MM.PART_NUMBER=MASTER_PVTM.NO_PART_TIPEMOTOR", "LEFT")
            ),
            'field' => "MASTER_PVTM.*, MM.PART_NUMBER, MM.PART_DESKRIPSI, MM.HET", 
            "custom" => "MASTER_PVTM.TYPE_MARKETING='".$jasa."'"
        );
        $data_message=[];
        $data=json_decode($this->curl->simple_get(API_URL."/api/sparepart/pvtm",$param));

        // var_dump($data); exit;

        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $message) {
                    $harga=number_format($message->HET);
                    $data_message['keyword'][$key] = $message->PART_NUMBER ." - ". $message->PART_DESKRIPSI." - ". $message->TYPE_MARKETING ." - ". $harga;
                }
            }else{
                $data_message['keyword'][]="";//$data->message;
            }
        }else{

            $data_message['keyword'][0]="<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,

        $this->output->set_output(json_encode($data_message));
    }
    
    function hargabeli_part($ajax=null,$debug=null){
        $where ="AND KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        if($this->input->get('part_number')){
            $where .=" AND PART_NUMBER='".$this->input->get('part_number')."'";
        }
        $param["query"] = $this->Custom_model->hargabeli_part($where);
        $data=json_decode($this->curl->simple_get(API_URL."/api/sparepart/part_terimadetail",$param));
        if($ajax==true){
            if($data){
                if($data->totaldata>0){
                    echo json_encode($data->message);
                }
            }
            if($debug==true){echo json_encode($data);}
        }else{
            return $data;
        }
    }

    //master kpb part
    public function kpb_part() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'MASTER_KPB_PART.*',
            'orderby' => 'MASTER_KPB_PART.ID DESC'
        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/kpb_part", $param));
 
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master/master_kpb_part', $data);
    }

    public function add_kpb_part($debug=null) {
        ini_set('max_execution_time', 200);
        ob_end_flush();
        ob_start();
        $this->auth->validate_authen('sparepart/kpb_part');
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list43a";
        
        $options = array(
            CURLOPT_URL => WS_URL.$param["link"],
            CURLOPT_RETURNTRANSFER => true, // return web page 
            CURLOPT_HEADER => false, // return headers 
            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
            CURLOPT_ENCODING => "", // handle all encodings 
            //CURLOPT_USERAGENT      => $userAgent, // who am i 
            CURLOPT_AUTOREFERER => true, // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect 
            CURLOPT_TIMEOUT => 120, // timeout on response 
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects 
            CURLOPT_POST => false
        );
        $cUrl = curl_init();
        curl_setopt_array($cUrl, $options);
        $datax = curl_exec($cUrl);
        $js = json_decode($datax,true);//json_decode(json_decode(json_encode($datax)), true);
        $datasp = array();
        $dataws = array();
        $datane = array();
        //var_dump($js);
        if($js){
            foreach ($js as $row) {
                $sama=0;
                $param=array();
                $param["no_mesin"]  = $row["nomesin"];
                $param["motor_kpb"] = $row["motorkpb"];
                $param["no_part_oli_1"]    = $row["nopartoli"];
                $param["no_part_oli_2"]    = $row["nopartoli2"];
                $param["isi_oli_1"] = $row["isioli"];
                $param["harga_oli_1"]       = $row["hargaoli"];
                $param["no_part_oli_2a"]    = $row["nopartoli_1"];
                $param["no_part_oli_2b"]    = $row["nopartoli_2"];
                $param["isi_oli_2"] = $row["isioli_2"];
                $param["harga_oli_2"]       = $row["hargaoli_2"];
                $param["nominal_jasa"]      = $row["nominjasa"];
                $param["kpb_ke"]      = $row["serviceke"];
                $param["kd_kpb"]      = $row["kodekpb"];
                $param["kd_segment"]      = $row["segmentkpb"];
                $hasil = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/kpb_part",$param));
            
                if($hasil){
                    if($hasil->totaldata > 0){
                        $datasp[]=$hasil->message;
                    }else{
                        $datane[]=$row;
                    }
                }
            }
        }
        ob_flush();
        flush();
        $data["listmd"] = $datane; 
        $data["jumlahdata"] = count($datasp);
        $data["selesai"] = microtime(true);
        if($debug){
            
            var_dump($datane);exit();
        }
        $this->load->view('form_tambah/add_kpb_part', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_kpb_part() {
        ini_set('max_execution_time', 200);
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));

        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'no_mesin' => $data[0][$i]["nomesin"],
                'motor_kpb' => $data[0][$i]["motorkpb"],
                'no_partoli' => $data[0][$i]["nopartoli"],
                'no_partoli2' => $data[0][$i]["nopartoli2"],
                'isi_oli' => $data[0][$i]["isioli"],
                'harga_oli' => $data[0][$i]["hargaoli"],
                'nopart_oli1' => $data[0][$i]["nopartoli_1"],
                'nopart_oli2' => $data[0][$i]["nopartoli_2"],
                'isi_oli2' => $data[0][$i]["isioli_2"],
                'harga_oli2' => $data[0][$i]["hargaoli_2"],
                'nominal_jasa' => $data[0][$i]["nominjasa"],
                'kpb_ke' => $data[0][$i]["serviceke"],
                'kd_kpb' => $data[0][$i]["kodekpb"],
                'kd_segment' => $data[0][$i]["segmentkpb"],
                'created_by' => $this->session->userdata("user_id")
            );

            $hasil = ($this->curl->simple_post(API_URL . "/api/sparepart/kpb_part", $param, array(CURLOPT_BUFFERSIZE => 10)));
            $datar = json_decode($hasil);
            if ($datar->recordexists == TRUE) {
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL . "/api/sparepart/kpb_part", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . "Data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal di simpan',
                'status' => false
            );

            $this->output->set_output(json_encode($data));
        }
    }

    public function kpb_part_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/kpb_part"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_MESIN;
            $data_message[1][$key] = $message->MOTOR_KPB;
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
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
