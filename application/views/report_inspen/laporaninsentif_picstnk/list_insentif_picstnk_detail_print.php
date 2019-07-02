<style type="text/css">
    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
        /* float: left; */
        text-align: left;
        display: table;
        width: 100%;
    }
    .project div {
        display: table-row;
    }

    .project .title {
        color: #5D6975;
        width: 90px;
    }

    .project span {
        text-align: left;
        /* width: 100px; */
        /* margin-right: 15px; */
        padding: 2px 0;
        display: table-cell;
        /* font-size: 0.8em; */
    }

    .project .content {
        width: 100%;
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Print Insentif PIC STNK</h4>
</div>
<div class="modal-body"  id="printarea">
	
<table class="table table-striped b-t b-light" >
		<?php 
			//var_dump($header);exit();
		?>
		<thead>
			<tr>
				<td colspan = '8' align = 'center'><h2 class="modal-title" id="myModalLabel">Rincian Insentif PIC STNK</h2></td>
				
			</tr>
			<tr>
				<td colspan = '2'>No. Proses</td>
				<td colspan = '2'><?php echo $header->message[0]->NO_PROSES ?></td>
				<td colspan = '4'>Periode <?php echo $header->message[0]->PERIODE ?></td>
			</tr>
			<tr>
				<td colspan = '2'>Kode Dealer</td>
				<td colspan = '2'><?php echo $header->message[0]->KD_DEALER ?></td>
				<td colspan = '4'>Status Approval : <?php 
					IF($header->message[0]->APPROVAL_STATUS == 'Y'):
						ECHO "Disetujui";
					ELSEIF($header->message[0]->APPROVAL_STATUS == 'N'):
						ECHO "Ditolak";
					ELSE:
						ECHO "Belum Direspon";
					ENDIF;
				?></td>
			</tr>
			<tr>
				<th style="width:25px;">No.</th>
				<th>NIK</th>
				<th>Nama Pengurus</th>
				<th>No. Transaksi</th>
				<th>Jumlah</th>
				<th>Insentif PerUnit</th>
				<th>Jumlah Insentif</th>
				<th>Tgl. Selesai Pengurusan</th>
			</tr>
		   
		</thead>
		<tbody>
			<?php
			$no = 0;
			$row_number = 0;
			if ($list):
				if (is_array($list->message)):
					$TOTAL_SELURUHNYA = 0;
					$totalunit = 0;
					$birojasa = "";
					foreach ($list->message as $key => $row):
						$no ++;
						$row_number ++;
						$insentif_perbaris = $row->JUMLAH * $row->INSENTIF_PERUNIT; 
						$TOTAL_SELURUHNYA = $TOTAL_SELURUHNYA + $insentif_perbaris;
						$totalunit = $totalunit + $row->JUMLAH;
						if($no < $list->totaldata){
							$next = $list->message[$no];
							$birojasa = $next->NIK;
						}
						?>
						<tr>
							<td><?php echo $row_number; ?></td>
							<td><?php echo $row->NIK; ?></td>
							<td><?php echo $row->NAMA_PENGURUS; ?></td>
							<td align="left"><?php echo $row->NO_TRANSAKSI; ?></td>
							<td align="center"><?php echo $row->JUMLAH; ?></td>
							<td align="right"><?php echo $row->INSENTIF_PERUNIT; ?></td>
							<td align="right"><?php echo $insentif_perbaris; ?></td>
							<td align="center"><?php echo $row->TGL_SELESAIPENGURUSAN; ?></td>
						</tr>
						<?php 
						if(($birojasa != "" && $next->NIK != $row->NIK) || $no == $list->totaldata){
						?>
						<tr>
							<td colspan = '3'>TOTAL INSENTIF</td>
							<td></td>
							<td  align="center"><?php echo $totalunit ?></td>
							<td></td>
							<td align="right"><?php echo $TOTAL_SELURUHNYA; ?></td>
							<td></td>
						</tr>
						<tr>
							<td colspan = '8'><br /></td>
						</tr>
						<?php
						$totalunit = 0;
						$TOTAL_SELURUHNYA = 0;
						$row_number = 0;
						}
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