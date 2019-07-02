<?php
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$defaulD_salesman = ($this->input->get("kd_salesman")) ? $this->input->get("kd_salesman") : $this->session->userdata("kd_salesman");


//print_r($defaulD_salesman);
?>

<?php                 
                            

                            
                            ?>

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
    .modal-lg {
    width: 1330px;
}

    @page { size: landscape; }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Rekap Insentif KSP</h4>
</div>

<div class="modal-body">
    <div class="col-lg-12 padding-left-right-5">
            <div class="panel margin-bottom-5">
                <div class="panel-heading">
                   <i class="fa fa-list-ul fa-fw"></i> Rekap Insentif KSP 
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
            
                <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                    <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/tambah_insentif_ksp");?>">
                        <div class="row">
                            <div class="col-xs-6 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control" id="kd_dealer" name="kd_dealer">
                                        <option value="">--Pilih Dealer--</option>
                                        <?php
                                            if (isset($dealer)) {
                                                if ($dealer->totaldata>0) {
                                                    foreach ($dealer->message as $key => $value) {
                                                   
                                                        echo "<option value='".$value->KD_DEALER."'>" . $value->NAMA_DEALER . "</option>";
                                                    }
                                                }
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>                        
                            <div class="col-xs-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <label>Tanggal Awal</label>
                                    <div class="input-group input-append date">
                                        <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <div class="input-group input-append date">
                                        <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <div class="col-xs-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Find by</label>
                                    <input class="form-control" type="text" id="keyword" name="keyword" placeholder="cari berdasarkan No Trans atau Nama Salesman">
                                </div>
                            </div> -->
                            <div class="col-xs-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <br>
                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div id="head" class='hidden'>
                <table class="table">
                    <tbody>
                        <?php echo (isset($judul))?$judul:"";?>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th  style="text-align: center !important">No</th>
                                <th  style="text-align: center !important">Tot. Sales</th>
                                <th  style="text-align: center !important">RPK</th>
                                <th  style="text-align: center !important">Margin/Unit</th>
                                <th  style="text-align: center !important">Insentif/Unit</th>
                                <th  style="text-align: center !important">Insentif</th>
                                <th  style="text-align: center !important">Penalty</th>
                                <th  style="text-align: center !important">PPh21</th>
                                <th  style="text-align: center !important">Insentif Diterima</th>                               
                                <th  style="text-align: center !important">Aksi</th>                               
                            </tr>
                           
                        </thead>
                       
                        <tfoot>
                           
                           
                           

                        </tfoot>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    

</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>


<script type="text/javascript">
   
      function loadData(id, value, select) {
            var param = $('#' + id + '').attr('title');
            
            $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
            var urls = "<?php echo base_url(); ?>laporan_insentif/" + param;
            var datax = {"kd": value};

            $('#' + id + '').attr('disabled', 'disabled');
            //alert(param);


            $.ajax({
                type: 'GET',
                url: urls,
                data: datax,
                typeData: 'html',
                success: function (result) {
                    
                   $("#sales").html(result);
                }
            });
        }
</script>