<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$tipe=($this->input->get("tp"))?$this->input->get("tp"):"0";
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$periodelap=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");
$tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
$periodelap=nBulan($periodelap)." ".$tahun;
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right">
            <a class="btn btn-default" onclick='printKw();' role="button">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Lead Time" ></i> Print Report
            </a>

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Laporan Back Order
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" >
                <form id="filterFormz" action="<?php echo base_url('laporan/back_order') ?>" class="bucket-form" method="get">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
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
                        <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan</option>
                                    <?php 
                                        for($i=1;$i<=12; $i++){
                                            $pilih=(date("m")===$i)?"selected":"";
                                            $pilih=((int)$this->input->get("bulan")===$i)?"selected":$pilih;
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">--Pilih Tahun</option>
                                    <?php 
                                        if(isset($tahun)){
                                            if($tahun->totaldata>0){
                                                foreach ($tahun->message as $key => $value) {
                                                    $pilih=(date("Y")==$value->TAHUN)?"selected":"";
                                                    $pilih=($this->input->get("tahun")==$value->TAHUN)?"selected":$pilih;
                                                    echo "<option value='".$value->TAHUN."' $pilih>".$value->TAHUN."</option>";
                                                }
                                            }else{
                                                echo "<option value='".date("Y")."' selected>".date("Y")."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-2 col-md-2 ">
                            <div class="form-group">
                                <label>Lead Time by</label>
                                <select id="tp" name="tp" class="form-control">
                                    <option value="0" <?php echo ($tipe == 0 ? "selected" : ""); ?>>Out Standing PO</option>
                                    <option value="1") <?php echo ($tipe == 1 ? "selected" : ""); ?>>Out Standing SO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-1 col-sm-1">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 padding-left-right-10">
    <div class="panel panel-default">
        <div class="table-responsive h250">
            
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Part Number</th>
                        <th>Part Deskripsi</th>
                        <th>Jumlah Order</th>
                        <th>Jumlah Supply</th>
                        <th>Out Standing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    if (isset($list)):
                        if ($list->totaldata):
                            foreach ($list->message as $key => $row):
                                $no ++;
                                ?>

                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row->PART_NUMBER ?></td>
                                    <td><?php echo $row->PART_DESKRIPSI ?></td>
                                    <td><?php echo $row->JUMLAH_ORDER ?></td>
                                    <td><?php echo $row->JUMLAH_SUPPLY;?></td>
                                    <td><?php echo $row->SISA;?></td>
                                </tr>

                                <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="6"><b><?php echo ($list->message); ?></b></td>
                            </tr>
                        <?php
                        endif;
                    else:
                        ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="6"><b>Ada error, harap hubungi bagian IT</b></td>
                        </tr>
                    <?php
                    endif;
                    ?>
                </tbody>
            </table>
        </div>

        <footer class="panel-footer">
            <div class="row">

                
            </div>
        </footer>
    </div>
</div>
<div id="printarea" style="height: 0.5px; overflow: hidden;width: 100% !important">
    <table style="width:100%; border-collapse: collapse;" border="0">
        <tr>
            <td style="width:100%; padding: 5px">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width:10%;" valign="top"><h4><?php echo $namadealer;?></h4></td>
                        <td style="width:40%" align="center" valign="middle"><h4>LAPORAN BACK ORDER</h4></td>
                        <td style="width:15%; white-space: nowrap;" valign="top">Tanggal Cetak </td>
                        <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo date('d/m/Y');?></td>
                    </tr>
                    <tr><td>&nbsp;</td><td align="center" valign="middle"><?php switch ($this->input->get("tp")) {
                                case '0':
                                   echo "Out Standing PO Main Dealer";
                                    break;
                                case '1':
                                   echo "Out Standing SO Dealer";
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                            ?></td>
                        <td style="width:15%; white-space: nowrap;" valign="top">Periode Laporan </td>
                        <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo $periodelap;?></td>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding: 5px;">
                <table style="width:100%; border-collapse: collapse;" border="1">
            <thead>
                <tr>
                    <th style="width:40px;">No.</th>
                    <th>Part Number</th>
                    <th>Part Deskripsi</th>
                    <th>Jumlah Order</th>
                    <th>Jumlah Supply</th>
                    <th>Out Standing</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                if (isset($list)):
                    if ($list->totaldata):
                        foreach ($list->message as $key => $row):
                            $no ++;
                            ?>

                            <tr>
                                <td align="center"><?php echo $no; ?></td>
                                <td><?php echo $row->PART_NUMBER ?></td>
                                <td style="white-space: nowrap;"><?php echo $row->PART_DESKRIPSI ?></td>
                                <td align="right" style="padding-right: 5px"><?php echo number_format($row->JUMLAH_ORDER,0); ?></td>
                                <td align="right" style="padding-right: 5px"><?php echo number_format($row->JUMLAH_SUPPLY,0);?></td>
                                <td align="right" style="padding-right: 5px"><?php echo number_format($row->SISA,0);?></td>
                            </tr>

                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="6"><b><?php echo ($list->message); ?></b></td>
                        </tr>
                    <?php
                    endif;
                else:
                    ?>
                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="6"><b>Ada error, harap hubungi bagian IT</b></td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
            </td>
        </tr>
    </table>
</div>
</section>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
    
    function printKw() {
        printJS({ printable: 'printarea', type: 'html', honorColor: true });
    }
</script>   