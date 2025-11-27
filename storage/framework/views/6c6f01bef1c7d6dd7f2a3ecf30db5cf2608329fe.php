<?php $__env->startSection('content'); ?>
    <div id="content">
        <p class="logout"><a href="<?php echo e(url('/logout')); ?>">LOGOUT</a></p>
        <div class="container">
            <div class="code-log-header">
                <h1>Activity Log</h1>
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">
                        <p><?php echo e($success); ?></p>
                    </div>
                <?php endif; ?>
                <?php if(count($log) > 0): ?>
            </div>

            <div class="row">
                <form class="search-log"> <!-- the class name is mandatory -->
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search by User Name" name="activityUserName">
                    </div>
                    <div class="col-md-3">
                        <select class="selectpicker" id="activityLogSelect" multiple selected="selected">
                            <?php $__currentLoopData = $userNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option><?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-default" id="activityReset">Reset</button>
                </form>
            </div>

            <br/>

            <div class="table-responsive" id="activityBox">
                <table class="table">
                    <thead>
                    <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
                    <input type="hidden" name="pagename" value="log_page">
                    <tr>
                        <th>Name</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                    </thead>
                    <tbody class="activity_inner_table">
                    <?php $__currentLoopData = $log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td> <?php echo e($log_data->name); ?> </td>
                            <td> <?php if($log_data->action == 1): ?> Login to system
                                <?php elseif($log_data->action == 2): ?> Generated Code
                                <?php elseif($log_data->action == 4): ?> Timed out
                                <?php else: ?> Logged out
                                <?php endif; ?></td>
                            <td> <?php echo e($log_data->log_date); ?> </td>
                            <td> <?php echo e($log_data->log_time); ?> </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <img id="loader" style="display: none" src='https://opengraphicdesign.com/wp-content/uploads/2009/01/loader64.gif'>
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