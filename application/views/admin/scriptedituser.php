<?php  
	$connect = mysqli_connect("localhost","root","");
	mysqli_select_db($connect, "dbcloud") or die("Database tidak ditemukan");
		
		if (mysqli_connect_error())
		{
			echo "Koneksi gagal!: " . mysqli_connect_error();
		}
	$id = $_POST['idbaru']; 
	$username = $_POST['usernamebaru'];  
	$password = $_POST['passwordbaru'];   
    $password = sha1($password);
	
	if ($username == 'admin')
	{
		if ($password == 'adminadmin')
		{
			$is_admin = 1;
		}
		else 
		{
			$is_admin = 0;
		}
	}
	else 
	{
		$is_admin = 0;
	}
   
	$connect = mysqli_connect("localhost","root","");
	mysqli_select_db($connect, "dbcloud") or die("Database tidak ditemukan");
	$query = "UPDATE users set username='$username', password='$password', is_admin='$is_admin' where id='$id'";  
	$hasil = mysqli_query($connect,$query);  
	
		if($hasil)
		{  
			echo "Edit user berhasil!";  
			/*
			$query = "SELECT * FROM users ORDER BY id";
			$hasil = mysqli_query($connect,$query);
			$nomor = 1;
			while ($indeks_nomor = mysqli_fetch_array($hasil))
			{
				$id = $indeks_nomor['id'];
				$query2 = "UPDATE users SET id=$nomor WHERE id=$id";
				mysqli_query($connect,$query2);
				$nomor = $nomor + 1;
			}
			$query = "ALTER TABLE users AUTO_INCREMENT = $nomor";
			mysqli_query($connect,$query);
			*/
		}
		else
		{  
			echo "Error! Edit user gagal!".mysqli_error();  
		}  
?>  