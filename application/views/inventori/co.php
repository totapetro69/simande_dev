
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Checkout</h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" action="<?php echo base_url('inventori/co');?>" method="post">

		<div class="form-group">
            <label >Nama Petugas</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama ">
        </div>

        <div class="form-group">
                    <label class="control-label" for="date">Tanggal Diterima</label>
                    <div class="input-group input-append date" id="date">
                        <input type="text" class="form-control" name="date" placeholder="MM/DD/YY" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
         </div>
        
        
</form>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger">Simpan</button>
</div>
