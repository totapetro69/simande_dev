<style type="text/css">
	 @page { size: landscape; }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Report Sales Akumulasi Harian </h4>
</div>
<?php
	$kd_lr=isset($dlr)?$dlr:$this->session->userdata("kd_dealer");
	$namadle=NamaDealer($kd_lr);
	$alamat="";
	if(isset($dealer)){
		if($dealer->totaldata>0){
			foreach ($dealer->message as $key => $value) {
				if($kd_lr==$value->KD_DEALER){
					$alamat = $value->ALAMAT_LENGKAP;
				}
			}
		}
	}
?>
<div class="modal-body" id="printarea" style="overflow: auto;">
	<div class="table-responsive h400">
		<table class="table table-bordered" border="0" style="border-collapse: collapse;">
			<tr style="height: 45px;">
				<td colspan="2" style="text-align: left !important; font-size: small; vertical-align: middle !important;border-bottom: 1px !important"><?php echo "<b>".$namadle."</b><br>".str_replace("\\n\\r","<br>",sentence_case(strtolower($alamat)));?></td>
				<td colspan="8" style="text-align: center !important; vertical-align: middle !important;border-bottom: 1px !important"><h4>REPORT SALES AKUMULASI HARIAN</h4></td>
				<td colspan="2" style="text-align: left !important; white-space: nowrap !important; border-bottom: 1px !important">Tanggal :<?php echo (isset($judul))?tglFromSql($judul):date('d/m/Y');?></td>
			</tr>
			
				<tr class="success" style="border:1px solid !important">
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">No</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">No.Faktur</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">Tgl Trans</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">Part Number</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">Deskripsi</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">Qty Sales</th>
					<th colspan="4" style="text-align: center !important; border: 1px solid !important">Per PCS</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">Amount Sales</th>
					<th rowspan="3" style="text-align: center !important; border: 1px solid !important">Src</th>
				</tr>
				<tr class="success">
					<th rowspan="2" style="text-align: center !important; border: 1px solid !important">HET</th>
					<th colspan="2" style="text-align: center !important; border: 1px solid !important">Diskon</th>
					<th rowspan="2" style="text-align: center !important; border: 1px solid !important">Harga Jual</th>
				</tr>
				<tr class="success">
					<th style="text-align: center !important; border: 1px solid !important">%</th>
					<th style="text-align: center !important; border: 1px solid !important">Rp</th>
				</tr>
			<!-- </thead> -->
			<tbody>
				<?php
					$jml=0;$tharga=0;
					$n=0;$part=array();
					if(isset($list)){
						if($list->totaldata >0){
							
							foreach ($list->message as $key => $value) {
								$n++;
								//$part=explode("-",$value->URAIAN_TRANSAKSI,2);
								?>
									<tr>
										<td class='text-center table-nowarp'><?php echo $n;?></td>
										<td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
										<td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
										<td class='table-nowarp'><?php echo $value->PART_NUMBER;?></td>
										<td class='td-overflow' title="<?php echo $value->PART_DESKRIPSI;?>"><?php echo $value->PART_DESKRIPSI;?></td>
										<td class='text-right table-nowarp'><?php echo number_format($value->JUMLAH_ORDER,0);?></td>
										<td class='text-right table-nowarp'><?php echo number_format($value->HET,0);?></td>
										<td class='text-right table-nowarp'><?php echo number_format($value->PROSEN,0);?></td>
										<td class='text-right table-nowarp'><?php echo number_format($value->DISKON,0);?></td>
										<td class='text-right table-nowarp'><?php echo number_format(($value->HARGA_JUAL),0);?></td>
										<td class='text-right table-nowarp'><?php echo number_format($value->TOTAL_HARGA,0);?></td>
										<td class='text-center table-nowarp'><?php echo substr($value->TIPE,0,2);?></td>
									</tr>
								<?php
								$jml +=$value->JUMLAH_ORDER;
								$tharga += ($value->TOTAL_HARGA);
							}
						}

					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5" class='text-right' style="padding-right: 10px">TOTAL</td>
					<td class="text-right"><?php echo number_format($jml,0);?></td>
					<td colspan="4">&nbsp;</td>
					<td class="text-right"><?php echo number_format($tharga,0);?></td>
					<td colspan="1">&nbsp;</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printSj() {
            printJS('printarea', 'html');
            $('#keluar').click();
        }
</script>