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
 
    <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('master_service/add_historyservice'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i> Upload File
    </a>
 
  </div>
  
</div>
 
 
<div class="col-lg-12 padding-left-right-10">
 
  <div class="panel margin-bottom-10">
 
    <div class="panel-heading">
      History Service Terakhir
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
 
    <div class="panel-body panel-body-border" style="display: none;">
 
      <form id="filterForm" action="<?php echo base_url('master_service/historyservice') ?>" class="bucket-form" method="get">
 
        <div id="ajax-url" url="<?php echo base_url('master_service/historyservice_typeahead');?>"></div>
 
        <div class="row">
 
          <div class="col-xs-12 col-sm-12">
            <div class="form-group">History Service Terakhir</label>
              <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Nomor Polisi atau Nomor Mesin" autocomplete="off">
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
            <th>Tanggal Trans</th>
            <th>Flag JP</th>
            <th>Main Dealer</th>
            <th>Nama Customer</th>
            <th>No Mesin</th>
            <th>No Rangka</th> 
            <th>No Polisi</th>
            <th>Tipe PKB</th>
            <th>Problem</th>
            <th>ID Job</th>
            <th>Keterangan Job</th>
            <th>Part Number</th>
            <th>Qty</th>
            <th>Dealer</th>
            <!-- <th>Main Dealer</th> -->
            <th>Tanggal Update</th>
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
                  <td><?php echo tglfromSql($row->TGL_TRANS);?></td>
                  <td><?php echo $row->FLAG_JP;?></td>
                   <td><?php echo $row->KD_MAINDEALER;?></td>
                  <td><?php echo $row->NAMA_CUSTOMER;?></td>
                  <td><?php echo $row->NO_MESIN;?></td>
                  <td><?php echo $row->NO_RANGKA;?></td>
                  <td><?php echo $row->NO_POLISI;?></td>
                  <td><?php echo $row->TIPE_PKB;?></td>
                  <td><?php echo $row->PROBLEM;?></td>
                  <td><?php echo $row->ID_JOB;?></td>
                  <td><?php echo $row->KETERANGAN_JOB;?></td>
                  <td><?php echo $row->PART_NUMBER;?></td>
                  <td class="text-right"><?php echo number_format($row->QTY, 0);?></td>
                  <td><?php echo $row->KD_DEALER;?></td>
                 <!--  <td><?php echo $row->KD_MAINDEALER;?></td> -->
                  <td><?php echo $row->CREATED_TIME;?></td>
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