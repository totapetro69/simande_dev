<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  // var_dump(isBolehAkses());exit;

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
  $pilih=$this->input->get('pilih');
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $kd_customer      = "";
  $nama_customer    = "";$jenis="";
  $part_number      = "";
  $part_deskripsi   = "";
  $kd_groupcustomer = "";
  $kd_typecustomer = "";
  $nama_groupcustomer= "";
  // $groupcustomer_mapping="";

  if ($this->input->get('n')!='') {
    if($listcust){
        if(is_array($listcust->message)){
            foreach ($listcust->message as $key => $value) {
                $kd_customer      = $value->KD_CUSTOMER;
                $nama_customer    = $value->KD_CUSTOMER." - ".$value->NAMA_CUSTOMER;
               // $part_number      = $value->PART_NUMBER;
               // $part_deskripsi   = $value->PART_DESKRIPSI;
                $kd_groupcustomer = $value->KD_CUSTOMER;
                $kd_typecustomer = $value->KD_TYPECUSTOMER;
                $nama_groupcustomer= $value->NAMA_GROUPCUSTOMER;
                $jenis= $value->JENIS;
            }
        }
    }
  }
 ?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right"> 
                <a class="btn btn-default" id="baru"><i class="fa fa-file-o fa-fw"></i> Baru </a>
                <a id="submit-btn" type="button" class="btn btn-default <?php echo $status_c; ?>">  
                <i class="fa fa-save fa-fw"></i> Simpan
                </a>
                <a href="<?php echo base_url('part/customer_part');?>" class="btn btn-default $status_v"><i class="fa fa-list"></i> List Customer Part</a>
         </div>

    </div>

<form id="partForm" method="post" action="<?php echo base_url('part/simpan_customer_part_mapping'); ?>" >
    
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading panel-custom">
                <h4 class="panel-title" style="padding-top: 10px"><i class='fa fa-file-o'></i> Input Customer Part</h4>
                <span class="tools pull-right">
                  <!-- <a class="fa fa-chevron-down" href="javascript:;"></a> -->
                </span>
            </div>

        <div class="panel-body panel-body-border">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Dealer</label>
                        <select class="form-control  <?php echo $status_n;?>" id="kd_dealer" name="kd_dealer" required="true" >
                            <option value="">--Pilih Dealer--</option>
                            <?php
                            if (isset($dealer)) {
                                if ($dealer->totaldata>0) {
                                    foreach ($dealer->message as $key => $value) {
                                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                        $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                        echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-6">    
                    <div class="form-group">
                        <label>Jenis Customer</label>
                        <select name="jenis" id="jenis" class="form-control">
                           <option value="">- Pilih Jenis Customer -</option>
                           <option value="Perorangan" <?php echo ($jenis=='Perorangan')?"selected":"";?>>Perorangan</option>
                           <option value="Group" <?php echo ($jenis=='Group')?"selected":"";?>>Group</option>
                        </select>
                    </div>
                </div>
                    
                <div class="col-xs-12 col-md-6 col-sm-6" style='display:none;' id="customer_id">    
                    <div class="form-group" >
                        <div id="ajax-url-customer" typeaheadurl="<?php echo base_url('part/customer_autocomplete/'.$defaultDealer.'/'); ?>">
                        </div>
                            <label>Nama Customer </label>
                            <input type="text" name="kd_customer" value="<?php echo $nama_customer; ?>" id="kd_customer" class="form-control" placeholder="Masukkan nama customer" autocomplete="off" typeaheadurl="<?php echo base_url('part/customer_autocomplete'); ?>" required>
                            <!-- <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $kd_customer;?>"> -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-6 " style='display:none;' id="jenis_group_id">            
                    <div class="form-group">
                        <label>Tipe Group</label>
                        <select id="kd_typecustomer" name="kd_typecustomer" class="form-control" >
                            <option value="" >- Pilih Tipe Group-</option>
                            <?php 
                            if($groupcustomer_mapping):
                            if($groupcustomer_mapping->totaldata>0) 
                            foreach ($groupcustomer_mapping->message as $key => $value) : 
                                $selected=($kd_typecustomer==$value->KD_TYPECUSTOMER)?"selected":"";?>
                            <option value="<?php echo $value->KD_TYPECUSTOMER; ?>" <?php echo $selected;?>><?php echo $value->KD_TYPECUSTOMER;?></option>
                          <?php endforeach; endif;?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 col-sm-6" style='display:none;' id="group_id">            
                    <div class="form-group">
                        <label>Nama Group</label>
                        <select id="kd_groupcustomer" name="kd_groupcustomer" class="form-control" >
                            <option value="" >- Pilih Nama Group-</option>
                        </select>
                    </div>
                </div>
            </div> 
        <div class="row">
            <div class="col-xs-12 col-md-6 col-sm-6">
                <div class="form-group">
                    <label>Part Number</label>

                    <div class="input-group">
                    <input type="text" name="part_number" value="" id="part_number" class="form-control" placeholder="Masukkan nomor part" autocomplete="off" typeaheadurl="<?php echo base_url('part/part_detail_typeahead'); ?>" disabled>
                        <span class="input-group-btn">
                            <button onclick="__addItem();" class="btn btn-primary <?php echo $status_c;?>"  type="button" id="btn-add-sp"><i class="fa fa-plus"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        </div>
    </div>
</div>
</form>


    <div class="col-xs-12 padding-left-right-12">
        <div class="row">
            <!-- <form class="bucket-form" id="addForm" method="post" action="<?php echo base_url("part/simpan_customer_part_mapping");?>" autocomplete="off"> -->
                <div class="col-sm-12">
                    <div class="panel margin-bottom-10">
                        <div class="panel-heading panel-custom">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="panel-title pull-left" style="padding-top: 10px">
                                        List Part Number
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive table table-bordered">
                            <table id="part_list" class="table table-striped b-t b-light  " >
                                <thead>
                                    <tr>
                                        <!-- <th style="width: 40px;">No.</th> -->
                                        <th style="width: 50px">Aksi</th>
                                        <th>Part Number</th>
                                        <th>Part Deskripsi</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                            <?php
                                                if (isset($list) ) {
                                                $no = 0;
                                                if (($list->totaldata>0)) {
                                                    foreach ($list->message as $key => $value){
                                                            # code...
                                                    $no++;
                                                    ?>  
                                                        
                                                        <tr id="l_<?php echo $value->ID;?>">
                                                            <td class="hidden"><?php echo $kd_customer;?></td>
                                                            <td class="hidden"><?php echo $kd_customer;?></td>
                                                            <td class="text-right"><span class='pull-left'><?php echo $no;?></span> <a class='hapus-item' onclick="__hapus_item('<?php echo $value->ID;?>');" role='button'><i class='fa fa-trash'></i></a></td>
                                                            <td><?php echo $value->PART_NUMBER; ?></td>
                                                            <td><?php echo $value->PART_DESKRIPSI; ?></td>
                                                             <td><?php echo $value->JENIS; ?></td>   
                                                        </tr>                                        
                                                    <?php
                                                    }
                                                    }
                                                }
                                        ?> 
                                    </tbody>     
                            </table>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
    <?php echo loading_proses();?>
</section>


<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

    $(document).ready(function(){
        var j="<?php echo $jenis;?>";
        if(j!=''){
            if ( j == 'Perorangan')
              {
                $("#customer_id").show();
                $("#jenis_group_id").hide();
                $("#group_id").hide();
                $("#part_number").removeAttr("disabled").show;
                $("select").addClass("disabled-action");
                $("input:not('#part_number')").addClass("disabled-action");
              }
              
              else 
              {
                $("#customer_id").hide();
                $("#jenis_group_id").show();
                $("#group_id").show();
                $("part_number").show();
                $('#kd_typecustomer').change()
                $("select").addClass("disabled-action");
              }
        }
        //console.log(j);
        $("#jenis").change(function(){
            if ( this.value == 'Perorangan')
              {
                $("#customer_id").show();
                $("#jenis_group_id").hide();
                $("#group_id").hide();
              }
              
              else if(this.value == 'Group')
              {
                $("#customer_id").hide();
                $("#jenis_group_id").show();
                $("#group_id").show();
              }

              else{
                $("#customer_id").hide();
                $("#jenis_group_id").hide();
                $("#group_id").hide();
              }


        });

        $("#jenis, #kd_customer,#kd_groupcustomer").change(function(){
            var grupVal = $('#kd_groupcustomer').val();
            var custVal = $('#kd_customer').val();
            var jenisval = $('#jenis').val();

            // console.log(grupVal+'|'+ custVal+'|'+  jenisval);

            if (jenisval=='Perorangan'&& custVal!='') {
                $("#part_number").removeAttr('disabled');
                $('#kd_groupcustomer').val('');

            }
            else if(jenisval=='Group'&& grupVal!=''){
                $("#part_number").removeAttr('disabled');
                $('#kd_customer').val('');
                
            }
            else{

                $('#kd_groupcustomer').val('');
                $('#kd_customer').val('');
                $("#part_number").val('');
                $("#part_number").attr('disabled','disabled');
            }
            
        });

        var kdgrp="<?php echo $kd_typecustomer;?>";
        if(kdgrp!=''){
            __loadNamaGroup(kdgrp,'<?php echo $kd_groupcustomer;?>');
            $("#part_number").removeAttr("disabled");
        } 

        $("#kd_typecustomer").change(function(){
            __loadNamaGroup($(this).val(),'');
         });


        $("#part_number").typeahead({
            source: function (query, process) {
                $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
                return $.get('<?php echo base_url("sparepart/part_typeahead"); ?>', {keyword: query}, function (data) {
                    // console.log(data);
                    data = $.parseJSON(data);
                    $('#fd').html('');
                    return process(data.keyword);
                })
            },
            minLength: 3,
            limit: 20
        });

      

        $('#submit-btn').click(function(){
        $("#partForm").valid();

        $("#partForm").validate({
            focusInvalid: false,
            invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())
                    return;

                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 2000);

            }
        });

        if (jQuery("#partForm").valid()) {
          storeData();
        }
    });



    $("#customer_id").change(function () {

            // var kdDealer = $('#kd_dealer').val();
            var customer_id = $(this).val();
        });

    $('.hapus-item').click(function(){
      var detailId = this.id;
      
      if(detailId != '')
      {
        
      }
    });

    $('#baru').click(function () {
            document.location.href="<?php echo base_url("part/add_customer_part");?>";
        })


    });

    function __addItem()
    {
        var part = $('#part_number').val();
        var jenis = $("#jenis").val();
        var jmlbaris=$("#part_list > tbody >tr").length;
        var data_split = part.split(" - ")
        console.log(data_split);
        if(!part){
            alert('Part Number blm di pilih!');
            return;
        }

        var cust = $("#kd_customer").val();
        var kdCustomer = cust.split(" - ")

        var html = '';

        html += '<tr>';
        // if(jenis == 'Perorangan'){
        html += '<td class="jenis hidden">'+(jenis == 'Perorangan'?kdCustomer[0]:'')+'</td>';
        // }
        // else{
        html += '<td class="jenis hidden">'+(jenis == 'Group'?$("#kd_groupcustomer").val():kdCustomer[0])+'</td>';
        //html += '<td class="jenis hidden" jenis-data="group">'+(jenis == 'Group'?kdGcustomer[1]:'')+'</td>';
        // }
        html += "<td class='text-right'><span class='pull-left'>"+(jmlbaris+1)+"</span> <a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>"; 
        html += '<td>'+data_split[0]+'</td>';
        html += '<td>'+data_split[1]+'</td>';
        html += '<td>'+$("#jenis").val()+'</td>';
        html += '</tr>';

        //console.log(html);

        // $("#part_list > tbody").html('');
        $("#part_list > tbody").append(html);
        $('#part_number').val("");
        //__deleteBtn();


    }

function __deleteBtn(){
    $('.hapus-item').click(function(){
        $(this).parents('tr').remove();
    
    });
    };


function __data()
{
    var jenisval = $('#jenis').val();
  var bariskex=0;
  bariskex = $('#part_list > tbody > tr').length;
  var dataxx=[];
  for(iz=0;iz< bariskex;iz++){
    var jenis = $("#part_list > tbody > tr:eq(" + iz + ") .jenis").attr('jenis-data');
    // console.log(jenis);

    $(".qurency").unmask();

    
    dataxx.push({
        'kd_customer' : $("#part_list > tbody > tr:eq(" + iz + ") td:eq(0)").text(),
        'kd_groupcustomer' : $("#part_list > tbody > tr:eq(" + iz + ") td:eq(1)").text(),
        'part_number'  : $("#part_list > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
        'jenis': $("#part_list > tbody > tr:eq(" + iz + ") td:eq(5)").text()
    })

    $(".qurency").mask('#.##0', {reverse: true});
  }
  console.log('jmlbaris: '+bariskex)
  // console.log(dataxx)
  return dataxx;
    }


        
    function storeData()
    {
    var data_form=__data();
    if(!data_form){ return;}
    var defaultBtn = $("#submit-btn").html();

    // $("#submit-btn").addClass("disabled");
    $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    var formData = $("#partForm").serialize();
    var act = $("#partForm").attr('action');

    $.ajax({
        url: act,
        type: 'POST',
        data: formData+"&detail="+JSON.stringify(data_form),
        dataType: "json",
        success: function (result) {

            if (result.status == true) {

                $('.success').animate({
                    top: "0"
                }, 500);
                $('.success').html(result.message);


                if (result.location != null) {
                    setTimeout(function () {
                        location.replace(result.location)
                    }, 1000);
                } else {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            } else {
                location.reload();
                /*$('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message);
                setTimeout(function () {
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);
                    $('#loadpage').addClass("hidden");
                }, 2000);*/


            }
        }

    });

    return false;

}
function __loadNamaGroup(kdGroup,selectVal){
    var kd_typecustomer = kdGroup;

            var url = http+'/part/get_group';

            $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

            $.getJSON(url,{ 'kd_typecustomer':kd_typecustomer}, function(data, status){
                console.log(data);
                if(status == 'success'){
                    var newDate = '';
                    var grupc = '';
                        grupc += '<option value="">-pilih -</option>';

                    $.each(data.gc_header.message, function(i, item){

                        grupc += '<option value="'+item.KD_GROUPCUSTOMER+'">'+item.KD_GROUPCUSTOMER+'</option>';

                    });
                    $('#kd_groupcustomer').html(grupc);
                    $('#kd_groupcustomer').val(selectVal).select();
                }

        $(".load-form").html('');
    });

}
function __hapus_item(id){
    if(confirm('Yakin data ini akan dihapus?')){
        $.getJSON(http+'/part/delete_customer_part_mapping/'+id,{'id':id}, function(data, status) {
            if (data.status == true) {
                 document.location.reload()
                 }

            });
        
    }
}
</script>