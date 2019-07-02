<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );



/*var_dump($data);
exit;*/

?>

<section class="wrapper">


    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a id="validasi-btn" class="btn btn-default" role="button">
                <i class="fa fa-check-square-o fa-fw"></i> Validasi
            </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Validasi KPB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border">

                <form id="filterForm" action="<?php echo base_url('kpb/validasi_kpb') ?>" class="bucket-form" method="get">

                    <input type="hidden" id="status_cabang" name="status_cabang" class="form-control" value="<?php echo $status_cabang ;?>">
                    <input type="hidden" id="month_interval" name="month_interval" class="form-control" value="<?php echo $month_interval ;?>">
                    <input type="hidden" id="month_end" name="month_end" class="form-control" value="<?php echo $month_end ;?>">
                    <input type="hidden" id="month_start" name="month_start" class="form-control" value="<?php echo $month_start ;?>">


                    <input type="hidden" id="tahun_docno" name="tahun_docno" class="form-control" value="<?php echo date('d/m/Y') ;?>">

                    <div id="ajax-url" url="<?php echo base_url('kpb/kpbvalidasi_typeahead?status_kpb=0&tgl_awal='.$this->input->get('tgl_awal').'&tgl_akhir='.$this->input->get('tgl_akhir'));?>"></div>
                    
                    <div class="row">

                        <div class="col-xs-12 col-sm-4">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="cari berdasarkan nomor mesin" autocomplete="off">

                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-3 col-sm-offset-1">

                            <div class="form-group">
                                <label>Tanggal Awal Tarik KPB</label>

                                <div class="input-group input-append "><!-- date -->
                                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $tgl_periode_awal; ?>" type="text" disabled="disabled"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>

                        </div>



                        <div class="col-xs-12 col-sm-4">

                            <div class="form-group">
                                <label>Tanggal Akhir Tarik KPB</label>
                                <div class="form-inline">
                                    
                                    <div class="input-group input-append " style="width: 65%;"><!-- date -->
                                        <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $tgl_periode_akhir; ?>" type="text" disabled="disabled"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>

                                    <a id="pull-btn" type="button" class="btn btn-primary pull-right <?php echo $status_c; ?>" style="width: 32%;">  
                                        <i class="fa fa-download fa-fw"></i> Tarik Data
                                    </a>

                                    <!-- <a id="pull-btn" onclick="pullData();" class="btn btn-primary pull-right">Tarik Data</a> -->
                                </div>
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
                        <tr class="no-hover"><th colspan="15" ><i class="fa fa-list fa-fw"></i> List KPB</th></tr>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th style="width:45px;"><input id="kpb_all" class="kpb_all" name="kpb_all" value="1" type="checkbox"></th>
                            <th style="width:45px;">Aksi</th>
                            <th>Kode Main Dealer</th>
                            <th>Kode Dealer</th>
                            <th>No. KPB</th>
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
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    ?>

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo  $no; ?></td>
                                        <td><input class="kpb_checked kpb_checked<?php echo $row->ID;?>" name="" value="<?php echo $row->ID;?>" type="checkbox"></td>
                                        <td>
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('kpb/edit_kpb/'.$row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                                              <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                        </td>
                                        <!-- <td class="table-nowarp"> -->
                                        <!-- </td> -->
                                        <td><?php echo  $row->KD_MAINDEALER; ?></td>
                                        <td><?php echo  $row->KD_DEALER; ?></td>
                                        <td><?php echo  $row->NO_KPB; ?></td>
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
<!-- 
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

            </footer> -->

        </div>

    </div>

    <?php echo loading_proses(); ?>

</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/kpb.js");?>"></script>
