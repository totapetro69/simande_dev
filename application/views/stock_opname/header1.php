<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
 
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$kd_lokasidealer = "";
$tgl_trans = "";
$no_trans = "";

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));

if (isset($list)) {
  if(($list->totaldata > 0)) {
    foreach ($list->message as $key => $value) {
      $kd_lokasidealer = $value->KD_LOKASIDEALER;
      $tgl_trans = $value->TGL_TRANS;
      $no_trans = $value->NO_TRANS;
    }
  }
}

?>

<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb(); ?>
  </div>

  <div class="col-lg-12 padding-left-right-5 ">
    <div class="panel margin-bottom-5">
      <div class="panel-heading">
        <i class="fa fa-list-ul fa-fw"></i> Stock Opname Header H1
        <span class="tools pull-right">
          <a class="fa fa-chevron-down" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border panel-body-10" style="display: block;">

        <form id="filterForms" method="GET" action="<?php echo base_url("stock_opname/header1"); ?>">

          <div class="row">
            
            <div class="col-xs-5 col-md-4 col-sm-4">
              <div class="form-group">
                <label>Nama Dealer</label>
                <select class="form-control" id="kd_dealer" name="kd_dealer">
                  <option value="">--Pilih Dealer--</option>
                  <?php
                  if($dealer){
                    if(is_array($dealer->message)){
                      foreach ($dealer->message as $key => $value) {
                        $select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                        $select=($this->input->get("kd_dealer")==$value->KD_DEALER)?"selected":$select;
                        echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-5">

              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                  <label>Dari Tanggal</label>
                  <div class="input-group input-append date" id="date">
                    <input class="form-control" id="dari_tanggal" name="dari_tanggal" value="<?php echo $dari_tanggal;?>">
                    <span></span><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                </div>
              </div>

              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                  <label>Sampai Tanggal</label>
                  <div class="input-group input-append date" id="date">
                    <input class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="<?php echo $sampai_tanggal;?>">
                    <span></span><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                </div>
              </div>

            </div>

            <div class="form-group">
              <br>
              <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
            </div>

          </div>
        
        </form>

      </div>

    </div>
  </div>

  <div class="col-lg-12 padding-left-right-5 ">
    <div class="panel panel-default">
      <div class="table-responsive">

        <table class="table table-hover table-striped table-bordered">

          <thead>
            <tr>
              <th style="width: 50px">No</th>
              <th class="table-nowarp text-center">Lokasi</th>
              <th class="table-nowarp text-center">No Trans</th>
              <th class="table-nowarp text-center">Tgl Trans</th>
              <th class="table-nowarp text-center">Nama Item</th>
              <th class="table-nowarp text-center">Stock</th>
            </tr>
          </thead>

          <tbody>
            <?php
            if (isset($list)) {
              $no = 0;
              if (($list->totaldata >0 )) {
                foreach ($list->message as $key => $value) {
                  # code...
                  $no++;
                  ?>
                  <tr id="l_<?php echo $value->ID;?>">
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="table-nowarp text-center"><?php echo $value->NAMA_LOKASI?></td>
                    <td class="table-nowarp text-center"><?php echo $value->NO_TRANS ?></td>
                    <td class="table-nowarp text-center"><?php echo tglFromSql($value->TGL_TRANS)?></td>
                    <td class="table-nowarp text-center"><?php echo $value->KETERANGAN ?></td>
                    <td class="table-nowarp text-center"><?php echo number_format($value->QTY_STOCK,0); ?></td>
                  </tr>
                  <?php
                }
              }
            }
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
  $(document).ready(function(){
    $('#nmd').html($('#kd_dealer option:selected').text())
  })
</script>
