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
    
    /*@page { size: portrait; }*/
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Daftar Pengajuan Insentif PIC STNK</h4>
</div>
<?php
$nama_dealer = "";
 foreach ($dealer->message as $key => $group) :
	if ($list->message[0]->KD_DEALER == $group->KD_DEALER):
		$nama_dealer = $group->NAMA_DEALER_ASLI;
	endif;
endforeach; 
?>
<div class="modal-body" id="printarea">

    <table class="table table-striped b-t b-light">
		<h3>DAFTAR PENGAJUAN INSENTIF PIC STNK</h3>
		<h4><?php echo $nama_dealer;?></h4>
		<hr/>
		
		<thead>

			<tr>
				<th style="width:40px;">No.</th>
				<th>Dealer</th>
				<th>No. Proses</th>
				<th>Periode Pengajuan</th>
				<th>Total Insentif</th>
				<th>Waktu Pengajuan</th>
				<th>Status Approval</th>
			</tr>
			
		   
		</thead>
			<?php
			$no = 0;
			if ($list):
				if (is_array($list->message)):
					//var_dump($list->message);exit;
					$totalunit = 0;
					$birojasa = "";
					foreach ($list->message as $key => $row):
						$no ++;
						//echo'<script>console.log("'.$row->KD_DEALER.'")</script>';
						?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $row->KD_DEALER; ?></td>
							<td><?php echo $row->NO_PROSES; ?></td>
							<td><?php echo $row->PERIODE; ?></td>
							<td align="right"><?php echo $row->GRAND_TOTAL; ?></td>
							<td><?php echo $row->CREATED_TIME; ?></td>
							<td><?php 
							if ($row->APPROVAL_STATUS == 'Y')
								echo '<b style="color:green">Disetujui</b>';
							else if ($row->APPROVAL_STATUS == 'N')
								echo '<b style="color:red">Ditolak</b>';
							else
								echo "Belum Direspon";
							
							?></td>
						</tr>
						<?php
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