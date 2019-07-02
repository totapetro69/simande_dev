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

      <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('sparepart/add_hargapart'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Baru
      </a>

    </div>
   
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          Harga Jual
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">

        <form id="filterForm" action="<?php echo base_url('sparepart/hargapart') ?>" class="bucket-form" method="get">

          <div id="ajax-url" url="<?php echo base_url('sparepart/hargapart_typeahead');?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-8">
              <div class="form-group">
                <label>Harga Jual</label>
                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan nomor part atau kode barang" autocomplete="off">
              </div>
            </div>

            <div class="col-xs-12 col-sm-4">
              <div class="form-group">
                <label>Status</label>
                <select id="row_status" name="row_status" class="form-control">
                  <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : "");?>>Aktif</option>
                  <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : "");?>>Tidak Aktif</option>
                  <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : "");?>>Semua</option>
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
              <th>No.</th>
              <th>Aksi</th>
              <th>Kategori</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Type Customer</th>
              <th>Harga Beli</th>
              <th>Harga Jual</th>
              <th>Tipe Diskon</th>
              <th>Diskon</th>
              <th>Start Date</th>
              <th>End Date</th>
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
              <td class="table-nowarp text-center"><?php echo $no;?></td>
              <td class="table-nowarp text-center">
                <a id="modal-button" onclick='addForm("<?php echo base_url('sparepart/edit_hargapart/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_e?>">
                  <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                </a>
                <!-- <a href="<?php echo base_url('sparepart/history_hargapart/' . $row->PART_NUMBER.'/'.$row->KD_TYPECUSTOMER); ?>" role="button">
                                                <i data-toggle="tooltip" data-placement="left" title="History Perubahan" class="fa fa-file-o text-success text-active"></i>
                                            </a> -->
				<?php 
								if($row->ROW_STATUS == 0){ 
											?>
                <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_e;?>" url="<?php echo base_url('sparepart/delete_hargapart/'.$row->ID); ?>">
                  <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                </a>
				<?php
											}
											?>
              </td>
              <td class="table-nowarp"><?php echo $row->KATEGORI;?></td>
              <td class="table-nowarp"><?php echo $row->PART_NUMBER;?></td>
              <td class="td-overflow-50 table-nowarp" title="<?php echo $row->NAMA_BARANG;?>"><?php echo $row->NAMA_BARANG;?></td>
              <td class="table-nowarp"><?php echo $row->NAMA_TYPECUSTOMER;?></td>
              <td class="table-nowarp text-right"><?php echo number_format($row->HARGA_BELI,0);?></td>
              <td class="table-nowarp text-right"><?php echo number_format($row->HARGA_JUAL,0);?></td>
              <td class="table-nowarp"><?php echo $row->DISKON_TYPE;?></td>
              <td class="table-nowarp text-right"><?php echo number_format($row->DISKON,0);?></td>
              <td class="table-nowarp"><?php echo tglfromsql($row->START_DATE);?></td>
              <td class="table-nowarp"><?php echo tglfromsql($row->END_DATE);?></td>
              <td class="table-nowarp"><?php echo $row->NAMA_DEALER;?></td>
              <td class="table-nowarp"><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
            </tr>

          <?php 
            endforeach;
            else:
          ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="14"><b><?php echo ($list->message); ?></b></td>
            </tr>
        <?php
            endif;
          else:
        ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="14"><b>ada error, harap hubungi bagian IT</b></td>
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