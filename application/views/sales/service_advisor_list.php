<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): ($this->session->userdata("kd_dealer"));
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$tgl_awal = ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month'));
$tgl_akhir = ($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y');
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('customer_service/add_service_advisor'); ?>' >
                    <i class="fa fa-file-o fa-fw"></i> Add SA
                </a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> LIST SALES ADVISOR
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="saForm" action="<?php echo base_url('customer_service/service_advisor_list') ?>" class="bucket-form">
               
                    <div id="ajax-url" url="<?php echo base_url('customer_service/serviceadvisor_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-3 col-md-3">

                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                                    <option value="">- Pilih Dealer -</option>
                                    <?php
                                    if(isset($dealer)){
                                        if($dealer->totaldata >0){
                                            foreach ($dealer->message as $key => $value) {
                                                $default = ($defaultDealer == $value->KD_DEALER) ? " selected" :"";
                                                ?>
                                                    <option value="<?php echo $value->KD_DEALER; ?>" <?php echo $default; ?> ><?php echo $value->NAMA_DEALER; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $tgl_awal; ?>" type="text" autocomplete="off"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2 col-md-2">

                            <div class="form-group">

                                <label class="control-label" for="date">Tanggal Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $tgl_akhir; ?>" type="text" autocomplete="off"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                                </div>

                            </div>

                        </div>

                    <!-- </div>

                    <div class="row"> -->

                        <div class="col-xs-12 col-sm-4 col-md-4">

                            <div class="form-group">
                                <label>Cari</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukan Kode Customer, No. Polisi atau No. Mesin" autocomplete="off">
                            </div>

                        </div>
                        
                        <div class="col-xs-12 col-sm-1 col-md-1">
                            
                            <div class="form-group">

                                <label> </label>
                                <button id="submit-btn" onclick="" class="btn btn-primary" style="width:100%" >Preview</button>

                            </div>
                        </div> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive h350">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>&nbsp;</th>
                            <th>Tanggal</th>
                            <th>Nama Customer</th>
                            <th>No. Telp</th>
                            <th>No. Polisi</th>
                            <th>No. Mesin</th>
                            <!-- <th>Tipe PKB</th> -->
                            <th>KM. Motor</th>
                            <th>Keluhan Konsumen</th>
                            <th>Analisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if (isset($list)) {
                            if ($list->totaldata >0) {
                                foreach ($list->message as $key => $value) {
                                    $no++;
                                    ?>

                                    <tr>
                                        <td class="table-nowarp text-center"><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <?php
                                            if ($value->STATUS_SA < 2) {
                                                ?>
                                            <a id="modal-button"  href="<?php echo base_url('customer_service/add_service_advisor?n=' .urlencode(base64_encode($value->KD_SA))  ); ?>");' role="button" class="<?php echo $status_v ?>" >
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                            <?php
                                            }
                                            ?>
                                             <?php
                                            if ($value->STATUS_SA == 0) {
                                                ?>
                                            <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('customer_service/delete_service_advisor/' . $value->ID); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text <?php echo $status_e;?>"></i>
                                            </a>
                                            <?php
                                            }
                                            ?>

                                            <a href="<?php echo base_url('customer_service/cetak_sa?n=' .urlencode(base64_encode($value->KD_SA))  ); ?>");' role="button" class="<?php echo $status_v ?>" target="_blank">
                                                <i data-toggle="tooltip" data-placement="left" title="form sa" class="fa fa-print text-success text-active"></i>
                                            </a>
                                        </td>
                                        <td class="table-nowarp" title="<?php echo $value->KD_SA;?>"><?php echo tglfromSql($value->TANGGAL_SA); ?></td>
                                        <td class="table-nowarp td-overflow-50" title="<?php echo $value->NAMA_CUSTOMER; ?>"><?php echo $value->NAMA_CUSTOMER; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_HP; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_MESIN; ?></td>
                                        <!-- <td class="table-nowarp"><?php echo $value->KD_TIPEPKB; ?></td> -->
                                        <td class="table-nowarp text-right"><?php echo number_format($value->KM_SAATINI,0); ?></td>
                                        <td class="table-nowarp td-overflow-50" title="<?php echo $value->KEBUTUHAN_KONSUMEN; ?>"><?php echo $value->KEBUTUHAN_KONSUMEN; ?></td>
                                        <td class="table-nowarp td-overflow-50" title="<?php echo $value->HASIL_ANALISA_SA; ?>"><?php echo $value->HASIL_ANALISA_SA; ?></td>
                                        <td class="table-nowarp">                                        
                                            <?php
                                            if ($value->STATUS_SA == 0) {
                                                echo "Open";
                                            } elseif ($value->STATUS_SA == 1) {
                                                echo "OnProgress";
                                            } elseif ($value->STATUS_SA == 2) {
                                                echo "Finish";
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                belumAdaData();
                            }
                        } else {
                            belumAdaData();
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