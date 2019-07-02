<?php
    class Login_model extends CI_Model
    {
        function __construct(){
            parent::__construct();
        }

        /**
        * Tampilkan daftar user 
        */
        function listusers($where='',$orderby=''){
            $data=array();
            $query="SELECT * FROM USERS ".$where.$orderby;
            $data=$this->db->query($query);
            return $data->result();
           
        }
        
        /**
        * insert data user baru
        */
        function replace_data($table,$data=array()){

            /*return $data;
            exit;*/

            $fld='';$value=''; $query="";
            foreach(array_keys($data) as $key){
                $fld .='?,';
            }
            foreach(array_values($data) as $val){
                $value .="'".$val."',";
            }
            $this->db->trans_start();
            $fld=substr($fld,0,(strlen($fld)-1));
            $value=substr($value,0,(strlen($value)-1));
            $procedure='exec '.$table." ".$value."";
 		    $query = $this->db->query($procedure)->row();
            $this->db->trans_complete();
            return $query;
        }
    }
?>