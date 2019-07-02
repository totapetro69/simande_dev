  <section class="wrapper">

  <div class="breadcrumb margin-bottom-10">
    <div id="bc1" class="myBreadcrumb">
      <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
      <a href="javascript:void(0);"><div>Sales</div></a>
      <a href="javascript:void(0);" class="active"><div>SPK</div></a>
    </div>

    <div class="bar-nav pull-right ">
      <a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('spk/input_spk'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Baru
      </a>
      <a class="btn-default">
        <i class="fa fa-print fa-fw"></i> Cetak
      </a>
      <a type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-file"></i> Download <span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="#">PDF</a></li>
        <li><a href="#">Excel</a></li>
      </ul>

    </div>

  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          SPK
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">

        <form id="filterForm" action="<?php echo base_url('sales/spk') ?>" class="bucket-form" method="get">

          <div id="ajax-url" url="<?php echo base_url('sales/spk_typeahead');?>"></div>
            <div class="form-group">
              <select class="form-control">
                <option>Nama Dealer</option>
                <option>1</option>
                <option>2</option>
              </select>
            </div>
            
        
          <div class="form-group">
            <label>Nomor SPK, nama sales, tipe motor, kode motor, nama customer, cara bayar</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan nomor SPK atau nama sales, tipe motor, kode motor, nama customer, cara bayar" autocomplete="off">
          </div>
            
        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel panel-default">
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th style="width:45px;">Aksi</th>
              <th>Nomor SPK</th>
              <th>Tanggal</th>
              <th>Tipe Motor</th>
              <th>Warna Motor</th>
              <th>Nama Customer</th>
              <th>Alamat</th>
              <th>Cara Bayar</th>
              <th>Nama Sales</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td class="table-nowarp">
                <a onclick='addForm("<?php echo base_url('spk/edit_spk'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" >
                  <i class="fa fa-edit text-success text-active"></i> </a>
                <a href="" class="active" ui-toggle-class="">
                  <i class="fa fa-trash text-danger text"></i>
                </a>
              </td>
              <td>01</td>
              <td>2017-02-008</td>
              <td>BIG</td>
              <td>Red</td>
              <td>Joko</td>
              <td>Jakarta</td>
              <td>Kredit</td>
              <td>Okta</td>
              <td>Bayar tiap 3 bulan</td>
            </tr>
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
          <div class="row">

               <div class="col-sm-5 text-center">
                        <small class="text-muted inline m-t-sm m-b-sm">showing 20-30 of 50 items</small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <ul class="pagination pagination-sm m-t-none m-b-none">
                            <li><a href=""><i class="fa fa-chevron-left"></i></a></li>
                            <li><a href="">1</a></li>
                            <li><a href="">2</a></li>
                            <li><a href="">3</a></li>
                            <li><a href="">4</a></li>
                            <li><a href=""><i class="fa fa-chevron-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </footer>
        </div>

    </div>


</section>