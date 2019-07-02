<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('master_service/rangenopajak_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Range Nomor Pajak</h4>
</div>

<div class="modal-body">

  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="form-group">
        <label>Dealer</label>
        <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
          <option value="">- Pilih Dealer -</option>
          <?php 
            if(isset($dealer)){
              if($dealer->totaldata > 0){
                foreach ($dealer->message as $key => $group) {
                  $pilih=($defaultDealer==$group->KD_DEALER)?"selected":"";
                  ?>
                  <option value="<?php echo $group->KD_DEALER;?>" <?php echo $pilih;?> ><?php echo $group->NAMA_DEALER;?></option>
                  <?php
                }
              }
            }
            
          ?>
        </select>
      </div>
    </div>
    <div class="col-xs-12 col-md-6 col-md-6">
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-6 col-md-6">
      <div class="form-group">
        <label>Range 1</label>
        <input id="range1" name="range1" type="text" class="form-control" placeholder="123-12.12345678" required>
      </div>
    </div>
    <div class="col-xs-12 col-md-6 col-md-6">
      <div class="form-group">
        <label>Range 2</label>
        <input type="text" id="range2" name="range2" class="form-control" placeholder="123-12.12345678" required>
      </div>
    </div>
  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close'></i> Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger submit-btn"><i class='fa fa-save'></i> Simpan</button>
  <!-- <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button> -->
</div>

</form>

<script type="text/javascript">
    $(document).ready(function () {

        $('.qurency').mask('000.000.000.000.000', {reverse: true});

        $('#range1').mask('000-00.00000000', {'translation': {
          0: {pattern: /[0-9]/}
        }})

        $('#range2').mask('000-00.00000000', {'translation': {
          0: {pattern: /[0-9]/}
        }});

        $("#submit-btn").on('click', function (event) {
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
        });
        $('#range1').on('focusout',function(){
          if($(this).val().length > 0){
            __check_no_seri("1");
            return;
          }
        })
        /* $('#range2').on('focusout',function(){
          if($(this).val().length > 0){
            __check_no_seri("2");
            return;
          }
        })*/
    })

    function storeData(formId, btnId)
    {
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
                       return false;
                    }, 1000);


                }
            }

        });

        return false;

    }
    function __check_no_seri(r){
      var r1=$('#range'+r).val();
          r1 = r1.split(".");

      $.getJSON("<?php echo base_url("master_service/get_serial_nopajak");?>",{'prefix':r1[0]+"."},function(result){
        if(result.length > 2){
          $.each(result,function(e,d){
            if(d.NOMOR==$('#range'+r).val()){
              alert("Nomor Seri Sudah Ada dalam database");
              $('#range'+r).val('');
              return false;
            }
          })
        }
      })
    }
</script>
