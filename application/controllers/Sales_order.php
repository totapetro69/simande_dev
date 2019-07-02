<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/controllers/cashier.php';

class Sales_order extends Cashier {
	 var $API="";
	public function __construct()
    {
            parent::__construct();
			//API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
			$this->load->library('form_validation');
        	$this->load->library('session');
        	$this->load->library('curl');
			$this->load->library('pagination');
            $this->load->library('dompdf_gen');
            $this->load->helper('form');
            $this->load->helper('url'); 
            $this->load->helper('zetro');
			if($this->session->userdata('kd_group') == null)
			{
				redirect("auth");
			}
        	/*print_r($this->session->userdata());
        	exit;*/

    }


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function sales_order($type = null)
	{

        $kd_dealer = $this->input->get('kd_dealer') ?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");
        $dtgl=($this->input->get("dtgl"))?$this->input->get("dtgl"):date("d/m/Y",strtotime("-5 Days"));
        $stgl=($this->input->get("stgl"))?$this->input->get("stgl"):date("d/m/Y");
        $delivery = $this->input->get("status_sj") == 'Belum'?"SJ.STATUS_SJ != 'aproved'":"SJ.STATUS_SJ = 'aproved'";

        $param = array(
            'keyword'   => $this->input->get('keyword'), 
            'custom'    => "TRANS_SPK.STATUS_SPK >= 2 AND convert(char,TRANS_SPK.TGL_SPK,112) BETWEEN '".tglToSql($dtgl)."' and '".tglToSql($stgl)."'",
            'kd_dealer' => $kd_dealer,
            'kd_customer' => $this->input->get('kd_customer'),
            'kd_sales'  => $this->input->get('kd_sales'),
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'jointable' => array(
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER","LEFT"),
                array("MASTER_SALESMAN AS MS","MS.KD_SALES=TRANS_SPK.KD_SALES AND MS.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJKELUAR AS SJ","SJ.NO_REFF=TRANS_SPK.NO_SO AND SJ.ROW_STATUS>=0","LEFT"),
                /*,
                array("TRANS_SPK_DETAILKENDARAAN AS SDK" , "SDK.SPK_ID=TRANS_SPK.ID", "LEFT", "SPK_ID"),
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=CONCAT(SDK.KD_TYPEMOTOR,'-',SDK.KD_WARNA)", "LEFT")*/
            ),
            'field' => 'MC.NAMA_CUSTOMER, MS.NAMA_SALES, TRANS_SPK.KD_SALES, TRANS_SPK.KD_CUSTOMER, TRANS_SPK.NO_SO, TRANS_SPK.ID, TRANS_SPK.NO_SPK,
                "CASE WHEN (SELECT COUNT(NO_MESIN) FROM TRANS_SPK_DETAILKENDARAAN AS TSTD WHERE TSTD.SPK_ID=TRANS_SPK.ID AND TSTD.NO_MESIN != \'\' AND TSTD.ROW_STATUS >= 0) >0 THEN 1 ELSE 0 END STATUS_MESIN"',
            // 'orderby' => 'TRANS_SPK.ID desc',
            'groupby' => TRUE,
            'orderby' => "TRANS_SPK.ID DESC"
        );


        if($this->input->get("status_sj")){
            $param['custom'] = "TRANS_SPK.STATUS_SPK >= 2 AND convert(char,TRANS_SPK.TGL_SPK,112) BETWEEN '".tglToSql($dtgl)."' and '".tglToSql($stgl)."' AND ".$delivery;
        }

        $data = array();

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));

        if($data['list'] && is_array($data['list']->message)){
            $queryIn = queryIndata($data['list']->message, 'ID');
        }
        else{
            $queryIn = "('')";
        }

        $params= array(
            'jointable' =>array(
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=CONCAT(TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR,'-',TRANS_SPK_DETAILKENDARAAN.KD_WARNA)", "LEFT"),
                array("TRANS_SPK AS SP","SP.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND SP.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJKELUAR_DETAIL AS SJD","SJD.NO_MESIN=TRANS_SPK_DETAILKENDARAAN.NO_MESIN AND SJD.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJKELUAR AS SJ","SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS>=0","LEFT"),
            ),
            'field' => "SP.NO_SPK,
                        SP.TGL_SPK,
                        SJ.STATUS_SJ,
                        TRANS_SPK_DETAILKENDARAAN.DISKON,
                        TRANS_SPK_DETAILKENDARAAN.SPK_ID,
                        TRANS_SPK_DETAILKENDARAAN.NO_MESIN,
                        TRANS_SPK_DETAILKENDARAAN.NO_RANGKA,
                        TRANS_SPK_DETAILKENDARAAN.ID,
                        TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR,
                        MP.KD_ITEM,
                        MP.NAMA_PASAR,MP.NAMA_ITEM,
                        CASE WHEN (SELECT COUNT(NO_MESIN) FROM TRANS_SJKELUAR_DETAIL AS TSD WHERE TSD.ROW_STATUS >= 0 AND TSD.NO_MESIN=TRANS_SPK_DETAILKENDARAAN.NO_MESIN AND TSD.NO_MESIN != '') >0 THEN 1 ELSE 0 END STATUS_SO,
                        (SELECT COUNT(SOP.KD_PROGRAM) FROM SETUP_SO_PROGRAMHADIAH_VIEW AS SOP WHERE (SOP.KD_DEALER = 'ALL' OR SOP.KD_DEALER = '".$kd_dealer."') AND SOP.KD_ITEM = TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND GETDATE() BETWEEN SOP.START_DATE AND END_PRINT AND SP.TGL_SPK BETWEEN SOP.START_DATE AND END_DATE AND SOP.ROW_STATUS >= 0) AS JUMLAH_PRINTHADIAH",
                        // (SELECT COUNT(SPK_DETAIL_ID) FROM TRANS_SPK_DETAIL_HADIAH_VIEW WHERE SPK_DETAIL_ID = TRANS_SPK_DETAILKENDARAAN.ID AND STATUS_PRINT = 1) AS JUMLAH_PRINTHADIAH
            'custom' => "TRANS_SPK_DETAILKENDARAAN.SPK_ID IN ".$queryIn,
            'groupby' => TRUE
        );

        if($this->input->get("status_sj")){
            $params['custom'] = "TRANS_SPK_DETAILKENDARAAN.SPK_ID IN ".$queryIn." AND ".$delivery;
        }

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_detailkendaraan", $params));

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));

        $data["datadealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer/true/true",array('kd_dealer' => $kd_dealer)));



        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        if($type == true){

            // $param['field'] = 'MS.NAMA_SALES, TRANS_SPK.KD_SAL/////ES';
            $type_data["sales"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));

            // $param['field'] = 'MC.NAMA_CUSTOMER, TRANS_SPK.KD_CUSTOMER';
            $type_data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));

            $this->output->set_output(json_encode($type_data));
            // $this->output->set_output(json_encode($data["list_group"]));
        }
        else{
            // $this->output->set_output(json_encode($data["datadealer"]));
            $this->template->site('sales/sales_order',$data);
        }

        
	}

    public function add_sales_order($data_only = null)
    {
        $data['total_data'] = 0;

        if($this->input->get("n")){
            $param = array(
                'no_so' => base64_decode(urldecode($this->input->get("n"))),
                'jointable' => array(
                    array("TRANS_SPK_DETAILCUSTOMER SDC" , "SDC.SPK_ID=TRANS_SPK.ID AND SDC.ROW_STATUS>=0", "LEFT"),
                    array("TRANS_GUESTBOOK GS" , "(GS.SPK_NO=TRANS_SPK.NO_SPK OR GS.GUEST_NO=TRANS_SPK.GUEST_NO) AND GS.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_CUSTOMER_VIEW AS MC","MC.KD_CUSTOMER=SDC.KD_CUSTOMER AND MC.ROW_STATUS >= 0","LEFT"),
                    array("TRANS_SPK_INDENT SPI" , "SPI.NO_SPK=TRANS_SPK.NO_SPK AND SPI.ROW_STATUS >= 0", "LEFT")
                ),
                'field' => 'TRANS_SPK.*, MC.KD_CUSTOMER, MC.NAMA_CUSTOMER,MC.ALAMAT_SURAT, MC.NO_HP, SPI.NO_TRANS AS NO_INDENT,
                            "CASE WHEN GS.STATUS = \'Deal Indent\' THEN 1 ELSE 0 END STATUS_INDENT"'
            );



            $data["soheader"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk", $param));

            // var_dump($data["soheader"]);exit;

            $params= array(
                'spk_id' =>$data["soheader"]->message[0]->ID,
                'jointable' =>array(
                    array("TRANS_SPK SP" , "SP.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND SP.ROW_STATUS>=0", "RIGHT"),
                    array("TRANS_GUESTBOOK GS" , "(GS.SPK_NO=SP.NO_SPK OR GS.GUEST_NO=SP.GUEST_NO) AND GS.ROW_STATUS>=0", "LEFT"),
                    array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=CONCAT(TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR,'-',TRANS_SPK_DETAILKENDARAAN.KD_WARNA)", "LEFT")
                ),
                'field' => 'TRANS_SPK_DETAILKENDARAAN.*,TRANS_SPK_DETAILKENDARAAN.ID as SDK_ID, MP.KD_ITEM,MP.NAMA_PASAR,
                        "CASE WHEN (SELECT COUNT(NO_MESIN) FROM TRANS_SJKELUAR_DETAIL AS TSD WHERE TSD.ROW_STATUS >= 0 AND TSD.NO_MESIN=TRANS_SPK_DETAILKENDARAAN.NO_MESIN AND TSD.NO_MESIN != \'\') >0 THEN 1 ELSE 0 END STATUS_SO",
                        "CASE WHEN GS.STATUS = \'Deal Indent\' THEN 1 ELSE 0 END STATUS_INDENT",
                        "CASE WHEN (SELECT COUNT(NO_TRANS) FROM TRANS_SPK_INDENT WHERE ROW_STATUS >= 0 AND NO_SPK=SP.NO_SPK AND STATUS_INDENT >= 2) > 0 THEN 1 ELSE 0 END CEK_INDENT"'
            );
            $details=json_decode($this->curl->simple_get(API_URL."/api/spk/spk_detailkendaraan", $params));
        // var_dump(($details));exit();

            $data['detail'] = '';

            if($details && is_array($details->message) || is_object($details->message)):
            if($details->message[0]->KD_ITEM != '' || count($details->message) > 1):
            $no = 1;

                foreach ($details->message as $key => $so) {

                    $status_so = ($so->STATUS_SO == 1?'disabled':'');
                    $adaHadiah = ($so->HADIAH == 1?'':'hidden');
                    $data['detail'] .= '<input type="hidden" id="sdk_id_'.$key.'" value="'.$so->SDK_ID.'">';
                    $data['detail'] .= '<input type="hidden" id="kd_item_'.$key.'" value="'.$so->KD_ITEM.'">';
                    $data['detail'] .= '<tr class="input-so">';
                    $data['detail'] .= '<td class="text-center table-nowarp">'.$no.'</td>';
                    $data['detail'] .= '<td class=" table-nowarp">'.$so->KD_ITEM.'</td>';
                    $data['detail'] .= '<td class=" table-nowarp">'.$so->NAMA_PASAR.'</td>';
                
                    $data['detail'] .= '<td class=" table-nowarp">'.$this->get_mesin($key, $so->KD_ITEM, $so->STATUS_INDENT, $so->CEK_INDENT,$so->NO_MESIN, $status_so).'<input type="hidden" id="no_mesin_old_'.$key.'" value="'.$so->NO_MESIN.'"></td>';
                    // $data['so'] .= '<td><input type="text" id="no_mesin_'.$key.'" class="form-control" value="'.$so->KD_ITEM.$key.'"></td>';
                    $data['detail'] .= '<td class=" table-nowarp"><input type="text" id="no_rangka_'.$key.'" class="form-control" value="'.$so->NO_RANGKA.'" readonly></td>';
                    // $data['detail'] .= '<td class=" table-nowarp"><input '.$status_so.' onclick="__checks(\''.$so->SDK_ID.'\',\''.$key.'\')" id="hadiah_'.$key.'" name="hadiah" type="checkbox" '.($so->HADIAH == 1 ? "checked" : "").'> Hadiah</td>';
                    $data['detail'] .= '</tr>';
                    /*$data['detail'] .= "<tr id='lst_hadiah' class='".$adaHadiah."'><td>&nbsp;</td><td colspan='2' class='table-nowarp'><span ".$status_so.">".$this->get_hadiah($so->KD_TYPEMOTOR,$key)."</span></td>";
                    $data['detail'] .= "<td colspan='2'><span id='n_hadiah'></span></td><td>&nbsp;</td></tr>";*/
                    $no++;
                }
            else:

                $data['detail'] .= "<tr><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=5><b>Belum ada data / data tidak ditemukan</b></td></tr>";
            endif;
            endif;

            $data['total_data'] =  count($details->message);
        }

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SPK.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SPK.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $param = array(
            'custom' => "TRANS_SPK.STATUS_SPK >= 1 AND (TRANS_SPK.TYPE_PENJUALAN = 'CASH' OR (TRANS_SPK.TYPE_PENJUALAN = 'CREDIT' AND SLS.HASIL = 'Approve')) AND (SDK.NO_MESIN = '' OR SDK.NO_MESIN IS NULL) AND ".$kd_dealer,
            'jointable' => array(
                array("TRANS_SPK_DETAILKENDARAAN SDK" , "SDK.SPK_ID=TRANS_SPK.ID AND SDK.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK_LEASING SLS" , "SLS.SPK_ID=TRANS_SPK.ID AND SLS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW MCV" , "MCV.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MCV.ROW_STATUS>=0", "LEFT"),
                array("TRANS_GUESTBOOK GS" , "(GS.SPK_NO=TRANS_SPK.NO_SPK OR GS.GUEST_NO=TRANS_SPK.GUEST_NO) AND GS.ROW_STATUS>=0", "LEFT"),
            ),
            'field' => 'TRANS_SPK.NO_SPK, TRANS_SPK.KD_DEALER, MCV.NAMA_CUSTOMER,"CASE WHEN GS.STATUS = \'Deal Indent\' THEN 1 ELSE 0 END STATUS_INDENT"',
            'groupby_text' => "TRANS_SPK.NO_SPK, TRANS_SPK.KD_DEALER, MCV.NAMA_CUSTOMER,GS.STATUS"
        );

        if($this->input->get("n")){
            $param['custom'] = "TRANS_SPK.NO_SO = '".base64_decode(urldecode($this->input->get("n")))."'";
        }

        $data["spk"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));

        if($data_only == 'true'){
            $this->output->set_output(json_encode($data["spk"]));
        }else{
            $this->template->site('sales/add_sales_order', $data);
        }
    }



    public function delete_so($id)
    {
       /* $param = array(
            'id' => $id,
            'lastmodified_by' => $this->session->userdata('user_id')
        );*/


        $param = array(
            'sdk_id'=> $id,
            'no_mesin'=> NULL,
            'no_rangka'=> NULL,
            'barang'=> NULL,
            'hadiah'=> NULL,
            'lok_pengiriman'=> NULL,
            'lastmodified_by'=> $this->session->userdata('user_id')
        );


        $data = array();

        $hasil=json_decode($this->curl->simple_get(API_URL."/api/spk/spk_detailkendaraan",array('custom' => "ID = ".$param['sdk_id'])));

        $data["list"]=json_decode($this->curl->simple_put(API_URL."/api/spk/so_kendaraan",$param, array(CURLOPT_BUFFERSIZE => 10)));

       /* if($data)
        {

            if($hasil->message>0){

                $param = array(
                    'no_mesin' => $hasil->message[0]->NO_MESIN,
                    'stock_status' => 0,
                    'lastmodified_by'=> $this->session->userdata('user_id')
                );

                $update_rm = $this->update_statusrm($param);

            }

        }
        */ 
        $this->data_output($data, 'delete', base_url('sales_order/sales_order'));



    }


    public function update_sokendaraan()
    {

       /* if($this->input->post("status_indent") == 1){
            $param_sms = array(
                'nohp'=> $this->input->post("no_hp"),
                'pesan'=> 'Salam satu hati, motor indent anda sudah tersedia dengan nomor mesin '.$this->input->post("no_mesin"),
                'created_by'=> $this->session->userdata('user_id')
            );

            $sms=json_decode($this->curl->simple_get(API_URL."/api/login/webservicesms",$param_sms, array(CURLOPT_BUFFERSIZE => 10)));
        }*/

            // var_dump($this->input->post());exit;


        if($this->input->post("no_so"))
        {
            $no_so = $this->input->post("no_so");
            $no_fak = $this->input->post("faktur_penjualan");
        }
        elseif ($this->session->userdata('no_so')) 
        {
            $no_so = $this->session->userdata('no_so');
            $no_fak = $this->session->userdata('no_fak');
        }
        else
        {
            $this->session->set_userdata('no_so', $this->getnopo('SO','Sales Order'));
            $no_so = $this->session->userdata('no_so');


            $this->session->set_userdata('no_fak', $this->getnopo('FAK','Sales Order'));
            $no_fak = $this->session->userdata('no_fak');
        }

        $param_so = array(
            'spk_id'=> $this->input->post("spk_id"),
            'no_so'=> $no_so,
            'faktur_penjualan'=> $no_fak,
            'status_spk'=> 2,
            'lok_penjualan'=> NULL,
            'lastmodified_by'=> $this->session->userdata('user_id')
        );
        
        $update_so=$this->curl->simple_put(API_URL."/api/spk/so",$param_so, array(CURLOPT_BUFFERSIZE => 10));


        if($update_so)
        {

            $param = array(
                'sdk_id'=> $this->input->post("sdk_id"),
                'no_mesin'=> $this->input->post("no_mesin"),
                'no_rangka'=> $this->input->post("no_rangka"),
                'barang'=> $this->input->post("aksesoris"),
                'hadiah'=> $this->input->post("hadiah"),
                'kd_paket'=> $this->input->post("program_hadiah"),
                'lastmodified_by'=> $this->session->userdata('user_id')
            );

            $hasil=json_decode($this->curl->simple_put(API_URL."/api/spk/so_kendaraan",$param, array(CURLOPT_BUFFERSIZE => 10)));

            if($hasil)
            {
                if($hasil->message>0){

                   /* if($this->input->post("no_mesin_old") != '')
                    {
                        $param = array(
                            'no_mesin' => $this->input->post("no_mesin_old"),
                            'stock_status' => 0,
                            'lastmodified_by'=> $this->session->userdata('user_id')
                        );

                        $update_rm = $this->update_statusrm($param);

                    }

                    $param = array(
                        'no_mesin' => $this->input->post("no_mesin"),
                        'stock_status' => 1,
                        'lastmodified_by'=> $this->session->userdata('user_id')
                    );

                    $update_rm = $this->update_statusrm($param);*/

                    if($this->input->post("status_indent") == 1 && $this->input->post("no_mesin") != ''){

                        $param = array(
                            'no_trans'=> $this->input->post("no_trans"),
                            'status_indent'=> 3,
                            'lastmodified_by'=> $this->session->userdata('user_id')
                        );

                        $close=json_decode($this->curl->simple_put(API_URL."/api/spk/spkindent/true",$param, array(CURLOPT_BUFFERSIZE => 10)));

                        $param_sms = array(
                            'nohp'=> $this->input->post("no_hp"),
                            'pesan'=> 'Salam satu hati, motor indent anda sudah tersedia dengan nomor mesin '.$this->input->post("no_mesin"),
                            'created_by'=> $this->session->userdata('user_id')
                        );

                        $sms=json_decode($this->curl->simple_get(API_URL."/api/login/webservicesms",$param_sms, array(CURLOPT_BUFFERSIZE => 10)));
                    }


                }

            } 
            
        }

        if($this->input->post('unset') == 'true')
        {
            $data = array(
                'location' => base_url().'sales_order/add_sales_order?n='.urlencode(base64_encode($no_so)), 
                'status_unset' => true
            );


       /* var_dump($data);
        exit;*/

            $this->output->set_output(json_encode($data));
        }
    }

    public function unset_notrans()
    {
        $this->session->unset_userdata('no_so');
        $this->session->unset_userdata('no_fak');

        $data = array(
            'status' => true,
            'message' => "Data SO berhasil diperbarui"
        );        

        $this->output->set_output(json_encode($data));
    }

    public function update_statusrm($param){

        /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert*/
        $hasil=$this->curl->simple_put(API_URL."/api/spk/status_rm",$param, array(CURLOPT_BUFFERSIZE => 10));
        /*var_dump($hasil);
        exit;*/


        // $this->data_output($hasil, 'put');

    }

    public function so_typeahead()
    {
        $param=array(
            'row_status'=>0,
            'custom'    => "NO_SO !='' AND NO_SO IS NOT NULL"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk",$param));


        $result['keyword'] = [];
        
        if($data['list']->totaldata > 0){
            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->NO_SO;
            }

            $result['keyword'] = ($data_message[0]);
        }

        $this->output->set_output(json_encode($result));

    }

    public function list_indent()
    {
        $data = array();
        $tgl= ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir"))?"convert(char,TRANS_SPK_INDENT.TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_SPK_INDENT.TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'" ;
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

        $param = array(
            'kd_dealer' => $kd_dealer,
            'custom' => $tgl,
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable'  => array(
                                array("TRANS_SPK_VIEW as SPK","SPK.NO_SPK=TRANS_SPK_INDENT.NO_SPK AND SPK.ROW_STATUS >= 0","LEFT"),            
                                array("MASTER_CUSTOMER_VIEW as MC","MC.KD_CUSTOMER=TRANS_SPK_INDENT.KD_CUSTOMER AND MC.ROW_STATUS>=0","LEFT"),
                                array("MASTER_P_TYPEMOTOR as PM","PM.KD_ITEM=TRANS_SPK_INDENT.KD_ITEM AND PM.ROW_STATUS>=0","LEFT"),
                            ),
            'field' => "TRANS_SPK_INDENT.*, SPK.TYPE_PENJUALAN, SPK.NAMA_SALES,MC.NAMA_CUSTOMER, MC.NO_KTP, MC.NO_HP, PM.NAMA_ITEM",
            'limit' => 15
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spkindent", $param));

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
        $this->template->site('sales/list_indent',$data);
        
    }

    public function indent_typeahead() {
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $param=array(
            'kd_dealer' => $kd_dealer,
            'row_status'=>0,
            'field' => 'KD_ITEM, NO_SPK, KD_CUSTOMER',
            'groupby' => true
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spkindent", $param));
        $data_message="";
        if(is_array($data["list"]->message)){
            foreach ($data["list"]->message as $key => $message) {
               $data_message[0][$key] = $message->NO_SPK;
               $data_message[1][$key] = $message->KD_ITEM;
               $data_message[2][$key] = $message->KD_CUSTOMER;
            }
            $data_message = array_merge($data_message[0], $data_message[1], $data_message[2]);
        }
        $result['keyword']=$data_message;
        $this->output->set_output(json_encode($result));
    }

    public function add_indent()
    {

        $this->auth->validate_authen('sales_order/add_sales_order');


        $param=array(
            'no_spk' =>$this->input->get('no_spk'),
            'jointable'  => array(
                                array("TRANS_SPK_VIEW as SPK","SPK.NO_SPK=TRANS_SPK.NO_SPK AND SPK.ROW_STATUS >= 0","LEFT"),            
                                array("TRANS_SPK_INDENT as I","I.NO_SPK=TRANS_SPK.NO_SPK AND I.ROW_STATUS >= 0","LEFT"),            
                                array("TRANS_SPK_DETAILKENDARAAN as SDK","SDK.SPK_ID=TRANS_SPK.ID AND SDK.ROW_STATUS >= 0","LEFT"),            
                                array("MASTER_CUSTOMER_VIEW as MC","MC.KD_CUSTOMER=TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS>=0","LEFT"),
                                array("MASTER_P_TYPEMOTOR as PM","PM.KD_TYPEMOTOR=SDK.KD_TYPEMOTOR AND PM.KD_WARNA=SDK.KD_WARNA AND PM.ROW_STATUS>=0","LEFT"),
                            ),
            'field' => "I.*, SPK.NO_SPK, SPK.TYPE_PENJUALAN, SPK.NAMA_SALES,MC.NAMA_CUSTOMER, MC.NO_KTP, MC.NO_HP, PM.KD_ITEM, PM.NAMA_ITEM",
            // 'field' => "KETERANGAN,ETA_INDENT"
        );
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/spk/spk",$param));
        // $this->output->set_output(json_encode($data));


        $this->load->view('sales/add_indent', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }


    public function create_indentlist()
    {
        $no_trans = $this->input->post("no_trans")? $this->input->post("no_trans") : $this->getnopo('IDN','Indent Fulfillment');

        $param = array(
            'kd_dealer'=> $this->input->post("kd_dealer"),
            'no_trans'=> $no_trans,
            'no_spk'=> $this->input->post("no_spk"),
            'tgl_trans'=> $this->input->post("tgl_trans"),
            'kd_item'=> $this->input->post("kd_item"),
            'jumlah_unit'=> $this->input->post("jumlah_unit"),
            'kd_customer'=> $this->input->post("kd_customer"),
            'keterangan'=> $this->input->post("keterangan"),
            'eta_indent'=> $this->input->post("eta_indent"),
            'created_by'=> $this->session->userdata('user_id'),
            'lastmodified_by'=> $this->session->userdata('user_id')
        );
        
        $hasil=$this->curl->simple_post(API_URL."/api/spk/spkindent",$param, array(CURLOPT_BUFFERSIZE => 10));
        // var_dump($hasil);exit;
        $method = "post";

        if(json_decode($hasil)->recordexists==TRUE){
            $hasil=$this->curl->simple_put(API_URL."/api/spk/spkindent",$param, array(CURLOPT_BUFFERSIZE => 10));  
            $method = "put";
        }

        if($hasil){

            $param = array(
                'spk_no'=> $this->input->post("no_spk"),
                'status'=> 'Deal Indent',
                'lastmodified_by'=> $this->session->userdata('user_id')
            );

            $hasil=$this->curl->simple_put(API_URL."/api/sales/gb_status",$param, array(CURLOPT_BUFFERSIZE => 10)); 
        }


        $this->data_output($hasil, $method, $no_trans);
    }

    /**
     * [get_spk description]
     * @return [type] [description]
     */
    public function get_spk(){
        // var_dump($this->input->get()); exit;

        $no_spk = $this->input->get('no_spk');
        $kd_dealer = $this->input->get('kd_dealer');

        $param = array(
            // 'no_spk' => $no_spk,
            'custom' => "TRANS_SPK.NO_SPK = '".$no_spk."' AND TRANS_SPK.KD_DEALER = '".$kd_dealer."' AND (SDK.NO_MESIN = '' OR SDK.NO_MESIN IS NULL)",
            'jointable' => array(
                array("TRANS_SPK_DETAILCUSTOMER SDC" , "SDC.SPK_ID=TRANS_SPK.ID AND SDC.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER_VIEW as MC","MC.KD_CUSTOMER=SDC.KD_CUSTOMER AND MC.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN SDK" , "SDK.SPK_ID=TRANS_SPK.ID AND SDK.ROW_STATUS>=0", "RIGHT"),
                array("TRANS_GUESTBOOK GS" , "(GS.SPK_NO=TRANS_SPK.NO_SPK OR GS.GUEST_NO=TRANS_SPK.GUEST_NO) AND GS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_P_TYPEMOTOR MP" , "MP.KD_ITEM=CONCAT(SDK.KD_TYPEMOTOR,'-',SDK.KD_WARNA)", "LEFT"),
                array("TRANS_SPK_INDENT SPI" , "SPI.NO_SPK=TRANS_SPK.NO_SPK AND SPI.ROW_STATUS >= 0", "LEFT")
            ),
            'field' => 'TRANS_SPK.*, MC.KD_CUSTOMER, MC.NAMA_CUSTOMER,MC.ALAMAT_SURAT, MC.NO_HP,MP.KD_ITEM,MP.NAMA_PASAR,SDK.ID as SDK_ID, SDK.NO_MESIN, SDK.NO_RANGKA, SDK.HADIAH, SDK.KD_PAKET, SDK.KD_TYPEMOTOR, SPI.NO_TRANS AS NO_INDENT,
                "CASE WHEN (SELECT COUNT(NO_MESIN) FROM TRANS_SJKELUAR_DETAIL AS TSD WHERE TSD.ROW_STATUS >= 0 AND TSD.NO_MESIN=SDK.NO_MESIN AND TSD.NO_MESIN != \'\') >0 THEN 1 ELSE 0 END STATUS_SO",
                "CASE WHEN GS.STATUS = \'Deal Indent\' THEN 1 ELSE 0 END STATUS_INDENT",
                "CASE WHEN (SELECT COUNT(NO_TRANS) FROM TRANS_SPK_INDENT WHERE ROW_STATUS >= 0 AND NO_SPK=TRANS_SPK.NO_SPK AND STATUS_INDENT >= 2) > 0 THEN 1 ELSE 0 END CEK_INDENT"',
            'orderby' => 'TRANS_SPK.ID desc'
        );

        $data['spk'] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk", $param));

        /*var_dump($data);
        exit;*/
        $data['so'] = "";

        if($data['spk']->message && (is_array($data['spk']->message) || is_object($data['spk']->message))):

            $kd_dealer = $data['spk']->message[0]->KD_DEALER;

            $tglso = tglfromSql(getNextDays($data['spk']->message[0]->TGL_SPK, 30));

            
            $data['tgl'] = $tglso;
            $data['tgl_spk'] = tglfromSql($data['spk']->message[0]->TGL_SPK);
            $data['no_so'] = $data['spk']->message[0]->NO_SO;

            $data['total_data'] =  count($data['spk']->message);

            if($data['spk'] && is_array($data['spk']->message) || is_object($data['spk']->message)):
            if($data['spk']->message[0]->KD_ITEM != '' || count($data['spk']->message) > 1):
            $no = 1;


                foreach ($data['spk']->message as $key => $so) {
                    $status_so = ($so->STATUS_SO == 1?'disabled':'');
                    $adaHadiah = ($so->HADIAH == 1?'':'hidden');

                    $data['so'] .= '<input type="hidden" id="sdk_id_'.$key.'" value="'.$so->SDK_ID.'">';
                    $data['so'] .= '<input type="hidden" id="kd_item_'.$key.'" value="'.$so->KD_ITEM.'">';
                    $data['so'] .= '<tr class="input-so">';
                    $data['so'] .= '<td>'.$no.'</td>';
                    $data['so'] .= '<td>'.$so->KD_ITEM.'</td>';
                    $data['so'] .= '<td>'.$so->NAMA_PASAR.'</td>';
                
                    $data['so'] .= '<td>'.$this->get_mesin($key, $so->KD_ITEM, $so->STATUS_INDENT, $so->CEK_INDENT).'</td>';
                    $data['so'] .= '<td><input type="text" id="no_rangka_'.$key.'" class="form-control" value="" readonly></td>';
                    // $data['so'] .= '<td class=" table-nowarp"><input onclick="__checks(\''.$so->SDK_ID.'\',\''.$key.'\')" id="hadiah_'.$key.'" name="hadiah" type="checkbox"> Hadiah</td>';
                    // $data['so'] .= '<td class=" table-nowarp"><input '.$status_so.' id="aksesoris_'.$key.'" name="aksesoris" type="checkbox"> Aksesoris <input onclick="__checks(\''.$so->SDK_ID.'\',\''.$key.'\')" id="hadiah_'.$key.'" name="hadiah" type="checkbox"> Hadiah</td>';
                    $data['so'] .= '</tr>';


                    // $data['so'] .= "<tr id='lst_hadiah' class='".$adaHadiah."'><td>&nbsp;</td><td colspan='2' class='table-nowarp'><span ".$status_so.">".$this->get_hadiah($so->KD_TYPEMOTOR,$key)."</span></td>";
                    // $data['so'] .= "<td colspan='2'><span id='n_hadiah'></span></td><td>&nbsp;</td></tr>";
                    $no++;
                }
            
            else:

                $data['so'] .= "<tr><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=5><b>Belum ada data / data tidak ditemukan</b></td></tr>";
            endif;
            endif;

        else:
            $data['total_data'] =  0;
            $data['tgl'] = '';
            $data['so'] .= "<tr><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=5><b>Belum ada data / data tidak ditemukan</b></td></tr>";
        endif;
        /**/
        $this->output->set_output(json_encode($data));
    }
    function get_hadiah_($kd_hadiah=null,$no_urut=null){
        $data=array();
        $kd_dealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
        $param=array(
            'custom' => "KD_DEALER IN('ALL','".$kd_dealer."') AND START_DATE <=GETDATE() AND END_DATE >= GETDATE()"
        );
        $data =json_decode($this->curl->simple_get(API_URL."/api/setup/so_hadiah", $param));
        $html="<select class='form-control' id='kd_hadiah_".$no_urut."' name='kd_hadiah'>";
        $html .="<option value=''>--Pilih Program Hadiah--</option>";
        if($data){
            if($data->totaldata >0){
                foreach ($data->message as $key => $value) {
                    $pilih=($kd_hadiah==$value->KD_PROGRAM)?'selected':'';
                    $html .= "<option value='".$value->KD_PROGRAM."' ".$pilih.">".$value->NAMA_PROGRAM."</option>";
                }
            }
        }
        return $html;
    }

    public function get_hadiah($kd_item=null,$no_urut=null){

        $data=array();
        $kd_dealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
        $param=array(
            'kd_item' => $kd_item,
            'custom' => "KD_DEALER IN('ALL','".$kd_dealer."') AND START_DATE <=GETDATE() AND END_DATE >= GETDATE()"
        );
        $data =json_decode($this->curl->simple_get(API_URL."/api/sales/so_programhadiah/true", $param));

        $html = '';

        if($data){
            if($data->totaldata >0){
                foreach ($data->message as $key => $value) {
                    $html .= '<input type="hidden" class="kd_prgram_'.$no_urut.'" value="'.$value->KD_PROGRAM.'">';
                    $html .= '<span class="label bg-primary" style="margin-right:5px;">'.$value->NAMA_HADIAH.'</span>';
                }
            }
        }
        return $html;


        // return $data;

    }
    /**
     * [get_mesin description]
     * @param  [type] $key       [description]
     * @param  [type] $kd_item   [description]
     * @param  [type] $no_mesin  [description]
     * @param  string $status_so [description]
     * @return [type]            [description]
     */
    public function get_mesin($key, $kd_item,$status_indent, $cek_indent, $no_mesin = NULL, $status_so='')
    {

        $html = '';
        $selected = '';

        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");

       /* $params= array(
            'custom' => "((STOCK_AKHIR > 0 AND NO_MESIN NOT IN (SELECT NO_MESIN FROM TRANS_SPK_DETAILKENDARAAN WHERE ROW_STATUS >= 0)) OR NO_MESIN = '".$no_mesin."') AND KD_ITEM = '".$kd_item."' AND ".$kd_dealer,
            'field' => 'NO_RANGKA, NO_MESIN, TGL_TERIMA',
            'orderby' => 'TGL_TERIMA DESC',
            'groupby' => true,
        );*/

        $params = array(
            'kd_item' => $kd_item, 
            'kd_dealer' => $kd_dealer, 
            'no_mesin' => $no_mesin, 
            'status_indent' => $status_indent, 
        );

        // $data['details']=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor", $params));
        // $details=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor", $params));
        $details=json_decode($this->curl->simple_get(API_URL."/api/laporan/stock_fulfillment", $params));
        // var_dump($details);exit;
        // $this->output->set_output(json_encode($details));


        $html .= '<select id="no_mesin_'.$key.'"  class="val-diff form-control" '.$status_so.'>';
        $html .= '<option value="">- Pilih No Mesin -</option>';

        if($details && (is_array($details->message) || is_object($details->message))):
        foreach ($details->message as $key => $detail) {
            if($no_mesin != ''):
                $selected=($no_mesin==$detail->NO_MESIN)?" selected":" ";
            endif;

            if($status_indent == 0 && $detail->STOCK_NOTINDENT <= 0){
                $disabled = 'disabled';
            }
            elseif($status_indent == 1 && $cek_indent == 0){
                $disabled = 'disabled';
            }
            else{
                $disabled = '';
            }

            $html .= '<option value="'.$detail->NO_MESIN.'" '.$selected.' '.$disabled.'>'.$detail->NO_MESIN.'</option>';
        }
        endif;

        $html .= '</select>';

        return $html;

    }

    public function get_rangka($no_mesin = null)
    {
        $params= array(
            // 'custom' =>"KD_ITEM = 'HBY-RA'",
            'custom' =>"NO_MESIN = '".$no_mesin."'",
            'field' => 'NO_RANGKA, NO_MESIN',
            'groupby' => true
        );

        // $data['details']=json_decode($this->curl->simple_get(API_URL."/api/inventori/terimamotor", $params));
        $details=json_decode($this->curl->simple_get(API_URL."/api/laporan/stockmotor", $params));

        if($details && (is_array($details->message) || is_object($details->message)))
        {
            $data=$details;

        }
        else{
            $data = array(
                'status' => false
            );

        }


        $this->output->set_output(json_encode($data));


    }


    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */

    function getnopo($kd_docno, $nama_docno){
        $nopo="";$nomorpo=0;
        $param=array(
            'kd_docno'  => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'),// substr($this->input->post('tahun_docno'), 6, 4),
            // 'bulan_docno' => (int)substr($this->input->post('tahun_docno'), 3, 2),
            'nama_docno' => $nama_docno,
            'reset_docno' => 1,
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

    public function faktur_stnk($spk_id)
    {
        $this->load->library('dompdf_gen');

        $data['motors']=array(
            array('no_stnk'=>'1234567','no_bpkb'=>'132421','no_mesin'=>'1232431'),
            array('no_stnk'=>'1234567','no_bpkb'=>'132421','no_mesin'=>'1232431'),
            array('no_stnk'=>'1234567','no_bpkb'=>'132421','no_mesin'=>'1232431'),
            array('no_stnk'=>'1234567','no_bpkb'=>'132421','no_mesin'=>'1232431')
        );

        $html = $this->load->view('pdf/faktur_stnk', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');
    }

    public function faktur_penjualan($id=null)
    {
        $this->load->library('dompdf_gen');

        //total_stnk dari BBN di deteil spk kendaraan by : pak iswan 08-05-2018

        $param = array(
            'custom' => "TRANS_SPK_DETAILKENDARAAN.ID = ".$id,
            'jointable' => array(
                array("TRANS_SPK PK" , "PK.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND PK.ROW_STATUS>=0", "LEFT"),
                array("MASTER_DEALER MD" , "MD.KD_DEALER=PK.KD_DEALER", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=CONCAT(TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR,'-',TRANS_SPK_DETAILKENDARAAN.KD_WARNA)", "LEFT"),
                array("TRANS_SPK_SALESPROGRAM SS" , "SS.NO_SPK=PK.NO_SPK AND SS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_STNK_BPKB as MSB","MSB.KD_TIPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND MSB.KD_DEALER=PK.KD_DEALER AND MSB.KD_PROPINSI=MD.KD_PROPINSI AND MSB.KD_KABUPATEN=MD.KD_KABUPATEN AND MSB.TAHUN = YEAR(GETDATE()) AND MSB.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILALAMAT as SDA","SDA.SPK_ID=PK.ID AND SDA.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILCUSTOMER as SDC","SDC.SPK_ID=PK.ID AND SDC.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SM","SM.NO_RANGKA=TRANS_SPK_DETAILKENDARAAN.NO_RANGKA AND SM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=TRANS_SPK_DETAILKENDARAAN.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => ' 
                MP.NAMA_PASAR,MP.NAMA_ITEM,MP.KD_ITEM,
                TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR, 
                TRANS_SPK_DETAILKENDARAAN.KD_WARNA, 
                TRANS_SPK_DETAILKENDARAAN.HARGA, 
                TRANS_SPK_DETAILKENDARAAN.HARGA_OTR, 
                TRANS_SPK_DETAILKENDARAAN.BBN, 
                TRANS_SPK_DETAILKENDARAAN.DISKON, 
                TRANS_SPK_DETAILKENDARAAN.NO_RANGKA, 
                TRANS_SPK_DETAILKENDARAAN.NO_MESIN, 
                ISNULL(SM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1,
                "CASE WHEN LEN(ISNULL(SDA.NAMA_PENERIMA,\'\'))=0 THEN SDC.NAMA_BPKB ELSE SDA.NAMA_PENERIMA END NAMA_PENERIMA", 
                "CASE WHEN LEN(ISNULL(SDA.ALAMAT_KIRIM,\'\'))=0 THEN SDC.ALAMAT_BPKB ELSE SDA.ALAMAT_KIRIM END ALAMAT_KIRIM", 
                SDA.NO_HP, 
                PK.FAKTUR_PENJUALAN, 
                PK.TYPE_PENJUALAN,
                PK.TGL_SPK,
                PK.TGL_SO,
                MSB.TOTAL_STNK,
                (SELECT SUM(S.JUMLAH) FROM TRANS_SPK_DETAILKENDARAAN S WHERE S.SPK_ID=PK.ID AND S.ROW_STATUS>=0) AS TOT_QTY,
                "CASE WHEN (PK.TYPE_PENJUALAN=\'CREDIT\' OR PK.TYPE_PENJUALAN=\'KREDIT\') THEN SS.SK_AHM ELSE SS.SC_AHM END PotAHM",
                "CASE WHEN (PK.TYPE_PENJUALAN=\'CREDIT\' OR PK.TYPE_PENJUALAN=\'KREDIT\') THEN SS.SK_MD ELSE SS.SC_MD END PotMD",
                "CASE WHEN ISNULL(TRANS_SPK_DETAILKENDARAAN.DISKON, 0) > 0 THEN ISNULL(TRANS_SPK_DETAILKENDARAAN.DISKON, 0) ELSE CASE WHEN (PK.TYPE_PENJUALAN=\'CREDIT\' OR PK.TYPE_PENJUALAN=\'KREDIT\') THEN SS.MIN_SK_SD ELSE SS.MIN_SC_SD END END PotDLR",
                "CASE WHEN (PK.TYPE_PENJUALAN=\'CREDIT\' OR PK.TYPE_PENJUALAN=\'KREDIT\') THEN SS.MIN_SK_SD ELSE SS.MIN_SC_SD END PotMinDLR",
                "CASE WHEN (PK.TYPE_PENJUALAN=\'CREDIT\' OR PK.TYPE_PENJUALAN=\'KREDIT\') THEN SS.SK_FINANCE ELSE 0 END PotLS"
                '
        );

        $data['motors'] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_detailkendaraan", $param));

        $data['total_bayar']=0;
        $data['pot_ahm']=0;
        $data['pot_md']=0;
        $data['pot_d']=0;
        $data['biaya_stnk']=0;
        $data['dpp']=0;

        if($data['motors'] && (is_array($data['motors']->message) || is_object($data['motors']->message))):
        foreach($data['motors']->message as $key =>  $motor):
            $PotMinDLR = $motor->DISKON > 0?$motor->DISKON:$motor->PotMinDLR;
            $harga_otr = $motor->HARGA_OTR + $motor->DISKON;

            $data['total_bayar']=$data['total_bayar']+$harga_otr;
            $data['pot_ahm']=$data['pot_ahm'] + $motor->PotAHM;
            $data['pot_md']=$data['pot_md'] + $motor->PotMD;
            $data['pot_d']=$data['pot_d'] + $motor->PotDLR;

            $data['biaya_stnk']=$data['biaya_stnk']+$motor->BBN;
        endforeach; 
        endif;

        $data['total']=$data['total_bayar']-$data['pot_ahm']-$data['pot_md']-$data['pot_d'];
        $data['dpp'] = ($data['total'] - $data['biaya_stnk']) * (100/110);
        $data['ppn']=$data['dpp']*0.1;
        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer/true",listDealer()));

        // $this->output->set_output(json_encode($data['dealer']));


        $html = $this->load->view('pdf/faktur_penjualan', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');
    }

    public function terima_foucher($id)
    {
        $this->auth->validate_authen('sales_order/sales_order');

        $param = array(
            'custom' => "TRANS_SPK_DETAILKENDARAAN.ID = ".$id,
            'jointable' => array(
                array("TRANS_SPK PK" , "PK.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND PK.ROW_STATUS>=0", "LEFT"),
                array("MASTER_DEALER MD" , "MD.KD_DEALER=PK.KD_DEALER", "LEFT"),
                array("MASTER_KABUPATEN MK" , "MK.KD_KABUPATEN=MD.KD_KABUPATEN", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=CONCAT(TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR,'-',TRANS_SPK_DETAILKENDARAAN.KD_WARNA)", "LEFT"),
                array("TRANS_SPK_SALESPROGRAM SS" , "SS.NO_SPK=PK.NO_SPK AND SS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_STNK_BPKB as MSB","MSB.KD_TIPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND MSB.KD_DEALER=PK.KD_DEALER AND MSB.KD_PROPINSI=MD.KD_PROPINSI AND MSB.KD_KABUPATEN=MD.KD_KABUPATEN AND MSB.TAHUN = YEAR(GETDATE()) AND MSB.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILALAMAT as SDA","SDA.SPK_ID=PK.ID AND SDA.ROW_STATUS>=0","LEFT"),
                array("TRANS_SJMASUK SM","SM.NO_RANGKA=TRANS_SPK_DETAILKENDARAAN.NO_RANGKA AND SM.ROW_STATUS>=0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=TRANS_SPK_DETAILKENDARAAN.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => ' SS.NAMA_SALESPROGRAM,
                MD.NAMA_DEALER,
                MD.ALAMAT,
                MK.NAMA_KABUPATEN,
                MP.NAMA_PASAR,
                TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR, 
                TRANS_SPK_DETAILKENDARAAN.KD_WARNA, 
                TRANS_SPK_DETAILKENDARAAN.HARGA, 
                TRANS_SPK_DETAILKENDARAAN.HARGA_OTR, 
                TRANS_SPK_DETAILKENDARAAN.BBN, 
                TRANS_SPK_DETAILKENDARAAN.DISKON, 
                TRANS_SPK_DETAILKENDARAAN.NO_RANGKA, 
                TRANS_SPK_DETAILKENDARAAN.NO_MESIN, 
                SDA.NAMA_PENERIMA, 
                SDA.ALAMAT_KIRIM, 
                SDA.NO_HP, 
                PK.NO_SPK,
                PK.FAKTUR_PENJUALAN, 
                PK.TYPE_PENJUALAN,
                PK.TGL_SPK,
                PK.TGL_SO,
                MSB.TOTAL_STNK,
                ISNULL(SM.VNORANGKA1, SAM.VNORANGKA1) as VNORANGKA1'
        );

        $data['motors'] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_detailkendaraan", $param));

        if($data['motors'] && (is_array($data['motors']->message) || is_object($data['motors']->message))):
        foreach($data['motors']->message as $key =>  $motor):
            $data['subsidi'] = $this->getSubsidi($motor->NO_SPK);
            $data['subsidi_terbilang'] = terbilang($this->getSubsidi($motor->NO_SPK));
           
        endforeach; 
        endif;

        // $this->output->set_output(json_encode($data));

        $this->load->view('sales/terima_foucher', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));
    }

    public function terima_hadiah($id)
    {
        $this->auth->validate_authen('sales_order/sales_order');
        // $this->load->library('dompdf_gen');

                        // (SELECT COUNT(SOP.KD_PROGRAM) FROM SETUP_SO_PROGRAMHADIAH_VIEW AS SOP WHERE (SOP.KD_DEALER = 'ALL' OR SOP.KD_DEALER = '".$kd_dealer."') AND SOP.KD_ITEM = TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND SP.TGL_SPK BETWEEN SOP.START_DATE AND END_PRINT AND SOP.ROW_STATUS >= 0) AS JUMLAH_PRINTHADIAH",
        $kd_dealer = $this->input->get('kd_dealer') ?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");


        $param = array(
            'custom' => "(PK.KD_DEALER = 'ALL' OR PK.KD_DEALER = '".$kd_dealer."') AND PK.TGL_SPK BETWEEN HD.START_DATE AND HD.END_DATE AND TRANS_SPK_DETAILKENDARAAN.ID = ".$id,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 1,
            'jointable' => array(
                array("SETUP_SO_PROGRAMHADIAH_VIEW HD" , "HD.KD_ITEM=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND HD.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK PK" , "PK.ID=TRANS_SPK_DETAILKENDARAAN.SPK_ID AND PK.ROW_STATUS>=0", "LEFT"),
                array("MASTER_DEALER MD" , "MD.KD_DEALER=PK.KD_DEALER", "LEFT"),
                array("MASTER_KABUPATEN MK" , "MK.KD_KABUPATEN=MD.KD_KABUPATEN", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MP" , "MP.KD_ITEM=CONCAT(TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR,'-',TRANS_SPK_DETAILKENDARAAN.KD_WARNA)", "LEFT"),
                array("TRANS_SPK_SALESPROGRAM SS" , "SS.NO_SPK=PK.NO_SPK AND SS.ROW_STATUS>=0", "LEFT"),
                array("MASTER_STNK_BPKB as MSB","MSB.KD_TIPEMOTOR=TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR AND MSB.KD_DEALER=PK.KD_DEALER AND MSB.KD_PROPINSI=MD.KD_PROPINSI AND MSB.KD_KABUPATEN=MD.KD_KABUPATEN AND MSB.TAHUN = YEAR(GETDATE()) AND MSB.ROW_STATUS>=0","LEFT"),
                array("TRANS_SPK_DETAILCUSTOMER as SDA","SDA.SPK_ID=PK.ID AND SDA.ROW_STATUS>=0","LEFT"),
                array("MASTER_CUSTOMER_VIEW as MC","MC.KD_CUSTOMER=SDA.KD_CUSTOMER AND MC.ROW_STATUS>=0","LEFT")
            ),
            'field' => ' SS.NAMA_SALESPROGRAM,
                MD.NAMA_DEALER,
                MD.ALAMAT,
                MK.NAMA_KABUPATEN,
                MP.NAMA_PASAR,
                TRANS_SPK_DETAILKENDARAAN.KD_TYPEMOTOR, 
                TRANS_SPK_DETAILKENDARAAN.KD_WARNA, 
                TRANS_SPK_DETAILKENDARAAN.HARGA, 
                TRANS_SPK_DETAILKENDARAAN.HARGA_OTR, 
                TRANS_SPK_DETAILKENDARAAN.BBN, 
                TRANS_SPK_DETAILKENDARAAN.DISKON, 
                TRANS_SPK_DETAILKENDARAAN.NO_RANGKA, 
                TRANS_SPK_DETAILKENDARAAN.NO_MESIN, 
                SDA.NAMA_BPKB AS NAMA_PENERIMA, 
                SDA.ALAMAT_BPKB AS ALAMAT_KIRIM, 
                PK.NO_SPK,
                PK.FAKTUR_PENJUALAN, 
                PK.TYPE_PENJUALAN,
                PK.TGL_SPK,
                PK.TGL_SO,
                MSB.TOTAL_STNK,
                HD.JUMLAH_HADIAH,
                HD.NAMA_PROGRAM,
                HD.NAMA_HADIAH,
                HD.START_DATE,
                HD.END_DATE,
                MC.NO_HP'
        );

        $data['motors'] = json_decode($this->curl->simple_get(API_URL . "/api/spk/spk_detailkendaraan", $param));



        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data['motors']) ? $data['motors']->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();


        if($data['motors'] && (is_array($data['motors']->message) || is_object($data['motors']->message))):
        foreach($data['motors']->message as $key =>  $motor):
            $data['subsidi'] = $this->getSubsidi($motor->NO_SPK);
            $data['subsidi_terbilang'] = terbilang($this->getSubsidi($motor->NO_SPK));
           
        endforeach; 
        endif;

        // $this->output->set_output(json_encode($data['motors']));        

        $this->load->view('sales/terima_hadiah', $data);
        $html = $this->output->get_output();
        
        $this->output->set_output(json_encode($html));        
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
