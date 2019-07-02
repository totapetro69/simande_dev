<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Main model
 * Author 	: Iswan Putera {zetrosoft}
 */
class Main_model extends CI_Model
{
	/**
	 * [__construct description]
	 */
	function __construct(){
		parent::__construct();
	}

	public $selectfield;
	public $search_criteria;
	public $offset=0;
	public $recordlimit;
	public $withjoin;
	public $groupby=FALSE;
	public $customcriteria;
	public $jointable;
	/**
	 * [tabel_name description]
	 * @param  [type] $tabel [description]
	 * @return [type]        [description]
	 */
	public function tabel_name($tabel){
		$this->tabel_name=$tabel;
	}
	/**
	 * [get_tablename description]
	 * @return [type] [description]
	 */
	function get_tablename(){
		return $this->tabel_name;
	}
	function set_reuseddata($y=null){
		$this->reuseddata=$y;
	}
	function get_reuseddata(){
		return $this->reuseddata;
	}
	/**
	 * [record_exists description]
	 * @return [type] [description]
	 */
	function record_exists($getID=null){
		if(isset($this->customcriteria)){
			$result = preg_replace('/ ([!]{0,1})(<)([=]{0,1}) /', '$1&lt;$3', $this->get_customcriteria());
			$result = preg_replace('/ ([!]{0,1})(>)([=]{0,1}) /', '$1&lt;$3', $result);
			$this->db->where(html_entity_decode($result));
		}
		
		$this->db->where($this->get_parameter());
		if($this->get_tablename()!='USERS'){
			// $this->db->where('ROW_STATUS >','-1');
		}
		$this->db->from($this->get_tablename());
		if(isset($this->reuseddata)){
			// baris ini digunakan untuk mandapatkan last id dari data yng status nya di hapus (row_status =-1)
			if(!isset($this->orderby)){	$this->db->order_by("ID DESC");}			
			$this->db->limit('1');
			return $this->db->get()->row(); 
			
		}else{
			return $this->db->get()->num_rows();
		}
		//return $this->db->get()->num_rows();
	}
	/**
	 * set selected field which will query
	 * @param $selected string with - delimiter | NULL for all field (*)
	 * ex: field1-field2-field3
	 */
	public function set_selectfield($selected=NULL){
		$select = str_replace("-", ",",$selected);
		$select = str_replace("koma", ",",$selected);
		$select = str_replace("min", "-", $select);
		$select = str_replace("plus", "+", $select);
		$select = str_replace("div", "/", $select);
		$select = str_replace("multi", "*", $select);
		$select = str_replace("smaller", "<", $select);
		$select = str_replace("bigger", ">", $select);
		$select = str_replace("somesmall", "<=", $select);
		$select = str_replace("somebigger", ">=", $select);
		$this->selectfield = $select ;
	}
	/**
	 * [get_selectfield description]
	 * @return [type] [description]
	 */
	public function get_selectfield(){
		return ($this->selectfield);
	}
	/**
	 * Set parameter cquery criteria
	 * @param $parameter array|NULL
	 */
	public function set_parameter($parameter= NULL){
		$parameter=str_replace("nol", "0", $parameter);
		$this->parameter = $parameter;
	}
	/**
	 * [get_parameter description]
	 * @return [type] [description]
	 */
	public function get_parameter(){ 
		return $this->parameter;
	}

	/**
	 * [set_offset description]
	 * @param [type] $offset [description]
	 */
	public function set_offset($offset = NULL){
		$this->offset = $offset;
	}
	/**
	 * [get_offset description]
	 * @return [type] [description]
	 */
	function get_offset(){
		return $this->offset;
	}
	/**
	 * [set_recordlimit description]
	 * @param [type] $limit [description]
	 */
	public function set_recordlimit($limit=NULL){
		$this->recordlimit=$limit;
	}
	/**
	 * [get_recordlimit description]
	 * @return [type] [description]
	 */
	public function get_recordlimit(){
		return $this->recordlimit;
	}
	/**
	 * [set_totalrecord description]
	 * @param integer $total [description]
	 */
	public function set_totalrecord($total=0){
		$this->totalrecord=$total;
	}
	/**
	 * [get_totalrecord description]
	 * @return [type] [description]
	 */
	public function get_totalrecord(){
		return $this->totalrecord;
	}
	/**
	 * set join table
	 * @param $withjoin array(array()) |NULL
	 * ex.: $jointable =array(array(table1,tabel1.id=table.id,left));
	 */
	function set_jointable($withjoin=array()){
		$this->withjoin = $withjoin;
	}
	/**
	 * [get_jointable description]
	 * @return [type] [description]
	 */
	function get_jointable(){
		return $this->withjoin;
	}
	/**
	 * [set_groupby description]
	 * @param boolean $groupby [description]
	 */
	function set_groupby($groupby= NULL){
		$this->groupby=$groupby;
	}
	/**
	 * [get_groupby description]
	 * @return [type] [description]
	 */
	function get_groupby(){
		return $this->groupby;
	}
	function set_groupby_text($groupby=NULL){
		$this->groupby_text=$groupby;
	}
	function get_groupby_text(){
		return $this->groupby_text;
	}
	/**
	 * [set_orderby description]
	 * @param [type] $orderby [description]
	 */
	function set_orderby($orderby=NULL){
		$this->orderby = $orderby;
	}
	/**
	 * [get_orderby description]
	 * @return [type] [description]
	 */
	function get_orderby(){
		return $this->orderby;
	}
	/**
	 * set criteria for search like %%
	 * @param  $[criteria] [<array>]
	 */
	public function set_search_criteria($criteria=NULL){
		$this->search_criteria=$criteria;
	}
	/**
	 * [get_search_criteria description]
	 * @return [type] [description]
	 */
	public function get_search_criteria(){
		return $this->db->escape_str($this->search_criteria);
	}
	/**
	 * [set_customcriteria untuk query select dengan criteria bukan sama dengan]
	 * @param [type] $custom [description]
	 */
	function set_customcriteria($customcriteria=NULL){
		$this->customcriteria=($customcriteria)?str_replace("xplus","+",$customcriteria):$customcriteria;
		// $this->customcriteria=($customcriteria)?str_replace("_edh","edh",$customcriteria):$customcriteria;
	}
	/**
	 * [get_customcriteria description]
	 * @return [type] [description]
	 */
	function get_customcriteria(){
		return $this->customcriteria;
	}
	/**
	 * [set_location description]
	 * @param [type] $location [description]
	 */
	function set_location($location = NULL){
		$this->location=$location;
	}
	/**
	 * [get_location description]
	 * @return [type] [description]
	 */
	function get_location(){
		return $this->location;
	}
	/**
	 * [set_statusdata description]
	 * @param [type] $statusdata [description]
	 */
	function set_statusdata($statusdata=0){
		$this->statusdata=$statusdata;
	}
	/**
	 * [get_statusdata description]
	 * @return [type] [description]
	 */
	function get_statusdata(){
		return $this->statusdata;
	}
	function set_havings($having=NULL){
		$this->havings=$having;
	}
	function get_havings(){
		return $this->havings;
	}
	/**
	 * set your manual query
	 * @param [type] $custom_query [description]
	 */
	function set_custom_query($custom_query=NULL){
		$this->custom_query=$custom_query;
	}
	function get_custom_query(){
		return $this->custom_query;
	}
	function set_jsons($query=NULL){
		$this->jsons=$query;
	}
	function get_jsons(){
		return $this->jsons;
	}
	function set_batches($batch=NULL){
		$this->batches=$batch;
	}
	function get_batches(){
		return $this->batches;
	}
	function set_custom_rowstatus($custom_row=null){
		$this->custom_rowstatus=$custom_row;
	}
	function get_custom_rowstatus(){
		return $this->custom_rowstatus;
	}
	function set_where_in($where_in=null){
		$this->where_in = $where_in;
	}
	function set_whereinfield($field=null){
		$this->whereinfield =$field;
	}
	function get_where_in(){
		return $this->where_in;
	}
	function get_whereinfield(){
		return $this->whereinfield;
	}
	function set_wheregroup($grouped=null){	$this->wheregroup=$grouped;}
	function get_wheregroup(){return $this->wheregroup;}
	function set_wheregroup_type($tpe_where=null){$this->wheregroup_type=$tpe_where;}
	function get_wheregroup_type(){	return $this->wheregroup_type;}
	function set_where_or($where_or=null){$this->where_or = $where_or;}
	function set_where_or_field($field=null){$this->where_or_field =$field;}
	function get_where_or(){return $this->where_or;}
	function get_where_or_field(){return $this->where_or_field;}
	/**
	 * Groupping where on grouping where
	 */
	function set_wheregroupout($grouped=null){	$this->wheregroupout=$grouped;}
	function get_wheregroupout(){return $this->wheregroupout;}
	function set_wheregroup_typeout($tpe_where=null){$this->wheregroup_typeout=$tpe_where;}
	function get_wheregroup_typeout(){	return $this->wheregroup_typeout;}
	function set_where_orout($where_or=null){$this->where_orout = $where_or;}
	function get_where_orout(){return $this->where_orout;}
	function set_where_or_fieldout($field=null){$this->where_or_fieldout =$field;}
	function get_where_or_fieldout(){return $this->where_or_fieldout;}
	/**
	 * query untuk menampilkan data dari database
	 * @return [array] [array result]
	 */
	function show_list_array($result_array=TRUE,$totaldatax=FALSE){
		$data=array();
		if(isset($this->selectfield)){
			$this->db->select(str_replace(";",",",str_replace("\"","",$this->get_selectfield())));
		}
		if($totaldatax==FALSE){
			if(isset($this->offset) && isset($this->recordlimit)){
				$this->db->limit($this->get_recordlimit(),$this->get_offset());
			}
		}/*else{
			$this->set_selectfield("COUNT(ID) AS ID");
		}*/
		
		
		if(isset($this->customcriteria)){
			$resulte = preg_replace('/ ([!]{0,1})([!]{0,1}) /', '$1&lt;$3', $this->get_customcriteria());
			$resulte = preg_replace('/ ([!]{0,1})([!]{0,1}) /', '$1&lt;$3', $resulte);
			$this->db->where(html_entity_decode($resulte));
		}

		/*$join= array(
                        array('TRANS_STNK K', 'K.ID=TRANS_STNK_DETAIL.STNK_ID', 'LEFT')
                        );
		$this->set_jointable($join);*/

		$this->db->from($this->get_tablename());
		$withJoin = $this->get_jointable();
		if($withJoin!='' || $withJoin !=NULL){ 
			//menambahkan alias di setiap field criteria
			$keyword="";$value="";
			if(isset($this->search_criteria)&& $this->get_search_criteria()!=''){
				$search=$this->array_map_assoc(function($k,$v){
					$keys=explode(".",$k);	$newArr=array();
					if(count($keys)<=1){
						$newArr[$this->tabel_name.".".$k]=$v;
					}else{
						$newArr[$k]=$v;
					}
					return $newArr;
				},$this->search_criteria);

				$this->set_search_criteria($search);
			}
			//menambah alias di field paramater
			if(isset($this->parameter) && count($this->parameter)>0){
				$par="";
				foreach ($this->parameter as $key =>$value ) {
					$par= $key;
				}
				$par=explode(".", $par);
				if(count($par)<=1){
					$params=$this->array_key_prefix_suffix($this->parameter,$this->tabel_name.".");	
				}else{
					$params = $this->parameter;
				}
				//echo count($par);
				//print_r($param);
				$this->set_parameter($params);
			}
			
			for($i=0;$i< count($withJoin);$i++){
				$join=$withJoin[$i];
				if(count($join)>3){
					$tbl=explode(' AS ', $join[0]);
					$alias=(count($tbl)>1)?$tbl[1]:"XXD";
					$this->db->join('(SELECT DISTINCT '.$join[3].' FROM '.$tbl[0].') AS '.$alias,$join[1],$join[2]);
				}else{
					$this->db->join($join[0],$join[1],$join[2]);
				}
			}	
		}
		
		if(isset($this->statusdata)){
			switch ($this->get_tablename()) {
				case 'USERS':
					switch ($this->get_statusdata()) {
						case "-2":
							$this->db->where('USERS.KD_STATUS > -2');
							break;
						case "-1":
							$this->db->where('USERS.KD_STATUS = -1');
							break;
						
						default:
							$this->db->where('USERS.KD_STATUS >= 0');
							break;
					}
					break;
				
				default:
					switch ($this->get_statusdata()) {
						case "-2":
							$this->db->where($this->get_tablename().'.ROW_STATUS > -2');
							break;
						case "-1":
							$this->db->where($this->get_tablename().'.ROW_STATUS = -1');
							break;
						case "[-2]":
							$this->db->where($this->get_tablename().'.ROW_STATUS = -2');
							break;
						case "[-3]":
							$this->db->where($this->get_tablename().'.ROW_STATUS < 0');
							break;
						default:
							$this->db->where($this->get_tablename().'.ROW_STATUS >= 0');
							break;
					}
				break;
			}
		}else{
			switch ($this->get_tablename()) {
				case 'USERS': $this->db->where('USERS.KD_STATUS >= 0');	break;
				default:$this->db->where($this->get_tablename().'.ROW_STATUS >= 0');break;
			}
		}
		
		if(isset($this->search_criteria)){
			if($this->get_search_criteria()!=''){
				$where_like ='(';
				$or_counter ='';
				$i=0;
				foreach ($this->get_search_criteria() as $key => $value) {
					$or_counter	=($i > 0)?' OR ':'';
					$newval		=$this->db->escape_like_str($value);
					$where_like.=$or_counter."$key LIKE '%$newval%'";
					$i++;
				}
				$where_like .=')';
				$this->db->where($where_like);
			}
			
		}
		//where paramater criteria
		if(isset($this->parameter)){			
			$this->db->where($this->get_parameter());
		}
		if(isset($this->where_in)){
			if(isset($this->wheregroup)){
				if(isset($this->wheregroupout)){
					($this->db->group_start());
				}
				$this->db->group_start();
				$this->db->where_in($this->get_whereinfield(),$this->get_where_in());
				if($this->get_wheregroup_type()=='OR'){
					$this->db->or_where($this->get_where_or_field(),$this->get_where_or());
				}else if($this->get_wheregroup_type()=='AND'){
					$this->db->where_in($this->get_where_or_field(),$this->get_where_or());
				}
				$this->db->group_end();
				if(isset($this->wheregroupout)){
					//echo $this->get_where_or_fieldout();
					if($this->get_wheregroup_typeout()=='OR'){
						$this->db->or_where($this->get_where_or_fieldout(),$this->get_where_orout());
					}else if($this->get_wheregroup_typeout()=='AND'){
						$this->db->where_in($this->get_where_or_fieldout(),$this->get_where_orout());
					}
					$this->db->group_end();
				}
			}else{
				$this->db->where_in($this->get_whereinfield(),$this->get_where_in());
			}
		}
		
		$groupby="";$formula="";
		if(isset($this->groupby)){
			$grb=array();
			if(($this->get_groupby())==TRUE){
				$groupby= str_getcsv($this->get_selectfield(),",","'");
				for($x=0;$x<count($groupby);$x++){
					$notformula=explode("(",$groupby[$x]);

					if(count($notformula)===1){
						$grb[]=$notformula[0];
						
					}
					if(count($notformula)>=2){
						$formula=explode("AS ",strtoupper(str_replace("\"","",$groupby[$x])));
						switch (strtoupper(trim($notformula[0]))) {
							case 'YEAR':
							case 'MONTH':
								$grb[]=$formula[0];
								break;
							case 'ISNULL':
								$grb[]=(count($formula)>1)?$formula[1]:$formula[0];
								break;
							default:
								# code...
								break;
						}
					}
					$this->db->group_by($grb);
				}
			}
		}
		if(isset($this->groupby_text)){
			if(strlen($this->get_groupby_text())>0){
				$this->db->group_by($this->get_groupby_text());
			}
		}
		//having proses
		if(isset($this->havings)){
			$this->db->having($this->get_havings());
		}
		//order by
		if(isset($this->orderby)){
			$this->db->order_by($this->get_orderby());
		}
		// var_dump($this->db);
		// exit();
		

		if(isset($this->custom_query)){
			if(!$totaldatax){
				$data = $this->db->query($this->get_custom_query())->result_array();
				//var_dump($data);exit();
			}else{
				$data = $this->db->query(($this->get_custom_query()))->num_rows();
			}

		}else{
			if(!$totaldatax){
				$data =($result_array)? $this->db->get()->result_array():$this->db->get()->result();
			}else{
				$data= $this->db->count_all_results();
			}
		}
		//$this->db->last_query();
		return $data ;
	}

	/**
	 * [insert_data description]
	 * @return [type] [description]
	 */
	function insert_data(){
			$query="";
			$data = $this->parameter;
				$fld='';$value=''; $query="";
	            foreach ($data as $key => $value) {
	            	$fld .="@".$key."='".$this->db->escape_str($value)."',";
	            }
	            $fld=substr($fld,0,(strlen($fld)-1)); //remove last delimiter
	            $procedure='exec '.$this->tabel_name." ".$fld.""; 
	 		    $query = $this->db->query($procedure)->row();
            return $query; // return id autoincrement
     }
     function insert_batch(){
     	 // ini_set('memory_limit','10240M');
     	 // ini_set('max_input_vars','10000');
     	$query="";
     	$param = $this->get_parameter();
     	$query = $this->insert_batch($this->get_tablename(),$param);
     	return $query;
     }
     function openjson(){
     	ini_set('max_execution_time',0);
     	$query = $this->db->query($this->get_jsons());
     	//$this->set_custom_query($this)
        return $query; 
     }
     /**
      * [total_record description]
      * diperlukan untuk perhitungan paging
      * @return [integer] [jumlah data yang ada sesuai dengan criteria query]
      */
     
     function total_record(){
     	$num_rows = $this->show_list_array(FALSE,TRUE);
		return $num_rows ;
     }
    
    /**
     * [response_result description]
     * @param  string  $method [description]
     * @param  boolean $status [description]
     * @return [type]          [description]
     */
	function response_result($method='get',$status=FALSE){
		$result = array(); 
		$data	= array();
		switch (strtolower($method)) {
			case 'get':
				# code...
				$data 	= $this->show_list_array();
				
				//echo $ttr; exit();
				if(count($data)==0){
					$result["status"]	= FALSE;
					$result["message"] 	= "Belum ada data / data tidak di temukan";
					$result["totaldata"]= 0;
					$result["param"]	= $this->db->last_query();
				}else{
					$result["status"]	= TRUE;
					$result["message"] 	= $data;
					$result["param"]	= $this->db->last_query();
					$ttr=$this->total_record();
					$result["totaldata"]= $ttr;
				}
		
				break;
			case 'json':
				$data= $this->openjson();
				if(!$data){
					$result["status"]	= FALSE;
					$result["message"] 	= "Data gagal di update";
					$result["debug"]	= $this->jsons;
					$result["param"]	= $this->db->last_query();
					$result["recordexists"]=FALSE;

				}else{
					$result["status"]	= TRUE;
					$result["message"] 	= 1;
					$result["location"] =(isset($this->location))?$this->get_location():"";
					$result["param"]	= $this->db->last_query();
					$result["recordexists"]=FALSE;
				}
			break;
			case 'post':
			case 'put':
			case 'delete':
				
				if($status==FALSE){
						$result["status"]	= FALSE;
						$result["message"] 	= "Tidah di ijinkan";
						$result["recordexists"]=FALSE;
						$result["lastid"]	= FALSE;
				}else{
					if($method==='post' && $this->get_totalrecord()>0 ){
						$result["status"]	= FALSE;
						$result["message"] 	= "Data sudah pernah ada sebelumnya";
						$result["recordexists"]=TRUE;
						$result["lastid"]	= $this->get_totalrecord();
						
					}else{
						$data =$this->insert_data();
						if($data->ID==0){
							$result["status"]	= FALSE;
							$result["message"] 	= "Data gagal disimpan";
							$result["debug"]	= $this->parameter;
							$result["param"]	= $this->db->last_query();
							$result["recordexists"]=FALSE;

						}else{
							$result["status"]	= TRUE;
							$result["message"] 	= $data->ID;
							$result["location"] =(isset($this->location))?$this->get_location():"";
							$result["param"]	= $this->db->last_query();
							$result["recordexists"]=FALSE;
						}
					}
				}
				break;
			case 'post_batch':
				$datax =$this->insert_batch();
				if(!$datax){
					$result["status"]	= FALSE;
					$result["message"] 	= "Data gagal di simpan";
					$result["debug"]	= $this->parameter;
					$result["param"]	= $this->db->last_query();
					$result["recordexists"]=FALSE;

				}else{
					$result["status"]	= TRUE;
					$result["message"] 	= $datax;
					$result["location"] =(isset($this->location))?$this->get_location():"";
					$result["param"]	= $this->db->last_query();
					$result["recordexists"]=FALSE;
				}
			break;
			default:
				if($status==FALSE){
					$result["status"]	= FALSE;
					$result["message"] 	= "Tidak di ijinkan";
					$result["recordexists"]=FALSE;
				}
				break;
		}
		
		return $result;
	}
	/**
	 * [data_sudahada description]
	 * @param  [array]  $param     [where parameter ]
	 * @param  [string]  $namatabel [nama table]
	 * @param  boolean $reinsert  [TRUE jika row_status -1 di abaikan]
	 * @return [int]             [record count]
	 */
	function data_sudahada($param,$namatabel,$reinsert=FALSE,$reused=null){
		if($reinsert==TRUE){
			$param["ROW_STATUS >="]='0'; 
		}
		$this->set_parameter($param);
		$this->tabel_name($namatabel);
		$lastId=0;$lastRecord=0;
		$lastId = isset($this->record_exists()->ID)?$this->record_exists()->ID:0;
		$lastRecord=($lastId>0)?$lastId:$this->record_exists();
        $this->set_totalrecord($lastRecord);
	}
	/**
	 * [webservice description]
	 * @param  [type] $servicename [description]
	 * @param  [type] $parameter   [description]
	 * @return [type]              [description]
	 */
	function webservice($servicename=NULL,$parameter=NULL){
		$result="";
		if($parameter==NULL || $parameter==''){
			$result= "http://36.66.232.220:8686/t10maiNDealer01/".$servicename;
		}else{
			$result= "http://36.66.232.220:8686/t10maiNDealer01/".$parameter."/".$servicename;
		}
		
		return $result;
	}
	/**
	 * [array_key_prefix_suffix description]
	 * @param  [type] &$array [description]
	 * @param  string $prefix [description]
	 * @param  string $suffix [description]
	 * @return [type]         [description]
	 */
	function array_key_prefix_suffix(&$array,$prefix='',$suffix=''){
        $key_array = array_keys($array);
        $key_string = $prefix.implode($suffix.','.$prefix,$key_array).$suffix;
        $key_array = explode(',', $key_string);
        $array = array_combine($key_array, $array);
        return $array;
    }
    /**
     * [array_map_assoc description]
     * @param  [type] $callback [description]
     * @param  [type] $arr      [description]
     * @return [type]           [description]
     */
    function array_map_assoc($callback, $arr) {
	   $remapped = array();

	   foreach($arr as $k => $v)
	      $remapped += $callback($k, $v);

	   return $remapped;
	}
}