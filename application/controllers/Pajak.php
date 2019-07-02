<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pajak extends CI_Controller {
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

    // view ==========================================================================================================
    public function list_faktur_pajak()
    {

        $data = array();

        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'NO_PAJAK, TGL_PAJAK',
            'groupby' => TRUE
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/fakturpajak", $param));

        // $NO_PAJAK = (array)$data["list"]->message;
        // var_dump($NO_PAJAK);exit();

        $param_detail = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'jointable' =>array(
                array("TRANS_FAKTURPAJAK F" , "F.NO_PAJAK = TRANS_FAKTURPAJAK_DETAIL.NO_PAJAK AND F.ROW_STATUS>=0", "LEFT"),
            ),
            'field' => 'F.NAMA_CUSTOMER, F.ALAMAT_CUSTOMER, F.NPWP_CUSTOMER, TRANS_FAKTURPAJAK_DETAIL.*',
            'keyword' => $this->input->get('keyword'),
            // 'custom' => "TRANS_FAKTURPAJAK_DETAIL.NO_PAJAK IN ('000-10.10324242', '000-10.10324243')"
            // 'custom' => "TRANS_FAKTURPAJAK_DETAIL.NO_PAJAK IN (".$data["list"]->message.")"
        );

        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/fakturpajak_detail", $param_detail));

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
        $this->template->site('accounting/list_faktur', $data);
    }

    public function faktur_pajak_unit($dataonly = null)
    {
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

        $param= array(
            'kd_dealer' => $kd_dealer,
            'jointable' =>array(
                array("TRANS_SJKELUAR SJ" , "SJ.NO_REFF = TRANS_SPK.NO_SO AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN DS" , "DS.SPK_ID = TRANS_SPK.ID AND DS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW CV" , "CV.KD_CUSTOMER = TRANS_SPK.KD_CUSTOMER AND CV.ROW_STATUS>=0", "LEFT"),
            ),
            'field' => "TRANS_SPK.KD_CUSTOMER, CV.NAMA_CUSTOMER",
            // 'kd_customer' => $this->input->get('kd_customer'),
            'groupby' => true,
            'orderby' => "CV.NAMA_CUSTOMER ASC",
            'custom' => "TRANS_SPK.FAKTUR_PENJUALAN IS NOT NULL AND DS.NO_RANGKA NOT IN (SELECT FD.NO_RANGKA FROM TRANS_FAKTURPAJAK_DETAIL AS FD WHERE FD.ROW_STATUS >= 0 AND FD.NO_RANGKA IS NOT NULL)"
        );

        $data["pajak"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));


        $param_faktur = array(
            'kd_dealer' => $kd_dealer,
            'jointable' =>array(
                array("TRANS_SJKELUAR SJ" , "SJ.NO_REFF = TRANS_SPK.NO_SO AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN DS" , "DS.SPK_ID = TRANS_SPK.ID AND DS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW CV" , "CV.KD_CUSTOMER = TRANS_SPK.KD_CUSTOMER AND CV.ROW_STATUS>=0", "LEFT"),
            ),
            'kd_customer' => $this->input->get('kd_customer'),
            'field' => "TRANS_SPK.FAKTUR_PENJUALAN, CV.NO_NPWP, CV.ALAMAT_SURAT, CV.NAMA_KABUPATEN, CV.NAMA_CUSTOMER",
            'custom' => "TRANS_SPK.FAKTUR_PENJUALAN IS NOT NULL AND DS.NO_RANGKA NOT IN (SELECT FD.NO_RANGKA FROM TRANS_FAKTURPAJAK_DETAIL AS FD WHERE FD.ROW_STATUS >= 0 AND FD.NO_RANGKA IS NOT NULL)"

        );
        $data["faktur"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param_faktur));

        $data['option_pajak'] = '';

        if($data["faktur"] && $this->input->get('kd_customer') != '' && (is_array($data["faktur"]->message) || is_object($data["faktur"]->message))):
          foreach ($data["faktur"]->message as $key => $value) {

          $data['option_pajak'] .= '<option value="'.$value->FAKTUR_PENJUALAN.'">'.$value->FAKTUR_PENJUALAN.'</option>';
            
          }
        endif;


        if($dataonly=='true'){

            $this->output->set_output(json_encode($data));
        }
        else{
            $this->template->site('accounting/pajak_unit',$data);
        }
    }

    public function faktur_pajak_lainnya($dataonly = null)
    {
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

        $param= array(
            'field' => "MASTER_CUSTOMER_VIEW.KD_CUSTOMER, MASTER_CUSTOMER_VIEW.NAMA_CUSTOMER, MASTER_CUSTOMER_VIEW.ALAMAT_SURAT, MASTER_CUSTOMER_VIEW.NAMA_KABUPATEN",
            'orderby' => "MASTER_CUSTOMER_VIEW.NAMA_CUSTOMER ASC",
            'groupby' => true
        );

        $data["pajak"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customerview", $param));

        $param= array(
            'kd_customer' => $this->input->get('kd_customer'),
            'field' => "MASTER_CUSTOMER_VIEW.KD_CUSTOMER, MASTER_CUSTOMER_VIEW.NAMA_CUSTOMER, MASTER_CUSTOMER_VIEW.ALAMAT_SURAT, MASTER_CUSTOMER_VIEW.NAMA_KABUPATEN, MASTER_CUSTOMER_VIEW.NO_NPWP",
            'groupby' => true
        );

        $data["faktur"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/customerview", $param));

        if($dataonly=='true'){

            $this->output->set_output(json_encode($data));
        }
        else{
            $this->template->site('accounting/pajak_lainnya',$data);
        }
    }


    // json data ======================================================================================================
    public function faktur_unit()
    {

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

        $param = array(
            'kd_dealer' => $kd_dealer,
            'jointable' =>array(
                array("TRANS_SJKELUAR SJ" , "SJ.NO_REFF = TRANS_SPK.NO_SO AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN DS" , "DS.SPK_ID = TRANS_SPK.ID AND DS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR PM" , "PM.KD_TYPEMOTOR = DS.KD_TYPEMOTOR AND PM.KD_WARNA = DS.KD_WARNA AND PM.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_SALESPROGRAM SS" , "SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS>=0", "LEFT"),
            ),
            'field' => 'TRANS_SPK.FAKTUR_PENJUALAN, TRANS_SPK.TGL_SO, SJ.NO_SURATJALAN, DS.NO_MESIN, DS.NO_RANGKA, DS.HARGA, PM.KET_WARNA, PM.NAMA_PASAR, PM.KD_ITEM, DS.DISKON, DS.BBN,
                "CASE WHEN (TRANS_SPK.TYPE_PENJUALAN=\'CASH\' OR TRANS_SPK.TYPE_PENJUALAN=\'Tunai\') THEN SS.SC_AHM ELSE SS.SK_AHM END PotAHM",
                "CASE WHEN (TRANS_SPK.TYPE_PENJUALAN=\'CASH\' OR TRANS_SPK.TYPE_PENJUALAN=\'Tunai\') THEN SS.SC_MD ELSE SS.SK_MD END PotMD",
                "CASE WHEN (TRANS_SPK.TYPE_PENJUALAN=\'CASH\' OR TRANS_SPK.TYPE_PENJUALAN=\'Tunai\') THEN SS.SC_SD ELSE SS.SK_SD END PotDLR"',
            'custom' => "TRANS_SPK.FAKTUR_PENJUALAN = '".$this->input->get('kd_itemfaktur')."' AND DS.NO_RANGKA NOT IN (SELECT FD.NO_RANGKA FROM TRANS_FAKTURPAJAK_DETAIL AS FD WHERE FD.ROW_STATUS >= 0 AND FD.NO_RANGKA IS NOT NULL)"

        );
        $faktur_unit = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));


        if($faktur_unit && (is_array($faktur_unit->message) || is_object($faktur_unit->message))):

            $data['faktur_unit'] = $this->get_faktur_unit_table($faktur_unit->message);
            $data['status'] = true;

        else:
            $data['faktur_unit'] = "<i class='fa fa-info-circle'></i> Belum ada data / data tidak ditemukan</b>";
            $data['status'] = false;

        endif;

        $this->output->set_output(json_encode($data));

    }

    public function get_seripajak($range=NULL)
    {
        // $data["noseri"] = array('message' => [['NOMOR'=>'']]); '000-01.1'


        $param=array(
          "kd_dealer" =>  $this->session->userdata("kd_dealer"),
          "field" => "LEFT(RANGE1, 7) NOMOR"
        );
        $data["noseri"] = json_decode($this->curl->simple_get(API_URL."/api/accounting/range_nopajak",$param));

        if($range == 'seri'){
            $param = array(
                'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
                'range_seri' => $this->input->get('range_seri')
            );

            $data["noseri"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/range_seripajak", $param));
        }

        $this->output->set_output(json_encode($data));
    }

    public function get_seripajak_()
    {
        $data1 = array();
        $data2 = array();

        $param=array(
          "kd_dealer" =>  $this->session->userdata("kd_dealer")
        );
        $range = json_decode($this->curl->simple_get(API_URL."/api/accounting/range_nopajak",$param));

        if($range && (is_array($range->message) || is_object($range->message))):
            foreach ($range->message as $key => $range_value):
                // $data1 = $range_value->RANGE1;
                for ($i=$range_value->RANGE1; $i < $range_value->RANGE2; $i++) { 
                    array_push($data1,$i);
                }
            endforeach;
        endif;


        $param_hasil=array(
          "kd_dealer" =>  $this->session->userdata("kd_dealer")
        );
        $hasil = json_decode($this->curl->simple_get(API_URL."/api/accounting/fakturpajak",$param_hasil));

        if($hasil && (is_array($hasil->message) || is_object($hasil->message))):
            foreach ($hasil->message as $key => $hasil_value):
                array_push($data2,$hasil_value->NO_PAJAK);
            endforeach;
        endif;

        $result = array_diff($data1, $data2);

        $this->output->set_output(json_encode($result));
    }

    // data table =====================================================================================================
    public function get_faktur_unit_table($list)
    {
        $html = '';

        foreach ($list as $key => $list_unit) {

            $total=$list_unit->HARGA-$list_unit->PotAHM-$list_unit->PotMD-$list_unit->PotDLR;
            $dpp = ($total - $list_unit->BBN) * (100/110);
            $ppn=$dpp*0.1;

            $html .= '<tr>';
            $html .= '<input class="no_faktur" type="hidden" value="'.$list_unit->FAKTUR_PENJUALAN.'">';
            $html .= '<input class="tgl_faktur" type="hidden" value="'.$list_unit->TGL_SO.'">';
            $html .= '<input class="kd_itemfaktur" type="hidden" value="'.$list_unit->KD_ITEM.'">';
            $html .= '<input class="disc_item" type="hidden" value="'.$list_unit->DISKON.'">';
            $html .= '<input class="dpp_faktur" type="hidden" value="'.$dpp.'">';
            $html .= '<input class="ppn_faktur" type="hidden" value="'.$ppn.'">';
            $html .= '<input class="biaya_stnk" type="hidden" value="'.$list_unit->BBN.'">';

            $html .= '<td class="no_sj">'.$list_unit->NO_SURATJALAN.'</td>';
            $html .= '<td class="nama_itemfaktur">'.$list_unit->NAMA_PASAR.'-'.$list_unit->KET_WARNA.'</td>';
            $html .= '<td class="qty_item">1</td>';
            $html .= '<td class="no_mesin">'.$list_unit->NO_MESIN.'</td>';
            $html .= '<td class="no_rangka">'.$list_unit->NO_RANGKA.'</td>';
            // $html .= '<td class="harga_item" style="text-align: right;">'.$list_unit->HARGA.'</td>';
            $html .= '<td class="harga_item" style="text-align: right;">'.number_format($list_unit->HARGA,0).'</td>';
            $html .= '</tr>';

        }

        return $html;
    }



    /**
     * [podetail_list description]
     * @return [type] [description]
     */
    public function pajak_data(){


        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $params= array(
            'kd_dealer' => $kd_dealer,
            'jointable' => array(
                array("TRANS_FAKTURPAJAK_DETAIL TPD" , "TPD.NO_TRANS=TRANS_FAKTURPAJAK.NO_TRANS", "LEFT"),
                array("MASTER_CUSTOMER_VIEW MC" , "MC.KD_CUSTOMER=TRANS_FAKTURPAJAK.KD_CUSTOMER", "LEFT")
            ),

            'field' => "TRANS_FAKTURPAJAK.KD_MAINDEALER ,TRANS_FAKTURPAJAK.KD_DEALER, TRANS_FAKTURPAJAK.TGL_PAJAK, YEAR(TRANS_FAKTURPAJAK.TGL_PAJAK) TAHUN_PAJAK , TRANS_FAKTURPAJAK.NAMA_CUSTOMER , MC.NO_KTP, MC.ALAMAT_LENGKAP, TRANS_FAKTURPAJAK.ALAMAT_CUSTOMER ,TRANS_FAKTURPAJAK.NPWP_CUSTOMER, TPD.*"
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/accounting/fakturpajak", $params));

        // $this->output->set_output(json_encode($data));       
        return $data;
    }
    /**
     * [createfile_udpo description]
     * @return [type] [description]
     */
    public function createfile_pajak(){
        $data=array();
        $data= $this->pajak_data();
        $namafile="";
        $isifile="";
        foreach($data->message as $key => $row){
            $namafile=  $row->KD_MAINDEALER."-".$row->KD_DEALER."-".date('ymdHis')."-".$row->NO_TRANS.".CSV";

            $isifile .= "FK;";
            $isifile .= "K014;";
            $isifile .= "0;";
            $isifile .= $row->NO_PAJAK.";";
            $isifile .= ";";
            $isifile .= $row->TAHUN_PAJAK.";";
            $isifile .= tglFromSql($row->TGL_PAJAK).";";
            $isifile .= $row->NPWP_CUSTOMER.";";
            $isifile .= $row->NO_KTP."#NIK#NAMA#".$row->NAMA_CUSTOMER.";";
            $isifile .= $row->ALAMAT_LENGKAP.";";
            $isifile .= $row->DPP_FAKTUR.";";
            $isifile .= $row->PPN_FAKTUR.";";
            $isifile .= "0;";
            $isifile .= ";";
            $isifile .= "0;";
            $isifile .= "0;";
            $isifile .= "0;";
            $isifile .= "0;";
            $isifile .= $row->NO_FAKTUR.PHP_EOL;

            $isifile .= "OF;";
            $isifile .= $row->KD_ITEMFAKTUR.";";
            $isifile .= $row->NAMA_ITEMFAKTUR.";";
            $isifile .= $row->HARGA_ITEM.";";
            $isifile .= $row->QTY_ITEM.";";
            $isifile .= $row->HARGA_ITEM.";";
            $isifile .= $row->DISC_ITEM.";";
            $isifile .= $row->DPP_FAKTUR.";";
            $isifile .= $row->PPN_FAKTUR.";";
            $isifile .= "0;";
            $isifile .= "0;";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= PHP_EOL;
        }
       
        $this->load->helper("download");
        force_download($namafile,$isifile);
    }



    // store data =====================================================================================================
    public function store_pajakunit()
    {        
        $no_trans = $this->getnopo('PJK');

        $param = array(
            'no_trans' => $no_trans,
            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'no_pajak' => $this->input->post("no_pajak"),
            'tgl_trans' => $this->input->post("tgl_trans"),
            'tgl_pajak' => $this->input->post("tgl_pajak"),
            'tgl_suratjalan' => $this->input->post("tgl_suratjalan"),
            'kd_customer' => $this->input->post("kd_customer"),
            'nama_customer' => $this->input->post("nama_customer"),
            'alamat_customer' => $this->input->post("alamat_customer"),
            'npwp_customer' => $this->input->post("npwp_customer"),
            'status_faktur' => $this->input->post("status_faktur"),
            'jenis_faktur' => $this->input->post("jenis_faktur"),
            'created_by' => $this->session->userdata('user_id')
        );

        $hasil=$this->curl->simple_post(API_URL."/api/accounting/fakturpajak",$param, array(CURLOPT_BUFFERSIZE => 10));  

        if(json_decode($hasil))
        {
            if(json_decode($hasil)->message>0){
                
                $this->store_detail($no_trans);

            }
        }  
    
        $this->data_output($hasil, 'post');
    }

    public function store_detail($no_trans)
    {

        $detail = $this->input->post("detail");

        for ($i=0; $i < count($detail); $i++) { 

            $param = array(
                'no_trans' => $no_trans,
                'no_pajak' => $this->input->post("no_pajak"),
                'kd_itemfaktur' => $detail[$i]['kd_itemfaktur'],
                'nama_itemfaktur' => $detail[$i]['nama_itemfaktur'],
                'no_faktur' => $detail[$i]['no_faktur']?$detail[$i]['no_faktur']:$this->getnopo('FAK'),
                'tgl_faktur' => onlyDate($detail[$i]['tgl_faktur']),
                'qty_item' => $detail[$i]['qty_item'],
                'harga_item' => str_replace(",", "", $detail[$i]['harga_item']),
                'disc_item' => $detail[$i]['disc_item'],
                'dpp_faktur' => $detail[$i]['dpp_faktur'],
                'ppn_faktur' => $detail[$i]['ppn_faktur'],
                'biaya_stnk' => $detail[$i]['biaya_stnk'],
                'no_rangka' => $detail[$i]['no_rangka'],
                'no_mesin' => $detail[$i]['no_mesin'],
                'created_by' => $this->session->userdata("user_id")
            );
    
            $hasil = $this->curl->simple_post(API_URL . "/api/accounting/fakturpajak_detail", $param, array(CURLOPT_BUFFERSIZE => 10));

            // var_dump($hasil);exit();
        }
    }

    public function delete_faktur($id)
    {

        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();

        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/accounting/fakturpajak_detail",$param));

        $this->data_output($data, 'delete');
    }


    // generate code number
    // ================================================================================================================

    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo($kd_docno){

        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'TERIMA BANK',
            'reset_docno' => 1,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'limit'     => 1,
            'offset'    => 0
        );

        $bulan_kirim = date('m');

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