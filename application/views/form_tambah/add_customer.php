<form id="addForm" class="bucket-form" action="<?php echo base_url('customer/add_customer_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class='fa fa-users fa-fw'></i> Tambah Customer Baru</h4>
    </div>

    <div class="modal-body">

        <div class="row table-responsive">

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Kode Customer</label>
                    <input type="text" name="kd_customer" id="kd_customer" class="form-control" placeholder="AUTO GENERATE" readonly>
                </div>

                <div class="form-group">
                    <label>Nama Customer</label>
                    <input type="text" name="nama_customer" id="nama_customer" class="form-control" placeholder="Masukkan Nama Customer" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control" id="kd_gender" name="kd_gender" required>
                        <option value="" >- Pilih Jenis Kelamin -</option>
                        <?php
                        if ($genders):
                            foreach ($genders->message as $key => $gender) :
                                ?>
                                <option value="<?php echo $gender->KD_GENDER; ?>"><?php echo $gender->NAMA_GENDER; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tgl. Lahir</label>
                    <div class="input-group input-append date" id="date">
                        <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" value="" placeholder="dd/mm/yyyy" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tgl. Pembuatan NPWP</label>
                    <div class="input-group input-append date" id="date">
                        <input type="text" class="form-control" id="tgl_pembuatan_npwp" name="tgl_pembuatan_npwp" value="" placeholder="dd/mm/yyyy" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Surat</label>
                    <textarea type="text" name="alamat_surat" id="alamat_surat" class="form-control" placeholder="Masukkan Alamat Surat" required></textarea>
                </div>

                <div class="form-group">
                    <label>No. KTP</label>
                    <input type="text" name="no_ktp" id="no_ktp" class="form-control input-number" placeholder="Masukkan No. KTP" >
                </div>

                <div class="form-group">
                    <label>No. NPWP</label>
                    <input type="text" name="no_npwp" id="no_npwp" class="form-control input-number" placeholder="Masukkan No. NPWP" >
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
                    <label>Kecamatan <span id="l_kecamatan"></span></label>
                    <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
                        <option value="0">--Pilih Kecamatan--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelurahan <span id="l_desa"></span></label>
                    <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                        <option value="0">--Pilih Desa/Kelurahan--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_possurat" id="kode_possurat" class="form-control input-number" placeholder="Masukkan Kode Pos" >
                </div>

                <div class="form-group">
                    <label>Agama</label>
                    <select class="form-control" id="kd_agama" name="kd_agama" >
                        <option value="" >- Pilih Agama -</option>
                        <?php
                        if ($agamas):
                            foreach ($agamas->message as $key => $agama) :
                                ?>
                                <option value="<?php echo $agama->KD_AGAMA; ?>"  ><?php echo $agama->NAMA_AGAMA; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pekerjaan</label>
                    <select class="form-control" id="kd_pekerjaan" name="kd_pekerjaan" >
                        <option value="" >- Pilih Pekerjaan -</option>
                        <?php
                        if ($pekerjaans):
                            foreach ($pekerjaans->message as $key => $pekerjaan) :
                                ?>
                                <option value="<?php echo $pekerjaan->KD_PEKERJAAN; ?>"  ><?php echo $pekerjaan->NAMA_PEKERJAAN; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Pengeluaran</label>
                    <select name="pengeluaran" id="pengeluaran" class="form-control">
                        <option value="">-- Pilih Pengeluaran Bulanan --</option>
                        <option value="1"><= 900.000</option>
                        <option value="2">Rp. 900.001 s/d Rp. 1.250.000</option>
                        <option value="3">Rp. 1.250.001 s/d Rp. 1.759.000</option>
                        <option value="4">Rp. 1.759.001 s/d Rp. 2.500.000</option>
                        <option value="5">Rp. 2.500.001 s/d Rp. 4.000.000</option>
                        <option value="6">Rp. 4.000.001 s/d Rp. 6.000.000</option>
                        <option value="7">> 6.000.000</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pendidikan</label>
                    <select name="kd_pendidikan" id="kd_pendidikan" class="form-control">
                        <option value="">-- Pilih Pendidikan Terakhir --</option>
                        <option value="SD">SD</option>
                        <option value="SLTP">SLTP</option>
                        <option value="SLTA">SLTA</option>
                        <option value="DIPLOMA">DIPLOMA</option>
                        <option value="S1">STRATA 1</option>
                        <option value="S2">STRATA 2</option>
                        <option value="S3">STRATA 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Penanggung Jawab</label>
                    <input type="text" name="nama_penanggungjawab" id="nama_penanggungjawab" class="form-control" placeholder="Masukkan Nama Penanggung Jawab" required>
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan No. HP" required>
                </div>
                <div class="form-group">
                    <label>Kode No. Telp</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="Masukkan No. Telp" >
                </div>

                <div class="form-group">
                    <label>Status Di Hubungi</label>
                    <select class="form-control" id="nama_metode" name="nama_metode" >
                        <option value="" >- Pilih Status Di Hubungi -</option>
                        <?php
                        if ($status) {
                            if (is_array($status->message)) {
                                foreach ($status->message as $key => $value) {
                                    echo "<option value='" . $value->NAMA_METODE . "'>" . $value->NAMA_METODE . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email" >
                </div>

                <div class="form-group">
                    <label>Status Rumah</label>
                    <select name="status_rumah" id="status_rumah" class="form-control">
                        <option value="">-- Pilih Status Rumah --</option>
                        <option value="Rumah Sendiri">Rumah Sendiri</option>
                        <option value="Rumah Orang Tua / Keluarga">Rumah Orang Tua / Keluarga</option>
                        <option value="Rumah Sewa">Rumah Sewa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status No. HP</label>
                    <select name="status_nohp" id="status_nohp" class="form-control">
                        <option value="">-- Pilih Status No. HP --</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak">Tidak Aktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text" name="akun_fb" id="akun_fb" class="form-control" placeholder="Masukkan Facebook" >
                </div>

                <div class="form-group">
                    <label>Twitter</label>
                    <input type="text" name="akun_twitter" id="akun_twitter" class="form-control" placeholder="Masukkan Twitter" >
                </div>

                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="akun_instagram" id="akun_instagram" class="form-control" placeholder="Masukkan Instagram" >
                </div>

                <div class="form-group">
                    <label>Youtube</label>
                    <input type="text" name="akun_youtube" id="akun_youtube" class="form-control" placeholder="Masukkan Youtube" >
                </div>

                <div class="form-group">
                    <label>Hobi</label>
                    <input type="text" name="hobi" id="hobi" class="form-control" placeholder="Masukkan Hobi" >
                </div>

                <div class="form-group">
                    <label>Karakteristik Konsumen</label>
                    <input type="text" name="karakteristik_konsumen" id="karakteristik_konsumen" class="form-control" placeholder="Masukkan Karakteristik Konsumen" >
                </div>

                <div class="form-group">
                    <label>ID Refferal</label>
                    <select class="form-control" name="kd_sales" id="kd_sales">
                        <option value='0'>--Pilih Nama Sales--</option>
                        <?php
                        if ($sales) {
                            if (is_array($sales->message)) {
                                foreach ($sales->message as $key => $value) {
                                    echo "<option value='" . $value->KD_SALES . "'>" . $value->NAMA_SALES . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

            </div>

        </div>

    </div>



    <script>

        var date = new Date();
        date.setDate(date.getDate());

        $('.date').datepicker({
            format: 'dd/mm/yyyy',
            endDate: date,
            autoclose: true
        });

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

        function loadData(id, value, select) {

            var param = $('#' + id + '').attr('title');
            $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
            var urls = "<?php echo base_url(); ?>customer/" + param;
            var datax = {"kd": value};
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
                }
            });
        }
    </script>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>