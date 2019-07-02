
<?php
$NO_SURATJALAN = '';
$STATUS_SJ = '';
$KETERANGAN = '';
$COUNT_BARANG = '';


if($list){

  if(is_array($list->message)){

    $jmluraian=count($list->message);

    foreach ($list->message as $key => $value) {
      $NO_SURATJALAN = $value->NO_SURATJALAN;
      $STATUS_SJ = $value->STATUS_SJ;
      $KETERANGAN = $value->KETERANGAN;
      $COUNT_BARANG = $value->COUNT_BARANG;
    }

  }

}

?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('pengeluaran/sj_status');?>" method="post" enctype="multipart/form-data">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Delivery Unit</h4>
</div>

<div class="modal-body">


    <div class="row">
        <div class="col-xs-12 col-sm-12">

            <div class="form-group">
                <label>No Surat Jalan</label>
                <input type="text" id="no_suratjalan" name="no_suratjalan" class="form-control" value="<?php echo $NO_SURATJALAN;?>" placeholder="Nomor Surat Jalan" readonly>
            </div>

        </div>

        <div class="col-xs-12 col-sm-6">
    		<div class="form-group">
                <label >Status</label>
                <select id="status_sj" name="status_sj" class="form-control" <?php echo $STATUS_SJ != 'process'?'disabled':'';?>>
                    <!-- <option value="process" <?php echo $STATUS_SJ == 'process'?'selected':'';?> >Process</option> -->
                    <?php if($COUNT_BARANG == 'allowed'): ?>
                    <option value="aproved" <?php echo $STATUS_SJ == 'aproved'?'selected':'';?>>Aproved</option>
                    <?php endif;?>
                    <option value="rejected" <?php echo $STATUS_SJ == 'rejected'?'selected':'';?>>Rejected</option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3">

            <div class="form-group">
                <label class="control-label" for="date">Tanggal Diterima</label>
                <div class="input-group input-append date" id="date">
                    <input type="text" class="form-control" id="tgl_terima" name="tgl_terima" placeholder="MM/DD/YY" value="<?php echo date('d/m/Y');?>" required="required" />
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3">

            <div class="form-group">
                <label class="control-label" for="date">Jam</label>
                <div class="input-group input-append datetime" id="datetime">
                    <input class="form-control" id="waktu_terima" name="waktu_terima" placeholder="HH:MM" value="" type="text" required/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                </div>
            </div>
        </div>

        <div id="keterangan_form"></div>

        <div class="col-xs-12 col-sm-12">
            
            <div class="form-group">
    			<label>Unggah</label>
    			<input type="file"  name="bukti_terima" placeholder="surat jalan"  required="required">
                <p class='help-block'>*jpg, *jpag, *png.</p>
            </div>
        </div>


    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button id="submit-btn" class="btn btn-danger file-btn">Simpan</button>
</div>
</form>

<script type="text/javascript">
$(document).ready(function(){

    var date = new Date();
    date.setDate(date.getDate());

    $('.date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true
    });

    $('.datetime').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });

    $(".file-btn").click(function(event){
        // event.preventDefault();


        $("#addForm").validate({
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });


        if (jQuery("#addForm").valid()) {
        // Do something

            $("#submit-btn").addClass("disabled");
            $("#status_sj").removeAttr('disabled');
            $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
            $(".alert-message").fadeIn();

            var options = { 
                
                success:    function(data, status) { 
                    var data = JSON.parse(data);
                    
                    if (data.status == true) {
            //          debugger;
                        $('.success').animate({top:"0"}, 500);
                        $('.success').html(data.message);

                        setTimeout(function(){
                            location.reload()
                        }, 2000);

                      } else {
                        $('.error').animate({top:"0"}, 500);
                        $('.error').html(data.message);

                        setTimeout(function(){
                            hideAllMessages();
                            $("#submit-btn").removeClass("disabled");
                            $("#submit-btn").html("Simpan Data");
                        }, 4000);;
                      }
                } 
            }; 
            $('#addForm').ajaxForm(options).submit(); 

            event.preventDefault();

        }

    });

    $("#status_sj").change(function(){
        var value = $(this).val();

        if(value == 'rejected')
        {
            var keterangan = 
              '<div class="col-xs-12 col-sm-12">'+
                '<div class="form-group">'+
                    '<label>Keterangan</label>'+
                    '<textarea id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan"></textarea>'+
                '</div>'+
              '</div>';

            $("#keterangan_form").html(keterangan);
        }
        else{

            $("#keterangan_form").html('');
        }
        // alert(value);
    });
})
</script>
