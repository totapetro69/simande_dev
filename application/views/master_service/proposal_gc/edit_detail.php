<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Detail Proposal Group Customer</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/detail_update_proposal_gc/' . $list->message[0]->ID); ?>">
      <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
      <input type="hidden" name="no_trans" id="no_trans" class="form-control" value="<?php echo  $list->message[0]->NO_TRANS; ?>" >
    	  <div class="form-group">
            <label>Nomor Proposal</label>
            <input type="text" name="no_pro" id="no_pro" class="form-control" value="<?php echo  $list->message[0]->NO_PRO;?>" disabled="disabled" readonly>
        </div>

        <div class="form-group" id="kd_typemotor">
            <label>Kode Tipemotor <span id="fd"></span></label>
            <input type="text" name="kd_typemotor" id="kd_typemotor" class="form-control" value="<?php echo  $list->message[0]->KD_TYPEMOTOR;?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>Qty</label>
          <input type="text" name="qty" id="qty" class="form-control" placeholder="Masukkan QTY" value="<?php echo  round($list->message[0]->QTY); ?>" required>
        </div>
        <?php if($list->message[0]->JENIS_TRANS == 'TUNAI'){
      ?>
        <div class="form-group">
          <label>SK AHM</label>
          <input type="text" name="s_ahm" id="s_ahm" class="form-control" placeholder="Masukkan SK AHM" value="<?php echo round($list->message[0]->S_AHM); ?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>SK MD</label>
          <input type="text" name="s_md" id="s_md" class="form-control" placeholder="Masukkan SK MD" value="<?php echo  round($list->message[0]->S_MD);?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>SK SD</label>
          <input type="text" name="s_sd" id="s_sd" class="form-control" placeholder="Masukkan SK SD" value="<?php echo round($list->message[0]->S_SD); ?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>SK Finance</label>
          <input type="text" name="sk_finance" id="sk_finance" class="form-control" placeholder="Masukkan SK Finance" value="<?php echo  round($list->message[0]->SK_FINANCE); ?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>SC AHM</label>
          <input type="text" name="sc_ahm" id="sc_ahm" class="form-control" placeholder="Masukkan SC AHM" value="<?php echo round($list->message[0]->SC_AHM); ?>">
        </div>

        <div class="form-group">
          <label>SC MD</label>
          <input type="text" name="sc_md" id="sc_md" class="form-control" placeholder="Masukkan SC MD" value="<?php echo  round($list->message[0]->SC_MD); ?>">
        </div>

        <div class="form-group">
          <label>SC SD</label>
          <input type="text" name="sc_sd" id="sc_sd" class="form-control" placeholder="Masukkan SC SD" value="<?php echo round($list->message[0]->SC_SD); ?>">
        </div>
        <?php
    }else{
      ?>
      <div class="form-group">
          <label>SK AHM</label>
          <input type="text" name="s_ahm" id="s_ahm" class="form-control" placeholder="Masukkan SK AHM" value="<?php echo round($list->message[0]->S_AHM); ?>" >
        </div>

        <div class="form-group">
          <label>SK MD</label>
          <input type="text" name="s_md" id="s_md" class="form-control" placeholder="Masukkan SK MD" value="<?php echo  round($list->message[0]->S_MD);?>" >
        </div>

        <div class="form-group">
          <label>SK SD</label>
          <input type="text" name="s_sd" id="s_sd" class="form-control" placeholder="Masukkan SK SD" value="<?php echo round($list->message[0]->S_SD); ?>" >
        </div>

        <div class="form-group">
          <label>SK Finance</label>
          <input type="text" name="sk_finance" id="sk_finance" class="form-control" placeholder="Masukkan SK Finance" value="<?php echo  round($list->message[0]->SK_FINANCE); ?>" >
        </div>

        <div class="form-group">
          <label>SC AHM</label>
          <input type="text" name="sc_ahm" id="sc_ahm" class="form-control" placeholder="Masukkan SC AHM" value="<?php echo round($list->message[0]->SC_AHM); ?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>SC MD</label>
          <input type="text" name="sc_md" id="sc_md" class="form-control" placeholder="Masukkan SC MD" value="<?php echo  round($list->message[0]->SC_MD); ?>" disabled="disabled" readonly>
        </div>

        <div class="form-group">
          <label>SC SD</label>
          <input type="text" name="sc_sd" id="sc_sd" class="form-control" placeholder="Masukkan SC SD" value="<?php echo round($list->message[0]->SC_SD); ?>" disabled="disabled" readonly>
        </div>
      <?php
    }
    ?>

        <div class="form-group">
          <label>Harga Kontrak</label>
          <input type="text" name="harga_kontrak" id="harga_kontrak" class="form-control" placeholder="Masukkan Harga Kontrak" value="<?php echo  round($list->message[0]->HARGA_KONTRAK); ?>">
        </div>

        <div class="form-group">
          <label>FEE</label>
          <input type="text" name="fee" id="fee" class="form-control" placeholder="Masukkan FEE" value="<?php echo round($list->message[0]->FEE); ?>">
        </div>

        <div class="form-group">
          <label>Pengurusan STNK</label>
          <input type="text" name="pengurusan_stnk" id="pengurusan_stnk" class="form-control" placeholder="Masukkan Pengurusan STNK" value="<?php echo  round($list->message[0]->PENGURUSAN_STNK); ?>">
        </div>

        <div class="form-group">
          <label>Pengurusan BPKB</label>
          <input type="text" name="pengurusan_bpkb" id="pengurusan_bpkb" class="form-control" placeholder="Masukkan Pengurusan BPKB" value="<?php echo round($list->message[0]->PENGURUSAN_BPKB); ?>">
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="row_status" class="form-control">
            <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
            <?php
            if($list->message[0]->ROW_STATUS == -1){
              ?>
              <option value="0">Aktif</option>
              <?php
            }else{
              ?>
              <option value="-1">Tidak Aktif</option>
              <?php
            }
            ?>
          </select>
        </div>

      </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
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
        minLength:3,
        limit:20
      });

  $("#kd_typemotor").change(function(){

         var kd_typemotor = $(this).val();
         var kd_gc = $("#kd_gc").val();

         var jenis_trans = $("#jenis_trans").val();
      //alert(jenis_trans);
      //if(jenis_trans == "TUNAI"){
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

