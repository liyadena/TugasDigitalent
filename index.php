
<?php

	$berkas = "data/data.json";
	$dataJson = file_get_contents($berkas);
	$rutePenerbanganAll = json_decode($dataJson, true);

	//Array Daftar Bandara dan Pajak
	$jenisMobil = array ("Sedan", "SUV", "Hatchback", "MPV");									//Array bandara asal penerbangan
	$Sopir = array ("Ya", "Tidak"); 											//Aray bandara tujuan penerbangan 
	$hargaJenisMobil = array ("Sedan"=>75000, "SUV"=>1000000, "Hatchback"=>1500000, "MPV"=>200000);	//Array pajak dari bandara asal
	$hargaSopir = array ("Ya"=>50000, "Tidak"=>0);


	//Fungsi Menghitung Total Pajak Bandara
	/**
		Fungsi ini berguna untuk menghitung total pajak bandara yang harus dibayarkan
		-- Argumen pertama berisi pajak dari bandara asal penerbangan
		-- Argumen kedua berisi pajak dari bandara tujuan penerbangan
		-- Balikan dari Fungsi ini adalah Total Pajak yang harus dibayarkan
		Author : nama
		Tanggal : 19 Oktober 2020
	**/
	function totalBiayaSewa($hargasewamobil, $hargapakaisopir){
		global $hargaJenisMobil, $hargaSopir;											
		//Variabel Global

		foreach ($hargaJenisMobil as $harga1 =>$harga1_value) {									//Mengambil biaya pajak dari bandara asal yang dipilih
			if($hargasewamobil == $harga1){
				$nilaimobil = $harga1_value;
			}
		}

		foreach ($hargaSopir as $harga2 =>$harga2_value) {									//Mengambil biaya pajak dari bandara tujuan yang dipilih
			if($hargapakaisopir == $harga2){
				$nilaisopir = $harga2_value;
			}
		}

		return $nilaimobil + $nilaisopir;
	}

	/**
		Fungsi ini berguna untuk menghitung total biaya penerbangan sebuah maskapai
		-- Argumen pertama berisi total pajak dari Bandara
		-- Argumen kedua berisi harga tiket maskapai yang di input oleh user
		-- Balikan dari Fungsi ini adalah Total Biaya penerbangan
	**/
	function totalHarga($totalBiayaSewa ){
		return $totalBiayaSewa ;
	}
?>
	

<!DOCTYPE html>
<html>
<head>
	<title>Rental Mobil</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<img src="img/fotopng.png">
	<h1>Rental Mobil</h1>

	<!-- Form Pendaftaran Rute Penerbangan -->
	<form action="index.php" method="post">
		<table width="100%">
			<tr>
				<td width="20%"><label>Nama</label></td>
				<td>:</td>
				<td width="80%"><input type="text" name="nama" class="inputtext" placeholder="Nama" required=""></td> <!-- Input nama Maskapai -->
			</tr>
			<tr>
				<td><label>Jenis Mobil</label></td>
				<td>:</td>
				<td>
					<select name="jenismobil"> 																						<!-- jenis mobil -->
						<?php
							foreach ($jenisMobil as $jm) {
								echo "<option value='".$jm."'>".$jm."</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label>Tanggal Sewa</label></td>
				<td>:</td>
				<td><input type="date" name="tanggalsewa" class="inputjadwal"></td>
			</tr>
				
				<tr>
				<td><label>Sopir</label></td> 											
				<td>:</td>
				<td>
					<select name="optionsopir">
						<!-- Perulangan untuk menampilkan Sopir -->
						<?php
							foreach ($Sopir as $s) {
								echo "<option value='".$s."'>".$s."</option>";
							}
						?>
					</select>
				</td>
			</tr>


			<tr>
				<td colspan="3" style="text-align: center;"><input type="submit" value="Submit" name="submit"></td>					<!-- Submit Form -->
			</tr>
		</table>		
	</form>

	<!-- Menampung seluruh hasil inputan User -->
	<?php
		if(isset($_POST['submit'])){
			$namaPelanggan = $_POST['nama'];
			$pilihanMobil = $_POST['jenismobil'];
			$tanggalSewaMobil = $_POST['tanggalsewa'];
			$pemakaianSopir = $_POST['optionsopir'];
			$totalHargaSewa = totalBiayaSewa($pilihanMobil, $pemakaianSopir);
			$totalHargaRentalMobil = totalHarga($totalHargaSewa);

			
			$rentalMobil = [$namaPelanggan, $pilihanMobil, $tanggalSewaMobil, $pemakaianSopir, $totalHargaSewa, $totalHargaRentalMobil];		//Menampung inputan User kedalam Array sementara
			array_push($rutePenerbanganAll, $rentalMobil);																												//Memasukan Array baru kedalam Array Daftar Rute Penerbangan
			array_multisort($rutePenerbanganAll, SORT_ASC);																													//Mengurutkan Daftar Maskapai sesuai Abjad dari yang terkecil
			$dataJson = json_encode($rutePenerbanganAll, JSON_PRETTY_PRINT);
			file_put_contents($berkas, $dataJson);
		}

	?>

	<!-- Menampilkan Daftar Maskapai Beserta Rute Penerbangannya -->
	<h1>Daftar Rental Mobil</h1>
	<table border="1px" width="100%" id="tabletampil">
		<thead>
			<tr>
				<th>Nama</th>
				<th>Jenis Mobil</th>
				<th>Tanggal Sewa</th>
				<th>Harga Sewa Mobil</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<!-- Perulangan untuk menampilkan isi Array Daftar Maskapai beserta Rute Penerbangan -->
			<?php
				for($i=0; $i<count($rutePenerbanganAll); $i++){
					echo "<tr>";
					echo "<td>".$rutePenerbanganAll[$i][0]."</td>";
					echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][1]."</td>";
					echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][2]."</td>";
					echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][4]."</td>";
					echo "<td style='text-align: center;'>".$rutePenerbanganAll[$i][5]."</td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
	

</body>
</html>