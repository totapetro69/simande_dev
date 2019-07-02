<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notis extends CI_Controller {
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
	public function pengurusan_notis()
	{
        $tgl= ($this->input->get("tahun")?"YEAR(SP.TGL_SO) = '" . $this->input->get("tahun") . "'" : "YEAR(SP.TGL_SO) = '" . date('Y') . "'");

        // var_dump($tgl);exit;
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_BUKTI_VIEW.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_BUKTI_VIEW.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";


		$param = array(
			'offset' 	=> ($this->input->get('page') == null)?0:$this->input->get('page', TRUE), 
			'limit' 	=> 15,
            'jointable' => array(
                array("TRANS_SJKELUAR AS SJ" , "SJ.NO_SURATJALAN=TRANS_STNK_BUKTI_VIEW.NO_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK AS SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER=TRANS_STNK_BUKTI_VIEW.KD_CUSTOMER","LEFT")
            ),
            // 'custom' => "TRANS_STNK_DETAIL.REFF_SOURCE = 2",
            'field' => "TRANS_STNK_BUKTI_VIEW.*, MC.NAMA_CUSTOMER, SP.TGL_SO, YEAR(SP.TGL_SO) AS TGL_FAKTUR"
		);

		$jenis = $this->input->get('jenis');


		switch ($jenis) {
			case 2://Notis Belum Selesai
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PINJAM_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB <= 5 AND (TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC <= 0 OR TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC IS NULL) AND ".$tgl." AND ".$kd_dealer;
			break;

			case 3://Notis Sudah Selesai
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PINJAM_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC >= 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 4://Notis Belum Diserahkan
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC = 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PINJAM_BPKB,''))>0 AND LEN(ISNULL(SP.TGL_SO,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC >= 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PENYERAHAN_HCC,''))>0 AND LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC > 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC = 0 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_STNK > 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			default://Notis Belum Dimohonkan
			$param['custom'] = "LEN(ISNULL(SP.TGL_SO,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB <= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC IS NULL AND ".$tgl." AND ".$kd_dealer;
			break;
		}
		


		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti_view", $param));

        // var_dump($data["list"]);exit;
        $data['list_detail'] = '';

		if($data['list'] && (is_array($data['list']->message) || is_object($data['list']->message))):

            $data['list_detail'] = $this->notis_table($data['list']->message);

        else:
			$data['list_detail'] .= '<table id="list_data" class="table table-striped table-bordered">';
			$data['list_detail'] .= '<thead>';
			$data['list_detail'] .= '<tr class="no-hover"><th colspan="13" ><i class="fa fa-list fa-fw"></i> List Notis</th></tr>';
			$data['list_detail'] .= '</thead>';
			$data['list_detail'] .= '<tbody>';
            $data['list_detail'] .= "<tr class='tr-notif'><td>&nbsp;<i class='fa fa-info-circle'></i></td><td colspan=12><b>Belum ada data / data tidak ditemukan</b></td></tr>";
	        $data['list_detail'] .= '</tbody>';
	        $data['list_detail'] .= '</table>';
	            
		endif;

        $data["dealer"] =json_decode($this->curl->simple_get(API_URL."/api/master/dealer",listDealer()));

        $string = link_pagination();
        $config = array(
            'per_page' => $param['limit'],
            'base_url' => $string[0],
            'total_rows' => ($data["list"]) ? $data["list"]->totaldata : 0
        );

		$pagination = $this->template->pagination($config);

		$this->pagination->initialize($pagination);
		$data['pagination'] = $this->pagination->create_links();

		$this->template->site('sales/pengurusan_notis',$data);
	}


    public function notis_pdf($return = null)
    {
        $this->load->library('dompdf_gen');

        
        $tgl= ($this->input->get("tahun")?"YEAR(SP.TGL_SO) = '" . $this->input->get("tahun") . "'" : "YEAR(SP.TGL_SO) = '" . date('Y') . "'");
        $kd_dealer = $this->input->get('kd_dealer') ? "TRANS_STNK_BUKTI_VIEW.KD_DEALER = '".$this->input->get('kd_dealer')."'" : "TRANS_STNK_BUKTI_VIEW.KD_DEALER ='".$this->session->userdata("kd_dealer")."'";

        // var_dump($tgl);exit;


		$param = array(
            'jointable' => array(
                array("TRANS_SJKELUAR AS SJ" , "SJ.NO_SURATJALAN=TRANS_STNK_BUKTI_VIEW.NO_SURATJALAN AND SJ.ROW_STATUS>=0", "LEFT"),
                array("TRANS_SPK AS SP" , "SP.NO_SO=SJ.NO_REFF AND SP.ROW_STATUS>=0", "LEFT"),
                array("MASTER_CUSTOMER AS MC","MC.KD_CUSTOMER=TRANS_STNK_BUKTI_VIEW.KD_CUSTOMER","LEFT")
            ),
            // 'custom' => "TRANS_STNK_DETAIL.REFF_SOURCE = 2",
            'field' => "TRANS_STNK_BUKTI_VIEW.*, MC.NAMA_CUSTOMER, SP.TGL_SO, YEAR(SP.TGL_SO) AS TGL_FAKTUR"
		);

		$jenis = $this->input->get('jenis');


		switch ($jenis) {
			case 2://Notis Belum Selesai
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PINJAM_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB <= 5 AND (TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC <= 0 OR TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC IS NULL) AND ".$tgl." AND ".$kd_dealer;
			break;

			case 3://Notis Sudah Selesai
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PINJAM_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC >= 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 4://Notis Belum Diserahkan
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC = 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PINJAM_BPKB,''))>0 AND LEN(ISNULL(SP.TGL_SO,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC >= 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_PENYERAHAN_HCC,''))>0 AND LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC > 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
			$param['custom'] = "LEN(ISNULL(TRANS_STNK_BUKTI_VIEW.TGL_BALIK_BPKB,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB >= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC = 0 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_STNK > 0 AND ".$tgl." AND ".$kd_dealer;
			break;
		
			default://Notis Belum Dimohonkan
			$param['custom'] = "LEN(ISNULL(SP.TGL_SO,''))>0 AND TRANS_STNK_BUKTI_VIEW.STATUS_BPKB <= 4 AND TRANS_STNK_BUKTI_VIEW.STATUS_PENERIMA_HCC IS NULL AND ".$tgl." AND ".$kd_dealer;
			break;
		}
		


		$data = array();
		$data["list"]=json_decode($this->curl->simple_get(API_URL."/api/stnkbpkb/stnk_bukti_view", $param));

        // $this->output->set_output(json_encode($data));

        // $this->load->view('pdf/faktur_penjualan', $data);
		if($return == 'true'){
			return $data['list'];

		}
		else{
	        $html = '<link rel="stylesheet" href="'.base_url("assets/css/bootstrap.min.css").'" >';
	        $html .= $this->notis_table($data['list']->message, true);
	        
	        $filename = 'report_'.time();
	        $this->dompdf_gen->generate($html, $filename, true, 'A4', 'potrait');
		}
    }

    public function notis_xls()
    {
        $this->load->library('libexcel');
        $this->libexcel->setActiveSheetIndex(0);

        $data=array();
        $data= $this->notis_pdf('true');

		$jenis = $this->input->get('jenis');
		$date_now = date('Y/m/d');

        // var_dump($data);exit;
        $namafile="";
        $isifile="";
        $title ="";


		switch ($jenis) {
			case 2://Notis Belum Selesai
			$title .= 'Laporan Notis Belum Selesa';
			break;

			case 3://Notis Sudah Selesai
			$title .= 'Laporan Notis Sudah Selesai';
			break;
		
			case 4://Notis Belum Diserahkan
			$title .= 'Laporan Notis Belum Diserahkan';
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
			$title .= 'Laporan Lead Time Pendaftaran Notis Pajak';
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
			$title .= 'Laporan Lead Time Penyerahan Notis Pajak';
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
			$title .= 'Laporan Notis Belum Diserahkan, STNK Sudah Diserahkan';
			break;
		
			default://Notis Belum Dimohonkan
			$title .= 'Laporan Notis Belum Dimohonkan';
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
        $this->libexcel->getActiveSheet()->setCellValue('D3', 'Tgl Mohon Faktur');

		switch ($jenis) {
			case 2://Notis Belum Selesai
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'No Mohon STNK');
		        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tgl Pinjam');
			break;

			case 3://Notis Sudah Selesai
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'Tgl Pinjam');
		        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tgl Krdt');
			break;
		
			case 4://Notis Belum Diserahkan
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'Tgl Pinjam');
		        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tgl Krdt');
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'Tgl Pinjam');
        		$this->libexcel->getActiveSheet()->mergeCells('E3:F3');
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'Tgl Krdt');
		        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tgl Serh Nts');
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'Tgl STNK Ambil');
		        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Tgl Krdt');
			break;
		
			default://Notis Belum Dimohonkan
		        $this->libexcel->getActiveSheet()->setCellValue('E3', 'No Mohon BPKB');
		        $this->libexcel->getActiveSheet()->setCellValue('F3', 'Harga');
			break;
		

		}

        $this->libexcel->getActiveSheet()->setCellValue('G3', 'Lead Time');


       	for($col = ord('A'); $col <= ord('G'); $col++){
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->libexcel->getActiveSheet()->getStyle(chr($col))->getFont()->setBold(false);

        }

        $no = 4;
        foreach ($data->message as $key => $row){
            $filename=  $row->KD_MAINDEALER."-".$row->KD_DEALERAHM."-".date('ymdHis')."-".$title.".xls";

            $this->libexcel->getActiveSheet()->setCellValue('A'.$no, $key+1);
            $this->libexcel->getActiveSheet()->setCellValue('B'.$no, $row->NAMA_CUSTOMER);
            $this->libexcel->getActiveSheet()->setCellValue('C'.$no, $row->KD_MESIN.$row->NO_MESIN);
            $this->libexcel->getActiveSheet()->setCellValue('D'.$no, tglfromSql($row->TGL_SO));

			switch ($jenis) {
				case 2://Notis Belum Selesai
				$interval=strtotime($date_now) - strtotime($row->TGL_PINJAM_BPKB);
				$lead_time = ceil($interval / (60 * 60 * 24));

	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, $row->TRANS_NO_STNK);
	            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, tglfromSql($row->TGL_PINJAM_BPKB));
				break;

				case 3://Notis Sudah Selesai
				$interval=strtotime($row->TGL_BALIK_BPKB) - strtotime($row->TGL_PINJAM_BPKB);
				$lead_time = ceil($interval / (60 * 60 * 24));
				
	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, tglfromSql($row->TGL_PINJAM_BPKB));
	            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, tglfromSql($row->TGL_BALIK_BPKB));
				break;
			
				case 4://Notis Belum Diserahkan
				$interval=strtotime($date_now) - strtotime($row->TGL_BALIK_BPKB);
				$lead_time = ceil($interval / (60 * 60 * 24));
				
	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, tglfromSql($row->TGL_PINJAM_BPKB));
	            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, tglfromSql($row->TGL_BALIK_BPKB));
				break;
			
				case 5://Lead Time Pendaftaran Notis Pajak
				$interval=strtotime($row->TGL_PINJAM_BPKB) - strtotime($row->TGL_SO);
				$lead_time = ceil($interval / (60 * 60 * 24));
				
	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, tglfromSql($row->TGL_PINJAM_BPKB));
        		$this->libexcel->getActiveSheet()->mergeCells('E3:F3');
				break;
			
				case 6://Lead Time Penyerahan Notis Pajak
				$interval=strtotime($row->TGL_PENYERAHAN_HCC) - strtotime($row->TGL_BALIK_BPKB);
				$lead_time = ceil($interval / (60 * 60 * 24));	
				
	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, tglfromSql($row->TGL_BALIK_BPKB));
	            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, tglfromSql($row->TGL_PENYERAHAN_HCC));
				break;
			
				case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
				$interval=strtotime($date_now) - strtotime($row->TGL_BALIK_BPKB);
				$lead_time = ceil($interval / (60 * 60 * 24));
				
	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, tglfromSql($row->TGL_PENYERAHAN_STNK));
	            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, tglfromSql($row->TGL_BALIK_BPKB));
				break;
			
				default://Notis Belum Dimohonkan
				$interval=strtotime($date_now) - strtotime($row->TGL_SO);
				$lead_time = ceil($interval / (60 * 60 * 24));
				
	            $this->libexcel->getActiveSheet()->setCellValue('E'.$no, $row->TRANS_NO_BPKB);
	            $this->libexcel->getActiveSheet()->setCellValue('F'.$no, '');
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

	public function notis_table($list, $pdf=false)
	{
		$jenis = $this->input->get('jenis');

		$date_now = date('Y/m/d');

		$html = '';


		if($pdf == true):
		switch ($jenis) {
			case 2://Notis Belum Selesai
			$html .= '<H3><strong>Laporan Notis Belum Selesai</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;

			case 3://Notis Sudah Selesai
			$html .= '<H3><strong>Laporan Notis Sudah Selesai</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;
		
			case 4://Notis Belum Diserahkan
			$html .= '<H3><strong>Laporan Notis Belum Diserahkan</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
			$html .= '<H3><strong>Laporan Lead Time Pendaftaran Notis Pajak</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
			$html .= '<H3><strong>Laporan Lead Time Penyerahan Notis Pajak</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
			$html .= '<H3><strong>Laporan Notis Belum Diserahkan, STNK Sudah Diserahkan</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;
		
			default://Notis Belum Dimohonkan
			$html .= '<H3><strong>Laporan Notis Belum Dimohonkan</strong> <small class="pull-right">'.date('d/m/Y H:i:s').'</small></H3>';
			break;
		

		}
		endif;

		$html .= '<table id="list_data" class="table '.($pdf == true?'':'table-striped').' table-bordered">';
		$html .= '<thead>';
		// $html .= '<tr class="no-hover"><th colspan="13" ><i class="fa fa-list fa-fw"></i> List Notis</th></tr>';
		$html .= '<tr class="no-hover">';
		$html .= '<th style="width:45px; vertical-align: middle;">No</th>';
		$html .= '<th>Nama Customer</th>';
		$html .= '<th>No Mesin</th>';
		$html .= '<th>Tgl Mohon Faktur</th>';


		switch ($jenis) {
			case 2://Notis Belum Selesai
			$html .= '<th>No Mohon STNK</th>';
			$html .= '<th>Tgl Pinjam</th>';
			break;

			case 3://Notis Sudah Selesai
			$html .= '<th>Tgl Pinjam</th>';
			$html .= '<th>Tgl Krdt</th>';
			break;
		
			case 4://Notis Belum Diserahkan
			$html .= '<th>Tgl Pinjam</th>';
			$html .= '<th>Tgl Krdt</th>';
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
			$html .= '<th>Tgl Pinjam</th>';
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
			$html .= '<th>Tgl Krdt</th>';
			$html .= '<th>Tgl Serh Nts</th>';
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
			$html .= '<th>Tgl STNK Ambil</th>';
			$html .= '<th>Tgl Krdt</th>';
			break;
		
			default://Notis Belum Dimohonkan
			$html .= '<th>No Mohon BPKB</th>';
			$html .= '<th>Harga</th>';
			break;
		

		}
		
		$html .= '<th>Lead Time</th>';
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
		$html .= '<td>'.tglfromSql($value->TGL_SO).'</td>';



		switch ($jenis) {
			case 2://Notis Belum Selesai
			$interval=strtotime($date_now) - strtotime($value->TGL_PINJAM_BPKB);
			$lead_time = ceil($interval / (60 * 60 * 24));
			$html .= '<td>'.$value->TRANS_NO_STNK.'</td>';
			$html .= '<td>'.tglfromSql($value->TGL_PINJAM_BPKB).'</td>';
			break;

			case 3://Notis Sudah Selesai
			$interval=strtotime($value->TGL_BALIK_BPKB) - strtotime($value->TGL_PINJAM_BPKB);
			$lead_time = ceil($interval / (60 * 60 * 24));
			$html .= '<td>'.tglfromSql($value->TGL_PINJAM_BPKB).'</td>';
			$html .= '<td>'.tglfromSql($value->TGL_BALIK_BPKB).'</td>';
			break;
		
			case 4://Notis Belum Diserahkan
			$interval=strtotime($date_now) - strtotime($value->TGL_BALIK_BPKB);
			$lead_time = ceil($interval / (60 * 60 * 24));
			$html .= '<td>'.tglfromSql($value->TGL_PINJAM_BPKB).'</td>';
			$html .= '<td>'.tglfromSql($value->TGL_BALIK_BPKB).'</td>';
			break;
		
			case 5://Lead Time Pendaftaran Notis Pajak
			$interval=strtotime($value->TGL_PINJAM_BPKB) - strtotime($value->TGL_SO);
			$lead_time = ceil($interval / (60 * 60 * 24));
			$html .= '<td>'.tglfromSql($value->TGL_PINJAM_BPKB).'</td>';
			break;
		
			case 6://Lead Time Penyerahan Notis Pajak
			$interval=strtotime($value->TGL_PENYERAHAN_HCC) - strtotime($value->TGL_BALIK_BPKB);
			$lead_time = ceil($interval / (60 * 60 * 24));	
			$html .= '<td>'.tglfromSql($value->TGL_BALIK_BPKB).'</td>';
			$html .= '<td>'.tglfromSql($value->TGL_PENYERAHAN_HCC).'</td>';
			break;
		
			case 7://Notis Belum Diserahkan, STNK Sudah Diserahkan
			$interval=strtotime($date_now) - strtotime($value->TGL_BALIK_BPKB);
			$lead_time = ceil($interval / (60 * 60 * 24));	
			$html .= '<td>'.tglfromSql($value->TGL_PENYERAHAN_STNK).'</td>';
			$html .= '<td>'.tglfromSql($value->TGL_BALIK_BPKB).'</td>';
			break;
		
			default://Notis Belum Dimohonkan
			$interval=strtotime($date_now) - strtotime($value->TGL_SO);
			$lead_time = ceil($interval / (60 * 60 * 24));
			$html .= '<td>'.$value->TRANS_NO_BPKB.'</td>';
			$html .= '<td></td>';
			break;
		
			
		}

		$html .= '<td>'.number_format($lead_time).'</td>';
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
