<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Leasing Skema Credit</title>

    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" > -->

        <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->

        <style type="text/css">



            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-bottom: 2cm;
                font-size: 10px;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;

                border-top: 1px solid  #5D6975;
                border-bottom: 1px solid  #5D6975;
                color: #5D6975;
                line-height: 1.4em;
                font-weight: normal;
                margin: 5px 0 5px 0;
                /*background: url("../images/dimension.png");*/

                /*height: 60px;*/
                /*min-height: 60px;*/
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                text-align: center;
                /*line-height: 1.5cm;*/
            }

            #header-left
            {
                /*width: 100px;*/
                /*height: 100px;*/
                float: left; 
                /*background: url("../images/honda_logo.png");*/
                /*background-size: 100px;*/
            }
            #header-right
            {
                /*width: 100px;*/
                /*height: 100px;*/
                float: right; 
                /*background: url("../images/ahass.png");*/
                /*background-size: 100px;*/
            }

            /*#desc {
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px;
                width: 100%;
            }*/
            .project {
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
                font-size: 9px;
            }

            .project span {
                text-align: left;
                padding: 2px 0;
                display: table-cell;
            }

            .project .content {
                width: 200px;
                font-size: 9px;
            }

            .project p{
                margin: 0;
            }

            .page-break-before{
                page-break-before: always;
            }

            .page-break-after{
                page-break-after: always;
            }
            main{
                font-size: 8px;
            }

            table.table-full {
                width: 100%;
            }

            table.table-border{
                border-collapse: collapse;
            }

            table.table-border td {
                border: 1px solid  #5D6975;
            }

            td{
                vertical-align: top;
                padding: 5px;
            }

            td.content{
                font-size: 9px;
            }

            td.title{
                font-size: 9px;
            }

            input.type1
            {
                border: 1px solid  #5D6975;
                height: 10px;
                width: 20px;

            }
            input.type2{
                border: 1px solid  #5D6975;
                height: 10px;
                width: 10px;

            }
        </style>

    </head>
    <body>

        <?php
        $tanggal = date('d/m/Y');
        $kd_typemotor = "";
        $start_date = date('d/m/Y');
        $end_date = date('d/m/Y');
        $harga_otr = "";
        $keterangan = "";

        if ($list) {

            if (is_array($list->message)) {

                foreach ($list->message as $key => $value) {
                    if (isset($list)) {
                        if ($list->totaldata > 0) {
                            foreach ($list->message as $key => $value) {
                                $kd_dealer = $value->KD_DEALER;
                                $kd_leasing = $value->KD_LEASING;
                                $nama_leasing = $value->NAMA_LEASING;
                                $tanggal = TglFromSql($value->TGL_TRANS);
                                $no_trans = $value->NO_TRANS;
                                $kd_typemotor = $value->KD_TYPEMOTOR;
                                $nama_motor = $value->NAMA_PASAR;
                                $harga_otr = intval($value->HARGA_OTR);
                                $start_date = TglFromSql($value->START_DATE);
                                $end_date = TglFromSql($value->END_DATE);
                                $keterangan = $value->KETERANGAN;
                            }
                        }
                    }
                }
            }
        }

        $KD_DEALERAHM = '';
        $NAMA_DEALER = '';
        $ALAMAT = '';
        $TLP = '';

        if ($dealer) {

            if (is_array($dealer->message)) {

                foreach ($dealer->message as $key => $value) {
                    if ($value->KD_DEALER == $kd_dealer) {
                        $KD_DEALERAHM = $value->KD_DEALERAHM;
                        $NAMA_DEALER = $value->NAMA_DEALER_ASLI;
                        $ALAMAT = $value->ALAMAT;
                        $TLP = $value->TLP . ($value->TLP2 ? ' / ' . $value->TLP2 : '') . ($value->TLP3 ? ' / ' . $value->TLP3 : '');
                    }
                }
            }
        }
        ?>
        <header class="clearfix">
            <div id="header" style="align-items:center;">
                <p style="text-align: center;">
                    <strong><?php echo $NAMA_DEALER; ?></strong><br>
                    <span style="font-size: 8px;">
                        <?php echo $ALAMAT; ?>
                    </span><br>
                    <strong style="font-size: 10px;"><?php echo $nama_leasing; ?></strong>
                    <br/>
                    <strong style="font-size: 10px;"><?php echo "[" . $kd_typemotor . "] " . $nama_motor; ?></strong>
                    <br/>
                    <strong style="font-size: 10px;"><?php echo "Rp. ".number_format($harga_otr, 0).",-"; ?></strong>
                </p>
            </div>

        </header>

        <main>
            <br/><br/>
            <div id="lsc-header"style="text-align: center; border-bottom: 1px solid  #5D6975;">
                <h5 style="margin:10px 0 10px 0;font-size: 10px">LEASING SCHEMA CREDIT</h5>
            </div>

            <br/>

            <table class="table-full table-border">
                <tr>
                    <td class="title" rowspan="2" style="vertical-align: middle;text-align: center">Uang Muka</td>
                    <td class="title" colspan="<?php echo $jumlahTenor; ?>" style="text-align:center">Angsuran</td>
                </tr>
                <tr>
                    <?php
                    foreach ($listDetail->message as $key => $value) {
                        foreach ($value as $item => $list1) {
                            if (!strcspn($item, '0123456789')) {
                                echo "<td class ='title' style = 'text-align:center'> $item </td>";
                            }
                        }
                        break;
                    }
                    ?>
                </tr>
<!--                <tr>
                    <td>TEST</td>
                </tr>-->
                <?php
                foreach ($listDetail->message as $key => $value) {
                    echo "<tr>
                            <td style = 'text-align:center'> Rp. ".number_format($value->UANG_MUKA, 0).",-</td>";
                    foreach ($value as $item => $list2) {
                        if (!strcspn($item, '0123456789')) {
                            echo "<td style = 'text-align:center'> Rp. ".number_format($list2, 0).",-</td>";
                        }
                    }
                    echo"</tr>";
                }
                ?>
            </table>
        </main>
    </body>
</html>