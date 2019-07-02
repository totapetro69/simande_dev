<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Birojasa extends CI_Controller {

    var $API = "";

    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('zetro');
        $this->load->helper('file');
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
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function birojasa() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER AS MD", "MD.KD_DEALER=MASTER_BIROJASA.KD_DEALER","LEFT")
            ),
            "custom" => "MD.KD_DEALER='".$this->session->userdata('kd_dealer')."'",
            'field' => 'MASTER_BIROJASA.*, NAMA_DEALER',
            'orderby' => 'MASTER_BIROJASA.ID desc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/birojasa", $param));

//        var_dump($data["list"]);
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        $this->template->site('master/master_birojasa', $data);
    }

    public function add_birojasa($id=null) {
        $data = array();
 
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        if($id){
            $param=array(
                'id'   => $id
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/birojasa", $param));
        }

        $this->load->view('form_tambah/add_birojasa', $data);
        $html = $this->output->get_output();
        

        $this->output->set_output(json_encode($html));
    }

    public function add_birojasa_simpan() {
        $this->form_validation->set_rules('kd_birojasa', 'Kode Biro Jasa', 'required|trim');
        $this->form_validation->set_rules('nama_birojasa', 'Nama Biro Jasa', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'kd_birojasa' => $this->input->post("kd_birojasa"),
                'nama_birojasa' => $this->input->post("nama_birojasa"),
                'nama_pengurus' => $this->input->post("nama_pengurus"),
                'alamat' => $this->input->post("alamat"),
                'status_birojasa' => $this->input->post("status_birojasa"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/birojasa", $param, array(CURLOPT_BUFFERSIZE => 10));

            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'post', base_url('birojasa/birojasa'));
        }
    }

    public function birojasa_typeahead() {
        $param = array(
            "custom" => "MASTER_BIROJASA.KD_DEALER='".$this->session->userdata('kd_dealer')."'",
        );
            

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/birojasa",$param));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_BIROJASA;
            $data_message[1][$key] = $message->NAMA_PENGURUS;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));
    }

    public function edit_birojasa($id, $row_status) {
        $this->auth->validate_authen('birojasa/birojasa');
        $param = array(
            'jointable' => array(
                array("MASTER_DEALER MK", "MK.KD_DEALER=MASTER_BIROJASA.KD_DEALER", "LEFT")
            ),
            'field' => 'MASTER_BIROJASA.*, MK.NAMA_DEALER',
            'custom' => "MASTER_BIROJASA.ID = '" . $id . "'",
            'row_status' => $row_status
        );
        $paramD = array(
            'custom' => "MASTER_DEALER.KD_DEALER = '" .  $this->session->userdata('kd_dealer') . "'"
        );
        $data = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",$paramD));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/birojasa", $param));
       

        $this->load->view('form_edit/edit_birojasa', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function update_birojasa($id) {
        $this->form_validation->set_rules('kd_birojasa', 'Kode Biro Jasa', 'required|trim');
        $this->form_validation->set_rules('nama_birojasa', 'Nama Biro Jasa', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_birojasa' => $this->input->post("kd_birojasa"),
                'nama_birojasa' => $this->input->post("nama_birojasa"),
                'nama_pengurus' => $this->input->post("nama_pengurus"),
                'alamat' => $this->input->post("alamat"),
                'status_birojasa' => $this->input->post("status_birojasa"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'kd_dealer' => $this->input->post("kd_dealer"),
                'row_status' => html_escape($this->input->post("row_status")),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/birojasa", $param, array(CURLOPT_BUFFERSIZE => 10));
//            var_dump($hasil);            exit();
            $this->session->set_flashdata('tr-active', $id);

            $this->data_output($hasil, 'put');
        }
    }

    public function delete_birojasa($id) {
        $param = array(
            "id" => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/stnkbpkb/birojasa", $param));


        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('birojasa/birojasa')
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
     * [data_output description]
     * @param  [type] $hasil [description]
     * @return [type]        [description]
     */
    function data_output($hasil = NULL, $method = '', $location = '', $ket_id = '', $status_penerima = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil disimpan",
                        'location' => $location,
                        'ket_id' => $ket_id,
                        'status_penerima' => $status_penerima
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
                        'location' => $location,
                        'ket_id' => $ket_id,
                        'status_penerima' => $status_penerima
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
                        'message' => 'data berhasil dihapus',
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

    /**
     * Proses kasir
     * pengajuan pinjaman stnk/bpkb
     */
    function list_pengajuan_approve($typeahead = true) {
        $result = "[]";
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_maindealer' => $this->session->userdata("kd_maindealer")
            , 'custom' => " TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE IS NOT NULL AND TS.ID IN(SELECT TSD.STNK_ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.STATUS_STNK=3)"
            , 'jointable' => array(
                array("TRANS_STNK TS", "TS.NO_TRANS=TRANS_STNK_BIAYA.NO_PENGAJUAN", "LEFT"),
                array("MASTER_BIROJASA MB", "MB.KD_BIROJASA=TS.NAMA_PENGURUS", "LEFT")
            )
            , 'field' => "TS.ID,TS.NO_TRANS,MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA,TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE, TRANS_STNK_BIAYA.TOTAL_BIAYAPENGAJUAN,CASE WHEN LEFT(TS.NO_TRANS,2)='ST' THEN 'STNK' ELSE 'BPKB' END JENIS_DOC, RTRIM(CONVERT(CHAR,TS.TGL_STNK,103))TGL_STNK"
            , 'orderby' => "MB.NAMA_PENGURUS,MB.NAMA_BIROJASA"
        );
        if ($this->input->get("p")) {
            $param["custom"] .= "AND TS.NAMA_PENGURUS ='" . $this->input->get("p") . "'";
            $param["order"] = "RIGHT(TS.NO_TRANS,6)";
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_biaya", $param));
        if ($data) {
            if ($data->totaldata > 0) {
                $result = ($data->message);
            }
        }
        if ($typeahead) {
            echo json_encode($result);
        } else {
            return $result;
        }
    }

}
