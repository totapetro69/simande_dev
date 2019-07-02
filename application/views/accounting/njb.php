<style type="text/css">
#desc {
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
    width: 100%;
}
.project {
    /* float: left; */
    text-align: left;
    display: table;
    width: 100%;
}
.project div {
    display: table-row;
}

.project .title {
    color: #5D6975;
    width: 90px;
}

.project span {
    text-align: left;
    /* width: 100px; */
    /* margin-right: 15px; */
    padding: 2px 0;
    display: table-cell;
    /* font-size: 0.8em; */
}

.project .content {
    width: 150px;
}
</style>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h5 class="modal-title" id="myModalLabel">NOTA JASA BENGKEL</h5>
</div>

<div class="modal-body" id="printarea">

    
        <table style="border-collapse: collapse;" border="0">

          <tr>
            <td colspan="2">No.Nota:</td>
            <td colspan="2" rowspan="3" valign="middle" align="center"><h4>NOTA JASA BENGKEL</h4></td>
            <td align="right">No. Pol :</td>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2">No. PKB:</td>
            <td align="right"> Nama : </td>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2">Tgl. Faktur:</td>
            <td align="right">Alamat:</td>
            <td colspan="2"></td>
          </tr>
          <tr><td colspan="7"></td></tr>
          <tr style="border-bottom: 1px solid; border-top: 1px solid; height: 30px" valign="middle" align="center">
            <td style="width:45px;">Item</td>
            <td>Nama</td>
            <td style="width: 100px">Harga</td>
            <td style="width: 60px">QTY</td>
            <td style="width: 80px">Diskon</td>
            <td style="width: 150px">Jumlah</td>
          </tr>

          
          <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

          <tr>
            <td colspan="6">NSC:</td>
          </tr>

          <tr  valign="middle" align="center">
            <td style="width:45px;">Item</td>
            <td>Nama</td>
            <td style="width: 100px">Harga</td>
            <td style="width: 60px">QTY</td>
            <td style="width: 80px">Diskon</td>
            <td style="width: 150px">Jumlah</td>
          </tr>

      <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
      <tr>
            <td colspan="6">NJB:</td>
      </tr>

      <tr  valign="middle" align="center">
            <td style="width:45px;">Item</td>
            <td>Nama</td>
            <td style="width: 100px">Harga</td>
            <td style="width: 60px">QTY</td>
            <td style="width: 80px">Diskon</td>
            <td style="width: 150px">Jumlah</td>
          </tr>

      <tr style="height: 30px;border-top:1px solid">
            <td colspan="3" style="padding-right: 10px; border:none;" align="right"></td>
            <td align="right" style="padding-right: 5px"></td>
            <td colspan="2" align="right" style="padding-right: 5px">Subtotal :</td>
            <td align="right" style="padding-right: 5px; border-bottom: 1px dotted;"></td>
      </tr>
          <tr style="height: 30px">
            <td colspan="3" rowspan="" valign="top">Terbilang :</td>
            <td></td>
            <td colspan="2" align="right" style="padding-right: 5px">DPP :</td>
            <td align="right" style="padding-right: 5px; border-bottom: 1px dotted;">0</td>
          </tr>
          <tr style="height: 30px">
            <td colspan="3" align="right" valign="bottom"></td>
            <td align="center"></td>
            <td colspan="2" align="right" style="padding-right: 5px">PPN:</td>
            <td align="right" style="padding-right: 5px; border-bottom: 1px dotted;"></td>
          </tr>
          <tr style="height: 30px">
            <td colspan="3" align="right" valign="bottom"></td>
            <td align="center"></td>
            <td colspan="2" align="right" style="padding-right: 5px">Total Bayar :</td>
            <td align="right" style="padding-right: 5px; border-bottom: 1px dotted;"></td>
          </tr>
          <!-- <tr>
            <td>&nbsp;</td>
            <td colspan="2"></td>
            <td align="right" style="padding-right: 5px"></td>
          </tr> -->
      </table>
      
</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>