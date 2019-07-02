<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$start_date =($this->input->get("start_date"))?$this->input->get("start_date"):date("d/m/Y", strtotime("first day of this month"));
$end_date = ($this->input->get("end_date"))?$this->input->get("end_date"):date("d/m/Y");

if (($this->session->userdata('kd_group')=='root') || ($this->session->userdata('kd_group')=='SPVH1')){
  $actionapprove = "";
} else {
  $actionapprove = "hidden";
  $dealeruser = $this->session->userdata('kd_dealer');
}

?>

<section class="wrapper">

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      <?php 
      if($this->session->userdata('kd_group') != 'MKT' ){
        ?>
        <a id="modal-button" class="btn btn-default  <?php echo $status_c?>" onclick='addForm("<?php echo base_url('sales_event/create_event'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Baru
        </a>
        <?php
      }
      ?>
      <!-- <a class="btn btn-info" href="<?php echo base_url('sales_event/list_event?p=1&kd_dealer='.$defaultDealer.'&start_date=' .$start_date. '&end_date=' .$end_date); ?> "target="_blank" class="<?php echo $status_p?>">
        <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan sales Event" ></i> Cetak
      </a> -->
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">

      <div class="panel-heading">
        Manage Sales Event
        <span class="tools pull-right">
          <a class="fa fa-chevron-up" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">
        <form id="filterForm" action="<?php echo base_url('sales_event/list_event') ?>" class="bucket-form" method="get">
          <div id="ajax-url" url="<?php echo base_url('sales_event/event_typeahead');?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Nama Dealer</label>
                <select class="form-control" id="kd_dealer" name="kd_dealer">
                  <option value="0">--Pilih Dealer--</option>
                  <?php
                  if ($dealer) {
                    if (($dealer->totaldata > 0)) {
                      foreach ($dealer->message as $key => $value) {
                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                          //$aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                        echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label class="control-label" for="date">Tanggal Awal</label>
                <div class="input-group input-append date">
                  <input class="form-control" name="start_date" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("start_date")) ? $this->input->get("start_date") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
            </div>

            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label class="control-label" for="date">Tanggal Akhir</label>
                <div class="input-group input-append date">
                  <input class="form-control" name="end_date" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("end_date")) ? $this->input->get("end_date") : date('d/m/Y'); ?>" type="text"/>
                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
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
              <th>Aksi</th>
              <th>Dealer</th>
              <th>Kode Event</th>
              <th>Nama Event</th>
              <th>Jenis Event</th>
              <th>Keterangan Event</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>Unit Target</th>
              <th>Revenue Target</th>
              <!-- <th>Assign</th> -->
              <th>Alamat</th>
              <th>Kelurahan</th>
              <th>Kecamatan</th>
              <th>Kab & Propinsi</th>
              <th>Approval</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            <?php
            if (isset($list)) {
              $no = 0;
              if (($list->totaldata >0 )) {
                foreach ($list->message as $key => $value) {
                  # code...
                  $no++;
                  ?>

                  <tr id="<?php echo $this->session->flashdata('tr-active') == $value->ID ? 'tr-active' : ' ';?>" >
                    <td><?php echo $no;?></td>
                    <td class="table-nowarp">
                      <!-- <a id="modal-button" onclick='addForm("<?php echo base_url('sales_event/prepare/'.$value->ID.'/'.$value->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                        <i data-toggle="tooltip" data-placement="left" title="prepare event" class="fa fa-user-secret text-success text-active"></i>
                      </a> --><!-- 

                      <a href="<?php echo base_url('sales_event/detail_display/' . $value->ID); ?>" role="button" class="<?php echo  $status_v ?>">
                      <i data-toggle="tooltip" data-placement="left" title="Unit display" class="fa fa-motorcycle text-success text-active"></i>
                    </a> -->
                    <?php 
                    if($this->session->userdata('kd_group') == 'ADMAL' || $this->session->userdata('kd_group') == 'Root'){
                      ?>
                      <a href="<?php echo base_url('sales_event/detail_people/' . $value->KD_EVENT); ?>" role="button" class="<?php echo  $status_v ?>">
                        <i data-toggle="tooltip" data-placement="left" title="Pic & Sales jaga" class="fa fa-users text-success text-active"></i>
                      </a>
                      <a href="<?php echo base_url('sales_event/detail_display/' . $value->KD_EVENT); ?>" role="button" class="<?php echo  $status_v ?>">
                        <i data-toggle="tooltip" data-placement="left" title="Unit Display" class="fa fa-motorcycle  text-success text-active"></i>
                      </a>
                      <a href="<?php echo base_url('sales_event/detail_budget/' . $value->KD_EVENT); ?>" role="button" class="<?php echo  $status_v ?>">
                        <i data-toggle="tooltip" data-placement="left" title="Budget event" class="fa fa-money text-success text-active"></i>
                      </a>
                      <?php 
                      if($value->APPROVAL_MD != 1){
                        ?>
                        <a id="modal-button" onclick='addForm("<?php echo base_url('sales_event/edit_event/'.$value->ID.'/'.$value->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                          <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                        </a>
                        <?php 
                      }
                      if($value->ROW_STATUS == 0){

                        if($value->APPROVAL_MD != 1){
                          ?>
                          <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('sales_event/delete_event/'.$value->KD_EVENT); ?>">
                            <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                          </a>
                          <?php
                        }
                      }

                    }else{
                      ?>

                      <a href="<?php echo base_url('sales_event/approval_event/' . $value->ID); ?>" role="button">
                        <i data-toggle="tooltip" data-placement="left" title="Approve/ Reject" class="fa fa-eye text-success text-active"></i>
                      </a>
                      <?php
                    }
                    ?>


                  </td>
                  <td><?php echo $value->KD_DEALER;?></td>
                  <td><?php echo $value->KD_EVENT;?></td>
                  <td><?php echo $value->NAMA_EVENT;?></td>
                  <td><?php echo $value->NAMA_JENIS_EVENT;?></td>
                  <td><?php echo $value->KETERANGAN_EVENT;?></td>
                  <td><?php echo tglFromSql($value->START_DATE);?></td>
                  <td><?php echo tglFromSql($value->END_DATE);?></td>
                  <td><?php echo $value->TARGET_UNIT; ?></td>
                  <td><?php echo number_format($value->TARGET_REVENUE,0); ?></td>
                  <!-- <td><?php echo $value->ASSIGN_EVENT;?></td> -->
                  <td><?php echo $value->ALAMAT_EVENT;?></td>
                  <td><?php echo $value->NAMA_DESA;?></td>
                  <td><?php echo $value->NAMA_KECAMATAN;?></td>
                  <td><?php echo $value->NAMA_KABUPATEN;?> , <?php echo $value->NAMA_PROPINSI;?></td>
                  <td><?php if($value->APPROVAL_MD == 0){
                    echo 'Waiting';
                  }elseif($value->APPROVAL_MD == 1){
                    echo 'Approved';
                  } else{
                    echo 'Rejected';
                  }
                  ;?></td>
                  <td><?php echo $value->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                </tr>

                <?php
              }
            }
          }
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