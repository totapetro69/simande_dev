<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Detail</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/detail_update_promoprogram/' . $list->message[0]->ID); ?>">
        <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <div class="form-group">
            <label>No Guest</label>
            <input type="text" name="guest_no" id="guest_no" class="form-control" value="<?php echo  $list->message[0]->GUEST_NO; ?>" disabled="disabled">
        </div>

        </div>  
        <div class="form-group">
            <label>Source</label>
            <input type="text" name="guest_source" id="guest_source" class="form-control" placeholder="Masukkan Guest Source" value="<?php echo  $list->message[0]->GUEST_SOURCE; ?>">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="row_status" class="form-control">
                  <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> </option>
                  <?php
                  if($list->message[0]->ROW_STATUS == 0){
                  ?>
                  <option value="-1">Tidak Aktif</option>
                  <?php
                  }else{
                  ?>
                  <option value="0">Aktif</option>
                  <?php
                  }
                  ?>
            </select>
        </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(e){

        $('#harga')
        .focusout(function(){
        })

        .ForceNumericOnly()

        $('#harga_no_part')
        .focusout(function(){
        })

        .ForceNumericOnly()

        $("#keyword_q").typeahead({
         source:function(query,process){
          $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get('<?php echo base_url("Sparepart/part_typeahead");?>',{keyword:query},function(data){
            console.log(data);
            data=$.parseJSON(data);
            $('#fd').html('');
            return process(data.keyword);
        })
      },
      minLength:3,
      limit:20
  });
        $("#keyword_qu").typeahead({
         source:function(query,process){
          $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get('<?php echo base_url("master_service/jasaa_typeahead");?>',{keyword:query},function(data){
            console.log(data);
            data=$.parseJSON(data);
            $('#fd').html('');
            return process(data.keyword);
        })
      },
      minLength:3,
      limit:20
  });
    });

</script>

