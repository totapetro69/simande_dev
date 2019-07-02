<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : ($this->session->userdata("kd_dealer"));
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$tgl_awal = ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month'));
$tgl_akhir = ($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y');
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('setup/add_lsc'); ?>' >
                    <i class="fa fa-file-o fa-fw"></i> Add LSC
                </a>
            </div>

            <div class="btn-group">
                <a class="btn btn-success" role="button" data-toggle="modal" data-target="#modal-upload-lsc">
                    <i class="fa fa-file-excel-o fa-fw"></i> Upload File Excel
                </a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> LIST LEASING SKEMA KREDIT
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span> 
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="lscForm" action="<?php echo base_url('setup/lsc_list') ?>" class="bucket-form">

                    <div class="row">

                        <div class="col-xs-12 col-sm-4 col-md-4">

                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                                    <option value="">- Pilih Dealer -</option>
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata > 0) {
                                            foreach ($dealer->message as $key => $value) {
                                                $kd_propinsi = ($value->KD_PROPINSI == "6300") ? "B" : "E";
                                                $default = ($defaultDealer == $value->KD_DEALER) ? " selected" : "";
                                                ?>
                                                <option value="<?php echo $value->KD_DEALER; ?>" <?php echo $default; ?> ><?php echo $value->NAMA_DEALER . " | " . $kd_propinsi; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $tgl_awal; ?>" type="text" autocomplete="off"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-2 col-md-2">

                            <div class="form-group">

                                <label class="control-label" for="date">Tanggal Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $tgl_akhir; ?>" type="text" autocomplete="off"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                                </div>

                            </div>

                        </div>

                        <!-- </div>
    
                        <div class="row"> -->

                        <div class="col-xs-12 col-sm-3 col-md-3">

                            <div class="form-group">
                                <label>Tipe Motor</label>
                                <input type="text" name="kd_typemotor" id="kd_typemotor" class="form-control" placeholder="Masukkan Type Motor">
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-1 col-md-1">

                            <div class="form-group">

                                <label> </label>
                                <button id="submit-btn" onclick="" class="btn btn-primary" style="width:100%" >Preview</button>

                            </div>
                        </div> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive h350">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>&nbsp;</th>
                            <th>Tanggal</th>
                            <th>Leasing</th>
                            <th>Tipe Motor</th>
                            <th>Harga OTR</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if (isset($list)) {
                            if ($list->totaldata > 0) {
                                foreach ($list->message as $key => $value) {
                                    $no++;
                                    ?>

                                    <tr>
                                        <td class="table-nowarp text-center"><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button"  href="<?php echo base_url('setup/add_lsc?n=' . urlencode(base64_encode($value->NO_TRANS))); ?>" role="button" class="<?php echo $status_v ?>" >
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>

                                            <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('setup/delete_lsc/' . $value->NO_TRANS); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text <?php echo $status_e; ?>"></i>
                                            </a>

                                            <a href="<?php echo base_url('setup/cetak_lsc?kd_dealer=' . $value->KD_DEALER . '&kd_typemotor=' . $value->KD_TYPEMOTOR.'&n='.urlencode(base64_encode($value->NO_TRANS))); ?>" role="button" class="<?php echo $status_v ?>" target="_blank">
                                                <i data-toggle="tooltip" data-placement="left" title="Cetak LSC" class="fa fa-print text-success text-active"></i>
                                            </a>
                                        </td>
                                        <td class="table-nowarp" title="<?php echo $value->NO_TRANS; ?>"><?php echo tglfromSql($value->TGL_TRANS); ?></td>
                                        <td class="table-nowarp td-overflow-50" title="<?php echo $value->KD_LEASING; ?>"><?php echo $value->NAMA_LEASING; ?></td>
                                        <td class="table-nowarp td-overflow-50" title="<?php echo $value->KD_TYPEMOTOR; ?>"><?php echo $value->NAMA_PASAR; ?></td>
                                        <td class="table-nowarp td-overflow-50" ><?php echo number_format($value->HARGA_OTR, 0); ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($value->START_DATE); ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($value->END_DATE); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                belumAdaData();
                            }
                        } else {
                            belumAdaData();
                        }
                        ?>
                    </tbody>

                </table>

            </div>

        </div>

        <div class="panel-footer">

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

        </div>

    </div>
    <?php echo loading_proses(); ?>
</section>

<div class="modal fade" id="modal-upload-lsc">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload LSC</h4>
            </div>
            <form action="#" id="upload-form" class="bucket-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="custom-file col-xs-12">Select File .xls/.xlsx/.csv</label>
                        <div class="input-group-append">
                            <input accept=".xlsx,.xls,.csv" type="file" id="file" class="custom-file-control form-control" name="file" placeholder="Choose file" required>
                            <span class="custom-file-control" data-attr="Choose file..."></span>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href ="<?php echo base_url('setup/downloadTemplateLSC')?>" class ="btn btn-primary pull-left">Download Template</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id = "btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];

    $(document).ready(function () {
        __getMotor();

        $('#modal-upload-lsc').on('hidden.bs.modal', function () {
            $("#file").val("");
        });

        $('#btnSubmit').on('click', function () {
            var file = $("#file").val();

            if (file == "") {
                showError("Pilih file yang ingin di upload !", 3000);
            } else {
                var fileExt = file.split('.').pop();
                if (fileExt == "xls" || fileExt == "xlsx" || fileExt == "csv") {
                    var $btn = $(this).find('button[type="submit"]');
                    var formdata = new FormData($('#upload-form')[0]);
                    var url_gm = '<?php echo base_url("setup/importExcelLSC"); ?>';
                    $.ajax({
                        url: url_gm,
                        type: 'POST',
                        dataType: 'JSON',
                        data: formdata,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#btnSubmit').html("<i class='fa fa-spinner fa-spin'></i> Loading");
                        },
                        success: function (response) {
                            if (response.status == false) {
                                var jumlahError = response.error.length;
                                var textError = "Error upload file excel : <br/>";
                                for (i = 0; i < jumlahError; i++) {
                                    textError += response.error[i];
                                }
                                showError(textError, 0);
                            } else {
                                $('.success').animate({top: "0"}, 500);
                                $('.success').html("Import data dari excel berhasil").fadeIn();
                                setTimeout(function () {
                                    hideAllMessages();
                                }, 3000);
                                window.location.reload(true);
                            }
                        },
                        error: function (jqXHR, textStatus, error) {
                            console.log(jqXHR + 'Unable to send request!');
                            window.location.reload(true);
                        }
                    }).always(function () {
                        $('#modal-upload-lsc').modal('hide');
                    });
                } else {
                    showError("Ekstensi file tidak valid !", 3000);
                }
            }
        });

        function showError(isiError, durasi) {
            $('.error').animate({top: "0"}, 500);
            $('.error').html(isiError).fadeIn();
            setTimeout(function () {
                hideAllMessages();
            }, durasi);
        }
    });

    function __getMotor()
    {
        var url = http + "/setup/list_motor";
        var kd_typemotor = $("#kd_typemotor").val();
        var kd_dealer = $("#kd_dealer option:selected").text();
        var kd_propinsi = kd_dealer[kd_dealer.length - 1];
        $('#kd_typemotor').inputpicker({
            url: url,
            urlParam: {"kd_item": kd_typemotor, "kd_wilayah": kd_propinsi},
            fields: ['KD_TYPEMOTOR', 'NAMA_PASAR', 'KD_WILAYAH'],
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
        });
    }
</script>