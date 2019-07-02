<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Purchasing controller
 */
require APPPATH . '/libraries/REST_Controller.php';

class Purchasing extends REST_Controller {

    /**
     * [__construct description]
     * @param string $config [description]
     */
    function __construct($config = 'rest') {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
        $this->load->helper("zetro");
    }

    /**
     * Generate result data from select query
     * @param string $tabel_name
     * @param array $param
     * @param string $method default 'get'
     * @param boolean $status default FALSE = not authorizes
     */
    function resultdata($tabel_name, $param = '', $method = 'get', $status = FALSE) {
        $this->Main_model->tabel_name($tabel_name);
        $this->Main_model->set_parameter($param);
        $result = $this->Main_model->response_result($method, $status);
        $this->response($result);
    }

    /**
     * purchase order list
     * @return [type] [description]
     */
    public function po_get() {
        $param = array();
        $search = "";
        if ($this->get("kd_dealer")) {
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if ($this->get("kd_maindealer")) {
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if ($this->get("bulan_kirim")) {
            $param["BULAN_KIRIM"] = $this->get("bulan_kirim");
            if (!$this->get("tahun")) {
                $param["TAHUN_KIRIM"] = ($this->get("tahun_kirim")) ? $this->get("tahun_kirim") : date("Y");
            }
        }
        if ($this->get("tahun")) {
            $param["TAHUN_KIRIM"] = ($this->get("tahun"));
        }
        if ($this->get("jenis_po")) {
            $param["KD_JENISPO"] = $this->get("jenis_po");
        }
        if ($this->get("no_po")) {
            $param["NO_PO"] = $this->get("no_po");
        }
        if ($this->get("status_po")) {
            if ($this->get("status_po") == "nol") {
                $param["STATUS_PO"] = 0;
            } else {
                $param["STATUS_PO"] = $this->get("status_po");
            }

		}
        if($this->get("tahun")){
            $param["TAHUN_KIRIM"]   =($this->get("tahun"));
        }
		if($this->get("jenis_po")){
			$param["KD_JENISPO"]	= $this->get("jenis_po");
		}
		if($this->get("no_po")){
			$param["NO_PO"]	= $this->get("no_po");
		}
		if($this->get("status_po")){
			if($this->get("status_po") == "nol"){
                                $param["APPROVAL_PO "]		= 0;
                                $param["STATUS_PO !="]		= -1;
			}else if($this->get("status_po") == "1"){
				$param["STATUS_PO"]		= 1;
                                $param["APPROVAL_PO"]		= 1;
                        }else if($this->get("status_po") == "2"){
				$param["STATUS_PO"]		= 2;
                                $param["APPROVAL_PO"]		= 1;
                        }else if($this->get("status_po") == "-1"){
				$param["STATUS_PO"]		= -1;
                                $param["APPROVAL_PO"]		= 0;
                        } 
                        
		}

        if($this->get("keyword")){
            $param=array();
            $search= array(
            	"NO_PO"		=> $this->get("keyword")
                );
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NO_PO" => $this->get("keyword")

            );
        }

        $custom = "";
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if ($this->get("orderby")) {
            $this->Main_model->set_orderby($this->get("orderby"));
        } else {
            $this->Main_model->set_orderby("TAHUN_KIRIM,BULAN_KIRIM,NO_PO");
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_statusdata($this->get("row_status"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PO2MD", $param);
    }

    /**
     * purchase order add
     * @return [type] [description]
     */
    public function po_post() {
        $param = array();
        $param["NO_PO"] = $this->post("no_po");
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["KD_MAINDEALER"] = $this->post("kd_maindealer");
        $param["KD_JENISPO"] = $this->post("jenis_po");
        $param["BULAN_KIRIM"] = $this->post("bulan_kirim");
        $param["TAHUN_KIRIM"] = $this->post("tahun_kirim");
        $this->Main_model->data_sudahada($param, "TRANS_PO2MD");
        $param["PERIODE_PO"] = $this->post("periode_po");
        $param["TGL_PO"] = $this->post("tgl_po");
        $param["TGL_SELESAI_PO"] = $this->post("tgl_selesai_po");
        $param["TGL_AWALPO"] = $this->post("tgl_awalpo");
        $param["TGL_AKHIRPO"] = $this->post("tgl_akhirpo");
        /* $param["TOP_PO"]			= $this->put("top_po");
          $param["TOD_PO"]			= $this->put("top_po");
          $param["PPN_PO"]			= $this->put("ppn_po");
          $param["PPH_PO"]			= $this->put("pph_po"); */
        $param["KETERANGAN"] = $this->post("keterangan");
        $param["CREATED_BY"] = $this->post("created_by");
        $this->resultdata("SP_TRANS_PO2MD_INSERT", $param, 'post', TRUE);
    }

    /**
     * purchase order update
     * @return [type] [description]
     */
    public function po_put() {
        $param = array();
        $param["NO_PO"] = $this->put("no_po");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["KD_MAINDEALER"] = $this->put("kd_maindealer");
        $param["KD_JENISPO"] = $this->put("jenis_po");
        $param["BULAN_KIRIM"] = $this->put("bulan_kirim");
        $param["TAHUN_KIRIM"] = $this->put("tahun_kirim");
        $param["PERIODE_PO"] = $this->put("periode_po");
        $param["TGL_PO"] = $this->put("tgl_po");
        /* $param["TGL_AWALPO"]		= $this->put("tgl_awalpo");
          $param["TGL_AKHIRPO"]		= $this->put("tgl_akhirpo");
          $param["TOP_PO"]			= $this->put("top_po");
          $param["TOD_PO"]			= $this->put("top_po");
          $param["PPN_PO"]			= $this->put("ppn_po");
          $param["PPH_PO"]			= $this->put("pph_po");
          $param["STATUS_PO"]			= $this->put("status_po"); */
        $param["KETERANGAN"] = $this->put("keterangan");
        $param["LASTMODIFIED_BY"] = $this->put("created_by");
        $this->resultdata("SP_TRANS_PO2MD_UPDATE", $param, 'put', TRUE);
    }

    /**
     * Approval po
     * @return [type] [description]
     */
    public function po_approve_put() {
        $param = array();
        $param["NO_PO"] = $this->put("no_po");
        $param["APPROVAL_PO"] = $this->put("approval_po");
        $param["APPROVAL_POBY"] = $this->put("approval_poby");
        $param["STATUS_PO"] = $this->put("status_po");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PO2MD_APPROVE", $param, 'put', TRUE);
    }

    /**
     * purchase order delete
     * @return [type] [description]
     */
    public function po_delete() {
        $param = array();
        $param["NO_PO"] = $this->delete("no_po");
        $param["LASTMODIFIED_BY"] = $this->delete("LASTMODIFIED_BY");
        $this->resultdata("SP_TRANS_PO2MD_DELETE", $param, 'delete', TRUE);
    }

    /**
     * [podetail_get description]
     * @return [type] [description]
     */
    public function podetail_get() {
        $param = array();
        $search = "";
        if ($this->get("id_po")) {
            $param["ID_PO"] = $this->get("id_po");
        }
        if ($this->get("no_po")) {
            $param["NO_PO"] = $this->get("no_po");
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "KD_WARNA" => $this->get("keyword"),
                "KD_TYPEMOTOR" => $this->get("keyword")
            );
        }

        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_statusdata($this->get("row_status"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PO2MD_DETAIL", $param);
    }

    /**
     * [podetail_post description]
     * @return [type] [description]
     * last modified 24/11/2017 10:28
     */
    public function podetail_post() {
        $param = array();
        $param["ID_PO"] = $this->post("id_po");
        $param["KD_TYPEMOTOR"] = $this->post("kd_type");
        $param["KD_WARNA"] = $this->post("kd_warna");
        //$this->Main_model->set_customcriteria("ROW_STATUS>=0");
        $this->Main_model->data_sudahada($param, "TRANS_PO2MD_DETAIL");
        $param["HARGA"] = ($this->post("harga")) ? $this->post("harga") : "0";
        $param["DISKON"] = ($this->post("diskon")) ? $this->post("diskon") : "0";
        $param["DISKON_TYPE"] = $this->post("diskon_type");
        $param["FIX_Qty"] = $this->post("fix_qty");
        $param["T1_QTY"] = $this->post("t1_qty");
        $param["T2_QTY"] = $this->post("t2_qty");
        $param["KET_DETAIL"] = $this->post("ket_detail");
        $param["CREATED_BY"] = $this->post("created_by");
        $this->resultdata("SP_TRANS_PO2MD_DETAIL_INSERT", $param, 'post', TRUE);
    }

    /**
     * [podetail_put description]
     * @return [type] [description]
     */
    public function podetail_put() {
        $param = array();
        // $param["ID"]				= $this->put("id");
        $param["ID_PO"] = $this->put("id_po");
        $param["KD_TYPEMOTOR"] = $this->put("kd_type");
        $param["KD_WARNA"] = $this->put("kd_warna");
        $param["HARGA"] = $this->put("harga");
        $param["DISKON"] = $this->put("diskon");
        $param["DISKON_TYPE"] = $this->put("diskon_type");
        $param["FIX_Qty"] = $this->put("fix_qty");
        $param["T1_QTY"] = $this->put("t1_qty");
        $param["T2_QTY"] = $this->put("t2_qty");
        $param["KET_DETAIL"] = $this->put("ket_detail");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $param["ROW_STATUS"] = $this->put("row_status");
        $this->resultdata("SP_TRANS_PO2MD_DETAIL_UPDATE", $param, 'put', TRUE);
    }

    /**
     * [podetail_delete description]
     * @return [type] [description]
     */
    public function podetail_delete() {
        $param["ID"] = $this->delete("id");
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");

        $this->resultdata("SP_TRANS_PO2MD_DETAIL_DELETE", $param, 'delete', TRUE);
    }

    /**
     * [nomorpo_get description]
     * @return [type] [description]
     */
    public function nomorpo_get() {
        $param = array();
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PO2MD_DETAIL", $param);
    }

    public function nomorpo_post() {
        
    }

    public function nomorpo_put() {
        
    }

    public function sales_get() {
        $param = array();
        $param["KD_TYPE"] = $this->get("kd_type");
        $param["KD_WARNA"] = $this->get("kd_warna");
    }

    public function stock_get() {
        
    }

    /**
     * [suratjalan_get description]
     * @return [type] [description]
     */
    public function suratjalan_get() {
        $param = array();
        $search = '';
        if ($this->get("no_sjmasuk")) {
            $param["NO_SJMASUK"] = $this->get("no_sjmasuk");
        }
        if ($this->get("kd_dealer")) {
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if ($this->get("tgl_sjmasuk")) {
            $param["TGL_SJMASUK"] = $this->get("tgl_sjmasuk");
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NO_SJMASUK" => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword"),
                "NO_MESIN" => $this->get("keyword"),
                "NAMA_TAGIHAN" => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get("row_status"));
        $custom = "";
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_SJMASUK", $param);
    }

    /**
     * [suratjalan_post description]
     * @return [type] [description]
     */
    public function suratjalan_post() {
        $param = array();
        $param["NO_SJMASUK"] = $this->post('no_sjmasuk');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"] = $this->post('kd_dealer');
        $param["NO_RANGKA"] = $this->post('no_rangka');
        $param["NO_MESIN"] = $this->post('no_mesin');
        $this->Main_model->data_sudahada($param, 'TRANS_SJMASUK');
        $param["KD_TYPEMOTOR"] = $this->post('kd_typemotor');
        $param["KD_WARNA"] = $this->post('kd_warna');
        $param["TGL_SJMASUK"] = $this->post('tgl_sjmasuk');
        $param["THN_PERAKITAN"] = $this->post('thn_perakitan');
        $param["NO_REFF"] = $this->post('no_reff');
        $param["NAMA_TAGIHAN"] = $this->post('nama_tagihan');
        $param["VNORANGKA1"] = $this->post('vnoRangka1');
        $param["NOPOL"] = $this->post('nopol');
        $param["EXPEDISI"] = $this->post('expedisi');
        $param["NO_FAKTUR"] = $this->post('no_faktur');
        $param["NO_POMD"] = $this->post("no_pomd");
        $param["NO_PO"] = $this->post("no_po");
        $param["CREATED_BY"] = $this->post('created_by');
        $this->resultdata("SP_TRANS_SJMASUK_INSERT", $param, 'post', TRUE);
    }

    /**
     * [suratjalan_put description]
     * @return [type] [description]
     */
    public function suratjalan_put() {
        $param = array();
        $param["NO_SJMASUK"] = $this->put('no_sjmasuk');
        $param["KD_DEALER"] = $this->put('kd_dealer');
        $param["KD_TYPEMOTOR"] = $this->put('kd_typemotor');
        $param["KD_WARNA"] = $this->put('kd_warna');
        $param["NO_RANGKA"] = $this->put('no_rangka');
        $param["NO_MESIN"] = $this->put('no_mesin');
        $param["THN_PERAKITAN"] = $this->put('thn_perakitan');
        $param["NO_REFF"] = $this->put('no_reff');
        $param["NAMA_TAGIHAN"] = $this->put('nama_tagihan');
        $param["VNORANGKA1"] = $this->put('vnoRangka1');
        $param["LASTMODIFIED_BY"] = $this->put('lastmodified_by');

        $this->resultdata("SP_TRANS_SJMASUK_UPDATE", $param, 'put', TRUE);
    }

    /**
     * [suratjalan_delete description]
     * @return [type] [description]
     */
    public function suratjalan_delete() {
        $param = array();
        $param["NO_SJMASUK"] = $this->delete('no_sjmasuk');
        $param["KD_DEALER"] = $this->delete('kd_dealer');
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_SJMASUK_DELETE", $param, 'delete', TRUE);
    }

    /**
     * [posp_get description]
     * @return [type] [description]
     */
    public function posp_get() {
        $param = array();
        $search = '';
        if ($this->get("no_po")) {
            $param["NO_PO"] = $this->get("no_po");
        }
        if ($this->get("kd_maindealer")) {
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if ($this->get("kd_dealer")) {
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if ($this->get("tahun")) {
            $param["TAHUN"] = $this->get("tahun");
        }
        if ($this->get("bulan")) {
            $param["BULAN"] = $this->get("bulan");
        }
        if ($this->get('jenis_po')) {
            $param["JENIS_PO"] = $this->get('jenis_po');
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NO_PO" => $this->get("keyword"),
                "KD_MAINDEALER" => $this->get("keyword"),
                "KD_DEALER" => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get("row_status"));
        $custom = "";
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_groupby_text($this->get("groupby_text"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_POSP", $param);
    }

    /**
     * [posp_post description]
     * @return [type] [description]
     */
    public function posp_post() {
        $param = array();
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["KD_DEALER"] = $this->post('kd_dealer');
        $param["NO_PO"] = $this->post('no_po');
        $this->Main_model->data_sudahada($param, 'TRANS_POSP');
        $param["TGL_PO"] = tglToSql($this->post('tgl_po'));
        $param["JENIS_PO"] = $this->post('jenis_po');
        $param["NAMA_KONSUMEN"] = $this->post('nama_konsumen');
        $param["KD_KONSUMEN"] = $this->post('kd_konsumen');
        $param["ALAMAT_KONSUMEN"] = $this->post('alamat_konsumen');
        $param["KOTA_KONSUMEN"] = $this->post('kota_konsumen');
        $param["KODE_POS"] = $this->post('kode_pos');
        ;
        $param["NO_TELP"] = $this->post('no_telp');
        $param["TYPE_MOTOR"] = $this->post('type_motor');
        $param["TAHUN_MOTOR"] = $this->post('tahun_motor');
        $param["VOR"] = $this->post('vor');
        $param["JSR"] = $this->post('jsr');
        $param["LSO"] = $this->post('lso');
        $param["REFF_NO"] = $this->post('reff_no');
        $param["BULAN"] = $this->post('bulan');
        $param["TAHUN"] = $this->post('tahun');
        $param["CREATED_BY"] = $this->post('created_by');
        $this->resultdata("SP_TRANS_POSP_INSERT", $param, 'post', TRUE);
    }

    function posp_approval_put() {
        $param = array();
        $param["KD_DEALER"] = $this->put('kd_dealer');
        $param["NO_PO"] = $this->put('no_po');
        $param["CREATED_BY"] = $this->put('created_by');
        $param["APPROVAL"] = $this->put('approval');
        $param["KETERANGAN"] = $this->put('keterangan');
        $this->resultdata("SP_TRANS_POSP_APPROVAL", $param, 'put', TRUE);
    }

    /**
     * [posp_put description]
     * @return [type] [description]
     */
    public function posp_put() {
        $param = array();
        // $param["ID"]				= $this->put('id');
        $param["KD_MAINDEALER"] = $this->put('kd_maindealer');
        $param["KD_DEALER"] = $this->put('kd_dealer');
        $param["NO_PO"] = $this->put('no_po');
        $param["TGL_PO"] = tglToSql($this->put('tgl_po'));
        $param["JENIS_PO"] = $this->put('jenis_po');
        $param["NAMA_KONSUMEN"] = $this->put('nama_konsumen');
        $param["KD_KONSUMEN"] = $this->put('kd_konsumen');
        $param["ALAMAT_KONSUMEN"] = $this->put('alamat_konsumen');
        $param["KOTA_KONSUMEN"] = $this->put('kota_konsumen');
        $param["KODE_POS"] = $this->put('kode_pos');
        $param["NO_TELP"] = $this->put('no_telp');
        $param["TYPE_MOTOR"] = $this->put('type_motor');
        $param["TAHUN_MOTOR"] = $this->put('tahun_motor');
        $param["VOR"] = $this->put('vor');
        $param["JSR"] = $this->put('jsr');
        $param["LSO"] = $this->put('lso');
        $param["REFF_NO"] = $this->put('reff_no');
        $param["BULAN"] = $this->put('bulan');
        $param["TAHUN"] = $this->put('tahun');
        $param["LASTMODIFIED_BY"] = $this->put('lastmodified_by');

        $this->resultdata("SP_TRANS_POSP_UPDATE", $param, 'put', TRUE);
    }

    /**
     * [posp_delete description]
     * @return [type] [description]
     */
    public function posp_delete() {
        $param = array();
        $param["ID"] = $this->delete('id');
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_POSP_DELETE", $param, 'delete', TRUE);
    }

    /**
     * [posp_detail_get description]
     * @return [type] [description]
     */
    public function posp_detail_get() {
        $param = array();
        $search = '';
        if ($this->get("no_po")) {
            $param["NO_PO"] = $this->get("no_po");
        }
        if ($this->get("part_number")) {
            $param["PART_NUMBER"] = $this->get("part_number");
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NO_PO" => $this->get("keyword"),
                "PART_NUMBER" => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get("row_status"));
        $custom = "";
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_POSP_DETAIL", $param);
    }

    /**
     * [posp_detail_post description]
     * @return [type] [description]
     */
    public function posp_detail_post() {
        $param = array();
        $param["NO_PO"] = $this->post('no_po');
        $param["PART_NUMBER"] = $this->post('part_number');
        $this->Main_model->data_sudahada($param, 'TRANS_POSP_DETAIL');
        $param["JUMLAH"] = ($this->post('jumlah')) ? $this->post('jumlah') : "0";
        $param["HARGA"] = ($this->post('harga')) ? $this->post('harga') : "0";
        $param["PPN"] = ($this->post('ppn')) ? $this->post('ppn') : "0";
        $param["PO_STATUS"] = $this->post('po_status');
        $param["CREATED_BY"] = $this->post('created_by');
        $this->resultdata("SP_TRANS_POSP_DETAIL_INSERT", $param, 'post', TRUE);
    }

    /**
     * [posp_detail_put description]
     * @return [type] [description]
     */
    public function posp_detail_put() {
        $param = array();
        $param["ID"] = $this->put('id');
        $param["NO_PO"] = $this->put('no_po');
        $param["PART_NUMBER"] = $this->put('part_number');
        $param["JUMLAH"] = ($this->put('jumlah')) ? $this->put('jumlah') : "0";
        ;
        $param["HARGA"] = ($this->put('harga')) ? $this->put('harga') : "0";
        $param["PPN"] = ($this->put('ppn')) ? $this->put('ppn') : "0";
        $param["PO_STATUS"] = $this->put('po_status');
        $param["LASTMODIFIED_BY"] = $this->put('lastmodified_by');

        $this->resultdata("SP_TRANS_POSP_DETAIL_UPDATE", $param, 'put', TRUE);
    }

    /**
     * [posp_detail_delete description]
     * @return [type] [description]
     */
    public function posp_detail_delete() {
        $param = array();
        $param["ID"] = $this->delete('id');
        $param["LASTMODIFIED_BY"] = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_POSP_DETAIL_DELETE", $param, 'delete', TRUE);
    }

    /**
     * [penerimaan_unit description]
     * @return [type] [description]
     */
    public function penerimaan_unit_get() {
        $param = array();
        $search = '';
        if ($this->get("no_sjmasuk")) {
            $param["NO_SJMASUK"] = $this->get("no_sjmasuk");
        }
        if ($this->get("kd_dealer")) {
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if ($this->get("tgl_sjmasuk")) {
            $param["TGL_SJMASUK"] = $this->get("tgl_sjmasuk");
        }
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NO_SJMASUK" => $this->get("keyword"),
                "NO_RANGKA" => $this->get("keyword"),
                "NO_MESIN" => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get("row_status"));
        $custom = "";
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PENERIMAAN_UNIT_VIEW", $param);
    }

    /**
     * [sjmasuk_vnorangka1_put description]
     * @return [type] [description]
     */
    public function sjmasuk_vnorangka1_put() {
        $param = array();
        $param["NO_SJMASUK"] = $this->put("no_sjmasuk");
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["NO_RANGKA"] = $this->put("no_rangka");
        $param["VNORANGKA1"] = $this->put("vnoRangka1");
        $param["LASTMODIFIED_BY"] = $this->put("lastmodified_by");
        $this->resultdata("SP_TRANS_SJMASUK_UPDATE_VNORANGKA1", $param, 'put', TRUE);
    }


}

?>