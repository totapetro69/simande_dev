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

    <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('master_service/add_petd'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i> Upload File .PETD
    </a>

  </div>
  
</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Laporan PETD
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>

    <div class="panel-body panel-body-border" style="display: none;">

      <form id="filterForm" action="<?php echo base_url('master_service/petd') ?>" class="bucket-form" method="get">

        <div id="ajax-url" url="<?php echo base_url('master_service/petd_typeahead');?>"></div>

        <div class="row">

          <div class="col-xs-12 col-sm-8">
            <div class="form-group">PETD</label>
              <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan kode jasa" autocomplete="off">
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
            <th>Kd Main Dealer</th>
            <th>No PO MD-AHM</th>
            <th>TGL PO</th>
            <th>Dealer</th>
            <th>Part Number</th>
            <th>Part Deskripsi</th>
            <th>Quantitypo Awal</th>
            <th>Quantitybo AHM</th>
            <th>ETDAHM Awal</th>
            <th>ETDAHM Revised</th>
            <th>Tanggal Update</th>
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
                  <td><?php echo $row->KD_MAINDEALER;?></td>
                  <td><?php echo $row->NOPOMD_KE_AHM;?></td>
                  <td><?php echo $row->TGLPOMD_KE_AHM;?></td>
                  <td><?php echo $row->KD_DEALER;?></td>
                  <td><?php echo $row->PART_NUMBER;?></td>
                  <td><?php echo $row->PART_DESKRIPSI;?></td>
                  <td><?php echo $row->QUANTITYPO_AWAL;?></td>
                  <td><?php echo $row->QUANTITYBO_AHM;?></td>
                  <td><?php echo $row->ETDAHM_AWAL;?></td>
                  <td><?php echo $row->ETDAHM_REVISED;?></td>
                  <td><?php echo $row->CREATED_TIME;?></td>
                  <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                </tr>

                <?php 
              endforeach;
            else:
              ?>
              <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="40"><b><?php echo ($list->message); ?></b></td>
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