<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$kd_lokasi = ($this->input->get("kd_lokasidealer"))?$this->input->get("kd_lokasidealer"):$this->session->userdata("kd_lokasidealer");
$tanggal=date('d/m/Y');
$cek_sales = '';

if(isset($sales)){
    $cek_sales = $sales;
}

$kd_sales = $this->input->get('kd_sales')?$this->input->get('kd_sales'):$cek_sales;


?>

<section class="wrapper">
        <div class="breadcrumb margin-bottom-10">
            <?php echo breadcrumb(); ?>
            <div class="bar-nav pull-right ">
                <!-- <div class="btn-group">
                    <a id="baru" type="button" class="btn btn-default baru ">
                        <i class="fa fa-file-o fa-fw"></i> Add SA
                    </a>
                </div>
                <div class="btn-group">
                    <a role="button" href="<?php echo base_url("customer_service/service_advisor_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List SA</a>
                </div> -->
            </div>

        </div>

    <form id="filterForm" action="<?php echo base_url('after_dealing/list_after_dealing') ?>" class="bucket-form" method="get">

        <div class="col-lg-12 padding-left-right-10">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-list-ul'></i> Activity After Dealing
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
                <div class="panel-body panel-body-border" style="display: block;">



                    <div class="row">
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata > 0) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                                $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-5 col-sm-5">
                            <div class="form-group">
                                <label>Sales Person</label>
                                <input type="text" id="kd_sales" name="kd_sales" class="form-control <?php echo $cek_sales?'disabled-action':'';?>" value="<?php echo $kd_sales;?>" <?php echo $cek_sales?'readonly=""':'';?>>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4 col-sm-4">
                            <div class="form-group">
                                <label>Status Aktivitas</label>
                                <select class="form-control" id="status_aktivitas" name="status_aktivitas" required="true">
                                    <option value="">All</option>
                                    <option value="0" <?php echo $this->input->get('status_aktivitas') == '0'? 'selected' : '';?> >Not Started</option>
                                    <option value="1" <?php echo $this->input->get('status_aktivitas') == '1'? 'selected' : '';?> >In Progress</option>
                                    <option value="2" <?php echo $this->input->get('status_aktivitas') == '2'? 'selected' : '';?> >Completed</option>
                                </select>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </form>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> List Activity After Dealing
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body" style="display: block;">
            <div class="row card-task">

                <?php
                if($list && is_array($list->message)): 
                foreach ($list->message as $key => $value):
                    switch ($value->STATUS_AKTIVITAS) {
                        case 0:
                            if($cek_sales){
                            $button_aktivitas = '<button type="button" data-value="1" class="btn btn-primary btn-xs btn-update btn-add-card">In Progress</button>';
                            }
                            else{
                            $button_aktivitas = '<button type="button" class="btn btn-danger btn-xs btn-update btn-add-card disabled-action">Not Started</button>';
                            }
                            break;
                        
                        case 1:
                            if($cek_sales){
                            $button_aktivitas = '<button type="button" data-value="2" class="btn btn-success btn-xs btn-update btn-add-card">Completed</button>';
                            }
                            else{
                            $button_aktivitas = '<button type="button" class="btn btn-primary btn-xs btn-update btn-add-card disabled-action">In Progress</button>';
                            }
                            break;

                        default:
                            if($cek_sales){
                            $button_aktivitas = '<button type="button" data-value="2" class="btn btn-success btn-xs btn-update btn-add-card disabled-action">Completed</button>';
                            }
                            else{
                            $button_aktivitas = '<button type="button" class="btn btn-success btn-xs btn-update btn-add-card disabled-action">Completed</button>';
                            }
                            break;
                    }
                ?>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                      <div class="thumbnail">
                          <div class="caption">
                            <div class='col-lg-12'>
                                <span class="glyphicon glyphicon-credit-card"></span>
                                
                                <a href="<?php echo base_url('after_dealing/cetak_card/'.$value->ID);?>" target="_blank" id="print-<?php echo $value->ID ;?>" data-id="<?php echo $value->ID ;?>" class="fa fa-print pull-right text-primary print-item <?php echo $status_p?>"></a>
                            </div>
                            <div class='col-lg-12 well well-add-card'>
                                <h4><?php echo $value->NAMA_CUSTOMER ;?></h4>
                            </div>
                            <div class='col-lg-12'>
                                <p><?php echo $value->NAMA_AKTIVITAS ;?></p>
                                <p class="text-muted">No HP : <?php echo $value->NO_HP ;?></p>
                                <p class="text-muted">Tanggal aktivitas : <?php echo TglFromSql($value->WAKTU_MULAI).' - '.TglFromSql($value->WAKTU_SELESAI) ;?></p>
                            </div>
                            
                            <span id="button-area-<?php echo $value->ID ;?>" class="button-area" data-id="<?php echo $value->ID ;?>">
                                <?php echo $button_aktivitas;?>
                            </span>
                            <span class='glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style' data-toggle="tooltip" data-placement="left" title="<?php echo $value->DESKRIPSI;?>" ></span>
                        </div>
                      </div>
                    </div>

                <?php
                endforeach;
                endif;
                ?>

            </div>
            </div>


          <div class="panel-footer">
              <div class="row">

                  <div class="col-sm-5">
                      <small class="text-muted inline m-t-sm m-b-sm"> 
                          <?php echo ($list)? ($list->totaldata==''?"":"<i>Total Data ". $list->totaldata ." items</i>") : '' ?>
                      </small>
                  </div>
                  <div class="col-sm-7 text-right text-center-xs">                
                       <?php echo $pagination;?>
                  </div>
              </div>
          </div>
        </div>
    </div>

    <?php echo loading_proses(); ?>
</section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function () {
        var date = new Date();
        date.setDate(date.getDate());

        sales_people();

        $(".card-task").on('click', '.btn-add-card', function(){
            var status_aktivitas = $(this).data('value');
            var detail_id = $(this).parents('.button-area').data('id');

            $(this).html("<i class='fa fa-spinner fa-spin'></i> Loading");
            // value = $(this).data('value');
            // alert(detail_id);


            $.getJSON(http+'/after_dealing/update_status_aktivitas',
                {status_aktivitas:status_aktivitas, detail_id:detail_id}, function(data, status) {
                if (data.status == true) {
                    if(status_aktivitas == '1'){
                        var buttonArea = '<button type="button" data-value="2" class="btn btn-success btn-xs btn-update btn-add-card">Completed</button>';
                    }
                    else if(status_aktivitas == '2'){
                        var buttonArea = '<button type="button" data-value="2" class="btn btn-success btn-xs btn-update btn-add-card disabled-action">Completed</button>';
                    }

                    // console.log(data.status+'|'+status_aktivitas+'|'+detail_id);

                    $("#button-area-"+detail_id).html(buttonArea);
                }
            });


        })

    })




    function sales_people()
    {
        var url = "<?php echo base_url('after_dealing/get_salespeople');?>";

        $('#kd_sales').inputpicker({
          url:url,
          fields:['NIK','NAMA'],
          fieldText:'NAMA',
          fieldValue:'NIK',
          headShow:true,
          filterOpen: true,
          urlDelay:1
        })
    }
</script>