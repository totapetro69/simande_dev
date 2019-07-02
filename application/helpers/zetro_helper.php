<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');    
 
    /**
     * [lama_execute description]
     * @return [type] [description]
     */
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
    /**
     * [nBulan description]
     * @param  [type]  $bln   [description]
     * @param  boolean $short [untuk nama bulan di singkat]
     * @return [type] String  [nama bulan]
     */
    function nBulan($bln,$short=FALSE){
        $bulan=($short==TRUE)?
                array('','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agst','Sep','Okt','Nov','Des'):
                array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September',
                    'Oktober','November','Desember');
        return $bulan[(int)$bln];    
    }
    function iBulan($bln,$short=FALSE){
        $bulan=($short==TRUE)?
                array('','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agst','Sep','Okt','Nov','Des'):
                array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September',
                    'Oktober','November','Desember');
        return array_search($bln, $bulan);
    }
    /**
     * [nHari description]
     * @param  [type] $hari [description]
     * @return [type]       [description]
     */
    function nHari($hari){
        $nHari=array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
        return $nHari[(int)$hari];    
    }
    /**
     * [blnRomawi description]
     * @param  [type] $bln [description]
     * @return [type]      [description]
     */
    function blnRomawi($bln){
        $bulan=array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XII','XII');
        return $bulan[(int)$bln];    
    }
    function isValidYear($year){
         // Convert to timestamp
         $start_year         =   strtotime(date('Y') - 1000); //1000 Years back
         $end_year           =   strtotime(date('Y')); // Current Year
         $received_year      =   strtotime($year);
         return (($received_year >= $start_year) && ($received_year <= $end_year));
    }
    /**
     * [tglToSql description]
     * @param  string $tgl [description]
     * @return [type]      [description]
     */
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
    /**
     * [tglfromSql description]
     * @param  string $tgl       [description]
     * @param  string $separator [description]
     * @return [type]            [description]
     */
    function tglfromSql($tgl='',$separator='/'){
        ($tgl=='')?
        $tanggal='':
        $tanggal=(strlen($tgl)>=10)?substr($tgl,8,2).$separator.substr($tgl,5,2).$separator.substr($tgl,0,4):
        substr($tgl,6,2).$separator.substr($tgl,4,2).$separator.substr($tgl,0,4);
        return $tanggal;
    }
    /**
     * [tglFromSqlLong description]
     * @param  string $tgl       [description]
     * @param  string $separator [description]
     * @return [type]            [description]
     */
    function tglFromSqlLong($tgl='',$separator='/')
    {
        $tanggal=substr($tgl,8,2).' '.nBulan(substr($tgl,5,2)).' '.substr($tgl,0,4);
        return $tanggal;
    }
    /**
     * [ShortTgl description]
     * @param string  $tgl      [description]
     * @param boolean $withYear [description]
     */
    function ShortTgl($tgl='',$withYear=false){
        ($tgl=='')?
        $tanggal=date('d/m'):
        $tanggal=($withYear==true)?substr($tgl,8,2).'/'.substr($tgl,5,2).'/'.substr($tgl,2,2):substr($tgl,8,2).'/'.substr($tgl,5,2);
        return $tanggal;
    }
    /**
     * [LongTgl description]
     * @param string $tgl [description]
     */
    function LongTgl($tgl=''){
     ($tgl=='')? $tanggal=date('d F Y'):
     $tanggal=substr($tgl,0,2)." ". nBulan(round(substr($tgl,3,2),0))." ". substr($tgl,6,4);
         return $tanggal;
    }
    /**
     * [getNextDays description]
     * @param  [type] $fromdate  [description]
     * @param  [type] $countdays [description]
     * @return [type]            [description]
     */
    function getNextDays($fromdate,$countdays) {
        $dated='';
        $time = strtotime($fromdate); // 20091030
        $day = 60*60*24;
        for($i = 0; $i<$countdays; $i++)
        {
            $the_time = $time+($day*$i);
            $dated = date('Y/m/d',$the_time);
        }
        return $dated;
    }
    /**
     * [getPrevDays description]
     * @param  [type] $fromdate  [description]
     * @param  [type] $countdays [description]
     * @return [type]            [description]
     */
    function getPrevDays($fromdate,$countdays) {
        $dated='';
        $time = strtotime($fromdate); // 20091030
        $day = 60*60*24;
        for($i = 0; $i <$countdays; $i++)
        {
            $the_time = $time-($day*$i);
            $dated = date('Y/m/d',$the_time);
        }
            return $dated;
    }
    /**
     * [compare_date description]
     * @param  [type] $date_1 [description]
     * @param  [type] $date_2 [description]
     * @return [type]         [description]
     */
    function  compare_date($date_1,$date_2){
      list($year, $month, $day) = explode('-', $date_1);
      $new_date_1 = sprintf('%04d%02d%02d', $year, $month, $day);
      list($year, $month, $day) = explode('-', $date_2);
      $new_date_2 = sprintf('%04d%02d%02d', $year, $month, $day);
        
        ($new_date_1 <= $new_date_2)? $data=true:$data=false; 
        return $data;
      }


    /**
     * [tglToSql description]
     * @param  string $tgl [description]
     * @return [type]      [description]
     */
    function onlyDate($tgl=''){
        //input dd/mm/yyyy -->output yyyymmdd
        $tanggal=NULL;
        if($tgl!=''){
            //check format inputan tanggal
            $strtotime=strtotime($tgl);
            $tanggal=date("d/m/Y", $strtotime);
        }
        
        return $tanggal;
    }
      /**
       * [is_json description]
       * @param  [type]  $string [description]
       * @return boolean         [description]
       */
    function is_json($string){
        // decode the JSON data
        $result = json_decode($string);
        
        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
 
        if ($error !== '') {
            // throw the Exception or exit // or whatever :)
            //exit($error);
            return false;
        }
 
        // everything is OK
        return true;
    }
    /**
     * [isBolehAkses fungsi untuk pengaturan hak akses di view]
     * @param  string  $aksi [C,E,V,P default kosong artinya di CEVP di orkan]
     * @return boolean       [true jika 1, false jika 0]
     */
    function isBolehAkses($aksi=''){
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $url=$ci->uri->uri_string();
        if($ci->session->userdata('user_id')=='Superuser' || $ci->session->userdata("nama_group")=='Root'){
            return TRUE;
            exit();
        }
        if($ci->session->userdata()){
            if($aksi!=''){
                return ($ci->session->userdata(strtolower($aksi))==1)?TRUE:FALSE;
            }else if(strlen($aksi)>1){
                $akses=explode(",",$aksi);
                $n=0;
                for($i=0; $i<count($akses);$i++){
                    $n +=($ci->session->userdata($akses[$i]));
                }
                return ($n==count($akses))?TRUE:FALSE;
            
            }else{
 
                if($ci->session->userdata("c")==1 ||
                   $ci->session->userdata("e")==1 ||
                   $ci->session->userdata("v")==1 ||
                   $ci->session->userdata("p")==1){
                       return TRUE;
                }
            }
            
        }else{
            return FALSE;
        }    
    }
    /**
     * [isDealerAkses description]
     * @param  [type]  $string [description]
     * @return boolean         [description]
     */
    function isDealerAkses($debug=null){
        $data=array(); $result=array();
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $param=array(
            'user_id' => $ci->session->userdata('user_id'),
            'auth_status' =>"1",
            'field' =>"KD_DEALER",
            'groupby' => TRUE
        );
        if($ci->session->userdata("kd_dealer")){
            $param["custom"] ="KD_DEALER NOT IN('".$ci->session->userdata("kd_dealer")."')";
        }
        $data=json_decode($ci->curl->simple_get(API_URL."/api/menu/users_area",$param));
        $result[] = ($ci->session->userdata("kd_dealer"))?$ci->session->userdata("kd_dealer"):array();
        if($data){
            if($data->totaldata > 0){
                foreach ($data->message as $key => $value) {
                    $result[]=$value->KD_DEALER;
                }
            }else{
                $result[] = $ci->session->userdata("kd_dealer");
            }
        }else{
            $result[] = $ci->session->userdata("kd_dealer");
        }
        /*if($debug){
            print_r($result);
            var_dump($data);
            exit();
        }*/
        return $result;
    }
    /**
     * [isRoot description]
     * @return boolean [description]
     */
    function isRoot(){
        $data=array(); $result=FALSE;
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $result=($ci->session->userdata("kd_group")=='root')?TRUE:FALSE;
        return $result;
    }
    /**
     * [isApproval description]
     * @param  [type]  $kd_doc [description]
     * @return boolean         [description]
     */
    function isApproval($kd_doc=null,$level=null,$user_id=null,$field='APP_LEVEL'){
        $data=array(); $result="0";
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $param=array(
            "jointable"=> array(array("USERS U","U.USER_ID = USERS_APPROVAL.USER_ID","LEFT")),
            "field" => "USERS_APPROVAL.USER_ID,KD_DOC,APP_LEVEL,U.USER_NAME",
            "orderby" => "KD_DOC"
        );
        if($kd_doc){
            $param["kd_doc"] = $kd_doc;
        }
        if($level){
            $param["app_level"] = $level;
        }
        if($user_id){
            $param["user_id"] = $user_id;
        }else{
            $param["users_id"] =($user_id)? $user_id: $ci->session->userdata("user_id");
        }
        $data=json_decode($ci->curl->simple_get(API_URL."/api/menu/users_approval",$param));
        // var_dump($data);exit();
        if($data){
            if($data->totaldata > 0){
                $result = $data->message;
                if($kd_doc){
                    foreach ($data->message as $key => $value) {
                        $result = $value->{$field};
                    }
                }
            }
        }
        return $result;
    }
    function CountApvDoc($kd_doc=null,$field='LEVEL_APV'){
        $data=array(); $result="0";
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $param=array(
            "field" => "KD_MODUL,NAMA_MODUL,LEVEL_APV",
            "orderby" => "KD_MODUL"
        );
        if($kd_doc){
            $param["kd_modul"] = $kd_doc;
        }
        $data=json_decode($ci->curl->simple_get(API_URL."/api/menu/modul_approval",$param));
        if($data){
            if($data->totaldata > 0){
                $result = $data->message;
                if($kd_doc){
                    foreach ($data->message as $key => $value) {
                        $result = $value->{$field};
                    }
                }
            }
        }
        return $result;
    }
    /**
     * [DropDownMotor description]
     * @param boolean $typemotor [description]
     * @param string  $selected  [description]
     * @param string  $class     [description]
     * @param string  $class2    [description]
     */
    function listDealer(){
        $param=array();
        if(isRoot()){
            $param=array();
        }else{
            $param=array(
                'where_in' =>(isDealerAkses())?isDealerAkses():'MD',
                'where_in_field' =>'KD_DEALER'
            );
        }
        return $param;
    }
    function DropDownMotor($typemotor=false,$selected='',$class='',$class2=''){
        DropDownMotors($typemotor,$selected,'',false,$class,$class2);
    }
    /**
     * [DropDownWarnaMotor description]
     * @param string $selected [description]
     * @param string $id       [description]
     */
    function DropDownWarnaMotor($selected='',$id='_wm'){
        DropDownMotors(false,$selected,$id,true);
    }
    /**
     * [DropDownMotorWithViewStock description]
     * @param boolean $typemotor [description]
     * @param boolean $onlyStock [description]
     * @param string  $selected  [description]
     */
    function DropDownMotorWithViewStock($typemotor=false,$onlyStock=false,$selected=''){
        DropDownMotors($typemotor,$selected,'',false,'','',$onlyStock);
    }
    /**
     * [DropDownMotors description]
     * @param boolean $typemotor [description]
     * @param string  $selected  [description]
     * @param string  $id        [description]
     * @param boolean $warna     [description]
     * @param string  $class     [description]
     * @param string  $class2    [description]
     * @param boolean $onlyStock [description]
     */
    function DropDownMotors($typemotor=false,$selected='',$id='',$warna=false,$class='',$class2='',$onlyStock=false){
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $param=array();
        $stoked="\"ISNULL((SELECT SUM(STOCK_AKHIR) FROM TRANS_STOCKMOTOR AS TS WHERE TS.KD_ITEM=MASTER_P_TYPEMOTOR.KD_ITEM AND KD_DEALER='".
                   $ci->session->userdata("kd_dealer")."' AND STOCK_AKHIR >=0 GROUP BY TS.KD_ITEM;TS.KD_DEALER);0) AS STOCK\"";
        $stoked2="\"ISNULL((SELECT SUM(STOCK_AKHIR) FROM TRANS_STOCKMOTOR TS WHERE TS.KD_GROUPMOTOR=MASTER_P_TYPEMOTOR.KD_TYPEMOTOR AND KD_DEALER='".
                   $ci->session->userdata("kd_dealer")."' GROUP BY TS.KD_GROUPMOTOR;TS.KD_DEALER);0) STOCK\"";
        $param["groupby"]    =($typemotor==false)?false:true;
        $param["field"]        =($typemotor==false)?"KD_ITEM,NAMA_TYPEMOTOR,NAMA_ITEM,KET_WARNA,".$stoked:"KD_TYPEMOTOR,NAMA_TYPEMOTOR,NAMA_PASAR";
        if($warna==true){
            $param=array(
                'groupby' =>true,
                'field'      =>'KD_WARNA,KET_WARNA',
                'orderby' => 'KD_WARNA,KET_WARNA'
            );
            if($selected!=''){
                $param["custom"]="KD_TYPE = '".$selected."'";
            }
        }
        // $param["custom"]    ="TGL_AKHIREFF >=GETDATE()";
        $param["orderby"]    =($typemotor==false)?"KD_ITEM,NAMA_ITEM":"KD_TYPEMOTOR,NAMA_TYPEMOTOR";
        $motor=json_decode($ci->curl->simple_get(API_URL."/api/master/motor",$param));
        //var_dump($param);var_dump($motor);exit();
        $default="";
        if($warna==true){$judul= 'List Warna Motor';}else{$judul= ($typemotor==false && $warna==false)?"List Type Motor":' List Motor';}
        echo '<div class="dropdown '.$class.' '.$class2.'"><input type="hidden" id="kd_item'.$id.'" name="kd_item'.$id.'">
                    <div class="input-group input-append">
                        <input class="form-control" type="text" name="kd_item2'.$id.'" id="kd_item2'.$id.'" aria-haspopup="true" aria-expanded="false" placeholder="'.str_replace("List", "Pilih ", $judul).'" value="'.$selected.'">
                            <div class="input-group-btn '.$class.'" id="xls'.$id.'">
                                <button class="btn btn-default '.$class.' dropdown-toggle" type="button" id="kd_items'.$id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span id="cls'.$id.'" class="caret"></span>
                                </button>
                                <ul id="mnu'.$id.'" class="dropdown-menu multi-column dropdown-menu-right" aria-labelledby="kd_items'.$id.'">
                                    <li class="">';
                                    echo '<table style="min-width:100%" id="list'.$id.'" name="list'.$id.'" class="table tablex table-bordered table-hover">
                                            <thead><tr class="panel-heading"><th colspan="5"><i class="fa fa-list fa-fw"></i>';
                                    echo $judul;
                                    echo '</th></tr></thead>
                                        <tbody>';
                                    if($motor){
                                        
                                        if(is_array($motor->message)){
                                        
                                            foreach ($motor->message as $key => $group) {
                                                $stok=0;
                                                if($typemotor==false && $warna==false){
                                                if($onlyStock==true){
                                                    if($group->STOCK==0){
                                                        continue;
                                                    }
                                                }
                                                echo '<tr onclick="dropdown_item'.$id.'(\''.$group->KD_ITEM.'\',\''.$group->NAMA_ITEM.'\');">
                                                        <td style="white-space: nowrap;">'.$group->KD_ITEM.'</td>
                                                        <td style="white-space: nowrap;">'.$group->NAMA_TYPEMOTOR.'</td>
                                                        <td style="white-space: nowrap;">'.$group->NAMA_ITEM.'&nbsp;&nbsp;<small><label class=\'badge\'>'.$group->STOCK.'</label></small></td>
                                                        <td style="white-space: nowrap;">'.$group->KET_WARNA.'</td>
                                                        <td style="width:45px">&nbsp;</td>
                                                        </tr>';
                                                        if($selected==$group->KD_ITEM){
                                                            $default="dropdown_item".$id."('$group->KD_ITEM','$group->NAMA_ITEM');";
                                                        }
                                                //exit();
                                                }else if($warna==true && $typemotor==false){
                                                    echo '<tr onclick="dropdown_item'.$id.'(\''.$group->KD_WARNA.'\',\''.$group->KET_WARNA.'\');">
                                                        <td style="white-space: nowrap;">'.$group->KD_WARNA.'</td>
                                                        <td style="white-space: nowrap;">'.$group->KET_WARNA.'</td>
                                                        <td style="width:45px">&nbsp;</td>
                                                        </tr>';
                                                        if($selected==$group->KD_WARNA){
                                                            $default="dropdown_item".$id."('$group->KD_WARNA','$group->KET_WARNA');";
                                                        }
                                                }else{
                                                    echo '<tr onclick="dropdown_item'.$id.'(\''.$group->KD_TYPEMOTOR.'\',\''.$group->NAMA_TYPEMOTOR.'\');">
                                                        <td style="white-space: nowrap;">'.$group->KD_TYPEMOTOR.'</td>
                                                        <td style="white-space: nowrap;">'.$group->NAMA_TYPEMOTOR.'</td>
                                                        <td style="white-space: nowrap;">'.$group->NAMA_PASAR.'</td>
                                                        <td style="width:45px">&nbsp;</td>
                                                        </tr>';
                                                        if($selected==$group->KD_TYPEMOTOR){
                                                            $default="dropdown_item".$id."('$group->KD_TYPEMOTOR','$group->NAMA_TYPEMOTOR');";
                                                        }
                                                }
                                            }
                                        
                                        }else{
                                            echo "<tr>";
                                            echo "<td style=\"white-space: nowrap;\" colspan='4'>".$data["motor"]->message."</td></tr>";
                                        }  
                                    
                                    }
        echo "</tbody></table></li></ul></div></div></div>";
        //$status=($typemotor)?1:0;
        if($typemotor==true && $warna==false){
            $status=1;
        }else if($warna==true){
            $status=2;
        }else{
            $status=0;
        }
        ?>
        <script type="text/javascript">
                /*added by iswan putera
                fungsi untuk dropdown motor*/
                $(document).ready(function(){
                    $("#kd_item2<?php echo $id;?>")
                    .keypress(function(e){ 
                        if(e.keyCode == 13){
                            $("#kd_item2<?php echo $id;?>").focusout();
                        }
                    })
                    .focusout(function(){
                        __getdata<?php echo $id;?>(""); 
                    })
                    .click(function(){
                        $(this).select();
                    });
                    <?php echo $default;?>
                    $("#kd_item2<?php echo $id;?>").width(function(n,c){
                        $("#list<?php echo $id;?>").width(c+50);
                    });
                });
                    /**
                     * menampilkan data list motor berdasarkan inputan di kolm kditem
                     * @return {[type]} [description]
                     */
                     function __getdata<?php echo $id;?>(kdtp){
                         var stat="<?php echo $status;?>";
                         var kw = $("#kd_item2<?php echo $id;?>").val();
                         var kd = $("#kd_item<?php echo $id;?>").val();
                         var kwk=kw.split('[');
                         if(kwk.length>1 && kd.length>0){ kw=kd;}else{kw=kw;}
                         var pilih="<?php echo $selected;?>"
                         $("#kd_items<?php echo $id;?> #cls<?php echo $id;?>").html("<i class=\'fa fa-refresh fa-spin fa-fw\'></i>");
                         $.ajax({
                             url:'<?php echo base_url("purchasing/listmotor");?>',
                             type:"POST",
                             dataType: "html",
                             data:{"keyword":kw,"lst":"<?php echo $status;?>","kd_type":kdtp,"lok":"<?php echo $id;?>"},
                             success:function(result){
                                 //console.log(result);
                                 $("#list<?php echo $id;?> tbody").html("");
                                 $("table#list<?php echo $id;?> tbody").append(result);
                                 $("#kd_items<?php echo $id;?> #cls<?php echo $id;?>").html("");
                                 if(pilih==''){
                                     $("#kd_items<?php echo $id;?>").click();
                                 }
 
                             }
 
                         });
                         return false;
                     }
                    /**
                     * mengisi kolom kditem dan nama item di form podetail
                     * @param  {[type]} kd_item   [description]
                     * @param  {[type]} nama_item [description]
                     * @return {[type]}           [description]
                     */
                     function dropdown_item<?php echo $id;?>(kd_item,nama_item){
                         var stat="<?php echo $status;?>";
                         $("#kd_item<?php echo $id;?>").val(kd_item);
                         $("#kd_item2<?php echo $id;?>").val(kd_item+" [ "+nama_item+" ]").attr("title",kd_item+" [ "+nama_item+" ]");
                         try{
                             if(typeof __getdata_warna ==='function'){
                                 if(parseInt(stat)!=2){__getdata_warna(kd_item);};
                             }
                             
                             if(typeof __getharga ==='function'){
                                 __getharga(kd_item);
                             }
                         }finally{
                             return true;
                         }
                     }    
         </script>
        <?php
    }
    function belumAdaData($table=11,$noecho=null){
        if($table!=''){
            if($noecho){
                return "<tr><td colspan=".$table."><i class='fa fa-info-circle'></i> <b>Belum ada data / data tidak ditemukan</b></td></tr>";
            }else{
                echo "<tr>
                    <td>&nbsp;<i class='fa fa-info-circle'></i></td>
                     <td colspan=".$table."><b>Belum ada data / data tidak ditemukan</b></td>
                </tr>";
            }
        }
    }
    /**
     * [DropdownCustomer description]
     * @param string $kd_dealer [description]
     */
    function DropdownCustomer($kd_dealer=''){
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
    }
    /**
     * [DropdownSalesman description]
     * @param string $kd_dealer [description]
     * @param string $tipesales [description]
     * @param string $value     [description]
     * @param string $id        [description]
     */
    function DropdownSalesman($kd_dealer=null,$tipesales=null,$selected=null,$id=null){
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
 
        if($kd_dealer){
            $param["kd_dealer"]=($kd_dealer)?$kd_dealer:$ci->session->userdata("kd_dealer");
        }
        if($tipesales){
            $param["group_sales"] = $tipesales;
        }
        $param["orderby"]    ="NAMA_SALES";
        $param["status_sales"]    ="A";
        $salesman=json_decode($ci->curl->simple_get(API_URL."/api/sales/salesman",$param));
        //var_dump($salesman);exit();
        ?>
            <div class="dropdown" id="pilihsales<?php echo $id;?>"><input type="hidden" id="kd_salesman<?php echo $id;?>" name="kd_salesman<?php echo $id;?>">
                <div class="input-group input-append">
                    <input class="form-control" type="text" id="nama_sales<?php echo $id;?>" name="nama_sales<?php echo $id;?>" aria-hospopup="true" placeholder="input nama sales" value="<?php echo $selected;?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default dropdown-toggle" type="button" id="kd_sales<?php echo $id;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span id="cls<?php echo $id;?>" class="caret"></span>
                        </button>
                        <ul id="menux<?php echo $id;?>" class="dropdown-menu multi-column dropdown-menu-right" aria-labelledby="kd_sales<?php echo $id;?>">
                            <li class="">
                                <!-- <div class="table-responsive"> -->
                                    <table id="listsalesman<?php echo $id;?>" name="listsalesman<?php echo $id;?>" class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="panel-heading">
                                                <th colspan="4"><i class="fa fa-list fa-fw"></i> LIST SALESMAN</th>
                                            </tr>
                                            <tr class="info">
                                                <th>#</th>
                                                <th>KD Sales</th>
                                                <th>Honda ID</th>
                                                <th>Nama Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $default="";
                                                if($salesman){
                                                    $n=0;
                                                    if(($salesman->totaldata > 0)){
                                                        foreach ($salesman->message as $key => $value) {
                                                            $n++;
                                                            echo "<tr onclick=\"dropdown_sales".$id."('".$value->KD_SALES."','".rtrim($value->NAMA_SALES)."');\">
                                                                  <td>".$n."</td>
                                                                  <td>".$value->KD_SALES."</td>
                                                                  <td>".$value->KD_HSALES."</td>
                                                                  <td>".rtrim($value->NAMA_SALES)."</td>
                                                                  </tr>";
                                                              if($selected==$value->KD_SALES){
                                                                $default="dropdown_sales".$id."('".$value->KD_SALES."','".rtrim($value->NAMA_SALES)."');";
                                                            }
                                                        }
                                                    }else{
                                                        echo "<tr><td colspan='4' class='table-nowrap'> <i class='fa fa-info'></i> ".$salesman->message."</td></tr>";
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                <!-- </div> -->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#nama_sales<?php echo $id;?>")
                    .keypress(function(e){ 
                        if(e.keyCode == 13){
                            $("#nama_sales<?php echo $id;?>").focusout();
                        }
                    })
                    .focusout(function(){
                        var grpsales=($('#kd_groupsales').val())
                        grpsales=(grpsales!='undefined')?grpsales:"";
                        //alert(grpsales);
                        switch(grpsales){
                            case "MK":
                                try{
                                    __getsalesmandatamk(grpsales);
                                }finally{
                                    return;
                                }
                            break;
                            default:
                                __getsalesmandata<?php echo $id;?>(grpsales); 
                            break;
                        }
                        
                    });
                    <?php echo $default;?>
                    $("#nama_sales<?php echo $id;?>").width(function(n,c){
                        $("#listsalesman<?php echo $id;?>").width(c+60);
                    });
                });
                function __getsalesmandata<?php echo $id;?>(kdtp){
                    var kw = $("#nama_sales<?php echo $id;?>").val();
                     $("#cls<?php echo $id;?>").html("<i class=\'fa fa-refresh fa-spin fa-fw\'></i>");
                     var pilih="<?php echo $selected;?>"
                     var dlr ="<?php echo ($kd_dealer)?$kd_dealer:$ci->session->userdata("kd_dealer");?>"
                     $.ajax({
                         url:'<?php echo base_url("spk/salesman");?>',
                         type:"POST",
                         dataType: "html",
                         data:{"keyword":kw,"group_sales":kdtp,"lok":"<?php echo $id;?>",'kd_dealer':dlr},
                         success:function(result){
                             $("#listsalesman<?php echo $id;?> tbody").html("");
                             $("table#listsalesman<?php echo $id;?> tbody").append(result);
                             $("#nama_sales<?php echo $id;?>").html("");
                             $("#cls<?php echo $id;?>").html("");
                             if(kdtp!=''){
                                 $("#kd_sales<?php echo $id;?>").click();
                             }
 
                         }
 
                     });
                     return false;
                }
 
                function dropdown_sales<?php echo $id;?>(kd_item,nama_item){
                     $("#kd_salesman<?php echo $id;?>").val(kd_item);
                     $("#nama_sales<?php echo $id;?>").val(kd_item+" [ "+nama_item+" ]");
                 }    
            </script>
        <?php
    }
    /**
     * [DropDownSales description]
     * @param string $id        [description]
     * @param string $kd_dealer [description]
     * @param string $selected  [description]
     */
    function DropDownSales($id=null,$kd_dealer=null,$selected=null){
        DropdownSalesman($kd_dealer,$kd_dealer,$selected,$id);
    }
    /**
     * [loading_proses description]
     * @return [type] [description]
     */
    function loading_proses(){
        echo '<div class="proses-loading text-center hidden" id="loadpage">
                <i class="fa fa-spinner fa-spin fa-4x fa-fw center-block"></i>
                <p class="text-center"></p>
            </div>';
    }
    /**
     * [UmurCustomer description]
     * @param [type] $tgl_lahir [description]
     */
    function UmurCustomer($tgl_lahir){
        if(isValidYear(substr($tgl_lahir, 0,4))){
            
        }
    }
    /**
     * [infoCustomer description]
     * @param  [type] $kdCustomer [description]
     * @return [type]             [description]
     */
    function infoCustomer($kdCustomer=null,$allData=null,$html=null){
        $info=""; $data=array();
        $enter=($html)?"<br>":"\n";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
        $param["kd_customer"]=$kdCustomer;
        $data = json_decode($ci->curl->simple_get(API_URL."/api/laporan/customer",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info .="Nama customer : ".strtoupper($value->NAMA_CUSTOMER).$enter;
                    $info .="Alamat : ".$value->ALAMAT_SURAT." ".strtoupper($value->NAMA_DESA).", KEC. ".$value->NAMA_KECAMATAN.$enter.$value->NAMA_KABUPATEN." ".$value->NAMA_PROPINSI.$enter;
                    $info .="No. Telp : ".$value->NO_HP;
                }
            }
        }
        // var_dump($data);exit();
        return($allData)?$data: $info;
    }
    function NamaDealer($kd_dealer=null,$kodeAHM=null){
        $info="";
        if(strlen(trim($kd_dealer))==0){ return $info;exit();}
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
        $kd_dealer=($kd_dealer)?$kd_dealer:$ci->session->userdata("kd_dealer");
        if($kd_dealer){
            $param["kd_dealer"]=$kd_dealer;
        }
        if($kodeAHM){
            $param["kd_dealerahm"]=$kodeAHM;
        } 
        $data = json_decode($ci->curl->simple_get(API_URL."/api/master/dealer/1/1",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->NAMA_DEALER;
                }
            }
        }
        return $info;
    }
    function KodeDealerAHM($kd_dealer=null,$kodeAHM=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $API=str_replace('frontend','backend/',base_url())."index.php";
        $param=array();
        if($kd_dealer){
            $param["kd_dealer"]=$kd_dealer;
        }
        if($kodeAHM){
            $param["kd_dealerahm"]=$kodeAHM;
        }
 
        $data = json_decode($ci->curl->simple_get(API_URL."/api/master/dealer",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->KD_DEALERAHM;
                }
            }
        }
        return $info;
    }
    function KodeDealer($kd_dealer=null, $kodeAHM=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
        if ($kd_dealer) {
            $param["kd_dealer"]=$kd_dealer;
        }
        if($kodeAHM){
            $param["kd_dealerahm"]=$kodeAHM;
        }
 
        $data = json_decode($ci->curl->simple_get(API_URL."/api/master/dealer",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->KD_DEALER;
                }
            }
        }
        return $info;
    }
    function KotaDealer($kd_dealer=null,$kodeAHM=null,$field='NAMA_KABUPATEN'){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
        );
        if($kd_dealer){
            $param["kd_dealer"]=$kd_dealer;
        }
        if($kodeAHM){
            $param["kd_dealerahm"]=$kodeAHM;
        }
        
        $data = json_decode($ci->curl->simple_get(API_URL."/api/master/dealer/true",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->{$field};
                }
            }
        }
        return $info;
    }
    function KodeMainDealer($kd_dealer=null,$kodeAHM=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
        );
        if($kd_dealer){
            $param["kd_dealer"]=$kd_dealer;
        }
        if($kodeAHM){
            $param["kd_dealerahm"]=$kodeAHM;
        }
        
        $data = json_decode($ci->curl->simple_get(API_URL."/api/master/dealer/true",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->KD_MAINDEALER;
                }
            }
        }
        return $info;
    }
    function NamaMainDealer($kd_dealer=null,$kodeAHM=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
        );
        if($kd_dealer){
            $param["kd_dealer"]=$kd_dealer;
        }
        if($kodeAHM){
            $param["kd_dealerahm"]=$kodeAHM;
        }
        
        $data = json_decode($ci->curl->simple_get(API_URL."/api/master/dealer/true",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->NAMA_MAINDEALER;
                }
            }
        }
        return $info;
    }
    function PartDefaultBin($part_number=null,$output=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
            'kd_dealer' => $ci->session->userdata("kd_dealer")
        );
        if($part_number){
          $param["part_number"] = $part_number; 
        }
        $kd_lokasi="";$kd_gudang="";
        $data=json_decode($ci->curl->simple_get(API_URL."/api/marketing/parts_vs_defaultrak", $param));
        if($data){
          if($data->totaldata >0){
            foreach ($data->message as $key => $value) {
                $kd_lokasi =($output)? $value->{$output}:"";
                $kd_gudang = $value->KD_LOKASI .":".$value->KD_GUDANG;
            }
          }
        }
        if($output){
            return $kd_lokasi;
        }else{
            return $kd_gudang;
        }
    }
    function NamaGudang($kd_gudang,$kd_dealer,$field='NAMA_GUDANG'){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
            'kd_dealer' => $kd_dealer,
            'kd_gudang' => $kd_gudang
        );
        $data=json_decode($ci->curl->simple_get(API_URL."/api/master/gudang", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =$value->{$field};
                }
            }
        }
        return $info;
    }
    function isSIMParts($part_number,$kd_dealer=null){
        $info=0;
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
        );
        $param=array(
            'kd_dealer'=>($kd_dealer)?$kd_dealer:$ci->session->userdata("kd_dealer"),
            'field' => "KATEGORI_AHASS"
        );
        $datad=json_decode($ci->curl->simple_get(API_URL."/api/master/dealer", $param));
        $kategori_ahass="A";
        if($datad){
            if($datad->totaldata>0){
                unset($param["field"]);
                foreach ($datad->message as $key => $value) {
                    $kategori_ahass = $value->KATEGORI_AHASS;
                }
            }

        }
        $param=array(
            "kategori_ahass" => $kategori_ahass,
            "part_number"   => $part_number
        );
        
        
        $data = json_decode($ci->curl->simple_get(API_URL."/api/sparepart/sim_parts", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =(int) $value->JUMLAH_STANDARITEM_MIN;
                }
            }
        }
        return $info;
    }
    function PartName($part_number,$field="PART_DESKRIPSI"){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
            'part_number'=>$part_number
        );
        $data = json_decode($ci->curl->simple_get(API_URL."/api/sparepart/part", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =$value->{$field};
                }
            }
        }
        return $info;
    }
    function NoMesin($no_rangka,$field='NO_MESIN',$rangka=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        if($rangka){
            $param=array("no_mesin" => $no_rangka);
        }else{
            $param=array(
                'no_rangka'=>$no_rangka
            );
        }
        $data = json_decode($ci->curl->simple_get(API_URL."/api/inventori/terimamotor", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =$value->{$field};
                }
            }
        }
        return $info;
    }
    function getConfig($kd_config=null){
        $result="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param =array("kd_config" => $kd_config);
        $hasil = json_decode($ci->curl->simple_get(API_URL."/api/login/config_app",$param));
        //print_r($param);var_dump($hasil);exit();
        if($hasil){
            if($hasil->totaldata>0){
                foreach ($hasil->message as $key => $value) {
                    $result = $value->VALUE_CONFIG;
                }
            }
        }
        return trim($result);
    }
    function sentence_case($string) {
        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $new_string = '';
        foreach ($sentences as $key => $sentence) {
            $new_string .= ($key & 1) == 0?
                ucwords(strtolower(trim($sentence))) :
                $sentence.' ';
        }
        return trim($new_string);
    } 
    function link_pagination(){
        $string = count(explode('&page=',$_SERVER["REQUEST_URI"])) > 1? explode('&page=',$_SERVER["REQUEST_URI"]):explode('?page=',$_SERVER["REQUEST_URI"]);
        return $string;
    }
    function get_perkiraan($kd_akun,$field="NAMA_AKUN"){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
            'kd_akun'=>$kd_akun
        );
        $data = json_decode($ci->curl->simple_get(API_URL."/api/accounting/kdakun", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =$value->{$field};
                }
            }
        }
        return $info;
    }
    function gitversion() {
        exec('git describe --always',$version_mini_hash);
        exec('git rev-parse HEAD | wc -l',$version_number);
        exec('git log -1',$line);
        //$version['short'] = "v1.".trim($version_number[0]).".".$version_mini_hash[0];
        //$version['full'] = "v1.".trim($version_number[0]).".$version_mini_hash[0] (".str_replace('commit ','',$line[0]).")";
        //return $version;
        return json_encode($version_mini_hash);
    }
    function get_current_git_commit() {
      exec("git log -1 --pretty=format:'%h - %s (%ci)' --abbrev-commit",$conf);
      return json_encode($conf);
    }
    function super_unique($array,$key){
       $temp_array = [];
       foreach ($array as &$v) {
           if (!isset($temp_array[$v[$key]]))
           $temp_array[$v[$key]] =& $v;
       }
       $array = array_values($temp_array);
       return $array;
    }
    function NamaUser($user_id,$field="USER_NAME"){
        $data=array(); $result="0";
        $ci =& get_instance();
        $ci->load->library('curl');
        $ci->load->library('session');
        $param=array(
            "user_id" => $user_id
        );
        $data=json_decode($ci->curl->simple_get(API_URL."/api/login/user",$param));
        if($data){
            if($data->totaldata > 0){
                foreach ($data->message as $key => $value) {
                    $result = $value->{$field};
                }
            }
        }
        return $result;
    }
    function NamaWilayah($jenis_wiayah=null,$kd_wilayah=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();$field="";
        switch($jenis_wiayah){
            case 'Propinsi':
                $param=array("kd_propinsi"=>$kd_wilayah);
                $data=json_decode($ci->curl->simple_get(API_URL."/api/master_general/propinsi",$param));
                $field="NAMA_PROPINSI";
            break;
            case 'Kabupaten':
                $param=array("kd_kabupaten"=>$kd_wilayah);
                $data= json_decode($ci->curl->simple_get(API_URL."/api/master_general/kabupaten",$param));
                $field = "NAMA_KABUPATEN";
            break;
            case 'Kecamatan':
                $param=array("kd_kecamatan"=>$kd_wilayah);
                $data=json_decode($ci->curl->simple_get(API_URL."/api/master_general/kecamatan",$param));
                $field = "NAMA_KECAMATAN";
            break;
            case 'Desa':
                $param=array("kd_desa"=>$kd_wilayah);
                $data= json_decode($ci->curl->simple_get(API_URL."/api/master_general/desa",$param));
                $field = "NAMA_DESA";
            break;
        }
        if($kd_wilayah){       
            if($data){
                if($data->totaldata>0){
                    foreach ($data->message as $key => $value) {
                        $info = $value->{$field};
                    }
                }
            }
        }
        return $info;
    }
    function RingArea($kd_kabupaten=null,$field=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
        $param=array('kd_kabupaten'=>$kd_kabupaten);
        $data= json_decode($ci->curl->simple_get(API_URL."/api/master_general/ring_area",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->{$field};
                }
            }
        }
        return $info;
    }
    function LokasiDealer($kd_dealer=null,$kd_lokasi=null,$field=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
        if($kd_lokasi){
            $param=array(
                'kd_dealer' => $kd_dealer,
                'kd_lokasi' => $kd_lokasi
            );
        }
        if(!$field){
            $field="NAMA_LOKASI";
        }
        $data= json_decode($ci->curl->simple_get(API_URL."/api/master_general/lokasidealer",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->{$field};
                }
            }
        }
        return $info;
    }

    function NamaBank($kd_dealer=null,$kd_bank=null,$field=null){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array();
        if($kd_bank){
            $param=array(
                'kd_dealer' => $kd_dealer,
                'kd_bank' => $kd_bank
            );
        }
        if(!$field){
            $field="NAMA_BANK";
        }
        $data= json_decode($ci->curl->simple_get(API_URL."/api/accounting/bank",$param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info = $value->{$field};
                }
            }
        }
        return $info;
    }
    /**
     * isCabang digunakan untuk menentukan delaer tersebut group Cabang trio atau deler independent
     * @param  [string]  $kd_dealer [Kode Dealer]
     * @return String            [Y=Cabang ; T=Dealer]
     */
    function isCabang($kd_dealer){
        return KotaDealer($kd_dealer,null,"KD_JENISDEALER");
    }
    function NamaBarang($kd_barang,$field='NAMA_BARANG'){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
            'kd_barang'=>$kd_barang
        );
        $data = json_decode($ci->curl->simple_get(API_URL."/api/inventori/barang", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =$value->{$field};
                }
            }
        }
        return $info;
    }
    function NamaSalesProgram($kd_salesProgram,$field='NAMA_SALESPROGRAM'){
        $info="";
        $ci =& get_instance();
        $ci->load->library('curl');
        $param=array(
            'kd_salesprogram'=>$kd_salesProgram
        );
        $data = json_decode($ci->curl->simple_get(API_URL."/api/setup/salesprogram", $param));
        if($data){
            if($data->totaldata>0){
                foreach ($data->message as $key => $value) {
                    $info =$value->{$field};
                }
            }
        }
        return $info;
    }
?>