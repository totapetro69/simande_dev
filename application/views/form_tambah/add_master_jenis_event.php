
<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/add_master_jenis_event_simpan');?>" method="post">
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Jenis Event</h4>
</div>

<div class="modal-body">
  <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Kode Jenis Event</label>
          <input type="text" style="text-transform:uppercase"  id="kd_jenis_event" name="kd_jenis_event" class="form-control" placeholder="Kode Jenis" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Nama Jenis Event</label>
          <input type="text-area" id="nama_jenis_event" name="nama_jenis_event" class="form-control" placeholder="Nama Jenis Event" >
        </div>
      </div>
    </div>
  </div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>