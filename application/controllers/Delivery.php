<?php
<<<<<<< HEAD
defined('BASEPATH') OR exit('No direct script access allowed');
class Delivery extends CI_Controller {
    var $API;
    /**
     * [__construct description]
     */
=======

defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends CI_Controller {

    var $API = "";

>>>>>>> 2e732809934c8b053dc12d8aba5c9a52034ed8ab
    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->library('template');
        $this->load->helper('form');
        $this->load->helper('url');
<<<<<<< HEAD
        $this->load->helper("zetro");
    }
    public function do_unit(){ 
        $data = array();
        $param = array();	
        if ($this->input->get('kd_dealer')) {
                    $param["kd_dealer"] = $this->input->get('kd_dealer');
                }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["poheader"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po"));
        $this->template->site('inventori/do_unit',$data);
    }
    public function no_po(){
            $param = array(
                'kd_dealer' =>$this->input->get('kd_dealer')
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po",$param));
            if($data){
                if(is_array($data->message)){
                    foreach ($data->message as $key => $value) {
                        echo   "<option value='".$value->NO_PO."'>".$value->NO_PO."</option>";
                    }
                }
            }
    }
    public function lokasi_asal(){
            $data = array();
            $param = array(
                'kd_dealer' => $this->input->post("kd_dealer"),
                'field' => "MASTER_DEALER_V.ALAMAT",
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/1/0",$param));
            // $this->output->set_output(json_encode($data));
            echo json_encode($data->message);
    }
}
=======
        $this->load->helper('zetro');
        $this->load->helper('file');
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    function index() {
        
    }

    /**
     * [DO_list description]
     */

    public function list_do() {
    $param = array(
        'kd_dealer' => $this->session->userdata("kd_dealer"),
        'keyword' => $this->input->get('keyword'),
        // 'row_status' => 0, /*
        //   'kd_dealer' => $this->input->get('kd_dealer'), */
        // 'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
        'limit' => 15,
        // 'jointable' => array(
        //     array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")
        // ),
        // 'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER",
        // 'orderby' => "TRANS_PO2MD.TGL_PO DESC",
         //"TAHUN_KIRIM,BULAN_KIRIM DESC,KD_JENISPO,TRANS_PO2MD.ID desc" ,
            /* 'custom'    =>"MASTER_DEALER.NAMA_DEALER IS NOT NULL" */
    );

    $param["bulan"] = ($this->input->get("bln")) ? $this->input->get("bln") : date("m"); 
    $param["tahun"] = ($this->input->get("thn")) ? $this->input->get("thn") : date("Y");
    $data = array();
    $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/delivery/do", $param));
    $string = link_pagination();
    $config = array(
        'per_page' => $param['limit'],
        'base_url' => $string[0],
        'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
    );

    // $param = array(
    //     'field' => "TAHUN_KIRIM",
    //     'groupby' => TRUE,
    //     'orderby' => "TAHUN_KIRIM"
    // );
    // $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));

    $pagination = $this->template->pagination($config);

    $this->pagination->initialize($pagination);
    $data['pagination'] = $this->pagination->create_links();
    // var_dump($data["list"]);
    $this->template->site('delivery/list_do', $data);
}

    public function do_typeahead() {
        $param = array(
            'field' => 'NO_DOLOG',
            'row_status' => 0,
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/delivery/do", $param));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_DOLOG;
        }

        $result['keyword'] = ($data_message[0]);

        $this->output->set_output(json_encode($result));
    }

}
>>>>>>> 2e732809934c8b053dc12d8aba5c9a52034ed8ab
