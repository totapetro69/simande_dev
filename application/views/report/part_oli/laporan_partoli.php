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

            <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('report_penjualan/cetak_partoli?kd_dealer='.$this->input->get("kd_dealer").'&jenis_item='.$this->input->get("jenis_item").'&tgl_awal='.$this->input->get("tgl_awal").'&tgl_akhir='.$this->input->get("tgl_akhir")); ?>" target="_blank">
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
                <i class="fa fa-list fa-fw"></i> Laporan Penjualan Part dan Oli
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border">

                <form id="filterForm" action="<?php echo base_url('report_penjualan/penjualan_partoli') ?>" class="bucket-form" method="get">

                    <input type="hidden" id="tahun_docno" name="tahun_docno" class="form-control" value="<?php echo date('d/m/Y') ;?>">

                    <div id="ajax-url" url="<?php echo base_url('kpb/kpbvalidasi_typeahead');?>"></div>
                    
                    <div class="row">


                        <div class="col-xs-6 col-sm-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select id="kd_dealer" name="kd_dealer" class="form-control">
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata > 0) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                                $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-2">

                            <div class="form-group">
                                <label>Jenis Item</label>
                                
                                <select id="jenis_item" name="jenis_item" class="form-control">
                                    <option value="PART" <?php echo ($this->input->get("jenis_item") == 'PART' ? "selected" : '');?>>PART</option>
                                    <option value="OLI" <?php echo ($this->input->get("jenis_item") == 'OLI' ? "selected" : '');?>>OLI</option>
                                </select>

                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-3 col-sm-offset-1">

                            <div class="form-group">
                                <label>Periode Awal</label>

                                <div class="input-group input-append date"><!-- date -->
                                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>

                        </div>



                        <div class="col-xs-12 col-sm-3">

                            <div class="form-group">
                                <label>Periode Akhir</label>
                                    
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
                        <tr class="no-hover"><th colspan="15" ><i class="fa fa-list fa-fw"></i> List Penjualan Part dan Oli</th></tr>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Nomor Penjualan</th>
                            <th>Tanggal</th>
                            <th>No. Part</th>
                            <th>Deskripsi Part</th>
                            <th>Qty</th>
                            <th>% Disc</th>
                            <th>Rp Disc</th>
                            <th>Harga Jual</th>
                            <th>Diskon</th>
                            <th>Harga Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if (isset($list)):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    $disc_percent = ($row->DISKON * 100)/($row->QTY * $row->HARGA_SATUAN);
                                    
                                    ?>

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo  $no; ?></td>
                                        <!-- <td class="table-nowarp"> -->
                                        <!-- </td> -->
                                        <td><?php echo  $row->NO_PKB; ?></td>
                                        <td><?php echo  tglfromSql($row->TANGGAL_PKB); ?></td>
                                        <td><?php echo  $row->KD_PEKERJAAN; ?></td>
                                        <td><?php echo  $row->PART_DESKRIPSI; ?></td>
                                        <td class="text-center"><?php echo  number_format($row->QTY,0); ?></td>
                                        <td class="text-center"><?php echo  $disc_percent; ?></td>
                                        <td class="text-right"><?php echo  number_format($row->DISKON,0); ?></td>
                                        <td class="text-right"><?php echo  number_format($row->HARGA_SATUAN,0); ?></td>
                                        <td class="text-center"><?php echo  0; ?></td>
                                        <td class="text-right"><?php echo  number_format($row->TOTAL_HARGA,0); ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                echo belumAdaData(11);
                                
                            endif;
                        else:
                            echo belumAdaData(11);
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

    <?php echo loading_proses(); ?>

</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/kpb.js");?>"></script>
