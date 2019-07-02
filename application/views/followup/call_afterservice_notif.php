<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}


$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">List Pekerjaan</h4>
</div>

<div class="modal-body">

  <div  id="printarea">
    
    <table id="desc" class="table table-striped table-bordered">

      <thead>
        <tr>
          <th style="width:40px;">No.</th>
          <th>KD. Pekerjaan</th>
          <th>Keterangan</th>
          <th>Qty</th>
          <th>Kategori</th>
        </tr>
      </thead>

      <tbody>


      <?php
      $no = $this->input->get('page');
      if ($list):
        if (is_array($list->message) || is_object($list->message)):
        
          foreach ($list->message as $key => $row):

          $no ++;
          ?>

          <tr>
            <td><?php echo $no;?></td>
            <td><?php echo $row->KD_PEKERJAAN;?></td>
            <td><?php echo $row->PART_DESKRIPSI;?></td>
            <td><?php echo $row->QTY;?></td>
            <td><?php echo $row->KATEGORI;?></td>
          </tr>

      <?php endforeach; endif; endif;?>
      </tbody>


    </table>

  </div>


</div>

<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <!-- <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button> -->

</div>

<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>

<script type="text/javascript">
  function printSj() {
    printJS('printarea','html');
     $('#keluar').click();
  }

  $(document).ready(function(){

    var date = new Date();
    date.setDate(date.getDate());

  });

</script>

