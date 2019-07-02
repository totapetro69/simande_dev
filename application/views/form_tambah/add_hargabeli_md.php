<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('part/simpan_hargabeli_md'); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Nama Unit Baru</h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Nama Unit</label>
            <select name="nama_unit" class="form-control">
                <option value="" >- Pilih Part -</option>
                <?php if ($part && (is_array($part->message) || is_object($part->message))): foreach ($part->message as $key => $value) : ?>
                        <option value="<?php echo $value->PART_NUMBER; ?>"><?php echo $value->PART_NUMBER; ?> - <?php echo $value->PART_DESKRIPSI; ?></option>
                    <?php endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga Beli</label>
            <input type="text" name="harga_beli" id="harga_beli" class="form-control qurency" placeholder="Masukkan Harga Beli" required>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Keterangan" ></textarea>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('.qurency').mask('000.000.000.000.000', {reverse: true});


        $("#submit-btn").on('click', function (event) {
            var formId = '#' + $(this).closest('form').attr('id');
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");

            $('.qurency').unmask();

            $(formId).validate({
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
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

            } else {
                $('#loadpage').addClass("hidden");
                $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
            }
        });
    })



</script>