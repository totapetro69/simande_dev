<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$no_trans=$this->input->get("n");
$tgl_trans=date('d/m/Y');
$k100=0;$k50=0;$k20=0;$k10=0;$k5=0;$k2=0;$k1=0;
$l1000=0;$l500=0;$l200=0;$l100=0;$l50=0;
$k100v=0;$k50v=0;$k20v=0;$k10v=0;$k5v=0;$k2v=0;$k1v=0;
$l1000v=0;$l500v=0;$l200v=0;$l100v=0;$l50v=0;
$selisih=0;$keterangan="";
$total_kertas=0; $total_koin=0;
$total_kertas_v=0; $total_koin_v=0;
$total_kas=0;$total_hasil=0; $status_audit="";
if(isset($list)){
	if($list->totaldata >0){
		foreach ($list->message as $key => $value) {
			$k100 = $value->JUMLAH_K100;
			$k50 = $value->JUMLAH_K50;
			$k20 = $value->JUMLAH_K20;
			$k10 = $value->JUMLAH_K10;
			$k5 = $value->JUMLAH_K5;
			$k2 = $value->JUMLAH_K2;
			$k1 = $value->JUMLAH_K1;
			$l1000= $value->JUMLAH_L1000;
			$l500= $value->JUMLAH_L500;
			$l200= $value->JUMLAH_L200;
			$l100= $value->JUMLAH_L100;
			$l50= $value->JUMLAH_L50;
			$k100v = $value->JUMLAH_K100*100000;
			$k50v = $value->JUMLAH_K50*50000;
			$k20v = $value->JUMLAH_K20*20000;
			$k10v = $value->JUMLAH_K10*10000;
			$k5v = $value->JUMLAH_K5*5000;
			$k2v = $value->JUMLAH_K2*2000;
			$k1v = $value->JUMLAH_K1*1000;
			$l1000v = $value->JUMLAH_L1000*1000;
			$l500v = $value->JUMLAH_L500*500;
			$l200v = $value->JUMLAH_L200*200;
			$l100v = $value->JUMLAH_L100*100;
			$l50v = $value->JUMLAH_L50*50;
			$selisih = $value->SELISIH;
			$keterangan = $value->KETERANGAN;
			$total_kertas = ($k100+$k50+$k20+$k10+$k5+$k2+$k1);
			$total_koin =($l1000+$l500+$l200+$l100+$l50);
			$total_kertas_v = ($k100v+$k50v+$k20v+$k10v+$k5v+$k2v+$k1v);
			$total_koin_v =($l1000v+$l500v+$l200v+$l100v+$l50v);
			$total_kas = $value->JUMLAH_KAS;
			$total_hasil = $value->JUMLAH_TOTAL;
			$tgl_trans =TglFromSql($value->TGL_TRANS);
			$status_audit = $value->STATUS_AUDIT;

		}
	}
}
?>
<div class="modal-header">
  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<h4 class="modal-title" id="myModalLabel">Print Cash Opname</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="table-responsive h400">
			<div id="printarea">
				<table class="table">
					<tr>
						<td colspan="7"><h3><?php echo NamaDealer();?></h3></td>
					</tr>
					<tr>
						<td colspan="7"><h4>CASH OPNAME TANGGAL <?php echo $tgl_trans;?></h4></td>
					</tr>
					<tr>
						<td colspan="4"><b>Saldo Kas Menurut LKH</b></td>
						<td style="width:20px">=</td>
						<td style="text-align: right;"><b><?php echo number_format($total_kas,0);?></b></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="total"><td colspan="7">UANG KERTAS</td></tr>
					<tr>
						<td style="width:50px;">&nbsp;</td>
						<td style="width:120px;text-align: right;padding-right: 5px">100.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k100);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k100v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">50.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k50);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k50v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">20.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k20);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k20v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">10.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k10);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k10v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">5.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k5);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k5v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">2.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k2);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k2v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">1.000</td>
						<td style="width:20px; text-align: center;">X</td>
						<td style="width:20;text-align: right;"><?php echo number_format($k1);?></td>
						<td style="width:20px">=</td>
						<td style="width:50px; text-align: right;"><?php echo number_format($k1v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="subtotal">
						<td colspan="4" style="text-align: right;padding-right: 5px"><em><b>Total Uang Kertas</b></em></td>
						<td>=</td>
						<td style="text-align: right;" class="bold"><?php echo number_format($total_kertas_v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="total"><td colspan="7">UANG LOGAM</td></tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">1.000</td>
						<td style="text-align: center">X</td>
						<td style="text-align: right;"><?php echo number_format($l1000,0);?></td>
						<td>=</td>
						<td style="text-align: right;"><?php echo number_format($l1000v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">500</td>
						<td style="text-align: center">X</td>
						<td style="text-align: right;"><?php echo number_format($l500,0);?></td>
						<td>=</td>
						<td style="text-align: right;"><?php echo number_format($l500v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">200</td>
						<td style="text-align: center">X</td>
						<td style="text-align: right;"><?php echo number_format($l200,0);?></td>
						<td>=</td>
						<td style="text-align: right;"><?php echo number_format($l200v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">100</td>
						<td style="text-align: center">X</td>
						<td style="text-align: right;"><?php echo number_format($l100,0);?></td>
						<td>=</td>
						<td style="text-align: right;"><?php echo number_format($l100v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td style="width:50px;">&nbsp;</td>
						<td style="text-align: right;padding-right: 5px">50</td>
						<td style="text-align: center">X</td>
						<td style="text-align: right;"><?php echo number_format($l50,0);?></td>
						<td>=</td>
						<td style="text-align: right;"><?php echo number_format($l50v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="subtotal">
						<td colspan="4" style="text-align: right;padding-right: 5px"><em><b>Total Uang Logam</b></em></td>
						<td>=</td>
						<td style="text-align: right;" class="bold"><?php echo number_format($total_koin_v,0);?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td colspan="7" style="border:none !important;">&nbsp;</td></tr>
					<tr>
						<td colspan="4"><b>TOTAL SALDO KAS</b></td>
						<td style="width:20px">=</td>
						<td style="text-align: right;"><b><?php echo number_format($total_hasil,0);?></b></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4"><b>TOTAL SALDO KAS MENURUT LKH</b></td>
						<td style="width:20px">=</td>
						<td style="text-align: right;"><b><?php echo number_format($total_kas,0);?></b></td>
						<td>&nbsp;</td>
					</tr>
					<tr class='total'>
						<td colspan="4"><b>SELISIH</b></td>
						<td style="width:20px">=</td>
						<td style="text-align: right;"><b><?php echo number_format($selisih,0);?></b></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td colspan="7" style="border:none !important;"><hr></td></tr>
					<tr style="height: 70px; border:none !important;" valign="top" >
						<td style="width:50px;border:none !important;">&nbsp;</td>
						<td colspan="3" style="border:none !important;">Mengetahui</td>
						<td colspan="3" style="text-align: center;border:none !important;"> Dibuat Oleh</td>
						<!-- <td>&nbsp;</td> -->
					</tr>
					<?php
						$kadel="";
						if(isset($kepala)){
							if($kepala->totaldata >0){
								$kadel = $kepala->message[0]->NAMA;
							}
						}
					?>
					<tr style="border:none !important;">
						<td style="width:50px;border:none !important;">&nbsp;</td>
						<td colspan="3" style="border:none !important;"><?php echo ($kadel);?></td>
						<td colspan="3" style="text-align: center;border:none !important;"><?php echo $this->session->userdata("user_name");?></td>
						<!-- <td>&nbsp;</td> -->
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="keluar" data-dismiss="modal"><i class='fa fa-close'></i> Batal</button>
	<button type="button" onclick="print_opname();" class="btn btn-primary"><i class='fa fa-print'></i> Print</button>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
	function print_opname(){
		printJS('printarea','html');
		$('#keluar').click();
	}
</script>