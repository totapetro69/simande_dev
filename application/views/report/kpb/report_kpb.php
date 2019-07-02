<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");

/*var_dump($data);
exit;*/

?>

<section class="wrapper">
    

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('report/cetak_kpb?keyword='.$this->input->get('keyword').'&tgl_awal='.$this->input->get("tgl_awal").'&tgl_akhir='.$this->input->get("tgl_akhir")); ?>" target="_blank">
              <i class="fa fa-print fa-fw"></i> Cetak
            </a>
            <!-- <a id="validasi-btn" class="btn btn-default" role="button">
                <i class="fa fa-check-square-o fa-fw"></i> Validasi
            </a> -->

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Laporan KPB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border">

                <form id="filterForm" action="<?php echo base_url('report/laporan_kbp') ?>" class="bucket-form" method="get">

                    <input type="hidden" id="tahun_docno" name="tahun_docno" class="form-control" value="<?php echo date('d/m/Y') ;?>">

                    <div id="ajax-url" url="<?php echo base_url('kpb/kpbvalidasi_typeahead');?>"></div>
                    
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                      if (($dealer->totaldata > 0)) {
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

                        <div class="col-xs-12 col-sm-3">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="cari berdasarkan nama kendaraan dan nomor mesin" autocomplete="off">

                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-3">

                            <div class="form-group">
                                <label>Tanggal Awal</label>

                                <div class="input-group input-append date"><!-- date -->
                                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>

                        </div>



                        <div class="col-xs-12 col-sm-3">

                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                    
                                <div class="input-group input-append date"><!-- date -->
                                    <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                                    <!-- <a id="pull-btn" onclick="pullData();" class="btn btn-primary pull-right">Tarik Data</a> -->
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
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $row):
                                    $no ++;

                                    switch ($row->STATUS_KPB) {
                                        case 1:
                                            $status = 'Sudah divalidasi';
                                            break;
                                        case 2:
                                            $status = 'Sudah diclaim';
                                            break;
                                        
                                        default:
                                            $status = 'Belum divalidasi';
                                            break;
                                    }
                                    ?>

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo  $no; ?></td>
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
                                        <td><?php echo  $status; ?></td>
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
