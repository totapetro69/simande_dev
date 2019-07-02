<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">STNK Lead Time</h4>
</div>

<div class="modal-body">


        <div class="table-responsive">
        <table id="list_data" class="table table-striped table-bordered">
        <thead>
        <tr>
        <th style="width:45px; vertical-align: middle;">No</th>
        <th>NO RANGKA</th>
        <th>NO MESIN</th>
        <th>KD ITEM</th>
        <th>TGL PENERIMAAN STNK</th>
        <th>TGL PENYERAHAN STNK</th>
        <th>LEAD TIME</th>
        </tr>
        </thead>
        <tbody>

            <?php 
            $no=0;
            if($list && is_array($list->message) || is_object($list->message)):
            foreach ($list->message as $key => $list_leadtime):
            $no++;
            $lead_time=strtotime($list_leadtime->TGL_PENYERAHAN) - strtotime($list_leadtime->TGL_PENERIMA);
            // $lead_time=date('z',strtotime($list_leadtime->TGL_PENYERAHAN)) - date('z',strtotime($list_leadtime->TGL_PENERIMA));
            ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $list_leadtime->NO_RANGKA ;?></td>
                    <td><?php echo $list_leadtime->NO_MESIN ;?></td>
                    <td><?php echo $list_leadtime->KD_ITEM ;?></td>
                    <td><?php echo $list_leadtime->TGL_PENERIMA ;?></td>
                    <td><?php echo $list_leadtime->TGL_PENYERAHAN ;?></td>
                    <td><?php echo round($lead_time / (60 * 60 * 24)) ;?></td>
                </tr>
            <?php 
            endforeach;
            else:
                belumAdaData(7);
            endif;
            ?>
        </tbody>
        </table>
        </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <!-- <button id="submit-btn" class="btn btn-danger file-btn">Simpan</button> -->
</div>
