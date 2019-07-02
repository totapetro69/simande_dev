<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/controllers/Customer.php';
class Cashier extends Customer {
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
      $this->load->library('user_agent');
      if($this->session->userdata('kd_div') == null)
      {
          redirect("auth");
      }
  }
  /**
   * [listkasir description]
   * @return [type] [description]
   */
  public function listkasir(){
    $data = array();
       $config['per_page'] = '15';
       $data['per_page'] = $config['per_page'];
       $param = array(
           'keyword' => $this->input->get('keyword'),
           'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
           'limit' => $config['per_page'],
           'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata('kd_dealer'),
           'jointable' =>array(
                        array('TRANS_UANGMASUK_DETAIL UD','UD.NO_TRANS=TRANS_UANGMASUK.NO_TRANS','LEFT')/*,
                        array('TRANS_JURNAL MC','MC.SOURCE_JURNAL=TRANS_UANGMASUK.NO_TRANS','LEFT')*/
                      ),
           'field'     =>'TRANS_UANGMASUK.*,UD.URAIAN_TRANSAKSI,(UD.JUMLAH*UD.HARGA)HARGA,UD.NO_URUT,UD.SALDO_AWAL,UD.KD_ACCOUNT,ISNULL(UD.LKH,0)LKH',
           'orderby'  => 'TRANS_UANGMASUK.NO_TRANS DESC'
       );
       if($this->input->get("tgl_trans_aw")){
          $tgl_akhir=($this->input->get('tgl_trans_ak'))?tglToSql($this->input->get('tgl_trans_ak')):tglToSql($this->input->get('tgl_trans_aw'));
          $param["custom"]  =" CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".tglToSql($this->input->get("tgl_trans_aw"))."' AND '".$tgl_akhir."'";
       }else{
         $param["custom"]  =" (CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".date('Ymd')."' AND '".date('Ymd')."' OR ( ISNULL(UD.LKH,0)=0 OR ISNULL(TRANS_UANGMASUK.POSTING_STATUS,0)=0))";
       }
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
    $string = link_pagination();
    $config = array(
        'per_page' => $param['limit'],
        'base_url' => $string[0],
        'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
    );
    $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
    $pagination = $this->template->pagination($config);
    $this->pagination->initialize($pagination);
    $data['pagination'] = $this->pagination->create_links();
    $this->template->site('accounting/ListKasir_trans',$data);
  }
  /**
   * [laporan_lkh description]
   * @param  [type] $seleksi [description]
   * @return [type]          [description]
   */
  function laporan_lkh($seleksi=null,$print=null){
    $this->auth->validate_authen('cashier/kasirnew');
    $data = array();
    $config['per_page'] = '15';
    $data['per_page'] = $config['per_page'];
    $param = array(
           'keyword' => $this->input->get('keyword'),
           'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
           'limit' => $config['per_page'],
           'kd_dealer'  =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata('kd_dealer'),
           'tgl_trans' =>($this->input->get('tgl_trans_aw'))?tglToSql($this->input->get('tgl_trans_aw')):date('Ymd'),
           'saldoAwal' => $this->getSaldo(true),
         );
    //$dariTgl=($this->input->get('tgl_trans_aw'))
    $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/laporanlkh", $param));
    $paramlkh=array(
      'kd_dealer' =>$param["kd_dealer"],
      'tgl_trans' =>$param["tgl_trans"]
    );
    $paramlkh["output"] ="ALLCEK";
    $data["cek"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="ALLKU";
    $data["ku"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="STNKCEK";
    $data["cek_stnk"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="BPKBKU";
    $data["ku_bpkb"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="JASASERVICE";
    $data["jasa"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    // var_dump($data["jasa"]);exit();
    $paramlkh["output"] ="PARTSERVICE";
    $data["part_bengkel"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="PARTCOUNTER";
    $data["part_counter"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="OLISERVICE";
    $data["oli_bengkel"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="OLICOUNTER";
    $data["oli_counter"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh)); 
    // $paramlkh["output"] ="PINJAMAN";
    // $data["pinjaman"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));
    $paramlkh["output"] ="PKB";
    $data["pkb"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/lkhcustom",$paramlkh));    
    $data["saldoawal"] =$this->getSaldo(true);
    if($print){
      $this->load->library('dompdf_gen');
      // $this->load->view('accounting/laporan_kasharian_print', $data);
      $html = $this->load->view('accounting/laporan_kasharian_print', $data, true);
      $filename = 'report_'.time();
      $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');
    }else{
      $uri=explode('&page=',$_SERVER["REQUEST_URI"]);
         $config = array(
          'per_page' => $param['limit'], 
          'base_url'  => $uri[0],
          'total_rows' =>(isset($data["list"]))? $data["list"]->totaldata:0
          );
      $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
      $pagination = $this->template->pagination($config);
      $this->pagination->initialize($pagination);
      $data['pagination'] = $this->pagination->create_links();
      //$this->output->set_output(json_encode($data["list"]));
      $this->template->site('accounting/laporan_kasharian',$data);
    }

  }
  /**
   * [seleksi_lkh description]
   * @param  [type] $onlyData [description]
   * @param  [type] $debug    [description]
   * @param  [type] $notrans  [description]
   * @return [type]           [description]
   */
  function seleksi_lkh($onlyData=null,$debug=null,$notrans=null){
    $this->auth->validate_authen('cashier/seleksi_lkh');
    $data = array();
    $config['per_page'] = '15';
    $data['per_page'] = $config['per_page'];
    $param = array(
           'keyword' => $this->input->get('keyword'),
           'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
           'limit' => $config['per_page']
         );
    $param['kd_dealer']=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata('kd_dealer');
    $param['tgl_trans']=($this->input->get('tgl_trans_aw'))?tglToSql($this->input->get('tgl_trans_aw')):date('Ymd');
    if($onlyData=='1'){
      $param=array(
        "no_trans" => base64_decode(urldecode($notrans))
      );
    }
    /*if($today=='1'){
      //$param['tgl_trans']= ($this->input->get('tgl_trans_aw'))?tglToSql($this->input->get('tgl_trans_aw')):date('Ymd');
    }*/
    if($this->input->get("jenis_trans")){
      $param["type_trans"] = $this->input->get("jenis_trans");
    }
    if($this->input->get('pstatus')){
      
    }
    switch ($this->input->get('pstatus')) {
      case '0':
      case '1':
        $param["custom"] = "TRANS_UANGMASUK.POSTING_STATUS = '".$this->input->get('pstatus')."'";
        break;
      case 'all':
      break;
      default:
        $param["custom"] = "TRANS_UANGMASUK.POSTING_STATUS = '0'";
        break;
    }
    $param["jointable"] = array(array("TRANS_UANGMASUK_CBAYAR CB","CB.NO_TRANS=TRANS_UANGMASUK.NO_TRANS","LEFT"));
    $param["field"] ="TRANS_UANGMASUK.*,CB.CARA_BAYAR,CB.NAMA_BANK,CB.NO_REKENING,CB.NO_CHEQUE,CB.JTH_TEMPO,CB.STATUS_PRINT";
    $data["listlkh"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
    $datax=array();
    if($data["listlkh"]){
      if($data["listlkh"]->totaldata >0 ){
        foreach ($data["listlkh"]->message as $key => $value) {
          $params =array(
            "no_trans" => $value->NO_TRANS,
            "jointable" =>array(array("MASTER_ACC_KODEAKUN_V MA","MA.KD_AKUN=TRANS_UANGMASUK_DETAIL.KD_ACCOUNT","LEFT")),
            "field" =>"TRANS_UANGMASUK_DETAIL.*,MA.NAMA_AKUN,MA.DEFAULT_AC"/*,
            "custom_rowstatus" => true*/
          );
          $datax[$value->NO_TRANS] =json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk_detail",$params));
        }
      }
    }
    $data["saldo"]= $this->getSaldo(FALSE,TRUE);
    $data["listd"]=$datax;
    if($onlyData=="1"){
      if($debug==true){
        echo json_encode($data);
      }else{
        return $data;
      }
    }else{
      $string = link_pagination();
         $config = array(
          'per_page' => $param['limit'], 
          'base_url'  => $string[0],
          'total_rows' =>($data["listlkh"])? $data["listlkh"]->totaldata:0
          );
      $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
      $pagination = $this->template->pagination($config);
      $this->pagination->initialize($pagination);
      $data['pagination'] = $this->pagination->create_links();
      // var_dump($data["listlkh"]);exit;
      $this->template->site('accounting/seleksi_trans',$data);
    }
  }
  /**
   * [kasirapp description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  public function kasirapp($debug=null){
    $data=array();
    $param=array();
    $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
    $param=array(
        "custom" => "ISNULL(TU.VOUCHER_NO,0)='0' AND MV.KD_TRANS IN('D006','D001') AND (TRANS_UANGMASUK_DETAIL.JUMLAH* TRANS_UANGMASUK_DETAIL.HARGA) > MV.MIN_VALUE",
        'jointable'   => array(
                        array("TRANS_UANGMASUK TU","TU.NO_TRANS=TRANS_UANGMASUK_DETAIL.NO_TRANS","LEFT"),
                        array("TRANS_UANGMASUK_CBAYAR TD","TD.NO_TRANS=TU.NO_TRANS","LEFT"),
                        array("USERS U","U.USER_ID=TU.CREATED_BY","LEFT"),
                        array("MASTER_TRANS M","M.ALIAS_TRANS=TU.JENIS_TRANS","LEFT"),
                        array("SETUP_MINIMAL_VALUE MV","MV.KD_TRANS=M.KD_TRANS AND MV.KD_DEALER=TU.KD_DEALER","LEFT")
                      ),
        'field'       => "TU.ID,TU.NO_TRANS,TU.TGL_TRANS,TU.NO_REFF,TU.KET_REFF,TD.CARA_BAYAR,URAIAN_TRANSAKSI,JUMLAH,HARGA,U.USER_NAME,TU.KD_DEALER,TU.KD_MAINDEALER,M.KD_TRANS,MV.MIN_VALUE",
        'orderby ' =>'TU.KD_DEALER,TU.ID'
      );
    $inDealer= str_replace("]","",str_replace("[[],","",json_encode(isDealerAkses())));
    $inDealer = str_replace("[", "", $inDealer);
    $param["custom"] .= " AND TU.KD_DEALER IN(".str_replace("\"","'",$inDealer).")";
    $data["transd"] = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk_detail",$param));
    if($debug==true){
     print_r($param);// print_r(json_encode(isDealerAkses()));
     var_dump($data["transd"]);
     exit();
    }else{
      $this->template->site('accounting/kasir_approval',$data);     
    }
  }
  function approval_ds($debug=null){
    $data=array();$apv_level=0;
    $dealer = isDealerAkses();
    $dealer_type=$this->session->userdata("status_cabang");
    if($this->input->get("kd_dealer")){
      $param=array('kd_dealer' =>$this->input->get("kd_dealer"));
    }else{
      $param=array(
        'where_in'  =>isDealerAkses(),
        'where_in_field'=>'KD_DEALER'
      );
    }
    $data["list"]   = json_decode($this->curl->simple_get(API_URL."/api/sales/approval_ds",$param));
    $param["field"]="KD_DEALER,COUNT(KD_DEALER) AS JML";
    $param["groupby_text"]="KD_DEALER";
    $data["jdlr"] = json_decode($this->curl->simple_get(API_URL."/api/sales/approval_ds",$param));
    $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
    if($debug){
      print_r($data["list"]);
    }else{
      $this->template->site('accounting/ds_approval',$data);     
    }
  }
  /**
   * [kasirnew description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  public function kasirnew($debug=null){
    $data=array();
    $paramx=array();
    $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
    $data["saldoAwal"] = $this->getSaldo(FALSE);
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'tgl_trans' => date('Ymd'),
      'saldoawal' => ($data["saldoAwal"])?$data["saldoAwal"]:'0'
    );
    $saldoAkhir=json_decode($this->curl->simple_get(API_URL."/api/accounting/laporanlkh",$param));
    if($saldoAkhir){
      if($saldoAkhir->totaldata>0){
        foreach ($saldoAkhir->message as $key => $value) {
          $data["saldoAkhir"]= $value->SALDO_AKHIR;
        }
      }
    }
    if($this->input->get('n')){
      $no_trans=base64_decode(urldecode($this->input->get("n")));
      $param=array(
        "no_trans" => $no_trans
      );
      $data["trans"] = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk",$param));
      $data["transd"] = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk_detail",$param));
      $data["cbayare"] = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk_cbayar",$param));
    }
    
    $usr=(strlen($this->session->userdata("user_id"))>22)?substr($this->session->userdata("user_id"),0,22):$this->session->userdata("user_id");
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'kd_docno'  => "KS-".strtoupper($usr),
      'custom'    => "LAST_DOCNO < TO_DOCNO"
    );
    $data["kwt"] = json_decode($this->curl->simple_get(API_URL."/api/setup/docno_users",$param));
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'custom'  =>"TRANS_UANGMASUK.CREATED_BY='".$this->session->userdata("user_id")."' AND TD.ROW_STATUS >=0 /*AND ISNULL(VOUCHER_NO,'0')='0'*/ AND (ISNULL(POSTING_STATUS,0)=0 OR ISNULL(LKH,0)=0)",
      'jointable' => array(array("TRANS_UANGMASUK_DETAIL TD","TD.NO_TRANS=TRANS_UANGMASUK.NO_TRANS","LEFT")),
      'field'   => "TRANS_UANGMASUK.NO_TRANS,POSTING_STATUS,LKH,ISNULL(VOUCHER_NO,'')VOUCHER_NO,TGL_TRANS,TD.ID AS TDID"
    );
    if($this->input->get('n')){
      $no_trans=base64_decode(urldecode($this->input->get("n")));
      $param["no_trans"] = $no_trans;
    }
    $data["appv"] = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk",$param));
    if($debug==true){
      print_r($data["appv"]);
    }else{
      $this->template->site('accounting/kasirnew',$data);     
    }
  }
  /**
   * [kodeakun description]
   * @param  [type] $jenisakun [description]
   * @return [type]            [description]
   */
  public function kodeakun($jenisakun=null){
    switch ($jenisakun) {
      case 'Penerimaan':
          $param=array(
            //'custom'  =>" TIPE IN(5,7) AND JENIS >0",
            'orderby' =>"NO_AKUN,NO_SUBAKUN",
            'field'   =>"KD_AKUN,NAMA_AKUN,SALDO_AWAL,\"CASE 
                        LEN(REPLICATE(CHAR(23),15minLEN(KD_AKUN))) 
                        WHEN 1 THEN CONCAT(KD_AKUN,REPLICATE('',1),'  ',NAMA_AKUN) 
                        WHEN 3 THEN CONCAT(KD_AKUN,REPLICATE('.',7),'',NAMA_AKUN)  
                        WHEN 6 THEN CONCAT(KD_AKUN,REPLICATE('.',12),'',NAMA_AKUN)
                        END \" AS NAMA_AKUNS"
          );
        break;
       case 'Penerimaan Barang':
        $param=array(
          //'custom'  =>" TIPE IN(2) AND JENIS >0",
          'orderby' =>"NO_AKUN,NO_SUBAKUN",
          'field'   =>"KD_AKUN,NAMA_AKUN,SALDO_AWAL,\"CASE 
                      LEN(REPLICATE(CHAR(23),15minLEN(KD_AKUN))) 
                      WHEN 1 THEN CONCAT(KD_AKUN,REPLICATE('',1),'  ',NAMA_AKUN) 
                      WHEN 3 THEN CONCAT(KD_AKUN,REPLICATE('.',7),'',NAMA_AKUN)  
                      WHEN 6 THEN CONCAT(KD_AKUN,REPLICATE('.',12),'',NAMA_AKUN)
                      END \" AS NAMA_AKUNS"
        );
      break;
      case 'Pengeluaran':
          $param=array(
            //'custom'  =>" TIPE IN(6,8,9) AND JENIS >0",
            'orderby' =>"NO_AKUN,NO_SUBAKUN",
            'field'   =>"KD_AKUN,NAMA_AKUN,SALDO_AWAL,\"CASE 
                        LEN(REPLICATE(CHAR(23),15minLEN(KD_AKUN))) 
                        WHEN 1 THEN CONCAT(KD_AKUN,REPLICATE('.',1),' ',NAMA_AKUN) 
                        WHEN 3 THEN CONCAT(KD_AKUN,REPLICATE('.',7),'',NAMA_AKUN)  
                        WHEN 6 THEN CONCAT(KD_AKUN,REPLICATE('.',12),'',NAMA_AKUN)
                        END\" AS  NAMA_AKUNS"
          );
      default:
        $param=array(
            // 'custom'  =>" TIPE IN(6,8,9) AND JENIS >0",
            'orderby' =>"NO_AKUN,NO_SUBAKUN",
            'field'   =>"KD_AKUN,NAMA_AKUN,SALDO_AWAL,\"CASE 
                        LEN(REPLICATE(CHAR(23),15minLEN(KD_AKUN))) 
                        WHEN 1 THEN CONCAT(KD_AKUN,REPLICATE('.',1),' ',NAMA_AKUN) 
                        WHEN 3 THEN CONCAT(KD_AKUN,REPLICATE('.',7),'',NAMA_AKUN)  
                        WHEN 6 THEN CONCAT(KD_AKUN,REPLICATE('.',12),'',NAMA_AKUN)
                        END\" AS  NAMA_AKUNS,COST_CENTER,NO_AKUN,NO_SUBAKUN"
          );
        break;
    }
    $data=json_decode($this->curl->simple_get(API_URL."/api/accounting/kdakun",$param));
    if($data){
      if((int)$data->totaldata>0){
        echo json_encode($data->message);
     }
    }
  }
  /**
   * Kasir model lama - depreciated
   * @param  string $nokwitansi [description]
   * @return [type]             [description]
   */
  public function kasir($nokwitansi=''){
    $data=array();$nospk="";$x=0;
    //jika kwitansi sudah dibuat - setelah proses simpan kwt
    $data["kwtdtl"]=array();
    if($nokwitansi!=''){
      $param=array(
        'no_trans'  => base64_decode(urldecode($nokwitansi)),
        'kd_dealer' => $this->session->userdata("kd_dealer"),
        'jointable' => array(array("MASTER_CUSTOMER MC","MC.KD_CUSTOMER=TRANS_UANGMASUK.KD_CUSTOMER","LEFT")),
        'field'     => "TRANS_UANGMASUK.*,MC.NAMA_CUSTOMER"
      );
      $data["kwt"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk",$param));
       if($data["kwt"]){
        if(is_array($data["kwt"]->message)){
          foreach ($data["kwt"]->message as $key => $value) {
            $paramdtl=array('trans_id' => $value->ID);
            $nospk .=$value->NO_REFF;
            $nospk .=($x>0)?",":"";
            $data["kwtdtl"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk_detail",$paramdtl));
            $x++;
          }
        }
       }
    }else{
      $data["kwt"]=array();
      $data["kwtdtl"]=array();
    }
    $data["nomork"]=$this->getNomorKwitansi($this->session->userdata('kd_dealer'));//nomor akhir kwitansi yang bisa digunakan
    $paramx=array();
    if($this->session->userdata("nama_group")!='Root'){
      $paramx["kd_dealer"] = $this->session->userdata("kd_dealer");
    }
    $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));//list dealer
    //echo $data["nomork"];exit();
    //ambil data spk yang mblm dilakukan pembayaran (status spk=0 dan spk yang akan di print buat di list dropdown)
    //jika mode spk baru
    $nsk=($nospk!='')? " OR SPKID IN(".$nospk.")":"";
    $param = array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'kd_maindealer'=>$this->session->userdata('kd_maindealer'),
      'custom'  => "S.KD_DEALER IN('".$this->session->userdata("kd_dealer")."') AND S.KD_MAINDEALER='".$this->session->userdata('kd_maindealer')."' ",
      'jointable' => array(
                      array("TRANS_SPK_VIEW S","S.SPKID=TRANS_SPK_KENDARAAN_VIEW.SPK_ID AND (S.STATUS_SPK IS NULL OR S.STATUS_SPK=0 $nsk)","LEFT"),
                      array("TRANS_GUESTBOOK TB","TB.GUEST_NO=S.GUEST_NO AND TB.SPK_NO=S.NO_SPK","LEFT")
                    ),
      'field'     => "S.SPKID,S.NO_SPK,S.KD_CUSTOMER,S.NAMA_CUSTOMER,S.ALAMAT_SURAT,S.TGL_SPK,S.TYPE_PENJUALAN,TB.STATUS",
      'groupby'   => TRUE
    );
    $data["ddspk"]=json_decode($this->curl->simple_get(API_URL."/api/laporan/spkview_kendaraan",$param));
    if($this->input->get('no_ref')){
      $param["custom"] .=" AND S.SPKID ='".$this->input->get('no_ref')."'";
      unset($param["groupby"]);
    }
    $data["spk"]=json_decode($this->curl->simple_get(API_URL."/api/laporan/spkview_kendaraan",$param));
    //var_dump($data["spk"]);exit();
    //
    //untuk mode edit data kasir
    if($this->input->get('no_ref')!=''){
      $params=array(
        "spk_id" =>$this->input->get("no_ref"),
        "field" => "SPK_ID,KD_ITEM,NAMA_ITEM,SUM(JUMLAH)JUMLAH,SUM(UANG_MUKA)UANG_MUKA,HARGA_OTR",
        "groupby"=>"TRUE"
      );
      $data["motor"]=json_decode($this->curl->simple_get(API_URL."/api/laporan/spkview_kendaraan",$params));
    }else{
      $data["motor"]=array();
    }
    $data["saldoAwal"] = $this->getSaldo(FALSE);
    $this->template->site('accounting/kasir',$data);
  }
  /**
   * [tm_typeahead description]
   * @return [type] [description]
   */
  public function tm_typeahead() {
    $param=array(
      'kd_dealer'  => $this->session->userdata('kd_dealer'),
     'jointable' =>array(
                  array('TRANS_UANGMASUK_DETAIL UD','UD.TRANS_ID=TRANS_UANGMASUK.ID','LEFT'),
                  array('MASTER_CUSTOMER MC','MC.KD_CUSTOMER=TRANS_UANGMASUK.KD_CUSTOMER','LEFT')
                ),
     'field'     =>'TRANS_UANGMASUK.*,UD.URAIAN_TRANSAKSI,UD.HARGA,MC.NAMA_CUSTOMER'
    );
       $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk"));
    $data_message="";
      if(is_array($data["list"]->message)){
          foreach ($data["list"]->message as $key => $message) {
             $data_message[0][$key] = $message->NO_TRANS;
             $data_message[1][$key] = $message->NAMA_CUSTOMER;
             $data_message[2][$key] = $message->URAIAN_TRANSAKSI;
             $data_message[3][$key] = $message->HARGA;
             $data_message[4][$key] = $message->NO_REFF;
              }
          $data_message = array_merge($data_message[0], $data_message[1], $data_message[2], $data_message[3], $data_message[4]);
      }
      $result['keyword']=$data_message;
      $this->output->set_output(json_encode($result));
  }
  /**
   * [add_titipan description]
   * Cek titipan uang pada proses spk
   */
  public function add_titipan(){
    $paramx=array();
    $param=array(
      'kd_dealer'=>$this->session->userdata("kd_dealer"),
      'custom'  => "STATUS IN('Deal Indent','Pending') AND (SPK_NO IS NULL OR SPK_NO ='') AND ROW_STATUS=0"
    );
    $data["guest"] =json_decode($this->curl->simple_get(API_URL."/api/sales/guestbook",$param));
    if($this->session->userdata("nama_group")!='Root'){
      $paramx["kd_dealer"] = $this->session->userdata("kd_dealer");
    }
    $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));//list dealer
    $param=array(
            "orderby" =>"MASTER_COM_FINANCE.NAMA_LEASING",
            "custom"   => "KD_LEASING !='CSH'"
        );
    $data["fincom"]     = json_decode($this->curl->simple_get(API_URL."/api/master/company_finance",$param));
    $this->load->view('accounting/kasir_titipan', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [check_open_cash description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  public function check_open_cash($debug=null){
    $result="[]";
    $param=array(
      'kd_dealer' => $this->session->userdata('kd_dealer'),
      'kd_lokasi' => ($this->session->userdata("kd_lokasi"))?$this->session->userdata("kd_lokasi"):"12",
      //'open_date' => date('Ymd'),
      'kd_trans'  =>'KSR',
      'custom'    => "((OPEN_DATE = '".date('Ymd')."' OR REOPEN =1))"//" OR (REOPEN IS NULL OR REOPEN > 0))"
    );
    $data=json_decode($this->curl->simple_get(API_URL."/api/accounting/kasir_openclose",$param));
    
    if($data){
      if($data->totaldata>0){
        $result= json_encode($data->message);
      }
    }
    if(!$debug){
      echo $result;
    }else{
      return $data;
    }
  }
  /**
   * [check_close_cash description]
   * @return [type] [description]
   */
  function check_close_cash(){
    $result="[]";
    $param=array(
      'kd_dealer' => $this->session->userdata('kd_dealer'),
      'kd_lokasi' => ($this->session->userdata("kd_lokasi"))?$this->session->userdata("kd_lokasi"):"12",
      'kd_trans'  =>'KSR',
      'custom'    =>'(CLOSE_DATE IS NULL OR CLOSE_DATE < OPEN_DATE) AND CONVERT(CHAR,OPEN_DATE,112) < CONVERT(CHAR,GETDATE(),112) AND (REOPEN IS NULL OR REOPEN =0) '
    );
    $data=json_decode($this->curl->simple_get(API_URL."/api/accounting/kasir_openclose",$param));
    if($data){
      if($data->totaldata>0){
        $result= json_encode($data->message);
      }
    }
    echo $result;
  }
  /**
   * [close_trans description]
   * @return [type] [description]
   */
  function close_trans($forOpname=null){
    $this->auth->validate_authen('cashier/kasirnew');
    $data = array();
    $LastTrans = $this->getSaldo(FALSE,TRUE);
    
    $fromdate=date("Ymd");
    $lastdate=date("Ymd");
    $saldoakhir=0;$id_transkasir=0; $total_transaksi=0;
    if($LastTrans){
      if($LastTrans->totaldata>0){
        foreach ($LastTrans->message as $key => $value) {
            $lastdate   =$value->CLOSE_DATE;
            $fromdate   =$value->OPEN_DATE;
            $saldoakhir =$value->SALDO_AWAL;
            $id_transkasir=$value->ID;
        }
      }
    }
    $fromdate=($fromdate=='')?date("Ymd"):str_replace("-","", substr($fromdate,0,10));
    $lastdate=($lastdate=='')?date("Ymd"):str_replace("-","", substr($lastdate,0,10));
    $lastdate=($lastdate < $fromdate)?$fromdate:$lastdate;
    $param=array(
      'kd_dealer' => $this->session->userdata('kd_dealer'),
      'tgl_trans' => $fromdate,
      'tgl_end'   => $lastdate,
      'saldoawal' => $saldoakhir
    );
    $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/laporanlkh",$param));
    if($forOpname){
      $saldo_akhir=0;
      if($data["list"]){
        if($data["list"]->totaldata>0){
          foreach ($data["list"]->message as $key => $value) {
              $saldo_akhir = $value->SALDO_AKHIR;
          }
        }
      }
      // var_dump($$data["list"]);
      return $saldo_akhir;
      exit();
    }
    $data["last"] = $LastTrans;
    $param=array(
      "custom"  => "(LKH IS NULL OR LKH =0) AND RIGHT(LEFT(NO_TRANS,5),3)='".$this->session->userdata("kd_dealer")."'"
    );
    $data["lkh"]  = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk_detail",$param));
    
    $param=array(
      'kd_dealer' => $this->session->userdata('kd_dealer'),
      'custom'  => " STATUS_PKB NOT IN(3,5,6,7,8,0)",
    );
    $data["pkb"] = json_decode($this->curl->simple_get(API_URL."/api/service/pkb",$param));
    
    $this->load->view('accounting/kasir_close', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [open_trans description]
   * @return [type] [description]
   */
  function open_trans(){
    $this->auth->validate_authen('cashier/kasirnew');
    $param=array();$result=0;
    $data['sa']=$this->getSaldo(TRUE);
    $data['last']=$this->getSaldo(FALSE,TRUE);
    $this->load->view('accounting/kasir_open', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [open_cash description]
   * Simpan saldo awal untuk memulai transaksi
   * @return [type] [description]
   */
  function open_cash(){
    $param=array();$result=0;
    $saldoawal=($this->getSaldo(TRUE)==0)?$this->input->post('saldo_awal'):$this->getSaldo(TRUE);
    $param["kd_dealer"] = $this->session->userdata("kd_dealer");
    $param["kd_maindealer"] = $this->session->userdata("kd_maindealer");
    $param["kd_lokasi"] = ($this->session->userdata("kd_lokasi"))?$this->session->userdata("kd_lokasi"):"12";
    $param["kd_trans"]  = "KSR";
    $param["open_date"] = date("Ymd");
    $param["saldo_awal"]= str_replace(",","",$this->input->post('saldo_awal'));
    $param["created_by"]= $this->session->userdata("user_id");
    $param["keterangan"]= $this->input->post("keterangan");
    $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/kasir_openclose", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil){
        if($hasil->recordexists==FALSE){
          $result= $hasil->message;
        }
    }
    
    redirect("cashier/kasirnew");
  }
  /**
   * [close_cash description]
   * @return [type] [description]
   */
  public function close_cash($forOpame=null){
    $datax=array();
    $param=array(
      'idtrans'       => $this->input->post("id_trans"),
      'close_date'    => date('Ymd'),
      'saldo_akhir'   =>  str_replace(",","",$this->input->post("saldo_akhir")),
      'lastmodified_by' => $this->session->userdata('user_id'),
      'keterangan'    => $this->input->post("keterangan")
    );
    $datax=($this->curl->simple_put(API_URL."/api/accounting/kasir_openclose", $param, array(CURLOPT_BUFFERSIZE => 10)));
    
    redirect("cashier/kasirnew");
  }
  /**
   * [reopen transaksi dilakukan untuk melakukan transaksi yng ketinggalan]
   * atau menyelesaikan transaksi yng masih gantung
   * jika dilakukan reopen maka tgl transaksi mengikuti tgl transaksi sebelumnya
   * @param  string $status [description]
   * @param  [type] $ajax   [description]
   * @return [type]         [description]
   */
  function reopen($status="1",$ajax=null){
    $datax=array();
    $param=array(
      "id" => $this->input->get("id"),
      "openstatus"  =>$status,
      "keterangan"  => $this->input->get("keterangan"),
      "lastmodified_by" => $this->session->userdata("user_id")
    );
    $datax=($this->curl->simple_put(API_URL."/api/accounting/kasir_reopen", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($ajax==true){
      redirect("cashier/kasirnew");
    }
  }
  /**
   * [add_titipan_simpan description]
   */
  function add_titipan_simpan(){
      $param=array();
      $hasil = $this->curl->simple_post(API_URL . "/api/accounting/uangmasuk", $param, array(CURLOPT_BUFFERSIZE => 10));
      $this->data_output($hasil, 'post');
  }
  /**
   * [no_reff description]
   * @param  [type] $ket_ref [description]
   * @return [type]          [description]
   */
  function no_reff($ket_ref=''){
    $no_reff="";$reff_source="";
    switch($this->input->post("jenis_transaksi")){
      case "Penerimaan Barang":
        $no_reff = $this->input->post("no_reff_b");
        $reff_source = $this->input->post("ket_reff_b");
        break;
      case "Penerimaan Umum":
      case "Pengeluaran Umum":
        $no_reff = $this->input->post("no_reff_u");
        $reff_source = $this->input->post("ket_reff_u");
        break;
      case "Penjualan Sparepart":
      case "Penjualan Aksesoris":
      case "Penjualan Apparel":
      case "Pengeluaran Barang":
      case "Service":
        $no_reff = $this->input->post("no_reff_sp");
        $reff_source = $this->input->post("ket_reff_sp");
        break;
      case "Fee Sales":
      case "Fee Penjualan":
        $no_reff = $this->input->post("no_reff_fe");
        $reff_source = $this->input->post("ket_reff_fe");
        break;
      case "Pinjaman":
        $no_reff = $this->input->post("no_reff_p");
        $reff_source = $this->input->post("ket_reff_p");
        break;
      case "Pengembalian Pinjaman":
        $no_reff = $this->input->post("no_reff_pb");
        $reff_source = $this->input->post("ket_reff_pb");
        break;
      case "Nilai SS":
        $no_reff = $this->input->post("nama_pengurus");
        $reff_source = NamaDealer($this->session->userdata("kd_dealer"));
        break;
      case "Titipan Uang":
        $no_reff = $this->input->post("no_reff_tp");
        $reff_source = $this->input->post("ket_reff_tp");
        break;
      case "Pinjaman Sementara":
        $no_reff = $this->input->post("no_reff_jp");
        $reff_source = $this->input->post("ket_reff_jp");
        break;
      default:
        $no_reff = $this->input->post("no_reff");
        $reff_source = $this->input->post("ket_reff");
        break;
    }
    if ($ket_ref){
      return $reff_source;
    }else{
      return $no_reff;
    }
  }
  /**
   * [simpan transaksi kwitansi header]
   * @return [type] [description]
   */
  public function kwitansi(){
    $result="";$nowkitansi="";
    $nokwitansi=($this->input->post("nomorKwt"));
    //$jenis_trans=explode("-",$this->input->post('jenis_pembayaran'));
    $param=array(
      'no_trans'    => ($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->nomor_trans(),
      'tgl_trans'   => $this->input->post('tgl_trans'),
      'kd_maindealer' => $this->session->userdata("kd_maindealer"),
      'kd_dealer'   => ($this->input->post('kd_dealer'))?$this->input->post('kd_dealer'):$this->session->userdata("kd_dealer"),
      'type_trans'  => $this->input->post("tp_transaksi"),
      'jenis_trans' => $this->input->post("jenis_transaksi"),
      'no_reff'     => $this->no_reff(),// $this->input->post('no_reff'),//spkid
      'reff_source' => $this->input->post('source'),
      'ket_reff'    => $this->no_reff(TRUE),//$this->input->post('ket_reff'),
      'created_by'  => $this->session->userdata('user_id')
    );
    $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil->recordexists==TRUE){
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk", $param, array(CURLOPT_BUFFERSIZE => 10)));
    }
    $result = $this->kwitansi_cbayar($param["no_trans"].":".$param["jenis_trans"]);
    //var_dump($result);
    if($hasil){
      if($hasil->status==true){
        switch ($param["jenis_trans"]) {
          case 'Penerimaan Barang':
          case 'Penjualan Sparepart':
          case 'Fee Sales':
          case "Fee Penjualan":
          case 'Pinjaman':
          case "Penjualan Aksesoris":
          case "Penjualan Apparel":
          case "Service":
            $result = $this->penerimaan_barang($param["no_trans"],$param["no_reff"],$this->input->post('stnk_id'));
            break;
          case "Pengeluaran Barang":
            $result = $this->pengeluaran_barang($param["no_trans"],$param["no_reff"],$this->input->post('stnk_id'));
            break;
          case "Pengembalian Pinjaman":
            $result = $this->penerimaan_barang($param["no_trans"],$param["no_reff"],$this->input->post('stnk_idb'));
            $result = $this->detailpengembalian($param["no_trans"]);
            break;
          case 'Penerimaan Umum':
          case "Pengeluaran Umum":
          case "Biaya Hadiah":
            $result = $this->penerimaan_umum($param["no_trans"],$param["jenis_trans"]);
            break;
          case 'Penjualan Unit':
              $result = $this->kwitansi_detail($param["no_trans"],$param["no_reff"]);   
          break;
          case 'Nilai SS':
              $result = $this->pembayaran_ss($param["no_trans"]);
          break;
          case 'Titipan Uang':
              $result = $this->titipan_detail($param["no_trans"],$param["no_reff"]);
              break;
          case 'Pinjaman Sementara':
              $result = $this->pjs_detail($param["no_trans"],$param["no_reff"]);
              break;
          default:
             $result= $this->kwitansi_detail($param["no_trans"]);
            break;
        }   
      }
    }
    if($this->input->post("reopen_status")){
    }
    //var_dump($hasil);exit();
   $this->data_output(json_encode($hasil),'post',base_url('cashier/kasirnew?n='.urlencode(base64_encode($param["no_trans"]))));
  }
  /**
   * [kwitansi_detail description]
   * @param  [type] $trans_id [description]
   * @param  [type] $noreff   [description]
   * @return [type]           [description]
   */
  function kwitansi_detail($trans_id,$noreff=null){
    $param=array(); $result="";
    $jenis_trans=explode("-",$this->input->post('jenis_pembayaran'));
    for($i=0;$i<5;$i++){
      if($this->input->post('uraian_'.$i)!=''){
        $param=array(
          'no_trans'    =>$trans_id,
          'no_urut'     =>($i),
          'uraian_transaksi'=>$this->input->post('uraian_'.$i),
          'jumlah'      => str_replace(",","",$this->input->post('jml_'.$i)),
          'harga'       => str_replace(",","",$this->input->post('harga_'.$i)),
          'saldo_awal'  => $this->input->post("saldo_awal"),
          'kd_account'  => $this->input->post("kd_akun"),
          'keterangan'   => $this->input->post("nama_akun"),
          'created_by'  => $this->session->userdata('user_id'),
          'saldo_akhir' => ($this->input->post('total_2'))?$this->input->post('total_2'):0
        );
        // TODO: proses piutang 
         $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
          if($hasil->recordexists==TRUE){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
          }  
          if($hasil){
            $result= $hasil->message;
            $hasil=$this->update_SPK($noreff,$trans_id,$param["saldo_akhir"]);
          }
      }  
         $result=0;
    }
    return $result;
  }
  /**
   * [detailpengembalian description]
   * @param  [type] $notrans [description]
   * @param  [type] $noreff  [description]
   * @param  [type] $stnk_id [description]
   * @return [type]          [description]
   */
  function detailpengembalian($notrans,$noreff=null,$stnk_id=null){
    $param=array(); $hasil=array();
    $jenis=$this->input->post("jenis_reff_pb");
    $tbayar=$this->input->post("list_A");
    if($this->input->post('pj')){
      $details=json_decode($this->input->post('pj'),true);
      for($i=0;$i < count($details);$i++){
        $param=array(
          'no_trans'  =>$notrans,
          'tgl_trans' =>date('d/m/Y'),
          'kd_maindealer' => $this->session->userdata("kd_maindealer"),
          'kd_dealer' => $this->session->userdata("kd_dealer"),
          'no_mesin'  => $details[$i]["no_mesin"], 
          'no_rangka'  => $details[$i]["no_rangka"], 
          'no_stnk'  => $details[$i]["kd_item"], 
          'no_plat'  => $details[$i]["customer"], 
          'bbnkb'  =>($jenis=='STNK')? $details[$i]["bbnkb"]:0, 
          'pkb'  => ($jenis=='STNK')? $details[$i]["pkb"]:0, 
          'swdkllj'  => ($jenis=='STNK')? $details[$i]["swdkllj"]:0,
          'bpkb'  => ($jenis=='BPKB' &&($tbayar=='B'))? $details[$i]["bpkb"]:0, 
          'stck'  => ($jenis=='BPKB' &&($tbayar=='S'))?$details[$i]["stck"]:0, 
          'plat_asli'  => ($jenis=='BPKB' &&($tbayar=='P'))?$details[$i]["plat_asli"]:0,
          'admin_samsat' => ($jenis=='BPKB' &&($tbayar=='A'))?$details[$i]["admin_samsat"]:0,
          'no_bpkb'  => $jenis.":".$tbayar,
          'created_by' => $this->session->userdata("user_id") 
        );
        $hasil = json_decode($this->curl->simple_post(API_URL."/api/stnkbpkb/trans_stnk_bpkb", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if($hasil){
          if($hasil->recordexists==TRUE){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL."/api/stnkbpkb/trans_stnk_bpkb", $param, array(CURLOPT_BUFFERSIZE => 10)));
          } 
        } 
      }
    } 
    //
    return $hasil;
  }
  function pengeluaran_barang($notrans,$jreff=null,$idreff=null){
    $param=array(); $result=""; $hasil=array();$tbayar="";
    $details=json_decode($this->input->post('dt'),true);
    for($i=0;$i<count($details);$i++){
      $param=array(
        'no_trans'    =>$notrans,
        'no_urut'     =>($i+1),
        'uraian_transaksi'=>$details[$i]["uraian_transaksi"],
        'jumlah'      => str_replace(",","",$details[$i]["jumlah"]),
        'harga'       => str_replace(",","",$details[$i]["harga"]),
        'saldo_awal'  => $details[$i]["saldo_awal"],
        'kd_account'  => $details[$i]["kd_akun"],
        'keterangan'   => $details[$i]["nama_akun"],
        'created_by'  => $this->session->userdata('user_id')
      );
      $tbayar =(isset($details[$i]["tipe_bayar"]))? $details[$i]["tipe_bayar"]:"";
      $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      if($hasil->recordexists==TRUE){
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      }  
      if($hasil){
        //update reff status
         if(substr($jreff, 0,2)=='SP'){
            $hasilx=($this->Update_SalesOrder($jreff,$notrans,'Barang'));
         }else{
            $hasilx=($this->Update_DOUnit($jreff,$notrans,'Barang'));
         }
        $result= $hasil->message;
      }
    }
  }
  function pjs_detail($no_trans,$no_reff=null){
    $param=array(); $result=""; $hasil=array();$tbayar="";
    $details=json_decode($this->input->post('xp'),true);
    for($i=0;$i < count($details);$i++){
      $param=array(
        'no_trans'    =>$no_trans,
        'no_urut'     =>($i+1),
        'uraian_transaksi'=>addslashes($details[$i]["uraian_jp"]).":".$details[$i]["pic"],
        'jumlah'      => "1",
        'harga'       => str_replace(",","",$details[$i]["jml_jp"]),
        'saldo_awal'  => $this->input->post("saldo_awal"),
        'kd_account'  => $this->input->post("kd_akun"),
        'keterangan'   => $this->input->post("nama_akun"),
        'created_by'  => $this->session->userdata('user_id'),
        'saldo_akhir' => "0"
      );
      $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      if($hasil){
        if($hasil->recordexists==TRUE){
          $param["lastmodified_by"] = $this->session->userdata("user_id");
          $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
        } 
      }
      if($hasil){
        if($hasil->status){
          // update status proposal jadi 5
          $paramx=array(
            'no_trans'  => $details[$i]["proposal"],
            'status_jp' => '5',
            'lastmodified_by' => $this->session->userdata('user_id')
          );
          $hasil = json_decode($this->curl->simple_put(API_URL."/api/jurnal/joinpromo_status", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
        }
      }
    }
  }
  /**
   * [penerimaan_barang description]
   * @param  [type] $notrans [description]
   * @param  [type] $jreff   [description]
   * @param  [type] $idreff  [description]
   * @return [type]          [description]
   */
  function penerimaan_barang($notrans,$jreff=null,$idreff=null){
    $param=array(); $result=""; $hasil=array();$tbayar="";
    $details=json_decode($this->input->post('xp'),true);
    $pj=json_decode($this->input->post('pj'),true);
      for($i=0;$i<count($details);$i++){
        $param=array(
          'no_trans'    =>$notrans,
          'no_urut'     =>($i+1),
          'uraian_transaksi'=>$details[$i]["uraian_transaksi"],
          'jumlah'      => str_replace(",","",$details[$i]["jumlah"]),
          'harga'       => str_replace(",","",$details[$i]["harga"]),
          'saldo_awal'  => $details[$i]["saldo_awal"],
          'kd_account'  => $details[$i]["kd_akun"],
          'keterangan'   => $details[$i]["nama_akun"],
          'created_by'  => $this->session->userdata('user_id'),
          'saldo_akhir' => "0"
        );
        $tbayar =(isset($details[$i]["tipe_bayar"]))? $details[$i]["tipe_bayar"]:"";
        $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
          if($hasil){
            if($hasil->recordexists==TRUE){
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
            } 
          }
        
        if($hasil){
            //update reff status
            switch (substr($jreff,0,2)) {
              case 'SP':
                 $hasilx=($this->Update_SalesOrder($jreff,$notrans));
                break;
              case 'WO':
                 $hasilx=($this->Update_PartPKB($jreff,$notrans)); 
                break;
              case 'ST':
              case 'BP':
                if($pj){
                  $tbayar=(substr($jreff,0,2)=='ST')?'1':$tbayar;
                  $hasilx= $this->Update_Pengurusan($idreff,$notrans,$tbayar,'B',$pj);
                }else{
                  $hasilx= $this->Update_Pengurusan($idreff,$notrans,$tbayar,'P');
                }
              break;
              default:
                # code...
                break;
            }
            $result= $hasil->message;
          }
      }
    return $result;
  }
  /**
   * [penerimaan_umum description]
   * @param  [type] $notrans [description]
   * @return [type]          [description]
   */
  function penerimaan_umum($notrans,$jenis_trn=null){
    $param=array(); $result=""; $hasil=array();
    $param=array(
      'no_trans'    =>$notrans,
      'no_urut'     =>"1",
      'uraian_transaksi'=>$this->input->post('uraian_u'),
      'jumlah'      => "1",//str_replace(",","",$this->input->post('jumlah_u')),
      'harga'       => str_replace(",","",$this->input->post('jumlah_u')),
      'saldo_awal'  => $this->input->post("saldo_awal"),
      'kd_account'  => $this->input->post("kd_akun"),
      'keterangan'   => $this->input->post("nama_akun"),
      'created_by'  => $this->session->userdata('user_id')
    );
    $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil->recordexists==TRUE){
      $param["lastmodified_by"] = $this->session->userdata("user_id");
      $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
    }  
    if($hasil){
      $result= $hasil->message;
      /**
       * update spk yang status_spk <0 menjadi 5 dan row_status <0
       */
      $param=array(
        "no_spk"=> $this->input->post("no_spk_batal"),
        "paybill_reff2" => $notrans,
        "lastmodified_by" => $this->session->userdata("user_id"),
        "status_spk" =>"5"
      );
      $hasil = json_decode($this->curl->simple_put(API_URL."/api/spk/spkbatal/true/true", $param, array(CURLOPT_BUFFERSIZE => 10)));
      // var_dump($hasil);print_r($param);
      return $hasil;
    }
  }
  /**
   * [pembayaran_ss description]
   * @param  [type] $no_trans [description]
   * @return [type]           [description]
   */
  function pembayaran_ss($no_trans){
    $param=array(); $result=""; $hasil=array();
    $param=array(
      'no_trans'    =>$no_trans,
      'no_urut'     =>"1",
      'uraian_transaksi'=>$this->input->post('uraian_ss'),
      'jumlah'      => "1",//str_replace(",","",$this->input->post('jumlah_u')),
      'harga'       => str_replace(",","",$this->input->post('jumlah_ss')),
      'saldo_awal'  => $this->input->post("saldo_awal"),
      'kd_account'  => $this->input->post("kd_akun"),
      'keterangan'   => $this->input->post("nama_akun"),
      'created_by'  => $this->session->userdata('user_id')
    );
     $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      if($hasil->recordexists==TRUE){
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      }  
      if($hasil){
        $result= $hasil->message;
        $hasil = $this->update_ss($no_trans);
      }
  }
  /**
   * [update_ss description]
   * @param  [type] $no_trans [description]
   * @return [type]           [description]
   */
  function update_ss($no_trans){
    $param=array();$hasil=array();
    $details=json_decode($this->input->post('xp'),true);
    for($i=0;$i < count($details); $i++){
      $param=array(
          'no_mesin' => $details[$i]['no_mesin'],
          'no_pengajuan' => $no_trans,
          'lastmodified_by' => $this->session->userdata("user_id")
      );
      $hasil = json_decode($this->curl->simple_put(API_URL."/api/stnkbpkb/stnk_detail_file", $param, array(CURLOPT_BUFFERSIZE => 10)));
    }
    return $hasil;
  }
  /**
   * [kwitansi_cbayar description]
   * @param  [type] $notrans [description]
   * @param  [type] $nokw    [description]
   * @return [type]          [description]
   */
  function kwitansi_cbayar($notrans=null,$nokw=null){
    $result=array();$hasilx=array();
    $no_trans = explode(":", $notrans);
    $sp="0";
    if(count($no_trans)>1){
      switch($no_trans[1]){
        case "Penjualan Sparepart":
        case "Service":
        case "Penjualan Aksesoris":
        case "Penjualan Apparel":
          $sp="1";
          break;
        default:
          $sp="0";
          break;
      }
    }
    $params=array(
      'no_trans'    => $no_trans[0],
      'no_rekening' => $this->input->post("no_rekening"),
      'cara_bayar'  => ($this->input->post("cbayar"))?$this->input->post("cbayar"):"Cash",
      'no_cheque'   => $this->input->post("no_cek"),
      'nama_bank'   => $this->input->post("nama_bank"),
      'jth_tempo'   => $this->input->post("tgl_jthtempo"),
      'no_kwitansi' => ($this->input->post("no_kwitansi"))?$this->input->post("no_kwitansi"):$nokw,
      'status_print' => $sp,//$this->input->post("status_print"),
      'printed_by'  => null,//$this->input->post("printed_by"),
      'printed_time' => null,//$this->input->post("printed_time"),
      'created_by'  => $this->session->userdata("user_id")
    );
    $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_cbayar", $params, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil->recordexists==TRUE){
      $params["lastmodified_by"] = $this->session->userdata("user_id");
      $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_cbayar", $params, array(CURLOPT_BUFFERSIZE => 10)));
    }  
    if($hasil){
      $result= $hasil->message;
    }
    return $result;
  }
  /**
   * [kwitansi_print description]
   * @param  string $nokwitansi [description]
   * @param  [type] $nom        [description]
   * @return [type]             [description]
   */
  public function kwitansi_print($nokwitansi='',$nom=null){
    $data=array();
    $param=array(
        'no_trans'  =>base64_decode(urldecode($nokwitansi)),
        'jointable' =>array(
                        array('TRANS_UANGMASUK_DETAIL UD','UD.NO_TRANS=TRANS_UANGMASUK.NO_TRANS','LEFT'),
                        array('TRANS_UANGMASUK_CBAYAR CB','CB.NO_TRANS=TRANS_UANGMASUK.NO_TRANS','LEFT'),
                        array('USERS S','S.USER_ID=TRANS_UANGMASUK.CREATED_BY','LEFT')
                      ),
        'field'     =>'TRANS_UANGMASUK.*,UD.URAIAN_TRANSAKSI,UD.JUMLAH,UD.HARGA,UD.NO_URUT,S.USER_NAME,CB.NO_KWITANSI,
        CB.CARA_BAYAR,CB.NO_REKENING,CB.NO_CHEQUE,CB.NAMA_BANK,CB.JTH_TEMPO'
    );
    $data["kwt"] =json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk",$param));
    $data["no_kw"] =$nom;
    $check="";$nomorkw="";$no_spk="";
    if($data["kwt"]){
      if($data["kwt"]->totaldata>0){
        foreach ($data["kwt"]->message as $key => $value) {
          $check=$value->CARA_BAYAR;
          $nomorkw=$value->NO_KWITANSI;
          $no_spk = $value->NO_REFF;
        }
      }
    }
    if($no_spk){
      $param=array(
        'no_reff'  =>$no_spk,
        'custom'  =>"ISNULL(STATUS_TITIPAN,0)=1",
        'orderby' =>"ID"
      );
      $data["titipan"] =json_decode($this->curl->simple_get(API_URL."/api/accounting/titipan_uang",$param));
    }
    if($check ==""){
        $this->kwitansi_cbayar($param["no_trans"],$nom);
        if(substr($no_spk,0,2)=='PK'){
          $this->update_spk_kendaraan();
        }
    }
    $data["browser"] = $this->agent->browser();
    $this->load->view('accounting/kwitansi', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [nota_sukucadang description]
   * @param  [type] $no_so [description]
   * @return [type]        [description]
   */
  function nota_sukucadang($no_so=null){
    $nom="";
    $no_pkb=base64_decode(urldecode($no_so));
    switch(substr($no_pkb,0,2)){
        case "WO":
          $param=array();
          $param["jointable"] =array(
                     array("TRANS_CSA CS","CS.KD_SA=TRANS_PKB.KD_SA","LEFT"),
                     array("MASTER_MEKANIK M","M.NIK = TRANS_PKB.NAMA_MEKANIK","LEFT"),
                     array("MASTER_KARYAWAN K","K.NIK=M.NIK","LEFT") 
              );
          $param["field"] ="TRANS_PKB.NO_PKB,TRANS_PKB.NO_POLISI,TRANS_PKB.JENIS_KPB,TRANS_PKB.STATUS_PKB,K.NAMA,CS.KD_SA,CS.NAMA_COMINGCUSTOMER";
          $param["orderby"] ="TRANS_PKB.ID";
          $param["no_pkb"] =$no_pkb;
          $data["pkb"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb",$param));
          $param=array(
            'no_pkb'  => $no_pkb
          );
          $data["pkbd"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail/true",$param));
        break;
        case 'SP':
          $param=array(
            'no_trans' => $no_pkb,
            'jointable'=> array(
                  array("TRANS_PARTSO P","P.NO_TRANS = TRANS_PARTSO_DETAIL.NO_TRANS","LEFT"),
                  array("MASTER_BARANG MB","MB.KD_BARANG=TRANS_PARTSO_DETAIL.PART_NUMBER AND MB.ROW_STATUS >=0","LEFT"),
                  array("MASTER_PART MP","MP.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER","LEFT"),
                  array("TRANS_PARTSO_CUSTOMER C","C.NO_TRANS = TRANS_PARTSO_DETAIL.NO_TRANS","LEFT")
              ),
            'field' => "TRANS_PARTSO_DETAIL.*,CASE WHEN P.JENIS_PART='Part' THEN MP.PART_DESKRIPSI ELSE MB.NAMA_BARANG END 
                        PART_DESKRIPSI,C.NAMA_CUSTOMER,C.NO_POLISI,C.NO_HP,C.ALAMAT,P.TGL_TRANS,
                        (SELECT PP.KD_RAKBIN FROM TRANS_PART_PICKING_DETAIL PP WHERE PP.NO_TRANS=TRANS_PARTSO_DETAIL.PICKING_REFF AND PP.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER)KD_RAKBIN",
          );
          $param["custom"] = "ISNULL(PICKING_STATUS,0)>0";
          $data["pkbd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso_detail",$param));
          // var_dump($data["pkbd"]);exit();
          $param=array("kd_dealer"=>$this->session->userdata("kd_dealer"));
          $data["dealer"]= json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",$param));        
          break;
      }
    $data["reff"] = substr($no_pkb,0,2);
    $data["no_kw"] = $nom;
    $data["nomor"] = base64_decode(urldecode($no_so));
    $this->load->view('accounting/nsc', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [kwitansi_setup description]
   * setup penggunaan nomor kwitansi untuk setiap user
   * dilakukan oleh user itu sendiri
   * satu lokasi dealer bisa lebih dari satu kasir dan harus di setup penomorannya masing2
   * @return [type] [description]
   */
  function kwitansi_setup(){
    $data=array();
    $data["nomor"] = $this->get_nomorator();
    $this->load->view('accounting/kasir_nomor', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [kwitansi_register description]
   * @param  [type] $no_trans [description]
   * @param  [type] $reopen   [description]
   * @return [type]           [description]
   */
  function kwitansi_register($no_trans,$reopen=null){
    $this->auth->validate_authen('cashier/kasirnew');
    $data=array();
    $data=$this->seleksi_lkh("1",false,$no_trans);
    //var_dump($data);exit();
    $ropen=$this->check_open_cash(true);
    if($ropen){
      if($ropen->totaldata>0){
        foreach ($ropen->message as $key => $value) {
          $reopen= $value->REOPEN."x".$value->ID;
        }
      }
    }
    $data["reopen"] = $reopen;
    $this->load->view('accounting/kasir_register', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [simpan_register description]
   * @return [type] [description]
   */
  function simpan_register(){
    $hasil=array();
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'kd_maindealer' => $this->session->userdata('kd_maindealer'),
      'no_trans'  => $this->input->post("no_trans"),
      'uraian_register'=> $this->input->post("regtext"),
      'status_print'  =>"1",
      'no_register' =>str_replace("KW","KH", $this->input->post("no_trans")),
      'tgl_register'=>date('d/m/Y'),
      'no_kwt'      => $this->input->post('no_kwt'),
      'created_by'  => $this->session->userdata("user_id")
    );
    $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/kasir_register", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil->recordexists==TRUE){
      $param["lastmodified_by"] = $this->session->userdata("user_id");
      $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/kasir_register", $param, array(CURLOPT_BUFFERSIZE => 10)));
    }
    //menambahkan proses jurnal otomatis
    if($hasil){
      if($hasil->status==true){
        //mapping to kode transaksi
        $paramx=array(
          'no_trans' =>$this->input->post("no_trans"),
          'jointable' => array( array("MASTER_TRANS M","M.ALIAS_TRANS=TRANS_UANGMASUK.JENIS_TRANS","LEFT")),
          'field'   =>"M.KD_TRANS",
          'groupby' => TRUE
        );
        $datax=json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk",$paramx));
        if($datax){
          if($datax->totaldata >0){
            $result = $this->generate_jurnal($datax->message[0]->KD_TRANS,$param['no_trans']);
            //TODO: process piutang jika pembayaran selain cash untuk semua transaksi
            $result = $this->process_piutang($datax->message[0]->KD_TRANS,$param['no_trans']);
          }
        }
         //var_dump($param);
      }
    }
    return $hasil;  
  }
  /**
   * [kwitansi_nota description]
   * @param  [type] $nokwitansi [description]
   * @param  [type] $nom        [description]
   * @return [type]             [description]
   */
  function kwitansi_nota($nokwitansi=null,$nom=null,$bengkel=null){
    $no_pkb="";
    if(!$bengkel){
      $param=array(
          'no_trans'  =>base64_decode(urldecode($nokwitansi)),
          'jointable' =>array(
                          array('TRANS_UANGMASUK_DETAIL UD','UD.NO_TRANS=TRANS_UANGMASUK.NO_TRANS','LEFT'),
                          array('TRANS_UANGMASUK_CBAYAR CB','CB.NO_TRANS=TRANS_UANGMASUK.NO_TRANS','LEFT'),
                          array('USERS S','S.USER_ID=TRANS_UANGMASUK.CREATED_BY','LEFT')
                        ),
          'field'     =>'TRANS_UANGMASUK.*,UD.URAIAN_TRANSAKSI,UD.JUMLAH,UD.HARGA,UD.NO_URUT,S.USER_NAME,CB.NO_KWITANSI',
          'order by' =>'UD.ID'
      );
      //diskon belum diambil mau di ambil dimana ya?
      $data["kwt"] =json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk",$param));
      if($data["kwt"]){
        if($data["kwt"]->totaldata >0){
          foreach ($data["kwt"]->message as $key => $value) {
            $no_pkb = $value->NO_REFF;
          }
        }
      }
    }
    if($no_pkb){
      if($bengkel){
        $no_pkb = $bengkel;
      }
      switch(substr($no_pkb,0,2)){
        case "WO":
          $param=array();
          $param["jointable"] =array(
                     array("TRANS_CSA CS","CS.KD_SA=TRANS_PKB.KD_SA","LEFT"),
                     array("MASTER_MEKANIK M","M.NIK = TRANS_PKB.NAMA_MEKANIK","LEFT"),
                     array("MASTER_KARYAWAN K","K.NIK=M.NIK","LEFT") 
              );
          $param["field"] ="TRANS_PKB.NO_PKB,TRANS_PKB.NO_POLISI,TRANS_PKB.JENIS_KPB,TRANS_PKB.STATUS_PKB,K.NAMA,CS.KD_SA,CS.NAMA_COMINGCUSTOMER";
          $param["orderby"] ="TRANS_PKB.ID";
          $param["no_pkb"] =$no_pkb;
          $data["pkb"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb",$param));
          $param=array(
            'no_pkb'  => $no_pkb
          );
          $data["pkbd"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb_detail/true",$param));
        break;
        case 'SP':
          $param=array(
            'no_trans' => $no_pkb,
            'jointable'=> array(
                  array("TRANS_PARTSO P","P.NO_TRANS = TRANS_PARTSO_DETAIL.NO_TRANS","LEFT"),
                  array("MASTER_BARANG MB","MB.KD_BARANG=TRANS_PARTSO_DETAIL.PART_NUMBER AND MB.ROW_STATUS >=0","LEFT"),
                  array("MASTER_PART MP","MP.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER","LEFT"),
                  array("TRANS_PARTSO_CUSTOMER C","C.NO_TRANS = TRANS_PARTSO_DETAIL.NO_TRANS","LEFT")
              ),
            'field' => "TRANS_PARTSO_DETAIL.*,CASE WHEN P.JENIS_PART='Part' THEN MP.PART_DESKRIPSI ELSE MB.NAMA_BARANG END 
                        PART_DESKRIPSI,C.NAMA_CUSTOMER,C.NO_POLISI,C.NO_HP,C.ALAMAT,P.TGL_TRANS,
                        (SELECT PP.KD_RAKBIN FROM TRANS_PART_PICKING_DETAIL PP WHERE PP.NO_TRANS=TRANS_PARTSO_DETAIL.PICKING_REFF AND PP.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER)KD_RAKBIN",
          );
          $data["pkbd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso_detail",$param));
          //var_dump($data["pkbd"]);exit();
          $param=array("kd_dealer"=>$this->session->userdata("kd_dealer"));
          $data["dealer"]= json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",$param));
          $data["darimana"]="kasir";
        break;
      }
    }
    $data["reff"] = substr($no_pkb,0,2);
    $data["no_kw"] = $nom;
    $data["nomor"] = base64_decode(urldecode($nokwitansi));
    $this->load->view('accounting/nsc', $data);
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  /**
   * [updateafterprint description]
   * @param  string $notrans [description]
   * @return [type]          [description]
   */
  public function updateafterprint($notrans=null,$debug=null){
    $param=array(
      'no_trans' => $notrans,
      'no_kwitansi'   => $this->input->post('no_kw'),
      'lastmodified_by' => $this->session->userdata("user_id"),
      'jenis_print' =>($this->input->post('jenis'))?$this->input->post('jenis'):'kwt'
    );
    $hasil = ($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_cbayar_upd", $param, array(CURLOPT_BUFFERSIZE => 10)));
     if($debug==true){
      print_r($param);
      exit();
    }else{
      $this->data_output($hasil,"put");
    }
    //redirect("cashier/kasirnew?n=".urlencode(base64_encode($param["no_trans"])));
  }
  /**
   * [nomor_trans description]
   * Autogenerater nomor transaksi 
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function nomor_trans($debug=null){
    $result="";
    $nomor=$this->getNomorKwitansi($this->session->userdata("kd_dealer"),TRUE,TRUE);
    $result= 'KW'.$this->session->userdata("kd_dealer").date('Y').str_pad(date('m'),2,'0', STR_PAD_LEFT).'-'.str_pad(($nomor),5,'0', STR_PAD_LEFT);
    if ($debug){ echo $result; }else{ return $result;}
  }
  /**
   * [getNomorKwitansi description]
   * @param  [type] $kd_dealer [description]
   * @param  [type] $urutan    [description]
   * @param  [type] $notrans   [description]
   * @param  [type] $list      [description]
   * @return [type]            [description]
   */
  public function getNomorKwitansi($kd_dealer,$urutan=NULL,$notrans=NULL,$list=NULL){
    $param = array(
      'kd_dealer' => $kd_dealer, 
      'kd_docno' =>'KW',
      'tahun_docno'=>date('Y'),
      'field'=>'ID,FROM_DOCNO,TO_DOCNO,LAST_DOCNO,ISNULL(NO_TRANS,0)NO_TRANS'
     );
    $nomor=0;
    if($notrans==FALSE){
      $param["kd_users"]=$this->session->userdata("user_id");
    }
    $hasil=json_decode($this->curl->simple_get(API_URL."/api/setup/docno_users/".$notrans,$param));
    if($hasil){
      if(is_array($hasil->message)){
        foreach ($hasil->message as $key => $value) {
            $nomor=$value->NO_TRANS;
        }
      }
    }
    if($urutan==TRUE){
      return $nomor+1;
    }else{
      return $hasil;
    }
    // return str_pad(($nomor+1),8,'0',STR_PAD_LEFT);
  }
  /**
   * [getSaldo description]
   * @param  boolean $SaldoAwal [jika TRUE AKAN AMBIL SALDO AKHIR SEBAGAI SALDO AWAL ; FALSE SALDO AWAL yang blm close]
   * @return [type]             [description]
   */
  function getSaldo($SaldoAwal=FALSE,$forClosing=FALSE){
    $saldo=0;
    $param=array(
      'kd_dealer' => $this->session->userdata('kd_dealer'),
      'kd_lokasi' => ($this->session->userdata("kd_lokasi"))?$this->session->userdata("kd_lokasi"):"12",
      'kd_trans'  =>'KSR',
      'custom'    =>($SaldoAwal==TRUE)?'(CLOSE_DATE IS NOT NULL OR CLOSE_DATE >= OPEN_DATE)':'(CLOSE_DATE IS NULL OR CLOSE_DATE < OPEN_DATE) ',
      'field'     =>'ID,OPEN_DATE,CLOSE_DATE,SALDO_AWAL,SALDO_AKHIR',
      'orderby'   =>'ID desc',
      'limit'     =>'1',
      'offset'    =>'0'
      );
      $data["closing"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/kasir_openclose",$param));
      //var_dump($data);
      if($data["closing"]){
        if(($data["closing"]->totaldata>0)){
          foreach ($data["closing"]->message as $key => $value) {
            $saldo=($SaldoAwal==TRUE)?$value->SALDO_AKHIR:$value->SALDO_AWAL;
          }
        }
      }
      if($forClosing==TRUE){
        return $data["closing"];
      }
      if($this->input->post('getsaldo')|| $this->input->get('getsaldo')){
        echo $saldo;
      }else{
        return $saldo;
      }
  }
  /**
   * Generate Saldo Akhir untuk proses Cash Opname
   * @param  [type] $SaldoAwal  [description]
   * @param  [type] $forClosing [description]
   * @return [type]             [description]
   */
  function getSaldoOpname($SaldoAwal=null,$forClosing=null){
    $saldo=0;
    $param=array(
      'kd_dealer' => $this->session->userdata('kd_dealer'),
      'kd_lokasi' => ($this->session->userdata("kd_lokasi"))?$this->session->userdata("kd_lokasi"):"12",
      'kd_trans'  =>'KSR',
      'custom'    =>($SaldoAwal==TRUE)?'(CLOSE_DATE IS NOT NULL OR CLOSE_DATE >= OPEN_DATE)':'(CLOSE_DATE IS NULL OR CLOSE_DATE <= OPEN_DATE) ',
      'field'     =>'ID,OPEN_DATE,CLOSE_DATE,SALDO_AWAL,SALDO_AKHIR',
      'orderby'   =>'ID desc',
      'limit'     =>'1',
      'offset'    =>'0'
      );
      $data["closing"]=json_decode($this->curl->simple_get(API_URL."/api/accounting/kasir_openclose",$param));
      //var_dump($data);
      if($data["closing"]){
        if(($data["closing"]->totaldata>0)){
          foreach ($data["closing"]->message as $key => $value) {
            $saldo=($SaldoAwal==TRUE)?$value->SALDO_AKHIR:$value->SALDO_AWAL;
          }
        }
      }
      if($forClosing==TRUE){
        return $data["closing"];
      }
      if($this->input->post('getsaldo')|| $this->input->get('getsaldo')){
        echo $saldo;
      }else{
        return $saldo;
      }
  }
  /**
   * [hapus_item description]
   * @return [type] [description]
   */
  function hapus_item(){
    $param=array(
      'id'  =>$this->input->get('id'),
      'lastmodified_by' => $this->session->userdata("user_id")
    );
    $hasil = ($this->curl->simple_delete(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
    $this->data_output($hasil,'delete');
  }
  /**
   * [batal_trans description]
   * @param  [type] $notrans [description]
   * @return [type]          [description]
   */
  function batal_trans($notrans){
    $param = array(
      'no_trans' =>$notrans,
      'lastmodified_by' => $this->session->userdata("user_id")
       );
    $hasil = ($this->curl->simple_delete(API_URL."/api/accounting/uangmasuk", $param, array(CURLOPT_BUFFERSIZE => 10)));
    $this->data_output($hasil,'delete');
  }
  /**
   * [pkb_detail description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function set_nopkb($no_pkb=null){
    $this->no_pkb=$no_pkb;
  }
  /**
   * [get_nopkb description]
   * @return [type] [description]
   */
  function get_nopkb(){
    return $this->no_pkb;
  }
  /**
   * [pkb_detail description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function pkb_detail($debug=null){
    $result=array();
    $resultx=array();
    $param=array(
      'no_pkb'  =>(isset($this->no_pkb))?$this->no_pkb: $this->input->get('p')
    );
    $data=json_decode($this->curl->simple_get(API_URL."/api/service/pkb_detail/true",$param));
    //var_dump($data);
    $dataoli=array();
    if($data){
      $n=0;
      if($data->totaldata > 0){
        foreach ($data->message as $key => $value) {
          if((int)$value->PICKING_STATUS >0 && $value->KATEGORI=='Part'){
            $result[]=$data->message[$n];    
          }
          if ($value->KATEGORI=='Jasa' && (int)$value->PICKING_STATUS ==0){
            $result[]=$data->message[$n]; 
          }
          $n++;
        }
      }
    }
    if($debug){ echo json_encode($result);}else{ return $data;}
  }
  /**
   * [posting_trans description]
   * @return [type] [description]
   */
  function posting_trans(){
    $details=json_decode($this->input->post('dt'),true);
    $hasil=array();
    for($i=0;$i<count($details);$i++){
      $param=array(
        'id' => $details[$i]['id'],
        'no_trans'=> $details[$i]["no_trans"],
        'pos_akun'=> $details[$i]["pos_akun"],
        'lastmodified_by' => $this->session->userdata("user_id")
      );
     $hasil = ($this->curl->simple_put(API_URL."/api/accounting/um_detail", $param, array(CURLOPT_BUFFERSIZE => 100)));
    }
    $this->data_output($hasil,"put",base_url("cashier/kasirnew"));
  }
  /**
   * [setup_nomorator description]
   * @return [type] [description]
   */
  function setup_nomorator(){
    $param=array(
      "kd_docno" =>(strlen($this->session->userdata("user_id"))>22)? "KS-".strtoupper(substr($this->session->userdata("user_id"),0,22)):"KS-".strtoupper($this->session->userdata("user_id")),
      "kd_dealer" =>  $this->session->userdata("kd_dealer"),
      "kd_maindealer" =>  $this->session->userdata('kd_maindealer'),
      "nama_docno" => 'Nomorator Kwitansi',
      "tahun_docno" => $this->input->post('tahun_docno'),
      "bulan_docno" =>  date("m"),
      "from_docno" =>  $this->input->post('from_docno'),
      "last_docno" =>  $this->input->post('last_docno'),
      "to_docno" =>  $this->input->post('to_docno'),
      "kd_users" =>  $this->session->userdata("user_id"),
      "no_trans" =>  0,
      "created_by" => $this->session->userdata("user_id")
    );
    $hasil = $this->curl->simple_post(API_URL."/api/setup/docno_users",$param,array(CURLOPT_BUFFERSIZE=>10));
    redirect("cashier/kasirnew");
  }
  /**
   * [get_nomorator description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function get_nomorator($debug=null){
    $result=array();
    $usr=(strlen($this->session->userdata("user_id"))>22)?substr($this->session->userdata("user_id"),0,22):$this->session->userdata("user_id");
    $param=array(
      "kd_docno" => "KS-".strtoupper($usr),
      "kd_dealer" =>  $this->session->userdata("kd_dealer"),
      "kd_maindealer" =>  $this->session->userdata('kd_maindealer'),
      "custom"    => "LAST_DOCNO < TO_DOCNO"
    );
    $hasil = json_decode($this->curl->simple_get(API_URL."/api/setup/docno_users",$param));
    if($hasil){
      if($hasil->totaldata>0){
        $result= $hasil->message;
      }
    }
    if($debug==true){
      echo json_encode($result);
    }else{
      return $hasil;
    }
  }
  /**
   * [get_biayabpkb description]
   * @param  [type] $debug      [description]
   * @param  [type] $returnfull [description]
   * @return [type]             [description]
   */
  function get_biayabpkb($debug=null,$returnfull=null){
    $result=array();
    $param=array(
      'stnk_id'=> $this->input->get("stnk_id"),
      'status' => ($this->input->get("status"))?$this->input->get("status"):"1"
    );
    $data=json_decode($this->curl->simple_get(API_URL."/api/accounting/biayabpkb",$param));
    if($data){
      if($data->totaldata>0){
        $result = $data->message;
      }
    }
    if($debug==true){
      echo json_encode($result);
      if($returnfull){ var_dump($data);}
    }else{
      return $data;
    }
  }
  /**
   * sales order proses
   * [addsop description]
   * @return [type] [description]
   */
  function addsop($debug=null){
    $data=array();
    if($this->input->get("n")){
      $param["no_trans"]= base64_decode(urldecode($this->input->get("n")));
      $data["soh"] = json_decode($this->curl->simple_get(API_URL."/api/inventori/partso",$param));
      $data["socs"]= json_decode($this->curl->simple_get(API_URL."/api/inventori/sopart_customer/true",$param));     
      $param["jointable"] =array(array("MASTER_PART P","P.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER","LEFT"));
      $param["field"] ="TRANS_PARTSO_DETAIL.*,P.PART_DESKRIPSI";
      $data["sod"] = json_decode($this->curl->simple_get(API_URL."/api/inventori/partso_detail",$param)); 
      if($debug){
        var_dump($data["sod"]);exit();
      }
    }
    $data["typecustomer"]=json_decode($this->curl->simple_get(API_URL."/api/setup/typecustomer"));
    $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
    $data["lokasi"]=json_decode($this->curl->simple_get(API_URL."/api/master_general/lokasidealer/",ListDealer()));
    $data["propinsi"]   = json_decode($this->curl->simple_get(API_URL . "/api/master_general/propinsi"));
    $data["saldoAwal"] = $this->getSaldo(FALSE);
    //$this->parts4gen();
    $this->template->site('sales/so_part',$data);
    //TODO : Ambil data motor sesuai nomor polisi
  }
  /**
   * Generate Stock part
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function parts4gen($debug=null){
    $param = array(
        'kd_dealer' =>($this->input->get("kd_dealer"))?$this->inpu->get("kd_dealer"):$this->session->userdata("kd_dealer"),
        'kd_lokasi' =>($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi"),
        'user_login' => str_replace("-","",$this->session->userdata("user_id"))
    );
    $direct=$this->input->get("d");
    $gndata=$this->curl->simple_get(API_URL . "/api/inventori/parts_generate",$param);
    if($debug){
      echo json_encode($gndata);
    }else{
      return $gndata;
    }
  }
   /**
      * [getbarangfromdounit description]
      * Ambil data barang yang di keluarkan via Delivery Unit
      * @return [type] [description]
   */
   function getbarangfromdounit($debug=null){
      $param=array(
         'kd_dealer' => ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
         'kd_lokasi' => $this->session->userdata("kd_lokasi")
      );
      if($this->input->get("no_trans")){
         $param["no_trans"] = $this->input->get("no_trans");
         $param["mode"] = "1";
      }
      $hasil=json_decode($this->curl->simple_get(API_URL."/api/inventori/barang_autopicking",$param));
      if($debug){
         $this->output->set_output(json_encode($hasil));
      }else{
         return $hasil;
      }
   }
  /**
   * [listsop description]
   * @param  [type] $onlyData [description]
   * @param  [type] $ajax     [description]
   * @return [type]           [description]
   */
  function listsop($onlyData=null,$ajax=null,$jenis_so='Part'){
    $data=array();$paramx=array();$datane=array();  
    $config['per_page'] = '10';
    $data['per_page'] = $config['per_page'];
    $dtgl=($this->input->get("dtgl"))?$this->input->get("dtgl"):date("d/m/Y",strtotime("-5 Days"));
    $stgl=($this->input->get("stgl"))?$this->input->get("stgl"):date("d/m/Y");
    $param = array(
       'keyword'   => $this->input->get('keyword'),
       'offset'    => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
       'limit'     => $config['per_page'],
       'jointable' => array(array("TRANS_PARTSO_CUSTOMER M","M.NO_TRANS = TRANS_PARTSO.NO_TRANS","LEFT")),
       'field'     => "TRANS_PARTSO.*,M.NAMA_CUSTOMER,M.KD_CUSTOMER KD_CUS",
     );
    $param['custom'] = "TGL_TRANS BETWEEN '".tglToSql($dtgl)."' and '".tglToSql($stgl)."'";
    //hanya load data nya saja tidak di tampilkan di html
    if($onlyData==true){
      $param=array(
        'jointable' => array(
            array("TRANS_PARTSO_DETAIL PD","PD.NO_TRANS=TRANS_PARTSO.NO_TRANS","LEFT"),
            array("TRANS_PARTSO_CUSTOMER M","M.NO_TRANS = TRANS_PARTSO.NO_TRANS","LEFT")
          ),
        'field' => "TRANS_PARTSO.NO_TRANS,M.NAMA_CUSTOMER",
        'groupby' =>TRUE
      );
      $param['custom']=($jenis_so=='Part')?"ISNULL(PD.PICKING_STATUS,0)=1":"ISNULL(PD.PICKING_STATUS,0)=0";
    }
    if($this->input->get("sosts")!=''){
      $param['custom'] .=" AND TRANS_PARTSO.NO_TRANS IN((SELECT NO_TRANS FROM TRANS_PARTSO_DETAIL WHERE ISNULL(PICKING_STATUS,0)=".$this->input->get("sosts")."))";
    }
    $param['kd_maindealer'] = $this->session->userdata("kd_maindealer");
    if($this->input->get("no_trans")){
      $param["no_trans"]=$this->input->get("no_trans");
    }else{
       $param["jenis_part"] = $jenis_so;
    }
     
    $param["kd_dealer"] =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
    $param["orderby"] ="TRANS_PARTSO.NO_TRANS DESC";
    $data["list"] = json_decode($this->curl->simple_get(API_URL."/api/inventori/partso",$param));
    /*var_dump($data["list"]);
    exit();*/
    $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
    $n_tr="";
    if(isset($data["list"])){
      $paramd=array();
      if($data["list"]->totaldata >0){
        $datane["soh"] = $data["list"]->message;
        foreach ($data["list"]->message as $key => $value) {
            $n_tr .= $value->NO_TRANS."','";
        }
      }
      if($this->input->get("no_trans")){
        $n_tr =$this->input->get("no_trans")."','";
      }
      $paramd["custom"] ="TRANS_PARTSO_DETAIL.NO_TRANS IN ('".substr($n_tr,0,strlen($n_tr)-3)."')";
      if($this->input->get("sosts")!=''){
        $paramd['custom'] .=" AND ISNULL(TRANS_PARTSO_DETAIL.PICKING_STATUS,0)=".$this->input->get("sosts");
      }
      /*else{
        $paramd['custom'] .=" AND ISNULL(TRANS_PARTSO_DETAIL.PICKING_STATUS,0)=1";
      }*/
      $paramd["jointable"] =array(
                              array("MASTER_PART P","P.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER","LEFT"),
                              array("MASTER_BARANG B","B.KD_BARANG=TRANS_PARTSO_DETAIL.PART_NUMBER AND B.ROW_STATUS >=0","LEFT")
                            );
      $paramd["field"] ="TRANS_PARTSO_DETAIL.*,ISNULL(P.PART_DESKRIPSI,B.NAMA_BARANG) AS PART_DESKRIPSI";
      $data["listd"] = json_decode($this->curl->simple_get(API_URL."/api/inventori/partso_detail",$paramd));
      //var_dump($data["listd"]);exit();
      if($data["listd"]){
        if($data["listd"]->totaldata>0){
          $datane["sod"]=$data["listd"]->message;
        }
      }
    }
    if($onlyData==true){
      if($ajax==true){
        echo json_encode($datane);
      }else{
        return $data;
      }
    }else{
      $string = link_pagination();
      $config = array(
              'per_page' => $param['limit'],
              'base_url' => $string[0],
              'total_rows' => (isset($data["list"]))?$data["list"]->totaldata:0
          );
      $pagination = $this->template->pagination($config);
      $this->pagination->initialize($pagination);
      $data['pagination'] = $this->pagination->create_links();
      $this->template->site('sales/so_partlist',$data);
    }
  }
  /**
   * [simpan_so description]
   * @param  [type] $pohotline [description]
   * @return [type]            [description]
   */
  function simpan_so($pohotline=null){
    $param=array();
    // $notrans=($this->input->get("n"))?base64_decode(urldecode($this->input->get("n"))):$this->notrans_so();
    $notrans=($this->input->post("no_transaksi"))?$this->input->post("no_transaksi"):$this->notrans_so();
    $kd_customer=($this->input->post("kd_customer"))?$this->input->post("kd_customer"):$this->getKDCustomer();
    $orderto=($this->input->post("as_booking"))?"AHM":"";
    if($this->input->post("oth_dlr")!="0"){
      $orderto = $this->input->post("oth_dlr");
    }
    if($this->input->post("on_md")){
      $orderto = $this->session->userdata("kd_maindealer");
    }
    $param=array(
      'kd_maindealer' => $this->session->userdata("kd_maindealer"),
      'kd_dealer'     => $this->session->userdata("kd_dealer"),
      'no_trans'      => $notrans,
      'tgl_trans'     => $this->input->post("tgl_trans"),
      'type_customer' => $this->input->post("jenis_customer"),
      'jenis_part'    => $this->input->post("jenis_penjualan"),
      'kd_customer'   => $kd_customer,
      'kd_typemotor'  => $this->input->post("kd_typemotor"),
      'tahun_motor'   => $this->input->post("thn_motor"),
      'vor'           => $this->input->post("vor"),
      'jr'            => $this->input->post('jr'),
      'booking_order' => ($this->input->post("as_booking"))?"1":"0",
      'kd_lokasi'     => $this->input->post("lokasi_jual"),
      'order_to'      => $orderto,
      'created_by'    => $this->session->userdata("user_id")
    );
    $hasil = ($this->curl->simple_post(API_URL."/api/inventori/partso", $param, array(CURLOPT_BUFFERSIZE => 10)));
     if($hasil){
      $hasile=json_decode($hasil);
        if($hasile->recordexists==true){
          $param["lastmodified_by"] = $this->session->userdata("user_id");
          $hasil = ($this->curl->simple_put(API_URL."/api/inventori/partso", $param, array(CURLOPT_BUFFERSIZE => 10)));
        }
        // $hasil=$this->simpan_bookingpart($notrans);    
        
        $hasil=$this->simpan_sodetail($notrans);    
        //data customer tidak disimpan di master customer karena dikhawatirkan tabel master customer terlalu besar
        $hasil=$this->simpan_konsumen($notrans);
    }
    if($pohotline==1){
      echo "purchasing/posp_add?so=".urlencode(base64_encode($notrans));
    }else{
      echo "cashier/addsop/?n=".urlencode(base64_encode($notrans));     
    }
  }

  
  function simpan_bookingpart($notrans){
    $details=json_decode($this->input->post('dt'),true);
    for($i=0;$i<count($details);$i++){
      if($details[$i]["part_number"]!='' && $details[$i]['picking']=='0'){
        $param=array(
          'no_trans'  =>$notrans,
          'part_number' => $details[$i]["part_number"],
          'jumlah_order' => str_replace(",","",$details[$i]["jumlah_order"]),
          'harga_jual'  => str_replace(",","",$details[$i]["harga_jual"]),
          'stock_awal'  => ($details[$i]["stock_awal"])?str_replace(",","",$details[$i]["stock_awal"]):"0",
          'diskon'      => str_replace(",","",$details[$i]["diskon"]),
          'eta'         => str_replace("</b>","",str_replace("<b>","",$details[$i]["eta"])),
          'created_by'  => $this->session->userdata("user_id")
        );      
        $hasil = ($this->curl->simple_post(API_URL."/api/inventori/partso_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if($hasil){
          $hasile=json_decode($hasil);
            if($hasile->recordexists==true){
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil = ($this->curl->simple_put(API_URL."/api/inventori/partso_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
      }
    }
  }
  /**
   * [simpan_sodetail description]
   * @param  [type] $notrans [description]
   * @return [type]          [description]
   */
  function simpan_sodetail($notrans){
    $details=json_decode($this->input->post('dt'),true);
    for($i=0;$i<count($details);$i++){
      if($details[$i]["part_number"]!='' && $details[$i]['picking']=='0'){
        $param=array(
          'no_trans'  =>$notrans,
          'part_number' => $details[$i]["part_number"],
          'jumlah_order' => str_replace(",","",$details[$i]["jumlah_order"]),
          'harga_jual'  => str_replace(",","",$details[$i]["harga_jual"]),
          'stock_awal'  => ($details[$i]["stock_awal"])?str_replace(",","",$details[$i]["stock_awal"]):"0",
          'diskon'      => str_replace(",","",$details[$i]["diskon"]),
          'eta'         => str_replace("</b>","",str_replace("<b>","",$details[$i]["eta"])),
          'created_by'  => $this->session->userdata("user_id")
        );      
        $hasil = ($this->curl->simple_post(API_URL."/api/inventori/partso_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if($hasil){
          $hasile=json_decode($hasil);
            if($hasile->recordexists==true){
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil = ($this->curl->simple_put(API_URL."/api/inventori/partso_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
      }
    }
  }
  /**
   * [simpan_konsumen description]
   * Update konsumen sebagai data master
   * @param  [type] $notrans [description]
   * @return [type]          [description]
   */
  function simpan_konsumen($notrans){
    $param=array();$hasil=array();$hasile=array();
    $param["no_trans"]  = $notrans;
    $param["no_polisi"] = $this->input->post("no_polisi");
    $param["kd_customer"]       = $this->input->post("kd_customer");
    $param["kd_typemotor"]      = $this->input->post("kd_typemotor");
    $param["tahun_motor"]       = $this->input->post("thn_motor");
    $param["nama_customer"]     = ltrim($this->input->post("nama_konsumen"));
    $param["alamat"]    = $this->input->post("alamat_konsumen");
    $param["kd_desa"]   = $this->input->post("kd_desa");
    $param["kd_kecamatan"]      = $this->input->post("kd_kecamatan");
    $param["kd_kabupaten"]      = $this->input->post("kd_kabupaten");
    $param["kd_propinsi"]       = $this->input->post("kd_propinsi");
    $param["no_hp"]     = $this->input->post("no_telp");
    $param["created_by"]= $this->session->userdata("user_id");
    $hasil = $this->curl->simple_post(API_URL . "/api/inventori/sopart_customer", $param, array(CURLOPT_BUFFERSIZE => 10));
    if($hasil){
      $hasile=json_decode($hasil);
      if($hasile->recordexists==true){
        $param["lastmodified_by"] =$this->session->userdata("user_id");
        $hasil = $this->curl->simple_put(API_URL . "/api/inventori/sopart_customer", $param, array(CURLOPT_BUFFERSIZE => 10));
      }
    }
    return $hasil;
  }
  /**
   * [simpan_customer description]
   * @param  [type] $kd_customer [description]
   * @return [type]              [description]
   */
  function simpan_customer($kd_customer){
    //$kd_customer=$this->getKDCustomer();
    $param = array(
        'kd_customer' => $kd_customer,
        'nama_customer' => ($this->input->post("nama_konsumen"))?$this->input->post("nama_konsumen"):$this->input->post("inputpicker-4"),
        'alamat_surat' => $this->input->post("alamat_konsumen"),
        'kelurahan' => $this->input->post("kd_desa"),
        'kd_kecamatan' => $this->input->post("kd_kecamatan"),
        'kd_kota' => $this->input->post("kd_kabupaten"),
        'kode_pos' => $this->input->post("kd_pos"),
        'kd_propinsi' => $this->input->post("kd_propinsi"),
        'no_hp' => $this->input->post("no_telp")
    );
    $hasil = $this->curl->simple_post(API_URL . "/api/master_general/customerso", $param, array(CURLOPT_BUFFERSIZE => 10));
    if($hasil){
      $hasile=json_decode($hasil);
        if($hasile->status){
          if($hasile->recordexists==true){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = ($this->curl->simple_put(API_URL."/api/master_general/customerap", $param, array(CURLOPT_BUFFERSIZE => 10)));
          }
        }   
    }
    return $kd_customer;
  }
  /**
   * [simpan_approval description]
   * @param  string $app   [description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function simpan_approval($app='0',$debug=null){
    $param=array(
      'no_trans' => $this->input->get('no_trans'),
      'voucher_no' => $app,
      'voucher_date'=>date('Ymd'),
      'lastmodified_by'=> $this->session->userdata("user_id")
    );
    $hasil = ($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_app", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($debug==true){
      print_r($param);
    }else{
      $this->data_output($hasil,"put");
    }
  }
  /**
   * [approve_trans description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function approve_trans($debug=null){
    $data=json_decode($this->input->post('dt'),true);
    for($i=0;$i<count($data);$i++){
      $param=array(
        'no_trans' => $data[$i]["no_trans"],
        'voucher_no' => $data[$i]["voucher_no"],
        'voucher_date'=>date('Ymd'),
        'lastmodified_by'=> $this->session->userdata("user_id")
      );
      $hasil = ($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_app", $param, array(CURLOPT_BUFFERSIZE => 10)));
    }
    if($debug==true){
      print_r($param);
    }else{
      $this->data_output($hasil,"put");
    }
  }
  /**
   * [check_approval description]
   * @return [type] [description]
   */
  function check_approval(){
    $no_voucher="";
    $result=array();
    $param=array(
      'no_trans' => $this->input->get("no_trans"),
      'custom'=>' VOUCHER_NO IS NOT NULL',
      'field' => 'VOUCHER_NO,VOUCHER_DATE'
    );
    $hasil = json_decode($this->curl->simple_get(API_URL."/api/accounting/uangmasuk", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil){
      if($hasil->totaldata>0){
        foreach ($hasil->message as $key => $value) {
          $no_voucher=$value->VOUCHER_NO;
        }
      }
    }
    echo $no_voucher;
  }
  /**
   * [hapus_so description]
   * @return [type] [description]
   */
  function hapus_so(){
    $param=array(
      'no_trans' =>$this->input->get("n"),
      'lastmodified_by'=> $this->session->userdata("user_id")
    );
    $hasil = $this->curl->simple_delete(API_URL . "/api/inventori/partso", $param, array(CURLOPT_BUFFERSIZE => 10));
    $this->data_output($hasil,'delete');
  }
  /**
   * [hapus_sod description]
   * @return [type] [description]
   */
  function hapus_sod(){
    $param=array(
      'id' =>$this->input->post("id"),
      'lastmodified_by'=> $this->session->userdata("user_id")
    );
    $hasil = $this->curl->simple_delete(API_URL . "/api/inventori/partso_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
    $this->data_output($hasil,'delete');
  }
  /**
   * [update_SPK description]
   * @param  [type] $no_spk   [description]
   * @param  [type] $no_trans [description]
   * @return [type]           [description]
   */
  function update_SPK($no_spk,$no_trans,$kurang_bayar=0){
    $param=array(
      'no_spk' => $no_spk,
      'status_spk'=>($kurang_bayar>0)?"0":"1",
      'lastmodified_by'=> $this->session->userdata("user_id"),
      'no_reff'  => $no_trans,
      'kurang_bayar' => $kurang_bayar,
      'rencana_bayar'=> $this->input->post('rencana_bayar')
    );
    $hasil= $this->curl->simple_put(API_URL."/api/spk/spk_status/true",$param,array(CURLOPT_BUFFERSIZE => 10));
    return $hasil;
  }
  function update_spk_kendaraan(){
    //blm jadi di pakai
  }
  /**
   * [Update_SalesOrder description]
   * @param [type] $noreff  [description]
   * @param [type] $notrans [description]
   */
   function Update_SalesOrder($noreff,$notrans,$jenis=null){
    $param=array(
      'no_trans'  => $noreff,
      'picking_status'=>'2',
      'bill_reff' => $notrans,
      'lastmodified_by' => $this->session->userdata("user_id"),
      'jenis' =>($jenis)?$jenis:'Part'
    );
    $hasil=$this->curl->simple_put(API_URL . "/api/inventori/so_paybill", $param, array(CURLOPT_BUFFERSIZE => 10));
    return $hasil;
   }
   function Update_DOUnit($noreff,$notrans,$jenis=null){
      $param=array(
         'no_trans'  => $noreff,
         'picking_status'=>'2',
         'bill_reff' => $notrans,
         'lastmodified_by' => $this->session->userdata("user_id"),
       );
       $hasil=$this->curl->simple_put(API_URL . "/api/inventori/do_paybill", $param, array(CURLOPT_BUFFERSIZE => 10));
       return $hasil;
   }
  /**
   * [Update_PartPKB description]
   * @param [type] $noreff  [description]
   * @param [type] $notrans [description]
   */
  function Update_PartPKB($noreff,$notrans){
    $param=array(
      'no_pkb'  => $noreff,
      'status_pkb' => "5",
      'lastmodified_by' => $this->session->userdata("user_id"),
      'picking_status'=>"2",
      'bill_reff' =>$notrans
    );
    $hasil=$this->curl->simple_put(API_URL . "/api/service/pkb_paybill", $param, array(CURLOPT_BUFFERSIZE => 10));
  }
  /**
   * [Update_Pengurusan description]
   * @param [type] $idreff  [description]
   * @param [type] $notrans [description]
   * @param string $tbayar  [description]
   * @param string $proses  [description]
   * @param [type] $data    [description]
   */
  function Update_Pengurusan($idreff,$notrans,$tbayar='',$proses='P',$data=null){
    $details=($data)?$data:array();
    if(count($details)>0){
      for($i=0;$i < count($details);$i++){
       
       $field="";$jp="";
        switch($tbayar){
          case 'A': $field = "REQ_ADMIN_SAMSAT"; $jp ='A'; break; 
          case 'B': $field = "REQ_BPKB";         $jp ='B'; break;
          case 'P': $field = "PLAT_ASLI";        $jp ='P'; break; 
          case 'S': $field = "REQ_STCK";         $jp ='S'; break;
          default : $field = "TGL_BALIK";        $jp ='2'; break;
        }
        $param=array(
          'no_rangka' => $details[$i]["no_rangka"],
          'stnk_id'   => $idreff,
          'field'     => $jp,
          'status_stnk' => '2',
          'no_reff'   => $notrans.':'.$jp,
          'lastmodified_by' => $this->session->userdata("user_id")
        );

        $hasil=$this->curl->simple_put(API_URL . "/api/stnkbpkb/stnkpaybill/true", $param, array(CURLOPT_BUFFERSIZE => 10));
        /*print_r($param);
        var_dump($hasil);*/
      }
    }else{
      $jenis_pijaman=str_split($tbayar);
      for($i=0; $i < count($jenis_pijaman);$i++){
        $field="";$jp="";
        switch($jenis_pijaman[$i]){
          case 'A': $field ="REQ_ADMIN_SAMSAT"; $jp='A';break; 
          case 'B': $field="REQ_BPKB"; $jp='B';break;
          case 'P': $field ="PLAT_ASLI"; $jp='P'; break; 
          case 'S': $field="REQ_STCK"; $jp='S'; break;
          default : $field = "TGL_PINJAM"; $jp ='1'; break; 
        }
        $param=array(
          'stnk_id' => $idreff,
          strtolower($field)=>'1'
        );
        $nomesin=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_detail",$param)); 
        // var_dump($nomesin);exit(); 
        if($nomesin){
          if($nomesin->totaldata >0){
            foreach ($nomesin->message as $key => $value) {
              $param=array(
                'no_rangka' => $value->NO_RANGKA,
                'stnk_id'   => $idreff,
                'field'     => $jp,
                'status_stnk' => '1',
                'no_reff'   => $notrans.':'.$jp,
                'lastmodified_by' => $this->session->userdata("user_id")
              );
              $hasil=$this->curl->simple_put(API_URL . "/api/stnkbpkb/stnkpaybill/true", $param, array(CURLOPT_BUFFERSIZE => 10));
            }
          }
        }     
      }
    }
    // exit();
  }
  /**
   * [notrans_so description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function notrans_so($debug=null,$kode='SP'){
    $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => $kode,
            'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),//  substr($this->input->post('tgl_trans'),6,4),
            'limit' => 1,
            'offset' => 0
        );
        $bulan_kirim = date('m');// $this->input->post('bulan_kirim');
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomorpo == 0) {
            $nopo = $kode . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) .$param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = $kode . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) .$param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
        }
        if($debug){echo $nopo;}else{ return $nopo;}
  }
  /**
   * [no_jurnal description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function no_jurnal($TipeJurnal="JUM",$debug=null){
    $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => $TipeJurnal,
            'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),//  substr($this->input->post('tgl_trans'),6,4),
            'limit' => 1,
            'offset' => 0
        );
        $bulan_kirim = date('m');// $this->input->post('bulan_kirim');
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomorpo == 0) {
            $nopo = $TipeJurnal . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) .$param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-0000001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = $TipeJurnal . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) .$param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 7, '0', STR_PAD_LEFT);
        }
        if($debug==true){echo $nopo;}else{ return $nopo;}
  }
  /**
   * [generate_jurnal description]
   * @param  string $type_jurnal [description]
   * @param  [type] $param       [description]
   * @return [type]              [description]
   */
  function generate_jurnal($type_jurnal=null,$paramx=null){
    $hasil="";
    switch ($type_jurnal) {
      case 'Unit': //jurnal penjualan unit motor
      case 'K014': //get No SPK from nomor trans uang masuk
        $param  = array("no_trans"=>$paramx);
        $dataspk = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
        $no_spk =$dataspk->message[0]->NO_REFF;
        $nomor_jurnal =($this->input->post("no_jurnal"))?$this->input->post("no_jurnal"): $this->no_jurnal();
        $keterangan_jurnal ="Pembelian Unit SMH No.SPK : ".$no_spk;       
        //generate jurnal header
        $param=array(
          'kd_maindealer' => $this->session->userdata("kd_maindealer"),
          'kd_dealer'     => $dataspk->message[0]->KD_DEALER,//$this->session->userdata("kd_dealer"),
          'pos_dealer'    =>"",
          'no_jurnal'     => $nomor_jurnal,
          'type_jurnal'   =>'JUM',
          'tgl_jurnal'    => TglFromSql($dataspk->message[0]->TGL_TRANS),
          'deskripsi_jurnal' => $keterangan_jurnal,
          'total_jurnal'  => '0',
          'closing_status' =>'0',
          'source_jurnal' => $no_spk,
          'kd_trans'      => $type_jurnal,
          'created_by'    => $this->session->userdata("user_id")
        );
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal", $param));
        if($hasil){
          if($hasil->recordexists==true){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal", $param));
          }
        }
        //generate jurnal detail
        if($hasil){
          if($hasil->status==true){
            $this->generate_jurnal_detail($no_spk,$nomor_jurnal);
          }
        }       
      break;
      case 'K010'://PENGEMBALIAN PINJAMAN iaya pengurusan STNK/BPKB
      case 'D007': //PINJAMAN biaya pengurusan STNK/BPKB
        $param  = array("no_trans"=>$paramx);
        $datahd = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
        $dataspk = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        $nomor_jurnal =($this->input->post("no_jurnal"))?$this->input->post("no_jurnal"): $this->no_jurnal();
        $keterangan_jurnal =$dataspk->message[0]->URAIAN_TRANSAKSI;       
        //generate jurnal header
        $param=array(
          'kd_maindealer' => $this->session->userdata("kd_maindealer"),
          'kd_dealer'     => $datahd->message[0]->KD_DEALER,
          'pos_dealer'    =>"",
          'no_jurnal'     => $nomor_jurnal,
          'type_jurnal'   =>'JUM',
          'tgl_jurnal'    => TglFromSql($datahd->message[0]->TGL_TRANS),
          'deskripsi_jurnal' => $keterangan_jurnal,
          'total_jurnal'  => '0',
          'closing_status' =>'0',
          'kd_trans'      => $type_jurnal,
          'source_jurnal' => $datahd->message[0]->NO_REFF,
          'created_by'    => $this->session->userdata("user_id")
        );
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal", $param));
        if($hasil){
          if($hasil->recordexists==true){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal", $param));
          }
        }
        //generate jurnal detail
        if($hasil){
          if($hasil->status==true){
            $this->generate_jurnal_bpkb($type_jurnal,$nomor_jurnal,$paramx);
          }
        }       
        break;
      case "D004": //PENERIMAAN BARANG bukan sp
      case "D006": //PENGELUARAN UMUM
      case "K009": //PENERIMAAN UMUM
      case "K015": //TITIPAN UANG
        $param  = array("no_trans"=>$paramx);
        $datahd = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
        $datatrx = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        $totalharga=0;$uraian="";
        if($datatrx){
          if($datatrx->totaldata > 0){
            foreach ($datatrx->message as $key => $value) {
               $totalharga += ((double)$value->JUMLAH *(double)$value->HARGA);
               $uraian = $value->URAIAN_TRANSAKSI;
            }
          }
        }
        $nomor_jurnal =($this->input->post("no_jurnal"))?$this->input->post("no_jurnal"): $this->no_jurnal();
        $keterangan_jurnal =($type_jurnal=='D006')?
            $uraian." No.Reff : ".$datahd->message[0]->NO_TRANS ."[ ".$datahd->message[0]->NO_REFF." ]":
            $datahd->message[0]->KET_REFF ." No Reff : ".$datahd->message[0]->NO_REFF."/".$datahd->message[0]->NO_TRANS;       
        //generate jurnal header
        $param=array(
          'kd_maindealer' => $this->session->userdata("kd_maindealer"),
          'kd_dealer'     => $datahd->message[0]->KD_DEALER,
          'pos_dealer'    =>"",
          'no_jurnal'     => $nomor_jurnal,
          'type_jurnal'   =>'JUM',
          'tgl_jurnal'    => TglFromSql($datahd->message[0]->TGL_TRANS),
          'deskripsi_jurnal' => $keterangan_jurnal,
          'total_jurnal'  => $totalharga,
          'closing_status' =>'0',
          'kd_trans'      => $type_jurnal,
          'source_jurnal' => $datahd->message[0]->NO_TRANS,
          'created_by'    => $this->session->userdata("user_id")
        );
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal", $param));
        if($hasil){
          if($hasil->recordexists==true){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal", $param));
          }
        }
        //generate jurnal detail
        if($hasil){
          if($hasil->status==true){
            //ambil data dari setup jurnal otomatis jika
            //minimal akun kas harus di setting dahulu di setup jurnal otomatis
            $paramx=array("kd_transaksi"=>$type_jurnal);
            $setup=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/acc_jurnal_oto", $paramx));
            if($setup){ 
              $urutan=0;
              if($setup->totaldata >0){
                foreach ($setup->message as $key => $value) {
                  $param = array(
                      "no_jurnal"     => $nomor_jurnal,
                      "urutan_jurnal" => $value->NOM,
                      "kd_akun"       => $value->KD_AKUN,
                      "keterangan_jurnal"=> $value->NAMA_AKUN,
                      "type_akun"     => $value->TYPE_AKUN,
                      "jumlah"        => $totalharga,
                      "created_by"    => $this->session->userdata("user_id")
                  );
                  $this->postingjurnal($param);
                  $urutan = $value->NOM;
                }
                  if($type_jurnal !='K015'){
                    if ($datatrx){
                      if($datatrx->totaldata > 0){
                        foreach ($datatrx->message as $key => $value) {
                          $urutan ++;
                          $paramtrx = array(
                            "no_jurnal"     => $param["no_jurnal"],
                            "urutan_jurnal" => $urutan,
                            "kd_akun"       => $value->KD_ACCOUNT,
                            "keterangan_jurnal"=> get_perkiraan($value->KD_ACCOUNT),
                            "type_akun"     => ($type_jurnal=='D006')?'D':'K',
                            "jumlah"        => ((double)$value->JUMLAH *(double)$value->HARGA),
                            "created_by"    => $this->session->userdata("user_id")
                          );
                          $this->postingjurnal($paramtrx);
                        }
                      }
                    }
                  }
              }
            }
          }
        }
      break;
      case "D008"://posting jurnal penerimaan barang bukan sparepart
        $param  = array("no_trans"=>$paramx);
        $datahd = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
        $datatrx = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        $totalharga=0;$uraian="";
        if($datatrx){
          if($datatrx->totaldata > 0){
            foreach ($datatrx->message as $key => $value) {
               $totalharga += ((double)$value->JUMLAH *(double)$value->HARGA);
               $uraian = $value->URAIAN_TRANSAKSI;
            }
          }
        }
        $nomor_jurnal =($this->input->post("no_jurnal"))?$this->input->post("no_jurnal"): $this->no_jurnal();
        $keterangan_jurnal =($type_jurnal=='D006')?
            $uraian." No.Reff : ".$datahd->message[0]->NO_TRANS ."[ ".$datahd->message[0]->NO_REFF." ]":
            $datahd->message[0]->KET_REFF ." No Reff : ".$datahd->message[0]->NO_REFF."/".$datahd->message[0]->NO_TRANS;       
        //generate jurnal header
        $param=array(
          'kd_maindealer' => $this->session->userdata("kd_maindealer"),
          'kd_dealer'     => $datahd->message[0]->KD_DEALER,
          'pos_dealer'    =>"",
          'no_jurnal'     => $nomor_jurnal,
          'type_jurnal'   =>'JUM',
          'tgl_jurnal'    => TglFromSql($datahd->message[0]->TGL_TRANS),
          'deskripsi_jurnal' => $keterangan_jurnal,
          'total_jurnal'  => $totalharga,
          'closing_status' =>'0',
          'kd_trans'      => $type_jurnal,
          'source_jurnal' => $datahd->message[0]->NO_TRANS,
          'created_by'    => $this->session->userdata("user_id")
        );
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal", $param));
        if($hasil){
          if($hasil->recordexists==true){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal", $param));
          }
        }
        //generate jurnal detail
        if($hasil){
          if($hasil->status==true){
            $this->postingjurnal_service($param["source_jurnal"],$param["no_jurnal"]);
          }
        }
      break;
      default:
        # code...
        break;
    }
  }
  function postingjurnal($param=null){
    $hasil=array();
    if($param){
      $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal_detail", $param));
      if($hasil){
        if($hasil->recordexists==true){
          $jml=(double)$param["jumlah"];
          $keterangan =$param["keterangan_jurnal"];
          //datapkan id data
          unset($param["keterangan_jurnal"]);
          unset($param["jumlah"]);
          unset($param["created_by"]);
          //unset($param["type_akun"]);
          $dx=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail", $param));
          $param["id"] = $dx->message[0]->ID;
          $param["jumlah"] = $jml;
          $param["type_akun"]=$param["type_akun"];
          $param["keterangan_jurnal"]=$keterangan;
          $param["lastmodified_by"] = $this->session->userdata("user_id");
          $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal_detail", $param));
        }
      }
    }
    return $hasil;
  }
  function generate_jurnal_bpkb($kd_trans,$no_jurnal,$notrans,$repost=null){
    $param=array(
      'kd_trans' => $kd_trans,
      'kd_dealer'   => $this->session->userdata("kd_dealer"),
      'no_trans'  => $notrans
    );
    $data_akun = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnalbpkb", $param));
    // var_dump($data_akun);
    $urutan=0; 
    $params=array();
    if($data_akun){
      if($data_akun->totaldata >0){
        foreach ($data_akun->message as $key => $value) {
          $jml=0;
          $param = array(
              "no_jurnal"     => $no_jurnal,
              "urutan_jurnal" => $value->NOM,
              "kd_akun"       => $value->KD_AKUN,
              "keterangan_jurnal"=> $value->NAMA_AKUN,
              "type_akun"     => $value->TYPE_AKUN,
              "jumlah"        => (double)$value->HARGA,
              "created_by"    => $this->session->userdata("user_id")
          );
          $jml=(double)$value->HARGA;
          $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal_detail", $param));
          if($hasil){
            if($hasil->recordexists==true){
              //datapkan id data
              unset($param["keterangan_jurnal"]);
              unset($param["jumlah"]);
              unset($param["created_by"]);
              //$params[$value->NOM]=$param;
              $dx=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail", $param));
              $param["id"] = $dx->message[0]->ID;
              $param["jumlah"] = $jml;
              $param["keterangan_jurnal"]=$value->NAMA_AKUN;
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal_detail", $param));
            }
          }
          $params[]=$param;
          //$param=array();
        } //end for
      }
    }
    if($repost==true){
       print_r($data_akun->message);
    }
  }
  /**
   * [generate_jurnal_detail description]
   * Khusus untuk penjualan unit
   * @param  [type] $no_spk    [description]
   * @param  [type] $no_jurnal [description]
   * @param  [type] $repost    [description]
   * @return [type]            [description]
   */
  function generate_jurnal_detail($no_spk,$no_jurnal,$repost=null){
    $hasil=array();
    $param=array("no_spk" =>$no_spk);
    if($repost){
      $param=array(
        'no_jurnal' => $no_jurnal,
        'lastmodified_by' => $this->session->userdata("user_id")
      );
      $hasile=json_decode($this->curl->simple_delete(API_URL . "/api/jurnal/jurnal_detail_all", $param, array(CURLOPT_BUFFERSIZE => 10)));
    }
    $data_akun = json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnalunit", $param, array(CURLOPT_BUFFERSIZE => 10)));
    $urutan=0; $params=array();
    if($data_akun){
      if($data_akun->totaldata >0){
        foreach ($data_akun->message as $key => $value) {
          $jml=0;
          $param = array(
              "no_jurnal"     => $no_jurnal,
              "urutan_jurnal" => $value->NOM,
              "kd_akun"       => $value->KD_AKUN,
              "keterangan_jurnal"=> $value->NAMA_AKUN,
              "type_akun"     => $value->TYPE_AKUN,
              "jumlah"        => (double)$value->HARGA,
              "created_by"    => $this->session->userdata("user_id")
          );
          $jml=(double)$value->HARGA;
          $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal_detail", $param));
          if($hasil){
            if($hasil->recordexists==true){
              //datapkan id data
              unset($param["keterangan_jurnal"]);
              unset($param["jumlah"]);
              unset($param["created_by"]);
              //$params[$value->NOM]=$param;
              $dx=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail", $param));
              $param["id"] = $dx->message[0]->ID;
              $param["jumlah"] = $jml;
              $param["keterangan_jurnal"]=$value->NAMA_AKUN;
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal_detail", $param));
            }
          }
          $params[]=$param;
        } //end for
      }
    }
    if($repost==true){
       print_r($data_akun->message);
    }
  }
  /**
   * [postingjurnal_service description]
   * @param  [type] $no_pkb    [description]
   * @param  [type] $no_jurnal [description]
   * @param  [type] $repost    [description]
   * @return [type]            [description]
   */
  function postingjurnal_service($no_pkb,$no_jurnal=null,$repost=null){
    $param  = array("no_trans"=>$no_pkb);
    $datahd = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
    if(!$datahd){}
    $this->set_nopkb($datahd->message[0]->NO_REFF);
    $data=$this->pkb_detail();
    $isPKB=0;$isReguler=0;
    $totalPKB=0; $KPB_olie=0;$KPB_jasa=0; 
    $totalReguler=0;$reg_jasa=0; $reg_sp=0;$reg_oli=0; $reg_part=0;
    if(isset($data)){
      if($data->totaldata >0){
        foreach ($data->message as $key => $value) {
          if($value->JENIS_KPB='PKB'){
            $isPKB ++;
            $totalPKB += $value->TOTAL_HARGA;
            $KPB_jasa +=($value->JENIS_ITEM=='JASA')?$value->TOTAL_HARGA:0;
            $KPB_olie +=($value->JENIS_ITEM=='OLI')?$value->TOTAL_HARGA:0;
          }else{
            $isReguler ++;
            $totalReguler += $value->TOTAL_HARGA;
            $reg_jasa +=($value->JENIS_ITEM=='JASA')?$value->TOTAL_HARGA:0;
            $reg_oli  +=($value->JENIS_ITEM=='OLI')?$value->TOTAL_HARGA:0;
            $reg_part +=($value->JENIS_ITEM=='PART')?$value->TOTAL_HARGA:0;
          }
        }
      }
    }
    $param=array(
      'kd_transaksi' => 'D008'
    );
    if($isPKB >0){
      $param["type_transaksi"]='KPB';
      $hasilx=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/acc_jurnal_oto", $param));
      if(isset($hasilx)){
        if($hasilx->totaldata > 0){
          foreach ($hasilx->message as $key => $value) {
            $jml=0;
            $param = array(
                "no_jurnal"     => $no_jurnal,
                "urutan_jurnal" => $value->NOM,
                "kd_akun"       => $value->KD_AKUN,
                "keterangan_jurnal"=> $value->NAMA_AKUN,
                "type_akun"     => $value->TYPE_AKUN,
                //"jumlah"        => (double)$value->HARGA,
                "created_by"    => $this->session->userdata("user_id")
            );
            if($isPKB > 0){
              switch ($value->CARA_BAYAR) {
                case 'OLI': $param["jumlah"] = $KPB_olie; break;  
                case 'JASA': $param["jumlah"] = $KPB_jasa;break;         
                default: $param["jumlah"] = $totalPKB; break;
              }
            }
            $jml=$param["jumlah"];
            $hasil=json_decode($this->curl->simple_post(API_URL . "/api/jurnal/jurnal_detail", $param));
            if($hasil){
              if($hasil->recordexists==true){
                //datapkan id data
                unset($param["keterangan_jurnal"]);
                unset($param["jumlah"]);
                unset($param["created_by"]);
                //$params[$value->NOM]=$param;
                $dx=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/jurnal_detail", $param));
                $param["id"] = $dx->message[0]->ID;
                $param["jumlah"] = $jml;
                $param["keterangan_jurnal"]=$value->NAMA_AKUN;
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil=json_decode($this->curl->simple_put(API_URL . "/api/jurnal/jurnal_detail", $param));
                /*print_r($hasil);
                print_r($param);*/
              }
            }
            $params[]=$param;
          }
        }
      }
    }
  }
  /**
   * [getSubsidi description]
   * @param  [type] $no_spk        [description]
   * @param  [type] $debug         [description]
   * @param  [type] $detailSubsidi [description]
   * @return [type]                [description]
   */
  function getSubsidi($no_spk=null,$debug=null,$detailSubsidi=null){
    $param=array(
      'no_spk'  =>($no_spk)?$no_spk:$this->input->get('no_spk'),
      'limit' =>'1',
      'offset'=>'0',
      'orderby'=>"ID DESC"
    );
    $type_spk="";
    $subsidi_dealer=((double)$this->input->get("subsidi"))?$this->input->get("subsidi"):"0";
    $spk =json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));
    if($spk){
      if($spk->totaldata >0){
        foreach ($spk->message as $key => $value) {
            $type_spk = $value->TYPE_PENJUALAN;
        }
      }
    }
    $subsidi=0;$detail=array();
    $data=json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_salesprogram", $param));
    if($data){
      if($data->totaldata>0){
        foreach ($data->message as $key => $value) {
            switch($type_spk){
              case "CREDIT":
              case "KREDIT":
                $subsidi += $value->SK_AHM;
                $subsidi += $value->SK_MD;
                $subsidi += ((double)$subsidi_dealer>0)?$subsidi_dealer:$value->MIN_SK_SD;
                $subsidi += $value->SK_FINANCE;
              break;
              case "CASH":
                $subsidi += $value->SC_AHM;
                $subsidi += $value->SC_MD;
                $subsidi += ((double)$subsidi_dealer>0)?$subsidi_dealer:$value->MIN_SC_SD;
              break;
            }
        }
        $detail = $data->message;
      }
    }
    if($debug){ 
      if($detailSubsidi){
        echo json_encode($detail);
      }else{
        echo $subsidi;
      }
    }else{ 
      return $subsidi;
    }
  }
  /**
   * [get_perkiraan_setup description]
   * @param  [type] $field [description]
   * @param  [type] $debug [description]
   * @return [type]        [description]
   */
  function get_perkiraan_setup($field=null,$debug=null){
    $hasil=array();$result="";
    $param=array(
      //'kd_maindealer' => $this->session->userdata("kd_maindealer"),
      'kd_transaksi'  => $this->input->get("kd_transaksi"),
      'type_transaksi'=> $this->input->get("type_transaksi"),
      'cara_bayar'    => $this->input->get("cara_bayar")
    );
    $field=strtoupper($field);
    $hasil=json_decode($this->curl->simple_get(API_URL . "/api/jurnal/acc_jurnal_oto", $param));
    if($hasil){
      if($hasil->totaldata >0){
        if($field){
          foreach ($hasil->message as $key => $value) {
            $result= $value->{$field};
          }
        }else{
          $result = json_encode($hasil->message);
        }
      }
    }
    if($debug){
      echo ($result);
    }else{
      return $result;
    }
  }
  /**
   * [process_piutang description]
   * @param  [type] $kd_trans [description]
   * @param  [type] $no_reff  [description]
   * @return [type]           [description]
   */
  function process_piutang($kd_trans=null,$no_reff=null,$debug=null){
    //get cara bayar
    $data=array(); $result=array();
    $param=array("no_trans" => $no_reff);
    $data = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_cbayar", $param));
    if($data){
      if($data->totaldata > 0){
        foreach ($data->message as $key => $value) {
           switch($value->CARA_BAYAR){
              case 'KU':
              case 'Cheque':
                    $result= $this->piutang_post($value->NO_TRANS,$kd_trans);
              break;
              default:
              break;
           }
        }
      }
    }
    if($debug){ echo json_decode($result);exit();}
    return $result;
  }
  function piutang_post($no_reff,$kd_trans=null){
    $hasil=array();
    $param=array();
    $no_trans =($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->notrans_so(null,'PH');
    $params=array(
      'no_trans'  => $no_reff,
      'jointable' => array(
                      array("TRANS_UANGMASUK_CBAYAR B","B.NO_TRANS = TRANS_UANGMASUK_DETAIL.NO_TRANS","LEFT"),
                      array("TRANS_UANGMASUK T","T.NO_TRANS= TRANS_UANGMASUK_DETAIL.NO_TRANS","LEFT")
                    ),
      'field'     => "TRANS_UANGMASUK_DETAIL.*,T.TGL_TRANS,T.NO_REFF,B.CARA_BAYAR,B.NO_REKENING,B.JTH_TEMPO,B.NO_KWITANSI,B.NO_CHEQUE"
    );
    $datas=json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $params));
    if($datas){
      if($datas->totaldata >0)
      foreach ($datas->message as $key => $value) {
        # code...
        $param=array();
        $param["kd_maindealer"]     = $this->session->userdata("kd_maindealer");
        $param["kd_dealer"] = $this->session->userdata("kd_dealer");;
        $param["no_trans"]  = $no_reff;
        $param["tgl_trans"] = date('d/m/Y');
        $param["kd_piutang"]= 'P'.$kd_trans;
        $param["tgl_piutang"]       = TglFromSql($value->TGL_TRANS);
        $param["reff_piutang"]      = $value->NO_REFF;
        $param["uraian_piutang"]    = $value->URAIAN_TRANSAKSI;
        $param["jumlah_piutang"]    = $value->HARGA;
        $param["cara_bayar"]= $value->CARA_BAYAR;
        $param["tgl_tempo"] = TglFromSql($value->JTH_TEMPO);
        $param["status_piutang"]    = "0";
        $param["created_by"]= $this->session->userdata("user_id");
        $hasil=json_decode($this->curl->simple_post(API_URL . "/api/accounting/piutang", $param));
        if($hasil){
          if($hasil->recordexists==true){
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil=json_decode($this->curl->simple_put(API_URL . "/api/accounting/piutang", $param));
          }
        }
      }
    }
    return $hasil;
  }
  function cashopname(){
    $data=array();
    $param = array(
         'keyword' => $this->input->get('keyword'),
         'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
         'limit' => 15,
         'kd_dealer' =>($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
     );
    $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/audit_kasir", $param));
    $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
    $paramx=array(
      'field' =>"YEAR(TGL_TRANS) AS TAHUN",
      'groupby_text'=>"YEAR(TGL_TRANS)",
      'orderby' => "YEAR(TGL_TRANS) DESC"
    );
    $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/audit_kasir", $paramx));
    $string = link_pagination();
    $config = array(
        'per_page' => $param['limit'],
        'base_url' => $string[0],
        'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
    );
    $pagination = $this->template->pagination($config);
    $this->pagination->initialize($pagination);
    $data['pagination'] = $this->pagination->create_links();
    $this->template->site('accounting/kasir_audit_list',$data);
  }
  function cashopname_add($prints=null){
    $this->auth->validate_authen("cashier/cashopname");
    $data=array();
    if($this->input->get("n")){
      $param=array(
        'kd_dealer' =>($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
        'no_trans' => $this->input->get("n")
      );
      $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/audit_kasir", $param));
    }else{
      $param=array(
        'kd_dealer' =>($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
        'tgl_trans' =>($this->input->get('t'))? tglToSql($this->input->get('t')):date('Ymd')
      );
      $data["list"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/audit_kasir", $param));
      if($data["list"]){
        if($data["list"]->totaldata==0){
          $data["kas_skrng"] = $this->getSaldoOpname(TRUE);
          // var_dump($data["kas_skrng"]);
        }
      }else{
        $data["kas_skrng"] = $this->getSaldoOpname(TRUE);
      }    
      
    }
    $data["dealer"] = json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
    $data["lokasi"] = json_decode($this->curl->simple_get(API_URL."/api/master_general/lokasidealer/",ListDealer()));
    $params=array(
      'kd_dealer' =>($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
      'personal_jabatan'=>'Kepala Sentra Penjualan',
      'personal_level' =>'3'
    );
    $data["kepala"] = json_decode($this->curl->simple_get(API_URL."/api/master_general/karyawan/",$params));
    if($prints){
      $this->load->view('accounting/kasir_audit_print', $data);
    }else{
      $this->load->view('accounting/kasir_audit_add', $data);
    }
    $html = $this->output->get_output();
    $this->output->set_output(json_encode($html));
  }
  function cashopname_simpan($apv=null){
      $hasil=array();
      if($apv){
        $hasil = $this->cashopname_apv($apv);
      }else{
        $no_trans =($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->notrans_so(null,'KO');
        $param=array(
            'kd_dealer' => ($this->input->post("kd_dealer"))?$this->input->post("kd_dealer"):$this->session->userdata("kd_dealer"),
            'kd_lokasidealer'=>$this->input->post("kd_lokasi_dealer"),
            'no_trans'  => $no_trans,
            'tgl_trans' => $this->input->post("tgl_trans"),
            'jumlah_total' => str_replace(",","",$this->input->post("total_hasil")),
            'jumlah_kas'  => str_replace(",","",$this->input->post("total_kas")),
            'jumlah_k100' => $this->input->post("jumlah_k100"),
            'jumlah_k50' => $this->input->post("jumlah_k50"),
            'jumlah_k20' => $this->input->post("jumlah_k20"),
            'jumlah_k10' => $this->input->post("jumlah_k10"),
            'jumlah_k5' => $this->input->post("jumlah_k5"),
            'jumlah_k2' => $this->input->post("jumlah_k2"),
            'jumlah_k1' => $this->input->post("jumlah_k1"),
            'jumlah_l1000' => $this->input->post("jumlah_l1000"),
            'jumlah_l500' => $this->input->post("jumlah_l500"),
            'jumlah_l200' => $this->input->post("jumlah_l200"),
            'jumlah_l100' => $this->input->post("jumlah_l100"),
            'jumlah_l50' => $this->input->post("jumlah_l50"),
            'selisih' => $this->input->post("selisih"),
            'created_by' => $this->session->userdata("user_id")
        );
        $hasil = ($this->curl->simple_post(API_URL . "/api/accounting/audit_kasir", $param,array(CURLOPT_BUFFERSIZE => 10)));
        $data=json_decode($hasil);
        if($data){
            if($data->recordexists==true){
                $param["lastmodified_by"] = $this->session->userdata("kd_dealer");
                $hasil = ($this->curl->simple_put(API_URL . "/api/accounting/audit_kasir", $param,array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
      }
      $this->data_output($hasil,'post');
  }
  function cashopname_apv($level=null){
    $hasil=array();
    if($level){
      $param=array(
        'no_trans'  => $this->input->post("no_trans"),
        'status_audit' => $level,
        'keterangan' => $this->input->post("keterangan"),
        'lastmodified_by' => $this->session->userdata("kd_dealer")
      );
      $hasil = ($this->curl->simple_put(API_URL . "/api/accounting/audit_kasir/true", $param,array(CURLOPT_BUFFERSIZE => 10)));
    }
    return $hasil;
  }
  function pengurus4ss(){
    $hasil=array();
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'id'        => "1",
      'field'     => "NAMA_BIROJASA,NAMA_PENGURUS,JUMLAH,ROW_STATUS",
      'custom'   => "JUMLAH >0 AND STATUS_STNK >=3",
      'orderby' => "NAMA_PENGURUS"
    );
    $hasil = json_decode($this->curl->simple_get(API_URL."/api/accounting/biaya_ss/",$param));
    if($hasil){
      echo json_encode($hasil);
    }
  }
  function detail_ss(){
    $hasil=array();
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'nama_pengurus' => $this->input->get("nama_pengurus"),
      'custom'  => "TRANS_STNK_SS.ID=0 AND TRANS_STNK_SS.STATUS_SS=0",
      'jointable' => array(
                      array("TRANS_SPK_KENDARAAN_VIEW D","D.NO_MESIN=TRANS_STNK_SS.NO_MESIN","LEFT"),
                      array("TRANS_SPK K","K.ID=D.SPK_ID","LEFT"),
                      array("MASTER_CUSTOMER M","M.KD_CUSTOMER = D.KD_CUSTOMER","LEFT")
                    ),
      // 'field'   => "TRANS_STNK_SS.NAMA_PENGURUS,TRANS_STNK_SS.KD_PENGURUS,TRANS_STNK_SS.NO_MESIN,TRANS_STNK_SS.NO_RANGKA,
      //               TRANS_STNK_SS.JUMLAH,K.NO_SPK,K.TYPE_PENJUALAN,K.KD_TYPECUSTOMER,D.KD_CUSTOMER,M.NAMA_CUSTOMER,
      //               D.ALAMAT_KIRIM,D.KD_ITEM,D.NAMA_ITEM",
      'field'   => "K.NO_SPK,D.KD_ITEM,D.NAMA_ITEM,TRANS_STNK_SS.NO_MESIN,TRANS_STNK_SS.JUMLAH,M.NAMA_CUSTOMER,D.ALAMAT_KIRIM",
      'groupby' => TRUE
    );
    $hasil = json_decode($this->curl->simple_get(API_URL."/api/accounting/biaya_ss/",$param));
    if($hasil){
      echo json_encode($hasil);
    }
  }
  function titipan_uang($total=null){
    $hasil=array();
    $param=array(
      'no_reff'  => $this->input->get("no_spk")/*,
      'custom'   => "(ISNULL(STATUS_TITIPAN,0) BETWEEN 0 AND 1)",*/
    );
    if($total){
      // unset($param["custom"]);
      $param["field"]="NO_REFF,('Total Uang Titipan SPK :'+NO_REFF) AS URAIAN_TITIPAN,SUM(JUMLAH_TITIPAN) JUMLAH_TITIPAN";
      $param["groupby_text"] = "NO_REFF,STATUS_TITIPAN";
      $param["custom"] = "STATUS_TITIPAN >0";
    }
    $hasil = json_decode($this->curl->simple_get(API_URL."/api/accounting/titipan_uang/",$param));
    if($hasil){
      // echo json_encode($hasil);
      $this->output->set_output(json_encode($hasil));
    }
  }
  function titipan_detail($no_trans,$no_reff=null){
    $param=array(); $result=""; $hasil=array();
    $param=array(
      'no_trans'    =>$no_trans,
      'no_urut'     =>"1",
      'uraian_transaksi'=>$this->input->post('uraian_titipan'),
      'jumlah'      => str_replace(",","",$this->input->post('jumlah_titipan')),
      'harga'       => str_replace(",","",$this->input->post('harga_titipan')),
      'saldo_awal'  => $this->input->post("saldo_awal"),
      'kd_account'  => $this->input->post("kd_akun"),
      'keterangan'   => $this->input->post("nama_akun"),
      'created_by'  => $this->session->userdata('user_id')
    );
      $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      if($hasil->recordexists==TRUE){
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/uangmasuk_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
      }  
      if($hasil){
        $result= $hasil->message;
        $hasil = $this->simpan_titipan($no_trans,$no_reff,"1");
      }
  }
  function simpan_titipan($no_trans=null,$no_spk=null,$status="0"){
    $hasil=array();
    $param=array();
    if($status=="0"){
      $param["no_trans"]  = ($this->input->post("no_trans"))?$this->input->post("no_trans"):$this->notrans_so(null,'TU');
    }else{
      $param["no_trans"]  = $this->input->post("no_trans_tp");
    }
    $param["tgl_trans"] = ($this->input->post("tgl_trans"))?$this->input->post("tgl_trans"):date('d/m/Y');
    $param["kd_dealer"] = $this->session->userdata("kd_dealer");
    $param["no_reff"]   = ($no_spk)?$no_spk:$this->input->post("no_reff");
    $param["jumlah_titipan"]    = str_replace(",","",$this->input->post("t_harga_titipan"));
    $param["no_kwitansi"]       = ($no_trans)?$no_trans:"0";
    $param["uraian_titipan"]    = $this->input->post("uraian_titipan");
    $param["status_titipan"]    = $status;//$this->input->post("status_titipan");
    $param["created_by"]      = $this->session->userdata("user_id");
    $hasil = json_decode($this->curl->simple_post(API_URL."/api/accounting/titipan_uang", $param, array(CURLOPT_BUFFERSIZE => 10)));
    //var_dump($hasil);print_r($param);exit();
    if($hasil){    
      if($hasil->recordexists==true){
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $hasil = json_decode($this->curl->simple_put(API_URL."/api/accounting/titipan_uang", $param, array(CURLOPT_BUFFERSIZE => 10)));
      }
    } 
    if($no_trans){
      return $hasil;      
    }else{
      if($hasil){
        echo $this->data_output(json_encode($hasil),"post",base_url("spk/add_spk?id=".$this->input->post("spk_id")."&tab=4"));
      }
    }
  }
  function hapus_titipan(){
    $param=array();
    $param["no_trans"]    = $this->input->post("no_trans");
    $param["created_by"]      = $this->session->userdata("user_id");
    $hasil = ($this->curl->simple_delete(API_URL."/api/accounting/titipan_uang", $param, array(CURLOPT_BUFFERSIZE => 10)));
    if($hasil){
        echo $this->data_output(($hasil),"post",base_url("spk/add_spk?id=".$this->input->post("spk_id")."&tab=4"));
    }
  }
  function update_titipan($no_spk){
    // gunakan trigger on after insert trans_uangmasuk
    // on 29-01-2019 di rubah ke sp trans_uangmasuk_insert
  }
  function minimal_value(){
    $kd_transaksi=""; $result="0";
    switch($this->input->get("jt")){
      case 'Pengeluaran Umum': $kd_transaksi='D006';break;
      case 'Biaya Hadiah': $kd_transaksi = 'D001';break;
      default: $kd_transaksi="";break;
    }
    if($kd_transaksi){
      $param=array(
        'kd_dealer' => $this->session->userdata("kd_dealer"),
        'kd_trans'  => $kd_transaksi
      );
      $hasil = json_decode($this->curl->simple_get(API_URL."/api/setup/minimal/",$param));
      //var_dump($hasil);
      if($hasil){
        if($hasil->totaldata >0){
          foreach ($hasil->message as $key => $value) {
            $result = $value->MIN_VALUE;
          }
        }
      }
    }
    echo $result;
  }
  function uangmuka_sopart(){
    $param=array(
      'kd_dealer' => $this->session->userdata("kd_dealer"),
      'query'   => "SELECT P.KD_DEALER,P.NO_TRANS,P.TGL_TRANS,P.BOOKING_ORDER,P.ORDER_TO,PD.HARGA_JUAL,
                    PC.NAMA_CUSTOMER,PC.ALAMAT_SURAT,PC.NAMA_KABUPATEN
                    FROM TRANS_PARTSO P
                    LEFT JOIN 
                    (SELECT SUM(HARGA_JUAL-DISKON) HARGA_JUAL,NO_TRANS FROM TRANS_PARTSO_DETAIL WHERE ROW_STATUS >=0 GROUP BY NO_TRANS) PD ON PD.NO_TRANS=P.NO_TRANS
                    LEFT JOIN TRANS_PARTSO_CUSTOMER_V PC ON PC.NO_TRANS=P.NO_TRANS
                    WHERE P.ROW_STATUS >=0 AND PC.ROW_STATUS >=0
                    AND KD_DEALER='".$this->session->userdata("kd_dealer")."' AND BOOKING_ORDER=1 AND P.SO_STATUS=0"
    );
    $hasil = json_decode($this->curl->simple_get(API_URL."/api/inventori/partso/",$param));
    echo json_encode($hasil);
  }
  /**
   * [data_output description]
   * @param  [type] $hasil    [description]
   * @param  string $method   [description]
   * @param  string $location [description]
   * @return [type]           [description]
   */
  function data_output($hasil = NULL, $method = '', $location = '') {
    $result = array();
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