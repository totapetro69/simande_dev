<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class STNK extends CI_Controller {
     var $API="";
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
            $this->load->helper('zetro'); 
            $this->load->helper('file');
    }
    /**
     * [stnk_list description]
     * @param  [type] $reff [description]
     * @return [type]       [description]
     */
    public function stnk_list($reff)
    {
        $data['reff'] = $reff == 'STNK'? 1:2;
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,SJ.TGL_SURATJALAN,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,SJ.TGL_SURATJALAN,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;
        // 'limit'     => 15,
        $kd_dealer = $this->input->get('kd_dealer') ? "SJ.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "SJ.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param= array(
            // 'kd_dealer' => '2NG',
            'keyword'   => $this->input->get('keyword'), 
            // 'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'jointable' =>array(
                array("TRANS_SJKELUAR SJ" , "SJ.ID = TRANS_SJKELUAR_DETAIL.ID_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_LEASING SPL" , "SPL.SPK_ID=SP.ID AND SPL.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER as MC","MC.KD_CUSTOMER=SJ.KD_CUSTOMER","LEFT"),
                array("MASTER_DESA as DS","DS.KD_DESA=MC.KELURAHAN", "LEFT"),
                array("MASTER_KECAMATAN as KC","KC.KD_KECAMATAN=MC.KD_KECAMATAN", "LEFT"),
                array("MASTER_KABUPATEN as KB","KB.KD_KABUPATEN=MC.KD_KOTA", "LEFT"),
                array("MASTER_PROPINSI as PR","PR.KD_PROPINSI=MC.KD_PROPINSI", "LEFT")
            ),
            'field' => '
                    TRANS_SJKELUAR_DETAIL.ID,
                    SJ.NO_SURATJALAN,
                    TRANS_SJKELUAR_DETAIL.NO_RANGKA,
                    TRANS_SJKELUAR_DETAIL.NO_MESIN,
                    MC.NAMA_CUSTOMER,
                    MC.ALAMAT_SURAT,
                    DS.NAMA_DESA,
                    KC.NAMA_KECAMATAN,
                    KB.NAMA_KABUPATEN,
                    MC.KODE_POS,
                    PR.NAMA_PROPINSI,
                    SP.JENIS_PENJUALAN,
                    SJ.KD_DEALER,
                    SPL.KD_FINCOY,
                    SPL.UANG_MUKA,
                    SPL.JANGKA_WAKTU,
                    SPL.JUMLAH_ANGSURAN,
                    TRANS_SJKELUAR_DETAIL.KET_UNIT,
                    TRANS_SJKELUAR_DETAIL.ID_SURATJALAN,
                    SJ.KD_CUSTOMER,
                    SJ.STATUS_SJ',
            'custom'    => "SJ.STATUS_SJ = 'aproved' AND TRANS_SJKELUAR_DETAIL.KET_UNIT NOT IN('KSU','HADIAH','BARANG') AND TRANS_SJKELUAR_DETAIL.NO_RANGKA NOT IN(SELECT TSTD.NO_RANGKA FROM TRANS_STNK_DETAIL AS TSTD WHERE TSTD.REFF_SOURCE=".$data['reff']." AND TSTD.ROW_STATUS >= 0 AND TSTD.NO_RANGKA IS NOT NULL) AND ".$kd_dealer." AND ".$tgl
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar_detail", $param));
        $data['udstk'] = false;
        $kd_dealers = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $params= array(
            // 'kd_dealer' => '2NG',
            'custom' => $kd_dealers
        );
        $detail_stnk = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $params));
        if($detail_stnk){
            if($detail_stnk->message>0){
                $data['udstk'] = true;
            }
        }
        $data['list_pengajuan'] = '';
        if($data['list'] && (is_array($data['list']->message) || is_object($data['list']->message))):
            $data['list_pengajuan'] = $this->list_pengajuan($data['list']->message);
        else:
            $data['list_pengajuan'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=19><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        /*var_dump($data);
        exit;*/
        /*$config = array(
            'per_page' => $param['limit'], 
            'base_url'  => base_url().'stnk/stnk_list?keyword='.$param['keyword'],
            'total_rows' => $data["list"]->totaldata
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();*/
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/list_pengajuan',$data);
    }
    public function add_pengurusan($reff=null, $data_only=null)
    {
        $data['reff'] = $reff == 'STNK'? 1:2;
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $param= array(
            'kabupaten_samsat' => $this->input->get('kd_kabupaten')?$this->input->get('kd_kabupaten'):$this->session->userdata("kd_kabupaten"),
            'keyword'   => $this->input->get('keyword'),
            'custom' => "TRANS_STNK_PENGURUSAN_V.NO_MESIN NOT IN(SELECT TSTD.KD_MESIN xplus TSTD.NO_MESIN AS NO_MESIN FROM TRANS_STNK_DETAIL AS TSTD WHERE TSTD.REFF_SOURCE=".$data['reff']." AND TSTD.ROW_STATUS >= 0 AND LEN(TSTD.NO_MESIN) > 0) AND TRANS_STNK_PENGURUSAN_V.KD_DEALER ='".$kd_dealer."'",
            'field' => "TRANS_STNK_PENGURUSAN_V.*, CONCAT(ALAMAT_SURAT,' Kel. ',NAMA_DESA,' Kec. ',NAMA_KECAMATAN,'  ',NAMA_KABUPATEN,'  ',NAMA_PROPINSI) AS ALAMAT_LENGKAP",
        );
        $data['toleransi'] = 0;
        if($data['reff'] == 1){
            $param_toleransi = array(
                'kd_dealer'    => $kd_dealer,
                'status_pengurusan' => ($this->input->get('status_manual') == 2 ? 2:1),
                'custom' => "REFF_SOURCE = ".$data['reff']
            );
            if($this->input->get('status_manual') == 2){
                $param['custom'] = "TRANS_STNK_PENGURUSAN_V.NO_MESIN NOT IN(SELECT TSTD.KD_MESIN xplus TSTD.NO_MESIN FROM TRANS_STNK_DETAIL AS TSTD WHERE TSTD.STATUS_PENGURUSAN = 2 AND TSTD.REFF_SOURCE=".$data['reff']." AND TSTD.ROW_STATUS >= 0 AND LEN(TSTD.NO_RANGKA)>0) AND TRANS_STNK_PENGURUSAN_V.KD_DEALER ='".$kd_dealer."' AND (ISNULL(TRANS_STNK_PENGURUSAN_V.PKB,0) = 0 OR ISNULL(TRANS_STNK_PENGURUSAN_V.SWDKLLJ,0) = 0 OR ISNULL(TRANS_STNK_PENGURUSAN_V.BBN,0) = 0 OR ISNULL(TRANS_STNK_PENGURUSAN_V.PKB,0)=0 OR ISNULL(TRANS_STNK_PENGURUSAN_V.SWDKLLJ,0)=0 OR ISNULL(TRANS_STNK_PENGURUSAN_V.BBN,0)=0)";
            }
            else{
                $param['custom'] = "TRANS_STNK_PENGURUSAN_V.NO_MESIN NOT IN(SELECT TSTD.KD_MESIN xplus TSTD.NO_MESIN FROM TRANS_STNK_DETAIL AS TSTD WHERE TSTD.STATUS_PENGURUSAN = 1 AND TSTD.REFF_SOURCE=".$data['reff']." AND TSTD.ROW_STATUS >= 0 AND TSTD.NO_RANGKA IS NOT NULL) AND TRANS_STNK_PENGURUSAN_V.NO_MESIN NOT IN(SELECT TSTD.NO_MESIN_1 FROM TRANS_STNK_BATASTOLERANSI_VIEW AS TSTD WHERE TSTD.STATUS_PENGURUSAN = 2 AND TSTD.REFF_SOURCE=".$data['reff']." AND TSTD.ROW_STATUS >= 0 AND TSTD.NO_RANGKA IS NOT NULL) AND TRANS_STNK_PENGURUSAN_V.KD_DEALER ='".$kd_dealer."' AND (ISNULL(TRANS_STNK_PENGURUSAN_V.PKB,0) > 0 AND ISNULL(TRANS_STNK_PENGURUSAN_V.SWDKLLJ,0)> 0 AND ISNULL(TRANS_STNK_PENGURUSAN_V.BBN,0)> 0)";
            }
            $toleransi =json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_batastoleransi", $param_toleransi));
            $data['toleransi'] = $toleransi->totaldata;
        }
        //$param["custom"] .=" AND NO_SURATJALAN='DO2NG201808-00061'";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_pengurusan/true", $param));
        // var_dump($data["list"]->param);
        // exit();
        $data['udstk'] = false;
        /*exit;*/
        $params= array(
            'custom' => "KD_DEALER = '".$kd_dealer."'"
        );
        $detail_stnk = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $params));
        if($detail_stnk){
            if($detail_stnk->message>0){
                $data['udstk'] = true;
            }
        }
        $data['list_pengajuan'] = '';
        if($data['list'] && (is_array($data['list']->message) || is_object($data['list']->message))):
            $data['list_pengajuan'] = $this->get_detail_table($data['list']->message, $data['reff']);
        else:
            $data['list_pengajuan'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=19><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        $param_bj = array(
            'custom'    => "KD_DEALER = '".$kd_dealer."'"
        );
        if($data['reff'] == 1){
            $param_bj['custom']    = "KD_DEALER = '".$kd_dealer."' AND KD_BIROJASA NOT IN(SELECT TBO.KD_BIROJASA FROM TRANS_BIROJASA_OUTSTANDING_VIEW AS TBO WHERE TBO.ROW_STATUS>=0 AND TBO.KD_BIROJASA IS NOT NULL)";
        }
        $data["birojasa"] =json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/birojasa", $param_bj));
        /*$param_kb = array(
            'jointable' => array(
                array("MASTER_KABUPATEN MK" , "MK.KD_KABUPATEN=TRANS_STNK_PENGURUSAN_VIEW.KD_KABUPATEN", "LEFT"),
            ),
            'custom'    =>"TRANS_STNK_PENGURUSAN_VIEW.KD_DEALER = '".$kd_dealer."' AND TRANS_STNK_PENGURUSAN_VIEW.KD_KABUPATEN IS NOT NULL",
            'field' => 'TRANS_STNK_PENGURUSAN_VIEW.KD_KABUPATEN, MK.NAMA_KABUPATEN',
            'groupby' => TRUE,
        );
        $data["kabupaten"] =json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_pengurusan", $param_kb));*/
        $data["kabupaten"] =json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/kabupaten_samsat"));
        if($data_only == 'true'){
            $this->output->set_output(json_encode($data));
        }
        else{
            $this->template->site('sales/add_pengurusan',$data);
        }
    }
    public function approval_pengurusan($reff)
    {
        $data['reff'] = $reff == 'STNK'? 1:2;
        $data['status_stnk'] = 0;
        $kd_dealer = $this->input->get('kd_dealer') ? "ST.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "ST.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param= array(
            // 'kd_dealer' => '2NG',
            'custom' => "TRANS_STNK_DETAIL.STATUS_STNK = ".$data['status_stnk']." AND TRANS_STNK_DETAIL.REFF_SOURCE = ".$data['reff']." AND ".$kd_dealer,
            'jointable' =>array(
                array("TRANS_STNK AS ST" , "ST.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("MASTER_BIROJASA AS BJ" , "BJ.KD_BIROJASA=ST.NAMA_PENGURUS AND BJ.KD_DEALER = ST.KD_DEALER", "LEFT")
            ),
            'field'     =>"ST.STATUS_MANUAL, ST.NO_TRANS, ST.ID, BJ.NAMA_PENGURUS, ST.KD_DEALER, ST.KD_MAINDEALER, ST.TGLMULAI_PENGURUSAN, 
                         ST.TGLSELESAI_PENGURUSAN",
            'groupby'   => TRUE
        );
        $data["stnk_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data['udstk'] = false;
        $kd_dealers = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $params= array(
            // 'kd_dealer' => '2NG',
            'custom' => "STATUS_STNK = 1 AND ROW_STATUS >= 0 AND REFF_SOURCE = 1 AND ".$kd_dealers
        );
        $detail_stnk = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $params));
        if($detail_stnk){
            if($detail_stnk->message>0){
                $data['udstk'] = true;
            }
        }
        $data['list_approval'] = '';
        if($data['stnk_header'] && (is_array($data['stnk_header']->message) || is_object($data['stnk_header']->message))):
            $data['list_approval'] = $this->get_header_table($data['stnk_header']->message, $data['status_stnk']);
        else:
            $data['list_approval'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=19><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        /*var_dump($data);
        exit;*/
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/approval_pengurusan',$data);
    }
    public function pengajuan_biaya($reff)
    {
        $data['reff'] = $reff == 'STNK'? 1:2;
        $data['status_stnk'] = 1;
        $kd_dealer = $this->input->get('kd_dealer') ? "ST.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "ST.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param= array(
            // 'kd_dealer' => '2NG',
            'custom' => "TRANS_STNK_DETAIL.STATUS_STNK = ".$data['status_stnk']." AND TRANS_STNK_DETAIL.REFF_SOURCE = ".$data['reff']." AND ".$kd_dealer,
            'jointable' =>array(
                array("TRANS_STNK AS ST" , "ST.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("MASTER_BIROJASA AS BJ" , "BJ.KD_BIROJASA=ST.NAMA_PENGURUS AND BJ.KD_DEALER = ST.KD_DEALER", "LEFT")
            ),
            'field'     =>"ST.NO_TRANS, ST.STATUS_MANUAL, ST.ID, BJ.NAMA_PENGURUS, ST.KD_DEALER, ST.KD_MAINDEALER",
            'groupby'   => TRUE
        );
        $data["stnk_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data['udstk'] = false;
        $data['list_approval'] = '';
        if($data['stnk_header'] && (is_array($data['stnk_header']->message) || is_object($data['stnk_header']->message))):
            $data['list_approval'] = $this->get_header_table($data['stnk_header']->message, $data['status_stnk']);
        else:
            $data['list_approval'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=19><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        /*var_dump($data);
        exit;*/
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/approval_pengurusan',$data);
    }
    public function approval_biaya($reff)
    {
        $data['reff'] = $reff == 'STNK'? 1:2;
        $data['status_stnk'] = 1;
        $kd_dealer = $this->input->get('kd_dealer') ? "ST.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "ST.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param= array(
            // 'kd_dealer' => '2NG',
            'custom' => "TRANS_STNK_DETAIL.STATUS_STNK = ".$data['status_stnk']." AND TRANS_STNK_DETAIL.REFF_SOURCE = ".$data['reff']." AND ".$kd_dealer,
            'jointable' =>array(
                array("TRANS_STNK AS ST" , "ST.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("MASTER_BIROJASA AS BJ" , "BJ.KD_BIROJASA=ST.NAMA_PENGURUS AND BJ.KD_DEALER = ST.KD_DEALER", "LEFT")
            ),
            'field'     =>"ST.STATUS_MANUAL, ST.NO_TRANS, ST.ID, BJ.NAMA_PENGURUS, ST.KD_DEALER, ST.KD_MAINDEALER",
            'groupby'   => TRUE
        );
        $data["stnk_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data['list_approval'] = '';
        if($data['stnk_header'] && (is_array($data['stnk_header']->message) || is_object($data['stnk_header']->message))):
            $data['list_approval'] = $this->get_header_table($data['stnk_header']->message, $data['status_stnk']);
        else:
            $data['list_approval'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=19><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        /*var_dump($data);
        exit;*/
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/approval_biaya',$data);
    }
    public function add_plat()
    {
        $data['status_stnk'] = 4;
        $kd_dealer = $this->input->get('kd_dealer') ? "ST.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "ST.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        if($this->input->get("n")){/*
            $param = array(
                'custom' => 'TRANS_STNK_BPKB.ID ='.base64_decode(urldecode($this->input->get("n"))),
                'field' => 'TRANS_STNK_BPKB.*, 
                            (SELECT TOP 1 KD_CUSTOMER FROM TRANS_STNK_DETAIL WHERE NO_RANGKA = TRANS_STNK_BPKB.NO_RANGKA) AS KD_CUSTOMER,
                            (SELECT TSD.ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.NO_RANGKA=TRANS_STNK_BPKB.NO_RANGKA AND TSD.REFF_SOURCE=1 AND TSD.ROW_STATUS>=0 AND TSD.STATUS_STNK>=4) AS STNKDETAIL_ID,
                            (SELECT TSD.ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.NO_RANGKA=TRANS_STNK_BPKB.NO_RANGKA AND TSD.REFF_SOURCE=2 AND TSD.ROW_STATUS>=0 AND TSD.STATUS_STNK>=4) AS BPKBDETAIL_ID'
            );
            $data["platheader"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/trans_stnk_bpkb", $param));*/
            $param = array(
                'jointable' =>array(
                    array("TRANS_STNK_BPKB AS TSB" , "TSB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND TSB.ROW_STATUS>=0", "LEFT")
                ),
                'custom' => "TRANS_STNK_DETAIL.NO_RANGKA ='".base64_decode(urldecode($this->input->get("n")))."'",
                'field' => 'TSB.*, TRANS_STNK_DETAIL.*, CONCAT(TRANS_STNK_DETAIL.KD_MESIN, TRANS_STNK_DETAIL.NO_MESIN) AS NO_MESIN,
                            (SELECT TSD.ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND TSD.REFF_SOURCE=1 AND TSD.ROW_STATUS>=0 AND TSD.STATUS_STNK>=4) AS STNKDETAIL_ID,
                            (SELECT TSD.ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND TSD.REFF_SOURCE=2 AND TSD.ROW_STATUS>=0 AND TSD.STATUS_STNK>=4) AS BPKBDETAIL_ID'
            );
            $data["platheader"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $param));
            $param_plat = array(
                'custom' => "NO_RANGKA ='".base64_decode(urldecode($this->input->get("n")))."' AND KETERANGAN='PLAT'"
            );
            $data["plat"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti", $param_plat))->message[0];
            $param_hcc = array(
                'custom' => "NO_RANGKA ='".base64_decode(urldecode($this->input->get("n")))."' AND KETERANGAN='HCC'"
            );
            $data["hcc"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti", $param_hcc))->message[0];
            $param_stnk = array(
                'custom' => "TRANS_STNK_BUKTI.NO_RANGKA ='".base64_decode(urldecode($this->input->get("n")))."' AND TRANS_STNK_BUKTI.KETERANGAN='STNK'",
                'jointable' =>array(
                    array("TRANS_STNK_DETAIL AS TSD" , "TSD.NO_RANGKA=TRANS_STNK_BUKTI.NO_RANGKA AND TSD.REFF_SOURCE=1 AND TSD.ROW_STATUS>=0", "LEFT")
                ),
                'field' => 'TRANS_STNK_BUKTI.*, TSD.STATUS_STNK'
            );
            $data["stnk"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti", $param_stnk))->message[0];
            $param_bpkb = array(
                'custom' => "TRANS_STNK_BUKTI.NO_RANGKA ='".base64_decode(urldecode($this->input->get("n")))."' AND TRANS_STNK_BUKTI.KETERANGAN='BPKB'",
                'jointable' =>array(
                    array("TRANS_STNK_DETAIL AS TSD" , "TSD.NO_RANGKA=TRANS_STNK_BUKTI.NO_RANGKA AND TSD.REFF_SOURCE=2 AND TSD.ROW_STATUS>=0", "LEFT")
                ),
                'field' => 'TRANS_STNK_BUKTI.*, TSD.STATUS_STNK'
            );
            $data["bpkb"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti", $param_bpkb))->message[0];
        }
        $param= array(
            // 'kd_dealer' => '2NG',
            'custom' => "TRANS_STNK_DETAIL.STATUS_STNK = ".$data['status_stnk']." AND TRANS_STNK_DETAIL.NO_RANGKA NOT IN(SELECT TSB.NO_RANGKA FROM TRANS_STNK_BPKB AS TSB WHERE TSB.ROW_STATUS>=0 AND TSB.NO_RANGKA IS NOT NULL) AND ".$kd_dealer,
            'jointable' =>array(
                array("TRANS_STNK AS ST" , "ST.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT")
            ),
            'field'     =>"TRANS_STNK_DETAIL.NO_RANGKA",
            // 'field'     =>"ST.NO_TRANS, ST.ID, BJ.NAMA_PENGURUS, ST.KD_DEALER, ST.KD_MAINDEALER, TRANS_SJKELUAR_DETAIL.",
            'groupby'   => TRUE
        );
        $data["stnk_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data['udstk'] = false;
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        // $this->output->set_output(json_encode($data["stnk"]));
        $this->template->site('sales/add_plat',$data);
    }
    public function plat_list($tipe_trans = false)
    {
        $data['status_stnk'] = 5;
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_DETAIL.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_DETAIL.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TS.TGL_STNK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TS.TGL_STNK,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;
        switch ($this->input->get('keterangan')) {
            case 1:
                $jenis = 'STNK';
                break;
            case 2:
                $jenis = 'BPKB';
                break;
            case 3:
                $jenis = 'PLAT';
                break;
            case 4:
                $jenis = 'HCC';
                break;
            case 5:
                $jenis = 'SRUT';
                break;
            default:
                $jenis = 'BPKB';
                break;
        }
        
        $data['tipe_trans'] = $tipe_trans;

        if($jenis == 'SRUT'):
            $tgl_srut= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TRANS_SRUT.TGL_TERIMA,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_SRUT.TGL_TERIMA,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;

            $jenis_penyerahan = $this->input->get('jenis_penyerahan');

            $fincoy = $jenis_penyerahan == 'leasing'?"SDL.KD_FINCOY = '".$this->input->get('kd_penyerahan_leasing')."'":"SDL.KD_FINCOY IS NULL";

            $kd_kota = $this->input->get('kd_kota')?$this->input->get('kd_kota'):$this->session->userdata("kd_kabupaten");

            $param= array(
                'kd_dealer' => $this->input->get('kd_dealer')?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
                'jointable' => array(
                    array("TRANS_SPK_DETAILKENDARAAN AS SDK", "SDK.NO_MESIN = TRANS_SRUT.NO_MESIN AND SDK.ROW_STATUS >= 0", "LEFT"),
                    array("TRANS_SPK_LEASING AS SDL", "SDL.SPK_ID=SDK.SPK_ID AND SDL.ROW_STATUS >= 0","LEFT"),
                    array("TRANS_SPK_DETAILCUSTOMER AS SDC", "SDC.SPK_ID=SDK.SPK_ID AND SDC.ROW_STATUS >= 0","LEFT")
                ),
                /*'field' => "1 AS STATUS_MANUAL, TRANS_SRUT.NO_TERIMA_DEALER AS STNK_ID, TRANS_SRUT.NO_TERIMA_DEALER AS NO_TRANS, TRANS_SRUT.STATUS_PENERIMA, TRANS_SRUT.NO_PENYERAHAN, TRANS_SRUT.DIRECTORY_BUKTIPENYERAHAN",
                'groupby_text' => "TRANS_SRUT.NO_TERIMA_DEALER, TRANS_SRUT.STATUS_PENERIMA, TRANS_SRUT.NO_PENYERAHAN, TRANS_SRUT.DIRECTORY_BUKTIPENYERAHAN"*/
            );
            if($tipe_trans == 'penyerahan'){
                $tipe = "TRANS_SRUT.TGL_TERIMA IS NOT NULL AND TRANS_SRUT.TGL_PENYERAHAN IS NULL AND (TRANS_SRUT.STATUS_PENERIMA IS NULL OR TRANS_SRUT.STATUS_PENERIMA = 0) AND TRANS_SRUT.STATUS_SRUT >= 4";
            }
            elseif($tipe_trans == 'bukti'){
                $tipe = "TRANS_SRUT.TGL_TERIMA IS NOT NULL AND TRANS_SRUT.TGL_PENYERAHAN IS NOT NULL AND TRANS_SRUT.STATUS_PENERIMA >= 1 AND TRANS_SRUT.STATUS_SRUT >= 4";

            }


            if($tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                $param['field'] = "1 AS STATUS_MANUAL, TRANS_SRUT.STATUS_PENERIMA, TRANS_SRUT.NO_PENYERAHAN, TRANS_SRUT.DIRECTORY_BUKTIPENYERAHAN";
                $param['groupby_text'] = "TRANS_SRUT.NO_PENYERAHAN, TRANS_SRUT.DIRECTORY_BUKTIPENYERAHAN, TRANS_SRUT.STATUS_PENERIMA";
            }
            else{
                $param['field'] = "1 AS STATUS_MANUAL, TRANS_SRUT.NO_TERIMA_DEALER AS STNK_ID, TRANS_SRUT.NO_TERIMA_DEALER AS NO_TRANS, TRANS_SRUT.STATUS_PENERIMA";
                $param['groupby_text'] = "TRANS_SRUT.NO_TERIMA_DEALER, TRANS_SRUT.STATUS_PENERIMA";
            }

            $param['custom'] = "SDK.SPK_ID IS NOT NULL AND ".$tipe." AND ".$fincoy." AND ".$tgl_srut." AND SDC.KD_KABUPATEN ='".$kd_kota."'";

            $data["header"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/srut", $param));

            // var_dump($data['header']);exit;
            if($data['header'] && is_array($data['header']->message)){
                if($jenis == 'SRUT' AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                    $queryIn = queryIndata($data['header']->message, 'NO_PENYERAHAN');
                    $queryIndata = "TRANS_SRUT.NO_PENYERAHAN IN ".$queryIn." AND ";
                }
                else{
                    $queryIn = queryIndata($data['header']->message, 'STNK_ID');
                    $queryIndata = "TRANS_SRUT.NO_TERIMA_DEALER IN ".$queryIn." AND ";
                }
            }
            else{
                $queryIndata = "";
            }

            $param_detail= array(
                'kd_dealer' => $this->input->get('kd_dealer')?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
                'jointable' => array(
                    array("TRANS_SPK_DETAILKENDARAAN AS SDK", "SDK.NO_MESIN = TRANS_SRUT.NO_MESIN AND SDK.ROW_STATUS >= 0", "LEFT"),
                    array("TRANS_SPK_LEASING AS SDL", "SDL.SPK_ID=SDK.SPK_ID AND SDL.ROW_STATUS >= 0","LEFT"),
                    array("TRANS_SPK_DETAILCUSTOMER AS SDC", "SDC.SPK_ID=SDK.SPK_ID AND SDC.ROW_STATUS >= 0","LEFT")
                ),
                'custom' => $queryIndata."SDK.SPK_ID IS NOT NULL AND ".$tipe." AND ".$fincoy." AND ".$tgl_srut." AND SDC.KD_KABUPATEN ='".$kd_kota."'",
                'field'     =>"TRANS_SRUT.TGL_TERIMA TGL_STNK, SDC.KD_CUSTOMER, TRANS_SRUT.NO_TERIMA_DEALER AS STNK_ID, TRANS_SRUT.ID AS DETAIL_ID,TRANS_SRUT.KD_DEALER, TRANS_SRUT.STATUS_SRUT AS STATUS_STNK, TRANS_SRUT.NO_RANGKA, TRANS_SRUT.NO_MESIN, TRANS_SRUT.NO_PENYERAHAN, TRANS_SRUT.NAMA_PENERIMA, 'SRUT' AS KETERANGAN, TRANS_SRUT.TGL_TERIMA AS TGL_PENERIMA, TRANS_SRUT.TGL_PENYERAHAN, TRANS_SRUT.DIRECTORY_BUKTIPENYERAHAN, TRANS_SRUT.STATUS_PENERIMA, TRANS_SRUT.ALAMAT, TRANS_SRUT.NO_HP NOHP, TRANS_SRUT.NO_TERIMA_DEALER DATA_NOMOR"
            );

            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/srut", $param_detail));


            $param_leasing = array(
                'kd_dealer' => $this->input->get('kd_dealer')?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer"),
                'jointable' => array(
                    array("TRANS_SPK_DETAILKENDARAAN AS SDK", "SDK.NO_MESIN = TRANS_SRUT.NO_MESIN AND SDK.ROW_STATUS >= 0", "LEFT"),
                    array("TRANS_SPK_LEASING AS SDL", "SDL.SPK_ID=SDK.SPK_ID AND SDL.ROW_STATUS >= 0","LEFT"),
                    array("TRANS_SPK_DETAILCUSTOMER AS SDC", "SDC.SPK_ID=SDK.SPK_ID AND SDC.ROW_STATUS >= 0","LEFT")
                ),
                'custom'    => "SDL.KD_FINCOY IS NOT NULL",
                'field'     => "SDL.KD_FINCOY",
                'groupby'   => true 
            );
            $data["leasing"] =json_decode($this->curl->simple_get(API_URL."/api/transaksi/SRUT", $param_leasing));

        else:
            $reff_source = ($jenis == 'STNK' || $jenis == 'HCC'? 1 : 2 );
            $jenis_penyerahan = $this->input->get('jenis_penyerahan');
            if($tipe_trans == 'penerimaan')
            {
                $status_req = '';

                if($jenis == 'BPKB'){
                    $status_req = ' AND TRANS_STNK_DETAIL.REQ_BPKB >= 3';
                }
                elseif($jenis == 'PLAT'){
                    $status_req = ' AND TRANS_STNK_DETAIL.REQ_STCK >= 3 AND TRANS_STNK_DETAIL.REQ_PLAT_ASLI >= 3';
                }
                elseif($jenis == 'STNK' || $jenis == 'HCC'){
                    $status_req = ' AND TRANS_STNK_DETAIL.TGL_BALIK IS NOT NULL';
                }

                $tipe = "SB.TGL_PENERIMA IS NULL AND (SB.STATUS_PENERIMA IS NULL OR SB.STATUS_PENERIMA = 0) AND TRANS_STNK_DETAIL.STATUS_STNK >= 4".$status_req;
            }
            elseif($tipe_trans == 'penyerahan')
            {
                $fincoy = '';
                if($jenis == 'BPKB'){
                    $fincoy = $jenis_penyerahan == 'leasing'?" AND TRANS_STNK_DETAIL.KD_FINCOY = '".$this->input->get('kd_penyerahan_leasing')."'":" AND TRANS_STNK_DETAIL.KD_FINCOY = ''";
                }
                $tipe = "SB.TGL_PENERIMA IS NOT NULL AND SB.TGL_PENYERAHAN IS NULL AND (SB.STATUS_PENERIMA IS NULL OR SB.STATUS_PENERIMA = 0) AND TRANS_STNK_DETAIL.STATUS_STNK >= 4".$fincoy;
            }
            elseif($tipe_trans == 'pengajuan_plat'){
                $tipe ="TRANS_STNK_DETAIL.STATUS_STNK = 2";
                // $jenis = 'PLAT';
            }
            elseif($tipe_trans == 'bukti'){
                $fincoy = '';
                if($jenis == 'BPKB'){
                    $fincoy = $jenis_penyerahan == 'leasing'?" AND TRANS_STNK_DETAIL.KD_FINCOY = '".$this->input->get('kd_penyerahan_leasing')."'":" AND TRANS_STNK_DETAIL.KD_FINCOY = ''";
                }
                $tipe = "SB.TGL_PENERIMA IS NOT NULL AND SB.TGL_PENYERAHAN IS NOT NULL AND SB.STATUS_PENERIMA >= 1 AND TRANS_STNK_DETAIL.STATUS_STNK >= 4".$fincoy;
            }
            else{
                $tipe ="TRANS_STNK_DETAIL.STATUS_STNK >= 1";
            }
            $param= array(
                'kd_kota' => $this->input->get('kd_kota')?$this->input->get('kd_kota'):$this->session->userdata("kd_kabupaten"),
                'custom'    => $tipe." AND TRANS_STNK_DETAIL.REFF_SOURCE=".$reff_source." AND ".$kd_dealer." AND ".$tgl,
                'jointable' =>array(
                    array("TRANS_STNK AS TS" , "TS.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                    array("TRANS_STNK_BPKB AS STB" , "STB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND STB.ROW_STATUS >= 0", "LEFT"),
                    array("TRANS_STNK_BUKTI AS SB" , "SB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SB.KETERANGAN ='".$jenis."' AND SB.ROW_STATUS >= 0", "LEFT")
                ),
                'groupby' => true
            );
            if($jenis == 'BPKB' AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                $param['field'] = "TRANS_STNK_DETAIL.REFF_SOURCE, SB.NO_PENYERAHAN, SB.DIRECTORY_BUKTIPENYERAHAN, SB.STATUS_PENERIMA, TS.STATUS_MANUAL, 
                                CASE WHEN (SELECT COUNT(S.STNK_ID) FROM TRANS_STNK_DETAIL S WHERE S.REFF_SOURCE=".$reff_source." AND S.STATUS_STNK=2 AND S.ROW_STATUS>=0)>0 THEN 1 ELSE 0 END COUNT_DETAIL";
            }
            else{
                $param['field'] = "TRANS_STNK_DETAIL.REFF_SOURCE, TRANS_STNK_DETAIL.STNK_ID, TS.NO_TRANS, TS.STATUS_MANUAL, 
                                CASE WHEN (SELECT COUNT(S.STNK_ID) FROM TRANS_STNK_DETAIL S WHERE S.REFF_SOURCE=".$reff_source." AND S.STNK_ID = TRANS_STNK_DETAIL.STNK_ID AND S.STATUS_STNK=2 AND S.ROW_STATUS>=0)>0 THEN 1 ELSE 0 END COUNT_DETAIL";
            }
            $data["header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));

            if($data['header'] && is_array($data['header']->message)){
                if($jenis == 'BPKB' AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                    $queryIn = queryIndata($data['header']->message, 'NO_PENYERAHAN');
                    $queryIndata = "SB.NO_PENYERAHAN IN ".$queryIn." AND ";
                }
                else{
                    $queryIn = queryIndata($data['header']->message, 'STNK_ID');
                    $queryIndata = "TRANS_STNK_DETAIL.STNK_ID IN ".$queryIn." AND ";
                }
            }
            else{
                $queryIndata = "";
            }


            $param_detail= array(
                'kd_kota' => $this->input->get('kd_kota')?$this->input->get('kd_kota'):$this->session->userdata("kd_kabupaten"),
                'custom'    => $queryIndata.$tipe." AND TRANS_STNK_DETAIL.STATUS_STNK >= 1 AND TRANS_STNK_DETAIL.REFF_SOURCE=".$reff_source." AND ".$kd_dealer." AND ".$tgl,
                'jointable' =>array(
                    array("TRANS_STNK AS TS" , "TS.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                    array("TRANS_STNK_BUKTI AS SB" , "SB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SB.KETERANGAN ='".$jenis."' AND SB.ROW_STATUS >= 0", "LEFT")
                ),
                'field'     =>"TS.TGL_STNK, TRANS_STNK_DETAIL.KD_CUSTOMER, TRANS_STNK_DETAIL.STNK_ID, TRANS_STNK_DETAIL.ID AS DETAIL_ID,TRANS_STNK_DETAIL.KD_DEALER, TRANS_STNK_DETAIL.STATUS_STNK, TRANS_STNK_DETAIL.NO_RANGKA, CONCAT(TRANS_STNK_DETAIL.KD_MESIN,TRANS_STNK_DETAIL.NO_MESIN) AS NO_MESIN, SB.NO_PENYERAHAN, SB.NAMA_PENERIMA, SB.KETERANGAN, SB.TGL_PENERIMA, SB.DIRECTORY_BUKTITERIMA, SB.TGL_PENYERAHAN, SB.DIRECTORY_BUKTIPENYERAHAN, SB.STATUS_PENERIMA, SB.ALAMAT, SB.NOHP, SB.DATA_NOMOR"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param_detail));


            $param_leasing = array(
                'custom'    => $kd_dealer." AND TRANS_STNK_DETAIL.KD_FINCOY !=''",
                'field' => "TRANS_STNK_DETAIL.KD_FINCOY",
                'groupby' => true 
            );
            $data["leasing"] =json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $param_leasing));

        endif;


        $param_print = array(
            'custom' => 'REFF_SOURCE=2 AND STATUS_STNK=3 AND '.$kd_dealer,
            'field' => 'NO_MESIN, KD_MESIN, NAMA_PEMILIK, STNK_ID'
        );
        $data["print"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param_print));
        $data['list_detail'] = '';
        if($data['header'] && (is_array($data['header']->message) || is_object($data['header']->message))):
            $data['list_detail'] = $this->plat_detail_table($data["header"]->message, $data['list']->message, $data['status_stnk'], $jenis, $tipe_trans, $jenis_penyerahan);
        else:
            $data['list_detail'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=25><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        $data["kabupaten"] =json_decode($this->curl->simple_get(API_URL."/api/master_general/kabupaten"));
        // $this->output->set_output(json_encode($data["header"]));
        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('sales/list_plat',$data);
    }
    public function list_data($reff)
    {
        $data['reff'] = $reff == 'STNK'? 1:2;
        $data['status_stnk'] = 5;
        $tipe_trans = "List Pengajuan";
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_DETAIL.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_DETAIL.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TS.TGL_STNK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TS.TGL_STNK,112) between '" . tglToSql(date('d/m/Y', strtotime('-30 days'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;
        $param= array(
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'kd_kota' => $this->input->get('kd_kota'),
            'custom'    => "ISNULL(TRANS_STNK_DETAIL.STATUS_STNK,0) >= 0 AND TRANS_STNK_DETAIL.REFF_SOURCE=".$data['reff']." AND ".$kd_dealer." AND ".$tgl,
            'jointable' =>array(
                array("TRANS_STNK AS TS" , "TS.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("TRANS_STNK_BPKB AS STB" , "STB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND STB.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_STNK_BUKTI AS SB" , "SB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SB.KETERANGAN ='".$reff."' AND SB.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_BIROJASA AS MB","MB.KD_BIROJASA=TS.NAMA_PENGURUS AND MB.KD_DEALER = TS.KD_DEALER","LEFT")
            ),
            'field'     =>"TRANS_STNK_DETAIL.REFF_SOURCE, TRANS_STNK_DETAIL.STNK_ID, TS.NO_TRANS, TS.STATUS_MANUAL, TS.STATUS_CETAK, MB.NAMA_PENGURUS, 
                            CASE WHEN (SELECT COUNT(S.STNK_ID) FROM TRANS_STNK_DETAIL S WHERE S.REFF_SOURCE=".$data['reff']." AND S.STNK_ID = TRANS_STNK_DETAIL.STNK_ID AND S.STATUS_STNK >= 0 AND S.ROW_STATUS>=0)>0 THEN 1 ELSE 0 END COUNT_DETAI, 
                            CASE WHEN (MAX(TRANS_STNK_DETAIL.REFF_SOURCE) = 1 AND MAX(TRANS_STNK_DETAIL.TGL_BALIK) != '') OR (MAX(TRANS_STNK_DETAIL.REFF_SOURCE) = 2 AND MAX(TRANS_STNK_DETAIL.TGL_BALIK) != '' AND MAX(LEN(TRANS_STNK_DETAIL.JENIS_BAYAR)) >5) THEN -3 ELSE -2 END HEADER_ROW_STATUS",
            'groupby' => true,
            'orderby' => "TRANS_STNK_DETAIL.STNK_ID DESC"
        );
        $data["header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));


        if($data['header'] && is_array($data['header']->message)){
            $queryIn = queryIndata($data['header']->message, 'STNK_ID');
        }
        else{
            $queryIn = "('')";
        }

        $param_detail= array(
            'kd_kota' => $this->input->get('kd_kota'),
            'custom'    => "TRANS_STNK_DETAIL.STNK_ID IN ".$queryIn." AND TRANS_STNK_DETAIL.STATUS_STNK >= 0 AND TRANS_STNK_DETAIL.REFF_SOURCE=".$data['reff']." AND ".$kd_dealer." AND ".$tgl,
            'jointable' =>array(
                array("TRANS_STNK AS TS" , "TS.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("TRANS_STNK_BUKTI AS SB" , "SB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SB.KETERANGAN ='".$reff."' AND SB.ROW_STATUS >= 0", "LEFT")
            ),
            'field'     =>"
                    TS.TGL_STNK, 
                    TRANS_STNK_DETAIL.JENIS_BAYAR,
                    TRANS_STNK_DETAIL.TGL_BALIK,
                    TRANS_STNK_DETAIL.KD_CUSTOMER, 
                    TRANS_STNK_DETAIL.STNK_ID, 
                    TRANS_STNK_DETAIL.ID AS DETAIL_ID,
                    TRANS_STNK_DETAIL.KD_DEALER, 
                    TRANS_STNK_DETAIL.STATUS_STNK, 
                    TRANS_STNK_DETAIL.NO_RANGKA, 
                    CONCAT(TRANS_STNK_DETAIL.KD_MESIN,
                    TRANS_STNK_DETAIL.NO_MESIN) AS NO_MESIN, 
                    TRANS_STNK_DETAIL.NAMA_PEMILIK, 
                    TRANS_STNK_DETAIL.ALAMAT_PEMILIK, 
                    TRANS_STNK_DETAIL.KD_ITEM,
                    TRANS_STNK_DETAIL.REQ_STCK, 
                    TRANS_STNK_DETAIL.REQ_BPKB, 
                    TRANS_STNK_DETAIL.REQ_ADMIN_SAMSAT, 
                    TRANS_STNK_DETAIL.REQ_PLAT_ASLI,
                    CASE WHEN TRANS_STNK_DETAIL.REFF_SOURCE = '1' THEN TRANS_STNK_DETAIL.BIAYA_STNK ELSE TRANS_STNK_DETAIL.BIAYA_BPKB END BIAYA_PENGAJUAN",
            'orderby' => 'TRANS_STNK_DETAIL.id desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param_detail));
        $data['list_detail'] = '';
        if($data['header'] && ($data['header']->totaldata >0)):
            $data['list_detail'] = $this->list_detail_table($data["header"]->message, $data['list'], $data['status_stnk'], $reff, $tipe_trans);
        else:
            $data['list_detail'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=12><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["header"]) ? $data["header"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $data["kabupaten"] =json_decode($this->curl->simple_get(API_URL."/api/master_general/kabupaten"));
        // $this->output->set_output(json_encode($data["header"]));
        $this->template->site('sales/list_data',$data);
    }
    public function add_birojasa($reff)
    {
        $this->auth->validate_authen('stnk/plat_list');
        $data['reff'] = $reff;


        if($this->input->get("kd_dealer")){
          $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
          $param['where_in']  = isDealerAkses();
          $param['where_in_field'] = 'KD_DEALER';
        }

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer", $param));
        /*var_dump($data);
        print_r($data);
        exit;*/
        $this->load->view('sales/add_birojasa',$data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function lead_time()
    {
        $data = array();
        $this->auth->validate_authen('stnk/plat_list');
        $param = array(
            'custom' => 'TRANS_STNK_DETAIL.REFF_SOURCE=1 AND TRANS_STNK_DETAIL.STATUS_STNK=6',
            'jointable' => array(
                array("TRANS_STNK_BUKTI TSB" , "TSB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND TSB.KETERANGAN='STNK'", "LEFT")
            ),
            'field' => 'TRANS_STNK_DETAIL.*, CONCAT(TRANS_STNK_DETAIL.KD_MESIN,TRANS_STNK_DETAIL.NO_MESIN) AS NO_MESIN, TSB.TGL_PENERIMA, TSB.TGL_PENYERAHAN',
            'orderby' => 'TSB.TGL_PENERIMA'
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail",$param));
        /*var_dump($data);
        exit;*/
        // $this->output->set_output(json_encode($data));
        $this->load->view('sales/lead_time', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));        
    }
    public function document_handling()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_DETAIL.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_DETAIL.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TSB.TGL_PENERIMA,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TSB.TGL_PENERIMA,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;
        $reff_source = ($this->input->get('keterangan') ? $this->input->get('keterangan') : 1 );
        $bukti_stnk = $reff_source == 1 ? 'STNK':'BPKB';
        $param= array(
            'keyword'   => $this->input->get('keyword'), 
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'custom'    => "TRANS_STNK_DETAIL.STATUS_STNK >= 5 AND TRANS_STNK_DETAIL.REFF_SOURCE=".$reff_source." AND ".$kd_dealer." AND ".$tgl,
            'jointable' =>array(
                array("TRANS_STNK_BUKTI TSB" , "TSB.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND TSB.KETERANGAN = '".$bukti_stnk."' AND TSB.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=TRANS_STNK_DETAIL.KD_ITEM", "LEFT")
            ),
            'field'     =>"CONCAT(TRANS_STNK_DETAIL.KD_MESIN,TRANS_STNK_DETAIL.NO_MESIN) AS NO_MESIN, TRANS_STNK_DETAIL.NO_RANGKA, TRANS_STNK_DETAIL.STATUS_STNK, MP.NAMA_PASAR, TRANS_STNK_DETAIL.NAMA_PEMILIK, TRANS_STNK_DETAIL.KD_CUSTOMER, TRANS_STNK_DETAIL.REFF_SOURCE,
                (SELECT S.DATA_NOMOR FROM TRANS_STNK_BUKTI S WHERE S.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND S.KETERANGAN = '".$bukti_stnk."' AND S.ROW_STATUS >= 0) AS DATA_NOMOR,
                (SELECT S.TGL_PENERIMA FROM TRANS_STNK_BUKTI S WHERE S.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND S.KETERANGAN = '".$bukti_stnk."' AND S.ROW_STATUS >= 0) AS TGL_PENERIMA,
                (SELECT S.TGL_PENYERAHAN FROM TRANS_STNK_BUKTI S WHERE S.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND S.KETERANGAN = '".$bukti_stnk."' AND S.ROW_STATUS >= 0) AS TGL_PENYERAHAN
                "
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        $data["kabupaten"] =json_decode($this->curl->simple_get(API_URL."/api/master_general/kabupaten"));
        $udh_param = array(
            'status_stnk' => 6, 
        );
        $data["udh"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/udh", $udh_param));
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/document_handling',$data);
    }
    public function print_plat()
    {
        $this->auth->validate_authen('stnk/plat_list/pengajuan_plat');
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $param = array(
            'kd_dealer' => $kd_dealer,
            'custom' => 'REFF_SOURCE=2 AND STATUS_STNK=3',
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 1,
            'jointable' => array(
                array("MASTER_DEALER AS MD","MD.KD_DEALER=TRANS_STNK_DETAIL.KD_DEALER","LEFT"),
                array("MASTER_KABUPATEN AS MK","MK.KD_KABUPATEN=TRANS_STNK_DETAIL.KD_KOTA","LEFT"),
                array("TRANS_STNK AS ST","ST.ID=TRANS_STNK_DETAIL.STNK_ID","LEFT"),
                array("MASTER_BIROJASA AS MB","MB.KD_BIROJASA=ST.NAMA_PENGURUS AND MB.KD_DEALER = ST.KD_DEALER","LEFT")
            ),
            'field' => 'ST.ID, ST.TGL_STNK, ST.TGL_PENGAJUANPLAT, MD.NAMA_DEALER, MK.NAMA_KABUPATEN, MB.NAMA_PENGURUS, ST.NO_TRANS',
            'groupby' => TRUE 
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        // var_dump($data);exit;
        $param_detail = array(
            'kd_dealer' => $kd_dealer,
            'custom' => 'REFF_SOURCE=2 AND STATUS_STNK=3',
            'field' => 'NO_MESIN, KD_MESIN, NAMA_PEMILIK, STNK_ID'
        );
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param_detail));        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('sales/print_plat', $data);
        $html = $this->output->get_output();
        // $this->output->set_output(json_encode($data));
        $this->output->set_output(json_encode($html));
    }
    // get from jquery
    // ===========================================================================
    public function get_approval()
    {
        $no_trans = $this->input->get('no_trans');
        $status_stnk = $this->input->get('status_stnk');
        $kd_dealer = $this->input->get('kd_dealer') ? "ST.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "ST.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param= array(
            // 'kd_dealer' => '2NG',
            'custom' => "ST.NO_TRANS = '".$no_trans."' AND ".$kd_dealer,
            'jointable' =>array(
                array("TRANS_STNK AS ST" , "ST.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("MASTER_BIROJASA AS BJ" , "BJ.KD_BIROJASA=ST.NAMA_PENGURUS AND BJ.KD_DEALER = ST.KD_DEALER", "LEFT")
            ),
            'field'     =>"ST.NO_TRANS, ST.STATUS_MANUAL, ST.ID, BJ.NAMA_PENGURUS, ST.KD_DEALER, ST.KD_MAINDEALER, ST.TGL_STNK, ST.TGLMULAI_PENGURUSAN, ST.TGLSELESAI_PENGURUSAN",
            'groupby'   => TRUE
        );
        $data["stnk_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data['list_approval'] = '';
        if($data['stnk_header'] && (is_array($data['stnk_header']->message) || is_object($data['stnk_header']->message))):
            $data['list_approval'] = $this->get_header_table($data['stnk_header']->message, $status_stnk);
            $data['list_approval'] .= "<script type='text/javascript'>
                $(document).ready(function(){
                    $('.ajukan_all').click(function(){
                    var key = $(this).val();
                    $('.ajukan_all_'+key+':checkbox:checked').each(function(){
                        $('.ajukan_'+key).prop('checked', true);
                        $('.total_stnk_'+key).removeAttr('style');
                        $('.total_biayaapprove_'+key).focus();
                    });
                    $('.ajukan_all_'+key+':checkbox:not(\":checked\")').each(function(){
                        $('.ajukan_'+key).prop('checked', false);
                        $('.total_stnk_'+key).css('display','none');
                    });
                    __cekTotal(key);
                    }); 
                    $('.ajukan').click(function(){
                    var key = this.id;
                    var total_checked = $('.ajukan_'+key+':checkbox:checked').length;
                    var total_ajukan = $('.ajukan_'+key+':checkbox').length;
                    if(total_checked == 0){
                    $('.ajukan_all_'+key).prop('checked', false);
                    }
                    else{
                    $('.ajukan_all_'+key).prop('checked', true);
                    }
                    });
                    $('.total_biayaapprove').keyup(function(){
                    var key = this.id;
                    var totBiy = $('.total_biayaapprove_'+key).val();
                    var cekError = __customError(totBiy, key)
                    });
                    $('.biaya_stnk').keyup(function(){
                    var result = this.id.split('-');
                    var i = result[0];
                    var j = result[1];
                    var biayaChecked = $('.ajukan_all_'+i+':checkbox:checked').length;
                    if(biayaChecked != ''){
                    var totBiy = $('.biaya_stnk_'+i+':eq(' + j + ')').val();
                    var cekError = __customErrorbiaya(totBiy, i, j);
                    }
                    });
                    }) </script>";
        else:
            $data['list_approval'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=19><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        $this->output->set_output(json_encode($data));
    }
    public function get_plat()
    {
        $no_trans = $this->input->get('no_trans');
        $status_stnk = $this->input->get('status_stnk');
        $kd_dealer = $this->input->get('kd_dealer') ? "ST.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "ST.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param= array(
            // 'kd_dealer' => '2NG',
            'custom' => "ST.NO_TRANS = '".$no_trans."' AND TRANS_STNK_DETAIL.STATUS_STNK = ".$status_stnk." AND ".$kd_dealer,
            'jointable' =>array(
                array("TRANS_STNK AS ST" , "ST.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT"),
                array("MASTER_BIROJASA AS BJ" , "BJ.KD_BIROJASA=ST.NAMA_PENGURUS AND BJ.KD_DEALER = ST.KD_DEALER", "LEFT")
            ),
            'field'     =>"TRANS_STNK_DETAIL.BIAYA_STNK, ST.NO_TRANS, ST.ID, BJ.NAMA_PENGURUS, ST.KD_DEALER, ST.KD_MAINDEALER, ST.TGL_STNK",
            'groupby'   => TRUE
        );
        $data["stnk_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data['list_rangka'] = '';
        if($data['stnk_header'] && (is_array($data['stnk_header']->message) || is_object($data['stnk_header']->message))):
            $data['list_rangka'] = $this->get_rangka($data['stnk_header']->message, $status_stnk);
        else:
            $data['list_rangka'] .= "<option value='null'>- Pilih No Rangka -</option>";
        endif;
        $this->output->set_output(json_encode($data));
    }
    // data table
    // ===========================================================================
    public function list_pengajuan($list)
    {
        $html = '';
        $no = $this->input->get('page');
        foreach ($list as $key => $list_pengajuan) {
            $no ++;
            $html .= '<tr id="'.($this->session->flashdata('tr-active') == $list_pengajuan->ID ? 'tr-active' : ' ').'" >';
            $html .= '<td>'.$no.'</td>';
            $html .= '<td id="no_rangka_'.$key.'">'.$list_pengajuan->NO_RANGKA.'</td>';
            $html .= '<td id="kd_mesin_'.$key.'">'.substr($list_pengajuan->NO_MESIN,0,5).'</td>';
            $html .= '<td id="no_mesin_'.$key.'">'.substr($list_pengajuan->NO_MESIN,-7).'</td>';
            $html .= '<td id="nama_pemilik_'.$key.'">'.$list_pengajuan->NAMA_CUSTOMER.'</td>';
            $html .= '<td id="alamat_pemilik_'.$key.'">'.$list_pengajuan->ALAMAT_SURAT.'</td>';
            $html .= '<td id="kd_kelurahan_'.$key.'">'.$list_pengajuan->NAMA_DESA.'</td>';
            $html .= '<td id="kd_kecamatan_'.$key.'">'.$list_pengajuan->NAMA_KECAMATAN.'</td>';
            $html .= '<td id="kd_kota_'.$key.'">'.$list_pengajuan->NAMA_KABUPATEN.'</td>';
            $html .= '<td id="kode_pos_'.$key.'">'.$list_pengajuan->KODE_POS.'</td>';
            $html .= '<td id="kd_propinsi_'.$key.'">'.$list_pengajuan->NAMA_PROPINSI.'</td>';
            $html .= '<td id="jenis_pembayaran_'.$key.'">'.$list_pengajuan->JENIS_PENJUALAN.'</td>';
            $html .= '<td id="kd_dealer_'.$key.'">'.$list_pengajuan->KD_DEALER.'</td>';
            $html .= '<td id="kd_fincoy_'.$key.'">'.$list_pengajuan->KD_FINCOY.'</td>';
            $html .= '<td id="dp_'.$key.'">'.$list_pengajuan->UANG_MUKA.'</td>';
            $html .= '<td id="tenor_'.$key.'">'.$list_pengajuan->JANGKA_WAKTU.'</td>';
            $html .= '<td id="besar_cicilan_'.$key.'">'.$list_pengajuan->JUMLAH_ANGSURAN.'</td>';
            $html .= '<td id="kd_customer_'.$key.'">'.$list_pengajuan->KD_CUSTOMER.'</td>';
            $html .= '</tr>';
        }
        return $html;
    }
    public function get_detail_table($list, $reff)
    {
        $html = '';
        $no = $this->input->get('page');
        $key = 0;
        foreach ($list as $list_pengajuan) {
            $no ++;
            $html .= '<tr id="'.($this->session->flashdata('tr-active') == $list_pengajuan->ID ? 'tr-active' : ' ').'" >';
            $html .= '<input id="no_rangka_'.$key.'" type="hidden" value="'.$list_pengajuan->NO_RANGKA.'">';
            $html .= '<input id="kd_mesin_'.$key.'" type="hidden" value="'.substr($list_pengajuan->NO_MESIN,0,5).'">';
            $html .= '<input id="no_mesin_'.$key.'" type="hidden" value="'.substr($list_pengajuan->NO_MESIN,-7).'">';
            $html .= '<input id="nama_pemilik_'.$key.'" type="hidden" value="'.$list_pengajuan->NAMA_CUSTOMER.'">';
            $html .= '<input id="alamat_pemilik_'.$key.'" type="hidden" value="'.$list_pengajuan->ALAMAT_SURAT.'">';
            $html .= '<input id="kd_kelurahan_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_DESA.'">';
            $html .= '<input id="kd_kecamatan_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_KECAMATAN.'">';
            $html .= '<input id="kd_kota_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_KABUPATEN.'">';
            $html .= '<input id="kode_pos_'.$key.'" type="hidden" value="'.$list_pengajuan->KODE_POS.'">';
            $html .= '<input id="kd_propinsi_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_PROPINSI.'">';
            $html .= '<input id="jenis_pembayaran_'.$key.'" type="hidden" value="'.$list_pengajuan->JENIS_PENJUALAN.'">';
            $html .= '<input id="kd_dealer_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_DEALER.'">';
            $html .= '<input id="kd_fincoy_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_FINCOY.'">';
            $html .= '<input id="dp_'.$key.'" type="hidden" value="'.$list_pengajuan->UANG_MUKA.'">';
            $html .= '<input id="tenor_'.$key.'" type="hidden" value="'.$list_pengajuan->JANGKA_WAKTU.'">';
            $html .= '<input id="besar_cicilan_'.$key.'" type="hidden" value="'.$list_pengajuan->JUMLAH_ANGSURAN.'">';
            $html .= '<input id="kd_customer_'.$key.'" type="hidden" value="'.$list_pengajuan->KD_CUSTOMER.'">';
            $html .= '<input id="kd_item_'.$key.'" type="hidden" value="'.$list_pengajuan->KET_UNIT.'">';
            $html .= '<input id="no_suratjalan_'.$key.'" type="hidden" value="'.$list_pengajuan->NO_SURATJALAN.'">';
            $stnk_manual = ($list_pengajuan->BBN - $list_pengajuan->BPKB - $list_pengajuan->STCK - $list_pengajuan->PLAT_ASLI);
            if($reff == 1 && $list_pengajuan->TOTAL_STNK != NULL):
                if($list_pengajuan->PKB == 0 OR $list_pengajuan->SWDKLLJ == 0 OR $list_pengajuan->BBN == 0):
                    $html .= '<input id="biaya_stnk_'.$key.'" type="hidden" value="'.$stnk_manual.'">';
                    $html .= '<input id="status_pengurusan_'.$key.'" type="hidden" value="2">';
                else:
                    $html .= '<input id="biaya_stnk_'.$key.'" type="hidden" value="'.$list_pengajuan->TOTAL_STNK.'">';
                    $html .= '<input id="status_pengurusan_'.$key.'" type="hidden" value="1">';
                endif;
                // $html .= '<input id="biaya_stnk_'.$key.'" type="hidden" value="'.($list_pengajuan->TAHUN == date('Y')? $list_pengajuan->TOTAL_STNK : $stnk_manual).'">';
                $html .= '<input id="biaya_bpkb_'.$key.'" type="hidden" value="0">';
            elseif($reff == 2 && $list_pengajuan->TOTAL_BPKB != NULL):
                $html .= '<input id="biaya_stnk_'.$key.'" type="hidden" value="0">';
                $html .= '<input id="biaya_bpkb_'.$key.'" type="hidden" value="'.$list_pengajuan->TOTAL_BPKB.'">';
                $html .= '<input id="status_pengurusan_'.$key.'" type="hidden" value="1">';
            else:
                $html .= '<input id="biaya_stnk_'.$key.'" type="hidden" value="0">';
                $html .= '<input id="biaya_bpkb_'.$key.'" type="hidden" value="0">';
                $html .= '<input id="status_pengurusan_'.$key.'" type="hidden" value="1">';
            endif;
            $html .= '<input id="stck_'.$key.'" type="hidden" value="'.$list_pengajuan->STCK.'">';
            $html .= '<input id="plat_asli_'.$key.'" type="hidden" value="'.$list_pengajuan->PLAT_ASLI.'">';
            $html .= '<input id="admin_samsat_'.$key.'" type="hidden" value="'.$list_pengajuan->ADMIN_SAMSAT.'">';
            $html .= '<input id="bpkb_'.$key.'" type="hidden" value="'.$list_pengajuan->BPKB.'">';
            $html .= '<input id="bbnkb_'.$key.'" type="hidden" value="'.$list_pengajuan->BBNKB.'">';
            $html .= '<input id="pkb_'.$key.'" type="hidden" value="'.$list_pengajuan->PKB.'">';
            $html .= '<input id="swdkllj_'.$key.'" type="hidden" value="'.$list_pengajuan->SWDKLLJ.'">';
            $html .= '<input id="ss_'.$key.'" type="hidden" value="'.$list_pengajuan->SS.'">';
            $html .= '<input id="banpen_'.$key.'" type="hidden" value="'.$list_pengajuan->BANPEN.'">';
            $html .= '<input id="biaya_bbn_'.$key.'" type="hidden" value="'.$list_pengajuan->BBN.'">';
            //biaya_stnk_
            $html .= '<td class="table-nowarp">'.$no.'</td>';
            $html .= '<td class="table-nowarp"><input class="ajukan ajukan_'.$key.'" name="" value="1" type="checkbox"></td>';
            // $html .= '<td>'.($cek_pengajuan->message <= 0?'<input class="ajukan ajukan_'.$key.'" name="" value="1" type="checkbox">':'').'</td>';
            $html .= '<td class="table-nowarp">'.$list_pengajuan->NO_RANGKA.'</td>';
            $html .= '<td class="table-nowarp">'.$list_pengajuan->NO_MESIN.'</td>';
            $html .= '<td class="table-nowarp">'.$list_pengajuan->KET_UNIT.'</td>';
            $html .= '<td class="td-overflow" title="'.$list_pengajuan->NAMA_CUSTOMER.'">'.$list_pengajuan->NAMA_CUSTOMER.'</td>';
            $html .= '<td class="td-overflow-50" title="'.$list_pengajuan->ALAMAT_LENGKAP.'">'.$list_pengajuan->ALAMAT_LENGKAP.'</td>';
            $html .= '<td class="table-nowarp">'.$list_pengajuan->KODE_POS.'</td>';
            $html .= '</tr>';
            $key++;
        }
        return $html;
    }
    public function get_header_table($list, $status_stnk)
    {
        $html = '';
        $no = $this->input->get('page');
        foreach ($list as $key => $list_pengajuan) {
        $manual = $list_pengajuan->STATUS_MANUAL == 2 ? '(M)':'';
        $no ++;
        $html .= '<tr class="info bold">';
        $html .= '<input class="stnk_id_'.$key.'" type="hidden" value="'.$list_pengajuan->ID.'">';
        $html .= '<input class="no_pengajuan_normal_'.$key.'" type="hidden" value="'.$list_pengajuan->NO_TRANS.'">';
        //biaya_stnk_
        $html .= '<td>'.$no.'</td>';
        $html .= '<td><input class="ajukan_all ajukan_all_'.$key.'" name="" value="'.$key.'" type="checkbox"'.($status_stnk == 2? 'checked': '').'></td>';
        // $html .= '<td>'.($cek_pengajuan->message <= 0?'<input class="ajukan ajukan_'.$key.'" name="" value="1" type="checkbox">':'').'</td>';
        $html .= '<td class="no_pengajuan_'.$key.'" colspan="2">'.$list_pengajuan->NO_TRANS.$manual.'</td>';
        if($status_stnk == 0):
        $html .= '<td colspan="1">'.$list_pengajuan->NAMA_PENGURUS.'</td>';
        $html .= '<td colspan="2">'.tglfromSql($list_pengajuan->TGLMULAI_PENGURUSAN).'</td>';
        $html .= '<td colspan="1">'.tglfromSql($list_pengajuan->TGLSELESAI_PENGURUSAN).'</td>';
        else:
        $html .= '<td colspan="4">'.$list_pengajuan->NAMA_PENGURUS.'</td>';
        endif;
        $html .= '</tr>';
        $html .= $this->approve_detail_table($key, $list_pengajuan->ID, $status_stnk);
        if($status_stnk==1):
        $html .= '<tr class="total_stnk total_stnk_'.$key.'" style="display:none;">';
        $html .= '<th colspan="5"></th>';
        $html .= '<th><input style="text-align: right;" type="text" class="total_kendaraan_'.$key.' form-control biaya_stnk" value="0" readonly>';
        $html .= '</th>';
        $html .= '<th><input style="text-align: right;" type="text" class="total_biayapengajuan_'.$key.' form-control biaya_stnk" value="0" readonly>';
        $html .= '</th>';
        $html .= '<th>';
        $html .= '<input style="text-align: right;" type="text" id="'.$key.'" class="form-control total_biayaapprove total_biayaapprove_'.$key.' biaya_stnk" value="" readonly>';
        $html .= '<span class="custom_error_'.$key.'"></span>';
        $html .= '</th>';
        $html .= '</tr>';
        endif;
        }
        return $html;
    }
    public function approve_detail_table($key, $stnk_id, $status_stnk)
    {
        $html = '';
        $params= array(
            'custom' => 'TRANS_STNK_DETAIL.STATUS_STNK = '.$status_stnk,
            'stnk_id' => $stnk_id,
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=TRANS_STNK_DETAIL.KD_ITEM", "LEFT"),
                array("MASTER_WILAYAH MW" , "MW.KD_PROPINSI=TRANS_STNK_DETAIL.KD_PROPINSI", "LEFT"),
                array("SETUP_HARGAMOTOR HM" , "MW.KD_WILAYAH=HM.KD_WILAYAH AND TRANS_STNK_DETAIL.KD_ITEM = HM.KD_ITEM", "LEFT")
            ),
            'field' => 'TRANS_STNK_DETAIL.*, MP.NAMA_PASAR'/*,
                        "CASE WHEN (SELECT COUNT(BBN) FROM SETUP_HARGAMOTOR AS HM WHERE MW.KD_WILAYAH = HM.KD_WILAYAH AND TRANS_STNK_DETAIL.KD_ITEM = HM.KD_ITEM ) >0 THEN BBN ELSE 0 END BBN"'*/
        );
        $detail_stnk = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $params));
        foreach ($detail_stnk->message as $j => $list_pengajuan) {
            $biaya_total = ($list_pengajuan->REFF_SOURCE == 1? number_format($list_pengajuan->BIAYA_STNK):number_format($list_pengajuan->BIAYA_BPKB));
            $html .= '<tr class="tr-ajukan tr_ajukan_'.$key.'" id="'.($this->session->flashdata('tr-active') == $list_pengajuan->ID ? 'tr-active' : ' ').'" >';
            $html .= '<input class="id id_'.$key.'" type="hidden" value="'.$list_pengajuan->ID.'">';
            $html .= '<td></td>';
            if($list_pengajuan->STATUS_STNK==0):
                $html .= '<td><input id="'.$key.'" class="ajukan ajukan_'.$key.'" name="" value="1" type="checkbox"'.($status_stnk == 1? 'checked': '').'></td>';
            else:
                $html .= '<td></td>';
            endif;
            // $html .= '<td>'.($cek_pengajuan->message <= 0?'<input class="ajukan ajukan_'.$key.'" name="" value="1" type="checkbox">':'').'</td>';
            $html .= '<td>'.$list_pengajuan->NO_RANGKA.'</td>';
            $html .= '<td>'.$list_pengajuan->KD_MESIN.$list_pengajuan->NO_MESIN.'</td>';
            $html .= '<td>'.$list_pengajuan->NAMA_PEMILIK.'</td>';
            if($list_pengajuan->STATUS_STNK==1):
                $html .= '<td class="kendaraan_'.$key.'" style="text-align:right;">'.number_format('1').'</td>';
                $html .= '<td class="biaya_stnk biaya_stnk_diajukan_'.$key.'" style="text-align:right;">'.$biaya_total.'</td>';
                $html .= '<td class="biaya_stnk biaya_stnk_diapprove_'.$key.'" style="text-align:right;">'.$biaya_total.'</td>';
                $html .= '<input class="form-control biaya_stnk biaya_bbn_'.$key.'" type="hidden" value="'.number_format($list_pengajuan->BIAYA_BBN, 0).'">';
                $html .= '<input class="form-control status_stnk status_stnk_'.$key.'" type="hidden" value="'.($list_pengajuan->REFF_SOURCE == 1?3:2).'">';
            else:
                $html .= '<td>'.$list_pengajuan->ALAMAT_PEMILIK.'</td>';
                $html .= '<td>'.$list_pengajuan->KODE_POS.'</td>';
                if($list_pengajuan->STATUS_STNK==0):
                    $html .= '<td>'.$list_pengajuan->NAMA_PASAR.'</td>';
                    $html .= '<input class="form-control biaya_stnk biaya_stnk_'.$key.'" type="hidden" value="'.number_format($list_pengajuan->BIAYA_STNK, 0).'">';
                    $html .= '<input class="form-control biaya_stnk biaya_bpkb_'.$key.'" type="hidden" value="'.number_format($list_pengajuan->BIAYA_BPKB, 0).'">';
                    $html .= '<input class="form-control biaya_stnk biaya_bbn_'.$key.'" type="hidden" value="'.number_format($list_pengajuan->BIAYA_BBN, 0).'">';
                    $html .= '<input class="form-control status_stnk status_stnk_'.$key.'" type="hidden" value="1">';
                elseif($list_pengajuan->STATUS_STNK==2):
                    if($list_pengajuan->REFF_SOURCE == 1):
                        $html .= '<td><input id="'.$key.'-'.$j.'" class="form-control biaya_stnk biaya_stnk_'.$key.'" type="text" value="'.($list_pengajuan->TOTAL_STNK != NULL? number_format($list_pengajuan->TOTAL_STNK) : "").'" '.($list_pengajuan->TOTAL_STNK != NULL? "disabled" : "").' style="text-align:right;"><span class="custom_error_'.$key.'"></span></td>';
                    else:
                        $html .= '<td><input id="'.$key.'-'.$j.'" class="form-control biaya_stnk biaya_stnk_'.$key.'" type="text" value="'.($list_pengajuan->TOTAL_BPKB != NULL? number_format($list_pengajuan->TOTAL_BPKB) : "").'" '.($list_pengajuan->TOTAL_BPKB != NULL? "disabled" : "").' style="text-align:right;"><span class="custom_error_'.$key.'"></span></td>';
                    endif;
                    $html .= '<input class="form-control biaya_stnk biaya_bbn_'.$key.'" type="hidden" value="'.($list_pengajuan->BBN != NULL? number_format($list_pengajuan->BBN) : 0).'">';
                    $html .= '<input class="form-control status_stnk status_stnk_'.$key.'" type="hidden" value="2">';
                endif;
            endif;
            $html .= '</tr>';
        }
        // var_dump($detail_stnk);
        // $this->output->set_output(json_encode($detail_stnk));
        // exit();
        return $html;
    }
    public function plat_detail_table($header, $list, $status_stnk, $jenis, $tipe_trans, $jenis_penyerahan)
    {
        $html = '';
        $no = $this->input->get('page');
        foreach ($header as $key => $value):
        $no ++;
        $manual = $value->STATUS_MANUAL == 2 ? '(M)':'';
            $html .= '<tr class="info bold">';
            $html .= '<td>'.$no.'</td>';
            if(($jenis == 'BPKB' || $jenis == 'SRUT') AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                $bukti_header_disabled = $value->STATUS_PENERIMA == 2?'':'disabled-action';

                $html .= '<td class="table-nowarp">
                            <a class="active" href="'.base_url().'stnk/cetak_penyerahan?no_penyerahan='.$value->NO_PENYERAHAN.'&keterangan='.$jenis.'" role="button" target="blank">
                                <i class="fa fa-print" data-toggle="tooltip" data-placement="left" title="Print Penyerahan" ></i>
                            </a>
                            <a href="'.base_url($value->DIRECTORY_BUKTIPENYERAHAN).'" target="blank" class="'.$bukti_header_disabled.'">
                              <i data-toggle="tooltip" data-placement="left" title="Bukti" class="fa fa-list-alt text-success text"></i>
                            </a>
                          </td>';
                $html .= '<td colspan="8">'.$value->NO_PENYERAHAN.$manual.'</td>';
                $html .= '<td>
                            <form method="post" id="bukti_'.$key.'" enctype="multipart/form-data" action="'.base_url('stnk/update_bukti_penyerahan_group?jenis='.$jenis).'" >
                                <input name="no_penyerahan" type="hidden" value="'.$value->NO_PENYERAHAN.'">
                                <input name="keterangan" type="hidden" value="'.$jenis.'">
                                <input type="file" class="form-control-file directory_buktiterima" id="directory_buktiterima_plat" name="directory_buktiterima" aria-describedby="fileHelp" required >
                                <small id="fileHelp" class="form-text text-muted">*jpg, *jpag, *png.</small>
                            </form>
                        </td>';
                $html .= '<td>
                            <btn id="'.$key.'" class="btn btn-success file-btn btn-sm" data-toggle="tooltip" data-placement="left" title="Upload bukti"><i class="fa fa-upload"></i></btn>
                        </td>';
            }
            else{
                if($tipe_trans == 'pengajuan_plat'):
                    $html .= '<td><input class="ajukan_all ajukan_all_'.$key.'" name="" value="'.$key.'" type="checkbox" '.($value->COUNT_DETAIL == 0?'disabled':'').'></td>';
                else:
                    $html .= '<td></td>';
                endif;
                $html .= '<td colspan="12">'.$value->NO_TRANS.$manual.'</td>';
            }
            $html .= '</tr>';
            if($list && (is_array($list) || is_object($list))):
            foreach ($list as $key2 => $list_pengajuan):
                if(($jenis == 'BPKB' || $jenis == 'SRUT') AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                    $condition_1 = $value->NO_PENYERAHAN;
                    $condition_2 = $list_pengajuan->NO_PENYERAHAN;
                }
                else{
                    $condition_1 = $value->STNK_ID;
                    $condition_2 = $list_pengajuan->STNK_ID;
                }
                if($condition_1==$condition_2):
                    $print_penyerahan = $list_pengajuan->STATUS_STNK >= 4?'':'disabled-action';
                    $print_disabled = $list_pengajuan->STATUS_PENERIMA >= 1?'':'disabled-action';
                    $bukti_disabled = $list_pengajuan->STATUS_PENERIMA == 2?'':'disabled-action';
                    $edit_disabled = $list_pengajuan->STATUS_STNK >= 4?'':'disabled-action';
                    $delete_disabled = $list_pengajuan->STATUS_STNK == 1?'':'disabled-action';
                    $html .= '<tr>';
                    $html .= '<input class="kd_customer-'.$key.$key2.'" type="hidden" value="'.$list_pengajuan->KD_CUSTOMER.'">';
                    $html .= '<input class="id_'.$key.'" type="hidden" value="'.$list_pengajuan->DETAIL_ID.'">';
                    $html .= '<input class="status_stnk_'.$key.'" type="hidden" value="3">';
                    $html .= '<input class="keterangan" type="hidden" value="'.$jenis.'">';
                    $html .= '<input class="status_penerima" type="hidden" value="'.$list_pengajuan->STATUS_PENERIMA.'">';
                    //biaya_stnk_
                    $html .= '<td></td>';
                if($tipe_trans == 'pengajuan_plat' && $list_pengajuan->STATUS_STNK == 2){
                    $html .= '<td class="plat_'.$key.'"><input id="'.$key.'" class="ajukan ajukan_'.$key.'" name="" value="1" type="checkbox"></td>';
                }
                /*else{
                $html .= '<td class="plat_'.$key.'"></td>';
                }*/
                if(($jenis == 'BPKB' || $jenis == 'SRUT') AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                    $html .= '<td class="table-nowarp"></td>';
                }
                else{
                
                    $html .= '<td class="table-nowarp">';

                    if($tipe_trans == 'bukti'){
                        $html .= '    <a class="active '.$print_penyerahan.'" href="'.base_url().'stnk/cetak_penyerahan?no_rangka='.$list_pengajuan->NO_RANGKA.'&keterangan='.$jenis.'" role="button" target="blank">
                                <i class="fa fa-print" data-toggle="tooltip" data-placement="left" title="Print Penyerahan" ></i>
                            </a>
                            <a href="'.base_url($list_pengajuan->DIRECTORY_BUKTIPENYERAHAN).'" target="blank" class="'.$bukti_disabled.'">
                              <i data-toggle="tooltip" data-placement="left" title="Bukti" class="fa fa-list-alt text-success text"></i>
                            </a>';
                    }
                    else{
                    }

                    $html .= '<a id="delete-btn'.$no.'" class="delete-btn '.$delete_disabled.'" url="'.base_url('stnk/delete_plat/'.$list_pengajuan->DETAIL_ID).'">
                                  <i data-toggle="tooltip" data-placement="left" title="Batal Pengajuan" class="fa fa-trash text-danger text"></i>
                                </a>
                              </td>';
                }



                $html .= '<td class="no_rangka">'.$list_pengajuan->NO_RANGKA.'</td>';
                $html .= '<td>'.$list_pengajuan->NO_MESIN.'</td>';
                $html .= '<td><input type="text" class="form-control data_nomor '.($jenis == 'PLAT'?'no_plat':'').'" value="'.$list_pengajuan->DATA_NOMOR.'" '.($list_pengajuan->STATUS_STNK < 4 || $list_pengajuan->DATA_NOMOR != ''? "disabled" : "").' style="width:150px; '.($jenis == 'PLAT'?'text-transform: uppercase;':'').'"></td>';
                $html .= '<td><div class="input-group input-append date"><input type="text" class="form-control tgl_penerima" value="'.($list_pengajuan->TGL_PENERIMA == ''?date('d/m/Y'):tglfromSql($list_pengajuan->TGL_PENERIMA)).'" '.($list_pengajuan->STATUS_STNK < 4 || $list_pengajuan->DATA_NOMOR != ''? "disabled" : "").' style="width:150px;"><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div></td>';
                $html .= '<td><input id="nama_penerima-'.$key.$key2.'" style="width:150px;" type="text" class="form-control nama_penerima" value="'.$list_pengajuan->NAMA_PENERIMA.'" '.($list_pengajuan->STATUS_STNK >= 4 && $list_pengajuan->STATUS_PENERIMA == 0 && $list_pengajuan->DATA_NOMOR != ''? "" : "disabled").'></td>';
                $html .= '<td><input id="nohp-'.$key.$key2.'" style="width:150px;" type="text" class="form-control nohp" value="'.$list_pengajuan->NOHP.'" '.($list_pengajuan->STATUS_STNK >= 4 && $list_pengajuan->STATUS_PENERIMA == 0 && $list_pengajuan->DATA_NOMOR != ''? "" : "disabled").'></td>';
                $html .= '<td><textarea id="alamat-'.$key.$key2.'" style="width:200px;" rows="3" class="form-control alamat"  '.($list_pengajuan->STATUS_STNK >= 4 && $list_pengajuan->STATUS_PENERIMA == 0 && $list_pengajuan->DATA_NOMOR != ''? "" : "disabled").'>'.$list_pengajuan->ALAMAT.'</textarea></td>';
                $html .= '<td><div class="input-group input-append date"><input type="text" class="form-control tgl_penyerahan" value="'.($list_pengajuan->TGL_PENYERAHAN == ''?date('d/m/Y'):tglfromSql($list_pengajuan->TGL_PENYERAHAN)).'" '.($list_pengajuan->STATUS_STNK >= 4 && $list_pengajuan->STATUS_PENERIMA == 0 && $list_pengajuan->DATA_NOMOR != ''? "" : "disabled").' style="width:150px;"><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div></td>';

                if(($jenis == 'BPKB' || $jenis == 'SRUT') AND $tipe_trans == 'bukti' AND $jenis_penyerahan == 'leasing'){
                    $html .= '<td colspan="2"></td>';
                }
                else{
                    $html .= '<td>
                                <form method="post" id="bukti_'.$key.$key2.'" enctype="multipart/form-data" action="'.base_url('stnk/update_bukti_penyerahan?jenis='.$jenis).'" >
                                    <input name="no_rangka" type="hidden" value="'.$list_pengajuan->NO_RANGKA.'">
                                    <input name="keterangan" type="hidden" value="'.$jenis.'">
                                    <input type="file" class="form-control-file directory_buktiterima" id="directory_buktiterima_plat" name="directory_buktiterima" aria-describedby="fileHelp" required >
                                    <small id="fileHelp" class="form-text text-muted">*jpg, *jpag, *png.</small>
                                </form>
                            </td>';
                    $html .= '<td>
                                <btn id="'.$key.$key2.'" class="btn btn-success file-btn btn-sm '.$print_disabled.'" data-toggle="tooltip" data-placement="left" title="Upload bukti"><i class="fa fa-upload"></i></btn>
                            </td>';
                }
                if($jenis == 'PLAT'):
                    $html .= '<td class="table-nowarp">'.($list_pengajuan->STATUS_STNK >= 3?'sudah':'belum').'</td>';
                endif;
                // $html .= '<td class="table-nowarp">'.tglfromSql($list_pengajuan->TGL_PENYERAHAN).'</td>';
                $html .= '</tr>';
                endif;
            endforeach;
            endif;
        endforeach;
        return $html;
    }
    public function list_detail_table($header, $list, $status_stnk, $jenis)
    {
        $html = '';
        $no = $this->input->get('page');
        $pembatalan = isApproval('STBPD');
        foreach ($header as $key => $value):
            $manual = $value->STATUS_MANUAL == 2 ? '(M)':'';
            $disabled_cetak = $value->STATUS_CETAK == 1?'disabled-action':'';
            $no ++;
            $html .= '<tr class="info bold">';
            $html .= '<td class="table-nowarp">'.$no.'</td>';
            $html .= '<td class="text-center" style="white-space: nowrap;">
                        <a id="cetak-btn'.$key.'" data-trans="'.$value->NO_TRANS.'" data-href="'.base_url('stnk/cetak_biaya?n='.urlencode(base64_encode($value->NO_TRANS))).'" class="cetak-btn '.$disabled_cetak.'">
                          <i data-toggle="tooltip" data-placement="left" title="cetak biaya" class="fa fa-print"></i>
                        </a>
                        <a id="deletetrans-btn'.$key.'" data-key="'.$key.'" class="deletetrans-btn '.($pembatalan >= 0 ? '' : 'disabled-action').'">
                          <i data-toggle="tooltip" data-placement="left" title="Batal Pengajuan" class="fa fa-trash text-danger text"></i>
                        </a>
                        <a id="printallow-btn'.$key.'" data-key="'.$key.'" data-trans="'.$value->NO_TRANS.'" class="printallow-btn '.($pembatalan >= 0 && $value->STATUS_CETAK == 1 ? '' : 'hide').'">
                          <i data-toggle="tooltip" data-placement="left" title="Allow Print" class="fa fa-check text"></i>
                        </a>
                      </td>';
                        // <a id="deletetrans-btn'.$key.'" class="delete-btn '.($pembatalan >= 0 ? '' : 'disabled-action').'" url="'.base_url('stnk/deletetrans_plat/'.$value->NO_TRANS.'?row_status='.$value->HEADER_ROW_STATUS).'">
                        // HEADER_ROW_STATUS
            $html .= '<td colspan="3">'.$value->NO_TRANS.$manual.'</td>';
            $html .= '<td colspan="5">'.$value->NAMA_PENGURUS.'</td>';
            $html .= '</tr>';
            if(isset($list)):
                if($list->totaldata >0):
                    foreach ($list->message as $key2 => $list_pengajuan):
                        if($value->STNK_ID == $list_pengajuan->STNK_ID):
                            switch ($list_pengajuan->STATUS_STNK) {
                                case 0: $status_stnk = 'Input Pengurusan'; break;
                                case 1: $status_stnk = 'Verifikasi'; break;
                                case 2: $status_stnk = 'Approval Biaya'; break;
                                case 3: $status_stnk = $jenis == 'STNK'?'Approval Biaya':'Pengajuan Plat'; break;
                                case 4: $status_stnk = 'Approve kasir'; break;
                                case 5: $status_stnk = 'Penerimaan'; break;
                                case 6: $status_stnk = 'Penyerahan';break;
                                default:$status_stnk = 'Cetak file .udh'; break;
                            }
                            $edit_disabled = $list_pengajuan->STATUS_STNK >= 4?'':'disabled-action';
                            $status_delete = -1;
                            $PENGEMBALIAN = ($jenis == 'STNK'?$list_pengajuan->TGL_BALIK != '':$list_pengajuan->TGL_BALIK != '' && strlen($list_pengajuan->JENIS_BAYAR) >5);
                            if($PENGEMBALIAN ){
                                $status_delete = -3;
                            }
                            elseif($list_pengajuan->STATUS_STNK >= 1 && $list_pengajuan->STATUS_STNK <= 4){
                                $status_delete = -2;
                            }

                            $disabled_req_stck = $list_pengajuan->REQ_STCK >= 2?'disabled-action':'';
                            $disabled_req_bpkb = $list_pengajuan->REQ_BPKB >= 2?'disabled-action':'';
                            $disabled_req_admin_samsat = $list_pengajuan->REQ_ADMIN_SAMSAT >= 2?'disabled-action':'';
                            $disabled_req_plat_asli = $list_pengajuan->REQ_PLAT_ASLI >= 2?'disabled-action':'';

                            // var_dump($status_delete);exit();
                            $html .= '<tr>';
                            $html .= '<input class="kd_customer-'.$key.$key2.'" type="hidden" value="'.$list_pengajuan->KD_CUSTOMER.'">';
                            $html .= '<input class="id_'.$key.'" type="hidden" value="'.$list_pengajuan->DETAIL_ID.'">';
                            $html .= '<input class="status_stnk_'.$key.'" type="hidden" value="3">';
                            $html .= '<input class="keterangan" type="hidden" value="'.$jenis.'">';
                            $html .= '<input class="row_status_'.$key.'" type="hidden" value="'.$status_delete.'">';
                            //biaya_stnk_
                            $html .= '<td></td>';
                            /*else{
                            $html .= '<td class="plat_'.$key.'"></td>';
                            }*/
                            $html .= '<td class="table-nowarp text-center">
                                       <a id="delete-btn'.$list_pengajuan->DETAIL_ID.'" class="delete-btn '.($pembatalan == 0 && $list_pengajuan->STATUS_STNK == 0 && $list_pengajuan->KD_DEALER == $this->session->userdata("kd_dealer") ? '' : 'disabled-action').'" url="'.base_url('stnk/delete_plat/'.$list_pengajuan->DETAIL_ID).'">
                                          <i data-toggle="tooltip" data-placement="left" title="Batal Pengajuan" class="fa fa-trash text-danger text"></i>
                                        </a>
                                      </td>';
                            $html .= '<td class="text-center '.($jenis == 'BPKB'?'':'hide').'">
                                        <input data-url="'.base_url("stnk/reqdata_adminsamsat").'" data-id="'.$list_pengajuan->DETAIL_ID.'" name="req_admin_samsat" value="req_admin_samsat" type="checkbox" class="req-btn '.$disabled_req_admin_samsat.'" data-toggle="tooltip" data-placement="left" title="ADMIN_SAMSAT" '.($list_pengajuan->REQ_ADMIN_SAMSAT >= 1?'checked':'').'>
                                        <input data-url="'.base_url("stnk/reqdata_bpkb").'" data-id="'.$list_pengajuan->DETAIL_ID.'" name="req_bpkb" value="req_bpkb" type="checkbox" class="req-btn '.$disabled_req_bpkb.'" data-toggle="tooltip" data-placement="left" title="BPKB" '.($list_pengajuan->REQ_BPKB >= 1?'checked':'').'>
                                        <input data-url="'.base_url("stnk/reqdata_platasli").'" data-id="'.$list_pengajuan->DETAIL_ID.'" name="req_plat_asli" value="req_plat_asli" type="checkbox" class="req-btn '.$disabled_req_plat_asli.'" data-toggle="tooltip" data-placement="left" title="PLAT ASLI" '.($list_pengajuan->REQ_PLAT_ASLI >= 1?'checked':'').'>
                                        <input data-url="'.base_url("stnk/reqdata_stck").'" data-id="'.$list_pengajuan->DETAIL_ID.'" name="req_stck" value="req_stck" type="checkbox" class="req-btn '.$disabled_req_stck.'" data-toggle="tooltip" data-placement="left" title="STCK" '.($list_pengajuan->REQ_STCK >= 1?'checked':'').'>
                                      </td>';
                            $html .= '<td class="table-nowarp">'.$list_pengajuan->KD_ITEM.'</td>';
                            $html .= '<td class="no_rangka table-nowarp">'.$list_pengajuan->NO_RANGKA.'</td>';
                            $html .= '<td class="table-nowarp">'.$list_pengajuan->NO_MESIN.'</td>';
                            $html .= '<td class="td-overflow" title="'.$list_pengajuan->NAMA_PEMILIK.'">'.str_replace("\'","'",$list_pengajuan->NAMA_PEMILIK).'</td>';
                            $html .= '<td class="td-overflow-50" title="'.$list_pengajuan->ALAMAT_PEMILIK.'">'.str_replace("\'","'",$list_pengajuan->ALAMAT_PEMILIK).'</td>';
                            $html .= '<td class="table-nowarp">'.$status_stnk.'</td>';
                            $html .= '<td class="table-nowarp text-righy">'.number_format($list_pengajuan->BIAYA_PENGAJUAN,0).'</td>';
                            $html .= '</tr>';
                        endif;
                    endforeach;
                endif;
            endif;
        endforeach;
        return $html;
    }


    public function reqdata_stck()
    {
        $param = array(
            'id' => $this->input->post("id"),
            'req_stck' => $this->input->post("ajukan") == 'true'?1:0,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_detail_reqstck",$param, array(CURLOPT_BUFFERSIZE => 10));  
        $this->data_output($param, 'put');
    }
    public function reqdata_bpkb()
    {
        $param = array(
            'id' => $this->input->post("id"),
            'req_bpkb' => $this->input->post("ajukan") == 'true'?1:0,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_detail_reqbpkb",$param, array(CURLOPT_BUFFERSIZE => 10));  
        $this->data_output($param, 'put');
    }
    public function reqdata_adminsamsat()
    {
        $param = array(
            'id' => $this->input->post("id"),
            'req_admin_samsat' => $this->input->post("ajukan") == 'true'?1:0,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_detail_reqadminsamsat",$param, array(CURLOPT_BUFFERSIZE => 10));  
        $this->data_output($param, 'put');
    }
    public function reqdata_platasli()
    {
        $param = array(
            'id' => $this->input->post("id"),
            'req_plat_asli' => $this->input->post("ajukan") == 'true'?1:0,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_detail_reqplatasli",$param, array(CURLOPT_BUFFERSIZE => 10));  
        $this->data_output($param, 'put');
    }

    public function cetak_biaya()
    {
        $this->load->library('dompdf_gen');
        //total_stnk dari BBN di deteil spk kendaraan by : pak iswan 08-05-2018
        $param_detail= array(
            'no_trans' => base64_decode(urldecode($this->input->get("n"))),
            'jointable' =>array(
                array("TRANS_STNK_DETAIL AS STD","STD.STNK_ID=TRANS_STNK.ID AND STD.ROW_STATUS >= 0","LEFT"),
                array("MASTER_KABUPATEN AS MK","MK.KD_KABUPATEN=STD.KD_KOTA AND MK.ROW_STATUS >= 0","LEFT"),
                array(" MASTER_DEALER_V AS MDV","MDV.KD_DEALER=TRANS_STNK.KD_DEALER AND MDV.ROW_STATUS >= 0","LEFT"),
                array(" MASTER_P_TYPEMOTOR AS MPV","MPV.KD_ITEM=STD.KD_ITEM AND MPV.ROW_STATUS >= 0","LEFT"),
                array(" TRANS_SPK_DETAILKENDARAAN AS SPD","SPD.NO_RANGKA=STD.NO_RANGKA AND SPD.ROW_STATUS >= 0","LEFT"),
                array(" TRANS_SPK AS SPK","SPK.ID=SPD.SPK_ID AND SPK.ROW_STATUS >= 0","LEFT"),
            ),
            'field'     =>"TRANS_STNK.NO_TRANS, TRANS_STNK.TGLMULAI_PENGURUSAN, MK.NAMA_KABUPATEN, MDV.NAMA_DEALER, MDV.ALAMAT, MDV.NAMA_KABUPATEN AS KABUPATEN_DEALER, MPV.NAMA_TYPEMOTOR, MPV.KET_WARNA, SPK.TYPE_PENJUALAN, SPK.FAKTUR_PENJUALAN, SPK.TGL_SO, STD.*"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk", $param_detail));
        $data["ket"] = $this->input->get('keterangan');
        // $this->output->set_output(json_encode($data));
        // $this->load->view('sales/cetak_biaya', $data);
        $html = $this->load->view('sales/cetak_biaya', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'Envelope', 'landscape');
    }
    public function cetak_penyerahan()
    {
        $this->load->library('dompdf_gen');
        //total_stnk dari BBN di deteil spk kendaraan by : pak iswan 08-05-2018
        if($this->input->get('keterangan') == 'SRUT'){
            $param_detail= array(
                'no_penyerahan' => $this->input->get('no_penyerahan'),
                'no_rangka' => $this->input->get('no_rangka'),
                'jointable' =>array(
                    array("TRANS_SPK_DETAILKENDARAAN AS SDK", "SDK.NO_MESIN = TRANS_SRUT.NO_MESIN AND SDK.ROW_STATUS >= 0", "LEFT"),
                    array("TRANS_SPK_LEASING AS SDL", "SDL.SPK_ID=SDK.SPK_ID AND SDL.ROW_STATUS >= 0","LEFT"),
                    array("TRANS_SPK_DETAILCUSTOMER AS SDC", "SDC.SPK_ID=SDK.SPK_ID AND SDC.ROW_STATUS >= 0","LEFT"),
                    array("MASTER_CUSTOMER_VIEW AS MCV","MCV.KD_CUSTOMER=SDC.KD_CUSTOMER","LEFT"),
                    array("MASTER_DEALER AS MD","MD.KD_DEALER=TRANS_SRUT.KD_DEALER","LEFT"),
                    array("MASTER_KABUPATEN AS MK","MK.KD_KABUPATEN=MD.KD_KABUPATEN","LEFT")
                ),
                'field'     =>"MD.NAMA_DEALER, MCV.ALAMAT_SURAT,MK.NAMA_KABUPATEN AS WILAYAH_DEALER, TRANS_SRUT.*, '' AS KD_MESIN, SDL.KD_FINCOY, SDC.NAMA_BPKB AS NAMA_PEMILIK, '' DATA_NOMOR_PLAT"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/srut", $param_detail));

        }
        else{
            $param_detail= array(
                'no_penyerahan_bpkb' => $this->input->get('no_penyerahan'),
                'no_rangka' => $this->input->get('no_rangka'),
                'jointable' =>array(
                    array("MASTER_CUSTOMER_VIEW AS MCV","MCV.KD_CUSTOMER=TRANS_STNK_BUKTI_VIEW.KD_CUSTOMER","LEFT"),
                    array("MASTER_DEALER AS MD","MD.KD_DEALER=TRANS_STNK_BUKTI_VIEW.KD_DEALER","LEFT"),
                    array("MASTER_KABUPATEN AS MK","MK.KD_KABUPATEN=MD.KD_KABUPATEN","LEFT")
                ),
                'field'     =>"MD.NAMA_DEALER, MCV.ALAMAT_SURAT,MK.NAMA_KABUPATEN AS WILAYAH_DEALER, TRANS_STNK_BUKTI_VIEW.*"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bukti_view", $param_detail));

        }
        $data["ket"] = $this->input->get('keterangan');
        // $this->output->set_output(json_encode($data));
        // $this->load->view('sales/surat_penyerahan', $data);
        $html = $this->load->view('sales/surat_penyerahan', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');
    }

    public function get_rangka($list, $status_stnk)
    {
        $html = '';
        /*var_dump($list);
        exit();*/
        $params= array(
            'custom' => "TRANS_STNK_DETAIL.STATUS_STNK = ".$status_stnk,
            'stnk_id' => $list[0]->ID,
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=TRANS_STNK_DETAIL.KD_ITEM", "LEFT"),
                array("MASTER_WILAYAH MW" , "MW.KD_PROPINSI=TRANS_STNK_DETAIL.KD_PROPINSI", "LEFT"),
                array("SETUP_HARGAMOTOR HM" , "MW.KD_WILAYAH=HM.KD_WILAYAH AND TRANS_STNK_DETAIL.KD_ITEM = HM.KD_ITEM", "LEFT")
            ),
            'field' => 'TRANS_STNK_DETAIL.*, MP.NAMA_PASAR, HM.BBN'/*,
                        "CASE WHEN (SELECT COUNT(BBN) FROM SETUP_HARGAMOTOR AS HM WHERE MW.KD_WILAYAH = HM.KD_WILAYAH AND TRANS_STNK_DETAIL.KD_ITEM = HM.KD_ITEM ) >0 THEN BBN ELSE 0 END BBN"'*/
        );
        $detail_stnk = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $params));
        foreach ($detail_stnk->message as $list_pengajuan) {
            $html .= '<option value="'.$list_pengajuan->NO_RANGKA.'">'.$list_pengajuan->NO_RANGKA.'</option>';
        }
        // var_dump($detail_stnk);
        // $this->output->set_output(json_encode($detail_stnk));
        // exit();
        return $html;
    }
    public function get_mesin($no_rangka)
    {
        $params= array(
            'no_rangka' => $no_rangka,
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=TRANS_STNK_DETAIL.KD_ITEM", "LEFT"),
                array("MASTER_WILAYAH MW" , "MW.KD_PROPINSI=TRANS_STNK_DETAIL.KD_PROPINSI", "LEFT"),
                array("SETUP_HARGAMOTOR HM" , "MW.KD_WILAYAH=HM.KD_WILAYAH AND TRANS_STNK_DETAIL.KD_ITEM = HM.KD_ITEM", "LEFT")
            ),
            'field' => 'TRANS_STNK_DETAIL.*, TRANS_STNK_DETAIL.ID AS DETAIL_ID, MP.NAMA_PASAR, HM.BBN,
                        (SELECT TSD.ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.NO_RANGKA=\''.$no_rangka.'\' AND TSD.REFF_SOURCE=1 AND TSD.ROW_STATUS>=0 AND TSD.STATUS_STNK=4) AS STNKDETAIL_ID,
                        (SELECT TSD.ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.NO_RANGKA=\''.$no_rangka.'\' AND TSD.REFF_SOURCE=2 AND TSD.ROW_STATUS>=0 AND TSD.STATUS_STNK=4) AS BPKBDETAIL_ID'/*,
                        "CASE WHEN (SELECT COUNT(BBN) FROM SETUP_HARGAMOTOR AS HM WHERE MW.KD_WILAYAH = HM.KD_WILAYAH AND TRANS_STNK_DETAIL.KD_ITEM = HM.KD_ITEM ) >0 THEN BBN ELSE 0 END BBN"'*/
        );
        $data['stnk_header'] = json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail", $params));
        if($data['stnk_header'] && (is_array($data['stnk_header']->message) || is_object($data['stnk_header']->message))):
            foreach ($data['stnk_header']->message as $list_pengajuan) {
                $data['no_mesin'] = $list_pengajuan->KD_MESIN.$list_pengajuan->NO_MESIN;
            }
        else:
                $data['no_mesin'] = '';
        endif;
        $this->output->set_output(json_encode($data));
    }
    public function get_customer($kd_customer)
    {
        $params= array(
            'kd_customer' => $kd_customer,
            'jointable' => array(
                array("MASTER_DESA as DS","DS.KD_DESA=MASTER_CUSTOMER.KELURAHAN", "LEFT"),
                array("MASTER_KECAMATAN as KC","KC.KD_KECAMATAN=MASTER_CUSTOMER.KD_KECAMATAN", "LEFT"),
                array("MASTER_KABUPATEN as KB","KB.KD_KABUPATEN=MASTER_CUSTOMER.KD_KOTA", "LEFT"),
                array("MASTER_PROPINSI as PR","PR.KD_PROPINSI=MASTER_CUSTOMER.KD_PROPINSI", "LEFT")
            ),
            'field' => 'MASTER_CUSTOMER.*,
                        CONCAT(MASTER_CUSTOMER.ALAMAT_SURAT,\' Kel. \',DS.NAMA_DESA,\' Kec. \',KC.NAMA_KECAMATAN,\'  \',KB.NAMA_KABUPATEN,\'  \',PR.NAMA_PROPINSI) AS ALAMAT_LENGKAP'
        );
        $data['customer'] = json_decode($this->curl->simple_get(API_URL."/api/master_general/customer", $params));
        $this->output->set_output(json_encode($data));
    }
    // store data, update, delete
    // ===========================================================================
    public function store_stnk()
    {
        $tipe_no = $this->input->post("reff_source") == 1?'ST':'BP';
        $param = array(
            'no_trans' => $this->getnopo($tipe_no),
            'tgl_stnk' => $this->input->post("tahun_docno"),
            'nama_pengurus' => $this->input->post("nama_pengurus"),
            'kd_dealer' => $this->input->post("kd_dealer"),
            'kd_maindealer' => $this->input->post("kd_maindealer"),
            'status_manual' => $this->input->post("status_manual"),
            'tglmulai_pengurusan' => $this->input->post("tglmulai_pengurusan"),
            'tglselesai_pengurusan' => $this->input->post("tglselesai_pengurusan"),
            'created_by' => $this->session->userdata('user_id')
        );
        $hasil=$this->curl->simple_post(API_URL."/api/stnkbpkb/stnk",$param, array(CURLOPT_BUFFERSIZE => 10));  
        if(json_decode($hasil))
        {
            if(json_decode($hasil)->message>0){
                $this->store_detail(json_decode($hasil)->message);
            }
        }  
        $this->data_output($hasil, 'post');
    }
    public function store_detail($stnk_id)
    {
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'stnk_id' => $stnk_id,
                'no_rangka' => $detail[$i]['no_rangka'],
                'kd_mesin' => $detail[$i]['kd_mesin'],
                'no_mesin' => $detail[$i]['no_mesin'],
                'reff_source' => $detail[$i]['reff_source'],
                'no_stnk' => NULL,
                'no_pengajuan' => NULL,
                'nama_pemilik' => $detail[$i]['nama_pemilik'],
                'alamat_pemilik' => $detail[$i]['alamat_pemilik'],
                'kd_kelurahan' => $detail[$i]['kd_kelurahan'],
                'kd_kecamatan' => $detail[$i]['kd_kecamatan'],
                'kd_kota' => $detail[$i]['kd_kota'],
                'kode_pos' => $detail[$i]['kode_pos'],
                'kd_propinsi' => $detail[$i]['kd_propinsi'],
                'jenis_pembayaran' => $detail[$i]['jenis_pembayaran'],
                'kd_dealer' => $detail[$i]['kd_dealer'],
                'kd_fincoy' => $detail[$i]['kd_fincoy'],
                'dp' => $detail[$i]['dp'] != ''?$detail[$i]['dp']:0.00,
                'tenor' => $detail[$i]['tenor'],
                'besar_cicilan' => $detail[$i]['besar_cicilan'] != ''?$detail[$i]['besar_cicilan']:0.00,
                'kd_customer' => $detail[$i]['kd_customer'],
                'status_pembayaran' => NULL,
                'materai' => NULL,
                'status_stnk' => 0,
                'kd_item' => $detail[$i]['kd_item'],
                'no_suratjalan' => $detail[$i]['no_suratjalan'],
                'biaya_stnk' => $detail[$i]['biaya_stnk'] != ''?$detail[$i]['biaya_stnk']:0.00,
                'biaya_bpkb' => $detail[$i]['biaya_bpkb'] != ''?$detail[$i]['biaya_bpkb']:0.00,
                'status_pengurusan' => $detail[$i]['status_pengurusan'],
                'stck' => $detail[$i]['stck'] != ''?$detail[$i]['stck']:0.00,
                'plat_asli' => $detail[$i]['plat_asli'] != ''?$detail[$i]['plat_asli']:0.00,
                'admin_samsat' => $detail[$i]['admin_samsat'] != ''?$detail[$i]['admin_samsat']:0.00,
                'bpkb' => $detail[$i]['bpkb'] != ''?$detail[$i]['bpkb']:0.00,
                'bbnkb' => $detail[$i]['bbnkb'] != ''?$detail[$i]['bbnkb']:0.00,
                'pkb' => $detail[$i]['pkb'] != ''?$detail[$i]['pkb']:0.00,
                'swdkllj' => $detail[$i]['swdkllj'] != ''?$detail[$i]['swdkllj']:0.00,
                'ss' => $detail[$i]['ss'] != ''?$detail[$i]['ss']:0.00,
                'banpen' => $detail[$i]['banpen'] != ''?$detail[$i]['banpen']:0.00,
                'biaya_bbn' => $detail[$i]['biaya_bbn'] != ''?$detail[$i]['biaya_bbn']:0.00,
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            //var_dump($param);exit;
        }
    }
    public function store_plat()
    {
        $param = array(
            'id' => $this->input->post('id'),
            'bbnkb' => $this->input->post('bbnkb'),
            'pkb' => $this->input->post('pkb'),
            'swdkllj' => $this->input->post('swdkllj'),
            'stck' => $this->input->post('stck'),
            'plat_asli' => $this->input->post('plat_asli'),
            'admin_samsat' => $this->input->post('admin_samsat'),
            'bpkb' => $this->input->post('bpkb'),
            'ss' => $this->input->post('ss'),
            'no_rangka' => $this->input->post('no_rangka'),
            'no_mesin' => $this->input->post('no_mesin'),
            'no_stnk' => $this->input->post('no_stnk'),
            'no_plat' => $this->input->post('no_plat'),
            'no_bpkb' => $this->input->post('no_bpkb'),
            'status_plat' => ($this->input->post('status_plat'))?1:0,
            'created_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/trans_stnk_bpkb", $param, array(CURLOPT_BUFFERSIZE => 10));
        $method = "post";
        if(json_decode($hasil)->recordexists==TRUE){
            $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/trans_stnk_bpkb",$param, array(CURLOPT_BUFFERSIZE => 10));  
            $method = "put";
        }
        $location = $method == 'post'?base_url('stnk/add_plat?n='.urlencode(base64_encode($param['no_rangka']))):'';
        $this->data_output($hasil, $method, $location);
    }
    public function store_bukti()
    {
        // var_dump($this->input->post());exit;
        $ket_id = $this->input->post('ket_id');
        $param = array(
            'no_rangka' => $this->input->post('no_rangka'), 
            'keterangan' => $this->input->post('keterangan'), 
            'directory_buktiterima' => $this->store_file(),
            'nama_penerima' => $this->input->post('nama_penerima'), 
            'tgl_penerima' => $this->input->post('tgl_penerima'), 
            'alamat' => $this->input->post('alamat'), 
            'nohp' => $this->input->post('nohp'), 
            'status_penerima' => $this->input->post('status_penerima'), 
            'data_nomor' => strtoupper($this->input->post('data_nomor')), 
            'created_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_bukti", $param, array(CURLOPT_BUFFERSIZE => 10));
        $method = "post";
        if(json_decode($hasil)->recordexists==TRUE){
            $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_bukti",$param, array(CURLOPT_BUFFERSIZE => 10));  
            $method = "put";
        }
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, $method, '', $ket_id, 0);
    }
    public function update_bukti_penyerahan()
    {
        // var_dump($_FILES['directory_buktiterima']['name']);exit;
        $param = array(
            'no_rangka' => $this->input->post('no_rangka'), 
            'directory_buktipenyerahan' => $this->store_file(),
            'status_penerima' => 2, 
            'keterangan' => $this->input->post('keterangan'), 
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        // var_dump($param);exit;
        if($this->input->get('jenis')=='SRUT'){
            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/srut_penyerahan_file", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        else{
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bukti_penyerahan_file", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, 'put');
    }
    public function update_bukti_penyerahan_group()
    {
        // var_dump($_FILES['directory_buktiterima']['name']);exit;
        $param = array(
            'no_penyerahan' => $this->input->post('no_penyerahan'), 
            'directory_buktipenyerahan' => $this->store_file(),
            'status_penerima' => 2, 
            'keterangan' => $this->input->post('keterangan'), 
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        // var_dump($param);exit;
        if($this->input->get('jenis')=='SRUT'){
            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/srut_penyerahan_group_file", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        else{
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_bukti_penyerahan_group_file", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, 'put');
    }
    public function update_bukti()
    {
        // var_dump($_FILES);exit;
        $ket_id = $this->input->post('ket_id');
        $param = array(
            'no_rangka' => $this->input->post('no_rangka'), 
            'directory_buktipenyerahan' => $this->store_file(),
            'tgl_penyerahan' => $this->input->post('tgl_penerima'), 
            'status_penerima' => 1, 
            'keterangan' => $this->input->post('keterangan'), 
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_buktipenyerahan", $param, array(CURLOPT_BUFFERSIZE => 10));
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, 'put', '', $ket_id, 1);
    }
    public function store_detailbukti()
    {
        $detail = json_decode($this->input->post("detail"),true);
        $no_penyerahan = $this->getnopo('NP');
        // var_dump(count($detail));exit;
        if(count($detail) > 0):
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'no_penyerahan' => $no_penyerahan, 
                'jenis_penyerahan' => $detail[$i]['jenis_penyerahan'], 
                'no_rangka' => $detail[$i]['no_rangka'], 
                'keterangan' => $detail[$i]['keterangan'], 
                'nama_penerima' => $detail[$i]['nama_penerima'], 
                'tgl_penerima' => $detail[$i]['tgl_penerima'], 
                'tgl_penyerahan' => $detail[$i]['tgl_penyerahan'], 
                'alamat' => $detail[$i]['alamat'], 
                'nohp' => $detail[$i]['nohp'], 
                'status_penerima' => $detail[$i]['status_penerima'], 
                'data_nomor' => strtoupper($detail[$i]['data_nomor']), 
                'created_by' => $this->session->userdata("user_id"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_bukti_terima", $param, array(CURLOPT_BUFFERSIZE => 10));
            $method = "post";
            if(json_decode($hasil)->recordexists==TRUE && $detail[$i]['status_penerima'] == 1){
                $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_bukti_penyerahan",$param, array(CURLOPT_BUFFERSIZE => 10));  
                $method = "put";
            }
        }
        else:
            $hasil = '{"status":false,"message":"Tidak ada data yang disimpan"}';
            $method = "post";
        endif;
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, $method);
    }

    public function store_detailsrut()
    {
        $detail = json_decode($this->input->post("detail"),true);
        $no_penyerahan = $this->getnopo('NP');
        // var_dump(count($detail));exit;
        if(count($detail) > 0):
        for ($i=0; $i < count($detail); $i++) { 
            if($detail[$i]['status_penerima'] == 1){
                $status_srut = 6;
            }
            elseif($detail[$i]['status_penerima'] == 2){
                $status_srut = 7;
            }
            else{
                $status_srut = 5;
            }

            $param = array(
                'no_penyerahan' => $no_penyerahan, 
                'status_srut' =>  $status_srut,
                'no_rangka' => $detail[$i]['no_rangka'], 
                'nama_penerima' => $detail[$i]['nama_penerima'], 
                'tgl_penyerahan' => $detail[$i]['tgl_penyerahan'], 
                'alamat' => $detail[$i]['alamat'], 
                'no_hp' => $detail[$i]['nohp'], 
                'status_penerima' => $detail[$i]['status_penerima'], 
                'lastmodified_by' => $this->session->userdata("user_id")
            );

            $hasil=$this->curl->simple_put(API_URL."/api/transaksi/srut_norangka",$param, array(CURLOPT_BUFFERSIZE => 10));  
            $method = "put";
        }
        else:
            $hasil = '{"status":false,"message":"Tidak ada data yang disimpan"}';
            $method = "put";
        endif;
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, $method);
    }

    public function store_file()
    {
        // var_dump($_FILES['directory_buktiterima']['name']);exit;
        if(!empty($_FILES['directory_buktiterima']['name'])){
            $config = array();
            $_FILES['pathFile']['name'] = $_FILES['directory_buktiterima']['name'];
            $_FILES['pathFile']['type'] = $_FILES['directory_buktiterima']['type'];
            $_FILES['pathFile']['tmp_name'] = $_FILES['directory_buktiterima']['tmp_name'];
            $_FILES['pathFile']['error'] = $_FILES['directory_buktiterima']['error'];
            $_FILES['pathFile']['size'] = $_FILES['directory_buktiterima']['size'];
            $uploadpathGalery = './assets/uploads/';
            $config['upload_path'] = $uploadpathGalery;
            $config['allowed_types'] = 'jpag|jpg|png|pdf';
            // $config['min_size']             = 3000;
            // $config['min_width']            = 3000;
            // $config['min_height']           = 3000;
        // var_dump($config);exit;
            $this->load->library('upload', $config, 'pathupload');
            $this->pathupload->initialize($config);
            if (!is_dir('assets/uploads'))
            {
                mkdir('./assets/uploads', 0777, true);
            }
            $upload_path = $this->pathupload->do_upload('pathFile');
        // var_dump($upload_path);exit;
            if($upload_path){
                $pathfile = $this->pathupload->data();
                $file = "assets/uploads/".$pathfile['file_name'];
                return $file;
            }
            else{
                $data['message'] = $this->pathupload->display_errors();
                $data['status'] = FALSE;
                $this->output->set_output(json_encode($data));
            }
        };
    }
    public function update_statuscetak()
    {
        $param = array(
            'no_trans' => $this->input->get('no_trans'), 
            'status_cetak' => $this->input->get('status_cetak'), 
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_statuscetak", $param, array(CURLOPT_BUFFERSIZE => 10));
        // $this->output->set_output(json_encode($hasil));
        $this->data_output($hasil, 'put');
    }
   /* public function store_bukti()
    {
        $directory_buktiterima = json_decode($this->input->post("directory_buktiterima"),true);
        // var_dump($directory_buktiterima);exit;
        for ($i=0; $i < count($directory_buktiterima); $i++) { 
            $param = array(
                'no_rangka'            => $directory_buktiterima[$i]['no_rangka'],
                'keterangan'   => $directory_buktiterima[$i]['keterangan'],
                'status_penerima'    => $directory_buktiterima[$i]['status_penerima'],
                'directory_buktiterima'     => $directory_buktiterima[$i]['name'],
                'nama_penerima'     => $directory_buktiterima[$i]['nama_penerima'],
                'tgl_penerima'     => $directory_buktiterima[$i]['tgl_penerima'],
                'alamat'     => $directory_buktiterima[$i]['alamat'],
                'nohp'     => $directory_buktiterima[$i]['nohp'],
                'data_nomor'     => $directory_buktiterima[$i]['data_nomor'],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_bukti", $param, array(CURLOPT_BUFFERSIZE => 10));
            // $this->output->set_output(json_encode($data[$i]['id']));
        }
    }*/
    public function delete_biaya()
    {
        $data = json_decode($this->input->post("header"),true);
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'id' => $detail[$i]['id'],
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $data = array();
            $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/stnkbpkb/stnk_detail",$param));
        }
        $this->data_output($data, 'delete');
    }
    public function delete_plat($id)
    {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/stnkbpkb/stnk_detail",$param));
        $this->data_output($data, 'delete');
    }
    public function deleteall_detail()
    {
        // var_dump($this->input->post());exit();
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'id' => $detail[$i]['id'],
                'row_status' => $detail[$i]['row_status'],
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $data = array();
            $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/stnkbpkb/stnk_detail_rw",$param));
        }
        $this->data_output($data, 'delete');
    }
    public function deletetrans_plat($no_trans)
    {
        $param = array(
            'no_trans' => $no_trans,
            'row_status' => $this->input->get('row_status'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/stnkbpkb/stnk",$param));
        $this->data_output($data, 'delete');
    }
    public function update_status_array()
    {
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'id' => $detail[$i]['id'],
                'status_stnk' => $detail[$i]['status_stnk'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        $this->data_output($hasil, 'put');
    }
    public function update_status()
    {
        $param = array(
            'id' => $this->input->post('id'),
            'status_stnk' => $this->input->post('status_stnk'),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        // $this->data_output($hasil, 'post');
    }
    public function aprove_stnk()
    {
        // $header = json_decode($this->input->post("header"),true);
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            if($detail[$i]['ajukan'] == true):
            $param = array(
                'id' => $detail[$i]['id'],
                'status_stnk' => $detail[$i]['status_stnk'],
                'biaya_stnk' => $detail[$i]['biaya_stnk'],
                'biaya_bbn' => $detail[$i]['biaya_bbn'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_biayaapprove", $param, array(CURLOPT_BUFFERSIZE => 10));
            else:
            $param = array(
                'id' => $detail[$i]['id'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_delete(API_URL . "/api/stnkbpkb/stnk_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            endif;
            // $this->output->set_output(json_encode($data[$i]['id']));
        }
        $this->data_output($hasil, 'put');
    }
    public function aprove_pengajuan()
    {
        // $header = json_decode($this->input->post("header"),true);
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            if($detail[$i]['ajukan'] == true):
            $param = array(
                'id' => $detail[$i]['id'],
                'status_stnk' => $detail[$i]['status_stnk'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_status", $param, array(CURLOPT_BUFFERSIZE => 10));
            else:
            $param = array(
                'id' => $detail[$i]['id'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_delete(API_URL . "/api/stnkbpkb/stnk_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            endif;
            // $this->output->set_output(json_encode($data[$i]['id']));
        }
        $this->data_output($hasil, 'put');
    }
    public function aprove_biaya()
    {
        $data = json_decode($this->input->post("header"),true);
            // var_dump($data);
        for ($i=0; $i < count($data); $i++) { 
            $param = array(
                'no_pengajuan' => $data[$i]['no_pengajuan'],
                'total_biayapengajuan' => $data[$i]['total_biayapengajuan'],
                'total_biayaapprove' => $data[$i]['total_biayaapprove'],
                'tgl_approve' => $data[$i]['tgl_approve'],
                'approve_by' => $data[$i]['approve_by'],
                'created_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_post(API_URL . "/api/stnkbpkb/stnk_biaya", $param, array(CURLOPT_BUFFERSIZE => 10));
            // var_dump($hasil);
        }
        // $this->store_biaya();
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'id' => $detail[$i]['id'],
                'status_stnk' => $detail[$i]['status_stnk'],
                // 'biaya_stnk' => $detail[$i]['biaya_stnk'],
                // 'biaya_bbn' => $detail[$i]['biaya_bbn'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_status", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        $this->data_output($hasil, 'post');
    }
    public function store_biaya()
    {
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'id' => $detail[$i]['id'],
                'status_stnk' => $detail[$i]['status_stnk'],
                'biaya_bpkb' => $detail[$i]['biaya_stnk'],
                'biaya_stnk' => $detail[$i]['biaya_stnk'],
                'biaya_bbn' => $detail[$i]['biaya_bbn'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            if($detail[$i]['reff_source'] == 1)
            {
                $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_biayaapprove", $param, array(CURLOPT_BUFFERSIZE => 10));
            }
            else{
                $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/bpkb_biayaapprove", $param, array(CURLOPT_BUFFERSIZE => 10));
            }
        }
        $this->data_output($hasil, 'put');
    }
    public function aprove_biaya_detail($id_stnkbiaya=NULL)
    {
        $data = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($data); $i++) { 
            $param = array(
                'id' => $data[$i]['id'],
                'id_stnkbiaya' => $data[$i]['status_stnk'] == 3 ? $id_stnkbiaya : NULL,
                'status_stnk' => $data[$i]['status_stnk'],
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $hasil = $this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_approve_biayadetail", $param, array(CURLOPT_BUFFERSIZE => 10));
            // var_dump($hasil);
        }
        $this->data_output($hasil, 'put');
    }
    public function store_birojasa($reff)
    {
        $param = array(
            'kd_birojasa'=> $this->input->post("kd_birojasa"),
            'nama_birojasa'=> $this->input->post("nama_birojasa"),
            'nama_pengurus'=> $this->input->post("nama_pengurus"),
            'kd_maindealer'=>  $this->input->post("kd_maindealer"),
            'kd_dealer'=> $this->input->post("kd_dealer"),
            'alamat'=> $this->input->post("alamat"),
            'status_birojasa'=> 0,
            'created_by'=> $this->session->userdata('user_id')
        );
        /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
        $hasil=$this->curl->simple_post(API_URL."/api/stnkbpkb/birojasa",$param, array(CURLOPT_BUFFERSIZE => 10));
        $this->data_output($hasil, 'post', base_url('stnk/add_pengurusan/'.$reff));
    }
    /**
     * [podetail_list description]
     * @return [type] [description]
     */
    public function stnk_cetak(){
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_DETAIL.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_DETAIL.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $params= array(
            // 'kd_dealer' => '2NG',
            'custom' => "TRANS_STNK_DETAIL.STATUS_STNK = 2 AND TRANS_STNK_DETAIL.REFF_SOURCE = 1 AND TRANS_STNK_DETAIL.ROW_STATUS >= 0 AND ".$kd_dealer,
            'jointable' => array(
                array("MASTER_DEALER AS MD","MD.KD_DEALER=TRANS_STNK_DETAIL.KD_DEALER","LEFT"),
                array("TRANS_STNK TS" , "TS.ID=TRANS_STNK_DETAIL.STNK_ID", "LEFT")
            ),
            'field' => "
                    MD.KD_MAINDEALER,
                    TS.NO_TRANS,
                    TRANS_STNK_DETAIL.NO_RANGKA, 
                    TRANS_STNK_DETAIL.KD_MESIN, 
                    TRANS_STNK_DETAIL.NO_MESIN, 
                    TRANS_STNK_DETAIL.NAMA_PEMILIK, 
                    TRANS_STNK_DETAIL.ALAMAT_PEMILIK, 
                    TRANS_STNK_DETAIL.KD_KELURAHAN, 
                    TRANS_STNK_DETAIL.KD_KECAMATAN, 
                    TRANS_STNK_DETAIL.KD_KOTA, 
                    TRANS_STNK_DETAIL.KODE_POS, 
                    TRANS_STNK_DETAIL.KD_PROPINSI, 
                    TRANS_STNK_DETAIL.JENIS_PEMBAYARAN, 
                    TRANS_STNK_DETAIL.KD_DEALER, 
                    TRANS_STNK_DETAIL.KD_FINCOY, 
                    TRANS_STNK_DETAIL.DP, 
                    TRANS_STNK_DETAIL.TENOR, 
                    TRANS_STNK_DETAIL.BESAR_CICILAN
                    "
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $params));
        return $data;
    }
    /**
     * [createfile_udpo description]
     * @return [type] [description]
     */
    public function createfile_udstk(){
        $data=array();
        $data= $this->stnk_cetak();
        $namafile="";
        $isifile="";
        foreach($data->message as $key => $row){
            $namafile=  $row->KD_MAINDEALER."-".$row->KD_DEALER."-".date('ymdHis')."-".$row->NO_TRANS.".UDSTK";
            $isifile .= $row->NO_RANGKA.";";
            $isifile .= $row->KD_MESIN.";";
            $isifile .= $row->NO_MESIN.";";
            $isifile .= $row->NAMA_PEMILIK.";";
            $isifile .= $row->ALAMAT_PEMILIK.";";
            $isifile .= $row->KD_KELURAHAN.";";
            $isifile .= $row->KD_KECAMATAN.";";
            $isifile .= $row->KD_KOTA.";";
            $isifile .= $row->KODE_POS.";";
            $isifile .= $row->KD_PROPINSI.";";
            $isifile .= $row->JENIS_PEMBAYARAN.";";
            $isifile .= $row->KD_DEALER.";";
            $isifile .= $row->KD_FINCOY.";";
            $isifile .= number_format($row->DP, 0).";";
            $isifile .= $row->TENOR.";";
            $isifile .= number_format($row->BESAR_CICILAN, 0).PHP_EOL;
            // $isifile .= $row->STATUS_SJ.";";
        }
        $this->load->helper("download");
        force_download($namafile,$isifile);
    }
    /**
     * [podetail_list description]
     * @return [type] [description]
     */
    public function udh_cetak(){
        $param = array(
            'status_stnk' => 6, 
            'jointable' =>array(
                array("TRANS_SJKELUAR TS" , "TS.NO_SURATJALAN=TRANS_STNK_UDH.NO_SURATJALAN", "LEFT"),
                array("TRANS_SPK SPK" , "SPK.NO_SO=TS.NO_REFF", "LEFT"),
                array("MASTER_SALESMAN MS" , "MS.KD_SALES=SPK.KD_SALES", "LEFT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=TRANS_STNK_UDH.KD_ITEM", "LEFT")
            ),
            'field' => "TRANS_STNK_UDH.*, MP.KD_TYPEMOTOR, MP.KD_WARNA, TS.NO_REFF, MS.KD_HSALES, TS.KD_MAINDEALER, TS.KD_DEALER,
                (SELECT TOP 1 R.NAMA_BIROJASA FROM MASTER_BIROJASA R WHERE R.KD_BIROJASA = TRANS_STNK_UDH.NAMA_PENGURUS_STNK) AS BIROJASA_STNK,
                (SELECT TOP 1 R.NAMA_BIROJASA FROM MASTER_BIROJASA R WHERE R.KD_BIROJASA = TRANS_STNK_UDH.NAMA_PENGURUS_BPKB) AS BIROJASA_BPKB"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/udh", $param));
        // $this->output->set_output(json_encode($data));       
        return $data;
    }
    public function createfile_udh()
    {
        $data=array();
        $data= $this->udh_cetak();
        $namafile="";
        $isifile="";
        if($data && is_array($data->message) || is_object($data->message)):
            foreach($data->message as $key => $row){
                $namafile=  $row->KD_MAINDEALER."-".$row->KD_DEALER."-".date('ymdHis').".UDH";
                $isifile .= $row->NO_SURATJALAN.";";
                $isifile .= $row->KD_CUSTOMER.";";
                $isifile .= $row->NO_REFF.";";
                $isifile .= $row->KD_HSALES.";";
                $isifile .= $row->KD_TYPEMOTOR.";";
                $isifile .= $row->KD_WARNA.";";
                $isifile .= $row->NO_RANGKA.";";
                $isifile .= $row->KD_MESIN.$row->NO_MESIN.";";
                $isifile .= $row->DATA_NOMOR_STNK.";";
                $isifile .= $row->DATA_NOMOR_PLAT.";";
                $isifile .= $row->TGL_PENYERAHAN_STNK.";";
                $isifile .= $row->NAMA_PENERIMA_STNK.";";
                $isifile .= $row->BIROJASA_STNK.";";
                $isifile .= $row->DATA_NOMOR_BPKB.";";
                $isifile .= $row->TGL_PENYERAHAN_BPKB.";";
                $isifile .= $row->NAMA_PENERIMA_BPKB.";";
                $isifile .= $row->BIROJASA_BPKB.PHP_EOL;
                // $isifile .= $row->STATUS_SJ.";";
            }
        endif;
        $this->load->helper('file');
        if ( write_file(FCPATH.'assets/uploads/'.$namafile, $isifile) == TRUE)
        {
            foreach($data->message as $key => $rows){
                $param = array(
                    'no_rangka' => $rows->NO_RANGKA, 
                    'status_stnk' => 7
                );
                $data = json_decode($this->curl->simple_put(API_URL . "/api/stnkbpkb/stnk_detail_status", $param));
            }
            $data_return = array(
                'status' => true, 
                'message' => 'data berahasil didownload', 
                'file' => base_url().'stnk/download_udh?namafile='.$namafile
            );
            // force_download(FCPATH.'assets/uploads/'.$namafile, NULL);
            // redirect(base_url().'stnk/document_handling');
        }
        else{
            $data_return = array(
                'status' => false, 
                'message' => 'data gagal didownload'
            );
        }
        $this->output->set_output(json_encode($data_return));        
    }
    public function download_udh()
    {
        $this->load->helper("download");
        $namafile = $this->input->get('namafile');
        force_download(FCPATH.'assets/uploads/'.$namafile, NULL);
    }
    // typeahead
    // ===========================================================================
    public function dochand_typeahead()
    {
        $param = array(
            'field' => 'NAMA_PEMILIK',
            'groupby' => TRUE 
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail",$param));
        $param2 = array(
            'field' => 'KD_ITEM',
            'groupby' => TRUE 
        );
        $data["list2"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail",$param2));
        // var_dump($data); exit;
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_PEMILIK;
        }
        foreach ($data["list2"]->message as $key => $message2) {
            $data_message2[0][$key] = $message2->KD_ITEM;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message2[0]);
        $this->output->set_output(json_encode($result));
    }
    public function sj_typeahead()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? "TS.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TS.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => "TRANS_SJKELUAR_DETAIL.KET_UNIT NOT IN('KSU','HADIAH','BARANG') AND TRANS_SJKELUAR_DETAIL.NO_RANGKA NOT IN(SELECT TSTD.NO_RANGKA FROM TRANS_STNK_DETAIL AS TSTD WHERE TSTD.ROW_STATUS >= 0 AND TSTD.REFF_SOURCE=1 AND TSTD.NO_RANGKA IS NOT NULL) AND ".$kd_dealer,
            'jointable' => array(
                array("TRANS_SJKELUAR AS TS", "TS.ID=TRANS_SJKELUAR_DETAIL.ID_SURATJALAN", "LEFT")
            ),
            'field'     =>'TRANS_SJKELUAR_DETAIL.NAMA_ITEM, TRANS_SJKELUAR_DETAIL.NO_MESIN'/*,
            'groupby'   => TRUE*/
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar_detail",$param));
/*
        var_dump($data);
        exit();*/
        $result['keyword'] = [];
        if($data['list']->totaldata > 0){
            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->NAMA_ITEM;
                $data_message[1][$key] = $message->NO_MESIN;
            }
            $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        }
        $this->output->set_output(json_encode($result));
    }
    public function pengurus_typeahead()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => $kd_dealer,
            'field'     =>'NAMA_PENGURUS',
            'groupby'   => TRUE
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk",$param));
/*
        var_dump($data);
        exit();*/
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NAMA_PENGURUS;
        }
        $result['nama_pengurus'] = ($data_message[0]);
        $this->output->set_output(json_encode($result));
    }
    // generate code number
    // ===========================================================================
    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo($kd_docno){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),// substr($this->input->post('tahun_docno'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'STNK',
            'reset_docno' => 12,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'limit'     => 1,
            'offset'    => 0
        );
        $bulan_kirim =date('m');// substr($this->input->post('tahun_docno'), 3, 2);
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
    function paybill_kasir($ajax=null,$detail=null){
        $param=array(
            //'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("TRANS_STNK S","S.ID=TRANS_STNK_DETAIL.STNK_ID","LEFT"),
                array("SETUP_TRANS_VS_ACC AC","AC.KD_TRANS=(CASE WHEN LEFT(S.NO_TRANS,2)='ST' THEN 'P_STNK' ELSE 'P_BPKB' END)","LEFT")
            ),
            'field'     =>"S.ID,S.NO_TRANS,S.TGL_STNK,S.KD_DEALER,S.KD_MAINDEALER,S.NAMA_PENGURUS,STATUS_PEMBAYARAN,
                           STNK_ID,{fn CONCAT(KD_MESIN,NO_MESIN)}NO_MESIN,NO_RANGKA,KD_ITEM,(SELECT TOP 1 NAMA_CUSTOMER FROM MASTER_CUSTOMER M WHERE M.KD_CUSTOMER=TRANS_STNK_DETAIL.KD_CUSTOMER)AS NAMA_CUSTOMER,KD_CUSTOMER,BIAYA_BBN,BIAYA_BPKB,BIAYA_STNK,JENIS_BAYAR,
                           STCK,PLAT_ASLI,BPKB,BBNKB,PKB,SWDKLLJ,ADMIN_SAMSAT,SS,BANPEN,STATUS_STNK,AC.KD_AKUN,AC.POSISI_AKUN,TGL_PINJAM,TGL_BALIK,STATUS_PENGURUSAN,
                           REQ_STCK,REQ_BPKB,REQ_PLAT_ASLI,REQ_ADMIN_SAMSAT",
            'custom'    => "S.KD_DEALER='".$this->session->userdata("kd_dealer")."'",
        );
        if($this->input->get('no_trans')){
            $param["custom"] .= " AND S.NO_TRANS='".$this->input->get("no_trans")."'";
        }
        if($this->input->get('p')=='b'){
            //$param["custom"] .= "AND TRANS_STNK_DETAIL.STNK_ID IN(CASE WHEN LEFT(S.NO_TRANS,2)='BP' THEN (SELECT DISTINCT STNK_ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.ROW_STATUS >=0 AND TSD.STATUS_STNK >= 4 AND (LEN(REPLACE(JENIS_BAYAR,',','')) BETWEEN 1 AND 7 AND TSD.STNK_ID=S.ID) AND TSD.REFF_SOURCE='2') ELSE (SELECT DISTINCT STNK_ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.ROW_STATUS >=0 AND TSD.STATUS_STNK >= 4 AND TSD.REFF_SOURCE='1' AND LEN(ISNULL(JENIS_BAYAR,''))=0 AND TSD.STNK_ID=S.ID) END)";
            $param["custom"] .= "AND TRANS_STNK_DETAIL.STNK_ID = S.ID";
        }
        $data=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail",$param));     
        if($ajax==true){
            // print_r($data->param);
            if($data){
                if($data->totaldata>0){
                    echo json_encode($data->message);  
                }
            }
        }else{
            return $data;
        }
    }   
    /**
     * [data_output description]
     * @param  [type] $hasil [description]
     * @return [type]        [description]
     */
    function data_output($hasil = NULL, $method = '', $location = '', $ket_id='', $status_penerima='') {
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
    function list_pengajuan_approve($typeahead=true,$pengurusOnly=null){
        $result="[]";
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_maindealer' =>$this->session->userdata("kd_maindealer")
            , 'custom'    =>" TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE IS NOT NULL AND TS.ID IN(SELECT TSD.STNK_ID FROM TRANS_STNK_DETAIL TSD WHERE TSD.STATUS_STNK =3)"
            ,'jointable' =>array(
                array("TRANS_STNK TS","TS.NO_TRANS=TRANS_STNK_BIAYA.NO_PENGAJUAN","LEFT"),
                array("MASTER_BIROJASA MB","MB.KD_BIROJASA=TS.NAMA_PENGURUS AND MB.KD_DEALER = TS.KD_DEALER","LEFT")
            )
        );
        if($pengurusOnly==true){
            $param['field' ] = "MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA";
            $param['orderby']= "MB.NAMA_PENGURUS,MB.NAMA_BIROJASA";
            $param["groupby"]= TRUE;
            $param["custom"] = str_replace("TSD.STATUS_STNK=3)","", $param["custom"])."(TSD.STATUS_STNK < 4 OR TSD.TGL_PINJAM IS NULL OR RIGHT(TSD.STATUS_PEMBAYARAN,4) NOT IN('BPSA','APSB','BAPS','ABPS')) AND TSD.KD_DEALER='".$this->session->userdata("kd_dealer")."')";
        }else{
            $param['field']= "TS.ID,TS.NO_TRANS,MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA,TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE, TRANS_STNK_BIAYA.TOTAL_BIAYAPENGAJUAN,CASE WHEN LEFT(TS.NO_TRANS,2)='ST' THEN 'STNK' ELSE 'BPKB' END JENIS_DOC, RTRIM(CONVERT(CHAR,TS.TGL_STNK,103))TGL_STNK";
             $param['orderby']= "MB.NAMA_PENGURUS,MB.NAMA_BIROJASA";
        }
        if($this->input->get("p")){
            $param["custom"] .= "AND TS.NAMA_PENGURUS ='".$this->input->get("p")."'";
            $param["order"] ="RIGHT(TS.NO_TRANS,6)";
        }
        $data=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_biaya",$param));
        
        if($data){
            if($data->totaldata>0){
                $result= ($data->message);
            }
        }
        if($typeahead==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    /**
     * get data nama pengurus dan nomor pengurusan di kasir
     * @param  [type] $debug        [description]
     * @param  [type] $pengurusOnly [description]
     * @return [type]               [description]
     */
    function get_pengurusan($debug=null,$pengurusOnly=null){
        $result=array();$data=array();
        $param=array(
            'custom'    =>"TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE IS NOT NULL AND MB.KD_BIROJASA IS NOT NULL AND TS.KD_DEALER IN('".$this->session->userdata("kd_dealer")."')"
        );
        if($this->input->get('p') && $this->input->get("t")){
            $param["jointable"]=array(
                    array("TRANS_STNK TS","TS.NO_TRANS=TRANS_STNK_BIAYA.NO_PENGAJUAN","LEFT"),
                    array("TRANS_STNK_DETAIL TSD","TSD.STNK_ID=TS.ID","LEFT"),
                    array("MASTER_BIROJASA MB","MB.KD_BIROJASA=TS.NAMA_PENGURUS AND MB.KD_DEALER = TS.KD_DEALER","LEFT")
                );
        }else if($this->input->get('p') && !$this->input->get("t")){
            $param["jointable"]=array(
                    array("TRANS_STNK TS","TS.NO_TRANS=TRANS_STNK_BIAYA.NO_PENGAJUAN AND TS.ROW_STATUS >=0","LEFT"),
                    array("MASTER_BIROJASA MB","MB.KD_BIROJASA=TS.NAMA_PENGURUS AND MB.KD_DEALER = TS.KD_DEALER","LEFT"),
                    array("TRANS_STNK_DETAIL AS TSD","TSD.STNK_ID=TS.ID AND TSD.ROW_STATUS >=0","LEFT","STNK_ID,STATUS_STNK,JENIS_BAYAR,ROW_STATUS")
                );
        }else{
            $param["jointable"]=array(
                    array("TRANS_STNK TS","TS.NO_TRANS=TRANS_STNK_BIAYA.NO_PENGAJUAN","LEFT"),
                    array("MASTER_BIROJASA MB","MB.KD_BIROJASA=TS.NAMA_PENGURUS AND MB.KD_DEALER = TS.KD_DEALER","LEFT"),
                    array("TRANS_STNK_DETAIL AS TSD","TSD.STNK_ID=TS.ID","LEFT")
                );
        }
        if($this->input->get('t')){
            //$param["customs"] .=($this->input->get("p"))?" AND TS.ID IN(CASE WHEN LEFT(TS.NO_TRANS,2)='BP' THEN (SELECT DISTINCT STNK_ID FROM TRANS_STNK_DETAIL AS TSD1 LEFT JOIN TRANS_STNK AS S ON S.ID=TSD1.STNK_ID WHERE TSD1.ROW_STATUS >= 0 AND TSD1.STATUS_STNK >= 4 AND (LEN(REPLACE(TSD1.JENIS_BAYAR,',','')) BETWEEN 1 AND 7) AND TSD1.REFF_SOURCE='2' AND S.NAMA_PENGURUS IN('".$this->input->get("p")."')) ELSE (SELECT DISTINCT STNK_ID FROM TRANS_STNK_DETAIL AS TSD2 LEFT JOIN TRANS_STNK AS S1 ON S1.ID=TSD2.STNK_ID WHERE TSD2.ROW_STATUS >= 0 AND TSD2.STATUS_STNK > 4 AND TSD2.REFF_SOURCE='1' AND LEN(ISNULL(JENIS_BAYAR,''))=0 AND S1.NAMA_PENGURUS IN('".$this->input->get("p")."')) END )": " AND TSD.STATUS_STNK > 4";
            $param["custom"] .=($this->input->get("p"))? 
            " AND TSD.TGL_PINJAM IS NOT NULL AND (TSD.TGL_BALIK IS NULL OR LEN(TSD.JENIS_BAYAR) BETWEEN 3 AND 8)": 
            " AND TSD.STATUS_STNK > 4";
            if($this->input->get("p")){
                $param["custom"] .=" AND TS.NAMA_PENGURUS IN('".$this->input->get("p")."') ";
                $param["field"]="TS.ID,TS.NO_TRANS,MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA,SUM(TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE) AS TOTAL_BIAYAAPPROVE,SUM(TRANS_STNK_BIAYA.TOTAL_BIAYAPENGAJUAN) AS TOTAL_BIAYAPENGAJUAN,CASE WHEN LEFT(TS.NO_TRANS,2)='ST' THEN 'STNK' ELSE 'BPKB' END AS JENIS_DOC, RTRIM(CONVERT(CHAR,TS.TGL_STNK,103)) AS TGL_STNK";
                $param["groupby_text"]="TS.ID,TS.NO_TRANS,MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA,TS.TGL_STNK";
                $param["orderby"] ="RIGHT(TS.NO_TRANS,6)";
            }
        }
        if($pengurusOnly==true){
            $param['field' ] = "MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA";
            $param["groupby"]= TRUE;
            $param['orderby']= "MB.NAMA_PENGURUS,MB.NAMA_BIROJASA";
        }
        if($this->input->get("p") && !$this->input->get('t')){
            $param["custom"] .= " AND TS.NAMA_PENGURUS IN('".$this->input->get("p")."') AND TSD.STATUS_STNK <= 4 AND (RTRIM(LTRIM(TSD.JENIS_BAYAR)) NOT IN('ABPS','BAPS','BPSA','APSB') OR TSD.JENIS_BAYAR IS NULL) AND TS.NO_TRANS IS NOT NULL";
            $param["field"]="TS.ID,TS.NO_TRANS,MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA,SUM(TRANS_STNK_BIAYA.TOTAL_BIAYAAPPROVE) AS TOTAL_BIAYAAPPROVE, SUM(TRANS_STNK_BIAYA.TOTAL_BIAYAPENGAJUAN) AS TOTAL_BIAYAPENGAJUAN, CASE WHEN LEFT(TS.NO_TRANS,2)='ST' THEN 'STNK' ELSE 'BPKB' END AS JENIS_DOC, RTRIM(CONVERT(CHAR,TS.TGL_STNK,103)) AS TGL_STNK,TSD.STATUS_STNK";
            $param["groupby_text"]="TS.ID,TS.NO_TRANS,MB.KD_BIROJASA,MB.NAMA_PENGURUS,MB.NAMA_BIROJASA,TS.TGL_STNK,TSD.STATUS_STNK";
            $param["orderby"] ="RIGHT(TS.NO_TRANS,6)";
        }
        $data=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_biaya",$param));
        if($data){
            if($data->totaldata>0){
                $result= ($data->message);
            }
        }
        if($debug==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    function get_pengurus($debug=null){
        $result=array();$data=array();
        $param=array(
            'kd_dealer' => ($this->session->userdata("kd_dealer"))?$this->session->userdata("kd_dealer"):$this->session->userdat('user_id'),
            'field' =>'KD_BIROJASA,NAMA_BIROJASA,NAMA_PENGURUS',
            'groupby'=>TRUE,
            'orderby'=>'NAMA_BIROJASA,KD_BIROJASA'
        );
        if($this->input->get('t')){
            $param["custom"] = "STATUS_TRANS='BALIK'";
        }else{
            $param["custom"] = "STATUS_TRANS='PINJAM'";
        }
        $data=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_biaya/true",$param));
        // var_dump($data);print_r($param);
        if($data){
            if($data->totaldata>0){
                $result= ($data->message);
            }
        }
        if($debug==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    function get_nomorpengurusan($debug=null){
        $result=array();$data=array();
        $param=array(
            'kd_birojasa'  => $this->input->get("p"),
            'kd_dealer'     => ($this->session->userdata("kd_dealer"))?$this->session->userdata("kd_dealer"):$this->session->userdat('user_id'),
            'field'     =>'TRANS_STNK_PENGURUS_V.NO_TRANS,DOC AS JENIS_DOC,TRANS_STNK_PENGURUS_V.NAMA_PENGURUS,S.TGL_APPROVE AS TGL_STNK,S.TOTAL_BIAYAPENGAJUAN,S.TOTAL_BIAYAAPPROVE,ST.ID,TRANS_STNK_PENGURUS_V.STATUS_PENGURUSAN',
            'orderby'   =>'NO_TRANS',
            
        );
        if($this->input->get('t')){
            $param["custom"] = "JENIS='BALIK' ";//AND S.NO_PENGAJUAN IS NOT NULL";
        }else{
            $param["custom"] = "JENIS='PINJAM' ";// AND S.NO_PENGAJUAN IS NOT NULL";
        }
        $data=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_biaya/true",$param));
        
        if($data){
            if($data->totaldata>0){
                $result= ($data->message);
            }
        }
        if($debug==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    function get_nomorpengurusan2($debug=null){
        $data=array();$result=array();
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_birojasa' => $this->input->get('p'),
            'field' => "ID,NO_TRANS,NAMA_PENGURUS,STATUS_PENGURUSAN,TGL_STNK,JENIS_DOC,BPKB,ADMIN_SAMSAT,STCK,PLAT_ASLI,STNK, (BIAYA_BPKB) AS TOTAL_BIAYAAPPROVE",
            //'groupby_text'=> 'ID,NO_TRANS,NAMA_PENGURUS,STATUS_PENGURUSAN,TGL_STNK,JENIS_DOC,STATUS_TRANS,JENIS_BIAYA',
            'orderby' => 'ID'
        );
        if($this->input->get('t')){
            $param["custom"] = "STATUS_TRANS='BALIK' AND BIAYA_BPKB >0 ";//AND TGL_PINJAM IS NOT NULL";
        }else{
            $param["custom"] = "STATUS_TRANS='PINJAM' AND BIAYA_BPKB >0";//" AND TGL_PINJAM IS NULL";
        }
        $data=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_biaya/true",$param));
        if($data){
            if($data->totaldata>0){
                $result= ($data->message);
            }
        }
        if($debug==true){
            echo json_encode($result);
        }else{
            return $result;
        }
    }


    public function approve_insentif_picstnk(){
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(),
            'field' => 'KD_DEALER, GRAND_TOTAL,NO_PROSES,PERIODE,APPROVAL_STATUS,ROW_STATUS,CREATED_TIME',
            'groupby_text'=> '',
            'orderby' => 'CREATED_TIME DESC',
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : 'xxx'
        );
        $param["custom"] = "";
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,CREATED_TIME,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,CREATED_TIME,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/header_insentif_picstnk", $param));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );
		
		if($this->session->userdata("kd_group") == "DS"){
			$param_ds = array(
				'user_id' => $this->session->userdata("user_id"),
				'field' => 'KD_DEALER'
			);
			$data["dealer_ds"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/dealer_area_ds", $param_ds));
		}
        $data["totaldata"] = 0;//$totaldata;
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));

        $this->template->site('report_inspen/laporaninsentif_picstnk/insentif_picstnk_list', $data);
    }
    
    public function modal_approve_insentif_picstnk($no_proses) {
        $data = array();
        $param["custom"] = " NO_PROSES = '".$no_proses."'";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/detail_insentif_picstnk",$param));
        $data["no_proses"] = $no_proses;
        $this->load->view('report_inspen/laporaninsentif_picstnk/modal_approve_insentif_picstnk', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    
    public function approve_proses_insentif_picstnk($no_proses){
        $param = array(
            'no_proses' => $no_proses,
            'approval_status' => $this->input->post("aksi"),
            'approval_by' => $this->session->userdata("user_id")
        );
		
        $hasil=$this->curl->simple_put(API_URL."/api/stnkbpkb/header_insentif_picstnk",$param, array(CURLOPT_BUFFERSIZE => 10));  
        $this->curl->simple_put(API_URL."/api/stnkbpkb/detail_insentif_picstnk",$param, array(CURLOPT_BUFFERSIZE => 10));
        $this->session->set_flashdata('tr-active', $no_proses);

        $this->data_output($hasil, 'put');
    }
    
    public function view_list_insentif_picstnk(){
        $this->auth->validate_authen('stnk/view_list_insentif_picstnk');
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(),
            'field' => 'KD_DEALER, GRAND_TOTAL, NO_PROSES,PERIODE,APPROVAL_STATUS,ROW_STATUS,CREATED_TIME',
            'limit'=>20,
            'groupby_text'=> '',
            'orderby' => 'CREATED_TIME DESC',
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );
        $param["custom"] = "";
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,CREATED_TIME,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,CREATED_TIME,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/header_insentif_picstnk", $param));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], 
            'total_rows' => (($data["list"])) ? $data["list"]->totaldata : 0
        );
        
        $data["totaldata"] = 0;//$totaldata;
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->template->site('report_inspen/laporaninsentif_picstnk/list_insentif_picstnk_header', $data);
    }
    
    public function view_list_insentif_picstnk_header_print() {
        $data = array();
        $this->auth->validate_authen('stnk/view_list_insentif_picstnk');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(),
            'field' => 'KD_DEALER, GRAND_TOTAL, NO_PROSES,PERIODE,APPROVAL_STATUS,ROW_STATUS,CREATED_TIME',
            'limit'=>100,
            'groupby_text'=> '',
            'orderby' => 'CREATED_TIME DESC',
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );
        $param["custom"] = "";
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,CREATED_TIME,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,CREATED_TIME,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/header_insentif_picstnk", $param));
        
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $this->load->view('report_inspen/laporaninsentif_picstnk/list_insentif_picstnk_header_print',$data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    
    public function view_list_insentif_picstnk_detail_print($no_proses) {
        $data = array();
        $param["custom"] = " NO_PROSES = '".$no_proses."'";
		$data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/detail_insentif_picstnk",$param));
        $param_header = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(),
            'field' => 'KD_DEALER, GRAND_TOTAL, NO_PROSES,PERIODE,APPROVAL_STATUS,ROW_STATUS,CREATED_TIME',
            'no_proses' => $no_proses
        );
        $data["header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/header_insentif_picstnk", $param_header));
        
        $this->load->view('report_inspen/laporaninsentif_picstnk/list_insentif_picstnk_detail_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
}