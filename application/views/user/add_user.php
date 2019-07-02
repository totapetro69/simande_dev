<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$defaultDealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$kd_div="";
$type_users="";$kd_lokasi="";$kd_level="";
$user_id="";$user_name="";$kd_group="";$apv_doce="";
$kd_md=$this->session->userdata("kd_maindealer");
if(isset($list)){
  if($list->totaldata >0){
    foreach ($list->message as $key => $value) {
      $type_users = $value->TYPE_USERS;
      $user_id = $value->USER_ID;
      $user_name = $value->USER_NAME;
      $kd_lokasi = $value->KD_LOKASI;
      $defaultDealer = $value->KD_DEALER;
      $kd_md = $value->KD_MAINDEALER;
      $kd_div = $value->KD_DIV;
      $kd_group = $value->KD_GROUP;
      $apv_doce = $value->APV_DOC;
      $kd_level = $value->KD_LEVEL;
    }
  }
}
$lock =($user_id)?'disabled-action':'';
?>


<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel"><i class='fa fa-user-md'></i> Tambah User</h4>
</div>

<div class="modal-body">


  <!-- <?php var_dump($this->session->userdata());?> -->

  <form id="addForm" class="bucket-form" action="<?php echo base_url('user/store_user');?>" method="post">
      <div class="row">
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>Type User</label>
              <select class="form-control <?php echo $lock;?>" name="type_users" id="type_users">
                <option value="" disabled="">-- Pilih ---</option>
                <option value="D" <?php echo ($type_users=='D')?'selected':'';?>>Dealer</option>
                <?php if(isRoot()){
                  ?>
                  <option value='MD' <?php echo ($type_users=='MD')?'selected':'';?>>Main Dealer</option>";
                  <?php
                }
                ?>
              </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div id="dealer_type" class="form-group" style="display: <?php echo ($type_users=='MD')?'none':'';?>">
              <label>Dealer <span class="loading-form"></span></label>
              <input type="text" name="kd_dealer" id="kd_dealers" class="form-control" required="true" value="<?php echo $defaultDealer;?>">
          </div>
          <div id="maindealer_type" class="form-group" style="display: <?php echo ($type_users=='D' || $type_users=='')?'none':'';?>">
              <label>Main Dealer <span class="loading-form"></span></label>
              <select name="kd_maindealer" id="kd_maindealer" class="form-control" required="true">
                <option value="">- Pilih Main Dealer -</option>
                <?php foreach ($maindealers->message as $key => $group_maindealer) : 
                    $default=($kd_md==$group_maindealer->KD_MAINDEALER)?" selected":'';
                ?>
                  <option value="<?php echo $group_maindealer->KD_MAINDEALER;?>" <?php echo $default;?> ><?php echo $group_maindealer->NAMA_MAINDEALER;?></option>
                <?php endforeach; ?>
              </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
            <label>Lokasi Dealer <span class="loading-form"></span></label>
            <select id="kd_lokasi" name="kd_lokasi" class="form-control" required>
              <option value="">- Pilih Lokasi -</option>
              <?php 
                if(isset($lokasidealer)){
                  if($lokasidealer->totaldata >0){
                    foreach ($lokasidealer->message as $key => $value) {
                      $pilih = ($kd_lokasi==$value->KD_LOKASI)?'selected':'';
                      ?>
                      <option value="<?php echo $value->KD_LOKASI;?>" <?php echo $pilih;?>><?php echo $value->NAMA_LOKASI;?></option>
                      <?php
                    }
                  }
                }
              ?>
            </select>

            <div id="lokasi_default" class="hidden">
              <option value="">- Pilih Lokasi -</option>
              <?php 
                if(isset($lokasidealer)){
                  if($lokasidealer->totaldata >0){
                    foreach ($lokasidealer->message as $key => $value) {
                      $pilih = ($kd_lokasi==$value->KD_LOKASI)?'selected':'';
                      ?>
                      <option value="<?php echo $value->KD_LOKASI;?>" <?php echo $pilih;?>><?php echo $value->NAMA_LOKASI;?></option>
                      <?php
                    }
                  }
                }
              ?>
            </div>

          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>NIK <span class="loading-nik"></span></label>
              <div id="usr_id">
                <input id="user_id" type="text" name="user_id" class="form-control <?php echo $lock;?>" placeholder="masukan NIK" autocomplete="off" required="true" value='<?php echo $user_id;?>' minlength="5">
              <!-- </div> -->
              <!-- <div class='hidden' id="usr_id_2"> -->
<!--                 <input id="user_id_2" type="text" name="user_id" class="form-control" placeholder="masukan NIK/User_ID" maxlength="10" autocomplete="off" required="true" value='<?php echo $user_id;?>'  min="5"> -->
              </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
            <label>Username <span class="loading-detail"></span></label>
            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="masukan username" autocomplete="off" required="true" value='<?php echo $user_name;?>'> 
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>Password <span class="loading-detail"></span></label>
              <input type="hidden" name="type_password" id="type_password">
              <input type="password" name="password" id="password" class="form-control <?php echo ($user_id)?'disabled-action':'';?>" placeholder="masukan password" autocomplete="off" required="true">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
            <label>Grup User</label>
            <select id="kd_group" name="kd_group" class="form-control" required="true">
              <option value="">- Pilih grup -</option>
              <?php 
                if(isset($groups)){
                  if($groups->totaldata >0){
                    foreach ($groups->message as $key => $group) {
                      $pilih=($kd_group==$group->KD_GROUP)?'selected':'';
                      ?>
                      <option value="<?php echo $group->KD_GROUP;?>" <?php echo $pilih;?>><?php echo $group->NAMA_GROUP;?></option>
                      <?php 
                    }
                  }
                } 
              ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
            <label>Level User</label>
            <select id="kd_level" name="kd_level" class="form-control">
              <option value="">- Pilih level -</option>
              <option value="0" <?php echo ($kd_level=='0')?'selected':'';?>>Manager</option>
              <option value="1" <?php echo ($kd_level=='1')?'selected':'';?>>Head</option>
              <option value="2" <?php echo ($kd_level=='2')?'selected':'';?>>Supervisor</option>
              <option value="3" <?php echo ($kd_level=='3')?'selected':'';?>>Operator</option>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
            <label>Divisi</label>
            <select id="kd_div" name="kd_div" class="form-control">
              <option value="">- Pilih divisi -</option>
              <?php 
                if(isset($divisions)){
                  if($divisions->totaldata >0){
                    foreach ($divisions->message as $key => $value) {
                      $pilih =($kd_div==$value->KD_DIVISI)?'selected':'';
                      ?>
                        <option value="<?php echo $value->KD_DIVISI;?>" <?php echo $pilih;?>><?php echo $value->NAMA_DIV;?></option>
                      <?php
                    }
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
          <div class="form-group">
            <label>Document Approval</label>
            <select class="form-control" name="apv_doc" id="apv_doc">
              <option value='0' <?php echo ($apv_doce=="0")?'selected':'';?>>Tidak</option>
              <option value='1' <?php echo ($apv_doce=="1")?'selected':'';?>>Ya</option>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-8 col-sm-8">
            <!-- <div class="form-group">
              <label>&nbsp;</label><br>
              <span id="app_info" class='small hidden'><em>Lanjutakan dengan setup document yang di approval dari menu list user icon <i class='fa fa-pencil-square'></i></em></span>
            </div> -->
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <div id="maindealer_area" class="table-responsive h250" style="display: none;">
            <table id="user_area" class="table  table-sm">
              <thead class="table-info">
                  <tr>
                      <th></th>
                      <th>KD DEALER</th>
                      <th>Nama Dealer</th>
                      <th>Jenis Dealer</th>
                  </tr>
              </thead>
              <tbody>


                <?php 
                //if($user_area && (is_array($user_area->message) || is_object($user_area->message))): 
                if(isset($user_area)){
                  if($user_area->totaldata >0){
                    foreach ($user_area->message as $key => $userarea_row) { ?>
                      <tr>
                          <td>
                              <label class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input user_area_data" value="<?php echo $userarea_row->KD_DEALER;?>">
                                  <span class="custom-control-indicator"></span>
                              </label>
                          </td>
                          <td><?php echo $userarea_row->KD_DEALER;?></td>
                          <td class='table-nowarp'><?php echo $userarea_row->NAMA_DEALER;?></td>
                          <td><?php echo ($userarea_row->KD_JENISDEALER=='Y')?'Cabang':'Dealer';?></td>
                      </tr> 
                    <?php 
                    }
                  }
                }
                //endforeach; endif;?>
              </tbody>
          </table>
          </div>
        </div>
      </div>
  </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="store-btn" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
  
var date = new Date();
date.setDate(date.getDate());

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
var datadealer=[];
var status_cabang = "<?php echo $this->session->userdata('status_cabang');?>";
var nik="<?php echo $this->input->get("n");?>";
var dlrs ="<?php echo $defaultDealer;?>";


$(document).ready(function(){
  
  lokDealer();

  if(!nik){
    $('#password').attr('required',true);
    $('#kd_dealers').removeAttr('required');
    if(dlrs){
      user_nik(status_cabang);
    }
  }else{
    $('#password').removeAttr("required");
    $('#kd_dealers').attr('required',true);
  }

  getDealer();

  $('.maskspace').mask('XXXXXXXXXXXXXXXXXXXX', {
      translation: {
          'X': {
              pattern: /[A-Za-z0-9]/, optional: true
          }
      }
  });

  $('#type_users').on("change",function(){
    lokDealer();
  });

  $('.date').datepicker({ 
      format: 'dd/mm/yyyy',
      endDate: date,
      autoclose: true
  });


  $('#store-btn').click(function(event){
    $("#addForm").valid();

      $("#addForm").validate({
          focusInvalid: false,
          invalidHandler: function(form, validator) {

              if (!validator.numberOfInvalids())
                  return;

              $('html, body').animate({
                  scrollTop: $(validator.errorList[0].element).offset().top
              }, 2000);

          }
      });

      if ($("#addForm").valid()) {
        event.preventDefault();
        storeData();
      }
  });

  $('#kd_dealers').change(function(){    
      var kd_dealer= $('#kd_dealers').val();
      var jD = 'T';

      var dx=datadealer.findIndex(obj => obj['KD DEALER'] === kd_dealer);
      if(dx>-1){
        jD= datadealer[dx]["KD JENISDEALER"];
      }

      // console.log(jD);

      if(jD =='Y'){
        if(nik){ return false;}
        user_nik(jD);
      }else{
        $("#user_name").removeAttr('readonly');

        show_userid('D');
        /*$('#usr_id_2').removeClass('hidden');
        $('#usr_id').addClass('hidden');*/
      }
      //lokasi dealer
  })

  $('#apv_doc').change(function(){
    if($(this).val()=='1'){
      $('#app_info').removeClass('hidden');
    }else{
      $('#app_info').addClass('hidden');
    }
  })
});

function lokDealer()
{
  var type_users = $('#type_users').val();// $("input[type=radio][name=type_users]:checked").val();
  var kd_dealer = $("#kd_dealers").val();
  var kd_maindealer = $("#kd_maindealer").val();
  var html_lokasi_dealer = $("#lokasi_default").html();

  $(".loading-form").html("<i class='fa fa-spinner fa-spin'></i>");
  $.getJSON(http+"/user/add_user/true",{'type_users':type_users, 'kd_dealer':kd_dealer, 'kd_maindealer':kd_maindealer},
  function(result){
    var lokasidealer = '';

    if(result.lokasidealer.totaldata>0){
      $.each(result.lokasidealer.message,function(e,d){
        lokasidealer += "<option value="+d.KD_LOKASI+">"+d.NAMA_LOKASI+"</option>";
      })
    }

    // console.log(lokasidealer);
    //$("#kd_lokasi").html(lokasidealer);

    if(type_users=='MD'){
      $("#maindealer_type").removeAttr('style');
      $("#dealer_type").css('display','none');
      $("#maindealer_area").removeAttr('style');
      $("#kd_lokasi").html('<option value="MD">MD</option>');

    }
    else{
      $("#dealer_type").removeAttr('style');
      $("#maindealer_type").css('display','none');
      $("#maindealer_area").css('display','none');
      $("#kd_lokasi").html(html_lokasi_dealer);
    }
    
    user_nik(status_cabang);

    $(".loading-form").html("");
    
  });

}

function getDealer(){
  var url = http+"/user/add_user/true";
  $(".loading-form").html("<i class='fa fa-spinner fa-spin'></i>");

  $.getJSON(url,function(result){
    if(result.dealer.message.length > 0){
      $.each(result.dealer.message,function(index,d){
        datadealer.push({
          'KD DEALER':d.KD_DEALER,
          'NAMA DEALER':d.NAMA_DEALER,
          'KD JENISDEALER':d.KD_JENISDEALER
        })
      })
    }
    // console.log(datadealer);
    $('#kd_dealers').inputpicker({
      data : datadealer,
      fields :['KD DEALER', 'NAMA DEALER', 'KD JENISDEALER'],
      fieldValue :'KD DEALER',
      fieldText:'NAMA DEALER',
      filterOpen :true
    });

    $(".loading-form").html("");
  })
  
}

function show_userid(type_users)
{
  if(type_users == 'D'){
    $("#usr_id").html('<input id="user_id" type="text" name="user_id" class="form-control <?php echo $lock;?>" placeholder="masukan NIK" autocomplete="off" required="true" value="<?php echo $user_id;?>" minlength="5">');
  }
  else{
    $("#usr_id").html('<input id="user_id_2" type="text" name="user_id" class="form-control" placeholder="masukan NIK/User_ID" maxlength="10" autocomplete="off" required="true" value="<?php echo $user_id;?>"  minlength="5">');
  }
}


function user_nik(cabang){
  $("#user_id").val('');
  $("#user_name").val('');
  $("#password").val('');
  var kd_dealer = $("#kd_dealers").val();
  var type_users = $('#type_users').val();// $("input[type=radio][name=type_users]:checked").val();
  var jD = 'T';

  var dx=datadealer.findIndex(obj => obj['KD DEALER'] === kd_dealer);
  if(dx>-1){
    jD= datadealer[dx]["KD JENISDEALER"];
  }

  // alert(type_users+'|'+cabang+'|'+jD);

  cabang=(cabang)?cabang:jD;
  if(type_users == 'D') {
    if(cabang=='Y'){
      $("#user_name").attr('readonly', 'readonly');
      var url = http+"/user/get_nik/"+kd_dealer;
      $("#user_name").attr('readonly',true);

      show_userid(type_users);
      /*$('#usr_id_2').addClass('hidden');
      $('#usr_id').removeClass('hidden');*/
      __nik(url);   
    }else{
      $("#user_name").removeAttr('readonly');
      show_userid(type_users);
      /*$('#usr_id_2').removeClass('hidden');
      $('#usr_id').addClass('hidden');*/
    }
  }
  else {

    var url = http+"/user/get_nik/MD";
    $("#user_name").removeAttr('readonly');

    show_userid('D');
    /*$('#usr_id_2').addClass('hidden');
    $('#usr_id').removeClass('hidden');*/
    __nik(url);   

    /*$("#user_name").removeAttr('readonly');
    show_userid(type_users);*/
  }
}
function __nik(url){
  $(".loading-nik").html("<i class='fa fa-spinner fa-spin'></i>");
  $.getJSON(url,function(result){

    var datrans=[];
    if(result.length > 0){
      $.each(result,function(index,d){
        datrans.push({
          'NIK':d.NIK,
          'NAMA':d.NAMA,
          'PASSWORD':d.PASSWORD
        })
      })
    }
    // $('#user_id').removeClass('hidden');
    $('#user_id').inputpicker({
      data : datrans,
      fields :['NIK', 'NAMA'],
      fieldValue :'NIK',
      fieldText:'NIK',
      filterOpen :true
    })
    /*.on("change",function(){
      var dx=datrans.findIndex(obj => obj['NIK'] === $(this).val());
      if(dx>-1){
        var oldPass = datrans[dx]['PASSWORD'];
        $('#user_name').val(datrans[dx]['NAMA'])
        $('#user_id_2').val($(this).val());
        if(oldPass != ''){
          $("#type_password").val('old');
          $("#password").val(oldPass);
        }
        else{
          $("#type_password").val('new');
          $("#password").val('');
        }

      }
    });*/
    $(".loading-nik").html("");
  })
}

$('#addForm').on('change', '#user_id', function(){
  var user_id = $(this).val();
  var url = http+"/user/get_detail_karyawan";
  $(".loading-detail").html("<i class='fa fa-spinner fa-spin'></i>");

  $.getJSON(url, {'user_id' : user_id}, function(result){
    // console(result);
    var oldPass = result.password;
    $('#user_name').val(result.nama);
    $('#user_id_2').val(user_id);
    $('#kd_div').val(result.kd_divisi);

    if(oldPass != ''){
      $("#type_password").val('old');
      $("#password").val(oldPass);
      $("#password").attr('readonly', 'readonly');
    }
    else{
      $("#type_password").val('new');
      $("#password").val('');
      $("#password").removeAttr('readonly');
    }


    $(".loading-detail").html("");
  });

});


function __data(){
  var data=[];

  var totalchecked = $('.user_area_data:checkbox:checked').length;

  if(totalchecked > 0)
  {

    var totaldata = $('.user_area_data:checkbox').length;
    
    for(i = 0; i < totaldata; i++)
    {

      var statuschecked = $(".user_area_data:eq(" + i + "):checkbox:checked").length;

      if(statuschecked == 1){
        var kd_lokasi = $(".user_area_data:eq(" + i + "):checkbox:checked").val();

        data.push({
          'user_id': $("#user_id").val(),
          'kd_dealer': $(".user_area_data:eq(" + i + "):checkbox:checked").val(),
          'auth_status': 1
        });
        
      }


    }


  }

  return data;
  
}

function storeData()
{
 
  var defaultBtn = $("#store-btn").html();

  var data_form=__data();
  // console.log(data_form);
  var act = $("#addForm").attr('action');

  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Process...");
  
  $.ajax({
    url:act,
    type:"POST",
    dataType: "json",
    data:$("#addForm").serialize()+'&detail='+JSON.stringify(data_form),
    success:function(result){

      if (result.status == true) 
      {
       
        $('.success').animate({ top: "0" }, 500).fadeIn();
        $('.success').html(result.message);

        setTimeout(function(){
            location.reload();
        }, 2000);
      }else{

        $('.error').animate({ top: "0" }, 500).fadeIn();
        $('.error').html(result.message);

        setTimeout(function () {
            hideAllMessages();
            $("#save-btn").removeClass("disabled");
            $("#save-btn").html(defaultBtn);
        }, 4000);
        
      }

    }
  });

}
</script>

