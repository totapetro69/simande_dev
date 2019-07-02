<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
//$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
//$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right">
            
            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/report_lhb_print?tanggal=' . $this->input->get("tanggal"). '&kd_dealer'. $this->input->get("kd_dealer") ); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Harian Bengkel" ></i> Cetak
            </a>
        </div>
        

    </div>

    <div class="col-lg-12 padding-left-right-5 ">

        <div class="panel margin-bottom-5">

            <div class="panel-heading">
                <i class="fa fa-list-ul fa-fw"></i> Laporan Harian Bengkel
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border panel-body-10" style="display: block;">

                <form id="filterForms" method="GET" action="<?php echo base_url("report/report_lhb"); ?>">
                    
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
                                <br>
                                <button id="submit-btn" onclick="addData();" class="btn btn-info" ><i class='fa fa-search'></i> Preview</button>
                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5 ">

        <div class="panel panel-default">

            <div class="table-responsive">

                <table class="table table-hover table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th></th>
                            <th>No. PKB</th>
                            <th>Tipe</th>
                            <th>No. Polisi</th>
                            <th>Nama Customer</th>
                            <th>Mekanik</th>
                            <th>Status</th>
                        </tr>
                    </thead>

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
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('report/report_lhb_detail/' . $value->NO_PKB); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo  $status_v ?>">    
                                                <i data-toggle="tooltip" data-placement="left" title="Detail" class="fa fa-cog text-success text-active"></i>
                                            </a>
                                        </td>
                                        <td class="table-nowarp"><?php echo $value->NO_PKB; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_TIPEPKB; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_COMINGCUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_TYPECOMINGCUSTOMER; ?></td>
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
                            <?php echo (isset($totaldata)) ? ($totaldata == '0' ? "" : "<i>Total Data " . $totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo isset($pagination) ? $pagination : ""; ?>
                    </div>
                </div>
            </footer>

        </div>

    </div>

</section>