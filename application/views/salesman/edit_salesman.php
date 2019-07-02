<?php
    $kd_sales="";$kd_hsales="";$nama_sales="";$nik_sales="";
    $kd_dealer=$this->session->userdata("kd_dealer");
    $kd_jabatan="";$ps_jabatan="";$kd_group="";$status_sales="";
    $kd_jab=array();$ps_jab=array();
    if(isset($list)){
        if($list->totaldata>0){
            foreach ($list->message as $key => $value) {
                $kd_sales   = $value->KD_SALES;
                $kd_hsales  = $value->KD_HSALES;
                $nama_sales = $value->NAMA_SALES;
                $kd_dealer  = $value->KD_DEALER;
                $kd_group   = $value->GROUP_SALES;
                $kd_jabatan = $value->KD_JABATAN;
                $ps_jabatan = $value->PERSONAL_JABATAN;
                $status_sales = $value->STATUS_SALES;
                $nik_sales  = $value->NIK;
            }
        }
    }
    $bukanCabang=($this->session->userdata("status_cabang"));
    $disabled_action=(strlen($kd_sales)>0 && $status_sales=='X')?'disabled-action':"";
    $disabled_action=(strlen($kd_sales)==0 && $bukanCabang=='Y')?'disabled-action':"";
?>
    <form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/simpan_sales');?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo ($kd_sales=='')?'Add':'Edit';?> Salesman</h4>
    </div>

    <div class="modal-body">
        <div class="row">
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Nama Dealer <?php //echo $bukanCabang."-".strlen($kd_sales)."-".$disabled_action;?></label>
                    <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
                        <option value="">--Pilih Dealer--</option>
                        <?php
                            if(isset($dealer)){
                                if($dealer->totaldata>0){
                                    foreach ($dealer->message as $key => $value) {
                                        $select=($kd_dealer==$value->KD_DEALER)? "selected":"";
                                        echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
        		</div>
        	</div>
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class="form-group">
        			<label></label>
        		</div>
        	</div>
    	</div>
    	<div class="row">
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Kode Sales</label>
        			<input type="text" id="kd_sales" name="kd_sales" class="form-control <?php echo $disabled_action;?>" value='<?php echo $kd_sales;?>'>
        		</div>
        	</div>
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Kode Honda ID</label>
        			<input type="text" id="kd_hsales" name="kd_hsales" class="form-control <?php echo $disabled_action;?>" value='<?php echo $kd_hsales;?>'>
        		</div>
        	</div>
        </div>
        <div class="row">
        	<div class="col-xs-12 col-sm-6 col-md-6" style="border: 0px solid">
        		<div class='form-group' style="border: 0px solid">
        			<label>NIK</label>
        			<input type="text" id="nik_sales" name="nik_sales" class="form-control <?php echo $disabled_action;?>" placeholder='Pilih NIK' value='<?php echo $nik_sales;?>'>
        		</div>
        	</div>
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Nama Salesman</label>
        			<input type="text" id="nama_sales" name="nama_sales" class="form-control <?php echo $disabled_action;?>" value='<?php echo $nama_sales;?>' autocomplete="off">
        		</div>
        	</div>
            </div>
        <div class="row">
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Kode Jabatan</label>
        			<select id="kd_jabatan" name="kd_jabatan" class="form-control <?php echo $disabled_action;?>" value='<?php echo $kd_jabatan;?>'>
                        <option value="">--Pilih Kode Jabatan--</option>
                        <?php 
                        if(isset($kdjb)){
                            if($kdjb->totaldata>0){
                                foreach ($kdjb->message as $key => $value) {
                                    $select=($kd_jabatan==$value->KD_JABATAN)?'selected':'';
                                    echo "<option value='".$value->KD_JABATAN."' ".$select.">".$value->KD_JABATAN."</option>";
                                }
                            }
                        }
                        ?>
        			</select>
        		</div>
        	</div>
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Personal Jabatan</label>
        			<select id="ps_jabatan" name="ps_jabatan" class="form-control <?php echo $disabled_action;?>" value='<?php echo $ps_jabatan;?>'>
        			 <option value="">--Pilih Personal Jabatan--</option>
                        <?php 
                        if(isset($psjb)){
                            if($psjb->totaldata>0){
                                foreach ($psjb->message as $key => $value) {
                                    $select=($ps_jabatan==$value->PERSONAL_JABATAN)?'selected':'';
                                    echo "<option value='".$value->PERSONAL_JABATAN."' ".$select.">".$value->PERSONAL_JABATAN."</option>";
                                }
                            }
                        }
                        ?>
                    </select>
        		</div>
        	</div>
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Group Sales</label>
        			<select id="kd_group" name="kd_group" class="form-control <?php echo $disabled_action;?>">
                        <option value="">--Pilih Group Sales--</option>
                        <?php
                        //var_dump($gs);
                            if(isset($gs)){
                                if($gs->totaldata>0){
                                    foreach ($gs->message as $key => $value) {
                                        $selected=($kd_group==$value->GROUP_SALES)?"selected":"";
                                        echo "<option value='".$value->GROUP_SALES."' ".$selected.">".$value->GROUP_SALES."</option>";
                                    }
                                }
                            }
                        ?>
        			</select>
        		</div>
        	</div>
        	<div class="col-xs-12 col-sm-6 col-md-6">
        		<div class='form-group'>
        			<label>Status</label>
        			<select id="status_sales" name="status_sales" class="form-control">
        				<option value='A' <?php echo ($status_sales=='A')?'selected':'';?>>Aktif</option>
                        <option value='X' <?php echo ($status_sales=='X')?'selected':'';?>>Non Aktif</option>
        			</select>
        		</div>
        	</div>
    	</div>
    
    </div>
    <div class="modal-footer">
    	<button type="button" id="keluar" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close fa-fw'></i> Keluar</button>
        <button type="submit" id="submit-btn" class="btn btn-danger"><i class="fa fa-save"></i> Update</button>
        </button>

    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        
        $.getJSON('<?php echo base_url();?>company/k2sales/<?php echo $nik_sales;?>',function(result){
            var datax =[];
            $.each(result,function(e,d){
                datax.push({
                    'text':d.NIK,
                    'value' : d.NIK,
                    'NAMA' : d.NAMA,
                    'NIK' : d.NIK,
                    'KDJAB': d.KD_JABATAN,
                    'PJAB' : d.PERSONAL_JABATAN
                })
            })
            $('#nik_sales').inputpicker({
                data : datax,
                fields :['NIK','NAMA',"KDJAB",'PJAB'],
                fieldText : 'text',
                fieldValue : 'value',
                filterOpen: true,
                headShow:true
            })
        })
    })
</script>