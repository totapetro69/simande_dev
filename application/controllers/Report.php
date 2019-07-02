<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
    /**
     * [__construct description]
     * 
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('dompdf_gen');
        $this->load->library('curl');
        $this->load->library('pagination');
        $this->load->library('template');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper("zetro_helper");
        $this->load->library('user_agent');
        $this->load->model('Custom_model');
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
     * [sales description]
     * @return [type] [description]
     */
    public function sales() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        //$kd_dealer = $this->input->get('kd_dealer') ? "TRANS_SPK_VIEW.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_SPK_VIEW.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_SPK,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SPK,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'custom' => "LEN(ISNULL(NO_SO,''))>0",
            'jointable' => array(
                array("TRANS_SPK_KENDARAAN_VIEW SK", "SK.SPK_ID=TRANS_SPK_VIEW.SPKID", "LEFT"),
                array("MASTER_P_GROUPMOTOR MP", "MP.KD_GROUPMOTOR=SK.KD_TYPEMOTOR", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS M", "M.KD_WARNA=SK.KD_WARNA", "LEFT", 'KD_WARNA,KET_WARNA')
            ),
            'custom' => $tgl
            //'custom' => $kd_dealer . " AND " . $tgl
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }
        
        $grouping = $this->input->get("pilih");
        $params = array();
        $newParam = array();
        $nom = 0;
        $totaldata = 0;
        switch ($grouping) {
            case '1':
                # motor 9 segmen...
                $html = "";
                $params["field"] = "SEMBILAN_SEGMEN";
                $params["groupby"] = TRUE;
                $params["orderby"] = "SEMBILAN_SEGMEN";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["9_segmen"] = $value->SEMBILAN_SEGMEN;
                            $html .= "<tr class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->SEMBILAN_SEGMEN) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . "-" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
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
                # Type Jual C/K
                $html = "";
                $nom = 0;
                $params["field"] = "TYPE_PENJUALAN";
                $params["groupby"] = TRUE;
                $params["orderby"] = "TYPE_PENJUALAN";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["type_penjualan"] = $value->TYPE_PENJUALAN;
                            $html .= "<tr class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->TYPE_PENJUALAN) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            //print_r($param);
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . "-" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                        //exit();
                    }
                }
                break;
            case '6':
                #Kategori
                $html = "";
                $params["field"] = "CATEGORY_MOTOR";
                $params["groupby"] = TRUE;
                $params["orderby"] = "CATEGORY_MOTOR";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["category"] = $value->CATEGORY_MOTOR;
                            $html .= "<tr class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->CATEGORY_MOTOR) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . " - " . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                break;
            case '5':
                #Honda ID
                $html = "";
                $params["field"] = "KD_SALES,KD_HSALES,NAMA_SALES";
                $params["groupby"] = TRUE;
                $params["orderby"] = "KD_SALES";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["kd_sales"] = $value->KD_SALES;
                            $html .= "<tr class='info'><td class='text-left'>$nom</td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->KD_SALES) . "[" . $value->KD_HSALES . "] " . $value->NAMA_SALES . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td class='text-right'>$n</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . "-" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                        }
                    }
                }
                break;
            case '3':
            #Kode Customer
            //break;
            case '4':
            #Lokasi Penjualan
            //break;
            default:
                $html = "";
                $n = 0;
                $param["field"] = "TRANS_SPK_VIEW.*,SK.KD_ITEM,SK.NAMA_ITEM,SK.HARGA,SK.KD_TYPEMOTOR,M.KET_WARNA,SK.NO_MESIN,SK.NO_RANGKA";
                $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                if ($datax) {
                    if ($datax->totaldata > 0) {
                        foreach ($datax->message as $key => $value) {
                            $n++;
                            $html .= "<tr><td class='text-center'>$n</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->KD_TYPEMOTOR . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . " - " . $value->NO_MESIN . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                            $html .= "<td class='text-center'>" . $value->TYPE_PENJUALAN . "</td>";
                            $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
                        }
                        $totaldata = $datax->totaldata;
                    }
                }
                break;
        }
        $data["html"] = $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
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
        $this->template->site('report/sales', $data);
    }
    /**
     * [sales_print description]
     * @return [type] [description]
     */
    public function sales_print() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'limit' => 15,
            'jointable' => array(
                array("TRANS_SPK_KENDARAAN_VIEW SK", "SK.SPK_ID=TRANS_SPK_VIEW.SPKID", "LEFT"),
                array("MASTER_P_GROUPMOTOR MP", "MP.KD_GROUPMOTOR=SK.KD_TYPEMOTOR", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS M", "M.KD_WARNA=SK.KD_WARNA", "LEFT", 'KD_WARNA,KET_WARNA')
            ),
            'custom' => "LEN(ISNULL(NO_SO,''))>0"
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] .= " AND CONVERT(CHAR,TGL_SPK,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] .= " AND CONVERT(CHAR,TGL_SPK,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
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
                # motor 9 segmen...
                $html = "";
                $params["field"] = "SEMBILAN_SEGMEN";
                $params["groupby"] = TRUE;
                $params["orderby"] = "SEMBILAN_SEGMEN";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["9_segmen"] = $value->SEMBILAN_SEGMEN;
                            $html .= "<tr style='border-bottom: 1px solid;' class='info'><td class='text-left'>$nom.</td><td></td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->SEMBILAN_SEGMEN) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td></td>";
                                        $html .= "<td class='text-right'>$n.</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . "-" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
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
                # Type Jual C/K
                $html = "";
                $nom = 0;
                $params["field"] = "TYPE_PENJUALAN";
                $params["groupby"] = TRUE;
                $params["orderby"] = "TYPE_PENJUALAN";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["type_penjualan"] = $value->TYPE_PENJUALAN;
                            $html .= "<tr style='border-bottom: 1px solid;' class='info'><td class='text-left'>$nom.</td><td></td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->TYPE_PENJUALAN) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            //print_r($param);
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td></td>";
                                        $html .= "<td class='text-right'>$n.</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . "-" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                        //exit();
                    }
                }
                break;
            case '6':
                #Kategori
                $html = "";
                $params["field"] = "CATEGORY_MOTOR";
                $params["groupby"] = TRUE;
                $params["orderby"] = "CATEGORY_MOTOR";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["category"] = $value->CATEGORY_MOTOR;
                            $html .= "<tr style='border-bottom: 1px solid;' class='info'><td class='text-left'>$nom.</td><td></td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->CATEGORY_MOTOR) . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td></td>";
                                        $html .= "<td class='text-right'>$n.</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . " - " . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
                                    }
                                    $totaldata += $datax->totaldata;
                                }
                            }
                            $html .= "<tr style='border-bottom: 1px solid;'></tr>";
                        }
                    }
                }
                break;
            case '5':
                #Honda ID
                $html = "";
                $params["field"] = "KD_SALES,KD_HSALES,NAMA_SALES";
                $params["groupby"] = TRUE;
                $params["orderby"] = "KD_SALES";
                $newParam = array_merge($param, $params);
                $list = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $newParam));
                if ($list) {
                    if ($list->totaldata > 0) {
                        foreach ($list->message as $key => $value) {
                            $nom++;
                            $n = 0;
                            $param["kd_sales"] = $value->KD_SALES;
                            $html .= "<tr style='border-bottom: 1px solid;' class='info'><td class='text-left'>$nom.</td><td></td>";
                            $html .= "<td colspan='9'>" . strtoupper($value->KD_SALES) . "[" . $value->KD_HSALES . "] " . $value->NAMA_SALES . "</td>";
                            $html .= "</tr>";
                            $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                            if ($datax) {
                                if ($datax->totaldata > 0) {
                                    foreach ($datax->message as $key => $value) {
                                        $n++;
                                        $html .= "<tr><td></td>";
                                        $html .= "<td class='text-right'>$n.</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                                        $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                                        $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . "-" . $value->NO_MESIN . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                                        $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                                        $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
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
            #Kode Customer
            //break;
            case '4':
            #Lokasi Penjualan
            //break;
            default:
                $html = "";
                $n = 0;
                $param["field"] = "TRANS_SPK_VIEW.*,SK.KD_ITEM,SK.NAMA_ITEM,SK.HARGA,SK.KD_TYPEMOTOR,M.KET_WARNA,SK.NO_MESIN,SK.NO_RANGKA";
                /* print_r($param);
                  exit(); */
                $datax = json_decode($this->curl->simple_get(API_URL . "/api/laporan/spkview", $param));
                if ($datax) {
                    if ($datax->totaldata > 0) {
                        foreach ($datax->message as $key => $value) {
                            $n++;
                            $html .= "<tr><td colspan='2' class='text-center'>$n.</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->NO_SO . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . tglFromSql($value->TGL_SPK) . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->NAMA_CUSTOMER . "</td>";
                            $html .= "<td class='text-center'>" . $value->KD_TYPEMOTOR . "</td>";
                            $html .= "<td class='text-left table-nowarp'>" . $value->KET_WARNA . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->NO_RANGKA . " - " . $value->NO_MESIN . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->KD_DEALER . "</td>";
                            $html .= "<td class='text-center table-nowarp'>" . $value->TYPE_PENJUALAN . "</td>";
                            $html .= "<td class='text-right table-nowarp'>" . number_format($value->HARGA, 0) . "</td></tr>";
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
        $this->load->view('report/sales_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [laporan_kbp description]
     * @return [type] [description]
     */
    public function laporan_kbp() {
        $data = array();
        //$kd_dealer = $this->input->get('kd_dealer') ? "TRANS_KPB_VALIDASI.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_KPB_VALIDASI.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_SERVICE,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SERVICE,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'field' => '*',
            'custom' => $tgl,
            'orderby' => 'TRANS_KPB_VALIDASI.ID desc'
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->template->site('report/kpb/report_kpb', $data);
    }
    /**
     * [cetak_kpb description]
     * @return [type] [description]
     */
    public function cetak_kpb() {
        $this->load->library('dompdf_gen');
        $data = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_KPB_VALIDASI.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_KPB_VALIDASI.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? "convert(char,TGL_SERVICE,112) between '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'" : "convert(char,TGL_SERVICE,112) between '" . tglToSql(date('d/m/Y', strtotime('first day of this month'))) . "' AND '" . tglToSql(date('d/m/Y')) . "'";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            // 'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            // 'limit' => 15,
            'field' => '*',
            'custom' => $kd_dealer . " AND " . $tgl,
            'orderby' => 'TRANS_KPB_VALIDASI.ID desc'
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/kpb_validasi", $param));
        $html = '<link rel="stylesheet" href="' . base_url("assets/css/bootstrap.min.css") . '" >';
        $html .= $this->kpb_report_table($data['list']->message);
        // var_dump($html);exit;
        $filename = 'report_kpb_' . time();
        $this->dompdf_gen->generate($html, $filename, true, 'Legal', 'landscape');
        // $this->output->set_output(json_encode($data["list"]));
    }
    /**
     * [kpb_report_table description]
     * @param  [type] $list [description]
     * @return [type]       [description]
     */
    public function kpb_report_table($list) {
        $html = '';
        $tgl = ($this->input->get("tgl_awal") or $this->input->get("tgl_akhir")) ? $this->input->get("tgl_awal") . " - " . $this->input->get("tgl_akhir") : date('d/m/Y', strtotime('first day of this month')) . " - " . date('d/m/Y');
        $html .= '<H3 class="text-center"><strong>LAPORAN KPB PERIODE : ' . $tgl . '</strong</H3><br>';
        $html .= '<table id="pkb_list" class="table table-bordered">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:40px;">No.</th>';
        $html .= '<th>Kode Main Dealer</th>';
        $html .= '<th>Kode Dealer</th>';
        $html .= '<th>No. KPB</th>';
        $html .= '<th>No. PKB</th>';
        $html .= '<th>No. Mesin</th>';
        $html .= '<th>No. Rangka</th>';
        $html .= '<th>Tanggal Beli</th>';
        $html .= '<th>Sequence</th>';
        $html .= '<th>KM Service</th>';
        $html .= '<th>Tanggal Service</th>';
        $html .= '<th>Motor Luar</th>';
        $html .= '<th>Buku Baru</th>';
        $html .= '<th>Keterangan</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $no = 1;
        foreach ($list as $key => $row):
            switch ($row->STATUS_KPB) {
                case 1:
                    $status = 'Sudah divalidasi';
                    break;
                case 2:
                    $status = 'Sudah diclaim';
                    break;
                default:
                    $status = 'Belum divalidasi';
                    break;
            }
            $html .= '<tr>';
            $html .= '<td>' . $no . '</td>';
            $html .= '<td>' . $row->KD_MAINDEALER . '</td>';
            $html .= '<td>' . $row->KD_DEALER . '</td>';
            $html .= '<td>' . $row->NO_KPB . '</td>';
            $html .= '<td>' . $row->NO_PKB . '</td>';
            $html .= '<td>' . $row->KD_MESIN . $row->NO_MESIN . '</td>';
            $html .= '<td>' . $row->NO_RANGKA . '</td>';
            $html .= '<td>' . $row->TGL_BELI . '</td>';
            $html .= '<td>' . $row->SEQUENCE . '</td>';
            $html .= '<td>' . $row->KM_SERVICE . '</td>';
            $html .= '<td>' . $row->TGL_SERVICE . '</td>';
            $html .= '<td>' . ($row->MOTOR_LUAR == '*' ? 'True' : 'False') . '</td>';
            $html .= '<td>' . ($row->BUKU_BARU == '' ? 'False' : 'True') . '</td>';
            $html .= '<td>' . $status . '</td>';
            $html .= '</tr>';
            $no ++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
    /**
     * [udh_list description]
     * @return [type] [description]
     */
    public function udh_list() {
        $param = array(
            'keyword' => base64_decode(urldecode($this->input->get("n"))),
            'jointable' => array(
                array("TRANS_SPK AS TS", "PD.ID_PO=TRANS_PO2MD.ID", "LEFT"),
                array("TRANS_SPK_DETAILKENDARAAN AS TD", "MS.KD_DEALER=TRANS_PO2MD.KD_DEALER", "LEFT"),
                array("MASTER_MAINDEALER AS MD", "MD.KD_MAINDEALER=MS.KD_MAINDEALER", "LEFT"),
                array("MASTER_P_TYPEMOTOR AS MT", "MT.KD_ITEM=CONCAT(PD.KD_TYPEMOTOR,'-',PD.KD_WARNA)", "LEFT")
            ),
            'field' => "TRANS_PO2MD.*,PD.ID AS PODETAILID,PD.KD_WARNA,PD.KD_TYPEMOTOR,PD.FIX_QTY,PD.T1_QTY,PD.T2_QTY,
                MT.KD_ITEM,MT.NAMA_ITEM,MS.NAMA_DEALER,MS.KD_DEALERAHM,MD.NAMA_MAINDEALER"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        //var_dump($data->message);
        return $data;
    }
    /**
     * [createfile_udh description]
     * @return [type] [description]
     */
    public function createfile_udh() {
        $data = array();
        $data = $this->udh_list();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('ymdHis') . ".UDPO";
            $isifile .= $row->NO_SURATJALAN . ";";
            $isifile .= $row->KD_CUSTOMER . ";";
            $isifile .= $row->NO_SO . ";";
            $isifile .= $row->KD_TYPEMOTOR . ";";
            $isifile .= $row->KD_WARNA . ";";
            $isifile .= $row->NO_RANGKA . ";";
            $isifile .= $row->NO_MESIN . ";";
            $isifile .= $row->NO_STNK . ";";
            $isifile .= $row->NO_POLISI . ";";
            $isifile .= $row->TGL_PENYERAHANSTNK . ";";
            $isifile .= $row->PENERIMA_STNK . ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= ";";
            $isifile .= PHP_EOL;
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    /**
     * [part description]
     * @param  [type] $dataOnly [description]
     * @param  [type] $detail   [description]
     * @return [type]           [description]
     */
    public function part($dataOnly = null, $detail = null) {
        $data = array();
        $superseed = '';
        if ($this->input->get('part_superseed') != '') {
            $superseed = $this->input->get('part_superseed') == 'notsuperseed' ? "PART_SUPERSEED = ''" : "PART_SUPERSEED != ''";
        }
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'custom' => $superseed,
            'part_rank' => ($this->input->get('part_rank') == null) ? 0 : $this->input->get('part_rank'),
            'part_current' => ($this->input->get('part_current') == null) ? 0 : $this->input->get('part_current'),
            'part_moving' => ($this->input->get('part_moving') == null) ? 0 : $this->input->get('part_moving'),
            'part_group' => ($this->input->get('part_group') == null) ? 0 : $this->input->get('part_group'),
            'part_source' => ($this->input->get('part_source') == null) ? 0 : $this->input->get('part_source'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*'
        );
        if ($dataOnly == true && !$detail) {
            $param = array(
                'keyword' => $this->input->get('q')
            );
            if (!$this->input->get("q")) {
                $param['limit'] = 100; // $this->input->get('limit');
                $param['offset'] = ($this->input->get('p') == null) ? 0 : $this->input->get('p', TRUE);
            }
        } else if ($dataOnly == true && $detail == true) {
            $param = array('part_number' => $this->input->get('p'));
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        if ($dataOnly == true) {
            if (isset($data["list"])) {
                if ($data["list"]->totaldata > 0) {
                    echo json_encode($data["list"]->message);
                } else {
                    echo "[]";
                }
            } else {
                echo "[]";
            }
        } else {
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            //var_dump($pagination);        exit();
            $this->template->site('report/part', $data);
        }
    }
    /**
     * [part_print description]
     * @param  [type] $dataOnly [description]
     * @param  [type] $detail   [description]
     * @return [type]           [description]
     */
    public function part_print($dataOnly = null, $detail = null) {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => '*'
        );
        if ($dataOnly == true && !$detail) {
            $param = array(
                'keyword' => $this->input->get('q')
            );
            if (!$this->input->get("q")) {
                $param['limit'] = 100; // $this->input->get('limit');
                $param['offset'] = ($this->input->get('p') == null) ? 0 : $this->input->get('p', TRUE);
            }
        } else if ($dataOnly == true && $detail == true) {
            $param = array('part_number' => $this->input->get('p'));
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sparepart/part", $param));
        if ($dataOnly == true) {
            if (isset($data["list"])) {
                if ($data["list"]->totaldata > 0) {
                    echo json_encode($data["list"]->message);
                } else {
                    echo "[]";
                }
            } else {
                echo "[]";
            }
        } else {
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $this->load->view('report/part_print', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }
    }
    /**
     * [guest_book_daily description]
     * @return [type] [description]
     */
    public function guest_book_daily() {
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) = '" . tglToSql($this->input->get("tanggal")) . "'",
            'keyword' => $this->input->get('keyword'),
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array(
                array("TRANS_GUESTBOOK_DETAIL AS TGD", "TGD.GUEST_ID = TRANS_GUESTBOOK_VIEW.ID", "LEFT"),
                array("MASTER_METODEFU AS MM", "MM.ID = TGD.METODE_FU", "LEFT"),
                array("SETUP_KETNOTDEAL AS KND", "KND.KD_KND = TRANS_GUESTBOOK_VIEW.KETERANGAN", "LEFT")
            ),
            'field' => "TRANS_GUESTBOOK_VIEW.*,STATUS, KND.ALASAN, METODE_FU, KET_NODEAL,
                CASE WHEN TRANS_GUESTBOOK_VIEW.STATUS='Pending' THEN TRANS_GUESTBOOK_VIEW.RENCANA_FU ELSE (SELECT RENCANA_FU FROM TRANS_GUESTBOOK_DETAIL WHERE TRANS_GUESTBOOK_DETAIL.GUEST_ID = TRANS_GUESTBOOK_VIEW.ID) END RENCANA_FU,
                (SELECT COUNT (ID)FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS IN ('Deal','Deal Indent') ) AS TYPECUSTOMER,
                (SELECT COUNT (ID) FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS NOT IN ('Deal','Deal Indent') ) AS JENIS",
            'limit' => 15
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        // var_dump($paramcustomer);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/daily_guestbook/guest_book_daily', $data); //, $data
    }
    /**
     * [download_listdaily description]
     * @return [type] [description]
     */
    public function download_listdaily() {
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_GUESTBOOK.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_GUESTBOOK.KD_DEALER ='" . $this->session->userdata("kd_dealer") . "'";
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TGL_TRANS,112) = '" . tglToSql($this->input->get("tanggal")) . "'",
            'keyword' => $this->input->get('keyword'),
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array(
                array("TRANS_GUESTBOOK_DETAIL AS TGD", "TGD.GUEST_ID = TRANS_GUESTBOOK_VIEW.ID", "LEFT"),
                array("MASTER_METODEFU AS MM", "MM.ID = TGD.METODE_FU", "LEFT"),
                array("SETUP_KETNOTDEAL AS KND", "KND.KD_KND = TRANS_GUESTBOOK_VIEW.KETERANGAN", "LEFT")
            ),
            'field' => "TRANS_GUESTBOOK_VIEW.*,STATUS, KND.ALASAN, METODE_FU, KET_NODEAL,
                CASE WHEN TRANS_GUESTBOOK_VIEW.STATUS='Pending' THEN TRANS_GUESTBOOK_VIEW.RENCANA_FU ELSE (SELECT RENCANA_FU FROM TRANS_GUESTBOOK_DETAIL WHERE TRANS_GUESTBOOK_DETAIL.GUEST_ID = TRANS_GUESTBOOK_VIEW.ID) END RENCANA_FU,
                (SELECT COUNT (ID)FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS IN ('Deal','Deal Indent') ) AS TYPECUSTOMER,
                (SELECT COUNT (ID) FROM TRANS_GUESTBOOK AS G WHERE G.KD_DEALER = TRANS_GUESTBOOK_VIEW.KD_DEALER AND G.KD_CUSTOMER=TRANS_GUESTBOOK_VIEW.KD_CUSTOMER AND STATUS NOT IN ('Deal','Deal Indent') ) AS JENIS",
            'limit' => 15
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL,112) = '" . date('Ymd') . "'";
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/sales/guestbook", $param));
        return $data;
    }
    /**
     * [createfile_daily_udcp description]
     * @return [type] [description]
     */
    public function createfile_daily_udcp() {
        $data = array();
        $data = $this->download_listdaily();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $this->session->userdata("kd_maindealer") . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".UDCP"; //
            $isifile .= $row->ID . ";"; //iconv(in_charset, out_charset, str)d guest
            $isifile .= $row->KD_CUSTOMER . ";"; //id customer
            $isifile .= $row->KD_DEALER . ";"; // id dealer
            $isifile .= $row->KD_HSALES . ";"; //fk honda id
            $isifile .= $row->METODE_FU . ";"; // fk merode fu
            if ($row->TYPECUSTOMER > 0) {
                $isifile .= "1" . ";";
            } else {
                $isifile .= "0" . ";";
            } // kfid jenis Customer
            if ($row->CARA_BAYAR = "CASH") {
                $isifile .= "T;";
            } elseif ($row->CARA_BAYAR = "CREDIT") {
                $isifile .= "K;";
            } elseif ($row->CARA_BAYAR = "BILYET") {
                $isifile .= "B;";
            } else {
                $isifile .= ";";
            }// kfid setup pembayaran
            $isifile .= $row->STATUS . ";"; // kfid hasil
            $isifile .= $row->KETERANGAN . ";"; // kfid ket not deal
            $isifile .= str_replace("/", "", tglFromSql($row->TANGGAL)) . ";"; // hari
            $isifile .= $row->NAMA_CUSTOMER . ";"; // NAMA
            $isifile .= $row->ALAMAT . ";"; //alamat
            $isifile .= $row->NO_HP . ";"; // nohp
            $isifile .= $row->KD_WARNA . ";"; //warna motor
            $isifile .= $row->KD_TYPEMOTOR . ";"; //tipe motor
            $isifile .= $row->ALASAN . ";"; //hasil
            $isifile .= $row->RENCANA_FU . ";"; //rencana next fu
            $isifile .= $row->KET_NODEAL . ";";
            $isifile .= $row->NAMA_SALES . ";" . PHP_EOL;
        }
        //        var_dump($data->message);
        //        exit();
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    /**
     * [report_ksu description]
     * @return [type] [description]
     */
    public function report_ksu() {
        $data = array();
        //$kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        if ($this->input->get("kd_dealer") != null) {
            $kd_dealer = $this->input->get('kd_dealer');
        }elseif($this->session->userdata('kd_dealer')){
            $kd_dealer = $this->session->userdata('kd_dealer');
        }else{
            $kd_dealer = '0098';
        }
        
        $param = array(
            'kd_dealer' => $kd_dealer,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'field' => "MASTER_KSU.KD_KSU, MASTER_KSU.NAMA_KSU,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%BPPSG%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS BPPSG,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%BPPSG%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "') AS NBPPSG,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%AKI%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS AKI,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%AKI%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NAKI,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%HELM%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS HELM,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%HELM%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NHELM,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%TOOLSET%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS TOOLSET,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%TOOLSET%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NTOOLSET,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%SPION%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS SPION,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%SPION%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NSPION",
            'limit' => 15
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param));
        //        var_dump($data);        exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/ksu/view', $data);
    }
    /**
     * [report_ksu_print description]
     * @return [type] [description]
     */
    public function report_ksu_print() {
        $data = array();
        $this->auth->validate_authen('report_penerimaan/penerimaan');
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $param = array(
            'kd_dealer' => $kd_dealer,
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'field' => "MASTER_KSU.KD_KSU, MASTER_KSU.NAMA_KSU,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%BPPSG%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS BPPSG,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%BPPSG%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "') AS NBPPSG,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%AKI%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS AKI,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%AKI%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NAKI,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%HELM%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS HELM,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%HELM%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NHELM,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%TOOLSET%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS TOOLSET,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%TOOLSET%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NTOOLSET,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU LIKE '%SPION%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS SPION,
                (SELECT COUNT (ID) FROM TRANS_TERIMASJMOTOR AS TS WHERE TS.KSU NOT LIKE '%SPION%' AND ROW_STATUS ='0' AND TS.KD_DEALER LIKE '" . $kd_dealer . "' ) AS NSPION",
            'limit' => 15
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/ksu", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('report/ksu/print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [stockharian_part description]
     * @param  [type] $debug [description]
     * @return [type]        [description]
     */
    function stockharian_part($debug = null) {
        $this->auth->validate_authen("part/stockharian_part");
        $data = array();
        $param = array(
            'kd_dealer' =>($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer"),
            //'kd_lokasi' =>($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi"),
            'user_login' => str_replace("-","",$this->session->userdata("user_id"))
        );
        $direct=$this->input->get("d");
        //$gndata =($direct)? $this->curl->simple_get(API_URL . "/api/inventori/parts_generate", $param):"";
        //var_dump($gndata);
        $rand = $param["kd_dealer"]."_".strtoupper(substr($param["user_login"], 0, 10));
        $param["custom"] = "JUMLAH_SAK >0 AND ID IN(50)";
        $param["jointable"] = array(array("PART_VS_TYPEMOTOR_VIEW P", "P.PART_NUMBER=TRANS_PARTSTOCK_VIEW.PART_NUMBER", "LEFT"));
        //if(!$this->input->get("filter")){
        $param["field"] = "TRANS_PARTSTOCK_VIEW.*,P.TYPE_MOTOR";
        if($this->input->get("filter") == "TS" || $this->input->get("filter") == "SIM"){
            $param['limit'] = 15;
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
            $config = array(
                'total_rows' => isset($data["list"])?$data["list"]->totaldata:"0"
            );
        }
        if ($this->input->get("filter") == "GD") {
            $ingudang = array();
            $param["field"] = "KD_DEALER,KD_LOKASI,KD_GUDANG";
            $param["groupby"] = TRUE;
            unset($param["custom"]);
            $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50)";
            $data["lokasigd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
            if ($data["lokasigd"]) {
                unset($param["field"]);
                unset($param["groupby"]);
                unset($param["custom"]);
                $param["field"] = "TRANS_PARTSTOCK_VIEW.*,P.TYPE_MOTOR";
                $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50)";
                if ($data["lokasigd"]->totaldata > 0) {
                    foreach ($data["lokasigd"]->message as $key => $value) {
                        $param['limit'] = 15;
                        $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
                        $param["kd_lokasi"] = $value->KD_LOKASI;
                        $param["kd_gudang"] = $value->KD_GUDANG;
                        $ingudang[$value->KD_GUDANG] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
                    }
                }
                $config = array(
                    'total_rows'=> isset($ingudang[$value->KD_GUDANG])?$ingudang[$value->KD_GUDANG]->totaldata:"0"
                );
            }
            $data["inlokasi"] = $ingudang;
        }
        //group sales material
        if ($this->input->get("filter") == "SG") {
            $ingudang = array();
            $param["field"] = "KD_GROUPSALES";
            $param["groupby"] = TRUE;
            unset($param["custom"]);
            $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50)";
            $data["lokasigd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
            if ($data["lokasigd"]) {
                unset($param["field"]);
                unset($param["groupby"]);
                unset($param["custom"]);
                $ttdata=array();
                $param["field"] = "TRANS_PARTSTOCK_VIEW.*,P.TYPE_MOTOR";
                $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50) ";
                if ($data["lokasigd"]->totaldata > 0) {
                    foreach ($data["lokasigd"]->message as $key => $value) {
                        $param['limit'] = 15;
                        $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
                        $param["kd_groupsales"] = $value->KD_GROUPSALES;
                        $ingudang[$value->KD_GROUPSALES] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
                        array_push($ttdata,$ingudang[$value->KD_GROUPSALES]->totaldata);
                    }
                    $config = array(
                        'total_rows' => isset($ingudang[$value->KD_GROUPSALES])?max($ttdata):"0"
                    );
                }
            }
            $data["inlokasi"] = $ingudang;
        }
        if ($this->input->get("filter") == "SGP") {
            $ingudang = array();
            $param["field"] = "KD_GROUPSALES";
            $param["groupby"] = TRUE;
            unset($param["custom"]);
            $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50) and KD_GROUPSALES<>'OIL'";
            $data["lokasigd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
            if ($data["lokasigd"]) {
                unset($param["field"]);
                unset($param["groupby"]);
                unset($param["custom"]);
                $ttdata=array();
                $param["field"] = "TRANS_PARTSTOCK_VIEW.*,P.TYPE_MOTOR";
                $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50) and KD_GROUPSALES<>'OIL' ";
                if ($data["lokasigd"]->totaldata > 0) {
                    foreach ($data["lokasigd"]->message as $key => $value) {
                        $param['limit'] = 15;
                        $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
                        $param["kd_groupsales"] = $value->KD_GROUPSALES;
                        $ingudang[$value->KD_GROUPSALES] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
                        array_push($ttdata,$ingudang[$value->KD_GROUPSALES]->totaldata);
                    }
                    $config = array(
                        'total_rows' => isset($ingudang[$value->KD_GROUPSALES])?max($ttdata):"0"
                    );
                }
            }
            $data["inlokasi"] = $ingudang;
        }
        if ($this->input->get("filter") == "SGO") {
            $ingudang = array();
            $param["field"] = "KD_GROUPSALES";
            $param["groupby"] = TRUE;
            unset($param["custom"]);
            $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50)";
            $param["kd_groupsales"] = "OIL";
            $data["lokasigd"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
            if ($data["lokasigd"]) {
                unset($param["field"]);
                unset($param["groupby"]);
                unset($param["custom"]);
                $ttdata=array();
                $param["field"] = "TRANS_PARTSTOCK_VIEW.*,P.TYPE_MOTOR";
                $param["custom"] = " JUMLAH_SAK > 0 AND ID IN(50) ";
                if ($data["lokasigd"]->totaldata > 0) {
                    foreach ($data["lokasigd"]->message as $key => $value) {
                        $param['limit'] = 15;
                        $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
                        $param["kd_groupsales"] = "OIL";
                        
                        $ingudang[$value->KD_GROUPSALES] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/parts_view", $param));
                        array_push($ttdata,$ingudang[$value->KD_GROUPSALES]->totaldata);
                    }
                    $config = array(
                        'total_rows' => isset($ingudang[$value->KD_GROUPSALES])?max($ttdata):"0"
                    );
                }
            }
            $data["inlokasi"] = $ingudang;
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        if ($debug == true) {
            print_r($param);
            print_r($data["list"]->param);
        } else {
            $string = link_pagination();
            $config['per_page'] = 15;
            $config['base_url'] =  $string[0];
            switch($this->input->get("filter")){
                case "TS":
                case "GD":
                case "SIM":
                case "SG":
                case "SGO":
                case "SGP":
                $pagination = $this->pagination($config);
                $this->pagination->initialize($pagination);
                $data['pagination'] = $this->pagination->create_links();
                break;
            }
            $this->template->site('report/report_stock_harian', $data);
        }
    }
    /**
     * [salesharian_part description]
     * @param  [type] $forPrint [description]
     * @return [type]           [description]
     */
    function salesharian_part($forPrint = null) {
        $data = array();
        $tgl_trans = ($this->input->get("tgl")) ? tglToSql($this->input->get('tgl')) : date('Ymd');
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer")
        );

        $params = array(
            'kd_dealer' => $param["kd_dealer"],
            'custom' => "CONVERT(CHAR,TGL_TRANS,112) ='" . $tgl_trans . "'",
            'orderby' => "TGL_TRANS"
        );
        $data["dealer"] =  json_decode($this->curl->simple_get(API_URL. "/api/master/dealer",listDealer()));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/sales_harian", $params));
        if ($forPrint == true) {
            unset($data["dealer"]);
            $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true",listDealer()));
            $data["dlr"] = $param["kd_dealer"];
            $data["judul"] = $tgl_trans;
            $this->load->view('report/report_sales_harian_prt', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        } else {
            $this->template->site('report/report_sales_harian', $data);
        }
    }
    /**
     * [report_lbb description]
     * @return [type] [description]
     */
    function report_lbb() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => $this->input->get('tahun'),
            'bulan' => $this->input->get('bulan')
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["revenue"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb", $param));
        $data["unitentry"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb/true", $param));
        $data["rekap"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb_cum", $param));
        $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb2", $param));
        if ($data["mekanik"]) {
            if ($data["mekanik"]->totaldata > 0) {
                foreach ($data["mekanik"]->message as $key => $value) {
                    if ((int) $value->URUTAN == 2) {
                        $data["jml_harikerja"] = $value->HARI_KERJA;
                        $data["rata2unit"] = ($value->HARI_KERJA > 0) ? ROUND($value->JML_UNIT / $value->HARI_KERJA, 0) : 0;
                    }
                }
            }
        }
        $data["pengeluaran"] = "0";
        $param = array(
            'field' => "YEAR(TANGGAL_PKB) AS TAHUN",
            'groupby' => TRUE,
            'orderby' => "YEAR(TANGGAL_PKB) DESC"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->template->site('report/report_lbb', $data);
    }
    /**
     * [report_wpp description]
     * @return [type] [description]
     */
    function report_wpp() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => $this->input->get('tahun'),
            'bulan' => $this->input->get('bulan')
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $data["revenue"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb", $param));
        $data["unitentry"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb/true", $param));
        $data["rekap"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb_cum", $param));
        $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb2", $param));
        $data["bengkel"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/biayabengkel", $param));
        $data["customer"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/customerservice", $param));
        if ($data["mekanik"]) {
            if ($data["mekanik"]->totaldata > 0) {
                foreach ($data["mekanik"]->message as $key => $value) {
                    if ((int) $value->URUTAN == 2) {
                        $data["jml_harikerja"] = $value->HARI_KERJA;
                        $data["rata2unit"] = ROUND($value->JML_UNIT / $value->HARI_KERJA, 0);
                    }
                }
            }
        }
        $data["pengeluaran"] = "0";
        $param = array(
            'field' => "YEAR(TANGGAL_PKB) AS TAHUN",
            'groupby' => TRUE,
            'orderby' => "YEAR(TANGGAL_PKB) DESC"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->template->site('report/report_wpp', $data);
    }
    /**
     * [report_wppfile description]
     * @return [type] [description]
     */
    function report_wppfile() {
        $data = ($_POST);
        $result = array();
        $namafile = "";
        $tahun = "";
        $bulan = "";
        $isifile = "";
        $result = (json_decode($data["datax"], true));
        if ($result) {
            $tahun = explode(" ", $result[3]);
            $namafile = $result[0] . KodeDealer($result[0]) . $tahun[1] . str_pad(iBulan($tahun[0]), 2, '0', STR_PAD_LEFT) . ".SDWPP";
            $isifile .= $result[0] . ';' . str_pad(iBulan($tahun[0]), 2, '0', STR_PAD_LEFT) . $tahun[1] . ';';
            $isifile .= str_replace(',', '', $result[5]) . ';' . $result[7] . ';' . $result[8] . ';' . $result[9] . ';' . $result[10] . ';';
            $isifile .= str_replace(",", '', $result[6]) . ';' . $result[11] . ';' . str_replace(',', '', $result[12]) . ';' . $result[13] . ';' . $result[14] . ';';
            $isifile .= $result[15] . ';' . $result[16] . ';' . $result[17] . ';' . $result[24] . ';' . $result[25] . ';' . $result[26] . ';';
            $isifile .= $result[27] . ';' . $result[28] . ';' . $result[29] . ';' . $result[18] . ';' . str_replace(',', '', $result[19]) . ';' . str_replace(',', '', $result[20]) . ';';
            $isifile .= $result[21] . ';' . $result[22] . PHP_EOL;
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    /**
     * [createlbb_file description]
     * @return [type] [description]
     */
    function createlbb_file() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => $this->input->get('tahun'),
            'bulan' => $this->input->get('bulan')
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_sdlbb", $param));
        //print_r($param);print_r($data);exit();
        $namafile = "";
        $isifile = "";
        if ($data) {
            if ($data->totaldata > 0) {
                foreach ($data->message as $key => $value) {
                    $namafile = $value->KD_MAINDEALER . $value->KD_DEALER . $value->TAHUN . str_pad($value->BULAN, 2, '0', STR_PAD_LEFT) . ".SDLBB";
                    $isifile .= $value->KD_DEALERAHM . ";"; //No AHASS Varchar 5
                    $isifile .= str_pad($value->BULAN, 2, '0', STR_PAD_LEFT) . $value->TAHUN . ";"; //Bulan & tahun laporan Date
                    $isifile .= $value->KD_MAINDEALER . ";"; //Kode MD Varchar 3
                    $isifile .= date('Ymd') . ";"; //Tanggal generate laporan Date
                    $isifile .= (int) $value->JML_MEKANIK . ";"; //Jumlah mekanik Numb 3
                    $isifile .= (int) $value->JUMLAH_PIT . ";"; //Jumlah PIT AHASS Numb 3
                    $isifile .= (int) $value->JML_UNIT . ";"; //Total unit entry Numb 5
                    $isifile .= (int) $value->KPB1 . ";"; //Jumlah total tipe pekerjaan ASS 1 Numb 5
                    $isifile .= (int) $value->KPB2 . ";"; //Jumlah total tipe pekerjaan ASS 2 Numb 5
                    $isifile .= (int) $value->KPB3 . ";"; //Jumlah total tipe pekerjaan ASS 3 Numb 5
                    $isifile .= (int) $value->KPB4 . ";"; //Jumlah total tipe pekerjaan ASS 4 Numb 5
                    $isifile .= (int) $value->CCS . ";"; //Jumlah total tipe pekerjaan Claim2 Numb 5
                    $isifile .= (int) $value->CS . ";"; //Jumlah total tipe pekerjaan Complete Service Numb 5
                    $isifile .= (int) $value->LS . ";"; //Jumlah total tipe pekerjaan Light Service Numb 5
                    $isifile .= (int) $value->ORS . ";"; //Jumlah total tipe pekerjaan Oil Replacement Numb 5
                    $isifile .= (int) $value->LR . ";"; //Jumlah total tipe pekerjaan Light Repair Numb 5
                    $isifile .= (int) $value->HR . ";"; //Jumlah total tipe pekerjaan Heavy Repair Numb 5
                    $isifile .= (int) $value->JR . ";"; //Jumlah total pekerjaan job return Numb 5
                    $isifile .= (int) $value->PRODUKTIFITAS . ";"; //Produktivitas mekanik Numb 5
                    $isifile .= (int) $value->KPB1H . ";"; //Pendapatan jasa dari tipe pekerjaan ASS 1 Numb 20
                    $isifile .= (int) $value->KPB2H . ";"; //Pendapatan jasa dari tipe pekerjaan ASS 2 Numb 20
                    $isifile .= (int) $value->KPB3H . ";"; //Pendapatan jasa dari tipe pekerjaan ASS 3 Numb 20
                    $isifile .= (int) $value->KPB4H . ";"; //Pendapatan jasa dari tipe pekerjaan ASS 4 Numb 20
                    $isifile .= (int) $value->CC2H . ";"; //Pendapatan jasa dari tipe pekerjaan Claim2 Numb 20
                    $isifile .= (int) $value->CSH . ";"; //Pendapatan jasa dari tipe pekerjaan Complete Service Numb 20
                    $isifile .= (int) $value->LSH . ";"; //Pendapatan jasa dari tipe pekerjaan Light Service Numb 20
                    $isifile .= (int) $value->LRH . ";"; //Pendapatan jasa dari tipe pekerjaan Light Repair Numb 20
                    $isifile .= (int) $value->HRH . ";"; //Pendapatan jasa dari tipe pekerjaan Heavy Repair Numb 20
                    $isifile .= (int) $value->PARTH . ";"; //Pendapatan part dari kelompok barang spare part Numb 20
                    $isifile .= (int) $value->OILH . PHP_EOL; //Pendapatan part dari kelompok barang Oli Numb
                }
            }
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    /**
     * [weekly_gb description]
     * @return [type] [description]
     */
    public function weekly_gb() {
        $data = array();
        $data['date'] = $this->get_date_perweek();
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tgl_awal' => tglToSql($data['date']['start_date']),
            'tgl_akhir' => tglToSql($data['date']['end_date']),
            'gb_source' => $this->input->get('gb_source') ? $this->input->get('gb_source') : 'Walk In'
        );
        $data["metode"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/weekly_gb", $param));
        $param_thismonth = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tgl_awal' => tglToSql($data['date']['this_month_start']),
            'tgl_akhir' => tglToSql($data['date']['this_month_end']),
            'gb_source' => $this->input->get('gb_source') ? $this->input->get('gb_source') : 'Walk In'
        );
        $data["metode_thismonth"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/weekly_gb", $param_thismonth));
        $param_min1 = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tgl_awal' => tglToSql($data['date']['min1_month_start']),
            'tgl_akhir' => tglToSql($data['date']['min1_month_end']),
            'gb_source' => $this->input->get('gb_source') ? $this->input->get('gb_source') : 'Walk In'
        );
        $data["metode_min1"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/weekly_gb", $param_min1));
        $param_min2 = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tgl_awal' => tglToSql($data['date']['min2_month_start']),
            'tgl_akhir' => tglToSql($data['date']['min2_month_end']),
            'gb_source' => $this->input->get('gb_source') ? $this->input->get('gb_source') : 'Walk In'
        );
        $data["metode_min2"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/weekly_gb", $param_min2));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        // $this->output->set_output(json_encode($data['date']));
        $this->template->site('report/weekly_gb', $data);
    }
    /**
     * [get_date_perweek description]
     * @return [type] [description]
     */
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
    /**
     * [weekly_crm description]
     * @return [type] [description]
     */
    public function weekly_crm() {
        $data = array();
        $data['date'] = $this->get_date_perweek();
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tgl_awal' => tglToSql($data['date']['start_date']),
            'tgl_akhir' => tglToSql($data['date']['end_date'])
        );
        $data["crm"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/weekly_crm", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        // $this->output->set_output(json_encode($data["crm"]));
        $this->template->site('report/weekly_crm', $data);
    }
    /**
     * [service_reminder description]
     * @return [type] [description]
     */
    public function service_reminder() {
        $data = array();
        $paramcustomer = array();
        //$kd_dealer = $this->input->get('kd_dealer') ? "TRANS_FU_SERVICE.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_FU_SERVICE.KD_DEALER ='" . $this->session->userdata("kd_dealer");
        $data['date'] = $this->get_date_perweek();
        $param = array(
            //'custom' => $kd_dealer,
            //'keyword' => $this->input->get('keyword'),
            'tgl_awal' => tglToSql($data['date']['start_date']),
            'tgl_akhir' => tglToSql($data['date']['end_date']),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.NO_RANGKA=TRANS_FU_SERVICE.NO_RANGKA AND SJD.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_SJKELUAR SJ", "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_FU_SERVICE_DETAIL FD", "FD.NO_TRANS=TRANS_FU_SERVICE.NO_TRANS AND FD.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_FU_SERVICE.KD_DEALER", "LEFT")
            ),
            'field' => "TRANS_FU_SERVICE.*,MD.KD_DEALERAHM, FD.KD_SETUP_STATUSSMS,FD.KD_SETUP_STATUSCALL,FD.KD_STATUS_METODEFU,FD.NAMA_METODEFU, FD.NAMA_METODEFU2,FD.TGL_METODEFU, FD.STATUS_METODEFU, FD.HASIL_METODEFU, FD.STATUS_METODEFU2,FD.TGL_METODEFU2, FD.HASIL_METODEFU2, SJ.NO_SURATJALAN, SJ.TGL_TERIMA, CASE WHEN (SELECT COUNT(FUD.NO_TRANS) FROM TRANS_FU_SERVICE_DETAIL AS FUD WHERE FUD.NO_TRANS = TRANS_FU_SERVICE.NO_TRANS AND (FUD.HASIL_METODEFU = 'Servis' OR FUD.HASIL_METODEFU2 != ''))>0 THEN 1 ELSE 0 END NEXT_FU",
            'orderby' => 'SJ.TGL_TERIMA asc',
            'limit' => 15
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }

        if (tglToSql($data['date']['start_date'])) {
            $param["custom"] = " CONVERT(CHAR,TRANS_FU_SERVICE.TGL_TRANS,112) BETWEEN '" . tglToSql($data['date']['start_date']) . "' AND '" . tglToSql($data['date']['end_date']) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TRANS_FU_SERVICE.TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_service", $param));
        /*$param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $param_area = array( 
            'jointable' => array(
                array("MASTER_DEALER MD", "MD.KD_DEALER=USERS_AREA.KD_DEALER", "LEFT")
            ),
            "custom" => "USERS_AREA.USER_ID='".$this->session->userdata('user_id')."' AND USERS_AREA.AUTH_STATUS > 0"
        );
        $data["dealer_area"] = json_decode($this->curl->simple_get(API_URL . "/api/menu/users_area", $param_area));
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }*/
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
        $this->template->site('report/service_reminder/view_sfu', $data); //, $data
    }
    /**
     * [download_sr description]
     * @return [type] [description]
     */
    public function download_sr() {
        $data = array();
        $paramcustomer = array();
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_FU_SERVICE.KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "TRANS_FU_SERVICE.KD_DEALER ='" . $this->session->userdata("kd_dealer");
        $data['date'] = $this->get_date_perweek();
        $param = array(
            'custom' => $kd_dealer,
            'keyword' => $this->input->get('keyword'),
            'tgl_awal' => tglToSql($data['date']['start_date']),
            'tgl_akhir' => tglToSql($data['date']['end_date']),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("TRANS_SJKELUAR_DETAIL SJD", "SJD.NO_RANGKA=TRANS_FU_SERVICE.NO_RANGKA AND SJD.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_SJKELUAR SJ", "SJ.ID=SJD.ID_SURATJALAN AND SJ.ROW_STATUS >= 0", "LEFT"),
                array("TRANS_FU_SERVICE_DETAIL FD", "FD.NO_TRANS=TRANS_FU_SERVICE.NO_TRANS AND FD.ROW_STATUS >= 0", "LEFT"),
                array("MASTER_DEALER MD", "MD.KD_DEALER=TRANS_FU_SERVICE.KD_DEALER", "LEFT")
            ),
            'field' => "TRANS_FU_SERVICE.*,MD.KD_DEALERAHM, FD.KD_SETUP_STATUSSMS,FD.KD_SETUP_STATUSCALL,FD.KD_STATUS_METODEFU,FD.NAMA_METODEFU, FD.NAMA_METODEFU2,FD.TGL_METODEFU, FD.STATUS_METODEFU, FD.HASIL_METODEFU, FD.STATUS_METODEFU2,FD.TGL_METODEFU2, FD.HASIL_METODEFU2, SJ.NO_SURATJALAN, SJ.TGL_TERIMA, CASE WHEN (SELECT COUNT(FUD.NO_TRANS) FROM TRANS_FU_SERVICE_DETAIL AS FUD WHERE FUD.NO_TRANS = TRANS_FU_SERVICE.NO_TRANS AND (FUD.HASIL_METODEFU = 'Servis' OR FUD.HASIL_METODEFU2 != ''))>0 THEN 1 ELSE 0 END NEXT_FU",
            'orderby' => 'SJ.TGL_TERIMA asc',
            'limit' => 15
        );
        if (tglToSql($data['date']['start_date'])) {
            $param["custom"] = " CONVERT(CHAR,TRANS_FU_SERVICE.TGL_TRANS,112) BETWEEN '" . tglToSql($data['date']['start_date']) . "' AND '" . tglToSql($data['date']['end_date']) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TRANS_FU_SERVICE.TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data = json_decode($this->curl->simple_get(API_URL . "/api/master_hc3/fu_service", $param));
        return $data;
    }
    /**
     * [createfile_sr description]
     * @return [type] [description]
     */
    public function createfile_sr() {
        $data = array();
        $data = $this->download_sr();
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $this->session->userdata("kd_maindealer") . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".SFU"; //
            $isifile .= $this->session->userdata("kd_maindealer") . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ";";
            $isifile .= $row->KD_METODEFU . ";";
            $isifile .= $row->KD_DEALERAHM . ";"; //id dealer ahm
            $isifile .= $row->KD_CUSTOMER . ";"; // id customer
            $isifile .= ";"; //$isifile .= $row->KD_HSALES . ";"; //fk honda id
            $isifile .= $row->NO_RANGKA . ";";
            $isifile .= $row->NO_MESIN . ";";
            $isifile .= $row->NO_POLISI . ";";
            $isifile .= $row->TGL_BELI . ";";
            $isifile .= $row->NAMA_STNK . ";";
            $isifile .= $row->NO_HP . ";";
            $isifile .= $row->ALAMAT_SURAT . ";";
            $isifile .= $row->KELURAHAN_SURAT . ";";
            $isifile .= $row->KECAMATAN_SURAT . ";";
            $isifile .= $row->KOTA_SURAT . ";";
            $isifile .= $row->KODE_POS . ";";
            $isifile .= $row->PROPINSI_SURAT . ";";
            $isifile .= $this->session->userdata("kd_maindealer") . $row->KD_DEALER . substr($row->TGL_TRANS, 0, 4) . substr($row->TGL_TRANS, 5, 2) . substr($row->NO_TRANS, 13, 5) . ";";
            $isifile .= $row->KD_METODEFU . ";";
            $isifile .= $row->KD_SETUP_STATUSSMS . ";";
            $isifile .= $row->KD_SETUP_STATUSCALL . ";";
            $isifile .= $row->KD_STATUS_METODEFU . ";";
            $isifile .= $row->NAMA_METODEFU . ";";
            $isifile .= $row->TGL_METODEFU . ";";
            $isifile .= $row->STATUS_METODEFU . ";";
            $isifile .= $row->HASIL_METODEFU . ";";
            $isifile .= $row->NAMA_METODEFU2 . ";";
            $isifile .= $row->TGL_METODEFU2 . ";";
            $isifile .= $row->STATUS_METODEFU2 . ";";
            $isifile .= $row->HASIL_METODEFU2 . ";" . PHP_EOL;
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    /**
     * [file_udprg description]
     * @return [type] [description]
     */
    public function file_udprg() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'field' => 'NO_TRANS, DIRECTORY_FILE',
            'groupby' => TRUE
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/file", $param));
        $param_detail = array(
            'jointable' => array(
                array("TRANS_FILE_UDPRG_VIEW AS FUV", "FUV.NO_RANGKA=TRANS_FILE.NO_RANGKA", "LEFT"),
            ),
            'field' => 'TRANS_FILE.NO_TRANS, TRANS_FILE.DIRECTORY_FILE, FUV.*',
            'keyword' => $this->input->get('keyword')
        );
        $data["detail"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/file", $param_detail));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        // $this->output->set_output(json_encode($data["detail"]));
        $this->template->site('sales/list_file', $data);
    }
    /**
     * [download_file_udprg description]
     * @return [type] [description]
     */
    public function download_file_udprg() {
        $param = array(
            'kd_dealer' => $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'custom' => "NO_RANGKA NOT IN (SELECT P.NO_RANGKA FROM TRANS_FILE AS P WHERE P.ROW_STATUS >= 0)",
            'field' => "TRANS_FILE_UDPRG_VIEW.* ,
                        (SELECT JAWABAN FROM TRANS_KUISONER AS M WHERE M.KETERANGAN = '7_digunakan_untuk' AND M.KD_CUSTOMER = TRANS_FILE_UDPRG_VIEW.KD_CUSTOMER AND M.ROW_STATUS >=0 AND M.SPK_ID = TRANS_FILE_UDPRG_VIEW.SPK_ID) AS JAWABAN ,
                        (SELECT JAWABAN FROM TRANS_KUISONER AS C WHERE C.KETERANGAN = '8_yang_menggunakan' AND C.KD_CUSTOMER = TRANS_FILE_UDPRG_VIEW.KD_CUSTOMER AND C.ROW_STATUS >=0 AND C.SPK_ID = TRANS_FILE_UDPRG_VIEW.SPK_ID) AS JAWABAN1"
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/file_udprg", $param, array(CURLOPT_BUFFERSIZE => 10)));
        if ($data && (is_array($data->message) || is_object($data->message))) {
            $no_trans = $this->autogenerate_trans('FL');
            $path_file = 'assets/uploads/FILE-' . time();
            // var_dump($nama_file);exit;
            if (!is_dir($path_file)) {
                mkdir($path_file, 0777, true);
            }
            for ($i = 1; $i <= 4; $i++) {
                switch ($i) {
                    case 1:
                        $tipe_file = 'UDSTK';
                        break;
                    case 2:
                        $tipe_file = 'CDB';
                        break;
                    case 3:
                        $tipe_file = 'UDPRG';
                        break;
                    case 4:
                        $tipe_file = 'TXT';
                        break;
                }
                $hasil = $this->createfile_updrg($data, $path_file, $tipe_file, $no_trans);
            }
            if ($hasil == true) {
                foreach ($data->message as $key => $value) {
                    $param_post = array(
                        'no_trans' => $no_trans,
                        'no_rangka' => $value->NO_RANGKA,
                        'directory_file' => $path_file,
                        'kd_maindealer' => $value->KD_MAINDEALER,
                        'kd_dealer' => $value->KD_DEALER,
                        'created_by' => $this->session->userdata('user_id')
                    );
                    $hasil_post = $this->curl->simple_post(API_URL . "/api/laporan/file", $param_post, array(CURLOPT_BUFFERSIZE => 10));
                }
                $data_return = array(
                    'status' => true,
                    'message' => 'data berahasil didownload',
                    'file' => base_url() . 'report/download_allfile?namafile=' . $path_file
                );
            }
        } else {
            $data_return = array(
                'status' => false,
                'message' => 'Tidak ada data untuk didownload'
            );
        }
        // var_dump($cetak_sdkpb);exit;
        $this->output->set_output(json_encode($data_return));
    }
    /**
     * [createfile_updrg description]
     * @param  [type] $data      [description]
     * @param  [type] $path_file [description]
     * @param  [type] $tipe_file [description]
     * @param  [type] $no_trans  [description]
     * @return [type]            [description]
     */
    public function createfile_updrg($data, $path_file, $tipe_file, $no_trans) {
        // $data=array();
        $namafile = "";
        $isifile = "";
        if ($data && (is_array($data->message) || is_object($data->message))):
            foreach ($data->message as $key => $row) {
                if ($tipe_file == 'UDSTK'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . "-" . $no_trans . ".UDSTK";
                    $isifile .= $row->NO_RANGKA . ";";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, -7) . ";";
                    $isifile .= $row->NAMA_CUSTOMER . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->KD_DESA . ";";
                    $isifile .= $row->KD_KECAMATAN . ";";
                    $isifile .= $row->KD_KOTA . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->JENIS_PENJUALAN . ";";
                    $isifile .= $row->KD_DEALER . ";";
                    $isifile .= $row->KD_FINCOY . ";";
                    $isifile .= number_format($row->UANG_MUKA, 0) . ";";
                    $isifile .= $row->JANGKA_WAKTU . ";";
                    $isifile .= number_format($row->JUMLAH_ANGSURAN, 0) . ";" . PHP_EOL;
                elseif ($tipe_file == 'CDB'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".CDB";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, 5, 7) . ";";
                    $isifile .= $row->NO_KTP . ";";
                    // $isifile .= $row->KD_TYPEMOTOR . ";";
                    // $isifile .= $row->KD_CUSTOMER . ";";
                    $isifile .= $row->JENIS_KELAMIN . ";";
                    $isifile .= str_replace("/", "", tglFromSql($row->TGL_LAHIR)) . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->NAMA_DESA . ";";
                    $isifile .= $row->NAMA_KECAMATAN . ";";
                    $isifile .= $row->NAMA_KABUPATEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->KD_AGAMA . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->PENGELUARAN . ";";
                    $isifile .= $row->KD_PENDIDIKAN . ";";
                    $isifile .= $row->NAMA_PENANGGUNGJAWAB . ";";
                    $isifile .= $row->NO_HP . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->STATUS_DIHUBUNGI . ";";
                    $isifile .= $row->NAMA_ITEM . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    $isifile .= $row->JAWABAN . ";";
                    $isifile .= $row->JAWABAN1 . ";";
                    $isifile .= $row->KD_ITEM . ";";
                    $isifile .= $row->NAMA_SALES . ";";
                    $isifile .= $row->EMAIL . ";";
                    $isifile .= $row->STATUS_RUMAH . ";";
                    $isifile .= $row->STATUS_NOHP . ";";
                    $isifile .= $row->AKUN_FB . ";";
                    $isifile .= $row->AKUN_TWITTER . ";";
                    $isifile .= $row->AKUN_INSTAGRAM . ";";
                    $isifile .= $row->AKUN_YOUTUBE . ";";
                    $isifile .= $row->HOBI . ";";
                    $isifile .= $row->KARAKTERISTIK_KONSUMEN . ";";
                    $isifile .= $row->ID_REFFERAL . ";" . PHP_EOL;
                elseif ($tipe_file == 'UDPRG'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".UDPRG";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, 5, 7) . ";";
                    $isifile .= $row->NO_KTP . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    $isifile .= $row->KD_CUSTOMER . ";";
                    $isifile .= $row->JENIS_KELAMIN . ";";
                    $isifile .= str_replace("/", "", tglFromSql($row->TGL_LAHIR)) . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->NAMA_DESA . ";";
                    $isifile .= $row->NAMA_KECAMATAN . ";";
                    $isifile .= $row->NAMA_KABUPATEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->KD_AGAMA . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->PENGELUARAN . ";";
                    $isifile .= $row->KD_PENDIDIKAN . ";";
                    $isifile .= $row->NAMA_PENANGGUNGJAWAB . ";";
                    $isifile .= $row->NO_HP . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->STATUS_DIHUBUNGI . ";";
                    $isifile .= $row->NAMA_ITEM . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    $isifile .= $row->JAWABAN . ";";
                    $isifile .= $row->JAWABAN1 . ";";
                    $isifile .= $row->KD_ITEM . ";";
                    $isifile .= $row->NAMA_SALES . ";";
                    $isifile .= $row->EMAIL . ";";
                    $isifile .= $row->STATUS_RUMAH . ";";
                    $isifile .= $row->STATUS_NOHP . ";";
                    $isifile .= $row->AKUN_FB . ";";
                    $isifile .= $row->AKUN_TWITTER . ";";
                    $isifile .= $row->AKUN_INSTAGRAM . ";";
                    $isifile .= $row->AKUN_YOUTUBE . ";";
                    $isifile .= $row->HOBI . ";";
                    $isifile .= $row->KARAKTERISTIK_KONSUMEN . ";";
                    $isifile .= $row->ID_REFFERAL . ";" . PHP_EOL;
                elseif ($tipe_file == 'TXT'):
                    $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALERAHM . "-" . date('YmdHis') . ".TXT";
                    $isifile .= substr($row->NO_MESIN, 0, 5) . ";";
                    $isifile .= substr($row->NO_MESIN, 5, 7) . ";";
                    $isifile .= $row->NO_KTP . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    $isifile .= $row->KD_CUSTOMER . ";";
                    $isifile .= $row->JENIS_KELAMIN . ";";
                    $isifile .= str_replace("/", "", tglFromSql($row->TGL_LAHIR)) . ";";
                    $isifile .= $row->ALAMAT_SURAT . ";";
                    $isifile .= $row->NAMA_DESA . ";";
                    $isifile .= $row->NAMA_KECAMATAN . ";";
                    $isifile .= $row->NAMA_KABUPATEN . ";";
                    $isifile .= $row->KODE_POS . ";";
                    $isifile .= $row->KD_PROPINSI . ";";
                    $isifile .= $row->KD_AGAMA . ";";
                    $isifile .= $row->KD_PEKERJAAN . ";";
                    $isifile .= $row->PENGELUARAN . ";";
                    $isifile .= $row->KD_PENDIDIKAN . ";";
                    $isifile .= $row->NAMA_PENANGGUNGJAWAB . ";";
                    $isifile .= $row->NO_HP . ";";
                    $isifile .= $row->NO_TELEPON . ";";
                    $isifile .= $row->STATUS_DIHUBUNGI . ";";
                    $isifile .= $row->NAMA_ITEM . ";";
                    $isifile .= $row->KD_TYPEMOTOR . ";";
                    $isifile .= $row->JAWABAN . ";";
                    $isifile .= $row->JAWABAN1 . ";";
                    $isifile .= $row->KD_ITEM . ";";
                    $isifile .= $row->NAMA_SALES . ";";
                    $isifile .= $row->EMAIL . ";";
                    $isifile .= $row->STATUS_RUMAH . ";";
                    $isifile .= $row->STATUS_NOHP . ";";
                    $isifile .= $row->AKUN_FB . ";";
                    $isifile .= $row->AKUN_TWITTER . ";";
                    $isifile .= $row->AKUN_INSTAGRAM . ";";
                    $isifile .= $row->AKUN_YOUTUBE . ";";
                    $isifile .= $row->HOBI . ";";
                    $isifile .= $row->KARAKTERISTIK_KONSUMEN . ";";
                    $isifile .= $row->ID_REFFERAL . ";" . PHP_EOL;
                endif;
                // $isifile .= $row->STATUS_SJ.";";
            }
        endif;
        $this->load->helper('file');
        if (write_file(FCPATH . $path_file . '/' . $namafile, $isifile) == TRUE) {
            foreach ($data->message as $key => $rows) {
                $param = array(
                    'id' => $rows->ID,
                    'status_kpb' => 2,
                    'lastmodified_by' => $this->session->userdata('user_id')
                );
                $data = json_decode($this->curl->simple_put(API_URL . "/api/service/kpb_validasi_status", $param));
            }
            $return = true;
        } else {
            $return = false;
        }
        return $return;
        // return $data_return;
        // $this->output->set_output(json_encode($data_return));        
    }
    /**
     * [download_allfile description]
     * @return [type] [description]
     */
    public function download_allfile() {
        $namafile = 'FILE-' . time() . '.zip';
        $folder_in_zip = '/'; //root directory of the new zip file
        $path = $this->input->get('namafile') . '/';
        $this->load->library(array('Zip', 'MY_Zip'));
        $this->zip->get_files_from_folder($path, $folder_in_zip);
        $this->zip->download($namafile);
    }
    /**
     * [podetail_list description]
     * @return [type] [description]
     */
    public function ufu_cetak() {
        $data['date'] = $this->get_date_perweek();
        $kd_dealer = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
        $tgl = "convert(char,TANGGAL,112) between '" . tglToSql($data['date']['start_date']) . "' AND '" . tglToSql($data['date']['end_date']) . "'";
        $params = array(
            'kd_dealer' => $kd_dealer,
            'custom' => $tgl,
            'field' => "
                    KD_MAINDEALER,
                    KD_DEALER,
                    KD_METODE_FU,
                    KD_STATUS_FU,
                    KD_HASIL_METODE,
                    STATUS,
                    METODE_FU,
                    TGL_FU,
                    STATUS_FU,
                    HASIL_METODE,
                    RENCANA_FU,
                    KETERANGAN,
                    KD_CUSTOMER,
                    TIPE,
                    TANGGAL,
                    GUEST_ID,
                    ROW_STATUS,
                    JUMLAH
                    "
        );
        $data = json_decode($this->curl->simple_get(API_URL . "/api/laporan/crm_ufu", $params));
        // $this->output->set_output(json_encode($data));
        // var_dump($data);
        return $data;
    }
    /**
     * [createfile_udpo description]
     * @return [type] [description]
     */
    public function download_ufu() {
        $data = array();
        $data = $this->ufu_cetak();
        // var_dump($data);exit;
        $namafile = "";
        $isifile = "";
        foreach ($data->message as $key => $row) {
            $namafile = $row->KD_MAINDEALER . "-" . $row->KD_DEALER . "-" . date('ymdHis') . ".UFU";
            $isifile .= $row->KD_METODE_FU . ";";
            $isifile .= $row->KD_METODE_FU . ";";
            $isifile .= $row->KD_STATUS_FU . ";";
            $isifile .= $row->KD_HASIL_METODE . ";";
            $isifile .= $row->STATUS . ";";
            $isifile .= $row->METODE_FU . ";";
            $isifile .= tglfromSql($row->TGL_FU) . ";";
            $isifile .= $row->STATUS_FU . ";";
            $isifile .= $row->HASIL_METODE . ";";
            $isifile .= $row->RENCANA_FU . ";";
            $isifile .= $row->KETERANGAN . PHP_EOL;
            // $isifile .= $row->STATUS_SJ.";";
        }
        $this->load->helper("download");
        force_download($namafile, $isifile);
    }
    /**
     * [autogenerate_trans description]
     * @param  [type] $kd_docno [description]
     * @return [type]           [description]
     */
    function autogenerate_trans($kd_docno) {
        $no_trans = "";
        $nomortrans = 0;
        $param = array(
            'kd_docno' => $kd_docno,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date('Y'), // substr($this->input->post('tgl_trans'), 6, 4),
            // 'bulan_docno' => date('m'),
            'nama_docno' => 'NO TRANS File UDPRG',
            'limit' => 1,
            'created_by' => $this->session->userdata('user_id'),
            'lastmodified_by' => $this->session->userdata('user_id'),
            'offset' => 0
        );
        //print_r($param);
        $bulan_kirim = date('m'); // substr($this->input->post('tgl_trans'), 3, 2);
        $nomortrans = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomortrans == 0) {
            $no_trans = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
            $param['urutan_docno'] = $nomortrans + 1;
            $this->curl->simple_post(API_URL . "/api/setup/setup_docno", $param, array(CURLOPT_BUFFERSIZE => 10));
        } else {
            $nomor_trans = $nomortrans + 1;
            $no_trans = $kd_docno . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomor_trans, 5, '0', STR_PAD_LEFT);
            $param['urutan_docno'] = $nomortrans;
            $this->curl->simple_put(API_URL . "/api/setup/docno", $param, array(CURLOPT_BUFFERSIZE => 10));
        }
        return $no_trans;
    }
    /**
     * [report_lhb description]
     * @return [type] [description]
     */
    function report_lhb() {
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'",
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'TRANS_PKB.NO_PKB',
            'jointable' => array(
                array("TRANS_CSA AS CSA", "CSA.KD_SA = TRANS_PKB.KD_SA", "LEFT"),
                array("MASTER_KARYAWAN AS KR", "KR.NIK = TRANS_PKB.NAMA_MEKANIK", "LEFT")
            ),
            'field' => "TRANS_PKB.*, CSA.NAMA_COMINGCUSTOMER, CSA.KD_TIPEPKB, CSA.KD_TYPECOMINGCUSTOMER, KR.NAMA",
            'limit' => 50
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
         $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        // var_dump($paramcustomer);exit;$string = link_pagination();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/report_lhb', $data);
    }
    /**
     * [report_lhb_detail description]
     * @param  [type] $no_pkb [description]
     * @return [type]         [description]
     */
    public function report_lhb_detail($no_pkb) {
        $this->auth->validate_authen('report/report_lhb');
        $param = array(
            'no_pkb' => $no_pkb,
            'jointable' => array(
                array("TRANS_CSA AS CSA", "CSA.KD_SA = TRANS_PKB.KD_SA", "LEFT"),
                array("MASTER_KARYAWAN AS KR", "KR.NIK = TRANS_PKB.NAMA_MEKANIK", "LEFT")
            ),
            'field' => "TRANS_PKB.*, CSA.NAMA_COMINGCUSTOMER, CSA.KD_TIPEPKB, CSA.KD_TYPECOMINGCUSTOMER, KR.NAMA",
        );
        $data = array();
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->load->view('report/report_lhb_detail', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [report_lhb_print description]
     * @return [type] [description]
     */
    public function report_lhb_print() {
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'",
            'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'orderby' => 'ID DESC',
            'jointable' => array(
                array("TRANS_CSA AS CSA", "CSA.KD_SA = TRANS_PKB.KD_SA", "LEFT"),
                array("MASTER_KARYAWAN AS KR", "KR.NIK = TRANS_PKB.NAMA_MEKANIK", "LEFT")
            ),
            'field' => "TRANS_PKB.*, CSA.NAMA_COMINGCUSTOMER, CSA.KD_TIPEPKB, CSA.KD_TYPECOMINGCUSTOMER, KR.NAMA",
            'limit' => 50
        );
        if ($this->input->get("tanggal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) = '" . tglToSql($this->input->get("tanggal")) . "'";
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
        // var_dump($paramcustomer);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        $this->load->view('report/report_lhb_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [report_unhandledcust description]
     * @return [type] [description]
     */
    function report_unhandledcust() {
        $data = array();
        $paramcustomer = array();
        $param = array(
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $this->template->site('report/report_unhandledcust', $data);
    }
    /**
     * [report_unhandledcust_print description]
     * @return [type] [description]
     */
    function report_unhandledcust_print() {
        $data = array();
        $paramcustomer = array();
        $param = array(
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        $this->load->view('report/report_unhandledcust_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [report_mpp description]
     * @return [type] [description]
     */
    function report_mpp() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => $this->input->get('tahun'),
            'bulan' => $this->input->get('bulan')
        );

        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            //$param['where_in_field'] = 'KD_DEALER';
        }

        $data["dealer"]=json_decode($this->curl->simple_get(API_URL."/api/master/dealer",ListDealer()));
        // $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_lbb2", $param));
        $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_mpp", $param));
        $param = array(
            //'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'field' => "YEAR(TANGGAL_PKB) AS TAHUN",
            'groupby' => TRUE,
            'orderby' => "YEAR(TANGGAL_PKB) DESC"
        );
        $data["tahun"] = json_decode($this->curl->simple_get(API_URL . "/api/service/pkb", $param));
        $this->template->site('report/report_mpp', $data);
        // $this->output->set_output(json_encode($data));
    }
    /**
     * [report_mpp_print description]
     * @return [type] [description]
     */
    function report_mpp_print() {
        $param = array(
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'tahun' => $this->input->get('tahun'),
            'bulan' => $this->input->get('bulan')
        );
        $data["mekanik"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/laporan_mpp", $param));
        $this->load->view('report/report_mpp_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [lap_pinjaman_bpkb description]
     * @return [type] [description]
     */
    public function lap_pinjaman_bpkb($print=null) {
        $data = array();
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Ymd');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Ymd", strtotime($start_dates));
        $end_date = date("Ymd", strtotime($end_dates));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'start_date' =>$start_date,
            'end_date' => $end_date
        );
        if($print){
            unset($param["limit"]);
        }
        $param['kd_dealer'] = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
        
        $paramlap = array('query' => $this->Custom_model->lapPinjamanaBPKB($param));
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $paramlap));
        $paramlapx = array('query' => $this->Custom_model->lapPinjamanaBPKB($param,true));
        $data["total"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $paramlapx));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        if($print){
            $this->load->view('report/lap_pinjaman/lap_pinjaman_bpkb_print', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }else{
            $string = link_pagination();
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["total"]) ? $data["total"]->message[0]->ID : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            //var_dump( $data['pagination']);exit();
            $this->template->site('report/lap_pinjaman/lap_pinjaman_bpkb', $data);
        }
    }
    /**
     * [lap_pinjaman_bpkb_print description]
     * @return [type] [description]
     */
    public function lap_pinjaman_bpkb_print() {
        $this->lap_pinjaman_bpkb(true);
        /*$data = array();
        $this->auth->validate_authen('report/lap_pinjaman_bpkb');
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array('TRANS_STNK TS', 'TS.ID=TRANS_STNK_DETAIL.STNK_ID', 'LEFT')
            ),
            'field' => 'TRANS_STNK_DETAIL.*,TS.NO_TRANS',
            'orderby' => 'TRANS_STNK_DETAIL.ID desc',
            'custom' => 'REFF_SOURCE = 2 AND TGL_PINJAM IS NOT NULL AND TGL_BALIK IS NULL'
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('report/lap_pinjaman/lap_pinjaman_bpkb_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));*/
    }
    /**
     * [lap_pinjaman_stnk description]
     * @return [type] [description]
     */
    public function lap_pinjaman_stnk() {
        $data = array();
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Y-m-d');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array('TRANS_STNK TS', 'TS.ID=TRANS_STNK_DETAIL.STNK_ID', 'LEFT')
            ),
            'field' => 'TRANS_STNK_DETAIL.*,TS.NO_TRANS',
            'orderby' => 'TRANS_STNK_DETAIL.ID desc',
            "custom" => "TRANS_STNK_DETAIL.REFF_SOURCE = 1 AND TRANS_STNK_DETAIL.TGL_PINJAM IS NOT NULL AND TRANS_STNK_DETAIL.TGL_BALIK IS NULL AND TRANS_STNK_DETAIL.STATUS_PENGURUSAN = 1 AND TRANS_STNK_DETAIL.KD_DEALER='" .$this->session->userdata('kd_dealer'). "' AND TRANS_STNK_DETAIL.TGL_PINJAM BETWEEN '".$start_date."' AND '".$end_date."'"
        );

/*        if($this->input->get("kd_dealer")){
            $param['kd_dealer'] = $this->input->get("kd_dealer");
        }else{
            $param['where_in'] = isDealerAkses();
            $param['where_in_field'] = 'KD_DEALER';
        }*/

        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        //var_dump($data["list"]);exit;
        /* if ($this->input->get('row_status')) {
          $param['status_aktif'] = $this->input->get('row_status');
          } */
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/lap_pinjaman/lap_pinjaman_stnk', $data);
    }
    /**
     * [lap_pinjaman_stnk_print description]
     * @return [type] [description]
     */
    public function lap_pinjaman_stnk_print() {
        $data = array();
        $this->auth->validate_authen('report/lap_pinjaman_stnk');
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Y-m-d');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            //'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            //'limit' => 15,
            'jointable' => array(
                array('TRANS_STNK TS', 'TS.ID=TRANS_STNK_DETAIL.STNK_ID', 'LEFT')
            ),
            'field' => 'TRANS_STNK_DETAIL.*,TS.NO_TRANS',
            'orderby' => 'TRANS_STNK_DETAIL.ID desc',
            "custom" => "TRANS_STNK_DETAIL.REFF_SOURCE = 1 AND TRANS_STNK_DETAIL.TGL_PINJAM IS NOT NULL AND TRANS_STNK_DETAIL.TGL_BALIK IS NULL AND TRANS_STNK_DETAIL.STATUS_PENGURUSAN = 1 AND TRANS_STNK_DETAIL.KD_DEALER='" .$this->session->userdata('kd_dealer'). "' AND TRANS_STNK_DETAIL.TGL_PINJAM BETWEEN '".$start_date."' AND '".$end_date."'"
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('report/lap_pinjaman/lap_pinjaman_stnk_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [lap_pinjaman_stnkmanual description]
     * @return [type] [description]
     */
    public function lap_pinjaman_stnkmanual() {
        $data = array();
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Y-m-d');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'jointable' => array(
                array('TRANS_STNK TS', 'TS.ID=TRANS_STNK_DETAIL.STNK_ID', 'LEFT')
            ),
            'field' => 'TRANS_STNK_DETAIL.*,TS.NO_TRANS',
            'orderby' => 'TRANS_STNK_DETAIL.ID desc',
            "custom" => "TRANS_STNK_DETAIL.REFF_SOURCE = 1 AND TRANS_STNK_DETAIL.TGL_PINJAM IS NOT NULL AND TRANS_STNK_DETAIL.TGL_BALIK IS NULL AND TRANS_STNK_DETAIL.STATUS_PENGURUSAN = 2 AND TRANS_STNK_DETAIL.KD_DEALER='" .$this->session->userdata('kd_dealer'). "' AND TRANS_STNK_DETAIL.TGL_PINJAM BETWEEN '".$start_date."' AND '".$end_date."'"
        );
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        /* if ($this->input->get('row_status')) {
          $param['status_aktif'] = $this->input->get('row_status');
          } */
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        if ($this->session->userdata("nama_group") != "Root") {
            $param = array(
                'kd_dealer' => $this->session->userdata("kd_dealer")
            );
        } else {
            $param = array();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/lap_pinjaman/lap_pinjaman_stnkmanual', $data);
    }
    /**
     * [lap_pinjaman_stnkmanual_print description]
     * @return [type] [description]
     */
    public function lap_pinjaman_stnkmanual_print() {
        $data = array();
        $this->auth->validate_authen('report/lap_pinjaman_stnkmanual');
        $start_dates = ($this->input->get('start_date'))?tglToSql($this->input->get('start_date')):date('Y-m-d');
        $end_dates = ($this->input->get('end_date'))?tglToSql($this->input->get('end_date')):date('Ymd');
        $start_date = date("Y-m-d", strtotime($start_dates));
        $end_date = date("Y-m-d", strtotime($end_dates));
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            //'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            //'limit' => 15,
            'jointable' => array(
                array('TRANS_STNK TS', 'TS.ID=TRANS_STNK_DETAIL.STNK_ID', 'LEFT')
            ),
            'field' => 'TRANS_STNK_DETAIL.*,TS.NO_TRANS',
            'orderby' => 'TRANS_STNK_DETAIL.ID desc',
            "custom" => "TRANS_STNK_DETAIL.REFF_SOURCE = 1 AND TRANS_STNK_DETAIL.TGL_PINJAM IS NOT NULL AND TRANS_STNK_DETAIL.TGL_BALIK IS NULL AND TRANS_STNK_DETAIL.STATUS_PENGURUSAN = 2 AND TRANS_STNK_DETAIL.KD_DEALER='" .$this->session->userdata('kd_dealer'). "' AND TRANS_STNK_DETAIL.TGL_PINJAM BETWEEN '".$start_date."' AND '".$end_date."'"
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/stnkbpkb/stnk_detail", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('report/lap_pinjaman/lap_pinjaman_stnkmanual_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [lap_penerimaan_barang description]
     * @return [type] [description]
     */
    public function lap_penerimaan_barang($debug=null) {
        $data = array();
        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer'=>($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata('kd_dealer')
        );
        $param["d_tgl"] =($this->input->get("tgl_awal"))?$this->input->get("tgl_awal"):date("d/m/Y",strtotime("first day of this month"));
        $param["s_tgl"] =($this->input->get("tgl_akhir"))?$this->input->get("tgl_akhir"):date("d/m/Y");
        $param["detail"]=($this->input->get("tp"))?$this->input->get("tp"):"0";
        $param["j_trans"] = "1";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang_stock/true", $param));   
        if($debug){
            print_r($param);
            var_dump($data["list"]);
            exit();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        if($this->input->get("p")){
            $data["j_trans"] =   $param["detail"];
            $data["d_tgl"]  =   $param["d_tgl"];
            $data["s_tgl"]  =   $param["s_tgl"];
            $this->load->view('report/lap_barang/lap_penerimaan_barang_print', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }else{
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            //$this->template->site('report/penerimaan', $data);
            $this->template->site('report/lap_barang/lap_penerimaan_barang', $data);
        }
    }
    /**
     * [lap_penerimaan_barang_print description]
     * @return [type] [description]
     */
    public function lap_penerimaan_barang_print() {
        $data = array();
        //$this->auth->validate_authen('report/lap_penerimaan_barang');
        $param = array(
            //'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array('TRANS_UANGMASUK AS TU', 'TU.NO_TRANS=TRANS_UANGMASUK_DETAIL.NO_TRANS', 'LEFT'),
                array('PENERIMAAN_VIEW AS P', 'P.NO_TRANS=TRANS_UANGMASUK_DETAIL.NO_TRANS', 'LEFT')
            ),
            'field' => 'TRANS_UANGMASUK_DETAIL.*,TU.TGL_TRANS,P.KD_BARANG,P.NAMA_BARANG,P.JUMLAH'
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "' AND P.KATEGORI LIKE 'BARANG'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "' AND P.KATEGORI LIKE 'BARANG'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        //var_dump($data);        exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('report/lap_barang/lap_penerimaan_barang_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [Laporan_gantung description]
     */
    public function Laporan_gantung() {
        $data = array();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];
        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page'],
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer'),
            'jointable' => array(
                array('TRANS_UANGMASUK_DETAIL UD', 'UD.NO_TRANS=TRANS_UANGMASUK.NO_TRANS', 'LEFT')
            /* ,
              array('MASTER_CUSTOMER MC','MC.KD_CUSTOMER=TRANS_UANGMASUK.KD_CUSTOMER','LEFT') */
            ),
            'field' => "TRANS_UANGMASUK.*,
                        UD.URAIAN_TRANSAKSI,
                        UD.HARGA,UD.NO_URUT,
                        UD.SALDO_AWAL,
                        (UD.KD_ACCOUNT),
                        ISNULL(UD.LKH,0)LKH",
            'orderby' => 'TRANS_UANGMASUK.NO_TRANS DESC'
        );
        if ($this->input->get("tgl_trans_aw")) {
            $tgl_akhir = ($this->input->get('tgl_trans_ak')) ? tglToSql($this->input->get('tgl_trans_ak')) : tglToSql($this->input->get('tgl_trans_aw'));
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_trans_aw")) . "' AND '" . $tgl_akhir . "' AND UD.KD_ACCOUNT = '100.11399'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd') . "' AND '" . date('Ymd') . "' AND UD.KD_ACCOUNT = '100.11399'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/laporan_gantung', $data);
    }
    /**
     * [Laporan_gantung_print description]
     */
    public function Laporan_gantung_print() {
        $data = array();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];
        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page'],
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer'),
            'jointable' => array(
                array('TRANS_UANGMASUK_DETAIL UD', 'UD.NO_TRANS=TRANS_UANGMASUK.NO_TRANS', 'LEFT')
            ),
            'field' => "TRANS_UANGMASUK.*,
                        UD.URAIAN_TRANSAKSI,
                        UD.HARGA,UD.NO_URUT,
                        UD.SALDO_AWAL,
                        (UD.KD_ACCOUNT),
                        ISNULL(UD.LKH,0)LKH",
            'orderby' => 'TRANS_UANGMASUK.NO_TRANS DESC'
        );
        if ($this->input->get("tgl_trans_aw")) {
            $tgl_akhir = ($this->input->get('tgl_trans_ak')) ? tglToSql($this->input->get('tgl_trans_ak')) : tglToSql($this->input->get('tgl_trans_aw'));
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_trans_aw")) . "' AND '" . $tgl_akhir . "' AND UD.KD_ACCOUNT LIKE '100.11399'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd') . "' AND '" . date('Ymd') . "' AND UD.KD_ACCOUNT LIKE '100.11399'";
        }
        //print_r($param);
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk", $param));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $this->load->view('report/Laporan_gantung_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [lap_pengeluaran_barang description]
     * @return [type] [description]
     */
    public function lap_pengeluaran_barang($debug=null) {
        $data = array();
        $param = array(
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 15,
            'kd_dealer'=>($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata('kd_dealer')
        );
        $param["d_tgl"] =($this->input->get("tgl_awal"))?$this->input->get("tgl_awal"):date("d/m/Y",strtotime("first day of this month"));
        $param["s_tgl"] =($this->input->get("tgl_akhir"))?$this->input->get("tgl_akhir"):date("d/m/Y");
        $param["detail"]=($this->input->get("tp"))?$this->input->get("tp"):"0";
        $param["j_trans"]="2";
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/inventori/barang_stock/true", $param));   
        if($debug){
            print_r($param);
            var_dump($data["list"]);
            exit();
        }
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        if($this->input->get("p")){
            $data["j_trans"] =   $param["detail"];
            $data["d_tgl"]  =   $param["d_tgl"];
            $data["s_tgl"]  =   $param["s_tgl"];
            $this->load->view('report/lap_barang/lap_pengeluaran_barang_print', $data);
            $html = $this->output->get_output();
            $this->output->set_output(json_encode($html));
        }else{
            $config = array(
                'per_page' => $param['limit'],
                'base_url' => $string[0],
                'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
            );
            $pagination = $this->template->pagination($config);
            $this->pagination->initialize($pagination);
            $data['pagination'] = $this->pagination->create_links();
            //$this->template->site('report/penerimaan', $data);
            $this->template->site('report/lap_barang/lap_pengeluaran_barang', $data);
        }
    }
    /**
     * [lap_pengeluaran_barang_print description]
     * @return [type] [description]
     */
    public function lap_pengeluaran_barang_print() {
        $data = array();
        //$this->auth->validate_authen('report/lap_penerimaan_barang');
        $param = array(
            //'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer"),
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50,
            'jointable' => array(
                array('TRANS_UANGMASUK AS TU', 'TU.NO_TRANS=TRANS_UANGMASUK_DETAIL.NO_TRANS', 'LEFT'),
                array('PENGELUARAN_VIEW AS P', 'P.NO_TRANS=TRANS_UANGMASUK_DETAIL.NO_TRANS', 'LEFT')
            ),
            'field' => 'TRANS_UANGMASUK_DETAIL.*,TU.TGL_TRANS,P.KD_BARANG,P.NAMA_BARANG,P.JUMLAH'
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->get('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "' AND P.KATEGORI LIKE 'BARANG'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TGL_TRANS,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "' AND P.KATEGORI LIKE 'BARANG'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        //var_dump($data);        exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $this->load->view('report/lap_barang/lap_pengeluaran_barang_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    /**
     * [tagihan description]
     * @param  [type] $debug [description]
     * @return [type]        [description]
     */
    function tagihan($debug=null,$tagihan=null) {
        $data = array();
        $kd_dealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
        $param = array(
            'orderby' =>"KD_PIUTANG,NO_TRANS",
        );
        if($kd_dealer){
            $param["kd_dealer"] = $kd_dealer;
        }
        if($this->input->get("sts")) {
            if($this->input->get("sts")!="2"){
                $param['status_piutang'] = $this->input->get("sts");
            }
        }
        if(!$tagihan){
            $bln = ($this->input->get("bln"))?$this->input->get("bln"):date("m");
            $thn = ($this->input->get("thn"))?$this->input->get("thn"):date("Y");
            $param['custom'] = "MONTH(TGL_TRANS)='".$bln."' AND YEAR(TGL_TRANS)=".$thn;
        }else{
            $param["custom"] ="STATUS_PIUTANG=1";
            if($this->input->get("bln")){
                
            }
        }
        if($this->input->get("m")=='apv'){
            $apv=((int)isApproval("PIUNT")-1);
            $param['custom'] = "STATUS_PIUTANG=".strval($apv);
            $data["nontunai_apv"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/1", $param));
            $param=array(
                'custom'=>"REPRINT=1"
            );
            $data["program"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/4", $param));
        }else{
            if($tagihan){
                if($this->input->get("bln")){
                    $param["custom"] = "(STATUS_PIUTANG>0 AND SISA_TAGIHAN >=0)";
                    $bln = $this->input->get("bln");
                    $thn = ($this->input->get("thn"))?$this->input->get("thn"):date("Y");
                    $param['custom'] .= " AND MONTH(TGL_TRANS)='".$bln."' AND YEAR(TGL_TRANS)=".$thn;
                }else{
                    $param["custom"] = "(STATUS_PIUTANG>0 AND SISA_TAGIHAN >0)";
                }
            }
            
        }
        $data["nontunai"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/1", $param));
        if($debug){
            var_dump($data["nontunai"]);
            print_r($param);
            exit();
        }
        unset($param["orderby"]);
        unset($param["apv_piutang"]);
        $param["orderby"] ="KD_FINCOY,NO_TRANS";
        //$param["custom"] ="SISA_TAGIHAN >0";
        $data["leasing"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/2", $param));
        $data["kupon"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/3", $param));
        $data["promo"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/5", $param));
        unset($param["orderby"]);
        $param["orderby"] ="NO_TRANS,TAGIHANKE";
        $param["custom"] .= " AND TAGIHANKE NOT IN('DEALER')";
        $data["program"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/4", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' =>0,// $param['limit'],
            'base_url' => $string[0],
            'total_rows' => isset($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        if($tagihan){
            $this->template->site('report/tagihan/pelunasan_ar', $data);
        }else{
            $this->template->site('report/tagihan/tagihan_new', $data);
        }
    }

    /**
     * [tagihan_approval description]
     * @return [type] [description]
     */
    function tagihan_approval(){
        $hasil=array();
        $data=json_decode($this->input->post('data'),true);
        if($data){
            for ($i=0; $i < count($data); $i++) {
                $param=array(
                    'no_trans' => $data[$i]["no_trans"],
                    'lastmodified_by' => $this->session->userdata("user_id"),
                    'status_piutang' => "1",
                    'apv_piutang'  => $data[$i]["apv"],
                    'apv_date' => date('d/m/Y') 
                );
                $hasil=($this->curl->simple_put(API_URL . "/api/accounting/piutang_apv", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        $this->data_output($hasil,'put');
    }
    /**
     * Aproval tagihan per notrans
     */
    function tagihan_apv(){
        $hasil=array();
        $param=array(
            'no_trans' => $this->input->get("no_trans"),
            'lastmodified_by' => $this->session->userdata("user_id"),
            'status_piutang' => $this->input->get("statuse"),
            'apv_piutang'  => $this->session->userdata("user_id"),
            'apv_date' => date('d/m/Y') 
        );
        $hasil=($this->curl->simple_put(API_URL . "/api/accounting/piutang_apv", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $this->data_output($hasil,'put');
    }
    function tagihan_dtl($no_trans=null){
        $param=array(
            'no_reff' =>$no_trans,
        );
        $data =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/1", $param));
        $this->output->set_output(json_encode($data));
    }
    function tagihan_lsg_print($no_spk,$jenis=null,$kupon=null){
        $param=array(
            'no_trans' =>$no_spk,
        );
        $modelkwt="";
        switch($jenis){
            case 'kupon':
                $param["kd_kupon"] = $kupon;
                $data["kupon"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/3", $param));
                $modelkwt ="kwitansi_print";
            break;
            case 'program':
                $param["tagihanke"] = $kupon;
                $param["custom"]    = "TAGIHANKE NOT IN('DEALER')";
                $data["program"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/4", $param));
                $modelkwt ="kwitansi3_print";
            break;
            case 'promo':
                $param["kd_fincoy"] = $kupon;
                //$param["no_trans"]    = "TAGIHANKE NOT IN('DEALER')";
                $data["program"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/5", $param));
                $modelkwt ="kwitansi4_print";
            break;
            default:
                $data["leasing"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/2", $param));
                $modelkwt ="kwitansi2_print";
            break;
        }
        $data["browser"] = $this->agent->browser();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true"));
        $data["finco"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        $usr=(strlen($this->session->userdata("user_id"))>22)?substr($this->session->userdata("user_id"),0,22):$this->session->userdata("user_id");
        $param=array(
          'kd_dealer' => $this->session->userdata("kd_dealer"),
          'kd_docno'  => "KS-".strtoupper($usr),
          'custom'    => "LAST_DOCNO < TO_DOCNO"
        );
        $data["kwt"] = json_decode($this->curl->simple_get(API_URL."/api/setup/docno_users",$param));
        $this->load->view('report/tagihan/'.$modelkwt, $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function tagihan_bayar($no_spk,$jenis=null,$kupon=null){
        $param=array(
            'no_trans' =>$no_spk,
        );
        $modelkwt="";
        switch($jenis){
            case 'kupon':
                $param["kd_kupon"] = $kupon;
                $data["kupon"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/3", $param));
                $data["jenis"] =$jenis;
            break;
            case 'program':
                $param["tagihanke"] = $kupon;
                //$param["tagihanke"]    = $this->input->get('t');
                $param["custom"]    = "TAGIHANKE NOT IN('DEALER')";
                $data["program"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/4", $param));
                $data["jenis"] =$jenis;
            break;
            case 'promo':
                $param["kd_fincoy"] = $kupon;
                $data["program"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/5", $param));
                $data["jenis"] =$jenis;
            break;
            default:

                $data["leasing"] =json_decode($this->curl->simple_get(API_URL . "/api/accounting/piutang/1/2", $param));
                $data["jenis"] =$jenis;
            break;
        }
        // var_dump($data);exit();
        $data["browser"] = $this->agent->browser();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer/true"));
        $data["finco"] = json_decode($this->curl->simple_get(API_URL . "/api/master/company_finance"));
        $usr=(strlen($this->session->userdata("user_id"))>22)?substr($this->session->userdata("user_id"),0,22):$this->session->userdata("user_id");
        $param=array(
          'kd_dealer' => $this->session->userdata("kd_dealer"),
          'kd_docno'  => "KS-".strtoupper($usr),
          'custom'    => "LAST_DOCNO < TO_DOCNO"
        );
        $data["kwt"] = json_decode($this->curl->simple_get(API_URL."/api/setup/docno_users",$param));
        $data["transbank"] =null;
        $this->load->view('report/tagihan/pelunasan', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function simpan_tagihan(){
        $param=array();
        $param["kd_maindealer"]     = $this->input->post("kd_maindealer");
        $param["kd_dealer"]         = $this->input->post("kd_dealer");
        $param["no_trans"]          = $this->input->post("no_trans");
        $param["tgl_trans"]         = ($this->input->post("tgl_trans"));
        $param["kd_piutang"]        = $this->input->post("kd_piutang");
        $param["tgl_piutang"]       = ($this->input->post("tgl_piutang"));
        $param["reff_piutang"]      = $this->input->post("reff_piutang");
        $param["uraian_piutang"]    = $this->input->post("uraian_piutang");
        $param["jumlah_piutang"]    = $this->input->post("jumlah_piutang");
        $param["cara_bayar"]        = $this->input->post("cara_bayar");
        $param["tgl_tempo"]         = ($this->input->post("tgl_tempo"));
        $param["status_piutang"]    = $this->input->post("status_piutang");
        $param["lastmodified_by"]   = $this->session->userdata("user_id");
        $hasil=($this->curl->simple_post(API_URL . "/api/accounting/piutang", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $hasilx=json_decode($hasil);
        if($hasil){
            if($hasilx->recordexists==true){
              $param["lastmodified_by"] = $this->session->userdata("user_id");
              $hasil=($this->curl->simple_put(API_URL . "/api/accounting/piutangv", $param, array(CURLOPT_BUFFERSIZE => 10)));
          }
        }
        $this->data_output($hasil,'post');
    }
    /**
     * [lap_pmodal description]
     * @param  [type] $seleksi [description]
     * @return [type]          [description]
     */
    function lap_pmodal() {
        //$data = array(); 
        $data['date'] = $this->get_date_perweek();
        $config['per_page'] = '15';
        $data['per_page'] = $config['per_page'];
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => $config['per_page'],
            'kd_dealer' => ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata('kd_dealer')
                //'tgl_trans' =>($this->input->get('tgl_trans_aw'))?tglToSql($this->input->get('tgl_trans_aw')):date('Ymd')
        );
        //$dariTgl=($this->input->get('tgl_trans_aw'))
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/laporanlkh", $param));
        $paramlkh = array(
            'kd_dealer' => $param["kd_dealer"],
                //'tgl_trans' =>$param["tgl_trans"]
        );
        $uri = explode('&page=', $_SERVER["REQUEST_URI"]);
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $uri[0],
            'total_rows' => (isset($data["list"])) ? $data["list"]->totaldata : 0
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('accounting/lap_pmodal', $data);
    }
    /**
     * [view_ar description]
     * @param  [type] $seleksi [description]
     * @return [type]          [description]
     */
    function view_ar($debug=false) {
        $this->tagihan($debug,true);
    }
    function piutang_bayar($asal=0){
        $param=array();
        $param["kd_maindealer"] = $this->input->post("kd_maindealer");
        $param["kd_dealer"]     = $this->input->post("kd_dealer");
        $param["no_trans"]      = ($this->input->post("no_pb"))?$this->input->post("no_pb"):$this->nomor_transaksi('PB');
        $param["tgl_bayar"]     = $this->input->post("tgl_bayar");
        $param["jumlah_bayar"]  = str_replace(",","",$this->input->post("jumlah_bayar"));
        $param["reff_bayar"]    = $this->input->post("reff_bayar");
        if($this->input->post("tagihanke")){
            $param["keterangan"]    = $this->input->post("no_trans").":".$this->input->post("kd_piutang").":".$this->input->post("tagihanke");
        }else{
            $param["keterangan"]    = $this->input->post("no_trans").":".$this->input->post("kd_piutang");
        }
        $param["sisa_bayar"]    = str_replace(",","",$this->input->post("sisa_bayar"));
        $param["rencana_bayar"] = $this->input->post("rencana_bayar");
        $param["no_kwitansi"]   = $this->input->post("no_kwitansi");
        $param["created_by"]    = $this->session->userdata("user_id");
        $param["tagihanke"]     = $this->input->post("tagihanke");
        //var_dump($param);exit();
        $hasil=($this->curl->simple_post(API_URL . "/api/accounting/piutang_bayar", $param, array(CURLOPT_BUFFERSIZE => 10)));
        $hasilx=json_decode($hasil);
        if($hasil){
            if($hasilx->recordexists==true){
                $param["lastmodified_by"] = $this->session->userdata("user_id");
                $hasil=($this->curl->simple_put(API_URL . "/api/accounting/piutang_bayar", $param, array(CURLOPT_BUFFERSIZE => 10)));
            }else{
                //update trans_piutang status
                $noreff ="";
                switch ($asal) {
                    case "1"://tagihan nontunai
                        $nomor_tran_asal=$this->input->post("no_kwitansi");
                        break;
                    default:
                        //tagihan program
                        if($this->input->post("tagihanke")){
                            $noreff=$param["keterangan"].":".$this->input->post("tagihanke");
                        }
                        //tagihan leasing
                        $nomor_tran_asal=$this->input->post("no_trans");
                        break;
                }
                $paramx=array(
                    'no_trans' => $nomor_tran_asal,
                    'no_reff' => $noreff,
                    'lastmodified_by' => $this->session->userdata("user_id")
                );
                $hasil=($this->curl->simple_put(API_URL . "/api/accounting/piutang_lunas", $paramx, array(CURLOPT_BUFFERSIZE => 10)));
            }
        }
        $this->data_output($hasil,'post');
    }
    /**
     * [neraca_coba description]
     * @return [type] [description]
     */
    public function neraca_coba() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        //$this->template->site('report/penerimaan', $data);
        $this->template->site('accounting/neraca_coba', $data);
    }
    /**
     * [buku_besar description]
     * @return [type] [description]
     */
    public function buku_besar() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('accounting/buku_besar', $data);
    }
    /**
     * [laba_rugi description]
     * @return [type] [description]
     */
    public function laba_rugi() {
        $data = array();
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/accounting/uangmasuk_detail", $param));
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", listDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('accounting/laba_rugi', $data);
    }
    function nomor_transaksi($kode=null){
        $nopo = "";
        $nomorpo = 0;
        $param = array(
            'kd_docno' => $kode,
            'kd_dealer' => $this->session->userdata("kd_dealer"),
            'tahun_docno' => date("Y"),
            'limit' => 1,
            'offset' => 0
        );

        $bulan_kirim = date("m");
        $nomorpo = $this->curl->simple_get(API_URL . "/api/setup/docno", $param);
        $kd_dealer = str_pad($this->session->userdata("kd_dealer"), 3, 0, STR_PAD_LEFT);
        if ($nomorpo == 0) {
            $nopo = $kode . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-00001";
        } else {
            $nomorpo = $nomorpo + 1;
            $nopo = $kode . $kd_dealer . $param["tahun_docno"] . str_pad($bulan_kirim, 2, '0', STR_PAD_LEFT) . "-" . str_pad($nomorpo, 5, '0', STR_PAD_LEFT);
        }
        return $nopo;
    }
    public function kwitansi_print() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'limit' => 15
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["html"] = $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $data["totaldata"] = $totaldata;
        $this->load->view('report/kwitansi_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    //KWITANSI 2 PRINT
    public function kwitansi2_print() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'limit' => 15
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["html"] = $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $data["totaldata"] = $totaldata;
        $this->load->view('report/kwitansi2_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    //Surat Jalan Mutasi Unit PRINT
    public function sjmu_print() {
        $data["html"] = '';
        $data = array();
        $data["totaldata"] = "";
        $param = array(
            'keyword' => $this->input->get('keyword'),
            'row_status' => $this->input->get('row_status'),
            'limit' => 15
        );
        if ($this->input->get("kd_dealer")) {
            $param["kd_dealer"] = $this->input->post('kd_dealer');
        } else {
            $param["kd_dealer"] = $this->session->userdata('kd_dealer');
        }
        $data["html"] = $html;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer"));
        $string = explode('&page=', $_SERVER["REQUEST_URI"]);
        $data["totaldata"] = $totaldata;
        $this->load->view('purchasing/sjmu_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
    function lhb() {
        $data = array();
        $paramcustomer = array();
        $fromDate=($this->input->get("tgl_awal"))?TglToSql($this->input->get("tgl_awal")):date('Ymd', strtotime('first day of this month'));
        $toDate=($this->input->get("tgl_akhir"))?TglToSql($this->input->get("tgl_akhir")):date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"));
        $param = array(
            'kd_dealer' => $kd_dealer,
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'jointable' => array(
                array("MASTER_KARYAWAN K", "K.NIK=TRANS_LHB_VIEWS.NAMA_MEKANIK", "LEFT")
            ),
            'field'=>"TRANS_LHB_VIEWS.*,K.NAMA",
            'custom' => "GRANDTOTAL >0 AND CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '".$fromDate."' AND '".$toDate."'",
            'orderby'=> "TRANS_LHB_VIEWS.NO_PKB"
        );
        if(!$this->input->get("p")){
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 15;
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/lhb", $param));
        $param["kd_dealer"] = $kd_dealer;
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        if($this->input->get("p")){
            $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", array('kd_dealer' => $kd_dealer)));

            $html = $this->load->view('report/lhb_print_new', $data, true);
            $filename = 'report_'.time();
            $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 595.28, 841.89), 'landscape');
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
            $this->template->site('report/lhb', $data);
        }
    }
    public function lhb_print() {
        $data = array();
        $paramcustomer = array();
        $fromDate=($this->input->get("frd"))?TglToSql($this->input->get("frd")):date('Ymd');
        $toDate=($this->input->get("tod"))?TglToSql($this->input->get("tod")):date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer"));
        $param = array(
            //'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) between '" . date("Y-m-d",tglToSql($this->input->get("tgl_awal"))) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            //'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("MASTER_KARYAWAN K", "K.NIK=TRANS_LHB_VIEWS.NAMA_MEKANIK", "LEFT")
            ),
            'custom' => "GRANDTOTAL >0" AND "CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '".$fromDate."' AND '".$toDate."'",
            'limit' => 15
        );
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/lhb", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        // var_dump($paramcustomer);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        $this->load->view('report/lhb_print', $data);
        $html = $this->output->get_output();
        $this->output->set_output(json_encode($html));
    }
// print laporan harian bengkel
    public function cetak_lhb() {
        $this->load->library('dompdf_gen');
        $data = array();
        $paramcustomer = array();
        $fromDate=($this->input->get("frd"))?TglToSql($this->input->get("frd")):date('Ymd');
        $toDate=($this->input->get("tod"))?TglToSql($this->input->get("tod")):date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer"));
        $param = array(
            //'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) between '" . date("Y-m-d",tglToSql($this->input->get("tgl_awal"))) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            //'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'jointable' => array(
                array("MASTER_KARYAWAN K", "K.NIK=TRANS_LHB_VIEWS.NAMA_MEKANIK", "LEFT")
            ),
            'custom' => "GRANDTOTAL >0" AND "CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '".$fromDate."' AND '".$toDate."'",
            'limit' => 15
        );
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/lhb", $param));
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        // var_dump($paramcustomer);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", $paramcustomer));
        $html = $this->load->view('report/lhb_print_new', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'landscape');
    }
    function data_output($hasil = NULL, $method = '') {
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
    /**
     * created by dimas rido
     * @return [type] [description]
     */
    function crmharian() {
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) between '" . date("Y-m-d",tglToSql($this->input->get("tgl_awal"))) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            //'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            //'orderby' => 'ID DESC',
            'limit' => 15
        );
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        //$param["custom"] = " TANGGAL_PKB BETWEEN '2019-02-01' AND '2019-03-31'";
        //var_dump($param); exit;
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/crmharian", $param));
        //var_dump($data); exit;
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        if ($this->session->userdata("nama_group") == 'Root') {
            unset($paramcustomer);
            $paramcustomer = array();
        }
        // var_dump($paramcustomer);exit;
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        //var_dump($data["list"]);
        $this->template->site('report/crmharian', $data);
    }
    public function crmharian_xls() {
        $this->load->library('libexcel');
        $this->libexcel->setActiveSheetIndex(0);
        $data = array();
        $paramcustomer = array();
        $param = array(
            'custom' => $this->input->get('kd_dealer') ? "KD_DEALER = '" . $this->input->get('kd_dealer') . "'" : "KD_DEALER ='" . $this->session->userdata("kd_dealer") . "' AND convert(char,TANGGAL_PKB,112) between '" . date("Y-m-d",tglToSql($this->input->get("tgl_awal"))) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'",
            //'tanggal' => $this->input->get('tanggal'),
            'kd_dealer' => ($this->input->get('kd_dealer')) ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"),
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            //'orderby' => 'ID DESC',
            'limit' => 15
        );
        if ($this->input->get("tgl_awal")) {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . tglToSql($this->input->get("tgl_awal")) . "' AND '" . tglToSql($this->input->get("tgl_akhir")) . "'";
        } else {
            $param["custom"] = " CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '" . date('Ymd', strtotime('first day of this month')) . "' AND '" . date('Ymd') . "'";
        }
        //$param["custom"] = " TANGGAL_PKB BETWEEN '2019-02-01' AND '2019-03-31'";
        //var_dump($param); exit;
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/crmharian", $param));
        // var_dump($data);exit;
        $namafile="";
        $isifile="";
        //$title ="";
        $title = 'Laporan CRM Harian';
        //name the worksheet
        $this->libexcel->getActiveSheet()->setTitle($title);
        $this->libexcel->getActiveSheet()->setCellValue('A1', $title);
        $this->libexcel->getActiveSheet()->mergeCells('A1:G1');
        $this->libexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        for($col = ord('A'); $col <= ord('H'); $col++){ 
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(true);
        }
        $this->libexcel->getActiveSheet()->setCellValue('A3', 'No');
        $this->libexcel->getActiveSheet()->setCellValue('B3', 'No PKB');
        $this->libexcel->getActiveSheet()->setCellValue('C3', 'Tanggal');
        $this->libexcel->getActiveSheet()->setCellValue('D3', 'No Polisi');
        $this->libexcel->getActiveSheet()->setCellValue('E3', 'Tipe Motor');
        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tahun');
        $this->libexcel->getActiveSheet()->setCellValue('G3', 'No Mesin');
        $this->libexcel->getActiveSheet()->setCellValue('H3', 'No Rangka');
        for($col = ord('A'); $col <= ord('H'); $col++){
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(false);
        }
        $no = 4;
        foreach ($data["list"]->message as $key => $row){
            //$filename=  $row->KD_MAINDEALER."-".$row->KD_DEALERAHM."-".date('ymdHis')."-".$title.".xls";
            $filename= "Laporan CRM - ".$row->KD_DEALER." ".$this->input->get("tgl_awal")." - ".$this->input->get("tgl_akhir").".xls";
            $this->libexcel->getActiveSheet()->setCellValue('A'.$no, $key+1);
            $this->libexcel->getActiveSheet()->setCellValue('B'.$no, $row->NO_PKB);
            $this->libexcel->getActiveSheet()->setCellValue('C'.$no, tglfromSql($row->TANGGAL_PKB));
            $this->libexcel->getActiveSheet()->setCellValue('D'.$no, $row->NO_POLISI);
            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, $row->NAMA_TYPEMOTOR);
            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, $row->TAHUN);
            $this->libexcel->getActiveSheet()->setCellValue('G'.$no, $row->NO_MESIN);
            $this->libexcel->getActiveSheet()->setCellValue('H'.$no, $row->NO_RANGKA);
            $no++;
        }
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->libexcel, 'Excel5'); 
        $objWriter->save('php://output');
    }
    /**
     * [report_insentifpicpart description]
     * @return [type] [description]
     */
    //-------------------------------------------------- dimas --------------------------------//
   function insentifpicpart() {
         ini_set('max_execution_time', 500);
        $data = array();
        $paramcustomer = array();
        $param = array(
            //'custom' => "'".$this->input->get('kd_dealer')."',".$this->input->get('start_date')."','".$this->input->get('end_date')."'", 
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50
        );
        
        $q = $this->input->get("q");
        $tahun = $this->input->get("tahun");
        if ($q == "Q1") {
            $start_date = ('01/01/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '03') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '03') . '/' . $this->input->get("tahun"));
        } else if ($q=="Q2") {
            $start_date = ('01/04/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '06') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '06') . '/' . $this->input->get("tahun"));
        } else if ($q=="Q3") {
            $start_date = ('01/07/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '09') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '09') . '/' . $this->input->get("tahun"));
        } else if ($q=="Q4") {
            $start_date = ('01/10/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '12') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '12') . '/' . $this->input->get("tahun"));        
        }
        
        if ($q) {
            $param['start_date'] = tglToSql($start_date);
            $param['end_date'] = tglToSql($end_date);
        }
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentifpicpart", $param));
        //var_dump($end_date); exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        $data["dealerpilih"] = $this->input->get('kd_dealer');
        $data["qpilih"] = $this->input->get('q');
        $data["tahunpilih"] = $this->input->get('tahun');
        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );
        $pagination = $this->template->pagination($config);
        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->site('report/insentif_picpart/insentifpicpart', $data);
    }
     /**
     * [report_insentifpicpart description]
     * @return [type] [description]
     */
    function insentifpicpart_print() {
         ini_set('max_execution_time', 500);
        $data = array();
        $paramcustomer = array();
        $param = array(
            //'custom' => "'".$this->input->get('kd_dealer')."',".$this->input->get('start_date')."','".$this->input->get('end_date')."'", 
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50
        );
        $q = $this->input->get("q");
        $tahun = $this->input->get("tahun");
        if ($q == "Q1") {
            $start_date = ('01/01/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '03') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '03') . '/' . $this->input->get("tahun"));
        } else if ($q=="Q2") {
            $start_date = ('01/04/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '06') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '06') . '/' . $this->input->get("tahun"));
        } else if ($q=="Q3") {
            $start_date = ('01/07/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '09') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '09') . '/' . $this->input->get("tahun"));
        } else if ($q=="Q4") {
            $start_date = ('01/10/' . $this->input->get("tahun"));
            $end_date = (date('t', strtotime(sprintf("%'.02d", '12') . '/01/' . $this->input->get("tahun"))) . '/' . sprintf("%'.02d", '12') . '/' . $this->input->get("tahun"));        
        }
        
        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);
        $param["kd_dealer"] = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentifpicpart", $param));
        //var_dump($data["list"]); exit();
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
         $html = $this->load->view('report/insentif_picpart/insentifpicpart_print', $data, true);
        $filename = 'report_'.time();
        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'landscape');
    }
    
    function proses_insentifpicpart($kd_dealer,$q,$tahun) {
        $data = array();
        $param = array(
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'offset' => ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE),
            'limit' => 50
        );
       
        if ($q == "Q1") {
            $start_date = ('01/01/' . $tahun);
            $end_date = (date('t', strtotime(sprintf("%'.02d", '03') . '/01/' . $tahun)) . '/' . sprintf("%'.02d", '03') . '/' . $tahun);
        } else if ($q=="Q2") {
            $start_date = ('01/04/' . $tahun);
            $end_date = (date('t', strtotime(sprintf("%'.02d", '06') . '/01/' . $tahun)) . '/' . sprintf("%'.02d", '06') . '/' . $tahun);
        } else if ($q=="Q3") {
            $start_date = ('01/07/' . $tahun);
            $end_date = (date('t', strtotime(sprintf("%'.02d", '09') . '/01/' . $tahun)) . '/' . sprintf("%'.02d", '09') . '/' . $tahun);
        } else if ($q=="Q4") {
            $start_date = ('01/10/' . $tahun);
            $end_date = (date('t', strtotime(sprintf("%'.02d", '12') . '/01/' . $tahun)) . '/' . sprintf("%'.02d", '12') . '/' . $tahun);        
        }

        $param['kd_dealer'] = $kd_dealer;
        $param['start_date'] = tglToSql($start_date);
        $param['end_date'] = tglToSql($end_date);
        //var_dump($param); exit();
        
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/insentifpicpart", $param));
        
        
        $paramhdr = array(
            'no_proses' => 'INSPP'.$param["kd_dealer"].$q.$tahun,
            'kd_dealer' => $param["kd_dealer"],
            'start_date' => $param['start_date'],
            'end_date' =>   $param['end_date'],
            'created_by' => $this->session->userdata("user_id")
        );
        //var_dump($paramhdr); exit();
        $hasil_header = $this->curl->simple_post(API_URL . "/api/laporan/insentifpicpartheader", $paramhdr, array(CURLOPT_BUFFERSIZE => 10));
        //var_dump($hasil_header); exit();
        foreach ($data['list']->message as $key => $value) { 
            $paramdtl = array(
                'no_proses' => 'INSPP'.$param["kd_dealer"].$q.$tahun,
                'nik' => $value->NIK,
                'insentif' => (int)$value->INSENTIF,
                'created_by' => $this->session->userdata("user_id")
            );
            //var_dump($paramdtl); exit();
            $hasil_detail = $this->curl->simple_post(API_URL . "/api/laporan/insentifpicpartdetail", $paramdtl, array(CURLOPT_BUFFERSIZE => 10));
        }
         //var_dump($hasil_detail); exit();
       // $this->session->set_flashdata('tr-active', $hasil_header->message);
        $this->session->set_flashdata('success', 'Update Berhasil');
        $this->data_output($hasil_header, 'post');
     
    }
    
     /**
     * [report_lab description]
     * @return [type] [description]
     */
    function lab() {
       $data = array();
        $paramcustomer = array();
        $fromDate=($this->input->get("tgl_awal"))?TglToSql($this->input->get("tgl_awal")):date('Ymd', strtotime('first day of this month'));
        $toDate=($this->input->get("tgl_akhir"))?TglToSql($this->input->get("tgl_akhir")):date('Ymd');
        $kd_dealer = ($this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer"));
        $param = array(
            'kd_dealer' => $kd_dealer,
            'row_status' => ($this->input->get('row_status') == null) ? 0 : $this->input->get('row_status'),
            'field'=>"TRANS_LAB_VIEWS.*",
            'custom' => "GRANDTOTAL >0 AND CONVERT(CHAR,TANGGAL_PKB,112) BETWEEN '".$fromDate."' AND '".$toDate."'",
            'orderby'=> "TRANS_LAB_VIEWS.TANGGAL_PKB"
        );
        if(!$this->input->get("p")){
            $param['offset'] = ($this->input->get('page') == null) ? 0 : $this->input->get('page', TRUE);
            $param['limit'] = 15;
        }
        $data["list"] = json_decode($this->curl->simple_get(API_URL . "/api/laporan/lab", $param));
        $param["kd_dealer"] = $kd_dealer;
        $paramcustomer = array(
            'kd_dealer' => $this->session->userdata("kd_dealer")
        );
        $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", ListDealer()));
        if($this->input->get("p")){
            $data["dealer"] = json_decode($this->curl->simple_get(API_URL . "/api/master/dealer", array('kd_dealer' => $kd_dealer)));

            $html = $this->load->view('report/lab_print', $data, true);
            $filename = 'report_'.time();
            $this->dompdf_gen->generate($html, $filename, true, array(0, 0, 595.28, 841.89), 'landscape');
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
            $this->template->site('report/lab', $data);
        }
    }
}