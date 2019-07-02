<form action="#" id="import-form" class="bucket-form">
 
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah File .UMINV</h4>
    </div>
 
    <div class="modal-body">
 
        <div class="form-group">
            <label class="custom-file col-xs-12">Select File .UMINV </label>
            <div class="input-group-append">
                <input accept=".uminv" type="file" id="file" class="custom-file-control form-control" name="file" placeholder="Choose file">
                <span class="custom-file-control" data-attr="Choose file..."></span>
            </div>
        </div>
    </div>
 
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Simpan</button>
    </div>
 
</form>
<script>
        $(function(){
            $('input[name="file"]').on('change',function(e){
                var filename = document.getElementById("file").files[0].name;
                $(this).next().attr('data-attr',filename);
            })
            $('#import-form').on('submit',function(e){
                e.preventDefault();
                var $btn = $(this).find('button[type="submit"]');
                var formdata = new FormData(this);
                var url_gm = '<?php echo base_url("master_service/import_uminv");?>';
                $.ajax({
                    url: url_gm,
                    type: 'POST',
                    method: 'POST',
                    dataType: 'JSON',
                    data:formdata,
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $btn.button('loading');
                    },
                    success:function(response){
                        $('.form-group.has-error').removeClass('has-error').find('span.text-danger').remove();
                        switch(response.status){
                            case 'form-incomplete':
                                $.each(response.errors, function(key,val){
                                    if(val.error!=''){
                                        $(val.field).closest('.form-group').addClass('has-error').append(val.error);
                                    }
                                })
                            break;
                            case 'success':
                                $('#import-form').modal('hide')
                                window.location.reload(true);
                            break;
                            case 'error':
                                console.log(response.message);
                            break;
                        }
                    },
                    error: function(jqXHR,textStatus,error){
                        console.log('Unable to send request!');
                    }
                }).always(function(){
                    //$('#import-form').modal('hide')
                    window.location.reload(true);
                    $btn.button('reset');
                });
            })
        })
    </script>