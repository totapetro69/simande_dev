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
      line-height: 1;
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
      height: 2.4cm; 
      /* plus .6 inches from padding */
      /* plus .125 inches from padding */
      /*height: 3.3cm; */
      width: 7.6cm; 
      /*padding: 0.04cm 0.13cm;*/

      /* the gutter */
      padding-right: 5px; 
      padding-left: 5px; 

      /* the gutter */
      /*margin-bottom: 3px; */
      padding-top: 7px; 

      float: left;

      text-align: left;
      overflow: hidden;

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
          for($i=0; $i<8; $i++):
        ?>

          <?php for($j=0; $j<2; $j++): ?>
            <div class="label">

              <table>
                <tr>
                  <td>
                    <img style="max-width:130px; height: 20px" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->VNORANGKA1.$detail->NO_RANGKA));?>">
                  </td>
                  <td>
                    <img style="max-width:130px; height: 20px" src="data:image/png;base64,<?php echo base64_encode(set_barcode($detail->NO_MESIN));?>">
                  </td>
                </tr>
              </table>

              <table style="font-size: 12px;">
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
                </tr>
              </table>

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