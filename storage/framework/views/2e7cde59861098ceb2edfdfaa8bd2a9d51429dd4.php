<?php $__env->startSection('content'); ?>
<div id="content">
	<p class="logout"><a href="<?php echo e(url('/logout')); ?>">LOGOUT</a></p>
	<div class="container">
	<h1>Templates</h1>
		<?php if(session('templateError')): ?>
			<div class="alert alert-danger" style="width:85%;">
				<?php echo e(session('templateError')); ?>

				<br/>
				<br/>
				<button type="button" class="btn btn-success" onclick="window.location.href='\confirmAddTemplate'">Confirm</button>
				<button type="button" class="btn btn-danger" onclick="window.location.href=window.location.href">Cancel</button>
			</div>
		<?php endif; ?>
		<?php if(session('templateSuccess')): ?>
			<div class="alert alert-success" style="width:85%;">
				<?php echo e(session('templateSuccess')); ?>

			</div>
		<?php endif; ?>
		<input type="hidden" name="pagename" value="template_page">
		<form action="<?php echo e(url('/addtemplate')); ?>" method="POST">
			<input type="text" name="prefix" placeholder="prefix"> PBN/REN MCKRTWS <input type="text" name="suffix" placeholder="suffix">
			<br><br>

			<div id="medicine_id" class="btn-group" data-toggle="buttons">
				<?php $__currentLoopData = $medicine_names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<label class="btn btn-primary">
					<input type="radio" value="<?php echo e($medicine->medicine_name); ?>" name="medicine_name" required=""> <?php echo e($medicine->medicine_name); ?>

				</label>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>

			<div id="medicine_type_id" class="btn-group" data-toggle="buttons">
			</div>

			<div id="medicine_dosage_id" class="btn-group" data-toggle="buttons">
			</div>

			<input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
			<input type="hidden" name="company_admin_id" value="<?php echo e($company_admin_id); ?>">
			<br>
			<button type="submit" class="btn btn-default btn-submit">Set</button>
			<?php echo csrf_field(); ?>

		</form>


		<div class="code-log-header">
			
			<?php if(!empty($success)): ?>
			<div class="alert alert-success">
				<p><?php echo e($success); ?></p>
			</div>
			<?php endif; ?>
			<?php if(count($template_log) > 0): ?>
		</div>
		<div class="table-responsive" id="box">
			<table class="table">
				<thead>
					<tr>
						<th>Templates</th>
						<th>Medicine</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="inner_table">
					<input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
					<input type="hidden" name="pagename" value="template_page">
					<?php $__currentLoopData = $template_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td> <?php echo e($template_data->template_message); ?> </td>
						<td> <?php echo e($template_data->medicine_name ." ". $template_data->medicine_type ." " . $template_data->medicine_dosage); ?> </td>
						<td> <a href = 'deleteTemplate/<?php echo e($template_data->id); ?>'> Remove Template </a>  </td>

					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<img id="loader" style="display: none" src='https://opengraphicdesign.com/wp-content/uploads/2009/01/loader64.gif'>
				</tbody>
			</table>
			<?php else: ?>
			<br>
			<div class="alert alert-danger">
				<p>No data available at the moment!</p>
			</div>
			<?php endif; ?>
		</div>



	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.generationpanel_master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>