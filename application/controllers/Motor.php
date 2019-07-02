<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/controllers/part.php';

class Motor extends Part {

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

    //Master Bundling
    public function bundling() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=MASTER_P_BUNDLING.KD_DEALER", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_TYPEMOTOR=MASTER_P_BUNDLING.KD_TYPEMOTOR", "LEFT", "KD_TYPEMOTOR,NAMA_TYPEMOTOR")
            ),
            'field' => "MASTER_P_BUNDLING.KD_DEALER,MASTER_P_BUNDLING.KD_BUNDLING,MASTER_P_BUNDLING.NAMA_BUNDLING,MASTER_P_BUNDLING.START_DATE,
            MASTER_P_BUNDLING.END_DATE,MASTER_P_BUNDLING.KD_TYPEMOTOR,\"CASE WHEN (MASTER_P_BUNDLING.KD_WARNA IS NULL OR MASTER_P_BUNDLING.KD_WARNA='') THEN 'ALL' ELSE MASTER_P_BUNDLING.KD_WARNA END KD_WARNA\",MP.NAMA_TYPEMOTOR,NAMA_DEALER,MASTER_P_BUNDLING.ROW_STATUS,\"CASE WHEN (\"CONVERT(CHAR,GETDATE(),112)\" > \"CONVERT(CHAR,END_DATE,112)\") OR 
                         (SELECT COUNT(KD_SALESPROGRAM) FROM TRANS_SPK_DETAILKENDARAAN AS TSP WHERE TSP.KD_SALESPROGRAM=MASTER_P_BUNDLING.KD_BUNDLING) >0 THEN 1 ELSE 0 END STATUS_BUNDLING\"",
            'orderby' => 'MASTER_P_BUNDLING.ID desc',
            "custom" => "MASTER_P_BUNDLING.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );

        //print_r($param);
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        //var_dump($data["list"]);exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], // base_url() . 'motor/bundling?keyword=' . $param['keyword'] . '&row_status=' . $param['row_status'],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_bundling', $data);
    }

    public function add_bundling($kd_bundling = '') {
        $this->auth->validate_authen('motor/add_bundling');
        $data = array();
        $param = array(
            'row_status' => 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $result["status"] = FALSE;
        $result["message"] = "Belum ada data / data tidak di temukan";
        $result["totaldata"] = 0;
        $data["bundling"] = json_decode(json_encode($result));
        if ($kd_bundling != '') {
            $param = array(
                'kd_bundling' => $kd_bundling,
                'jointable' => array(array("MASTER_P_BUNDLING_DETAIL MP", "MP.KD_BUNDLING=MASTER_P_BUNDLING.KD_BUNDLING AND MP.ROW_STATUS>=0", "LEFT")),
                'field' => "MASTER_P_BUNDLING.*,MP.ID AS DETAILID,MP.KD_ITEM,MP.NAMA_ITEM,MP.GROUP_BUNDLING,MP.KETERANGAN,MP.JUMLAH,
                         \"CASE WHEN (\"CONVERT(CHAR,GETDATE(),112)\" > \"CONVERT(CHAR,END_DATE,112)\") OR 
                         (SELECT COUNT(KD_SALESPROGRAM) FROM TRANS_SPK_DETAILKENDARAAN AS TSP WHERE TSP.KD_SALESPROGRAM=MASTER_P_BUNDLING.KD_BUNDLING) >0 THEN 1 ELSE 0 END STATUS_BUNDLING\""
            );
            $data["bundling"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        }
        $data["no_bundling"] = $this->getKodeBundling();
        $this->template->site('sales/add_bundling', $data);
    }

    public function add_bundling_simpan() {

        //$this->form_validation->set_rules('kd_bundling', 'Kode Bundling', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_bundling', 'Nama Bundling', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            //$kditem=explode($this->input->post("kd_item"));
            $kd_bundling = ($this->input->post("kd_bundling")) ? $this->input->post("kd_bundling") : $this->getKodeBundling();
            $param = array(
                'kd_bundling' => $kd_bundling, //html_escape($this->input->post("kd_bundling")),
                'nama_bundling' => html_escape($this->input->post("nama_bundling")),
                'kd_dealer' => $this->input->post('kd_dealer'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'kd_maindealer' => "T10",
                'kd_typemotor' => ($this->input->post("kd_item")),
                'kd_warna' => ($this->input->post("kd_item_wm")),
                'no_urut' => $this->getKodeBundling(true),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master/bundling", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            if ($data) {
                if ($data->recordexists == TRUE) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = $this->curl->simple_put(API_URL . "/api/master/bundling", $param, array(CURLOPT_BUFFERSIZE => 10));
                }
                $hasil = $this->add_detailbundling_simpan($kd_bundling);
            }

            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('motor/bundling'));
        }
    }

    function add_detailbundling_simpan($kd_bundling) {
        $param = array();
        $hasil = "";
        $data = array();
        $pdata = json_decode($this->input->post("detail"), true);

        $data = $pdata;
        for ($i = 0; $i < count($data); $i++) {
            $param["kd_bundling"] = $kd_bundling;
            $param["kd_item"] = $data[$i]["kd_item"];
            $param["nama_item"] = $data[$i]["nama_item"];
            $param["jumlah"] = $data[$i]['jumlah'];
            $param["keterangan"] = $data[$i]['keterangan'];
            $param["group_bundling"] = $data[$i]['group_bundling'];
            $param["created_by"] = $this->session->userdata("user_id");

            $hasil = $this->curl->simple_post(API_URL . "/api/master/bundling_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($param);var_dump($hasil);exit();
            $datax = json_decode($hasil);
            if ($datax) {
                if ($datax->recordexists == TRUE) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = $this->curl->simple_put(API_URL . "/api/master/bundling_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
                }
            }
        }
        return $hasil;
    }

    function getKodeBundling($return_totaldata = false) {
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom' => "YEAR(END_DATE)='" . date('Y') . "'",
            'field' => "ID,YEAR(END_DATE)"/* ,
                  'groupby'   => TRUE */
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        //print_r($data);exit();
        if ($data) {
            return ($return_totaldata == true) ? $data->totaldata : str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) . date('Y') . "-" . str_pad(($data->totaldata + 1), 3, "0", STR_PAD_LEFT);
        } else {
            return ($return_totaldata == true) ? 0 : str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) . date('Y') . "-" . str_pad(1, 3, "0", STR_PAD_LEFT);
        }
    }

    public function add_item_bundling($kd_bundling) {
        $this->auth->validate_authen('motor/bundling');
        $data = array();
        $param = array(
            'kd_bundling' => $kd_bundling
        );

        //$data["kd_bundling"] = $kd_bundling;
        //var_dump($data["kd_bundling"]);exit();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));

        $this->load->view('form_tambah/add_bundling_item', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_aksesoris_bundling($kd_bundling) {
        $this->auth->validate_authen('motor/bundling');
        $data = array();
        $param = array(
            'kd_bundling' => $kd_bundling
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/aksesoris"));

        $this->load->view('form_tambah/add_bundling_aksesoris', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_apparel_bundling($kd_bundling) {
        $this->auth->validate_authen('motor/bundling');
        $data = array();
        $param = array(
            'kd_bundling' => $kd_bundling
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/apparel"));

        $this->load->view('form_tambah/add_bundling_apparel', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_detail_bundling_simpan() {

        $this->form_validation->set_rules('type_bundling', 'Tipe Motor', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        //var_dump($this->input->post("kd_bundling"));exit();

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("status_bundling") == "Motor") {
                $param = array(
                    'kd_bundling' => html_escape($this->input->post("kd_bundling")),
                    'type_bundling' => substr($this->input->post("type_bundling"), 0, 3),
                    'kd_warna' => substr($this->input->post("type_bundling"), 4, 5),
                    'status_bundling' => html_escape($this->input->post("status_bundling")),
                    'jumlah' => $this->input->post('jumlah'),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_name')
                );
            } else {
                $param = array(
                    'kd_bundling' => html_escape($this->input->post("kd_bundling")),
                    'type_bundling' => $this->input->post("type_bundling"),
                    'status_bundling' => html_escape($this->input->post("status_bundling")),
                    'jumlah' => $this->input->post('jumlah'),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_name')
                );
            }
            $hasil = $this->curl->simple_post(API_URL . "/api/master/bundling_detail", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('motor/detail_bundling/' . $this->input->post("kd_bundling")));
        }
    }

    public function edit_bundling($kd_bundling, $row_status) {
        $this->auth->validate_authen('motor/bundling');
        $param = array(
            'kd_bundling' => $kd_bundling,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));

        $this->load->view('form_edit/edit_bundling', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_bundling($id) {
        $this->form_validation->set_rules('kd_bundling', 'Kode Bundling', 'required|trim');
        $this->form_validation->set_rules('nama_bundling', 'Nama Bundling', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_bundling' => html_escape($this->input->post("kd_bundling")),
                'nama_bundling' => html_escape($this->input->post("nama_bundling")),
                'kd_dealer' => $this->input->post('kd_dealer'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master/bundling", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_bundling($kd_bundling) {
        $param = array(
            'kd_bundling' => $kd_bundling,
            'lastmodified_by' => $this->session->userdata('user_name')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/bundling", $param));

        $this->data_output($data, 'delete', base_url('motor/bundling'));
    }

    public function detail_bundling($kd_bundling) {
        $data = array();
        $param_header = array(
            'kd_bundling' => $kd_bundling
        );

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_bundling' => $kd_bundling,
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR", "MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING=MASTER_P_TYPEMOTOR.KD_TYPEMOTOR", "LEFT"),
                //array("MASTER_P_TYPEMOTOR", "MASTER_P_BUNDLING_DETAIL.KD_WARNA=MASTER_P_TYPEMOTOR.KD_WARNA", "LEFT"),
                array("MASTER_P_BUNDLING", "MASTER_P_BUNDLING.KD_BUNDLING=MASTER_P_BUNDLING_DETAIL.KD_BUNDLING", "LEFT")
            ),
            'field' => '*',
            'orderby' => 'MASTER_P_BUNDLING_DETAIL.ID desc',
            "custom" => "MASTER_P_BUNDLING_DETAIL.STATUS_BUNDLING='Motor'",
            "custom" => "MASTER_P_BUNDLING_DETAIL.KD_WARNA=MASTER_P_TYPEMOTOR.KD_WARNA"
        );
        $param_aksesoris = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_bundling' => $kd_bundling,
            'jointable' => array(
                array("MASTER_AKSESORIS", "MASTER_AKSESORIS.KD_AKSESORIS=MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING", "LEFT"),
                array("MASTER_P_BUNDLING", "MASTER_P_BUNDLING.KD_BUNDLING=MASTER_P_BUNDLING_DETAIL.KD_BUNDLING", "LEFT")
            ),
            'field' => '*',
            'orderby' => 'MASTER_P_BUNDLING_DETAIL.ID desc',
            "custom" => "MASTER_P_BUNDLING_DETAIL.STATUS_BUNDLING='Aksesoris'"
        );
        $param_apparel = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_bundling' => $kd_bundling,
            'jointable' => array(
                array("MASTER_APPAREL", "MASTER_APPAREL.KD_APPAREL=MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING", "LEFT"),
                array("MASTER_P_BUNDLING", "MASTER_P_BUNDLING.KD_BUNDLING=MASTER_P_BUNDLING_DETAIL.KD_BUNDLING", "LEFT")
            ),
            'field' => '*',
            'orderby' => 'MASTER_P_BUNDLING_DETAIL.ID desc',
            "custom" => "MASTER_P_BUNDLING_DETAIL.STATUS_BUNDLING='Apparel'"
        );
        $data["list_header"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling", $param_header));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling_detail", $param));
        $data["list_aksesoris"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling_detail", $param_aksesoris));
        $data["list_apparel"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling_detail", $param_apparel));
        /**
          $config = array(
          'per_page' => $param['limit'],
          'base_url' => base_url() . 'motor/bundling?keyword=' . $param['keyword'] . '&row_status=' . $param['row_status'],
          'total_rows' => $data["list"]->totaldata
          );

          $pagination = $this->template->pagination($config);

          $this->pagination->initialize($pagination);
          $data['pagination'] = $this->pagination->create_links();
         * */
        $this->template->site('form_detail/bundling', $data);
    }

    public function edit_bundling_item($kd_bundling, $type_bundling, $kd_warna, $status_bundling) {
        $this->auth->validate_authen('motor/bundling');
        if ($status_bundling == "Motor") {
            $param = array(
                'kd_bundling' => $kd_bundling,
                "custom" => "MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING='" . $type_bundling . "'",
                "custom" => "MASTER_P_BUNDLING_DETAIL.KD_WARNA='" . $kd_warna . "'"
            );
        } else {
            $param = array(
                'kd_bundling' => $kd_bundling,
                "custom" => "MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING='" . $type_bundling . "'"
            );
        }


        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling_detail", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));

        $this->load->view('form_edit/edit_bundling_item', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function edit_bundling_aksesoris($kd_bundling, $type_bundling) {
        $this->auth->validate_authen('motor/bundling');
        $param = array(
            'kd_bundling' => $kd_bundling,
            "custom" => "MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING='" . $type_bundling . "'"
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling_detail", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/aksesoris"));

        $this->load->view('form_edit/edit_bundling_aksesoris', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function edit_bundling_apparel($kd_bundling, $type_bundling) {
        $this->auth->validate_authen('motor/bundling');
        $param = array(
            'kd_bundling' => $kd_bundling,
            "custom" => "MASTER_P_BUNDLING_DETAIL.TYPE_BUNDLING='" . $type_bundling . "'"
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling_detail", $param));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/apparel"));

        $this->load->view('form_edit/edit_bundling_apparel', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function edit_detail_bundling_simpan($id) {

        $this->form_validation->set_rules('type_bundling', 'Tipe', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        //var_dump($this->input->post("kd_bundling"));exit();

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            if ($this->input->post("status_bundling") == "Motor") {
                $param = array(
                    'id' => $id,
                    'kd_bundling' => html_escape($this->input->post("kd_bundling")),
                    'type_bundling' => substr(html_escape($this->input->post("type_bundling")), 0, 3),
                    'kd_warna' => substr(html_escape($this->input->post("type_bundling")), 4, 5),
                    'status_bundling' => html_escape($this->input->post("status_bundling")),
                    'jumlah' => html_escape($this->input->post('jumlah')),
                    'row_status' => 0,
                    'lastmodified_by' => $this->session->userdata("user_id")
                );
            } else {
                $param = array(
                    'id' => $id,
                    'kd_bundling' => html_escape($this->input->post("kd_bundling")),
                    'type_bundling' => html_escape($this->input->post("type_bundling")),
                    'status_bundling' => html_escape($this->input->post("status_bundling")),
                    'jumlah' => html_escape($this->input->post('jumlah')),
                    'row_status' => 0,
                    'lastmodified_by' => $this->session->userdata("user_id")
                );
            }

            $hasil = $this->curl->simple_put(API_URL . "/api/master/bundling_detail", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_bundling_detail($kd_bundling) {
        $param = array(
            'id' => $this->input->post("id"),
            'lastmodified_by' => $this->session->userdata('user_name')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/bundling_detail", $param));

        //$this->data_output($data, 'delete', base_url('motor/detail_bundling/'.$kd_bundling));
        $this->data_output($data, 'delete', base_url('motor/add_bundling/' . $kd_bundling));
    }

    public function bundling_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/bundling"), true);

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_BUNDLING;
            $data_message[1][$key] = $message->NAMA_BUNDLING;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    public function aparel_typeahead($result = false) {
        $param = array();
        if ($this->input->post("nama_aparel")) {
            $param = array(
                "nama_barang" => $this->input->post("nama_aparel")
            );
        }
        $param["kategori"] = 'Apparel';
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));
        if ($result == false) {
            foreach ($data["list"]->message as $key => $message) {
                $data_message[$key] = $message->NAMA_BARANG;
                //$data_message[1][$key] = $message->NAMA_BUNDLING;
            }

            $result['keyword'] = $data_message; // array_merge($data_message[0], $data_message[1]);
            $this->output->set_output(json_encode($result));
        } else {
            if ($data["list"]) {
                if ($data["list"]->totaldata > 0) {
                    echo json_encode(($data["list"]->message));
                } else {
                    echo "[]";
                }
            } else {
                echo "[]";
            }
        }
    }

    public function aksesoris_typeahead($result = false) {
        $param = array();
        if ($this->input->post("nama_aksesoris")) {
            $param = array(
                "nama_barang" => $this->input->post("nama_aksesoris")
            );
        }
        $param["kategori"] = 'Aksesoris';
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));
        if ($result == false) {
            foreach ($data["list"]->message as $key => $message) {
                $data_message[$key] = $message->NAMA_BARANG;
                //$data_message[1][$key] = $message->NAMA_BUNDLING;
            }

            $result['keyword'] = $data_message; // array_merge($data_message[0], $data_message[1]);
            $this->output->set_output(json_encode($result));
        } else {
            if ($data["list"]) {
                if ($data["list"]->totaldata > 0) {
                    echo json_encode(($data["list"]->message));
                } else {
                    echo "[]";
                }
            } else {
                echo "[]";
            }
        }
    }

    //Master Category
    public function category() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor", $param));
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_category', $data);
    }

    public function add_category() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor"));

        $this->load->view('form_tambah/add_category', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_category_simpan() {
        $this->form_validation->set_rules('kd_category', 'Kode Kategori Motor', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_category', 'Nama Kategori Motor', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_category' => html_escape($this->input->post("kd_category")),
                'nama_category' => html_escape($this->input->post("nama_category")),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master/categorymotor", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('motor/category'));
        }
    }

    public function edit_category($kd_category, $row_status) {
        $this->auth->validate_authen('motor/category');
        $param = array(
            'kd_category' => $kd_category,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor", $param));

        $this->load->view('form_edit/edit_category', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_category($id) {
        $this->form_validation->set_rules('kd_category', 'Kode Kategori Motor', 'required|trim');
        $this->form_validation->set_rules('nama_category', 'Nama Kategori Motor', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_category' => html_escape($this->input->post("kd_category")),
                'nama_category' => html_escape($this->input->post("nama_category")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master/categorymotor", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_category($kd_category) {
        $param = array(
            'kd_category' => $kd_category,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/categorymotor", $param));

        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('motor/category')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );
        }
        $this->output->set_output(json_encode($data_status));
    }

    public function category_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/category"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_CATEGORY;
            $data_message[1][$key] = $message->NAMA_CATEGORY;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master Grup Motor
    public function grup_motor() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'kd_groupmotor' => $this->input->get('kd_groupmotor'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*',
            'orderby' => 'MASTER_P_GROUPMOTOR.ID desc'
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/groupmotor", $param));
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_group_motor', $data);
    }

    public function add_grup_motor() {
        $param = array(
            'row_status' => 0
        );
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));
        $data["categories"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor", $param));
        $data["segment"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen", $param));
        $data["seriest"] = json_decode($this->curl->simple_get(API_URL . "/api/master/seriesmotor", $param));

//        var_dump($data);
//        exit;
        $this->load->view('form_tambah/add_grup_motor', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function import_grup_motor() {
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
                            'kd_groupmotor' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'nama_groupmotor' => !empty($importdata[1]) ? rtrim($importdata[1]) : '',
                            'kd_typemotor' => !empty($importdata[0]) ? rtrim($importdata[0]) : '',
                            'category_motor' => !empty($importdata[2]) ? rtrim($importdata[2]) : '',
                            'sembilan_segmen' => !empty($importdata[3]) ? rtrim($importdata[3]) : '',
                            'series' => !empty($importdata[4]) ? rtrim($importdata[4]) : '',
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id') . ": upload file .UGM"
                        );
                        //$param[$x]=$arr;
                        /* if($n==100){
                          $n=0;
                          $hasilx = $this->simpan_part($arr);
                          } */
                        $n++;
                    }
                    //API Url
                    $url = API_URL . "/api/master/ugmbatch";

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
        //var_dump($data);exit;
        $this->data_output($data, 'post', base_url('motor/grup_motor'));
    }

    public function notEmpty() {
        if (!empty($_FILES['file']['name'])) {
            return TRUE;
        } else {
            $this->form_validation->set_message('notEmpty', 'The {field} field can not be empty.');
            return FALSE;
        }
    }

    public function edit_grup_motor($kd_groupmotor) {
        $this->auth->validate_authen('motor/grup_motor');
        $param = array(
            'kd_groupmotor' => $kd_groupmotor
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/groupmotor", $param));
        $param = array(
            'row_status' => 0
        );
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));
        $data["categories"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor", $param));
        $data["segment"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen", $param));
        $data["seriest"] = json_decode($this->curl->simple_get(API_URL . "/api/master/seriesmotor", $param));

        $this->load->view('form_edit/edit_grup_motor', $data);

        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_grup_motor($id) {
        $this->form_validation->set_rules('kd_groupmotor', 'Kode Group Motor', 'required|trim');
        $this->form_validation->set_rules('nama_groupmotor', 'Nama Group Motor', 'required|trim');
        $this->form_validation->set_rules('kd_item', 'Kode Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('kd_category', 'Kategori Motor', 'required|trim');
        $this->form_validation->set_rules('kd_segmen', 'Segmen Motor', 'required|trim');
        $this->form_validation->set_rules('kd_series', 'Series Motor', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_groupmotor' => html_escape($this->input->post("kd_groupmotor")),
                'nama_groupmotor' => html_escape($this->input->post("nama_groupmotor")),
                'kd_typemotor' => $this->input->post("kd_item"),
                'category_motor' => $this->input->post('kd_category'), // kd_category
                'sembilan_segmen' => $this->input->post('kd_segmen'), // kd_segmen
                'series' => $this->input->post('kd_series'), // kd_series
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master/groupmotor", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }

    public function delete_grup_motor($kd_groupmotor) {
        $param = array(
            'kd_groupmotor' => $kd_groupmotor,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/groupmotor", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('motor/grup_motor')
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

    public function grup_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/groupmotor"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_GROUPMOTOR;
            $data_message[1][$key] = $message->NAMA_GROUPMOTOR;
            $data_message[2][$key] = $message->KD_TYPEMOTOR;
            $data_message[3][$key] = $message->CATEGORY_MOTOR;
            $data_message[4][$key] = $message->SEMBILAN_SEGMEN;
            $data_message[5][$key] = $message->SERIES;
        }


        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2]);


        $this->output->set_output(json_encode($result));
    }

    //Master Segmen Motor
    public function segmen_motor() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen", $param));

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


        $this->template->site('master/master_segmen_motor', $data);
    }

    public function add_segmen_motor() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen"));
        $this->load->view('form_tambah/add_segmen_motor', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_segmen_motor_simpan() {
        $this->form_validation->set_rules('kd_segmen', 'Kode Segmen Motor', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_segmen', 'Nama Segmen Motor', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_segmen' => $this->input->post("kd_segmen"),
                'nama_segmen' => $this->input->post("nama_segmen"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/segmen", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('motor/segmen_motor'));
        }
    }

    public function edit_segmen_motor($kd_segmen, $row_status) {
        $this->auth->validate_authen('motor/segmen_motor');
        $param = array(
            'kd_segmen' => $kd_segmen,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen", $param));

        $this->load->view('form_edit/edit_segmen_motor', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_segmen_motor($id) {
        $this->form_validation->set_rules('kd_segmen', 'Kode Segmen Motor', 'required|trim');
        $this->form_validation->set_rules('nama_segmen', 'Nama Segmen Motor', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_segmen' => html_escape($this->input->post("kd_segmen")),
                'nama_segmen' => html_escape($this->input->post("nama_segmen")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/segmen", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_segmen_motor($kd_segmen) {
        $param = array(
            'kd_segmen' => $kd_segmen,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/segmen", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('motor/segmen_motor')
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

    public function segmen_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_SEGMEN;
            $data_message[1][$key] = $message->NAMA_SEGMEN;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    public function segmen_motor_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/segmen"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_SEGMEN;
            $data_message[1][$key] = $message->NAMA_SEGMEN;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    /**
     * [series_motor description]
     * @return [type] [description]
     */
    public function series_motor() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/seriesmotor", $param));
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_series_motor', $data);
    }

    public function add_series_motor() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/seriesmotor"));

        $this->load->view('form_tambah/add_series_motor', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_series_motor_simpan() {
        $this->form_validation->set_rules('kd_series', 'Kode Seri Motor', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_series', 'Nama Series', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_series' => html_escape($this->input->post("kd_series")),
                'nama_series' => html_escape($this->input->post("nama_series")),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master/seriesmotor", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('motor/series_motor'));
        }
    }

    public function edit_series_motor($kd_series, $row_status) {
        $this->auth->validate_authen('motor/series_motor');
        $param = array(
            'kd_series' => $kd_series,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/seriesmotor", $param));

        $this->load->view('form_edit/edit_series_motor', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_series_motor($id) {
        $this->form_validation->set_rules('kd_series', 'Kode Seri Motor', 'required|trim');
        $this->form_validation->set_rules('nama_series', 'Nama Seri Motor', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_series' => html_escape($this->input->post("kd_series")),
                'nama_series' => html_escape($this->input->post("nama_series")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master/seriesmotor", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_series_motor($kd_series) {
        $param = array(
            'kd_series' => $kd_series,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/seriesmotor", $param));

        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('motor/series_motor')
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

    public function series_motor_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/seriesmotor"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_SERIES;
            $data_message[1][$key] = $message->NAMA_SERIES;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master Tipe Motor
    public function tipe_motor($dataOnly = null,$debug=null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_P_TYPEMOTOR.LASTMODIFIED_TIME DESC'
        );
        if ($this->input->get("otm") == true) {
            $param = array(
                "field" => "KD_TYPEMOTOR,NAMA_TYPEMOTOR,JENIS_MOTOR,NAMA_PASAR",
                "groupby" => TRUE,
                "orderby" => "KD_TYPEMOTOR,NAMA_TYPEMOTOR"
            );
        }
        if ($this->input->get('aktif') == true) {
            $param["custom"] = "TGL_AKHIREFF >= GETDATE()";
        }
        if($dataOnly){
            unset($param["limit"]);
            unset($param["orderby"]);
            if (!$this->input->get("otm")) {
                $param["orderby"] ="KD_TYPEMOTOR,KD_WARNA";
            }
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));

        if ($dataOnly == "1") {
            if ($data["list"]) {
                if($debug){
                    echo json_encode($data["list"]);
                    exit();
                }
                if ($data["list"]->totaldata > 0) {
                    echo json_encode($data["list"]->message);
                }
            }
        } else {       
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();

            $this->template->site('master/master_tipe_motor', $data);
        }
    }

    public function add_tipe_motor() {
        //ob_clean();
        ini_set('max_execution_time', 300);
        $data = array();
        $param = array();
        $js = array();
        $param["link"] = "list22";
        $data["dataxx"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"),true);
        $data["listmd"] = json_decode($this->curl->simple_get(API_URL . "/api/login/webservice/true", $param));
        // if($listmd->status==false){
        //     $data["listmd"] = $listmd;
        // }else{
        //     $js=json_decode($listmd->message,true);
        //     $n=0;
        //     for($i=0;$i < count($js);$i++){
        //         $param=array("kd_item" => $js[$i]["kditem"]);
        //         $datax=array();
        //         // $datax = json_decode($this->curl->simple_get(API_URL . "/api/master/motor",$param));
        //         $datax =(object)$this->filter_by_value($dataxx["message"],"KD_ITEM",$js[$i]["kditem"]);
        //         // var_dump($datax);exit();
        //         if($datax){
        //             //if($datax->totaldata >0){
        //                 foreach ($datax as $key => $value) {
        //                     $simande = $value->KD_TYPEMOTOR.$value->NAMA_TYPEMOTOR.$value->KD_WARNA.$value->KET_WARNA.$value->NAMA_PASAR.number_format((double)$value->CC_MOTOR,2).$value->KD_ITEM.$value->NAMA_ITEM.$value->JENIS_MOTOR.str_replace('-','',$value->TGL_AWALEFF).str_replace('-','',$value->TGL_AKHIREFF).number_format((double)$value->CBU,2).$value->SEGMENT.$value->TRIO_STATUS.$value->SUB_KATEGORI;
        //                 }
        //             //}
        //         }
        //         $webservice = ($js[$i]["kdtipe"].$js[$i]["ket1"].$js[$i]["kdwarna"].$js[$i]["ketwarna"].$js[$i]["ket2"].number_format((double)$js[$i]["kapasitas"],2).$js[$i]["kditem"].$js[$i]["ketwarna2"].$js[$i]["kategori"].tglToSql($js[$i]["bgneffd"]).tglToSql($js[$i]["lsteffd"],'-').number_format((double)$js[$i]["vcbu"],2).$js[$i]["triostatus"].$js[$i]["segment"].$js[$i]["subkategori"]);
        //         $compare =strcasecmp(md5(strtoupper(trim($webservice))),md5(strtoupper(trim($simande))));
        //         if($compare !=0){
        //             $n++;
        //             $std =json_decode($listmd->message,true);
        //             $list['status'] =true;
        //             $list["message"][$i]= $std[$i];
        //         }
        //         /*if($n==10){
        //             break;
        //         }*/
        //     }
        //     if($list){
        //         $data["listmd"]=(object)($list);
        //     }
        // }
        //exit();
        $this->load->view('form_tambah/add_tipe_motor', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /* 
     * filtering an array 
     */ 
    function filter_by_value ($array, $index, $value){ 
        if(is_array($array) && count($array)>0)  
        { 
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $newarray[$key] = $array[$key]; 
                } 
            } 
          } 
      return $newarray; 
    } 
    public function update_tipe_motor() {
        ini_set('max_execution_time','200');
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));

        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);

        $hasil = "";
        $n=0;
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_type' => $data[0][$i]["kdtipe"],
                'nama_typemotor' => $data[0][$i]["ket1"],
                'kd_warna' => $data[0][$i]["kdwarna"],
                'ket_warna' => $data[0][$i]["ketwarna"],
                'nama_pasar' => $data[0][$i]["ket2"],
                'kd_item' => $data[0][$i]["kditem"],
                'nama_item' => $data[0][$i]["ketwarna2"],
                'cc_motor' => $data[0][$i]["kapasitas"],
                'jenis_motor' => $data[0][$i]["kategori"],
                'tgl_awaleff' => $data[0][$i]["bgneffd"],
                'tgl_akhireff' => $data[0][$i]["lsteffd"],
                'cbu' => $data[0][$i]["vcbu"],
                'segment' => $data[0][$i]["segment"],
                'trio_status'=> $data[0][$i]["triostatus"],
                'sub_kategori' => $data[0][$i]["subkategori"],
                'modif_date' => $data[0][$i]['dmodi'],
                'created_by' => $this->session->userdata("user_id") . "| ws_list22"
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/master/motor", $param, array(CURLOPT_BUFFERSIZE => 10)));
            //var_dump($hasil);print_r($param);exit();
            $hasile=json_decode($hasil);
            if($hasile->recordexists){
                $param["lastmodified_by"]= $this->session->userdata("user_id") . "| ws_list22";
                $hasil = ($this->curl->simple_put(API_URL . "/api/master/motor", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            /*if($n==100){
               break;
            }*/
        }

        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " data berhasil di simpan"
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

    public function edit_tipe_motor($kdtm=null,$debug=null) {
        $this->auth->validate_authen('motor/tipe_motor');
        ob_end_clean();
        $param = array();
        $param = array(
            'kd_item' => ($this->input->get('kd_item'))?$this->input->get('kd_item'):$kdtm
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));
        if($debug){var_dump($data);exit();}
        $this->load->view('form_edit/edit_tipe_motor', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function tipemotor_typeahead($json = false, $item = false) {

        $param = array(
            'kd_item' => $this->input->get('kd_item'),
            'field' => "KD_TYPEMOTOR, NAMA_PASAR, CC_MOTOR, KD_WARNA, KD_ITEM, KET_WARNA",
            'groupby' => true
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));

        if($json == 'true'){
            $this->output->set_output(json_encode($data["list"]->message));
        }
        elseif($item == 'true'){
            $this->output->set_output(json_encode($data["list"]->message));
        }
        else{

            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->KD_TYPEMOTOR;
                $data_message[1][$key] = $message->NAMA_PASAR;
                $data_message[2][$key] = $message->CC_MOTOR;
                $data_message[3][$key] = $message->KD_WARNA;
            }

            $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2], $data_message[3]);

            $this->output->set_output(json_encode($result));
        }
    }

     public function typemotor_typeahead() {
        $data = [];
        $keywords = $this->input->get('keyword');

        $param = array(
            'keyword' => $keywords
        );
        $data_message = [];
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));

        // var_dump($data); exit;

        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->KD_TYPEMOTOR . " - " . $message->NAMA_TYPEMOTOR . " - " . $message->NAMA_ITEM;
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


    //Master Type Motor Marketing
    public function typemotorm() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_TYPEMOTOR_MARKETING.ID desc'
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/typemotor_marketing", $param));
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_typemotorm', $data);
    }

    public function add_typemotorm() {
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/categorymotor"));

        $this->load->view('form_tambah/add_typemotorm');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_typemotorm_simpan() {
        $this->form_validation->set_rules('type_marketing', 'Tipe Motor Marketing', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi/ Varian', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'type_marketing' => $this->input->post("type_marketing"),
                'deskripsi' => $this->input->post("deskripsi"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/marketing/typemotor_marketing", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('motor/typemotorm'));
        }
    }

    public function edit_typemotorm($id, $row_status) {
        $this->auth->validate_authen('motor/category');
        $param = array(
            "custom" => "ID='" . $id . "'",
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/typemotor_marketing", $param));

        $this->load->view('form_edit/edit_typemotorm', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_typemotorm($id) {
        $this->form_validation->set_rules('type_marketing', 'Tipe Motor Marketing', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi/ Varian', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'type_marketing' => $this->input->post("type_marketing"),
                'deskripsi' => $this->input->post("deskripsi"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/marketing/typemotor_marketing", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_typemotorm($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/marketing/typemotor_marketing", $param));

        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('motor/typemotorm')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );
        }
        $this->output->set_output(json_encode($data_status));
    }

    public function typemotorm_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/marketing/typemotor_marketing"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->TYPE_MARKETING;
            $data_message[1][$key] = $message->DESKRIPSI;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

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
    //proses mutasi unit
    
    function mutasiunit_list($no_trans=null){

        $data=array();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];
        $param = array(
               'keyword' => $this->input->get('keyword'),
               'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
               'limit' => $config['per_page'],
               'tipe_trans' =>'UNIT',
               'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
               'jointable' => array(
                                array("MASTER_DEALER MD","MD.KD_DEALER = TRANS_INV_MUTASI.KD_DEALER_TUJUAN AND MD.ROW_STATUS >= 0","LEFT")),
               'field'  =>"TRANS_INV_MUTASI.*, MD.NAMA_DEALER",
               'orderby'=> "TRANS_INV_MUTASI.NO_TRANS DESC,TRANS_INV_MUTASI.ID"
             );
        $paramd=array("kd_dealer" => $this->session->userdata("kd_dealer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        if($this->input->get('dari_tanggal')){
            $param["custom"] ="CONVERT(CHAR,TRANS_INV_MUTASI.TGL_TRANS,112) BETWEEN '".TglToSql($this->input->get("dari_tanggal"))."' AND '".TglToSql($this->input->get("sampai_tanggal"))."'";
        }
        if($no_trans){
            $param=array("no_trans" => $no_trans);
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
       
        if($no_trans){
            $data["ntrans"]=$no_trans;
            $this->template->site('inventori/mutasi/mutasiunit_add', $data);
        }
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => (isset($data["list"])) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('inventori/mutasi/mutasiunit_list', $data);
    }
    function mutasiunit_add($no_trans=null){
        $this->auth->validate_authen("mutasiunit_add");
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jenis_gudang'=>'UNIT'
        );
        $data["gudang"] = json_decode($this->curl->simple_get(API_URL."/api/master/gudang",$param));
        //print_r($data['gudang']);die();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer"));

        if($no_trans){
            $param=array(
                'no_trans'  => $no_trans
            );
            $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        }
        $data["ntrans"]=$no_trans;
        $this->template->site('inventori/mutasi/mutasiunit_add', $data);
    }
    function mutasiunit_simpan(){
        $param=array();$data=array();$datax=array();
        $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        $param["kd_maindealer"] = $this->session->userdata("kd_maindealer");
        $param["no_trans"]      =($this->input->post("no_trans"))?$this->input->post("no_trans"): $this->notransaksi();
        $param["tgl_trans"]     = ($this->input->post("tgl_mutasi"));
        $param["part_number"]   = $this->input->post("no_rangka");
        $param["keterangan"]    = $this->input->post("keterangan");
        $param["tipe_trans"]    = 'UNIT';
        $param["jenis_trans"]   = $this->input->post("jenis_mutasi");
        $param["jumlah"]        = '1';
        $param["kd_gudang_asal"]= $this->input->post("kd_gudang");
        $param["kd_gudang_tujuan"] = $this->input->post("kd_gudang_tujuan");
        $param["kd_dealer_tujuan"] = $this->input->post("kd_dealer_tujuan");
        $param["kd_status_tujuan"] = $this->input->post("kd_status_tujuan");
        $param["created_by"]    = $this->session->userdata("user_id");
        $param["status_mutasi"] ="0";
        if ($this->input->post("jenis_mutasi") == 'Status Unit'){
             $param["lastmodified_by"]=$this->session->userdata("user_id");
             $param["status_mutasi"] ="1";
             $param["kd_gudang_tujuan"] = $this->input->post("kd_status_tujuan");
             $this->curl->simple_get(API_URL . "/api/inventori/update_status_nrfs", $param);
             
        }
       
        $data=($this->curl->simple_post(API_URL . "/api/inventori/inv_mutasi", $param));
        $datax=json_decode($data);
        if($datax){
            if($datax->recordexists){
                $param["lastmodified_by"]=$this->session->userdata("user_id");
                $data=($this->curl->simple_put(API_URL . "/api/inventori/inv_mutasi", $param));
            }
        }
        redirect(base_url()."motor/mutasiunit_add/".$param["no_trans"]);
        //$this->output->set_output($data,'post');
    }
    function mutasiunit_del($id=null){
        $param=array(
            'id'    =>$this->input->get('id'),
            'lastmodified_by'=> $this->session->userdata("user_id")
        );
        $data=($this->curl->simple_delete(API_URL . "/api/inventori/inv_mutasi", $param));
        $this->output->set_output($data,'delete');
    }
    function mutasiunit_print(){
        $data=array();
        $param=array(
            'no_trans'  => $this->input->get("n")
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));
        $data["dlr"] = $this->input->get("d");
        $data["no_trans"] = $param["no_trans"];
        $param=array("kd_dealer"=>$this->input->get("d"),"jenis_gudang" =>'UNIT');
        $data["gudang"] = json_decode($this->curl->simple_get(API_URL."/api/master/gudang/true",$param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer/true"));

        $this->load->view('inventori/mutasi/mutasiunit_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function notransaksi($debug=null){
        $result= $this->autogenerate_trans("MT");
        if($debug){echo $result;}else{ return $result;}
    }
    function getNoRangka(){
        $result=array();
        $param=array(
            'kd_dealer' => $this->input->get("d"),
            'kd_gudang' => $this->input->get("g"),
            'jointable' => array(
                array("TRANS_TERIMASJMOTOR AS TT", "TT.NO_MESIN = TRANS_STOCKMOVEMENT.NO_MESIN", "LEFT")               
            ),
            'field'    => "TRANS_STOCKMOVEMENT.*,TT.KSU"

        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/unitmovement", $param));
        
        if($data){
            if($data->totaldata>0){
                $result=$data->message;
            }
        }
        echo json_encode($result);
    }

     function getNoRangkaNRFS(){
        $result=array();
        if ($this->input->get('s') == 'NRFS') {
           $stock_status = 1;
        }else{
            $stock_status = 0;
        }
        $param=array(
            'kd_dealer' => $this->input->get("d"),
             'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM = TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT"),
               
            ),
            'field'    => "TRANS_TERIMASJMOTOR.NO_RANGKA,TRANS_TERIMASJMOTOR.NO_MESIN,TRANS_TERIMASJMOTOR.KD_ITEM, TRANS_TERIMASJMOTOR.KSU,MP.NAMA_TYPEMOTOR,MP.NAMA_ITEM",
            'custom'    => "TRANS_TERIMASJMOTOR.STOCK_STATUS =".$stock_status

            
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));

        if($data){
            if($data->totaldata>0){
                $result=$data->message;
            }
        }
        echo json_encode($result);
    }

    //srut
    public function srut() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'TRANS_SRUT.*',
            'orderby' => 'TRANS_SRUT.ID DESC',
            "custom" => "TRANS_SRUT.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"

        );
 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/srut", $param));
 
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
 
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
 
 
        $this->template->site('master/master_srut', $data);
    }

    public function add_srut() {
        

        $this->auth->validate_authen('motor/srut');
        //ob_clean();
        $data = array();
        $param = array();
        $js = array();
        $y = date('Y');
        $m = date('m');

        
        $param["param"] = $this->session->userdata("kd_dealer")."/".$y.$m;
        //$param["param"] = $this->session->userdata("kd_dealer")."/201810";
        //var_dump($param["param"]);exit;
        $param["link"] = "list44";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/srut"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        //var_dump($data["list"]);exit;
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);

        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $data["listmd"] = $js;
        $this->load->view('form_tambah/add_srut', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_srut() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));

        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        /* print_r($data[0]);
          exit(); */
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_dealer' => $data[0][$i]["kddlr"],
                'no_terima_dealer' => $data[0][$i]["notterimadlr"],
                'tgl_terima' => $data[0][$i]["tgltterima"],
                'no_mesin' => $data[0][$i]["nomesin"],
                'no_rangka' => $data[0][$i]["norangka"],
                'no_sut' => $data[0][$i]["nosut"],
                'no_srut' => $data[0][$i]["nosrut"],
                'created_by' => $this->session->userdata("user_id")
            );

            $hasil = ($this->curl->simple_post(API_URL . "/api/transaksi/srut", $param, array(CURLOPT_BUFFERSIZE => 10)));
            $datar = json_decode($hasil);
            //if($hasil){
            if ($datar->recordexists == TRUE) {
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = ($this->curl->simple_put(API_URL . "/api/transaksi/srut", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            //}
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

    public function srut_typeahead() {
        $param = array(
            "custom" => "TRANS_SRUT.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"

        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/srut", $param));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_MESIN;
            $data_message[0][$key] = $message->NO_TERIMA_DEALER;
            $data_message[0][$key] = $message->NO_RANGKA;
            
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }


    /*     * stnk_bpkb description]
     * @return [type] [description]
     */

    public function toleransi() {
        $data = array();
        if ($this->session->userdata('kd_group') == 'root') {
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'jointable' => array(
                    array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_TOLERANSI.KD_KABUPATEN", "LEFT")
                ),
                'field' => 'MASTER_TOLERANSI.*, MK.NAMA_KABUPATEN',
                'orderby' => 'MASTER_TOLERANSI.ID DESC'
            );
        } else {
            if($this->session->userdata('kd_dealer') != null){
                $cek = array(
                'field' => 'MASTER_DEALER.*',
                "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
                );

                $data = array();
               

                if ($this->session->userdata("status_cabang") == "T") {
                    $param = array(
                        'keyword' => $this->input->get('keyword'),
                        'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                        'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                        'limit' => 15,
                        'jointable' => array(
                            array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_TOLERANSI.KD_KABUPATEN", "LEFT")
                        ),
                        'field' => 'MASTER_TOLERANSI.*, MK.NAMA_KABUPATEN',
                        "custom" => "MASTER_TOLERANSI.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
                            // 'orderby' => 'MASTER_STNK_BPKB.ID DESC'
                            // 'orderby' =>'ROW_NUMBER() OVER(PARTITION BY KD_DEALER,KD_TIPEMOTOR,TAHUN ORDER BY ID DESC)'
                    );
                }else{
                    $param = array(
                    'keyword' => $this->input->get('keyword'),
                    'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                    'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                    'limit' => 15,
                    'jointable' => array(
                        array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_TOLERANSI.KD_KABUPATEN", "LEFT")
                    ),
                    'field' => 'MASTER_TOLERANSI.*, MK.NAMA_KABUPATEN',
                    "custom" => "MASTER_TOLERANSI.KD_DEALER='ALL'",
                    'orderby' => 'MASTER_TOLERANSI.ID DESC'
                        // 'orderby' => 'ROW_NUMBER() OVER(PARTITION BY KD_DEALER,KD_TIPEMOTOR,TAHUN ORDER BY ID DESC)'
                );
                }
            }else{
                $param = array(
                    'keyword' => $this->input->get('keyword'),
                    'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                    'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                    'limit' => 15,
                    'jointable' => array(
                        array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_TOLERANSI.KD_KABUPATEN", "LEFT")
                    ),
                    'field' => 'MASTER_TOLERANSI.*, MK.NAMA_KABUPATEN',
                    "custom" => "MASTER_TOLERANSI.KD_DEALER='ALL'",
                    'orderby' => 'MASTER_TOLERANSI.ID DESC'
                        // 'orderby' => 'ROW_NUMBER() OVER(PARTITION BY KD_DEALER,KD_TIPEMOTOR,TAHUN ORDER BY ID DESC)'
                );
            }
            

            
            /*else {
                $param = array(
                    'keyword' => $this->input->get('keyword'),
                    'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                    'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                    'limit' => 15,
                    'jointable' => array(
                        array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_TOLERANSI.KD_KABUPATEN", "LEFT")
                    ),
                    'field' => 'MASTER_TOLERANSI.*, MK.NAMA_KABUPATEN',
                    "custom" => "MASTER_TOLERANSI.KD_DEALER='ALL'",
                    'orderby' => 'MASTER_TOLERANSI.ID DESC'
                        // 'orderby' => 'ROW_NUMBER() OVER(PARTITION BY KD_DEALER,KD_TIPEMOTOR,TAHUN ORDER BY ID DESC)'
                );
            }*/
        }


        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/toleransi", $param));

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('master/master_toleransi', $data);
    }

    public function add_toleransi() {
        $this->auth->validate_authen('motor/toleransi');
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/master_comingcustomer"));
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
 
        $this->load->view('form_tambah/add_toleransi', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }


    /**
     * [kabupaten description]
     * @return [type] [description]
     */
    public function kabupaten() {
        $param = array(
            'kd_propinsi' => $this->input->post('kd')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
        echo "<option value='0'>--Pilih Kabupaten--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_KABUPATEN . "'>" . $value->NAMA_KABUPATEN . "</option>";
                }
            }
        }
    }

    public function add_toleransi_simpan() {
        $this->form_validation->set_rules('kd_propinsi', 'Propinsi', 'required|trim');
        $this->form_validation->set_rules('kd_kabupaten', 'Kabupaten', 'required|trim');
        $this->form_validation->set_rules('toleransi', 'Toleransi (hari)', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            if($this->session->userdata('kd_dealer') != null){
                $cek = array(
                'field' => 'MASTER_DEALER.*',
                "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
                );

                $data = array();
                

                if ($this->session->userdata("status_cabang") == "T") {
                    $param = array(
                        'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                        'kd_dealer' => $this->session->userdata('kd_dealer'),
                        'kd_propinsi' => $this->input->post("kd_propinsi"),
                        'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                        'toleransi' => $this->input->post("toleransi"),
                        'row_status' => 0,
                        'created_by' => $this->session->userdata('user_id')
                    );
                }
            }else{
                $param = array(
                    'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                    'kd_dealer' => 'ALL',
                    'kd_propinsi' => $this->input->post("kd_propinsi"),
                    'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                    'toleransi' => $this->input->post("toleransi"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );
            }
            
            $hasil = $this->curl->simple_post(API_URL . "/api/master/toleransi", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('motor/toleransi'));
        }
    }
 
    public function edit_toleransi($id, $row_status) {
        $this->auth->validate_authen('master_service/comingcustomer');
        $param = array(
            "custom" => "MASTER_TOLERANSI.ID='" . $id . "'",
            'row_status' => $row_status
        );
 
        $data = array();
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/toleransi", $param));
        //var_dump($data["list"]);exit;
        $this->load->view('form_edit/ubah_toleransi', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_toleransi($id) {
        $this->form_validation->set_rules('kd_propinsi', 'Propinsi', 'required|trim');
        $this->form_validation->set_rules('kd_kabupaten', 'Kabupaten', 'required|trim');
        $this->form_validation->set_rules('toleransi', 'Toleransi (hari)', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'toleransi' => $this->input->post("toleransi"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );

            //var_dump($param);exit;
            $hasil = $this->curl->simple_put(API_URL . "/api/master/toleransi", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_toleransi($id) {
        $data = array();
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/toleransi", $param));
 
 
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('motor/toleransi')
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
 
    public function toleransi_typeahead() {
        $param = array(
                'jointable' => array(
                    array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_TOLERANSI.KD_KABUPATEN", "LEFT")
                ),
                'field' => 'MASTER_TOLERANSI.*, MK.NAMA_KABUPATEN'
            );


        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/master/toleransi", $param));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_KABUPATEN;
            $data_message[1][$key] = $message->NAMA_KABUPATEN;
        }
 
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
 
        $this->output->set_output(json_encode($result));
    }







}
