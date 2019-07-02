<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
$status_cabang = $this->session->userdata("status_cabang");

$required = $status_cabang == 'T' ? '':'required';
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('company/add_karyawan_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Input Karyawan</h4>
    </div>


    <div class="modal-body">

        <div class="form-group">
            <label>Kode Perusahaan</label>
            <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required>
                <option value="0">--Pilih Dealer--</option>
                <?php
                if ($dealer) {
                    if (is_array($dealer->message)) {
                        foreach ($dealer->message as $key => $value) {
                            $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                            $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                            echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->KD_DEALER . "</option>";
                        }
                    }
                }
                ?> 
            </select>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Karyawan" required>
        </div>

        <div class="form-group">
            <label>Kode Status</label>
            <select class="form-control" id="kd_status" name="kd_status" <?php echo $required;?>>
                <option value="">- Pilih Kode Status -</option>
                <?php if ($status && (is_array($status->message) || is_object($status->message))): foreach ($status->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_STATUS; ?>"><?php echo $value->KD_STATUS; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Divisi</label>
            <select class="form-control" id="kd_divisi" name="kd_divisi" <?php echo $required;?>>
                <option value="">- Pilih Kode Divisi -</option>
                <?php if ($divisi && (is_array($divisi->message) || is_object($divisi->message))): foreach ($divisi->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_DIVISI; ?>"><?php echo $value->KD_DIVISI; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Jabatan</label>
            <select class="form-control" id="kd_jabatan" name="kd_jabatan" <?php echo $required;?>>
                <option value="">- Pilih Kode Jabatan -</option>
                <?php if ($jabatan && (is_array($jabatan->message) || is_object($jabatan->message))): foreach ($jabatan->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_JABATAN; ?>"><?php echo $value->KD_JABATAN; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Personal Jabatan</label>
            <select class="form-control" id="personal_jabatan" name="personal_jabatan" required>
                <option value="">- Pilih Kode Personal Jabatan -</option>
                <?php if ($personal_jabatan && (is_array($personal_jabatan->message) || is_object($personal_jabatan->message))): foreach ($personal_jabatan->message as $key => $value) : ?>
                        <option value="<?php echo $value->PERSONAL_JABATAN; ?>"><?php echo $value->PERSONAL_JABATAN; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Personal Level</label>
            <select class="form-control" id="personal_level" name="personal_level" <?php echo $required;?>>
                <option value="">- Pilih Kode Personal Level -</option>
                <?php if ($personal_level && (is_array($personal_level->message) || is_object($personal_level->message))): foreach ($personal_level->message as $key => $value) : ?>
                        <option value="<?php echo $value->PERSONAL_LEVEL; ?>"><?php echo $value->PERSONAL_LEVEL; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Atasan Langsung</label>
            <select class="form-control" id="atasan_langsung" name="atasan_langsung" <?php echo $required;?>>
                <option value="">- Pilih Kode Atasan Langsung -</option>
                <?php if ($atasan && (is_array($atasan->message) || is_object($atasan->message))): foreach ($atasan->message as $key => $value) : ?>
                        <option value="<?php echo $value->NIK; ?>"><?php echo $value->NIK; ?> - <?php echo $value->NAMA; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="date">Tanggal Lahir</label>
            <div class="input-group input-append date" id="datex">
                <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" value="" placeholder="dd/mm/yyyy" <?php echo $required;?> />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Pendidikan</label>
            <select class="form-control" id="pendidikan" name="pendidikan" required>
                <option value="">- Pilih Pendidikan -</option>
                <?php if ($studi && (is_array($studi->message) || is_object($studi->message))): foreach ($studi->message as $key => $value) : ?>
                        <option value="<?php echo $value->PENDIDIKAN; ?>"><?php echo $value->PENDIDIKAN; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="date">Tanggal Masuk</label>
            <div class="input-group input-append date" id="datein">
                <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk" value="" placeholder="dd/mm/yyyy" <?php echo $required;?> />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        


        
<!--        <div class="form-group">
            <label>Kode Sales</label>
            <input type="text" name="kd_sales" id="kd_sales" class="form-control" placeholder="Masukkan Kode Sales" >
        </div>

        <div class="form-group">
            <label>Kode HSales</label>
            <input type="text" name="kd_hsales" id="kd_hsales" class="form-control" placeholder="Masukkan Kode H Sales" >
        </div>-->

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>

<script type="text/javascript">
    $(document).ready(function(){

        var date = new Date();
        date.setDate(date.getDate());

        $('#datex').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight:true,
            onClose:function(){
                checkumur();
            }
        });


        $('#datein').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });
    })
</script>