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

    <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('setup/detail_add_proposal_gc/'. $cek->message[0]->ID .'/'.$cek->message[0]->KD_GC); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i>Baru
    </a>
    <a class="btn btn-default <?php echo $status_c?>" href="<?php echo base_url('setup/proposal_gc');?>" role="button">
      <i class="fa fa-list-ul fa-fw"></i>List Proposal GC
    </a>

  </div>

</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Detail Proposal GC
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

                <table class="table table-striped b-t b-light">
                    <tr>
                        <td>No Proposal</td>
                        <td>: <?php echo $cek->message[0]->NO_TRANS; ?></td>
                    </tr>
                    <tr>
                        <td>Nama Program</td>
                        <td>: <?php echo $cek->message[0]->DESC_PROGRAM; ?></td>
                    </tr>
                    <tr>
                        <td>Kode Master GC</td>
                        <td>: <?php echo $cek->message[0]->KD_GC; ?></td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>: <?php echo $cek->message[0]->START_DATE; ?> - <?php echo $cek->message[0]->END_DATE; ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                          <form id="filterForm" action="<?php echo base_url('setup/detail_proposal_gc/'. $cek->message[0]->ID) ?>" class="bucket-form" method="get">
                                  <div class="form-group">
                                    <select id="row_status" name="row_status" class="form-control">
                                      <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                                      <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                                      <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
                                    </select>
                                  </div>


                            </form>

                        </td>
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
            <th>Tipe Motor</th>
            <th>Qty</th>
            <th>SK AHM</th>
            <th>SK MD</th>
            <th>SK SD</th>
            <th>MIN SK FINANCE</th>
            <th>SC AHM</th>
            <th>SC MD</th>
            <th>SC SD</th>
            <th>Hrg Kontrak</th>
            <th>Nilai Fee</th>
            <th>P_STNK</th>
            <th>P_BPKB</th>
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
                    <a id="modal-button" onclick='addForm("<?php echo base_url('setup/detail_edit_proposal_gc/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                      <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                    </a>
                    <?php 
                    if($row->ROW_STATUS == 0){ 
                      ?>
                      <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('setup/detail_delete_proposal_gc/'.$row->ID.'/'.$row->NO_TRANS); ?>">
                        <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </td>
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