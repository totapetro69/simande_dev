<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('ssu/update_ssu/' . $list->message[0]->ID); ?>">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit SSU</h4>
</div>

<div class="modal-body">

      <!-- customer test submodule-->
      <div class="row">
        
        <div class="col-xs-6">
          <div class="form-group">
              <label>NAMA CUSTOMER</label>
              <input type="text" name="nama_customer" id="nama_customer" class="form-control" value="<?php echo  $list->message[0]->NAMA_CUSTOMER; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>JENIS KELAMIN</label>

              <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                  <option disabled=""> --pilih jenis kelamin-- </option>
                  <?php
                      if ($kelamin && is_array($kelamin->message)):
                          foreach ($kelamin->message as $key => $value):
                  ?>
                          <option value="<?php echo $value->KD_GENDER;?>" <?php echo ($list->message[0]->JENIS_KELAMIN == $value->KD_GENDER?'selected':'');?> > <?php echo $value->NAMA_GENDER;?> </option>

                  <?php
                          endforeach;
                      endif;
                  ?>
              </select>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>TGL LAHIR</label>

              <div class="input-group input-append date" id="date">
                  <input class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?php echo tglFromSql($list->message[0]->TGL_LAHIR);?>">
                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
              </div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>TGL PEMBUATAN NPWP</label>
              <div class="input-group input-append date" id="date">
                  <input class="form-control" id="tgl_pembuatan_npwp" name="tgl_pembuatan_npwp" value="<?php echo tglFromSql($list->message[0]->TGL_PEMBUATAN_NPWP);?>">
                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
              </div>
              
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>NO KTP</label>
              <input type="text" name="no_ktp" id="no_ktp" class="form-control" value="<?php echo  $list->message[0]->NO_KTP; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>NO NPWP</label>
              <input type="text" name="no_npwp" id="no_npwp" class="form-control" value="<?php echo  $list->message[0]->NO_NPWP; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>ALAMAT SURAT</label>
              <input type="text" name="alamat_surat" id="alamat_surat" class="form-control" value="<?php echo  $list->message[0]->ALAMAT_SURAT; ?>">
          </div>
        </div>



        <div class="col-xs-6">
          <div class="form-group">
              <input type="hidden" name="nama_propinsi" id="nama_propinsi" class="form-control" value="<?php echo  $list->message[0]->NAMA_PROPINSI; ?>">
              
              <label>PROPINSI</label>
              <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                  <option disabled="">--Pilih Propinsi--</option>
                  <?php
                  if ($propinsi) {
                      if (is_array($propinsi->message)) {
                          foreach ($propinsi->message as $key => $value) {
                              echo "<option value='" . $value->KD_PROPINSI . "'".($list->message[0]->KD_PROPINSI == $value->KD_PROPINSI?'selected':'').">" . $value->NAMA_PROPINSI . "</option>";
                          }
                      }
                  }
                  ?>
              </select>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <input type="hidden" name="nama_kabupaten" id="nama_kabupaten" class="form-control" value="<?php echo  $list->message[0]->NAMA_KABUPATEN; ?>">
              
              <label>KOTA <span id="l_kabupaten"></span></label>
              <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                  <option value="<?php echo  $list->message[0]->KD_KABUPATEN; ?>"><?php echo  $list->message[0]->NAMA_KABUPATEN; ?></option>
              </select>
          </div>
        </div>

        <div class="col-xs-6">

          <div class="form-group">
              <input type="hidden" name="nama_kecamatan" id="nama_kecamatan" class="form-control" value="<?php echo  $list->message[0]->NAMA_KECAMATAN; ?>">
              
              <label>Kecamatan <span id="l_kecamatan"></span></label>
              <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
                  <option value="<?php echo  $list->message[0]->KD_KECAMATAN; ?>"><?php echo  $list->message[0]->NAMA_KECAMATAN; ?></option>
              </select>
          </div>
        </div>

        <div class="col-xs-6">

          <div class="form-group">
              <input type="hidden" name="nama_desa" id="nama_desa" class="form-control" value="<?php echo  $list->message[0]->NAMA_DESA; ?>">
              
              <label>Desa <span id="l_desa"></span></label>
              <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                  <option value="<?php echo  $list->message[0]->KD_DESA; ?>"><?php echo  $list->message[0]->NAMA_DESA; ?></option>
              </select>
          </div>
        </div>




        <div class="col-xs-6">
          <div class="form-group">
              <label>KODE POS</label>
              <input type="text" name="kode_pos" id="kode_pos" class="form-control" value="<?php echo  $list->message[0]->KODE_POS; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>AGAMA</label>


              <select class="form-control" name="kd_agama" id="kd_agama">

                  <option disabled=""> --pilih agama-- </option>
                  <?php
                      if ($agamas && is_array($agamas->message)) :
                          foreach ($agamas->message as $key => $value) :
                  ?>
                          <option value="<?php echo $value->KD_AGAMA;?>" <?php echo ($list->message[0]->KD_AGAMA == $value->KD_AGAMA?'selected':'');?> ><?php echo $value->NAMA_AGAMA;?> </option>;
                  <?php   endforeach;
                      endif
                  ?>
              </select>
          </div>
        </div>



        <div class="col-xs-6">
          <div class="form-group">
              <label>PEKERJAAN</label>

              <select class="form-control" name="kd_pekerjaan" id="kd_pekerjaan">

                  <option disabled=""> --pilih pekerjaan-- </option>
                  <?php
                      if ($pekerjaans && is_array($pekerjaans->message)) :
                          foreach ($pekerjaans->message as $key => $value) :
                  ?>
                          <option value="<?php echo $value->KD_PEKERJAAN;?>" <?php echo ($list->message[0]->KD_PEKERJAAN == $value->KD_PEKERJAAN?'selected':'');?> ><?php echo $value->NAMA_PEKERJAAN;?> </option>;
                  <?php   endforeach;
                      endif
                  ?>
              </select>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>PENGELUARAN</label>
              <select class="form-control" name="range_pengeluaran" id="range_pengeluaran">
                  <option disabled=""> --pilih pengeluaran-- </option>
                  <?php
                      if ($pengeluaran && is_array($pengeluaran->message)) :
                          foreach ($pengeluaran->message as $key => $value) :
                  ?>
                          <option value="<?php echo $value->ID;?>" <?php echo ($list->message[0]->PENGELUARAN == $value->ID?'selected':'');?> ><?php echo $value->RANGE_PENGELUARAN;?> </option>
                  <?php   
                          endforeach;
                      endif;
                  ?>
              </select>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>PENDIDIKAN</label>
              <select class="form-control" name="kd_pendidikan" id="kd_pendidikan">

                  <option disabled=""> --pilih pendidikan-- </option>
                  <?php
                      if ($pendidikan && is_array($pendidikan->message)) :
                          foreach ($pendidikan->message as $key => $value) :
                  ?>
                          <option value="<?php echo $value->KD_PENDIDIKAN;?>" <?php echo ($list->message[0]->KD_PENDIDIKAN == $value->KD_PENDIDIKAN?'selected':'');?> ><?php echo $value->NAMA_PENDIDIKAN;?> </option>;
                  <?php   endforeach;
                      endif
                  ?>
              </select>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>NAMA PENANGGUNGJAWAB</label>
              <input type="text" name="nama_penanggungjawab" id="nama_penanggungjawab" class="form-control" value="<?php echo  $list->message[0]->NAMA_PENANGGUNGJAWAB; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>NO HP</label>
              <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?php echo  $list->message[0]->NO_HP; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>NO TELEPON</label>
              <input type="text" name="no_telepon" id="no_telepon" class="form-control" value="<?php echo  $list->message[0]->NO_TELEPON; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>STATUS DIHUBUNGI</label>
              <!-- <input type="text" name="status_dihubungi" id="status_dihubungi" class="form-control" value="<?php echo  $list->message[0]->STATUS_DIHUBUNGI; ?>"> -->

              <select class="form-control" name="status_dihubungi" id="status_dihubungi">

                  <option disabled=""> --pilih status dihubungi-- </option>

                  <option value="Y" <?php echo ($list->message[0]->STATUS_DIHUBUNGI == 'Y'?'selected':'');?>>Ya</option>
                  <option value="N" <?php echo ($list->message[0]->STATUS_DIHUBUNGI == 'N'?'selected':'');?>>Tidak</option>
              </select>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>EMAIL</label>
              <input type="text" name="email" id="email" class="form-control" value="<?php echo  $list->message[0]->EMAIL; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>STATUS RUMAH</label>

              <select class="form-control" name="status_rumah" id="status_rumah">
                <option disabled="">-- Pilih Status Rumah --</option>
                <option value="Rumah Sendiri" <?php echo ($list->message[0]->STATUS_RUMAH == 'Rumah Sendiri'?'selected':'');?>>Rumah Sendiri</option>
                <option value="Rumah Orang Tua / Keluarga" <?php echo ($list->message[0]->STATUS_RUMAH == 'Rumah Orang Tua / Keluarga'?'selected':'');?>>Rumah Orang Tua / Keluarga</option>
                <option value="Rumah Sewa" <?php echo ($list->message[0]->STATUS_RUMAH == 'Rumah Sewa'?'selected':'');?>>Rumah Sewa</option>
              </select>

              <!-- <input type="text" name="status_rumah" id="status_rumah" class="form-control" value="<?php echo  $list->message[0]->STATUS_RUMAH; ?>"> -->
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>STATUS NOHP</label>

              <select class="form-control" name="status_nohp" id="status_nohp">

                  <option disabled=""> --pilih status no hp-- </option>

                  <option value="1" <?php echo ($list->message[0]->STATUS_NOHP == 1?'selected':'');?>>Pra Bayar (Isi Ulang)</option>
                  <option value="2" <?php echo ($list->message[0]->STATUS_NOHP == 2?'selected':'');?>>Pasca Bayar /Billing/Tagihan</option>
              </select>
              
              <!-- <input type="text" name="status_nohp" id="status_nohp" class="form-control" value="<?php echo  $list->message[0]->STATUS_NOHP; ?>"> -->


          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>AKUN FB</label>
              <input type="text" name="akun_fb" id="akun_fb" class="form-control" value="<?php echo  $list->message[0]->AKUN_FB; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>AKUN TWITTER</label>
              <input type="text" name="akun_twitter" id="akun_twitter" class="form-control" value="<?php echo  $list->message[0]->AKUN_TWITTER; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>AKUN INSTAGRAM</label>
              <input type="text" name="akun_instagram" id="akun_instagram" class="form-control" value="<?php echo  $list->message[0]->AKUN_INSTAGRAM; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>AKUN YOUTUBE</label>
              <input type="text" name="akun_youtube" id="akun_youtube" class="form-control" value="<?php echo  $list->message[0]->AKUN_YOUTUBE; ?>">
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>HOBI</label>


              <select class="form-control" name="hobi" id="hobi">
                  <option disabled=""> --pilih jenis kelamin-- </option>
                  <?php
                      if ($hobby && is_array($hobby->message)):
                          foreach ($hobby->message as $key => $value):
                  ?>
                          <option value="<?php echo $value->KD_HOBBY;?>" <?php echo ($list->message[0]->HOBI == $value->KD_HOBBY?'selected':'');?> > <?php echo $value->NAMA_HOBBY;?> </option>

                  <?php
                          endforeach;
                      endif;
                  ?>
              </select>

              <!-- <input type="text" name="hobi" id="hobi" class="form-control" value="<?php echo  $list->message[0]->HOBI; ?>"> -->
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group">
              <label>KARAKTERISTIK KONSUMEN</label>
              <input type="text" name="karakteristik_konsumen" id="karakteristik_konsumen" class="form-control" value="<?php echo  $list->message[0]->KARAKTERISTIK_KONSUMEN; ?>">
          </div>
        </div>




        <div class="col-xs-6">
          <div class="form-group">
              <label>ALAMAT</label>
              <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo  $list->message[0]->ALAMAT; ?>">
          </div>
        </div>

      </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn <?php echo $status_e?>">Simpan</button>
    <!-- <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button> -->
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

  $('#kd_propinsi').on('change', function () {
      var value = $(this).val();
      var text = $("#kd_propinsi option:selected").text();

      $("#nama_propinsi").val(text);

      loadData('kd_kabupaten', value, '0')
  })
  $('#kd_kabupaten').on('change', function () {
      var value = $(this).val();
      var text = $("#kd_kabupaten option:selected").text();

      $("#nama_kabupaten").val(text);

      loadData('kd_kecamatan', value, '0')
  })
  $('#kd_kecamatan').on('change', function () {
      var value = $(this).val();
      var text = $("#kd_kecamatan option:selected").text();

      $("#nama_kecamatan").val(text);

      loadData('kd_desa', value, '0')
  })
  $('#kd_desa').on('change', function () {
      var value = $(this).val();
      var text = $("#kd_desa option:selected").text();

      $("#nama_desa").val(text);
      
  })

  function loadData(id, value, select) {

      var param = $('#' + id + '').attr('title');
      $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
      var urls = "<?php echo base_url(); ?>customer/" + param;
      var datax = {"kd": value};

      // console.log(urls+value);
      $.ajax({
          type: 'GET',
          url: urls,
          data: datax,
          typeData: 'html',
          success: function (result) {
              $('#' + id + '').html('');
              $('#' + id + '').html(result);
              $('#' + id + '').val(select).select();
              $('#l_' + param + '').html('');
          }
      });
  }
})

</script>