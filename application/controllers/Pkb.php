<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pkb extends CI_Controller {
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
    public function pkb_list() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PKB.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PKB.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "TRANS_PKB.NAMA_MEKANIK=MK.NIK", "LEFT"),
            ),
            'custom' => $kd_dealer,
            'field' => "TRANS_PKB.*, MK.NAMA,
                        CASE WHEN (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB = TRANS_PKB.NO_PKB AND TPD.KATEGORI NOT IN('Jasa') AND TPD.ROW_STATUS >= 0) = (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB = TRANS_PKB.NO_PKB AND TPD.PICKING_STATUS >= 1 AND TPD.KATEGORI NOT IN('Jasa') AND TPD.ROW_STATUS >= 0) THEN 'true' ELSE 'false' END ALL_PICKING_STATUS, CASE WHEN (SELECT COUNT(TPD.APPROVAL_ITEM) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB = TRANS_PKB.NO_PKB AND TPD.APPROVAL_ITEM = 0 AND TPD.ROW_STATUS >= 0) > 0 THEN 'reject' ELSE 'approve' END STATUS_APPROVE",
            'orderby' => 'TRANS_PKB.TANGGAL_PKB DESC, TRANS_PKB.NO_PKB DESC',
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
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
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/pkb_list', $data);
    }
    public function add_pkb() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        // $data["jasa"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa"));
        if ($this->input->get("n")) {
            $param = array(
                "kd_sa" => $this->input->get('n'),
                "kd_dealer" => $kd_dealer,
                'jointable' =>array(
                    array("TRANS_STNK_UDH UDH" , "UDH.NO_RANGKA=TRANS_CSA.NO_RANGKA AND UDH.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=UDH.KD_ITEM", "LEFT"),
                    array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.NO_RANGKA=TRANS_CSA.NO_RANGKA AND SJD.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_SJKELUAR SJ" , "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_SPK SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
                ),
                "field" => "TRANS_CSA.*, TRANS_CSA.KM_SAATINI AS KM_MOTOR,MP.NAMA_PASAR, SP.TGL_SO AS TGL_TERIMA, UDH.KD_ITEM,
                            (SELECT TOP 1 S.TANGGAL_PKB FROM TRANS_PKB AS S WHERE S.NO_RANGKA=TRANS_CSA.NO_RANGKA AND S.ROW_STATUS>=0) AS TANGGAL_PKB,
                            (SELECT TOP 1 S.JENIS_KPB FROM TRANS_PKB AS S WHERE S.NO_RANGKA=TRANS_CSA.NO_RANGKA AND S.ROW_STATUS>=0) AS JENIS_KPB"
            );
            $data["sa"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));
            $data['kpb'] = $this->cek_kpb($data["sa"]->message[0]);
        }
        if ($this->input->get("u")) {
            $param = array(
                "no_pkb" => $this->input->get('u'),
                'jointable' =>array(
                    array("TRANS_PKB_DETAIL TPD" , "TPD.NO_PKB=TRANS_PKB.NO_PKB AND TPD.ROW_STATUS>=0", "LEFT"),
                ),
                "field" => "TRANS_PKB.*, TPD.PICKING_STATUS, TPD.ID AS DETAIL_ID, TPD.KD_PEKERJAAN, TPD.QTY, TPD.HARGA_SATUAN, TPD.TOTAL_HARGA, TPD.KATEGORI, TPD.DISKON, TRANS_PKB.TGL_BELI AS TGL_TERIMA, TPD.JENIS_ITEM, TPD.JENIS_PKB, TPD.APPROVAL_ITEM,
                    CASE WHEN TPD.KATEGORI='Part' 
                    THEN (SELECT TOP 1 S.PART_DESKRIPSI FROM MASTER_PART as S WHERE S.PART_NUMBER=TPD.KD_PEKERJAAN)
                    ELSE (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END PART_DESKRIPSI,
                    CASE WHEN (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB = TRANS_PKB.NO_PKB AND TPD.KATEGORI NOT IN('Jasa')) = (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB = TRANS_PKB.NO_PKB AND TPD.PICKING_STATUS >= 1 AND TPD.KATEGORI NOT IN('Jasa') AND TPD.ROW_STATUS >= 0) THEN 'true' ELSE 'false' END ALL_PICKING_STATUS",
                "orderby" => "TPD.KATEGORI asc"
            );
            $data["pkb"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
            // var_dump($data['pkb']);exit;
            $data['kpb'] = $this->cek_kpb($data["pkb"]->message[0]);
        }
        $parammekanikready = array(
            'custom' => "convert(char,AM.TANGGAL,112) = '".tglToSql(date('d/m/Y'))."' AND MASTER_MEKANIK_VIEW.KD_DEALER = '".$kd_dealer."'",
            'jointable' =>array(
                array("MASTER_KARYAWAN KR" , "KR.NIK=MASTER_MEKANIK_VIEW.NIK AND KR.ROW_STATUS>=0", "LEFT"),
                array("TRANS_ABSENSI_MEKANIK AM" , "AM.NIK=MASTER_MEKANIK_VIEW.NIK AND AM.ROW_STATUS>=0", "LEFT")
            ),
            'field' => "MASTER_MEKANIK_VIEW.*, KR.NAMA,
                        (SELECT COUNT(S.NAMA_MEKANIK) FROM TRANS_PKB AS S WHERE convert(char,S.TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND S.NAMA_MEKANIK = MASTER_MEKANIK_VIEW.NIK AND S.KD_DEALER = '".$kd_dealer."' AND S.ROW_STATUS >= 0) AS JUMLAH_PEKERJAAN,
                        (SELECT MAX(S.ESTIMASI_SELESAI) FROM TRANS_PKB AS S WHERE convert(char,S.TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND S.NAMA_MEKANIK = MASTER_MEKANIK_VIEW.NIK AND S.KD_DEALER = '".$kd_dealer."' AND S.ROW_STATUS >= 0) AS SELESAI_PENGERJAAN"
        );
        $data["mekanik_ready"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $parammekanikready));
        $parammekanik = array(
            'jointable' =>array(
                array("MASTER_KARYAWAN KR" , "KR.NIK=MASTER_MEKANIK_VIEW.NIK AND KR.ROW_STATUS>=0", "LEFT"),
            ),
            'field' => "MASTER_MEKANIK_VIEW.*, KR.NAMA"
        );
        $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $parammekanik));
        $parampkb = array(
            'custom' => "convert(char,TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND KD_DEALER = '".$kd_dealer."'", 
        );
        $data["antrian"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $parampkb));


        $data["pit"] = json_decode($this->curl->simple_get(API_URL . "/api/master/pitdealer", array('kd_dealer'=>$kd_dealer)));

        $data["approval"] = isApproval('APPKB');

        //generate stock part
        // $this->output->set_output(json_encode($data['mekanik_ready']));
        $this->template->site('sales/pkb_tambah', $data);
        // $this->parts4gen();
    }
    
    public function antrian_service($datax = false)
    {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PKB.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PKB.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "TRANS_PKB.NAMA_MEKANIK=MK.NIK", "LEFT"),
            ),
            'custom' => "convert(char,TRANS_PKB.TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND ". $kd_dealer,
            'field' => 'TRANS_PKB.*, MK.NAMA',
            'orderby' => 'TRANS_PKB.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $data['antrian'] = '';
        if($data['list'] && (is_array($data['list']->message) || is_object($data['list']->message))){
            $data['antrian'] = $this->antrian($data['list']->message, 4).$this->antrian($data['list']->message, 2).$this->antrian($data['list']->message, 1);
        }
        if($datax == true){
            $this->output->set_output(json_encode($data)); 
        }
        else{
            $this->template->site('sales/antrian_service', $data);
        }
    }
    public function antrian_service_fullscreen()
    {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PKB.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PKB.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_KARYAWAN AS MK", "TRANS_PKB.NAMA_MEKANIK=MK.NIK", "LEFT"),
            ),
            'custom' => "convert(char,TRANS_PKB.TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND ". $kd_dealer,
            'field' => 'TRANS_PKB.*, MK.NAMA',
            'orderby' => 'TRANS_PKB.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $data['antrian'] = '';
        if($data['list'] && (is_array($data['list']->message) || is_object($data['list']->message))){
            $data['antrian'] = $this->antrian($data['list']->message, 4).$this->antrian($data['list']->message, 2).$this->antrian($data['list']->message, 1);
        }
        $this->load->view('sales/antrian_service_fullscreen', $data);
    }
    public function antrian($list, $status)
    {
        $html = '';
        switch ($status) {
            case 2:
                $proses = 'Dikerjakan';
                $label = 'warning';
                $mulai = '';
                $selesai = '(est)';
                break;
            case 4:
                $proses = 'Selesai';
                $label = 'danger';
                $mulai = '';
                $selesai = '';
                break;
            default:
                $proses = 'Antri';
                $label = 'success';
                $mulai = '(est)';
                $selesai = '(est)';
                break;
        }
        foreach ($list as $key => $value) {
            $to_time = strtotime($value->ESTIMASI_SELESAI);
            $from_time = strtotime($value->ESTIMASI_MULAI);
            if($value->STATUS_PKB == $status):
            $html .= '<tr class="'.$label.'">';
            $html .= '<td>'.$value->NO_ANTRIAN.'</td>';
            $html .= '<td style="text-transform: uppercase;">'.$value->NO_POLISI.'</td>';
            $html .= '<td>'.$value->JENIS_PIT.'</td>';
            $html .= '<td class="datetime-mulai">'.date('H:i', strtotime($value->ESTIMASI_MULAI)).$mulai.'</td>';
            $html .= '<td>'.round(abs($to_time - $from_time) / 60,2). " menit".'</td>';
            $html .= '<td class="datetime-selesai">'.date('H:i', strtotime($value->ESTIMASI_SELESAI)).$selesai.'</td>';
            $html .= '<td>'.$proses.'</td>';
            $html .= '</tr>';
            endif;
        }
        return $html;
    }
    public function get_sa()
    {
        $param = array(
            "kd_sa" => $this->input->get('kd_sa'),
            'jointable' =>array(
                array("TRANS_CSA_DETAIL CSD" , "CSD.KD_SA=TRANS_CSA.KD_SA AND CSD.ROW_STATUS>=0", "LEFT"),
                array("TRANS_STNK_UDH UDH" , "UDH.NO_RANGKA=TRANS_CSA.NO_RANGKA AND UDH.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=UDH.KD_ITEM", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.NO_RANGKA=TRANS_CSA.NO_RANGKA AND SJD.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SJKELUAR SJ" , "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
            ),
            "field" => "TRANS_CSA.*, CSD.KD_PEKERJAAN, CSD.QTY, CSD.HARGA_SATUAN, CSD.TOTAL_HARGA, CSD.KATEGORI, TRANS_CSA.KM_SAATINI AS KM_MOTOR,MP.NAMA_PASAR, SP.TGL_SO AS TGL_TERIMA, UDH.KD_ITEM,
                        (SELECT TOP 1 S.TANGGAL_PKB FROM TRANS_PKB AS S WHERE S.NO_RANGKA=TRANS_CSA.NO_RANGKA AND S.ROW_STATUS>=0) AS TANGGAL_PKB,
                        (SELECT TOP 1 S.JENIS_KPB FROM TRANS_PKB AS S WHERE S.NO_RANGKA=TRANS_CSA.NO_RANGKA AND S.ROW_STATUS>=0) AS JENIS_KPB,
                        "
        );
        $data["sa_header"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));
        if($data["sa_header"] && (is_array($data["sa_header"]->message) || is_object($data["sa_header"]->message))){
            $data['kpb'] = $this->cek_kpb($data["sa_header"]->message[0]);
        }
        $this->output->set_output(json_encode($data));        
    }
    public function get_pkb()
    {
        $param = array(
            'no_pkb' => $this->input->get('no_pkb'),
            'jointable' =>array(
                array("TRANS_PKB TP" , "TP.NO_PKB=TRANS_PKB_DETAIL.NO_PKB AND TP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.NO_RANGKA=TP.NO_RANGKA AND SJD.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SJKELUAR SJ" , "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("MASTER_PART MP" , "MP.PART_NUMBER=TRANS_PKB_DETAIL.KD_PEKERJAAN AND MP.ROW_STATUS>=0", "LEFT")
            ),
            "field" => "TRANS_PKB_DETAIL.*, SJ.TGL_TERIMA, MP.PART_DESKRIPSI",
            'custom' => "TRANS_PKB_DETAIL.KATEGORI = 'Part'"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail", $param));
        $html = '';
        if($data && (is_array($data->message) || is_object($data->message))):
            foreach ($data->message as $key => $pkb_row) {
                $html .= '<tr>';
                $html .= "<td class='hidden'>".$pkb_row->KD_PEKERJAAN."</td>";
                $html .= "<td class='text-center'><a id='".$pkb_row->ID."' class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>";
                $html .= "<td>".$pkb_row->KD_PEKERJAAN." - ".$pkb_row->PART_DESKRIPSI."</td>";
                $html .= "<td class='text-right'>".$pkb_row->QTY."</td>";
                $html .= "<td class='text-right qurency'>".number_format($pkb_row->HARGA_SATUAN)."</td>";
                $html .= "<td class='text-right qurency'>".number_format($pkb_row->TOTAL_HARGA)."</td>";
                $html .= "<td class='text-right'>".$pkb_row->KATEGORI."</td>";
                $html .= '</tr>';
                $html .= "<script>$(document).ready(function(){
                    $('.hapus-item').click(function(){
                        var detailId = this.id;
                        $(this).parents('tr').remove();
                    });});</script>";
            }
        endif;
        $this->output->set_output(json_encode($html));      
    }
    public function mekanik_ready()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $parammekanik = array(
            'custom' => "convert(char,AM.TANGGAL,112) = '".tglToSql(date('d/m/Y'))."' AND MASTER_MEKANIK_VIEW.KD_DEALER = '".$kd_dealer."'",
            'jointable' =>array(
                array("MASTER_KARYAWAN KR" , "KR.NIK=MASTER_MEKANIK_VIEW.NIK AND KR.ROW_STATUS>=0", "LEFT"),
                array("TRANS_ABSENSI_MEKANIK AM" , "AM.NIK=MASTER_MEKANIK_VIEW.NIK AND AM.ROW_STATUS>=0", "LEFT")
            ),
            'field' => "MASTER_MEKANIK_VIEW.*, KR.NAMA,
                        (SELECT COUNT(S.NAMA_MEKANIK) FROM TRANS_PKB AS S WHERE convert(char,S.TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND S.NAMA_MEKANIK = MASTER_MEKANIK_VIEW.NIK AND S.KD_DEALER = '".$kd_dealer."' AND S.ROW_STATUS >= 0) AS JUMLAH_PEKERJAAN,
                        (SELECT MAX(S.ESTIMASI_SELESAI) FROM TRANS_PKB AS S WHERE convert(char,S.TANGGAL_PKB,112) = '".tglToSql(date('d/m/Y'))."' AND S.NAMA_MEKANIK = MASTER_MEKANIK_VIEW.NIK AND S.KD_DEALER = '".$kd_dealer."' AND S.ROW_STATUS >= 0) AS SELESAI_PENGERJAAN"
        );
        if($this->input->get('nik')){
            $parammekanik['nik'] = $this->input->get('nik');
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/mekanik", $parammekanik));
        if($data->totaldata > 0){
            $this->output->set_output(json_encode($data->message));    
        }
    }
    public function get_kpbpart()
    {
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'part_number' => $this->input->get('part_number'),
            'kd_typemotor' => $this->input->get('kd_typemotor')
        );
        $data["metode4"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/kpb_part_oli/4", $param));
        $this->output->set_output(json_encode($data));    
    }
    public function hargajasa()
    {
        $param=array(
            "kd_jasa" => $this->input->get('part_number'),
            "field" => "KD_JASA AS PART_NUMBER, KETERANGAN AS PART_DESKRIPSI, 1 AS JUMLAH_SAK, HARGA AS HARGA_JUAL, FRT"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa", $param));
        if($data && $data->totaldata>0){
            $getJasa = $data->message;
        }
        else{
            $getJasa = array();
        }
        $this->output->set_output(json_encode($getJasa));        
    }
    public function cek_kpb($list)
    {
        $kpb = 'NONKPB';
        // $this->output->set_output(json_encode($list));    
        $param = array(
            "no_mesin" => substr($list->NO_MESIN,0,5)
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb", $param));
        if($data && (is_array($data->message) || is_object($data->message))):
            $km_motor = str_replace(".", "", $list->KM_MOTOR);
            $kbp_data = $list->JENIS_KPB != 'NONKPB'?(int)substr($list->JENIS_KPB,3,1):0;
            $jml_bln=strtotime(date('Y/m/d H:i:s')) - strtotime($list->TGL_TERIMA);
            $bln = round(($jml_bln / (60 * 60 * 24))/30);
            if($kbp_data = 1):
                $kpb_row = 2;
            elseif($kbp_data = 2):
                $kpb_row = 3;
            elseif($kbp_data = 3):
                $kpb_row = 4;
            else:
                $kpb_row = 0;
            endif;
            // var_dump($kpb_row);exit;
            if($km_motor <= $data->message[0]->BKM1):
                $km_row = 1;
            elseif($data->message[0]->BKM1 <= $km_motor && $km_motor <= $data->message[0]->BKM2):
                $km_row = 2;
            elseif($data->message[0]->BKM2 <= $km_motor && $km_motor <= $data->message[0]->BKM3):
                $km_row = 3;
            elseif($data->message[0]->BKM3 <= $km_motor && $km_motor <= $data->message[0]->BKM4):
                $km_row = 4;
            else:
                $km_row = 0;
            endif;
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
            $kbp_array = array($kpb_row, $km_row, $bln_row);
            // var_dump($kbp_array);exit;
            $kpb_max = max($kbp_array);
            switch ($kpb_max) {
                case 1: $kpb = 'KPB1';break;
                case 2: $kpb = 'KPB2';break;
                case 3: $kpb = 'KPB3';break;
                case 4: $kpb = 'KPB4';break;
                default: $kpb = 'NONKPB';break;
            }
        endif;
        return $kpb;  
    }
    public function simpan_pkb() {
        $ntrans = ($this->input->post('no_pkb') ? $this->input->post('no_pkb') : $this->autogenerate_pkb());
        $param = array(
            'id' => $this->input->post("id"),
            'no_pkb' => $ntrans,
            'no_polisi' => strtoupper($this->input->post("no_polisi")),
            'kd_dealer' => $this->session->userdata('kd_dealer'),
            'kd_sa' => $this->input->post("kd_sa"),
            'no_mesin' => $this->input->post("no_mesin"),
            'no_rangka' => $this->input->post("no_rangka"),
            'km_motor' => $this->input->post("km_motor"),
            'nama_typemotor' => $this->input->post("nama_typemotor"),
            'tahun' => $this->input->post("tahun"),
            'nama_mekanik' => $this->input->post("nama_mekanik"),
            'no_antrian' => $this->input->post("no_antrian"),
            'jenis_kpb' => $this->input->post("jenis_kpb"),
            'jenis_pit' => $this->input->post("jenis_pit"),
            'estimasi_mulai' => $this->input->post("estimasi_mulai"),
            'estimasi_selesai' => $this->input->post("estimasi_selesai"),
            'saran_mekanik' => $this->input->post("saran_mekanik"),
            'pembelian_motor' => $this->input->post("pembelian_motor"),
            'alasan_ke_ahass' => $this->input->post("alasan_ke_ahass"),
            'hubungan_dengan_pembawa' => $this->input->post("hubungan_dengan_pembawa"),
            'service_sebelumnya' => $this->input->post("service_sebelumnya"),
            'bbm' => $this->input->post("bbm"),
            'status_pkb' => $this->input->post("status_pkb")?$this->input->post("status_pkb"):0,
            'status_approval' => $this->input->post("status_pkb")?$this->input->post("status_approval"):0,
            'keterangan' => $this->input->post("keterangan"),
            'final_confirmation' => $this->input->post("status_pkb")?$this->input->post("final_confirmation"):0,
            'tanggal_pkb' => $this->input->post("tanggal_pkb"),
            'kd_item' => $this->input->post("kd_item"),
            'tgl_beli' => $this->input->post("tgl_beli"),
            'kd_lokasi' => $this->input->post("lokasi_dealer") ? $this->input->post("lokasi_dealer"): $this->session->userdata("kd_lokasi"),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil =  $this->curl->simple_post(API_URL . "/api/service/pkb", $param, array(CURLOPT_BUFFERSIZE => 100));
        //data
        $method = "post";
        // var_dump($hasil);exit;
        if(json_decode($hasil)->recordexists==TRUE){
            $hasil= $this->curl->simple_put(API_URL."/api/service/pkb",$param, array(CURLOPT_BUFFERSIZE => 10));  
        // var_dump($hasil);exit;
            $method = "put";
        }
        if($hasil)
        {
            if(json_decode($hasil)->message>0){
                $post_detail = $this->pkb_detail($ntrans);
            }
        }  
        //test
        $this->data_output($hasil, $method, base_url('pkb/add_pkb?u='.$ntrans)); 
    }
    public function pkb_detail($ntrans){
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'no_pkb' => $ntrans,
                'kd_pekerjaan' => $detail[$i]['kd_pekerjaan'],
                'kategori' => $detail[$i]['kategori'],
                'qty' => $detail[$i]['qty'],
                'harga_satuan' => $detail[$i]['harga_satuan'],
                'total_harga' => $detail[$i]['total_harga'],
                'jenis_item' => $detail[$i]['jenis_item'],
                'diskon' => $detail[$i]['diskon'],
                'jenis_pkb' => $detail[$i]['jenis_pkb'],
                'approval_item' => $detail[$i]['approval_item'],
                'created_by' => $this->session->userdata("user_id")
            );
                // var_dump($param); exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/service/pkb_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            if(json_decode($hasil)->recordexists==TRUE){
                $param_pkb = array(
                    'no_pkb' => $ntrans, 
                    'kd_pekerjaan' => $detail[$i]['kd_pekerjaan'],
                    'jenis_item' => $detail[$i]['jenis_item'],
                    'field' => '*'
                );
                $pkb= json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail", $param_pkb));
                $param_update = array(
                    'no_pkb' => $ntrans,
                    'kd_pekerjaan' => $detail[$i]['kd_pekerjaan'],
                    'kategori' => $detail[$i]['kategori'],
                    'qty' => $detail[$i]['qty'] + $pkb->message[0]->QTY,
                    'harga_satuan' => $detail[$i]['harga_satuan'],
                    'total_harga' => $detail[$i]['total_harga'] + $pkb->message[0]->TOTAL_HARGA,
                    'jenis_item' => $detail[$i]['jenis_item'],
                    'diskon' => $detail[$i]['diskon'] + $pkb->message[0]->DISKON,
                    'jenis_pkb' => $detail[$i]['jenis_pkb'],
                    'approval_item' => $detail[$i]['approval_item'],
                    'lastmodified_by' => $this->session->userdata("user_id")
                );
                // var_dump($pkb); exit;
                $hasil= $this->curl->simple_put(API_URL."/api/service/pkb_detail",$param_update, array(CURLOPT_BUFFERSIZE => 10));  
            }
        }
        // $this->data_output($hasil, 'post');
    }
    public function delete_pkb_detail() {
        $param = array(
            'id' => $this->input->get('id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/service/pkb_detail", $param));
        $this->data_output($data, 'delete');
    }
    public function edit_pkb($id, $row_status) {
        $this->auth->validate_authen('sales/pkb_edit');
        $param = array(
            'custom' => "id = '" . $id . "'",
            'row_status' => $row_status
        );
        $data = array();
        $parammekanik = array(
            'custom' => "PERSONAL_JABATAN = 'Mekanik'"
        );
        $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $parammekanik));
        $data["pit"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jenispit"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->template->site('sales/pkb_edit', $data);
    }
    public function update_pkb() {
        $this->form_validation->set_rules('id', 'ID', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );
            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'no_pkb' => $this->input->post("no_pkb"),
                'no_polisi' => $this->input->post("no_polisi"),
                'km_motor' => $this->input->post("km_motor"),
                'tanggal_pkb' => $this->input->post("tanggal_pkb"),
                'no_rangka' => $this->input->post("no_rangka"),
                'no_mesin' => $this->input->post("no_mesin"),
                'nama_typemotor' => $this->input->post("nama_typemotor"),
                'tahun' => $this->input->post("tahun"),
                'nama_mekanik' => $this->input->post("nama_mekanik"),
                'no_antrian' => $this->input->post("no_antrian"),
                'jenis_kpb' => $this->input->post("jenis_kpb"),
                'jenis_pit' => $this->input->post("jenis_pit"),
                'estimasi_mulai' => date('H:i', strtotime($this->input->post("estimasi_mulai"))),
                'estimasi_selesai' => date('H:i', strtotime($this->input->post("estimasi_selesai"))),
                'saran_mekanik' => $this->input->post("saran_mekanik"),
                'pembelian_motor' => $this->input->post("pembelian_motor"),
                'alasan_ke_ahass' => $this->input->post("alasan_ke_ahass"),
                'hubungan_dengan_pembawa' => $this->input->post("hubungan_dengan_pembawa"),
                'service_sebelumnya' => $this->input->post("service_sebelumnya"),
                'bbm' => $this->input->post("bbm"),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'kd_sa' => $this->input->post("kd_sa"),
                'status_pkb' => $this->input->post("status_pkb"),
                'status_approval' => $this->input->post("status_approval"),
                'keterangan' => $this->input->post("keterangan"),
                'final_confirmation' => $this->input->post("final_confirmation"),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb", $param, array(CURLOPT_BUFFERSIZE => 10));
            $this->data_output($hasil, 'put', base_url('pkb/pkb_list'));
        }
    }

    public function approval_item()
    {

        $param = array(
            'id' => $this->input->get('detail_id'), 
            'approval_item' => 1, 
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_approval_detail", $param, array(CURLOPT_BUFFERSIZE => 10));

        $this->data_output($hasil, 'put');
    }

    public function delete_pkb($id) {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/service/pkb", $param));
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('pkb/pkb_list')
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
    public function alasan_pending($id){
        $this->auth->validate_authen('pkb/pkb_list');
        $param = array(
            'custom' => 'ID = '.$id,
            'field' => 'ID, ALASAN_PENDING'
        );
        $data = array();
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/service/pkb",$param));
        $this->load->view('sales/pkb_pending', $data);
        $html = $this->output->get_output();
        // $this->output->set_output(json_encode($data));
        $this->output->set_output(json_encode($html));
    }
    public function simpan_alasan($id){
        $param = array(
            'id' => $id, 
            'alasan_pending' => $this->input->post('alasan_pending'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_pending", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $id);
        $this->data_output($hasil, 'put');
        // $this->output->set_output(json_encode($param));
    }
    public function simpan_prosespkb(){
        $param_cek = array(
            'custom' => "ID = ".$this->input->get('id'),
            'field' => 'NO_PKB'
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/service/pkb",$param_cek));
        if($data->totaldata > 0)
        {
            $param = array(
                'no_pkb' => $data->message[0]->NO_PKB, 
                'status_pkb' => $this->input->get('status_pkb'), 
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        $this->data_output($hasil, 'put');
        // $this->output->set_output(json_encode($data));
    }
    public function edit_approval($id) {
        $this->auth->validate_authen('pkb/pkb_list');
        $param = array(
            'custom' => "id = '" . $id . "'"
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->load->view('sales/pkb_approval', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_pkb_approval($id) {
        $param = array(
            'id' => html_escape($this->input->post("id")),
            'status_pkb' => $this->input->post("status_approval") == 2 ? 3 : 0,
            'status_approval' => $this->input->post("status_approval"),
            'final_confirmation' => $this->input->post("status_approval") == 2 ? 2 : 0,
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_approval", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $id);
        $this->data_output($hasil, 'put');
    }
    public function final_confirmation($id) {
        $this->auth->validate_authen('pkb/pkb_list');
        $param = array(
            'custom' => 'ID = '.$id,
            'field' => 'ID, KETERANGAN, SARAN_MEKANIK'
        );
        $data = array();
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/service/pkb",$param));
        $this->load->view('sales/pkb_finalconfirmation', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function update_final_confirmation($id) {
        $param = array(
            'id' => $id, 
            'keterangan' => $this->input->post("keterangan"),
            'saran_mekanik' => $this->input->post("saran_mekanik"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/service/pkb_finalconfirmation", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $id);
        $this->data_output($hasil, 'put');
    }
    public function cek_detailpkb($no_pkb){
        $param = array(
            'no_pkb' => $no_pkb,
            // 'field' => "KD_PEKERJAAN",
            'field' => "NO_PKB, (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB ='".$no_pkb."' AND TPD.KATEGORI NOT IN('Jasa') AND TPD.ROW_STATUS >= 0) AS PEKERJAAN, 
                CASE WHEN (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB ='".$no_pkb."' AND TPD.KATEGORI NOT IN('Jasa') AND TPD.ROW_STATUS >= 0) = (SELECT COUNT(TPD.KD_PEKERJAAN) FROM TRANS_PKB_DETAIL TPD WHERE TPD.NO_PKB ='".$no_pkb."' AND TPD.PICKING_STATUS >= 1 AND TPD.KATEGORI NOT IN('Jasa') AND TPD.ROW_STATUS >= 0) THEN 'true' ELSE 'false' END ALL_PICKING_STATUS",
            // 'orderby' => 'TRANS_PKB.TANGGAL_PKB desc'
            'groupby' => true
        );
        $list = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail", $param));
        $data = false;
        if ($list && is_array($list->message)) {
            foreach ($list->message as $key => $value) {
                $data = $value->ALL_PICKING_STATUS;
            }
        }
        $this->output->set_output(json_encode($data));
    }
    public function sa_typeahead() {
        $param = array(
            'custom' => "STATUS_SA = 0 AND KD_DEALER='".$this->session->userdata('kd_dealer')."'" );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa",$param)); //,$param
        $result['keyword'] = [];
        if($data['list']->totaldata > 0){
            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->KD_SA;
            }
            $result['keyword'] = array_merge($data_message[0]);
        }
        $this->output->set_output(json_encode($result));
    }
    public function get_nosa() {

        $offset = ($this->input->get('p')-1)*$this->input->get('per_page');
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $param = array(
            'custom' => "STATUS_SA = 0 AND KD_DEALER='".$kd_dealer."'",
            'field' => 'NO_POLISI, KD_SA'
        );

        if($this->input->get('q')){
            $param['keyword']   = $this->input->get('q');
            // $param['custom']   = "(PART_NUMBER LIKE '%".$this->input->get('q')."%' OR PART_DESKRIPSI LIKE '%".$this->input->get('q')."%')";
        }else{
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');
        }
        
        $sa = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa",$param)); 

        $data=array();
        if($sa){
            if($sa->totaldata >0) {
                $data = array(
                    'p' => $this->input->get('q'), 
                    'count' => $sa->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => $sa->message
                );
            }else{
                $data=array(
                    'p' => $this->input->get('p'), 
                    'count' => $sa->totaldata, 
                    'per_page' => $this->input->get('per_page'), 
                    'data' => array()
                );
            }
        }else{
            $data=array(
                'p' => $this->input->get('p'), 
                'count' => 0,//$sa->totaldata, 
                'per_page' => $this->input->get('per_page'), 
                'data' => array()
            );
        }
        $this->output->set_output(json_encode($data));
    }

    public function update_printnota()
    {
        $param = array(

            'no_pkb' => $this->input->get('no_pkb'),
            'final_confirmation' => 1,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $hasil= $this->curl->simple_put(API_URL."/api/service/pkb_finalconfirm",$param, array(CURLOPT_BUFFERSIZE => 10));  
        // $this->output->set_output(json_encode($param));
        $this->data_output($hasil, 'put');

    }

    public function print_nota($no_pkb){
        $this->auth->validate_authen('pkb/pkb_list');
        $param = array(
            'jointable' => array(
                             array("TRANS_CSA CS","CS.KD_SA=TRANS_PKB.KD_SA","LEFT"),
                             array("MASTER_MEKANIK_VIEW K","K.NIK=TRANS_PKB.NAMA_MEKANIK","LEFT") , 
                             array("TRANS_PKB_DETAIL TPD","TPD.NO_PKB=TRANS_PKB.NO_PKB","LEFT") , 
                             array("MASTER_CUSTOMER_VIEW CSV","CSV.KD_CUSTOMER=CS.KD_CUSTOMER","LEFT") , 
                         ),
            'field' => "TRANS_PKB.NAMA_MEKANIK, TRANS_PKB.TANGGAL_PKB, TRANS_PKB.NO_PKB,TRANS_PKB.NO_POLISI,TRANS_PKB.JENIS_KPB,TRANS_PKB.STATUS_PKB, CS.KD_SA, CS.NAMA_COMINGCUSTOMER, CS.ALAMAT_COMINGCUSTOMER ALAMAT_SURAT, CS.KONFIRMASI_PEKERJAANTAMBAHAN HP_PEMBAWA, K.NAMA_MEKANIK AS NAMA_MEKANIK",
            'orderby' => "TRANS_PKB.ID",
            'no_pkb' => $no_pkb,
        );
        $data["pkb"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb",$param));
        $param=array(
            'no_pkb'  => $no_pkb,
            'custom' => "(KATEGORI = 'JASA' OR (PICKING_STATUS >= 1 AND KATEGORI != 'Jasa'))",
        );
        $data["pkbd"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail/true",$param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        // $this->output->set_output(json_encode($data["pkb"]));

        $this->load->view('sales/nota_pkb', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function parts4gen($debug=null){
        $param = array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->inpu->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'kd_lokasi' =>($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi"),
            'user_login' => str_replace("-","",$this->session->userdata("user_id"))
        );
        $direct=$this->input->get("d");
        $gndata=$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param);
        
        $this->output->set_output(json_encode($gndata));
        // if($debug){echo json_encode($gndata);}
    }
    public function part_jasa($kategori, $dataonly=null){
        $offset = ($this->input->get('p')-1)*$this->input->get('per_page');
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        if($this->input->get('q')){
            $param['keyword']   = $this->input->get('q');
            // $param['custom']   = "(PART_NUMBER LIKE '%".$this->input->get('q')."%' OR PART_DESKRIPSI LIKE '%".$this->input->get('q')."%')";
        }else{
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');
        }
        
        if($kategori == 'Part'):
            $param['id']   = '59';
            $param['custom']   = 'JUMLAH_SAK >0';
            $param['kd_lokasi']   = $this->input->get("lokasi_dealer");
            $param["part_number"] = $this->input->get("data_number");
            $param['kd_dealer'] = $kd_dealer;
            $param['user_login'] = str_replace("-","",$this->session->userdata("user_id"));
            $param['field'] = "PART_NUMBER AS DATA_NUMBER, PART_DESKRIPSI AS DATA_DESKRIPSI, HARGA_JUAL AS DATA_HARGA,0 AS FRT, 
                                CASE WHEN KD_GROUPSALES = 'OIL' THEN 'OLI' ELSE 'PART' END JENIS_ITEM";
            $list = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
        else:
            $param["kd_typemotor"] = $this->input->get("kd_typemotor");
            $param["kd_jasa"] = $this->input->get("data_number");
            $param['custom']   = "MD.KD_DEALER = '".$kd_dealer."' AND JASA_VS_TYPEMOTOR.KATEGORI IN ('".KotaDealer($kd_dealer,null,"KATEGORI_DEALER")."','A')";
            $param['jointable']   = array(
                                        array("MASTER_DEALER MD" , "MD.KATEGORI_DEALER=JASA_VS_TYPEMOTOR.KATEGORI AND MD.ROW_STATUS>=0", "LEFT"),
                                    );
            $param['field']   = "JASA_VS_TYPEMOTOR.KD_JASA AS DATA_NUMBER, JASA_VS_TYPEMOTOR.KETERANGAN AS DATA_DESKRIPSI, ISNULL(JASA_VS_TYPEMOTOR.HARGA,0) AS DATA_HARGA,ISNULL(FRT, 30) FRT,CASE WHEN LEFT(JASA_VS_TYPEMOTOR.KETERANGAN, 3) = 'ASS' THEN 'ASS' ELSE 'JASA' END JENIS_ITEM";
            $param["orderby"] = "JASA_VS_TYPEMOTOR.KETERANGAN";
            $param["groupby_text"] = "KD_JASA,KETERANGAN,HARGA,FRT";
            $list = json_decode($this->curl->simple_get(API_URL . "/api/master_service/jasa_vs_typemotor",$param));
        endif;
        //var_dump($list);exit();
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
            $this->output->set_output(json_encode($list->message));
        }
        else{
            $this->output->set_output(json_encode($data));
        }
    }

    public function tipe_motor($dataonly=null)
    {

        $offset = ($this->input->get('p')-1)*$this->input->get('per_page');

        $param = array(
            'kd_item' => $this->input->get('kd_item'),
            'field' => "KD_TYPEMOTOR, NAMA_PASAR, CC_MOTOR, KD_WARNA, KD_ITEM, KET_WARNA",
            'groupby' => true
        );

        if($this->input->get('q')){
            $param['keyword']   = $this->input->get('q');
            // $param['custom']   = "(PART_NUMBER LIKE '%".$this->input->get('q')."%' OR PART_DESKRIPSI LIKE '%".$this->input->get('q')."%')";
        }else{
            $param['offset']    = $offset; 
            $param['limit']     = $this->input->get('per_page');
        }

        $list = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));


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
    /**
     * [pkb_typeahead description]
     * @param  [type] $dataonly [description]
     * @param  [type] $debug    [description]
     * @return [type]           [description]
     * Status PKB
     * 0: input PKB
     * 1: Approve PKB
     * 2: Pengerjaan, kalau belum ya masih 1
     * 3: Pending
     * 4: Selesai : update juga kolom final konfirmasi nya dengan tgl dan jam
     * 5: Dibayar
     */
    public function pkb_typeahead($dataonly=null,$debug=null) {
        $param=array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'kd_maindealer' => $this->session->userdata("kd_maindealer")
        );
        if($dataonly==true){
            $param["status_pkb"]="4";
            $param["jointable"] =array(
                   array("TRANS_CSA CS","CS.KD_SA=TRANS_PKB.KD_SA","LEFT"),
                   array("MASTER_MEKANIK M","M.NIK = TRANS_PKB.NAMA_MEKANIK","LEFT"),
                   array("MASTER_KARYAWAN K","K.NIK=M.NIK","LEFT") 
            );
            $param["field"] = "TRANS_PKB.NO_PKB,TRANS_PKB.NO_POLISI,TRANS_PKB.JENIS_KPB,TRANS_PKB.STATUS_PKB,K.NAMA,CS.KD_SA,
            \"CASE WHEN LEN(CS.NAMA_COMINGCUSTOMER)>2 THEN CS.NAMA_COMINGCUSTOMER ELSE CS.NAMA_PEMILIK END AS NAMA_COMINGCUSTOMER\"";
            $param["groupby_text"] = "TRANS_PKB.NO_PKB,TRANS_PKB.NO_POLISI,TRANS_PKB.JENIS_KPB,TRANS_PKB.STATUS_PKB,K.NAMA,CS.KD_SA,CS.NAMA_PEMILIK,CS.NAMA_COMINGCUSTOMER";
            $param["orderby"] = "TRANS_PKB.NO_PKB";
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb",$param));
        //var_dump($data);
        if ($data) {
            if($dataonly==true){
                if($data->totaldata>0){
                    if($debug==true){ echo json_encode($data->message);}else{ return $data;}
                }else{
                    if($debug==true){ echo "[]";}else{return false;}
                }
            }else{
                if ($data->totaldata >0) {
                    foreach ($data->message as $key => $message) {
                        $data_message[0][$key] = $message->NO_PKB;
                        $data_message[1][$key] = $message->NO_POLISI;
                    }
                    $result['keyword'] = array_merge($data_message[0], $data_message[1]);
                    $this->output->set_output(json_encode($result));
                }
            }
        }
    }
    function autogenerate_pkb() {
        $no_pkb = "";
        $nomorpkb = 0;
        $param = array(
            'kd_docno' => 'WO',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tanggal_pkb'), 6, 4),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = substr($this->input->post('tanggal_pkb'), 3, 2);
        $nomorpkb = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpkb == 0) {
            $no_pkb = "WO" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpkb = $nomorpkb + 1;
            $no_pkb = "WO" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpkb, 5, '0', STR_PAD_LEFT);
        }
        return $no_pkb;
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