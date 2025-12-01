<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo e($page_title); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $__env->startSection('styles'); ?>
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo e(asset('panel/css/login.css')); ?>">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <?php echo $__env->yieldSection(); ?>
</head>
<body>

<div class="is-visible"><!-- log in form -->

    <div id="session_msg">

    </div>

    <?php if(!empty(session()->get('message'))): ?>
        <div class="alert alert-success">
            <p><?php echo e(session('message')); ?></p>
        </div>
    <?php endif; ?>
    <div id="login">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div id="login_div" style="display: block">
                        <h3 style="text-align: center; text-transform: capitalize"><?php echo e($company); ?> </h3>
                        <br>
                        <p id="error_msg"></p>
                        <div class="form-group">
                            <p class="fieldset">
                                <input type="text" name="phone_number" placeholder="Phone Number"
                                       class="form-control" required="">
                            </p>
                            <meta name="csrf-token2" content="<?php echo e(csrf_token()); ?>">
                        </div>
                        <button id="login_button" type="button" class="btn btn-primary">Login</button>
                    </div>

                        <div id="verify_div" style="display: none">
                            <h4 id="checkMessage" style="text-align: center">
                                A  code has been sent to your phone number.
                                
                            </h4>
                            <h4><a id="resend_code" href="#">Resend code</a></h4>

                            <div class="form-group">
                                <input name="verification_code" type="text" placeholder="Enter Code"
                                       class="form-control">
                            </div>

                            <input type="hidden" id="userid" name="id" value="<?php echo e(session()->get('id')); ?>">
                            <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
                            <meta name="csrf-token_verify" content="<?php echo e(csrf_token()); ?>">
                            <button id="verify_button" type="button" class="btn btn-primary">Login</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo e(asset('js/generationpanel.js')); ?>"></script>
<script>
    $("#resend_code").click(function(){
        console.log('haha');
        var id =  $("input[name='id']").val();
        var company =  $("input[name='company_name']").val();

        /*  $.ajax({
         url: "/your/url",
         method: "POST",
         data:
         {
         id: id,
         company: company,
         _token: $('meta[name="csrf-token"]').attr('content')
         },
         datatype: "json"
         }); */

        $.post("/resend", {id: id,_token: "<?php echo e(csrf_token()); ?>"}, function (data) {
            var a = data;
            $("#session_msg").html(a);
        });
    });
</script>
</body>
</html>