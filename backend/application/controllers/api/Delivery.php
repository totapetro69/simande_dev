<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Purchasing controller
 */
require APPPATH . '/libraries/REST_Controller.php';

class Delivery extends REST_Controller {

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

    public function do_get() {
        $param = array();
        $search = "";
        if ($this->get("kd_dealer")) {
            $param["KD_DEALER"] = $this->get("kd_dealer");
        }
        if ($this->get("kd_maindealer")) {
            $param["KD_MAINDEALER"] = $this->get("kd_maindealer");
        }
        if ($this->get("bulan")) {
            $param["BULAN"] = $this->get("bulan");
            if (!$this->get("tahun")) {
                $param["TAHUN"] = ($this->get("tahun")) ? $this->get("tahun") : date("Y");
            }
        }
        if ($this->get("tahun")) {
            $param["TAHUN"] = ($this->get("tahun"));
        }
        if ($this->get("jenis_po")) {
            $param["KD_JENISPO"] = $this->get("jenis_po");
        }
        if ($this->get("no_dolog")) {
            $param["NO_DOLOG"] = $this->get("no_dolog");
        }
        if ($this->get("status_do")) {
            if ($this->get("status_do") == "nol") {
                $param["STATUS_DO"] = 0;
            } else {
                $param["STATUS_DO"] = $this->get("status_do");
            }

		}
        if($this->get("tahun")){
            $param["TAHUN"]   =($this->get("tahun"));
        }
		if($this->get("jenis_po")){
			$param["KD_JENISPO"]	= $this->get("jenis_po");
		}
		if($this->get("no_po")){
			$param["NO_PO"]	= $this->get("no_po");
		}
		if($this->get("status_do")){
			if($this->get("status_do") == "nol"){
                                $param["APPROVAL_DO "]		= 0;
                                $param["STATUS_DO !="]		= -1;
			}else if($this->get("status_do") == "1"){
				$param["STATUS_DO"]		= 1;
                                $param["APPROVAL_DO"]		= 1;
                        }else if($this->get("status_do") == "2"){
				$param["STATUS_DO"]		= 2;
                                $param["APPROVAL_DO"]		= 1;
                        }else if($this->get("status_do") == "-1"){
				$param["STATUS_DO"]		= -1;
                                $param["APPROVAL_DO"]		= 0;
                        } 
                        
		}
        if ($this->get("keyword")) {
            $param = array();
            $search = array(
                "NO_DOLOG" => $this->get("keyword")

            );
        }

        $custom = "";
        if ($this->get("custom")) {
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        if ($this->get("orderby")) {
            $this->Main_model->set_orderby($this->get("orderby"));
        } else {
            $this->Main_model->set_orderby("TAHUN,BULAN,NO_DOLOG");
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_statusdata($this->get("row_status"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_DOMD", $param);
    }
    

    public function do_post() {
        $param = array();
        $param["KD_DEALER"] = $this->post("kd_dealer");
        $param["PO_DEALER"] = $this->post("po_dealer");
        $param["TGL_DOLOG"] = $this->post("tgl_dolog");
        $param["NO_DOLOG"] = $this->post("no_dolog");
        $this->Main_model->data_sudahada($param, "TRANS_DOMD");
        $param["LOKASI_ASAL"] = $this->post("lokasi_asal");
        $param["ALAMAT_LOKASI"] = $this->post("alamat_lokasi");
        $param["STATUS"] = $this->post("status");
        $param["HARGA"] = $this->post("harga");
        $param["CREATED_DATE"] = $this->post("created_date");
        $param["CREATED_BY"] = $this->post("created_by");
        $param["ROW_STATUS"] = $this->post("row_status");
        $param["AKSESORIS"] = $this->post("aksesoris");
        $param["NO_DO"] = $this->post("no_do");
        $this->resultdata("SP_TRANS_DOMD_INSERT", $param, 'post', TRUE);
    }

    public function do_put() {
        $param = array();
        $param["KD_DEALER"] = $this->put("kd_dealer");
        $param["PO_DEALER"] = $this->put("po_dealer");
        $param["TGL_DOLOG"] = $this->put("tgl_dolog");
        $param["NO_DOLOG"] = $this->put("no_dolog");
        $param["KD_SALES"] = $this->put("kd_sales");
        $param["NAMA_SALES"] = $this->put("nama_sales");
        $param["LOKASI_ASAL"] = $this->put("lokasi_asal");
        $param["ALAMAT_LOKASI"] = $this->put("alamat_lokasi");
        $param["STATUS"] = $this->put("status");
        $param["HARGA"] = $this->put("harga");
        $param["MODIFY_DATE"] = $this->put("modify_date");
        $param["MODIFY_BY"] = $this->put("modify_by");
        $param["ROW_STATUS"] = $this->put("row_status");
        $param["AKSESORIS"] = $this->put("aksesoris");
        $param["NO_DO"] = $this->put("no_do");
        $this->resultdata("SP_TRANS_DOMD_UPDATE", $param, 'put', TRUE);
    }

    public function do_delete() {
        $param = array();
        $param["NO_DOLOG"] = $this->delete("no_dolog");
        $param["MODIFY_BY"] = $this->delete("modify_by");
        $this->resultdata("SP_TRANS_DOMD_DELETE", $param, 'delete', TRUE);
    }

    public function dodetail_get() {
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
        $this->resultdata("TRANS_DOMD_DETAIL", $param);
    }

    public function dodetail_post() {
        $param = array();
        $param["DOLOG_NO_DOLOG"] = $this->post("dolog_no_dolog");
        $param["KD_TYPEMOTOR"] = $this->post("kd_typemotor");
        $param["KD_WARNA"] = $this->post("kd_warna");
        $param["QTY"] = $this->post("qty");
        $param["CREATED_DATE"] = $this->post("created_date");
        $param["CREATED_BY"] = $this->post("created_by");
        $param["ROW_STATUS"] = $this->post("row_status");
        $this->Main_model->data_sudahada($param, "TRANS_DOMD_DETAIL");
        $param["DISC_CASH"] = $this->post("disc_cash");
        $param["DISC_POT_DO"] = $this->post("disc_pot_do");
        $param["DISC_SUBSIDI"] = $this->post("disc_subsidi");
        $param["DISC_CAS_PERSEN"] = $this->post("disc_cas_persen");
        $param["KD_PKT_AKSESORIS"] = $this->post("kd_pkt_aksesoris");
        $this->resultdata("SP_TRANS_DOMD_DETAIL_INSERT", $param, 'post', TRUE);
    }

    public function dodetail_put() {
        $param = array();
        $param["DOLOG_NO_DOLOG"] = $this->put("dolog_no_dolog");
        $param["KD_TYPEMOTOR"] = $this->put("kd_typemotor");
        $param["KD_WARNA"] = $this->put("kd_warna");
        $param["QTY"] = $this->put("qty");
        $param["MODIFY_DATE"] = $this->put("modify_date");
        $param["MODIFY_BY"] = $this->put("modify_by");
        $param["ROW_STATUS"] = $this->put("row_status");
        $param["DISC_CASH"] = $this->put("disc_cash");
        $param["DISC_POT_DO"] = $this->put("disc_pot_do");
        $param["DISC_SUBSIDI"] = $this->put("disc_subsidi");
        $param["DISC_CAS_PERSEN"] = $this->put("disc_cas_persen");
        $param["KD_PKT_AKSESORIS"] = $this->put("kd_pkt_aksesoris");
        $this->resultdata("SP_TRANS_DOMD_DETAIL_UPDATE", $param, 'put', TRUE);
    }

    public function dodetail_delete() {
        $param = array();
        $param["DOLOG_NO_DOLOG"] = $this->delete("dolog_no_dolog");
        $param["MODIFY_BY"] = $this->delete("modify_by");
        $this->resultdata("SP_TRANS_DOMD_DETAIL_DELETE", $param, 'delete', TRUE);
    }


}

?>