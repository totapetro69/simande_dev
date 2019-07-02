<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('follow_up/call_service_reminder_booking'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-volume-control-phone fa-fw"></i> Follow Up
            </a>
            <a href="<?php echo base_url("follow_up/call_pembeliaan");?>" class="btn btn-default" role="button"> <i class="fa fa-list-ul"></i> FU Pembelian</a>
            <a href="<?php echo base_url("follow_up/after_service");?>" class="btn btn-default" role="button"> <i class="fa fa-list-ul"></i> FU After Services</a>
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-list-ul"></i> Follow Up Service Reminder & Service Booking
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: none;">
                <form id="filterForm" action="<?php echo base_url('follow_up/service_reminder_booking') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('customer/customer_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                        if(isset($dealer)){
                                            if($dealer->totaldata >0){
                                                foreach ($dealer->message as $key => $value) {
                                                    $pilih=($defaultDealer==$value->KD_DEALER)?'selected':'';
                                                    echo "<option value='".$value->KD_DEALER."' $pilih>".$value->NAMA_DEALER."</option>";
                                                }
                                            }
                                        }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-5 col-md-5">

                            <div class="form-group">
                                <label>Kode Customer</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Kode Customer" autocomplete="off">
                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel panel-default">

            <div class="table-responsive h400">

                <table class="table table-striped table-bordered b-t b-light">

                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Aksi</th>
                            <th rowspan="2">No. Rangka</th>
                            <th rowspan="2">Type KPB</th>
                            <!-- <th rowspan="2">Kode Customer</th> -->
                            <th rowspan="2">Nama Customer</th>
                            <!-- <th rowspan="2">Propinsi</th> -->
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

                                    $next_fu = $row->NEXT_FU == 0 ? $status_e :'disabled-action';
                                    $no ++;
                                    ?>

                                    <tr class="<?php echo $notifclass;?>">
                                        <td class="table-nowarp text-center"><?php echo  $no; ?></td>
                                        <td class="table-nowarp">
                                            <a class="active <?php echo $next_fu?><?php echo $status_e;?>" id="modal-button" onclick='addForm("<?php echo base_url('follow_up/call_service_reminder_booking?no_trans='.$row->NO_TRANS); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class='fa fa-volume-control-phone' data-toggle="tooltip" data-placement="left" title="follow up Service Reminder" ></i>
                                            </a>
                                        </td>
                                        <td class="table-nowarp"><?php echo  $row->NO_RANGKA; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->JENIS_KPB; ?></td>
                                        <!-- <td class="table-nowarp"><?php echo  $row->KD_CUSTOMER; ?></td> -->
                                        <td class="table-nowarp"><?php echo  $row->NAMA_STNK; ?></td>
                                        <!-- <td class="table-nowarp"><?php echo  $row->PROPINSI_SURAT; ?></td> -->
                                        <td class="table-nowarp"><?php echo  $row->NO_HP; ?></td>

                                        <td class="table-nowarp"><?php echo  $row->STATUS_METODEFU; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->HASIL_METODEFU; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->STATUS_METODEFU2; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->HASIL_METODEFU2; ?></td>

                                        <td class="table-nowarp"><?php echo  tglfromSql($row->TGL_TERIMA); ?></td>
                                        <td class="table-nowarp"><?php echo  $row->NO_SURATJALAN; ?></td>
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

</section>