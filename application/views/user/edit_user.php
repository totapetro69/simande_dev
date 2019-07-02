<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$ID = '';
$USER_NAME = '';
$KD_DEALER = '';
$USER_ID = '';
$USER_NAME = '';
$KD_STATUS = '';
$KD_GROUP = '';
$KD_LEVEL = '';
$KD_DIV = '';
$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');

$TYPE_USER = $this->input->get('type_users');
// var_dump($TYPE_USER);exit;
$KD_DEALER="";
foreach ($list->message as $key => $value) {
  $ID = $value->ID;
  $USER_NAME = $value->USER_NAME;
  $KD_DEALER = $value->KD_DEALER;
  $USER_ID = $value->USER_ID;
  $USER_NAME = $value->USER_NAME;
  $KD_STATUS = $value->KD_STATUS;
  $KD_GROUP = $value->KD_GROUP;
  $KD_LEVEL = $value->KD_LEVEL;
  $KD_DIV = $value->KD_DIV;

}


$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('user/update_user/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit User : <?php echo $list->message[0]->USER_NAME;?></h4>
</div>

<div class="modal-body">

      <div class="row">



        <div class="col-xs-12 col-sm-6">
                
          <div class="form-group">
              <label>Dealer</label>
              <select name="kd_dealer" id="kd_dealer" class="form-control">
                <option value="">- Pilih Dealer -</option>
                <?php foreach ($dealers->message as $key => $group) : 
                  $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";

                ?>
                  <option value="<?php echo $group->KD_DEALER;?>" <?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
                <?php endforeach; ?>
              </select>
          </div>

        </div>


        <div class="col-xs-12 col-md-4">
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $USER_NAME;?>"  required>
          </div>
        </div>

        <div class="col-xs-12 col-md-2">
          <div class="form-group">
            <label>Status</label>
            <select id="kd_status" name="kd_status" class="form-control" required>
              <option value="0" <?php echo ($KD_STATUS == 0 ? "selected" : "");?>>Aktif</option>
              <option value="-1" <?php echo ($KD_STATUS == -1 ? "selected" : "");?>>Tidak Aktif</option>
            </select>
          </div>
        </div>

      </div>


    

      <div class="row">
        <div class="col-xs-12 col-md-6">

          <div class="form-group">
              <label>NIK</label>
              <input id="user_id" type="text" name="user_id" class="form-control" value="<?php echo $USER_ID;?>" readonly>
          </div>

        </div>

        <div class="col-xs-12 col-md-6">

          <div class="form-group">
            <label>Grup User</label>
            <select name="kd_group" class="form-control" required>
              <option value="">- Pilih grup -</option>
              <?php if($groups  && (is_array($groups->message) || is_object($groups->message))): foreach ($groups->message as $key => $group) : ?>
                  <option value="<?php echo $group->KD_GROUP;?>" <?php echo ($group->KD_GROUP == $KD_GROUP ? "selected" : "");?>  ><?php echo $group->NAMA_GROUP;?></option>
              <?php endforeach; endif;?>
            </select>
          </div>

        </div>

      </div>


    

      <div class="row">

        <div class="col-xs-12 col-md-6">
          <div class="form-group">
            <label>Level User</label>
            <select name="kd_level" class="form-control" required>
              <option value="">- Pilih level -</option>
              <option value="0" <?php echo ($KD_LEVEL == 0 ? "selected" : "");?>>Manager</option>
              <option value="1" <?php echo ($KD_LEVEL == 1 ? "selected" : "");?>>Head</option>
              <option value="2" <?php echo ($KD_LEVEL == 2 ? "selected" : "");?>>Supervisor</option>
              <option value="3" <?php echo ($KD_LEVEL == 3 ? "selected" : "");?>>Operator</option>
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-md-6">
          <div class="form-group">
            <label>Divisi</label>
            <select name="kd_div" class="form-control" required>
              <option value="">- Pilih divisi -</option>
              <?php if($divisions && (is_array($divisions->message) || is_object($divisions->message))): foreach ($divisions->message as $key => $division) : ?>
                <option value="<?php echo $division->KD_DIVISI;?>" <?php echo ($division->KD_DIVISI == $KD_DIV ? "selected" : "");?> ><?php echo $division->NAMA_DIV;?></option>
              <?php endforeach; endif;?>
            </select>
          </div>
        </div>

        </div>

      </div>


      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn">Simpan</button>
</div>

</form>
<script type="text/javascript">
/*
processAjaxData('#submit-btn', 'user/user_list');  


 function processAjaxData(response, urlPath){
     document.getElementById("content").innerHTML = response.html;
     document.title = response.pageTitle;
     window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
 }

 window.onpopstate = function(e){
    if(e.state){
        document.getElementById("content").innerHTML = e.state.html;
        document.title = e.state.pageTitle;
    }
}; */
/*

$(document).ready(function(){
  $("#username").change(function(){
      alert($("#username").val());
  });
});
*/
</script>

