<!doctype html>
<html>
<head>
	<title>Form delete user</title>
	<meta charset="utf-8">

	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/cloud.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

</head>
<body>
	<?php echo form_open('cloud/deleteuser/'.$ID.'/'.$id_value); ?>
	
	<h1>Delete this user?</h1>
	<table class="table table-hover">
	<thead>
		<tr>
			<td>id</td>
			<td>username</td>
			<td>password</td>
			<td>level</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<?php echo $ID; ?>
			</td>
			<td>
				<?php echo $Username; ?>
			</td>
			<td>
				<?php echo $Password; ?>
			</td>
			<td>
				<?php echo $is_admin; ?>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input type="submit" value="Delete user" />
			</td>
		</tr>
	</tfoot>
	</table>
	

	</form>
	
</body>
</html>