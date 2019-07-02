<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('part/update_hargabeli_md/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Harga Beli Ke MD : <?php echo $list->message[0]->NAMA_UNIT; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group hidden">
            <label></label>
            <input type="text" name="id" id="id" class="form-control" value="<?php echo $list->message[0]->ID; ?>" >
        </div>
        
        <div class="form-group">
            <label>Nama Unit</label>
            <input type="text" name="nama_unit" id="nama_unit" class="form-control" value="<?php echo $list->message[0]->NAMA_UNIT; ?>"  readonly>
        </div>

        <div class="form-group">
            <label>Harga Beli</label>
            <input type="text" name="harga_beli" id="harga_beli" class="form-control qurency" value="<?php echo number_format($list->message[0]->HARGA_BELI); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Keterangan</label>
            <textarea type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo $list->message[0]->KETERANGAN; ?>" ><?php echo $list->message[0]->KETERANGAN; ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="row_status" class="form-control">
                <option value="<?php echo $list->message[0]->ROW_STATUS; ?>"> <?php
                    if ($list->message[0]->ROW_STATUS == 0) {
                        echo "Aktif";
                    } ELSE {
                        echo "Tidak Aktif";
                    }
                    ?> </option>
                <?php
                if ($list->message[0]->ROW_STATUS == -1) {
                    ?>
                    <option value="0">Aktif</option>
                    <?php
                } else {
                    ?>
                    <option value="-1">Tidak Aktif</option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e ?>">Simpan</button>
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