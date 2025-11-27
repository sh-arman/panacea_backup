<?php $__env->startSection('content'); ?>
    <div id="content">
        <p class="logout"><a href="<?php echo e(url('/logout')); ?>">LOGOUT</a></p>
        <div class="container">
            <div class="code-log-header">
                <h1>Print Order Log</h1>
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">
                        <p><?php echo e($success); ?></p>
                    </div>
                <?php endif; ?>
                <?php if(count($order) > 0): ?>

                    <div class="row">
                        <form class="search-log"> <!-- the class name is mandatory -->
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Search by Batch Number" name="batch">
                            </div>
                            <div class="col-md-3">
                                <select class="selectpicker" id="printOrderSelect" multiple selected="selected">
                                    <?php $__currentLoopData = $medicine; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option><?php echo e($med->medicine_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="button" class="input-sm form-control" name="daterange" value="Date"/>
                            </div>
                            <button type="button" id="reset_button" class="btn btn-default">Reset</button>
                        </form>
                    </div>
            </div>

            <div class="table-responsive" id="box">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>File Name</th>
                        <th>Download CSV</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody class="inner_table">
                    <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
                    <?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($ord->medicine->medicine_name ." ". $ord->medicine->medicine_type ." " . $ord->medicine->medicine_dosage); ?></td>
                            <td><?php echo e($ord->batch_number); ?></td>
                            <td><?php echo e($ord->quantity); ?></td>
                            <td><?php echo e(ucfirst($ord->status)); ?></td>
                            <td><?php echo e($ord->file); ?></td>
                            <td><a href="codes/<?php echo e($ord->file); ?>"
                                   download="<?php echo e(strpos($ord->file, '_')!= false?explode('_', $ord->file, 2)[1]:$ord->file); ?>">Download</a>
                            </td>
                            <td><?php echo e($ord->created_at); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <p>No data available at the moment!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.generationpanel_master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>