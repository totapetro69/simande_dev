<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Tambah Promo Program</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/detail_add_promoprogram_simpan'); ?>">
        <div class="form-group">
            <label>Kode Promo Program</label>
            <input type="text" name="kd_detailpromo" id="kd_detailpromo" class="form-control" value="<?php echo $list->message[0]->KD_PROMO; ?>" disabled="disabled">
        </div>
        <div id="ajax-url-filter" url="<?php echo base_url('master_service/jasaa_typeahead');?>">

        </div>  
        <div class="form-group" id="pekerjaan">
            <label>Kode Pekerjaan <span id="fd"></span></label>
            <input id="kd_pekerjaan" type="text" name="kd_pekerjaan" class="form-control" placeholder="0">
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="text" name="harga" id="harga" class="form-control" placeholder="Masukkan harga" >
        </div>
        <div id="ajax-url-filter" url="<?php echo base_url('sparepart/part_typeahead');?>">

        </div>
        <div class="form-group" id="part_number">
            <label>Nomor Part <span id="fd"></span></label>
            <input id="part_no" type="text" name="part_no" class="form-control" placeholder="0">
        </div>
        <div class="form-group">
            <label>Harga Part Number</label>
            <input type="text" name="harga_no_part" id="harga_no_part" class="form-control" placeholder="Masukkan harga nomor part" >
        </div>		

    </form>

</div>

<div class="modal-footer">
    <a class="btn btn-default" href="<?php echo base_url('master_service/detail_promoprogram/'.$list->message[0]->KD_PROMO);?>">Batal</a>
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

        $("#kd_pekerjaan").typeahead({
                 source:function(query,process){
                  $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
                  return $.get('<?php echo base_url("master_service/jasa_promo");?>',{keyword:query},function(data){
                    console.log(data);
                    data=$.parseJSON(data);
                    $('#fd').html('');
                    return process(data.keyword);
                })
              },
              minLength:2,
              limit:20
          });

        $('#kd_pekerjaan').on('change', function () {
           
        })

       /* $("#part_no").typeahead({

                   source:function(query,process){
                    var res = "00000BA023";
                    var param = res.substring(5,7);
                    $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
                    return $.get('<?php echo base_url("Sparepart/part_jasa/");?>',{para:param,keyword:query},function(data){
                      console.log(data);
                      data=$.parseJSON(data);
                      $('#fd').html('');
                      return process(data.keyword);
                  })
                },
                minLength:3,
                limit:20
            });*/

          //$('#kd_pekerjaan').on('change', function () {
              $("#part_no").typeahead({
                   source:function(query,process){
                    var res = $('#kd_pekerjaan').val();
                    var param = res.substring(5,7);
                    $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
                    return $.get('<?php echo base_url("Sparepart/part_jasa/");?>',{para:param,keyword:query},function(data){
                      console.log(data);
                      data=$.parseJSON(data);
                      $('#fd').html('');
                      return process(data.keyword);
                  })
                },
                minLength:3,
                limit:20
            });
          //})
              
        
        
    });

</script>

