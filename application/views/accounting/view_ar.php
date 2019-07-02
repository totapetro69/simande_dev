<?php
if (!isBolehAkses()) {
    //redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$pilih = $this->input->get('pilih');
?>
<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb(); ?>
    <div class="bar-nav pull-right ">

    <a class="btn btn-default" id="baru"><i class="fa fa-file-o fa-fw"></i> Baru </a>
    <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/sales_print?pilih=' . $this->input->get("pilih") . '&tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir") . '&keyword=' . $this->input->get("keyword")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Penerimaan" ></i> Cetak
    </a>
  </div>
  </div>

  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
        Pelunasan AR
        <span class="tools pull-right">
          <a class="fa fa-chevron-down" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border" style="display: block;">
        <form id="filterFormz" action="<?php echo base_url('report/sales') ?>" class="bucket-form" method="get">
          <div id="ajax-url" url="<?php echo base_url('report/sales_typeahead'); ?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Nama Dealer</label>
                <select name="kd_dealer" class="form-control" <?php echo ($this->session->userdata("kd_group") == "Root") ? "" : "disabled"; ?>>
                  <option value="0">--Pilih Dealer--</option>
                  <?php
                  if ($dealer) {
                    if (is_array($dealer->message)) {
                      foreach ($dealer->message as $key => $value) {
                        $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                        echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>No Kwitansi</label>
                <select name="" class="form-control">
                  <option value="0">--Pilih--</option>
                  
                </select>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
    </div>

    <div id="detail-panel" class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-body panel-body-border-top" style="display: show;">

          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>NO Kwitansi</label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>No Mesin</label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>NO Kontrak</label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label class="control-label" for="date">Tanggal Pembayaran</label>
              <div class="input-group input-append date" id="date">
            <input class="form-control" id="tgl_bayar" name="" placeholder="DD/MM/YYYY" value="" type="text"/>
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label class="control-label" for="date">Tanggal TOP</label>
              <div class="input-group input-append date" id="date">
            <input class="form-control" id="tgl_top" name="" placeholder="DD/MM/YYYY" value="" type="text"/>
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>NO SPJB</label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>Nama Customer</label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>Alamat Customer </label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
                <label>Angsuran Ke</label>
                <select name="pilih" class="form-control">
                  <option value="0" >1</option>
                  <option value="1" >2</option>
                  <option value="2" >3</option>
                  <option value="3" >4</option>
                  <option value="4" >5</option>
                  <option value="5" >6</option>
                </select>
              </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>Angsuran Per Bulan</label>
              <input type="text" id="" name="" value="Rp." class="form-control">
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>Angsuran Terbayar</label>
              <input type="text" id="" name="" value="Rp." class="form-control">
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
              <label>Jumlah Bayar</label>
              <input type="text" id="" name="" value="" class="form-control">
            </div>
          </div>
          <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="form-group">
              <label>Jenis Pembayaran</label>
              <br>
              <input type="checkbox" name="jenis_bayar" value=""> Pembayaran Cash<br>
              <input type="checkbox" name="jenis_bayar" value=""> Pembayaran dengan Cheque<br>
              <input type="checkbox" name="jenis_bayar" value=""> Pembayaran KU 
            </div>
          </div>

</div>
    </div>


          <div id="detail-panel" class="col-lg-6 ">
            <div class="panel-heading panel-custom">
              <i class="fa fa-list fa-fw"></i> Detail Hutang Customer
            </div>

            <div class="panel margin-bottom-10">
              <div class="panel-body panel-body-border-top" style="display: show;">

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label>Hutang Pokok</label>
                    <input class="form-control" id="" name="" placeholder="" type="text"/>
                  </div>
                </div>

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label>Total Angsuran</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label>Sisa Hutang Pokok</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div id="detail-panel" class="col-lg-6 padding-left-right-5">
            <div class="panel-heading panel-custom">
            </div>

            <div class="panel margin-bottom-10">
              <div class="panel-body panel-body-border-top" style="display: show;">

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label>No Rek</label>
                    <input class="form-control" id="" name="" placeholder="" type="text"/>
                  </div>
                </div>

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label>No Rek/BG</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label>Nama Bank</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class="col-xs-6 col-sm-10">
                  <div class="form-group">
                    <label class="control-label" for="date">Tanggal TOP</label>
                    <div class="input-group input-append date" id="date">
                      <input class="form-control" id="tgl_top" name="" placeholder="DD/MM/YYYY" value="" type="text"/>
                      <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                  </div>
                </div>

            </div>
          </div>



        </div>
        </div>

            <footer class="panel-footer">
                <div class="row">

                    <!-- <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($totaldata == '') ? "" : "<i>Total Data " . $totaldata . " items</i>"; ?>
                        </small>
                    </div> -->
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </footer>
        
</section>
