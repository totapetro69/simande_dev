<form id="addForms" class="bucket-form"  action="<?php echo base_url('setup/simpan_komposisi');?>" method="post">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"> Prosentase Leasing Setup</h4>
  </div>

  <div class="modal-body">
  <?php 
    $defaultDealer=$this->session->userdata("kd_dealer");
  ?>
  <div class="panel margin-bottom-10">
        <div class="panel-heading" style="height:100px;"">
          <div class="col-xs-12 col-md-6">
            <?php
            if($this->session->userdata("nama_group") == 'Root' || $this->session->userdata("nama_group") == 'Direct Sales'){
              ?>
              <div class="form-group">
              <label>Dealer</label>
              <select class="form-control" id="kd_dealer" name="kd_dealer">
                <option value="">-- Pilih Dealer -</option>
                <?php 
                  if(isset($dealer)){
                    if(($dealer->totaldata > 0)){
                        foreach ($dealer->message as $key => $value){
                          $select=($defaultDealer==$value->KD_DEALER)?" selected":"";
                          echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                        }
                    }
                  } 
                ?>
              </select>
            </div>
              <?php
            }else{
              ?>
              <div class="form-group">
              <label>Dealer</label>
              <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="true">
                <option value="">-- Pilih Dealer -</option>
                <?php 
                  if(isset($dealer)){
                    if(($dealer->totaldata > 0)){
                        foreach ($dealer->message as $key => $value){
                          $select=($defaultDealer==$value->KD_DEALER)?" selected":"";
                          echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                        }
                    }
                  } 
                ?>
              </select>
            </div>
              <?php
            }
            ?>
            
          </div>
          <div class="col-xs-12 col-md-4">
            <div class="form-group">
              <label>Tahun</label>
              <select class="form-control disabled-action" id="tahun" name="tahun">
                <option value="<?php echo date('Y')-2;?>"><?php echo date('Y')-2;?></option>
                <option value="<?php echo date('Y')-1;?>"><?php echo date('Y')-1;?></option>
                <option value="<?php echo date('Y');?>" selected="selected"><?php echo date('Y');?></option>
                <option value="<?php echo date('Y')+1;?>"><?php echo date('Y')+1;?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped tablex">
            <tr class='info'>
              <th class='col-md-1'>Urutan</th><th class='col-md-10'>Nama Leasing</th><th class='col-md-1'>Target(%)</th>
            </tr>
            <?php
              $rangking=array();
              $kd_leasing=array('FIF','ADR','MCF');
              $target_leasing=array();
              if($prosensales){
                if($prosensales->totaldata>0){
                  foreach ($prosensales->message as $key => $value) {
                    $rangking[]   =$value->RANGKING_LEASING;
                    $kd_leasing[]   =$value->KD_LEASING;
                    $target_leasing[]=$value->TARGET_LEASING*100;
                  }
                }
              }
              $x=0;
              for($i=1;$i<=3;$i++){
                $selected="";
                ?>
                  <tr>
                    <td class='text-center'><input type='text' id='rangking_<?php echo $i;?>' name='rangking_<?php echo $i;?>' class='on-grid form-control text-center' required value="<?php echo (count($rangking)>0)?$rangking[$x] :$i;?>"></td>
                    <td><select id='kd_leasing_<?php echo $i;?>' name='kd_leasing_<?php echo $i;?>' class='on-grid form-control' requeired="true">
                      <option value="">-- Pilih Nama Finansial Perusahaan -</option>
                      <?php
                        if($fincom){
                          if(is_array($fincom->message)){
                            foreach ($fincom->message as $key => $value) {
                              if(count($kd_leasing)>0){
                                $selected=($kd_leasing[($x)]==$value->KD_LEASING)?"selected":"";
                              }
                              
                              echo "<option value='".$value->KD_LEASING."' ".$selected.">[".$value->KD_LEASING."] ".$value->NAMA_LEASING."</option>";
                            }
                          }
                        }
                      ?>
                    </select></td>
                    <td><input type='text' id='target_leasing_<?php echo $i;?>' name='target_leasing_<?php echo $i;?>' class='txt on-grid form-control text-center' required value="<?php echo (count($target_leasing)>0)?$target_leasing[$x]:'0';?>"></td>
                  </tr>
                <?php
                $x++;
              }
            ?>
            <tr>
              <td class='text-center'><input type='text' id='rangking_4' name='rangking_4' class='on-grid form-control text-center' required value="<?php echo (count($rangking)>0)?$rangking[3] :4;?>"></td>
              <td><input id='kd_leasing_4' name='kd_leasing_4' class='on-grid form-control' value='OTH'/></td>
              <td><input type='text' id='target_leasing_4' name='target_leasing_4' class='txt on-grid form-control text-center' value="<?php echo (count($target_leasing)>0)?$target_leasing[3]:'0';?>" required="true"></td>
            </tr>
          </table>
        </div>
    </div>
  </div>
  <div class="modal-footer">
      <input type="hidden" id="totals" value="0">
      <button type="button" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close'></i> Batal</button>
      <button type="button" id="btn-submit" class="btn btn-danger disabled-action"><i clas='fa fa-save'></i> Simpan</button>
  </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    $("#addForms input[name^='target_leasing_']").ForceNumericOnly();
    $("#addForms .txt")
    .on('click',function(){$(this).focus().select()})
    .on('change',function(e){
      
      var total=0
      $("#addForms .txt").each(function(){
        total +=parseFloat($(this).val())
      });
      //alert(total);
      $('#totals').val(total);
      if((total)>100){
        $('#btn-submit').addClass('disabled-action');
        //$(this).focus().select();
        alert("Total Prosentase sudah lebih dari 100%");
        return false;
      }
     if((total)==100){
       $('#btn-submit').removeClass('disabled-action')
     }
    })
    .on("keypress",function(e){
      var total=parseInt($('#totals').val());
      if(e.which==13){
        //e.stopImmediatePropagation();
        e.preventDefault();
        var nom=$(this).attr('id');
            nom=(nom.split('_'))[2];
        
        if(parseInt(nom)<4){
          $('#target_leasing_'+(parseInt(nom)+1)).focus().select();
        }else{
          $('#btn-submit').focus();
        }
        
      }
      
    })
   $('#addForms #btn-submit').on('click',function(){
    var nama_1=$('#addForms #kd_leasing_1').val();
    var nama_2=$('#addForms #kd_leasing_2').val();
    var nama_3=$('#addForms #kd_leasing_3').val();
      if(nama_1==''){
        alert('Nama 1 Leasing belum di tentukan ');
        return
      }
      if(nama_2==''){
        alert('Nama 2 Leasing belum di tentukan ');
        return
      }
      if(nama_3==''){
        alert('Nama 3 Leasing belum di tentukan ');
        return
      }
      if(nama_1 == nama_2){
        alert("Nama Leasing tidak boleh doubel")
        return;
      }else if(nama_1 == nama_3){
        lert("Nama Leasing tidak boleh doubel")
        return;
      }else if(nama_2 == nama_3){
        lert("Nama Leasing tidak boleh doubel")
        return;
      }
      console.log($('#addForms').serialize());
      $('#loadpage').removeClass("hidden");
      $.ajax({
        type :'POST',
        url  : $('#addForms').attr('action'),
        data : $('#addForms').serialize(),
        dataType : 'json',
        success:function(result){
          if(result.status){
            $('.success').animate({ top: "0"}, 500);
            $('.success').html('Data berhasil di simpan').fadeIn();
            window.location.reload();
          }else{
            $('.error').animate({top: "0" }, 500);
            $('.error').html('Data gagal disimpan').fadeIn();
            setTimeout(function() {
              hideAllMessages();
            }, 4000);
            $('#loadpage').addClass("hidden");
          }
        }
      })
      
   });
  })
</script>
