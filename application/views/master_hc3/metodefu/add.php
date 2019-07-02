<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Metode FU</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_hc3/add_metodefu_simpan'); ?>">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_metode" id="nama_metode" class="form-control" placeholder="Masukkan nama metode fu" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
    
    $(document).ready(function(){
        $('#nama_metode').keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
            }
        });
    })

</script>