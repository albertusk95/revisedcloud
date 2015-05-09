<!doctype html>
<html>
<head>
	<title>Form Add User</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/cloud.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
	
	<div class="jumbotron">
	<h2>User Management - Add user</h2>
		<?php echo form_open("cloud/cek_adduser"); ?>
			<table class="table table-hover">
				<thead>
					<tr>
						<td>Username</td>
						<td>Password</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="newuser" placeholder="username" value="<?php echo set_value('newuser'); ?>">
						<br>
						<?php echo form_error('newuser','<font color="red">','</font>'); ?>
						</td>
						<td><input type="text" name="passnewuser" placeholder="password" value="<?php echo set_value('passnewuser'); ?>">
						<br>
						<?php echo form_error('passnewuser','<font color="red">','</font>'); ?>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td><input type="submit" value="SUBMIT"></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
	
</body>
</html>

