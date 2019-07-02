<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reminder_booking extends CI_Controller {

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



    public function service_reminder() {

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
        $this->template->site('Reminder_booking/service_reminder', $data);
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


        $this->load->view('reminder_booking/service_reminder_add', $data);
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

    public function service_booking($data_only=null)
    {
        $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_CUST_BOOKING.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_CUST_BOOKING.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'keyword' => $this->input->get('keyword'),
            'custom' => $kd_dealer
        );
        if($this->input->get("np")){
            $param=array(
                'no_polisi' => $this->input->get("np")
            );
        }   
        $param['jointable']= array(array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_CUST_BOOKING.KD_CUSTOMER", "LEFT"));
        $param['field']="U.*, TRANS_CUST_BOOKING.*";
        $param['orderby'] = 'TRANS_CUST_BOOKING.WAKTU_SERVIS DESC';

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/cust_booking", $param));
        if($data_only){
            if($data["list"]){
                echo json_encode($data["list"]);
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
            $this->template->site('Reminder_booking/service_booking', $data);
        }

    }


    public function add_service_booking($data_only=null){

        $data = array();

        $this->auth->validate_authen('reminder_booking/service_booking');
        if($this->input->get('no_trans')){
            $param = array(
                'no_trans' => $this->input->get('no_trans'),
                'field' => "TRANS_CUST_BOOKING.*"
            );

            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/cust_booking", $param));
        }
        if($data_only){
            if(isset($data["list"])){
                echo json_encode($data["list"]);
            }
        }else{
            $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
            $this->load->view('reminder_booking/service_booking_add', $data);
            $html = $this->output->get_output();
            
            $this->output->set_output(json_encode($html));
        }
        
    }

    public function get_datacustmotor()
    {
        $no_polisi = $this->input->get("no_polisi");
        //echo 'No Polisi : '.$no_polisi; exit();
        $param=array(
            'no_polisi' => $no_polisi,
            'jointable' => array(
                array("TRANS_SJMASUK AS SJ" , "SJ.NO_RANGKA=TRANS_STNK_BUKTI.NO_RANGKA", "LEFT")
            ),
            'field'     => "TRANS_STNK_BUKTI.*, SJ.THN_PERAKITAN, SJ.KD_TYPEMOTOR, SJ.KD_WARNA"
            
        );
        //var_dump($param); exit();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bukti", $param));
        // var_dump( $data["list"]); exit();
        $this->output->set_output(json_encode($data));
    }

    public function service_booking_simpan()
    {
        $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_fu('BO'));
        $tglsrv =  tglToSql($this->input->post("waktu_servis"));
        $jamsrv =  $this->input->post("waktu_servis2");
        $jam = date('H', strtotime($jamsrv));
        $tgl = new DateTime(date('Y-m-d', strtotime($tglsrv)));
        $today = new DateTime(date('Y-m-d', strtotime('today')));
        $diff = $tgl->diff($today)->format("%a");
        
        if ($jam < 8 || $jam >= 16 || $diff > 1) {
           $hasil = array(
                    'status' => false,
                    'message' => "Booking hanya boleh HARI INI atau BESOK antara jam 08:00 - 16:00",
                    'location' => ""
                    );
            $this->output->set_output(json_encode($hasil));
        }else{
            $param = array(
                'tgl_trans' => tglToSql($this->input->post("tgl_trans")),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'no_trans' => $ntrans,
                'kd_customer' => $this->input->post("kd_customer"),
                'nama_pemilik' => $this->input->post("nama_pemilik"),
                'nama_customer' => $this->input->post("nama_customer"),
                'no_polisi' => strtoupper($this->input->post("no_polisi")),
                'no_mesin' => $this->input->post("no_mesin"),
                'no_rangka' => $this->input->post("no_rangka"),
                'tahun_kendaraan' => $this->input->post("tahun_kendaraan"),
                'no_telepon' => $this->input->post("no_telepon"),
                'waktu_servis' => $tglsrv.' '.$jamsrv,
                'tipe_motor' => $this->input->post("tipe_motor"),
                'keluhan_cust' => $this->input->post("keluhan_cust"),
                'kd_tipepkb' => $this->input->post("kd_tipepkb"),
                'tipe_manual' => $this->input->post("tipe_manual"),
                'alamat' => $this->input->post("alamat"),
                'email' => $this->input->post("email"),
                'keterangan' => $this->input->post("keterangan"),
                'alasan' => $this->input->post("alasan"),
                'created_by' => $this->session->userdata('user_id'),
            );
            // var_dump($param);exit();
            $waktu_servis = date("Y-m-d H:i:s ", strtotime($param['waktu_servis']));
            $paramget=array(
                'custom' => "NO_TRANS != '".$ntrans."' AND DATEPART(HOUR, WAKTU_SERVIS) >= DATEPART(HOUR, '".$waktu_servis."') AND (DATEPART(HOUR, WAKTU_SERVIS) < (DATEPART(HOUR, '".$waktu_servis."')+1)) AND DATEPART(YEAR, WAKTU_SERVIS) = DATEPART(YEAR, '".$waktu_servis."') AND DATEPART(MONTH, WAKTU_SERVIS) = DATEPART(MONTH, '".$waktu_servis."') AND DATEPART(DAY, WAKTU_SERVIS) = DATEPART(DAY, '".$waktu_servis."')"
                );
            $slot = json_decode($this->curl->simple_get(API_URL . "/api/service/cust_booking", $paramget));
            // var_dump($slot->totaldata);exit();

            if ($slot->totaldata >= 3) {          
                $hasil = array(
                        'status' => false,
                        'message' => "Slot sudah penuh, Silakan pilih jam lain",
                        'location' => ""
                        );
                $this->output->set_output(json_encode($hasil));
            } else {
                $hasil =  $this->curl->simple_post(API_URL."/api/service/cust_booking", $param, array(CURLOPT_BUFFERSIZE => 100));
                // var_dump($hasil);exit();

                $method = "post";
                if(json_decode($hasil)->recordexists==TRUE){
                    // var_dump($param);exit();
                    $hasil= $this->curl->simple_put(API_URL."/api/service/cust_booking",$param, array(CURLOPT_BUFFERSIZE => 100)); 
                    $method = "put";
                    
                }

                  $data = json_decode($hasil);

                  $this->data_output($hasil, $method); 
            }
        }

      
    }

    public function service_booking_hapus($id)
    {
        $param = array(
            'id' => $id,
            'lastmodified_by'=> $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/service/cust_booking",$param));

        $this->data_output($data, 'delete', base_url('reminder_booking/service_booking'));

    }

    public function service_booking_cancel($id)
    {   
        $status = 0;
        $param = array(
            'id' => $id,
            'status_booking' => $status,
            'lastmodified_by'=> $this->session->userdata('user_id')
        );
        
        $hasil= $this->curl->simple_put(API_URL."/api/service/cust_booking_cancel",$param, array(CURLOPT_BUFFERSIZE => 10));

        $method = "put";
        $this->data_output($hasil, $method); 
    }

    public function get_inputpicker($filter = false)
    {
        $data_fu = array();

        if($this->input->get('no_polisi') == null):

            $offset = ($this->input->get('p')-1)*$this->input->get('per_page');

            $param['custom']   = "NO_POLISI != ''";
            $param['keyword']   = $this->input->get('q');
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');

            $sj = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sopart_customer/true", $param));


            if(!empty($sj) && (is_array($sj->message) || is_object($sj->message))):
                $data_fu = $sj->message;
            endif;

            $param_totdata = array(
                'custom' => "NO_POLISI != ''", 
                'keyword' => $this->input->get('q'), 
                'field' => "COUNT(*) AS TOTAL", 
            );

            $totaldata = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sopart_customer/true", $param_totdata));

            $data = array(
                'p' => $this->input->get('p'), 
                'count' => $totaldata->message[0]->TOTAL, 
                'per_page' => $this->input->get('per_page'), 
                'data' => $data_fu
            );

            // var_dump($totaldata);exit;


        else:
            $param['custom'] = "NO_POLISI = '".$this->input->get('no_polisi')."'";

            $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sopart_customer/true", $param));

        endif;

        $this->output->set_output(json_encode($data));
        // return $data_fu;


    }


    function autogenerate_fu($kd_docno,$debug=null) {
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
        if($debug){  echo $no_trans;} else{ return $no_trans;}
    }

    /**
     * [add_customer description]
     */


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
