<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Approve Insentif PIC Part</h4>
</div>

<div class="modal-body">
  <div class="row">
      <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/prosesapprove_insentifpicpart/'.$no_proses);?>" method="post">
    <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th>NIK</th>
              <th>Insentif</th>
              <th>Status</th>
              <th>Status Approve</th>
            </tr>
          </thead>
          <tbody>
        <?php
          $no = $this->input->get('page');
          if($list):
            if(is_array($list->message) || is_object($list->message)):
            foreach($list->message as $key=>$row): 
            $no ++;
        ?>

            <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
              <td><?php echo $no;?></td>
              <td><?php echo $row->NIK;?></td>
              <td><?php echo $row->INSENTIF; ?></td>
                <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                 <td><?php echo $row->STATUS_APPROVE == 1 ? 'Sudah Approve' : 'Belum Approve'; ?></td>
            </tr>

          <?php 
            endforeach;
            else:
          ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="8"><b><?php echo ($list->message); ?></b></td>
            </tr>
        <?php
            endif;
          else:
        ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="8"><b>ada error, harap hubungi bagian IT</b></td>
            </tr>
        <?php
          endif;
        ?>
          </tbody>
        </table>
        <div id="no_proses" style=""><?php echo $no_proses ?></div>
      </div>

      </form>
  </div>
</div>



<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Approve</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">

   $(document).ready(function(){
        
            var no_proses = document.getElementById('no_proses');
            console.log("No Proses "+no_proses);
            $('#aprv').click(function(){
                    
                     console.log("No Proses "+no_proses);
                    __approval(no_proses);
            })
	})
	function __approval(no_proses){
		
            $.post("<?php echo base_url();?>setup/prosesapprove_insentifpicpart/"+no_proses,function(result){
                console.log(result);
                document.location.reload();
                $('#loadpage').removeClass("hidden");

            })
		
	}=

   
</script>
