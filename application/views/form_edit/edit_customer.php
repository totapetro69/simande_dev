<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<?php
$kd_propinsi = "";
$kd_kabupaten = "";
$kd_kecamatan = "";
$kd_desa = "";

if ($list) {
    if (is_array($list->message)) {
        foreach ($list->message as $key => $value) {
            $kd_propinsi = $value->KD_PROPINSI;
            $kd_kabupaten = $value->KD_KOTA;
            $kd_kecamatan = $value->KD_KECAMATAN;
            $kd_desa = $value->KELURAHAN;
        }
    }
}
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('customer/update_customer/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Customer : <?php echo  $list->message[0]->NAMA_CUSTOMER; ?></h4>
    </div>

    <div class="modal-body">

        <div class="row table-responsive">

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Kode Customer</label>
                    <input type="text" name="kd_customer" id="kd_customer" class="form-control" value="<?php echo  $list->message[0]->KD_CUSTOMER; ?>" placeholder="AUTO GENERATE" readonly>
                </div>

                <div class="form-group">
                    <label>Nama Customer</label>
                    <input type="text" name="nama_customer" id="nama_customer" class="form-control" value="<?php echo  $list->message[0]->NAMA_CUSTOMER; ?>" placeholder="Masukkan Nama Customer" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control" id="kd_gender" name="kd_gender" required>
                        <option value="" >- Pilih Jenis Kelamin -</option>
                        <?php
                        if ($genders):
                            foreach ($genders->message as $key => $gender) :
                                ?>
                                <option value="<?php echo  $gender->KD_GENDER; ?>" <?php echo ($gender->KD_GENDER == $list->message[0]->JENIS_KELAMIN ? "selected" : ""); ?> ><?php echo  $gender->NAMA_GENDER; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="date">Tgl. Lahir</label>
                    <div class="input-group input-append date">
                        <input class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="DD/MM/YYYY" value="<?php echo tglfromSql($list->message[0]->TGL_LAHIR); ?>" type="text"/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tgl. Pembuatan NPWP</label>
                    <div class="input-group input-append date" id="date">
                        <input class="form-control" id="tgl_pembuatan_npwp" name="tgl_pembuatan_npwp" placeholder="DD/MM/YYYY" value="<?php echo tglfromSql($list->message[0]->TGL_PEMBUATAN_NPWP); ?>" type="text"/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Surat</label>
                    <textarea type="text" name="alamat_surat" id="alamat_surat" class="form-control" value="<?php echo  $list->message[0]->ALAMAT_SURAT; ?>" placeholder="Masukkan Alamat Surat" required><?php echo  $list->message[0]->ALAMAT_SURAT; ?></textarea>
                </div>

                <div class="form-group">
                    <label>No. KTP</label>
                    <input type="text" name="no_ktp" id="no_ktp" class="form-control" value="<?php echo  $list->message[0]->NO_KTP; ?>" placeholder="Masukkan No. KTP" >
                </div>

                <div class="form-group">
                    <label>No. NPWP</label>
                    <input type="text" name="no_npwp" id="no_npwp" class="form-control" value="<?php echo  $list->message[0]->NO_NPWP; ?>" placeholder="Masukkan No. NPWP" >
                </div>

                <div class="form-group">
                    <label>Propinsi</label>
                    <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                        <option value="0">--Pilih Propinsi--</option>
                        <?php
                        /* var_dump($propinsi);
                          exit(); */
                        if ($propinsi) {
                            if (is_array($propinsi->message)) {
                                foreach ($propinsi->message as $key => $value) {
                                    $select = ($kd_propinsi == $value->KD_PROPINSI) ? "selected" : "";
                                    echo "<option value='" . $value->KD_PROPINSI . "' " . $select . ">" . $value->NAMA_PROPINSI . "</option>";
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
                        <option value="0">--Pilih Kecmatan--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelurahan <span id="l_desa"></span></label>
                    <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                        <option value="0">--Pilih Kelurahan--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_possurat" id="kode_possurat" class="form-control" value="<?php echo  $list->message[0]->KODE_POS; ?>" placeholder="Masukkan Kode Pos" >
                </div>

                <div class="form-group">
                    <label>Agama</label>
                    <select class="form-control" id="kd_agama" name="kd_agama" >
                        <option value="" >- Pilih Agama -</option>
                        <?php
                        if ($agamas):
                            foreach ($agamas->message as $key => $agama) :
                                ?>
                                <option value="<?php echo  $agama->KD_AGAMA; ?>" <?php echo ($agama->KD_AGAMA == $list->message[0]->KD_AGAMA ? "selected" : ""); ?> ><?php echo  $agama->NAMA_AGAMA; ?></option>
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
                                <option value="<?php echo  $pekerjaan->KD_PEKERJAAN; ?>" <?php echo ($pekerjaan->KD_PEKERJAAN == $list->message[0]->KD_PEKERJAAN ? "selected" : ""); ?> ><?php echo  $pekerjaan->NAMA_PEKERJAAN; ?></option>
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
                        <option <?php echo ($list->message[0]->PENGELUARAN == 1 ? "selected" : ""); ?> value="1"><= 900.000</option>
                        <option <?php echo ($list->message[0]->PENGELUARAN == 2 ? "selected" : ""); ?> value="2">Rp. 900.001 s/d Rp. 1.250.000</option>
                        <option <?php echo ($list->message[0]->PENGELUARAN == 3 ? "selected" : ""); ?> value="3">Rp. 1.250.001 s/d Rp. 1.759.000</option>
                        <option <?php echo ($list->message[0]->PENGELUARAN == 4 ? "selected" : ""); ?> value="4">Rp. 1.759.001 s/d Rp. 2.500.000</option>
                        <option <?php echo ($list->message[0]->PENGELUARAN == 5 ? "selected" : ""); ?> value="5">Rp. 2.500.001 s/d Rp. 4.000.000</option>
                        <option <?php echo ($list->message[0]->PENGELUARAN == 6 ? "selected" : ""); ?> value="6">Rp. 4.000.001 s/d Rp. 6.000.000</option>
                        <option <?php echo ($list->message[0]->PENGELUARAN == 7 ? "selected" : ""); ?> value="7">> 6.000.000</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pendidikan</label>
                    <select name="kd_pendidikan" id="kd_pendidikan" class="form-control">
                        <option value="">-- Pilih Pendidikan --</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "SD" ? "selected" : ""); ?> value="SD">SD</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "SLTP" ? "selected" : ""); ?> value="SLTP">SLTP</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "SLTA" ? "selected" : ""); ?> value="SLTA">SLTA</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "DIPLOMA" ? "selected" : ""); ?> value="DIPLOMA">DIPLOMA</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "S1" ? "selected" : ""); ?> value="S1">STRATA 1</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "S2" ? "selected" : ""); ?> value="S2">STRATA 2</option>
                        <option <?php echo ($list->message[0]->KD_PENDIDIKAN == "S3" ? "selected" : ""); ?> value="S3">STRATA 3</option>    
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Penanggung Jawab</label>
                    <input type="text" name="nama_penanggungjawab" id="nama_penanggungjawab" class="form-control" value="<?php echo  $list->message[0]->NAMA_PENANGGUNGJAWAB; ?>" placeholder="Masukkan Nama Penanggung Jawab" required>
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?php echo  $list->message[0]->NO_HP; ?>" placeholder="Masukkan No. HP" >
                </div>

                <div class="form-group">
                    <label>Kode No. Telp</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" value="<?php echo  $list->message[0]->NO_TELEPON; ?>" placeholder="Masukkan No. Telp" >
                </div>

                <div class="form-group">
                    <label>Status Di Hubungi</label>
                    <select name="nama_metode" id="nama_metode" class="form-control">
                        <option value="">-- Pilih Status Di Hubungi --</option>
                         <?php
                        if ($status):

                            foreach ($status->message as $key => $value) :
                                ?>
                                <option value="<?php echo  $value->NAMA_METODE; ?>" <?php echo ($value->NAMA_METODE == $list->message[0]->STATUS_DIHUBUNGI ? "selected" : ""); ?> ><?php echo  $value->NAMA_METODE; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo  $list->message[0]->EMAIL; ?>" placeholder="Masukkan Email" >
                </div>

                <div class="form-group">
                    <label>Status Rumah</label>
                    <select name="status_rumah" id="status_rumah" class="form-control">
                        <option value="">-- Pilih Status Rumah --</option>
                        <option <?php echo ($list->message[0]->STATUS_RUMAH == "Rumah Sendiri" ? "selected" : ""); ?> value="Rumah Sendiri">Rumah Sendiri</option>
                        <option <?php echo ($list->message[0]->STATUS_RUMAH == "Rumah Orang Tua / Keluarga" ? "selected" : ""); ?> value="Rumah Orang Tua / Keluarga">Rumah Orang Tua / Keluarga</option>
                        <option <?php echo ($list->message[0]->STATUS_RUMAH == "Rumah Sewa" ? "selected" : ""); ?> value="Rumah Sewa">Rumah Sewa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status No. HP</label>
                    <select name="status_nohp" id="status_nohp" class="form-control">
                        <option value="">-- Pilih Status No. HP --</option>
                        <option <?php echo ($list->message[0]->STATUS_NOHP == "Aktif" ? "selected" : ""); ?> value="Aktif">Aktif</option>
                        <option <?php echo ($list->message[0]->STATUS_NOHP == "Tidak" ? "selected" : ""); ?> value="Tidak">Tidak Aktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text" name="akun_fb" id="akun_fb" class="form-control" value="<?php echo  $list->message[0]->AKUN_FB; ?>" placeholder="Masukkan Facebook" >
                </div>

                <div class="form-group">
                    <label>Twitter</label>
                    <input type="text" name="akun_twitter" id="akun_twitter" class="form-control" value="<?php echo  $list->message[0]->AKUN_TWITTER; ?>" placeholder="Masukkan Twitter" >
                </div>

                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="akun_instagram" id="akun_instagram" class="form-control" value="<?php echo  $list->message[0]->AKUN_INSTAGRAM; ?>" placeholder="Masukkan Instagram" >
                </div>

                <div class="form-group">
                    <label>Youtube</label>
                    <input type="text" name="akun_youtube" id="akun_youtube" class="form-control" value="<?php echo  $list->message[0]->AKUN_YOUTUBE; ?>" placeholder="Masukkan Youtube" >
                </div>

                <div class="form-group">
                    <label>Hobi</label>
                    <input type="text" name="hobi" id="hobi" class="form-control" value="<?php echo  $list->message[0]->HOBI; ?>" placeholder="Masukkan Hobi" >
                </div>

                <div class="form-group">
                    <label>Karakteristik Konsumen</label>
                    <input type="text" name="karakteristik_konsumen" id="karakteristik_konsumen" class="form-control" value="<?php echo  $list->message[0]->KARAKTERISTIK_KONSUMEN; ?>" placeholder="Masukkan Karakteristik Konsumen" >
                </div>

                <div class="form-group">
                    <label>ID Refferal</label>
                    <select class="form-control" name="kd_sales" id="kd_sales">
                        <option value='0'>--Pilih Nama Sales--</option>
                        <?php
                        if ($sales):

                            foreach ($sales->message as $key => $value) :
                                ?>
                                <option value="<?php echo  $value->KD_SALES; ?>" <?php echo ($value->KD_SALES == $list->message[0]->ID_REFFERAL ? "selected" : ""); ?> ><?php echo  $value->NAMA_SALES; ?></option>
                                <?php
                            endforeach;
                        endif;
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

        loadData('kd_kabupaten', '<?php echo $kd_propinsi; ?>', '<?php echo $kd_kabupaten; ?>');
        loadData('kd_kecamatan', '<?php echo $kd_kabupaten; ?>', '<?php echo $kd_kecamatan; ?>');
        loadData('kd_desa', '<?php echo $kd_kecamatan; ?>', '<?php echo $kd_desa; ?>');

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
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>