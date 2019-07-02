<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");

?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right">
            <a class="btn btn-default  <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('laporan_customer/customer_print?pilih=' . $this->input->get("pilih") . '&tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir")); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Customer" ></i> Cetak
            </a>

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Laporan customer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" >
                <form id="filterFormz" action="<?php echo base_url('laporan_customer/customer') ?>" class="bucket-form" method="get">
                    <!--<div id="ajax-url" url="<?php echo base_url('laporan_customer/customer_typeahead'); ?>"></div>-->
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
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

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Filter by</label>
                                <select id="pilih" name="pilih" class="form-control">
                                    <option value="0" <?php echo ($pilih == 0 ? "selected" : ""); ?>>Appointment</option>
                                    <option value="1") <?php echo ($pilih == 1 ? "selected" : ""); ?>>Customer Database</option>
                                    <option value="2" <?php echo ($pilih == 2 ? "selected" : ""); ?>>Guest Book</option>
                                    <option value="3" <?php echo ($pilih == 3 ? "selected" : ""); ?>>Test Drive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <br>
                            <button id="submit-btn " onclick="addData();" class="btn btn-primary pull-right"><i class='fa fa-search'></i> Preview</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 padding-left-right-10">
    <div class="panel panel-default">
        <div class="table-responsive">
            <?php
            if ($pilih == 0) {
                ?>
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>Tanggal</th>
                            <!--<th>No. Telepon</th>-->
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->KD_CUSTOMER ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->NAMA_CUSTOMER; ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->ALAMAT; ?></td>
                                        <td><?php echo tglfromSql($row->TANGGAL_JANJI); ?></td>
                                        <!-- <td><?php echo $row->NO_TELEPON; ?></td> -->
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            ?>
                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                            </tr>
                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>

                <?php
            }elseif ($pilih == 1) {
                ?>

                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal SPK</th>
                            <th>Nomor Telepon</th>
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->KD_CUSTOMER ?></td>
                                        <td><?php echo $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo $row->JENIS_KELAMIN; ?></td>
                                        <td><?php echo tglfromSql($row->TGL_SPK); ?></td>
                                        <td><?php echo $row->NO_TELEPON; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            ?>
                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                            </tr>
                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>

                <?php
            }elseif ($pilih == 2) {
                ?>

                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Berkunjung</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->KD_CUSTOMER ?></td>
                                        <td><?php echo $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo $row->JENIS_KELAMIN; ?></td>
                                        <td><?php echo tglfromSql($row->TANGGAL); ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->ALAMAT; ?></td>
                                        <td><?php echo $row->NO_TELEPON; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            ?>
                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                            </tr>
                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>
                <?php
            }else {
                ?>

                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Jenis Kelamin</th>  
                            <th>Tanggal Berkunjung</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
                            <th>Test Drive</th>
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->KD_CUSTOMER ?></td>
                                        <td><?php echo $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo $row->JENIS_KELAMIN; ?></td>
                                        <td><?php echo tglfromSql($row->TANGGAL); ?></td>
                                        <td class="table-nowarp" class="td-overflow-50"><?php echo $row->ALAMAT; ?></td>
                                        <td><?php echo $row->NO_TELEPON; ?></td>
                                        <td><?php echo $row->TEST_DRIVE; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>

                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                                </tr>

                            <?php
                            endif;
                        else:
                            ?>

                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                            </tr>

                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
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