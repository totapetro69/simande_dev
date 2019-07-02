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

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('customer/createfile_udcp?kd_dealer=' . $this->input->get("kd_dealer") . '&tanggal=' . $this->input->get("tanggal")); ?>" role="button">
                    <i class="fa fa-download fa-fw"></i> Download File .UDCP
                </a>
            </div>

<!--            <div class="btn-group">
                <a class="btn btn-default " href="<?php echo base_url('report/guest_book_daily'); ?>" role="button">
                    <i class="fa fa-arrow-circle-right"></i> Download Daily
                </a>
            </div>-->

<!--            <div class="btn-group">
                <a role="button" href="<?php echo base_url("customer/guest_book"); ?>" class="btn btn-default  <?php echo $status_v; ?>" ><i class="fa fa-list-ul"></i> List Guest Book</a>
            </div>-->

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> Guest Book
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterFormz" action="<?php echo base_url('customer/guest_book_download') ?>" class="bucket-form">

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

                                <label> </label>
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

                <table class="table table-striped b-t b-light">

                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>ID Guest Book</th>
                            <th>Nama Customer</th>
                            <th>ID Honda</th>
                            <th>Kode Warna</th>
                            <th>Kode Type</th>
                            <th>Tanggal</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Rencana Pembayaran</th>
                            <th>Jenis Customer</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Nama Sales</th>
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
                                        <td class="table-nowarp"><?php echo $value->GUEST_NO; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_HSALES; ?></td>
                                        <td class="table-nowarp"><?php echo $value->KD_WARNA; ?></td>
                                        <td class="td-overflow"><?php echo $value->KD_TYPEMOTOR; ?></td>
                                        <td class="table-nowarp"><?php echo $value->TANGGAL; ?></td>
                                        <td class="td-overflow" title="<?php echo $value->ALAMAT; ?>"><?php echo $value->ALAMAT; ?></td>
                                        <td><?php echo $value->NO_HP; ?></td>
                                        
                                        <?php if ($value->CARA_BAYAR == "CASH") { ?>
                                            <td>(1) CASH</td>
                                        <?php } else if ($value->CARA_BAYAR == "CREDIT") { ?>
                                            <td>(2) CREDIT</td>
                                        <?php } else if ($value->CARA_BAYAR == "BILYET") { ?>
                                            <td>(3) BILYET</td>
                                        <?php }else { ?>
                                            <td align="center">-</td>
                                        <?php } ?> 
                                        
                                        
                                        <?php if ($value->KD_TYPECUSTOMER == 'RO') { ?>
                                            <td class="td-overflow">(2) <?php echo $value->NAMA_TYPECUSTOMER; ?></td>
                                        <?php } else if ($value->KD_TYPECUSTOMER == ''){ ?>
                                            <td align="center">-</td>
                                        <?php } else { ?>
                                            <td class="td-overflow">(1) <?php echo $value->NAMA_TYPECUSTOMER; ?></td>
                                        <?php } ?>
                                        
                                        <!--?php if ($value->TYPECUSTOMER == '1') { ?>
                                            <td>(1) Baru</td>
                                        < ?php } else { ?>
                                            <td>(2) RO</td>
                                        < ?php } ?--> 
                                        
                                        <?php if ($value->JENIS > 0) { ?>
                                            <td>(2) Hot</td>
                                        <?php } else { ?>
                                            <td>(1) Cold</td>
                                            <?php
                                        }
                                        ?> 
                                        <td title="<?php echo $value->KETERANGAN; ?>"><?php echo $value->KETERANGAN; ?></td>
                                        <td><?php echo $value->NAMA_SALES; ?></td>
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

        </div>

        <div class="panel-footer">

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

        </div>

    </div>
    <?php echo loading_proses(); ?>
</section>