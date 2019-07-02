<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>

  <section class="wrapper">


  <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
    
   
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          Master Insentif PIC STNK
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
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
              <th>Nama</th>
              <th>Nilai</th>
              <th>Perubahan Terakhir</th>
              <th>Tanggal Perubahan</th>
            </tr>
          </thead>
          <tbody>
        <?php
          $no = $this->input->get('page');
          if($list):
            if(is_array($list->message) || is_object($list->message)):
            foreach($list->message as $key=>$row): 
            $no ++;
			echo '<script>console.log("'.$status_v.'")</script>';
        ?>

            <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
              <td><?php echo $no;?></td>
              <td class="table-nowarp">
                <a id="modal-button" onclick='addForm("<?php echo base_url('setup/edit_insentif_stnk/'.$row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                  <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                </a>
              </td>
              <td><?php echo $row->NAMA_CONFIG;?></td>
              <td><?php echo $row->VALUE_CONFIG;?></td>
              <td><?php echo $row->CREATED_BY;?></td>
              <td><?php echo $row->CREATED_TIME;?></td>
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