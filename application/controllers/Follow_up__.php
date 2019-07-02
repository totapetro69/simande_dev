<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_up extends CI_Controller {

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

    //Customer
    /**
     * [customer description]
     * @return [type] [description]
     */
    public function call_pembeliaan() {
//        var_dump($this->session->userdata());exit;
        $data = array();
         $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SJKELUAR.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SJKELUAR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'keyword' => $this->input->get('keyword'),
            'custom' => "TRANS_SJKELUAR.STATUS_SJ = 'aproved' AND SJD.NO_RANGKA NOT IN (SELECT P.NO_FRAME FROM TRANS_FU_THANKS AS P WHERE P.ROW_STATUS >=0 AND P.NO_FRAME IS NOT NULL) AND ".$kd_dealer,
            'jointable' => array(
                array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_SJKELUAR.KD_CUSTOMER", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.KET_UNIT NOT IN('KSU','HADIAH','BARANG')", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'SJD.NO_RANGKA, U.*, TRANS_SJKELUAR.KD_CUSTOMER, TRANS_SJKELUAR.NAMA_PENERIMA, TRANS_SJKELUAR.NO_SURATJALAN, TRANS_SJKELUAR.TGL_TERIMA, TRANS_SJKELUAR.STATUS_SJ,SP.TGL_SO, convert(char,SP.TGL_SO,112) AS TGL_PEMBELIAN',
            'orderby' => 'TRANS_SJKELUAR.TGL_TERIMA asc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('followup/call_pembeliaan', $data);
    }

    public function call_thanks(){

        $data = array();
        
        $this->auth->validate_authen('follow_up/call_pembeliaan');
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SJKELUAR.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SJKELUAR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'custom' => "SJD.NO_RANGKA = '".$this->input->get('no_rangka')."' AND ".$kd_dealer,
            'jointable' => array(
                array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_SJKELUAR.KD_CUSTOMER", "LEFT"),
                array("MASTER_AGAMA MA", "MA.KD_AGAMA=U.KD_AGAMA", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.KET_UNIT NOT IN('KSU','HADIAH','BARANG')", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'SJD.NO_RANGKA, SJD.NO_MESIN, U.*, MA.NAMA_AGAMA, TRANS_SJKELUAR.NO_SURATJALAN, TRANS_SJKELUAR.TGL_TERIMA, TRANS_SJKELUAR.STATUS_SJ,SP.TGL_SO, convert(char,SP.TGL_SO,112) AS TGL_PEMBELIAN',
            'orderby' => 'TRANS_SJKELUAR.TGL_TERIMA asc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        $this->load->view('followup/call_thanks', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }

    public function metode_fu($filter_metode=null)
    {
        $param = array(
            'id' => $this->input->get('id')
        );

        if($filter_metode == null)
        {
            $param['custom'] = "(NAMA_METODE = 'SMS' OR NAMA_METODE = 'CALL')";
        }

        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/metodefu", $param));
        
        $this->output->set_output(json_encode($data->message));
    }

    public function status_metodefu()
    {
        $param = array(
            'kategori' => $this->input->get('kategori'),
            'id' => $this->input->get('id')/*,
            'custom' => "KATEGORI = 'SMS' OR KATEGORI = 'CALL'"*/
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/marketing/callvisit", $param));
        
        $this->output->set_output(json_encode($data->message));

    }

    public function followup_pembelian_simpan()
    {
        // var_dump($this->session->userdata());exit;
        // var_dump($this->input->post());exit;
        $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_fu('FUC'));

        $param = array(
            'no_trans' => $ntrans,
            'tgl_trans' => $this->input->post("tgl_trans"),
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_fu_thanks' => $ntrans,
            'kd_customer' => $this->input->post("kd_customer"),
            'honda_id' => $this->session->userdata('kd_dealerahm'),
            'kd_metodefu' => $this->input->post("kd_metodefu"),
            'kd_unitdelivery' => $this->input->post("kd_unitdelivery"),
            'no_frame' => $this->input->post("no_frame"),
            'tgl_pembelian' => $this->input->post("tgl_pembelian"),
            'nama_customer' => $this->input->post("nama_customer"),
            'tgl_lahir' => $this->input->post("tgl_lahir"),
            'no_hp' => $this->input->post("no_hp"),
            'alamat_surat' => $this->input->post("alamat_surat"),
            'kelurahan' => $this->input->post("kelurahan"),
            'kecamatan' => $this->input->post("kecamatan"),
            'kota' => $this->input->post("kota"),
            'kode_pos' => $this->input->post("kode_pos"),
            'propinsi' => $this->input->post("propinsi"),
            'agama' => $this->input->post("agama"),
            'email' => $this->input->post("email"),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $hasil =  $this->curl->simple_post(API_URL . "/api/master_hc3/fu_thanks", $param, array(CURLOPT_BUFFERSIZE => 100));
        $method = "post";
        if(json_decode($hasil)->recordexists==TRUE){
            $hasil= $this->curl->simple_put(API_URL."/api/master_hc3/fu_thanks",$param, array(CURLOPT_BUFFERSIZE => 10));  
            $method = "put";
        }
        if($hasil)
        {
            if(json_decode($hasil)->message>0){
                $post_detail = $this->fu_thanksdetail($ntrans);
            }
            
        }
        $this->data_output($hasil, $method); 
    }

    public function fu_thanksdetail($ntrans)
    {

        $param = array(
            'no_trans' => $ntrans,
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_fu_thanks' => $ntrans,
            'kd_metodefu' => $this->input->post("kd_metodefu"),
            'kd_setup_statuscall' => $this->input->post("kd_setup_statuscall"),
            'nama_metodefu' => $this->input->post("nama_metodefu"),
            'tgl_fu' => $this->input->post("tgl_fu"),
            'status_metode' => $this->input->post("status_metode"),
            'ket_thanks' => $this->input->post("ket_thanks"),
            'reminder_kpb1' => $this->input->post("reminder_kpb1"),
            'informasi_dealer' => $this->input->post("informasi_dealer"),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $hasil =  $this->curl->simple_post(API_URL . "/api/master_hc3/fu_thanks_detail", $param, array(CURLOPT_BUFFERSIZE => 100));
    }


    public function service_reminder_booking() {

        $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_FU_SERVICE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_FU_SERVICE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'keyword' => $this->input->get('keyword'),
            'custom' => $kd_dealer,
            'jointable' => array(
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.NO_RANGKA=TRANS_FU_SERVICE.NO_RANGKA AND SJD.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_SJKELUAR SJ", "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_FU_SERVICE_DETAIL FD", "FD.NO_TRANS=TRANS_FU_SERVICE.NO_TRANS AND FD.ROW_STATUS >= 0", "LEFT")
            ),
            'field' => "TRANS_FU_SERVICE.*, FD.STATUS_METODEFU, FD.HASIL_METODEFU, FD.STATUS_METODEFU2, FD.HASIL_METODEFU2, SJ.NO_SURATJALAN, SJ.TGL_TERIMA, CASE WHEN (SELECT COUNT(FUD.NO_TRANS) FROM TRANS_FU_SERVICE_DETAIL AS FUD WHERE FUD.NO_TRANS = TRANS_FU_SERVICE.NO_TRANS AND (FUD.HASIL_METODEFU = 'Servis' OR FUD.HASIL_METODEFU2 != ''))>0 THEN 1 ELSE 0 END NEXT_FU",
            'orderby' => 'SJ.TGL_TERIMA asc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_service", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('followup/service_reminder_booking', $data);
    }


    public function call_service_reminder_booking(){

        $data = array();
        $this->auth->validate_authen('follow_up/service_reminder_booking');
        if($this->input->get('no_trans'))
        {
            $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_FU_SERVICE.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_FU_SERVICE.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

            $param = array(
                'custom' => $kd_dealer,
                'no_trans' => $this->input->get('no_trans'),
                'jointable' => array(
                    array("TRANS_FU_SERVICE_DETAIL FSD" , "FSD.NO_TRANS=TRANS_FU_SERVICE.NO_TRANS AND FSD.ROW_STATUS>=0", "LEFT")
                ),
                'field' => 'FSD.*, TRANS_FU_SERVICE.*'
            );

            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_service", $param));
        }

        $param_hasilfu = array(
            'kategori' => 'Hasil FU H2' 
        );


        $data["hasil_fu"] = json_decode($this->curl->simple_get(API_URL . "/api/setup/hasil_fu", $param_hasilfu));
        
        // $this->output->set_output(json_encode($data));

        $this->load->view('followup/call_service_reminder_booking', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }

    public function get_rangka_bykpb()
    {
        $data_fu = array();

        $param = array(
            'jointable' => array(
                array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_SJKELUAR.KD_CUSTOMER", "LEFT"),
                array("MASTER_AGAMA MA", "MA.KD_AGAMA=U.KD_AGAMA", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.KET_UNIT NOT IN('KSU','HADIAH','BARANG')", "LEFT"),
                array("TRANS_STNK_BUKTI STB" , "STB.NO_RANGKA=SJD.NO_RANGKA AND STB.KETERANGAN = 'PLAT' AND STB.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'SJD.NO_RANGKA, STB.DATA_NOMOR, SJD.NO_MESIN, SJD.KET_UNIT, U.*, MA.NAMA_AGAMA, TRANS_SJKELUAR.NO_SURATJALAN, TRANS_SJKELUAR.TGL_TERIMA, TRANS_SJKELUAR.STATUS_SJ,SP.TGL_SO, convert(char,SP.TGL_SO,112) AS TGL_PEMBELIAN',
            'orderby' => 'TRANS_SJKELUAR.TGL_TERIMA asc'
        );

        if($this->input->get('no_rangka') == null):
            
            $param['custom'] = "TRANS_SJKELUAR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

            $sj = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));

            if(!empty($sj) && (is_array($sj->message) || is_object($sj->message))):
            foreach ($sj->message as $key => $value):

                $kpb = $this->get_kpb($value->NO_MESIN, $value->TGL_PEMBELIAN);


                $jml_bln=strtotime(date('Y/m/d H:i:s')) - strtotime($value->TGL_PEMBELIAN);
                $bln = round(($jml_bln / (60 * 60 * 24))/30);

                $param_fu = array(
                    'custom' => "NO_RANGKA = '".$value->NO_RANGKA."' AND JENIS_KPB = '".$kpb."'",
                );
                
                $fu = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_service", $param_fu));

                if($kpb != 'NONKPB' && $fu->totaldata == 0){
                    $data_fu[$key]['NO_RANGKA'] = $value->NO_RANGKA;
                    $data_fu[$key]['JENIS_KPB'] = $kpb;
                    $data_fu[$key]['BULAN_SERVICE'] = $bln;
                    // array_push($no_rangka, $value->NO_RANGKA.'-'.$kpb.'-'.$bln);
                }


            endforeach;
            endif;

        else:
            $param['custom'] = "SJD.NO_RANGKA = '".$this->input->get('no_rangka')."' AND TRANS_SJKELUAR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

            $data_fu['sj'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));

            if(!empty($data_fu['sj']) && (is_array($data_fu['sj']->message) || is_object($data_fu['sj']->message))):
                
                $data_fu['kpb'] = $this->get_kpb($data_fu['sj']->message[0]->NO_MESIN, $data_fu['sj']->message[0]->TGL_PEMBELIAN);



            endif;



        endif;

        $this->output->set_output(json_encode($data_fu));
        // return $data_fu;


    }

    public function followup_service_reminder_booking_simpan()
    {
        $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate_fu('FUS'));

        $param = array(
            'no_trans' => $ntrans,
            'tgl_trans' => $this->input->post("tgl_trans"),
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_fu_service_reminderh2' => $ntrans,
            'kd_metodefu' => $this->input->post("kd_metodefu"),
            'kd_customer' => $this->input->post("kd_customer"),
            'kd_motor' => $this->input->post("kd_motor"),
            'no_rangka' => $this->input->post("no_rangka"),
            'no_mesin' => $this->input->post("no_mesin"),
            'no_polisi' => $this->input->post("no_polisi"),
            'tgl_beli' => $this->input->post("tgl_pembelian"),
            'nama_stnk' => $this->input->post("nama_stnk"),
            'no_hp' => $this->input->post("no_hp"),
            'alamat_surat' => $this->input->post("alamat_surat"),
            'kelurahan_surat' => $this->input->post("kelurahan"),
            'kecamatan_surat' => $this->input->post("kecamatan"),
            'kota_surat' => $this->input->post("kota"),
            'kode_pos' => $this->input->post("kode_pos"),
            'propinsi_surat' => $this->input->post("propinsi"),
            'jenis_kpb' => $this->input->post("jenis_kpb"),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $hasil =  $this->curl->simple_post(API_URL . "/api/master_hc3/fu_service", $param, array(CURLOPT_BUFFERSIZE => 100));
        //data

        $method = "post";

        // var_dump($hasil);exit;
        if(json_decode($hasil)->recordexists==TRUE){

            $hasil= $this->curl->simple_put(API_URL."/api/master_hc3/fu_service",$param, array(CURLOPT_BUFFERSIZE => 10));  

        // var_dump($hasil);exit;
            $method = "put";
        }


        if($hasil)
        {
            if(json_decode($hasil)->message>0){

                $post_detail = $this->fu_service_reminder_booking_detail($ntrans);

            }
            
        }


        $this->data_output($hasil, $method); 
        //test


    }



    public function fu_service_reminder_booking_detail($ntrans)
    {
        $param = array(
            'no_trans' => $ntrans,
            'tgl_trans' => $this->input->post('tgl_trans'),
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_fu_service_reminderh2' => $ntrans,
            'kd_metodefu' => $this->input->post("kd_metodefu"),
            'kd_status_metodefu' => $this->input->post("kd_setup_statuscall"),
            'nama_metodefu' => $this->input->post("nama_metodefu"),
            'tgl_metodefu' => $this->input->post("tgl_fu"),
            'status_metodefu' => $this->input->post("status_metode"),
            'hasil_metodefu' => $this->input->post("hasil_metodefu"),
            'created_by' => $this->session->userdata('user_id')
        );

        $hasil =  $this->curl->simple_post(API_URL . "/api/master_hc3/fu_service_detail", $param, array(CURLOPT_BUFFERSIZE => 100));


        if(json_decode($hasil)->recordexists==TRUE){

            $param['kd_metodefu2'] = $this->input->post("kd_metodefu2");
            $param['kd_status_metodefu2'] = $this->input->post("kd_status_metodefu2");
            $param['nama_metodefu2'] = $this->input->post("nama_metodefu2");
            $param['tgl_metodefu2'] = $this->input->post("tgl_metodefu2");
            $param['status_metodefu2'] = $this->input->post("status_metode2");
            $param['hasil_metodefu2'] = $this->input->post("hasil_metodefu2");
            $param['lastmodified_by'] = $this->session->userdata('user_id');

            $hasil= $this->curl->simple_put(API_URL."/api/master_hc3/fu_service_detail",$param, array(CURLOPT_BUFFERSIZE => 10));  

        }


    }


    public function get_kpb($no_mesin, $tgl_pembelian)
    {
        $kpb = '';

        $param = array(
            "no_mesin" => substr($no_mesin,0,5)
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb", $param));


        if($data && (is_array($data->message) || is_object($data->message))):

            $jml_bln=strtotime(date('Y/m/d H:i:s')) - strtotime($tgl_pembelian);
            $bln = round(($jml_bln / (60 * 60 * 24))/30);


            if($bln <= $data->message[0]->BSE1):
                $bln_row = 1;
            elseif($data->message[0]->BSE1 <=  $bln && $bln <= $data->message[0]->BSE2):
                $bln_row = 2;
            elseif($data->message[0]->BSE2 <=  $bln && $bln <= $data->message[0]->BSE3):
                $bln_row = 3;
            elseif($data->message[0]->BSE3 <=  $bln && $bln <= $data->message[0]->BSE4):
                $bln_row = 4;
            else:
                $bln_row = 0;
            endif;

            switch ($bln_row) {
                case 1:
                $kpb = 'KPB1';
                    break;
                
                case 2:
                $kpb = 'KPB2';
                    break;
                
                case 3:
                $kpb = 'KPB3';
                    break;
                
                case 4:
                $kpb = 'KPB4';
                    break;
                
                default:
                $kpb = 'NONKPB';
                    break;
            }

        endif;

        return $kpb;
    }

    public function after_service()
    {
        $data = array();

        $param = array(
            'jointable' => array(
                array("MASTER_CUSTOMER_VIEW U", "U.KD_CUSTOMER=TRANS_FOLLOWUP_CALL_VIEW.KD_CUSTOMER", "LEFT")
            ),
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'field' => "U.*, TRANS_FOLLOWUP_CALL_VIEW.*",
            'limit' => 15
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_call_view", $param));


        $param_popup = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'custom' => 'SELISIH_TGL = 6'
        );
        $data["pop_up"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_call_view", $param_popup));
                       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $this->template->site('followup/call_afterservice', $data);
        
    }


    public function after_service_notif(){

        $data = array();
        $this->auth->validate_authen('follow_up/after_service');

        $param = array(
            'jointable' =>array(
                array("MASTER_PART MP" , "MP.PART_NUMBER=TRANS_PKB_DETAIL.KD_PEKERJAAN AND MP.ROW_STATUS>=0", "LEFT")

            ),
            "no_pkb" => $this->input->get('no_pkb'),
            "orderby" => "TRANS_PKB_DETAIL.KATEGORI asc"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail", $param));

        // $this->output->set_output(json_encode($data));
        
        $this->load->view('followup/call_afterservice_notif', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }


    function autogenerate_fu($kd_docno) {
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
