<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
 
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$pilih = $this->input->get('pilih');

$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
 
        <div class="bar-nav pull-right ">
 
            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('laporan/succes_rate_print?pilih=' . $this->input->get("pilih") . '&tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir") . '&keyword=' . $this->input->get("keyword")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Success Rate" ></i> Cetak
            </a>
 
 
        </div>
    </div>
 
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Laporan Succes Rate
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
 
            <div class="panel-body panel-body-border" style="display: block;">
 
                <form id="filterFormz" action="<?php echo base_url('laporan/succes_rate') ?>" class="bucket-form" method="get">
 
                    <!-- <div id="ajax-url" url="<?php echo base_url('report/sales_typeahead'); ?>"></div> -->
 
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
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Group By</label>
                                <select name="pilih" class="form-control">
                                    <option value="0" <?php echo ($pilih == 0 ? "selected" : ""); ?>>Semua</option>
                                    <option value="1" <?php echo ($pilih == 1 ? "selected" : ""); ?>>Kategori Motor</option>
                                    <option value="2" <?php echo ($pilih == 2 ? "selected" : ""); ?>>Sales</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Kata Kunci</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan kata kunci sesuai group by yang dipilih" autocomplete="off">
                            </div>
                        </div>
                    </div>
 
                    <div class="row">
                        <div class="col-xs-12">
                            <button id="submit-btn" onclick="addData();" class="btn btn-primary pull-right"><i class='fa fa-search'></i> Preview</button>
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
                            <th>No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>No. SPK</th>
                            <th>Tipe Motor</th>
                            <th>Keterangan Warna</th>
                            <th>Nama Sales</th>
                            <th>Tanggal Berkunjung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        echo $html;
                        ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
 
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($totaldata == '') ? "" : "<i>Total Data " . $totaldata . " items</i>"; ?>
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
