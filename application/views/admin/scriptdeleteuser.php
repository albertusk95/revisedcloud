<?php 
function removedir($dirname)
    {
        if (is_dir($dirname))
        $dir_handle = opendir($dirname);
        if (!$dir_handle)
        return false;
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
                else
                {
                    $a=$dirname.'/'.$file;
                    removedir($a);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
	
	removedir($path_1);
	
	//script delete di database
	$id_value = $path_id;
	
	$connect = mysqli_connect("localhost","root","");
	mysqli_select_db($connect, "dbcloud") or die("Database tidak ditemukan");
		
		if (mysqli_connect_error())
		{
			echo "Koneksi gagal!: " . mysqli_connect_error();
		}
		$id = $id_value;
		$delete = mysqli_query($connect, "DELETE FROM users where id=$id");
		
		if ($delete) {
			echo "<script>alert('Delete user berhasil!');history.go(-1);</script>";
			
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
		else {
			echo "<script>alert('Delete user gagal!');history.go(-1);</script>";
		}
	
?>
