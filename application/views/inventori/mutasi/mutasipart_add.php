<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$defaultPart = '';
$kd_gudang_asal="";
$rakbin_asal="";
$jumlah_asal="";

$kd_gudang_tujuan="";
$kd_dealer_tujuan="";
$no_rangka=""; $tgl_mutasi=date("d/m/Y");
$keterangan="";$no_trans="";$jenis_mutasi="";
if(isset($list)){
  if($list->totaldata>0){
    foreach ($list->message as $key => $value) {
      $defaultDealer = $value->KD_DEALER;
      $defaultPart = $value->PART_NUMBER;
      $kd_gudang_asal = $value->KD_GUDANG_ASAL;
      $kd_gudang_tujuan = $value->KD_GUDANG_TUJUAN;
      $kd_dealer_tujuan = $value->KD_DEALER_TUJUAN;
      $tgl_mutasi = tglFromSql($value->TGL_TRANS);
      $no_trans = $value->NO_TRANS;
      $keterangan = $value->KETERANGAN;
      $jenis_mutasi = $value->JENIS_TRANS;
    }
  }
}
$disabled_action=($no_trans != '')?"disabled-action":"";
$readonly=($no_trans != '')?"readonly":"";
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('part/mutasipart_simpan');?>" method="post">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Mutasi Part</h4>
  </div>
  <div class="modal-body">

    <input id="tipe_mutasi" type="hidden" name="tipe_mutasi" value='PART'>

    <div class="row">
      <!-- dealer -->
      <div class="col-xs-12 col-sm-2 col-md-2">
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control disabled-action" id="kd_dealers" name="kd_dealer">
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

      <div class="col-xs-12 col-sm-4 col-md-4">

        <div class="form-group">
          <label>No. Part</label>
          <select class="form-control" id="part_number" name="part_number" required>
            <option value="">--Pilih Part--</option>
            <?php
              if ($part) {
                if (is_array($part->message)) {
                  foreach ($part->message as $key => $value) {
                    $aktif = ($defaultPart == $value->PART_NUMBER) ? "selected" : "";
                    echo "<option value='" . $value->PART_NUMBER . "' " . $aktif . ">" . $value->PART_NUMBER . "</option>";
                  }
                }
              }
            ?> 
          </select>
        </div>
      </div>


      <!-- deskripsi motor -->
      <div class="col-xs-12 col-md-3 col-sm-3">
        <div class="form-group">
          <label>Keterangan</label>
          <input type="text" name="keterangan" id="keterangan" class="form-control disabled-action" value="<?php echo $keterangan;?>">
        </div>
      </div>

      <!-- tanggal mutasi -->
      <div class="col-xs-12 col-sm-3 col-md-3">
        <div class="form-group">
          <label>Tanggal Mutasi</label>
          <div class="input-group input-append date" id="datepicker">
              <input class="form-control <?php echo $disabled_action;?>" name="tgl_mutasi" id="tgl_mutasi" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>

    </div>
    <div class="row">

      <!-- jenis mutasi -->
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Jenis Mutasi</label>
          <select id="jenis_mutasi" name="jenis_mutasi" class="form-control" required>
            <option value="">--Pilih Jenis Mutasi--</option>
            <option value="Antar Gudang" <?php echo ($jenis_mutasi=='Antar Gudang')?'selected':'';?>>Antar Gudang</option>
            <!-- <option value="Antar Dealer" <?php echo ($jenis_mutasi=='Antar Dealer')?'selected':'';?>>Antar Dealer</option> -->
            <option value="Antar Rakbin" <?php echo ($jenis_mutasi=='Antar Rakbin')?'selected':'';?>>Antar Rakbin</option>
            <!-- <option value="Retur" <?php echo ($jenis_mutasi=='Retur')?'selected':'';?>>Retur</option> -->
            <!-- <option value="Disposal" <?php echo ($jenis_mutasi=='Disposal')?'selected':'';?>>Disposal</option> -->
            <!-- <option value="Return" class="hidden">Return</option> -->
          </select>
        </div>
      </div>
      <!-- nomor transaksi -->
      <div class="col-xs-12 col-md-6 col-sm-6">
        <div class="form-group">
          <label>No. Transaksi</label>
          <input type="text" id="no_trans" name="no_trans" class="form-control" value="<?php echo $no_trans;?>" readonly>
        </div>
      </div>

    </div>
    <div class="row">

      <!-- gudang asal -->
      <div class="col-xs-12 col-md-6 col-sm-6">
        <div class="form-group">
          <label>Lokasi Asal <span class="form_gudang"></span></label>
          <input type="text" id="kd_gudang" name="kd_gudang" class="form-control" value="" required>
          <input type="hidden" id="kd_gudang_asal" name="kd_gudang_asal" class="form-control" value="<?php echo $kd_gudang_asal;?>">
          <input type="hidden" id="kd_rakbin" name="kd_rakbin" class="form-control" value="<?php echo $rakbin_asal;?>">
          <input type="hidden" id="jumlah_asal" name="jumlah_asal" class="form-control" value="<?php echo $jumlah_asal;?>">
          <input type="hidden" id="harga_beli" name="harga_beli" class="form-control" value="">
          <input type="hidden" id="het" name="het" class="form-control" value="">
          <!-- <select id="kd_gudang" name="kd_gudang" class="form-control atg <?php echo $disabled_action;?>">
            <option value="">--Pilih Gudang--</option>
            <?php 
              if(isset($gudang)){
                if($gudang->totaldata>0){
                  foreach ($gudang->message as $key => $value) {
                    $pilih=($kd_gudang_asal==$value->KD_GUDANG)?'selected':'';
                    echo "<option value='".$value->KD_GUDANG."' ".$pilih.">".$value->NAMA_GUDANG." [".$value->KD_GUDANG."] </option>";
                  }
                }
              }
            ?>
          </select> -->

        </div>
      </div>


      <!-- gudang tujuan -->
      <div class="col-xs-12 col-md-6 col-sm-6">
        <div class="form-group">
          <label>Lokasi Tujuan <span class="form_mutasi"></span></label>
          <select id="kd_gudang_tujuan" name="kd_gudang_tujuan" class="form-control atg" readonly required>
            <option value="">--Pilih Gudang--</option>
            <?php 
              if(isset($gudang)){
                if($gudang->totaldata>0){
                  foreach ($gudang->message as $key => $value) {
                    $pilih=($kd_gudang_tujuan==$value->KD_GUDANG)?'selected':'';
                    echo "<option value='".$value->KD_GUDANG."' class='".$value->KD_GUDANG." hidden' ".$pilih.">".$value->NAMA_GUDANG." [".$value->KD_GUDANG."] </option>";
                  }
                }
              }
            ?>
          </select>
          <select id="kd_dealer_tujuan" name="kd_dealer_tujuan" class="form-control atd hidden" readonly required>
            <option value="">--Pilih Dealer--</option>
            <?php
              if ($dealer) {
                if (is_array($dealer->message)) {
                  foreach ($dealer->message as $key => $value) {
                    $aktif = ($kd_dealer_tujuan == $value->KD_DEALER) ? "selected" : "";
                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . " class='".$value->KD_DEALER." hidden'>" . $value->NAMA_DEALER . "</option>";
                  }
                }
              }
            ?> 
          </select>
        </div>
      </div>

    </div>
    <div class="row">

      <!-- rakbin tujuan -->
      <div id="movingrakbin_tujuan" class="col-xs-12 col-md-6 col-sm-6 atd hidden">
        <div class="form-group">
          <label>RAKBIN Tujuan</label>
          <select id="rakbin_tujuan" name="rakbin_tujuan" class="form-control" required>
            <option value="">- Pilih Rakbin -</option>
          </select>
        </div>
      </div>

      <!-- rakbin tujuan -->
      <div id="movingjumlah_tujuan" class="col-xs-12 col-md-5 col-sm-6 atd hidden">
        <div class="form-group">
          <label>Jumlah</label>
          <input type="number" id="jumlah" name="jumlah" class="form-control" value="" min="1" max="" required>
        </div>
      </div>

      <div id="add_part_detail" class="col-xs-12 col-sm-12 col-md-1 atd hidden">

        <div class="form-group">
          <br>
          <button id="addpart-btn" type="submit" class="btn btn-info"><i class="fa fa-plus"></i></button>
        </div>
                      <!-- 
        <label style="color: white">&nbsp;</label>
        <button class="btn btn-info" type="submit"> <i class="fa fa-plus"></i></button> -->
      </div>


    </div>

    <div class="row">
      <div class="table-responsive col-xs-12">
        <table id="part_list" class="table table-hover table-striped">
          <thead>
            <tr>
              <th>No. Trans</th>
              <th>Tanggal</th>
              <th>No. Part</th>
              <th>Keterangan</th>
              <th>Jenis Mutasi</th>
              <th>Lokasi Asal</th>
              <th>Rakbin Asal</th>
              <th>Lokasi Tujuan</th>
              <th>Rakbin Tujuan</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
     <button id="reload-btn" type="submit" class="btn btn-info reload-btn"><i class="fa fa-refresh"></i> Reload</button>
  </div>
</form>
<script type="text/javascript">
  var path = window.location.pathname.split('/');
  var http = window.location.origin + '/' + path[1];

  $(document).ready(function(){

    __gudangAsal();

    $('#part_number').change(function(){
      $('#kd_rakbin').val('');
      $('#jumlah_asal').val('');
      $('#harga_beli').val('');
      $('#het').val('');
      
      $(".form_gudang").html("<i class='fa fa-spinner fa-spin'></i>");
      __gudangAsal();
    })

    $('#jenis_mutasi, #kd_gudang, #part_number').change(function(){
      hideUnhideform();
    })

    $('#kd_gudang_tujuan').change(function(){

      var kd_gudang_tujuan = $(this).val();
      var kd_rakbin = $('#kd_rakbin').val();
      var maxJumlah = $('#jumlah_asal').val();

      $("#jumlah").val('');
      
      $(".form_mutasi").html("<i class='fa fa-spinner fa-spin'></i>");

      if(kd_gudang_tujuan != ''){

        $.getJSON(http+'/part/get_movingrakbin',
        {'kd_gudang':kd_gudang_tujuan}, 
        function(data, status) {
            if (status == 'success') {
              $('#movingrakbin_tujuan').removeClass("hidden");
              $('#movingjumlah_tujuan').removeClass("hidden");
              $('#add_part_detail').removeClass("hidden");

              $("#rakbin_tujuan").html(data);

              $('#rakbin_tujuan option').addClass("hidden");

              $("#rakbin_tujuan option:not(."+kd_rakbin+")").removeClass("hidden");

              $("#jumlah").attr('max', maxJumlah);
            }

          $(".form_mutasi").html("");
        });
      }
      else{
        $('#movingrakbin_tujuan').addClass("hidden");
        $('#movingjumlah_tujuan').addClass("hidden");
        $('#add_part_detail').addClass("hidden");
        
        $(".form_mutasi").html("");
      }

    })

    $('#kd_dealer_tujuan').change(function(){
      var maxJumlah = $('#jumlah_asal').val();
      $("#jumlah").val('');

        if($(this).val() != ''){
          $('#movingjumlah_tujuan').removeClass("hidden");
          $('#add_part_detail').removeClass("hidden");
          $("#jumlah").attr('max', maxJumlah);
        }
        else{
          $('#movingjumlah_tujuan').addClass("hidden");
          $('#add_part_detail').addClass("hidden");

        }
    })

    $("#kd_gudang").change(function(){
      var valueGudang = $(this).val();
      var part_number = $('#part_number').val();
      var gudangSplit = valueGudang.split("_");


      $.getJSON(http+'/part/get_partdetail',
        {'part_number':part_number, 'kd_gudang':gudangSplit[0], 'kd_rakbin':gudangSplit[1]}, 
        function(data, status) {


            if (data.status == true && data.message.length > 0) {
              
              // alert(gudangSplit[0]+' | '+gudangSplit[1]);

              $('#kd_gudang_asal').val(data.message[0].KD_GUDANG);
              $('#kd_rakbin').val(data.message[0].KD_RAKBIN);
              $('#jumlah_asal').val(data.message[0].JUMLAH);
              $('#harga_beli').val(data.message[0].HARGA_BELI);
              $('#het').val(data.message[0].HARGA_JUAL);

            }
        });
    })

    $("#addpart-btn").click(function(){
      var formId = '#' + $(this).closest('form').attr('id');
      var btnId = '#' + this.id;
      $(formId).valid();

      $(formId).validate({
          focusInvalid: false,
          invalidHandler: function(form, validator) {

              if (!validator.numberOfInvalids())
                  return;

              $('html, body').animate({
                  scrollTop: $(validator.errorList[0].element).offset().top
              }, 1000);

          }
      });



      if (jQuery(formId).valid()) {
          // Do something
          event.preventDefault();

          storeData(formId, btnId);

      }
    })

    $(".reload-btn").click(function(){
      location.reload();
    })

  })

  function storeData(formId, btnId) {
      // alert(formId);
      var defaultBtn = $(btnId).html();

      $(btnId).addClass("disabled");
      $(btnId).html("<i class='fa fa-spinner fa-spin'></i>");
      $(".alert-message").fadeIn();
      $('#loadpage').removeClass("hidden");

      $(formId +" select").removeAttr("disabled");
      $(formId +" select").removeClass("disabled-action");
      var formData = $(formId).serialize();
      var act = $(formId).attr('action');

      var formData = {
        tipe_mutasi       : 'PART',
        kd_dealer         : $("#kd_dealers").val(),
        part_number       : $("#part_number").val(),
        keterangan        : $("#keterangan").val(),
        tgl_mutasi        : $("#tgl_mutasi").val(),
        jenis_mutasi      : $("#jenis_mutasi").val(),
        no_trans          : $("#no_trans").val(),
        kd_gudang         : $("#kd_gudang").val(),
        kd_gudang_asal    : $("#kd_gudang_asal").val(),
        kd_rakbin         : $("#kd_rakbin").val(),
        jumlah_asal       : $("#jumlah_asal").val(),
        harga_beli        : $("#harga_beli").val(),
        het               : $("#het").val(),
        kd_gudang_tujuan  : $("#kd_gudang_tujuan").val(),
        kd_dealer_tujuan  : $("#kd_dealer_tujuan").val(),
        rakbin_tujuan     : $("#rakbin_tujuan").val(),
        jumlah            : $("#jumlah").val()
      }


      // alert('test');
      // console.log(formData);

      $.ajax({
          url: act,
          type: 'POST',
          data: formData,
          dataType: "json",
          success: function(result) {

              if (result.status == true) {

                  $('.success').html(result.message);
                  $('.success').animate({ top: "0" }, 500);

                  $("#no_trans").val(result.location);

                  var tabledata = '';
                  tabledata += "<tr>";
                  tabledata += "<td>"+result.location+"</td>";
                  tabledata += "<td>"+formData.tgl_mutasi+"</td>";
                  tabledata += "<td>"+formData.part_number+"</td>";
                  tabledata += "<td>"+formData.keterangan+"</td>";
                  tabledata += "<td>"+formData.jenis_mutasi+"</td>";
                  tabledata += "<td>"+formData.kd_gudang_asal+"</td>";
                  tabledata += "<td>"+formData.kd_rakbin+"</td>";
                  tabledata += "<td>"+formData.kd_gudang_tujuan+"</td>";
                  tabledata += "<td>"+formData.rakbin_tujuan+"</td>";
                  tabledata += "<td>"+formData.jumlah+"</td>";
                  tabledata += "</tr>";

                  $("#part_list tbody").append(tabledata);

                  setTimeout(function() {
                      hideAllMessages();
                      $(btnId).removeClass("disabled");
                      $(btnId).html(defaultBtn);
                      $('#loadpage').addClass("hidden");

                      $('#kd_rakbin').val('');
                      $('#jumlah_asal').val('');
                      $('#harga_beli').val('');
                      $('#het').val('');
                      
                      $(".form_gudang").html("<i class='fa fa-spinner fa-spin'></i>");
                      __gudangAsal();

                  }, 2000);

              } else {

                  $('.error').animate({
                      top: "0"
                  }, 500);
                  $('.error').html(result.message);

                  setTimeout(function() {
                      hideAllMessages();
                      $(btnId).removeClass("disabled");
                      $(btnId).html(defaultBtn);
                      $('#loadpage').addClass("hidden");
                  }, 2000);
              }
          }

      });

      return false;
  }

  function __gudangAsal(){
    var part_number = $('#part_number').val();
    var gudang=[]

    if(part_number != ''){
      $.getJSON(http+'/part/get_partdetail',
      {'part_number':part_number}, 
      function(data, status) {

        if (data.status == true && data.message.length > 0) {

            $('#keterangan').val(data.message[0].PART_DESKRIPSI);

            $('#kd_gudang').val('');
            $('#kd_gudang').removeAttr('readonly');

            $.each( data.message, function( key, value ) {
                gudang.push({
                  'Lokasi Asal' : value.NAMA_GUDANG+' ['+value.KD_GUDANG+']',
                  'Rakbin Asal'  : value.KD_RAKBIN,
                  'Jumlah'  : value.JUMLAH,
                  'value' : value.KD_GUDANG+'_'+value.KD_RAKBIN
                })
            });

        }
        $('#kd_gudang').inputpicker({
          data : gudang,
          fields :['Lokasi Asal', 'Rakbin Asal','Jumlah'],
          fieldText : 'Lokasi Asal',
          fieldValue : 'value',
          filterOpen : true,
          headShow:true,
        })
        
        hideUnhideform()
        $(".form_gudang").html("");
      });
    }
  }

  function hideUnhideform()
  {
    var jm =$('#jenis_mutasi').val()
    var valueGudang = $('#kd_gudang').val();

    var gudangSplit = valueGudang.split("_");

    var kd_gudang = gudangSplit[0];

    $(".form_mutasi").html("<i class='fa fa-spinner fa-spin'></i>");

    if(jm != '' && valueGudang != ''){

      $('#kd_gudang_tujuan').removeAttr('readonly');
      $('#kd_dealer_tujuan').removeAttr('readonly');


      if(jm=='Antar Dealer'){
        $('#movingrakbin_tujuan').addClass("hidden");
        $('#movingjumlah_tujuan').addClass("hidden");
        $('#add_part_detail').addClass("hidden");
        $('#kd_dealer_tujuan option').addClass('hidden')
        $('#kd_gudang_tujuan').addClass("hidden").val('').select();
        $('#kd_gudang_tujuan option').addClass("hidden");
        $('#kd_dealer_tujuan').removeClass("hidden");
        $("#kd_dealer_tujuan option:not(."+$("kd_dealer").val()+")").removeClass('hidden')
      }
      else if(jm=='Antar Gudang'){
        // alert(kd_gudang);

        $('#movingrakbin_tujuan').addClass("hidden");
        $('#movingjumlah_tujuan').addClass("hidden");
        $('#add_part_detail').addClass("hidden");
        $('#kd_gudang_tujuan option').addClass("hidden");
        $('#kd_gudang_tujuan').removeClass("hidden").val('').select();
        $("#kd_gudang_tujuan option:not(."+kd_gudang+")").removeClass("hidden");
        $('#kd_dealer_tujuan').addClass("hidden");
        $('#kd_dealer_tujuan option').addClass('hidden')
      }
      else if(jm=='Antar Rakbin'){
        // alert(kd_gudang);

        $('#movingrakbin_tujuan').addClass("hidden");
        $('#movingjumlah_tujuan').addClass("hidden");
        $('#add_part_detail').addClass("hidden");
        $('#kd_gudang_tujuan option').addClass("hidden");
        $('#kd_gudang_tujuan').removeClass("hidden").val('').select();
        $("#kd_gudang_tujuan option[value="+kd_gudang+"]").removeClass("hidden");
        $('#kd_dealer_tujuan').addClass("hidden");
        $('#kd_dealer_tujuan option').addClass('hidden')
      }
      
    }
    else{

      $('#kd_gudang_tujuan').attr('readonly', 'readonly');
      $('#kd_dealer_tujuan').attr('readonly', 'readonly');
    }

    setTimeout(function () {
      $(".form_mutasi").html("");
    }, 500);
  }
</script>