<?php

if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
//$tgl_awal=date("d/m/Y");$tgl_akhir=date('d/m/Y',strtotime('Last day of next month'));
?>



  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Kepala Sales</h4>
  </div>

  <div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('company/update_ks_bawahan/'. $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <input type="hidden" name="ks_id" id="ks_id" class="form-control" value="<?php echo  $list->message[0]->KS_ID; ?>" >
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required>
            <option value="0">--Pilih Dealer--</option>
            <?php
            if ($dealer) {
              if (is_array($dealer->message)) {
                foreach ($dealer->message as $key => $value) {
                  $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                  $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                  echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                }
              }
            }
            ?> 
          </select>
        </div>

        <div class="form-group">
          <label>NIK</label>
          <input id="nik" name="nik" type="text" class="form-control disabled-action" value="<?php echo $list->message[0]->NIK;?>">
        </div>

        <div class="form-group">
          <label>Nama Karyawan</label>
          <input type="text" id="nama_karyawan" value="<?php echo $list->message[0]->NAMA;?>"  name="nama_karyawan" class="form-control disabled-action" placeholder="Masukkan nama karyawan">
        </div>

        <div class="form-group">
          <label>Tanggal Pengangkatan</label>
          <div class="input-group input-append date" id="date">
            <input type="text" class="form-control" id="tgl_awal" name="tgl_awal" value="<?php echo ($list->message[0]->TGL_AWAL!='')?tglfromSql($list->message[0]->TGL_AWAL): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
        <div class="form-group">
          <label>Tanggal Berhenti</label>
          <div class="input-group input-append date" id="date">
            <input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?php echo ($list->message[0]->TGL_AKHIR!='')?tglfromSql($list->message[0]->TGL_AKHIR): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
        <div class="form-group">
          <label>Status Jabatan</label>
          <select name="status_aktif" class="form-control">
            <option value="<?php echo $list->message[0]->STATUS;?>"> 
              <?php if($list->message[0]->STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> 
            </option>
            <?php
            if($list->message[0]->STATUS== 0){
              ?>
              <option value="1">Tidak Aktif</option>
              <?php
            }else{
              ?>
              <option value="0">Aktif</option>
              <?php
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="row_status" class="form-control">
            <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> 
              <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> 
            </option>
            <?php
            if($list->message[0]->ROW_STATUS == 0){
              ?>
              <option value="-1">Tidak Aktif</option>
              <?php
            }else{
              ?>
              <option value="0">Aktif</option>
              <?php
            }
            ?>
          </select>
        </div>
      </div>
    </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>



<script type="text/javascript">
  var path = window.location.pathname.split('/');
  var http = window.location.origin + '/' + path[1];
  $(document).ready(function () {

    $('.selectpicker').selectpicker();

    var date = new Date();
    date.setDate(date.getDate());

    $('#date,#datex').datepicker({
      format: 'dd/mm/yyyy',
      daysOfWeekHighlighted: "0",
      autoclose: true,
      todayHighlight: true
    });


/*    $("#submit-btn").on('click', function (event) {
      var formId = '#' + $(this).closest('form').attr('id');
      var btnId = '#' + this.id;
      $('#loadpage').removeClass("hidden");
      $('.qurency').unmask();
 
      $(formId).valid();
 
      if (jQuery(formId).valid()) {
        // Do something
        event.preventDefault();
 
        storeData(formId, btnId);
 
      } else {
 
        $('#loadpage').addClass("hidden");
 
            }
          });*/
          __namakaryawan();
        })

/*  function storeData(formId, btnId){
    // alert(formId);
    var defaultBtn = $(btnId).html();
 
    $(btnId).addClass("disabled");
    $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
 
    $(formId + " select").removeAttr("disabled");
    $(formId + " select").removeClass("disabled-action");
    var formData = $(formId).serialize();
    var act = $(formId).attr('action');
    $.ajax({
        url: act,
        type: 'POST',
        data: formData,
        dataType: "json",
        success: function (result) {
 
        if (result.status == true) {
 
          $('.success').animate({
            top: "0"
          }, 500);
          $('.success').html(result.message);
          
            if (result.location != null) {
              setTimeout(function () {
                location.replace(result.location)
              }, 1000);
            } else {
              setTimeout(function () {
              location.reload();
              }, 1000);
                    }
        } else {
 
                $('.error').animate({
                  top: "0"
                }, 500);
                $('.error').html(result.message);
 
                setTimeout(function () {
                  hideAllMessages();
                    $(btnId).removeClass("disabled");
                    $(btnId).html(defaultBtn);
                    $('#loadpage').addClass("hidden");
                }, 2000);
 
                }
            }
    });
 
      return false;
    }*/
    function __namakaryawan(){
      var dealer="<?php echo $defaultDealer;?>";
      var datax=[];
      $.getJSON(http+"/company/karyawan/true",{'kd_dealer':dealer},function(result){
        if(result){
          $.each(result,function(e,d){
            datax.push({
              'NIK' : d.NIK,
              'NAMA': d.NAMA,
              'JABATAN':d.PERSONAL_JABATAN
            })
          })
          $('#nik').inputpicker({
            data:datax,
            fields:['NIK','NAMA','JABATAN'],
            headShow:true,
            fieldText:'NIK',
            fieldValue:'NIK',
            filterOpen:true

          });
        }
      })
    }

  /*function __namabawahan(nik){
    var dealer="<?php echo $defaultDealer;?>";
    var datax=[];
    $.getJSON(http+"/company/karyawan/true",{'atasan_langsung':nik},function(result){
      if(result){
          $.each(result,function(e,d){
            datax.push({
              'NIK' : d.NIK,
              'NAMA': d.NAMA,
              'JABATAN':d.PERSONAL_JABATAN
            })
          })
          $('#nik_bawahan').inputpicker({
            data:datax,
            fields:['NIK','NAMA','JABATAN'],
            headShow:true,
            fieldText:'NIK',
            fieldValue:'NIK',
            filterOpen:true

          }).change(function(e){
            e.preventDefault();
            var nik = $(this).val();
            // alert(nik);
            $("#kd_lokasi").removeAttr('disabled');
            var dx=datax.findIndex(obj => obj['NIK'] === $(this).val());
            if(dx>-1){
              $('#nama_karyawan').val(datax[dx]['NAMA']);
              $('#nama_karyawan').addClass('disabled-action');
            }
          });
      }
    })
  }*/
</script>