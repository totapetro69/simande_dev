
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Target SF</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_targetsf_simpan'); ?>" method="post">

        <div class="row">

            <div class="col-xs-12 col-sm-12">

                <div class="form-group">
                    <label>Nama Sales</label>
                    <select name="kd_sales" class="form-control">
                        <option value="">- Pilih Sales -</option>
                        <?php if ($saless && (is_array($saless->message) || is_object($saless->message))): foreach ($saless->message as $key => $value) : ?>
                                <option value="<?php echo $value->KD_SALES; ?>"><?php echo $value->KD_SALES; ?> - <?php echo $value->NAMA_SALES; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

            </div>

            <div class="col-xs-4 col-sm-4">

                <div class="row">

                    <div class="col-xs-12 col-sm-12">

                        <div class="col-xs-12 col-sm-7">
                            <div class="form-group">
                                <label>Bulan 1</label>
                                <select class="form-control" name="bulan" id="bulan">
                                    <option value='1' <?php
                                    if (date("m") == 1) {
                                        echo 'selected';
                                    }
                                    ?> >January</option>
                                    <option value='2' <?php
                                    if (date("m") == 2) {
                                        echo 'selected';
                                    }
                                    ?> >February</option>
                                    <option value='3' <?php
                                    if (date("m") == 3) {
                                        echo 'selected';
                                    }
                                    ?> >March</option>
                                    <option value='4' <?php
                                    if (date("m") == 4) {
                                        echo 'selected';
                                    }
                                    ?> >April</option>
                                    <option value='5' <?php
                                    if (date("m") == 5) {
                                        echo 'selected';
                                    }
                                    ?> >May</option>
                                    <option value='6' <?php
                                    if (date("m") == 6) {
                                        echo 'selected';
                                    }
                                    ?> >June</option>
                                    <option value='7' <?php
                                    if (date("m") == 7) {
                                        echo 'selected';
                                    }
                                    ?> >July</option>
                                    <option value='8' <?php
                                    if (date("m") == 8) {
                                        echo 'selected';
                                    }
                                    ?> >August</option>
                                    <option value='9' <?php
                                    if (date("m") == 9) {
                                        echo 'selected';
                                    }
                                    ?> >September</option>
                                    <option value='10' <?php
                                    if (date("m") == 10) {
                                        echo 'selected';
                                    }
                                    ?> >October</option>
                                    <option value='11' <?php
                                    if (date("m") == 11) {
                                        echo 'selected';
                                    }
                                    ?> >November</option>
                                    <option value='12' <?php
                                    if (date("m") == 12) {
                                        echo 'selected';
                                    }
                                    ?> >December</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group">
                                <label>Tahun 1</label>
                                <select class="form-control" name="tahun" id="tahun">
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="form-group">
                    <label>Target 1</label>
                    <input id="target" type="text" name="target" class="form-control">
                </div>

            </div>

            <div class="col-xs-4 col-sm-4">

                <div class="row">

                    <div class="col-xs-12 col-sm-12">

                        <div class="col-xs-12 col-sm-7">
                            <div class="form-group">
                                <label>Bulan 2</label>
                                <select class="form-control disabled-action" name="bulan2" id="bulan2" readonly>
                                    <option value='1' <?php
                                    if (date("m") + 1 == 1) {
                                        echo 'selected';
                                    }
                                    ?> >January</option>
                                    <option value='2' <?php
                                    if (date("m") + 1 == 2) {
                                        echo 'selected';
                                    }
                                    ?> >February</option>
                                    <option value='3' <?php
                                    if (date("m") + 1 == 3) {
                                        echo 'selected';
                                    }
                                    ?> >March</option>
                                    <option value='4' <?php
                                    if (date("m") + 1 == 4) {
                                        echo 'selected';
                                    }
                                    ?> >April</option>
                                    <option value='5' <?php
                                    if (date("m") + 1 == 5) {
                                        echo 'selected';
                                    }
                                    ?> >May</option>
                                    <option value='6' <?php
                                    if (date("m") + 1 == 6) {
                                        echo 'selected';
                                    }
                                    ?> >June</option>
                                    <option value='7' <?php
                                    if (date("m") + 1 == 7) {
                                        echo 'selected';
                                    }
                                    ?> >July</option>
                                    <option value='8' <?php
                                    if (date("m") + 1 == 8) {
                                        echo 'selected';
                                    }
                                    ?> >August</option>
                                    <option value='9' <?php
                                    if (date("m") + 1 == 9) {
                                        echo 'selected';
                                    }
                                    ?> >September</option>
                                    <option value='10' <?php
                                    if (date("m") + 1 == 10) {
                                        echo 'selected';
                                    }
                                    ?> >October</option>
                                    <option value='11' <?php
                                    if (date("m") + 1 == 11) {
                                        echo 'selected';
                                    }
                                    ?> >November</option>
                                    <option value='12' <?php
                                    if (date("m") + 1 == 12) {
                                        echo 'selected';
                                    }
                                    ?> >December</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group">
                                <label>Tahun 2</label>
                                <select class="form-control disabled-action" name="tahun2" id="tahun2" readonly>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="form-group">
                    <input id="enable" name="enable" type="checkbox" /> Target 2
                    <input id="target2" type="text" name="target2" class="form-control textbox">
                </div>

            </div>

            <div class="col-xs-4 col-sm-4">

                <div class="row">

                    <div class="col-xs-12 col-sm-12">

                        <div class="col-xs-12 col-sm-7">
                            <div class="form-group">
                                <label>Bulan 3</label>
                                <select class="form-control disabled-action" name="bulan3" id="bulan3" readonly>
                                    <option value='1' <?php
                                    if (date("m") + 2 == 1) {
                                        echo 'selected';
                                    }
                                    ?> >January</option>
                                    <option value='2' <?php
                                    if (date("m") + 2 == 2) {
                                        echo 'selected';
                                    }
                                    ?> >February</option>
                                    <option value='3' <?php
                                    if (date("m") + 2 == 3) {
                                        echo 'selected';
                                    }
                                    ?> >March</option>
                                    <option value='4' <?php
                                    if (date("m") + 2 == 4) {
                                        echo 'selected';
                                    }
                                    ?> >April</option>
                                    <option value='5' <?php
                                    if (date("m") + 2 == 5) {
                                        echo 'selected';
                                    }
                                    ?> >May</option>
                                    <option value='6' <?php
                                    if (date("m") + 2 == 6) {
                                        echo 'selected';
                                    }
                                    ?> >June</option>
                                    <option value='7' <?php
                                    if (date("m") + 2 == 7) {
                                        echo 'selected';
                                    }
                                    ?> >July</option>
                                    <option value='8' <?php
                                    if (date("m") + 2 == 8) {
                                        echo 'selected';
                                    }
                                    ?> >August</option>
                                    <option value='9' <?php
                                    if (date("m") + 2 == 9) {
                                        echo 'selected';
                                    }
                                    ?> >September</option>
                                    <option value='10' <?php
                                    if (date("m") + 2 == 10) {
                                        echo 'selected';
                                    }
                                    ?> >October</option>
                                    <option value='11' <?php
                                    if (date("m") + 2 == 11) {
                                        echo 'selected';
                                    }
                                    ?> >November</option>
                                    <option value='12' <?php
                                    if (date("m") + 2 == 12) {
                                        echo 'selected';
                                    }
                                    ?> >December</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group">
                                <label>Tahun 3</label>
                                <select class="form-control disabled-action" name="tahun3" id="tahun3" readonly>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="form-group">
                    <label>Target 3</label>
                    <input id="target3" type="text" name="target3" class="form-control textbox" >
                </div>

            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>


<script type="text/javascript">

    $(document).ready(function (e) {

        $('#target,#target2,#target3').focusout(function () {}).ForceNumericOnly()
        $('input.textbox:text').attr("disabled", true);
    });

    $(document).ready(function ($) {
        $('input.textbox:text').attr("disabled", true);

        $("input[name='enable']").click(function () {
            if ($(this).is(':checked')) {
                $('input.textbox:text').val('0');
                $('input.textbox:text').attr("disabled", false);
            } else if ($(this).not(':checked')) {
                var remove = '';
                $('input.textbox:text').val('');
                $('input.textbox:text').attr('value', remove);
                $('input.textbox:text').attr("disabled", true);
            }
        });
    });

    var min = new Date().getFullYear(),
            max = min + 2,
            max2 = min + 3,
            select = document.getElementById('tahun');
    select2 = document.getElementById('tahun2');
    select3 = document.getElementById('tahun3');

    for (var i = min; i <= max; i++) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }
    for (var i = min; i <= max2; i++) {
        var opt2 = document.createElement('option');
        var opt3 = document.createElement('option');
        opt3.value = i;
        opt2.value = i;
        opt3.innerHTML = i;
        opt2.innerHTML = i;
        select2.appendChild(opt2);
        select3.appendChild(opt3);
    }

    $('#bulan').change(function () {
        var year = $('#tahun').val();
        var month = $(this).val();
        var day = 1;

        var bulan = new Date(year, month, day);
        var bulan2 = bulan.getMonth() + 1;//new Date(bulan).setMonth(bulan.getMonth()+1);
        var bulan3 = (bulan.getMonth() + 1) + 1;
//        var lastday = bulan3.getDate()-1;
        
        var date = new Date(bulan).setMonth(bulan.getMonth() + 1);
        var year2 = bulan.getFullYear();
        var year3 = bulan.getFullYear();
        if (bulan3 == 13) {
            bulan3 = 1;
            year3 = year3 + 1;
        }

        $('#bulan2').val(bulan2);
        $('#bulan3').val(bulan3);
        $('#tahun2').val(year2);
        $('#tahun3').val(year3);

//        console.log(tahun2);
//        console.log(bulan3);
//        console.log(bulan2);
//        console.log(lastday);
    });

    $('#tahun').change(function () {
        var year = $(this).val();
        var month = $('#bulan').val();
        var day = 1;
        var bulan = new Date(year, month, day);
        var bulan2 = bulan.getMonth() + 1;//new Date(bulan).setMonth(bulan.getMonth()+1);
        var bulan3 = (bulan.getMonth() + 1) + 1;
        var date = new Date(bulan).setMonth(bulan.getMonth() + 1);
        var year2 = bulan.getFullYear();
        var year3 = bulan.getFullYear();
        if (bulan3 == 13) {
            bulan3 = 1;
            year3 = year3 + 1;
        }

        $('#tahun2').val(year2);
        $('#tahun3').val(year3);
    });
</script>


