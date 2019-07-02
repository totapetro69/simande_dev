<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Laporan Insentif PIC Part</title>

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

  //$nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";$nama_user="";
    //var_dump($dealer);
  if(isset($dealer)){
    if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        if ($value->KD_DEALER==$this->session->userdata("kd_dealer")) {
            $kd_dealerahm = $value->KD_DEALERAHM;
            $alamat = $value->ALAMAT;
            $nama_kabupaten = NamaWilayah("Kabupaten",$value->KD_KABUPATEN);
            $tlp = $value->TLP;
            $nama_dealer = $value->NAMA_DEALER;
        }
      }
    }
  }

  $nama_user = NamaUser($this->session->userdata("user_id"));
?>

<header class="clearfix">
  <div id="header" style="width:750px; display: flex !important; align-items:center;">
    <table style="border: none;" id="desc" >
      <tr>
        <td style="text-align: left; width:200px !important;"><span style="font-size: 7px;"><?php echo $nama_dealer;?><br>
          <?php echo $alamat;?> <br>
          <?php echo $nama_kabupaten;?><br>
          <?php echo $tlp;?><br></span>
        </td>
        <td style="text-align: center; width:500px !important;"><h4 style="margin:0 0 5px 0;"><strong>Laporan Insentif PIC Part</strong></h4><br><span style="font-size: 8px;">Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></span></td>
        <td style="width:250px !important;"></td>
      </tr>
    </table>
  </div>
</header>
<main>
    
   <table border='1' class="table-full table-border" style="width:750px;border-collapse: collapse;">
 
      
      <tr>
        <th style="text-align: center;width:8px;">Jenis</th>
        <th style="text-align: center;width:8px;">Target</th>
        <th colspan="2" style="text-align: center;width:8px;">Aktual</th>
        <th style="text-align: center;width:8px;">Achievement</th>
        <th style="text-align: center;width:8px;">% Insentif</th>
        <th style="text-align: center;width:8px;">% X Sales Out</th>
        <th style="text-align: center;width:8px;">Index Insentif %</th>
        <th style="text-align: center;width:8px;">Jumlah</th>
        <th style="text-align: center;width:8px;">Total Insentif</th>
      </tr>


    <tbody>
      <?php
     
      
        if (($list->totaldata >0 )) {
          foreach ($list->message as $key => $value) {
          
            ?>
        <tr>
                <td rowspan="2" style="text-align: center !important;width:5px!important;"><?php echo 'Penjualan'; ?></td>
                <td rowspan="2" style="white-space: nowrap !important;"><?php echo number_format(($value->TARGETJUAL),0);?></td>
                <td style=" width:8px !important"><?php echo 'Counter'; ?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->TOTALCOUNTER));?></td>
                <td rowspan="2" style="white-space: nowrap !important;"><?php echo number_format(($value->ACHIEVEMENTJUAL));?></td>
                <td style="white-space: nowrap !important;"><?php echo '1.5';?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->PERSENCOUNTER));?></td>
                <td rowspan="2"  style="white-space: nowrap !important;"><?php echo ($value->INDEXINSENTIF);?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->JUMLAHINSENTIFCOUNTER));?></td>
                <td rowspan="2" style="white-space: nowrap !important;"><?php echo number_format(($value->TOTALINSENTIF));?></td>
            </tr>
             <tr>
              
                <td style=" width:8px !important"><?php echo 'Bengkel'; ?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->TOTALBENGKEL));?></td>
               
                <td style="white-space: nowrap !important;"><?php echo '0.25';?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->PERSENBENGKEL));?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->JUMLAHINSENTIFBENGKEL));?></td>
               
            </tr>
            <tr>
                <td style="text-align: center !important;width:5px!important;"><?php echo 'Pembelian'; ?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->TARGETBELI));?></td>
                <td style=" width:8px !important"><?php echo 'Main Dealer'; ?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->TOTALBELI));?></td>
                <td style="white-space: nowrap !important;"><?php echo number_format(($value->ACHIEVEMENTBELI));?></td>
                <td colspan="5"></td>
            </tr>
            <?php
            break;
          }
        }
      //}
      ?>
    </tbody>
  
    
  </table>
<br/><br/>  
    
  <table border='1' class="table-full table-border" style="width:750px;border-collapse: collapse;">
    <thead>
      
      <tr>
        <th style="text-align: center;width:8px;">No</th>
        <th style="text-align: center;width:8px;">NIK</th>
        <th style="text-align: center;width:8px;">Nama</th>
        <th style="text-align: center;width:8px;">Persentase</th>
        <th style="text-align: center;width:8px;">Insentif</th>

      </tr>
    </thead>

    <tbody>
      <?php
     
      //if (isset($list)) {
        $no = 0;
        if (($list->totaldata >0 )) {
          foreach ($list->message as $key => $value) {
              if ($value->ACHIEVEMENTBELI>50) {
                # code...
                $no++;
            ?>
                <tr>
                  <td style="text-align: center !important;width:5px!important;"><?php echo $no; ?></td>
                  <td style="white-space: nowrap !important;"><?php echo ($value->NIK);?></td>
                  <td style=" width:8px !important"><?php echo ($value->NAMA); ?></td>
                  <td style="white-space: nowrap !important;"><?php echo ($value->PERSENTASE);?></td>
                  <td style="white-space: nowrap !important;"><?php echo number_format(($value->INSENTIF));?></td>

                </tr>
            <?php
              }
          }
        }
      //}
      ?>
    </tbody>
  
    
  </table>

  <footer>
    <table>
      <tr style="border-bottom: 0px !important; height: 30px">
      <td colspan="13" align="left" valign="bottom"><h5><?php echo "Dicetak:".$nama_user."-".date('d-F-Y H:i:s');?></h5></td>
    </tr>
    </table>
  </footer>

</main>

<footer>

  <div class="project">
  </footer>
</body>
</html>