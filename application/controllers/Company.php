<?php 
//ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

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

        if ($this->session->userdata('kd_div') == null) {
            redirect("auth");
        }
    }

    public function pagination($config) {
        $config['per_page'] = $config['per_page'];
        $config['base_url'] = $config['base_url'];
        $config['total_rows'] = $config['total_rows'];
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
     * [desa description]
     * @return [type] [description]
     */
    public function desa($onlydata = null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );
        if ($onlydata == true) {
            $param = array();
            if ($this->input->get('kd_desa')) {
                $param["kd_desa"] = $this->input->get("kd_desa");
            }
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $param));
        if ($onlydata == true) {
            if ($data["list"]) {
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
            //var_dump($data);
            $this->template->site('master/master_desa', $data);
        }
    }

    public function add_desa() {
        //ob_clean();
        $data = array();
        $param = array();
        $dealer = array();
        $url = "";
        $js = array();
        $param["link"] = "list15";
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);

        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));
        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $dealer[] = array(
                'kdKel' => $js[$i]['kdKel'],
                'kdkota' => $js[$i]['kdkota'],
                'kdkec' => $js[$i]['kdkec'],
                'kelurahan' => $js[$i]['kelurahan'],
                'kdkelahm' => $js[$i]['kdkelahm'],
                'kelurahanahm' => $js[$i]['kelurahanahm'],
                'kecamatan' => $js[$i]['kecamatan'],
                'propinsi' => $js[$i]['propinsi'],
                'kota' => $js[$i]['kota'],
                'kodePos' => $js[$i]['kodePos'],
                'status' => $js[$i]['status']
            );
        }
        $url = API_URL . "/api/master_general/desabatch";
        $ch = curl_init($url);
        $jsonDataEncoded = (base64_encode(json_encode($dealer)));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_exec($ch);
        $paramdesa = array('query' => $this->Custom_model->check_desa($dealer));
        $dataupdate = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $paramdesa));
        /*print_r($paramdesa);
        var_dump($dataupdate);exit();*/
        $data["listmd"] = $dataupdate;
        $this->load->view('form_tambah/add_desa', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_desa() {
        $param = array();
        $dealer = array();
        $url = "";
        $js = array();
        $param["link"] = "list15";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);

        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        //$data["listmd"] = $js;
        //$js = str_replace("[","",str_replace("]","",$js));
        $js = (json_decode($js, true));
        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $dealer[] = array(
                'kdKel' => $js[$i]['kdKel'],
                'kdkota' => $js[$i]['kdkota'],
                'kdkec' => $js[$i]['kdkec'],
                'kelurahan' => $js[$i]['kelurahan'],
                'kdkelahm' => $js[$i]['kdkelahm'],
                'kelurahanahm' => $js[$i]['kelurahanahm'],
                'kecamatan' => $js[$i]['kecamatan'],
                'propinsi' => $js[$i]['propinsi'],
                'kota' => $js[$i]['kota'],
                'kodePos' => $js[$i]['kodePos'],
                'status' => $js[$i]['status']
            );
        }
        $url = API_URL . "/api/master_general/desabatch/true";
        $ch = curl_init($url);
        $jsonDataEncoded = (base64_encode(json_encode($dealer)));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        //curl_exec($ch);
        //curl_exec($ch);

        if (curl_exec($ch)) {
            $result = array(
                'status' => true,
                'message' => count($dealer) . " Data berhasil di simpan"
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

    public function edit_desa() {
        $this->auth->validate_authen('company/desa');
        ob_end_clean();
        $param = array();
        $param = array(
            'kd_desa' => $this->input->get('kd_desa')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $param));
        //var_dump($data);
        $this->load->view('form_edit/edit_desa', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function desa_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DESA;
            $data_message[1][$key] = $message->NAMA_DESA;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master divisi
    public function divisi() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => '15',
            'orderby' => 'KD_DIVISI'
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/divisi", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_divisi', $data);
    }

    public function add_divisi() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/divisi"));

        $this->load->view('form_tambah/add_divisi', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_divisi_simpan() {
        $this->form_validation->set_rules('kd_div', 'Kode Divisi', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_div', 'Nama Divisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_div' => html_escape($this->input->post("kd_div")),
                'nama_div' => html_escape($this->input->post("nama_div")),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master/divisi", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/divisi'));
        }
    }

    public function edit_divisi($kd_div, $row_status) {
        $this->auth->validate_authen('company/divisi');
        $param = array(
            'kd_div' => $kd_div,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/divisi", $param));

        $this->load->view('form_edit/edit_divisi', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_divisi($id) {
        $this->form_validation->set_rules('kd_div', 'Kode Divisi', 'required|trim');
        $this->form_validation->set_rules('nama_div', 'Nama Divisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_div' => html_escape($this->input->post("kd_div")),
                'nama_div' => html_escape($this->input->post("nama_div")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master/divisi", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_divisi($kd_div) {
        $param = array(
            'kd_div' => $kd_div,
            'lastmodified_by' => $this->session->userdata('user_name')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/divisi", $param));

        $this->data_output($data, 'delete', base_url('company/divisi'));
    }

    public function divisi_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/divisi"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DIV;
            $data_message[1][$key] = $message->NAMA_DIV;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master Finansial
    public function finansial_perusahaan() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*'
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_company_finance', $data);
    }

    public function add_finansial_perusahaan() {
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["link"] = "list39";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        //$listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));

        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $propinsi = array(
                'kd_leasing' => $js[$i]['kdleasing'],
                'nama_leasing' => trim($js[$i]['nmleasing']),
                'kd_leasingahm' => $js[$i]['kdleasingahm']
            );
            //print_r($param);
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance", $propinsi));
            /*var_dump($databaru);
            exit();*/
            if ($databaru) {
                if ($databaru->totaldata == 0) {
                    $dataupdate[$totaldata] = $js[$i];
                    $totaldata++;
                }
            } else {
                $dataupdate[$totaldata] = $js[$i];
                $totaldata++;
            }
        }
        $data["listmd"] = $dataupdate;
        /* var_dump($js);
          exit(); */

        $this->load->view('form_tambah/add_finansial_perusahaan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_finansial_perusahaan() {
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
                'kd_leasing' => $data[0][$i]["kdleasing"],
                'nama_leasing' => rtrim($data[0][$i]["nmleasing"]),
                'kd_leasingahm' => $data[0][$i]["kdleasingahm"],
                'created_by' => $this->session->userdata("user_id")
            );
            if ($i == 121) {
                //  print_r($param);
                //exit();
            }
            $hasil = ($this->curl->simple_post(API_URL . "/api/master/company_finance", $param, array(CURLOPT_BUFFERSIZE => 100)));
            if ($hasil) {
                $hasil = json_decode($hasil);
                if ($hasil->recordexists == true) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id") . "| ws_list39";
                    $hasil = ($this->curl->simple_put(API_URL . "/api/master/company_finance", $param, array(CURLOPT_BUFFERSIZE => 10)));
                }
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

    public function finansial_perusahaan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_COFIN;
            $data_message[1][$key] = $message->NAMA_COFIN;
        }


        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2]);

        $this->output->set_output(json_encode($result));
    }

    public function leasing_typeahead() {
        $data = [];
        $keywords = $this->input->get('keyword');

        $param = array(
            'keyword' => $keywords
        );
        $data_message = [];
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance", $param));

        // var_dump($data); exit;

        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $message) {
                    $data_message['keyword'][$key] = $message->KD_LEASING; /* . " - " . $message->NAMA_LEASING . " - " . $message->KD_LEASINGAHM; */
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

    //Master Jabatan
    public function jabatan() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*,MASTER_JABATAN.ID as ID',
            //'custom'=>'USERS_GROUP.ROW_STATUS>-1',
            'orderby' => 'MASTER_JABATAN.ID desc'
        );


        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jabatan", $param));


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

        $this->template->site('master/master_jabatan', $data);
    }

    public function add_jabatan() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jabatan"));
        /* var_dump($data);
          print_r($data);
          exit; */

        $this->load->view('form_tambah/add_jabatan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_jabatan_simpan() {

        $this->form_validation->set_rules('kd_jabatan', 'Kode Jabatan', 'required|trim');
        $this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jabatan' => html_escape($this->input->post("kd_jabatan")),
                'nama_jabatan' => html_escape($this->input->post("nama_jabatan")),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/jabatan", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/jabatan'));
        }
    }

    public function edit_jabatan($kd_jabatan, $row_status) {
        $this->auth->validate_authen('company/jabatan');

        $param = array(
            'kd_jabatan' => $kd_jabatan,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jabatan", $param));

        /* var_dump($data);
          exit; */

        $this->load->view('form_edit/edit_jabatan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_jabatan($id) {
        $this->form_validation->set_rules('kd_jabatan', 'Kode Jabatan', 'required|trim');
        $this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'required|trim');
        $this->form_validation->set_rules('row_status', 'Status', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'kd_jabatan' => html_escape($this->input->post("kd_jabatan")),
                'nama_jabatan' => html_escape($this->input->post("nama_jabatan")),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );

            /* print_r($hasil);
              exit; */
            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/jabatan", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_jabatan($kd_jabatan) {
        $param = array(
            'kd_jabatan' => $kd_jabatan,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/jabatan", $param));

        $this->data_output($data, 'delete', base_url('company/jabatan'));
    }

    //Master Karyawan
    public function karyawan($debug=null,$usedfor=null) {
        $dealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer") ;
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_KARYAWAN AS A","MASTER_KARYAWAN.ATASAN_LANGSUNG=A.NIK","LEFT")
            ),
            'field' => 'MASTER_KARYAWAN.*, A.NAMA AS NAMA_ATASAN',
            "custom" => "MASTER_KARYAWAN.KD_CABANG='" . $dealer. "'",
        );
        
        $data = array();
        if($debug==true){
            unset($param["offset"]);
            unset($param["limit"]);
            $param["orderby"] ="MASTER_KARYAWAN.NAMA";
            $param["custom"] .= " AND MASTER_KARYAWAN.KD_STATUS NOT IN('STS-4')";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));

        if($debug==true){
            if($data["list"]){
                if($usedfor){
                    echo json_encode($data["list"]);
                }else{
                    if($data["list"]->totaldata >0){
                        echo json_encode($data["list"]->message);
                    }
                }
            }
            exit();
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

        $this->template->site('master/master_karyawan', $data);
    }

    public function add_karyawan() {
        ini_set('max_execution_time', 120);
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["param"] = $this->session->userdata("kd_dealer");
        $param["link"] = "list9a";
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));

        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));

        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $karyawan = array(
                'nik' => $js[$i]['nik'],
                'nama' => str_replace("'", "", rtrim($js[$i]['nama'])),
                'kd_status' => $js[$i]['kdstatus'],
                'kd_perusahaan' => ($js[$i]['kdperusahaan']=='TM')?'T10':$js[$i]['kdperusahaan'],
                'kd_cabang' => $js[$i]['kdcabang'],
                'kd_divisi' => $js[$i]['kddivisi'],
                'kd_jabatan' => $js[$i]['kdjabatan'],
                'personal_jabatan' => trim($js[$i]['personaljabatan']),
                'personal_level' => $js[$i]['personallevel'],
                'atasan_langsung' => $js[$i]['atasanlangsung'],
                'tgl_lahir' => $js[$i]['tgllahir'],
                'tgl_masuk' => $js[$i]['tglmasuk'],
                'pendidikan' => $js[$i]['kdstudi']
            );
            //print_r($karyawan);
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $karyawan));
            if ($databaru) {
                if ($databaru->totaldata == 0) {
                    $dataupdate[$totaldata] = $js[$i];
                    $totaldata++;
                }
            } else {
                $dataupdate[$totaldata] = $js[$i];
                $totaldata++;
            }
            //var_dump($dataupdate);
            if($totaldata==50){
                break;
            }
            
        }
        $data["listmd"] = $dataupdate;

        $this->load->view('form_tambah/add_karyawan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function tambah_karyawan() {
        $this->auth->validate_authen('company/karyawan');
        $kd_dealer = $this->session->userdata("kd_dealer");

        $param = array('kd_dealer' => $this->session->userdata("kd_dealer"));

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));

        $paramstatus = array(
            'field' => "KD_STATUS",
            'groupby' => TRUE,
            'orderby' => 'KD_STATUS ASC'
        );
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramstatus));

        $paramdivisi = array(
            'field' => "KD_DIVISI",
            'groupby' => TRUE,
            'orderby' => 'KD_DIVISI ASC'
        );
        $data["divisi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramdivisi));

        $paramjabatan = array(
            'field' => "KD_JABATAN",
            'groupby' => TRUE,
            'orderby' => 'KD_JABATAN ASC'
        );
        $data["jabatan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramjabatan));

        $parampersonal_jabatan = array(
            'field' => "PERSONAL_JABATAN",
            'groupby' => TRUE,
            'orderby' => 'PERSONAL_JABATAN ASC'
        );
        $data["personal_jabatan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $parampersonal_jabatan));

        $parampersonal_level = array(
            'field' => "PERSONAL_LEVEL",
            'groupby' => TRUE,
            'orderby' => 'PERSONAL_LEVEL ASC'
        );
        $data["personal_level"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $parampersonal_level));

        $paramatasan = array(
            'custom' => "KD_CABANG = '" . $kd_dealer . "'",
            'field' => "NIK,NAMA, KD_CABANG ",
        );
        $data["atasan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramatasan));

        $paramstudi = array(
            'field' => "PENDIDIKAN",
            'groupby' => TRUE,
            'orderby' => 'PENDIDIKAN ASC'
        );
        $data["studi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramstudi));

        $this->load->view('form_tambah/tambah_karyawan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_karyawan_simpan() {
        $this->form_validation->set_rules('nama', 'nama', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'nik' => $this->getNik(),
                'nama' => $this->input->post("nama"),
                'kd_status' => $this->input->post("kd_status"),
                'kd_perusahaan' => $this->session->userdata('kd_dealer'),
                'kd_cabang' => $this->session->userdata('kd_dealer'),
                'kd_divisi' => $this->input->post("kd_divisi"),
                'kd_jabatan' => $this->input->post("kd_jabatan"),
                'personal_jabatan' => $this->input->post("personal_jabatan"),
                'personal_level' => $this->input->post("personal_level"),
                'atasan_langsung' => $this->input->post("atasan_langsung"),
                'tgl_lahir' => $this->input->post("tgl_lahir"),
                'tgl_masuk' => $this->input->post("tgl_masuk"),
                'pendidikan' => $this->input->post("pendidikan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/karyawan_dealer", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('company/karyawan'));
        }
    }

    public function getNik() {
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? -2 : $this->input->get('row_status')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $number = str_pad(($data->totaldata) + 1, 3, '0', STR_PAD_LEFT);
        return str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT) . '-' . $number;
    }

    public function edit_karyawan($nik, $row_status) {
        $this->auth->validate_authen('company/karyawan');
        $param = array(
            'nik' => $nik,
            'row_status' => $row_status
        );
        $kd_dealer = $this->session->userdata("kd_dealer");
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));

        $paramstatus = array(
            'field' => "KD_STATUS",
            'groupby' => TRUE,
            'orderby' => 'KD_STATUS ASC'
        );
        $data["status"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramstatus));

        $paramdivisi = array(
            'field' => "KD_DIVISI",
            'groupby' => TRUE,
            'orderby' => 'KD_DIVISI ASC'
        );
        $data["divisi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramdivisi));

        $paramjabatan = array(
            'field' => "KD_JABATAN",
            'groupby' => TRUE,
            'orderby' => 'KD_JABATAN ASC'
        );
        $data["jabatan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramjabatan));

        $parampersonal_jabatan = array(
            'field' => "PERSONAL_JABATAN",
            'groupby' => TRUE,
            'orderby' => 'PERSONAL_JABATAN ASC'
        );
        $data["personal_jabatan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $parampersonal_jabatan));

        $parampersonal_level = array(
            'field' => "PERSONAL_LEVEL",
            'groupby' => TRUE,
            'orderby' => 'PERSONAL_LEVEL ASC'
        );
        $data["personal_level"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $parampersonal_level));



        $paramatasan = array(
            'custom' => "MASTER_KARYAWAN.KD_CABANG = '" . $kd_dealer . "'",
            'jointable' => array(
                array("MASTER_KARYAWAN AS A","MASTER_KARYAWAN.ATASAN_LANGSUNG=A.NIK","LEFT")
            ),
            'field' => "MASTER_KARYAWAN.NIK,MASTER_KARYAWAN.NAMA,MASTER_KARYAWAN.ATASAN_LANGSUNG, A.NAMA AS NAMA_ATASAN",
            'orderby' => "MASTER_KARYAWAN.ATASAN_LANGSUNG ASC"
//            'field' => "NIK,NAMA,ATASAN_LANGSUNG",
        );
        $data["atasan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramatasan));

        $paramstudi = array(
            'field' => "PENDIDIKAN",
            'groupby' => TRUE,
            'orderby' => 'PENDIDIKAN ASC'
        );
        $data["studi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramstudi));

//        var_dump($data["atasan"]); exit();

        $this->load->view('form_edit/ubah_karyawan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function ubah_karyawan($id) {
        $this->form_validation->set_rules('nik', 'Nik', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'nik' => html_escape($this->input->post("nik")),
                'nama' => html_escape($this->input->post("nama")),
                'kd_status' => html_escape($this->input->post("kd_status")),
                'kd_perusahaan' => html_escape($this->input->post('kd_dealer')),
                'kd_cabang' => html_escape($this->input->post("kd_dealer")),
                'kd_divisi' => $this->input->post("kd_divisi"),
                'kd_jabatan' => $this->input->post("kd_jabatan"),
                'personal_jabatan' => $this->input->post("personal_jabatan"),
                'personal_level' => $this->input->post("personal_level"),
                'atasan_langsung' => $this->input->post("atasan_langsung"),
                'tgl_lahir' => $this->input->post("tgl_lahir"),
                'tgl_masuk' => $this->input->post("tgl_masuk"),
                'pendidikan' => $this->input->post("pendidikan")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/karyawan_dealer", $param, array(CURLOPT_BUFFERSIZE => 10));

//            var_dump($hasil);            exit();
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function update_karyawan() {
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
                'nik' => $data[0][$i]["nik"],
                'nama' => strtoupper(str_replace("'", "", (rtrim($data[0][$i]["nama"])))),
                'kd_status' => rtrim($data[0][$i]["kdstatus"]),
                'kd_perusahaan' => $data[0][$i]["kdperusahaan"],
                'kd_cabang' => rtrim($data[0][$i]["kdcabang"]),
                'kd_divisi' => $data[0][$i]["kddivisi"],
                'kd_jabatan' => $data[0][$i]["kdjabatan"],
                'personal_jabatan' => $data[0][$i]["personaljabatan"],
                'personal_level' => $data[0][$i]["personallevel"],
                'atasan_langsung' => $data[0][$i]["atasanlangsung"],
                'tgl_lahir' => $data[0][$i]["tgllahir"],
                'tgl_masuk' => $data[0][$i]["tglmasuk"],
                'pendidikan' => $data[0][$i]["kdstudi"],
                'password' => $data[0][$i]["password"],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = ($this->curl->simple_post(API_URL . "/api/master_general/karyawan", $param, array(CURLOPT_BUFFERSIZE => 100)));
            /* var_dump($hasil);
              exit(); */

            if ($hasil) {
                $hasil = json_decode($hasil);
                if ($hasil->recordexists == true) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id") . "| ws_list9";
                    $hasil = ($this->curl->simple_put(API_URL . "/api/master_general/karyawan", $param, array(CURLOPT_BUFFERSIZE => 10)));
                }
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

    public function delete_karyawan($nik) {
        $param = array(
            'nik' => $nik,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/karyawan", $param));

        $this->data_output($data, 'delete', base_url('company/karyawan'));
    }

    public function karyawan_typeahead() {
        $param = array(
            "custom" => "MASTER_KARYAWAN.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NIK;
            $data_message[1][$key] = $message->NAMA;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }
    public function k2sales($kd=null,$debug=null){
        $data=array();$nama=array();
        $param = array(
            'field' => "NIK,NAMA,KD_JABATAN,PERSONAL_JABATAN,KD_CABANG",
            'groupby' => TRUE,
            //'custom' => "NAMA LIKE '%".$nama[0]."%'",
            'custom'    => "MASTER_KARYAWAN.NIK NOT IN(SELECT DISTINCT (ISNULL(NIK,'')) FROM MASTER_SALESMAN MS WHERE MS.KD_DEALER=MASTER_KARYAWAN.KD_CABANG)",
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'orderby'   => "NAMA,NIK"
        );
        if($kd){
            unset($param["custom"]);
            $param["custom"] = "(MASTER_KARYAWAN.NIK NOT IN(SELECT DISTINCT (ISNULL(NIK,'')) FROM MASTER_SALESMAN MS WHERE MS.KD_DEALER=MASTER_KARYAWAN.KD_CABANG) OR MASTER_KARYAWAN.NIK='".$kd."') AND MASTER_KARYAWAN.PERSONAL_JABATAN LIKE '%sales%' ";
        }
        $data= json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));        
        if(isset($data)){
            if($data->totaldata>0){
                echo json_encode($data->message);
            }
        }
        if($debug){
            var_dump($param);
            var_dump($data);
        }
        
    }
    
    public function salesman_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DEALER;
            $data_message[1][$key] = $message->NAMA_SALES;
            $data_message[2][$key] = $message->KD_SALES;
            $data_message[3][$key] = $message->KD_HSALES;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2], $data_message[3]);

        $this->output->set_output(json_encode($result));
    }

    //Master Kabupaten
    //Master Kabupaten
    public function kabupaten($dataOnly = null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => ARRAY(ARRAY("MASTER_PROPINSI", "MASTER_PROPINSI.KD_PROPINSI=MASTER_KABUPATEN.KD_PROPINSI", "LEFT")),
            'field' => "MASTER_KABUPATEN.*,MASTER_PROPINSI.NAMA_PROPINSI",
            'orderby' => "MASTER_KABUPATEN.KD_PROPINSI,MASTER_KABUPATEN.NAMA_KABUPATEN"
        );
        if ($dataOnly == "1") {
            $param = array(
                'jointable' => ARRAY(ARRAY("MASTER_PROPINSI", "MASTER_PROPINSI.KD_PROPINSI=MASTER_KABUPATEN.KD_PROPINSI", "LEFT")),
                'field' => "MASTER_KABUPATEN.KD_KABUPATEN,NAMA_KABUPATEN,MASTER_KABUPATEN.KD_PROPINSI,MASTER_PROPINSI.NAMA_PROPINSI",
                'orderby' => "MASTER_KABUPATEN.KD_PROPINSI,MASTER_KABUPATEN.NAMA_KABUPATEN"
            );
        }
        if ($this->input->get("p")) {
            $param["custom"] = "MASTER_KABUPATEN.KD_PROPINSI IN(SELECT KD_PROPINSI FROM MASTER_DEALER WHERE KD_DEALER='" . $this->session->userdata("kd_dealer") . "')";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
//        var_dump($this->curl->simple_get(API_URL."/api/master_general/desa",$param));
//        exit();

        if ($dataOnly == "1") {
            if ($data["list"]) {
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
            $this->template->site('master/master_kabupaten', $data);
        }
    }

    public function add_kabupaten() {
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["link"] = "list13";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        //$listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));

        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $propinsi = array(
                'kd_kabupaten' => $js[$i]['kode'],
                'nama_kabupaten' => trim($js[$i]['kabupatenkota']),
                    //'kd_negara' => 62
            );
            //print_r($param);
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $propinsi));
            //var_dump($databaru);
            //exit();
            if ($databaru) {
                if ($databaru->totaldata == 0) {
                    $dataupdate[$totaldata] = $js[$i];
                    $totaldata++;
                }
            } else {
                $dataupdate[$totaldata] = $js[$i];
                $totaldata++;
            }
        }
        $data["listmd"] = $dataupdate;
        /* var_dump($js);
          exit(); */

        $this->load->view('form_tambah/add_kabupaten', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_kabupaten() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));

        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        /* print_r($data[0]);
          exit(); */
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {

            $propinsi = substr($data[0][$i]["kode"], 0, 2) . "00";
            if ($propinsi !== $data[0][$i]["kode"]) {
                $param = array(
                    'kd_propinsi' => $propinsi,
                    'kd_kabupaten' => $data[0][$i]["kode"],
                    'nama_kabupaten' => $data[0][$i]["kabupatenkota"],
                    'created_by' => $this->session->userdata("user_id") . "| ws_list13"
                );

                $hasil = ($this->curl->simple_post(API_URL . "/api/master_general/kabupaten", $param, array(CURLOPT_BUFFERSIZE => 10)));
                if ($hasil) {
                    $hasil = json_decode($hasil);
                    if ($hasil->recordexists == true) {
                        $param["lastmodified_by"] = $this->session->userdata("user_id") . "| ws_list13";
                        $hasil = ($this->curl->simple_put(API_URL . "/api/master_general/kabupaten", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    }
                }
            }
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal disimpan',
                'status' => false
            );

            $this->output->set_output(json_encode($data));
        }
    }

    public function edit_kabupaten() {
        $this->auth->validate_authen('company/kabupaten');
        ob_end_clean();
        $param = array();
        $param = array(
            'kd_kabupaten' => $this->input->get('kd_kabupaten')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
        //var_dump($data);
        $this->load->view('form_edit/edit_kabupaten', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function kabupaten_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_KABUPATEN;
            $data_message[1][$key] = $message->KD_KABUPATEN;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master Kecamatan
    public function kecamatan() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan", $param));
//        var_dump($this->curl->simple_get(API_URL."/api/master_general/kecamatan",$param));
               
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
        $this->template->site('master/master_kecamatan', $data);
    }

    public function add_kecamatan() {
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["link"] = "list14";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        //$listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));

        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $propinsi = array(
                'kd_kecamatan' => $js[$i]['kdkec'],
                'nama_kecamatan' => trim($js[$i]['kecamatan']),
                    //'kd_negara' => 62
            );
            //print_r($param);
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan", $propinsi));
            //var_dump($databaru);
            //exit();
            if ($databaru) {
                if ($databaru->totaldata == 0) {
                    $dataupdate[$totaldata] = $js[$i];
                    $totaldata++;
                }
            } else {
                $dataupdate[$totaldata] = $js[$i];
                $totaldata++;
            }
        }
        $data["listmd"] = $dataupdate;
        /* var_dump($js);
          exit(); */

        $this->load->view('form_tambah/add_kecamatan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_kecamatan() {
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
                'kd_kabupaten' => $data[0][$i]["kdkota"],
                'kd_kecamatan' => $data[0][$i]["kdkec"],
                'nama_kecamatan' => $data[0][$i]["kecamatan"],
                'created_by' => $this->session->userdata("user_id") . "| ws_list14"
            );
            /* $paramsales=array(
              'query' => $this->Custom_model->simpan_kecamantan(json_encode($param))
              ); */
//            print_r($param);
            $hasil = ($this->curl->simple_post(API_URL . "/api/master_general/kecamatan", $param, array(CURLOPT_BUFFERSIZE => 100)));
            if ($hasil) {
                $hasil = json_decode($hasil);
                if ($hasil->recordexists == true) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id") . "| ws_list14";
                    $hasil = ($this->curl->simple_put(API_URL . "/api/master_general/kecamatan", $param, array(CURLOPT_BUFFERSIZE => 100)));
                }
            }
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

    public function edit_kecamatan() {
        $this->auth->validate_authen('company/kecamatan');
        ob_end_clean();
        $param = array();
        $param = array(
            'kd_kecamatan' => $this->input->get('kd_kecamatan')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan", $param));
        //var_dump($data);
        $this->load->view('form_edit/edit_kecamatan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function kecamatan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_KECAMATAN;
            $data_message[1][$key] = $message->NAMA_KECAMATAN;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master Pekerjaan
    public function pekerjaan() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_PEKERJAAN.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan", $param));
               
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
        $this->template->site('master/master_pekerjaan', $data);
    }

    public function add_pekerjaan() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));
        $this->load->view('form_tambah/add_pekerjaan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    function add_pekerjaan_simpan() {
        $this->form_validation->set_rules('kd_pekerjaan', 'Kode Pekerjaan', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_pekerjaan', 'Nama Pekerjaan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_pekerjaan' => $this->input->post("kd_pekerjaan"),
                'nama_pekerjaan' => $this->input->post("nama_pekerjaan"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/pekerjaan", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/pekerjaan'));
        }
    }

    public function edit_pekerjaan($kd_pekerjaan, $row_status) {
        $this->auth->validate_authen('company/pekerjaan');
        $param = array(
            'kd_pekerjaan' => $kd_pekerjaan,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan", $param));

        $this->load->view('form_edit/edit_pekerjaan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_pekerjaan($id) {
        $this->form_validation->set_rules('kd_pekerjaan', 'Kode Pekerjaan', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_pekerjaan', 'Nama Pekerjaan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_pekerjaan' => html_escape($this->input->post("kd_pekerjaan")),
                'nama_pekerjaan' => html_escape($this->input->post("nama_pekerjaan")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/pekerjaan", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_pekerjaan($kd_pekerjaan) {
        $param = array(
            'kd_pekerjaan' => $kd_pekerjaan,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/pekerjaan", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('company/pekerjaan')
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

    public function pekerjaan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_PEKERJAAN;
            $data_message[1][$key] = $message->NAMA_PEKERJAAN;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master Perusahaan
    public function perusahaan() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'pimpinan_dealer' => $this->input->get('pimpinan_dealer'),
            'kepala_gudang' => $this->input->post('kepala_gudang'),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=MASTER_COMPANY.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN MK", "MK.NIK=MASTER_COMPANY.PIMPINAN_DEALER", "LEFT"),
                array("MASTER_KARYAWAN MP", "MP.NIK=MASTER_COMPANY.KEPALA_GUDANG", "LEFT")
            ),
            'field' => '*,MASTER_COMPANY.ID as ID, MASTER_COMPANY.ROW_STATUS, MK.NAMA AS PIMPINAN_DEALER2, MP.NAMA AS KEPALA_GUDANG2',
            'orderby' => 'MASTER_COMPANY.ID desc',
            "custom" => "MASTER_COMPANY.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('master/master_company', $data);
    }

    /**
     * [add_customer description]
     */
    public function add_perusahaan() {
        $data = array();
        $param = array(
            'row_status' => 0
        );

        $param_kar = array(
            "custom" => "MASTER_KARYAWAN.KD_CABANG='" . $this->session->userdata('kd_dealer') . "'"
        );
//        $paramcustomer["custom"] = "";
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param_kar));
        //var_dump($data["karyawan"]);exit;
        //$data["karyawanz"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan"));
        $this->load->view('form_tambah/add_perusahaan', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    /**
     * [add_customer_simpan description]
     */
    public function add_perusahaan_simpan() {
        $this->form_validation->set_rules('nama_company', 'Nama Perusahaan', 'required|trim');
        $this->form_validation->set_rules('kd_company', 'Kode Perusahaan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_company' => $this->input->post("kd_company"),
                'nama_company' => $this->input->post("nama_company"),
                'pimpinan_dealer' => trim($this->input->post("nik")),
                'kepala_gudang' => trim($this->input->post("nama")),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master/company", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/perusahaan'));
        }
    }

    public function edit_company($kd_company, $row_status) {
        $this->auth->validate_authen('company/perusahaan');
        $param = array(
            'kd_company' => $kd_company,
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $param));
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["karyawans"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan"));
        $this->load->view('form_edit/edit_perusahaan', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function update_company($id) {
        $this->form_validation->set_rules('kd_company', 'Kode Perusahaan', 'required|trim');
        $this->form_validation->set_rules('nama_company', 'Nama Perusahaan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_company' => $this->input->post("kd_company"),
                'nama_company' => $this->input->post("nama_company"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'pimpinan_dealer' => $this->input->post("nik"),
                'kepala_gudang' => $this->input->post("nama"),
                'row_status' => html_escape($this->input->post("row_status")),
                'modified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/company", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function perusahaandetail() {
        $param = array(
            "nama_company" => $this->input->post("nama_company")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $param));
        $result = array();
        if ($data) {
            if (is_array($data->message)) {
                $result = json_encode($data->message);
            } else {
                $result = json_encode($data);
            }
        }
        echo $result;
    }

    public function delete_perusahaan($kd_company) {
        $param = array(
            'kd_company' => $kd_company,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/company", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('company/perusahaan')
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
    public function company_typeahead() {

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company"));
        if ($data) {
            if (is_array($data["list"]->message)) {

                foreach ($data["list"]->message as $key => $message) {
                    $data_message[0][$key] = $message->KD_COMPANY;
                    $data_message[0][$key] = $message->NAMA_COMPANY;
                }
                $result['keyword'] = $data_message[0]; // array_merge($data_message[0], $data_message[1]);

                $this->output->set_output(json_encode($result));
            }
        }
    }

    public function provinsi() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi", $param));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_provinsi', $data);
    }

    /**
     * [add_provinsi description]
     */
    public function add_provinsi() {
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["link"] = "list12";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        //$listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));

        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $propinsi = array(
                'kd_propinsi' => $js[$i]['kode'],
                'nama_propinsi' => trim($js[$i]['provinsi']),
                    //'kd_negara' => 62
            );
            //print_r($param);
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi", $propinsi));
            //var_dump($databaru);
            //exit();
            if ($databaru) {
                if ($databaru->totaldata == 0) {
                    $dataupdate[$totaldata] = $js[$i];
                    $totaldata++;
                }
            } else {
                $dataupdate[$totaldata] = $js[$i];
                $totaldata++;
            }
        }
        $data["listmd"] = $dataupdate;
        /* var_dump($js);
          exit(); */

        $this->load->view('form_tambah/add_provinsi', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    /**
     * [update_propinsi description]
     * @return [type] [description]
     */
    public function update_propinsi() {
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
                'kd_propinsi' => $data[0][$i]["kode"],
                'nama_propinsi' => strtoupper(str_replace("'", "", (rtrim($data[0][$i]["provinsi"])))),
                'kd_negara' => 62,
                'created_by' => $this->session->userdata("user_id") . "| ws_list12"
                    //'created_by' => $this->session->userdata("user_id")
            );

            $hasil = ($this->curl->simple_post(API_URL . "/api/master_general/propinsi", $param, array(CURLOPT_BUFFERSIZE => 10)));
            if ($hasil) {
                $hasil = json_decode($hasil);
                if ($hasil->recordexists == true) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id") . "| ws_list12";
                    $hasil = ($this->curl->simple_put(API_URL . "/api/master_general/propinsi", $param, array(CURLOPT_BUFFERSIZE => 10)));
                }
            }
        }
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => count($data[0]) . " data berhasil di simpan"
            );
            $this->output->set_output(json_encode($result));
        } else {
            $data = array(
                'message' => 'Data gagal disimpan',
                'status' => false
            );

            $this->output->set_output(json_encode($data));
        }
    }

    public function edit_provinsi() {
        $this->auth->validate_authen('company/provinsi');
        ob_end_clean();
        $param = array();
        $param = array(
            'kd_propinsi' => $this->input->get('kd_propinsi')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi", $param));
        //var_dump($data); q
        $this->load->view('form_edit/edit_provinsi', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function provinsi_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_PROPINSI;
            $data_message[1][$key] = $message->NAMA_PROPINSI;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master mobil
    public function mobil() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => '15',
            'orderby' => 'MASTER_MOBIL.ID desc',
            "custom" => "MASTER_MOBIL.KD_DEALER='".$this->session->userdata("kd_dealer")."'"
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/mobil", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_mobil', $data);
    }

    public function add_mobil() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/mobil"));

        $this->load->view('form_tambah/add_mobil', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_mobil_simpan() {
        $this->form_validation->set_rules('no_polisi', 'Nomor Polisi', 'required|trim');
        //$this->form_validation->set_rules('nama_div', 'Nama Divisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'no_polisi' => html_escape($this->input->post("no_polisi")),
                'merek' => html_escape($this->input->post("merek")),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/mobil", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/mobil'));
        }
    }

    public function edit_mobil($id, $row_status) {
        $this->auth->validate_authen('company/mobil');
        $param = array(
            "custom" => "ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/mobil", $param));

        $this->load->view('form_edit/edit_mobil', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_mobil($id) {
        $this->form_validation->set_rules('no_polisi', 'Nomor Polisi', 'required|trim');
        //$this->form_validation->set_rules('nama_div', 'Nama Divisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => html_escape($this->input->post("id")),
                'no_polisi' => html_escape($this->input->post("no_polisi")),
                'merek' => html_escape($this->input->post("merek")),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/mobil", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_mobil($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_name')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/mobil", $param));

        $this->data_output($data, 'delete', base_url('company/mobil'));
    }

    public function mobil_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/mobil"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_POLISI;
            $data_message[1][$key] = $message->MEREK;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Master supir
    public function supir() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => '15',
            'orderby' => 'MASTER_SUPIR.ID desc', 
            "custom" => "MASTER_SUPIR.KD_DEALER='".$this->session->userdata("kd_dealer")."'"
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/supir", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('master/master_supir', $data);
    }

    public function add_supir() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/supir"));

        $this->load->view('form_tambah/add_supir', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function add_supir_simpan() {
        $this->form_validation->set_rules('nama_supir', 'Nama Supir', 'required|trim');
        //$this->form_validation->set_rules('nama_div', 'Nama Divisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'nama_supir' => html_escape($this->input->post("nama_supir")),
                'no_hp' => html_escape($this->input->post("no_hp")),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/supir", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/supir'));
        }
    }

    public function edit_supir($id, $row_status) {
        $this->auth->validate_authen('company/supir');
        $param = array(
            "custom" => "ID='".$id."'",
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/supir", $param));

        $this->load->view('form_edit/edit_supir', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_supir($id) {
        $this->form_validation->set_rules('nama_supir', 'Nama Supir', 'required|trim');
        //$this->form_validation->set_rules('nama_div', 'Nama Divisi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => html_escape($this->input->post("id")),
                'nama_supir' => html_escape($this->input->post("nama_supir")),
                'no_hp' => html_escape($this->input->post("no_hp")),
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_name')
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/supir", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_supir($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_name')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/supir", $param));

        $this->data_output($data, 'delete', base_url('company/supir'));
    }

    public function supir_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/supir"));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_SUPIR;
            $data_message[1][$key] = $message->NO_HP;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    //Kepala Sales
    public function ks() {
        $data = array();/*
        $dealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer');*/
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "MK.NIK=SETUP_KEPALASALES.NIK", "LEFT"),
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=SETUP_KEPALASALES.KD_DEALER", "LEFT"),
                array("MASTER_LOKASIDEALER AS ML", "ML.KD_LOKASI=SETUP_KEPALASALES.KD_LOKASI AND ML.KD_DEALER=SETUP_KEPALASALES.KD_DEALER", "LEFT")
            ),
            'field' => 'SETUP_KEPALASALES.ID, SETUP_KEPALASALES.NIK, SETUP_KEPALASALES.TGL_AWAL, SETUP_KEPALASALES.TGL_AKHIR, SETUP_KEPALASALES.STATUS_AKTIF, SETUP_KEPALASALES.ROW_STATUS, MK.NAMA, MK.PERSONAL_JABATAN, MD.NAMA_DEALER, ML.NAMA_LOKASI',
            'orderby' => 'SETUP_KEPALASALES.ID desc',
            'groupby' => TRUE

        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales", $param));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('sales/kepala_sales/list_ks', $data);
    }

    public function add_ks($id=null) {
        $data = array();
 
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'groupby' => TRUE
            );
        } else {
            $param = array('groupby' => TRUE);
        }


 
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$param));

        $paramj = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom'=> "PERSONAL_JABATAN !='Kepala Sales'" 
         );


        $data["karyawan"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$paramj));
        //$data["jabatan"]   = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jabatan"));
        if($id){
            $param=array(
                'id'   => $id
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales", $param));
        }
        $this->load->view('sales/kepala_sales/add_ks', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function ks_simpan() {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {


            /*if ($this->input->post("nik_bawahan") != null) {
                $nik_bawahan = implode(", ", $this->input->post("nik_bawahan"));
            } else {
                $nik_bawahan = "";
            }*/

            if ($this->input->post("status_aktif") != 0 ) {
                $tgl_akhir = date('d/m/Y');
            } else {
                $tgl_akhir = $this->input->post('tgl_akhir');
            }

            $param = array(
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'nik' => strtoupper($this->input->post('nik')),
                'nama_karyawan' => null,
                'kd_jabatan' => null,
                'tgl_awal' => $this->input->post('tgl_awal'),
                'tgl_akhir' => $tgl_akhir,
                'status_aktif' => $this->input->post('status_aktif'),
                'kd_lokasi' => $this->input->post('kd_lokasi'),
                'nik_bawahan' => null,
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            //var_dump($param);exit();
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/kepalasales", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($hasil);exit();
            $data = json_decode($hasil);
            if($data->recordexists==true){
                $param["id"]= $this->input->post('id');
                $param["lastmodified_by"]=$this->session->userdata("user_id");
                $hasil = $this->curl->simple_put(API_URL . "/api/setup/kepalasales", $param, array(CURLOPT_BUFFERSIZE => 10));
                $data = json_decode($hasil);
                //print_r($param);
                //var_dump($hasil);exit();
            }
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('company/ks'));
        }
    }
 
    public function edit_ks($id, $row_status) {
        $this->auth->validate_authen('company/ks');
        $param = array(
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=SETUP_KEPALASALES.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN AS MK", "MK.NIK=SETUP_KEPALASALES.NIK", "LEFT")
            ),
            'field' => 'SETUP_KEPALASALES.*, MD.NAMA_DEALER,MK.NAMA',
            "custom" => "SETUP_KEPALASALES.ID='".$id."'",
            'row_status' => $row_status
        );
        $paramj = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom'=> "PERSONAL_JABATAN !='Kepala Sales'" 
         );

        if ($this->session->userdata("nama_group") != "Root") {
            $paraml = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $paraml = array();
        }
 
        $data = array();
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$paraml));
        $data["karyawan"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$paramj));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales", $param));
        
        $this->load->view('sales/kepala_sales/edit_ks', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_ks($id) {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validate_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            //$nik_bawahan = implode(', ', $this->input->post("nik_bawahan"));
            if ($this->input->post("status_aktif") != 0 ) {
                $tgl_akhir = date('d/m/Y');
            } else {
                $tgl_akhir = $this->input->post('tgl_akhir');
            }
            $param = array(
                'id' => $this->input->post('id'),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'nik' => strtoupper($this->input->post('nik')),
                //'nama_karyawan' => strtoupper($this->input->post('nama_karyawan')),
                //'kd_jabatan' => $this->input->post('kd_jabatan'),
                'tgl_awal' => $this->input->post('tgl_awal'),
                'tgl_akhir' => $tgl_akhir,
                'status_aktif' => $this->input->post('status_aktif'),
                'kd_lokasi' => $this->input->post('kd_lokasi'),
                //'nik_bawahan' => $nik_bawahan,
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')            
            );
            //var_dump($param);exit;
 
            $hasil = $this->curl->simple_put(API_URL . "/api/setup/kepalasales", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_ks($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/kepalasales", $param));
 
        $this->data_output($data, 'delete', base_url('company/ks'));
    }

    public function ks_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/setup/kepalasales"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DEALER;
            $data_message[1][$key] = $message->NIK;
            $data_message[2][$key] = $message->NAMA_KARYAWAN;
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }

    public function ks_bawahan($id) {
        $this->auth->validate_authen('company/ks');
        $data = array();/*
        $dealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer');*/
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=SETUP_KEPALASALES_BAWAHAN.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN AS MK", "MK.NIK=SETUP_KEPALASALES_BAWAHAN.NIK", "LEFT")
            ),
            'field' => 'SETUP_KEPALASALES_BAWAHAN.*, MD.NAMA_DEALER,MK.NAMA',
            'orderby' => 'SETUP_KEPALASALES_BAWAHAN.ID desc',
            "custom" => "SETUP_KEPALASALES_BAWAHAN.KS_ID='".$id."'"

        );

        $param_cek = array(
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "MK.NIK=SETUP_KEPALASALES.NIK", "LEFT"),
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=SETUP_KEPALASALES.KD_DEALER", "LEFT"),
                array("MASTER_LOKASIDEALER AS ML", "ML.KD_LOKASI=SETUP_KEPALASALES.KD_LOKASI AND ML.KD_DEALER=SETUP_KEPALASALES.KD_DEALER", "LEFT")
            ),
            'field' => 'SETUP_KEPALASALES.*, MK.NAMA, MK.PERSONAL_JABATAN, MD.NAMA_DEALER, ML.NAMA_LOKASI',
            "custom" => "SETUP_KEPALASALES.ID='" . $id . "'"
        );


        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales_bawahan", $param));
        $data["cek"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales", $param_cek));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('sales/kepala_sales/list_ks_bawahan', $data);
    }

    public function add_ks_bawahan($id) {
        $this->auth->validate_authen('company/ks');
        $data = array();
 
         if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }

        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$param));

        $paramj = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom'=> "PERSONAL_JABATAN !='Kepala Sales'" 
         );

        $data["karyawan"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$paramj));
        $data["ks_id"] = $id;
        //$data["jabatan"]   = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jabatan"));
            
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales_bawahan"));
        $this->load->view('sales/kepala_sales/add_ks_bawahan', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function ks_bawahan_simpan() {
        $this->auth->validate_authen('company/ks');
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required|trim');
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("status_aktif") != 0 ) {
                $tgl_akhir = date('d/m/Y');
            } else {
                $tgl_akhir = $this->input->post('tgl_akhir');
            }

            $param = array(
                'ks_id' => $this->input->post('ks_id'),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'nik' => strtoupper($this->input->post('nik')),
                'tgl_awal' => $this->input->post('tgl_awal'),
                'tgl_akhir' => $tgl_akhir,
                'status' => $this->input->post('status_aktif'),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/setup/kepalasales_bawahan", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            if($data->recordexists==true){
                $param["id"]= $this->input->post('id');
                $param["lastmodified_by"]=$this->session->userdata("user_id");
                $hasil = $this->curl->simple_put(API_URL . "/api/setup/kepalasales_bawahan", $param, array(CURLOPT_BUFFERSIZE => 10));
                $data = json_decode($hasil);
                //print_r($param);
                //var_dump($hasil);exit();
            }
            $this->session->set_flashdata('tr-active', $data->message);
 
            $this->data_output($hasil, 'post', base_url('company/ks_bawahan/'.$this->input->post('ks_id')));
        }
    }
 
    public function edit_ks_bawahan($id, $row_status) {
        $this->auth->validate_authen('company/ks');
        $param = array(
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=SETUP_KEPALASALES_BAWAHAN.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN AS MK", "MK.NIK=SETUP_KEPALASALES_BAWAHAN.NIK", "LEFT")
            ),
            'field' => 'SETUP_KEPALASALES_BAWAHAN.*, MD.NAMA_DEALER,MK.NAMA',
            "custom" => "SETUP_KEPALASALES_BAWAHAN.ID='".$id."'",
            'row_status' => $row_status
        );

        $paramj = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom'=> "PERSONAL_JABATAN !='Kepala Sales'" 
         );
 
        $data = array();

        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $data["karyawan"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$paramj));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/kepalasales_bawahan", $param));
        //var_dump($data["list"]);exit;
        
        $this->load->view('sales/kepala_sales/edit_ks_bawahan', $data);
        $html = $this->output->get_output();
 
        $this->output->set_output(json_encode($html));
    }
 
    public function update_ks_bawahan($id) {
        $this->form_validation->set_rules('kd_dealer', 'Dealer', 'required');
        $this->form_validation->set_rules('nik', 'NIK', 'required');

        if ($this->input->post("status_aktif") != 0 ) {
                $tgl_akhir = date('d/m/Y');
            } else {
                if($this->input->post('tgl_akhir') == ''){
                    $tgl_akhir = null;
                }else{
                    $tgl_akhir = $this->input->post('tgl_akhir');
                }
                
            }
 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validate_errors()
            );
 
            $this->output->set_output(json_encode($data));
        } else {
            
            $param = array(
                'id' => $this->input->post('id'),
                'ks_id' => $this->input->post('ks_id'),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->input->post('kd_dealer'),
                'nik' => strtoupper($this->input->post('nik')),
                'tgl_awal' => $this->input->post('tgl_awal'),
                'tgl_akhir' => $tgl_akhir,
                'status' => $this->input->post('status_aktif'),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')            
            );

            //var_dump($param);exit;
 
           $hasil = $this->curl->simple_put(API_URL . "/api/setup/kepalasales_bawahan", $param, array(CURLOPT_BUFFERSIZE => 10));
 
            $this->session->set_flashdata('tr-active', $id);
 
            $this->data_output($hasil, 'put');
        }
    }
 
    public function delete_ks_bawahan($id, $ks_id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
 
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/setup/kepalasales_bawahan", $param));
 
        $this->data_output($data, 'delete', base_url('company/ks_bawahan/'.$ks_id));
    }

    public function ks_bawahan_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/setup/kepalasales_bawahan"));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DEALER;
            $data_message[1][$key] = $message->NIK;
            $data_message[2][$key] = $message->NAMA_KARYAWAN;
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }

    //Master Bank
    public function bank($forDropdown=null) {
        if($this->session->userdata("kd_dealer") == ''){
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => $this->input->get('row_status'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'jointable' => array(
                    array("MASTER_KABUPATEN MD", "MD.KD_KABUPATEN=MASTER_BANK.KD_KOTA", "LEFT"),
                    array("MASTER_DEALER MK", "MK.KD_DEALER=MASTER_BANK.KD_DEALER", "LEFT")
                ),
                'field' => 'MASTER_BANK.*, MD.NAMA_KABUPATEN, MK.NAMA_DEALER',
                'orderby' => 'MASTER_BANK.ID desc',
                "custom" => "MK.KD_JENISDEALER='Y'"
            );
        }else{
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => $this->input->get('row_status'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'jointable' => array(
                    array("MASTER_KABUPATEN MD", "MD.KD_KABUPATEN=MASTER_BANK.KD_KOTA", "LEFT"),
                    array("MASTER_DEALER MK", "MK.KD_DEALER=MASTER_BANK.KD_DEALER", "LEFT")
                ),
                'field' => 'MASTER_BANK.*, MD.NAMA_KABUPATEN, MK.NAMA_DEALER',
                'orderby' => 'MASTER_BANK.ID desc',
                "custom" => "MASTER_BANK.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
            );
            $param["kd_dealer"] =($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");
        }
        
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        if($forDropdown==true){
            $param=array(
                'jointable' => array(
                    array("MASTER_KABUPATEN MD", "MD.KD_KABUPATEN=MASTER_BANK.KD_KOTA", "LEFT")
                ),
                'field' => 'MASTER_BANK.*, MD.NAMA_KABUPATEN',
                'orderby' => 'MASTER_BANK.ID desc',
                "custom" => "MASTER_BANK.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
            );
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/bank", $param));


        if($forDropdown==true){
            if($data["list"]){
                if($data["list"]->totaldata>0){
                    echo json_encode($data["list"]->message);
                }
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
            $this->template->site('master/master_bank', $data);
        }
    }

    public function add_bank() {
        $this->auth->validate_authen('company/bank');
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/bank"));
        /* var_dump($data);exit;*/
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["akun"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/kdakun"));


        $this->load->view('form_tambah/add_bank', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function add_bank_simpan() {

        $this->form_validation->set_rules('kd_bank', 'Kode Bank', 'required|trim');
        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $akun = substr($this->input->post("kd_akun"), 0, strpos($this->input->post("kd_akun"), ' '));
            $param = array(
                'kd_bank' => strtoupper($this->input->post("kd_bank")),
                'nama_bank' => $this->input->post("nama_bank"),
                'alamat_bank' => $this->input->post("alamat_bank"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kota' => $this->input->post("kd_kabupaten"),
                'no_rekening' => $this->input->post("no_rekening"),
                'kd_akun' => $akun,
                'kd_dealer' => $this->input->post("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/bank", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('company/bank'));
        }
    }

    public function edit_bank($kd_bank, $row_status) {
        $this->auth->validate_authen('company/bank');

        $param = array(
            'kd_bank' => $kd_bank,
            'row_status' => $row_status
        );

        $data = array();
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["akun"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/kdakun"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/bank", $param));

        /* var_dump($data);
          exit; */

        $this->load->view('form_edit/edit_bank', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_bank($id) {
        $this->form_validation->set_rules('kd_bank', 'Kode Bank', 'required|trim');
        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $akun = substr($this->input->post("kd_akun"), 0, strpos($this->input->post("kd_akun"), ' '));
            $param = array(
                'kd_bank' => strtoupper($this->input->post("kd_bank")),
                'nama_bank' => $this->input->post("nama_bank"),
                'alamat_bank' => $this->input->post("alamat_bank"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kota' => $this->input->post("kd_kabupaten"),
                'no_rekening' => $this->input->post("no_rekening"),
                'kd_akun' => $akun,
                'kd_dealer' => $this->input->post("kd_dealer"),
                'row_status' => $this->input->post("row_status"),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/accounting/bank", $param, array(CURLOPT_BUFFERSIZE => 10));

            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_bank($kd_bank) {
        $param = array(
            'kd_bank' => $kd_bank,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/accounting/bank", $param));

        $this->data_output($data, 'delete', base_url('company/bank'));
    }

    public function bank_typeahead() {
        $param = array(
            "custom" => "MASTER_BANK.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"

        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/bank", $param));
 
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_BANK;
            $data_message[1][$key] = $message->NAMA_BANK;
            
        }
 
        $result['keyword'] = array_merge($data_message[0]);
 
        $this->output->set_output(json_encode($result));
    }

    public function akun_typeahead($keywords=null,$price=null) {
        $data=[];
        $keywords = $this->input->get('keyword');

        $param = array(
            'keyword' => $keywords 
        );
        $data_message=[];
        $data=json_decode($this->curl->simple_get(API_URL."/api/accounting/kdakun",$param));

        // var_dump($data); exit;

        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $message) {
                    //$data_message['keyword'][$key] = $message->KD_AKUN ." - ". $message->NAMA_AKUN;
                    $data_message['keyword'][$key] = $message->KD_AKUN ." - ". $message->NAMA_AKUN;
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

    /**
   * [kodeakun description]
   * @param  [type] $jenisakun [description]
   * @return [type]            [description]
   */
      public function kodeakun($jenisakun=null){
        $param=array(
                'custom'  =>"JENIS >0",
                'orderby' =>"TIPE,SUBTIPE,JENIS,SUBJENIS,SUBSUBJENIS",
                'field'   =>"KD_AKUN,NAMA_AKUN,SALDO_AWAL,\"CASE 
                            LEN(REPLICATE(CHAR(23),15minLEN(KD_AKUN))) 
                            WHEN 1 THEN CONCAT(KD_AKUN,REPLICATE('',1),'  ',NAMA_AKUN) 
                            WHEN 3 THEN CONCAT(KD_AKUN,REPLICATE('.',7),'',NAMA_AKUN)  
                            WHEN 6 THEN CONCAT(KD_AKUN,REPLICATE('.',12),'',NAMA_AKUN)
                            END \" AS NAMA_AKUNS"
              );
        
        $data=json_decode($this->curl->simple_get(API_URL."/api/accounting/kdakun"));
        //var_dump($data);
        if($data){
          if((int)$data->totaldata>0){
            echo json_encode($data->message);
         }
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
