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

            <a id="udprg-btn" class="btn btn-default <?php echo $status_p;?>" role="button">
                <i class="fa fa-download fa-fw"></i> Download File .UDPRG, .UDSTK, .CDB, .TXT
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

                <form id="filterForm" action="<?php echo base_url('report/file_udprg') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('kpb/kpbvalidasi_typeahead?status_kpb=1'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Cari berdasarkan nomor rangka" autocomplete="off" value="<?php echo $this->input->get('keyword');?>">
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
                <table id="pkb_list" class="table table-striped table-bordered">
                    <thead>
                        <tr class="no-hover"><th colspan="15" ><i class="fa fa-list fa-fw"></i> Data Telah Didownload</th></tr>
                        <tr>
                            <th rowspan="2" style="width:40px;">No.</th>
                            <th rowspan="2" style="width:45px;"></th>
                            <th colspan="8">No. Trans</th>
                        </tr>
                        <tr>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Kota</th>
                            <th>Propinsi</th>
                            <th>Kode POS</th>
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
                                            <a href="<?php echo base_url().'report/download_allfile?namafile='.$header->DIRECTORY_FILE ;?>" class="active" id="modal-button">
                                                <i class="fa fa-download" data-toggle="tooltip" data-placement="left" title="download ulang"></i>
                                            </a>
                                        </td>
                                        <td colspan="8"><?php echo  $header->NO_TRANS; ?></td>
                                    </tr>

                        <?php
                                foreach ($detail->message as $key => $row):
                                if($header->NO_TRANS == $row->NO_TRANS):
                                    ?>

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->NO_RANGKA ? 'tr-active' : ' '; ?>" >
                                        <td></td>
                                        <td></td>
                                        <td><?php echo  $row->NO_MESIN; ?></td>
                                        <td><?php echo  $row->NO_RANGKA; ?></td>
                                        <td><?php echo  $row->NAMA_CUSTOMER; ?></td>
                                        <td><?php echo  $row->ALAMAT_SURAT; ?></td>
                                        <td><?php echo  $row->NAMA_KECAMATAN; ?></td>
                                        <td><?php echo  $row->NAMA_KABUPATEN; ?></td>
                                        <td><?php echo  $row->NAMA_PROPINSI; ?></td>
                                        <td><?php echo  $row->KODE_POS; ?></td>
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

  $("#udprg-btn").click(function(){

    var defaultBtn = $("#udprg-btn").html();

    $("#udprg-btn").addClass("disabled");
    $("#udprg-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    var url = http+'/report/download_file_udprg';

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
</script>