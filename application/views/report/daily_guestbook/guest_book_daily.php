<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('report/createfile_daily_udcp?kd_dealer=' . $this->input->get("kd_dealer") . '&tanggal=' . $this->input->get("tanggal") . '&keyword=' . $this->input->get("keyword")); ?>" role="button">
                    <i class="fa fa-download fa-fw"></i> Download Daily Guest Book
                </a>
            </div>
            <div class="btn-group">
                <a role="button" href="<?php echo base_url("customer/guest_book"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List Guest Book</a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> Guest Book
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterFormz" action="<?php echo base_url('report/guest_book_daily') ?>" class="bucket-form">

                    <div class="row">

                        <div class="col-xs-12 col-sm-5">

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

                        <div class="col-xs-12 col-sm-3 col-sm-push-2">

                            <div class="form-group">


                                <label class="control-label" for="date">Tanggal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tanggal" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tanggal")) ? $this->input->get("tanggal") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                                </div>

                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2 col-sm-push-2">

                            <div class="form-group">

                                <label> </label>
                                <button id="submit-btn" onclick="addData();" class="btn btn-primary" style="width:100%"><i class='fa fa-search'></i> Preview</button>

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
                            <th>ID Guest Book</th>
                            <th>ID Customer</th>
                            <th>ID Dealer</th>
                            <th>ID Honda</th>
                            <th>Metode FU</th>
                            <th>Jenis Customer</th>
                            <th>Setup Pembayaran</th>
                            <th>ID Hasil</th>
                            <th>Ket. Not Deal</th>
                            <th>Tanggal</th>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Warna Motor</th>
                            <th>Tipe Motor</th>
                            <th>Hasil</th>
                            <th>Rencana FU</th>
                            <th>Keterangan</th>
                            <th>Nama Sales</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($list) {
                            $no = 0;
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $value) {
                                    # code...
                                    if ($value->CARA_BAYAR = "CASH") {
                                        $cara = '(T) CASH';
                                    } elseif ($value->CARA_BAYAR = "CREDIT") {
                                        $cara = 'K CREDIT';
                                    } elseif ($value->CARA_BAYAR = "BILYET") {
                                        $cara = 'B BILYET';
                                    } else {
                                        $cara = '';
                                    }
                                    $no++;
                                    ?>

                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp"><?php echo $value->ID; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_DEALER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_HSALES; ?></td>
                                        <td class="table-nowarp"><?php echo '('.$value->METODE_FU.')' ?></td>
            <?php if ($value->TYPECUSTOMER > 0) { ?>
                                            <td>RO</td>
            <?php } else { ?>
                                            <td>Baru</td>
                                            <?php
                                        }
                                        ?>
                                        <td class="td-overflow"><?php echo $cara; ?></td>
                                        <td class="td-overflow"><?php echo $value->STATUS; ?></td>
                                        <td title="<?php echo $value->KETERANGAN; ?>"><?php echo $value->KETERANGAN; ?></td>
                                        <td class="table-nowarp"><?php echo $value->TANGGAL; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_CUSTOMER; ?></td>
                                        <td class="td-overflow" title="<?php echo $value->ALAMAT; ?>"><?php echo $value->ALAMAT; ?></td>
                                        <td><?php echo $value->NO_HP; ?></td>
                                        <td><?php echo $value->KD_WARNA; ?></td>
                                        <td><?php echo $value->KD_TYPEMOTOR; ?></td>
                                        <td class="table-nowarp" title="<?php echo $value->ALASAN; ?>"><?php echo $value->ALASAN; ?></td>
                                        <td><?php echo $value->RENCANA_FU; ?></td>
                                        <td class="table-nowarp" title="<?php echo $value->KET_NODEAL; ?>"><?php echo $value->KET_NODEAL; ?></td>
                                        <td><?php echo $value->NAMA_SALES; ?></td>
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