<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_insentif extends CI_Controller {

    var $API;

    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('dompdf_gen');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper("zetro_helper");
    }

    public function pagination($config){
        $config['per_page']             = $config['per_page'];
        $config['base_url']             = $config['base_url'];
        $config['total_rows']           = $config['total_rows'];
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open']        = "<ul class='pagination pagination-sm m-t-none m-b-none'>";
        $config['full_tag_close']       = "</ul>";
        $config['num_tag_open']         = '<li>';
        $config['num_tag_close']        = '</li>';
        $config['cur_tag_open']         = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close']        = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open']        = "<li>";
        $config['next_tagl_close']      = "</li>";
        $config['prev_tag_open']        = "<li>";
        $config['prev_tagl_close']      = "</li>";
        $config['first_tag_open']       = "<li>";
        $config['first_tagl_close']     = "</li>";
        $config['last_tag_open']        = "<li>";
        $config['last_tagl_close']      = "</li>";
        return $config;
    }

    public function data_output($hasil = NULL, $method = '', $location='') {
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
            default:
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Update berhasil",
                        'location' => $location
                    );
                    $this->output->set_output(json_encode($result));
                } else {
                    $result = array(
                        'message' => 'Data gagal di simpan',
                        'status' => false
                    );
                    $this->output->set_output(json_encode($result));
                }
                break;
        }
    }

    public function insentif_salesman() {

        $data = array();    
        $start_date =$this->input->get('tgl_awal');
        $end_date =$this->input->get('tgl_akhir');
        $custom = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4";
        }else{
             $customs = "STATUS_SPK = 4";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");        
        
        $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  =>  $this->input->get('kd_salesman'),            
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        $jabatan = $data['sales']->message[0]->KD_JABATAN;
       /* print_r($data["sales"]);die();*/
        switch ($jabatan) {
            case 'PL':
            case 'S. Re':
            case 'SW':
                $jab ="AND MI.KATEGORI = 'Reguler'";
                $jab2 ="AND MI2.KATEGORI = 'Reguler'";
                break;
            case 'S.Win':
                $jab ="AND MI.KATEGORI = 'WING'";
                $jab2 ="AND MI2.KATEGORI = 'WING'";
                break;
            case 'SWAT':
                $jab ="AND MI.KATEGORI = 'SWAT'";
                $jab2 ="AND MI2.KATEGORI = 'SWAT'";
                break;            
            default:
                $jab ="AND MI.KATEGORI = ''";
                $jab2 ="AND MI2.KATEGORI = ''";
                break;
        }
        if ($this->input->get('kd_salesman')) {
                $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SJMASUK TSM","TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI2","MI2.KD_MOTOR=MG.KD_TYPEMOTOR ".$jab2." AND  MI2.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR ".$jab." AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MS.PERSONAL_JABATAN, MK.KD_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET, SK.JATUH_TEMPO,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C, 
                            TSM.TGL_SJMASUK,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, MI2.CASH CASHH, MI2.KREDIT KREDITT, MI2.KHUSUS KHUSUSS,CASE
                
                            WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 

                            WHEN MK.KD_JABATAN ='S. Re' OR MK.KD_JABATAN ='PL' AND TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN MK.KD_JABATAN ='SW' AND TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT           
                            WHEN MK.KD_JABATAN ='' OR MK.KD_JABATAN = NULL AND TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN MK.KD_JABATAN ='SWAT' OR MK.KD_JABATAN ='S.Win' AND TYPE_PENJUALAN ='CREDIT'  THEN MI2.KREDIT

                            WHEN MK.KD_JABATAN ='S. Re' OR MK.KD_JABATAN ='PL' AND TYPE_PENJUALAN ='CASH'  THEN MI.CASH
                            WHEN  MK.KD_JABATAN ='SW' AND TYPE_PENJUALAN ='CASH'  THEN MI.CASH      
                            WHEN MK.KD_JABATAN ='' OR MK.KD_JABATAN = NULL AND TYPE_PENJUALAN ='CASH'  THEN MI.CASH
                            WHEN MK.KD_JABATAN ='SWAT' OR MK.KD_JABATAN ='S.Win' AND TYPE_PENJUALAN ='CASH'  THEN MI2.CASH             
                           
                            END AS INSENTIF";
            
                        $params = array(
                            'keyword'   => $this->input->get('keyword'),
                            'custom'    => $customs,            
                            'kd_dealer' => $kd_dealer,
                            'kd_sales'  => $this->input->get('kd_salesman'),            
                            'jointable' => $jointable,
                            'field'     => $field,
                            'orderby'   => "TGL_SPK"
                        );
                         $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
        }

       
        
      //print_r($data["list"]);die();
        $this->template->site('report/insentif_salesman/view', $data);
        
    }   
  

    public function insentif_salesman_print() {

        $data = array();    
        $start_date =$this->input->get('tgl_awal');
        $end_date =$this->input->get('tgl_akhir');
        $custom = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4";
        }else{
             $customs = "STATUS_SPK = 4";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");        
        
        $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  =>  $this->input->get('kd_salesman'),            
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        $jabatan = $data['sales']->message[0]->KD_JABATAN;
       /* print_r($data["sales"]);die();*/
        switch ($jabatan) {
            case 'PL':
            case 'S. Re':
            case 'SW':
                $jab ="AND MI.KATEGORI = 'Reguler'";
                $jab2 ="AND MI2.KATEGORI = 'Reguler'";
                break;
            case 'S.Win':
                $jab ="AND MI.KATEGORI = 'WING'";
                $jab2 ="AND MI2.KATEGORI = 'WING'";
                break;
            case 'SWAT':
                $jab ="AND MI.KATEGORI = 'SWAT'";
                $jab2 ="AND MI2.KATEGORI = 'SWAT'";
                break;            
            default:
                $jab ="AND MI.KATEGORI = ''";
                $jab2 ="AND MI2.KATEGORI = ''";
                break;
        }
        if ($this->input->get('kd_salesman')) {
                $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SJMASUK TSM","TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0","LEFT"),

                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI2","MI2.KD_MOTOR=MG.KD_TYPEMOTOR ".$jab2." AND  MI2.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR ".$jab." AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MS.PERSONAL_JABATAN, MK.KD_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET, 
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C,
                            TSM.TGL_SJMASUK,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, MI2.CASH CASHH, MI2.KREDIT KREDITT, MI2.KHUSUS KHUSUSS,CASE
                
                            WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 

                            WHEN MK.KD_JABATAN ='S. Re' OR MK.KD_JABATAN ='PL' AND TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN MK.KD_JABATAN ='SW' AND TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT           
                            WHEN MK.KD_JABATAN ='' OR MK.KD_JABATAN = NULL AND TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN MK.KD_JABATAN ='SWAT' OR MK.KD_JABATAN ='S.Win' AND TYPE_PENJUALAN ='CREDIT'  THEN MI2.KREDIT

                            WHEN MK.KD_JABATAN ='S. Re' OR MK.KD_JABATAN ='PL' AND TYPE_PENJUALAN ='CASH'  THEN MI.CASH
                            WHEN  MK.KD_JABATAN ='SW' AND TYPE_PENJUALAN ='CASH'  THEN MI.CASH      
                            WHEN MK.KD_JABATAN ='' OR MK.KD_JABATAN = NULL AND TYPE_PENJUALAN ='CASH'  THEN MI.CASH
                            WHEN MK.KD_JABATAN ='SWAT' OR MK.KD_JABATAN ='S.Win' AND TYPE_PENJUALAN ='CASH'  THEN MI2.CASH             
                           
                            END AS INSENTIF";
            }

        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  => $this->input->get('kd_salesman'),            
            'jointable' => $jointable,
            'field'     => $field,
            'orderby'   => "TGL_SPK"
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
      
        $this->load->view('report/insentif_salesman/print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }   

      public function insentif_k_salesman() {

        $data = array();    
        $start_date =$this->input->get('tgl_awal');
        $end_date =$this->input->get('tgl_akhir');
        $custom = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4 AND (MK1.PERSONAL_JABATAN = 'Koordinator Sales' OR MK1.PERSONAL_JABATAN = 'Kepala Sales') ";
        }else{
             $customs = "STATUS_SPK = 4 AND (MK1.PERSONAL_JABATAN = 'Koordinator Sales' OR MK1.PERSONAL_JABATAN = 'Kepala Sales')";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");        
        $nik_salesman = $this->input->get('nik_salesman');
        $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'custom'    => "MK.NIK ='".$nik_salesman."'",
            'kd_sales'  =>  $this->input->get('kd_ksalesman'),            
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        
       /* print_r($data["sales"]);die();*/
        
                $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK1","MK.ATASAN_LANGSUNG=MK1.NIK  AND MK1.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SJMASUK TSM","TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),                        
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Sales' AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MK.KD_JABATAN, MK.NAMA, MK.PERSONAL_JABATAN, MK.ATASAN_LANGSUNG,MK1.NIK,MK1.NAMA NAMA_ATASAN,MK1.PERSONAL_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET, SK.JATUH_TEMPO,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C, 
                            TSM.TGL_SJMASUK,MG.CATEGORY_MOTOR,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, CASE                 
                            WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 
                            WHEN  TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN  TYPE_PENJUALAN ='CASH'  THEN MI.CASH                           
                            END AS INSENTIF,CASE WHEN (DATEDIFF(DAY,convert(date, SK.JATUH_TEMPO) , convert(date, TRANS_SPK.TGL_SPK) )) > 10 THEN 10000 END AS PENALTY_AR";
           

        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,
                    
            'jointable' => $jointable,
            'field'     => $field,
            'orderby'   => "TGL_SPK"
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
        
      //print_r($paramm);die();
      //print_r($data["sales"]);die();
        $this->template->site('report/insentif_salesman/view_k', $data);
        
    }   

      public function insentif_k_salesman_print() {

        $data = array();    
        $start_date =$this->input->get('tgl_awal');
        $end_date =$this->input->get('tgl_akhir');
        $custom = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4 AND (MK1.PERSONAL_JABATAN = 'Koordinator Sales' OR MK1.PERSONAL_JABATAN = 'Kepala Sales') ";
        }else{
             $customs = "STATUS_SPK = 4 AND (MK1.PERSONAL_JABATAN = 'Koordinator Sales' OR MK1.PERSONAL_JABATAN = 'Kepala Sales')";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");        
        $nik_salesman = $this->input->get('nik_salesman');
        $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'custom'    => "MK.NIK ='".$nik_salesman."'",
            'kd_sales'  =>  $this->input->get('kd_ksalesman'),            
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        
        
        
                $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK1","MK.ATASAN_LANGSUNG=MK1.NIK  AND MK1.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SJMASUK TSM","TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),                        
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Sales' AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MK.KD_JABATAN, MK.NAMA, MK.PERSONAL_JABATAN, MK.ATASAN_LANGSUNG,MK1.NIK,MK1.NAMA NAMA_ATASAN,MK1.PERSONAL_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET, SK.JATUH_TEMPO,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C, 
                            TSM.TGL_SJMASUK,MG.CATEGORY_MOTOR,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, CASE                 
                            WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 
                            WHEN  TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN  TYPE_PENJUALAN ='CASH'  THEN MI.CASH                           
                            END AS INSENTIF";
           

        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,
                    
            'jointable' => $jointable,
            'field'     => $field,
            'orderby'   => "TGL_SPK"
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
        
     
        $this->load->view('report/insentif_salesman/print_k', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }   


    public function rekap_insentif() {

        $data = array();     
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');       

        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."' AND" :  "";         
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param = array(
            'kd_dealer'  => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")           
                       
           
        );      
        
        $paramm = array(              
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $params = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom."(MK.PERSONAL_JABATAN ='Sales' OR MK.PERSONAL_JABATAN ='Salesman' OR MK.PERSONAL_JABATAN ='Salesgirl')",              
                        
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(
                                array("MASTER_TARGETSF MT","MT.KD_SALES=TRANS_REKAP_V.KD_SALES AND MT.START_DATE >='".tglToSql($start_date)."' AND MT.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_V.NIK AND MK.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK1","MK1.NIK=MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0","LEFT")
                             ),
            'field'        => "TRANS_REKAP_V.KD_SALES, TRANS_REKAP_V.KD_JABATAN,TRANS_REKAP_V.PERSONAL_JABATAN,TRANS_REKAP_V.NAMA_SALES, MT.TARGET, COUNT(*) AS JUAL, MK1.NAMA as NAMA_ATASAN,SUM(CONVERT(INT,TRANS_REKAP_V.INSENTIF)) AS INS_DASAR",  

            'groupby_text' => "TRANS_REKAP_V.KD_SALES,TRANS_REKAP_V.KD_JABATAN,TRANS_REKAP_V.PERSONAL_JABATAN, TRANS_REKAP_V.NAMA_SALES, MT.TARGET, MK.ATASAN_LANGSUNG, MK1.NAMA",            
        );   

        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);
       
        $data["list_k_sales"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_sales", $param));
         
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));        
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif", $params));
        
       
        $this->template->site('report/insentif_salesman/rekap', $data);
        
    }


    public function rekap_insentif_print() {

      $data = array();     
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');       

        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."' AND" :  "";         
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param = array(
            'kd_dealer'  => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")           
                       
           
        );      
        
        $paramm = array(              
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $params = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom."(MK.PERSONAL_JABATAN ='Sales' OR MK.PERSONAL_JABATAN ='Salesman' OR MK.PERSONAL_JABATAN ='Salesgirl')",              
                        
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(
                                array("MASTER_TARGETSF MT","MT.KD_SALES=TRANS_REKAP_V.KD_SALES AND MT.START_DATE >='".tglToSql($start_date)."' AND MT.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_V.NIK AND MK.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK1","MK1.NIK=MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0","LEFT")
                             ),
            'field'        => "TRANS_REKAP_V.KD_SALES, TRANS_REKAP_V.KD_JABATAN,TRANS_REKAP_V.PERSONAL_JABATAN,TRANS_REKAP_V.NAMA_SALES, MT.TARGET, COUNT(*) AS JUAL, MK1.NAMA as NAMA_ATASAN,SUM(CONVERT(INT,TRANS_REKAP_V.INSENTIF)) AS INS_DASAR",  

            'groupby_text' => "TRANS_REKAP_V.KD_SALES,TRANS_REKAP_V.KD_JABATAN,TRANS_REKAP_V.PERSONAL_JABATAN, TRANS_REKAP_V.NAMA_SALES, MT.TARGET, MK.ATASAN_LANGSUNG, MK1.NAMA",            
        );   

        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);
       
        $data["list_k_sales"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_sales", $param));
         
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));        
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif", $params));
        
       
        $this->load->view('report/insentif_salesman/rekap_print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }    

    public function rekap_penalty() {

       $data = array();   
       $param['start_date'] = tglToSql($this->input->get('tgl_awal'));
       $param['end_date'] = tglToSql($this->input->get('tgl_akhir'));   
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
       $nik_salesman = $this->input->get('nik_salesman');   
        $paramm = array(              
            'field'        => "*",
            'custom'        => "NIK ='".$nik_salesman."'",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_sales", $param));      
      
        //print_r($data["sales"]);die();
        $this->template->site('report/insentif_salesman/penalty', $data);
        
    }

    public function rekap_penalty_print() {

       $data = array();   
       $param['start_date'] = tglToSql($this->input->get('tgl_awal'));
       $param['end_date']   = tglToSql($this->input->get('tgl_akhir'));   
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");    
       $nik_salesman = $this->input->get('nik_salesman');   
        $paramm = array(              
            'field'        => "*",
            'custom'        => "NIK ='".$nik_salesman."'",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"]  = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"]   = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_sales", $param));      
      
        //print_r($data["list"]);die();
        $this->load->view('report/insentif_salesman/penalty_print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }

    public function rekap_penalty_counter() {

       $data = array();   
       $param['start_date'] = tglToSql($this->input->get('tgl_awal'));
       $param['end_date'] = tglToSql($this->input->get('tgl_akhir'));   
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");    
        $nik_salesman = $this->input->get('nik_salesman');   
        $paramm = array(              
            'field'        => "*",
            'custom'        => "NIK ='".$nik_salesman."'",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_counter", $param));      
      
        //print_r($data["list"]);die();
        $this->template->site('report/insentif_salescounter/penalty', $data);
        
    }

    public function rekap_penalty_counter_print() {

       $data = array();   
       $param['start_date'] = tglToSql($this->input->get('tgl_awal'));
       $param['end_date'] = tglToSql($this->input->get('tgl_akhir'));   
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");    
        $nik_salesman = $this->input->get('nik_salesman');   
        $paramm = array(              
            'field'        => "*",
            'custom'        => "NIK ='".$nik_salesman."'",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_counter", $param));      
      
        $this->load->view('report/insentif_salescounter/penalty_print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }

     public function list_exclude() {
       
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/exc_penalty"));
      
        $this->template->site('report/insentif_salesman/view_exclude', $data);

        
    }
    public function exclude() {      
      
        $this->load->view('report/insentif_salesman/exclude');

        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

     public function exclude_simpan() {
     
        
            $param = array(
                'nik' => $this->input->post("nik"),
                'no_mesin'      => $this->input->post("no_mesin"),
                'kd_dealer'     => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'tgl'           => $this->input->post("tgl_awal"),              
            );
            $hasil = $this->curl->simple_post(API_URL ."/api/laporan/exc_penalty", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
          
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('laporan_insentif/rekap_insentif'));

        
    }

    public function rekap_insentif_counter() {

        $data = array();
     
        $start_date     = $this->input->get('tgl_awal');
        $end_date       = $this->input->get('tgl_akhir');
        $custom         = ($this->input->get("tgl_awal"))?  "TRANS_REKAP_INSCOUNTER_V.TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TRANS_REKAP_INSCOUNTER_V.TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."' AND " :  "";             
        $tgl_pengajuan  = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");            
                          
          
        
         $paramm = array(            
            
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $params = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom."MK.PERSONAL_JABATAN ='Sales Counter' OR MK.PERSONAL_JABATAN ='Counter Sales'",              
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(
                            array("MASTER_TARGETSF MT","MT.KD_SALES=TRANS_REKAP_INSCOUNTER_V.KD_SALES AND MT.START_DATE >='".tglToSql($start_date)."' AND MT.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_INSCOUNTER_V.NIK AND MK.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK1","MK1.NIK=MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0","LEFT")
                            ),
            'field'        => "TRANS_REKAP_INSCOUNTER_V.KD_SALES, TRANS_REKAP_INSCOUNTER_V.KD_JABATAN,TRANS_REKAP_INSCOUNTER_V.NAMA_SALES, MT.TARGET, MK.ATASAN_LANGSUNG, MK1.NAMA NAMA_ATASAN, COUNT(*) AS JUAL,SUM(CONVERT(INT,TRANS_REKAP_INSCOUNTER_V.INSENTIF)) AS INS_DASAR",
            
            'groupby_text' => "TRANS_REKAP_INSCOUNTER_V.KD_SALES, TRANS_REKAP_INSCOUNTER_V.KD_JABATAN,TRANS_REKAP_INSCOUNTER_V.NAMA_SALES, MT.TARGET,MK.ATASAN_LANGSUNG, MK1.NAMA"
        );   

        

        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));        
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif_counter", $params));
        $data["list_k_counter"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_counter", $param));
       
      
    
   
        $this->template->site('report/insentif_salescounter/rekap', $data);
        
    }   

    public function rekap_insentif_counter_print() {

       $data = array();
     
        $start_date     = $this->input->get('tgl_awal');
        $end_date       = $this->input->get('tgl_akhir');
        $custom         = ($this->input->get("tgl_awal"))?  "TRANS_REKAP_INSCOUNTER_V.TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TRANS_REKAP_INSCOUNTER_V.TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."' AND " :  "";             
        $tgl_pengajuan  = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");            
                          
          
        
         $paramm = array(            
            
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $params = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom."MK.PERSONAL_JABATAN ='Sales Counter' OR MK.PERSONAL_JABATAN ='Counter Sales'",              
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(
                            array("MASTER_TARGETSF MT","MT.KD_SALES=TRANS_REKAP_INSCOUNTER_V.KD_SALES AND MT.START_DATE >='".tglToSql($start_date)."' AND MT.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_INSCOUNTER_V.NIK AND MK.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK1","MK1.NIK=MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0","LEFT")
                            ),
            'field'        => "TRANS_REKAP_INSCOUNTER_V.KD_SALES, TRANS_REKAP_INSCOUNTER_V.KD_JABATAN,TRANS_REKAP_INSCOUNTER_V.NAMA_SALES, MT.TARGET, MK.ATASAN_LANGSUNG, MK1.NAMA NAMA_ATASAN, COUNT(*) AS JUAL,SUM(CONVERT(INT,TRANS_REKAP_INSCOUNTER_V.INSENTIF)) AS INS_DASAR",
            
            'groupby_text' => "TRANS_REKAP_INSCOUNTER_V.KD_SALES, TRANS_REKAP_INSCOUNTER_V.KD_JABATAN,TRANS_REKAP_INSCOUNTER_V.NAMA_SALES, MT.TARGET,MK.ATASAN_LANGSUNG, MK1.NAMA"
        );   

        

        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));        
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif_counter", $params));
        $data["list_k_counter"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/rekap_ins_k_counter", $param));

        $this->load->view('report/insentif_salescounter/rekap_print', $data);

        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }   

    public function insentif_counter() {

        $data = array();
    
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');
        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4";
        }else{
             $customs = "STATUS_SPK = 4";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        
        
        
         $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  =>  $this->input->get('kd_salesman'),                 
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        $jabatan = ($data['sales']->message > 0)?$data['sales']->message[0]->PERSONAL_JABATAN:"";
        
       switch ($jabatan) {
           
            case 'Kepala Counter':
                $jab ="AND MI.KATEGORI = 'Kepala Counter'";
                $jab2 ="AND MI2.KATEGORI = 'Kepala Counter'";
                break;
            case 'Sales Counter':
            case 'Counter Sales':
                $jab ="AND MI.KATEGORI = 'SC Reguler'";
                $jab2 ="AND MI2.KATEGORI = 'SC Reguler'";
                break;
            default:
                $jab ="AND MI.KATEGORI = '".$jabatan."'";
                $jab2 ="AND MI2.KATEGORI = '".$jabatan."'";
                break;
           
        }
      
            $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI2","MI2.KD_MOTOR=MG.KD_TYPEMOTOR ".$jab2." AND  MI2.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR ".$jab." AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MS.PERSONAL_JABATAN, MK.KD_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, MI2.CASH CASHH, MI2.KREDIT KREDITT, MI2.KHUSUS KHUSUSS,CASE
                
        WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 
       
        WHEN MK.PERSONAL_JABATAN ='Kepala Counter' OR MK.PERSONAL_JABATAN ='Sales Counter' AND TYPE_PENJUALAN ='CASH'  THEN MI.KREDIT       
        WHEN MK.PERSONAL_JABATAN ='SC WING' AND TYPE_PENJUALAN ='CASH'  THEN MI2.CASH

        WHEN MK.PERSONAL_JABATAN ='Kepala Counter' OR MK.PERSONAL_JABATAN ='Sales Counter' AND TYPE_PENJUALAN ='CASH'  THEN MI.KREDIT       
        WHEN MK.PERSONAL_JABATAN ='SC WING' AND TYPE_PENJUALAN ='CASH'  THEN MI2.CASH            
       
        END AS INSENTIF";
          
        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  => $this->input->get('kd_salesman'),            
            'jointable' => $jointable,            
            'field'     => $field,            
            'orderby'   => "TGL_SPK"
        );
       

        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
        
       
         
            $this->template->site('report/insentif_salescounter/view', $data);
        
    }

    public function insentif_counter_print() {

        $data = array();
    
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');
        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4";
        }else{
             $customs = "STATUS_SPK = 4";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        
        
        
         $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  =>  $this->input->get('kd_salesman'),                 
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        $jabatan = ($data['sales']->message > 0)?$data['sales']->message[0]->PERSONAL_JABATAN:"";
        
       switch ($jabatan) {
           
            case 'Kepala Counter':
                $jab ="AND MI.KATEGORI = 'Kepala Counter'";
                $jab2 ="AND MI2.KATEGORI = 'Kepala Counter'";
                break;
            case 'Sales Counter':
            case 'Counter Sales':
                $jab ="AND MI.KATEGORI = 'SC Reguler'";
                $jab2 ="AND MI2.KATEGORI = 'SC Reguler'";
                break;
            default:
                $jab ="AND MI.KATEGORI = '".$jabatan."'";
                $jab2 ="AND MI2.KATEGORI = '".$jabatan."'";
                break;
           
        }
      
            $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI2","MI2.KD_MOTOR=MG.KD_TYPEMOTOR ".$jab2." AND  MI2.ROW_STATUS >=0","LEFT"),
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR ".$jab." AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MS.PERSONAL_JABATAN, MK.KD_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, MI2.CASH CASHH, MI2.KREDIT KREDITT, MI2.KHUSUS KHUSUSS,CASE
                
        WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 
       
        WHEN MK.PERSONAL_JABATAN ='Kepala Counter' OR MK.PERSONAL_JABATAN ='Sales Counter' AND TYPE_PENJUALAN ='CASH'  THEN MI.KREDIT       
        WHEN MK.PERSONAL_JABATAN ='SC WING' AND TYPE_PENJUALAN ='CASH'  THEN MI2.CASH

        WHEN MK.PERSONAL_JABATAN ='Kepala Counter' OR MK.PERSONAL_JABATAN ='Sales Counter' AND TYPE_PENJUALAN ='CASH'  THEN MI.KREDIT       
        WHEN MK.PERSONAL_JABATAN ='SC WING' AND TYPE_PENJUALAN ='CASH'  THEN MI2.CASH            
       
        END AS INSENTIF";
          
        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,
            'kd_sales'  => $this->input->get('kd_salesman'),            
            'jointable' => $jointable,            
            'field'     => $field,            
            'orderby'   => "TGL_SPK"
        );
       

        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));         
        $this->load->view('report/insentif_salescounter/print', $data);

        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }


    public function insentif_k_counter() {

        $data = array();    
        $start_date =$this->input->get('tgl_awal');
        $end_date =$this->input->get('tgl_akhir');
        $custom = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4 AND MK1.PERSONAL_JABATAN = 'Kepala Counter' ";
        }else{
             $customs = "STATUS_SPK = 4 AND MK1.PERSONAL_JABATAN = 'Kepala Counter'";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");        
        $nik_salesman = $this->input->get('nik_salesman');
        $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'custom'    => "MK.NIK ='".$nik_salesman."'",
            'kd_sales'  =>  $this->input->get('kd_ksalesman'),            
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        
       /* print_r($data["sales"]);die();*/
        
                $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK1","MK.ATASAN_LANGSUNG=MK1.NIK  AND MK1.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SJMASUK TSM","TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),                        
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Counter' AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MK.KD_JABATAN, MK.NAMA, MK.PERSONAL_JABATAN, MK.ATASAN_LANGSUNG,MK1.NIK,MK1.NAMA NAMA_ATASAN,MK1.PERSONAL_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET, SK.JATUH_TEMPO,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C, 
                            TSM.TGL_SJMASUK,MG.CATEGORY_MOTOR,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, CASE                 
                            WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 
                            WHEN  TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN  TYPE_PENJUALAN ='CASH'  THEN MI.CASH                           
                            END AS INSENTIF,CASE WHEN (DATEDIFF(DAY,convert(date, SK.JATUH_TEMPO) , convert(date, TRANS_SPK.TGL_SPK) )) > 10 THEN 10000 END AS PENALTY_AR";
           

        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,                    
            'jointable' => $jointable,
            'field'     => $field,
            'orderby'   => "TGL_SPK"
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
        
      //print_r($paramm);die();
      //print_r($data["sales"]);die();
        $this->template->site('report/insentif_salescounter/view_k', $data);
        
    }   

      public function insentif_k_counter_print() {

         $data = array();    
        $start_date =$this->input->get('tgl_awal');
        $end_date =$this->input->get('tgl_akhir');
        $custom = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";

        if ($start_date != "" && $end_date != "") {
            $customs =$custom." AND STATUS_SPK = 4 AND MK1.PERSONAL_JABATAN = 'Kepala Counter' ";
        }else{
             $customs = "STATUS_SPK = 4 AND MK1.PERSONAL_JABATAN = 'Kepala Counter'";
        }
      
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");        
        $nik_salesman = $this->input->get('nik_salesman');
        $paramm = array(            
            'kd_dealer' => $kd_dealer,
            'custom'    => "MK.NIK ='".$nik_salesman."'",
            'kd_sales'  =>  $this->input->get('kd_ksalesman'),            
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK=MASTER_SALESMAN.NIK","LEFT"),
                            array("MASTER_TARGETSF MT","MT.KD_SALES=MASTER_SALESMAN.KD_SALES","LEFT")
                        ),

            'field'      => "MASTER_SALESMAN.NIK,MASTER_SALESMAN.KD_SALES,MASTER_SALESMAN.NAMA_SALES, MK.KD_JABATAN,
                            MK.PERSONAL_JABATAN,MT.TARGET",
            'orderby' => "MASTER_SALESMAN.NIK"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));
        
       /* print_r($data["sales"]);die();*/
        
                $jointable = array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("MASTER_SALESMAN MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.KD_DEALER = TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK","MK.NIK=MS.NIK  AND MK.ROW_STATUS >=0","LEFT"),
                            array("MASTER_KARYAWAN MK1","MK.ATASAN_LANGSUNG=MK1.NIK  AND MK1.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_SALESPROGRAM SS","SS.NO_SPK=TRANS_SPK.NO_SPK AND SS.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SJMASUK TSM","TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_TYPEMOTOR MTM"," MTM.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >=0","LEFT"),
                            array("MASTER_P_GROUPMOTOR MG"," MG.KD_TYPEMOTOR=SD.KD_TYPEMOTOR AND MG.ROW_STATUS >=0","LEFT"),                        
                            array("MASTER_INSENTIF MI","MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Counter' AND  MI.ROW_STATUS >=0","LEFT")
                                
                            );

            $field      = "TRANS_SPK.*,MC.NAMA_CUSTOMER, MK.KD_JABATAN, MK.NAMA, MK.PERSONAL_JABATAN, MK.ATASAN_LANGSUNG,MK1.NIK,MK1.NAMA NAMA_ATASAN,MK1.PERSONAL_JABATAN,
                            SK.ID AS LEASINGID,SK.UANG_MUKA,SK.HASIL,SK.KETERANGAN KET, SK.JATUH_TEMPO,
                            SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA,SD.HARGA_OTR,
                            (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR) AS SUB_DLR_K,
                            (SS.SC_AHM+SS.SC_MD+SS.SC_SD) AS SUB_DLR_C, 
                            TSM.TGL_SJMASUK,MG.CATEGORY_MOTOR,
                            MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR,
                            MG.CATEGORY_MOTOR, MI.CASH, MI.KREDIT, MI.KHUSUS, CASE                 
                            WHEN  (SS.SK_AHM+SS.SK_MD+SS.SK_SD+SS.SK_FINANCE+SS.DP_OTR+SD.DISKON) >  150000 OR (SS.SC_AHM+SS.SC_MD+SS.SC_SD+SD.DISKON) > 150000 THEN MI.KHUSUS 
                            WHEN  TYPE_PENJUALAN ='CREDIT'  THEN MI.KREDIT
                            WHEN  TYPE_PENJUALAN ='CASH'  THEN MI.CASH                           
                            END AS INSENTIF,CASE WHEN (DATEDIFF(DAY,convert(date, SK.JATUH_TEMPO) , convert(date, TRANS_SPK.TGL_SPK) )) > 10 THEN 10000 END AS PENALTY_AR";
           

        $params = array(
            'keyword'   => $this->input->get('keyword'),
            'custom'    => $customs,            
            'kd_dealer' => $kd_dealer,                    
            'jointable' => $jointable,
            'field'     => $field,
            'orderby'   => "TGL_SPK"
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $params));
        
     
        $this->load->view('report/insentif_salescounter/print_k', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }   

    public function insentif_ksp() {

        $data = array();              
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));            
        $kd_dealer      = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata('kd_dealer');          
        $params = array(
            'keyword'   => $this->input->get('keyword'),                     
            'kd_dealer' => $kd_dealer,     
            'jointable' => array(
                            array("MASTER_KARYAWAN MK"," MK.NIK = TRANS_INSENTIF_KSP.NIK AND MK.ROW_STATUS >= 0 ","LEFT")
                            ),                 
            'field'      => "TRANS_INSENTIF_KSP.*, MK.NAMA",
            
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/Laporan/insentif_ksp", $params));
        //print_r($data["list"]);die();     
        $this->template->site('report/insentif_ksp/view', $data);
        
    } 

    public function insentif_ksp_print() {

        $data = array();              
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));        
        $kd_dealer      = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata('kd_dealer');
        $id      = $this->input->get('id');        
          
        $params = array(
            'keyword'   => $this->input->get('keyword'),                    
            'jointable' => array(
                            array("MASTER_KARYAWAN MK","MK.NIK = TRANS_INSENTIF_KSP.NIK AND MK.ROW_STATUS >= 0","LEFT")
                            ),      
             'custom'      => "TRANS_INSENTIF_KSP.ID = ".$id,
             'field'      => "TRANS_INSENTIF_KSP.*,MK.NAMA",
            
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/Laporan/insentif_ksp", $params));
        //print_r($params);die();
        //print_r($data["list"]);die();
     
        $this->load->view('report/insentif_ksp/print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    } 


    public function add_insentif_ksp() {

        $data = array();     
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');
        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";         
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param = array(
            'kd_dealer'  => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),            
            'kd_jabatan' => ($this->input->get('kd_jabatan')) ? $this->input->get('kd_jabatan') : ''            
           
        );      
        
          $paramm = array( 
            'kd_dealer' => $param["kd_dealer"],         
            'custom'        => "(PERSONAL_JABATAN like '%KSP%' OR PERSONAL_JABATAN like '%Kepala Sentra Penjualan%') AND KD_STATUS ='STS-1'",
          
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
      
       /* $params2 = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom,              
                            
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(
                                array("MASTER_TARGETSF MT","MT.KD_SALES=TRANS_REKAP_V.KD_SALES AND MT.START_DATE >='".tglToSql($start_date)."' AND MT.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_V.NIK AND MK.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK1","MK1.NIK=MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0","LEFT")
                             ),
            'field'        => "TRANS_REKAP_V.KD_DEALER, TRANS_REKAP_V.KD_MAINDEALER, count(*) AS JUAL",  
            'groupby_text' => "TRANS_REKAP_V.KD_DEALER, TRANS_REKAP_V.KD_MAINDEALER"                      
                        
        );   
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif", $params2));
        */
        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramm));       
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_ins_ksp", $param));    
        //print_r($data['list']);die();       
        $this->template->site('report/insentif_ksp/add_insentif_ksp', $data);
        
    }
    public function add_insentif_ksp_simpan(){

            $param = array(
                'no_trans'        => "",               
                'kd_maindealer'   => $this->input->post("kd_maindealer"),
                'kd_dealer'       => $this->input->post("kd_dealer"),
                'nik'             => $this->input->post("nik"),
                'periode_awal'    => $this->input->post("tgl_awal"),
                'periode_akhir'   => $this->input->post("tgl_akhir"),
                'tgl_pengajuan'   => $this->input->post("tgl_pengajuan"),
                'total_sales'     => $this->input->post("total_sales"),
                'sales_tambah'    => $this->input->post("sales_tambah"),
                'sales_kurang'    => $this->input->post("sales_kurang"),
                'rpk'             => $this->input->post("rpk"),
                'margin_unit'     => $this->input->post("margin_unit"),
                'insentif_unit'   => $this->input->post("insentif_unit"),
                'total_insentif'  => $this->input->post("total_insentif"),
                'penalty'         => $this->input->post("penalty"),
                'pph21'           => $this->input->post("pph21"),
                'insentif_terima' => $this->input->post("insentif_terima"),                
                'created_by'      => $this->session->userdata('user_id')
                );

            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/laporan/insentif_ksp", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);die();
            $data = json_decode($hasil);  
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('laporan_insentif/insentif_ksp'));
       
    }
    

    public function rekap_insentif_ksp_print() {

        $data = array();     
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');
        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  "";         
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param = array(
            'kd_dealer'  => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),            
            'kd_jabatan' => ($this->input->get('kd_jabatan')) ? $this->input->get('kd_jabatan') : ''            
           
        );      
        
        $paramm = array(              
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
      
        $params2 = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom,              
                            
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(
                                array("MASTER_TARGETSF MT","MT.KD_SALES=TRANS_REKAP_V.KD_SALES AND MT.START_DATE >='".tglToSql($start_date)."' AND MT.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_V.NIK AND MK.ROW_STATUS >=0","LEFT"),
                                array("MASTER_KARYAWAN MK1","MK1.NIK=MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0","LEFT")
                             ),
            'field'        => "TRANS_REKAP_V.KD_DEALER, count(*) AS JUAL",  
            'groupby_text' => "TRANS_REKAP_V.KD_DEALER"                      
                        
        );   
         
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));        
       
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif", $params2));
       /* print_r($params);die();*/
        //print_r($data["list"]);die();
           
        $this->load->view('report/insentif_ksp/rekap_print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }


    public function penalty_ksp() {

       $data = array();   
       $param['tgl_awal'] = $this->input->get('tgl_awal');
       $param['tgl_akhir'] = $this->input->get('tgl_akhir'); 
      // print_r($param);die();  
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");    
        $paramm = array(              
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/penalty_ksp", $param));      
      
        //print_r($data["list"]);die();
        $this->template->site('report/insentif_ksp/penalty', $data);
        
    }

     public function penalty_ksp_print() {

       $data = array();   
       $param['tgl_awal'] = $this->input->get('tgl_awal');
       $param['tgl_akhir'] = $this->input->get('tgl_akhir'); 
      // print_r($param);die();  
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");    
        $paramm = array(              
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/penalty_ksp", $param));      
      
        //print_r($data["list"]);die();
        $this->load->view('report/insentif_ksp/penalty_print', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
        
    }


    public function list_exclude_ksp() {
       
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/exc_penalty"));
      
        $this->template->site('report/insentif_ksp/view_exclude', $data);

        
    }
    public function exclude_ksp() {      
      
        $this->load->view('report/insentif_ksp/exclude');

        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

     public function exclude_ksp_simpan() {
        
            $param = array(
                'nik' => $this->input->post("nik"),
                'no_mesin'      => $this->input->post("no_mesin"),
                'kd_dealer'     => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'tgl'           => $this->input->post("tgl_awal"),              
            );
            $hasil = $this->curl->simple_post(API_URL ."/api/laporan/exc_penalty", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
          
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('laporan_insentif/insentif_ksp'));

        
    }


    public function insentif_kops() {
        $data = array();            
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));        
        $kd_dealer      = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata('kd_dealer');                  
        $params = array(
            'keyword'   => $this->input->get('keyword'),                     
            'kd_dealer' => $kd_dealer,            
            'field'     => "*",
            'orderby'   => "TGL_PENGAJUAN"
        );
        
        $data["list"] = json_decode($this->curl->simple_get(API_URL."/api/Laporan/insentif_kops", $params));
        //print_r($data["list"]);die();     
        $this->template->site('report/insentif_kops/view', $data);        
    } 

    public function detail_insentif_kops() {
        $data = array();                
        $params = array(
            'keyword'   => $this->input->get('keyword'),                     
            'insentif_kops_id' => $this->input->get('insentif_kops_id'),              
            'orderby' => "PERIODE"
        );
        
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/Laporan/insentif_kops_detail", $params));
        
        $this->template->site('report/insentif_kops/detail_insentif', $data);
        
    } 

    public function add_insentif_kops() {

        $data       = array();     
        $start_date = $this->input->get('tgl_awal');
        $end_date   = $this->input->get('tgl_akhir');
        $custom     = ($this->input->get("tgl_awal"))?  "TGL_SPK >= '" . tglToSql($this->input->get("tgl_awal")). "' AND TGL_SPK <= '" . tglToSql($this->input->get("tgl_akhir")) ."'" :  ""; 
        $tgl_pengajuan = ($this->input->get("tgl_pengajuan")) ? tglToSql($this->input->get('tgl_pengajuan')) : date('Ymd');
        $param = array(
            'kd_dealer'  => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")             
        );      
        //print_r( $this->session->userdata("kd_dealer"));die();
        
        $paramm = array( 
            'kd_dealer' => $param["kd_dealer"],         
            'custom'    => "(PERSONAL_JABATAN like '%operasional%' OR PERSONAL_JABATAN like '%ops%') AND KD_STATUS ='STS-1'",
          
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
      
        $params2 = array(
              'keyword'   => $this->input->get('keyword'),
              'custom'    => $custom,                             
              'kd_dealer' => $param["kd_dealer"],
              'jointable' => array(                               
                                array("MASTER_KARYAWAN MK","MK.NIK=TRANS_REKAP_V.NIK AND MK.ROW_STATUS >=0","LEFT")
                              
                             ),
            'field'        => "TRANS_REKAP_V.KD_DEALER, TRANS_REKAP_V.KD_MAINDEALER,YEAR(TRANS_REKAP_V.TGL_SPK) as TAHUN, MONTH(TRANS_REKAP_V.TGL_SPK) as BULAN, count(*) AS JUAL",  
            'groupby_text' => "TRANS_REKAP_V.KD_DEALER, TRANS_REKAP_V.KD_MAINDEALER, YEAR(TRANS_REKAP_V.TGL_SPK),MONTH(TRANS_REKAP_V.TGL_SPK)",
            'orderby' => "TAHUN ,BULAN"
                        
        );           

        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $paramm));           
        $data["list"]  = json_decode($this->curl->simple_get(API_URL."/api/rekap/rekap_insentif", $params2));  
        //print_r( $data["list"]);die();         
        $this->template->site('report/insentif_kops/add_ins_kops', $data);
        
    }

    public function add_insentif_kops_simpan(){

         $param1 = array(
                        
                'tgl_trans'       => date('Ymd'),                               
                'periode_awal'    => $this->input->post('periode_awal'),
                'periode_akhir'   => $this->input->post('periode_akhir'),               
                'kd_dealer'       => $this->input->post('kd_dealer'),   
                'kd_maindealer'   => $this->input->post('kd_maindealer'),   
                'total_penjualan' => $this->input->post('total_sales'),   
                'nik'             => $this->input->post('nik'),   
                'tgl_pengajuan'   => $this->input->post('tgl_pengajuan'),   
                'margin_unit'     => $this->input->post('margin_unit'),                  
                'created_by'      => $this->session->userdata('user_id')
                );
       
            print_r($param1);die();
            
            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/laporan/insentif_kops", $param1, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $id_ins_kops = $data->message;

        $param = array(
                        
                'no_trans'          => "1",
                'insentif_kops_id'  => "1",//$this->input->post('insentif_kops_id'),                
                'periode'           => tglToSql($this->input->post('periode')),
                'p1'                => $this->input->post('p1'),
                'p1_total'          => $this->input->post('p1_total'),
                'p2'                => $this->input->post('p2'),
                'p2_total'          => $this->input->post('p2_total'),
                'p3'                => $this->input->post('p3'),
                'p3_total'          => $this->input->post('p3_total'),
                'p4'                => $this->input->post('p4'),
                'p4_total'          => $this->input->post('p4_total'),
                'p5'                => $this->input->post('p5'),              
                'p6'                => $this->input->post('p6'),
                'p7'                => $this->input->post('p7'),
                'p8'                => $this->input->post('p8'),
                'p9'                => $this->input->post('p9'),
                'p10'               => $this->input->post('p10'),
                'p11'               => $this->input->post('p11'),
                'total_penjualan'   => $this->input->post('total_sales'),   
                'created_by'        => $this->session->userdata('user_id')
                );

       print_r($param);die();
             //print_r($param);die();
            /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
            $hasil = $this->curl->simple_post(API_URL . "/api/laporan/insentif_kops_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            //print_r($hasil);die();
            $data = json_decode($hasil);  
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('laporan_insentif/insentif_kops'));
       
    }

    public function penalty_kops() {

       $data = array();   
       $param['tgl_awal'] = $this->input->get('tgl_awal');
       $param['tgl_akhir'] = $this->input->get('tgl_akhir'); 
      // print_r($param);die();  
       $param['kd_dealer']  = ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");    
        $paramm = array(              
            'field'        => "KD_JABATAN,PERSONAL_JABATAN",
            'groupby_text' => "PERSONAL_JABATAN"
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
        $data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $paramm));    
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/rekap/penalty_ksp", $param));      
      
        //print_r($data["list"]);die();
        $this->template->site('report/insentif_kops/penalty', $data);
        
    }

    public function exclude_kops() {      
      
        $this->load->view('report/insentif_kops/exclude');

        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function exclude_kops_simpan() {
     
        
            $param = array(
                'nik'           => $this->input->post("nik"),
                'no_mesin'      => $this->input->post("no_mesin"),
                'kd_dealer'     => $this->input->post("kd_dealer"),
                'kd_maindealer' => $this->input->post("kd_maindealer"),
                'tgl'           => $this->input->post("tgl_awal"),              
            );
            $hasil = $this->curl->simple_post(API_URL ."/api/laporan/exc_penalty", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
          
            $this->session->set_flashdata('tr-active', $data->message);
            $this->data_output($hasil, 'post', base_url('laporan_insentif/insentif_kops'));
        
    } 
    

    public function getCounter() {
        $param = array(
            'kd_dealer' => $this->input->get('kd'),
            'custom' => "(PERSONAL_JABATAN ='Counter Sales' OR PERSONAL_JABATAN ='Sales Counter')"

        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        //print_r($data);die();


        echo "<option value=''>--Pilih KSP--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->NIK . "'>" . $value->NAMA . " - ".$value->PERSONAL_JABATAN."</option>";
                }
            }
        }
    }

    public function getKSP() {
        $param = array(
            'kd_dealer' => $this->input->get('kd'),
            'custom' => "(PERSONAL_JABATAN ='Kepala Sentra Penjualan' OR PERSONAL_JABATAN ='KSP')"

        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        //print_r($data);die();


        echo "<option value=''>--Pilih KSP--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->NIK . "'>" . $value->NAMA . " - ".$value->PERSONAL_JABATAN."</option>";
                }
            }
        } 
    }

    public function getSalesman() {
        $param = array(
            'kd_dealer' => $this->input->get('kd'),
            'jointable' => array(
                                    array("MASTER_KARYAWAN MK", "MK.NIK = MASTER_SALESMAN.NIK AND MK.ROW_STATUS >=0","LEFT")
                                ),
            'field'     => "MASTER_SALESMAN.ID, MASTER_SALESMAN.NIK, MASTER_SALESMAN.KD_SALES, MASTER_SALESMAN.KD_DEALER, MK.PERSONAL_JABATAN",
            'custom' => "MK.PERSONAL_JABATAN = 'Sales' OR MK.PERSONAL_JABATAN = 'Salesman' MK.PERSONAL_JABATAN = 'Salesgirl' "

        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
      
        echo "<option value=''>--Pilih Salesman--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->KD_SALES . "'>" . $value->NAMA_SALES . " - ".$value->KD_JABATAN."</option>";
                }
            }
        }
    }    
    public function getKSalesman() {
        $param = array(
            'kd_dealer' => $this->input->get('kd'),
            'jointable' => array(
                                    array("MASTER_KARYAWAN MK", "MK.NIK = MASTER_SALESMAN.NIK AND MK.ROW_STATUS >=0","LEFT")
                                ),
            'field'     => "MASTER_SALESMAN.ID, MASTER_SALESMAN.NIK, MASTER_SALESMAN.KD_SALES, MASTER_SALESMAN.KD_DEALER, MK.PERSONAL_JABATAN",
            'custom' => "MK.PERSONAL_JABATAN = 'Kepala Sales' MK.PERSONAL_JABATAN = 'Koordinator Sales' "

        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
      
        echo "<option value=''>--Pilih Salesman--</option>";
        if ($data) {
            if (is_array($data->message)) {
                foreach ($data->message as $key => $value) {
                    echo "<option value='" . $value->NIK . "'>" . $value->NAMA_SALES . " - ".$value->KD_JABATAN."</option>";
                }
            }
        }
    }    
   
    public function getNamaSalesman() {
        $param = array(
            'kd_sales' => $this->input->get('kd')
        );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/salesman", $param));
        
        if ($data) {
            if (is_array($data->message)) {
                echo($data->message[0]->NAMA_SALES);
            }
        }
        
        
    }         

    

}
