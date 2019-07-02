<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/report_ksu_print?kd_dealer=' . $this->input->get("kd_dealer") ); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Penerimaan" ></i> Cetak
            </a>    

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Laporan KSU
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterForm" action="<?php echo base_url('report/report_ksu') ?>" class="bucket-form" method="get">

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12">

                            <div class="form-group">
                                <label>Dealer</label>
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
                            <th>Kode KSU</th>
                            <th>Nama KSU</th>
                            <th>Total Unit</th>
                            <th>KSU Diterima</th>
                            <th>KSU Belum Diterima</th>
                        </tr>

                    </thead>

                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    ?>

                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row->KD_KSU; ?></td>
                                        <td><?php echo $row->NAMA_KSU; ?></td>
                                        <?php if ($row->KD_KSU == "BPPSG") { ?>
                                            <td><?php echo $row->BPPSG+$row->NBPPSG; ?></td>
                                        <?php } else if ($row->KD_KSU == "AKI") { ?>
                                            <td><?php echo $row->AKI+$row->NAKI; ?></td>
                                        <?php } else if ($row->KD_KSU == "HELM") { ?>
                                            <td><?php echo $row->HELM+$row->NHELM; ?></td>
                                        <?php } else if ($row->KD_KSU == "SPION") { ?>
                                            <td><?php echo $row->SPION+$row->NSPION; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $row->TOOLSET+$row->NTOOLSET; ?></td>
                                        <?php }; ?>
                                        
                                        <?php if ($row->KD_KSU == "BPPSG") { ?>
                                            <td><?php echo $row->BPPSG; ?></td>
                                        <?php } else if ($row->KD_KSU == "AKI") { ?>
                                            <td><?php echo $row->AKI; ?></td>
                                        <?php } else if ($row->KD_KSU == "HELM") { ?>
                                            <td><?php echo $row->HELM; ?></td>
                                        <?php } else if ($row->KD_KSU == "SPION") { ?>
                                            <td><?php echo $row->SPION; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $row->TOOLSET; ?></td>
                                        <?php }; ?>

                                        <?php if ($row->KD_KSU == "BPPSG") { ?>
                                            <td><?php echo $row->NBPPSG; ?></td>
                                        <?php } else if ($row->KD_KSU == "AKI") { ?>
                                            <td><?php echo $row->NAKI; ?></td>
                                        <?php } else if ($row->KD_KSU == "HELM") { ?>
                                            <td><?php echo $row->NHELM; ?></td>
                                        <?php } else if ($row->KD_KSU == "SPION") { ?>
                                            <td><?php echo $row->NSPION; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $row->NTOOLSET; ?></td>
                                        <?php }; ?>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>

                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                                </tr>

                            <?php
                            endif;
                        else:
                            echo belumAdaData(40);
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
</section>
