<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = $list->message[0]->KD_DEALER;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">    <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Range Nomor Pajak</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_rangenopajak/'. $list->message[0]->ID); ?>">
      <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
      
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
        <label>Range 1</label>
        <input type="text" id="range1" name="range1" class="form-control" value="<?php echo $list->message[0]->RANGE1;?>">
    </div>

    <div class="form-group">
        <label>Range 2</label>
        <input type="text" id="range2" name="range2" class="form-control" value="<?php echo $list->message[0]->RANGE2;?>">
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="row_status" class="form-control">
          <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> </option>
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

</form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('.qurency').mask('000.000.000.000.000', {reverse: true});

        $('#range1').mask('000-00.00000000', {'translation': {
          0: {pattern: /[0-9]/}
        }})

        $('#range2').mask('000-00.00000000', {'translation': {
          0: {pattern: /[0-9]/}
        }})

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
                        $('#loadpage').addClass("hidden");
                    }, 2000);


                }
            }

        });

        return false;

    }

</script>