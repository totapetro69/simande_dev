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
    <h4 class="modal-title" id="myModalLabel">Approve Insentif PIC STNK</h4>
</div>
<div class="modal-body">
<form id="addForm" class="bucket-form" action="<?php echo base_url('stnk/approve_proses_insentif_picstnk/'.$no_proses);?>" method="post">
	<div class="form-group">
    <label>No. Proses</label>
    <input id="no_proses" readonly type="text" name="no_proses" class="form-control" value=" <?php echo $no_proses;?>">
	<input id="aksi" readonly type="hidden" name="aksi" class="form-control" value="">
  </div>
</form>
<table class="table table-striped b-t b-light" >

		<thead>

			<tr>
				<th style="width:30px;">No.</th>
				<th>NIK</th>
				<th>Nama Pengurus</th>
				<th>No. Transaksi</th>
				<th>Jumlah</th>
				<th>Insentif/Unit</th>
				<th>Jumlah Insentif</th>
				<th>Tgl. Selesai Pengurusan</th>
			</tr>
		   
		</thead>
		<tbody>
			<?php
			$no = 0;
			if ($list):
				if (is_array($list->message)):
					$TOTAL_SELURUHNYA = 0;
					$totalunit = 0;
					$birojasa = "";
					foreach ($list->message as $key => $row):
						$no ++;
						$insentif_perbaris = $row->JUMLAH * $row->INSENTIF_PERUNIT; 
						$TOTAL_SELURUHNYA = $TOTAL_SELURUHNYA + $insentif_perbaris;
						$totalunit = $totalunit + $row->JUMLAH;
						if($no < $list->totaldata){
							$next = $list->message[$no];
							$birojasa = $next->NIK;
						}
						?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $row->NIK; ?></td>
							<td><?php echo $row->NAMA_PENGURUS; ?></td>
							<td><?php echo $row->NO_TRANSAKSI; ?></td>
							<td><?php echo $row->JUMLAH; ?></td>
							<td><?php echo $row->INSENTIF_PERUNIT; ?></td>
							<td><?php echo $insentif_perbaris; ?></td>
							<td><?php echo $row->TGL_SELESAIPENGURUSAN; ?></td>
						</tr>
						<?php 
						if(($birojasa != "" && $next->NIK != $row->NIK) || $no == $list->totaldata){
						?>
						<tr>
							<td colspan = '3'>TOTAL INSENTIF</td>
							<td></td>
							<td><?php echo $totalunit ?></td>
							<td></td>
							<td><?php echo $TOTAL_SELURUHNYA; ?></td>
							<td></td>
						</tr>
						<?php
						$totalunit = 0;
						$TOTAL_SELURUHNYA = 0;
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
   <button id="approve-btn" value="approve-btn" onclick="approvedata()" class="btn btn-success">Approve</button>
   <button id="reject-btn" value ="reject-btn" onclick="rejectdata()" class="btn btn-danger">Reject</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">

function approvedata(){
		document.getElementById("aksi").value = "Y";
		addData();
}
function rejectdata(){
		document.getElementById("aksi").value = "N";
		addData();
}  

</script>