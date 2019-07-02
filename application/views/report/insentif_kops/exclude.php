
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Exclude</h4>
</div>

<div class="modal-body">
    <form id="addForm" class="bucket-form" action="<?php echo base_url('laporan_insentif/exclude_kops_simpan');?>" method="post">   
        <div class="form-group">
            <label>No Mesin</label>
            <input id="no_mesin" type="text" name="no_mesin" class="form-control">
            <input id="nik" type="hidden" name="nik" value="<?php echo $this->input->get('nik_salesman') ?>" class="form-control">
            <input id="kd_maindealer" type="hidden" name="kd_maindealer" value="<?php echo $this->input->get('kd_main') ?>" class="form-control">
            <input id="kd_dealer" type="hidden" name="kd_dealer" value="<?php echo $this->input->get('kd_dealer') ?>" class="form-control">
            <input id="tgl_awal" type="hidden" name="tgl_awal" value="<?php echo $this->input->get('tgl_awal') ?>" class="form-control">
            <input id="tgl_akhir" type="hidden" name="tgl_akhir" value="<?php echo $this->input->get('tgl_akhir')?>" class="form-control">
        </div>  
    </form> 
</div>

<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
  
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


