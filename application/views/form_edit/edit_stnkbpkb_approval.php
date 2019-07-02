<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Master STNK BPKB</h4>
</div>

<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_stnk_bpkb_approval/' . $list->message[0]->ID); ?>">
        <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <input type="hidden" name="id_stnkbpkb" id="id_stnkbpkb" class="form-control" value="<?php echo  $list->message[0]->ID_STNKBPKB; ?>" >
        <input type="hidden" name="created_by" id="created_by" class="form-control" value="<?php echo  $list->message[0]->CREATED_BY; ?>" >
        <div class="form-group">
            <label>Dealer</label>
            <input id="kd_dealer" type="text" name="kd_dealer" class="form-control" value="<?php echo  $list->message[0]->KD_DEALER; ?>" disabled="disabled">
      </div>
      <!-- propinsi -->
      <div class="form-group">
        <label>Propinsi</label>
        <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" disabled="disabled">
            <option value="0">--Pilih Propinsi--</option>
            <?php
            if ($propinsi) {
                if (is_array($propinsi->message)) {
                    foreach ($propinsi->message as $key => $value) {
                        $select=($list->message[0]->KD_PROPINSI == $value->KD_PROPINSI)?"selected":"";
                        echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">" . $value->NAMA_PROPINSI . "</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <!-- kabupaten -->
    <div class="form-group">
        <label>Kabupaten <span id="l_kabupaten"></span></label>
        <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" disabled="disabled">
            <option value="0">--Pilih Kabupaten--</option>
        </select>
    </div>
    <div class="form-group">
        <label>Kode Item</label>
        <select name="kd_motor" class="form-control" disabled="disabled">
          <option value="">- Pilih Item -</option>
          <?php 
          if($typemotors && (is_array($typemotors->message) || is_object($typemotors->message))): 
            foreach ($typemotors->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_TYPEMOTOR;?>" <?php echo ($value->KD_TYPEMOTOR == $list->message[0]->KD_TIPEMOTOR ? "selected" : "");?>><?php echo $value->KD_TYPEMOTOR;?> - <?php echo $value->NAMA_TYPEMOTOR;?> - <?php echo $value->NAMA_PASAR;?> - <?php echo $value->KET_WARNA;?></option>
            <?php 
        endforeach; 
        endif;?>
    </select>
</div>

<div class="form-group">
 <label>Tahun</label>
 <div class="input-group input-append date" id="datepicker">
   <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->TAHUN!='')?$list->message[0]->TAHUN: date('Y');?>" placeholder="yyyy" />
   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
</div>
</div>

<div class="form-group">
    <label>BBNKB</label>
    <input id="bbnkb" type="text" name="bbnkb" class="form-control" value="<?php echo  $list->message[0]->BBNKB; ?>">
</div>
<div class="form-group">
    <label>PKB</label>
    <input id="pkb" type="text" name="pkb" class="form-control" value="<?php echo  $list->message[0]->PKB; ?>">
</div>
<div class="form-group">
    <label>SWDKLLJ</label>
    <input id="swdkllj" type="text" name="swdkllj" class="form-control" value="<?php echo  $list->message[0]->SWDKLLJ; ?>">
</div>
<div class="form-group">
    <label>STCK</label>
    <input id="stck" type="text" name="stck" class="form-control" value="<?php echo  $list->message[0]->STCK; ?>">
</div>
<div class="form-group">
    <label>PLAT ASLI</label>
    <input id="plat_asli" type="text" name="plat_asli" class="form-control" value="<?php echo  $list->message[0]->PLAT_ASLI; ?>">
</div>
<div class="form-group">
    <label>ADMIN SAMSAT</label>
    <input id="admin_samsat" type="text" name="admin_samsat" class="form-control" value="<?php echo  $list->message[0]->ADMIN_SAMSAT; ?>">
</div>
<div class="form-group">
    <label>BPKB</label>
    <input id="bpkb" type="text" name="bpkb" class="form-control" value="<?php echo  $list->message[0]->BPKB; ?>">
</div>
<div class="form-group">
    <label>PENGURUSAN TAMBAHAN</label>
    <input id="pengurusan_tambahan" type="text" name="pengurusan_tambahan" class="form-control" value="<?php echo  $list->message[0]->PENGURUSAN_TAMBAHAN; ?>">
</div>
<div class="form-group">
    <label>SS</label>
    <input id="ss" type="text" name="ss" class="form-control" value="<?php echo  $list->message[0]->SS; ?>">
</div>
<div class="form-group">
    <label>BANPEN</label>
    <input id="banpen" type="text" name="banpen" class="form-control" value="<?php echo  $list->message[0]->BANPEN; ?>">
</div>
<div class="form-group">
      <label>Approve By</label>
      <select name="nik" class="form-control" disabled="disabled">
        <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
          <option value="<?php echo $value->NIK;?>"><?php echo $value->NIK;?> - <?php echo $value->NAMA;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>
    
    <div class="form-group">
      <label>Approval</label>
      <select name="status_approval" id="status_approval" class="form-control">
       <option value="0">- Approve/ Reject -</option>
       <option value="1">Approved</option>
       <option value="2">Rejected</option>
     </select>
   </div>
</form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#datepicker").datepicker( {
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });

        loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KABUPATEN;?>");
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KABUPATEN;?>")
        })

        $("#submit-btn").on('click',function(event){
            var formId = '#'+$(this).closest('form').attr('id');
            var btnId = '#'+this.id;
            $('#loadpage').removeClass("hidden");

            $(formId).validate({
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function(error, element) {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();

                addValid(formId, btnId);

            }else{
                $('#loadpage').addClass("hidden");
                $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
            }
        });

        $('#bbnkb')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#pkb')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#swdkllj')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#stck')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#plat_asli')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#bpkb')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#pengurusan_tambahan')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#admin_samsat')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#ss')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $('#banpen')
        .focusout(function(){

        })
        .ForceNumericOnly()
    })

    function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>dealer/" + param;
        var datax = {"kd": value};
        $('#' + id + '').attr('disabled','disabled');
        $.ajax({
            type: 'POST',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function (result) {
                $('#' + id + '').html('');
                $('#' + id + '').html(result);
                $('#' + id + '').val(select).select();
                $('#l_' + param + '').html('');
                //$('#' + id + '').removeAttr('disabled');
            }
        });
    }
</script>


