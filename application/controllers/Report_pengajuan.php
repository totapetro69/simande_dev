<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_pengajuan extends CI_Controller {
     var $API="";
    public function __construct()
    {
            parent::__construct();
            //API_URL=str_replace('frontend/','backend/',base_url())."index.php"; 
            $this->load->library('form_validation');
            $this->load->library('session');
            $this->load->library('curl');
            $this->load->library('pagination');
            $this->load->helper('form');
            $this->load->helper('url'); 
            $this->load->helper('zetro'); 
            $this->load->helper('file');
    }

    public function laporan_pengajuan($reff){
        $reff = $reff == 'stnk'?'STNK':'BPKB';
        $tgl= ($this->input->get("tahun")?"YEAR(SP.TGL_SO) = '" . $this->input->get("tahun") . "'" : "YEAR(SP.TGL_SO) = '" . date('Y') . "'");

        // var_dump($tgl);exit;
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_BUKTI_VIEW.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_BUKTI_VIEW.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $jenis = $this->input->get('jenis');

        $param = array(
            'offset'    => ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
            'limit'     => 15,
            'jointable' => array(
                array("TRANS_SJKELUAR AS SJ" , "SJ.NO_SURATJALAN=TRANS_STNK_BUKTI_VIEW.NO_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK AS SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER=TRANS_STNK_BUKTI_VIEW.KD_CUSTOMER","LEFT")
            ),
            // 'custom' => "TRANS_STNK_DETAIL.REFF_SOURCE = 2",
            'field' => "TRANS_STNK_BUKTI_VIEW.*, MC.NAMA_CUSTOMER, SP.TGL_SO, YEAR(SP.TGL_SO) AS TGL_FAKTUR"
        );

        switch ($jenis) {
            case 2://Belum diserahkan


            $filter = "TRANS_STNK_BUKTI_VIEW.STATUS_".$reff." <= 5 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_".$reff." >= 0 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_".$reff." < 1";
            break;

            default://Belum diterima

            $filter = "TRANS_STNK_BUKTI_VIEW.STATUS_".$reff." <= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_".$reff." IS NULL";

            break;
        }
        

        // $param['custom'] = $kd_dealer." AND ".$filter;
        $param['custom'] = "LEN(ISNULL(SP.TGL_SO,''))>0 AND ".$tgl." AND ".$kd_dealer." AND ".$filter;

        $data = array();
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti_view", $param));
        // var_dump($param);exit;

        // var_dump($data["list"]);exit;
        $data['list_detail'] = '';

        if($data['list'] && (is_array($data['list']->message) || is_object($data['list']->message))):

            $data['list_detail'] = $this->pengajuan_table($data['list']->message, false, $reff);

        else:
            $data['list_detail'] .= '<table id="list_data" class="table table-striped table-bordered">';
            $data['list_detail'] .= '<thead>';
            $data['list_detail'] .= '<tr class="no-hover"><th colspan="13" ><i class="fa fa-list fa-fw"></i> List '.$reff.'</th></tr>';
            $data['list_detail'] .= '</thead>';
            $data['list_detail'] .= '<tbody>';
            $data['list_detail'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=12><b>Belum ada data / data tidak ditemukan</b></td></tr>";
            $data['list_detail'] .= '</tbody>';
            $data['list_detail'] .= '</table>';
                
        endif;

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));
        $data['reff'] = $reff;

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

        $pagination = $this->template->pagination($config);

        $this->pagination->initialize($pagination);
        $data['pagination'] = $this->pagination->create_links();

        // $this->output->set_output(json_encode($data));
        $this->template->site('report/pengajuan_stbp/laporan_pengajuan',$data);

    }



    public function pengajuan_pdf($reff, $return = null)
    {
        $this->load->library('dompdf_gen');

        $reff = $reff == 'stnk'?'STNK':'BPKB';
        $tgl= ($this->input->get("tahun")?"YEAR(SP.TGL_SO) = '" . $this->input->get("tahun") . "'" : "YEAR(SP.TGL_SO) = '" . date('Y') . "'");

        // var_dump($tgl);exit;
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_BUKTI_VIEW.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_BUKTI_VIEW.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        $jenis = $this->input->get('jenis');

        $param = array(
            'jointable' => array(
                array("TRANS_SJKELUAR AS SJ" , "SJ.NO_SURATJALAN=TRANS_STNK_BUKTI_VIEW.NO_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK AS SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER=TRANS_STNK_BUKTI_VIEW.KD_CUSTOMER","LEFT")
            ),
            // 'custom' => "TRANS_STNK_DETAIL.REFF_SOURCE = 2",
            'field' => "TRANS_STNK_BUKTI_VIEW.*, MC.NAMA_CUSTOMER, SP.TGL_SO, YEAR(SP.TGL_SO) AS TGL_FAKTUR"
        );

        switch ($jenis) {
            case 2://Belum diserahkan


            $filter = "TRANS_STNK_BUKTI_VIEW.STATUS_".$reff." <= 5 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_".$reff." >= 0 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_".$reff." < 1";
            break;

            default://Belum diterima

            $filter = "TRANS_STNK_BUKTI_VIEW.STATUS_".$reff." <= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_".$reff." IS NULL";

            break;
        }
        

        // $param['custom'] = $kd_dealer." AND ".$filter;
        $param['custom'] = "LEN(ISNULL(SP.TGL_SO,''))>0 AND ".$tgl." AND ".$kd_dealer." AND ".$filter;

        $data = array();
        $data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti_view", $param));
        // $this->output->set_output(json_encode($data));

        // $this->load->view('pdf/faktur_penjualan', $data);
        if($return){
            return $data['list'];

        }
        else{
            $html = '<link rel="stylesheet" href="'.base_url("assets/css/pdf-style.css").'" >';
            $html .= $this->pengajuan_table($data['list']->message, true, $reff);
            
            $filename = 'report_'.time();
            $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');
        }
    }

    public function pengajuan_xls($reff)
    {

        $this->load->library('libexcel');
        $this->libexcel->setActiveSheetIndex(0);

        $data=array();
        $data= $this->pengajuan_pdf($reff, true);

        $reff = $reff == 'stnk'?'STNK':'BPKB';
        // var_dump($reff);exit;
        $jenis = $this->input->get('jenis');
        $date_now = date('Y/m/d');

        // var_dump($data);exit;
        $namafile="";
        $isifile="";
        $title ="";


        switch ($jenis) {
            case 2://Notis Belum Selesai
            $title .= ($reff == 'STNK'?'STNK blm disrahkan ke konsumn':'BPKB blm disrahkan ke konsumn');
            break;

            default://Notis Belum Dimohonkan
            $title .= ($reff == 'STNK'?'STNK blm selesai':'BPKB blm diterima Cabang');
            break;
        

        }

        //name the worksheet
        $this->libexcel->getActiveSheet()->setTitle($title);
        $this->libexcel->getActiveSheet()->setCellValue('A1', $title);
        $this->libexcel->getActiveSheet()->mergeCells('A1:G1');
        $this->libexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->libexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        for($col = ord('A'); $col <= ord('G'); $col++){ 
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(true);
        }


        $this->libexcel->getActiveSheet()->setCellValue('A3', 'No');
        $this->libexcel->getActiveSheet()->setCellValue('B3', 'Nama Customer');
        $this->libexcel->getActiveSheet()->setCellValue('C3', 'No Mesin');
        $this->libexcel->getActiveSheet()->setCellValue('D3', 'Tgl Pengurusan');

        switch ($jenis) {
            case 2://Notis Belum Selesai
                $this->libexcel->getActiveSheet()->setCellValue('E3', 'No Mohon '.$reff);
                $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tgl Penerimaan');
            break;
        
            default://Notis Belum Dimohonkan
                $this->libexcel->getActiveSheet()->setCellValue('E3', 'No Mohon '.$reff);
                $this->libexcel->getActiveSheet()->mergeCells('E3:F3');
            break;
        

        }

        $this->libexcel->getActiveSheet()->setCellValue('G3', 'Lead Time');


        for($col = ord('A'); $col <= ord('G'); $col++){
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(false);

        }

        $no = 4;

        // var_dump($data->message);exit;

        foreach ($data->message as $key => $row){
            $filename=  $row->KD_MAINDEALER."-".$row->KD_DEALERAHM."-".date('ymdHis')."-".$title.".xls";

            $this->libexcel->getActiveSheet()->setCellValue('A'.$no, $key+1);
            $this->libexcel->getActiveSheet()->setCellValue('B'.$no, $row->NAMA_CUSTOMER);
            $this->libexcel->getActiveSheet()->setCellValue('C'.$no, $row->KD_MESIN.$row->NO_MESIN);
            $this->libexcel->getActiveSheet()->setCellValue('D'.$no, ($reff == 'STNK' ? tglfromSql($row->TGL_PENGURUSAN_STNK) : tglfromSql($row->TGL_PENGURUSAN_BPKB)));

            switch ($jenis) {
                case 2://Notis Belum Selesai
                $interval=strtotime($date_now) - ($reff == 'STNK' ? strtotime($row->TGL_PENGURUSAN_STNK) : strtotime($row->TGL_PENGURUSAN_BPKB));
                $lead_time = ceil($interval / (60 * 60 * 24));

                $this->libexcel->getActiveSheet()->setCellValue('E'.$no, ($reff == 'STNK' ? $row->TRANS_NO_STNK : $row->TRANS_NO_BPKB));
                $this->libexcel->getActiveSheet()->setCellValue('F'.$no, ($reff == 'STNK' ? tglfromSql($row->TGL_PENERIMAAN_STNK) : tglfromSql($row->TGL_PENERIMAAN_BPKB)));
                break;
            
                default://Notis Belum Dimohonkan
                $interval=strtotime($date_now) - ($reff == 'STNK' ? strtotime($row->TGL_PENGURUSAN_STNK) : strtotime($row->TGL_PENGURUSAN_BPKB));
                $lead_time = ceil($interval / (60 * 60 * 24));

                $this->libexcel->getActiveSheet()->setCellValue('E'.$no, ($reff == 'STNK' ? $row->TRANS_NO_STNK : $row->TRANS_NO_BPKB));
                $this->libexcel->getActiveSheet()->mergeCells('E3:F3');
                break;
            }
            


                        
            $this->libexcel->getActiveSheet()->setCellValue('G'.$no, number_format($lead_time));

            $no++;




        }


        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->libexcel, 'Excel5'); 
        $objWriter->save('php://output');
    }

    public function pengajuan_table($list, $pdf=false, $reff)
    {
        $jenis = $this->input->get('jenis');

        $date_now = date('Y/m/d');

        $html = '';


        if($pdf == true):
        switch ($jenis) {
            case 2://Notis Belum Selesai
            $html .= '<H3><strong>'.($reff == 'STNK'?'Laporan STNK belum diserahkan ke konsumen':'Laporan BPKB belum diserahkan ke konsumen').'</strong> <small style="float:right; font-size:9px;">'.date('d/m/Y H:i:s').'</small></H3>';
            break;        
            default://Notis Belum Dimohonkan
            $html .= '<H3><strong>'.($reff == 'STNK'?'Laporan STNK belum selesai':'Laporan BPKB belum diterima Cabang').'</strong> <small style="float:right; font-size:9px;">'.date('d/m/Y H:i:s').'</small></H3>';
            break;
        

        }
        endif;

        $html .= '<table id="list_data" class="table '.($pdf == true?'':'table-striped').' table-bordered" style="width:100%; font-size:13px;">';
        $html .= '<thead>';
        // $html .= '<tr class="no-hover"><th colspan="13" ><i class="fa fa-list fa-fw"></i> List Notis</th></tr>';
        $html .= '<tr class="no-hover">';
        $html .= '<th style="width:45px; vertical-align: middle;">No</th>';
        $html .= '<th>Nama Customer</th>';
        $html .= '<th>No Mesin</th>';
        $html .= '<th>Tgl Pengurusan</th>';


        switch ($jenis) {
            case 2://Notis Belum Selesai
            $html .= '<th>No Mohon '.$reff.'</th>';
            $html .= '<th>Tgl Penerimaan</th>';
            break;

            default://Notis Belum Dimohonkan
            $html .= '<th>No Mohon '.$reff.'</th>';
            break;
        

        }
        
        $html .= '<th style="white-space: nowrap;">Lead Time</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $no=$this->input->get('page');
        foreach ($list as $key => $value):
        $no++;

        // var_dump($date_now);exit;

        $html .= '<tr>';
        $html .= '<td>'.$no.'</td>';
        $html .= '<td>'.$value->NAMA_CUSTOMER.'</td>';
        $html .= '<td>'.$value->KD_MESIN.$value->NO_MESIN.'</td>';
        $html .= '<td>'.($reff == 'STNK' ? tglfromSql($value->TGL_PENGURUSAN_STNK) : tglfromSql($value->TGL_PENGURUSAN_BPKB)).'</td>';



        switch ($jenis) {
            case 2://Notis Belum Selesai
            $interval=strtotime($date_now) - ($reff == 'STNK' ? strtotime($value->TGL_PENGURUSAN_STNK) : strtotime($value->TGL_PENGURUSAN_BPKB));
            $lead_time = ceil($interval / (60 * 60 * 24));
            $html .= '<td>'.($reff == 'STNK' ? $value->TRANS_NO_STNK : $value->TRANS_NO_BPKB).'</td>';
            $html .= '<td>'.($reff == 'STNK' ? tglfromSql($value->TGL_PENERIMAAN_STNK) : tglfromSql($value->TGL_PENERIMAAN_BPKB)).'</td>';
            break;

            default://Notis Belum Dimohonkan
            $interval=strtotime($date_now) - ($reff == 'STNK' ? strtotime($value->TGL_PENGURUSAN_STNK) : strtotime($value->TGL_PENGURUSAN_BPKB));
            $lead_time = ceil($interval / (60 * 60 * 24));
            $html .= '<td>'.($reff == 'STNK' ? $value->TRANS_NO_STNK : $value->TRANS_NO_BPKB).'</td>';
            break;
        
            
        }

        $html .= '<td style="text-align:center;">'.number_format($lead_time).'</td>';
        $html .= '</tr>';

        endforeach;
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;

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
