<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

if ($list) {
    if (is_array($list->message)) {
        foreach ($list->message as $key => $value) {
            $kd_lokasidealer = $value->KD_LOKASIDEALER;
        }
    }
}
?>

<section class="wrapper">

    <form id="addFormx" action="<?php echo base_url('customer_service/service_advisor_update'); ?>" method="post">
        <input type="hidden" name="id" id="id" class="form-control" value="<?= $list->message[0]->ID; ?>" readonly aria-describedby="addon">

        <div class="breadcrumb margin-bottom-10">

            <?php echo breadcrumb(); ?>

            <div class="bar-nav pull-right">

                <div class="btn-group">
                    <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('customer_service/add_service_advisor'); ?>' >
                        <i class="fa fa-file-o fa-fw"></i> Tambah Data
                    </a>
                </div>

                <div class="btn-group">
                    <a id="submit-btn" type="submit" class="btn btn-default submit-btn $status_e" >
                        <i class="fa fa-save fa-fw"></i> Update Data 
                    </a>
                </div>

                <div class="btn-group">
                    <a role="button" href="<?php echo base_url("customer_service/service_advisor_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List SA</a>
                </div>

            </div>

        </div>

        <div class="col-xs-12 padding-left-right-10">

            <div class="row">

                <div class="col-sm-7">

                    <div class="panel margin-bottom-10">

                        <div class="panel-heading panel-custom">

                            <div class="row">

                                <div class="col-sm-6">
                                    <h4 class="panel-title pull-left" style="padding-top: 10px;">
                                        <i class="fa fa-file fa-fw"></i> Data Form SA
                                    </h4>
                                </div>

                                <div class="col-sm-6">

                                    <div class="input-group">
                                        <span class="input-group-addon" id="addon">Status</span>

<!--                                        <input type="text" name="status_sa" id="status_sa" class="form-control"
       value="<?php
                                        if ($list->message[0]->STATUS_SA == "0") {
                                            echo "Open";
                                        } elseif ($list->message[0]->STATUS_SA == "1") {
                                            echo "On Progress";
                                        } else {
                                            echo "Finish";
                                        }
                                        ?>"readonly aria-describedby="addon">-->

                                        <select id="status_sa" name="status_sa" class="form-control" >
                                            <option value="0" <?php echo ($list->message[0]->STATUS_SA == 0 ? "selected" : ""); ?>>Open</option>
                                            <option value="1" <?php echo ($list->message[0]->STATUS_SA == 1 ? "selected" : ""); ?>>On Progress</option>
                                            <option value="2" <?php echo ($list->message[0]->STATUS_SA == 2 ? "selected" : ""); ?>>Finish</option>
                                        </select>



                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="panel-body panel-body-border">

                            <div class="row">

                                <div class="col-xs-6 col-sm-6 col-md-6">

                                    <div class="form-group">
                                        <label>Kode Dealer</label>
                                        <input type="text" name="kd_dealer" id="kd_dealer" class="form-control disabled" value="<?= $list->message[0]->KD_DEALER; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Lokasi Dealer</label>
                                        <select class="form-control" id="kd_lokasidealer" name="kd_lokasidealer" required="true">
                                            <option value="0">--Pilih Lokasi Dealer--</option>
                                               <?php
                                                  if ($lokasidealer) {
                                                    if (is_array($lokasidealer->message)) {
                                                      foreach ($lokasidealer->message as $key => $value) {
                                                        $aktif = ($this->input->get("kd_lokasidealer") == $value->KD_LOKASI) ? "selected" :"";
                                                        $aktif = ($kd_lokasidealer == $value->KD_LOKASI) ? "selected" :  $aktif;
                                                         echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                                                      }
                                                    }
                                                  }
                                              ?>  
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Kode SA</label>
                                        <input type="text" name="kd_sa" id="kd_sa" class="form-control disabled" value="<?= $list->message[0]->KD_SA; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Customer</label>
                                        <input type="text" name="kd_customer" id="kd_customer" class="form-control" value="<?= $list->message[0]->KD_CUSTOMER; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal SA</label>
                                        <!--<input type="text" name="tanggal_sa" id="tanggal_sa" class="form-control" value="<?= $list->message[0]->TANGGAL_SA; ?>">-->
                                        <div class="input-group input-append date" id="datex">
                                            <input type="text" class="form-control" id="tanggal_sa" name="tanggal_sa" placeholder="dd/mm/yyyy" required="required" value="<?= tglFromSql($list->message[0]->TANGGAL_SA); ?>" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>No. STNK</label>
                                        <input type="text" name="no_stnk" id="no_stnk" class="form-control" value="<?= $list->message[0]->NO_STNK; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>No. Polisi</label>
                                        <input type="text" name="no_polisi" id="no_polisi" class="form-control" value="<?= $list->message[0]->NO_POLISI; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>No. Mesin</label>
                                        <input type="text" name="no_mesin" id="no_mesin" class="form-control" value="<?= $list->message[0]->NO_MESIN; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>No. Rangka</label>
                                        <input type="text" name="no_rangka" id="no_rangka" class="form-control" value="<?= $list->message[0]->NO_RANGKA; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Tipe PKB</label>
                                        <input type="text" name="kd_tipepkb" id="kd_tipepkb" class="form-control" value="<?= $list->message[0]->KD_TIPEPKB; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>KM Saat Ini</label>
                                        <input type="text" name="km_saatini" id="km_saatini" class="form-control" value="<?= $list->message[0]->KM_SAATINI; ?>">
                                    </div>

                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">    

                                    <div class="form-group">
                                        <label>Kebutuhan Konsumen</label>
                                        <textarea type="text" name="kebutuhan_konsumen" id="kebutuhan_konsumen" class="form-control" placeholder="Deskripsi masalah motor yang butuh di perbaiki"><?= $list->message[0]->KEBUTUHAN_KONSUMEN; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Hasil Analisa SA</label>
                                        <textarea type="text" name="hasil_analisa_sa" id="hasil_analisa_sa" class="form-control" placeholder="Deskripsi masalah motor yang di lihat"><?= $list->message[0]->HASIL_ANALISA_SA; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Saran Mekanik</label>
                                        <textarea type="text" name="saran_mekanik" id="saran_mekanik" class="form-control" placeholder="Saran untuk perbaikan motor"><?= $list->message[0]->SARAN_MEKANIK; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan Tambahan</label>
                                        <textarea type="text" name="catatan_tambahan" id="catatan_tambahan" class="form-control" placeholder="Deskripsi keperluan tambahan"><?= $list->message[0]->CATATAN_TAMBAHAN; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Konfirmasi Pekerjaan Tambahan</label>
                                        <textarea type="text" name="konfirmasi_pekerjaantambahan" id="konfirmasi_pekerjaantambahan" class="form-control" placeholder="Konfirmasi pekerjaan tambahan"><?= $list->message[0]->KONFIRMASI_PEKERJAANTAMBAHAN; ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat konsumen"><?= $list->message[0]->ALAMAT; ?></textarea>
                                    </div>
                                    
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-sm-5">

                    <div class="panel margin-bottom-10">

                        <div class="panel-heading panel-custom">

                            <div class="row">

                                <div class="col-sm-6">
                                    <h4 class="panel-title pull-left" style="padding-top: 10px;">
                                        <i class="fa fa-list fa-fw"></i> Detail Data
                                    </h4>
                                </div>

                            </div>

                        </div>


                        <div class="panel-body panel-body-border">

                            <div class="row">

                                <div class="col-xs-6 col-sm-6 col-md-6">

                                    <div class="form-group">
                                        <label>Kode Pembawa Motor</label>
                                        <input type="text" name="kd_pembawamotor" id="kd_pembawamotor" class="form-control" value="<?= $list->message[0]->KD_PEMBAWAMOTOR; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Pemakai Motor</label>
                                        <input type="text" name="kd_pemakaimotor" id="kd_pemakaimotor" class="form-control" value="<?= $list->message[0]->KD_PEMAKAIMOTOR; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Tipe Customer</label>
                                        <input type="text" name="kd_typecomingcustomer" id="kd_typecomingcustomer" class="form-control" value="<?= $list->message[0]->KD_TYPECOMINGCUSTOMER; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Honda</label>
                                        <input type="text" name="kd_honda" id="kd_honda" class="form-control" value="<?= $list->message[0]->KD_HONDA; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Jenis Pit</label>
                                        <input type="text" name="kd_jenispit" id="kd_jenispit" class="form-control" value="<?= $list->message[0]->KD_JENISPIT; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Tipe Service</label>
                                        <input type="text" name="kd_typeservice" id="kd_typeservice" class="form-control" value="<?= $list->message[0]->KD_TYPESERVICE; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Set Up Pembayaran</label>
                                        <input type="text" name="kd_setuppembayaran" id="kd_setuppembayaran" class="form-control" value="<?= $list->message[0]->KD_SETUPPEMBAYARAN; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Nama Pemilik</label>
                                        <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" value="<?= $list->message[0]->NAMA_PEMILIK; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?= $list->message[0]->NO_HP; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Foto Konsumen</label>
                                        <input type="text" name="foto_konsumen" id="foto_konsumen" class="form-control" value="<?= $list->message[0]->FOTO_KONSUMEN; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Dokumen</label>
                                        <input type="text" name="dokumen" id="dokumen" class="form-control" value="<?= $list->message[0]->DOKUMEN; ?>">
                                    </div>
                                   
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    
                                    <div class="form-group">
                                        <label>Nama Coming Customer</label>
                                        <input type="text" name="nama_comingcustomer" id="nama_comingcustomer" class="form-control" value="<?= $list->message[0]->NAMA_COMINGCUSTOMER; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Estimasi Pendaftaran</label>
                                        <div class="input-group input-append date" id="datex">
                                            <input type="text" class="form-control" id="estimasi_pendaftaran" name="estimasi_pendaftaran" placeholder="dd/mm/yyyy" value="<?= tglFromSql($list->message[0]->ESTIMASI_PENDAFTARAN); ?>" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Estimasi Pengerjaan</label>
                                        <div class="input-group input-append date" id="datex">
                                            <input type="text" class="form-control" id="estimasi_pengerjaan" name="estimasi_pengerjaan" placeholder="dd/mm/yyyy" value="<?= tglFromSql($list->message[0]->ESTIMASI_PENGERJAAN); ?>" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Estimasi Selesai</label>
                                        <div class="input-group input-append date" id="datex">
                                            <input type="text" class="form-control" id="estimasi_selesai" name="estimasi_selesai" placeholder="dd/mm/yyyy" value="<?= tglFromSql($list->message[0]->ESTIMASI_SELESAI); ?>" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Pekerjaan</label>
                                        <input type="text" name="kd_pekerjaan" id="kd_pekerjaan" class="form-control" value="<?= $list->message[0]->KD_PEKERJAAN; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Part Number</label>
                                        <input type="text" name="part_number" id="part_number" class="form-control" value="<?= $list->message[0]->PART_NUMBER; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Total FRT</label>
                                        <input type="text" name="total_frt" id="total_frt" class="form-control" value="<?= $list->message[0]->TOTAL_FRT; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control" value="<?= $list->message[0]->AMOUNT; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>No. Pit</label>
                                        <input type="text" name="no_pit" id="no_pit" class="form-control" value="<?= $list->message[0]->NO_PIT; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Bensin Saat Ini</label>
                                        <input type="text" name="bensin_saatini" id="bensin_saatini" class="form-control" value="<?= $list->message[0]->BENSIN_SAATINI; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>No. Buku</label>
                                        <input type="text" name="no_buku" id="no_buku" class="form-control" value="<?= $list->message[0]->NO_BUKU; ?>">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

    <?php echo loading_proses(); ?>

</section>

<script type="text/javascript">
    $(document).ready(function () {

        $('#baru').click(function () {
            document.location.reload();
        })

        $("#submit-btn").on('click', function (event) {
            var formId = '#' + $(this).closest('form').attr('id');
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");

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

    function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>customer_service/" + param;
        var datax = {"kd": value};
        $('#' + id + '').attr('disabled', 'disabled');
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