<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer =($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit  Master Bank</h4>
</div>
<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('company/update_bank/' . $list->message[0]->KD_BANK); ?>">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control" id="kd_dealer" name="kd_dealer" required="true" disabled="disabled" readonly>
                            <option value="0">--Pilih Dealer--</option>
                            <?php
                            if (isset($dealer)) {
                                if (($dealer->totaldata)) {
                                    foreach ($dealer->message as $key => $value) {
                                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                        echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                    }
                                }
                            }
                            ?> 
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kode Bank</label>
                        <input type="text" name="kd_bank" id="kd_bank" class="form-control" value="<?php echo  $list->message[0]->KD_BANK; ?>" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" name="nama_bank" id="nama_bank" class="form-control" value="<?php echo  $list->message[0]->NAMA_BANK; ?>">
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea type="text" rows="4"  name="alamat_bank" id="alamat_bank" class="form-control" autocomplete="off"><?php echo htmlspecialchars($list->message[0]->ALAMAT_BANK); ?></textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- propinsi -->
                    <div class="form-group">
                        <label>Propinsi</label>
                        <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                            <option value="0">--Pilih Propinsi--</option>
                            <?php
                            if (isset($propinsi)) {
                                if (($propinsi->totaldata)) {
                                    foreach ($propinsi->message as $key => $value) {
                                        $select=($list->message[0]->KD_PROPINSI == $value->KD_PROPINSI)?"selected":"";
                                        echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">" . $value->NAMA_PROPINSI . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- kabupaten -->
                    <div class="form-group">
                        <label>Kabupaten <span id="l_kabupaten"></span></label>
                        <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                            <option value="0">--Pilih Kabupaten--</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>No Rekening</label>
                        <input type="text" name="no_rekening" id="no_rekening" class="form-control" value="<?php echo  $list->message[0]->NO_REKENING; ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kode Perkiraan <span id="fd"></span></label>
                        <input id="kd_akun" type="text" name="kd_akun" class="form-control" value="<?php echo  $list->message[0]->KD_AKUN; ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-sm-6">
                    <div class="form-group">
                       <label>Status</label>
                       <select name="row_status" class="form-control">
                         <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
                         <?php
                         if($list->message[0]->ROW_STATUS == -1){
                             ?>
                             <option value="0">Aktif</option>
                             <?php
                         }else{
                             ?>
                             <option value="-1">Tidak Aktif</option>
                             <?php
                         }
                         ?>
                     </select>
                 </div>
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
    $(document).ready(function(){
        $("#kd_akun").typeahead({
         source:function(query,process){
          $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get('<?php echo base_url("company/akun_typeahead");?>',{keyword:query},function(data){
            console.log(data);
            data=$.parseJSON(data);
            $('#fd').html('');
            return process(data.keyword);
        })
      },
      minLength:2,
      limit:100
  });

        loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KOTA;?>");
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KOTA;?>")
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
    })

    function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>master_service/" + param;
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
                $('#' + id + '').removeAttr('disabled');
            }
        });
    }
</script>