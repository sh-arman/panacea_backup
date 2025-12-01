<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        width: 40%;
    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        background-color: blue;
    }
    .card a:hover {
        color: white;
        
        text-decoration: none;
    }
</style>
<?php $__env->startSection('content'); ?>
    <div id="content">
        <div class="container">
            <h3> <b class="text-primary"> Available codes : &nbsp;<?php echo e(number_format($codes)); ?> </b></h3>
            <div class="code-log-header">
                <h1>Choose Company</h1>
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">
                        <p><?php echo e($success); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <?php $__currentLoopData = $company; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $individual_co): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card">
                        <a href="<?php echo e(url('/choose/'.$individual_co->display_name)); ?>">
                        <div class="card-block" style="padding: 2px 16px;">
                            <h4 class="card-title"><?php echo e(ucfirst($individual_co->display_name)); ?></h4>
                        </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.generationpanel_master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>