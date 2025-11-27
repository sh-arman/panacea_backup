<?php $__env->startSection('content-box'); ?>





<?php $__env->startSection('img'); ?>
    <div id="renataIcon" style="padding-bottom: 0rem; !important; margin-top: -1rem;">
        <img class="renata" src="<?php echo e(asset('livecheckpro/asset/renata.svg')); ?>">
    </div>  
    <div id="liveCheckIcon">
        <img class="live-check" src="<?php echo e(asset('livecheckpro/asset/live_check.svg')); ?>">
    </div>
    <div id="verfiedIcon" style="display: none">
        <img class="mark" src="<?php echo e(asset('livecheckpro/asset/tick.svg')); ?>">
    </div>
    <div id="incorrectIcon" style="display: none">
        <img class="mark" src="<?php echo e(asset('livecheckpro/asset/incorrect.svg')); ?>">
    </div>
<?php $__env->stopSection(); ?>


<div class="content-box">
    <input type="hidden" id="nola" value="<?php echo e($modal); ?>">
    <form method="POST" action="<?php echo e(route('mupslivecheck')); ?>">

        
        <div class="d-flex flex-row-reverse">
            <?php if(Session::has('locale')): ?>
                <?php if(Session::get('locale') == 'bn'): ?>
                    <a class="btnlng" id="btnlang" href="<?php echo e(route('locale.setting', 'en')); ?>" role="button">Engish</a>
                <?php elseif(Session::get('locale') == 'en'): ?>
                    <a class="btnlng" id="btnlang" style="font-family: 'Hind Siliguri', sans-serif;" href="<?php echo e(route('locale.setting', 'bn')); ?>" role="button">বাংলা</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="btnlng" id="btnlang" href="<?php echo e(route('locale.setting', 'bn')); ?>" role="button">বাংলা</a>
            <?php endif; ?>
        </div>

        
         <div class="row justify-content-center"  id="CodeDiv" >
            <?php echo e(csrf_field()); ?>

            <div class="error-msg" id="confirmationCodeInfo"></div>
            <p id="lebel" class='text-secondary mt-2'><small><?php echo e(trans('literature.lebel')); ?></small></p>
            <input value="REN " id="code" name="code"  autocomplete="on" required />
        </div>
        
        <div class="row justify-content-center" id="PhoneDiv" style="display:none;">
            <p id="lebel" class='text-secondary mt-2'><small><?php echo e(trans('literature.lebel-phone')); ?></small></p>
            <input type="number" name="phoneNo" id="phoneNo" autocomplete="on" required />
        </div>
        
        <div class="row justify-content-center">
            
            <button type="button" id="back" class="btnverify" style="display:none;" ><?php echo e(trans('literature.button-back')); ?></button>
            
            <button type="button" id="nextOne" class="btnverify" ><?php echo e(trans('literature.button-next')); ?></button>
            
            <button type="submit" id="nextTwo" class="btnverify" style="display:none;"><?php echo e(trans('literature.button-verify')); ?></button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    var nola = $('#nola').val();
    if(nola == 1) {
        console.log('modal response');
        $('#exampleModal').modal('show'); 
    } else {
        console.log('no modal response');
    }
    $("#nextOne").click(function() {
        $("#CodeDiv").slideUp();
        $("#PhoneDiv").slideUp();
        $("#PhoneDiv").removeAttr("style");
        $("#back").removeAttr("style");
        $("#nextOne").css("display","none");
        $("#nextTwo").removeAttr("style");
    });
    $("#back").click(function() {
        $("#PhoneDiv").slideUp();
        $("#CodeDiv").slideDown();
        $("#PhoneDiv").css("display","none");
        $("#back").css("display","none");
        $("#nextTwo").css("display","none");
        $("#nextOne").fadeIn();
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('livecheckproMups.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>