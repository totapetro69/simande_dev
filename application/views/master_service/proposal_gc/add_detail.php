<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Input Tipe Motor</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/detail_add_proposal_gc_simpan');?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <input type="hidden" name="jenis_trans" id="jenis_trans" class="form-control" value="<?php echo  $list->message[0]->JENIS_TRANS; ?>" >
    <div class="form-group">
      <label>Nomor Proposal</label>
      <input type="text" name="no_pro" id="no_pro" class="form-control" value="<?php echo $list->message[0]->NO_TRANS; ?>" disabled="disabled" readonly>
    </div>
    <div class="form-group">
      <label>Kode Master GC</label>
      <input type="text" name="kd_gc" id="kd_gc" class="form-control" value="<?php echo $list->message[0]->KD_GC; ?>" disabled="disabled" readonly>
    </div>

    <div class="form-group">
      <label>Kode Tipe Motor</label>
      <select name="kd_typemotor" id="kd_typemotor" class="form-control" required>
        <option value="" >- Pilih Tipe Motor -</option>
        <?php if($gc && (is_array($gc->message) || is_object($gc->message))): foreach ($gc->message as $key => $value) : ?>
          <option value="<?php echo $value->KD_TYPEMOTOR;?>"><?php echo $value->KD_TYPEMOTOR;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>

    <div class="form-group">
      <label>Qty</label>
      <input type="text" name="qty" id="qty" class="form-control" placeholder="0" value="0" required>
    </div>

    <?php if($list->message[0]->JENIS_TRANS == 'TUNAI'){
      ?>
      <div class="form-group">
      <label>SK AHM</label>
      <input type="text" name="s_ahm" id="s_ahm" class="form-control" placeholder="0" value="0" disabled="disabled" readonly>
    </div>

    <div class="form-group">
      <label>SK MD</label>
      <input type="text" name="s_md" id="s_md" class="form-control" placeholder="0" value="0" disabled="disabled" readonly>
    </div>

    <div class="form-group">
      <label>SK SD</label>
      <input type="text" name="s_sd" id="s_sd" class="form-control" placeholder="0" value="0" disabled="disabled" readonly>
    </div>
    <div class="form-group">
      <label>SK Finance</label>
      <input type="text" name="sk_finance" id="sk_finance" class="form-control" value="0" placeholder="0" disabled="disabled" readonly>
    </div>
    <div class="form-group">
      <label>SC AHM</label>
      <input type="text" name="sc_ahm" id="sc_ahm" class="form-control " value="0" placeholder="0" >
    </div>
    <div class="form-group">
      <label>SC MD</label>
      <input type="text" name="sc_md" id="sc_md" class="form-control " value="0" placeholder="0" >
    </div>

    <div class="form-group">
      <label>SC SD</label>
      <input type="text" name="sc_sd" id="sc_sd" class="form-control " value="0" placeholder="0" >
    </div>
      <?php
    }else{
      ?>
      <div class="form-group">
      <label>SK AHM</label>
      <input type="text" name="s_ahm" id="s_ahm" class="form-control" placeholder="0" value="0">
    </div>

    <div class="form-group">
      <label>SK MD</label>
      <input type="text" name="s_md" id="s_md" class="form-control" placeholder="0" value="0">
    </div>

    <div class="form-group">
      <label>SK SD</label>
      <input type="text" name="s_sd" id="s_sd" class="form-control" placeholder="0" value="0" >
    </div>
    <div class="form-group">
      <label>SK Finance</label>
      <input type="text" name="sk_finance" id="sk_finance" class="form-control" value="0" placeholder="0" >
    </div>
    <div class="form-group">
      <label>SC AHM</label>
      <input type="text" name="sc_ahm" id="sc_ahm" class="form-control " value="0" placeholder="0" disabled="disabled" readonly>
    </div>
    <div class="form-group">
      <label>SC MD</label>
      <input type="text" name="sc_md" id="sc_md" class="form-control " value="0" placeholder="0" disabled="disabled" readonly>
    </div>

    <div class="form-group">
      <label>SC SD</label>
      <input type="text" name="sc_sd" id="sc_sd" class="form-control " value="0" placeholder="0" disabled="disabled" readonly>
    </div>
      <?php
    }
    ?>

    <div class="form-group">
      <label>Harga Kontrak</label>
      <input type="text" name="harga_kontrak" id="harga_kontrak" class="form-control" value="0" placeholder="0" >
    </div>


    <div class="form-group">
      <label>Fee</label>
      <input type="text" name="fee" id="fee" class="form-control" value="0" placeholder="0">
    </div>


    <div class="form-group">
      <label>Pengurusan STNK</label>
      <input type="text" name="pengurusan_stnk" id="pengurusan_stnk" class="form-control" value="0" placeholder="0">
    </div>


    <div class="form-group">
      <label>Pengurusan BPKB</label>
      <input type="text" name="pengurusan_bpkb" id="pengurusan_bpkb" class="form-control" value="0" placeholder="0">
    </div>

  </form>

</div>

<div class="modal-footer">
  <a class="btn btn-default" href="<?php echo base_url('setup/detail_proposal_gc/'.$list->message[0]->ID);?>">Batal</a>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
  $(document).ready(function(e){
    $('#qty')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#s_ahm')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#s_md')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#s_sd')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#sk_finance')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#sc_ahm')
    .focusout(function(){
    })

    .ForceNumericOnly()


    $('#sc_md')
    .focusout(function(){
    })

    .ForceNumericOnly()


    $('#sc_sd')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#harga_kontrak')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#fee')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#pengurusan_stnk')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#pengurusan_bpkb')
    .focusout(function(){
    })

    .ForceNumericOnly()


    $("#keyword_q").typeahead({
     source:function(query,process){
      $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
      return $.get('<?php echo base_url("motor/typemotor_typeahead");?>',{keyword:query},function(data){
        console.log(data);
        data=$.parseJSON(data);
        $('#fd').html('');
        return process(data.keyword);
      })
    },
    minLength:1,
    limit:20
  });

$("#kd_typemotor").change(function(){
      var kd_typemotor = $(this).val();
      var kd_gc = $("#kd_gc").val();
      var jenis_trans = $("#jenis_trans").val();
            $.getJSON("<?php echo base_url("setup/get_gc_new");?>",
                {'kd_gc':kd_gc, 'kd_typemotor':kd_typemotor},
                  function(result){
                    if(result.status == true){
                      $.each(result.message,function(e,d){
                      //console.log(d);
                      if(jenis_trans == "TUNAI"){
                      $("#sc_ahm").val(d.S_AHM);
                      $("#sc_md").val(d.S_MD);
                      $("#sc_sd").val(d.S_SD);
                    }else{
                      $("#s_ahm").val(d.S_AHM);
                      $("#s_md").val(d.S_MD);
                      $("#s_sd").val(d.S_SD);
                    }

                   })
                   }
                }
                )
          });
  });

</script>