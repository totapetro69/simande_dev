<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report_dmd/report_dmd_print?tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir"). '&pilih=' . $this->input->get("pilih"). '&bulan=' . $this->input->get("bulan"). '&tahun=' . $this->input->get("tahun")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Service Rate" ></i> Cetak
            </a>    

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> Service Rate
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>


            <div class="panel-body panel-body-border" >

                <form id="filterFormz" action="<?php echo base_url('report_dmd/service_dmd') ?>" class="bucket-form" method="get">

                    <div class="row">

                        <div class="col-xs-4 col-sm-4 col-md-4">

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
                        
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan</option>
                                    <?php 
                                        for($i=1;$i<=12; $i++){
                                            $periode=(date("m")==$i)?"selected":"";
                                            $periode=((int)$this->input->get("bulan")==$i)?"selected":"";
                                            echo "<option value='".$i."' ".$periode.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-xs-2 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">--Pilih Tahun</option>
                                    <?php 
                                        if(isset($tahun)){
                                            if($tahun->totaldata>0){
                                                foreach ($tahun->message as $key => $value) {
                                                    $periode=(date("Y")==$value->TAHUNS)?"selected":"";
                                                    $periode=($this->input->get("tahun")==$value->TAHUNS)?"selected":$periode;
                                                    echo "<option value='".$value->TAHUNS."' $periode>".$value->TAHUNS."</option>";
                                                }
                                            }else{
                                                echo "<option value='".date("Y")."' selected>".date("Y")."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-4 col-sm-4 col-md-4">

                            <div class="form-group">
                                <label>Service Rate</label>
                                <select id="pilih" name="pilih" class="form-control">
                                    <option value="0" <?php echo ($pilih == 0 ? "selected" : ""); ?>>Main Dealer to Dealer</option>
                                    <option value="1" <?php echo ($pilih == 1 ? "selected" : ""); ?>>Dealer to Customer</option>
                                </select>
                            </div>

                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-12">

                            <div class="form-group" >

                                <button id="submit-btn" onclick="addData();" class="btn btn-info pull-right  " ><i class='fa fa-search'></i> Preview</button>
                                
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

                <?php
                if ($pilih == 0) {
                    ?>
                    <table class="table table-striped b-t b-light">

                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Tanggal</th>
                                <th>Part Number</th>
                                <th>Deskripsi</th>
                                <th>Qty. Order</th>
                                <th>Qty. Supply</th>
                                <th>Amount Order</th>
                                <th>Amount Supply</th>
                                <th>SR Q</th>
                                <th>SR A</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = $this->input->get('page');
                            if ($list):
                                if (is_array($list->message) || is_object($list->message)):
                                    foreach ($list->message as $key => $row):
                                        $AMOUNT_SUPPLY = $row->JUMLAH2 * $row->HARGA;
                                        $SRQ = $row->JUMLAH2 == 0 ? 0 : ($row->JUMLAH1) / ($row->JUMLAH2) * 100;
                                        $SRA = $AMOUNT_SUPPLY == 0 ? 0 : $row->HARGA / $AMOUNT_SUPPLY * 100;

                                        $no ++;
                                        ?>

                                        <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo tglfromSql($row->TGL_PO); ?></td>
                                            <td><?php echo $row->PART_NUMBER; ?></td>
                                            <td><?php echo $row->PART_DESKRIPSI; ?></td>
                                            <td align="center"><?php echo $row->JUMLAH1; ?></td>                      <!--QTY ORDER-->
                                            <td align="center"><?php echo $row->JUMLAH2; ?></td>                            <!--QTY SUPPLY-->
                                            <td align="center"><?php echo number_format($row->HARGA, 2); ?></td>      <!--AMOUNT ORDER-->
                                            <td align="center"><?php echo number_format($AMOUNT_SUPPLY, 2); ?></td>        <!--AMOUNT SUPPLY-->
                                            <td align="center"><?php echo $SRQ; ?></td>                                    <!--SRQ-->
                                            <td align="center"><?php echo $SRA; ?></td>                                    <!--SRA-->
                                        </tr>

                                        <?php
                                    endforeach;
                                else:
                                    echo belumAdaData(40);
                                    ?>
                                <?php
                                endif;
                            else:
                                echo belumAdaData(40);
                                ?>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>

                    <?php
                }
                elseif ($pilih == 1) {
                    ?>

                    <table class="table table-striped b-t b-light">
                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Tanggal</th>
                                <th>Part Number</th>
                                <th>Deskripsi</th>
                                <th>Qty. Order</th>
                                <th>Qty. Supply</th>
                                <th>Amount Order</th>
                                <th>Amount Supply</th>
                                <th>SR Q</th>
                                <th>SR A</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                            $no = $this->input->get('page');
                            if ($list):
                                if (is_array($list->message) || is_object($list->message)):
                                    foreach ($list->message as $key => $row):
                                        $AMOUNT_SUPPLY = $row->JUMLAH * $row->HARGA_JUAL;
                                        $SRQ = $row->JUMLAH == 0 ? 0 : ($row->JUMLAH_ORDER) / ($row->JUMLAH) * 100;
                                        $SRA = $AMOUNT_SUPPLY == 0 ? 0 : $row->HARGA_JUAL / $AMOUNT_SUPPLY * 100;

                                        $no ++;
                                        ?>

                                        <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo tglfromSql($row->TGL_TRANS); ?></td>
                                            <td><?php echo $row->PART_NUMBER; ?></td>
                                            <td><?php echo $row->PART_DESKRIPSI; ?></td>
                                            <td align="center"><?php echo $row->JUMLAH_ORDER; ?></td>                      <!--QTY ORDER-->
                                            <td align="center"><?php echo $row->JUMLAH; ?></td>                            <!--QTY SUPPLY-->
                                            <td align="center"><?php echo number_format($row->HARGA_JUAL, 2); ?></td>      <!--AMOUNT ORDER-->
                                            <td align="center"><?php echo number_format($AMOUNT_SUPPLY, 2); ?></td>        <!--AMOUNT SUPPLY-->
                                            <td align="center"><?php echo $SRQ; ?></td>                                    <!--SRQ-->
                                            <td align="center"><?php echo $SRA; ?></td>                                    <!--SRA-->
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
    <?php echo loading_proses(); ?>
</section>