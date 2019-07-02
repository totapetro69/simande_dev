<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.0/jquery-ui.css'); ?>" />
  <script></script>
  <!--load jquery-->
    <script src="<?php echo base_url('assets/js/jquery-1.10.2.js'); ?>"></script>
    <!--load jquery ui js file-->
    <script src="<?php echo base_url('assets/jquery-ui-1.12.0/jquery-ui.js'); ?>"></script>
    </head>

<body>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Karyawan Dengan Kode : "Test"</h4>
</div>

<div class="modal-body">

    <form class="bucket-form" method="get">

        <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK" >
                </div>

                <div class="form-group">
                    <label>NIK Lama</label>
                    <input type="text" name="nik_lama" class="form-control" placeholder="Masukan NIK Lama" >
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" id="" class="form-control" placeholder="Masukkan Nama" >
                </div>

                <div class="form-group">
                    <label>Nama di Kartu</label>
                    <input type="text" name="nama_dikartu" class="form-control" placeholder="Masukan Nama di Kartu" >
                </div>

                <div class="form-group">
                    <label>Nomor KTP</label>
                    <input type="text" name="no_ktp" id="" class="form-control" placeholder="Masukkan Nomor KTP" >
                </div>

                <div class="form-group">
                    <label>NPWP</label>
                    <input type="text" name="npwp" class="form-control" placeholder="Masukkan NPWP" >
                </div>

                <div class="form-group">
                    <label>Kode Gender</label>
                    <select class="form-control">
                        <option>L</option>
                        <option>P</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Golongan Darah</label>
                    <input type="text" name="gol_darah" class="form-control" placeholder="Masukkan Golongan Darah">
                </div>

                <div class="form-group">
                    <label>Provinsi Lahir</label>
                    <select class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option> 
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Tanggal Lahir</label>
                    <div class="input-group input-append date" id="tgl_lahir">
                    <input type="text" name="tgl_lahir" class="form-control" placeholder="MM/YY/DD">
                    <span class="input-group-addon addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                </div>

                <script type="text/javascript">
                    $(function() {
                    $("#tgl_lahir").datepicker();
                    });
                </script>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea type="text" name="alamat" class="form-control" placeholder="Masukkan Alamat"></textarea>
                </div>

                <div class="form-group">
                    <label>RT</label>
                    <input type="text" name="rt" class="form-control" placeholder="Masukkan RT" >
                </div>

                <div class="form-group">
                    <label>RW</label>
                    <input type="text" name="rw" class="form-control" placeholder="Masukkan RW">

                </div>

                <div class="form-group">
                    <label>Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" placeholder="Masukkan Kelurahan" >
                </div>

                <div class="form-group">
                    <label>Kecamatan</label>
                    <select class="form-control">
                        <option>L</option>
                        <option>P</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Propinsi</label>
                    <select class="form-control">
                        <option>L</option>
                        <option>P</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Kota</label>
                    <input type="text" name="kd_kota" id="" class="form-control" placeholder="Masukkan Kode Kota">
                </div>

                <div class="form-group">
                    <label>Kode Kabupaten</label>
                    <select class="form-control">
                        <option>test</option>
                        <option>test</option>
                        <option>test</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Negara</label>
                    <input type="text" name="negara" class="form-control" placeholder="Masukkan Negara">
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" placeholder="Masukkan Telepon" >
                </div>

                <div class="form-group">
                    <label>Handphone</label>
                    <input type="text" name="handphone" class="form-control" placeholder="Masukkan Handphone">
                </div>

                <div class="form-group">
                    <label>Kode Agama</label>
                    <select class="form-control">
                        <option>I</option>
                        <option>K</option>
                        <option>B</option>
                        <option>H</option>
                        <option>KK</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" class="form-control" placeholder="Masukkan Kewarganegaraan">

                </div>

                <div class="form-group">
                    <label>Tinggi</label>
                    <input type="text" name="tinggi" class="form-control" placeholder="Masukkan Tinggi" >
                </div>

                <div class="form-group">
                    <label>Berat</label>
                    <input type="text" name="berat" class="form-control" placeholder="Masukkan Berat">
                </div>

                <div class="form-group">
                    <label>Kode Status Kawin</label>
                    <input type="text" name="kd_status_kawin" class="form-control" placeholder="Masukkan Kode Status Kawin" >
                </div></div>

                <div class="form-group">
                    <label for="date">Tanggal Kawin</label>
                    <div class="input-group input-append date" id="tgl_kawin">
                    <input type="text" class="form-control" name="tgl_kawin" placeholder="MM/DD/YY">
                </div>
                </div>

               <script type="text/javascript">
                $(function() {
                $("#date").datepicker();
                });
                </script>

                <div class="form-group">
                    <label>Jumlah Anak</label>
                    <input type="text" name="jumlah_anak" class="form-control" placeholder="Masukkan Jumlah Anak" >
                </div>

                <div class="form-group">
                    <label>Kode Status Rumah</label>
                    <input type="text" name="kd_status_rumah" class="form-control" placeholder="Masukkan Kode Status Rumah">
                </div>

                <div class="form-group">
                    <label>Rekening Bank</label>
                    <input type="text" name="rekening_bank" class="form-control" placeholder="Masukkan Rekening Bank" >
                </div>

                <div class="form-group">
                    <label>Kota Bank</label>
                    <input type="text" name="kota_bank" class="form-control" placeholder="Masukkan Kota Bank">
                </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Alamat Bank</label>
                    <textarea type="text" name="alamat_bank" class="form-control" placeholder="Masukkan Alamat Bank" ></textarea>
                </div>

                <div class="form-group">
                    <label>Nama di Rekening</label>
                    <input type="text" name="nm_di_rekening" class="form-control" placeholder="Masukkan Nama di Rekening">
                </div>

                <div class="form-group">
                    <label>Kode Status Karyawan</label>
                    <input type="text" name="kd_status_karyawan" class="form-control" placeholder="Masukkan Kode Status Karyawan" >
                </div>

                <div class="form-group">
                    <label>Kode Perusahaan</label>
                    <select class="form-control">
                        <option>Test</option>
                        <option>Test</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Cabang</label>
                    <input type="text" name="kd_cabang" class="form-control" placeholder="Masukkan Kode Cabang" >
                </div>

                <div class="form-group">
                    <label>Kode Divisi</label>
                    <select class="form-control">
                        <option>Test</option>
                        <option>Test</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Jabatan</label>
                    <input type="text" name="kd_jabatan" class="form-control" placeholder="Masukkan Kode Jabatan" >
                </div>

                <div class="form-group">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" class="form-control" placeholder="Masukkan Tanggal Masuk ">
                </div>

                <div class="form-group">
                    <label>Tanggal Keluar</label>
                    <input type="date" name="kd_tanggal_keluar" class="form-control" placeholder="Masukkan Tanggal Keluar" >
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Masukkan Catatan">
                </div>

                <div class="form-group">
                    <label>Nomor KPJ</label>
                    <input type="text" name="no_kpj" class="form-control" placeholder="Masukkan Tanggal Keluar">
                </div>

                <div class="form-group">
                    <label>NIK Apply Cuti</label>
                    <input type="text" name="nik_app_cuti" class="form-control" placeholder="Masukkan NIK Apply Cuti">              
                </div>

                <div class="form-group">
                    <label>NIK Apply Surat</label>
                    <input type="text" name="nik_app_surat" class="form-control" placeholder="Masukkan NIK Apply Surat">
                </div>

                <div class="form-group">
                    <label>Personal Level</label>
                    <input type="text" name="personal_level" class="form-control" placeholder="Masukkan Personal Level">
                </div>

                <div class="form-group">
                    <label>Personal Jabatan</label>
                    <input type="text" name="personal_jabatan" class="form-control" placeholder="Masukkan Personal Jabatan">
                </div>

                <div class="form-group">
                    <label>Provinsi Bank</label>
                    <select class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option> 
                    </select>
                </div>

                <div class="form-group">
                    <label>Cabang Bank</label>
                    <input type="text" name="cabang_bank" class="form-control" placeholder="Masukkan Cabang Bank">
                </div>

                <div class="form-group">
                    <label>Kode Bank</label>
                    <input type="text" name="kd_bank" class="form-control" placeholder="Masukkan Kode Bank">
                </div>

                <div class="form-group">
                    <label>Nomor Record</label>
                    <input type="text" name="no_record" class="form-control" placeholder="Masukkan Nomor Record">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Masukkan Email">
                </div>

                <div class="form-group">
                    <label>Foto</label>
                    <input type="text" name="foto" class="form-control" placeholder="Masukkan Foto">

                    <div class="form-group">
                        <label>Photo</label>
                        <input type="text" name="photo" class="form-control" placeholder="Masukkan Photo">
                    </div>

                    <div class="form-group">
                        <label>NIK Lama</label>
                        <input type="text" name="nik_lama" class="form-control" placeholder="Masukkan NIK Lama">
                    </div>

                    <div class="form-group">
                        <label>Nama Ayah</label>
                        <input type="text" name="nm_ayah" class="form-control" placeholder="Masukkan Nama Ayah">
                    </div>

                    <label>Nama Ibu</label>
                    <input type="text" name="nm_ibu" class="form-control" placeholder="Masukkan Nama Ibu">
                </div>

                <div class="form-group">
                    <label>Keterangan Pengunduran</label>
                    <input type="text" name="ket_pengunduran" class="form-control" placeholder="Masukkan Keterangan Pengunduran">
                </div>

                <div class="form-group">
                    <label>Tanggal Pengunduran</label>
                    <input type="date" name="tgl_pengunduran" class="form-control" placeholder="Masukkan Tanggal Pengunduran">
                </div>

                <div class="form-group">
                    <label>Kode Pos</label>
                    <input type="text" name="kd_pos" class="form-control" placeholder="Masukkan Kode Pos">
                </div>

                <div class="form-group">
                    <label>Alasan</label>
                    <textarea type="text" name="alasan" class="form-control" placeholder="Masukkan Alasan"></textarea>
                </div>

                <div class="form-group">
                    <label>Nomor KK</label>
                    <input type="text" name="no_kk" class="form-control" placeholder="Masukkan Nomor KK">
                </div>

            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-danger">Save changes</button>
</div>
</body>
</html>