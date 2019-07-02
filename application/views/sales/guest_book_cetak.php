<?php
    $defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Customer search</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="table-responsive h400">
            <table class="table table-striped b-t b-light table-hover table-bordered" id="lst">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>&nbsp;</th>
                        <th>Kode Customer</th>
                        <th>Nama Customer</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Type Customer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n=0;
                        if(isset($list)){
                            if($list->totaldata > 0){
                                foreach ($list->message as $key => $value) {
                                    
                                    $hidden = ($value->KD_DEALER==$defaultDealer)?"":"hidden";
                                    if(!$hidden){
                                        $n++;
                                    }
                                    ?>
                                        <tr id="l_<?php echo $value->KD_CUSTOMER;?>" class="<?php echo $hidden;?>">
                                            <td class='text-center'><?php echo $n;?></td>
                                            <td class='text-center'><input type="radio" onclick="__pilih('<?php echo $value->KD_CUSTOMER;?>');" id='chk_<?php echo $value->KD_CUSTOMER;?>' group='chk' name='chk'></td>
                                            <td class="table-nowarp"><?php echo $value->KD_CUSTOMER;?></td>
                                            <td class='td-overflow' title="<?php echo $value->NAMA_CUSTOMER;?>"><?php echo str_replace("\'","'",$value->NAMA_CUSTOMER);?></td>
                                            <td class='table-nowarp'><?php echo $value->NO_HP;?></td>
                                            <td class='td-overflow-50' title="<?php echo $value->ALAMAT_SURAT;?>"><?php echo str_replace("\'","'",$value->ALAMAT_SURAT);?></td>
                                            <td class='table-nowarp'><?php echo (isset($value->NO_KTP))?$value->NO_KTP:"";?></td>
                                        </tr>
                                    <?
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type='hidden' value='' id="pilihan">
<div class="modal-footer">
        <button type="button" id="plh" class="btn btn-warning hidden"><i class='fa fa-cogs'></i> Submit Pilihan</button>
        <button type="button" class="btn btn-default batal" data-dismiss="modal"><i class='fa fa-close'></i> Batal</button>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //if($('#pilihan').val()){
            $('#plh').removeClass('hidden');
            $('#plh').click(function(){
                __getcustomerdetail($('#pilihan').val(),true)
                $('.batal').click();
            })
        //}
    })
    function __pilih(kd_customer){
        //var nama= $('#lst >tbody >tr#l_'+kd_customer+'td:eq(3)').text();
        $('#pilihan').val(kd_customer);
        $('#plh').removeClass('hidden');
    }
</script>