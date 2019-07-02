<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasing extends CI_Controller {

    var $API = "";

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
        $this->load->helper('zetro');
        $this->load->helper('file');
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    function index() {
        
    }

    /**
     * [PO_list description]
     */
    public function PO_list() {
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0, /*
              'kd_dealer' => $this->input->get('kd_dealer'), */
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER",
            'orderby' => "TRANS_PO2MD.TGL_PO DESC", //"TAHUN_KIRIM,BULAN_KIRIM DESC,KD_JENISPO,TRANS_PO2MD.ID desc" ,
                /* 'custom'    =>"MASTER_DEALER.NAMA_DEALER IS NOT NULL" */
        );
        $param["bulan_kirim"] = ($this->input->get("bln")) ? $this->input->get("bln") : date("m");
        $param["tahun"] = ($this->input->get("thn")) ? $this->input->get("thn") : date("Y");
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $param = array(
            'field' => "TAHUN_KIRIM",
            'groupby' => TRUE,
            'orderby' => "TAHUN_KIRIM"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('purchasing/index', $data);
    }

    public function po_received() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0, /*
              'kd_dealer' => $this->input->get('kd_dealer'), */
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER",
            'orderby' => "TRANS_PO2MD.TGL_PO DESC", //"TAHUN_KIRIM,BULAN_KIRIM DESC,KD_JENISPO,TRANS_PO2MD.ID desc" ,
                /* 'custom'    =>"MASTER_DEALER.NAMA_DEALER IS NOT NULL" */
        );
        if ($this->input->get('kd_dealer')) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }
        if ($this->input->get('keyword')) {
            $param["keyword"] = $this->input->get('keyword');
        }
        $param["bulan_kirim"] = ($this->input->get("bln")) ? $this->input->get("bln") : date("m");
        $param["tahun"] = ($this->input->get("thn")) ? $this->input->get("thn") : date("Y");
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $param = array(
            'field' => "TAHUN_KIRIM",
            'groupby' => TRUE,
            'orderby' => "TAHUN_KIRIM"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('purchasing/po_received', $data);
    }

    public function monitoring_po() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0, /*
              'kd_dealer' => $this->input->get('kd_dealer'), */
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER",
            'orderby' => "TRANS_PO2MD.TGL_PO DESC", //"TAHUN_KIRIM,BULAN_KIRIM DESC,KD_JENISPO,TRANS_PO2MD.ID desc" ,
                /* 'custom'    =>"MASTER_DEALER.NAMA_DEALER IS NOT NULL" */
        );

        if ($this->input->get('kd_dealer')) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }
        if ($this->input->get('jenis_po')) {
            if ($this->input->get('jenis_po') != '') {
                $param["jenis_po"] = $this->input->get('jenis_po');
            }
        }
        if ($this->input->get('status_po')) {
            if ($this->input->get('status_po') != '') {
                $param["status_po"] = $this->input->get('status_po');
            }
        }
        $param["bulan_kirim"] = ($this->input->get("bln")) ? $this->input->get("bln") : date("m");
        $param["tahun"] = ($this->input->get("thn")) ? $this->input->get("thn") : date("Y");

        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );


        $param = array(
            'field' => "TAHUN_KIRIM",
            'groupby' => TRUE,
            'orderby' => "TAHUN_KIRIM"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('purchasing/monitoring_po', $data);
    }   

    public function monitoringpopart() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => 0,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => "TRANS_POSP.*,"
            . '(SELECT SUM(CSD.JUMLAH) FROM TRANS_POSP_DETAIL AS CSD WHERE ROW_STATUS = 0 AND CSD.NO_PO = TRANS_POSP.NO_PO) AS QTY_ORDER,'
            . '(SELECT SUM(TPD.JUMLAH) AS JUMLAH_TERIMA 
                FROM TRANS_PART_TERIMA TP JOIN TRANS_PART_TERIMADETAIL TPD ON(TP.NO_TRANS=TPD.NO_TRANS) 
                WHERE TP.NO_PO = TRANS_POSP.NO_PO) AS QTY_FULLFILMENT'
            ,
            'orderby' => "TGL_PO DESC"
        );
        if ($this->input->get('kd_dealer')) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }
        if ($this->input->get('jenis_po')) {
            if ($this->input->get('jenis_po') != '') {
                $param["jenis_po"] = $this->input->get('jenis_po');
            }
        }
        if ($this->input->get('tahun')) {
            $param["tahun"] = $this->input->get('tahun');
        }
        if ($this->input->get('bulan')) {
            $param["bulan"] = $this->input->get('bulan');
        }


        $data = array();
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));

        $string = link_pagination();

        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        
        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('purchasing/monitoringpopart', $data);
    }

    /**
     * [add_po description]
     */
    public function add_po() {
        $data = array();
        if ($this->input->get('b') == 'y') {
            unset($_SESSION['podetail']);
        }
        if ($this->input->get("n")) {
            $param = array(
                'no_po' => base64_decode(urldecode($this->input->get("n"))),
                'jointable' => array(
                    array("MASTER_DEALER_V", "MASTER_DEALER_V.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")
                ),
                'field' => "TRANS_PO2MD.*,MASTER_DEALER_V.ALAMAT, MASTER_DEALER_V.NAMA_KABUPATEN, MASTER_DEALER_V.NAMA_PROPINSI, MASTER_DEALER_V.TLP as TLP, MASTER_DEALER_V.TLP2",
            );
            $data["poheader"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
            //var_dump($data["poheader"]);exit;
            if ($data["poheader"]) {
                if ($data["poheader"]->totaldata > 0) {
                    $params = array(
                        'id_po' => $data["poheader"]->message[0]->ID,
                        //'custom' => "TRANS_PO2MD_DETAIL.ROW_STATUS>=0",
                        'jointable' => array(
                            array("MASTER_P_TYPEMOTOR M", "M.KD_TYPEMOTOR=TRANS_PO2MD_DETAIL.KD_TYPEMOTOR AND M.KD_WARNA=TRANS_PO2MD_DETAIL.KD_WARNA", "LEFT")
                        ),
                        'field' => "TRANS_PO2MD_DETAIL.*,TRANS_PO2MD_DETAIL.ID AS PODETAILID,M.NAMA_ITEM",
                        'orderby' => "TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,TRANS_PO2MD_DETAIL.ID,TRANS_PO2MD_DETAIL.KD_WARNA"
                    );
                    $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/podetail", $params));
                }
            }
        }
        $param = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));

        $this->template->site('purchasing/add_po', $data);
    }

    public function detail_po_ship_to() {
        $data = array();
        $param = array(
            'kd_dealer' => $this->input->post("kd_dealer"),
            'field' => "MASTER_DEALER_V.ALAMAT, MASTER_DEALER_V.NAMA_KABUPATEN, MASTER_DEALER_V.NAMA_PROPINSI, MASTER_DEALER_V.TLP as TLP, MASTER_DEALER_V.TLP2",
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/1/0", $param));
        echo json_encode($data->message);
    }

    /**
     * depreciated
     * @return [type] [description]
     */
    public function listmotor($debug = null) {
        $param = array();
        $typemotor = ($this->input->post("lst")) ? $this->input->post("lst") : 0;
        // echo $typemotor;
        // echo $this->input->get("lst");
        $stoked = "\"ISNULL((SELECT SUM(STOCK_AKHIR) FROM TRANS_STOCKMOTOR AS TS WHERE TS.KD_ITEM=MASTER_P_TYPEMOTOR.KD_ITEM AND KD_DEALER='" .
                $this->session->userdata("kd_dealer") . "' AND STOCK_AKHIR >=0 GROUP BY TS.KD_ITEM;TS.KD_DEALER);0) AS STOCK\"";
        $stoked2 = "\"ISNULL((SELECT SUM(STOCK_AKHIR) FROM TRANS_STOCKMOTOR TS WHERE TS.KD_GROUPMOTOR=MASTER_P_TYPEMOTOR.KD_TYPEMOTOR AND KD_DEALER='" .
                $this->session->userdata("kd_dealer") . "' GROUP BY TS.KD_GROUPMOTOR;TS.KD_DEALER);0) STOCK\"";
        $param["field"] = ($typemotor == 0) ? "KD_ITEM,NAMA_TYPEMOTOR,NAMA_ITEM,KET_WARNA," . $stoked : "KD_TYPEMOTOR,NAMA_TYPEMOTOR,NAMA_PASAR";
        if ($typemotor == 0) {
            $param["keyword"] = $this->input->post("keyword");
            $param["groupby"] = true;
            //$param["custom"] = "TGL_AKHIREFF >=GETDATE()";
            $param["orderby"] = ($typemotor == 0) ? "KD_ITEM,NAMA_ITEM" : "KD_TYPEMOTOR,NAMA_TYPEMOTOR";
        } else if ($typemotor == 2) {
            $param = array(
                'groupby' => true,
                'field' => 'KD_WARNA,KET_WARNA'/* ,
                      'orderby' => 'KD_WARNA' */
            );
            $param["custom"] = "(NAMA_PASAR LIKE '%" . $this->input->post("keyword") . "' OR KD_WARNA LIKE '%" . $this->input->post("keyword") . "%' OR KET_WARNA LIKE '%" . $this->input->post("keyword") . "%')";
            //$param["custom"] = str_replace("edh", '_edh', strtolower($param["custom"]));
            //$param["custom"] .= " AND TGL_AKHIREFF >=GETDATE()";
            if ($this->input->post('kd_type') != '') {
                $param["kd_type"] = $this->input->post('kd_type');
            }
        } else {
            $param["custom"] = "(KD_TYPEMOTOR LIKE '%" . $this->input->post("keyword") . "%' OR NAMA_TYPEMOTOR LIKE '%" . $this->input->post("keyword") . "%' OR NAMA_PASAR LIKE '%" . $this->input->post('keyword') . "%')";
            //$param["custom"] .= " AND TGL_AKHIREFF >=GETDATE()";
            $param["custom"] = str_replace("%", '&#37;', strtolower($param["custom"]));
            $param["orderby"] = ($typemotor == 0) ? "KD_ITEM,NAMA_ITEM" : "KD_TYPEMOTOR,NAMA_TYPEMOTOR";
        }

        $data["motor"] = json_decode($this->curl->simple_get(API_URL . "/api/master/motor", $param));
        //var_dump($data["motor"]);exit();
        if ($debug) {
            if ($data["motor"]) {
                $this->output->set_output(json_encode($data["motor"]));
                echo json_encode($data["motor"]);
            } else {
                var_dump($data["motor"]);
            }
            exit();
        }
        //print_r($param);exit();
        $html = "";
        $link = $this->input->post("lok");
        if ($data["motor"]) {
            if (($data["motor"]->totaldata > 0)) {
                if ($typemotor == 0) {

                    foreach ($data["motor"]->message as $key => $group) {
                        $html .= "<tr onclick=\"dropdown_item('" . $group->KD_ITEM . "','" . $group->NAMA_ITEM . "');\">";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->KD_ITEM . "</td>";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->NAMA_TYPEMOTOR . "</td>";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->NAMA_ITEM . "&nbsp;&nbsp;<small><label class='badge'>" . $group->STOCK . "</label></small></td>";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->KET_WARNA . "</td>";
                        $html .= "</tr>";
                    }
                } else if ($typemotor == 2) {
                    foreach ($data["motor"]->message as $key => $group) {
                        $html .= "<tr onclick=\"dropdown_item" . $link . "('" . $group->KD_WARNA . "','" . $group->KET_WARNA . "');\">";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->KD_WARNA . "</td>";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->KET_WARNA . "</td>";
                        $html .= "</tr>";
                    }
                } else {
                    foreach ($data["motor"]->message as $key => $group) {
                        $html .= "<tr onclick=\"dropdown_item('" . $group->KD_TYPEMOTOR . "','" . $group->NAMA_TYPEMOTOR . "');\">";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->KD_TYPEMOTOR . "</td>";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->NAMA_TYPEMOTOR . "</td>";
                        $html .= "<td style=\"white-space: nowrap;\">" . $group->NAMA_PASAR . "</td>";
                        $html .= "</tr>";
                    }
                }
            } else {
                $html = "<tr>";
                $html .= "<td style=\"white-space: nowrap;\" colspan='4'>" . $data["motor"]->message . "</td></tr>";
            }
        } else {
            $html = "<tr>";
            $html .= "<td style=\"white-space: nowrap;\" colspan='4'>Data tidak ditemukan</td></tr>";
        }
        echo $html;
    }

    /**
     * Menampung inputtan item po di session simpan sementara
     * @return [type] [description]
     */
    public function podetail_listtmp($sudah_ada = null) {
        $all_podetail = null;
        $html = "";
        if (!isset($_SESSION["podetail"])) {
            
        }
        $param = array();
        //nonaktikan id podetail yang di edit untuk di insert ulang

        if ((int) $this->input->post('idpo') > 0) {
            $paramx = array(
                'id' => $this->input->post('idpo'),
                'lastmodified_by' => $this->session->userdata('userid') . "- Proses EditItem"
            );
        }
        if ($this->input->post('idx')) {
            $key = array_search($this->input->post('idx'), array_column($_SESSION["podetail"], "id"));
            if ($key >= 0) {
                unset($_SESSION["podetail"][$key]);
            }
        }

        $param = (isset($_SESSION["podetail"]) ) ?
                (is_array($_SESSION['podetail'])) ? array_values($_SESSION["podetail"]) : null : null;
        // if(strlen(trim($this->input->post("kd_item")))==0){
        //     $param=array();
        // }
        if (strlen(trim($this->input->post("kd_item"))) >= 4) {
            $param[] = array(
                'kd_item' => $this->input->post("kd_item"),
                'nama_item' => $this->input->post("nama_item"),
                'fix_qty' => $this->input->post("fix_qty"),
                't1_qty' => ($this->input->post("t1_qty")) ? $this->input->post("t1_qty") : "0",
                't2_qty' => ($this->input->post("t2_qty")) ? $this->input->post("t2_qty") : "0",
                'idpo' => $this->input->post("idpo"),
                'id' => $this->input->post('id')
            );
        }
        $this->session->set_userdata('podetail', $param);

        $all_podetail = (isset($_SESSION["podetail"])) ? $_SESSION["podetail"] : null;
        //print_r($all_podetail);exit();
        if (isset($all_podetail)) {
            $podetailid = 0;
            if (count($all_podetail) > 0) {
                for ($i = 0; $i < count($all_podetail); $i++) {
                    $podetailid = $all_podetail[$i]["id"];
                    $podetailid = ($podetailid == "0") ? $i : $podetailid;
                    $aktif = ($i == (count($all_podetail) - 1)) ? ' tr-active' : '';
                    if (strlen(trim($all_podetail[$i]["kd_item"])) > 3) {
                        $html .= "<tr id='l_$podetailid'.$aktif>
                            <td>" . ($i + 1) . "</td>
                            <td style='white-space:nowrap'>
                                <a class='edit-btn hidden' role='button' onclick=\"editItem('" . $podetailid . "','" . $all_podetail[$i]["idpo"] . "');\"><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"Edit Item\" class=\"fa fa-edit text-success text-active\"></i></a>";
                        $html .= ((int) $all_podetail[$i]["id"] > 0) ?
                                "<a id='x_" . $podetailid . "' class='delete-btn' onclick=\"hapusItem('" . $podetailid . "');\"><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"hapus\" class=\"fa fa-trash text-danger text\"></i></a>" :
                                "<a class='delete-btn' role='button' onclick=\"unsetSession('" . $podetailid . "');\"><i class='fa fa-trash text-danger fa-fw'></i></a>";
                        $html .= "</td>
                            <td>" . $all_podetail[$i]["kd_item"] . "</td>
                            <td>" . $all_podetail[$i]["nama_item"] . "</td>
                            <td class='text-right'>" . number_format($all_podetail[$i]["fix_qty"], 0) . "</td>
                            <td class='text-right'>" . number_format($all_podetail[$i]["t1_qty"], 0) . "</td>
                            <td class='text-right'>" . number_format($all_podetail[$i]["t2_qty"], 0) . "</td>
                            <td class='text-center'>&nbsp;</td>
                        </tr>";
                    }
                }
            }
        }

        echo $html;
    }

    /**
     * dapatkan no po terakhir di tahun aktif
     * @return [type] [description]
     */
    function getnopo() {
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => 'PO',
            'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun_docno' => $this->input->post('tahun_kirim'),
            'limit' => 1,
            'offset' => 0
        );
        $bulan_kirim = $this->input->post('bulan_kirim');
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        //format nomor po : Nama Document-Kode Dealer-Tahunbulan-nomorurut
        if ($nomorpo == 0) {
            $nopo = "PO" . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) . "-" . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
            $paramin = array(
                'kd_docno' => 'PO',
                'kd_dealer' => $this->session->userdata("kd_dealer"),
                'tahun_docno' => $param["tahun_docno"],
                'bulan_docno' => $bulan_kirim,
                'nama_docno' => 'Nomor Urutan PO dengan fromat PO-KODE DEALER-TAHUNBULAN-URUTAN',
                'urutan_docno' => 1,
                'reset_docno' => 12,
                'created_by' => $this->session->userdata('user_id')
            );

            $insertnomorpo = $this->curl->simple_post(API_URL . "/api/setup/setup_docno", $paramin, array(CURLOPT_BUFFERSIZE => 10));
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = "PO" . str_pad($param["kd_dealer"], 3, '0', STR_PAD_LEFT) . "-" . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
            $param = array(
                'kd_docno' => 'PO',
                'kd_dealer' => ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer"),
                'tahun_docno' => $param["tahun_docno"],
                'urutan_docno' => $nomorpo - 1,
                'lastmodified_by' => $this->session->userdata('user_id')
            );
            $updatenomorpo = $this->curl->simple_put(API_URL . "/api/setup/docno", $param);
        }
        //var_dump($nopo);exit();
        return $nopo;
    }

    /**
     * [simpan_po description]
     * @return [type] [description]
     */
    public function simpan_po() {
        //data header
        ini_set("max_execution_time", 120);
        $hasil = array();
        $periode = "";
        $param_head = array();
        $param_detail = array();
        if ($this->input->post('jenis_po') == 'F') {
            $periode = explode('(', $this->input->post('periode_po'));
            if (count($periode) > 1) {
                $periode = ($this->input->post('jenis_po') == 'F') ? explode(' sd ', $periode[1]) : explode('', $periode[0]);
            } else {
                $periode = $periode[0];
            }
        } else {
            $periode = $this->input->post('periode_po');
        }
        $tambahitem = $this->input->post('no_po');
        $nomorpo = ($this->input->post('no_po')) ? $this->input->post('no_po') : $this->getnopo();
        echo tglToSql(0);
        $param_head = array(
            'no_po' => $nomorpo,
            'kd_maindealer' => $this->session->userdata('kd_maindealer'),
            'kd_dealer' => $this->input->post('kd_dealer'),
            'tgl_po' => tglToSql($this->input->post('tgl_po')),
            'tgl_selesai_po' => tglToSql($this->input->post('tgl_selesai_po')),
            'jenis_po' => $this->input->post('jenis_po'),
            'periode_po' => substr($this->input->post('periode_po'), 0, 1),
            'tgl_awalpo' => (is_array($periode)) ? tglToSql($periode[0]) : NULL,
            'tgl_akhirpo' => (is_array($periode)) ? tglToSql(str_replace(')', '', $periode[1])) : NULL,
            'bulan_kirim' => $this->input->post('bulan_kirim'),
            'tahun_kirim' => $this->input->post('tahun_kirim'),
            'created_by' => $this->session->userdata("user_id"),
            'keterangan' => $this->input->post('keterangan')
        );
        $datap = json_decode($this->curl->simple_post(API_URL . "/api/purchasing/po", $param_head, array(CURLOPT_BUFFERSIZE => 10)));

        if ($datap) {
            if ($datap->recordexists == true) {
                unset($param_head["tgl_po"]);
                unset($param_head["tgl_selesai_po"]);
                $param_head['tgl_po'] = tglToSql(($this->input->post('tgl_po')));
                $param_head['tgl_selesai_po'] = tglToSql(($this->input->post('tgl_selesai_po')));
                $param_head["lastmodified_by"] = $this->session->userdata("user_id");
                $datap = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/po", $param_head, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }

        $id_pods = null;
        if ($datap) {
            //update document number - execute via trigger in db
            if ($datap->message > 0) {
                $param_detail = json_decode($this->input->post('d'), true);
                ;
                // print_r($param_detail);
                for ($i = 0; $i < count($param_detail); $i++) {
                    $tipe_motor = explode("-", $param_detail[$i]["kd_item"]);
                    $param_pod = array(
                        'id_po' => $datap->message,
                        'kd_type' => $tipe_motor[0],
                        'kd_warna' => $tipe_motor[1],
                        'harga' => 0,
                        'diskon' => 0,
                        'diskon_type' => 'P',
                        'row_status' => 0,
                        'fix_qty' => ($param_detail[$i]["fix_qty"]) ? $param_detail[$i]["fix_qty"] : "0",
                        't1_qty' => ($param_detail[$i]["t1_qty"]) ? $param_detail[$i]["t1_qty"] : "0",
                        't2_qty' => ($param_detail[$i]["t2_qty"]) ? $param_detail[$i]["t2_qty"] : "0",
                        'ket_detail' => '',
                        'created_by' => $this->session->userdata("user_id")
                    );
                    $id_pods = $this->curl->simple_post(API_URL . "/api/purchasing/podetail", $param_pod, array(CURLOPT_BUFFERSIZE => 10));
                    $hasil = json_decode($id_pods);
                    if ($hasil) {
                        if ($hasil->recordexists == true) {
                            $param_pod["lastmodified_by"] = $this->session->userdata("user_id");
                            $id_pods = $this->curl->simple_put(API_URL . "/api/purchasing/podetail", $param_pod, array(CURLOPT_BUFFERSIZE => 10));
                        }
                    }
                }
            }
        } else {
            $id_pods = (isset($id_pods)) ? ($datap) : (array("status" => false, "message" => 'Gagal di simpan'));
        }

        $this->data_output($id_pods, 'post', /* "purchasing/add_po?n=". */ urlencode(base64_encode($nomorpo)));
    }

    /**
     * [cetak_po description]
     * @return [type] [description]
     */
    public function cetak_po() {
        $data = array();
        if (base64_decode(urldecode($this->input->get("n")))) {
            $param = array(
                'no_po' => base64_decode(urldecode($this->input->get("n"))),
                'jointable' => array(array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")),
                'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER"
            );
            $data["poheader"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
            $params = array(
                'id_po' => $data["poheader"]->message[0]->ID,
                'custom' => "TRANS_PO2MD_DETAIL.ROW_STATUS >=0",
                'jointable' => array(array("MASTER_P_TYPEMOTOR", "MASTER_P_TYPEMOTOR.KD_ITEM={fn CONCAT(TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,'-'+TRANS_PO2MD_DETAIL.KD_WARNA)}", "LEFT")),
                'field' => "TRANS_PO2MD_DETAIL.*,TRANS_PO2MD_DETAIL.ID AS PODETAILID,MASTER_P_TYPEMOTOR.NAMA_ITEM",
                'orderby' => "TRANS_PO2MD_DETAIL.FIX_QTY,TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,TRANS_PO2MD_DETAIL.KD_WARNA"
            );
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/podetail", $params));
            //untuk keperluan create file udpo
            $data["fileudpo"] = $this->podetail_list();
        }

        $this->load->view('purchasing/cetak_po', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    public function md_approve_po() {
        $data = array();
        if (base64_decode(urldecode($this->input->get("n")))) {
            $param = array(
                'no_po' => base64_decode(urldecode($this->input->get("n"))),
                'jointable' => array(array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")),
                'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER"
            );
            $data["poheader"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
            $params = array(
                'id_po' => $data["poheader"]->message[0]->ID,
                'custom' => "TRANS_PO2MD_DETAIL.ROW_STATUS >=0",
                'jointable' => array(array("MASTER_P_TYPEMOTOR", "MASTER_P_TYPEMOTOR.KD_ITEM={fn CONCAT(TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,'-'+TRANS_PO2MD_DETAIL.KD_WARNA)}", "LEFT")),
                'field' => "TRANS_PO2MD_DETAIL.*,TRANS_PO2MD_DETAIL.ID AS PODETAILID,MASTER_P_TYPEMOTOR.NAMA_ITEM",
                'orderby' => "TRANS_PO2MD_DETAIL.FIX_QTY,TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,TRANS_PO2MD_DETAIL.KD_WARNA"
            );
            $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/podetail", $params));
            //untuk keperluan create file udpo
            $data["fileudpo"] = $this->podetail_list();
        }

        $this->load->view('purchasing/md_approve_po', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }

    /**
     * [po_exists description]
     * @return [type] [description]
     */
    public function po_exists($load_detail = null) {
        $data = array();
        $result = "0";
        $param = array(
            'kd_dealer' => $this->input->post('kd_dealer'),
            'bulan_kirim' => $this->input->post('bulan_kirim'),
            'tahun_kirim' => $this->input->post('tahun_kirim'),
            'jenis_po' => $this->input->post('jenis_po'),
            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
            'custom' => 'ROW_STATUS >= 0 AND PO_STATUS >= 0',
            'jointable' => array(
                array("MASTER_DEALER_V", "MASTER_DEALER_V.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,MASTER_DEALER_V.ALAMAT, MASTER_DEALER_V.NAMA_KABUPATEN, MASTER_DEALER_V.NAMA_PROPINSI, MASTER_DEALER_V.TLP as TLP, MASTER_DEALER_V.TLP2",
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        /* print_r($param);
          exit(); */
        if ($load_detail) {
            if ($data) {
                if ($data->totaldata > 0) {
                    echo json_encode($data->message);
                    exit();
                }
            }
        } else {
            if ($data) {
                $result = $data->totaldata;
                if ($data->totaldata > 0) {
                    foreach ($data->message as $key => $value) {
                        //$result .= ":";
                        $result .= ($value->APPROVAL_PO) ? ":" . $value->APPROVAL_PO . ":" . urlencode(base64_encode($value->NO_PO)) : ":0:" . urlencode(base64_encode($value->NO_PO));
                    }
                } else {
                    $result .= ":0";
                }
            } else {
                $result .= ":0";
            }
            echo $result;
        }
    }

    /**
     * [podetail_list description]
     * @return [type] [description]
     */
    public function podetail_list($no_po = null, $debug = null) {
        $param = array(
            'no_po' => ($no_po) ? $no_po : base64_decode(urldecode($this->input->get("n"))),
            'jointable' => array(
                array("TRANS_PO2MD_DETAIL AS PD", "PD.ID_PO=TRANS_PO2MD.ID", "LEFT"),
                array("MASTER_DEALER AS MS", "MS.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT"),
                array("MASTER_MAINDEALER AS MD", "MD.KD_MAINDEALER=MS.KD_MAINDEALER", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MT", "MT.KD_ITEM=CONCAT(PD.KD_TYPEMOTOR,'-',PD.KD_WARNA)", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,PD.ID AS PODETAILID,PD.KD_WARNA,PD.KD_TYPEMOTOR,PD.FIX_QTY,PD.T1_QTY,PD.T2_QTY,
                MT.KD_ITEM,MT.NAMA_ITEM,MS.NAMA_DEALER,MS.KD_DEALERAHM,MD.NAMA_MAINDEALER"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        //var_dump($data->message);
        return $data;
    }

    /**
     * [createfile_udpo description]
     * @return [type] [description]
     */
    public function createfile_udpo() {
        $data = array();
        $data = $this->podetail_list();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('ymdHis') . "-" . $row->NO_PO . ".UDPO";
            $isifile .= $row->KD_MAINDEALER . ";";
            $isifile .= $row->KD_DEALERAHM . ";";
            $isifile .= $row->NO_PO . ";";
            $isifile .= str_replace("/", "", tglFromSql($row->TGL_PO)) . ";";
            $isifile .= $row->BULAN_KIRIM . ";";
            $isifile .= $row->TAHUN_KIRIM . ";";
            $isifile .= $row->KD_TYPEMOTOR . ";";
            $isifile .= $row->KD_WARNA . ";";
            $isifile .= str_replace(",", "", number_format($row->FIX_QTY, 0)) . ";";
            $isifile .= str_replace("/", "", tglFromSql($row->TGL_AWALPO)) . ";";
            $isifile .= str_replace("/", "", tglFromSql($row->TGL_AKHIRPO)) . PHP_EOL;
        }

        $this->load->helper("download");
        force_download($namafile, $isifile);
    }

    /**
     * [pobulanlalu description]
     * @return [type] [description]
     */
    public function pobulanlalu($skrng = null) {
        $html = "";
        $approval = isBolehAkses("c");
        $approval += isBolehAkses("e");
        $param = array(
            'kd_dealer' => $this->input->post("kd_dealer"),
            'bulan_kirim' => $this->input->post("bulan_kirim"),
            'tahun_kirim' => $this->input->post("tahun_kirim"),
            'jenis_po' => $this->input->post("jenis_po"),
            //'custom'        => "APPROVAL_PO >0",
            'jointable' => array(
                array("TRANS_PO2MD_DETAIL AS PD", "PD.ID_PO=TRANS_PO2MD.ID", "LEFT"),
                array("MASTER_DEALER AS MS", "MS.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT"),
                array("MASTER_MAINDEALER AS MD", "MD.KD_MAINDEALER=MS.KD_MAINDEALER", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MT", "MT.KD_ITEM=CONCAT(PD.KD_TYPEMOTOR,'-',PD.KD_WARNA)", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,PD.ID AS PODETAILID,PD.KD_WARNA,PD.KD_TYPEMOTOR,PD.FIX_QTY,PD.T1_QTY,PD.T2_QTY,
                MT.KD_ITEM,MT.NAMA_ITEM,MS.NAMA_DEALER,MS.KD_DEALERAHM,MD.NAMA_MAINDEALER"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        //var_dump($data);exit();
        //masukan ke session 
        $all_podetail = NULL;
        if (!isset($_SESSION)) {
            session_start();
        }
        $paramx = "";
        $param = array();
        $paramx = (isset($_SESSION["podetail"])) ? $_SESSION["podetail"] : "";
        if ($data) {
            if ($data->totaldata > 0) {

                $i = 0;
                $hidden_class = ($approval == 0) ? 'hidden' : '';
                if ($skrng) {
                    foreach ($data->message as $key => $value) {
                        $param[] = array(
                            'kd_item' => $value->KD_TYPEMOTOR . "-" . $value->KD_WARNA,
                            'nama_item' => $value->NAMA_ITEM,
                            'fix_qty' => number_format($value->FIX_QTY, 0),
                            't1_qty' => number_format($value->T1_QTY, 0),
                            't2_qty' => number_format($value->T2_QTY, 0),
                            'idpo' => $value->ID,
                            'id' => $value->PODETAILID
                        );
                    }
                } else {
                    foreach ($data->message as $key => $value) {
                        $param[] = array(
                            'kd_item' => $value->KD_TYPEMOTOR . "-" . $value->KD_WARNA,
                            'nama_item' => $value->NAMA_ITEM,
                            'fix_qty' => number_format($value->T1_QTY, 0),
                            't1_qty' => number_format($value->T2_QTY, 0),
                            't2_qty' => 0,
                            'idpo' => 0,
                            'id' => 0,
                        );
                    }
                }
                $this->session->set_userdata('podetail', $param);
            } else {
                if ($this->input->post("jenis_po") == 'F') {
                    unset($_SESSION["podetail"]);
                }
            }
            //print_r($param);
        } else {
            echo 'PO Bulan lalu belum dibuat';
        }
    }

    /**
     * [podetailid description]
     * @return [type] [description]
     */
    public function podetailid() {
        $data = array();
        $param = array(
            /* 'id_po'     => $this->input->post('id_po'), */
            'custom' => "TRANS_PO2MD_DETAIL.ID = " . $this->input->post('id'),
            'jointable' => array(
                array("MASTER_P_TYPEMOTOR AS MT", "MT.KD_ITEM=CONCAT(TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,'-',TRANS_PO2MD_DETAIL.KD_WARNA)", "LEFT")
            ),
            'field' => "TRANS_PO2MD_DETAIL.*,MT.KD_ITEM,MT.NAMA_ITEM"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/podetail", $param));
        /* var_dump($data);
          exit(); */
        echo json_encode($data->message);
    }

    /**
     * [get_poid description]
     * @param  [type] $nopo [description]
     * @return [type]       [description]
     */
    public function get_poid($nopo) {
        $poid = array();
        $param = array(
            'no_po' => $nopo,
            'field' => 'ID'
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        foreach ($data->message as $key => $value) {
            $poid['message'] = $value->ID;
            $pois['status'] = TRUE;
        }
        /* var_dump($poid);
          exit(); */
        return json_encode($poid);
    }

    /**
     * [po_delete description]
     * @return [type] [description]
     */
    public function po_delete() {
        $param = array(
            'no_po' => base64_decode(urldecode($this->input->get('n'))),
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $id_pods = json_decode($this->curl->simple_delete(API_URL . "/api/purchasing/po", $param, array(CURLOPT_BUFFERSIZE => 10)));
        /* var_dump($id_pods);
          exit(); */
        $this->data_output($id_pods, 'delete');
    }

    /**
     * [podetail_delete description]
     * @return [type] [description]
     */
    public function podetail_delete() {
        $param["id"] = ($this->input->get("id"));
        $param["lastmodified_by"] = $this->session->userdata("user_id");
        $id_pods = json_decode($this->curl->simple_delete(API_URL . "/api/purchasing/podetail", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->data_output($id_pods, 'delete');
    }

    /**
     * [po_typeahead description]
     * @return [type] [description]
     */
    public function po_typeahead() {
        $param = array(
            'field' => 'NO_PO',
            'row_status' => 0,
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));

        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->NO_PO;
        }

        $result['keyword'] = ($data_message[0]);

        $this->output->set_output(json_encode($result));
    }

    public function posp_typeahead() {
        $param = array(
            'field' => 'NO_PO',
            'row_status' => 0,
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));

        if ($data["list"]->status) {
            foreach ($data["list"]->message as $key => $message) {
                $data_message[0][$key] = $message->NO_PO;
            }

            $result['keyword'] = ($data_message[0]);

            $this->output->set_output(json_encode($result));
        }
    }

    /**
     * [approval_po description]
     * @return [type] [description]
     */
    function approval_po() {
        if (base64_decode(urldecode($this->input->get("a")))) {
            $param = array(
                'no_po' => base64_decode(urldecode($this->input->get("a"))),
                'approval_po' => "1",
                'status_po' => "1",
                'approval_poby' => $this->session->userdata("user_id"),
                'lastmodified_by' => $this->session->userdata("user_id")
            );
            $data["hasil"] = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/po_approve", $param));
            /* print_r($param);
              exit(); */
        }

        //get aproval level user login
        $param = array(
            'user_id' => $this->session->userdata("user_id"),
            'kd_doc' => 'PO2MD'
        );
        $data["approval"] = json_decode($this->curl->simple_get(API_URL . "/api/login/users_app", $param));

        //get po for approval
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'custom' => "(ISNULL(APPROVAL_PO,0)=0 AND STATUS_PO=0)",
            'jointable' => array(
                array("MASTER_DEALER", "MASTER_DEALER.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT"),
                array("USERS", "USERS.USER_ID=TRANS_PO2MD.CREATED_BY", "LEFT")),
            'field' => "TRANS_PO2MD.*,MASTER_DEALER.NAMA_DEALER,USERS.USER_NAME"
        );
        if (base64_decode(urldecode($this->input->get("n")))) {
            $param["keyword"] = base64_decode(urldecode($this->input->get("n")));
            /* $param['offset']    =0;
              $param['limit' ]    => 1; */
        }


        $data["poheader"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));

        $poid = (base64_decode(urldecode($this->input->get('d')))) ? base64_decode(urldecode($this->input->get('d'))) : 0;
        if ($data["poheader"]) {
            if ($data["poheader"]->status == true) {
                $params = array(
                    'id_po' => ($poid > 0) ? $poid : $data["poheader"]->message[0]->ID,
                    'custom' => "TRANS_PO2MD_DETAIL.ROW_STATUS >=0",
                    'jointable' => array(array("MASTER_P_TYPEMOTOR", "MASTER_P_TYPEMOTOR.KD_ITEM=CONCAT(TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,'-',TRANS_PO2MD_DETAIL.KD_WARNA)", "LEFT")),
                    'field' => "TRANS_PO2MD_DETAIL.*,MASTER_P_TYPEMOTOR.NAMA_ITEM",
                    'orderby' => "FIX_QTY DESC"
                );

                $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/podetail", $params));
            }
        }
        $this->template->site('purchasing/approval_po', $data);
    }

    function reject_po() {
        $param = array(
            'no_po' => $this->input->get("id"),
            'status_po' => "-1",
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $data = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/po_approve", $param));
        $this->data_output($data, 'reject');
    }

    function return_po() {
        $param = array(
            'no_po' => $this->input->get("id"),
            'status_po' => "1",
            'approval_po' => "0",
            'approval_poby' => "returned",
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $data = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/po_approve", $param));
        $this->data_output($data, 'returned');
    }

    function process_po() {
        $param = array(
            'no_po' => $this->input->get("id"),
            'status_po' => "2",
            'approval_po' => "1",
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $data = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/po_approve", $param));
        $this->data_output($data, 'process');
    }

    /**
     * PO Sparepart
     */

    /**
     * [posp_list description]
     * @param  [type] $onlyPO [description]
     * @return [type]         [description]
     */
    function posp_list($onlyPO = null) {
        $data = array();
        $params = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == "Root") {
            $params = array();
        }
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $params));
        $potahun = array(
            "field" => "TAHUN", 'groupby' => TRUE, 'orderby' => "TAHUN DESC"
        );
        $data['tahunpo'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $potahun));

        $param = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer'),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'jointable' => array(array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_POSP.KD_DEALER", "LEFT")),
            'field' => 'TRANS_POSP.*,MD.NAMA_DEALER,'
            . 'CASE WHEN (SELECT COUNT(CSD.ID) FROM TRANS_POSP_DETAIL AS CSD WHERE ROW_STATUS = 0 AND CSD.NO_PO = TRANS_POSP.NO_PO) >0 THEN 1 ELSE 0 END STATUS_DETAIL'
        );
        if ($this->input->get("jenis_order")) {
            $param["jenis_po"] = $this->input->get("jenis_order");
        }

        $data['po'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        if ($onlyPO) {
            if ($data["po"]) {
                if ($data["po"]->totaldata > 0) {
                    echo json_encode($data["po"]->message);
                    //exit();
                }
            }
        } else {

            $this->template->site('purchasing/posplist', $data);
        }
    }

    public function podetail_list_sparepart($onlyPLHO = null, $debug = null) {
        $result = array();
        $kd_dealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $param = array(
            'custom' => "TRANS_POSP_DETAIL.ROW_STATUS >= 0 ",
            'keyword' => base64_decode(urldecode($this->input->get("n"))),
            'jointable' => array(
                array("TRANS_POSP AS PD", "PD.NO_PO=TRANS_POSP_DETAIL.NO_PO", "LEFT"),
                array("MASTER_PART M", "M.PART_NUMBER=TRANS_POSP_DETAIL.PART_NUMBER", "LEFT")
            ),
            'field' => 'PD.*,TRANS_POSP_DETAIL.PART_NUMBER,TRANS_POSP_DETAIL.JUMLAH,TRANS_POSP_DETAIL.HARGA,M.PART_DESKRIPSI'
        );
        if ($onlyPLHO == '1') {
            unset($param["keyword"]);
            unset($param["field"]);
            $param["field"] = "PD.NO_PO,PD.TGL_PO,PD.NAMA_KONSUMEN,PD.ALAMAT_KONSUMEN,PD.NO_TELP,PD.JENIS_PO";
            $param["groupby"] = true;
            $param["custom"] .= "AND PD.JENIS_PO='" . $this->input->get('jp') . "' AND TRANS_POSP_DETAIL.PO_STATUS =0 ";
            $param["custom"] .= "AND PD.KD_DEALER ='$kd_dealer'";
        }
        if ($this->input->get('nopo')) {
            unset($param["keyword"]);
            $param["no_po"] = $this->input->get('nopo');
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp_detail", $param));
        if ($data) {
            if ($data->totaldata > 0) {
                if ($onlyPLHO == '1' ||
                        $debug == '1') {
                    echo json_encode($data->message);
                } else {
                    return ($data);
                }
            }
        }
    }

    public function posudahditerima($debug = null) {
        $param = array(
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'kd_main_dealer' => $this->session->userdata("kd_maindealer"),
            'jointable' => array()
        );
    }

    public function createfile_pdpo_sparepart() {
        $data = array();
        $data = $this->podetail_list_sparepart();
        $namafile = "";
        $isifile = "";
        $n = 0;
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $row) {
                    $n++;
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALER . "-" . date('ymdHis') . "-" . str_replace("-", "", $row->NO_PO) . ".PDPO";
                    $isifile .= $row->KD_DEALER . ";";
                    $isifile .= $row->NO_PO . ";";
                    $isifile .= str_replace("/", "", $row->TGL_PO) . ";";
                    $isifile .= $row->JENIS_PO . ";";
                    $isifile .= $n . ";";
                    $isifile .= $row->PART_NUMBER . ";"; //detail
                    $isifile .= $row->JUMLAH . ";"; //detail
                    $isifile .= $row->HARGA . ";"; //detail
                    $isifile .= $row->NAMA_KONSUMEN . ";";
                    $isifile .= $row->ALAMAT_KONSUMEN . ";";
                    $isifile .= $row->KOTA_KONSUMEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->TYPE_MOTOR . ";";
                    $isifile .= $row->TAHUN_MOTOR . ";";
                    $isifile .= $row->NO_TELP . ";";
                    $isifile .= $row->VOR . ";";
                    $isifile .= $row->JSR . ";" . PHP_EOL;
                }
            }
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }

    public function podetail_plhlo() {
        $param = array(
            'custom' => "TRANS_POSP_DETAIL.ROW_STATUS >= 0 ",
            'keyword' => base64_decode(urldecode($this->input->get("n"))),
            'jointable' => array(
                array("TRANS_POSP AS PD", "PD.NO_PO=TRANS_POSP_DETAIL.NO_PO", "LEFT"),
                array("TRANS_PART_TERIMA AS TPT", "TPT.NO_PO = PD.NO_PO", "LEFT"),
                array("TRANS_PART_TERIMADETAIL AS TPD", "TPD.NO_TRANS = TPT.NO_TRANS", "LEFT")
            ),
            'field' => 'PD.*,TRANS_POSP_DETAIL.PART_NUMBER,TRANS_POSP_DETAIL.JUMLAH,TRANS_POSP_DETAIL.HARGA'
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp_detail", $param));
        return $data;
    }

    public function createfile_plhlo_sparepart() {
        $data = array();
        $data = $this->podetail_plhlo();
        $namafile = "";
        $isifile = "";
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $row) {
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALER . "-" . date('ymdHis') . "-" . str_replace("-", "", $row->NO_PO) . ".PLHLO";
                    $isifile .= $row->KD_DEALER . ";";
                    $isifile .= ";"; //$row->NO_PO. ";";  //no pesanan customer
                    $isifile .= ";"; //str_replace("/", "", $row->TGL_PO) . ";"; // Tanggal pesanan customer
                    $isifile .= $row->KD_MAINDEALER . ";";
                    $isifile .= $row->NO_PO . ";"; //"NO_PO_D_KE_MD;";
                    $isifile .= str_replace("/", "", $row->TGL_PO) . ";"; //"TGL_PO_D_KE_MD;";
                    $isifile .= $row->PART_NUMBER . ";";
                    $isifile .= ";"; //$row->TGL_TERIMA . ";";
                    $isifile .= ";"; //$row->QTY_TERIMA . ";"; 
                    $isifile .= ";"; //$row->QTY_PESANAN . ";";
                    $isifile .= ";"; //$row->NO_SO . ";";
                    $isifile .= ";"; //$row->TGL_SO . ";";
                    $isifile .= ";"; //$row->QTY_SALES . ";";
                    $isifile .= ";"; //$row->NAMA_CONSUMENT . ";";
                    $isifile .= ";" . PHP_EOL;
                }
            } else {
                
            }
        }

        $this->load->helper("download");
        force_download($namafile, $isifile);
    }

    function posp_add($no_po = null) {
        $this->auth->validate_authen('purchasing/posp_add');
        $data = array();
        if ($this->input->get("n")) {
            $param = array(
                'no_po' => base64_decode(urldecode($this->input->get("n")))
            );
            $data['po'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));

            $param["jointable"] = array(array("MASTER_PART MP", "MP.PART_NUMBER=TRANS_POSP_DETAIL.PART_NUMBER", "LEFT"));
            $param["field"] = "TRANS_POSP_DETAIL.*,MP.PART_DESKRIPSI,MP.HET";

            $data['podetail'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp_detail", $param));
            //get aproval level user login
            $param = array(
                'user_id' => $this->session->userdata("user_id"),
                'kd_doc' => 'PO2SP'
            );
            $data["approvale"] = json_decode($this->curl->simple_get(API_URL . "/api/login/users_app", $param));
        }
        if ($this->input->get("so")) {
            $param = array(
                "no_trans" => base64_decode(urldecode($this->input->get("so"))),
                "kd_dealer" => $this->session->userdata("kd_dealer")
            );
            //$param["no_trans"]= base64_decode(urldecode($this->input->get("n")));
            $data["soh"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso", $param));
            $param = array(
                "no_trans" => base64_decode(urldecode($this->input->get("so")))
            );
            $data["suc"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/sopart_customer/true", $param));

            $param["jointable"] = array(array("MASTER_PART P", "P.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER", "LEFT"));
            $param["field"] = "TRANS_PARTSO_DETAIL.*,P.PART_DESKRIPSI";
            $data["sod"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso_detail", $param));
        }
        $params = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == "Root") {
            $params = array();
        }
        $data['dealer'] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $params));
        $this->template->site('purchasing/pospadd', $data);
    }

    function posp_fix() {
        $param = array(
            'kd_dealer' => $this->input->get("kd_dealer"),
            'tahun' => $this->input->get("tahun"),
            'bulan' => $this->input->get("bulan"),
            'jenis_po' => 'Fix'
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        if ($data) {
            echo json_encode($data);
        }
    }

    function posp_suggest($rankpart = null, $onlyData = null) {
        $data = array();
        $param = array();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'kd_maindealer' => $this->session->userdata("kd_maindealer"),
            //'tgl_trans' =>($this->input->get('tgl_trans'))?tglToSql($this->input->get('tgl_trans')):date('Ymd',strtotime("-1 Days")),
            'rpt' => ($rankpart == true) ? 'RP' : 'SGS',
            'jointable' => array(array("MASTER_PART M", "M.PART_NUMBER=TRANS_ADJ_SGSORDER.PART_NUMBER", "LEFT")),
            'field' => "TRANS_ADJ_SGSORDER.*,((M.HET*10/100)) AS PEPEEN,M.PART_DESKRIPSI,M.HET",
            'custom' => "NO_TRANS IN(SELECT TOP 1 NO_TRANS FROM TRANS_ADJ_SGSORDER WHERE ROW_STATUS >=0 GROUP BY NO_TRANS ORDER BY NO_TRANS DESC)"
        );
        if ($this->input->get("n")) {
            $param = array("no_trans" => $this->input->get("no_trans"));
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/adj_sgsorder", $param));
        } else {
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/adj_sgsorder", $param));
            if ($data["list"]) {
                if ($data["list"]->totaldata == 0) {
                    $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/rankparts", $param));
                }
            }
        }

        if ($onlyData == true) {
            if ($data["list"]) {
                if ($data["list"]->totaldata > 0) {
                    echo json_encode($data["list"]->message);
                    exit();
                }
            }
        } else {
            if ($rankpart == true) {
                $this->template->site('purchasing/rank_parts', $data);
            } else {
                $this->template->site('purchasing/suggested_order', $data);
            }
        }
    }

    function rankparts() {
        $this->posp_suggest(true);
    }

    function simpan_suggest() {
        $details = json_decode($this->input->post('data'), true);
        if (count($details) > 0) {
            for ($i = 0; $i < count($details); $i++) {
                $param = array();
                $param = $details[$i];
                $param["created_by"] = $this->session->userdata("user_id");
                $hasil = json_decode($this->curl->simple_post(API_URL . "/api/inventori/adj_sgsorder", $param, array(CURLOPT_BUFFERSIZE => 10)));
                if ($hasil) {
                    if ($hasil->recordexists == true) {
                        $param["lastmodified_by"] = $this->session->userdata("user_id");
                        $hasil = json_decode($this->curl->simple_put(API_URL . "/api/inventori/adj_sgsorder", $param, array(CURLOPT_BUFFERSIZE => 10)));
                    }
                }
            }
        }
        $data = json_encode($hasil);
        $this->data_output($data, 'post_batch', $param["no_trans"]);
    }

    function posp_simpan() {
        $no_po = ($this->input->post('no_po')) ? $this->input->post('no_po') : $this->getnopo();
        $pdata = json_decode($this->input->post("detail"), true);
        $param = array();
        $param["kd_maindealer"] = $this->session->userdata("kd_maindealer");
        $param["kd_dealer"] = ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer");
        $param["no_po"] = $no_po;
        $param["tgl_po"] = ($this->input->post('tgl_po'));
        $param["jenis_po"] = $this->input->post('jenis_order');
        $param["nama_konsumen"] = $this->input->post('nama_konsumen');
        $param["kd_konsumen"] = $this->input->post('kd_konsumen');
        $param["alamat_konsumen"] = $this->input->post('alamat_konsumen');
        $param["kota_konsumen"] = $this->input->post('kota_konsumen');
        $param["kode_pos"] = $this->input->post('kd_pos');
        ;
        $param["no_telp"] = $this->input->post('no_telp');
        $param["type_motor"] = $this->input->post('kd_typemotor');
        $param["tahun_motor"] = $this->input->post('thn_motor');
        $param["vor"] = $this->input->post('vor');
        $param["jsr"] = $this->input->post('jr');
        $param["lso"] = $this->input->post('lso');
        $param["reff_no"] = $this->input->post('no_reff');
        $param["bulan"] = $this->input->post('bulan_kirim');
        $param["tahun"] = $this->input->post('tahun_kirim');
        $param["created_by"] = $this->session->userdata("user_id");

        $hasil = json_decode($this->curl->simple_post(API_URL . "/api/purchasing/posp", $param, array(CURLOPT_BUFFERSIZE => 100)));
        if ($hasil->recordexists == TRUE) {
            $param["lastmodified_by"] = $this->session->userdata("user_id");
            $hasil = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/posp", $param, array(CURLOPT_BUFFERSIZE => 100)));
        }
        //var_dump($hasil);
        if ($hasil) {
            if ($this->input->post("jenis_order") == 'NRFS') {

                $mutasi = $this->updateStatusMutasi($param["reff_no"]);
            }
            // if($hasil->message > 0){
            $detail = $this->posp_simpandetail($no_po, $pdata);
            //update sales order
            $sodata = $this->posp_updateSO($no_po, $param["reff_no"]);
        }
        $data = json_encode($hasil);
        //var_dump($data);
        $this->data_output($data, 'post', base_url('purchasing/posp_add?n=' . urlencode(base64_encode($no_po))));
    }

    function updateStatusMutasi($no_trans) {
        $param = array(
            'no_trans' => $no_trans,
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = ($this->curl->simple_get(API_URL . "/api/inventori/update_mutasi_nrfs", $param));
        // $this->data_output($hasil, 'put');
        return $hasil;
    }

    function posp_updateSO($nopo, $no_so) {
        $param = array(
            'nopo' => $nopo,
            'noso' => $no_so,
            'lastmodified_by' => $this->session->userdata("user_id")
        );
        $hasil = ($this->curl->simple_put(API_URL . "/api/inventori/partso_updpo", $param));
        // $this->data_output($hasil, 'put');
        return $hasil;
    }

    function posp_simpandetail($no_po, $detail = null) {
        $hasil = [];
        if ($detail) {
            $paramx = [];
            for ($i = 0; $i < count($detail); $i++) {
                $param = array(
                    "no_po" => $no_po,
                    "part_number" => $detail[$i]['part_number'],
                    "jumlah" => ($detail[$i]['jumlah']) ? $detail[$i]['jumlah'] : "0",
                    "harga" => ($detail[$i]['harga']) ? $detail[$i]['harga'] : "0",
                    "po_status" => "0",
                    "ppn" => ($detail[$i]["ppn"]) ? $detail[$i]["ppn"] : "0",
                    "created_by" => $this->session->userdata("user_id")
                );
                $paramx[] = $param;
                $hasil = json_decode($this->curl->simple_post(API_URL . "/api/purchasing/posp_detail", $param, array(CURLOPT_BUFFERSIZE => 100)));
                if ($hasil->recordexists == TRUE) {
                    $param["lastmodified_by"] = $this->session->userdata("user_id");
                    $hasil = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/posp_detail", $param, array(CURLOPT_BUFFERSIZE => 100)));
                }
            }
        }
        return $hasil;
    }

    function posp_hpsdetail() {
        $param = array("id" => $this->input->get('id'));
        $hasil = json_decode($this->curl->simple_delete(API_URL . "/api/purchasing/posp_detail", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->output->set_output(json_encode($hasil));
    }

    function posp_hps() {
        $param = array("id" => $this->input->get('id'));
        $hasil = json_decode($this->curl->simple_delete(API_URL . "/api/purchasing/posp", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->output->set_output(json_encode($hasil));
    }

    function posp_approve($mode = null) {
        $param = array();
        $param["kd_dealer"] = ($this->input->post('kd_dealer')) ? $this->input->post('kd_dealer') : $this->session->userdata("kd_dealer");
        $param["no_po"] = $this->input->post('no_po');
        $param["created_by"] = $this->session->userdata("user_id");
        $param["approval"] = ($mode == '2') ? "-1" : "1";
        $param["keterangan"] = $this->input->post("alasan");

        $hasil = json_decode($this->curl->simple_put(API_URL . "/api/purchasing/posp_approval", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $data = json_encode($hasil);
        $this->data_output($data, 'post', base_url('purchasing/posp_add?n=' . urlencode(base64_encode($param["no_po"]))));
        //$this->output->set_output(json_encode($hasil));
    }

    function getMutasiNRFS() {
        $result = array();

        $param = array(
            'kd_dealer' => $this->input->get("d"),
            'jointable' => array(
                array("TRANS_TERIMASJMOTOR AS TTS", "TTS.NO_MESIN = TRANS_INV_MUTASI.PART_NUMBER", "LEFT"),
                array("TRANS_SJMASUK AS TSJ", "TSJ.NO_MESIN = TRANS_INV_MUTASI.PART_NUMBER", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MP", "MP.KD_ITEM = TTS.KD_ITEM", "LEFT"),
            ),
            'field' => "TRANS_INV_MUTASI.ID,TRANS_INV_MUTASI.NO_TRANS,MP.KD_TYPEMOTOR, MP.NAMA_TYPEMOTOR,TSJ.THN_PERAKITAN",
            'custom' => "TRANS_INV_MUTASI.JENIS_TRANS ='Status Unit'"
        );
        //$data=json_decode($this->curl->simple_get(API_URL . "/api/inventori/terimamotor", $param));
        $data = json_decode($this->curl->simple_get(API_URL . "/api/inventori/inv_mutasi", $param));

        //print_r($data);die();
        if ($data) {
            if ($data->totaldata > 0) {
                $result = $data->message;
            }
        }
        echo json_encode($result);
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
                        'location' => base_url('purchasing/add_po?n=' . $output_custom)
                    );
                    $this->session->unset_userdata("podetail");
                    //session_destroy();
                } else {
                    $result = $hasil;
                }
                $this->output->set_output(json_encode($result));
                break;
            case 'post_batch':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan",
                        'nodoc' => $output_custom,
                        'location' => $output_custom
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
                        'location' => base_url('purchasing/PO_list')
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
                        'nodoc' => $output_custom,
                        'location' => base_url('purchasing/add_po?n=' . $output_custom)
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

    function cek_minmaxpo() {
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'row_status' => 0,
            'kd_typemotor' => $this->input->get('item'),
            'jenis_po' => $this->input->get('jenis_po'),
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/setup/qtypo", $param));
        echo json_encode($data->message);
    }

    function lastpoitemquantity() {
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer'),
            'row_status' => 0,
            'bulan_kirim' => $this->input->get('bulan_kirim'),
            'tahun_kirim' => $this->input->get('tahun_kirim'),
            'custom' => " CONCAT(TRANS_PO2MD_DETAIL.KD_TYPEMOTOR,'-',TRANS_PO2MD_DETAIL.KD_WARNA) = '" . $this->input->get('item') . "'",
            'jointable' => array(
                array("TRANS_PO2MD_DETAIL", "TRANS_PO2MD_DETAIL.ID_PO = TRANS_PO2MD.ID", "LEFT")
            ),
            'field' => "TRANS_PO2MD_DETAIL.T1_QTY, TRANS_PO2MD_DETAIL.T2_QTY"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/po", $param));
        echo json_encode($data->message);
    }

}
