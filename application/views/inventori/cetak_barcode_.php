<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Surat Penyerahan</title>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" >

    <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->
    
    <style type="text/css">



    /** Define now the real margins of every page in the PDF **/
    body {
        margin-top: 3cm;
        /*margin-left: 2cm;*/
        /*margin-right: 2cm;*/
        margin-bottom: 2cm;
    }

    /** Define the header rules **/
    header {
        position: fixed;
        top: 0cm;
        /*left: 0cm;*/
        /*right: 0cm;*/
        height: 2cm;

        /*text-align: center;*/
        line-height: 10px;
    }

    /** Define the footer rules **/
    footer {
        position: fixed; 
        bottom: 0cm; 
        /*left: 0cm; */
        /*right: 0cm;*/
        height: 2cm;

        text-align: center;
        /*line-height: 1.5cm;*/
    }

    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
        text-align: left;
        display: table;
        width: 100%;
    }
    .project div {
        display: table-row;
    }

    .project .title {
        color: #5D6975;
        width: 90px;
        font-size: 10px;
    }

    .project span {
        text-align: left;
        padding: 2px 0;
        display: table-cell;
    }

    .project .content {
        width: 150px;
        font-size: 10px;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
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
    <header class="clearfix">

      <h4><strong>BARCODE KPB</strong></h4> 

    </header>

    <main>
        <table class="table">
        <?php 
        $no=1;
        $total = 0;
        foreach($list->message as $key => $detail): 
          for($i=0; $i<2; $i++):
        ?>

        <tr>
          <?php for($j=0; $j<3; $j++): ?>

          <td>
            <div class="project">
              <div style="">
                <span class="content">
                  <img style="max-width:120px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->TGL_SURATJALAN));?>">
                </span> 
                <span class="content">
                  <img style="max-width:120px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->NO_MESIN));?>">
                </span>
              </div>

              <div style="">
                <span class="content">
                  <img style="max-width:120px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->VNORANGKA1.$detail->NO_RANGKA));?>">
                </span> 
                <span class="content">
                </span>
              </div>


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
                  <?php echo $detail->ALAMAT_KIRIM;?>
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
                  <?php echo $detail->DATA_NOMOR;?>
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
            </div>
          </td>

          <?php endfor;?>

        </tr>

        <?php 
        $no++;
          endfor;
        ?>
        </table>



        <span class="page-break-after"></span>



        <?php
        if($i < 2) echo '<span class="page-break-after"></span>';
        endforeach; 
        ?>


        <table class="table">
        <?php 
        $total = 0;
        foreach($list->message as $key => $detail2): 
          for($k=0; $k<6; $k++):
        ?>

        <tr>
          <?php for($l=0; $l<2; $l++): ?>

          <td>
            <div class="project">
              <div style="">
                <span class="content">
                  <img style="max-width:200px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail2->VNORANGKA1.$detail2->NO_RANGKA));?>">
                </span> 
                <span class="content">
                  <img style="max-width:200px;" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail2->NO_MESIN));?>">
                </span>
              </div>

              <div style="">
                <span class="title">
                  No. Rangka
                </span> 
                <span class="content">
                  <?php echo $detail2->VNORANGKA1.$detail2->NO_RANGKA;?>
                </span>
              </div>

              <div style="">
                <span class="title">
                  No. Mesin
                </span> 
                <span class="content">
                  <?php echo $detail2->NO_MESIN;?>
                </span>
              </div>
            </div>
          </td>

          <?php endfor;?>

        </tr>

        <?php 
          endfor;
        ?>

        </table>

        <?php
        if($k < 2) echo '<span class="page-break-after"></span>';
        endforeach; 
        ?>

    </main>

    <footer>

    </footer>

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