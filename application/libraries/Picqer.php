<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."./third_party/picqer/BarcodeGenerator.php";
require_once APPPATH."./third_party/picqer/BarcodeGeneratorPNG.php";
require_once APPPATH."./third_party/picqer/BarcodeGeneratorSVG.php";
require_once APPPATH."./third_party/picqer/BarcodeGeneratorJPG.php";
require_once APPPATH."./third_party/picqer/BarcodeGeneratorHTML.php";

class Picqer {

	public function generate($id)
	{
/*
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
		file_put_contents('tests/verified-files/081231723897-ean13.svg', $generatorSVG->getBarcode('081231723897', $generatorSVG::TYPE_EAN_13));
*/
	    $generatorJPG = new Picqer\Barcode\BarcodeGeneratorJPG();
		$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
		$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();

	    $data = $generatorJPG->getBarcode($id, $generatorJPG::TYPE_CODE_128);
	    
	    return $data;
	}
}