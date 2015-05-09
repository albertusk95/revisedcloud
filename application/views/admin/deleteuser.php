<!doctype html>
<html>
<head>
	<title>Form Delete User</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/cloud.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
					<div class="jumbotron">
						<h2>User Management - Delete user</h2>
						
						<?php echo form_open("cloud/cek_deleteuser"); ?>
						
						Masukan nomor tabel yang ingin dihapus :
						<input type="text" name="notabeldelete">
						<input type="submit" name="nomortabeldelete" value="Delete user">
						
						<table class="table table-hover">
							<thead>
							<tr>
								<td>ID</td>
								<td>Username</td>
								<td>Password</td>
								<td>Level</td>
							</tr>
							</thead>
							
							<tbody>
							
								<?php
								$connect = mysqli_connect("localhost","root","");
								mysqli_select_db($connect, "dbcloud") or die("Database tidak ditemukan");
								if (mysqli_connect_error())
								{
								echo "Koneksi gagal!: " . mysqli_connect_error();
								}
								$sql = mysqli_query ($connect,"SELECT * FROM users");
								
								while ($baris = mysqli_fetch_array($sql)) {
									?>
								<tr>
									<td><?php echo $baris['id']; ?> </td>
									<td><?php echo $baris['username']; ?> </td>
									<td><?php echo $baris['password']; ?> </td>
									<td><?php echo $baris['is_admin']; ?> </td>
								</tr>
								<?php
								}
								mysqli_close($connect);
								?>
							</tbody>
						</table>
						
						</form>
					</div>

</body>
</html>