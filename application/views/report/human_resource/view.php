<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
/*$bulan=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");*/
?>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a class="btn btn-default " id="modal-button" onclick='addForm("<?php echo base_url('laporan/human_resource_print?bulan=' . $this->input->get("bulan") . '&kd_dealer=' . $this->input->get("kd_dealer") . '&tahun=' . $this->input->get("tahun")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Human Resource Report" ></i> Cetak
            </a>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10 ">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <MAIN>Human Resource Report</MAIN>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" >
                <form id="filterFormz" action="<?php echo base_url('laporan/human_resource') ?>" class="bucket-form" method="get">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-5">
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

            <div class="table-responsive">
            <table class="table table-stripped table-hover table-bordered" style="font-size: 12px">
            
            <thead>

                <tr>
                    <th style="width:40px;" >No.</th>
                    <th>Honda id</th>
                    <th>Nama Karyawan</th>
                    <th>Tanggal Lahir</th>
                    <th>Pendidikan Terakhir</th>
                    <th>Tanggal Masuk</th>
                    <th>Jabatan</th>
                    <th>Status Training</th>
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
                    <td><?php echo $row->NAMA; ?></td>
                    <td><?php echo tglfromSql($row->TGL_LAHIR);?></td>
                    <td><?php echo $row->PENDIDIKAN; ?></td>
                    <td><?php echo tglfromSql($row->TGL_MASUK);?></td>
                    <td><?php echo $row->PERSONAL_JABATAN; ?></td>
                    <td><?php echo $row->KD_STATUS; ?></td>
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