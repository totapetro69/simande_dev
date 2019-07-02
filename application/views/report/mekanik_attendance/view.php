<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$start_date= ($this->input->get('start_date'))?$this->input->get('start_date'):date("Ymd");
$end_date= ($this->input->get("end_date"))?$this->input->get("end_date"):date('Ymd');
/*$tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");*/
/*$bulan=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");*/
?>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right">
            <div class="btn-group">
                <a class="btn btn-default "id="modal-button" onclick='addForm("<?php echo base_url('laporan/mekanik_attendance_print?kd_dealer=' . $this->input->get("kd_dealer") . '&start_date=' . $this->input->get("start_date") . '&keyword=' . '&end_date=' . $this->input->get("end_date")); ?>");'  role="button" data-toggle="modal" data-target=" #myModalLg" data-backdrop="static">
                    <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Kehadiran Mekanik" ></i> Cetak
                </a>
            </div>

            <div class="btn-group">
                <a role="button" href="<?php echo base_url("master_service/absensi_mekanik"); ?>" class="btn btn-default <?php echo $status_v;?>"><i class="fa fa-list-ul"></i> List Absensi Mekanik</a>
            </div>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10 ">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <MAIN>Mekanik Attendance</MAIN> 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" >
                <form id="filterFormz" action="<?php echo base_url('laporan/mekanik_attendance') ?>" class="bucket-form" method="get">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4">
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

                        <div class="col-xs-12 col-sm-3 col-md-3">
                          <div class="form-group">
                            <label class="control-label" for="date">Periode Awal</label>
                            <div class="input-group input-append date">
                              <input class="form-control" name="start_date" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("start_date")) ? $this->input->get("start_date") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-3">
                          <div class="form-group">
                            <label class="control-label" for="date">Periode Akhir</label>
                            <div class="input-group input-append date">
                              <input class="form-control" name="end_date" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("end_date")) ? $this->input->get("end_date") : date('d/m/Y'); ?>" type="text"/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-xs-3 col-md-1 col-sm-1">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-20">

        <div class="panel panel-default">

            <div class="table-responsive">
            <table class="table table-stripped table-hover table-bordered" style="font-size: 12px">
            
            <thead>

                <tr>
                    <th style="width:40px;" >No.</th>
                    <th>Honda ID</th>
                    <th>NIK</th>
                    <th>Nama Mekanik</th>
                    <th>Jabatan</th>
                   <!--  <th>Status</th> -->
                    <th>Jumlah Kehadiran (Hari)</th>
                    <th>Jumlah Ketidakhadiran (Hari)</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $no = $this->input->get('page');
                    if (isset($list)):
                    if ($list->totaldata>0):
                        foreach ($list->message as $key => $row):
                        $no ++;
                ?>  
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $row->HONDA_ID; ?></td>
                    <td><?php echo $row->NIK; ?></td>
                    <td><?php echo $row->NAMA; ?></td>
                    <td><?php echo $row->PERSONAL_JABATAN; ?></td>
                    <td align="center"><?php echo number_format($row->KEHADIRAN,0);?></td>
                    <td align="center"><?php echo number_format($row->KETIDAKHADIRAN,0);?></td>
                    
                </tr>   
               <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="17"><b><?php echo ($list->message); ?></b></td>
                    </tr>
                <?php
                endif;
            else:
                echo belumAdaData(17);
            endif;
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