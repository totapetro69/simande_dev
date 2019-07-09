<?php if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action');
$status_e = (isBolehAkses('e') ? '' : 'disabled-action');
$status_v = (isBolehAkses('v') ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? '' : 'disabled-action');
?>

<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb(); ?>

    <div class="bar-nav pull-right ">
      <a id="modal-button" class="btn btn-default <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('service_reminder/add_service_reminder'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
        <i class="fa fa-gear fa-fw"></i> Add Service Reminder Schedule
      </a>
      <a id="modal-button" class="btn btn-default <?php echo  $status_c ?>" href="<?= base_url('service_reminder/setupreminder'); ?>">
        <i class="fa fa-gear fa-fw"></i> Setup Reminder
      </a>
    </div>

  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">
      <div class="panel-heading">
        Customer
        <span class="tools pull-right">
          <a class="fa fa-chevron-up" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">
        <form id="filterForm" action="<?php echo base_url('service_reminder/index') ?>" class="bucket-form" method="get">
          <div id="ajax-url" url="<?php echo base_url('customer/customer_typeahead'); ?>"></div>
          <div class="row">
            <div class="col-xs-12 col-sm-8">
              <div class="form-group">Service Reminder Schedule</label>
                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan KPB" autocomplete="off">
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
        <table class="table table-striped table-bordered b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th style="width:45px;">Aksi</th>
              <th>Tgl Service Reminder</th>
              <th>Kode Dealer</th>
              <th>Kode Customer</th>
              <th>Nama Customer</th>
              <th>Kode Tipe Unit</th>
              <th>No Mesin</th>
              <th>Nomor Polisi</th>
              <th>No HP</th>
              <th>Tgl Service Terakhir</th>
              <th>Type Service Terakhir</th>
              <th>Tgl Service Berikutnya</th>
              <th>Type Service Berikutnya</th>
              <th>Status SMS</th>
              <th>Status Call</th>
              <th>Booking Status</th>
              <th>Alasan</th>
              <th>Reschedule</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = $this->input->get('page');
            if ($list) :
              if (is_array($list->message) || is_object($list->message)) :
                foreach ($list->message as $key => $row) :
                  $notifclass = '';
                  $no++;
                  ?>
                  <tr class="<?php echo $notifclass; ?>">
                    <td><?php echo  $no; ?></td>
                    <td class="table-nowarp">

                      <a id="modal-button" onclick='addForm("<?php echo base_url('service_reminder/edit_service_reminder/' . $row->ID . '/' . $row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v ?>">
                        <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                      </a>

                      <a id="delete-btn<?php echo $no; ?>" class="delete-btn <?php echo $status_e ?>" url="<?php echo base_url('service_reminder/service_reminder_hapus/' . $row->ID); ?>">
                        <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                    </td>
                    <td><?php echo  tglfromSql($row->TGL_REMINDER); ?></td>
                    <td><?php echo  $row->KD_DEALER; ?></td>
                    <td><?php echo  $row->KD_CUSTOMER; ?></td>
                    <td><?php echo  $row->NAMA_CUSTOMER; ?></td>
                    <td><?php echo  $row->KD_TYPEMOTOR; ?></td>
                    <td><?php echo  $row->NO_MESIN; ?></td>
                    <td><?php echo  $row->NO_POLISI; ?></td>
                    <td><?php echo  $row->NO_HP; ?></td>
                    <td><?php echo  tglfromSql($row->TGL_LASTSERVICE); ?></td>
                    <td><?php echo  $row->TYPE_LASTSERVICE; ?></td>
                    <td><?php echo  tglfromSql($row->TGL_NEXTSERVICE); ?></td>
                    <td><?php echo  $row->TYPE_NEXTSERVICE; ?></td>
                    <td><?php echo  $row->STATUS_SMS; ?></td>
                    <td><?php echo  $row->STATUS_CALL; ?></td>
                    <td><?php echo  $row->BOOKING_STATUS; ?></td>
                    <td><?php echo  $row->ALASAN; ?></td>
                    <td><?php echo  tglfromSql($row->RESCHEDULE); ?></td>
                  </tr>
                <?php
                endforeach;
              else :
                ?>
                <tr>
                  <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                  <td colspan="14"><b><?php echo ($list->message); ?></b></td>
                </tr>
              <?php
              endif;
            else :
              echo belumAdaData(14);
            endif;
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