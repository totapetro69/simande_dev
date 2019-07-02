
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Delivery Unit</h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" action="<?php echo base_url('inventori/du');?>" method="post">

		<div class="form-group">
            <label > <b> Status : </b></label>
            <select id="single_select" class="form-control" >
                <option>Process</option>
                <option>Aproved</option>
                <option>Rejected</option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="date">Tanggal Diterima</label>
            <div class="input-group input-append date" id="date">
                <input type="text" class="form-control" name="date" placeholder="MM/DD/YY" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
        
        <div class="form-group">
			<label>Unggah</label>
			<input type="file"  name="dokumen" placeholder="surat jalan">
        </div>
</form>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger">Simpan</button>
</div>
