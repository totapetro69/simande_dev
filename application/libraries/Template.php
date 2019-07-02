<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
		 var $API=""; var $_ci="";
			var	$html = '';

		function __construct() { 
			$this->_ci =& get_instance();
			$this->_ci->load->library('curl');
			$this->_ci->load->library('session');
			$this->_ci->load->helper('form');
			//API_URL=str_replace('frontend/','backend/',base_url())."index.php";
		}

		function site($content, $data=NULL) {
			if($this->_ci->session->userdata("nama_group")==="Root"){
				$param=array(
					'custom'	=> "MM.NAMA_MODUL IS NOT NULL",
					// 'kd_group'	=>	1,
					//'kd_group'	=>	$this->_ci->session->userdata('kd_group'),
					'orderby' 	=> 'MM.URUTAN_MODUL asc',
					'jointable' => array(
						array("MASTER_MODUL AS MM","MM.KD_MODUL=USERS_GROUP_PRIVILAGES.KD_MODUL AND MM.ROW_STATUS>=0","LEFT")
					),
					'field' 	=>"MM.ID,MM.KD_MODUL,MM.NAMA_MODUL,MM.ICON_MODUL, MM.LINK_MODUL,MM.PARENT_MODUL,MM.URUTAN_MODUL",
					'groupby'	=>TRUE
				);
			}else{
				$param=array(
					'custom'	=> "MM.NAMA_MODUL IS NOT NULL AND (V=1 OR C=1 OR E=1 OR P=1 OR MM.PARENT_MODUL='' OR MM.PARENT_MODUL IS NULL) ",
					// 'kd_group'	=>	1,
					'kd_group'	=>	$this->_ci->session->userdata('kd_group'),
					'orderby' 	=> 'MM.URUTAN_MODUL asc',
					'jointable' => array(
						array("MASTER_MODUL AS MM","MM.KD_MODUL=USERS_GROUP_PRIVILAGES.KD_MODUL AND MM.ROW_STATUS>=0","LEFT")
					),
					'field' 	=>"MM.ID,MM.KD_MODUL,MM.NAMA_MODUL,MM.ICON_MODUL, MM.LINK_MODUL,KD_GROUP,MM.PARENT_MODUL,C,E,V,P"
				);
			}
			

			// print_r($this->_ci->session->userdata());exit();
			
			$tree= ($this->_ci->session->userdata('kd_group') != null) ? json_decode($this->_ci->curl->simple_get(API_URL."/api/login/auths",$param)) : '';
			$message = ((is_array($tree) || is_object($tree)) && $tree->status == true) ? $this->parseTree($tree->message) : array();
			
			$result['navbar'] = $this->printTree($message);
			$layout = array(
				'metadata' => $this->_ci->load->view('template/site/metadata',$data,true),
				'loading_page' => $this->_ci->load->view('template/common/loading_page',$data,true),
				'header' => $this->_ci->load->view('template/site/header',$data,true),
				'sidebar' => $this->_ci->load->view('template/site/sidebar',$result,true),
				'content' => $this->_ci->load->view($content,$data,true),
				'footer' => $this->_ci->load->view('template/site/footer',$data,true),
				'alert' => $this->_ci->load->view('template/common/alert',$data,true),
				'modal' => $this->_ci->load->view('template/common/modal',$data,true),
				'script' => $this->_ci->load->view('template/site/script',$data,true)
			);
			
			$this->_ci->parser->parse('template/site',$layout);
		}

		public function parseTree($tree, $root = null) {
		    $return = array();
		    # Traverse the tree and search for direct children of the root
		    foreach($tree as $child => $parent) {
		        # A direct child is found
		        if($parent->PARENT_MODUL == $root) {
		            # Remove item from tree (we don't need to traverse this again)
		            unset($tree[$child]);
		            # Append the child into result array and parse its children
		            $return[] = array(
		                'NAMA_MODUL' 	=> $parent->NAMA_MODUL,
		                'LINK_MODUL' 	=> $parent->LINK_MODUL,
		                'ICON_MODUL' 	=> $parent->ICON_MODUL,
		                'KD_MODUL' 		=> $parent->KD_MODUL,
		                'PARENT_MODUL' 	=> $parent->PARENT_MODUL,
		                'CHILDREN' 		=> $this->parseTree($tree, $parent->KD_MODUL)
		            );
		        }
		    }
		    return empty($return) ? null : $return;    
		}

		public function printTree($tree, $parent = false) {
			// return $tree;
			$html="";
		    if(!is_null($tree) && count($tree) > 0):

		        $html .= $parent == false?' ':'<ul class="sub">';

		        foreach($tree as $node):
		        	$html .= '<li class="sub-menu">';
		        	// $html .= $parent == false?'<li class="sub-menu">':'<li>';
                    $html .= '<a href="'.base_url($node['LINK_MODUL']).'">';
                    $html .= ($node['ICON_MODUL'] != null? "<i class='fa ".$node['ICON_MODUL']."'></i>" : "");
                    $html .= '<span>'.$node['NAMA_MODUL'].'</span>';
                    $html .= '</a>';
		            $html .= $this->printTree($node['CHILDREN'],true);
		            $html .= '</li>';
		        endforeach;
		        $html .= $parent == false?' ':'</ul>';
		    
		    endif;
			return $html;
		}

		public function pagination($config){
			$config['per_page'] = $config['per_page'];
			$config['base_url'] = $config['base_url'];
			$config['total_rows'] = $config['total_rows'];
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'page';
		   	$config['full_tag_open'] = "<ul class='pagination pagination-sm m-t-none m-b-none'>";
		   	$config['full_tag_close'] ="</ul>";
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
}
