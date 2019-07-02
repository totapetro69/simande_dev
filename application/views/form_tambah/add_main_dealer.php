<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_main_dealer_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Main Dealer Baru</h4>
    </div>

    <div class="modal-body">

        <div class="row table-responsive">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Main Dealer</label>
                    <input type="text" name="kd_maindealer" id="kd_maindealer" class="form-control" placeholder="Masukkan Kode Main Dealer" maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Main Dealer</label>
                    <input type="text" name="nama_maindealer" id="nama_maindealer" class="form-control" placeholder="Masukkan Nama Main Dealer" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea type="text" name="alamat" id="alamat" class="form-control " placeholder="Masukkan Alamat Main Dealer" required></textarea>
                </div>

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

                <div class="form-group">
                    <label>Kabupaten <span id="l_kabupaten"></span></label>
                    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                        <option value="0">--Pilih Kabupaten--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" id="telepon" class="form-control " placeholder="Masukkan No. Telepon" required>
                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function () {


            /*pilihan propinsi*/
            $('#kd_propinsi').on('change', function () {
                loadData('kd_kabupaten', $('#kd_propinsi').val(), '0')
            })
            $('#kd_kabupaten').on('change', function () {
                loadData('kd_kecamatan', $(this).val(), '0')
            })
            $('#kd_kecamatan').on('change', function () {
                loadData('kd_desa', $(this).val(), '0')
            })

            $('#baru').click(function () {
                document.location.reload();
            })

        })

        function loadData(id, value, select) {

            var param = $('#' + id + '').attr('title');
            $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
            var urls = "<?php echo base_url(); ?>customer/" + param;
            var datax = {"kd": value};
            $('#' + id + '').attr('disabled', 'disabled');
            $.ajax({
                type: 'GET',
                url: urls,
                data: datax,
                typeData: 'html',
                success: function (result) {
                    $('#' + id + '').empty();
                    $('#' + id + '').html(result);
                    $('#' + id + '').val(select).select();
                    $('#l_' + param + '').html('');
                    $('#' + id + '').removeAttr('disabled');
                }
            });
        }
    </script>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>
