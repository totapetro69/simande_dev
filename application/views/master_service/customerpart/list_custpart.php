<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
//var_dump($tahunpo);
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('part/add_customer_part'); ?>' >
                <i class="fa fa-file-o fa-fw"></i> Input Customer Part
            </a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class="fa fa-search"></i> Customer Part
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterForm" action="<?php echo base_url('part/customer_part') ?>" class="bucket-form" method="get">
                    
                    <div class="col-xs-12 col-md-6 col-sm-6">

                    <div class="form-group">
                        <label>Dealer</label>
                            <select class="form-control" id="kd_dealer" name="kd_dealer" required="true">
                                <option value="">--Pilih Dealer--</option>
                                <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata > 0) {
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
        
        <div id="ajax-url" url="<?php echo base_url('part/customer_part_mapping_typeahead'); ?>"></div>

            <div class="col-xs-12 col-md-6 col-sm-6">
                    
                    
                        <label>Field Pencarian</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $this->input->get('keyword'); ?>" placeholder="Cari berdasarkan Part Number " autocomplete="off">

                    <!-- <div class="col-xs-12 col-sm-4">
                      <div class="form-group">
                        <label>Status</label>
                        <select id="row_status" name="row_status" class="form-control">
                          <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                          <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                          <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
                        </select>
                      </div>
                    </div> -->

                </div>
            </form>
        </div>
    </div>
</div>




 <div class="col-lg-12 padding-left-right-5">

    <div class="panel panel-default">

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
                            <th rowspan="2" style="width:50px; vertical-align: middle;">Aksi</th>
                            <th colspan="2">Part Number</th>
                            <th>Part Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- <tr>
                            <td></td>
                            <td></td>
                            <td>Jenis Customer</td>
                            <td>Nama Customer/Group</td>
                            <td></td>
                        </tr> -->
<!--  -->
                        <?php
                        $no = $this->input->get('page');
                        //print_r($list);exit();
                        if($list):
                          if($list->totaldata>0):
                          foreach($list->message as $key=>$group_row): 
                              $no ++;

                              $link=$group_row->KD_CUSTOMER;
                              $link=base64_encode(urlencode($link));
                            ?>
                            <tr class="info bold">
                                <td class="text-bold"><?php echo  $no; ?></td>
                                <td class="table-nowarp">
                                    <a href="<?php echo base_url('part/add_customer_part') ."?n=".$link; ?>" class="<?php echo $status_v?>">
                                      <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text"></i>
                                    </a>
                                </td>

                                <td><?php echo $group_row->JENIS;?></td>
                                <td><?php echo ($group_row->NAMA_CUSTOMER)?$group_row->NAMA_CUSTOMER:$group_row->NAMA_GROUPCUSTOMER;?></td>
                                <td><?php echo $group_row->KD_CUSTOMER;?></td>
                            </tr>

                            <?php
                                if($list_group): 
                                    if($list_group->totaldata>0): $nox=0;
                                        foreach ($list_group->message as $row):
                                            if(($group_row->KD_CUSTOMER == $row->KD_CUSTOMER)):

                                                $part_data = explode(" - ",$row->PART_NUMBER);
                                                $nox++;
                                                ?>

                                                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >

                                                <td class='text-right'><?php echo $nox;?></td>
                                                <td class='text-center'>
                                                    <a id="delete-btn<?php echo $no;?>" class="delete-btn " url="<?php echo base_url('part/delete_customer_part_mapping/'.$row->ID); ?>">
                                                            <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                                        </a>
                                                </td>
                                                    <td colspan="2"><?php echo $part_data[0];?></td>
                                                    <td><?php echo $row->PART_DESKRIPSI;?></td>
                                                </tr>

                                                <?php 
                                            endif;
                                        endforeach;
                                    endif;
                                endif;
                            endforeach;
                            else:
                            ?>
                          <tr>
                              <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                              <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                          </tr>
                      <?php
                          endif;
                        else:
                      
                          belumAdaData(9);

                        endif;
                      ?>
                    </tbody>

                    
                </table>  

            </div>

            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($totaldata) ? ($totaldata == '0' ? "" : "<i>Total Data " . $totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </footer>
        </div>
    </div>
  </section>


<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

    $(document).ready(function(){
        $("#jenis").change(function(){

        // alert('test');
        if ( this.value == 'Perorangan')
          {
            $("#customer_id").show();
            $("#jenis_group_id").hide();
            $("#group_id").hide();
            $("#part_number").show();
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

        $("#kd_typecustomer").change(function(){

            var kd_typecustomer = $(this).val();

            var url = http+'/part/get_group';

            $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

            $.getJSON(url,{
            kd_typecustomer:kd_typecustomer
            }, function(data, status){

                if(status == 'success'){
                    var newDate = '';
          // console.log(data.gc_header.message[0].KD_GROUPCUSTOMER);
                    var grupc = '';
                    $.each(data.gc_header.message, function(i, item){

                        grupc += '<option value="'+item.KD_GROUPCUSTOMER+'">'+item.KD_GROUPCUSTOMER+'</option>';

                    });
                
                // console.log(grupc);


                    $('#kd_groupcustomer').html(grupc);
                }

        $(".load-form").html('');

      });

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
        $.getJSON(http+'/part/delete_customer_part_mapping',{id:detailId}, function(data, status) {


            if (data.status == true) {

              $("#"+detailId).parents('tr').remove();

            }

        });
      }
    });

    $('#baru').click(function () {
            document.location.reload();
        })


    });

    function __addItem()
    {
        var part = $('#part_number').val();
        var jenis = $("#jenis").val();

        var data_split = part.split(" - ")
        console.log(data_split);

        var html = '';

        html += '<tr>';
        // if(jenis == 'Perorangan'){
        html += '<td class="jenis hidden" jenis-data="customer">'+(jenis == 'Perorangan'?$("#nama_customer").val():'')+'</td>';
        // }
        // else{
        html += '<td class="jenis hidden" jenis-data="group">'+(jenis == 'Group'?$("#kd_groupcustomer").val():'')+'</td>';
        // }
        html += "<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>"; 
        html += '<td>'+data_split[0]+'</td>';
        html += '<td>'+data_split[1]+'</td>';
        html += '</tr>';

        console.log(html);

        // $("#part_list > tbody").html('');
        $("#part_list > tbody").append(html);

        __deleteBtn();


    }

function __deleteBtn(){
    $('.hapus-item').click(function(){
        $(this).parents('tr').remove();
    
    });
    };


function __data()
{

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
        'part_deskripsi': $("#part_list > tbody > tr:eq(" + iz + ") td:eq(4)").text()
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
                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message);
                setTimeout(function () {
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);
                    $('#loadpage').addClass("hidden");
                }, 2000);


            }
        }

    });

    return false;

}
</script>