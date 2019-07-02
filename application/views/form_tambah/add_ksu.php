<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan KSU</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('inventori/add_ksu_simpan'); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_ksu" id="kd_ksu" class="form-control" placeholder="Masukkan kode KSU" >
        </div>

        <div class="form-group">
            <label>Nama KSU</label>
            <input type="text" name="nama_ksu" id="nama_ksu" class="form-control" placeholder="Masukkan nama KSU" >
        </div>
		
		<div class="form-group">
            <label>Jumlah</label>
            <input type="text" min="0" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan jumlah KSU" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
	
	$(document).ready(function(e){
		
		$('#jumlah')
			.focusout(function(){

			})
			.ForceNumericOnly()
			/*.popover({
			placement:'top',
			html:true,
			title:'<i class=\'fa fa-info-circle fa-fw\'></i> Informasi',
			content:'Informasi demand and supply untuk po bulan ini'
		});*/
		
		//unsetSession(-1);
	});

</script>

