<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_detail = ($this->input->get('status_download') == 0 && $list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
?>


<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

<!--            <a class="btn btn-default <?php echo $status_p; ?>" href="<?php echo base_url('laporan_customer/createfile_cddb'); ?>" role="button">
                <i class="fa fa-download fa-fw"></i> Download File .CDDB
            </a>-->

      <!--<a type="button" class="btn btn-default <?php echo $status_c ?>" href="<?php echo base_url('laporan_customer/createfile_cddb?n=' . $this->input->get('n') . ''); ?>" >
        <i class="fa fa-download fa-fw"></i> Download file .CDDB  <!-- <span class="caret"></span> 
      </a> -->

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Customer Database
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border">

                <form id="filterForm" action="<?php echo base_url('laporan_customer/customer_database') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('laporan_customer/customer_database_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">Customer Database</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan nomor mesin, nomor rangka, nama item, nama customer" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">


                                <label class="control-label" for="date">Tanggal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tanggal" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tanggal")) ? $this->input->get("tanggal") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                                </div>

                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label>Status Download</label>
                                <select id="status_download" name="status_download" class="form-control">
                                    <option value="0" <?php echo ($this->input->get('status_download') == 0 ? "selected" : ""); ?>>Belum Terdownload</option>
                                    <option value="1" <?php echo ($this->input->get('status_download') == 1 ? "selected" : ""); ?>>Sudah Terdownload</option>

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
                            <th>Nomor Mesin</th>
                            <th>Nomor Rangka</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Jenis Kelamin</th>
                            <th>Nomor Telepon</th>
                            <th>Alamat</th>
                            <th class="text-center">Status Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');

                        if ($list) :
                            if (is_array($list->message) || is_object($list->message)) :
                                foreach ($list->message as $key => $group_row) :
                                    $no ++;
                                    if ($group_row->NAMA_FILE != '' && $group_row->STATUS_DOWNLOAD == 1) {
                                        ?>

                                        <tr class="info bold">
                                            <td class="text-bold"><?php echo $no; ?></td>
                                            <td colspan="9"><?php echo $group_row->NAMA_FILE; ?></td>
                                            <td class="table-nowarp text-center" >
                                                <!--<a>Download</a>-->
<!--                                                <a class="<?php echo $status_v ?>" href="<?php echo base_url('laporan_customer/download/' . $group_row->NAMA_FILE) ?>"  >
                                                    <i data-toggle="tooltip" data-placement="left" title="Download" class="fa fa-download" ></i>
                                                </a>-->
                                            </td>
                                        </tr>

                                        <?php
                                        if ($detail) {
                                            if ($detail->totaldata > 0) {

                                                foreach ($detail->message as $row):
                                                    if ($group_row->NAMA_FILE == $row->NAMA_FILE):
                                                        ?>
                                                        <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                                            <td></td>
                                                            <!--<td><?php echo LongTgl(tglfromSql($row->TGL_SPK)); ?></td>-->
                                                            <td><?php echo $row->NO_MESIN; ?></td>
                                                            <td><?php echo $row->NO_RANGKA; ?></td>
                                                            <td><?php echo $row->KD_ITEM; ?></td>
                                                            <td><?php echo $row->NAMA_ITEM; ?></td>
                                                            <td><?php echo $row->KD_CUSTOMER; ?></td>
                                                            <td><?php echo $row->NAMA_CUSTOMER; ?></td>
                                                            <td><?php echo $row->JENIS_KELAMIN; ?></td>
                                                            <td><?php echo $row->NO_TELEPON; ?></td>
                                                            <td><?php echo $row->ALAMAT; ?></td>
                                                            <td class="text-center"><?php echo $row->STATUS_DOWNLOAD == 0 ? 'Belum' : 'Sudah'; ?></td>

                                                        </tr>

                                                        <?php
                                                    endif;
                                                endforeach;
                                            }
                                        }
                                    }
                                    else if ($group_row->STATUS_DOWNLOAD == 0) {
                                        $no2 = $this->input->get('page');
                                        if ($detail) {
                                            if ($detail->totaldata > 0) {
                                                foreach ($detail->message as $row):
                                                    $no2 ++;
                                                    ?>
                                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                                        <td class="text-bold"><?php echo $no2; ?></td>
                                                        <!--<td><?php echo LongTgl(tglfromSql($row->TGL_SPK)); ?></td>-->
                                                        <td><?php echo $row->NO_MESIN; ?></td>
                                                        <td><?php echo $row->NO_RANGKA; ?></td>
                                                        <td><?php echo $row->KD_ITEM; ?></td>
                                                        <td><?php echo $row->NAMA_ITEM; ?></td>
                                                        <td><?php echo $row->KD_CUSTOMER; ?></td>
                                                        <td><?php echo $row->NAMA_CUSTOMER; ?></td>
                                                        <td><?php echo $row->JENIS_KELAMIN; ?></td>
                                                        <td><?php echo $row->NO_TELEPON; ?></td>
                                                        <td><?php echo $row->ALAMAT; ?></td>
                                                        <td class="text-center"><?php echo $group_row->STATUS_DOWNLOAD == 0 ? 'Belum' : 'Sudah'; ?></td>
                                                    </tr>

                                                    <?php
                                                endforeach;
                                            }
                                        }
                                    }

                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="11"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            belumAdaData(12);
                        endif;
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