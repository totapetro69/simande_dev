<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_model extends CI_Model 
{
    var $tabel_name = 'users';
    function  __construct() 
	{
        parent::__construct();
    }
	
    function cek_user_login($username, $password) 
	{
        $this->db->select('*');
        $this->db->where('namapengguna', $username);
        $this->db->where('sandi', md5($password));
        $this->db->where('status >','0');
        $this->db->where('d','t');
        $query = $this->db->get('mcusers');
        return $query->result();
		
    }
	
	function userlist($limit,$offset){
		$this->db->where('idlevel !=','1');
		$this->db->select('*');
		$this->db->order_by('userid');
		//$this->db->limit($limit,$offset);
		$query=$this->db->get('mcusers');
		return $query;
	}
	function ulang($kondisi){
		$this->kondisi=$kondisi;	
	}
	function join($tableparent,$tablejoin,$fieldparent,$fieldjoin){
		$this->db->join($tablejoin, $tablejoin.$fieldjoin=$tableparent.$fieldparent);
	}
	function filterby($where,$field){
		$this->where=$where;
		$this->field=$field;
	}
	
	function total_data($tabel,$where='',$field=''){
		if($where!=''){
			$query=$this->db->where($field,$where);
		}else{
				$query=$this->db->where($this->field,$this->where);
		}
		$query=$this->db->count_all_results($tabel);
		return $query;	
	}
	function show_single_field($table,$field='*',$where){
		$nom='';
		$sql="select $field from $table $where";
		//echo $sql;
		$rs=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($rs)){
			$nom=$row[$field];	
		}
		return $nom;
	}
	
	
	function user_exists($uid=''){
		($uid=='')?$uid=$this->session->userdata('userid'):$uid=$uid;
		$this->db->where("userid",$uid);
		$q=$this->db->count_all_results("users");
		;
		return $q;
	}
	function field_exists($table,$where='',$field='*'){
		$q=$this->db->query("select $field from $table $where");
		if ($q->num_rows() > 0) {
			$row=$q->row();
			$hasil=$row->$field;
		}else{ $hasil='';}
		return $hasil;
	}
	function cek_pwd($uid=''){
		($uid=='')?$uid=$this->session->userdata('userid'):$uid=$uid;
		$q=$this->db->query("select password from users where userid='$uid'");
		$row=$q->row();
		$hasil=$row->password;
		return $hasil;
	}
	function cek_oto($menu,$fields,$uid=''){
		check_logged_in($this->session->userdata('userid'));
		($uid=='')?$uid=$this->session->userdata('userid'):$uid=$uid;
		$q=$this->db->query("select $fields from useroto where idmenu='$menu' and userid='$uid'");
		if ($q->num_rows() > 0) {
			$row=$q->row();
			$hasil=$row->$fields;
		}else{ $hasil='';}
		return $hasil;
	}
	function is_oto($menu,$field,$userid=''){
		check_logged_in($this->session->userdata('userid'));
		$oto='';
		($userid=='')?$uid=$this->session->userdata('userid'):$uid=$userid;
		$sql="select $field from useroto where idmenu='$menu' and userid='$uid'";	
		//echo $sql;
		$rs=mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($rs)){
			$oto=$row[$field];	
		}
		return $oto;
	}
	function is_oto_all($menu,$link_oto,$userid=''){
		check_logged_in($this->session->userdata('userid'));
		($userid=='')?$uid=$this->session->userdata('userid'):$uid=$userid;
		if($uid=='Superuser'){
			$link_oto;
		}else{
			if($this->is_oto($menu,'c',$uid)=='Y' ||
			   $this->is_oto($menu,'e',$uid)=='Y' ||
			   $this->is_oto($menu,'v',$uid)=='Y' ||
			   $this->is_oto($menu,'p',$uid)=='Y' ||
			   $this->is_oto($menu,'d',$uid)=='Y' ){
				   $link_oto;
			   }else{
				  //$this->load->view("admin/no_authorisation");
				  no_auth();
			   }
		}
	}
	function update_nomor($data,$table='nomor_transaksi'){
		$this->simpan_data($table,$data);
	}
	function simpan_data($tabel,$tabeldata){
		$simpan=$this->db->insert($tabel,$tabeldata);
		return $simpan;
	}
	function simpan_update($tabel,$data,$field){
		$this->db->where($field,$data[$field]);
		$q=$this->db->update($tabel,$data);
		return $q->result();
	}
	function isi_list($tabel,$where='',$field='*'){
		$q=$this->db->query("select $field from $tabel $where");
		return $q->result();
	}
	function show_listed($tabel,$where='',$field='*'){
        //echo "select $field from $tabel $where";
		$q=$this->db->query("select $field from $tabel $where");
		return $q->result();//or die(mysql_error());	
	}
	function hapus_table($tabel,$field,$isi){
		$this->db->where($field,$isi);
		return $this->db->delete($tabel);	
	}
	function update_table($table,$where,$field,$data){
		$this->db->where($where, $field);
		$q= $this->db->update($table, $data);
		return $q;
		//>result() or die(mysql_error());
	}
	function tgl_to_mysql($tgl='',$delimiter='/'){
		if($tgl==''){$tgle=date('Ymd');}else{
			$tgl=str_replace($delimiter,"",$tgl);
			$tgle=substr($tgl,4,4).substr($tgl,2,2).substr($tgl,0,2);
		}
		return $tgle;
	 }
	
	function upd_data($table,$field,$where){
		$q="update $table $field $where";
		//echo $q;
		return $this->db->query($q) or die(mysql_error());
		//return	mysql_query($q) or die(mysql_error());
	}
	function hps_data($table,$where=''){
		$q="delete from $table $where";
		return	$this->db->query($q) or die(mysql_error());
	}
	
	//sql replace into method
	function replace_data($table,$data=array()){
		$fld='';$value='';
		foreach(array_keys($data) as $key){
			$fld .=$key.',';
		}
		foreach(array_values($data) as $val){
			$value .="'".$val."',";
		}
		$fld=substr($fld,0,(strlen($fld)-1));
		$value=substr($value,0,(strlen($value)-1));
		$sql="replace into $table (".$fld.") values(".$value.")";
		//echo $sql;
		return $this->db->query($sql) or die($sql.mysql_error());	
	}
	function record_exists($table,$data=array()){

		$this->db->where($data);
		return $this->db->get($table)->num_rows();
	}
	function selectfield($selected='*'){
		if($selected==''){
			$this->selected = str_replace("-", ",", $selected);
		}else{
			$this->selected="*";
		}
		
	}
	function parameter($parameter=''){
		$this->parameter = $parameter;
	}
	function show_list_array($table,$data=array(),$withJoin=''){
		$this->db->start_cache();
		$this->db->select($this->selected);
		if(count($data)>0){$this->db->where($data);}
		$this->db->from($table);
		if($withJoin!=''){ 
			for($i=0;$i< count($withJoin);$i++){
			$join=$withJoin[$i];
			$this->db->join($join[0],$join[1],$join[2]);	
			}	
		}
		$data = $this->db->get()->result();	
		return $data ;
	}
	function show_list()
	{


	}
	function show_menu_auth($idkelompok){

	}
	
}
?>