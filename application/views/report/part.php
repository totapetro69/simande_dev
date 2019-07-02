<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-default  <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/part_print?keyword=' . $this->input->get("keyword") . '&row_status=' . $this->input->get("row_status") . '&part_rank=' . $this->input->get("part_rank") . '&part_current=' . $this->input->get("part_current") . '&part_moving=' . $this->input->get("part_moving") . '&part_group=' . $this->input->get("part_group") . '&part_source=' . $this->input->get("part_source"). '&part_superseed=' . $this->input->get("part_superseed") . '&page='. $this->input->get("page")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Part" ></i> Cetak
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

            <div class="panel-body panel-body-border" >

                <form id="filterFormz" action="<?php echo base_url('report/part') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('report/part_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-8">

                            <div class="form-group">
                                <label>Part Number, Part Deskripsi atau HET</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword') ?>" class="form-control" placeholder="Masukkan Part Number, Part Deskripsi atau HET" autocomplete="off">
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

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Part Superseed</label>
                                <select id="part_superseed" name="part_superseed" class="form-control">
                                    <option value="" <?php echo ($this->input->get('part_superseed') == "" ? "selected" : ""); ?>>-</option>
                                    <option value="notsuperseed" <?php echo ($this->input->get('part_superseed') == "notsuperseed" ? "selected" : ""); ?>>Not Superseed</option>
                                    <option value="superseed" <?php echo ($this->input->get('part_superseed') == "superseed" ? "selected" : ""); ?>>Superseed</option>

                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Part Rank</label>
                                <select id="part_rank" name="part_rank" class="form-control">
                                    <option value="" <?php echo ($this->input->get('part_rank') == "" ? "selected" : ""); ?>>-</option>
                                    <option value="A" <?php echo ($this->input->get('part_rank') == "A" ? "selected" : ""); ?>>A</option>
                                    <option value="B" <?php echo ($this->input->get('part_rank') == "B" ? "selected" : ""); ?>>B</option>
                                    <option value="C" <?php echo ($this->input->get('part_rank') == "C" ? "selected" : ""); ?>>C</option>
                                    <option value="D" <?php echo ($this->input->get('part_rank') == "D" ? "selected" : ""); ?>>D</option>
                                    <option value="E" <?php echo ($this->input->get('part_rank') == "E" ? "selected" : ""); ?>>E</option>
                                    <option value="F" <?php echo ($this->input->get('part_rank') == "F" ? "selected" : ""); ?>>F</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Part Current</label>
                                <select id="part_current" name="part_current" class="form-control">
                                    <option value="" <?php echo ($this->input->get('part_current') == "" ? "selected" : ""); ?>>-</option>
                                    <option value="C" <?php echo ($this->input->get('part_current') == "C" ? "selected" : ""); ?>>Current</option>
                                    <option value="N" <?php echo ($this->input->get('part_current') == "N" ? "selected" : ""); ?>>Non Current</option>
                                    <option value="O" <?php echo ($this->input->get('part_current') == "O" ? "selected" : ""); ?>>Others</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Part Moving</label>
                                <select id="part_moving" name="part_moving" class="form-control">
                                    <option value="" <?php echo ($this->input->get('part_moving') == "" ? "selected" : ""); ?>>-</option>
                                    <option value="F" <?php echo ($this->input->get('part_moving') == "F" ? "selected" : ""); ?>>Fast</option>
                                    <option value="S" <?php echo ($this->input->get('part_moving') == "S" ? "selected" : ""); ?>>Slow</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Part Group</label>
                                <select id="part_group" name="part_group" class="form-control">
                                    <option value="" <?php echo ($this->input->get('part_group') == "" ? "selected" : ""); ?>>-</option>
                                    <option value="E" <?php echo ($this->input->get('part_group') == "E" ? "selected" : ""); ?>>Engine</option>
                                    <option value="EL" <?php echo ($this->input->get('part_group') == "EL" ? "selected" : ""); ?>>Electrical</option>
                                    <option value="F" <?php echo ($this->input->get('part_group') == "F" ? "selected" : ""); ?>>Frame</option>
                                    <option value="O" <?php echo ($this->input->get('part_group') == "O" ? "selected" : ""); ?>>Others</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Part Source</label>
                                <select id="part_source" name="part_source" class="form-control">
                                    <option value="" <?php echo ($this->input->get('part_source') == "" ? "selected" : ""); ?>>-</option>
                                    <option value="N" <?php echo ($this->input->get('part_source') == "N" ? "selected" : ""); ?>>Lokal</option>
                                    <option value="Y" <?php echo ($this->input->get('part_source') == "Y" ? "selected" : ""); ?>>Import</option>
                                </select>
                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <div><button id="submit-btn" onclick="addData();" class="btn btn-primary pull-right"><i class='fa fa-search'></i> Preview</button></div>
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->PART_NUMBER ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        
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
                                        <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
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
                            echo belumAdaData(22);
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