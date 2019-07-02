<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dealer extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
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
     * Activiti dealer
    */
    public function Activity() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => $this->input->get('kd_dealer'),
            'jointable' => array(
                array("MASTER_DEALER AS MD ", "MASTER_ACTIVITY.KD_DEALER=MD.KD_DEALER", "LEFT"),
            ),
            'orderby' => 'MASTER_ACTIVITY.ID desc',
            "custom" => "MASTER_ACTIVITY.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/activity", $param));               
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
        $this->template->site('master/master_activity', $data);
    }
    public function add_activity() {
        $data = array();
        $param = array(
            'row_status' => 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('form_tambah/add_activity', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_activity_simpan() {
        $this->form_validation->set_rules('kd_activity', 'Kode Pit', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_activity', 'Nama Pit', 'required|trim');
        $this->form_validation->set_rules('jenis_activity', 'Jenis Pit', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Tanggal Berakhir', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'kd_activity' => $this->input->post("kd_activity"),
                'nama_activity' => $this->input->post('nama_activity'),
                'jenis_activity' => $this->input->post('jenis_activity'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/activity", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('dealer/activity'));
        }
    }
    public function edit_activity($kd_activity, $row_status) {
        $this->auth->validate_authen('dealer/activity');
        $param = array(
            'kd_activity' => $kd_activity,
            'row_status' => $row_status
        );
        $data = array();
        $paramcustomer["custom"] = "";
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/activity", $param));
        $param = array(
            'row_status' => 0
        );
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $this->load->view('form_edit/edit_activity', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_activity($id) {
        $this->form_validation->set_rules('kd_activity', 'Kode Aktivitas', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_activity', 'Nama Aktivitas', 'required|trim');
        $this->form_validation->set_rules('jenis_activity', 'Jenis Aktivitas', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required|trim');
        $this->form_validation->set_rules('end_date', 'Tanggal Berakhir', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_activity' => $this->input->post("kd_activity"),
                'nama_activity' => $this->input->post('nama_activity'),
                'jenis_activity' => $this->input->post('jenis_activity'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/activity", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_activity($kd_activity) {
        $param = array(
            'kd_activity' => $kd_activity,
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/activity", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('dealer/activity')
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
    public function activity_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/activity"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_ACTIVITY;
            $data_message[1][$key] = $message->NAMA_ACTIVITY;
            $data_message[2][$key] = $message->KD_DEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2]);
        $this->output->set_output(json_encode($result));
    }
    //Master Area Dealer
    public function area_dealer() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'kd_areadealer' => $this->input->get('kd_areadealer'),
            'kd_dealer' => ($this->input->get('kd_dealer')),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' =>"KD_AREADEALER,NAMA_AREADEALER"
        );
        if($this->input->get("kd_dealer")){
            $param["kd_dealer"] = ($this->input->get("kd_dealer")) ;
        }else{
            $param["where_in"] = isDealerAkses();
            $param["where_in_field"]="KD_DEALER";
        }
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/areadealer", $param));
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
        //var_dump($data);
        $this->template->site('master/master_area_dealer', $data);
    }
    public function add_area_dealer() {
        $data=array();$wherin=array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        if($data["dealer"]){
            if($data["dealer"]->totaldata>0){
                foreach ($data["dealer"]->message as $key => $value) {
                    $wherin[]=$value->KD_KABUPATEN;
                }
            }
        }
        $param=array(
            'where_in'=> $wherin,
            'where_in_field'=>"KD_KABUPATEN"
        );
        $data["kabupaten"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten",$param));
        if($this->input->get("n")){
            $param=array(
                'kd_dealer' => $this->input->get("kd_dealer"),
                'kd_areadealer' => $this->input->get("n")
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/areadealer",$param));
        }
        $this->load->view('form_tambah/add_area_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_area_dealer_simpan() {
        $this->form_validation->set_rules('kd_kecamatan', 'Kecamatan harus di pilih', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_kabupaten', 'Kabupaten harus dipilih', 'required|trim');
        $result=array();
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_areadealer' => $this->input->post("kd_kecamatan"),
                'nama_areadealer' => $this->input->post("kd_kabupaten"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'ring_area' => $this->input->post("ring_area"),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/areadealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            if($hasil){
                $result=json_decode($hasil);
                if($result->recordexists){
                    $param["lastmodified_by"]=$this->session->userdata("user_id");
                    $hasil = $this->curl->simple_put(API_URL . "/api/master/areadealer", $param, array(CURLOPT_BUFFERSIZE => 10));
                }
                $this->session->set_flashdata('tr-active', $result->message);
            }
            $this->data_output($hasil, 'post', base_url('dealer/area_dealer'));
        }
    }
    public function edit_area_dealer($kd_areadealer, $row_status) {
        $this->auth->validate_authen('dealer/area_dealer');
        $param = array(
            'kd_areadealer' => $kd_areadealer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/areadealer", $param));
        $param = array(
            'row_status' => 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('form_edit/edit_area_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_area_dealer($id) {
        $this->form_validation->set_rules('kd_areadealer', 'Kode Area Dealer', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_areadealer', 'Nama Area Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_areadealer' => $this->input->post("kd_areadealer"),
                'nama_areadealer' => $this->input->post("nama_areadealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/areadealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_area_dealer($kd_areadealer) {
        $param = array(
            'kd_areadealer' => $kd_areadealer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/areadealer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('dealer/area_dealer')
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
    public function areadealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/areadealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_AREADEALER;
            $data_message[1][$key] = $message->NAMA_AREADEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Master Dealer
    public function dealer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PROPINSI AS MP ", "MASTER_DEALER.KD_PROPINSI=MP.KD_PROPINSI", "LEFT", "KD_PROPINSI,NAMA_PROPINSI"),
                array("MASTER_KABUPATEN AS MK ", "MASTER_DEALER.KD_KABUPATEN=MK.KD_KABUPATEN", "LEFT", "KD_KABUPATEN,NAMA_KABUPATEN"),
            ),
            'field' => 'MASTER_DEALER.*, MP.NAMA_PROPINSI, MK.NAMA_KABUPATEN',
            'orderby' => 'MASTER_DEALER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true/true", $param)); 
        //var_dump($data["list"]) ;exit;          
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
        $this->template->site('master/master_dealer', $data);
    }
    public function add_dealer() {
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $param["link"] = "list34";
        //$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"), true);
        //$listmd2=array("status"=>FALSE,"message"=>"Bad request");//
        //$listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param);
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) :
                json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));
        //var_dump($js);exit;

        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            $propinsi = array(
                'kd_dealer' => $js[$i]["kddlr"],
                'kd_dealerahm' => $js[$i]["kddlrahm"],
                'nama_dealer' => $js[$i]["nmdlr"],
                'tlp' => $js[$i]["tlp"],
                'tlp2' => $js[$i]["tlp2"],
                'tlp3' => $js[$i]["tlp3"],
                'alamat' => $js[$i]["alamat"],
                'kd_jenisdealer' => $js[$i]["cabang"],
                'kd_statusdealer' => $js[$i]["status"],
                'kd_kabupaten' => $js[$i]["kdkota"],
                'kd_propinsi' => $js[$i]["kdprop"],
                'rule_dealer' => $js[$i]["dlrrule"],
                'kategori_dealer' => $js[$i]["dlrareawsh"],
                'no_npwp' => $js[$i]["dlrnpwp"],
                'pkp' => $js[$i]["pkp"],
                'group_dealer' => $js[$i]["groupdealer"],
                'lat' => $js[$i]["laty"],
                'lng' => $js[$i]["lonx"]
                    //'kd_negara' => 62
            );
            //print_r($param);
            $databaru = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $propinsi));
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
        /* var_dump($data["listmd"]);
          exit(); */

        $this->load->view('form_tambah/add_dealer', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }
    public function update_dealer() {
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
                'kd_dealerahm' => $data[0][$i]["kddlrahm"],
                'nama_dealer' => $data[0][$i]["nmdlr"],
                'tlp' => $data[0][$i]["tlp"],
                'tlp2' => $data[0][$i]["tlp2"],
                'tlp3' => $data[0][$i]["tlp3"],
                'alamat' => $data[0][$i]["alamat"],
                'kd_jenisdealer' => $data[0][$i]["cabang"],
                'kd_statusdealer' => $data[0][$i]["status"],
                'kd_kabupaten' => $data[0][$i]["kdkota"],
                'kd_propinsi' => $data[0][$i]["kdprop"],
                'rule_dealer' => $data[0][$i]["dlrrule"],
                'kategori_dealer' => $data[0][$i]["dlrareawsh"],
                'no_npwp' => $data[0][$i]["dlrnpwp"],
                'pkp' => $data[0][$i]["pkp"],
                'group_dealer' => $data[0][$i]["groupdealer"],
                'lat' => $data[0][$i]["laty"],
                'lng' => $data[0][$i]["lonx"],
                'kd_maindealer' => "0",
                'jumlah_pit' => "0",
                'created_by' => $this->session->userdata("user_id") . "| ws_list34"
            );
//print_r($param);
            //var_dump($param);exit;
            $hasil = ($this->curl->simple_post(API_URL . "/api/master/dealer", $param, array(CURLOPT_BUFFERSIZE => 10)));
            $datax = json_decode($hasil);
            if($datax->recordexists == TRUE){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                
                $hasil = ($this->curl->simple_put(API_URL . "/api/master/dealer", $param, array(CURLOPT_BUFFERSIZE => 10)));
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
    public function edit_dealer($kd_dealer, $row_status) {
        $this->auth->validate_authen('dealer/dealer');
        //ob_end_clean();
        //$param = array();
        $param = array(
            'kd_dealer' => $kd_dealer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        //var_dump($data);
        $this->load->view('form_edit/edit_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function dealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DEALER;
            $data_message[1][$key] = $message->KD_DEALERAHM;
            $data_message[2][$key] = $message->NAMA_DEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Master Gudang
    public function gudang() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            //'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_dealer' => $this->input->get('kd_dealer'),
            'jointable' => array(
                array("MASTER_DEALER AS MD ", "MASTER_GUDANG.KD_DEALER=MD.KD_DEALER", "LEFT"),
            ),
            'field' => 'MASTER_GUDANG.*, MD.NAMA_DEALER',
            'orderby' => 'MASTER_GUDANG.ID desc',
            "custom" => "MASTER_GUDANG.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
        $paramd =listDealer();
        if ($this->session->userdata("nama_group") != "Root") {
            //$paramd["kd_dealer"] = $this->session->userdata("kd_dealer");
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramd));
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
        $this->template->site('master/master_gudang', $data);
    }
    public function add_gudang($jenis_gudang = null) {
        $gudang_default = 0;
        $paramd =listDealer();
        if ($this->session->userdata("nama_group") != "Root") {
            $paramd["kd_dealer"] = $this->session->userdata("kd_dealer");
        }
        $param=array(
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramd));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$param));
        $gudang = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
        // var_dump($data);exit();
        if ($gudang) {
            if ((int) $gudang->totaldata > 0) {
                foreach ($gudang->message as $key => $value) {
                    if ($value->DEFAULTS == 1) {
                        $gudang_default = 1;
                    }
                }
            }
        }
        $data["gudang"] = $gudang_default;
        if($jenis_gudang != null){
            $param_gudang = array(
                'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
                'jenis_gudang' => $jenis_gudang,
                'custom' => "DEFAULTS = 1" 
            );
            $gudang_jenis = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param_gudang));
            $this->output->set_output(json_encode($gudang_jenis));
        }
        else{
            $this->load->view('form_tambah/add_gudang', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }
    }
    function add_gudang_simpan() {
        $this->form_validation->set_rules('kd_lokasidealer', 'Kode Lokasi Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_gudang', 'Kode Gudang', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_gudang', 'Nama Gudang', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        //$this->form_validation->set_rules('alamat', 'Alamat Gudang', 'required|trim');
        //$this->form_validation->set_rules('defaults', 'Defaults', 'required|trim|is_numeric'); 
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_lokasidealer' => $this->input->post("kd_lokasidealer"),
                'kd_gudang' => $this->input->post("kd_gudang"),
                'nama_gudang' => $this->input->post("nama_gudang"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'alamat' => $this->input->post("alamat"),
                'defaults' => $this->input->post("defaults"),
                'jenis_gudang' => $this->input->post("jenis_gudang"),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/gudang", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('dealer/gudang'));
        }
    }
    public function edit_gudang($id, $row_status) {
        $gudang_default = 0;
        $this->auth->validate_authen('dealer/gudang');
        $kd_dealer = $this->session->userdata("kd_dealer");
        $param = array(
            "custom" => "ID='".$id."'",
            'row_status' => $row_status
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
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
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$param));
        $gudang = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang", $param));
        if ($gudang) {
            if ((int) $gudang->totaldata > 0) {
                foreach ($gudang->message as $key => $value) {
                    if ($value->DEFAULTS == 1) {
                        $gudang_default = 1;
                    }
                }
            }
        }
        $data["gudang"] = $gudang_default;
        $this->load->view('form_edit/edit_gudang', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_gudang($id) {
        $this->form_validation->set_rules('kd_lokasidealer', 'Kode Lokasi Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_gudang', 'Kode Gudang', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_gudang', 'Nama Gudang', 'required|trim');
        //$this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        //$this->form_validation->set_rules('defaults', 'Default', 'required|trim');
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
                'kd_lokasidealer' => $this->input->post("kd_lokasidealer"),
                'kd_gudang' => $this->input->post("kd_gudang"),
                'nama_gudang' => $this->input->post('nama_gudang'),
                'alamat' => $this->input->post('alamat'),
                'defaults' => $this->input->post('defaults'),
                'jenis_gudang' => $this->input->post('jenis_gudang'),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/gudang", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);exit();
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_gudang($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        //var_dump($param);exit;
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/gudang", $param));
        $this->data_output($data, 'delete', base_url('dealer/gudang'));
    }
    public function gudang_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_GUDANG;
            $data_message[1][$key] = $message->NAMA_GUDANG;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Master Jenis Dealer
    public function jenis_dealer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_JENISDEALER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/jenisdealer", $param));               
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
        $this->template->site('master/master_jenis_dealer', $data);
    }
    public function add_jenis_dealer() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/jenisdealer"));
        $this->load->view('form_tambah/add_jenis_dealer');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_jenis_dealer_simpan() {
        $this->form_validation->set_rules('kd_jenisdealer', 'Kode Jenis Dealer', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_jenisdealer', 'Nama Jenis Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenisdealer' => $this->input->post("kd_jenisdealer"),
                'nama_jenisdealer' => $this->input->post("nama_jenisdealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/jenisdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('dealer/jenis_dealer'));
        }
    }
    public function edit_jenis_dealer($kd_jenisdealer, $row_status) {
        $this->auth->validate_authen('dealer/jenis_dealer');
        $param = array(
            'kd_jenisdealer' => $kd_jenisdealer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/jenisdealer", $param));
        $this->load->view('form_edit/edit_jenis_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_jenis_dealer($id) {
        $this->form_validation->set_rules('kd_jenisdealer', 'Kode Jenis Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_jenisdealer', 'Nama Jenis Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_jenisdealer' => $this->input->post("kd_jenisdealer"),
                'nama_jenisdealer' => $this->input->post("nama_jenisdealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'created_by' => $this->session->userdata('user_id')
            );
            //print_r($param);
            $hasil = $this->curl->simple_put(API_URL . "/api/master/jenisdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_jenis_dealer($kd_jenisdealer) {
        $param = array(
            'kd_jenisdealer' => $kd_jenisdealer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/jenisdealer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('dealer/jenis_dealer')
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
    public function jenisdealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/jenisdealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_JENISDEALER;
            $data_message[1][$key] = $message->NAMA_JENISDEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Master Lokasi Dealer
    public function lokasi_dealer($dataOnly = null, $ajax = null) {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'kd_lokasi' => $this->input->get('kd_lokasi'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER AS MD ", "MD.KD_DEALER=MASTER_LOKASIDEALER.KD_DEALER", "LEFT"),
                array("MASTER_MAINDEALER AS MM", "MM.KD_MAINDEALER=MASTER_LOKASIDEALER.KD_MAINDEALER", "LEFT")
            ),
            'field' => 'MASTER_LOKASIDEALER.*, MD.NAMA_DEALER, MM.NAMA_MAINDEALER',
            'orderby' => 'MASTER_LOKASIDEALER.ID desc',
            "custom" => "MASTER_LOKASIDEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = array();
        if ($dataOnly == true) {
            $param = array(
                'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer")
            );
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer", $param));
        if ($dataOnly == true) {
            if ($data["list"]) {
                if ($data["list"]->totaldata > 0) {
                    if ($ajax == true) {
                        echo json_encode($data["list"]->message);
                    } else {
                        return $data["list"];
                    }
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
            $this->template->site('master/master_lokasi_dealer', $data);
        }
    }
    public function add_lokasi_dealer() {
        $param = array(
            'row_status' => 0,
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",$param));
        $data["maindealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer", $param));
        $this->load->view('form_tambah/add_lokasi_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_lokasi_dealer_simpan() {
        $this->form_validation->set_rules('kd_lokasi', 'Kode Lokasi Dealer', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_maindealer', 'Kode Main Dealer', 'required|trim');
        //$this->form_validation->set_rules('alamat', 'Alamat Dealer', 'required|trim');
        //$this->form_validation->set_rules('chanel', 'Channel Dealer', 'required|trim|is_numeric');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_lokasi' => $this->input->post("kd_lokasi"),
                'nama_lokasi' => $this->input->post("nama_lokasi"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'alamat' => $this->input->post("alamat"),
                'chanel' => ($this->input->post("chanel"))?$this->input->post("chanel"):'1',
                'defaults' => $this->input->post("defaults"),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master_general/lokasidealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post');
        }
    }
    public function edit_lokasi_dealer($kd_lokasi, $row_status) {
        $this->auth->validate_authen('dealer/lokasi_dealer');
        $param = array(
            'kd_lokasi' => $kd_lokasi,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer", $param));
        $param = array(
            'row_status' => 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["maindealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer", $param));
        $this->load->view('form_edit/edit_lokasi_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_lokasi_dealer($id) {
        $this->form_validation->set_rules('kd_lokasi', 'Kode Lokasi Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('kd_maindealer', 'Kode Main Dealer', 'required|trim');
        //$this->form_validation->set_rules('alamat', 'Alamat Dealer', 'required|trim');
        //$this->form_validation->set_rules('chanel', 'Channel Dealer', 'required|trim|is_numeric');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_lokasi' => $this->input->post("kd_lokasi"),
                'nama_lokasi' => $this->input->post("nama_lokasi"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'alamat' => $this->input->post("alamat"),
                'chanel' => $this->input->post("chanel"),
                'defaults' => $this->input->post("defaults"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master_general/lokasidealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_lokasi_dealer($kd_lokasi) {
        $param = array(
            'kd_lokasi' => $kd_lokasi,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master_general/lokasidealer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('dealer/lokasi_dealer')
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
    public function lokasidealer_typeahead() {
        $param = array("kd_dealer" => $this->session->userdata("kd_dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer", $param));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_LOKASI;
            $data_message[1][$key] = $message->NAMA_LOKASI;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Master Main Dealer
    public function main_dealer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'kd_maindealer' => $this->input->get('kd_maindealer'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PROPINSI AS MP ", "MASTER_MAINDEALER.KD_PROPINSI=MP.KD_PROPINSI", "LEFT", "KD_PROPINSI,NAMA_PROPINSI"),
                array("MASTER_KABUPATEN AS MK ", "MASTER_MAINDEALER.KD_KABUPATEN=MK.KD_KABUPATEN", "LEFT", "KD_KABUPATEN,NAMA_KABUPATEN"),
            ),
            'orderby' => 'MASTER_MAINDEALER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer", $param));
//        var_dump($this->curl->simple_get(API_URL."/api/master/maindealer",$param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
//        var_dump($data);        exit();
        $this->template->site('master/master_main_dealer', $data);
    }
    public function add_main_dealer() {
        $param = array(
            'row_status' => 0
        );
        $data["kabupatens"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
//        var_dump($data);
//        exit;
        $this->load->view('form_tambah/add_main_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function add_main_dealer_simpan() {
        $this->form_validation->set_rules('kd_maindealer', 'Kode Main Dealer', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_maindealer', 'Nama Main Dealer', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('kd_kabupaten', 'Kode Kabupaten', 'required|trim');
        $this->form_validation->set_rules('kd_propinsi', 'Kode Propinsi', 'required|trim');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => html_escape($this->input->post("kd_maindealer")),
                'nama_maindealer' => html_escape($this->input->post("nama_maindealer")),
                'alamat' => $this->input->post("alamat"),
                'kd_kabupaten' => $this->input->post('kd_kabupaten'),
                'kd_propinsi' => $this->input->post('kd_propinsi'),
                'telepon' => $this->input->post('telepon'),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/maindealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('dealer/main_dealer'));
        }
    }
    public function edit_main_dealer($kd_maindealer, $row_status) {
        $this->auth->validate_authen('dealer/main_dealer');
        $param = array(
            'kd_maindealer' => $kd_maindealer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer", $param));
        $param = array(
            'row_status' => 0
        );
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $this->load->view('form_edit/edit_main_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_main_dealer($id) {
        $this->form_validation->set_rules('kd_maindealer', 'Kode Main Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_maindealer', 'Nama Main Dealer', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('kd_kabupaten', 'Kode Kabupaten', 'required|trim');
        $this->form_validation->set_rules('kd_propinsi', 'Kode Propinsi', 'required|trim');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_maindealer' => html_escape($this->input->post("kd_maindealer")),
                'nama_maindealer' => html_escape($this->input->post("nama_maindealer")),
                'alamat' => $this->input->post("alamat"),
                'kd_kabupaten' => $this->input->post('kd_kabupaten'),
                'kd_propinsi' => $this->input->post('kd_propinsi'),
                'telepon' => $this->input->post('telepon'),
                'row_status' => html_escape($this->input->post("row_status")),
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/maindealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_main_dealer($kd_maindealer) {
        $param = array(
            'kd_maindealer' => $kd_maindealer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/maindealer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('dealer/main_dealer')
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
    public function maindealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/maindealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_MAINDEALER;
            $data_message[1][$key] = $message->NAMA_MAINDEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
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
    //Master Pit Dealer
    public function pit_dealer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'orderby' => 'MASTER_PITDEALER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/pitdealer", $param));               
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
        $this->template->site('master/master_pit_dealer', $data);
    }
    public function add_pit_dealer() {
        $data = array();
        $param = array(
            'row_status' => 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["jenispit"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jenispit"));
        $this->load->view('form_tambah/add_pit_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_pit_dealer_simpan() {
        $this->form_validation->set_rules('kd_pit', 'Kode Pit', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_pit', 'Nama Pit', 'required|trim');
        $this->form_validation->set_rules('jenis_pit', 'Jenis Pit', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param_cek = array(
                "custom" => "KD_DEALER='" . $this->input->post("kd_dealer") . "' AND URUTAN='" . $this->input->post('urutan') . "'"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/pitdealer", $param_cek));
            if ($data["list"]->totaldata > 0) {
                $data_status = array(
                    'status' => false,
                    'message' => 'Urutan sudah ada',
                );
                $this->output->set_output(json_encode($data_status));
            } else {
                $param = array(
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'kd_pit' => $this->input->post("kd_pit"),
                    'nama_pit' => $this->input->post('nama_pit'),
                    'jenis_pit' => $this->input->post('jenis_pit'),
                    'urutan' => $this->input->post('urutan'),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata("user_id")
                );
                $hasil = $this->curl->simple_post(API_URL . "/api/master/pitdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
                $data = json_decode($hasil);
                $this->session->set_flashdata('tr-active', $data->message);
                $this->data_output($hasil, 'post', base_url('dealer/pit_dealer'));
            }
        }
    }
    public function edit_pit_dealer($kd_pit, $row_status) {
        $this->auth->validate_authen('dealer/pit_dealer');
        $param = array(
            'kd_pit' => $kd_pit,
            'row_status' => $row_status
        );
        $data = array();
        $paramcustomer["custom"] = "";
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/pitdealer", $param));
        $param = array(
            'row_status' => 0
        );
        $data["dealers"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $data["jenispit"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jenispit"));
        $this->load->view('form_edit/edit_pit_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_pit_dealer($id) {
        $this->form_validation->set_rules('kd_pit', 'Kode Pit', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('kd_dealer', 'Kode Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_pit', 'Nama Pit', 'required|trim');
        $this->form_validation->set_rules('jenis_pit', 'Jenis Pit', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param_cek = array(
                "custom" => "KD_DEALER='" . $this->input->post("kd_dealer") . "' AND URUTAN='" . $this->input->post('urutan') . "'"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/pitdealer", $param_cek));
            if ($data["list"]->totaldata > 0) {
                $data_status = array(
                    'status' => false,
                    'message' => 'Urutan sudah ada',
                );
                $this->output->set_output(json_encode($data_status));
            } else {
                $param = array(
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'kd_pit' => $this->input->post("kd_pit"),
                    'nama_pit' => $this->input->post('nama_pit'),
                    'jenis_pit' => $this->input->post('jenis_pit'),
                    'urutan' => $this->input->post('urutan'),
                    'row_status' => html_escape($this->input->post("row_status")),
                    'lastmodified_by' => $this->session->userdata('user_id')
                );
                $hasil = $this->curl->simple_put(API_URL . "/api/master/pitdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
                $this->session->set_flashdata('tr-active', $id);
                $this->data_output($hasil, 'put');
            }
        }
    }
    public function delete_pit_dealer($kd_pit) {
        $param = array(
            'kd_pit' => $kd_pit,
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/pitdealer", $param));
//        var_dump($data);
//        exit;
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('dealer/pit_dealer')
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
    public function pitdealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/pitdealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_PIT;
            $data_message[1][$key] = $message->NAMA_PIT;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Salesman
    public function salesman() {
        $data = array();
        $dealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            //'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=MASTER_SALESMAN.KD_DEALER", "LEFT")
            ),
            'orderby' => 'MASTER_SALESMAN.STATUS_SALES,MASTER_SALESMAN.NAMA_SALES',
            "custom" => "MASTER_SALESMAN.KD_DEALER IN('" . $dealer . "')"
        );
        if ($this->input->get('row_status')) {
            $param['status_sales'] = $this->input->get('row_status');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
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
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('salesman/index', $data);
    }
    public function add_salesman() {
        $data = array();
        $databaru = array();
        $param = array();
        $dataupdate = array();
        $js = array();
        $defaultDealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata('kd_dealer');
        $param["link"] = "list1";
        $param["param"] = ($this->session->userdata("kd_dealer"));
        $listmd = $this->curl->simple_get(API_URL . "/api/login/webservice", $param, array('useragent' => true, 'timeout' => 0, 'returntransfer' => true));
        //var_dump($listmd);exit();
        $js = (is_json($listmd)) ? (json_decode($listmd, true)) : json_encode(array("status" => FALSE, "message" => "Bad request"));
        $js = (json_decode($js, true));
        $totaldata = 0;
        for ($i = 0; $i < count($js); $i++) {
            if($defaultDealer==rtrim($js[$i]['kddlr'])){
                $dealer = array(
                    'kd_sales' => $js[$i]['idsales'],
                    'nama_sales' => str_replace("'", "", rtrim($js[$i]['nmsales'])),
                    'status_sales' => $js[$i]['status'],
                    'kd_dealer' => $js[$i]['kddlr']
                );
                $databaru = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $dealer));
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
        }
        $data["listmd"] = $dataupdate;
        $this->load->view('salesman/add_salesman', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function edit_salesman($id=null,$row_status=null){
        $data=array();
        $defaultDealer = ($row_status)?$row_status:$this->session->userdata("kd_dealer");
        $param=array(
            'field'=>"KD_JABATAN",
            'groupby' => TRUE,
            'orderby' =>"KD_JABATAN",
            'custom'  => "KD_JABATAN IN('SWAT','SW','S. Win','S. Re')"
        );
        $data["kdjb"]=json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $param=array(
            'field'=>"PERSONAL_JABATAN",
            'groupby' => TRUE,
            'orderby' =>"PERSONAL_JABATAN",
            'custom'  => "PERSONAL_JABATAN LIKE '%Sales%'"
        );
        $data["psjb"]=json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $param = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $param));
        if($id){
            $param=array(
                'kd_sales'=>$id,
                'kd_dealer' => $defaultDealer
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
        }
        $param=array(
            'field' =>"GROUP_SALES",
            'groupby' => TRUE,
            'orderby' =>"GROUP_SALES"
        );
        $data["gs"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
        $this->load->view('salesman/edit_salesman', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function simpan_sales(){
        $data=array();$hasil=array();
        $param=array();
        $param['kd_sales'] = $this->input->post("kd_sales");
        $param['nama_sales'] = strtoupper(str_replace("'", "", (rtrim($this->input->post("nama_sales")))));
        $param['kd_hsales'] = $this->input->post("kd_hsales");
        $param['kd_dealer'] = $this->input->post("kd_dealer");
        $param['status_sales'] = $this->input->post("status_sales");
        $param['group_sales'] = $this->input->post("kd_group");
        $param['nik']   = ($this->input->post("nik_sales"))?$this->input->post("nik_sales"):$this->input->post("inputpicker-1");
        $param['kd_jabatan']   = $this->input->post("kd_jabatan");
        $param['ps_jabatan']   = $this->input->post("ps_jabatan");
        $param['createdby'] = $this->session->userdata("user_id");
        $hasil = $this->curl->simple_post(API_URL . "/api/sales/salesman", $param, array(CURLOPT_BUFFERSIZE => 10));
        $data=json_decode($hasil);
        if($data){
            if ($data->recordexists){
                $param['lastmodified_by'] = $this->session->userdata("user_id");
                $hasil = $this->curl->simple_put(API_URL . "/api/sales/salesman", $param, array(CURLOPT_BUFFERSIZE => 10));
            }
        }
        $this->session->set_flashdata('tr-active', $param['kd_sales']);
        redirect(base_url().'dealer/salesman');
    }
    public function update_salesman() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        $param = array();
        for ($i = 0; $i < count($data[0]); $i++) {
            $param[$i]['KD_SALES'] = $data[0][$i]["idsales"];
            $param[$i]['NAMA_SALES'] = strtoupper(str_replace("'", "", (rtrim($data[0][$i]["nmsales"]))));
            $param[$i]['KD_HSALES'] = rtrim($data[0][$i]["hondaid"]);
            $param[$i]['KD_DEALER'] = rtrim($data[0][$i]["kddlr"]);
            $param[$i]['STATUS_SALES'] = trim($data[0][$i]["status"]);
            $param[$i]['GROUP_SALES'] = substr($data[0][$i]["idsales"], 3, 2);
            $param[$i]['CREATED_BY'] = $this->session->userdata("user_id");
        }
        $paramsales = array(
            'query' => $this->Custom_model->simpan_sales(json_encode($param))
        );
        $hasil = $this->curl->simple_post(API_URL . "/api/sales/salesmannew", $paramsales, array(CURLOPT_BUFFERSIZE => 100));
        if ($hasil) {
            $result = array(
                'status' => true,
                'message' => number_format($i, 0) . " Data berhasil di simpan"
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
    public function salesman_typeahead() {
        $param = array(
            "custom" => "MASTER_SALESMAN.KD_DEALER='".$this->session->userdata('kd_dealer')."'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_DEALER;
            $data_message[1][$key] = $message->KD_SALES;
            $data_message[2][$key] = $message->NAMA_SALES;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2]);
        $this->output->set_output(json_encode($result));
    }
    //Master Status Dealer
    public function status_dealer() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'orderby' => 'MASTER_STATUSDEALER.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/statusdealer", $param));
//        var_dump($this->curl->simple_get(API_URL."/api/master/statusdealer",$param));
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
        $this->template->site('master/master_status_dealer', $data);
    }
    public function add_status_dealer() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/statusdealer"));
        $this->load->view('form_tambah/add_status_dealer');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function add_status_dealer_simpan() {
        $this->form_validation->set_rules('kd_statusdealer', 'Kode Status Dealer', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_statusdealer', 'Nama Status Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_statusdealer' => $this->input->post("kd_statusdealer"),
                'nama_statusdealer' => $this->input->post("nama_statusdealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/statusdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            /* var_dump($hasil);
              exit(); */
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('dealer/status_dealer'));
        }
    }
    public function edit_status_dealer($kd_statusdealer, $row_status) {
        $this->auth->validate_authen('dealer/status_dealer');
        $param = array(
            'kd_statusdealer' => $kd_statusdealer,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/statusdealer", $param));
        $this->load->view('form_edit/edit_status_dealer', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_status_dealer($id) {
        $this->form_validation->set_rules('kd_statusdealer', 'Kode Status Dealer', 'required|trim');
        $this->form_validation->set_rules('nama_statusdealer', 'Nama Status Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_statusdealer' => $this->input->post("kd_statusdealer"),
                'nama_statusdealer' => $this->input->post("nama_statusdealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'created_by' => $this->session->userdata("user_id")
            );
            //print_r($param);
            $hasil = $this->curl->simple_put(API_URL . "/api/master/statusdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_status_dealer($kd_statusdealer) {
        $param = array(
            'kd_statusdealer' => $kd_statusdealer,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/statusdealer", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('dealer/status_dealer')
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
    public function statusdealer_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/statusdealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_STATUSDEALER;
            $data_message[1][$key] = $message->NAMA_STATUSDEALER;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //Master Wilayah
    public function Wilayah() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => '*',
            'orderby' => 'MASTER_WILAYAH.ID desc'
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/wilayahdealer", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_wilayah', $data);
    }
    public function add_wilayah() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/wilayah"));
        $this->load->view('form_tambah/add_wilayah', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function add_wilayah_simpan() {
        $this->form_validation->set_rules('kd_wilayah', 'Kode Wilayah', 'required|trim|max_length[5]');
        $this->form_validation->set_rules('nama_wilayah', 'Nama Wilayah', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_wilayah' => html_escape($this->input->post("kd_wilayah")),
                'nama_wilayah' => html_escape($this->input->post("nama_wilayah")),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/master/wilayahdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('dealer/wilayah'));
        }
    }
    public function edit_wilayah($kd_wilayah, $row_status) {
        $this->auth->validate_authen('dealer/wilayah');
        $param = array(
            'kd_wilayah' => $kd_wilayah,
            'row_status' => $row_status
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/wilayahdealer", $param));
        $this->load->view('form_edit/edit_wilayah', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_wilayah($id) {
        $this->form_validation->set_rules('kd_wilayah', 'Kode Wilayah', 'required|trim');
        $this->form_validation->set_rules('nama_wilayah', 'Nama Wilayah', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_wilayah' => html_escape($this->input->post("kd_wilayah")),
                'nama_wilayah' => html_escape($this->input->post("nama_wilayah")),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/master/wilayahdealer", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function delete_wilayah($kd_wilayah) {
        $param = array(
            'kd_wilayah' => $kd_wilayah,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/master/wilayahdealer", $param));
        $this->data_output($data, 'delete', base_url('dealer/wilayah'));
    }
    public function wilayah_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master/wilayahdealer"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_WILAYAH;
            $data_message[1][$key] = $message->NAMA_WILAYAH;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    /**
     * [stnk_bpkb_old description]
     * depreciated
     * @return [type] [description]
     */
    public function stnk_bpkb_old() {
        $data = array();
        if ($this->session->userdata('kd_group') == 'root') {
            $param = array(
                'keyword' => $this->input->get('keyword'),
                'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                'limit' => 15,
                'jointable' => array(
                    array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_STNK_BPKB.KD_KABUPATEN", "LEFT")
                ),
                'field' => 'MASTER_STNK_BPKB.*, MK.NAMA_KABUPATEN',
                'orderby' => 'MASTER_STNK_BPKB.ID DESC'
            );
        } else {
            $cek = array(
                'field' => 'MASTER_DEALER.*',
                "custom" => "MASTER_DEALER.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
            );
            $data = array();
            $data['user'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $cek));
            $jenisdealer = $data['user']->message[0]->KD_JENISDEALER;
            if ($jenisdealer == "T") {
                $param = array(
                    'keyword' => $this->input->get('keyword'),
                    'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                    'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                    'limit' => 15,
                    'jointable' => array(
                        array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_STNK_BPKB.KD_KABUPATEN", "LEFT")
                    ),
                    'field' => 'MASTER_STNK_BPKB.*, MK.NAMA_KABUPATEN',
                    "custom" => "MASTER_STNK_BPKB.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
                        // 'orderby' => 'MASTER_STNK_BPKB.ID DESC'
                        // 'orderby' =>'ROW_NUMBER() OVER(PARTITION BY KD_DEALER,KD_TIPEMOTOR,TAHUN ORDER BY ID DESC)'
                );
            } else {
                $param = array(
                    'keyword' => $this->input->get('keyword'),
                    'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
                    'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
                    'limit' => 15,
                    'jointable' => array(
                        array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_STNK_BPKB.KD_KABUPATEN", "LEFT")
                    ),
                    'field' => 'MASTER_STNK_BPKB.*, MK.NAMA_KABUPATEN',
                    "custom" => "MASTER_STNK_BPKB.KD_DEALER='ALL' AND MASTER_STNK_BPKB.KD_KABUPATEN IN(SELECT KD_KABUPATEN FROM MASTER_DEALER WHERE KD_DEALER='" . $this->session->userdata("kd_dealer") . "')",
                    'orderby' => 'MASTER_STNK_BPKB.ID DESC'
                        // 'orderby' => 'ROW_NUMBER() OVER(PARTITION BY KD_DEALER,KD_TIPEMOTOR,TAHUN ORDER BY ID DESC)'
                );
            }
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $param));
        //var_dump($data["list"]);exit();
        $data["list_backup"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb_backup"));               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_stnkbpkb', $data);
    }
    /**
     * new stnk bpkb proses load list
     * @param  [type] $debug [for development purpose only]
     * @return [type]        [description]
     */
    public function stnk_bpkb($debug=null){
        $paramex=array(
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );
        $inDealer=array($paramex["kd_dealer"]);
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        $inKabupaten=array(); 
        $isCabang="T";
        if($data["dealer"]){
            if($data["dealer"]->totaldata > 0){
                foreach ($data["dealer"]->message as $key => $value) {
                    if($value->KD_JENISDEALER=='Y'){
                        // array_push($inDealer,'ALL');
                        $inDealer=array('ALL');
                        if($this->input->get("kd_dealer") && $paramex["kd_dealer"]== $value->KD_DEALER){
                            array_push($inKabupaten,$value->KD_KABUPATEN);
                            $isCabang =$value->KD_JENISDEALER;
                        }
                        
                    }
                }
            }
        }
        $param = array(
            'kd_tipemotor' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status'))?$this->input->get('row_status'):"0",
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'tahun' => ($this->input->get("tahun"))?$this->input->get("tahun"):date('Y'),
        );
        $param["where_in"] = $inDealer;
        $param["where_in_field"] = "KD_DEALER";  
        $param["orderby"] = "ID DESC"; 
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $param));
        //print_r(expression)
        //load data yng akan di approve
        $paramx=array( 
            'row_status' => "[-2]",
            'custom'     => "TAHUN = ".$param["tahun"]." AND (LEN(KD_KABUPATEN)>1 OR KD_KABUPATEN >0)",
        );
        $areaD=array();
        if($isCabang=='T'){
            $prm=array(
                'kd_dealer' => $paramex["kd_dealer"],
                'field' =>"NAMA_AREADEALER",
                'groupby'=>TRUE
            );
            $hasil =json_decode($this->curl->simple_get(API_URL . "/api/master/areadealer", $prm));
            if($hasil){
                if($hasil->totaldata >0){
                    foreach ($hasil->message as $key => $value) {
                        array_push($areaD,$value->NAMA_AREADEALER);
                    }
                }
            }
        }
        $paramx["where_in"] = ($isCabang=='T')?$areaD:$inDealer;
        $paramx["where_in_field"] = ($isCabang=='T')?"KD_KABUPATEN":"KD_DEALER";
        $paramx["custom"] .=($isCabang=='Y')?" AND KD_DEALER='ALL'":"";
        if($isCabang=='T'){
            $paramx["grouping"]=TRUE;
            $paramx["where_group"]="AND";
            $paramx["where_or"] = implode(",",$inDealer);
            $paramx["where_or_field"] = "KD_DEALER";

            $paramx["groupingout"]=TRUE;
            $paramx["where_groupout"]="OR";
            $paramx["where_orout"] = implode(",",$inDealer);
            $paramx["where_or_fieldout"] = "KD_DEALER";
        }
        $paramx["offset"] =   ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
        $paramx["limit"] =  15;
        $paramx["orderby"] = "ID DESC";   
        $data["needApv"] =json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb/", $paramx));
        //end of data approve
        if($debug){
            //echo "Cabang :".$isCabang."<br>";
            print_r($paramx);
            var_dump($data["needApv"]);
            // var_dump($data["needApv"]->totaldata);
            exit();
        }

        $paramth=array('field'=>"TAHUN",'groupby'=>TRUE, 'orderby'=>"TAHUN DESC");
        $data["thndata"]=json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $paramth));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],//base_url() . 'dealer/stnk_bpkb?keyword=' . $param['keyword'] . '&row_status=' . $param['row_status'],
            'total_rows' => isset( $data["list"])?$data["list"]->totaldata:"0"
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_stnkbpkb', $data);
    }
    public function stnk_bpkb_approval() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_STNK_BPKB_BACKUP.KD_KABUPATEN", "LEFT"),
                array("MASTER_KARYAWAN as MR", "MR.NIK=MASTER_STNK_BPKB_BACKUP.NIK", "LEFT"),
            ),
            'field' => 'MASTER_STNK_BPKB_BACKUP.*, MK.NAMA_KABUPATEN, MR.NAMA',
            'orderby' => 'MASTER_STNK_BPKB_BACKUP.ID DESC',
            "custom" => "MASTER_STNK_BPKB_BACKUP.NIK='" . $this->session->userdata('user_id') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb_backup", $param));               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master/master_stnkbpkb_approval', $data);
    }
    public function history_stnk_bpkb($id) {
        $this->auth->validate_authen('dealer/stnk_bpkb');
        $data = array();
        $param_detail = array(
            'jointable' => array(
                array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_STNK_BPKB.KD_KABUPATEN", "LEFT")
            ),
            'field' => 'MASTER_STNK_BPKB.*, MK.NAMA_KABUPATEN',
            "custom" => "MASTER_STNK_BPKB.ID='" . $id . "'"
        );
        $data["list_detail"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $param_detail));
        $param = array(
            /* /'keyword' => $this->input->get('keyword'),
              'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
              'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
              //'limit' => 15, */
            'jointable' => array(
                array("MASTER_KABUPATEN as MK", "MK.KD_KABUPATEN=MASTER_STNK_BPKB_BACKUP.KD_KABUPATEN", "LEFT"),
                array("MASTER_KARYAWAN as MR", "MR.NIK=MASTER_STNK_BPKB_BACKUP.NIK", "LEFT"),
            ),
            'field' => 'MASTER_STNK_BPKB_BACKUP.*, MK.NAMA_KABUPATEN, MR.NAMA',
            'orderby' => 'MASTER_STNK_BPKB_BACKUP.TAHUN DESC',
            'kd_dealer' => $data["list_detail"]->message[0]->KD_DEALER,
            'kd_tipemotor' => $data["list_detail"]->message[0]->KD_TIPEMOTOR,
            "custom" => "MASTER_STNK_BPKB_BACKUP.KD_KABUPATEN='" . $data["list_detail"]->message[0]->KD_KABUPATEN . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb_backup", $param));
        /* $config = array(
          'per_page' => $param['limit'],
          'base_url' => base_url() . 'dealer/history_stnk_bpkb?keyword=' . $param['keyword'] . '&row_status=' . $param['row_status'],
          'total_rows' => $data["list"]->totaldata
          );
          $pagination = $this->pagination($config);
          $this->pagination->initialize($pagination);
          $data['pagination'] = $this->pagination->create_links();
         */
        $this->template->site('master/master_stnkbpkb_history', $data);
    }
    public function add_stnk_bpkb($id=null, $row_status=null) {
        $this->auth->validate_authen('dealer/stnk_bpkb');
        $data = array();
        $param['kd_dealer'] = $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $param=array(
            'field' => 'KD_TYPEMOTOR,NAMA_TYPEMOTOR,NAMA_PASAR',
            'groupby' => TRUE,
            'orderby' =>'KD_TYPEMOTOR,NAMA_TYPEMOTOR'
        );
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor",$param));
        //var_dump($data["typemotors"]);print_r($param);exit();
        $param = array('kd_propinsi' => $data["dealer"]->message[0]->KD_PROPINSI);
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi", $param));
        if((int)$id>0){
            $param=array(
                'id'    =>$id
             );
            if((int)$row_status==-2){
                $param['row_status'] ="[-2]";
            }
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $param));

        }
        $judul="Tambah";
        if((int)$id >0 && !$row_status){ $judul="Edit"; } else if((int)$id>0 && $row_status="-2"){ $judul="Approve";}
        $data["judul"]=$judul;
        $param=array(
            'field' =>"KD_KABUPATEN,NAMA_KABUPATEN",
            'groupby' =>"TRUE",
            'orderby' =>"NAMA_KABUPATEN",
            // 'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["wilayah"] = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bpkb_wilayah/true",$param));
        //var_dump($data);exit();
        $this->load->view('form_tambah/add_stnkbpkb', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function add_stnk_bpkb_simpan() {
        $this->form_validation->set_rules('kd_kabupaten', 'Kabupeten', 'required|trim');
        $this->form_validation->set_rules('kd_motor', 'Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('kd_propinsi', 'Propinsi', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Delaer', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('bbnkb', 'BBNKB', 'required|trim');
        $this->form_validation->set_rules('pkb', 'PKB', 'required|trim');
        $this->form_validation->set_rules('swdkllj', 'Swdkllj', 'required|trim');
        $this->form_validation->set_rules('stck', 'STCK', 'required|trim');
        $this->form_validation->set_rules('plat_asli', 'Plat Asli', 'required|trim');
        $this->form_validation->set_rules('bpkb', 'BPKB', 'required|trim');
        $this->form_validation->set_rules('admin_samsat', 'Admin Samsat', 'required|trim');
        $this->form_validation->set_rules('banpen', 'Banpen', 'required|trim');
        //validasi
            if ($this->input->post("bbnkb") == null) {
                $bbnkb = 0;$bbnkb_a =0;
            } else {
                $bbnkb = str_replace(',', '', $this->input->post("bbnkb"));
                $bbnkb_a = str_replace(',', '', $this->input->post("bbnkb_a"));
            }
            if ($this->input->post("pkb") == null) {
                $pkb = 0;$pkb_a =0;
            } else {
                $pkb = str_replace(',', '', $this->input->post("pkb"));
                $pkb_a = str_replace(',', '', $this->input->post("pkb_a"));
            }
            if ($this->input->post("swdkllj") == null) {
                $swdkllj = 0;$swdkllj_a =0;
            } else {
                $swdkllj = str_replace(',', '', $this->input->post("swdkllj"));
                $swdkllj_a = str_replace(',', '', $this->input->post("swdkllj_a"));
            }
            if ($this->input->post("stck") == null) {
                $stck = 0;
            } else {
                $stck = str_replace(',', '', $this->input->post("stck"));
            }
            if ($this->input->post("plat_asli") == null) {
                $plat_asli = 0;
            } else {
                $plat_asli = str_replace(',', '', $this->input->post("plat_asli"));
            }
            if ($this->input->post("bpkb") == null) {
                $bpkb = 0;
            } else {
                $bpkb = str_replace(',', '', $this->input->post("bpkb"));
            }
            if ($this->input->post("pengurusan_tambahan") == null) {
                $pengurusan_tambahan = 0;
            } else {
                $pengurusan_tambahan = str_replace(',', '', $this->input->post("pengurusan_tambahan"));
            }
            if ($this->input->post("admin_samsat") == null) {
                $admin_samsat = 0;
            } else {
                $admin_samsat = str_replace(',', '', $this->input->post("admin_samsat"));
            }
            if ($this->input->post("ss") == null) {
                $ss = 0;
            } else {
                $ss = str_replace(',', '', $this->input->post("ss"));
            }
            if ($this->input->post("banpen") == null) {
                $banpen = 0;
            } else {
                $banpen = str_replace(',', '', $this->input->post("banpen"));
            }
            if($this->input->post("kd_dealer")=='ALL'){
                $kd_dealer='ALL';
            }else{
                $cek = array(
                    'kd_dealer'=>$this->session->userdata('kd_dealer') 
                );
                $data = array();
                $data['user'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $cek));
                $jenisdealer = 'Y';
                if($data["user"]){
                    if (($data["user"]->totaldata >0)) {
                        $jenisdealer = $data['user']->message[0]->KD_JENISDEALER;
                        # code...
                    }
                }
                if ($jenisdealer == "T") {
                    $kd_dealer = $this->session->userdata('kd_dealer');
                } else {
                    $kd_dealer = "ALL";
                }
            }
        if(!$this->input->post("kd_kabupaten")){
            $mode="reinst";
            goto fedback;
        }
        //endof validasi
        //need approval
        
        $row_status=0;
        if(!$this->input->post("notApv")){
            if((double)$bbnkb != (double)$bbnkb_a ||
                (double)$pkb != (double)$pkb_a ||
                (double)$swdkllj != (double)$swdkllj_a){
                $row_status = "-2";
            }
        }
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {

            $param = array(
                'kd_tipemotor' => $this->input->post("kd_motor"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_dealer' => $kd_dealer,
                'tahun' => $this->input->post("start_date"),
                'bbnkb' => $bbnkb,
                'pkb' => $pkb,
                'swdkllj' => $swdkllj,
                'total_stnk' => (int) $bbnkb + (int) $pkb + (int) $swdkllj,
                'stck' => $stck,
                'plat_asli' => $plat_asli,
                'admin_samsat' => $admin_samsat,
                'bpkb' => $bpkb,
                'pengurusan_tambahan' => $pengurusan_tambahan,
                'total_bpkb' => (int) $stck + (int) $plat_asli + (int) $admin_samsat + (int) $bpkb,
                'ss' => $ss,
                'banpen' => $banpen,
                'wilayah_samsat' => $this->input->post("wilayah_samsat")?$this->input->post("wilayah_samsat"):null,
                'tipe_customer' => $this->input->post("tipe_customer"),
                'row_status' => $row_status,
                'created_by' => $this->session->userdata('user_id'),
                're_insert' =>($this->input->post("id"))?"":"1"
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_bpkb", $param, array(CURLOPT_BUFFERSIZE => 10));
            $hasilx=json_decode($hasil);
            
            $mode='post';
            if($hasilx){
                if($hasilx->recordexists){
                    if($param["re_insert"]){
                        //check row_status dari id tersebut
                        $paramx=array(
                            "id"=>$hasilx->lastid,
                            'row_status' =>"[-2]"
                        );
                        if($jenisdealer=='T'){
                            $paramx["kd_dealer"]=$param["kd_dealer"];
                        }
                        $hasile=($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $paramx));
                    
                        if($hasile){
                            $hsl=json_decode($hasile);
                            if($hsl->totaldata ==0){
                                $hasil=$hasile;
                                $mode="reinst";
                                goto fedback;
                            }else{
                            goto updatex;
                            }
                        }
                    }else{
                            updatex:
                            $mode='put';
                            $param["id"]    = ($mode=='post')?$this->input->post("id"):$hasilx->lastid;
                            $param["lastmodified_by"] = $this->session->userdata("user_id");
                            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bpkb", $param, array(CURLOPT_BUFFERSIZE => 10));
                            
                    }
                }
            }
            fedback:
            $data = json_decode($hasil);
            if($this->input->post('updAll')){
                // echo "ok";
                $hasil =$this->updateAllItem($param,$param["kd_kabupaten"],$param["tahun"]);
            }
            $this->session->set_flashdata('tr-active', $this->input->post("id"));
            
            $this->data_output($hasil, $mode);
        }
    }
    function updateAllItem($data,$kd_kabupaten=null,$tahun=null){
        //get data item kendaraaan
        $hasil=array();
        $param=array(
            'field' => 'KD_TIPEMOTOR,ID',
            'groupby' => TRUE,
            'kd_kabupaten' => $kd_kabupaten,
            'tahun' =>$tahun,
            'kd_dealer' => 'ALL'
        );
        $datamotor = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb",$param));
        if($datamotor){
            if($datamotor->totaldata>0){
                foreach ($datamotor->message as $key => $value) {
                    $param = array(
                        'kd_tipemotor' => $value->KD_TIPEMOTOR,
                        'kd_propinsi' => $data["kd_propinsi"],
                        'kd_kabupaten' => $data["kd_kabupaten"],
                        'kd_dealer' => $data["kd_dealer"],
                        'tahun' => $data["tahun"],
                        'stck' => $data["stck"],
                        'plat_asli' => $data["plat_asli"],
                        'admin_samsat' => $data["admin_samsat"],
                        'bpkb' => $data["bpkb"],
                        'pengurusan_tambahan' => $data["pengurusan_tambahan"],
                        'total_bpkb' => $data["total_bpkb"],
                        'ss' => $data["ss"],
                        'banpen' => $data["banpen"],
                        'row_status' => "0",
                        'wilayah_samsat' => $data["wilayah_samsat"],
                        'tipe_customer' => $data["tipe_customer"],
                    );
                    $param["id"]    = $value->ID;
                    $param["lastmodified_by"] = 'updALL:'.substr($this->session->userdata("user_id"),0,43);
                    $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bpkb/true", $param, array(CURLOPT_BUFFERSIZE => 10));
                }
            }
        }
        return $hasil;
    }
    function get_stnk_bpkb($debug = null, $onlyTahun = null) {
        $result = array();
        $param = array(
            'kd_dealer' => $this->input->get("kd_dealer"),
            'kd_kabupaten' => $this->input->get("kd_kabupaten"),
            'kd_tipemotor' => $this->input->get("kd_tipemotor")
        );
        if ($onlyTahun == true) {
            $param["field"] = "TAHUN";
            $param["groupby"] = TRUE;
            $param["orderby"] = "TAHUN DESC";
            $param["custom"] = "TAHUN < YEAR(GETDATE())";
            unset($param["kd_kabupaten"]);
        } else {
            $param['tahun'] = $this->input->get("tahun");
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $param));
        if ($data) {
            if ($data->totaldata > 0) {
                $result = $data;
            }
            if ($debug == true && $data->totaldata>0) {
                //var_dump($data);
                echo json_encode($result->message);
            } else {
                if($debug == true){
                    echo "[]";
                }else{
                    return $result;
                }
            }
        }else{
            if($debug == true){
                echo "[]";
            }else{
                return $result;
            }
        }
    }
    public function edit_stnk_bpkb($id, $row_status) {
        $this->auth->validate_authen('dealer/stnk_bpkb');
        $data = array();
        $param = array(
            "custom" => "MASTER_STNK_BPKB.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $param_kary = array(
            'jointable' => array(
                array("MASTER_KARYAWAN as MK", "MK.NIK=MASTER_COMPANY.PIMPINAN_DEALER", "LEFT"),
            ),
            'field' => 'MASTER_COMPANY.*, MK.NAMA, MK.NIK',
            "custom" => "MASTER_COMPANY.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $param_kary));
        $this->load->view('form_edit/edit_stnkbpkb', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
        /* } */
    }
    public function update_stnk_bpkb_submit($id) {
        $this->form_validation->set_rules('kd_kabupaten', 'Kabupeten', 'required|trim');
        $this->form_validation->set_rules('kd_motor', 'Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('kd_propinsi', 'Propinsi', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Delaer', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('bbnkb', 'BBNKB', 'required|trim');
        $this->form_validation->set_rules('pkb', 'PKB', 'required|trim');
        $this->form_validation->set_rules('swdkllj', 'Swdkllj', 'required|trim');
        $this->form_validation->set_rules('stck', 'STCK', 'required|trim');
        $this->form_validation->set_rules('plat_asli', 'Plat Asli', 'required|trim');
        $this->form_validation->set_rules('bpkb', 'BPKB', 'required|trim');
        $this->form_validation->set_rules('admin_samsat', 'Admin Samsat', 'required|trim');
        $this->form_validation->set_rules('banpen', 'Banpen', 'required|trim');
        if ($this->input->post("bbnkb") == null) {
            $bbnkb = 0;
        } else {
            $bbnkb = $this->input->post("bbnkb");
        }
        if ($this->input->post("pkb") == null) {
            $pkb = 0;
        } else {
            $pkb = $this->input->post("pkb");
        }
        if ($this->input->post("swdkllj") == null) {
            $swdkllj = 0;
        } else {
            $swdkllj = $this->input->post("swdkllj");
        }
        if ($this->input->post("stck") == null) {
            $stck = 0;
        } else {
            $stck = $this->input->post("stck");
        }
        if ($this->input->post("plat_asli") == null) {
            $plat_asli = 0;
        } else {
            $plat_asli = $this->input->post("plat_asli");
        }
        if ($this->input->post("bpkb") == null) {
            $bpkb = 0;
        } else {
            $bpkb = $this->input->post("bpkb");
        }
        if ($this->input->post("pengurusan_tambahan") == null) {
            $pengurusan_tambahan = 0;
        } else {
            $pengurusan_tambahan = $this->input->post("pengurusan_tambahan");
        }
        if ($this->input->post("admin_samsat") == null) {
            $admin_samsat = 0;
        } else {
            $admin_samsat = $this->input->post("admin_samsat");
        }
        if ($this->input->post("ss") == null) {
            $ss = 0;
        } else {
            $ss = $this->input->post("ss");
        }
        if ($this->input->post("banpen") == null) {
            $banpen = 0;
        } else {
            $banpen = $this->input->post("banpen");
        }
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param_backup = array(
                'id_stnkbpkb' => $this->input->post("id"),
                'kd_tipemotor' => $this->input->post("kd_motor"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'tahun' => $this->input->post("start_date"),
                'bbnkb' => $bbnkb,
                'pkb' => $pkb,
                'swdkllj' => $swdkllj,
                'total_stnk' => (int) $bbnkb + (int) $pkb + (int) $swdkllj,
                'stck' => $stck,
                'plat_asli' => $plat_asli,
                'admin_samsat' => $admin_samsat,
                'bpkb' => $bpkb,
                'pengurusan_tambahan' => $pengurusan_tambahan,
                'total_bpkb' => (int) $stck + (int) $plat_asli + (int) $admin_samsat + (int) $bpkb,
                'ss' => $ss,
                'banpen' => $banpen,
                'nik' => $this->input->post("nik"),
                'status_approve' => 0,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_bpkb_backup", $param_backup, array(CURLOPT_BUFFERSIZE => 10));
            $this->session->set_flashdata('tr-active', $id);
            $this->data_output($hasil, 'put');
        }
    }
    public function edit_stnk_bpkb_approval($id, $row_status) {
        $this->auth->validate_authen('dealer/stnk_bpkb_approval');
        $param = array(
            "custom" => "MASTER_STNK_BPKB_BACKUP.ID='" . $id . "'",
            'row_status' => $row_status
        );
        $param_kary = array(
            'jointable' => array(
                array("MASTER_KARYAWAN as MK", "MK.NIK=MASTER_COMPANY.PIMPINAN_DEALER", "LEFT"),
            ),
            'field' => 'MASTER_COMPANY.*, MK.NAMA, MK.NIK',
            "custom" => "MASTER_COMPANY.KD_DEALER='" . $this->session->userdata('kd_dealer') . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bpkb_backup", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["typemotors"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor"));
        $data["propinsi"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["karyawan"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company", $param_kary));
        $this->load->view('form_edit/edit_stnkbpkb_approval', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_stnk_bpkb_approval($id) {
        $this->form_validation->set_rules('kd_kabupaten', 'Kabupeten', 'required|trim');
        $this->form_validation->set_rules('kd_motor', 'Tipe Motor', 'required|trim');
        $this->form_validation->set_rules('kd_propinsi', 'Propinsi', 'required|trim');
        $this->form_validation->set_rules('kd_dealer', 'Delaer', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('bbnkb', 'BBNKB', 'required|trim');
        $this->form_validation->set_rules('pkb', 'PKB', 'required|trim');
        $this->form_validation->set_rules('swdkllj', 'Swdkllj', 'required|trim');
        $this->form_validation->set_rules('stck', 'STCK', 'required|trim');
        $this->form_validation->set_rules('plat_asli', 'Plat Asli', 'required|trim');
        $this->form_validation->set_rules('bpkb', 'BPKB', 'required|trim');
        $this->form_validation->set_rules('admin_samsat', 'Admin Samsat', 'required|trim');
        $this->form_validation->set_rules('banpen', 'Banpen', 'required|trim');
        if ($this->input->post("bbnkb") == null) {
            $bbnkb = 0;
        } else {
            $bbnkb = $this->input->post("bbnkb");
        }
        if ($this->input->post("pkb") == null) {
            $pkb = 0;
        } else {
            $pkb = $this->input->post("pkb");
        }
        if ($this->input->post("swdkllj") == null) {
            $swdkllj = 0;
        } else {
            $swdkllj = $this->input->post("swdkllj");
        }
        if ($this->input->post("stck") == null) {
            $stck = 0;
        } else {
            $stck = $this->input->post("stck");
        }
        if ($this->input->post("plat_asli") == null) {
            $plat_asli = 0;
        } else {
            $plat_asli = $this->input->post("plat_asli");
        }
        if ($this->input->post("bpkb") == null) {
            $bpkb = 0;
        } else {
            $bpkb = $this->input->post("bpkb");
        }
        if ($this->input->post("pengurusan_tambahan") == null) {
            $pengurusan_tambahan = 0;
        } else {
            $pengurusan_tambahan = $this->input->post("pengurusan_tambahan");
        }
        if ($this->input->post("admin_samsat") == null) {
            $admin_samsat = 0;
        } else {
            $admin_samsat = $this->input->post("admin_samsat");
        }
        if ($this->input->post("ss") == null) {
            $ss = 0;
        } else {
            $ss = $this->input->post("ss");
        }
        if ($this->input->post("banpen") == null) {
            $banpen = 0;
        } else {
            $banpen = $this->input->post("banpen");
        }
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            if ($this->input->post("status_approval") == '1') {
                $param = array(
                    'id' => $this->input->post("id_stnkbpkb"),
                    'kd_tipemotor' => $this->input->post("kd_motor"),
                    'kd_propinsi' => $this->input->post("kd_propinsi"),
                    'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'tahun' => $this->input->post("start_date"),
                    'bbnkb' => $bbnkb,
                    'pkb' => $pkb,
                    'swdkllj' => $swdkllj,
                    'total_stnk' => (int) $bbnkb + (int) $pkb + (int) $swdkllj,
                    'stck' => $stck,
                    'plat_asli' => $plat_asli,
                    'admin_samsat' => $admin_samsat,
                    'bpkb' => $bpkb,
                    'pengurusan_tambahan' => $pengurusan_tambahan,
                    'total_bpkb' => (int) $stck + (int) $plat_asli + (int) $admin_samsat + (int) $bpkb,
                    'ss' => $ss,
                    'banpen' => $banpen,
                    'row_status' => 0,
                    'modified_by' => $this->input->post("created_by")
                );
                $param_backup = array(
                    'id' => $this->input->post("id"),
                    'id_stnkbpkb' => $this->input->post("id_stnkbpkb"),
                    'kd_tipemotor' => $this->input->post("kd_motor"),
                    'kd_propinsi' => $this->input->post("kd_propinsi"),
                    'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'tahun' => $this->input->post("start_date"),
                    'bbnkb' => $bbnkb,
                    'pkb' => $pkb,
                    'swdkllj' => $swdkllj,
                    'total_stnk' => (int) $bbnkb + (int) $pkb + (int) $swdkllj,
                    'stck' => $stck,
                    'plat_asli' => $plat_asli,
                    'admin_samsat' => $admin_samsat,
                    'bpkb' => $bpkb,
                    'pengurusan_tambahan' => $pengurusan_tambahan,
                    'total_bpkb' => (int) $stck + (int) $plat_asli + (int) $admin_samsat + (int) $bpkb,
                    'ss' => $ss,
                    'banpen' => $banpen,
                    'nik' => $this->input->post("nik"),
                    'status_approve' => $this->input->post("status_approval"),
                    'row_status' => 0,
                    'modified_by' => $this->session->userdata('user_id')
                );
                $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bpkb", $param, array(CURLOPT_BUFFERSIZE => 10));
                $hasil2 = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bpkb_backup", $param_backup, array(CURLOPT_BUFFERSIZE => 10));
                $this->session->set_flashdata('tr-active', $id);
                $this->data_output($hasil, 'put');
            } else {
                $param_backup = array(
                    'id' => $this->input->post("id"),
                    'id_stnkbpkb' => $this->input->post("id"),
                    'kd_tipemotor' => $this->input->post("kd_motor"),
                    'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                    'kd_dealer' => $this->input->post("kd_dealer"),
                    'tahun' => $this->input->post("start_date"),
                    'bbnkb' => $bbnkb,
                    'pkb' => $pkb,
                    'swdkllj' => $swdkllj,
                    'total_stnk' => (int) $bbnkb + (int) $pkb + (int) $swdkllj,
                    'stck' => $stck,
                    'plat_asli' => $plat_asli,
                    'admin_samsat' => $admin_samsat,
                    'bpkb' => $bpkb,
                    'pengurusan_tambahan' => $pengurusan_tambahan,
                    'total_bpkb' => (int) $stck + (int) $plat_asli + (int) $admin_samsat + (int) $bpkb,
                    'ss' => $ss,
                    'banpen' => $banpen,
                    'nik' => $this->input->post("nik"),
                    'status_approve' => $this->input->post("status_approval"),
                    'row_status' => 0,
                    'created_by' => $this->session->userdata('user_id')
                );
                $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bpkb_backup", $param_backup, array(CURLOPT_BUFFERSIZE => 10));
                $this->session->set_flashdata('tr-active', $id);
                $this->data_output($hasil, 'put');
            }
        }
    }
    public function delete_stnk_bpkb($id) {
        $this->auth->validate_authen('dealer/stnk_bpkb');
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/stnkbpkb/stnk_bpkb", $param));
        redirect(base_url('dealer/stnk_bpkb'));
        /*if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('dealer/stnk_bpkb')
            );
            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );
            $this->output->set_output(json_encode($data_status));
        }*/
    }
    public function stnk_bpkb_typeahead() {
        $param=array(

        );
        $data_message=array();
        $data = json_decode($this->curl->simple_get(API_URL . "api/stnkbpkbb/stnk_bpkb"));
        if($data){
            if($data->totaldata >0){
                foreach ($data["list"]->message as $key => $message) {
                    $data_message[1][$key] = $message->KD_TIPEMOTOR;
                }
            }
        }
        
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    public function stnk_bpkb_backup_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "api/stnkbpkbb/stnk_bpkb_backup"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[1][$key] = $message->KD_TIPEMOTOR;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    public function petd_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/petd"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_MAINDEALER;
            $data_message[1][$key] = $message->NOPMD_KE_AHM;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    //pdetd
    public function pdetd() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'part_deskripsi' => $this->input->get('part_deskripsi'),
            'field' => '*',
            'orderby' => 'TRANS_PDETD.ID desc',
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdetd", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/pdetd/view', $data);
    }
    public function add_pdetd() {
        $this->auth->validate_authen('dealer/pdetd');
        $this->load->view('report/pdetd/add');
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function import_pdetd() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('file', 'File', 'callback_notEmpty');
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
                if ($_FILES['file']['size'] > 0) {
                    $file = fopen($filename, "r");
                    $is_header_removed = FALSE;
                    while (($importdata = fgetcsv($file, 1000000, ";")) !== FALSE) {
                        $param = array(
                            'kd_dealer' => !empty($importdata[0]) ? $importdata[0] : '',
                            'no_podealer_ke_md' => !empty($importdata[1]) ? $importdata[1] : '',
                            'tglpodealer_ke_md' => !empty($importdata[2]) ? $importdata[2] : '',
                            'nopomd_ke_ahm' => !empty($importdata[3]) ? $importdata[3] : '',
                            'tglpomd_ke_ahm' => !empty($importdata[4]) ? $importdata[4] : '',
                            'part_number' => !empty($importdata[5]) ? $importdata[5] : '',
                            'part_deskripsi' => !empty($importdata[6]) ? $importdata[6] : '',
                            'quantitypo_awal' => !empty($importdata[7]) ? $importdata[7] : '',
                            'quantitybo_ahm' => !empty($importdata[8]) ? $importdata[8] : '',
                            'etdahm_awal' => !empty($importdata[9]) ? $importdata[9] : '',
                            'etdahm_revised' => !empty($importdata[10]) ? $importdata[10] : '',
                            'nopesanan_konsumen' => !empty($importdata[11]) ? $importdata[11] : '',
                            'tglpesanan_konsumen' => !empty($importdata[12]) ? $importdata[12] : '',
                            'nama_konsumen' => !empty($importdata[13]) ? $importdata[13] : '',
                            'notel_konsumen' => !empty($importdata[14]) ? $importdata[14] : '',
                            'row_status' => 0,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $hasil - $this->curl->simple_post(API_URL . "api/laporan/pdetd/", $param, array(CURLOPT_BUFFERSIZE => 10));
                    }
                    if ($hasil <= 0) {
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong while saving your data';
                    } else {
                        $response['status'] = 'success';
                        $response['message'] = 'Succesfully added new record';
                    }
                    fclose($file);
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong while trying to communicate with the server';
            }
        }
        $data = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('dealer/pdetd'));
    }
    function edit_pdetd($id) {
        $this->auth->validate_authen('dealer/pdetd');
        $param = array(
            "custom" => "TRANS_PDETD='" . $id . "'",
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdetd", $param));
        $this->template->site('report/petd/edit', $data);
    }
    public function update_pdetd() {
        $this->form_validation->set_rules('kd_maindealer', 'Main Dealer', 'required|trim');
        $this->form_validation->set_rules('nopodealer_ke_md', 'No PO Dealer ke Main Dealer', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post('id'),
                'kd_dealer' => $this->input->post('kd_dealer'),
                'nopodealer_ke_md' => $this->input->post('nopodealer_ke_md'),
                'tglpodealer_ke_md' => $this->input->post('tglpodealer_ke_md'),
                'nopomd_ke_ahm' => $this->input->post('nopomd_ke_ahm'),
                'tglpomd_ke_ahm' => $this->input->post('tglpomd_ke_ahm'),
                'part_number' => $this->input->post('part_number'),
                'part_deskripsi' => $this->input->post('part_deskripsi'),
                'quantitypo_awal' => $this->input->post('quantitypo_awal'),
                'quantitybo_ahm' => $this->input->post('quantitypo_ahm'),
                'etdahm_awal' => $this->input->post('etdahm_awal'),
                'etdahm_revised' => $this->input->post('etdahm_revised'),
                'nopesanan_konsumen' => $this->input->post('nopesanan_konsumen'),
                'tglpesanan_konsumen' => $this->input->post('tglpesanan_konsumen'),
                'nama_konsumen' => $this->input->post('nama_konsumen'),
                'notel_konsumen' => $this->input->post('notel_konsumen'),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/laporan/pdetd", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr_active', $data->message);
            $this->data_output($hasil, 'put');
        }
    }
    public function pdetd_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/pdetd"));
        foreach ($data["list"]->message as $key => $message) {
            //$data_message[0][$key] = $message->KD_MAINDEALER;
            $data_message[0][$key] = $message->NOPODEALER_KE_MD;
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
            case 'reinst':
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Data Sudah sudah pernah di input menunggu di approve",
                        'location' => $location
                    );
                    $this->output->set_output(json_encode($result));
                }
                break;
        }
    }
}