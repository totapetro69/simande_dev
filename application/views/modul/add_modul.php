<form id="addForm" class="bucket-form" action="<?php echo base_url('modul/store_modul');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Modul</h4>
</div>

<div class="modal-body">


    <div class="row">
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <label>Kode Modul</label>
            <input id="kd_modul" type="text" name="kd_modul" class="form-control" placeholder="masukan Kode Modul" style="text-transform: uppercase;" maxlength="5" required>
        </div>
      </div>
      <div class="col-xs-12 col-md-5">
        <div class="form-group">
            <label>Nama Modul</label>
            <input id="nama_modul" type="text" name="nama_modul" class="form-control" placeholder="masukan Nama Modul" required>
        </div>
      </div>
      <div class="col-xs-12 col-md-4">
        <div class="form-group">
            <label>Icon Modul</label>
            <input id="icon_modul" type="text" name="icon_modul" class="form-control" placeholder="masukan Nama Icon" >
        </div>


      </div>


    </div>


    <div class="row">
      <div class="col-xs-12 col-md-2">
        <div class="form-group">
            <label>Urutan</label>
            <input id="urutan_modul" type="number" name="urutan_modul" class="form-control input-number" placeholder="masukan Urutan" min="1" required>
        </div>
      </div>
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <label>Link Modul</label>
            <input id="link_modul" type="text" name="link_modul" class="form-control" placeholder="masukan Alamat Link" >
        </div>
      </div>
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
          <label>Parent</label>
          <select name="parent_modul" class="form-control">
            <option value="">- NULL -</option>
            <?php if($moduls && (is_array($moduls->message) || is_object($moduls->message))): foreach ($moduls->message as $key => $modul) : ?>
              <option value="<?php echo $modul->KD_MODUL;?>"><?php echo $modul->NAMA_MODUL;?> [<?php echo strtoupper($modul->KD_MODUL);?> ]</option>
            <?php endforeach; endif;?>
          </select>

        </div>
      </div>

      <div class="col-xs-12 col-md-4">
        <div class="form-group">
            <br>
            <input id="parent_status" name="parent_status" type="checkbox"> Tidak memiliki submenu
        </div>
      </div>


    </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>

<script type="text/javascript">

  /*
                $('#storeForm').validate({
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

                jQuery("#storeForm").on("submit", function( event ) {
                  event.preventDefault();
                  if (jQuery("#storeForm").valid()) {
                      // Do something
                    // alert('sukses');
                    event.preventDefault();
                    

                    var defaultBtn = $("#save-btn").html();

                    $("#save-btn").addClass("disabled");
                    $("#save-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
                    $(".alert-message").fadeIn();

                    var formData = $("#storeForm").serialize();
                    var act = $("#storeForm").attr('action');     

                    
                    $.ajax({
                        url: act,
                        type: 'POST',
                        data: formData,
                        dataType: "json",
                        success: function (result) {

                            if (result.status == true) {

                                $('.success').animate({ top: "0" }, 500);
                                $('.success').html(result.message);


                                if (result.location != null) {
                                    setTimeout(function(){
                                    location.replace(result.location)
                                    }, 2000);
                                }
                                else
                                {
                                    setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                }
                            } 
                            else {

                                $('.error').animate({ top: "0" }, 500);
                                $('.error').html(result.message);

                                setTimeout(function () {
                                    hideAllMessages();
                                    $("#save-btn").removeClass("disabled");
                                    $("#save-btn").html(defaultBtn);
                                }, 4000);
                            }
                        }
                        
                    });
                    
                    return false;
                  }
                  else{
                    alert('gagal');
                  }
                });*/
                       

</script>

