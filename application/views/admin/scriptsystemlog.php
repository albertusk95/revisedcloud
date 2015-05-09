<!doctype html>
<html>
<head>
	<title>Form System Log</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/cloud.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>

<?php 
	$connect = mysqli_connect("localhost","root","");
	mysqli_select_db($connect, "dbcloud") or die("Database tidak ditemukan");
		if (mysqli_connect_error())
			{
				echo "Koneksi gagal!: " . mysqli_connect_error();
			}
	$sql = mysqli_query ($connect,"SELECT * FROM track_record");
	
	$i = 0;
	
	while ($baris = mysqli_fetch_array($sql)) 
	{
		if ($Username == $baris['username'])
		{
			$i = $i + 1;
			$id[$i] = $baris['id'];
			$username[$i] = $baris['username'];
			$filefolder[$i] = $baris['file_folder'];
			$tanggal[$i] = $baris['tanggal'];
			$bulan[$i] = $baris['bulan'];
			$tahun[$i] = $baris['tahun'];
			$jam[$i] = $baris['jam'];
			$menit[$i] = $baris['menit'];
			$detik[$i] = $baris['detik'];
			$tipedata[$i] = $baris['tipe_data'];
		}
	}
	
	/*
	//konversi waktu ke detik 
	for ($a=1; $a<=$i; $a++)
	{
		$times_jam[$a] = $jam[$a] * 3600;
		$times_menit[$a] = $menit[$a] * 60;
		$times_detik[$a] = $detik[$a];
		$times_waktu[$a] = $times_jam[$a] + $times_menit[$a] + $times_detik[$a];
	}
	
	//mengurutkan tanggal dari yg paling lama sampai paling baru
	//acuan tahun adalah tahun 0
	for ($a=1; $a<=$i; $a++)
	{
		if ($tahun[$a] % 4 ==0) { $substract = 1; }
		else { $substract = 2; } 
		$times_year = $tahun[$a] * 365; //konversi ke hari
		
		$times_month = ($bulan[$a]-1) * 30; 
		if ($bulan[$a] == 1) { $times_month = $times_month; }
		else if ($bulan[$a] == 2) { $times_month = $times_month + 1; }
		else if ($bulan[$a] == 3) { $times_month = $times_month + 1 - $substract; }
		else if ($bulan[$a] == 4) { $times_month = $times_month + 2 - $substract; }
		else if ($bulan[$a] == 5) { $times_month = $times_month + 2 - $substract; }
		else if ($bulan[$a] == 6) { $times_month = $times_month + 3 - $substract; }
		else if ($bulan[$a] == 7) { $times_month = $times_month + 3 - $substract; }
		else if ($bulan[$a] == 8) { $times_month = $times_month + 4 - $substract; }
		else if ($bulan[$a] == 9) { $times_month = $times_month + 5 - $substract; }
		else if ($bulan[$a] == 10) { $times_month = $times_month + 5 - $substract; }
		else if ($bulan[$a] == 11) { $times_month = $times_month + 6 - $substract; }
		else if ($bulan[$a] == 12) { $times_month = $times_month + 6 - $substract; }
		
		$times_date = $tanggal[$a];
		
		$times_tanggal[$a] = $times_date + $times_month + $times_year;
	}
	
	for($a=1; $a<=$i; $a++)
	{
		$found = 1;
		for ($b=1; $b<=$i; $b++)
		{
			if ($status[$b] == 1 && $found == 1) 
			{
				$date_min[$a] = $times_tanggal[b];
				$found = 0;
			}
		}
		
		for ($b=1; $b<=$i; $b++)
		{
			if ($status[$b] == 1)
			{
				if ($times_tanggal[$b] <= $date_min[$a])
				{
					$date_min[$a] = $times_tanggal[$b];
				}
				else 
				{
					$date_min[$a] = $date_min[$a];
				}
			}
		}
		
		$found = 1;
		for ($b=1; $b<=$i; $b++)
		{
			if ($times_tanggal[$b] == $date_min[$a] && $found == 1)
			{
				$status[$b] = 0;
				$found = 0;
				$index_datemin[$a] = $b;
			}
		}
		
	}
	
	for ($a=1; $a<=$i; $a++)
	{
		echo $tanggal[$index_datemin[$a]]." ".$bulan[$index_datemin[$a]]." ".$tahun[$index_datemin[$a]]."<br>";
	}
	*/
	mysqli_close($connect);							
?>

<div class="jumbotron">
	<h2><b>System log ini menampilkan aksi user dari waktu paling lama sampai paling baru.</b></h2><br>
	<table border=0>
	<tr>
		<td>
		<b>ID User  :</b>
		</td>
		<td>
		<?php echo $id[1]; ?>
		</td>
	</tr>
	<tr>
		<td>
		<b>Username :</b>
		</td>
		<td>
		<?php echo $username[1]; ?>
		</td>
	</tr>
	</table>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<td>Aksi user</td>
				<td>Nama file/folder</td>
				<td>Waktu aksi</td>
				<td>Tanggal aksi</td>
			</tr>
		</thead>
		<tbody>
			<?php 
				for ($a=1; $a<=$i; $a++)
				{
			?>
			
			<tr>	
				<td>
					<?php 
						if ($tipedata[$a] == 'new_folder')
						{
							echo "Membuat folder baru";
						}
						else if ($tipedata[$a] == 'upload_file')
						{
							echo "Upload file baru";
						}
						else if ($tipedata[$a] == 'delete_file')
						{
							echo "Menghapus file";
						}
						else 
						{
							echo "Menghapus folder";
						}
					?>
				</td>
				<td>
					<?php 
						echo $filefolder[$a];
					?>
				</td>
				<td>
					<?php 
						echo $jam[$a].":".$menit[$a].":".$detik[$a];
					?>
				</td>
				<td>
					<?php 
						echo $tanggal[$a]."/".$bulan[$a]."/".$tahun[$a];
					?>
				</td>
			</tr>
			
			<?php 
				}
			?>
		</tbody>
	</table>
</div>

</body>
</html>

