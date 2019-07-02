<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
//$defaultDealer = ($this->session->userdata("kd_dealer"));
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">
    
    
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('pkb/add_pkb'); ?>' >
                    <i class="fa fa-file-o fa-fw"></i> Tambah Data
                </a>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list-ul'></i> List PKB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form id="filterForm" action="<?php echo base_url('pkb/pkb_list') ?>" class="bucket-form">
                    <div id="ajax-url" url="<?php echo base_url('pkb/pkb_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <label>Cari</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $this->input->get('keyword'); ?>" placeholder="Masukan No. PKB atau No. Polisi" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control">
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
                            <th style="width:30px;">No.</th>
                            <?php if($status_e == '') echo '<th>Proses</th>';?>
                            <th>Aksi</th>
                            <th>Tgl Service</th>
                            <!-- <th>No. SA</th> -->
                            <th>No. PKB</th>
                            <th>No. Polisi</th>
                            <th>KM. Motor</th>
                            <!-- <th>No. Rangka</th>
                            <th>No. Mesin</th>
                            <th>Tipe Motor</th> -->
                            <th>Nama Mekanik</th>
                            <th>No. Antrian</th>
                            <th>Status PKB</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Jenis PKB</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($list) {
                            $no = $this->input->get('page');
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $value) {
                                    $approve = $value->STATUS_PKB == 0 && $value->STATUS_APPROVE == 'approve'?$status_e:'disabled-action';
                                    $play = $value->STATUS_PKB == 1 || $value->STATUS_PKB == 3?$status_e:'disabled-action';
                                    $pause = $value->STATUS_PKB == 2?$status_e:'disabled-action';
                                    $finish = $value->STATUS_PKB == 2 && $value->ALL_PICKING_STATUS == 'true'?$status_e:'disabled-action';
                                    $print_nota = $value->STATUS_PKB >= 4 && $value->ALL_PICKING_STATUS == 'true'?$status_e:'disabled-action';
                                    $edit = $value->STATUS_PKB < 4?$status_e:'disabled-action';

                                    # code...
                                    $no++;
                                    ?>
                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $value->ID ? 'tr-active' : ' '; ?>" >
                                        <td class="table-nowarp text-center"><?php echo $no; ?></td>
                                        <?php if($status_e == ''):?>
                                        <td class="table-nowarp">
                                            <btn id="approve_<?php echo $value->ID;?>" class="proses_<?php echo $value->ID;?> proses btn btn-success btn-xs <?php echo $approve;?>" data-toggle="tooltip" data-placement="left" title="approve"><i class="fa fa-check"></i></btn>
                                            <btn id="play_<?php echo $value->ID;?>" class="proses_<?php echo $value->ID;?> proses btn btn-primary btn-xs <?php echo $play; ?>" data-toggle="tooltip" data-placement="left" title="kerjakan"><i class="fa fa-play"></i></btn>
                                            <btn id="pause_<?php echo $value->ID;?>" class="proses_<?php echo $value->ID;?> proses btn btn-danger btn-xs <?php echo $pause; ?>" role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-pause" data-toggle="tooltip" data-placement="left" title="pending"></i></btn>
                                            <btn id="finish_<?php echo $value->ID;?>" class="proses_<?php echo $value->ID;?> proses btn btn-info btn-xs <?php echo $finish; ?>" role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-flag" data-toggle="tooltip" data-placement="left" title="finish"></i></btn>
                                            <btn id="modal-button" data-nopkb="<?php echo $value->NO_PKB;?>" class="btn nota_<?php echo $value->ID;?> btn-warning btn-xs <?php echo $print_nota; ?>" onclick='addForm("<?php echo base_url('pkb/print_nota/'.$value->NO_PKB); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print" data-toggle="tooltip" data-placement="left" title="Print nota penjualan"></i></btn>
                                        </td>
                                        <?php endif;?>
                                        <td class="table-nowarp">
<!-- 
                                            <a class="active <?php echo $status_p?>" id="modal-button" onclick='addForm("<?php echo base_url('pkb/print_nota/'.$value->NO_PKB); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Print nota penjualan" ></i>
                                            </a> -->
                                            <a id="edit"  href="<?php echo base_url('pkb/add_pkb?u=' . $value->NO_PKB); ?>");' role="button" class="<?php echo $status_v ?> action_<?php echo $value->ID;?> " >
                                            <!-- <a id="edit"  href="<?php echo base_url('pkb/edit_pkb/' . $value->ID . '/' . $value->ROW_STATUS); ?>");' role="button" class="<?php echo  $status_v ?>" > -->
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                        <?php if($status_e == ''):?>
                                            <a id="delete-btn<?php echo  $no; ?>" class="delete-btn <?php echo $edit ?> action_<?php echo $value->ID;?> " url="<?php echo base_url('pkb/delete_pkb/' . $value->ID); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        <?php endif;?>
                                        </td>
                                        <td class="table-nowarp"><?php echo tglfromSql($value->TANGGAL_PKB); ?></td>
                                        <!-- <td class="table-nowarp"><?php echo $value->KD_SA; ?></td> -->
                                        <td class="table-nowarp"><?php echo $value->NO_PKB; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                                        <td class="table-nowarp text-right"><?php echo number_format($value->KM_MOTOR,0); ?></td>
                                        <!-- <td class="table-nowarp"><?php echo $value->NO_RANGKA; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_MESIN; ?></td>
                                        <td class="td-overflow-20" title="<?php echo $value->NAMA_TYPEMOTOR; ?>"><?php echo $value->NAMA_TYPEMOTOR; ?></td> -->
                                        <td class="td-overflow-20" title="<?php echo $value->NAMA; ?>"><?php echo $value->NAMA; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_ANTRIAN; ?></td>
                                        <td id="status_<?php echo $value->ID;?>" class="table-nowarp">                                        
                                        <?php
                                            switch ($value->STATUS_PKB) {
                                                case 1: echo "Diapprove"; break;
                                                case 2: echo "Pengerjaan"; break;
                                                case 3: echo "Pending"; break;
                                                case 4: echo "Selesai"; break;
                                                case 5: echo "Dibayar"; break;
                                                default: echo "Menunggu"; break;
                                            }
                                        ?>
                                        </td>
                                        <td class="datetime-mulai table-nowarp"><?php echo date('H:i', strtotime($value->ESTIMASI_MULAI)); ?></td>
                                        <td class="datetime-selesai table-nowarp"><?php echo date('H:i', strtotime($value->ESTIMASI_SELESAI)); ?></td>
                                        <td class="text-right table-nowarp"><?php echo $value->JENIS_KPB; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                belumAdaData(16);
                            }
                        } else {
                            belumAdaData(16);
                        }
                        ?>
                    </tbody>
                </table>
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
    </div>
    <?php echo loading_proses(); ?>
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/pkb.js");?>"></script>