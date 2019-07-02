<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$nik="";$nama_karyawan="";$tgl_awal=date('d/m/Y');$tgl_akhir=null;$status=""; $id=""; $nik_bawahan=""; $kd_lokasi=""; $bawahan=array();
$tgl_akhir=date('d/m/Y',strtotime('Last day of next year'));
$tampil="hidden";
if(isset($list)){
  if($list->totaldata>0){
    foreach ($list->message as $key => $value) {
        $defaultDealer = $value->KD_DEALER;
        $nik = $value->NIK;
        $nama_karyawan = $value->NAMA_KARYAWAN;
        $tgl_awal = TglFromSql($value->TGL_AWAL);
        $status = $value->STATUS_AKTIF;
        $nik_bawahan = $value->NIK_BAWAHAN;
        $kd_lokasi = $value->KD_LOKASI;
        $id=$value->ID;
    }
  }

  $bawahan = explode(", ",$nik_bawahan);
}
$tampil =($id)?"":"hidden";
//var_dump($bawahan) ;
?>
 
<form id="addForm" class="bucket-form" action="<?php echo base_url('company/ks_simpan');?>" method="post">
 
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Data Kepala Sales</h4>
    <input type="hidden" name="id" value="<?php echo $id;?>">
  </div>
 
  <div class="modal-body">
    <div class="row">

      <div class="col-xs-12 col-md-6">   
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control" id="kd_dealer" name="kd_dealer">
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
      </div>

      <div class="col-xs-12 col-md-6">
        <div class="form-group">
          <label>NIK</label>
          <input id="nik" name="nik" type="text" value="<?php echo $nik;?>" class="form-control" placeholder="Masukkan nik karyawan"  required>
        </div>
      </div>

      <div class="col-xs-12 col-md-6">
        <div class="form-group">
          <label>Nama Karyawan</label>
          <input type="text" id="nama_karyawan" value="<?php echo $nama_karyawan;?>"  name="nama_karyawan" class="form-control disabled-action" placeholder="Masukkan nama karyawan" required >
        </div>
      </div>
      
      <!-- <div class="col-xs-12 col-md-6">
        <div class="form-group">
          <label>Jabatan</label>
          <input type="text" id="kd_jabatan" name="kd_jabatan" class="form-control disabled-action" value="Kepala Sales" />
        </div>
      </div> -->

      <div class="col-xs-12 col-md-6">
        <div class="form-group">
        	<label>Tanggal Pengangkatan</label>
        	<div class="input-group input-append date" id="date">
            <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $tgl_awal; ?>" type="text"/>
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
          <label>Tanggal Berakhir</label>
          <div class="input-group input-append date" id="datex">
            <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="dd/mm/yyyy" value="<?php echo $tgl_akhir; ?>" type="text"/>
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>

      <!-- <div class="col-xs-12 col-md-3 <?php echo $tampil;?>">
        <div class="form-group">
      		<label>Tanggal Berakhir</label>
      		<div class="input-group input-append date" id="datex">
            <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="dd/mm/yyyy" value="<?php echo $tgl_akhir; ?>" type="text"/>
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
    	</div> -->

      <div class="col-xs-12 col-md-3"> 
      <div class="form-group">
        <label>Status Jabatan</label>
        <select name="status_aktif" id="status_aktif" class="form-control">
            <option value="0" <?php echo ($status=="0")?"selected":"";?>>Aktif</option>
            <option value="1" <?php echo ($status=="1")?"selected":"";?>>Non Aktif</option>
        </select>
      </div>
    </div>

      <!-- <div class="col-xs-12 col-md-6">
        <div class="form-group">
          <label>Bawahan</label>
          <input id="nik_bawahan" name="nik_bawahan" type="text" value="<?php echo $nik_bawahan;?>" class="form-control" placeholder="Masukkan nik Bawahan"  required disabled>
        </div>
      </div> -->

      <div class="col-xs-12 col-md-6">
        <div class="form-group">
          <label>Lokasi</label>
          <select class="form-control" id="kd_lokasi" name="kd_lokasi" required="true" >
            <option value="0">--Pilih Lokasi Dealer--</option>
             <?php
                if ($lokasidealer) {
                  if (is_array($lokasidealer->message)) {
                    foreach ($lokasidealer->message as $key => $value) {
                      $aktif = ($kd_lokasi == $value->KD_LOKASI) ? "selected" : '';
                      echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                    }
                  }
                }
            ?>  
        </select>
        </div>
      </div>

      <!-- <div class="col-xs-12 col-md-12">
        <div class="form-group">
          <label>Bawahan</label>
          <select  name="nik_bawahan[]" id="nik_bawahan"  class="selectpicker form-control edit" multiple value="" data-live-search="true" >
            <?php
            //$cek = array();
           
            if ($karyawan) {
              if (is_array($karyawan->message)) {
                foreach ($karyawan->message as $key => $value) {
                $aktif = '';
                  foreach($bawahan as $bawahan_val){
                    if($value->NIK == $bawahan_val){
                      $aktif = "selected";
                      // array_push($cek, $bawahan_val);
                    }
                  }
                  
                  echo "<option value='" . $value->NIK . "' " . $aktif . ">" . $value->NAMA . "</option>";
                }
              }
            }

            ?>
          </select>
        </div>
      </div> -->

      <?php 


            //print_r();
            ?>

      
    </div> 
  </div>
 
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>
 
</form>
 
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

          }).change(function(e){
            e.preventDefault();
            var nik = $(this).val();
            // alert(nik);
            $("#nik_bawahan").removeAttr('disabled');
            // __namabawahan(nik);
            var dx=datax.findIndex(obj => obj['NIK'] === $(this).val());
            if(dx>-1){
              $('#nama_karyawan').val(datax[dx]['NAMA']);
              $('#nama_karyawan').addClass('disabled-action');
            }
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