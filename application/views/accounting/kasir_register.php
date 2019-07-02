<form>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="myModalLabel">Register Pembayaran </h4>
	</div>
	<div class="modal-body">     
		<div class="row ">
		    <table class="table table-stripped" border="0">
                <thead >
                    <tr>
                        <th style="width:4%">No.</th>
                        <th style="width:4%" class="hidden"></th>
                        <th style="width:10%; white-space: nowrap;">No. Transaksi</th>
                        <th style="width:5%; white-space: nowrap;">Tanggal</th>
                        <th style="width:10%; white-space: nowrap;">NO.Reff</th>
                        <th style="width:5%; white-space: nowrap;">Cara Bayar</th>
                        <th colspan="4">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no_trans="";$tgl="";$keterangan="";$jenis=""; $kredit=0;$debet=0;
                        if(isset($listlkh)){ 
                            $n=0; $no_trans="";$valID=0;
                            if($listlkh->totaldata > 0){
                                foreach ($listlkh->message as $key => $value) {
                                    $n++;$jml_detail=0;
                                    if(isset($listd[$value->NO_TRANS])){ 
                                        $jml_detail=$listd[$value->NO_TRANS]->totaldata;
                                    }
                                    $no_trans=$value->NO_TRANS;
                                    $tgl=tglFromSql($value->TGL_TRANS);
                                    $jenis=$value->JENIS_TRANS;
                                    $valID = $value->ID;
                                    if($jml_detail==0){ continue ;}
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $n;?></td>
                                        <td class="text-center hidden">
                                            <a onclick="__showDetail('m_<?php echo $value->ID;?>');" title="Seleksi transaksi"><i class="fa fa-ioxhost"></i></a>

                                        </td>
                                        <td class="td-overflow"><?php echo $value->NO_TRANS;?></td>
                                        <td class="text-center"><?php echo tglFromSql($value->TGL_TRANS,"/");?></td>
                                        <td class="text-left td-overflow" title="<?php echo $value->NO_REFF?>"><?php echo $value->NO_REFF?></td>
                                        <td><?php echo $value->CARA_BAYAR;?></td>
                                        <td colspan="4"><span>
                                            <?php 
                                                if($value->CARA_BAYAR !='Cash'){
                                                    ?>
                                                    <ul class="list-inline">
                                                        <li><?php echo "Nama BanK : ".$value->NAMA_BANK ;?></li>
                                                        <li><?php echo "No.Rek : ".$value->NO_REKENING;?></li>
                                                        <li><?php echo "No.Cek : ".$value->NO_CHEQUE;?></li>
                                                        <li><?php echo "Jatuh Tempo: ".tglFromSql($value->JTH_TEMPO);?></li>
                                                    </ul>

                                            <?php
                                                }
                                            ?></span>   
                                        </td>
                                    </tr>
                                    <tr class="m_<?php echo $value->ID;?>">
                                        <td colspan="9"> Detail Transaksi : <?php echo $value->TYPE_TRANS;?></td>
                                    </tr>
                                    <tr class="m_<?php echo $value->ID;?> info ">
                                        <td>No</td>
                                        <td class="hidden">
                                            <a onclick="__posting('<?php echo $value->NO_TRANS;?>','<?php echo $value->ID;?>');" title="Posting transaksi"><i class="fa fa-cogs"></i></a>
                                        </td>
                                        <td colspan="3">Uraian Transaksi</td>
                                        <td>Jumlah</td>
                                        <td style="white-space: nowrap;">Harga</td>
                                        <td style="white-space: nowrap;">Total Harga</td>
                                        <!-- <td>Status</td> -->
                                        <td colspan="2">Kode Akun</td>
                                    </tr>
                                    <?
                                    $keterangan=$value->TYPE_TRANS;
                                    if(isset($listd[$value->NO_TRANS])){ $x=0;
                                        if($listd[$value->NO_TRANS]->totaldata >0){
                                            foreach ($listd[$value->NO_TRANS]->message as $key => $val) {
                                                
                                                $kredit +=($jenis=='Pengeluaran' || $value->TYPE_TRANS=='Penerimaan Barang')?($val->JUMLAH*$val->HARGA):0;
                                                $debet +=($jenis=='Pengeluaran' || $value->TYPE_TRANS=='Penerimaan Barang')?0:($val->JUMLAH*$val->HARGA);
                                                $x++;
                                                ?><tr id="r_<?php echo $value->ID."_".$x;?>" class="m_<?php echo $value->ID;?> pos_<?php echo $value->ID;?>">
                                                    <td class="hidden"><?php echo $val->ID;?></td>
                                                    <td class="hidden"><?php echo($val->POS_AKUN!='')? $val->POS_AKUN:$val->DEFAULT_AC;?></td>

                                                    <td class='text-right'><?php echo $x;?></td>
                                                    <td style="white-space: nowrap;" class="hidden">
                                                        <a onclick="__edit('<?php echo $val->ID;?>');" title="edit transaksi"><i class='fa fa-edit'></i></a>
                                                        <a onclick="__delete('<?php echo $val->ID;?>');" title="Hapus transaksi"><i class='fa fa-trash'></i></a>
                                                    </td>
                                                    <td colspan="3"><?php echo $value->JENIS_TRANS;?>&nbsp;<?php echo $val->URAIAN_TRANSAKSI;?></td>
                                                    <td class='text-right'><?php echo number_format($val->JUMLAH,0);?></td>
                                                    <td class='text-right'><?php echo number_format($val->HARGA,0);?></td>
                                                    <td class='text-right'><?php echo number_format(($val->JUMLAH * $val->HARGA),0);?></td>
                                                    <!-- <td></td> -->
                                                    <td colspan="2" title="<?php echo $val->NAMA_AKUN;?>"><?php echo trim($val->KD_ACCOUNT);?>
                                                        [<?php echo($val->POS_AKUN!='')? $val->POS_AKUN:$val->DEFAULT_AC;?>]</td>

                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <tr class="m_<?php echo $value->ID;?> hidden"><td colspan="9">&nbsp;</td></tr>
                                    <?php
                                }
                            }
                        }
                    ?>

                </tbody>
            </table>
            <div id="printarea" style="margin-top: -12px;font-size:smaller !important;"><smaller><?php echo $no_trans." ".$tgl." ".$keterangan."-".$jenis." ".$this->session->userdata("user_name")." ".date("d/m/Y")." ".$debet." ".$kredit;?></div></smaller>
            <input type="hidden" id="sts_reopen" name="sts_reopen" value="<?php echo (isset($reopen))?$reopen:"";?>">
        </div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
   		<button type="button" onclick="__posting('<?php echo $no_trans;?>','<?php echo $valID;?>');" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
	</div>
</form>
<script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
	$(document).ready(function(){
		$('#print_kwts').hide();
		$('#myModalLg').on("hidden.bs.modal",function(){
			$('#print_kwts').show();
		})
	})
	function __posting(notran,id){
        var datax=[];
        printJS('printarea','html');
        if(confirm('Print Register')){
            var trow=$('tr.pos_'+id).length;
            for(i=0;i<trow;i++){
                datax.push({
                    'id':$("tr#r_"+id+"_"+(parseInt(i)+1)+" > td:eq(0)").text(),
                    'pos_akun':$("tr#r_"+id+"_"+(parseInt(i)+1)+" > td:eq(1)").text(),
                    'no_trans':notran
                })
            }
            /*console.log(trow);
            console.log(datax);*/
            $.ajax({
                type:'POST',
                url :"<?php echo base_url("cashier/posting_trans");?>",
                data : {'dt':JSON.stringify(datax)},
                dataType :'json',
                success:function(result){
                    if (result.status == true) {
                        //simpan register
                        __simpan_register();
                        $('.success').animate({top: "0"}, 500);
                        $('.success').html(result.message).fadeIn();
                        var reopen=$('#sts_reopen').val().split("x");
                        //console.log(reopen);
                        if(Array.isArray(reopen)){
                            if(reopen[0]=="1"){
                                $.ajax({
                                    type :'GET',
                                    url : http +"/cashier/reopen/0",
                                    data:{'id':reopen[1],'ket':'reopen'},
                                    dataType :'json',
                                    success : function(datas){

                                    }
                                })
                            }
                        }

                        if (result.location != null) {
                            setTimeout(function() {
                                location.replace(result.location)
                            }, 2000);
                        } else {
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    } else {

                        $('.error').animate({
                            top: "0"
                        }, 500);
                        $('.error').html(result.message).fadeIn();

                        setTimeout(function() {
                            hideAllMessages();
                            $('#loadpage').addClass("hidden");
                        }, 4000);
                    }
                }
            })
        }
    }
    function __simpan_register(){
        var reg= $('#printarea').text();
        $.ajax({
            type: 'POST',
            url : http+"/cashier/simpan_register",
            data :{'regtext':reg,'no_trans':'<?php echo $no_trans;?>','no_kwt':''},
            dataType :'json',
            success:function(result){

            }
        })
    }
</script>