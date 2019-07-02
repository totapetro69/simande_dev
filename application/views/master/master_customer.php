<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$defaultDealer = ($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            <!-- <a id="modal-button" class="btn btn-default <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('customer/add_customer'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Tambah Customer
            </a> -->
            <a id="modal-button" class="btn btn-default" href="<?php echo base_url('customer/add_customer'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Tambah Customer
            </a>
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class="fa fa-list-ul"></i> Customer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterForm" action="<?php echo base_url('customer/customer') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('customer/customer_typeahead'); ?>"></div>

                    <div class="row">
                        <div class="col-xs-6 col-md-4 col-sm-4">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control " id="kd_dealer" name="kd_dealer">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata>0) {
                                            foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-8">

                            <div class="form-group">
                                <label>Kode Customer atau Nama Customer</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Kode Customer atau Nama Customer" autocomplete="off">
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-4 hidden">

                            <div class="form-group">
                                <label>Status</label>
                                <select id="row_status" name="row_status" class="form-control">
                                    <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                                    <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                                    <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
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
                            <th style="width:45px;">Aksi</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Jenis Kelamin</th>
                            <th>Propinsi</th>
                            <th>No. Hp</th>
                            <th>Status</th>
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

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo  $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button" href="<?php echo base_url('customer/edit_customer/' . $row->KD_CUSTOMER); ?>");' role="button"  class="<?php echo  $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text"></i>
                                            </a>
                                            <a id="delete-btn<?php echo  $no; ?>" class="delete-btn hidden" url="<?php echo base_url('customer/delete_customer/' . $row->KD_CUSTOMER); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <td><?php echo  $row->KD_CUSTOMER; ?></td>
                                        <td><?php echo  $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo  $row->JENIS_KELAMIN; ?></td>
                                        <td><?php echo  $row->NAMA_PROPINSI; ?></td>
                                        <td><?php echo  $row->NO_HP; ?></td>
                                        <td><?php echo  $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
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