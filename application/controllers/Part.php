<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . "controllers\inventori.php";
class Part extends Inventori {
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
    //Customer
    /**
     * [customer description]
     * @return [type] [description]
     */
    public function part($dataOnly=null,$detail=null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'oderby' => "ID DESC"
        );
        if($dataOnly==true && !$detail){
            $param = array(
                'keyword' => $this->input->get('q')
            );
            if(!$this->input->get("q")){
                $param['limit'] =100;// $this->input->get('limit');
                $param['offset']= ($this->input->get('p') == null) ? 0 : $this->input->get('p', TRUE);
            }
        }else if($dataOnly==true && $detail==true){
            $param=array('part_number'=>$this->input->get('p'));
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        if($dataOnly==true){
            if(isset($data["list"])){
                if($data["list"]->totaldata>0){
                    echo json_encode($data["list"]->message);
                }else{
                    echo "[]";
                }
            }else{
                echo "[]";
            }
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
            $this->template->site('master/master_part', $data);
        }
    }
    /**
     * [add_customer description]
     */
    public function add_part() {
        $this->auth->validate_authen('part/part');
        $this->load->view('form_tambah/add_part');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [add_customer_simpan description]
     */
    public function import_part() {
        ini_set('max_execution_time', 120);
        ini_set('post_max_size', 0);
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
        $hasilx = "";
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
                $filename = $_FILES["file"]["tmp_name"];
                /* var_dump($_FILES);
                  exit(); */
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    $n = 0;
                    $x = 0;
                    $param[$x]["param"] = array();
                    $arr = array();
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        //$arr[]=$importdata;
                        $arr[] = array(
                            'part_number' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'part_deskripsi' => !empty($importdata[1]) ? strtoupper(rtrim(stripslashes($importdata[1]))) : '',
                            'het' => !empty($importdata[2]) ? $importdata[2] : '0',
                            'harga_beli' => (!empty(trim($importdata[3])) || (int) $importdata[3] > 0) ? $importdata[3] : '0',
                            'kd_supplier' => !empty($importdata[4]) ? $importdata[4] : '',
                            'kd_groupsales' => !empty($importdata[5]) ? $importdata[5] : '',
                            'part_reference' => !empty($importdata[6]) ? $importdata[6] : '',
                            'part_status' => !empty($importdata[7]) ? $importdata[7] : '',
                            'part_superseed' => !empty($importdata[8]) ? $importdata[8] : '',
                            'moq_dk' => !empty($importdata[9]) ? $importdata[9] : '0',
                            'moq_dm' => !empty($importdata[10]) ? $importdata[10] : '0',
                            'moq_db' => !empty($importdata[11]) ? $importdata[11] : '0',
                            'part_numbertype' => !empty($importdata[12]) ? $importdata[12] : '',
                            'part_moving' => !empty($importdata[13]) ? $importdata[13] : '',
                            'part_source' => !empty($importdata[14]) ? $importdata[14] : '',
                            'part_rank' => !empty($importdata[15]) ? $importdata[15] : '',
                            'part_current' => !empty($importdata[16]) ? $importdata[16] : '',
                            'part_type' => !empty($importdata[17]) ? $importdata[17] : '',
                            'part_lifetime' => !empty($importdata[18]) ? $importdata[18] : '',
                            'part_group' => !empty($importdata[19]) ? $importdata[19] : '',
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        //$param[$x]=$arr;
                        /* if($n==100){
                          $n=0;
                          $hasilx = $this->simpan_part($arr);
                          } */
                        $n++;
                    }
                    //API Url
                    $url = API_URL . "/api/sparepart/partbatch";
                    //Initiate cURL.
                    $ch = curl_init($url);
                    //Encode the array into JSON.
                    $jsonDataEncoded = (base64_encode(json_encode($arr))); //json_encode($arr);
                    //Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1);
                    //curl_setopt($ch, , 1);
                    //Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
                    //Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    //Execute the request
                    $hasilx = curl_exec($ch);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
            }
        }
        $data = json_encode($hasilx);
        var_dump($data);exit();
        $this->data_output($data, 'post', base_url('part/part'));
    }
    function simpan_part($json) {
        $paramsales = array(
            'query' => $this->Custom_model->simpan_part(json_encode($json))
        );
        //var_dump($paramsales);
        $hasil = $this->curl->simple_post(API_URL . "/api/sales/salesmannew", $paramsales, array(CURLOPT_BUFFERSIZE => 500));
        return $hasil;
    }
    public function notEmpty() {
        if (!empty($_FILES['file']['name'])) {
            return TRUE;
        } else {
            $this->form_validation->set_message('notEmpty', 'The {field} field can not be empty.');
            return FALSE;
        }
    }
    /**
     * [edit_customer description]
     * @param  [type] $kd_customer [description]
     * @return [type]              [description]
     */
    public function edit_part($part_number) {
        $this->auth->validate_authen('part/part');
        $param = array(
            'part_number' => $part_number
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        $paramgroup = array(
            'field' => "KD_GROUPSALES",
            'groupby' => true
        );
        $data["group"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $paramgroup));
        $this->template->site('form_edit/edit_part', $data);
    }
    /**
     * [update_customer description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update_part() {
        $this->form_validation->set_rules('part_number', 'Part Number', 'required|trim');
        $this->form_validation->set_rules('part_deskripsi', 'Part Deskripsi', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'part_number' => $this->input->post("part_number"),
                'part_deskripsi' => $this->input->post("part_deskripsi"),
                'het' => $this->input->post("het"),
                'harga_beli' => $this->input->post("harga_beli"),
                'kd_supplier' => $this->input->post("kd_supplier"),
                'kd_groupsales' => $this->input->post("kd_groupsales"),
                'part_reference' => $this->input->post("part_reference"),
                'part_status' => $this->input->post("part_status"),
                'part_superseed' => $this->input->post("part_superseed"),
                'moq_dk' => $this->input->post("moq_dk"),
                'moq_dm' => $this->input->post("moq_dm"),
                'moq_db' => $this->input->post("moq_db"),
                'part_numbertype' => $this->input->post("part_numbertype"),
                'part_moving' => $this->input->post("part_moving"),
                'part_source' => $this->input->post("part_source"),
                'part_rank' => $this->input->post("part_rank"),
                'part_current' => $this->input->post("part_current"),
                'part_type' => $this->input->post("part_type"),
                'part_lifetime' => $this->input->post("part_lifetime"),
                'part_group' => $this->input->post("part_group"),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sparepart/part", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $param["part_number"]);
            $this->data_output($hasil, 'put', base_url('part/part'));
        }
    }
    /**
     * [customer_typeahead description]
     * @return [type] [description]
     */
    public function part_typeahead($part_number = '') {
        /* if($kd_dealer!=''){
          $param[""]
          } */
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part"));
        if ($data) {
            if (is_array($data["list"]->message)) {
                foreach ($data["list"]->message as $key => $message) {
                    $data_message[0][$key] = $message->PART_NUMBER;
                    $data_message[1][$key] = $message->PART_DESKRIPSI;
                }
                $result['keyword'] = array_merge($data_message[0], $data_message[1]);
                $this->output->set_output(json_encode($result));
            }
        }
    }
    public function picking_typeahead($jenis, $type=null) {
        $data=array();
        if ($jenis == 'pkb') {
            $param = array(
                'jointable' => array(
                    array("TRANS_PKB_DETAIL PD", "PD.NO_PKB=TRANS_PKB.NO_PKB AND PD.ROW_STATUS>=0", "LEFT")
                ),
                'custom' => "ISNULL(PD.PICKING_STATUS,0) = 0 AND (TRANS_PKB.STATUS_PKB between 1 AND 5) AND PD.KATEGORI = 'Part' AND TRANS_PKB.KD_DEALER = '" . $this->session->userdata("kd_dealer") . "'",
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'field' => "TRANS_PKB.NO_PKB AS NO_TRANS,TRANS_PKB.NO_POLISI AS NAMA_CUSTOMER,TRANS_PKB.NAMA_TYPEMOTOR AS ALAMAT",
                'groupby_text' => "TRANS_PKB.NO_PKB,TRANS_PKB.NO_POLISI,TRANS_PKB.NAMA_TYPEMOTOR"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        } elseif($jenis == 'so') {
            $param = array(
                'jointable' => array(
                    array("TRANS_PARTSO_DETAIL PD", "PD.NO_TRANS=TRANS_PARTSO.NO_TRANS AND PD.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_PARTSO_CUSTOMER PC","PC.NO_TRANS=TRANS_PARTSO.NO_TRANS AND PC.ROW_STATUS >=0","LEFT")
                ),
                'custom' => "ISNULL(TRANS_PARTSO.BOOKING_ORDER,0)=0 AND ISNULL(PD.PICKING_STATUS,0)=0",
                // 'custom' => "((PD.PICKING_STATUS IS NULL OR PD.PICKING_STATUS = 0) AND TRANS_PARTSO.SO_STATUS <=1 AND TRANS_PARTSO.BOOKING_ORDER >= 1)", 
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'field' => 'TRANS_PARTSO.NO_TRANS,UPPER(PC.NAMA_CUSTOMER)NAMA_CUSTOMER,PC.ALAMAT',
                'groupby_text' => "TRANS_PARTSO.NO_TRANS,PC.NAMA_CUSTOMER,PC.ALAMAT",
                'orderby' => "TRANS_PARTSO.NO_TRANS"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso", $param));
        }elseif($jenis == 'retur') {
            $param = array(
                'jointable' => array(
                    array("TRANS_RETUR_JUALBELI_DETAIL PD", "PD.NO_TRANS=TRANS_RETUR_JUALBELI.NO_TRANS AND PD.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_PARTSO_CUSTOMER PC","PC.NO_TRANS = TRANS_RETUR_JUALBELI.NO_REFF AND PC.ROW_STATUS >=0","LEFT")
                ),
                'custom' => " ISNULL(PD.PICKING_STATUS,0)=0 AND TRANS_RETUR_JUALBELI.JENIS_RETUR = 'Pembelian'",
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'field' => 'TRANS_RETUR_JUALBELI.NO_TRANS,UPPER(PC.NAMA_CUSTOMER)NAMA_CUSTOMER,PC.ALAMAT',
                'groupby_text' => "TRANS_RETUR_JUALBELI.NO_TRANS,PC.NAMA_CUSTOMER,PC.ALAMAT"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli", $param));
        }
        if($type == 'true'){
            $this->output->set_output(json_encode($data));
        }
        else{
            $result['keyword'] = [];
            if (is_array($data->message)) {
                foreach ($data->message as $key => $message) {
                    $data_message[0][$key] = $message->NO_TRANS;
                }
                $result['keyword'] = array_merge($data_message[0]);
            }
            $this->output->set_output(json_encode($result));
        }
        // var_dump($data);
    }
    public function get_picking($jenis=null,$data_only=null) {
        ini_set('max_execution_time',120);
        if ($jenis == 'pkb') {
            $param = array(
                'no_pkb' => $this->input->get('no_trans'),
                'jointable' => array(
                    array("TRANS_PKB TP", "TP.NO_PKB=TRANS_PKB_DETAIL.NO_PKB AND TP.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_SJKELUAR_DETAIL SJD", "SJD.NO_RANGKA=TP.NO_RANGKA AND SJD.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_SJKELUAR SJ", "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_PART MP", "MP.PART_NUMBER=TRANS_PKB_DETAIL.KD_PEKERJAAN AND MP.ROW_STATUS>=0", "LEFT")
                ),
                "field" => "TRANS_PKB_DETAIL.KD_PEKERJAAN AS PART_NUMBER, TRANS_PKB_DETAIL.ID, TRANS_PKB_DETAIL.QTY AS JUMLAH, TRANS_PKB_DETAIL.TOTAL_HARGA AS PRICE, TRANS_PKB_DETAIL.HARGA_SATUAN AS HARGA_JUAL, MP.PART_DESKRIPSI",
                'custom' => "(TRANS_PKB_DETAIL.PICKING_STATUS = 0 OR TRANS_PKB_DETAIL.PICKING_STATUS IS NULL) AND (TRANS_PKB_DETAIL.PICKING_REFF = 0 OR TRANS_PKB_DETAIL.PICKING_REFF IS NULL) AND TRANS_PKB_DETAIL.KATEGORI = 'Part'"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail", $param));
        } elseif ($jenis == 'so') {
            $param = array(
                'no_trans' => $this->input->get('no_trans'),
                'jointable' => array(
                    array("TRANS_PARTSO SO", "SO.NO_TRANS=TRANS_PARTSO_DETAIL.NO_TRANS AND SO.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_PART MP", "MP.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER AND MP.ROW_STATUS>=0", "LEFT")
                ),
                "field" => "TRANS_PARTSO_DETAIL.PART_NUMBER, TRANS_PARTSO_DETAIL.ID, TRANS_PARTSO_DETAIL.JUMLAH_ORDER AS JUMLAH, TRANS_PARTSO_DETAIL.HARGA_JUAL AS PRICE, TRANS_PARTSO_DETAIL.HARGA_JUAL, MP.PART_DESKRIPSI",
                'custom' => "(ISNULL(SO.BOOKING_ORDER,0)=0 AND ISNULL(TRANS_PARTSO_DETAIL.PICKING_STATUS,0)=0)"
                // 'custom' => "(TRANS_PARTSO_DETAIL.PICKING_STATUS = 0 OR TRANS_PARTSO_DETAIL.PICKING_STATUS IS NULL) AND (TRANS_PARTSO_DETAIL.PICKING_REFF = 0 OR TRANS_PARTSO_DETAIL.PICKING_REFF IS NULL)"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso_detail", $param));
        } elseif ($jenis == 'retur') {
            $param = array(
                'no_trans' => $this->input->get('no_trans'),
                'jointable' => array(
                    array("TRANS_RETUR_JUALBELI RJ", "RJ.NO_TRANS=TRANS_RETUR_JUALBELI_DETAIL.NO_TRANS AND RJ.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_PART MP", "MP.PART_NUMBER=TRANS_RETUR_JUALBELI_DETAIL.PART_NUMBER AND MP.ROW_STATUS>=0", "LEFT")
                ),
                // "field" => "TRANS_RETUR_JUALBELI_DETAIL.PART_NUMBER, TRANS_RETUR_JUALBELI_DETAIL.ID, TRANS_RETUR_JUALBELI_DETAIL.JUMLAH, MP.PART_DESKRIPSI",
                "field" => "TRANS_RETUR_JUALBELI_DETAIL.PART_NUMBER, TRANS_RETUR_JUALBELI_DETAIL.ID, TRANS_RETUR_JUALBELI_DETAIL.JUMLAH, 0 AS PRICE, 0 AS HARGA_JUAL, MP.PART_DESKRIPSI",
                'custom' => "(TRANS_RETUR_JUALBELI_DETAIL.PICKING_STATUS = 0 OR TRANS_RETUR_JUALBELI_DETAIL.PICKING_STATUS IS NULL) AND (TRANS_RETUR_JUALBELI_DETAIL.PICKING_REFF = 0 OR TRANS_RETUR_JUALBELI_DETAIL.PICKING_REFF IS NULL) AND RJ.JENIS_RETUR = 'PEMBELIAN'"
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/accounting/retur_jualbeli_detail", $param));
        }
        // var_dump($data);exit;
        $html = '';
        $no = 1;
        if(!$data_only){
            if(isset($data)){
                if($data->totaldata >0){
                    foreach ($data->message as $key => $picking_row) {
                        $html .= '<tr>';
                        $html .= "<td class='hidden'>" . $picking_row->ID . "</td>";
                        $html .= "<td class='hidden'>" . $picking_row->PART_NUMBER . "</td>";
                        $html .= "<td class='hidden'>" . $picking_row->PRICE . "</td>";
                        $html .= "<td class='hidden'>" . $picking_row->HARGA_JUAL . "</td>";
                        $html .= "<td class='text-center'>" . $no . "</td>";
                        $html .= "<td>" . $picking_row->PART_NUMBER . " - " . $picking_row->PART_DESKRIPSI ."<span class='badge pull-right' title='Stock On Hand'></span></td>";
                        $html .= "<td class='text-right'>" . $picking_row->JUMLAH . "</td>";
                        $html .= "<td><select name='kd_gudang' id='kd_gudang_" . $key . "' class='form-control kd_gudang' select-val='" . $key . "' required='true'>
                                  <option value=''>--Pilih Gudang--</option>";
                                  $gdg = $this->getStock($picking_row->PART_NUMBER,'GDG');
                                    if($gdg){
                                        foreach ($gdg as $row) {
                                            $html .="<option value='".$row["kd_gudang"]."'>".$row["kd_gudang"]." [".number_format($row["stocked"],0)."]</option>";
                                        }
                                    }
                        $html .="</select></td>";
                        $html .= '<td><select name="kd_rakbin" id="kd_rakbin_' . $key . '" class="form-control" required="true" disabled>
                                    <option value="">- Pilih Rak -</option>';
                                    $bins = $this->getStock($picking_row->PART_NUMBER,'BIN');
                                    if($bins){
                                        foreach ($bins as $row) {
                                            $html .="<option value='".$row["kd_bin"]."'>".$row["kd_bin"]." [ ".number_format($row["stocked"],0)." ]</option>";
                                        }
                                    }
                        $html .= '</select></td>';
                        // $html .= "<td>" . $this->get_rakbin($key, $picking_row->PART_NUMBER) . "</td>";
                        $html .= '</tr>';
                        $html .= "";
                        $no++;
                    }
                }
            }
        }else{
            if(isset($data)){
                $html=$data;
            }
        }
        $this->output->set_output(json_encode($html));
    }
    function getStock($part_number=null,$output='STK',$debug=null){
        $data=array();$result="";
        $data=$this->parts4picking($part_number);
        $total_stock=0;$bin=array();$gudang=array();$harga="0";
        $n=0;
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    if($value->ID==50){
                        $total_stock += $value->JUMLAH_SAK;
                    }
                    $harga = $value->HARGA_JUAL;
                    if($value->ID==50){
                        $gudang[$n]=array(
                            'kd_gudang' => $value->KD_GUDANG,
                            'stocked'   => $value->JUMLAH_SAK,
                            'harga_jual'=> $value->HARGA_JUAL
                        );
                    }else{
                        $bin[$n]=array(
                            'kd_bin'    => $value->KD_RAKBIN,
                            'kd_gudang' => $value->KD_GUDANG,
                            'stocked'   => $value->JUMLAH_SAK,
                            'harga_jual'=> $value->HARGA_JUAL
                        );
                    }
                    $n++;
                }
            }
        }
        switch($output){
            case 'GDG': $result = $gudang;break;
            case 'BIN': $result = $bin;break;
            case 'STK': $result = array('stock'=>$total_stock,'harga_jual'=>$harga);break;
            default: $result = $total_stock;break;
        }
        if($debug){
            //var_dump($result);
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    /**
     * Gets the rakbin.
     *
     * @param      <type>  $key          The key
     * @param      <type>  $part_number  The part number
     */
    public function get_rakbin($key=null, $part_number=null) {
        $param = array(
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_gudang' => $this->input->get('kd_gudang'),
            'jointable' => array(
                array("MASTER_GUDANG MG", "MG.KD_GUDANG=MASTER_LOKASI_RAK_BIN.KD_GUDANG", "LEFT"),
            ),
            'custom' => "MG.JENIS_GUDANG = 'PART'",
            'field' => "MASTER_LOKASI_RAK_BIN.KD_LOKASI AS KD_RAKBIN, MASTER_LOKASI_RAK_BIN.RAK_DEFAULT AS DEFAULTS"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $param));
        // }
        // var_dump($data);exit;
        $html = '';
        // $html .= '<select name="kd_rakbin" id="kd_rakbin_' . $key . '" class="form-control" required="true">';
        // $html .= '<option value="">- Pilih Rakbin -</option>';
        if ($data && (is_array($data->message) || is_object($data->message))):
            foreach ($data->message as $key => $rakbin) :
                $html .= '<option value="' . $rakbin->KD_RAKBIN . '" ' . ($rakbin->DEFAULTS == 1 ? "selected" : " ") . '>' . $rakbin->KD_RAKBIN . '</option>';
            endforeach;
        endif;
        // $html .= '</select>';
        $this->output->set_output(json_encode($html));
        // return $data;
    }
    /**
     * Gets the gudang.
     *
     * @param      string  $key        The key
     * @param      string  $kd_gudang  The kd gudang
     *
     * @return     string  The gudang.
     */
    public function get_gudang($key, $kd_gudang = null) {
        $html = '';
        $param = array(
            'custom' => "KD_DEALER = '" . $this->session->userdata('kd_dealer') . "' AND JENIS_GUDANG = 'PART'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
        $disable = "";
        $html .= '<select name="kd_gudang" id="kd_gudang_' . $key . '" class="form-control kd_gudang" select-val="' . $key . '" required="true">';
        $html .= '<option value="">- Pilih Gudang -</option>';
        foreach ($data["list"]->message as $key => $gudang) :
            if ($kd_gudang != '' || $kd_gudang != null):
                if (($kd_gudang == $gudang->KD_GUDANG)):
                    $default = " selected";
                else:
                    $default = ($gudang->DEFAULTS == 1) ? " selected" : " ";
                endif;
            else:
                $default = ($gudang->DEFAULTS == 1) ? " selected" : " ";
            endif;
            $html .= '<option value="' . $gudang->KD_GUDANG . '" ' . $default . ' >' . $gudang->NAMA_GUDANG . '</option>';
        endforeach;
        $html .= '</select>';
        return $html;
    }
    /**
     * { function_description }
     *
     * @param      boolean  $dataOnly  The data only
     * @param      integer  $forList   For list
     *
     * @return     array    ( description_of_the_return_value )
     */
    public function sim_part($dataOnly=null,$forList=null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            /* 'jointable' => array(
              array("MASTER_PART MP", "MP.PART_NUMBER=MASTER_SIM_PARTS.PART_NUMBER", "LEFT"),
              ), */
            'field' => '*',
            'orderby' => 'MASTER_SIM_PARTS.ID desc'
        );
        if($dataOnly==true){
            $param=array(
                'kd_dealer'=>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
                'field' => "KATEGORI_AHASS"
            );
            $datad=json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
            $kategori_ahass="A";
            if($datad){
                if($datad->totaldata>0){
                    unset($param["field"]);
                    foreach ($datad->message as $key => $value) {
                        $kategori_ahass = $value->KATEGORI_AHASS;
                    }
                }
            }
            $param=array(
                "kategori_ahass" => $kategori_ahass,
                "part_number"   => $this->input->get("p")
            );
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/sim_parts", $param));
        if($dataOnly==true){
            if($data["list"]){
                if($data["list"]->totaldata >0){
                    echo json_encode($data["list"]->message);
                }else{
                    echo "[".json_encode(array('JUMLAH_STANDARITEM_MIN'=>'0'))."]";
                }
            }else{
                echo "[]";
            }
            if($forList==1){
                echo json_encode($data["list"]);
            }else if ($forList==2){
                return $data["list"];
            }
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
            $this->template->site('master/master_sim_part', $data);
        }
    }
    /**
     * [add_customer description]
     */
    public function add_sim_part() {
        $this->auth->validate_authen('part/sim_part');
        $this->load->view('form_tambah/add_sim_part');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [add_customer_simpan description]
     */
    public function import_sim_part() {
        ini_set('max_execution_time', 120);
        ini_set('post_max_size', 0);
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
        $hasilx = "";
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            try {
                $filename = $_FILES["file"]["tmp_name"];
                /* var_dump($_FILES);
                  exit(); */
                if ($_FILES['file']['size'] > 0) {
                    //$hasil="Berhasil";
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    $n = 0;
                    $x = 0;
                    $param[$x]["param"] = array();
                    $arr = array();
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        //$arr[]=$importdata;
                        $arr[] = array(
                            'kategori_ahass' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'part_number' => !empty($importdata[1]) ? rtrim($importdata[1]) : '',
                            'jumlah_standaritem_min' => !empty($importdata[2]) ? rtrim($importdata[2]) : '',
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        //$param[$x]=$arr;
                        /* if($n==100){
                          $n=0;
                          $hasilx = $this->simpan_part($arr);
                          } */
                        $n++;
                    }
                    //API Url
                    $url = API_URL . "/api/sparepart/pdsimbatch";
                    //Initiate cURL.
                    $ch = curl_init($url);
                    //Encode the array into JSON.
                    $jsonDataEncoded = (base64_encode(json_encode($arr))); //json_encode($arr);
                    //Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1);
                    //curl_setopt($ch, , 1);
                    //Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
                    //Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    //Execute the request
                    $hasilx = curl_exec($ch);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server.';
                $hasilx = $response;
            }
        }
        $data = json_encode($hasilx);
        //var_dump($data);exit();
        $this->data_output($data, 'post', base_url('part/sim_part'));
    }
    /**
     * [edit_customer description]
     * @param  [type] $kd_customer [description]
     * @return [type]              [description]
     */
    public function edit_sim_part($part_number) {
        $this->auth->validate_authen('part/sim_part');
        $param = array(
            'part_number' => $part_number
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/sim_parts", $param));
        //$data["parts"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/parts"));
        $this->template->site('form_edit/edit_sim_part', $data);
    }
    /**
     * [update_customer description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update_sim_part() {
        $this->form_validation->set_rules('kategori_ahass', 'Kategori Ahass', 'required|trim');
        $this->form_validation->set_rules('part_number', 'Part Number', 'required|trim');
        $this->form_validation->set_rules('jumlah_standaritem_min', 'Kategori Ahass', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kategori_ahass' => $this->input->post("kategori_ahass"),
                'part_number' => $this->input->post("part_number"),
                'jumlah_standaritem_min' => $this->input->post("jumlah_standaritem_min"),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sparepart/sim_parts", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'put');
        }
    }
    public function sim_part_typeahead() {
        /* if($kd_dealer!=''){
          $param[""]
          } */
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/sim_parts"));
        if ($data) {
            if (is_array($data["list"]->message)) {
                foreach ($data["list"]->message as $key => $message) {
                    $data_message[0][$key] = $message->PART_NUMBER;
                }
                $result['keyword'] = array_merge($data_message[0]);
                $this->output->set_output(json_encode($result));
            }
        }
    }
    public function defaultrakvsparts() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' =>($this->input->get('kd_dealer'))? $this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
            /*'jointable' => array(
                array("MASTER_DEALER AS MD ", "TRANS_PART_DEFAULT_RAK.KD_DEALER=MD.KD_DEALER", "LEFT"),
                array("MASTER_LOKASI_RAK_BIN AS ML", "TRANS_PART_DEFAULT_RAK.LOKASI_RAK_BIN_ID=ML.KD_LOKASI", "LEFT")
            ),
            'field' => 'TRANS_PART_DEFAULT_RAK.*, ML.KD_LOKASI, MD.NAMA_DEALER',*/
            'orderby' => 'TRANS_PART_DEFAULT_RAK.ID desc',
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/parts_vs_defaultrak", $param));
        $urls =explode('&page=', $_SERVER["REQUEST_URI"]);       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        //var_dump($data);
        $this->template->site('master/master_defaultrakvsparts', $data);
    }
    public function add_defaultrakvsparts($id=null) {
        $this->auth->validate_authen('master_service/defaultrakvsparts');
        $paramcustomer=array();
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["raks"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $paramcustomer));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",$paramcustomer));
        $paramcustomer["jenis_gudang"] = 'PART';
        $data["gudang"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang",$paramcustomer));
        if($id){
            $param = array(
                "custom" => "ID='" . $id . "'"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/parts_vs_defaultrak", $param));
        }
        $this->load->view('form_tambah/add_defaultrakvsparts', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function add_defaultrakvsparts_simpan() {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $part=explode(" - ", $this->input->post('part_number'));
            $param = array(
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'part_number' => $part[0],
                'kd_lokasi' => $this->input->post('kd_lokasi'),
                'kd_gudang' => $this->input->post('kd_gudang'),
                'keterangan' => $this->input->post('keterangan'),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'created_by' => $this->session->userdata('user_id')
            );
            if($this->input->post('id')){
                //if($data->recordexists==true){
                    $param["id"] = $this->input->post('id');
                    $param['row_status'] = 0;
                    $param["lastmodified_by"] =$this->session->userdata('user_id');
                    $hasil =($this->curl->simple_put(API_URL . "/api/marketing/parts_vs_defaultrak", $param, array(CURLOPT_BUFFERSIZE => 10))); 
                //}
                $this->session->set_flashdata('tr-active', $param["part_number"]);
            }else{
                $hasil = $this->curl->simple_post(API_URL . "/api/marketing/parts_vs_defaultrak", $param, array(CURLOPT_BUFFERSIZE => 10));
                if($hasil){
                    /*$data = json_decode($hasil);
                    if($data->recordexists==true){
                        $param["id"] = $this->input->post('id');
                        $param['row_status'] = 0;
                        $param["lastmodified_by"] =$this->session->userdata('user_id');
                        $hasil =($this->curl->simple_put(API_URL . "/api/marketing/parts_vs_defaultrak", $param, array(CURLOPT_BUFFERSIZE => 10))); 
                    }
                    $this->session->set_flashdata('tr-active', $param["part_number"]);*/
                }
            }
            $this->data_output($hasil, 'post', base_url('part/defaultrakvsparts'));
        }
    }
    public function edit_defaultrakvsparts($id, $row_status) {
        $this->auth->validate_authen('part/defaultrakvsparts');
        $param = array(
            "custom" => "TRANS_PARTS_VS_DEFAULTRAK.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        //$paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/parts_vs_defaultrak", $param));
        $data["parts"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part"));
        $data["raks"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin"));
        $this->load->view('form_edit/edit_defaultrakvsparts', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_defaultrakvsparts($id) {
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'part_number' => $this->input->post("part_number"),
                'lokasi_rak_bin_id' => $this->input->post("kd_lokasi"),
                'keterangan' => $this->input->post('keterangan'),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/parts_vs_defaultrak", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_defaultrakvsparts($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/parts_vs_defaultrak", $param));
        $this->data_output($data, 'delete', base_url('part/defaultrakvsparts'));
    }
    public function defaultrakvsparts_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/parts_vs_defaultrak"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->LOKASI_RAK_BIN_ID;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    public function lokasirakbin() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            //'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'kd_gudang' => $this->input->get('kd_gudang'),
            'jointable' => array(
                array("MASTER_DEALER AS MD ", "MASTER_LOKASI_RAK_BIN.KD_DEALER=MD.KD_DEALER", "LEFT"),
                array("MASTER_GUDANG AS MG", "MASTER_LOKASI_RAK_BIN.KD_GUDANG=MG.KD_GUDANG AND MASTER_LOKASI_RAK_BIN.KD_DEALER=MG.KD_DEALER", "LEFT")
            ),
            'field' => 'MASTER_LOKASI_RAK_BIN.*, MD.NAMA_DEALER, MG.NAMA_GUDANG',
            'orderby' => 'MASTER_LOKASI_RAK_BIN.ID desc',
            "custom" => "MASTER_LOKASI_RAK_BIN.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $param));  
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));     
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_lokasirakbin', $data);
    }
    public function add_lokasirakbin() {
        $lokasirakbin_default = 0;
        $this->auth->validate_authen('part/lokasirakbin');
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $paramcustomer["jenis_gudang"] ='PART';
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["gudangs"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $paramcustomer));
        unset($paramcustomer["jenis_gudang"]);
        $lokasirakbin = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $paramcustomer));
        if ($lokasirakbin) {
            if ((int) $lokasirakbin->totaldata > 0) {
                foreach ($lokasirakbin->message as $key => $value) {
                    if ($value->DEFAULTS == 1) {
                        $lokasirakbin_default = 1;
                    }
                }
            }
        }
        $data["lokasirakbin"] = $lokasirakbin_default;
        $this->load->view('form_tambah/add_lokasirakbin', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_lokasirakbin_simpan() {
        $this->form_validation->set_rules('kd_lokasi', 'Kode Lokasi', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_rak', 'Kode Rak', 'required|trim');
        $this->form_validation->set_rules('kd_binbox', 'Kode Bin', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_lokasi' => $this->input->post("kd_lokasi"),
                'kd_gudang' => $this->input->post("kd_gudang"),
                'nama_gudang' => $this->input->post("nama_gudang"),
                'kd_rak' => $this->input->post("kd_rak"),
                'kd_binbox' => $this->input->post("kd_binbox"),
                'keterangan' => $this->input->post("keterangan"),
                'row_status' => 0,
                'rak_default' => ($this->input->post("rak_default")) ? 1 : 0,
                'defaults' => $this->input->post("defaults"),
                'defaults1' =>0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/lokasi_rak_bin", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('part/lokasirakbin'));
        }
    }
    public function edit_lokasirakbin($id, $row_status) {
        $lokasirakbin_default = 0;
        $this->auth->validate_authen('part/lokasirakbin');
        $kd_dealer = $this->session->userdata("kd_dealer");
        $param = array(
            "custom" => "MASTER_LOKASI_RAK_BIN.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $param));
        if ($data["list"]) {
            if ($data["list"]->totaldata > 0) {
                foreach ($data["list"]->message as $key => $value) {
                    $kd_dealer = $value->KD_DEALER;
                }
            }
        }
        $param = array(
            'row_status' => 0,
            'kd_dealer' => $kd_dealer
        );
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $param["jenis_gudang"]='PART';
        $data["gudangs"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
        $lokasirakbin = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $param));
        if ($lokasirakbin) {
            if ((int) $lokasirakbin->totaldata > 0) {
                foreach ($lokasirakbin->message as $key => $value) {
                    if ($value->DEFAULTS == 1) {
                        $lokasirakbin_default = 1;
                    }
                }
            }
        }
        $data["lokasirakbin"] = $lokasirakbin_default;
        $this->load->view('form_edit/edit_lokasirakbin', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_lokasirakbin($id) {
        $this->form_validation->set_rules('kd_lokasi', 'Kode Lokasi', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_rak', 'Kode Rak', 'required|trim');
        $this->form_validation->set_rules('kd_binbox', 'Kode Bin', 'required|trim');
        //$this->form_validation->set_rules('keterangan', 'keterangan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_lokasi' => $this->input->post("kd_lokasi"),
                'kd_gudang' => $this->input->post('kd_gudang'),
                'nama_gudang' => $this->input->post('nama_gudang'),
                'kd_rak' => $this->input->post('kd_rak'),
                'kd_binbox' => $this->input->post('kd_binbox'),
                'keterangan' => $this->input->post('keterangan'),
                'row_status' => $this->input->post("row_status"),
                'rak_default' => ($this->input->post('rak_default')) ? 1 : 0,
                'defaults' => $this->input->post('defaults'),
                'defaults1' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/lokasi_rak_bin", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_lokasirakbin($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/lokasi_rak_bin", $param));
        $this->data_output($data, 'delete', base_url('part/lokasirakbin'));
    }
    public function lokasirakbin_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_LOKASI;
        }
        $result['keyword'] = array_merge($data_message[0]);
        $this->output->set_output(json_encode($result));
    }
    public function part_detail_typeahead($keywords = null, $price = null) {
        $data = [];
        $keywords = $this->input->get('keyword');
        $param = array(
            'keyword' => $keywords
        );
        $data_message = [];
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        // var_dump($data); exit;
        $desk=($this->input->get("d"));
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] =($desk)? $message->PART_NUMBER ." - ".$message->PART_DESKRIPSI:$message->PART_NUMBER;
                }
            } else {
                $data_message['keyword'][] = ""; //$data->message;
            }
        } else {
            $data_message['keyword'][0] = "<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,
        $this->output->set_output(json_encode($data_message));
    }
    function part_stok($debug=null) {
        $data = array();
        $keyword = explode('-', $this->input->get('keyword'));
        $param = array(
            'keyword' => $keyword[0],
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'user_login' => str_replace("-","",$this->session->userdata("user_id")),
            'stoked_only' => $this->input->get("os")
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $gndata=$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param);
        //var_dump($gndata);exit();
        $rand=substr($param["user_login"],0,10);
        $param["custom"] = "JUMLAH_SAK >0 AND ID IN(70)";
        $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view",$param));
        unset($param["kustom"]);
        $data["list_d"] =json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view",$param));
        if($debug){
            //print_r($param);
            //$gndata=$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param);
            var_dump($data["list"]);exit();
        }
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('warehouse/part_stok', $data);
    }
    function parts4gen($n=null){
        $param = array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->inpu->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'kd_lokasi' =>($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi"),
            'user_login' => str_replace("-","",$this->session->userdata("user_id"))
        );
        if($this->input->get('s')=='d'){
            $param["tgl_trans"] = $this->input->get('tgl');
        }
        //var_dump($param);exit();
        $direct=($n)?$n:$this->input->get("d");
        $gndata=($direct)?$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param):"";
        if ($this->input->get("d")){ 
            echo json_encode($gndata);
        }else {
            return $gndata;
        }
    }
    function parts4picking($part_number=null,$debug=null,$posisi="0"){
        $data=array();$result="";
        $param = array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'kd_lokasi' =>($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi"),
            'user_login' => str_replace("-","",$this->session->userdata("user_id"))
        );
        $direct=$this->input->get("d");
        $gndata=($direct)?$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param):"";
        $rand=substr($param["user_login"],0,10);
        $param["custom"] ="";
        if($part_number){
            $param["custom"] = "PART_NUMBER='".$part_number."' AND";
        }
        $param["custom"] .= " JUMLAH_SAK >0 AND ID IN(0,1,2,9,50)";
        $data =json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view",$param));
        /*var_dump($gndata);
        exit();*/
        $result=$data;
        if($debug){
            echo json_encode($data->message);
        }else{
            return $result;
        }
    }
    /**
     * { function_description }
     *
     * @param      <type>        $debug       The debug if you want show return to screen fill true
     * @param      <type>        $type_motor  The type motor
     *
     * @return     array|string  ( description_of_the_return_value )
     */
    function parts4so($debug=null,$type_motor=null,$mode="0"){
        $data=array();$result="";$param=array();
        $offset =($this->input->get('p'))?($this->input->get('p')-1)*$this->input->get('per_page'):0;
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        // $mode = 1 muncukan semua data parta 0=hanya yang punya stock
        switch($mode){
            case "1":
                $param['kd_dealer'] = $kd_dealer;
                $param['field']="RTRIM(PART_NUMBER) AS PART_NUMBER,PART_DESKRIPSI,'0' STOCK,FORMAT(HET,'N0')HET";
                if($this->input->get("q")){
                    $param['keyword']   = $this->input->get('q');
                }else{
                    $param['offset']    = $offset; 
                    $param['limit']     = ($this->input->get('per_page'))?$this->input->get('per_page'):15;
                }
                if($this->input->get("part")){
                    $param=array();
                    $param["part_number"] = $this->input->get("part");
                    $param['field']="RTRIM(PART_NUMBER) AS PART_NUMBER,PART_DESKRIPSI,'0' STOCK,HET";
                }
                
                $list =json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part",$param));
                
            break;
            default:
                $param = array(
                    'kd_dealer' =>($this->input->get("kd_dealer"))?$this->inpu->get("kd_dealer"):$this->session->userdata("kd_dealer"),
                    'kd_lokasi' => $this->input->get("kd_lokasi"),
                    'user_login' => str_replace("-","",$this->session->userdata("user_id")),
                    
                );
                $param["field"] = "RTRIM(PART_NUMBER) AS PART_NUMBER,PART_DESKRIPSI,JUMLAH_SAK AS STOCK,FORMAT(HARGA_JUAL,'N0') AS HET";
                if($this->input->get("q")){
                    $param['keyword']   = $this->input->get('q');
                }else{
                    $param['offset']    = $offset; 
                    $param['limit']     = ($this->input->get('per_page'))?$this->input->get('per_page'):15;
                }
                if($this->input->get("part")){
                    $param=array();
                    $param["part_number"] = $this->input->get("part");
                    $param['kd_dealer'] = $kd_dealer;
                    $param['user_login'] = str_replace("-","",$this->session->userdata("user_id"));
                    $param["field"] = "RTRIM(PART_NUMBER) AS PART_NUMBER,PART_DESKRIPSI,JUMLAH_SAK AS STOCK,HARGA_JUAL AS HET";
                }
                //$gndata=$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param);
                $rand=substr($param["user_login"],0,10);
                $param["custom"] =($this->input->get('os'))? "JUMLAH_SAK >0 AND ID IN(59)":"JUMLAH_SAK >=0 AND ID IN(59)";
                $list =json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view",$param));
                //var_dump($list);
            break;
            
        }
        if($list){
            if($list->totaldata >0) {
                $data = array(
                    'p' => $this->input->get('q'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => $list->message
                );
            }else{
                $data=array(
                    'p' => $this->input->get('p'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => array()
                );
            }
        }else{
            $data=array(
                'p' => $this->input->get('p'), 
                'count' => 0,//$list->totaldata, 
                'per_page' => $this->input->get('per_page'), 
                'data' => array()
            );
        }
        $dataOnly=$this->input->get("dt");
        if(!$dataOnly){
            if($debug){
                $this->output->set_output(json_encode($data));
            }
            else{
                $this->output->set_output(json_encode($data));
            }
        }
        else{
            if($list){
                if($list->totaldata >0){
                    $result = $list->message;
                }
            }
            if($debug){
                echo ($type_motor)?json_encode($list): json_encode($list->message);
            }else{
                return $result;
            }
        }
    }
    function partstock_ovr($debug=null){
        $data=array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->template->site('inventori/stock_overview_part', $data);
    }
    public function part_stok_typeahead() {
        $param = array(
            "custom" => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/part_stock", $param));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER . " - " . $message->PART_DESKRIPSI;
        }
        $result['keyword'] = array_merge($data_message[0]); //, $data_message[1]
        $this->output->set_output(json_encode($result));
    }
    public function partstok_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_stock"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->KD_GUDANG;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    public function hargabeli_md() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PART as MP", "MP.PART_NUMBER=TRANS_PART_SJMASUK.PART_NUMBER", "LEFT"),
                array("MASTER_DEALER as MD", "MD.KD_DEALER=TRANS_PART_SJMASUK.KD_DEALER", "LEFT")
            ),
            'field' => 'TRANS_PART_SJMASUK.*, MP.PART_DESKRIPSI, MD.NAMA_DEALER',
            'orderby' => 'TRANS_PART_SJMASUK.CREATED_TIME desc',
            "custom" => "TRANS_PART_SJMASUK.KD_DEALER='" . $this->session->userdata("kd_dealer") . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_sjmasuk", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_hargabeli_md', $data);
    }
    public function add_hargabeli_md() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/hargabeli_md"));
        $this->load->view('form_tambah/add_hargabeli_md', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function simpan_hargabeli_md() {
        $this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required|trim');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'nama_unit' => $this->input->post("nama_unit"),
                'harga_beli' => $this->input->post("harga_beli"),
                'keterangan' => $this->input->post("keterangan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/hargabeli_md", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('part/hargabeli_md'));
        }
    }
    public function edit_hargabeli_md($id, $row_status){
        $this->auth->validate_authen('part/hargabeli_md');
        $param = array(
            'custom' => "id = '" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/hargabeli_md", $param));
        $this->load->view('form_edit/edit_hargabeli_md', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_hargabeli_md($id) {
        $this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required|trim');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'nama_unit' => $this->input->post("nama_unit"),
                'harga_beli' => $this->input->post("harga_beli"),
                'keterangan' => $this->input->post("keterangan"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sparepart/hargabeli_md", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function history_hargabeli_md($id) {
        $data = array();
        $param_detail = array(
            'field' => '*',
            "custom" => "TRANS_PART_SJMASUK.PART_NUMBER='" . $id . "' AND TRANS_PART_SJMASUK.KD_DEALER='" . $this->session->userdata("kd_dealer") . "'"
        );
        $data["list_detail"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_sjmasuk", $param_detail));
        //var_dump($data["list_detail"]);exit;
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PART as MP", "MP.PART_NUMBER=TRANS_PART_SJMASUK.PART_NUMBER", "LEFT"),
                array("MASTER_DEALER as MD", "MD.KD_DEALER=TRANS_PART_SJMASUK.KD_DEALER", "LEFT")
            ),
            'field' => 'TRANS_PART_SJMASUK.*, MP.PART_DESKRIPSI, MD.NAMA_DEALER',
            'orderby' => 'TRANS_PART_SJMASUK.CREATED_TIME desc',
            //'groupby' => 'TRANS_PART_SJMASUK.PART_NUMBER',
            "custom" => "TRANS_PART_SJMASUK.PART_NUMBER='" . $id . "' AND TRANS_PART_SJMASUK.KD_DEALER='" . $this->session->userdata("kd_dealer") . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_sjmasuk", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_hargabeli_md_history', $data);
    }
    public function delete_hargabeli_md($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sparepart/hargabeli_md", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('part/hargabeli_md')
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
    public function hargabeli_md_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/hargabeli_md"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_UNIT;
        }
        $result['keyword'] = array_merge($data_message[0]);
        $this->output->set_output(json_encode($result));
    }
    //Master divisi
    public function groupcustomerpart() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_company' => $this->input->get('kd_company'),
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_PART_GROUPCUSTOMER.KD_DEALER", "LEFT"),
                array("MASTER_COMPANY MC", "MC.KD_COMPANY=TRANS_PART_GROUPCUSTOMER.KD_PERUSAHAAN", "LEFT"),
                array("MASTER_KABUPATEN K", "K.KD_KABUPATEN=TRANS_PART_GROUPCUSTOMER.KD_KOTAPERUSAHAAN", "LEFT")
            ),
            'field' => 'TRANS_PART_GROUPCUSTOMER.*, TRANS_PART_GROUPCUSTOMER.ID as ID, TRANS_PART_GROUPCUSTOMER.ROW_STATUS, MC.KD_COMPANY',
            'orderby' => 'TRANS_PART_GROUPCUSTOMER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_groupcustomer", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/groupcustomerpart/view', $data);
    }
    public function add_groupcustomerpart() {
        $this->auth->validate_authen('part/groupcustomerpart');
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_groupcustomer"));
        $data["kotas"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten"));
        $data["companies"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $paramcustomer));
        $this->load->view('master_service/groupcustomerpart/add', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function add_groupcustomerpart_simpan() {
        $this->form_validation->set_rules('kd_groupcp', 'Kode Group Customer Part', 'required|trim|max_length[5]');
        //$this->form_validation->set_rules('nama_perusahaan', 'Kode Perusahaan', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_groupcp' => $this->input->post("kd_groupcp"),
                'kd_perusahaan' => $this->input->post('kd_company'),
                'nama_perusahaan' => $this->input->post("nama_company"),
                'alamat_perusahaan' => $this->input->post("alamat_perusahaan"),
                'kd_kotaperusahaan' => $this->input->post("kd_kabupaten"),
                'notel_perusahaan' => $this->input->post("notel_perusahaan"),
                'ket_tambahan' => $this->input->post("ket_tambahan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/part_groupcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('part/groupcustomerpart'));
        }
    }
    public function edit_groupcustomerpart($id, $row_status) {
        $this->auth->validate_authen('part/groupcustomerpart');
        $param = array(
            'custom' => "id = '" . $id . "'",
            'row_status' => $row_status
        );
        $paramdealer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data = array();
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_groupcustomer", $param));
        $data["kotas"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten"));
        $data["companies"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $paramdealer));
        $this->load->view('master_service/groupcustomerpart/edit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_groupcustomerpart($id) {
        $this->form_validation->set_rules('kd_groupcp', 'Kode Grup Customer Part', 'required|trim|max_length[5]');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_groupcp' => $this->input->post("kd_groupcp"),
                'kd_perusahaan' => $this->input->post('kd_company'),
                'nama_perusahaan' => $this->input->post("nama_company"),
                'alamat_perusahaan' => $this->input->post("alamat_perusahaan"),
                'kd_kotaperusahaan' => $this->input->post("kd_kabupaten"),
                'notel_perusahaan' => $this->input->post("notel_perusahaan"),
                'ket_tambahan' => $this->input->post("ket_tambahan"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/part_groupcustomer", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_groupcustomerpart($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/part_groupcustomer", $param));
        $this->data_output($data, 'delete', base_url('part/groupcustomerpart'));
    }
    public function history_het() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'ID'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        //var_dump($data);exit();
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/history_het/view', $data);
    }
    public function history_het_view($id) {
        $this->auth->validate_authen('part/history_het');
        $data = array();
        $param_detail = array(
            'field' => '*',
            "custom" => "MASTER_PART.ID='" . $id . "'"
        );
        $data["list_detail"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param_detail));
        //var_dump($data["list_detail"]);exit;
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PART as MP", "MP.PART_NUMBER=MASTER_PART_BACKUP.PART_NUMBER", "LEFT")
            ),
            'field' => 'MASTER_PART_BACKUP.*, MP.PART_DESKRIPSI',
            'orderby' => 'MASTER_PART_BACKUP.CREATED_TIME DESC',
            "custom" => "MASTER_PART_BACKUP.ID_PART='" . $id . "'"
                /* "custom" => "MASTER_STNK_BPKB_BACKUP.KD_DEALER='".$data["list_detail"]->message[0]->KD_DEALER."' &&",
                  "custom" => "MASTER_STNK_BPKB_BACKUP.KD_PROPINSI='".$data["list_detail"]->message[0]->KD_PROPINSI."' &&",
                  "custom" => "MASTER_STNK_BPKB_BACKUP.KD_KABUPATEN='".$data["list_detail"]->message[0]->KD_KABUPATEN."' &&",
                  "custom" => "MASTER_STNK_BPKB_BACKUP.KD_TIPEMOTOR='".$data["list_detail"]->message[0]->KD_TIPEMOTOR."'" */
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_backup", $param));       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/history_het/detail', $data);
    }
    public function history_het_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_backup"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->PART_NUMBER;
            $data_message[1][$key] = $message->PART_DESKRIPSI;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    /**
     * [partvstipemotor description]
     * @return [type] [description]
     */
    public function partvstipemotor() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PART AS MD ", "MASTER_PVTM.NO_PART_TIPEMOTOR=MD.PART_NUMBER", "LEFT")
            ),
            'field' => 'MASTER_PVTM.*, MD.PART_DESKRIPSI',
            'orderby' => 'MASTER_PVTM.NO_PART_TIPEMOTOR, MASTER_PVTM.TYPE_MARKETING'
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/pvtm", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/partvstipemotor/view', $data);
    }
    public function add_partvstipemotor() {
        $this->auth->validate_authen('part/partvstipemotor');
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
        //$data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $this->load->view('master_service/partvstipemotor/add');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function import_partvstipemotor() {
        ini_set('max_execution_time', 120);
        ini_set('post_max_size', 0);
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
        $hasilx = "";
        $response = false;
        if (!$this->form_validation->run()) {
            $response['status'] = 'form-incomplete';
            $response['errors'] = array(
                array(
                    'field' => 'input[name="file"]',
                    'error' => form_error('file')
                )
            );
        } else {
            // try {
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES['file']['size'] > 0) {
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    $n = 0;
                    $x = 0;
                    $param[$x]["param"] = array();
                    $arr = array();
                    while (($importdata = fgetcsv($file, 1024, ";")) !== FALSE) {
                        $arr[] = array(
                            'no_part_tipemotor' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'type_marketing' => !empty($importdata[1]) ? rtrim($importdata[1]) : '',
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $n++;
                    }
                    //API Url
                    $url = API_URL . "/api/sparepart/pvtmbatch";
                    //Initiate cURL.
                    $ch = curl_init($url);
                    //Encode the array into JSON.
                    $jsonDataEncoded = (base64_encode(json_encode($arr))); //json_encode($arr);
                    //Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1);
                    //curl_setopt($ch, , 1);
                    //Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
                    //Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    //Execute the request
                    $hasilx = curl_exec($ch);
                    var_dump($arr);
                }
            // } catch (Exception $e) {
            //     $response['status'] = 'error';
            //     $response['message'] = 'Something went wrong while trying to communicate with the server.';
            // }
        }
        $data = json_decode($hasilx);
        var_dump($data);exit;
        $this->data_output($data, 'post', base_url('part/partvstipemotor'));
    }
    public function partvstipemotor_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/pvtm"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_PART_TIPEMOTOR;
        }
        $result['keyword'] = array_merge($data_message[0]);
        $this->output->set_output(json_encode($result));
    }
    public function picking_part() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'field' => 'TRANS_PART_PICKING.*, TRANS_PART_PICKING.ID as ID,TRANS_PART_PICKING.ROW_STATUS',
            'orderby' => 'TRANS_PART_PICKING.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_picking", $param));
        $param_detail = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("TRANS_PART_PICKING_DETAIL as PD", "PD.NO_TRANS=TRANS_PART_PICKING.NO_TRANS AND PD.ROW_STATUS >= 0", "LEFT")
            ),
            'field' => "PD.*, TRANS_PART_PICKING.NO_REFF,
                        CASE WHEN LEFT(TRANS_PART_PICKING.NO_REFF,2)='WO' THEN 'PKB' ELSE 'SO' END JENIS_REFF,
                        CASE WHEN LEFT(TRANS_PART_PICKING.NO_REFF,2)='WO' 
                        THEN (SELECT S.ID FROM TRANS_PKB_DETAIL AS S WHERE S.NO_PKB = TRANS_PART_PICKING.NO_REFF AND S.KD_PEKERJAAN = PD.PART_NUMBER AND S.ROW_STATUS >=0) 
                        ELSE (SELECT S.ID FROM TRANS_PARTSO_DETAIL AS S WHERE S.NO_TRANS = TRANS_PART_PICKING.NO_REFF AND S.PART_NUMBER = PD.PART_NUMBER AND S.ROW_STATUS >=0) END ID_REFF,
                        CASE WHEN LEFT(TRANS_PART_PICKING.NO_REFF,2)='WO' 
                        THEN (SELECT S.PICKING_STATUS FROM TRANS_PKB_DETAIL AS S WHERE S.NO_PKB = TRANS_PART_PICKING.NO_REFF AND S.KD_PEKERJAAN = PD.PART_NUMBER AND S.ROW_STATUS >=0) 
                        ELSE (SELECT S.PICKING_STATUS FROM TRANS_PARTSO_DETAIL AS S WHERE S.NO_TRANS = TRANS_PART_PICKING.NO_REFF AND S.PART_NUMBER = PD.PART_NUMBER AND S.ROW_STATUS >=0) END PICKING_REFF
                        "
        );
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_picking", $param_detail));

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        //var_dump($data);
        // $this->output->set_output(json_encode($data));
        $this->template->site('master_service/pickingpart/view', $data);
    }
    public function add_picking_part() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PART as MP", "MP.PART_NUMBER=TRANS_PART_PICKING_DETAIL.PART_NUMBER", "LEFT"),
                array("MASTER_LOKASI_RAK_BIN as MB", "MB.KD_LOKASI=TRANS_PART_PICKING_DETAIL.KD_RAKBIN", "LEFT"),
                array("MASTER_GUDANG as MG", "MG.KD_GUDANG=TRANS_PART_PICKING_DETAIL.KD_GUDANG", "LEFT"),
            ),
            'field' => 'TRANS_PART_PICKING_DETAIL.*, MP.PART_DESKRIPSI, MB.KD_LOKASI, MG.NAMA_GUDANG',
            'orderby' => 'TRANS_PART_PICKING_DETAIL.ID desc'
        );
        if ($this->input->get("u")) {
            $param_picking = array(
                "no_trans" => $this->input->get('u'),
                'jointable' => array(
                    array("TRANS_PART_PICKING_DETAIL TPD", "TPD.NO_TRANS=TRANS_PART_PICKING.NO_TRANS AND TPD.ROW_STATUS>=0", "LEFT")
                ),
                "field" => "TRANS_PART_PICKING.*, convert(char,TRANS_PART_PICKING.TGL_TRANS,112) AS TGL_TRANS, TPD.ID AS DETAIL_ID, TPD.PART_NUMBER, TPD.JUMLAH, TPD.HARGA_JUAL, TPD.PRICE,
                    (SELECT S.PART_DESKRIPSI FROM MASTER_PART as S WHERE S.PART_NUMBER=TPD.PART_NUMBER) AS PART_DESKRIPSI"
            );
            $data["part_picking"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_picking", $param_picking));
        }
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_picking", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        //$gendis=$this->parts4gen("1");
        $this->template->site('master_service/pickingpart/add', $data);
    }
    public function picking_part_simpan() {
        $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_trans('PP'));
        $param = array(
            //'id' => $this->input->post("id"),
            'no_trans' => $ntrans, //generate nomor transaksi
            'no_reff' => $this->input->post("no_reff"),
            'tgl_trans' => $this->input->post('tgl_trans'),
            'nama_konsumen' => NULL,
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/part_picking", $param, array(CURLOPT_BUFFERSIZE => 10));
        if ($hasil) {
            if (json_decode($hasil)->message > 0) {
                $update_reff = $this->update_reff($this->input->post("jenis_picking"), $this->input->post("no_reff"));
                $post_detail = $this->pickingpart_detail($ntrans);
            }
        }
        //test
        $this->data_output($hasil, 'post');
    }
    public function update_reff($jenis_picking, $no_reff) {
        if ($jenis_picking == 'SO') {
            $param = array(
                'no_trans' => $no_reff,
                'so_status' => '1',
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/so_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        elseif($jenis_picking == 'PKB') {
            $param = array(
                'no_pkb' => $no_reff,
                'status_approval' => '1',
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_pickingstatus", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
    }
    public function pickingpart_detail($ntrans) {
        $detail = json_decode($this->input->post("detail"), true);
        for ($i = 0; $i < count($detail); $i++) {
            $param = array(
                'no_trans' => $ntrans,
                'part_number' => $detail[$i]['part_number'],
                'jumlah' => $detail[$i]['jumlah'],
                'price' => $detail[$i]['price'],
                'harga_beli' => 0,
                'harga_jual' => $detail[$i]['harga_jual'],
                'part_batch' => NULL,
                'kd_gudang' => $detail[$i]['kd_gudang'],
                'kd_rakbin' => $detail[$i]['kd_rakbin'],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sparepart/part_picking_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            if ($hasil) {
                if (json_decode($hasil)->message > 0) {
                    $update_detail = $this->update_detail($this->input->post("jenis_picking"), $detail[$i]['id'], $ntrans);
                }
            }
            //var_dump($hasil);exit;
        }
        // $this->data_output($hasil, 'post');
    }
    public function update_detail($jenis_picking, $id, $no_reff) {
        if ($jenis_picking == 'PKB') {
            $param = array(
                'id' => $id,
                'picking_status' => 1,
                'picking_reff' => $no_reff,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_picking_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
        } elseif ($jenis_picking == 'SO') {
            $param = array(
                'id' => $id,
                'picking_status' => 1,
                'picking_reff' => $no_reff,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/inventori/so_picking_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            
        } elseif ($jenis_picking == 'RETUR') {
            $param = array(
                'id' => $id,
                'picking_status' => 1,
                'picking_reff' => $no_reff,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/accounting/retur_picking_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
    }
    public function delete_picking_part($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sparepart/part_picking", $param));
        $this->data_output($data, 'delete');
    }
    public function delete_part_pickint_detail($id, $jenis_reff, $id_reff) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        // var_dump($param);exit;
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sparepart/part_picking_detail", $param));
        if ($data["list"]->message > 0) {
            if ($jenis_reff == 'PKB') {
                $param = array(
                    'id' => $id_reff,
                    'picking_status' => 0,
                    'picking_reff' => NULL,
                    'lastmodified_by' => $this->session->userdata('user_id')
                );
                $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_picking_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            } else {
                $param = array(
                    'id' => $id_reff,
                    'picking_status' => 0,
                    'picking_reff' => NULL,
                    'lastmodified_by' => $this->session->userdata('user_id')
                );
                $hasil = $this->curl->simple_put(API_URL . "/api/inventori/so_picking_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            }
            $this->data_output($data, 'delete');
        }
    }
    public function cetak_picking_part() {
        $param_picking = array(
            "no_trans" => $this->input->get('u'),
            'jointable' => array(
                array("TRANS_PART_PICKING_DETAIL TPD", "TPD.NO_TRANS=TRANS_PART_PICKING.NO_TRANS AND TPD.ROW_STATUS>=0", "LEFT")
            ),
            "field" => "TRANS_PART_PICKING.*, TGL_TRANS, TPD.ID AS DETAIL_ID, TPD.PART_NUMBER, TPD.JUMLAH, TPD.HARGA_JUAL, TPD.PRICE, TPD.KD_RAKBIN, TPD.PART_BATCH,
                (SELECT S.PART_DESKRIPSI FROM MASTER_PART as S WHERE S.PART_NUMBER=TPD.PART_NUMBER) AS PART_DESKRIPSI"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_picking", $param_picking));
        $this->load->view('master_service/pickingpart/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function edit_picking_part() {
    }
    public function update_picking_part() {
    }
    public function picking_part_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_picking"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_TRANS;
            $data_message[1][$key] = $message->NAMA_KONSUMEN;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    /**
     * Customer Part
     */
    function customer_part() {
        $data = array();
        $totaldata = 0;
        $config = array();
        // $datax=array();
        $result = array();
        // $config['per_page'] = 15;
        $data["no_data"] = true;
        // $data['per_page'] = $config['per_page'];
        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("MASTER_PART AS MP ", "TRANS_PART_CUSTOMER_MAPPING.PART_NUMBER=MP.PART_NUMBER", "LEFT")
            ),
            'field' => "TRANS_PART_CUSTOMER_MAPPING.*,MP.PART_DESKRIPSI",
            "custom" => "TRANS_PART_CUSTOMER_MAPPING.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
                // 'groupby'   => TRUE
        );
        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_customer_mapping", $param));
        //var_dump($data["list"]);exit();
        $params = array(
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_CUSTOMER AS MC", "TRANS_PART_CUSTOMER_MAPPING.KD_CUSTOMER=MC.KD_CUSTOMER", "LEFT"),
                //array("MASTER_GROUPCUSTOMER_MAPPING AS MGC", "TRANS_PART_CUSTOMER_MAPPING.KD_GROUPCUSTOMER=MGC.KD_GROUPCUSTOMER", "LEFT"),
                array("MASTER_GROUPCUSTOMER AS MG", "MG.KD_GROUPCUSTOMER=TRANS_PART_CUSTOMER_MAPPING.KD_CUSTOMER", "LEFT")
            ),
            'field' => "TRANS_PART_CUSTOMER_MAPPING.KD_CUSTOMER, MC.NAMA_CUSTOMER , MG.NAMA_GROUPCUSTOMER ,TRANS_PART_CUSTOMER_MAPPING.JENIS",
            'groupby' => true,
            "custom" => "TRANS_PART_CUSTOMER_MAPPING.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_customer_mapping", $params));
        //print_r($data["list"]);exit();
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["totaldata"] = (isset($data["list"]))?$data["list"]->totaldata:0;
        // $data=(($result));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => (isset($data["list"]))?$data["list"]->totaldata:0
        );
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jointable' => array(array("MASTER_WILAYAH MW", "MW.KD_PROPINSI=MASTER_DEALER.KD_PROPINSI", "LEFT")),
            'field' => 'MASTER_DEALER.*,MW.KD_WILAYAH'
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer["kd_dealer"]);
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        /*
          $config = array(
          'per_page' => $param['limit'],
          'total_rows' => ($data["list"]) ? (isset($data["list"]))?$data["list"]->totaldata:0; : 0
          ); */
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        // $this->output->set_output(json_encode($data['list']));
        $this->template->site('master_service/customerpart/list_custpart', $data);
    }
    public function add_customer_part() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        if ($this->input->get("n")) {
            $param = array(
                'kd_customer' => urldecode(base64_decode($this->input->get("n"))),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'jointable' => array(
                    array("MASTER_PART as MP", "MP.PART_NUMBER=TRANS_PART_CUSTOMER_MAPPING.PART_NUMBER", "LEFT")
                ),
                'field' => 'TRANS_PART_CUSTOMER_MAPPING.PART_NUMBER, MP.PART_DESKRIPSI,TRANS_PART_CUSTOMER_MAPPING.ID,TRANS_PART_CUSTOMER_MAPPING.JENIS',
                'orderby' => 'TRANS_PART_CUSTOMER_MAPPING.ID desc'
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_customer_mapping", $param));
            $params = array(
                'kd_customer' => urldecode(base64_decode($this->input->get("n"))),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'jointable' => array(
                    array("MASTER_CUSTOMER MC", "MC.KD_CUSTOMER=TRANS_PART_CUSTOMER_MAPPING.KD_CUSTOMER", "LEFT"),
                    array("MASTER_GROUPCUSTOMER AS MG", "MG.KD_GROUPCUSTOMER=TRANS_PART_CUSTOMER_MAPPING.KD_CUSTOMER", "LEFT")
                ),
                'field' => "TRANS_PART_CUSTOMER_MAPPING.KD_CUSTOMER,MC.NAMA_CUSTOMER,(SELECT KD_TYPECUSTOMER FROM MASTER_GROUPCUSTOMER_MAPPING WHERE KD_GROUPCUSTOMER=MG.KD_GROUPCUSTOMER )AS KD_TYPECUSTOMER, TRANS_PART_CUSTOMER_MAPPING.JENIS,MG.NAMA_GROUPCUSTOMER,TRANS_PART_CUSTOMER_MAPPING.JENIS"
            );
            $data["listcust"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/part_customer_mapping", $params));
            //print_r($data["listcust"] );exit();
        }
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        //var_dump($data["dealer"]);exit;
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer"));
        $data["groupcustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer"));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $data["typecustomer"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/typecustomer"));
        $paramgroup = array(
            'field' => "KD_TYPECUSTOMER",
            'groupby' => true
        );
        $data["groupcustomer_mapping"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer_mapping", $paramgroup));
        $data["part"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part"));
        $params = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == "Root") {
            $params = array();
        }
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $params));
        $this->template->site('master_service/customerpart/add_p', $data);
    }
    public function simpan_customer_part_mapping() {
        $detail = json_decode($this->input->post("detail"), true);
        $part_number = substr($this->input->post("part_number"), 0, strpos($this->input->post("part_number"), ' '));
        for ($i = 0; $i < count($detail); $i++) {
            $kd_customer = explode(" - ", $this->input->post("kd_customer"));
            $param = array(
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_customer' => ($detail[$i]['jenis'] == 'Group') ? $detail[$i]['kd_groupcustomer'] : $detail[$i]['kd_customer'],
                'kd_groupcustomer' => $detail[$i]['kd_groupcustomer'],
                'jenis' => $detail[$i]['jenis'],
                'part_number' => $detail[$i]['part_number'],
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/part_customer_mapping", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        $datapart = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $datapart->message);
        $this->data_output($hasil, 'post', base_url('part/customer_part'));
    }
    public function delete_customer_part_mapping($id = null) {
        $param = array(
            'id' =>($id)?$id:0,// $this->input->get("id"),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/part_customer_mapping", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('part/customer_part')
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
    public function customer_part_mapping_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/marketing/part_customer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_CUSTOMER;
            $data_message[1][$key] = $message->PART_NUMBER;
        }
        $result['keyword'] = array_merge($data_message[0]);
        $this->output->set_output(json_encode($result));
    }
    /**
     * [customer_autocomple description]
     * @return [type] [description]
     */
    public function customer_autocomplete($kd_dealer = '') {
        $data = [];
        $keywords = $this->input->get('keyword');
        $param = array(
            'keyword' => $keywords
        );
        $data_message = [];
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customer", $param));
        // var_dump($data); exit;
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->KD_CUSTOMER . " - " . $message->NAMA_CUSTOMER;
                }
            } else {
                $data_message['keyword'][] = ""; //$data->message;
            }
        } else {
            $data_message['keyword'][0] = "<i class='fa fa-info'></i> Data tidak di temukan";
        }
        // $result['keyword'] = array_merge($data_message);,
        $this->output->set_output(json_encode($data_message));
    }
    public function get_group() {
        $param = array(
            "kd_typecustomer" => $this->input->get('kd_typecustomer'),
        );
        $data["gc_header"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/groupcustomer_mapping", $param));
        $this->output->set_output(json_encode($data));
    }
    public function mutasipart_list()
    {
        $data=array();

        $param = array(
               'keyword' => $this->input->get('keyword'),
               'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
               'limit' => 15,
               'tipe_trans' =>'PART',
               'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
               'field' => "NO_TRANS, TGL_TRANS",
               'groupby' => true
             );
        if($this->input->get('dari_tanggal')){
            $param["custom"] ="CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".TglToSql($this->input->get("dari_tanggal"))."' AND '".TglToSql($this->input->get("sampai_tanggal"))."'";
        }
        $data["header"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));

        $param_list = array(
               'keyword' => $this->input->get('keyword'),
               'tipe_trans' =>'PART',
               'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
             );
        if($this->input->get('dari_tanggal')){
            $param_list["custom"] ="CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".TglToSql($this->input->get("dari_tanggal"))."' AND '".TglToSql($this->input->get("sampai_tanggal"))."'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param_list));
        $paramd=array("kd_dealer" => $this->session->userdata("kd_dealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",$paramd));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => (isset($data["header"])) ? $data["header"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        
        // $this->output->set_output(json_encode($data["header"]));
        $this->template->site('inventori/mutasi/mutasipart_list', $data);
    }
    public function mutasipart_add($no_trans=null){
        $gendata=$this->parts4gen(true);
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        
        $param['id']   = '60';
        $param['kd_lokasi']   = $this->session->userdata("kd_lokasi");
        if($this->input->get("data_number")){
            $param["part_number"] = $this->input->get("data_number");
        }
        $param['kd_dealer'] = $kd_dealer;
        $param['user_login'] = str_replace("-","",$this->session->userdata("user_id"));
        $param["custom"]      = "JUMLAH_SAK >0";
        $data["part"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
        //var_dump($data["part"]);

        $paramgd=array(
            'custom' => "KD_DEALER = '".$kd_dealer."' AND JENIS_GUDANG = 'PART' ",
            'field' => 'KD_GUDANG, NAMA_GUDANG',
            // 'groupby' => true  
        );
        $data["gudang"] = json_decode($this->curl->simple_get(API_URL."/api/master/gudang",$paramgd));

        if($this->input->get("kd_dealer")){
            $param_dealer['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param_dealer['where_in']  = isDealerAkses();
            $param_dealer['where_in_field'] ='KD_DEALER';
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer", $param_dealer));
        if($no_trans){
            $param=array(
                'no_trans'  => $no_trans
            );
            $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        }



        $this->load->view('inventori/mutasi/mutasipart_add', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));

        // $this->output->set_output(json_encode($data["part"]));

    }
    public function get_partdetail()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        
        $rand=strtoupper($kd_dealer."_".substr(str_replace("-","",$this->session->userdata("user_id")),0,10));


        $param["custom"]      = "TRANS_PARTSTOCK_VIEW.JUMLAH >0 AND TRANS_PARTSTOCK_VIEW.ID IN(40)";
        // $param["custom"]      = "TRANS_PARTSTOCK_VIEW.JUMLAH_SAK >0 AND TRANS_PARTSTOCK_VIEW.ID IN(0,1,2,9)";
        $param["part_number"] = $this->input->get("part_number");
        $param['kd_gudang']   = $this->input->get("kd_gudang");
        $param['kd_rakbin']   = $this->input->get("kd_rakbin");
        $param['kd_dealer']   = $kd_dealer;
        $param['user_login']  = str_replace("-","",$this->session->userdata("user_id"));
        $param['jointable']   = array(array("MASTER_GUDANG GDG","GDG.KD_GUDANG=TRANS_PARTSTOCK_VIEW.KD_GUDANG AND GDG.KD_DEALER = '".$kd_dealer."' AND GDG.ROW_STATUS >= 0 AND GDG.JENIS_GUDANG='Part'","LEFT"));
        $param['field']       = "TRANS_PARTSTOCK_VIEW.KD_GUDANG, TRANS_PARTSTOCK_VIEW.KD_RAKBIN, TRANS_PARTSTOCK_VIEW.JUMLAH, TRANS_PARTSTOCK_VIEW.PART_DESKRIPSI, TRANS_PARTSTOCK_VIEW.HARGA_BELI, TRANS_PARTSTOCK_VIEW.HARGA_JUAL, GDG.NAMA_GUDANG"; 
        $param['groupby']     = true;

        // var_dump($param);exit;
        $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));

        $this->output->set_output(json_encode($data));
    }
    public function get_movingrakbin()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $paramgd=array(
            'custom' => $kd_dealer." AND KD_GUDANG = '".$this->input->get('kd_gudang')."'"
            // 'groupby' => true  
        );
        $data = json_decode($this->curl->simple_get(API_URL."/api/marketing/lokasi_rak_bin",$paramgd));
        $html = '';
        $html .= '<option value="">- Pilih Rakbin -</option>';
        if ($data && (is_array($data->message) || is_object($data->message))):
            foreach ($data->message as $key => $rakbin) :
                $html .= '<option value="' . $rakbin->KD_LOKASI . '" class="' . $rakbin->KD_LOKASI . '"' . ($rakbin->DEFAULTS == 1 ? "selected" : " ") . '>' . $rakbin->KD_LOKASI . '</option>';
            endforeach;
        endif;
        $this->output->set_output(json_encode($html));
    }
    public function mutasipart_simpan()
    {
        $param=array();$data=array();$datax=array();
        $no_trans = ($this->input->post("no_trans"))?$this->input->post("no_trans"): $this->autogenerate_trans("MT");

        $param["kd_dealer"]         = $this->session->userdata("kd_dealer");
        $param["kd_maindealer"]     = $this->session->userdata("kd_maindealer");
        $param["no_trans"]          = $no_trans;
        $param["tgl_trans"]         = ($this->input->post("tgl_mutasi"));
        $param["part_number"]       = $this->input->post("part_number");
        $param["keterangan"]        = $this->input->post("keterangan");
        $param["tipe_trans"]        = 'PART';
        $param["jenis_trans"]       = $this->input->post("jenis_mutasi");
        $param["jumlah"]            = $this->input->post("jumlah");
        $param["kd_gudang_asal"]    = $this->input->post("kd_gudang_asal");
        $param["kd_gudang_tujuan"]  = $this->input->post("kd_gudang_tujuan");
        $param["rakbin_asal"]       = $this->input->post("kd_rakbin");
        $param["rakbin_tujuan"]     = $this->input->post("rakbin_tujuan");
        $param["kd_dealer_tujuan"]  = $this->input->post("kd_dealer_tujuan");
        $param["harga_beli"]        = $this->input->post("harga_beli");
        $param["het"]               = $this->input->post("het");
        $param["created_by"]        = $this->session->userdata("user_id");
        $param["status_mutasi"]     ="0";

        $tipe = 'post';

        $data=($this->curl->simple_post(API_URL . "/api/inventori/inv_mutasi", $param));
        $datax=json_decode($data);
        if($datax){
            if($datax->recordexists){
                $param["lastmodified_by"]=$this->session->userdata("user_id");
                $data=($this->curl->simple_put(API_URL . "/api/inventori/inv_mutasi", $param));
                $tipe = 'put';
            }
        }
        $this->parts4gen(true);

        $this->data_output($data, $tipe, $no_trans);

        // $this->output->set_output($data,'post');
    }
    public function moving_typeahead($tipe = 'PART',$onlypart=null) {
        $param=array(
            'tipe_trans'  => $tipe
        );
        if($onlypart==true){
            $param["field"]='PART_NUMBER';
            $param['groupby']=true;
        }
        $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        if ($data) {
            if (is_array($data["list"]->message)) {
                foreach ($data["list"]->message as $key => $message) {
                    $data_message[0][$key] = $message->PART_NUMBER;
                    if((!$onlypart)){
                        $data_message[1][$key] = $message->NO_TRANS;
                    }
                }
                $result['keyword'] =(!$onlypart)? array_merge($data_message[0], $data_message[1]):$data_message[0];
                $this->output->set_output(json_encode($result));
            }
        }
    }
    public function print_slip()
    {
        $this->auth->validate_authen('part/mutasipart_list');
        $param = array(
            'no_trans' => $this->input->get('no_trans'),
            'tipe_trans' =>'PART',
            'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        $this->load->view('inventori/mutasi/print_slip', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function autogenerate_trans($kd_docno) {
        $no_trans = "";
        $nomortrans = 0;
        $param = array(
            'kd_docno' => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),// substr($this->input->post('tgl_trans'), 6, 4),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = date('m');// substr($this->input->post('tgl_trans'), 3, 2);
        $nomortrans = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomortrans == 0) {
            $no_trans = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomortrans = $nomortrans + 1;
            $no_trans = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomortrans, 5, '0', STR_PAD_LEFT);
        }
        return $no_trans;
    }
    function stockoverview($onlydata=null,$debug=null){
        $this->auth->validate_authen('part/partstock_ovr');
        $html="";
        $param=array(
            'part_number' => $this->input->get('part_number'),
            'kd_dealer'   => ($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
            'field' =>"KD_GUDANG,SUM(STOCK)JUMLAH_TOTAL",
            'groupby'=>TRUE
        );
        $this->auth->set_kd_dealer($param["kd_dealer"]);
        if($onlydata==true){
            if($this->input->get('kd_gudang')){
                $param["custom"]= "KD_GUDANG IN(".$this->lokasiDealer($this->input->get("kd_gudang")).") AND JENIS_MOVE NOT IN('Mutasi')";
               // $param["having"] = "SUM(STOCK)>0";
            }
        }
        //print_r($param);
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/moving_part/true", $param));
        //var_dump($data);
        if($data){
            $totalstoked=0; $totalblok=0; $totalintransit=0;
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    //if($value->JUMLAH_TOTAL){
                        $html .="<tr><td>".$value->KD_GUDANG."<span class='pull-right'><em><smaller>Total</smaller></em></td><td class='text-right'><b><em>".number_format($value->JUMLAH_TOTAL,0)."</em></b></td>";
                        $html .=($this->rakdefault($value->KD_GUDANG,'true')==$value->KD_GUDANG && $this->blockstock($param["part_number"])>0)?"<td class='text-right'><b><em>".number_format($this->blockstock($param["part_number"]))."</em></b></td>":"<td>&nbsp;</td>";
                        $html .="<td>&nbsp;</td></tr>";
                        $html .= $this->stockinrakbin($value->KD_GUDANG,$param["part_number"]);
                        $totalstoked += $value->JUMLAH_TOTAL;
                        $totalintransit = $this->totalintransit($param["part_number"]);
                        $totalblok =$this->blockstock($param["part_number"]);
                   // }
                }
                $html .="<tr><td class='text-right'><b><em>Total Stock</em></b></td><td class='text-right'><b><em>".number_format($totalstoked,0)."</em></b></td><td class='text-right'><b><em>".number_format($totalblok,0)."</em></b></td><td class='text-right'><b><em>".number_format($totalintransit,0)."</em></b></td></tr>";
            }else{
                $html=belumAdaData(4,true);
            }
        }else{
            $html=belumAdaData(4,true);
        }
        //echo json_encode($data);
        if($onlydata==true){
            if($debug==true){
                if($data){
                    if($data->totaldata){
                        echo json_encode($data->message);
                    }else{
                        echo "[]";
                    }
                }
            }else{
                return $data;
            }
        }else{
            $this->output->set_output(json_encode($html));
        }
    }
    function lokasiDealer($kd_lokasi=null){
        $result="";
        $param=array(
            'kd_dealer' =>$this->auth->get_kd_dealer()
        );
        if($kd_lokasi){
            $param["custom"] = "KD_LOKASIDEALER='".$kd_lokasi."'";
        }
        $data = json_decode($this->curl->simple_get(API_URL."/api/master/gudang",$param));
        //var_dump($data);
        if($kd_lokasi){
            if($data){
                $n=0;
                if($data->totaldata>0){
                    foreach ($data->message as $key => $value) {
                        $n++;
                        $result .="'".$value->KD_GUDANG."'";
                        $result .=($n < $data->totaldata )?",":"";
                    }
                }
            }
            return $result;
        }else{
            return  $data;
        }
    }
    function stockinrakbin($kd_gudang,$part_number){
        $html=""; $on_hand=0; $blok=0;$intransit=0;
        $param=array(
            'kd_gudang' => $kd_gudang,
            'part_number' => $part_number,
            'kd_dealer'  => ($this->auth->get_kd_dealer())?$this->auth->get_kd_dealer():$this->session->userdata("kd_dealer"),
            'field'     =>'KD_GUDANG,KD_RAKBIN,(JUMLAH_TOTAL) AS JUMLAH_TOTAL',
            'orderby'   =>'KD_RAKBIN'/*,
            'groupby'   => TRUE*/
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/moving_part/", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $on_hand =($value->JUMLAH_TOTAL>0)?$value->JUMLAH_TOTAL:0;
                    //$blok    =($value->JUMLAH_TOTAL>0 && $value->JENIS_MOVE=='Blocked')?$value->JUMLAH:"";
                    $html .="<tr><td class='text-right'>".$value->KD_RAKBIN."</td>
                            <td class='text-right'>".number_format($on_hand,0)."</td>";
                        $html .=($this->blockstock($param["part_number"])>0)?"<td class='text-right'>".number_format($this->blockstock($param["part_number"]),0)."</td>":"<td>&nbsp;</td>";
                        $html .="<td>&nbsp;</td></tr>";
                    //}
                }
            }
        }
        //print_r($data);
        return $html;
    }
    function blockstock($part_number=null,$debug=null){
        $stockbloked=0;
        // mutasi antar dealer
        $param=array(
            'part_number' => $part_number,
            'kd_dealer'   => $this->auth->get_kd_dealer(),
            'tipe_trans'  =>'PART',
            'jenis_trans' =>'Antar Dealer',
            'custom'      => "(APPROVAL_STATUS IS NULL OR APPROVAL_STATUS='0')",
            'field'       => "PART_NUMBER,SUM(JUMLAH) AS JUMLAH",
            'groupby'     => TRUE
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $stockbloked +=$value->JUMLAH;
                }
            }
        }
        //so dan pkb yang belum di picking
        $param=array(
            'kd_dealer' => $this->auth->get_kd_dealer(),
            'part_number' => $part_number,
            'custom'    =>"JENIS_MOVE ='Blocked'",
            'field'       => "PART_NUMBER,SUM(JUMLAH) AS JUMLAH",
            'groupby'     => TRUE
        );
        $datax=json_decode($this->curl->simple_get(API_URL . "/api/inventori/moving_part/true", $param));
        if($datax){
            if($datax->totaldata>0){
                foreach ($datax->message as $key => $value) {
                    $stockbloked +=$value->JUMLAH;
                }
            }
        }
        if($debug==true){
            echo json_encode($data);
        }else{
            return $stockbloked;
        }
    }
    function totalintransit($part_number,$debug=null){
         $stockbloked=0;
        $param=array(
            'part_number'   => $part_number,
            'custom'        => "KD_DEALER_TUJUAN='".$this->auth->get_kd_dealer()."' AND APPROVAL_STATUS >0",
            'tipe_trans'  => 'PART',
            'jenis_trans' => 'Antar Dealer',
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $stockbloked +=$value->JUMLAH;
                }
            }
        }
        //var_dump($data);
        if($debug==true){
            echo json_encode($data);
        }else{
            return $stockbloked;
        }
    }
    function rakdefault($kd_gudang,$gdg=null){
        $kd_rakbin="";
        $param=array(
            'kd_dealer' => $this->auth->get_kd_dealer("kd_dealer"),
            'kd_gudang' => $kd_gudang,
            'custom'    => "RAK_DEFAULT = 1"
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/marketing/lokasi_rak_bin", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $kd_rakbin =($gdg==true)?$value->KD_GUDANG: $value->KD_LOKASI;
                }
            }
        }
        return $kd_rakbin;
    }
    function partstock_mvt($debug=null){
        $data=array();$param=array();
        $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
        $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
        $param = array(
               'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
               'limit' => 15,
               'jointable' => array(array("MASTER_PART P","P.PART_NUMBER=TRANS_PART_MOVEMENT.PART_NUMBER","LEFT"))
           );
        $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        if($this->input->get("kd_dealer")){
            $param["kd_dealer"] = $this->input->get("kd_dealer");
        }
        $param["custom"] ="CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".TglToSql($dari_tanggal)."' AND '".TglToSql($sampai_tanggal)."'";
        if($this->input->get("keyword")){
            unset($param["custom"]);
            $param["keyword"] = $this->input->get("keyword");
        }
        //distinct part_number
        $param["field"] = "TRANS_PART_MOVEMENT.PART_NUMBER,P.PART_DESKRIPSI";
        $param["groupby"]= TRUE;
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/moving_part/true", $param));
        if($data["list"]){
            if($data["list"]->totaldata>0){
                $detail=array();
                foreach ($data["list"]->message as $key => $value) {
                    $params=array(
                        'kd_dealer' => $param["kd_dealer"],
                        'part_number' => $value->PART_NUMBER
                    );
                    $detail[$value->PART_NUMBER]=json_decode($this->curl->simple_get(API_URL . "/api/inventori/moving_part/true", $params));
                }
                $data["listd"] = $detail;
            }
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        if($debug==true){
            //print_r($param);
            print_r($data["listd"]);
        }else{
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => (isset($data["list"])) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('inventori/mutasi/mutasipart_history', $data);
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
                if($hasil){
                    if ($hasil->status === TRUE) {
                        $result = array(
                            'status' => true,
                            'message' => "Data berhasil disimpan",
                            'location' => $location
                        );
                    } else {
                        $result = $hasil;
                    }
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