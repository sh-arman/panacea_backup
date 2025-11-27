<div class="full-height fh-hero">

<?php $__env->startSection('content'); ?>

		<div class="hero">
			<div class="container">
				<div class="row text-center">
					<div class="col-md-6 col-md-offset-3">
						<h2>Verify Your Product</h2>
						<form id="verify" action="<?php echo e(route('response')); ?>" method="post">
							<input type="text" name="code" class="form-control" maxlength="11" placeholder="Enter Your Code" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Your Code'">
							<?php echo csrf_field(); ?>

							<input type="submit" class="btn btn-default"  role="button" id="verifyButton" value="Live Check">
						</form>
					</div>
				</div>
			</div>
		</div>
</div>
<div class="full-height fh-what">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<h2>What Is Panacea?</h2>
				<p>
            		Panacea partners with brands that are committed to protecting their consumers from counterfeit products. We give each product a unique identity with a unique code which you can check with an SMS or on our website.
				</p>
			</div>
		</div>
	</div>
</div>
<h2 class="text-center" style="padding-top: 5%"><b>How to verify</b></h2>

<div id="how-to" class="full-height fh-how">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="col-md-12 thumbnail text-center">
					<img alt="Medicine back" style="width: 100%; height: auto" class="img-responsive" src="<?php echo e(asset('frontend/images/how-1.png')); ?>">
					<div class="caption red">
						<h3>Check the code on your product</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="col-md-12 thumbnail text-center">
					<img alt="Phone display" style="width: 100%; height: auto" class="img-responsive" src="<?php echo e(asset('frontend/images/how-2.png')); ?>">
					<div class="caption blue">
						<h3>Write the code in SMS and send it to 26969</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="col-md-12 thumbnail text-center">
					<img alt="Phone and medicine" style="width: 100%; height: auto" class="img-responsive" src="<?php echo e(asset('frontend/images/how-3.png')); ?>">
					<div class="caption orange">
						<h3>Receive a reply telling you if your product is verified</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div style="text-align: center;background-color: #ffffff;padding-bottom: 8%; font-size:25px;">
	<b>You may also send us code in other ways than SMS for free. Learn the<br> 
	other ways you can send us the code for verification 
	<a href="/platforms" target="_blank" style="color: #ff0000">here.</a></b>
</div>

<div id="brands" class="full-height fh-med">
	<div class="container text-center">
		<h1>Brands<br><small>These are the products that are being protected from being counterfeited with Panacea.</small></h1>
<!--		<div class="row text-center">
			<div class="medicine-list">
				<div class="col-md-4 col-md-offset-2" style="padding-right: 15px; border-right: 1px solid #ccc;">
					<span data-toggle="tooltip" title="Maxpro is manufactured by Renata Limited. The generic name of the medicine is Esomeprazole. The verification service is available for Maxpro 20 mg tablet."><img src="https://www.panacea.live/frontend/images/maxpro.png" class="img-responsive" alt="Maxpro"></span>
				</div>
				<div class="col-md-4">
					<span data-toggle="tooltip" title="Rolac is manufactured by Renata Limited. The generic name of the medicine is Ketorolac Tromethamine. The verification service is available for Rolac 10 mg tablet."><img src="https://www.panacea.live/frontend/images/rolac.png" class="img-responsive" alt="Maxpro"></span>
				</div>
			</div>
		</div>
!-->
		<div class="row justify-centent-center">
			<div class="col-md-6" style="border-right:solid 1px #ccc;">
				<span data-toggle="tooltip" title="Maxpro is manufactured by Renata Limited. The generic name of the medicine is Esomeprazole. The verification service is available for Maxpro 20 mg tablet."><img src="https://www.panacea.live/frontend/images/maxpro.png" style="max-width:400px;" alt="Maxpro"></span>
			</div>
			<div class="col-md-6" style="margin-top:10px;">
				<span data-toggle="tooltip" title="Rolac is manufactured by Renata Limited. The generic name of the medicine is Ketorolac Tromethamine. The verification service is available for Rolac 10 mg tablet."><img src="https://www.panacea.live/frontend/images/rolac.png" style="max-width:400px;" alt="Maxpro"></span>
			</div>
		</div>
		<div class="row justify-centent-center">
			<div class="col-md-12">
				<span data-toggle="tooltip" title="Essilor corrects, protects and prevents
					risks to the eye health of more than one billion people worldwide every day. Essilor is world famous for the Varilux lens, the world's first varifocal, which was invented in 1959.">
					<img src="<?php echo e(asset('frontend/images/essilor.png')); ?>" style="max-width:300px;" alt="Essilor"></span>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
	##parent-placeholder-16728d18790deb58b3b8c1df74f06e536b532695##
	<script type="text/javascript" src="<?php echo e(asset('frontend/js/tympanus-medicine/imagesloaded.pkgd.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('frontend/js/tympanus-medicine/masonry.pkgd.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('frontend/js/tympanus-medicine/classie.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('frontend/js/tympanus-medicine/cbpGridGallery.js')); ?>"></script>
	<script>
		new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
	</script>
	<script>
		$('span[data-toggle="tooltip"]').tooltip({
		            animated: 'fade',
			placement: 'bottom',
			html: true
		});
	</script>
	<script>
		document.getElementById("slideclick").onclick = function(e){
			document.getElementById('topnav').style.visibility = 'hidden';
		}
		document.getElementById("opennav").onclick = function(e){
			document.getElementById('topnav').style.visibility = 'visible';
		}
	</script>

	<script>
		$(document).ready(function(){
			$(window).scroll(function() {
				if ($(document).scrollTop() > 100) {
					$(".navbar-fixed-top").css("background-color", "#182848");
				} else {
					$(".navbar-fixed-top").css("background-color", "transparent");
				}
			});
		});
	</script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-76090840-1', 'auto');
		ga('send', 'pageview');


	</script>
	<!-- /.Background Animation -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>