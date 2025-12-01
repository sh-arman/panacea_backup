<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Panacea | Machine Room</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#00baff">
	<link rel="shortcut icon" type='image/x-icon' href="<?php echo e(asset('images/favicon.ico')); ?>">

	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<link rel="stylesheet" href="<?php echo e(asset('panel/css/style2.css')); ?>">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

<!-- Sidebar -->

<?php if($page!='menu_page'): ?>
<div id="sidebar">
	<div class="sidebar-items">
		<?php if(session()->has('CompanyName')): ?>
			<a href="<?php echo e(url('/choosemenu')); ?>">
				<?php if($page==''): ?>
					<div class="sidebar-item text-center active">
						<?php else: ?>
							<div class="sidebar-item text-center">
								<?php endif; ?>
								<h5>Menu</h5>
							</div>
			</a>
		<?php endif; ?>
		<a href="<?php echo e(url('/code/generate')); ?>">
			<?php if($page=='order_page'): ?>
				<div class="sidebar-item text-center active">
					<?php else: ?>
						<div class="sidebar-item text-center">
							<?php endif; ?>
				<h5>Order Codes</h5>
			</div>
		</a>
		<a href="<?php echo e(url('/order')); ?>">
			<?php if($page=='print_log_page'): ?>
				<div class="sidebar-item text-center active">
					<?php else: ?>
						<div class="sidebar-item text-center">
							<?php endif; ?>
							<h5>Print order log</h5>
			</div>
		</a>
		<a href="<?php echo e(url('/log')); ?>">
			<?php if($page=='log_page'): ?>
				<div class="sidebar-item text-center active">
					<?php else: ?>
						<div class="sidebar-item text-center">
							<?php endif; ?>
							<h5>Activity log</h5>
			</div>
		</a>
		<a href="<?php echo e(url('/templates')); ?>">
			<?php if($page=='template_page'): ?>
				<div class="sidebar-item text-center active">
					<?php else: ?>
						<div class="sidebar-item text-center">
							<?php endif; ?>
							<h5>Templates</h5>
			</div>
		</a>
	</div>
</div>
<?php endif; ?>
<!-- ./Sidebar -->

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

<div id="wrapper">
	<div class="wrapper-inside">
		<?php echo $__env->yieldContent('content'); ?>
	</div>
</div>

<script>
	$('.man-date').datepicker({
		autoclose: true
	});
	$('.exp-date').datepicker({
		autoclose: true
	});
</script>
<script type="text/javascript" src="<?php echo e(asset('js/generationpanel.js?v1.2')); ?>"></script>
<!-- ./Scripts -->
</body>
</html>
