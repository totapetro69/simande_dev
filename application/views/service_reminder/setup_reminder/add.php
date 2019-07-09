<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Setup Reminder</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('service_reminder/add_setupreminder_simpan'); ?>">
        <div class="form-group">
            <label>Type Service</label>
          <select name="type_srv_next" class="form-control">
             <option value="KPB1">KPB1</option>
             <option value="KPB2">KPB2</option>
             <option value="KPB3">KPB3</option>
             <option value="KPB4">KPB4</option>
             <option value="NONKPB">NONKPB</option>
         </select>
        </div>
        <div class="form-group">
            <label>Hari -x reminder </label>
            <input type="text" name="tgl_srv_reminder" id="tgl_srv_reminder" class="form-control" placeholder="Masukkan jumlah Hari-x reminder" >
        </div>
        <div class="form-group">
            <label>Hari Jatuh tempo Service</label>
            <input type="text" name="tgl_srv_next" id="tgl_srv_next" class="form-control" placeholder="Masukkan hari jatuh tempo service" >
        </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

