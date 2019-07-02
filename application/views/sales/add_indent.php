<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$defaultDealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$KETERANGAN = '';
$ETA_INDENT = date('d/m/Y');
$NO_TRANS = '';
$NO_SPK = '';
$KD_ITEM = '';
$NAMA_ITEM = '';
$NAMA_CUSTOMER = '';
$NO_KTP = '';
$NO_HP = '';
$TYPE_PENJUALAN = '';
$NAMA_SALES = '';

if(isset($list)){
  if($list->totaldata>0){
    foreach ($list->message as $key => $value) {
      $KETERANGAN = $value->KETERANGAN;
      $ETA_INDENT = tglfromSql($value->ETA_INDENT);
      $NO_TRANS = $value->NO_TRANS;
      $NO_SPK = $value->NO_SPK;
      $KD_ITEM = $value->KD_ITEM;
      $NAMA_ITEM = $value->NAMA_ITEM;
      $NAMA_CUSTOMER = $value->NAMA_CUSTOMER;
      $NO_KTP = $value->NO_KTP;
      $NO_HP = $value->NO_HP;
      $TYPE_PENJUALAN = $value->TYPE_PENJUALAN;
      $NAMA_SALES = $value->NAMA_SALES;
    }
  }
}

?>


<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tipe Unit <?php echo $NAMA_ITEM;?> Indent</h4>
</div>

<div class="modal-body">


  <!-- <?php var_dump($this->session->userdata());?> -->

  <form id="addForm" class="bucket-form" action="<?php echo base_url('user/store_user');?>" method="post">

      <div class="row">
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>Customer</label>
              <input type="text" name="" class="form-control disabled-action" placeholder="" value="<?php echo $NAMA_CUSTOMER;?>">
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>No KTP</label>
              <input type="text" name="" class="form-control disabled-action" placeholder="" value="<?php echo $NO_KTP;?>">
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>No HP</label>
              <input type="text" name="" class="form-control disabled-action" placeholder="" value="<?php echo $NO_HP;?>">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>No SPK</label>
              <input type="text" name="" class="form-control disabled-action" placeholder="" value="<?php echo $NO_SPK;?>">
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>Tipe Penjualan</label>
              <input type="text" name="" class="form-control disabled-action" placeholder="" value="<?php echo $TYPE_PENJUALAN;?>">
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>Sales</label>
              <input type="text" name="" class="form-control disabled-action" placeholder="" value="<?php echo $NAMA_SALES;?>">
          </div>
        </div>
      </div>
     
      <div class="row">
        <div class="col-xs-12 col-md-8 col-sm-8">
          <div class="form-group">
              <label>Keterangan <span class="loading-nik"></span></label>
              <input id="keterangan" type="text" name="keterangan" class="form-control" placeholder="Keterangan" value="<?php echo $KETERANGAN;?>">
          </div>
        </div>


        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="form-group">
              <label>Waktu perkiraan Tiba <span class="loading-nik"></span></label>
              <div class="input-group input-append date">
                  <input type="text" id="eta_indent" name="eta_indent" class="form-control" value="<?php echo $ETA_INDENT;?>">
                   <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
               </div>
          </div>
        </div>
      </div>
  </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <!-- <button id="store-btn" class="btn btn-danger">Simpan</button> -->
  <button id="indent-btn" class="btn btn-danger">indent</button>
</div>

<script type="text/javascript">
  
var date = new Date();
date.setDate(date.getDate());

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];


$(document).ready(function(){

  $('#indent-btn').click(function(){

    var defaultBtn = $("#indent-btn").html();
    var total_data = $("#list_data >tbody > tr.input-so").length;
    var listData   = total_data - 1;

    $("#indent-btn").addClass("disabled");
    $("#indent-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");


    if(total_data > 0){

      for(i = 0; i < total_data; i++)
      {
        var url = '<?php echo base_url("sales_order/create_indentlist");?>';

        var data = {
          unset             : (listData == i? true:false),

          kd_dealer         : $("#kd_dealer").val(),
          no_trans          : $("#no_indent").val(),
          no_spk            : $("#no_spk").val(),
          tgl_trans         : $("#tgl_indent").val(),
          kd_item           : $("#kd_item_"+i).val(),
          jumlah_unit       : 1,
          kd_customer       : $("#kd_customer").val(),
          keterangan        : $("#keterangan").val(),
          eta_indent        : $("#eta_indent").val()
        } 

        $.ajax({
          url:url,
          type:"POST",
          dataType: "json",
          data:data,
          success:function(result){
            if(result.status == true){
              $('.success').animate({ top: "0" }, 500).fadeIn();
              $('.success').html(result.message);
              $("#no_indent").val(result.location);
              refreshData();
              getSPk();

              setTimeout(function () {
                  hideAllMessages();
                  $("#indent-btn").removeClass("disabled");
                  $("#indent-btn").html(defaultBtn);
                  $(".close").click();
              }, 4000);
            }
            else{
              $('.error').animate({ top: "0" }, 500).fadeIn();
              $('.error').html(result.message);
              
              setTimeout(function () {
                  hideAllMessages();
                  $("#indent-btn").removeClass("disabled");
                  $("#indent-btn").html(defaultBtn);
              }, 4000);
            }
          }

        });
      }      
    }
    else{
      $(".alert-message").fadeIn();

      $('.error').animate({ top: "0" }, 500);
      $('.error').html("Maaf, unit tidak ada");
      
      setTimeout(function () {
          hideAllMessages();
          $("#indent-btn").removeClass("disabled");
          $("#indent-btn").html(defaultBtn);
      }, 4000);
    }


    // var formData = $('#soForm').serialize();

    // console.log(data);

  })
  
});

</script>

