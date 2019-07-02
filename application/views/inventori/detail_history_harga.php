
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Detail History Harga: <?php echo $list->message[0]->KD_ITEM;?> - <?php echo $list->message[0]->NAMA_ITEM;?></h4>
</div>

<div class="modal-body">
	<div class="table-responsive">
		<table class="table table-striped b-t b-light">
			<tr>
              <td width='28%'>Kode Item</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->KD_ITEM;?></td>
            </tr>
			<tr>
              <td width='28%'>Nama Item</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->NAMA_ITEM;?></td>
            </tr>
			<tr>
              <td width='28%'>Kode Wilayah</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->KD_WILAYAH;?></td>
            </tr>
			<tr>
              <td width='28%'>Tanggal Update</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->TGL_UPDATE;?></td>
            </tr>
			<tr>
              <td width='28%'>Kode Kategori</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->KD_CATEGORY;?></td>
			  <tr>
              <td width='28%'>Harga Beli</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo number_format($list->message[0]->HARGA_DEALERD);?></td>
            </tr>
            </tr>
			<tr>
              <td width='28%'>Harga Jual Customer</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo number_format($list->message[0]->HARGA_DEALER);?></td>
            </tr>
			<tr>
              <td width='28%'>Harga Jual ke Dealer</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo number_format($list->message[0]->HARGA);?></td>
            </tr>
			<tr>
              <td width='28%'>Harga OTR</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo number_format($list->message[0]->HARGA_OTR);?></td>
            </tr>
			<tr>
              <td width='28%'>BBN</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo number_format($list->message[0]->BBN);?></td>
            </tr>
			<tr>
              <td width='28%'>PPH RK</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->PPH_RK;?></td>
            </tr>
			<tr>
              <td width='28%'>PPH RK 2</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->PPH_RK2;?></td>
            </tr>
			<tr>
              <td width='28%'>Biaya Administrasi</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->BIAYA_ADM;?></td>
            </tr>
			<tr>
              <td width='28%'>Biaya Lain-lain</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->BIAYA_LAIN;?></td>
            </tr>
			<tr>
              <td width='28%'>Aksesoris</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo $list->message[0]->BARANG;?></td>
            </tr>
        </table>	
	</div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>

