<div class="container-fluid">
	<ol class="breadcrumb">
		<li>
			<a href="<?= site_url('cloud/folder') ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-home"></span></a>
		</li>
		<?php $i=0;$len = count($breadcrumbs);foreach($breadcrumbs as $breadcrumb) {
		if ($i == $len - 2) { ?>
		<li class="breadcrumb-middle">
			<a class="hidden-xs" href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
			<a class="visible-xs-inline" href="<?= $breadcrumb['url'] ?>">...</a>
		</li>
		<?php } else if($i == $len - 1) { ?>
		<li class="breadcrumb-middle">
			<a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
		</li>
		<?php } else { ?>
		<li class="breadcrumb-middle hidden-xs">
			<a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
		</li>
		<?php } $i++;} ?>
	</ol>
</div>
<div class="col-sm-6 col-sm-offset-3">
	<div class="file-icon">
		<span class="glyphicon glyphicon-cloud-upload"></span>
	</div>
</div>
<div class="col-sm-6 col-sm-offset-3">
<div class="panel panel-default">
  <div class="panel-body">
		<?php echo form_open_multipart($this->uri->uri_string());?>
		<div class="form-group">
			<label for="filename">File Name</label>
			<input type="text" class="form-control" id="filename" name="filename" placeholder="File.ext" />
		</div>
			<div class="btn btn-default btn-primary btn-file">
				Browse <input type="file" name="userfile" id="file" />
			</div>
		<button type="submit" id="upload-btn" class="btn btn-success btn-bg" disabled="disabled">Upload</button>
		</form>
	</div>
</div>
</div>
<script type="text/javascript">
document.forms[0].addEventListener('submit', function( evt ) {
    var file = document.getElementById('file').files[0];

    if(file && file.size < <?= $max_size * 1024 ?>) { // 10 MB (this size is in bytes)
    } else {
        $('#alert').append('<div class="alert alert-danger alert-fixed-top alert-dismissable" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span><b>Error</b></span><span> File Too Big</span></div>');
        evt.preventDefault();
    }
}, false);
document.getElementById('file').addEventListener('change', function( evt ) {
	var file = document.getElementById('file').files[0];
	$('#filename').val(file.name);
	$('#upload-btn').prop('disabled', false);
}, false);
</script>