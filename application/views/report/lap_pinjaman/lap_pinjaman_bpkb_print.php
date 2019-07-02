<style type="text/css">
    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }

    @page { size: portrait; }
</style>

<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title" id="myModalLabel">Laporan Pinjaman BPKB</h4>
</div>

<div class="modal-body" id="desc">
   <div class="table-responsive h350">
      <div id="printarea">
         <div align="center"><h2><strong>Laporan Pinjaman BPKB</strong></h2></div>
         <hr>
         <table style="border-collapse: collapse;" width="793.7px" border="1">
            <thead id="hdr">
               <tr>
               <th style="width:38px">No.</th>
               <th style="width:90px">Nomor Mhn</th>
               <th style="width:90px">Tanggal Pinjam</th>
               <th style="width:150px">No Mesin</th>
               <th style="width:200px">Nama</th>
               <th style="width:90px">Jumlah</th>
               <th style="width:135px">Keterangan</th>
            </tr>
            </thead>
         <tbody>
            <?php
              $no = 0;

              if ($list):
                if ($list->totaldata >0):
                  foreach ($list->message as $key => $row):
                    $no ++;
                     ?>

                     <tr id="<?php echo $this->session->flashdata('tr-active') == $row->NO_TRANS ? 'tr-active' : ' '; ?>" >
                        <td align="center"><?php echo $no; ?></td>
                        <td nowrap="true"><?php echo $row->NO_TRANS; ?></td>
                        <td style="white-space: nowrap;"><?php echo tglFromSql($row->TGL_PINJAM); ?></td>
                        <td style="white-space: nowrap;"><?php echo $row->NO_MESIN; ?></td>
                        <td style="white-space: nowrap;"><?php echo $row->NAMA_PEMILIK; ?></td>
                        <td style="white-space: nowrap;" align="right"><?php echo number_format($row->BIAYA_BPKB,0); ?></td>
                        <td nowrap="true" style="white-space: nowrap;"><?php echo ((int)$row->REQ_ADMIN_SAMSAT==2)?'ADMIN SAMSAT, ':'';?>
                           <?php echo ((int)$row->REQ_BPKB==2)?'BPKB, ':'';?>
                           <?php echo ((int)$row->REQ_PLAT_ASLI==2)?'PLAT ASLI, ':'';?>
                           <?php echo ((int)$row->REQ_STCK==2)?'STCK ':'';?>
                        </td>
                     </tr>

                       <?php
                  endforeach;
               else:
              ?>
              <tr>
                  <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                  <td colspan="6"><b><?php echo ($list->message); ?></b></td>
               </tr>

            <?php
            endif;
          else:
            echo belumAdaData(7);
          endif;
            ?>
          </tbody>

          </table>

      </div>
   </div>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
  <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
  function printSj() {
    //printJS('printarea', 'html');
    printJS({
      printable:'printarea',
      type:'html',
      //header:'hdr'
    })
    $('#keluar').click();
  }
</script>