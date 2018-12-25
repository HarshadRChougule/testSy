<?php  //echo $this->uri->segment(2);//exit; ?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->



    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>BXP</title>
        <link rel="icon" type="image/ico" href="assets/images/favicon.ico" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">




        <!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
        <!-- vendor css files -->
        <link rel="stylesheet" href="<?php echo base_url('admin_resource');?>/assets/css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url('admin_resource');?>/assets/css/vendor/animate.css">
        <link rel="stylesheet" href="<?php echo base_url('admin_resource');?>/assets/css/vendor/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url('admin_resource');?>/assets/js/vendor/animsition/css/animsition.min.css">

        <!-- project main css files -->
        <link rel="stylesheet" href="<?php echo base_url('admin_resource');?>/assets/css/main.css">
        <!--/ stylesheets -->

       

        <!-- ==========================================
        ================= Modernizr ===================
        =========================================== -->
        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>

        <!--/ modernizr -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
         <!-- form validation -->

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/parsley/parsley.min.js"></script>
        <script src="<?php echo base_url('admin_resource');?>/assets/js/main.js"></script>

        <!-- form validation -->


    </head>





    <body id="minovate" class="appWrapper">

        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
        <div id="wrap" class="animsition">

            <div class="page page-core page-login" ><!-- style="background-color:#ddd;" -->

                <div class="text-center"><img class="img-rounded" src="<?php echo base_url('admin_resource');?>/assets/images/logo.png" alt="" style="width: 125px;"></div>

                 <div class="text-center"><h3 class="text-light text-white"><span class="text-lightred">BOWLING</span> EXPRESS PAY</h3></div> 

                <div class="container w-420 p-15 bg-white mt-40 text-center">


                    <h2 class="text-light text-greensea">Reset Password</h2>

                    <form name="form" class="form-validation mt-20" action="<?php echo base_url()?>index.php/auth/set_new_password" novalidate="" novalidate="" method="post" data-parsley-validate>
                        <input type="hidden" name="user_id" value="<?php echo $this->uri->segment(2);?>">
                        <div class="form-group">
                            <input type="password" class="form-control underline-input" name="newPassword" id="newPassword" placeholder="New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}(?=.[^0-9]*)" required>
                            <span class="text-muted"><small>[Password should be contain <mark>Minimum 6 character</mark>, Combination of At least <mark>one capital letter</mark>, at least <mark>one special character</mark>, At least <mark>one number</mark>.]</small></span>
                        </div>

                        <div class="form-group">
                            <input type="password" placeholder="Confirm New Password" name="cNewPassword" class="form-control underline-input" data-parsley-equalto="#newPassword" required>
                        </div>

                        <div class="form-group text-left mt-20">
                            <input type="submit" name="submit" value="UPDATE" class="btn btn-greensea b-0 br-2 mr-5">
                        </div>
                    </form>
                    <hr class="b-3x">

                    <div class="social-login text-left">

                        <ul class="pull-right list-unstyled list-inline">
                            <li class="p-0">
                                <a class="btn btn-sm btn-primary b-0 btn-rounded-20" href="https://www.facebook.com/" target="blank"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li class="p-0">
                                <a class="btn btn-sm btn-info b-0 btn-rounded-20" href="https://twitter.com/login" target="blank"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li class="p-0">
                                <a class="btn btn-sm btn-lightred b-0 btn-rounded-20" href="https://plus.google.com" target="blank"><i class="fa fa-google-plus"></i></a>
                            </li>
                            <li class="p-0">
                                <a class="btn btn-sm btn-primary b-0 btn-rounded-20" href="https://in.linkedin.com/" target="blank"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>

                        <h5>Find us at</h5>

                    </div>
                    <!-- <hr class="b-3x">  -->                  
                </div>

            </div>



        </div>
        <!--/ Application Content -->



        <!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/bootstrap/bootstrap.min.js"></script>

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/jRespond/jRespond.min.js"></script>

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/sparkline/jquery.sparkline.min.js"></script>

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/animsition/js/jquery.animsition.min.js"></script>

        <script src="<?php echo base_url('admin_resource');?>/assets/js/vendor/screenfull/screenfull.min.js"></script>
        <!--/ vendor javascripts -->



        <!-- ============================================
        ============== Custom JavaScripts ===============
        ============================================= -->
        <script src="<?php echo base_url('admin_resource');?>/assets/js/main.js"></script>
        <!--/ custom javascripts -->






        <!-- ===============================================
        ============== Page Specific Scripts ===============
        ================================================ -->
        <script>
            $(window).load(function(){
                $("#alert_note").css("display", "block");
                $("#alert_note").slideUp(4000);
            });
        </script>
        <!--/ Page Specific Scripts -->





        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');


        </script>

    </body>
</html>
    