<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_service extends CI_Controller {

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

    //service advisor 
    /**
     * list data guest book
     * @return [type] [description]
     */
    public function service_advisor_list() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page'),
            //'jointable' => array(array("TRANS_PARTSO_CUSTOMER_V P","P.NO_MESIN=TRANS_CSA.NO_MESIN","LEFT","KD_CUSTOMER,NAMA_CUSTOMER,NO_MESIN")),
            'field' => "TRANS_CSA.KD_SA,TRANS_CSA.TANGGAL_SA,TRANS_CSA.STATUS_SA,TRANS_CSA.NO_STNK,TRANS_CSA.NO_POLISI,
                        TRANS_CSA.NO_MESIN,TRANS_CSA.NO_RANGKA,TRANS_CSA.NAMA_PEMILIK AS NAMA_CUSTOMER,TRANS_CSA.ALAMAT,
                        TRANS_CSA.NO_HP,TRANS_CSA.KD_TIPEPKB,TRANS_CSA.KEBUTUHAN_KONSUMEN,TRANS_CSA.HASIL_ANALISA_SA, 
                        ISNULL(TRANS_CSA.KD_CUSTOMER,'')KD_CUSTOMER,KM_SAATINI,TRANS_CSA.ID",
            'orderby' => 'TRANS_CSA.ID DESC',
            'limit' => 15
        );
        
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_SA,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_SA,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }

        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang/true"));
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
        $this->template->site('sales/service_advisor_list', $data); //, $data
    }

    /**$
     * load form inputan guestbook
     */
    public function add_service_advisor() {
        $data = array();
        $param = array();

        if($this->input->get("n")){
            $param=array(
                'kd_sa' => base64_decode(urldecode($this->input->get("n"))),
                'jointable' =>array(
                    array("TRANS_CSA_DETAIL TPD" , "TPD.KD_SA=TRANS_CSA.KD_SA AND TPD.ROW_STATUS>=0", "LEFT"),
                ),
                "field" => "TRANS_CSA.*, TPD.ID AS DETAIL_ID, TPD.KD_PEKERJAAN, TPD.QTY, TPD.HARGA_SATUAN, TPD.TOTAL_HARGA, TPD.KATEGORI, TRANS_CSA.TGL_BELI AS TGL_TERIMA,
                    CASE WHEN TPD.KATEGORI='Part' 
                    THEN (SELECT TOP 1 S.PART_DESKRIPSI FROM MASTER_PART as S WHERE S.PART_NUMBER=TPD.KD_PEKERJAAN)
                    ELSE (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END PART_DESKRIPSI",
                "orderby" => "TPD.KATEGORI asc"
            );
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));
        }

        $data["tipeservice"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipeservicemekanik"));
        $data["tipepkb"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/tipepkb"));
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_service/typecomingcustomer"));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $paramdealer = array(
            'field' => "KD_LOKASI, NAMA_LOKASI",
            'kd_dealer' =>($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer")
        );
        
        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/lokasidealer",$paramdealer));
        
        // $this->output->set_output(json_encode($data));
        $this->template->site('sales/service_advisor', $data);
    }

    public function get_detailcustomer()
    {

        $param=array(
            'no_polisi' => $this->input->get("no_polisi")
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bukti/true", $param));

        $this->output->set_output(json_encode($data));

    }

    public function simpan_service_advisor() {
        $this->form_validation->set_rules('no_polisi', 'No Polisi', 'required|trim');
        $this->form_validation->set_rules('no_mesin', 'No Mesin', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {

            $hasil = array();
            $data = array();
            $ntrans = ($this->input->post('kd_sa')) ? $this->input->post('kd_sa') : $this->autogenerate_sa();
            $param = array(
                'kd_sa' => $ntrans, //$this->input->post('kd_sa'),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'no_polisi' => $this->input->post("no_polisi"),
                'kd_lokasidealer' => $this->input->post("kd_lokasidealer"),
                'kd_customer' => $this->input->post("kd_customer"),
                'tanggal_sa' => $this->input->post("tanggal_sa"),
                'no_mesin' => strtoupper($this->input->post("no_mesin")),
                'no_rangka' => strtoupper($this->input->post("no_rangka")),
                'kd_pembawamotor' => $this->input->post("kd_pembawamotor"),
                'kd_pemakaimotor' => $this->input->post("kd_pemakaimotor"),
                'kd_typecomingcustomer' => $this->input->post("kd_typecomingcustomer"),
                'kd_honda' => $this->input->post("kd_honda"),
                'kd_tipepkb' => $this->input->post("kd_tipepkb"),
                'kd_jenispit' => $this->input->post("kd_jenispit"),
                'foto_konsumen' => $this->input->post("foto_konsumen"),
                'dokumen' => $this->input->post("dokumen"),
                'estimasi_pendaftaran' => $this->input->post("estimasi_pendaftaran"),
                'estimasi_pengerjaan' => $this->input->post("estimasi_pengerjaan"),
                'estimasi_selesai' => $this->input->post("estimasi_selesai"),
                'hasil_analisa_sa' => $this->input->post("hasil_analisa_sa"),
                'kebutuhan_konsumen' => $this->input->post("kebutuhan_konsumen"),
                'saran_mekanik' => $this->input->post("saran_mekanik"),
                'km_saatini' => $this->input->post("km_saatini"),
                'kd_pekerjaan' => $this->input->post("kd_pekerjaan"),
                'part_number' => $this->input->post("part_number"),
                'total_frt' => $this->input->post("total_frt"),
                'amount' => $this->input->post("amount"),
                'no_pit' => $this->input->post("no_pit"),
                'kd_typeservice' => $this->input->post("kd_typeservice"),
                'kd_setuppembayaran' => $this->input->post("kd_setuppembayaran"),
                'alamat_comingcustomer' => $this->input->post("alamat_pembawa"),
                'bensin_saatini' => $this->input->post("bensin_saatini"),
                'hp_comingcustomer' => $this->input->post("nohp_pembawa"),
                'no_buku' => $this->input->post("no_buku"),
                'status_sa' => $this->input->post("status_sa"),
                'no_stnk' => $this->input->post("no_stnk"),
                'nama_pemilik' => $this->input->post("nama_pemilik"),
                'no_hp' => $this->input->post("no_hp"),
                'alamat' => $this->input->post("alamat"),
                'nama_comingcustomer' => $this->input->post("nama_comingcustomer"),
                'kd_typemotor' => $this->input->post("kd_typemotor"),
                'catatan_tambahan' => $this->input->post("catatan_tambahan"),
                'konfirmasi_pekerjaantambahan' => $this->input->post("konfirmasi_pekerjaantambahan"),
                'kd_propinsi' => $this->input->post("kd_propinsi"),
                'kd_kabupaten' => $this->input->post("kd_kabupaten"),
                'kd_kecamatan' => $this->input->post("kd_kecamatan"),
                'kd_kelurahan' => $this->input->post("kd_kelurahan"),
                'kd_propinsi_comingcustomer' => $this->input->post("kd_propinsi_comingcustomer"),
                'kd_kabupaten_comingcustomer' => $this->input->post("kd_kabupaten_comingcustomer"),
                'kd_kecamatan_comingcustomer' => $this->input->post("kd_kecamatan_comingcustomer"),
                'kd_kelurahan_comingcustomer' => $this->input->post("kd_kelurahan_comingcustomer"),
                'tahun' => $this->input->post("tahun"),
                'tgl_beli' => $this->input->post("tgl_beli"),
                'created_by' => $this->session->userdata('user_id')
                
            );
            $data=array();
            $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/csa", $param, array(CURLOPT_BUFFERSIZE => 100));
            $method = "post";
            

            $data=json_decode($hasil);
            if($data){
                if($data->recordexists==true){
                    $param["lastmodified_by"] = $this->session->userdata('user_id');
                    $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/csa", $param, array(CURLOPT_BUFFERSIZE => 100));
            // var_dump($param);exit;
                    $method = "put";
                }
            }


            if($hasil)
            {
                if(json_decode($hasil)->message>0){
                    $post_detail = $this->sa_detail($ntrans);
                }
            }  


            // $this->data_output($hasil, 'post', base_url('customer_service/service_advisor_list'));
            // $this->data_output($hasil, 'post', base_url('pkb/add_pkb?n=') . $ntrans); // 
            $this->data_output($hasil, $method, base_url('customer_service/add_service_advisor?n='.urlencode(base64_encode($ntrans)))); 
        }
    }

    public function delete_csa_detail() {
        $param = array(
            'id' => $this->input->get('id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/csa_detail", $param));
        $this->data_output($data, 'delete');
    }

    public function sa_detail($ntrans)
    {
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'kd_sa' => $ntrans,
                'kd_pekerjaan' => $detail[$i]['kd_pekerjaan'],
                'qty' => $detail[$i]['qty'],
                'harga_satuan' => $detail[$i]['harga_satuan'],
                'total_harga' => $detail[$i]['total_harga'],
                'kategori' => $detail[$i]['kategori'],
                'created_by' => $this->session->userdata("user_id")
            );
                // var_dump($param); exit;
            $hasil = $this->curl->simple_post(API_URL . "/api/transaksi/csa_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
            if(json_decode($hasil)->recordexists==TRUE){
                $param_pkb = array(
                    'kd_sa' => $ntrans, 
                    'kd_pekerjaan' => $detail[$i]['kd_pekerjaan'],
                    'field' => '*'
                );
                $pkb= json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa_detail", $param_pkb));
                $param_update = array(
                    'kd_sa' => $ntrans,
                    'kd_pekerjaan' => $detail[$i]['kd_pekerjaan'],
                    'qty' => $detail[$i]['qty'] + $pkb->message[0]->QTY,
                    'harga_satuan' => $detail[$i]['harga_satuan'],
                    'total_harga' => $detail[$i]['total_harga'] + $pkb->message[0]->TOTAL_HARGA,
                    'kategori' => $detail[$i]['kategori'],
                    'lastmodified_by' => $this->session->userdata("user_id")
                );
                // var_dump($pkb); exit;
            // var_dump($param_update);exit;
                $hasil= $this->curl->simple_put(API_URL."/api/transaksi/csa_detail",$param_update, array(CURLOPT_BUFFERSIZE => 10));  
            }
        }

    }

    public function cetak_sa()
    {
        $this->load->library('dompdf_gen');
        
        
        $param=array(
            'jointable' =>array(
                array("TRANS_CSA_DETAIL TPD" , "TPD.KD_SA=TRANS_CSA.KD_SA AND TPD.ROW_STATUS>=0", "LEFT"),
            ),
            "field" => "TRANS_CSA.*, TPD.ID AS DETAIL_ID, TPD.KD_PEKERJAAN, TPD.QTY, TPD.HARGA_SATUAN, TPD.TOTAL_HARGA, TPD.KATEGORI, TRANS_CSA.TGL_BELI AS TGL_TERIMA,
                CASE WHEN TPD.KATEGORI='Part' 
                THEN (SELECT TOP 1 S.PART_DESKRIPSI FROM MASTER_PART as S WHERE S.PART_NUMBER=TPD.KD_PEKERJAAN)
                ELSE (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END PART_DESKRIPSI",
            'kd_sa' => base64_decode(urldecode($this->input->get("n")))
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));

        // $param=array("kd_dealer"=>$this->session->userdata("kd_dealer"));
        // $data["dealer"]= json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",$param));        
        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));

        // $this->output->set_output(json_encode($data));

        // $html = $this->load->view('sales/form_sa', $data);

        $html = $this->load->view('sales/form_sa', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'portrait');

    }

    function autogenerate_sa() {
        $no_sa = "";
        $nomorsa = 0;
        $param = array(
            'kd_docno' => 'SA',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' =>date('Y'),// substr($this->input->post('tanggal_sa'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tgl_spk'), 3, 2),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim =date('m');// substr($this->input->post('tanggal_sa'), 3, 2);
        $nomorsa = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorsa == 0) {
            $no_sa = "SA" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorsa = $nomorsa + 1;
            $no_sa = "SA" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorsa, 5, '0', STR_PAD_LEFT);
        }
        return $no_sa;
    }
    public function no_polisi($debug=null){
        $data=array();
        $result="";
        $param=array(
            'no_polisi'=>$this->input->get('n'),
            'field' =>"NO_POLISI,MAX(NO_MESIN)NO_MESIN,MAX(NO_RANGKA)NO_RANGKA,MAX(NAMA_CUSTOMER)NAMA_CUSTOMER,
                       MAX(ALAMAT_SURAT)ALAMAT_SURAT,MAX(NO_HP)NO_HP,ISNULL(MAX(NAMA_PROPINSI),'')NAMA_PROPINSI,
                       ISNULL(MAX(NAMA_KABUPATEN),'')NAMA_KABUPATEN,ISNULL(MAX(NAMA_KECAMATAN),'')NAMA_KECAMATAN,
                       ISNULL(MAX(NAMA_DESA),'')NAMA_DESA,ISNULL(MAX(KODE_POS),'')KODE_POS",
            'groupby_text'=>"NO_POLISI"
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/sopart_customer/true", $param));
        $result =$data;
        if($debug){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    function get_datacsa($debug=null){
        $data=array();
        $param=array(
            'no_polisi' =>$this->input->get("n")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));
        $result =$data;
        if($debug){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    function get_datanopol($debug=null){
        $data=array();
        $param=array(
            'no_polisi' => $this->input->get("n")
        );
        $data=json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_bukti/true", $param));
        $result =$data;
        if($debug){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    public function get_nopol() {

        $param = array(
            'jointable' => array(
                array("TRANS_SJKELUAR AS SJ", "SJ.NO_SURATJALAN = TRANS_STNK_UDH.NO_SURATJALAN", "LEFT"),
                array("TRANS_SPK AS TS", "SJ.NO_REFF=TS.NO_SO","LEFT"),
                array("TRANS_SPK_DETAILALAMAT AS DA", "DA.SPK_ID=TS.ID","LEFT")
            ), 
            'custom' => "DATA_NOMOR_PLAT = '" . $this->input->get('no_polisi') . "'",
            'field' => "TRANS_STNK_UDH.*, DA.ALAMAT_SURAT, DA.NO_HP"
        );

        $data["nopol_header"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/udh", $param));

        $this->output->set_output(json_encode($data));
    }

    public function nopol_typeahead() {

        $param = array(
            'status_stnk' => 7
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/udh", $param)); //

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->DATA_NOMOR_PLAT;
        }

        $result['keyword'] = array_merge($data_message[0]);

        $this->output->set_output(json_encode($result));
    }
    
    public function service_advisor_detail($kd_sa, $row_status) {
        $this->auth->validate_authen('customer_service/service_advisor_list');
        $param = array(
            'custom' => "KD_SA = '" . $kd_sa . "'",
            'row_status' => $row_status
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa", $param));

        $data["lokasidealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/gudang/true"));
        $this->template->site('sales/service_advisor', $data);
    }

    public function service_advisor_update() {
        $this->form_validation->set_rules('kd_sa', 'Kode SA', 'required|trim');
        $this->form_validation->set_rules('kd_customer', 'Kode Customer', 'required|trim');
        $this->form_validation->set_rules('kd_lokasidealer', 'Kode Lokasi Dealer', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'status' => false,
                'message' => validation_errors()
            );

            $this->output->set_output(json_encode($data));
        } else {
            $param = array(
                'id' => $this->input->post("id"),
                'kd_sa' => $this->input->post("kd_sa"),
                'kd_customer' => $this->input->post("kd_customer"),
                'tanggal_sa' => $this->input->post("tanggal_sa"),
                'no_polisi' => $this->input->post("no_polisi"),
                'no_mesin' => $this->input->post("no_mesin"),
                'no_rangka' => $this->input->post("no_rangka"),
                'kd_pembawamotor' => $this->input->post("kd_pembawamotor"),
                'kd_pemakaimotor' => $this->input->post("kd_pemakaimotor"),
                'kd_typecomingcustomer' => $this->input->post("kd_typecomingcustomer"),
                'kd_honda' => $this->input->post("kd_honda"),
                'kd_tipepkb' => $this->input->post("kd_tipepkb"),
                'kd_jenispit' => $this->input->post("kd_jenispit"),
                'foto_konsumen' => $this->input->post("foto_konsumen"),
                'dokumen' => $this->input->post("dokumen"),
                'estimasi_pendaftaran' => $this->input->post("estimasi_pendaftaran"),
                'estimasi_pengerjaan' => $this->input->post("estimasi_pengerjaan"),
                'estimasi_selesai' => $this->input->post("estimasi_selesai"),
                'hasil_analisa_sa' => $this->input->post("hasil_analisa_sa"),
                'kebutuhan_konsumen' => $this->input->post("kebutuhan_konsumen"),
                'saran_mekanik' => $this->input->post("saran_mekanik"),
                'km_saatini' => $this->input->post("km_saatini"),
                'kd_pekerjaan' => $this->input->post("kd_pekerjaan"),
                'part_number' => $this->input->post("part_number"),
                'total_frt' => $this->input->post("total_frt"),
                'amount' => $this->input->post("amount"),
                'no_pit' => $this->input->post("no_pit"),
                'kd_typeservice' => $this->input->post("kd_typeservice"),
                'kd_setuppembayaran' => $this->input->post("kd_setuppembayaran"),
                'catatan_tambahan' => $this->input->post("catatan_tambahan"),
                'bensin_saatini' => $this->input->post("bensin_saatini"),
                'konfirmasi_pekerjaantambahan' => $this->input->post("konfirmasi_pekerjaantambahan"),
                'no_buku' => $this->input->post("no_buku"),
                'status_sa' => $this->input->post("status_sa"),
                'no_stnk' => $this->input->post("no_stnk"),
                'nama_pemilik' => $this->input->post("nama_pemilik"),
                'no_hp' => $this->input->post("no_hp"),
                'alamat' => $this->input->post("alamat"),
                'nama_comingcustomer' => $this->input->post("nama_comingcustomer"),
                'kd_maindealer' => $this->session->userdata('kd_maindealer'),
                'kd_dealer' => $this->session->userdata('kd_dealer'),
                'kd_lokasidealer' => $this->input->post('kd_lokasidealer'),
                'row_status' => 0,
                'lastmodified_by' => $this->session->userdata('user_id')
            );


            $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/csa", $param, array(CURLOPT_BUFFERSIZE => 10));
            $data = json_decode($hasil);
            $this->session->set_flashdata('tr-active', $data->message);

            $this->data_output($hasil, 'put', base_url('customer_service/service_advisor_list'));
        }
    }

    public function delete_service_advisor($id) {

        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/transaksi/csa", $param));
        $string = link_pagination();
        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'location' => base_url('customer_service/service_advisor_list')
            );

            $this->output->set_output(json_encode($data_status));
        } else {
            $data_status = array(
                'status' => false,
                'message' => 'Data gagal dihapus',
            );

            $this->output->set_output(json_encode($data_status));
        }
    }

    public function wilayah_customer($tipe)
    {
        if($tipe == 'propinsi'){
            $param = array(
                'field' => 'KD_PROPINSI AS KODE, NAMA_PROPINSI AS NAMA', 
            );

            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi", $param));
        }
        elseif($tipe == 'kabupaten'){
            $param = array(
                'custom' => "KD_PROPINSI = ".$this->input->get('kd_propinsi'), 
                'field' => 'KD_KABUPATEN AS KODE, NAMA_KABUPATEN AS NAMA', 
            );

            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kabupaten", $param));
        }
        elseif($tipe == 'kecamatan'){
            $param = array(
                'custom' => "KD_KABUPATEN = ".$this->input->get('kd_kabupaten'), 
                'field' => 'KD_KECAMATAN AS KODE, NAMA_KECAMATAN AS NAMA', 
            );
            
            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kecamatan", $param));
        }
        else{
            $param = array(
                'custom' => "KD_KECAMATAN = ".$this->input->get('kd_kecamatan'), 
                'field' => 'KD_DESA AS KODE, NAMA_DESA AS NAMA', 
            );

            $data = json_decode($this->curl->simple_get(API_URL . "/api/master_general/desa", $param));
        }

        if($data && is_array($data->message)){
            $result = $data->message;
        }
        else{
            $data = array(array(
                'KODE' => '', 
                'NAMA' => ''
            ));

            $result = json_decode(json_encode($data));

        }
        $this->output->set_output(json_encode($result));

        // $this->output->set_output(json_encode($data));
    }

    public function serviceadvisor_typeahead() {
         $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/csa")); //,$param

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_POLISI;
            $data_message[1][$key] = $message->NO_MESIN;
            $data_message[2][$key] = $message->KD_CUSTOMER;
        }


        $result['keyword'] = array_merge($data_message[0], $data_message[1], $data_message[2]);

        $this->output->set_output(json_encode($result));
    }


    public function cek_kpb()
    {
        $kpb = 'NONKPB';
        // $this->output->set_output(json_encode($list));    
        $param = array(
            "no_mesin" => substr($this->input->get('no_mesin'),0,5)
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_service/kpb", $param));
        if($data && (is_array($data->message) || is_object($data->message))):
            $km_motor = str_replace(".", "", $this->input->get('km_motor'));
            // $kbp_data = $list->JENIS_KPB != 'NONKPB'?(int)substr($list->JENIS_KPB,3,1):0;
            $jml_bln=strtotime(date('Y/m/d H:i:s')) - strtotime($this->input->get('tgl_terima'));
            $bln = round(($jml_bln / (60 * 60 * 24))/30);
            /*if($kbp_data = 1):
                $kpb_row = 2;
            elseif($kbp_data = 2):
                $kpb_row = 3;
            elseif($kbp_data = 3):
                $kpb_row = 4;
            else:
                $kpb_row = 0;
            endif;*/
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
            $kbp_array = array($km_row, $bln_row);
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
        
        $this->output->set_output(json_encode($kpb));
        // return $kpb;  
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
                        'message' => "Data berhasil simpan",
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

}
