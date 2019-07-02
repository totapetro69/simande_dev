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
<?php
	$no_proses = 'INSPS'.date('Ym');
	if($no_proses != $list->message[0]->NAMA_CONFIG){
		$no_proses_final = $no_proses."0001";
		$value_config = 1;
	} else{
		$value = $list->message[0]->VALUE_CONFIG + 1;
		$num = sprintf("%04d", $value);
		$no_proses_final = $no_proses.$num;
		$value_config = $value;
	}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Proses Insentif PIC STNK</h4>
</div>
<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('report_inspen/proses_insentifpic_stnk/'.$list->message[0]->ID);?>" method="post">
	<input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <input type="hidden" name="kd_config" id="kd_config" class="form-control" value="NO_PIPS" />
	<input type="hidden" name="nama_config" id="nama_config" class="form-control" value="<?php echo $no_proses?>" />
	<input type="hidden" name="value_config" id="value_config" class="form-control" value="<?php echo $value_config?>" />
	
   <div class="form-group">
    <label>Tanggal Proses</label>
    <input id="tgl_proses" readonly type="text" name="tgl_proses" class="form-control" value=" <?php echo date("d-m-Y");?>">
  </div>
  <div class="form-group">
    <label>Nomor Proses</label>
    <input id="no_proses" readonly type="text" name="no_proses" class="form-control" value="<?php echo $no_proses_final;?>">
  </div>
  <div class="form-group">
    <label>Periode</label>
	<select id="periode" type="text" name="periode" required class="form-control">
		<option value="" >-- Pilih Bulan --</option>
		<option value="Januari <?php echo date('Y') - 1;?>" >Januari <?php echo date('Y') - 1;?></option>
		<option value="Februari <?php echo date('Y') - 1;?>" >Februari <?php echo date('Y') - 1;?></option>
		<option value="Maret <?php echo date('Y') - 1;?>" >Maret <?php echo date('Y') - 1;?></option>
		<option value="April <?php echo date('Y') - 1;?>" >April <?php echo date('Y') - 1;?></option>
		<option value="Mei <?php echo date('Y') - 1;?>" >Mei <?php echo date('Y') - 1;?></option>
		<option value="Juni <?php echo date('Y') - 1;?>" >Juni <?php echo date('Y') - 1;?></option>
		<option value="Juli <?php echo date('Y') - 1;?>" >Juli <?php echo date('Y') - 1;?></option>
		<option value="Agustus <?php echo date('Y') - 1;?>" >Agustus <?php echo date('Y') - 1;?></option>
		<option value="September <?php echo date('Y') - 1;?>" >September <?php echo date('Y') - 1;?></option>
		<option value="Oktober <?php echo date('Y') - 1;?>" >Oktober <?php echo date('Y') - 1;?></option>
		<option value="November <?php echo date('Y') - 1;?>" >November <?php echo date('Y') - 1;?></option>
		<option value="Desember <?php echo date('Y') - 1;?>" >Desember <?php echo date('Y') - 1;?></option>
		<option value="Januari <?php echo date('Y');?>" >Januari <?php echo date('Y');?></option>
		<option value="Februari <?php echo date('Y');?>" >Februari <?php echo date('Y');?></option>
		<option value="Maret <?php echo date('Y');?>" >Maret <?php echo date('Y');?></option>
		<option value="April <?php echo date('Y');?>" >April <?php echo date('Y');?></option>
		<option value="Mei <?php echo date('Y');?>" >Mei <?php echo date('Y');?></option>
		<option value="Juni <?php echo date('Y');?>" >Juni <?php echo date('Y');?></option>
		<option value="Juli <?php echo date('Y');?>" >Juli <?php echo date('Y');?></option>
		<option value="Agustus <?php echo date('Y');?>" >Agustus <?php echo date('Y');?></option>
		<option value="September <?php echo date('Y');?>" >September <?php echo date('Y');?></option>
		<option value="Oktober <?php echo date('Y');?>" >Oktober <?php echo date('Y');?></option>
		<option value="November <?php echo date('Y');?>" >November <?php echo date('Y');?></option>
		<option value="Desember <?php echo date('Y');?>" >Desember <?php echo date('Y');?></option>
	</select>
  </div>
  
</form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" onclick="addData2();" class="btn btn-danger">Proses Insentif</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(e){
	
	});
 
 function addData2(){
	var period = document.getElementById("periode").value;
	if(period == ""){
		alert("Pilih Periode!");
	} else {
		addData();
	}
	 
 }
  

</script>