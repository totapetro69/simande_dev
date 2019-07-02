<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class After_dealing extends CI_Controller {

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

    public function add_after_dealing()
    {
        $kd_dealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");

        if($this->input->get('u')){
            $param = array(
                'no_trans' => $this->input->get('u'),
                'kd_dealer' => $kd_dealer,
                'jointable' => array(
                    array("TRANS_AFTER_DEALING_DETAIL AFD" , "AFD.NO_TRANS=TRANS_AFTER_DEALING.NO_TRANS AND AFD.ROW_STATUS >= 0", "LEFT"),
                    array("MASTER_KARYAWAN MK" , "MK.NIK=AFD.KD_SALES AND MK.ROW_STATUS >= 0", "LEFT"),
                ),
                'field' => "TRANS_AFTER_DEALING.NO_HP, 
                            TRANS_AFTER_DEALING.NO_TRANS, 
                            TRANS_AFTER_DEALING.TGL_TRANS, 
                            TRANS_AFTER_DEALING.NO_SPK, 
                            TRANS_AFTER_DEALING.KD_DEALER, 
                            TRANS_AFTER_DEALING.NAMA_CUSTOMER, 
                            TRANS_AFTER_DEALING.KD_CUSTOMER, 
                            TRANS_AFTER_DEALING.NO_RANGKA, 
                            TRANS_AFTER_DEALING.NO_MESIN, 
                            AFD.*, 
                            TRANS_AFTER_DEALING.ID, 
                            AFD.ID AS DETAIL_ID,
                            MK.NAMA AS NAMA_SALES",
            );


            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing",$param));

            $param_detail = array(
                'no_trans' => $this->input->get('u'),
                'jointable' => array(
                    array("TRANS_AFTER_DEALING AFD" , "AFD.NO_TRANS=TRANS_AFTER_DEALING_DETAIL.NO_TRANS AND AFD.ROW_STATUS >= 0", "LEFT"),
                    array("MASTER_KARYAWAN MK" , "MK.NIK=TRANS_AFTER_DEALING_DETAIL.KD_SALES AND MK.ROW_STATUS >= 0", "LEFT"),
                ),
                'field' => "AFD.NO_HP, 
                            AFD.NO_TRANS, 
                            AFD.TGL_TRANS, 
                            AFD.NO_SPK, 
                            AFD.KD_DEALER, 
                            AFD.NAMA_CUSTOMER, 
                            AFD.KD_CUSTOMER, 
                            AFD.NO_RANGKA, 
                            AFD.NO_MESIN, =
                            TRANS_AFTER_DEALING_DETAIL.*, 
                            AFD.ID, 
                            TRANS_AFTER_DEALING_DETAIL.ID AS DETAIL_ID,
                            MK.NAMA AS NAMA_SALES",
                // 'custom' => "AFD.NO_TRANS = '".$this->input->get('u')."'"
            );

            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing_detail",$param_detail));


            $param_act = array(
                'jointable' => array(
                    array("TRANS_AFTER_DEALING AFD" , "AFD.NO_SPK=TRANS_LIST_AFTER_DEALING_VIEW.NO_SPK AND AFD.ROW_STATUS >= 0", "LEFT"),
                ), 
                'field' => "TRANS_LIST_AFTER_DEALING_VIEW.TIPE AS TIPE_AKTIVITAS ,
                    TRANS_LIST_AFTER_DEALING_VIEW.KETERANGAN AS NAMA_AKTIVITAS,
                    CASE WHEN (SELECT COUNT(TD.TIPE_AKTIVITAS) FROM TRANS_AFTER_DEALING_DETAIL TD LEFT JOIN TRANS_AFTER_DEALING T ON T.NO_TRANS = TD.NO_TRANS WHERE T.NO_SPK = TRANS_LIST_AFTER_DEALING_VIEW.NO_SPK AND TD.TIPE_AKTIVITAS = TRANS_LIST_AFTER_DEALING_VIEW.TIPE AND TD.ROW_STATUS >= 0) > 0 THEN 1 ELSE 0 END STATUS_ACTIVITY",
                'custom' => "AFD.NO_TRANS = '".$this->input->get('u')."'"
            );;
            
            $data['activity'] = json_decode($this->curl->simple_get(API_URL . "/api/sales/list_after_dealing",$param_act));
        }

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramdealer = array(
            'field' => "KD_LOKASI, NAMA_LOKASI",
            'kd_dealer' =>($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );
        
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$paramdealer));
        
        // $this->output->set_output(json_encode($data));
        $this->template->site('after_dealing/add_after_dealing', $data);
    }

    public function list_activity_afterdealing()
    {
        $data = array();
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TRANS_AFTER_DEALING.TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_AFTER_DEALING.TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $param = array(
            'kd_dealer' => $kd_dealer,
            'custom' => $tgl,
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("TRANS_SPK_INDENT I" , "I.NO_SPK=TRANS_AFTER_DEALING.NO_SPK AND I.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_P_TYPEMOTOR P" , "P.KD_ITEM=I.KD_ITEM AND P.ROW_STATUS >= 0", "LEFT"),
            ),
            'field' => "TRANS_AFTER_DEALING.*, I.NO_TRANS AS KD_INDENT, P.KD_ITEM, P.NAMA_ITEM"
        );
        // $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spkindent", $param));
        $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing",$param));


        if($data['list'] && is_array($data['list']->message)){
            $queryIn = queryIndata($data['list']->message, 'NO_TRANS');
        }
        else{
            $queryIn = "('')";
        }

        $param_detail = array(
            'jointable' => array(
                array("MASTER_KARYAWAN K" , "K.NIK=TRANS_AFTER_DEALING_DETAIL.KD_SALES AND K.ROW_STATUS >= 0", "LEFT"),
            ),
            'custom' => 'TRANS_AFTER_DEALING_DETAIL.NO_TRANS IN'.$queryIn,
            'field' => "TRANS_AFTER_DEALING_DETAIL.*, K.NAMA"
        );

        $data['detail'] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing_detail",$param_detail));

        // var_dump($queryIn);exit;

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        
        // $this->output->set_output(json_encode($data));
        $this->template->site('after_dealing/list_activity_afterdealing',$data);
    }

    public function activity_typeahead()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $param=array(
            'kd_dealer' => $kd_dealer,
            'row_status'=>0,
            'field' => 'NO_TRANS, NAMA_CUSTOMER, NO_MESIN',
            'groupby' => true
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing", $param));
        $data_message="";
        if(is_array($data["list"]->message)){
            foreach ($data["list"]->message as $key => $message) {
               $data_message[0][$key] = $message->NO_TRANS;
               $data_message[1][$key] = $message->NAMA_CUSTOMER;
               $data_message[2][$key] = $message->NO_MESIN;
            }
            $data_message = array_merge($data_message[0], $data_message[1], $data_message[2]);
        }
        $result['keyword']=$data_message;
        $this->output->set_output(json_encode($result));
    }

    public function list_after_dealing()
    {
        $kd_dealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));

        $param_karyawan = array(
            'nik' => $this->session->userdata('user_id'),
            'kd_jabatan' => 'CRM'
        );

        $sales = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$param_karyawan));

        $data["sales"] = '';

        if ($sales && is_array($sales->message)) {
            // var_dump($sales->message[0]);exit;
            $data["sales"] = $sales->message[0]->NIK;
        }

        $param = array(
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'kd_sales' => $data["sales"]?$data["sales"]:$this->input->get('kd_sales'),
            'jointable' => array(
                array("TRANS_AFTER_DEALING AFD" , "AFD.NO_TRANS=TRANS_AFTER_DEALING_DETAIL.NO_TRANS AND AFD.ROW_STATUS >= 0", "LEFT"),
            ),
            'field' => "AFD.NAMA_CUSTOMER, AFD.NO_HP, TRANS_AFTER_DEALING_DETAIL.*",
            'custom' => "AFD.kd_dealer = '".$kd_dealer."'"
        );

        if($this->input->get('status_aktivitas') != ''){
            $param['custom'] = "AFD.kd_dealer = '".$kd_dealer."' AND TRANS_AFTER_DEALING_DETAIL.STATUS_AKTIVITAS = ".$this->input->get('status_aktivitas');
        }


        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing_detail",$param));
        

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        // $this->output->set_output(json_encode($data));

        $this->template->site('after_dealing/list_after_dealing', $data);
    }

    public function get_salespeople($dataonly=null)
    {

        $offset = ($this->input->get('p')-1)*$this->input->get('per_page');
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");


        $param_karyawan = array(
            'nik' => $this->session->userdata('user_id'),
            'kd_jabatan' => 'CRM'
        );

        $sales = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$param_karyawan));
        $nik = '';

        if ($sales && is_array($sales->message)) {
            $nik = $sales->message[0]->NIK;
        }

        $param = array(
            'nik' => $nik, 
            'kd_dealer' => $kd_dealer, 
            'kd_jabatan' => 'CRM'
        );

        if($this->input->get('q')){
            $param['custom']   = "(NIK LIKE '%".$this->input->get('q')."%' OR NAMA LIKE '%".$this->input->get('q')."%')";
            // $param['custom']   = "(PART_NUMBER LIKE '%".$this->input->get('q')."%' OR PART_DESKRIPSI LIKE '%".$this->input->get('q')."%')";
        }else{
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');
        }

        $list = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan",$param));
        
        $data=array();
        if($list){
            if($list->totaldata >0) {
                $data = array(
                    'p' => $this->input->get('q'), 
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => $list->message
                );
            }else{
                $data=array(
                    'p' => $this->input->get('p'),
                    'count' => $list->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => array()
                );
            }
        }else{
            $data=array(
                'p' => $this->input->get('p'), 
                'count' => 0,//$list->totaldata, 
                'per_page' => $this->input->get('per_page'), 
                'data' => array()
            );
        }
        if($dataonly){
            $this->output->set_output(json_encode($list));
        }
        else{
            $this->output->set_output(json_encode($data));
        }

    }


    public function get_list_afterdealing($dataonly=null, $detail = null)
    {

        $offset = ($this->input->get('p')-1)*$this->input->get('per_page');
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $param = array(
            'kd_dealer' => $kd_dealer
        );

        if($detail == null){
            if ($this->input->get('no_spk')) {
                $param['custom'] = "NO_SPK ='".$this->input->get('no_spk')."'";
            }
            else{
                $param['custom'] = "TIPE NOT IN(SELECT DT.TIPE_AKTIVITAS FROM TRANS_AFTER_DEALING D LEFT JOIN TRANS_AFTER_DEALING_DETAIL DT ON DT.NO_TRANS = D.NO_TRANS WHERE D.NO_SPK = TRANS_LIST_AFTER_DEALING_VIEW.NO_SPK AND DT.ROW_STATUS >=0 AND D.ROW_STATUS >= 0)";
                // $param['custom'] = "NO_SPK NOT IN(SELECT D.NO_SPK FROM TRANS_AFTER_DEALING D WHERE D.NO_SPK = NO_SPK AND D.ROW_STATUS >= 1 )";
            }
            $param['field']     = "KD_CUSTOMER ,NAMA_CUSTOMER, NO_SPK";
            $param['groupby']   = true;
        }
        else{
            $param['no_spk']    = $this->input->get('no_spk');

            $param['jointable'] = array(
                    array("TRANS_AFTER_DEALING AFD" , "AFD.NO_SPK=TRANS_LIST_AFTER_DEALING_VIEW.NO_SPK AND AFD.ROW_STATUS >= 0", "LEFT"),
                );
            $param['field']     = "TRANS_LIST_AFTER_DEALING_VIEW.*, AFD.NO_TRANS";
        }

        if($this->input->get('q')){
            $param['keyword']   = $this->input->get('q');
        }else{
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');
        }

        $result['list'] = json_decode($this->curl->simple_get(API_URL . "/api/sales/list_after_dealing",$param));

        $result['card'] = '';

        if($detail){
            $param['field'] = "TIPE AS TIPE_AKTIVITAS ,KETERANGAN AS NAMA_AKTIVITAS,
                    CASE WHEN (SELECT COUNT(TD.TIPE_AKTIVITAS) FROM TRANS_AFTER_DEALING_DETAIL TD LEFT JOIN TRANS_AFTER_DEALING T ON T.NO_TRANS = TD.NO_TRANS WHERE T.NO_SPK = TRANS_LIST_AFTER_DEALING_VIEW.NO_SPK AND TD.TIPE_AKTIVITAS = TRANS_LIST_AFTER_DEALING_VIEW.TIPE AND TD.ROW_STATUS >= 0) > 0 THEN 1 ELSE 0 END STATUS_ACTIVITY";
            
            $result['activity'] = json_decode($this->curl->simple_get(API_URL . "/api/sales/list_after_dealing",$param));

            if($result['list'] && is_array($result['list']->message)){
                if($result['list']->message[0]->NO_TRANS){
                    $result['card'] = $this->cardDetail($result['list']->message[0]->NO_TRANS);
                }
            }
        }
        
        $data=array();
        if($result['list']){
            if($result['list']->totaldata >0) {
                $data = array(
                    'p' => $this->input->get('q'), 
                    'count' => $result['list']->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => $result['list']->message
                );
            }else{
                $data=array(
                    'p' => $this->input->get('p'),
                    'count' => $result['list']->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => array()
                );
            }
        }else{
            $data=array(
                'p' => $this->input->get('p'), 
                'count' => 0,//$result['list']->totaldata, 
                'per_page' => $this->input->get('per_page'), 
                'data' => array()
            );
        }
        if($dataonly){
            $this->output->set_output(json_encode($result));
        }
        else{
            $this->output->set_output(json_encode($data));
        }

    }

    public function cardDetail($no_trans)
    {
        $this->auth->validate_authen('after_dealing/add_after_dealing');

        $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
        $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
        $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
        $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        
        $param_detail = array(
            'no_trans' => $no_trans,
            'jointable' => array(
                array("TRANS_AFTER_DEALING AFD" , "AFD.NO_TRANS=TRANS_AFTER_DEALING_DETAIL.NO_TRANS AND AFD.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_KARYAWAN MK" , "MK.NIK=TRANS_AFTER_DEALING_DETAIL.KD_SALES AND MK.ROW_STATUS >= 0", "LEFT"),
            ),
            'field' => "AFD.NO_HP, 
                        AFD.NO_TRANS, 
                        AFD.TGL_TRANS, 
                        AFD.NO_SPK, 
                        AFD.KD_DEALER, 
                        AFD.NAMA_CUSTOMER, 
                        AFD.KD_CUSTOMER, 
                        AFD.NO_RANGKA, 
                        AFD.NO_MESIN, 
                        TRANS_AFTER_DEALING_DETAIL.*, 
                        AFD.ID, 
                        TRANS_AFTER_DEALING_DETAIL.ID AS DETAIL_ID,
                        MK.NAMA AS NAMA_SALES",
        );


        $detail = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing_detail",$param_detail));

        $html = '';

        if($detail && is_array($detail->message)){

            if(!empty($detail) && is_array($detail->message)): 
            foreach ($detail->message as $key => $value):
                switch ($value->STATUS_AKTIVITAS) {
                    case 0:
                        $button_aktivitas = '<button type="button" class="btn btn-danger btn-xs btn-update btn-add-card disabled-action">Not Started</button>';
                        break;
                    
                    case 1:
                        $button_aktivitas = '<button type="button" class="btn btn-primary btn-xs btn-update btn-add-card disabled-action">In Progress</button>';
                        break;
                    
                    default:
                        $button_aktivitas = '<button type="button" class="btn btn-success btn-xs btn-update btn-add-card disabled-action">Completed</button>';
                        break;
                }
$html .= '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 card-form">';
$html .= '<div class="thumbnail">';
$html .= '<div class="caption">';
$html .= '<div class="col-lg-12">';
$html .= '<span class="glyphicon glyphicon-credit-card"></span>';
$html .= '<a href="#" id="hapus-'.$value->DETAIL_ID.'" data-id="'.$value->DETAIL_ID.'" class="fa fa-trash pull-right text-primary hapus2-item '.$status_e.'"></a>';
$html .= '<a href="#" id="edit-'.$value->DETAIL_ID.'" data-id="'.$value->DETAIL_ID.'" class="fa fa-edit pull-right text-primary edit2-item '.$status_e.'"></a>';
$html .= '<a href="'.base_url('after_dealing/cetak_card/'.$value->DETAIL_ID).'" target="_blank" id="print-'.$value->DETAIL_ID .'" data-id="'.$value->DETAIL_ID .'" class="fa fa-print pull-right text-primary print-item '.$status_p.'"></a>';

$html .= '</div>';
$html .= '<div class="col-lg-12 well well-add-card">';
$html .= '<input type="hidden" name="detail_id" value="'.$value->DETAIL_ID.'" class="detail_id_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="kd_sales" value="'.$value->KD_SALES.'" class="kd_sales_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="nama_aktivitas" value="'.$value->NAMA_AKTIVITAS.'" class="nama_aktivitas_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="tipe_aktivitas" value="'.$value->TIPE_AKTIVITAS.'" class="tipe_aktivitas_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="status_aktivitas" value="'.$value->STATUS_AKTIVITAS.'" class="status_aktivitas_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="waktu_mulai" value="'.TglFromSql($value->WAKTU_MULAI).'" class="waktu_mulai_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="waktu_selesai" value="'.TglFromSql($value->WAKTU_SELESAI).'" class="waktu_selesai_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="deskripsi" value="'.$value->DESKRIPSI.'" class="deskripsi_old_'.$value->DETAIL_ID.'">';
$html .= '<input type="hidden" name="keterangan" value="'.$value->KETERANGAN.'" class="keterangan_old_'.$value->DETAIL_ID.'">';
$html .= '<h4>'.$value->NAMA_CUSTOMER.'</h4>';
$html .= '</div>';
$html .= '<div class="col-lg-12">';
$html .= '<p>'.$value->NAMA_AKTIVITAS.'</p>';
$html .= '<p class="text-muted">No HP : '.$value->NO_HP.'</p>';
$html .= '<p class="text-muted">Tanggal aktivitas : '.TglFromSql($value->WAKTU_MULAI).' - '.TglFromSql($value->WAKTU_SELESAI).'</p>';
$html .= '</div>';
$html .= $button_aktivitas;
$html .= '<span class="glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style" data-toggle="tooltip" data-placement="left" title="'.$value->DESKRIPSI.'"></span>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

            endforeach;
            endif;


        }



        return $html;
    }

    public function simpan_after_dealing()
    {
        $ntrans = ($this->input->post('no_trans') ? $this->input->post('no_trans') : $this->autogenerate('AFD'));
        $param = array(
            'no_trans' => $ntrans,
            'kd_customer' => $this->input->post("kd_customer"),
            'no_spk' => $this->input->post("no_spk"),
            'no_rangka' => $this->input->post("no_rangka"),
            'no_mesin' => $this->input->post("no_mesin"),
            'tgl_trans' => $this->input->post("tgl_trans"),
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'nama_customer' => $this->input->post("nama_customer"),
            'no_hp' => $this->input->post("no_hp"),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil =  $this->curl->simple_post(API_URL . "/api/sales/after_dealing", $param, array(CURLOPT_BUFFERSIZE => 100));
        //data
        $method = "post";
        // var_dump($hasil);exit;
        if(json_decode($hasil)->recordexists==TRUE){
            $hasil= $this->curl->simple_put(API_URL."/api/sales/after_dealing",$param, array(CURLOPT_BUFFERSIZE => 10));  
            $method = "put";
        }
        // var_dump($hasil);exit;
        if($hasil)
        {
            if(json_decode($hasil)->message>0){
                $post_detail = $this->after_dealing_detail($ntrans);
            }
        }  
        //test
        $this->data_output($hasil, $method, base_url('after_dealing/add_after_dealing?u='.$ntrans)); 

    }

    public function after_dealing_detail($ntrans)
    {
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'no_trans' => $ntrans,
                'id' => $detail[$i]['detail_id'],
                'kd_sales' => $detail[$i]['kd_sales'],
                'nama_aktivitas' => $detail[$i]['nama_aktivitas'],
                'tipe_aktivitas' => $detail[$i]['tipe_aktivitas'],
                'status_aktivitas' => $detail[$i]['status_aktivitas'],
                'waktu_mulai' => $detail[$i]['waktu_mulai'],
                'waktu_selesai' => $detail[$i]['waktu_selesai'],
                'deskripsi' => $detail[$i]['deskripsi'],
                'keterangan' => $detail[$i]['keterangan'],
                'created_by' => $this->session->userdata("user_id"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
                // var_dump($param); exit;
            if($detail[$i]['detail_id']){
                $hasil= $this->curl->simple_put(API_URL."/api/sales/after_dealing_detailid",$param, array(CURLOPT_BUFFERSIZE => 10));  
                // var_dump($hasil);
            }
            else{
                $hasil = $this->curl->simple_post(API_URL . "/api/sales/after_dealing_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
                if(json_decode($hasil)->recordexists==TRUE){
                    $hasil= $this->curl->simple_put(API_URL."/api/sales/after_dealing_detail",$param, array(CURLOPT_BUFFERSIZE => 10));  
                }
            }
        }

        // var_dump($hasil);exit;

    }

    public function edit_afterdealing_detail()
    {

        $param = array(
            'id' => $this->input->get('id'),
        );


        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing_detail",$param));

        $this->output->set_output(json_encode($data));
    }

    public function delete_afterdealing_detail()
    {
        $param = array(
            'id' => $this->input->get('id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/sales/after_dealing_detail", $param));
        $this->data_output($data, 'delete');
    }


    public function update_status_aktivitas()
    {
        $param = array(
            'id'                => $this->input->get('detail_id'),
            'status_aktivitas'  => $this->input->get('status_aktivitas'),
            'lastmodified_by'   => $this->session->userdata('user_id')
        );
        $data = array();
        
        $hasil= $this->curl->simple_put(API_URL."/api/sales/after_dealing_detail_status_aktivitas",$param, array(CURLOPT_BUFFERSIZE => 10));  
        $this->data_output($hasil, 'put');
    }



    public function cetak_card($id)
    {
        $this->load->library('dompdf_gen');
        //total_stnk dari BBN di deteil spk kendaraan by : pak iswan 08-05-2018
        
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        
        $param_detail = array(
            'id' => $id,
            'jointable' => array(
                array("TRANS_AFTER_DEALING AFD" , "AFD.NO_TRANS=TRANS_AFTER_DEALING_DETAIL.NO_TRANS AND AFD.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_KARYAWAN MK" , "MK.NIK=TRANS_AFTER_DEALING_DETAIL.KD_SALES AND MK.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_KARYAWAN KSP" , "KSP.NIK=AFD.CREATED_BY AND KSP.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_DEALER_V AS MDV","MDV.KD_DEALER=AFD.KD_DEALER AND MDV.ROW_STATUS >= 0","LEFT"),
            ),
            'field' => "AFD.NO_HP, 
                        AFD.NO_TRANS, 
                        AFD.TGL_TRANS, 
                        AFD.NO_SPK, 
                        AFD.KD_DEALER, 
                        AFD.NAMA_CUSTOMER, 
                        AFD.KD_CUSTOMER, 
                        AFD.NO_RANGKA, 
                        AFD.NO_MESIN, 
                        TRANS_AFTER_DEALING_DETAIL.*, 
                        AFD.ID, 
                        TRANS_AFTER_DEALING_DETAIL.ID AS DETAIL_ID,
                        MK.NAMA AS NAMA_SALES,
                        KSP.NAMA AS NAMA_KSP,
                        MDV.NAMA_DEALER, MDV.ALAMAT AS ALAMAT_DEALER, MDV.NAMA_KABUPATEN AS KABUPATEN_DEALER, MDV.TLP AS TELEPON_DEALER",
        );

        $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/sales/after_dealing_detail",$param_detail));

        // $this->output->set_output(json_encode($data));
        // $this->load->view('after_dealing/cetak_card', $data);

        $html = $this->load->view('after_dealing/cetak_card', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');    
    }



    function autogenerate($kd_docno) {
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
