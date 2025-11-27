<?php $__env->startSection('content'); ?>
    <!-- <div class="add-container">
        <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
            -->
    <div id="content">
        <p class="logout"><a href="<?php echo e(url('/logout')); ?>">LOGOUT</a></p>
        <div class="container">
            <h4 class="alert alert-warning"><b>Please check if all information are correct. If not, you can go back and change it</b></h4>
            <form action="<?php echo e(url('/code/orderBack')); ?>" method="post">
                <input type="hidden" name="company_id" value="<?php echo e($confirm['company_id']); ?>">
                <input type="hidden" name="mfg_date" value="<?php echo e($confirm['mfg_date']); ?>">
                <input type="hidden" name="expiry_date" value="<?php echo e($confirm['expiry_date']); ?>">
                <input type="hidden" name="quantity" value="<?php echo e($confirm['quantity']); ?>">
                <input type="hidden" name="file" value="<?php echo e($confirm['file']); ?>">
                <input type="hidden" name="batch_number" value="<?php echo e($confirm['batch_number']); ?>">
                <input type="hidden" name="medicine_dosage_id" value="<?php echo e($confirm['medicine_id']); ?>">
                <input type="hidden" name="medicine_name" value="<?php echo e($medicine['medicine_name']); ?>">
                <input type="hidden" name="medicine_type" value="<?php echo e($medicine['medicine_type']); ?>">
                <input type="hidden" name="medicine_dosage" value="<?php echo e($confirm['medicine_dosage']); ?>">
                <input type="hidden" name="template_message" value="<?php echo e($template['template_message']); ?>">

                <h3>
                    <button class="btn btn-default">&#8249; Go back</button>
                </h3>
                <?php echo csrf_field(); ?>

            </form>
            <h1><?php echo e($medicine['medicine_name']); ?> <?php echo e($medicine['medicine_type']); ?> <?php echo e($medicine['medicine_dosage']); ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="info">
                        <span>Manufacturing Date</span>
                        <p><?php echo e($confirm['mfg_date']); ?></p>
                    </div>
                    <div class="info">
                        <span>Quantity</span>
                        <p><?php echo e($confirm['quantity']); ?></p>
                    </div>
                    <div class="info">
                        <span>Datapack Name</span>
                        <p><?php echo e($confirm['file']); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info">
                        <span>Expiry Date</span>
                        <p><?php echo e($confirm['expiry_date']); ?></p>
                    </div>
                    <div class="info">
                        <span>Production Batch Number</span>
                        <p><?php echo e($confirm['batch_number']); ?></p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="info">
                        <span>Your message will look like:</span>

                        <?php if($confirm['pref'] == "REN"): ?>
                            <p>REN MCKRTWS</p>
                        <?php endif; ?>

                        

                    </div>
                </div>
            </div>
            <br>

            <form action="<?php echo e(url('/code/confirm')); ?>" method="post">
                <input type="hidden" name="company_id" value="<?php echo e($confirm['company_id']); ?>">
                <input type="hidden" name="mfg_date" value="<?php echo e($confirm['mfg_date']); ?>">
                <input type="hidden" name="expiry_date" value="<?php echo e($confirm['expiry_date']); ?>">
                <input type="hidden" name="quantity" value="<?php echo e($confirm['quantity']); ?>">
                <input type="hidden" name="file" value="<?php echo e($confirm['file']); ?>">
                <input type="hidden" name="batch_number" value="<?php echo e($confirm['batch_number']); ?>">
                <input type="hidden" name="medicine_dosage_id" value="<?php echo e($confirm['medicine_id']); ?>">
                <input type="hidden" name="prefix" value="<?php echo e($confirm['pref']); ?>">
                <meta name="csrf-token_confirm" content="<?php echo e(csrf_token()); ?>">
                <?php echo csrf_field(); ?>


                <button id="generate_button" type="submit" class="btn btn-primary">Start Generating</button>
            </form>
        </div>
    </div>
    <!--  </div> -->
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.generationpanel_master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>