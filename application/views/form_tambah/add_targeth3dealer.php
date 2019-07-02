
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Tambah Master Target H3 Dealer</h4>
</div>

<div class="modal-body">
     <div class="row">
        <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_targeth3dealer_simpan');?>" method="post">
            
            <div class="col-xs-12 col-sm-12">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="">- Pilih Kategori -</option>
                        <option value="Penjualan">Penjualan</option>
                        <option value="Pembelian">Pembelian</option>
                   </select>
               </div>
            </div>
            <div class="col-xs-12 col-sm-12">
                  <div class="form-group" id="kd_dealer">
                   <label>Kode Dealer</label>
                   <select name="kd_dealer" class="form-control">
                     <option value="">- Pilih Dealer -</option>
                     <?php if($dealer && (is_array($dealer->message) || is_object($dealer->message))): foreach ($dealer->message as $key => $value) : ?>
                       <option value="<?php echo $value->KD_DEALER;?>"><?php echo $value->NAMA_DEALER;?></option>
                     <?php endforeach; endif;?>
                   </select>
                 </div>
            </div>

            <div class="col-xs-4 col-sm-4">
                      <div class="row">
                          <div class="col-xs-12 col-sm-12">
                              <div class="col-xs-12 col-sm-7">
                                  <div class="form-group">
                                      <label>Bulan</label>
                                      <select class="form-control" name="bulan" id="bulan">
                                          <option value='1' <?php
                                          if (date("m") == 1) {
                                              echo 'selected';
                                          }
                                          ?> >January</option>
                                          <option value='2' <?php
                                          if (date("m") == 2) {
                                              echo 'selected';
                                          }
                                          ?> >February</option>
                                          <option value='3' <?php
                                          if (date("m") == 3) {
                                              echo 'selected';
                                          }
                                          ?> >March</option>
                                          <option value='4' <?php
                                          if (date("m") == 4) {
                                              echo 'selected';
                                          }
                                          ?> >April</option>
                                          <option value='5' <?php
                                          if (date("m") == 5) {
                                              echo 'selected';
                                          }
                                          ?> >May</option>
                                          <option value='6' <?php
                                          if (date("m") == 6) {
                                              echo 'selected';
                                          }
                                          ?> >June</option>
                                          <option value='7' <?php
                                          if (date("m") == 7) {
                                              echo 'selected';
                                          }
                                          ?> >July</option>
                                          <option value='8' <?php
                                          if (date("m") == 8) {
                                              echo 'selected';
                                          }
                                          ?> >August</option>
                                          <option value='9' <?php
                                          if (date("m") == 9) {
                                              echo 'selected';
                                          }
                                          ?> >September</option>
                                          <option value='10' <?php
                                          if (date("m") == 10) {
                                              echo 'selected';
                                          }
                                          ?> >October</option>
                                          <option value='11' <?php
                                          if (date("m") == 11) {
                                              echo 'selected';
                                          }
                                          ?> >November</option>
                                          <option value='12' <?php
                                          if (date("m") == 12) {
                                              echo 'selected';
                                          }
                                          ?> >December</option>
                                      </select>
                                  </div>
                              </div>

                              <div class="col-xs-12 col-sm-5">
                                  <div class="form-group">
                                      <label>Tahun</label>
                                      <select class="form-control" name="tahun" id="tahun">
                                      </select>
                                  </div>
                              </div>

                          </div>

                      </div>

                  </div>

            
   
      <div class="col-xs-12 col-sm-12">
            <div class="form-group">
              <label>Target</label>
              <input id="target" type="text" name="target" class="form-control">
            </div>
      </div>
 


        </form>
     </div>


</div>

<div class="modal-footer">
  <a class="btn btn-default" href="<?php echo base_url('setup/targeth3dealer');?>">Batal</a>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
 
</div>

<script type="text/javascript">

    var min = new Date().getFullYear(),
    max = min + 2,
            //max2 = min + 3,
    select = document.getElementById('tahun');
    //select2 = document.getElementById('tahun2');
    //select3 = document.getElementById('tahun3');

    for (var i = min; i <= max; i++) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
        //console.log("Tes");
        //select2.appendChild(opt);
    }
    
   
    

   

   
</script>





