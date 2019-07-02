<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
 
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
 
if ($list) {
    if ($list->totaldata > 0) {
        foreach ($list->message as $key => $value) {
            $kd_groupsales = $value->KD_GROUPSALES;
            $part_status = $value->PART_STATUS;
            $part_source = $value->PART_SOURCE;
            $part_moving = $value->PART_MOVING;
            $part_numbertype = $value->PART_NUMBERTYPE;
            $part_rank = $value->PART_RANK;
            $part_current = $value->PART_CURRENT;
            $part_type = $value->PART_TYPE;
            $part_lifetime = $value->PART_LIFETIME;
            $part_group = $value->PART_GROUP;
            $row_status = $value->ROW_STATUS;
        }
    }
}
?>
 
<section class="wrapper">
 
    <form id="addFormx" action="<?php echo base_url('part/update_part'); ?>" method="post">
 
        <div class="breadcrumb margin-bottom-10">
 
            <?php echo breadcrumb(); ?>
 
            <div class="bar-nav pull-right">
 
                <a id="submit-btn" type="submit" class="btn btn-default submit-btn $status_e" >
                    <i class="fa fa-save fa-fw"></i> Update Part
                </a>
 
                <a href="<?php echo base_url('part/part'); ?>" class="btn btn-default $status_v">
                    <i class="fa fa-list"></i> List Part
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
 
                                    <div class="form-group">
                                        <label>Part Number</label>
                                        <input type="text" name="part_number" id="part_number" class="form-control disabled" value="<?php echo $list->message[0]->PART_NUMBER; ?>" readonly>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Deskripsi Part</label>
                                        <input type="text" name="part_deskripsi" id="part_deskripsi" class="form-control" value="<?php echo $list->message[0]->PART_DESKRIPSI; ?>" readonly>
                                    </div>
 
                                    <div class="form-group">
                                        <label>HET</label>
                                        <input type="text" name="het" id="het" class="form-control qurency" value="<?php echo number_format($list->message[0]->HET); ?>" readonly>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Harga Pokok</label>
                                        <input type="text" name="harga_beli" id="harga_beli" class="form-control qurency" value="<?php echo number_format($list->message[0]->HARGA_BELI); ?>">
                                    </div>
 
                                    <div class="form-group">
                                        <label>Kode Supplier</label>
                                        <input type="text" name="kd_supplier" id="kd_supplier" class="form-control" value="<?php echo $list->message[0]->KD_SUPPLIER; ?>" readonly>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Kode Group Sales</label>
                                        <select class="form-control" id="kd_groupsales" name="kd_groupsales" >
                                            <option value="" >-- Pilih Kode Group Sales --</option>
                                            <?php
                                            if ($group):
                                                foreach ($group->message as $key => $value) :
                                                    $select = ($kd_groupsales == $value->KD_GROUPSALES) ? "selected" : "";
                                                    ?>
                                                    <option value="<?php echo $value->KD_GROUPSALES; ?>" <?php echo $select; ?> ><?php echo $value->KD_GROUPSALES; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Reference</label>
                                        <input type="text" name="part_reference" id="part_reference" class="form-control" value="<?php echo $list->message[0]->PART_REFERENCE; ?>">
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Status</label>
                                        <select name="part_status" id="part_status" class="form-control">
                                            <option value="" <?php echo ($part_status == "") ? "selected" : ""; ?>>-- Pilih Part Status --</option>
                                            <option value="A" <?php echo ($part_status == "A") ? "selected" : ""; ?>>Aktif</option>
                                            <option value="D" <?php echo ($part_status == "D") ? "selected" : ""; ?>>Tidak Aktif</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Superseed</label>
                                        <input type="text" name="part_superseed" id="part_superseed" class="form-control" value="<?php echo $list->message[0]->PART_SUPERSEED; ?>">
                                    </div>
 
                                    <div class="form-group">
                                        <label>MO Dealer Kecil</label>
                                        <input type="text" name="moq_dk" id="moq_dk" class="form-control" value="<?php echo $list->message[0]->MOQ_DK; ?>">
                                    </div>
 
                                </div>
 
                                <div class="col-xs-6 col-sm-6 col-md-6">
 
                                    <div class="form-group">
                                        <label>MO Dealer Menengah</label>
                                        <input type="text" name="moq_dm" id="moq_dm" class="form-control" value="<?php echo $list->message[0]->MOQ_DM; ?>">
                                    </div>
 
                                    <div class="form-group">
                                        <label>MO Dealer Besar</label>
                                        <input type="text" name="moq_db" id="moq_db" class="form-control" value="<?php echo $list->message[0]->MOQ_DB; ?>">
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Number Type</label>
                                        <select name="part_numbertype" id="part_numbertype" class="form-control">
                                            <option value="" <?php echo ($part_numbertype == "") ? "selected" : ""; ?>>-- Pilih PNT --</option>
                                            <option value="A" <?php echo ($part_numbertype == "A") ? "selected" : ""; ?>>A</option>
                                            <option value="B" <?php echo ($part_numbertype == "B") ? "selected" : ""; ?>>B</option>
                                            <option value="C" <?php echo ($part_numbertype == "C") ? "selected" : ""; ?>>C</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Moving</label>
                                        <select name="part_moving" id="part_moving" class="form-control">
                                            <option value="" <?php echo ($part_moving == "") ? "selected" : ""; ?>>-- Pilih Part Moving --</option>
                                            <option value="F" <?php echo ($part_moving == "F") ? "selected" : ""; ?>>(F) Fast</option>
                                            <option value="S" <?php echo ($part_moving == "S") ? "selected" : ""; ?>>(S) Slow</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Source</label>
                                        <select name="part_source" id="part_source" class="form-control">
                                            <option value="" <?php echo ($part_source == "") ? "selected" : ""; ?>>-- Pilih Part Source --</option>
                                            <option value="N" <?php echo ($part_source == "N") ? "selected" : ""; ?>>(N) Lokal</option>
                                            <option value="Y" <?php echo ($part_source == "Y") ? "selected" : ""; ?>>(Y) Import</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Rank</label>
                                        <select name="part_rank" id="part_rank" class="form-control">
                                            <option value="" <?php echo ($part_rank == "") ? "selected" : ""; ?>>-- Pilih Part Rank --</option>
                                            <option value="A" <?php echo ($part_rank == "A") ? "selected" : ""; ?>>A</option>
                                            <option value="B" <?php echo ($part_rank == "B") ? "selected" : ""; ?>>B</option>
                                            <option value="C" <?php echo ($part_rank == "C") ? "selected" : ""; ?>>C</option>
                                            <option value="D" <?php echo ($part_rank == "D") ? "selected" : ""; ?>>D</option>
                                            <option value="E" <?php echo ($part_rank == "E") ? "selected" : ""; ?>>E</option>
                                            <option value="F" <?php echo ($part_rank == "F") ? "selected" : ""; ?>>F</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Current</label>
                                        <select name="part_current" id="part_current" class="form-control">
                                            <option value="" <?php echo ($part_current == "") ? "selected" : ""; ?>>-- Pilih Part Current --</option>
                                            <option value="C" <?php echo ($part_current == "C") ? "selected" : ""; ?>>(C) Current Parts</option>
                                            <option value="N" <?php echo ($part_current == "N") ? "selected" : ""; ?>>(N) Non Current Parts</option>
                                            <option value="O" <?php echo ($part_current == "O") ? "selected" : ""; ?>>(O) Others</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Type</label>
                                        <select name="part_type" id="part_type" class="form-control">
                                            <option value="" <?php echo ($part_type == "") ? "selected" : ""; ?>>-- Pilih Part Type --</option>
                                            <option value="I" <?php echo ($part_type == "I") ? "selected" : ""; ?>>(I) Very Important Parts</option>
                                            <option value="S" <?php echo ($part_type == "S") ? "selected" : ""; ?>>(S) Safety Parts</option>
                                            <option value="A" <?php echo ($part_type == "A") ? "selected" : ""; ?>>(A) Additional Parts</option>
                                            <option value="O" <?php echo ($part_type == "O") ? "selected" : ""; ?>>(O) Others</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Lifetime</label>
                                        <select name="part_lifetime" id="part_lifetime" class="form-control">
                                            <option value="" <?php echo ($part_lifetime == "") ? "selected" : ""; ?>>-- Pilih Part Lifetime --</option>
                                            <option value="L" <?php echo ($part_lifetime == "L") ? "selected" : ""; ?>>(L) Long Life Time Parts</option>
                                            <option value="S" <?php echo ($part_lifetime == "S") ? "selected" : ""; ?>>(S) Short Life Time Parts</option>
                                            <option value="O" <?php echo ($part_lifetime == "O") ? "selected" : ""; ?>>(O) Others</option>
                                        </select>
                                    </div>
 
                                    <div class="form-group">
                                        <label>Part Group</label>
                                        <select name="part_group" id="part_group" class="form-control">
                                            <option value="" <?php echo ($part_group == "") ? "selected" : ""; ?>>-- Pilih Part Group --</option>
                                            <option value="E " <?php echo ($part_group == "E ") ? "selected" : ""; ?>>(E) Engine Parts</option>
                                            <option value="EL" <?php echo ($part_group == "EL") ? "selected" : ""; ?>>(EL) Electrical Parts</option>
                                            <option value="F " <?php echo ($part_group == "F ") ? "selected" : ""; ?>>(F) Frame Parts</option>
                                            <option value="O " <?php echo ($part_group == "O ") ? "selected" : ""; ?>>(O) Others</option>
                                        </select 
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
        $('.qurency').mask('000.000.000.000.000', {reverse: true});
 
        $('#baru').click(function () {
            document.location.reload();
        })
 
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
 
    function loadData(id, value, select) {
 
        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>part/" + param;
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