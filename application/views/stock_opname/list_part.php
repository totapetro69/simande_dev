<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );

$defaultgudang= ($this->input->get("kd_gudang"))?$this->input->get("kd_gudang"):"";
$defaultitem= ($this->input->get("kd_item"))?$this->input->get("kd_item"):"";
$defaultLokasi=($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";

$edit = $this->input->get('no_trans') ? $status_e : 'disabled-action';

?>

<section class="wrapper">

   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right">
         <a class="btn btn-default <?php echo $edit;?>" href="<?php echo base_url('stock_opname/add_stock?n='.$this->input->get('no_trans')); ?>">
            <i class="fa fa-edit fa-fw"></i> Edit
         </a>

         <a class="btn btn-default" href="<?php echo base_url('stock_opname/add_stock'); ?>">
            <i class="fa fa-file-o fa-fw"></i> Baru
         </a>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-5 ">

      <div class="panel margin-bottom-5">
         <div class="panel-heading">
            <i class="fa fa-list-ul fa-fw"></i> Add Stock Opname Part
            <span class="tools pull-right">
               <a class="fa fa-chevron-down" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border panel-body-10" style="display: block;">
            <form id="filterForms" method="GET" action="<?php echo base_url("stock_opname/list_part"); ?>">

               <div class="row">
                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control " id="kd_dealer" name="kd_dealer" required="">
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

                  <div class="col-xs-6 col-md-4 col-sm-4">
                     <div class="form-group">
                        <label>Keterangan <span class="detail-loading"></span></label>
                        <select class="form-control " id="keterangan" name="keterangan" required="">
                           <option value="A" <?php echo ($this->input->get('keterangan') == 'A'?'selected':'');?>>SELISIH KURANG SPARE PART (QUANTITY FISIK ADA, QUANTITY SISTEM ADA)</option>
                           <option value="B" <?php echo ($this->input->get('keterangan') == 'B'?'selected':'');?>>SELISIH KURANG SPARE PART (QUANTITY FISIK TIDAK ADA, QUANTITY SISTEM ADA)</option>
                           <option value="C" <?php echo ($this->input->get('keterangan') == 'C'?'selected':'');?>>STOCK SPARE PART YANG TIDAK TERDAPAT SELISIH</option>
                           <option value="D" <?php echo ($this->input->get('keterangan') == 'D'?'selected':'');?>>SELISIH LEBIH SPARE PART (QUANTITY SISTEM ADA, QUANTITY FISIK ADA)</option>
                           <option value="E" <?php echo ($this->input->get('keterangan') == 'E'?'selected':'');?>>SELISIH LEBIH SPARE PART (QUANTITY SISTEM TIDAK ADA, QUANTITY FISIK ADA)</option>
                           <option value="F" <?php echo ($this->input->get('keterangan') == 'F'?'selected':'');?>>SELISIH KURANG OLI (QUANTITY FISIK ADA, QUANTITY SISTEM ADA)</option>
                           <option value="G" <?php echo ($this->input->get('keterangan') == 'G'?'selected':'');?>>STOCK OLI YANG TIDAK TERDAPAT SELISIH</option>
                           <option value="H" <?php echo ($this->input->get('keterangan') == 'H'?'selected':'');?>>SELISIH LEBIH OLI (QUANTITY SISTEM ADA, QUANTITY FISIK ADA)</option>
                        </select>

                     </div>
                  </div>

                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>No. Transaksi <span class="detail-loading"></span></label>
                        <input class="form-control" id="no_trans" name="no_trans" placeholder="Part Number" value="<?php echo $this->input->get('no_trans');?>" type="text"/>

                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Jenis</label>
                        <select class="form-control " id="jenis" name="jenis" required="">
                           <option value="Header" <?php echo ($this->input->get('jenis') == 'Header'?'selected':'');?>>Header</option>
                           <option value="Detail" <?php echo ($this->input->get('jenis') == 'Detail'?'selected':'');?>>Detail</option>
                        </select>

                     </div>
                  </div>

                  <div class="col-xs-6 col-md-1 col-sm-1">
                     <div class="form-group">
                        <br>
                        <button type='submit' class="btn btn-info" ><i class='fa fa-search'></i> Cari</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-5 ">
      <div class="panel panel-default">
         <div class="table-responsive h350">
            <table id="detail_list" class="table table-hover table-striped table-bordered">
               
               <thead>
                  <tr>
                     <th style="width:4%">Aksi</th>
                     <th style="width:10%">No Part</th>
                     <th style="width:10%">Deskripsi</th>
                     <th style="width:10%">HET</th>
                     <?php if($this->input->get('jenis') == 'Detail'): ?>
                     <th style="width:10%">Gudang</th>
                     <th style="width:10%">Rakbin</th>
                     <?php endif;?>
                     <th style="width:8%">Qty System</th>
                     <th style="width:8%">Qty Fisik</th>
                     <th style="width:8%">Selisih</th>
                     <th style="width:8%">Amount</th>
                  </tr>
               </thead>

               <tbody>
                  <?php
                  if (isset($list)) {
                     $no = $this->input->get('page');
                     if (($list->totaldata >0 )) {
                        foreach ($list->message as $key => $value) {
                           # code...
                           $no++;
                           $selisih = $value->QTY_AKTUAL - $value->QTY_STOCK;
                           $amount = $value->HARGA_JUAL * $selisih;
                           ?>
                           <tr>
                              <td class="text-center"><?php echo $no; ?></td>
                              <td class="table-nowarp text-left"><?php echo $value->KD_ITEM; ?></td>
                              <td class="table-nowarp text-left"><?php echo $value->KETERANGAN; ?></td>
                              <td class="table-nowarp text-right"><?php echo $value->HARGA_JUAL; ?></td>
                              <?php if($this->input->get('jenis') == 'Detail'): ?>
                              <td class="table-nowarp text-center"><?php echo $value->KD_GUDANG; ?></td>
                              <td class="table-nowarp text-center"><?php echo $value->KD_RAKBIN; ?></td>
                              <?php endif;?>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY_STOCK,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY_AKTUAL,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo $selisih; ?></td>
                              <td class="table-nowarp text-right"><?php echo $amount; ?></td>
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
<!-- </div>
</div> -->

<?php echo loading_proses();?>
</section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
   getNotrans();

   $('#part_number').change(function(e){
      var allData=$(this).val();
      var items        = allData.split('|');
      
      var part_number  = items[0];
      var kd_gudang    = items[1];
      var kd_rakbin    = items[2];
      var kd_lokasi    = items[3];
      var jumlah       = items[4];
      var harga_jual   = items[5];
      var part_deskripsi   = items[6];
      var jenis_item   = items[7] == 'OIL'?'OLI':'PART';

      $('#kd_item').val(part_number);
      $('#kd_gudang').val(kd_gudang);
      $('#kd_rakbin').val(kd_rakbin);
      $('#kd_lokasidealer').val(kd_lokasi);
      $('#qty_stock').val(jumlah);
      $('#harga_jual').val(harga_jual);
      $('#keterangan').val(part_deskripsi);
      $('#jenis_item').val(jenis_item);
   });

   $("#submit-btn").click(function(){
      $("#form_sop").valid();
        $("#form_sop").validate({
            focusInvalid: false,
            invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                    return;
                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 2000);
            }
        });
        if (jQuery("#form_sop").valid()) {
          storeData();
        }
   });

   $("#detail_list").on('click', '.hapus-item', function(){
      var detail_id = this.id;

      var defaultBtn = $(this).html();
      var url = "<?php echo base_url('stock_opname/delete_detail');?>";
      var result = confirm("Apakah anda yakin ingin menghapus data ini ?");

      $(".alert-message").fadeIn();
      $(this).html("<i class='fa fa-spinner fa-spin'></i>");


      if (result) {

         $(this).html("<i data-toggle='tooltip' data-placement='left' title='hapus' class='fa fa-spinner fa-spin text-danger text'></i>");
         $(".alert-message").fadeIn();

         $.getJSON(url,{'id':detail_id}, function(data, status) {


             if (data.status == true) {

               $('.success').animate({top: "0"}, 500);
               $('.success').html(data.message);
               getNotrans();

               setTimeout(function() {
                  hideAllMessages();
                  $("#" + detail_id).parents("tr").remove();
               }, 2000);


             } else {
               $('.error').animate({top: "0"}, 500);
               $('.error').html(data.message);
               setTimeout(function() {
                  hideAllMessages();
                  $("#" + detail_id).html(defaultBtn);
               }, 2000);
             }

         });
      }
   });

});

function getNotrans()
{
   var no_trans = $("#no_trans").val();
   var url = "<?php echo base_url('stock_opname/get_partheder');?>";

   $('#no_trans').inputpicker({
      url:url,
      fields:['NO_TRANS','TGL_OPNAME'],
      fieldText:'NO_TRANS',
      fieldValue:'NO_TRANS',
      filterOpen: true,
      headShow:true,
      pagination: true,
      pageMode: '',
      pageField: 'p',
      pageLimitField: 'per_page',
      limit: 15,
      pageCurrent: 1,
      urlDelay:2,
      delimiter:true
   });

}

</script>