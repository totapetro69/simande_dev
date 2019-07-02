
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambahkan Surat</h4>
</div>

<div class="modal-body">

<script type="text/javascript">
    function somumaCheck(){
        if (document.getElementById('soCheck').checked) {document.getElementById('ifSo').style.display='block';}
        else document.getElementById('ifSo').style.display='none';
    }
</script>

<form id="addForm" class="bucket-form" action="<?php echo base_url('inventori/add_sjkeluar_simpan');?>" method="post">
    <table border="0">
        <tr>
            <td>
                <label>Jenis Surat Jalan</label>
            </td>
            <td>:</td>
            <td>SO<input type="radio" onclick="javascript:somumaCheck();" name="somuma" id="soCheck"> &nbsp;&nbsp; Mutasi <input type="radio" onclick="javascript:somumaCheck();" name="somuma" id="muCheck"> &nbsp;&nbsp; Manual <input type="radio" onclick="javascript:somumaCheck();" name="somuma" id="maCheck"></td>
        </tr>

        <tr>
            <td></td>
            <td id="ifSo" style="display: none">
                <!-- <div class="form-group">
                <label > <b> Transaksi : </b></label>
                    <select id="single_select" class="form-control" >
                        <option value ="0">Tunai</option><option value="1">Kredit</option>
                    </select>
                </div> -->
                <!-- <input type='text' id='so' name='so'> -->
                
                <td>
                    <label >No Faktur </label>
                    <select id="single_select" class="form-control" >
                        <option value ="0">48756783902</option><option value="1">2345</option>
                    </td>
                </div>
            </td>
        </tr>

    </table>
</form>


            <!-- &nbsp;
            <div class="radio-inline"> <input type="radio" name="radioOption" id="so" value="" checked> SO </div>
            <div class="radio-inline"> <input type="radio" name="radioOption" id="mutasi" value=""> Mutasi </div>
            <div class="radio-inline"> <input type="radio" name="radioOption" id="manual" value=""> Manual </div>
        </div>

        <div class="form-group">
            <label > <b> Transaksi : </b></label>
            <select id="single_select" class="form-control" >
                <option value ="0">Tunai</option><option value="1">Kredit</option>
            </select>
        </div>

        <div class="form-group">
            <label >Nama </label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama ">
        </div> -->

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-danger">Save changes</button>
</div>
</div>