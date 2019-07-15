<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Service_reminder extends CI_Controller
{

    var $API;

    public function __construct()
    {
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

    public function pagination($config)
    {

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

    public function service_reminder()
    {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'TRANS_SERVICE_REMINDER.*',
            'orderby' => 'TRANS_SERVICE_REMINDER.ID DESC'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service_reminder/service_reminder", $param));

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


        $this->template->site('service_reminder/index', $data);
    }

    public function add_service_reminder()
    {
        $this->auth->validate_authen('service_reminder/service_reminder');

        $this->load->view('service_reminder/add_service_reminder');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function mesin_kpb()
    {
            $param_no['no_mesin'] = $this->input->get('id');
            
            $n = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb", $param_no));
            $this->output->set_output(json_encode($n));

    }

    public function add_service_reminder_simpan()
    {
        $this->form_validation->set_rules('nama_customer', 'nama_customer', 'required|trim');
        $this->form_validation->set_rules('kd_typemotor', 'kd_typemotor', 'required|trim');
        $this->form_validation->set_rules('no_polisi', 'no_polisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_customer' => $this->input->post("kd_customer"),
                'nama_customer' => $this->input->post("nama_customer"),
                'kd_typemotor' => $this->input->post("kd_typemotor"),
                'no_mesin' => $this->input->post("no_mesin"),
                'no_polisi' => $this->input->post("no_polisi"),
                'no_hp' => $this->input->post("no_hp"),
                'tgl_lastservice' => $this->input->post("tgl_lastservice"),
                'type_lastservice' => $this->input->post("type_lastservice"),
                'tgl_nextservice' => $this->input->post("tgl_nextservice"),
                'type_nextservice' => $this->input->post("type_nextservice"),
                'tgl_reminder' => $this->input->post("tgl_reminder"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
                
            $hasil = $this->curl->simple_post(API_URL . "/api/service_reminder/service_reminder", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('service_reminder/service_reminder'));
        }
    }

    public function edit_service_reminder($id, $row_status)
    {
        $this->auth->validate_authen('service_reminder/service_reminder');
        $param = array(
            "custom" => "TRANS_SERVICE_REMINDER.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service_reminder/service_reminder", $param));
        $this->load->view('service_reminder/edit_service_reminder', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_service_reminder($id)
    {
        $this->form_validation->set_rules('nama_customer', 'nama_customer', 'required|trim');
        $this->form_validation->set_rules('kd_typemotor', 'kd_typemotor', 'required|trim');
        $this->form_validation->set_rules('no_polisi', 'no_polisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'id' => $this->input->post("id"),
                'tgl_reminder' => $this->input->post("tgl_reminder"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_customer' => $this->input->post("kd_customer"),
                'nama_customer' => $this->input->post("nama_customer"),
                'kd_typemotor' => $this->input->post("kd_typemotor"),
                'no_mesin' => $this->input->post("no_mesin"),
                'no_polisi' => $this->input->post("no_polisi"),
                'no_hp' => $this->input->post("no_hp"),
                'tgl_lastservice' => $this->input->post("tgl_lastservice"),
                'type_lastservice' => $this->input->post("type_lastservice"),
                'tgl_nextservice' => $this->input->post("tgl_nextservice"),
                'type_nextservice' => $this->input->post("type_nextservice"),
                'status_sms' => $this->input->post("status_sms"),
                'status_call' => $this->input->post("status_call"),
                'booking_status' => $this->input->post("booking_status"),
                'alasan' => $this->input->post("alasan"),
                'reschedule' => $this->input->post("reschedule"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            
            $hasil = $this->curl->simple_put(API_URL . "/api/service_reminder/service_reminder", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function service_reminder_hapus($id)
    {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/service_reminder/service_reminder", $param));

        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('service_reminder/service_reminder')
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

    public function setupreminder()
    {
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

    public function kirimsms()
    {
        $param= array(
            'link' => 'kirimsms',
            'nohp' => '',
            'pesan' => 'Salam satu hati',
            'creaby' => 'Simande'
        );
        $data = $this->curl->simple_get(API_URL . "/api/login/webservicesms/true", $param);
        var_dump($data);
    }

    public function add_setupreminder()
    {
        $this->auth->validate_authen('service_reminder/setupreminder');

        $this->load->view('service_reminder/setup_reminder/add');
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_setupreminder_simpan()
    {
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

    public function edit_setupreminder($id, $row_status)
    {
        $this->auth->validate_authen('service_reminder/setupreminder');
        $param = array(
            "custom" => "SETUP_SERVICE_REMINDER.ID='" . $id . "'",
            'row_status' => $row_status
        );

        $data = array();

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service_reminder/setupreminder", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('service_reminder/setup_reminder/edit', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_setupreminder($id)
    {
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

    public function delete_setupreminder($id)
    {
        $data = array();
        $param = array(
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

    public function setupreminder_typeahead()
    {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/service_reminder/setupreminder"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->TYPE_SRV_NEXT;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    function data_output($hasil = NULL, $method = '')
    {
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
