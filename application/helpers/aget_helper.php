<?php defined('BASEPATH') OR exit('No direct script access allowed');	

function titledata(){

	$ci =& get_instance();
	$ci->load->library('curl');
	$ci->load->library('session');
	$ci->load->helper('form');

	$modul= json_decode($ci->curl->simple_get(API_URL."/api/setup/modul", array('custom' => "LINK_MODUL = '".$ci->uri->uri_string()."'")));
	
	$title = 'SIMANDE';

	if(isset($modul) && is_array($modul->message)){
		$title = $modul->message[0]->NAMA_MODUL;
	}

	// var_dump($title);exit;

	return $title;


}

function breadcrumb()
{
	$ci =& get_instance();
	$ci->load->library('curl');
	$ci->load->library('session');
	$ci->load->helper('form');
	//$API=str_replace('frontend/','backend/',base_url())."index.php";


	$tree= json_decode($ci->curl->simple_get(API_URL."/api/setup/modul"));
	$uri_str=explode("?",$ci->uri->uri_string());
	$urlpath=explode("/", $uri_str[0]);//$ci->uri->uri_string());
	
	$tree_child= json_decode($ci->curl->simple_get(API_URL."/api/setup/modul", array('custom' => "LINK_MODUL = '".$ci->uri->uri_string()."'")));

	$parent = ((is_array($tree_child) || is_object($tree_child)) && $tree_child->status == true) ? breadcrumbTree($tree->message, $tree_child->message[0]->KD_MODUL) : array();

	$result = '<div id="bc1" class="myBreadcrumb">';
	$result .= '<a href="'.base_url().'"><i class="fa fa-home fa-2x"></i></a>';

	$result .= printBreadcrumb($parent);
	$result .= '</div>';

	// var_dump($result);

	return $result;
}

function breadcrumbTree($tree, $root) {
	$ci =& get_instance();
	$ci->load->library('curl');
	$ci->load->library('session');
	$ci->load->helper('form');
	//$API=str_replace('frontend/','backend/',base_url())."index.php";

    $return = array();
    # Traverse the tree and search for direct children of the root
    foreach($tree as $child => $parent) {
        # A direct child is found
        if($parent->KD_MODUL == $root) {
            # Remove item from tree (we don't need to traverse ci again)
            unset($tree[$child]);
            # Append the child into result array and parse its children
            $return[] = array(
                'NAMA_MODUL' 	=> $parent->NAMA_MODUL,
                'LINK_MODUL' 	=> $parent->LINK_MODUL,
                'ICON_MODUL' 	=> $parent->ICON_MODUL,
                'KD_MODUL' 		=> $parent->KD_MODUL,
                'PARENT_MODUL' 	=> $parent->PARENT_MODUL,
                'CHILDREN' 		=> breadcrumbTree($tree, $parent->PARENT_MODUL)
            );
        }
    }
    return empty($return) ? null : $return;    
}

function printBreadcrumb($tree, $parent = false) {
	$ci =& get_instance();
	$ci->load->library('curl');
	$ci->load->library('session');
	$ci->load->helper('form');
	//$API=str_replace('frontend/','backend/',base_url())."index.php";
	// return $tree;
	$html="";
    if(!is_null($tree) && count($tree) > 0):
			

        foreach($tree as $node):
            $html .= printBreadcrumb($node['CHILDREN'],true);
            if($parent == false):
				$html .= '<a href="javascript:void(0);" class="active hidden-xs hidden-sm"><div>'.$node['NAMA_MODUL'].'</div></a>';
				else:
					if($node['LINK_MODUL'] == 'null'):
					$html .= '<a class="hidden-xs hidden-sm" href="javascript:void(0);"><div>'.$node['NAMA_MODUL'].'</div></a>';
					else:
					$html .= '<a class=" hidden-xs hidden-sm" href="'.base_url($node['LINK_MODUL']).'"><div>'.$node['NAMA_MODUL'].'</div></a>';
					endif;
				endif;
        endforeach;

    
    endif;
	return $html;
}


function set_barcode($code)
{
	$ci =& get_instance();
    //load library
    $ci->load->library('picqer');

    return $ci->picqer->generate($code);

}

function queryIndata($data, $queryby)
{
    $query = array();

	foreach ($data as $key => $value) {
		array_push($query,$value->$queryby);
	}

	return "('".implode("','",$query)."')";
}

/*function url_link(){
	$ci =& get_instance();
	$ci->load->helper('url');
	$string = count(explode('&page=',$_SERVER["REQUEST_URI"])) > 1? explode('&page=',$_SERVER["REQUEST_URI"]):explode('?page=',$_SERVER["REQUEST_URI"]);
	return $string;
}*/
?>