<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<section class="wrapper">


    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">


          <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('pajak/createfile_pajak');?>" role="button">
              <i class="fa fa-download fa-fw"></i> Download File .CSV
          </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> List Data
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('ssu/transaksi_ssu') ?>" class="bucket-form" method="get">

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Cari berdasarkan nomor faktur" autocomplete="off" value="<?php echo $this->input->get('keyword');?>">
                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel panel-default">

            <div class="table-responsive">
                <table id="pkb_list" class="table table-striped table-bordered nowarp">
                    <thead>
                        <tr class="no-hover"><th colspan="13" ><i class="fa fa-list fa-fw"></i> Data Faktur Pajak</th></tr>
                        <tr>
                            <th rowspan="2" style="width:40px;">No.</th>
                            <th rowspan="2" style="width:45px;"></th>
                            <th>No. Trans</th>
                            <th colspan="10">Tgl Trans</th>
                        </tr>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>NPWP</th>
                            <th>Nama Item</th>
                            <th>Harga</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Diskon</th>
                            <th>DPP</th>
                            <th>PPN</th>
                            <th>Biaya STNK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $header) :
                                $no ++;
                        ?>

                                    <tr class="info bold">
                                        <td><?php echo  $no; ?></td>
                                        <td>
                                            
                                        </td>
                                        <td><?php echo  $header->NO_PAJAK; ?></td>
                                        <td colspan="10"><?php echo  $header->TGL_PAJAK; ?></td>
                                    </tr>

                        <?php
                                foreach ($detail->message as $key2 => $row):
                                if($header->NO_PAJAK == $row->NO_PAJAK):
                                    ?>

                                    <tr>
                                        <td></td>
                                        <td>
                                            
                                          <a id="delete-btn<?php echo $row->ID;?>" class="delete-btn <?php echo $status_e;?>" url="<?php echo base_url('pajak/delete_faktur/'.$row->ID);?>">
                                            <i data-toggle="tooltip" data-placement="left" title="Batal Pengajuan" class="fa fa-trash text-danger text"></i>
                                          </a>

                                        </td>
                                        <td><?php echo  $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo  $row->ALAMAT_CUSTOMER; ?></td>
                                        <td><?php echo  $row->NPWP_CUSTOMER; ?></td>
                                        <td><?php echo  $row->NAMA_ITEMFAKTUR; ?></td>
                                        <td><?php echo  $row->HARGA_ITEM; ?></td>
                                        <td><?php echo  $row->NO_MESIN; ?></td>
                                        <td><?php echo  $row->NO_RANGKA; ?></td>
                                        <td><?php echo  $row->DISC_ITEM; ?></td>
                                        <td><?php echo  $row->DPP_FAKTUR; ?></td>
                                        <td><?php echo  $row->PPN_FAKTUR; ?></td>
                                        <td><?php echo  $row->BIAYA_STNK; ?></td>

                                    </tr>

                                    <?php
                                endif;
                                endforeach;

                                endforeach;

                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(40);
                        endif;
                        ?>
                    </tbody>

                </table>

            </div>

            <footer class="panel-footer">

                <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
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

$(document).ready(function () {

    $("#pull-btn").click(function(){

        var defaultBtn = $("#pull-btn").html();

        $("#pull-btn").addClass("disabled");
        $("#pull-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();

        var url = http+'/ssu/pull_ssu';

        $.getJSON(url, function(data, status) {

          if (data.status == true) {

              $('.success').animate({
                  top: "0"
              }, 500);
              $('.success').html(data.message);


              setTimeout(function(){
                  location.reload();
              }, 2000);
          } 
          else {
              $('.error').animate({
                  top: "0"
              }, 500);
              $('.error').html(data.message);
              setTimeout(function () {
                  hideAllMessages();
                  $("#pull-btn").removeClass("disabled");
                  $("#pull-btn").html(defaultBtn);
                  $('#loadpage').addClass("hidden");
              }, 2000);

          }

        });

        return false;
    });

    $("#udprg-btn").click(function(){

    var defaultBtn = $("#udprg-btn").html();

    $("#udprg-btn").addClass("disabled");
    $("#udprg-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    var url = http+'/ssu/download_file_ssu';

    $.getJSON(url, function(data, status) {

      if (data.status == true) {

          $('.success').animate({
              top: "0"
          }, 500);
          $('.success').html(data.message);

          location.replace(data.file);

          setTimeout(function(){
              location.reload();
          }, 2000);
      } 
      else {
          $('.error').animate({
              top: "0"
          }, 500);
          $('.error').html(data.message);
          setTimeout(function () {
              hideAllMessages();
              $("#udprg-btn").removeClass("disabled");
              $("#udprg-btn").html(defaultBtn);
              $('#loadpage').addClass("hidden");
          }, 2000);

      }

    });

    return false;

    });
});    

function downloadBtn(url, id){
    var defaultBtn = $("#download-"+id).html();

    $("#download-"+id).addClass("disabled");

    $("#download-"+id).html("<i class='fa fa-spinner fa-spin'></i>");

    // alert(id);

    $.getJSON(url, function(data, status) {

      if (data.status == true) {

          $('.success').animate({
              top: "0"
          }, 500);
          $('.success').html(data.message).fadeIn();

          setTimeout(function () {
              hideAllMessages();
              location.replace(data.file);
              $("#download-"+id).removeClass("disabled");
              $("#download-"+id).html(defaultBtn);
              $('#loadpage').addClass("hidden");
          }, 2000);
      } 
      else {
          $('.error').animate({
              top: "0"
          }, 500);
          $('.error').html(data.message).fadeIn();
          setTimeout(function () {
              hideAllMessages();
              $("#download-"+id).removeClass("disabled");
              $("#download-"+id).html(defaultBtn);
              $('#loadpage').addClass("hidden");
          }, 2000);

      }

    });
}
</script>