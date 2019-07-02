<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$no_trans = base64_decode(urldecode($this->input->get("n")));
$kd_lokasi = ($this->input->get("kd_lokasidealer")) ? $this->input->get("kd_lokasidealer") : $this->session->userdata("kd_lokasi");

// var_dump($kd_lokasi);exit;
$tanggal = date('d/m/Y');
$kd_dealer = "";
$kd_typemotor = "";
$start_date = date('d/m/Y');
$end_date = date('d/m/Y');
$harga_otr = "";
$keterangan = "";
$status_sl = "0";

if (isset($list)) {
    if ($list->totaldata > 0) {
        foreach ($list->message as $key => $value) {
            $kd_dealer = $value->KD_DEALER;
            $kd_leasing = $value->KD_LEASING;
            $tanggal = TglFromSql($value->TGL_TRANS);
            $no_trans = $value->NO_TRANS;
            $kd_typemotor = $value->KD_TYPEMOTOR;
            $harga_otr = intval($value->HARGA_OTR);
            $start_date = TglFromSql($value->START_DATE);
            $end_date = TglFromSql($value->END_DATE);
            $keterangan = $value->KETERANGAN;
        }
    }
}

$dsb = ((int) $status_sl > 0) ? 'disabled-action' : '';
$lock = ($no_trans) ? 'disabled-action' : '';

$edit = $status_sl == 1 ? 'disabled-action' : $status_e;
$hide_data = $status_sl == 1 ? 'hide' : '';
$allow_formsa = $status_sl == 1 ? $status_v : 'disabled-action';
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <div class="btn-group">
                <a id="baru" type="button" class="btn btn-default baru ">
                    <i class="fa fa-file-o fa-fw"></i> Add LSC
                </a>
            </div>
            <div class="btn-group">
                <a id="submit-btn" type="button" class="btn btn-default submit-btn <?php echo $status_c . " " . $dsb; ?>">  
                    <i class="fa fa-save fa-fw"></i> Simpan
                </a>
            </div>
            <div class="btn-group">
                <a role="button" href="<?php echo base_url("setup/lsc_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List LSC</a>
            </div>

        </div>

    </div>

    <form class="bucket-form" id="addFormz" method="post" action="<?php echo base_url("setup/simpan_lsc"); ?>" autocomplete="off">
        <div class="col-lg-12 padding-left-right-10">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-list-ul'></i> Form Leasing Skema Credit
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
                <div class="panel-body panel-body-border" style="display: block;">


                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="col-xs-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control <?php echo $lock; ?>" id="kd_dealer" name="kd_dealer">
                                        <?php
                                        if (isset($dealer)) {
                                            if ($dealer->totaldata > 0) {
                                                foreach ($dealer->message as $key => $value) {
                                                    $kd_propinsi = ($value->KD_PROPINSI == "6300") ? "B" : "E";
//                                                    $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                                    $select = ($kd_dealer == $value->KD_DEALER) ? "selected" : "";
                                                    echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . " | " . $kd_propinsi . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Leasing</label>
                                    <select class="form-control <?php echo $lock; ?>" id="kd_leasing" name="kd_leasing" required>
                                        <option value="" disabled selected>Pilih Leasing</option>
                                        <?php
                                        if (isset($leasing)) {
                                            if ($leasing->totaldata > 0) {
                                                foreach ($leasing->message as $key => $value) {
                                                    $pilih = ($value->KD_LEASING == $kd_leasing) ? 'selected' : '';
                                                    echo "<option value='" . $value->KD_LEASING
                                                    . "' " . $pilih . "> [" . $value->KD_LEASING . "] " . $value->NAMA_LEASING . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="col-xs-5 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Tanggal Transaksi</label>
                                    <div class="input-group input-append date" id="date">
                                        <input class="form-control <?php echo $lock; ?>" id="tgl_trans" name="tgl_trans" placeholder="DD/MM/YYYY" value="<?php echo $tanggal; ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>No. Transaksi</label>
                                    <input type="text" class="form-control <?php echo $lock; ?>" id="no_trans" autocomplete="off" name="no_trans" placeholder="AUTO GENERATE" value="<?php echo $no_trans; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Tipe Motor</label>
                                    <input type="text" name="kd_typemotor" id="kd_typemotor" class="form-control <?php echo $lock; ?>" value="<?php echo $kd_typemotor; ?>" placeholder="Nama Type Motor" required>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Harga OTR <span class="detail-loading-harga"></span></label>
                                    <input type="text" name="harga_otr" id="harga_otr" class="form-control qurency text-right disabled-action" value="<?php echo $harga_otr; ?>" placeholder="Harga OTR">
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <div class="input-group input-append" id="">
                                        <input class="form-control" id="start_date" name="start_date" placeholder="DD/MM/YYYY" value="<?php echo $start_date; ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <div class="input-group input-append" id="">
                                        <input class="form-control" id="end_date" name="end_date" placeholder="DD/MM/YYYY" value="<?php echo $end_date; ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div id="form-additem" class="col-xs-12 col-sm-12 padding-left-right-10 <?php echo $hide_data; ?>">
        <div class="panel margin-bottom-10">
            <div class="panel-body panel-body-border-top">
                <input type="hidden" id="part_desc" name="part_desc" class="form-control">
                <input type="hidden" id="kategori_item" name="kategori_item" class="form-control">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label>Uang Muka <span class="hddetail-loading"/></label>
                            <input type="text" id="uang_muka" name="uang_muka" class="form-control qurency" placeholder="Masukkan Uang Muka">
                        </div> 
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label>Tenor <span class="hddetail-loading"/></label>
                            <input type="text" id="tenor" name="tenor" class="form-control" placeholder="Tenor">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label>Jumlah Angsuran <span class="hddetail-loading"/></span></label>
                            <div class="input-group">

                                <input type="text" name="jml_angsuran" id="jml_angsuran" class="form-control qurency" value="" placeholder="Jumlah Angsuran">
                                <!-- <input type="text" name="harga_sp" id="harga_sp" class="form-control qurency text-right" value="" placeholder="Harga Part" data-mask="#.##0" data-mask-reverse="true"> -->
                                <span class="input-group-btn">
                                    <button class="btn btn-primary <?php echo $status_c; ?>" onclick="__addItem();" type="button" id="btn-add-sp"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table id="lsc_list" class="table table-bordered table-hover b-t b-light">
                    <thead>
                        <tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i> List Leasing Skema Credit Detail</th></tr>
                        <tr>
                            <!-- <th style="width:40px;">No.</th> -->
                            <th style="width:50px;">Aksi</th>
                            <th class = "text-center">Uang Muka</th>
                            <th class="text-center">Tenor</th>
                            <th class="text-center">Jumlah Angsuran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($this->input->get('n')):
                            if ($list->message[0]->ID != ''):
                                foreach ($list->message as $key => $list_row):
                                    ?>
                                    <tr class = "list-lsc">
                                        <td class='text-center'>
                                            <a id="<?php echo $list_row->ID; ?>" class='hapus2-item <?php echo $edit; ?>' role='button'><i class='fa fa-trash'></i></a>
                                        </td>
                                        <td class='text-right qurency'><?php echo number_format($list_row->UANG_MUKA); ?></td>
                                        <td class='text-right qurency'><?php echo number_format($list_row->TENOR); ?></td>
                                        <td class='text-right qurency'><?php echo number_format($list_row->JML_ANGSURAN); ?></td>
                                        <td class='hidden'><?php echo $list_row->ID; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php echo loading_proses(); ?>
</section>

<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];

    $(document).ready(function () {
        var date = new Date();
        date.setDate(date.getDate());

        $('#baru').click(function () {
            document.location.href = "<?php echo base_url('setup/add_lsc'); ?>"
        });

        $('#start_date,#end_date').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true
        });

        $('.qurency').mask('000.000.000.000.000', {reverse: true});

        $("#submit-btn").on('click', function (event) {
            var formId = '#addFormz';

            $(formId).valid();

            if (jQuery(formId).valid()) {
                // Do something

                var lscDetail = $(".list-lsc").length;

                if (lscDetail == 0) {
                    $('.error').animate({top: "0"}, 500);
                    $('.error').html("Data list detail leasing skema credit kosong !").fadeIn();
                    setTimeout(function () {
                        hideAllMessages();
                    }, 3000);
                } else {
                    var btnId = '#' + this.id;
                    $('#loadpage').removeClass("hidden");
                    $('.qurency').unmask();

                    event.preventDefault();

                    storeData(formId, btnId);
                }

            } else {
                $('#loadpage').addClass("hidden");
            }
        });

        __getMotor();


        $('#lsc_list').on('click', '.hapus-item', function () {
            $(this).parents('tr').remove();
        });

        $('.hapus2-item').click(function () {
            var detailId = this.id;
            if (detailId != '')
            {
                $.getJSON(http + '/setup/delete_lsc_detail', {id: detailId}, function (data, status) {
                    if (data.status == true) {
                        $("#" + detailId).parents('tr').remove();
                    }
                });
            }
        });
    });

    $('#kd_dealer').change(function () {
        $("#harga_otr").val("");
        __getMotor();
    });

    function __getMotor()
    {
        var url = http + "/setup/list_motor";
        var kd_typemotor = $("#kd_typemotor").val();
        var kd_dealer = $("#kd_dealer option:selected").text();
        var kd_propinsi = kd_dealer[kd_dealer.length - 1];


        $('#kd_typemotor').inputpicker({
            url: url,
            urlParam: {"kd_typemotor": kd_typemotor, "kd_wilayah": kd_propinsi},
//            fields: ['KD_ITEM', 'NAMA_ITEM', 'KET_WARNA'],
            fields: ['KD_TYPEMOTOR', 'NAMA_PASAR'],
            fieldText: 'NAMA_PASAR',
            fieldValue: 'KD_TYPEMOTOR',
            filterOpen: true,
            headShow: true,
            pagination: true,
            pageMode: '',
            pageField: 'p',
            pageLimitField: 'per_page',
            limit: 15,
            pageCurrent: 1,
            urlDelay: 2
        })
                .on("change", function () {
//                    alert($(this).val());
                    __getHargaOTR(url, kd_propinsi);
                });
    }

    function __getHargaOTR(url, kd_propinsi) {
        var kd_typemotor = $("#kd_typemotor").val();

        $(".detail-loading-harga").html("<i class='fa fa-spinner fa-spin'></i>");

        $.getJSON(url, {"kd_typemotor": kd_typemotor, "kd_wilayah": kd_propinsi}, function (result) {
            var harga_otr = 0;

            harga_otr = result.data[0].HARGA_OTR;

            $('#harga_otr').attr('min', parseFloat(harga_otr));
            $('#harga_otr').val(parseFloat(harga_otr));

            $(".detail-loading-harga").html("");
        });
    }

    function __addItem()
    {
        var uang_muka = $("#uang_muka").val();
        var tenor = $("#tenor").val();
        var jml_angsuran = $("#jml_angsuran").val();

        if ((uang_muka == "" || uang_muka == "0") || (tenor == "" || tenor == "0") || (jml_angsuran == "" || jml_angsuran == "0")) {
            $('.error').animate({top: "0"}, 500);
            $('.error').html("data tidak boleh kosong atau 0").fadeIn();
            setTimeout(function () {
                hideAllMessages();
            }, 2000);
        } else {
            var html = "";

            html += "<tr class = 'list-lsc'>";
            html += "<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>";
            html += "<td class='text-right qurency'>" + $('#uang_muka').val() + "</td>";
            html += "<td class='text-right qurency'>" + $('#tenor').val() + "</td>";
            html += "<td class='text-right qurency'>" + $('#jml_angsuran').val() + "</td>";
            html += "</tr>";

            $('#lsc_list > tbody').append(html);

            $("#uang_muka").val('');
            $("#tenor").val('');
            $("#jml_angsuran").val('');

        }
    }

    function __data()
    {
        var dataxx = [];

        $('#lsc_list .list-lsc').each(function ()
        {
            dataxx.push({
                'uang_muka': $(this).find("td").eq(1).html(),
                'tenor': $(this).find("td").eq(2).html(),
                'jml_angsuran': $(this).find("td").eq(3).html()
            });

//            $(".qurency").mask('#.##0', {reverse: true});
        });

        return dataxx;
    }

    function storeData(formId, btnId) {
        // alert(formId);
        var data_form = __data();
        var defaultBtn = $(btnId).html();

        $(btnId).addClass("disabled");
        $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();

        $(formId + " select").removeAttr("disabled");
        $(formId + " select").removeClass("disabled-action");
        var formData = $(formId).serialize();
        var act = $(formId).attr('action');

//        console.log(formData);
//        console.log(data_form);
//        console.log(act);

        $.ajax({
            url: act,
            type: 'POST',
            data: formData + "&detail=" + JSON.stringify(data_form),
            dataType: "json",
            success: function (result) {

//                console.log("Result Status : "+ result.status + " Result Location : "+result.location);
//                alert("Result Status : "+ result.status + " Result Location : "+result.location);

                if (result.status == true) {

                    $('.success').animate({
                        top: "0"
                    }, 500);
                    $('.success').html(result.message);


                    if (result.location != null) {
                        setTimeout(function () {
                            location.replace(result.location);
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