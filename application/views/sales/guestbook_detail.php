<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->session->userdata("kd_dealer"));
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>

<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
   <?php echo breadcrumb();?>

   <div class="bar-nav pull-right ">

    <a id="modal-button" class="btn btn-default>
      <i class="fa fa-file-o fa-fw"></i>Baru
    </a>
    <a role="button" href="<?php echo base_url("customer/guest_book"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List Guest Book</a>
  </div>
</div>


<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Detail Guestbook
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

      <table class="table table-striped b-t b-light">
        <tr>
          <td>No Guest</td>
          <td>: <?php echo $cek->message[0]->GUEST_NO; ?></td>
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
                            <!-- <th>ID Guest</th> -->
                            <!-- <th>No Guest</th> -->
                            <th>Source</th>
                            <th>Rencana Bayar</th>
                            <!-- <th>Follow Up</th> -->
                            <!-- <th>Rencana FU</th> -->
                            <th>Metode FU</th>
                            <th>Tgl FU</th>
                            <th>Status FU</th>
                            <th>Hasil Metode</th>
                            <th>Keterangan Status</th>
                            <th>Klasifikasi Status</th>
                            <th>Status Customer</th>
                            <th>Tgl Next FU</th>
                            <th>Keterangan NODEAL</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($list) {
                            $no = 0;
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $value) {
                                    # code...
                                    $hpus=($value->GUEST_ID=='')?'':'hidden';
                                    $no++;
                                    ?>

                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <!-- <a id="modal-button"  class="<?php echo  $status_v ?>"  href='<?php echo base_url() . "customer/guestbook_edit?n=" . $value->GUEST_NO; ?>')" role="button" >
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah Data customer" class="fa fa-edit fa-fw"></i>
                                            </a> -->
                                            <a id="delete-btn" class="delete-btn <?php echo $hpus;?> <?php echo  $status_e ?>" url="<?php echo base_url('customer/guestbook_detail_delete/' . $value->GUEST_NO); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus data guest no:<?php echo $value->GUEST_NO; ?>" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <!-- <td class="table-nowarp"><?php echo $value->GUEST_ID; ?></td> -->
                                        <!-- <td class="table-nowarp""><?php echo $value->GUEST_NO; ?></td> -->
                                        <td class="table-nowarp"><?php echo $value->GUEST_SOURCE; ?></td>
                                        <td class="table-nowarp"><?php echo $value->RENCANA_BAYAR; ?></td>
                                        <!-- <td class='table-nowarp'><?php echo $value->FOLLOW_UP; ?></td> -->
                                        <!-- <td class="table-nowarp"><?php echo $value->RENCANA_FU; ?></td> -->
                                        <td class="table-nowarp"><?php echo $value->METODE_FU; ?></td>
                                        <td class="table-nowarp"><?php echo $value->TGL_FU; ?></td>
                                        <td class="table-nowarp"><?php echo $value->STATUS_FU; ?></td>
                                        <td class="table-nowarp"><?php echo $value->HASIL_METODE; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KET_STATUSFU; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KLAS_STATUSFU; ?></td>
                                        <td class="table-nowarp"><?php echo $value->STATUS_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->TGL_NEXTFU; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KET_NODEAL; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                belumAdaData();
                            }
                        } else {
                            belumAdaData();
                        }
                        ?>
                    </tbody>

                </table>

            </div>

        </div>

        <div class="panel-footer">

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

        </div>

    </div>
    <?php echo loading_proses(); ?>
</section>