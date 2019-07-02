<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Laporan Kas Harian</title>
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pdf-style.css" > -->
    <link rel="stylesheet" href="assets/css/pdf-style.css" >
    <style type="text/css">
        .table-content{
            font-size: 9pt!important;
        }
        table tr {
            border:1px solid #fff;
        }
    </style>
  </head>
  <body>
    <header class="clearfix">
<?php
  $usergroup=$this->session->userdata("kd_group");
  $mode=($this->input->get("t"))?"":"hidden";
?>
<section class="wrapper">
    <div id="printarea" style="width:100%">
        <div class="row">
            <table class='table' style="width:100%">
                <tr>
                    <td><h4><?php echo NamaDealer($this->session->userdata("kd_dealer"));?></h4></td>
                <tr>
                    <td style="text-align: center"><h2>LAPORAN KAS HARIAN</h2></td>
                </tr>
                <tr>
                    <td align="center">Tanggal : <?php echo ($this->input->get('tgl_trans_aw'))?$this->input->get('tgl_trans_aw'):date('d/m/Y');?></td>
                </tr>
            </table>
        </div>
        <div class="table-content">
            <table style="width:100%; border-collapse: collapse; border-width: 0.5px!important" border="1">
                <!-- <thead> -->
                    <tr style="background-color: grey">
                        <th style="width:5%">No.</th>
                        <th style="width:15%">No.Trans</th>
                        <th style="width:45%">Keterangan</th>
                        <!-- <th style="width:5%">jns</th> -->
                        <th style="width:10%">Ket</th>
                        <th style="width:10%">Debet</th>
                        <th style="width:10%">Kredit</th>
                    </tr>
                <!-- </thead> -->
                <!-- <tbody> -->
                    <?php
                    $saldoAkhir=0;$n=0;$kredit=0;$debet=0;
                    $totalWO=0;$totalSO=0;
                    //print_r($list);exit();
                    if(isset($list)){ 
                            if($list->totaldata > 0){
                                foreach ($list->message as $key => $value) {
                                    $n++;
                                    $saldoAkhir=$value->SALDO_AKHIR;
                                    $kredit +=($value->HARGA_AKHIR>0 && $value->POSTING_STATUS==1)?$value->HARGA_AKHIR:0;
                                    $debet +=($value->HARGA_AKHIR<0 && $value->POSTING_STATUS==1)?$value->HARGA_AKHIR:0;
                                    switch (substr($value->NO_REFF,0,2)) {
                                        case 'SO': $totalSO +=$value->HARGA_AKHIR; break;
                                        case 'WO': $totalWO +=$value->HARGA_AKHIR; break;
                                        default:
                                            # code...
                                            break;
                                    }
                                    //if ( $value->POSTING_STATUS==1 ){

                                    ?>

                                    <tr class='<?php echo ( $value->POSTING_STATUS==1 || (int)$value->URUTAN ==0 )?'':'info';?>'>
                                        <td style="text-align: center;"><?php echo $n;?></td>
                                        <td valign="top" style="text-align: center; white-space: nowrap;"><?php echo $value->NO_TRANS;?></td>
                                        <td><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                        <!-- <td class="text-center">&nbsp;</td> -->
                                        <td style="text-align: center;"><?php echo ($value->POS_AKUN);?></td>
                                        <?php 
                                        if($value->URUTAN==0){ ?>
                                            <td style="text-align: right;"><?php echo number_format(($value->HARGA_AKHIR),0);?></td>
                                            <td style="text-align: right;"><?php echo "0";?></td>
                                       <?php 
                                        }else{
                                            ?>
                                            <td style="text-align: right;"><?php echo(($value->HARGA_AKHIR>=0))? number_format(($value->JUMLAH*$value->HARGA),0):"0";?></td>
                                            <td style="text-align: right;"><?php echo(($value->HARGA_AKHIR<0))? number_format(($value->JUMLAH*$value->HARGA),0):"0";?></td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                    <?
                                   //}
                                }
                            }
                        }
                    ?>
                <!-- </tbody>
                <tfoot> -->
                    <tr class="subtotal">
                        <td colspan="4" class="text-right" style="text-align: right; padding-right: 10px"><b><i>Total</i></b></td>
                        <td style="text-align: center;"><?php echo number_format($kredit,0);?></td>
                        <td style="text-align: center;"><?php echo number_format(abs($debet),0);?></td>
                    </tr>
                    <!-- <tr class="subtotal"><td colspan="4">&nbsp;</td>
                        <td colspan="2">Saldo Kasir</td>
                        <td class="text-right"><?php echo number_format($saldoAkhir,0);?></td>
                    </tr> -->
                    <?php
                        $kus=0;$ceks=0;$total_kucek=0;
                        $kus= (isset($ku))? ($ku->totaldata>0)?($ku->message[0]->TOTAL_HARGA):0:0;
                        $ceks =  (isset($cek))? ($cek->totaldata>0)?($cek->message[0]->TOTAL_HARGA):0:0;
                        $total_kucek=($kus+$ceks);
                    ?>
                    <tr class="subtotal">
                        <td colspan="6">
                            <table style="width:100%; border-collapse: collapse;">
                                <tr>
                                    <td colspan="2">Total CEK STNK<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;">
                                        <?php echo (isset($cek_stnk))?($cek_stnk->totaldata>0)? number_format($cek_stnk->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                    <td>( KREDIT )</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Total KU BPKB<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($ku_bpkb))?($ku_bpkb->totaldata>0)? number_format($ku_bpkb->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                    <td>( KREDIT )</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <!-- <tr class="hidden">
                                    <td colspan="2">Total CEK STNK<span class='pull-right'>:</span></td>
                                    <td class='text-right'>0</td>
                                    <td class="text-left">( KREDIT )</td>
                                    <td>&nbsp;</td>
                                    <td>TOTAL KU RP<span class='pull-right'>:</span></td>
                                    <td class='text-right'>0</td>
                                </tr> -->
                                <tr>
                                    <td colspan="2">Pinj. Pengurusan STNK/BPKB Gantung<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($pinjaman))? number_format($pinjaman->message[0]->HARGA,0):"0";?></td>
                                    <td>( KREDIT )</td>
                                    <td>&nbsp;</td>
                                    <td>TOTAL KU RP<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo number_format($kus,0);?></td>
                                </tr>
                                <tr style="border-bottom:2px solid grey !important">
                                    <td colspan="2">Pinj. Sementara Gantung<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;">0</td>
                                    <td>( KREDIT )</td>
                                    <td>&nbsp;</td>
                                    <td>TOTAL CEK RP<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo number_format($ceks,0);?></td>
                                </tr>
                                <tr>
                                    <td>Total Cek DEBET<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;">0</td>
                                    <td>Total Batal Cek DEBET<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;">0</td>
                                    <td>&nbsp;</td>
                                    <td>TOTAL KU/CEK RP<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo number_format($total_kucek,0);?></td>
                                </tr>
                                <tr style="border-bottom:2px solid grey !important">
                                    <td>Total Cek KREDIT<span class='pull-right'>:</span></td>
                                    <td  style="text-align: center;">0</td>
                                    <td>Total Batal Cek KREDIT<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;">0</td>
                                    <td>&nbsp;</td>
                                    <td>SALDO KASIR<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo number_format($saldoAkhir,0);?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td>Jasa Bengkel<span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($jasa))? ($jasa->totaldata >0)? number_format($jasa->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td style="text-align: center;"></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td>Part Bengkel <span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($part_bengkel))?($part_bengkel->totaldata >0)? number_format($part_bengkel->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                    <td>&nbsp;</td>
                                    <td>Part Counter <span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($part_counter))?($part_counter->totaldata > 0)? number_format($part_counter->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                </tr>
                                <tr>
                                    <td>Total PKB Pause  <span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($pkb))?($pkb->totaldata > 0)? number_format($pkb->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                    <td>Oli Bengkel <span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($oli_bengkel))?($oli_bengkel->totaldata >0)?number_format($oli_bengkel->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                    <td>&nbsp;</td>
                                    <td>Oli Counter <span class='pull-right'>:</span></td>
                                    <td style="text-align: center;"><?php echo (isset($oli_counter))?($oli_counter->totaldata >0)? number_format($oli_counter->message[0]->TOTAL_HARGA,0):"0":"0";?></td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="6"><small><em><?php echo date('d-m-Y H:i:s');?></em></small></td></tr>

                </tfoot>
            </table>
        </div>
    </div>
</section>