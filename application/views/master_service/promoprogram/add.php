<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Promo Program</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/add_promoprogram_simpan'); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_promo" id="kd_promo" class="form-control" placeholder="Masukkan kode promo program" >
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_program" id="nama_program" class="form-control" placeholder="Masukkan nama promo program" >
        </div>
		<div class="form-group">
       <label>Tanggal Mulai</label>
		   <div class="input-group input-append date" id="date">
			   <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy" />
			   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		   </div>
		</div>
		<div class="form-group">
		   <label>Tanggal Selesai</label>
		   <div class="input-group input-append date" id="date">
			   <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy" />
			   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		   </div>
		</div>
		<div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

