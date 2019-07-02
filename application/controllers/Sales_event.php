<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_event extends CI_Controller {

    var $API;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('dompdf_gen');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper("zetro_helper");
        $this->load->library('user_agent');
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

    public function master_event() {
        $data = array();
        
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => "MASTER_EVENT.*"
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/master_event", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master/master_event', $data);
    }

    public function edit_master_event($id) {
        $this->auth->validate_authen('sales_event/master_event');
        $kd_dealer = $this->input->get('kd_dealer') ? "MASTER_EVENT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "MASTER_EVENT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/master_event", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/master/master_jenis_event"));
        $data["event"] = json_decode($this->curl->simple_get(API_URL . "/api/master/master_event"));
        $param = array(
            'row_status' => 0
        );
        $this->load->view('form_edit/edit_master_event', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function master_event_update($id) {
        $this->form_validation->set_rules('nama_event', 'nama_event', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'id_event' => $this->input->post("id_event"),
                'nama_event' => $this->input->post("nama_event"),
                'jenis_event' => $this->input->post("jenis_event"),
                'unit_target' => $this->input->post("unit_target"),
                'revenue_target' => $this->input->post("revenue_target"),
                'budget_event' => $this->input->post("budget_event"),
                'loc_event' => $this->input->post("loc_event"),
                'desc_event' => $this->input->post("desc_event"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/master_event", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_master_event($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/master_event", $param));
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('sales_event/master_event')
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

    public function approve_master_event($id) {
        $param = array(
            'id' => $id,
            'status' =>1,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/master/master_event_app", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $id);
        $this->data_output($hasil, 'put');
    }

    public function reject_master_event($id) {
        $param = array(
            'id' => $id,
            'status' =>2,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/master/master_event_rej", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $id);
        $this->data_output($hasil, 'put');
    }

    public function master_event_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master/master_event"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_EVENT;
            $data_message[1][$key] = $message->JENIS_EVENT;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }


    //re
    public function master_jenis_event() {
        $data = array();
        
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => "MASTER_JENIS_EVENT.*"
        );
        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master/master_jenis_event', $data);
    }

    public function add_master_jenis_event() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event"));
        //$data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $this->load->view('form_tambah/add_master_jenis_event', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_master_jenis_event_simpan() {
        $this->form_validation->set_rules('nama_jenis_event', 'Nama Jenis Event', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_jenis_event' => strtoupper($this->input->post("kd_jenis_event")),
                'nama_jenis_event' => $this->input->post("nama_jenis_event"),
                'need_approval' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sales_manage/master_jenis_event", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('sales_event/master_jenis_event'));
        }
    }

    public function edit_jenis_event($id) {
        $this->auth->validate_authen('sales_event/master_jenis_event');
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event", $param));
        $data["maindealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        $param = array(
            'row_status' => 0
        );
        $this->load->view('form_edit/edit_jenis_event', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function jenis_event_update($id) {
        $this->form_validation->set_rules('nama_jenis_event', 'nama_jenis_event', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_jenis_event' => strtoupper($this->input->post("kd_jenis_event")),
                'nama_jenis_event' => $this->input->post("nama_jenis_event"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/master_jenis_event", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    /*public function list_event() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_EVENT_CREATE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_EVENT_CREATE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("start_date") or $this->input->get("end_date")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("start_date")) . "' AND '" . tglToSql($this->input->get("end_date")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'jointable' => array(
                array("MASTER_JENIS_EVENT MJE", "MJE.KD_JENIS_EVENT=TRANS_EVENT_CREATE.KD_JENIS_EVENT", "LEFT"),
                array("MASTER_DESA MD", "MD.KD_DESA=TRANS_EVENT_CREATE.KD_DESA", "LEFT"),
                array("MASTER_KECAMATAN MKC", "MKC.KD_KECAMATAN=TRANS_EVENT_CREATE.KD_KECAMATAN", "LEFT"),
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=TRANS_EVENT_CREATE.KD_KABUPATEN", "LEFT"),
                array("MASTER_PROPINSI MP", "MP.KD_PROPINSI=TRANS_EVENT_CREATE.KD_PROPINSI", "LEFT"),
            ),
            'limit' => 15,
            'field' => 'TRANS_EVENT_CREATE.*, MJE.NAMA_JENIS_EVENT, MD.NAMA_DESA, MKC.NAMA_KECAMATAN, MK.NAMA_KABUPATEN, MP.NAMA_PROPINSI',
            'orderby' => 'TRANS_EVENT_CREATE.ID DESC',            
            "custom" => $tgl,

        );
         if(!$this->input->get("p")){
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 15;
        }

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        if($this->input->get("p")){
            $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", array('kd_dealer' => $kd_dealer)));

            $html = $this->load->view('sales/manage_sales_event/print', $data, true);
            $filename = 'report_'.time();
            $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 595.28, 841.89), 'landscape');
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
            $this->template->site('sales/manage_sales_event/view_new', $data);
        }
    }*/

    public function add_event_new($id) {
        $this->auth->validate_authen('sales_event/list_event');
    
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_EVENT_CREATE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_EVENT_CREATE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'jointable' => array(
                array("MASTER_JENIS_EVENT MJE", "MJE.KD_JENIS_EVENT=TRANS_EVENT_CREATE.KD_JENIS_EVENT", "LEFT"),
                array("MASTER_DEALER MDE", "MDE.KD_DEALER=TRANS_EVENT_CREATE.KD_DEALER", "LEFT"),
                array("MASTER_DESA MD", "MD.KD_DESA=TRANS_EVENT_CREATE.KD_DESA", "LEFT"),
                array("MASTER_KECAMATAN MKC", "MKC.KD_KECAMATAN=TRANS_EVENT_CREATE.KD_KECAMATAN", "LEFT"),
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=TRANS_EVENT_CREATE.KD_KABUPATEN", "LEFT"),
                array("MASTER_PROPINSI MP", "MP.KD_PROPINSI=TRANS_EVENT_CREATE.KD_PROPINSI", "LEFT")
            ),
            'limit' => 15,
            'field' => 'TRANS_EVENT_CREATE.*, MDE.NAMA_DEALER, MJE.NAMA_JENIS_EVENT, MD.NAMA_DESA, MKC.NAMA_KECAMATAN, MK.NAMA_KABUPATEN, MP.NAMA_PROPINSI',
            'custom' => "TRANS_EVENT_CREATE.ID = '" . $id . "'"
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));

        $this->template->site('sales/manage_sales_event/create_event_new', $data);
    }

    public function list_event() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_EVENT_CREATE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_EVENT_CREATE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("start_date") or $this->input->get("end_date")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("start_date")) . "' AND '" . tglToSql($this->input->get("end_date")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'jointable' => array(
                array("MASTER_JENIS_EVENT MJE", "MJE.KD_JENIS_EVENT=TRANS_EVENT_CREATE.KD_JENIS_EVENT", "LEFT"),
                array("MASTER_DESA MD", "MD.KD_DESA=TRANS_EVENT_CREATE.KD_DESA", "LEFT"),
                array("MASTER_KECAMATAN MKC", "MKC.KD_KECAMATAN=TRANS_EVENT_CREATE.KD_KECAMATAN", "LEFT"),
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=TRANS_EVENT_CREATE.KD_KABUPATEN", "LEFT"),
                array("MASTER_PROPINSI MP", "MP.KD_PROPINSI=TRANS_EVENT_CREATE.KD_PROPINSI", "LEFT")
            ),
            'limit' => 15,
            'field' => 'TRANS_EVENT_CREATE.*, MJE.NAMA_JENIS_EVENT, MD.NAMA_DESA, MKC.NAMA_KECAMATAN, MK.NAMA_KABUPATEN, MP.NAMA_PROPINSI',
            'orderby' => 'TRANS_EVENT_CREATE.ID DESC',            
            "custom" => $tgl,

        );
         if(!$this->input->get("p")){
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 15;
        }

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        if($this->input->get("p")){
            $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", array('kd_dealer' => $kd_dealer)));

            $html = $this->load->view('sales/manage_sales_event/print', $data, true);
            $filename = 'report_'.time();
            $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 595.28, 841.89), 'landscape');
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
            $this->template->site('sales/manage_sales_event/view', $data);
        }
    }

    /*public function approve_event($kd_event) {
        $param = array(
            'kd_event' => $kd_event,
            'status_approve' =>1,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $kd_event);
        $this->data_output($hasil, 'put');
    }*/

    //ERROR
    /*public function approval_event($id) {
        $this->auth->validate_authen('sales_event/list_event');

        //var_dump(date('d/m/Y'));exit;
    
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_EVENT_CREATE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_EVENT_CREATE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));

         //var_dump($data);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $param = array(
            'row_status' => 0
        );
        $this->load->view('sales/manage_sales_event/approve', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }*/

    public function approval_event($id) {
        $this->auth->validate_authen('sales_event/list_event');

        //var_dump(date('d/m/Y'));exit;
    
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_EVENT_CREATE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_EVENT_CREATE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'jointable' => array(
                array("MASTER_JENIS_EVENT MJE", "MJE.KD_JENIS_EVENT=TRANS_EVENT_CREATE.KD_JENIS_EVENT", "LEFT"),
                array("MASTER_DEALER MDE", "MDE.KD_DEALER=TRANS_EVENT_CREATE.KD_DEALER", "LEFT"),
                array("MASTER_DESA MD", "MD.KD_DESA=TRANS_EVENT_CREATE.KD_DESA", "LEFT"),
                array("MASTER_KECAMATAN MKC", "MKC.KD_KECAMATAN=TRANS_EVENT_CREATE.KD_KECAMATAN", "LEFT"),
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=TRANS_EVENT_CREATE.KD_KABUPATEN", "LEFT"),
                array("MASTER_PROPINSI MP", "MP.KD_PROPINSI=TRANS_EVENT_CREATE.KD_PROPINSI", "LEFT")/*,
                array("TRANS_EVENT_APPROVAL MYP", "MYP.KD_EVENT=TRANS_EVENT_CREATE.KD_EVENT", "LEFT")*/
            ),
            'limit' => 15,
            'field' => 'TRANS_EVENT_CREATE.*, MDE.NAMA_DEALER, MJE.NAMA_JENIS_EVENT, MD.NAMA_DESA, MKC.NAMA_KECAMATAN, MK.NAMA_KABUPATEN, MP.NAMA_PROPINSI',
            'custom' => "TRANS_EVENT_CREATE.ID = '" . $id . "'"
        );

        

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));

        $param_x = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'custom' => "KD_EVENT = '" . $data["list"]->message[0]->KD_EVENT . "'"
        );
        //var_dump($data["list"]->message[0]->KD_EVENT);exit;
        $data["people"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_people", $param_x));
        $data["budget"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_budget", $param_x));
        $data["unit"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_unit2display", $param_x));
        
        $this->template->site('sales/manage_sales_event/approve_new', $data);
    }

    public function approval_event_edit($id) {
        $this->auth->validate_authen('sales_event/list_event');

        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));

         //var_dump($data);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $param = array(
            'row_status' => 0
        );
        $this->load->view('sales/manage_sales_event/approve_edit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function approval_event_simpan($id) {
        //$this->form_validation->set_rules('kd_event', 'kd_event', 'required|trim');
        $this->form_validation->set_rules('status_event', 'Status', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'approval_md' => $this->input->post("status_event"),
                'approval_date' => date('d/m/Y'),
                'lastmodified_by' => $this->session->userdata("user_id")
            );

            //var_dump($param);exit;

            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_app", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
            redirect(base_url('sales_event/approval_event/'.$this->input->post("id")));
        }
    }

    public function detail_budget($kd_event) {
        $this->auth->validate_authen('sales_event/list_event');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'jointable' => array(
                array("TRANS_EVENT_CREATE TEC", "TEC.KD_EVENT=TRANS_EVENT_BUDGET.KD_EVENT", "LEFT"),
            ),
            'limit' => 15,
            'field' => "TRANS_EVENT_BUDGET.*,TEC.NAMA_EVENT,TEC.APPROVAL_MD",
            'orderby' => 'TRANS_EVENT_BUDGET.ID DESC',
            "custom" => "TRANS_EVENT_BUDGET.KD_EVENT='" . $kd_event . "'"
        );
        $param_cek = array(
            "custom" => "TRANS_EVENT_CREATE.KD_EVENT='" . $kd_event . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_budget", $param));
        //var_dump($param);exit;     
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param_cek));  
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('sales/manage_sales_event/view_budget', $data);
    }

    public function detail_people($kd_event) {
        $this->auth->validate_authen('sales_event/list_event');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'jointable' => array(
                array("TRANS_EVENT_CREATE TEC", "TEC.KD_EVENT=TRANS_EVENT_PEOPLE.KD_EVENT", "LEFT"),
            ),
            'limit' => 15,
            'field' => "TRANS_EVENT_PEOPLE.*,TEC.KD_EVENT,TEC.NAMA_EVENT",
            'orderby' => 'TRANS_EVENT_PEOPLE.ID DESC',
            "custom" => "TRANS_EVENT_PEOPLE.KD_EVENT='" . $kd_event . "'"
        );
        $param_cek = array(
            "custom" => "TRANS_EVENT_CREATE.KD_EVENT='" . $kd_event . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_people", $param));
        //var_dump($param);exit;     
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param_cek));  
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('sales/manage_sales_event/view_people', $data);
    }

    public function detail_display($kd_event) {
        $this->auth->validate_authen('sales_event/list_event');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'jointable' => array(
                array("TRANS_EVENT_CREATE TEC", "TEC.KD_EVENT=TRANS_EVENT_UNT2DISPLAY.KD_EVENT", "LEFT"),
                //array("TRANS_STOCKMOTOR TS", "TS.KD_ITEM=TRANS_EVENT_UNT2DISPLAY.KD_ITEM", "LEFT"),
            ),
            'limit' => 15,
            'field' => "TRANS_EVENT_UNT2DISPLAY.*,TEC.KD_EVENT,TEC.NAMA_EVENT",
            'orderby' => 'TRANS_EVENT_UNT2DISPLAY.ID DESC',
            "custom" => "TRANS_EVENT_UNT2DISPLAY.KD_EVENT='" . $kd_event . "'"
        );
        $param_cek = array(
            "custom" => "TRANS_EVENT_CREATE.KD_EVENT='" . $kd_event . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_unit2display", $param));
        //var_dump($param);exit;   
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param_cek));  
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('sales/manage_sales_event/view_display', $data);
    }

    public function create_event() {
        $this->auth->validate_authen('sales_event/list_event');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $this->load->view('sales/manage_sales_event/create_event', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function getIdevent() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status'),
            "custom" => "kd_dealer='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $number = str_pad(($data->totaldata) + 1, 4, '0', STR_PAD_LEFT);
        return "EV" . str_pad($this->session->userdata("kd_dealer"), 4, 0, STR_PAD_LEFT) .  '-' . date('Ym') .'-'. $number;
    }

    public function create_event_simpan() {
        $this->form_validation->set_rules('nama_event', 'Nama Event', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {

            /*if ($this->input->post("assign_event") != null) {
                $assign_event = implode(", ", $this->input->post("assign_event"));
            } else {
                $assign_event = "";
            }*/

            $param = array(
                'kd_event' => $this->getIdevent(),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_jenis_event' => $this->input->post("kd_jenis_event"),
                'inisiasi_event' => $this->input->post("inisiasi_event"),
                'nama_event' => $this->input->post("nama_event"),
                'tgl_trans' => $this->input->post("tgl_trans"),
                'keterangan_event' => $this->input->post("keterangan_event"),
                //'assign_event' => $assign_event,
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'target_unit' => $this->input->post("target_unit"),
                'target_revenue' => $this->input->post("target_revenue"),
                'alamat_event' => $this->input->post("alamat_event"),
                'kd_desa' => $this->input->post("kd_desa"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'approval_md' => 0,
                'status_event' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sales_manage/event_create", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('sales_event/list_event'));
        }
    }

    public function edit_event($id) {
        $this->auth->validate_authen('sales_event/list_event');
    
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_EVENT_CREATE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_EVENT_CREATE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));

         //var_dump($data);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/master_jenis_event"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $param = array(
            'row_status' => 0
        );
        $this->load->view('sales/manage_sales_event/edit_event_new', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function event_update($id) {
        $this->form_validation->set_rules('nama_event', 'nama_event', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("assign_event") != null) {
                $assign_event = implode(", ", $this->input->post("assign_event"));
            } else {
                $assign_event = "";
            }

            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->session->userdata("kd_maindealer"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_jenis_event' => $this->input->post("kd_jenis_event"),
                'inisiasi_event' => $this->input->post("inisiasi_event"),
                'kd_event' => $this->input->post("kd_event"),
                'tgl_trans' => $this->input->post("tgl_trans"),
                'nama_event' => $this->input->post("nama_event"),
                'keterangan_event' => $this->input->post("keterangan_event"),
                'assign_event' => $assign_event,
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'target_unit' => $this->input->post("target_unit"),
                'target_revenue' => $this->input->post("target_revenue"),
                //'status_event' => 0,
                'approval_md' => 0,
                'alamat_event' => $this->input->post("alamat_event"),
                'kd_desa' => $this->input->post("kd_desa"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_create", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit;
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function add_budget($id) {
        $this->auth->validate_authen('sales_event/list_event');
        $data = array();
        $param = array(
            "custom" => "TRANS_EVENT_CREATE.ID='" . $id . "'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $this->load->view('sales/manage_sales_event/add_budget', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_people($id) {
        $this->auth->validate_authen('sales_event/list_event');
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SALES_EVENT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SALES_EVENT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            "custom" => "TRANS_EVENT_CREATE.ID='" . $id . "'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom' => " STATUS_SALES ='A' AND LEN(TRIM(KD_SALES))>0"
        );
        $data["salesman"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $this->load->view('sales/manage_sales_event/add_people', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function get_sales(){
        $param = array(
            'kd_sales' => $this->input->get('kd_sales'),
            'sales_status' => "A",
            'kd_dealer'=>$this->session->userdata("kd_dealer")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));

        $this->output->set_output(json_encode($data));
    }

    public function add_unit($id) {
        $this->auth->validate_authen('sales_event/list_event');
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SALES_EVENT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SALES_EVENT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            "custom" => "TRANS_EVENT_CREATE.ID='" . $id . "'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["stockmotor"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $paramcustomer));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_create", $param));
        $this->load->view('sales/manage_sales_event/add_display', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function get_unit(){
        $param = array(
            'kd_item' => $this->input->get('kd_item'),
            'kd_dealer'=>$this->session->userdata("kd_dealer")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $param));

        $this->output->set_output(json_encode($data));
    }

    public function add_budget_simpan() {
        $this->form_validation->set_rules('kd_budget', 'kode budget', 'required|trim');
        $this->form_validation->set_rules('nama_budget', 'nama budget', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_event' => $this->input->post("kd_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_budget' => strtoupper($this->input->post("kd_budget")),
                'nama_budget' => $this->input->post("nama_budget"),
                'jumlah_budget' => $this->input->post("jumlah_budget"),
                'keterangan_budget' => $this->input->post("keterangan_budget"),
                'aktual_budget' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/sales_manage/event_budget", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('sales_event/detail_budget/' . $this->input->post("kd_event")));
                }
    }

    public function add_people_simpan() {
        $this->form_validation->set_rules('kd_sales', 'kd_sales', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_event' => $this->input->post("kd_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_sales' => $this->input->post("kd_sales"),
                'nama_sales' => $this->input->post("nama_sales"),
                'jabatan_sales' => $this->input->post("jabatan_sales"),
                'kehadiran_sales' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/sales_manage/event_people", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('sales_event/detail_people/' . $this->input->post("kd_event")));
                }
    }

    public function add_unit_simpan() {
        $this->form_validation->set_rules('kd_item', 'kd_item', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_event' => $this->input->post("kd_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_item' => $this->input->post("kd_item"),
                'nama_item' => $this->input->post("nama_item"),
                'keterangan' =>$this->input->post("keterangan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            //var_dump($param);exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/sales_manage/event_unit2display", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('sales_event/detail_display/' . $this->input->post("kd_event")));
                }
    }

    public function edit_budget($id, $row_status) {
        $param = array(
            
            'field' => "TRANS_EVENT_BUDGET.*",
            "custom" => "TRANS_EVENT_BUDGET.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_budget", $param));
        $this->load->view('sales/manage_sales_event/edit_budget', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function edit_people($id, $row_status) {
        //$data = array();
        //$kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SALES_EVENT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SALES_EVENT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'jointable' => array(
            ),
            'field' => "TRANS_EVENT_PEOPLE.*",
            "custom" => "TRANS_EVENT_PEOPLE.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramcustomer["sales_status"] = "A";
        $paramcustomer["custom"] = "LEN(TRIM(KD_SALES))>0";
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["salesman"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_people", $param));
        $this->load->view('sales/manage_sales_event/edit_people', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function edit_unit($id, $row_status) {
        $param = array(
            
            'field' => "TRANS_EVENT_UNT2DISPLAY.*",
            "custom" => "TRANS_EVENT_UNT2DISPLAY.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_unit2display", $param));
        $this->load->view('sales/manage_sales_event/edit_unit', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_budget($id) {
        $this->form_validation->set_rules('kd_budget', 'kode budget', 'required|trim');
        $this->form_validation->set_rules('nama_budget', 'nama budget', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_event' => $this->input->post("kd_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_budget' => strtoupper($this->input->post("kd_budget")),
                'nama_budget' => $this->input->post("nama_budget"),
                'jumlah_budget' => $this->input->post("jumlah_budget"),
                'keterangan_budget' => $this->input->post("keterangan_budget"),
                'aktual_budget' => 0,
                //'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_budget", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function update_people($id) {
        //$this->form_validation->set_rules('kd_typemotor', 'Kode Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('kd_sales', 'kd_sales', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_event' => $this->input->post("kd_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_sales' => strtoupper($this->input->post("kd_sales")),
                'nama_sales' => $this->input->post("nama_sales"),
                'jabatan_sales' => $this->input->post("jabatan_sales"),
                'kehadiran_sales' => 0,
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_people", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function update_unit($id) {
        $this->form_validation->set_rules('kd_item', 'kd_item', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_event' => $this->input->post("kd_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_item' => strtoupper($this->input->post("kd_item")),
                'nama_item' => $this->input->post("nama_item"),
                'keterangan' =>$this->input->post("keterangan"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_unit2display", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_event($kd_event) {
        $param = array(
            'kd_event' => $kd_event,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sales_manage/event_create", $param));
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('sales_event/list_event')
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

    public function delete_budget($id,$kd_event) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sales_manage/event_budget", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('sales_event/detail_budget/' . $kd_event)
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

    public function delete_people($id,$kd_event) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sales_manage/event_people", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('sales_event/detail_people/' . $kd_event)
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

    public function delete_unit($id,$kd_event) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sales_manage/event_unit2display", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('sales_event/detail_display/' . $kd_event)
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

    public function salesevent_print() {
        $this->load->library('dompdf_gen');
        $data = array();
        //$this->auth->validate_authen('sales_event/list_event');
        $param = array(

            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'tanggal' => $this->input->get('tgl_event'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
           'field' => "TRANS_SALES_EVENT_VIEW.*",
            'custom' => "APPROVAL_MD = 1",
            'orderby' => true,
        );
        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/sales_event_view", $param));
        //var_dump($data);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $html = $this->load->view('sales/manage_sales_event/print', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'landscape');
    }

    /**
     * [report_sm description]
     * @return [type] [description]
     */
    function report_sm() {
        $data = array();
        //$paramcustomer = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE), 
            'tanggal' => $this->input->get('tgl_event'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
           'field' => "TRANS_SALES_EVENT_VIEW.*",
            'custom' => "APPROVAL_MD = 1",
            'limit' => 15,
        );
        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/sales_event_view", $param));
        //var_dump($data);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('sales/manage_sales_event/report', $data);
    }
    public function act($id) {
        
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales_manage/event_budget", $param));
        $this->load->view('sales/manage_sales_event/act_budget', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_act($id) {
        $this->form_validation->set_rules('kd_budget', 'kd_budget', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
               // 'kd_event' => $this->input->post("kd_event"),
                'kd_budget' => $this->input->post("kd_budget"),
                'nama_budget' => $this->input->post("nama_budget"),
                'aktual_budget' => $this->input->post("aktual_budget"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales_manage/event_budget_aktual", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put'); }
    }
    //

    public function get_event(){
        $param = array(
            'id_event' => $this->input->get('id_event')
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/master_event", $param));

        $this->output->set_output(json_encode($data));
    }

    public function add_event() {
        $this->auth->validate_authen('sales_event/list_event');
        $data = array();
        $param_kab = array(
            'jointable' => array(
                array("MASTER_KABUPATEN MK", "MK.KD_KABUPATEN=MASTER_DEALER.KD_KABUPATEN", "LEFT")
            ),
            'field' => "MASTER_DEALER.KD_KABUPATEN AS KD_KABUPATEN, MK.NAMA_KABUPATEN AS NAMA_KABUPATEN",
            "custom" => "MASTER_DEALER.KD_DEALER='" .$this->session->userdata('kd_dealer'). "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/trans_sales_event"));
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["event"] = json_decode($this->curl->simple_get(API_URL . "/api/master/master_event", $paramcustomer)); 
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["jevent"] = json_decode($this->curl->simple_get(API_URL . "/api/master/master_jenis_event"));
        $this->load->view('sales/manage_sales_event/add_event', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_event_simpan() {
        $this->form_validation->set_rules('id_event', 'id_event', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'kd_dealer', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id_event' => $this->input->post("id_event"),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'nama_event' => $this->input->post("nama_event"),
                'desc_event' => $this->input->post("desc_event"),
                'jenis_event' => $this->input->post("jenis_event"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),
                'budget_event' => $this->input->post("budget_event"),
                'loc_event' => $this->input->post("loc_event"),
                //s'unit_to_display' => $this->input->post("unit_to_display"),
                'unit_target' => $this->input->post("unit_target"),
                'revenue_target' => $this->input->post("revenue_target"),
                'tanggal' => $this->input->post("tanggal"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/sales/trans_sales_event", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('sales_event/list_event'));

        }
    }

    public function prepare($id) {
        $this->auth->validate_authen('sales_event/list_event');
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SALES_EVENT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SALES_EVENT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/trans_sales_event", $param));   
        $paramcustomer["sales_status"] = "A";
        $paramcustomer["kd_dealer"] = $this->session->userdata("kd_dealer");
        $data["salesman"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramcustomer));
        $data["promoprogram"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/promoprogram"));
        $data["motor"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $this->load->view('sales/manage_sales_event/prepare', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    
    public function get_item(){
        $param=array(
            //'kd_dealer' => $this->input->get("kd_dealer"),
            'field'     => "KD_ITEM,NAMA_ITEM",
            'groupby'   => TRUE
        );
        $param["custom"] = "row_status=>0";
        $data= json_decode($this->curl->simple_get(API_URL . "/api/master/motor",$param));
        $this->output->set_output(json_encode($data));      
    }

    public function update_prepare($id) {
        $this->form_validation->set_rules('pic_salesevent', 'pic_salesevent', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'pic_salesevent' => $this->input->post("pic_salesevent"),
                'sp_salesevent' => $this->input->post("sp_salesevent"),
                'available_promotion' => $this->input->post("available_promotion"),
                'unit_to_display' => $this->input->post("unit_to_display"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/sales/salesevent_prepare", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function event_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/sales/trans_sales_event"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_EVENT;
            //$data_message[1][$key] = $message->JENIS_EVENT;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }

    

    
    function data_output($hasil = NULL, $method = null,$location=null,$dataid=null) {
        $result = "";
        switch ($method) {  
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan",
                        'location' => $location,
                        'dataid' => $dataid
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
