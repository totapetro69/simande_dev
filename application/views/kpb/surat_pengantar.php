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


    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Surat Pengantar
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('kpb/surat_pengantar') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('kpb/kpbvalidasi_typeahead?status_kpb=2'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Cari berdasarkan nomor mesin" autocomplete="off" value="<?php echo $this->input->get('keyword');?>">
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
                <table id="pkb_list" class="table table-striped table-bordered">
                    <thead>
                        <tr class="no-hover"><th colspan="15" ><i class="fa fa-list fa-fw"></i> List Surat</th></tr>
                        <tr>
                            <th rowspan="2" style="width:40px;">No.</th>
                            <th rowspan="2" style="width:45px;">Aksi</th>
                            <th colspan="11">No. Claim</th>
                        </tr>
                        <tr>
                            <th>Kode Main Dealer</th>
                            <th>Kode Dealer</th>
                            <th>No. PKB</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Tanggal Beli</th>
                            <th>Sequence</th>
                            <th>KM Service</th>
                            <th>Tanggal Service</th>
                            <th>Motor Luar</th>
                            <th>Buku Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $header) :
                                $no ++;
                        ?>

                                    <tr class="info bold">
                                        <td><?php echo  $no; ?></td>
                                        <td>
                                            <a class="active <?php echo $status_p?>" id="modal-button" onclick='addForm("<?php echo base_url('kpb/print_surat_baru?no_kpb='.$header->NO_KPB); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Print surat pengantar" ></i>
                                            </a>
                                        </td>
                                        <td colspan="11"><?php echo  $header->NO_KPB; ?></td>
                                    </tr>

                        <?php
                                foreach ($detail->message as $key => $row):
                                if($header->NO_KPB == $row->NO_KPB):
                                    ?>

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td></td>
                                        <td>
                                            
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('kpb/edit_kpb/'.$row->ID.'?status_kpb=1'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                                              <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                        </td>
                                        <!-- <td class="table-nowarp"> -->
                                        <!-- </td> -->
                                        <td><?php echo  $row->KD_MAINDEALER; ?></td>
                                        <td><?php echo  $row->KD_DEALER; ?></td>
                                        <td><?php echo  $row->NO_PKB; ?></td>
                                        <td><?php echo  $row->KD_MESIN.$row->NO_MESIN; ?></td>
                                        <td><?php echo  $row->NO_RANGKA; ?></td>
                                        <td><?php echo  $row->TGL_BELI; ?></td>
                                        <td><?php echo  $row->SEQUENCE; ?></td>
                                        <td><?php echo  $row->KM_SERVICE; ?></td>
                                        <td><?php echo  $row->TGL_SERVICE; ?></td>
                                        <td><?php echo  $row->MOTOR_LUAR == '*'? 'True':'False'; ?></td>
                                        <td><?php echo  $row->BUKU_BARU == ''? 'False':'True'; ?></td>
                                    </tr>

                                    <?php
                                endif;
                                endforeach;

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

<script type="text/javascript" src="<?php echo base_url("assets/js/external/kpb.js");?>"></script>
