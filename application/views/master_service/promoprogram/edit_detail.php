<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Detail Promo Program</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/detail_update_promoprogram/' . $list->message[0]->ID); ?>">
    	<input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <div class="form-group">
            <label>Kode Promo Program</label>
            <input type="text" name="kd_detailpromo" id="kd_detailpromo" class="form-control" value="<?php echo  $list->message[0]->KD_DETAILPROMO; ?>" disabled="disabled">
        </div>
        <div id="ajax-url-filter" url="<?php echo base_url('master_service/jasaa_typeahead');?>">

        </div>  
        <div class="form-group" id="pekerjaan">
            <label>Kode Pekerjaan <span id="fd"></span></label>
            <input id="keyword_qu" type="text" name="kd_pekerjaan" class="form-control" placeholder="0" value="<?php echo  $list->message[0]->KD_PEKERJAAN; ?>" disabled="disabled">
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="text" name="harga" id="harga" class="form-control" placeholder="Masukkan harga" value="<?php echo  $list->message[0]->HARGA; ?>">
        </div>
        <div id="ajax-url-filter" url="<?php echo base_url('sparepart/part_typeahead');?>">

        </div>
        <div class="form-group" id="part_number">
            <label>Nomor Part <span id="fd"></span></label>
            <input id="keyword_q" type="text" name="part_no" class="form-control" placeholder="0" value="<?php echo  $list->message[0]->NO_PART; ?>" disabled="disabled">
        </div>
        <div class="form-group">
            <label>Harga Part Number</label>
            <input type="text" name="harga_no_part" id="harga_no_part" class="form-control" placeholder="Masukkan harga nomor part" value="<?php echo  $list->message[0]->HARGA_NO_PART; ?>">
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

