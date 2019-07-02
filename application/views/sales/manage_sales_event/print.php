<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Laporan Sales Event</title>

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
        font-size: 9px;
    }

    .project p{
      margin: 1px 0;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }
    main{
      font-size: 10px;
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
      font-size: 12px;
    }

    td.title{
      font-size: 12px;
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
  $nama_user = NamaUser($this->session->userdata("user_id"));
  ?>

  <header class="clearfix">
    <div id="header" style="display: flex !important; align-items:center;">
      <table style="border: none;" id="desc" border="0" >
        <tr>
          <td style="text-align: center; width:900px !important;"><h4 style="margin:0 0 5px 0;"><strong>REPORT SALES EVENT</strong></h4><br><span style="font-size: 8px;">Periode : <?php echo ($this->input->get("start_date")) ? $this->input->get("start_date") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("end_date")) ? $this->input->get("end_date") : date('d/m/Y'); ?></span></td>
          <td style="width:100px !important;"><span style="font-size: 8px;">Tgl Cetak : <?php echo date('d/m/Y') ?></span></td>
        </tr>
      </table>
    </div>
  </header>

  <main>
    <table border='1' class="table-full table-border" style="border-collapse: collapse; width:1000px">
      <thead>
        <tr>
          <th rowspan="2" style=" width:3px !important;">No</th>
          <th style="text-align: center;" rowspan="2">Kode</th>
          <th style="text-align: center;" rowspan="2">Jadwal Event</th>
          <th style="text-align: center;" rowspan="2">Nama Event</th>
          <th style="text-align: center;" rowspan="2">Jenis</th>
          <th style="text-align: center;" rowspan="2">Expense</th>
          <th style="text-align: center;" rowspan="2">Aktual Provit</th>
          <th style="text-align: center;" colspan="2">Sales (Unit Motorcycle)</th>
          <th style="text-align: center;">% achieve</th>
        </tr>
        <tr>
          <th style="text-align: center;">Target (A)</th>
          <th style="text-align: center;">Actual (A)</th>
          <th style="text-align: center;">T/A</th>
        </tr>
      </thead>

      <tbody>
        <?php
        if ($list) {
          $no = 0;
          if (is_array($list->message)) {
            $total_ex = 0;
            $total_ap = 0;
            $total_t = 0;
            $total_a = 0;
            $total_ta = 0;
            foreach ($list->message as $key => $value) {
              $no++;
              $total_ex = $total_ex + $value->ALOKASI_BUDGET;
              $total_ap = $total_ap + $value->AKTUAL_BUDGET;
              $total_t = $total_t + $value->TARGET_UNIT;
              $total_a = $total_a + $value->UNIT_REVENUE;
              ?>
              <tr>
                <td style=" width:3px"><?php echo $no; ?></td>
                <td style="text-align: center;"><?php echo $value->KD_EVENT;?></td>
                <td style="text-align: center;"><?php echo tglFromSql($value->START_DATE);?>-<?php echo tglFromSql($value->END_DATE);?></td>
                <td style="text-align: center;"><?php echo $value->NAMA_EVENT;?></td>
                <td style="text-align: center;"><?php echo $value->NAMA_JENIS_EVENT;?></td>
                <td style="text-align: right;"><?php echo number_format($value->ALOKASI_BUDGET,0);?></td>
                <td style="text-align: center;"><?php echo number_format($value->AKTUAL_BUDGET,0);?></td>
                <td style="text-align: center;"><?php echo $value->TARGET_UNIT;?></td>
                <td style="text-align: center;"><?php echo number_format($value->UNIT_REVENUE,0);?></td>
                <td style="text-align: center;"><?php echo $pencapaian = ($value->TARGET_UNIT != NULL)?($value->UNIT_REVENUE/$value->TARGET_UNIT)*100: 0 ;?></td>
              </tr>  
              <?php
            }
          } else {
            belumAdaData(20);
          }
        } else {
          belumAdaData(20);
        }
        ?>
      </tbody>
      <tfoot>
        <tr class='total'>
          <td style="text-align: center;" colspan="3">Total per Period</td>
          <td style="text-align: center;" colspan="2"><?php echo ($list) ? ($list->totaldata == '' ? "" :  $list->totaldata. "</i>") : '' ?></td>
          <td style="text-align: center;"><?php echo number_format($total_ex,0);?></td>
          <td style="text-align: center;"><?php echo number_format($total_ap,0);?></td>
          <td style="text-align: center;"><?php echo number_format($total_t,0);?></td>
          <td style="text-align: center;"><?php echo number_format($total_a,0);?></td>
          <td style="text-align: center;"></td>
        </tr>
      </tfoot>
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