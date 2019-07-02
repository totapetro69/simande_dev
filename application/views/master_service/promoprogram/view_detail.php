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

    <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('master_service/detail_add_promoprogram/'. $cek->message[0]->KD_PROMO); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i>Baru
    </a>
    <a class="btn btn-default <?php echo $status_c?>" href="<?php echo base_url('master_service/promoprogram');?>" role="button">
      <i class="fa fa-list-ul fa-fw"></i>List Promo Progam
    </a>

  </div>

</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Detail Promo Program
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

                <table class="table table-striped b-t b-light">
                    <tr>
                        <td>Kode Promo</td>
                        <td>: <?php echo $cek->message[0]->KD_PROMO; ?></td>
                    </tr>
                    <tr>
                        <td>Nama Program</td>
                        <td>: <?php echo $cek->message[0]->NAMA_PROGRAM; ?></td>
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
            <th>Kode Promo Program</th>
            <th>Kode Pekerjaan</th>
            <th>Harga</th>
            <th>Part Number</th>
            <th>Harga Part Number</th>
            <th>Status</th>
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
                    <a id="modal-button" onclick='addForm("<?php echo base_url('master_service/detail_edit_promoprogram/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                      <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                    </a>
                    <?php 
                    if($row->ROW_STATUS == 0){ 
                      ?>
                      <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('master_service/detail_delete_promoprogram/'.$row->ID.'/'.$row->KD_DETAILPROMO); ?>">
                        <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </td>
                  <td><?php echo $row->KD_DETAILPROMO?></td>
                  <td><?php echo $row->KD_PEKERJAAN;?></td>
                  <td><?php echo number_format($row->HARGA,0);?></td>
                  <td><?php echo $row->NO_PART;?></td>
                  <td><?php echo number_format($row->HARGA_NO_PART,0);?></td>
                  <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
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