<?php 
	function lama_execute(){
		$awal = microtime(true);
		
		// --- bagian yang akan dihitung execution time --
		
		$bil = 2;
		$hasil = 1;
		for ($i=1; $i<=10000000; $i++)
		{
			 $hasil .= $bil;
		}
		
		// --- bagian yang akan dihitung execution time --
		
		$akhir = microtime(true);
		$lama = $akhir - $awal;
		return $lama;
	}
	
	function nBulan($bln){
		$bulan=array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September',
					'Oktober','November','Desember');
		return $bulan[(int)$bln];	
	}
	function nBulanS($bln)
	{
		$bulan=array('','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agst','Sep','Okt','Nov','Des');
		return $bulan[(int)$bln];	

	}
	function nHari($hari)
	{
		$nHari=array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
		return $nHari[(int)$hari];	
	}
	function blnRomawi($bln)
	{
		$bulan=array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XII','XII');
		return $bulan[(int)$bln];	
	}
	function isValidYear($year)
	{
	     // Convert to timestamp
	     $start_year         =   strtotime(date('Y') - 1000); //1000 Years back
	     $end_year           =   strtotime(date('Y')); // Current Year
	     $received_year      =   strtotime($year);

	    // Check that user date is between start & end
	    return (($received_year >= $start_year) && ($received_year <= $end_year));
	}
	function tglToSql($tgl=''){
		//input dd/mm/yyyy -->output yyyymmdd
		$tanggal=NULL;
		if($tgl!=''){
			//check format inputan tanggal
			if(isValidYear(substr(0,4))){
				$tanggal=str_replace("/","",str_replace("-", "", $tgl));
			}else{
				$tanggal=substr($tgl,6,4).substr($tgl,3,2).substr($tgl,0,2);
			}
		}
		
		return $tanggal;
	}
	function tglfromSql($tgl='',$separator='/'){
		($tgl=='')?
		$tanggal='':
		$tanggal=substr($tgl,8,2).$separator.substr($tgl,5,2).$separator.substr($tgl,0,4);
		return $tanggal;
	}
	function tglFromSqlLong($tgl='',$separator='/')
	{
		$tanggal=substr($tgl,8,2).' '.nBulan(substr($tgl,5,2)).' '.substr($tgl,0,4);
		return $tanggal;
	}
	function ShortTgl($tgl='',$withYear=false){
		($tgl=='')?
		$tanggal=date('d/m'):
		$tanggal=($withYear==true)?substr($tgl,8,2).'/'.substr($tgl,5,2).'/'.substr($tgl,2,2):substr($tgl,8,2).'/'.substr($tgl,5,2);
		return $tanggal;
	}
	function LongTgl($tgl=''){
	 ($tgl=='')? $tanggal=date('d F Y'):
	 $tanggal=substr($tgl,0,2)." ". nBulan(round(substr($tgl,3,2),0))." ". substr($tgl,6,4);
	 	return $tanggal;
	}
	
	
	function getNextDays($fromdate,$countdays) {
		$dated='';
		$time = strtotime($fromdate); // 20091030
		$day = 60*60*24;
		for($i = 0; $i<$countdays; $i++)
		{
			$the_time = $time+($day*$i);
			$dated = date('Y-m-d',$the_time);
		}
			return $dated;
    }
	function getPrevDays($fromdate,$countdays) {
		$dated='';
		$time = strtotime($fromdate); // 20091030
		$day = 60*60*24;
		for($i = 0; $i <$countdays; $i++)
		{
			$the_time = $time-($day*$i);
			$dated = date('Ymd',$the_time);
		}
			return $dated;
    }
	function  compare_date($date_1,$date_2){
	  list($year, $month, $day) = explode('-', $date_1);
	  $new_date_1 = sprintf('%04d%02d%02d', $year, $month, $day);
	  list($year, $month, $day) = explode('-', $date_2);
	  $new_date_2 = sprintf('%04d%02d%02d', $year, $month, $day);
		
		($new_date_1 <= $new_date_2)? $data=true:$data=false; 
		return $data;
  	}
	function getConfig($kd_config=null){
		$result="";
		$ci = & get_instance();
		$ci->load->model("main_model");
		$param=array();
        if($kd_config){
            $param["KD_CONFIG"] = $kd_config;
        }
        $ci->Main_model->tabel_name("CONFIG_APP");
        $ci->Main_model->set_parameter($param);
        $hasil = ($ci->Main_model->response_result("get"));
        $result=($hasil["message"][0]["VALUE_CONFIG"]);
        return ($result);
	}
	function working_days($year=null,$month=null,$offDay=2){
		$workdays = array();
		$type = CAL_GREGORIAN;
		$month =($month)?$month: date('m'); // Month ID, 1 through to 12.
		$year =($year)?$year:date('Y'); // Year in 4 digit 2009 format.
		$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

		//loop through all days
		for ($i = 1; $i <= $day_count; $i++) {

		        $date = $year.'/'.$month.'/'.$i; //format date
		        $get_name = date('l', strtotime($date)); //get week day
		        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

		        //if not a weekend add day to array
		        if($offDay==2){
			        if($day_name != 'Sun' && $day_name != 'Sat'){
			            $workdays[] = $i;
			        }
			    }else{
			    	if($day_name != 'Sun'){
			            $workdays[] = $i;
			        }
			    }

		}
		return $workdays;
	}
?>