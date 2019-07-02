<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Surat Penyerahan</title>

    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" > -->

    <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->
    
    <style type="text/css">

    @page { 
      margin: 0px; 
      margin-top:3px;
      margin-left:3px;
      margin-bottom: -15px;
    }
    body { 
      margin: 0px;
      margin-top:3px;
      margin-left:3px;
      /*margin-bottom: -15px;*/
    }

    table{
      width: 100%;
    }

    table td{
      line-height: 0.6;
      /*width: 100%;*/
    }

    .nowarp{
      white-space: nowrap;
    }

    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        /*margin-bottom: 20px;*/
        width: 100%;
    }
    .project {
      padding: 0 3px;
      text-align: left;
      display: table;
      width: 100%;
    }
    .project div {
        display: table-row;
    }

    .project .title {
        color: #5D6975;
        width: 30%;
        font-size: 10px;
    }

    .project span {
        text-align: left;
        display: table-cell;
    }

    .project .content {
        width: 70%;
        font-size: 10px;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }


    .label{
      /*width: 6.4cm;*/ /* plus .6 inches from padding */
      width: 6.0cm; /* plus .6 inches from padding */
      /* plus .125 inches from padding */
      /*height: 3.3cm; */
      height: 3.2cm; 
      /*padding: 0.04cm 0.13cm;*/

      /* the gutter */
/*      padding-right: 5px; 
      padding-left: 5px;*/ 
      padding-right:0px; 
      padding-left: 0px; 

      /* the gutter */
      /*margin-bottom: 3px; */
      /*padding-top: 7px; */
      margin-right: -20px; 
      margin-left: 36px;
      padding-top:5px;

      float: left;

      text-align: left;
      overflow: hidden;
	  font-fam

      /*outline: 1px dotted; */
      /* outline doesn't occupy space like border does */
    }


    .page-break  {
      clear: left;
      display:block;
    }

    </style>

  </head>
  <body>

  
<?php
$NO_TRANS = '';
$TGLMULAI_PENGURUSAN = '';
$NAMA_KABUPATEN = '';
$NAMA_DEALER = '';
$ALAMAT = '';
$KABUPATEN_DEALER='';
$KETERANGAN = '';
$FAKTUR_PENJUALAN = '';

if($list){

  if(is_array($list->message)){

    foreach ($list->message as $key => $value) {

    }

  }

}

?>
    <header>
    </header>

    <main>
        <?php 
        $no=1;
        $total = 0;
        foreach($list->message as $key => $detail): 
          for($i=0; $i<2; $i++):
        ?>

          <?php for($j=0; $j<3; $j++): ?>
            <div class="label">

              <table>
                <tr>
                  <td>
                    <img style="max-width:90px; height: 20px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->TGL_SURATJALAN));?>">
                  </td>
                  <td>
                    <img style="max-width:110px; height: 20px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->NO_MESIN));?>">
                  </td>
                </tr>
                <tr>
                  <td>
                    <img style="max-width:110px; height: 20px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->VNORANGKA1.$detail->NO_RANGKA));?>">
                  </td>
                  <td></td>
                </tr>
              </table>

              <table style="font-size: 7px;">
                <tr>
                  <td class="nowarp">
                    Nama
                  </td>
                  <td colspan="3">
                    :<?php echo $detail->NAMA_PENERIMA;?>
                  </td>
                </tr>
                <tr>
                  <td class="nowarp">
                    Alamat
                  </td>
                  <td colspan="3">
                    :<?php echo $detail->ALAMAT_SURAT.', '.$detail->NAMA_KABUPATEN;?>
                  </td>
                </tr>
                <tr>
                  <td class="nowarp">
                    No. HP/Telpon
                  </td>
                  <td colspan="3">
                    :<?php echo $detail->NO_HP;?>
                  </td>
                </tr>
                <tr>
                  <td class="nowarp">
                    Tipe/No Polisi
                  </td>
                  <td colspan="3">
                    :<?php echo $detail->NAMA_TYPEMOTOR.($detail->DATA_NOMOR?' / '.$detail->DATA_NOMOR:'');?>
                  </td>
                </tr>
                <tr>
                  <td class="nowarp">
                    No. Rangka
                  </td>
                  <td colspan="3">
                    :<?php echo $detail->VNORANGKA1.$detail->NO_RANGKA;?>
                  </td>
                </tr>
                <tr>
                  <td class="nowarp">
                    No. Mesin
                  </td>
                  <td>
                    :<?php echo $detail->NO_MESIN;?>
                  </td>
                  <td class="nowarp">
                    Tgl Pembelian
                  </td>
                  <td class="nowarp">
                    :<?php echo tglfromSql($detail->TGL_SO);?>
                  </td>
                </tr>
              </table>


<!--               <div class="project">

                <div style="">
                  <span class="title">
                    Nama
                  </span> 
                  <span class="content">
                    <?php echo $detail->NAMA_PENERIMA;?>
                  </span>
                </div>

                <div style="">
                  <span class="title">
                    Alamat
                  </span> 
                  <span class="content">
                    <?php echo $detail->ALAMAT_SURAT.', '.$detail->NAMA_KABUPATEN;?>
                  </span>
                </div>
                
                <div style="">
                  <span class="title">
                    No. HP/Telpon
                  </span> 
                  <span class="content">
                    <?php echo $detail->NO_HP;?>
                  </span>
                </div>

                <div style="">
                  <span class="title">
                    Tipe/No Polisi
                  </span> 
                  <span class="content">
                    <?php echo $detail->NAMA_TYPEMOTOR.($detail->DATA_NOMOR?' / '.$detail->DATA_NOMOR:'');?>
                  </span>
                </div>

                <div style="">
                  <span class="title">
                    No. Rangka
                  </span> 
                  <span class="content">
                    <?php echo $detail->VNORANGKA1.$detail->NO_RANGKA;?>
                  </span>
                </div>

                <div style="">
                  <span class="title">
                    No. Mesin
                  </span> 
                  <span class="content">
                    <?php echo $detail->NO_MESIN;?>
                  </span>
                </div>

              </div> -->
            </div>
          <?php endfor;?>
          
          <div class="page-break"></div>

        <?php 
        $no++;
          endfor;
        ?>




        <!-- <span class="page-break-after"></span> -->


        <?php
        // if($i < 2) echo '<span class="page-break-after"></span>';
        endforeach; 
        ?>


    </main>

  <!-- <script src="<?php echo base_url('assets/js/jquery2.0.3.min.js') ;?>"></script>
  <script src="<?php echo base_url('assets/js/jquery.mask.js'); ?>"></script> -->
  <!-- <script src="assets/js/jquery2.0.3.min.js"></script>
  <script src="assets/js/jquery.mask.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){

      $('.biaya').mask('000.000.000.000,00', {reverse: true});
    });
  </script> -->
  
  </body>
</html>