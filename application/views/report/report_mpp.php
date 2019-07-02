<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$dari_tgl = ($this->input->get("tgl_trans")) ? $this->input->get("tgl_trans") : date("d/m/Y", strtotime("-1 Days"));
$no_trx = $this->input->get("n");
$disable = ($no_trx) ? "" : "disabled-action";
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$bulan = ($this->input->get('bulan')) ? $this->input->get('bulan') : date("m");
$tahuns = ($this->input->get("tahun")) ? $this->input->get("tahun") : date('Y');

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
//$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
//$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right">
            <a class="btn btn-default " id="modal-button" onclick='addForm("<?php echo base_url('report/report_mpp_print?bulan=' . $this->input->get("bulan") . '&kd_dealer=' . $this->input->get("kd_dealer") . '&tahun=' . $this->input->get("tahun")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Mekanik Performance Parameter" ></i> Cetak
            </a>
        </div>

    </div>

    <fieldset class="">
        <div class="col-lg-12 padding-left-right-10" style="display: block;">

            <div class="panel margin-bottom-5">
                <div class="panel-heading">
                    <i class="fa fa-list fa-fw"></i> Laporan Mekanik Performance Parameter
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>

                <div class="panel-body panel-body-border">

                    <form id="frmAdd" method="get" action="<?php echo base_url("report/report_mpp"); ?>">

                        <div class="row">

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Dealer</label>
                                    <select id="kd_dealer" name="kd_dealer" class="form-control">
                                        <option value="0">--Pilih Dealer--</option>
                                        
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata > 0) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                                $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Periode Bulan</label>
                                    <select id="bulan" name="bulan" class="form-control">
                                        <option value="">--Pilih Bulan--</option>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            $pilih = ($bulan == $i) ? "selected" : "";
                                            echo "<option value='" . $i . "' " . $pilih . ">" . nBulan($i) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-3 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select id="tahun" name="tahun" class="form-control">
                                        <option value="">--Pilih Tahun--</option>
                                        <?php
                                        if (isset($tahun)) {
                                            if ($tahun->totaldata > 0) {
                                                foreach ($tahun->message as $key => $value) {
                                                    $pilih = ($tahuns == $value->TAHUN) ? "selected" : "";
                                                    echo "<option value='" . $value->TAHUN . "' " . $pilih . ">" . $value->TAHUN . "</option>";
                                                }
                                            } else {
                                                echo "<option value='" . date('Y') . "' selected>" . date('Y') . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-3 col-sm-1 col-md-1">
                                <div class="form-group">
                                    <br>
                                    <button id="submit-btn" class="btn btn-info" ><i class='fa fa-search'></i> Preview</button>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-lg-12 padding-left-right-10" id="">
            <div>

                <div class="table-responsive panel margin-bottom-5">

                    <table class="table table-stripped table-hover table-bordered" style="width:100% !important">
                        <thead style="background-color: #FFC140 !important">

                            <tr style="text-align: center !important;">
                                <th colspan="2" style="width:5% !important;" class="text-center"></th>
                                <th colspan="11" style="width:5% !important;" class="text-center">Type</th>
                            </tr>
                            <tr style="text-align: center !important;">
                                <th style="width:15% !important;" class="text-center">ID Emp</th>
                                <th style="width:30% !important;" class="text-center">Nama</th>
                                <th style="width:5% !important;" class="text-center">Data</th>
                                <th style="width:5% !important;" class="text-center">ASS</th>
                                <th style="width:5% !important;" class="text-center">Claim</th>
                                <th style="width:5% !important;" class="text-center">HR</th>
                                <th style="width:5% !important;" class="text-center">JR</th>
                                <th style="width:5% !important;" class="text-center">LR</th>
                                <th style="width:5% !important;" class="text-center">PDI</th>
                                <th style="width:5% !important;" class="text-center">CS</th>
                                <th style="width:5% !important;" class="text-center">LS</th>
                                <th style="width:5% !important;" class="text-center">GOP</th>
                                <th style="width:8% !important; white-space: nowrap;" class="text-center">Grand Total</th>
                            </tr>

                        </thead>

                        <tbody>
                            <?php
                            $n = 0;
                            if (isset($mekanik)):
                                if ($mekanik->totaldata > 0):
                                    // $GTside = 0;

                                    $GTASS = 0; $GTCC2 = 0; $GTHR = 0; $GTJR = 0; $GTLR = 0; $GTCS = 0; $GTLS = 0;

                                    $GTPG_ASS = 0; $GTPG_CC2 = 0; $GTPG_HR = 0; $GTPG_JR = 0; $GTPG_LR = 0; $GTPG_CS = 0; $GTPG_LS = 0;
                                    
                                    foreach ($mekanik->message as $key => $value) {
                                        $GTside = array_sum([$value->ASS,$value->CC2,$value->HR,$value->JR,$value->LR /*+ $value->PDI*/,$value->CS,$value->LS]) /*+ $value->GOP*/;
                                        $PG_GTside = array_sum([$value->PG_ASS,$value->PG_CC2,$value->PG_HR,$value->PG_JR,$value->PG_LR /*+ $value->PG_PDI*/,$value->PG_CS,$value->PG_LS]) /*+ $value->GOP*/;


                                        $GTASS = $GTASS + $value->ASS; $GTCC2 = $GTCC2 + $value->CC2; $GTHR = $GTHR +  $value->HR; $GTJR = $GTJR +  $value->JR; $GTLR = $GTLR +  $value->LR; $GTCS = $GTCS +  $value->CS; $GTLS = $GTLS +  $value->LS;
 
                                        $GTPG_ASS = $GTPG_ASS + $value->PG_ASS; $GTPG_CC2 = $GTPG_CC2 + $value->PG_CC2; $GTPG_HR = $GTPG_HR + $value->PG_HR; $GTPG_JR = $GTPG_JR + $value->PG_JR; $GTPG_LR = $GTPG_LR + $value->PG_LR; $GTPG_CS = $GTPG_CS + $value->PG_CS; $GTPG_LS = $GTPG_LS + $value->PG_LS;




                                        ?>
                                        <tr>
                                            <td rowspan="2" nowrap="nowrap" class='text-left'><?php echo $value->NIK; ?></td>
                                            <td rowspan="2" nowrap="nowrap" class="td-overflow" style="white-space: nowrap;"><?php echo $value->NAMA_MEKANIK; ?></td>
                                            <td class="text-left">Unit</td>
                                            <td class="text-right"><?php echo $value->ASS; ?>  </td>
                                            <td class="text-right"><?php echo $value->CC2; ?>  </td>
                                            <td class="text-right"><?php echo $value->HR; ?>  </td>
                                            <td class="text-right"><?php echo $value->JR; ?>  </td>
                                            <td class="text-right"><?php echo $value->LR; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo $value->CS; ?>  </td>
                                            <td class="text-right"><?php echo $value->LS; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo $GTside; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Waktu</td> <!-- data -->
                                            <td class="text-right"><?php echo $value->PG_ASS; ?>  </td>
                                            <td class="text-right"><?php echo $value->PG_CC2; ?>  </td>
                                            <td class="text-right"><?php echo $value->PG_HR; ?>  </td>
                                            <td class="text-right"><?php echo $value->PG_JR; ?>  </td>
                                            <td class="text-right"><?php echo $value->PG_LR; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo $value->PG_CS; ?>  </td>
                                            <td class="text-right"><?php echo $value->PG_LS; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo $PG_GTside; ?></td>
                                        </tr>
                                        </tr>
                            <?php
                                    }
                            ?>
                                        <tr>
                                            <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">Grand Total</td>
                                            <td class="text-left">Unit</td>
                                            <td class="text-right"><?php echo $GTASS; ?>  </td>
                                            <td class="text-right"><?php echo $GTCC2; ?>  </td>
                                            <td class="text-right"><?php echo $GTHR; ?>  </td>
                                            <td class="text-right"><?php echo $GTJR; ?>  </td>
                                            <td class="text-right"><?php echo $GTLR; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo $GTCS; ?>  </td>
                                            <td class="text-right"><?php echo $GTLS; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo array_sum([$GTASS,$GTCC2,$GTHR,$GTJR,$GTLR,$GTCS,$GTLS]); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Waktu</td>
                                            <td class="text-right"><?php echo $GTPG_ASS; ?>  </td>
                                            <td class="text-right"><?php echo $GTPG_CC2; ?>  </td>
                                            <td class="text-right"><?php echo $GTPG_HR; ?>  </td>
                                            <td class="text-right"><?php echo $GTPG_JR; ?>  </td>
                                            <td class="text-right"><?php echo $GTPG_LR; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo $GTPG_CS; ?>  </td>
                                            <td class="text-right"><?php echo $GTPG_LS; ?>  </td>
                                            <td class="text-right"><?php echo 0; ?></td>
                                            <td class="text-right"><?php echo array_sum([$GTPG_ASS,$GTPG_CC2,$GTPG_HR,$GTPG_JR,$GTPG_LR,$GTPG_CS,$GTPG_LS]); ?></td>
                                        </tr>
                            <?php
                                else:
                                    belumAdaData(13);
                                endif;
                            else:
                                belumAdaData(13);
                            endif;
                            ?>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </fieldset>

</section>
