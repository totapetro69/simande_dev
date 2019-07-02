<?php
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$kd_kabupaten =($this->input->get("kd_kabupaten"))?$this->input->get("kd_kabupaten"):$this->session->userdata("kd_kabupaten");
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_area_dealer_simpan'); ?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class='fa fa-list-ul'></i> Add Area Dealer</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label>Kode Dealer</label>
                    <select class="form-control" id="kd_dealer" name="kd_dealer">
                        <option value="0">--Pilih Dealer--</option>
                        <?php
                        if(isset($dealer)){
                           if($dealer->totaldata >0){
                              foreach ($dealer->message as $key => $value) {
                                 $pilih = ($defaultDealer == $value->KD_DEALER)?'selected':'';
                                 ?>
                                    <option value="<?php echo $value->KD_DEALER;?>" <?php echo $pilih;?>><?php echo $value->NAMA_DEALER;?></option>
                                 <?php
                              }
                           }
                        }
                        ?> 
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label>Kode Kabupaten</label>
                    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" required="true">
                        <option value="">--Pilih Kabupaten--</option>
                        <?php
                        if(isset($kabupaten)){
                            if($kabupaten->totaldata >0){
                                foreach ($kabupaten->message as $key => $value) {
                                    $pilih=($kd_kabupaten == $value->KD_KABUPATEN)?'selected':'';
                                    echo "<option value='".$value->KD_KABUPATEN."' ".$pilih.">".$value->NAMA_KABUPATEN."</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label>Kode Kecamatan <span id="l_kecamatan"></span></label>
                    <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan" required="true">
                        <option value="0">--Pilih Kecamatan--</option>
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label>Ring Area <i class='fa fa-info-circle' style="cursor: pointer;"></i></label>
                    <select class="form-control" id="ring_area" name="ring_area">
                        <option value="LOKASI">Lokasi</option>
                        <option value="RING1">Ring 1</option>
                        <option value="RING1">Ring 2</option>
                        <option value="RING1">Ring 3</option>
                        <option value="OTHERS">Others</option>
                    </select>
                </div>
            </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        loadData('kd_kecamatan','<?php echo $kd_kabupaten;?>','0');
        $('#kd_kabupaten').on('change', function () {
            loadData('kd_kecamatan', $(this).val(), '0')
        })
    });
    function loadData(id, value, select) {
    var param = $('#' + id + '').attr('title');
    $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
    var urls = "<?php echo base_url();?>" + "/customer/" + param+"/";
    var datax = {
        "kd": value
    };
    $('#' + id + '').attr('disabled', 'disabled');
    select = (select == '' || select == "0") ? "0" : select;
    $.ajax({
        type: 'GET',
        url: urls,
        data: datax,
        typeData: 'html',
        success: function(result) {
            $('#' + id + '').empty();
            $('#' + id + '').html(result);
            $('#' + id + '').val(select).select();
            $('#l_' + param + '').html('');
            $('#alamat_lg').html("");
            $('#' + id + '').removeAttr('disabled');
        }
    });
}

</script>