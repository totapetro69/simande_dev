
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Detail Sales Program : <?php echo (isset($list))?$list->message[0]->NAMA_SALESPROGRAM:"";?></h4>
</div>

<div class="modal-body">
	<div class="table-responsive">
		<table class="table table-striped b-t b-light">
			<tr>
              <td width='28%'>Kode</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->KD_SALESPROGRAM;?></td>
            </tr>
			<tr>
              <td width='28%'>Nama Sales Program</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->NAMA_SALESPROGRAM;?></td>
            </tr>
			<tr>
              <td width='28%'>Tipe</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->TIPE_SALESPROGRAM;?></td>
            </tr>
			<tr>
              <td width='28%'>Kode AHM</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->KD_SALESPROGRAMAHM;?></td>
            </tr>
			<tr>
              <td width='28%'>No. Surat SP</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->NO_SURATSP;?></td>
            </tr>
			<tr>
              <td width='28%'>SP Khusus</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SALESPROGRAM_KHUSUS;?></td>
            </tr>
			<tr>
              <td width='28%'>SP Hadiah</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SALESPROGRAM_GIFT;?></td>
            </tr>
			<tr>
              <td width='28%'>SP Cabang</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SALESPROGRAM_CABANG;?></td>
            </tr>
			<tr>
              <td width='28%'>Start Date</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->START_DATE;?></td>
            </tr>
			<tr>
              <td width='28%'>POT Start</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->POT_START;?></td>
            </tr>
			<tr>
              <td width='28%'>POT End</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->POT_END;?></td>
            </tr>
			<tr>
              <td width='28%'>SSU Start</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SSU_START;?></td>
            </tr>
			<tr>
              <td width='28%'>SSU End</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SSU_END;?></td>
            </tr>
			<tr>
              <td width='28%'>End Date</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->END_DATE;?></td>
            </tr><tr>
              <td width='28%'>End Claim</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->END_CLAIM;?></td>
            </tr>
			<tr>
              <td width='28%'>Kode Tipe Motor</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->KD_TYPEMOTOR;?></td>
            </tr><tr>
              <td width='28%'>Jumlah</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->QTY;?></td>
            </tr><tr>
              <td width='28%'>SK AHM</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SK_AHM;?></td>
            </tr>
			<tr>
              <td width='28%'>SK MD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SK_MD;?></td>
            </tr>
			<tr>
              <td width='28%'>SK SD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SK_SD;?></td>
            </tr>
			<tr>
              <td width='28%'>SK Finance</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SK_FINANCE;?></td>
            </tr>
			<tr>
              <td width='28%'>SC AHM</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SC_AHM;?></td>
            </tr>
			<tr>
              <td width='28%'>SC MD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SC_MD;?></td>
            </tr>
			<tr>
              <td width='28%'>SC SD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SC_SD;?></td>
            </tr>
			<tr>
              <td width='28%'>CB AHM</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->CB_AHM;?></td>
            </tr><tr>
              <td width='28%'>CB MD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->CB_MD;?></td>
            </tr>
			<tr>
              <td width='28%'>CB SD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->CB_SD;?></td>
            </tr>
			<tr>
              <td width='28%'>POT Faktur</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->POT_FAKTUR;?></td>
            </tr>
			<tr>
              <td width='28%'>Cash Tempo</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->CASH_TEMPO;?></td>
            </tr>
			<tr>
              <td width='28%'>Split OTR</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SPLIT_OTR;?></td>
            </tr>
			<tr>
              <td width='28%'>Split OTR 2</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->SPLIT_OTR2;?></td>
            </tr>
			<tr>
              <td width='28%'>Hadiah Langsung</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->HADIAH_LANGSUNG;?></td>
            </tr>
			<tr>
              <td width='28%'>Harga Kontrak</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->HARGA_KONTRAK;?></td>
            </tr>
			<tr>
              <td width='28%'>Fee</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->FEE;?></td>
            </tr>
			<tr>
              <td width='28%'>Pengurusan STNK</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->PENGURUSAN_STNK;?></td>
            </tr>
			<tr>
              <td width='28%'>Pengurusan BPKB</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->PENGURUSAN_BPKB;?></td>
            </tr>
			<tr>
              <td width='28%'>No. PO</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->NO_PO;?></td>
            </tr>
			<tr>
              <td width='28%'>Min SK SD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->MIN_SK_SD;?></td>
            </tr>
			<tr>
              <td width='28%'>Min SC SD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->MIN_SC_SD;?></td>
            </tr>
			<tr>
              <td width='28%'>DP OTR</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->DP_OTR;?></td>
            </tr>
			<tr>
              <td width='28%'>Tambahan Finance</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->TAMBAHAN_FINANCE;?></td>
            </tr>
			<tr>
              <td width='28%'>Tambahan MD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->TAMBAHAN_MD;?></td>
            </tr>
			<tr>
              <td width='28%'>Tambahan SD</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->TAMBAHAN_SD;?></td>
            </tr>
			<tr>
              <td width='28%'>Tunda Faktur</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->TUNDA_FAKTUR;?></td>
            </tr>
			<tr>
              <td width='28%'>Hadiah Langsung 2</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->HADIAH_LANGSUNG2;?></td>
            </tr>
			<tr>
              <td width='28%'>Keterangan Hadiah</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->KETERANGAN_HADIAH;?></td>
            </tr>
			<tr>
              <td width='28%'>Tambahan AHM</td>
			  <td width='2%'>:</td>
              <td width='70%'><?php echo (!isset($list))?"":$list->message[0]->TAMBAHAN_AHM;?></td>
            </tr>
        </table>	
	</div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>

