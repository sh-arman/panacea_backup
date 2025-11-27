<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('livecheckpro/livecheckpro.css?v1.0')); ?>">
    <title>Maxpro Mups | QR Verificaton</title>
</head>

<body>
    <div class="hero">
        <img class="hero-img" src="<?php echo e(asset('livecheckpro/asset/circular_background.svg')); ?>" alt="Background_image">
        <div class="hero-items">
            <?php $__env->startSection('img'); ?>
            <?php echo $__env->yieldSection(); ?>
        </div>
    </div>
    <div class="content">
        <div class="container">
          <?php $__env->startSection('content-box'); ?>
          <?php echo $__env->yieldSection(); ?>
        </div>
        

        <footer class="footer">
            <div class="container">
                <div class="row" id="mupsfooter">
                    <div class="col text-center">
                        
                        <a href="https://www.facebook.com/maxpropage" class="item mx-2" target="_blank">
                            <img class=" icon" src="<?php echo e(asset('livecheckpro/asset/facebook.svg')); ?>">
                            <p style="font-size:12px !important;"><?php echo e(trans('literature.footer-fb')); ?><br><?php echo e(trans('literature.footer-page')); ?></p>
                        </a>
                        <a href="<?php echo e(route('leaflet')); ?>" class="item mx-2" target="_blank" rel="noopener noreferrer">
                            <img class=" icon" src="<?php echo e(asset('livecheckpro/asset/leaflet.svg')); ?>">
                            <p style="font-size:12px !important;"><?php echo e(trans('literature.footer-medicine')); ?><br><?php echo e(trans('literature.footer-leaflet')); ?></p>
                        </a>
                        
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <?php echo $__env->yieldContent('script'); ?>
</body>
</html>