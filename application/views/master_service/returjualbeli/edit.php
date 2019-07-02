<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $list->message[0]->KD_DEALER;
$defaultMainDealer = $list->message[0]->KD_MAINDEALER;
$status_retur = $list->message[0]->STATUS_RETUR;

if ($list) {
    if (is_array($list->message)) {
        foreach ($list->message as $key => $value) {
            $kd_lokasidealer = $value->KD_LOKASIDEALER;
        }
    }
}
?>

<section class="wrapper">

    <form id="addFormx" action="<?php echo base_url('retur/jualbeli_update'); ?>" method="post">
        <input type="hidden" name="id" id="id" class="form-control" value="<?php echo $list->message[0]->ID; ?>" readonly aria-describedby="addon">

        <div class="breadcrumb margin-bottom-10">

            <?php echo breadcrumb(); ?>

            <div class="bar-nav pull-right">

                <div class="btn-group">
                    <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('retur/add_jualbeli'); ?>' >
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

                <div class="col-sm-12">

                    <div class="panel margin-bottom-10">

                        <div class="panel-heading panel-custom">

                            <div class="row">

                                <div class="col-sm-12">
                                    <h4 class="panel-title pull-left" style="padding-top: 10px;">
                                        <i class="fa fa-file fa-fw"></i>Edit Data Retur Jual Beli 
                                    </h4>
                                </div>

                                
                            </div>

                        </div>


                        <div class="panel-body panel-body-border">

                            <div class="row">

                                <div class="col-sm-4">

                                    <div class="form-group">
                                        <label>Kode Dealer</label>
                                        <input type="text" name="kd_dealer" id="kd_dealer" class="form-control disabled" value="<?php echo $list->message[0]->KD_DEALER; ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-4">
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
                                </div>


                                <div class="col-sm-4">
                                    <div class="form-group">
                                    	<label>No. Trans</label>
                                    	<input type="text" name="no_trans" id="NO_TRANS" class="form-control disabled" value="<?php echo $list->message[0]->NO_TRANS; ?>" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                            	<div class="col-sm-3">
                            		<div class="form-group">
                            			<label>Tanggal Trans</label>
                            			<div class="input-group input-append date" id="datex">
                            				<input type="text" class="form-control" id="tgl_trans" name="tgl_trans" placeholder="dd/mm/yyyy" required="required" value="<?php echo tglFromSql($list->message[0]->TGL_TRANS); ?>">
                            				<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            			</div>
                            		</div>
                            	</div>

                            	<div class="col-sm-3">
                            		<div class="form-group">
                                        <label>Jenis Retur</label>
                                        <input type="text" name="jenis_retur" id="jenis_retur" class="form-control disabled" value="<?php echo $list->message[0]->JENIS_RETUR; ?>" readonly>
                                    </div>
                            	</div>

                            	<div class="col-sm-3">
                                	<div class="form-group">
                                		<label>Doc. Reff</label>
                                		<input type="text" name="no_reff" id="no_reff" class="form-control disabled" value="<?php echo $list->message[0]->NO_REFF; ?>" readonly>
                                	</div>
                                </div>

                                <div class="col-sm-3">
                                	<div class="form-group">
                                        <label>Status Retur</label>
                                        <select class="form-control" id="status_retur" name="status_retur">
                                            <option value="">--Pilih Status Retur--</option>
                                            <option value="On Progress" <?php echo($list->message[0]->STATUS_RETUR=="On Progress")?"selected":"";?>>On Progress</option>
                                            <option value="Done" <?php echo($list->message[0]->STATUS_RETUR=="Done")?"selected":"";?>>Done</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                            	<div class="col-sm-3">
                            	 	<div class="form-group">
                                    	<label>Part Number</label>
                                    	<input type="text" name="part_number" id="part_number" class="form-control">
                                	</div>
                            	</div>

                            	<div class="col-sm-2">
                                	<div class="form-group">
                                    	<label>Qty</label>
                                    	<input type="text" name="qty" id="qty" class="form-control qurency text-center" placeholder="Qty">
                                	</div>
                            	</div>

                            	<div class="col-sm-6">
                                	<div class="form-group">
                                    	<label>Keterangan Retur</label>
                                    	<div class="input-group">
                                        	<input type="text" name="Keterangan" id="Keterangan" class="form-control">  

                                        	<span class="input-group-btn">
                                            	<button class="btn btn-primary <?php echo $status_c;?>" onclick="_addItem();" type="button" id="btn-add-sp"><i class="fa fa-plus"></i></button>
                                        	</span>
                                    	</div>
                                	</div>
                            	</div>
                        	</div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table id="pkb_list" class="table table-bordered table-hover b-t b-light">
                    <thead>
                        <tr class="no-hover"><th colspan="6"><i class="fa fa-list fa-fw"></i>List Retur Penjualan & Pembelian Detail</th></tr>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Part Number</th>
                                <th class="text-center" style="width:80px;">Part Deksripsi</th>
                                <th class="text-justify" style="width:200px;">Jumlah</th>
                                <th class="text-justify" style="width:150px;">Keterangan</th>
                                <th class="text-justify" style="width: 150px">Doc Reff</th>
                            </tr>
                        </tr>             
                    </thead>                   
                </table>
            </div>            
        </div>
    </div>
 
    <?php echo loading_proses(); ?>
</section>
 
 

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
        var urls = "<?php echo base_url(); ?>retur/" + param;
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

	