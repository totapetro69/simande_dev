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
      <a class="btn btn-default" href="<?php echo base_url('sparepart/hargapart');?>" role="button">
      <i class="fa fa-list-ul fa-fw"></i>List Harga Barang
    </a>

    </div>
   
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
                History Perubahan Harga 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: show;">

                <table class="table table-striped b-t b-light">
                    <tr>
                        <td>Dealer</td>
                        <td>: <?php echo $list_detail->message[0]->NAMA_DEALER; ?></td>
                    </tr>
                    <tr>
                        <td>Kode Barang</td>
                        <td>: <?php echo $list_detail->message[0]->PART_NUMBER; ?></td>
                    </tr>
                    <tr>
                        <td>Nama Barang</td>
                        <?php if($list_detail->message[0]->KATEGORI == 'Part'){
                          ?>
                          <td>: <?php echo $list_detail->message[0]->PART_DESKRIPSI; ?></td>
                          <?php
                        }elseif ($list_detail->message[0]->KATEGORI == 'Barang'){
                          ?>
                          <td>: <?php echo $list_detail->message[0]->NAMA_BARANG; ?></td>
                          <?php
                        }else{
                          ?>
                          <td>: <?php echo $list_detail->message[0]->KD_MOTOR.' - '.$list_detail->message[0]->KETERANGAN; ?></td>
                          <?php
                        }
                        ?>
                        
                    </tr>
                    <tr>
                        <td>Tipe Customer</td>
                        <td>: <?php echo $list_detail->message[0]->NAMA_TYPECUSTOMER; ?></td>
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
              <th>Tanggal Perubahan</th>
              <th>Pengubah</th>
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
                <a id="modal-button" onclick='addForm("<?php echo base_url('sparepart/edit_hargapart/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                  <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                </a>

				<?php 
											if($row->ROW_STATUS == 0){ 
											?>
                <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('sparepart/delete_hargapart/'.$row->ID); ?>">
                  <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                </a>
				<?php
											}
											?>
              </td>
              <td><?php echo $row->KATEGORI;?></td>
              <td><?php echo $row->PART_NUMBER;?></td>
              <td><?php if($row->KATEGORI == 'Part'){
                echo $row->PART_DESKRIPSI;
              }else if($row->KATEGORI == 'Barang'){
                echo $row->NAMA_BARANG;
              }else{
                echo $row->KD_MOTOR.'-'.$row->KETERANGAN;
              } ?></td>
              <td><?php echo $row->NAMA_TYPECUSTOMER;?></td>
              <td><?php echo number_format($row->HARGA_BELI,0);?></td>
              <td><?php echo number_format($row->HARGA_JUAL,0);?></td>
              <td><?php echo $row->DISKON_TYPE;?></td>
              <td><?php echo number_format($row->DISKON,0);?></td>
              <td><?php echo tglfromsql($row->START_DATE);?></td>
              <td><?php echo tglfromsql($row->END_DATE);?></td>
              <td><?php echo tglfromsql($row->CREATED_TIME);?></td>
              <td><?php echo $row->CREATED_BY;?></td>
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