
<?php
	//print_r($kwt);
	$nomor="";$nama="";$namane="";$jumlah=0;$uraian="";$tgltrans="";$dibuatoleh="";$nokw="";$n=0;$jmluraian=0;
	$no_rek="";$no_check="";$nama_bank="";$jth_tempo="";$cbayare="";$jenis_trans="";$tp_trans="";$penerima="";
	$isCabang ='Y';
	if(isset($kwt)){
		if(($kwt->totaldata>0)){
			$jmluraian=($kwt->totaldata);
			foreach ($kwt->message as $key => $value) {
				$nomor=$value->NO_TRANS;
				$nokw=(isset($no_kw))?$no_kw:$value->NO_KWITANSI;
				$namane=($value->TYPE_TRANS=='Pengeluaran')? NamaDealer($value->KD_DEALER):$value->KET_REFF;
				$jumlah +=($value->JUMLAH * $value->HARGA);
				$uraian .=($jmluraian>1)?$value->NO_URUT.". ".$value->URAIAN_TRANSAKSI:$value->URAIAN_TRANSAKSI;
				$uraian .=($jmluraian>1)? " -> Rp. ".number_format(($value->JUMLAH * $value->HARGA))."<br>":"<br>";
				$tgltrans=tglFromSqlLong($value->TGL_TRANS);
				$yngMemberi=explode(" ", ltrim($value->URAIAN_TRANSAKSI));
				//echo ($yngMemberi[0]);
				switch ($yngMemberi[0]) {
					case 'Pinjaman':
						if($yngMemberi[1]=='Biaya'){
							$dibuatoleh=$value->USER_NAME;
							$nama=NamaDealer($value->KD_DEALER);
							$penerima =(isset($yngMemberi[9]))?$yngMemberi[9]:'';
						}else{
							$dibuatoleh=(isset($yngMemberi[9]))?$yngMemberi[9]:$value->USER_NAME;
							$nama=NamaDealer($value->KD_DEALER);
						}
						break;
					case 'Pengembalian':
						$dibuatoleh=$value->USER_NAME;
						$nama=(isset($yngMemberi[9]))?$yngMemberi[9]:$namane;
						break;
					default:
						$dibuatoleh=((int)$value->POSTING_STATUS>0)?'':$value->USER_NAME;
						$nama=$namane;
						break;
				}
				$cbayare = $value->CARA_BAYAR;
				$no_rek = $value->NO_REKENING;
				$no_cek = $value->NO_CHEQUE;
				$nama_bank = NamaBank($value->KD_DEALER,$value->NAMA_BANK);
				$jth_tempo = ($value->JTH_TEMPO)?tglFromSql($value->JTH_TEMPO):"";
				// $dibuatoleh=($value->TYPE_TRANS=='Pengeluaran')?$value->KET_REFF:$value->USER_NAME;
				$jenis_trans= $value->JENIS_TRANS;
				$isCabang = isCabang($value->KD_DEALER);
				$tp_trans = $value->TYPE_TRANS;
			}
		}
	}
	$nokw=($nokw=="")?$no_kw:$nokw;
	$jml_titipan=0;$ket_titipan="";
	//var_dump($titipan);
	if(isset($titipan)){
		$n=0;
		if($titipan->totaldata >0){
			foreach ($titipan->message as $key => $value) {
				$n++;
				$harga=(string)number_format($value->JUMLAH_TITIPAN,0);
				$ket_titipan .="<span style='padding-left: 10px;'>Titipan ke ".$n. " = ".str_pad($harga,12,".",STR_PAD_LEFT)." [ ".$value->NO_KWITANSI."]</span><br>";
				$jml_titipan +=(double)$value->JUMLAH_TITIPAN;
			}
		}
	}
	$jumlah =($jml_titipan >0 && $jenis_trans=='Titipan Uang')?$jml_titipan:$jumlah;
	$tampil=($isCabang=='Y')?'hidden':'';
?>
<form>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="myModalLabel">Kwitansi Pembayaran </h4>
	</div>
	<div class="modal-body" id="printarea">
		<table style="width:100%; border-collapse: collapse;" class="" border="0">
			<tr style="height: 15px" valign="top">
				<td colspan="2"style="width:250px" align="left">No. Trans : <?php echo $nomor;?></td>
				<td style="width: 530px;" align="right"><h3><span class="<?php echo $tampil;?>">No :</span><?php echo $nokw;?></h3></td>
			</tr>
			<tr><td colspan="2">&nbsp;</td><td></td></tr></tr>
			<tr style="height: 20px">
				<td style="width:120px" class="table-nowarp"><span class="<?php echo $tampil;?>">Sudah terima uang dari </span> </td>
				<td style="width:20px"><span class="<?php echo $tampil;?>">:</span></td>
				<td style="width:530px;"><em><strong><?php echo stripslashes($nama);?></strong></em></td>
			</tr>

			<tr style="height: 35px">
				<td><span class="<?php echo $tampil;?>">Uang Sejumlah</span><!--  --> </td>
				<td><span class="<?php echo $tampil;?>">:</span></td>
				<td><em><?php echo terbilang($jumlah);?> Rupiah</em></td>
			</tr>
			<tr style="height: 40px">
				<td><span class="<?php echo $tampil;?>">Untuk Pembayaran</span><!--  --> </td>
				<td><span class="<?php echo $tampil;?>">:</span></td>
				<td><em><strong><?php echo ($jmluraian==1)? $uraian:"";?></strong></em></td>
			</tr>
			
			<tr style="height: 135px">
				<td></td>
				<td valign="top" colspan="2" class="text-justify" style="padding:5px; font-size: 10pt">
					<?php echo ($jmluraian >1)?"<em>".stripslashes($uraian)."</em><br>":"";?>
					<?php echo ($cbayare!='Cash')? "<em><span style='padding-left: 10px;'>Bayar Via : ".strtoupper($cbayare).", No. Cheque : ".$no_cek.", Bank : ".$nama_bank.", No.Acc :".$no_rek.", Jatuh Tempo : ".$jth_tempo."</span></em><br>":"";?>
					<?php echo ($ket_titipan)?"<em>".$ket_titipan."</em>":"";?>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td><td></td></tr></tr>
			<tr style="height: 50px">
				<td style="padding-left:90px" valign="bottom">
					<em><h3><span class="<?php echo $tampil;?>">Rp.&nbsp;&nbsp;</span><?php echo number_format($jumlah,0);?></h3></em>
				</td>
				<td>&nbsp;</td>
				<td align="right" style="padding-right: 25px;padding-top: -5px;" valign="top"> <em><?php echo $tgltrans;?></em></td>
			</tr>
			<tr style="height: 35px" align="bottom">
				<td><small style="font-size:6pt;padding-left: 100px;padding-top: 10px">
					<span class="<?php echo $tampil;?>">Tanggal cetak :</span><!--  --> <?php echo date('d/m/Y H:i');?></small></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="border: 0px solid grey; padding:4px; white-space: nowrap;" valign="bottom">
					<small style="font-size: 7pt; display:; color: white ">
						<span class="<?php echo $tampil;?>">
						<!-- Pembayaran Dengan Cheque/Bilyet Giro --><br>
						<!-- Dianggap Sah Setelah Cheque/Bilyet Giro --><br>
						<!-- Dapat Diuangkan (Clearing) -->
						</span>
					</small>
				</td>
				<td colspan="2">
					<table style="width:100%; border-collapse: collapse;">
						<tr>
							<td align="center" style="width:30%"><span id="pnrm1" class='pnrm hidden'>(__________________________)</span></td>
							<td align="center" style="width:30%"><span id="pnrm2" class='pnrm hidden'>(__________________________)</span></td>
							<td align="center" style="width:30%">(__<u><?php echo stripslashes($dibuatoleh);?></u>__)</td>
						</tr>
						<tr valign="top">
							<td align="center"><span id="pnrm3" class='pnrm hidden'>Pimpinan</span></td>
							<td align="center"><span id="pnrm4" class='pnrm hidden'>Penerima</span></td>
							<td align="center"><span id="pnrm5" class='pnrm hidden'>Kasir</span></td>
						</tr>

					</table>
				</td>
				<!-- <td align="right" style="white-space: nowrap;" valign="bottom"> 			</td>-->
				<!-- <td colspan="2" style="white-space: nowrap; align-items: right;" align="right" valign="bottom"><span id="pnrm" class="hidden" align="left">( ___________________ )&nbsp;&nbsp;&nbsp;( ___________________ ) </span><span style="color:white"><?php echo str_repeat("&nbsp;", 18);?></span><span  align="right"  style="text-align: right;padding-right: 20px;">( <u><?php echo stripslashes($dibuatoleh);?></u> )</span></td> -->
			</tr>
			<!-- <tr valign="top"><td>&nbsp;</td><td colspan="2" style="border:1px solid red;white-space: nowrap; align-items: right;" align="right" valign="bottom"><span id="pnrm" class="hidden" align="left"><?php echo str_repeat("&nbsp;",7)."Pimpinan".str_repeat("&nbsp;",8);?>&nbsp;&nbsp;&nbsp;<?php echo str_repeat("&nbsp;",7)."Penerima".str_repeat("&nbsp;",8);?> </span><span style="color:white"><?php echo str_repeat("&nbsp;", 18);?></span><span  align="right"  style="text-align: right;padding-right: 20px;">&nbsp;&nbsp;<?php echo str_repeat("&nbsp;",strlen($dibuatoleh));?>&nbsp;&nbsp;</span></td></tr> -->
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
   		<button type="button" onclick="printKw();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
   		<input type="hidden" id="bwr" name="bwr" value="<?php echo isset($browser)?$browser:'';?>">
	</div>
</form>
<script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#print_kwts').hide();
		$('#myModalLg').on("hidden.bs.modal",function(){
			$('#print_kwts').show();
		})
		var tpkwt="<?php echo $tp_trans;?>";
		if(tpkwt=='Pengeluaran'){ $('.pnrm').removeClass('hidden')}else{ $('.pnrm').addClass('hidden')}
	})
    function printKw() {
     	printJS('printarea','html');
     	var browsere=$('#bwr').val();
      	$.post("<?php echo base_url('cashier/updateafterprint/'.$nomor);?>",
      	{
      		'nomor':'<?php echo $nomor;?>',
      		'no_kw':'<?php echo $nokw;?>',
      		'jenis':'kwt'
      	},function(result){
      		console.log('document <?php echo $nomor;?> printed ');
      		//window.location.reload();
	      	if(result){
	      		window.location.reload();
	      	}
	      	$('#myModalLg').on("hidden.bs.modal",function(){
	      		window.location.reload();
	      	})
	      	if(browsere!='Chrome'){
	      		//if(confirm("Print Kwitansi?")){
	      			window.location.reload();
	      		//}
	      	}
      })
       $('#keluar').click();

    }
    
</script>