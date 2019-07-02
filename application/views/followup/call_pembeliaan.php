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
            <a href="<?php echo base_url("follow_up/after_service");?>" class="btn btn-default" role="button"> <i class="fa fa-list-ul"></i> FU After Service</a>
            <a href="<?php echo base_url("follow_up/service_reminder_booking");?>" class="btn btn-default" role="button"> <i class="fa fa-list-ul"></i> FU Remainder</a>
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Follow Up Pembelian
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('follow_up/call_pembeliaan') ?>" class="bucket-form" method="get">
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
                                <label>Kode Customer atau Nama Customer</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Kode Customer atau Nama Penerima" autocomplete="off">
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
                <table class="table table-striped b-t b-light">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Aksi</th>
                            <th>No. Rangka</th>
                            <th>Nama Penerima</th>
                            <!-- <th>Kode Customer</th> -->
                            <th>Nama Customer</th>
                            <th>Jenis Kelamin</th>
                            <th>Propinsi</th>
                            <th>No. Hp</th>
                            <th>Tgl. Terima</th>
                            <th>No. SJ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if (isset($list)):
                            if ($list->totaldata >0):
                            
                                foreach ($list->message as $key => $row):
                                    $notifclass = '';
                                    
                                    if(tglToSql(tglfromSql($row->TGL_TERIMA)) <= tglToSql(date('d/m/Y')) && tglToSql(tglfromSql(getNextDays($row->TGL_TERIMA,5))) >= tglToSql(date('d/m/Y')) ){

                                        $notifclass = 'info';
                                    }elseif(tglToSql(tglfromSql(getNextDays($row->TGL_TERIMA,5))) < tglToSql(date('d/m/Y')) ){
                                        $notifclass = 'danger';
                                    }
                                    $no ++;
                                    ?>

                                    <tr class="<?php echo $notifclass;?>">
                                        <td class="table-nowarp text-center"><?php echo  $no; ?></td>
                                        <td class="table-nowarp">
                                            <a class="active <?php echo $status_e?>" id="modal-button" onclick='addForm("<?php echo base_url('follow_up/call_thanks?no_rangka='.$row->NO_RANGKA); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class='fa fa-volume-control-phone' data-toggle="tooltip" data-placement="left" title="follow up pembelian" ></i>
                                            </a>
                                        </td>
                                        <td class="table-nowarp"><?php echo  $row->NO_RANGKA; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->NAMA_PENERIMA; ?></td>
                                        <!-- <td class="table-nowarp"><?php echo  $row->KD_CUSTOMER; ?></td> -->
                                        <td class="table-nowarp"><?php echo  $row->NAMA_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->JENIS_KELAMIN; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->NAMA_PROPINSI; ?></td>
                                        <td class="table-nowarp"><?php echo  $row->NO_HP; ?></td>
                                        <td class="table-nowarp"><?php echo  tglfromSql($row->TGL_TERIMA); ?></td>
                                        <td class="table-nowarp"><?php echo  $row->NO_SURATJALAN; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="33"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(8);
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