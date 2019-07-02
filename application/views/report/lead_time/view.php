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

    <div class="col-lg-12 padding-left-right-10 ">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Report Lead Time Part
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" >
                <form id="filterFormz" action="<?php echo base_url('laporan/lead_time') ?>" class="bucket-form" method="get">
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
                                          //$aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
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
                                            $pilih=(date("m")==$i)?"selected":"";
                                            $pilih=((int)$this->input->get("bulan")==$i)?"selected":"";
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
                                    <option value="0" <?php echo ($tipe == 0 ? "selected" : ""); ?>>PO Dealer ke MD</option>
                                    <option value="1") <?php echo ($tipe == 1 ? "selected" : ""); ?>>Booking Customer</option>
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

    <div class="col-lg-12 padding-left-right-10 ">
        <div class="panel panel-default">
            <div class="table-responsive h250">
                <?php

                if ($tipe == "0") {
                    ?>
                    <table class="table table-striped b-t b-light" id="tbl_lst">
                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Nomor PO</th>
                                <th>PO Start</th>
                                <th>PO Close</th>
                                <th>Lama (jam)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no =0;
                            if (isset($list)):
                                if ($list->totaldata>0):
                                    foreach ($list->message as $key => $row):
                                        $no ++;
                                        $tgl_po=strtotime($row->TGL_PO);
                                        $tgl_terima=strtotime($row->TGL_TERIMA);
                                        ?>
                                        
                                        <tr id="l_<?php echo $no; ?>" title='Click for detail item part'>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $row->NO_PO; ?><span class="pull-right"><i class="fa fa-chevron-down"></i></span></td>
                                            <td><?php echo date( 'd/m/Y H:i',$tgl_po); ?></td>
                                            <td><?php echo((date('Y',$tgl_terima)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terima):""; ?></td>
                                            <td><?php echo number_format($row->LEADTIME,0);?></td>
                                        </tr>

                                        <?php
                                        if(isset($listd)){
                                            if(isset($listd[$row->NO_PO])){
                                                $x=0;
                                                if($listd[$row->NO_PO]->totaldata>0){
                                                    foreach ($listd[$row->NO_PO]->message as $key => $value) {
                                                        $x++;
                                                        $tgl_pod=strtotime($value->TGL_PO);
                                                        $tgl_terimad=strtotime($value->TGL_TERIMA);
                                                        ?>
                                                        <tr class="l_<?php echo $no;?> hidden">
                                                            <td align="right" style="padding-right: 5px"><?php echo $x; ?></td>
                                                            <td><?php echo $value->PART_NUMBER ." - ". PartName($value->PART_NUMBER); ?></td>
                                                            <td><?php echo date( 'd/m/Y H:i',$tgl_pod); ?></td>
                                                            <td><?php echo((date('Y',$tgl_terimad)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terimad):""; ?></td>
                                                            <td><?php echo number_format($value->LEADTIME,0);?></td>
                                                        </tr>
                                                        <?
                                                    }
                                                }
                                            }
                                        }
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
                                    <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>

                    <?php
                }elseif ($tipe == 1) {
                    ?>

                    <table class="table table-striped b-t b-light" id="lst_so">
                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Nomor SO</th>
                                <th>SO Start</th>
                                <th>SO Close</th>
                                <th>Lama (jam)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no =0;
                            if (isset($list)):
                                if ($list->totaldata>0):
                                    foreach ($list->message as $key => $row):
                                        $no ++;
                                        $tgl_po=strtotime($row->TGL_PO);
                                        $tgl_terima=strtotime($row->TGL_TERIMA);
                                        ?>
                                        
                                        <tr id="so_<?php echo $no; ?>" title='Click for detail item part'>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $row->NO_PO; ?><span class="pull-right"><i class="fa fa-chevron-down"></i></span></td>
                                            <td><?php echo date( 'd/m/Y H:i',$tgl_po); ?></td>
                                            <td><?php echo((date('Y',$tgl_terima)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terima):""; ?></td>
                                            <td><?php echo number_format($row->LEADTIME,0);?></td>
                                        </tr>

                                        <?php
                                        if(isset($listd)){
                                            if(isset($listd[$row->NO_PO])){
                                                $x=0;
                                                if($listd[$row->NO_PO]->totaldata>0){
                                                    foreach ($listd[$row->NO_PO]->message as $key => $value) {
                                                        $x++;
                                                        $tgl_pod=strtotime($value->TGL_PO);
                                                        $tgl_terimad=strtotime($value->TGL_TERIMA);
                                                        ?>
                                                        <tr class="so_<?php echo $no;?> hidden">
                                                            <td align="right" style="padding-right: 5px"><?php echo $x; ?></td>
                                                            <td><?php echo $value->PART_NUMBER ." - ". PartName($value->PART_NUMBER); ?></td>
                                                            <td><?php echo date( 'd/m/Y H:i',$tgl_pod); ?></td>
                                                            <td><?php echo((date('Y',$tgl_terimad)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terimad):""; ?></td>
                                                            <td><?php echo number_format($value->LEADTIME,0);?></td>
                                                        </tr>
                                                        <?
                                                    }
                                                }
                                            }
                                        }
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
                                    <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
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
                            <td style="width:40%" align="center" valign="middle"><h4>LAPORAN PEMENUHAN DAN LEADTIME PO</h4></td>
                            <td style="width:15%; white-space: nowrap;" valign="top">Tanggal Cetak </td>
                            <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo date('d/m/Y');?></td>
                        </tr>
                        <tr><td></td><td align="center" valign="middle"><?php switch ($this->input->get("tp")) {
                                    case '0':
                                       echo "PO Dealer To Main Dealer";
                                        break;
                                    case '1':
                                       echo "PO Booking To Customer";
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
                    <?php
                        if ($tipe == "0") {
                            ?>
                            <table style="width: 100%; border-collapse: collapse;" id="tbl_lst" border="1">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">No.</th>
                                        <th>Nomor PO</th>
                                        <th>PO Start</th>
                                        <th>PO Close</th>
                                        <th>Lama (jam)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no =0;
                                    if (isset($list)):
                                        if ($list->totaldata>0):
                                            foreach ($list->message as $key => $row):
                                                $no ++;
                                                $tgl_po=strtotime($row->TGL_PO);
                                                $tgl_terima=strtotime($row->TGL_TERIMA);
                                                ?>
                                                
                                                <tr style="background-color: silver;" title='Click for detail item part'>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $row->NO_PO; ?><span class="pull-right hidden"><i class="fa fa-chevron-down"></i></span></td>
                                                    <td align="center"><?php echo date( 'd/m/Y H:i',$tgl_po); ?></td>
                                                    <td align="center"><?php echo((date('Y',$tgl_terima)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terima):""; ?></td>
                                                    <td align="right" style="padding-right: 5px"><b><?php echo number_format($row->LEADTIME,0);?></b></td>
                                                </tr>

                                                <?php
                                                if(isset($listd)){
                                                    if(isset($listd[$row->NO_PO])){
                                                        $x=0;
                                                        if($listd[$row->NO_PO]->totaldata>0){
                                                            foreach ($listd[$row->NO_PO]->message as $key => $value) {
                                                                $x++;
                                                                $tgl_pod=strtotime($value->TGL_PO);
                                                                $tgl_terimad=strtotime($value->TGL_TERIMA);
                                                                ?>
                                                                <tr class="l_<?php echo $no;?>">
                                                                    <td align="right" style="padding-right: 5px"><?php echo $x; ?></td>
                                                                    <td><?php echo $value->PART_NUMBER ." - ". PartName($value->PART_NUMBER); ?></td>
                                                                    <td align="center"><?php echo date( 'd/m/Y H:i',$tgl_pod); ?></td>
                                                                    <td align="center"><?php echo((date('Y',$tgl_terimad)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terimad):""; ?></td>
                                                                    <td align="right" style="padding-right: 5px"><em><?php echo number_format($value->LEADTIME,0);?></em></td>
                                                                </tr>
                                                                <?
                                                            }
                                                        }
                                                    }
                                                }
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
                                            <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                                        </tr>
                                    <?php
                                    endif;
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        }elseif ($tipe == 1) {
                            ?>

                            <table style="width:100%; border-collapse: collapse;" border="1" >
                                <thead>
                                    <tr>
                                        <th style="width:40px;">No.</th>
                                        <th>Nomor SO</th>
                                        <th>SO Start</th>
                                        <th>SO Close</th>
                                        <th>Lama (jam)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no =0;
                                    if (isset($list)):
                                        if ($list->totaldata>0):
                                            foreach ($list->message as $key => $row):
                                                $no ++;
                                                $tgl_po=strtotime($row->TGL_PO);
                                                $tgl_terima=strtotime($row->TGL_TERIMA);
                                                ?>
                                                
                                                <tr style="background-color: silver " title='Click for detail item part'>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $row->NO_PO; ?><span class="pull-right hidden"><i class="fa fa-chevron-down"></i></span></td>
                                                    <td align="center"><?php echo date( 'd/m/Y H:i',$tgl_po); ?></td>
                                                    <td align="center"><?php echo((date('Y',$tgl_terima)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terima):""; ?></td>
                                                    <td align="right" style="padding-right: 5px"><b><?php echo number_format($row->LEADTIME,0);?></b></td>
                                                </tr>

                                                <?php
                                                if(isset($listd)){
                                                    if(isset($listd[$row->NO_PO])){
                                                        $x=0;
                                                        if($listd[$row->NO_PO]->totaldata>0){
                                                            foreach ($listd[$row->NO_PO]->message as $key => $value) {
                                                                $x++;
                                                                $tgl_pod=strtotime($value->TGL_PO);
                                                                $tgl_terimad=strtotime($value->TGL_TERIMA);
                                                                ?>
                                                                <tr class="so_<?php echo $no;?>">
                                                                    <td align="right" style="padding-right: 5px"><?php echo $x; ?></td>
                                                                    <td><?php echo $value->PART_NUMBER ." - ". PartName($value->PART_NUMBER); ?></td>
                                                                    <td align="center"><?php echo date( 'd/m/Y H:i',$tgl_pod); ?></td>
                                                                    <td align="center"><?php echo((date('Y',$tgl_terimad)>=date('Y')))? date( 'd/m/Y H:i',$tgl_terimad):""; ?></td>
                                                                    <td align="right" style="padding-right: 5px"><em><?php echo number_format($value->LEADTIME,0);?></em></td>
                                                                </tr>
                                                                <?
                                                            }
                                                        }
                                                    }
                                                }
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
                                            <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                                        </tr>
                                    <?php
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</section>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tbl_lst tr').on('click',function(){
            var id=$(this).attr("id");
            // console.log($(this))
            if ($("#tbl_lst tr."+id).hasClass("hidden")){
                $("#tbl_lst tr."+id).removeClass("hidden");
            }else{
                $("#tbl_lst tr."+id).addClass("hidden");
            }
        })
        $('#lst_so tr').on('click',function(){
            var id=$(this).attr("id");
            // console.log($(this))
            if ($("#lst_so tr."+id).hasClass("hidden")){
                $("#lst_so tr."+id).removeClass("hidden");
            }else{
                $("#lst_so tr."+id).addClass("hidden");
            }
        })
    })
     function printKw() {
            printJS({ printable: 'printarea', type: 'html', honorColor: true });
            //$('#keluar').click();
        }
</script>