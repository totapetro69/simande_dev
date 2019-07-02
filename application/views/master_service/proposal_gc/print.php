<style type="text/css">
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
    }

    .project span {
    text-align: left;
         width: 100px; 
         margin-right: 15px; 
        padding: 2px 0;
        display: table-cell;
         font-size: 0.8em; 
    }

    .project .content {
        width: 100%;
    }

    /*@page { size: portrait; }*/
</style>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">PROPOSAL GC</h4>
    </div>

    <div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td><h4><u><strong>PROPOSAL GC</strong></u></h4></td>
        </tr>
    </table>
    <table border='0' id="desc" class="">
        <tr>
            <td style="width:150px;">No Proposal</td>
            <td style="width:15px;">:</td>
            <td><?php echo $cek->message[0]->NO_TRANS; ?></td>
            <td style="width:150px;">No PO Perusahaan</td>
            <td style="width:15px;"></td>
            <td><?php echo $cek->message[0]->NO_PO_PERUSAHAAN; ?></td>
        </tr>
        <tr>
            <td>Program Description</td>
            <td>:</td>
            <td colspan="4"><?php echo $cek->message[0]->DESC_PROGRAM; ?></td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td>:</td>
            <td colspan="4"><?php echo $cek->message[0]->TYPE; ?></td>
        </tr>
        <tr>
            <td>Period</td>
            <td>:</td>
            <td colspan="4"> <?php echo $cek->message[0]->START_DATE; ?> s/d <?php echo $cek->message[0]->END_DATE; ?></td>
        </tr>
    

    </table>



    <table border='0' class="table table-hover table-striped">

        <thead>
            <tr>
                <th>KD TIPE</th>
                <th>QTY</th>
                <th>SK AHM</th>
                <th>SK MD</th>
                <th>SK SD</th>
                <th>SK Finance </th>
                <th>SC AHM</th>
                <th>SC MD</th>
                <th>SC SD</th>
                <th>HRG KONTRAK</th>
                <th>FEE</th>
                <th>P STNK</th>
                <th>P BPKB</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if ($list) {
                $no = 0;
                if (is_array($list->message)) {
                    foreach ($list->message as $key => $row) {
                    $no++;
                ?>

                <tr>
                  <td><?php echo $row->KD_TYPEMOTOR;?></td>
                  <td><?php echo number_format($row->QTY);?></td>
                  <td><?php echo number_format($row->S_AHM,0);?></td>
                  <td><?php echo number_format($row->S_MD,0);?></td>
                  <td><?php echo number_format($row->S_SD,0);?></td>
                  <td><?php echo number_format($row->SK_FINANCE,0);?></td>
                  <td><?php echo number_format($row->SC_AHM,0);?></td>
                  <td><?php echo number_format($row->SC_MD,0);?></td>
                  <td><?php echo number_format($row->SC_SD,0);?></td>
                  <td><?php echo number_format($row->HARGA_KONTRAK,0);?></td>
                  <td><?php echo number_format($row->FEE,0);?></td>
                  <td><?php echo number_format($row->PENGURUSAN_STNK,0);?></td>
                  <td><?php echo number_format($row->PENGURUSAN_BPKB,0);?></td>

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

    </table>
    <table border="0">
      <tr>
        <td style="width:600px;">List Kota :</td>
        <td style="width:200px;">List Leasing :</td>
      </tr>
      <tr>
        <td valign="top">- <?php echo $cek->message[0]->NAMA_KABUPATEN; ?></td>
        <td>
          <?php
          $arr=explode(", ",$cek->message[0]->KD_LEASING);
          foreach($arr as $i) echo "- $i<br>";
          ?>
          
        </td>
      </tr>
    </table>
    <br>
    <br>
    <table border="0">
      <tr>
        <td colspan="2" align="right"><?php echo $cek->message[0]->NAMA_KABUPATEN; ?>, <?php echo date('d-m-Y');?></td>
      </tr>
      <tr>
        <td style="width:700px;">Menyetujui</td>
        <td>Mengajukan</td>
      </tr>
      <tr>
        <td><br><br><br></td>
          <td></td>
      </tr>
      <tr>
        <td>..........................</td>
        <td><?php echo $cek->message[0]->NAMA; ?></td>
      </tr>
    </table>



</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
    function printSj() {
        printJS('printarea', 'html');
        $('#keluar').click();
    }
</script>