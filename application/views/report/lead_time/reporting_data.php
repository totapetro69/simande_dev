<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$tipe=($this->input->get("tp"))?$this->input->get("tp"):"0";
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
//$periodebln=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");
$tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
$bulan=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");

//$periodebln=nBulan($periodebln)." ".$tahun;
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
    </div>
    
    <div class="col-lg-12 padding-left-right-10 ">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Reporting Data Lead Time
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" >
                <form id="filterFormz" action="<?php echo base_url('laporan/reporting_data') ?>" class="bucket-form" method="get">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 col-md-3">
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
                        <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan</option>
                                    <?php 
                                        for($i=1;$i<=12; $i++){
                                            $pilih=(date("m")==$i)?"selected":"";
                                            $pilih=((int)$this->input->get("bulan")==$i)?"selected":"";
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">--Pilih Tahun</option>
                                    <?php 
                                        if(isset($tahun)){
                                            if($tahun->totaldata>0){
                                                foreach ($tahun->message as $key => $value) {
                                                    $pilih=(date("Y")==$value->TAHUN)?"selected":"";
                                                    $pilih=($this->input->get("tahun")==$value->TAHUN)?"selected":$pilih;
                                                    echo "<option value='".$value->TAHUN."' $pilih>".$value->TAHUN."</option>";
                                                }
                                            }else{
                                                echo "<option value='".date("Y")."' selected>".date("Y")."</option>";
                                            }
                                        }
                                    ?>
                                </select>
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

            <div class="table-responsive h350">
            <table class="table table-stripped table-hover table-bordered" style="font-size: 12px">
            
            <thead>

                <tr>
                    <th class="text-center" rowspan="3" style="width:40px;" >NO</th>
                    <th class="text-center" colspan="9">SPK</th>
                    <th class="text-center" colspan="3">Pengurusan Faktur</th>
                    <th class="text-center" colspan="6">Unit Delivery</th>
                </tr>
                
                <tr>
                    <th class="text-center" rowspan="2">No SPK</th>
                    <th class="text-center" rowspan="2">Tgl SPK</th>
                    <th class="text-center" colspan="2">APP Fincoy</th>
                    <th class="text-center" colspan="2">SO</th>
                    <th class="text-center" colspan="3">Delivery</th>
                    <th class="text-center" rowspan="2">Tgl Pengajuan</th>
                    <th class="text-center" rowspan="2">Tgl Penerimaan</th>
                    <th class="text-center" rowspan="2">Lead Time</th>
                    <th class="text-center" colspan="3">STNK</th>
                    <th class="text-center" colspan="3">BPKB</th>
                </tr>
                
                <tr>
                    <!-- fincoy -->
                    <th>Tgl</th>
                    <th>Lead Time</th>
                    <!-- so -->
                    <th>Tgl</th>
                    <th>Lead Time</th>
                    <!-- dlivery -->
                    <th>Tgl Estimasi</th>
                    <th>Tgl Actual</th>
                    <th>Lead Time</th>
                    <!-- unit delivery -->
                    <th>Tgl Estimasi</th>
                    <th>Tgl Actual</th>
                    <th>Lead Time</th>
                    <th>Tgl Estimasi</th>
                    <th>Tgl Actual</th>
                    <th>Lead Time</th>
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
                                        <td class="table-nowarp"><?php echo ($row->NO_SPK); ?></td>
                                        <td><?php echo tglFromSql($row->TGL_SPK); ?></td>
                                        <td><?php echo tglFromSql($row->TGL_APPROVEFIN); ?></td>
                                        <td><?php echo $row->LT_FIN; ?></td>
                                        <td><?php echo tglFromSql($row->TGL_SO); ?></td>
                                        <td><?php echo $row->LT_SO; ?></td>
                                        <td><?php echo tglFromSql($row->TGL_ESTIMASI); ?></td>
                                        <td><?php echo tglFromSql($row->TGL_KIRIM); ?></td>
                                        <td><?php echo $row->LT_UD; ?></td>
                                        <td><?php echo tglFromSql($row->TGLMULAI_PENGURUSAN); ?></td>
                                        <td><?php echo tglFromSql($row->TGLSELESAI_PENGURUSAN); ?></td>
                                        <td><?php echo $row->LT_FK; ?></td>
                                        <td><?php echo tglFromSql($row->STNK_ESTIMASI); ?></td>
                                        <td><?php echo tglFromSql($row->STNK_SERAH); ?></td>
                                        <td><?php echo $row->STNK_LEAD; ?></td>
                                        <td><?php echo tglFromSql($row->BPKB_ESTIMASI); ?></td>
                                        <td><?php echo tglFromSql($row->BPKB_SERAH); ?></td>
                                        <td><?php echo $row->BPKB_LEAD; ?></td>
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