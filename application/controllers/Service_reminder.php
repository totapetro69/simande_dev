<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service_reminder extends CI_Controller {

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

    public function index() {

        $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_CUST_REMINDER.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_CUST_REMINDER.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_CUST_REMINDER.KD_CUSTOMER", "LEFT")
            ),
            'custom' => $kd_dealer,
            'field' => "U.*, TRANS_CUST_REMINDER.*"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/cust_reminder", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('service_reminder/index', $data);
    }


    public function add_service_reminder(){

        $data = array();
        $this->auth->validate_authen('reminder_booking/service_reminder');


        if($this->input->get('no_trans')){
            $param = array(
                'no_trans' => $this->input->get('no_trans')
            );

            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/cust_reminder", $param));
        }


        $this->load->view('service_reminder/add_service_reminder', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }


    public function service_reminder_simpan()
    {
        $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_fu('RS'));

        $param = array(
            'id' => $this->input->post("id"),
            'tgl_trans' => $this->input->post("tgl_trans"),
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'no_trans' => $ntrans,
            'kd_customer' => $this->input->post("kd_customer"),
            'nama_customer' => $this->input->post("nama_customer"),
            'waktu_jdwreminder' => $this->input->post("waktu_jdwreminder"),
            'waktu_reminderkpb' => $this->input->post("waktu_reminderkpb"),
            'jenis_kpb' => $this->input->post("jenis_kpb"),
            'jenis_reminder' => $this->input->post("jenis_reminder"),
            'status_reminder' => $this->input->post("status_reminder"),
            'no_hp' => $this->input->post("no_hp"),
            'kd_typemotor' =>$this->input->post("kd_typemotor"),
            'no_polisi' =>$this->input->post("no_polisi"),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        // var_dump($param);exit;
        $hasil =  $this->curl->simple_post(API_URL . "/api/service/cust_reminder", $param, array(CURLOPT_BUFFERSIZE => 100));
        //data

        $method = "post";

        if(json_decode($hasil)->recordexists==TRUE){

            $hasil= $this->curl->simple_put(API_URL."/api/service/cust_reminder",$param, array(CURLOPT_BUFFERSIZE => 10));  

        // var_dump($hasil);exit;
            $method = "put";
        }


        $this->data_output($hasil, $method); 
        //test


    }

    public function service_reminder_hapus($id)
    {
        $param = array(
            'id' => $id,
            'lastmodified_by'=> $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/service/cust_reminder",$param));

        $this->data_output($data, 'delete', base_url('reminder_booking/service_reminder'));

    }
    
    public function setupreminder() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'SETUP_SERVICE_REMINDER.*',
            'orderby' => 'SETUP_SERVICE_REMINDER.ID DESC'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service_reminder/setupreminder", $param));
        
        //var_dump($data["list"]);
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('service_reminder/setup_reminder/view', $data);
    }

    public function add_setupreminder() {
        $this->auth->validate_authen('service_reminder/setupreminder');
        
        $this->load->view('service_reminder/setup_reminder/add');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_setupreminder_simpan() {
        $this->form_validation->set_rules('type_srv_next', 'Type KPB', 'required|trim');
        $this->form_validation->set_rules('tgl_srv_reminder', 'Hari -x reminder', 'required|trim');
        $this->form_validation->set_rules('tgl_srv_next', 'Hari Jatuh tempo Service', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {          

            $param = array(
                'type_srv_next' => $this->input->post("type_srv_next"),
                'tgl_srv_reminder' => $this->input->post("tgl_srv_reminder"),
                'tgl_srv_next' => $this->input->post("tgl_srv_next"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/service_reminder/setupreminder", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('service_reminder/setupreminder'));
        }
    }

    public function edit_setupreminder($id, $row_status) {
        $this->auth->validate_authen('service_reminder/setupreminder');
        $param = array(
            "custom" => "SETUP_SERVICE_REMINDER.ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service_reminder/setupreminder", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('service_reminder/setup_reminder/edit', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_setupreminder($id) {
        $this->form_validation->set_rules('type_srv_next', 'Type KPB', 'required|trim');
        $this->form_validation->set_rules('tgl_srv_reminder', 'Hari -x reminder', 'required|trim');
        $this->form_validation->set_rules('tgl_srv_next', 'Hari Jatuh tempo Service', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            
            $param = array(
                'id' => $this->input->post("id"),
                'type_srv_next' => $this->input->post("type_srv_next"),
                'tgl_srv_reminder' => $this->input->post("tgl_srv_reminder"),
                'tgl_srv_next' => $this->input->post("tgl_srv_next"),
                'row_status' => $this->input->post("row_status"),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $hasil = $this->curl->simple_put(API_URL . "/api/service_reminder/setupreminder", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_setupreminder($id) {
        $data = array();        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/service_reminder/setupreminder", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('service_reminder/setupreminder')
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

    public function setupreminder_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/service_reminder/setupreminder"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->TYPE_SRV_NEXT;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    function data_output($hasil = NULL, $method = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan"
                    );
                } else {
                    $result = $hasil;
                }
                $this->output->set_output(json_encode($result));
                break;

            default:
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Update berhasil"
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal di simpan',
                        'status' => false
                    );

                    $this->output->set_output(json_encode($result));
                }
                break;
        }
    }
    
}
