<?php
$defaultDealer = $this->session->userdata("kd_dealer");
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Karyawan : <?php echo $list->message[0]->NAMA; ?></h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('company/ubah_karyawan/' . $list->message[0]->NIK); ?>">

        <div class="form-group">
            <label>NIK</label>
            <input type="text" name="nik" id="nik" class="form-control" value="<?php echo $list->message[0]->NIK; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Kode Perusahaan</label>
            <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
                <option value="0">--Pilih Dealer--</option>
                <?php
                if ($dealer) {
                    if (is_array($dealer->message)) {
                        foreach ($dealer->message as $key => $value) {
                            $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                            $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                            echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->KD_DEALER . "</option>";
                        }
                    }
                }
                ?> 
            </select>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $list->message[0]->NAMA; ?>" >
        </div>
        <div class="form-group">
            <label>Kode Status</label>
            <select class="form-control" id="kd_status" name="kd_status">
                <option value="0">--Pilih Kode Status--</option>
                <?php if ($status && (is_array($status->message) || is_object($status->message))): foreach ($status->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_STATUS ?>" <?php echo ($value->KD_STATUS == $list->message[0]->KD_STATUS ? "selected" : ""); ?>><?php echo $value->KD_STATUS ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>

        </div>

        <div class="form-group">
            <label>Kode Divisi</label>
            <select class="form-control" id="kd_divisi" name="kd_divisi" required>
                <option value="">- Pilih Kode Divisi -</option>
                <?php if ($divisi && (is_array($divisi->message) || is_object($divisi->message))): foreach ($divisi->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_DIVISI; ?>" <?php echo ($value->KD_DIVISI == $list->message[0]->KD_DIVISI ? "selected" : ""); ?>><?php echo $value->KD_DIVISI; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Jabatan</label>
            <select class="form-control" id="kd_jabatan" name="kd_jabatan" required>
                <option value="">- Pilih Kode Jabatan -</option>
                <?php if ($jabatan && (is_array($jabatan->message) || is_object($jabatan->message))): foreach ($jabatan->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_JABATAN; ?>" <?php echo ($value->KD_JABATAN == $list->message[0]->KD_JABATAN ? "selected" : ""); ?>><?php echo $value->KD_JABATAN; ?></option>
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
                        <option value="<?php echo $value->PERSONAL_JABATAN; ?>" <?php echo ($value->PERSONAL_JABATAN == $list->message[0]->PERSONAL_JABATAN ? "selected" : ""); ?> ><?php echo $value->PERSONAL_JABATAN; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Personal Level</label>
            <select class="form-control" id="personal_level" name="personal_level" required>
                <option value="">- Pilih Kode Personal Level -</option>
                <?php if ($personal_level && (is_array($personal_level->message) || is_object($personal_level->message))): foreach ($personal_level->message as $key => $value) : ?>
                        <option value="<?php echo $value->PERSONAL_LEVEL; ?>" <?php echo ($value->PERSONAL_LEVEL == $list->message[0]->PERSONAL_LEVEL ? "selected" : ""); ?>><?php echo $value->PERSONAL_LEVEL; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Atasan Langsung</label>
            <select class="form-control" id="atasan_langsung" name="atasan_langsung" required>
                <option value="">- Pilih Kode Atasan Langsung -</option>
               
                <option value="<?php echo $list->message[0]->ATASAN_LANGSUNG; ?>" <?php echo ($list->message[0]->ATASAN_LANGSUNG ? "selected" : ""); ?>><?php echo $list->message[0]->ATASAN_LANGSUNG; ?></option>
                
                
                <?php if ($atasan && (is_array($atasan->message) || is_object($atasan->message))): foreach ($atasan->message as $key => $value) : ?>
                        <option value="<?php echo $value->NIK; ?>"><?php echo $value->NIK; ?> - <?php echo $value->NAMA; ?> </option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="date">Tanggal Lahir</label>
            <div class="input-group input-append date" id="datex">
                <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?php echo ($list->message[0]->TGL_LAHIR!='')?tglfromSql($list->message[0]->TGL_LAHIR): date('d/m/Y');?>" placeholder="dd/mm/yyyy" required="required" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Pendidikan</label>
            <select class="form-control" id="pendidikan" name="pendidikan" required>
                <option value="">- Pilih Pendidikan -</option>
                <?php if ($studi && (is_array($studi->message) || is_object($studi->message))): foreach ($studi->message as $key => $value) : ?>
                        <option value="<?php echo $value->PENDIDIKAN; ?>" <?php echo ($value->PENDIDIKAN == $list->message[0]->PENDIDIKAN ? "selected" : "");?>><?php echo $value->PENDIDIKAN; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="date">Tanggal Masuk</label>
            <div class="input-group input-append date" id="datex">
                <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk" value="<?php echo ($list->message[0]->TGL_MASUK!='')?tglfromSql($list->message[0]->TGL_MASUK): date('d/m/Y');?>" placeholder="dd/mm/yyyy" required="required" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <!--        <div class="form-group">
                    <label>Kode Sales</label>
                    <input type="text" name="kd_sales" id="kd_sales" class="form-control" value="<?php echo $list->message[0]->KD_SALES; ?>" >
                </div>
        
                <div class="form-group">
                    <label>Kode HSales</label>
                    <input type="text" name="kd_hsales" id="kd_hsales" class="form-control" value="<?php echo $list->message[0]->KD_HSALES; ?>" >
                </div>-->

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>