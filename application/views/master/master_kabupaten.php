<?php
if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth');
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
            <a id="modal-button" class="btn btn-primary <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('company/add_kabupaten'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a>
        </div>
    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul fa-fw'></i> List Kabupaten
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('company/kabupaten') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('company/kabupaten_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>Field Pencarian</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Kode Kabupaten atau Nama Kabupaten" autocomplete="off">
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
<!--                            <th>Kode Provinsi</th>-->
                            <th>Kode Kabupaten</th>
                            <th>Nama Kabupaten</th>
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
                                            <a class="active" id="modal-button" ui-toggle-class="" onclick='addForm("<?php echo base_url('company/edit_kabupaten?kd_kabupaten=' . $row->KD_KABUPATEN . ''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo  $status_v ?>">
                                                <i title="Detail" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                            <a class="hidden">
                                                <i class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <!--<td class="table-nowarp"><?php echo $row->KD_PROPINSI; ?></td>-->
                                        <td class="table-nowarp"><?php echo $row->KD_KABUPATEN; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_KABUPATEN; ?></td>
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