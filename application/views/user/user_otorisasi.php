  <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
  ?>
  <section class="wrapper">

    <style type="text/css">
      .menu .accordion-heading {  position: relative; }
    .menu .accordion-heading .edit {
        position: absolute;
        top: 8px;
        right: 30px; 
    }
    .menu .area { border-left: 4px solid #f38787; }
    .menu .equipamento { border-left: 4px solid #65c465; }
    .menu .ponto { border-left: 4px solid #98b3fa; }
    .menu .collapse.in { overflow: visible; }


    .accordion{margin-bottom:20px;}
    .accordion-group{margin-bottom:2px;border:1px solid #e5e5e5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
    .accordion-heading{border-bottom:0;}
    .accordion-heading .accordion-toggle{display:block;padding:8px 15px;}
    .accordion-inner{padding:9px 15px;border-top:1px solid #e5e5e5;}
    </style>
<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->



  <div class="breadcrumb margin-bottom-10">
    
    <?php echo breadcrumb();?>


    <div class="bar-nav pull-right ">
      <a class="btn btn-default" href="<?php echo base_url("user/user_list");?>"><i class="fa fa-users"></i> List Users</a>
      <a class="btn btn-default" href="<?php echo base_url("user/user_group_list");?>"><i class="fa fa-list-ol"></i> List Group</a>
      <a class="btn btn-default" href="<?php echo base_url("modul/modul_list");?>"><i class="fa fa-cogs"></i> List Modul</a>
    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          User List
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border">

        <form id="filterForm" action="<?php echo base_url('user/user_otorisasi') ?>" class="bucket-form" method="get">

          <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6">
                  
              <div class="form-group">
                <label>Grup User</label>
                <select id="kd_group" name="kd_group" class="form-control">
                  <option value="">- Pilih grup -</option>
                  <?php 
                    if(isset($groups)){
                      if($groups->totaldata>0){
                        foreach ($groups->message as $key => $value) {
                          //$pilih=($this->session->userdata("kd_group")==$value->KD_GROUP)?"selected":"";
                          $pilih=($this->input->get("kd_group")==$value->KD_GROUP)?'selected':"";
                          ?>
                          <option value="<?php echo $value->KD_GROUP;?>" <?php echo $pilih;?>><?php echo $value->NAMA_GROUP;?></option>
                          <?php
                        }
                      }
                    }
                  ?>
                </select>
              </div>

            </div>


          </div>

        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel">
      
      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> User otorisasi
          <div class="pull-right">
            <span class="badge bg-info">create</span>
            <span class="badge bg-success">edit</span>
            <span class="badge bg-warning">view</span>
            <span class="badge bg-danger">print</span>
          </div>  
      </div>

      <div class="panel-body panel-body-border">
        <?php echo $tree;?>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">

  var path = window.location.pathname.split('/');
  var http = window.location.origin + '/' + path[1];

  $(document).ready(function(){

    var kd_group = $("#kd_group").val();

    
    if(kd_group == ''){
      $('.checked').attr('disabled', 'disabled');
    }
    else{
      $('.checked').removeAttr('disabled');
    }

    $(".checked").click(function(){
      var headerId = $(this).parents('.accordion-toggle').attr('id');
      var id = $("#"+headerId).children("input[name='id']").val();

      var totalChecked = $("#"+headerId).find(".checked:checkbox:checked").length;

      if(totalChecked == 0){
        var url = http+'/user/delete_user_otorisasi';
      }
      else{
        var url = http+'/user/update_user_otorisasi';
      }

      var data = {
        kd_modul : $("#"+headerId).children("input[name='kd_modul']").val(),
        kd_group : ($("#"+headerId).children("input[name='kd_group']").val() ? $("#"+headerId).children("input[name='kd_group']").val():$("#kd_group").val()),
        c : $("#"+headerId).find("input[name='c']:checkbox:checked").length,
        e : $("#"+headerId).find("input[name='e']:checkbox:checked").length,
        v : $("#"+headerId).find("input[name='v']:checkbox:checked").length,
        p : $("#"+headerId).find("input[name='p']:checkbox:checked").length
      }

      $(".alert-message").fadeIn();

      // console.log(data);

      $.ajax({
        url:url,
        type:"POST",
        dataType: "json",
        data:data,
        success:function(result){

          if (result.status == true) 
          {
           
            $('.success').animate({ top: "0" }, 500);
            $('.success').html(result.message);

            setTimeout(function () {
                hideAllMessages();
                $("#store-btn").removeClass("disabled");
                // $("#store-btn").html(defaultBtn);
            }, 4000);
          }else{

            $('.error').animate({ top: "0" }, 500);
            $('.error').html(result.message);

            setTimeout(function () {
                hideAllMessages();
                $("#store-btn").removeClass("disabled");
                // $("#store-btn").html(defaultBtn);
            }, 4000);
            
          }

        }
      });

    });


  });
</script>