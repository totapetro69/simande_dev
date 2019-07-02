<?php
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
?>

<style type="text/css">
    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
        /* float: left; */
        text-align: left;
        display: table;
        width: 100%;
    }
    .project div {
        display: table-row;
    }

    .project .title {
        color: #5D6975;
        width: 90px;
    }

    .project span {
        text-align: left;
        /* width: 100px; */
        /* margin-right: 15px; */
        padding: 2px 0;
        display: table-cell;
        /* font-size: 0.8em; */
    }

    .project .content {
        width: 150px;
    }

    /*@page { size: landscape; }*/
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Customer</h4>
</div>

<div class="modal-body" id="printarea">

    <table border="0" id="desc" class="">
        <tr>
            <td rowspan="2"></td>
            <td align="center" colspan="9"><h2><strong>Laporan Customer</strong></h2></td>
        </tr>

        <tr>
            <th colspan="11" class="text-center"><h5><strong>Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></strong></h5></th>
        </tr>

        <tr>
            <td colspan="11" class="text-center">
                Datanya berdasarkan : <?php switch ($this->input->get("pilih")) {
                    case '0':
                       echo "Appointment";
                        break;
                    case '1':
                       echo "Customer Database";
                        break;
                    case '2':
                       echo "Guest Book";
                        break;
                    case '3':
                        echo "Test Drive";
                        break;
                    default:
                       echo "Appointment";
                        break;
                }
                ?>
            </td>
        </tr>

    </table>

    <table border="0" id="desc" class="">
        <tr>
            <td style="width: 150px;">Kode Main Dealer</td>
            <td  style="width: 20px;">:</td>
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

        <?php
        if ($pilih == 0) {
            ?>
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th style="width:200px;">Kode Customer</th>
                        <th style="width: 100px;">Nama Customer</th>
                        <th>Alamat</th>
                        <th style="width: 100px;">Tanggal</th>
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
                                    <td class="table-nowarp" class="td-overflow-50"><?php echo $row->KD_CUSTOMER; ?></td>
                                    <td class="table-nowarp" class="td-overflow-50"><?php echo $row->NAMA_CUSTOMER; ?></td>
                                    <td class="table-nowarp" class="td-overflow-50"><?php echo $row->ALAMAT; ?></td>
                                    <td><?php echo tglfromSql($row->TANGGAL_JANJI); ?></td>
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
                        <th>Tanggal</th>
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
                                    <td><?php echo $row->KD_CUSTOMER ?></td>
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
                                    <td class="table-nowarp"><?php echo $row->NAMA_CUSTOMER; ?></td>
                                    <td><?php echo $row->JENIS_KELAMIN; ?></td>
                                    <td><?php echo tglfromSql($row->TANGGAL); ?></td>
                                    <td class="table-nowarp" class="td-overflow-50"><?php echo $row->ALAMAT ?></td>
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
                                    <td class="table-nowarp" class="td-overflow-50"><?php echo $row->NAMA_CUSTOMER; ?></td>
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



        <tr><td colspan="9">&nbsp;</td></tr>
        <tr><td colspan="9">&nbsp;</td></tr>

        <tr>
            <td colspan="8"></td>
            <td style="text-align: right;" valign="top">
                <div class="project">
                    <div><span class="title" style="text-align: right;"><?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total : " . $list->totaldata . "</i>") : '' ?></span></div>
                </div>
            </td>
        </tr>



</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printSj() {
            printJS('printarea', 'html');
            $('#keluar').click();
        }
</script>