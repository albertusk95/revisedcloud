<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/cloud.css">
	<title>SimpleCloud</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
	<div class="container-fluid">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<?= anchor('cloud', '<span class="glyphicon glyphicon-cloud"></span> <b>Simple</b>Cloud', array('class'=>'navbar-brand')) ?>
	</div>
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav navbar-right">
			<p class="navbar-text"><?= $this->session->userdata('username') ?></p>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Options <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Profile</a></li>
					<li><?= anchor('auth/logout', 'Log Out') ?></li>
				</ul>
			</li>
		</ul>
	</div>
	</div>
</nav>
<div id="alert">
	<?php if(isset($error)&&$error!==''): ?>
	<div class="alert alert-danger alert-fixed-top alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span><b>Error</b></span><span> <?= $error?></span>
	</div>
	<?php endif ?>
	<?php if(isset($_GET['error'])): ?>
	<div class="alert alert-danger alert-fixed-top alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span><b>Error</b></span><span> <?= $this->input->get('error') ?></span>
	</div>
	<?php endif ?>
	<?php if($this->input->get('upload_success')): ?>
	<div class="alert alert-success alert-fixed-top alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span><b>Success</b></span><span> File <?= $this->input->get('upload_name') ?> uploaded</span>
	</div>
	<?php endif ?>
	<?php if($this->input->get('create_success')): ?>
	<div class="alert alert-success alert-fixed-top alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span><b>Success</b></span><span> <?= $this->input->get('create_name') ?> created</span>
	</div>
	<?php endif ?>
	<?php if($this->input->get('delete_success')): ?>
	<div class="alert alert-success alert-fixed-top alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span><b>Success</b></span><span> <?= $this->input->get('delete_name') ?> deleted</span>
	</div>
	<?php endif ?>
</div>