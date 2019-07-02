<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
//$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
//$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <!-- <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('laporan/monitoring_report_print?tanggal=' . $this->input->get("tanggal"). '&kd_dealer'. $this->input->get("kd_dealer") ); ?>");'    role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                    <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Monitoring Report Harian" ></i> Cetak
                </a> -->

                <a class="btn btn-default" onclick='printKw();' role="button">
                    <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Lead Time" ></i> Print Report
                </a>
            </div>

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('laporan/createfile_daily_unit?kd_dealer=' . $this->input->get("kd_dealer") . '&tanggal=' . $this->input->get("tanggal") . '&keyword=' . $this->input->get("keyword")); ?>" role="button">
                    <i class="fa fa-download fa-fw"></i> Download Monitoring Report
                </a>
            </div>

            <div class="btn-group">
                <a role="button" href="<?php echo base_url("pkb/pkb_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List PKB</a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> Monitoring Report
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterFormz" action="<?php echo base_url('laporan/monitoring_report') ?>" class="bucket-form">

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
                            <th>Kode Dealer</th>
                            <th>Kategori</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nomor NJB</th>  
                            <th>Kode Customer</th>
                            <th>Kode Honda</th>
                            <th>No. Polisi</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Kode Pekerjaan / Part Number</th>
                            <th>Keterangan Jasa</th>
                            <!-- <th>Part Number</th> -->
                            <th>Qty</th>
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
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_DEALER; ?></td>
                                        <td><?php echo $value->KATEGORI; ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($value->TANGGAL_PKB); ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_PKB; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_HONDA ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                                        <td class="td-overflow"><?php echo $value->NO_MESIN; ?></td>
                                        <td class="td-overflow"><?php echo $value->NO_RANGKA; ?></td>
                                        <td class="td-overflow"><?php echo $value->KD_PEKERJAAN; ?></td>
                                        <td class="td-overflow"><?php echo $value->KETERANGAN; ?></td>
                                      <!--  <td class="td-overflow"><?php echo $value->PART_NUMBER; ?></td> -->
                                        <td class="text-right"><?php echo $value->QTY; ?></td>                                    
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

        <div id="printarea" style="height: 0.5px; overflow: hidden;width: 100% !important">
            <table style="width: 100%; border-collapse: collapse;" border="0">
                <tr>
                    <td style="width:100%; padding: 5px">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td colspan="5" style="width: 40%" align="center" valign="middle"><h2><strong>Laporan Monitoring Daily</strong></h2></td>
                            </tr>
                            <tr>
                                <td align="center" valign="middle">Tanggal Transaksi : <?php echo($this->input->get("tanggal"))?></td>
                            </tr>
                        </table>

                        <table border="0" id="desc" class="">
                            <tr>
                                <td style="width: 150px">Kode Main Dealer</td>
                                <td style="width: 10px">:</td>
                                <td><?php echo KodeMainDealer($defaulD); ?></td>
                            </tr>

                        <tr>
                            <td>Nama Main Dealer</td>
                            <td>:</td>
                            <td><?php echo NamaMainDealer($defaulD); ?></td>
                        </tr>
    
                        <tr>
                            <td>Nomor AHASS</td>
                            <td>:</td>
                            <td><?php echo KodeDealerAHM($defaulD); ?></td>
                        </tr>

                        <tr>
                            <td>Nama AHASS</td>
                            <td>:</td>
                            <td><?php echo NamaDealer($defaulD); ?></td>
                        </tr>

                        <tr>
                            <td>Kota</td>
                            <td>:</td>
                            <td><?php echo KotaDealer($defaulD); ?></td>
                        </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td valign="top" style="padding: 5px;">
                        <table style="width: 100%; border-collapse: collapse;" border="1">
                        <thead>   
                            <tr>
                                <th>Waktu Transaksi</th>
                                <th>No. NJB</th>  
                                <th>Nama Customer</th>
                                <th>Honda ID</th>
                                <th>No. Polisi</th>
                                <th>No. Mesin</th>
                                <th>No. Rangka</th>
                                <th>Jasa/Part Number</th>
                                <th>Keterangan Jasa</th>
                                <th>Qty</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tbody>
                            <?php
                                if ($list) {
                                $no = 0;
                                    if (is_array($list->message)) {
                                        foreach ($list->message as $key => $value) {
                                         # code...

                                    $no++;
                            ?>

                                            <tr>
                                                <td style="width: 5px;"><?php echo date('H:i:s', strtotime($value->CREATED_TIME)); ?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->NO_PKB; ?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->KD_CUSTOMER; ?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->KD_HONDA ?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->NO_POLISI; ?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->NO_MESIN; ?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->NO_RANGKA; ?></td> 
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->KD_PEKERJAAN;?></td>
                                                <td class="table-nowarp" class="td-overflow-50"><?php echo $value->KETERANGAN; ?></td> 
                                                <td class="text-right" class="td-overflow-50"><?php echo number_format($value->QTY, 0); ?></td> 
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

    <?php echo loading_proses(); ?>

</section>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
    
    function printKw() {
        printJS({ printable: 'printarea', type: 'html', honorColor: true });
    }
</script>