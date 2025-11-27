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
                <h1>Generate Codes</h1>
                    <div class="choose-med">
                        <form action="<?php echo e(url('/code/generate')); ?>" method="post">
                        <input name="company_id" value="<?php echo e($company->id); ?>" type="hidden">

                            <div id="medicine_id" class="btn-group" data-toggle="buttons">
                                <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="btn btn-primary">
                                    <input type="radio" value="<?php echo e($medicine->medicine_name); ?>" name="medicine_name" required=""> <?php echo e($medicine->medicine_name); ?>

                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div id="medicine_type_id" class="btn-group" data-toggle="buttons">
                            </div>

                            <div id="medicine_dosage_id" class="btn-group" data-toggle="buttons">
                            </div>

                            <br><br>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" id="mfg_date" name="mfg_date" class="form-control  datepicker" placeholder="Manufacturing Date" required>
                            </div>
                            <div class="form-group">
                                <input type="text" id="expiry_date" name="expiry_date" class="form-control  datepicker" placeholder="Expiry Date" required>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="quantity" class="form-control" placeholder="Quantity" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="batch_number" class="form-control" placeholder="Production Batch Number" required>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="file" class="form-control" placeholder="Datapack Name" required>
                            </div>
                            <div class="form-group"  id="pregroup">
                                <!-- <input type="radio" name="prefix" value="2777" required> &nbsp;&nbsp; PBN to 2777 <br> -->
                                <!-- <input type="radio" name="prefix" value="6969" required> &nbsp;&nbsp; REN to 6969 <br> -->
                                <!-- <input type="radio" name="prefix" value="26969" required> &nbsp;&nbsp; REN to 26969 <br> -->
                                <input type="radio" name="prefix" value="REN" required> &nbsp;&nbsp; REN to 26969 <br>
                                <input type="radio" name="prefix" value="6spcae" required> &nbsp;&nbsp; REN to 26969 with 6 Space <br>
                            </div>
                            <!-- <div class="form-group" id="qr">
                                <input type="radio" name="prefix" value="qr" required> &nbsp;&nbsp; For QR Codes
                            </div> -->
                        </div>
                        <button type="submit" class="btn btn-default btn-submit">Order Codes For Printing (CSV)</button>


                        <?php echo csrf_field(); ?>

                    </form>
                    </div>

            </div>
        </div>
  <!--  </div> -->
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.generationpanel_master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>