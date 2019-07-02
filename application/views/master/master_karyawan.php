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
            <?php if ($this->session->userdata('status_cabang') == 'Y') { ?>
            <a id="modal-button" class="btn btn-primary <?php echo $status_c ?>" onclick='addForm("<?php echo base_url('company/add_karyawan'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a>

            <?php }
            elseif ($this->session->userdata('kd_group') == 'root') { ?>
               <a id="modal-button" class="btn btn-primary <?php echo $status_c ?> hidden" onclick='addForm("<?php echo base_url('company/add_karyawan'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a><?php
            }
             else { ?>
            <a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('company/tambah_karyawan'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>
            <?php } ?>

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class="fa fa-list-ul"></i> Karyawan
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('company/karyawan') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('company/karyawan_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>NIK atau Nama Karyawan</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $this->input->get("keyword") ?>" placeholder="Masukkan NIK atau Nama Karyawan" autocomplete="off">
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
                            <?php if ($this->session->userdata('status_cabang') == 'T') { ?>
                            <th style="width: 45px">Aksi</th>
                            <?php } ?>
                            <th>NIK</th>
                            <th>Nama </th>
                            <th>Kode Status</th>
                            <th>Kode Perusahaan</th>
                            <th>Kode Cabang </th>
                            <th>Kode Divisi</th>
                            <th>Kode Jabatan</th>
                            <th>Personal Jabatan </th>
                            <th>Personal Level</th>
                            <th>Tgl Lahir</th>
                            <th>Pendidikan</th>
                            <th>Tgl Masuk</th>
                            <th>Atasan Langsung </th>
<!--                            <th>Kode Sales</th>
    <th>Kode HSales</th>-->
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

                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                    <td class="table-nowarp"><?php echo $no; ?></td>
                    <?php if ($this->session->userdata('status_cabang') == 'T') { ?>
                    <td class="table-nowarp">
                        <a id="modal-button" onclick='addForm("<?php echo base_url('company/edit_karyawan/' . $row->NIK . '/' . $row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                            <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                        </a>
                        <?php if ($row->ROW_STATUS == 0) { ?>
                        <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('company/delete_karyawan/' . $row->NIK); ?>">
                            <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                        </a>
                        <?php } ?>
                    </td>
                    <?php } ?>

                    <td class="table-nowarp"><?php echo $row->NIK; ?></td>
                    <td class="table-nowarp"><?php echo $row->NAMA; ?></td>
                    <td class="table-nowarp"><?php echo $row->KD_STATUS; ?></td>
                    <td class="table-nowarp"><?php echo $row->KD_PERUSAHAAN; ?></td>
                    <td class="table-nowarp"><?php echo $row->KD_CABANG; ?></td>
                    <td class="table-nowarp"><?php echo $row->KD_DIVISI; ?></td>
                    <td class="table-nowarp"><?php echo $row->KD_JABATAN; ?></td>
                    <td class="table-nowarp"><?php echo $row->PERSONAL_JABATAN; ?></td>
                    <td class="table-nowarp"><?php echo $row->PERSONAL_LEVEL; ?></td>
                    <td class="table-nowarp"><?php echo tglfromsql($row->TGL_LAHIR); ?></td>
                    <td class="table-nowarp"><?php echo $row->PENDIDIKAN; ?></td>
                    <td class="table-nowarp"><?php echo tglfromsql($row->TGL_MASUK); ?></td>
                    <?php if ($row->ATASAN_LANGSUNG != '') { ?>
                    <td class="table-nowarp">(<?php echo $row->ATASAN_LANGSUNG; ?>) <?php echo $row->NAMA_ATASAN; ?></td>
                    <?php } else { ?>
                    <td class="table-nowarp"></td>
                    <?php } ?>

<!--                                        <td><?php echo $row->KD_SALES ?></td>
    <td><?php echo $row->KD_HSALES ?></td>-->
</tr>

<?php
endforeach;
else:
    ?>
    <tr>
        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
        <td colspan="8"><b><?php echo ($list->message); ?></b></td>
    </tr>
    <?php
endif;
else:
    ?>
    <tr>
        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
        <td colspan="8"><b>ada error, harap hubungi bagian IT</b></td>
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