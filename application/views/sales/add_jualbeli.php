<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->session->userdata("kd_dealer"));
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$no_trans = base64_decode($this->input->get("t"));
?>
<section class="wrapper">

    <form class="bucket-form" id="addFormz" method="post" action="<?php echo base_url("customer_service/simpan_service_advisor"); ?>" autocomplete="off">
        <div class="breadcrumb margin-bottom-10">

            <?php echo breadcrumb(); ?>

            <div class="bar-nav pull-right ">

                <div class="btn-group">
                    <a id="baru" type="submit" class="btn btn-default baru ">
                        <i class="fa fa-file-o fa-fw"></i> Baru
                    </a>
                </div>

                <div class="btn-group">
                    <a id="submit-btn" type="button" class="btn btn-default submit-btn <?php echo $status_c; ?>">  
                        <i class="fa fa-save fa-fw"></i> Simpan
                    </a>
                </div>

                <!--                <div class="btn-group">
                                    <a type="button" id="modal-button" class="btn btn-default disabled"  role="button" onclick='addForm("<?php echo base_url('customer_service/print_service_advisor'); ?>");' data-toggle="modal" data-target="#myModalLg" data-backdrop="static"> 
                                        <i class="fa fa-print fa-fw"></i> Cetak
                                    </a>
                                </div>-->

                <div class="btn-group">
                    <a role="button" href="<?php echo base_url("customer_service/service_advisor_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List SA</a>
                </div>

            </div>

        </div>

        <div class="col-lg-12 padding-left-right-10">

            <div class="panel margin-bottom-10">

                <div class="panel-heading"><i class='fa fa-list-ul'></i> Form SA
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-up" href="javascript:;"></a>
                    </span>
                </div>

                <div class="panel-body panel-body-border" style="display: block;">

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n; ?>>
                                        <option value="0">--Pilih Dealer--</option>
                                        <?php
                                        if ($dealer) {
                                            if (is_array($dealer->message)) {
                                                foreach ($dealer->message as $key => $value) {
                                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                    $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                                }
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Kode Lokasi Dealer</label>
                                    <select class="form-control" id="kd_lokasidealer" name="kd_lokasidealer" required="true">
                                        <option value="0">--Pilih Lokasi Dealer--</option>
                                         <?php
                                            if ($lokasidealer) {
                                              if (is_array($lokasidealer->message)) {
                                                foreach ($lokasidealer->message as $key => $value) {
                                                  $aktif = ($this->input->get("kd_lokasidealer") == $value->KD_LOKASI) ? "selected" : '';
                                                  echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                                                }
                                              }
                                            }
                                        ?>  
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <br>
                                    <input type="checkbox" name="" class=""/> From Booking 
                                </div>
                            </div>

                            <!-- <div class="col-sm-3">
                                
                            </div> -->

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group input-append date" id="date">
                                        <input class="form-control" id="tanggal_sa" name="tanggal_sa" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>No. Transaksi</label>
                                    <input type="text" class="form-control" id="kd_sa" autocomplete="off" name="kd_sa" placeholder="AUTO GENERATE" value="<?php echo $no_trans; ?>" readonly="true">
                                </div>
                            </div>

                            <!-- <div class="col-sm-3">
                                
                            </div> -->

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xs-12 padding-left-right-10">
            <div class="row">

                <div class="col-sm-12">

                    <div class="panel margin-bottom-10">

                        <div class="panel-heading panel-custom">

                            <div class="row">

                                <div class="col-sm-5">
                                    <h4 class="panel-title pull-left" style="padding-top: 10px;">
                                        <i class="fa fa-list fa-fw"></i> Data Kendaraan
                                    </h4>
                                </div>

                            </div>

                        </div>

                        <div class="panel-body panel-body-border">

                            <div class="row">

                                <div class="col-xs-12 col-sm-12 col-md-6">

                                    <div class="col-xs-12 col-sm-12 col-md-6">

                                        <div class="form-group">
                                            <label>No. Polisi <span class="load-form"></span></label>
                                            <input type="text" name="no_polisi" id="no_polisi" class="form-control" style="text-transform: uppercase;" placeholder="AB-1234-XX" autocomplete="off" typeaheadurl="<?php echo base_url('customer_service/nopol_typeahead'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>No. Rangka <span class="load-form"></span></label>
                                            <input type="text" name="no_rangka" id="no_rangka" class="form-control" style="text-transform: uppercase;" placeholder="Nomor Rangka" >
                                        </div>

                                        <div class="form-group">
                                            <label>KM Motor</label>
                                            <input type="text" name="km_saatini" id="km_saatini" class="form-control qurency" placeholder="Masukkan Kode Motor" required>
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6">

                                        <div class="form-group">
                                            <label>No STNK <span></span></label>

                                            <input type="text" name="no_stnk" id="no_stnk" class="form-control" style="text-transform: uppercase;" placeholder="Masukkan No STNK" autocomplete="off" >

                                        </div>

                                        <div class="form-group">
                                            <label>No. Mesin <span class="load-form"></span>    </label>
                                            <input type="text" name="no_mesin" id="no_mesin" class="form-control" style="text-transform: uppercase;" placeholder="Nomor Mesin" >
                                        </div>

                                        <div class="form-group">
                                            <label>Jenis Service</label>
                                            <select class="form-control " id="kd_tipepkb" name="kd_tipepkb" required>
                                                <?php if ($tipepkb && (is_array($tipepkb->message) || is_object($tipepkb->message))): foreach ($tipepkb->message as $key => $value) : ?>
                                                        <option value="<?php echo $value->KD_TIPEPKB; ?>"><?= $value->KD_TIPEPKB; ?> - <?= $value->NAMA_TIPEPKB; ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">

                                        <div class="form-group">
                                            <label>Keluhan Konsumen</label>
                                            <textarea type="text" name="kebutuhan_konsumen" id="kebutuhan_konsumen" class="form-control" placeholder="Deskripsi masalah motor yang butuh di perbaiki" required></textarea>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6">

                                    <div class="col-xs-12 col-sm-12 col-md-6">

                                        <div class="form-group">
                                            <label>Nama Pemilik <span class="load-form"></span></label>
                                            <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" placeholder="Masukkan Nama Pemilik" >
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label>No. HP <span class="load-form"></span></label>
                                            <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Nomor HP" >
                                        </div>


                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label>Alamat <span class="load-form"></span></label>
                                            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" >
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6">

                                        <div class="form-group">
                                            <label>Tipe Coming Customer</label>
                                            <select class="form-control" id="kd_typecomingcustomer" name="kd_typecomingcustomer" >
                                                <option value="" >- Pilih Tipe Coming Customer -</option>
                                                <?php
                                                if ($customer):
                                                    foreach ($customer->message as $key => $value) :
                                                        ?>
                                                        <option value="<?= $value->KD_TYPECOMINGCUSTOMER; ?>"><?= $value->NAMA_TYPECOMINGCUSTOMER; ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label></label>
                                            <input type="text" name="nama_comingcustomer" id="nama_comingcustomer" class="form-control" placeholder="Masukkan Nama Customer" >
                                        </div>


                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label>Hasil Analisa SA</label>
                                            <textarea type="text" name="hasil_analisa_sa" id="hasil_analisa_sa" class="form-control" placeholder="Deskripsi masalah motor yang di lihat SA" required></textarea>
                                        </div>
                                    </div>

                                    <!--hidden-->
                                    <div class="form-group hidden">
                                        <input type="text" name="kd_customer" id="kd_customer" class="form-control" placeholder="">
                                        <input type="text" name="kd_maindealer" id="kd_maindealer" class="form-control" placeholder="">
                                        <input type="text" name="kd_pembawamotor" id="kd_pembawamotor" class="form-control" placeholder="">
                                        <input type="text" name="kd_pemakaimotor" id="kd_pemakaimotor" class="form-control" placeholder="">
                                        <input type="text" name="kd_honda" id="kd_honda" class="form-control" placeholder="">
                                        <input type="text" name="kd_jenispit" id="kd_jenispit" class="form-control" placeholder="">
                                        <input type="text" name="foto_konsumen" id="foto_konsumen" class="form-control" placeholder="">
                                        <input type="text" name="dokumen" id="dokumen" class="form-control" placeholder="">
                                        <input class="form-control" id="estimasi_pendaftaran" name="estimasi_pendaftaran" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                                        <input class="form-control" id="estimasi_pengerjaan" name="estimasi_pengerjaan" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                                        <input class="form-control" id="estimasi_selesai" name="estimasi_selesai" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                                        <input type="text" name="saran_mekanik" id="saran_mekanik" class="form-control" placeholder="">
                                        <input type="text" name="kd_pekerjaan" id="kd_pekerjaan" class="form-control" placeholder="">
                                        <input type="text" name="part_number" id="part_number" class="form-control" placeholder="">
                                        <input type="text" name="total_frt" id="total_frt" class="form-control" placeholder="">
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="">
                                        <input type="text" name="no_pit" id="no_pit" class="form-control" placeholder="">
                                        <input type="text" name="kd_typeservice" id="kd_typeservice" class="form-control" placeholder="">
                                        <input type="text" name="kd_setuppembayaran" id="kd_setuppembayaran" class="form-control" placeholder="">
                                        <input type="text" name="catatan_tambahan" id="catatan_tambahan" class="form-control" placeholder="">
                                        <input type="text" name="bensin_saatini" id="bensin_saatini" class="form-control" placeholder="">
                                        <input type="text" name="konfirmasi_pekerjaantambahan" id="konfirmasi_pekerjaantambahan" class="form-control" placeholder="">
                                        <input type="text" name="no_buku" id="no_buku" class="form-control" placeholder="">
                                        <input type="text" name="status_sa" id="status_sa" class="form-control" placeholder="">

                                    </div>

                                </div>

                            </div>
                            <!-- </form> -->
                        </div>

                    </div>

                </div>
                <!-- </form> -->
            </div>

        </div>

        <!--        <div class="panel-footer">
                    
                    <div class="row">
        
                        <div class="col-sm-5">
                            <small class="text-muted inline m-t-sm m-b-sm"> 
        <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                            </small>
                        </div>
                        
                        <div class="col-sm-7 text-right text-center-xs">                
        <?php echo $pagination; ?>
                        </div>
                        
                    </div>
                    
                </div>-->

        </div>
    </form>
    <?php echo loading_proses(); ?>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        $('#baru').click(function () {
            document.location.reload();
        })
        
        $("#no_polisi").change(function () {

            // var kdDealer = $('#kd_dealer').val();
            var no_polisi = $(this).val();

            var url = "<?php echo base_url() . '/customer_service/get_nopol/'; ?>";

            $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

            $.getJSON(url, {
                no_polisi: no_polisi
            }, function (data, status) {

                if (status == 'success') {


                    $('#no_stnk').val(data.nopol_header.message['0'].DATA_NOMOR_STNK);
                    $('#no_rangka').val(data.nopol_header.message['0'].NO_RANGKA);



                    if (data.nopol_header.message['0'].KD_MESIN == undefined && data.nopol_header.message['0'].NO_MESIN == undefined) {
                        $('#no_mesin').val('');
                    } else {
                        $('#no_mesin').val(data.nopol_header.message['0'].KD_MESIN + data.nopol_header.message['0'].NO_MESIN);
                    }

                    $('#nama_pemilik').val(data.nopol_header.message['0'].NAMA_PEMILIK);
                    $('#no_hp').val(data.nopol_header.message['0'].NO_HP);
                    $('#alamat').val(data.nopol_header.message['0'].ALAMAT_SURAT);
                    $('#kd_customer').val(data.nopol_header.message['0'].KD_CUSTOMER);
//                    console.log(data.stnk_header.message['0'].KD_MESIN); 
//          
                }

                $(".load-form").html('');

            });


        });

//        $("#no_stnk").change(function () {
//
//            // var kdDealer = $('#kd_dealer').val();
//            var no_stnk = $(this).val();
//
//            var url = "<?php echo base_url() . '/customer_service/get_stnk/'; ?>";
//
//            $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
//
//            $.getJSON(url, {
//                no_stnk: no_stnk
//            }, function (data, status) {
//
//                if (status == 'success') {
//
//
//                    $('#no_polisi').val(data.stnk_header.message['0'].DATA_NOMOR_PLAT);
//                    $('#no_rangka').val(data.stnk_header.message['0'].NO_RANGKA);
//
//
//
//                    if (data.stnk_header.message['0'].KD_MESIN == undefined && data.stnk_header.message['0'].NO_MESIN == undefined) {
//                        $('#no_mesin').val('');
//                    } else {
//                        $('#no_mesin').val(data.stnk_header.message['0'].KD_MESIN + data.stnk_header.message['0'].NO_MESIN);
//                    }
//
//                    $('#nama_pemilik').val(data.stnk_header.message['0'].NAMA_PEMILIK);
//                    $('#no_hp').val(data.stnk_header.message['0'].NO_HP);
//                    $('#alamat').val(data.stnk_header.message['0'].ALAMAT_SURAT);
//                    $('#kd_customer').val(data.stnk_header.message['0'].KD_CUSTOMER);
////                    console.log(data.stnk_header.message['0'].KD_MESIN); 
////          
//                }
//
//                $(".load-form").html('');
//
//            });
//
//
//        });

        var date = new Date();
        date.setDate(date.getDate());

        $('#date,#datex, #date_fu, #date_fu1, #date_fu2').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true
        });

        $('.qurency').mask('000.000.000.000.000', {reverse: true});

        $('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
          A: {pattern: /[A-Za-z]/},
          Z: {pattern: /[A-Za-z]/,optional:true},
          0: {pattern: /[0-9]/},
          1: {pattern: /[0-9]/,optional:true}
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