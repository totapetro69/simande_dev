
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Profile</h4>
</div>

<div class="modal-body">


  <div class="row">
      <div class="col-xs-12 text-center" style="padding: 0 50px;">

          <img class="picture profile-picture" src="<?php echo base_url('assets/images/pp.png'); ?>">

      </div>
  </div>
  <div class="row">
      <div class="col-xs-12 text-left">
          <div class=" col-xs-6 tital">NIK</div>
          <div class=" col-xs-6 "><?php echo $list->message[0]->USER_ID;?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-xs-6 tital ">Nama Lengkap</div>
          <div class="col-xs-6"><?php echo $list->message[0]->USER_NAME;?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-xs-6 tital ">Grup</div>
          <div class="col-xs-6"><?php echo $list->message[0]->NAMA_GROUP;?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-xs-6 tital ">Divisi</div>
          <div class="col-xs-6"><?php echo $list->message[0]->NAMA_DIV;?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-xs-6 tital ">Level</div>
          <div class="col-xs-6"><?php echo $list->message[0]->NAMA_LEVEL;?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-xs-6 tital ">Dealer</div>
          <div class="col-xs-6"><?php echo $list->message[0]->NAMA_DEALER;?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>
      </div>
  </div>  

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>