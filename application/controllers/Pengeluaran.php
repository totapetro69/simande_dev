<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengeluaran extends CI_Controller {
    var $API;
    /**
     * [__construct description]
     */
    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->library('template');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper("zetro");
    }
    public function pengeluaran()
    {
        $data = array();
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TRANS_SJKELUAR.TGL_SURATJALAN,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_SJKELUAR.TGL_SURATJALAN,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;
        $param = array(
            'custom' => $tgl,
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable'  => array(
                                array("TRANS_SPK as SPK","SPK.NO_SO=TRANS_SJKELUAR.NO_REFF AND SPK.ROW_STATUS >= 0","LEFT")            
                            ),
            'field' => "TRANS_SJKELUAR.*, SPK.TYPE_PENJUALAN",
            'orderby' => 'TRANS_SJKELUAR.NO_SURATJALAN desc',
            'limit' => 15
        );
        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in']  = isDealerAkses();
            $param['where_in_field'] ='TRANS_SJKELUAR.KD_DEALER';
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));
        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar_detail"));
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
        
        // $this->output->set_output(json_encode($data["list"]));
        $this->template->site('inventori/pengeluaran_motor',$data);
    }
   public function sjkeluar_typeahead() {
        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param=array(
            'custom' => $kd_dealer,
            'row_status'=>0,
            'field' => 'NO_SURATJALAN, NO_REFF, NAMA_PENGIRIM,NO_MOBIL',
            'groupby' => true
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));
        $data_message="";
        if(is_array($data["list"]->message)){
            foreach ($data["list"]->message as $key => $message) {
               $data_message[0][$key] = $message->NO_SURATJALAN;
               $data_message[1][$key] = $message->NAMA_PENGIRIM;
               $data_message[2][$key] = $message->NO_MOBIL;
               $data_message[3][$key] = $message->NO_REFF;
            }
            $data_message = array_merge($data_message[0], $data_message[1], $data_message[2], $data_message[3]);
        }
        $result['keyword']=$data_message;
        $this->output->set_output(json_encode($result));
    }
    public function add_pengeluaran($tipe = null)
    {
        $data = array();
        if($this->input->get("n")){
            $param = array(
                'keyword' => base64_decode(urldecode($this->input->get("n")))
            );
            $data["sjheader"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sjkeluar", $param));
            $params= array(
                'id_suratjalan' =>$data["sjheader"]->message[0]->ID
            );
            $details=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar_detail", $params));
            $data['kendaraan'] = '';
            $data['ksu'] = '';
            $data['hadiah'] = '';
            $data['barang'] = '';
            
            if(isset($details)){
                if($details->totaldata >0){
                    $data['kendaraan'] .= $this->table_kendaraan($details->message);
                    $data['ksu'] .= $this->table_ksu($details->message);
                    $data['hadiah'] .= $this->table_hadiah($details->message);
                    $data['barang'] .= $this->table_barang($details->message);
                }
            }

        }
        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            'custom' => "(STATUS_SPK = 2 OR STATUS_SPK = 3) AND ".$kd_dealer,
            'jointable' => array(
                array("TRANS_SPK_DETAILKENDARAAN SDK" , "SDK.SPK_ID=TRANS_SPK.ID AND SDK.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW MCV" , "MCV.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MCV.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'TRANS_SPK.NO_SO, TRANS_SPK.KD_DEALER, MCV.NAMA_CUSTOMER',
            'groupby' => TRUE
        );
        if($this->input->get("n")){
            foreach ($data["sjheader"]->message as $key => $value) {
                $NO_SO          = $value->NO_REFF;
            }
            $param['custom'] = "TRANS_SPK.NO_SO = '".$NO_SO."'";
        }
        $data["so"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        $param=array(
            'custom' => $kd_dealer
        );
        $data["gudang"]=json_decode($this->curl->simple_get(API_URL."/api/master/gudang",$param));

        //if($this->input->get('no_mobil')){
            //$param['no_polisi'] = $this->input->get('no_mobil');
        //}
        
        
	    if($this->input->get("kd_dealer")){
	      $param['kd_dealer'] = $this->input->get("kd_dealer");
	    }else{
	      $param['where_in']  = isDealerAkses();
	      $param['where_in_field'] = 'USERS.KD_DEALER';
	    }
        
        $data["mobil"]=json_decode($this->curl->simple_get(API_URL."/api/master_general/mobil",$param));

        $data["supir"]=json_decode($this->curl->simple_get(API_URL."/api/master_general/supir",$param));



        // $temp = '1234567890';
        // $temp = rand(1000, 3);
        // $data["barcode"]=$this->set_barcode($temp);

        if($tipe == null){
            $this->template->site('inventori/add_pengeluaran', $data);
        }
        else{
            $this->output->set_output(json_encode($data));
        }
    }
    public function get_so(){
        $no_so = $this->input->get('no_so');
        $kd_dealer = $this->input->get('kd_dealer');
        $data['kendaraan'] = '';
        $data['ksu'] = '';
        $data['hadiah'] = '';
        $data['barang'] = '';
        $param = array(
            'no_so' => $no_so,
            'jointable' => array(
                array("TRANS_SPK_DETAILKENDARAAN SDK" , "SDK.SPK_ID=TRANS_SPK.ID AND SDK.ROW_STATUS>=0 AND SDK.NO_MESIN != ''", "RIGHT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=CONCAT(SDK.KD_TYPEMOTOR,'-',SDK.KD_WARNA) AND MP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_TERIMASJMOTOR AS TT" , "TT.NO_MESIN=SDK.NO_MESIN AND TT.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_DETAILALAMAT as SDA","SDA.SPK_ID=TRANS_SPK.ID AND SDA.ROW_STATUS>=0","LEFT"),
                array("MASTER_DEALER MD" , "MD.KD_DEALER=TRANS_SPK.KD_DEALER", "LEFT"),
                array("MASTER_GUDANG MG" , "MG.KD_DEALER=TRANS_SPK.KD_DEALER AND MG.JENIS_GUDANG = 'Unit' AND MG.DEFAULTS=1 AND MG.ROW_STATUS>=0", "LEFT"),
                array("MASTER_COMPANY MC" , "MC.KD_DEALER=TRANS_SPK.KD_DEALER AND MC.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'TRANS_SPK.*, 
                SDK.ID as SDK_ID, 
                MP.KD_ITEM,
                MP.NAMA_PASAR,
                SDK.KD_WARNA,
                SDK.JUMLAH, 
                SDK.NO_MESIN, 
                SDK.NO_RANGKA,
                SDK.KD_PAKET,
                SDK.KD_SALESKUPON,
                SDK.KD_TYPEMOTOR,
                SDA.NAMA_PENERIMA,
                SDA.ALAMAT_KIRIM,
                SDA.NO_HP,
                SDA.TGL_KIRIM,
                SDA.JAM_KIRIM,
                MD.NAMA_DEALER,
                MC.KEPALA_GUDANG,
                TT.KSU,
                MG.KD_GUDANG',
            'orderby' => 'TRANS_SPK.ID desc',
            'custom' => "TRANS_SPK.KD_DEALER = '".$kd_dealer."' AND SDK.NO_MESIN NOT IN(SELECT TSD.NO_MESIN FROM TRANS_SJKELUAR_DETAIL AS TSD WHERE TSD.ROW_STATUS >= 0 AND TSD.NO_MESIN IS NOT NULL)"
        );
        //print_r($param);
        $data['so_header'] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));
        // var_dump(($data));exit();
        $data['tgl_suratjalan'] = date("d/m/Y");
        if($data['so_header'] && (is_array($data['so_header']->message) || is_object($data['so_header']->message))):
            $data['kendaraan'] .= $this->table_kendaraan($data['so_header']->message);
            $data['ksu'] .= $this->table_ksu($data['so_header']->message);
            $data['hadiah'] .= $this->table_hadiah($data['so_header']->message);
            $data['barang'] .= $this->table_barang($data['so_header']->message);
        else:
            // $data['status'] = false;
            $data['total_data'] =  0;
            $data['kendaraan'] .= "<table class='table table-bordered table-hover b-t b-light'><tbody><tr><td  style='width:40px;'>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=6><b>Belum ada data / data tidak ditemukan</b></td></tr></tbody></table>";
        endif;
        $this->output->set_output(json_encode($data));
    }
    public function table_kendaraan($data_kendaraan){
        $html = '';
        $html .= '<table id="table_kendaraan" class="table table-bordered b-t b-light">';
        $html .= '<tbody class="thead-alias">';
        // $html .= '<thead>';
        $html .= '<tr class="thead-alias-tr no-hover"><th colspan="7" >Detail Kendaraan</th></tr>';
        $html .= '<tr class="thead-alias-tr no-hover">';
        $html .= '<th>No.</th>';
        $html .= '<th>Kirim</th>';
        $html .= '<th>Kode Item</th>';
        $html .= '<th>Nama Item</th>';
        $html .= '<th>No. Mesin</th>';
        $html .= '<th>No. Rangka</th>';
        $html .= '<th>Jumlah</th>';
        $html .= '</tr>';
        // $html .= '</thead>';
        // $html .= '<tbody>';
        $no_kendaraan = 1;
        $norut = 0;
        foreach ($data_kendaraan as $key => $kendaraan) {
        if(base64_decode(urldecode($this->input->get("n")))){
            if($kendaraan->KET_UNIT != 'KSU' && $kendaraan->KET_UNIT != 'HADIAH' && $kendaraan->KET_UNIT != 'BARANG'){
            $html .= '<tr>';
            $html .= '<td class="table-nowarp">'.$no_kendaraan.'</td>';
            $html .= '<td class="table-nowarp"><input class="status_kirim status_kirim_'.$norut.'" name="status_kirim" value="1" type="checkbox" checked disabled></td>';
            $html .= '<td class="table-nowarp">'.$kendaraan->KET_UNIT.'</td>';
            $html .= '<td class="td-overflow" title="'.$kendaraan->NAMA_ITEM.'">'.$kendaraan->NAMA_ITEM.'</td>';
            $html .= '<td class="table-nowarp">'.$kendaraan->NO_MESIN.'</td>';
            $html .= '<td class="table-nowarp">'.$kendaraan->NO_RANGKA.'</td>';
            $html .= '<td class="table-nowarp">'.$kendaraan->JUMLAH.'</td>';
            $html .= '</tr>';
            $norut++;
            $no_kendaraan++;
            }
        }
        else{
            // $cek_kendaraan = $this->cek_kendaraan($kendaraan->NO_MESIN);
                $html .= '<tr class="tr-count">';
                $html .= '<input type="hidden" id="nama_item_kendaraan_'.$norut.'" value="'.$kendaraan->NAMA_PASAR.'">';
                $html .= '<input type="hidden" id="jumlah_kendaraan_'.$norut.'" value="'.$kendaraan->JUMLAH.'">';
                $html .= '<input type="hidden" id="ket_unit_kendaraan_'.$norut.'" value="'.$kendaraan->KD_ITEM.'">';
                $html .= '<input type="hidden" id="kd_warna_kendaraan_'.$norut.'" value="'.$kendaraan->KD_WARNA.'">';
                $html .= '<input type="hidden" class="no_mesin_kendaraan" id="no_mesin_kendaraan_'.$norut.'" value="'.$kendaraan->NO_MESIN.'">';
                $html .= '<input type="hidden" id="no_rangka_kendaraan_'.$norut.'" value="'.$kendaraan->NO_RANGKA.'">';
                $html .= '<td>'.$no_kendaraan.'</td>';
                $html .= '<td><input class="status_kirim status_kirim_'.$norut.'" name="status_kirim" value="1" type="checkbox" checked></td>';
                $html .= '<td>'.$kendaraan->KD_ITEM.'</td>';
                $html .= '<td>'.$kendaraan->NAMA_PASAR.'</td>';
                $html .= '<td>'.$kendaraan->NO_MESIN.'</td>';
                $html .= '<td>'.$kendaraan->NO_RANGKA.'</td>';
                $html .= '<td>'.$kendaraan->JUMLAH.'</td>';
                $html .= '</tr>';
                $norut++;
                $no_kendaraan++;
        }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
    public function cek_stock($kd_item, $total_kendaraan){
        $stock = 0;
        $param = array(
            'custom' => "KD_ITEM ='".$kd_item."'"
        );
        switch ($kd_item){
            case 'BPPSG_':
                $total_data = 2;
                break;
            case 'HELM_':
                $total_data = 1;
                break;
            default:
                $total_data = 100000000000;
                break;
        }
        if($total_kendaraan <= $total_data)
        {
            $stock = $total_kendaraan;
        }
        else{
            $stock = $total_data;
        }
        return $stock;
    }
    public function recek_ksu($ksu_stock){
        $html = '';
        $html .= '<table id="table_ksu" id="table_ksu" class="table table-bordered b-t b-light">';
        $html .= '<thead>';
        $html .= '<tr class="thead-alias-tr no-hover"><th colspan="3" >KSU</th></tr>';
        $html .= '<tr class="thead-alias-tr no-hover">';
        $html .= '<th>No.</th>';
        $html .= '<!-- <th>No Terima SJ</th> -->';
        $html .= '<th>Nama KSU</th>';
        $html .= '<th>Jumlah</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $param = array(
            'custom' => 'STATUS_KIRIM = 1'
        );
        $ksu=json_decode($this->curl->simple_get(API_URL."/api/inventori/ksu", $param));
        $no_ksu = 1;
        $norut = 0;
        foreach ($ksu->message as $key => $ksu_totrow) {
            $cek_stock = $this->cek_stock($ksu_totrow->KD_KSU, $ksu_stock);
            $html .= '<tr>';
            $html .= '<input type="hidden" id="nama_item_ksu_'.$norut.'" value="'.$ksu_totrow->KD_KSU.'">';
            $html .= '<input class="ksu_stock_value" type="hidden" id="jumlah_ksu_'.$norut.'" value="'.$cek_stock.'">';
            $html .= '<input type="hidden" id="ket_unit_ksu_'.$norut.'" value="KSU">';
            $html .= '<td>'.$no_ksu.'</td>';
            $html .= '<td>'.$ksu_totrow->KD_KSU.'</td>';
            $html .= '<td class="data_stock">'.$cek_stock.'</td>';
            $html .= '</tr>';
            $norut++;
            $no_ksu++;
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $this->output->set_output(json_encode($html));
        // return $html;
    }
    public function table_ksu($data_kendaraan){
        $html = '';
        $html .= '<table id="table_ksu" class="table table-bordered b-t b-light">';
        $html .= '<tbody class="thead-alias">';
        // $html .= '<thead>';
        $html .= '<tr class="thead-alias-tr no-hover"><th colspan="3" >KSU</th></tr>';
        $html .= '<tr class="thead-alias-tr no-hover">';
        $html .= '<th style="width:40px;">No.</th>';
        $html .= '<!-- <th>No Terima SJ</th> -->';
        $html .= '<th>Nama KSU</th>';
        $html .= '<th>Jumlah</th>';
        $html .= '</tr>';
        // $html .= '</thead>';
        // $html .= '<tbody>';
        if(base64_decode(urldecode($this->input->get("n")))){
            $no_kendaraan = 1;
            $norut = 0;
            foreach ($data_kendaraan as $key => $kendaraan) {
                if($kendaraan->KET_UNIT == 'KSU'){
                $html .= '<tr>';
                $html .= '<td class="table-nowarp">'.$no_kendaraan.'</td>';
                $html .= '<td class="table-nowarp">'.$kendaraan->NAMA_ITEM.'</td>';
                $html .= '<td class="table-nowarp">'.$kendaraan->JUMLAH.'</td>';
                $html .= '</tr>';
                $norut++;
                $no_kendaraan++;
                }
            }
        }
        else{
            $total_kendaraan = count($data_kendaraan);
            $param = array(
                'custom' => 'STATUS_KIRIM = 1'
            );
            $ksu=json_decode($this->curl->simple_get(API_URL."/api/inventori/ksu", $param));
        // var_dump(($ksu));exit();
            // $ksu_total = $this->merge_ksu($data_kendaraan);
            $no_ksu = 1;
            $norut = 0;
            if($ksu AND (is_array($ksu->message) || is_object($ksu->message))){
            foreach ($ksu->message as $key => $ksu_totrow) {
                $cek_stock = $this->cek_stock($ksu_totrow->KD_KSU, $total_kendaraan);
                $html .= '<tr class="tr-count">';
                $html .= '<input type="hidden" id="nama_item_ksu_'.$norut.'" value="'.$ksu_totrow->KD_KSU.'">';
                $html .= '<input class="ksu_stock_value" type="hidden" id="jumlah_ksu_'.$norut.'" value="'.$cek_stock.'">';
                $html .= '<input type="hidden" id="ket_unit_ksu_'.$norut.'" value="KSU">';
                $html .= '<td class="table-nowarp">'.$no_ksu.'</td>';
                $html .= '<td class="table-nowarp">'.$ksu_totrow->KD_KSU.'</td>';
                $html .= '<td class="data_stock">'.$cek_stock.'</td>';
                $html .= '</tr>';
                $norut++;
                $no_ksu++;
            }
            }
        }

        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
    public function recek_hadiah(){
        $hadiah_data = $this->input->post('hadiah');
        $data = '';
        // if($hadiah_data != null){
            $in = str_replace(',', "','", $hadiah_data);
            $param = array(
                'keyword' => $this->input->post('no_so'),
                'custom' => "SDK.NO_MESIN IN ('".$in."') AND SDK.NO_MESIN != '' AND SDK.NO_MESIN NOT IN(SELECT TSD.NO_MESIN FROM TRANS_SJKELUAR_DETAIL AS TSD WHERE TSD.ROW_STATUS >= 0 AND TSD.NO_MESIN IS NOT NULL)",
                'jointable' => array(
                    array("TRANS_SPK_DETAILKENDARAAN SDK" , "SDK.SPK_ID=TRANS_SPK.ID AND SDK.ROW_STATUS>=0", "RIGHT")
                ),
                'field' => '
                    TRANS_SPK.KD_DEALER, 
                    SDK.NO_MESIN, 
                    SDK.KD_PAKET,
                    SDK.KD_SALESKUPON,
                    SDK.KD_TYPEMOTOR',
                'orderby' => 'TRANS_SPK.ID desc'
            );
            $hadiah = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));
            $data = $this->table_hadiah($hadiah->message);
        // }
        $this->output->set_output(json_encode($data));
    }
    public function table_hadiah($data_kendaraan){
        $html = '';        
        $html .= '<table id="table_hadiah" class="table table-bordered b-t b-light">';
        $html .= '<tbody class="thead-alias">';
        // $html .= '<thead>';
        $html .= '<tr class="thead-alias-tr no-hover"><th colspan="3" >List Hadiah</th></tr>';
        $html .= '<tr class="thead-alias-tr no-hover">';
        $html .= '<th style="width:40px;">No.</th>';
        $html .= '<th>Nama Item</th>';
        $html .= '<th>Jumlah</th>';
        $html .= '</tr>';
        // $html .= '</thead>';
        // $html .= '<tbody>';
        if(base64_decode(urldecode($this->input->get("n")))){
            $no_kendaraan = 1;
            $norut = 0;
            foreach ($data_kendaraan as $key => $kendaraan) {
                if($kendaraan->KET_UNIT == 'HADIAH'){
                $html .= '<tr>';
                $html .= '<td>'.$no_kendaraan.'</td>';
                $html .= '<td>'.$kendaraan->NAMA_ITEM.'</td>';
                $html .= '<td>'.$kendaraan->JUMLAH.'</td>';
                $html .= '</tr>';
                $norut++;
                $no_kendaraan++;
                }
            }
        }
        else{
            $get_hadiah = $this->get_hadiah($data_kendaraan);
            $no_hadiah = 1;
            $norut = 0;
            if(is_array($get_hadiah)){
            foreach ($get_hadiah as $key => $hadiah_totrow) {
            $html .= '<tr class="tr-count">';
            $html .= '<input type="hidden" id="nama_item_hadiah_'.$norut.'" value="'.$key.'">';
            $html .= '<input type="hidden" id="jumlah_hadiah_'.$norut.'" value="'.$hadiah_totrow.'">';
            $html .= '<input type="hidden" id="ket_unit_hadiah_'.$norut.'" value="HADIAH">';
            $html .= '<td>'.$no_hadiah.'</td>';
            $html .= '<td>'.$key.'</td>';
            $html .= '<td class="data_stock">'.$hadiah_totrow.'</td>';
            $html .= '</tr>';
            $norut++;
            $no_hadiah++;
            }
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
    public function get_hadiah($data_hadiah){
        $data = array();
        /*var_dump($data_hadiah);
        exit();*/
        if(is_array($data_hadiah)){
        foreach ($data_hadiah as $key => $hadiah) {
            // $cek_kendaraan = $this->cek_kendaraan($hadiah->NO_MESIN);
                $saleskupon_data = $this->cek_saleskupon('saleskupon', $hadiah->KD_SALESKUPON, $hadiah->KD_TYPEMOTOR);
                ($saleskupon_data != ''? array_push($data,$saleskupon_data):'');
                $saleskuponkota_data = $this->cek_saleskupon('saleskuponkota', $hadiah->KD_SALESKUPON, $hadiah->KD_DEALER);
                ($saleskuponkota_data != ''? array_push($data,$saleskuponkota_data):'');
                $saleskuponleasing_data = $this->cek_saleskupon('saleskuponleasing', $hadiah->KD_SALESKUPON);
                ($saleskuponleasing_data != ''? array_push($data,$saleskuponleasing_data):'');
                $sohadiah_data = $this->cek_saleskupon('sohadiah', $hadiah->KD_PAKET);
                ($sohadiah_data != ''? array_push($data,$sohadiah_data):'');
        }
        $data = array_count_values($data);
        }
        /*var_dump($data);
        exit();*/
        return $data;
    }
    public function table_barang($data_kendaraan){
        $html = '';        
        $html .= '<table id="table_barang" class="table table-bordered b-t b-light">';
        $html .= '<tbody class="thead-alias">';
        // $html .= '<thead>';
        $html .= '<tr class="thead-alias-tr no-hover"><th colspan="3" >List Barang</th></tr>';
        $html .= '<tr class="thead-alias-tr no-hover">';
        $html .= '<th style="width:40px;">No.</th>';
        $html .= '<th>Nama Item</th>';
        $html .= '<th>Jumlah</th>';
        $html .= '</tr>';
        // $html .= '</thead>';
        // $html .= '<tbody>';
        if(base64_decode(urldecode($this->input->get("n")))){
            $no_kendaraan = 1;
            $norut = 0;
            foreach ($data_kendaraan as $key => $kendaraan) {
                if($kendaraan->KET_UNIT == 'BARANG'){
                $html .= '<tr>';
                $html .= '<td>'.$no_kendaraan.'</td>';
                $html .= '<td>'.$kendaraan->NAMA_ITEM.'</td>';
                $html .= '<td>'.$kendaraan->JUMLAH.'</td>';
                $html .= '</tr>';
                $norut++;
                $no_kendaraan++;
                }
            }
        }
        else{
            // $total_kendaraan = count($data_kendaraan);

            $param = array(
                // 'kd_dealer' => 'CH5'
                'kd_dealer' => $data_kendaraan[0]->KD_DEALER,
                'jointable' => array(
                    array("MASTER_BARANG MB" , "MB.KD_BARANG=SETUP_BARANG.KD_BARANG AND MB.ROW_STATUS >= 0", "RIGHT")
                ),
                'field' => 'MB.NAMA_BARANG, SETUP_BARANG.*'
            );
            $barang=json_decode($this->curl->simple_get(API_URL."/api/inventori/setup_barang", $param));
            // var_dump($barang);exit();
            
            $no_barang = 1;
            $norut = 0;
            if($barang && is_array($barang->message)){
            foreach ($barang->message as $key => $barang_totrow) {
            $html .= '<tr class="tr-count">';
            $html .= '<input type="hidden" id="kode_item_barang_'.$norut.'" value="'.$barang_totrow->KD_BARANG.'">';
            $html .= '<input type="hidden" id="nama_item_barang_'.$norut.'" value="'.$barang_totrow->NAMA_BARANG.'">';
            $html .= '<input type="hidden" id="jumlah_barang_'.$norut.'" value="'.$barang_totrow->QTY_DEFAULT.'">';
            $html .= '<input type="hidden" id="ket_unit_barang_'.$norut.'" value="BARANG">';
            $html .= '<td>'.$no_barang.'</td>';
            $html .= '<td>'.$barang_totrow->NAMA_BARANG.'</td>';
            $html .= '<td class="data_stock">'.$barang_totrow->QTY_DEFAULT.'</td>';
            $html .= '</tr>';
            $norut++;
            $no_barang++;
            }
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
    public function cek_saleskupon($jenis_saleskupon, $kd_saleskupon, $kd_typemotor_dealer=null){
        $result = '';
        if($jenis_saleskupon == 'saleskupon'){
            $param = array(
                'custom' => "KD_SALESKUPON = '".$kd_saleskupon."' AND KD_TYPEMOTOR = '".$kd_typemotor_dealer."' AND NAMA_SALESKUPON LIKE '%HADIAH%' ",
                'field' => 'NAMA_SALESKUPON'
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/setup/kupon", $param));
        }
        elseif($jenis_saleskupon == 'saleskuponkota'){
            $param = array(
                'custom' => "KD_SALESKUPON = '".$kd_saleskupon."' AND KD_DEALER = '".$kd_typemotor_dealer."' AND NAMA_SALESKUPON LIKE '%HADIAH%' ",
                'field' => 'NAMA_SALESKUPON'
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/setup/saleskuponkota", $param));
        }
        elseif($jenis_saleskupon == 'saleskuponleasing'){
            $param = array(
                'custom' => "KD_SALESKUPON = '".$kd_saleskupon."' AND NAMA_SALESKUPON LIKE '%HADIAH%' ",
                'field' => 'NAMA_SALESKUPON'
            );
            $data = json_decode($this->curl->simple_get(API_URL . "/api/setup/saleskuponleasing", $param));
        }
        elseif($jenis_saleskupon == 'sohadiah'){
            $param=array(
                'custom' => "KD_PROGRAM = '".$kd_saleskupon."'",
                'field' => 'NAMA_PROGRAM AS NAMA_SALESKUPON'
            );
            $data = json_decode($this->curl->simple_get(API_URL."/api/sales/so_programhadiah", $param));
        }
        if($data->message > 0)
        {
            $result = $data->message[0]->NAMA_SALESKUPON;
        }
        return $result;
    }
    public function merge_ksu($ksu_data){
        $ksu_array = array();
        foreach ($ksu_data as $key => $ksu) {
            if($ksu->KSU != '')
            {
                $ksu_list = explode(", ", $ksu->KSU);
                array_push($ksu_array,$ksu_list);
            }
        }
        $ksu_total = array();
        if(is_array($ksu_array)){
            $ksu_result = array();
            foreach ($ksu_array as $array) {
                $ksu_result = array_merge($ksu_result, $array);
            }
            $ksu_total = array_count_values($ksu_result);
        }
        return $ksu_total;
    }
    public function store_sjkeluar(){
        $return_detail = false;

        $no_suratjalan = ($this->input->post('no_suratjalan') ? $this->input->post('no_suratjalan') : $this->getnopo());

        $param = array
        (
            'no_suratjalan' => $no_suratjalan,
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->input->post('kd_dealer'),
            'tgl_suratjalan' => $this->input->post('tgl_suratjalan'),
            'kd_gudang' => $this->input->post('kd_gudang'),
            'no_reff' => $this->input->post('no_so'),
            'kd_customer' => $this->input->post('kd_customer'),
            'alamat_kirim' => $this->input->post('alamat_kirim'),
            'tgl_kirim' => $this->input->post('tgl_suratjalan'),
            'nama_pengirim' => $this->input->post('nama_pengirim'),
            'nama_ekspedisi' => $this->input->post('nama_ekspedisi'),
            'no_mobil' => $this->input->post('no_mobil'),
            'nama_sopir' => $this->input->post('nama_sopir'),
            'nama_penerima' => $this->input->post('nama_penerima'),
            'tgl_terima' => NULL,
            'status_sj' => 'process',
            'keterangan' => NULL,
            'tgl_estimasikirim' => tglfromSql($this->input->post('tgl_estimasikirim')),
            'waktu_estimasikirim' => $this->input->post('waktu_estimasikirim'),
            'created_by' => $this->session->userdata("user_id"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = ($this->curl->simple_post(API_URL . "/api/inventori/sjkeluar", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $datax=json_decode($hasil);
        $method = "post";
        if($hasil){
            if($datax->message > 0){
                $param_spk = array(
                    'id' => $this->input->post('spk_id'),
                    'status_spk' => $this->input->post('status_spk'),
                    'lastmodified_by'=> $this->session->userdata('user_id')
                );
                $hasil_spk = ($this->curl->simple_put(API_URL."/api/spk/spk_status",$param_spk, array(CURLOPT_BUFFERSIZE => 10)));
                $datax_spk=json_decode($hasil_spk);
                if($hasil_spk)
                {
                    $return_detail = $this->store_detailsj($datax->message);
                }
            }
        }

        if($datax->recordexists==TRUE){

            $hasil= $this->curl->simple_put(API_URL."/api/inventori/sjkeluar_bynosj",$param, array(CURLOPT_BUFFERSIZE => 10));  

            $method = "put";

        }
        
        /*if($return_detail == true) */$this->data_output($hasil, $method, base_url('pengeluaran/add_pengeluaran?n='.urlencode(base64_encode($no_suratjalan))));

    }
    public function delete_sjkeluar($no_suratjalan)
    {
        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        $data = array();
        $params = array(
            'no_suratjalan' => $no_suratjalan,
            'jointable' =>array(
                array("TRANS_SPK AS SP" , "SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0", "LEFT")
            ),
            'field' => 'TRANS_SJKELUAR.ID, SP.ID as SPK_ID'
        );
        $hasil=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$params));
/*
                $param_spk = array(
                    'id' => $hasil->message[0]->SPK_ID,
                    'status_spk' => 2,
                    'lastmodified_by'=> $this->session->userdata('user_id')
                );
        var_dump($param_spk);
        exit;*/
        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/inventori/sjkeluar",$param));
        if ($data) {
            if($hasil->message>0){
                $param_spk = array(
                    'id' => $hasil->message[0]->SPK_ID,
                    'status_spk' => 2,
                    'lastmodified_by'=> $this->session->userdata('user_id')
                );
                $spk = $this->curl->simple_put(API_URL."/api/spk/spk_status",$param_spk, array(CURLOPT_BUFFERSIZE => 10));
                $param_sj = array(
                    'id_suratjalan' => $hasil->message[0]->ID,
                    'lastmodified_by'=> $this->session->userdata('user_id')
                );
                $hasil=$this->curl->simple_delete(API_URL."/api/inventori/sjkeluar_detail",$param_sj, array(CURLOPT_BUFFERSIZE => 10));
            }
            $this->data_output($data, 'delete', base_url('pengeluaran/pengeluaran'));
        }
    }
    public function store_detailsj($id_suratjalan)
    {
        $detail = json_decode($this->input->post("detail"),true);
        for ($i=0; $i < count($detail); $i++) { 
            $param = array(
                'id_suratjalan' => $id_suratjalan,
                'nama_item' => $detail[$i]['nama_item'],
                'kd_warna' => $detail[$i]['kd_warna'],
                'no_mesin' => $detail[$i]['no_mesin'],
                'no_rangka' => $detail[$i]['no_rangka'],
                'jumlah' => $detail[$i]['jumlah'],
                'ket_unit' => $detail[$i]['ket_unit'],
                'created_by' => $this->session->userdata("user_id")
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/inventori/sjkeluar_detail", $param, array(CURLOPT_BUFFERSIZE => 10));
        }

        return true;
    }
    public function sj_keluar($no_suratjalan)
    {
        $data = array();
        $this->auth->validate_authen('pengeluaran/add_pengeluaran');
        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'jointable' => array(
                array("MASTER_DEALER MD" , "MD.KD_DEALER=TRANS_SJKELUAR.KD_DEALER", "LEFT"),
                array("MASTER_KABUPATEN MK" , "MK.KD_KABUPATEN=MD.KD_KABUPATEN", "LEFT"),
                array("MASTER_COMPANY MC" , "MC.KD_DEALER=TRANS_SJKELUAR.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN MKR" , "MKR.NIK=MC.KEPALA_GUDANG", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP","SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILCUSTOMER SPD","SPD.SPK_ID=SP.ID AND SPD.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SM","SM.NO_RANGKA=SJD.NO_RANGKA AND SM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=SJD.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => 'SJD.*, ISNULL(SM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1, SPD.NAMA_BPKB , TRANS_SJKELUAR.*, MD.NAMA_DEALER,MD.ALAMAT, SP.FAKTUR_PENJUALAN, SP.TGL_SPK, MK.NAMA_KABUPATEN, MKR.NAMA AS KEPALA_GUDANG',
            'orderby' => 'SJD.NO_RANGKA desc',
            'custom' => "KET_UNIT NOT IN ('KSU','HADIAH','BARANG')"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$param));
        /*var_dump($data);
        exit;*/
        // $this->output->set_output(json_encode($data));
        $this->load->view('inventori/sj_keluar', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function delivery_unit($no_suratjalan)
    {
        $data = array();
        $this->auth->validate_authen('pengeluaran/pengeluaran');
        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'field' => "TRANS_SJKELUAR.*, CASE WHEN (SELECT COUNT(D.ID) FROM TRANS_SJKELUAR_DETAIL D WHERE D.ID_SURATJALAN = TRANS_SJKELUAR.ID AND D.KET_UNIT = 'BARANG' AND D.ROW_STATUS = 0)>0 THEN 'notallowed' ELSE 'allowed' END COUNT_BARANG"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$param));
       // $this->output->set_output(json_encode($data));
       $this->load->view('inventori/delivery_unit', $data);
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
    }
    public function sj_status()
    {
        // var_dump($_FILES);
        if(!empty($_FILES['bukti_terima']['name'])){
            $config = array();
            $_FILES['pathFile']['name'] = $_FILES['bukti_terima']['name'];
            $_FILES['pathFile']['type'] = $_FILES['bukti_terima']['type'];
            $_FILES['pathFile']['tmp_name'] = $_FILES['bukti_terima']['tmp_name'];
            $_FILES['pathFile']['error'] = $_FILES['bukti_terima']['error'];
            $_FILES['pathFile']['size'] = $_FILES['bukti_terima']['size'];
            $uploadpathGalery = './assets/uploads/';
            $config['upload_path'] = $uploadpathGalery;
            // $config['max_size'] = '8000';
            $config['allowed_types'] = 'jpag|jpg|png';
            $this->load->library('upload', $config, 'pathupload');
            $this->pathupload->initialize($config);
            if (!is_dir('assets/uploads'))
            {
                mkdir('./assets/uploads', 0777, true);
            }
            $upload_path = $this->pathupload->do_upload('pathFile');
            if($upload_path){
                $pathfile = $this->pathupload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $pathfile['full_path']; //get original image
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 350;
                $config['height'] = 300;
                $this->load->library('image_lib', $config, 'path_image_lib');
                $this->path_image_lib->initialize($config);
                if (!$this->path_image_lib->resize()) {
                    $data['message'] = $this->path_image_lib->display_errors();
                    $data['status'] = FALSE;
                    $this->output->set_output(json_encode($data));
                }
                else
                {
                    $param = array(
                        'no_suratjalan' => $this->input->post('no_suratjalan'), 
                        'tgl_terima' => $this->input->post('tgl_terima'), 
                        'waktu_terima' => $this->input->post('waktu_terima'), 
                        'status_sj' => $this->input->post('status_sj'), 
                        'keterangan' => $this->input->post('keterangan'), 
                        'bukti_terima' => "assets/uploads/".$pathfile['file_name'], 
                        'lastmodified_by' => $this->session->userdata("user_id")
                    );
                    $hasil = $this->curl->simple_put(API_URL . "/api/inventori/sjkeluar_status", $param, array(CURLOPT_BUFFERSIZE => 10));
                    $this->data_output($hasil, 'put');
                }
            }
            else{
                $data['message'] = $this->pathupload->display_errors();
                $data['status'] = FALSE;
                $this->output->set_output(json_encode($data));
            }
        } /*
        else{
            $data['message'] = 'Bukti Terima tidak boleh kosong';
            $data['status'] = FALSE;
            $this->output->set_output(json_encode($data));
        }*/
    }

    public function cetak_barcode($no_suratjalan,$debug=null)
    {

        $this->load->library('dompdf_gen');

        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'jointable' => array(
                array("TRANS_SPK SPK" , "SPK.NO_SO=TRANS_SJKELUAR.NO_REFF AND SPK.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MPT" , "MPT.KD_ITEM=SJD.KET_UNIT AND MPT.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW MCV","MCV.KD_CUSTOMER=TRANS_SJKELUAR.KD_CUSTOMER AND MCV.ROW_STATUS>=0","LEFT"),
                array("TRANS_STNK_BUKTI TSB","TSB.NO_RANGKA=SJD.NO_RANGKA AND TSB.KETERANGAN = 'PLAT' AND TSB.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SM","SM.NO_RANGKA=SJD.NO_RANGKA AND SM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=SJD.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => 'SJD.NO_RANGKA, SJD.NO_MESIN, TRANS_SJKELUAR.TGL_SURATJALAN, TRANS_SJKELUAR.NAMA_PENERIMA, TRANS_SJKELUAR.ALAMAT_KIRIM, MCV.NO_HP, MCV.ALAMAT_SURAT, MCV.NAMA_KABUPATEN, TSB.DATA_NOMOR, ISNULL(SM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1, MPT.NAMA_TYPEMOTOR, SPK.TGL_SO',
            'orderby' => 'SJD.NO_RANGKA desc',
            'custom' => "KET_UNIT NOT IN ('KSU','HADIAH','BARANG')"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$param));
        // $this->output->set_output(json_encode($data));
        // echo $data['barcode'];

        // $this->load->view('inventori/cetak_barcode', $data);

        
        $html = $this->load->view('inventori/cetak_barcode', $data, true);
        $filename = 'report_'.time();
        
        //satusan dalam kertas adalah point atau pt
        // $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 566.929, 391.181), 'portrait');
        $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 850.3935, 636.7715), 'landscape');
    }



    public function cetak_barcode2($no_suratjalan,$debug=null)
    {

        $this->load->library('dompdf_gen');

        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'jointable' => array(
                array("TRANS_SPK SPK" , "SPK.NO_SO=TRANS_SJKELUAR.NO_REFF AND SPK.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MPT" , "MPT.KD_ITEM=SJD.KET_UNIT AND MPT.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW MCV","MCV.KD_CUSTOMER=TRANS_SJKELUAR.KD_CUSTOMER AND MCV.ROW_STATUS>=0","LEFT"),
                array("TRANS_STNK_BUKTI TSB","TSB.NO_RANGKA=SJD.NO_RANGKA AND TSB.KETERANGAN = 'PLAT' AND TSB.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SM","SM.NO_RANGKA=SJD.NO_RANGKA AND SM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=SJD.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => 'SJD.NO_RANGKA, SJD.NO_MESIN, TRANS_SJKELUAR.TGL_SURATJALAN, TRANS_SJKELUAR.NAMA_PENERIMA, TRANS_SJKELUAR.ALAMAT_KIRIM, MCV.NO_HP, MCV.ALAMAT_SURAT, MCV.NAMA_KABUPATEN, TSB.DATA_NOMOR, ISNULL(SM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1, MPT.NAMA_TYPEMOTOR, SPK.TGL_SO',
            'orderby' => 'SJD.NO_RANGKA desc',
            'custom' => "KET_UNIT NOT IN ('KSU','HADIAH','BARANG')"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$param));
        // $this->output->set_output(json_encode($data));
        // echo $data['barcode'];

        // $this->load->view('inventori/cetak_barcode2', $data);

        
        $html = $this->load->view('inventori/cetak_barcode2', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 453.543, 602.276), 'portrait');
    }

    public function penyerahan_bpkb($no_suratjalan,$debug=null)
    {
        $this->load->library('dompdf_gen');



        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'jointable' => array(
                array("MASTER_DEALER MD" , "MD.KD_DEALER=TRANS_SJKELUAR.KD_DEALER", "LEFT"),
                array("MASTER_KABUPATEN MK" , "MK.KD_KABUPATEN=MD.KD_KABUPATEN", "LEFT"),
                array("MASTER_KARYAWAN MKR" , "MKR.KD_CABANG=TRANS_SJKELUAR.KD_DEALER AND MKR.PERSONAL_JABATAN = 'KSP'", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=SJD.KET_UNIT AND MP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP","SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILCUSTOMER SPD","SPD.SPK_ID=SP.ID AND SPD.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SJM","SJM.NO_MESIN=SJD.NO_MESIN AND SJM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_MESIN=SJD.NO_MESIN AND SAM.ROW_STATUS >= 0","LEFT"),
                array("TRANS_SPK_LEASING SPL","SPL.SPK_ID=SP.ID AND SPL.ROW_STATUS>=0","LEFT"),
                array("MASTER_COM_FINANCE COM","COM.KD_LEASING=SPL.KD_FINCOY AND COM.ROW_STATUS>=0","LEFT")
            ),
            'field' => 'SJD.*, TRANS_SJKELUAR.*, MD.NAMA_DEALER,MD.ALAMAT, SP.FAKTUR_PENJUALAN, MK.NAMA_KABUPATEN, MKR.NAMA AS KSP, MKR.PERSONAL_JABATAN, SPD.NAMA_BPKB, SPD.ALAMAT_BPKB, MP.CC_MOTOR, SJM.THN_PERAKITAN, ISNULL(SJM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1, SPL.KD_FINCOY, COM.NAMA_LEASING',
            'orderby' => 'SJD.NO_RANGKA desc',
            'custom' => "KET_UNIT NOT IN ('KSU','HADIAH','BARANG')"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$param));

        // $this->output->set_output(json_encode($data));
        // $html = $this->load->view('inventori/surat_kuasa', $data);
        
        $html = $this->load->view('inventori/penyerahan_bpkb', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'portrait');

    }


    public function surat_kuasa($no_suratjalan,$debug=null)
    {
        $this->load->library('dompdf_gen');



        $param = array(
            'no_suratjalan' => $no_suratjalan,
            'jointable' => array(
                array("MASTER_DEALER MD" , "MD.KD_DEALER=TRANS_SJKELUAR.KD_DEALER", "LEFT"),
                array("MASTER_MAINDEALER MDL" , "MDL.KD_MAINDEALER=MD.KD_MAINDEALER", "LEFT"),
                array("MASTER_KABUPATEN MK" , "MK.KD_KABUPATEN=MD.KD_KABUPATEN", "LEFT"),
                array("MASTER_COMPANY MC" , "MC.KD_DEALER=TRANS_SJKELUAR.KD_DEALER", "LEFT"),
                array("MASTER_KARYAWAN MKR" , "MKR.NIK=MC.PIMPINAN_DEALER", "LEFT"),
                array("TRANS_SJKELUAR_DETAIL SJD" , "SJD.ID_SURATJALAN=TRANS_SJKELUAR.ID AND SJD.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=SJD.KET_UNIT AND MP.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK SP","SP.NO_SO=TRANS_SJKELUAR.NO_REFF AND SP.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILCUSTOMER SPD","SPD.SPK_ID=SP.ID AND SPD.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SJM","SJM.NO_MESIN=SJD.NO_MESIN AND SJM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_MESIN=SJD.NO_MESIN AND SAM.ROW_STATUS >= 0","LEFT"),
                array("TRANS_SPK_LEASING SPL","SPL.SPK_ID=SP.ID AND SPL.ROW_STATUS>=0","LEFT"),
                array("MASTER_COM_FINANCE COM","COM.KD_LEASING=SPL.KD_FINCOY AND COM.ROW_STATUS>=0","LEFT")
            ),
            'field' => 'SJD.*, TRANS_SJKELUAR.*, MD.NAMA_DEALER,MD.ALAMAT, SP.FAKTUR_PENJUALAN, MK.NAMA_KABUPATEN, MKR.NAMA AS KEPALA_GUDANG, MKR.PERSONAL_JABATAN, SPD.NAMA_BPKB, SPD.ALAMAT_BPKB, SPD.NAMA_KELURAHAN, SPD.NAMA_KECAMATAN, MP.CC_MOTOR, MP.NAMA_TYPEMOTOR, MP.KET_WARNA, SJM.THN_PERAKITAN, ISNULL(SJM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1, SPL.KD_FINCOY, COM.NAMA_LEASING, MDL.NAMA_MAINDEALER, MDL.ALAMAT AS ALAMAT_MAINDEALER',
            'orderby' => 'SJD.NO_RANGKA desc',
            'custom' => "KET_UNIT NOT IN ('KSU','HADIAH','BARANG')"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/sjkeluar",$param));

        // $this->output->set_output(json_encode($data));
        // $html = $this->load->view('inventori/surat_kuasa', $data);
        
        $html = $this->load->view('inventori/surat_kuasa', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'portrait');

    }
    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo(){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => 'DO',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tahun_docno'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'Delivery Order',
            'reset_docno' => 1,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'limit'     => 1,
            'offset'    => 0
        );
        $bulan_kirim = substr($this->input->post('tahun_docno'), 3, 2);
        $nomor_po=$this->curl->simple_get(API_URL."/api/setup/docno",$param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomor_po == 0) {
            $nopo = "DO" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
            $param['urutan_docno'] = $nomor_po+1;
            $this->curl->simple_post(API_URL."/api/setup/setup_docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        } else {
            $nomorpo = $nomor_po+1;
            $nopo = "DO" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
            $param['urutan_docno'] = $nomor_po;
            $this->curl->simple_put(API_URL."/api/setup/docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        }
        //var_dump($nopo);exit();
        return $nopo;
    }    
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
}