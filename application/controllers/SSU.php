<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SSU extends CI_Controller {
     var $API="";
    /**
     * { function_description }
     */
    public function __construct(){
            parent::__construct();
            $this->load->library('form_validation');
            $this->load->library('session');
            $this->load->library('curl');
            $this->load->library('pagination');
            $this->load->helper('form');
            $this->load->helper('url'); 
            $this->load->helper('zetro'); 
            $this->load->helper('file');
    }
    public function transaksi_ssu(){
        $data = array();
        $dealerd =$this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $param = array(
            'kd_dealer' => $dealerd,
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'NO_TRANS, NAMA_FILE,KD_DEALER',
            'groupby' => TRUE,
            'orderby' => 'NO_TRANS DESC'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/ssu", $param));
        if($data["list"]){
            if($data["list"]->totaldata >0){
                foreach ($data["list"]->message as $key => $value) {
                    $param_detail = array(
                        'kd_dealer' => $value->KD_DEALER,
                        'no_trans'  => $value->NO_TRANS,
                        'nama_file'  => $value->NAMA_FILE
                    );
                    $data["detail"][$value->NO_TRANS] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/ssu/true", $param_detail));
                }
            }
        }
        // var_dump($data["list"]);exit();
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
        // $this->output->set_output(json_encode($data["detail"]));
        $this->template->site('sales/list_ssu', $data);
    }
    public function edit_ssu($id,$debug=null) {
        $this->auth->validate_authen('ssu/transaksi_ssu');
        $data = array();
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'no_mesin' => $id
        );
        $data["udstk"]          = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/datassu/udstk_d", $param));
        $data["udprg"]          = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/datassu/udprg_d", $param));
        $param["jointable"]     = array(array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER = TRANS_SSU_DETAIL_CDDB.KD_CUSTOMER","LEFT"));
        $param["field"]         ="TRANS_SSU_DETAIL_CDDB.*,MC.NAMA_CUSTOMER";
        $data["cddb"]           = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/datassu/cddb_d", $param));
        $data["kelamin"]        = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jeniskelamin"));
        $data["dealer"]         = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["propinsi"]       = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
        $data["agamas"]         = json_decode($this->curl->simple_get(API_URL . "/api/master_general/agama"));
        $data["pekerjaans"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pekerjaan"));
        $data["pengeluaran"]    = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pengeluaran"));
        $data["pendidikans"]    = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pendidikanlevel"));
        $data["hobbyne"]        = json_decode($this->curl->simple_get(API_URL . "/api/master_general/hobby"));
        $data["jenise_motor"]   = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jenismotor"));
        $data["merke_motor"]    = json_decode($this->curl->simple_get(API_URL . "/api/master_general/merkmotor"));
        $data["penggunane"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pengguna"));
        $data["kegunaane"]      = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kegunaan"));
        if($debug){
            print_r($data["udstk"]);echo "<br>";
            print_r($data["udprg"]);echo "<br>";
            print_r($data["cddb"]);
        }else{
            $this->template->site('sales/edit_ssu_new', $data);
        }
    }
    function update_newssu(){
        $hasil=array();
        $hasil = $this->update_udstk();
        $hasil = $this->update_cddb();
        $hasil = $this->update_udprg();
        $this->data_output($hasil,'put');
    }
    public function generate_ssu($reload=null){
        ini_set('max_execution_time',600);
        $data=array();$result=null;$no_mesine=array();
        $defaultDealer=$this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $param=array(
            'kd_dealer' => $defaultDealer,
            'orderby'   => 'NO_MESIN',

        );
        $no_trans ="";$hasil=array();
        $KD_MAINDEALER = $this->session->userdata("kd_maindealer");
        $KD_DEALERAHM = KodeDealerAHM($defaultDealer);
        $namafile =$KD_MAINDEALER . "-" . $KD_DEALERAHM . "-" . date('YmdHis') ;
        //download for udstk
        if($reload){
            //hapus semua data yang no trans nya sama
            $paramd = array(
                'no_trans'=>$this->input->get("n"),
                'field' => "NO_MESIN",
                'groupby' => TRUE
            );
            $datax= json_decode($this->curl->simple_get(API_URL . "/api/transaksi/ssu", $paramd));
            if($datax){
                if($datax->totaldata >0){
                    foreach ($datax->message as $key => $value) {
                        array_push($no_mesine, $value->NO_MESIN);
                    }
                }
            }
            $paramd["no_mesin"]="0";
            unset($paramd["field"]);
            unset($paramd["groupby"]);
            $dataxx=($this->curl->simple_delete(API_URL . "/api/transaksi/ssu/1", $paramd, array(CURLOPT_BUFFERSIZE => 10)));
            
        }
        $no_trans =($reload)?$this->input->get("n"): $this->autogenerate_trans('FL');
        if($reload){
            unset($param["no_mesin"]);
            $param["where_in"] = $no_mesine;
            $param["where_in_field"] ="NO_MESIN";
        }
        $data["udstk"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/datassu/UDSTK", $param));
        
        if($data['udstk']){
            $n=0;
            if($data["udstk"]->totaldata > 0){
                $dx = new ArrayObject($data["udstk"]->message);
                $iterator = $dx->getIterator();
                // foreach ( new LimitIterator($iterator,0,50) as $key => $value) {
                foreach ($data["udstk"]->message as $key => $value) {
                    $paramx=array();
                    $paramx["kd_dealer"] = $value->KD_DEALER;
                    $paramx["no_trans"]  = $no_trans;
                    $paramx["no_mesin"]  = $value->NO_MESIN;
                    $paramx["nama_file"] = $namafile;
                    $paramx["type_file"] = "UDSTK";
                    $paramx["status_download"]   = "1";
                    $paramx["tgl_download"]      = date("d/m/Y H:i:s");
                    $paramx["created_by"]= $this->session->userdata("user_id");
                    $result = ($this->curl->simple_post(API_URL . "/api/transaksi/ssu", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
                    /*print_r($paramx);
                    var_dump($result);*/
                    $hasil= json_decode($result);
                    if($hasil){
                        if($hasil->recordexists){
                            $paramx["lastmodified_by"] = $this->session->userdata("user_id");
                            $hasil = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
                        }
                    }
                }
                $this->simpan_udstk($data["udstk"]);
                
                //download for cddb
                $hasil=array();
                $data["cddb"]  = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/datassu/CDDB", $param));
                if($data["cddb"]){
                    if($data["cddb"]->totaldata >0){
                        $dxc = new ArrayObject($data["cddb"]->message);
                        $iteratorc = $dxc->getIterator();
                        // foreach ( new LimitIterator($iteratorc,0,50) as $key => $value) {
                        foreach ($data["cddb"]->message as $key => $value) {
                            $paramx=array();
                            $paramx["kd_dealer"] = $value->KD_DEALER;
                            $paramx["no_trans"]  = $no_trans;
                            $paramx["no_mesin"]  = $value->NO_MESIN;
                            $paramx["nama_file"] = $namafile;
                            $paramx["type_file"] = "CDDB";
                            $paramx["status_download"]   = "1";
                            $paramx["tgl_download"]      = date("d/m/Y H:i:s");;
                            $paramx["created_by"]= $this->session->userdata("user_id");
                            $result = ($this->curl->simple_post(API_URL . "/api/transaksi/ssu", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
                            $hasil= json_decode($result);
                            if($hasil){
                                if($hasil->recordexists){
                                    $paramx["lastmodified_by"] = $this->session->userdata("user_id");
                                    $hasil = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
                                }
                            }
                        }
                        $this->simpan_cddb($data["cddb"]);
                    }

                }
                //download for udprg
                $hasil=array();
                $data["udprg"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/datassu/UDPRG", $param));
                if($data["udprg"]){
                    if($data["udprg"]->totaldata >0){
                        $dxb = new ArrayObject($data["udprg"]->message);
                        $iteratorb = $dxb->getIterator();
                        // foreach ( new LimitIterator($iteratorb,0,50) as $key => $value) {
                        foreach ($data["udprg"]->message as $key => $value) {
                            $paramx=array();
                            $paramx["kd_dealer"] = $value->KD_DEALER;
                            $paramx["no_trans"]  = $no_trans;
                            $paramx["no_mesin"]  = $value->NO_MESIN;
                            $paramx["nama_file"] = $namafile;
                            $paramx["type_file"] = "UDPRG";
                            $paramx["status_download"]   = "1";
                            $paramx["tgl_download"]      = date("d/m/Y H:i:s");;
                            $paramx["created_by"]= $this->session->userdata("user_id");
                            $result = ($this->curl->simple_post(API_URL . "/api/transaksi/ssu", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
                            $hasil= json_decode($result);
                            if($hasil){
                                if($hasil->recordexists){
                                    $paramx["lastmodified_by"] = $this->session->userdata("user_id");
                                    $hasil = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
                                }
                            }
                        }
                        $this->simpan_udprg($data["udprg"]);
                    }
                }
            }
        }
        //exit();
        $this->data_output($result,'post');
    }
    function simpan_udstk($datane=null){
        $data=array();$param=array();
        if($datane){
            if($datane->totaldata >0){
                $dx = new ArrayObject($datane->message);
                $iterator = $dx->getIterator();
                // foreach (new LimitIterator($iterator,0,50) as $key => $value) {
                foreach ($datane->message as $key => $value) {
                    $param=array();
                    $param["spk_id"]    = $value->SPK_ID;
                    $param["status_spk"]= $value->STATUS_SPK;
                    $param["kd_dealer"] = $value->KD_DEALER;
                    $param["no_rangka"] = $value->NO_RANGKA;
                    $param["no_mesin1"] = $value->NO_MESIN1;
                    $param["no_mesin2"] = $value->NO_MESIN2;
                    $param["no_mesin"]  = $value->NO_MESIN;
                    $param["nama_bpkb"] = $value->NAMA_BPKB;
                    $param["alamat_bpkb"]= $value->ALAMAT_BPKB;
                    $param["nama_kelurahan"]= $value->NAMA_KELURAHAN;
                    $param["nama_kecamatan"]= $value->NAMA_KECAMATAN;
                    $param["kd_kabupaten"]= $value->KD_KABUPATEN;
                    $param["kode_pos"]= $value->KODE_POS;
                    $param["kd_propinsi"]= $value->KD_PROPINSI;
                    $param["jenis_pembelian"]= $value->JENIS_PEMBELIAN;
                    $param["kd_leasing"]= $value->KD_LEASING;
                    $param["uang_muka"]= $value->UANG_MUKA;
                    $param["jangka_waktu"]= $value->JANGKA_WAKTU;
                    $param["honda_id"]= $value->HONDA_ID;
                    $param["jml_angsuran"]= $value->JUMLAH_ANGSURAN;
                    $param["kd_posahm"]= $value->KD_POSAHM;
                    $param["ktp_bpkb"]= $value->KTP_BPKB;
                    $param["email_bpkb"]= $value->EMAIL_BPKB;
                    $param["created_by"] = $this->session->userdata("user_id");

                    $data = json_decode($this->curl->simple_post(API_URL . "/api/transaksi/ssu_udstk", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    if($data){
                        if($data->recordexists){
                            $param["lastmodified_by"] = $this->session->userdata("user_id");
                            $data = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu_udstk", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        }
                    }
                    // if($value->NO_MESIN=='JBK3E1272905'){
                    //     //print_r($param);
                    //    var_dump($data);
                    // }
                    /*;
                    exit();*/
                }
            }
        }
        return $data;
    }
    function simpan_cddb($datane=null){
        $data=array();
        //var_dump($datane);
        //exit();
        if($datane){
            if($datane->totaldata > 0){
                $dx = new ArrayObject($datane->message);
                $iterator = $dx->getIterator();
                // foreach (new LimitIterator($iterator,0,50) as $key => $value) {
                foreach ($datane->message as $key => $value) {
                    $param=array();
                    $param["spk_id"]= $value->SPK_ID;
                    $param["status_spk"]= $value->STATUS_SPK;
                    $param["kd_dealer"]= $value->KD_DEALER;
                    $param["no_rangka"]= $value->NO_RANGKA;
                    $param["no_mesin1"]= $value->NO_MESIN1;
                    $param["no_mesin2"]= $value->NO_MESIN2;
                    $param["no_mesin"]= $value->NO_MESIN;
                    $param["no_ktp"]= $value->NO_KTP;
                    $param["kd_customer"]= $value->KD_CUSTOMER;
                    $param["jenis_kelamin"]= $value->JENIS_KELAMIN;
                    $param["tgl_lahir"]= $value->TGL_LAHIR;
                    $param["alamat_surat"]= $value->ALAMAT_SURAT;
                    $param["kd_desa"]= $value->KD_DESA;
                    $param["nama_desa"]= $value->NAMA_DESA;
                    $param["nama_kecamatan"]= $value->NAMA_KECAMATAN;
                    $param["kd_kecamatan"]= $value->KD_KECAMATAN;
                    $param["kd_kota"]= $value->KD_KOTA;
                    $param["kd_propinsi"]= $value->KD_PROPINSI;
                    $param["kd_agama"]= $value->KD_AGAMA;
                    $param["email"]= $value->EMAIL;
                    $param["status_rumah"]= $value->STATUS_RUMAH;
                    $param["status_hp"]= $value->STATUS_HP;
                    $param["status_dihubungi"]= $value->STATUS_DIHUBUNGI;
                    $param["akun_fb"]= $value->AKUN_FB;
                    $param["twitter"]= $value->TWITTER;
                    $param["instagram"]= $value->INSTAGRAM;
                    $param["youtube"]= $value->YOUTUBE;
                    $param["hobi"]= $value->HOBI;
                    $param["keterangan"]= $value->KETERANGAN;
                    $param["kartu_keluarga"]= $value->KARTU_KELUARGA;
                    $param["wni"]= $value->WNI;
                    $param["reff_id"]= $value->REFF_ID;
                    $param["robb_id"]= $value->ROBB_ID;
                    $param["pekerjaan"]= $value->PEKERJAAN;
                    $param["pengeluaran"]= $value->PENGELUARAN;
                    $param["pendidikan"]= $value->PENDIDIKAN;
                    $param["pic_perusahaan"]= $value->PIC_PERUSAHAAN;
                    $param["no_hp"]= $value->NO_HP;
                    $param["no_telp"]= $value->NO_TELP;
                    $param["informasi_baru"]= $value->INFORMASI_BARU;
                    $param["merk_motor"]= $value->MERK_MOTOR;
                    $param["jenis_motor"]= $value->JENIS_MOTOR;
                    $param["digunakan_untuk"]= $value->DIGUNAKAN_UNTUK;
                    $param["yang_menggunakan"]= $value->YANG_MENGGUNAKAN;
                    $param["kd_sales"]= $value->KD_SALES;
                    $param["kode_pos"]= $value->KODE_POS;
                    $param["created_by"] = $this->session->userdata("user_id");
                    $data = json_decode($this->curl->simple_post(API_URL . "/api/transaksi/ssu_cddb", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    
                    if($data){
                        if($data->recordexists){
                            $param["lastmodified_by"] = $this->session->userdata("user_id");
                            $data = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu_cddb", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        }
                    }
                    // if($value->NO_MESIN=='JBK3E1272905'){
                    // print_r($param);
                    // var_dump($data);
                    // }
                }
            }
        }
        //exit();
        return $data;
    }
    function simpan_udprg($datane=null){
        $data=array();
        if($datane){
            if($datane->totaldata >0){
                $dx = new ArrayObject($datane->message);
                $iterator = $dx->getIterator();
                // foreach (new LimitIterator($iterator,0,50) as $key => $value) {
                foreach ($datane->message as $key => $value) {
                    $param =array();
                    $param["spk_id"]= $value->SPK_ID;
                    $param["status_spk"]= $value->STATUS_SPK;
                    $param["kd_dealer"]= $value->KD_DEALER;
                    $param["no_rangka"]= $value->NO_RANGKA;
                    $param["no_mesin"]= $value->NO_MESIN;
                    $param["kd_leasing"]= $value->KD_LEASING;
                    $param["kd_salesprogram"]= $value->KD_SALESPROGRAM;
                    $param["telp_kosong"]= $value->TELP_KOSONG;
                    $param["hp_kosong"]= $value->HP_KOSONG;
                    $param["tgl_beli"]= $value->TGL_BELI;
                    $param["kd_salesprogramahm"]= $value->KD_SALESPROGRAMAHM;
                    $param["lokal_sp"]= $value->LOKAL_SP;
                    $param["uang_muka"]= $value->UANG_MUKA;
                    $param["jenis_beli"]= $value->JENIS_BELI;
                    $param["asal_jual"]= $value->ASAL_JUAL;
                    $param["kd_lokasi"]= $value->KD_LOKASI;
                    $param["sales_force"]= $value->SALES_FORCE;
                    $param["kd_desa"]= $value->KD_DESA;
                    $param["kd_kecamatan"]= $value->KD_KECAMATAN;
                    $param["dp_setor"]= $value->DP_SETOR;
                    $param["susb_ahm"]= $value->SUSB_AHM;
                    $param["sub_md"]= $value->SUB_MD;
                    $param["sub_dlr"]= $value->SUB_DLR;
                    $param["sub_fin"]= $value->SUB_FIN;
                    $param["split_otr"]= $value->SPLIT_OTR;
                    $param["ro"]= $value->RO;
                    $param["ro_mesin"]= $value->RO_MESIN;
                    $param["jenis_customer"]= $value->JENIS_CUSTOMER;
                    $param["of_tr"]= $value->OF_TR;
                    $param["kelurahan_surat"]= $value->KELURAHAN_SURAT;
                    $param["kecamatan_surat"]= $value->KECAMATAN_SURAT;
                    $param["jml_angsuran"]= $value->JML_ANGSURAN;
                    $param["created_by"] = $this->session->userdata("user_id");
                    $data = json_decode($this->curl->simple_post(API_URL . "/api/transaksi/ssu_udprg", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    if($data){
                        if($data->recordexists){
                            $param["lastmodified_by"] = $this->session->userdata("user_id");
                            $data = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu_udprg", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        }
                    }
                    // if($value->NO_MESIN=='JBK3E1272905'){
                    //     var_dump($data);
                        
                    // }
                    // print_r($param);
                    // exit();
                }
            }
        }
        return $data;
    }
    function update_udstk(){
        $data=array();$param=array();
        $param["spk_id"]    = $this->input->post("spk_id");
        $param["status_spk"]= $this->input->post("status_spk");
        $param["kd_dealer"] = $this->input->post("kd_dealer");
        $param["no_rangka"] = $this->input->post("no_rangka");
        $param["no_mesin1"] = $this->input->post("no_mesin1");
        $param["no_mesin2"] = $this->input->post("no_mesin2");
        $param["no_mesin"]  = $this->input->post("no_mesin");
        $param["nama_bpkb"] = $this->input->post("nama_udstk");
        $param["alamat_bpkb"]= $this->input->post("alamat_udstk");
        $param["nama_kelurahan"]= $this->input->post("kd_desa_udstk");
        $param["nama_kecamatan"]= $this->input->post("kd_kecamatan_udstk");
        $param["kd_kabupaten"]= $this->input->post("kd_kabupaten_udstk");
        $param["kode_pos"]= $this->input->post("kode_pos_udstk");
        $param["kd_propinsi"]= $this->input->post("kd_propinsi_udstk");
        $param["jenis_pembelian"]= $this->input->post("jenis_beli_udstk");
        $param["kd_leasing"]= $this->input->post("kd_leasing_udstk");
        $param["uang_muka"]= $this->input->post("uang_muka_udstk");
        $param["jangka_waktu"]= $this->input->post("jangka_waktu");
        $param["honda_id"]= $this->input->post("hondaid_udstk");
        $param["jml_angsuran"]= $this->input->post("jml_angsuran_udstk");
        $param["kd_posahm"]= $this->input->post("kd_posahm_udstk");
        $param["ktp_bpkb"]= $this->input->post("ktp_bpkb_udstk");
        $param["email_bpkb"]= $this->input->post("email_udstk");
        $param["created_by"] = $this->session->userdata("user_id");
        $data = json_decode($this->curl->simple_post(API_URL . "/api/transaksi/ssu_udstk", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if($data){
            if($data->recordexists){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $data = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu_udstk", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        return $data;
    }
    function update_cddb(){
        $data=array();$param=array();
        $param["spk_id"]= $this->input->post("spk_id");
        $param["status_spk"]= $this->input->post("status_spk");
        $param["kd_dealer"]= $this->input->post("kd_dealer");
        $param["no_rangka"]= $this->input->post("no_rangka");
        $param["no_mesin1"]= $this->input->post("no_mesin1");
        $param["no_mesin2"]= $this->input->post("no_mesin2");
        $param["no_mesin"]= $this->input->post("no_mesin");
        $param["no_ktp"]= $this->input->post("nomor_ktp_cddb");
        $param["kd_customer"]= $this->input->post("kd_customer");
        $param["jenis_kelamin"]= $this->input->post("kd_jeniskelamin_cddb");
        $param["tgl_lahir"]= str_replace("/","",$this->input->post("tgl_lahir_cddb"));
        $param["alamat_surat"]= $this->input->post("alamat_cddb");
        $param["kd_desa"]= $this->input->post("kd_desa");
        $param["nama_desa"]= $this->input->post("kd_desa_cddb");
        $param["nama_kecamatan"]= $this->input->post("kd_kecamatan_cddb");
        $param["kd_kecamatan"]= $this->input->post("kd_kecamatan");
        $param["kd_kota"]= $this->input->post("kd_kabupaten_cddb");
        $param["kd_propinsi"]= $this->input->post("kd_propinsi_cddb");
        $param["kode_pos"]= $this->input->post("kode_pos_cddb");
        $param["kd_agama"]= $this->input->post("kd_agama_cddb");
        $param["email"]= $this->input->post("email_customer_cddb");
        $param["status_rumah"]= $this->input->post("status_rumah_cddb");
        $param["status_hp"]= $this->input->post("status_hp_cddb");
        $param["status_dihubungi"]= $this->input->post("status_dihubungi");
        $param["akun_fb"]= $this->input->post("kd_facebook_cddb");
        $param["twitter"]= $this->input->post("kd_twiter_cddb");
        $param["instagram"]= $this->input->post("kd_instagram_cddb");
        $param["youtube"]= $this->input->post("kd_youtube_cddb");
        $param["hobi"]= $this->input->post("kd_hobby_cddb");
        $param["keterangan"]= $this->input->post("keterangan_cddb");
        $param["kartu_keluarga"]= $this->input->post("kartu_keluarga_ccdb");
        $param["wni"]= $this->input->post("kebangsaan_cddb");
        $param["reff_id"]= $this->input->post("reff_id_cddb");
        $param["robb_id"]= $this->input->post("robb_id_cddb");
        $param["pekerjaan"]= $this->input->post("pekerjaan_cddb");
        $param["pengeluaran"]= $this->input->post("pengeluaran_cddb");
        $param["pendidikan"]= $this->input->post("pendidikan_cddb");
        $param["pic_perusahaan"]= $this->input->post("pic_perusahaan_cddb");
        $param["no_hp"]= $this->input->post("no_hp_cddb");
        $param["no_telp"]= $this->input->post("no_telp_cddb");
        $param["informasi_baru"]= $this->input->post("siap_dihubungi_cddb");
        $param["merk_motor"]= $this->input->post("merk_motor_cddb");
        $param["jenis_motor"]= $this->input->post("jenis_motor_cddb");
        $param["digunakan_untuk"]= $this->input->post("digunakan_untuk_cddb");
        $param["yang_menggunakan"]= $this->input->post("yang_menggunakan_cddb");
        $param["kd_sales"]= $this->input->post("kd_salesAhm_cddb");
        $param["row_status"]= "0";
        $param["created_by"] = $this->session->userdata("user_id");
        $data = json_decode($this->curl->simple_post(API_URL . "/api/transaksi/ssu_cddb", $param, array(CURLOPT_BUFFERSIZE => 10)));
        //print_r($param);exit();
        if($data){
            if($data->recordexists){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $data = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu_cddb", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        return $data;
    }
    function update_udprg(){
        $data=array();$param=array();
        $param["spk_id"]= $this->input->post("spk_id");
        $param["status_spk"]= $this->input->post("status_spk");
        $param["kd_dealer"]= $this->input->post("kd_dealer");
        $param["no_rangka"]= $this->input->post("no_rangka");
        $param["no_mesin"]= $this->input->post("no_mesin");
        $param["kd_leasing"]= $this->input->post("kd_leasing_udprg");
        $param["kd_salesprogram"]= $this->input->post("kd_salesprogram_udprg");
        $param["telp_kosong"]= $this->input->post("alasan_telp_kosong_udprg");
        $param["hp_kosong"]= $this->input->post("alasan_hp_kosong_udprg");
        $param["tgl_beli"]= str_replace("/","",$this->input->post("tgl_beli_udprg"));
        $param["kd_salesprogramahm"]= $this->input->post("sp_ahm_udprg");
        $param["lokal_sp"]= $this->input->post("sp_dlr_udprg");
        $param["uang_muka"]= $this->input->post("uang_muka_udprg");
        $param["jenis_beli"]= $this->input->post("jenis_beli_udprg");
        $param["asal_jual"]= $this->input->post("asal_jual_udprg");
        $param["kd_lokasi"]= $this->input->post("kd_lokasi_udprg");
        $param["sales_force"]= $this->input->post("sales_force_udprg");
        $param["kd_desa"]= $this->input->post("kd_desa_udprg");
        $param["kd_kecamatan"]= $this->input->post("kd_kecamatan_udprg");
        $param["dp_setor"]= $this->input->post("dp_setor_udprg");
        $param["susb_ahm"]= $this->input->post("sub_ahm_udprg");
        $param["sub_md"]= $this->input->post("sub_md_udprg");
        $param["sub_dlr"]= $this->input->post("sub_dealer_udprg");
        $param["sub_fin"]= $this->input->post("sub_fin_udprg");
        $param["split_otr"]= $this->input->post("split_otr_udprg");
        $param["ro"]= $this->input->post("ro_udprg");
        $param["ro_mesin"]= $this->input->post("ro_mesin_udprg");
        $param["jenis_customer"]= $this->input->post("jenis_cust_udprg");
        $param["of_tr"]= $this->input->post("of_tr_udprg");
        $param["kelurahan_surat"]= $this->input->post("kd_desa_surat_udprg");
        $param["kecamatan_surat"]= $this->input->post("kd_kecamatan_surat_udprg");
        $param["jml_angsuran"]= $this->input->post("jml_angsuran_udprg");
        $param["row_status"]= "0";
        $param["created_by"] = $this->session->userdata("user_id");
        $data = json_decode($this->curl->simple_post(API_URL . "/api/transaksi/ssu_udprg", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if($data){
            if($data->recordexists){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $data = json_decode($this->curl->simple_put(API_URL . "/api/transaksi/ssu_udprg", $param, array(CURLOPT_BUFFERSIZE => 10)));
                /*var_dump($data);
                exit();*/
            }
        }
        return $data;
    }
    public function pull_ssu(){
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'jointable' =>array(
                        array('MASTER_JENISMOTOR MJM','MJM.KD_JENIS_MOTOR=TRANS_FILE_UDPRG_VIEW.JENIS_MOTOR','LEFT'),
                        array('MASTER_KEGUNAAN MK','MK.KEGUNAAN=TRANS_FILE_UDPRG_VIEW.DIGUNAKAN_UNTUK','LEFT'),
                        array('MASTER_MERKMOTOR MM','MM.MERK_MOTOR=TRANS_FILE_UDPRG_VIEW.MERK_MOTOR','LEFT'),
                        array('MASTER_PENGGUNA MPG','MPG.PENGGUNA=TRANS_FILE_UDPRG_VIEW.YANG_MENGGUNAKAN','LEFT')
                    ),
            'custom' => "NO_RANGKA NOT IN (SELECT P.NO_RANGKA FROM TRANS_FILE AS P WHERE P.ROW_STATUS >= 0 AND P.NO_RANGKA IS NOT NULL)",
            'field' => "TRANS_FILE_UDPRG_VIEW.*, MJM.ID AS ID_JENIS_MOTOR, MK.ID AS ID_KEGUNAAN, MM.ID AS ID_MERKMOTOR, MPG.ID AS ID_PENGGUNA"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/file_udprg", $param, array(CURLOPT_BUFFERSIZE => 10)));
        // $this->output->set_output(json_encode($data));
        // var_dump($data);exit;
        if ($data && (is_array($data->message) || is_object($data->message))) {
            $no_trans = $this->autogenerate_trans('FL');
            foreach ($data->message as $key => $value) {
                $param_post = array(
                    'no_trans' => $no_trans,
                    'tgl_trans' => date('d/m/Y'),
                    'directory_file' => NULL,
                    'spk_id' => $value->SPK_ID,
                    'kd_dealerahm' => $value->KD_DEALERAHM,
                    'kd_dealer' => $value->KD_DEALER,
                    'kd_maindealer' => $value->KD_MAINDEALER,
                    'no_mesin' => $value->NO_MESIN,
                    'no_rangka' => $value->NO_RANGKA,
                    'jenis_penjualan' => $value->JENIS_PENJUALAN,
                    'type_penjualan' => $value->TYPE_PENJUALAN,
                    'kd_typemotor' => $value->KD_TYPEMOTOR,
                    'kd_item' => $value->KD_ITEM,
                    'nama_item' => $value->NAMA_ITEM,
                    'kd_fincoy' => $value->KD_FINCOY,
                    'uang_muka' => $value->UANG_MUKA ? $value->UANG_MUKA:0 ,
                    'jangka_waktu' => $value->JANGKA_WAKTU ? $value->JANGKA_WAKTU:0,
                    'jumlah_angsuran' => $value->JUMLAH_ANGSURAN ? $value->JUMLAH_ANGSURAN:0 ,
                    'kd_customer' => $value->KD_CUSTOMER,
                    'nama_customer' => $value->NAMA_CUSTOMER,
                    'jenis_kelamin' => $value->JENIS_KELAMIN,
                    'tgl_lahir' => tglFromSql($value->TGL_LAHIR),
                    'tgl_pembuatan_npwp' => tglFromSql($value->TGL_PEMBUATAN_NPWP),
                    'no_ktp' => $value->NO_KTP,
                    'no_npwp' => $value->NO_NPWP,
                    'alamat_surat' => $value->ALAMAT_SURAT,
                    'kd_kecamatan' => $value->KD_KECAMATAN,
                    'kode_pos' => $value->KODE_POS,
                    'kd_propinsi' => $value->KD_PROPINSI,
                    'kd_agama' => $value->KD_AGAMA,
                    'kd_pekerjaan' => $value->PEKERJAAN,
                    'pengeluaran' => $value->PENGELUARAN, // (updated)
                    'kd_pendidikan' => $value->KD_PENDIDIKAN,
                    'nama_penanggungjawab' => $value->NAMA_PENANGGUNGJAWAB,
                    'no_hp' => $value->NO_HP,
                    'no_telepon' => $value->NO_TELEPON,
                    'status_dihubungi' => $value->STATUS_DIHUBUNGI,
                    'email' => $value->EMAIL,
                    'status_rumah' => $value->STATUS_RUMAH,
                    'status_nohp' => $value->KD_STATUS_HP,
                    'akun_fb' => $value->AKUN_FB,
                    'akun_twitter' => $value->AKUN_TWITTER,
                    'akun_instagram' => $value->AKUN_INSTAGRAM,
                    'akun_youtube' => $value->AKUN_YOUTUBE,
                    'hobi' => $value->HOBI,
                    'karakteristik_konsumen' => $value->KARAKTERISTIK_KONSUMEN,
                    'id_refferal' => $value->ID_REFFERAL,
                    'nama_propinsi' => $value->NAMA_PROPINSI, //X
                    'kd_kabupaten' => $value->KD_KOTA, //X KD_KOTA
                    'nama_kabupaten' => $value->NAMA_KABUPATEN, //X
                    'nama_kecamatan' => $value->NAMA_KECAMATAN, //X
                    'kd_desa' => $value->KELURAHAN, //X KELURAHAN
                    'nama_desa' => $value->NAMA_DESA, //X
                    'alamat' => $value->ALAMAT_SURAT, //X ALAMAT_SURAT
                    'nama_sales' => $value->NAMA_SALES,
                    'finance_company' => $value->KD_FINCOY,
                    'program_id' => $value->KD_SALESPROGRAM,
                    'reason_tel' => NULL,
                    'reason_hp' => NULL,
                    'tgl_jual' => tglFromSql($value->TGL_JUAL),
                    'sp_id' => $value->KD_SALESPROGRAMAHM,
                    'lclsp_id' => $value->KD_SALESPROGRAM,
                    'jenis_bayar' => $value->TYPE_PENJUALAN,
                    'asal_jual' => $value->LOK_PENJUALAN,
                    'kd_dlrpos' => NULL,
                    'jenis_slsforce' => $value->GROUP_SALES,
                    'dp_setor' => $value->HARGA,
                    's_ahm' => $value->S_AHM ? $value->S_AHM : '0',
                    's_md' => $value->S_MD ? $value->S_MD : '0',
                    's_sd' => $value->S_SD ? $value->S_SD : '0',
                    's_finance' => $value->S_FINANCE ? $value->S_FINANCE : '0',
                    'split_otr' => (strlen($value->SPLIT_OTR)>0) ? $value->SPLIT_OTR : '0',
                    'ro' => $value->RO,
                    'j_cust' => $value->JENIS_CUSTOMER, //JENIS CUSTOMER W,E,C (updated)
                    'informasi' => $value->INFORMASI,
                    'merk_motor' => $value->ID_MERKMOTOR, // (updated)
                    'jenis_motor' => $value->ID_JENIS_MOTOR, // (updated)
                    'digunakan_untuk' => $value->ID_KEGUNAAN, // (updated)
                    'yang_menggunakan' => $value->ID_PENGGUNA, // (updated)
                    'hondaid_sales' => $value->KD_HSALES, // (updated)
                    'group_customer' => $value->GROUP_CUSTOMER, // (updated)
                    'no_mesinro' => $value->NO_MESIN_RO, // (updated)
                    'pos_pengiriman' => '', // (updated)
                    'jesnis_harga' => $value->JENIS_HARGA, // (updated)
                    'tgl_tarikdata' => date('d/m/Y'),
                    'created_by' => $this->session->userdata('user_id')
                );
                $hasil_post = $this->curl->simple_post(API_URL . "/api/laporan/file", $param_post, array(CURLOPT_BUFFERSIZE => 10));
                // var_dump($hasil_post);exit;
            }
            $data_return = array(
                'status' => true,
                'message' => 'data berahasil ditarik'
            );
        }
        else{
            $data_return = array(
                'status' => false,
                'message' => 'Tidak ada data untuk ditarik'
            );
        }
        $this->output->set_output(json_encode($data_return));
    }
    public function download_file_ssu() {
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'no_trans' => $this->input->get('no_trans'),
            'jointable' =>array(
                array("MASTER_COM_FINANCE MF" , "MF.KD_LEASING = TRANS_FILE.KD_FINCOY AND MF.ROW_STATUS>=0", "LEFT"),
            ),
            "field" => "MF.KD_LEASINGAHM, TRANS_FILE.*"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/file", $param, array(CURLOPT_BUFFERSIZE => 10)));
        // $this->output->set_output(json_encode($data));
        if ($data && (is_array($data->message) || is_object($data->message))) {
            $no_trans = $this->autogenerate_trans('FL');
            $path_file = 'assets/uploads/FILE-' . time();
            // var_dump($nama_file);exit;
            if (!is_dir($path_file)) {
                mkdir($path_file, 0777, true);
            }
            for ($i = 1; $i <= 4; $i++) {
                switch ($i) {
                    case 1:
                        $tipe_file = 'UDSTK';
                        break;
                    case 2:
                        $tipe_file = 'CDDB';
                        break;
                    case 3:
                        $tipe_file = 'UDPRG';
                        break;
                    case 4:
                        $tipe_file = 'TXT';
                        break;
                }
                $hasil = $this->createfile_updrg($data, $path_file, $tipe_file, $no_trans);
            }
            if ($hasil == true) {
                foreach ($data->message as $key => $value) {
                    $param_post = array(
                        'id' => $value->ID,
                        'tgl_download' => date('d/m/Y'),
                        'status_download' => 1,
                        'lastmodified_by' => $this->session->userdata('user_id')
                    );
                    $hasil_post = $this->curl->simple_put(API_URL . "/api/laporan/file_download", $param_post, array(CURLOPT_BUFFERSIZE => 10));
                }
                $data_return = array(
                    'status' => true,
                    'message' => 'data berahasil didownload',
                    'file' => base_url() . 'report/download_allfile?namafile=' . $path_file
                );
            }
        } else {
            $data_return = array(
                'status' => false,
                'message' => 'Tidak ada data untuk didownload'
            );
        }
        $this->output->set_output(json_encode($data_return));
    }
    public function generate_file_ssu($debug=null){
        $data =array();
        ini_set('max_execution_time',500);
        $param=array(
            'no_trans' =>$this->input->get("n"),
            'kd_dealer'=> $this->session->userdata("kd_dealer"),
            'where_in' => $this->input->get("mesin"),
            "where_in_field" => "NO_MESIN"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/transaksi/ssu/true",$param));

        $no_mesine=array();$nama_file="";$kd_dealer="";
        if($data){
            if($data->totaldata >0){
                $n=0;
                foreach ($data->message as $key => $value) {
                    $n++;
                    $nama_file = $value->NAMA_FILE;
                    $no_mesine[]=$value->NO_MESIN;
                    $kd_dealer = $value->KD_DEALER;
                }
                $param=array(
                    'kd_dealer' => $kd_dealer,
                    'where_in' => $no_mesine,
                    'where_in_field' =>"NO_MESIN"
                );
                //create file
                $path_file = 'assets/uploads/FILE_SSU_' .$nama_file;
                if (!is_dir($path_file)) {
                    mkdir($path_file, 0777, true);
                }

                $data_udstk = json_decode($this->curl->simple_get(API_URL."/api/transaksi/datassu/udstk_d",$param));
                if($debug){
                    print_r($param);
                    var_dump($data_udstk->param);
                    exit();
                }
                $this->create_ssu($data_udstk,$nama_file,'UDSTK',$path_file);

                $data_cddb  = json_decode($this->curl->simple_get(API_URL."/api/transaksi/datassu/cddb_d",$param));
                $this->create_ssu($data_cddb,$nama_file,'CDDB',$path_file);
                
                $data_udprg = json_decode($this->curl->simple_get(API_URL."/api/transaksi/datassu/udprg_d",$param));
                $this->create_ssu($data_udprg,$nama_file,'UDPRG',$path_file);
                
                $data_txt   = json_decode($this->curl->simple_get(API_URL."/api/transaksi/datassu/txt",$param));
                $this->create_ssu($data_txt,$nama_file,'TXT',$path_file);
            }
        }
    }
    public function create_ssu($data,$namafile=null,$tipe_file=null,$path_file=null){
        ini_set('max_execution_time',500);
        $param=array();$isifile="";$isifile_cddb="";$isifile_udrpg="";$return=0; $isifile_txt="";
        if($data){
            if($data->totaldata >0){
                $namafile = $namafile.".".$tipe_file;
                foreach ($data->message as $key => $value){
                    switch ($tipe_file) {
                        case 'UDSTK':
                            $isifile .= $value->NO_RANGKA. ";";
                            $isifile .= $value->NO_MESIN1. ";";
                            $isifile .= $value->NO_MESIN2. ";";
                            $isifile .= $value->NAMA_BPKB. ";";
                            $isifile .= preg_replace('/\r\n?/', "", $value->ALAMAT_BPKB). ";";
                            $isifile .= $value->NAMA_KELURAHAN. ";";
                            $isifile .= $value->NAMA_KECAMATAN. ";";
                            $isifile .= $value->KD_KABUPATEN. ";";
                            $isifile .= $value->KODE_POS. ";";
                            $isifile .= $value->KD_PROPINSI. ";";
                            $isifile .= $value->JENIS_PEMBELIAN. ";";
                            $isifile .= $value->KD_DEALER. ";";
                            $isifile .= $value->KD_LEASING. ";";
                            $isifile .= $value->UANG_MUKA. ";";
                            $isifile .= $value->JANGKA_WAKTU. ";";
                            $isifile .= $value->JUMLAH_ANGSURAN. ";";
                            $isifile .= $value->HONDA_ID. ";";
                            $isifile .= $value->KD_POSAHM. ";".PHP_EOL;
                            break;
                        case 'CDDB':
                            $isifile_cddb .= $value->NO_MESIN1. ";";
                            $isifile_cddb .= $value->NO_MESIN2. ";";
                            $isifile_cddb .= $value->NO_KTP. ";";
                            $isifile_cddb .= $value->KD_CUSTOMER. ";";
                            $isifile_cddb .= $value->JENIS_KELAMIN. ";";
                            $isifile_cddb .= trim($value->TGL_LAHIR). ";";
                            $isifile_cddb .= preg_replace('/\r\n?/', "",$value->ALAMAT_SURAT). ";";
                            $isifile_cddb .= $value->NAMA_DESA. ";";
                            $isifile_cddb .= $value->NAMA_KECAMATAN. ";";
                            $isifile_cddb .= $value->KD_KOTA. ";";
                            $isifile_cddb .= $value->KODE_POS. ";";
                            $isifile_cddb .= $value->KD_PROPINSI. ";";
                            $isifile_cddb .= $value->KD_AGAMA. ";";
                            $isifile_cddb .= $value->PEKERJAAN. ";";
                            $isifile_cddb .= $value->PENGELUARAN. ";";
                            $isifile_cddb .= $value->PENDIDIKAN. ";";
                            $isifile_cddb .= $value->PIC_PERUSAHAAN. ";";
                            $isifile_cddb .= $value->NO_HP. ";";
                            $isifile_cddb .= $value->NO_TELP. ";";
                            $isifile_cddb .= $value->INFORMASI_BARU. ";";
                            $isifile_cddb .= $value->MERK_MOTOR. ";";
                            $isifile_cddb .= $value->JENIS_MOTOR. ";";
                            $isifile_cddb .= $value->DIGUNAKAN_UNTUK. ";";
                            $isifile_cddb .= $value->YANG_MENGGUNAKAN. ";";
                            $isifile_cddb .= $value->KD_SALES. ";";
                            $isifile_cddb .= $value->EMAIL. ";";
                            $isifile_cddb .= $value->STATUS_RUMAH. ";";
                            $isifile_cddb .= $value->STATUS_HP. ";";
                            $isifile_cddb .= $value->AKUN_FB. ";";
                            $isifile_cddb .= $value->TWITTER. ";";
                            $isifile_cddb .= $value->INSTAGRAM. ";";
                            $isifile_cddb .= $value->YOUTUBE. ";";
                            $isifile_cddb .= $value->HOBI. ";";
                            $isifile_cddb .= $value->KETERANGAN. ";";
                            $isifile_cddb .= $value->WNI. ";";
                            $isifile_cddb .= $value->KARTU_KELUARGA. ";";
                            $isifile_cddb .= $value->REFF_ID. ";";
                            $isifile_cddb .= $value->ROBB_ID. ";".PHP_EOL;
                            break;
                        case 'UDPRG':
                            $isifile_udrpg .= $value->KD_DEALER. ";";
                            $isifile_udrpg .= $value->NO_MESIN. ";";
                            $isifile_udrpg .= $value->KD_LEASING. ";";
                            $isifile_udrpg .= $value->KD_SALESPROGRAM. ";";
                            $isifile_udrpg .= $value->TELP_KOSONG. ";";
                            $isifile_udrpg .= $value->HP_KOSONG. ";";
                            $isifile_udrpg .= trim($value->TGL_BELI). ";";
                            $isifile_udrpg .= $value->KD_SALESPROGRAMAHM. ";";
                            $isifile_udrpg .= $value->LOKAL_SP. ";";
                            $isifile_udrpg .= $value->UANG_MUKA. ";";
                            $isifile_udrpg .= $value->JENIS_BELI. ";";
                            $isifile_udrpg .= $value->ASAL_JUAL. ";";
                            $isifile_udrpg .= $value->KD_LOKASI. ";";
                            $isifile_udrpg .= $value->SALES_FORCE. ";";
                            $isifile_udrpg .= $value->KD_DESA. ";";
                            $isifile_udrpg .= $value->KD_KECAMATAN. ";";
                            $isifile_udrpg .= $value->DP_SETOR. ";";
                            $isifile_udrpg .= $value->SUSB_AHM. ";";
                            $isifile_udrpg .= $value->SUB_MD. ";";
                            $isifile_udrpg .= $value->SUB_DLR. ";";
                            $isifile_udrpg .= $value->SUB_FIN. ";";
                            $isifile_udrpg .= (strlen($value->SPLIT_OTR)>0)?trim($value->SPLIT_OTR). ";":'0;';
                            $isifile_udrpg .= $value->RO. ";";
                            $isifile_udrpg .= $value->RO_MESIN. ";";
                            $isifile_udrpg .= $value->JENIS_CUSTOMER. ";";
                            $isifile_udrpg .= $value->OF_TR. ";";
                            $isifile_udrpg .= $value->KELURAHAN_SURAT. ";";
                            $isifile_udrpg .= $value->KECAMATAN_SURAT. ";".PHP_EOL;
                            break; 
                        case 'TXT':
                            $isifile_txt .= substr($value->KD_CUSTOMER,0,100).";";
                            $isifile_txt .= substr($value->NO_MESIN,0,31).";";
                            $isifile_txt .= substr(strtoupper($value->NAMA_CUSTOMER),0,31).";";
                            $isifile_txt .= substr($value->KD_LEASING,0,7).";";
                            $isifile_txt .= substr($value->KD_AKUN,0,5).";";
                            $isifile_txt .= substr($value->TGL_BELI,0,10).";";
                            $isifile_txt .= substr($value->KETERANGAN,0,10).";";
                            $isifile_txt .= substr($value->KD_ITEM,0,10).";";
                            $isifile_txt .= substr(strtoupper($value->ALAMAT_SURAT),0,100).";";
                            $isifile_txt .= substr(strtoupper($value->KELURAHAN),0,41).";";
                            $isifile_txt .= substr(strtoupper($value->NAMA_KECAMATAN),0,41).";";
                            $isifile_txt .= substr($value->CREATED_TIME,0,8).";";
                            $isifile_txt .= substr($value->LASTMODIFIED_TIME,0,8).";".PHP_EOL;
                            $this->update_mark_as_downloaded($value->NO_MESIN);
                            break;                   
                    }
                }
                
                switch($tipe_file){
                    case 'UDSTK':
                        $return +=(write_file(FCPATH . $path_file . '/' . $namafile, $isifile));
                    break;
                    case 'CDDB':
                        $return +=(write_file(FCPATH . $path_file . '/' . $namafile, $isifile_cddb));
                    break;
                    case 'TXT':
                        $return +=(write_file(FCPATH . $path_file . '/' . $namafile, $isifile_txt));
                    break;
                    case 'UDPRG':
                        $return +=(write_file(FCPATH . $path_file . '/' . $namafile, $isifile_udrpg));
                        //var_dump($this->download_allfile(FCPATH . replace("/","\\",$path_file) . '/' . $namafile));
                        if($return){
                                $data_return = array(
                                'status' => true,
                                'message' => 'data berahasil didownload',
                                'file' => base_url() . 'report/download_allfile?namafile=' . $path_file
                            );
                            $this->output->set_output(json_encode($data_return));
                        }   
                    break;
                }
                
            }
        }
    }
    function update_mark_as_downloaded($no_mesin){
        $hasil=array();
        $param=array(
            'no_mesin' => $no_mesin,
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = $this->curl->simple_put(API_URL . "/api/transaksi/ssu_download",$param, array(CURLOPT_BUFFERSIZE => 10));
        return $hasil;
    }
    public function createfile_updrg($data, $path_file, $tipe_file, $no_trans) {
        // $data=array();
        $namafile = "";
        $isifile = "";
        if ($data && (is_array($data->message) || is_object($data->message))):
            foreach ($data->message as $key => $row) {
                if ($tipe_file == 'UDSTK'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".UDSTK";
                    $isifile .= $row->NO_RANGKA . ";";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, -7) . ";";
                    $isifile .= $row->NAMA_CUSTOMER . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->NAMA_DESA . ";";
                    $isifile .= $row->NAMA_KECAMATAN . ";";
                    $isifile .= $row->KD_KABUPATEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->JENIS_PENJUALAN . ";";
                    $isifile .= $row->KD_DEALER . ";";
                    $isifile .= $row->KD_LEASINGAHM . ";";
                    $isifile .= number_format($row->UANG_MUKA, 0) . ";";
                    $isifile .= $row->JANGKA_WAKTU . ";";
                    $isifile .= number_format($row->JUMLAH_ANGSURAN, 0) . ";";
                    $isifile .= $row->HONDAID_SALES . ";"; //honda id sales
                    $isifile .= ";" . PHP_EOL; //kode pos penjualan ahm
                elseif ($tipe_file == 'CDDB'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".CDDB";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, 5, 7) . ";";
                    $isifile .= $row->NO_KTP . ";";
                    $isifile .= $row->GROUP_CUSTOMER . ";"; //group customer G = Group / I = Individual
                    $isifile .= $row->JENIS_KELAMIN . ";";
                    $isifile .= str_replace("/", "", tglFromSql($row->TGL_LAHIR)) . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->NAMA_DESA . ";";
                    $isifile .= $row->NAMA_KECAMATAN . ";";
                    $isifile .= $row->KD_KABUPATEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->KD_AGAMA . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->PENGELUARAN . ";"; //kode pengeluaran
                    $isifile .= $row->KD_PENDIDIKAN . ";"; //kode pendidikan
                    $isifile .= $row->NAMA_PENANGGUNGJAWAB . ";";
                    $isifile .= $row->NO_HP . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->INFORMASI . ";";
                    $isifile .= $row->MERK_MOTOR . ";"; // kode motor sekarang
                    $isifile .= $row->JENIS_MOTOR . ";"; // kode jenis motor sekarang
                    $isifile .= $row->DIGUNAKAN_UNTUK . ";"; //kode kegunaan
                    $isifile .= $row->YANG_MENGGUNAKAN . ";"; // kode pengguna
                    $isifile .= $row->KD_SALES . ";"; // kd sales honda id
                    $isifile .= $row->EMAIL . ";";
                    $isifile .= $row->STATUS_RUMAH . ";"; //kd status rumah
                    $isifile .= $row->STATUS_NOHP . ";"; //kd no hp
                    $isifile .= $row->AKUN_FB . ";";
                    $isifile .= $row->AKUN_TWITTER . ";";
                    $isifile .= $row->AKUN_INSTAGRAM . ";";
                    $isifile .= $row->AKUN_YOUTUBE . ";";
                    $isifile .= $row->HOBI . ";";
                    $isifile .= $row->KARAKTERISTIK_KONSUMEN . ";";
                    $isifile .= "1;";
                    $isifile .= ";"; //no_kartu_keluarga
                    $isifile .= $row->ID_REFFERAL . ";"; //reff_id
                    $isifile .= ";" . PHP_EOL; //robd_id
                elseif ($tipe_file == 'UDPRG'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".UDPRG"; //0
                    $isifile .= $row->KD_DEALER . ";"; //1
                    $isifile .= $row->NO_MESIN . ";"; //2
                    $isifile .= ($row->FINANCE_COMPANY != ''?$row->FINANCE_COMPANY:'CSH') . ";"; //3Tunai = CSH, FIF = FIF, ADIRA = ADR
                    $isifile .= $row->PROGRAM_ID . ";"; //4
                    $isifile .= $row->REASON_TEL . ";"; //5kd alasan tel
                    $isifile .= $row->REASON_HP . ";"; //6kd alasan hp
                    $isifile .= $row->TGL_JUAL . ";"; //7
                    $isifile .= $row->SP_ID . ";"; //8
                    $isifile .= $row->LCLSP_ID . ";"; //9
                    $isifile .= number_format($row->UANG_MUKA) . ";";//10
                    $isifile .= ($row->JENIS_BAYAR == 'CREDIT'?'K':'C') . ";";//11
                    $isifile .= $row->ASAL_JUAL . ";"; //DLR = Dealer, POS = Pos Penjualan//12
                    $isifile .= $row->KD_DLRPOS . ";";//13
                    $isifile .= $row->JENIS_SLSFORCE . ";";//14
                    $isifile .= $row->KD_DESA . ";";//15
                    $isifile .= $row->KD_KECAMATAN . ";";//16
                    $isifile .= number_format($row->DP_SETOR) . ";";//17
                    $isifile .= number_format($row->S_AHM) . ";";//18
                    $isifile .= number_format($row->S_MD) . ";";//19
                    $isifile .= number_format($row->S_SD) . ";";//20
                    $isifile .= number_format($row->S_FINANCE) . ";";//21
                    $isifile .= (strlen(trim($value->SPLIT_OTR))>0)?trim($value->SPLIT_OTR).";":'0;';//22
                    $isifile .= $row->RO . ";";//23
                    $isifile .= $row->NO_MESINRO . ";"; //no mesin ro//24
                    $isifile .= $row->J_CUST . ";"; //jenis customer : W – Walk In, C – Canvassing, E – Exhibition //25
                    $isifile .= ($row->JENIS_HARGA == 'Off The Road' ? '*':''). ";"; // off the road : Berisi * jika dijual //26 dengan off the road
                    $isifile .= $row->KD_DESA . ";"; // kode kelurahan surat //27
                    $isifile .= $row->KD_KECAMATAN . ";" . PHP_EOL; //kode kecamatan surat //28
                elseif ($tipe_file == 'TXT'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".TXT";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, 5, 7) . ";";
                    $isifile .= $row->NO_KTP . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    $isifile .= $row->KD_CUSTOMER . ";";
                    $isifile .= $row->JENIS_KELAMIN . ";";
                    $isifile .= str_replace("/", "", tglFromSql($row->TGL_LAHIR)) . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->NAMA_DESA . ";";
                    $isifile .= $row->NAMA_KECAMATAN . ";";
                    $isifile .= $row->NAMA_KABUPATEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->KD_AGAMA . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->PENGELUARAN . ";";
                    $isifile .= $row->KD_PENDIDIKAN . ";";
                    $isifile .= $row->NAMA_PENANGGUNGJAWAB . ";";
                    $isifile .= $row->NO_HP . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->STATUS_DIHUBUNGI . ";";
                    $isifile .= $row->NAMA_ITEM . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    // $isifile .= $row->JAWABAN . ";";
                    // $isifile .= $row->JAWABAN1 . ";";
                    $isifile .= $row->KD_ITEM . ";";
                    $isifile .= $row->NAMA_SALES . ";";
                    $isifile .= $row->EMAIL . ";";
                    $isifile .= $row->STATUS_RUMAH . ";";
                    $isifile .= $row->STATUS_NOHP . ";";
                    $isifile .= $row->AKUN_FB . ";";
                    $isifile .= $row->AKUN_TWITTER . ";";
                    $isifile .= $row->AKUN_INSTAGRAM . ";";
                    $isifile .= $row->AKUN_YOUTUBE . ";";
                    $isifile .= $row->HOBI . ";";
                    $isifile .= $row->KARAKTERISTIK_KONSUMEN . ";";
                    $isifile .= $row->ID_REFFERAL . ";" . PHP_EOL;
                endif;
                // $isifile .= $row->STATUS_SJ.";";
            }
        endif;
        $this->load->helper('file');
        if (write_file(FCPATH . $path_file . '/' . $namafile, $isifile) == TRUE) {
            foreach ($data->message as $key => $rows) {
                $param = array(
                    'id' => $rows->ID,
                    'status_kpb' => 2,
                    'lastmodified_by' => $this->session->userdata('user_id')
                );
                $data = json_decode($this->curl->simple_put(API_URL . "/api/service/kpb_validasi_status", $param));
            }
            $return = true;
        } else {
            $return = false;
        }
        return $return;
        // return $data_return;
        // $this->output->set_output(json_encode($data_return));        
    }
    public function download_allfile(){
        $namafile = 'FILE_SSU_' . time() . '.zip';
        $folder_in_zip = '/'; //root directory of the new zip file
        $path = $this->input->get('namafile');
        $this->load->library(array('Zip', 'MY_Zip'));
        $this->zip->get_files_from_folder($path, $folder_in_zip);
        $this->zip->download($namafile);
    }
    public function update_ssu($id){
        $param = array(
            'id'=> $id,
            'nama_customer'=> $this->input->post("nama_customer"),
            'jenis_kelamin'=> $this->input->post("jenis_kelamin"),
            'tgl_lahir'=> $this->input->post("tgl_lahir"),
            'tgl_pembuatan_npwp'=> $this->input->post("tgl_pembuatan_npwp"),
            'no_ktp'=> $this->input->post("no_ktp"),
            'no_npwp'=> $this->input->post("no_npwp"),
            'alamat_surat'=> $this->input->post("alamat_surat"),
            'kelurahan'=> $this->input->post("kelurahan"),
            'kd_kecamatan'=> $this->input->post("kd_kecamatan"),
            // 'kd_kota'=> $this->input->post("kd_kota"),
            'kode_pos'=> $this->input->post("kode_pos"),
            'kd_propinsi'=> $this->input->post("kd_propinsi"),
            'kd_agama'=> $this->input->post("kd_agama"),
            'kd_pekerjaan'=> $this->input->post("kd_pekerjaan"),
            'pengeluaran'=> $this->input->post("pengeluaran"),
            'kd_pendidikan'=> $this->input->post("kd_pendidikan"),
            'nama_penanggungjawab'=> $this->input->post("nama_penanggungjawab"),
            'no_hp'=> $this->input->post("no_hp"),
            'no_telepon'=> $this->input->post("no_telepon"),
            'status_dihubungi'=> $this->input->post("status_dihubungi"),
            'email'=> $this->input->post("email"),
            'status_rumah'=> $this->input->post("status_rumah"),
            'status_nohp'=> $this->input->post("status_nohp"),
            'akun_fb'=> $this->input->post("akun_fb"),
            'akun_twitter'=> $this->input->post("akun_twitter"),
            'akun_instagram'=> $this->input->post("akun_instagram"),
            'akun_youtube'=> $this->input->post("akun_youtube"),
            'hobi'=> $this->input->post("hobi"),
            'karakteristik_konsumen'=> $this->input->post("karakteristik_konsumen"),
            'nama_propinsi'=> $this->input->post("nama_propinsi"),
            'kd_kabupaten'=> $this->input->post("kd_kabupaten"),
            'nama_kabupaten'=> $this->input->post("nama_kabupaten"),
            'nama_kecamatan'=> $this->input->post("nama_kecamatan"),
            'kd_desa'=> $this->input->post("kd_desa"),
            'nama_desa'=> $this->input->post("nama_desa"),
            'alamat'=> $this->input->post("alamat"),
            'lastmodified_by'=> $this->session->userdata('user_id')
        );
        /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
        $hasil=$this->curl->simple_put(API_URL."/api/laporan/file",$param, array(CURLOPT_BUFFERSIZE => 10));
     /*   var_dump($hasil);
        exit;*/
        $this->data_output($hasil, 'put');
    }
    // generate code number
    // ===========================================================================
    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function autogenerate_trans($kd_docno){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'NO TRANS File UDPRG',
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
        //var_dump(($hasil));exit();
        switch ($method) {
            case 'post':
                $hasil =($hasil) ?(json_decode($hasil)):null;
                if($hasil){
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
                }else{
                    $result = array(
                            'status' => true,
                            'message' => "Data tidak tersedia",
                            'location' => $location,
                            'noclaim' => $no_claim
                        );
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