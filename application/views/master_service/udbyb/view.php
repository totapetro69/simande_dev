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
         <!-- <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('laporan/add_udbyb'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
            <i class="fa fa-file-o fa-fw"></i> Upload File UDBYB
         </a> -->
         <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('laporan/createfile_udbyb?kd_dealer=' . $this->session->userdata("kd_dealer")); ?>" role="button">
                    <i class="fa fa-download fa-fw"></i> Download UDBYB
                </a>
      </div>

   </div>

   <div class="col-lg-12 padding-left-right-10">

      <div class="panel margin-bottom-10">

         <div class="panel-heading">
            File. UDBYB
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border" style="display: none;">
            <form id="filterForm" action="<?php echo base_url('laporan/udbyb') ?>" class="bucket-form" method="get">
               <div id="ajax-url" url=""></div>
               <div class="row">

                  <div class="col-xs-12 col-sm-12">
                     <div class="form-group">Search</label>
                        <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Nomor Permohonan Biaya (No. MHN) atau Nama Wilayah" autocomplete="off">
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
                     <!-- <th>Kode Main Dealer</th> -->
                     <th>Kode Dealer</th>
                     <th>No.Mohon Biaya</th>
                     <th>Nama Wilayah</th>
                     <th>Tgl Mohon Biaya</th>
                     <th>Tgl Faktur</th>
                     <th>No.Faktur</th>
                     <th>No.Mesin</th>
                     <th>No.Rangka</th>
                     <th>Kode Item</th>
                     <th>Nama Konsumen</th>
                     <th>Nilai BPKB</th>
                     <th>Nilai STCK</th>
                     <th>Formulir</th>
                     <th>SP3</th>
                     <th>Stnk</th>
                     <th>Plat Asli</th>
                     <th>Admin</th>
                     <th>Asuransi</th>
                     <th>Admin Samsat</th>
                     <th>Leges Fak</th>
                     <th>SPKB</th>
                     <th>Biaya</th>
                     <th>Kode Dealer2</th>
                     <th>Hash</th>
                     <th>Ket Rangka</th>
                     <!-- <th>Tgl Update</th> -->
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
                              <!-- <td><?php echo $row->KD_MAINDEALER;?></td> -->
                              <td><?php echo $row->KD_DEALER;?></td>
                              <td><?php echo $row->NO_TRANS;?></td>
                              <td><?php echo $row->NAMA_KABUPATEN;?></td>
                              <td><?php echo tglfromSql($row->TGL_STNK);?></td>
                              <td><?php echo tglfromSql($row->TGL_DOWNLOAD);?></td>
                              <td><?php echo $row->FAKTUR_PENJUALAN;?></td>
                              <td><?php echo $row->KD_MESIN.$row->NO_MESIN;?></td>
                              <td><?php echo $row->NO_RANGKA;?></td>
                              <td><?php echo $row->KD_ITEM;?></td>
                              <td><?php echo $row->NAMA_PEMILIK;?></td>
                              <td><?php echo number_format($row->BPKB);?></td>
                              <td><?php echo number_format($row->STCK);?></td>
                              <td>0</td>
                              <td>0</td>
                              <td>0</td>
                              <td><?php echo number_format($row->PLAT_ASLI);?></td>
                              <td>0</td>
                              <td>0</td>
                              <td><?php echo number_format($row->ADMIN_SAMSAT);?></td>
                              <td>0</td>
                              <td>0</td>
                              <td><?php echo number_format($row->BIAYA_BPKB);?></td>
                              <td><?php echo $row->KD_DEALER;?></td>
                              <td></td>
                              <td><?php echo $row->VNORANGKA1;?></td>
                              <!-- <td><?php echo $row->CREATED_TIME;?></td> -->
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