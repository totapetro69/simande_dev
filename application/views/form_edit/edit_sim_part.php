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

    <form id="addFormx" action="<?php echo base_url('part/update_sim_part'); ?>" method="post">

        <div class="breadcrumb margin-bottom-10">
            <?php echo breadcrumb(); ?>

            <div class="bar-nav pull-right">
                
                <a id="submit-btn" type="submit" class="btn btn-default submit-btn $status_e" >
                    <i class="fa fa-save fa-fw"></i> Update Sim Part
                </a>

                <a href="<?php echo base_url('part/sim_part'); ?>" class="btn btn-default $status_v">
                    <i class="fa fa-list"></i> List Sim Part
                </a>

            </div>

            <div class="col-xs-12 padding-left-right-10">

                <div class="row">

                    <div class="col-sm-12">

                        <div class="panel margin-bottom-10">

                            <div class="panel-heading panel-custom">

                                <div class="row">

                                    <div class="col-sm-5">
                                        <h4 class="panel-title pull-left" style="padding-top: 10px;">
                                            <i class="fa fa-list fa-fw"></i> Data Sim Part
                                        </h4>
                                    </div>

                                </div>

                            </div>

                            <div class="panel-body panel-body-border">

                                <div class="row">

                                    <div class="col-xs-6 col-sm-6 col-md-6">

                                        <div class="form-group">
                                            <label>Part Number</label>
                                            <input type="text" name="part_number" id="part_number" class="form-control disabled" value="<?php echo  $list->message[0]->PART_NUMBER; ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Kategori AHASS</label>
                                            <input type="text" name="kategori_ahass" id="kategori_ahass" class="form-control" value="<?php echo  $list->message[0]->KATEGORI_AHASS; ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Jumlah Minimal Standar Item</label>
                                            <input type="text" name="jumlah_standaritem_min" id="het" class="form-control" value="<?php echo number_format($list->message[0]->JUMLAH_STANDARITEM_MIN,0); ?>">
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
        var urls = "<?php echo base_url(); ?>customer/" + param;
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