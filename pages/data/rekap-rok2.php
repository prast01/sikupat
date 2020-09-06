<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="pages/style-galery.css">

<style> 
	body{
		background:#FFFFFF;
	}
    th{
        color: black;
        font: Times New Roman;
		text-align:right;
		font-size: 11px;
    }
    td{
        font-size: 12px;
        color: black;
        font: Times New Roman;
    }
a:link {
	color: #3333FF;
}
a:visited {
	color: #0000FF;
}
</style>

<?php
error_reporting(E_ALL ^ E_WARNING);
$bb = array(
    '01' => '1',
    '02' => '2',
    '03' => '3',
    '04' => '4',
    '05' => '5',
    '06' => '6',
    '07' => '7',
    '08' => '8',
    '09' => '9',
    '10' => '10',
    '11' => '11',
    '12' => '12',
    'b1' => '1',
    'b2' => '2',
    'b3' => '3',
    'b4' => '4',
    'b5' => '5',
    'b6' => '6',
    'b7' => '7',
    'b8' => '8',
    'b9' => '9',
    'b10' => '10',
    'b11' => '11',
    'b12' => '12'
);

$bln = date("m");
if($bln==01){
    $bulan2 = "b1";
    $b = '1';
}elseif($bln==02){
	$bulan2 = "b2";
    $b = '2';
}elseif($bln==03){
	$bulan2 = "b3";
    $b = '3';
}elseif($bln==04){
	$bulan2 = "b4";
    $b = '4';
}elseif($bln==05){
	$bulan2 = "b5";
    $b = '5';
}elseif($bln==06){
	$bulan2 = "b6";
    $b = '6';
}elseif($bln==07){
	$bulan2 = "b7";
    $b = '7';
}elseif($bln==08){
	$bulan2 = "b8";
    $b = '8';
}elseif($bln==09){
	$bulan2 = "b9";
    $b = '9';
}elseif($bln==10){
	$bulan2 = "b10";
    $b = '10';
}elseif($bln==11){
	$bulan2 = "b11";
    $b = '11';
}elseif($bln==12){
	$bulan2 = "b12";
    $b = '12';
}

$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : $bulan2;
$b = isset($_POST['bulan']) ? $bb[$_POST['bulan']] : $bb[date('m')];
$ckseksi = isset($_REQUEST['ckseksi']) ? $_REQUEST['ckseksi'] : '';


?>

<div class="tab-content">			
	<div class="panel-body" >
			<div class="panel panel-default" style="">			
				<div class="panel-body">
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Bulan</label>
                            <div class="col-sm-4">
                                <select name="bulan" id="bulan" class="form-control">
                                    <?php 
                                    if($bulan==""){
                                        
                                        if($bulan2 =="b1"){
                                    ?>
                                        <option value="b1">Januari</option>
                                    <?php
                                    }elseif ($bulan2 =="b2") {
                                    ?>    
                                        <option value="b2">Februari</option>
                                    <?php
                                    }elseif ($bulan2 =="b3") {
                                    ?>    
                                        <option value="b3">Maret</option>
                                    <?php
                                    }elseif ($bulan2 =="b4") {
                                    ?>    
                                        <option value="b4">April</option>
                                    <?php
                                    }elseif ($bulan2 =="b5") {
                                    ?>    
                                        <option value="b5">Mei</option>
                                    <?php
                                    }elseif ($bulan2 =="b6") {
                                    ?>    
                                        <option value="b6">Juni</option>
                                    <?php
                                    }elseif ($bulan2 =="b7") {
                                    ?>    
                                        <option value="b7">Juli</option>
                                    <?php
                                    }elseif ($bulan2 =="b8") {
                                    ?>    
                                        <option value="b8">Agustus</option>
                                    <?php
                                    }elseif ($bulan2 =="b9") {
                                    ?>    
                                        <option value="b9">September</option>
                                    <?php
                                    }elseif ($bulan2 =="b10") {
                                    ?>    
                                        <option value="b10">Oktober</option>
                                    <?php
                                    }elseif ($bulan2 =="b11") {
                                    ?>    
                                        <option value="b11">November</option>
                                    <?php
                                    }elseif ($bulan2 =="b12") {
                                    ?>    
                                        <option value="b12">Desember</option>
                                    <?php
                                        }
                                    ?>
                                        
                                        <option value="b1">Januari</option>
                                        <option value="b2">Februari</option>
                                        <option value="b3">Maret</option>
                                        <option value="b4">April</option>
                                        <option value="b5">Mei</option>
                                        <option value="b6">Juni</option>
                                        <option value="b7">Juli</option>
                                        <option value="b8">Agustus</option>
                                        <option value="b9">September</option>
                                        <option value="b10">Oktober</option>
                                        <option value="b11">November</option>
                                        <option value="b12">Desember</option>
                                        
                                        
                                    <?php    
                                    }else{
                                    ?>
                                    
                                    <?php 
                                    if($bulan=="b1"){
                                    ?>
                                        <option value="b1">Januari</option>
                                    <?php
                                    }elseif ($bulan=="b2") {
                                    ?>    

                                        <option value="b2">Februari</option>
                                    <?php
                                    }elseif ($bulan=="b3") {
                                    ?>    
                                        <option value="b3">Maret</option>
                                    <?php
                                    }elseif ($bulan=="b04") {
                                    ?>    
                                        <option value="b4">April</option>
                                    <?php
                                    }elseif ($bulan=="b5") {
                                    ?>    
                                        <option value="b5">Mei</option>
                                    <?php
                                    }elseif ($bulan=="b6") {
                                    ?>    
                                        <option value="b6">Juni</option>
                                    <?php
                                    }elseif ($bulan=="b7") {
                                    ?>    
                                        <option value="b7">Juli</option>
                                    <?php
                                    }elseif ($bulan=="b8") {
                                    ?>    
                                        <option value="b8">Agustus</option>
                                    <?php
                                    }elseif ($bulan=="b9") {
                                    ?>    
                                        <option value="b9">September</option>
                                    <?php
                                    }elseif ($bulan=="b10") {
                                    ?>    
                                        <option value="b10">Oktober</option>
                                    <?php
                                    }elseif ($bulan=="b11") {
                                    ?>    
                                        <option value="b11">November</option>
                                    <?php
                                    }elseif ($bulan=="b12") {
                                    ?>    
                                        <option value="b12">Desember</option>
                                    <?php
                                    }
                                    ?>
                                    <option value="b1">Januari</option>
                                    <option value="b2">Februari</option>
                                    <option value="b3">Maret</option>
                                    <option value="b4">April</option>
                                    <option value="b5">Mei</option>
                                    <option value="b6">Juni</option>
                                    <option value="b7">Juli</option>
                                    <option value="b8">Agustus</option>
                                    <option value="b9">September</option>
                                    <option value="b10">Oktober</option>
                                    <option value="b11">November</option>
                                    <option value="b12">Desember</option>
                                    <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <div class="widget-header">			
                            <h5 class="widget-title">REKAP ROK</h5>
                            <span class="widget-toolbar">
                                <a href="#" data-action="reload">
                                    <i class="ace-icon fa fa-refresh"></i>
                                </a>
                
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                
                                <a href="#" data-action="close">
                                    <i class="ace-icon fa fa-times"></i>
                                </a>
                            </span>
                        </div>
                        <table id="table1" class="table table-bordered table-hover" width="100%">
                            <thead>
                                <tr>		
                                    <td style="text-align:center"><font color=""><b>NO</b></font></td>
                                    <td style="text-align:center"><font color=""><b>KEGIATAN</b></font></td>
                                    <td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
                                    <td style="text-align:center"><font color=""><b>TOTAL</b></font></td>
                                    <td style="text-align:center"><font color=""><b>BBM/PERDIN</b></font></td>
                                    <td style="text-align:center"><font color=""><b>KEGIATAN</b></font></td>
                                    <td style="text-align:center"><font color=""><b>BBM/PERDIN (%)</b></font></td>
                                    <td style="text-align:center"><font color=""><b>KEGIATAN (%)</b></font></td>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                        $no = 1;
                                        $query = mysql_query("SELECT `kegiatan`.`nm_kegiatan` AS `nm_kegiatan`, `kegiatan`.`kbidang` AS `kbidang`, `kegiatan`.`id_kegiatan` AS `id_kegiatan`, `user`.`nm_seksi` AS `nm_seksi`,`user`.`kseksi` AS `kseksi`,`tb_valid_rok`.`id_valid` AS `id_valid` from ((`kegiatan` join `tb_valid_rok` on((`kegiatan`.`id_kegiatan` = `tb_valid_rok`.`id_kegiatan`))) join `user` on((`tb_valid_rok`.`kseksi` = `user`.`kseksi`))) group by `tb_valid_rok`.`kseksi`,`tb_valid_rok`.`id_kegiatan` ORDER BY `kegiatan`.`id_kegiatan` ASC");

                                        while ($hasil = mysql_fetch_array($query)){
                                            $nm_kegiatan	= $hasil['nm_kegiatan'];
                                            $nm_seksi	= $hasil['nm_seksi'];
                                            $id_kegiatan	= $hasil['id_kegiatan'];
                                            $kseksi	= $hasil['kseksi'];
                                            $kbidang	= $hasil['kbidang'];

                                            // BBM/Perdin
                                            if ($kbidang != 'DK007') {
                                                if ($id_kegiatan != '13' && $id_kegiatan != '16') {
                                                    $d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE bulan='$b' and seksi_pl='$kseksi' and id_keg='6' and seksi='DJ002' and id_keg_pl='$id_kegiatan'"));
                                                } else {
                                                    $d = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) as nominal FROM target_detail a, spj b WHERE a.bulan='$b' and a.seksi='$kseksi' and a.id_keg='$id_kegiatan' AND (b.gol='1' OR b.gol='2') AND a.id_spj=b.id_spj"));
                                                }
                                                $nom = number_format($d['nominal'], 0, ',', '.');
                                            } else {
                                                $d = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) as nominal FROM target_detail a, spj b WHERE a.bulan='$b' and a.seksi='$kseksi' and a.id_keg='$id_kegiatan' AND (b.gol='1' OR b.gol='2') AND a.id_spj=b.id_spj"));
                                                $nom = number_format($d['nominal'], 0, ',', '.');
                                            }
                                            

                                            // Non BBM/Perdin
                                            $d2 = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) as nominal FROM target_detail a, spj b WHERE a.bulan='$b' and a.seksi='$kseksi' and a.id_keg='$id_kegiatan' AND (b.gol!='1' OR b.gol!='2') AND a.id_spj=b.id_spj"));
                                            $nom2 = number_format($d2['nominal'], 0, ',', '.');

                                            $total = $d['nominal'] + $d2['nominal'];
                                            $total2 = number_format($total, 0, ',', '.');

                                            if ($d['nominal'] == '0') {
                                                $p1 = 0;
                                            } else {
                                                $p1 = ($d['nominal']/$total)*100;
                                                $px1 = number_format($p1, 2, ',', '.');
                                            }
                                            if ($d2['nominal'] == '0') {
                                                $p2 = 0;
                                            } else {
                                                $p2 = ($d2['nominal']/$total)*100;
                                                $px2 = number_format($p2, 2, ',', '.');
                                            }
                                            
                                    ?>
                                <tr>	
                                    <td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $total2; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $nom; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $nom2; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $px1; ?></td>
                                    <td valign="top" align="left" bgcolor=""><?php echo $px2; ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/lib/jquery/jquery.js"></script>
<script>
  $(function () {
	$('#table1').DataTable({
	  "paging": true,
	  "lengthChange": true,
	  "searching": true,
	  "ordering": true,
	  "info": true,
	  "autoWidth": false
	});
  });
</script>
