<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Pajak Perusahaan</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/add_pajakperusahaan_simpan'); ?>">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" >
        </div>
        <div class="form-group">
            <label>Nomor NPWP</label>
            <input type="text" name="no_npwp" id="no_npwp" class="form-control" placeholder="Masukkan nnomor NPWP" >
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat" >
        </div>
        <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="text" name="no_telpperusahaan" id="no_telpperusahaan" class="form-control" placeholder="Masukkan nomor telepon" >
        </div>
        <!-- propinsi -->
        <div class="form-group">
            <label>Propinsi</label>
            <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                <option value="0">--Pilih Propinsi--</option>
                <?php
                if ($propinsi) {
                    if (is_array($propinsi->message)) {
                        foreach ($propinsi->message as $key => $value) {
                            echo "<option value='" . $value->KD_PROPINSI . "'>" . $value->NAMA_PROPINSI . "</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>
        <!-- kabupaten -->
        <div class="form-group">
            <label>Kabupaten <span id="l_kabupaten"></span></label>
            <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                <option value="0">--Pilih Kabupaten--</option>
            </select>
        </div>

    </form>
    <?php echo loading_proses();?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(), '0')
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

