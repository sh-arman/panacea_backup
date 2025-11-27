<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('livecheckpro/livecheckpro.css?v1.0')); ?>">
    <title>Maxpro Mups | QR Verificaton</title>
<style>
  .btnverify {
      font-size: 16px;
      font-weight: 600;
      color: #000000;
      text-align: center;
      width: 120px;
      padding: 8px;
      border: 0px;
      border-radius: 100px;
      background-color: #fc924c;
      /* background: #FC924C; */
      /* background-color: linear-gradient(356.3deg, #FC924C 31.69%, #FFFFFF 208.06%); */
  }

  .mark {
      width: 30%;
      height: auto;
      background-color: transparent;
      padding-bottom: 1rem;
  }

  .live-check {
      width: 40%;
      height: auto;
      background-color: transparent;
      padding-bottom: 1rem;
  }

  .info p {
      font-size: 15px !important;
      font-weight: 500;
      line-height: 0.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 1.3rem 0rem !important;
  }

  .expire {
      margin: 0 auto !important;
      padding: 10px;
      width: 50%;
      border-radius: 10px;
      background-color: #fee93b;
  }
</style>
</head>


<body style="height: 100% !important;">
    
    <div class="hero" style="height: 25% !important;">
        <img class="hero-img" src="<?php echo e(asset('livecheckpro/asset/circular_background.svg')); ?>" alt="Background_image">
        <div class="hero-items">
        <?php if($response): ?>
            <?php if($response['status'] == 'invalid code'): ?>
                <img class="mark" src="<?php echo e(asset('livecheckpro/asset/incorrect.svg')); ?>">
            <?php elseif($response['status'] == 'already verified'): ?>
                <img class="mark" src="<?php echo e(asset('livecheckpro/asset/tick.svg')); ?>">
            <?php elseif($response['status'] == 'verified first time'): ?>
                <img class="mark" src="<?php echo e(asset('livecheckpro/asset/tick.svg')); ?>">
            <?php elseif($response['status'] == 'expired'): ?>
                <img class="mark" style="width: 15%;" src="<?php echo e(asset('livecheckpro/asset/warning.svg')); ?>">
            <?php elseif($response['status'] == 'wrong number'): ?>
                <img class="mark" src="<?php echo e(asset('livecheckpro/asset/incorrect.svg')); ?>">
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>


    <div class="content mb-4">
        <div class="container">
          
          <div class="row justify-content-center">
              <div class="content-box" style="padding-top: 2% !important;">
                <?php if($response): ?>
                    <?php if($response['status'] == 'invalid code'): ?>
                        <h4><?php echo e(trans('literature.wrong-code')); ?></h4>
                        <p><?php echo e(trans('literature.non-verified-sub-heading')); ?></p>

                    <?php elseif($response['status'] == 'already verified'): ?>
                        <h4><?php echo e(trans('literature.verified-heading')); ?></h4>

                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title"><?php echo e(trans('literature.info-Manufacturer')); ?>: </span> &nbsp;  <?php echo e($response['info']['manufacturer']); ?>

                          </p>

                          <p id="productDosage">
                            <span class="bold-title"><?php echo e(trans('literature.info-medicine-Name')); ?>: </span> &nbsp;  <?php echo e($response['info']['product']); ?>&nbsp;<?php echo e($response['info']['dosage']); ?>

                          </p>

                          <p id="mfg">
                            <span class="bold-title"><?php echo e(trans('literature.info-Manufacturing-Date')); ?>: </span> &nbsp;  <?php echo e($response['info']['mfg']); ?>

                          </p>

                          <p id="expiry">
                            <span class="bold-title"><?php echo e(trans('literature.info-Expiry-Date')); ?>: </span> &nbsp;  <?php echo e($response['info']['expiry']); ?>

                          </p>
                            
                          

                        </div>

                        <div id="warningMsg">
                          <div class="warning" style="width: 90% !important;">
                            <p style="font-size: .9rem !important;"><?php echo e(trans('literature.warning-paragraph')); ?></p>
                            <img src="<?php echo e(asset('livecheckpro/asset/warning.svg')); ?>">
                          </div>

                          <div class="info">
                            <p id="totalCount">
                              <span class="bold-title"><?php echo e(trans('literature.previous-number')); ?>: </span> &nbsp;  <?php echo e($response['info']['preNumber']); ?>

                            </p>
                            <p id="totalCount">
                              <span class="bold-title"><?php echo e(trans('literature.auth-date')); ?>: </span> &nbsp;  <?php echo e($response['info']['preDate']); ?>

                            </p>
                            <p id="totalCount">
                              <span class="bold-title"><?php echo e(trans('literature.verification-count')); ?>: </span> &nbsp;  <?php echo e($response['info']['totalCount']); ?>

                            </p>
                          </div>
                        </div>

                    <?php elseif($response['status'] == 'verified first time'): ?>
                        <h4><?php echo e(trans('literature.verified-heading')); ?></h4>

                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title"><?php echo e(trans('literature.info-Manufacturer')); ?>: </span> &nbsp;  <?php echo e($response['info']['manufacturer']); ?>

                          </p>

                          <p id="productDosage">
                            <span class="bold-title"><?php echo e(trans('literature.info-medicine-Name')); ?>: </span> &nbsp;  <?php echo e($response['info']['product']); ?>&nbsp;<?php echo e($response['info']['dosage']); ?>

                          </p>

                          <p id="mfg">
                            <span class="bold-title"><?php echo e(trans('literature.info-Manufacturing-Date')); ?>: </span> &nbsp;  <?php echo e($response['info']['mfg']); ?>

                          </p>

                          <p id="expiry">
                            <span class="bold-title"><?php echo e(trans('literature.info-Expiry-Date')); ?>: </span> &nbsp;  <?php echo e($response['info']['expiry']); ?>

                          </p>
                            
                          
                        </div>
                    
                    <?php elseif($response['status'] == 'expired'): ?>
                        <h4><?php echo e(trans('literature.expired-medicine')); ?></h4>
                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title"><?php echo e(trans('literature.info-Manufacturer')); ?>: </span> &nbsp;  <?php echo e($response['info']['manufacturer']); ?>

                          </p>

                          <p id="productDosage">
                            <span class="bold-title"><?php echo e(trans('literature.info-medicine-Name')); ?>: </span> &nbsp;  <?php echo e($response['info']['product']); ?>&nbsp;<?php echo e($response['info']['dosage']); ?>

                          </p>

                          <p id="mfg">
                            <span class="bold-title"><?php echo e(trans('literature.info-Manufacturing-Date')); ?>: </span> &nbsp;  <?php echo e($response['info']['mfg']); ?>

                          </p>

                          <p id="expiry">
                            <span class="bold-title"><?php echo e(trans('literature.info-Expiry-Date')); ?>: </span> &nbsp;  <?php echo e($response['info']['expiry']); ?>

                          </p>
                            
                          
                        </div>
                        <div id="warningMsg" class="mb-3 text-center">
                          <div class="warning" style="width: 90% !important;">
                            <p style="font-size: .9rem !important;"><?php echo e(trans('literature.expired-info')); ?></p>
                          </div>
                        </div>
                    <?php elseif($response['status'] == 'wrong number'): ?>
                        <h4><?php echo e(trans('literature.wrong-phone')); ?></h4>
                        <p><?php echo e(trans('literature.lebel-phone')); ?></p>
                    <?php else: ?> 
                        <h4><?php echo e(trans('literature.wrong-code')); ?></h4>
                        <p><?php echo e(trans('literature.non-verified-sub-heading')); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
              </div>
          </div>
          <div class="row justify-content-center mb-4">
            <a href="<?php echo e(route('mups')); ?>">
              <button type="submit" id="donebtn" class="btnverify" ><?php echo e(trans('literature.button-done')); ?></button>
            </a>
          </div>
        </div>
    </div>


    
    <div class="container mt-4">
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


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<?php echo $__env->yieldContent('script'); ?>
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
</body>
</html>