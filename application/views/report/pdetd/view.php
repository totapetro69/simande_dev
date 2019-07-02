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

    <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('master_service/add_pdetd'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i> Upload File .PDETD
    </a>

  </div>
  
</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      PDETD
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>

    <div class="panel-body panel-body-border" style="display: none;">

      <form id="filterForm" action="<?php echo base_url('master_service/pdetd') ?>" class="bucket-form" method="get">

        <div id="ajax-url" url="<?php echo base_url('master_service/pdetd_typeahead');?>"></div>

        <div class="row">

          <div class="col-xs-12 col-sm-8">
            <div class="form-group">PDETD</label>
              <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Part Number" autocomplete="off">
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
      <!-- <table class="table table-striped b-t b-light"> -->
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th rowspan="2" style="width:40px;">No.</th>
            <th rowspan="2" style="width: 40px">Nama Dealer</th>
            <th colspan="4">PO</th>
            <th rowspan="2">Part Number</th>
            <th rowspan="2">Part Deskripsi</th>
            <th colspan="2">QTY PO</th>
            <th colspan="2">ETD AHM</th>  
            <th colspan="4">Konsumen</th>
            <th rowspan="2">Tanggal Update</th>
            <th rowspan="2">Status</th>
          </tr>

          <tr>
            <th>No.PO Dealer</th>
            <th>Tgl PO Dealer</th>
            <th>No.PO MD</th>
            <th>Tgl PO MD</th>
            <th>Awal</th>
            <th>AHM</th>
            <th>Awal</th>
            <th>Revisi</th>
            <th>Tgl. Pesan</th>
            <th>No. Pesanan</th>
            <th>Nama Konsumen</th>
            <th>No. Telepon</th>
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
                  <td class="text-center"><?php echo $no;?></td>
                  <td><?php echo $row->KD_DEALER;?></td>
                  <td class="table-nowarp"><?php echo $row->NOPODEALER_KE_MD;?></td>
                  <td class="table-nowarp"><?php echo TglFromSql($row->TGLPODEALER_KE_MD);?></td>
                  <td class="table-nowarp"><?php echo $row->NOPOMD_KE_AHM;?></td>
                  <td class="table-nowarp"><?php echo TglFromSql($row->TGLPOMD_KE_AHM);?></td>
                  <td class="table-nowarp"><?php echo $row->PART_NUMBER;?></td>
                  <td class="td-overflow-20" title="<?php echo $row->PART_DESKRIPSI;?>"><?php echo $row->PART_DESKRIPSI;?></td>
                  <td class="table-nowarp text-right"><?php echo number_format($row->QUANTITYPO_AWAL,0);?></td>
                  <td class="table-nowarp text-right"><?php echo number_format($row->QUANTITYBO_AHM,0);?></td>
                  <td class="table-nowarp"><?php echo TglFromSql($row->ETDAHM_AWAL);?></td>
                  <td class="table-nowarp"><?php echo TglFromSql($row->ETDAHM_REVISED);?></td>
                  <td class="table-nowarp"><?php echo TglFromSql($row->TGLPESANAN_KONSUMEN);?></td>
                  <td class="table-nowarp"><?php echo $row->NOPESANAN_KONSUMEN;?></td>
                  <td class="td-overflow-20" title="<?php echo $row->NAMA_KONSUMEN;?>"><?php echo $row->NAMA_KONSUMEN;?></td>
                  <td class="table-nowarp"><?php echo $row->NOTEL_KONSUMEN;?></td>
                  <td class="table-nowarp"><?php echo TglFromSql($row->CREATED_TIME);?></td>
                  <td class="table-nowarp"><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>

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