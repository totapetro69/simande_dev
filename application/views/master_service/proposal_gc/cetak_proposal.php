<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Proposal GC</title>

   <style type="text/css">
   /** Define now the real margins of every page in the PDF **/
   body {
      margin-top: 3cm;
      margin-bottom: 2cm;
   }

   /** Define the header rules **/
   header {
      position: fixed;
      top: 0cm;
      left: 0cm;
      right: 0cm;

      text-align: center;

      border-top: 0px solid  #5D6975;
      border-bottom: 0px solid  #5D6975;
      color: #5D6975;
      line-height: 1.4em;
      font-weight: normal;
      margin: 5px 0 5px 0;
      /*background: url("../images/dimension.png");*/

      min-height: 150px;
   }

   /** Define the footer rules **/
   footer {
      position: fixed; 
      bottom: 0cm;
      left: 0cm; 
      right: 0cm;
      height: 2cm;

      text-align: center;
      /*line-height: 1.5cm;*/
   }

   #header-left
   {
      width: 100px;
      height: 100px;
      float: left; 
      /*background: url("../images/honda_logo.png");*/
      background-size: 100px;
   }

   #header-right
   {
      width: 100px;
      height: 100px;
      float: right; 
      /*background: url("../images/ahass.png");*/
      background-size: 100px;
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
        width: 200px;
        font-size: 10px;
    }

    .project p{
      margin: 2px 0;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }
    main{
      font-size: 11px;
    }

    table.table-full {
      width: 100%;
    }

    table.table-border{
      border-collapse: collapse;
    }

    table.table-border td {
       border: 1px solid  #5D6975;
    }

    td{
      vertical-align: top;
      padding: 5px;
    }

    td.content{
      font-size: 10px;
    }

    td.title{
      font-size: 11px;
    }

    input.type1
    {
       border: 1px solid  #5D6975;
       height: 10px;
       width: 20px;

    }
    input.type2{
       border: 1px solid  #5D6975;
       height: 10px;
       width: 10px;

    }
    </style>

  </head>
  <body>

<?php

  $no_trans=""; $desc_program=""; $type=""; $no_po_perusahaan=""; $kd_kabupaten=""; $kd_leasing="";
$date="";
  if(isset($dealer)){
    if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        $kd_dealerahm = $value->KD_DEALERAHM;
        $alamat = $value->ALAMAT;
        $nama_kabupaten = NamaWilayah("Kabupaten",$value->KD_KABUPATEN);
        $tlp = $value->TLP;
        $nama_dealer = $value->NAMA_DEALER;
      }
    }
  }

  $nama_user = NamaUser($this->session->userdata("user_id"));
?>

<header class="clearfix">
  <div id="header" style="width:1000px; display: flex !important; align-items:center;">
    <table style="border: none;" id="desc" >
      <tr>
        <td style="text-align: left; width:100px !important;"><span style="font-size: 10px;">No Proposal<br>
          Program Description<br>
          Type<br>
          Periode
        </td>
        <td><span style="font-size: 10px;">:<br>:<br>:<br>:</td>
        <td style="text-align: left; width:200px !important;"><span style="font-size: 10px;"><?php echo $no_trans;?><br>
          <?php echo $desc_program;?> <br>
          <?php echo $type;?><br>
          <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></span>
        </td>
        <td style="text-align: center; width:200px !important;"><h4 style="margin:0 0 5px 0;"><strong>Proposal GC</strong></h4></td>
        <td style="width:250px !important;"></td>
        <td style="text-align: left; width:100px !important;"><span style="font-size: 10px;">No PO Perusahaan</td>
        <td>:</td>
        <td style="text-align: left; width:200px !important;"><span style="font-size: 10px;"><?php echo $no_po_perusahaan;?></span></td>
      </tr>
    </table>
  </div>
</header>
<main>
  <table border='1' class="table-full table-border" style="width:1000px;border-collapse: collapse;">
    <thead>
      <tr>
        <th style="text-align: center!important; width:5px !important;">NO</th>
        <th style="text-align: center; width:8px">KD TIPE</th>
        <th style="text-align: center; width:8px;">QTY</th>
        <th style="text-align: center; width:8px;">SK AHM</th>
        <th style="text-align: center; width:8px;">SK MD</th>
        <th style="text-align: center; width:8px;">SK SD</th>
        <th style="text-align: center; width:8px;">SK FINANCE</th>
        <th style="text-align: center; width:8px;">SC AHM</th>
        <th style="text-align: center; width:8px;">SC MD</th>
        <th style="text-align: center; width:8px;">SC SD</th>
        <th style="text-align: center; width:8px;">HARGA KONTRAK</th>
        <th style="text-align: center; width:8px;">FEE</th>
        <th style="text-align: center; width:8px;">P STNK</th>
        <th style="text-align: center; width:8px;">P BPKB</th>
      </tr>
    </thead>

    <tbody>
      <?php
      if (isset($list)) {
        $no = 0;
        if (($list->totaldata >0 )) {
          foreach ($list->message as $key => $value) {
            # code...
            $no++;
            ?>
            <tr>
              <td style="text-align: center !important;width:5px!important;"><?php echo $no; ?></td>
              <td style="white-space: nowrap !important;"><?php echo ($value->KD_TYPEMOTOR);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->QTY),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SK_AHM),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SK_MD),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SK_SD),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SK_FINANCE),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SC_AHM),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SC_MD),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SC_SD),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->HARGA_KONTRAK),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->FEE),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->PENGURUSAN_STNK),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->PENGURUSAN_BPKB),0);?></td>
            </tr>
            <?php
          }
        }
      }
      ?>
    </tbody>   
  </table>
  <body>
    <table>
      <tr>
        <td style="text-align: left; width:200px !important;">List Kota: <br>
          <ul><li></li><?php echo $kd_kabupaten;?></li></ul></td>
      
        <td style="text-align: right; width:700px !important;">List Leasing: <br>
          <ul><li></li><?php echo $kd_leasing;?></li></ul></td>
      </tr>
    </table>
  </body>

  <footer>
    <table>
      <tr style="border-bottom: 0px !important; height: 50px">
        <td style="text-align: left; width:200px !important;">Menyetujui, <br><br><br>
        ________________________</td>
        <td style="text-align: right; width:700px !important;"><?php echo $kd_kabupaten;?><?php echo $date;?>Mengajukan, <br><br><br>
        ________________________</td>
      <!-- <td colspan="13" align="left" valign="bottom"><?php echo "Dicetak:".$nama_user."-".date('d-F-Y H:i:s');?></td> -->
    </tr>
    </table>
  </footer>

</main>

<footer>

  <div class="project">
  </footer>
</body>
</html>