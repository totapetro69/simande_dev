<?php
     $dari_tgl =($this->input->get("tgl_trans"))?$this->input->get("tgl_trans"):date("d/m/Y",strtotime("-1 Days"));
     $no_trx=$this->input->get("n");
     $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
     $disable=($no_trx)?"":"disabled-action";
     $defaulD=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
     $bulan= ($this->input->get('bulan'))?$this->input->get('bulan'):date("m");
     $tahuns= ($this->input->get("tahun"))?$this->input->get("tahun"):date('Y');
     $p_jasa=0;$p_part=0;$jml_mekanik=0;$jml_unit=0; $jml_jamkerja=0; $jam_terpakai=0; $hari_kerja=0;
     $job_return=0; $mekanik_hadir=0; $mekanik_absen=0;$jml_mekanik_aktif=0; $biaya_opr=0;
     $p_spart=0;$p_oli=0;$jml_unit_kpb=0;$jml_unit_nonkpb=0;
     if(isset($revenue)){
        if($revenue->totaldata>0){
            foreach ($revenue->message as $key => $value) {
               if($value->JUDUL=='3. Total'){
                    $p_jasa = $value->JSR;
                    $p_part = $value->JSP;
               }else if($value->JUDUL=='2. Pendapatan Penjualan Parts'){
                    if(substr($value->KOL,0,1)=='1'){
                        $p_spart += $value->JSP;
                    }else{
                        $p_oli += $value->JSP;
                    }
                }
            }
        }
     }
     if(isset($unitentry)){
        if($unitentry->totaldata>0){
            foreach ($unitentry->message as $key => $value) {
                if($value->URUTAN=='4'){
                    $jml_unit = $value->JML_UNIT;
                    $job_return = $value->JR;
                }
            }
        }
     }
     if(isset($mekanik)){
        if($mekanik->totaldata >0){
            foreach ($mekanik->message as $key => $value) {
               if($value->URUTAN=='2'){
                    $jml_mekanik_aktif =(int)$value->NAMA_MEKANIK;
                    $jml_mekanik = $value->JML_MEKANIK;
                    $mekanik_absen = $value->ABSEN;
                    $mekanik_hadir = $value->HADIR;
                    $hari_kerja = $value->HARI_KERJA;
                    $jml_jamkerja = $value->JAM_KERJA;
                    $jam_terpakai = $value->JAM_TERPAKAI;
                }else if($value->URUTAN=='1'){
                    if((int)$value->KPB >0){
                        $jml_unit_kpb +=$value->KPB;
                    }
                }
            }
        }
     }
     if(isset($bengkel)){
        if($bengkel->totaldata>0){
            foreach ($bengkel->message as $key => $value) {
               $biaya_opr += $value->JUMLAH;
            }
        }
     }
     $jml_cust=0; $jml_cust_2th=0;
     $jml_cust_baru=0;$jml_cust_3bln=0;
     $jml_cust_lama=0;
     if(isset($customer)){
        if($customer->totaldata >0){
            foreach ($customer->message as $key => $value) {
                $jml_cust = $value->TOTAL_PELANGGAN;
                $jml_cust_lama = $value->LAMA;
                $jml_cust_baru = $value->LANGGANAN_BARU;
                $jml_cust_2th = $value->PELANGGAN_2TH;
                $jml_cust_3bln = $value->TBULAN;
            }
        }
     }
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a onclick="upd_file();" class="btn btn-default"><i class="fa fa-download"></i> Download file .SDWPP</a>
            <a onclick="printKw();" class="btn btn-default hidden"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>
    <div class="col-lg-12 padding-left-right-10" style="display: block;">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> WORKSHOP PERFORMANCE PARAMATER
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form id="frmAdd" method="get" action="<?php echo base_url("report/report_wpp");?>">
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
                                <select id="bulan" name="bulan"class="form-control">
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
                                <select id="tahun" name="tahun"class="form-control">
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
    <div id="printarea">
        <div class="col-lg-12 padding-left-right-10">
            <div class="panel margin-bottom-5">
                <div class="panel-heading">
                    <i class="fa fa-list-ul"></i> Workshop Performance Parameter
                </div>
                <div class="panel-body panel-body-border">
                    <form id="frmAddfile" class="form-horizontal table-responsive h350" method="post" action="<?php echo base_url("report/report_wppfile");?>">
                        <div class="col-xs-12 col-md-11 col-sm-11">
                            <!-- no ahass -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6 ">1. No. AHASS</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo KodeDealerAHM($defaulD);?></label>
                                    <input type="hidden" value="" name="datax" id="datax">
                                </div>
                            </div>
                            <!-- nama ahass -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">2. Nama. AHASS</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo NamaDealer($defaulD);?></label>
                                </div>
                            </div>
                            <!-- kota -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">3. Kota</label>
                                <div class="col-xs-5col-md-4 col-sm-4">
                                    <label class="form-control"><?php echo KotaDealer($defaulD);?></label>
                                </div>
                            </div>
                            <!-- periode laporan -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">4. Periode Laporan</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo nBulan($bulan)." ". $tahuns;?></label>
                                </div>
                            </div>
                            <!-- tanggal cetak -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">5. Tanggal Cetak</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo date("d/m/Y H:i:s");?></label>
                                </div>
                            </div>
                            <!-- produktiviti amount -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">6. Productivity Of Mechanic (Amount)</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ((int)$p_jasa>0)? number_format(round(($p_jasa /($jml_mekanik * $hari_kerja)),-2),0):"0";?></label>
                                        <span class="input-group-addon">Rp/Orang</span>
                                    </div>
                                </div>
                            </div>
                            <!-- produktivity unit -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">7. Productivity Of Mechanic (Unit)</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ((int)$jml_unit>0)?number_format(round(($jml_unit/($jml_mekanik*$hari_kerja)),2),2):"0";?></label>
                                        <span class="input-group-addon">Unit/Orang</span>
                                    </div>
                                </div>
                            </div>
                            <!-- effesiency -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">8. Effeciency</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ((int)$jam_terpakai>0)?number_format(round(($jam_terpakai/($jml_jamkerja)*100),0),0):"0";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- job return -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">9. Job return</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ((int)$job_return>0)? number_format(round(($job_return/($jml_unit-$job_return))*100,2),2) :"0";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- attendance mekanik -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">10. Attendance Of Mechanic</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ((int)$mekanik_absen>0)?number_format(round(($mekanik_hadir/($jml_mekanik*$hari_kerja)*100),1),1):"0";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- biaya operasional -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">11. Operation Expenses to Income Ratio</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ($biaya_opr>0 && $p_jasa >0)? number_format(round(($biaya_opr/$p_jasa*100),1),2):"0";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- man hour rate -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">12. Man Hour Rate</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ($jam_terpakai>0)?number_format(round(($p_jasa/$jam_terpakai),-2),1):"0";?></label>
                                        <span class="input-group-addon">Rp / Jam</span>
                                    </div>
                                </div>
                            </div>
                            <!-- sales ability -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">13. Sales Ability</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo (($p_part+$p_jasa)>0)?number_format(round((($p_part+$p_jasa)/$jml_unit),-2),1):"0";?></label>
                                        <span class="input-group-addon">Rp / Unit</span>
                                    </div>
                                </div>
                            </div>
                            <!-- labor part ratio -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">14. Labour To Part Ratio</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo (($p_spart)>0)?number_format(round((($p_part+$p_jasa)/$p_spart*100),2),2):"0";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- new customer -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">15. New Customer Ratio</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ($jml_cust_baru>0)?number_format(round(($jml_cust_baru/($jml_cust)*100),2),2):"0";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- new customer -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">16. New Customer Handling Effeciency</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                     <div class="input-group">
                                        <label class="form-control"><?php echo ($jml_cust_baru>0)?number_format(round(($jml_cust_baru/$jml_cust*100),2),2):"0";?></label>
                                         <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- new cust handling -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">17. Customer Handling Effeciency</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo ($jml_cust>0)?number_format(round(($jml_cust/$jml_cust_2th*100),2),2):"0";?></label>
                                         <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- part unvail -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">18. Parts Unvailability</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo "";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- unhanlde cust -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">19. Unhandled Customer Ratio</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <label class="form-control"><?php echo "";?></label>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- total jasa -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">20. Total Pendapatan Jasa</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo($p_jasa)?number_format($p_jasa,0):"0";?></label>
                                </div>
                            </div>
                            <!-- total part -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">21. Total Pendapatan Parts</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo($p_part)?number_format($p_part,0):"0";?></label>
                                </div>
                            </div>
                            <!-- unit entry KPB -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">22. Total Unit Entry KPB 1-4</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo($jml_unit_kpb>0)?number_format($jml_unit_kpb,0):"0";?></label>
                                </div>
                            </div>
                            <!-- unit entry non kpb -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">23. Total Unit Entry Non KPB</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo($jml_unit>0)?number_format(($jml_unit-$jml_unit_kpb),0):"0";?></label>
                                </div>
                            </div>
                            <!-- customer unhandle -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">24. Customer Unhandle</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ""?></label>
                                </div>
                            </div>
                            <!-- langganan lama -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">25. Langganan Lama</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ($jml_cust_lama >0)?number_format($jml_cust_lama,0): "0";?></label>
                                </div>
                            </div>
                            <!-- langganan baru -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">26. Langganan Baru</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ($jml_cust_baru >0)?number_format($jml_cust_baru,0): "0";?></label>
                                </div>
                            </div>
                            <!-- langganan bulan ini -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">27. Jumlah Langganan Bulan Ini</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ($jml_cust >0)?number_format($jml_cust,0): "0";?></label>
                                </div>
                            </div>
                            <!-- lebih dari 3 bulan tidak datang -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">28. Langganan Yang Lebih dari 3 Bulan tidak datang</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ($jml_cust_3bln >0)?number_format($jml_cust_3bln,0): "0";?></label>
                                </div>
                            </div>
                            <!-- yang terkontrol -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">29. Langganan Yang Terkontrol</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ($jml_cust >0)?number_format($jml_cust,0): "0";?></label>
                                </div>
                            </div>
                            <!-- langganan 2 tahun terakhir -->
                            <div class="form-group">
                                <label style="text-align: left !important; padding-left: 20px !important" for="inputid" class="control-label col-xs-6 col-md-6 col-sm-6">30. Jumlah Langganan Selama 2 Tahun</label>
                                <div class="col-xs-5 col-md-3 col-sm-3">
                                    <label class="form-control"><?php echo ($jml_cust_2th >0)?number_format($jml_cust_2th,0): "0";?></label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printKw() {
            printJS({ printable: 'printarea', type: 'html', honorColor: true });
            //$('#keluar').click();
        }
        function upd_file(){
            // 
            var data=[];
            $('label.form-control').each(function(key,value){
                data.push($(this).text());
            })
            if(data){
                $('#datax').val(JSON.stringify(data));
                $('#frmAddfile').submit();
                
            }
        }
</script>