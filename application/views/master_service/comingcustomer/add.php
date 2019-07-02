<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Coming Customer</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/add_comingcustomer_simpan'); ?>">

        <div class="form-group">
            <label>Nama (wajib diisi)</label>
            <input type="text" name="nama_comingcustomer" id="nama_comingcustomer" class="form-control" placeholder="Masukkan nama" >
        </div>
        <div class="form-group">
           <label>Tipe (wajib diisi)</label>
           <select name="kd_typecomingcustomer" class="form-control">
               <option value="" >- Pilih Tipe Coming Customer -</option>
               <?php if($typecomingcustomers && (is_array($typecomingcustomers->message) || is_object($typecomingcustomers->message))): foreach ($typecomingcustomers->message as $key => $typecomingcustomer) : ?>
                 <option value="<?php echo $typecomingcustomer->KD_TYPECOMINGCUSTOMER;?>"><?php echo $typecomingcustomer->NAMA_TYPECOMINGCUSTOMER;?></option>
             <?php endforeach; endif;?>
         </select>
     </div>
     <div class="form-group">
       <label>Jenis Kelamin</label>
       <select name="kd_gender" class="form-control">
           <option value="" >- Pilih Jenis Kelamin -</option>
           <?php if($genders && (is_array($genders->message) || is_object($genders->message))): foreach ($genders->message as $key => $gender) : ?>
             <option value="<?php echo $gender->KD_GENDER;?>"><?php echo $gender->NAMA_GENDER;?></option>
         <?php endforeach; endif;?>
     </select>
 </div>
 <div class="form-group">
    <label>Email</label>
    <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email">
</div>
<div class="form-group">
    <label>Nomor KTP (wajib diisi)</label>
    <input type="text" name="no_ktp" id="no_ktp" class="form-control" placeholder="Masukkan nomor KTP" >
</div>
<div class="form-group">
    <label>Nomor Telepon</label>
    <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="Masukkan nomor telepon" >
</div>
<div class="form-group">
    <label>Alamat KTP</label>
    <input type="text" name="alamat_ktp" id="alamat_ktp" class="form-control" placeholder="Masukkan alamat sesuai KTP" >
</div>
<div class="form-group">
    <label>Alamat Terakhir</label>
    <input type="text" name="alamat_terakhir" id="alamat_terakhir" class="form-control" placeholder="Masukkan alamat saat ini" >
</div>
<!-- propinsi -->
<div class="form-group">
    <label>Propinsi</label>
    <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
        <option value="0">--Pilih Propinsi--</option>
        <?php
        if ($propinsi) {
            if (is_array($propinsi->message)) {
                foreach ($propinsi->message as $key => $value) {
                    echo "<option value='" . $value->KD_PROPINSI . "'>" . $value->NAMA_PROPINSI . "</option>";
                }
            }
        }
        ?>
    </select>
</div>
<!-- kabupaten -->
<div class="form-group">
    <label>Kabupaten <span id="l_kabupaten"></span></label>
    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
        <option value="0">--Pilih Kabupaten--</option>
    </select>
</div>
<!-- kecamatan -->
<div class="form-group">
    <label>Kecamatan <span id="l_kecamatan"></span></label>
    <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
        <option value="0">--Pilih Kecamatan--</option>
    </select>
</div>
<!-- kelurahan -->
<div class="form-group">
    <label>Kelurahan <span id="l_desa"></span></label>
    <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
        <option value="0">--Pilih Desa/Kelurahan--</option>
    </select>
</div>
<div class="form-group">
    <label>Kode Pos</label>
    <input type="text" name="kode_pos" id="kode_pos" class="form-control" placeholder="Masukkan kode pos" >
</div>
</form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(), '0')
        })
        $('#kd_kabupaten').on('change', function () {
            loadData('kd_kecamatan', $(this).val(), '0')
        })
        $('#kd_kecamatan').on('change', function () {
            loadData('kd_desa', $(this).val(), '0')
        })
        $('#baru').click(function(){
            document.location.reload();
        })

        $("#submit-btn").on('click',function(event){
            var formId = '#'+$(this).closest('form').attr('id');
            var btnId = '#'+this.id;
            $('#loadpage').removeClass("hidden");

            $(formId).validate({
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
            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();

                addValid(formId, btnId);

            }else{
                $('#loadpage').addClass("hidden");
                $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
            }
        });
    })

    function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>master_service/" + param;
        var datax = {"kd": value};
        $('#' + id + '').attr('disabled','disabled');
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