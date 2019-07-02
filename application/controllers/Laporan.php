<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends CI_Controller {
    var $API;
    public function __construct() {
        parent::__construct();
        //API_URL=str_replace('frontend/','backend/',base_url())."index.php";
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('dompdf_gen');
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
     * [succes_rate description]
     * @return [type] [description]
     */
    public function succes_rate() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";

        $param = array(
            'keyword' => $this->input->get('keyword'),
            //'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => $this->input->get('row_status'),
            'status' => ($this->input->get('page') == null) ? 'Deal' : $this->input->get('status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("MASTER_P_GROUPMOTOR MP", "MP.KD_GROUPMOTOR=TRANS_GUESTBOOK_VIEW.KD_TYPEMOTOR", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS M", "M.KD_WARNA=TRANS_GUESTBOOK_VIEW.KD_WARNA", "LEFT", 'KD_WARNA,KET_WARNA')
            ),
            'status' => 'Deal',
            'limit' => '*',
            "custom" => "TRANS_GUESTBOOK_VIEW.STATUS='" . ($this->session->userdata('status') == 'Deal') . "'"
        );

        if ($this->input->get("kd_dealer") != null) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        }elseif($this->session->userdata('kd_dealer')){
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }else{
            $param["kd_dealer"] = '0098';
        }

        //var_dump($param["kd_dealer"]);exit;

        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $grouping = $this->input->get("pilih");
        $params = array();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        switch ($grouping) {
            case '1':
                $html = "";
                $params["field"] = "CATEGORY_MOTOR";
                $params["groupby"] = TRUE;
                $params["orderby"] = "CATEGORY_MOTOR";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["category"] = $value->CATEGORY_MOTOR;
                            $html .= "<tr class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->CATEGORY_MOTOR) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-right'>$n</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->SPK_NO . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_SALES . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->TANGGAL . "</td>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                //var_dump($data);
                break;
            case '2':
                #Honda ID
                $html = "";
                $params["field"] = "KD_SALES,NAMA_SALES";
                $params["groupby"] = TRUE;
                $params["orderby"] = "KD_SALES";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["kd_sales"] = $value->KD_SALES;
                            $html .= "<tr class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->KD_SALES) . "[" . $value->NAMA_SALES . "]" . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-right'>$n</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->SPK_NO . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_SALES . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->TANGGAL . "</td>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                break;
            default:
                $html = "";
                $n = 0;
                $param["field"] = "TRANS_GUESTBOOK_VIEW.*";
                /* print_r($param);
                  exit(); */
                $datax = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
                if ($datax) {
                    if ($datax->totaldata > 0) {
                        foreach ($datax->message as $key => $value) {
                            $n++;
                            $html .= "<tr><td class='text-left'>$n</td>";
                            $html .= "<td class='text-left'>" . $value->KD_CUSTOMER . "</td>";
                            $html .= "<td class='text-left'>" . $value->NAMA_CUSTOMER . "</td>";
                            $html .= "<td class='text-left'>" . $value->SPK_NO . "</td>";
                            $html .= "<td class='text-left'>" . $value->KD_TYPEMOTOR . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_SALES . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->TANGGAL . "</td>";
                        }
                        $totaldata = $datax->totaldata;
                    }
                }
                break;
        }
        $data["html"] = $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0], // base_url().'report/sales?keyword='.$param['keyword'].'&row_status='.$param['row_status'],
            'total_rows' => $totaldata
        );
        $data["totaldata"] = $totaldata;
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/succes_rate/view', $data);
    }
    public function succes_rate_print() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'status' => ($this->input->get('page') == null) ? 'Deal' : $this->input->get('status'),
            //'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                /* array("TRANS_SPK_VIEW TS", "TS.NO_SPK=TRANS_GUESTBOOK_VIEW.SPK_NO", "LEFT"), */
                array("MASTER_P_GROUPMOTOR MP", "MP.KD_GROUPMOTOR=TRANS_GUESTBOOK_VIEW.KD_TYPEMOTOR", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS M", "M.KD_WARNA=TRANS_GUESTBOOK_VIEW.KD_WARNA", "LEFT", 'KD_WARNA,KET_WARNA')
            ),
            'status' => 'Deal',
            'limit' => 15,
            "custom" => "TRANS_GUESTBOOK_VIEW.STATUS='" . ($this->session->userdata('status') == 'Deal') . "'"
                /* "custom" => "TRANS_GUESTBOOK_VIEW.STATUS.STATUS='".$this->session->userdata('status') == 'Deal')."'" */
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $grouping = $this->input->get("pilih");
        $params = array();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        $grouping = $this->input->get("pilih");
        $params = array();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        switch ($grouping) {
            case '1':
                $html = "";
                $params["field"] = "CATEGORY_MOTOR";
                $params["groupby"] = TRUE;
                $params["orderby"] = "CATEGORY_MOTOR";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["category"] = $value->CATEGORY_MOTOR;
                            $html .= "<tr style='border-bottom: 1px solid;' class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->CATEGORY_MOTOR) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-left'>$n</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->SPK_NO . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NAMA_SALES . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->TANGGAL . "</td>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                    }
                }
                //var_dump($data);
                break;
            case '2':
                #Honda ID
                $html = "";
                $params["field"] = "KD_SALES,NAMA_SALES";
                $params["groupby"] = TRUE;
                $params["orderby"] = "KD_SALES";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["kd_sales"] = $value->KD_SALES;
                            $html .= "<tr style='border-bottom: 1px solid;' class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->KD_SALES) . "[" . $value->NAMA_SALES . "]" . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-left'>$n</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-left'>" . $value->SPK_NO . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NAMA_SALES . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->TANGGAL . "</td>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                    }
                }
                break;
            default:
                $html = "";
                $n = 0;
                $param["field"] = "TRANS_GUESTBOOK_VIEW.*";
                /* print_r($param);
                  exit(); */
                $datax = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
                if ($datax) {
                    if ($datax->totaldata > 0) {
                        foreach ($datax->message as $key => $value) {
                            $n++;
                            $html .= "<tr><td class='text-left'>$n</td>";
                            $html .= "<td class='text-left'>" . $value->KD_CUSTOMER . "</td>";
                            $html .= "<td class='text-left'>" . $value->NAMA_CUSTOMER . "</td>";
                            $html .= "<td class='text-left'>" . $value->SPK_NO . "</td>";
                            $html .= "<td class='text-left'>" . $value->KD_TYPEMOTOR . "</td>";
                            $html .= "<td class='text-left'>" . $value->KET_WARNA . "</td>";
                            $html .= "<td class='text-left'>" . $value->NAMA_SALES . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->TANGGAL . "</td>";
                        }
                        $totaldata = $datax->totaldata;
                    }
                }
                $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                break;
        }
        $data["html"] = $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $data["totaldata"] = $totaldata;
        $this->load->view('report/succes_rate/print', $data);
        $html = $this->output->get_output();
//
        $this->output->set_output(json_encode($html));
    }
    public function data_stock($print=null) {
        $data = array();
        $totaldata = 0;
        $data['totaldata'] = '';
        $datax = array();
        $data['html'] = '';
        $html = "";
        $newParam = array();
        $defaultDealer =($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata('kd_dealer');
        $offset =($this->input->get('page') ) ?  $this->input->get('page'):'0';
        $params = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') ) ? $this->input->get('page'):'0',
            'kd_dealer' => $defaultDealer
        );
        $grouping = ($this->input->get("pilih"))?$this->input->get("pilih"):"";
        // print_r($params);exit();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        switch ($grouping) {
            case '1':
                $html = "";
                $params = array(
                    'field' => "KD_DEALER,ISNULL(KD_GUDANG,'OTHERS') AS KD_GUDANG,SUM(STOCK_AKHIR) STOCK_AKHIR",
                    'groupby_text' => 'KD_DEALER,KD_GUDANG',
                    'orderby' => "KD_DEALER,KD_GUDANG",
                    'kd_dealer' => $defaultDealer,
                    'custom' => "STOCK_AKHIR >0"
                );
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $params));
                // var_dump($list->param);exit();
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $paramx = array();
                            $paramx["kd_dealer"] = $value->KD_DEALER;
                            $paramx["limit"] ="15";
                            $paramx["offset"] = $offset;
                            if($print){
                                unset($paramx["limit"]);
                            }
                            $paramx["custom"] = ($value->KD_GUDANG == 'OTHERS') ? " KD_GUDANG IS NULL" : " KD_GUDANG='" . $value->KD_GUDANG . "'";
                            $paramx["custom"] .= " AND STOCK_AKHIR >0 ";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $paramx));
                            $html .= "<tr id='l_" . $nom . "' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
                            $html .= "<td colspan='2'> GUDANG " . strtoupper($value->KD_GUDANG) . "</td>";
                            $html .= "<td class='text-center'>" . number_format($value->STOCK_AKHIR, 0) . "</td>";
                            $html .= "<td colspan='6'>&nbsp;</td>";
                            $html .= "</tr>";
                            //var_dump($paramx);exit();
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $status = "";
                                        $jumlah = 0;
                                        $title = "";
                                        if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                            $status = "BOOKED";
                                            $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                            $title = "Sudah di booking untuk di anter ke customer";
                                        } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                            $satus = "NRFS";
                                            $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                            $title = "Not Ready To Sales - Unit tidak layak jual";
                                        } else {
                                            $status = "RFS";
                                            $jumlah = $value->BELI;
                                            $title = "Ready To Sales";
                                        }
                                        $n++;
                                        $sembunyi =($print)?"":" hidden";
                                        $html .= "<tr class='l_" . $nom . $sembunyi."'><td class='text-right'>".($offset+$n)."</td>";
                                        $html .= "<td class='text-center table-nowarp' style='white-space:nowrap'>" . $value->KD_ITEM . "</td>";
                                        $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->NAMA_ITEM . "</td>";
                                        $html .= "<td class='text-center'>" . $value->STOCK_AKHIR . "</td>";
                                        $html .= "<td class='text-center' style='white-space:nowrap'>" . $value->NO_RANGKA . "</td>";
                                        $html .= "<td class='text-center' style='white-space:nowrap'>" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_GUDANG . "</td>";
                                        $html .= "<td class='text-center'>" . $status . "</td>";
                                        $html .= "</tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                break;
            case '2':
                $html = "";
                $params = array(
                    'field' => "KD_DEALER,NRFS,CASE WHEN NRFS >0 THEN 'NOT READY FOR SALES (NRFS)' ELSE 'READY FOR SALES' END AS STOCK_STATUS,SUM(STOCK_AKHIR) STOCK_AKHIR",
                    'groupby_text' => 'KD_DEALER,NRFS',
                    'orderby' => "KD_DEALER",
                    'kd_dealer' => $defaultDealer,
                    'custom' => "STOCK_AKHIR >0"
                );
                
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $params));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $paramx = array();
                            $paramx["kd_dealer"] = $value->KD_DEALER;
                            $paramx["limit"] ="15";
                            $paramx["offset"] = $offset;
                            if($print){
                                unset($paramx["limit"]);
                            }
                            $paramx["custom"] = "NRFS='" . $value->NRFS . "'";
                            $paramx["custom"] .= " AND STOCK_AKHIR >0 ";

                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $paramx));
                            
                            $html .= "<tr id='l_" . $nom . "' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
                            $html .= "<td colspan='2'>" . strtoupper($value->STOCK_STATUS) . "</td>";
                            $html .= "<td class='text-center'>" . number_format($value->STOCK_AKHIR, 0) . "</td>";
                            $html .= "<td colspan='6'>&nbsp;</td>";
                            $html .= "</tr>";
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $status = "";
                                        $jumlah = 0;
                                        $title = "";
                                        if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                            $status = "BOOKED";
                                            $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                            $title = "Sudah di booking untuk di anter ke customer";
                                        } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                            $satus = "NRFS";
                                            $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                            $title = "Not Ready To Sales - Unit tidak layak jual";
                                        } else {
                                            $status = "RFS";
                                            $jumlah = $value->BELI;
                                            $title = "Ready To Sales";
                                        }
                                        $n++;
                                        $sembunyi =($print)?"":" hidden";
                                        $html .= "<tr class='l_" . $nom . $sembunyi."'><td class='text-right'>".($offset+$n)."</td>";
                                        $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->KD_ITEM . "</td>";
                                        $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->NAMA_ITEM . "</td>";
                                        $html .= "<td class='text-center'>" . $value->STOCK_AKHIR . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NO_RANGKA . "</td>";
                                        $html .= "<td class='text-left' style='white-space:nowrap'>" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-left' style='white-space:nowrap'>" . $value->KD_GUDANG . "</td>";
                                        $html .= "<td class='text-left'>" . $status . "</td>";
                                        $html .= "</tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                break;
            case '3':
                # Tipe Motor
                $html = "";
                $params = array(
                    'field' => "KD_DEALER,CASE WHEN LEN(ISNULL(SUB_KATEGORI,''))>0 THEN SUB_KATEGORI ELSE 'OTHERS' END SUB_KATEGORI,SUM(STOCK_AKHIR) STOCK_AKHIR",
                    'groupby_text' => 'KD_DEALER,SUB_KATEGORI',
                    'orderby' => "KD_DEALER,SUB_KATEGORI",
                    'kd_dealer' => $defaultDealer,
                    'custom'    => "STOCK_AKHIR >0"
                );
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $params));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $paramx = array();
                            $paramx["kd_dealer"] = $value->KD_DEALER;
                            $paramx["limit"] ="15";
                            //$paramx["offset"] = $offset;
                            if($print){
                                unset($paramx["limit"]);
                            }
                            $paramx["custom"] = ($value->SUB_KATEGORI == 'OTHERS') ? " LEN(ISNULL(SUB_KATEGORI,''))=0" : "SUB_KATEGORI='" . $value->SUB_KATEGORI . "'";;
                            $paramx["custom"] .= " AND STOCK_AKHIR >0 ";

                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $paramx));
                            $html .= "<tr id='l_" . $nom . "' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
                            $html .= "<td colspan='2'>" . strtoupper($value->SUB_KATEGORI) . "</td>";
                            //$html .= "<td>" . $value->SUB_KATEGORI . "</td>";
                            $html .= "<td class='text-right'>" . number_format($value->STOCK_AKHIR, 0) . "</td>";
                            $html .= "<td colspan='6'>&nbsp;</td>";
                            $html .= "</tr>";
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $status = "";
                                        $jumlah = 0;
                                        $title = "";
                                        if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                            $status = "BOOKED";
                                            $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                            $title = "Sudah di booking untuk di anter ke customer";
                                        } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                            $satus = "NRFS";
                                            $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                            $title = "Not Ready To Sales - Unit tidak layak jual";
                                        } else {
                                            $status = "RFS";
                                            $jumlah = $value->BELI;
                                            $title = "Ready To Sales";
                                        }
                                        $n++;
                                        $sembunyi =($print)?"":" hidden";
                                        $html .= "<tr class='l_" . $nom . $sembunyi."'><td class='text-right'>".($offset+$n)."</td>";
                                        $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->KD_ITEM . "</td>";
                                        $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->NAMA_ITEM . "</td>";
                                        $html .= "<td class='text-right'>" . $value->STOCK_AKHIR . "</td>";
                                        $html .= "<td class='text-left' style='white-space:nowrap'>" . $value->NO_RANGKA . "</td>";
                                        $html .= "<td class='text-left' style='white-space:nowrap'>" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_GUDANG . "</td>";
                                        $html .= "<td class='text-left'>" . $status . "</td>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                break;
           default:
                $html = "";
                $n = $this->input->get('page');
                $params["custom"]= "STOCK_AKHIR >0";
                $params["limit"]="15";
                $params["orderby"] ="KD_ITEM,NAMA_ITEM";
                if($print){
                    unset($params["limit"]);
                }
                $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $params));
                if ($datax) {
                    if ($datax->totaldata > 0) {
                        foreach ($datax->message as $key => $value) {
                            $status = "";
                            $jumlah = 0;
                            $title = "";
                            if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                $status = "BOOKED";
                                $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                $title = "Sudah di booking untuk di anter ke customer";
                            } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                $satus = "NRFS";
                                $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                $title = "Not Ready To Sales - Unit tidak layak jual";
                            } else {
                                $status = "RFS";
                                $jumlah = $value->STOCK_AKHIR;
                                $title = "Ready To Sales";
                            }
                            $n++;
                            $html .= "<tr><td class='text-center'>".($offset+$n)."</td>";
                            $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->KD_ITEM . "</td>";
                            $html .= "<td class='text-left table-nowarp' style='white-space:nowrap'>" . $value->NAMA_ITEM . "</td>";
                            $html .= "<td class='text-center'>" . $value->STOCK_AKHIR . "</td>";
                            $html .= "<td class='text-left' style='white-space:nowrap'>" . $value->NO_RANGKA . "</td>";
                            $html .= "<td class='text-left' style='white-space:nowrap'>" . $value->NO_MESIN . "</td>";
                            $html .= "<td class='text-center'>" . $value->KD_GUDANG . "</td>";
                            $html .= "<td class='text-left'>" . $status . "</td>";
                            $html .= "</tr>";
                        }
                        $totaldata += $datax->totaldata;
                    }
                }
                break;
        }
        // echo $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["html"] = $html;
        if($print){
            $this->load->view('report/data_stock/print', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }else{
            $string = explode('&page=', $_SERVER["REQUEST_URI"]);
            $config = array(
                    'per_page' => 15,
                    'base_url' => $string[0],
                    'total_rows' => $totaldata
                );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data["totaldata"] = $totaldata;
            $data['pagination'] =($grouping=='3')?'': $this->pagination->create_links();
            $this->template->site('report/data_stock/view', $data);
        }
    }
    public function data_stock_print() {
        $data['html'] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            "custom" => "STOCK_AKHIR >0",
            'orderby' => "TRANS_STOCKMOTOR.KD_ITEM"
        );
        $paramdetail = array(
            'jointable' => array(
                array("TRANS_SJMASUK AS TM", "TM.NO_MESIN=TRANS_STOCKMOTOR.NO_MESIN", "LEFT"),
                array("TRANS_TERIMASJMOTOR AS TJ", "TJ.NO_MESIN=TRANS_STOCKMOTOR.NO_MESIN AND TJ.NO_SJMASUK=TM.NO_SJMASUK AND TJ.ROW_STATUS>=0", "LEFT", "NO_MESIN,NO_SJMASUK,KD_GUDANG,TGL_TRANS,ROW_STATUS")
            ),
            'field' => "TRANS_STOCKMOTOR.KD_MAINDEALER,TRANS_STOCKMOTOR.KD_ITEM,TRANS_STOCKMOTOR.NAMA_ITEM,TRANS_STOCKMOTOR.NO_MESIN,
            TRANS_STOCKMOTOR.NO_RANGKA,SUM(BELI) BELI,SUM(RETUR)RETUR,SUM(BOOK)BOOK,SUM(BOOK_FULL)BOOK_FULL,SUM(DEL_PARSIAL)DEL_PAR,SUM(DEL_COMP)SALES,SUM(RETUR_MD)RETUR_MD, TM.TGL_SJMASUK,TJ.TGL_TRANS,TRANS_STOCKMOTOR.ROW_STATUS,TJ.KD_GUDANG",
            'groupby' => TRUE/* ,
                  'having'  => "((SUM(BELI)+SUM(RETUR)) - SUM(DEL_PARSIAL)-SUM(DEL_COMP)-SUM(RETUR_MD)) > 0 " */
        );
        if ($this->input->get("onlystock")) {
            //$paramdetail["having"] = "((SUM(BELI)+SUM(RETUR)) - SUM(DEL_PARSIAL)-SUM(DEL_COMP)-SUM(RETUR_MD)) >0";
        }
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $grouping = $this->input->get("pilih");
        $params = array();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        $grouping = $this->input->get("pilih");
        $params = array();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        switch ($grouping) {
            case '1':
                $html = "";
                $params = array(
                    'field' => "ISNULL(KD_GUDANG,'OTHERS') AS KD_GUDANG,SUM(STOCK_AKHIR) STOCK_AKHIR",
                    'groupby_text' => 'KD_GUDANG',
                    'orderby' => "KD_GUDANG"
                );
                unset($param["offset"]);
                unset($param["limit"]);
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $paramx = array();
                            $groupe = ($value->KD_GUDANG == 'OTHERS') ? "AND KD_GUDANG IS NULL" : "AND KD_GUDANG='" . $value->KD_GUDANG . "'";
                           $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", array_merge($param, $paramdetail)));
                            //print_r(array_merge($param,$paramx));var_dump($dataxs);exit();
                            $html .= "<tr id='l_" . $nom . "' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
                            $html .= "<td colspan='2'>" . strtoupper($value->KD_GUDANG) . "</td>";
                            $html .= "<td class='text-center'>" . number_format($value->STOCK_AKHIR, 0) . "</td>";
                            $html .= "<td colspan='6'>&nbsp;</td>";
                            $html .= "</tr>";
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $status = "";
                                        $jumlah = 0;
                                        $title = "";
                                        if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                            $status = "BOOKED";
                                            $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                            $title = "Sudah di booking untuk di anter ke customer";
                                        } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                            $satus = "NRFS";
                                            $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                            $title = "Not Ready To Sales - Unit tidak layak jual";
                                        } else {
                                            $status = "RFS";
                                            $jumlah = $value->BELI;
                                            $title = "Ready To Sales";
                                        }
                                        $n++;
                                        $html .= "<td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_ITEM . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_ITEM . "</td>";
                                        $html .= "<td class='text-center'>" . $jumlah . "</td>";
                                        $html .= "<td class='text-center'>" . $value->NO_RANGKA . "</td>";
                                        $html .= "<td class='text-center'>" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_GUDANG . "</td>";
                                        $html .= "<td class='text-center'>" . $status . "</td>";
                                        $html .= "</tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                    }
                }
                break;
            case '2':
                $html = "";
                $params = array(
                    'field' => "ISNULL(STOCK_STATUS,'OTHERS') AS STOCK_STATUS,SUM(STOCK_AKHIR) STOCK_AKHIR",
                    'groupby_text' => 'STOCK_STATUS',
                    'orderby' => "STOCK_STATUS"
                );
                unset($param["offset"]);
                unset($param["limit"]);
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $paramx = array();
                            $groupe = ($value->STOCK_STATUS == 'OTHERS') ? "AND STOCK_STATUS IS NULL" : "AND STOCK_STATUS='" . $value->STOCK_STATUS . "'";
                            $param["custom"] = "STOCK_AKHIR >0 " . $groupe;
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", array_merge($param, $paramdetail)));
                            //print_r(array_merge($param,$paramx));var_dump($dataxs);exit();
                            $html .= "<tr id='l_" . $nom . "' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
                            $html .= "<td colspan='2'>" . strtoupper($value->STOCK_STATUS) . "</td>";
                            $html .= "<td class='text-center'>" . number_format($value->STOCK_AKHIR, 0) . "</td>";
                            $html .= "<td colspan='6'>&nbsp;</td>";
                            $html .= "</tr>";
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $status = "";
                                        $jumlah = 0;
                                        $title = "";
                                        if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                            $status = "BOOKED";
                                            $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                            $title = "Sudah di booking untuk di anter ke customer";
                                        } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                            $satus = "NRFS";
                                            $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                            $title = "Not Ready To Sales - Unit tidak layak jual";
                                        } else {
                                            $status = "RFS";
                                            $jumlah = $value->BELI;
                                            $title = "Ready To Sales";
                                        }
                                        $n++;
                                        $html .= "<td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_ITEM . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_ITEM . "</td>";
                                        $html .= "<td class='text-center'>" . $jumlah . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NO_RANGKA . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_GUDANG . "</td>";
                                        $html .= "<td class='text-left'>" . $status . "</td>";
                                        $html .= "</tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                    }
                }
                break;
            case '3':
                # Tipe Motor
                $html = "";
                $params = array(
                    'field' => "ISNULL(KD_GROUPMOTOR,'OTHERS') AS KD_GROUPMOTOR,ISNULL(NAMA_GROUPMOTOR,'OTHERS')NAMA_GROUPMOTOR,SUM(STOCK_AKHIR) STOCK_AKHIR",
                    'groupby_text' => 'KD_GROUPMOTOR,NAMA_GROUPMOTOR,KD_GUDANG',
                    'orderby' => "KD_GROUPMOTOR"
                );
                /* if($this->input->get("kd_gudang")!="0"){
                  $params["custom"] = " KD_GUDANG='".$this->input->get("kd_gudang")."' AND STOCK_AKHIR > 0";
                  $params["groupby_text"]='KD_GROUPMOTOR,NAMA_GROUPMOTOR,KD_GUDANG';
                  }else{
                  $params["custom"]= "STOCK_AKHIR >0";
                  } */
                unset($param["offset"]);
                unset($param["limit"]);
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $paramx = array();
                            $groupe = ($value->KD_GROUPMOTOR == 'OTHERS') ? "AND KD_GROUPMOTOR IS NULL" : "AND KD_GROUPMOTOR='" . $value->KD_GROUPMOTOR . "'";
                            $param["custom"] = "STOCK_AKHIR >0 " . $groupe;
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", array_merge($param, $paramdetail)));
                            //print_r(array_merge($param,$paramx));var_dump($dataxs);exit();
                            $html .= "<tr id='l_" . $nom . "' class='info'><td class='text-center'>$nom <span class='pull-right'><i class='fa fa-chevron-down'></i></span></td>";
                            $html .= "<td colspan='0'>" . strtoupper($value->KD_GROUPMOTOR) . "</td>";
                            $html .= "<td>" . $value->NAMA_GROUPMOTOR . "</td>";
                            $html .= "<td class='text-center'>" . number_format($value->STOCK_AKHIR, 0) . "</td>";
                            $html .= "<td colspan='6'>&nbsp;</td>";
                            $html .= "</tr>";
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $status = "";
                                        $jumlah = 0;
                                        $title = "";
                                        if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                            $status = "BOOKED";
                                            $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                            $title = "Sudah di booking untuk di anter ke customer";
                                        } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                            $satus = "NRFS";
                                            $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                            $title = "Not Ready To Sales - Unit tidak layak jual";
                                        } else {
                                            $status = "RFS";
                                            $jumlah = $value->BELI;
                                            $title = "Ready To Sales";
                                        }
                                        $n++;
                                        $html .= "<td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_ITEM . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_ITEM . "</td>";
                                        $html .= "<td class='text-center'>" . $jumlah . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NO_RANGKA . "</td>";
                                        $html .= "<td class='text-left'>" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-left'>" . $value->KD_GUDANG . "</td>";
                                        $html .= "<td class='text-left'>" . $status . "</td>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                    }
                }
                break;
            default:
                $html = "";
                $n = $this->input->get('page');
                //$param["custom"]  = " STOCK_AKHIR >0 ";
                /* if($this->input->get("kd_gudang")!="0"){
                  $param["custom"] = " TRANS_STOCKMOTOR.KD_GUDANG='".$this->input->get("kd_gudang")."' AND STOCK_AKHIR > 0";
                  }else{
                  $param["custom"] = " STOCK_AKHIR > 0";
                  } */
                $params = array_merge($param, $paramdetail);
                $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor", $params));
                /* print_r($params);
                  var_dump($datax);exit(); */
                if ($datax) {
                    if ($datax->totaldata > 0) {
                        foreach ($datax->message as $key => $value) {
                            $status = "";
                            $jumlah = 0;
                            $title = "";
                            if ($value->BOOK_FULL > 0 || $value->BOOK > 0) {
                                $status = "BOOKED";
                                $jumlah = ($value->BOOK_FULL > 0) ? $value->BOOK_FULL : $value->BOOK;
                                $title = "Sudah di booking untuk di anter ke customer";
                            } else if ($value->RETUR > 0 || $value->RETUR_MD > 0) {
                                $satus = "NRFS";
                                $jumlah = ($value->RETUR > 0) ? $value->RETUR : $value->RETUR_MD;
                                $title = "Not Ready To Sales - Unit tidak layak jual";
                            } else {
                                $status = "RFS";
                                $jumlah = $value->BELI;
                                $title = "Ready To Sales";
                            }
                            $n++;
                            $html .= "<tr><td class='text-left'>$n</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->KD_ITEM . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_ITEM . "</td>";
                            $html .= "<td class='text-center'>" . $jumlah . "</td>";
                            $html .= "<td class='text-center'>" . $value->NO_RANGKA . "</td>";
                            $html .= "<td class='text-center'>" . $value->NO_MESIN . "</td>";
                            $html .= "<td class='text-center'>" . $value->KD_GUDANG . "</td>";
                            $html .= "<td class='text-center'>" . $status . "</td>";
                            $html .= "</tr>";
                        }
                        //$html .=$datax->totaldata;
                        $totaldata += $datax->totaldata;
                    }
                }
                $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                break;
        }
        // echo $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $data["html"] = $html;
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $data["totaldata"] = $totaldata;
        $this->load->view('report/data_stock/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /* public function data_stock_typeahead() {
      /* if($kd_dealer!=''){
      $param[""]
      }
      $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/stockmotor"));
      if ($data) {
      if (is_array($data["list"]->message)) {
      foreach ($data["list"]->message as $key => $message) {
      $data_message[0][$key] = $message->NO_MESIN;
      }
      $result['keyword'] = array_merge($data_message[0]);
      $this->output->set_output(json_encode($result));
      }
      }
      }
     */
    public function back_order_print() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') . "'",
            'limit' => 50,
            'field' => '*'
        );
        $params = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            /* 'custom' => "KD_DEALER='" . $this->session->userdata('kd_dealer') ."'", */
            'limit' => 50,
            'jointable' => array(
                array("MASTER_PART P", "P.PART_NUMBER=TRANS_PARTSO_DETAIL.PART_NUMBER", "LEFT")
            ),
            'field' => 'TRANS_PARTSO_DETAIL.*,P.PART_DESKRIPSI'
        );
        $parampo = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array("MASTER_PART P", "P.PART_NUMBER=TRANS_POSP_DETAIL.PART_NUMBER", "LEFT"),
                array("TRANS_POSP TP", "TP.NO_PO=TRANS_POSP_DETAIL.NO_PO", "LEFT")
            ),
            'field' => 'TRANS_POSP_DETAIL.*, P.PART_DESKRIPSI, TP.JENIS_PO'
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        /*
          $data = array();
          if ($this->input->get('pilih') == 1) {
          $data['pilih'] = 1;
          $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/partso_detail", $params));
          } else {
          $data['pilih'] = 0;
          $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp_detail", $parampo));
          } */
        $data = array();
        if ($this->input->get('pilih') == 1) {
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $this->load->view('report/back_order/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function lead_time() {
        $data = array();
        $result = null;
        $param = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m")
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        if ($this->input->get("tp") == "1") {
            $param["so"] = "true";
        }
        $result = ($this->curl->simple_get(API_URL . "/api/inventori/part_leadtime/true", $param));
        unset($param["so"]);
        $param['urutan'] = "2";
        $param['orderby'] = "NO_PO,TGL_PO";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/part_leadtime/", $param));
        $lstDetail = array();
        if ($data["list"]) {
            if ($data["list"]->totaldata > 0) {
                foreach ($data["list"]->message as $key => $value) {
                    unset($param["urutan"]);
                    $param["urutan"] = "1";
                    $param["no_po"] = $value->NO_PO;
                    $lstDetail[$value->NO_PO] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/part_leadtime", $param));
                }
            }
        }
        $param_area = array( 
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=USERS_AREA.KD_DEALER AND USERS_AREA.AUTH_STATUS > 0", "LEFT")
            ),
            "custom" => "USERS_AREA.USER_ID='".$this->session->userdata('user_id')."'"
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $data["dealer_area"] = json_decode($this->curl->simple_get(API_URL . "/api/menu/users_area", $param_area));
        $data["listd"] = $lstDetail;
        $param = array(
            'field' => "YEAR(TGL_PO) AS TAHUN",
            'groupby_text' => "YEAR(TGL_PO)",
            'orderby' => "YEAR(TGL_PO) DESC"
        );
        $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $deltmp = $this->curl->simple_get(API_URL . "/api/inventori/part_deltmp", $param);
        $this->template->site('report/lead_time/view', $data);
    }
    public function lead_time_print() {
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'custom' => "TRANS_POSP.KD_DEALER= '" . $this->session->userdata('kd_dealer') . "'",
            'jointable' => array(
                array("TRANS_PART_TERIMA TP", "TP.NO_PO=TRANS_POSP.NO_PO", "LEFT")
            ),
            'field' => 'TRANS_POSP.*, TP.TGL_TRANS'
        );
        $paramtrans = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*'
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = "CONVERT(CHAR, APPROVAL_DATE, 112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["cutom"] = "CONVERTT(CHAR, APPROVAL_DATE, 112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data = array();
        if ($this->input->get('pilih') == 1) {
            $data['[pilih'] = 1;
            $data['list'] = json_decode($this->curl->simple_get(API_URL . "/api/cashier/listsop"));
        } else {
            $data['pilih'] = 0;
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $this->load->view('report/lead_time/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function reporting_data() {
        $data = array();
        $result = null;
        $data["totaldata"] = "";
        $bln = ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m");
        $thn = ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y");
        //$kd_dealer = $this->input->get('kd_dealer') ? "TRANS_LEADTIME_UNIT.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_LEADTIME_UNIT.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $param = array(
            //'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            //'tahun' => ($this->input->get("tahun"))?$this->input->get("tahun"):date("Y"),
            //'bulan' => ($this->input->get("bulan"))?$this->input->get("bulan"):date("m"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*'
        );

        if ($this->input->get("kd_dealer") != null) {
            $kd_dealer = $this->input->get('kd_dealer');
        }elseif($this->session->userdata('kd_dealer')){
            $kd_dealer = $this->session->userdata('kd_dealer');
        }else{
            $kd_dealer = '0098';
        }

        //var_dump($param["kd_dealer"]);exit;

        $param["custom"] = "MONTH(TGL_SPK)='" . $bln . "' AND YEAR(TGL_SPK)='" . $thn . "' AND KD_DEALER='". $kd_dealer."'";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/reporting_data", $param));
        $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
       /* if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }*/
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' =>  ($data["list"]) ? $data["list"]->totaldata : 0
        );
        //$data["totaldata"] = $totaldata;
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
         // print_r($data["list"]);exit();
         $this->template->site('report/lead_time/reporting_data', $data);
     }
    public function monitoring_etd_eta() {
        $data = array();
        $result = null;
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array("MASTER_PART AS MP ", "TRANS_ETDETA_SO.PART_NUMBER=MP.PART_NUMBER", "LEFT")
            ),
            'field' => '*'
        );
        $param_area = array( 
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=USERS_AREA.KD_DEALER AND USERS_AREA.AUTH_STATUS > 0", "LEFT")
            ),
            "custom" => "USERS_AREA.USER_ID='".$this->session->userdata('user_id')."'"
        );
        $data["dealer_area"] = json_decode($this->curl->simple_get(API_URL . "/api/menu/users_area", $param_area));
        $param["custom"] = "MONTH(TGL_TRANS)='" . $this->input->get('bulan') . "' AND YEAR(TGL_TRANS)='" . $this->input->get("tahun") . "'";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/monitoring_etdeta", $param));
        $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' =>  ($data["list"]) ? $data["list"]->totaldata : 0
        );
        //$data["totaldata"] = $totaldata;
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        // print_r($data["list"]);exit();
         $this->template->site('report/monitoring_etd_eta', $data);
     }
    /**
     * [monitoring_etd_eta description]
     * @return [type] [description]
     */
    public function monitoring_etd_eta_typeahead($part_number = '') {
        /* if($kd_dealer!=''){
          $param[""]
          } */
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/monitoring_etdeta"));
        if ($data) {
            if (is_array($data["list"]->message)) {
                foreach ($data["list"]->message as $key => $message) {
                    $data_message[0][$key] = $message->PART_NUMBER;
                    $data_message[1][$key] = $message->NO_TRANS;
                }
                $result['keyword'] = array_merge($data_message[0], $data_message[1]);
                $this->output->set_output(json_encode($result));
            }
        }
    }
    public function back_order() {
        $data = array();
        $result = null;
        $param = array(
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m")
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        $result = ($this->curl->simple_get(API_URL . "/api/inventori/part_ots/true", $param));
        // var_dump($result);print_r($param);exit();
        if ($this->input->get("tp") == "1") {
            $param["tipe_rpt"] = "SO";
        } else {
            $param["tipe_rpt"] = "PO";
        }   
        $param_area = array( 
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=USERS_AREA.KD_DEALER AND USERS_AREA.AUTH_STATUS > 0", "LEFT")
            ),
            "custom" => "USERS_AREA.USER_ID='".$this->session->userdata('user_id')."'"
        );
        $data["dealer_area"] = json_decode($this->curl->simple_get(API_URL . "/api/menu/users_area", $param_area));
        $param['orderby'] = "PART_NUMBER";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/part_ots/", $param));
        //var_dump($data["list"]);print_r($param);exit();
        $param = array(
            'field' => "YEAR(TGL_PO) AS TAHUN",
            'groupby_text' => "YEAR(TGL_PO)",
            'orderby' => "YEAR(TGL_PO) DESC"
        );
        $data['tahun'] = json_decode($this->curl->simple_get(API_URL . "/api/purchasing/posp", $param));
        $deltmp = $this->curl->simple_get(API_URL . "/api/inventori/part_delots", $param);
        $this->template->site('report/back_order/view', $data);
    }
    public function udbyb() {
        $data = array();
        $param = array(
            //'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer"). "' AND convert(char, TANGGAL_PKB,112) =  '" . tglToSql($this->input->get("tanggal_pkb")) . "'",
            //'tanggal' => $this->input->get('tanggal'),
            //'kd_dealer' => $this->input->get('kd_dealer'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("TRANS_STNK AS SD", "SD.ID=TRANS_STNK_DETAIL.STNK_ID AND SD.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_KABUPATEN AS K", "K.KD_KABUPATEN=TRANS_STNK_DETAIL.KD_KOTA AND K.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN AS SDK","SDK.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SDK.ROW_STATUS >= 0","LEFT"),
                array("TRANS_SPK AS SP","SP.ID=SDK.SPK_ID AND SP.ROW_STATUS >= 0","LEFT"),
                array("TRANS_SSU AS S","S.NO_MESIN=SDK.NO_MESIN AND S.ROW_STATUS >= 0 AND S.KD_DEALER=SP.KD_DEALER","LEFT","NO_MESIN,TGL_DOWNLOAD,KD_DEALER,ROW_STATUS"),
                array("TRANS_SJMASUK AS SJ","SJ.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SJ.ROW_STATUS >= 0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => 'TRANS_STNK_DETAIL.ID,TRANS_STNK_DETAIL.KD_DEALER,TRANS_STNK_DETAIL.KD_MESIN,TRANS_STNK_DETAIL.NO_MESIN,TRANS_STNK_DETAIL.NO_RANGKA,TRANS_STNK_DETAIL.BPKB,TRANS_STNK_DETAIL.STCK,TRANS_STNK_DETAIL.PLAT_ASLI,TRANS_STNK_DETAIL.ADMIN_SAMSAT, TRANS_STNK_DETAIL.NO_PENGAJUAN,TRANS_STNK_DETAIL.NAMA_PEMILIK,TRANS_STNK_DETAIL.KD_ITEM,TRANS_STNK_DETAIL.BIAYA_BPKB,S.TGL_DOWNLOAD,SD.TGL_STNK,K.NAMA_KABUPATEN, SP.FAKTUR_PENJUALAN, SJ.VNORANGKA1, SD.NO_TRANS',
            'limit' => 15,
            "custom" => "SD.NO_TRANS LIKE 'B%' AND S.TGL_DOWNLOAD != '' AND TRANS_STNK_DETAIL.KD_DEALER='".$this->session->userdata("kd_dealer")."' AND TRANS_STNK_DETAIL.NO_RANGKA NOT IN (SELECT TRANS_UDBYB.NO_RANGKA FROM TRANS_UDBYB)",
            'groupby' => TRUE
            //"custom" => "TRANS_STNK_BPKB.NO_RANGKA NOT IN TRANS_UDBYB.NO_RANGKA"
        );
        //print_r($param);
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param)); 
        //var_dump($data["list"]); exit();
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('master_service/udbyb/view', $data);
    }
    public function download_udbyb() {
        $param = array(
            //'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'jointable' => array(
                array("TRANS_STNK SD", "SD.ID=TRANS_STNK_DETAIL.STNK_ID AND SD.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_KABUPATEN K", "K.KD_KABUPATEN=TRANS_STNK_DETAIL.KD_KOTA AND K.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN SDK","SDK.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SDK.ROW_STATUS >= 0","LEFT"),
                array("TRANS_SPK AS SP","SP.ID=SDK.SPK_ID AND SP.ROW_STATUS >= 0","LEFT"),
                array("TRANS_SSU AS S","S.NO_MESIN=SDK.NO_MESIN AND S.ROW_STATUS >= 0 AND S.KD_DEALER=SP.KD_DEALER","LEFT","NO_MESIN,TGL_DOWNLOAD,KD_DEALER,ROW_STATUS"),
                array("TRANS_SJMASUK SJ","SJ.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SJ.ROW_STATUS >= 0","LEFT"),
                array("SETUP_SA_UNIT AS SAM","SAM.NO_RANGKA=TRANS_STNK_DETAIL.NO_RANGKA AND SAM.ROW_STATUS >= 0","LEFT")
            ),
            'field' => 'TRANS_STNK_DETAIL.ID,TRANS_STNK_DETAIL.KD_DEALER,TRANS_STNK_DETAIL.KD_MESIN,TRANS_STNK_DETAIL.NO_MESIN,TRANS_STNK_DETAIL.NO_RANGKA,TRANS_STNK_DETAIL.BPKB,TRANS_STNK_DETAIL.STCK,TRANS_STNK_DETAIL.PLAT_ASLI,TRANS_STNK_DETAIL.ADMIN_SAMSAT, TRANS_STNK_DETAIL.NO_PENGAJUAN,TRANS_STNK_DETAIL.NAMA_PEMILIK,TRANS_STNK_DETAIL.KD_ITEM,TRANS_STNK_DETAIL.BIAYA_BPKB,S.TGL_DOWNLOAD,SD.TGL_STNK,K.NAMA_KABUPATEN, SP.FAKTUR_PENJUALAN, SJ.VNORANGKA1, SD.NO_TRANS',
            "custom" => "SD.NO_TRANS LIKE 'B%' AND S.TGL_DOWNLOAD != '' AND TRANS_STNK_DETAIL.KD_DEALER='".$this->session->userdata("kd_dealer")."' AND TRANS_STNK_DETAIL.NO_RANGKA NOT IN (SELECT TRANS_UDBYB.NO_RANGKA FROM TRANS_UDBYB)",
            'groupby' => TRUE
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        //var_dump($data);exit;
        return $data;
    }
    public function createfile_udbyb() {
        $this->auth->validate_authen('laporan/udbyb');
        $data = array();
        $data = $this->download_udbyb();
        //var_dump($data);exit;
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $this->session->userdata("kd_maindealer"). "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".UDBYB"; //
                //$isifile .= $row->KD_MAINDEALER . ";";
                $isifile .= $row->KD_DEALER . ";";
                $isifile .= $row->NO_TRANS . ";";
                $isifile .= $row->NAMA_KABUPATEN . ";";
                $isifile .= str_replace("/", "-", tglFromSql($row->TGL_STNK)) . ";";
                $isifile .= str_replace("/", "-", tglFromSql($row->TGL_DOWNLOAD)) . ";";
                $isifile .= $row->FAKTUR_PENJUALAN . ";";
                $isifile .= $row->KD_MESIN .$row->NO_MESIN . ";";
                $isifile .= $row->NO_RANGKA . ";";
                $isifile .= $row->KD_ITEM . ";";
                $isifile .= $row->NAMA_PEMILIK . ";";
                $isifile .= str_replace(".00","", $row->BPKB) . ";";
                $isifile .= str_replace(".00","", $row->STCK) . ";";
                $isifile .= "0;";
                $isifile .= "0;";
                $isifile .= "0;";
                $isifile .= str_replace(".00","", $row->PLAT_ASLI) . ";";
                $isifile .= "0;";
                $isifile .= "0;";
                $isifile .= str_replace(".00","", $row->ADMIN_SAMSAT) . ";";
                $isifile .= "0;";
                $isifile .= "0;";
                $isifile .= str_replace(".00","", $row->BIAYA_BPKB). ";";
                $isifile .= $row->KD_DEALER . ";";
                $isifile .=  ";";
                $isifile .= $row->VNORANGKA1 . ";" . PHP_EOL;
                $param = array(
                'kd_dealer' => $row->KD_DEALER,
                'no_mhn' => $row->NO_TRANS,
                'nama_wilayah' => $row->NAMA_KABUPATEN,
                'tgl_mhn' => tglFromSql($row->TGL_STNK),
                'tgl_faktur' => tglFromSql($row->TGL_DOWNLOAD),
                'no_faktur' => $row->FAKTUR_PENJUALAN,
                'no_mesin' => $row->KD_MESIN.$row->NO_MESIN,
                'no_rangka' => $row->NO_RANGKA,
                'kd_item' => $row->KD_ITEM,
                'nama_konsumen' => $row->NAMA_PEMILIK,
                'bpkb' => $row->BPKB,
                'stck' => $row->STCK,
                'formulir' => 0,
                'sp3' => 0,
                'stnk' => 0,
                'plat_asli' => $row->PLAT_ASLI,
                'admin' => 0,
                'asuransi' => 0,
                'admin_samsat' => $row->ADMIN_SAMSAT,
                'leges_fak' => 0,
                'spkb' => 0,
                'biaya' => $row->BIAYA_BPKB,
                'kd_dealer2' => $row->KD_DEALER,
                'hash' => NULL,
                'keterangan_rangka' => $row->VNORANGKA1,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
                $hasil = $this->curl->simple_post(API_URL . "/api/laporan/udbyb", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        /*foreach ($data->message as $key => $row) {
            $param = array(
                'kd_dealer' => $row->KD_DEALER,
                'no_trans' => $row->NO_TRANS,
                'wilayah' => $row->NAMA_KABUPATEN,
                'tgl_mhn' => $row->TGL_STNK,
                'tgl_faktur' => $row->TGL_DOWNLOAD,
                'no_faktur' => $row->FAKTUR_PENJUALAN,
                'no_mesin' => $row->KD_MESIN.$row->NO_MESIN,
                'no_rangka' => $row->NO_RANGKA,
                'kd_item' => $row->KD_ITEM,
                'nama_konsumen' => $row->NAMA_PEMILIK,
                'bpkb' => $row->BPKB,
                'stck' => $row->STCK,
                'formulir' => 0,
                'sp3' => 0,
                'stnk' => 0,
                'plat_asli' => $row->PLAT_ASLI,
                'admin' => 0,
                'asuransi' => 0,
                'admin_samsat' => $row->ADMIN_SAMSAT,
                'leges_fak' => 0,
                'spkb' => 0,
                'biaya' => $row->BIAYA_BPKB,
                'kd_dealer2' => $row->KD_DEALER,
                'hash' => NULL,
                'keterangan_rangka' => $row->VNORANGKA1,
                'row_status' => 0,
                'created_by' => $this->session->userdata('user_id')
            )
        }*/
        //var_dump($param);exit;
        $this->load->helper("download");
        force_download($namafile, $isifile);
        /* dilanjukan ke field yang lainnya sesuai inputan sp_users_insert */
        $data = json_decode($hasil);
        $this->session->set_flashdata('tr-active', $data->message);
        $this->data_output($hasil, 'post', base_url('laporan/udbyb'));
    }
    public function stock_barang($debug=null) {
        $data = array();
        $param = array(
            // 'keyword' => $this->input->get('keyword'),
            'kd_dealer'=>($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata('kd_dealer')
        );
        if(!$this->input->get("p")){
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 15;
        }
        $param["d_tgl"] =($this->input->get("d_tgl"))?$this->input->get("d_tgl"):date("d/m/Y",strtotime("first day of this month"));
        $param["s_tgl"] =($this->input->get("s_tgl"))?$this->input->get("s_tgl"):date("d/m/Y");
        $param["j_trans"]=($this->input->get("tp"))?$this->input->get("tp"):"0";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang_stock", $param));   
        if($debug){
            print_r($param);
            var_dump($data["list"]);
            exit();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        if($this->input->get("p")){
            $data["j_trans"]     =   $param["j_trans"];
            $data["d_tgl"]  =   $param["d_tgl"];
            $data["s_tgl"]  =   $param["s_tgl"];
            $this->load->view('report/stock_barang/print', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }else{
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            $this->template->site('report/stock_barang/view', $data);
        }
    }
    public function stock_barang_print() {
        $data = array();
        $this->auth->validate_authen('laporan/stock_barang');
       $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'field' => "MASTER_BARANG.*",
            'limit' => 15   
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang", $param));
        $this->load->view('report/stock_barang/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function stock_barang_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang"));
        foreach ($data["list"]->message as $key => $message) {
            $data_message[0][$key] = $message->KD_BARANG;
            $data_message[1][$key] = $message->NAMA_BARANG;
        }
        $result['keyword'] = array_merge($data_message[0], $data_message[1]);
        $this->output->set_output(json_encode($result));
    }
    public function monitoring_report() {
        $data = array();
        $result = null;
        $paramcustomer = array();
        $param = array(
            'custom' => "convert(char, TANGGAL_PKB,112) =  '" . tglToSql($this->input->get("tanggal_pkb")) . "'",
            'tanggal' => $this->input->get('tanggal'),  
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array(
                array("TRANS_PKB_DETAIL AS TPD", "TPD.NO_PKB = TRANS_PKB.NO_PKB", "LEFT"),
                array("TRANS_CSA AS TC", "TC.KD_SA = TRANS_PKB.KD_SA", "LEFT")/*
                array("MASTER_JASA AS MJ", "MJ.KD_JASA = TPD.KD_PEKERJAAN", "LEFT")*/
            ),              
            'field' => "TRANS_PKB.*, TPD.KD_PEKERJAAN, TPD.KATEGORI, TC.KD_CUSTOMER, TC.KD_HONDA, TPD.QTY, 
                        CASE WHEN TPD.KATEGORI='Jasa' 
                        THEN (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END KETERANGAN",
            "orderby" => "TPD.KATEGORI asc",
            'limit' => 15
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR, TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . date('Ymd') . "'";
        }

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        
        $data["dealer"] =  json_decode($this->curl->simple_get(API_URL. "/api/master/dealer",listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/monitoring_report/view', $data);
    }
    public function download_daily_unit() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_PKB.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_PKB.KD_DEALER= '" . $this->session->userdata("kd_dealer") . "'";
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER= '" . $this->session->userdata("kd_dealer") . "' AND convert(char, TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal_pkb")) . "'",
            'keyword' => $this->input->get('keyword'),
            'tanggal_pkb' => $this->input->get('tanggal_pkb'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array( 
                array("TRANS_PKB_DETAIL AS TPD", "TPD.NO_PKB = TRANS_PKB.NO_PKB", "LEFT"),
                array("TRANS_CSA AS TC", "TC.KD_SA = TRANS_PKB.KD_SA", "LEFT"),
                array("MASTER_PART AS MP", "MP.PART_NUMBER = TPD.KD_PEKERJAAN", "LEFT"),
                array("MASTER_CUSTOMER AS MC", "MC.KD_CUSTOMER = TC.KD_CUSTOMER", "LEFT"),
                array("MASTER_DEALER AS MD", "MD.KD_DEALER = TRANS_PKB.KD_DEALER", "LEFT")
                /*array("MASTER_JASA AS MJ", "MJ.KD_JASA = TPD.KD_PEKERJAAN", "LEFT")*/
            ),
            'field' => "TRANS_PKB.*, TPD.KATEGORI, TPD.KD_PEKERJAAN, TPD.KATEGORI, TC.KD_CUSTOMER, TC.KD_HONDA, TC.NAMA_PEMILIK, TC.NO_HP, TC.KD_PEMBAWAMOTOR, TPD.QTY, MP.PART_NUMBER, MC.NAMA_CUSTOMER, MC.NO_TELEPON, MD.KD_DEALERAHM,
                CASE WHEN TPD.KATEGORI='Jasa' 
                THEN (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END KETERANGAN",
                "orderby" => "TPD.KATEGORI asc",
            'limit' => '*'
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . date('Ymd') . "'";
        }  
        $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        return $data;
    }
    public function createfile_daily_unit() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $data = $this->download_daily_unit();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $this->session->userdata("kd_maindealer") . "-" . $row->KD_DEALER . "-" . tglfromSql($row->TANGGAL_PKB) . ".SDUE";
                $isifile .= str_replace("/", "", tglFromSql($row->TANGGAL_PKB)) . ";";
                /*$isifile .= $row->KATEGORI . ";";*/
                if ($row->KATEGORI == "Jasa") {
                    $isifile .= "J" . ";";
                    $isifile .= $row->KD_DEALERAHM . ";";
                    $isifile .= $row->NO_PKB . ";";
                    $isifile .= $row->NAMA_PEMILIK . ";";
                    $isifile .= $row->NO_HP . ";";  
                    $isifile .= $row->KD_PEMBAWAMOTOR. ";";
                    $isifile .= ";";
                    $isifile .= $row->NAMA_CUSTOMER . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->KD_HONDA . ";";
                    $isifile .= $row->NO_POLISI . ";";
                    $isifile .= $row->NO_MESIN . ";";
                    $isifile .= $row->NO_RANGKA . ";";
                    $isifile .= $row->JENIS_KPB . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->KETERANGAN . ";";
                    $isifile .= $row->QTY . ";"; 
                    $isifile .= $row->KD_PROMO . ";";
                    $isifile .= $row->KETERANGAN_PROMO . ";" . PHP_EOL;  
                } elseif($row->KATEGORI == "Part") {
                    $isifile .= "P" . ";";
                    $isifile .= $row->KD_DEALERAHM . ";";
                    $isifile .= $row->NO_PKB . ";";
                    $isifile .= $row->NAMA_PEMILIK . ";";
                    $isifile .= $row->NO_HP . ";";  
                    $isifile .= $row->KD_PEMBAWAMOTOR. ";";
                    $isifile .= ";";
                    $isifile .= $row->NAMA_CUSTOMER . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->KD_HONDA . ";";
                    $isifile .= $row->NO_POLISI . ";";
                    $isifile .= $row->NO_MESIN . ";";
                    $isifile .= $row->NO_RANGKA . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->KETERANGAN . ";";
                    $isifile .= $row->QTY . ";";
                    $isifile .= ";";
                    $isifile .= ";" . PHP_EOL;  
                    } else {
                        $isifile .= ";";
                    }
                /*$isifile .= $row->KD_DEALERAHM . ";";
                $isifile .= $row->NO_PKB . ";";
                $isifile .= $row->NAMA_PEMILIK . ";";
                $isifile .= $row->NO_HP . ";";  
                $isifile .= $row->KD_PEMBAWAMOTOR. ";";
                $isifile .= $row->NAMA_CUSTOMER . ";";
                $isifile .= $row->NO_TELEPON . ";";
                $isifile .= $row->KD_HONDA . ";";
                $isifile .= $row->NO_POLISI . ";";
                $isifile .= $row->NO_MESIN . ";";
                $isifile .= $row->NO_RANGKA . ";";
                $isifile .= $row->JENIS_KPB . ";";
                $isifile .= $row->KD_PEKERJAAN . ";";
                $isifile .= $row->KETERANGAN . ";";
               /* $isifile .= $row->PART_NUMBER . ";";
                $isifile .= $row->QTY . ";" . PHP_EOL;  */
        }   
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    public function monitoring_report_print() {
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '".$this->input->get('kd_dealer')."'" : "KD_DEALER ='".$this->session->userdata("kd_dealer"). "' AND convert(char, TANGGAL_PKB,112) =  '" . tglToSql($this->input->get("tanggal")) . "'",
            'tanggal' => $this->input->get('tanggal'),  
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array(
                array("TRANS_PKB_DETAIL AS TPD", "TPD.NO_PKB = TRANS_PKB.NO_PKB", "LEFT"),
                array("TRANS_CSA AS TC", "TC.KD_SA = TRANS_PKB.KD_SA", "LEFT"),
                array("MASTER_CUSTOMER AS MC", "MC.KD_CUSTOMER = TC.KD_CUSTOMER", "LEFT")
            ),              
            'field' => "TRANS_PKB.*, TPD.KD_PEKERJAAN, MC.NAMA_CUSTOMER, TC.KD_HONDA, TPD.QTY,
                        CASE WHEN TPD.KATEGORI='Jasa'
                        THEN (SELECT TOP 1 Q.KETERANGAN FROM MASTER_JASA as Q WHERE Q.KD_JASA=TPD.KD_PEKERJAAN) END KETERANGAN",
            'orderby' => "TPD.KATEGORI asc",
            'limit' => 50
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR, TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        ); 
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        $data["dealer"] =  json_decode($this->curl->simple_get(API_URL. "/api/master/dealer", $paramcustomer));
        $param = array(
            'field' => "YEAR(TANGGAL_PKB) AS TAHUN",
            'groupby' => TRUE,
            'orderby' => "YEAR(TANGGAL_PKB) DESC"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->load->view('report/monitoring_report/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function human_resource() {
        $data = array();
        $result = null;
        $data["totaldata"] = "";
        $bln = ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m");
        $thn = ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y");
        $param = array(
            'row_status' => ($this->input->get('row_status') == null)? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*'
        );
        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }
        $param["custom"] = "MONTH(TGL_MASUK)='" . $this->input->get('bulan') . "' AND YEAR(TGL_MASUK)='" . $this->input->get("tahun"). "'";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));

        $string = explode('&page=', $_SERVER["REQUEST_URI"]);

        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/human_resource/view', $data);
    }
    public function human_resource_print() {
        $data = array();
        $bln = ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m");
        $thn = ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y");
        $param = array(
             'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            /*'tahun' => ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y"),
            'bulan' => ($this->input->get("bulan")) ? $this->input->get("bulan") : date("m"),*/
            'row_status' => ($this->input->get('row_status') == null)? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            /*'orderby' => 'ID DESC',*/
            'field' => '*'
        );
        $param["custom"] = "MONTH(TGL_MASUK)='" . $this->input->get('bulan') . "' AND YEAR(TGL_MASUK)='" . $this->input->get("tahun"). "'";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
       /* $string = explode('&page=', $_SERVER["REQUEST_URI"]);*/
       $this->load->view('report/human_resource/print', $data);
       $html = $this->output->get_output();
       $this->output->set_output(json_encode($html));
    }
   /* public function human_resource_typeahead() {
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_general/karyawan"));
        if ($data) {
            if ($is_array($data["list"]->message)) {
                foreach ($data["list"]->message as $key => $message) {
                    $data_message[0][$key] = $message->NAMA;
                }
                $result['keyword'] = array_merge($data_message[0]);
                $this->output->set_output(json_encode($result));
            }
        }
    }*/
    public function mekanik_attendance() {
        $data = array();
        $data["totaldata"] = "";
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Y-m-d');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));

        $param = array( 
            'keyword' => $this->input->get('keyword'),
            //'kd_dealer' => listDealer(),
            'row_status' => ($this->input->get('row_status') == null)? 0 : $this->input->get('row_status'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );
        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
        }
        
                //var_dump($param); exit;
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/mekanik_attendance", $param));
        //var_dump($data["list"]); print_r($param);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer",listDealer()));
        //var_dump($this->session->userdata('kd_group')); exit;
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
       /* $string = link_pagination();
*/
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/mekanik_attendance/view', $data);
    }
    public function mekanik_attendance_print() {
        $data = array();
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Y-m-d');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));
        $param = array( 
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null)? 0 : $this->input->get('row_status'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/transaksi/mekanik_attendance", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('report/mekanik_attendance/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    public function get_date_perweek() {
        $data = array();
        $date_now = $this->input->get('tgl_awal') ? $this->input->get('tgl_awal') : date('d/m/Y');
        // $date_now = '31/07/2018';
        // $date_now = '02/08/2018';
        $thisday = date("m", strtotime(tglToSql($date_now)));
        $firstday = date("m", strtotime('monday this week', strtotime(tglToSql($date_now))));
        $month = $thisday - $firstday;
        $tgl_periode_awal = date("d/m/Y", strtotime('monday this week', strtotime(tglToSql($date_now))));
        $tgl_periode_akhir = tglfromSql(getNextDays(tglToSql($tgl_periode_awal), 7));
        $month_interval = date("m", strtotime(tglToSql($tgl_periode_akhir))) - date("m", strtotime(tglToSql($tgl_periode_awal)));
        $month_end = date("d/m/Y", strtotime('last day of this month', strtotime(tglToSql($tgl_periode_awal))));
        $month_start = date("d/m/Y", strtotime('first day of this month', strtotime(tglToSql($tgl_periode_akhir))));
        $month_1 = date("F", strtotime('monday this week', strtotime(tglToSql($date_now))));
        $month_2 = date("F", strtotime('first day of this month', strtotime(tglToSql($tgl_periode_akhir))));
        $this_month_start = date("d/m/Y", strtotime('first day of this month', strtotime(tglToSql($date_now))));
        $this_month_end = date("d/m/Y", strtotime('last day of this month', strtotime(tglToSql($date_now))));
        $min1_month_start = date("d/m/Y", strtotime("first day of previous month", strtotime(tglToSql($date_now))));
        $min1_month_end = date("d/m/Y", strtotime("last day of previous month", strtotime(tglToSql($date_now))));
        $min2_month_start = date("d/m/Y", strtotime("first day of previous month", strtotime(tglToSql($min1_month_start))));
        $min2_month_end = date("d/m/Y", strtotime("last day of previous month", strtotime(tglToSql($min1_month_start))));
        if ($month_interval == 1) {
            $data['start_date'] = $month == 0 ? $tgl_periode_awal : $month_start;
            $data['end_date'] = $month == 0 ? $month_end : $tgl_periode_akhir;
            $data['month'] = $month == 0 ? $month_1 : $month_2;
            $data['this_month_start'] = $this_month_start;
            $data['this_month_end'] = $this_month_end;
            $data['min1_month_start'] = $min1_month_start;
            $data['min1_month_end'] = $min1_month_end;
            $data['min2_month_start'] = $min2_month_start;
            $data['min2_month_end'] = $min2_month_end;
        } else {
            $data['start_date'] = $tgl_periode_awal;
            $data['end_date'] = $tgl_periode_akhir;
            $data['month'] = $month_1;
            $data['this_month_start'] = $this_month_start;
            $data['this_month_end'] = $this_month_end;
            $data['min1_month_start'] = $min1_month_start;
            $data['min1_month_end'] = $min1_month_end;
            $data['min2_month_start'] = $min2_month_start;
            $data['min2_month_end'] = $min2_month_end;
        }
        // $this->output->set_output(json_encode($data));
        return $data;
    }
    public function data_output($hasil = NULL, $method = '') {
        $result = "";
        switch ($method) {
            case 'post':
                $hasil = (json_decode($hasil));
                if ($hasil->status === TRUE) {
                    $result = array(
                        'status' => true,
                        'message' => "Data berhasil simpan"
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
                        'message' => "Update berhasil"
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
}
        