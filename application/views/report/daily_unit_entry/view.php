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
                <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('laporan/createfile_daily_unit?kd_dealer=' . $this->input->get("kd_dealer") . '&tanggal_pkb=' . $this->input->get("tanggal_pkb") . '&keyword=' . $this->input->get("keyword")); ?>" role="button">
                    <i class="fa fa-download fa-fw"></i> Download Daily Unit Entry
                </a>
            </div>
            <div class="btn-group">
                <a role="button" href="<?php echo base_url("pkb/pkb_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List PKB</a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> Daily Unit Entry
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterFormz" action="<?php echo base_url('laporan/daily_unit') ?>" class="bucket-form">

                    <div class="row">

                        <div class="col-xs-12 col-sm-5">

                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n; ?>>
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                        if (is_array($dealer->message)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
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
                                    <input class="form-control" name="tanggal_pkb" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tanggal_pkb")) ? $this->input->get("tanggal_pkb") : date('d/m/Y'); ?>" type="text"/>
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
                            <th>ID</th>
                            <th>Kode Dealer</th>
                           <!--  <th>Kategori</th> -->
                            <th>Tanggal Transaksi</-th>
                            <th>NO. NJB</th>  
                            <!-- <th>Nama Pemilik</th> -->
                            <th>Nomor HP</th>
                            <!-- <th>Nama Pembawa</th>
                            <th>Nomor Telpon Pembawa</th>
                             <th>Nama Pemakai</th>
                            <th>Nomor Telpon Pemakai</th>
                            <th>Honda ID</th> -->
                            <th>No. Polisi</th>
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th>Tipe PKB</th>
                            <th>Kode Pekerjaan</th>
                            <th>Keterangan</th>
                            <th>Part Number</th>
                            <th>Qty</th>
                            <!-- <th>Id Promo</th> -->
                           <!--  <th>Keterangan</th> -->
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

                                    <!-- <tr>
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp"><?php echo $value->ID; ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($value->TANGGAL_PKB); ?></td>
                                        <td class="table-nowarp"><?php echo $value->KATEGORI; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_DEALER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_SA; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_PEMILIK; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_HP; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_COMINGCUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_TELEPON; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_TELEPON;?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_HONDA ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                                        <td class="td-overflow"><?php echo $value->NO_MESIN; ?></td>
                                        <td class="td-overflow"><?php echo $value->NO_RANGKA; ?></td>
                                        <td class="td-overflow"><?php echo $value->JENIS_KPB; ?></td>
                                        <td class="td-overflow"><?php echo $value->KD_PEKERJAAN; ?></td>
                                        <td class="td-overflow"><?php echo $value->KETERANGAN; ?></td>
                                        <td class="table-nowarp"><?php echo $value->QTY; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_DETAILPROMO; ?></td>
                                         <td class="table-nowarp"><?php echo $value->KETERANGAN; ?></td> -
                                    </tr> -->

                                     <tr>
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp"><?php echo $value->ID; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_DEALER; ?></td>
                                        <!-- <td class="table-nowarp"><?php echo $value->KATEGORI; ?></td> -->
                                        <td class="table-nowarp"><?php echo tglfromSql($value->TANGGAL_PKB); ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_PKB;?></td>
                                       <!--  <td class="table-nowarp"><?php echo $value->NAMA_PEMILIK; ?></td> -->
                                        <td class="table-nowarp"><?php echo $value->NO_HP; ?></td>
                                       <!--  <td class="table-nowarp"><?php echo $value->NAMA_PEMILIK; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_HP; ?></td> -->
                                        <!-- <td class="table-nowarp"><?php echo $value->NAMA_COMINGCUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_TELEPON; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_TELEPON;?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_HONDA ?></td>-->
                                        <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                                        <td class="td-overflow"><?php echo $value->NO_MESIN; ?></td>
                                        <td class="td-overflow"><?php echo $value->NO_RANGKA; ?></td>
                                        <td class="td-overflow"><?php echo $value->JENIS_KPB; ?></td>
                                        <td class="td-overflow"><?php echo $value->KD_PEKERJAAN; ?></td>
                                        <td class="td-overflow"><?php echo $value->KETERANGAN; ?></td>
                                        <td class="td-overflow"><?php echo $value->PART_NUMBER; ?></td>
                                        <td class="text-right"><?php echo $value->QTY; ?></td> 
                                        <!-- <td class="td-overflow"><?php echo $value->KETERANGAN; ?></td> -->
                                        <!-- <td class="table-nowarp"><?php echo $value->KD_DETAILPROMO; ?></td>
                                         --><!-- <td class="table-nowarp"><?php echo $value->KETERANGAN; ?></td > -->
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