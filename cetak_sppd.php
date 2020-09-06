<?php
	/** format tanggal Indo**/
	
	date_default_timezone_set('Asia/Jakarta');
	function tgl_ind($date) {
	
	/** ARRAY HARI DAN BULAN**/	
			$Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
			$Bulan = array("Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nov","Desember");
			
	/** MEMISAHKAN FORMAT TANGGAL, BULAN, TAHUN, DENGAN SUBSTRING**/		
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl = substr($date, 8, 2);
		$waktu = substr($date, 11, 8);		
		$hari = date("w", strtotime($date));
		
		$result = $tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu."";
		return $result;
		} 
		
	$tgl = date('Y-m-d'); // sesuaikan dari hasil output query select tabel database
	$tanggal = tgl_ind($tgl); // ini adalah kode untuk menampilkan fungsi pada file functiopn_tanggal.php 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Cetak</title>
		<style>
			@media print {
				.pagebreak {
					clear: both;
					page-break-after: always;
				}
				td, th {
					font-size: 12px;
				}
			}
		
			.footer{
				position : fixed;
				left : 0;
				bottom : 0;
				width : 100%;
				text-align : left;
			}
		</style>
	</head>
	<body onLoad="window.print()" onclick="">
		<table border="0" style="border-collapse:collapse; border-bottom:1px solid" width="100%">
			<thead>
				<tr>
					<th width="25%">
						<img src="img/logo-jepara.png" width="100px" style="padding: 5px 5px 5px 5px; border: 0px solid">
					</th>
					<td colspan="2" align="center">
						<h2 style="margin-bottom:-20px">PEMERINTAH KABUPATEN JEPARA</h2>
						<h1 style="margin-bottom:-10px">DINAS KESEHATAN</h1>
						<p>Jl. Kartini No. 44 Telp. (0291) 591427, 591743 Fax. (0291) 591427<br>
						E-mail : <u>dinkeskabjepara@yahoo.co.id</u><br>
						J E P A R A 59411
						</p>
					</td>
				</tr>
			</thead>
		</table>
		<br>
		<table border="0" width="100%">
			<tr>
				<td width="66%"></td>
				<td width="6%">Lampiran</td>
				<td width="28%">:</td>
			</tr>
			<tr>
				<td></td><td>Kode No</td><td>:</td>
			</tr>
			<tr>
				<td></td><td>Nomor</td><td>:</td>
			</tr>
		</table>
		
		<table border="0" width="100%">
			<tr>
				<td align="center" colspan="3"><h2>SURAT PERINTAH PERJALANAN DINAS (SPPD)</h2>
			  </td>
			</tr>
		</table>
		<table border="1" style="border-collapse:collapse;" width="100%">
			<tr>
				<td width="3%" align="center" valign="middle">1</td>
				<td width="31%"><p>Pejabat berwenang yang memberi perintah</p></td>
				<td width="66%">Kepala Dinas Kesehatan Kab. Jepara</td>
			</tr>
			<tr>
				<td align="center" valign="middle">2</td>
				<td><p>Nama/NIP Pegawai yang melaksanakan Perjalanan Dinas</p></td>
				<td></td>
			</tr>
			<tr>
				<td align="center" valign="middle">3</td>
				<td>
				<p>
					a. Pangkat dan Golongan<br>
					b. Jabatan/ Instansi<br>
					c. Tingkat biaya perjalanan dinas
				</p>
				</td>
				<td>
				<p>
					a. 
					<br>
					b. 
					<br>
					c. 
				</p>
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">4</td>
				<td><p>Maksud Perjalanan Dinas</p></td>
				<td></td>
			</tr>
			<tr>
				<td align="center" valign="middle">6</td>
				<td>
				<p>
					a. Tempat Berngkat<br>
					b. Tempat Tujuan<br>
					</p>
					</td>
					<td>
					<p>
					a. DKK Jepara
					<br>
					b. 
					<br>
				</p>
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">7</td>
				<td>
				<p>
					a. Lamanya Perjalanan Dinas<br>
					b. Tanggal Berangkat<br>
					c. Tanggal Harus Kembali
					</p>
					</td>
					<td>
					<p>
					a. 
					<br>
					b. 
					<br>
					c.
				</p>
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">8</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Pengikut &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Golongan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Keterangan</td>
			</tr>
			<tr>
				<td align="center" valign="middle"></td>
				<td>
				<p>
					1. <br>
					2. <br>
					3.
					</p>
					</td>
					<td>
					<p>
					
					<br>
					
					<br>
					
				</p>
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">9</td>
				<td>
				<p>
					Pembebanan Anggaran<br>
					a. Instansi<br>
					b. Mata Anggaran
					</p>
					</td>
					<td>
					<p>
					 
					<br>
					a. DKK Jepara
					<br>
					b.
				</p>
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">10</td>
				<td>
				<p>Keterangan lain-lain</p>
				</td>
				<td></td>
			</tr>
		</table>
		<br>
		<table border="0" style="border-collapse:collapse;" width="100%">
			<tr>
				<td width="1%" align="center" valign="middle"></td>
				<td width="42%"></td>
				<td width="16%">Dikeluarkan di</td>
				<td width="1%">:</td>
				<td width="40%">Jepara</td>
			</tr>
			<tr>
				<td width="1%" align="center" valign="middle"></td>
				<td width="42%"></td>
				<td width="16%">Pada Tanggal</td>
				<td>:</td><td><?php echo $tanggal; ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="3"><hr></td>
			</tr>
		</table>
		<table border="0" width="100%">
			<tr style="height: 10px">
				<td width="5%"></td>
				<td width="30%">
					<p style="margin-bottom:-10px"></p>
					<p></p>
				</td>
				<td width="26%">
					<p style="margin-bottom:-10px"></p>
					<p></p>
			  </td>
				<td width="39%">
					<p style="margin-bottom:-10px">Ka. Dinas Kesehatan Kabupaten Jepara</p>
					<p></p>
			  </td>
			</tr>
			<tr>
				<td width="5%"></td>
				<td width="30%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"></p>
					<p></p>
				</td>
				<td width="26%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"></p>
					<p></p>
				
			  </td>
				<td>
					<br>
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline">Mudrikatun, S.SiT, SKM, MM.Kes, MH</p>
					<p>NIP. 19690610 199003 2 010</p>
				</td>
			</tr>
		</table>
		
		<div class="pagebreak"></div>
		
		<table border="1" style="border-collapse:collapse;" width="100%">
			<tr>
				<td width="50%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"></p>
					<p></p>
				</td>
				<td>
					<table width="100%">
						<tr>
							<td width="5%">I.</td>
							<td width="31%">Berangkat dari</td>
							<td width="1%">:</td>
							<td width="63%">DKK Jepara</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="4">(Tempat Kedudukan)</td>
						</tr>
						<tr>
							<td></td>
							<td>Ke</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"><hr></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center">Ka. Dinas Kesehatan Kabupaten Jepara</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><u>Mudrikatun, S.SiT, SKM, MM.Kes, MH</u></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center">NIP. 19690610 199003 2 010</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td width="50%">
					<table width="100%">
						<tr>
							<td width="5%">II.</td>
							<td width="31%">Tiba di</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>&nbsp;</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">(............................................................)</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP.</td>
						</tr>
					</table>
				</td>
				<td>
					<table width="100%">
						<tr>
							<td width="5%">&nbsp;</td>
							<td width="31%">Berangkat dari</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>Ke</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">(............................................................)</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP.</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td width="50%">
					<table width="100%">
						<tr>
							<td width="5%">III.</td>
							<td width="31%">Tiba di</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>&nbsp;</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">(............................................................)</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP.</td>
						</tr>
					</table>
				</td>
				<td>
					<table width="100%">
						<tr>
							<td width="5%">I.</td>
							<td width="31%">Berangkat dari</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>Ke</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">(............................................................)</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP.</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td width="50%">
					<table width="100%">
						<tr>
							<td width="5%">IV.</td>
							<td width="31%">Tiba di</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>&nbsp;</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">(............................................................)</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP.</td>
						</tr>
					</table>
				</td>
				<td>
					<table width="100%">
						<tr>
							<td width="5%">&nbsp;</td>
							<td width="31%">Berangkat dari</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>Ke</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">(............................................................)</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP.</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td width="50%">
					<table width="100%">
						<tr>
							<td width="5%">V.</td>
							<td width="31%">Tiba di</td>
							<td width="1%">:</td>
							<td width="63%"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3">(Tempat Kedudukan)</td>
						</tr>
						<tr>
							<td></td>
							<td>Pada Tangal</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">Ka. Dinas Kesehatan Kabupaten Jepara</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left"><u>Mudrikatun, S.SiT, SKM, MM.Kes, MH</u></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP. 19690610 199003 2 010</td>
						</tr>
					</table>
				</td>
				<td width="50%">
					<table width="100%">
						<tr>
							<td width="5%">&nbsp;</td>
							<td colspan="3"><font size="-2">Telah diperiksa dengan keterangan bahwa perjalanan tersebut</font></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3"><font size="-2">atas perintahnya dan semata-mata untuk kepentingan jabatan</font></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3"><font size="-2">dalam waktu yang sesingkat-singkatnya</font></td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left"><font size="-2">Pejabat Pelaksana Keuangan SKPD(PPK-SKPD)</font></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="center"><br><br><br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left"><u>Drs. Adi Bintoro, MM</u></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" align="left">NIP. 19630326 199203 1 002</td>
						</tr>
					</table>
				</td>				
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%">
						<tr>
							<td width="5%">VI.</td>
							<td colspan="3">Catatan lain-lain</td>
						</tr>

					</table>				
				</td>
			</tr>							
		</table>
		<table width="100%">
			<tr>
				<td width="5%">VII.</td>
				<td colspan="2">PERHATIAN</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2"><font size="-2">Pejabat yang berwenang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang<br>
				mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggungjawab berdasarkan peraturan-peraturan<br>
				keuangan Negara apabila Negara menderita kerugian akibat kesalahan, kealpaannya</font>
				</td>
			</tr>
		</table>
			
	</body>
</html>
