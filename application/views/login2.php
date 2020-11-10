<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>HR Management Application</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Aplikasi Management Karyawan Koperasi Baytul Ikhtiar" />
	<meta name="author" content="Koperasi Baytul Ikhtiar IT Team" />
	<meta name="theme-color" content="#00a90a"/>
	<style rel="stylesheet" type="text/css">
		@import url('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all');
	</style>
	<link rel="manifest" href="<?=base_url('manifest.json');?>">
	<link href="<?=base_url();?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
	<link href="<?=base_url();?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url();?>assets/pages/css/login-4.css" rel="stylesheet" type="text/css" />
	<link href='<?php echo base_url();?>assets/pages/img/logo_sm.png' rel='shortcut icon'>
</head>
<body class="login">
	<div class="logo">
		<a href="#">
			<img src="<?=base_url();?>assets/pages/img/logo_sm.png" alt="Logo BAIK" />
		</a>
	</div>
	<div class="content">
		<form class="login-form" action="<?=site_url('authentication');?>" method="post">
			<h4 class="form-title text-center">HR Management Application</h4>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				<span> Enter any username and password. </span>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<div class="input-icon">
					<i class="fa fa-user"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" autofocus />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="input-icon">
					<i class="fa fa-lock"></i>
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" />
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn green pull-right"> <i class="fa fa-sign-in"></i> Login </button>
			</div>
		</form>
	</div>
	<div class="copyright"> 
		2019 &copy; KSPPS Baytul Ikhtiar - HR Management Application V.2.0.0 
	</div>
	<script src="<?=base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script defer src="<?=base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/plugins/backstretch/jquery.backstretch.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
	<script async src="<?=base_url();?>assets/pages/scripts/login-4.js" type="text/javascript"></script>
</body>
</html>