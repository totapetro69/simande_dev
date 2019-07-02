<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('reminder_booking/add_service_booking'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-gear fa-fw"></i> Service Booking
            </a>
            <!-- <a id="modal-button" class="btn btn-default" href="<?php echo base_url('customer/add_customer'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Tambah Customer
            </a> -->
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

                <form id="filterForm" action="<?php echo base_url('reminder_booking/service_booking') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('customer/customer_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Kode Customer, no polisi, no trans" autocomplete="off">
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
                            <th>Nomor Transaksi</th>
                            <th>Nama Customer</th>
                            <th>No. Telepon</th>
                            <th>No. Polisi</th>
                            <th>Keluhan Cust</th>
                            <th>Waktu Servis</th>
                            <th>Status Booking</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                            
                                foreach ($list->message as $key => $row):
                                    $notifclass = '';

                                    // $next_fu = $row->NEXT_FU == 0 ? $status_e :'disabled-action';
                                    
                                    /*if(tglToSql(tglfromSql($row->TGL_TERIMA)) <= tglToSql(date('d/m/Y')) && tglToSql(tglfromSql(getNextDays($row->TGL_TERIMA,5))) >= tglToSql(date('d/m/Y')) ){

                                        $notifclass = 'info';
                                    }elseif(tglToSql(tglfromSql(getNextDays($row->TGL_TERIMA,5))) < tglToSql(date('d/m/Y')) ){
                                        $notifclass = 'danger';
                                    }*/
                                    $no ++;
                                    ?>

                                    <tr class="<?php echo $notifclass;?>">
                                        <td><?php echo  $no; ?></td>
                                        <td class="table-nowarp">
                                            <?php if ($row->STATUS_BOOKING == 1): ?>
                                                <a id="delete-btn<?php echo $no;?>" class="<?php echo $status_e?>" url="<?php echo base_url('reminder_booking/service_booking_cancel/'.$row->ID); ?>" onclick="confirm('Apakah anda yakin ingin membatalkan data booking ini ??')">
                                                  <i data-toggle="tooltip" data-placement="left" title="Batal Booking" class="fa fa-remove text-danger text"></i>    
                                            <?php else: ?>
                                                <a class="active" id="modal-button" onclick='addForm("<?php echo base_url('reminder_booking/add_service_booking?no_trans='.$row->NO_TRANS); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                    <i class='fa fa-edit' data-toggle="tooltip" data-placement="left" title="Edit" ></i>
                                                </a>                                            
                                                <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_e?>" url="<?php echo base_url('reminder_booking/service_booking_hapus/'.$row->ID); ?>" >
                                                  <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                                </a>                                                
                                            <?php endif ?>                                            
                                        </td>
                                        <td><?php echo  $row->NO_TRANS; ?></td>
                                        <td><?php echo  $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo  $row->NO_TELEPON; ?></td>
                                        <td><?php echo  $row->NO_POLISI; ?></td>
                                        <td><?php echo  $row->KELUHAN_CUST; ?></td>
                                        <td><?php echo  tglfromSql($row->WAKTU_SERVIS).' '.substr($row->WAKTU_SERVIS,11,5); ?></td>
                                        <td>
                                            <?php if ($row->STATUS_BOOKING == 1): ?>
                                                CLOSED
                                            <?php else: ?>
                                                OPEN
                                            <?php endif ?>
                                        </td>
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