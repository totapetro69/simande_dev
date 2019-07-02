<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">

            <a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('Setup/add_diskonpart'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Setting Diskon
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('Setup/diskonpart') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('Setup/diskonpart_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-8">

                            <div class="form-group">
                                <label>Setting Diskon</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan part number atau kode tipe customer" autocomplete="off">
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-4">

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
                            <th>Sparepart</th>
							<th>Customer</th>
							<th>Tipe</th>
							<th>Besar Diskon</th>
							<th>Tanggal Mulai</th>
							<th>Tanggal Selesai</th>
							<th>Dealer</th>
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
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('Setup/edit_diskonpart/' . $row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>
											<?php 
											if($row->ROW_STATUS == 0){ 
											?>
                                            <a id="delete-btn<?php echo  $no; ?>" class="delete-btn" url="<?php echo base_url('Setup/delete_diskonpart/' . $row->ID); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
											<?php
											}
											?>
                                        </td>
                                        <td><?php echo  $row->PART_NUMBER; ?> - <?php echo  $row->PART_DESKRIPSI; ?></td>
										<td><?php echo  $row->NAMA_JENISCUSTOMER; ?></td>
										<td><?php if($row->TIPE_DISKON == 0){echo 'PERSEN'; }else{ echo 'RUPIAH'; }?></td>
										<td><?php echo  $row->AMOUNT; ?></td>
										<td><?php echo  $row->START_DATE; ?></td>
										<td><?php echo  $row->END_DATE; ?></td>
										<td><?php echo  $row->NAMA_DEALER; ?></td>
                                        <td><?php echo  $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="12"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            ?>
                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="12"><b>Ada error, harap hubungi bagian IT</b></td>
                            </tr>
                        <?php
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
