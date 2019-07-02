<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a id="modal-button" class="btn btn-primary <?php echo $status_c ?>" onclick='addForm("<?php echo base_url('part/add_part'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Upload File .PDMP
            </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Part
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('part/part') ?>" class="bucket-form" method="get">

                    <!-- <div id="ajax-url" url="<?php echo base_url('part/part_typeahead'); ?>"></div> -->

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Part Number atau Part Deskripsi</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Part Number atau Part Deskripsi" autocomplete="off">
                            </div>

                        </div>

<!--                        <div class="col-xs-12 col-sm-4">

                            <div class="form-group">
                                <label>Status</label>
                                <select id="row_status" name="row_status" class="form-control">
                                    <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                                    <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                                    <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
                                </select>
                            </div>

                        </div>-->

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
                            <th>Part Number</th>
                            <th>Deskripsi Part</th>
                            <th>Part Reference</th>
                            <th>Kode Supplier</th>
                            <th>Kode Group Sales</th>
                            <th>Part Status</th>
                            <th>Part Superseed</th>
                            <th>HET</th>
                            <th>Harga Pokok</th>
                            <th>MOQ DK</th>
                            <th>MOQ DM</th>
                            <th>MOQ DB</th>
                            <th>Part Number Type</th>
                            <th>Part Moving</th>
                            <th>Part Source</th>
                            <th>Part Rank</th>
                            <th>Part Current</th>
                            <th>Part Type</th>
                            <th>Part Lifetime</th>
                            <th>Part Group</th>
                            <!--<th>Status</th>-->
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->PART_NUMBER ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button" href="<?php echo base_url('part/edit_part/' . $row->PART_NUMBER . '/' . $row->ROW_STATUS); ?>");' role="button" class="<?php echo $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                            <?php
                                            if ($row->ROW_STATUS == 0) {
                                                ?>
                <!--                                                <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('part/delete_part/' . $row->PART_NUMBER); ?>">
                                                        <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                                    </a>-->
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td class='table-nowarp'><?php echo $row->PART_NUMBER; ?></td>
                                        <td class='table-nowarp'><?php echo $row->PART_DESKRIPSI; ?></td>
                                        <td class='table-nowarp'><?php echo $row->PART_REFERENCE; ?></td>
                                        <td><?php echo $row->KD_SUPPLIER; ?></td>
                                        <td><?php echo $row->KD_GROUPSALES; ?></td>
                                        <td><?php echo $row->PART_STATUS; ?></td>
                                        <td class='table-nowarp'><?php echo $row->PART_SUPERSEED; ?></td>
                                        <td class="text-right" ><?php echo number_format($row->HET, 0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->HARGA_BELI, 0); ?></td>
                                        <td><?php echo $row->MOQ_DK; ?></td>
                                        <td><?php echo $row->MOQ_DM; ?></td>
                                        <td><?php echo $row->MOQ_DB; ?></td>
                                        <td><?php echo $row->PART_NUMBERTYPE; ?></td>
                                        <td><?php echo $row->PART_MOVING; ?></td>
                                        <td><?php echo $row->PART_SOURCE; ?></td>
                                        <td><?php echo $row->PART_RANK; ?></td>
                                        <td><?php echo $row->PART_CURRENT; ?></td>
                                        <td><?php echo $row->PART_TYPE; ?></td>
                                        <td><?php echo $row->PART_LIFETIME; ?></td>
                                        <td><?php echo $row->PART_GROUP; ?></td>
                                        <!--<td><?php echo $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>-->
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(40);
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