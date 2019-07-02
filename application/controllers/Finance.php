<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/controllers/Cashier.php';

class Finance extends Cashier {

    var $API;
    /**
     * [__construct description]
     */
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

        if ($this->session->userdata('kd_div') == null) {
            redirect("auth");
        }
    }

    /**
     * [transaksi description]
     * @param  [type] $dataonly [description]
     * @return [type]           [description]
     */
    public function transaksi($dataonly=null) {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'MASTER_TRANS.*',
            'orderby' => 'MASTER_TRANS.ID desc'
        );



        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans", $param));


        /* var_dump($this->curl->simple_get(API_URL."/api/login/user", $param));
          exit(); */
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        /* var_dump($data);
          exit(); */

        $this->template->site('master/master_transaksi', $data);
    }
    /**
     * [add_transaksi_simpan description]
     */
    public function add_transaksi_simpan() {

        $this->form_validation->set_rules('kd_trans', 'Kode Transaksi', 'required|trim');
        $this->form_validation->set_rules('nama_trans', 'Nama Transaksi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            //$akun = substr($this->input->post("kd_akun"), 0, strpos($this->input->post("kd_akun"), ' '));
            $param = array(
                'kd_trans' => $this->input->post("kd_trans"),
                'nama_trans' => $this->input->post("nama_trans"),
                'tipe_trans' => $this->input->post("tipe"),
                'tipe_ar' => $this->input->post("ar") ? $this->input->post("ar") : 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            //var_dump($param);exit;

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/trans", $param, array(CURLOPT_BUFFERSIZE => 10));
            if($hasil){
                $hasil=json_decode($hasil);
                if($hasil->recordexists==true){
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = $this->curl->simple_put(API_URL . "/api/accounting/trans", $param, array(CURLOPT_BUFFERSIZE => 10));
                }
            }
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            //$this->data_output($hasil, 'post', base_url('finance/transaksi'));
            redirect(base_url('finance/transaksi'));
        }
    }
    /**
     * [edit_transaksi description]
     * @param  [type] $kd_trans   [description]
     * @param  [type] $row_status [description]
     * @return [type]             [description]
     */
    public function edit_transaksi($kd_trans, $row_status) {
        $this->auth->validate_authen('finance/transaksi');

        $param = array(
            'kd_trans' => $kd_trans,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans", $param));

        /* var_dump($data);
          exit; */

        $this->load->view('form_edit/edit_transaksi', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    /**
     * [update_transaksi description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update_transaksi($id) {
        $this->form_validation->set_rules('kd_trans', 'Kode Transaksi', 'required|trim');
        $this->form_validation->set_rules('nama_trans', 'Nama Transaksi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'kd_trans' => $this->input->post("kd_trans"),
                'nama_trans' => $this->input->post("nama_trans"),
                'tipe_trans' => $this->input->post("tipe"),
                'tipe_ar' => $this->input->post("ar") ? $this->input->post("ar") : 0,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            //var_dump($param);exit;

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_put(API_URL . "/api/accounting/trans", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            redirect(base_url('finance/transaksi'));
        }
    }
    /**
     * [delete_transaksi description]
     * @param  [type] $kd_trans [description]
     * @return [type]           [description]
     */
    public function delete_transaksi($kd_trans) {
        $param = array(
            'kd_trans' => $kd_trans,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/accounting/trans", $param));

        $this->data_output($data, 'delete', base_url('finance/transaksi'));
    }
    /**
     * [transaksi_typeahead description]
     * @return [type] [description]
     */
    public function transaksi_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_TRANS;
            $data_message[1][$key] = $message->NAMA_TRANS;
            
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }

    /**
     * [perkiraan description]
     * @param  [type] $dataonly [description]
     * @return [type]           [description]
     */
    public function perkiraan($dataonly=null) {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'MASTER_ACC_KODEAKUN_V.*',
            'orderby' => 'MASTER_ACC_KODEAKUN_V.NO_AKUN ASC, MASTER_ACC_KODEAKUN_V.NO_SUBAKUN ASC'
        );
        if($dataonly==true){
            $param=array("kd_akun" => $this->input->get("kd_akun"));
        }

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/kdakun", $param));
        if($dataonly==true){
            return $data["list"];
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
            $this->template->site('master/master_perkiraan', $data);
        }
    }
    /**
     * [add_perkiraan description]
     */
    public function add_perkiraan() {
        $this->auth->validate_authen('finance/perkiraan');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/kdakun"));


        $this->load->view('form_tambah/add_perkiraan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    /**
     * [add_perkiraan_simpan description]
     */
    public function add_perkiraan_simpan() {

        $this->form_validation->set_rules('kd_akun', 'Kode Perkiraan', 'required|trim');
        $this->form_validation->set_rules('nama_akun', 'Nama Perkiraan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            //$akun = substr($this->input->post("kd_akun"), 0, strpos($this->input->post("kd_akun"), ' '));
            $param = array(
                'kd_akun' => $this->input->post("kd_akun"),
                'nama_akun' => $this->input->post("nama_akun"),
                'saldo_awal' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            //var_dump($param);exit;

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/kdakun", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('finance/perkiraan'));
            //redirect(base_url('finance/perkiraan'));
        }
    }
    /**
     * [edit_perkiraan description]
     * @param  [type] $kd_akun    [description]
     * @param  [type] $row_status [description]
     * @return [type]             [description]
     */
    public function edit_perkiraan($kd_akun, $row_status) {
        $this->auth->validate_authen('finance/perkiraan');

        $param = array(
            'kd_akun' => $kd_akun,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/kdakun", $param));

        //var_dump($data["list"]);exit;

        $this->load->view('form_edit/edit_perkiraan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    /**
     * [update_perkiraan description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update_perkiraan($id) {
        $this->form_validation->set_rules('kd_akun', 'Kode Perkiraan', 'required|trim');
        $this->form_validation->set_rules('nama_akun', 'Nama Perkiraan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'kd_akun' => $this->input->post("kd_akun"),
                'nama_akun' => $this->input->post("nama_akun"),
                'saldo_awal' => 0,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            //var_dump($param);exit;
            
            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_put(API_URL . "/api/accounting/kdakun", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }
    /**
     * [delete_perkiraan description]
     * @param  [type] $kd_akun [description]
     * @return [type]          [description]
     */
    public function delete_perkiraan($kd_akun) {
        $param = array(
            'kd_akun' => $kd_akun,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/accounting/kdakun", $param));

        $this->data_output($data, 'delete', base_url('finance/perkiraan'));
    }
    /**
     * [mapping_akun description]
     * @return [type] [description]
     */
    public function mapping_akun(){
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'ID,KD_AKUN_LAMA,KD_AKUN_BARU,M.NAMA_AKUN',
            'jointable' => array(array("MASTER_ACC_KODEAKUN M","M.KD_AKUN = SETUP_ACC_MAPPING.KD_AKUN_BARU","LEFT")),
            'order by' => "KD_AKUN_BARU"
        );


        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/acc_mapping", $param));
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => link_pagination(),
            'total_rows' => $data["list"]->totaldata
        );


        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('accounting/mapping_kdakun', $data);
    }
    /**
     * [mapping_simpan description]
     * @return [type] [description]
     */
    function mapping_simpan(){
        $hasil=array();
        $param=array(
            'kd_akun_lama' =>$this->input->post("kd_akun_lama"),
            'kd_akun_baru' => $this->input->post('kd_akun_baru'),
            'created_by' => $this->session->userdata("user_id")
        );
        $hasil=($this->curl->simple_get(API_URL."/api/accounting/acc_mapping",$param));
        if($hasil){

        }
    }
    /**
     * [setup_jno description]
     * @param  [type] $dataonly [description]
     * @param  [type] $text     [description]
     * @return [type]           [description]
     */
    public function setup_jno($dataonly=null,$text=null){
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            //'limit' => 15,
            'field' => "*,CASE WHEN SUB_AKUN ='' THEN KD_AKUN ELSE {fn CONCAT(KD_AKUN,'.'plusSUB_AKUN)} END NO_AKUN",
            'orderby'=> "KD_TRANSAKSI,TYPE_AKUN,KD_AKUN,ID"
        );
        if($this->input->get("j")){
            $param['kd_transaksi'] = $this->input->get("j");
        }
        if($dataonly){
            $param=array(
                'kd_transaksi' => $this->input->get('kd'),
                'field' => "*,CASE WHEN SUB_AKUN ='' THEN KD_AKUN ELSE {fn CONCAT(KD_AKUN,'.'plusSUB_AKUN)} END NO_AKUN"
            );
        }
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/acc_jurnal_oto", $param));
        /*print_r($param);
        var_dump($data["list"]);exit();*/
        $html="";$n=0;
        if($dataonly==true){
            if($data["list"]){
                if($data["list"]->totaldata >0){
                    if($text==true){
                        foreach ($data["list"]->message as $key => $value) {
                            $n++;
                            $html .="<tr><td class='text-center'>".$n."<i class='fa fa-spinner fa-spin hidden' id='l_".$value->ID."'></i></td>";
                            $html .="<td class='text-center'><a onclick='__hpus_item(\"".$value->ID."\")' title='hapus item kd akun'><i class='fa fa-trash'></i></a></td>";
                            $html .="<td class='table-nowrap'>".$value->TYPE_TRANSAKSI."</td>";
                            $html .="<td class='table-nowrap'>".$value->CARA_BAYAR."</td>";
                            $html .="<td class='table-nowrap'>".$value->KD_AKUN."</td>";
                            $html .="<td class='td-overflow-50'>".($value->NAMA_AKUN)."</td>";
                            $html .="<td class='text-center'>".$value->TYPE_AKUN."</td><td>&nbsp;</td></tr>";
                        }
                        echo $html;
                    }else{
                        echo json_encode($data["list"]->message);
                    }
                }
            }
            exit();
        }
        $params=array(
            "field" => "SETUP_JURNAL_OTO_V.KD_TRANSAKSI,M.NAMA_TRANS",
            "groupby" => TRUE,
            "jointable" => array(array("MASTER_TRANS M","M.KD_TRANS=SETUP_JURNAL_OTO_V.KD_TRANSAKSI","LEFT"))/*,
            "orderby" =>"SETUP_ACC_JURNAL_OTO.ID DESC"*/
        );
        /*if($this->input->get("j")){
            $params['kd_transaksi'] = $this->input->get("j");
        }*/
        $data["trans"] =json_decode($this->curl->simple_get(API_URL . "/api/jurnal/acc_jurnal_oto", $params));
        //var_dump($data["trans"]);exit();
        $config = array(
            'per_page' => $data["list"]->totaldata,//$param['limit'],
            'base_url' => link_pagination(),
            'total_rows' => $data["list"]->totaldata
        );


        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('accounting/setup_jurnal', $data);   
    }
    /**
     * [jno_add description]
     * @param  [type] $no_trans [description]
     * @return [type]           [description]
     */
    function jno_add($no_trans=null){
        $data=array();
        $param=array(
            //'kasir' =>'1',
            'orderby' =>'KD_TRANS,NAMA_TRANS,ISNULL(KASIR_TRANS,0)'
        );
        $data["trans"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/trans", $param));
        $data["no_trans"] = $no_trans;
        $this->load->view('accounting/setup_jurnal_add', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    /**
     * [jno_simpan description]
     * @return [type] [description]
     */
    function jno_simpan(){
        $hasil=array();$data=array();
        $proses="post";
        $param=array();
        $param["kd_maindealer"]     = $this->session->userdata("kd_maindealer");
        $param["kd_transaksi"]      = $this->input->post("kd_transaksi");
        $param["type_transaksi"]    = $this->input->post("tp_transaksi");
        $param["cara_bayar"]        = $this->input->post("tp_trans");
        $param["periode_tahun"]     = ($this->input->post("periode_tahun"))?$this->input->post("periode_tahun"):date('Y');
        $param["periode_bulan"]     = ($this->input->post("periode_bulan"))?$this->input->post("periode_bulan"):date('m');
        $param["kd_akun"]           = $this->input->post("kd_akun_1");
        $param["sub_akun"]          = $this->input->post("sub_akun");
        $param["type_akun"]         = $this->input->post("type_akun");
        $param["created_by"]        = $this->session->userdata("user_id");

        $hasil= $this->curl->simple_post(API_URL."/api/jurnal/acc_jurnal_oto",$param);
        if($hasil){
            $data=json_decode($hasil);
            if($data->recordexists==true){
                $proses ="put";
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil= $this->curl->simple_put(API_URL."/api/jurnal/acc_jurnal_oto",$param);
            }
        }
        //$this->session->set_flashdata('tr-active', $id);

        $this->data_output($hasil, $proses);
    }
    /**
     * [jno_delete description]
     * @return [type] [description]
     */
    function jno_delete(){
        $param=array(
            'id'    => $this->input->get('id'),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil= $this->curl->simple_delete(API_URL."/api/jurnal/acc_jurnal_oto",$param);
        $this->data_output($hasil,'delete');
    }
    /**
     * [jno_delete description]
     * @return [type] [description]
     */
    function jno_delete_all(){
        $param=array(
            'id'    => $this->input->get('kd_transaksi'),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil= $this->curl->simple_delete(API_URL."/api/jurnal/acc_jurnal_oto/true",$param);
        $this->data_output($hasil,'delete');
    }
    /**
     * [perkiraan_typeahead description]
     * @return [type] [description]
     */
    public function perkiraan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/kdakun"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_AKUN;
            $data_message[0][$key] = $message->NAMA_AKUN;
            
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }
    /**
     * [jurnal_list description]
     * @param  [type] $debug [description]
     * @return [type]        [description]
     */
    function jurnal_list($debug=null){
        $param=array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'jointable' => array(array("TRANS_JURNAL_DETAIL_VIEW TD", "TD.NO_JURNAL=TRANS_JURNAL.NO_JURNAL AND TD.TP_TRANS=2","LEFT")),
            'field' =>"TRANS_JURNAL.*,DEBET,KREDIT,BALANCE",
            'orderby' =>"TGL_JURNAL DESC,TRANS_JURNAL.ID DESC,TRANS_JURNAL.NO_JURNAL"
        );
        $param['custom'] =($this->input->get("bulan"))?" MONTH(TGL_JURNAL)='".$this->input->get("bulan")."'":" MONTH(TGL_JURNAL)='".date('m')."'";
        $param['custom'] .=($this->input->get("tahun"))?" AND YEAR(TGL_JURNAL)='".$this->input->get("tahun")."'":" AND YEAR(TGL_JURNAL)='".date('Y')."'";
        $data["trans"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal",$param));
        if($debug==true){
            print_r($param);
            print_r($data["trans"]);
            exit();
        }
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => link_pagination()[0],
            'total_rows' => $data["trans"]->totaldata
        );
        $paramd=array();
        if($this->session->userdata("nama_group")!="Root"){
            $paramd=array(
                'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
            );
        }
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer"));
        $param=array(
            'field' => 'YEAR(TGL_JURNAL) AS TAHUN',
            'groupby_text'=>"YEAR(TGL_JURNAL)",
            'orderby' => "YEAR(TGL_JURNAL)"
        );
        $data["thn"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal",$param));

        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('accounting/jurnal_list', $data);   
    }
    function jurnal_new($no_jurnal=null){
        $data=array();
        $paramd=array(
                'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
         );
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",$paramd));
        if($no_jurnal){
            $param=array(
                'no_jurnal' => $no_jurnal
            );
            $data["jurnal_h"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal",$param));
            $param["orderby"] ="TP_TRANS,NOM,KD_AKUN";
            $data["jurnal_d"] = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail/true",$param));
        }
        $this->load->view('accounting/jurnal_add', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    function jurnal_detail($no_jurnal=null){
        $data=array();
        $html="";
        //if($no_jurnal){ echo $html;exit();}
        $param=array(
            'no_jurnal' =>($this->input->get("no_jurnal"))?$this->input->get("no_jurnal"): $no_jurnal
        );
        $param["orderby"] ="NOM,TP_TRANS,KD_AKUN";
        $data = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail/true",$param));
        if($data){
            $n=0;
            if($data->totaldata >0){
                foreach ($data->message as $key => $value) {
                    $n++; $DEBET="";$KREDIT="";$BALANCE="";
                    //$disabled=($value->CLOSING_STATUS==0)?"":"disabled-action";
                    $DEBET =($value->DEBET >0)?number_format($value->DEBET):'';
                    $KREDIT =($value->KREDIT >0)?number_format($value->KREDIT):'';
                    $BALANCE =($value->BALANCE >0)?number_format($value->BALANCE):'';
                    if($value->TP_TRANS=='1'){
                        $html .='<tr>
                                <td class="text-center table-nowarp">'.$n.'</td>
                                <td>
                                    <a onclick="__hapus_jurnal_detail(\''.$value->ID.'\',\''.$value->NO_JURNAL.'\')" id="xls_'.$value->ID.'"><i class="fa fa-trash"></i></a>
                                </td>
                                <td class=\'text-left table-nowarp\'>'.$value->KD_AKUN.'</td>
                                <td class=\'tb-overflow-50\' title="'.$value->KETERANGAN_JURNAL.'">'. $value->KETERANGAN_JURNAL.'</td>
                                <td class=\'text-right table-nowarp\'>'.$DEBET.'</td>
                                <td class=\'text-right table-nowarp\'>'.$KREDIT.'</td>
                                <td>&nbsp;</td>
                            </tr>';
                    }else{ 
                        $html .='<tr class="total" style="font-weight: bold !important">
                                <td colspan="4" class="text-right">'.$value->KETERANGAN_JURNAL.'</td>
                                <td class=\'text-right table-nowarp\'>'.$DEBET.'</td>
                                <td class=\'text-right table-nowarp\'>'.$KREDIT.'</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr style="font-style: italic;">
                                <td colspan="5" class="text-right"><em>Balance</em></td>
                                <td class="text-right table-nowarp info">'.$BALANCE.'</td>
                                <td class="info"></td>
                            </tr>';
                    }
                }
            }
        }
        echo $html;

    }
    function jurnal_head_simpan(){
        $nomor_jurnal =($this->input->post("no_jurnal"))?$this->input->post("no_jurnal"): $this->no_jurnal($this->input->post("type_jurnal"));
        $keterangan_jurnal =$this->input->post("deskripsi_jurnal");       
        $result="";
        $param=array(
              'kd_maindealer' => $this->session->userdata("kd_maindealer"),
              'kd_dealer'     => $this->session->userdata("kd_dealer"),
              'pos_dealer'    =>"",
              'no_jurnal'     => $nomor_jurnal,
              'type_jurnal'   => $this->input->post("type_jurnal"),
              'tgl_jurnal'    => $this->input->post("tgl_jurnal"),
              'deskripsi_jurnal' => $keterangan_jurnal,
              'total_jurnal'  => '0',
              'closing_status' =>'0',
              'source_jurnal' => '',
              'created_by'    => $this->session->userdata("user_id")
        );
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal", $param));
        if($hasil){
            if($hasil->recordexists==true){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal", $param));
            }
        }
        //generate jurnal detail
        if($hasil){
            if($hasil->status==true){
                $result=$this->jurnal_detail_simpan($nomor_jurnal);
            }
        }
        echo $result;
    }
    function jurnal_detail_simpan($no_jurnal){
        //nomor urutan
        $no_urut=0;
        $paramx=array(
            'no_jurnal' => $no_jurnal
        );
        $data=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal_detail", $paramx));
        if($data){
            $no_urut = $data->totaldata;
        }
        $param = array(
            "no_jurnal"     => $no_jurnal,
            "urutan_jurnal" => $no_urut+1,
            "kd_akun"       => $this->input->post("kd_akun"),
            "keterangan_jurnal"=> $this->input->post("nama_akun"),
            "type_akun"     => $this->input->post("type_akun"),
            "jumlah"        => $this->input->post("jml"),
            "created_by"    => $this->session->userdata("user_id")
          );
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal_detail", $param));
        //print_r($param);exit();
        if($hasil){
            if($hasil->recordexists==true){
                //datapkan id data
                unset($param["keterangan_jurnal"]);
                unset($param["jumlah"]);
                unset($param["created_by"]);
                $dx=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail", $param));
                $param["id"] = $dx->message[0]->ID;
                $param["jumlah"] = $this->input->post("jml");
                $param["keterangan_jurnal"] =$this->input->post("nama_akun");
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal_detail", $param));
            }
        }
        return $no_jurnal;
    }
    function hapus_jurnal(){
        $param=array(
            'no_jurnal' => $this->input->get("no_jurnal")
        );
        $hasil=json_decode($this->curl->simple_delete(API_URL . "/api/jurnal/jurnal", $param));
        $this->data_output($hasil,'delete');
    }
    function hapus_jurnal_detail(){
         $param=array(
            'id' => $this->input->get("id")
        );
        $hasil=json_decode($this->curl->simple_delete(API_URL . "/api/jurnal/jurnal_detail", $param));
        $this->data_output($hasil,'delete');
    }

    /**
     * [saldoawal description]
     * @param  [type] $dataonly [description]
     * @return [type]           [description]
     */
    public function saldoawal($dataonly=null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'TRANS_ACC_SALDOAWAL.*'
        );
        if($dataonly==true){
            $param=array("kd_akun" => $this->input->get("kd_akun"));
        }
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }

        if($this->input->get('edit')){

            $param_edt = array(
                'custom' => "ID = ".$this->input->get('edit')
            );
            $data["edit"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/acc_saldoawal", $param_edt));
        }
        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/acc_saldoawal", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

    // var_dump($data);exit;

        if($dataonly==true){
            return $data["list"];
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
            $this->template->site('accounting/trans_saldoawal', $data);
        }
    }
    /**
     * [add_saldoawal_simpan description]
     */
    public function add_saldoawal_simpan() {


        //$this->form_validation->set_rules('kd_akun', 'Kode Akun', 'required|trim');
        $this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
        //var_dump($_POST);exit();
            //$akun = substr($this->input->post("kd_akun"), 0, strpos($this->input->post("kd_akun"), ' '));
            $param = array(

                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_akun' => $this->input->post("kd_akun")?$this->input->post("kd_akun"):$this->input->post("kd_akun_edit"),
                'nama_akun' => $this->input->post("nama_akun"),
                'saldo_awal' => $this->input->post("saldo_awal"),
                'created_by' => $this->session->userdata('user_id')
            );

            // var_dump($param);exit;

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/acc_saldoawal", $param, array(CURLOPT_BUFFERSIZE => 10));
            $metode = 'post';
            if($hasil){
                // $hasil=json_decode($hasil);
                if(json_decode($hasil)->recordexists==TRUE){
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = $this->curl->simple_put(API_URL . "/api/accounting/acc_saldoawal", $param, array(CURLOPT_BUFFERSIZE => 10));
                    $metode = 'put';
                }
            }
            // $data = json_decode($hasil);
            // var_dump($hasil);exit;
            // $this->session->set_flashdata('tr-active', $hasil->message);
            $this->data_output($hasil, $metode, base_url('finance/saldoawal'));

            //redirect(base_url('finance/saldoawal'));
        }
    }

    /**
     * [delete_saldoawal description]
     * @param  [type] $kd_trans [description]
     * @return [type]           [description]
     */
    public function delete_saldoawal($kd_trans) {
        $param = array(
            'id' => $kd_trans,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/accounting/acc_saldoawal", $param));

        $this->data_output($data, 'delete', base_url('finance/saldoawaL'));
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
