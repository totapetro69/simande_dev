<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>


<section class="wrapper">

  <div class="breadcrumb margin-bottom-10">
   <?php echo breadcrumb();?>

   <div class="bar-nav pull-right ">
    <?php 
    if($cek->message[0]->APPROVAL_MD != 1){ 
      ?>
      <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('sales_event/add_budget/'. $cek->message[0]->ID.'/'.$cek->message[0]->KD_EVENT); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
        <i class="fa fa-file-o fa-fw"></i>Baru
      </a>
      <?php
    }
    ?>
    <a class="btn btn-default <?php echo $status_c?>" href="<?php echo base_url('sales_event/list_event');?>" role="button">
      <i class="fa fa-list-ul fa-fw"></i>List 
    </a>

  </div>

</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Detail Event Budget
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

      <table class="table table-striped b-t b-light">
        <tr>
          <td>Kode Event</td>
          <td>: <?php echo $cek->message[0]->KD_EVENT; ?></td>
        </tr>
        <tr>
          <td>Nama Event</td>
          <td>: <?php echo $cek->message[0]->NAMA_EVENT; ?></td>
        </tr>
        <tr>
          <td>Periode</td>
          <td>: <?php echo $cek->message[0]->START_DATE; ?> - <?php echo $cek->message[0]->END_DATE; ?></td>
        </tr>
        <tr>
          <td>Periode</td>
          <td>: <?php if($cek->message[0]->APPROVAL_MD == 0){
            echo 'Waiting';
          }elseif($cek->message[0]->APPROVAL_MD == 1){
            echo 'Approved';
          } else{
            echo 'Rejected';
          }
          ?></td>
        </tr>
      </table>

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
            <th>Kode Budget</th>
            <th>Nama Budget</th>
            <th>Jumlah Budget</th>
            <th>Aktual Budget</th>
            <th>Keterangan Budget</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = $this->input->get('page');
          if($list):
            if(is_array($list->message) || is_object($list->message)):
              foreach($list->message as $key=>$row): 
                $edit = $row->APPROVAL_MD == 1 ?$status_e:'disabled-action';
                $aktual = $row->APPROVAL_MD == 0 ?$status_e:'disabled-action';
                $no ++;
                ?>

                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                  <td><?php echo $no;?></td>
                  <td class="table-nowarp">
                    <?php 
                    if($cek->message[0]->APPROVAL_MD == 1){ 
                      ?>
                      <a id="aktual" onclick='addForm("<?php echo base_url('sales_event/act/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                        <i data-toggle="tooltip" data-placement="left" title="Aktual Budget" class="fa fa-book text-active"></i>
                      </a>
                      <?php
                    }else{
                      ?>
                      <a id="edit" onclick='addForm("<?php echo base_url('sales_event/edit_budget/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                        <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                      </a>
                      <?php
                    }
                    ?>
                    
                    <?php 
                    if($row->ROW_STATUS == 0){ 
                      if($cek->message[0]->APPROVAL_MD != 1){ 
                        ?>
                        <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('sales_event/delete_budget/'.$row->ID.'/'.$row->KD_EVENT); ?>">
                          <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                        </a>
                        <?php
                      }
                    }
                    ?>
                  </td>
                  <td><?php echo $row->KD_BUDGET;?></td>
                  <td><?php echo $row->NAMA_BUDGET;?></td>
                  <td><?php echo number_format($row->JUMLAH_BUDGET,0); ?></td>
                  <td><?php echo number_format($row->AKTUAL_BUDGET,0); ?></td>
                  <td><?php echo $row->KETERANGAN_BUDGET;?></td>
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