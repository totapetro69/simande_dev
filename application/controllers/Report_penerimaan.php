<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_penerimaan extends CI_Controller {

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

    public function pagination($config) {

        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open'] = "<ul class='pagination pagination-sm m-t-none m-b-none'>";
        $config['full_tag_close'] = "</ul>";
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
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods notf prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    //Penerimaan
    public function penerimaan() {
        $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_TERIMASJMOTOR.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_TERIMASJMOTOR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
            //'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => "NO_TERIMASJM asc",
            'field' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-') as TGL_TRANS2",
            'custom' => $tgl,
            'groupby_text' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-')",
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
            $params['where_in'] = isDealerAkses();
            $params['where_in_field'] = 'KD_DEALER';
        }

        //print_r($param);die();

        /*if ($this->input->get("kd_dealer") != null) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }elseif($this->session->userdata('kd_dealer')){
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }else{
            $param["kd_dealer"] = '0098';
        }*/

        /*if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }*/

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));
        //print_r($data["list"]);die();
        $params = array(
            //'custom' => $this->input->get('kd_dealer') ? "TRANS_TERIMASJMOTOR.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_TERIMASJMOTOR.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'",
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM = TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT"),
                array("TRANS_SJMASUK AS TS", "TS.NO_SJMASUK=TRANS_TERIMASJMOTOR.NO_SJMASUK", "LEFT")
            ),

            'field' => 'TRANS_TERIMASJMOTOR.ID, TRANS_TERIMASJMOTOR.NO_TERIMASJM, TS.TGL_SJMASUK, TRANS_TERIMASJMOTOR.TGL_TRANS, TRANS_TERIMASJMOTOR.KD_ITEM, MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR, MP.NAMA_ITEM, MP.KD_WARNA, MP.KET_WARNA, TRANS_TERIMASJMOTOR.NO_RANGKA,TRANS_TERIMASJMOTOR.KSU, TRANS_TERIMASJMOTOR.JUMLAH, TRANS_TERIMASJMOTOR.NO_MESIN, TS.NO_SJMASUK, TS.NO_PO, TS.NO_FAKTUR, TS.NAMA_TAGIHAN',
            'custom' => $kd_dealer . " AND " . $tgl,

           

            'groupby' => TRUE
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $params));
        //print_r($data['list_group']);die();
         //var_dump($data["list"] );exit();
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

        $this->template->site('report/penerimaan', $data);
    }

    public function penerimaan_print() {
        $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_TERIMASJMOTOR.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_TERIMASJMOTOR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
         
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => "NO_TERIMASJM asc",
            'field' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-') as TGL_TRANS2",
            'custom' => $tgl,
            'groupby_text' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-')",
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
            $params['where_in'] = isDealerAkses();
            $params['where_in_field'] = 'KD_DEALER';
        }        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));
        
        $params = array(
         
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM = TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT"),
                array("TRANS_SJMASUK AS TS", "TS.NO_SJMASUK=TRANS_TERIMASJMOTOR.NO_SJMASUK", "LEFT")
            ),

            'field' => 'TRANS_TERIMASJMOTOR.ID, TRANS_TERIMASJMOTOR.NO_TERIMASJM, TS.TGL_SJMASUK, TRANS_TERIMASJMOTOR.TGL_TRANS, TRANS_TERIMASJMOTOR.KD_ITEM, MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR, MP.NAMA_ITEM, MP.KD_WARNA, MP.KET_WARNA, TRANS_TERIMASJMOTOR.NO_RANGKA,TRANS_TERIMASJMOTOR.KSU, TRANS_TERIMASJMOTOR.JUMLAH, TRANS_TERIMASJMOTOR.NO_MESIN, TS.NO_SJMASUK, TS.NO_PO, TS.NO_FAKTUR, TS.NAMA_TAGIHAN',
            'custom' => $kd_dealer . " AND " . $tgl,
            'groupby' => TRUE
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $params));
       
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
        $this->load->view('report/penerimaan_print',$data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function penerimaan_part() {
        $data = array();  

        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(            
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => "NO_TRANS asc",
            'field' => "NO_TRANS,NO_SURATJALAN,NO_PO, replace(convert(char(11),TGL_TRANS,113),' ','-') as TGL_TRANS2",
            'custom' => $tgl,
          
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
            $params['where_in'] = isDealerAkses();
            $params['where_in_field'] = 'KD_DEALER';
        }        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima", $param));
         //print_r($data['list']);die();
       
       $tgl2 = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TRANS_PART_TERIMA.TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_PART_TERIMA.TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PART_TERIMA.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_PART_TERIMA.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
      
        $params = array(
           
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
             
                array("TRANS_PART_TERIMADETAIL AS TT", "TT.NO_TRANS = TRANS_PART_TERIMA.NO_TRANS", ""),
                array("MASTER_PART AS MP", "MP.PART_NUMBER = TT.PART_NUMBER AND MP.ROW_STATUS >=0", "LEFT")
            ),

            'field' => 'TRANS_PART_TERIMA.*, TT.PART_NUMBER,TT.JUMLAH,TT.KD_RAKBIN, MP.PART_DESKRIPSI,MP.PART_REFERENCE',
            'custom' =>$tgl2,
             
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part_terima", $params));
        //print_r($data['list_group']);die();
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
        $this->template->site('report/penerimaan_part', $data);
    }


    public function selisih_penerimaan() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SJMASUK.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SJMASUK.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TRANS_SJMASUK.TGL_SJMASUK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_SJMASUK.TGL_SJMASUK,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";

        $param = array(         
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'NO_SJMASUK asc',
            'field' => "NO_SJMASUK,replace(convert(char(11),TGL_SJMASUK,113),' ','-') AS TGL_SJMASUK",
            'custom' => $tgl." AND NO_SJMASUK IN (SELECT NO_SJMASUK from TRANS_TERIMASJMOTOR) AND NO_RANGKA not in (select NO_RANGKA from TRANS_TERIMASJMOTOR)",
            'groupby_text' => "NO_SJMASUK, replace(convert(char(11),TGL_SJMASUK,113),' ','-')",
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }       

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));

        $params = array(
           
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(               
                          array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_WARNA=TRANS_SJMASUK.KD_WARNA AND MP.KD_TYPEMOTOR = TRANS_SJMASUK.KD_TYPEMOTOR", "LEFT")
            ),
            'field' => 'TRANS_SJMASUK.ID,TRANS_SJMASUK.TGL_SJMASUK, COUNT(*) as JUMLAH, TRANS_SJMASUK.NO_MESIN, TRANS_SJMASUK.NO_RANGKA,  MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR, MP.NAMA_ITEM, MP.KD_WARNA, MP.KET_WARNA,  TRANS_SJMASUK.NO_SJMASUK, TRANS_SJMASUK.NO_PO, TRANS_SJMASUK.NO_FAKTUR, TRANS_SJMASUK.NAMA_TAGIHAN',
            'custom' => $kd_dealer . " AND " . $tgl ." AND TRANS_SJMASUK.NO_SJMASUK IN (SELECT NO_SJMASUK FROM TRANS_TERIMASJMOTOR WHERE ROW_STATUS >= 0) AND NO_RANGKA not in (select NO_RANGKA from TRANS_TERIMASJMOTOR)",
            'groupby' => TRUE
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $params));
        //print_r($data["list_group"]);die();
         //var_dump($data["list"] );exit();
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

        $this->template->site('report/selisih_penerimaan', $data);
    }

    public function penerimaan_nrfs() {
         $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_TERIMASJMOTOR.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_TERIMASJMOTOR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
         
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => "NO_TERIMASJM asc",
            'field' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-') as TGL_TRANS2",
            'custom' => $tgl. "AND STOCK_STATUS = 0",
            'groupby_text' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-')",
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
            $params['where_in'] = isDealerAkses();
            $params['where_in_field'] = 'KD_DEALER';
        }        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));
        
        $params = array(
         
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM = TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT"),
                array("TRANS_SJMASUK AS TS", "TS.NO_SJMASUK=TRANS_TERIMASJMOTOR.NO_SJMASUK", "LEFT")
            ),

            'field' => 'TRANS_TERIMASJMOTOR.ID, TRANS_TERIMASJMOTOR.NO_TERIMASJM, TS.TGL_SJMASUK, TRANS_TERIMASJMOTOR.TGL_TRANS, TRANS_TERIMASJMOTOR.KD_ITEM, MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR, MP.NAMA_ITEM, MP.KD_WARNA, MP.KET_WARNA, TRANS_TERIMASJMOTOR.NO_RANGKA,TRANS_TERIMASJMOTOR.KSU, TRANS_TERIMASJMOTOR.JUMLAH, TRANS_TERIMASJMOTOR.NO_MESIN, TS.NO_SJMASUK, TS.NO_PO, TS.NO_FAKTUR, TS.NAMA_TAGIHAN',
            'custom' => $kd_dealer . " AND STOCK_STATUS = 0 AND " . $tgl,
            'groupby' => TRUE
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $params));
       //print_r($data['list_group']);die();
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

        $this->template->site('report/penerimaan_nrfs', $data);
    }

    public function penerimaan_nrfs_print() {
         $data = array();

        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_TERIMASJMOTOR.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_TERIMASJMOTOR.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_TRANS,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_TRANS,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
         
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => "NO_TERIMASJM asc",
            'field' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-') as TGL_TRANS2",
            'custom' => $tgl. "AND STOCK_STATUS = 0",
            'groupby_text' => "NO_TERIMASJM, replace(convert(char(11),TGL_TRANS,113),' ','-')",
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
            $params['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
            $params['where_in'] = isDealerAkses();
            $params['where_in_field'] = 'KD_DEALER';
        }        

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));
        
        $params = array(
         
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM = TRANS_TERIMASJMOTOR.KD_ITEM", "LEFT"),
                array("TRANS_SJMASUK AS TS", "TS.NO_SJMASUK=TRANS_TERIMASJMOTOR.NO_SJMASUK", "LEFT")
            ),

            'field' => 'TRANS_TERIMASJMOTOR.ID, TRANS_TERIMASJMOTOR.NO_TERIMASJM, TS.TGL_SJMASUK, TRANS_TERIMASJMOTOR.TGL_TRANS, TRANS_TERIMASJMOTOR.KD_ITEM, MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR, MP.NAMA_ITEM, MP.KD_WARNA, MP.KET_WARNA, TRANS_TERIMASJMOTOR.NO_RANGKA,TRANS_TERIMASJMOTOR.KSU, TRANS_TERIMASJMOTOR.JUMLAH, TRANS_TERIMASJMOTOR.NO_MESIN, TS.NO_SJMASUK, TS.NO_PO, TS.NO_FAKTUR, TS.NAMA_TAGIHAN',
            'custom' => $kd_dealer . " AND STOCK_STATUS = 0 AND " . $tgl,
            'groupby' => TRUE
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $params));
       //print_r($data['list_group']);die();
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

       
        $this->load->view('report/penerimaan_nrfs_print',$data);
        $html = $this->output->get_output();

        $this->output->set_output(json_encode($html));
    }

    public function selisih_penerimaan_print() {
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SJMASUK.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SJMASUK.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TRANS_SJMASUK.TGL_SJMASUK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TRANS_SJMASUK.TGL_SJMASUK,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
         
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'NO_SJMASUK asc',
            'field' => "NO_SJMASUK,replace(convert(char(11),TGL_SJMASUK,113),' ','-') AS TGL_SJMASUK",
            'custom' => $tgl." AND NO_SJMASUK IN (SELECT NO_SJMASUK from TRANS_TERIMASJMOTOR) AND NO_RANGKA not in (select NO_RANGKA from TRANS_TERIMASJMOTOR)",
            'groupby_text' => "NO_SJMASUK, replace(convert(char(11),TGL_SJMASUK,113),' ','-')",
            'limit' => 50
            
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }       

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $param));

        $params = array(
           
            'keyword' => $this->input->get('keyword'),
            'jointable' => array(               
                          array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_WARNA=TRANS_SJMASUK.KD_WARNA AND MP.KD_TYPEMOTOR = TRANS_SJMASUK.KD_TYPEMOTOR", "LEFT")
            ),
            'field' => 'TRANS_SJMASUK.ID,TRANS_SJMASUK.TGL_SJMASUK, COUNT(*) as JUMLAH, TRANS_SJMASUK.NO_MESIN, TRANS_SJMASUK.NO_RANGKA,  MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR, MP.NAMA_ITEM, MP.KD_WARNA, MP.KET_WARNA,  TRANS_SJMASUK.NO_SJMASUK, TRANS_SJMASUK.NO_PO, TRANS_SJMASUK.NO_FAKTUR, TRANS_SJMASUK.NAMA_TAGIHAN',
            'custom' => $kd_dealer . " AND " . $tgl ." AND TRANS_SJMASUK.NO_SJMASUK IN (SELECT NO_SJMASUK FROM TRANS_TERIMASJMOTOR WHERE ROW_STATUS >= 0) AND NO_RANGKA not in (select NO_RANGKA from TRANS_TERIMASJMOTOR)",
            'groupby' => TRUE
        );

        $data["list_group"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/suratjalan", $params));
        //print_r($data["list_group"]);die();
         //var_dump($data["list"] );exit();
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

        $this->load->view('report/selisih_penerimaan_print',$data);
        $html = $this->output->get_output();
//
        $this->output->set_output(json_encode($html));
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
