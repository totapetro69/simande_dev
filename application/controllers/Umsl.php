<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Umsl extends CI_Controller {

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

    /**
     * lst surat jalan masuk yng sudah di download dari webservice
     * @return [type] [description]
     */
    public function listsj() {
        $data = array();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];


        $param = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'field' => "TGL_SJMASUK,NO_SJMASUK,KD_DEALER,KD_MAINDEALER,NO_REFF,NO_FAKTUR,NO_PO,NO_POMD,EXPEDISI,NOPOL,NAMA_TAGIHAN",
            'groupby' => TRUE,
            'orderby' => "TGL_SJMASUK DESC,NO_SJMASUK DESC",
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => $config['per_page'],
        );

        $data["listh"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));
        $datas = array();
        if(isset($data["listh"])){
            if (($data["listh"]->totaldata >0)) {
                $i = 0;
                foreach ($data["listh"]->message as $key => $value) {
                    $param = array(
                        'no_sjmasuk' => $value->NO_SJMASUK,
                        'jointable' => array(
                            array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM=CONCAT(TRANS_SJMASUK.KD_TYPEMOTOR,'-',TRANS_SJMASUK.KD_WARNA)", "LEFT")
                        ),
                        'field' => 'NO_SJMASUK,TRANS_SJMASUK.KD_TYPEMOTOR,TRANS_SJMASUK.KD_WARNA,NO_RANGKA,NO_MESIN,STATUS_SJ,MP.KD_ITEM,MP.NAMA_ITEM'
                    );
                    $listd = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param), true);
                    $datas[$i][$value->NO_SJMASUK] = $listd["message"];
                    $i++;
                }
            }
        }
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page']
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));
        $data["listd"] = $datas;
        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('purchasing/tampilan', $data);
    }
    /**
     * [addsjf description]
     * @return [type] [description]
     */
    public function addsjf() {
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["list"] = (array("status" => FALSE, "message" => "Bad request"));
        $data["listmd"] = NULL; //(array("status" => FALSE, "message" => "Bad request"));
        $this->load->view('purchasing/add_sjmasuk', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    /**
     * Download surat jalan masuk 5 hari kebelakang yang sesuai dengan kode dealer
     * webservice /10/kodedealer/list35 yang kemudian gabungkan dengan listsj detail di
     * webservice/nosj[replace / with .(dot)]/list36
     * @return [type] [description]
     */
    public function addsj() {
        ini_set('max_execution_time',300);
        $tglmundur = ($this->input->post('tgl_data')) ? new DateTime(tglToSql($this->input->post('tgl_data'))) : new DateTime();
        $today = new DateTime();
        $jmlhari = $today->diff($tglmundur);
         
        $param = array(
            'link' => 'list35',
            'param' => $jmlhari->days . '/' . $this->input->post("kd_dealer")
        );

        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $nosj = ($this->curl->simple_get(API_URL . "/api/login/webservice", $param));
        //$nosjm=(json_decode($nosj));
        $datas = array();
        if (isset($nosj)) {
            try {
                if ($nosj) {
                    $nosj = json_decode(json_decode($nosj, true), true);
                    //print_r($nosj);
                    $n = 0;
                    for ($i = 0; $i < count($nosj); $i++) {
                        $n++;$datax="";
                        $param = array(
                            'link' => 'list36',
                            'param' => str_replace("/", ".", $nosj[$i]["noSj"])
                        );

                        $datax = ($this->curl->simple_get(API_URL . "/api/login/webservice", $param));
                        if($datax){
                            $datas[$i][str_replace("/", ".", $nosj[$i]["noSj"])] = json_decode(json_decode($datax, true), true);
                        }
                        
                       //echo($i."-".str_replace("/", ".", $nosj[$i]["noSj"])."\n\r");
                    }
                    /*var_dump($datas);
                    exit();*/
                } else {
                    $datas = json_encode(array("status" => FALSE, "message" => "Web service error"));
                }
            } catch (Exception $e) {
                $datas = json_encode(array("status" => FALSE, "message" => "Bad request or " . $e));
            }
        } else {
            $datas = json_encode(array("status" => FALSE, "message" => "Bad request"));
        }
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")/* ,
                  'custom'  => "TGL_SJMASUK between ".tglToSql($this->input->post('tgl_data'))." and GETDATE()" */
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param), true);
        $data["listmd"] = $datas;
        //print_r($datas);exit();
        $html = "";
        $this->load->view('purchasing/add_sjmasuk', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    /**
     * [add_sj_simpan description]
     */
    function add_sj_simpan() {
        $param = array();
        $data = array();
        $pdata = json_decode($this->input->post("data"));

        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $tipem = explode("-", $data[0][$i]["kdItem"]);
            $param = array(
                'no_sjmasuk' => $data[0][$i]["noSj"],
                'tgl_sjmasuk' => tglToSql($data[0][$i]["tglSj"], "-"),
                'kd_dealer' => $data[0][$i]["kdDlr"],
                'kd_maindealer' => $this->session->userdata("kd_maindealer") ,
                'kd_typemotor' => $tipem[0],
                'kd_warna' => $tipem[1],
                'no_rangka' => $data[0][$i]["noRangka"],
                'no_mesin' => $data[0][$i]["noMesin"],
                'thn_perakitan' => $data[0][$i]["thProduksi"],
                'no_reff' => $data[0][$i]["noDo"],
                'expedisi' => $data[0][$i]["ekspedisi"],
                'nopol' => $data[0][$i]["nopolisi"],
                'no_faktur' => $data[0][$i]["nofaktur"],
                'nama_tagihan' => $data[0][$i]["namaTagihan"],
                'vnoRangka1' => $data[0][$i]["vnoRangka1"],
                'no_po' => $data[0][$i]["nopodlr"],
                'no_pomd' => $data[0][$i]["podlrnopoint"],
                'created_by' => $this->session->userdata("user_id") . "- ws_list35-36",
                'lastmodified_by' => $this->session->userdata("user_id") . "- ws_list35-36"
            );

            $hasil = $this->curl->simple_post(API_URL . "/api/purchasing/suratjalan", $param, array(CURLOPT_BUFFERSIZE => 10));
            $method = 'post';

            if(json_decode($hasil)->recordexists==TRUE){
                $hasil= $this->curl->simple_put(API_URL."/api/purchasing/sjmasuk_vnorangka1",$param, array(CURLOPT_BUFFERSIZE => 10));  
                $method = 'put';
            }
             /*print_r($param);
             print_r($hasil);   
              exit(); */
        }

        $this->data_output($hasil, $method);
    }

    /**
     * [editsj description]
     * @param  [type] $no_surat_jalan [description]
     * @return [type]                 [description]
     */
    public function editsj($no_surat_jalan) {
        $param = array(
            'no_surat_jalan' => $no_surat_jalan
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));
        $this->load->view('purchasing/edit_sj', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    /**
     * [updatesj description]
     * @return [type] [description]
     */
    public function updatesj() {
        $hasil = "";
        $param = array(
            'no_surat_jalan' => $this->input->post('no_surat_jalan'),
            'tgl_shipping' => $this->input->post("tgl_shipping"),
            'kd_dealer' => $this->input->post("kd_dealer"),
            'kd_cabang_dealer' => $this->input->post("kd_cabang_dealer"),
            'kd_type_motor' => $this->input->post("kd_type_motor"),
            'kd_warna' => $this->input->post("kd_warna"),
            'no_rangka' => $this->input->post("no_rangka"),
            'no_mesin' => $this->input->post("no_mesin"),
            'thn_perakitan' => $this->input->post("thn_perakitan"),
            'no_ref' => $this->input->post("no_ref"),
            'expedisi' => $this->input->post("expedisi"),
            'no_pol_truk' => $this->input->post("no_pol_truk"),
            'no_faktur' => $this->input->post("no_faktur"),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        //print_r($param);
        $hasil = $this->curl->simple_put(API_URL . "/api/purchasing/suratjalan", $param, array(CURLOPT_BUFFERSIZE => 10));
        $this->data_output($hasil);
    }

    /**
     * [deletesj description]
     * @param  [type] $no_surat_jalan [description]
     * @return [type]                 [description]
     */
    public function deletesj($no_surat_jalan) {
        $param = array(
            'no_surat_jalan' => $no_surat_jalan,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();
        $data["list"] = json_decode($this->curl->simple_delete(API_URL . "/api/purchasing/suratjalan", $param));

        if ($data) {
            $data_status = array(
                'status' => true,
                'message' => 'data berhasil dihapus',
                'location' => base_url('surat_jalan/suratjalan')
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

    /**
     * [terimasj description]
     * @return [type] [description]
     */
    public function terimamotor() {
        $data = array();


        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;


        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'custom' => $kd_dealer." AND ".$tgl,
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'field' => 'NO_TERIMASJM',
            'groupby' => TRUE,
            'orderby' => "NO_TERIMASJM DESC",
            'limit' => 15
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));
           
        $params= array(
            'custom' => $kd_dealer,
            'keyword' => $this->input->get('keyword'),
            'jointable' =>array(
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT")
            ),
            'field' => 'TRANS_TERIMASJMOTOR.*, MP.NAMA_PASAR,
                         "CASE WHEN (SELECT COUNT(NO_MESIN) FROM TRANS_SPK_DETAILKENDARAAN AS TSP WHERE TSP.ROW_STATUS >= 0 AND TSP.NO_MESIN=TRANS_TERIMASJMOTOR.NO_MESIN) >0 THEN 1 ELSE 0 END STATUS_RM"'
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $params));
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",array('custom' => 'NAMA_DEALER IS NOT NULL')));

        
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
        );
        

        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        // $this->output->set_output(json_encode($data));
        $this->template->site('inventori/penerimaan_motor', $data);
    }

    public function addpenerimaan() 
    {
        $data = array();

        $data['total_data'] = 0;


        $kd_dealer = $this->input->get('kd_dealer') ?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");

        $param = array(
            'kd_dealer' => $kd_dealer,
            'custom' => 'STATUS_SJ <= 1 AND STATUS_SJ >=0 ',
            'field' => 'NO_SJMASUK',
            'groupby' => TRUE
        );

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));
        //ambil penerimaan dari 
        $params=array(
            'kd_dealer_tujuan'=>$kd_dealer,
            'custom'    => "ISNULL(APPROVAL_STATUS,0) > 1"
            // 'custom'    => "ISNULL(APPROVAL_STATUS,0) >=0 AND ((STATUS_MUTASI <= 1 AND STATUS_MUTASI >=0) OR STATUS_MUTASI IS NULL)"
        );
        $data["mutasi"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $params));
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        

        //    $this->output->set_output(json_encode($data["mutasi"]));
        $this->template->site('inventori/addpenerimaan', $data);
    }

    public function get_ksu_in($key, $ksu)
    {
        // if($ksu)
        $html= '';


        if($ksu != ''):

        $data = explode(", ",$ksu);

        foreach ($data as $i => $ksu_row) {

            $html .= '<input class="ksu_'.$key.'" name="ksu[]" value="'.$ksu_row.'" type="checkbox" checked disabled> '.$ksu_row.'&nbsp &nbsp';        
        }
        endif;

        return $html;
           
    }

    public function get_ksu_notin($key, $ksu)
    {
        $html= '';
        $ksu_data = str_replace(', ', "','", $ksu);
        // $ksu_data = str_replace(', ', "','", $data["list"]->message[0]->KSU);

        $param_ksu = array(
            'custom' => "KD_KSU NOT IN ('".$ksu_data."')",
            'field' => "KD_KSU"
         );

        $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param_ksu));

        if($data && (is_array($data->message) || is_object($data->message))):
        foreach ($data->message as $i => $ksu_row) {

            $html .= '<input class="ksu ksu_'.$key.'" name="ksu[]" value="'.$ksu_row->KD_KSU.'" type="checkbox"> '.$ksu_row->KD_KSU.'&nbsp &nbsp';        
        }
        endif;

        return $html;
    }

    public function store_penerimaan() {

        if($this->input->post('no_terimasjm'))
        {
            $no_terimasjm = $this->input->post('no_terimasjm');
        }
        elseif($this->session->userdata('no_terimasjm'))
        {
            $no_terimasjm = $this->session->userdata('no_terimasjm');
        }
        else{

            $this->session->set_userdata('no_terimasjm', $this->getnopo());
            $no_terimasjm = $this->session->userdata('no_terimasjm');


            // $this->output->set_output(json_encode($notrans));
        }

        $param = array(
            'id' => $this->input->post('id'),
            'no_terimasjm' => $no_terimasjm,
            'no_sjmasuk' => $this->input->post('no_sjmasuk'),
            'kd_maindealer' => $this->input->post('kd_maindealer'),
            'kd_dealer' => $this->input->post('kd_dealer'),
            'kd_item' => $this->input->post('kd_item'),
            'stock_status' => $this->input->post('tipe_stock'),
            'keterangan_nrfs' => $this->input->post('keterangan_nrfs'),
            'no_rangka' => $this->input->post('no_rangka'),
            'no_mesin' => $this->input->post('no_mesin'),
            'jumlah' => 1,
            'ksu' => $this->input->post('ksu'),
            'expedisi' => $this->input->post('expedisi'),
            'nopol' => $this->input->post('nopol'),
            'kd_gudang' => $this->input->post('kd_gudang'),
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );
        /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/

        $hasil=json_decode($this->curl->simple_post(API_URL."/api/inventori/terimamotor",$param, array(CURLOPT_BUFFERSIZE => 10)));  

        // var_dump(($param));exit();

        if($hasil->recordexists==TRUE){

            $hasil=json_decode($this->curl->simple_put(API_URL."/api/inventori/terimamotor",$param, array(CURLOPT_BUFFERSIZE => 10)));  

        }

        if($hasil)
        {
            if($hasil->message>0){

                $update_sj = $this->update_sj();

            }

        }     


        if($this->input->post('unset') == 'true')
        {
            
            $notrans['status_unset'] = true;

            $this->output->set_output(json_encode($notrans));
        } 
    }

    public function update_sj(){
        $jenis_penerimaan = $this->input->post("jenis_penerimaan");

        $param = array(
            'id' => $this->input->post("id_reff"),
            'lastmodified_by'=> $this->session->userdata('user_id')
        );

        if($jenis_penerimaan == 'TSJ'){
            $param['status_sj'] = $this->input->post("status_sj");

            $hasil=$this->curl->simple_put(API_URL."/api/inventori/sj_terimamotor",$param, array(CURLOPT_BUFFERSIZE => 10));
        }
        else{
            $param['status_mutasi'] = $this->input->post("status_sj");

            $hasil=$this->curl->simple_put(API_URL."/api/inventori/mutasi_terimamotor",$param, array(CURLOPT_BUFFERSIZE => 10));            
        }


        // $this->data_output($hasil);

    }

    public function unset_notrans()
    {
        $this->session->unset_userdata('no_terimasjm');

        $data = array(
            'status' => true,
            'message' => "Data penerimaan berhasil diperbarui"
        );        

        $this->output->set_output(json_encode($data));
    }

    public function tm_typeahead()
    {
        $kd_dealer = $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param=array(
            'custom' => $kd_dealer,
            'row_status'=>0,
            'field' => 'NO_TERIMASJM',
            'groupby' => true
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor",$param));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_TERIMASJM;
        }

        $params=array(
            'custom' => $kd_dealer,
            'row_status'=>0,
            'field' => 'NO_MESIN',
            'groupby' => true
        );
        $data["list_mesin"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor",$params));

        foreach ($data["list_mesin"]->message as $key => $message) {
            $data_message[1][$key] = $message->NO_MESIN;
        }

        $result['keyword'] = array_merge($data_message[0], $data_message[1]);

        $this->output->set_output(json_encode($result));

    }

    public function get_kudang($key, $kd_dealer, $kd_gudang = null)
    {
        $ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');

        $html = '';

        $param=array(
            'custom' => "KD_DEALER = '".$kd_dealer."'"
        );

        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/master/gudang",$param));

        $disable="";
        $html .= '<select name="kd_gudang" id="kd_gudang_'.$key.'" class="form-control" required="true" '.$ROOT.' disabled>';
        $html .= '<option value="">- Pilih Gudang -</option>';

        if($data["list"] && (is_array($data["list"]->message) || is_object($data["list"]->message))):
        foreach ($data["list"]->message as $key => $gudang) :
            if($kd_gudang != '' || $kd_gudang != null):
                if(($kd_gudang==$gudang->KD_GUDANG)):
                    $default=" selected";
                else:
                    $default=($gudang->DEFAULTS == 1)?" selected":" ";
                endif;
            else:
                $default=($gudang->DEFAULTS == 1)?" selected":" ";
            endif;

        $html .= '<option value="'.$gudang->KD_GUDANG.'" '.$default.' >'.$gudang->NAMA_GUDANG.'</option>';

        endforeach;

        endif;

        $html .= '</select>';

        return $html;
    }


    public function get_penerimaan($scan = null)
    {
        $no_mesin = $this->input->get('no_mesin');
        $kd_dealer = $this->input->get('kd_dealer');
        $no = $this->input->get('no') + 1;
        $key = $this->input->get('no');
        $no_sjmasuk = $this->input->get('no_sjmasuk');

        $param = array(
            'kd_dealer' => $kd_dealer,
        );

        if($scan == 'true'){
            $param['custom'] = "(NO_RANGKA IN(SELECT SJM.NO_RANGKA FROM TRANS_TERIMASJMOTOR SJM WHERE SJM.NO_SJMASUK=NO_SJMASUK) OR STATUS_SJ = 0) AND (STATUS_SJ <= 0 OR STATUS_SJ IS NULL) AND NO_MESIN = '".$no_mesin."'" ;
        }
        else{
            $param['no_sjmasuk'] = $no_sjmasuk ;
            $param['custom'] = "((NO_RANGKA IN(SELECT SJM.NO_RANGKA FROM TRANS_TERIMASJMOTOR SJM WHERE SJM.NO_SJMASUK=NO_SJMASUK) AND STATUS_SJ <= 1) OR STATUS_SJ = 0 OR STATUS_SJ IS NULL )" ;
        }

        // $data['sj'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));
        $data['sj'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/penerimaan_unit", $param));

        $data['rm'] = "";

        if($data['sj']->message && (is_array($data['sj']->message) || is_object($data['sj']->message))):


            $tglsj = tglfromSql($data['sj']->message[0]->TGL_SJMASUK);

            
            $data['total_data'] = count($data['sj']->message);

            $data['tgl'] = $tglsj;
            // $data['tgl'] = LongTgl($tglsj);
            $no = 1;
            foreach ($data['sj']->message as $key => $sj) {
                $data['rm'] .= '<tr class="list-penerimaan">';

                $data['rm'] .= '<input type="hidden" id="id_reff_'.$key.'" class="form-control" value="'.$sj->ID_REFF.'">';
                $data['rm'] .= '<input type="hidden" id="jenis_penerimaan_'.$key.'" class="form-control" value="'.$sj->JENIS_PENERIMAAN.'">';

                if($scan == 'true'){
                $data['no_mesin'] = $sj->NO_MESIN;

                $data['rm'] .= '<input type="hidden" id="no_sjmasuk_'.$key.'" class="form-control" value="'.$sj->NO_SJMASUK.'">
                                <input type="hidden" id="expedisi_'.$key.'" class="form-control" value="'.$sj->EXPEDISI.'">
                                <input type="hidden" id="nopol_'.$key.'" class="form-control" value="'.$sj->NOPOL.'">';

                }

                else{

                $data['rm'] .= '<input type="hidden" id="no_terimasjm_'.$key.'" value="'.$sj->NO_TERIMASJM.'">
                                <input type="hidden" id="id_'.$key.'" value="'.$sj->ID.'">';
                }

                $data['rm'] .= '<td rowspan="2" class="text-center table-nowarp">'.$no.'</td>';

                $data['rm'] .= '<td class="text-center"><input class="stock_status '.$sj->NO_MESIN.' stock_status_'.$key.'" name="stock_status" value="1" type="checkbox" '.($sj->STATUS_SJ == 1?"checked disabled":"").'></td>';

                // select type status
                $data['rm'] .= '<td class="table-nowarp"><select name="tipe_stock" data-key="'.$key.'" id="tipe_stock_'.$key.'" class="form-control tipe_stock" required="true">';
                $data['rm'] .= '<option value="1" '.($sj->STOCK_STATUS == '1'?'selected':'').'>RFS</option>';
                $data['rm'] .= '<option value="0" '.($sj->STOCK_STATUS == '0'?'selected':'').'>NRFS</option>';
                $data['rm'] .= '</select></td>';

                // kteranagan NRFS

                // INPUT AWAL
                $data['rm'] .= '<td class="text-center table-nowarp"><input type="hidden" id="kd_item_'.$key.'" class="form-control" value="'.$sj->KD_ITEM.'">'.$sj->KD_ITEM.'</td>';
                $data['rm'] .= '<td class=" table-nowarp">'.$sj->NAMA_PASAR.'</td>';
                $data['rm'] .= '<td class=" table-nowarp"><input type="hidden" id="no_mesin_'.$key.'" class="form-control" value="'.$sj->NO_MESIN.'">'.$sj->NO_MESIN.'</td>';
                $data['rm'] .= '<td class=" table-nowarp"><input type="hidden" id="no_rangka_'.$key.'" class="form-control" value="'.$sj->NO_RANGKA.'">'.$sj->NO_RANGKA.'</td>';
                $data['rm'] .= '<td class="table-nowarp">'.$this->get_kudang($key, $sj->KD_DEALER, $sj->KD_GUDANG).'</td>';
                $data['rm'] .= '</tr>';

                $data['rm'] .= '<tr>';
                $data['rm'] .= '<td colspan="2" class="table-nowarp ket-stock"><input class="form-control type="text" id="keterangan_nrfs_'.$key.'" '.($sj->STOCK_STATUS == '0'?'':'disabled').' value="'.$sj->KETERANGAN_NRFS.'"></td>';
                $data['rm'] .= '<td colspan="5" class="table-nowarp">'.$this->cek_ksudata($sj, $key).'</td>';
                $data['rm'] .= '</tr>';


                $no++;
            }
        else:
            if($scan == null){
                $data['total_data'] =  0;
                $data['rm'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=7><b>Belum ada data / data tidak ditemukan</b></td></tr>";
            }

        endif;
        
        $this->output->set_output(json_encode($data));


    }

    public function cek_ksudata($sj, $key)
    {

        $data_ksu_in = explode(": ",$sj->KSU);
        $data_ksu_notin = explode(": ",$sj->KSU2);

        $ksu_in = '';
        $ksu_notin = '';


        foreach ($data_ksu_in as $ksu_row) {
            if($ksu_row!=null):
            $ksu_in .= '<input class="ksu_'.$key.'" name="ksu[]" value="'.trim($ksu_row).'" type="checkbox" checked disabled> '.$ksu_row.'&nbsp &nbsp';     
          endif; 
        }

        foreach ($data_ksu_notin as $ksu_row) {
            if($ksu_row!=' '):
            $ksu_notin .= '<input class="ksu ksu_'.$key.'" name="ksu[]" value="'.trim($ksu_row).'" type="checkbox"> '.$ksu_row.'&nbsp &nbsp';     
            endif;   
        }

        return $ksu_in.$ksu_notin;
    }

    public function cek_ksu($key, $no_mesin)
    {

        $params= array(
            'custom' =>"NO_MESIN = '".$no_mesin."'",
            'jointable' =>array(
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT")
            ),
            'field' => 'TRANS_TERIMASJMOTOR.*, MP.KD_ITEM,MP.NAMA_PASAR'
        );

        // $data['details']=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor", $params));
        $details=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor", $params));

        /*var_dump($details);
        exit();*/

        if($details->message > 0)
        {
            $data = "<input type='hidden' id='no_terimasjm_".$key."' value='".$details->message[0]->NO_TERIMASJM."'><input type='hidden' id='id_".$key."' value='".$details->message[0]->ID."'>".$this->get_ksu_in($key, $details->message[0]->KSU).$this->get_ksu_notin($key, $details->message[0]->KSU);
        }
        else
        {
            $data = "<input type='hidden' id='id_".$key."' value=''>".$this->get_ksu($key);
        }

        return $data;

        /*$data['detail'] = '';
        $no = 1;
        foreach ($details->message as $key => $sj) {
                

                $data['rm'] .= '<td>'.$this->get_ksu($key).'</td>';
*/
    }

    public function edit_penerimaan($id)
    {
        $this->auth->validate_authen('umsl/terimamotor');

        $param = array(
            'custom' => 'ID ='.$id
        );

        $data = array();
        $data['ksu_penerimaan'] = array();

        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor",$param));

        $ksu_data = '';
        if($data["list"]->message[0]->KSU != '')
        {

            $data['ksu_penerimaan'] = explode(", ",$data["list"]->message[0]->KSU);
            $ksu_data = str_replace(', ', "','", $data["list"]->message[0]->KSU);

        }

        $param_ksu = array(
            'custom' => "KD_KSU NOT IN ('".$ksu_data."')",
            'field' => "KD_KSU"
         );

        $data['ksu'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param_ksu));
        // $this->output->set_output(json_encode($data));


        $this->load->view('inventori/edit_penerimaan_motor', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));

    }

    public function update_penerimaan($id)
    {

        $param = array(
            'id' => $id,
            'no_terimasjm' => $this->input->post('no_terimasjm'),
            'no_sjmasuk' => $this->input->post('no_sjmasuk'),
            'kd_maindealer' => $this->input->post('kd_maindealer'),
            'kd_dealer' => $this->input->post('kd_dealer'),
            'kd_item' => $this->input->post('kd_item'),
            'no_rangka' => $this->input->post('no_rangka'),
            'no_mesin' => $this->input->post('no_mesin'),
            'jumlah' => 1,
            'ksu' => is_array($this->input->post('ksu'))?implode(', ', $this->input->post('ksu')):'',
            'expedisi' => $this->input->post('expedisi'),
            'nopol' => $this->input->post('nopol'),
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        /*print_r($hasil);
        exit;*/
        /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
        $hasil=$this->curl->simple_put(API_URL."/api/inventori/terimamotor",$param, array(CURLOPT_BUFFERSIZE => 10));

        $this->session->set_flashdata('tr-active', $id);

        $this->data_output($hasil, 'put');
        // $ksu = implode(', ', $this->input->post('ksu'));
        // var_dump($this->input->post());

    }

    public function delete_penerimaan($id)
    {
        $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );

        $data = array();

        $hasil=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor",array('custom' => "ID = ".$param['id'])));

        $data["list"]=json_decode($this->curl->simple_delete(API_URL."/api/inventori/terimamotor",$param));

        $this->data_output($data, 'delete', base_url('umsl/terimamotor'));

        /*if ($data) {
            if($hasil->message>0){
                $param = array(
                    'no_rangka' => $hasil->message[0]->NO_RANGKA,
                    'no_sjmasuk'=> $hasil->message[0]->NO_SJMASUK,
                    'status_sj'=> 0,
                    'lastmodified_by'=> $this->session->userdata('user_id')
                );

                $update_du=$this->curl->simple_put(API_URL."/api/inventori/sj_terimamotor",$param, array(CURLOPT_BUFFERSIZE => 10));
                # code...
            }
    
            $this->data_output($data, 'delete', base_url('umsl/terimamotor'));
        }*/

        
    }

    public function get_ksu($key)
    {

        $html = '';

        $param_ksu = array(
            // 'custom' => 'ROW_STATUS = 1'
        );

        $ksu = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param_ksu));
        // $sj->NO_RANGKA
        foreach ($ksu->message as $i=> $ksu_row) {
            // $html .= '<div class=" col-xs-3 text-center"><input class="ksu_'.$key.' col-xs-3" name="ksu['.$i.']" value="'.$ksu_row->KD_KSU.'" type="checkbox"> <span class="col-xs-5">'.$ksu_row->KD_KSU.'</span></div>';
            $html .= '<input class="ksu ksu_'.$key.'" name="ksu[]" value="'.$ksu_row->KD_KSU.'" type="checkbox"> '.$ksu_row->KD_KSU.'&nbsp &nbsp';
            // $html .= '<input class="ksu_'.$key.'" name="ksu['.$i.']" value="'.$ksu_row->KD_KSU.'" type="checkbox"> '.$ksu_row->KD_KSU.'</br>';
        }

        return $html;
        // $this->output->set_output(json_encode($html));


    }



    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo(){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => 'RM',
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => substr($this->input->post('tahun_docno'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => 'Penerimaan',
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
            $nopo = "RM" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";

            $param['urutan_docno'] = $nomor_po+1;
            $this->curl->simple_post(API_URL."/api/setup/setup_docno",$param, array(CURLOPT_BUFFERSIZE => 10));

        } else {
            $nomorpo = $nomor_po+1;
            $nopo = "RM" . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);


            $param['urutan_docno'] = $nomor_po;
            $this->curl->simple_put(API_URL."/api/setup/docno",$param, array(CURLOPT_BUFFERSIZE => 10));
        }
        //var_dump($nopo);exit();
        return $nopo;
    }

    /**
     * [hargamotor description]
     * @return [type] [description]
     */
    public function hargamotor() {
        $data = array();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];

        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page']/* ,
                  'jointable'  => array(
                  array("MASTER_P_TYPEMOTOR AS MP","MP.KD_ITEM=CONCAT(TRANS_SJMASUK.KD_TYPEMOTOR,'-',TRANS_SJMASUK.KD_WARNA)","LEFT")
                  ),
                  'field'      => 'TRANS_SJMASUK.*,MP.KD_ITEM,MP.NAMA_ITEM' */
        );
        //wilayah harga motor berdasarkan userlogin
        $par = array('kd_dealer' => $this->session->userdata("kd_dealer"));
        $propinsi = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $par), true);
        $data["propinsi"] = ($propinsi) ? (is_array($propinsi["message"])) ? $propinsi["message"][0]["KD_PROPINSI"] : 0 : 0;
        if ($this->input->get('kd_wilayah') != '0') {
            $param["kd_wilayah"] = $this->input->get('kd_wilayah');
        } else if ($this->input->get('kd_wilayah') == '0') {
            
        } else {
            if ($data["propinsi"] > 0) {
                $pars = array('kd_propinsi' => $data["propinsi"]);
                $wilayah = json_decode($this->curl->simple_get(API_URL . "/api/master/wilayahdealer", $pars), true);
                $area = ($wilayah) ? $wilayah["message"][0]["KD_WILAYAH"] : "";
                if ($area != "") {
                    $param["custom"] = " KD_WILAYAH ='" . $area . "'";
                }
            }
        }

        $data["wilayah"] = json_decode($this->curl->simple_get(API_URL . "/api/master/wilayahdealer"));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/hargamotor", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );


        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        $this->template->site('inventori/hargamotor', $data);
    }

    /**
     * [addhargamotor description]
     * @return [type] [description]
     */
    public function addhargamotor() {
        $this->auth->validate_authen("umsl/hargamotor");
        ini_set('max_execution_time',300);
        $data = array();$databaru=[];
        $param = array();$dataupdate=array();
        $param = array(
            'link' => 'list11'
        );
        // $data["listd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/hargamotor"));
        // $data["listmd"] = json_decode($this->curl->simple_get(API_URL . "/api/login/webservice/true", $param));
        $data["datamd"] = json_decode(json_decode(($this->curl->simple_get(API_URL . "/api/login/webservice", $param)), true), true);
        //var_dump($data['datamd']);exit();
        $datax=$data["datamd"];
        $totaldata=0;$n=0;
        if($datax){
            for($i=0;$i < count($datax);$i++){
                $params=array(
                    'kd_wilayah' => $datax[$i]["area"],
                    'kd_item'   => $datax[$i]["kditem"],
                    'custom'      => 'HARGA ='.$datax[$i]["harga"],
                );
                $params["custom"] .=" AND HARGA_OTR =".$datax[$i]["hrgotr"];
                $params["custom"] .=" AND BBN =".$datax[$i]["bbn"];
                $params["custom"] .=" AND HARGA_DEALER =".$datax[$i]["hrgdlr"];
                $params["custom"] .=" AND HARGA_DEALERD =".$datax[$i]["hrgdlrd"];
                $params["custom"] .=" AND PPH_RK =".$datax[$i]["pphdlrk"];
                $params["custom"] .=" AND PPH_RK2 =".$datax[$i]["pphdlrk2"];

                $databaru= json_decode($this->curl->simple_get(API_URL . "/api/inventori/hargamotor",$params), true);
                if($databaru){
                    //var_dump($databaru);
                    //print_r($params);
                    if((int)$databaru["totaldata"]==0){
                        $dataupdate[$totaldata]=$datax[$i];
                        $totaldata++;
                    }
                    
                }else{
                    $dataupdate[$totaldata]=$datax[$i];
                    $totaldata++;
                }
                if ($totaldata == 100){
                    break;
                }
            }
        }
        $data["listmd"]=$dataupdate;
        $this->load->view('inventori/add_hargamotor', $data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    /**
     * [updatehargamotor description]
     * @return [type] [description]
     */
    public function updatehargamotor() {
        set_time_limit(0);
        $hasil = "";
        $data = array();
        $pdata = json_decode($this->input->post("data"));
        $data = ("[" . substr($pdata, 0, strlen($pdata) - 0) . "]");
        $data = json_decode($data, true);
        $hasil = "";
        for ($i = 0; $i < count($data[0]); $i++) {
            $param = array(
                'kd_item' => $data[0][$i]["kditem"],
                'nama_item' => $data[0][$i]["nama"],
                'kd_wilayah' => $data[0][$i]["area"],
                'kd_category' => $data[0][$i]["kategori"],
                'harga' => $data[0][$i]["harga"],
                'harga_otr' => $data[0][$i]["hrgotr"],
                'harga_dealer' => $data[0][$i]["hrgdlr"],
                'harga_dealerd' => $data[0][$i]["hrgdlrd"],
                'bbn' => $data[0][$i]["bbn"],
                'pph_dlrk' => $data[0][$i]["pphdlrk"],
                'pph_dlrk2' => $data[0][$i]["pphdlrk2"],
                'biaya_adm' => $data[0][$i]["adm"],
                'biaya_lain' => (!$data[0][$i]["biayalain"]) ? 0 : $data[0][$i]["biayalain"],
                'aksesoris' => $data[0][$i]["aksesoris"],
                'tgl_update' => date("d/m/Y"),
                'created_by' => $this->session->userdata("user_id") . " | ws_list11"
            );

            $hasil = ($this->curl->simple_post(API_URL . "/api/inventori/hargamotor", $param, array(CURLOPT_BUFFERSIZE => 10)));
            $proses='post';
            if($hasil){
                $hasile=json_decode($hasil);
                if($hasile->recordexists==TRUE){
                    unset($param["created_by"]);
                    $param["lastmodified_by"] = $this->session->userdata("user_id") . " | ws_list11";
                    /*print_r($param);
                    exit();*/
                    $hasil = $this->curl->simple_put(API_URL . "/api/inventori/hargamotor", $param, array(CURLOPT_BUFFERSIZE => 10));
                    $proses='put';
                }
            }
        }
        //var_dump($param);
        $this->data_output($hasil, $proses);
    }

    /**
     * [suratjalan_typeahead description]
     * @return [type] [description]
     */
    public function suratjalan_typeahead() {
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'field'     =>"KD_DEALER,NO_SJMASUK",
            'groupby'   => TRUE
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan",$param));
        $data_message = "";
        if (is_array($data["list"]->message)) {

            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->NO_SJMASUK;
                /*$data_message[1][$key] = $message->NO_RANGKA;
                $data_message[2][$key] = $message->NO_MESIN;
                $data_message[3][$key] = $message->EXPEDISI;*/
            }


            $data_message[0] = ($data_message[0]);
        }
        $param=array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'field'     =>"KD_DEALER,NO_RANGKA,NO_MESIN",
            'groupby'   => TRUE
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan",$param));
        if (is_array($data["list"]->message)) {

            foreach ($data["list"]->message as $key => $message) {
                //$data_message[0][$key] = $message->NO_SJMASUK;
                $data_message[1][$key] = $message->NO_RANGKA;
                $data_message[2][$key] = $message->NO_MESIN;
                 /*$data_message[3][$key] = $message->EXPEDISI;*/
            }


            $data_message = array_merge($data_message[0], $data_message[1], $data_message[2]);
        }
        $result['keyword'] = $data_message;
        $this->output->set_output(json_encode($result));
    }

    /**
     * [cetak_suratjalan description]
     * @return [type] [description]
     */
    public function cetak_suratjalan() {

        $this->template->site('purchasing/cetak_suratjalan');
    }

    /**
     * [data_output description]
     * @param  [type] $this->template->site('purchasing/add_po',$data);$hasil [description]
     * @return [type]        [description]
     */
    function data_output($hasil = NULL, $method = '', $output_custom = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan",
                        'nodoc' => $output_custom,
                        'location' => base_url('umsl/listsj?n=' . $output_custom)
                    );
                    $this->session->unset_userdata("podetail");
                    //session_destroy();
                } else {
                    $result = $hasil;
                }
                $this->output->set_output(json_encode($result));
                break;
            case 'deleted':
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil hapus",
                        'nodoc' => $output_custom,
                        'location' => base_url('umsl/listsj')
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
                        'location' => $output_custom
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
                        'location' => $output_custom
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

            default:
                if ($hasil) {
                    $result = array(
                        'status' => true,
                        'message' => "Update berhasil",
                        'nodoc' => $output_custom,
                        'location' => base_url('umsl/listsj?n=' . $output_custom)
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

    public function monitoring_nrfs()
    {
        
        $param = array(
            'keyword' => $this->input->get('keyword'),
            // 'row_status' => 0, 
            // 'kd_dealer' => $this->input->get('kd_dealer'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            // 'jointable' => array(
            //     array("TRANS_POSP_DETAIL", "TRANS_POSP.NO_PO=TRANS_POSP_DETAIL.NO_PO", "LEFT")
            // ),
            'field' => "TRANS_POSP.*,(SELECT SUM(TRANS_POSP_DETAIL.JUMLAH) FROM TRANS_POSP_DETAIL WHERE TRANS_POSP_DETAIL.NO_PO = TRANS_POSP.NO_PO) AS QTY",
            'orderby' => "TGL_PO DESC"
            // 'field' => "TRANS_POSP.*,TRANS_POSP_DETAIL.JUMLAH",            
            // 'orderby' => "TRANS_PO2MD.TGL_PO DESC",//"TAHUN_KIRIM,BULAN_KIRIM DESC,KD_JENISPO,TRANS_PO2MD.ID desc" ,
                  /*'custom'    =>"MASTER_DEALER.NAMA_DEALER IS NOT NULL" */
        );
        if($this->input->get('kd_dealer')){
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }
        if($this->input->get('jenis_po')){
            if($this->input->get('jenis_po') != ''){
                $param["jenis_po"] = $this->input->get('jenis_po');
            }
        }        
        if($this->input->get('APPROVAL')){
            if($this->input->get('APPROVAL') == 'nol'){
                $param["APPROVAL"] = 'nol';
            } elseif ($this->input->get('APPROVAL') == '2') {
                $param["row_status"] = -1;
            } else {
                $param["APPROVAL"] = $this->input->get('APPROVAL');
            }
        }

        $param["bulan"]=($this->input->get("bln"));
        $param["tahun"]=($this->input->get("thn"));
        
        $param['jenis_po'] = "NRFS";

        // var_dump($param);exit;

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));

        // var_dump($data['list']);exit;

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $param=array(
            'field' =>"TAHUN",
            'groupby' =>TRUE,
            'orderby' =>"TAHUN"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('inventori/monitoring_order_nrfs', $data);

    }

}
