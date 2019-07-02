
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Tambah Master Insentif H3</h4>
</div>

<div class="modal-body">
     <div class="row">
        <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_masterinsentifh3_simpan');?>" method="post">
            
           
            <div class="col-xs-12 col-sm-12">
                  <div class="form-group" id="kd_dealer">
                   <label>Kode Dealer</label>
                   <select name="kd_dealer" class="form-control">
                     <option value="">- Pilih Dealer -</option>
                     <?php if($dealer && (is_array($dealer->message) || is_object($dealer->message))): foreach ($dealer->message as $key => $value) : ?>
                       <option value="<?php echo $value->KD_DEALER;?>"><?php echo $value->KD_DEALER;?> - <?php echo $value->NAMA_DEALER;?></option>
                     <?php endforeach; endif;?>
                   </select>
                 </div>
            </div>
            
             <div class="col-xs-12 col-sm-12">
                  <div class="form-group" id="kd_dealer">
                   <label>Karyawan</label>
                   <select name="nik" class="form-control">
                     <option value="">- Pilih Karyawan -</option>
                     <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
                       <option value="<?php echo $value->NIK;?>"><?php echo $value->NIK;?> - <?php echo $value->NAMA;?></option>
                     <?php endforeach; endif;?>
                   </select>
                 </div>
            </div>

            
   
      <div class="col-xs-12 col-sm-12">
            <div class="form-group">
              <label>Persentase</label>
              <input id="persentase" type="text" name="persentase" class="form-control">
            </div>
      </div>
 


        </form>
     </div>


</div>

<div class="modal-footer">
  <a class="btn btn-default" href="<?php echo base_url('setup/masterinsentifh3');?>">Batal</a>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
 
</div>

<script type="text/javascript">

    var min = new Date().getFullYear(),
    max = min + 2,
            //max2 = min + 3,
    select = document.getElementById('tahun');
    select2 = document.getElementById('tahun2');
    //select3 = document.getElementById('tahun3');

    for (var i = min; i <= max; i++) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
        //console.log("Tes");
        //select2.appendChild(opt);
    }
    
    for (var i = min; i <= max; i++) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        //select.appendChild(opt);
        select2.appendChild(opt);
    }
    

   

   
</script>





