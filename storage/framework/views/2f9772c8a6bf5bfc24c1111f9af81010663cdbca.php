<!-- Navigation Menu Bar -->
<style>
.dropdown .dropbtn {
  font-size: 16px;  
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 0;
}

.dropdown-content {
  display: none;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {
  background-color: #ddd;
}

.dropdown:hover .dropdown-content {
  display: block;
}
</style>


<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="topnav">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".top-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(asset('frontend/images/logo.PNG')); ?>" alt="Panacea Live Logo">
            </a>
        </div>
        <div class="collapse navbar-collapse top-navbar">
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <div class="dropdown">
                        <button class="dropbtn">
                            SERVICES
                            <img style="max-width:15px;margin-left:5px;" src="<?php echo e(asset('frontend/images/dropdown.png')); ?>">
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo e(route('home')); ?>">Live Check</a>
                            <a href="#">Live Warranty<br/><span style="color:red;font-size:15px;">Coming Soon!</span></a>
                        </div>
                    </div> 
                </li>
                <li><a href="<?php echo e(route('report')); ?>">Report</a></li>
                <li><a href="<?php echo e(route('home')); ?>#brands">Brands</a></li>
                <li><a href="<?php echo e(route('press')); ?>">Press</a></li>
                <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(\Sentinel::check()): ?>
                    <li>
                        <a href="<?php echo e(route('logout')); ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-modal">
                        <a href="#" data-toggle="modal" id="signUpBtn" data-target="#login-modal">Sign Up</a>
                    </li>
                    <li class="nav-modal">
                        <a href="#" data-toggle="modal" id="loginBtn" data-target="#login-modal">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- BEGIN # MODAL LOGIN -->

<!-- Need To Add Error Messages -->

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    &#10005;
                </button>
            </div>

            <!-- Begin # DIV Form -->
            <div id="div-forms">

                <!-- Begin # Login Form -->
                <form id="login-form" style="display:none;">

                    <div class="modal-body">
                        <div class="alert">
                        </div>
                        <div id="div-switch">
                            <span style="float:left;">Login</span>
                        </div>
                        <input id="login_phone" class="form-control" type="tel" placeholder="Phone Number" required>
                        <input id="login_password" class="form-control" type="password" placeholder="Password" required>
                        <div>
                            <button id="login_lost_btn" type="button" class="btn btn-link">Forgot Password?</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                        </div>
                        <div style="margin-top:10px;text-align:center">
                            <a id="login_register_btn" href="javascript:void()" class="changeModalBody" role="button">Do not have an accout? Sign Up!</a>
                        </div>
                    </div>
                </form>
                <!-- End # Login Form -->

                <!-- Begin | Lost Password Form -->
                <form id="lost-form" style="display:none;">
                    <div class="modal-body">
                        <div class="alert">
                        </div>
                        <div id="div-switch">
                            <span style="float:left;">Enter Your Phone Number</span>
                            <div style="float:right;">
                                <a id="lost_login_btn" class="btn btn-default" href="javascript:void()" role="button">Login</a>
                                <a id="lost_register_btn" class="btn btn-default" href="javascript:void()" role="button">Sign Up</a>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                        <input id="reset_phone_number" class="form-control" type="tel" placeholder="Phone Number" required>
                        <input id="reset_code" class="form-control" type="text" placeholder="Reset Code" >
                        <input id="reset_password" class="form-control" type="password" placeholder="New Password" >

                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                        </div>
                    </div>
                </form>
                <!-- End | Lost Password Form -->

                <!-- Begin | Register Form -->
                <form id="register-form" style="display:none;">
                    <div class="modal-body">
                        <div class="alert">
                        </div>
                        <div id="div-switch">
                            <span style="float:left;">Sign Up</span>
                        </div>
                        <input id="phone_number" class="form-control" type="tel" placeholder="Phone Number" required>
                        <input id="password" class="form-control" type="password" placeholder="Password" required>
                        <table style="margin-top:10px">
                            <tr>
                                <td>
                                    <label class="termsCheck" style="margin-top:10px">
                                        <input type="checkbox" id="terms" class="regular-checkbox big-checkbox" required><span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                    I agree to Panacea Live Ltd.'s 
                                    <a href="<?php echo e(route('legal')); ?>" target="_blank"  style="color:#2e53b9;">
                                        Liability Limitation Clauses, Privacy Policy and Terms of Service
                                    </a>
                                    <label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" id="registration-button" class="btn btn-primary btn-lg btn-block" disabled="disabled">Sign Up</button>
                        </div>
                        <div style="margin-top:10px;text-align:center">
                                <a id="register_login_btn" href="javascript:void()" class="changeModalBody" role="button">Already have an account? Login!</a>
                        </div>
                    </div>
                </form>
                <!-- End | Register Form -->

                <!--Activation form-->
                <form id="activation-form" style="display:none;">
                    <div class="alert">
                    </div>
                    <p class="fieldset">
                        <label>Enter the authentication code that has been sent to your number.</label>
                        <input class="form-control" id="code" type="text" placeholder="Authentication Code" required>
                        <input id="authcode" type="hidden">
                    </p>

                    <center>
                        <p class="fieldset" style="padding-top:8px">
                            <button class="create-my-account btn btn-primary btn-lg btn-block" style="padding:5px 25px;" type="submit">
                                Create My Account
                            </button>
                        </p>
                    </center>
                    <br><br>
                </form>
                <!--end of activation-->
            </div>
            <!-- End # DIV Form -->
        </div>
    </div>
</div>

