<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_detail = "";//($list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('report/createfile_sr?kd_dealer=' . $this->input->get("kd_dealer") . '&tgl_awal=' . $this->input->get("tgl_awal") . '&keyword=' . '&tgl_akhir=' . $this->input->get("tgl_akhir") . $this->input->get("keyword")); ?>" role="button">
                    <i class="fa fa-download fa-fw"></i> Download .SFU
                </a>
            </div>
            <div class="btn-group">
                <a role="button" href="<?php echo base_url("follow_up/service_reminder_booking"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List Service Reminder</a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> 
                Service Reminder
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterFormz" action="<?php echo base_url('report/service_reminder') ?>" class="bucket-form">

                    <div class="row">

                        <div class="col-xs-12 col-sm-4 ">

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

                       <div class="col-xs-6 col-sm-3 ">
                            <div class="form-group">
                                <label>Periode</label>

                                <div class="input-group input-append date" id="datepicker">
                                  <input id="tgl_awal" type="text" name="tgl_awal" class="form-control" value="<?php echo $this->input->get('tgl_awal'); ?>" placeholder="DD/MM/YYYY">
                                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>
                        </div>


                        <div class="col-xs-6 col-sm-3 ">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <input id="periode" type="text" name="periode" class="form-control disabled-action" value="<?php echo $date['start_date'].' - '.$date['end_date']; ?>" placeholder="DD/MM/YYYY" style="text-align: center;background: #ffc107;color: white;font-weight: 800;">


                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-2 ">

                            <div class="form-group">
                                <label>&nbsp;</label>
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

                <table class="table table-striped table-bordered b-t b-light">

                    <thead>
                        <tr>
                            <th rowspan="2" style="width:40px;">No.</th>
                            <th rowspan="2">No. Rangka</th>
                            <th rowspan="2">Type KPB</th>
                            <th rowspan="2">Kode Customer</th>
                            <th rowspan="2">Nama Customer</th>
                            <th rowspan="2">Propinsi</th>
                            <th rowspan="2">No. Hp</th>
                            <th colspan="2">Metode 1</th>
                            <th colspan="2">Metode 2</th>
                            <th rowspan="2">Tgl. Terima</th>
                            <th rowspan="2">No. SJ</th>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <th>Hasil</th>
                            <th>Status</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):

                                foreach ($list->message as $key => $row):
                                    $notifclass = '';

                                    $next_fu = $row->NEXT_FU == 0 ? $status_e : 'disabled-action';

                                    /* if(tglToSql(tglfromSql($row->TGL_TERIMA)) <= tglToSql(date('d/m/Y')) && tglToSql(tglfromSql(getNextDays($row->TGL_TERIMA,5))) >= tglToSql(date('d/m/Y')) ){

                                      $notifclass = 'info';
                                      }elseif(tglToSql(tglfromSql(getNextDays($row->TGL_TERIMA,5))) < tglToSql(date('d/m/Y')) ){
                                      $notifclass = 'danger';
                                      } */
                                    $no ++;
                                    ?>

                                    <tr class="<?php echo $notifclass; ?>">
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row->NO_RANGKA; ?></td>
                                        <td><?php echo $row->JENIS_KPB; ?></td>
                                        <td><?php echo $row->KD_CUSTOMER; ?></td>
                                        <td><?php echo $row->NAMA_STNK; ?></td>
                                        <td><?php echo $row->PROPINSI_SURAT; ?></td>
                                        <td><?php echo $row->NO_HP; ?></td>
                                        <td><?php echo $row->STATUS_METODEFU; ?></td>
                                        <td><?php echo $row->HASIL_METODEFU; ?></td>
                                        <td><?php echo $row->STATUS_METODEFU2; ?></td>
                                        <td><?php echo $row->HASIL_METODEFU2; ?></td>

                                        <td><?php echo tglfromSql($row->TGL_TERIMA); ?></td>
                                        <td><?php echo $row->NO_SURATJALAN; ?></td>
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
    <?php echo loading_proses(); ?>
</section>