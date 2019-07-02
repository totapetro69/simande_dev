<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<section class="wrapper">

    <form id="addFormx" action="<?php echo base_url('pkb/update_pkb'); ?>" method="post">

        <div class="breadcrumb margin-bottom-10">

            <?php echo breadcrumb(); ?>

            <div class="bar-nav pull-right">

                <a id="submit-btn" type="submit" class="btn btn-default submit-btn $status_e" >
                    <i class="fa fa-save fa-fw"></i> Update Part
                </a>

                <a href="<?php echo base_url('pkb/pkb_list'); ?>" class="btn btn-default $status_v">
                    <i class="fa fa-list"></i> List PKB
                </a>

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
                                        <i class="fa fa-list fa-fw"></i> Data Part
                                    </h4>
                                </div>

                            </div>

                        </div>

                        <div class="panel-body panel-body-border">

                            <div class="row">

                                <div class="col-xs-6 col-sm-6 col-md-6">

                                    <div class="form-group hidden">
                                        <label>ID</label>
                                        <input type="text" name="id" id="id" class="form-control disabled" value="<?php echo $list->message[0]->ID; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Dealer</label>
                                        <input type="text" name="kd_dealer" id="kd_dealer" class="form-control " value="<?php echo $list->message[0]->KD_DEALER; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>No. PKB</label>
                                        <input type="text" name="no_pkb" id="no_pkb" class="form-control" value="<?php echo $list->message[0]->NO_PKB; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Kode SA</label>
                                        <input type="text" name="kd_sa" id="kd_sa" class="form-control " value="<?php echo $list->message[0]->KD_SA; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>No. Polisi</label>
                                        <input type="text" name="no_polisi" id="no_polisi" class="form-control " value="<?php echo $list->message[0]->NO_POLISI; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>No. Rangka</label>
                                        <input type="text" name="no_rangka" id="no_rangka" class="form-control " value="<?php echo $list->message[0]->NO_RANGKA; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>No. Mesin</label>
                                        <input type="text" name="no_mesin" id="no_mesin" class="form-control " value="<?php echo $list->message[0]->NO_MESIN; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>KM Motor</label>
                                        <input type="text" name="km_motor" id="km_motor" class="form-control meter" value="<?php echo $list->message[0]->KM_MOTOR; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Nama Typemotor</label>
                                        <input type="text" name="nama_typemotor" id="nama_typemotor" class="form-control" value="<?php echo $list->message[0]->NAMA_TYPEMOTOR; ?>">
                                    </div>

                                    <!--                                    <div class="form-group">
                                                                            <label>Tgl. Lahir</label>
                                                                            <div class="input-group input-append date" id="datex">
                                                                                <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="dd/mm/yyyy" required="required" value="<?php echo $tgl_lahir; ?>" />
                                                                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        </div>-->

                                    <div class="form-group">
                                        <label>Tanggal PKB</label>
                                        <div class="input-group input-append date" id="date">
                                            <input class="form-control" id="tanggal_pkb" name="tanggal_pkb" placeholder="DD/MM/YYYY" value="<?php echo tglFromSql($list->message[0]->TANGGAL_PKB); ?>" type="text"/>
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="text" name="tahun" id="tahun" class="form-control tahun" value="<?php echo $list->message[0]->TAHUN; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Pembelian Motor</label>
                                        <input type="text" name="pembelian_motor" id="pembelian_motor" class="form-control " value="<?php echo $list->message[0]->PEMBELIAN_MOTOR; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Saran Mekanik</label>
                                        <textarea type="text" name="saran_mekanik" id="saran_mekanik" class="form-control " placeholder="Masukkan Keterangan" ><?php echo $list->message[0]->SARAN_MEKANIK; ?></textarea>
                                    </div>

                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">

                                    <div class="form-group">
                                        <label>No. Antrian</label>
                                        <input type="text" name="no_antrian" id="no_antrian" class="form-control " value="<?php echo $list->message[0]->NO_ANTRIAN; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Status PKB</label>
                                        <?php
                                        if ($list->message[0]->STATUS_APPROVAL == 0) {
                                            ?>
                                            <select name="status_pkb" id="status_pkb" class="form-control" disabled="true">
                                                <option value="0" <?php echo ($list->message[0]->STATUS_PKB == "0") ? "selected" : ""; ?>>Menunggu Approval</option>
                                                <option value="0" <?php echo ($list->message[0]->STATUS_PKB == "1") ? "selected" : ""; ?>>Diproses</option>
                                            </select>
                                            <?php
                                        } else {
                                            ?>
                                            <select name="status_pkb" id="status_pkb" class="form-control" >
                                                <option value="0" <?php echo ($list->message[0]->STATUS_PKB == "0") ? "selected" : ""; ?>>Open</option>
                                                <option value="1" <?php echo ($list->message[0]->STATUS_PKB == "1") ? "selected" : ""; ?>>Diproses</option>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Nama Mekanik</label>
                                        <select class="form-control" id="nama_mekanik" name="nama_mekanik" required>
                                            <option value="" >- Pilih Mekanik -</option>
                                            <?php
                                            if ($mekanik):
                                                foreach ($mekanik->message as $key => $value) :
                                                    ?>
                                                    <option value="<?php echo $value->NIK; ?>" <?php echo ($value->NIK == $list->message[0]->NAMA_MEKANIK ? "selected" : ""); ?>><?php echo $value->NAMA; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis Pit</label>
                                        <select class="form-control" id="jenis_pit" name="jenis_pit">
                                            <option value="" >- Pilih Jenis Pit -</option>

                                            <?php
                                            if ($pit):

                                                foreach ($pit->message as $key => $value) :
                                                    ?>
                                                    <option value="<?php echo $value->KD_JENISPIT; ?>" <?php echo ($value->KD_JENISPIT == $list->message[0]->JENIS_PIT ? "selected" : ""); ?> ><?php echo $value->NAMA_JENISPIT; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Est. Waktu Mulai</label>
                                        <div class="input-group input-append datetime-mulai" id="datetime">
                                            <input class="form-control" id="estimasi_mulai" name="estimasi_mulai" placeholder="HH:MM" value="<?php echo date('H:i', strtotime($list->message[0]->ESTIMASI_MULAI)); ?>" type="text"/>
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Est. Waktu Selesai</label>
                                        <div class="input-group input-append datetime-selesai" id="datetime">
                                            <input class="form-control" id="estimasi_selesai" name="estimasi_selesai" placeholder="HH:MM" value="<?php echo date('H:i', strtotime($list->message[0]->ESTIMASI_SELESAI)); ?>" type="text"/>
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis KPB</label>
                                        <select name="jenis_kpb" id="jenis_kpb" class="form-control" required>
                                            <option value="" <?php echo ($list->message[0]->JENIS_KPB == "") ? "selected" : ""; ?>>- Pilih Jenis KPB-</option>
                                            <option value="KPB1" <?php echo ($list->message[0]->JENIS_KPB == "KPB1") ? "selected" : ""; ?>>KPB 1</option>
                                            <option value="KPB2" <?php echo ($list->message[0]->JENIS_KPB == "KPB2") ? "selected" : ""; ?>>KPB 2</option>
                                            <option value="KPB3" <?php echo ($list->message[0]->JENIS_KPB == "KPB3") ? "selected" : ""; ?>>KPB 3</option>
                                            <option value="KPB4" <?php echo ($list->message[0]->JENIS_KPB == "KPB4") ? "selected" : ""; ?>>KPB 4</option>
                                        </select>                                
                                    </div>

                                    <div class="form-group">
                                        <label>Alasan Ke AHASS</label>
                                        <input type="text" name="alasan_ke_ahass" id="alasan_ke_ahass" class="form-control " value="<?php echo $list->message[0]->ALASAN_KE_AHASS; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Hubungan Dengan Pembawa</label>
                                        <input type="text" name="hubungan_dengan_pembawa" id="hubungan_dengan_pembawa" class="form-control " value="<?php echo $list->message[0]->HUBUNGAN_DENGAN_PEMBAWA; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Service Sebelumnya</label>
                                        <div class="input-group input-append date" id="datex">
                                            <input type="text" class="form-control" id="service_sebelumnya" name="service_sebelumnya" placeholder="dd/mm/yyyy" value="<?php echo tglFromSql($list->message[0]->SERVICE_SEBELUMNYA); ?>" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>BBM</label>
                                        <select name="bbm" id="bbm" class="form-control">
                                            <option value="10" <?php echo ($list->message[0]->BBM == "10") ? "selected" : ""; ?>>10%</option>
                                            <option value="20" <?php echo ($list->message[0]->BBM == "20") ? "selected" : ""; ?>>20%</option>
                                            <option value="30" <?php echo ($list->message[0]->BBM == "30") ? "selected" : ""; ?>>30%</option>
                                            <option value="40" <?php echo ($list->message[0]->BBM == "40") ? "selected" : ""; ?>>40%</option>
                                            <option value="50" <?php echo ($list->message[0]->BBM == "50") ? "selected" : ""; ?>>50%</option>
                                            <option value="60" <?php echo ($list->message[0]->BBM == "60") ? "selected" : ""; ?>>60%</option>
                                            <option value="70" <?php echo ($list->message[0]->BBM == "70") ? "selected" : ""; ?>>70%</option>
                                            <option value="80" <?php echo ($list->message[0]->BBM == "80") ? "selected" : ""; ?>>80%</option>
                                            <option value="90" <?php echo ($list->message[0]->BBM == "90") ? "selected" : ""; ?>>90%</option>
                                            <option value="100" <?php echo ($list->message[0]->BBM == "100") ? "selected" : ""; ?>>100%</option>
                                        </select>   
                                    </div>

                                    <div class="form-group hidden">
                                        <label>Status Approval</label>
                                        <input type="text" name="status_approval" id="status_approval" class="form-control " value="<?php echo $list->message[0]->STATUS_APPROVAL; ?>" >
                                    </div>

                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <textarea type="text" name="keterangan" id="keterangan" class="form-control " placeholder="Masukkan Keterangan" ><?php echo $list->message[0]->KETERANGAN; ?></textarea>
                                    </div>

                                    <div class="form-group hidden">
                                        <label>Final Confirmation</label>
                                        <input type="text" name="final_confirmation" id="final_confirmation" class="form-control " value="<?php echo $list->message[0]->FINAL_CONFIRMATION; ?>" >
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

    </form>
    <?php echo loading_proses(); ?>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        var date = new Date();
        date.setDate(date.getDate());

        $('.datetime-mulai').datetimepicker({
            format: 'LT',
        locale: 'ru'
        });

        $('.datetime-selesai').datetimepicker({
            format: 'LT',
        locale: 'ru'
        });

        $('#baru').click(function () {
            document.location.reload();
        })

        $('.qurency').mask('000.000.000.000.000', {reverse: true});
        $('.tahun').mask('0000', {reverse: true});
        $('.meter').mask('000.000.000.000.000', {reverse: true});

        $("#submit-btn").on('click', function (event) {
            var formId = '#' + $(this).closest('form').attr('id');
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");

            $('.qurency').unmask();
            $('.tahun').unmask();

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