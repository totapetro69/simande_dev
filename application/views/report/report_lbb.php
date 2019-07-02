<?php
     $dari_tgl =($this->input->get("tgl_trans"))?$this->input->get("tgl_trans"):date("d/m/Y",strtotime("-1 Days"));
     $no_trx=$this->input->get("n");
     $disable=($no_trx)?"":"disabled-action";
     $defaulD=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
     $bulan= ($this->input->get('bulan'))?$this->input->get('bulan'):date("m");
     $tahuns= ($this->input->get("tahun"))?$this->input->get("tahun"):date('Y');
     $defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a href="<?php echo str_replace("report_lbb","createlbb_file",$_SERVER["REQUEST_URI"]);?>" class="btn btn-default"><i class="fa fa-download"></i> Download file .SDLBB</a>
            <a onclick="printKw();" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>
    <fieldset class="">
    <div class="col-lg-12 padding-left-right-10" style="display: block;">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Laporan Bulanan Bengkel (LBB 1)
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form id="frmAdd" method="get" action="<?php echo base_url("report/report_lbb");?>">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
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
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan--</option>
                                    <?php
                                        for ($i=1;$i<=12;$i++){
                                            $pilih=($bulan==$i)?"selected":"";
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
                                    <option value="">--Pilih Tahun--</option>
                                    <?php
                                    if(isset($tahun)){
                                        if($tahun->totaldata>0){
                                            foreach ($tahun->message as $key => $value) {
                                                $pilih=($tahuns==$value->TAHUN)?"selected":"";
                                                echo "<option value='".$value->TAHUN."' ".$pilih.">".$value->TAHUN."</option>";
                                            }
                                        }else{
                                           echo "<option value='".date('Y')."' selected>".date('Y')."</option>";  
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-1 col-md-1">
                            <div class="form-group">
                                <br>
                                <button type="submit" class='btn btn-info'><i class="fa fa-search"></i> Preview</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10" id="">
        <div>
            
            <div class="panel margin-bottom-5">
                <div class="panel-heading">
                    <i class="fa fa-list-ul"></i> I. Laporan Pendapatan Bengkel
                </div>
                <table class="table table-stripped">
                    <thead>
                        <tr style="background-color: #FFC110 !important">
                            <th style="width:45%">1. Pendapatan Jasa Dan Reparasi</th>
                            <th style="width:5%"></th>
                            <th style="width:45%">2. Pendapatan Penjualan Parts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <table class="table table-stripped table-hover">
                                    <tbody>
                                    <?php
                                        $n=0; $totaljasa=0;
                                            if(isset($revenue)){
                                                if($revenue->totaldata>0){
                                                    foreach ($revenue->message as $key => $value) {
                                                       if($value->JUDUL=='1. Pendapatan Jasa Dan Reparasi'){
                                                        ?>  
                                                            <tr>
                                                                <?php
                                                                if($value->JUDUL=='1. Pendapatan Jasa Dan Reparasi'){
                                                                     $n++;
                                                                    echo "<td>".$n.". ".$value->KOL."</td><td>Rp.</td>
                                                                         <td class='text-right'>".number_format($value->JSR,0)."</td>";
                                                                         $totaljasa +=$value->JSR;
                                                                }
                                                                ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #EEEEEE !important"><td class="text-right"><b>Total -1</b></td><td><b>Rp.</b></td>
                                            <td class="text-right"><b><?php echo number_format($totaljasa,0);?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                                <table class="table table-stripped table-hover">
                                    <tbody>
                                    <?php
                                        $xn=0;$i=0; $totalpart=0;
                                            if(isset($revenue)){
                                                if($revenue->totaldata>0){
                                                    foreach ($revenue->message as $key => $value) {
                                                        if($value->JUDUL=='2. Pendapatan Penjualan Parts'){
                                                        ?>  
                                                            <tr>
                                                                <?php
                                                                if($value->JUDUL=='2. Pendapatan Penjualan Parts'){
                                                                    $xn++;
                                                                    echo "<td>".$value->KOL."</td><td>Rp.</td>
                                                                         <td class='text-right'>".number_format($value->JSP,0)."</td>";
                                                                         $totalpart += $value->JSP;
                                                                }
                                                                ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            for($i=$xn; $i< $n; $i++){
                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #EEEEEE !important"><td class="text-right"><b>Total -2</b></td><td><b>Rp.</b></td>
                                            <td class="text-right"><b><?php echo number_format($totalpart,0);?></b></td>
                                        </tr>
                                        <tr><td colspan="3"><b>Penghasilan Bengkel</b></td></tr>
                                        <tr><td><b>Total -1 + Total -2</b></td><td><b>Rp.</b></td>
                                            <td class="text-right"><b><?php echo number_format(($totaljasa+$totalpart),0);?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel margin-bottom-5">
                <div class="panel-heading">
                    <i class="fa fa-list-ul"></i> II. Jumlah <em>Unit</em> Sepeda Motor Yang Dikerjakan
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
                <table class="table table-stripped table-hover table-bordered" style="width:100% !important">
                    <thead style="background-color: #FFC140 !important">
                        <tr style="text-align: center !important;">
                            <th style="width:4% !important;" rowspan="3" class="text-center">No.</th>
                            <th style="width:6% !important;" rowspan="3" class="text-center">Type Motor</th>
                            <th style="width:8% !important;" rowspan="3" class="text-center">Total Unit Entry</th>
                            <th colspan="14" class="text-center">Yang Dikerjakan Dari Unit Ini</th>
                        </tr>
                        <tr style="text-align: center !important;">
                            <th colspan="4" class="text-center">Kartu Perawatan Berkala / ASS</th>
                            <th style="width:5% !important;" class="text-center">Claim </th>
                            <th colspan="3" class="text-center">Qulok Service</th>
                            <th rowspan="2" class="text-center">LR</th>
                            <th rowspan="2" class="text-center">HR</th>
                            <th rowspan="2" class="text-center">PL</th>
                            <th rowspan="2" class="text-center">PKL</th>
                            <th rowspan="2" class="text-center">Total</th>
                            <th rowspan="2" class="text-center">JR</th>
                        </tr>
                        <tr style="text-align: center !important;">
                            <th style="width:5% !important;" class="text-center">1</th>
                            <th style="width:5% !important;" class="text-center">2</th>
                            <th style="width:5% !important;" class="text-center">3</th>
                            <th style="width:5% !important;" class="text-center">4</th>
                            <th style="width:5% !important;" class="text-center">C2</th>
                            <th style="width:5% !important;" class="text-center">CS</th>
                            <th style="width:5% !important;" class="text-center">LS</th>
                            <th style="width:5% !important;" class="text-center">OR+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n=0;$totalunit=0; $clase="";
                            if(isset($unitentry)){
                                if($unitentry->totaldata>0){
                                    foreach ($unitentry->message as $key => $value) {
                                        if((int)$value->URUTAN==1){$n++;}else{$n=1;}
                                        switch ((int)$value->URUTAN) {
                                            case 2: $classe="subtotal"; break;
                                            case 4: $classe="total"; break;
                                            default:$classe="";break;
                                        }
                                        ?>
                                        <tr class="<?php echo $classe;?>">
                                            <td class='text-center'><?php echo($value->URUTAN==1)? $n:"";?></td>
                                            <td><?php echo((int)$value->URUTAN==1)? $value->KD_ITEM." - ".$value->NAMA_TYPEMOTOR:$value->NAMA_TYPEMOTOR;?></td>
                                            <td class="text-right"><?php echo number_format($value->JML_UNIT,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->KPB1,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->KPB2,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->KPB3,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->KPB4,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->CC2,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->CS,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->LS,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->ORS,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->LR,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->HR,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->PL,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->PKL,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->TOTAL,0);?></td>
                                            <td class="text-right"><?php echo number_format($value->JR,0);?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="16">
                                <br>
                               <table class="table table-bordered table-stripped">
                                   <thead>
                                       <tr>
                                        <th>TAHUN KENDARAAN</th>
                                            <?php
                                                if(isset($rekap)){
                                                    if($rekap->totaldata>0){
                                                        foreach ($rekap->message as $key => $value) {
                                                           echo "<th class='text-center'>".$value->TAHUN."</th>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tr>
                                   </thead>
                                   <tbody>
                                       <tr>
                                        <td>JUMLAH UNIT</td>
                                        <?php
                                                if(isset($rekap)){
                                                    if($rekap->totaldata>0){
                                                        foreach ($rekap->message as $key => $value) {
                                                           echo "<td class='text-center'>".number_format($value->JML_UNIT,0)."</td>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tr>
                                   </tbody>
                               </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-5" style="padding:10px !important">
            <div class="panel-heading">
                <i class="fa fa-list-ul"></i> I. Laporan Bulanan Bengkel 2
            </div>
            <table style="width:100%; margin-top: 5px">
                <tr><td style="width:60%">III. LAPORAN PENGELUARAN OPERSIONAL BENGKEL</td>
                    <td style="width:20%; border:1px solid">Rp. <?php echo isset($pengeluaran)?number_format($pengeluaran,0):"";?></td>
                    <td style="width:20%">&nbsp;</td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td>IV. JUMLAH HARI KERJA DALAM BULAN INI</td>
                    <td style="border:1px solid"><?php echo isset($jml_harikerja)?$jml_harikerja:"0";?> Hari</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td>V. RATA - RATA UNIT PER HARI DALAM BULAN INI</td>
                    <td style="border:1px solid"><?php echo isset($rata2unit)?number_format($rata2unit,0):"0";?> Unit/Hari</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td>V. PRESTASI MEKANIK DALAM BULAN INI</td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
                <!-- <tr><td colspan="3">&nbsp;</td></tr> -->
            </table>
            <table style="width:100%; border-collapse: collapse;" border="1">
                <thead>
                    <tr>
                        <th style="width:4% rowspan="3" class="text-center">No.</th>
                        <th style="width:15%" rowspan="3" class="text-center">Nama Mekanik</th>
                        <th style="width:8%" rowspan="3" class="text-center">Jml Unit Diservice</th>
                        <th style="width:6%" rowspan="3" class="text-center">Absen (hari)</th>
                        <th style="width:6%" rowspan="3" class="text-center">Hadir (hari)</th>
                        <th style="width:6%"  rowspan="3" class="text-center">Jam Tersedia</th>
                        <th colspan="11" class="text-center">JENIS PEKERJAAN</th>
                        <th style="width:6%"  rowspan="3" class="text-center">Jam Terpakai</th>
                    </tr>
                    <tr>
                        <th style="width:5%" rowspan="2" class="text-center">KPB</th>
                        <th style="width:5%" rowspan="2" class="text-center">Claim (C2) </th>
                        <th colspan="3" class="text-center">Qulok Service</th>
                        <th style="width:5%" rowspan="2" class="text-center">LR</th>
                        <th style="width:5%" rowspan="2" class="text-center">HR</th>
                        <th style="width:5%" rowspan="2" class="text-center">PL</th>
                        <th style="width:5%" rowspan="2" class="text-center">PKL</th>
                        <th style="width:5%" rowspan="2" class="text-center">Total</th>
                        <th style="width:5%" rowspan="2" class="text-center">JR</th>
                    </tr>
                    <tr>
                        <!-- <th style="width:5% !important;" class="text-center">C2</th> -->
                        <th style="width:5% !important;" class="text-center">CS</th>
                        <th style="width:5% !important;" class="text-center">LS</th>
                        <th style="width:5% !important;" class="text-center">OR+</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n=0;
                        if(isset($mekanik)){
                            if($mekanik->totaldata>0){
                                foreach ($mekanik->message as $key => $value) {
                                  if((int)$value->URUTAN==1){$n++;}else{$n=1;}
                                    switch ((int)$value->URUTAN) {
                                        case 2: $classe="style='font-weight: bold;background-color: #fcfcfc;'"; break;
                                        case 4: $classe="style='font-size:14px !important; font-weight:bold!important; background-color: #F1F1F1 !important;border-top:2px solid #FFC140 !important;'"; break;
                                        default:$classe="";break;
                                    }
                                    ?>
                                    <tr <?php echo $classe;?>>
                                        <td align="center" class='text-center'><?php echo($value->URUTAN==1)? $n:"";?></td>
                                        <td nowrap="nowrap" style="white-space: nowrap;"><?php echo((int)$value->URUTAN==1)? $value->NAMA_KARYAWAN:"TOTAL";?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->JML_UNIT <= 0)?"": number_format($value->JML_UNIT,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->ABSEN <= 0)?"":number_format($value->ABSEN,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->HADIR <= 0)?"":number_format($value->HADIR,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->JAM_KERJA <= 0)?"":number_format($value->JAM_KERJA,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->KPB <= 0)?"":number_format($value->KPB,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->CC2 <= 0)?"":number_format($value->CC2,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->CS <= 0)?"":number_format($value->CS,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->LS <= 0)?"":number_format($value->LS,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->ORS <= 0)?"":number_format($value->ORS,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->LR <= 0)?"":number_format($value->LR,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->HR <= 0)?"":number_format($value->HR,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->PL <= 0)?"":number_format($value->PL,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->PKL <= 0)?"":number_format($value->PKL,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->TOTAL <= 0)?"":number_format($value->TOTAL,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->JR <= 0)?"":number_format($value->JR,0);?></td>
                                        <td align="center" class="text-center"><?php echo ((double)$value->JAM_TERPAKAI <= 0)?"":number_format($value->JAM_TERPAKAI,0);?></td>

                                    </tr>
                                   <?php
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </fieldset>
</section>
<div id="printarea" style="height: 0.5px; overflow: hidden;padding: 10px;width:800px" class="onlyprint">
    <table  style="width:100%">
        <tr><td>
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width:100% !important" colspan="5" align="center"><h4><u>LAPORAN BULANAN BENGKEL I</u></h4></td>
                    </tr>
                    <tr>
                        <td style="width:100% !important" colspan="5" align="center">(L.B.B)</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Nomor AHASS</td>
                        <td style="width:20%">: <?php echo KodeDealerAHM($defaulD);?></td>
                        <td style="width:5%">&nbsp;</td>
                        <td style="width:10%"> Laporan Bulan </td>
                        <td style="width:20%">: <?php echo nBulan($bulan)." ". $tahuns;?></td>
                    </tr>
                    <tr>
                        <td>Nama AHASS</td>
                        <td>: <?php echo NamaDealer($defaulD);?></td>
                        <td>&nbsp;</td>
                        <td> Tanggal Dibuat </td>
                        <td>: <?php echo date("d/m/Y H:i:s");?></td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td>: <?php echo KotaDealer($defaulD);?></td>
                        <td>&nbsp;</td>
                        <td> &nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td><hr></td></tr>
        <tr><td style="width:100%">I. LAPORAN PENDAPATAN BENGKEL</td></tr>
        <tr><td style="border: 1px solid">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #FFC110 !important">
                            <th style="width:50%">1. Pendapatan Jasa Dan Reparasi</th>
                            <!-- <th style="width:5%"></th> -->
                            <th style="width:50%">2. Pendapatan Penjualan Parts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width:50%" valign="top">
                                <table style="width:100%; border-collapse: collapse;" border="1" >
                                    <tbody>
                                    <?php
                                        $n=0; $totaljasa=0;
                                            if(isset($revenue)){
                                                if($revenue->totaldata>0){
                                                    foreach ($revenue->message as $key => $value) {
                                                       if($value->JUDUL=='1. Pendapatan Jasa Dan Reparasi'){
                                                        ?>  
                                                            <tr>
                                                                <?php
                                                                if($value->JUDUL=='1. Pendapatan Jasa Dan Reparasi'){
                                                                     $n++;
                                                                    echo "<td>".$n.". ".$value->KOL."</td><td>Rp.</td>
                                                                         <td class='text-right'>".number_format($value->JSR,0)."</td>";
                                                                         $totaljasa +=$value->JSR;
                                                                }
                                                                ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #EEEEEE !important"><td class="text-right"><b>Total -1</b></td><td><b>Rp.</b></td>
                                            <td class="text-right"><b><?php echo number_format($totaljasa,0);?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                            <!-- <td>&nbsp;</td> -->
                            <td valign="top">
                                <table style="width:100%; border-collapse: collapse;" border="1" >
                                    <tbody>
                                        <?php
                                        $xn=0;$i=0; $totalpart=0;
                                            if(isset($revenue)){
                                                if($revenue->totaldata>0){
                                                    foreach ($revenue->message as $key => $value) {
                                                        if($value->JUDUL=='2. Pendapatan Penjualan Parts'){
                                                        ?>  
                                                            <tr>
                                                                <?php
                                                                if($value->JUDUL=='2. Pendapatan Penjualan Parts'){
                                                                    $xn++;
                                                                    echo "<td>".$value->KOL."</td><td>Rp.</td>
                                                                         <td class='text-right'>".number_format($value->JSP,0)."</td>";
                                                                         $totalpart += $value->JSP;
                                                                }
                                                                ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            for($i=$xn; $i< $n; $i++){
                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #EEEEEE !important"><td class="text-right"><b>Total -2</b></td><td><b>Rp.</b></td>
                                            <td class="text-right"><b><?php echo number_format($totalpart,0);?></b></td>
                                        </tr>
                                        <tr><td colspan="3"><b>Penghasilan Bengkel</b></td></tr>
                                        <tr><td><b>Total -1 + Total -2</b></td><td><b>Rp.</b></td>
                                            <td class="text-right"><b><?php echo number_format(($totaljasa+$totalpart),0);?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td> II. JUMLAH <em>UNIT</em> SEPEDA MOTOR YANG DIKERJAKAN</td></tr>
        <tr><td>
                <table style="width:100% !important; border-collapse: collapse;" border="1">
                    <thead style="background-color: #FFC140 !important">
                        <tr style="text-align: center !important;">
                            <th style="width:4% !important;" rowspan="3" class="text-center">No.</th>
                            <th style="width:15% !important;" rowspan="3" class="text-center">Type Motor</th>
                            <th style="width:10% !important;" rowspan="3" class="text-center">Total Unit Entry</th>
                            <th colspan="14" class="text-center">Yang Dikerjakan Dari Unit Ini</th>
                        </tr>
                        <tr style="text-align: center !important;">
                            <th colspan="4" class="text-center">Kartu Perawatan Berkala / ASS</th>
                            <th style="width:5% !important;" class="text-center">Claim </th>
                            <th colspan="3" class="text-center">Qulok Service</th>
                            <th rowspan="2" class="text-center">LR</th>
                            <th rowspan="2" class="text-center">HR</th>
                            <th rowspan="2" class="text-center">PL</th>
                            <th rowspan="2" class="text-center">PKL</th>
                            <th rowspan="2" class="text-center">Total</th>
                            <th rowspan="2" class="text-center">JR</th>
                        </tr>
                        <tr style="text-align: center !important;">
                            <th style="width:5% !important;" class="text-center">1</th>
                            <th style="width:5% !important;" class="text-center">2</th>
                            <th style="width:5% !important;" class="text-center">3</th>
                            <th style="width:5% !important;" class="text-center">4</th>
                            <th style="width:5% !important;" class="text-center">C2</th>
                            <th style="width:5% !important;" class="text-center">CS</th>
                            <th style="width:5% !important;" class="text-center">LS</th>
                            <th style="width:5% !important;" class="text-center">OR+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n=0;$totalunit=0; $clase="";
                            if(isset($unitentry)){
                                if($unitentry->totaldata>0){
                                    foreach ($unitentry->message as $key => $value) {
                                        if((int)$value->URUTAN==1){$n++;}else{$n=1;}
                                        switch ((int)$value->URUTAN) {
                                            case 2: $classe="style='font-weight: bold;background-color: #fcfcfc;'"; break;
                                            case 4: $classe="style='font-size:14px !important; font-weight:bold!important; background-color: #F1F1F1 !important;border-top:2px solid #FFC140 !important;'"; break;
                                            default:$classe="";break;
                                        }
                                        ?>
                                        <tr <?php echo $classe;?>>
                                            <td align="center" class='text-center'><?php echo($value->URUTAN==1)? $n:"";?></td>
                                            <td style="white-space: nowrap;"><?php echo((int)$value->URUTAN==1)? $value->KD_ITEM." - ".$value->NAMA_TYPEMOTOR:$value->NAMA_TYPEMOTOR;?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->JML_UNIT <= 0)?"": number_format($value->JML_UNIT,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->KPB1 <= 0)?"":number_format($value->KPB1,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->KPB2 <= 0)?"":number_format($value->KPB2,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->KPB3 <= 0)?"":number_format($value->KPB3,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->KPB4 <= 0)?"":number_format($value->KPB4,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->CC2 <= 0)?"":number_format($value->CC2,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->CS <= 0)?"":number_format($value->CS,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->LS <= 0)?"":number_format($value->LS,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->ORS <= 0)?"":number_format($value->ORS,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->LR <= 0)?"":number_format($value->LR,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->HR <= 0)?"":number_format($value->HR,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->PL <= 0)?"":number_format($value->PL,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->PKL <= 0)?"":number_format($value->PKL,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->TOTAL <= 0)?"":number_format($value->TOTAL,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->JR <= 0)?"":number_format($value->JR,0);?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="17" style="padding: 5px">
                                <br>
                               <table style="width:80%; border-collapse: collapse;" border="1" >
                                   <thead>
                                       <tr style="background-color: grey">
                                        <th>TAHUN KENDARAAN</th>
                                            <?php
                                                if(isset($rekap)){
                                                    if($rekap->totaldata>0){
                                                        foreach ($rekap->message as $key => $value) {
                                                           echo "<th class='text-center'>".$value->TAHUN."</th>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tr>
                                   </thead>
                                   <tbody>
                                       <tr>
                                        <td>JUMLAH UNIT</td>
                                        <?php
                                                if(isset($rekap)){
                                                    if($rekap->totaldata>0){
                                                        foreach ($rekap->message as $key => $value) {
                                                           echo "<td class='text-center'>".number_format($value->JML_UNIT,0)."</td>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tr>
                                   </tbody>
                               </table>
                            </td>
                            <!-- <td>&nbsp;</td> -->
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>
                <table style="width:100%">
                   <tr><td style="width:60%">III. LAPORAN PENGELUARAN OPERSIONAL BENGKEL</td>
                        <td style="width:20%; border:1px solid">Rp. <?php echo isset($pengeluaran)?number_format($pengeluaran,0):"";?></td>
                        <td style="width:20%">&nbsp;</td>
                    </tr>
                    <tr><td colspan="3">&nbsp;</td></tr>
                    <tr><td>IV. JUMLAH HARI KERJA DALAM BULAN INI</td>
                        <td style="border:1px solid"><?php echo isset($jml_harikerja)?$jml_harikerja:"0";?> Hari</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr><td colspan="3">&nbsp;</td></tr>
                    <tr><td>V. RATA - RATA UNIT PER HARI DALAM BULAN INI</td>
                        <td style="border:1px solid"><?php echo isset($rata2unit)?number_format($rata2unit,0):"0";?> Unit/Hari</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td> VI. LAPORAN PRESTASI MEKANIK DALAM BULAN INI</td></tr>
        <tr><td>
                <table style="width:100%; border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th style="width:4% rowspan="3" class="text-center">No.</th>
                            <th style="width:15%" rowspan="3" class="text-center">Nama Mekanik</th>
                            <th style="width:8%" rowspan="3" class="text-center">Jml Unit Diservice</th>
                            <th style="width:6%" rowspan="3" class="text-center">Absen (hari)</th>
                            <th style="width:6%" rowspan="3" class="text-center">Hadir (hari)</th>
                            <th style="width:6%"  rowspan="3" class="text-center">Jam Tersedia</th>
                            <th colspan="11" class="text-center">JENIS PEKERJAAN</th>
                            <th style="width:6%"  rowspan="3" class="text-center">Jam Terpakai</th>
                        </tr>
                        <tr>
                            <th style="width:5%" rowspan="2" class="text-center">KPB</th>
                            <th style="width:5%" rowspan="2" class="text-center">Claim (C2) </th>
                            <th colspan="3" class="text-center">Qulok Service</th>
                            <th style="width:5%" rowspan="2" class="text-center">LR</th>
                            <th style="width:5%" rowspan="2" class="text-center">HR</th>
                            <th style="width:5%" rowspan="2" class="text-center">PL</th>
                            <th style="width:5%" rowspan="2" class="text-center">PKL</th>
                            <th style="width:5%" rowspan="2" class="text-center">Total</th>
                            <th style="width:5%" rowspan="2" class="text-center">JR</th>
                        </tr>
                        <tr>
                            <!-- <th style="width:5% !important;" class="text-center">C2</th> -->
                            <th style="width:5% !important;" class="text-center">CS</th>
                            <th style="width:5% !important;" class="text-center">LS</th>
                            <th style="width:5% !important;" class="text-center">OR+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n=0;
                            if(isset($mekanik)){
                                if($mekanik->totaldata>0){
                                    foreach ($mekanik->message as $key => $value) {
                                      if((int)$value->URUTAN==1){$n++;}else{$n=1;}
                                        switch ((int)$value->URUTAN) {
                                            case 2: $classe="style='font-weight: bold;background-color: #fcfcfc;'"; break;
                                            case 4: $classe="style='font-size:14px !important; font-weight:bold!important; background-color: #F1F1F1 !important;border-top:2px solid #FFC140 !important;'"; break;
                                            default:$classe="";break;
                                        }
                                        ?>
                                        <tr <?php echo $classe;?>>
                                            <td align="center" class='text-center'><?php echo($value->URUTAN==1)? $n:"";?></td>
                                            <td nowrap="nowrap" style="white-space: nowrap;"><?php echo((int)$value->URUTAN==1)? $value->NAMA_MEKANIK:"TOTAL";?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->JML_UNIT <= 0)?"": number_format($value->JML_UNIT,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->ABSEN <= 0)?"":number_format($value->ABSEN,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->HADIR <= 0)?"":number_format($value->HADIR,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->JAM_KERJA <= 0)?"":number_format($value->JAM_KERJA,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->KPB <= 0)?"":number_format($value->KPB,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->CC2 <= 0)?"":number_format($value->CC2,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->CS <= 0)?"":number_format($value->CS,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->LS <= 0)?"":number_format($value->LS,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->ORS <= 0)?"":number_format($value->ORS,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->LR <= 0)?"":number_format($value->LR,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->HR <= 0)?"":number_format($value->HR,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->PL <= 0)?"":number_format($value->PL,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->PKL <= 0)?"":number_format($value->PKL,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->TOTAL <= 0)?"":number_format($value->TOTAL,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->JR <= 0)?"":number_format($value->JR,0);?></td>
                                            <td align="center" class="text-center"><?php echo ((double)$value->JAM_TERPAKAI <= 0)?"":number_format($value->JAM_TERPAKAI,0);?></td>

                                        </tr>
                                       <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr style="height:10px"><td>&nbsp;</td></tr>
        <tr><td style="border: 1px solid; padding:10px">
                <table style="width:100%; border-collapse: collapse">
                    <tr>
                        <td style="width:33%" align="center">Dibuat Oleh,</td>
                        <td style="width:33%" align="center">Diperkisa Oleh,</td>
                        <td style="width:33%" align="center">Mengetahui,</td>
                    </tr>
                    <tr style="height: 60px"><td colspan="3">&nbsp;</td></tr>
                    <tr>
                        <td style="width:33%" align="center">Front Desk</td>
                        <td style="width:33%" align="center">Kepala Bengkel</td>
                        <td style="width:33%" align="center">Pemilik/Pimpinan</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printKw() {
            $('#printarea').removeClass("onlyprint");
            printJS({ 
                printable: 'printarea', 
                type: 'html', 
                honorColor: true,
             });
            $('#printarea').addClass("onlyprint");
         }
</script>