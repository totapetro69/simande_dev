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

    <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('master_service/add_uminv'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i> Upload File .UMINV
    </a>

  </div>
  
</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      UMINV
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>

    <div class="panel-body panel-body-border" style="display: none;">

      <form id="filterForm" action="<?php echo base_url('master_service/uminv') ?>" class="bucket-form" method="get">

        <div id="ajax-url" url="<?php echo base_url('master_service/uminv_typeahead');?>"></div>

        <div class="row">

          <div class="col-xs-12 col-sm-12">
            <div class="form-group">UMINV</label>
              <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan No. Inv dan Kode Tipe" autocomplete="off">
            </div>
          </div>

        </div>


      </form>

    </div>
    
  </div>

</div>

<div class="col-lg-12 padding-left-right-20">

  <div class="panel panel-default">

    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width:40px;">No.</th>
            <th>No. Inv</th>
            <th>Tanggal Inv</th>
            <th>Kode Tipe</th>
            <th>Kode Warna</th>
            <th>Dealer</th>
            <th>Cabang Dealer</th>
            <th>Qty</th>
            <th>Ammount</th>
            <th>MPPN</th>
            <th>MPrice</th>
            <th>MDiscount</th>
            <th>No Reff</th>
            <th>Main Dealer</th>
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
                  <td><?php echo $row->NO_INV;?></td>
                  <td><?php echo $row->TGL_INV;?></td>
                  <td><?php echo $row->KD_TIPE;?></td>
                  <td><?php echo $row->KD_WARNA;?></td>
                  <td><?php echo $row->KD_DEALER;?></td>
                  <td><?php echo $row->KD_CABANGDEALER;?></td>
                  <td><?php echo $row->QTY;?></td>
                  <td class="text-right"><?php echo number_format($row->AMOUNT, 0);?></td>
                  <td><?php echo number_format($row->MPPN, 0);?></td>
                  <td><?php echo number_format($row->MPRICE, 0);?></td>
                  <td class="text-right"><?php echo number_format($row->MDISCOUNT, 0);?></td>
                  <td><?php echo $row->NO_REFF;?></td>
                  <td><?php echo $row->KD_MAINDEALER;?></td>
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

