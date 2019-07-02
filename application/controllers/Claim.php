<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim extends CI_Controller {
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

    public function claim_promo()
    {

        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $jenis = $this->input->get('jenis') ? $this->input->get('jenis') : "A";

        $param= array(
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'keyword'   => $this->input->get('keyword'), 
            'custom'    => $kd_dealer
        );


        if($this->input->get('kd_fincoy') != ''){
            $param['kd_fincoy'] = $this->input->get('kd_fincoy');
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/claim_promo", $param));
               
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        if($jenis == 'A'){
            $data['list_detail'] = $this->list_claim_a($data['list'], $data['pagination']);
        }
        else{
            $data['list_detail'] = $this->list_claim_b($data['list'], $data['pagination']);
        }

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",array('custom' => 'NAMA_DEALER IS NOT NULL')));

        $data["fincoy"] =json_decode($this->curl->simple_get(API_URL."/api/master/company_finance"));

        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/list_claimpromo',$data);
    }

    public function add_claimpromo()
    {

        $kd_dealer = $this->input->get('kd_dealer') ? "PK.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "PK.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param= array(
            'custom'    => "TRANS_SPK_DETAILKENDARAAN.NO_MESIN != '' AND TRANS_SPK_DETAILKENDARAAN.NO_MESIN IS NOT NULL AND (TRANS_SPK_DETAILKENDARAAN.CLAIM_STATUS IS NULL OR TRANS_SPK_DETAILKENDARAAN.CLAIM_STATUS = 0) AND ".$kd_dealer,
            'jointable' => array(
                array("TRANS_SPK PK" , "PK.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND PK.ROW_STATUS>=0", "LEFT"),
                array("MASTER_DEALER MD" , "MD.KD_DEALER=PK.KD_DEALER AND MD.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR TPM" , "TPM.KD_TYPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND TPM.KD_WARNA=TRANS_SPK_DETAILKENDARAAN.KD_WARNA AND TPM.ROW_STATUS>=0", "LEFT"),
                array("SETUP_SALESPROGRAM SP" , "SP.KD_SALESPROGRAM=TRANS_SPK_DETAILKENDARAAN.KD_SALESPROGRAM AND SP.KD_TYPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND SP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_LEASING TSL" , "TSL.SPK_ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND TSL.HASIL = 'Approve' AND TSL.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW as MC","MC.KD_CUSTOMER=PK.KD_CUSTOMER","LEFT"),
                array("TRANS_FILE as TF","TF.NO_RANGKA=TRANS_SPK_DETAILKENDARAAN.NO_RANGKA AND TF.ROW_STATUS >=0","LEFT"),
                array("TRANS_SJKELUAR as TSJ","TSJ.NO_REFF=PK.NO_SO AND TSJ.ROW_STATUS >=0","LEFT"),
                array("MASTER_COM_FINANCE FC" , "FC.KD_LEASING=TSL.KD_FINCOY AND FC.ROW_STATUS>=0", "LEFT")
            ),
            'field'     => "PK.KD_MAINDEALER,
                            PK.KD_DEALER,
                            TRANS_SPK_DETAILKENDARAAN.NO_MESIN,
                            PK.ID AS SPK_ID,
                            TRANS_SPK_DETAILKENDARAAN.KD_SALESPROGRAM,
                            MD.KD_DEALERAHM,
                            MD.NAMA_DEALER,
                            TRANS_SPK_DETAILKENDARAAN.NO_RANGKA,
                            TPM.KD_TYPEMOTOR AS KD_TIPE,
                            TPM.NAMA_TYPEMOTOR AS NAMA_TIPE,
                            TPM.KD_WARNA,
                            TPM.KET_WARNA AS DESKRIPSI_WARNA,
                            SP.NAMA_SALESPROGRAM,
                            PK.FAKTUR_PENJUALAN AS NO_FAKTUR_JUAL,
                            PK.TGL_SO AS TGL_FAKTUR_JUAL,
                            PK.TYPE_PENJUALAN AS TIPE_PENJUALAN,
                            TSL.KD_FINCOY,
                            FC.NAMA_LEASING AS NAMA_FINCOY,
                            MC.ALAMAT_SURAT AS ALAMAT,
                            MC.KD_KABUPATEN AS KD_KOTA,
                            MC.NAMA_CUSTOMER,
                            MC.NAMA_KABUPATEN AS NAMA_KOTA,
                            TF.TGL_TRANS AS TGL_FAKTUR_STNK,
                            TSJ.TGL_SURATJALAN AS TGL_BAST
                            "
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_detailkendaraan", $param));

        $data['list_detail'] = $this->list_addclaim($data['list']);

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",array('custom' => 'NAMA_DEALER IS NOT NULL')));

        $data["fincoy"] =json_decode($this->curl->simple_get(API_URL."/api/master/company_finance"));

        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('sales/add_claimpromo',$data);
    }

    public function get_claimpromo()
    {

        $kd_dealer = $this->input->get('kd_dealer') ? "PK.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "PK.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";


        $kd_fincoy = $this->input->get('kd_fincoy') ? " AND TSL.KD_FINCOY = '".$this->input->get('kd_fincoy')."'": "";
        

        $param= array(
            'custom'    => "TRANS_SPK_DETAILKENDARAAN.NO_MESIN != '' AND TRANS_SPK_DETAILKENDARAAN.NO_MESIN IS NOT NULL AND (TRANS_SPK_DETAILKENDARAAN.CLAIM_STATUS IS NULL OR TRANS_SPK_DETAILKENDARAAN.CLAIM_STATUS = 0) AND ".$kd_dealer.$kd_fincoy,
            'jointable' => array(
                array("TRANS_SPK PK" , "PK.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND PK.ROW_STATUS>=0", "LEFT"),
                array("MASTER_DEALER MD" , "MD.KD_DEALER=PK.KD_DEALER AND MD.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR TPM" , "TPM.KD_TYPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND TPM.KD_WARNA=TRANS_SPK_DETAILKENDARAAN.KD_WARNA AND TPM.ROW_STATUS>=0", "LEFT"),
                array("SETUP_SALESPROGRAM SP" , "SP.KD_SALESPROGRAM=TRANS_SPK_DETAILKENDARAAN.KD_SALESPROGRAM AND SP.KD_TYPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND SP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_LEASING TSL" , "TSL.SPK_ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND TSL.HASIL = 'Approve' AND TSL.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW as MC","MC.KD_CUSTOMER=PK.KD_CUSTOMER","LEFT"),
                array("TRANS_FILE as TF","TF.NO_RANGKA=TRANS_SPK_DETAILKENDARAAN.NO_RANGKA AND TF.ROW_STATUS >=0","LEFT"),
                array("TRANS_SJKELUAR as TSJ","TSJ.NO_REFF=PK.NO_SO AND TSJ.ROW_STATUS >=0","LEFT"),
                array("MASTER_COM_FINANCE FC" , "FC.KD_LEASING=TSL.KD_FINCOY AND FC.ROW_STATUS>=0", "LEFT")

            ),
            'field'     => "PK.KD_MAINDEALER,
                            PK.KD_DEALER,
                            TRANS_SPK_DETAILKENDARAAN.NO_MESIN,
                            PK.ID AS SPK_ID,
                            TRANS_SPK_DETAILKENDARAAN.KD_SALESPROGRAM,
                            MD.KD_DEALERAHM,
                            MD.NAMA_DEALER,
                            TRANS_SPK_DETAILKENDARAAN.NO_RANGKA,
                            TPM.KD_TYPEMOTOR AS KD_TIPE,
                            TPM.NAMA_TYPEMOTOR AS NAMA_TIPE,
                            TPM.KD_WARNA,
                            TPM.KET_WARNA AS DESKRIPSI_WARNA,
                            SP.NAMA_SALESPROGRAM,
                            PK.FAKTUR_PENJUALAN AS NO_FAKTUR_JUAL,
                            PK.TGL_SO AS TGL_FAKTUR_JUAL,
                            PK.TYPE_PENJUALAN AS TIPE_PENJUALAN,
                            TSL.KD_FINCOY,
                            FC.NAMA_LEASING AS NAMA_FINCOY,
                            MC.ALAMAT_SURAT AS ALAMAT,
                            MC.KD_KABUPATEN AS KD_KOTA,
                            MC.NAMA_CUSTOMER,
                            MC.NAMA_KABUPATEN AS NAMA_KOTA,
                            TF.TGL_TRANS AS TGL_FAKTUR_STNK,
                            TSJ.TGL_SURATJALAN AS TGL_BAST
                            "
        );



        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_detailkendaraan", $param));

        $data['list_detail'] = $this->list_addclaim($data['list']);
        $data['list_detail'] .= "<script type='text/javascript'>
                            $(document).ready(function(){
                            $('.claim_all').click(function(){

                            $('.claim_all:checkbox:checked').each(function(){
                            $('.claim').prop('checked', true);
                            });

                            $('.claim_all:checkbox:not(\":checked\")').each(function(){
                            $('.claim').prop('checked', false);
                            });

                            });
                            });
                            </script>";

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",array('custom' => 'NAMA_DEALER IS NOT NULL')));

        $data["fincoy"] =json_decode($this->curl->simple_get(API_URL."/api/master/company_finance"));

        $this->output->set_output(json_encode($data));

    }

    public function store_claimpromo()
    {
        $no_claim = $this->getnopo();

        $detail = json_decode($this->input->post("detail"),true);

        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'no_claim' => $no_claim,
                'kd_maindealer' => $detail[$i]['kd_maindealer'],
                'kd_dealer' => $detail[$i]['kd_dealer'],
                'kd_dealerahm' => $detail[$i]['kd_dealerahm'],
                'no_mesin' => $detail[$i]['no_mesin'],
                'spk_id' => $detail[$i]['spk_id'],
                'kd_salesprogram' => $detail[$i]['kd_salesprogram'],
                'claim_status' => 1,
                'nama_dealer' => $detail[$i]['nama_dealer'],
                'no_rangka' => $detail[$i]['no_rangka'],
                'kd_tipe' => $detail[$i]['kd_tipe'],
                'nama_tipe' => $detail[$i]['nama_tipe'],
                'kd_warna' => $detail[$i]['kd_warna'],
                'deskripsi_warna' => $detail[$i]['deskripsi_warna'],
                'nama_salesprogram' => $detail[$i]['nama_salesprogram'],
                'no_faktur_jual' => $detail[$i]['no_faktur_jual'],
                'tgl_faktur_jual' => $detail[$i]['tgl_faktur_jual'],
                'tgl_bast' => $detail[$i]['tgl_bast'],
                'tipe_penjualan' => $detail[$i]['tipe_penjualan'],
                'kd_fincoy' => $detail[$i]['kd_fincoy'],
                'nama_fincoy' => $detail[$i]['nama_fincoy'],
                'no_po_fincoy' => NULL,
                'tgl_po_fincoy' => NULL,
                'tgl_faktur_stnk' => $detail[$i]['tgl_faktur_stnk'],
                'alamat' => $detail[$i]['alamat'],
                'kd_kota' => $detail[$i]['kd_kota'],
                'nama_customer' => $detail[$i]['nama_customer'],
                'nama_kota' => $detail[$i]['nama_kota'],
                'tgl_claim' => $this->input->post('tahun_docno'),
                'created_by' => $this->session->userdata("user_id")
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/service/claim_promo", $param, array(CURLOPT_BUFFERSIZE => 10));

            // var_dump($hasil);exit();
            // $this->output->set_output(json_encode($data[$i]['id']));
        }

        $this->data_output($hasil, 'post', '',$no_claim);

    }

    public function list_addclaim($list)
    {
        $html = '';

        // var_dump($pagination);exit;

        $no = $this->input->get('page');
  

        $html .= '<div class="table-responsive">';
        $html .= '<table id="list_data" class="table table-striped table-bordered">';
        $html .= '<thead>';
        $html .= '<tr class="no-hover"><th colspan="16" ><i class="fa fa-list fa-fw"></i> List Claim Promo</th></tr>';
        $html .= '<tr>';
        $html .= '<th style="width:45px; vertical-align: middle;">No</th>';
        $html .= '<th style="width:50px; vertical-align: middle;"><input id="claim_all" class="claim_all" name="claim_all" value="1" type="checkbox"></th>';
        $html .= '<th>Nama</th>';
        $html .= '<th>Alamat</th>';
        $html .= '<th>Kota</th>';
        $html .= '<th>No. Rangka</th>';
        $html .= '<th>No. Mesin</th>';
        $html .= '<th>Tipe</th>';
        $html .= '<th>Warna</th>';
        $html .= '<th>No. Faktur</th>';
        $html .= '<th>Tgl Faktur</th>';
        $html .= '<th>Jenis Pembayaran</th>';
        $html .= '<th>No. PO Leasing</th>';
        $html .= '<th>Tgl PO Leasing</th>';
        $html .= '<th>KD Leasing</th>';
        $html .= '<th>Nama Leasing</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        if($list && (is_array($list->message) || is_object($list->message))):
            foreach ($list->message as $key => $list_claim) {
            $no ++;

            $html .= '<tr class="tr-ajukan">';

            $html .= '<input id="kd_maindealer_'.$key.'" type="hidden" value="'.$list_claim->KD_MAINDEALER.'">';
            $html .= '<input id="kd_dealer_'.$key.'" type="hidden" value="'.$list_claim->KD_DEALER.'">';
            $html .= '<input id="kd_dealerahm_'.$key.'" type="hidden" value="'.$list_claim->KD_DEALERAHM.'">';
            $html .= '<input id="no_mesin_'.$key.'" type="hidden" value="'.$list_claim->NO_MESIN.'">';
            $html .= '<input id="spk_id_'.$key.'" type="hidden" value="'.$list_claim->SPK_ID.'">';
            $html .= '<input id="kd_salesprogram_'.$key.'" type="hidden" value="'.$list_claim->KD_SALESPROGRAM.'">';
            $html .= '<input id="nama_dealer_'.$key.'" type="hidden" value="'.$list_claim->NAMA_DEALER.'">';
            $html .= '<input id="no_rangka_'.$key.'" type="hidden" value="'.$list_claim->NO_RANGKA.'">';
            $html .= '<input id="kd_tipe_'.$key.'" type="hidden" value="'.$list_claim->KD_TIPE.'">';
            $html .= '<input id="nama_tipe_'.$key.'" type="hidden" value="'.$list_claim->NAMA_TIPE.'">';
            $html .= '<input id="kd_warna_'.$key.'" type="hidden" value="'.$list_claim->KD_WARNA.'">';
            $html .= '<input id="deskripsi_warna_'.$key.'" type="hidden" value="'.$list_claim->DESKRIPSI_WARNA.'">';
            $html .= '<input id="nama_salesprogram_'.$key.'" type="hidden" value="'.$list_claim->NAMA_SALESPROGRAM.'">';
            $html .= '<input id="no_faktur_jual_'.$key.'" type="hidden" value="'.$list_claim->NO_FAKTUR_JUAL.'">';
            $html .= '<input id="tgl_faktur_jual_'.$key.'" type="hidden" value="'.tglfromSql($list_claim->TGL_FAKTUR_JUAL).'">';
            $html .= '<input id="tipe_penjualan_'.$key.'" type="hidden" value="'.$list_claim->TIPE_PENJUALAN.'">';
            $html .= '<input id="kd_fincoy_'.$key.'" type="hidden" value="'.$list_claim->KD_FINCOY.'">';
            $html .= '<input id="nama_fincoy_'.$key.'" type="hidden" value="'.$list_claim->NAMA_FINCOY.'">';
            $html .= '<input id="alamat_'.$key.'" type="hidden" value="'.$list_claim->ALAMAT.'">';
            $html .= '<input id="kd_kota_'.$key.'" type="hidden" value="'.$list_claim->KD_KOTA.'">';
            $html .= '<input id="nama_customer_'.$key.'" type="hidden" value="'.$list_claim->NAMA_CUSTOMER.'">';
            $html .= '<input id="nama_kota_'.$key.'" type="hidden" value="'.$list_claim->NAMA_KOTA.'">';
            $html .= '<input id="tgl_faktur_stnk_'.$key.'" type="hidden" value="'.tglfromSql($list_claim->TGL_FAKTUR_STNK).'">';
            $html .= '<input id="tgl_bast_'.$key.'" type="hidden" value="'.tglfromSql($list_claim->TGL_BAST).'">';
            


            $html .= '<td>'.$no.'</td>';
            $html .= '<td><input class="claim claim_'.$key.'" name="" value="1" type="checkbox"></td>';
            $html .= '<td>'.$list_claim->NAMA_CUSTOMER.'</td>';
            $html .= '<td>'.$list_claim->ALAMAT.'</td>';
            $html .= '<td>'.$list_claim->NAMA_KOTA.'</td>';
            $html .= '<td>'.$list_claim->NO_RANGKA.'</td>';
            $html .= '<td>'.$list_claim->NO_MESIN.'</td>';
            $html .= '<td>'.$list_claim->NAMA_TIPE.'</td>';
            $html .= '<td>'.$list_claim->DESKRIPSI_WARNA.'</td>';
            $html .= '<td>'.$list_claim->NO_FAKTUR_JUAL.'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_FAKTUR_JUAL).'</td>';
            $html .= '<td>'.$list_claim->TIPE_PENJUALAN.'</td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td>'.$list_claim->KD_FINCOY.'</td>';
            $html .= '<td>'.$list_claim->NAMA_FINCOY.'</td>';
            $html .= '</tr>';
            }

        else:
            $html .=  "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=15><b>Belum ada data / data tidak ditemukan</b></td></tr>";

        endif;

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    public function list_claim_a($list, $pagination)
    {
        $html = '';

        // var_dump($pagination);exit;

        $no = $this->input->get('page');
  

        $html .= '<div class="table-responsive">';
        $html .= '<table id="list_data" class="table table-striped table-bordered">';
        $html .= '<thead>';
        $html .= '<tr class="no-hover"><th colspan="13" ><i class="fa fa-list fa-fw"></i> List Claim Promo</th></tr>';
        $html .= '<tr>';
        $html .= '<th style="width:45px; vertical-align: middle;">No</th>';
        $html .= '<th>No. Rangka</th>';
        $html .= '<th>No. Mesin</th>';
        $html .= '<th>Tipe</th>';
        $html .= '<th>Warna</th>';
        $html .= '<th>Sales Program</th>';
        $html .= '<th>No. Faktur</th>';
        $html .= '<th>Tgl Faktur</th>';
        $html .= '<th>Tgl BAST</th>';
        $html .= '<th>Jenis Pembayaran</th>';
        $html .= '<th>KD Leasing</th>';
        $html .= '<th>No. PO Leasing</th>';
        $html .= '<th>Tgl PO Leasing</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        if($list && (is_array($list->message) || is_object($list->message))):
            foreach ($list->message as $key => $list_claim) {
            $no ++;

            $html .= '<tr>';

            $html .= '<input class="" type="hidden" value="'.$list_claim->ID.'">';
            //biaya_stnk_

            $html .= '<td>'.$no.'</td>';
            $html .= '<td>'.$list_claim->NO_RANGKA.'</td>';
            $html .= '<td>'.$list_claim->NO_MESIN.'</td>';
            $html .= '<td>'.$list_claim->NAMA_TIPE.'</td>';
            $html .= '<td>'.$list_claim->DESKRIPSI_WARNA.'</td>';
            $html .= '<td>'.$list_claim->NAMA_SALESPROGRAM.'</td>';
            $html .= '<td>'.$list_claim->NO_FAKTUR_JUAL.'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_FAKTUR_JUAL).'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_BAST).'</td>';
            $html .= '<td>'.$list_claim->TIPE_PENJUALAN.'</td>';
            $html .= '<td>'.$list_claim->KD_FINCOY.'</td>';
            $html .= '<td>'.$list_claim->NO_PO_FINCOY.'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_PO_FINCOY).'</td>';
            $html .= '</tr>';
            }

        else:
            $html .=  "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=12><b>Belum ada data / data tidak ditemukan</b></td></tr>";

        endif;

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<footer class="panel-footer">';
        $html .= '<div class="row">';

        $html .= '<div class="col-sm-5">';
        $html .= '<small class="text-muted inline m-t-sm m-b-sm"> ';
        $html .= ($list? ($list->totaldata==''?"":"<i>Total Data".$list->totaldata." items</i>") : '');
        $html .= '</small>';
        $html .= '</div>';
        $html .= '<div class="col-sm-7 text-right text-center-xs">';
        $html .= $pagination;
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</footer>';

        return $html;
    }


    public function list_claim_b($list, $pagination)
    {
        $html = '';

        // var_dump($pagination);exit;

        $no = $this->input->get('page');
  

        $html .= '<div class="table-responsive">';
        $html .= '<table id="list_data" class="table table-striped table-bordered">';
        $html .= '<thead>';
        $html .= '<tr class="no-hover"><th colspan="16" ><i class="fa fa-list fa-fw"></i> List Claim Promo</th></tr>';
        $html .= '<tr>';
        $html .= '<th style="width:45px; vertical-align: middle;">No</th>';
        $html .= '<th>Nama</th>';
        $html .= '<th>Alamat</th>';
        $html .= '<th>Kota</th>';
        $html .= '<th>No. Rangka</th>';
        $html .= '<th>No. Mesin</th>';
        $html .= '<th>Tipe</th>';
        $html .= '<th>Warna</th>';
        $html .= '<th>No. Faktur</th>';
        $html .= '<th>Tgl Faktur</th>';
        $html .= '<th>Jenis Pembayaran</th>';
        $html .= '<th>No. PO Leasing</th>';
        $html .= '<th>Tgl PO Leasing</th>';
        $html .= '<th>KD Leasing</th>';
        $html .= '<th>Nama Leasing</th>';
        $html .= '<th>Tgl BAST</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        if($list && (is_array($list->message) || is_object($list->message))):
            foreach ($list->message as $key => $list_claim) {
            $no ++;

            $html .= '<tr>';

            $html .= '<input class="" type="hidden" value="'.$list_claim->ID.'">';
            //biaya_stnk_

            $html .= '<td>'.$no.'</td>';
            $html .= '<td>'.$list_claim->NAMA_CUSTOMER.'</td>';
            $html .= '<td>'.$list_claim->ALAMAT.'</td>';
            $html .= '<td>'.$list_claim->NAMA_KOTA.'</td>';
            $html .= '<td>'.$list_claim->NO_RANGKA.'</td>';
            $html .= '<td>'.$list_claim->NO_MESIN.'</td>';
            $html .= '<td>'.$list_claim->NAMA_TIPE.'</td>';
            $html .= '<td>'.$list_claim->DESKRIPSI_WARNA.'</td>';
            $html .= '<td>'.$list_claim->NO_FAKTUR_JUAL.'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_FAKTUR_JUAL).'</td>';
            $html .= '<td>'.$list_claim->TIPE_PENJUALAN.'</td>';
            $html .= '<td>'.$list_claim->NO_PO_FINCOY.'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_PO_FINCOY).'</td>';
            $html .= '<td>'.$list_claim->KD_FINCOY.'</td>';
            $html .= '<td>'.$list_claim->NAMA_FINCOY.'</td>';
            $html .= '<td>'.tglfromSql($list_claim->TGL_BAST).'</td>';
            $html .= '</tr>';
            }

        else:
            $html .=  "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=15><b>Belum ada data / data tidak ditemukan</b></td></tr>";

        endif;

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<footer class="panel-footer">';
        $html .= '<div class="row">';

        $html .= '<div class="col-sm-5">';
        $html .= '<small class="text-muted inline m-t-sm m-b-sm"> ';
        $html .= ($list? ($list->totaldata==''?"":"<i>Total Data".$list->totaldata." items</i>") : '');
        $html .= '</small>';
        $html .= '</div>';
        $html .= '<div class="col-sm-7 text-right text-center-xs">';
        $html .= $pagination;
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</footer>';

        return $html;
    }

    public function claim_cetak()
    {

        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param= array(
            'keyword'   => $this->input->get('keyword'), 
            'custom'    => $kd_dealer
        );


        if($this->input->get('kd_fincoy') != ''){
            $param['kd_fincoy'] = $this->input->get('kd_fincoy');
        }

        
        if($this->input->get('no_claim') != ''){
            $param['no_claim'] = $this->input->get('no_claim');
        }

        $data = json_decode($this->curl->simple_get(API_URL . "/api/service/claim_promo", $param));

        // $this->output->set_output(json_encode($data));
        return $data;
    }

/*    public function createfile_csv()
    {
        $data=array();
        $data= $this->claim_cetak();
        $namafile="";
        $isifile="";

        if($this->input->get('jenis') != 'B')
        {            
            foreach($data->message as $key => $row){
                $namafile=  $row->KD_MAINDEALER."-".$row->KD_DEALER."-".date('ymdHis')."-".$row->NO_CLAIM."JENIS-A.CSV";
                
                $isifile .= $row->KD_MAINDEALER.";";
                $isifile .= $row->KD_DEALER.";";
                $isifile .= $row->NO_RANGKA.";";
                $isifile .= $row->NO_MESIN.";";
                $isifile .= $row->KD_TIPE.";";
                $isifile .= $row->DESKRIPSI_WARNA.";";
                $isifile .= $row->NAMA_SALESPROGRAM.";";
                $isifile .= $row->NO_FAKTUR_JUAL.";";
                $isifile .= tglfromSql($row->TGL_FAKTUR_JUAL).";";
                $isifile .= tglfromSql($row->TGL_BAST).";";
                $isifile .= $row->TIPE_PENJUALAN.";";
                $isifile .= $row->KD_FINCOY.";";
                $isifile .= $row->NO_PO_FINCOY.";";
                $isifile .= tglfromSql($row->TGL_PO_FINCOY)."\n";
            }
        }
        else{

            foreach($data->message as $key => $row){
                $namafile=  $row->KD_MAINDEALER."-".$row->KD_DEALER."-".date('ymdHis')."-".$row->NO_CLAIM."JENIS-B.CSV";
                $isifile .= $row->KD_DEALER.";";
                $isifile .= $row->NAMA_DEALER.";";
                $isifile .= $row->NO_FAKTUR_JUAL.";";
                $isifile .= tglfromSql($row->TGL_FAKTUR_JUAL).";";
                $isifile .= $row->NO_PO_FINCOY.";";
                $isifile .= tglfromSql($row->TGL_PO_FINCOY).";";
                $isifile .= $row->NO_RANGKA.";";
                $isifile .= $row->NO_MESIN.";";
                $isifile .= $row->KD_TIPE.";";
                $isifile .= $row->NAMA_TIPE.";";
                $isifile .= $row->DESKRIPSI_WARNA.";";
                $isifile .= tglfromSql($row->TGL_FAKTUR_STNK).";";
                $isifile .= $row->TIPE_PENJUALAN.";";
                $isifile .= $row->KD_FINCOY.";";
                $isifile .= $row->NAMA_FINCOY.";";
                $isifile .= tglfromSql($row->TGL_BAST).";";
                $isifile .= $row->NAMA_CUSTOMER.";";
                $isifile .= $row->ALAMAT.";";
                $isifile .= $row->NAMA_KOTA."\n";
                // $isifile .= $row->STATUS_SJ.";";
            }
        }
       
        $this->load->helper("download");
        force_download($namafile,$isifile);
        // var_dump($this->input->get());
    }*/



    public function createfile_csv()
    {

        $this->load->library('libexcel');

        $this->libexcel->setActiveSheetIndex(0);

        $data=array();
        $data= $this->claim_cetak();

        // var_dump($data);exit;
        $namafile="";
        $isifile="";

        if($this->input->get('jenis') != 'B')
        {           

            //name the worksheet
            $this->libexcel->getActiveSheet()->setTitle('Claim Promo Jenis A');
            $this->libexcel->getActiveSheet()->setCellValue('A1', 'Claim Promo Jenis A');
            $this->libexcel->getActiveSheet()->mergeCells('A1:N1');
            $this->libexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
            for($col = ord('A'); $col <= ord('N'); $col++){ 
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(true);
            }

            $this->libexcel->getActiveSheet()->setCellValue('A3', 'KD_MAINDEALER');
            $this->libexcel->getActiveSheet()->setCellValue('B3', 'KD_DEALER');
            $this->libexcel->getActiveSheet()->setCellValue('C3', 'NO_RANGKA');
            $this->libexcel->getActiveSheet()->setCellValue('D3', 'NO_MESIN');
            $this->libexcel->getActiveSheet()->setCellValue('E3', 'KD_TIPE');
            $this->libexcel->getActiveSheet()->setCellValue('F3', 'DESKRIPSI_WARNA');
            $this->libexcel->getActiveSheet()->setCellValue('G3', 'NAMA_SALESPROGRAM');
            $this->libexcel->getActiveSheet()->setCellValue('H3', 'NO_FAKTUR_JUAL');
            $this->libexcel->getActiveSheet()->setCellValue('I3', 'TGL_FAKTUR_JUAL');
            $this->libexcel->getActiveSheet()->setCellValue('J3', 'TGL_BAST');
            $this->libexcel->getActiveSheet()->setCellValue('K3', 'TIPE_PENJUALAN');
            $this->libexcel->getActiveSheet()->setCellValue('L3', 'KD_FINCOY');
            $this->libexcel->getActiveSheet()->setCellValue('M3', 'NO_PO_FINCOY');
            $this->libexcel->getActiveSheet()->setCellValue('N3', 'TGL_PO_FINCOY');


           for($col = ord('A'); $col <= ord('N'); $col++){
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(false);

            }
            $data= $this->claim_cetak();

            $no = 4;
            foreach ($data->message as $key => $row){
                $filename=  $row->KD_MAINDEALER."-".$row->KD_DEALERAHM."-".date('ymdHis')."-".$row->NO_CLAIM."JENIS-A.xls";

                $this->libexcel->getActiveSheet()->setCellValue('A'.$no, $row->KD_MAINDEALER);
                $this->libexcel->getActiveSheet()->setCellValue('B'.$no, $row->KD_DEALERAHM);
                $this->libexcel->getActiveSheet()->setCellValue('C'.$no, $row->NO_RANGKA);
                $this->libexcel->getActiveSheet()->setCellValue('D'.$no, $row->NO_MESIN);
                $this->libexcel->getActiveSheet()->setCellValue('E'.$no, $row->KD_TIPE);
                $this->libexcel->getActiveSheet()->setCellValue('F'.$no, $row->KD_WARNA);
                $this->libexcel->getActiveSheet()->setCellValue('G'.$no, $row->NAMA_SALESPROGRAM);
                $this->libexcel->getActiveSheet()->setCellValue('H'.$no, $row->NO_FAKTUR_JUAL);
                $this->libexcel->getActiveSheet()->setCellValue('I'.$no, tglfromSql($row->TGL_FAKTUR_JUAL));
                $this->libexcel->getActiveSheet()->setCellValue('J'.$no, tglfromSql($row->TGL_BAST));
                $this->libexcel->getActiveSheet()->setCellValue('K'.$no, $row->TIPE_PENJUALAN);
                $this->libexcel->getActiveSheet()->setCellValue('L'.$no, $row->KD_FINCOY);
                $this->libexcel->getActiveSheet()->setCellValue('M'.$no, $row->NO_PO_FINCOY);
                $this->libexcel->getActiveSheet()->setCellValue('N'.$no, tglfromSql($row->TGL_PO_FINCOY));

                $no++;
            }
        }
        else{
            //name the worksheet
            $this->libexcel->getActiveSheet()->setTitle('Claim Promo Jenis B');
            $this->libexcel->getActiveSheet()->setCellValue('A1', 'Claim Promo Jenis B');
            $this->libexcel->getActiveSheet()->mergeCells('A1:T1');
            $this->libexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
            for($col = ord('A'); $col <= ord('T'); $col++){ 
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(true);
            }

            $this->libexcel->getActiveSheet()->setCellValue('A3', 'KD_DEALER');
            $this->libexcel->getActiveSheet()->setCellValue('B3', 'NAMA_DEALER');
            $this->libexcel->getActiveSheet()->setCellValue('C3', 'NO_FAKTUR_JUAL');
            $this->libexcel->getActiveSheet()->setCellValue('D3', 'TGL_FAKTUR_JUAL');
            $this->libexcel->getActiveSheet()->setCellValue('E3', 'NO_PO_FINCOY');
            $this->libexcel->getActiveSheet()->setCellValue('F3', 'TGL_PO_FINCOY');
            $this->libexcel->getActiveSheet()->setCellValue('G3', 'NO_RANGKA');
            $this->libexcel->getActiveSheet()->setCellValue('H3', 'NO_MESIN');
            $this->libexcel->getActiveSheet()->setCellValue('I3', 'KD_TIPE');
            $this->libexcel->getActiveSheet()->setCellValue('J3', 'NAMA_TIPE');
            $this->libexcel->getActiveSheet()->setCellValue('K3', 'KD_WARNA');
            $this->libexcel->getActiveSheet()->setCellValue('L3', 'DESKRIPSI_WARNA');
            $this->libexcel->getActiveSheet()->setCellValue('M3', 'TGL_FAKTUR_STNK');
            $this->libexcel->getActiveSheet()->setCellValue('N3', 'TIPE_PENJUALAN');
            $this->libexcel->getActiveSheet()->setCellValue('O3', 'KD_FINCOY');
            $this->libexcel->getActiveSheet()->setCellValue('P3', 'NAMA_FINCOY');
            $this->libexcel->getActiveSheet()->setCellValue('Q3', 'TGL_BAST');
            $this->libexcel->getActiveSheet()->setCellValue('R3', 'NAMA_CUSTOMER');
            $this->libexcel->getActiveSheet()->setCellValue('S3', 'ALAMAT');
            $this->libexcel->getActiveSheet()->setCellValue('T3', 'NAMA_KOTA');


           for($col = ord('A'); $col <= ord('T'); $col++){
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(false);

            }
            $data= $this->claim_cetak();

            $no = 4;
            foreach ($data->message as $key => $row){
                $filename=  $row->KD_MAINDEALER."-".$row->KD_DEALERAHM."-".date('ymdHis')."-".$row->NO_CLAIM."JENIS-B.xls";

                $this->libexcel->getActiveSheet()->setCellValue('A'.$no, $row->KD_DEALERAHM);
                $this->libexcel->getActiveSheet()->setCellValue('B'.$no, $row->NAMA_DEALER);
                $this->libexcel->getActiveSheet()->setCellValue('C'.$no, $row->NO_FAKTUR_JUAL);
                $this->libexcel->getActiveSheet()->setCellValue('D'.$no, tglfromSql($row->TGL_FAKTUR_JUAL));
                $this->libexcel->getActiveSheet()->setCellValue('E'.$no, $row->NO_PO_FINCOY);
                $this->libexcel->getActiveSheet()->setCellValue('F'.$no, tglfromSql($row->TGL_PO_FINCOY));
                $this->libexcel->getActiveSheet()->setCellValue('G'.$no, $row->NO_RANGKA);
                $this->libexcel->getActiveSheet()->setCellValue('H'.$no, $row->NO_MESIN);
                $this->libexcel->getActiveSheet()->setCellValue('I'.$no, $row->KD_TIPE);
                $this->libexcel->getActiveSheet()->setCellValue('J'.$no, $row->NAMA_TIPE);
                $this->libexcel->getActiveSheet()->setCellValue('K'.$no, $row->KD_WARNA);
                $this->libexcel->getActiveSheet()->setCellValue('L'.$no, $row->DESKRIPSI_WARNA);
                $this->libexcel->getActiveSheet()->setCellValue('M'.$no, tglfromSql($row->TGL_FAKTUR_STNK));
                $this->libexcel->getActiveSheet()->setCellValue('N'.$no, $row->TIPE_PENJUALAN);
                $this->libexcel->getActiveSheet()->setCellValue('O'.$no, $row->KD_FINCOY);
                $this->libexcel->getActiveSheet()->setCellValue('P'.$no, $row->NAMA_FINCOY);
                $this->libexcel->getActiveSheet()->setCellValue('Q'.$no, tglfromSql($row->TGL_BAST));
                $this->libexcel->getActiveSheet()->setCellValue('R'.$no, $row->NAMA_CUSTOMER);
                $this->libexcel->getActiveSheet()->setCellValue('S'.$no, $row->ALAMAT);
                $this->libexcel->getActiveSheet()->setCellValue('T'.$no, $row->NAMA_KOTA);

                $no++;
            }

        }

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->libexcel, 'Excel5'); 
        $objWriter->save('php://output');
    }

    public function test_exel()
    {   
        $this->load->library('libexcel');

        $this->libexcel->setActiveSheetIndex(0);
        //name the worksheet
        $this->libexcel->getActiveSheet()->setTitle('Countries');
        //set cell A1 content with some text
        $this->libexcel->getActiveSheet()->setCellValue('A1', 'Claim Promo');


       for($col = ord('A'); $col <= ord('N'); $col++){ //set column dimension $this->libexcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                 //change the font size
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(true);
            // $this->libexcel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        }

        $this->libexcel->getActiveSheet()->setCellValue('A3', 'KD_MAINDEALER');
        $this->libexcel->getActiveSheet()->setCellValue('B3', 'KD_DEALER');
        $this->libexcel->getActiveSheet()->setCellValue('C3', 'NO_RANGKA');
        $this->libexcel->getActiveSheet()->setCellValue('D3', 'NO_MESIN');
        $this->libexcel->getActiveSheet()->setCellValue('E3', 'KD_TIPE');
        $this->libexcel->getActiveSheet()->setCellValue('F3', 'DESKRIPSI_WARNA');
        $this->libexcel->getActiveSheet()->setCellValue('G3', 'NAMA_SALESPROGRAM');
        $this->libexcel->getActiveSheet()->setCellValue('H3', 'NO_FAKTUR_JUAL');
        $this->libexcel->getActiveSheet()->setCellValue('I3', 'TGL_FAKTUR_JUAL');
        $this->libexcel->getActiveSheet()->setCellValue('J3', 'TGL_BAST');
        $this->libexcel->getActiveSheet()->setCellValue('K3', 'TIPE_PENJUALAN');
        $this->libexcel->getActiveSheet()->setCellValue('L3', 'KD_FINCOY');
        $this->libexcel->getActiveSheet()->setCellValue('M3', 'NO_PO_FINCOY');
        $this->libexcel->getActiveSheet()->setCellValue('N3', 'TGL_PO_FINCOY');

        //merge cell A1 until C1
        $this->libexcel->getActiveSheet()->mergeCells('A1:N1');

        //set aligment to center for that merged cell (A1 to C1)
        $this->libexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //make the font become bold
        $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        // $this->libexcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');

       for($col = ord('A'); $col <= ord('N'); $col++){ //set column dimension $this->libexcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                 //change the font size
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(false);
            // $this->libexcel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        }
        //retrive contries table data

        // $rs = $this->db->get('countries');

        $data= $this->claim_cetak();

        $exceldata="";

        $no = 4;
        foreach ($data->message as $key => $row){
            $this->libexcel->getActiveSheet()->setCellValue('A'.$no, $row->KD_MAINDEALER);
            $this->libexcel->getActiveSheet()->setCellValue('B'.$no, $row->KD_DEALER);
            $this->libexcel->getActiveSheet()->setCellValue('C'.$no, $row->NO_RANGKA);
            $this->libexcel->getActiveSheet()->setCellValue('D'.$no, $row->NO_MESIN);
            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, $row->KD_TIPE);
            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, $row->DESKRIPSI_WARNA);
            $this->libexcel->getActiveSheet()->setCellValue('G'.$no, $row->NAMA_SALESPROGRAM);
            $this->libexcel->getActiveSheet()->setCellValue('H'.$no, $row->NO_FAKTUR_JUAL);
            $this->libexcel->getActiveSheet()->setCellValue('I'.$no, tglfromSql($row->TGL_FAKTUR_JUAL));
            $this->libexcel->getActiveSheet()->setCellValue('J'.$no, tglfromSql($row->TGL_BAST));
            $this->libexcel->getActiveSheet()->setCellValue('K'.$no, $row->TIPE_PENJUALAN);
            $this->libexcel->getActiveSheet()->setCellValue('L'.$no, $row->KD_FINCOY);
            $this->libexcel->getActiveSheet()->setCellValue('M'.$no, $row->NO_PO_FINCOY);
            $this->libexcel->getActiveSheet()->setCellValue('N'.$no, tglfromSql($row->TGL_PO_FINCOY));

            $no++;
            // $exceldata[] = 'test'.$key;
        }
              //Fill data
        // $this->libexcel->getActiveSheet()->fromArray($exceldata, null, 'A4');
        // $this->libexcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $this->libexcel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $this->libexcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $filename='PHPExcelDemo.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)

        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->libexcel, 'Excel5'); 

        //force user to download the Excel file without writing it to server's HD

        $objWriter->save('php://output');
    }


    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo(){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => 'CL',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tahun_docno'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'Claim Promo',
            'reset_docno' => 1,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'limit'     => 1,
            'offset'    => 0
        );


        $bulan_kirim = substr($this->input->post('tahun_docno'), 3, 2);

        $nomor_po=$this->curl->simple_get(API_URL."/api/setup/docno",$param);

        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);

        if ($nomor_po == 0) {
            $nopo = "CL" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";

            $param['urutan_docno'] = $nomor_po+1;
            $this->curl->simple_post(API_URL."/api/setup/setup_docno",$param, array(CURLOPT_BUFFERSIZE => 10));

        } else {
            $nomorpo = $nomor_po+1;
            $nopo = "CLAIM" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);


            $param['urutan_docno'] = $nomor_po;
            $this->curl->simple_put(API_URL."/api/setup/docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        }
        //var_dump($nopo);exit();
        return $nopo;
    }


    function data_output($hasil = NULL, $method = '', $location = '', $no_claim='') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil disimpan",
                        'location' => $location,
                        'noclaim' => $no_claim
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
}