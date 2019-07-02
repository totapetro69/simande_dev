<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Edit Master Target H3 Dealer</h4>
</div>

<div class="modal-body">
    <div class="row">
        <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_targeth3dealer/'.$list->message[0]->ID);?>" method="post">
            <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
            <div class="col-xs-12 col-sm-12">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="<?php echo $list->message[0]->KATEGORI;?>"><?php echo $list->message[0]->KATEGORI;?></option>
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
                        <option value="<?php echo $value->KD_DEALER;?>" <?php echo ($value->KD_DEALER == $list->message[0]->KD_DEALER ? "selected" : "");?>><?php echo $value->NAMA_DEALER;?></option>
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
                                          if (substr($list->message[0]->START_DATE,5,2) == '01') {
                                              echo 'selected';
                                          }
                                          ?> >January</option>
                                          <option value='2' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '02') {
                                              echo 'selected';
                                          }
                                          ?> >February</option>
                                          <option value='3' <?php
                                         if (substr($list->message[0]->START_DATE,5,2) == '03') {
                                              echo 'selected';
                                          }
                                          ?> >March</option>
                                          <option value='4' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '04') {
                                              echo 'selected';
                                          }
                                          ?> >April</option>
                                          <option value='5' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '05') {
                                              echo 'selected';
                                          }
                                          ?> >May</option>
                                          <option value='6' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '06') {
                                              echo 'selected';
                                          }
                                          ?> >June</option>
                                          <option value='7' <?php
                                         if (substr($list->message[0]->START_DATE,5,2) == '07') {
                                              echo 'selected';
                                          }
                                          ?> >July</option>
                                          <option value='8' <?php
                                         if (substr($list->message[0]->START_DATE,5,2) == '08') {
                                              echo 'selected';
                                          }
                                          ?> >August</option>
                                          <option value='9' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '09') {
                                              echo 'selected';
                                          }
                                          ?> >September</option>
                                          <option value='10' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '10') {
                                              echo 'selected';
                                          }
                                          ?> >October</option>
                                          <option value='11' <?php
                                          if (substr($list->message[0]->START_DATE,5,2) == '11') {
                                              echo 'selected';
                                          }
                                          ?> >November</option>
                                          <option value='12' <?php
                                         if (substr($list->message[0]->START_DATE,5,2) == '12') {
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
                 <input id="target" type="text" name="target" class="form-control" value="<?php echo $list->message[0]->TARGET;?>">
               </div>
            </div>

            <div class="col-xs-12 col-sm-12">
                <div class="form-group">
                    <label>Status</label>
                    <select name="row_status" class="form-control">
                        <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
                        <?php
                        if($list->message[0]->ROW_STATUS == -1){
                          ?>
                          <option value="0">Aktif</option>
                          <?php
                        }else{
                          ?>
                          <option value="-1">Tidak Aktif</option>
                          <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
