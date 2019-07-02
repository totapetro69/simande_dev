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

            <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('pdss/createfile_pdss??kd_dealer=' . $this->input->get("kd_dealer") . '&tanggal=' . $this->input->get("tanggal") . '&keyword=' . $this->input->get("keyword")); ?>" role="button">
                <i class="fa fa-download fa-fw"></i> Download File .PDSS
            </a>
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                File Sales Stock Dealer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border">

                <form id="filterForm" action="<?php echo base_url('pdss/pdss_list') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('pdss/pdss_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-3">

                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n; ?>>
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                        if (is_array($dealer->message)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" ."(".$value->KD_DEALERAHM.") ". $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>

                        </div>
                        
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">File Sales Stock Dealer
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Part Number" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">

                            <label class="control-label" for="date">Tanggal</label>
                            <div class="input-group input-append date">
                                <input class="form-control" name="tanggal" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tanggal")) ? $this->input->get("tanggal") : date('d/m/Y'); ?>" type="text"/>
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

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
                            <th>Kode Main Dealer</th>
                            <th>Kode Dealer</th>
                            <th>Tanggal</th>
                            <th>Part Number</th>
                            <th>Qty On Hand</th>
                            <th>Qty Sales</th>
                            <th>Qty Sim Parts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($list) {
                            $no = 0;
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $row) {
                                    $no ++;
                                    ?>
                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row->KD_MAINDEALER; ?></td>
                                        <td><?php echo $row->KD_DEALERAHM; ?></td>
                                        <td><?php echo $row->TANGGAL; ?></td>
                                        <td><?php echo $row->PART_NUMBER; ?></td>
                                        <td><?php echo $row->QTY_ON_HAND; ?></td>
                                        <td><?php echo $row->QTY_SALES; ?></td>
                                        <td><?php echo $row->QTY_SIM_PARTS; ?></td>
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