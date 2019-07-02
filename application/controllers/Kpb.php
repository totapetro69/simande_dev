<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kpb extends CI_Controller {

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
    public function list_claim() {
        $data = array();


        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_KPB_VALIDASI.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_KPB_VALIDASI.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";


        // $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TGL_SERVICE,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SERVICE,112) between '" . tglToSql($data['tgl_periode_awal']) . "' AND '" . tglToSql($data['tgl_periode_akhir']) . "'" ;

        $param = array(
            // 'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'TRANS_KPB_VALIDASI.NO_KPB',
            'custom' =>  "TRANS_KPB_VALIDASI.STATUS_KPB = 1 AND ".$kd_dealer,
            // 'orderby' => 'TRANS_KPB_VALIDASI.ID desc',
            'groupby'   => TRUE
        );

        if($this->input->get('keyword')){
            $param['custom'] .= " AND CONCAT(KD_MESIN,NO_MESIN) = '".$this->input->get('keyword')."'";
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));


        $param_detail = array(
            // 'keyword' => $this->input->get('keyword'),  
            'custom' =>  "TRANS_KPB_VALIDASI.STATUS_KPB = 1 AND ".$kd_dealer,
            'orderby' => 'TRANS_KPB_VALIDASI.ID desc'
        );

        if($this->input->get('keyword')){
            $param_detail['custom'] .= " AND CONCAT(KD_MESIN,NO_MESIN) = '".$this->input->get('keyword')."'";
        }

        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param_detail));

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        /* var_dump( $data["list"]);
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

        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('kpb/list_claim', $data);
    }

    public function validasi_kpb() {
        $data = array();



        $date_now = date('Y-m-d');
        // $date_now = "2018-03-04";

        // $tgl_periode = date("d/m/Y", strtotime('monday this week', strtotime($date_now)));
        // $tgl_periode = date("d/m/Y", strtotime('monday this week', strtotime($date_now)));

        $date_min7 = tglfromSql(getPrevDays($date_now,8));



        $data['status_cabang'] = $this->session->userdata('status_cabang');

        $data['tgl_periode_awal'] = date("d/m/Y", strtotime('monday this week', strtotime(tglToSql($date_min7))));
        $data['tgl_periode_akhir'] = tglfromSql(getNextDays(tglToSql($data['tgl_periode_awal']), 7));

        $data['month_interval'] =  date("m",strtotime(tglToSql($data['tgl_periode_akhir']))) - date("m",strtotime(tglToSql($data['tgl_periode_awal'])));

        $data['month_end'] = date("d/m/Y", strtotime('last day of this month', strtotime(tglToSql($data['tgl_periode_awal']))));
        $data['month_start'] = date("d/m/Y", strtotime('first day of this month', strtotime(tglToSql($data['tgl_periode_akhir']))));


        // var_dump($data);exit;

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_KPB_VALIDASI.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_KPB_VALIDASI.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";


        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TGL_SERVICE,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SERVICE,112) between '" . tglToSql($data['tgl_periode_awal']) . "' AND '" . tglToSql($data['tgl_periode_akhir']) . "'" ;
        
        $param = array(
            // 'keyword' => $this->input->get('keyword'),
            // 'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            // 'limit' => 15,
            'field' => '*',
            'custom' =>  "TRANS_KPB_VALIDASI.STATUS_KPB = 0 AND ".$kd_dealer." AND ".$tgl,
            'orderby' => 'TRANS_KPB_VALIDASI.ID desc'
        );

        if($this->input->get('keyword')){
            $param['custom'] .= " AND CONCAT(KD_MESIN,NO_MESIN) = '".$this->input->get('keyword')."'";
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));

        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }


        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
     /*
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => base_url() . 'kpb/kpb_validasi?keyword=' . $param['keyword'] . '&row_status=' . $param['row_status'], // . $this->session->userdata("kd_dealer"),
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);

        $data['pagination'] = $this->pagination->create_links();*/

        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('kpb/kpb_validasi', $data);
    }

    public function edit_kpb($id) {
        $this->auth->validate_authen('kpb/validasi_kpb');

        $param = array(
            'custom' => "ID =".$id
        );

        $data = array();
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/service/kpb_validasi",$param));

        if($this->input->get('status_kpb')){
            $data['update_status_kpb'] = $this->input->get('status_kpb');
        }

        // var_dump($data);exit;

        $this->load->view('kpb/kpb_edit', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }


    public function surat_pengantar() {
        $data = array();


        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_KPB_VALIDASI.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_KPB_VALIDASI.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";


        // $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TGL_SERVICE,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SERVICE,112) between '" . tglToSql($data['tgl_periode_awal']) . "' AND '" . tglToSql($data['tgl_periode_akhir']) . "'" ;

        $param = array(
            // 'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'TRANS_KPB_VALIDASI.NO_KPB',
            'custom' =>  "TRANS_KPB_VALIDASI.STATUS_KPB = 2 AND ".$kd_dealer,
            // 'orderby' => 'TRANS_KPB_VALIDASI.ID desc',
            'groupby'   => TRUE
        );

        if($this->input->get('keyword')){
            $param['custom'] .= " AND CONCAT(KD_MESIN,NO_MESIN) = '".$this->input->get('keyword')."'";
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));


        $param_detail = array(
            // 'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'custom' =>  "TRANS_KPB_VALIDASI.STATUS_KPB = 2 AND ".$kd_dealer,
            'orderby' => 'TRANS_KPB_VALIDASI.ID desc'
        );

        if($this->input->get('keyword')){
            $param_detail['custom'] .= " AND CONCAT(KD_MESIN,NO_MESIN) = '".$this->input->get('keyword')."'";
        }

        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param_detail));

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        /* var_dump( $data["list"]);
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

        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('kpb/surat_pengantar', $data);
    }
    
    public function print_surat_baru() {
        $this->auth->validate_authen('kpb/surat_pengantar');

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        
        $param = array(
            'kd_dealer' => $kd_dealer,
            'no_kpb' => $this->input->get('no_kpb'),
            'row_status' => 0,
            'field'=>"TRANS_SURAT_PENGANTAR_KPB_VIEWS.*",
            'orderby'=> "TRANS_SURAT_PENGANTAR_KPB_VIEWS.KD_MESIN"
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_surat_pengantar", $param));
        //var_dump($data["list"]); exit();
        $data['no_kpb'] = $this->input->get('no_kpb');
        $this->load->view('kpb/print_surat_baru', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }
    
    public function print_surat() {
        $this->auth->validate_authen('kpb/surat_pengantar');

        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        
        $param = array(
            'no_kpb' => $this->input->get('no_kpb'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 1,
            'custom' =>  "STATUS_KPB = 2 AND ".$kd_dealer,
            'field' => 'NO_KPB, KD_MESIN, SEQUENCE,
                        (SELECT COUNT(S.KD_MESIN) FROM TRANS_KPB_VALIDASI AS S WHERE S.NO_KPB = TRANS_KPB_VALIDASI.NO_KPB AND S.KD_MESIN = TRANS_KPB_VALIDASI.KD_MESIN AND S.SEQUENCE = TRANS_KPB_VALIDASI.SEQUENCE AND S.ROW_STATUS >= 0) AS TOTAL_ITEM',
            // 'orderby' => 'TRANS_KPB_VALIDASI.ID desc',
            'groupby'   => TRUE
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));
       
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);

        $data['pagination'] = $this->pagination->create_links();


        $this->load->view('kpb/print_surat', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }

    public function tarik_data()
    {
        $kd_dealer = $this->input->post('kd_dealer') ? "TRANS_PKB.KD_DEALER = '".$this->input->post('kd_dealer')."'" : "TRANS_PKB.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $tgl_awal = tglToSql($this->input->post("tgl_awal"));
        $tgl_akhir = tglToSql($this->input->post("tgl_akhir"));

        $param = array(
            'jointable' => array(
                array("MASTER_DEALER AS MD","MD.KD_DEALER=TRANS_PKB.KD_DEALER","LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.NO_RANGKA=TRANS_PKB.NO_RANGKA AND SJD.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SJKELUAR SJ" , "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
            ),
            'custom' => "TRANS_PKB.JENIS_KPB != 'NONKPB' AND convert(char,TRANS_PKB.TANGGAL_PKB,112) between '" . $tgl_awal . "' AND '" . $tgl_akhir . "' AND ".$kd_dealer."AND TRANS_PKB.NO_PKB NOT IN (SELECT S.NO_PKB FROM TRANS_KPB_VALIDASI AS S WHERE convert(char,S.TGL_SERVICE,112) between '". $tgl_awal . "' AND '" . $tgl_akhir . "' AND S.ROW_STATUS >= 0 AND S.NO_RANGKA IS NOT NULL)",
            'orderby' => 'TRANS_PKB.ID desc',
            'field' => 'TRANS_PKB.*, SP.TGL_SO AS TGL_BELI, MD.KD_MAINDEALER, MD.KD_DEALERAHM,
                        "CASE WHEN (SELECT COUNT(S.NO_PKB) FROM TRANS_KPB_VALIDASI AS S WHERE S.NO_PKB = TRANS_PKB.NO_PKB AND convert(char,S.TGL_SERVICE,112) between \''. $tgl_awal . '\' AND \'' . $tgl_akhir . '\' AND S.ROW_STATUS >= 0) > 0 THEN 1 ELSE 0 END DATA_PERPERIODE"'
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));

        if($data && (is_array($data->message) || is_object($data->message))){

            $no_kpb = $this->getnopo();

            $hasil = array();

            foreach ($data->message as $key => $value) {
                    $param_kpb = array(
                        'no_kpb' => $no_kpb,
                        'kd_maindealer' => $value->KD_MAINDEALER,
                        'kd_dealer' => $value->KD_DEALER,
                        'kd_dealerahm' => $value->KD_DEALERAHM,
                        'no_pkb' => $value->NO_PKB,
                        'kd_mesin' => substr($value->NO_MESIN,0,5),
                        'no_mesin' => substr($value->NO_MESIN,-7),
                        'no_rangka' => $value->NO_RANGKA,
                        'tgl_beli' => tglfromSql($value->TGL_BELI),
                        'sequence' => substr($value->JENIS_KPB,3,1),
                        'km_service' => $value->KM_MOTOR,
                        'tgl_service' => tglfromSql($value->TANGGAL_PKB),
                        'motor_luar' => $value->PEMBELIAN_MOTOR == 'Dealer Sendiri'?NULL:'*',
                        'buku_baru' => 0,
                        'status_kpb' => 0,
                        'created_by' => $this->session->userdata('user_id')
                    );

                    $hasil =  $this->curl->simple_post(API_URL . "/api/service/kpb_validasi", $param_kpb, array(CURLOPT_BUFFERSIZE => 100));

            }
        }
        else{

            $hasil = '{"status":false,"message":"Data pada periode ini sudah tidak ada"}';
        }

        $this->data_output($hasil, 'post');
    }

    public function validasi_data()
    {
        $detail = json_decode($this->input->post("detail"),true);

        for ($i=0; $i < count($detail); $i++) { 

            $param = array(
                'id' => $detail[$i]['id'],
                'status_kpb' => $detail[$i]['status_kpb'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );

            $hasil = $this->curl->simple_put(API_URL . "/api/service/kpb_validasi_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        // var_dump($param);exit;
        }
        $this->data_output($hasil, 'put');
    }

    public function claim_data()
    {
        $detail = json_decode($this->input->post("detail"),true);

        $path_file = 'assets/uploads/KPB-FILE'.$this->session->userdata('kd_dealer').date('Ymd').'-'.time();
        // var_dump($nama_file);exit;
        
        if (!is_dir($path_file))
        {
            mkdir($path_file, 0777, true);
        }

        for ($i=0; $i < count($detail); $i++) { 

            $param = array(
                'no_kpb' => $detail[$i]['no_kpb'],
                'custom' => 'STATUS_KPB = 1',
                'field' => 'NO_KPB, KD_MESIN, SEQUENCE',
                'groupby' => TRUE
            );

            $hasil = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param, array(CURLOPT_BUFFERSIZE => 10)));

            if($hasil && (is_array($hasil->message) || is_object($hasil->message))){
                $hasil = $this->cetak_sdkpb($hasil, $path_file);
            }
            else{
                $hasil = '{"status":false,"message":"Data pada periode ini sudah tidak ada"}';
            }
        }

        if ($hasil == true){

            $data_return = array(
                'status' => true, 
                'message' => 'data berahasil didownload', 
                'file' => base_url().'kpb/download_udkpb?namafile='.$path_file

            );
        }


            // var_dump($cetak_sdkpb);exit;
        $this->output->set_output(json_encode($data_return));
        // $this->data_output($hasil, 'post');

    }

    public function update_kpb($id)
    {
        $param = array(
            'id' => $id,
            'kd_maindealer' => $this->input->post('kd_maindealer'),
            'kd_dealer' => $this->input->post('kd_dealer'),
            'kd_dealerahm' => $this->input->post('kd_dealerahm'),
            'no_pkb' => $this->input->post('no_pkb'),
            'kd_mesin' => substr($this->input->post('no_mesin'),0,5),
            'no_mesin' => substr($this->input->post('no_mesin'),-7),
            'no_rangka' => $this->input->post('no_rangka'),
            'no_kpb' => $this->input->post('no_kpb'),
            'tgl_beli' => $this->input->post('tgl_beli'),
            'sequence' => $this->input->post('sequence'),
            'km_service' => $this->input->post('km_service'),
            'tgl_service' => $this->input->post('tgl_service'),
            'motor_luar' => $this->input->post('motor_luar')?'*':'',
            'buku_baru' => $this->input->post('buku_baru'),
            'status_kpb' => $this->input->post('status_kpb'),
            'revisi' => $this->input->post('revisi'),
            'lastmodified_by' => $this->session->userdata("user_id")
        );

        // var_dump($this->input->post('motor_luar'));exit;

        $hasil = $this->curl->simple_put(API_URL . "/api/service/kpb_validasi", $param, array(CURLOPT_BUFFERSIZE => 10));
        // var_dump($param);exit;
        $this->session->set_flashdata('tr-active', $id);

        $this->data_output($hasil, 'put');

    }


    public function cetak_sdkpb($hasil, $path_file)
    {

        foreach ($hasil->message as $key => $row) {
            $param = array(
                'no_kpb' => $row->NO_KPB,
                'custom' => "STATUS_KPB = 1 AND KD_MESIN = '".$row->KD_MESIN."' AND SEQUENCE ='".$row->SEQUENCE."'" 
            );

            $data = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param, array(CURLOPT_BUFFERSIZE => 10)));

            $createfile_sdkpb = $this->createfile_sdkpb($data, $path_file);
        }

        return true;

    }


    public function createfile_sdkpb($data, $path_file)
    {
        // $data=array();
        $namafile="";
        $isifile="";

        // var_dump($data);exit;

        if($data && (is_array($data->message) || is_object($data->message))):
            foreach($data->message as $key => $row){

                switch ($row->SEQUENCE) {
                    case '1':
                        $SERVICE_KE = 'A';
                        break;
                    
                    case '2':
                        $SERVICE_KE = 'B';
                        break;
                    
                    case '3':
                        $SERVICE_KE = 'C';
                        break;
                    
                    case '4':
                        $SERVICE_KE = 'D';
                        break;
                }

                $revisi = $row->REVISI >= 1 ?'REVISI'.$row->REVISI:'';

                $namafile=  $row->KD_MAINDEALER."-".$row->KD_DEALERAHM."-".$row->NO_KPB."-".$row->KD_MESIN."-".$SERVICE_KE."-".date('dmYHis').$revisi.".sdkpb";

                $isifile .= $row->KD_MAINDEALER.";";
                $isifile .= $row->KD_DEALERAHM.";";
                $isifile .= $row->KD_MESIN.$row->NO_MESIN.";";
                $isifile .= ($row->BUKU_BARU?$row->BUKU_BARU:0).";";
                // $isifile .= tglToSql(tglfromSql($row->TGL_BELI)).";";
                $isifile .= date("dmY", strtotime($row->TGL_BELI) ).";";
                $isifile .= $row->SEQUENCE.";";
                $isifile .= $row->KM_SERVICE.";";
                $isifile .= date("dmY", strtotime($row->TGL_SERVICE) ).";";

                $isifile .= $row->KD_DEALERAHM."-".$row->NO_KPB."-".$row->KD_MESIN."-".$SERVICE_KE.";";

                $isifile .= $row->MOTOR_LUAR.";";
                $isifile .= ($row->MOTOR_LUAR == '*'?$row->NO_RANGKA:'').";".PHP_EOL;
                // $isifile .= $row->STATUS_SJ.";";
            }
        endif;
       
        $this->load->helper('file');

        if ( write_file(FCPATH.$path_file.'/'.$namafile, $isifile) == TRUE)
        {
            foreach($data->message as $key => $rows){

                $param = array(
                    'id' => $rows->ID, 
                    'status_kpb' => 2,
                    'lastmodified_by' =>$this->session->userdata('user_id')
                );

                $data = json_decode($this->curl->simple_put(API_URL . "/api/service/kpb_validasi_status", $param));

            }

            $return = true;
        }
        else{

            $return = false;
            
        }

        return $return;
        // return $data_return;
        // $this->output->set_output(json_encode($data_return));        
    }

    public function download_udkpb()
    {
        $namafile = 'KPB-FILE'.$this->session->userdata('kd_dealer').date('Ymd').'-'.time().'.zip';
        $folder_in_zip = '/'; //root directory of the new zip file

        $path = $this->input->get('namafile').'/';
        $this->load->library(array('Zip', 'MY_Zip'));

        $this->zip->get_files_from_folder($path, $folder_in_zip);

        $this->zip->download($namafile);

    }




    public function kpbvalidasi_typeahead()
    {
        $date_now = date('Y-m-d');
        $date_min7 = tglfromSql(getPrevDays($date_now,8));

        $data['tgl_periode_awal'] = date("d/m/Y", strtotime('monday this week', strtotime(tglToSql($date_min7))));
        $data['tgl_periode_akhir'] = tglfromSql(getNextDays(tglToSql($data['tgl_periode_awal']), 7));


        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TGL_SERVICE,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SERVICE,112) between '" . tglToSql($data['tgl_periode_awal']) . "' AND '" . tglToSql($data['tgl_periode_akhir']) . "'" ;

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $status_kpb = $this->input->get('status_kpb') != '' ? "STATUS_KPB = ".$this->input->get('status_kpb'):"STATUS_KPB >= 0";

        $param = array(
            'kd_dealer' =>  $kd_dealer,
            'custom' =>  $status_kpb,
            'orderby' => 'ID desc'
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));

        // var_dump($status_kpb);exit;


        $result['keyword'] = [];
        
        if($data['list']->totaldata > 0){
        
            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->KD_MESIN.$message->NO_MESIN;
            }
            $result['keyword'] = array_merge($data_message[0]);
        }


        $this->output->set_output(json_encode($result));
    }

    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo(){
        $kd_docno = 'KPB';

        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tahun_docno'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'STNK',
            'reset_docno' => 1,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'limit'     => 1,
            'offset'    => 0
        );

        $bulan_kirim = substr($this->input->post('tahun_docno'), 3, 2);

        $nomor_po=$this->curl->simple_get(API_URL."/api/setup/docno",$param);

        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut

        if ($nomor_po == 0) {
            $nopo = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";

            $param['urutan_docno'] = $nomor_po+1;
            $this->curl->simple_post(API_URL."/api/setup/setup_docno",$param, array(CURLOPT_BUFFERSIZE => 10));

        } else {
            $nomorpo = $nomor_po+1;
            $nopo = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);


            $param['urutan_docno'] = $nomor_po;
            $this->curl->simple_put(API_URL."/api/setup/docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        }
        //var_dump($nopo);exit();
        return $nopo;
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
