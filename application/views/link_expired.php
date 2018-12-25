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
        <div class="col-md-12" id="alert_note" style="display: none;">
                                <?php
                                    $this->load->helper('form');
                                    $error = $this->session->flashdata('error');
                                    if($error)
                                    {
                                ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <?php echo $this->session->flashdata('error'); ?>                    
                                </div>
                                <?php } ?>
                                <?php  
                                    $success = $this->session->flashdata('success');
                                    if($success)
                                    {
                                ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                                <?php } ?>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                                    </div>
                                </div>
            </div>
        <!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
        <div id="wrap" class="animsition">

            <div class="page page-core page-login" ><!-- style="background-color:#ddd;" -->

                <div class="text-center"><img class="img-rounded" src="<?php echo base_url('admin_resource');?>/assets/images/logo.png" alt="" style="width: 125px;"></div>

                 <div class="text-center"><h3 class="text-light text-white"><span class="text-lightred">BOWLING</span> EXPRESS PAY</h3></div> 

                <div class="container w-420 p-15 bg-white mt-40 text-center">

                    <h2 class="text-light text-greensea">Link <strong>Expired</strong></h2>

                    <h4 class="mb-0 mt-40">Whoops, the provided link is got expired!</h4>

                    <p class="text-muted">If you want to reset password you have to click try again... :-(</p>

                    <form class="mt-40 ng-pristine ng-valid">
                      <div class="input-group w-md m-auto"><!-- 
                        <input type="text" class="form-control" placeholder="search..."> -->
                        <!-- <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </span> -->
                      </div>
                      <!-- /input-group -->
                    </form>
                    <div class="bg-slategray lt wrap-reset mt-40 text-center">
                    <a href="<?php echo base_url()?>login/forget_password" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Try again</a>
                     <a href="<?php echo base_url()?>login" class="btn btn-greensea btn-sm b-0"><i class="fa fa-home"></i> Return to home</a>
                     <a href="#" class="btn btn-lightred btn-sm b-0"><i class="fa fa-envelope-o"></i> Contact</a>

                  </div>
                    <!-- <hr class="b-3x">  -->                  
                                                   

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



        <!-- ===============================================
        ============== Page Specific Scripts ===============
        ================================================ -->
        
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
    