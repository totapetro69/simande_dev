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
      <a id="modal-button" class="btn btn-primary" onclick='addForm("<?php echo base_url('setup/add_saleskupon'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-download"></i> Update Sales Kupon
      </a>
	  <a id="modal-button" class="btn btn-primary" onclick='addForm("<?php echo base_url('setup/add_saleskupon_kota'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
         <i class="fa fa-download"></i> Update SK Kota
      </a>
	  <a id="modal-button" class="btn btn-primary" onclick='addForm("<?php echo base_url('setup/add_saleskupon_leasing'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-download"></i> Update SK Leasing
      </a>
    </div>
  </div>


  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
          Sales Kupon
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">
        <form id="filterForm" action="<?php echo base_url('setup/saleskupon') ?>" class="bucket-form" method="get">
          <div id="ajax-url" url="<?php echo base_url('setup/saleskupon_typeahead');?>"></div>
          <div class="row">

            <div class="col-xs-12 col-sm-8">
              <div class="form-group">
                  <label>Sales Kupon</label>
                  <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan kode atau nama Sales Kupon" autocomplete="off">
              </div>
            </div>
			
			<div class="col-xs-12 col-sm-4">
              <div class="form-group">
                <label>Pilih</label>
                <select id="pilih" name="pilih" class="form-control">
                  <option value="0" <?php echo ($pilih == 0 ? "selected" : ""); ?>>Sales Kupon Motor</option>
                  <option value="1" <?php echo ($pilih == 1 ? "selected" : ""); ?>>Sales Kupon Kota</option>
                  <option value="2") <?php echo ($pilih == 2 ? "selected" : ""); ?>>Sales Kupon Leasing</option>
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
	  <?php
	  if($pilih == 0){
	  ?>
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th>Kode</th>
      			  <th>Sales Kupon</th>
      			  <th>Tgl Mulai</th>
      			  <th>Tgl Selesai</th>
      			  <th>End Claim</th>
      			  <th>No. Akun</th>
      			  <th>Sub Akun</th>
      			  <th>Tipe Motor</th>
      			  <th>Nilai</th>
      			  <th>Top 1</th>
      			  <th>Top 2</th>
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
              <td class="table-nowarp"><?php echo $row->KD_SALESKUPON?></td>
              <td class="td-overflow-50"><?php echo $row->NAMA_SALESKUPON;?></td>
			        <td class="table-nowarp"><?php echo tglFromSql($row->START_DATE);?></td>
              <td class="table-nowarp"><?php echo tglFromSql($row->END_DATE);?></td>
      			  <td class="table-nowarp"><?php echo tglFromSql($row->END_CLAIM);?></td>
      			  <td class="table-nowarp"><?php echo $row->NO_PERKIRAAN;?></td>
      			  <td class="table-nowarp"><?php echo $row->NO_SUBPERKIRAAN;?></td>
      			  <td class="text-center"><?php echo $row->KD_TYPEMOTOR;?></td>
      			  <td class="table-nowarp text-right"><?php echo $row->NILAI;?></td>
      			  <td class="text-center"><?php echo $row->TOP1;?></td>
      			  <td class="text-center"><?php echo $row->TOP2;?></td>
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
                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
            </tr>
        <?php
          endif;
        ?>
          </tbody>
        </table>
		<?php
	  }elseif($pilih == 1){
	  ?>
	  
	  <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th>Kode</th>
			  <th>Sales Kupon</th>
			  <th>Kota</th>
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
              <td><?php echo $row->KD_SALESKUPON?></td>
              <td><?php echo $row->NAMA_SALESKUPON;?></td>
			  <td><?php echo $row->KD_DEALER;?></td>
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
                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
            </tr>
        <?php
          endif;
        ?>
          </tbody>
        </table>
		<?php
	  }else{
	  ?>
	  <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th>Kode</th>
			  <th>Sales Kupon</th>
			  <th>Leasing</th>
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
              <td><?php echo $row->KD_SALESKUPON?></td>
              <td><?php echo $row->NAMA_SALESKUPON;?></td>
			  <td><?php echo $row->KD_LEASING;?></td>
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
                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
            </tr>
        <?php
          endif;
        ?>
          </tbody>
        </table>
	  <?php
	  }
	  ?>
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