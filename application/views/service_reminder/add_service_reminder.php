<?php

$ID = "";
$NO_TRANS = "";
$TGL_REMINDER = "";
$KD_DEALER = "";
$KD_CUSTOMER = "";
$NAMA_CUSTOMER = "";
$KD_TYPEMOTOR = "";
$NO_POLISI = "";
$NO_HP = "";
$NO_MESIN = "";
$TGL_LASTSERVICE = "";
$TYPE_LASTSERVICE = "";
$TGL_NEXTSERVICE = "";
$TYPE_NEXTSERVICE = "";
$STATUS_SMS = "";
$STATUS_CALL = "";
$BOOKING_STATUS = "";
$ALASAN = "";
$RESCHEDULE = "";

$JENIS_KPB = "";

if (!empty($list) && (is_array($list) || is_object($list))) {
    foreach ($list->message as $key => $value) {
        $ID  = $value->ID;
        $TGL_REMINDER  = tglfromSql($value->TGL_REMINDER);
        $KD_CUSTOMER  = $value->KD_CUSTOMER;
        $NO_TRANS  = $value->NO_TRANS;
        //$NO_MESIN  = $value->NO_MESIN;
        $NAMA_CUSTOMER  = $value->NAMA_CUSTOMER;
        $NO_HP  = $value->NO_HP;
        $NO_POLISI  = $value->NO_POLISI;
        $KD_TYPEMOTOR  = $value->KD_TYPEMOTOR;
        $TGL_LASTSERVICE  = tglToSql($value->TGL_LASTSERVICE);
        $JENIS_KPB  = $value->JENIS_KPB;
        $TGL_NEXTSERVICE  = tglfromSql($value->TGL_NEXTSERVICE);
        $TYPE_NEXTSERVICE  = $value->TYPE_NEXTSERVICE;
        $STATUS_SMS  = $value->STATUS_SMS;
        $STATUS_CALL  = $value->STATUS_CALL;
        $BOOKING_STATUS  = $value->BOOKING_STATUS;
        $ALASAN = $value->ALASAN;
        $RESCHEDULE = tglfromSql($value->RESCHEDULE);
    }
}

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Service Reminder</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('service_reminder/add_service_reminder_simpan'); ?>">

        <div class="form-group">
            <label>Kode Dealer</label>
            <input id="kd_dealer" type="text" name="kd_dealer" class="form-control" value="<?php echo $defaultDealer = $this->session->userdata("kd_dealer"); ?>" placeholder="Kode No Trans" readonly>
        </div>

        <div class="form-group">
            <?php if ($NO_TRANS == '') :; ?>
                <label>Nama Customer <span class="loading-fu"></span></label>
                <input id="no_rangka_service" type="text" name="no_rangka" class="form-control" value="" placeholder="Masukan data customer" required>
                <input id="nama_customer" type="hidden" name="nama_customer" class="form-control" value="<?php echo $NAMA_CUSTOMER; ?>" required>
            <?php else :; ?>
                <label>Nama Customer</label>
                <input id="nama_customer" type="text" name="nama_customer" class="form-control" value="<?php echo $NAMA_CUSTOMER; ?>" required>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Kode Customer</label>
            <input id="kd_customer" type="text" name="kd_customer" class="form-control" required>
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input id="no_hp" type="text" name="no_hp" class="form-control" required>
        </div>

        <div class="form-group">
            <label>No Polisi</label>
            <input id="no_polisi" type="text" name="no_polisi" class="form-control">
        </div>

        <div class="form-group">
            <label>Tipe Unit</label>
            <input id="kd_typemotor" type="text" name="kd_typemotor" class="form-control">
        </div>

        <div class="form-group">
            <label>No Mesin</label>
            <input id="no_mesin" type="text" name="no_mesin" class="form-control">
        </div>

        <div class="form-group">
            <label>Tgl Service Sebelumnya</label>
            <div class="input-group">
                <input id="tgl_lastservice" type="text" name="tgl_lastservice" class="form-control">
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Type Service KPB Sebelumnya </label>
            <select id="type_lastservice" name="type_lastservice" class="form-control">
                <option value="" <?php echo ($TYPE_LASTSERVICE == '' ? "selected" : ""); ?>>---</option>
                <option value="NONKPB" <?php echo ($TYPE_LASTSERVICE == 'NONKPB' ? "selected" : ""); ?>>NONKPB</option>
                <option value="KPB1" <?php echo ($TYPE_LASTSERVICE == 'KPB1' ? "selected" : ""); ?>>KPB1</option>
                <option value="KPB2" <?php echo ($TYPE_LASTSERVICE == 'KPB2' ? "selected" : ""); ?>>KPB2</option>
                <option value="KPB3" <?php echo ($TYPE_LASTSERVICE == 'KPB3' ? "selected" : ""); ?>>KPB3</option>
                <option value="KPB4" <?php echo ($TYPE_LASTSERVICE == 'KPB4' ? "selected" : ""); ?>>KPB4</option>
            </select>
        </div>

        <div class="form-group">
            <label>Tgl Service Berikutnya</label>
            <div class="input-group input-append date">
                <input id="tgl_nextservice" type="text" name="tgl_nextservice" class="form-control" required>
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Type Service KPB Berikutnya </label>
            <select id="type_nextservice" name="type_nextservice" class="form-control">
                <option value="" <?php echo ($TYPE_NEXTSERVICE == '' ? "selected" : ""); ?>>---</option>
                <option value="NONKPB" <?php echo ($TYPE_NEXTSERVICE == 'NONKPB' ? "selected" : ""); ?>>NONKPB</option>
                <option value="KPB1" <?php echo ($TYPE_NEXTSERVICE == 'KPB1' ? "selected" : ""); ?>>KPB1</option>
                <option value="KPB2" <?php echo ($TYPE_NEXTSERVICE == 'KPB2' ? "selected" : ""); ?>>KPB2</option>
                <option value="KPB3" <?php echo ($TYPE_NEXTSERVICE == 'KPB3' ? "selected" : ""); ?>>KPB3</option>
                <option value="KPB4" <?php echo ($TYPE_NEXTSERVICE == 'KPB4' ? "selected" : ""); ?>>KPB4</option>
            </select>
        </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];

    $(function() {
        $('#tgl_lastservice').datepicker({
            format: 'dd/mm/yyyy'
        });
        $('#tgl_nextservice').datepicker();
    });

    $(document).ready(function() {

        getData();

    });

    function getData() {

        var url_fu_service = http + "/follow_up/get_rangka_bykpbreminder";

        $('#no_rangka_service').inputpicker({
                url: url_fu_service,
                fields: ['NAMA_CUSTOMER', 'KD_CUSTOMER', 'NO_HP', 'NO_POLISI', 'KD_TYPEMOTOR', 'NO_MESIN', 'NO_RANGKA', 'JENIS_KPB', 'SERVICE_TERAKHIR'],
                fieldText: 'NAMA_CUSTOMER',
                fieldValue: 'NO_RANGKA',
                filterOpen: true,
                headShow: true,
                pagination: true,
                pageMode: '',
                pageField: 'p',
                pageLimitField: 'per_page',
                limit: 30,
                pageCurrent: 1,
            })
            .on("change", function(e) {
                e.preventDefault();

                var no_rangka = $(this).val();

                $.getJSON(http + "/follow_up/get_detail_fureminder", {
                    "no_rangka": no_rangka
                }, function(result) {

                    var tglpkb = new Date(result.sj.message[0].TANGGAL_PKB);
                    year = tglpkb.getFullYear(),
                        month = (tglpkb.getMonth() + 1) < 10 ? '0' + (tglpkb.getMonth() + 1) : (tglpkb.getMonth() + 1),
                        day = tglpkb.getDate() < 10 ? '0' + tglpkb.getDate() : tglpkb.getDate(),
                        last_srv = day + '/' + month + '/' + year;


                    if (result.kpb[0].JENIS_KPB == 'KPB1') {
                        var kpb = 'KPB1';
                    } else if (result.kpb[0].JENIS_KPB == 'KPB2') {
                        var kpb = 'KPB2';
                    } else if (result.kpb[0].JENIS_KPB == 'KPB3') {
                        var kpb = 'KPB3';
                    } else if (result.kpb[0].JENIS_KPB == 'KPB4') {
                        var kpb = 'KPB4';
                    } else {
                        var kpb = 'NONKPB';
                    }

                    $('#kd_customer').val(result.sj.message[0].KD_CUSTOMER);
                    $('#nama_customer').val(result.sj.message[0].NAMA_CUSTOMER);
                    $('#no_hp').val(result.sj.message[0].NO_HP);
                    $('#no_polisi').val(result.sj.message[0].DATA_NOMOR);
                    $('#kd_typemotor').val(result.sj.message[0].KET_UNIT);
                    $('#type_lastservice').val(kpb);
                    $('#tgl_lastservice').val(last_srv);
                    $('#no_mesin').val(result.sj.message[0].NO_MESIN);
                    // $('#kelurahan').val(result.sj.message[0].NAMA_DESA);
                    // $('#kecamatan').val(result.sj.message[0].NAMA_KECAMATAN);
                    // $('#kota').val(result.sj.message[0].NAMA_KABUPATEN);
                    // $('#kode_pos').val(result.sj.message[0].KODE_POS);
                    // $('#propinsi').val(result.sj.message[0].NAMA_PROPINSI);
                    // $('#tgl_pembelian').val(newDate);
                    // $('#nama_stnk').val(result.sj.message[0].NAMA_CUSTOMER);
                    // $('#alamat_surat').val(result.sj.message[0].ALAMAT_SURAT);
                    $('#jenis_kpb_title').html(result.kpb[0].JENIS_KPB);

                });

            })

    }
</script>