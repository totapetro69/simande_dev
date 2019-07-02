<?php

$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');

?>


<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">List Dealer Aksess</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" action="<?php echo base_url('user/store_user');?>" method="post">

      <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id;?>">
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <div id="maindealer_area" class="table-responsive h400">
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
                // if($user_area && (is_array($user_area->message) || is_object($user_area->message))): 
                if(isset($user_area)){
                  if($user_area->totaldata >0){
                    foreach ($user_area->message as $key => $userarea_row) { 
                    $checked = '' ; 
                    //if($list && (is_array($list->message) || is_object($list->message))): 
                      if(isset($list)){
                        if($list->totaldata >0){
                          foreach ($list->message as $key => $userarea_list) {
                            if($userarea_row->KD_DEALER == $userarea_list->KD_DEALER) $checked = 'checked' ;
                          }
                        }
                      }
                    //}
                  
                  ?>

                  <tr>
                      <td>
                          <label class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input user_area_data" value="<?php echo $userarea_row->KD_DEALER;?>" <?php echo $checked;?>>
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
                ?>
              </tbody>
          </table>
          </div>
        </div>
      </div>

    </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
  <!-- <button id="store-btn" class="btn btn-danger submit-btn">Simpan</button> -->
</div>

<script type="text/javascript">
  
var date = new Date();
date.setDate(date.getDate());

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function(){

  $(".user_area_data").click(function(){

    var status = $(this).prop('checked') == true?1:0;
    var kd_dealer = $(this).val();
    var user_id = $("#user_id").val();

    var url = http+'/user/user_list_update';
    
    var data = {
        status : status,
        kd_dealer : kd_dealer,
        user_id : user_id
    }


    $.ajax({
      url:url,
      type:"POST",
      dataType: "json",
      data:data,
      success:function(result){

        if (result.status == true) 
        {
         
          $('.success').animate({ top: "0" }, 500).fadeIn();
          $('.success').html(result.message);

          setTimeout(function () {
              hideAllMessages();
          }, 4000);
        }else{

          $('.error').animate({ top: "0" }, 500).fadeIn();
          $('.error').html(result.message);

          setTimeout(function () {
              hideAllMessages();
          }, 4000);
          
        }

      }
    });



  });
});




function storeData()
{
 
  var defaultBtn = $("#store-btn").html();

  // $("#store-btn").addClass("disabled");
  // $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  
  var data_form=__data();
  // var url = http+'/pajak/store_pajakunit';
  // var formData = $("#addForm").serialize();
  // var formData = JSON.stringify(jQuery("#addForm").serializeArray());
  // var formData = JSON.stringify(jQuery("#addForm").serialize());

  var act = $("#addForm").attr('action');

  var data = {
      type_users : $("input[type=radio][name=type_users]:checked").val(),
      kd_dealer : $("#kd_dealers").val(),
      kd_maindealer : $("#kd_maindealer").val(),
      user_id : $("#user_id").val(),
      user_name : $("#user_name").val(),
      type_password : $("#type_password").val(),
      password : $("#password").val(),
      kd_lokasi : $("#kd_lokasi").val(),
      kd_group : $("#kd_group").val(),
      kd_level : $("#kd_level").val(),
      kd_div : $("#kd_div").val(),

      detail : data_form
  }


  // console.log(data);
  
  $.ajax({
    url:act,
    type:"POST",
    dataType: "json",
    data:data,
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

