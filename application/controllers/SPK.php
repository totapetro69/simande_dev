<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/controllers/Cashier.php';
class SPK extends Cashier {
     var $API="";
     /**
      * [__construct description]
      */
    public function __construct(){
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->helper('url'); 
        $this->load->helper('zetro'); 
        $this->load->helper('terbilang');
        $this->load->model("Custom_model"); 
        if($this->session->userdata('kd_div') == null)
        {
            redirect("auth");
        }
    }
    /**
     * SPK STATUS :
        1: di bayar -- kondisi masih bisa edit
        2: sudah SO
        3 : Dikirim parsial -- tidak ada status ini
        4: dikirm Full
        5: dikembalikan
        6: Indent
        -1 : Dihapus
        -2 : Batal need aproval
     */
    /**
     * [pagination description]
     * @param  [type] $config [description]
     * @return [type]         [description]
     */
    public function pagination($config){
        $config['per_page'] = $config['per_page'];
        $config['base_url'] = $config['base_url'];
        $config['total_rows'] = $config['total_rows'];
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open'] = "<ul class='pagination pagination-sm m-t-none m-b-none'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        return $config;
    }
    /**
     * [spk description]
     * @return [type] [description]
     */
    public function spk($view=null){
        if($view){
            $param=array(
                'spkid' => $this->input->get("spk_id"),
                'field' => "NO_KTP,KD_HSALES,NAMA_SALES,KD_SALES"
            );
            $data=json_decode($this->curl->simple_get(API_URL."/api/spk/spkview", $param));
            echo json_encode($data);
            exit();
        }
        $param = array(
            'row_status' => $this->input->get('row_status'),
            'offset'     => ($this->input->get('page')== null)?0:$this->input->get('page', TRUE),
            'limit'      => 15,
            'jointable'  => array(
                            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_LEASING SK","SK.SPK_ID=TRANS_SPK.ID AND SK.ROW_STATUS >=0","LEFT"),
                            array("TRANS_SPK_DETAILKENDARAAN SD","SD.SPK_ID=TRANS_SPK.ID AND SD.ROW_STATUS >=0","LEFT")
                        ),
            'field'      => "TRANS_SPK.*,MC.NAMA_CUSTOMER,SK.ID AS LEASINGID,SK.KD_FINCOY,SK.HASIL,SK.KETERANGAN KET,SD.HARGA_OTR,SD.DISKON,SD.KD_TYPEMOTOR,SD.KD_WARNA",
            'orderby'    => 'TRANS_SPK.NO_SPK DESC,TRANS_SPK.ID DESC'
        );
        $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
        $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
        if($this->input->get("keyword")){
            $param["custom"] = "(MC.NAMA_CUSTOMER LIKE '%". $this->input->get('keyword')."%' OR NO_SPK LIKE '%".$this->input->get('keyword')."%')";
        }else{
            $param["custom"] ="CONVERT(CHAR,TRANS_SPK.TGL_SPK,112) BETWEEN '".tglToSql($dari_tanggal)."' AND '".tglToSql($sampai_tanggal)."'";
        }
        if($this->input->get('kd_dealer')){
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }else{
             $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        }
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $param));
        // var_dump(($data["list"]));print_r($param);exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],//base_url().'spk/spk?keyword='.$param['keyword'],
            'total_rows' => isset($data["list"])? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('sales/spk', $data);
    }
    /**
     * [add_spk description]
     * @param [type] $debug [description]
     */
    public function add_spk($debug=null){
        $typemotor="";$carabayar="";$kd_typemotor="";$kd_warna="";$nomorspk="";
        $jointable = array(
            array("TRANS_GUESTBOOK MG","MG.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MG.ROW_STATUS>=0","LEFT"),
            array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER","LEFT"),
            array("TRANS_SPK_LEASING SL","SL.SPK_ID=TRANS_SPK.ID AND SL.ROW_STATUS=0","LEFT")
         );

        if($this->input->get('id')||$this->input->get('n')){
            $param=array(
                'spkid'     => $this->input->get('id'),
                'jointable' => $jointable,
                'field'     => "TRANS_SPK.ID,TRANS_SPK.NO_SPK,TRANS_SPK.TGL_SPK,TRANS_SPK.KD_DEALER,TRANS_SPK.KD_MAINDEALER,
                                \"CASE WHEN LEN(TRANS_SPK.KD_SALES)=0 THEN MG.KD_SALES ELSE TRANS_SPK.KD_SALES END AS KD_SALES\"
                                ,TRANS_SPK.KD_SALESHONDA,TRANS_SPK.KD_CUSTOMER,
                                TRANS_SPK.JENIS_PENJUALAN,TRANS_SPK.TYPE_PENJUALAN,TRANS_SPK.JENIS_P_ANTARDEALER,TRANS_SPK.KD_TYPECUSTOMER,
                                TRANS_SPK.PENJUALAN_VIA,TRANS_SPK.JENIS_HARGA,TRANS_SPK.STATUS_SPK,TRANS_SPK.GUEST_NO,TRANS_SPK.FAKTUR_PENJUALAN,
                                TRANS_SPK.CHANEL,TRANS_SPK.TGL_SO,TRANS_SPK.LOK_PENJUALAN,TRANS_SPK.PAYBILL_REFF,TRANS_SPK.PAYBILL_REFF2,
                                TRANS_SPK.ROW_STATUS,MG.ID AS GUESTID,MC.NAMA_CUSTOMER,
                                ISNULL(MG.KD_TYPEMOTOR,'')KD_TYPEMOTOR,ISNULL(MG.KD_WARNA,'')KD_WARNA,
                                \"CASE WHEN KD_TYPEMOTOR IS NOT NULL THEN CONCAT(MG.KD_TYPEMOTOR,'-',MG.KD_WARNA)ELSE '' END AS KD_ITEM\",
                                MC.PENGELUARAN,MC.KD_PENDIDIKAN,MC.KD_PEKERJAAN,MC.NO_HP,MC.NO_TELEPON,SL.ID AS LEASINGID,SL.HASIL,
                                SL.KETERANGAN KET_LEASING,SL.KD_FINCOY,
                                \"(SELECT NAMA_LEASING FROM MASTER_COM_FINANCE MFL WHERE MFL.KD_LEASING=SL.KD_FINCOY) NAMA_LEASING\""
            );

            $data["spkview"] = json_decode($this->curl->simple_get(API_URL."/api/spk/spk",$param));
            $n=0;
            if($data["spkview"]){
                if(($data["spkview"]->totaldata >0)){
                    foreach ($data["spkview"]->message as $key => $value) {
                        $n++;
                        $typemotor .= $value->KD_ITEM;
                        $kd_typemotor .= $value->KD_TYPEMOTOR;
                        $kd_warna   .= $value->KD_WARNA;
                        $typemotor .=($n==count($data["spkview"]->message) || $value->KD_ITEM=='')?"":",";
                        $kd_typemotor .=($n==count($data["spkview"]->message)|| $value->KD_TYPEMOTOR=='')?"":",";
                        $kd_warna .=($n==count($data["spkview"]->message)|| $value->KD_WARNA=='')?"":",";
                        $carabayar =$value->TYPE_PENJUALAN;
                        $spkidne = $value->ID;
                        $nomorspk = $value->NO_SPK;
                    }
                    // var_dump($data["spk_bpkb"]);exit();
                }
            }
            $paramkendaraan=array(
                'spk_id' =>$param['spkid']
            );
            $data["spk_motor"] = json_decode($this->curl->simple_get(API_URL."/api/laporan/spkview_kendaraan",$paramkendaraan));
            $data["spk_bpkb"]  = json_decode($this->curl->simple_get(API_URL."/api/spk/spk_detailcustomer",$paramkendaraan));

            $paramkendaraan=array(
                'no_spk' =>$nomorspk
            );
            $data["salesprg"]  = json_decode($this->curl->simple_get(API_URL."/api/spk/spk_salesprogram",$paramkendaraan));
            $paramkendaraan=array(
                'no_reff' =>$nomorspk,
                'orderby' => "ID DESC"
            );
            $data["tipun"]  = json_decode($this->curl->simple_get(API_URL."/api/accounting/titipan_uang",$paramkendaraan));
        }
        $paramcustomer=array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        /**
         * tab Customer dan header
         */
        if($this->input->get("g")){
            $paramcustomer["guest_no"] =base64_decode(urldecode($this->input->get("g")));
        }
        $data["propinsi"]   =json_decode($this->curl->simple_get(API_URL."/api/master_general/propinsi"));
        $paramcustomer["custom"]="STATUS NOT IN('Not Deal','Pending') AND ROW_STATUS=0 AND NAMA_CUSTOMER !='' AND (SPK_NO IS NULL OR SPK_NO='')";
        $paramcustomer["orderby"]="NAMA_CUSTOMER";
        if($this->input->get("n")){
            $paramcustomer=array(
                'spk_no' => base64_decode(urldecode($this->input->get("n")))
            );
        }
        $data["guestbook"]  =json_decode($this->curl->simple_get(API_URL."/api/sales/guestbook",$paramcustomer));
        if($debug==true){
            print_r($data["spkview"]);
            exit();
        }
        $data["gender"]     =json_decode($this->curl->simple_get(API_URL."/api/master_general/jeniskelamin"));
        $data["agama"]     =json_decode($this->curl->simple_get(API_URL."/api/master_general/agama"));
        $paramcustomer=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'jointable' => array(array("MASTER_WILAYAH MW","MW.KD_PROPINSI=MASTER_DEALER.KD_PROPINSI","LEFT")),
            'field' => 'MASTER_DEALER.*,MW.KD_WILAYAH'
        );
        $data["hobbyne"] = $this->hoby();
        $data["dealer"]  = json_decode($this->curl->simple_get(API_URL."/api/master/dealer/1/1",$paramcustomer));
        // print_r($paramcustomer);
        // var_dump($data["dealer"]);exit();
        $data["typecustomer"]=json_decode($this->curl->simple_get(API_URL."/api/setup/typecustomer"));
        /**
         * Tab Kendaraan
         */
        $param=array(
            "jointable" => array(array("TRANS_SPK_LEASING_KOMPOSISI AS SK","SK.KD_LEASING=MASTER_COM_FINANCE.KD_LEASING","LEFT","KD_LEASING,RANGKING_LEASING")),
            "orderby" =>"ISNULL(SK.RANGKING_LEASING,99),MASTER_COM_FINANCE.NAMA_LEASING",
            "custom"   => "MASTER_COM_FINANCE.KD_LEASING !=''",
            "field"    =>"MASTER_COM_FINANCE.KD_LEASING,NAMA_LEASING"
        );
        if($this->input->get("id")){
            $param["custom"] .=" AND MASTER_COM_FINANCE.KD_LEASING NOT IN ((SELECT KD_FINCOY FROM TRANS_SPK_LEASING WHERE SPK_ID=".$this->input->get('id')." AND ROW_STATUS>0))";
        }
        $data["fincom"]     = json_decode($this->curl->simple_get(API_URL."/api/master/company_finance",$param));
        // var_dump($data["fincom"]);exit();
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom'    => "START_DATE <='".date("Ymd")."' AND END_DATE >='".date("Ymd")."'"
        );
        if(strlen($kd_typemotor)>1 && strlen($kd_warna)>1){
            $param["custom"] .= " AND (KD_TYPEMOTOR IN('".str_replace(",", "','", $kd_typemotor)."') AND KD_WARNA IN('".str_replace(",", "','", $kd_warna)."') OR (KD_TYPEMOTOR IN('".str_replace(",", "','", $kd_typemotor)."') AND KD_WARNA='')";
        }else if(strlen($kd_typemotor)>1){
             $param["custom"] .="(KD_TYPEMOTOR IN('".str_replace(",", "','", $kd_typemotor)."') AND KD_WARNA='')";
        }
        $data["bundling"]       = json_decode($this->curl->simple_get(API_URL."/api/master/bundling",$param));
        // var_dump($param );exit();
        $param = array(
            'jointable' =>array(array("MASTER_KABUPATEN MK","MK.NAMA_KABUPATEN = SETUP_SALESPROGRAMKOTA.KD_KABUPATEN","LEFT"),
                            array("MASTER_DEALER MD","MD.KD_KABUPATEN = MK.KD_KABUPATEN","LEFT"),
                            array("SETUP_SALESPROGRAM AS SP","SP.KD_SALESPROGRAM = SETUP_SALESPROGRAMKOTA.KD_SALESPROGRAM","LEFT","KD_SALESPROGRAM,NAMA_SALESPROGRAM,START_DATE,END_DATE")
                        ),
            'field'     => 'SP.KD_SALESPROGRAM,SP.NAMA_SALESPROGRAM,SETUP_SALESPROGRAMKOTA.KD_KABUPATEN',
            'groupby'   => true,
            'orderby'   => 'SP.KD_SALESPROGRAM'
         );
        $param["custom"] = "START_DATE <='".date("Ymd")."' AND END_DATE >='".date("Ymd")."'";
        $param["custom"] .= " AND (MD.KD_DEALER='".$this->session->userdata("kd_dealer")."' OR SETUP_SALESPROGRAMKOTA.KD_KABUPATEN='ALL')";
        $data["salesprogram"]   = json_decode($this->curl->simple_get(API_URL."/api/setup/salesprogramkota",$param));
        $data["saleskupon"]     =$this->saleskupon_new(FALSE);// ($this->saleskuponkota(FALSE,$typemotor,$carabayar));
        $data["saleskupon2"]    = $this->saleskupon(FALSE,$typemotor);
        $paramsales=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_maindealer'=> $this->session->userdata("kd_maindealer"),
            'jointable' => array(array("MASTER_COM_FINANCE MF","MF.KD_LEASING=TRANS_SPK_LEASING_KOMPOSISI.KD_LEASING","LEFT")),
            'field' =>"TRANS_SPK_LEASING_KOMPOSISI.ID,TRANS_SPK_LEASING_KOMPOSISI.KD_LEASING,ISNULL(MF.NAMA_LEASING,'OTHERS LEASING')NAMA_LEASING,TARGET_LEASING"
        );
        $where ="WHERE SL.KD_DEALER='".$this->session->userdata("kd_dealer")."' AND SL.TAHUN='".date('Y')."'";
        $paramsales=array(
            'query'  => $this->Custom_model->Leasing_Achieve($where)
        );
        $data["prosensales"]    =json_decode($this->curl->simple_get(API_URL."/api/spk/leasing_komposisi",$paramsales));
        // var_dump($data["prosensales"]);exit();
        // history leasing
        if($this->input->get("id")){
            $paramsales=array(
                'spk_id'    => $this->input->get("id"),
                //'custom'    => "TRANS_SPK_LEASING.ROW_STATUS > 0",
                'jointable' => array(array("MASTER_COM_FINANCE MF","MF.KD_LEASING=TRANS_SPK_LEASING.KD_FINCOY","LEFT")),
                'field'     => "TRANS_SPK_LEASING.KD_FINCOY,NAMA_LEASING,TRANS_SPK_LEASING.HASIL,TRANS_SPK_LEASING.KETERANGAN,TRANS_SPK_LEASING.TANGGAL,TRANS_SPK_LEASING.ALASAN_PAKSA,TRANS_SPK_LEASING.TYPE_CREDIT,TRANS_SPK_LEASING.ROW_STATUS"
            );
         $data["hist_leasing"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk_leasing",$paramsales));
        }else{
            $data["hist_leasing"]=null;
        }
        /**
         * Tab Quiz
         */
        $param=array("orderby" =>"NAMA_PEKERJAAN");
        $data["pekerjaan"]     = json_decode($this->curl->simple_get(API_URL."/api/master_general/pekerjaan",$param));
        $param=array("orderby" =>"CAST(KD_PENDIDIKAN AS INT)");
        $data["pendidikan"]     = json_decode($this->curl->simple_get(API_URL."/api/master_general/pendidikanlevel",$param));
        $data["jenise_motor"]   = json_decode($this->curl->simple_get(API_URL . "/api/master_general/jenismotor"));
        $data["merke_motor"]    = json_decode($this->curl->simple_get(API_URL . "/api/master_general/merkmotor"));
        $data["penggunane"]     = json_decode($this->curl->simple_get(API_URL . "/api/master_general/pengguna"));
        $data["kegunaane"]      = json_decode($this->curl->simple_get(API_URL . "/api/master_general/kegunaan"));
        $this->template->site('sales/add_spk',$data);
    }

    /**
     * [simpan_spk description]
     * simpan / update dalam satu transaksi
     * @return [type] [description]
     * Status SPK
     *  0:Open
     *  1: di bayar
     *  2: sudah SO
     *  3: Dikirim parsial
     *  4: dikirm Full
     *  5: dikembalikan
     *  6: Indent
     *  -1 : Dihapus
     */
    public function simpan_spk($antarDealer=null){
        $nomorspk=($this->input->post('nospk'))?$this->input->post("nospk"):$this->nomor_spk();
        $param=array(
            'no_spk'    => $nomorspk,
            'kd_maindealer' =>$this->session->userdata("kd_maindealer"),
            'kd_dealer' => $this->session->userdata("kd_dealer"),'kd_customer'   => $this->input->post("kd_customer"),
            'type_penjualan'=> $this->input->post("type_penjualan"),
            'jenis_penjualan'=> $this->input->post("jenis_penjualan"),
            'jenis_p_antardealer'=> $this->input->post("jp_antardealer"),
            'kd_typecustomer'   => $this->input->post("kd_typecustomer"),
            'penjualan_via' => $this->input->post("kd_groupsales"),
            'jenis_harga'   => $this->input->post("jenis_harga"),
            'kd_sales'      =>($this->input->post("kd_groupsales")=="TP")?$this->input->post('kd_salesman_tp'): $this->input->post("kd_salesman"),
            'kd_saleshonda' => $this->input->post("kd_saleshonda"),
            'created_by'    => $this->session->userdata("user_id"),
            'tgl_spk'       => $this->input->post("tgl_spk"),
            'guest_no'       => $this->input->post("guest_no"),
            'chanel'        =>($this->input->post("jp_antardealer"))?$this->input->post("kd_dealer"):""
        );
        $spkid=0;$spkcs=0;$spkgb=0;$hasil=array();
        $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk", $param, array(CURLOPT_BUFFERSIZE => 100)));   
        if($hasil->recordexists==TRUE){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk", $param, array(CURLOPT_BUFFERSIZE => 100)));  
        }
        $spkid =($this->input->post('spkid'))?($this->input->post('spkid')):$hasil->message;
        //var_dump($hasil);var_dump($antarDealer);var_dump($spkid);
        if($antarDealer=="1"){
            //echo $spkid;
            return $spkid;
        }else{
            if($hasil){
                if((int)$hasil->message>0){
                    $spkcs =$this->simpancs_spk($spkid);
                    $spkcs +=$this->simpansurat_spk($spkid);
                    if($spkcs >0){
                        $this->guestbook_upd_status($nomorspk);
                    }
                    //updata data customer
                    $spkgb=$this->update_customer($param["kd_customer"]);
                }
            }
            echo $param["no_spk"].":".$spkid.":".$hasil->status.":".$hasil->message;
        }
    }
    /**
     * [simpancs_spk description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function simpancs_spk($id){
        $tgl_lahir = explode("/", $this->input->post("tgl_lahir"));
        $tgl_lahire = (strlen($tgl_lahir[0])==4)?str_replace("-", "", $this->input->post("tgl_lahir")):tglToSql($this->input->post("tgl_lahir"));
        $param=array(
            'spk_id'        => $id,
            'kd_customer'   => $this->input->post("kd_customer"),
            'nama_bpkb'     => $this->input->post("nama_dibpkb"),
            'alamat_bpkb'   => $this->input->post("alamat_dibpkb"),
            'ktp_bpkb'      => $this->input->post("nomor_ktp"),
            'email_bpkb'    => $this->input->post("email_customer"),
            'tgl_lahir_bpkb'=> $tgl_lahire,
            'keterangan'    => $this->input->post("keterangan"),
            'created_by'    => $this->session->userdata("user_id"),
        );
        //tambahan untuk kebutuhan ssu
            $param["kd_propinsi"]       = $this->input->post('kd_propinsi_bpkb');
            $param["kd_kabupaten"]      = $this->input->post('kd_kabupaten_bpkb');
            $param["kd_kecamatan"]      = $this->input->post('kecamatan_bpkb');
            $param["kd_kelurahan"]      = $this->input->post('kelurahan_bpkb');
            $param["nama_kecamatan"]    = $this->input->post('kec');
            $param["nama_kelurahan"]    = $this->input->post('dsa');
            $param["kode_pos"]          = $this->input->post('kode_posbpkb');
        $hasil =json_decode ($this->curl->simple_post(API_URL."/api/spk/spk_detailcustomer", $param, array(CURLOPT_BUFFERSIZE => 100)));
        if($hasil){
            if($hasil->recordexists==TRUE){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil =json_decode ($this->curl->simple_put(API_URL."/api/spk/spk_detailcustomer", $param, array(CURLOPT_BUFFERSIZE => 100)));
            }
        }
        $spkcs=0;
        if($hasil){
            $spkcs=$hasil->message;
        }
        return $spkcs;
    }
    /**
     * [simpanmotor_spk description]
     * @return [type] [description]
     */
    public function simpanmotor_spk(){
        $hasil="";
        $data = array();
        $pdata = json_decode($this->input->post("motor"),true);
        // $phead = json_decode($this->input->post("header"),true);
        $mode=0;
        // jika spk antar dealer direc
        $antarDealer = $this->input->post("jp_antardealer");
        $spkid=($antarDealer)? $this->simpan_spk($antarDealer):$this->input->post('espekaid');
        $data =$pdata;
        //mode approval spk untuk leasing
        if($this->input->post("show_approval")=="1"){
            $hasil = $this->simpanleasing_spk($this->input->post('espekaid'));
            $mode = 1;
        }else{
            for ($i=0;$i< count($data);$i++){
                $tpm=explode('-',$data[$i]["kd_item"]);
                $param = array(
                    'spk_id'        =>($spkid)?$spkid: $this->input->post('espekaid') ,
                    'kd_typemotor'  => $tpm[0],
                    'kd_warna'      => explode("[",$tpm[1])[0],
                    'harga'         => $data[$i]["harga_jual"],
                    'harga_otr'     => $data[$i]["harga_otr"],
                    'harga_dealer'  => $data[$i]["harga_dealer"],
                    'harga_dealerd' => $data[$i]["harga_dealerd"],
                    'jumlah'        => $data[$i]["qty"],
                    'bbn'           => $data[$i]["biaya_stnk"],
                    'diskon'        => $data[$i]["diskon"],
                    'kd_salesprogram'=> $this->input->post('kd_program_grp'),
                    'kd_saleskupon' => $this->input->post('kd_saleskupon_grp'),
                    'kd_bundling'   => $this->input->post('kd_bundling_grp'),
                    'crm'           => $this->input->post('crm'),
                    'aksesoris'     => $this->input->post('aksesoris'),
                    'hadiah'        => $this->input->post('hadiah'),
                    'estimasi_stnk' => $this->input->post("tgl_stnk"),
                    'estimasi_bpkb' => $this->input->post("tgl_bpkb"),
                    'no_rangka'     => NULL,
                    'no_mesin'      => NULL,
                    'kd_paket'      => NULL,
                    'created_by'    => $this->session->userdata("user_id")
                );
                //print_r($param);
                /*exit();*/
                $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_detailkendaraan", $param, array(CURLOPT_BUFFERSIZE => 100)));
                if($hasil->recordexists==TRUE){
                    $mode=1;
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_detailkendaraan", $param, array(CURLOPT_BUFFERSIZE => 100)));
                }
                if($hasil){
                    if($hasil->message > 0){
                       $leasing = $this->simpanleasing_spk($this->input->post('espekaid'));
                       $kiriman = $this->simpanalamatkirim_spk($this->input->post('espekaid'));
                       $saleskupom =($param["kd_saleskupon"])?$this->simpan_saleskupon($param,$this->input->post('nospkne')):"";
                       $salesprg =($param["kd_salesprogram"])?$this->simpan_salesprogram($param,$this->input->post('nospkne')):"";
                       $salesbdg =($param["kd_bundling"])?$this->simpan_salesbundling($param,$this->input->post('nospkne')):"";
                    }
                } 
                //var_dump($hasil);
            }
        }
        echo $this->input->post('espekaid').":".$mode.":".$hasil->status;
    }
    /**
     * [simpanquiz_spk description]
     * @return [type] [description]
     */
    public function simpanquiz_spk(){
        $param = array();$params=array();
        $data = array();
        $pdata = json_decode($this->input->post("quiz"),true);
        $data =$pdata;//("[{" . substr($pdata, 0, strlen($pdata) - 0) . "}]");
        if($data){
            $n=0;$nourut='';
            for($i=1;$i< count($data);$i++){
                $nourut=explode('_',$data[$i]["name"]);
                $param[$n]["no_urut"]   =(count($nourut)==5)?$nourut[2]:$nourut[0];
                $param[$n]["keterangan"]   = $data[$i]["name"];
                $param[$n]["jawaban"]   = $data[$i]["value"];
                $param[$n]["spk_id"]    =   $data[0]["value"];
                $param[$n]["kd_customer"]   = $this->input->post('kd_customer');
                $param[$n]["created_by"]    = $this->session->userdata("user_id");
                $n++;
            }
        }
         /*exit();*/
        for($x=0;$x < count($param);$x++){
            $paramx="";
            $paramx=$param[$x];$mode=0;
            $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/trans_kuisoner", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
            if($hasil->recordexists==TRUE){
                $mode=1;
                $paramx["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/trans_kuisoner", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
            }
            //print_r($paramx)."<br>";
        }
        echo $param[0]["spk_id"].":".$mode.":".$hasil->status;
    }
    function simpankk_spk(){
        $param=array();
        $data = array();
        $data = json_decode($this->input->post("kk"),true);
        $param = array(
            'no_kk' => $this->input->post('no_kk'),
            'alamat_kk' => $this->input->post('alamat_kk'),
            'rtrw_kk'   => $this->input->post('rtrw_kk'),
            'kd_propinsi' => $this->input->post('propinsi_kk'),
            'kd_kabupaten' => $this->input->post('kabupaten_kk'),
            'kd_kecamatan' => $this->input->post('kecamatan_kk'),
            'kd_desa' => $this->input->post('desa_kk'),
            'keterangan' => $this->input->post('espekaid'),
            'created_by' => $this->session->userdata("user_id")
        );
        $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/datakk", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if($hasil->recordexists==TRUE){
            $mode=1;
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/datakk", $param, array(CURLOPT_BUFFERSIZE => 10)));

        }
        if($hasil){
            $hasilx = $this->simpan_kk_detail($data);
            //update master customer input kk by kd customer
            $param=array(
                'query' => $this->Custom_model->nomor_kk($this->input->post('kd_cus'),$this->input->post('no_kk'))
            );
            $mode=2;
            $rst= json_decode($this->curl->simple_get(API_URL."/api/spk/datakk",$param));
        }
        
        echo $this->input->post('espekaid').":".$mode.":".$hasil->status;
    }
    function simpan_kk_detail($datakk){
        if(count($datakk)>0){
            for($i=0; $i < count($datakk); $i++){
                $param=array(
                    'no_kk' =>$datakk[$i]["no_kk"],
                    'nama_anggota'  => $datakk[$i]["nama_anggota"],
                    'jenis_kelamin'  => $datakk[$i]["jenis_kelamin"],
                    'tgl_lahir'  => $datakk[$i]["tgl_lahir"],
                    'nik_anggota'  => $datakk[$i]["nik_anggota"],
                    'status_dikk'   => NULL,
                    'pendidikan_terakhir'=>NULL,
                    'pekerjaan'=>NULL,
                    'nama_ibukandung'   =>NULL,
                    'created_by'=> $this->session->userdata("user_id")
                );
                $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/datakk_detail", $param, array(CURLOPT_BUFFERSIZE => 100)));
                if($hasil->recordexists==TRUE){
                    $mode=1;
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/datakk_detail", $param, array(CURLOPT_BUFFERSIZE => 100)));
                }
                // var_dump($hasil);
                // print_r($param);exit();
            }
        }
    }
    /**
     * [simpansurat_spk description]
     * @param  [type] $spkid [description]
     * @return [type]        [description]
     */
    public function simpansurat_spk($spkid){
        $param = array();
        $param["spk_id"]        = $spkid;
        $param["kd_customer"]   = $this->input->post('kd_customer');
        $param["alamat_surat"]  = $this->input->post('alamat_surat');
        $param["kd_propinsi"]   = $this->input->post('kd_propinsi_surat');
        $param["kd_kota"]       = $this->input->post('kd_kabupaten_surat');
        $param["kd_kecamatan"]  = $this->input->post('kd_kecamatan_surat');
        $param["kd_desa"]       = $this->input->post('kd_desa_surat');
        $param["kode_pos"]      = $this->input->post('kode_possurat');
        $param["created_by"]    = $this->input->post('created_by');
        $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_detailalamat", $param, array(CURLOPT_BUFFERSIZE => 100)));
        if($hasil->recordexists==TRUE){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_detailalamat", $param, array(CURLOPT_BUFFERSIZE => 100)));
        }
        if($hasil){
            return $hasil->message;
        }else{
            return 0;
        }
    }
    /**
     * [simpanalamatkirim_spk description]
     * @param  [type] $spkid [description]
     * @return [type]        [description]
     */
    public function simpanalamatkirim_spk($spkid){
        $param=array();
        $param["spk_id"]        = $spkid;
        $param["tgl_kirim"]     = $this->input->post('tgl_kirim');
        $param["no_hp"]         = $this->input->post('no_hp_surat');
        $param["alamat_kirim"]  = $this->input->post('alamat_pengiriman');
        $param["nama_penerima"] = $this->input->post('nama_penerima');
        $param["keterangan"]    = $this->input->post('keterangan_tambahan');
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $param["jam_kirim"]     =  $this->input->post('jam_kirim');
        $param["lokasi_kirim"]  = $this->input->post("like_alamat");
        //print_r($param);exit();
        $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_alamatkirim", $param, array(CURLOPT_BUFFERSIZE => 100)));
        if($hasil){
            return $hasil->message;
        }else{
            return 0;
        }
    }
    /**
     * [simpanleasing_spk description]
     * @param  [type] $spkid [description]
     * @return [type]        [description]
     * added on 10-04-2018
     */
    public function simpanleasing_spk($spkid){
        $param = array();
        $param["spk_id"]    = $spkid;
        $param["kd_fincoy"] = $this->input->post('kd_fincom');
        $param["uang_muka"] = (double)str_replace(",","", $this->input->post('uang_muka'));
        $param["bunga"]     = (double)str_replace(",","", $this->input->post('bunga'));
        $param["adm"]       = (double)str_replace(",","", $this->input->post('biaya_adm'));
        $param["jangka_waktu"]   = $this->input->post('jangka_waktu');
        $param["jumlah_angsuran"]   = (double)str_replace(",","", $this->input->post('jumlah_angsuran'));
        $param["jatuh_tempo"]   = $this->input->post('jatuh_tempo');
        $param["keterangan"]= $this->input->post('keterangan');
        $param["created_by"]= $this->session->userdata("user_id");
        $param["hasil"] = $this->input->post('hasil');
        $param["tanggal"]=date('d/m/Y');
        $param["alasan_paksa"]= $this->input->post("alasan_maksa");
        $param["type_credit"] = $this->input->post("type_credit");
        if($param["kd_fincoy"]!=''){
            $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_leasing", $param, array(CURLOPT_BUFFERSIZE => 100)));
            if($hasil->recordexists==TRUE){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_leasing", $param, array(CURLOPT_BUFFERSIZE => 100)));
            }
            if($hasil){
                return $hasil;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    /**
     * [simpan_saleskupon description]
     * @param  [type] $data  [description]
     * @param  [type] $nospk [description]
     * @return [type]        [description]
     */
    function simpan_saleskupon($data,$nospk){
        $hasil="";
        if($data){
            $kdkp=substr($data["kd_saleskupon"],-1);
            $kd_saleskupond = str_replace(",","','",$data["kd_saleskupon"]);//,-1)):$data["kd_saleskupon"];
            $param=array('custom'=> "KD_SALESKUPON IN('".$kd_saleskupond."') AND KD_TYPEMOTOR IN('".$data["kd_typemotor"]."')");
            $kupon= json_decode($this->curl->simple_get(API_URL."/api/setup/kupon",$param));
            if($kupon){
                if($kupon->totaldata > 0){
                    foreach ($kupon->message as $key => $value) {
                        $param=array();
                        $param["no_spk"]            = $nospk;
                        $param['kd_saleskupon']     = $value->KD_SALESKUPON;
                        $param['nama_saleskupon']   = $value->NAMA_SALESKUPON;
                        $param['start_date']        = $value->START_DATE;
                        $param['end_date']          = $value->END_DATE;
                        $param['end_claim']         = $value->END_CLAIM;
                        $param['no_perkiraan']      = $value->NO_PERKIRAAN;
                        $param['no_subperkiraan']   = $value->NO_SUBPERKIRAAN;    
                        $param['kd_typemotor']      = $value->KD_TYPEMOTOR;
                        $param['top1']              = $value->TOP1; 
                        $param['top2']              = $value->TOP2;
                        $param['nilai']             = $value->NILAI;
                        $param['created_by']        = $this->session->userdata("user_id");
                        $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_saleskupon", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        if($hasil){
                            if($hasil->recordexists==true){
                                $param['lastmodified_by'] = $this->session->userdata("user_id");
                                $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_saleskupon", $param, array(CURLOPT_BUFFERSIZE => 10)));
                            }
                        }
                    }
                }
            }
        }
        return $hasil;
    }
    /**
     * [simpan_salesprogram description]
     * @param  [type] $data  [description]
     * @param  [type] $nospk [description]
     * @return [type]        [description]
     */
    function simpan_salesprogram($data,$nospk){
        if($data){
            $param=array('custom'=> "KD_SALESPROGRAM IN('".$data["kd_salesprogram"]."') AND KD_TYPEMOTOR IN('".$data["kd_typemotor"]."')");
            $kupon= json_decode($this->curl->simple_get(API_URL."/api/setup/salesprogram",$param));
            //var_dump($kupon);
            if($kupon){
                if($kupon->totaldata > 0){
                    foreach ($kupon->message as $key => $value) {
                        $param=array(
                            'no_spk' => $nospk,
                            'kd_salesprogram' => $value->KD_SALESPROGRAM,
                            'nama_salesprogram' =>$value->NAMA_SALESPROGRAM,
                            'tipe_salesprogram' => $value->TIPE_SALESPROGRAM,
                            'kd_salesprogramahm' => $value->KD_SALESPROGRAMAHM,
                            'no_suratsp' => $value->NO_SURATSP,
                            'salesprogram_khusus' => $value->SALESPROGRAM_KHUSUS,
                            'salesprogram_gift' => $value->SALESPROGRAM_GIFT,
                            'salesprogram_cabang' => $value->SALESPROGRAM_CABANG,
                            'start_date' => $value->START_DATE,
                            'pot_start' => $value->POT_START,
                            'pot_end' => $value->POT_END,
                            'ssu_start' => $value->SSU_START,
                            'ssu_end' => $value->SSU_END,
                            'end_date' => $value->END_DATE,
                            'end_claim' => $value->END_CLAIM,
                            'kd_typemotor' => $value->KD_TYPEMOTOR,
                            'qty' => $value->QTY,
                            'sk_ahm' => $value->SK_AHM,
                            'sk_md' => $value->SK_MD,
                            'sk_sd' => $value->SK_SD,
                            'sk_finance' => $value->SK_FINANCE,
                            'sc_ahm' => $value->SC_AHM,
                            'sc_md' => $value->SC_MD,
                            'sc_sd' => $value->SC_SD,
                            'cb_ahm' => $value->CB_AHM,
                            'cb_md' => $value->CB_MD,
                            'cb_sd' => $value->CB_SD,
                            'pot_faktur' => $value->POT_FAKTUR,
                            'cash_tempo' => $value->CASH_TEMPO,
                            'split_otr' => $value->SPLIT_OTR,
                            'split_otr2' => $value->SPLIT_OTR2,
                            'hadiah_langsung' => $value->HADIAH_LANGSUNG,
                            'harga_kontrak' =>$value->HARGA_KONTRAK,
                            'fee' => $value->FEE,
                            'pengurusan_stnk' => $value->PENGURUSAN_STNK,
                            'pengurusan_bpkb' => $value->PENGURUSAN_BPKB,
                            'no_po' => $value->NO_PO,
                            'min_sk_sd' => $value->MIN_SK_SD,
                            'min_sc_sd' => $value->MIN_SC_SD,
                            'dp_otr' => $value->DP_OTR,
                            'tambahan_finance' => $value->TAMBAHAN_FINANCE,
                            'tambahan_md' => $value->TAMBAHAN_MD,
                            'tambahan_sd' => $value->TAMBAHAN_SD,
                            'tunda_faktur' => $value->TUNDA_FAKTUR,
                            'hadiah_langsung2' => $value->HADIAH_LANGSUNG2,
                            'keterangan_hadiah' => $value->KETERANGAN_HADIAH,
                            'tambahan_ahm' => $value->TAMBAHAN_AHM,
                            'created_by' => $this->session->userdata("user_id")
                        );
                        $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_salesprogram", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        if($hasil){
                            if($hasil->recordexists==true){
                                $param['lastmodified_by'] = $this->session->userdata("user_id");
                                $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_salesprogram", $param, array(CURLOPT_BUFFERSIZE => 10)));
                            }
                        }
                        //var_dump($hasil);exit();
                    }
                }
            }
        }
    }
    /**
     * [simpan_salesbundling description]
     * @param  [type] $data  [description]
     * @param  [type] $nospk [description]
     * @return [type]        [description]
     */
    function simpan_salesbundling($data,$nospk){
        $param=array();
        if($data){
            $param=array("KD_BUNDLING" => $data["kd_bundling"]);
            $bundling =json_decode($this->curl->simple_get(API_URL."/api/master/bundling_detail",$param));
            if($bundling){
                if($bundling->totaldata > 0){
                    foreach ($bundling->message as $key => $value) {
                        $parameter=array(
                            'no_spk'    => $nospk,
                            'kd_bundling' => $value->KD_BUNDLING,
                            'kd_item'   => $value->KD_ITEM,
                            'nama_item' => $value->NAMA_ITEM,
                            'keterangan' => $value->GROUP_BUNDLING,
                            'jml_item'  => $value->JUMLAH,
                            'het_item'  => $value->HARGA_JUAL,
                            'created_by' => $this->session->userdata('user_id')
                        );
                        $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_salesbundling", $param, array(CURLOPT_BUFFERSIZE => 10)));
                        //var_dump($hasil);exit();
                        if($hasil){
                            if($hasil->recordexists==true){
                                $param['lastmodified_by'] = $this->session->userdata("user_id");
                                $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_salesbundling", $param, array(CURLOPT_BUFFERSIZE => 10)));
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * [guestbook_upd_status description]
     * @param  [type] $nospk [description]
     * @return [type]        [description]
     */
    function guestbook_upd_status($nospk){
            $param=array(
                'spk_no'    => $nospk,
                'guest_no'  => $this->input->post("guest_no"),
                'lastmodified_by'=>$this->session->userdata("user_id")
            );
            $hasilx = json_decode($this->curl->simple_put(API_URL . "/api/sales/guestbooksts", $param, array(CURLOPT_BUFFERSIZE => 10)));
            return $hasilx;
    }
    /**
     * [update_customer description]
     * @param  [type] $kdcust [description]
     * @return [type]         [description]
     */
    function update_customer($kdcust){
        $param = array(
            'kd_customer'   => $kdcust,
            'nama_customer' => $this->input->post("nama_customer"),
            'jenis_kelamin' => $this->input->post("kd_jeniskelamin"),
            'tgl_lahir'     => $this->input->post("tgl_lahir"),
            'no_ktp'        => $this->input->post("nomor_ktp"),
            'no_npwp'       => $this->input->post("npwp_customer"),
            'alamat_surat'  => $this->input->post("alamat_cust"),
            'kelurahan'     => $this->input->post("kd_desa"),
            'kd_kecamatan'  => $this->input->post("kd_kecamatan"),
            'kd_kota'       => $this->input->post("kd_kabupaten"),
            'kode_possurat' => $this->input->post("kode_pos"),
            'kd_propinsi'   => $this->input->post("kd_propinsi"),
            'kd_agama'      => $this->input->post("kd_agama"),
            'no_hp'         => $this->input->post("no_hp"),
            'email'         => $this->input->post("email_customer"),
            'akun_fb'       => $this->input->post("kd_facebook"),
            'akun_twitter'  => $this->input->post("kd_twiter"),
            'akun_instagram'=> $this->input->post("kd_instagram"),
            'akun_youtube'  => $this->input->post("kd_youtube"),
            'hobi'          => $this->input->post("kd_hobby"),
            'lastmodified_by' => $this->session->userdata("user_id")."| spk",
            'upline'        => $this->input->post("upline")
        );
        $hasilx = json_decode($this->curl->simple_put(API_URL . "/api/master_general/customergb", $param, array(CURLOPT_BUFFERSIZE => 10)));
        return $hasilx;
    }
    /**
     * [nomor_spk description]
     * @return [type] [description]
     */
    function nomor_spk(){
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => 'PK',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tgl_spk'), 6, 4),
           // 'bulan_docno' => (int)substr($this->input->post('tgl_spk'), 3, 2),
            'limit' => 1,
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = substr($this->input->post('tgl_spk'), 3, 2);
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpo == 0) {
            $nopo = "PK" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = "PK" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
        }
        //var_dump($nopo);exit();
        return $nopo;
    }
    /**
     * [diskon description]
     * @param  string  $type_customer [description]
     * @param  boolean $echo          [description]
     * @return [type]                 [description]
     */
    public function diskon($type_customer='',$echo=TRUE){
        $param=array();$result="[]";
        $param["custom"]    =" START_DATE <='".date("Ymd")."' AND END_DATE >='".date('Ymd')."'";
        $param["kd_dealer"] = $this->session->userdata("kd_dealer");
        //check tipe customer
        if($type_customer){
            $param["type_customer"] = $type_customer;
        }
        $data     = json_decode($this->curl->simple_get(API_URL."/api/setup/diskon",$param));
        if($data){
            if($data->totaldata > 0){
                $result= json_encode($data->message);
            }
        }
        if($echo==FALSE){
            return $data;
        }else{
            echo $result;
        }
    }
    /**
     * [list_saleskupon description]
     * @return [type] [description]
     */
    public function list_saleskupon(){
        $result="";
        $kdl=$this->input->get('kd_saleskupon');
        $kdl=(substr($kdl, -1)===",")?substr($kdl,0, -1):$kdl;
        $param=array(
            'custom' =>"KD_SALESKUPON IN('".str_replace(",","','",$kdl)."')",
            'field'  =>"KD_SALESKUPON,NAMA_SALESKUPON",
            'groupby'=>TRUE,
            'orderby'=>"KD_SALESKUPON"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/setup/kupon",$param));
        if($data){
            if($data->totaldata>0){
                $result .="<li class='list-group-item warning'>Sales Kupon</li>";
                foreach ($data->message as $key => $value) {
                    $result .="<li class='list-group-item' id='s_".$value->KD_SALESKUPON."'>";
                    $result .="[".$value->KD_SALESKUPON."] ".$value->NAMA_SALESKUPON;
                    $result .="<span class='pull-right'><a style='cursor:pointer' ";
                    $result .="onclick=\"hps_kupon('".$value->KD_SALESKUPON."')\" title='hapus item ini'>";
                    $result .="<i class='fa fa-close'></a></i></span></li>";
                }
            }
        }
        //print_r($param);
        echo $result;
    }
    /**
     * [list_salesprogram description]
     * @return [type] [description]
     */
    public function list_salesprogram(){
        $result="";
        $kdl=$this->input->get('kd_salesprogram');
        $kdl=(substr($kdl, -1)===",")?substr($kdl,0, -1):$kdl;
        $param=array(
            'custom' =>"KD_SALESPROGRAM IN('".str_replace(",","','",$kdl)."')",
            'field'  =>"KD_SALESPROGRAM,NAMA_SALESPROGRAM",
            'groupby'=>TRUE,
            'orderby'=>"KD_SALESPROGRAM"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/setup/salesprogram",$param));
        if($data){
            if($data->totaldata>0){
                $result .="<li class='list-group-item info'>Sales Program</li>";
                foreach ($data->message as $key => $value) {
                    $result .="<li class='list-group-item' id='s_".$value->KD_SALESPROGRAM."'>";
                    $result .="[".$value->KD_SALESPROGRAM."] ".$value->NAMA_SALESPROGRAM;
                    $result .="</li>";
                    $result .="::".$value->KD_SALESPROGRAM;
                }
            }
        }
        echo $result;
    }
    public function list_salesprogram_new(){
        $result="";
        $param=array(
            'no_spk' =>$this->input->get('no_spk'),
            'field'  =>"KD_SALESPROGRAM,NAMA_SALESPROGRAM",
            'groupby'=>TRUE,
            'orderby'=>"KD_SALESPROGRAM"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/spk/spk_salesprogram",$param));
        if($data){
            if($data->totaldata>0){
                $result .="<li class='list-group-item info'>Sales Program</li>";
                foreach ($data->message as $key => $value) {
                    $result .="<li class='list-group-item' id='s_".$value->KD_SALESPROGRAM."'>";
                    $result .="[".$value->KD_SALESPROGRAM."] ".$value->NAMA_SALESPROGRAM;
                    $result .="</li>";
                    $result .="::".$value->KD_SALESPROGRAM;
                }
            }
        }
        echo $result;
    }
    /**
     * [listbundling description]
     * @return [type] [description]
     */
    public function listbundling(){
        $result="";
        $kdl=$this->input->get('kd_salesprogram');
        $kdl=(substr($kdl, -1)===",")?substr($kdl,0, -1):$kdl;
        $param=array(
            'custom' =>"KD_BUNDLING IN('".str_replace(",","','",$kdl)."')",
            'field'  =>"KD_BUNDLING,NAMA_BUNDLING",
            'groupby'=>TRUE,
            'orderby'=>"KD_BUNDLING"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/master/bundling",$param));
        if($data){
            if($data->totaldata>0){
                $result .="<li class='list-group-item success'>Bundling Program</li>";
                foreach ($data->message as $key => $value) {
                    $result .="<li class='list-group-item' id='s_".$value->KD_BUNDLING."'>";
                    $result .="[".$value->KD_BUNDLING."] ".$value->NAMA_BUNDLING;
                    $result .="</li>";
                    $result .="::".$value->KD_BUNDLING;
                }
            }
        }
        //print_r($param);
        echo $result;
    }
    /**
     * [saleskupon description]
     * @param  boolean $echo         [description]
     * @param  string  $kd_typemotor [description]
     * @return [type]                [description]
     */
    public function saleskupon($echo=FALSE,$kd_typemotor=''){
        $param = array();
        $kd_typemotor=($kd_typemotor=='')?$this->input->get("kd_item"):$kd_typemotor;
        //$param["custom"] ="(KD_DEALER  IN('".$this->session->userdata('kd_dealer')."','ALL') AND START_DATE <='".date('Ymd')."' AND END_DATE >='".date('Ymd')."'";
        $param["custom"]="";
        if($kd_typemotor!=''){
            $param["custom"] .=" KD_TYPEMOTOR IN('".$kd_typemotor."')";
        }
        if($this->input->get('kd_leasing')){
            $param["custom"] .=" AND KD_LEASING IN('".$this->input->get("kd_leasing")."','ALL')";
        }
        if($this->input->get("kd_saleskupon")){
            $param["kd_saleskupon"]= $this->input->get("kd_saleskupon");
        }
        if($this->input->get("kd_item")){
            $param["kd_typemotor"]= $this->input->get("kd_item");
        }
        $data     = json_decode($this->curl->simple_get(API_URL."/api/laporan/saleskupon",$param));
       /// var_dump($data);
        $result="[]";
        if($data){
            if($data->totaldata>0){
                if($echo==FALSE && $this->input->get('echo')==false ){
                    return $data;
                }else{
                    echo ($data->totaldata > 0)?json_encode($data->message):"[]";
                }
            }else{
                if($echo==TRUE){
                    echo "[]";
                }
            }
        }
    }
    /**
     * [detailsales description]
     * @return [type] [description]
     */
    public function detailsales(){
        $result="[]";$data=array();
        if($this->input->get('kd_saleskupon')){
            $param=array("kd_saleskupon" => $this->input->get("kd_saleskupon"));
            $data=json_decode($this->curl->simple_get(API_URL."/api/setup/kupon",$param));
        }
        if($this->input->get('kd_salesprogram')){
            $param=array("kd_salesprogram" => $this->input->get("kd_salesprogram"));
            $data=json_decode($this->curl->simple_get(API_URL."/api/setup/salesprogram",$param));
        }
        //var_dump($data);
        if($data){
            if($data->totaldata>0){
                $result=json_encode($data->message);
            }
        }
        echo $result;
    }
    /**
     * [saleskupon_new description]
     * @param  boolean $echo [description]
     * @return [type]        [description]
     */
    public function saleskupon_new($echo=false,$debug=null){
        $kupon_kota=array(); $arr_kk=array();
        $kupon_leasing=array();$arr_kl=array();
        $sales_kupon=array();$arr_data=array();$data="[]";
        $param=array(
            'custom'=>"KD_DEALER IN('".$this->session->userdata("kd_dealer")."','ALL')",
            'field' =>"SETUP_SALESKUPONKOTA.KD_SALESKUPON",
            'jointable' =>array(array("SETUP_SALESKUPON K","K.KD_SALESKUPON=SETUP_SALESKUPONKOTA.KD_SALESKUPON","LEFT")),
            'groupby' =>TRUE
        );
        $param["custom"] .="AND (CONVERT(CHAR,START_DATE,112) <=CONVERT(CHAR,GETDATE(),112) AND CONVERT(CHAR,END_DATE,112) >=CONVERT(CHAR,GETDATE(),112))";
        $kupon_kota=json_decode($this->curl->simple_get(API_URL."/api/setup/saleskuponkota",$param));
        if($kupon_kota){
            if($kupon_kota->totaldata>0){
                foreach ($kupon_kota->message as $key => $value) {
                    $arr_kk[]=$value->KD_SALESKUPON;
                }
            }
        }
        $param=array(
            'custom'=>"KD_LEASING IN('".$this->input->get("kd_leasing")."','ALL')",
            'field' =>"SETUP_SALESKUPONLEASING.KD_SALESKUPON",
            'jointable' =>array(array("SETUP_SALESKUPONLEASING K","K.KD_SALESKUPON=SETUP_SALESKUPONLEASING.KD_SALESKUPON","LEFT")),
            'groupby' =>TRUE
        );
        $param["custom"] .="AND (CONVERT(CHAR,START_DATE,112) <=CONVERT(CHAR,GETDATE(),112) AND CONVERT(CHAR,END_DATE,112) >=CONVERT(CHAR,GETDATE(),112))";
        $kupon_leasing=json_decode($this->curl->simple_get(API_URL."/api/setup/saleskuponleasing",$param));
        if($kupon_leasing){
            if($kupon_leasing->totaldata>0){
                foreach ($kupon_leasing->message as $key => $value) {
                    $arr_kl[]=$value->KD_SALESKUPON;
                }
            }
        }
        $arr_data=array_merge($arr_kk,$arr_kl);
        if($debug){
            $kd_kupon = str_replace(",,","",$this->input->get("kd_saleskupon"));
            $arr_data =explode(",",$kd_kupon);
        }
       if(count($arr_data)>0){
            $paramxx=array(
                'jointable' => array(
                    array("SETUP_SALESKUPONKOTA SK","SK.KD_SALESKUPON=SETUP_SALESKUPON.KD_SALESKUPON","LEFT"),
                    array("SETUP_SALESKUPONLEASING SL","SL.KD_SALESKUPON=SETUP_SALESKUPON.KD_SALESKUPON","LEFT")
                ),
                'custom' =>"SETUP_SALESKUPON.KD_SALESKUPON IN('".implode("','", $arr_data)."') AND START_DATE <='".date('Ymd')."' AND END_DATE >='".date('Ymd')."' AND KD_DEALER IN('".$this->session->userdata("kd_dealer")."','ALL')",
                'field'  =>"SETUP_SALESKUPON.KD_SALESKUPON,SETUP_SALESKUPON.NAMA_SALESKUPON,TOP1,TOP2",
                'groupby'=> TRUE
            );
            if($this->input->get("kd_leasing")){
                $paramxx["custom"] .=" AND KD_LEASING IN('".$this->input->get("kd_leasing")."','ALL')";
            }
            if($this->input->get('jangka_waktu')){
                $paramxx["custom"] .=" AND TOP2 >=".$this->input->get('jangka_waktu')." AND TOP1 <=".$this->input->get('jangka_waktu');
            }
            if($this->input->get('kd_item')){
            }
            $data    = json_decode($this->curl->simple_get(API_URL."/api/setup/kupon",$paramxx));
            //var_dump($data->param);exit();
            if($data){
                if($data->status){
                    if($echo==false && $this->input->get('echo')==false){
                        return $data->message;
                    }else{
                        echo json_encode($data->message);
                    }
                //echo json_encode($data);
                }else{
                    if($echo==false && $this->input->get('echo')==false){
                        return $data;
                    }else{
                        echo json_encode($data);
                    }
                }
            }else{
                if($echo==false && $this->input->get('echo')==false){
                    return $data;
                }else{
                    echo json_encode($data);
                }
                // print_r($data);
            }
        }
    }
    /**
     * [saleskuponkota description]
     * @param  boolean $echo      [description]
     * @param  string  $typemotor [description]
     * @param  string  $type_jual [description]
     * @return [type]             [description]
     */
    public function saleskuponkota($echo=FALSE,$typemotor='',$type_jual='Kredit'){
        $kota=array();$leasing=array();
        $typemotore=($typemotor!='')?",SK.KD_TYPEMOTOR":"";
        $param = array(
            'jointable' => array(
                array('SETUP_SALESKUPON AS SK','SK.KD_SALESKUPON=SETUP_SALESKUPONKOTA.KD_SALESKUPON','LEFT',"KD_SALESKUPON,START_DATE,END_DATE$typemotore")
            ),
            'field' =>"SETUP_SALESKUPONKOTA.KD_SALESKUPON,SETUP_SALESKUPONKOTA.NAMA_SALESKUPON,SETUP_SALESKUPONKOTA.KD_DEALER$typemotore",
            'groupby'   => TRUE,
            'orderby'   =>"SETUP_SALESKUPONKOTA.KD_SALESKUPON"
        );
        $param["custom"] ="(KD_DEALER ='".$this->session->userdata('kd_dealer')."' OR KD_DEALER='ALL') AND SK.START_DATE <='".date('Ymd')."' AND SK.END_DATE >='".date('Ymd')."'";
        if($typemotor!=''){
            $param["custom"] .=" AND SK.KD_TYPEMOTOR IN('".str_replace(",", "','", substr($typemotor, 0,-1))."')";
        }
        /*exit();*/
        $data["kota"]     = json_decode($this->curl->simple_get(API_URL."/api/setup/saleskuponkota",$param));
        if($data["kota"]){
            if($data["kota"]->status==TRUE){
                $kota= $data["kota"]->message;
            }
        }
        if($type_jual=='Kredit'){
            $data["leasing"]= $this->saleskuponleasing($echo);
            if($data["leasing"]){
                if($data["leasing"]->status==TRUE){
                    $leasing=$data["leasing"]->message;
                }
            }
        }
        if($echo==FALSE && $this->input->get('echo')==false){
            return ($type_jual=='Kredit')?(array_merge(($kota),($leasing))):$kota;
        }else{
            echo ($type_jual=='Kredit')?(json_encode(array_merge(($kota),($leasing)))):json_encode($kota);
        }
    }
    /**
     * [saleskuponleasing description]
     * @param  boolean $echo [description]
     * @return [type]        [description]
     */
    public function saleskuponleasing($echo=FALSE){
        $param = array(
            'jointable' => array(
                array('SETUP_SALESKUPON AS SK','SK.KD_SALESKUPON=SETUP_SALESKUPONLEASING.KD_SALESKUPON','LEFT',"KD_SALESKUPON,START_DATE,END_DATE")
            ),
            'field' =>"SETUP_SALESKUPONLEASING.KD_SALESKUPON,SETUP_SALESKUPONLEASING.NAMA_SALESKUPON,SETUP_SALESKUPONLEASING.KD_LEASING",
            'groupby'   => TRUE,
            'orderby'   =>"SETUP_SALESKUPONLEASING.KD_SALESKUPON"
        );
        $param["custom"] =" SK.START_DATE <='".date('Ymd')."' AND SK.END_DATE >='".date('Ymd')."'";
        if($this->input->get('kd_leasing')){
            $param["custom"] .=" AND ( KD_LEASING='".$this->input->get("kd_leasing")."' OR KD_LEASING='ALL')";
        }
        if($this->input->get('kd_saleskupon')){
            $param['kd_saleskupon'] = $this->input->get('kd_leasing');
        }
        $data     = json_decode($this->curl->simple_get(API_URL."/api/setup/saleskuponleasing",$param));
        return $data;
    }
    /**
     * [bundlingmotor description]
     * @return [type] [description]
     */
    function bundlingmotor(){
        $kd_typemotor="";$kd_warna="";
        if($this->input->get('kd_item')){
            $kd_item=explode("-",$this->input->get('kd_item'));
            $kd_typemotor = $kd_item[0];
            $kd_warna   = $kd_item[1];
        }
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom'    => "START_DATE <='".date("Ymd")."' AND END_DATE >='".date("Ymd")."'"
        );
        if($this->input->get("kd_bundling")){
            $param["kd_bundling"] = $this->input->get("kd_bundling");
        }
        if($kd_typemotor!='' && $kd_warna!=''){
            $param["custom"] .= " AND (KD_TYPEMOTOR IN('".str_replace(",", "','", $kd_typemotor)."') AND KD_WARNA IN('".str_replace(",", "','", $kd_warna)."')) OR (KD_TYPEMOTOR IN('".str_replace(",", "','", $kd_typemotor)."') AND KD_WARNA='')";
        }
        $data = json_decode($this->curl->simple_get(API_URL."/api/master/bundling",$param));
        if($data){
            if($data->totaldata>0){
                echo json_encode($data->message);
            }else{
                echo "[]";
            }
        }else{
            echo "[]"; 
        }
    }
    /**
     * [spkkendaraan_delete description]
     * @return [type] [description]
     */
    public function spkkendaraan_delete(){
        $param=array(
            'id'    => $this->input->post('id'),
            'lastmodified_by'=> $this->session->userdata('user_id')
        );
        $data=json_decode($this->curl->simple_delete(API_URL."/api/spk/spk_detailkendaraan",$param));
        if($data){
            echo $data->message;
        }else{
            echo "0";
        }
    }
    /**
     * [spkkendaraan description]
     * @param  boolean $javascript [description]
     * @param  string  $spkid      [description]
     * @return [type]              [description]
     */
    public function spkkendaraan($javascript=TRUE,$spkid=null){
        $param=array();
        if($javascript==TRUE){
            $param["spk_id"] = $this->input->get('spk_id');
        }else{
            $param=array();
        }
        if($spkid!='' && $javascript==FALSE){
            $param=array('custom'=>"SPK_ID IN('".str_replace(",", "','", substr($spkid, 0,(strlen($spkid)-1)))."')");
        }
        //print_r($param);
        $data=json_decode($this->curl->simple_get(API_URL."/api/laporan/spkview_kendaraan",$param));
        //var_dump($data);exit();
        if($data){
            if($data->totaldata > 0){
                if($javascript){
                    echo json_encode($data);
                }else{
                    return $data;
                }
            }else{
                echo "[]";
            }
        }else{
            echo "[]";
        }
    }
    /**
     * [spkquiz description]
     * @return [type] [description]
     */
    public function spkquiz(){
        $param=array();
        $param["spk_id"] = $this->input->get('spk_id');
        $data=json_decode($this->curl->simple_get(API_URL."/api/spk/trans_kuisoner",$param));
        if($data){
            if($data->totaldata > 0){
                echo json_encode($data->message);
            }else{
                echo "[]";
            }
        }else{
            echo "[]";
        }
    }
    /**
     * [delete_spk description]
     * @param  [type] $no_spk [description]
     * @return [type]         [description]
     */
    public function delete_spk($no_spk=null){
        $param = array(
            'no_spk' => $no_spk,
            'lastmodified_by' => $this->session->userdata("user_id")
         );
        $data=json_decode($this->curl->simple_delete(API_URL."/api/spk/spk",$param));
        print_r($param);
        var_dump($data);exit();
        $this->data_output($data, 'delete', base_url('spk/spk'));
    }
    /**
     * [spk_typeahead description]
     * @return [type] [description]
     */
    public function spk_typeahead()
    {}
    /**
     * [salesman description]
     * @return [type] [description]
     */
    public function salesman(){
        $param=array(
            'keyword' => $this->input->post('keyword'),
            'status_sales'=>'A'
        );
        $param['custom'] ="KD_DEALER='".$this->session->userdata("kd_dealer")."'";
        if($this->input->post('group_sales')){
            $param["custom"] .=" AND GROUP_SALES='".$this->input->post('group_sales')."'";
        }
        $id=$this->input->post('lok');
        $salesman=json_decode($this->curl->simple_get(API_URL."/api/sales/salesman",$param));
        //var_dump($salesman);
        if($salesman){
            $n=0;
            if(($salesman->totaldata >0)){
                foreach ($salesman->message as $key => $value) {
                    $n++;
                    echo "<tr onclick=\"dropdown_sales".$id."('".$value->KD_SALES."','".rtrim($value->NAMA_SALES)."');\">
                          <td>".$n."</td>
                          <td>".$value->KD_SALES."</td>
                          <td>".$value->KD_HSALES."</td>
                          <td>".$value->NAMA_SALES."</td>
                          </tr>";
                }
            }else{
                echo "<tr><td colspan='4' class='table-nowrap'> <i class='fa fa-info'></i> ".$salesman->message."</td></tr>";
            }
        }else{
             echo "<tr><td colspan='4' class='table-nowrap'> <i class='fa fa-info'></i> Data tidak ditemukan</td></tr>";
        }
    }
    /**
     * [makelar description]
     * @return [type] [description]
     */
    public function makelar(){
       $param=array(
            'keyword' => $this->input->post('keyword')
        ); 
       if($this->session->userdata("kd_dealer")){
            //$param['custom'] ="KD_DEALER='".$this->session->userdata("kd_dealer")."'";
       }
       //print_r($param);
       $salesman=json_decode($this->curl->simple_get(API_URL."/api/setup/makelar",$param));
        //var_dump($salesman);
        if($salesman){
            $n=0;
            if($salesman->totaldata > 0){
                foreach ($salesman->message as $key => $value) {
                    $n++;
                    echo "<tr onclick=\"dropdown_sales('".$value->KD_MAKELAR."','".rtrim($value->NAMA_MAKELAR)."');\">
                          <td>".$n."</td>
                          <td>".$value->KD_MAKELAR."</td>
                          <td>&nbsp;</td>
                          <td>".$value->NAMA_MAKELAR."</td>
                          </tr>";
                }
            }else{
                echo "<tr><td colspan='4' class='table-nowrap'> <i class='fa fa-info'></i> ".$salesman->message."</td></tr>";
            }
        }else{
             echo "<tr><td colspan='4' class='table-nowrap'> <i class='fa fa-info'></i> Data tidak ditemukan</td></tr>";
        }
    }
    /**
     * [detailguest description]
     * @return [type] [description]
     */
    public function detailguest() {
        $param = array(
            "custom" => "ID=".$this->input->get("id")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        $result = array();
        if ($data) {
            if ($data->totaldata > 0) {
                $result = json_encode($data->message);
            } else {
                $result = json_encode($data);
            }
        }
        echo $result;
    }
    /**
     * [aprove_leasing description]
     * @return [type] [description]
     */
    public function aprove_leasing(){
        $param = array();
        $param["spk_id"]    = $this->input->post('espekaid');
        $param["kd_fincoy"] = $this->input->post('kd_fincom');
        $param["uang_muka"] = (double)str_replace(",","", $this->input->post('uang_muka'));
        $param["bunga"]     = (double)str_replace(",","", $this->input->post('bunga'));
        $param["adm"]       = (double)str_replace(",","", $this->input->post('biaya_adm'));
        $param["jangka_waktu"]   = $this->input->post('jangka_waktu');
        $param["jumlah_angsuran"]   = (double)str_replace(",","", $this->input->post('jumlah_angsuran'));
        $param["jatuh_tempo"]   = $this->input->post('jatuh_tempo');
        $param["keterangan"]= $this->input->post('ket');
        $param["created_by"]= $this->session->userdata("user_id");
        $param["hasil"] = $this->input->post('hasil');
        $param["tanggal"]=date('d/m/Y');
        $param["alasan_paksa"]= $this->input->post("alasan_maksa");
        $param["type_credit"] = $this->input->post("type_credit");
        //print_r($param);
        if($param["kd_fincoy"]!=''){
            $hasil = json_decode($this->curl->simple_post(API_URL."/api/spk/spk_leasing", $param, array(CURLOPT_BUFFERSIZE => 10)));
            if($hasil->recordexists==TRUE){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spk_leasing", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
            if($hasil){
                echo $hasil->message;
            }else{
                echo "[]";
            }
        }else{
            echo "[]";
        }
    }
    /**
     * [hargamotor description]
     * @param  [type] $debug [description]
     * @return [type]        [description]
     */
    public function hargamotor($debug=null){
        $param=array(
            'field'     => 'SETUP_HARGAMOTOR.*,MD.KD_DEALER',
            'jointable' => array(
                            array("MASTER_WILAYAH MW","MW.KD_WILAYAH=SETUP_HARGAMOTOR.KD_WILAYAH","LEFT"),
                            array("MASTER_DEALER MD","MD.KD_PROPINSI=MW.KD_PROPINSI","LEFT")
                           ),
            'custom'    => "MD.KD_DEALER='".$this->session->userdata('kd_dealer')."' AND SETUP_HARGAMOTOR.KD_ITEM='".$this->input->get("kd_item")."'"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/inventori/hargamotor",$param));
        if($data){
            if($data->totaldata > 0){
                echo json_encode($data->message);
            }else{
                echo "[]";
            }
        }else{
            echo "[]";
        }
        if($debug){
            var_dump($data);
        }
    }
    /**
     * [salesprogram description]
     * @param  string $kd_leasing   [description]
     * @param  string $kd_typemotor [description]
     * @return [type]               [description]
     */
    public function salesprogram($kd_leasing='',$kd_typemotor=''){
        $result="[]";
        $sp_cash=" ";
        $param = array(
            'jointable' =>array(
                            array("MASTER_COM_FINANCE MD","MD.KD_LEASING = SETUP_SALESPROGRAMLEASING.KD_LEASING","LEFT"),
                            array("SETUP_SALESPROGRAM AS SP","SP.KD_SALESPROGRAM = SETUP_SALESPROGRAMLEASING.KD_SALESPROGRAM","LEFT","KD_SALESPROGRAM,NAMA_SALESPROGRAM,START_DATE,END_DATE")
                        ),
            'field'     => 'SP.KD_SALESPROGRAM,SP.NAMA_SALESPROGRAM,SETUP_SALESPROGRAMLEASING.KD_LEASING',
            'groupby'   => true,
            'orderby'   => 'SP.KD_SALESPROGRAM'
         );
        $param["custom"] = "SP.START_DATE <='".date("Ymd")."' AND SP.END_DATE >='".date("Ymd")."'";
        $param["custom"] .=" AND SETUP_SALESPROGRAMLEASING.KD_LEASING IN('".$kd_leasing."','ALL')";
        $data   = json_decode($this->curl->simple_get(API_URL."/api/setup/salesprogramleasing",$param));
        if($data){
            if($data->totaldata>0){
                $result= json_encode($data->message);
            }
        }
        echo $result;
    }
    /**
     * [salesprogram_new description]
     * @param  [type] $debug [description]
     * @return [type]        [description]
     */
    function salesprogram_new($debug=null){
        $result="[]";
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_leasing' => $this->input->get("kd_leasing"),
            'kd_typemotor' => $this->input->get("kd_typemotor")
        );
        $data   = json_decode($this->curl->simple_get(API_URL."/api/setup/salesprogram_v",$param));
        
        if($data){
            if($data->totaldata>0){
                $result= json_encode($data->message);
            }
        }
        if($debug){
            //var_dump($data);
            echo $result;
        }else{
            return $data;
        }
    }
    /**
     * [dealer description]
     * @param  string $notin [description]
     * @return [type]        [description]
     */
    function dealer($notin=''){
        $result="[]";
        $param=array(
            'jointable' =>array(array("MASTER_WILAYAH MW","MW.KD_PROPINSI=MASTER_DEALER.KD_PROPINSI","LEFT"),
                array("MASTER_KABUPATEN MK","MK.KD_KABUPATEN=MASTER_DEALER.KD_KABUPATEN","LEFT")),
            'field'     =>"MASTER_DEALER.*,MW.KD_WILAYAH,MW.NAMA_WILAYAH,MK.NAMA_KABUPATEN"
        );
        if($notin=='true'){
            $param["custom"]= "KD_DEALER NOT IN('".$this->session->userdata("kd_dealer")."') AND MW.KD_WILAYAH ='".$this->session->userdata("kd_wilayah")."'";
            $param["orderby"]="CASE WHEN MASTER_DEALER.KD_KABUPATEN= (SELECT KD_KABUPATEN FROM MASTER_DEALER WHERE KD_DEALER='".$this->session->userdata("kd_dealer")."') THEN 0 ELSE MASTER_DEALER.KD_KABUPATEN END";
            $param["orderby"] = "MASTER_DEALER.NAMA_DEALER";
        }else{
            $param["kd_dealer"]= $this->session->userdata("kd_dealer");
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/1/1",$param));
        if($data){
            if($data->totaldata>0){
                $result= json_encode($data->message);
            }
        }
        echo $result;
    }
    /**
     * [hoby description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    function hoby($data=null){
        $result="[]";
        $data=array();$param=array('field' =>"KD_HOBBY,NAMA_HOBBY",'orderby' =>'NAMA_HOBBY');
        $data= json_decode($this->curl->simple_get(API_URL . "/api/master_general/hobby",$param));
        if($data){
            if($data->totaldata>0){
                $result= json_encode($data->message);
            }
        }
        if($data){
            return $data;
        }else{
            echo $result;
        }
    }
    /**
     * [spk_detail description]
     * @param  [type] $ajax [description]
     * @return [type]       [description]
     */
    function spk_detail($ajax=null){
        $data=array();$result="[]";
        $param = array(
            'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("TRANS_SPK_KENDARAAN_VIEW K","K.SPK_ID=TRANS_SPK.ID /*AND K.HASIL='Approve'*/","LEFT"),
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER = TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >=0 ","LEFT","KD_CUSTOMER,NAMA_CUSTOMER,ALAMAT_SURAT,ROW_STATUS"),
                array("MASTER_P_GROUPMOTOR AS G","G.KD_GROUPMOTOR=K.KD_TYPEMOTOR","LEFT","KD_GROUPMOTOR,KD_TYPEMOTOR,CATEGORY_MOTOR"),
                array("TRANS_SPK_SALESPROGRAM AS P","P.NO_SPK=TRANS_SPK.NO_SPK AND P.KD_SALESPROGRAM=K.KD_SALESPROGRAM AND P.ROW_STATUS >=0","LEFT")
                //array("TRANS_KUISIONER AS KU","")
                ),
            'custom' =>"LEN(RTRIM(ISNULL(K.KD_ITEM,''))) >0 AND ISNULL(TRANS_SPK.STATUS_SPK,0)=(CASE WHEN TRANS_SPK.JENIS_PENJUALAN=2 THEN 1 ELSE 0 END) ",          
            'orderby'=> "TRANS_SPK.NO_SPK"
        );
        if($this->input->get("nospk")){
            $param["no_spk"] = $this->input->get("nospk");
            $param["field"] = "TRANS_SPK.ID,TRANS_SPK.NO_SPK,TRANS_SPK.TGL_SPK,TRANS_SPK.KD_CUSTOMER,TRANS_SPK.TYPE_PENJUALAN,
                               TRANS_SPK.STATUS_SPK,K.*,G.KD_TYPEMOTOR AS TMP,MC.NAMA_CUSTOMER,UPPER(CATEGORY_MOTOR)GROUP_MOTOR,
                               (SELECT TOP 1 KD_AKUN FROM SETUP_ACC_JURNAL WHERE KD_TRANS='Penjualan Unit' AND 
                               TYPE_TRANS=UPPER(CATEGORY_MOTOR) AND JENIS_TRANS='SMH')KD_AKUN,ISNULL(P.SC_AHM,0)SC_AHM,
                               ISNULL(P.SC_MD,0)SC_MD,ISNULL(P.SC_SD,0)SC_SD,ISNULL(P.MIN_SC_SD,0)MIN_SC_SD,
                               ISNULL(P.SK_AHM,0)SK_AHM,ISNULL(P.SK_MD,0)SK_MD,ISNULL(P.SK_FINANCE,0)SK_FINANCE,
                               ISNULL(P.SK_SD,0)SK_SD,ISNULL(P.MIN_SK_SD,0)MIN_SK_SD,
                               ISNULL((P.SC_AHM+P.SC_MD+P.SC_SD),0) AS SUBSIDI";
        }else{
            $param["field"] = "TRANS_SPK.ID,TRANS_SPK.NO_SPK,TRANS_SPK.TYPE_PENJUALAN,MC.ALAMAT_SURAT,ISNULL(MC.NAMA_CUSTOMER,'Antar Dealer')NAMA_CUSTOMER";
            $param["groupby_text"]="TRANS_SPK.ID,TRANS_SPK.NO_SPK,TRANS_SPK.TYPE_PENJUALAN,MC.ALAMAT_SURAT,MC.NAMA_CUSTOMER";
        }
        if($this->input->get("kd_sales")){
            $param['jointable']= array(
                array("TRANS_SPK_KENDARAAN_VIEW K","K.SPK_ID=TRANS_SPK.ID","LEFT"),
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER = TRANS_SPK.KD_CUSTOMER","LEFT","KD_CUSTOMER,NAMA_CUSTOMER"),
                array("MASTER_P_GROUPMOTOR AS G","G.KD_GROUPMOTOR=K.KD_TYPEMOTOR","LEFT","KD_GROUPMOTOR,KD_TYPEMOTOR")
            );
            $param['custom']  = "K.KD_ITEM IS NOT NULL AND (TRANS_SPK.STATUS_SPK >= 0)";
            $param["kd_sales"] = $this->input->get("kd_sales");
            //$param["field"] ="TRANS_SPK.NO_SPK,K.NAMA_TYPEMOTOR,MC.NAMA_CUSTOMER";
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk",$param));
        if($data){
            if($data->totaldata>0){
                //check quiz
                if($this->input->get("nospk")){
                    $param=array(
                        'spk_id'    =>$data->message[0]->SPK_ID
                    );
                    $quiz=json_decode($this->curl->simple_get(API_URL . "/api/spk/trans_kuisoner",$param));
                    if($quiz){
                        if($quiz->totaldata==0){
                            $result= json_encode($quiz);
                        }else{
                            $result= json_encode($data);
                        }
                    }
                }else{
                    $result= json_encode($data->message);
                }
            }
        }
        if($ajax==true){
            // var_dump($data);
            $this->output->set_output(($result));
        }else{
            return $data;
        }
    }
    /**
     * [sales_in_spk description]
     * @param  string $tps [description]
     * @return [type]      [description]
     */
    function sales_in_spk($tps="",$debug=null){
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'field'     =>"PENJUALAN_VIA,KD_SALES,KD_DEALER,KD_MAINDEALER,CASE PENJUALAN_VIA WHEN 'SC' THEN (SELECT TOP 1 NAMA_SALES FROM MASTER_SALESMAN WHERE KD_SALES=TRANS_SPK.KD_SALES AND ROW_STATUS >=0) WHEN 'SM' THEN (SELECT TOP 1 NAMA_SALES FROM MASTER_SALESMAN WHERE KD_SALES=TRANS_SPK.KD_SALES AND ROW_STATUS >=0) WHEN 'MK' THEN (SELECT TOP 1 NAMA_MAKELAR FROM MASTER_MAKELAR WHERE KD_SALES=TRANS_SPK.KD_SALES AND ROW_STATUS >=0) WHEN 'TP' THEN KD_SALES ELSE '' END NAMA_SALES",
            'penjualan_via' =>$this->input->get('tps'),
            'groupby'   =>TRUE,
            'order by'  =>"PENJUALAN_VIA",
            'custon'    =>"(KD_SALES !='' OR KD_SALES IS NOT NULL)"
        );
        $data=json_decode($this->curl->simple_get(API_URL."/api/spk/spk",$param));
        if($debug){
            var_dump($data);
            print_r($param);
            exit();
        }
        if($data){
            if($data->totaldata>0){
                echo json_encode($data->message);
            }
        }
    }
    /**
     * [batal_spk description]
     * @return [type] [description]
     */
    function batal_spk(){
        $param=array();
        if($this->input->get("no_spk")){
            $param['no_spk']   = $this->input->get('no_spk');
            $dealer= json_encode(isDealerAkses());
            $dealer= str_replace("]","",str_replace("[","", $dealer));
            $param["kd_dealer"] = '';//($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$dealer;
            //$this->session->userdata("kd_dealer");
            $data["spkdtl"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spkbatal",$param));
            $param=array();
            $param["no_doc"] = $this->input->get('no_spk');
            $data["ket"] = json_decode($this->curl->simple_get(API_URL."/api/sales/approval_ds/true",$param));
            // var_dump($data["ket"]);exit();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $this->template->site('sales/spk_batal', $data);
    }
    function batal_spk_prs(){
        $data=array();$datax=array();
        $param=array(
            "no_spk" => $this->input->post("no_spk"),
            "status_spk" => $this->input->post("status"),
            "lastmodified_by" => $this->session->userdata("user_id")
        );
        //print_r($param);
        $datax =($this->curl->simple_put(API_URL."/api/spk/spkbatal", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $param=array(
            "no_doc" => $this->input->post("no_spk"),
            "kd_dealer" => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
            "tgl_trans" =>date("d/m/Y"),
            "jenis_doc" => $this->input->post("jenis_doc"),
            "keterangan"    => $this->input->post("alasan"),
            "created_by" => $this->session->userdata("user_id")
        );
        $data =($this->curl->simple_post(API_URL."/api/sales/approval_ds", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $hasil= json_decode($data);
        if($hasil->recordexists==TRUE){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $data = ($this->curl->simple_put(API_URL."/api/sales/approval_ds", $param, array(CURLOPT_BUFFERSIZE => 10)));
        }
        $this->data_output($data,'post',base_url()."spk/batal_spk");
    }
    function unapv_batal_spk(){
        $data=array();$datax=array();
        $posting = json_decode($this->input->post("d"),true);
        $param=array(
            "no_spk" => $posting[0]["no_spk"],
            "status_spk" => $posting[0]["status"],
            "lastmodified_by" => $this->session->userdata("user_id")
        );
        $datax =($this->curl->simple_put(API_URL."/api/spk/spkbatal", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $param=array(
            "no_doc" => $posting[0]["no_spk"],
            "kd_dealer" => $posting[0]["kd_dealer"]
        );
        $data = json_decode($this->curl->simple_get(API_URL."/api/sales/approval_ds/true",$param));
        if($data){
            if($data->totaldata >0){
                foreach ($data->message as $key => $value) {
                    $paramx=array(
                        'id' => $value->ID,
                        'lastmodified_by' =>$this->session->userdata("user_id")
                    );
                    $hasil = json_decode($this->curl->simple_delete(API_URL."/api/sales/approval_ds",$paramx));
                }
            }
        }
        echo json_encode($data);
    }
    function batal_spk_view($debug=null){
        $result=array();
        $param =array(
            'kd_dealer' =>$this->session->userdata("kd_dealer"),
            'row_status' => "[-3]",
            //'custom'    => "LEN(ISNULL(NO_TRANS,''))>0"
        );
        $result = json_decode($this->curl->simple_get(API_URL."/api/spk/spk/true",$param));
        if($debug){
            var_dump($result);
            exit();
        }
        $this->output->set_output(json_encode($result));
    }
    /**
     * [approval_spk description]
     * @param  [type] $id        [description]
     * @param  [type] $asal      [description]
     * @param  [type] $batal_spk [description]
     * @return [type]            [description]
     */
    function approval_spk($id=null,$asal=null,$batal_spk=null){
        $hasil=array();
        $data = json_decode($this->input->post("d"));
        if($batal_spk){ //jika batal spk true
            switch($asal){
                case 'SPK':
                    $param = array(
                        'no_spk' => $id,
                        'lastmodified_by' => $this->session->userdata("user_id")
                     );
                    $hasil = json_decode($this->curl->simple_delete(API_URL."/api/spk/spk",$param));
                break;
                case 'MUTASI':
                case 'MTSAD':
                    $param=array(
                        'id'    =>$id,
                        'lastmodified_by'=> $this->session->userdata("user_id")
                    );
                    $hasil = ($this->curl->simple_delete(API_URL . "/api/inventori/inv_mutasi", $param));
                break;
                case 'PEMBATALAN':
                    $hasil = $this->Approve_batal($no_spk,$kd_dealer);
                break;
            }
            redirect(base_url("cashier/approval_ds"));
        }else{
            if($asal){ //asal mempunyai nilai (SPK/MUTASI dll)
                $param=array(
                    'id'    => $id,
                    'tp'    => $asal,
                    'created_by' => $this->session->userdata("user_id"),
                    'apv_level' =>isApproval($asal)
                );
                $hasil =json_decode($this->curl->simple_get(API_URL."/api/spk/approvalds",$param));
                // print_r($param);
                // var_dump($hasil);exit();
                redirect(base_url("cashier/approval_ds"));
            }else{
                if($data){
                    //var_dump($data);
                    foreach ($data as $key => $value) {
                        switch ($value->tp){
                            case "PEMBATALAN":
                                $hasil = $this->Approve_batal($value->no_spk,$value->kd_dealer,$value->level,$value->jmlAprover);
                            break;
                            default:
                            $param=array(
                                'id'    => $value->id,
                                'tp'    => $value->tp,
                                'no_trans'=> $value->no_spk,
                                'created_by' => $this->session->userdata("user_id"),
                                'apv_level' =>isApproval($value->tp),
                                'jml_aprover' => $value->jmlAprover,
                                'level_apv' => $value->level
                            );
                            $hasil =json_decode($this->curl->simple_get(API_URL."/api/spk/approvalds",$param));
                            break;
                        }
                    }
                }
            }
        }
        echo json_encode($hasil);
    }
    function Approve_batal($no_spk,$kd_dealer=null,$apl_level=null,$jml_apv=null){
        $data=array(); $hasil=array();
        $param=array(
            'no_spk' => $no_spk,
            'kd_dealer' => ($kd_dealer)?$kd_dealer:$this->session->userdata("kd_dealer")
        );
        $DealerCabang = isCabang($param["kd_dealer"]);
        if($jml_apv==$apl_level || $DealerCabang=='T'){
            $data = json_decode($this->curl->simple_get(API_URL."/api/spk/spkbatal",$param));
            // var_dump($data);print_r($param);exit();
            if($data){
                if($data->totaldata >0){
                    foreach ($data->message as $key => $value) {
                        switch ((int)$value->NOM) {
                            case 1: //spk
                            //case 2: //so ga perlu di proses
                                    $param=array(
                                        'no_spk'    =>$no_spk,
                                        'lastmodified_by' => $this->session->userdata('user_id')
                                    );
                                    $hasil = json_decode($this->curl->simple_delete(API_URL."/api/spk/spk",$param));
                                break;
                            case 3: //kasir bayar
                                // Buat transaksi pengeluaran umum untuk membayar pembatalan spk
                                    //muncul no spk di kasir pengeluaran umum
                                    //$hasil = $this->generater_jurnal($value->DOC_NO);
                                    $hasil=array('status'=>1,'message'=>'1');
                            break;
                            case 4: //DO
                                    $param=array(
                                        'no_suratjalan'    =>$value->DOC_NO,
                                        'lastmodified_by' => $this->session->userdata('user_id')
                                    );
                                    $hasil = $this->curl->simple_delete(API_URL."/api/inventori/sjkeluar",$param);
                                break;
                            case 5: //Pengurusan STNK
                                    $param=array(
                                        'id'    =>$value->DOC_ID,
                                        'lastmodified_by' => $this->session->userdata('user_id')
                                    );
                                    $hasil = $this->curl->simple_delete(API_URL."/api/stnkbpkb/stnk_detail",$param);
                                break;
                            case 6: //Pengurusan BPKB
                                    $param=array(
                                        'id'    =>$value->DOC_ID,
                                        'lastmodified_by' => $this->session->userdata('user_id')
                                    );
                                    $hasil = $this->curl->simple_delete(API_URL."/api/stnkbpkb/stnk_detail",$param);
                                break;
                            case 7: //pembatalan Pinjaman STNK
                            case 8: //pengembalian Pinjaman STNK
                            case 9: //pembatalan Pinjaman BPKB
                                //generate jurnal pembalik
                                    $hasil = $this->generater_jurnal($value->DOC_NO);
                                break;
                            default:
                                $hasil=array('status'=>1,'message'=>'1');
                                break;
                        }
                    }
                }
            }
        }else{
            //update level status_spk
            $param=array(
                'no_spk' =>$no_spk,
                'status_spk'=> $apl_level * -1,
                'lastmodified_by'=> $this->session->userdata("user_id")
            );
            $data = json_decode($this->curl->simple_put(API_URL."/api/spk/spkbatal/true",$param));
            // var_dump($data);print_r($param);exit();
            $hasil= $data;
        }
       return $hasil; 
    }
    function generater_jurnal($no_kasir){
        $hasil=array();$data=array();$no_jurnal_lama="";$hasild=array();
        //generate header jurnal copy dari jurnal awal
        $no_jurnal = $this->no_jurnal();
        $param=array(
            'source_jurnal' => $no_kasir
        );
        $hasil = json_decode($this->curl->simple_get(API_URL."/api/jurnal/jurnal",$param));
        if($hasil){
            if($hasil->totaldata >0){
                foreach ($hasil->message as $key => $value) {
                    $no_jurnal_lama = $value->NO_JURNAL;
                    $param=array(
                        'kd_maindealer' => $value->KD_MAINDEALER,
                        'kd_dealer'     => $value->KD_DEALER,
                        'pos_dealer'    => $value->KD_POSDEALER,
                        'no_jurnal'     => $no_jurnal,
                        'type_jurnal'   =>'JUM',
                        'tgl_jurnal'    => date("d/m/Y"),
                        'deskripsi_jurnal' => 'Jurnal Pembalik '.$value->DESKRIPSI_JURNAL,
                        'total_jurnal'  => $value->TOTAL_JURNAL,
                        'closing_status' =>'0',
                        'source_jurnal' => $no_jurnal_lama,
                        'kd_trans'      => $value->KD_TRANS,
                        'created_by'    => $this->session->userdata("user_id")
                    );
                    $data = json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal", $param));
                    if($data){
                        if($data->recordexists==true){
                            $param["lastmodified_by"] = $this->session->userdata("user_id");
                            $data = json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal", $param));
                        }
                    }
                }   
            }
        }
        //generate jurnal detail
        $param=array(
            'no_jurnal' => $no_jurnal_lama
        );
        $hasild = json_decode($this->curl->simple_get(API_URL."/api/jurnal/jurnal_detail",$param));
        //ambil data jurnal lama dengan merubah posisi akun D ke K atau sebaliknya
        if($hasild){
            if($hasild->totaldata >0){
                foreach ($hasild->message as $key => $value) {
                    $pos_akun=($value->TYPE_AKUN=='K')?"D":"K";
                    $paramtrx = array(
                        "no_jurnal"     => $no_jurnal,
                        "urutan_jurnal" => $value->URUTAN_JURNAL,
                        "kd_akun"       => $value->KD_AKUN,
                        "keterangan_jurnal"=> $value->KETERANGAN_JURNAL,
                        "type_akun"     => $pos_akun,
                        "jumlah"        => (double)$value->JUMLAH,
                        "created_by"    => $this->session->userdata("user_id")
                    );
                    $hasil = $this->postingjurnal($paramtrx);
                }
            }
        }
       return $hasil;
    }
    function fee_penjualan($debug=null,$unique=null){
        $hasil=array();$data=array();
        $result=array();
        $param=array(
            'kd_dealer' =>($this->input->get("kd_trans"))?$this->input->get("kd_trans"):$this->session->userdata("kd_dealer"),
            'groups' => $this->input->get("grp")
        );
        $hasil = json_decode($this->curl->simple_get(API_URL."/api/spk/fee_jual",$param));
        if($hasil){
            if($hasil->totaldata >0){
                $result = $hasil->message;
                /*foreach ($hasil->message as $key => $value) {
                    $data[]=array(
                        'KD_SALES'  =>$value->KD_SALES,
                        'NAMA_SALES' => $value->NAMA_SALES
                    );
                }*/
            }
        }
        if($unique){
            $result =($result)? super_unique(json_decode(json_encode($result),true),"KD_SALES"):$result;
        }
        if($debug){
            echo json_encode($result);
        }else{
            return $result;
        }
    }
    /**
     * spksalesprogam
     */
    function spk_salesprogram($nomorspk){
        $paramkendaraan=array(
            'no_spk' =>$nomorspk
        );
        $result  = json_decode($this->curl->simple_get(API_URL."/api/spk/spk_salesprogram",$paramkendaraan));
       $this->output->set_output(json_encode($result)); 
    }
    /**
     * [getDataKK description]
     * @param  [type] $no_kk  [description]
     * @param  [type] $detail [description]
     * @return [type]         [description]
     */
    function getDataKK($no_kk=null,$detail=null){
        $param=array();
        $data =array();
        if($no_kk){
            $param=array(
                'no_kk' => $no_kk
            );
            $data=json_decode($this->curl->simple_get(API_URL."/api/spk/datakk",$param));
            if($detail){
                $data=json_decode($this->curl->simple_get(API_URL."/api/spk/datakk_detail",$param));
            }
        }else{
            $param=array(
                'field'     => "NO_KK",
                'groupby'   => TRUE,
                'kd_customer'=> $this->input->get("kd_cus")
            );
            $data=json_decode($this->curl->simple_get(API_URL."/api/master_general/customer",$param));
        }
        $this->output->set_output(json_encode($data));
    }
    function setSPKindent(){
        $param=array(
            'no_trans'  => $this->no
        );
    }
    /**
     * [data_output description]
     * @param  [type] $hasil    [description]
     * @param  string $method   [description]
     * @param  string $location [description]
     * @return [type]           [description]
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
    // end of spk controller
}
?>