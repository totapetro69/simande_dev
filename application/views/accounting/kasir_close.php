<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('cashier/close_cash/');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Close Transaksi : </h4>
</div>
<?php
  $hariIni=date("d/m/Y");
  $tgltrans=date("d/m/Y");$saldo_awal=0;$total_terima=0;$total_keluar=0;$saldo_akhir=0;$id_trans=0;
  if(isset($list)){
    if($list->totaldata>0){
      foreach ($list->message as $key => $value) {
          $total_terima +=($value->TYPE_TRANS=='Penerimaan' AND $value->JENIS_TRANS !='Penerimaan Barang')?($value->JUMLAH*$value->HARGA):0;
          $total_keluar +=($value->TYPE_TRANS=='Pengeluaran')?($value->JUMLAH*$value->HARGA):0;
          $saldo_akhir = $value->SALDO_AKHIR;
      }
    }
  }
  //print_r($last);
  if(isset($last)){
    if($last->totaldata>0){
      foreach ($last->message as $key => $value) {
        $tgltrans=tglFromSql($value->OPEN_DATE);
        $saldo_awal = $value->SALDO_AWAL;
        $id_trans = $value->ID;
      }
    }
  }
  //print_r($last);
  $tgltrans=($tgltrans)?$tgltrans:date("d/m/Y");
  $saldo_akhir =($saldo_akhir==0)?($saldo_awal):$saldo_akhir;
?>
<!-- <div class="col-xs-12 col-md-8 col-sm-12 col-md-offset-2"> -->
  <div class="modal-body">
    <div class="form-group">
      <label>Dealer</label>
     <input id="nama_dealer" type="text" name="nama_dealer" class="form-control" value='<?php echo $this->session->userdata("nama_dealer");?>' readonly required>
     <input type="hidden" id="kd_dealer" name="kd_dealer" value="<?php echo $this->session->userdata("kd_dealer");?>" required="true">
     <input type="hidden" id="id_trans" name="id_trans" value="<?php echo $id_trans;?>" required="true">
    </div>

    <div class="form-group">
      <label>Open Date</label>
      <input type="text" name="open_date" class="form-control" placeholder="dd/mm/yyyy"  value="<?php echo $tgltrans;?>" readonly>
    </div>
    <div class="form-group hidden">
      <label>Saldo Awal</label>
      <input id="saldo_awal" type="text" name="saldo_awal" class="form-control" placeholder="Saldo Awal" value="<?php echo number_format($saldo_awal,0);?>" data-mask="#,##0" data-mask-reverse="true" required readonly="true">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 no-margin-l hidden">
      <div class="form-group">
        <label>Total Penerimaan</label>
        <input id="total_penerimaan" type="text" name="total_penerimaan" class="form-control" placeholder="Total Penerimaan" value="<?php echo number_format($total_terima,0);?>" data-mask="#,##0" data-mask-reverse="true" required="true" readonly="true">
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 no-margin-r hidden">
      <div class="form-group">
        <label>Total Pengeluaran </label>
        <input id="total_pengeluaran" type="text" name="total_pengeluaran" class="form-control" placeholder="Total Penerimaan" value="<?php echo number_format($total_keluar,0);?>" data-mask="#,##0" data-mask-reverse="true" autocomplete="off" >
      </div>
    </div>
     <div class="form-group hidden">
      <label>Transaksi PKB Blm Selesai </label>
      <input id="total_setoran" type="text" name="total_setoran" class="form-control hidden" placeholder="Total Setor Ke KAS" value="<?php echo number_format($total_keluar,0);?>" data-mask="#,##0" data-mask-reverse="true" autocomplete="off" >
      <input id="ada_pkb" type="text" name="ada_pkb" class="form-control" value="<?php echo isset($pkb)?$pkb->totaldata:"0";?>">
      <input id="ada_lkh" type="text" name="ada_lkh" class="form-control" value="<?php echo isset($lkh)?$lkh->totaldata:"0";?>">
    </div>
    <div class="form-group">
      <label>Saldo Akhir</label>
      <input id="saldo_akhir" type="text" name="saldo_akhir" class="form-control" placeholder="saldo akhir" value="<?php echo number_format($saldo_akhir,0);?>" data-mask="#,##0" data-mask-reverse="true" requierd readonly="true">
    </div>

    <div class="form-group">
      <label>Keterangan</label>
      <input id="keterangan" type="text" name="keterangan" class="form-control" placeholder="Keterangan" >
    </div>
  </div>

  <div class="modal-footer">
    <di class="pull-left">
      <a href="<?php echo base_url().'cashier/reopen/1/true?id='.$id_trans;?>" id="btn_reopens" class="btn btn-info hidden"><i class="fa fa-cogs"></i> Re Transaksi </a>
    </di>
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
    <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn"><i class='fa fa-save'></i> Proses</button>
  </div>
<!-- </div> -->

</form>
<script type="text/javascript">
  $(document).ready(function(e){
    if($('#ada_pkb').val()!="0" || $('#ada_lkh').val()!="0"){
      var pkb =($('#ada_pkb').val()!="0")?" \nMasih Ada Transaksi PKB Yang masih blm di selesaikan!":"";
      var lkh =($('#ada_lkh').val()!="0")?" \nMasih Ada Transaksi yang gantung - Cek via menu seleksi transaksi":"";
      var bukanHariini="<?php echo $tgltrans;?>";
      var hariNow="<?php echo $hariIni;?>"
      alert("Transaksi periode ini tidak bisa di close"+pkb+lkh);
      $('#submit-btn').addClass("disabled-action");
      if($('#ada_pkb').val()!="0"){
        if(hariNow!=bukanHariini){
          $('#btn_reopens').removeClass("hidden");
        }
        
      }
      
      return false;
    }

    cek_saldoAkhir();
    $('#keterangan').on("keyup",function(){
      if($(this).val().length>2){
        $('#submit-btn').removeClass("disabled-action");
      }
    })
    /*$('#total_setoran').on("keypress",function(e){
      //e.preventDefault();
      if(e.which==13){
        e.preventDefault();
        var saldo_awal=$('#saldo_awal').val().replace(/,/g,"");;
        var transterima=$('#total_penerimaan').val().replace(/,/g,"");
        var keluar=$('#total_pengeluaran').val().replace(/,/g,"");
        var setor =$('#total_setoran').val().replace(/,/g,"");
        var saldo_akhir =(parseFloat(saldo_awal)+parseFloat(transterima))-(parseFloat(keluar)+parseFloat(setor));
        $('#saldo_akhir').val(saldo_akhir.toLocaleString());
        $('#saldo_akhir').focus().select();
      }
    }).focus(function(){$(this).select()})
    .focusout(function(e){
      $(this).mask('#,##0',{reverse:true})
    });

    $('#total_pengeluaran').on("keypress",function(e){
      if(e.which==13){
        e.preventDefault();
        var saldo_awal=$('#saldo_awal').val().replace(/,/g,"");;
        var transterima=$('#total_penerimaan').val().replace(/,/g,"");
        var keluar=$('#total_pengeluaran').val().replace(/,/g,"");
        var setor =$('#total_setoran').val().replace(/,/g,"");
        var saldo_akhir =(parseFloat(saldo_awal)+parseFloat(transterima))-(parseFloat(keluar)+parseFloat(setor));
        $('#saldo_akhir').val(saldo_akhir.toLocaleString());
        $('#total_setoran').focus().select();

      }
    })
    .focus(function(){$(this).select()})
    .focusout(function(e){
      $(this).mask('#,##0',{reverse:true})
    });*/
    $('#btn_reopen').on('click',function(e){
      $.getJSON("<?php echo base_url('cashier/reopen/1/true');?>",{'id':'<?php echo $id_trans;?>','keterangan':'masih ada transaksi yang gantung'},function(result){
        console.log(result);
      })
    });
  })
  function cek_saldoAkhir(){
    var sak=$('#saldo_akhir').val().replace(/,/g,'');
      if(parseFloat(sak)>1000000){
        if($('#keterangan').val()==''){
          alert ("Saldo Akhir lebih dari 1 juta\nKolom keterangan wajib di isi")
          $('#submit-btn').addClass("disabled-action");
        }
      }
  }
  function __transaksiLagi(id){
    $.getJSON("<?php echo base_url('cashier/reopen/1/true');?>",{'id':id,'keterangan':'masih ada transaksi yang gantung'},function(result){
      console.log(result);
    })
  }
</script>
