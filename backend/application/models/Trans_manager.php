<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_manager extends Main_model
{
	function __construct(){
		parent::__construct();
	}
	public function db_insert(){
		if(is_array($this->get_tablename())){
			$this->db->trans_begin();
			for($i=0;$i< count($this->get_tablename());$i++){
				$data=$this->get_parameter()[$i];
				$fld='';$value=''; $query="";
	            foreach ($data as $key => $value) {
	            	$fld .="@".$key."='".$value."',";
	            }
	            $this->db->trans_start();//start transaksi
	            $fld=substr($fld,0,(strlen($fld)-1)); //remove last delimiter
	            $procedure='exec '.$this->get_tablename()[$i]." ".$fld.""; 
	 		    $query = $this->db->query($procedure)->row();
			}
			if($this->db->trans_status()==FALSE){
				$this->db->trans_rollback();
				return -1;
			}else{
				$this->db->trans_commit();
				return $query;
			}
		}else{
			return 0;
		}
	}
}