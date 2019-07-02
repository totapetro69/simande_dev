<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$defaultDealer = ($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$dtgl =($this->input->get("d_tgl"))?$this->input->get("d_tgl"):date("d/m/Y",strtotime("first day of this month"));
$stgl =($this->input->get("s_tgl"))?$this->input->get("s_tgl"):date("d/m/Y");
$tp =($this->input->get('tp'))?$this->input->get("tp"):"0";
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('laporan/stock_barang?p=1&kd_dealer='.$defaultDealer.'&tgl_awal=' .$dtgl. '&tgl_akhir=' .$stgl.'&tp='.$tp); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Stock Barang" ></i> Print Report
            </a>    
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-list-ul"></i> Laporan Stock Barang
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form id="filterForm" action="<?php echo base_url('laporan/stock_barang') ?>" class="bucket-form" method="get">
                    <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label>Dealer</label>
                            <select name="kd_dealer" id="kd_dealer" class="form-control">
                                <option value=''>--Pilih Dealer--</option>
                                <?php 
                                    if(isset($dealer)){
                                        if($dealer->totaldata >0){
                                            foreach ($dealer->message as $key => $value) {
                                                $pilih = ($defaultDealer== $value->KD_DEALER)?'selected':'';
                                                echo "<option value='".$value->KD_DEALER."' ".$pilih.">".$value->NAMA_DEALER."</option>";
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class='col-xs-3 col-md-2 col-sm-2'>
                        <div class="form-group">
                            <label>Periode Dari</label>
                            <input type="text" name="d_tgl" class="form-control date" value="<?php echo $dtgl;?>">
                        </div>
                    </div>
                    <div class='col-xs-3 col-md-2 col-sm-2'>
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="text" name="s_tgl" class="form-control date" value="<?php echo $stgl;?>">
                        </div>
                    </div>
                    <div class='col-xs-3 col-md-2 col-sm-2'>
                        <div class="form-group">
                            <label>Filter view</label>
                            <select id="tp" name="tp" class="form-control">
                                <option value='0' <?php echo ($tp=='0')?'selected':'';?>>Total</option>
                                <option value="1" <?php echo ($tp=='1')?'selected':'';?>>Detail</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive h350">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr align="center">
                            <th style="width:5%">No.</th>
                            <th style="width:10%">Kode Barang</th>
                            <th style="width:25%">Nama Barang</th>
                            <?php if($tp=='1'){
                                echo "<th style='width:10%'> No. Trans</th>
                                <th style='width:8%'>Tanggal</th>";
                            }
                            ?>
                            <th style="width:10%">Stock Awal</th>
                            <th style="width:10%">Terima</th>
                            <th style="width:10%">Keluar</th>
                            <th style="width:10%">Stock Akhir</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if (isset($list)){
                            if ($list->totaldata >0){
                                foreach ($list->message as $key => $row){
                                    $no ++;
                                    ?>
                                    <tr>
                                        <td class='text-center'><?php echo $no; ?></td>
                                        <td class='text-center'><?php echo $row->KD_BARANG; ?></td>
                                        <td class='td-overflow' title="<?php echo $row->NAMA_BARANG; ?>"><?php echo $row->NAMA_BARANG; ?></td>
                                        <?php if($tp=='1'){
                                            echo "<td class='table-nowarp text-center'>".$row->NO_TRANS."</td>
                                            <td class='table-nowarp text-center'>".tglFromSql($row->TGL_TRANS)."</td>";
                                        }
                                        ?>
                                        <td class='text-right'><?php echo number_format($row->SALDO_AWAL,0); ?></td>
                                        <td class='text-right'><?php echo number_format($row->TERIMA,0); ?></td>
                                        <td class='text-right'><?php echo number_format($row->KELUAR,0); ?></td>
                                        <td class='text-right'><?php echo number_format($row->SALDO_AKHIR,0); ?></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                }
                            }
                        }else{
                            echo belumAdaData(7);
                        }
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