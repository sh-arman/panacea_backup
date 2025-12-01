<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Panacea Live | The Future Is Original</title>
    <?php $__env->startSection('styles'); ?>
        <?php if($body_id == 'report'): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/tympanus-report/normalize.css')); ?>" />
            <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/tympanus-report/demo.css')); ?>" />
            <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/tympanus-report/component.css')); ?>" />
            <script src="<?php echo e(asset('frontend/js/tympanus-report/modernizr.custom.js')); ?>"></script>
            <style type="text/css">.navbar-fixed-top{background-color:transparent;}</style>
        <?php endif; ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/style.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/login-signup.css?v1.1')); ?>">
    <?php echo $__env->yieldSection(); ?>

    <?php if($body_id == 'home_verify'): ?>
        <style type="text/css">.navbar-fixed-top{background-color:transparent;}</style>
    <?php endif; ?>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/tympanus-medicine/component.css')); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/tympanus-medicine/demo.css')); ?>" />
    <script type="text/javascript" src="<?php echo e(asset('frontend/js/tympanus-medicine/modernizr.custom.js')); ?>"></script>

            <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                    document,'script','https://connect.facebook.net/en_US/fbevents.js');

            fbq('init', '1086797394746371');
            fbq('track', "PageView");</script>
        <noscript><img alt="fbpixel" height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=1086797394746371&ev=PageView&noscript=1"
                    /></noscript>
        <!-- End Facebook Pixel Code -->
</head>
<body <?php if(!empty($body_id)): ?> id="<?php echo e($body_id); ?>" <?php endif; ?> >
<?php echo $__env->make('partials._menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('partials._footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('frontend/js/bootstrap.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('frontend/js/main.js?v1.1')); ?>"></script>

<?php echo $__env->yieldSection(); ?>
</body>
</html>
