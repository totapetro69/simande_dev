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

    <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('setup/add_proposal_gc'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i> Baru
    </a>

  </div>

</div>

<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Proposal GC
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>

    <div class="panel-body panel-body-border" style="display: none;">

      <form id="filterForm" action="<?php echo base_url('setup/proposal_gc') ?>" class="bucket-form" method="get">

        <div id="ajax-url" url="<?php echo base_url('setup/proposal_gc_typeahead');?>"></div>

        <div class="row">

          <div class="col-xs-12 col-sm-8">
            <div class="form-group"><label>Proposal GC</label>
              <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan kode atau nama Proposal GC" autocomplete="off">
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="form-group">
              <label>Status</label>
              <select id="row_status" name="row_status" class="form-control">
                <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
              </select>
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
            <th>No Proposal</th>
            <th>Nama Program</th>
            <th>No Master GC</th>
            <th>Tipe</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>No PO Perusahaan</th>
            <th>Kabupaten</th>
            <th>Dealer</th>
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
                    
                    <a id="modal-button" onclick='addForm("<?php echo base_url('setup/edit_proposal_gc/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                      <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                    </a>
                    <?php 
                    if($row->ROW_STATUS == 0){ 
                      ?>
                      <a id="modal-button" onclick='addForm("<?php echo base_url('setup/leasing_proposal_gc/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                      <i data-toggle="tooltip" data-placement="left" title="Leasing" class="glyphicon glyphicon-home"></i>
                    </a>
                      <a href="<?php echo base_url('setup/detail_proposal_gc/' . $row->ID); ?>" role="button" class="<?php echo  $status_v ?>">
                      <i data-toggle="tooltip" data-placement="left" title="Input Tipe Motor" class="glyphicon glyphicon-list-alt"></i>
                    </a>
                    <a id="modal-button" onclick='addForm("<?php echo base_url('setup/proposal_gc_print/'.$row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                      <i data-toggle="tooltip" data-placement="left" title="Cetak" class="glyphicon glyphicon-print"></i>
                    </a>
                    <a href="<?php echo base_url('setup/createfile_udpgc/' . $row->ID); ?>" role="button" class="<?php echo  $status_v ?>">
                      <i data-toggle="tooltip" data-placement="left" title="Download .UDPGC" class="glyphicon glyphicon-download-alt"></i>
                    </a>
                      <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('setup/delete_proposal_gc/'.$row->ID); ?>">
                        <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </td>
                  <td><?php echo $row->NO_TRANS?></td>
                  <td><?php echo $row->DESC_PROGRAM;?></td>
                  <td><?php echo $row->KD_GC;?></td>
                  <td><?php echo $row->TYPE;?></td>
                  <td><?php echo $row->START_DATE;?></td>
                  <td><?php echo $row->END_DATE;?></td>
                  <td><?php echo $row->NO_PO_PERUSAHAAN;?></td>
                  <td><?php echo $row->NAMA_KABUPATEN;?></td>
                  <td><?php echo $row->NAMA_DEALER;?></td>
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