 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$dari_tgl =($this->input->get("dtgl"))?$this->input->get("dtgl"):date("d/m/Y",strtotime("-5 Days"));
$smp_tgl =($this->input->get("stgl"))?$this->input->get("stgl"):date("d/m/Y");

$no_npwp = '';

if (isset($datadealer) && is_array($datadealer->message)) {
  foreach ($datadealer->message as $key => $value) {
    $no_npwp = $value->NO_NPWP;
  }
}


?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      <a class="btn btn-default" role="btn" href="<?php echo base_url('sales_order/add_sales_order');?>">
        <i class='fa fa-file-o fa-fw'></i> Input Sales Order</a>
    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list-ul fa-fw"></i> List SO
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">

        <form id="filterForm" action="<?php echo base_url('sales_order/sales_order') ?>" class="bucket-form" method="get">


          <div id="ajax-url" url="<?php echo base_url('sales_order/so_typeahead');?>"></div>

          <div class="row">


            <div class="col-xs-12 col-sm-2">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                    
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

            <div class="col-xs-12 col-sm-4">
              <div class="form-group">
                  <label>Nomor Sales Order</label>
                  <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Nomor Sales Order" autocomplete="off">
              </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Periode Tanggal</label>
                    <div class="input-group input-append date">
                        <input type="text" id="dtgl" name="dtgl" class="form-control" value="<?php echo $dari_tgl;?>">
                         <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                     </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Sampai Dengan Tanggal</label>
                    <div class="input-group input-append date">
                        <input type="text" id="stgl" name="stgl" class="form-control" value="<?php echo $smp_tgl;?>">
                         <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                     </div>
                </div>
            </div>

          </div>
          <div class="row">


            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                <label>Status Delivery</label>
                <select id="status_sj" name="status_sj" class="form-control">
                  <option value="">--Pilih Status--</option>
                  <option value="Sudah" <?php echo ($this->input->get('status_sj') == 'Sudah' ? "selected" : ""); ?>>Sudah</option>
                  <option value="Belum" <?php echo ($this->input->get('status_sj') == 'Belum' ? "selected" : ""); ?>>Belum</option>
                </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4">
              <div class="form-group">
                  <label>Sales</label>
                  <input type="text" id="kd_sales" name="kd_sales" value="<?php echo $this->input->get('kd_sales'); ?>" class="form-control" placeholder="Kode Sales" autocomplete="off">
              </div>
            </div>

            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                  <label>Konsumen</label>
                  <input type="text" id="kd_customer" name="kd_customer" value="<?php echo $this->input->get('kd_customer'); ?>" class="form-control" placeholder="Kode Konsumen" autocomplete="off">
              </div>
            </div>

            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                  <label>NPWP Dealer</label>
                  <input type="text" id="no_npwp" name="no_npwp" value="<?php echo $no_npwp; ?>" class="form-control" placeholder="No NPWP" autocomplete="off" disabled>
              </div>
            </div>

          </div>
        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel panel-default">
      <!-- <div class="panel-heading">
        Responsive Table
      </div> -->

      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr class="text-center">
              <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
              <th rowspan="2" style="width:50px; vertical-align: middle;">Aksi</th>
              <th colspan="2" style="text-align: center;">Nomor Sales Order</th>
              <th colspan="2" style="text-align: center;">Nomor SPK</th>
            </tr>
            <tr>
              <th>KD Item</th>
              <th>Nama Item</th>
              <th>No. Rangka</th>
              <th>No. Mesin</th>
            </tr>
          </thead>
          <tbody>

          <?php
            $no = $this->input->get('page');
            if($list):
              if(is_array($list->message) || is_object($list->message)):
              foreach($list->message as $key=>$group_row): 
              if($group_row->STATUS_MESIN == 1):
              $no ++;
          ?>

            <tr class="info bold">
              <td><?php echo $no;?></td>
              <td class="table-nowarp">
                <a href="<?php echo base_url('sales_order/add_sales_order?n='.urlencode(base64_encode($group_row->NO_SO))); ?>" class="<?php echo $status_v?>">
                  <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text"></i>
                </a>
              </td>
              <td colspan="2"><?php echo $group_row->NO_SO;?></td>
              <td colspan="2"><?php echo $group_row->NO_SPK;?></td>
            </tr>

              <?php   
                if($list_group && is_array($list_group->message) || is_object($list_group->message)):
                  foreach($list_group->message as $row): 
                  if($group_row->ID == $row->SPK_ID AND $row->NO_MESIN != ''):

                  $allow_printhadiah = $row->JUMLAH_PRINTHADIAH > 0 ? $status_p : 'disabled-action';
              ?>

                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                      <td></td>
                      <td class="table-nowarp">

                        <?php $status_so = ($row->STATUS_SO == 1?'disabled-action' : $status_e); ?>


                        <a href="<?php echo base_url('sales_order/faktur_penjualan/'.$row->ID); ?>" target="_blank" class="<?php echo $status_p?>">
                          <i data-toggle="tooltip" data-placement="left" title="Faktur Penjualan" class="fa fa-list-alt text-warning text"></i>
                        </a>

                        <!-- <a href="<?php echo base_url('sales_order/terima_foucher/'.$row->ID.'?subsidi='.$row->DISKON); ?>" target="_blank" class="<?php echo $status_p?>">
                          <i data-toggle="tooltip" data-placement="left" title="Tanda terima vouvher" class="fa fa-list-alt text-warning text"></i>
                        </a> -->

                        
                        <a class="active <?php echo $status_p?>" id="modal-button" onclick='addForm("<?php echo base_url('sales_order/terima_foucher/'.$row->ID.'?subsidi='.$row->DISKON); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                            <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Tanda terima voucher" ></i>
                        </a>

                        <a class="active <?php echo $allow_printhadiah?>" id="modal-button" onclick='addForm("<?php echo base_url('sales_order/terima_hadiah/'.$row->ID.'?subsidi='.$row->DISKON); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                            <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Tanda terima hadiah" ></i>
                        </a>

                        <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_so?>" url="<?php echo base_url('sales_order/delete_so/'.$row->ID); ?>">
                          <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                        </a>
                        
                      </td>
                      <td class='table-nowarp'><?php echo $row->KD_ITEM;?></td>
                      <td class='table-nowarp'><?php echo $row->NAMA_ITEM;?></td>
                      <td class='table-nowarp'><?php echo $row->NO_RANGKA;?></td>
                      <td class='table-nowarp'><?php echo $row->NO_MESIN;?></td>
                    </tr>

                <?php 
                  endif;
                  endforeach;
                endif;

              endif;
              endforeach;
              else:
                belumAdaData(8);
              endif;
            else:
          
              belumAdaData(8);

            endif;
          ?>

          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
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
      </footer>

    </div>
  </div>



</section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
  
$(document).ready(function(){

    var dataSales=[{'KD SALES' :'','NAMA SALES' : ''}];
    var dataKonsumen=[{'KD CUSTOMER' :'','NAMA CUSTOMER' : ''}];
    // var edit
    // console.log(dataSO);

    $.getJSON(http+"/sales_order/sales_order/true",{"dtgl":$("#dtgl").val(),"stgl":$("#stgl").val()},function(result){

      if(result.sales && (result.sales.totaldata>0)){

        $.each(result.sales.message,function(e,d){

          dataSales.push({
            'KD SALES' :d.KD_SALES,
            'NAMA SALES' : d.NAMA_SALES
          });
          
        })
      }
      if(result.customer && (result.customer.totaldata>0)){

        $.each(result.customer.message,function(e,d){

          dataKonsumen.push({
            'KD CUSTOMER' :d.KD_CUSTOMER,
            'NAMA CUSTOMER' : d.NAMA_CUSTOMER
          });
          
        })
      }

      // console.log(dataMekanik);
      $('#kd_sales').inputpicker({
        data:dataSales,
        fields:['KD SALES','NAMA SALES'],
          fieldText:'KD SALES',
          fieldValue:'KD SALES',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 10,
          pageCurrent: 1,
          urlDelay:1
      }).change(function(){
        $("#filterForm").submit();
      });


      $('#kd_customer').inputpicker({
        data:dataKonsumen,
        fields:['KD CUSTOMER','NAMA CUSTOMER'],
          fieldText:'KD CUSTOMER',
          fieldValue:'KD CUSTOMER',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 10,
          pageCurrent: 1,
          urlDelay:1
      }).change(function(){
        $("#filterForm").submit();
      });
      
    })  
});
</script>
