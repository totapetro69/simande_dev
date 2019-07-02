<?php
/* class control authorisation
	Author : Iswan Putera
	
*/


class Zetro_auth extends CI_model
{	
	function __construct(){
		//parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('control_model');
		$this->userid=$this->session->userdata('idlevel');
		$this->load->library('REST_Controller');
		$this->load->library('Curl');
	}

	function menu_id($menuid){
		
		$this->menu=$menuid;
	}
	
	function field($field){
		$this->field=$field;	
	}
	function view($link){
		$this->link=$link;
	}
	function cek_login(){
		if($this->session->userdata('idlevel')=='') $this->logout();		
	}
	public function frm_filename($filename){
		$this->filename=$filename;	
	}
	public function oto($menuid='',$field=''){
		$this->menu_id($menuid);
		$this->field($field);
		($this->menu!='All')?
		$this->Admin_model->is_oto($this->menu,$this->field):
		$this->Admin_model->is_oto_all($this->menu,$this->link);
	}
   public function tab_select($prs=''){
		return $this->prs=$prs;	
	}
    function logout() {
        $data = array
            (
            'userid' => 0,
            'username' => 0,
            'type' => 0,
			'idlevel'=>0,
            'login' => FALSE
        );
        $this->session->sess_destroy();
        $this->session->unset_userdata($data);
        redirect('admin/process_login');
    }
//function authenication	
	function cek_oto($field,$menu,$userid=''){
		($userid=='')?
		$this->control_model->userid=$this->userid:
		$this->control_model->userid=$userid;
		$datax=$this->control_model->cek_oto($field,str_replace('/','__',$menu));
			return($this->userid!='')? $datax:$this->zetro_auth->cek_login();
	}
	function lock($field,$menu){
		if($this->userid!='1'){
		return($this->cek_oto($field,$menu,$this->userid)=='')? "disabled='disabled'":'';	
		}
	}
	function auth($key='',$value=''){
		$data=array();$n=0;
		foreach($this->menu as $mnu){
			$data['c_'.$mnu]=$this->cek_oto('c',$mnu);
			$data['e_'.$mnu]=$this->cek_oto('e',$mnu);
			$data['v_'.$mnu]=$this->cek_oto('v',$mnu);
			$data['p_'.$mnu]=$this->cek_oto('p',$mnu);
			$data['all_'.$mnu]=$this->cek_oto('c',$mnu).$this->cek_oto('e',$mnu).$this->cek_oto('v',$mnu).$this->cek_oto('p',$mnu);
		}
		if(is_array($key)){
			foreach($key as $k){
				$data[$k]=$value[$n];
				$n++;	
			}
		}
		return $data;
	
	}
	function cek_area(){
		$data=array();$area='';$n=0;
		$data=($this->session->userdata('idlevel')!=1)?
		$this->Admin_model->show_list('user_oto_area',"where userid='".$this->session->userdata('userid')."' and c='Y'"):
		$this->Admin_model->show_list('user_lokasi','order by ID', "ID as lokasi");
		
		foreach($data as $r){
			$n++;
			$koma=($n==count($data))?"":",";
			$area .="'".$r->lokasi."'".$koma."";	
		}
		return $area;
		echo $area;
	}
// get data read variable form zetro_*.frm
   public function get_data_field($section,$table){
		$data=array();
		$jml=$this->zetro_manager->Count($section,$this->filename);
		for ($i=1;$i<$jml;$i++){
			$fld=explode(",",$this->zetro_manager->rContent($section,$i,$this->filename));
			$fld2=explode(" ",$fld[2]);
			($fld2[0]=='date')?
			$result=tglToSql($this->input->post($fld[3])):
			$result=$this->input->post($fld[3]);
			$data[$fld[3]]=$result;
		}
		$data["created_by"]=$this->session->userdata("userid");
		$this->Admin_model->simpan_data($table,$data);
		//print_r($data);
	}
	public function show_data_field($section,$table,$where){
		$data=array();
		$jml=$this->zetro_manager->Count($section,$this->filename);
		for ($i=1;$i<=$jml;$i++){
			$fld=explode(",",$this->zetro_manager->rContent($section,$i,$this->filename));
			$fld2=explode(" ",$fld[2]);
			($fld2[0]=='date')?
			$result=tglfromSql($this->Admin_model->show_single_field($table,$fld[3],$where)):
			$result=$this->Admin_model->show_single_field($table,$fld[3],$where);
		   $data[$fld[3]]=$result;
		}
		 return $data;
	}
	public function update_data_field($section,$table){
		$data=array();
		$jml=$this->zetro_manager->Count($section,$this->filename);
		for ($i=1;$i<$jml;$i++){
			$fld=explode(",",$this->zetro_manager->rContent($section,$i,$this->filename));
			$fld2=explode(" ",$fld[2]);
			($fld2[0]=='date')?
			$result=tglToSql($this->input->post($fld[3])):
			$result=$this->input->post($fld[3]);
			$data[$fld[3]]=$result;
		}
		$data["created_by"]=$this->session->userdata("userid");
		$this->Admin_model->replace_data($table,$data);
	}

	/**
	* Encrypt data password
	*/
	public function encrypts($text) {
        //Secret Key
        //$text = "doan1234";
        $key1 = "Je ne vous oublie pas";
        $key2 = "!@#$%!@#$%";
        $key = substr($key1 . $key2, 0, 24);

        //Initialization Vector
        $iv = "";
        $array_iv = array(12, 241, 10, 21, 90, 74, 11, 39);
        foreach ($array_iv as $value_iv) {
            $iv .= chr($value_iv);
        }


        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        $block = mcrypt_get_block_size('tripledes', 'cbc');
        $len = strlen($text);
        $padding = $block - ($len % $block);
        $text .= str_repeat(chr($padding), $padding);

        mcrypt_generic_init($td, $key, $iv);
        $encrypt_text = mcrypt_generic($td, $text);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        //echo base64_encode($encrypt_text);
        return base64_encode($encrypt_text);
    }
	public function set_password($password){
        $this->password = $this->encryptNET3DES($password);
    }
    public function get_password(){
    	return $this->password;
    }
	public function validatePassword($password){
        if ($this->encrypts($password) == $this->password) {
            return true;
        } else {
            return false;
        }
       
    }


	function encryptNET3DES($text) {
            //Secret Key
            $key1="Je ne vous oublie pas";
            $key2="!@#$%!@#$%";
            $key = substr($key1.$key2,0,24);

            //Initialization Vector
            $iv="";
            $array_iv = array(12,241,10,21,90,74,11,39);
            foreach ($array_iv as $value_iv) {
                    $iv .= chr($value_iv);
            }
	
            $td = mcrypt_module_open (MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
            $block = mcrypt_get_block_size('tripledes', 'cbc');
            $len = strlen($text);           
            $padding= $block-($len%$block);                  
            $text .= str_repeat(chr($padding),$padding);

            mcrypt_generic_init ($td, $key, $iv);
            $encrypt_text = mcrypt_generic ($td, $text);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return base64_encode($encrypt_text);    
	}     

	public static function actionDecrypt($text) {

        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        $iv = "";
        $array_iv = array(12, 241, 10, 21, 90, 74, 11, 39);
        foreach ($array_iv as $value_iv) {
            $iv .= chr($value_iv);
        }
        // Complete the key
        $key1 = "Je ne vous oublie pas";
        $key2 = "!@#$%!@#$%";
        $key = substr($key1 . $key2, 0, 24);

        $key_add = 24 - strlen($key);

        echo "1 : " . $key_add . "</br>";

        mcrypt_generic_init($td, $key, $iv);

        echo "2 : " . $key. "</br>";

        $decrypt_text = mdecrypt_generic($td, $text);

        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        //remove the padding text

        $block = mcrypt_get_block_size('tripledes', 'cbc');

        $packing = ord($decrypt_text{strlen($decrypt_text) - 1});

        echo "3 :" . $packing . "</br>";

        if ($packing and ($packing < $block)) {

            for ($P = strlen($decrypt_text) - 1; $P >= strlen($decrypt_text) - $packing; $P--) {

                if (ord($decrypt_text{$P}) != $packing) {

                    $packing = 0;
                }
            }
        }

        echo "4:" . $decrypt_text . "</br>";

        $decrypt_text = substr($decrypt_text, 0, strlen($decrypt_text) - $packing);

        echo "5:" . $decrypt_text . "</br>";
        //return base64_encode($decrypt_text);
        echo base64_encode($decrypt_text);
        echo $decrypt_text;
    }

    public function encrypt($data)
	{
	    //Generate a key from a hash
	    $secret="@!!@)%";
	    $key = md5(utf8_encode($secret), true);

	    //Take first 8 bytes of $key and append them to the end of $key.
	    $key .= substr($key, 0, 8);

	    //Pad for PKCS7
	    $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
	    $len = strlen($data);
	    $pad = $blockSize - ($len % $blockSize);
	    $data .= str_repeat(chr($pad), $pad);

	    //Encrypt data
	    $encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');

	    return base64_encode($encData);
	}

	public function decrypt($data)
	{
	    //Generate a key from a hash
	    $secret="@!!@)%";
	    $key = md5(utf8_encode($secret), true);

	    //Take first 8 bytes of $key and append them to the end of $key.
	    $key .= substr($key, 0, 8);

	    $data = base64_decode($data);

	    $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');

	    $block = mcrypt_get_block_size('tripledes', 'ecb');
	    $len = strlen($data);
	    $pad = ord($data[$len-1]);

	    return substr($data, 0, strlen($data) - $pad);
	}
}
?>