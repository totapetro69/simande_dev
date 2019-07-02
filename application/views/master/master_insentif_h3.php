<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

if (($this->session->userdata('kd_group')=='root') || ($this->session->userdata('kd_group')=='DS')){
    $actionapprove = "";
} else {
    $actionapprove = "hidden";
    $dealeruser = $this->session->userdata('kd_dealer');
}
?>

  <section class="wrapper">


  <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        
    <div class="bar-nav pull-right ">

      <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('setup/add_masterinsentifh3'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Baru
      </a>

    </div>
   
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          Master Insentif H3
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" >

        <form id="filterForm" action="<?php echo base_url('setup/masterinsentifh3') ?>" class="bucket-form" method="get">

          <div id="ajax-url" url="<?php echo base_url('setup/masterinsentifh3_typeahead');?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-8">
              <div class="form-group">
                <label>Kode Dealer</label>
                  
                <select name="kd_dealer" id="kd_dealer" class="form-control">
                        <?php       
             
                           if (($this->session->userdata('kd_group')=='root') || ($this->session->userdata('kd_group')=='DS')){
                        ?>
                                <option value="">--Pilih Dealer--</option>
                        <?php
                                if(is_array($dealer->message)){
                                    foreach ($dealer->message as $key => $value) {
                                        $len =(strlen($value->KD_DEALER) >=3)?"":"&nbsp;";
                                        $select=($dealerpilih==$value->KD_DEALER)?"selected":"";
                                        echo "<option value='".$value->KD_DEALER."' ".$select.">"." [ ".$value->KD_DEALER.$len." ] ".$value->NAMA_DEALER."</option>";
                                    }
                                }
                            } else {
                                if(is_array($dealer->message)){
                                    foreach ($dealer->message as $key => $value) {
                                        $len =(strlen($value->KD_DEALER) >=3)?"":"&nbsp;";
                                        if ($dealeruser==$value->KD_DEALER) {
                                            echo "<option value='".$value->KD_DEALER."' selected>"." [ ".$value->KD_DEALER.$len." ] ".$value->NAMA_DEALER."</option>";
                                        }
                                    }
                                }
                            }
                            
                        ?>
                </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                    <label>Status</label>
                    <select id="row_status" name="row_status" class="form-control">
                        <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : "");?>>Aktif</option>
                        <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : "");?>>Tidak Aktif</option>
                        <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : "");?>>Semua</option>
                    </select>
                </div>
                <div class="pull-right">
                    <br>
                   <button class="btn btn-info <?php echo $actionapprove ?>" type="button" id="aprv"><i class="fa fa-cog"></i> Approved</button>
                   
                    <button class="btn btn-default disabled-action hidden" type="button" id="unaprv"><i class="fa fa-trash"></i> Un Approved</button>
                </div>
            </div>


          </div>

        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel panel-default">

      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th style="width:45px;">Aksi</th>
              <th>Dealer</th>
              <th>Nama</th>
              <th>Persentase</th>
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
              <td class="table-nowarp">
                <a id="modal-button" onclick='addForm("<?php echo base_url('setup/edit_masterinsentifh3/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                  <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                </a>
				<?php 
											if($row->ROW_STATUS == 0){ 
											?>
                <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('setup/delete_masterinsentifh3/'.$row->ID); ?>">
                  <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                </a>
				<?php
											}
											?>
                </td>
                <td><?php echo $row->NAMA_DEALER;?></td>
                <td><?php echo $row->NAMA;?></td>
                <td><?php echo number_format($row->PERSENTASE, 0); ?></td>
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
      </div>
      <footer class="panel-footer">
          <div class="row">

              <div class="col-sm-5">
                  <small class="text-muted inline m-t-sm m-b-sm"> 
                      <?php echo ($list)? ($list->totaldata==''?"":"<i>Total Data ". $list->totaldata ." items</i>") : '' ?>
                  </small>
              </div>
              <div class="col-sm-7 text-right text-center-xs">                
                   <?php echo $pagination;?>
              </div>
          </div>
      </footer>

    </div>
  </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        
            var kd_dealer = $('#kd_dealer').val();
            $('#aprv').click(function(){
                    
                     console.log("Kd Dealer "+kd_dealer);
                    __approval(kd_dealer);
            })
	})
	function __approval(kd_dealer){
		
            $.post("<?php echo base_url();?>setup/approve_masterinsentifh3/"+kd_dealer,function(result){
                console.log(result);
                document.location.reload();
                $('#loadpage').removeClass("hidden");

            })
		
	}
</script>