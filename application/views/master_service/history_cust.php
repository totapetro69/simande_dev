<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");

?>

<section class="wrapper">

   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb();?>
      <div class="bar-nav pull-right">
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
         <div class="panel-heading"><i class='fa fa-list-ul'> History Cust Purchase</i>
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border" style="display: block;">
            <form id="filterForm" action="<?php echo base_url('master_service/history_cust') ?>" class="bucket-form">

               <div class="row">

                  <div class="col-xs-12 col-sm-4">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="true">
                           <option value="0">--Pilih Dealer--</option>
                           <?php
                           if ($dealer) {
                              if (($dealer->totaldata > 0)) {
                                 foreach ($dealer->message as $key => $value) {
                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-12 col-sm-4">
                     <div class="form-group">
                        <label>Cari</label>
                        <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Nama Customer atau No SO" autocomplete="off">
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

            <table class="table table-bordered table-striped">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>No SO</th>
                     <th>Tgl Kirim</th>
                     <th>Nama Customer</th>
                     <th>No HP</th>
                     <th>Desk Tipe Unit</th>
                     <th>Desk Warna</th>
                     <th>Sales People</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  if ($list) {
                     $no = 0;
                     if (is_array($list->message)) {
                        foreach ($list->message as $key => $value) {
                           $no++;
                           ?>
                           <tr>
                              <td style=" width:3px"><?php echo $no; ?></td>
                           <td><?php echo $value->NO_SO;?></td>
                           <td><?php echo tglFromSql($value->TGL_KIRIM);?></td>
                           <td><?php echo $value->NAMA_CUSTOMER;?></td>
                           <td><?php echo $value->NO_HP;?></td>
                           <td><?php echo $value->NAMA_TYPEMOTOR;?></td>
                           <td><?php echo $value->KET_WARNA;?></td>
                           <td><?php echo $value->NAMA_SALES;?></td>
                        </tr>
                        <?php
                        }
                     } else {
                        belumAdaData(20);
                     }
                  } else {
                     belumAdaData(20);
                  }
                  ?>
               </tbody>

            </table>
         </div>

         <footer class="panel-footer">
            <div class="row">
               <div class="col-sm-5">
                  <small class="text-muted inline m-t-sm m-b-sm">
                     <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                  </small>
               </div>
               <div class="col-sm-7 text-right text-center-xs">
                  <?php echo $pagination; ?>
               </div>
            </div>
         </footer>
      </div>
   </div>
</section>