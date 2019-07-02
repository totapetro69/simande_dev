<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."./third_party/dompdf/autoload.inc.php";
use Dompdf\Dompdf;

class Dompdf_gen {

  public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait")
  {
    $dompdf = new DOMPDF();
    // $dompdf->set_option('enable_css_float', true);
    $dompdf->loadHtml($html);
    // $dompdf->loadHtmlFile();
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
    } else {
        return $dompdf->output();
    }
  }
}